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
 * Theme Boost Union - Smart menu test location.
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
 * Smart menu test location which renders its menus as plain lists at the end of the page.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class testlocation extends location_base {
    /**
     * Get the key of the provider.
     *
     * @return string
     */
    public function get_key(): string {
        return 'testlocation';
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
        return 'Test location';
    }

    /**
     * This location supports all presentations by default.
     * For testing the restriction of the presentations by a location, it can be switched with the config
     * theme_boost_union | behat_smartmenu_testlocation_listonly to support the list presentation only.
     *
     * @return string[]|null
     */
    public function get_supported_presentations(): ?array {
        if (get_config('theme_boost_union', 'behat_smartmenu_testlocation_listonly')) {
            return ['list'];
        }
        return null;
    }

    /**
     * Get the CSS classes which should be added to the body element.
     *
     * @param array $nodes The smart menu nodes which this location renders on the current page.
     * @return string[]
     */
    public function get_body_classes(array $nodes): array {
        return ['theme-boost-union-testlocation'];
    }

    /**
     * Get the AMD modules which have to be loaded on the page.
     *
     * The location does not have any AMD module by default. For testing that the locations are asked for their
     * AMD modules with the nodes which they render, the location can be switched with the config
     * theme_boost_union | behat_smartmenu_testlocation_amd to return a (not existing) AMD module whose name
     * contains the number of the given nodes.
     *
     * @param array $nodes The smart menu nodes which this provider renders on the current page.
     * @return string[]
     */
    public function get_amd_modules(array $nodes): array {
        if (!get_config('theme_boost_union', 'behat_smartmenu_testlocation_amd')) {
            return [];
        }
        return ['theme_boost_union/testlocation_' . count($nodes) . 'nodes'];
    }

    /**
     * Add the form elements of this provider to the smart menu edit form.
     *
     * @param \MoodleQuickForm $mform The form.
     * @param array $customdata The custom data of the form.
     * @return void
     */
    protected function menu_form_definition(\MoodleQuickForm $mform, array $customdata): void {
        $name = $this->field_name('label');
        $mform->addElement('text', $name, 'Test location label');
        $mform->setType($name, PARAM_TEXT);
    }

    /**
     * Validate the form elements of this provider in the smart menu edit form.
     *
     * @param array $data The submitted form data.
     * @param array $files The submitted files.
     * @return array
     */
    public function menu_form_validation(array $data, array $files): array {
        $name = $this->field_name('label');
        if (($data[$name] ?? '') === 'invalid') {
            return [$name => 'The test location label is invalid.'];
        }
        return [];
    }

    /**
     * Render the smart menus of this location.
     *
     * @param location_context $context The export context.
     * @return void
     */
    public function export(location_context $context): void {
        // For testing how Boost Union handles a broken location, the location can be switched with the config
        // theme_boost_union | behat_smartmenu_testlocation_broken to fail.
        if (get_config('theme_boost_union', 'behat_smartmenu_testlocation_broken')) {
            throw new \coding_exception('The test location is broken.');
        }

        $nodes = $context->primary->apply_presentation_content($context->nodes, $this->get_key(), $context->output);

        $html = '';
        foreach ($nodes as $node) {
            $node = (object) $node;
            $label = $this->get_data($node->menudata ?? null)['label'] ?? '';
            $content = $node->contenthtml ?? html_writer::alist(array_map(
                fn($child) => html_writer::link($child['url'], $child['text']),
                array_filter($node->children ?? [], fn($child) => empty($child['divider']))
            ));
            $html .= html_writer::div(
                html_writer::tag('h6', $node->text . ($label !== '' ? ' (' . s($label) . ')' : '')) . $content,
                'testlocation-menu'
            );
        }

        $context->add_slot_html(self::SLOT_BODY_END, html_writer::div($html, 'theme-boost-union-testlocation-menus'));
    }
}
