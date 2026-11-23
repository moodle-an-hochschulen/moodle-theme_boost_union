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

/**
 * Theme Boost Union - Tests for the check if the smart menu tables exist already.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class smartmenu_tables_exist_test extends advanced_testcase {
    /**
     * Test that the existence of the smart menu tables is derived from the installed plugin version.
     *
     * @covers \theme_boost_union\smartmenu::tables_exist
     *
     * @return void
     */
    public function test_tables_exist(): void {
        $this->resetAfterTest();

        // With the currently installed plugin version, the tables exist.
        $this->assertTrue(smartmenu::tables_exist());

        // With the plugin version of the upgrade step which created the tables, the tables exist.
        set_config('version', smartmenu::TABLES_SINCE_VERSION, 'theme_boost_union');
        $this->assertTrue(smartmenu::tables_exist());

        // With a plugin version before the upgrade step which created the tables, the tables do not exist.
        set_config('version', smartmenu::TABLES_SINCE_VERSION - 1, 'theme_boost_union');
        $this->assertFalse(smartmenu::tables_exist());

        // Without any installed plugin version, the tables do not exist.
        unset_config('version', 'theme_boost_union');
        $this->assertFalse(smartmenu::tables_exist());
    }

    /**
     * Test that the FontAwesome icons of the smart menu items are not fetched from the database
     * as long as the smart menu tables do not exist yet.
     *
     * @covers \theme_boost_union\smartmenu_item::get_all_fa_icons
     *
     * @return void
     */
    public function test_get_all_fa_icons_without_tables(): void {
        global $DB;

        $this->resetAfterTest();

        // Pretend that the upgrade step which creates the smart menu tables has not been run yet.
        set_config('version', smartmenu::TABLES_SINCE_VERSION - 1, 'theme_boost_union');

        // Load the plugin config into the cache again, as it was purged by set_config()
        // and as reloading it would be counted as database query otherwise.
        get_config('theme_boost_union');

        // Get the icons and verify that the database was not queried at all.
        $readsbefore = $DB->perf_get_reads();
        $icons = smartmenu_item::get_all_fa_icons();
        $this->assertSame([], $icons);
        $this->assertEquals($readsbefore, $DB->perf_get_reads());
    }
}
