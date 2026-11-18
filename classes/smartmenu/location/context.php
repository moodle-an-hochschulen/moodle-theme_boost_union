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

namespace theme_boost_union\smartmenu\location;

use core\output\renderer_base;
use theme_boost_union\output\navigation\primary;
use theme_boost_union\smartmenu;

/**
 * Theme Boost Union - Context which is handed over to a smart menu location provider when it has to render its menus.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class context {
    /**
     * Constructor.
     *
     * @param array $nodes The smart menu nodes which have to be rendered at the location. The nodes are already built
     *                     for the current user (i.e. the access rules have been applied). A node is either an object
     *                     which carries the menu record in 'menudata' (a menu in submenu mode with its items in
     *                     'children') or an array which carries the item record in 'itemdata' (an item of a menu in
     *                     inline mode).
     * @param primary $primary The primary navigation output class which offers helper functions to build the menus.
     * @param renderer_base $output The renderer.
     * @param \moodle_page $page The page.
     * @param \stdClass $export The data which the primary navigation exports to the layouts. The properties moremenu,
     *                          mobileprimarynav and user hold the navigation structures of the built-in locations,
     *                          the property slots holds the HTML of the template slots.
     *                          This data is not exposed to the location on purpose: A location must only work within
     *                          its own scope and has to use the functions of this class to hand over its output.
     * @param string $locationkey The key of the location for which this context is created.
     */
    public function __construct(
        /** @var array The smart menu nodes which have to be rendered at the location. */
        public readonly array $nodes,
        /** @var primary The primary navigation output class. */
        public readonly primary $primary,
        /** @var renderer_base The renderer. */
        public readonly renderer_base $output,
        /** @var \moodle_page The page. */
        public readonly \moodle_page $page,
        /** @var \stdClass The data which the primary navigation exports to the layouts. */
        private readonly \stdClass $export,
        /** @var string The key of the location for which this context is created. */
        private readonly string $locationkey,
    ) {
    }

    /**
     * Add HTML to a template slot.
     *
     * This is the way how a location of another plugin hands over its output.
     *
     * @param string $slot One of the base::SLOT_* constants.
     * @param string $html The HTML to add.
     * @return void
     * @throws \coding_exception If the slot does not exist.
     */
    public function add_slot_html(string $slot, string $html): void {
        if (!in_array($slot, base::SLOTS, true)) {
            throw new \coding_exception('Unknown smart menu template slot: ' . $slot);
        }
        $this->export->slots[$slot] .= $html;
    }

    /**
     * Set the main navigation and the mobile primary navigation.
     *
     * This function is reserved for the built-in main navigation location, as the main navigation is a structure
     * of Moodle core which must not be modified by any other location.
     *
     * @param array $moremenu The more menu data of the main navigation.
     * @param array $mobileprimarynav The mobile primary navigation nodes.
     * @return void
     * @throws \coding_exception If the function is called by another location.
     */
    public function set_primary_navigation(array $moremenu, array $mobileprimarynav): void {
        $this->require_location(smartmenu::LOCATION_MAIN);

        $this->export->moremenu = $moremenu;
        $this->export->mobileprimarynav = $mobileprimarynav;
    }

    /**
     * Attach smart menu nodes to the user menu.
     *
     * This function is reserved for the built-in user menu location, as the user menu is a structure
     * of Moodle core which must not be modified by any other location.
     *
     * @param array $nodes The smart menu nodes (which have already been flagged as active, if needed).
     * @return void
     * @throws \coding_exception If the function is called by another location.
     */
    public function attach_to_user_menu(array $nodes): void {
        $this->require_location(smartmenu::LOCATION_USER);

        // Attach the menus to the user menu.
        // (The menus are always shown as list there, the presentations of the menus are not asked to render their content).
        $this->primary->attach_to_user_menu($this->export->user, $nodes);
    }

    /**
     * Make sure that this context was created for the given location.
     *
     * @param string $locationkey The key of the location which is allowed to proceed.
     * @return void
     * @throws \coding_exception If the context was created for another location.
     */
    private function require_location(string $locationkey): void {
        if ($this->locationkey !== $locationkey) {
            throw new \coding_exception('The smart menu location ' . $this->locationkey .
                    ' is not allowed to modify the navigation structures of the location ' . $locationkey . '.');
        }
    }
}
