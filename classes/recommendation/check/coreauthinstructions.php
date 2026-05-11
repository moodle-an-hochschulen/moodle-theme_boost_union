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
 * Core auth instructions recommendation.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\recommendation\check;

use core\url;
use theme_boost_union\recommendation\recommendation;

/**
 * Recommendation for Moodle core auth_instructions usage with Boost Union.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class coreauthinstructions implements recommendation {
    /** @var string|null */
    protected static $status = null;

    /**
     * Return the recommendation id.
     *
     * @return string
     */
    public function get_id(): string {
        return 'coreauthinstructions';
    }

    /**
     * Return the recommendation title.
     *
     * @return string
     */
    public function get_title(): string {
        return get_string('recommendation_coreauthinstructions_title', 'theme_boost_union');
    }

    /**
     * Return the recommendation summary.
     *
     * @return string
     */
    public function get_summary(): string {
        return get_string('recommendation_coreauthinstructions_summary', 'theme_boost_union');
    }

    /**
     * Return the recommendation description.
     *
     * @return string
     */
    public function get_description(): string {
        return get_string('recommendation_coreauthinstructions_description', 'theme_boost_union');
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

        // If auth_instructions setting is not empty, this is a notice as these instructions are not rendered in Boost Union.
        // Otherwise this is ok.
        $coreauthinstructions = (string) get_config('core', 'auth_instructions');
        if (trim(strip_tags($coreauthinstructions)) !== '') {
            self::$status = recommendation::NOTICE;
        } else {
            self::$status = recommendation::OK;
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
        return new url('/admin/search.php', ['query' => 'auth_instructions']);
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
     * Clears the Moodle core auth_instructions setting.
     *
     * @return void
     */
    public function autofix(): void {
        // Clear auth_instructions setting.
        set_config('auth_instructions', '');

        // Reset cached status so the next call to get_status() re-evaluates.
        self::$status = null;
    }
}
