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
 * Theme Boost Union - slider layout include.
 *
 * @package   theme_boost_union
 * @copyright 2023 Annika Lambert <annika.lambert@itc.ruhr-uni-bochum.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Require the necessary libraries.
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

// Get theme config.
$config = get_config('theme_boost_union');

// Initialize templatecontext flag to show the slider or not.
$templatecontext['showslider'] = false;

// Initialize slides data for templatecontext.
$slides = [];

// Iterate over all slides.
for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_SLIDES_COUNT; $i++) {
    // If the slide is enabled? (regardless if it contains any content).
    if (isset($config->{'slide' . $i . 'enabled'}) &&
            $config->{'slide' . $i . 'enabled'} == THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Get and set the slide's background image.
        $bgimage = theme_boost_union_get_urlofslidebackgroundimage($i);

        // If the slide does not have a background image set, skip it.
        if ($bgimage == null) {
            continue;
        }

        // Flip the show-slider flag to true.
        $templatecontext['showslider'] = true;

        // Get and set the background image alt attribute.
        $backgroundimagealt = format_string(trim($config->{'slide'.$i.'backgroundimagealt'}));

        // Get and set the slide's content.
        $formatoptions = ['noclean' => true];
        $content = format_text($config->{'slide'.$i.'content'}, FORMAT_HTML, $formatoptions);

        // Get and set the slide's link.
        $link = $config->{'slide'.$i.'link'};
        $linktitle = format_string(trim($config->{'slide'.$i.'linktitle'}));
        if ($config->{'slide'.$i.'linktarget'} == THEME_BOOST_UNION_SETTING_LINKTARGET_NEWTAB) {
            $linktargetnewtab = true;
        } else {
            $linktargetnewtab = false;
        }

        // Get and set the slide's caption.
        $caption = format_string(trim($config->{'slide'.$i.'caption'}));

        // Get and set the slide's order.
        // The order is not needed for the mustache template, but the usort() method will need it later.
        $order = $config->{'slide'.$i.'order'};

        // Get and set the slide's content style class.
        switch ($config->{'slide'.$i.'contentstyle'}) {
            case THEME_BOOST_UNION_SETTING_CONTENTSTYLE_LIGHT:
                $contentstyleclass = 'slide-light';
                break;
            case THEME_BOOST_UNION_SETTING_CONTENTSTYLE_LIGHTSHADOW:
                $contentstyleclass = 'slide-lightshadow';
                break;
            case THEME_BOOST_UNION_SETTING_CONTENTSTYLE_DARK:
                $contentstyleclass = 'slide-dark';
                break;
            case THEME_BOOST_UNION_SETTING_CONTENTSTYLE_DARKSHADOW:
                $contentstyleclass = 'slide-darkshadow';
                break;
        }

        // Get and set the slide's link source.
        switch ($config->{'slide'.$i.'linksource'}) {
            case THEME_BOOST_UNION_SETTING_SLIDER_LINKSOURCE_BOTH:
                $linkimage = true;
                $linktext = true;
                break;
            case THEME_BOOST_UNION_SETTING_SLIDER_LINKSOURCE_IMAGE:
                $linkimage = true;
                $linktext = false;
                break;
            case THEME_BOOST_UNION_SETTING_SLIDER_LINKSOURCE_TEXT:
                $linkimage = false;
                $linktext = true;
                break;
        }

        // Deduce if a caption or content is set.
        if (!empty($caption) || !empty($content)) {
            $captionorcontent = true;
        } else {
            $captionorcontent = false;
        }

        // Compose and remember this slide as templatecontext object.
        $slide = new stdClass();
        $slide->content = $content;
        $slide->linktitle = $linktitle;
        $slide->link = $link;
        $slide->linktargetnewtab = $linktargetnewtab;
        $slide->backgroundimageurl = $bgimage;
        $slide->backgroundimagealt = $backgroundimagealt;
        $slide->caption = $caption;
        $slide->no = $i;
        $slide->order = $order;
        $slide->contentstyleclass = $contentstyleclass;
        $slide->captionorcontent = $captionorcontent;
        $slide->linkimage = $linkimage;
        $slide->linktext = $linktext;
        $slide->isfirstslide = false; // Here, all slides are marked with false. This will be changed after ordering shortly.
        $slides[$i] = $slide;
    }
}

