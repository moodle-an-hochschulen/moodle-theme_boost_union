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
 * Theme Boost Union - Smart menu location provider for the main navigation.
 *
 * The smart menus are merged into the primary navigation of Moodle core (including the custom menu)
 * and into the mobile primary navigation drawer.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class main extends base {
    /**
     * Get the key of the provider.
     *
     * @return string
     */
    public function get_key(): string {
        return 'main';
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
     * This location supports the more menu behavior setting, as the main navigation collapses its menus
     * into a "More" menu.
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
        return get_string('smartmenusmenulocationmain', 'theme_boost_union');
    }

    /**
     * Render the smart menus of this location.
     *
     * @param context $context The export context.
     * @return void
     */
    public function export(context $context): void {
        // Build the main navigation and the mobile primary navigation.
        // (This lets the presentations of the menus render their content as well).
        [$moremenu, $mobileprimarynav] = $context->primary->build_primary_navigation($context->nodes, $context->output);

        // Hand the navigations over to the export data.
        $context->set_primary_navigation($moremenu, $mobileprimarynav);
    }
}
