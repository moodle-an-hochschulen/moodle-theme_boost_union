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
use stdClass;
use core_course_list_element;
use theme_boost_union\util\course;

/**
 * Theme Boost Union - Course renderer
 *
 * @package    theme_boost_union
 * @copyright  2024 Daniel Neis Araujo {@link https://www.adapta.online}
 *             based on code 2022 Willian Mano {@link https://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_renderer extends \core_course_renderer {

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
     *
     * @param coursecat_helper $chelper various display options
     * @param array $courses the list of courses to display
     * @param int|null $totalcount total number of courses (affects display mode if it is AUTO or pagination if applicable),
     *     defaulted to count($courses)
     * @return string
     */
    protected function coursecat_courses(coursecat_helper $chelper, $courses, $totalcount = null) {
        // If the course listing should remain unchanged.
        $courselistingpresetation = get_config('theme_boost_union', 'courselistingpresentation');
        if (!isset($courselistingpresetation) || $courselistingpresetation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_NOCHANGE) {
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
            // In 'auto' course display mode we analyse if number of courses is more or less than $CFG->courseswithsummarieslimit.
            if ($totalcount <= $CFG->courseswithsummarieslimit) {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED);
            } else {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_COLLAPSED);
            }
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

        // If course cards are enabled.
        if ($courselistingpresetation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_CARDS) {
            // Start the course listing as card grid.
            // Use the same wrapper classes as in /course/templates/coursecards.mustache.
            // Set the row-cols-lg class depending on the coursecardscolumncount setting.
            // And add the theme_boost_union-courselisting class to be used in the CSS.
            $maxcols = get_config('theme_boost_union', 'coursecardscolumncount');
            $maxcolslg = $maxcols;
            $maxcolssm = ($maxcols > 1) ? $maxcols : 1;
            $content .= html_writer::start_tag('div',
                    [
                        'class' => 'card-grid mx-0 row row-cols-1 row-cols-sm-'.$maxcolssm.' row-cols-lg-'.$maxcolslg.
                                ' theme_boost_union-courselisting',
                        'role' => 'list',
                    ]
            );

            // Iterate over the courses.
            foreach ($courses as $course) {
                // Build the course card.
                // Use the same wrapper classes as in /course/templates/coursecards.mustache.
                $content .= html_writer::start_tag('div', ['class' => 'col d-flex px-0 mb-2']);
                $content .= $this->coursecat_coursebox($chelper, $course);
                $content .= html_writer::end_tag('div');
            }

            // Or if the course list is enabled.
        } else if ($courselistingpresetation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_LIST) {
            // Start the course listing as course list.
            // And add the theme_boost_union-courselisting class to be used in the CSS.
            $content .= html_writer::start_tag('div',
                    [
                        'class' => 'mx-0 row no-gutters theme_boost_union-courselisting',
                        'role' => 'list',
                    ]
            );

            // Iterate over the courses.
            foreach ($courses as $course) {
                // Build the course list.
                $content .= html_writer::start_tag('div', ['class' => 'col-12']);
                $content .= $this->coursecat_coursebox($chelper, $course);
                $content .= html_writer::end_tag('div');
            }
        }

        // End the course listing.
        $content .= html_writer::end_tag('div');

        // If the course listing details modal is enabled, add the necessary JS.
        if (get_config('theme_boost_union', 'courselistinghowpopup') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            $this->page->requires->js_call_amd('theme_boost_union/courselistingdetailsmodal', 'init');
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
        $courselistingpresetation = get_config('theme_boost_union', 'courselistingpresentation');
        if (!isset($courselistingpresetation) || $courselistingpresetation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_NOCHANGE) {
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
        $courselistingpresetation = get_config('theme_boost_union', 'courselistingpresentation');
        if (!isset($courselistingpresetation) || $courselistingpresetation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_NOCHANGE) {
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

            // Disable card footer as a start.
            $skeleton['showcardfooter'] = false;
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
        $templatedata['viewurl'] = new \core\url('/course/view.php', ['id' => $course->id]);

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
        if ($skeleton['showcoursecategory']) {
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
            $templatedata['hasprogress'] = ($courseprogress != null);
        }

        // Enable card footer, if necessary.
        if ($templatedata['showcourseenrolicons'] && $templatedata['hasenrolicons']) {
            $templatedata['showcardfooter'] = true;
        }
        if ($templatedata['showcourseprogress'] && $templatedata['hasprogress']) {
            $templatedata['showcardfooter'] = true;
        }

        // If course cards are enabled.
        if ($courselistingpresetation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_CARDS) {
            // Render the card template.
            $content = $this->render_from_template('theme_boost_union/courselistingcard', $templatedata);

            // Or if the course list is enabled.
        } else if ($courselistingpresetation == THEME_BOOST_UNION_SETTING_COURSELISTPRES_LIST) {
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
}
