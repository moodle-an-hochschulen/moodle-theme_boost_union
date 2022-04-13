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
 * Theme Boost Union - Locallib file
 *
 * @package   theme_boost_union
 * @copyright 2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Return if the info banner should be displayed on current page layout.
 *
 * @param array $infobannerpagestoshow The list of page layouts on which the info banner should be shown.
 * @param string $infobannercontent The content which should be displayed within the info banner.
 * @param mixed|moodle_page $thispagelayout The current page layout.
 * @param string $perbibuserprefdialdismissed The user preference if the dissmissible banner has been dismissed.
 * @return boolean
 */
function theme_boost_union_show_banner_on_selected_page($infobannerpagestoshow, $infobannercontent, $thispagelayout,
    $perbibuserprefdialdismissed) {

    // Initialize variable.
    $infobannershowonselectedpage = false;

    // Traverse multiselect setting.
    foreach ($infobannerpagestoshow as $page) {
        if (empty($infobannercontent)) {
            $infobannershowonselectedpage = false;
        } else {
            // Decide if the info banner should be shown at all.
            if (!empty($infobannercontent) && $thispagelayout == $page && !$perbibuserprefdialdismissed) {
                $infobannershowonselectedpage = true;
                continue;
            }
        }
    }
    return $infobannershowonselectedpage;
}

/**
 * Return if the time limited info banner should be displayed on current page layout.
 *
 * @param int $now The timestamp of the current server time.
 * @param array $timedibshowonpages The list of page layouts on which the info banner should be shown.
 * @param string $timedibcontent The content which should be displayed within the info banner.
 * @param string $timedibstartsetting The value from setting timedibstart.
 * @param string $timedibendsetting The value from setting timedibend.
 * @param mixed|moodle_page $thispagelayout The current page layout.
 * @return boolean
 */
function theme_boost_union_show_timed_banner_on_selected_page($now, $timedibshowonpages, $timedibcontent, $timedibstartsetting,
    $timedibendsetting, $thispagelayout) {

    // Initialize variable.
    $timedinfobannershowonselectedpage = false;

    // Check if time settings are empty and try to convert the time string_s_ to a unix timestamp.
    if (empty($timedibstartsetting)) {
        $timedibstartempty = true;
        $timedibstart = 0;
    } else {
        $timedibstart = strtotime($timedibstartsetting);
        $timedibstartempty = false;
    }
    if (empty($timedibendsetting)) {
        $timedibendempty = true;
        $timedibend = 0;
    } else {
        $timedibend = strtotime($timedibendsetting);
        $timedibendempty = false;
    }

    // Add the time check:
    // Show the banner when now is between start and end time OR
    // Show the banner when start is not set but end is not reached yet OR
    // Show the banner when end is not set, but start lies in the past OR
    // Show the banner if no dates are set, so there's no time restriction.
    if (($now >= $timedibstart && $now <= $timedibend ||
        ($now <= $timedibend && $timedibstartempty) ||
        ($now >= $timedibstart && $timedibendempty) ||
        ($timedibstartempty && $timedibendempty))) {
        $timedinfobannershowonselectedpage = theme_boost_union_show_banner_on_selected_page($timedibshowonpages,
            $timedibcontent, $thispagelayout, false);
    }

    return $timedinfobannershowonselectedpage;
}

/**
 * Build the course page information banners HTML code.
 * This function evaluates and composes all information banners which may appear on a course page below the full header.
 *
 * @return string.
 */
