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
 * Slash arguments recommendation.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\recommendation\check;

use core\url;
use theme_boost_union\recommendation\recommendation;

/**
 * Recommendation for slash arguments.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class slasharguments implements recommendation {
    /** @var string|null */
    protected static $status = null;

    /**
     * Return the recommendation id.
     *
     * @return string
     */
    public function get_id(): string {
        return 'slasharguments';
    }

    /**
     * Return the recommendation title.
     *
     * @return string
     */
    public function get_title(): string {
        return get_string('recommendation_slasharguments_title', 'theme_boost_union');
    }

    /**
     * Return the recommendation summary.
     *
     * @return string
     */
    public function get_summary(): string {
        return get_string('recommendation_slasharguments_summary', 'theme_boost_union');
    }

    /**
     * Return the recommendation description.
     *
     * @return string
     */
    public function get_description(): string {
        return get_string('recommendation_slasharguments_description', 'theme_boost_union');
    }

    /**
     * Return the recommendation status.
     *
     * @return string
     */
    public function get_status(): string {
        // If status is already determined, return it.
        if (self::$status !== null) {
            return self::$status;
        }

        global $CFG;

        // If slash arguments are enabled, this is good. If not, recommend enabling them.
        if (!empty($CFG->slasharguments)) {
            self::$status = recommendation::OK;
        } else {
            self::$status = recommendation::WARNING;
        }

        // Return status.
        return self::$status;
    }

    /**
     * Return the recommendation action URL.
     *
     * @return \moodle_url|null
     */
    public function get_action_url(): ?\moodle_url {
        return new url('/admin/search.php', ['query' => 'slasharguments']);
    }

    /**
     * Return the recommendation category.
     *
     * @return string
     */
    public function get_category(): string {
        return recommendation::CATEGORY_MOODLECORE;
    }

    /**
     * Return whether this recommendation can be fixed automatically.
     *
     * @return bool
     */
    public function is_autofixable(): bool {
        return true;
    }

    /**
     * Apply the automatic fix for this recommendation.
     *
     * Enables slash arguments by setting the slasharguments config to 1.
     *
     * @return void
     */
    public function autofix(): void {
        // Enable slash arguments.
        set_config('slasharguments', 1);

        // Reset cached status so the next call to get_status() re-evaluates.
        self::$status = null;
    }
}
