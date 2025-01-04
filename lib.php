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
 * Theme Boost Union - Library
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Constants which are use throughout this theme.
define('THEME_BOOST_UNION_SETTING_SELECT_YES', 'yes');
define('THEME_BOOST_UNION_SETTING_SELECT_NO', 'no');

define('THEME_BOOST_UNION_SETTING_SELECT_NOCHANGE', 'nochange');

define('THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_NONE', 'none');
define('THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTNOTE', 'footnote');
define('THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTER', 'footer');
define('THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_BOTH', 'both');

define('THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_HOME', 'home');
define('THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_MYHOME', 'myhome');
define('THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_MYCOURSES', 'courses');
define('THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_SITEADMIN', 'siteadminnode');

define('THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT', 5);
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_MY', 'mydashboard');
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_MYCOURSES', 'mycourses');
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_SITEHOME', 'frontpage');
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_COURSE', 'course');
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_LOGIN', 'login');
define('THEME_BOOST_UNION_SETTING_INFOBANNERMODE_PERPETUAL', 'perp');
define('THEME_BOOST_UNION_SETTING_INFOBANNERMODE_TIMEBASED', 'time');

define('THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_COUNT', 12);
define('THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_COLUMN_COUNT', 4);
define('THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_FRONTPAGEPOSITION_BEFORE', 1);
define('THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_FRONTPAGEPOSITION_AFTER', 2);

define('THEME_BOOST_UNION_SETTING_SLIDES_COUNT', 6);
define('THEME_BOOST_UNION_SETTING_SLIDER_ANIMATIONTYPE_NONE', 0);
define('THEME_BOOST_UNION_SETTING_SLIDER_ANIMATIONTYPE_SLIDE', 1);
define('THEME_BOOST_UNION_SETTING_SLIDER_ANIMATIONTYPE_FADE', 2);
define('THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_BEFOREBEFORE', 1);
define('THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_BEFOREAFTER', 2);
define('THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_AFTERBEFORE', 3);
define('THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_AFTERAFTER', 4);
define('THEME_BOOST_UNION_SETTING_SLIDER_RIDE_ONPAGELOAD', 0);
define('THEME_BOOST_UNION_SETTING_SLIDER_RIDE_AFTERINTERACTION', 1);
define('THEME_BOOST_UNION_SETTING_SLIDER_RIDE_NEVER', 2);
define('THEME_BOOST_UNION_SETTING_SLIDER_LINKSOURCE_BOTH', 0);
define('THEME_BOOST_UNION_SETTING_SLIDER_LINKSOURCE_IMAGE', 1);
define('THEME_BOOST_UNION_SETTING_SLIDER_LINKSOURCE_TEXT', 2);

define('THEME_BOOST_UNION_SETTING_HEIGHT_100PX', '100px');
define('THEME_BOOST_UNION_SETTING_HEIGHT_150PX', '150px');
define('THEME_BOOST_UNION_SETTING_HEIGHT_200PX', '200px');
define('THEME_BOOST_UNION_SETTING_HEIGHT_250PX', '250px');

define('THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER', 'center center');
define('THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP', 'center top');
define('THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM', 'center bottom');
define('THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP', 'left top');
define('THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER', 'left center');
define('THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM', 'left bottom');
define('THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP', 'right top');
define('THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER', 'right center');
define('THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM', 'right bottom');

define('THEME_BOOST_UNION_SETTING_COURSEIMAGELAYOUT_STACKEDDARK', 'stackeddark');
define('THEME_BOOST_UNION_SETTING_COURSEIMAGELAYOUT_STACKEDLIGHT', 'stackedlight');
define('THEME_BOOST_UNION_SETTING_COURSEIMAGELAYOUT_HEADINGABOVE', 'headingabove');

define('THEME_BOOST_UNION_SETTING_COMPLETIONINFOPOSITION_STARTOFLINE', 'startofline');
define('THEME_BOOST_UNION_SETTING_COMPLETIONINFOPOSITION_ENDOFLINE', 'endofline');
define('THEME_BOOST_UNION_SETTING_COMPLETIONINFOPOSITION_ICONCOLOR', 'iconcolor');

define('THEME_BOOST_UNION_SETTING_CONTENTSTYLE_NOCHANGE', 'nochange');
define('THEME_BOOST_UNION_SETTING_CONTENTSTYLE_LIGHT', 'light');
define('THEME_BOOST_UNION_SETTING_CONTENTSTYLE_LIGHTSHADOW', 'lightshadow');
define('THEME_BOOST_UNION_SETTING_CONTENTSTYLE_DARK', 'dark');
define('THEME_BOOST_UNION_SETTING_CONTENTSTYLE_DARKSHADOW', 'darkshadow');

