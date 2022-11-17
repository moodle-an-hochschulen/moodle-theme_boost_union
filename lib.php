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

define('THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_NONE', 'none');
define('THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTNOTE', 'footnote');
define('THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTER', 'footer');
define('THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_BOTH', 'both');

define('THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_HOME', 'home');
define('THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_MYHOME', 'myhome');
define('THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_MYCOURSES', 'courses');
define('THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_SITEADMIN', 'siteadmin');

define('THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT', 5);
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_MY', 'mydashboard');
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_MYCOURSES', 'mycourses');
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_SITEHOME', 'frontpage');
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_COURSE', 'course');
define('THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_LOGIN', 'login');
define('THEME_BOOST_UNION_SETTING_INFOBANNERMODE_PERPETUAL', 'perp');
define('THEME_BOOST_UNION_SETTING_INFOBANNERMODE_TIMEBASED', 'time');

define('THEME_BOOST_UNION_SETTING_FAVERSION_NONE', 'none');
define('THEME_BOOST_UNION_SETTING_FAVERSION_FA6FREE', 'fa6free');
define('THEME_BOOST_UNION_SETTING_FAFILES_MANDATORY', 'm');
define('THEME_BOOST_UNION_SETTING_FAFILES_OPTIONAL', 'o');


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

    // Require local library.
    require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

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

    // In contrast to Boost core, Boost Union should add the login page background to the body element as well.
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

    // Lastly, we make sure that the background image is fixed and not repeated. Just to be sure.
    $content .= 'body { ';
    $content .= "background-repeat: no-repeat;";
    $content .= "background-attachment: fixed;";
    $content .= '}';

    // Note: Boost Union is also capable of overriding the background image in its flavours.
    // In contrast to the other flavour assets like the favicon overriding, this isn't done here in place as this function
    // is composing Moodle core CSS which has to remain flavour-independent.
    // Instead, the flavour is overriding the background image later in flavours/styles.php.

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
    global $CFG;

    // Serve the files from the admin settings.
    if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'logo' || $filearea === 'backgroundimage' ||
        $filearea === 'loginbackgroundimage' || $filearea === 'favicon' || $filearea === 'additionalresources' ||
                $filearea === 'customfonts' || $filearea === 'fontawesome')) {
        $theme = theme_config::load('boost_union');
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
        $filename = array_pop($args);
        array_pop($args); // This is the themerev number in the $args array which is used for browser caching, here we ignore it.
        $itemid = array_pop($args);
        if ((!$file = $fs->get_file($context->id, 'theme_boost_union', $filearea, $itemid, '/', $filename)) ||
                $file->is_directory()) {
            send_file_not_found();
        }

        // Unlock session during file serving.
        \core\session\manager::write_close();

        // Send stored file (and cache it for 90 days, similar to other static assets within Moodle).
        send_stored_file($file, DAYSECS * 90, 0, $forcedownload, $options);

    } else {
        send_file_not_found();
    }
}

/**
 * Callback to add head elements.
 *
 * We use this callback to inject the FontAwesome CSS code and the flavour's CSS code to the page.
 *
 * @return string
 */
function theme_boost_union_before_standard_html_head() {
    global $CFG;

    // Initialize HTML (even though we do not add any HTML at this stage of the implementation).
    $html = '';

    // If another theme than Boost Union is active, return directly.
    // This is necessary as the before_standard_html_head() callback is called regardless of the active theme.
    if ($CFG->theme != 'boost_union') {
        return $html;
    }

    // Require local library.
    require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

    // Add the FontAwesome icons to the page.
    theme_boost_union_add_fontawesome_to_page();

    // Add the flavour CSS to the page.
    theme_boost_union_add_flavourcss_to_page();

    // Return an empty string to keep the caller happy.
    return $html;
}
