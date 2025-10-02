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
 * Theme Boost Union - Hook: Hook to allow subscribers to modify the process after headers are sent.
 *
 * @package    theme_boost_union
 * @copyright  2025 Lars Bonczek <bonczek@tu-berlin.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\local\hook\output;

use dml_exception;

/**
 * Hook to allow subscribers to modify the process after headers are sent.
 *
 * @package    theme_boost_union
 * @copyright  2025 Lars Bonczek <bonczek@tu-berlin.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class after_http_headers {

    /**
     * Callback to add elements after header.
     *
     * @param \core\hook\output\after_http_headers $hook
     * @throws dml_exception
     */
    public static function callback(\core\hook\output\after_http_headers $hook): void {
        global $CFG;

        // Require local library.
        require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

        // Call callback implementation.
        theme_boost_union_callbackimpl_after_http_headers($hook);
    }
}
