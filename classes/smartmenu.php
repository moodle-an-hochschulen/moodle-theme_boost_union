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
 * Menu controller for managing menus and menu items. Build menu for different locations.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

defined('MOODLE_INTERNAL') || die();

use context_system;
use moodle_exception;
use core\navigation\views\primary;
use cache;

require_once($CFG->dirroot.'/theme/boost_union/smartmenus/menulib.php');

/**
 * The menu controller handles actions related to managing menus.
 *
 * This controller provides methods for listing available menus, creating new menus,
 * updating existing menus, deleting menus, and sorting the order of menus.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class smartmenu {

    /**
     * The unique identifier for the menu.
     *
     * @var int
     */
    public $id;

    /**
     * The name of the menu.
     *
     * @var \stdclass
     */
    public $menu;

    /**
     * The helper object for managing smart menu restrict access rules.
     *
     * @var smartmenu_helper
     */
    public $helper;

    /**
     * The cache object for caching menu data.
     *
     * @var \cache
     */
    public $cache;

    /**
     * Constants that define different locations where the menu can appear.
     * Displayed in Main menu.
     *
     * @var int
     */
    public const LOCATION_MAIN = 1;

    /**
     * Displayed in Menu bar. Top of the main menu.
     *
     * @var int
     */
    public const LOCATION_MENU = 2;

    /**
     * Display in Usermenu.
     *
     * @var int
     */
    public const LOCATION_USER = 3;

    /**
     * Display in the Bottom mobile bar.
     *
     * @var int
     */
    public const LOCATION_BOTTOM = 4;

    /**
     * The description text is never displayed.
     * @var int
     */
    public const DESC_NEVER = 0;

    /**
     * The description text is displayed above the menu item.
     * @var int
     */
    public const DESC_ABOVE = 1;

    /**
     * The description text is displayed below the menu item.
     * @var int
     */
    public const DESC_BELOW = 2;

    /**
     * The description text is displayed in a help tooltip when the user hovers over the menu item.
     * @var int
     */
    public const DESC_HELP = 3;

    /**
     * Constants for the type of smart menu display
     * Displays menu items in a list format.
     *
     * @var int
     */
    public const TYPE_LIST = 0;

    /**
     * Displays menu items in a card format.
     * @var int
     */
    public const TYPE_CARD = 1;

    /**
     * Roles based on Any context
     * @var int
     */
    public const ANYCONTEXT = 1;

    /**
     * Roles based on System context
     * @var int
     */
    public const SYSTEMCONTEXT = 2;

    /**
     * Constants for access rules all matching
     * @var int
     */
    public const ALL = 1;

    /**
     * Constants for access rule any of matching
     * @var int
     */
    public const ANY = 2;

    /**
     * Class constants for specifying image dimensions
     */

    /**
     * Square (1/1) dimensions
     * @var int
     */
    public const CARDFORM_SQUARE = 1;

    /**
     * Portrait (2/3) dimensions
     * @var int
     */
    public const CARDFORM_PORTRAIT = 2;

    /**
     * Landscape (3/2) dimensions
     * @var int
     */
    public const CARDFORM_LANDSCAPE = 3;

    /**
     * Full width dimensions
     * @var int
     */
    public const CARDFORM_FULLWIDTH = 4;

    /**
     * Tiny size option.
     * @var int
     */
    public const CARDSIZE_TINY = 1;

    /**
     * Small - Card size option .
     * @var int
     */
    public const CARDSIZE_SMALL = 2;

    /**
     * Medium size option for card.
     * @var int
     */
    public const CARDSIZE_MEDIUM = 3;

    /**
     * Large size option for card.
     * @var int
     */
    public const CARDSIZE_LARGE = 4;

    /**
     * Constants for controlling the display of the "More" menu.
     * Default position (below main menu)
     * @var int
     */
    public const MOREMENU_DONOTCHANGE = 0;

    /**
     * Position into the main menu.
     * @var int
     */
    public const MOREMENU_INTO = 1;

    /**
     * Position outside of the main menu.
     * @var int
     */
    public const MOREMENU_OUTSIDE = 2;

    /**
     * Flag to indicate the overflow behavior of card, should be wrapped.
     * @var int
     */
    public const CARDOVERFLOWBEHAVIOUR_WRAP = 1;

    /**
     * Flag to indicate the overflow behavior of card, should not be wrapped.
     * @var int
     */
    public const CARDOVERFLOWBEHAVIOUR_NOWRAP = 2;

    /**
     * Display the menuitems as menu.
     * @var int
     */
    public const MODE_INLINE = 2;

    /**
     * Display the menuitems as submenu.
     * @var int
     */
    public const MODE_SUBMENU = 1;

    /**
     * Restrict to admins: Show to all users.
     * @var int
     */
    public const BYADMIN_ALL = 0;

    /**
     * Restrict to admins: Show only to admins.
     * @var int
     */
    public const BYADMIN_ADMINS = 1;

    /**
     * Restrict to admins: Show only to non-admins.
     * @var int
     */
    public const BYADMIN_NONADMINS = 2;

    /**
     * Cache key for the menus list.
     */
    public const CACHE_MENUSLIST = 'menuslist';

    /**
     * Create an instance of the smartmenu class from the given menu ID or menu object/array.
     *
     * @param int|stdclass $menu
     * @return smartmenu
     */
    public static function instance($menu) {

        if (is_scalar($menu)) {
            $menu = self::get_menu($menu);
        }

        if (!is_array($menu) && !is_object($menu)) {
            throw new moodle_exception('menuformatnotcorrect', 'theme_boost_union');
        }
        return new self($menu);
    }

    /**
     * SmartMenu constructor.
     *
     * @param mixed $menu Menu data.
     * @throws moodle_exception If menu format is not correct.
     */
    public function __construct($menu) {
        $this->id = $menu->id;
        $this->menu = self::update_menu_valuesformat($menu);
        $this->helper = new smartmenu_helper($this->menu);
        // Cache for menu.
        $this->cache = cache::make('theme_boost_union', 'smartmenus');
    }

    /**
     * Find the menu's visibility, it only consider the menus own visiblity
     * Please use the $this->menu->visible to check only menus visiblity.
     *
     * @return bool
     */
    public function is_visible() {
        // Verify the menus access rules for current user.
        return $this->menu->visible && $this->helper->verify_access_restrictions($this->menu) ? true : false;
    }

    /**
     * Delete the current menu and all its associated items from the database.
     *
     * @return bool True if the deletion is successful, false otherwise.
     */
    public function delete_menu() {
        global $DB;
        if ($DB->delete_records('theme_boost_union_menus', ['id' => $this->id])) {
            // Delete all its items.
            $DB->delete_records('theme_boost_union_menuitems', ['menu' => $this->id]);
            // Purge the menus cache.
            $this->cache->delete_menu($this->id);
            // Delete the cached menus list.
            $this->cache->delete(self::CACHE_MENUSLIST);

            return true;
        }
        return false;
    }

    /**
     * Move the current menu upwards in the list of menus, fetch the previous menu and move the menu to previous menu position.
     *
     * @return bool true if the menu was moved successfully, false otherwise.
     */
    public function move_upward() {
        global $DB;
        // Current menu position.
        $currentposition = $this->menu->sortorder;
        // Confirm is moving upward is possible.
        if ($currentposition > 1) {
            $menus = $DB->get_records('theme_boost_union_menus', ['sortorder' => $currentposition - 1]);
            if (empty($menus)) {
                return false;
            }
            $prevmenu = current($menus);
            // Update the menu position to upwards.
            $DB->set_field('theme_boost_union_menus', 'sortorder', $prevmenu->sortorder, ['id' => $this->id]);
            // Set the prevmenu position to down.
            $DB->set_field('theme_boost_union_menus', 'sortorder', $currentposition, ['id' => $prevmenu->id]);
            // Purge the menus list, need to recreate in new order.
            $this->cache->delete(self::CACHE_MENUSLIST);

            return true;
        }
        return false;
    }

    /**
     * Move the current menu downwards in the list of menus, fetch the next menu and move the menu to next menu position.
     *
     * @return bool true if the menu was moved successfully, false otherwise.
     */
    public function move_downward() {
        global $DB;
        $currentposition = $this->menu->sortorder;
        // Confirm is moving downward is possible.
        if ($currentposition < self::get_menuscount()) {
            $menus = $DB->get_records('theme_boost_union_menus', ['sortorder' => $currentposition + 1]);
            if (empty($menus)) {
                return false;
            }
            $nextmenu = current($menus);
            // Update the menu position to down.
            $DB->set_field('theme_boost_union_menus', 'sortorder', $nextmenu->sortorder, ['id' => $this->id]);
            // Set the nextmenu position to up.
            $DB->set_field('theme_boost_union_menus', 'sortorder', $currentposition, ['id' => $nextmenu->id]);
            // Purge the menus list, need to recreate in new order.
            $this->cache->delete(self::CACHE_MENUSLIST);

            return true;
        }
        return false;
    }

    /**
     * Duplicates the current menu, along with all its menu items.
     *
     * @return bool True on success, false on failure.
     * @throws moodle_exception If the menu format is not correct.
     */
    public function duplicate() {

        // Get list of items associated with its current menu.
        $items = $this->get_menu_items();
        // Clone the menu.
        $record = $this->menu;
        // Remove the id to create the menu as new menu.
        $record->id = 0;

        // Send the current menu without id to manage_instance will insert the menu as new menu.
        $duplicateid = self::manage_instance($record);

        if (!empty($items)) {
            // Duplicate the items related to menu.
            foreach ($items as $item) {
                $item = \theme_boost_union\smartmenu_item::instance($item->id)->item;
                $item->id = 0;
                // New menu id as menu for duplicate item.
                $item->menu = $duplicateid;
                // Create the item.
                \theme_boost_union\smartmenu_item::manage_instance($item);
            }
        }
        // Success message for duplicated menu.
        \core\notification::success(get_string('smartmenusmenuduplicatesuccess', 'theme_boost_union'));

        // New menu added, Recreate the menuslist in cache.
        $this->cache->delete(self::CACHE_MENUSLIST);

        return true;
    }

    /**
     * Updates the "visible" field of the current menu and deletes it from the cache.
     *
     * @param bool $visible The new value for the "visible" field.
     * @return bool True if the update was successful, false otherwise.
     */
    public function update_visible(bool $visible) {
        // Delete the current menu from cache.
        $this->cache->delete_menu($this->id);
        // Purge the menus list from cache, recreates it on next page load.
        $this->cache->delete(self::CACHE_MENUSLIST);

        return $this->update_field('visible', $visible, ['id' => $this->id]);
    }

    /**
     * Updates a field of the current menu with the given key and value.
     *
     * @param string $key The key of the field to update.
     * @param mixed $value The new value of the field.
     * @return bool|int Returns true on success, or false on failure. it also deletes the current menu from cache.
     */
    public function update_field($key, $value) {
        global $DB;

        $result = $DB->set_field('theme_boost_union_menus', $key, $value, ['id' => $this->id]);

        // Delete the current menu from cache.
        $this->cache->delete_menu($this->id);

        return $result;
    }

    /**
     * Get the HTML class for the card form size.
     *
     * @return string HTML class for the card form size.
     */
    protected function get_cardform() {

        $options = [
                self::CARDFORM_SQUARE => 'square',
                self::CARDFORM_PORTRAIT => 'portrait',
                self::CARDFORM_LANDSCAPE => 'landscape',
                self::CARDFORM_FULLWIDTH => 'fullwidth',
        ];

        return isset($options[$this->menu->cardform]) ? 'card-form-'.$options[$this->menu->cardform] : '';
    }

    /**
     * Get the HTML class for the card size.
     *
     * @return string HTML class for the card size.
     */
    protected function get_cardsize() {

        $options = [
                self::CARDSIZE_TINY => 'tiny',
                self::CARDSIZE_SMALL => 'small',
                self::CARDSIZE_MEDIUM => 'medium',
                self::CARDSIZE_LARGE => 'large',
        ];

        return isset($options[$this->menu->cardsize]) ? 'card-size-' . $options[$this->menu->cardsize] : '';
    }

    /**
     * Get the HTML class for the card overflow behaviour.
     *
     * @return string HTML class for the card overflow behaviour.
     */
    public function get_cardwrap() {

        $options = [
                self::CARDOVERFLOWBEHAVIOUR_WRAP => 'wrap',
                self::CARDOVERFLOWBEHAVIOUR_NOWRAP => 'no-wrap',
        ];

        return isset($options[$this->menu->cardoverflowbehavior]) ? 'card-overflow-' .
                $options[$this->menu->cardoverflowbehavior] : '';
    }


    /**
     * Fetch the list of menuitems assosciated with current menu.
     *
     * @return array|false List of menu items or false if no items found.
     */
    public function get_menu_items() {
        global $DB;

        $sql = "SELECT mi.* FROM {theme_boost_union_menuitems} mi
            LEFT JOIN {theme_boost_union_menus} mn ON mn.id = mi.menu
            WHERE mi.menu=:id
            ORDER BY mi.sortorder ASC";

        $params = [
            'id' => $this->menu->id,
        ];
        $items = $DB->get_records_sql($sql, $params);

        return $items;
    }

    /**
     * This method is responsible for building the menu and its associated menu items.
     * It first checks whether the menu is visible or not. If the menu is not visible, it returns false.
     *
     * Next, the method checks the cache for the menu and its menu items. If the cache has the data, it returns the cached data.
     * Otherwise, it builds the menu and its items from scratch.
     *
     * The method build node from menu, These node array include the menu's classes, title, URL, text, and key.
     * It also sets up some additional properties like itemtype, submenuid, card, forceintomoremenu,
     * and haschildren based on the menu's properties.
     *
     * Then fetches the menu items for the menu and builds them one by one.
     * It checks the type of the item, whether it is static or dynamic, and processes it accordingly.
     * If the menu has any child items, the method sets up a children array for the menu node and adds the child items to it.
     * It then sets the haschildren property to true.
     *
     * Finally, the processed menu node and its child items are stored in the cache, and the method returns the node.
     *
     * @param bool $resetcache True means remove the cache and build. Useful to session based menus and items purge.
     * @return false|object Returns false if the menu is not visible or a menu object otherwise.
     */
    public function build($resetcache=false) {
        global $OUTPUT, $USER;
        static $itemcache;

        if (empty($itemcache)) {
            $itemcache = cache::make('theme_boost_union', 'smartmenu_items');
        }
        // Make cachekey using combine the menu and current user.
        $cachekey = "{$this->menu->id}_u_{$USER->id}";

        // Purge the cached menus data if the menu date restrictions are reached or passed.
        smartmenu_helper::purge_cache_date_reached($this->cache, $this->menu, 'menulastcheckdate');

        // Get the menu and its menu items from cache.
        $menuitems = [];
        $nodes = $this->cache->get($cachekey);
        if (!empty($nodes)) {
            // List of menu items added to this menu.
            $menuitems = $nodes->menuitems ?? [];

        } else {
            // Set flag to store the menu data to cache.
            $storecache = true;

            if (!$this->is_visible()) {
                return false;
            }

            // Add marker class to make clear that this is a Boost Union smart menu.
            $this->menu->classes[] = 'boost-union-smartmenu';

            // Add custom CSS class.
            $this->menu->classes[] = $this->menu->cssclass;

            // Add CSS classes for card menus.
            $this->menu->classes[] = $this->get_cardform(); // Html class for the card form size, Potrait, Square, landscape.
            $this->menu->classes[] = $this->get_cardsize(); // HTML class for the card Size, tiny, small, medium, large.
            $this->menu->classes[] = $this->get_cardwrap(); // HtML class for the card overflow behaviour.

            // Add CSS classes for more behaviour.
            $this->menu->classes[] = ($this->menu->moremenubehavior == self::MOREMENU_OUTSIDE) ? "force-menu-out" : '';

            // Card type menus doesn't supports inline menus.
            // Mode is submenu or not set anything then create the menuitems as submenu.
            // Otherwise add the menu items directoly as menu.
            if ($this->menu->mode != self::MODE_INLINE || $this->menu->type == self::TYPE_CARD) {

                $nodes = (object) [
                    'menudata' => $this->menu,
                    // Do not set the title attribute as this would show a standard tooltip based on the
                    // Moodle core custom menu logic.
                    'title' => '',
                    'url' => null,
                    'text' => format_string($this->menu->title),
                    'key' => $this->menu->id,
                    'submenulink' => 1,
                    'itemtype' => 'submenu-link', // Used in user menus, to identify the menu type, "link" for submmenus.
                    'submenuid' => uniqid(), // Menu has user menu location, then the submenu id is manatory for submenus.
                    'card' => ($this->menu->type == self::TYPE_CARD) ? true : false,
                    'forceintomoremenu' => ($this->menu->moremenubehavior == self::MOREMENU_INTO) ? true : false,
                    'haschildren' => 0,
                    'sort' => uniqid(), // Support third level menu.
                ];

                // Add the description data to nodes. Inline mode menus not supports the menu.
                if ($this->menu->showdesc != self::DESC_NEVER) {
                    $description = format_text($this->menu->description['text'], FORMAT_HTML);
                    $nodes->helptext = $description;
                    $nodes->abovehelptext = ($this->menu->showdesc == self::DESC_ABOVE) ? true : false;
                    $nodes->belowhelptext = ($this->menu->showdesc == self::DESC_BELOW) ? true : false;
                    // Add selector class in dropdown element for style.
                    $this->menu->classes[] = ($nodes->abovehelptext) ? 'dropdown-description-above' : '';
                    $this->menu->classes[] = ($nodes->belowhelptext) ? 'dropdown-description-below' : '';

                    // Show the description as helpicon.
                    if ($this->menu->showdesc == self::DESC_HELP) {
                        $alt = get_string('description');
                        $data = [
                            'text' => $description,
                            'alt' => $alt,
                            'icon' => (new \core\output\pix_icon('help', $alt, 'core'))->export_for_template($OUTPUT),
                            'ltr' => !right_to_left(),
                        ];
                        $nodes->helpicon = $OUTPUT->render_from_template('core/help_icon', $data);
                    }

                }
                // Menu is set to inline, items classes are loadded in this variable menuclasses in template.
                $nodes->menuclasses = $this->menu->classes; // Menus classes.
            }
        }
        // Menus not exists in cache, then build the menu and menu items.
        // Get list of its items.
        $menuitems = $menuitems ?: $this->get_menu_items();

        if (!empty($menuitems)) {

            $builditems = [];
            foreach ($menuitems as $item) {
                // Need to purge the items for user, remove the cache before build.
                if ($resetcache) {
                    // Purge the items cache for this user.
                    $cachekey = "{$item->id}_u_{$USER->id}";
                    $itemcache->delete($cachekey);
                }
                // Build the item based on restrict rules and its type like static, dynamic.
                $item = \theme_boost_union\smartmenu_item::instance($item, $this->menu)->build();
                // Merge the dynamic course items as single item.
                $builditems = (!empty($item)) ? array_merge($builditems, $item) : $builditems;
            }

            if (isset($nodes) && !empty($nodes)) {
                // Setup the childrens to parent menu node.
                $nodes->haschildren = (count($builditems) > 0) ? true : false;
                $nodes->children = $builditems;
            } else {
                // If menu is inline mode, then it items are displayed directly in menus.
                // Set the menuitems as separate menu node in cache.
                // Remove dividers from inline menus.
                $builditems = array_filter($builditems, function($item) {
                    // Remove the item is divider.
                    return !isset($item['divider']) || !$item['divider'];
                });

                array_walk($builditems, function(&$item) {
                    // Make the dynamic courses as top menu for user menus dropdown. if menu mode is inline.
                    if ($item['haschildren']) {
                        // Below elements are used to separate the submenus and links for usermenu.
                        $item['itemtype'] = 'submenu-link';
                        $item['submenulink'] = 1;
                        $item['link'] = 0;
                        $item['submenuid'] = uniqid();
                    }
                });

                $nodes = $builditems;
            }
        }

        // Set the processed menus node and its children item nodes in Cache.
        if (isset($nodes) && isset($storecache)) {
            $nodescache = clone (object) $nodes;
            // Remove the children data from cache before store.
            unset($nodescache->children);
            $nodescache->menuitems = $menuitems;
            $this->cache->set($cachekey, $nodescache);
        }

        // If the current menu doesn't contain any nodes, hide the menu from users.
        // Verify after storing the cache to prevent rebuilding the menu items.
        // This helps to verify the cached menus, too.
        if (!isset($builditems) || empty($builditems)) {
            return false;
        }

        // Remove the menu items list from nodes. it doesn't need to build the smartmenus.
        if (isset($nodes->menuitems)) {
            // Remove the menu items list from nodes, it doesn't need anymore.
            unset($nodes->menuitems);
        }

        return $nodes ?? false;
    }

    /**
     * Retrieves the list of menus that are assigned to the specified location.
     *
     * @param string $location The location to retrieve menus for.
     * @param array $menus The list of all available menus.
     * @return array An array of menus that are assigned to the specified location.
     */
    public static function get_menus_forlocation($location, $menus) {

        if (empty($menus) || $location == '') {
            return [];
        }
        // Get the menu location from topmenus list.
        // Locations from itemdata is not accurate, to fix this need to remove the all of items cache for the updated menu.
        // Instead of delete item caches, get locations from cached menus list.
        $topmenus = smartmenu_helper::get_menu_cache()->get(self::CACHE_MENUSLIST);

        $menulocation = [];
        foreach ($menus as $menu) {

            $menu = (object) $menu;

            if (isset($menu->menudata->location)) {
                $menulocation = $menu->menudata->location;
            } else if (isset($menu->itemdata->menu) && isset($topmenus[$menu->itemdata->menu])) { // Inline menus.
                $menulocation = json_decode($topmenus[$menu->itemdata->menu]->location);
            }

            if (!isset($menulocation) || empty($menulocation)) {
                continue;
            }
            // The menu contians the specified location. then store the menu for this location.
            if (in_array($location, $menulocation)) {
                $result[] = $menu;
                $menulocation = []; // Reset the menu location for verify next menu.
            }
        }

        return $result ?? [];
    }

    /**
     * Fetches a menu record from the database by ID and returns it as an object with convert the json values to array.
     *
     * @param int $id The ID of the menu to fetch.
     * @return stdClass|false Returns an object if the menu is found, false otherwise.
     */
    public static function get_menu($id) {
        global $DB;

        // Verfiy and Fetch menu record from DB.
        if ($record = $DB->get_record('theme_boost_union_menus', ['id' => $id])) {

            // Decode the multiple option select elements values to array.
            $record = self::update_menu_valuesformat($record);

            return $record;
        } else {
            throw new moodle_exception('error:smartmenusmenunotfound', 'theme_boost_union');
        }
        return false;
    }

    /**
     * Update the menu values from json to array.
     *
     * @param stdclass $menu Record data of the menu.
     * @return stdclass Converted menu data.
     */
    public static function update_menu_valuesformat($menu) {

        // Verify the format is already updated.
        if (!is_scalar($menu->location)) {
            return $menu;
        }

        $menu->description = [
            'text'   => $menu->description,
            'format' => $menu->description_format,
        ];
        // Decode the multiple option select elements values to array.
        $menu->location = json_decode($menu->location);
        $menu->roles = json_decode($menu->roles);
        $menu->cohorts = json_decode($menu->cohorts);
        $menu->languages = json_decode($menu->languages);
        $menu->mode = $menu->mode ?? self::MODE_SUBMENU; // Submenu is default menu mode.

        return $menu;
    }

    /**
     * Returns the count of all menus in the database.
     *
     * @return int The number of menus.
     */
    public static function get_menuscount() {
        global $DB;
        return $DB->count_records('theme_boost_union_menus', []);
    }

    /**
     * Get the last menu from records based on the sordorder.
     *
     * @return stdClass An object representing the last menu, or an empty object if no menus exist.
     */
    public static function get_lastmenu() {
        global $DB;
        $records = $DB->get_records_sql('SELECT * FROM {theme_boost_union_menus} ORDER BY sortorder DESC', [], 0, 1);
        return (object) (!empty($records) ? current($records) : []);
    }

    /**
     * Get all available smart menu locations.
     * Menu will be displayed in Main menu, Menu bar (above the main menu), User menu, Bottom menu.
     *
     * @return array An array with all available locations.
     */
    public static function get_locations() {
        // List of locations where same menu can be used in multiple places.
        $locations = [
            self::LOCATION_MAIN => get_string('smartmenusmenulocationmain', 'theme_boost_union'),
            self::LOCATION_MENU => get_string('smartmenusmenulocationmenu', 'theme_boost_union'),
            self::LOCATION_USER => get_string('smartmenusmenulocationuser', 'theme_boost_union'),
            self::LOCATION_BOTTOM => get_string('smartmenusmenulocationbottom', 'theme_boost_union'),
        ];

        return $locations;
    }

    /**
     * Fetch the user readable name for a specific location of the current menu.
     *
     * @param int $location The type ID.
     * @return string|false The localized name of the location, or false if the type is invalid.
     */
    public static function get_location($location) {
        $locations = self::get_locations();
        return $locations[$location] ?? false;
    }

    /**
     * Get all available smart menu types. Either card or list.
     *
     * @return array An array with all available types, where key is the type id and value is the localized type name.
     */
    public static function get_types() {
        $types = [
            self::TYPE_LIST => get_string('smartmenusmenutypelist', 'theme_boost_union'),
            self::TYPE_CARD => get_string('smartmenusmenutypecard', 'theme_boost_union'),
        ];

        return $types;
    }

    /**
     * Get the localized name for a specific type.
     *
     * @param int $type The type ID.
     * @return string|false The localized name of the type, or false if the type is invalid.
     */
    public static function get_type($type) {
        $types = self::get_types();
        return $types[$type] ?? false;
    }

    /**
     * Return options for the mode setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_mode_options(): array {
        return [
            self::MODE_SUBMENU => get_string('smartmenusmodesubmenu', 'theme_boost_union'),
            self::MODE_INLINE => get_string('smartmenusmodeinline', 'theme_boost_union'),
        ];
    }

    /**
     * Return options for the showdescription setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_showdescription_options(): array {
        return [
            self::DESC_NEVER => get_string('smartmenusmenushowdescriptionnever', 'theme_boost_union'),
            self::DESC_ABOVE => get_string('smartmenusmenushowdescriptionabove', 'theme_boost_union'),
            self::DESC_BELOW => get_string('smartmenusmenushowdescriptionbelow', 'theme_boost_union'),
            self::DESC_HELP => get_string('smartmenusmenushowdescriptionhelp', 'theme_boost_union'),
        ];
    }

    /**
     * Return options for the moremenu setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_moremenu_options(): array {
        return [
            self::MOREMENU_DONOTCHANGE => get_string('dontchange', 'theme_boost_union'),
            self::MOREMENU_INTO => get_string('smartmenusmenumoremenubehaviorforceinto', 'theme_boost_union'),
            self::MOREMENU_OUTSIDE => get_string('smartmenusmenumoremenubehaviorkeepoutside', 'theme_boost_union'),
        ];
    }

    /**
     * Return options for the cardsize setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_cardsize_options(): array {
        return [
            self::CARDSIZE_TINY => get_string('smartmenusmenucardsizetiny', 'theme_boost_union').' (50px)',
            self::CARDSIZE_SMALL => get_string('smartmenusmenucardsizesmall', 'theme_boost_union').' (100px)',
            self::CARDSIZE_MEDIUM => get_string('smartmenusmenucardsizemedium', 'theme_boost_union').' (150px)',
            self::CARDSIZE_LARGE => get_string('smartmenusmenucardsizelarge', 'theme_boost_union').' (200px)',
        ];
    }

    /**
     * Return options for the cardform setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_cardform_options(): array {
        return[
            self::CARDFORM_SQUARE =>
                get_string('smartmenusmenucardformsquare', 'theme_boost_union').' (1/1)',
            self::CARDFORM_PORTRAIT =>
                get_string('smartmenusmenucardformportrait', 'theme_boost_union').' (2/3)',
            self::CARDFORM_LANDSCAPE =>
                get_string('smartmenusmenucardformlandscape', 'theme_boost_union').' (3/2)',
            self::CARDFORM_FULLWIDTH =>
                get_string('smartmenusmenucardformfullwidth', 'theme_boost_union'),
        ];
    }

    /**
     * Return options for the cardoverflowbehaviour setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_cardoverflowbehaviour_options(): array {
        return [
            self::CARDOVERFLOWBEHAVIOUR_NOWRAP =>
                get_string('smartmenusmenucardoverflowbehaviornowrap', 'theme_boost_union'),
            self::CARDOVERFLOWBEHAVIOUR_WRAP =>
                get_string('smartmenusmenucardoverflowbehaviorwrap', 'theme_boost_union'),
        ];
    }

    /**
     * Return options for the rolecontext setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_rolecontext_options(): array {
        return [
            self::ANYCONTEXT => get_string('any'),
            self::SYSTEMCONTEXT => get_string('coresystem'),
        ];
    }

    /**
     * Return options for the byadmin setting.
     *
     * @return array
     */
    public static function get_byadmin_options(): array {
        return [
            self::BYADMIN_ALL => get_string('smartmenusbyadmin_all', 'theme_boost_union'),
            self::BYADMIN_ADMINS => get_string('smartmenusbyadmin_admins', 'theme_boost_union'),
            self::BYADMIN_NONADMINS => get_string('smartmenusbyadmin_nonadmins', 'theme_boost_union'),
        ];
    }

    /**
     * Return options for the operator setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_operator_options(): array {
        return [
            self::ANY => get_string('any'),
            self::ALL => get_string('all'),
        ];
    }

    /**
     * Insert or update the menu instance to DB. Convert the multiple options select elements to json.
     * setup menu order after insert.
     *
     * Delete the current menu cache after updated the menu.
     *
     * @param stdclass $formdata
     * @return int The menu ID.
     */
    public static function manage_instance($formdata) {
        global $DB;

        $record = $formdata;

        $record->description_format = $formdata->description['format'];
        $record->description = $formdata->description['text'];

        // Encode the multiple value elements into json to store.
        $record->location = json_encode($formdata->location);
        $record->roles = json_encode($formdata->roles);
        $record->cohorts = json_encode($formdata->cohorts);
        $record->languages = json_encode($formdata->languages);

        $cache = cache::make('theme_boost_union', 'smartmenus');

        $transaction = $DB->start_delegated_transaction();

        if (isset($formdata->id) && $DB->record_exists('theme_boost_union_menus', ['id' => $formdata->id])) {
            $menuid = $formdata->id;

            $DB->update_record('theme_boost_union_menus', $record);
            // Clear the current menu caches. Update may cause changes in the menus list.
            // Delete the menu cache for all users.
            $cache->delete_menu($menuid);
            // Menu updated, recreate the menuslist.
            $cache->delete(self::CACHE_MENUSLIST);
            // Show the edited success notification.
            \core\notification::success(get_string('smartmenusmenueditsuccess', 'theme_boost_union'));
        } else {
            // Setup the menu order.
            $lastmenu = self::get_lastmenu();
            $record->sortorder = isset($lastmenu->sortorder) ? $lastmenu->sortorder + 1 : 1;
            $menuid = $DB->insert_record('theme_boost_union_menus', $record);
            // New menu added, recreate the menuslist.
            $cache->delete(self::CACHE_MENUSLIST);
            // Show the menu inserted success notification.
            \core\notification::success(get_string('smartmenusmenucreatesuccess', 'theme_boost_union'));
        }

        // Allow to update the DB changes to Database.
        $transaction->allow_commit();

        return $menuid;
    }

    /**
     * Retrieve all top level menus.
     *
     * @return array|false An array of top level menus or false if no menu found.
     */
    public static function get_menus() {
        global $DB;

        $topmenus = $DB->get_records('theme_boost_union_menus', [], 'sortorder ASC');
        return $topmenus;
    }

    /**
     * Initialize the build of smart menus, Fetch the list of menus and init the build for each menu.
     *
     * @return array An array of SmartMenu nodes.
     */
    public static function build_smartmenu() {
        global $USER;

        $nodes = [];

        // Verify the language changes in user session, if changed than purge the menus and items cache for the user session.
        self::verify_lang_session_changes();

        $cache = cache::make('theme_boost_union', 'smartmenus');
        // Fetch the list of menus from cache.
        $topmenus = $cache->get(self::CACHE_MENUSLIST);
        // Get top level menus, store the menus to cache.
        if (empty($topmenus)) {
            $topmenus = self::get_menus();
            $cache->set(self::CACHE_MENUSLIST, $topmenus);
        }

        if (empty($topmenus)) {
            // Still menus are not created.
            return false;
        }

        // Test the flag to purge the cache is set for this user.
        $removecache = (get_user_preferences('theme_boost_union_menu_purgesessioncache', false) == true);

        foreach ($topmenus as $menu) {
            // Need to purge the menus for user, remove the cache before build.
            if ($removecache) {
                // Purge the menu cache for this user.
                $cachekey = "{$menu->id}_u_{$USER->id}";
                $cache->delete($cachekey);
            }

            if ($node = self::instance($menu)->build($removecache)) {
                if (isset($node->menudata)) {
                    $nodes[] = $node;
                } else {
                    $nodes = array_merge($nodes, array_values((array) $node));
                }
            }
        }

        // Menus are purged in the build method when needed, then clear the user preference of purge cache.
        smartmenu_helper::clear_user_cachepreferencemenu();

        return $nodes;
    }

    /**
     * Verifies and handles changes in the session language.
     * Clears cached smart menus and items when the user changes the language using the language menu.
     *
     * @return void
     */
    protected static function verify_lang_session_changes() {
        global $SESSION, $USER;
        // Make sure the lang is updated for the session.
        if ($lang = optional_param('lang', '', PARAM_SAFEDIR)) {
            // Confirm the cache is not already purged for this language change. To avoid multiple purge.
            if (!isset($SESSION->prevlang) || $SESSION->prevlang != $lang) {
                // Set the purge cache preference for this session user. Cache will purged in the build_smartmenu method.
                smartmenu_helper::set_user_purgecache($USER->id);
                $SESSION->prevlang = $lang; // Save this lang for verification.
            }
        }
    }
}
