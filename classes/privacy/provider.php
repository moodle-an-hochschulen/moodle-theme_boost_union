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
 * Theme Boost Union - Privacy provider
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\writer;

/**
 * Privacy Subsystem implementing the metadata provider and the user preference provider.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    // This plugin stores user preferences.
    \core_privacy\local\metadata\provider,
    // This plugin has some sitewide user preferences to export.
    \core_privacy\local\request\user_preference_provider {
    /** The user preference prefix for the dismissed info banners (followed by the banner number and the suffix). */
    const INFOBANNER_DISMISSED_PREFIX = 'theme_boost_union_infobanner';

    /** The user preference suffix for the dismissed info banners. */
    const INFOBANNER_DISMISSED_SUFFIX = '_dismissed';

    /** The user preference for the flag to purge the flavours cache. */
    const FLAVOURS_PURGESESSIONCACHE = 'theme_boost_union_flavours_purgesessioncache';

    /** The user preference for the flag to purge the smart menus cache. */
    const MENU_PURGESESSIONCACHE = 'theme_boost_union_menu_purgesessioncache';

    /**
     * Returns meta data about this system.
     *
     * @param collection $items The initialised item collection to add items to.
     * @return collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $items): collection {
        global $CFG;

        // Require the theme's lib.php for the number of info banners.
        require_once($CFG->dirroot . '/theme/boost_union/lib.php');

        // Add the user preferences for the dismissed info banners.
        // As the privacy API does not support wildcard preference names, add one preference per info banner.
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT; $i++) {
            $items->add_user_preference(
                self::get_infobanner_preference_name($i),
                'privacy:metadata:preference:infobannerdismissed'
            );
        }

        // Add the user preferences for the flags to purge the caches.
        $items->add_user_preference(
            self::FLAVOURS_PURGESESSIONCACHE,
            'privacy:metadata:preference:flavourspurgesessioncache'
        );
        $items->add_user_preference(
            self::MENU_PURGESESSIONCACHE,
            'privacy:metadata:preference:menupurgesessioncache'
        );

        return $items;
    }

    /**
     * Store all user preferences for the plugin.
     *
     * @param int $userid The userid of the user whose data is to be exported.
     */
    public static function export_user_preferences(int $userid) {
        global $CFG;

        // Require the theme's lib.php for the number of info banners.
        require_once($CFG->dirroot . '/theme/boost_union/lib.php');

        // Export the user preferences for the dismissed info banners.
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT; $i++) {
            $preferencename = self::get_infobanner_preference_name($i);
            $preference = get_user_preferences($preferencename, null, $userid);
            if ($preference !== null) {
                writer::export_user_preference(
                    'theme_boost_union',
                    $preferencename,
                    $preference,
                    get_string('privacy:infobannerdismissed', 'theme_boost_union', $i)
                );
            }
        }

        // Export the user preferences for the flags to purge the caches.
        $preference = get_user_preferences(self::FLAVOURS_PURGESESSIONCACHE, null, $userid);
        if ($preference !== null) {
            writer::export_user_preference(
                'theme_boost_union',
                self::FLAVOURS_PURGESESSIONCACHE,
                $preference,
                get_string('privacy:flavourspurgesessioncache', 'theme_boost_union')
            );
        }
        $preference = get_user_preferences(self::MENU_PURGESESSIONCACHE, null, $userid);
        if ($preference !== null) {
            writer::export_user_preference(
                'theme_boost_union',
                self::MENU_PURGESESSIONCACHE,
                $preference,
                get_string('privacy:menupurgesessioncache', 'theme_boost_union')
            );
        }
    }

    /**
     * Helper function to get the user preference name for the given info banner.
     *
     * @param int $bannerno The number of the info banner.
     * @return string The user preference name.
     */
    protected static function get_infobanner_preference_name(int $bannerno): string {
        return self::INFOBANNER_DISMISSED_PREFIX . $bannerno . self::INFOBANNER_DISMISSED_SUFFIX;
    }
}
