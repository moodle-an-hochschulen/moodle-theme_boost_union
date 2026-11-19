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
 * Theme Boost Union - Smart menu test presentation with support for the submenu mode of menu items.
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

use core\output\html_writer;
use core\output\renderer_base;
use theme_boost_union\smartmenu\presentation\base as presentation_base;

/**
 * Smart menu test presentation which confirms the support for the submenu mode of menu items and renders the menu
 * items as a list with a nested list for each item in submenu mode.
 *
 * In contrast to the test presentation (which relies on the default and does not support the submenu mode of menu
 * items), this presentation is used to test that a presentation of another plugin can present menu items in
 * submenu mode.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class testsubmenupresentation extends presentation_base {
    /**
     * Get the key of the provider.
     *
     * @return string
     */
    public function get_key(): string {
        return 'testsubmenupresentation';
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
        return 'Submenu capable test presentation';
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
     * Render the content of a menu.
     *
     * @param \stdClass $node The menu node.
     * @param string $locationkey The key of the location where the menu is rendered.
     * @param renderer_base $output The renderer.
     * @return string|null
     */
    public function render_menu_content(\stdClass $node, string $locationkey, renderer_base $output): ?string {
        $entries = '';
        foreach ($node->children ?? [] as $child) {
            if (!empty($child['divider'])) {
                continue;
            }

            // The node of a menu item in submenu mode carries the nodes of its courses in its children.
            // They are presented as a nested list below the title of the menu item.
            if (!empty($child['children'])) {
                $subentries = '';
                foreach ($child['children'] as $subchild) {
                    $subchild = (array) $subchild;
                    $subentries .= html_writer::tag(
                        'li',
                        html_writer::link($subchild['url'], $subchild['text'], ['class' => 'dropdown-item'])
                    );
                }
                $entries .= html_writer::tag(
                    'li',
                    html_writer::span($child['text'], 'testsubmenupresentation-submenutitle') .
                            html_writer::tag('ul', $subentries, ['class' => 'testsubmenupresentation-submenu'])
                );

                // Otherwise, the node is the node of a regular menu item.
            } else {
                $entries .= html_writer::tag(
                    'li',
                    html_writer::link($child['url'], $child['text'], ['class' => 'dropdown-item'])
                );
            }
        }
        return html_writer::tag('ul', $entries, ['class' => 'testsubmenupresentation-list', 'data-location' => $locationkey]);
    }
}
