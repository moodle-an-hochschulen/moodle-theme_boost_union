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
use core_date;

/**
 * Theme Boost Union - Tests for the admin_setting_configdatetime class.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class admin_setting_configdatetime_test extends advanced_testcase {
    /**
     * Require the admin library which provides the admin setting base class and the theme library which provides
     * the theme constants.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void {
        global $CFG;

        require_once($CFG->libdir . '/adminlib.php');
        require_once($CFG->dirroot . '/theme/boost_union/lib.php');

        parent::setUpBeforeClass();
    }

    /**
     * Test that a default setting without date and time is stored, so that the setting is not considered as new setting
     * anymore.
     *
     * @covers \theme_boost_union\admin_setting_configdatetime::write_setting
     * @covers \theme_boost_union\admin_setting_configdatetime::get_setting
     *
     * @return void
     */
    public function test_write_unset_default_setting(): void {
        $this->resetAfterTest();

        $setting = new admin_setting_configdatetime('theme_boost_union/configdatetimetest', 'Test', 'Test', 0);

        // Initially, the setting is not set and thus considered as new setting by Moodle core.
        $this->assertNull($setting->get_setting());

        // Apply the default setting, just like admin_apply_default_settings() does it.
        $this->assertSame('', $setting->write_setting($setting->get_defaultsetting()));

        // The default setting has been stored as 0.
        $this->assertSame('0', get_config('theme_boost_union', 'configdatetimetest'));

        // The setting is not considered as new setting anymore, but no date and time is set.
        $this->assertSame(0, $setting->get_setting());

        // A non-numeric default setting (like an empty string) is stored as 0 as well.
        unset_config('configdatetimetest', 'theme_boost_union');
        $this->assertSame('', $setting->write_setting(''));
        $this->assertSame('0', get_config('theme_boost_union', 'configdatetimetest'));
        $this->assertSame(0, $setting->get_setting());
    }

    /**
     * Test that a numeric default setting is stored as unix timestamp.
     *
     * @covers \theme_boost_union\admin_setting_configdatetime::write_setting
     * @covers \theme_boost_union\admin_setting_configdatetime::get_setting
     *
     * @return void
     */
    public function test_write_numeric_default_setting(): void {
        $this->resetAfterTest();

        $setting = new admin_setting_configdatetime('theme_boost_union/configdatetimetest', 'Test', 'Test', 1794032820);

        // Initially, the setting is not set.
        $this->assertNull($setting->get_setting());

        // Apply the default setting, just like admin_apply_default_settings() does it.
        $this->assertSame('', $setting->write_setting($setting->get_defaultsetting()));

        // The default setting has been stored as unix timestamp.
        $this->assertSame('1794032820', get_config('theme_boost_union', 'configdatetimetest'));
        $this->assertSame(1794032820, $setting->get_setting());
    }

    /**
     * Test that the not-before check is applied to a non-array value as well, as such a value might also have been
     * submitted with a hand-crafted request to the admin settings form.
     *
     * @covers \theme_boost_union\admin_setting_configdatetime::write_setting
     * @covers \theme_boost_union\admin_setting_configdatetime::set_not_before
     *
     * @return void
     */
    public function test_write_non_array_value_respects_not_before(): void {
        $this->resetAfterTest();

        // The other setting holds a date and time already.
        set_config('configdatetimeteststart', 1794032820, 'theme_boost_union');

        $setting = new admin_setting_configdatetime('theme_boost_union/configdatetimetest', 'Test', 'Test', 0);
        $setting->set_not_before('configdatetimeteststart', 'Not before error');

        // A timestamp before the other setting's date and time is rejected and not stored.
        $this->assertSame('Not before error', $setting->write_setting(1794032820 - HOURSECS));
        $this->assertFalse(get_config('theme_boost_union', 'configdatetimetest'));

        // A timestamp after the other setting's date and time is stored.
        $this->assertSame('', $setting->write_setting(1794032820 + HOURSECS));
        $this->assertSame(1794032820 + HOURSECS, $setting->get_setting());

        // The value 0 is stored without being checked, as no date and time is set then.
        $this->assertSame('', $setting->write_setting(0));
        $this->assertSame('0', get_config('theme_boost_union', 'configdatetimetest'));
    }

    /**
     * Test that the default settings of the info banner start and end time settings are stored when Moodle core applies
     * the default settings and that they are not applied again afterwards.
     *
     * @covers \theme_boost_union\admin_setting_configdatetime::write_setting
     *
     * @return void
     */
    public function test_apply_default_settings_only_once(): void {
        $this->resetAfterTest();
        $this->setAdminUser();

        // Pretend that the info banner start and end time settings have never been stored.
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT; $i++) {
            unset_config('infobanner' . $i . 'start', 'theme_boost_union');
            unset_config('infobanner' . $i . 'end', 'theme_boost_union');
        }

        // Apply the default settings for the first time, just like the Moodle core upgrade does it.
        $node = admin_get_root(true, true)->locate('theme_boost_union');
        $saved = admin_apply_default_settings($node, false);

        // All info banner start and end time settings have been handled as new settings.
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT; $i++) {
            $this->assertArrayHasKey('theme_boost_union/infobanner' . $i . 'start', $saved);
            $this->assertArrayHasKey('theme_boost_union/infobanner' . $i . 'end', $saved);
            $this->assertSame('0', get_config('theme_boost_union', 'infobanner' . $i . 'start'));
            $this->assertSame('0', get_config('theme_boost_union', 'infobanner' . $i . 'end'));
        }

        // Apply the default settings for the second time.
        $node = admin_get_root(true, true)->locate('theme_boost_union');
        $saved = admin_apply_default_settings($node, false);

        // There are not any new settings anymore.
        $this->assertSame([], $saved);
        $this->assertFalse(any_new_admin_settings($node));
    }

    /**
     * Test that a submitted date and time is still stored as unix timestamp.
     *
     * @covers \theme_boost_union\admin_setting_configdatetime::write_setting
     * @covers \theme_boost_union\admin_setting_configdatetime::get_setting
     *
     * @return void
     */
    public function test_write_submitted_date_and_time(): void {
        $this->resetAfterTest();

        $setting = new admin_setting_configdatetime('theme_boost_union/configdatetimetest', 'Test', 'Test', 0);

        // Submit a date and time.
        $data = ['year' => 2026, 'month' => 11, 'day' => 24, 'hour' => 10, 'minute' => 30];
        $this->assertSame('', $setting->write_setting($data));

        // The date and time has been stored as unix timestamp, interpreted as server time.
        $expected = make_timestamp(2026, 11, 24, 10, 30, 0, core_date::get_server_timezone());
        $this->assertSame($expected, $setting->get_setting());
    }
}