define('THEME_BOOST_UNION_SETTING_LINKTARGET_SAMEWINDOW', 'same');
define('THEME_BOOST_UNION_SETTING_LINKTARGET_NEWTAB', 'new');

define('THEME_BOOST_UNION_SETTING_LOGINFORMPOS_CENTER', 'center');
define('THEME_BOOST_UNION_SETTING_LOGINFORMPOS_LEFT', 'left');
define('THEME_BOOST_UNION_SETTING_LOGINFORMPOS_RIGHT', 'right');

define('THEME_BOOST_UNION_SETTING_NAVBARCOLOR_LIGHT', 'light');
define('THEME_BOOST_UNION_SETTING_NAVBARCOLOR_DARK', 'dark');
define('THEME_BOOST_UNION_SETTING_NAVBARCOLOR_PRIMARYLIGHT', 'primarylight');
define('THEME_BOOST_UNION_SETTING_NAVBARCOLOR_PRIMARYDARK', 'primarydark');

define('THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSPLACEMENT_NEXTMAINCONTENT', 'nextmaincontent');
define('THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSPLACEMENT_NEARWINDOW', 'nearwindowedges');
define('THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSWITH_FULLWIDTH', 'fullwidth');
define('THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSWITH_COURSECONTENTWIDTH', 'coursecontentwidth');
define('THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSWITH_HEROWIDTH', 'herowidth');

define('THEME_BOOST_UNION_SETTING_ENABLEFOOTER_ALL', 'enablefooterbuttonall');
define('THEME_BOOST_UNION_SETTING_ENABLEFOOTER_DESKTOP', 'enablefooterbuttondesktop');
define('THEME_BOOST_UNION_SETTING_ENABLEFOOTER_MOBILE', 'enablefooterbuttonmobile');
define('THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE', 'enablefooterbuttonnone');

define('THEME_BOOST_UNION_SETTING_COURSEOVERVIEW_SHOWCOURSEIMAGES_CARD', 'card');
define('THEME_BOOST_UNION_SETTING_COURSEOVERVIEW_SHOWCOURSEIMAGES_LIST', 'list');
define('THEME_BOOST_UNION_SETTING_COURSEOVERVIEW_SHOWCOURSEIMAGES_SUMMARY', 'summary');

define('THEME_BOOST_UNION_SETTING_MARKLINKS_WHOLEPAGE', 'wholepage');
define('THEME_BOOST_UNION_SETTING_MARKLINKS_COURSEMAIN', 'coursemain');

define('THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_NONE', 0);
define('THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_DOWNLOAD', 1);
define('THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_GITHUB', 2);

define('THEME_BOOST_UNION_SETTING_SELECT_NEVER', 'never');
define('THEME_BOOST_UNION_SETTING_SELECT_ALWAYS', 'always');
define('THEME_BOOST_UNION_SETTING_SELECT_ONLYGUESTSANDNONLOGGEDIN', 'guestandnonloggedin');

