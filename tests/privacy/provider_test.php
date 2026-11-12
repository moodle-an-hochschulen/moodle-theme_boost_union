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

namespace theme_boost_union\privacy;

use context_user;
use core_privacy\local\metadata\collection;
use core_privacy\local\metadata\types\user_preference;
use core_privacy\local\request\writer;

/**
 * Theme Boost Union - Privacy provider tests.
 *
 * @package    theme_boost_union
 * @category   test
 * @coversDefaultClass \theme_boost_union\privacy\provider
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class provider_test extends \core_privacy\tests\provider_testcase {
    /**
     * Setup the test.
     *
     * @return void
     */
    protected function setUp(): void {
        global $CFG;

        parent::setUp();

        require_once($CFG->dirroot . '/theme/boost_union/lib.php');

        $this->resetAfterTest();
    }

    /**
     * Test that all user preferences are declared in the metadata.
     *
     * @covers ::get_metadata
     *
     * @return void
     */
    public function test_get_metadata(): void {
        $collection = provider::get_metadata(new collection('theme_boost_union'));

        // Collect the declared user preferences.
        $preferences = [];
        foreach ($collection->get_collection() as $item) {
            $this->assertInstanceOf(user_preference::class, $item);
            $preferences[$item->get_name()] = $item->get_summary();
        }

        // Build the expected user preferences.
        $expected = [];
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT; $i++) {
            $expected['theme_boost_union_infobanner' . $i . '_dismissed'] = 'privacy:metadata:preference:infobannerdismissed';
        }
        $expected['theme_boost_union_flavours_purgesessioncache'] = 'privacy:metadata:preference:flavourspurgesessioncache';
        $expected['theme_boost_union_menu_purgesessioncache'] = 'privacy:metadata:preference:menupurgesessioncache';

        $this->assertEquals($expected, $preferences);
    }

    /**
     * Data provider for test_export_user_preferences().
     *
     * @return array
     */
    public static function export_user_preferences_provider(): array {
        return [
            'Info banner 2 dismissed' => [
                'theme_boost_union_infobanner2_dismissed',
                'privacy:infobannerdismissed',
                2,
            ],
            'Flavours cache purge flag set' => [
                'theme_boost_union_flavours_purgesessioncache',
                'privacy:flavourspurgesessioncache',
                null,
            ],
            'Smart menus cache purge flag set' => [
                'theme_boost_union_menu_purgesessioncache',
                'privacy:menupurgesessioncache',
                null,
            ],
        ];
    }

    /**
     * Test that a set user preference is exported with its description.
     *
     * @covers ::export_user_preferences
     * @dataProvider export_user_preferences_provider
     *
     * @param string $preference The name of the user preference.
     * @param string $expectdescription The language string identifier of the expected description.
     * @param int|null $expectdescriptiona The parameter for the language string of the expected description.
     * @return void
     */
    public function test_export_user_preferences(string $preference, string $expectdescription, ?int $expectdescriptiona): void {
        // Create a user and set the user preference.
        $user = $this->getDataGenerator()->create_user();
        set_user_preference($preference, true, $user);

        // Export the user preferences.
        provider::export_user_preferences($user->id);
        $writer = writer::with_context(context_user::instance($user->id));
        $this->assertTrue($writer->has_any_data());

        // The export must contain exactly this user preference with its description.
        $exportedpreferences = $writer->get_user_preferences('theme_boost_union');
        $this->assertCount(1, (array) $exportedpreferences);
        $this->assertEquals('1', $exportedpreferences->{$preference}->value);
        $this->assertEquals(
            get_string($expectdescription, 'theme_boost_union', $expectdescriptiona),
            $exportedpreferences->{$preference}->description
        );
    }

    /**
     * Test that nothing is exported if the user does not have any user preferences of the theme.
     *
     * @covers ::export_user_preferences
     *
     * @return void
     */
    public function test_export_user_preferences_without_preferences(): void {
        // Create a user without any user preferences of the theme.
        $user = $this->getDataGenerator()->create_user();

        // Export the user preferences.
        provider::export_user_preferences($user->id);
        $writer = writer::with_context(context_user::instance($user->id));

        // The export must not contain anything.
        $this->assertFalse($writer->has_any_data());
    }
}
