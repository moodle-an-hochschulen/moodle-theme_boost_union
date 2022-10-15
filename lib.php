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
 * @copyright  2022 Moodle an Hochschulen e.V. <kontakt@moodle-an-hochschulen.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Constants which are use throughout this theme.
define('THEME_BOOST_UNION_SETTING_SELECT_YES', 'yes');
define('THEME_BOOST_UNION_SETTING_SELECT_NO', 'no');

define('THEME_BOOST_UNION_SETTING_IMPRINTLINKPOSITION_NONE', 'none');
define('THEME_BOOST_UNION_SETTING_IMPRINTLINKPOSITION_FOOTNOTE', 'footnote');
define('THEME_BOOST_UNION_SETTING_IMPRINTLINKPOSITION_FOOTER', 'footer');
define('THEME_BOOST_UNION_SETTING_IMPRINTLINKPOSITION_BOTH', 'both');

define('THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT', 5);
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_MY', 'mydashboard');
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_MYCOURSES', 'mycourses');
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_SITEHOME', 'frontpage');
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_COURSE', 'course');
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_LOGIN', 'login');
define('THEME_BOOST_UNION_SETTING_INFOBANNERMODE_PERPETUAL', 'perp');
define('THEME_BOOST_UNION_SETTING_INFOBANNERMODE_TIMEBASED', 'time');


/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    $scss .= file_get_contents($CFG->dirroot . '/theme/boost_union/scss/boost_union/pre.scss');
    if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_boost_union', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        // Safety fallback - maybe new installs etc.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost_union/scss/preset/default.scss');
    }
    $scss .= file_get_contents($CFG->dirroot . '/theme/boost_union/scss/boost_union/post.scss');

    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return array
 */
function theme_boost_union_get_pre_scss($theme) {
    global $CFG;

    $scss = '';

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

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // Overwrite Boost core SCSS variables which need units and thus couldn't be added to $configurable above.
    // Set variables which are influenced by the coursecontentmaxwidth setting.
    if (isset($theme->settings->coursecontentmaxwidth)) {
        $scss .= '$course-content-maxwidth: '.$theme->settings->coursecontentmaxwidth.";\n";
    }

    // Overwrite Boost core SCSS variables which are stored in a SCSS map and thus couldn't be added to $configurable above.
    // Set variables for the activity icon colors.
    $activityiconcolors = array();
    if (!empty($theme->settings->activityiconcoloradministration)) {
        $activityiconcolors[] = '"administration": '.$theme->settings->activityiconcoloradministration;
    }
    if (!empty($theme->settings->activityiconcolorassessment)) {
        $activityiconcolors[] = '"assessment": '.$theme->settings->activityiconcolorassessment;
    }
    if (!empty($theme->settings->activityiconcolorcollaboration)) {
        $activityiconcolors[] = '"collaboration": '.$theme->settings->activityiconcolorcollaboration;
    }
    if (!empty($theme->settings->activityiconcolorcommunication)) {
        $activityiconcolors[] = '"communication": '.$theme->settings->activityiconcolorcommunication;
    }
    if (!empty($theme->settings->activityiconcolorcontent)) {
        $activityiconcolors[] = '"content": '.$theme->settings->activityiconcolorcontent;
    }
    if (!empty($theme->settings->activityiconcolorinterface)) {
        $activityiconcolors[] = '"interface": '.$theme->settings->activityiconcolorinterface;
    }
    if (count($activityiconcolors) > 0) {
        $activityiconscss = '$activity-icon-colors: ('."\n";
        $activityiconscss .= implode(",\n", $activityiconcolors);
        $activityiconscss .= ');';
        $scss .= $activityiconscss."\n";
    }

    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_extra_scss($theme) {
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

    return $content;
}

/**
 * Get compiled css.
 *
 * @return string compiled css
 */
function theme_boost_union_get_precompiled_css() {
    global $CFG;
    return file_get_contents($CFG->dirroot . '/theme/boost_union/style/moodle.css');
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
function theme_boost_union_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'logo' || $filearea === 'backgroundimage' ||
        $filearea === 'loginbackgroundimage' || $filearea === 'favicon')) {
        $theme = theme_config::load('boost_union');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Process css for theme.
 * @param string $css
 * @param theme_config $theme
 * @return string css
 */
function theme_boost_union_process_css($css, $theme) {
    global $OUTPUT, $CFG;
    $css = theme_boost_union_blockregion($css, $theme);
    return $css;
}

/**
 * Get the Blocks region width.
 * @param string $css
 * @param theme_config $theme
 * @return string css
 */
function theme_boost_union_blockregion($css, $theme) {

    $leftregionwidth = (isset($theme->settings->leftregionwidth) &&
                ($theme->settings->leftregionwidth != '')) ? $theme->settings->leftregionwidth : '300px';
    $rightregionwidth = (isset($theme->settings->rightregionwidth) &&
                 ($theme->settings->rightregionwidth != '')) ? $theme->settings->rightregionwidth : '300px';
    $css = str_replace('[[leftregionwidth]]', $leftregionwidth, $css);
    $css = str_replace('[[rightregionwidth]]', $rightregionwidth, $css);
    return $css;
}

/**
 * Define additional block regions.
 *
 * @param array $pageregions List of page regions.
 * @return array $regions
 */
function theme_boost_union_additional_regions($pageregions=[]) {
    $regions = [
        'top' => 'outside-top',
        'footerleft' => 'footer-left',
        'footerright' => 'footer-right',
        'footercenter' => 'footer-center',
        'offcanvasleft' => 'offcanvas-left',
        'offcanvasright' => 'offcanvas-right',
        'offcanvascenter' => 'offcanvas-center',
        'left' => 'outside-left',
        'right' => 'outside-right',
        'bottom' => 'outside-bottom',
        'headertop' => 'header-top'
    ];

    return ($pageregions) ? array_intersect($regions, $pageregions) : $regions;
}
