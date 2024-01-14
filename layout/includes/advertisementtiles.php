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
 * Theme Boost Union - advertisement tiles layout include.
 *
 * @package   theme_boost_union
 * @copyright 2022 Nina Herrmann <nina.herrmann@gmx.de>
 * @copyright on behalf of Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Require the necessary libraries.
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

// Get theme config.
$config = get_config('theme_boost_union');

// Initialize templatecontext flag to show advertisement tiles or not.
$templatecontext['showadvtiles'] = false;

// Initialize advertisement tiles data for templatecontext.
$advertisementtiles = [];

// Iterate over all advertisement tiles.
for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_COUNT; $i++) {
    // If the tile is enabled? (regardless if it contains any content).
    if (isset($config->{'tile'.$i.'enabled'}) &&
            $config->{'tile'.$i.'enabled'} == THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Flip the show-advertisement-tiles flag to true.
        $templatecontext['showadvtiles'] = true;

        // Get and set the tile's title.
        $title = format_string(trim($config->{'tile'.$i.'title'}));

        // Get and set the tile's content.
        $formatoptions = ['noclean' => true];
        $content = format_text($config->{'tile'.$i.'content'}, FORMAT_HTML, $formatoptions);

        // Get and set the tile's link.
        $link = $config->{'tile'.$i.'link'};
        $linktitle = format_string(trim($config->{'tile'.$i.'linktitle'}));
        if ($config->{'tile'.$i.'linktarget'} == THEME_BOOST_UNION_SETTING_LINKTARGET_NEWTAB) {
            $linktargetnewtab = true;
        } else {
            $linktargetnewtab = false;
        }

        // Get and set the tile's background image.
        $bgimage = theme_boost_union_get_urloftilebackgroundimage($i);

        // Get and set the tile's background image position.
        $bgimageposition = $config->{'tile'.$i.'backgroundimageposition'};

        // Get and set the tile's order.
        // The order is not needed for the mustache template, but the usort() method will need it later.
        $order = $config->{'tile'.$i.'order'};

        // Get and set the tile's content style class.
        switch ($config->{'tile'.$i.'contentstyle'}) {
            case THEME_BOOST_UNION_SETTING_CONTENTSTYLE_NOCHANGE:
                $contentstyleclass = '';
                break;
            case THEME_BOOST_UNION_SETTING_CONTENTSTYLE_LIGHT:
                $contentstyleclass = 'tile-light';
                break;
            case THEME_BOOST_UNION_SETTING_CONTENTSTYLE_LIGHTSHADOW:
                $contentstyleclass = 'tile-lightshadow';
                break;
            case THEME_BOOST_UNION_SETTING_CONTENTSTYLE_DARK:
                $contentstyleclass = 'tile-dark';
                break;
            case THEME_BOOST_UNION_SETTING_CONTENTSTYLE_DARKSHADOW:
                $contentstyleclass = 'tile-darkshadow';
                break;
        }

        // Compose and remember this tile as templatecontext object.
        $advtile = new stdClass();
        $advtile->title = $title;
        $advtile->content = $content;
        $advtile->linktitle = $linktitle;
        $advtile->link = $link;
        $advtile->linktargetnewtab = $linktargetnewtab;
        $advtile->backgroundimageurl = $bgimage;
        $advtile->backgroundimageposition = $bgimageposition;
        $advtile->no = $i;
        $advtile->order = $order;
        $advtile->contentstyleclass = $contentstyleclass;
        $advertisementtiles[$i] = $advtile;
    }
}

// Only if we have any tiles to show.
if ($templatecontext['showadvtiles'] == true) {
    // Getting and setting the advertisement tiles position on the frontpage.
    switch ($config->{'tilefrontpageposition'}) {
        case THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_FRONTPAGEPOSITION_BEFORE:
            $templatecontext['advtilespositionbefore'] = true;
            $templatecontext['advtilespositionafter'] = false;
            break;
        case THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_FRONTPAGEPOSITION_AFTER:
            $templatecontext['advtilespositionbefore'] = false;
            $templatecontext['advtilespositionafter'] = true;
    }

    // Getting and setting the advertisement tiles height on the frontpage.
    $tileheight = $config->{'tileheight'};
    $templatecontext['tileheight'] = $tileheight;

    // Calculating and setting the col-x class from the number in the tilecolumns setting.
    $colclass = 'col-12';
    switch ($config->{'tilecolumns'}) {
        case 1:
            // Nothing to add in this case.
            break;
        case 2:
            $colclass .= ' col-sm-6';
            break;
        case 3:
            $colclass .= ' col-sm-6 col-md-4';
            break;
        case 4:
            $colclass .= ' col-sm-6 col-md-3';
    }
    $templatecontext['advtileslayoutclass'] = $colclass;

    // Reorder the tiles based on their order settings.
    usort($advertisementtiles, 'theme_boost_union_compare_order');

    // Add advertisement tiles data to templatecontext.
    $templatecontext['advtiles'] = $advertisementtiles;
}
