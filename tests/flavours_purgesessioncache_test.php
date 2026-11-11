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
 * Theme Boost Union - Tests for the flag to purge the flavours session cache.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class flavours_purgesessioncache_test extends advanced_testcase {
    /**
     * Data provider for test_purgesessioncache_flag_is_reset().
     *
     * @return array
     */
    public static function cohort_membership_change_provider(): array {
        return [
            'User added to cohort' => ['added'],
            'User removed from cohort' => ['removed'],
        ];
    }

    /**
     * Test that the flag to purge the flavours session cache is set when the cohort membership of a user changes
     * and that it is removed again after the flavour which applies has been determined for this user.
     *
     * The test has to run in a separate process as theme_boost_union_get_flavour_which_applies() remembers
     * the flavour which applies in static variables.
     *
     * @covers \theme_boost_union\eventobservers::cohort_member_added
     * @covers \theme_boost_union\eventobservers::cohort_member_removed
     * @covers ::theme_boost_union_get_flavour_which_applies
     * @dataProvider cohort_membership_change_provider
     * @runInSeparateProcess
     *
     * @param string $change The cohort membership change ('added' or 'removed').
     * @return void
     */
    public function test_purgesessioncache_flag_is_reset(string $change): void {
        global $CFG, $DB, $PAGE;

        require_once($CFG->dirroot . '/cohort/lib.php');
        require_once($CFG->dirroot . '/theme/boost_union/flavours/flavourslib.php');

        $this->resetAfterTest();

        // Create a user and a cohort.
        $user = $this->getDataGenerator()->create_user();
        $cohort = $this->getDataGenerator()->create_cohort(['idnumber' => 'cohort1']);

        // If the user should be removed from the cohort, add the user to the cohort before the flavour exists,
        // so that the flag is not set yet.
        if ($change === 'removed') {
            cohort_add_member($cohort->id, $user->id);
        }

        // Create a flavour which applies to the cohort.
        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $generator->create_flavour(['title' => 'Flavour 1', 'applytocohorts_ids' => 'cohort1']);

        // Initially, the flag must not be set.
        $params = ['userid' => $user->id, 'name' => 'theme_boost_union_flavours_purgesessioncache'];
        $this->assertFalse($DB->record_exists('user_preferences', $params));

        // Change the cohort membership of the user.
        if ($change === 'added') {
            cohort_add_member($cohort->id, $user->id);
        } else {
            cohort_remove_member($cohort->id, $user->id);
        }

        // Now, the flag must be set.
        $this->assertEquals(1, $DB->get_field('user_preferences', 'value', $params));

        // Determine the flavour which applies for the user, as it is done on every page load.
        $this->setUser($user);
        $PAGE->set_url('/my/');
        $PAGE->set_course(get_site());
        theme_boost_union_get_flavour_which_applies();

        // Afterwards, the flag must be removed again, both from the database and from the user's preferences.
        $this->assertFalse($DB->record_exists('user_preferences', $params));
        $this->assertNull(get_user_preferences('theme_boost_union_flavours_purgesessioncache'));
    }
}
