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
 * Theme Boost Union - Admin settings page with tabs as well as external pages within a tab
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 *             based on code 2016 Ryan Wyllie in class theme_boost_admin_settingspage_tabs
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

/**
 * Class admin_settingspage_tabs_with_external_and_tertiary.
 *
 * This class is copied and modified from /theme/boost/classes/admin_settingspage_tabs.php
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 *             based on code 2016 Ryan Wyllie in class theme_boost_admin_settingspage_tabs
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_settingspage_tabs_with_external_and_tertiary extends \theme_boost_union\admin_settingspage_tabs_with_tertiary {
    /**
     * Add the page.
     *
     * This function is amended with a switch for the external tabs.
     *
     * @param object $tab A tab.
     * @return bool
     */
    public function add($tab) {
        // If the tab is an external page, add it as external tab.
        if ($tab instanceof \admin_externalpage) {
            return $this->add_external_tab($tab);

            // Otherwise, fall back to normal mode.
        } else {
            return $this->add_tab($tab);
        }
    }

    /**
     * Add an external tab.
     *
     * @param \admin_externalpage $tab An external tab.
     */
    private function add_external_tab(\admin_externalpage $tab) {
        $this->tabs[] = $tab;
        return true;
    }

    /**
     * Generate the HTML output.
     *
     * This function is amended with a switch for the external tabs.
     *
     * @return string
     */
    public function output_html() {
        global $OUTPUT;

        // Initialize output.
        $output = '';

        // Add tertiary navigation as a select menu.
        $this->override_selector_active_url(new \core\url('/theme/boost_union/snippets/overview.php'));
        $output .= $this->render_tertiary_navigation();

        // Show alert if Boost Union is not the active theme.
        $output .= theme_boost_union_is_not_active_alert();

        $activetab = optional_param('activetab', '', PARAM_TEXT);
        $context = ['tabs' => []];
        $havesetactive = false;

        foreach ($this->get_tabs() as $tab) {
            $active = false;

            // Default to first tab it not told otherwise.
            if (empty($activetab) && !$havesetactive) {
                $active = true;
                $havesetactive = true;
            } else if ($activetab === $tab->name) {
                $active = true;
            }

            $newtab = [
                'name' => $tab->name,
                'displayname' => $tab->visiblename,
                'html' => $tab->output_html(),
                'active' => $active,
            ];
            // If the tab is an external page.
            if ($tab instanceof \admin_externalpage) {
                // Add a flag for the mustache template.
                $newtab['externaltab'] = true;
                // And change the name (which is used as link in the mustache template)
                // to hold the full URL of the external page..
                $newtab['name'] = $tab->url->out();
            }
            $context['tabs'][] = $newtab;
        }

        if (empty($context['tabs'])) {
            return '';
        }

        // Render the page with the template.
        $output .= $OUTPUT->render_from_template('theme_boost_union/admin_setting_tabs_with_external', $context);

        return $output;
    }
}
