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
use templatable;
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
     * Combine the various menus into a standardized output.
     *
     * Modifications compared to the original function:
     * * Build the smart menus and its items as navigation nodes.
     * * Generate the nodes for different locations based on the menus locations.
     * * Combine the smart menus nodes with core primary menus.
     * * Convert the children menus into submenus like usermenus.
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
        // And convert the children menu items into submenus.
        $locationmainmenu = smartmenu::get_menus_forlocation(smartmenu::LOCATION_MAIN, $smartmenus);
        $locationmainmenuconverted = $this->convert_submenus($locationmainmenu);

        // Separate the menus for the menubar location.
        // And convert the children menu items into submenus.
        $locationmenubarmenu = smartmenu::get_menus_forlocation(smartmenu::LOCATION_MENU, $smartmenus);
        $locationmenubarmenuconverted = $this->convert_submenus($locationmenubarmenu);

        // Separate the menus for the usermenu location.
        // (There is no need to concert the submenus in this case).
        $locationusermenus = smartmenu::get_menus_forlocation(smartmenu::LOCATION_USER, $smartmenus);

        // Separate the menus for the bottom menu location.
        // And convert the children menu items into submenus.
        $locationbottommenu = smartmenu::get_menus_forlocation(smartmenu::LOCATION_BOTTOM, $smartmenus);
        $locationbottommenuconverted = $this->convert_submenus($locationbottommenu);

        // Primary menu.
        // Merge the smart menu nodes which contain the main menu location with the primary and custom menu nodes.
        $mainmenudata = array_merge($this->get_primary_nav(), $this->get_custom_menu($output), $locationmainmenuconverted);
        $moremenu = new \core\navigation\output\more_menu((object) $mainmenudata, 'navbar-nav', false);

        // Menubar.
        // Items of menus only added in the menubar.
        // Removed the menu nodes from menubar, each item will be displayed as menu in menubar.
        if (!empty($locationmenubarmenuconverted)) {
            $menubarmoremenu = new \core\navigation\output\more_menu((object) $locationmenubarmenuconverted,
                    'navbar-nav-menu-bar', false);
        }

        // Bottom bar.
        // Include the menu navigation menus to the mobile menu when the bottom bar doesn't have any menus.
        $mobileprimarynav = (!empty($locationbottommenu))
            ? array_merge($this->get_primary_nav(), $this->get_custom_menu($output), $locationbottommenu)
            : array_merge($this->get_primary_nav(), $this->get_custom_menu($output), $locationmainmenu);

        if (!empty($locationbottommenu)) {
            $mobilemenudata = array_merge($this->get_primary_nav(), $this->get_custom_menu($output), $locationbottommenuconverted);
            $bottombar = new \core\navigation\output\more_menu((object) $mobilemenudata, 'navbar-nav-bottom-bar', false);
            $bottombardata = $bottombar->export_for_template($output);
            $bottombardata['drawer'] = (!empty($locationbottommenu)) ? true : false;
        }

        // Usermenu.
        // Merge the smartmenu nodes which contains the location for user menu, with the default core user menu nodes.
        $languagemenu = new \core\output\language_menu($this->page);
        $usermenu = $this->get_user_menu($output);
        $this->build_usermenus($usermenu, $locationusermenus);

        // Check if any of the smartmenus are going to be included on the page.
        // This is used as flag to include the smart menu's JS file in mustache templates later
        // as well as for controlling the smart menu SCSS.
        $includesmartmenu = (!empty($locationmainmenu) || !empty($locationmenubarmenu) ||
                !empty($locationusermenus) || !empty($locationbottommenu));

        return [
            'mobileprimarynav' => $mobileprimarynav,
            'moremenu' => $moremenu->export_for_template($output),
            'menubar' => isset($menubarmoremenu) ? $menubarmoremenu->export_for_template($output) : false,
            'lang' => !isloggedin() || isguestuser() ? $languagemenu->export_for_template($output) : [],
            'user' => $usermenu ?? [],
            'bottombar' => $bottombardata ?? false,
            'includesmartmenu' => $includesmartmenu ? true : false,
        ];
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
            if ($addpreferredlangsetting == THEME_BOOST_UNION_SETTING_SELECT_YES &&
                    array_key_exists('submenus', $parentoutput)) {
                // Get the needle.
                $needle = get_string('languageselector');

                // Iterate over the submenus.
                foreach ($parentoutput['submenus'] as $sm) {
                    // Search the 'Language' submenu.
                    if ($sm->title == $needle) {
                        // Create and inject a divider node.
                        $dividernode = [
                            'title' => '####',
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
                            'url' => new \moodle_url('/user/language.php'),
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
     * once all items are added, separator included before logout
     * if any smart menus are included then added the logout menu to menu items.
     *
     * @param array $usermenu
     * @param array $menus
     * @param bool $forusermenu If false, the divider and logout nodes are unchanged.
     * @return void
     */
    public function build_usermenus(&$usermenu, $menus, $forusermenu = true) {

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
                ];
                $usermenu['submenus'][] = (object) $submenu;
                // The key of this submenu which helps later to include its children after including the necessary data.
                $lastkey = array_key_last($usermenu['submenus']);

                // Update the dividers item type.
                array_walk($children, function(&$value) use (&$usermenu, $menu) {
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

        // If the menu is to be used as user menu.
        if ($forusermenu) {
            // Include the divider after smart menus items to make difference from logout.
            $divider = [
                'title' => '####',
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
    }

    /**
     * Converts the second-level children of moremenu into submenu format, similar to usermenu.
     *
     * Updates the ID of first-level submenus as the value of 'sort', where 'sort' contains unique IDs.
     * Splits the children of first-level submenus into 'items' and 'submenus', where 'items' contain the first-level main menus
     * and 'submenus' contain their children.
     *
     * @param array $menus The array of menus to build submenus for.
     * @return array The updated array of menus with submenus built.
     */
    protected function convert_submenus($menus) {

        // Verify the empty entries.
        if (empty($menus)) {
            return $menus;
        }

        // Create a deep clone of menus, direct use of menus mismatch with the usermenus format.
        $primarymenu = array_map(function($item) {
            return clone $item;
        }, $menus);

        foreach ($primarymenu as $key => $parentmenu) {

            // Menu doesn't contain any children menus, continue to the next menu.
            if (!$parentmenu->haschildren || $parentmenu->card) {
                continue;
            }

            $submenu = [];
            // Children menus of this menu.
            $children = $parentmenu->children;

            // Updates the ID of first-level submenus as the value of 'sort', where 'sort' contains unique IDs.
            array_walk($children, function(&$val) use ($parentmenu) {
                $val['submenuid'] = $val['sort'];
            });

            // Update the format of children menus into submenus, similar to usermenu.
            $this->build_usermenus($submenu, (object) $children, false);

            // Splits the children of first-level submenus into 'items' and 'submenus'.
            $primarymenu[$key]->children = ['items' => $submenu['items'] ?? []];
            $primarymenu[$key]->submenus = $submenu['submenus'] ?? [];
        }

        return $primarymenu;
    }
}
