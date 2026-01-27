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
 * Theme Boost Union - Core renderer
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\output;

use context_course;
use context_system;
use core_course_list_element;
use coursecat_helper;
use moodle_url;
use stdClass;
use core\di;
use core\hook\manager as hook_manager;
use core\hook\output\before_standard_footer_html_generation;
use core\output\html_writer;
use core_block\output\block_contents;
use theme_boost_union\coursesettings;
use theme_boost_union\util\course;

/**
 * Extending the core_renderer interface.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_boost\output\core_renderer {
    /**
     * Returns the moodle_url for the favicon.
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * It checks if the favicon is overridden in a flavour and, if yes, it serves this favicon.
     * If there isn't a favicon in any flavour set, it serves the general favicon.
     *
     * @since Moodle 2.5.1 2.6
     * @return moodle_url The moodle_url for the favicon
     * @throws \moodle_exception
     */
    public function favicon() {
        global $CFG;

        // Initialize static variable for the flavour favicon as this function might be called (for whatever reason) multiple times
        // during a page output.
        static $hasflavourfavicon, $flavourfaviconurl;

        // If the flavour favicon has already been checked.
        if ($hasflavourfavicon != null) {
            // If there is a flavour favicon.
            if ($hasflavourfavicon == true) {
                // Directly return the flavour favicon.
                return $flavourfaviconurl;
            }
            // Otherwise, if there isn't a flavour favicon, this function will continue to run the logic from Moodle core later.

            // Otherwise.
        } else {
            // Require flavours library.
            require_once($CFG->dirroot . '/theme/boost_union/flavours/flavourslib.php');

            // If any flavour applies to this page.
            $flavour = theme_boost_union_get_flavour_which_applies();
            if ($flavour != null) {
                // If the flavour has a favicon set.
                if ($flavour->look_favicon != null) {
                    // Remember this fact for subsequent runs of this function.
                    $hasflavourfavicon = true;

                    // Compose the URL to the flavour's favicon.
                    $flavourfaviconurl = \core\url::make_pluginfile_url(
                        context_system::instance()->id,
                        'theme_boost_union',
                        'flavours_look_favicon',
                        $flavour->id,
                        '/64x64' .
                        '/' . theme_get_revision(),
                        '/' . $flavour->look_favicon
                    );

                    // Return the URL.
                    return $flavourfaviconurl;

                    // Otherwise.
                } else {
                    // Remember this fact for subsequent runs of this function.
                    $hasflavourfavicon = false;
                }
            }
        }

        // Apparently, there isn't any flavour favicon set. Let's continue with the logic to serve the general favicon.
        $logo = null;
        if (!during_initial_install()) {
            $logo = get_config('theme_boost_union', 'favicon');
        }
        if (empty($logo)) {
            return $this->image_url('favicon', 'theme');
        }

        // Use $CFG->themerev to prevent browser caching when the file changes.
        return moodle_url::make_pluginfile_url(
            context_system::instance()->id,
            'theme_boost_union',
            'favicon',
            '64x64/',
            theme_get_revision(),
            $logo
        );
    }

    /**
     * Return the site's logo URL, if any.
     *
     * This renderer function is copied and modified from /lib/classes/output/renderer_base.php
     *
     * It checks if the logo is overridden in a flavour and, if yes, it serves this logo.
     * If there isn't a logo in any flavour set, it serves the general logo.
     *
     * @param int $maxwidth The maximum width, or null when the maximum width does not matter.
     * @param int $maxheight The maximum height, or null when the maximum height does not matter.
     * @return moodle_url|false
     */
    public function get_logo_url($maxwidth = null, $maxheight = 200) {
        global $CFG;

        // Initialize static variable for the flavour logo as this function might be called (for whatever reason) multiple times
        // during a page output.
        static $hasflavourlogo, $flavourlogourl;

        // If the flavour logo has already been checked.
        if ($hasflavourlogo != null) {
            // If there is a flavour logo.
            if ($hasflavourlogo == true) {
                // Directly return the flavour logo.
                return $flavourlogourl;
            }
            // Otherwise, if there isn't a flavour logo, this function will continue to run the logic from Moodle core later.

            // Otherwise.
        } else {
            // Require flavours library.
            require_once($CFG->dirroot . '/theme/boost_union/flavours/flavourslib.php');

            // If any flavour applies to this page.
            $flavour = theme_boost_union_get_flavour_which_applies();
            if ($flavour != null) {
                // If the flavour has a logo set.
                if ($flavour->look_logo != null) {
                    // Remember this fact for subsequent runs of this function.
                    $hasflavourlogo = true;

                    // Compose the URL to the flavour's logo.
                    $flavourlogourl = \core\url::make_pluginfile_url(
                        context_system::instance()->id,
                        'theme_boost_union',
                        'flavours_look_logo',
                        $flavour->id,
                        '/' . theme_get_revision(),
                        '/' . $flavour->look_logo
                    );

                    // Return the URL.
                    return $flavourlogourl;

                    // Otherwise.
                } else {
                    // Remember this fact for subsequent runs of this function.
                    $hasflavourlogo = false;
                }
            }
        }

        // Apparently, there isn't any flavour logo set. Let's continue to serve the general logo.
        $logo = get_config('theme_boost_union', 'logo');
        if (empty($logo)) {
            return false;
        }

        // If the logo is a SVG image, do not add a size to the path.
        $logoextension = pathinfo($logo, PATHINFO_EXTENSION);
        if (in_array($logoextension, ['svg', 'svgz'])) {
            // The theme_boost_union_pluginfile() function will look for a filepath and will try to extract the size from that.
            // Thus, we cannot drop the filepath from the URL completely.
            // But we can add a path without an 'x' in it which will then be interpreted by theme_boost_union_pluginfile()
            // as "no resize requested".
            $filepath = '1/';

            // Otherwise, add a size to the path.
        } else {
            // 200px high is the default image size which should be displayed at 100px in the page to account for retina displays.
            // It's not worth the overhead of detecting and serving 2 different images based on the device.

            // Hide the requested size in the file path.
            $filepath = ((int) $maxwidth . 'x' . (int) $maxheight) . '/';
        }

        // Use $CFG->themerev to prevent browser caching when the file changes.
        return moodle_url::make_pluginfile_url(
            context_system::instance()->id,
            'theme_boost_union',
            'logo',
            $filepath,
            theme_get_revision(),
            $logo
        );
    }

    /**
     * Return the site's compact logo URL, if any.
     *
     * This renderer function is copied and modified from /lib/classes/output/renderer_base.php
     *
     * It checks if the logo is overridden in a flavour and, if yes, it serves this logo.
     * If there isn't a logo in any flavour set, it serves the general compact logo.
     *
     * @param int $maxwidth The maximum width, or null when the maximum width does not matter.
     * @param int $maxheight The maximum height, or null when the maximum height does not matter.
     * @return moodle_url|false
     */
    public function get_compact_logo_url($maxwidth = 300, $maxheight = 300) {
        global $CFG;

        // Initialize static variable for the flavour logo as this function is called (for whatever reason) multiple times
        // during a page output.
        static $hasflavourlogo, $flavourlogourl;

        // If the flavour logo has already been checked.
        if ($hasflavourlogo != null) {
            // If there is a flavour logo.
            if ($hasflavourlogo == true) {
                // Directly return the flavour logo.
                return $flavourlogourl;
            }
            // Otherwise, if there isn't a flavour logo, this function will continue to run the logic from Moodle core later.

            // Otherwise.
        } else {
            // Require flavours library.
            require_once($CFG->dirroot . '/theme/boost_union/flavours/flavourslib.php');

            // If any flavour applies to this page.
            $flavour = theme_boost_union_get_flavour_which_applies();
            if ($flavour != null) {
                // If the flavour has a compact logo set.
                if ($flavour->look_logocompact != null) {
                    // Remember this fact for subsequent runs of this function.
                    $hasflavourlogo = true;

                    // If the flavour logo is a SVG image, do not add a size to the path.
                    $flavourlogoextension = pathinfo($flavour->look_logocompact, PATHINFO_EXTENSION);
                    if (in_array($flavourlogoextension, ['svg', 'svgz'])) {
                        // The theme_boost_union_pluginfile() function will look for a filepath and will extract the size from that.
                        // If we add a path without an 'x' in it, it will then be interpreted by theme_boost_union_pluginfile()
                        // as "no resize requested".
                        // This mechanism is used for the normal compact logo as well.
                        $flavourfilepath = '1/';

                        // Otherwise, add a size to the path.
                    } else {
                        // Hide the requested size in the file path.
                        $flavourfilepath = ((int)$maxwidth . 'x' . (int)$maxheight) . '/';
                    }

                    // Compose the URL to the flavour's compact logo.
                    $flavourlogourl = \core\url::make_pluginfile_url(
                        context_system::instance()->id,
                        'theme_boost_union',
                        'flavours_look_logocompact',
                        $flavour->id . '/' . $flavourfilepath,
                        theme_get_revision(),
                        '/' . $flavour->look_logocompact
                    );

                    // Return the URL.
                    return $flavourlogourl;

                    // Otherwise.
                } else {
                    // Remember this fact for subsequent runs of this function.
                    $hasflavourlogo = false;
                }
            }
        }

        // Apparently, there isn't any flavour logo set. Let's continue to service the general compact logo.
        $logo = get_config('theme_boost_union', 'logocompact');
        if (empty($logo)) {
            return false;
        }

        // If the logo is a SVG image, do not add a size to the path.
        $logoextension = pathinfo($logo, PATHINFO_EXTENSION);
        if (in_array($logoextension, ['svg', 'svgz'])) {
            // The theme_boost_union_pluginfile() function will look for a filepath and will try to extract the size from that.
            // Thus, we cannot drop the filepath from the URL completely.
            // But we can add a path without an 'x' in it which will then be interpreted by theme_boost_union_pluginfile()
            // as "no resize requested".
            $filepath = '1/';

            // Otherwise, add a size to the path.
        } else {
            // Hide the requested size in the file path.
            $filepath = ((int)$maxwidth . 'x' . (int)$maxheight) . '/';
        }

        // Use $CFG->themerev to prevent browser caching when the file changes.
        return moodle_url::make_pluginfile_url(
            context_system::instance()->id,
            'theme_boost_union',
            'logocompact',
            $filepath,
            theme_get_revision(),
            $logo
        );
    }

    /**
     * Returns HTML attributes to use within the body tag. This includes an ID and classes.
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * @since Moodle 2.5.1 2.6
     * @param string|array $additionalclasses Any additional classes to give the body tag,
     * @return string
     */
    public function body_attributes($additionalclasses = []) {
        global $CFG;

        // Require local libraries.
        require_once($CFG->dirroot . '/theme/boost_union/locallib.php');
        require_once($CFG->dirroot . '/theme/boost_union/flavours/flavourslib.php');

        if (!is_array($additionalclasses)) {
            $additionalclasses = explode(' ', $additionalclasses);
        }

        // If this isn't the login page and the page has a background image, add a class to the body attributes.
        if ($this->page->pagelayout != 'login') {
            if (!empty(get_config('theme_boost_union', 'backgroundimage'))) {
                $additionalclasses[] = 'backgroundimage';
            }
        }

        // If this is the login page and the page has a login background image, add a class to the body attributes.
        if ($this->page->pagelayout == 'login') {
            // Generate the background image class for displaying a random image for the login page.
            $loginimageclass = theme_boost_union_get_random_loginbackgroundimage_class();

            // If the background image class was returned, we can expect that a background image was set.
            // In this case, add both the general loginbackgroundimage class as well as the generated
            // class to the body tag.
            if ($loginimageclass != '') {
                $additionalclasses[] = 'loginbackgroundimage';
                $additionalclasses[] = $loginimageclass;
            }
        }

        // If there is a flavour applied to this page, add the flavour ID as additional body class.
        // Boost Union itself does not need this class for applying the flavour to the page (yet).
        // However, theme designers might want to use it.
        $flavour = theme_boost_union_get_flavour_which_applies();
        if ($flavour != null) {
            $additionalclasses[] = 'flavour' . '-' . $flavour->id;
        }

        // If the admin decided to change the breakpoints of the footer button,
        // add the setting as additional body class.
        // With this setting, we show and hide the footer button as well as move the buttons
        // (back-to-top and communication) which are stacked on top of the footer button upwards.
        $footerbutton = get_config('theme_boost_union', 'enablefooterbutton');
        switch ($footerbutton) {
            case THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE:
                $additionalclasses[] = 'theme_boost-union-footerbuttonnone';
                break;
            case THEME_BOOST_UNION_SETTING_ENABLEFOOTER_ALL:
                $additionalclasses[] = 'theme_boost-union-footerbuttonall';
                break;
            case THEME_BOOST_UNION_SETTING_ENABLEFOOTER_MOBILE:
                $additionalclasses[] = 'theme_boost-union-footerbuttonmobile';
                break;
            case THEME_BOOST_UNION_SETTING_ENABLEFOOTER_DESKTOP:
            default:
                $additionalclasses[] = 'theme_boost-union-footerbuttondesktop';
                break;
        }

        // If this is the login page and the page has the accessibility button, add a class to the body attributes.
        // This is currently just needed to make sure in SCSS that the footnote is not covered by the accessibility button.
        if ($this->page->pagelayout == 'login') {
            // If the accessibility button is enabled.
            $enableaccessibilitysupportsetting = get_config('theme_boost_union', 'enableaccessibilitysupport');
            $enableaccessibilitysupportfooterbuttonsetting =
                    get_config('theme_boost_union', 'enableaccessibilitysupportfooterbutton');
            if (
                isset($enableaccessibilitysupportsetting) &&
                    $enableaccessibilitysupportsetting == THEME_BOOST_UNION_SETTING_SELECT_YES &&
                    isset($enableaccessibilitysupportfooterbuttonsetting) &&
                    $enableaccessibilitysupportfooterbuttonsetting == THEME_BOOST_UNION_SETTING_SELECT_YES
            ) {
                // If user login is either not required or if the user is logged in.
                $allowaccessibilitysupportwithoutloginsetting =
                        get_config('theme_boost_union', 'allowaccessibilitysupportwithoutlogin');
                if (
                    !(isset($allowaccessibilitysupportwithoutloginsetting) &&
                        $allowaccessibilitysupportwithoutloginsetting != THEME_BOOST_UNION_SETTING_SELECT_YES) ||
                        (isloggedin() && !isguestuser())
                ) {
                    $additionalclasses[] = 'theme_boost-union-accessibilitybutton';
                }
            }
        }

        return ' id="' . $this->body_id() . '" class="' . $this->body_css_classes($additionalclasses) . '"';
    }

    /**
     * Wrapper for header elements.
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        $pagetype = $this->page->pagetype;
        $homepage = get_home_page();
        $homepagetype = null;
        // Add a special case since /my/courses is a part of the /my subsystem.
        if ($homepage == HOMEPAGE_MY || $homepage == HOMEPAGE_MYCOURSES) {
            $homepagetype = 'my-index';
        } else if ($homepage == HOMEPAGE_SITE) {
            $homepagetype = 'site-index';
        }
        if (
            $this->page->include_region_main_settings_in_header_actions() &&
                !$this->page->blocks->is_block_present('settings')
        ) {
            // Only include the region main settings if the page has requested it and it doesn't already have
            // the settings block on it. The region main settings are included in the settings block and
            // duplicating the content causes behat failures.
            $this->page->add_header_action(html_writer::div(
                $this->region_main_settings_menu(),
                'd-print-none',
                ['id' => 'region-main-settings-menu']
            ));
        }

        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($this->page->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();
        $header->headeractions = $this->page->get_header_actions();

        // Initialize a marker that course header is not enabled.
        $header->courseheaderenabled = false;

        // If we are on a course page.
        if ($this->page->pagelayout == 'course') {
            // If enabled, add the enhanced course header data for rendering.
            if (coursesettings::get_config_with_course_override('courseheaderenabled') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                // If course headers are activated, we get the course header image url
                // (which might be the global image depending on the course settings and theme settings).
                $courseheaderimageurl = theme_boost_union_get_course_header_image_url();

                // Get the course header background type setting.
                $courseheaderimagerequirement = get_config('theme_boost_union', 'courseheaderimagerequirement');

                // If there is no course header image url and the background type is set to show standard header only in this case,
                // we don't enable the enhanced course header.
                if (
                    empty($courseheaderimageurl) &&
                        $courseheaderimagerequirement == THEME_BOOST_UNION_SETTING_COURSEHEADERIMAGEREQUIREMENT_STANDARDONLY
                ) {
                    // Don't set course header as enabled, so the standard course header will be used.
                    $header->courseheaderenabled = false;
                } else {
                    // Set a marker that course header is enabled.
                    $header->courseheaderenabled = true;
                    // Set the course header image url (might be empty if background type allows it).
                    $header->courseheaderimageurl = $courseheaderimageurl;
                    // Additionally, get the course header height.
                    $header->courseheaderheight = coursesettings::get_config_with_course_override('courseheaderheight');
                    // Additionally, get the course header image position.
                    $header->courseheaderimageposition =
                            coursesettings::get_config_with_course_override('courseheaderimageposition');
                    // Additionally, get the course header canvas border and background and determine the CSS classes.
                    $courseheadercanvasborder = coursesettings::get_config_with_course_override('courseheadercanvasborder');
                    $courseheadercanvasbackground = coursesettings::get_config_with_course_override('courseheadercanvasbackground');
                    // Build the CSS header canvas class string including withimage/withoutimage and background classes.
                    $canvasclasses = [];
                    // Add withimage or withoutimage class.
                    if (!empty($courseheaderimageurl)) {
                        $canvasclasses[] = 'withimage';
                    } else {
                        $canvasclasses[] = 'withoutimage';
                    }
                    // Add background classes based on setting.
                    switch ($courseheadercanvasbackground) {
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBACKGROUND_WHITE:
                            $canvasclasses[] = 'bg-white';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBACKGROUND_LIGHTGREY:
                            $canvasclasses[] = 'bg-light';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBACKGROUND_LIGHTBRANDCOLOR:
                            $canvasclasses[] = 'bg-primary-light';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBACKGROUND_BRANDCOLORGRADIENTLIGHT:
                            $canvasclasses[] = 'bg-primary-gradient-light';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBACKGROUND_BRANDCOLORGRADIENTFULL:
                            $canvasclasses[] = 'bg-primary-gradient-full';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBACKGROUND_TRANSPARENT:
                        default:
                            // No background class added.
                            break;
                    }
                    $header->courseheadercanvasclasses = implode(' ', $canvasclasses);
                    // Build the CSS header border class string based on setting.
                    switch ($courseheadercanvasborder) {
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBORDER_GREY:
                            $borderclasses = 'border-secondary border';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBORDER_BRANDCOLOR:
                            $borderclasses = 'border-primary border';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBORDER_NONE:
                        default:
                            // No border class added.
                            $borderclasses = '';
                            break;
                    }
                    $header->courseheaderborderclasses = $borderclasses;
                    // Additionally, set text on image style classes based on setting.
                    $courseheadertextonimagestyle = coursesettings::get_config_with_course_override('courseheadertextonimagestyle');
                    switch ($courseheadertextonimagestyle) {
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERTEXTONIMAGESTYLE_LIGHT:
                            $header->textonimagestyle = 'textonimage-light';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERTEXTONIMAGESTYLE_LIGHTSHADOW:
                            $header->textonimagestyle = 'textonimage-lightshadow';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERTEXTONIMAGESTYLE_LIGHTBG:
                            $header->textonimagestyle = 'textonimage-lightbg';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERTEXTONIMAGESTYLE_DARK:
                            $header->textonimagestyle = 'textonimage-dark';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERTEXTONIMAGESTYLE_DARKSHADOW:
                            $header->textonimagestyle = 'textonimage-darkshadow';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERTEXTONIMAGESTYLE_DARKBG:
                            $header->textonimagestyle = 'textonimage-darkbg';
                            break;
                    }
                    // Additionally, determine the partial template for the course header layout.
                    $courseheaderlayout = coursesettings::get_config_with_course_override('courseheaderlayout');
                    switch ($courseheaderlayout) {
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERLAYOUT_HEADINGABOVE:
                            $courseheadertemplate = 'theme_boost_union/full_header-partial-headingabove';
                            break;
                        case THEME_BOOST_UNION_SETTING_COURSEHEADERLAYOUT_STACKED:
                            $courseheadertemplate = 'theme_boost_union/full_header-partial-stacked';
                            break;
                    }

                    // Note: The following code is more or less duplicated in course_renderer::coursecat_coursebox_content().
                    // This was done on purpose as it is not a 100% copy and creating another helper function would not have
                    // improved the code quality much.

                    // Get course util for the course.
                    $courselistelement = new core_course_list_element($this->page->course);
                    $courseutil = new course($courselistelement);
                    $chelper = new coursecat_helper();

                    // Enable course contacts, if configured.
                    if (get_config('theme_boost_union', 'courseheadershowcontacts') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                        $header->showcoursecontacts = true;
                    } else {
                        $header->showcoursecontacts = false;
                    }

                    // Enable course shortname, if configured.
                    if (get_config('theme_boost_union', 'courseheadershowshortname') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                        $header->showshortname = true;
                    } else {
                        $header->showshortname = false;
                    }

                    // Enable course category, if configured.
                    if (get_config('theme_boost_union', 'courseheadershowcategory') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                        $header->showcoursecategory = true;
                    } else {
                        $header->showcoursecategory = false;
                    }

                    // Enable course progress, if configured.
                    if (get_config('theme_boost_union', 'courseheadershowprogress') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                        $header->showcourseprogress = true;
                    } else {
                        $header->showcourseprogress = false;
                    }

                    // Enable course fields, if configured.
                    if (get_config('theme_boost_union', 'courseheadershowfields') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                        $header->showcoursefields = true;
                    } else {
                        $header->showcoursefields = false;
                    }

                    // Enable course details popup, if configured.
                    if (get_config('theme_boost_union', 'courseheadershowpopup') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                        $header->showcoursepopup = true;

                        // Add the necessary JS.
                        $this->page->requires->js_call_amd('theme_boost_union/coursedetailsmodal', 'init');
                    } else {
                        $header->showcoursepopup = false;
                    }

                    // Enable edit icon, if configured and if edit mode is on.
                    if (
                        get_config('theme_boost_union', 'courseheadershowediticon') == THEME_BOOST_UNION_SETTING_SELECT_YES &&
                            $this->page->user_is_editing()
                    ) {
                        $header->showcourseediticon = true;
                        // Get the course settings URL with the course header settings as anchor.
                        $header->coursesettingsurl = new moodle_url(
                            '/course/edit.php',
                            ['id' => $this->page->course->id],
                            'id_theme_boost_union_course_courseheaderhdr'
                        );
                    } else {
                        $header->showcourseediticon = false;
                    }

                    // Enable iconsbar, if necessary.
                    if ($header->showcoursepopup || $header->showcoursecontacts || $header->showcourseediticon) {
                        $header->showiconsbar = true;
                    } else {
                        $header->showiconsbar = false;
                    }

                    // Check if the user can view user details, if necessary.
                    if ($header->showcoursecontacts || $header->showcoursepopup) {
                        $header->canviewuserdetails =
                                has_capability('moodle/user:viewdetails', \context_course::instance($this->page->course->id));
                    }

                    // Amend course contacts, if enabled.
                    if ($header->showcoursecontacts || $header->showcoursepopup) {
                        $header->contacts = $courseutil->get_course_contacts();
                        $header->hascontacts = (count($header->contacts) > 0);
                    }

                    // Amend course shortname, if enabled.
                    if ($header->showshortname) {
                        $header->shortname = $courselistelement->shortname;
                    }

                    // Amend course fullname, if enabled.
                    if ($header->showcoursepopup) {
                        $header->fullname = $courselistelement->fullname;
                    }

                    // Amend course category, if enabled.
                    if ($header->showcoursecategory) {
                        $header->coursecategory = $courseutil->get_category();
                    }

                    // Amend course summary, if enabled.
                    if ($header->showcoursepopup) {
                        $header->summary = $courseutil->get_summary($chelper);
                        $header->hassummary = ($header->summary != false);
                    }

                    // Amend custom fields, if enabled.
                    if ($header->showcoursefields || $header->showcoursepopup) {
                        $header->customfields = $courseutil->get_custom_fields('header');
                        $header->hascustomfields = ($header->customfields != false);

                        // If custom fields should be shown as badges.
                        $courseheaderstylefields = get_config('theme_boost_union', 'courseheaderstylefields');
                        if ($courseheaderstylefields == THEME_BOOST_UNION_SETTING_SHOWAS_BADGE) {
                            $header->customfieldsstyleasbadge = true;

                            // Otherwise.
                        } else {
                            $header->customfieldsstyleasbadge = false;
                        }
                    }

                    // Amend course progress, if enabled.
                    if ($header->showcourseprogress) {
                        $courseprogress = $courseutil->get_progress();
                        $header->progress = (int) $courseprogress;
                        $header->hasprogress = ($courseprogress !== null);

                        // If progress should be shown as progress bar.
                        $courseprogressstyle = get_config('theme_boost_union', 'courseheaderprogressstyle');
                        if ($courseprogressstyle == THEME_BOOST_UNION_SETTING_COURSEPROGRESSSTYLE_BAR) {
                            $header->progressstyleasbar = true;

                            // Otherwise.
                        } else {
                            $header->progressstyleasbar = false;
                        }
                    }

                    // Render the course header partial template for the course header and add it to the header data.
                    // This approach is taken as Mustache does not support dynamicly named partials.
                    $header->courseheaderhtml = $this->render_from_template($courseheadertemplate, $header);
                }
            }
        }

        if (!empty($pagetype) && !empty($homepagetype) && $pagetype == $homepagetype) {
            $header->welcomemessage = \core\user::welcome_message();
        }
        return $this->render_from_template('core/full_header', $header);
    }

    /**
     * Renders the "breadcrumb" for all pages in boost union.
     *
     * This renderer function is copied and modified from /theme/boost/classes/output/core_renderer.php
     *
     * @return string the HTML for the navbar.
     */
    public function navbar(): string {
        $newnav = new \theme_boost_union\boostnavbar($this->page);
        return $this->render_from_template('core/navbar', $newnav);
    }

    /**
     * Prints a nice side block with an optional header.
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * @param block_contents $bc HTML for the content
     * @param string $region the region the block is appearing in.
     * @return string the HTML to be output.
     */
    public function block(block_contents $bc, $region) {
        global $CFG;

        // Require own locallib.php.
        require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

        $bc = clone($bc); // Avoid messing up the object passed in.
        if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        }

        $id = !empty($bc->attributes['id']) ? $bc->attributes['id'] : uniqid('block-');
        $context = new stdClass();
        $context->skipid = $bc->skipid;
        $context->blockinstanceid = $bc->blockinstanceid ?: uniqid('fakeid-');
        $context->dockable = $bc->dockable;
        $context->id = $id;
        $context->hidden = $bc->collapsible == block_contents::HIDDEN;
        $context->skiptitle = strip_tags($bc->title);
        $context->showskiplink = !empty($context->skiptitle);
        $context->arialabel = $bc->arialabel;
        $context->ariarole = !empty($bc->attributes['role']) ? $bc->attributes['role'] : '';
        $context->class = $bc->attributes['class'];
        $context->type = $bc->attributes['data-block'];
        $context->title = (string) $bc->title;
        $context->showtitle = $context->title !== '';
        $context->content = $bc->content;
        $context->annotation = $bc->annotation;
        $context->footer = $bc->footer;
        $context->hascontrols = !empty($bc->controls);

        // Hide edit control options for the regions based on the capabilities.
        $regions = theme_boost_union_get_additional_regions();
        $regioncapname = array_search($region, $regions);
        if (!empty($regioncapname) && $context->hascontrols) {
            $context->hascontrols = has_capability('theme/boost_union:editregion' . $regioncapname, $this->page->context);
        }

        if ($context->hascontrols) {
            $context->controls = $this->block_controls($bc->controls, $id);
        }

        return $this->render_from_template('core/block', $context);
    }

    /**
     * Renders the login form.
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     */
    public function render_login(\core_auth\output\login $form) {
        global $SITE;

        $context = $form->export_for_template($this);

        $context->errorformatted = $this->error_text($context->error);
        $url = $this->get_logo_url();
        if ($url) {
            $url = $url->out(false);
        }
        $context->logourl = $url;
        $context->sitename = format_string(
            $SITE->fullname,
            true,
            ['context' => context_course::instance(SITEID), "escape" => false]
        );

        // Check if the local login form is enabled.
        $loginlocalloginsetting = get_config('theme_boost_union', 'loginlocalloginenable');
        $showlocallogin = ($loginlocalloginsetting != false) ? $loginlocalloginsetting : THEME_BOOST_UNION_SETTING_SELECT_YES;
        if ($showlocallogin == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            // Add marker to show the local login form to template context.
            $context->showlocallogin = true;
        }

        // Check if the local login intro is enabled.
        $loginlocalshowintrosetting = get_config('theme_boost_union', 'loginlocalshowintro');
        $showlocalloginintro = ($loginlocalshowintrosetting != false) ?
            $loginlocalshowintrosetting : THEME_BOOST_UNION_SETTING_SELECT_NO;
        if ($showlocalloginintro == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            // Add marker to show the local login intro to template context.
            $context->showlocalloginintro = true;
            // Check if custom intro text is set.
            $loginlocalintrotext = get_config('theme_boost_union', 'loginlocalintrotext');
            if (!empty($loginlocalintrotext)) {
                $context->localloginintrotext = format_string(
                    $loginlocalintrotext,
                    true,
                    ['context' => context_system::instance()]
                );
            }
        }

        // Check if the IDP login is enabled.
        $loginidploginenablesetting = get_config('theme_boost_union', 'loginidploginenable');
        $showidplogin = ($loginidploginenablesetting != false) ? $loginidploginenablesetting : THEME_BOOST_UNION_SETTING_SELECT_YES;
        if ($showidplogin == THEME_BOOST_UNION_SETTING_SELECT_NO) {
            // Hide identity providers if IDP login is disabled.
            $context->hasidentityproviders = false;
            $context->identityproviders = [];
        }

        // Check if the IDP login intro is enabled.
        $loginidpshowintrosetting = get_config('theme_boost_union', 'loginidpshowintro');
        $showidploginintro = ($loginidpshowintrosetting != false) ?
                $loginidpshowintrosetting : THEME_BOOST_UNION_SETTING_SELECT_YES;
        if ($showidploginintro == THEME_BOOST_UNION_SETTING_SELECT_YES && $showidplogin == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            // Add marker to show the IDP login intro to template context.
            $context->showidploginintro = true;
            // Check if custom intro text is set.
            $loginidpintrotext = get_config('theme_boost_union', 'loginidpintrotext');
            if (!empty($loginidpintrotext)) {
                $context->idploginintrotext = format_string($loginidpintrotext, true, ['context' => context_system::instance()]);
            }
        }

        // Check if guest login is enabled.
        // The guest login should be shown only if BOTH conditions are met:
        // 1. The theme setting 'loginguestloginenable' is enabled.
        // 2. The Moodle core setting 'guestloginbutton' is enabled.
        $loginguestloginenablesetting = get_config('theme_boost_union', 'loginguestloginenable');
        $showguestlogin = ($loginguestloginenablesetting != false) ?
                $loginguestloginenablesetting : THEME_BOOST_UNION_SETTING_SELECT_YES;

        // Check Moodle core's "Guest login button" setting.
        $coreguestloginbutton = !empty(get_config('core', 'guestloginbutton'));

        // Show guest login only if both theme setting AND core setting are enabled.
        if ($showguestlogin == THEME_BOOST_UNION_SETTING_SELECT_YES && $coreguestloginbutton) {
            // Add marker to show guest login to template context.
            $context->canloginasguest = true;

            // Check if the guest login intro is enabled.
            $loginguestshowintrosetting = get_config('theme_boost_union', 'loginguestshowintro');
            $showguestloginintro = ($loginguestshowintrosetting != false) ?
                $loginguestshowintrosetting : THEME_BOOST_UNION_SETTING_SELECT_NO;
            if ($showguestloginintro == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                // Add marker to show the guest login intro to template context.
                $context->showguestloginintro = true;
                // Check if custom intro text is set.
                $loginguestintrotext = get_config('theme_boost_union', 'loginguestintrotext');
                if (!empty($loginguestintrotext)) {
                    $context->guestloginintrotext = format_string(
                        $loginguestintrotext,
                        true,
                        ['context' => context_system::instance()]
                    );
                }
            }
        } else {
            // Hide guest login if either setting is disabled.
            $context->canloginasguest = false;
        }

        // Check if self registration is enabled.
        // The self registration should be shown only if BOTH conditions are met:
        // 1. The theme setting 'loginselfregistrationenable' is enabled.
        // 2. The Moodle core setting 'registerauth' is configured (not empty).
        $loginselfregistrationenablesetting = get_config('theme_boost_union', 'loginselfregistrationenable');
        $showselfregistration = ($loginselfregistrationenablesetting != false) ?
                $loginselfregistrationenablesetting : THEME_BOOST_UNION_SETTING_SELECT_YES;

        // Check Moodle core's "Self registration" setting.
        // This follows the same logic as Moodle core: checks if registerauth is set.
        $coreregisterauth = !empty(get_config('core', 'registerauth'));

        // Show self registration only if both theme setting AND core setting are enabled.
        if ($showselfregistration == THEME_BOOST_UNION_SETTING_SELECT_YES && $coreregisterauth) {
            // Note: cansignup is already set by the login form context from Moodle core.
            // We preserve it here, but will hide it below if either setting is disabled.

            // Check if the self registration intro is enabled.
            $loginselfregistrationshowintrosetting = get_config('theme_boost_union', 'loginselfregistrationshowintro');
            $showselfregistrationloginintro = ($loginselfregistrationshowintrosetting != false) ?
                $loginselfregistrationshowintrosetting : THEME_BOOST_UNION_SETTING_SELECT_NO;
            if ($showselfregistrationloginintro == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                // Add marker to show the self registration intro to template context.
                $context->showselfregistrationloginintro = true;
                // Check if custom intro text is set.
                $loginselfregistrationintrotext = get_config('theme_boost_union', 'loginselfregistrationintrotext');
                if (!empty($loginselfregistrationintrotext)) {
                    $context->selfregistrationloginintrotext = format_string(
                        $loginselfregistrationintrotext,
                        true,
                        ['context' => context_system::instance()]
                    );
                }
            }
        } else {
            // Hide self registration if either setting is disabled.
            $context->cansignup = false;
        }

        // Check login layout setting.
        $loginlayoutsetting = get_config('theme_boost_union', 'loginlayout');
        $loginlayout = ($loginlayoutsetting != false) ? $loginlayoutsetting : THEME_BOOST_UNION_SETTING_LOGINLAYOUT_VERTICAL;
        $context->loginlayout = $loginlayout;

        // If accordion layout is enabled, set marker.
        if ($loginlayout == THEME_BOOST_UNION_SETTING_LOGINLAYOUT_ACCORDION) {
            $context->loginaccordion = true;
        }

        // For vertical, accordion, and tabs layouts, create sorted login methods array.
        // This ensures the DOM order matches the visual order, so CSS :first-of-type and :last-of-type work correctly.
        // Note: The template uses the same loop structure for all layouts, with conditionals for tabs vs vertical/accordion.
        if (
            $loginlayout == THEME_BOOST_UNION_SETTING_LOGINLAYOUT_VERTICAL ||
                $loginlayout == THEME_BOOST_UNION_SETTING_LOGINLAYOUT_ACCORDION ||
                $loginlayout == THEME_BOOST_UNION_SETTING_LOGINLAYOUT_TABS
        ) {
            $loginmethods = [];

            // Method: Local login.
            if (!empty($context->showlocallogin)) {
                $order = get_config('theme_boost_union', 'loginorderlocal');
                if ($order === false) {
                    $order = 1; // Default order.
                }
                $loginmethods[] = (object)[
                    'id' => 'theme_boost_union-loginorder-local',
                    'name' => 'local',
                    'order' => $order,
                    'type' => 'local',
                    'islocal' => true,
                    'isidp' => false,
                    'issignup' => false,
                    'isguest' => false,
                ];
            }

            // Method: IDP login.
            if (!empty($context->hasidentityproviders) && !empty($context->identityproviders)) {
                $order = get_config('theme_boost_union', 'loginorderidp');
                if ($order === false) {
                    $order = 2; // Default order.
                }
                $loginmethods[] = (object)[
                    'id' => 'theme_boost_union-loginorder-idp',
                    'name' => 'idp',
                    'order' => $order,
                    'type' => 'idp',
                    'islocal' => false,
                    'isidp' => true,
                    'issignup' => false,
                    'isguest' => false,
                ];
            }

            // Method: Self registration.
            // Only show if self registration is enabled in theme settings AND (signup is allowed OR instructions exist).
            // Reuse $showselfregistration from earlier check (line 703).
            if (
                $showselfregistration == THEME_BOOST_UNION_SETTING_SELECT_YES &&
                    (!empty($context->cansignup) || !empty($context->hasinstructions))
            ) {
                $order = get_config('theme_boost_union', 'loginorderfirsttimesignup');
                if ($order === false) {
                    $order = 3; // Default order.
                }
                $loginmethods[] = (object)[
                    'id' => 'theme_boost_union-loginorder-firsttimesignup',
                    'name' => 'signup',
                    'order' => $order,
                    'type' => 'signup',
                    'islocal' => false,
                    'isidp' => false,
                    'issignup' => true,
                    'isguest' => false,
                ];
            }

            // Method: Guest login.
            if (!empty($context->canloginasguest)) {
                $order = get_config('theme_boost_union', 'loginorderguest');
                if ($order === false) {
                    $order = 4; // Default order.
                }
                $loginmethods[] = (object)[
                    'id' => 'theme_boost_union-loginorder-guest',
                    'name' => 'guest',
                    'order' => $order,
                    'type' => 'guest',
                    'islocal' => false,
                    'isidp' => false,
                    'issignup' => false,
                    'isguest' => true,
                ];
            }

            // Sort login methods by order setting.
            usort($loginmethods, function ($a, $b) {
                return $a->order <=> $b->order;
            });

            // Mark the first method in the sorted array.
            if (!empty($loginmethods)) {
                $loginmethods[0]->isfirst = true;
            }

            // For tabs and accordion layouts, add label information to each login method.
            if ($loginlayout == THEME_BOOST_UNION_SETTING_LOGINLAYOUT_TABS ||
                    $loginlayout == THEME_BOOST_UNION_SETTING_LOGINLAYOUT_ACCORDION) {
                foreach ($loginmethods as $method) {
                    $label = '';
                    switch ($method->name) {
                        case 'local':
                            $label = get_config('theme_boost_union', 'loginlocallogintablabel');
                            if ($label === false || empty($label)) {
                                $label = 'Local login'; // Default.
                            }
                            break;
                        case 'idp':
                            $label = get_config('theme_boost_union', 'loginidplogintablabel');
                            if ($label === false || empty($label)) {
                                $label = 'IDP login'; // Default.
                            }
                            break;
                        case 'signup':
                            $label = get_config('theme_boost_union', 'loginselfregistrationlogintablabel');
                            if ($label === false || empty($label)) {
                                $label = 'Self Registration'; // Default.
                            }
                            break;
                        case 'guest':
                            $label = get_config('theme_boost_union', 'loginguestlogintablabel');
                            if ($label === false || empty($label)) {
                                $label = 'Guest Login'; // Default.
                            }
                            break;
                    }
                    $method->label = $label;
                }
            }

            // For accordion layout, determine which item should be open by default.
            if ($loginlayout == THEME_BOOST_UNION_SETTING_LOGINLAYOUT_ACCORDION) {
                $primarylogin = get_config('theme_boost_union', 'primarylogin');
                if ($primarylogin === false) {
                    $primarylogin = 'none';
                }
                // Set flags for which accordion item should be open.
                if ($primarylogin != 'none') {
                    switch ($primarylogin) {
                        case 'local':
                            $context->activeaccordionlocal = true;
                            break;
                        case 'idp':
                            $context->activeaccordionidp = true;
                            break;
                        case 'firsttimesignup':
                        case 'signup':
                            $context->activeaccordionsignup = true;
                            break;
                        case 'guest':
                            $context->activeaccordionguest = true;
                            break;
                    }
                }
            }

            $context->loginmethods = $loginmethods;
        }

        // If tabs layout is enabled, prepare tab structure.
        if ($loginlayout == THEME_BOOST_UNION_SETTING_LOGINLAYOUT_TABS) {
            $tabs = [];

            // Build tabs from loginmethods array (which already has labels).
            foreach ($loginmethods as $method) {
                $tabid = 'login-tab-' . $method->name;
                $tabs[] = (object)[
                    'id' => $tabid,
                    'name' => $method->name,
                    'displayname' => $method->label,
                    'order' => $method->order,
                    'content' => $method->name,
                ];
            }

            // Sort tabs by order setting.
            usort($tabs, function ($a, $b) {
                return $a->order <=> $b->order;
            });

            // Determine which tab should be active based on primarylogin setting.
            $primarylogin = get_config('theme_boost_union', 'primarylogin');
            if ($primarylogin === false) {
                $primarylogin = 'none';
            }

            // Map 'firsttimesignup' to 'signup' for tab matching.
            $activetabname = ($primarylogin != 'none') ? $primarylogin : null;
            if ($activetabname === 'firsttimesignup') {
                $activetabname = 'signup';
            }

            // Set the active tab and update context flags.
            // First, find which tab should be active.
            $activetab = null;
            if ($activetabname !== null) {
                // Find the tab that matches the primarylogin setting.
                foreach ($tabs as $tab) {
                    if ($tab->name === $activetabname) {
                        $activetab = $tab;
                        break;
                    }
                }
            }
            // If no matching tab found, use the first tab as default.
            if ($activetab === null && !empty($tabs)) {
                $activetab = $tabs[0];
            }

            // Now set active flag only on the selected tab.
            foreach ($tabs as $tab) {
                $tab->active = ($tab === $activetab);

                if ($tab->active) {
                    // Set the active flag for the corresponding tab content.
                    switch ($tab->name) {
                        case 'local':
                            $context->activetablocal = true;
                            break;
                        case 'idp':
                            $context->activetabidp = true;
                            break;
                        case 'signup':
                            $context->activetabsignup = true;
                            break;
                        case 'guest':
                            $context->activetabguest = true;
                            break;
                    }
                }
            }

            $context->logintabs = (object)['tabs' => $tabs];
        }

        return $this->render_from_template('core/loginform', $context);
    }

    /**
     * Content that should be output in the footer area
     * of the page. Designed to be called in theme layout.php files.
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * @return string HTML fragment.
     */
    public function standard_footer_html() {
        global $CFG;

        // Initialize static variable to store the output for subsequent runs of this function
        // (we call it at least twice from footer.mustache).
        static $output;

        // If the output has already been generated.
        if ($output != null) {
            // Return it directly.
            return $output;
        }

        if (during_initial_install()) {
            // Debugging info can not work before install is finished,
            // in any case we do not want any links during installation!
            return '';
        }

        // Require own locallib.php.
        require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

        // Check if there are any footersuppressstandardfooter_ settings set to YES.
        // If not, we can use the standard Moodle core hook dispatch mechanism for better performance.
        // We cache this check in the application cache to avoid iterating over hundreds of Boost Union settings on every page load.
        $cache = \cache::make('theme_boost_union', 'hooksuppress');
        $cachedhashooksuppresssettings = $cache->get('hashooksuppresssettings');

        // If the cache is empty, call the helper function to check all settings and cache the result.
        if ($cachedhashooksuppresssettings === false) {
            $hashooksuppresssettings = theme_boost_union_reset_hooksuppress_cache();
        } else {
            // Convert cached integer back to boolean.
            $hashooksuppresssettings = (bool)$cachedhashooksuppresssettings;
        }

        // If there are no suppressed footer settings, use the standard Moodle core renderer mechanism.
        if (!$hashooksuppresssettings) {
            // Create the hook and dispatch it normally.
            $hook = new before_standard_footer_html_generation($this);
            $hook->process_legacy_callbacks();
            di::get(hook_manager::class)->dispatch($hook);

            // Gather the output.
            $output = $hook->get_output();

            // Otherwise, we need to suppress specific plugin outputs.
        } else {
            // Process the hooks as defined by Moodle core.
            // But, instead of letting Moodle core dispatch the hook and call all callbacks,
            // we create an empty hook and manually call only the callbacks which are not suppressed by Boost Union
            // or by $CFG->hooks_callback_overrides. This is the only way to suppress specific plugin outputs in the footer
            // without modifying the plugins themselves.
            $hook = new before_standard_footer_html_generation($this);

            // Get all callbacks for this hook.
            $callbacks = di::get(hook_manager::class)->get_callbacks_for_hook(
                'core\\hook\\output\\before_standard_footer_html_generation'
            );

            // Iterate over all callbacks and call only those which are not suppressed.
            foreach ($callbacks as $callback) {
                // Check if the callback is disabled via $CFG->hooks_callback_overrides.
                if (theme_boost_union_is_callback_disabled_in_config($callback['callback'])) {
                    // Skip this callback as it's disabled in config.php.
                    continue;
                }

                // Extract the pluginname.
                $pluginname = theme_boost_union_get_pluginname_from_callbackname($callback);

                // Check if the given plugin's output is suppressed by Boost Union's settings.
                $suppresssetting = get_config('theme_boost_union', 'footersuppressstandardfooter_' . $pluginname);

                // If the plugin's output is NOT suppressed by Boost Union.
                if (!isset($suppresssetting) || $suppresssetting != THEME_BOOST_UNION_SETTING_SELECT_YES) {
                    // Call the callback manually.
                    call_user_func($callback['callback'], $hook);
                }
            }

            // Give plugins an opportunity to add any footer elements (for legacy plugins).
            // Originally, this is realized with $hook->process_legacy_callbacks();
            // However, we duplicate the code here and use the logic from Boost Union which has been used there up to v4.3.
            // Get the array of plugins with the standard_footer_html() function which can be suppressed by Boost Union.
            $pluginswithfunction = get_plugins_with_function(function: 'standard_footer_html', migratedtohook: true);
            // Iterate over all plugins.
            foreach ($pluginswithfunction as $plugintype => $plugins) {
                foreach ($plugins as $pluginname => $function) {
                    // If the given plugin's output is suppressed by Boost Union's settings.
                    $suppresssetting = get_config('theme_boost_union', 'footersuppressstandardfooter_' . $plugintype . '_' .
                            $pluginname);
                    if (isset($suppresssetting) && $suppresssetting == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                        // Skip the plugin.
                        continue;

                        // Otherwise.
                    } else {
                        // Add the output.
                        $hook->add_html($function());
                    }
                }
            }

            // Gather the output.
            $output = $hook->get_output();
        }

        // If the theme switcher links are not suppressed by Boost Union's settings.
        $suppressthemeswitchsetting = get_config('theme_boost_union', 'footersuppressthemeswitch');
        if (!isset($suppressthemeswitchsetting) || $suppressthemeswitchsetting == THEME_BOOST_UNION_SETTING_SELECT_NO) {
            if ($this->page->devicetypeinuse == 'legacy') {
                // The legacy theme is in use print the notification.
                $output .= html_writer::tag('div', get_string('legacythemeinuse'), ['class' => 'legacythemeinuse']);
            }

            // Get links to switch device types (only shown for users not on a default device).
            $output .= $this->theme_switch_links();
        }

        return $output;
    }

    /**
     * Returns course-specific information to be output immediately above content on any course page
     * (for the current course)
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * It is based on the standard_end_of_body_html() function but was split into two parts
     * (for the additionalhtmlfooter and the unique endtoken) to be requested individually in footer.mustache
     * in Boost Union.
     *
     * @param bool $onlyifnotcalledbefore output content only if it has not been output before
     * @return string
     */
    public function course_content_header_notifications($onlyifnotcalledbefore = false) {
        static $functioncalled = false;
        if ($functioncalled && $onlyifnotcalledbefore) {
            // We have already output the notifications.
            return '';
        }

        // Output any session notification.
        $notifications = \core\notification::fetch();

        $bodynotifications = '';
        foreach ($notifications as $notification) {
            $bodynotifications .= $this->render_from_template(
                $notification->get_template_name(),
                $notification->export_for_template($this)
            );
        }

        $output = html_writer::span($bodynotifications, 'notifications', ['id' => 'user-notifications']);

        $functioncalled = true;

        return $output;
    }

    /**
     * Returns course-specific information to be output immediately above content on any course page
     * (for the current course)
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * It is based on the standard_end_of_body_html() function but was split into two parts
     * (for the additionalhtmlfooter and the unique endtoken) to be requested individually in footer.mustache
     * in Boost Union.
     *
     * @param bool $onlyifnotcalledbefore output content only if it has not been output before
     * @return string
     */
    public function course_content_header_coursecontent($onlyifnotcalledbefore = false) {
        global $CFG;

        static $functioncalled = false;
        if ($functioncalled && $onlyifnotcalledbefore) {
            // We have already output the course content header.
            return '';
        }

        $output = '';

        if ($this->page->course->id == SITEID) {
            // Return immediately and do not include /course/lib.php if not necessary.
            return $output;
        }

        require_once($CFG->dirroot . '/course/lib.php');
        $functioncalled = true;
        $courseformat = course_get_format($this->page->course);
        if (($obj = $courseformat->course_content_header()) !== null) {
            $output .= html_writer::div($courseformat->get_renderer($this->page)->render($obj), 'course-content-header');
        }
        return $output;
    }

    /**
     * The standard tags (typically script tags that are not needed earlier) that
     * should be output after everything else. Designed to be called in theme layout.php files.
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * It is based on the standard_end_of_body_html() function but was split into two parts
     * (for the additionalhtmlfooter and the unique endtoken) to be requested individually in footer.mustache
     * in Boost Union.
     *
     * @return string HTML fragment.
     */
    public function standard_end_of_body_html_endtoken() {
        // This function is normally called from a layout.php file in core_renderer::header()
        // but some of the content won't be known until later, so we return a placeholder
        // for now. This will be replaced with the real content in core_renderer::footer().
        $output = $this->unique_end_html_token;
        return $output;
    }

    /**
     * The standard tags (typically script tags that are not needed earlier) that
     * should be output after everything else. Designed to be called in theme layout.php files.
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * It is based on the standard_end_of_body_html() function but was split into two parts
     * (for the additionalhtmlfooter and the unique endtoken) to be requested individually in footer.mustache
     * in Boost Union.
     *
     * @return string HTML fragment.
     */
    public function standard_end_of_body_html_additionalhtmlfooter() {
        global $CFG;

        // Initialize static variable to store the output for subsequent runs of this function
        // (we call it at least twice from footer.mustache).
        static $output;

        // If the output has already been generated.
        if ($output != null) {
            // Return it directly.
            return $output;
        }

        $output = '';
        if ($this->page->pagelayout !== 'embedded' && !empty($CFG->additionalhtmlfooter)) {
            $output .= "\n" . $CFG->additionalhtmlfooter;
        }
        return $output;
    }

    /**
     * Start output by sending the HTTP headers, and printing the HTML <head>
     * and the start of the <body>.
     *
     * To control what is printed, you should set properties on $PAGE.
     *
     * @return string HTML that you must output this, preferably immediately.
     */
    public function header() {
        global $CFG, $SESSION, $USER;

        // Get the header output from the parent class.
        $output = parent::header();

        // If the admin decided to suppress the login info in the footer,
        // the 'failed login attempts' counter in the navbar will not be reset as this is only done by the
        // user_count_login_failures() function from the login_info() function which is not called anymore in this case.
        //
        // The header() function calls the user_count_login_failures() function as well, but does not set the parameter
        // to reset the failed login attempts counter (see issue #658 for details).
        // So we call the user_count_login_failures() function here with the reset parameter set to true here to ensure that the
        // failed login attempts counter is reset anyway when the footer is suppressed.
        //
        // As an alternative to this approach, we could have overwritten the header() function completely here, just changing
        // the line calling the user_count_login_failures(). This would have resulted in a mainantenance overhead
        // and would not have had any performance benefits as the original Moodle calls user_count_login_failures() twice as well.
        $footersuppresslogininfosetting = get_config('theme_boost_union', 'footersuppresslogininfo');
        if (isset($footersuppresslogininfosetting) && $footersuppresslogininfosetting == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            if (isset($SESSION->justloggedin) && !empty($CFG->displayloginfailures)) {
                require_once($CFG->dirroot . '/user/lib.php');
                user_count_login_failures($USER, true);
            }
        }

        // Return the parent header() output.
        return $output;
    }

    /**
     * Get the course pattern datauri to show on a course card.
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * @param int $id Id to use when generating the pattern
     * @return string datauri or URL to fallback image
     */
    public function get_generated_image_for_id($id) {
        // Get the course overview image source setting.
        $imagesource = get_config('theme_boost_union', 'courseoverviewimagesource');

        // If not set, use the default (course image with pattern fallback).
        if (!$imagesource) {
            $imagesource = THEME_BOOST_UNION_SETTING_COURSEOVERVIEWIMAGESOURCE_COURSEPLUSPATTERN;
        }

        // Handle the different image source options.
        switch ($imagesource) {
            // Option 1: Course image with pattern fallback (default Moodle behavior).
            case THEME_BOOST_UNION_SETTING_COURSEOVERVIEWIMAGESOURCE_COURSEPLUSPATTERN:
                return parent::get_generated_image_for_id($id);

            // Option 2: Course image with fallback image.
            case THEME_BOOST_UNION_SETTING_COURSEOVERVIEWIMAGESOURCE_COURSEPLUSFALLBACK:
                // This function is called only if there is no course image and the caller is requesting
                // the course pattern image as fallback. We do not need to check for the course image here,
                // just return the fallback image instead of the pattern.

                // Try to get and return the fallback image.
                $fallbackimageurl = theme_boost_union_get_course_overview_fallback_image_url();
                if ($fallbackimageurl !== null) {
                    return $fallbackimageurl->out();
                }
                // If no fallback image is configured, use the pattern.
                return parent::get_generated_image_for_id($id);

            // Default fallback.
            default:
                return parent::get_generated_image_for_id($id);
        }
    }

    /**
     * Returns a string containing a link to the user documentation.
     * Also contains an icon by default. Shown to teachers and admin only.
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * @param string $path The page link after doc root and language, no leading slash.
     * @param string $text The text to be displayed for the link
     * @param boolean $forcepopup Whether to force a popup regardless of the value of $CFG->doctonewwindow
     * @param array $attributes htm attributes
     * @return string
     */
    public function doc_link($path, $text = '', $forcepopup = false, array $attributes = []) {
        global $CFG;

        // Set the icon only if the setting is not set to suppress the footer icons.
        $footericonsetting = get_config('theme_boost_union', 'footersuppressicons');
        if (!isset($footericonsetting) || $footericonsetting == THEME_BOOST_UNION_SETTING_SELECT_NO) {
            $icon = $this->pix_icon('book', '', 'moodle');

            // Otherwise.
        } else {
            $icon = null;
        }

        $attributes['href'] = new moodle_url(get_docs_url($path));
        $newwindowicon = '';
        if (!empty($CFG->doctonewwindow) || $forcepopup) {
            $attributes['target'] = '_blank';
            $newwindowicon = $this->pix_icon(
                'i/externallink',
                get_string('opensinnewwindow'),
                attributes: ['class' => 'ms-1']
            );
        }

        return html_writer::tag('a', $icon . $text . $newwindowicon, $attributes);
    }

    /**
     * Returns the services and support link for the help pop-up.
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * @return string
     */
    public function services_support_link(): string {
        global $CFG;

        if (
            during_initial_install() ||
            (isset($CFG->showservicesandsupportcontent) && $CFG->showservicesandsupportcontent == false) ||
            !is_siteadmin()
        ) {
            return '';
        }

        // Set the icon only if the setting is not set to suppress the footer icons.
        $footericonsetting = get_config('theme_boost_union', 'footersuppressicons');
        if (!isset($footericonsetting) || $footericonsetting == THEME_BOOST_UNION_SETTING_SELECT_NO) {
            $liferingicon = $this->pix_icon('t/life-ring', '', 'moodle', ['class' => 'fa fa-life-ring']);

            // Otherwise.
        } else {
            $liferingicon = null;
        }

        $newwindowicon = $this->pix_icon('i/externallink', get_string('opensinnewwindow'), 'moodle', ['class' => 'ms-1']);
        $link = !empty($CFG->servicespage)
            ? $CFG->servicespage
            : 'https://moodle.com/help/?utm_source=CTA-banner&utm_medium=platform&utm_campaign=name~Moodle4+cat~lms+mp~no';
        $content = $liferingicon . get_string('moodleservicesandsupport') . $newwindowicon;

        return html_writer::tag('a', $content, ['target' => '_blank', 'href' => $link]);
    }

    /**
     * Returns the HTML for the site support email link
     *
     * This renderer function is copied and modified from /lib/classes/output/core_renderer.php
     *
     * @param array $customattribs Array of custom attributes for the support email anchor tag.
     * @param bool $embed Set to true if you want to embed the link in other inline content.
     * @return string The html code for the support email link.
     */
    public function supportemail(array $customattribs = [], bool $embed = false): string {
        global $CFG;

        // Do not provide a link to contact site support if it is unavailable to this user. This would be where the site has
        // disabled support, or limited it to authenticated users and the current user is a guest or not logged in.
        if (
            !isset($CFG->supportavailability) ||
                $CFG->supportavailability == CONTACT_SUPPORT_DISABLED ||
                ($CFG->supportavailability == CONTACT_SUPPORT_AUTHENTICATED && (!isloggedin() || isguestuser()))
        ) {
            return '';
        }

        $label = get_string('contactsitesupport', 'admin');

        // Set the icon only if the setting is not set to suppress the footer icons.
        $footericonsetting = get_config('theme_boost_union', 'footersuppressicons');
        if (!isset($footericonsetting) || $footericonsetting == THEME_BOOST_UNION_SETTING_SELECT_NO) {
            $icon = $this->pix_icon('book', '', 'moodle');

            // Otherwise.
        } else {
            $icon = null;
        }

        // Set the icon only if the setting is no set to suppress the footer icons.
        if (isset($footericonsetting) && $footericonsetting != THEME_BOOST_UNION_SETTING_SELECT_YES) {
            $icon = $this->pix_icon('t/email', '');
        }

        if (!$embed) {
            $content = $icon . $label;
        } else {
            $content = $label;
        }

        if (!empty($CFG->supportpage)) {
            $attributes = ['href' => $CFG->supportpage, 'target' => 'blank'];
            $content .= $this->pix_icon('i/externallink', '', 'moodle', ['class' => 'ms-1']);
        } else {
            $attributes = ['href' => $CFG->wwwroot . '/user/contactsitesupport.php'];
        }

        $attributes += $customattribs;

        return html_writer::tag('a', $content, $attributes);
    }
}
