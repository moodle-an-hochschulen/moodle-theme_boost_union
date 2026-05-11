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
 * Boost preset recommendation.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\recommendation\check;

use core\url;
use theme_boost_union\recommendation\recommendation;

/**
 * Recommendation for the Boost preset setting.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class themeboostpreset implements recommendation {
    /** @var string|null */
    protected static $status = null;

    /**
     * Return the recommendation id.
     *
     * @return string
     */
    public function get_id(): string {
        return 'themeboostpreset';
    }

    /**
     * Return the recommendation title.
     *
     * @return string
     */
    public function get_title(): string {
        return get_string('recommendation_themeboostpreset_title', 'theme_boost_union');
    }

    /**
     * Return the recommendation summary.
     *
     * @return string
     */
    public function get_summary(): string {
        return get_string('recommendation_themeboostpreset_summary', 'theme_boost_union');
    }

    /**
     * Return the recommendation description.
     *
     * @return string
     */
    public function get_description(): string {
        return get_string('recommendation_themeboostpreset_description', 'theme_boost_union');
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

        // Only the default Boost preset is considered fine.
        $preset = (string) get_config('theme_boost', 'preset');
        if ($preset === 'default.scss') {
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
        return new url('/admin/settings.php', ['section' => 'themesettingboost']);
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
     * Sets the Boost preset to default.scss.
     *
     * @return void
     */
    public function autofix(): void {
        // Set the preset to default.scss.
        set_config('preset', 'default.scss', 'theme_boost');

        // Reset cached status so the next call to get_status() re-evaluates.
        self::$status = null;

        // Reset the theme caches to ensure the change takes effect immediately.
        theme_reset_all_caches();
    }
}
