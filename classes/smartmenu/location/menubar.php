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

/**
 * Theme Boost Union - Smart menu location provider for the menu bar.
 *
 * The menu bar is an additional navigation bar which is shown above the navbar.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class menubar extends base {
    /**
     * Get the key of the provider.
     *
     * @return string
     */
    public function get_key(): string {
        return 'menu';
    }

    /**
     * Get the version of the provider API which this provider has been built for.
     * As a built-in provider, it is always up-to-date with the provider API.
     *
     * @return int
     */
    public function get_api_version(): int {
        return self::API_VERSION;
    }

    /**
     * This location supports the inline mode.
     *
     * @return bool
     */
    public function menu_supports_inline_mode(): bool {
        return true;
    }

    /**
     * This location supports the more menu behavior setting, as the menu bar collapses its menus into a "More" menu.
     *
     * @return bool
     */
    public function menu_supports_moremenu_behavior(): bool {
        return true;
    }

    /**
     * Get the localised name of the provider.
     *
     * @return string
     */
    public function get_name(): string {
        return get_string('smartmenusmenulocationmenu', 'theme_boost_union');
    }

    /**
     * Render the smart menus of this location.
     *
     * @param context $context The export context.
     * @return void
     */
    public function export(context $context): void {
        $nodes = $context->nodes;

        // Find out if the menu bar as a whole has to be hidden on desktop, tablet or mobile devices.
        // The admin cannot configure this for the menu bar or for a menu, there is only the 'Responsive hiding'
        // setting of the menu items. But the menu bar is a bar of its own above the navbar, and if all of its
        // items are hidden on a device, an empty bar would remain there. That's why the menu bar is hidden
        // completely on a device as soon as there is not anything to show in it on this device.
        //
        // This is derived in two stages:
        // 1. When a menu is built, the menu node gets its own desktop, tablet and mobile flags. A menu is flagged
        // as hidden on a device only if all of its items are hidden on this device (see smartmenu::build()).
        // 2. Here, the same rule is applied one level above: The menu bar is hidden on a device only if all of its
        // nodes are hidden on this device.
        // (For a menu in inline mode, the nodes are the nodes of its items which carry the flags of the items directly).

        // Start with the assumption that the menu bar has to be hidden on all devices.
        $hidedesktop = $hidemobile = $hidetablet = 1;

        // Iterate over the nodes of the menu bar.
        foreach ($nodes as $menu) {
            $menu = (object) $menu;
            // If the node is not hidden on desktop, the menu bar has to be shown on desktop.
            if (empty($menu->desktop)) {
                $hidedesktop = 0;
            }
            // If the node is not hidden on tablets, the menu bar has to be shown on tablets.
            if (empty($menu->tablet)) {
                $hidetablet = 0;
            }
            // If the node is not hidden on mobiles, the menu bar has to be shown on mobiles.
            if (empty($menu->mobile)) {
                $hidemobile = 0;
            }
        }

        // Flag the active nodes of the menu bar menus based on the current page.
        $context->primary->flag_active_nodes_first($nodes);

        // Build the menu bar (which is rendered by the same React component as the main navigation).
        // (This lets the presentations of the menus render their content as well).
        $data = $context->primary->build_moremenu_navigation($nodes, 'navbar-nav-menu-bar', $context->output, $this->get_key());
        if (empty($data)) {
            return;
        }

        // Define the visibility classes for the menu bar based on the flags from above.
        // These are Bootstrap display utility classes which hide or show the bar from the given breakpoint upwards.
        $classes = [];
        $classes[] = $hidedesktop ? 'd-lg-none' : 'd-lg-flex';
        $classes[] = $hidetablet ? 'd-md-none' : 'd-md-flex';
        $classes[] = $hidemobile ? 'd-none' : 'd-flex';
        $data['classes'] = implode(' ', $classes);

        // The React mount point template decides based on this flag which React component is mounted.
        $data['includesmartmenu'] = true;

        // Render the menu bar into the slot above the navbar.
        $context->add_slot_html(
            self::SLOT_NAVBAR_ABOVE,
            $context->output->render_from_template('theme_boost_union/smartmenus-location-menubar', $data)
        );
    }

    /**
     * Get the SCSS code which should be added after the post SCSS of Boost Union.
     *
     * @return string
     */
    public function get_post_scss(): string {
        global $CFG;

        return file_get_contents($CFG->dirroot . '/theme/boost_union/scss/boost_union/smartmenus/location_menubar.scss');
    }
}