/**
 * Returns the main SCSS content.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_main_scss_content($theme) {
    global $CFG;

    // Require Boost Core library.
    require_once($CFG->dirroot.'/theme/boost/lib.php');

    // Initialize SCSS code.
    $scss = '';

    // Get and include the main SCSS from Boost Core.
    // This particularly covers the theme preset which is set in Boost Core and not Boost Union.
    $scss .= theme_boost_get_main_scss_content(\core\output\theme_config::load('boost'));

    // Include post.scss from Boost Union.
    $scss .= file_get_contents($CFG->dirroot . '/theme/boost_union/scss/boost_union/post.scss');

    // Get and include the external Post SCSS.
    // This should actually be in theme_boost_union_get_extra_scss().
    // But as the *_get_extra_scss() functions work in practice, this is not possible as the external Raw SCSS code
    // would end of _after_ the code from theme_boost_get_extra_scss() and not _before_.
    // Thus, we sadly have to get and include the external Post SCSS here already.
    $scss .= theme_boost_union_get_external_scss('post');

    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_pre_scss($theme) {
    global $CFG;

    // Require local library.
    require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

    // Pick the active flavour from the global scope.
    // This global variable is set in /theme/boost_union/flavour/styles.php.
    // It is only set by that file and only needed in this and another single function in this file.
    // This approach feels a bit hacky but it is the most efficient way to get the flavour ID into this function.
    global $themeboostunionappliedflavour;
    if (isset($themeboostunionappliedflavour)) {
        $flavourid = $themeboostunionappliedflavour;
    } else {
        $flavourid = null;
    }

    // If any flavour applies to this page.
    if ($flavourid != null) {
        // Require flavours library.
        require_once($CFG->dirroot . '/theme/boost_union/flavours/flavourslib.php');
    }

    // Initialize SCSS code.
    $scss = '';

    // You might think that this pre SCSS function is only called for the activated theme.
    // However, due to the way how the theme_*_get_pre_scss callback functions are searched and called within Boost child theme
    // hierarchy Boost Union not only gets the pre SCSS from this function here but only from theme_boost_get_pre_scss as well.
    //
    // There, the custom Pre SCSS from $theme->settings->scsspre (which hits the SCSS settings from theme_boost_union even though
    // the code is within theme_boost) is already added to the SCSS codebase.
    //
    // We have to accept this fact here and must not copy the code from theme_boost_get_pre_scss into this function.
    // Instead, we must only add additionally CSS code which is based on any Boost Union-only functionality.

    // But, well, there is one exception: Boost Union Child themes.
    // Due to the described call chain, Boost Union Child won't get all the necessary extra SCSS.
    // Thus, we fetch Boost's extra SCSS if the current theme is not Union itself (i.e. a Boost Union Child theme is active).
    if (theme_boost_union_is_active_childtheme() == true) {
        $scss .= theme_boost_get_pre_scss(\core\output\theme_config::load('boost_union'));
    }

    // Include pre.scss from Boost Union.
    $scss .= file_get_contents($CFG->dirroot . '/theme/boost_union/scss/boost_union/pre.scss');

    // Add SCSS constants for evaluating select setting values in SCSS code.
    $scss .= '$boostunionsettingyes: '.THEME_BOOST_UNION_SETTING_SELECT_YES. ";\n";
    $scss .= '$boostunionsettingno: '.THEME_BOOST_UNION_SETTING_SELECT_NO. ";\n";

    $configurable = [
        // Config key => [variableName, ...].
        'brandcolor' => ['primary'],
        'bootstrapcolorsuccess' => ['success'],
        'bootstrapcolorinfo' => ['info'],
        'bootstrapcolorwarning' => ['warning'],
        'bootstrapcolordanger' => ['danger'],
    ];

    // Define the configurables which can be overridden by flavours.
    // The key is the configurable and the value is the field name in mdl_theme_boost_union_flavours.
    $flavourconfigurable = [
        'brandcolor' => 'look_brandcolor',
        'bootstrapcolorsuccess' => 'look_bootstrapcolorsuccess',
        'bootstrapcolorinfo' => 'look_bootstrapcolorinfo',
        'bootstrapcolorwarning' => 'look_bootstrapcolorwarning',
        'bootstrapcolordanger' => 'look_bootstrapcolordanger',
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        // Get the global config value for the given config key.
        $value = get_config('theme_boost_union', $configkey);

        // If any flavour applies to this page.
        if ($flavourid != null) {
            // If the configurable can be overridden by flavours.
            if (array_key_exists($configkey, $flavourconfigurable)) {
                // Pick the flavour config key.
                $flavourconfigkey = $flavourconfigurable[$configkey];
            }
            // Get the flavour config value for the given flavour id.
            $flavourvalue = theme_boost_union_get_flavour_config_item_for_flavourid($flavourid, $flavourconfigkey);
            // If a flavour value is set.
            if ($flavourvalue != null && !empty($flavourvalue)) {
                // Override the global config value with the flavour value.
                $value = $flavourvalue;
            }
        }

        // If the value is not set, continue.
        if (!($value)) {
            continue;
        }

        // Otherwise, set the SCSS variable.
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // Overwrite Boost core SCSS variables which need units and thus couldn't be added to $configurable above.
    // Set variables which are influenced by the coursecontentmaxwidth setting.
    if (get_config('theme_boost_union', 'coursecontentmaxwidth')) {
        $scss .= '$course-content-maxwidth: '.get_config('theme_boost_union', 'coursecontentmaxwidth').";\n";
    }
    // Set variables which are influenced by the mediumcontentmaxwidth setting.
    if (get_config('theme_boost_union', 'mediumcontentmaxwidth')) {
        $scss .= '$medium-content-maxwidth: '.get_config('theme_boost_union', 'mediumcontentmaxwidth').";\n";
    }
    // Set variables which are influenced by the h5pcontentmaxwidth setting.
    if (get_config('theme_boost_union', 'h5pcontentmaxwidth')) {
        $scss .= '$h5p-content-maxwidth: '.get_config('theme_boost_union', 'h5pcontentmaxwidth').";\n";
    }
    // Set variables which are influenced by the courseindexdrawerwidth setting.
    if (get_config('theme_boost_union', 'courseindexdrawerwidth')) {
        $scss .= '$drawer-width: '.get_config('theme_boost_union', 'courseindexdrawerwidth').";\n";
        $scss .= '$drawer-left-width: '.get_config('theme_boost_union', 'courseindexdrawerwidth').";\n";
    }
    // Set variables which are influenced by the blockdrawerwidth setting.
    if (get_config('theme_boost_union', 'blockdrawerwidth')) {
        $scss .= '$drawer-right-width: '.get_config('theme_boost_union', 'blockdrawerwidth').";\n";
    }

    // Set variables which are influenced by the activityiconcolor* settings.
    $purposes = [MOD_PURPOSE_ADMINISTRATION,
            MOD_PURPOSE_ASSESSMENT,
            MOD_PURPOSE_COLLABORATION,
            MOD_PURPOSE_COMMUNICATION,
            MOD_PURPOSE_CONTENT,
            MOD_PURPOSE_INTERACTIVECONTENT,
            MOD_PURPOSE_INTERFACE];
    // Iterate over all purposes.
    foreach ($purposes as $purpose) {
        // Get color setting from global settings.
        $activityiconcolor = get_config('theme_boost_union', 'activityiconcolor'.$purpose);

        // If any flavour applies to this page.
        if ($flavourid != null) {
            // Get color setting from flavour.
            $activityiconcolorflavour = theme_boost_union_get_flavour_config_item_for_flavourid($flavourid,
                    'look_aicol'.$purpose);

            // If a flavour color is set.
            if (!empty($activityiconcolorflavour)) {
                // Override the global color setting with the flavour color setting.
                $activityiconcolor = $activityiconcolorflavour;
            }
        }

        // If a color is set.
        if (!empty($activityiconcolor)) {
            // Set the activity-icon-*-bg variable which was replaced by the CSS filters in Moodle 4.4 but which is still part
            // of the codebase.
            $scss .= '$activity-icon-'.$purpose.'-bg: '.$activityiconcolor.";\n";

            // Set the activity-icon-*-filter variable which holds the CSS filters for the activity icon colors now.
            $solver = new \theme_boost_union\lib\hextocssfilter\solver($activityiconcolor);
            $cssfilterresult = $solver->solve();
            $scss .= '$activity-icon-'.$purpose.'-filter: '.$cssfilterresult['filter'].";\n";
        }
    }

    // Set custom Boost Union SCSS variable: The block region outside left width.
    $blockregionoutsideleftwidth = get_config('theme_boost_union', 'blockregionoutsideleftwidth');
    // If the setting is not set.
    if (!$blockregionoutsideleftwidth) {
        // Set the variable to the default setting to make sure that the SCSS variable does not remain uninitialized.
        $blockregionoutsideleftwidth = '300px';
    }
    $scss .= '$blockregionoutsideleftwidth: '.$blockregionoutsideleftwidth.";\n";

    // Set custom Boost Union SCSS variable: The block region outside left width.
    $blockregionoutsiderightwidth = get_config('theme_boost_union', 'blockregionoutsiderightwidth');
    // If the setting is not set.
    if (!$blockregionoutsiderightwidth) {
        // Set the variable to the default setting to make sure that the SCSS variable does not remain uninitialized.
        $blockregionoutsiderightwidth = '300px';
    }
    $scss .= '$blockregionoutsiderightwidth: '.$blockregionoutsiderightwidth.";\n";

    // Add custom Boost Union SCSS variable as goody for designers: $themerev.
    $scss .= '$themerev: '.$CFG->themerev.";\n";

    // Get and include the external Pre SCSS.
    $scss .= theme_boost_union_get_external_scss('pre');

    // If any flavour applies to this page.
    if ($flavourid != null) {
        // If there is any raw Pre SCSS in the flavour.
        $flavourrawscsspre = theme_boost_union_get_flavour_config_item_for_flavourid($flavourid, 'look_rawscsspre');
        // Append it to the SCSS stack.
        if ($flavourrawscsspre != null && !empty($flavourrawscsspre)) {
            $scss .= $flavourrawscsspre;
        }
    }

    return $scss;
}

/**
 * Inject additional SCSS.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_extra_scss($theme) {
    global $CFG;

    // Require the necessary libraries.
    require_once($CFG->dirroot . '/course/lib.php');

    // Pick the active flavour from the global scope.
    // This global variable is set in /theme/boost_union/flavour/styles.php.
    // It is only set by that file and only needed in this and another single function in this file.
    // This approach feels a bit hacky but it is the most efficient way to get the flavour ID into this function.
    global $themeboostunionappliedflavour;
    if (isset($themeboostunionappliedflavour)) {
        $flavourid = $themeboostunionappliedflavour;
    } else {
        $flavourid = null;
    }

    // If any flavour applies to this page.
    if ($flavourid != null) {
        // Require flavours library.
        require_once($CFG->dirroot . '/theme/boost_union/flavours/flavourslib.php');
    }

    // Initialize extra SCSS.
    $content = '';

    // You might think that this extra SCSS function is only called for the activated theme.
    // However, due to the way how the theme_*_get_extra_scss callback functions are searched and called within Boost child theme
    // hierarchy Boost Union not only gets the extra SCSS from this function here but only from theme_boost_get_extra_scss as well.
    //
    // There, the CSS snippets for the background image and the login background images are added already to the SCSS codebase.
    // Additionally, the custom SCSS from $theme->settings->scss (which hits the SCSS settings from theme_boost_union even though
    // the code is within theme_boost) is already added to the SCSS codebase as well.
    //
    // We have to accept this fact here and must not copy the code from theme_boost_get_extra_scss into this function.
    // Instead, we must only add additionally CSS code which is based on any Boost Union-only functionality.

    // But, well, there is one exception: Boost Union Child themes.
    // Due to the described call chain, Boost Union Child won't get all the necessary extra SCSS.
    // Thus, we fetch Boost's extra SCSS if the current theme is not Union itself (i.e. a Boost Union Child theme is active).
    if (theme_boost_union_is_active_childtheme() == true) {
        $content .= theme_boost_get_extra_scss(\core\output\theme_config::load('boost_union'));
    }

    // Now, in contrast to Boost core, Boost Union should add the login page background to the body element as well.
    // Thus, check if a login background image is set.
    $loginbackgroundimagepresent = get_config('theme_boost_union', 'loginbackgroundimage');
    if (!empty($loginbackgroundimagepresent)) {
        // We first have to revert the background which is set to #page on the login page by Boost core already.
        // Doing this, we also have to make the background of the #page element transparent on the login page.
        $content .= 'body.pagelayout-login #page { ';
        $content .= "background-image: none !important;";
        $content .= "background-color: transparent !important;";
        $content .= '}';

        // Afterwards, we set the background-size attribute for the body element again.
        $content .= 'body.pagelayout-login { ';
        $content .= "background-size: cover;";
        $content .= '}';

        // Finally, we add all possible background image urls which will be picked based on the (random) loginpageimage class.
        $content .= theme_boost_union_get_loginbackgroundimage_scss();
    }

    // Boost core has the behaviour that the normal background image is not shown on the login page, only the login background image
    // is shown on the login page.
    // This is fine, but it is done improperly as the normal background image is still there on the login page and just overlaid
    // with a grey color in the #page element. This can result in flickering during the page load.
    // We try to avoid this by removing the background image from the body tag if no login background image is set.
    if (empty($loginbackgroundimagepresent)) {
        $content .= 'body.pagelayout-login { ';
        $content .= "background-image: none !important;";
        $content .= '}';
    }

    // If a login background image is present, we set its background image position.
    if (!empty($loginbackgroundimagepresent)) {
        $content .= 'body.pagelayout-login { ';
        $content .= "background-position: ".get_config('theme_boost_union', 'loginbackgroundimageposition').";";
        $content .= '}';
    }
    // And we set the normal background image position in any case.
    $content .= 'body { ';
    $content .= "background-position: ".get_config('theme_boost_union', 'backgroundimageposition').";";

    // Lastly, we make sure that the (normal and login) background image is fixed and not repeated. Just to be sure.
    $content .= "background-repeat: no-repeat;";
    $content .= "background-attachment: fixed;";
    $content .= '}';

    // One more thing: Boost Union is also capable of overriding the background image and background image position in its flavours.
    // So, if any flavour applies to this page.
    if ($flavourid != null) {
        // And if the flavour has a background image.
        $backgroundimage = theme_boost_union_get_flavour_config_item_for_flavourid($flavourid, 'look_backgroundimage');
        if ($backgroundimage != null && !empty($backgroundimage)) {
            // Compose the URL to the flavour's background image.
            $backgroundimageurl = moodle_url::make_pluginfile_url(
                    context_system::instance()->id, 'theme_boost_union', 'flavours_look_backgroundimage', $flavourid,
                    '/'.theme_get_revision(), '/'.$backgroundimage);

            // And add it to the SCSS code, adhering the fact that we must not overwrite the login page background image again.
            $content .= 'body:not(.pagelayout-login) { ';
            $content .= 'background-image: url("'.$backgroundimageurl.'");';
            $content .= "background-size: cover;";
            $content .= '}';
        }
        // And if a background image position is set in the flavour.
        $backgroundimageposition = theme_boost_union_get_flavour_config_item_for_flavourid($flavourid,
                'look_backgroundimagepos');
        if ($backgroundimageposition != null && $backgroundimageposition != THEME_BOOST_UNION_SETTING_SELECT_NOCHANGE) {
            // Set the background position in the SCSS code, adhering the fact that we must not overwrite the login page
            // background image position again.
            $content .= 'body:not(.pagelayout-login) { ';
            $content .= "background-position: ".$backgroundimageposition.";";
            $content .= '}';
        }
    }

    // Now we want to add the custom SCSS from the flavour.
    // If any flavour applies to this page.
    if ($flavourid != null) {
        // If there is any raw SCSS in the flavour.
        $flavourrawscss = theme_boost_union_get_flavour_config_item_for_flavourid($flavourid, 'look_rawscss');
        // Append it to the SCSS stack.
        if ($flavourrawscss != null && !empty($flavourrawscss)) {
            $content .= $flavourrawscss;
        }
    }

    // For the rest of this function, we add SCSS snippets to the SCSS stack based on enabled admin settings.
    // This is done here as it is quite easy to do. As an alternative, it could also been done in post.scss by using
    // SCSS variables with @if conditions and SCSS variables. However, we preferred to do it here in a single place.

    // Setting: Activity icon purpose.
    $content .= theme_boost_union_get_scss_for_activity_icon_purpose($theme);

    // Setting: Mark external links.
    $content .= theme_boost_union_get_scss_to_mark_external_links($theme);

    // Setting: Mark mailto links.
    $content .= theme_boost_union_get_scss_to_mark_mailto_links($theme);

    // Setting: Mark broken links.
    $content .= theme_boost_union_get_scss_to_mark_broken_links($theme);

    // Setting: Course overview block.
    $content .= theme_boost_union_get_scss_courseoverview_block($theme);

    // Setting: Login order.
    $content .= theme_boost_union_get_scss_login_order($theme);

    return $content;
}

/**
 * Get compiled css.
 *
 * @return string compiled css
 */
