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
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

defined('MOODLE_INTERNAL') || die();

use context_system;
use stdClass;
use cache;
use core\output\html_writer;
use core_course\external\course_summary_exporter;

require_once($CFG->dirroot.'/theme/boost_union/smartmenus/menulib.php');

/**
 * The item controller handles actions related to managing items.
 *
 * This controller provides methods for listing available menus, creating new items,
 * updating existing items, deleting items, and sorting the order of items.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
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
     * Display the course shortname as title in menu for dynamic menu item.
     *
     * @var int
     */
    const FIELD_SHORTNAME = 1;

    /**
     * Display the course fullname as title in menu for dynamic menu item.
     * @var int
     */
    const FIELD_FULLNAME = 0;

    /**
     * Sort the course list alphabetically by fullname ascending for dynamic menu item.
     * @var int
     */
    const LISTSORT_FULLNAME_ASC = 0;

    /**
     * Sort the course list alphabetically by fullname descending for dynamic menu item.
     * @var int
     */
    const LISTSORT_FULLNAME_DESC = 1;

    /**
     * Sort the course list alphabetically by shortname ascending for dynamic menu item.
     * @var int
     */
    const LISTSORT_SHORTNAME_ASC = 2;

    /**
     * Sort the course list alphabetically by shortname descending for dynamic menu item.
     * @var int
     */
    const LISTSORT_SHORTNAME_DESC = 3;

    /**
     * Sort the course list numerically by course-id ascending for dynamic menu item.
     * @var int
     */
    const LISTSORT_COURSEID_ASC = 4;

    /**
     * Sort the course list numerically by course-id descending for dynamic menu item.
     * @var int
     */
    const LISTSORT_COURSEID_DESC = 5;

    /**
     * Sort the course list alphabetically by course idnumber ascending for dynamic menu item.
     * @var int
     */
    const LISTSORT_COURSEIDNUMBER_ASC = 6;

    /**
     * Sort the course list alphabetically by course idnumber descending for dynamic menu item.
     * @var int
     */
    const LISTSORT_COURSEIDNUMBER_DESC = 7;

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
     * @var smartmenu_helper
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
     * @param int|stdclass $item The ID of the item to retrieve or record data of item.
     * @param stdclass|null $menu Data of the menu the item belongs to.
     * @return smartmenu_item A new instance of this class.
     */
    public static function instance($item, $menu = null) {
        return new self($item, $menu);
    }

    /**
     * Menu item constructor, Retrive the item data, Create smartmenu_helper for this item,
     * Creates the cache instance for item and its menu.
     *
     * @param int|stdclass $item Record or id of the menu.
     * @param stdclass|null $menu Menu data belongs to this item, it fetch the menus data if empty.
     */
    public function __construct($item, $menu = null) {

        if (is_scalar($item)) {
            $item = $this->get_item($item);
        }

        // Item ID.
        $this->id = $item->id;

        // Format the item values.
        $this->item = $this->update_item_valuesformat($item);

        // Verify the item data is object or array, otherwise throws an exeception.
        if (!is_array($item) && !is_object($item)) {
            throw new \moodle_exception('error:smartmenusmenuitemnotfound', 'theme_boost_union');
        }

        // Menu data, the current item belongs to.
        $this->menu = $menu ?: smartmenu::get_menu($this->item->menu);

        // Smartmenu helper to verify the access rules.
        $this->helper = new smartmenu_helper($this->item);

        // Cache instance for the items.
        $this->cache = cache::make('theme_boost_union', 'smartmenu_items');

        // Menus cache instance.
        // Purge the menu related to the item, when the item is updated, created and sorted.
        $this->menucache = cache::make('theme_boost_union', 'smartmenus');
    }

    /**
     * Deletes the cache for the current item and the cached data of the current item's menu.
     *
     * @return void
     */
    public function delete_cache() {
        // Remove cache of current item for all users.
        $this->cache->delete_menu($this->item->id);
        // Delete the cached data of current items menu.
        $this->menucache->delete_menu($this->item->menu);
    }

    /**
     * Fetches a item record from the database by ID and returns it as an object with convert the json values to array.
     *
     * @param int $itemid Id of the item.
     * @return \stdclass Menu record if found or false.
     * @throws \moodle_exception When menu is not found.
     */
    public function get_item($itemid = null) {
        global $DB;

        // Verfiy and Fetch menu record from DB.
        if ($record = $DB->get_record('theme_boost_union_menuitems', ['id' => $itemid ?: $this->id ])) {

            // Decode the multiple option select elements values to array.
            return $this->update_item_valuesformat($record);

        } else {
            throw new \moodle_exception('error:smartmenusmenuitemnotfound', 'theme_boost_union');
        }

        return false;
    }

    /**
     * Updated the items values format, Some the values like category and other restriction options are stored as json.
     * Convert the json values to array.
     *
     * @param stdclass $itemdata
     * @return stdclass Items data in updated format.
     */
    public function update_item_valuesformat($itemdata) {
        // Verify the format is already updated.
        if (!is_scalar($itemdata->category)) {
            return $itemdata;
        }
        $itemdata->category = json_decode($itemdata->category) ?: [];
        $itemdata->enrolmentrole = json_decode($itemdata->enrolmentrole) ?: [];
        $itemdata->completionstatus = json_decode($itemdata->completionstatus) ?: [];
        $itemdata->daterange = json_decode($itemdata->daterange) ?: [];

        // Restrict access rules.
        $itemdata->roles = json_decode($itemdata->roles) ?: [];
        $itemdata->cohorts = json_decode($itemdata->cohorts) ?: [];
        $itemdata->languages = json_decode($itemdata->languages) ?: [];

        // Seperate the customfields.
        $customfields = json_decode($itemdata->customfields) ?: [];
        foreach ($customfields as $field => $value) {
            $itemdata->{'customfield_'.$field} = $value;
        }

        return $itemdata;
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
            // Find the previous item.
            $sql = 'SELECT * FROM {theme_boost_union_menuitems} WHERE sortorder < :pos AND menu = :menu ORDER BY sortorder ASC';
            $previtems = $DB->get_records_sql($sql, [
                'pos' => $currentposition,
                'menu' => $this->item->menu,
            ]);

            if (empty($previtems)) {
                return false;
            }

            $previtem = end($previtems);

            // Update the menu position to upwards.
            $DB->set_field('theme_boost_union_menuitems', 'sortorder', $previtem->sortorder, [
                'id' => $this->id,
                'menu' => $this->item->menu,
            ]);
            // Set the prevmenu position to down.
            $DB->set_field('theme_boost_union_menuitems', 'sortorder', $currentposition, [
                'id' => $previtem->id,
                'menu' => $this->item->menu,
            ]);

            // Difference between two items is more than 1 then reorder the items.
            if (($currentposition - $previtem->sortorder) > 1) {
                $this->reorder_items();
            }
            // Delete the menu cache, recreate the menu with updated items order.
            $this->delete_cache();

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
        // Find the previous item.
        $sql = 'SELECT * FROM {theme_boost_union_menuitems} WHERE sortorder > :pos AND menu = :menu ORDER BY sortorder ASC';
        $nextitems = $DB->get_records_sql($sql, [
            'pos' => $currentposition,
            'menu' => $this->item->menu,
        ]);

        if (empty($nextitems)) {
            return false;
        }

        $nextitem = current($nextitems); // First item in the list.
        // Update the menu position to down.
        $DB->set_field('theme_boost_union_menuitems', 'sortorder', $nextitem->sortorder, [
            'id' => $this->id,
            'menu' => $this->item->menu,
        ]);
        // Set the prevmenu position to up.
        $DB->set_field('theme_boost_union_menuitems', 'sortorder', $currentposition, [
            'id' => $nextitem->id,
            'menu' => $this->item->menu,
        ]);

        // Difference between two items is more than 1 then reorder the items.
        if (($nextitem->sortorder - $currentposition) > 1) {
            $this->reorder_items();
        }

        // Delete the menu cache, recreate the menu with updated items order.
        $this->delete_cache();

        return true;

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
        $records = $DB->get_records_sql($sql, ['menu' => $this->item->menu]);

        if (empty($records)) {
            return false;
        }

        // The last item order is not same as count of records.
        if (end($records)->sortorder != count($records)) {
            // Update the sortorder from lower order.
            $i = 1;
            foreach ($records as $itemid => $item) {
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
            \core\notification::success(get_string('smartmenusmenuitemduplicatesuccess', 'theme_boost_union'));
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

            $url = \core\url::make_pluginfile_url(
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
     * Returns the URL of the image associated with the given course ID,
     * or a placeholder image URL if no image is associated with the course.
     *
     * @param stdclass $course The course record
     * @return string The URL of the image associated with the item.
     */
    public function get_course_image($course) {

        $courseimage = course_summary_exporter::get_course_image($course);
        if (!$courseimage && $this->menu->type == smartmenu::TYPE_CARD) {
            // Course image not available, then check current parent item image,
            // If found then use the image otherwise generate the Custom image.
            $courseimage = $this->get_itemimage($this->item->id);
        }
        return $courseimage;
    }

    /**
     * Generate a node data for a heading item.
     *
     * @return array The node data.
     */
    protected function generate_heading() {

        return $this->generate_node_data(
            $this->item->title, // Title.
            '#', // URL.
            null, // Default key.
            $this->item->tooltip, // Tooltip.
            'heading'
        );
    }

    /**
     * Generate the item as static menu item, Send the custom URL to core\url to make this work with relative URL.
     *
     * @return string
     */
    protected function generate_static_item() {

        $staticurl = new \core\url($this->item->url);

        return $this->generate_node_data(
            $this->item->title, // Title.
            $staticurl, // URL.
            null, // Default key.
            $this->item->tooltip,
        // Tooltip.
        );
    }

    /**
     * Generate the dynamic courses based on the conditions of categories, enrollmentrole,
     * daterange (Past, Present, Future), and customfields.
     *
     * Generate the fetched course records as each nodes.
     *
     * @return stdclass
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

        // Sort the courses in ascending order by its ID.
        // The real list sorting is done later as we have to handle multilanguage strings
        // which is not possible in SQL.
        // Sorting by ID here is just to make cases where two courses have exactly identical names deterministic.
        $sql .= " ORDER BY c.id ASC ";

        // Fetch the course records based on the sql.
        $records = $DB->get_records_sql($sql, $params);

        if (empty($records)) {
            return [];
        }

        $items = [];
        // Build the items data into nodes.
        foreach ($records as $record) {
            $url = new \core\url('/course/view.php', ['id' => $record->id]);
            $rkey = 'item-'.$this->item->id.'-dynamic-'.$record->id;
            // Get the course image from overview files.
            $itemimage = $this->get_course_image($record);
            // Generate the navigation node for this course and add the node to items list.
            $coursename = ($this->item->displayfield == self::FIELD_SHORTNAME) ? $record->shortname : $record->fullname;
            // Short the course text name. used custom end (2) dots instead of three dots to display more words from coursenames.
            $coursename = ($this->item->textcount) ? $this->shorten_words($coursename, $this->item->textcount) : $coursename;
            // Store the string which should be used for sorting within the item.
            switch ($this->item->listsort) {
                case self::LISTSORT_FULLNAME_ASC:
                case self::LISTSORT_FULLNAME_DESC:
                default:
                    $sortstring = $record->fullname;
                    break;
                case self::LISTSORT_SHORTNAME_ASC:
                case self::LISTSORT_SHORTNAME_DESC:
                    $sortstring = $record->shortname;
                    break;
                case self::LISTSORT_COURSEID_ASC:
                case self::LISTSORT_COURSEID_DESC:
                    $sortstring = $record->id;
                    break;
                case self::LISTSORT_COURSEIDNUMBER_ASC:
                case self::LISTSORT_COURSEIDNUMBER_DESC:
                    $sortstring = $record->idnumber;
                    break;
            }

            $items[] = $this->generate_node_data($coursename, $url, $rkey, null, 'link', false, [], $itemimage, $sortstring);
        }

        // Sort the courses based on the configured setting.
        $listsort = $this->item->listsort;
        usort($items, function($course1, $course2) use ($listsort) {
            switch ($listsort) {
                case self::LISTSORT_FULLNAME_ASC:
                case self::LISTSORT_SHORTNAME_ASC:
                case self::LISTSORT_COURSEID_ASC:
                case self::LISTSORT_COURSEIDNUMBER_ASC:
                default:
                    return strnatcasecmp($course1['sortstring'], $course2['sortstring']);
                case self::LISTSORT_FULLNAME_DESC:
                case self::LISTSORT_SHORTNAME_DESC:
                case self::LISTSORT_COURSEID_DESC:
                case self::LISTSORT_COURSEIDNUMBER_DESC:
                    return strnatcasecmp($course2['sortstring'], $course1['sortstring']);
            }
        });

        // Submenu only contains the title as separate node.
        if ($this->item->mode == self::MODE_SUBMENU) {
            $haschildren = (count($items) > 0 ) ? true : false;
            $submenu[] = $this->generate_node_data(
                $this->item->title, // Title.
                '', // URL.
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
     * Given some text and an ideal length, this function truncates the text based on words count.
     *
     * @param string $text text to be shortened
     * @param int $count Length of the words
     * @return string $text shortened string
     */
    protected function shorten_words($text, $count) {
        if (str_word_count($text, 0) > $count) {
            $words = str_word_count($text, 2); // Find the position of last word.
            $positions = array_keys($words);
            $text = trim(substr($text, 0, $positions[$count])).'..';
        }
        return $text;
    }


    /**
     * Adds category filter to the SQL query.
     *
     * @param stdclass $query the database query to modify
     * @return bool true if categories filter was applied, false otherwise
     */
    protected function get_categories_sql(&$query) {
        global $DB;

        if (empty($this->item->category)) {
            return false;
        }

        list($insql, $inparams) = $DB->get_in_or_equal($this->item->category, SQL_PARAMS_NAMED, 'cg');

        // If subcategories should be included, we have to investigate the whole category sub-path.
        // Unfortunately, as there is no combination of IN and LIKE in SQL, we have to chain up a list of
        // LIKE-statements.
        if (property_exists($this->item, 'category_subcats') && $this->item->category_subcats == true) {
            // Join the course category table.
            $query->join[] = "JOIN {course_categories} cc ON c.category = cc.id";

            // Build a LIKE clause for each selected category.
            $likesqlparts = [];
            foreach ($this->item->category as $subcat) {
                $likesqlparts[] = $DB->sql_like('cc.path', ':pathcat'.$subcat);
                $likeparams['pathcat'.$subcat] = '%/'.$subcat.'/%';
            }
            $likesql = implode(' OR ', $likesqlparts);

            // Add the categories filter to the query.
            $query->where[] = "c.category $insql OR $likesql";
            $query->params += $inparams;
            $query->params += $likeparams;

            // Otherwise, the query is simpler.
        } else {
            // Add the categories filter to the query.
            $query->where[] = "c.category $insql";
            $query->params += $inparams;
        }
    }

    /**
     * Prepare the SQL query to get the enrolments based role.
     * Get user role assignments in the context, roleid should same as selected.
     *
     * @param stdclass $query the database query to modify
     * @return void
     */
    protected function get_enrollmentrole_sql(&$query) {
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
    protected function get_completionstatus_sql(&$query) {
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
                CASE WHEN cc.timecompleted > 0 THEN 'completed'
                    WHEN cc.timestarted > 0 THEN 'inprogress'
                    ELSE 'enrolled'
                    END AS status,
                    e.courseid AS courseid
            FROM {user_enrolments} ue
            LEFT JOIN {enrol} e ON ue.enrolid = e.id
            LEFT JOIN {course_completions} cc ON cc.course = e.courseid AND ue.userid = cc.userid
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
    protected function get_daterange_sql(&$query) {
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
    protected function get_customfield_sql(&$query) {
        global $DB;

        if (empty($this->item->customfields)) {
            return false;
        }

        $customfields = $this->item->customfields ? json_decode($this->item->customfields) : [];

        $i = 0; // Multiple fields unique id for values.
        $params = [];
        $sql = [];

        foreach ($customfields as $shortname => $value) {
            // Filter the null, autocomplete fields dont displayed the empty value, user cannot able to remove the null fields.
            // Therfore remove the 0 or empty values from condition values.
            if (is_array($value)) {
                $value = array_filter($value, function($v) {
                    return $v != 0;
                });
            }

            if ($value == '' || $value === 0 || empty($value)) {
                continue;
            }

            // Select from multiple values for a custom field.
            if (is_array($value)) {
                list($insql, $inparams) = $DB->get_in_or_equal($value, SQL_PARAMS_NAMED, 'val_'.$i);
                $where = "cd.value $insql";
                $params += $inparams;
            } else {
                $where = "cd.value=:value_$i";
                $params += ["value_$i" => $value];
            }

            $i++;
            $sql[] = "
                c.id IN (
                    SELECT instanceid FROM {customfield_data} cd
                    JOIN {customfield_field} cf ON cd.fieldid = cf.id AND cf.shortname = :shortname_$i
                    WHERE $where
                )";
            $params += ["shortname_$i" => $shortname];
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
        global $USER;

        smartmenu_helper::purge_cache_date_reached($this->cache, $this->item, 'itemlastcheckdate');

        $cachekey = "{$this->item->id}_u_{$USER->id}";
        if ($result = $this->cache->get($cachekey)) {
            return $result;
        }

        // Verify the restriction rules.
        if (empty($this->item) || !$this->helper->verify_access_restrictions()) {
            return false;
        }

        // Add marker class to make clear that this is a Boost Union smart menu item.
        $class[] = 'boost-union-smartmenuitem';

        // Add custom CSS class.
        $class[] = $this->item->cssclass;

        // Add classes for hide items in specific viewport.
        $class[] = $this->item->desktop ? 'd-lg-none' : 'd-lg-inline-flex';
        $class[] = $this->item->tablet ? 'd-md-none' : 'd-md-inline-flex';
        $class[] = $this->item->mobile ? 'd-none' : 'd-inline-flex';

        // Add classes for item title placement on card.
        $class[] = $this->get_textposition_class();

        // Add menu item class.
        $types = [self::TYPESTATIC => 'static', self::TYPEDYNAMIC => 'dynamic', self::TYPEHEADING => 'heading'];
        $class[] = 'menu-item-'.($types[$this->item->type] ?? '');

        // Add classes to item data.
        $this->item->classes = $class;

        // Load the location of menu, used to collect menus for locations in menu inline mode.
        $this->item->location = $this->menu->location;

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

        // Save the items cache.
        $this->cache->set($cachekey, $result);

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
     * @param string $itemimage Card image url for item.
     * @param string $sortstring The string to be used for sorting the items.
     *
     * @return array An associative array of node data for the item.
     */
    public function generate_node_data($title, $url, $key = null, $tooltip = null,
        $itemtype = 'link', $haschildren = 0, $children = [], $itemimage = '', $sortstring = '') {

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

        // Generate dynamic image for empty image cards.
        if (empty($itemimage) && $this->menu->type == smartmenu::TYPE_CARD) {
            $itemimage = $this->get_itemimage($this->item->id);
        }

        $data = [
            'itemdata' => $this->item,
            'menuclasses' => $this->item->classes, // If menu is inline, need to add the item custom class in dropdown.
            'location' => $this->menu->location,
            'url' => $url ?: 'javascript:void(0)',
            'key' => $key != null ? $key : 'item-'.$this->item->id,
            'text' => $title,
            // Do not set the title attribute as this would show a standard tooltip based on the Moodle core custom menu logic.
            'title' => '',
            'tooltip' => $tooltip ? format_string($tooltip) : '',
            'haschildren' => $haschildren,
            'itemimage' => $itemimage,
            'itemtype' => 'link',
            'link' => 1,
            'sort' => uniqid(), // Support third level menu.
            'sortstring' => format_string($sortstring),
        ];

        if ($haschildren && !empty($children)) {
            $data['children'] = $children;
        }

        if ($this->item->target == self::TARGET_NEW && $url != '') {
            $data['attributes'] = [[
                'name' => 'target',
                'value' => '__blank',
            ], ];
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
        global $PAGE;

        $coursehandler = \core_course\customfield\course_handler::create();
        foreach ($coursehandler->get_fields() as $field) {
            $shortname = $field->get('shortname');
            $fieldid = $field->get('id');
            $field = \core_customfield\field_controller::create($fieldid);
            $data = \core_customfield\api::get_instance_fields_data([$fieldid => $field], 0);
            // If this field is a textarea, adjust the shortname to include _editor.
            $istextarea = $field->get('type') == 'textarea';
            if ($istextarea) {
                $shortname .= "_editor";
            }
            if (isset($data[$fieldid])) {
                $data = $data[$fieldid];
                $data->instance_form_definition($mform);
                $elem = $mform->getElement("customfield_".$shortname);
                // If this field is a textarea, we'll remove the element and re-add
                // it in a group as textareas can't be conditionally hidden due to a limitation in Moodle core.
                if ($istextarea) {
                    $mform->removeElement("customfield_" . $shortname);
                    $mform->addGroup([$elem], "group_customfield_" . $shortname, $elem->getLabel());
                }
                // Remove the rules for custom fields.
                if (isset($mform->_rules["customfield_".$shortname])) {
                    unset($mform->_rules["customfield_".$shortname]);
                }
                // Remove the custom fields from required sections.
                if (($key = array_search("customfield_".$shortname, $mform->_required)) !== false) {
                    unset($mform->_required[$key]);
                }
                // By default, ensure that no values are pre-set in the form as defaults.
                $default = (isset($mform->_types["customfield_".$shortname])
                    && $mform->_types["customfield_".$shortname]) == 'int' ? 0 : '';

                $mform->setDefault("customfield_".$shortname, $default);
                // Change the password fields type to text, then admin can view the password field as text field.
                if ($elem->_type == 'password') {
                    $elem->_type = 'text';
                }

                // Make the select fields to select multiple.
                if ("select" == $field->get('type') || "semester" == $field->get('type')) {
                    $elem->setMultiple(true);
                    $mform->setDefault("customfield_".$shortname, 0);
                }

                // Hide the field if needed (and distinguish between textareas and other fields here as explained above).
                if ($istextarea) {
                    $mform->hideif("group_customfield_" . $shortname, 'type', 'neq', self::TYPEDYNAMIC);
                } else {
                    $mform->hideif("customfield_" . $shortname, 'type', 'neq', self::TYPEDYNAMIC);
                }
            }
        }

        $PAGE->requires->js_amd_inline('require(["core/form-autocomplete", "core/str"], function(Auto, Str) {
            // List of custom fields.
            var dropdowns = document.querySelectorAll("div[data-fieldtype=select] [id^=id_customfield_]");
            // Fetch no-selection string.
            Str.get_string("noselection", "form").then((noSelection) => {
                dropdowns.forEach((elem) => {
                    elem.classList.add("custom-select");
                    // Change the field type to autcomplete, it fix the suggestion box alignment.
                    elem.parentNode.setAttribute("data-fieldtype", "autocomplete");
                    Auto.enhance(elem, "", false, "", false, true, noSelection);
                });
            });
        })');
    }

    /**
     * Get an array of available types for the item.
     *
     * @param int|null $type Optional. The specific type to retrieve. Defaults to null.
     * @return array|string An array of types if $type is null, or a string with the name of the specific type.
     */
    public static function get_types(?int $type = null) {
        $types = [
                self::TYPESTATIC => get_string('smartmenusmenuitemtypestatic', 'theme_boost_union'),
                self::TYPEHEADING => get_string('smartmenusmenuitemtypeheading', 'theme_boost_union'),
                self::TYPEDYNAMIC => get_string('smartmenusmenuitemtypedynamiccourses', 'theme_boost_union'),
        ];

        return ($type !== null && isset($types[$type])) ? $types[$type] : $types;
    }

    /**
     * Return the options for the display setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_display_options() {
        return [
            self::DISPLAY_SHOWTITLEICON => get_string('smartmenusmenuitemdisplayoptionsshowtitleicon', 'theme_boost_union'),
            self::DISPLAY_HIDETITLE => get_string('smartmenusmenuitemdisplayoptionshidetitle', 'theme_boost_union'),
            self::DISPLAY_HIDETITLEMOBILE => get_string('smartmenusmenuitemdisplayoptionshidetitlemobile', 'theme_boost_union'),
        ];
    }

    /**
     * Return the options for the target setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_target_options(): array {
        return [
            self::TARGET_SAME => get_string('smartmenusmenuitemlinktargetsamewindow', 'theme_boost_union'),
            self::TARGET_NEW => get_string('smartmenusmenuitemlinktargetnewtab', 'theme_boost_union'),
        ];
    }

    /**
     * Return the options for the completionstatus setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_completionstatus_options(): array {
        return [
            self::COMPLETION_ENROLLED =>
                get_string('smartmenusdynamiccoursescompletionstatusenrolled', 'theme_boost_union'),
            self::COMPLETION_INPROGRESS =>
                get_string('smartmenusdynamiccoursescompletionstatusinprogress', 'theme_boost_union'),
            self::COMPLETION_COMPLETED =>
                get_string('smartmenusdynamiccoursescompletionstatuscompleted', 'theme_boost_union'),
        ];
    }

    /**
     * Return the options for the daterange setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_daterange_options(): array {
        return [
            self::RANGE_PAST =>
                get_string('smartmenusdynamiccoursesdaterangepast', 'theme_boost_union'),
            self::RANGE_PRESENT =>
                get_string('smartmenusdynamiccoursesdaterangepresent', 'theme_boost_union'),
            self::RANGE_FUTURE =>
                get_string('smartmenusdynamiccoursesdaterangefuture', 'theme_boost_union'),
        ];
    }

    /**
     * Return the options for the listsort setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_listsort_options(): array {
        return [
            self::LISTSORT_FULLNAME_ASC =>
                get_string('smartmenusmenuitemlistsortfullnameasc', 'theme_boost_union'),
            self::LISTSORT_FULLNAME_DESC =>
                get_string('smartmenusmenuitemlistsortfullnamedesc', 'theme_boost_union'),
            self::LISTSORT_SHORTNAME_ASC =>
                get_string('smartmenusmenuitemlistsortshortnameasc', 'theme_boost_union'),
            self::LISTSORT_SHORTNAME_DESC =>
                get_string('smartmenusmenuitemlistsortshortnamedesc', 'theme_boost_union'),
            self::LISTSORT_COURSEID_ASC =>
                get_string('smartmenusmenuitemlistsortcourseidasc', 'theme_boost_union'),
            self::LISTSORT_COURSEID_DESC =>
                get_string('smartmenusmenuitemlistsortcourseiddesc', 'theme_boost_union'),
            self::LISTSORT_COURSEIDNUMBER_ASC =>
                get_string('smartmenusmenuitemlistsortcourseidnumberasc', 'theme_boost_union'),
            self::LISTSORT_COURSEIDNUMBER_DESC =>
                get_string('smartmenusmenuitemlistsortcourseidnumberdesc', 'theme_boost_union'),
        ];
    }

    /**
     * Return the options for the displayfield setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_displayfield_options(): array {
        return [
            self::FIELD_FULLNAME => get_string('smartmenusmenuitemdisplayfieldcoursefullname', 'theme_boost_union'),
            self::FIELD_SHORTNAME => get_string('smartmenusmenuitemdisplayfieldcourseshortname', 'theme_boost_union'),
        ];
    }

    /**
     * Return the options for the mode setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_mode_options(): array {
        return [
            self::MODE_INLINE => get_string('smartmenusmodeinline', 'theme_boost_union'),
            self::MODE_SUBMENU => get_string('smartmenusmodesubmenu', 'theme_boost_union'),
        ];
    }

    /**
     * Return the options for the testposition setting.
     *
     * @return array
     * @throws \coding_exception
     */
    public static function get_textposition_options(): array {
        return [
            self::POSITION_BELOW =>
                get_string('smartmenusmenuitemtextpositionbelowimage', 'theme_boost_union'),
            self::POSITION_OVERLAYTOP =>
                get_string('smartmenusmenuitemtextpositionoverlaytop', 'theme_boost_union'),
            self::POSITION_OVERLAYBOTTOM =>
                get_string('smartmenusmenuitemtextpositionoverlaybottom', 'theme_boost_union'),
        ];
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

        // Cache for menus.
        $menucache = cache::make('theme_boost_union', 'smartmenus');
        // Cache for menu items.
        $cache = cache::make('theme_boost_union', 'smartmenu_items');

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
                    'menuid' => $formdata->menu,
                ]);
            }

            // Delete the cached data of its menu. Menu will recreate with this item.
            $menucache->delete_menu($formdata->menu);
            // Purge the current item cache for all users.
            $cache->delete_menu($formdata->id);

            // Show the edited success notification.
            \core\notification::success(get_string('smartmenusmenuitemeditsuccess', 'theme_boost_union'));
        } else {
            $record->sortorder = $record->sortorder ?: 1;
            $itemid = $DB->insert_record('theme_boost_union_menuitems', $record);
            // Setup the order for item.
            $sql = "UPDATE {theme_boost_union_menuitems}
                SET sortorder = sortorder + 1
                WHERE sortorder >= :sortorder AND id != :item AND menu=:menuid";

            $DB->execute($sql, ['sortorder' => $record->sortorder, 'item' => $itemid, 'menuid' => $record->menu]);
            // Show the menu item inserted success notification.
            \core\notification::success(get_string('smartmenusmenuitemcreatesuccess', 'theme_boost_union'));

            // Delete the cached data of its menu. Menu will recreate with this item.
            $menucache->delete_menu($formdata->menu);
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
                self::image_filepickeroptions()
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
    public static function image_filepickeroptions() {

        return [
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => 'web_image',
        ];
    }

}
