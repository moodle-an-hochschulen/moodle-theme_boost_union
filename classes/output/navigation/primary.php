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
 * Theme Boost Union - Primary navigation render.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\output\navigation;

use renderable;
use renderer_base;
use custom_menu;
use theme_boost_union\smartmenu;
use theme_boost_union\smartmenu\location\base as location_base;
use theme_boost_union\smartmenu\location\context as location_context;
use theme_boost_union\smartmenu\provider_registry;

/**
 * Primary navigation renderable.
 *
 * This file combines primary nav, custom menu, lang menu and
 * usermenu into a standardized format for the frontend.
 *
 * This renderer is copied and modified from /lib/classes/navigation/output/primary.php
 *
 * @package     theme_boost_union
 * @copyright   2023 bdecent GmbH <https://bdecent.de>
 * @copyright   based on code 2021 onwards Peter Dias
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class primary extends \core\navigation\output\primary {
    /** @var \moodle_page $page the moodle page that the navigation belongs to */
    private $page = null;

    /**
     * primary constructor.
     * @param \moodle_page $page
     */
    public function __construct($page) {
        $this->page = $page;
        parent::__construct($page);
    }

    /**
     * Get the primary nav object and standardize the output.
     *
     * Moodle core adds the guest calendar node without checking
     * the theme's removed primary navigation items. We handle that case here.
     *
     * @param \navigation_node|null $parent used for nested nodes, by default the primarynav node
     * @return array
     */
    protected function get_primary_nav($parent = null): array {
        // Fetch the configured list of node keys to hide.
        $removed = $this->page->theme->removedprimarynavitems ?? [];

        // Core adds the guest calendar node without checking the removed list, so remove it explicitly.
        if (in_array(THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_CALENDAR, $removed)) {
            $calendarnode = $this->page->primarynav->find('calendar', \core\navigation\navigation_node::TYPE_ROOTNODE);
            if ($calendarnode) {
                $calendarnode->remove();
            }
        }

        // Continue to handle the primary navigation tree with the parent function.
        return parent::get_primary_nav($parent);
    }

    /**
     * Normalize a menu node tree for the mobile primary navigation.
     *
     * The theme_boost_union/primary-drawer-mobile-child template renders itself recursively to support third level
     * menu items. Mustache resolves a key which is missing in the current context by walking up the context stack.
     * A node which carries neither 'haschildren' nor 'children' would therefore inherit both from its parent node,
     * render the parent's child list again and recurse until the PHP memory limit is exhausted.
     *
     * To prevent this, every node is given both keys explicitly. The nodes are copied instead of being modified in
     * place as the very same node objects are also used to build the more menu.
     * Additionally, the nodes are re-indexed as Mustache does only treat arrays with sequential keys as lists
     * and would otherwise render the children as a single context which results in the same recursion.
     *
     * @param array $nodes The menu nodes, given as arrays and/or objects.
     * @return array The normalized menu nodes.
     */
    protected function normalize_menu_nodes(array $nodes): array {
        $normalized = [];

        foreach ($nodes as $node) {
            if (is_object($node)) {
                $node = clone $node;
                $node->children = $this->normalize_menu_nodes((array) ($node->children ?? []));
                $node->haschildren = !empty($node->children) ? 1 : 0;
            } else if (is_array($node)) {
                $node['children'] = $this->normalize_menu_nodes((array) ($node['children'] ?? []));
                $node['haschildren'] = !empty($node['children']) ? 1 : 0;
            }

            $normalized[] = $node;
        }

        return $normalized;
    }

    /**
     * Get the template data of the core primary navigation with the normalized mobile primary navigation nodes.
     *
     * This is used if no smart menus are built. In this case, the mobile primary navigation still contains the
     * core primary navigation nodes which can contain divider nodes without 'haschildren' and 'children' keys,
     * for example if a plugin adds a nested node with preceedwithhr set.
     *
     * @param renderer_base|null $output
     * @return array
     */
    protected function export_core_for_template(?renderer_base $output = null): array {
        $data = parent::export_for_template($output);
        $data['mobileprimarynav'] = $this->normalize_menu_nodes($data['mobileprimarynav'] ?? []);

        return $data;
    }

    /**
     * Combine the various menus into a standardized output.
     *
     * Modifications compared to the original function:
     * * Build the smart menus and its items as navigation nodes.
     * * Group the nodes by the locations of the menus and let each smart menu location provider
     *   (see \theme_boost_union\smartmenu\location\base) render its menus. The built-in locations use the helper
     *   functions of this class to do that:
     *   * The main navigation location combines the smart menu nodes with the core primary menus and exports the React
     *     props for the main navigation.
     *   * The menu bar location renders the menu bar (with the same theme_boost_union/nav/Nav React component as the
     *     main navigation).
     *   * The user menu location attaches the smart menus to the user menu.
     * * Export the template slots, the body classes and the AMD modules of the smart menu location providers.
     *
     * @param renderer_base|null $output
     * @return array
     */
    public function export_for_template(?renderer_base $output = null): array {
        global $DB;

        // Create smart menu cache.
        $cache = \cache::make('theme_boost_union', 'smartmenus');

        // Check if the smart menus are already there in the cache.
        if (!$cache->get(smartmenu::CACHE_MENUSLIST)) {
            // If the smart menu feature is not installed at all, use the parent function.
            // This will help to avoid hickups during a theme upgrade.
            $dbman = $DB->get_manager();
            if (!$dbman->table_exists('theme_boost_union_menus')) {
                return $this->export_core_for_template($output);
            }
        }

        if (!$output) {
            $output = $this->page->get_renderer('core');
        }

        // Generate the menus and its items into nodes.
        $smartmenus = smartmenu::build_smartmenu();
        // Smartmenus not created, then fallback to core navigation.
        if (empty($smartmenus)) {
            return $this->export_core_for_template($output);
        }

        // Group the smart menu nodes by the locations to which their menus are assigned.
        // Menus which are assigned to locations which do not exist (anymore) are not shown there.
        $grouped = smartmenu::group_nodes_by_location($smartmenus);

        // Prepare the data which is exported to the layouts.
        // The location providers integrate their smart menus into this data.
        // Please note: The first four entries (moremenu, mobileprimarynav, lang and user) are the entries which the
        // function of Moodle core exports as well. They are contained here as this function replaces the function of
        // Moodle core and the layouts expect them. The remaining entries are added by Boost Union for the smart menus.
        $languagemenu = new \core\output\language_menu($this->page);
        $export = (object) [
            // The main navigation and the mobile primary navigation (as exported by Moodle core as well).
            // They are filled by the main navigation location which merges its smart menus into them.
            'moremenu' => null,
            'mobileprimarynav' => null,
            // The language menu (as exported by Moodle core as well).
            // It is exported in the same way as Moodle core does it and is not touched by the smart menus.
            'lang' => !isloggedin() || isguestuser() ? $languagemenu->export_for_template($output) : [],
            // The user menu (as exported by Moodle core as well).
            // The user menu location attaches its smart menus to it.
            'user' => $this->get_user_menu($output),
            // The HTML of the template slots into which location providers can render their smart menus
            // (added by Boost Union).
            'slots' => array_fill_keys(location_base::SLOTS, ''),
            // The CSS classes which the location providers add to the body element (added by Boost Union).
            'bodyclasses' => [],
            // The AMD modules which the location and presentation providers need on the page (added by Boost Union).
            'amdmodules' => [],
            // The flag if at least one smart menu is included on the page (added by Boost Union).
            'includesmartmenu' => false,
            // The flag if any of the rendered smart menu items requires client-side starred-courses cache invalidation
            // (added by Boost Union).
            'includestarredcacheinvalidation' => $this->smartmenus_require_starred_cacheinvalidation($cache),
        ];

        // Let each location render its smart menus.
        foreach (provider_registry::get_locations() as $key => $location) {
            // Locations without any smart menus (which are visible for the current user) have nothing to do.
            if (empty($grouped[$key])) {
                continue;
            }

            // The location does not get access to the export data itself, it hands over its output with the functions
            // of the context. This way, each location only works within its own scope.
            // If the location fails, its smart menus are not shown and the page is rendered without them.
            // A broken location provider of another plugin must not break the navigation of the whole site (which would
            // also lock the admin out of the pages where the menu could be removed from the location again).
            try {
                $location->export(new location_context($grouped[$key], $this, $output, $this->page, $export, $key));

                // Get the body classes and the AMD modules of the location.
                $bodyclasses = $location->get_body_classes($grouped[$key]);
                $amdmodules = $location->get_amd_modules($grouped[$key]);

                // Add the AMD modules of the presentations of the menus at this location.
                // Each presentation is only asked with the nodes of the menus which it presents at this location.
                // A presentation which cannot be used at this location is not asked at all as its menus are shown
                // as list there.
                foreach ($this->group_nodes_by_presentation($grouped[$key], $key) as $presentationnodes) {
                    $amdmodules = array_merge(
                        $amdmodules,
                        $presentationnodes->presentation->get_amd_modules($presentationnodes->nodes)
                    );
                }
            } catch (\Throwable $e) {
                debugging('Smart menu location ' . get_class($location) . ' failed to render its smart menus' .
                        ' and is skipped: ' . $e->getMessage(), DEBUG_DEVELOPER);
                continue;
            }

            // Collect the body classes and the AMD modules.
            $export->bodyclasses = array_merge($export->bodyclasses, $bodyclasses);
            $export->amdmodules = array_merge($export->amdmodules, $amdmodules);

            // Remember that at least one smart menu is included on the page.
            // This is used as flag for controlling the smart menu SCSS and for mounting the smart menu React component.
            $export->includesmartmenu = true;
        }

        // If the main navigation location did not have any smart menus, the main navigation is built without smart menus.
        // (It has to be built here in any case as this function replaces the function of Moodle core which builds it).
        if ($export->moremenu === null) {
            [$export->moremenu, $export->mobileprimarynav] = $this->build_primary_navigation([], $output);
        }

        // Finalize the collected body classes and AMD modules.
        if ($export->includesmartmenu) {
            array_unshift($export->bodyclasses, 'theme-boost-union-smartmenu');
        }
        $export->bodyclasses = array_values(array_unique(array_filter($export->bodyclasses)));
        $export->amdmodules = array_values(array_unique(array_filter($export->amdmodules)));

        return (array) $export;
    }

    /**
     * Build the main navigation and the mobile primary navigation.
     * The given smart menu nodes are merged with the primary navigation nodes and the custom menu nodes of Moodle core.
     *
     * This function is used by the main navigation smart menu location.
     *
     * Please note: The presentations of the menus only render their content for the main navigation in the navbar.
     * In the mobile primary navigation (i.e. in the drawer on small screens), the menus are always shown as list,
     * regardless of their presentation. This is done on purpose as the drawer is narrow and is built as a
     * collapsible list.
     *
     * @param array $smartmenunodes The smart menu nodes to be added to the main navigation.
     * @param renderer_base $output The renderer.
     * @param string $locationkey The key of the location for which the navigation is built.
     * @return array An array with the more menu data of the main navigation as first element and the mobile primary
     *               navigation nodes as second element.
     */
    public function build_primary_navigation(
        array $smartmenunodes,
        renderer_base $output,
        string $locationkey = smartmenu::LOCATION_MAIN
    ): array {
        // Primary menu.
        // Merge the smart menu nodes which contain the main menu location with the primary and custom menu nodes.
        // Update the active and open states to the nodes based on the current page.
        $custommenu = $this->get_custom_menu($output);
        $mainmenudata = $this->merge_primary_and_custom($this->get_primary_nav(), array_merge($custommenu, $smartmenunodes));

        // Let the presentations of the menus render their content.
        // This has to be done after the nodes have been merged as the merge flags the active nodes and as the
        // presentations need these flags to highlight the active items in their content.
        // (The nodes of Moodle core and of the custom menu are not smart menu nodes and are not touched here).
        // This is done for the primary menu only, the nodes for the mobile primary navigation are kept as they are.
        $mainmenudata = $this->apply_presentation_content($mainmenudata, $locationkey, $output);

        $moremenu = new \core\navigation\output\more_menu((object) $mainmenudata, 'navbar-nav', false);
        $moremenudata = $moremenu->export_for_template($output);
        if (!empty($moremenudata)) {
            $moremenudata['reactprops'] = $this->export_react_props($mainmenudata, $moremenudata);
        }

        // Mobile primary navigation.
        // The mobile navigation menu uses the expand/collapse method for submenus, for this reason the expanded
        // menu flags are used.
        // The smart menu nodes are used without the content of their presentations here, so the smart menus are always
        // shown as list in the mobile primary navigation.
        $mobileprimarynav = $this->merge_primary_and_custom(
            $this->get_primary_nav(),
            array_merge($custommenu, $smartmenunodes),
            true
        );

        // Make sure that every node carries its own 'haschildren' and 'children' key.
        // This is required as the mobile drawer template renders itself recursively, see normalize_menu_nodes().
        $mobileprimarynav = $this->normalize_menu_nodes($mobileprimarynav);

        return [$moremenudata, $mobileprimarynav];
    }

    /**
     * Build a more menu (i.e. a navigation bar whose items collapse into a "More" menu if the space does not suffice)
     * from the given smart menu nodes. The more menu is rendered by the same React component as the main navigation,
     * the returned data is the context for the core/primarymoremenu template.
     *
     * This function is used by the menu bar smart menu location and can be used by other locations as well.
     *
     * @param array $nodes The smart menu nodes (which have already been flagged as active, if needed).
     * @param string $navbarstyle The CSS class of the navigation list (e.g. 'navbar-nav-menu-bar').
     * @param renderer_base $output The renderer.
     * @param string $locationkey The key of the location for which the more menu is built.
     * @return array The more menu data.
     */
    public function build_moremenu_navigation(
        array $nodes,
        string $navbarstyle,
        renderer_base $output,
        string $locationkey
    ): array {
        // Let the presentations of the menus render their content.
        $nodes = $this->apply_presentation_content($nodes, $locationkey, $output);

        $moremenu = new \core\navigation\output\more_menu((object) $nodes, $navbarstyle, false);
        $data = $moremenu->export_for_template($output);
        if (!empty($data)) {
            $data['reactprops'] = $this->export_react_props($nodes, $data);
        }

        return $data;
    }

    /**
     * Flag the active nodes of the given smart menu nodes based on the current page. This is done like
     * merge_primary_and_custom() does it for the main navigation: Stop at the first active menu so that
     * only one menu is active at a time.
     *
     * @param array $nodes The smart menu nodes.
     * @return void
     */
    public function flag_active_nodes_first(array &$nodes): void {
        foreach (array_keys($nodes) as $i) {
            $menu = (object) $nodes[$i];
            $active = $this->flag_active_nodes($menu);
            if (is_array($nodes[$i])) {
                $nodes[$i] = (array) $menu;
            }
            if ($active) {
                break;
            }
        }
    }

    /**
     * Let the presentations of the given smart menus render the content of the menus for a location.
     *
     * If the presentation of a menu renders content of its own, this content is added to the menu node as 'contenthtml'
     * and is shown by the locations instead of the list of menu items. Menus with the list presentation are not changed.
     * Menus whose presentation cannot be used at the location (as the presentation does not support the location or
     * as the location does not support the presentation) are not changed as well and are shown as list there.
     *
     * @param array $nodes The smart menu nodes.
     * @param string $locationkey The key of the location where the menus are rendered.
     * @param renderer_base $output The renderer.
     * @return array The smart menu nodes.
     */
    public function apply_presentation_content(array $nodes, string $locationkey, renderer_base $output): array {
        foreach ($nodes as $i => $node) {
            // Only menus in submenu mode have a content, the items of inline menus are shown directly at the location.
            if (!is_object($node) || !isset($node->menudata)) {
                continue;
            }

            // Skip the menu if its presentation cannot be used at the location. The menu is shown as list in this case.
            // The edit form does not allow to save such a combination, but it can exist nevertheless, e.g. if a provider
            // changed its supported providers after the menu has been saved.
            // (If the location is not a registered location provider, only the presentation is asked).
            $presentation = provider_registry::get_menu_presentation($node->menudata);
            if (!$this->is_presentation_usable_at_location($presentation, $locationkey)) {
                continue;
            }

            // If the presentation fails to render the content, the menu is shown as list instead.
            // A broken presentation provider of another plugin must not break the navigation of the whole site.
            try {
                $contenthtml = $presentation->render_menu_content($node, $locationkey, $output);
            } catch (\Throwable $e) {
                debugging('Smart menu presentation ' . get_class($presentation) . ' failed to render the content of a menu' .
                        ' and the menu is shown as list instead: ' . $e->getMessage(), DEBUG_DEVELOPER);
                continue;
            }
            if ($contenthtml === null) {
                continue;
            }

            // The same node object may be used by several locations, so the content is added to a copy of the node.
            $nodes[$i] = clone $node;
            $nodes[$i]->contenthtml = $contenthtml;
        }

        return $nodes;
    }

    /**
     * Check if a presentation can be used at a location.
     * If the location is not a registered location provider, only the presentation is asked.
     *
     * @param \theme_boost_union\smartmenu\presentation\base $presentation The presentation provider.
     * @param string $locationkey The key of the location.
     * @return bool
     */
    protected function is_presentation_usable_at_location(
        \theme_boost_union\smartmenu\presentation\base $presentation,
        string $locationkey
    ): bool {
        $location = provider_registry::get_location($locationkey);
        if ($location !== null) {
            return provider_registry::is_supported_combination($presentation, $location);
        }

        $supportedlocations = $presentation->get_supported_locations();
        return ($supportedlocations === null || in_array($locationkey, $supportedlocations));
    }

    /**
     * Group the given smart menu nodes of a location by the presentations which present them there.
     *
     * A node is either the node of a menu in submenu mode (which carries the menu record in 'menudata') or the node of
     * an item of a menu in inline mode (which carries the item record in 'itemdata'). In both cases, the node is assigned
     * to the presentation of its menu. If this presentation cannot be used at the location, the menu is shown as list
     * there and the node is assigned to the fallback presentation instead.
     *
     * @param array $nodes The smart menu nodes.
     * @param string $locationkey The key of the location where the menus are rendered.
     * @return \stdClass[] The groups, keyed by the keys of the presentations. Each group holds the presentation provider
     *                     in 'presentation' and its nodes in 'nodes'.
     */
    protected function group_nodes_by_presentation(array $nodes, string $locationkey): array {
        // The nodes of the items of inline menus do not carry the menu record, so the menu records are needed
        // to look up the presentations of these menus. They are fetched only if there is such a node at all.
        $menus = null;

        $groups = [];
        foreach ($nodes as $node) {
            // Get the menu record of the node.
            $nodeobject = (object) $node;
            if (isset($nodeobject->menudata)) {
                $menu = $nodeobject->menudata;
            } else if (isset($nodeobject->itemdata->menu)) {
                if ($menus === null) {
                    $menus = \cache::make('theme_boost_union', 'smartmenus')->get(smartmenu::CACHE_MENUSLIST)
                            ?: smartmenu::get_menus();
                }
                $menu = $menus[$nodeobject->itemdata->menu] ?? null;
            } else {
                $menu = null;
            }

            // Skip nodes which do not belong to a smart menu.
            if (!($menu instanceof \stdClass)) {
                continue;
            }

            // Get the presentation which presents the menu at the location.
            $presentation = provider_registry::get_menu_presentation($menu);
            if (!$this->is_presentation_usable_at_location($presentation, $locationkey)) {
                $presentation = provider_registry::get_fallback_presentation();
            }

            // Add the node to the group of the presentation.
            $key = $presentation->get_key();
            if (!isset($groups[$key])) {
                $groups[$key] = (object) ['presentation' => $presentation, 'nodes' => []];
            }
            $groups[$key]->nodes[] = $node;
        }

        return $groups;
    }

    /**
     * Build the JSON props consumed by the theme_boost_union/nav/Nav component.
     *
     * Modifications compared to the original function:
     * * Add the label which the smart menu carousels need to the props which the parent function built.
     *
     * @param array $nodes Standardised primary/custom/smart menu nav items.
     * @param array $moremenudata The more_menu export these props render alongside.
     * @return string JSON-encoded props.
     */
    protected function export_react_props(array $nodes, array $moremenudata): string {
        $props = json_decode(parent::export_react_props($nodes, $moremenudata), true);

        // The label of the "Go back" links in the smart menu carousels.
        $props['gobacklabel'] = get_string('usermenugoback', 'core');

        return json_encode($props, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Flatten a standardised menu item into a JSON-safe structure for React.
     *
     * Modifications compared to the original function:
     * * Add the Boost Union smart menu specific fields to smart menu nodes and smart menu item nodes.
     *   Moodle core nodes and custom menu nodes are exported unchanged.
     *
     * @param array $node The node to flatten.
     * @param int $index The node's position among its siblings.
     * @return array
     */
    protected function export_node_for_react(array $node, int $index): array {
        $item = parent::export_node_for_react($node, $index);

        // Dividers do not need any further processing.
        if (!empty($item['divider'])) {
            return $item;
        }

        // Detect the Boost Union node types.
        // Smart menu nodes (menus in submenu mode) carry their menu record in 'menudata',
        // smart menu item nodes carry their item record in 'itemdata'.
        $issmartmenu = array_key_exists('menudata', $node);
        $issmartmenuitem = array_key_exists('itemdata', $node);
        if (!$issmartmenu && !$issmartmenuitem) {
            return $item;
        }

        // Smart menu nodes carry formatted HTML in their text (icons, multi-line labels).
        // Core decodes the HTML entities of its escaped plain-text labels, this must not happen for this HTML.
        $item['text'] = (string) ($node['text'] ?? '');

        // Prefix the key to avoid collisions with the keys of the core and custom menu nodes
        // (custom menu nodes use their numeric sort order as key).
        $item['key'] = 'smartmenu-' . $item['key'];

        // The core export never fills the attributes as core primary nav nodes are never action links.
        // Smart menu items use them to open the link in a new tab.
        $item['attributes'] = array_values($node['attributes'] ?? []);

        // The tooltip of the node.
        $item['tooltip'] = !empty($node['tooltip']) ? (string) $node['tooltip'] : null;

        if ($issmartmenu) {
            $menudata = $node['menudata'];

            $item['smartmenu'] = true;

            // The menu classes are applied to the list item (li) element of the menu.
            $item['wrapperclasses'] = array_values(array_filter((array) ($node['menuclasses'] ?? [])));
            $item['classes'] = [];

            // The menu should be kept outside of the "More" menu.
            $item['forceout'] = (isset($menudata->moremenubehavior) &&
                    $menudata->moremenubehavior == smartmenu::MOREMENU_OUTSIDE);

            // The menu description and help.
            $item['helptext'] = !empty($node['helptext']) ? (string) $node['helptext'] : null;
            $item['helptextposition'] = null;
            if (!empty($node['abovehelptext'])) {
                $item['helptextposition'] = 'above';
            } else if (!empty($node['belowhelptext'])) {
                $item['helptextposition'] = 'below';
            }
            $item['helpicon'] = !empty($node['helpicon']) ? (string) $node['helpicon'] : null;

            // The content of the menu if the presentation of the menu rendered content of its own.
            // It is shown instead of the list of the menu items.
            $item['contenthtml'] = isset($node['contenthtml']) ? (string) $node['contenthtml'] : null;
        } else {
            $classes = array_values(array_filter((array) ($node['itemdata']->classes ?? [])));

            $item['smartmenuitem'] = true;

            // The item classes are applied to the list item (li) element as well as to the link (a) element.
            $item['wrapperclasses'] = $classes;
            $item['classes'] = $classes;
            $item['forceout'] = false;
        }

        return $item;
    }

    /**
     * Get/Generate the user menu.
     *
     * Modifications compared to the original function:
     * * Add a 'Set preferred language' link to the lang menu if the addpreferredlang setting is enabled in Boost Union.
     *
     * @param renderer_base $output
     * @return array
     */
    public function get_user_menu(renderer_base $output): array {

        // If not any Boost Union user menu modification is enabled.
        // (This if-clause is already built in a way that we could add more Boost Union user menu modifications
        // in the future).
        $addpreferredlangsetting = get_config('theme_boost_union', 'addpreferredlang');
        if (!isset($addpreferredlangsetting) || $addpreferredlangsetting == THEME_BOOST_UNION_SETTING_SELECT_NO) {
            // Directly return the output of the parent function.
            return parent::get_user_menu($output);

            // Otherwise, process the Boost Union user menu modifications.
        } else {
            // Get the output of the parent function.
            $parentoutput = parent::get_user_menu($output);

            // If addpreferredlangsetting is enabled and if there are submenus in the output.
            if (
                $addpreferredlangsetting == THEME_BOOST_UNION_SETTING_SELECT_YES &&
                    array_key_exists('submenus', $parentoutput)
            ) {
                // Get the needle.
                $needle = get_string('languageselector');

                // Iterate over the submenus.
                foreach ($parentoutput['submenus'] as $sm) {
                    // Search the 'Language' submenu.
                    if ($sm->title == $needle) {
                        // Create and inject a divider node.
                        $dividernode = [
                            'title' => '', // Empty title.
                            'itemtype' => 'divider',
                            'divider' => 1,
                            'link' => '',
                        ];
                        $sm->items[] = $dividernode;

                        // Create and inject the 'Set preferred language' link.
                        $spfnode = [
                            'title' => get_string('setpreferredlanglink', 'theme_boost_union'),
                            'text' => get_string('setpreferredlanglink', 'theme_boost_union'),
                            'link' => true,
                            'isactive' => false,
                            'url' => new \core\url('/user/language.php'),
                        ];
                        $sm->items[] = $spfnode;

                        // No need to look further.
                        break;
                    }
                }
            }

            // Return the output.
            return $parentoutput;
        }
    }

    /**
     * Attach the smart menus to the user menu which has location selected for user menu.
     * Separate the children items and attach those items to submenus element in user menu.
     * Add the menus in items element in user menu.
     *
     * User menu and its submenus are connected using submenuid. Added submenuid for submenu items if that has children.
     * Add all the items before logout menu. Removed the logout menu, then add the items into user menu items,
     * once all items are added, divider included before logout
     * if any smart menus are included then added the logout menu to menu items.
     *
     * This function is used by the user menu smart menu location only (by means of the attach_to_user_menu() function
     * of the location context, which is reserved for this location).
     *
     * Please note: In the user menu, the smart menus are always shown as list, regardless of their presentation.
     * The presentations of the menus are not asked to render their content here. This is done on purpose as the
     * user menu is narrow and is built as a list with submenus by Moodle core.
     *
     * @param array $usermenu
     * @param array $menus
     * @return void
     */
    public function attach_to_user_menu(&$usermenu, $menus) {

        if (empty($menus)) {
            return;
        }

        $logout = !empty($usermenu['items']) ? array_pop($usermenu['items']) : '';
        foreach ($menus as $menu) {
            // Cast the menu to an object, if needed.
            $menu = !is_object($menu) ? (object) $menu : $menu;

            // Menu with empty childrens.
            if (!isset($menu->children)) {
                $menu->link = !(isset($menu->divider) && $menu->divider);
                $menu->submenulink = false;
                $usermenu['items'][] = $menu;
                continue;
            }

            // Menu with children, split the children and push them into submenus.
            if (isset($menu->submenuid)) {
                $menu->link = false;
                $menu->submenulink = true;
                $children = $menu->children;

                // Add the second level menus list before the course list to the user menu.
                // This will have the effect that, when opening the third level submenus, the transition will go to the right.
                $submenu = [
                    'id' => $menu->submenuid,
                    'title' => $menu->title ?: $menu->text,
                    // The menu description (shown above or below the items or as help icon).
                    'helptext' => $menu->helptext ?? '',
                    'abovehelptext' => !empty($menu->abovehelptext),
                    'belowhelptext' => !empty($menu->belowhelptext),
                    'helpicon' => $menu->helpicon ?? '',
                ];

                $usermenu['submenus'][] = (object) $submenu;
                // The key of this submenu which helps later to include its children after including the necessary data.
                $lastkey = array_key_last($usermenu['submenus']);

                // Update the dividers item type.
                array_walk($children, function (&$value) use (&$usermenu, $menu) {
                    if (isset($value['divider'])) {
                        $value['itemtype'] = 'divider';
                        $value['link'] = false;
                        $value['divider'] = true;
                    }

                    // Children is submenu item, add third level submenu.
                    // Only three levels is available,
                    // Therefore implemented in a static way, in case wants to use multiple levels.
                    // Convert this into separate function make dynamic.
                    if (!empty($value['children'])) {
                        $uniqueid = uniqid();
                        $value['submenuid'] = $uniqueid;

                        $submenu = [
                            'id' => $uniqueid,
                            'returnid' => $menu->submenuid, // Return the third level submenus back to its parent section.
                            'title' => $value['title'],
                            'text' => strip_tags($value['text']), // Remove the item icon from the submenus title.
                            'items' => $value['children'],
                        ];

                        // Insert the third level children into submenus.
                        $usermenu['submenus'][] = (object) $submenu;

                        unset($value['children']);
                    }
                });

                // Update the children elements for the submenu.
                $usermenu['submenus'][$lastkey]->items = $children;
                $usermenu['items'][] = $menu;
            }
        }

        // Include the divider after smart menus items to make difference from logout.
        $divider = [
            'title' => '', // Empty title.
            'itemtype' => 'divider',
            'divider' => 1,
            'link' => '',
        ];
        array_push($usermenu['items'], $divider);

        // Update the logout menu at end of menus.
        if (!empty($logout)) {
            array_push($usermenu['items'], $logout);
        }
    }

    /**
     * Attach the smart menus to the user menu.
     *
     * @param array $usermenu
     * @param array $menus
     * @return void
     * @deprecated since Boost Union 5.3. Use attach_to_user_menu() instead.
     */
    public function build_usermenus(&$usermenu, $menus) {
        debugging('build_usermenus() is deprecated. Use attach_to_user_menu() instead.', DEBUG_DEVELOPER);
        $this->attach_to_user_menu($usermenu, $menus);
    }

    /**
     * Recursive checks if any of the children is active. If that's the case this node (the parent) is active as
     * well. If the node has no children, check if the node itself is active. Use pass by reference for the node
     * object because we actively change/set the "isactive" flag inside the method and this needs to be kept at the
     * callers side.
     * Set $expandedmenu to true, if the mobile menu is done, in this case the active flag gets the node that is
     * actually active, while the parent hierarchy of the active node gets the flag isopen.
     *
     * Modifications compared to the original function:
     * * Updated the children node type to object
     *
     * @param object $node
     * @param bool $expandedmenu
     * @return bool
     */
    protected function flag_active_nodes(object $node, bool $expandedmenu = false): bool {
        global $FULLME;
        $active = false;
        foreach (array_keys($node->children ?? []) as $c) {
            // Update the type of child nodes (smart menu).
            // To prevent issues with already configured menus,
            // The type of children is not updated during the smart menu build process.
            $child = (object) $node->children[$c];

            if ($this->flag_active_nodes($child, $expandedmenu)) {
                $active = true;
            }

            // If the child node is an array, the cast to an object above has created a copy of it. Write the flags
            // which have been set on the copy back to the child node. This includes the children of the copy as the
            // flags of the grandchildren (e.g. the courses of a dynamic courses item in submenu mode) have been set
            // there by the recursive call.
            if (is_array($node->children[$c])) {
                $node->children[$c]['isactive'] = $child->isactive ?? false;
                if (isset($child->isopen)) {
                    $node->children[$c]['isopen'] = $child->isopen;
                }
                if (isset($child->children)) {
                    $node->children[$c]['children'] = $child->children;
                }
            }
        }
        // One of the children is active, so this node (the parent) is active as well.
        if ($active) {
            if ($expandedmenu) {
                $node->isopen = true;
            } else {
                $node->isactive = true;
            }
            return true;
        }

        // By default, the menu item node to check is not active.
        $node->isactive = false;

        // Check if the node url matches the called url. The node url may omit the trailing index.php, therefore check
        // this as well.
        if (empty($node->url)) {
            // Current menu node has no url set, so it can't be active.
            return false;
        }
        $nodeurl = parse_url($node->url);
        $current = parse_url($FULLME ?? '');

        $pathmatches = false;

        // Check for same host names before comparing the path.
        $currenthost = array_key_exists('host', $current) ? strtolower($current['host']) : '';
        $nodehost = array_key_exists('host', $nodeurl) ? strtolower($nodeurl['host']) : '';
        if ($currenthost !== $nodehost) {
            return false;
        }
        // Exact match of the path of node and current url.
        $nodepath = $nodeurl['path'] ?? '/';
        $currentpath = $current['path'] ?? '/';
        if ($nodepath === $currentpath) {
            $pathmatches = true;
        }
        // The current url may be trailed by a index.php, otherwise it's the same as the node path.
        if (!$pathmatches && $nodepath . 'index.php' === $currentpath) {
            $pathmatches = true;
        }
        // No path did match, so the node can't be active.
        if (!$pathmatches) {
            return false;
        }
        // We are here because the path matches, so now look at the query string.
        $nodequery = $nodeurl['query'] ?? '';
        $currentquery = $current['query'] ?? '';
        // If the node has no query string defined, then the patch match is sufficient.
        if (empty($nodeurl['query'])) {
            $node->isactive = true;
            return true;
        }
        // If the node contains a query string then also the current url must match this query.
        if ($nodequery === $currentquery) {
            $node->isactive = true;
        }
        return $node->isactive;
    }

    /**
     * Check whether any rendered smart menu item uses client-side starred-courses refresh.
     * The result is cached in the smartmenus cache to avoid repeated checks.
     *
     * @param \core_cache\application_cache $cache Cache object to store the result.
     * @return bool
     */
    protected function smartmenus_require_starred_cacheinvalidation($cache): bool {
        global $DB;

        // Try to get the result from the cache first.
        $cachekey = smartmenu::CACHE_STARREDCACHEINVALIDATION;
        $cached = $cache->get($cachekey);
        if ($cached !== false) {
            return (bool) $cached;
        }

        // If the smart menu feature is not installed at all, skip the check.
        // This will help to avoid hickups during a theme upgrade.
        $dbman = $DB->get_manager();
        if (!$dbman->table_exists('theme_boost_union_menuitems')) {
            $cache->set($cachekey, 0);
            return false;
        }

        // Check if at least one dynamic courses smart menu item is configured for client-side starred-courses handling.
        $result = $DB->record_exists_sql(
            'SELECT 1
               FROM {theme_boost_union_menuitems} mi
              WHERE mi.type = :typedynamic
                AND mi.starredcourses = :starredcourses',
            [
                'typedynamic' => \theme_boost_union\smartmenu_item::TYPEDYNAMIC,
                'starredcourses' => \theme_boost_union\smartmenu_item::STARREDCOURSES_ONLY_CLIENT,
            ]
        );

        // Store the result in the cache.
        $cache->set($cachekey, $result ? 1 : 0);

        // And return the result.
        return $result;
    }
}
