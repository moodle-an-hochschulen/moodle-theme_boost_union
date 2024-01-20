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
 * Theme Boost Union - Static pages layout include.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Require the necessary libraries.
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

$config = get_config('theme_boost_union');

// The static pages to be supported.
$staticpages = ['aboutus', 'offers', 'imprint', 'contact', 'help', 'maintenance', 'page1', 'page2', 'page3'];

// Initialize flags if any static page should be linked from the footer and footnote.
$templatecontext['anystaticpagelinkedfromfooter'] = false;
$templatecontext['anystaticpagelinkedfromfootnote'] = false;

// Iterate over the static pages.
foreach ($staticpages as $staticpage) {
    // If the page is enabled.
    if (isset($config->{'enable'.$staticpage}) &&
            $config->{'enable'.$staticpage} == THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // If the admin wants to show a link in the footnote or in both locations.
        if ($config->{$staticpage.'linkposition'} == THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTNOTE ||
                $config->{$staticpage.'linkposition'} == THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_BOTH) {
            // If the footnote is empty and not configured to be shown yet.
            if (isset($templatecontext['showfootnote']) == false || $templatecontext['showfootnote'] == false) {
                // Add marker to show the footnote to templatecontext.
                $templatecontext['showfootnote'] = true;
            }

            // Add marker to show the page link in the footnote to templatecontext.
            $templatecontext[$staticpage.'linkpositionfootnote'] = true;

            // Flip flag that at least one static page should be linked from the footnote.
            $templatecontext['anystaticpagelinkedfromfootnote'] = true;
        }

        // If the admin wants to show a link in the footer or in both locations.
        if ($config->{$staticpage.'linkposition'} == THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTER ||
                $config->{$staticpage.'linkposition'} == THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_BOTH) {
            // Add marker to show the page link in the footer to templatecontext.
            $templatecontext[$staticpage.'linkpositionfooter'] = true;

            // Flip flag that at least one static page should be linked from the footer.
            $templatecontext['anystaticpagelinkedfromfooter'] = true;
        }

        // Add the page link and page title to the templatecontext.
        $templatecontext[$staticpage.'link'] = theme_boost_union_get_staticpage_link($staticpage);
        $templatecontext[$staticpage.'pagetitle'] = theme_boost_union_get_staticpage_pagetitle($staticpage);
    }
}