function theme_boost_union_get_precompiled_css() {
    global $CFG;
    // Get the fallback CSS file from Boost Core as long as Boost Union does not use a fallback file of its own.
    return file_get_contents($CFG->dirroot . '/theme/boost/style/moodle.css');
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_boost_union_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    global $CFG;

    // Serve the (general) logo files or favicon file from the theme settings.
    // This code is copied and modified from core_admin_pluginfile() in admin/lib.php.
    if (in_array($filearea, ['logo', 'logocompact', 'favicon'])) {
        $size = array_shift($args); // The path hides the size.
        $itemid = clean_param(array_shift($args), PARAM_INT);
        $filename = clean_param(array_shift($args), PARAM_FILE);
        $themerev = theme_get_revision();
        if ($themerev <= 0) {
            // Normalise to 0 as -1 doesn't place well with paths.
            $themerev = 0;
        }

        // Extract the requested width and height.
        $maxwidth = 0;
        $maxheight = 0;
        if (preg_match('/^\d+x\d+$/', $size)) {
            list($maxwidth, $maxheight) = explode('x', $size);
            $maxwidth = clean_param($maxwidth, PARAM_INT);
            $maxheight = clean_param($maxheight, PARAM_INT);
        }

        $lifetime = 0;
        if ($itemid > 0 && $themerev == $itemid) {
            // The itemid is $CFG->themerev, when 0 or less no caching. Also no caching when they don't match.
            $lifetime = DAYSECS * 60;
        }

        // Anyone, including guests and non-logged in users, can view the logos.
        $options = ['cacheability' => 'public'];

        // Check if we've got a cached file to return. When lifetime is 0 then we don't want to cached one.
        $candidate = $CFG->localcachedir . "/theme_boost_union/$themerev/$filearea/{$maxwidth}x{$maxheight}/$filename";
        if (file_exists($candidate) && $lifetime > 0) {
            send_file($candidate, $filename, $lifetime, 0, false, false, '', false, $options);
        }

        // Find the original file.
        $fs = get_file_storage();
        $filepath = "/{$context->id}/theme_boost_union/{$filearea}/0/{$filename}";
        if (!$file = $fs->get_file_by_hash(sha1($filepath))) {
            send_file_not_found();
        }

        // Check whether width/height are specified, and we can resize the image (some types such as ICO cannot be resized).
        if (($maxwidth === 0 && $maxheight === 0) ||
                !$filedata = $file->resize_image($maxwidth, $maxheight)) {

            if ($lifetime) {
                file_safe_save_content($file->get_content(), $candidate);
            }
            send_stored_file($file, $lifetime, 0, false, $options);
        }

        // If we don't want to cached the file, serve now and quit.
        if (!$lifetime) {
            send_content_uncached($filedata, $filename);
        }

        // Save, serve and quit.
        file_safe_save_content($filedata, $candidate);
        send_file($candidate, $filename, $lifetime, 0, false, false, '', false, $options);

        // Serve all other (general) image and resource files from the theme settings.
        // This code is copied and modified from theme_boost_pluginfile() in theme/boost/lib.php.
    } else if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'backgroundimage' ||
        $filearea === 'loginbackgroundimage' || $filearea === 'additionalresources' ||
                $filearea === 'customfonts' || $filearea === 'courseheaderimagefallback' ||
                $filearea === 'touchiconsios' ||
                preg_match("/tilebackgroundimage[2-9]|1[0-2]?/", $filearea) ||
                preg_match("/slidebackgroundimage[2-9]|1[0-2]?/", $filearea))) {
        $theme = \core\output\theme_config::load('boost_union');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);

        // Serve the files from the theme flavours.
    } else if ($filearea === 'flavours_look_logocompact' || $filearea === 'flavours_look_logo' ||
            $filearea === 'flavours_look_favicon' || $filearea === 'flavours_look_backgroundimage') {
        // Flavour files should not be top secret.
        // Even if they apply to particular contexts or cohorts, we do not do any hard checks if a user should be
        // allowed to request a file.
        // We just make sure that the forcelogin setting is respected. This is ok as there isn't any possibility
        // to apply a flavour to the login page / for non-logged-in users at the moment.
        if ($CFG->forcelogin) {
            require_login();
        }

        // Get file storage.
        $fs = get_file_storage();

        // Get the file from the filestorage.
        $filename = clean_param(array_pop($args), PARAM_FILE);
        array_pop($args); // This is the themerev number in the $args array which is used for browser caching, here we ignore it.
        $itemid = clean_param(array_pop($args), PARAM_INT);
        if ((!$file = $fs->get_file($context->id, 'theme_boost_union', $filearea, $itemid, '/', $filename)) ||
                $file->is_directory()) {
            send_file_not_found();
        }

        // Unlock session during file serving.
        \core\session\manager::write_close();

        // Send stored file (and cache it for 90 days, similar to other static assets within Moodle).
        send_stored_file($file, DAYSECS * 90, 0, $forcedownload, $options);

        // Serve the files from the smart menu card images.
    } else if ($filearea === 'smartmenus_itemimage' && $context->contextlevel === CONTEXT_SYSTEM) {
        // Get file storage.
        $fs = get_file_storage();

        // Get the file from the filestorage.
        $file = $fs->get_file($context->id, 'theme_boost_union', $filearea, $args[0], '/', $args[1]);
        if (!$file) {
            send_file_not_found();
        }

        // Send stored file (and cache it for 90 days, similar to other static assets within Moodle).
        send_stored_file($file, DAYSECS * 90, 0, $forcedownload, $options);

    } else {
        send_file_not_found();
    }
}

