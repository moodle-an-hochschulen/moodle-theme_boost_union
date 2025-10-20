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
 * Theme Boost Union - Tabs to be shown within an external admin settings page
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

use moodle_url;

/**
 * Class admin_externalpage_tabs.
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_externalpage_tabs {
    /**
     * @var array Holds the tabs in this tab tree.
     */
    private $tabs;


    /**
     * Create a tab tree.
     *
     * @return void
     */
    public function __construct() {
        // Initialize the tab tree.
        $this->tabs = [];
    }

    /**
     * Add a tab to the tab tree.
     *
     * @param string $name The (internal) tab name.
     * @param moodle_url $url The tab URL.
     * @param string $label The tab label.
     * @return void
     */
    public function add_tab(string $name, moodle_url $url, string $label) {
        // Create a new tab.
        $newtab = new \core\output\tabobject($name, $url, $label, '', true);

        // Add the tab to the tab tree.
        $this->tabs[] = $newtab;
    }

    /**
     * Render the tab tree.
     *
     * @param string $selected The selected tab's name.
     * @return string The rendered tab tree.
     */
    public function render_tabtree(string $selected) {
        global $OUTPUT;

        // Make a tabtree object from the added tabs.
        $tabtree = new \core\output\tabtree($this->tabs, $selected);

        // Render and return the tab tree.
        return $OUTPUT->render($tabtree);
    }
}
