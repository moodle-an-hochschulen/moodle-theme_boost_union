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
 * @copyright  bdecent GmbH 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

defined('MOODLE_INTERNAL') || die();

use custom_menu;
use context_system;
use moodle_exception;
use core\navigation\views\primary;
use cache;
use cache_helper;
use smartmenu_helper;

require_once($CFG->dirroot.'/theme/boost_union/smartmenus/menulib.php');

/**
 * The menu controller handles actions related to managing menus.
 *
 * This controller provides methods for listing available menus, creating new menus,
 * updating existing menus, deleting menus, and sorting the order of menus.
 *
 * @package    theme_boost_union
 * @copyright    bdecent GmbH 2023
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
     * @var \smartmenu_helper
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
    const TYPE_LIST = 0;

    /**
     * Displays menu items in a card format.
     * @var int
     */
    const TYPE_CARD = 1;

    /**
     * Roles based on Any context
     * @var int
     */
    const ANYCONTEXT = 1;

    /**
     * Roles based on System context
     * @var int
     */
    const SYSTEMCONTEXT = 2;

    /**
     * Constants for access rules all matching
     * @var int
     */
    const ALL = 1;

    /**
     * Constants for access rule any of matching
     * @var int
     */
    const ANY = 2;

    /**
     * Class constants for specifying image dimensions
     */

    /**
     * Square (1/1) dimensions
     * @var int
     */
    public const SQUARE = 1;

    /**
     * Portrait (2/3) dimensions
     * @var int
     */
    public const PORTRAIT = 2;

    /**
     * Landscape (3/2) dimensions
     * @var int
     */
    public const LANDSCAPE = 3;

    /**
     * Full width dimensions
     * @var int
     */
    public const FULLWIDTH = 4;

    /**
     * Tiny size option.
     * @var int
     */
    public const TINY = 1;

    /**
     * Small - Card size option .
     * @var int
     */
    public const SMALL = 2;

    /**
     * Medium size option for card.
     * @var int
     */
    public const MEDIUM = 3;

    /**
     * Large size option for card.
     * @var int
     */
    public const LARGE = 4;

    /**
     * Constants for controlling the display of the "More" menu.
     * Default position (below main menu)
     * @var int
     */
    public const MOREMENU_DEFAULT = 0;

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
    public const WRAP = 1;

    /**
     * Flag to indicate the overflow behavior of card, should not be wrapped.
     * @var int
     */
    public const NOWRAP = 2;

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
        global $DB;

        $this->id = $menu->id;
        $this->menu = $menu;
        $this->helper = new \smartmenu_helper($this->menu);
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
            cache_helper::purge_by_event('theme_boost_union_menus_deleted');
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
            // Purge the menu cache.
            cache_helper::purge_by_event('theme_boost_union_menus_resorted');

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
            // Purge the menu cache.
            cache_helper::purge_by_event('theme_boost_union_menus_resorted');

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
        global $DB;

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
        \core\notification::success(get_string('smartmenu:menuduplicated', 'theme_boost_union'));

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
        $this->cache->delete($this->id);

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
        $this->cache->delete($this->id);

        return $result;
    }

    /**
     * Get the HTML class for the card form size.
     *
     * @return string HTML class for the card form size.
     */
    public function get_cardform() {

        $options = [
            self::SQUARE => 'square', self::PORTRAIT => 'portrait', self::LANDSCAPE => 'landscape', self::FULLWIDTH => 'fullwidth'
        ];

        return isset($options[$this->menu->cardform]) ? 'card-form-'.$options[$this->menu->cardform] : '';
    }

    /**
     * Get the HTML class for the card size.
     *
     * @return string HTML class for the card size.
     */
    public function get_cardsize() {

        $options = [
            self::TINY => 'tiny', self::SMALL => 'small', self::MEDIUM => 'medium', self::LARGE => 'large'
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
            self::WRAP => 'wrap', self::NOWRAP => 'no-wrap'
        ];

        return isset($options[$this->menu->overflowbehavior]) ? 'card-overflow-' . $options[$this->menu->overflowbehavior] : '';
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
            'id' => $this->menu->id
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
     * @return false|object Returns false if the menu is not visible or a menu object otherwise.
     */
    public function build() {
        global $DB;

        if (!$this->is_visible()) {
            return false;
        }

        // Cache for menu.
        $cache = cache::make('theme_boost_union', 'smartmenus');

        // Purge the cahced menus data if the menu date restrictions are reached or passed.
        smartmenu_helper::purge_cache_date_reached($cache, $this->menu, 'theme_boost_union_menulastcheckdate');

        // Get the menu and its menu items from cache.
        if ($nodes = $cache->get($this->menu->id)) {
            return $nodes;
        }

        $this->menu->classes[] = $this->get_cardform(); // Html class for the card form size, Potrait, Square, landscape.
        $this->menu->classes[] = $this->get_cardsize(); // HTML class for the card Size, tiny, small, medium, large.
        $this->menu->classes[] = $this->get_cardwrap(); // HtML class for the card overflow behaviour.

        $nodes = (object) [
            'menudata' => $this->menu,
            'title' => $this->menu->title,
            'url' => null,
            'text' => $this->menu->title,
            'key' => $this->menu->id,
            'submenulink' => 1,
            'itemtype' => 'submenu-link',
            'submenuid' => uniqid(), // Menu has user menu location, then the submenu id is manatory for submenus.
            'card' => ($this->menu->type == self::TYPE_CARD) ? true : false,
            'forceintomoremenu' => ($this->menu->moremenubehavior == self::MOREMENU_INTO) ? true : false,
            'haschildren' => 0
        ];

        // Menus not exists in cache, then build the menu and menu items.
        // Get list of its items.
        $menuitems = $this->get_menu_items();
        if (!empty($menuitems)) {

            $builditems = [];
            foreach ($menuitems as $item) {
                // Build the item based on restrict rules and its type like static, dynamic.
                $item = \theme_boost_union\smartmenu_item::instance($item->id)->build();
                // Merge the dynamic course items as single item.
                $builditems = (!empty($item)) ? array_merge($builditems, $item) : $builditems;
            }

            // Setup the childrens to parent menu node.
            $nodes->haschildren = (count($builditems) > 0) ? true : false;
            $nodes->children = $builditems;
        }
        // Set the processed menus node and its children item nodes in Cache.
        $cache->set($this->menu->id, $nodes);

        return $nodes;
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

        foreach ($menus as $menu) {
            if (empty($menu->menudata->location)) {
                continue;
            }
            // The menu contians the specified location. then store the menu for this location.
            if (in_array($location, $menu->menudata->location)) {
                $result[] = $menu;
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
            $record->description = [
                'text'   => $record->description,
                'format' => $record->description_format
            ];
            // Decode the multiple option select elements values to array.
            $record->location = json_decode($record->location);
            $record->roles = json_decode($record->roles);
            $record->cohorts = json_decode($record->cohorts);
            $record->languages = json_decode($record->languages);
            return $record;
        } else {
            // TODO: string for menu not found.
            throw new moodle_exception('menunotfound', 'theme_boost_union');
        }
        return false;
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
        $locations = array(
            self::LOCATION_MAIN => get_string('smartmenu:location:main', 'theme_boost_union'),
            self::LOCATION_MENU => get_string('menu', 'core'),
            self::LOCATION_USER => get_string('user', 'core'),
            self::LOCATION_BOTTOM => get_string('smartmenu:location:bottom', 'theme_boost_union')
        );

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
        $types = array(
            self::TYPE_LIST => get_string('smartmenu:types:list', 'theme_boost_union'),
            self::TYPE_CARD => get_string('smartmenu:types:card', 'theme_boost_union')
        );

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
     * Insert or update the menu instance to DB. Convert the multiple options select elements to json.
     * setup menu order after insert.
     *
     * Delete the current menu cache after updated the menu.
     *
     * @param stdclass $formdata
     * @return bool
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

        $transaction = $DB->start_delegated_transaction();
        // Smart menus cache instance.
        $cache = cache::make('theme_boost_union', 'smartmenus');

        if (isset($formdata->id) && $DB->record_exists('theme_boost_union_menus', ['id' => $formdata->id])) {
            $menuid = $formdata->id;

            $DB->update_record('theme_boost_union_menus', $record);
            // Clear the current menu caches. Update may cause changes in the menus list.
            $cache->delete($menuid);

            // Show the edited success notification.
            \core\notification::success(get_string('smartmenu:updatesuccess', 'theme_boost_union'));
        } else {
            // Setup the menu order.
            $lastmenu = self::get_lastmenu();
            $record->sortorder = isset($lastmenu->sortorder) ? $lastmenu->sortorder + 1 : 1;
            $menuid = $DB->insert_record('theme_boost_union_menus', $record);

            \cache_helper::purge_by_event('theme_boost_union_menus_created');
            // Show the menu inserted success notification.
            \core\notification::success(get_string('smartmenu:insertsuccess', 'theme_boost_union'));
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
        $nodes = [];
        // Get top level menus.
        $topmenus = self::get_menus();
        foreach ($topmenus as $menu) {
            if ($node = self::instance($menu->id)->build()) {
                $nodes[] = $node;
            }
        }
        return $nodes;
    }
}
