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
 * Theme Boost Union - Smart menu test presentation.
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
 * Smart menu test presentation which renders the menu items as a list of boxes.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class testpresentation extends presentation_base {
    /**
     * Get the key of the provider.
     *
     * @return string
     */
    public function get_key(): string {
        return 'testpresentation';
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
        return 'Test presentation';
    }

    /**
     * This presentation cannot be used in the menu bar.
     *
     * @return string[]|null
     */
    public function get_supported_locations(): ?array {
        return ['main', 'user', 'testlocation'];
    }

    /**
     * Add the form elements of this provider to the smart menu edit form.
     *
     * @param \MoodleQuickForm $mform The form.
     * @param array $customdata The custom data of the form.
     * @return void
     */
    protected function menu_form_definition(\MoodleQuickForm $mform, array $customdata): void {
        $name = $this->field_name('boxcolor');
        $mform->addElement('select', $name, 'Test presentation box color', ['red' => 'Red', 'green' => 'Green']);
        $mform->setType($name, PARAM_ALPHA);
    }

    /**
     * Add the form elements of this provider to the smart menu item edit form.
     *
     * @param \MoodleQuickForm $mform The form.
     * @param \stdClass $menu The menu record of the item.
     * @param array $customdata The custom data of the form.
     * @return void
     */
    public function item_form_definition(\MoodleQuickForm $mform, \stdClass $menu, array $customdata): void {
        $name = $this->field_name('note');
        $mform->addElement('text', $name, 'Test presentation note');
        $mform->setType($name, PARAM_TEXT);
    }

    /**
     * Validate the form elements of this provider in the smart menu item edit form.
     *
     * @param array $data The submitted form data.
     * @param array $files The submitted files.
     * @param \stdClass $menu The menu record of the item.
     * @return array
     */
    public function item_form_validation(array $data, array $files, \stdClass $menu): array {
        $name = $this->field_name('note');
        if (($data[$name] ?? '') === 'invalid') {
            return [$name => 'The test presentation note is invalid.'];
        }
        return [];
    }

    /**
     * Decorate the node of a menu while the menu is built.
     *
     * @param \stdClass $node The menu node.
     * @param \stdClass $menu The menu record.
     * @param array $providerdata The stored settings of this provider for the menu.
     * @return void
     */
    public function decorate_menu_node(\stdClass $node, \stdClass $menu, array $providerdata): void {
        $node->menuclasses[] = 'testpresentation-' . ($providerdata['boxcolor'] ?? 'red');
    }

    /**
     * Decorate the node of a menu item while the menu item is built.
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
        $node['testnote'] = $itemproviderdata['note'] ?? '';
    }

    /**
     * Get the AMD modules which have to be loaded on the page.
     *
     * The presentation does not have any AMD module by default. For testing that the presentations are asked for their
     * AMD modules with the nodes which they present, the presentation can be switched with the config
     * theme_boost_union | behat_smartmenu_testpresentation_amd to return a (not existing) AMD module whose name
     * contains the number of the given nodes.
     *
     * @param array $nodes The smart menu nodes which this provider renders on the current page.
     * @return string[]
     */
    public function get_amd_modules(array $nodes): array {
        if (!get_config('theme_boost_union', 'behat_smartmenu_testpresentation_amd')) {
            return [];
        }
        return ['theme_boost_union/testpresentation_' . count($nodes) . 'nodes'];
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
        // For testing how Boost Union handles a broken presentation, the presentation can be switched with the config
        // theme_boost_union | behat_smartmenu_testpresentation_broken to fail.
        if (get_config('theme_boost_union', 'behat_smartmenu_testpresentation_broken')) {
            throw new \coding_exception('The test presentation is broken.');
        }

        $boxes = '';
        foreach ($node->children ?? [] as $child) {
            if (!empty($child['divider'])) {
                continue;
            }
            $note = !empty($child['testnote']) ? html_writer::span(s($child['testnote']), 'testpresentation-note') : '';
            $boxes .= html_writer::div(
                html_writer::link($child['url'], $child['text'], ['class' => 'dropdown-item']) . $note,
                'testpresentation-box' . (!empty($child['isactive']) ? ' testpresentation-box-active' : '')
            );
        }
        return html_writer::div($boxes, 'testpresentation-boxes', ['data-location' => $locationkey]);
    }
}
