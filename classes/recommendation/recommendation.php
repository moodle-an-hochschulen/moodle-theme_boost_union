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
 * Recommendation base class and status constants.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\recommendation;

/**
 * Base class for a Boost Union recommendation.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class recommendation {
    /** @var array Arguments parsed from a parameterised recommendation id (only relevant when supports_args() returns true). */
    protected array $args = [];

    /** @var string */
    public const OK = 'ok';

    /** @var string */
    public const CHECK = 'check';

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
    public const CATEGORY_USABILITY = 'usability';

    /** @var string */
    public const CATEGORY_ACCESSIBILITY = 'accessibility';

    /**
     * Return an unique recommendation identifier.
     *
     * @return string
     */
    abstract public function get_id(): string;

    /**
     * Return the recommendation title.
     *
     * @return string
     */
    abstract public function get_title(): string;

    /**
     * Return the recommendation summary.
     *
     * @return string
     */
    abstract public function get_summary(): string;

    /**
     * Return the recommendation description.
     *
     * @return string
     */
    abstract public function get_description(): string;

    /**
     * Return the recommendation status.
     *
     * @return string One status value supported by the manager.
     */
    abstract public function get_status(): string;

    /**
     * Return the action URL for this recommendation.
     *
     * @return \moodle_url|null
     */
    abstract public function get_action_url(): ?\moodle_url;

    /**
     * Return the recommendation category.
     *
     * @return string One of the CATEGORY_* constants.
     */
    abstract public function get_category(): string;

    /**
     * Return whether this recommendation can be fixed automatically.
     *
     * @return bool
     */
    public function supports_autofix(): bool {
        return false;
    }

    /**
     * Apply the automatic fix for this recommendation.
     *
     * This method is only called if supports_autofix() returns true.
     * It must perform the necessary changes to resolve the recommendation.
     *
     * @return void
     */
    public function autofix(): void {
        // No automatic fix available by default.
    }

    /**
     * Return whether this recommendation should be hidden from the list when its status is OK or N/A.
     *
     * Override this in a subclass and return true for recommendations that only need to be shown
     * when an action is actually required (i.e. status is not OK or N/A).
     *
     * @return bool
     */
    public function hide_if_ok(): bool {
        return false;
    }

    /**
     * Return whether this recommendation accepts slash-separated arguments via a parameterised id.
     *
     * If this returns true, the manager will call set_args() to pass the parsed arguments before get_status() is invoked.
     *
     * @return bool
     */
    public function supports_args(): bool {
        return false;
    }

    /**
     * Set the arguments parsed from a parameterised recommendation id (e.g. 'infobannerloginpagesidebyside/3').
     *
     * This method is only called if supports_args() returns true.
     * It will remember the arguments internally so that they can be used in get_status() and other methods as needed.
     *
     * @param array $args
     * @return void
     */
    public function set_args(array $args): void {
        $this->args = $args;
    }
}
