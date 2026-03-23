<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Theme Boost Union - External function to clear smart menu cache for starred-course mode.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_value;

/**
 * Provides the theme_boost_union_smartmenus_clear_starredcourses_cache external function.
 */
class smartmenus_clear_starredcourses_cache extends external_api {
    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    /**
     * Clear smart menu caches for current user and starred-courses client mode.
     *
     * @return bool
     */
    public static function execute(): bool {
        global $DB, $USER;

        // Validate parameters - none in this case, but this also checks for valid session key and user login.
        self::validate_parameters(self::execute_parameters(), []);

        // Only logged in users can have starred courses, so this function should not be called for guests.
        // Anyway, to avoid hickups if called, just return true if the user is a guest.
        require_login();
        if (isguestuser()) {
            return true;
        }

        // Get the menu items which are dynamic and have the starred courses client handling enabled.
        // These are the only ones which require cache invalidation after starring or unstarring courses.
        $params = [
            'type' => \theme_boost_union\smartmenu_item::TYPEDYNAMIC,
            'starredcourses' => \theme_boost_union\smartmenu_item::STARREDCOURSES_ONLY_CLIENT,
        ];
        $items = $DB->get_records('theme_boost_union_menuitems', $params, '', 'id,menu');

        // If there are no such items, there is no need to invalidate any cache, so just return true.
        if (empty($items)) {
            return true;
        }

        // Invalidate the cache for all affected menu items for this user.
        // During this loop, also collect the affected menu ids to invalidate the cache for the whole menu as well, to be sure.
        $menuids = [];
        foreach ($items as $item) {
            \theme_boost_union\smartmenu_helper::remove_user_cacheitem((int) $item->id, 0, (int) $USER->id);
            $menuids[(int) $item->menu] = true;
        }

        // Now invalidate the cache for the whole menu as well, to be sure.
        foreach (array_keys($menuids) as $menuid) {
            \theme_boost_union\smartmenu_helper::remove_user_cachemenu((int) $menuid, 0, (int) $USER->id);
        }

        // Done.
        return true;
    }

    /**
     * Describes the external function result value.
     *
     * @return external_value
     */
    public static function execute_returns(): external_value {
        return new external_value(PARAM_BOOL, 'Whether the cache invalidation ran successfully');
    }
}
