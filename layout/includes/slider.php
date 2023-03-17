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
 * Theme Boost Union - scrollspy include.
 *
 * @package   theme_boost_union
 * @copyright 2023 Annika Lambert <annika.lambert@itc.ruhr-uni-bochum.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Require the necessary libraries.
require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

// Get theme config.
$config = get_config('theme_boost_union');

$generalsettings = new stdClass();
$generalsettings->show = $config->{'slideractivatedsetting'};

// Getting and setting the Slider position on the frontpage.
switch ($config->{'sliderpositiononfrontpage'}) {
    case THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_BEFORE:
        $templatecontext['sliderpositionbefore'] = true;
        $templatecontext['sliderpositionafter'] = false;
        break;
    case THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_AFTER:
        $templatecontext['sliderpositionbefore'] = false;
        $templatecontext['sliderpositionafter'] = true;
}

if ($generalsettings->show) {

    $generalsettings->showarrownav = $config->{'sliderarrownavsetting'};
    $generalsettings->showindicatornav = $config->{'sliderindicatornavsetting'};

    switch ($config->{'slideranimationsetting'}) {
        case 0:
            $generalsettings->animation = "slide";
            break;
        case 1:
            $generalsettings->animation = "slide carousel-fade";
            break;
        case 2:
            $generalsettings->animation = "";
    }
    if ($config->{'sliderintervalsetting'} < 1000) {
        $generalsettings->interval = 1000;
    } else if ($config->{'sliderintervalsetting'} > 10000) {
        $generalsettings->interval = 10000;
    } else {
        $generalsettings->interval = $config->{'sliderintervalsetting'};
    }

    // Bootstrap mixed-value logic.
    switch ($config->{'sliderridesetting'}) {
        case 0:
            $templatecontext['ride'] = "carousel";
            break;
        case 1:
            $templatecontext['ride'] = "true";
            break;
        case 2:
            $templatecontext['ride'] = "false";
    }

    $generalsettings->ride = $templatecontext['ride'];

    /**
     * Maps boolean values (true/false) to corresponding string values ("true"/"false")
     *
     * PHP translates booleans to 1/0 instead of true/false. Bootstrap needs string boolean values.
     */
    function boolean_to_string ($var) {
        if ($var == 1) {
            return "true";
        } else {
            return "false";
        }
    }
    $generalsettings->keyboard = boolean_to_string($config->{'sliderkeyboardsetting'});
    $generalsettings->pause = boolean_to_string($config->{'sliderpausesetting'});
    $generalsettings->wrap = boolean_to_string($config->{'sliderwrapsetting'});


    $templatecontext['slidergeneralsettings'] = $generalsettings;


    $slides = [];
    for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_SLIDES_COUNT; $i++) {
        $sliderimage = theme_boost_union_get_urlofsliderimage($i);
        if ($sliderimage && $config->{'slide' . $i . 'enabled'} == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            $slidercontent = new stdClass();
            $slidercontent->count = count($slides);
            $slidercontent->image = $sliderimage;
            $slidercontent->imagetitle = $config->{'oneslideimagetitle' . $i};
            $slidercontent->link = $config->{'oneslidelink' . $i};
            $slidercontent->linktitle = $config->{'oneslidelinktitle' . $i};
            $slidercontent->caption = $config->{'oneslidecaption' . $i};
            $slidercontent->content = $config->{'oneslidecontent' . $i};
            array_push($slides, $slidercontent);
        }
    }
    $templatecontext['slidecontent'] = $slides;
}
