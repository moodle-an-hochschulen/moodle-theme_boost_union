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

use context_system;
use moodle_exception;
use core\navigation\views\primary;
use cache;
use theme_boost_union\smartmenu\provider_registry;

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
     * Constants that define the built-in locations where the menu can appear.
     * The values are the keys of the location providers (see \theme_boost_union\smartmenu\location\base).
     * Further locations can be added by other plugins, see \theme_boost_union\smartmenu\provider_registry.
     * Displayed in Main menu.
     *
     * @var string
     */
    public const LOCATION_MAIN = 'main';

    /**
     * Displayed in Menu bar. Top of the main menu.
     *
     * @var string
     */
    public const LOCATION_MENU = 'menu';

    /**
     * Display in Usermenu.
     *
     * @var string
     */
    public const LOCATION_USER = 'user';

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
     * Constants for the built-in presentations of the smart menu.
     * The values are the keys of the presentation providers (see \theme_boost_union\smartmenu\presentation\base).
     * Further presentations can be added by other plugins, see \theme_boost_union\smartmenu\provider_registry.
     * Displays menu items in a list format.
     *
     * @var string
     */
    public const PRESENTATION_LIST = 'list';

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
     * Cache key for whether starred-courses client-side cache invalidation JS is required.
     */
    public const CACHE_STARREDCACHEINVALIDATION = 'starredcacheinvalidation';

    /**
     * Cache key for the fingerprint of the smart menu providers for which the caches have been built.
     */
    public const CACHE_PROVIDERFINGERPRINT = 'providerfingerprint';

    /**
     * Cache key for the record of the recent purges of the caches because of a changed fingerprint
     * of the smart menu providers.
     */
    public const CACHE_PROVIDERFINGERPRINTPURGES = 'providerfingerprintpurges';

    /**
     * The interval (in seconds) within which the purges of the caches because of a changed fingerprint
     * of the smart menu providers are limited.
     */
    public const PROVIDERFINGERPRINT_PURGEINTERVAL = 60;

    /**
     * The maximum number of purges of the caches because of a changed fingerprint of the smart menu providers
     * within the interval.
     */
    public const PROVIDERFINGERPRINT_MAXPURGES = 5;

    /**
     * The fields of a menu and of a menu item which hold the settings of the location and presentation providers.
     */
    public const PROVIDERDATA_FIELDS = ['locationdata', 'presentationdata'];

    /**
     * Invalidate shared smartmenus cache keys.
     *
     * @param \cache $cache
     * @return void
     */
    public static function invalidate_shared_cache(cache $cache): void {
        $cache->delete(self::CACHE_MENUSLIST);
        $cache->delete(self::CACHE_STARREDCACHEINVALIDATION);
    }

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

        // Remember the IDs of the items of this menu to be able to inform the providers afterwards.
        $itemids = $DB->get_fieldset_select('theme_boost_union_menuitems', 'id', 'menu = :menu', ['menu' => $this->id]);

        if ($DB->delete_records('theme_boost_union_menus', ['id' => $this->id])) {
            // Delete all its items.
            $DB->delete_records('theme_boost_union_menuitems', ['menu' => $this->id]);
            // Purge the menus cache.
            $this->cache->delete_menu($this->id);
            // Delete shared smartmenus cache entries.
            self::invalidate_shared_cache($this->cache);

            // Inform all providers (and not only the ones which are currently used by the menu, as the menu may
            // still hold data of providers which have been used before).
            foreach (provider_registry::get_all_providers() as $provider) {
                foreach ($itemids as $itemid) {
                    $provider->item_deleted((int) $itemid);
                }
                $provider->menu_deleted((int) $this->id);
            }

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
            // Purge shared smartmenus cache entries, need to recreate in new order.
            self::invalidate_shared_cache($this->cache);

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
            // Purge shared smartmenus cache entries, need to recreate in new order.
            self::invalidate_shared_cache($this->cache);

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
        // The provider data of the menu is copied as it is.
        $duplicateid = self::manage_instance($record);

        // Inform the providers that the menu has been duplicated.
        foreach (provider_registry::get_all_providers() as $provider) {
            $provider->menu_duplicated((int) $this->id, (int) $duplicateid);
        }

        if (!empty($items)) {
            // Duplicate the items related to menu.
            foreach ($items as $item) {
                $olditemid = $item->id;
                $item = \theme_boost_union\smartmenu_item::instance($item->id)->item;
                $item->id = 0;
                // New menu id as menu for duplicate item.
                $item->menu = $duplicateid;
                // Create the item.
                $newitemid = \theme_boost_union\smartmenu_item::manage_instance($item);

                // Inform the providers that the item has been duplicated.
                foreach (provider_registry::get_all_providers() as $provider) {
                    $provider->item_duplicated((int) $olditemid, (int) $newitemid);
                }
            }
        }
        // Success message for duplicated menu.
        \core\notification::success(get_string('smartmenusmenuduplicatesuccess', 'theme_boost_union'));

        // New menu added, recreate shared smartmenus cache entries.
        self::invalidate_shared_cache($this->cache);

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
        // Purge shared smartmenus cache entries, recreates them on next page load.
        self::invalidate_shared_cache($this->cache);

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
     * It also sets up some additional properties like itemtype, submenuid, forceintomoremenu,
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
    public function build($resetcache = false) {
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

            // Add CSS classes for more behaviour.
            $this->menu->classes[] = ($this->menu->moremenubehavior == self::MOREMENU_OUTSIDE) ? "force-menu-out" : '';

            // Get the presentation of the menu.
            $presentation = provider_registry::get_menu_presentation($this->menu);

            // Mode is submenu or not set anything then create the menuitems as submenu.
            // Otherwise add the menu items directoly as menu.
            // Presentations which do not support the inline mode are always shown as submenu.
            if ($this->menu->mode != self::MODE_INLINE || !$presentation->menu_supports_inline_mode()) {
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

                // Let the presentation of the menu decorate the menu node.
                $presentation->decorate_menu_node(
                    $nodes,
                    $this->menu,
                    $presentation->get_data($this->menu)
                );
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
                    $itemcachekey = "{$item->id}_u_{$USER->id}";
                    $itemcache->delete($itemcachekey);
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

                // Set the visibility of the menu node for mobile, tablet, and desktop based on its child items.
                foreach ($builditems as $key => $item) {
                    // If any of the items are visible on the desktop, the menu node should also be visible on the desktop.
                    if (!isset($item['desktop']) || empty($item['desktop'])) {
                        $hidemenudesktop = 0;
                    }
                    // If any of the items are visible on the tablet, the menu node should also be visible on the tablet.
                    if (!isset($item['tablet']) || empty($item['tablet'])) {
                        $hidemenutablet = 0;
                    }
                    // If any of the items are visible on the mobile, the menu node should also be visible on the mobile.
                    if (!isset($item['mobile']) || empty($item['mobile'])) {
                        $hidemenumobile = 0;
                    }
                }

                // Include the menu node visibility based on its child items.
                $nodes->desktop = $hidemenudesktop ?? true;
                $nodes->tablet = $hidemenutablet ?? true;
                $nodes->mobile = $hidemenumobile ?? true;
            } else {
                // If menu is inline mode, then it items are displayed directly in menus.
                // Set the menuitems as separate menu node in cache.
                // Remove dividers from inline menus.
                $builditems = array_filter($builditems, function ($item) {
                    // Remove the item is divider.
                    return !isset($item['divider']) || !$item['divider'];
                });

                array_walk($builditems, function (&$item) {
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
     * The given list contains the built nodes of all smart menus which are visible to the current user, regardless of
     * their locations. A node is either the node of a menu in submenu mode (an object which carries the menu record in
     * 'menudata') or the node of a menu item of a menu in inline mode (an array which carries the item record in
     * 'itemdata'). This function picks the nodes whose menu is assigned to the given location.
     *
     * @param string $location The key of the location to retrieve menus for.
     * @param array $menus The list of all built smart menu nodes.
     * @return array An array of nodes (as objects) whose menus are assigned to the specified location.
     */
    public static function get_menus_forlocation($location, $menus) {

        // If there are not any nodes or if no location is given, there is nothing to pick.
        if (empty($menus) || $location == '') {
            return [];
        }

        // Get the cached list of all menus to look up the locations of the menus in inline mode.
        // The locations which are contained in the item nodes themselves are not reliable: The item nodes are cached
        // and would have to be purged for all items of a menu as soon as the locations of the menu are changed.
        // Instead of purging the item caches, the locations are taken from the cached menus list which is always up-to-date.
        $topmenus = smartmenu_helper::get_menu_cache()->get(self::CACHE_MENUSLIST);

        // Iterate over the nodes.
        foreach ($menus as $menu) {
            // Start without any location for each node.
            // This makes sure that a node whose locations cannot be determined never inherits the locations
            // of the previous node.
            $menulocation = [];

            // Handle each node as object, regardless of whether it is a menu node (object) or a menu item node (array).
            $menu = (object) $menu;

            // If the node is the node of a menu in submenu mode, get the locations from the menu record.
            if (isset($menu->menudata->location)) {
                $menulocation = $menu->menudata->location;

                // Otherwise, if the node is the node of a menu item of a menu in inline mode,
                // get the locations of its menu from the cached menus list.
            } else if (isset($menu->itemdata->menu) && isset($topmenus[$menu->itemdata->menu])) {
                $menulocation = json_decode($topmenus[$menu->itemdata->menu]->location);
            }

            // If the locations are still given as JSON string (as they are stored in the database), decode them.
            if (isset($menulocation) && is_scalar($menulocation)) {
                $menulocation = json_decode($menulocation);
            }

            // If the menu is not assigned to any location, skip the node.
            if (!isset($menulocation) || empty($menulocation)) {
                continue;
            }

            // If the menu is assigned to the given location, pick the node for this location.
            // The location keys are compared as strings to be safe against differently typed values.
            if (in_array((string) $location, array_map('strval', (array) $menulocation), true)) {
                $result[] = $menu;
            }
        }

        // Return the picked nodes (or an empty array if no menu is assigned to the location).
        return $result ?? [];
    }

    /**
     * Groups the given smart menu nodes by the locations to which their menus are assigned.
     * Only the locations which are provided by a location provider are considered, locations which do not exist
     * (anymore) are ignored.
     *
     * Menus in inline mode are not passed to locations which do not support the inline mode. The smart menu edit form
     * prevents this combination already, but it can still exist (for example as a location provider changed its
     * inline mode support after the menu has been saved or as the menu has been created with a generator).
     * In this case, the menu is not shown at this location, but it is still shown at its other locations.
     *
     * @param array $menus The list of all built smart menu nodes.
     * @return array The nodes, keyed by the location keys. Locations without nodes are not contained.
     */
    public static function group_nodes_by_location($menus): array {
        $grouped = [];

        // Iterate over the locations which are provided by a location provider.
        // Nodes of menus which are assigned to a location which does not exist (anymore) are not picked up this way.
        foreach (provider_registry::get_locations() as $key => $location) {
            // Pick the nodes whose menus are assigned to this location.
            $nodes = self::get_menus_forlocation($key, $menus);

            // If the location does not support the inline mode, remove the nodes of the menus in inline mode.
            if (!$location->menu_supports_inline_mode()) {
                $supportednodes = [];
                $skippedmenus = [];

                // Iterate over the nodes of this location.
                foreach ($nodes as $node) {
                    // The node of a menu in submenu mode carries the menu record in 'menudata', it is kept.
                    if (isset($node->menudata)) {
                        $supportednodes[] = $node;

                        // The nodes of a menu in inline mode are the nodes of its menu items, they do not have
                        // 'menudata', but 'itemdata'. They are dropped and their menu is remembered to be able to
                        // notify the developer once per menu (and not once per menu item).
                    } else if (isset($node->itemdata->menu)) {
                        $skippedmenus[$node->itemdata->menu] = true;
                    }
                }

                // Notify the developer about each menu which is not shown at this location.
                foreach (array_keys($skippedmenus) as $menuid) {
                    debugging('The smart menu with the ID ' . $menuid . ' is in inline mode and is not shown at the ' .
                            'location \'' . $key . '\' as this location does not support the inline mode.', DEBUG_DEVELOPER);
                }

                // Continue with the remaining nodes only.
                $nodes = $supportednodes;
            }

            // Add the nodes to the result.
            // Locations without nodes are left out so that the caller only gets the locations which have to be rendered.
            if (!empty($nodes)) {
                $grouped[$key] = $nodes;
            }
        }

        return $grouped;
    }

    /**
     * Decode the provider data (i.e. the content of the locationdata or presentationdata field) of a menu or a menu item.
     *
     * The provider data reaches this function in different shapes, depending on where the record comes from:
     * As JSON string (directly from the database), as array (if it has been decoded before, e.g. by get_menu())
     * or as object (e.g. if it comes from a generator or has been cast together with its record).
     * This function always returns an array, regardless of the given shape, so that the callers do not have to care.
     *
     * @param mixed $providerdata The provider data as stored in the database (JSON) or already decoded.
     * @return array The provider data, keyed by the keys of the providers
     *               (e.g. ['card' => ['cardsize' => '...']]). An empty array if there is no (valid) provider data.
     */
    public static function decode_providerdata($providerdata): array {
        // If the provider data is an array, it has been decoded already and is returned as it is.
        if (is_array($providerdata)) {
            return $providerdata;
        }

        // If the provider data is an object, convert it to an array recursively
        // (the nested settings of the providers have to become arrays as well, a simple cast would not do that).
        if (is_object($providerdata)) {
            return json_decode(json_encode($providerdata), true) ?: [];
        }

        // If there is no provider data at all (null or an empty string), return an empty array.
        if (empty($providerdata)) {
            return [];
        }

        // Otherwise, the provider data is a JSON string which is decoded.
        // If the string is not valid JSON or does not contain a structure, an empty array is returned as well.
        $decoded = json_decode($providerdata, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Add the stored settings of the providers to a menu record for editing it with the smart menu edit form.
     *
     * The settings are stored per provider in the locationdata and presentationdata fields of the menu
     * (e.g. {"card": {"cardsize": "..."}}), whereas the form expects one value per form element
     * (e.g. prescard_cardsize). This function converts the former to the latter.
     * It is the counterpart of the processing of the provider form elements in manage_instance().
     *
     * The settings of all providers are added (and not only the ones which are currently used by the menu),
     * as the admin may switch the presentation or the locations in the form.
     *
     * @param \stdClass|null $record The menu record as returned by get_menu() or null if a new menu is created.
     * @return \stdClass The data for the form.
     */
    public static function add_provider_form_data(?\stdClass $record = null): \stdClass {
        // If a new menu is created, start with an empty menu record.
        // The providers are called nevertheless as they may have to prepare form elements of new menus as well
        // (e.g. the draft area of a file manager).
        $record = $record ?? (object) ['id' => 0];

        // Iterate over all location providers and all presentation providers.
        foreach (provider_registry::get_all_providers() as $provider) {
            // Let the provider prepare its stored settings for the form.
            // It gets its own part of the provider data and returns the values keyed by its (prefixed) element names.
            $providerformdata = $provider->menu_form_prepare_data($record, $provider->get_data($record));

            // Add the values to the record which is passed to the form.
            foreach ($providerformdata as $name => $value) {
                $record->{$name} = $value;
            }
        }

        return $record;
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

        // Decode the settings of the location and presentation providers.
        foreach (self::PROVIDERDATA_FIELDS as $datafield) {
            $menu->{$datafield} = self::decode_providerdata($menu->{$datafield} ?? null);
        }

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
     * @param bool $processproviders Whether the data comes from the smart menu edit form and the location and
     *                               presentation providers of the menu have to process their form elements.
     *                               Otherwise, the provider data is taken as it is from $formdata->locationdata and
     *                               $formdata->presentationdata (if given).
     * @return int The menu ID.
     */
    public static function manage_instance($formdata, bool $processproviders = false) {
        global $DB;

        $record = $formdata;

        // The presentation falls back to the list presentation if it is not given.
        $record->presentation = $formdata->presentation ?? self::PRESENTATION_LIST;

        foreach (self::PROVIDERDATA_FIELDS as $datafield) {
            if (!property_exists($formdata, $datafield)) {
                continue;
            }

            // If the data does not come from the smart menu edit form (e.g. when a menu is duplicated), the provider data
            // is given directly as decoded array and is encoded here for storing it.
            // If it is not given at all, the provider data which is stored for an existing menu is not touched.
            if (!$processproviders) {
                $record->{$datafield} = json_encode((object) self::decode_providerdata($formdata->{$datafield}));

                // Otherwise, the provider data is built from the form elements of the providers below.
                // The form does not submit the provider data fields themselves, but remove them to be sure that
                // they are never written unencoded.
            } else {
                unset($record->{$datafield});
            }
        }

        $record->description_format = $formdata->description['format'];
        $record->description = $formdata->description['text'];

        // Encode the multiple value elements into json to store.
        $record->location = json_encode(array_values(array_map('strval', (array) $formdata->location)));
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

            // Delete the item caches of the items of this menu for all users as well.
            // The item nodes are built depending on the settings of the menu (e.g. they are decorated by the
            // presentation of the menu and depend on the mode of the menu), so they have to be rebuilt as well.
            $itemcache = cache::make('theme_boost_union', 'smartmenu_items');
            $itemids = $DB->get_fieldset_select('theme_boost_union_menuitems', 'id', 'menu = :menu', ['menu' => $menuid]);
            foreach ($itemids as $itemid) {
                $itemcache->delete_menu($itemid);
            }

            // Menu updated, recreate shared smartmenus cache entries.
            self::invalidate_shared_cache($cache);

            // Show the edited success notification.
            \core\notification::success(get_string('smartmenusmenueditsuccess', 'theme_boost_union'));
        } else {
            // Setup the menu order.
            $lastmenu = self::get_lastmenu();
            $record->sortorder = isset($lastmenu->sortorder) ? $lastmenu->sortorder + 1 : 1;
            $menuid = $DB->insert_record('theme_boost_union_menus', $record);

            // New menu added, recreate shared smartmenus cache entries.
            self::invalidate_shared_cache($cache);

            // Show the menu inserted success notification.
            \core\notification::success(get_string('smartmenusmenucreatesuccess', 'theme_boost_union'));
        }

        // Let the providers which are used by the menu process their form elements and store their settings.
        // The settings of providers which are not used by the menu (anymore) are kept, so that they are not lost if the
        // admin switches back to them later.
        if ($processproviders) {
            // Get the provider data which is currently stored for the menu.
            $stored = $DB->get_record(
                'theme_boost_union_menus',
                ['id' => $menuid],
                'id, ' . implode(', ', self::PROVIDERDATA_FIELDS),
                MUST_EXIST
            );

            // Decode the stored provider data of both data fields.
            $providerdata = [];
            foreach (self::PROVIDERDATA_FIELDS as $datafield) {
                $providerdata[$datafield] = self::decode_providerdata($stored->{$datafield});
            }

            // Let each provider process its form elements and replace its part of the provider data with the result.
            foreach (provider_registry::get_menu_providers($record) as $provider) {
                $providerdata[$provider->get_data_field()][$provider->get_key()] =
                    $provider->menu_form_process_data($formdata, (int) $menuid);
            }

            // Encode the provider data of both data fields again.
            foreach (self::PROVIDERDATA_FIELDS as $datafield) {
                $stored->{$datafield} = json_encode((object) $providerdata[$datafield]);
            }

            // Store the provider data of the menu.
            $DB->update_record('theme_boost_union_menus', $stored);
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
        global $SESSION, $USER;

        $nodes = [];

        // Detect language changes in user session - if changed then purge the menus and items cache for the user session.
        self::detect_lang_session_change();

        $cache = cache::make('theme_boost_union', 'smartmenus');

        // The built menus and menu items are decorated by the presentation providers. If the set of providers has
        // changed (e.g. as a plugin which ships a provider has been installed or uninstalled), the caches are outdated.
        // Please note: The number of purges for this reason is limited within a short interval. Otherwise, web servers
        // which do not agree on the set of providers for a while (e.g. during a rolling deployment on a cluster) would
        // purge the caches of the whole site alternately with nearly every request. As soon as the limit is reached,
        // such a web server shows the menus as they have been built by the other web servers until the interval is over.
        $fingerprint = provider_registry::get_fingerprint();
        if ($cache->get(self::CACHE_PROVIDERFINGERPRINT) !== $fingerprint) {
            // Get the record of the recent purges and start a new interval if the last one is over.
            $now = time();
            $purges = $cache->get(self::CACHE_PROVIDERFINGERPRINTPURGES);
            if (!is_array($purges) || $now - $purges['start'] >= self::PROVIDERFINGERPRINT_PURGEINTERVAL) {
                $purges = ['start' => $now, 'count' => 0];
            }

            // Purge the caches if the limit has not been reached yet.
            if ($purges['count'] < self::PROVIDERFINGERPRINT_MAXPURGES) {
                $cache->purge();
                cache::make('theme_boost_union', 'smartmenu_items')->purge();
                $cache->set(self::CACHE_PROVIDERFINGERPRINT, $fingerprint);
                $purges['count']++;
                $cache->set(self::CACHE_PROVIDERFINGERPRINTPURGES, $purges);
            }
        }

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
        // If the user is a guest.
        if (!isloggedin() || isguestuser()) {
            if (
                isset($SESSION->theme_boost_union_menu_purgesessioncache) &&
                    $SESSION->theme_boost_union_menu_purgesessioncache == true
            ) {
                $removecache = true;
            } else {
                $removecache = false;
            }

            // Otherwise.
        } else {
            $removecache = (get_user_preferences('theme_boost_union_menu_purgesessioncache', false) == true);
        }

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
     * Clears cached smart menus and items especially when the user changes the language using the language menu or if the language
     * gets changed by a forced language in a course.
     *
     * @return void
     */
    protected static function detect_lang_session_change() {
        global $SESSION, $USER;

        // Get the current language.
        $lang = current_language();

        // If the language does not match the language of the previous smart menu build or if we did not have a previous build yet.
        if (!isset($SESSION->theme_boost_union_prevlang) || $SESSION->theme_boost_union_prevlang != $lang) {
            // Set the purge cache preference for this session user. Cache will purged in the build_smartmenu method.
            smartmenu_helper::set_user_purgecache($USER->id);

            // And save this language for verification in the next build.
            $SESSION->theme_boost_union_prevlang = $lang;
        }
    }
}
