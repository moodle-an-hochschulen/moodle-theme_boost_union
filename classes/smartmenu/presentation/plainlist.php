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

namespace theme_boost_union\smartmenu\presentation;

/**
 * Theme Boost Union - Smart menu presentation provider for the list presentation.
 *
 * A list menu is composed of simple text links. The list is rendered by the locations themselves, so this provider
 * neither decorates the nodes nor renders any content.
 *
 * Please note: The class cannot be called 'list' as this is a reserved word in PHP.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class plainlist extends base {
    /**
     * Get the key of the provider.
     *
     * @return string
     */
    public function get_key(): string {
        return 'list';
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
     * This presentation supports the inline mode.
     *
     * @return bool
     */
    public function menu_supports_inline_mode(): bool {
        return true;
    }

    /**
     * This presentation supports the submenu mode of menu items.
     *
     * @return bool
     */
    public function item_supports_submenu_mode(): bool {
        return true;
    }

    /**
     * Get the localised name of the provider.
     *
     * @return string
     */
    public function get_name(): string {
        return get_string('smartmenusmenutypelist', 'theme_boost_union');
    }
}
