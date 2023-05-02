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
 * Item controller for managing menu items. Build the item as node to attach as submenu.
 *
 * @package    theme_boost_union
 * @copyright  bdecent GmbH 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

defined('MOODLE_INTERNAL') || die();

use context_system;
use html_writer;
use smartmenu_helper;
use stdClass;
use cache;

require_once($CFG->dirroot.'/theme/boost_union/smartmenus/menulib.php');

/**
 * The item controller handles actions related to managing items.
 *
 * This controller provides methods for listing available menus, creating new items,
 * updating existing items, deleting items, and sorting the order of items.
 *
 * @package    theme_boost_union
 * @copyright  bdecent GmbH 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class smartmenu_item {

    /**
     * Representing a heading type for a menu item.
     * @var int
     */
    const TYPEHEADING = 0;

    /**
     * Represents the type of a static element.
     * @var int
     */
    const TYPESTATIC = 1;

    /**
     * Represents the type of a dynamic element.
     * @var int
     */
    const TYPEDYNAMIC = 2;

    /**
     * Represents the completion status of an item where the status is 'enrolled'.
     * @var int
     */
    const COMPLETION_ENROLLED = 1;

    /**
     * Represents the completion status of an item where the status is 'inprogress'.
     * @var int
     */
    const COMPLETION_INPROGRESS = 2;

    /**
     * Represents the completion status of an item where the status is 'completed'.
     * @var int
     */
    const COMPLETION_COMPLETED = 3;

    /**
     * Represents the range of an items where the range is past.
     * @var int
     */
    const RANGE_PAST = 1;

    /**
     * Represents the range of an items where the range is preset.
     * @var int
     */
    const RANGE_PRESENT = 2;

    /**
     * Represents the range of an items where the range is future.
     * @var int
     */
    const RANGE_FUTURE = 3;

    /**
     * Hide the item title an all viewport.
     * @var int
     */
    const DISPLAY_HIDETITLE = 0;

    /**
     * Hide the item title in mobile viewport.
     * @var int
     */
    const DISPLAY_HIDETITLEMOBILE = 1;

    /**
     * Display the title with icon.
     * @var int
     */
    const DISPLAY_SHOWTITLEICON = 2;

    /**
     * Open the item link in same tab.
     * @var int
     */
    const TARGET_SAME = 0;

    /**
     * Open the item link in new tab on browser.
     * @var int
     */
    const TARGET_NEW = 1;

    /**
     * Show the item title in below of the card image.
     * @var int
     */
    const POSITION_BELOW = 0;

    /**
     * Show the item title in top of the card overlay.
     * @var int
     */
    const POSITION_OVERLAYTOP = 1;

    /**
     * Show the item title in bottom of the card overlay.
     * @var int
     */
    const POSITION_OVERLAYBOTTOM = 2;

    /**
     * Display the dynamic items as second level submenu of the menu.
     * @var int
     */
    const MODE_SUBMENU = 2;

    /**
     * Display the dynamic items as each item of the menu.
     * @var int
     */
    const MODE_INLINE = 1;

    /**
     * Opacity for the Card background layout in overlay text positions.
     * @var int
     */
    const BACKGROUND_OPACITY = 5;

    /**
     * The ID of the menu item.
     * @var int
     */
    public $id;

    /**
     * The record of the current item.
     * @var \stdclass
     */
    public $item;

    /**
     * The menu to which this menu item belongs.
     * @var \stdclass
     */
    public $menu;

    /**
     * The helper object for this menu item.
     * @var \smartmenu_helper
     */
    public $helper;

    /**
     * The cache object for this menu item.
     * @var cache
     */
    public $cache;

    /**
     * The menu cache object for this menu item.
     * @var object
     */
    public $menucache;

    /**
     * Create a new instance of this class.
     *
     * @param int $id The ID of the item to retrieve.
     * @return smartmenu_item A new instance of this class.
     */
    public static function instance($id) {
        return new self($id);
    }

    /**
     * Menu item constructor, Retrive the item data, Create smartmenu_helper for this item,
     * Creates the cache instance for item and its menu.
     *
     * @param int $id Record id of the menu.
     */
    public function __construct(int $id) {
        global $DB;

        // Item id.
        $this->id = $id;

        // Item data.
        $this->item = $this->get_item($id);

        // Menu data, the current item belongs to.
        $this->menu = smartmenu::get_menu($this->item->menu);

        // Smartmenu helper to verify the access rules.
        $this->helper = new smartmenu_helper($this->item);

        // Cache instance for the items.
        $this->cache = cache::make('theme_boost_union', 'smartmenu_items');

        // Cache instance for the item`s menus.
        $this->menucache = cache::make('theme_boost_union', 'smartmenus');
    }

    /**
     * Deletes the cache for the current item and the cached data of the current item's menu.
     *
     * @return void
     */
    public function delete_cache() {
        // Delete this item cached.
        if ($this->cache->has($this->id)) {
            $this->cache->delete($this->id);
        }
        // Delete the cached data of current items menu.
        if ($this->menucache->has($this->item->menu)) {
            $this->menucache->delete($this->item->menu);
        }
    }

    /**
     * Fetches a item record from the database by ID and returns it as an object with convert the json values to array.
     *
     * @return \stdclass Menu record if found or false.
     * @throws \moodle_exception When menu is not found.
     */
    public function get_item() {
        global $DB;

        // Verfiy and Fetch menu record from DB.
        if ($record = $DB->get_record('theme_boost_union_menuitems', ['id' => $this->id])) {

            // Decode the multiple option select elements values to array.
            $record->category = json_decode($record->category) ?: [];
            $record->enrolmentrole = json_decode($record->enrolmentrole) ?: [];
            $record->completionstatus = json_decode($record->completionstatus) ?: [];
            $record->daterange = json_decode($record->daterange) ?: [];

            // Restrict access rules.
            $record->roles = json_decode($record->roles) ?: [];
            $record->cohorts = json_decode($record->cohorts) ?: [];
            $record->languages = json_decode($record->languages) ?: [];

            // Seperate the customfields.
            $customfields = json_decode($record->customfields) ?: [];
            foreach ($customfields as $field => $value) {
                $record->{'customfield_'.$field} = $value;
            }
            return $record;

        } else {
            // TODO: string for menu not found.
            throw new \moodle_exception('itemnotfound', 'theme_boost_union');
        }

        return false;
    }

    /**
     * Delete the current item's menu from the database.
     *
     * @return bool True if the deletion is successful, false otherwise.
     */
    public function delete_menuitem() {
        global $DB;

        if ($DB->delete_records('theme_boost_union_menuitems', ['id' => $this->id])) {
            // Reorder the items.
            $this->reorder_items();
            // Delete the cache.
            $this->delete_cache();

            return true;
        }
        return false;
    }

    /**
     * Move the menu items order to upwards.
     * Find the previous item and set the previous item order as order for current item.
     *
     * @return bool true if the item was moved successfully, false otherwise
     */
    public function move_upward() {
        global $DB;
        $currentposition = $this->item->sortorder;
        // Confirm is moving upward is possible.
        if ($currentposition > 1) {
            // TODO: remove the fetch based on sortorder, use get last element.
            $previtem = $DB->get_record('theme_boost_union_menuitems', [
                'sortorder' => $currentposition - 1,
                'menu' => $this->item->menu
            ]);

            if (empty($previtem)) {
                return false;
            }
            // Update the menu position to upwards.
            $DB->set_field('theme_boost_union_menuitems', 'sortorder', $previtem->sortorder, [
                'id' => $this->id,
                'menu' => $this->item->menu
            ]);
            // Set the prevmenu position to down.
            $DB->set_field('theme_boost_union_menuitems', 'sortorder', $currentposition, [
                'id' => $previtem->id,
                'menu' => $this->item->menu
            ]);

            // Delete the cache and menu cache.
            $this->delete_cache();

            // Purge the menu items cache.
            \cache_helper::purge_by_event('theme_boost_union_menuitems_sorted');

            return true;
        }
        return false;
    }

    /**
     * Move the menu items order to downwards.
     * Find the next item and set the next item order as order for current item.
     *
     * @return bool true if the item was moved successfully, false otherwise
     */
    public function move_downward() {
        global $DB;
        $currentposition = $this->item->sortorder;
        // Confirm, moving downward is possible.
        if ($currentposition < self::get_itemscount($this->item->menu)) {

            $nextitem = $DB->get_record('theme_boost_union_menuitems', [
                'sortorder' => $currentposition + 1,
                'menu' => $this->item->menu
            ]);

            if (!$nextitem) {
                return false;
            }
            // Update the menu position to down.
            $DB->set_field('theme_boost_union_menuitems', 'sortorder', $nextitem->sortorder, [
                'id' => $this->id,
                'menu' => $this->item->menu
            ]);
            // Set the prevmenu position to up.
            $DB->set_field('theme_boost_union_menuitems', 'sortorder', $currentposition, [
                'id' => $nextitem->id,
                'menu' => $this->item->menu
            ]);

            // Delete the cache and menu cache.
            $this->delete_cache();

            // Purge the menu items cache.
            \cache_helper::purge_by_event('theme_boost_union_menuitems_sorted');

            return true;
        }
        return false;
    }

    /**
     * Reorder the items sort order, if the last menu order is not same as count of records.
     * We should update the order of items.
     *
     * @return void
     */
    public function reorder_items() {
        global $DB;

        $sql = 'SELECT * FROM {theme_boost_union_menuitems} WHERE menu=:menu ORDER BY sortorder ASC';
        $records = $DB->get_records_sql($sql, ['menu' => $this->item->menu], 0, 1);

        if (empty($records)) {
            return false;
        }

        // The last item order is not same as count of records.
        if (current($records)->sortorder != count($records)) {
            // Update the sortorder from lower order.
            $i = 1;
            foreach (array_reverse($records) as $itemid => $item) {
                $DB->set_field('theme_boost_union_menuitems', 'sortorder', $i, ['id' => $item->id]);
                $i++;
            }
        }
    }

    /**
     * Get the last item from the current menu`s list of items.
     *
     * @param int $menuid
     * @return stdclass An object representing the last item, or an empty object if no menus exist.
     */
    public static function get_lastitem($menuid) {
        global $DB;

        $sql = 'SELECT * FROM {theme_boost_union_menuitems} WHERE menu=:menu ORDER BY sortorder DESC';
        $records = $DB->get_records_sql($sql, ['menu' => $menuid], 0, 1);
        return (object) (!empty($records) ? current($records) : []);
    }
    /**
     * Duplicate the current item, clone the current item object and remove the id from item then
     * send to manage_instance method to create as new item.
     *
     * @return void
     */
    public function duplicate() {
        $record = $this->item;
        $record->id = 0;
        // Create instance.
        if (self::manage_instance($record)) {
            \core\notification::success(get_string('smartmenu:menuitemduplicated', 'theme_boost_union'));
        }
    }

    /**
     * Updates a field of the current item with the given key and value.
     *
     * @param string $key The key of the field to update.
     * @param mixed $value The new value of the field.
     * @return bool|int Returns true on success, or false on failure. it also deletes the current menu from cache.
     */
    public function update_field($key, $value) {
        global $DB;
        // Delete the cached current item and menu of this item.
        $this->delete_cache();

        return $DB->set_field('theme_boost_union_menuitems', $key, $value, ['id' => $this->id]);
    }

    /**
     * Returns the URL of the image associated with the given item ID,
     * or a placeholder image URL if no image is associated with the item.
     *
     * @param int $itemid The ID of the item.
     * @return string The URL of the image associated with the item.
     */
    public function get_itemimage($itemid) {
        global $OUTPUT, $SITE;

        $fs = get_file_storage();
        $contextid = \context_system::instance()->id;
        $files = $fs->get_area_files($contextid, 'theme_boost_union', 'smartmenus_itemimage', $itemid, '', false);
        if (!empty($files)) {
            // Get the first file.
            $file = reset($files);

            $url = \moodle_url::make_pluginfile_url(
                $file->get_contextid(),
                $file->get_component(),
                $file->get_filearea(),
                $file->get_itemid(),
                $file->get_filepath(),
                $file->get_filename(),
                false
            );
        }
        $placeholderimage = $OUTPUT->get_generated_image_for_id($SITE->id);
        return $url ?? $placeholderimage;
    }

    /**
     * Generate a node data for a heading item.
     *
     * @return array The node data.
     */
    protected function generate_heading() {
        $heading = format_string($this->item->title);

        return $this->generate_node_data(
            $this->item->title, // Title.
            '#', // URL.
            null, // Default key.
            $this->item->tooltip, // Tooltip.
            'heading'
        );
    }

    /**
     * Generate the item as static menu item, Send the custom URL to moodle_url to make this work with relative URL.
     *
     * @return string
     */
    protected function generate_static_item() {

        $staticurl = new \moodle_url($this->item->url);

        return $this->generate_node_data(
            $this->item->title, // Title.
            $staticurl, // URL.
            null, // Default key.
            $this->item->tooltip, // Tooltip.
        );
    }

    /**
     * Generate the dynamic courses based on the conditions of categories, enrollmentrole,
     * daterange (Past, Present, Future), and customfields.
     *
     * Generate the fetched course records as each nodes.
     *
     * @return void
     */
    protected function generate_dynamic_item() {
        global $DB;

        // Prevent the item if the item mode is submenu and the menu is card.
        if ($this->item->mode == self::MODE_SUBMENU && $this->menu->type == smartmenu::TYPE_CARD) {
            return [];
        }
        $query = (object) [
            'select' => ['c.*'],
            'join' => [],
            'where' => ["c.visible > 0"],
            'params' => [],
        ];

        // Courses from categories.
        $this->get_categories_sql($query);

        // Enrolment role.
        $this->get_enrollmentrole_sql($query);

        // Completion status based condition query.
        $this->get_completionstatus_sql($query);

        // Daterange based courses filter.
        $this->get_daterange_sql($query);

        // Custom field based courses filter.
        $this->get_customfield_sql($query);

        // Build the queries.
        $select = implode(',', array_filter($query->select));
        $join = implode('', array_filter($query->join));
        $where = implode(' AND ', array_filter($query->where));
        $params = array_merge($query->params);

        $sql = " SELECT $select FROM {course} c $join";
        $sql .= $where ? " WHERE $where " : '';

        // Fetch the course records based on the sql.
        $records = $DB->get_records_sql($sql, $params);

        if (empty($records)) {
            return [];
        }

        $items = [];
        // Build the items data into nodes.
        foreach ($records as $record) {
            $url = new \moodle_url('/course/view.php', ['id' => $record->id]);
            $rkey = 'item-'.$this->item->id.'-dynamic-'.$record->id;
            $items[] = $this->generate_node_data($record->fullname, $url, $rkey);
        }

        // Submenu only contains the title as separate node.
        if ($this->item->mode == self::MODE_SUBMENU) {
            $haschildren = (count($items) > 1 ) ? true : false;
            $submenu[] = $this->generate_node_data(
                $this->item->title, // Title.
                'javascript:void(0)', // URL.
                null, // Default key.
                $this->item->tooltip, // Tooltip.
                'submenu', // Item type.
                $haschildren, // Has children.
                $items // Children.
            );
            return $submenu;
        }

        return $items;
    }

    /**
     * Adds category filter to the SQL query.
     *
     * @param stdclass $query the database query to modify
     * @return bool true if categories filter was applied, false otherwise
     */
    public function get_categories_sql(&$query) {
        global $DB;

        if (empty($this->item->category)) {
            return false;
        }

        list($insql, $inparams) = $DB->get_in_or_equal($this->item->category, SQL_PARAMS_NAMED, 'cg');
        $query->where[] = "c.category $insql";
        $query->params += $inparams;
    }

    /**
     * Prepare the SQL query to get the enrolments based role.
     * Get user role assignments in the context, roleid should same as selected.
     *
     * @param stdclass $query the database query to modify
     * @return void
     */
    public function get_enrollmentrole_sql(&$query) {
        global $DB, $USER;

        if (empty($this->item->enrolmentrole)) {
            return false;
        }

        $roles = $this->item->enrolmentrole;
        [$rsql, $rparams] = $DB->get_in_or_equal($roles, SQL_PARAMS_NAMED, 'roles');

        $query->where[] = "c.id IN (SELECT ctx.instanceid
            FROM {role_assignments} ra
            JOIN {context} ctx ON ctx.id = ra.contextid AND ctx.contextlevel = " . CONTEXT_COURSE . "
            WHERE ra.userid = :ruserid AND ra.roleid $rsql)";

        $query->params += $rparams + ['ruserid' => $USER->id];
    }

    /**
     * Build condition query for course completion status,
     * It uses course_completion conditions, it has conditions Enrolled, Inprogress, Completed.
     *
     * Enrolled means user should have active enrollment, not completed any course completion enabled activity modules.
     * Inprogress means user should complete any of the activity enabled for course completion condition.
     * Completed Means user should completed the course.
     *
     * Query find the progress by calculate the completions of activity which enabled for course completions.
     *
     * @param [type] $query
     * @return void
     */
    public function get_completionstatus_sql(&$query) {
        global $DB, $USER;

        if (empty($this->item->completionstatus)) {
            return false;
        }

        $status = $this->item->completionstatus;
        // Convert the selected completion status to insql.
        $list = [];
        foreach ($status as $condition) {
            switch($condition) {
                case self::COMPLETION_INPROGRESS:
                    $list[] = 'inprogress';
                    break;
                case self::COMPLETION_COMPLETED:
                    $list[] = 'completed';
                    break;
                case self::COMPLETION_ENROLLED:
                    $list[] = 'enrolled';
                    break;
            }
        }

        list($insql, $inparam) = $DB->get_in_or_equal($list, SQL_PARAMS_NAMED, 'csts');

        $sql = "SELECT ue.courseid FROM (
            SELECT
                CASE WHEN (mcm.progress/cms.total) * 100 = 100 THEN 'completed'
                    WHEN mcm.progress > 0 THEN 'inprogress'
                    WHEN ue.timestart > 0 THEN 'enrolled'
                    ELSE NULL
                    END AS status,
                    e.courseid AS courseid
            FROM {user_enrolments} ue
            LEFT JOIN {enrol} e ON ue.enrolid = e.id
            LEFT JOIN (
                SELECT count(*) AS total, course FROM {course_modules}
                WHERE completion >= 1 GROUP BY course
            ) cms ON cms.course = e.courseid
            LEFT JOIN (
                SELECT count(*) as progress, cm.course, mc.userid FROM {course_modules} cm
                JOIN {course_modules_completion} mc ON mc.coursemoduleid = cm.id
                WHERE mc.completionstate > 0 GROUP BY mc.userid, cm.course
            ) mcm ON mcm.course = e.courseid AND mcm.userid = ue.userid
            WHERE ue.userid = :fueuserid AND ue.status <= 0
            AND (ue.timestart = 0 OR ue.timestart <= :timestart)
            AND (ue.timeend = 0 OR ue.timeend > :timeend)

        ) ue WHERE ue.status $insql";

        $query->where[] = " c.id IN ($sql) ";
        $query->params += ['fueuserid' => $USER->id, 'timestart' => time(), 'timeend' => time()] + $inparam;
    }

    /**
     * Generates the SQL statement for the date range condition. Range is based on the course startdate and enddate.
     *
     * @param stdclass $query The database query object.
     * @return bool Returns false if the item's date range is empty.
     */
    public function get_daterange_sql(&$query) {
        global $DB, $USER;

        if (empty($this->item->daterange)) {
            return false;
        }

        $dates = $this->item->daterange;

        $sql = [];
        $params = [];
        foreach ($dates as $key => $date) {
            switch ($date) {
                case self::RANGE_PAST:
                    $sql[] = "c.enddate <> 0 AND c.enddate < :now_$key";
                    $params += ['now_'.$key => time()];
                    break;
                case self::RANGE_PRESENT:
                    $sql[] = "(c.startdate < :startdate_$key AND ( c.enddate = 0 OR c.enddate > :enddate_$key) )";
                    $params += ['enddate_'.$key => time(), 'startdate_'.$key => time()];
                    break;
                case self::RANGE_FUTURE:
                    $sql[] = "c.startdate > :now_$key";
                    $params += ['now_'.$key => time()];
                    break;
            }
        }

        $query->where[] = $sql ? '('.implode(' OR ', $sql).')' : '';
        $query->params += $params;
    }

    /**
     * Generates the SQL statement for the custom field condition.
     * It creates a condition query to fetch the course which has the same value mentioned in the item customfield conditions.
     *
     * @param stdclass $query The database query object.
     * @return bool Returns false if the item's date range is empty.
     */
    public function get_customfield_sql(&$query) {
        global $DB, $USER;

        if (empty($this->item->customfields)) {
            return false;
        }

        $customfields = $this->item->customfields ? json_decode($this->item->customfields) : [];

        $i = 0;
        $params = [];
        $sql = [];
        foreach ($customfields as $shortname => $value) {
            if ($value == 0 ) {
                continue;
            }
            $i++;
            $sql[] = "
                c.id IN (
                    SELECT instanceid FROM {customfield_data} cd
                    JOIN {customfield_field} cf ON cd.fieldid = cf.id AND cf.shortname = :shortname_$i
                    WHERE cd.value=:value_$i
                )";
            $params += ["shortname_$i" => $shortname, "value_$i" => $value];
        }

        $query->where[] = $sql ? implode(' AND ', $sql) : '';
        $query->params += $params;
    }

    /**
     * Defines a build method that generates the HTML markup for a menu item.
     *
     * First, it checks if the menu item is cached and returns it if found.
     * If not, it verifies if the user has access to the menu item and checks for any access restrictions.
     *
     * It then adds custom CSS classes and hides the menu item based on specific viewport sizes.
     * It also adds classes for the item title placement on the card.
     *
     * Next, it checks the type of the menu item and generates the array markup based on the type:
     * If the type is static, it generates a static item using the generate_static_item method and returns it as an array.
     * If the type is dynamic, it generates a dynamic item using the generate_dynamic_item method and returns it as an array.
     * If the type is heading or not set, it generates a heading using the generate_heading method and returns it as an array.
     * It then sets the item's classes and saves the items cache.
     * Finally, it deletes the menu cache and returns the generated HTML markup as an array.
     *
     * @return false|array Returns false if the menu is not visible or a item array otherwise.
     */
    public function build() {
        global $DB;

        // Cache for menu.
        $cache = cache::make('theme_boost_union', 'smartmenu_items');

        // Purge the cahced menus data if the menu date restrictions are reached or passed.
        smartmenu_helper::purge_cache_date_reached($cache, $this->item, 'theme_boost_union_menuitemlastcheckdate');

        // If the flag to purge the menuitems cache is set for this user.
        if (get_user_preferences('theme_boost_union_menuitem_purgesessioncache', false) == true) {
            // Purge the menuitems cache for this user.
            \cache_helper::purge_by_definition('theme_boost_union', 'smartmenu_items');
        }

        // Get the node data for item from cache if it is stored.
        if ($result = $cache->get($this->item->id)) {
            return $result;
        }

        // Verify the restriction rules.
        if (empty($this->item) || !$this->helper->verify_access_restrictions()) {
            return false;
        }

        // Add custom css class.
        $class[] = $this->item->cssclass;
        // Add classes for hide items in specific viewport.
        $class[] = $this->item->desktop ? 'd-lg-none' : 'd-lg-inline-block';
        $class[] = $this->item->tablet ? 'd-md-none' : 'd-md-inline-block';
        $class[] = $this->item->mobile ? 'd-none' : 'd-inline-block';

        // Add classes for item title placement on card.
        $class[] = $this->get_textposition_class();

        // Convert the item background color hexcode into rgba with opacity. Used in the overlay style.
        $this->convert_background_code();

        switch ($this->item->type):

            case self::TYPESTATIC:
                $static = $this->generate_static_item();
                $result = [$static]; // Return the result as recursive array for merge with dynamic items.
                $type = 'static';
                break;

            case self::TYPEDYNAMIC:
                $result = $this->generate_dynamic_item();
                $type = 'dynamic';
                break;

            case self::TYPEHEADING:
            default:
                $heading = $this->generate_heading();
                $result = [$heading]; // Return the result as recursive array useful to merge with dynamic items.
                $type = 'heading';

        endswitch;

        // Menu item class.
        $class[] = 'menu-item-'.$type;
        // Add classes to item data.
        $this->item->classes = $class;

        // Save the items cache.
        $cache->set($this->item->id, $result);
        // Delete the cache of items menu, Recreate the menu.
        $this->menucache->delete($this->item->menu);

        return $result;
    }

    /**
     * Generate node data for dynamic menu item.
     *
     * Formats the title and adds an icon if it is specified in the menu item.
     * It then creates an array containing all the necessary data for the node such as
     * the item data, URL, key, text, title, whether or not it has children, item image, and item type.
     *
     * If the node has children, the function adds an array of child nodes to the data array.
     * If the menu item's target is set to open in a new window, the function adds an 'attributes' element
     * to the data array with the target set to '__blank'.
     *
     * Lastly, if the title is a series of '#' characters, the function adds a 'divider' element to the data array.
     *
     * @param string $title The title of the item.
     * @param string $url The URL of the item.
     * @param string|null $key The unique key of the item, defaults to 'item-' followed by the item ID.
     * @param string|null $tooltip The tooltip text of the item.
     * @param string $itemtype The type of the item, defaults to 'link'.
     * @param int $haschildren Whether the item has children or not, defaults to 0.
     * @param array $children An array of child nodes, defaults to an empty array.
     *
     * @return array An associative array of node data for the item.
     */
    public function generate_node_data($title, $url, $key=null, $tooltip=null, $itemtype='link', $haschildren=0, $children=[]) {
        global $OUTPUT;

        $title = format_string($title);
        // Icon not shown in moodle 4.x, added the icon with text.
        if ($this->item->menuicon) {
            $icon = explode(':', $this->item->menuicon);
            $iconstr = isset($icon[1]) ? $icon[1] : 'moodle';
            $component = isset($icon[0]) ? $icon[0] : '';
            // Render the pix icon.
            $icon = $OUTPUT->pix_icon($iconstr,  $this->item->title, $component);

            switch ($this->item->display) {

                case self::DISPLAY_SHOWTITLEICON:
                    $title = $icon . $title;
                    break;
                case self::DISPLAY_HIDETITLE:
                    $title = $icon;
                    break;
                case self::DISPLAY_HIDETITLEMOBILE:
                    $title = $icon . html_writer::tag('label', $title, ['class' => 'd-none d-sm-inline-block']);
                    break;
            }
        }

        $data = [
            'itemdata' => $this->item,
            'url' => $url,
            'key' => $key != null ? $key : 'item-'.$this->item->id,
            'text' => $title,
            'title' => $tooltip ? format_string($tooltip) : format_string($title),
            'haschildren' => $haschildren,
            'itemimage' => $this->get_itemimage($this->item->id),
            'itemtype' => $itemtype,
            'link' => 1
        ];

        if ($haschildren && !empty($children)) {
            $data['children'] = $children;
        }

        if ($this->item->target == self::TARGET_NEW) {
            $data['attributes'] = array([
                'name' => 'target',
                'value' => '__blank'
            ]);
        }

        if (preg_match("/^#+$/", format_string($title))) {
            // In main menu divider is separate property.
            // For lang menu divider is mentioned in itemtype.
            // Updated the item type in the build_user_menu in primary navigation class method.
            $data['divider'] = true;
        }
        return $data;
    }

    /**
     * Get the class of item title text position for card layout.
     *
     * @return string|null An html class of the text position.
     */
    public function get_textposition_class() {
        switch ($this->item->textposition) {
            case self::POSITION_OVERLAYBOTTOM:
                $class = 'card-text-overlay-bottom';
                break;
            case self::POSITION_OVERLAYTOP;
                $class = 'card-text-overlay-top';
                break;
            default:
                $class = 'card-text-below';
                break;
        }
        return $class ?? '';
    }

    /**
     * Convert the card item background color hexa code into rgba().
     * It includes the opacity with color code and convert it into rgba.
     *
     * @return void
     */
    public function convert_background_code() {
        // Verify the menu style is card, and is the text position is overly bottom or overlay top.
        if ($this->menu->type != smartmenu::TYPE_CARD || $this->item->textposition == self::POSITION_BELOW) {
            return false;
        }

        // Attach the opacity into bg color and convert the item bgcolor hexa code into rgba.
        $background = smartmenu_helper::color_get_rgba($this->item->backgroundcolor, self::BACKGROUND_OPACITY);
        $this->item->backgroundcolor = $background;
    }

    /**
     * Get count of available menus.
     *
     * @param int $menuid Count the items under the given menu.
     * @return int The number of items.
     */
    public static function get_itemscount($menuid) {
        global $DB;
        return $DB->count_records('theme_boost_union_menuitems', ['menu' => $menuid]);
    }

    /**
     * Load the course custom fields mform elements to create/edit menu item form.
     * It helps to setup the conditions based on custom field values when the menu item type is dynamic courses.
     *
     * @param \MoodleQuickForm $mform
     * @return void
     */
    public static function load_custom_field_config(&$mform) {
        global $DB;

        $coursehandler = \core_course\customfield\course_handler::create();
        foreach ($coursehandler->get_fields() as $field) {
            $shortname = $field->get('shortname');
            $fieldid = $field->get('id');
            $field = \core_customfield\field_controller::create($fieldid);
            $data = \core_customfield\api::get_instance_fields_data([$fieldid => $field], 0);
            if (isset($data[$fieldid])) {
                $data = $data[$fieldid];
                $data->instance_form_definition($mform);
                $mform->hideif("customfield_".$shortname, 'type', 'neq', self::TYPEDYNAMIC);
            }
        }
    }

    /**
     * Get an array of available types for the item.
     *
     * @param int|null $type Optional. The specific type to retrieve. Defaults to null.
     * @return array|string An array of types if $type is null, or a string with the name of the specific type.
     */
    public static function get_types(int $type=null) {
        $types = array(
            self::TYPEHEADING => get_string('heading', 'editor'),
            self::TYPESTATIC => get_string('smartmenu:static', 'theme_boost_union'),
            self::TYPEDYNAMIC => get_string('smartmenu:dynamiccourses', 'theme_boost_union'),
        );

        return ($type !== null && isset($types[$type])) ? $types[$type] : $types;
    }

    /**
     * Returns the display options for the menu items.
     *
     * @param int|null $option The display option to retrieve. If null, returns all display options.
     * @return array|string The array of display options if $option is null, or the display option string if $option is set.
     * @throws coding_exception if $option is set but invalid.
     */
    public static function get_display_options(int $option=null) {
        $displayoptions = [
            self::DISPLAY_SHOWTITLEICON => get_string('smartmenu:showtitleicon', 'theme_boost_union'),
            self::DISPLAY_HIDETITLE => get_string('smartmenu:hidetitle', 'theme_boost_union'),
            self::DISPLAY_HIDETITLEMOBILE => get_string('smartmenu:hidetitlemobile', 'theme_boost_union')
        ];

        return ($option !== null && isset($displayoptions[$option])) ? $displayoptions[$option] : $displayoptions;
    }

    /**
     * Insert or update the menu instance to DB. Convert the multiple options select elements to json.
     * setup menu path after insert/update.
     *
     * Update the other items order when the current item order is updated.
     * Increase the sortorder to next for all items
     *
     *
     * @param stdclass $formdata
     * @return bool
     */
    public static function manage_instance($formdata) {
        global $DB;

        $record = $formdata;

        // Convert the multiple valueable item types to JSON.
        $record->category = json_encode($formdata->category);
        $record->enrolmentrole = json_encode($formdata->enrolmentrole);
        $record->completionstatus = json_encode($formdata->completionstatus);
        $record->daterange = json_encode($formdata->daterange);

        $coursehandler = \core_course\customfield\course_handler::create();
        $customfields = [];
        foreach ($coursehandler->get_fields() as $field) {
            $shortname = $field->get('shortname');
            $customfields[$shortname] = $record->{'customfield_'.$shortname} ?? '';
        }
        $record->customfields = json_encode($customfields);

        // Update the multiple values to JSON format.
        $record->roles = json_encode($formdata->roles);
        $record->cohorts = json_encode($formdata->cohorts);
        $record->languages = json_encode($formdata->languages);

        // Enable the responsive viewports.
        $record->desktop = ($record->desktop) ?? 0;
        $record->tablet = ($record->tablet) ?? 0;
        $record->mobile = ($record->mobile) ?? 0;

        $transaction = $DB->start_delegated_transaction();

        // Cache for menu.
        $menucache = cache::make('theme_boost_union', 'smartmenus');

        if (isset($formdata->id) && $oldrecord = $DB->get_record('theme_boost_union_menuitems', ['id' => $formdata->id])) {
            $itemid = $formdata->id;

            $DB->update_record('theme_boost_union_menuitems', $record);

            // Increase or decrease the order of items between previous and current order of this item.
            if ($oldrecord->sortorder != $record->sortorder) {
                $operation = ($oldrecord->sortorder < $record->sortorder)
                    ? 'SET sortorder=sortorder-1 WHERE (sortorder between :oldorder AND :neworder)'
                    : 'SET sortorder=sortorder+1 WHERE (sortorder between :neworder AND :oldorder)';

                $decrease = "UPDATE {theme_boost_union_menuitems} $operation AND id != :item AND menu=:menuid";
                // Used the execute method, couldn't found any defined function to update records using sql.
                $DB->execute($decrease, [
                    'oldorder' => $oldrecord->sortorder,
                    'neworder' => $record->sortorder,
                    'item' => $formdata->id,
                    'menuid' => $formdata->menu
                ]);
            }

            // Delete the item cached.
            \cache_helper::purge_by_event('theme_boost_union_menuitems_edited');
            // Delete the cached data of its menu.
            \cache_helper::purge_by_event('theme_boost_union_menus_edited');

            // Show the edited success notification.
            \core\notification::success(get_string('smartmenu:updatesuccess', 'theme_boost_union'));
        } else {
            $record->sortorder = $record->sortorder ?: 1;
            $itemid = $DB->insert_record('theme_boost_union_menuitems', $record);
            // Setup the order for item.
            $sql = "UPDATE {theme_boost_union_menuitems}
                SET sortorder = sortorder + 1
                WHERE sortorder >= :sortorder AND id != :item AND menu=:menuid";

            $DB->execute($sql, ['sortorder' => $record->sortorder, 'item' => $itemid, 'menuid' => $record->menu]);
            // Show the menu inserted success notification.
            \core\notification::success(get_string('smartmenu:insertsuccess', 'theme_boost_union'));

            // Delete the cached data of its menu. Menu will recreate with this item.
            $menucache->delete($formdata->menu);
        }

        // Save the item image files to the file directory.
        if (isset($record->image)) {
            $draftitemid = file_get_submitted_draft_itemid('itemimage');
            file_save_draft_area_files(
                $record->image,
                context_system::instance()->id,
                'theme_boost_union',
                'smartmenus_itemimage',
                $itemid,
                self::image_fileoptions()
            );
        }

        $transaction->allow_commit();

        return true;
    }

    /**
     * Get file options for selecting a single web image file.
     *
     * @return array An array of file options.
     */
    public static function image_fileoptions() {

        return [
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => 'web_image',
        ];
    }

}
