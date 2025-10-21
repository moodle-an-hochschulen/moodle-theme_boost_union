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
 * Theme Boost Union - Admin settings page with tabs and tertiary navigation.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 *             based on code 2016 Ryan Wyllie in class theme_boost_admin_settingspage_tabs
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

// Require admin lib.
// While this is not needed for the class itself, it is required for the tertiary navigation to work properly
// as the admin_settingpage class was not found when called from theme_boost_union_callbackimpl_before_standard_html().
// Let codechecker ignore the next line because otherwise it would complain about a missing login check
// after requiring config.php which is really not needed.
// phpcs:disable moodle.Files.RequireLogin.Missing,moodle.Files.MoodleInternal.MoodleInternalGlobalState
require_once($CFG->libdir . '/adminlib.php');

/**
 * Class admin_settingspage_tabs_with_tertiary.
 *
 * This class is copied and modified from /theme/boost/classes/admin_settingspage_tabs.php
 *
 * Please note that we have specific code to directly add all Boost Union settings pages to the tertiary navigation.
 * And we rely on the fact that the before_standard_head_html_generation hook calls the class's get_tertiary_navigation_css()
 * function.
 * Thus, this class cannot be fully re-used in other themes directly.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 *             based on code 2016 Ryan Wyllie in class theme_boost_admin_settingspage_tabs
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_settingspage_tabs_with_tertiary extends \theme_boost_admin_settingspage_tabs {
    /**
     * List of tertiary navigation items.
     *
     * @var array
     */
    protected $tertiaryitems = [];

    /**
     * Curently active URL for the tertiary navigation.
     *
     * @var \core\url
     */
    protected $selectoractiveurl = null;

    /**
     * Constructor (adopted from admin_settingpage).
     *
     * @param string $name The internal name for this external page.
     * @param string $visiblename The displayed name for this external page.
     * @param string $reqcapability The role capability/permission a user must have to access this external page.
     * @param bool $hidden Is this external page hidden in admin tree block?
     * @param \stdClass|null $context The context the page relates to.
     */
    public function __construct($name, $visiblename, $reqcapability = 'moodle/site:config', $hidden = false, $context = null) {
        global $PAGE;

        // Call parent constructor.
        parent::__construct($name, $visiblename, $reqcapability, $hidden, $context);

        // Initialize tertiary navigation items.
        $this->init_boost_union_tertiary_settings();

        // Set the currently active URL for the tertiary navigation.
        // But only if available (especially during the initial Moodle setup, this code is already called, but no page URL is set).
        if ($PAGE->has_set_url()) {
            $this->selectoractiveurl = $PAGE->url->out(false);
        } else {
            $this->selectoractiveurl = null;
        }
    }

    /**
     * Add an tertiary navigation item.
     *
     * @param \core\url $url The URL of the tertiary navigation item.
     * @param string $label The label of the tertiary navigation item.
     */
    public function add_tertiary_item(\core\url $url, string $label) {
        $this->tertiaryitems[] = [
            'url' => $url,
            'label' => $label,
        ];
    }

    /**
     * Override the active URL for the tertiary navigation.
     * This is necessary especially on external admin pages which contain subpages.
     *
     * @param \core\url $url
     */
    public function override_selector_active_url(\core\url $url) {
        $this->selectoractiveurl = $url->out(false);
    }

    /**
     * Private helper function to add all Boost Union settings pages to the tertiary navigation with one step
     * within the constructor.
     */
    private function init_boost_union_tertiary_settings() {
        // First, add all Boost Union setting pages.
        $this->add_tertiary_item(
            new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_look']),
            get_string('configtitlelook', 'theme_boost_union', null, true)
        );

        $this->add_tertiary_item(
            new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_feel']),
            get_string('configtitlefeel', 'theme_boost_union', null, true)
        );

        $this->add_tertiary_item(
            new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_content']),
            get_string('configtitlecontent', 'theme_boost_union', null, true)
        );

        $this->add_tertiary_item(
            new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_functionality']),
            get_string('configtitlefunctionality', 'theme_boost_union', null, true)
        );

        $this->add_tertiary_item(
            new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_accessibility']),
            get_string('configtitleaccessibility', 'theme_boost_union', null, true)
        );

        $this->add_tertiary_item(
            new \core\url('/theme/boost_union/flavours/overview.php'),
            get_string('configtitleflavours', 'theme_boost_union', null, true)
        );

        $this->add_tertiary_item(
            new \core\url('/theme/boost_union/snippets/overview.php'),
            get_string('configtitlesnippets', 'theme_boost_union', null, true)
        );

        $this->add_tertiary_item(
            new \core\url('/theme/boost_union/smartmenus/menus.php'),
            get_string('smartmenus', 'theme_boost_union', null, true)
        );

        // Then, add navigation items for all Boost Union Child themes.
        // Use a static variable here as we do not want to search for such plugins more than once, even if the function
        // is called multiple times.
        static $pluginsfunction = null;
        if (is_null($pluginsfunction)) {
            $pluginsfunction = get_plugins_with_function('extend_busettingsoverview', 'lib.php');
        }
        foreach ($pluginsfunction as $plugintype => $plugins) {
            foreach ($plugins as $function) {
                try {
                    $buccards = $function();
                    foreach ($buccards as $buccard) {
                        $this->add_tertiary_item($buccard['url'], $buccard['label']);
                    }
                } catch (\Throwable $e) {
                    debugging("Exception calling '$function'", DEBUG_DEVELOPER, $e->getTrace());
                }
            }
        }

        // Finally, add the category overview link.
        $this->add_tertiary_item(
            new \core\url('/admin/category.php', ['category' => 'theme_boost_union']),
            get_string('settingsoverview_all', 'theme_boost_union', null, true)
        );
    }

    /**
     * Generate the HTML output.
     *
     * @return string
     */
    public function output_html() {
        global $OUTPUT, $PAGE;

        // Initialize output.
        $output = '';

        // Add tertiary navigation as a select menu.
        $output .= $this->render_tertiary_navigation();

        // Show alert if Boost Union is not the active theme.
        $output .= theme_boost_union_is_not_active_alert();

        // Append parent output.
        $output .= parent::output_html();

        return $output;
    }

    /**
     * Render the tertiary navigation menu.
     *
     * This function generates the HTML for the tertiary navigation menu
     * if there are any tertiary items available.
     *
     * @return string The tertiary navigation menu HTML (or empty string).
     */
    protected function render_tertiary_navigation() {
        global $OUTPUT;

        // If there are any tertiary items.
        if (!empty($this->tertiaryitems)) {
            // Initialize select options.
            $selectoptions = [];

            // Itearate over tertiary items.
            foreach ($this->tertiaryitems as $item) {
                // Add item to select options.
                $selectoptions[$item['url']->out(false)] = $item['label'];
            }

            // Create select menu.
            $tertiarymenu = new \core\output\select_menu('boostuniontertiary', $selectoptions, $this->selectoractiveurl);

            // Add SR-only label to the select menu.
            $tertiarymenu->set_label(get_string('tertiarysettings', 'theme_boost_union', null, true), ['class' => 'sr-only']);

            // Build the tertiary navigation select menu.
            $navigation = \html_writer::tag(
                'div',
                $OUTPUT->render_from_template('core/tertiary_navigation_selector', $tertiarymenu->export_for_template($OUTPUT)),
                ['class' => 'tertiary-navigation admin_settingspage_tabs_with_tertiary pb-2 pt-0', 'id' => 'tertiary-navigation']
            );

            // Return.
            return $navigation;
        }

        // Fallback to empty string.
        return '';
    }

    /**
     * Return the tertiary navigation items for an external settings page.
     *
     * @param \core\url|null $overrideactiveurl The URL to override the active URL for the tertiary navigation.
     * @return string
     */
    public static function get_tertiary_navigation_for_externalpage(?\core\url $overrideactiveurl = null) {
        // Initialize a dummy instance of this class, just to get the tertiary navigation.
        $instance = new self('Dummy', 'Dummy');

        // Override the active URL for the tertiary navigation if necessary.
        if ($overrideactiveurl != null) {
            $instance->selectoractiveurl = $overrideactiveurl->out(false);
        }

        // Return the tertiary navigation.
        return $instance->render_tertiary_navigation();
    }

    /**
     * Helper function to get the CSS for the tertiary navigation.
     * This is obtained by the before_standard_head_html_generation hook to be added to the page even if Boost Union is not active.
     * That's why we use this approach instead of adding it to post.scss.
     *
     * @return string
     */
    public static function get_tertiary_navigation_css_for_head() {

        /* The first CSS rule's purpose:
           Enlarge the font size of the tertiary navigation to match the size of a h2 heading.
           This has to use !important as there would be a more specific selector with a smaller font size.
           The second CSS rule's purpose:
           Remove the original heading if there is a tertiary navigation. */
        $css = <<<CSS
.admin_settingspage_tabs_with_tertiary .dropdown-toggle { font-size: 1.875rem !important; }
h2:has(+ .admin_settingspage_tabs_with_tertiary) { display: none; }
CSS;
        $csstag = \html_writer::tag('style', $css, ['type' => 'text/css']);

        return $csstag;
    }
}