// Only if we have any slide to show.
if ($templatecontext['showslider'] == true) {
    // Getting and setting the slider position on the frontpage.
    switch ($config->{'sliderfrontpageposition'}) {
        case THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_BEFOREBEFORE:
            $templatecontext['sliderpositionbeforebefore'] = true;
            $templatecontext['sliderpositionbeforeafter'] = false;
            $templatecontext['sliderpositionafterbefore'] = false;
            $templatecontext['sliderpositionafterafter'] = false;
            break;
        case THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_BEFOREAFTER:
            $templatecontext['sliderpositionbeforebefore'] = false;
            $templatecontext['sliderpositionbeforeafter'] = true;
            $templatecontext['sliderpositionafterbefore'] = false;
            $templatecontext['sliderpositionafterafter'] = false;
            break;
        case THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_AFTERBEFORE:
            $templatecontext['sliderpositionbeforebefore'] = false;
            $templatecontext['sliderpositionbeforeafter'] = false;
            $templatecontext['sliderpositionafterbefore'] = true;
            $templatecontext['sliderpositionafterafter'] = false;
            break;
        case THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_AFTERAFTER:
            $templatecontext['sliderpositionbeforebefore'] = false;
            $templatecontext['sliderpositionbeforeafter'] = false;
            $templatecontext['sliderpositionafterbefore'] = false;
            $templatecontext['sliderpositionafterafter'] = true;
            break;
    }

    // Initialize general slider settings object.
    $generalslidersettings = new stdClass();

    // Getting and setting the slider's navigation settings.
    switch ($config->{'sliderarrownav'}) {
        case THEME_BOOST_UNION_SETTING_SELECT_YES:
            $generalslidersettings->showarrownav = true;
            break;
        case THEME_BOOST_UNION_SETTING_SELECT_NO:
            $generalslidersettings->showarrownav = false;
            break;
    }
    switch ($config->{'sliderindicatornav'}) {
        case THEME_BOOST_UNION_SETTING_SELECT_YES:
            $generalslidersettings->showindicatornav = true;
            break;
        case THEME_BOOST_UNION_SETTING_SELECT_NO:
            $generalslidersettings->showindicatornav = false;
            break;
    }

    // Getting and setting the slider's animation setting.
    switch ($config->{'slideranimation'}) {
        case THEME_BOOST_UNION_SETTING_SLIDER_ANIMATIONTYPE_SLIDE:
            $generalslidersettings->animation = 'slide';
            break;
        case THEME_BOOST_UNION_SETTING_SLIDER_ANIMATIONTYPE_FADE:
            $generalslidersettings->animation = 'slide carousel-fade';
            break;
        case THEME_BOOST_UNION_SETTING_SLIDER_ANIMATIONTYPE_NONE:
            $generalslidersettings->animation = '';
            break;
    }

    // Getting and setting the slider's animation interval setting.
    if ($config->{'sliderinterval'} < 1000) {
        $generalslidersettings->interval = 1000;
    } else if ($config->{'sliderinterval'} > 10000) {
        $generalslidersettings->interval = 10000;
    } else {
        $generalslidersettings->interval = $config->{'sliderinterval'};
    }

    // Getting and setting the slider's cycle setting.
    switch ($config->{'sliderride'}) {
        case THEME_BOOST_UNION_SETTING_SLIDER_RIDE_ONPAGELOAD:
            $generalslidersettings->ride = 'carousel';
            break;
        case THEME_BOOST_UNION_SETTING_SLIDER_RIDE_AFTERINTERACTION:
            $generalslidersettings->ride = 'true';
            break;
        case THEME_BOOST_UNION_SETTING_SLIDER_RIDE_NEVER:
            $generalslidersettings->ride = 'false';
            break;
    }

    // Getting and setting the slider's pause setting.
    switch ($config->{'sliderpause'}) {
        case THEME_BOOST_UNION_SETTING_SELECT_YES:
            $generalslidersettings->pause = 'hover';
            break;
        case THEME_BOOST_UNION_SETTING_SELECT_NO:
            $generalslidersettings->pause = 'false';
            break;
    }

    // Getting and setting the slider's keyboard setting.
    $generalslidersettings->keyboard = theme_boost_union_yesno_to_boolstring($config->{'sliderkeyboard'});

    // Getting and setting the slider's wrap setting.
    $generalslidersettings->wrap = theme_boost_union_yesno_to_boolstring($config->{'sliderwrap'});

    // Add general slider settings to templatecontext.
    $templatecontext['slidergeneralsettings'] = $generalslidersettings;

    // Reorder the slides based on their order settings.
    usort($slides, 'theme_boost_union_compare_order');

    // Add a slideto attribute to each slide.
    // This is needed for the slide controls, based on the latest ordering and starting from 0.
    foreach ($slides as $key => $notneeded) {
        $slides[$key]->slideto = $key;
    }

    // Mark the first slide as first slide.
    $slides[0]->isfirstslide = true;

    // Add slides data to templatecontext.
    $templatecontext['slides'] = $slides;
}
