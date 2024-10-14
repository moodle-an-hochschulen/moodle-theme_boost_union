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

namespace theme_boost_union;

use core\navigation\views\view;
use navigation_node;
use moodle_url;

/**
 * Creates a navbar for boost union that allows easy control of the navbar items.
 *
 * This class is copied and modified from /theme/boost/classes/boostnavbar.php
 *
 * @package    theme_boost_union
 * @copyright  2023 Luca Bösch <luca.boesch@bfh.ch>
 * @copyright  based on code from theme_boost by Adrian Greeve
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class boostnavbar extends \theme_boost\boostnavbar {

    /**
     * Prepares the navigation nodes for use with boost.
     *
     * This function is amended with the category composing code from
     * get_course_categories() in lib/navigation.lib
     */
    protected function prepare_nodes_for_boost(): void {
        global $PAGE;

        // Remove the navbar nodes that already exist in the primary navigation menu.
        $this->remove_items_that_exist_in_navigation($PAGE->primarynav);

        // Defines whether section items with an action should be removed by default.
        $removesections = true;

        if ($this->page->context->contextlevel == CONTEXT_COURSECAT) {
            // Remove the 'Permissions' navbar node in the Check permissions page.
            if ($this->page->pagetype === 'admin-roles-check') {
                $this->remove('permissions');
            }
        }
        if ($this->page->context->contextlevel == CONTEXT_COURSE) {
            if (get_config('theme_boost_union', 'categorybreadcrumbs') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                // Create the categories breadcrumb navigation nodes.
                $categorynodes = [];
                foreach (array_reverse($this->get_categories()) as $category) {
                    $context = \context_coursecat::instance($category->id);
                    if (!\core_course_category::can_view_category($category)) {
                        continue;
                    }

                    $displaycontext = \context_helper::get_navigation_filter_context($context);
                    $url = new moodle_url('/course/index.php', ['categoryid' => $category->id]);
                    $name = format_string($category->name, true, ['context' => $displaycontext]);
                    $categorynode = \breadcrumb_navigation_node::create($name, $url, \breadcrumb_navigation_node::TYPE_CATEGORY,
                            null, $category->id);
                    if (!$category->visible) {
                        $categorynode->hidden = true;
                    }
                    $categorynodes[] = $categorynode;
                }
                $itemswithcategories = [];
                if (!$this->items) {
                    $itemswithcategories = $categorynodes;
                } else {
                    foreach ($this->items as $item) {
                        if ($item->type == \breadcrumb_navigation_node::TYPE_COURSE) {
                            $itemswithcategories = array_merge($itemswithcategories, $categorynodes);
                        }
                        $itemswithcategories[] = $item;
                    }
                }
                $this->items = $itemswithcategories;
            }

            // Remove any duplicate navbar nodes.
            $this->remove_duplicate_items();
            // Remove 'My courses' and 'Courses' if we are in the course context.
            $this->remove('mycourses');
            $this->remove('courses');

            switch (get_config('theme_boost_union', 'categorybreadcrumbs')) {
                case THEME_BOOST_UNION_SETTING_SELECT_NO:
                    // Remove the course category breadcrumb nodes.
                    foreach ($this->items as $key => $item) {
                        // Remove if it is a course category breadcrumb node.
                        $this->remove($item->key, \breadcrumb_navigation_node::TYPE_CATEGORY);
                    }
                case THEME_BOOST_UNION_SETTING_SELECT_YES:
                    break;
            }
            // Remove the course breadcrumb node.
            if (!str_starts_with($this->page->pagetype, 'course-view-section-')) {
                $this->remove($this->page->course->id, \breadcrumb_navigation_node::TYPE_COURSE);
            }
            // Remove the navbar nodes that already exist in the secondary navigation menu.
            $this->remove_items_that_exist_in_navigation($PAGE->secondarynav);

            switch ($this->page->pagetype) {
                case 'group-groupings':
                case 'group-grouping':
                case 'group-overview':
                case 'group-assign':
                    // Remove the 'Groups' navbar node in the Groupings, Grouping, group Overview and Assign pages.
                    $this->remove('groups');
                case 'backup-backup':
                case 'backup-restorefile':
                case 'backup-copy':
                case 'course-reset':
                    // Remove the 'Import' navbar node in the Backup, Restore, Copy course and Reset pages.
                    $this->remove('import');
                case 'course-user':
                    $this->remove('mygrades');
                    $this->remove('grades');
            }
        }

        // Remove 'My courses' if we are in the module context.
        if ($this->page->context->contextlevel == CONTEXT_MODULE) {
            $this->remove('mycourses');
            $this->remove('courses');
            // Remove the course category breadcrumb nodes.
            foreach ($this->items as $key => $item) {
                // Remove if it is a course category breadcrumb node.
                $this->remove($item->key, \breadcrumb_navigation_node::TYPE_CATEGORY);
            }
            $courseformat = course_get_format($this->page->course);
            $removesections = $courseformat->can_sections_be_removed_from_navigation();
            if ($removesections) {
                // If the course sections are removed, we need to add the anchor of current section to the Course.
                $coursenode = $this->get_item($this->page->course->id);
                if (!is_null($coursenode) && $this->page->cm->sectionnum !== null) {
                    $coursenode->action = course_get_format($this->page->course)->get_view_url($this->page->cm->sectionnum);
                }
            }
        }

        if ($this->page->context->contextlevel == CONTEXT_SYSTEM) {
            // Remove the navbar nodes that already exist in the secondary navigation menu.
            $this->remove_items_that_exist_in_navigation($PAGE->secondarynav);
        }

        // Set the designated one path for courses.
        $mycoursesnode = $this->get_item('mycourses');
        if (!is_null($mycoursesnode)) {
            $url = new \moodle_url('/my/courses.php');
            $mycoursesnode->action = $url;
            $mycoursesnode->text = get_string('mycourses');
        }

        $this->remove_no_link_items($removesections);

        // Don't display the navbar if there is only one item. Apparently this is bad UX design.
        // Except, leave it in when in course context and categorybreadcrumbs are desired.
        if (!(get_config('theme_boost_union', 'categorybreadcrumbs') == THEME_BOOST_UNION_SETTING_SELECT_YES &&
                $this->page->context->contextlevel == CONTEXT_COURSE)) {
            if ($this->item_count() <= 1) {
                $this->clear_items();
                return;
            }
        }

        // Make sure that the last item is not a link. Not sure if this is always a good idea.
        // Except, leave it when categorybreadcrumbs are desired and if we are on a course page.
        if (!(get_config('theme_boost_union', 'categorybreadcrumbs') == THEME_BOOST_UNION_SETTING_SELECT_YES &&
                $this->page->context->contextlevel == CONTEXT_COURSE)) {
            $this->remove_last_item_action();
        }
    }

    /**
     * Get the course categories.
     *
     * @return boostnavbaritem[] Boost navbar items.
     */
    public function get_categories(): array {
        return $this->page->categories;
    }

    /**
     * Remove a boostnavbaritem from the boost navbar.
     *
     * @param  string|int $itemkey An identifier for the boostnavbaritem
     * @param  int|null $itemtype An additional type identifier for the boostnavbaritem (optional)
     */
    protected function remove($itemkey, ?int $itemtype = null): void {

        $itemfound = false;
        foreach ($this->items as $key => $item) {
            if ($item->key === $itemkey) {
                // If a type identifier is also specified, check whether the type of the breadcrumb item matches the
                // specified type. Skip if types to not match.
                if (!is_null($itemtype) && $item->type !== $itemtype) {
                    continue;
                }
                unset($this->items[$key]);
                $itemfound = true;
                break;
            }
        }
        if (!$itemfound) {
            return;
        }

        $itemcount = $this->item_count();
        if ($itemcount <= 0) {
            return;
        }

        $this->items = array_values($this->items);
        // Set the last item to last item if it is not.
        $lastitem = $this->items[$itemcount - 1];
        if (is_a($lastitem, 'breadcrumb_navigation_node') && !$lastitem->is_last()) {
            $lastitem->set_last(true);
        }
    }

    /**
     * Removes the action from the last item of the boostnavbaritem.
     */
    protected function remove_last_item_action(): void {
        $item = end($this->items);
        if (is_a($item, 'breadcrumb_navigation_node')) {
            $item->action = null;
        }
        reset($this->items);
    }

    /**
     * Remove items that have no actions associated with them and optionally remove items that are sections.
     *
     * The only exception is the last item in the list which may not have a link but needs to be displayed.
     *
     * @param bool $removesections Whether section items should be also removed (only applies when they have an action)
     */
    protected function remove_no_link_items(bool $removesections = true): void {
        foreach ($this->items as $key => $value) {
            if (isset($lastitem) && is_a($lastitem, 'breadcrumb_navigation_node') && !$value->is_last() &&
                    (!$value->has_action() || ($value->type == \navigation_node::TYPE_SECTION && $removesections))) {
                unset($this->items[$key]);
            }
        }
        $this->items = array_values($this->items);
    }
}
