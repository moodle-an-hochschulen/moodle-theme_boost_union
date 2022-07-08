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
 * @package   theme_boost_union
 * @copyright 2022 Moodle an Hochschulen e.V. <kontakt@moodle-an-hochschulen.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Require the necessary libraries.
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

$config = get_config('theme_boost_union');

// If the imprint is enabled.
if ($config->enableimprint == THEME_BOOST_UNION_SETTING_SELECT_YES) {
    // If the admin wants to show a link in the footnote or in both locations.
    if ($config->imprintlinkposition == THEME_BOOST_UNION_SETTING_IMPRINTLINKPOSITION_FOOTNOTE ||
            $config->imprintlinkposition == THEME_BOOST_UNION_SETTING_IMPRINTLINKPOSITION_BOTH) {
        // If the footnote is empty and not configured to be shown yet.
        if (isset($templatecontext['showfootnote']) == false || $templatecontext['showfootnote'] == false) {
            // Add marker to show the footnote to templatecontext.
            $templatecontext['showfootnote'] = true;
        }

        // Add marker to show the imprint link in the footnote to templatecontext.
        $templatecontext['imprintlinkpositionfootnote'] = true;
    }

    // If the admin wants to show a link in the footer or in both locations.
    if ($config->imprintlinkposition == THEME_BOOST_UNION_SETTING_IMPRINTLINKPOSITION_FOOTER ||
            $config->imprintlinkposition == THEME_BOOST_UNION_SETTING_IMPRINTLINKPOSITION_BOTH) {
        // Add marker to show the imprint link in the footer to templatecontext.
        $templatecontext['imprintlinkpositionfooter'] = true;
    }

    // Add the imprint link and page title to the templatecontext.
    $templatecontext['imprintlink'] = theme_boost_union_get_imprint_link();
    $templatecontext['imprintpagetitle'] = theme_boost_union_get_imprint_pagetitle();
}