/**
 * Callback to add head elements (for releases up to Moodle 4.3).
 *
 * @return string
 */
function theme_boost_union_before_standard_html_head() {
    global $CFG;

    // Require local library.
    require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

    // Call and return callback implementation.
    return theme_boost_union_callbackimpl_before_standard_html();
}

/**
 * Callback to add body elements on top (for releases up to Moodle 4.3).
 *
 * @return string
 * @throws coding_exception
 * @throws dml_exception
 */
function theme_boost_union_before_standard_top_of_body_html() {
    global $CFG;

    // Require local library.
    require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

    // Call and return callback implementation.
    return theme_boost_union_callbackimpl_before_standard_top_of_body_html();
}

/**
 * Fetches the list of icons and creates an icon suggestion list to be sent to a fragment.
 *
 * @param array $args An array of arguments.
 * @return string The rendered HTML of the icon suggestion list.
 */
function theme_boost_union_output_fragment_icons_list($args) {
    global $OUTPUT, $PAGE;

    // Proceed only if a context was given as argument.
    if ($args['context']) {
        // Initialize rendered icon list.
        $icons = [];

        // Load the theme config.
        $theme = \core\output\theme_config::load($PAGE->theme->name);

        // Get the FA system.
        $faiconsystem = \core\output\icon_system_fontawesome::instance($theme->get_icon_system());

        // Get the icon list.
        $iconlist = $faiconsystem->get_core_icon_map();

        // Add an empty element to the beginning of the icon list.
        array_unshift($iconlist, '');

        // Iterate over the icons.
        foreach ($iconlist as $iconkey => $icontxt) {
            // Split the component from the icon key.
            $icon = explode(':', $iconkey);

            // Pick the icon key.
            $iconstr = isset($icon[1]) ? $icon[1] : 'moodle';

            // Pick the component.
            $component = isset($icon[0]) ? $icon[0] : '';

            // Render the pix icon.
            $icon = new \core\output\pix_icon($iconstr,  "", $component);
            $icons[] = [
                'icon' => $faiconsystem->render_pix_icon($OUTPUT, $icon),
                'value' => $iconkey,
                'label' => $icontxt,
            ];
        }

        // Return the rendered icon list.
        return $OUTPUT->render_from_template('theme_boost_union/fontawesome-iconpicker-popover', ['options' => $icons]);
    }
}

