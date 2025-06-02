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
 * Theme Boost Union - External admin settings page which can be placed within a tab
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

/**
 * Class admin_externalpage_in_tab.
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_externalpage_in_tab extends \admin_externalpage {
    /**
     * Function to make /admin/settings.php happy on the one side and to prevent blank tabs on
     * /admin/settings.php?section=theme_boost_union_snippets on the other side.
     * It checks if the current URL matches "/admin/settings.php?section=theme_boost_union_snippets" with no anchor
     * (which means that the Tab is currently shown in the tab)
     * and redirects to "/theme/boost_union/snippets/overview.php" if it does.
     *
     * @return string
     */
    public function output_html() {
        $script = "
            <script>
                (function() {
                    // Get the current URL path and search parameters.
                    var currentPath = window.location.pathname;
                    var currentSearch = window.location.search;
                    var hasAnchor = window.location.hash.length > 0;

                    // Check if the URL matches the pattern and has no anchor.
                    if (currentPath.endsWith('/admin/settings.php') &&
                            currentSearch === '?section=theme_boost_union_snippets' &&
                            !hasAnchor) {
                        // Redirect to the canoical URL of the tab, using relative path to support subdirectory installations.
                        window.location.href = M.cfg.wwwroot + '/theme/boost_union/snippets/overview.php';
                    }
                })();
            </script>
        ";

        return $script;
    }
}
