<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace theme_boost_union;

use advanced_testcase;
use theme_boost_union\util\course_settings_transfer;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/theme/boost_union/lib.php');

/**
 * Tests for the validation of course-specific setting values.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class coursesettings_test extends advanced_testcase {
    /**
     * A value which breaks out of a style attribute if it is printed unescaped.
     */
    private const MALICIOUSVALUE = '100px;" onmouseover="alert(1)" data-x="';

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();

        // Reset the database and the configuration after each test.
        $this->resetAfterTest();
    }

    /**
     * Test that options are known for each and every course setting.
     *
     * coursesettings::get_course_settings_config() fetches its options from coursesettings::get_course_setting_options().
     * If this test fails, a course setting was added to the former without mapping its options in the latter.
     *
     * @covers \theme_boost_union\coursesettings::get_course_setting_options
     *
     * @return void
     */
    public function test_options_exist_for_all_course_settings(): void {
        foreach (coursesettings::get_course_settings_config() as $name => $config) {
            $options = coursesettings::get_course_setting_options($name, true);
            $this->assertIsArray($options, 'No options known for course setting ' . $name);

            // The options offered in the course form must be the known options plus the "Use global default" option.
            $formoptions = $config['options'];
            unset($formoptions[THEME_BOOST_UNION_SETTING_USEGLOBAL]);
            $this->assertEquals(array_keys($formoptions), array_keys($options), 'Options differ for course setting ' . $name);
        }

        $this->assertNull(coursesettings::get_course_setting_options('doesnotexist'));
    }

    /**
     * Test the validation of course setting values.
     *
     * @dataProvider is_valid_course_setting_value_provider
     * @covers \theme_boost_union\coursesettings::is_valid_course_setting_value
     *
     * @param string $name The name of the setting.
     * @param mixed $value The value to check.
     * @param bool $expected The expected result.
     * @return void
     */
    public function test_is_valid_course_setting_value(string $name, $value, bool $expected): void {
        $this->assertSame($expected, coursesettings::is_valid_course_setting_value($name, $value));
    }

    /**
     * Data provider for test_is_valid_course_setting_value().
     *
     * @return array
     */
    public static function is_valid_course_setting_value_provider(): array {
        return [
            'Valid height' => ['courseheaderheight', '250px', true],
            'Unknown height' => ['courseheaderheight', '300px', false],
            'Malicious height' => ['courseheaderheight', self::MALICIOUSVALUE, false],
            'Valid image position' => ['courseheaderimageposition', 'right center', true],
            'Malicious image position' => ['courseheaderimageposition', 'center;" onmouseover="alert(1)', false],
            'Valid enabled' => ['courseheaderenabled', 'yes', true],
            'Use global is not a stored option' => ['courseheaderenabled', 'useglobal', false],
            'Valid layout' => ['courseheaderlayout', 'stacked', true],
            'Valid section appearance' => ['sectiononeplusappearance', 'notcollapsible', true],
            'Hidden is no option for sections 1+' => ['sectiononeplusappearance', 'hidden', false],
            'Empty value' => ['courseheaderheight', '', false],
            'Null value' => ['courseheaderheight', null, false],
            'Array value' => ['courseheaderheight', ['250px'], false],
            'Unknown setting' => ['doesnotexist', '250px', false],
        ];
    }

    /**
     * Test that values which are not an option of the setting are not transferred into a course.
     *
     * @covers \theme_boost_union\util\course_settings_transfer::transfer_course_setting
     *
     * @return void
     */
    public function test_transfer_course_setting_rejects_invalid_values(): void {
        global $DB;

        $course = $this->getDataGenerator()->create_course();

        // A malicious value must not be stored.
        course_settings_transfer::transfer_course_setting(
            ['courseid' => $course->id, 'name' => 'courseheaderheight', 'value' => self::MALICIOUSVALUE]
        );
        $this->assertFalse($DB->record_exists('theme_boost_union_course', ['courseid' => $course->id]));

        // A valid value must be stored.
        course_settings_transfer::transfer_course_setting(
            ['courseid' => $course->id, 'name' => 'courseheaderheight', 'value' => '250px']
        );
        $this->assertEquals('250px', $DB->get_field(
            'theme_boost_union_course',
            'value',
            ['courseid' => $course->id, 'name' => 'courseheaderheight']
        ));

        // A malicious value must not overwrite an existing valid value.
        course_settings_transfer::transfer_course_setting(
            ['courseid' => $course->id, 'name' => 'courseheaderheight', 'value' => self::MALICIOUSVALUE]
        );
        $this->assertEquals('250px', $DB->get_field(
            'theme_boost_union_course',
            'value',
            ['courseid' => $course->id, 'name' => 'courseheaderheight']
        ));
    }

    /**
     * Test that a value which is already stored in the database but which is not an option of the setting
     * is not returned as course override, but that the global setting is returned instead.
     *
     * @covers \theme_boost_union\coursesettings::get_config_with_course_override
     *
     * @return void
     */
    public function test_get_config_with_course_override_ignores_invalid_stored_values(): void {
        global $DB, $PAGE;

        set_config('courseheaderheight', '150px', 'theme_boost_union');
        set_config('courseheaderheight_courseoverride', 1, 'theme_boost_union');
        set_config('courseheaderimageposition', 'left top', 'theme_boost_union');
        set_config('courseheaderimageposition_courseoverride', 1, 'theme_boost_union');

        $course = $this->getDataGenerator()->create_course();
        $PAGE->set_course($course);
        $PAGE->set_pagelayout('course');

        // Write a malicious value directly into the database, as a restore did before the values were validated.
        $DB->insert_record(
            'theme_boost_union_course',
            (object)['courseid' => $course->id, 'name' => 'courseheaderheight', 'value' => self::MALICIOUSVALUE]
        );

        // And write a valid value for another setting.
        coursesettings::set_course_setting($course->id, 'courseheaderimageposition', 'right center');

        // The malicious value must be replaced by the global setting, on a cache miss as well as on a cache hit.
        $this->assertEquals('150px', coursesettings::get_config_with_course_override('courseheaderheight'));
        $this->assertEquals('150px', coursesettings::get_config_with_course_override('courseheaderheight'));

        // The valid value must be returned.
        $this->assertEquals('right center', coursesettings::get_config_with_course_override('courseheaderimageposition'));
    }
}
