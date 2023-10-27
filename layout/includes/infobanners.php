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
 * Theme Boost Union - info banners layout include.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Require the necessary libraries.
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

$config = get_config('theme_boost_union');

// Initialize info banners data for templatecontext.
$infobanners = [];

// Remember if we need the dismissible AMD module.
$dismissibleamdneeded = false;

// Iterate over all info banners.
for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT; $i++) {
    // If the info banner (is enabled and) should be shown on this page.
    if (theme_boost_union_infobanner_is_shown_on_page($i)) {
        // Gather this info banner's data.
        // Info banner content.
        $formatoptions = ['noclean' => true, 'newlines' => false];
        $contentsettingname = 'infobanner'.$i.'content';
        $content = format_text($config->{$contentsettingname}, FORMAT_HTML, $formatoptions);

        // Info banner Bootstrap class.
        $bsclasssettingname = 'infobanner'.$i.'bsclass';
        $bsclass = $config->{$bsclasssettingname};

        // Info banner ordering.
        $ordersettingname = 'infobanner'.$i.'order';
        $order = $config->{$ordersettingname};

        // Info banner dismissible status (but not on the login page as the user preference can't be stored there).
        if ($PAGE->pagelayout != 'login') {
            $dismissiblesettingname = 'infobanner'.$i.'dismissible';
            if ($config->{$dismissiblesettingname} == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                $dismissible = true;
            } else {
                $dismissible = false;
            }
        } else {
            $dismissible = false;
        }

        // If the info banner is dismissible.
        if ($dismissible == true) {
            // Remember if we need the dismissible AMD module.
            $dismissibleamdneeded = true;
        }

        // Compose and remember this info banner's object.
        $infobanner = new stdClass();
        $infobanner->content = $content;
        $infobanner->bsclass = $bsclass;
        $infobanner->order = $order;
        $infobanner->dismissible = $dismissible;
        $infobanner->no = $i;
        $infobanners[$i] = $infobanner;
    }
}

// Reorder the info banners based on their order settings.
usort($infobanners, 'theme_boost_union_compare_order');

// Add info banners data to templatecontext.
$templatecontext['infobanners'] = $infobanners;

// Add the dismissible AMD module to the page if needed.
if ($dismissibleamdneeded == true) {
    $PAGE->requires->js_call_amd('theme_boost_union/infobanner', 'init');
}
