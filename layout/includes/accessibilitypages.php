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
 * Theme Boost Union - Accessibility pages layout include.
 *
 * @package    theme_boost_union
 * @copyright  2024 Katalin Lukacs Toth, ZHAW Zurich University of Applied Sciences <lukc@zhaw.ch>
 * @copyright  2024 Simon Schoenenberger, ZHAW Zurich University of Applied Sciences <scgo@zhaw.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Require the necessary libraries.
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

$config = get_config('theme_boost_union');

// The static pages to be supported.
$pages = ['accessibility', 'accessibilitysupport'];

// Initialize flags if any static page should be linked from the footer and footnote.
$templatecontext['anyaccessibilitypagelinkedfromfooter'] = false;
$templatecontext['anyaccessibilitypagelinkedfromfootnote'] = false;

// Iterate over the static pages.
foreach ($pages as $page) {
    // If the page is enabled.
    if (isset($config->{'enable'.$page}) &&
            $config->{'enable'.$page} == THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // If the admin wants to show a link in the footnote or in both locations.
        if ($config->{$page.'linkposition'} == THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTNOTE ||
                $config->{$page.'linkposition'} == THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_BOTH) {
            // If the footnote is empty and not configured to be shown yet.
            if (isset($templatecontext['showfootnote']) == false || $templatecontext['showfootnote'] == false) {
                // Add marker to show the footnote to templatecontext.
                $templatecontext['showfootnote'] = true;
            }

            // Add marker to show the page link in the footnote to templatecontext.
            $templatecontext[$page.'linkpositionfootnote'] = true;

            // Flip flag that at least one page should be linked from the footnote.
            $templatecontext['anystaticpagelinkedfromfootnote'] = true;
        }

        // If the admin wants to show a link in the footer or in both locations.
        if ($config->{$page.'linkposition'} == THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTER ||
                $config->{$page.'linkposition'} == THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_BOTH) {
            // Add marker to show the page link in the footer to templatecontext.
            $templatecontext[$page.'linkpositionfooter'] = true;

            // Flip flag that at least one page should be linked from the footer.
            $templatecontext['anyaccessibilitypagelinkedfromfooter'] = true;
        }

        // Add the page link and page title to the templatecontext.
        $templatecontext[$page.'link'] = theme_boost_union_get_staticpage_link($page);
        $templatecontext[$page.'pagetitle'] = theme_boost_union_get_staticpage_pagetitle($page);
    }
}
