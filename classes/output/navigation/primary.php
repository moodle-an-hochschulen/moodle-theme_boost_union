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
     * Combine the various menus into a standardized output.
     *
     * Modifications compared to the original function:
     * * Build the smart menus and its items as navigation nodes.
     * * Generate the nodes for different locations based on the menus locations.
     * * Combine the smart menus nodes with core primary menus.
     * * Export the React props for the main navigation and the menu bar
     *   (both are rendered by the theme_boost_union/nav/Nav React component).
     * * Attach the smart menus which are configured for the user menu location to the user menu.
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
                return parent::export_for_template($output);
            }
        }

        if (!$output) {
            $output = $this->page->get_renderer('core');
        }

        // Generate the menus and its items into nodes.
        $smartmenus = smartmenu::build_smartmenu();
        // Smartmenus not created, then fallback to core navigation.
        if (empty($smartmenus)) {
            return parent::export_for_template($output);
        }

        // Get the menus for the main menu loation.
        $locationmainmenu = smartmenu::get_menus_forlocation(smartmenu::LOCATION_MAIN, $smartmenus);

        // Separate the menus for the menubar location.
        $locationmenubarmenu = smartmenu::get_menus_forlocation(smartmenu::LOCATION_MENU, $smartmenus);

        // Separate the menus for the usermenu location.
        $locationusermenus = smartmenu::get_menus_forlocation(smartmenu::LOCATION_USER, $smartmenus);

        // Primary menu.
        // Merge the smart menu nodes which contain the main menu location with the primary and custom menu nodes.
        // Update the active and open states to the nodes based on the current page.
        $locationmainmenucustommerged = array_merge($this->get_custom_menu($output), $locationmainmenu);
        $mainmenudata = $this->merge_primary_and_custom($this->get_primary_nav(), $locationmainmenucustommerged);
        $moremenu = new \core\navigation\output\more_menu((object) $mainmenudata, 'navbar-nav', false);
        $moremenudata = $moremenu->export_for_template($output);
        if (!empty($moremenudata)) {
            $moremenudata['reactprops'] = $this->export_react_props($mainmenudata, $moremenudata);
        }

        // Menubar.
        // Items of menus only added in the menubar.
        // Removed the menu nodes from menubar, each item will be displayed as menu in menubar.
        if (!empty($locationmenubarmenu)) {
            // Set the visibility of the menu bar for mobile, tablet, and desktop based on its child items.
            $hidedesktop = $hidemobile = $hidetablet = 1;
            foreach ($locationmenubarmenu as $key => $menu) {
                // Check if the menu has any children to be displayed on desktop.
                if (!isset($menu->desktop) || empty($menu->desktop)) {
                    $hidedesktop = 0;
                }
                // Check if the menu has any children to be displayed on tablets.
                if (!isset($menu->tablet) || empty($menu->tablet)) {
                    $hidetablet = 0;
                }
                // Check if the menu has any children to be displayed on mobiles.
                if (!isset($menu->mobile) || empty($menu->mobile)) {
                    $hidemobile = 0;
                }
            }

            // Flag the active nodes of the menu bar menus based on the current page. This is done like
            // merge_primary_and_custom() does it for the main navigation: Stop at the first active menu so that
            // only one menu is active at a time.
            foreach (array_keys($locationmenubarmenu) as $i) {
                $menu = (object) $locationmenubarmenu[$i];
                $active = $this->flag_active_nodes($menu);
                if (is_array($locationmenubarmenu[$i])) {
                    $locationmenubarmenu[$i] = (array) $menu;
                }
                if ($active) {
                    break;
                }
            }

            $menubarmoremenu = new \core\navigation\output\more_menu(
                (object) $locationmenubarmenu,
                'navbar-nav-menu-bar',
                false
            );
            $menubartemplatedata = $menubarmoremenu->export_for_template($output);
            if (!empty($menubartemplatedata)) {
                $menubartemplatedata['reactprops'] = $this->export_react_props($locationmenubarmenu, $menubartemplatedata);
            }

            // Define the visibility classes for the menubar.
            $menubarclasses[] = $hidedesktop ? 'd-lg-none' : 'd-lg-flex';
            $menubarclasses[] = $hidetablet ? 'd-md-none' : 'd-md-flex';
            $menubarclasses[] = $hidemobile ? 'd-none' : 'd-flex';
            $menubartemplatedata['classes'] = implode(' ', $menubarclasses);
        }

        // Mobile primary navigation.
        // The mobile navigation menu uses the expand/collapse method for submenus, for this reason the expanded
        // menu flags are used.
        $mobileprimarynav = $this->merge_primary_and_custom($this->get_primary_nav(), $locationmainmenucustommerged, true);

        // Usermenu.
        // Merge the smartmenu nodes which contains the location for user menu, with the default core user menu nodes.
        $languagemenu = new \core\output\language_menu($this->page);
        $usermenu = $this->get_user_menu($output);
        // Flag the active nodes of the user menu smart menus based on the current page (in the same way as it is done
        // for the menu bar above).
        foreach (array_keys($locationusermenus) as $i) {
            $menu = (object) $locationusermenus[$i];
            $active = $this->flag_active_nodes($menu);
            if (is_array($locationusermenus[$i])) {
                $locationusermenus[$i] = (array) $menu;
            }
            if ($active) {
                break;
            }
        }
        $this->build_usermenus($usermenu, $locationusermenus);

        // Check if any of the smartmenus are going to be included on the page.
        // This is used as flag for controlling the smart menu SCSS.
        $includesmartmenu = (!empty($locationmainmenu) || !empty($locationmenubarmenu) || !empty($locationusermenus));

        // Check if any of the rendered smart menu items requires client-side starred-courses cache invalidation.
        $includestarredcacheinvalidation = $this->smartmenus_require_starred_cacheinvalidation($cache);

        return [
            'mobileprimarynav' => $mobileprimarynav,
            'moremenu' => $moremenudata,
            'menubar' => $menubartemplatedata ?? false,
            'lang' => !isloggedin() || isguestuser() ? $languagemenu->export_for_template($output) : [],
            'user' => $usermenu ?? [],
            'includesmartmenu' => $includesmartmenu ? true : false,
            'includestarredcacheinvalidation' => $includestarredcacheinvalidation ? true : false,
        ];
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
            // The menu description.
            $item['helptext'] = !empty($node['helptext']) ? (string) $node['helptext'] : null;
            $item['helptextposition'] = null;
            if (!empty($node['abovehelptext'])) {
                $item['helptextposition'] = 'above';
            } else if (!empty($node['belowhelptext'])) {
                $item['helptextposition'] = 'below';
            }
            $item['helpicon'] = !empty($node['helpicon']) ? (string) $node['helpicon'] : null;
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
     * @param array $usermenu
     * @param array $menus
     * @return void
     */
    public function build_usermenus(&$usermenu, $menus) {

        if (empty($menus)) {
            return [];
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
