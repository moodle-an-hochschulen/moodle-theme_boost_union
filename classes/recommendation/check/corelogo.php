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
 * Core logo recommendation.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\recommendation\check;

use core\url;
use theme_boost_union\recommendation\recommendation;

/**
 * Recommendation for Moodle core logo usage with Boost Union.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class corelogo implements recommendation {
    /** @var string|null */
    protected static $status = null;

    /**
     * Return the recommendation id.
     *
     * @return string
     */
    public function get_id(): string {
        return 'corelogo';
    }

    /**
     * Return the recommendation title.
     *
     * @return string
     */
    public function get_title(): string {
        return get_string(
            'recommendation_corebrandasset_title',
            'theme_boost_union',
            get_string('logosetting', 'theme_boost_union')
        );
    }

    /**
     * Return the recommendation summary.
     *
     * @return string
     */
    public function get_summary(): string {
        return get_string(
            'recommendation_corebrandasset_summary',
            'theme_boost_union',
            get_string('logosetting', 'theme_boost_union')
        );
    }

    /**
     * Return the recommendation description.
     *
     * @return string
     */
    public function get_description(): string {
        return get_string(
            'recommendation_corebrandasset_description',
            'theme_boost_union',
            get_string('logosetting', 'theme_boost_union')
        );
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

        // If a core logo is configured or uploaded, show a notice. Otherwise this is fine.
        $logo = (string) get_config('core_admin', 'logo');
        if ($logo === '') {
            $fs = get_file_storage();
            $systemcontext = \context_system::instance();
            if (!$fs->is_area_empty($systemcontext->id, 'core_admin', 'logo', 0)) {
                $logo = '1';
            }
        }

        if ($logo !== '') {
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
        return new url('/admin/settings.php', ['section' => 'logos']);
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
     * Removes the core logo config value and deletes all files in the logo filearea.
     *
     * @return void
     */
    public function autofix(): void {
        // Remove config and files.
        unset_config('logo', 'core_admin');
        $fs = get_file_storage();
        $systemcontext = \context_system::instance();
        $fs->delete_area_files($systemcontext->id, 'core_admin', 'logo', 0);

        // Reset cached status so the next call to get_status() re-evaluates.
        self::$status = null;
    }
}
