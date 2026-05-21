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
 * Info banner on login page recommendation.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\recommendation\check;

use core\url;
use theme_boost_union\recommendation\recommendation;

/**
 * Recommendation for info banners on the login page with side-by-side login arrangement.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class infobannerloginpagesidebyside extends recommendation {
    /**
     * Return the recommendation id.
     *
     * @return string
     */
    public function get_id(): string {
        return 'infobannerloginpagesidebyside';
    }

    /**
     * Return the recommendation title.
     *
     * @return string
     */
    public function get_title(): string {
        return get_string('recommendation_infobannerloginpagesidebyside_title', 'theme_boost_union');
    }

    /**
     * Return the recommendation summary.
     *
     * @return string
     */
    public function get_summary(): string {
        return get_string('recommendation_infobannerloginpagesidebyside_summary', 'theme_boost_union');
    }

    /**
     * Return the recommendation description.
     *
     * @return string
     */
    public function get_description(): string {
        return get_string('recommendation_infobannerloginpagesidebyside_details', 'theme_boost_union');
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

        // Get the banner number from args, or iterate over all banners if none was given.
        $bannerno = isset($this->args[0]) && is_numeric($this->args[0]) ? (int) $this->args[0] : null;
        $banners = ($bannerno !== null) ? [$bannerno] : range(1, THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT);

        // Iterate over the relevant banners and check if any of them is active on the login page.
        foreach ($banners as $no) {
            // Get the pages for this banner.
            $pages = get_config('theme_boost_union', 'infobanner' . $no . 'pages');
            $pages = explode(',', $pages);

            // Iterate over the pages and check if the login page is among them.
            foreach ($pages as $page) {
                if ($page === THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_LOGIN) {
                    // If the login page is active for this banner,
                    // we know now that this is a check status, so we can stop checking further.
                    return recommendation::CHECK;
                }
            }
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
            ['section' => 'theme_boost_union_content', 'anchor' => 'theme_boost_union_infobanners_infobanner']
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
     * Return whether this recommendation supports arguments (i.e. parameterised recommendation id).
     *
     * @return bool
     */
    public function supports_args(): bool {
        return true;
    }
}