function theme_boost_union_get_course_information_banners() {
    global $CFG, $COURSE, $PAGE, $USER;

    // Require user library.
    require_once($CFG->dirroot.'/user/lib.php');

    // Initialize HTML code.
    $html = '';

    // If the setting showhintcoursehidden is set, the visibility of the course is hidden and
    // a hint for the visibility will be shown.
    if (get_config('theme_boost_union', 'showhintcoursehidden') == 'yes'
        && has_capability('theme/boost_union:viewhintinhiddencourse', \context_course::instance($COURSE->id))
        && $PAGE->has_set_url()
        && $PAGE->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)
        && $COURSE->visible == false) {
        $html .= html_writer::start_tag('div', array('class' => 'course-hidden-infobox alert alert-warning'));
        $html .= html_writer::start_tag('div', array('class' => 'media'));
        $html .= html_writer::start_tag('div', array('class' => 'mr-3 icon-size-5'));
        $html .= html_writer::tag('i', null, array('class' => 'fa fa-exclamation-circle fa-3x'));
        $html .= html_writer::end_tag('div');
        $html .= html_writer::start_tag('div', array('class' => 'media-body align-self-center'));
        $html .= get_string('showhintcoursehiddengeneral', 'theme_boost_union', $COURSE->id);
        // If the user has the capability to change the course settings, an additional link to the course settings is shown.
        if (has_capability('moodle/course:update', context_course::instance($COURSE->id))) {
            $html .= html_writer::tag('div', get_string('showhintcoursehiddensettingslink',
                'theme_boost_union', array('url' => $CFG->wwwroot.'/course/edit.php?id='. $COURSE->id)));
        }
        $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
    }

    // If the setting showhintcourseguestaccess is set, a hint for users that view the course with guest access is shown.
    // We also check that the user did not switch the role. This is a special case for roles that can fully access the course
    // without being enrolled. A role switch would show the guest access hint additionally in that case and this is not
    // intended.
    if (get_config('theme_boost_union', 'showhintcourseguestaccess') == 'yes'
        && is_guest(\context_course::instance($COURSE->id), $USER->id)
        && $PAGE->has_set_url()
        && $PAGE->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)
        && !is_role_switched($COURSE->id)) {
        $html .= html_writer::start_tag('div', array('class' => 'course-guestaccess-infobox alert alert-warning'));
        $html .= html_writer::start_tag('div', array('class' => 'media'));
        $html .= html_writer::start_tag('div', array('class' => 'mr-3 icon-size-5'));
        $html .= html_writer::tag('i', null, array('class' => 'fa fa-exclamation-circle fa-3x'));
        $html .= html_writer::end_tag('div');
        $html .= html_writer::start_tag('div', array('class' => 'media-body align-self-center'));
        $html .= get_string('showhintcourseguestaccessgeneral', 'theme_boost_union',
            array('role' => role_get_name(get_guest_role())));
        $html .= theme_boost_union_get_course_guest_access_hint($COURSE->id);
        $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
    }

    // If the setting showhintcourseselfenrol is set, a hint for users is shown that the course allows unrestricted self
    // enrolment. This hint is only shown if the course is visible, the self enrolment is visible and if the user has the
    // capability "theme/boost_union:viewhintcourseselfenrol".
    if (get_config('theme_boost_union', 'showhintcourseselfenrol') == 'yes'
        && has_capability('theme/boost_union:viewhintcourseselfenrol', \context_course::instance($COURSE->id))
        && $PAGE->has_set_url()
        && $PAGE->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)
        && $COURSE->visible == true) {
        // Get the active enrol instances for this course.
        $enrolinstances = enrol_get_instances($COURSE->id, true);
        // Prepare to remember when self enrolment is / will be possible.
        $selfenrolmentpossiblecurrently = false;
        $selfenrolmentpossiblefuture = false;
        foreach ($enrolinstances as $instance) {
            // Check if unrestricted self enrolment is possible currently or in the future.
            $now = (new \DateTime("now", \core_date::get_server_timezone_object()))->getTimestamp();
            if ($instance->enrol == 'self' && empty($instance->password) && $instance->customint6 == 1 &&
                (empty($instance->enrolenddate) || $instance->enrolenddate > $now)) {

                // Build enrol instance object with all necessary information for rendering the note later.
                $instanceobject = new stdClass();

                // Remember instance name.
                if (empty($instance->name)) {
                    $instanceobject->name = get_string('pluginname', 'enrol_self') .
                        " (" . get_string('defaultcoursestudent', 'core') . ")";
                } else {
                    $instanceobject->name = $instance->name;
                }

                // Remember type of unrestrictedness.
                if (empty($instance->enrolenddate) && empty($instance->enrolstartdate)) {
                    $instanceobject->unrestrictedness = 'unlimited';
                    $selfenrolmentpossiblecurrently = true;
                } else if (empty($instance->enrolstartdate) &&
                    !empty($instance->enrolenddate) && $instance->enrolenddate > $now) {
                    $instanceobject->unrestrictedness = 'until';
                    $selfenrolmentpossiblecurrently = true;
                } else if (empty($instance->enrolenddate) &&
                    !empty($instance->enrolstartdate) && $instance->enrolstartdate > $now) {
                    $instanceobject->unrestrictedness = 'from';
                    $selfenrolmentpossiblefuture = true;
                } else if (empty($instance->enrolenddate) &&
                    !empty($instance->enrolstartdate) && $instance->enrolstartdate <= $now) {
                    $instanceobject->unrestrictedness = 'since';
                    $selfenrolmentpossiblecurrently = true;
                } else if (!empty($instance->enrolstartdate) && $instance->enrolstartdate > $now &&
                    !empty($instance->enrolenddate) && $instance->enrolenddate > $now) {
                    $instanceobject->unrestrictedness = 'fromuntil';
                    $selfenrolmentpossiblefuture = true;
                } else if (!empty($instance->enrolstartdate) && $instance->enrolstartdate <= $now &&
                    !empty($instance->enrolenddate) && $instance->enrolenddate > $now) {
                    $instanceobject->unrestrictedness = 'sinceuntil';
                    $selfenrolmentpossiblecurrently = true;
                } else {
                    // This should not happen, thus continue to next instance.
                    continue;
                }

                // Remember enrol start date.
                if (!empty($instance->enrolstartdate)) {
                    $instanceobject->startdate = $instance->enrolstartdate;
                } else {
                    $instanceobject->startdate = null;
                }

                // Remember enrol end date.
                if (!empty($instance->enrolenddate)) {
                    $instanceobject->enddate = $instance->enrolenddate;
                } else {
                    $instanceobject->enddate = null;
                }

                // Remember this instance.
                $selfenrolinstances[$instance->id] = $instanceobject;
            }
        }

        // If there is at least one unrestricted enrolment instance,
        // show the hint with information about each unrestricted active self enrolment in the course.
        if (!empty($selfenrolinstances) &&
            ($selfenrolmentpossiblecurrently == true || $selfenrolmentpossiblefuture == true)) {
            // Start hint box.
            $html .= html_writer::start_tag('div', array('class' => 'course-selfenrol-infobox alert alert-info'));
            $html .= html_writer::start_tag('div', array('class' => 'media'));
            $html .= html_writer::start_tag('div', array('class' => 'mr-3 icon-size-5'));
            $html .= html_writer::tag('i', null, array('class' => 'fa fa-sign-in fa-3x'));
            $html .= html_writer::end_tag('div');
            $html .= html_writer::start_tag('div', array('class' => 'media-body align-self-center'));

            // Show the start of the hint depending on the fact if enrolment is already possible currently or
            // will be in the future.
            if ($selfenrolmentpossiblecurrently == true) {
                $html .= get_string('showhintcourseselfenrolstartcurrently', 'theme_boost_union');
            } else if ($selfenrolmentpossiblefuture == true) {
                $html .= get_string('showhintcourseselfenrolstartfuture', 'theme_boost_union');
            }
            $html .= html_writer::empty_tag('br');

            // Iterate over all enrolment instances to output the details.
            foreach ($selfenrolinstances as $selfenrolinstanceid => $selfenrolinstanceobject) {
                // If the user has the capability to config self enrolments, enrich the instance name with the settings link.
                if (has_capability('enrol/self:config', \context_course::instance($COURSE->id))) {
                    $url = new moodle_url('/enrol/editinstance.php', array('courseid' => $COURSE->id,
                        'id' => $selfenrolinstanceid, 'type' => 'self'));
                    $selfenrolinstanceobject->name = html_writer::link($url, $selfenrolinstanceobject->name);
                }

                // Show the enrolment instance information depending on the instance configuration.
                if ($selfenrolinstanceobject->unrestrictedness == 'unlimited') {
                    $html .= get_string('showhintcourseselfenrolunlimited', 'theme_boost_union',
                        array('name' => $selfenrolinstanceobject->name));
                } else if ($selfenrolinstanceobject->unrestrictedness == 'until') {
                    $html .= get_string('showhintcourseselfenroluntil', 'theme_boost_union',
                        array('name' => $selfenrolinstanceobject->name,
                            'until' => userdate($selfenrolinstanceobject->enddate)));
                } else if ($selfenrolinstanceobject->unrestrictedness == 'from') {
                    $html .= get_string('showhintcourseselfenrolfrom', 'theme_boost_union',
                        array('name' => $selfenrolinstanceobject->name,
                            'from' => userdate($selfenrolinstanceobject->startdate)));
                } else if ($selfenrolinstanceobject->unrestrictedness == 'since') {
                    $html .= get_string('showhintcourseselfenrolsince', 'theme_boost_union',
                        array('name' => $selfenrolinstanceobject->name,
                            'since' => userdate($selfenrolinstanceobject->startdate)));
                } else if ($selfenrolinstanceobject->unrestrictedness == 'fromuntil') {
                    $html .= get_string('showhintcourseselfenrolfromuntil', 'theme_boost_union',
                        array('name' => $selfenrolinstanceobject->name,
                            'until' => userdate($selfenrolinstanceobject->enddate),
                            'from' => userdate($selfenrolinstanceobject->startdate)));
                } else if ($selfenrolinstanceobject->unrestrictedness == 'sinceuntil') {
                    $html .= get_string('showhintcourseselfenrolsinceuntil', 'theme_boost_union',
                        array('name' => $selfenrolinstanceobject->name,
                            'until' => userdate($selfenrolinstanceobject->enddate),
                            'since' => userdate($selfenrolinstanceobject->startdate)));
                }

                // Add a trailing space to separate this instance from the next one.
                $html .= ' ';
            }

            // If the user has the capability to config self enrolments, add the call for action.
            if (has_capability('enrol/self:config', \context_course::instance($COURSE->id))) {
                $html .= html_writer::empty_tag('br');
                $html .= get_string('showhintcourseselfenrolinstancecallforaction', 'theme_boost_union');
            }

            // End hint box.
            $html .= html_writer::end_tag('div');
            $html .= html_writer::end_tag('div');
            $html .= html_writer::end_tag('div');
        }
    }

    // Only use this if setting 'showswitchedroleincourse' is active.
    if (get_config('theme_boost_union', 'showswitchedroleincourse') === 'yes') {
        // Check if the user did a role switch.
        // If not, adding this section would make no sense and, even worse,
        // user_get_user_navigation_info() will throw an exception due to the missing user object.
        if (is_role_switched($COURSE->id)) {
            // Get the role name switched to.
            $opts = \user_get_user_navigation_info($USER, $PAGE);
            $role = $opts->metadata['rolename'];
            // Get the URL to switch back (normal role).
            $url = new moodle_url('/course/switchrole.php',
                array('id'        => $COURSE->id, 'sesskey' => sesskey(), 'switchrole' => 0,
                    'returnurl' => $PAGE->url->out_as_local_url(false)));
            $html .= html_writer::start_tag('div', array('class' => 'switched-role-infobox alert alert-info'));
            $html .= html_writer::start_tag('div', array('class' => 'media'));
            $html .= html_writer::start_tag('div', array('class' => 'mr-3 icon-size-5'));
            $html .= html_writer::tag('i', null, array('class' => 'fa fa-user-circle fa-3x'));
            $html .= html_writer::end_tag('div');
            $html .= html_writer::start_tag('div', array('class' => 'media-body align-self-center'));
            $html .= html_writer::start_tag('div');
            $html .= get_string('switchedroleto', 'theme_boost_union');
            // Give this a span to be able to address via CSS.
            $html .= html_writer::tag('span', $role, array('class' => 'switched-role'));
            $html .= html_writer::end_tag('div');
            // Return to normal role link.
            $html .= html_writer::start_tag('div');
            $html .= html_writer::tag('a', get_string('switchrolereturn', 'core'),
                array('class' => 'switched-role-backlink', 'href' => $url));
            $html .= html_writer::end_tag('div'); // Return to normal role link: end div.
            $html .= html_writer::end_tag('div');
            $html .= html_writer::end_tag('div');
            $html .= html_writer::end_tag('div');
        }
    }

    // Return HTML code.
    return $html;
}
