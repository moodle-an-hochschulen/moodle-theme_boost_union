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
 * Theme Boost Union - Tests for the flag to purge the smart menus session cache.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class smartmenu_purgesessioncache_test extends advanced_testcase {
    /**
     * Data provider for test_purgesessioncache_flag_is_reset().
     *
     * @return array
     */
    public static function role_change_provider(): array {
        return [
            'Role assigned to user' => ['assigned'],
            'Role unassigned from user' => ['unassigned'],
        ];
    }

    /**
     * Test that the flag to purge the smart menus session cache is set when the roles of a user change
     * and that it is removed again (and not just reset to 0) after the smart menus have been built for this user.
     *
     * @covers \theme_boost_union\eventobservers::role_assigned
     * @covers \theme_boost_union\eventobservers::role_unassigned
     * @covers \theme_boost_union\smartmenu_helper::set_user_purgecache
     * @covers \theme_boost_union\smartmenu_helper::clear_user_cachepreferencemenu
     * @covers \theme_boost_union\smartmenu::build_smartmenu
     * @dataProvider role_change_provider
     *
     * @param string $change The role change ('assigned' or 'unassigned').
     * @return void
     */
    public function test_purgesessioncache_flag_is_reset(string $change): void {
        global $CFG, $DB, $PAGE;

        require_once($CFG->dirroot . '/theme/boost_union/smartmenus/menulib.php');

        $this->resetAfterTest();

        // Create a user, a course and a smart menu.
        $user = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();
        $coursecontext = \context_course::instance($course->id);
        $studentroleid = $DB->get_field('role', 'id', ['shortname' => 'student']);
        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $generator->create_smartmenu(['title' => 'Smart menu 1', 'location' => [smartmenu::LOCATION_MAIN]]);

        // If the role should be unassigned from the user, assign it first.
        if ($change === 'unassigned') {
            role_assign($studentroleid, $user->id, $coursecontext->id);
        }

        // Build the smart menus for the user once, as it is done on every page load.
        // This sets the flag as well (as the session language is detected for the first time) and clears it again.
        $this->setUser($user);
        $PAGE->set_url('/my/');
        $PAGE->set_course(get_site());
        smartmenu::build_smartmenu();

        // Afterwards, there must not be any flag left behind in the database (not even one which is reset to 0).
        $params = ['userid' => $user->id, 'name' => 'theme_boost_union_menu_purgesessioncache'];
        $this->assertFalse($DB->record_exists('user_preferences', $params));

        // Change the role of the user.
        if ($change === 'assigned') {
            role_assign($studentroleid, $user->id, $coursecontext->id);
        } else {
            role_unassign($studentroleid, $user->id, $coursecontext->id);
        }

        // Now, the flag must be set.
        $this->assertEquals(1, $DB->get_field('user_preferences', 'value', $params));

        // Build the smart menus for the user again.
        smartmenu::build_smartmenu();

        // Afterwards, the flag must be removed again, both from the database and from the user's preferences.
        $this->assertFalse($DB->record_exists('user_preferences', $params));
        $this->assertNull(get_user_preferences('theme_boost_union_menu_purgesessioncache'));
    }
}
