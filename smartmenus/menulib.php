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
 * Theme Boost Union - Smart menu Helper.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

defined('MOODLE_INTERNAL') || die();

use cache;
use context_system;

require_once($CFG->dirroot. '/theme/boost_union/locallib.php');

/**
 * Smartmenu helper which contains the methods to verify the access rules for menu and its items.
 *
 * It contains the method to purge caches of menu and items for different events.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class smartmenu_helper {

    /**
     * Data record of the item or menu.
     *
     * @var stdclass
     */
    public $data;

    /**
     * Current loggedin user id.
     *
     * @var int
     */
    public $userid;

    /**
     * Smartmenu helper - Constructor.
     *
     * This class mainly verifies the access rules of the menu or item are passed for the current user.
     * Use the item DB record data if want to verify for item, use menu data if want to verify menu.
     *
     * @param stdclass $data the item DB record data if want to verify for item, use menu data if want to verify menu.
     */
    public function __construct($data) {
        global $USER;

        $this->data = $data;
        // Verify for the current user.
        $this->userid = $USER->id;
    }

    /**
     * Find the user visibility access.
     *
     * Genreate and join the multiple restrictions conditions based Queries and fetch the records using the query,
     * User will have access if records found otherwise user will restricted.
     *
     * @return bool Returns true if access rules are passed, otherfise its false.
     */
    public function verify_access_restrictions() {
        global $DB;

        if (!$this->data->visible) {
            return false;
        }

        $query = (object) [
            'select' => ['u.*'],
            'join' => [],
            'where' => [],
            'params' => ['userid' => $this->userid],
        ];

        // Restriction by roles.
        $this->restriction_byroles($query);

        // Restricted by site admin status.
        if (!$this->restriction_byadmin()) {
            return false;
        }

        // Restriction by cohorts.
        $this->restriction_bycohorts($query);

        // Restriction by languages.
        if (!$this->restriction_bylanguage()) {
            return false;
        }

        // Restriction by date. Menu configured date is not started or already ended then hide the menu.
        if (!$this->restriction_bydate()) {
            return false;
        }

        // Join the query object.
        $select = implode(',', array_filter($query->select));
        $join = implode('', array_filter($query->join));
        $where = implode(' AND ', array_filter($query->where));
        $params = array_merge($query->params);

        // Show the menus and item, if it doesn't contain any restrictions.
        if (empty($where)) {
            return true;
        }

        // Build the query from query object from different restrictions.
        $sql = " SELECT $select FROM {user} u $join";
        $sql .= $where ? " WHERE u.id=:userid AND $where " : ' WHERE u.id=:userid ';

        $records = $DB->get_records_sql($sql, $params);
        // Records found user will have access otherwise restrict the user to view the menu or menu item.
        return count($records) > 0 ? true : false;
    }

    /**
     * Generate the queries to verify the user has selected role.
     *
     * @param stdclass $query Array which contains elements for DB conditions, selectors and params.
     * @return void
     */
    public function restriction_byroles(&$query) {
        global $DB, $CFG;

        $roles = $this->data->roles;
        // If no role restrictions are set.
        if ($roles == '' || empty($roles)) {
            // Return directly.
            return true;
        }

        // If the user is logged in and the default user role is allowed to view the menu.
        $defaultuserroleid = isset($CFG->defaultuserroleid) ? $CFG->defaultuserroleid : 0;
        if ($defaultuserroleid && in_array($defaultuserroleid, $roles) && !empty($this->userid) && !isguestuser($this->userid)) {
            // Return directly.
            return true;
        }

        // If the user is a guest and the guest role is allowed to view the menu.
        $guestroleid = isset($CFG->guestroleid) ? $CFG->guestroleid : 0;
        if ($guestroleid && in_array($guestroleid, $roles) && isguestuser()) {
            // Return directly.
            return true;
        }

        // If the user is a visitor and the visitor role is allowed to view the menu.
        $visitorroleid = isset($CFG->notloggedinroleid) ? $CFG->notloggedinroleid : 0;
        if ($visitorroleid && in_array($visitorroleid, $roles) && !isloggedin() && !isguestuser()) {
            // Return directly.
            return true;
        }

        list($insql, $inparam) = $DB->get_in_or_equal($roles, SQL_PARAMS_NAMED, 'rl');

        $contextsql = ($this->data->rolecontext == smartmenu::SYSTEMCONTEXT)
            ? ' AND contextid=:systemcontext ' : '';

        $query->where[] = " u.id IN (SELECT userid FROM {role_assignments} WHERE roleid $insql AND userid=:rluserid $contextsql)";
        $params = [
            'rluserid' => $this->userid,
            'systemcontext' => context_system::instance()->id,
        ];
        $query->params += array_merge($params, $inparam);
    }

    /**
     * Verify if the menu is restricted to site admins.
     *
     * @return bool True if the menu is available for this user, otherwise false.
     */
    public function restriction_byadmin() {
        // If the item is restricted to site admins only.
        if ($this->data->byadmin == smartmenu::BYADMIN_ADMINS) {
            return is_siteadmin($this->userid);

            // Otherwise, if the item is restricted to non-site admins only.
        } else if ($this->data->byadmin == smartmenu::BYADMIN_NONADMINS) {
            return !is_siteadmin($this->userid);
        }

        // Allow the item to be viewed by the user.
        return true;
    }

    /**
     * The purpose of this function is to check if a user is assigned to one or more cohorts that are specified in a menu.
     * For the operator "ALL" it gets the count of records and verfiy the records count is same as count of selected cohorts.
     *
     * @param stdclass $query Array which contains elements for DB conditions, selectors and params.
     * @return void
     */
    public function restriction_bycohorts(&$query) {
        global $DB;

        $cohorts = $this->data->cohorts;

        if ($cohorts == '' || empty($cohorts)) {
            return true;
        }
        // Build insql to confirm the user cohort is available in the configured cohort.
        list($insql, $inparam) = $DB->get_in_or_equal($cohorts, SQL_PARAMS_NAMED, 'ch');

        // If operator is all then check the count of user assigned cohorts,
        // Confirm the count is same as configured menu/items cohorts count.
        $condition = ($this->data->operator == smartmenu::ALL) ? " GROUP BY cm.userid HAVING COUNT(DISTINCT c.id) = :chcount" : '';

        $sql = " JOIN (SELECT count(*) AS member FROM {cohort_members} cm
            JOIN {cohort} c ON cm.cohortid = c.id
            WHERE c.id $insql AND cm.userid=:chuserid $condition) ch ON true";

        $params = ['chuserid' => $this->userid, 'chcount' => count($cohorts)] + $inparam;
        $query->params += $params;
        $query->join[] = $sql;
        $query->where[] = ' ch.member <> 0 ';
    }

    /**
     * Verify the menu has restricted based on the current language.
     *
     * @return bool True if the menu is available for this lanauage, otherwise false.
     */
    public function restriction_bylanguage() {
        global $DB;

        $languages = $this->data->languages;
        if (empty($languages)) {
            return true;
        }
        // Current language selected for this session.
        $lang = current_language();

        return in_array($lang, $languages); // This item is available for the current language.
    }

    /**
     * It checks the current item or menu data contained access rules based on start or end date.
     *
     * Start date is configured and the date is reached user has access otherwise it hide the node.
     * Or the end date is cconfigured and the date is passed it will hide the node from menu.
     *
     * @return bool True if the date is reached and not passed if configured, otherwise it false.
     */
    public function restriction_bydate() {

        $startdate = $this->data->start_date;
        $enddate = $this->data->end_date;
        // Check any of the start date or end date is configured.
        if (empty($startdate) && empty($enddate)) {
            return true;
        }

        $date = new \DateTime("now", \core_date::get_user_timezone_object());
        $today = $date->getTimestamp();

        // Verify the started date is reached.
        if (!empty($startdate) && $startdate > $today) {
            return false;
        }

        // If the menu startdate reached or no start date, then check the enddate is reached.
        if (!empty($enddate) && $enddate < $today) {
            return false;
        }
        // Menu is configured between the start and end date.
        return true;
    }

    /**
     * Fetch the list of menus which is used the triggered ID in the access rules for the given method.
     *
     * Find the menus which contains the given ID in the access rule (Role or cohorts).
     *
     * @param int $id ID of the triggered method, Role or cohort id.
     * @param string $method Field to find, Role or Cohort.
     * @return array
     */
    public static function find_condition_used_menus($id, $method='cohorts') {
        global $DB;

        $like = $DB->sql_like($method, ':value');
        $sql = "SELECT * FROM {theme_boost_union_menus} WHERE $like";
        $params = ['value' => '%"'.$id.'"%'];

        $records = $DB->get_records_sql($sql, $params);

        return $records;
    }

    /**
     * Fetch the list of items which is used the triggered ID in the access rules for the given method.
     *
     * Find the items which contains the given ID in the access rule (Role or cohorts).
     *
     * @param int $id ID of the triggered method, Role or cohort id.
     * @param string $method Field to find, Role or Cohort.
     * @return array
     */
    public static function find_condition_used_menuitems($id, $method='cohorts') {
        global $DB;

        $like = $DB->sql_like($method, ':value');
        $sql = "SELECT * FROM {theme_boost_union_menuitems} WHERE $like";
        $params = ['value' => '%"'.$id.'"%'];

        $records = $DB->get_records_sql($sql, $params);
        return $records;
    }

    /**
     * Generate the cache helper for smart menu.
     *
     * @return cache
     */
    public static function get_menu_cache() {
        static $cache;

        if (empty($cache)) {
            $cache = cache::make('theme_boost_union', 'smartmenus');
        }

        return $cache;
    }

    /**
     * Generate the cache helper for smart menu item.
     *
     * @return cache
     */
    public static function get_item_cache() {
        static $cache;

        if (empty($cache)) {
            $cache = cache::make('theme_boost_union', 'smartmenu_items');
        }

        return $cache;
    }


    /**
     * Purge the cache for menu and menu items when the cohort is deleted.
     *
     * It verify any of the menus are used the cohort in the access rules. if records found it will purge the cache of the menus.
     * It run the same verification and purge cache for items. Then it remove the cohort from rules.
     *
     * @param int $cohortid Deleted cohort id.
     * @return void
     */
    public static function purge_cache_deleted_cohort($cohortid) {

        $records = self::find_condition_used_menus($cohortid);

        if (!empty($records)) {
            // Remove the deleted cohort from rules if used in menus restriction.
            self::remove_deleted_condition_menu($records, $cohortid);
        }

        $records = self::find_condition_used_menuitems($cohortid);
        if (!empty($records)) {
            // Remove the deleted cohort from menu item rules if used in menuitems restriction.
            self::remove_deleted_condition_menuitems($records, $cohortid);
        }
    }

    /**
     * Clear the smartmenu and menu items stored cache for the menus which is used the given role in restriction condition.
     * Remove the deleted role from menu restrictions.
     *
     * @param [type] $roleid
     * @return void
     */
    public static function purge_cache_deleted_roles($roleid) {

        $records = self::find_condition_used_menus($roleid, 'roles');

        if (!empty($records)) {
            // Remove the deleted role from menu restrictions.
            self::remove_deleted_condition_menu($records, $roleid, 'roles');
        }

        $records = self::find_condition_used_menuitems($roleid, 'roles');
        if (!empty($records)) {
            // Remove the deleted role from menu item restrictions.
            self::remove_deleted_condition_menuitems($records, $roleid, 'roles');
        }

    }

    /**
     * Remove the deleted conditions from menu data.
     * If the role or cohort is deleted this method will remove the role from the access rules if setup in the menus.
     *
     * Get the menus which is used the deleted role or cohort in access rules,
     * then remove the id from that method and set the updated data for the method related field.
     *
     * @param stclass $menus List of menus need to purge from cache.
     * @param int $id ID of the deleted role or cohort.
     * @param string $method Role or cohort which is triggered the purge.
     * @return void
     */
    public static function remove_deleted_condition_menu($menus, $id, $method='cohorts') {
        global $DB;

        if ($menus) {
            foreach ($menus as $menu) {
                if (isset($menu->$method)) {
                    $value = json_decode($menu->$method);
                    if (($key = array_search($id, $value)) !== false) {
                        unset($value[$key]);
                        $updated = json_encode(array_values($value));
                        $DB->set_field('theme_boost_union_menus', $method, $updated, ['id' => $menu->id]);

                        // Purge the cache of this menu.
                        self::purge_menu_cache($menu->id);
                    }
                }
            }
        }
    }

    /**
     * Remove the deleted conditions from menu items data.
     * If the role or cohort is deleted this method will remove the role from the access rules if setup in the menu items.
     *
     * Get the items which is used the deleted role or cohort in access rules,
     * then remove the id from that method and set the updated data for the method related field.
     *
     * @param stclass $menuitems List of menuitems need to purge from cache
     * @param int $id ID of the deleted role or cohort.
     * @param string $method Role or cohort which is triggered the purge.
     * @return void
     */
    public static function remove_deleted_condition_menuitems($menuitems, $id, $method='cohorts') {
        global $DB;

        if ($menuitems) {
            foreach ($menuitems as $item) {
                if (isset($item->$method)) {
                    $value = json_decode($item->$method);
                    if (($key = array_search($id, $value)) !== false) {
                        unset($value[$key]);
                        $updated = json_encode(array_values($value));
                        $DB->set_field('theme_boost_union_menuitems', $method, $updated, ['id' => $item->id]);
                        // Purge the cache of this item and its menu.
                        self::purge_menu_cache($item->menu);
                        self::purge_item_cache($item->id);
                    }
                }
            }
        }
    }

    /**
     * Purge the cache for menu and menu items when the user is assigned or removed from the cohort.
     *
     * It sets the user preferences to trigger the cache purging when the menu is fetched for the user.
     *
     * The actual cache purging is performed in build method in smartmenu
     * that checks the user preferences and purges the cache accordingly.
     *
     * @param int $cohortid Affected cohort id.
     * @param int $userid Affected user id.
     *
     * @return void
     */
    public static function purge_cache_session_cohort(int $cohortid, int $userid) {

        if ($menus = self::find_condition_used_menus($cohortid)) {
            // Remove the menus cache for the user.
            $menus = array_column($menus, 'id');
            array_walk($menus, [self::class, 'remove_user_cachemenu'], $userid);
        }

        if ($items = self::find_condition_used_menuitems($cohortid)) {
            // Get the list of menus related to the items.
            $menus = array_unique(array_column($items, 'menu'));
            $items = array_column($items, 'id');
            // Remove the menus and item cache for the user.
            array_walk($menus, [self::class, 'remove_user_cachemenu'], $userid);
            array_walk($items, [self::class, 'remove_user_cacheitem'], $userid);

        }
    }

    /**
     * Purge the given user cache of menu and items which are configured with the affected role.
     *
     * Find the list of menus and items configured with the given role and delete the menu from users cache.
     * Items are configured with this role, then method removes the items menu cache too.
     *
     * @param int $roleid Removed role id.
     * @param int $userid Releated user who is affected by this event.
     * @return void
     */
    public static function purge_cache_session_roles(int $roleid, int $userid) {

        if ($menus = self::find_condition_used_menus($roleid, 'roles')) {
            // Remove the menus cache for the user.
            $menus = array_column($menus, 'id');
            array_walk($menus, [self::class, 'remove_user_cachemenu'], $userid);
        }

        if ($items = self::find_condition_used_menuitems($roleid, 'roles')) {
            // Get the list of menus related to the items.
            $menus = array_unique(array_column($items, 'menu'));
            $items = array_column($items, 'id');
            // Remove the menus and item cache for the user.
            array_walk($menus, [self::class, 'remove_user_cachemenu'], $userid);
            array_walk($items, [self::class, 'remove_user_cacheitem'], $userid);
        }
    }

    /**
     * Purge the cache of dynamic course items.
     *
     * @return void
     */
    public static function purge_cache_dynamic_courseitems() {
        global $DB;
        // Fetch list of menuitems, configured with the event categoryid.
        if ($items = $DB->get_records('theme_boost_union_menuitems', ['type' => smartmenu_item::TYPEDYNAMIC])) {
            // List of items to purge.
            $items = array_column($items, 'id');
            // Remove the menus items for the user.
            array_walk($items, [self::class, 'purge_item_cache']);
        }
    }

    /**
     * Deletes the cached menu data for the particular user.
     * Fetch the cache instacne, genreate the key combine with menuid and userid, then delete the menu cahce.
     *
     * @param int $menuid ID of the menu.
     * @param int $key
     * @param int $userid ID of the user to purge menu cache.
     * @return void
     */
    public static function remove_user_cachemenu($menuid, $key, $userid) {
        // Fetch the cache helper for menu.
        $cache = self::get_menu_cache();
        // Create key to remove this menu cache for given user and delete.
        $cachekey = "{$menuid}_u_{$userid}";
        $cache->delete($cachekey);
    }

    /**
     * Deletes the cached menu item data for the particular user.
     * Fetch the cache instacne, genreate the key combine with itemid and userid, then delete the item from cache.
     *
     * @param int $itemid ID of the item.
     * @param int $key
     * @param int $userid ID of the user to purge menu cache.
     * @return void
     */
    public static function remove_user_cacheitem($itemid, $key, $userid) {
        // Fetch the cache helper for item.
        $cache = self::get_item_cache();
        // Create key to remove this items cache for given user and delete.
        $cachekey = "{$itemid}_u_{$userid}";
        $cache->delete($cachekey);
    }

    /**
     * Purges the cached menu data if the menu has a start or end date restriction, and that restriction has been reached or passed.
     *
     * If the menu has a start date and that date is earlier than the current date,
     * and the last check date is earlier than the startdate, then the cached menu data is cleared from the cache
     * and the last check date is updated to the current date.
     *
     * Similarly, if the menu has an end date and that date is earlier than the current date,
     * and the last check date is earlier than or equal to the end date,
     * then the cached menu data is cleared from the cache and the last check date is updated to the current date.
     *
     * @param \core_cache\store $cache The cache object.
     * @param object $data The menu data object.
     * @param string $key The cache key.
     *
     * @return void
     */
    public static function purge_cache_date_reached($cache, $data, $key) {

        // Check is the cached menu has setup date restriction, then check is the time reached or ended.
        $lastcheckdate = $cache->get($key);
        $date = new \DateTime("now", \core_date::get_user_timezone_object());
        $today = $date->getTimestamp();

        // Menu start date reached and last cache date is less than today, then clear the cache and set last check date.
        // Verify the lastcheckdate is prevents setup cache again even the cache was stored after the startdate reached.
        if ($data->start_date != '' && $data->start_date < $today && $lastcheckdate < $data->start_date) {
            $cache->delete_menu($data->id);
            $cache->set($key, $today);
        }

        // Menu end date is gone and last cache date is less than today, then clear the cache and set last check date.
        // Verify the lastcheckdate is prevents setup cache again even the cache was stored after the enddate reached.
        if ($data->end_date != '' && $data->end_date < $today && $lastcheckdate <= $data->end_date) {
            $cache->delete_menu($data->id);
            $cache->set($key, $today);
        }
    }

    /**
     * Remove the specific menu cache for all the users.
     *
     * @param int $menuid Menu ID to purge.
     * @return void
     */
    protected static function purge_menu_cache($menuid) {
        $cache = self::get_menu_cache();
        $cache->delete_menu($menuid);
    }

    /**
     * Remove the specific item cache for all the users.
     *
     * @param int $itemid Item ID to purge.
     * @return void
     */
    protected static function purge_item_cache($itemid) {
        $cache = self::get_item_cache();
        $cache->delete_menu($itemid);
    }

    /**
     * Sets the user preferences to trigger the cache purging when the menu is fetched for the user.
     * The actual cache purging is performed in build method in smartmenu
     * that checks the user preferences and purges the cache accordingly.
     *
     * @param int $userid
     * @return void
     */
    public static function set_user_purgecache($userid) {
        // Clear all the menu and item caches for this user.
        set_user_preference('theme_boost_union_menu_purgesessioncache', true, $userid);
    }

    /**
     * Reset the preference of user to clear the session cache for menu items.
     * @return void
     */
    public static function clear_user_cachepreferencemenu() {
        global $USER;
        set_user_preference('theme_boost_union_menu_purgesessioncache', false, $USER);
    }

    /**
     * Function returns the rgb format with the combination of passed color hex and opacity.
     * Used in the item background color or card layout.
     *
     * @param string $hexa Color code #ffffff
     * @param int $opacity Opacity need to add for color, if 5 then opacity is 0.5
     * @return string
     */
    public static function color_get_rgba($hexa, $opacity) {
        if (!empty($hexa)) {
            list($r, $g, $b) = sscanf($hexa, "#%02x%02x%02x");
            if ($opacity == '') {
                $opacity = 0.0;
            } else {
                $opacity = $opacity / 10;
            }
            return "rgba($r, $g, $b, $opacity)";
        }
    }

    /**
     * Returns the list of lanuages available in LMS.
     *
     * @return array
     */
    public static function get_lanuage_options() {
        $languages = get_string_manager()->get_list_of_translations();
        $langoptions = [];
        foreach ($languages as $key => $lang) {
            $langoptions[$key] = $lang;
        }
        return $langoptions;
    }
}