/**
 * Define preferences which may be set via the core_user_set_user_preferences external function.
 *
 * @uses \core\user::is_current_user
 *
 * @return array[]
 */
function theme_boost_union_user_preferences(): array {
    // Build preferences array.
    $preferences = [];
    for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT; $i++) {
        $preferences['theme_boost_union_infobanner'.$i.'_dismissed'] = [
            'type' => PARAM_INT,
            'null' => NULL_NOT_ALLOWED,
            'default' => 0,
            'choices' => [0, 1],
            'permissioncallback' => [\core\user::class, 'is_current_user'],
        ];
    }
    return $preferences;
}

/**
 * Returns the html for the starred courses popover menu.
 *
 * @return string
 */
function theme_boost_union_render_navbar_output() {
    global $CFG;

    // Require local library.
    require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

    // Initialize the navbar content.
    $content = '';

    // Setting: Show starred courses popover in the navbar.
    $content .= theme_boost_union_get_navbar_starredcoursespopover();

    // Return.
    return $content;
}

/**
 * Triggered as soon as practical on every moodle bootstrap before session is started.
 *
 * We use this callback function to manipulate / set settings which would normally be manipulated / set through
 * /config.php, but we do not want to urge the admin to add stuff to /config.php when installing Boost Union.
 */
function theme_boost_union_before_session_start() {
    global $CFG;

    // Note: At this point, the $PAGE object does not exist yet. Thus, we cannot quickly and reliably detect if Boost Union
    // (or a Boost Union child theme) is the active theme. Thus, the following code is executed for every theme.
    // This fact is noted in the README.

    // Require own local library.
    require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

    // Manipulate Moodle core hooks.
    theme_boost_union_manipulate_hooks();
}

