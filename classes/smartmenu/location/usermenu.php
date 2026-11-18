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
 * Theme Boost Union - Smart menu location provider for the user menu.
 *
 * The smart menus are attached to the user menu of Moodle core (above the logout link).
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class usermenu extends base {
    /**
     * Get the key of the provider.
     *
     * @return string
     */
    public function get_key(): string {
        return 'user';
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
     * This location does not support the more menu behavior setting, as there is no "More" menu in the user menu.
     *
     * @return bool
     */
    public function menu_supports_moremenu_behavior(): bool {
        return false;
    }

    /**
     * Get the localised name of the provider.
     *
     * @return string
     */
    public function get_name(): string {
        return get_string('smartmenusmenulocationuser', 'theme_boost_union');
    }

    /**
     * Render the smart menus of this location.
     *
     * @param context $context The export context.
     * @return void
     */
    public function export(context $context): void {
        $nodes = $context->nodes;

        // Flag the active nodes of the user menu smart menus based on the current page.
        $context->primary->flag_active_nodes_first($nodes);

        // Attach the menus to the user menu.
        // (The menus are always shown as list there, regardless of their presentation).
        $context->attach_to_user_menu($nodes);
    }

    /**
     * Get the SCSS code which should be added after the post SCSS of Boost Union.
     *
     * @return string
     */
    public function get_post_scss(): string {
        global $CFG;

        return file_get_contents($CFG->dirroot . '/theme/boost_union/scss/boost_union/smartmenus/location_usermenu.scss');
    }
}
