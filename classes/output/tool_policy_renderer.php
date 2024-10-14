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

namespace theme_boost_union\output;

/**
 * Theme Boost Union - tool_policy renderer
 *
 * @package    theme_boost_union
 * @copyright  2024 Christian Wolters <info@christianwolters.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tool_policy_renderer extends \tool_policy\output\renderer {

    /**
     * Overrides the header() function.
     *
     * The goal here is to override page layout for /admin/tool/policy/viewall.php.
     *
     * @return string HTML of the page header.
     */
    public function header() {
        // Check that only the /admin/tool/policy/viewall.php page is affected.
        $pageurl = new \core\url('/admin/tool/policy/viewall.php');
        if ($pageurl->compare($this->page->url, URL_MATCH_BASE) == true) {

            // If the admin wants to show navigation on the policy page.
            $config = get_config('theme_boost_union', 'policyoverviewnavigation');
            if (isset($config) && $config == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                // Set the page layout to standard.
                $this->page->set_pagelayout('standard');
            }
        }

        // Call and return the header function.
        return parent::header();
    }
}