/**
 * Callback function which allows themes to alter the CSS URLs.
 * We use this function to change the CSS URL to the flavour CSS URL if a flavour applies to the current page.
 *
 * @copyright 2023 Mario Wehr
 *            based on example code by Bas Brands from https://github.com/bmbrands/theme_picture/blob/change_css_urls/lib.php.
 *
 * @param mixed $urls The CSS URLs (passed as reference).
 */
function theme_boost_union_alter_css_urls(&$urls) {
    global $CFG;

    // Require flavours library.
    require_once($CFG->dirroot . '/theme/boost_union/flavours/flavourslib.php');

    // In the original code, Bas commented: "No CSS switch during behat runs, or it will take ages to run a scenario."
    // While there is a reason for this in Bas' context, We do not have to care about this as we do only change the URL
    // if a flavour applies and in these cases, the CSS must be switched in any case.

    // If any flavour applies to this page.
    $flavour = theme_boost_union_get_flavour_which_applies();
    if ($flavour != null) {
        // Iterate over the CSS URLs.
        foreach (array_keys($urls) as $i) {
            // If we have a moodle_url object.
            if ($urls[$i] instanceof \core\url) {
                // Take the flavour CSS URL and escape it to be used in a regular expression.
                $pathstyles = preg_quote($CFG->wwwroot . '/theme/styles.php', '|');
                // Replace the CSS URL with the flavour CSS URL.
                // As a result, the file /theme/boost_union/flavours/styles.php is called instead of /theme/styles.php and the
                // flavour ID is injected into the URL parameters.
                if (preg_match("|^$pathstyles(/_s)?(.*)$|", $urls[$i]->out(false), $matches)) {
                    // Do the whole operation only if slasharguments are enabled.
                    // A warning is shown on the flavour edit page if slasharguments is off.
                    if (!empty($CFG->slasharguments)) {
                        $parts = explode('/', $matches[2]);
                        $parts[3] = $flavour->id . '/' . $parts[3];
                        $urls[$i] = new moodle_url('/theme/boost_union/flavours/styles.php');
                        $urls[$i]->set_slashargument($matches[1] . join('/', $parts));
                    }
                }
            }
        }
    }
}
