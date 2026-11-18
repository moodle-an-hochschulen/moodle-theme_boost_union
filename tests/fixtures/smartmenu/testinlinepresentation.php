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
 * Theme Boost Union - Smart menu test presentation with inline mode support.
 *
 * This smart menu presentation provider is only used in the Behat and PHPUnit tests of Boost Union to test the provider
 * API without depending on an additional plugin. It is added by \theme_boost_union\smartmenu\provider_registry if the
 * (hidden) setting theme_boost_union | behat_smartmenu_testproviders is enabled in a test run.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union_fixtures\smartmenu;

use theme_boost_union\smartmenu\presentation\base as presentation_base;

/**
 * Smart menu test presentation which confirms the inline mode support and only decorates the menu items.
 *
 * In contrast to the test presentation (which relies on the default and does not support the inline mode), this
 * presentation is used to test that menus with a presentation of another plugin can be shown in inline mode.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class testinlinepresentation extends presentation_base {
    /**
     * Get the key of the provider.
     *
     * @return string
     */
    public function get_key(): string {
        return 'testinlinepresentation';
    }

    /**
     * Get the version of the provider API which this provider has been built for.
     * As a test fixture of Boost Union, it is always up-to-date with the provider API.
     *
     * @return int
     */
    public function get_api_version(): int {
        return self::API_VERSION;
    }

    /**
     * Get the localised name of the provider.
     *
     * @return string
     */
    public function get_name(): string {
        return 'Inline capable test presentation';
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
     * Decorate the node of a menu item while the menu item is built.
     * This is the only way for a presentation to influence a menu in inline mode.
     *
     * @param array $node The menu item node.
     * @param \stdClass $item The menu item record.
     * @param \stdClass $menu The menu record.
     * @param array $itemproviderdata The stored settings of this provider for the menu item.
     * @param array $menuproviderdata The stored settings of this provider for the menu.
     * @param \stdClass|null $course The course record if the node represents a course of a dynamic courses item.
     * @return void
     */
    public function decorate_item_node(
        array &$node,
        \stdClass $item,
        \stdClass $menu,
        array $itemproviderdata,
        array $menuproviderdata,
        ?\stdClass $course = null
    ): void {
        $node['testinlinedecorated'] = true;
    }

    /**
     * Get the AMD modules which have to be loaded on the page.
     *
     * The presentation does not have any AMD module by default. For testing that the presentations are asked for their
     * AMD modules with the nodes which they present, the presentation can be switched with the config
     * theme_boost_union | behat_smartmenu_testinlinepresentation_amd to return a (not existing) AMD module whose name
     * contains the number of the given nodes.
     *
     * @param array $nodes The smart menu nodes which this provider renders on the current page.
     * @return string[]
     */
    public function get_amd_modules(array $nodes): array {
        if (!get_config('theme_boost_union', 'behat_smartmenu_testinlinepresentation_amd')) {
            return [];
        }
        return ['theme_boost_union/testinlinepresentation_' . count($nodes) . 'nodes'];
    }
}
