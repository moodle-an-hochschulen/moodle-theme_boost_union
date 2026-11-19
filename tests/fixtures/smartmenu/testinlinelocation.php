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
 * Theme Boost Union - Smart menu test location with inline mode support.
 *
 * This smart menu location provider is only used in the Behat and PHPUnit tests of Boost Union to test the provider
 * API without depending on an additional plugin. It is added by \theme_boost_union\smartmenu\provider_registry if the
 * (hidden) setting theme_boost_union | behat_smartmenu_testproviders is enabled in a test run.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union_fixtures\smartmenu;

use core\output\html_writer;
use theme_boost_union\smartmenu\location\base as location_base;
use theme_boost_union\smartmenu\location\context as location_context;

/**
 * Smart menu test location which confirms the inline mode support and renders its menus at the top of the page.
 *
 * In contrast to the test location (which relies on the default and does not support the inline mode), this location
 * is used to test that a location of another plugin can handle menus in inline mode.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class testinlinelocation extends location_base {
    /**
     * Get the key of the provider.
     *
     * @return string
     */
    public function get_key(): string {
        return 'testinlinelocation';
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
        return 'Inline capable test location';
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
     * Render the smart menus of this location.
     *
     * @param location_context $context The export context.
     * @return void
     */
    public function export(location_context $context): void {
        $html = '';
        foreach ($context->nodes as $node) {
            $node = (object) $node;

            // The node of a menu in submenu mode carries the menu record in 'menudata' and its items as children.
            if (isset($node->menudata)) {
                $content = html_writer::alist(array_map(
                    fn($child) => html_writer::link($child['url'], $child['text']),
                    array_filter($node->children ?? [], fn($child) => empty($child['divider']))
                ));
                $html .= html_writer::div(html_writer::tag('h6', $node->text) . $content, 'testinlinelocation-menu');

                // Otherwise, the node is the node of a menu item of a menu in inline mode, it is shown directly.
            } else {
                $classes = 'testinlinelocation-inlineitem';
                // Show that the item has been decorated by the inline capable test presentation.
                if (!empty($node->testinlinedecorated)) {
                    $classes .= ' testinlinepresentation-decorated';
                }
                $html .= html_writer::div(html_writer::link($node->url, $node->text), $classes);
            }
        }

        $context->add_slot_html(self::SLOT_PAGE_TOP, html_writer::div($html, 'theme-boost-union-testinlinelocation-menus'));
    }
}
