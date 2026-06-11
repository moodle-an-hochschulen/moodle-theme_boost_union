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
 * Footnote on login page recommendation.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\recommendation\check;

use core\url;
use theme_boost_union\recommendation\recommendation;

/**
 * Recommendation for the footnote on the login page with side-by-side login arrangement.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class footnoteloginpagesidebyside extends recommendation {
    /**
     * Return the recommendation id.
     *
     * @return string
     */
    public function get_id(): string {
        return 'footnoteloginpagesidebyside';
    }

    /**
     * Return the recommendation title.
     *
     * @return string
     */
    public function get_title(): string {
        return get_string('recommendation_footnoteloginpagesidebyside_title', 'theme_boost_union');
    }

    /**
     * Return the recommendation summary.
     *
     * @return string
     */
    public function get_summary(): string {
        return get_string('recommendation_footnoteloginpagesidebyside_summary', 'theme_boost_union');
    }

    /**
     * Return the recommendation description.
     *
     * @return string
     */
    public function get_description(): string {
        return get_string('recommendation_footnoteloginpagesidebyside_details', 'theme_boost_union');
    }

    /**
     * Return the recommendation status.
     *
     * @return string
     */
    public function get_status(): string {
        // If the login page arrangement is not side-by-side, everything is fine.
        $loginarrangement = get_config('theme_boost_union', 'loginarrangement');
        if ($loginarrangement !== THEME_BOOST_UNION_SETTING_LOGINARRANGEMENT_SIDEBYSIDE) {
            return recommendation::OK;
        }

        // Get the page layouts for which the footnote is enabled.
        $footnotelayoutssetting = get_config('theme_boost_union', 'footnotelayouts');

        // If the footnote is not shown on any page, everything is fine.
        if (empty($footnotelayoutssetting)) {
            return recommendation::OK;
        }

        // If the login page layout is among the enabled layouts, the admin should be aware of the different appearance.
        $footnotelayoutsarray = explode(',', $footnotelayoutssetting);
        if (in_array('login', $footnotelayoutsarray)) {
            return recommendation::CHECK;
        }

        // Everything should be fine if we ended up here, so return OK.
        return recommendation::OK;
    }

    /**
     * Return the recommendation action URL.
     *
     * @return \moodle_url|null
     */
    public function get_action_url(): ?\moodle_url {
        return new url(
            '/admin/settings.php',
            ['section' => 'theme_boost_union_content', 'anchor' => 'theme_boost_union_content_footer']
        );
    }

    /**
     * Return the recommendation category.
     *
     * @return string
     */
    public function get_category(): string {
        return recommendation::CATEGORY_USABILITY;
    }

    /**
     * Return whether this recommendation should be hidden from the list when its status is OK.
     *
     * @return bool
     */
    public function hide_if_ok(): bool {
        return true;
    }
}
