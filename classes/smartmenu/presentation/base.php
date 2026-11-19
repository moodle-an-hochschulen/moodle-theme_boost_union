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

use core\output\renderer_base;
use theme_boost_union\smartmenu\provider;

/**
 * Theme Boost Union - Base class of the smart menu presentation providers.
 *
 * A presentation provider defines how the items of a smart menu are presented within the menu.
 *
 * Boost Union ships the presentation 'list' itself. Other plugins can add further presentations with the
 * \theme_boost_union\hook\smartmenu\collect_providers hook.
 *
 * A presentation can influence the rendering of a menu in two ways:
 * * At build time, it can decorate the nodes of the menu and its items (e.g. with additional CSS classes or data).
 *   The decorated nodes are cached together with the menu.
 * * At render time, it can replace the list of menu items with HTML of its own. This HTML is used by all locations
 *   (and in all rendering paths of the locations: React, Mustache and the NonJS fallbacks).
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class base extends provider {
    /**
     * Get the kind prefix of the provider.
     *
     * @return string
     */
    final protected function get_kind_prefix(): string {
        return 'pres';
    }

    /**
     * Get the name of the field of a menu or menu item in which the settings of the presentations are stored.
     *
     * @return string
     */
    final public function get_data_field(): string {
        return 'presentationdata';
    }

    /**
     * Whether menus with this presentation may be shown in inline mode
     * (where the menu items are shown directly at the location instead of within a submenu).
     *
     * If a presentation does not support the inline mode, its menus are always shown as submenu, regardless of
     * the menu mode which is stored for the menu. The menu mode setting is hidden in the smart menu edit form
     * as soon as this presentation is selected.
     * (This is handled differently than for locations which do not support the inline mode,
     * see \theme_boost_union\smartmenu\location\base::menu_supports_inline_mode()).
     *
     * The inline mode is not supported by default as a presentation has to handle it on its own: In inline mode,
     * there is no menu node, so decorate_menu_node() and render_menu_content() are not called and the presentation
     * can only influence the menu items with decorate_item_node().
     * A presentation which presents its menus properly this way has to overwrite this function and return true.
     *
     * Please note: There is no menu_supports_submenu_mode() counterpart on purpose. The submenu mode is the default
     * mode of a smart menu and the mode which is used as fallback (see above), so each presentation has to support it.
     * A presentation which only supports the inline mode would not have a menu node and thus nothing to present.
     *
     * @return bool
     */
    public function menu_supports_inline_mode(): bool {
        return false;
    }

    /**
     * Whether menu items in submenu mode (i.e. dynamic courses items which show their courses in a submenu of their own)
     * are supported by this presentation.
     *
     * If a presentation does not support the submenu mode of menu items, such items are not shown in menus with
     * this presentation. The menu item mode setting is not offered in the smart menu item edit form for menus with
     * this presentation, and an existing menu item in submenu mode is set to the inline mode as soon as it is saved.
     *
     * The submenu mode of menu items is not supported by default as a presentation has to handle it on its own:
     * The node of such an item does not link to a target, but carries the nodes of the courses in its children,
     * which render_menu_content() has to present as a further menu level.
     * A presentation which handles these nodes properly has to overwrite this function and return true.
     *
     * Please note: There is no item_supports_inline_mode() counterpart on purpose. The inline mode is the regular mode
     * of a menu item and the only mode of all item types except dynamic courses. A presentation which does not support
     * it could not show any static menu item, so each presentation has to support it.
     *
     * @return bool
     */
    public function item_supports_submenu_mode(): bool {
        return false;
    }

    /**
     * Get the keys of the locations at which menus with this presentation can be shown.
     *
     * @return string[]|null The location keys or null if all locations are supported.
     */
    public function get_supported_locations(): ?array {
        return null;
    }

    /**
     * Decorate the node of a menu while the menu is built.
     *
     * This function is only called for menus in submenu mode. The result is cached per menu and user,
     * so this function must not add anything which depends on the current page.
     *
     * @param \stdClass $node The menu node. Additional CSS classes can be added to $node->menuclasses.
     * @param \stdClass $menu The menu record.
     * @param array $providerdata The stored settings of this provider for the menu.
     * @return void
     */
    public function decorate_menu_node(\stdClass $node, \stdClass $menu, array $providerdata): void {
    }

    /**
     * Decorate the node of a menu item while the menu item is built.
     *
     * The result is cached per menu item and user (for cacheable item types), so this function must not add anything
     * which depends on the current page.
     *
     * @param array $node The menu item node. Additional CSS classes can be added to $node['itemdata']->classes.
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
    }

    /**
     * Render the content of a menu.
     *
     * If this function returns HTML, this HTML is shown instead of the list of menu items within the menu
     * (the menu description and the menu help icon are still rendered by the location).
     * If this function returns null, the location renders the menu items as list on its own.
     *
     * The result of this function is not cached. The HTML must not rely on JavaScript which is bound to particular
     * DOM nodes, see get_amd_modules().
     *
     * @param \stdClass $node The menu node with its (already built and decorated) item nodes in $node->children.
     * @param string $locationkey The key of the location where the menu is rendered.
     * @param renderer_base $output The renderer.
     * @return string|null
     */
    public function render_menu_content(\stdClass $node, string $locationkey, renderer_base $output): ?string {
        return null;
    }

    /**
     * Hide a form element of the smart menu edit form as long as this presentation is not selected.
     * This is done automatically for all elements which the presentation adds in menu_form_definition().
     *
     * @param \MoodleQuickForm $mform The form.
     * @param string $elementname The (prefixed) name of the element to hide.
     * @return void
     */
    final protected function hide_unless_selected(\MoodleQuickForm $mform, string $elementname): void {
        $mform->hideIf($elementname, 'presentation', 'neq', $this->get_key());
    }
}
