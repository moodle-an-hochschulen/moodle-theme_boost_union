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

namespace theme_boost_union\local;

/**
 * Theme Boost Union - MWP Extension Library.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mwp {
    /**
     * Helper function to check if we are currently on MWP, regardless if the Boost Union MWP extension is present.
     * If yes, we simply return true.
     *
     * @return bool True if we are on MWP, false otherwise.
     */
    public static function core_present(): bool {
        global $CFG;

        // Use static variable to cache the result of this function,
        // as it may be called multiple times during a request.
        static $result = null;
        if ($result !== null) {
            return $result;
        }

        // If we are running a Behat test and MWP core presence is simulated, return true.
        if (defined('BEHAT_SITE_RUNNING') && get_config('theme_boost_union', 'behat_mwp_core_present')) {
            // Inform the caller.
            $result = true;
            return $result;
        }

        // If tool_tenant not present, we are on MWP.
        if (file_exists($CFG->dirroot . '/admin/tool/tenant/version.php')) {
            // Inform the caller.
            $result = true;
            return $result;
        }

        // Otherwise, we are not on MWP.
        // Inform the caller.
        $result = false;
        return $result;
    }

    /**
     * Helper function to check if we are currently on MWP and if the Boost Union MWP extension is present.
     * If yes, the Boost Union MWP extension is required and can be used.
     *
     * @return bool True if we are on MWP and the MWP extension is present, false otherwise.
     */
    public static function extension_present(): bool {
        global $CFG;

        // Use static variable to cache the result of this function,
        // as it may be called multiple times during a request.
        static $result = null;
        if ($result !== null) {
            return $result;
        }

        // If we do not see the MWP core, we do not have to look further.
        if (!self::core_present()) {
            // Inform the caller.
            $result = false;
            return $result;
        }

        // If local_boost_union_mwp is present, we can enable the MWP extension.
        if (file_exists($CFG->dirroot . '/local/boost_union_mwp/version.php')) {
            // And inform the caller.
            $result = true;
            return $result;

            // Otherwise.
        } else {
            // Inform the caller.
            $result = false;
            return $result;
        }
    }

    /**
     * Helper function to check if the Workplace theme is present.
     * This is used in config.php to determine if Workplace should be added as a parent theme.
     * It does not check again if we are on MWP.
     *
     * @return bool True if the Workplace theme is present, false otherwise.
     */
    public static function themeworkplace_present(): bool {
        global $CFG;

        // Use static variable to cache the result of this function,
        // as it may be called multiple times during a request.
        static $result = null;
        if ($result !== null) {
            return $result;
        }

        // If the workplace theme config file exists, the theme is present.
        if (file_exists($CFG->dirroot . '/theme/workplace/version.php')) {
            // Inform the caller.
            $result = true;
            return $result;
        }

        // Otherwise, the workplace theme is not present.
        // Inform the caller.
        $result = false;
        return $result;
    }
}
