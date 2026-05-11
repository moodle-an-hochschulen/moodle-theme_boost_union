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
 * Recommendation interface and status constants.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\recommendation;

/**
 * Contract for a Boost Union recommendation.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface recommendation {
    /** @var string */
    public const OK = 'ok';

    /** @var string */
    public const NOTICE = 'notice';

    /** @var string */
    public const WARNING = 'warning';

    /** @var string */
    public const NA = 'na';

    /** @var string */
    public const MUTED = 'muted';

    /** @var string */
    public const CATEGORY_MOODLECORE = 'moodlecore';

    /** @var string */
    public const CATEGORY_BOOSTUNION = 'boostunion';

    /** @var string */
    public const CATEGORY_THIRDPARTY = 'thirdparty';

    /** @var string */
    public const CATEGORY_ACCESSIBILITY = 'accessibility';

    /**
     * Return an unique recommendation identifier.
     *
     * @return string
     */
    public function get_id(): string;

    /**
     * Return the recommendation title.
     *
     * @return string
     */
    public function get_title(): string;

    /**
     * Return the recommendation summary.
     *
     * @return string
     */
    public function get_summary(): string;

    /**
     * Return the recommendation description.
     *
     * @return string
     */
    public function get_description(): string;

    /**
     * Return the recommendation status.
     *
     * @return string One status value supported by the manager.
     */
    public function get_status(): string;

    /**
     * Return the action URL for this recommendation.
     *
     * @return \moodle_url|null
     */
    public function get_action_url(): ?\moodle_url;

    /**
     * Return the recommendation category.
     *
     * @return string One of the CATEGORY_* constants.
     */
    public function get_category(): string;

    /**
     * Return whether this recommendation can be fixed automatically.
     *
     * @return bool
     */
    public function is_autofixable(): bool;

    /**
     * Apply the automatic fix for this recommendation.
     *
     * This method is only called if is_autofixable() returns true.
     * It must perform the necessary changes to resolve the recommendation.
     *
     * @return void
     */
    public function autofix(): void;
}
