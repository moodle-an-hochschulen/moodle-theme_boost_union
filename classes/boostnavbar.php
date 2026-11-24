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

use navigation_node;
use moodle_url;

/**
 * Creates a navbar for boost union that allows easy control of the navbar items.
 *
 * This class is copied and modified from /theme/boost/classes/boostnavbar.php
 *
 * @package    theme_boost_union
 * @copyright  2023 Luca Bösch <luca.boesch@bfh.ch>
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @copyright  based on code from theme_boost by Adrian Greeve
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class boostnavbar extends \theme_boost\boostnavbar {
    /**
     * Prepares the navigation nodes for use with boost.
     *
     * This function is copied and modified from prepare_nodes_for_boost() in /theme/boost/classes/boostnavbar.php.
     * All modifications are marked with 'Boost Union:' comments.
     */
    protected function prepare_nodes_for_boost(): void {
        global $PAGE;

        // Remove the navbar nodes that already exist in the primary navigation menu.
        $this->remove_items_that_exist_in_navigation($PAGE->primarynav);

        // Defines whether section items with an action should be removed by default.
        $removesections = true;

        // Boost Union: Check if the category breadcrumbs should be shown in the course context.
        $showcategorybreadcrumbs = ($this->page->context->contextlevel == CONTEXT_COURSE &&
                get_config('theme_boost_union', 'categorybreadcrumbs') == THEME_BOOST_UNION_SETTING_SELECT_YES);

        if ($this->page->context->contextlevel == CONTEXT_COURSECAT) {
            // Remove the 'Permissions' navbar node in the Check permissions page.
            if ($this->page->pagetype === 'admin-roles-check') {
                $this->remove('permissions');
            }
        }
        if ($this->page->context->contextlevel == CONTEXT_COURSE) {
            $removesections = course_get_format($this->page->course)->can_sections_be_removed_from_navigation();
            // Remove any duplicate navbar nodes.
            $this->remove_duplicate_items();
            // Remove 'My courses' and 'Courses' if we are in the course context.
            $this->remove('mycourses');
            $this->remove('courses');
            // Remove the course category breadcrumb nodes.
            foreach ($this->items as $key => $item) {
                // Remove if it is a course category breadcrumb node.
                $this->remove($item->key, \breadcrumb_navigation_node::TYPE_CATEGORY);
            }
            // Boost Union: Add our own course category breadcrumb nodes if they are desired.
            if ($showcategorybreadcrumbs) {
                $this->add_category_items();
            }
            // Remove the course breadcrumb node.
            if (!str_starts_with($this->page->pagetype, 'course-view-section-')) {
                $this->remove($this->page->course->id, \breadcrumb_navigation_node::TYPE_COURSE);
            }
            // Boost Union: Make sure that the breadcrumb ends with the section which is shown on the section page.
            // This is a workaround for a glitch in Moodle core (MDL-90146). It is applied regardless of the
            // categorybreadcrumbs setting as it fixes a wrong breadcrumb which is also wrong in Boost core.
            if (str_starts_with($this->page->pagetype, 'course-view-section-')) {
                $this->fix_section_page_item();
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
                    // PhpCS complains here: "There must be a comment when fall-through is intentional in a non-empty case body."
                    // However, this code is copied as-is from theme/boost/classes/boostnavbar.php.
                    // So we leave a comment here to make phpcs happy.
                case 'backup-backup':
                case 'backup-restorefile':
                case 'backup-copy':
                case 'course-reset':
                    // Remove the 'Import' navbar node in the Backup, Restore, Copy course and Reset pages.
                    $this->remove('import');
                    // PhpCS complains here: "There must be a comment when fall-through is intentional in a non-empty case body."
                    // However, this code is copied as-is from theme/boost/classes/boostnavbar.php.
                    // So we leave a comment here to make phpcs happy.
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

                // Module types not visible on the course main page cannot have a section breadcrumb.
                if (!$this->page->cm->is_of_type_that_can_display() && $item->type === navigation_node::TYPE_SECTION) {
                    $this->remove($item->key, \breadcrumb_navigation_node::TYPE_SECTION);
                }
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
        // Boost Union: Except, leave it in when in course context and categorybreadcrumbs are desired as the navbar might
        // consist of a single category item there.
        if ($this->item_count() <= 0 || ($this->item_count() <= 1 && !$showcategorybreadcrumbs)) {
            $this->clear_items();
            return;
        }

        // Make sure that the last item is not a link. Not sure if this is always a good idea.
        // Boost Union: Except, leave it when categorybreadcrumbs are desired and the last item is a category item.
        // This is the case on the course main page where the category breadcrumbs are the only items in the navbar
        // and should remain clickable. On all other pages, the last item is the current page and should not be a link,
        // just like in Boost core.
        $lastitem = end($this->items);
        reset($this->items);
        if (!($showcategorybreadcrumbs && $lastitem->type === \breadcrumb_navigation_node::TYPE_CATEGORY)) {
            $this->remove_last_item_action();
        }
    }

    /**
     * Boost Union: Add the course category breadcrumb nodes to the navbar items.
     *
     * The category nodes are inserted in front of the course node (if there is one) or prepended to the navbar items
     * otherwise. This process is copied and modified from get_course_categories() in lib/navigationlib.php.
     * The category nodes which might have been in the navbar already have been removed before by the Boost core code.
     */
    protected function add_category_items(): void {
        // Create the category breadcrumb navigation nodes.
        $categorynodes = [];
        foreach (array_reverse($this->page->categories) as $category) {
            $context = \context_coursecat::instance($category->id);
            if (!\core_course_category::can_view_category($category)) {
                continue;
            }

            $displaycontext = \context_helper::get_navigation_filter_context($context);
            $url = new moodle_url('/course/index.php', ['categoryid' => $category->id]);
            $name = format_string($category->name, true, ['context' => $displaycontext]);
            $categorynode = new \breadcrumb_navigation_node(navigation_node::create(
                $name,
                $url,
                \breadcrumb_navigation_node::TYPE_CATEGORY,
                null,
                $category->id
            ));
            if (!$category->visible) {
                $categorynode->hidden = true;
            }
            $categorynodes[] = $categorynode;
        }

        // Insert the category nodes in front of the course node.
        $itemswithcategories = [];
        $inserted = false;
        foreach ($this->items as $item) {
            if (!$inserted && $item->type == \breadcrumb_navigation_node::TYPE_COURSE) {
                $itemswithcategories = array_merge($itemswithcategories, $categorynodes);
                $inserted = true;
            }
            $itemswithcategories[] = $item;
        }
        // If there wasn't any course node, prepend the category nodes.
        if (!$inserted) {
            $itemswithcategories = array_merge($categorynodes, $itemswithcategories);
        }
        $this->items = $itemswithcategories;
    }

    /**
     * Boost Union: Make sure that the last section item in the breadcrumb on a section page is the section which is shown.
     *
     * Some course formats (for example the topics and weeks formats) remove the section node of the general section
     * from the navigation if the general section is empty. If the section page of such an empty general section is opened,
     * Moodle core does not find a navigation node for the current page and falls back to the first section node
     * with a matching base URL. As a result, the breadcrumb ends with the name of the first section after the general
     * section instead of the general section itself. If there is no other section node to fall back to (for example
     * in a course which only consists of the general section), the breadcrumb ends with the course and does not contain
     * any section at all.
     * This glitch is reported in MDL-90146.
     * The same wrong fallback happens on the section page of a delegated section (i.e. a subsection), where the breadcrumb
     * does not end with the subsection.
     * This function detects these situations and replaces the wrong section item with a section item for the current section
     * or appends the section item for the current section to the course item. For a subsection, the parent section is
     * placed in front of the subsection.
     * It is called regardless of the categorybreadcrumbs setting. This is a deliberate deviation from the principle that
     * Boost Union behaves like Boost core if a setting is disabled, as the breadcrumb is simply wrong in Boost core here.
     * As soon as MDL-90146 is fixed in all Moodle core versions supported by Boost Union, this workaround can be removed.
     */
    protected function fix_section_page_item(): void {
        // Get the section which is shown on the section page.
        $sectionid = $this->page->url->param('id');
        if (empty($sectionid)) {
            return;
        }

        // Get the last navbar item and check if it is a section item for another section (which is the case if Moodle core
        // fell back to another section node) or if it is the course item (which is the case if Moodle core did not find
        // any other section node to fall back to, for example in a course which only consists of the general section).
        $lastitem = end($this->items);
        reset($this->items);
        if (!$lastitem) {
            return;
        }
        $iswrongsection = ($lastitem->type === \breadcrumb_navigation_node::TYPE_SECTION &&
                (int) $lastitem->key !== (int) $sectionid);
        $iscourse = ($lastitem->type === \breadcrumb_navigation_node::TYPE_COURSE &&
                (int) $lastitem->key === (int) $this->page->course->id);
        if (!$iswrongsection && !$iscourse) {
            return;
        }

        // Get the section info of the section which is shown on the section page.
        $modinfo = get_fast_modinfo($this->page->course);
        $section = $modinfo->get_section_info_by_id($sectionid, IGNORE_MISSING);
        if (!$section) {
            return;
        }

        // If the current section is a delegated section (i.e. a subsection), get its parent section as the parent section
        // has to be shown in front of the current section.
        $parentsection = $section->get_component_instance()?->get_parent_section();
        $lastisparent = ($iswrongsection && $parentsection && (int) $lastitem->key === (int) $parentsection->id);

        // Remove the wrong section item, unless it is the parent section of the current section.
        if ($iswrongsection && !$lastisparent) {
            array_pop($this->items);
        }
        // Append the section item for the parent section, unless it is already there.
        if ($parentsection && !$lastisparent) {
            $this->items[] = $this->create_section_item($parentsection);
        }
        // Append the section item for the current section and flag it as last item.
        foreach ($this->items as $item) {
            $item->set_last(false);
        }
        $sectionnode = $this->create_section_item($section);
        $sectionnode->set_last(true);
        $this->items[] = $sectionnode;
    }

    /**
     * Boost Union: Create a breadcrumb item for a given section.
     *
     * The item is built in the same way as the section nodes are built in load_section_navigation() in
     * lib/navigationlib.php.
     *
     * @param \section_info $section The section.
     * @return \breadcrumb_navigation_node The breadcrumb item.
     */
    protected function create_section_item(\section_info $section): \breadcrumb_navigation_node {
        $format = course_get_format($this->page->course);
        $sectionnode = new \breadcrumb_navigation_node(navigation_node::create(
            $format->get_section_name($section),
            $format->get_view_url($section, ['navigation' => true]),
            \breadcrumb_navigation_node::TYPE_SECTION,
            null,
            $section->id
        ));
        $sectionnode->hidden = (!$section->visible || !$section->available);
        return $sectionnode;
    }
}
