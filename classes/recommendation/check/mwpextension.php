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
 * MWP extension recommendation.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\recommendation\check;

use core\url;
use theme_boost_union\local\mwp;
use theme_boost_union\recommendation\recommendation;

/**
 * Recommendation for installing the Boost Union MWP extension when running on MWP.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mwpextension extends recommendation {
    /** @var string|null */
    protected static $status = null;

    /**
     * Return the recommendation id.
     *
     * @return string
     */
    public function get_id(): string {
        return 'mwpextension';
    }

    /**
     * Return the recommendation title.
     *
     * @return string
     */
    public function get_title(): string {
        return get_string('recommendation_mwpextension_title', 'theme_boost_union');
    }

    /**
     * Return the recommendation summary.
     *
     * @return string
     */
    public function get_summary(): string {
        return get_string('recommendation_mwpextension_summary', 'theme_boost_union');
    }

    /**
     * Return the recommendation description.
     *
     * @return string
     */
    public function get_description(): string {
        return get_string('recommendation_mwpextension_description', 'theme_boost_union');
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

        // If MWP core is present but the MWP extension is not, recommend installing the extension.
        if (mwp::core_present() && !mwp::extension_present()) {
            self::$status = recommendation::WARNING;
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
        return new url('https://bdecent.de/union');
    }

    /**
     * Return the recommendation category.
     *
     * @return string
     */
    public function get_category(): string {
        return recommendation::CATEGORY_MWP;
    }

    /**
     * Return whether this recommendation should be hidden from the list when its status is OK or N/A.
     *
     * @return bool
     */
    public function hide_if_ok(): bool {
        return true;
    }
}
