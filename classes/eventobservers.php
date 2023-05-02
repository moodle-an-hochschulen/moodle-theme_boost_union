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

/**
 * Theme Boost Union - Event observers.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

/**
 * Observer class containing methods monitoring various events.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class eventobservers {

    /**
     * Cohort deleted event observer.
     *
     * @param \core\event\base $event The event.
     */
    public static function cohort_deleted(\core\event\base $event) {
        global $CFG;

        // Require flavours library.
        require_once($CFG->dirroot . '/theme/boost_union/flavours/flavourslib.php');

        // If a flavour exists which is configured to apply to the given cohort.
        if (theme_boost_union_flavour_exists_for_cohort($event->objectid)) {
            // Purge the flavours cache as the users might get other flavours which apply after the cohort deletion.
            // We would have preferred using cache_helper::purge_by_definition, but this just purges the session cache
            // of the current user and not for all users.
            \cache_helper::purge_by_event('theme_boost_union_cohort_deleted');
        }
    }

    /**
     * Cohort member added event observer.
     *
     * @param \core\event\base $event The event.
     */
    public static function cohort_member_added(\core\event\base $event) {
        global $CFG;

        // Require flavours library.
        require_once($CFG->dirroot . '/theme/boost_union/flavours/flavourslib.php');

        // If a flavour exists which is configured to apply to the given cohort.
        if (theme_boost_union_flavour_exists_for_cohort($event->objectid)) {
            // Set a flag within a user preference for the affected user as the user might get other flavours which apply
            // after adding him to a cohort. This flag is checked in theme_boost_union_get_flavour_which_applies() where,
            // if set, the flavours cache for the affected user is cleared.
            // This way, we avoid that the flavours cache is purged unnecessarily for all users.
            set_user_preference('theme_boost_union_flavours_purgesessioncache', true, $event->relateduserid);
        }
    }

    /**
     * Cohort member removed event observer.
     *
     * @param \core\event\base $event The event.
     */
    public static function cohort_member_removed(\core\event\base $event) {
        global $CFG;

        // Require flavours library.
        require_once($CFG->dirroot . '/theme/boost_union/flavours/flavourslib.php');

        // If a flavour exists which is configured to apply to the given cohort.
        if (theme_boost_union_flavour_exists_for_cohort($event->objectid)) {
            // Set a flag within a user preference for the affected user as the user might get other flavours which apply
            // after removing him from a cohort. This flag is checked in theme_boost_union_get_flavour_which_applies() where,
            // if set, the flavours cache for the affected user is cleared.
            // This way, we avoid that the flavours cache is purged unnecessarily for all users.
            set_user_preference('theme_boost_union_flavours_purgesessioncache', true, $event->relateduserid);
        }
    }
}
