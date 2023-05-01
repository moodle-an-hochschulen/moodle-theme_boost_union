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
 * @copyright  bdecent GmbH 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\output\navigation;

use custom_menu;
use \theme_boost_union\smartmenu;
use renderer_base;

/**
 * Extending the \core\navigation\output\primary renderer.
 */
class primary extends \core\navigation\output\primary {

    /** @var moodle_page $page the moodle page that the navigation belongs to */
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
     * Updated the export_for_template method of \core\navigation\output\primary from lib\classes\navigation\output\primary.php.
     *
     * Build the smartmenus and its items as navigation nodes.
     * Generate the nodes for different locations based on the menus locations.
     * Combine the smart menus nodes with core primary menus.
     *
     * @param renderer_base|null $output
     * @return array
     */
    public function export_for_template(?renderer_base $output = null): array {
        global $DB;

        $dbman = $DB->get_manager();
        // Backward support check.
        if (!$dbman->table_exists('theme_boost_union_menus')) {
            return parent::export_for_template($output);
        }

        if (!$output) {
            $output = $this->page->get_renderer('core');
        }

        // Generate the menus and its items into nodes.
        $smartmenus = smartmenu::build_smartmenu();

        // Get the menus for mainmenu.
        $mainmenu = smartmenu::get_menus_forlocation(smartmenu::LOCATION_MAIN, $smartmenus);
        // Separate the menus for menubar.
        $menubarmenus = smartmenu::get_menus_forlocation(smartmenu::LOCATION_MENU, $smartmenus);
        // Separate the menus for usermenus.
        $locationusermenus = smartmenu::get_menus_forlocation(smartmenu::LOCATION_USER, $smartmenus);
        // Separate the menus for bottom menu.
        $locationbottom = smartmenu::get_menus_forlocation(smartmenu::LOCATION_BOTTOM, $smartmenus);

        // Merge the smartmenu nodes which contians the main menu location with primary and custom menu nodes.
        $menudata = (object) array_merge($this->get_primary_nav(), $this->get_custom_menu($output), $mainmenu);
        $moremenu = new \core\navigation\output\more_menu($menudata, 'navbar-nav', false);
        // Menubar.
        // Items of menus only added in the menubar.
        // Removed the menu nodes from menubar, each item will be displayed as menu in menubar.
        $menubar = [];
        if (!empty($menubarmenus)) {
            foreach ($menubarmenus as $menu) {
                if (!empty($menu->children)) {
                    // Remove the divider from menus, Menubar doesn't supports the menubar.
                    $children = array_filter($menu->children, function($item) {
                        return !isset($item['divider']) || !$item['divider'] ? true : false;
                    });
                    $menubar = array_merge($menubar, (array) $children);
                }
            }
            $menubarmoremenu = new \core\navigation\output\more_menu((object) $menubar, 'navbar-nav-menu-bar', false);
        }

        // Bottom bar.
        $mobileprimarynav = array_merge($this->get_primary_nav(), $this->get_custom_menu($output), $locationbottom);
        $bottombar = new \core\navigation\output\more_menu((object) $mobileprimarynav, 'navbar-nav-bottom-bar', false);
        $bottombardata = $bottombar->export_for_template($output);
        $bottombardata['drawer'] = true;

        // Usermenu.
        // Merge the smartmenu nodes which contians the location for usermenu, with the default core usermenu nodes.
        $languagemenu = new \core\output\language_menu($this->page);
        $usermenu = $this->get_user_menu($output);
        $this->build_usermenus($usermenu, $locationusermenus);

        return [
            'mobileprimarynav' => $mobileprimarynav,
            'moremenu' => $moremenu->export_for_template($output),
            'menubar' => isset($menubarmoremenu) ? $menubarmoremenu->export_for_template($output) : false,
            'lang' => !isloggedin() || isguestuser() ? $languagemenu->export_for_template($output) : [],
            'user' => $usermenu ?? [],
            'bottombar' => $bottombardata
        ];
    }

    /**
     * Attach the smartmenus to usermenu which has location selected for usermenu.
     * Seperate the children items and attach those items to submenus element in usermenu.
     * Add the menus in items element in usermenu.
     *
     * Add all the items before logout menu. Removed the logout menu, then add the items into usermenu items,
     * once all items are added, then add the logout menu to menu items.
     *
     * @param array $usermenu
     * @param array $menus
     * @return void
     */
    public function build_usermenus(&$usermenu, $menus) {

        if (empty($menus)) {
            return [];
        }

        $logout = array_pop($usermenu['items']);
        foreach ($menus as $menu) {
            // Menu with empty childrens.
            if (!isset($menu->children)) {
                $usermenu['items'][] = $menu;
                continue;
            }
            // Menu with children, split the childrens and push them into submenus.
            if (isset($menu->submenuid)) {
                $children = $menu->children;
                // Update the dividers itemtype.
                array_walk($children, function(&$value) {
                    if (isset($value['divider'])) {
                        $value['itemtype'] = 'divider';
                        $value['link'] = '';
                    }
                    unset($value['itemdata']); // Remove the item data before add to usermenu.
                });

                $submenu = [
                    'id' => $menu->submenuid,
                    'title' => $menu->title,
                    'items' => $children
                ];
                $usermenu['items'][] = $menu;
                $usermenu['submenus'][] = (object) $submenu;
            }
        }

        // Update the logout menu at end of menus.
        array_push($usermenu['items'], $logout);
    }
}
