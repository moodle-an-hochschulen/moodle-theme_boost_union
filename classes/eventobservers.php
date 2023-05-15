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

        // Verify any of the menu or menu items uses this cohort for restriction.
        require_once($CFG->dirroot . '/theme/boost_union/smartmenus/menulib.php');
        // Deletion of this cohort may result restriction of menus for users.
        // Verfiy any of the menus used this deleted cohort in restriction rules, then need to purge the menus cache.
        \smartmenu_helper::purge_cache_deleted_cohort($event->objectid);

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

        // Verify any of the menu or menu items uses this cohort for restriction.
        // if uses then update the cache for this user session only.
        require_once($CFG->dirroot . '/theme/boost_union/smartmenus/menulib.php');
        // Deletion of this cohort may result restriction of menus for users.
        // Verfiy any of the menus used this deleted cohort in restriction, then update the menus cache for this user session.
        \smartmenu_helper::purge_cache_session_cohort($event->objectid, $event->relateduserid);
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

        // Verify any of the menu or menu items uses this cohort for restriction.
        // if uses then update the cache for this user session only.
        require_once($CFG->dirroot . '/theme/boost_union/smartmenus/menulib.php');
        // Deletion of this cohort may result restriction of menus for users.
        // Verfiy any of the menus used this deleted cohort in restriction, then update the menus cache for this user session.
        \smartmenu_helper::purge_cache_session_cohort($event->objectid, $event->relateduserid);

    }

    /**
     * Event observer for when a user profile is updated.
     * Purges the cached menus for the updated user.
     *
     * @param \core\event\base $event The event that triggered the handler.
     */
    public static function user_updated(\core\event\base $event) {
        set_user_preference('theme_boost_union_menu_purgesessioncache', true, $event->relateduserid);
        set_user_preference('theme_boost_union_menuitem_purgesessioncache', true, $event->relateduserid);
    }

    /**
     * Event observer for when a role is assigned to a user.
     * Purges the cached menus for the user with the assigned role.
     *
     * @param \core\event\base $event The event that triggered the handler.
     */
    public static function role_assigned(\core\event\base $event) {
        global $CFG;

        require_once($CFG->dirroot . '/theme/boost_union/smartmenus/menulib.php');
        \smartmenu_helper::purge_cache_session_roles($event->objectid, $event->relateduserid);
    }

    /**
     * Event observer for when a role is unassigned from a user.
     * Purges the cached menus for the user with the unassigned role.
     *
     * @param \core\event\base $event The event that triggered the handler.
     */
    public static function role_unassigned(\core\event\base $event) {
        global $CFG;

        require_once($CFG->dirroot . '/theme/boost_union/smartmenus/menulib.php');
        \smartmenu_helper::purge_cache_session_roles($event->objectid, $event->relateduserid);
    }

    /**
     * Event observer for when a role is deleted.
     * Purges the cached menus for all users with the deleted role.
     *
     * @param \core\event\base $event The event that triggered the handler.
     */
    public static function role_deleted(\core\event\base $event) {
        global $CFG;

        require_once($CFG->dirroot . '/theme/boost_union/smartmenus/menulib.php');
        \smartmenu_helper::purge_cache_deleted_roles($event->objectid);
    }

    /**
     * Event observer for when a course is updated.
     * Purges the cached menus related to the course.
     *
     * @param \core\event\base $event The event that triggered the handler.
     */
    public static function course_updated(\core\event\base $event) {
        global $CFG;

        require_once($CFG->dirroot . '/theme/boost_union/smartmenus/menulib.php');
        // Clear the cache of menu when the course updated.
        \cache_helper::purge_by_event('theme_boost_union_course_updated');
    }

    /**
     * Event observer for when a course or module completion is updated.
     * Purges the cached menus related to the user.
     *
     * @param \core\event\base $event The event that triggered the handler.
     */
    public static function completion_updated(\core\event\base $event) {
        global $CFG;

        require_once($CFG->dirroot . '/theme/boost_union/smartmenus/menulib.php');
        // Clear the cache of menu when the course/module completion updated for user.
        \smartmenu_helper::purge_all_cache_user_session($event->relateduserid);
    }
}
