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
 * Theme Boost Union - Hook: Allows plugins to modify configuration.
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\local\hook;

/**
 * Hook to allow plugins to modify configuration.
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class after_config {
    /**
     * Callback which is triggered as soon as practical on every moodle bootstrap after config has been loaded.
     *
     * We use this callback function to manipulate / set settings which would normally be manipulated / set through
     * /config.php, but we do not want to urge the admin to add stuff to /config.php when installing Boost Union.
     *
     * @param \core\hook\after_config $hook
     *
     * @return void.
     */
    public static function callback(\core\hook\after_config $hook): void {
        global $CFG;

        // Require own local library.
        require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

        // If this is not called by a CLI script or an AJAX script.
        if (!CLI_SCRIPT && !AJAX_SCRIPT) {
            // Manipulate Moodle core hooks.
            theme_boost_union_manipulate_hooks();
        }
    }
}
