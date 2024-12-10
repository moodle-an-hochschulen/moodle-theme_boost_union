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
use moodle_url;
use stdClass;
use core\di;
use core\hook\manager as hook_manager;
use core\hook\output\before_standard_footer_html_generation;
use core\output\html_writer;
use core_block\output\block_contents;

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
                        '/'.theme_get_revision(),
                        '/'.$flavour->look_favicon
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
                        '/'.theme_get_revision(),
                        '/'.$flavour->look_logo
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

                    // Compose the URL to the flavour's compact logo.
                    $flavourlogourl = \core\url::make_pluginfile_url(
                        context_system::instance()->id,
                        'theme_boost_union',
                        'flavours_look_logocompact',
                        $flavour->id,
                        '/'.theme_get_revision(),
                        '/'.$flavour->look_logocompact
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
            $additionalclasses[] = 'flavour'.'-'.$flavour->id;
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
            if (isset($enableaccessibilitysupportsetting) &&
                    $enableaccessibilitysupportsetting == THEME_BOOST_UNION_SETTING_SELECT_YES &&
                    isset($enableaccessibilitysupportfooterbuttonsetting) &&
                    $enableaccessibilitysupportfooterbuttonsetting == THEME_BOOST_UNION_SETTING_SELECT_YES) {

                // If user login is either not required or if the user is logged in.
                $allowaccessibilitysupportwithoutloginsetting =
                        get_config('theme_boost_union', 'allowaccessibilitysupportwithoutlogin');
                if (!(isset($allowaccessibilitysupportwithoutloginsetting) &&
                        $allowaccessibilitysupportwithoutloginsetting != THEME_BOOST_UNION_SETTING_SELECT_YES) ||
                        (isloggedin() && !isguestuser())) {

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

        // Add the course header image for rendering.
        if ($this->page->pagelayout == 'course' && (get_config('theme_boost_union', 'courseheaderimageenabled')
                        == THEME_BOOST_UNION_SETTING_SELECT_YES)) {
            // If course header images are activated, we get the course header image url
            // (which might be the fallback image depending on the course settings and theme settings).
            $header->courseheaderimageurl = theme_boost_union_get_course_header_image_url();
            // Additionally, get the course header image height.
            $header->courseheaderimageheight = get_config('theme_boost_union', 'courseheaderimageheight');
            // Additionally, get the course header image position.
            $header->courseheaderimageposition = get_config('theme_boost_union', 'courseheaderimageposition');
            // Additionally, get the template context attributes for the course header image layout.
            $courseheaderimagelayout = get_config('theme_boost_union', 'courseheaderimagelayout');
            switch($courseheaderimagelayout) {
                case THEME_BOOST_UNION_SETTING_COURSEIMAGELAYOUT_HEADINGABOVE:
                    $header->courseheaderimagelayoutheadingabove = true;
                    $header->courseheaderimagelayoutstackedclass = '';
                    break;
                case THEME_BOOST_UNION_SETTING_COURSEIMAGELAYOUT_STACKEDDARK:
                    $header->courseheaderimagelayoutheadingabove = false;
                    $header->courseheaderimagelayoutstackedclass = 'dark';
                    break;
                case THEME_BOOST_UNION_SETTING_COURSEIMAGELAYOUT_STACKEDLIGHT:
                    $header->courseheaderimagelayoutheadingabove = false;
                    $header->courseheaderimagelayoutstackedclass = 'light';
                    break;
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
        $context->ariarole = !empty($bc->attributes['role']) ? $bc->attributes['role'] : 'complementary';
        $context->class = $bc->attributes['class'];
        $context->type = $bc->attributes['data-block'];
        $context->title = $bc->title;
        $context->content = $bc->content;
        $context->annotation = $bc->annotation;
        $context->footer = $bc->footer;
        $context->hascontrols = !empty($bc->controls);

        // Hide edit control options for the regions based on the capabilities.
        $regions = theme_boost_union_get_additional_regions();
        $regioncapname = array_search($region, $regions);
        if (!empty($regioncapname) && $context->hascontrols) {
            $context->hascontrols = has_capability('theme/boost_union:editregion'.$regioncapname, $this->page->context);
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
        global $CFG, $SITE;

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
        }

        // Check if the IDP login intro is enabled.
        $loginidpshowintrosetting = get_config('theme_boost_union', 'loginidpshowintro');
        $showidploginintro = ($loginidpshowintrosetting != false) ?
                $loginidpshowintrosetting : THEME_BOOST_UNION_SETTING_SELECT_YES;
        if ($showidploginintro == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            // Add marker to show the IDP login intro to template context.
            $context->showidploginintro = true;
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

        // Process the hooks as defined by Moodle core.
        // If Boost Union is configured to suppress a particular footer element, the hook has been disabled by
        // theme_boost_union_manipulate_books().
        $hook = new before_standard_footer_html_generation($this);
        di::get(hook_manager::class)->dispatch($hook);

        // Give plugins an opportunity to add any footer elements (for legacy plugins).
        // Originally, this is realized with $hook->process_legacy_callbacks();
        // However, we duplicate the code here and use the logic from Boost Union which has been used there up to v4.3.
        // Get the array of plugins with the standard_footer_html() function which can be suppressed by Boost Union.
        $pluginswithfunction = get_plugins_with_function(function: 'standard_footer_html', migratedtohook: true);
        // Iterate over all plugins.
        foreach ($pluginswithfunction as $plugintype => $plugins) {
            foreach ($plugins as $pluginname => $function) {
                // If the given plugin's output is suppressed by Boost Union's settings.
                $suppresssetting = get_config('theme_boost_union', 'footersuppressstandardfooter_'.$plugintype.'_'.$pluginname);
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
                'moodle',
                ['class' => 'fa fa-externallink fa-fw']
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
