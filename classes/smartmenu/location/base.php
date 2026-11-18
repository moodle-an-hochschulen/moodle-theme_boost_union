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

use theme_boost_union\smartmenu\provider;
use theme_boost_union\smartmenu\provider_registry;

/**
 * Theme Boost Union - Base class of the smart menu location providers.
 *
 * A location provider defines a place on the page where smart menus can be shown and is responsible for rendering
 * the smart menus which the admin assigned to this location.
 *
 * Boost Union ships the locations 'main' (main navigation), 'menu' (menu bar) and 'user' (user menu) itself.
 * Other plugins can add further locations with the \theme_boost_union\hook\smartmenu\collect_providers hook.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class base extends provider {
    /** @var string Template slot above the navbar (this is where the menu bar is rendered). */
    public const SLOT_NAVBAR_ABOVE = 'navbar_above';

    /** @var string Template slot below the navbar. */
    public const SLOT_NAVBAR_BELOW = 'navbar_below';

    /** @var string Template slot at the top of the page element. */
    public const SLOT_PAGE_TOP = 'page_top';

    /** @var string Template slot above the footer element. */
    public const SLOT_FOOTER_ABOVE = 'footer_above';

    /** @var string Template slot at the end of the footer element. */
    public const SLOT_FOOTER_BELOW = 'footer_below';

    /** @var string Template slot at the end of the page wrapper. */
    public const SLOT_BODY_END = 'body_end';

    /**
     * The template slots into which a location provider can render HTML.
     * As Mustache cannot iterate over dynamic keys, this list is fixed and each slot is output by the layout templates.
     *
     * @var string[]
     */
    public const SLOTS = [
        self::SLOT_NAVBAR_ABOVE,
        self::SLOT_NAVBAR_BELOW,
        self::SLOT_PAGE_TOP,
        self::SLOT_FOOTER_ABOVE,
        self::SLOT_FOOTER_BELOW,
        self::SLOT_BODY_END,
    ];

    /**
     * Get the kind prefix of the provider.
     *
     * @return string
     */
    final protected function get_kind_prefix(): string {
        return 'loc';
    }

    /**
     * Get the name of the field of a menu or menu item in which the settings of the locations are stored.
     *
     * @return string
     */
    final public function get_data_field(): string {
        return 'locationdata';
    }

    /**
     * Whether menus at this location may be shown in inline mode
     * (where the menu items are shown directly at the location instead of within a submenu).
     *
     * If a location does not support the inline mode, a menu in inline mode cannot be assigned to this location:
     * The smart menu edit form refuses to save the menu and shows an error message at the menu mode setting.
     * (This is handled differently than for presentations which do not support the inline mode,
     * see \theme_boost_union\smartmenu\presentation\base::menu_supports_inline_mode()).
     *
     * The inline mode is not supported by default as a location has to handle it on its own: In inline mode,
     * export() does not get one node per menu, but the nodes of the menu items themselves.
     * A location which handles these nodes properly has to overwrite this function and return true.
     *
     * Please note: There is no menu_supports_submenu_mode() counterpart on purpose. The submenu mode is the default
     * mode of a smart menu, so each location has to support it. A location which only shows menu items directly is
     * conceivable, but there is no such location yet and it would need additional handling in the smart menu edit form
     * and during rendering. If such a function is added later, this will be an incompatible change of the provider API
     * (as the supported modes have to be confirmed actively) which requires to raise provider::API_VERSION.
     *
     * @return bool
     */
    public function menu_supports_inline_mode(): bool {
        return false;
    }

    /**
     * Whether this location supports the more menu behavior setting of a menu, i.e. whether this location collapses
     * the menus which do not fit into the available space into a "More" menu.
     *
     * The more menu behavior setting is only offered in the smart menu edit form as long as at least one location
     * is selected which supports it. If only locations are selected which do not support it, the setting is hidden
     * and the menu is saved with the default more menu behavior.
     *
     * The setting is not supported by default as a location has to handle it on its own. A location which builds
     * its menus with \theme_boost_union\output\navigation\primary::build_moremenu_navigation() gets this handling
     * for free (as the React component which renders the navigation interprets the setting) and should overwrite
     * this function and return true.
     *
     * @return bool
     */
    public function menu_supports_moremenu_behavior(): bool {
        return false;
    }

    /**
     * Get the keys of the presentations which can be used for menus at this location.
     *
     * @return string[]|null The presentation keys or null if all presentations are supported.
     */
    public function get_supported_presentations(): ?array {
        return null;
    }

    /**
     * Render the smart menus of this location.
     *
     * This function is called while the primary navigation is exported, but only if there is at least one smart menu
     * which is visible to the current user at this location. The provider either integrates the given nodes into the
     * existing navigation structures in the export object (as the built-in locations do) or renders them to HTML
     * and adds this HTML to one of the template slots with $context->add_slot_html().
     *
     * @param context $context The export context.
     * @return void
     */
    abstract public function export(context $context): void;

    /**
     * Get the CSS classes which should be added to the body element as soon as this location renders at least one menu.
     *
     * @param array $nodes The smart menu nodes which this location renders on the current page.
     * @return string[]
     */
    public function get_body_classes(array $nodes): array {
        return [];
    }

    /**
     * Hide a form element of the smart menu edit form as long as this location is not selected.
     * This is done automatically for all elements which the location adds in menu_form_definition().
     *
     * @param \MoodleQuickForm $mform The form.
     * @param string $elementname The (prefixed) name of the element to hide.
     * @return void
     */
    final protected function hide_unless_selected(\MoodleQuickForm $mform, string $elementname): void {
        // The location element is a multi-select. For multi-selects, the 'in' condition is fulfilled if the selected
        // values are a subset of the given values. So, the element is hidden as soon as only other locations
        // (or no location at all, which is represented by an empty value) are selected.
        $allkeys = array_keys(provider_registry::get_locations());

        // The location element may offer further locations which do not exist (anymore), e.g. as the plugin which
        // provides the location has been uninstalled. They have to be treated as other locations as well. Otherwise,
        // the element would be shown as soon as such a location is selected together with another location.
        if ($mform->elementExists('location')) {
            foreach ($mform->getElement('location')->_options as $option) {
                $allkeys[] = (string) $option['attr']['value'];
            }
        }
        $otherkeys = array_diff(array_unique($allkeys), [$this->get_key()]);
        $otherkeys[] = '';

        // Please note: The dependency has to be defined on the name of the HTML element, which is 'location[]' for the
        // multi-select, as the form JavaScript matches the dependencies by the name attributes of the elements.
        $mform->hideIf($elementname, 'location[]', 'in', array_values($otherkeys));
    }
}
