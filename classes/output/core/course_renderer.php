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

namespace theme_boost_union\output\core;

use html_writer;
use coursecat_helper;
use lang_string;
use moodle_url;
use stdClass;
use core_course_category;
use core_course_list_element;
use theme_boost_union\util\course;

/**
 * Theme Boost Union - Course renderer
 *
 * @package    theme_boost_union
 * @copyright  2024 Daniel Neis Araujo {@link https://www.adapta.online}
 *             2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 *             based on code 2010 Sam Hemelryk
 *             based on code 2022 Willian Mano {@link https://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_renderer extends \core_course_renderer {
    /**
     * Override the constructor so that we can inject the AMD modal.
     *
     * @param \moodle_page $page
     * @param string $target
     */
    public function __construct(\moodle_page $page, $target) {
        // If the course listing details modal is enabled and should be shown, add the necessary JS.
        // This has to be done here even if categorylistingpresentation is set to nochange to make sure that
        // the JS is loaded in any case.
        static $detailsmodalchecked = null;
        if ($detailsmodalchecked == null) {
            $courselistingpresentation = get_config('theme_boost_union', 'courselistingpresentation');
            $courselistinghowpopup = get_config('theme_boost_union', 'courselistinghowpopup');
            if (isset($courselistingpresentation) &&
                    $courselistingpresentation != THEME_BOOST_UNION_SETTING_COURSELISTPRES_NOCHANGE &&
                    isset($courselistinghowpopup) &&
                    $courselistinghowpopup == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                $page->requires->js_call_amd('theme_boost_union/courselistingdetailsmodal', 'init');
            }
            $detailsmodalchecked = true;
        }

        // Call parent constructor.
        parent::__construct($page, $target);
    }

    /**
     * Renders the list of courses
     *
     * This is internal function, please use \core_course_renderer::courses_list() or another public
     * method from outside of the class
     *
     * If list of courses is specified in $courses; the argument $chelper is only used
     * to retrieve display options and attributes, only methods get_show_courses(),
     * get_courses_display_option() and get_and_erase_attributes() are called.
     *
     * Modifications compared to the original function:
     * * Build the modified course listing if enabled, otherwise call the parent function to build the default view.
     * * Show the category name in the course listing if enabled.
     * * In 'auto' course display mode, always show the category in expanded mode.
     *
     * @param coursecat_helper $chelper various display options
     * @param array $courses the list of courses to display
     * @param int|null $totalcount total number of courses (affects display mode if it is AUTO or pagination if applicable),
     *     defaulted to count($courses)
     * @return string
     */
    protected function coursecat_courses(coursecat_helper $chelper, $courses, $totalcount = null) {
        // If the course listing should remain unchanged.
        $courselistingpresentation = get_config('theme_boost_union', 'courselistingpresentation');
        if (!isset($courselistingpresentation) || $courselistingpresentation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_NOCHANGE) {
            // Call the parent function to present the default view.
            return parent::coursecat_courses($chelper, $courses, $totalcount);
        }
        global $CFG;
        if ($totalcount === null) {
            $totalcount = count($courses);
        }
        if (!$totalcount) {
            // Courses count is cached during courses retrieval.
            return '';
        }
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_AUTO) {
            // In 'auto' course display mode we always show the category in expanded mode.
            // This is done to avoid that sticky headers appear on the category overview page if $CFG->courseswithsummarieslimit
            // is set to a too small value.
            $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED);
        }

        // Prepare content of paging bar if it is needed.
        $paginationurl = $chelper->get_courses_display_option('paginationurl');
        $paginationallowall = $chelper->get_courses_display_option('paginationallowall');
        if ($totalcount > count($courses)) {
            // There are more results that can fit on one page.
            if ($paginationurl) {
                // The option paginationurl was specified, display pagingbar.
                $perpage = $chelper->get_courses_display_option('limit', $CFG->coursesperpage);
                $page = $chelper->get_courses_display_option('offset') / $perpage;
                $pagingbar = $this->paging_bar($totalcount, $page, $perpage,
                    $paginationurl->out(false, ['perpage' => $perpage]));
                if ($paginationallowall) {
                    $pagingbar .= html_writer::tag('div', html_writer::link($paginationurl->out(false, ['perpage' => 'all']),
                        get_string('showall', '', $totalcount)), ['class' => 'paging paging-showall']);
                }
            } else if ($viewmoreurl = $chelper->get_courses_display_option('viewmoreurl')) {
                // The option for 'View more' link was specified, display more link.
                $viewmoretext = $chelper->get_courses_display_option('viewmoretext', new \lang_string('viewmore'));
                $morelink = html_writer::tag(
                    'div',
                    html_writer::link($viewmoreurl, $viewmoretext, ['class' => 'btn btn-secondary']),
                    ['class' => 'paging paging-morelink']
                );
            }
        } else if (($totalcount > $CFG->coursesperpage) && $paginationurl && $paginationallowall) {
            // There are more than one page of results and we are in 'view all' mode, suggest to go back to paginated view mode.
            $pagingbar = html_writer::tag('div',
                html_writer::link($paginationurl->out(false, ['perpage' => $CFG->coursesperpage]),
                    get_string('showperpage', '', $CFG->coursesperpage)), ['class' => 'paging paging-showperpage']);
        }

        // Display list of courses.
        $attributes = $chelper->get_and_erase_attributes('courses');
        $content = html_writer::start_tag('div', $attributes);

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }

        // Check if we should show the category sticky headers.
        $categorylistingpresentation = get_config('theme_boost_union', 'categorylistingpresentation');
        if (isset($categorylistingpresentation) && $categorylistingpresentation == THEME_BOOST_UNION_SETTING_CATLISTPRES_BOXLIST &&
                $chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COLLAPSED) {
            $showstickyheaders = true;
        } else {
            $showstickyheaders = false;
        }

        // If course cards are enabled.
        if ($courselistingpresentation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_CARDS) {
            // Start the course listing as card grid.
            // And add the theme_boost_union-courselisting class to be used in the CSS.
            $content .= html_writer::start_tag('div',
                    [
                        'class' => 'row no-gutters theme_boost_union-courselisting theme_boost_union-courselisting-card',
                        'role' => 'list',
                    ]
            );
            $content .= html_writer::start_tag('div', ['class' => 'col-12']);

            // Initialize the category id.
            $cat = null;

            // Initialize the card grid started flag.
            $cardgridstarted = false;

            // Get the card grid config.
            $maxcols = get_config('theme_boost_union', 'coursecardscolumncount');
            $maxcolslg = $maxcols;
            $maxcolssm = ($maxcols > 1) ? $maxcols : 1;

            // Iterate over the courses.
            foreach ($courses as $course) {
                // If we are looking at a new category.
                if ($cat == null || $cat->id != $course->category) {
                    // End the previous course card grid if necessary.
                    if ($showstickyheaders == true && $cat != null) {
                        $content .= html_writer::end_tag('div');
                    }

                    // Set the category id.
                    $cat = $course->category;

                    // Get the category.
                    $cat = \core_course_category::get($course->category, IGNORE_MISSING);

                    // Show the category heading as sticky header, if necessary.
                    if ($showstickyheaders == true) {
                        $content .= html_writer::start_tag('div',
                                ['class' =>
                                        'theme_boost_union-stickycategory bg-white rounded-bottom mb-3 pt-3 mx-1 px-0 sticky-top']);
                        $content .= html_writer::start_tag('div', ['class' => 'border rounded px-3 pt-3 pb-2 bg-light']);
                        $content .= html_writer::tag('h6', $cat->name);
                        $content .= html_writer::end_div();
                        $content .= html_writer::end_div();
                    }

                    // Start a new course card grid, if necessary.
                    // Use the same wrapper classes as in /course/templates/coursecards.mustache.
                    // Set the row-cols-lg class depending on the coursecardscolumncount setting.
                    if ($showstickyheaders == true || $cardgridstarted == false) {
                        $content .= html_writer::start_tag('div',
                                ['class' =>
                                        'card-grid row no-gutters row-cols-1 row-cols-sm-'.$maxcolssm.' row-cols-lg-'.$maxcolslg,
                                  'role' => 'list',
                                ]
                        );
                        $cardgridstarted = true;
                    }
                }

                // Build the course card.
                // Use the same wrapper classes as in /course/templates/coursecards.mustache.
                $content .= html_writer::start_tag('div', ['class' => 'col d-flex px-0 mb-2']);
                $content .= $this->coursecat_coursebox($chelper, $course);
                $content .= html_writer::end_tag('div');
            }

            // End the course card grid, if there were any courses.
            if (count($courses) > 0) {
                $content .= html_writer::end_tag('div');
            }

            // End the course listing.
            $content .= html_writer::end_tag('div');
            $content .= html_writer::end_tag('div');

            // Or if the course list is enabled.
        } else if ($courselistingpresentation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_LIST) {
            // Start the course listing as course list.
            // And add the theme_boost_union-courselisting class to be used in the CSS.
            $content .= html_writer::start_tag('div',
                    [
                        'class' => 'theme_boost_union-courselisting theme_boost_union-courselisting-list',
                        'role' => 'list',
                    ]
            );

            // Initialize the category id.
            $cat = null;

            // Iterate over the courses.
            foreach ($courses as $course) {
                // If we are looking at a new category.
                if ($cat == null || $cat->id != $course->category) {
                    // End the previous category list if necessary.
                    if ($showstickyheaders == true && $cat != null) {
                        $content .= html_writer::end_tag('div');
                    }

                    // Set the category id.
                    $cat = $course->category;

                    // Get the category.
                    $cat = \core_course_category::get($course->category, IGNORE_MISSING);

                    // Start the category list.
                    $content .= html_writer::start_div('row no-gutters categorylist');

                    // Show the category heading as sticky header, if necessary.
                    if ($showstickyheaders == true) {
                        $content .= html_writer::start_tag('div',
                                ['class' =>
                                        'theme_boost_union-stickycategory col-12 bg-white rounded-bottom mb-3 pt-3 sticky-top']);
                        $content .= html_writer::start_tag('div', ['class' => 'border rounded px-3 pt-3 pb-2 bg-light']);
                        $content .= html_writer::tag('h6', $cat->name);
                        $content .= html_writer::end_div();
                        $content .= html_writer::end_div();
                    }
                }

                // Build the course list.
                $content .= html_writer::start_tag('div', ['class' => 'col-12']);
                $content .= $this->coursecat_coursebox($chelper, $course);
                $content .= html_writer::end_tag('div');
            }

            // End the category list, if there were any courses.
            if (count($courses) > 0) {
                $content .= html_writer::end_tag('div');
            }

            // End the course listing.
            $content .= html_writer::end_tag('div');
        }

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }
        if (!empty($morelink)) {
            $content .= $morelink;
        }

        $content .= html_writer::end_tag('div'); // End courses.

        return $content;
    }

    /**
     * Displays one course in the list of courses.
     *
     * This is an internal function, to display an information about just one course
     * please use \core_course_renderer::course_info_box()
     *
     * Modifications compared to the original function:
     * * Show the course listing view if enabled, otherwise call the parent function to present the default view.
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_list_element|stdClass $course
     * @param string $additionalclasses additional classes to add to the main <div> tag (usually
     *    depend on the course position in list - first/last/even/odd)
     * @return string
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        // If the course listing should remain unchanged.
        $courselistingpresentation = get_config('theme_boost_union', 'courselistingpresentation');
        if (!isset($courselistingpresentation) || $courselistingpresentation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_NOCHANGE) {
            // Call the parent function to present the default view.
            return parent::coursecat_coursebox($chelper, $course, $additionalclasses);
        }

        if (!isset($this->strings->summary)) {
            $this->strings->summary = get_string('summary');
        }
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof stdClass) {
            $course = new core_course_list_element($course);
        }

        // Render the course box.
        $content = $this->coursecat_coursebox_content($chelper, $course);

        // And return it.
        return $content;
    }

    /**
     * Returns HTML to display course content (summary, course contacts and optionally category name)
     *
     * This method is called from coursecat_coursebox() and may be re-used in AJAX
     *
     * Modifications compared to the original function:
     * * Compose the course listing view if enabled, otherwise call the parent function to compose the default view.
     *
     * @param coursecat_helper $chelper various display options
     * @param stdClass|core_course_list_element $course
     * @return string
     */
    protected function coursecat_coursebox_content(coursecat_helper $chelper, $course) {
        // If the course listing should remain unchanged.
        $courselistingpresentation = get_config('theme_boost_union', 'courselistingpresentation');
        if (!isset($courselistingpresentation) || $courselistingpresentation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_NOCHANGE) {
            // Call the parent function to compose the default view.
            return parent::coursecat_coursebox_content($chelper, $course);
        }

        // Prepare a static skeleton for the course listing templatedata.
        // This is done to avoid that we check the same stuff for every card / list element again and again.
        static $skeleton;
        if ($skeleton == null) {
            $skeleton = [];

            // Enable course image, if configured.
            if (get_config('theme_boost_union', 'courselistinghowimage') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                $skeleton['showcourseimage'] = true;
            } else {
                $skeleton['showcourseimage'] = false;
            }

            // Enable course contacts, if configured.
            if (get_config('theme_boost_union', 'courselistingshowcontacts') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                $skeleton['showcoursecontacts'] = true;
            } else {
                $skeleton['showcoursecontacts'] = false;
            }

            // Enable course shortname, if configured.
            if (get_config('theme_boost_union', 'courselistinghowshortname') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                $skeleton['showshortname'] = true;
            } else {
                $skeleton['showshortname'] = false;
            }

            // Enable course category, if configured.
            if (get_config('theme_boost_union', 'courselistinghowcategory') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                $skeleton['showcoursecategory'] = true;
            } else {
                $skeleton['showcoursecategory'] = false;
            }

            // Enable course goto button, if configured.
            if (get_config('theme_boost_union', 'courselistinghowgoto') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                $skeleton['showcoursegoto'] = true;
            } else {
                $skeleton['showcoursegoto'] = false;
            }

            // Enable course details popup, if configured.
            if (get_config('theme_boost_union', 'courselistinghowpopup') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                $skeleton['showcoursepopup'] = true;
            } else {
                $skeleton['showcoursepopup'] = false;
            }

            // Enable course buttons area, if necessary.
            if ($skeleton['showcoursegoto'] || $skeleton['showcoursepopup']) {
                $skeleton['showbuttons'] = true;
            } else {
                $skeleton['showbuttons'] = false;
            }

            // Enable course fields, if configured.
            if (get_config('theme_boost_union', 'courselistinghowfields') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                $skeleton['showcoursefields'] = true;
            } else {
                $skeleton['showcoursefields'] = false;
            }

            // Enable course enrol icons, if configured.
            if (get_config('theme_boost_union', 'courselistinghowenrolicons') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                $skeleton['showcourseenrolicons'] = true;
            } else {
                $skeleton['showcourseenrolicons'] = false;
            }

            // Enable course progress, if configured.
            if (get_config('theme_boost_union', 'courselistinghowprogress') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                $skeleton['showcourseprogress'] = true;
            } else {
                $skeleton['showcourseprogress'] = false;
            }

            // Check if the user can view user details, if necessary.
            if ($skeleton['showcoursecontacts'] || $skeleton['showcoursepopup']) {
                $skeleton['canviewuserdetails'] = has_capability('moodle/user:viewdetails', \context_system::instance()) &&
                        (isloggedin() || isguestuser() && get_config('forceloginforprofiles') != true);
            }

            // Disable details bar as a start.
            $skeleton['showdetailsbar'] = false;
        }

        // Create a copy of the skeleton for this particular course.
        $templatedata = $skeleton;

        // Get the course list element for the course.
        if ($course instanceof stdClass) {
            $course = new core_course_list_element($course);
        }

        // Get course util for the course.
        $courseutil = new course($course);

        // Add the particular course data to the template data.
        $templatedata['id'] = $course->id;
        $templatedata['visible'] = $course->visible;
        $templatedata['fullname'] = $chelper->get_course_formatted_name($course);
        $templatedata['viewurl'] = new moodle_url('/course/view.php', ['id' => $course->id]);

        // Amend course image, if enabled.
        if ($templatedata['showcourseimage']) {
            $templatedata['courseimage'] = $courseutil->get_courseimage();
        }

        // Amend course contacts, if enabled.
        if ($templatedata['showcoursecontacts'] || $templatedata['showcoursepopup']) {
            $templatedata['contacts'] = $courseutil->get_course_contacts();
            $templatedata['hascontacts'] = (count($templatedata['contacts']) > 0);
        }

        // Amend course shortname, if enabled.
        if ($templatedata['showshortname']) {
            $templatedata['shortname'] = $course->shortname;
        }

        // Amend course category, if enabled.
        if ($templatedata['showcoursecategory']) {
            $templatedata['coursecategory'] = $courseutil->get_category();
        }

        // Amend course summary, if enabled.
        if ($templatedata['showcoursepopup']) {
            $templatedata['summary'] = $courseutil->get_summary($chelper);
            $templatedata['hassummary'] = ($templatedata['summary'] != false);
        }

        // Amend custom fields, if enabled.
        if ($templatedata['showcoursefields'] || $templatedata['showcoursepopup']) {
            $templatedata['customfields'] = $courseutil->get_custom_fields();
            $templatedata['hascustomfields'] = ($templatedata['customfields'] != false);
        }

        // Amend course enrolment icons, if enabled.
        if ($templatedata['showcourseenrolicons']) {
            $courseenrolmenticons = $courseutil->get_enrolment_icons();
            $courseenrolmenticons = !empty($courseenrolmenticons) ? $this->render_enrolment_icons($courseenrolmenticons) : false;
            $templatedata['enrolmenticons'] = $courseenrolmenticons;
            $templatedata['hasenrolicons'] = ($courseenrolmenticons != false);
        }

        // Amend course progress, if enabled.
        if ($templatedata['showcourseprogress']) {
            $courseprogress = $courseutil->get_progress();
            $templatedata['progress'] = (int) $courseprogress;
            $templatedata['hasprogress'] = ($courseprogress !== null);
        }

        // Enable detailsbar, if necessary.
        if ($templatedata['showcourseenrolicons'] && $templatedata['hasenrolicons']) {
            $templatedata['showdetailsbar'] = true;
        }
        if ($templatedata['showcourseprogress'] && $templatedata['hasprogress']) {
            $templatedata['showdetailsbar'] = true;
        }

        // Enable sidebar (in the list view), if necessary.
        if ($templatedata['showbuttons'] || $templatedata['showcoursefields']) {
            $templatedata['showsidebar'] = true;
        }

        // If course cards are enabled.
        if ($courselistingpresentation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_CARDS) {
            // Render the card template.
            $content = $this->render_from_template('theme_boost_union/courselistingcard', $templatedata);

            // Or if the course list is enabled.
        } else if ($courselistingpresentation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_LIST) {
            // Render the list template.
            $content = $this->render_from_template('theme_boost_union/courselistinglist', $templatedata);
        }

        // And return it.
        return $content;
    }

    /**
     * Returns enrolment icons
     *
     * @param array $icons
     *
     * @return array
     */
    protected function render_enrolment_icons(array $icons): array {
        $data = [];

        foreach ($icons as $icon) {
            $data[] = $this->render($icon);
        }

        return $data;
    }

    /**
     * Returns HTML to display a course category as a part of a tree
     *
     * This is an internal function, to display a particular category and all its contents
     * use \core_course_renderer::course_category()
     *
     * Modifications compared to the original function:
     * * Style the category number badge if enabled, otherwise call the parent function to compose the default view.
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_category $coursecat
     * @param int $depth depth of this category in the current tree
     * @return string
     */
    protected function coursecat_category(coursecat_helper $chelper, $coursecat, $depth) {
        // If the category listing should remain unchanged.
        $categorylistingpresentation = get_config('theme_boost_union', 'categorylistingpresentation');
        if (!isset($categorylistingpresentation) ||
                $categorylistingpresentation == THEME_BOOST_UNION_SETTING_CATLISTPRES_NOCHANGE) {
            // Call the parent function to compose the default view.
            return parent::coursecat_category($chelper, $coursecat, $depth);
        }

        // This code is based on the original function, we do not want to fix the coding style flaws from it.
        // phpcs:disable

        // open category tag
        $classes = array('category');
        if (empty($coursecat->visible)) {
            $classes[] = 'dimmed_category';
        }
        if ($chelper->get_subcat_depth() > 0 && $depth >= $chelper->get_subcat_depth()) {
            // do not load content
            $categorycontent = '';
            $classes[] = 'notloaded';
            if ($coursecat->get_children_count() ||
                    ($chelper->get_show_courses() >= self::COURSECAT_SHOW_COURSES_COLLAPSED && $coursecat->get_courses_count())) {
                $classes[] = 'with_children';
                $classes[] = 'collapsed';
            }
        } else {
            // load category content
            $categorycontent = $this->coursecat_category_content($chelper, $coursecat, $depth);
            $classes[] = 'loaded';
            if (!empty($categorycontent)) {
                $classes[] = 'with_children';
                // Category content loaded with children.
                $this->categoryexpandedonload = true;
            }
        }

        // Make sure JS file to expand category content is included.
        $this->coursecat_include_js();

        $content = html_writer::start_tag('div', array(
            'class' => join(' ', $classes),
            'data-categoryid' => $coursecat->id,
            'data-depth' => $depth,
            'data-showcourses' => $chelper->get_show_courses(),
            'data-type' => self::COURSECAT_TYPE_CATEGORY,
        ));

        // category name
        $categoryname = $coursecat->get_formatted_name();
        $categoryname = html_writer::link(new moodle_url('/course/index.php',
                array('categoryid' => $coursecat->id)),
                $categoryname);
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_COUNT
                && ($coursescount = $coursecat->get_courses_count())) {
            $categoryname .= html_writer::tag('span', $coursescount,
                    array('title' => get_string('numberofcourses'), 'class' => 'numberofcourse badge badge-pill badge-secondary ml-2'));
        }
        $content .= html_writer::start_tag('div', array('class' => 'info'));

        $content .= html_writer::tag(($depth > 1) ? 'h4' : 'h3', $categoryname, array('class' => 'categoryname aabtn'));
        $content .= html_writer::end_tag('div'); // .info

        // add category content to the output
        $content .= html_writer::tag('div', $categorycontent, array('class' => 'content'));

        $content .= html_writer::end_tag('div'); // .category

        // Return the course category tree HTML
        return $content;

        // phpcs:enable
    }

    /**
     * Returns HTML to display a tree of subcategories and courses in the given category
     *
     * Modifications compared to the original function:
     * * Style the category tree if enabled, otherwise call the parent function to compose the default view.
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_category $coursecat top category (this category's name and description will NOT be added to the tree)
     * @return string
     */
    protected function coursecat_tree(coursecat_helper $chelper, $coursecat) {
        // If the category listing should remain unchanged.
        $categorylistingpresentation = get_config('theme_boost_union', 'categorylistingpresentation');
        if (!isset($categorylistingpresentation) ||
                $categorylistingpresentation == THEME_BOOST_UNION_SETTING_CATLISTPRES_NOCHANGE) {
            // Call the parent function to compose the default view.
            return parent::coursecat_tree($chelper, $coursecat);
        }

        // This code is based on the original function, we do not want to fix the coding style flaws from it.
        // phpcs:disable

        // Reset the category expanded flag for this course category tree first.
        $this->categoryexpandedonload = false;
        $categorycontent = $this->coursecat_category_content($chelper, $coursecat, 0);
        if (empty($categorycontent)) {
            return '';
        }

        // If the modified course listing within the category tree is enabled.
        $courselistingpresentation = get_config('theme_boost_union', 'courselistingpresentation');
        $additionalclasses = '';
        if (isset($courselistingpresentation) && $courselistingpresentation != THEME_BOOST_UNION_SETTING_COURSELISTPRES_NOCHANGE) {
            // Add a CSS class to allow styling the category listing.
            $additionalclasses = 'theme_boost_union-catlisting-cl';
        }

        // Start content generation
        $content = '';
        $attributes = $chelper->get_and_erase_attributes('theme_boost_union-catlisting '.$additionalclasses.
                ' course_category_tree clearfix');
        $content .= html_writer::start_tag('div', $attributes);

        if ($coursecat->get_children_count()) {
            $classes = array(
                'collapseexpand', 'aabtn'
            );

            // Check if the category content contains subcategories with children's content loaded.
            if ($this->categoryexpandedonload) {
                $classes[] = 'collapse-all';
                $linkname = get_string('collapseall');
            } else {
                $linkname = get_string('expandall');
            }

            // Only show the collapse/expand if there are children to expand.
            $content .= html_writer::start_tag('div', array('class' => 'collapsible-actions'));
            $content .= html_writer::link('#', $linkname, array('class' => implode(' ', $classes)));
            $content .= html_writer::end_tag('div');
            $this->page->requires->strings_for_js(array('collapseall', 'expandall'), 'moodle');
        }

        $content .= html_writer::tag('div', $categorycontent, array('class' => 'content'));

        $content .= html_writer::end_tag('div'); // .course_category_tree

        return $content;

        // phpcs:enable
    }

    /**
     * Renders HTML to display particular course category - list of it's subcategories and courses
     *
     * Invoked from /course/index.php
     *
     * Modifications compared to the original function:
     * * Style the category description if enabled, otherwise call the parent function to compose the default view.
     *
     * @param int|stdClass|core_course_category $category
     */
    public function course_category($category) {
        global $CFG;

        // If the category listing should remain unchanged.
        $categorylistingpresentation = get_config('theme_boost_union', 'categorylistingpresentation');
        if (!isset($categorylistingpresentation) ||
                $categorylistingpresentation == THEME_BOOST_UNION_SETTING_CATLISTPRES_NOCHANGE) {
            // Call the parent function to compose the default view.
            return parent::course_category($category);
        }

        // This code is based on the original function, we do not want to fix the coding style flaws from it.
        // phpcs:disable

        $usertop = core_course_category::user_top();
        if (empty($category)) {
            $coursecat = $usertop;
        } else if (is_object($category) && $category instanceof core_course_category) {
            $coursecat = $category;
        } else {
            $coursecat = core_course_category::get(is_object($category) ? $category->id : $category);
        }
        $site = get_site();
        $actionbar = new \core_course\output\category_action_bar($this->page, $coursecat);
        $output = $this->render_from_template('core_course/category_actionbar', $actionbar->export_for_template($this));

        if (core_course_category::is_simple_site()) {
            // There is only one category in the system, do not display link to it.
            $strfulllistofcourses = get_string('fulllistofcourses');
            $this->page->set_title($strfulllistofcourses);
        } else if (!$coursecat->id || !$coursecat->is_uservisible()) {
            $strcategories = get_string('categories');
            $this->page->set_title($strcategories);
        } else {
            $strfulllistofcourses = get_string('fulllistofcourses');
            $this->page->set_title($strfulllistofcourses);
        }

        // Print current category description
        $chelper = new coursecat_helper();
        if ($description = $chelper->get_category_formatted_description($coursecat)) {
            $output .= $this->box($description, array('class' => 'theme_boost_union-coursecategoryinfo generalbox info'));
        }

        // Prepare parameters for courses and categories lists in the tree
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_AUTO)
                ->set_attributes(array('class' => 'category-browse category-browse-'.$coursecat->id));

        $coursedisplayoptions = array();
        $catdisplayoptions = array();
        $browse = optional_param('browse', null, PARAM_ALPHA);
        $perpage = optional_param('perpage', $CFG->coursesperpage, PARAM_INT);
        $page = optional_param('page', 0, PARAM_INT);
        $baseurl = new moodle_url('/course/index.php');
        if ($coursecat->id) {
            $baseurl->param('categoryid', $coursecat->id);
        }
        if ($perpage != $CFG->coursesperpage) {
            $baseurl->param('perpage', $perpage);
        }
        $coursedisplayoptions['limit'] = $perpage;
        $catdisplayoptions['limit'] = $perpage;
        if ($browse === 'courses' || !$coursecat->get_children_count()) {
            $coursedisplayoptions['offset'] = $page * $perpage;
            $coursedisplayoptions['paginationurl'] = new moodle_url($baseurl, array('browse' => 'courses'));
            $catdisplayoptions['nodisplay'] = true;
            $catdisplayoptions['viewmoreurl'] = new moodle_url($baseurl, array('browse' => 'categories'));
            $catdisplayoptions['viewmoretext'] = new lang_string('viewallsubcategories');
        } else if ($browse === 'categories' || !$coursecat->get_courses_count()) {
            $coursedisplayoptions['nodisplay'] = true;
            $catdisplayoptions['offset'] = $page * $perpage;
            $catdisplayoptions['paginationurl'] = new moodle_url($baseurl, array('browse' => 'categories'));
            $coursedisplayoptions['viewmoreurl'] = new moodle_url($baseurl, array('browse' => 'courses'));
            $coursedisplayoptions['viewmoretext'] = new lang_string('viewallcourses');
        } else {
            // we have a category that has both subcategories and courses, display pagination separately
            $coursedisplayoptions['viewmoreurl'] = new moodle_url($baseurl, array('browse' => 'courses', 'page' => 1));
            $catdisplayoptions['viewmoreurl'] = new moodle_url($baseurl, array('browse' => 'categories', 'page' => 1));
        }
        $chelper->set_courses_display_options($coursedisplayoptions)->set_categories_display_options($catdisplayoptions);

        // Display course category tree.
        $output .= $this->coursecat_tree($chelper, $coursecat);

        return $output;

        // phpcs:enable
    }
}
