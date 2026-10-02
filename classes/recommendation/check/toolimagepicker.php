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
 * Theme Boost Union - Recommendation: Companion plugin tool_imagepicker.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\recommendation\check;

use core\url;
use theme_boost_union\recommendation\recommendation;

/**
 * Recommendation for installing the companion plugin tool_imagepicker.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class toolimagepicker extends recommendation {
    /**
     * Return the recommendation id.
     *
     * @return string
     */
    public function get_id(): string {
        return 'toolimagepicker';
    }

    /**
     * Return the recommendation title.
     *
     * @return string
     */
    public function get_title(): string {
        return get_string('recommendation_toolimagepicker_title', 'theme_boost_union');
    }

    /**
     * Return the recommendation summary.
     *
     * @return string
     */
    public function get_summary(): string {
        return get_string('recommendation_toolimagepicker_summary', 'theme_boost_union');
    }

    /**
     * Return the recommendation description.
     *
     * @return string
     */
    public function get_description(): string {
        return get_string('recommendation_toolimagepicker_description', 'theme_boost_union');
    }

    /**
     * Return the recommendation status.
     *
     * @return string
     */
    public function get_status(): string {
        global $CFG;

        // Require the theme library (for the image picker helper function).
        require_once($CFG->dirroot . '/theme/boost_union/lib.php');

        // If the companion plugin is installed, everything is fine. Otherwise, raise the admin's awareness.
        // This is deliberately not cached in a static variable as the availability check does its own caching.
        if (theme_boost_union_is_imagepicker_available()) {
            return recommendation::OK;
        } else {
            return recommendation::NOTICE;
        }
    }

    /**
     * Return the recommendation action URL.
     *
     * @return \moodle_url|null
     */
    public function get_action_url(): ?\moodle_url {
        return new url('https://github.com/moodle-an-hochschulen/moodle-tool_imagepicker');
    }

    /**
     * Return the recommendation category.
     *
     * @return string
     */
    public function get_category(): string {
        return recommendation::CATEGORY_THIRDPARTY;
    }
}
