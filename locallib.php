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
 * Theme Boost Union - Local library
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\di;
use core\hook\manager as hook_manager;

/**
 * Build the course related hints HTML code.
 * This function evaluates and composes all course related hints which may appear on a course page below the course header.
 *
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  based on code from theme_boost_campus by Kathrin Osswald.
 *
 * @return string.
 */
function theme_boost_union_get_course_related_hints() {
    global $CFG, $COURSE, $PAGE, $USER, $OUTPUT;

    // Require user library.
    require_once($CFG->dirroot.'/user/lib.php');

    // Initialize HTML code.
    $html = '';

    // If the setting showhintcoursehidden is set and the visibility of the course is hidden
    // a hint for the visibility will be shown.
    if (get_config('theme_boost_union', 'showhintcoursehidden') == THEME_BOOST_UNION_SETTING_SELECT_YES
            && has_capability('theme/boost_union:viewhintinhiddencourse', \context_course::instance($COURSE->id))
            && $PAGE->has_set_url()
            && $COURSE->visible == false) {

        // Initialize hint text.
        $hintcoursehiddentext = '';

        // The general hint will only be shown when the course is viewed.
        if ($PAGE->url->compare(new core\url('/course/view.php'), URL_MATCH_BASE)) {
            // Use the default hint text for hidden courses.
            $hintcoursehiddentext = get_string('showhintcoursehiddengeneral', 'theme_boost_union');
        }

        // If the setting showhintcoursehiddennotifications is set too and we view a forum (e.g. announcement) within a hidden
        // course a hint will be shown that no notifications via forums will be sent out to students.
        if (get_config('theme_boost_union', 'showhintforumnotifications') == THEME_BOOST_UNION_SETTING_SELECT_YES
                && ($PAGE->url->compare(new core\url('/mod/forum/view.php'), URL_MATCH_BASE) ||
                        $PAGE->url->compare(new core\url('/mod/forum/discuss.php'), URL_MATCH_BASE) ||
                        $PAGE->url->compare(new core\url('/mod/forum/post.php'), URL_MATCH_BASE))) {
            // Use the specialized hint text for hidden courses on forum pages.
            $hintcoursehiddentext = get_string('showhintforumnotifications', 'theme_boost_union');
        }

        // If we show any kind of hint for the hidden course, construct the hints HTML item via mustache.
        if ($hintcoursehiddentext) {
            // Prepare the templates context.
            $templatecontext = [
                'courseid' => $COURSE->id,
                'hintcoursehiddentext' => $hintcoursehiddentext,
                // If the user has the capability to change the course settings, an additional link to the course settings is shown.
                'showcoursesettingslink' => has_capability('moodle/course:update', context_course::instance($COURSE->id)),
            ];

            // Render template and add it to HTML code.
            $html .= $OUTPUT->render_from_template('theme_boost_union/course-hint-hidden', $templatecontext);
        }
    }

    // If the setting showhintcourseguestaccess is set and the user is accessing the course with guest access,
    // a hint for users is shown.
    // We also check that the user did not switch the role. This is a special case for roles that can fully access the course
    // without being enrolled. A role switch would show the guest access hint additionally in that case and this is not
    // intended.
    if (get_config('theme_boost_union', 'showhintcourseguestaccess') == THEME_BOOST_UNION_SETTING_SELECT_YES
            && is_guest(\context_course::instance($COURSE->id), $USER->id)
            && $PAGE->has_set_url()
            && $PAGE->url->compare(new core\url('/course/view.php'), URL_MATCH_BASE)
            && !is_role_switched($COURSE->id)) {

        // Require self enrolment library.
        require_once($CFG->dirroot . '/enrol/self/lib.php');

        // Prepare template context.
        $templatecontext = ['courseid' => $COURSE->id,
                'role' => role_get_name(get_guest_role()), ];

        // Search for an available self enrolment link in this course.
        $templatecontext['showselfenrollink'] = false;
        $instances = enrol_get_instances($COURSE->id, true);
        $plugins = enrol_get_plugins(true);
        foreach ($instances as $instance) {
            // If the enrolment plugin isn't enabled currently, skip it.
            if (!isset($plugins[$instance->enrol])) {
                continue;
            }

            // Remember the enrolment plugin.
            $plugin = $plugins[$instance->enrol];

            // If there is a self enrolment link.
            if ($plugin->show_enrolme_link($instance)) {
                $templatecontext['showselfenrollink'] = true;
                break;
            }
        }

        // Render template and add it to HTML code.
        $html .= $OUTPUT->render_from_template('theme_boost_union/course-hint-guestaccess', $templatecontext);
    }

    // If the setting showhintcourseselfenrol is set, a hint for users is shown that the course allows unrestricted self
    // enrolment. This hint is only shown if the course is visible, the self enrolment is visible and if the user has the
    // capability "theme/boost_union:viewhintcourseselfenrol".
    if (get_config('theme_boost_union', 'showhintcourseselfenrol') == THEME_BOOST_UNION_SETTING_SELECT_YES
            && has_capability('theme/boost_union:viewhintcourseselfenrol', \context_course::instance($COURSE->id))
            && $PAGE->has_set_url()
            && $PAGE->url->compare(new core\url('/course/view.php'), URL_MATCH_BASE)
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

            // Prepare template context.
            $templatecontext = [];

            // Add the start of the hint t the template context
            // depending on the fact if enrolment is already possible currently or will be in the future.
            if ($selfenrolmentpossiblecurrently == true) {
                $templatecontext['selfenrolhintstart'] = get_string('showhintcourseselfenrolstartcurrently', 'theme_boost_union');
            } else if ($selfenrolmentpossiblefuture == true) {
                $templatecontext['selfenrolhintstart'] = get_string('showhintcourseselfenrolstartfuture', 'theme_boost_union');
            }

            // Iterate over all enrolment instances to output the details.
            foreach ($selfenrolinstances as $selfenrolinstanceid => $selfenrolinstanceobject) {
                // If the user has the capability to config self enrolments, enrich the instance name with the settings link.
                if (has_capability('enrol/self:config', \context_course::instance($COURSE->id))) {
                    $url = new core\url('/enrol/editinstance.php', ['courseid' => $COURSE->id,
                            'id' => $selfenrolinstanceid, 'type' => 'self', ]);
                    $selfenrolinstanceobject->name = core\output\html_writer::link($url, $selfenrolinstanceobject->name);
                }

                // Add the enrolment instance information to the template context depending on the instance configuration.
                if ($selfenrolinstanceobject->unrestrictedness == 'unlimited') {
                    $templatecontext['selfenrolinstances'][] = get_string('showhintcourseselfenrolunlimited', 'theme_boost_union',
                            ['name' => $selfenrolinstanceobject->name]);
                } else if ($selfenrolinstanceobject->unrestrictedness == 'until') {
                    $templatecontext['selfenrolinstances'][] = get_string('showhintcourseselfenroluntil', 'theme_boost_union',
                            ['name' => $selfenrolinstanceobject->name,
                                    'until' => userdate($selfenrolinstanceobject->enddate), ]);
                } else if ($selfenrolinstanceobject->unrestrictedness == 'from') {
                    $templatecontext['selfenrolinstances'][] = get_string('showhintcourseselfenrolfrom', 'theme_boost_union',
                            ['name' => $selfenrolinstanceobject->name,
                                    'from' => userdate($selfenrolinstanceobject->startdate), ]);
                } else if ($selfenrolinstanceobject->unrestrictedness == 'since') {
                    $templatecontext['selfenrolinstances'][] = get_string('showhintcourseselfenrolsince', 'theme_boost_union',
                            ['name' => $selfenrolinstanceobject->name,
                                    'since' => userdate($selfenrolinstanceobject->startdate), ]);
                } else if ($selfenrolinstanceobject->unrestrictedness == 'fromuntil') {
                    $templatecontext['selfenrolinstances'][] = get_string('showhintcourseselfenrolfromuntil', 'theme_boost_union',
                            ['name' => $selfenrolinstanceobject->name,
                                    'until' => userdate($selfenrolinstanceobject->enddate),
                                    'from' => userdate($selfenrolinstanceobject->startdate), ]);
                } else if ($selfenrolinstanceobject->unrestrictedness == 'sinceuntil') {
                    $templatecontext['selfenrolinstances'][] = get_string('showhintcourseselfenrolsinceuntil', 'theme_boost_union',
                            ['name' => $selfenrolinstanceobject->name,
                                    'until' => userdate($selfenrolinstanceobject->enddate),
                                    'since' => userdate($selfenrolinstanceobject->startdate), ]);
                }
            }

            // If the user has the capability to config self enrolments, add the call for action to the template context.
            if (has_capability('enrol/self:config', \context_course::instance($COURSE->id))) {
                $templatecontext['calltoaction'] = true;
            } else {
                $templatecontext['calltoaction'] = false;
            }

            // Render template and add it to HTML code.
            $html .= $OUTPUT->render_from_template('theme_boost_union/course-hint-selfenrol', $templatecontext);
        }
    }

    // If the setting showswitchedroleincourse is set and the user has switched his role,
    // a hint for the role switch will be shown.
    if (get_config('theme_boost_union', 'showswitchedroleincourse') === THEME_BOOST_UNION_SETTING_SELECT_YES
            && is_role_switched($COURSE->id) ) {

        // Get the role name switched to.
        $opts = \user_get_user_navigation_info($USER, $PAGE);
        $role = $opts->metadata['rolename'];

        // Get the URL to switch back (normal role).
        $url = new core\url('/course/switchrole.php',
                ['id' => $COURSE->id,
                        'sesskey' => sesskey(),
                        'switchrole' => 0,
                        'returnurl' => $PAGE->url->out_as_local_url(false), ]);

        // Prepare template context.
        $templatecontext = ['role' => $role,
                'url' => $url->out(), ];

        // Render template and add it to HTML code.
        $html .= $OUTPUT->render_from_template('theme_boost_union/course-hint-switchedrole', $templatecontext);
    }

    // Return HTML code.
    return $html;
}

/**
 * Build the link to a static page.
 *
 * @param string $page The static page's identifier.
 * @return string.
 */
function theme_boost_union_get_staticpage_link($page) {
    // Compose the URL object.
    $url = new core\url('/theme/boost_union/pages/'.$page.'.php');

    // Return the string representation of the URL.
    return $url->out();
}

/**
 * Build the page title of a static page.
 *
 * @param string $page The static page's identifier.
 * @return string.
 */
function theme_boost_union_get_staticpage_pagetitle($page) {
    // Get the configured page title.
    $pagetitleconfig = format_string(get_config('theme_boost_union', $page.'pagetitle'), true,
    ['context' => \context_system::instance()]);

    // If there is a string configured.
    if ($pagetitleconfig) {
        // Return this setting.
        return $pagetitleconfig;

        // Otherwise.
    } else {
        // Return the default string.
        return get_string($page.'pagetitledefault', 'theme_boost_union');
    }
}

/**
 * Build the link to a accessibility page.
 *
 * @param string $page The accessibility page's identifier.
 * @return string.
 */
function theme_boost_union_get_accessibility_link($page) {
    // Compose the URL object.
    $url = new core\url('/theme/boost_union/accessibility/'.$page.'.php');

    // Return the string representation of the URL.
    return $url->out();
}

/**
 * Build the page title of a accessibility page.
 *
 * @param string $page The accessibility page's identifier.
 * @return string.
 */
function theme_boost_union_get_accessibility_pagetitle($page) {
    // Re-use the theme_boost_union_get_staticpage_pagetitle() as we are basically doing the same thing here.
    return theme_boost_union_get_staticpage_pagetitle('accessibility'.$page);
}

/**
 * Build the screenreader link title to the accessibility support page.
 *
 * @return string.
 */
function theme_boost_union_get_accessibility_srlinktitle() {
    $supporttitle = get_config('theme_boost_union', 'accessibilitysupportpagesrlinktitle');
    if (empty($supporttitle)) {
        $supporttitle = get_string('accessibilitysupportpagesrlinktitledefault', 'theme_boost_union');
    }

    return $supporttitle;
}

/**
 * Helper function to check if a given info banner should be shown on this page.
 * This function checks
 * a) if the banner is enabled at all
 * b) if the banner has any content (i.e. is not empty)
 * b) if the banner is configured to be shown on the given page
 * c) if the banner is configured to be shown now (in case it is a time-based banner)
 *
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  based on code from theme_boost_campus by Kathrin Osswald.
 *
 * @param int $bannerno The counting number of the info banner.
 *
 * @return boolean.
 */
function theme_boost_union_infobanner_is_shown_on_page($bannerno) {
    global $PAGE;

    // Get theme config.
    $config = get_config('theme_boost_union');

    // If the info banner is enabled.
    $enabledsettingname = 'infobanner'.$bannerno.'enabled';
    if ($config->{$enabledsettingname} == THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // If the info banner has any content.
        $contentsettingname = 'infobanner'.$bannerno.'content';
        if (!empty($config->{$contentsettingname})) {
            // If the info banner should be shown on this page.
            $pagessettingname = 'infobanner'.$bannerno.'pages';
            $showonpage = false;
            $pages = explode(',', $config->{$pagessettingname});
            foreach ($pages as $page) {
                if ($PAGE->pagelayout == $page) {
                    $showonpage = true;
                    break;
                }
            }
            if ($showonpage == true) {
                // If this is a time-based-banner.
                $modesettingname = 'infobanner'.$bannerno.'mode';
                if ($config->{$modesettingname} == THEME_BOOST_UNION_SETTING_INFOBANNERMODE_TIMEBASED) {
                    $startsettingname = 'infobanner'.$bannerno.'start';
                    $endsettingname = 'infobanner'.$bannerno.'end';
                    // Check if time settings are empty and try to convert the time strings to a unix timestamp.
                    if (empty($config->{$startsettingname})) {
                        $startempty = true;
                        $start = 0;
                    } else {
                        $startempty = false;
                        $start = $config->{$startsettingname};
                    }
                    if (empty($config->{$endsettingname})) {
                        $endempty = true;
                        $end = 0;
                    } else {
                        $endempty = false;
                        $end = $config->{$endsettingname};
                    }

                    // The banner is shown if
                    // a) now is between start and end time OR
                    // b) start is not set but end is not reached yet OR
                    // c) end is not set, but start lies in the past OR
                    // d) no dates are set, so there's no time restriction.
                    $now = time();
                    if (($now >= $start && $now <= $end ||
                            ($now <= $end && $startempty) ||
                            ($now >= $start && $endempty) ||
                            ($startempty && $endempty))) {
                        return true;
                    }

                    // Otherwise this is a perpetual banner.
                } else {
                    // If the banner was not dismissed by the user.
                    if (get_user_preferences('theme_boost_union_infobanner'.$bannerno.'_dismissed') != true) {
                        return true;
                    }
                }
            }
        }
    }

    // Apparently, the banner should not be shown on this page.
    return false;
}

/**
 * Helper function to compare either infobanner or tiles or slides orders.
 *
 * @param int $a The first value
 * @param int $b The second value
 *
 * @return boolean.
 */
function theme_boost_union_compare_order($a, $b) {
    // If the same 'order' attribute is given to both items.
    if ($a->order == $b->order) {
        // We have to compare the 'no' attribute.
        // This way, we make sure that the item which is presented first in the admin settings is still placed first in the
        // ordered list even if the same order is configured.
        return ($a->no < $b->no) ? -1 : 1;
    }

    // Otherwise, compare both items based on their 'order' attribute.
    return ($a->order < $b->order) ? -1 : 1;
}

/**
 * Helper function to reset the visibility of a given info banner.
 *
 * @param int $no The number of the info banner.
 *
 * @return bool True if everything went fine, false if at least one user couldn't be resetted.
 */
function theme_boost_union_infobanner_reset_visibility($no) {
    global $DB;

    // Clean the no parameter, just to be sure as we will use it within a user preference label (hence in a SQL query).
    $no = clean_param($no, PARAM_INT);

    // Get all users that have dismissed the info banner once and therefore the user preference.
    $whereclause = 'name = :name AND value = :value';
    $params = ['name' => 'theme_boost_union_infobanner'.$no.'_dismissed', 'value' => '1'];
    $users = $DB->get_records_select('user_preferences', $whereclause, $params, '', 'userid');

    // Initialize variable for feedback messages.
    $somethingwentwrong = false;
    // Store coding exception.
    $codingexception[] = [];

    foreach ($users as $user) {
        try {
            unset_user_preference('theme_boost_union_infobanner'.$no.'_dismissed', $user->userid);
        } catch (coding_exception $e) {
            $somethingwentwrong = true;
        }
    }

    if (!$somethingwentwrong) {
        return true;
    } else {
        return false;
    }
}

/**
 * Get the random number for displaying the background image on the login page randomly.
 *
 * @return int|null
 * @throws coding_exception
 * @throws dml_exception
 */
function theme_boost_union_get_random_loginbackgroundimage_number() {
    // Static variable.
    static $number = null;

    if ($number == null) {
        // Get all files for loginbackgroundimages.
        $files = theme_boost_union_get_loginbackgroundimage_files();

        // Get count of array elements.
        $filecount = count($files);

        // We only return a number if images are uploaded to the loginbackgroundimage file area.
        if ($filecount > 0) {
            // If Behat tests are running.
            if (defined('BEHAT_SITE_RUNNING')) {
                // Select the last image (to make Behat tests work).
                $number = $filecount;
            } else {
                // Generate random number.
                $number = rand(1, $filecount);
            }
        }
    }

    return $number;
}

/**
 * Get a random class for body tag for the background image of the login page.
 *
 * @return string
 */
function theme_boost_union_get_random_loginbackgroundimage_class() {
    // Get the static random number.
    $number = theme_boost_union_get_random_loginbackgroundimage_number();

    // Only create the class name with the random number if there is a number (=files uploaded to the file area).
    if ($number != null) {
        return 'loginbackgroundimage'.$number;
    } else {
        return '';
    }
}

/**
 * Return the files from the loginbackgroundimage file area.
 * This function always loads the files from the filearea which is not really performant.
 * However, we accept this at the moment as it is only invoked on the login page.
 *
 * @return array|null
 * @throws coding_exception
 * @throws dml_exception
 */
function theme_boost_union_get_loginbackgroundimage_files() {
    // Static variable to remember the files for subsequent calls of this function.
    static $files = null;

    if ($files == null) {
        // Get the system context.
        $systemcontext = \context_system::instance();

        // Get filearea.
        $fs = get_file_storage();

        // Get all files from filearea.
        $files = $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'loginbackgroundimage',
                false, 'itemid', false);
    }

    return $files;
}

/**
 *
 * Get the advertisement tile's background image URL from the filearea 'tilebackgroundimage'.tileno.
 *
 * Note:
 * Calling this function for each tile separately is maybe not performant. Originally it was planed to put
 * all files in one filearea. However, at the time of development
 * https://github.com/moodle/moodle/blob/master/lib/outputlib.php#L2062
 * did not support itemids in setting-files of themes.
 *
 * @param int $tileno The tile number.
 * @return string|null
 */
function theme_boost_union_get_urloftilebackgroundimage($tileno) {
    // If the tile number is apparently not valid, return.
    // Note: We just check the tile's number, we do not check if the tile is enabled or not.
    if ($tileno < 0 || $tileno > THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_COUNT) {
        return null;
    }

    // Get the background image config for this tile.
    $bgconfig = get_config('theme_boost_union', 'tile'.$tileno.'backgroundimage');

    // If a background image is configured.
    if (!empty($bgconfig)) {
        // Get the system context.
        $systemcontext = context_system::instance();

        // Get filearea.
        $fs = get_file_storage();

        // Get all files from filearea.
        $files = $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'tilebackgroundimage'.$tileno,
                false, 'itemid', false);

        // Just pick the first file - we are sure that there is just one file.
        $file = reset($files);

        // Build and return the image URL.
        return core\url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
                $file->get_itemid(), $file->get_filepath(), $file->get_filename());
    }

    // As no image was found, return null.
    return null;
}

/**
 * Get the slider's background image URL from the filearea 'slidebackgroundimage'.tileno.
 *
 * Note:
 * Calling this function for each slide separately is maybe not performant. Originally it was planed to put
 * all files in one filearea. However, at the time of development
 * https://github.com/moodle/moodle/blob/master/lib/outputlib.php#L2062
 * did not support itemids in setting-files of themes.
 *
 * @param int $slideno The slide number.
 * @return string|null
 */
function theme_boost_union_get_urlofslidebackgroundimage($slideno) {
    // If the slide number is apparently not valid, return.
    // Note: We just check the slide's number, we do not check if the slide is enabled or not.
    if ($slideno < 0 || $slideno > THEME_BOOST_UNION_SETTING_SLIDES_COUNT) {
        return null;
    }

    // Get the background image config for this slide.
    $bgconfig = get_config('theme_boost_union', 'slide'.$slideno.'backgroundimage');

    // If a background image is configured.
    if (!empty($bgconfig)) {
        // Get the system context.
        $systemcontext = context_system::instance();

        // Get filearea.
        $fs = get_file_storage();

        // Get all files from filearea.
        $files = $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'slidebackgroundimage'.$slideno,
                false, 'itemid', false);

        // Just pick the first file - we are sure that there is just one file.
        $file = reset($files);

        // Build and return the image URL.
        return core\url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
                $file->get_itemid(), $file->get_filepath(), $file->get_filename());
    }

    // As no image was found, return null.
    return null;
}

/**
 * Add background images from setting 'loginbackgroundimage' to SCSS.
 *
 * @return string
 */
function theme_boost_union_get_loginbackgroundimage_scss() {
    // Initialize variables.
    $count = 0;
    $scss = '';

    // Get all files from filearea.
    $files = theme_boost_union_get_loginbackgroundimage_files();

    // Add URL of uploaded images to equivalent class.
    foreach ($files as $file) {
        $count++;
        // Get url from file.
        $url = core\url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
                $file->get_itemid(), $file->get_filepath(), $file->get_filename());
        // Add this url to the body class loginbackgroundimage[n] as a background image.
        $scss .= 'body.pagelayout-login.loginbackgroundimage'.$count.' {';
        $scss .= 'background-image: url("'.$url.'");';
        $scss .= '}';
    }

    return $scss;
}

/**
 * Get the text that should be displayed for the randomly displayed background image on the login page.
 *
 * @return array (of two strings, holding the text and the text color)
 * @throws coding_exception
 * @throws dml_exception
 */
function theme_boost_union_get_loginbackgroundimage_text() {
    // Get the random number.
    $number = theme_boost_union_get_random_loginbackgroundimage_number();

    // Only search for the text if there's a background image.
    if ($number != null) {

        // Get the files from the filearea loginbackgroundimage.
        $files = theme_boost_union_get_loginbackgroundimage_files();
        // Get the file for the selected random number.
        $file = array_slice($files, ($number - 1), 1, false);
        // Get the filename.
        $filename = array_pop($file)->get_filename();

        // Get the config for loginbackgroundimagetext and make an array out of the lines.
        $lines = explode("\n", get_config('theme_boost_union', 'loginbackgroundimagetext'));

        // Process the lines.
        foreach ($lines as $line) {
            $settings = explode("|", $line);
            // If the line does not have three items, skip it.
            if (count($settings) != 3) {
                continue;
            }
            // Compare the filenames for a match.
            if (strcmp($filename, trim($settings[0])) == 0) {
                // Trim the second parameter as we need it more than once.
                $settings[2] = trim($settings[2]);

                // If the color value is not acceptable, replace it with dark.
                if ($settings[2] != 'dark' && $settings[2] != 'light') {
                    $settings[2] = 'dark';
                }

                // Return the text + text color that belongs to the randomly selected image.
                return [format_string(trim($settings[1])), $settings[2]];
            }
        }
    }

    return '';
}

/**
 * Return the files from the additionalresources file area as templatecontext structure.
 * It was designed to compose the files for the settings-additionalresources-filelist.mustache template.
 * This function always loads the files from the filearea which is not really performant.
 * Thus, you have to take care where and how often you use it (or add some caching).
 *
 * @return array|null
 * @throws coding_exception
 * @throws dml_exception
 */
function theme_boost_union_get_additionalresources_templatecontext() {
    global $OUTPUT;

    // Static variable to remember the files for subsequent calls of this function.
    static $filesforcontext = null;

    if ($filesforcontext == null) {
        // Get the system context.
        $systemcontext = \context_system::instance();

        // Get filearea.
        $fs = get_file_storage();

        // Get all files from filearea.
        $files = $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'additionalresources', false, 'itemid', false);

        // Iterate over the files and fill the templatecontext of the file list.
        $filesforcontext = [];
        foreach ($files as $af) {
            $urlpersistent = new core\url('/pluginfile.php/1/theme_boost_union/additionalresources/0/'.$af->get_filename());
            $urlrevisioned = new core\url('/pluginfile.php/1/theme_boost_union/additionalresources/'.theme_get_revision().
                    '/'.$af->get_filename());
            $filesforcontext[] = ['filename' => $af->get_filename(),
                                        'filetype' => $af->get_mimetype(),
                                        'filesize' => display_size($af->get_filesize()),
                                        'fileicon' => $OUTPUT->image_icon(file_file_icon($af), get_mimetype_description($af)),
                                        'fileurlpersistent' => $urlpersistent->out(),
                                        'fileurlrevisioned' => $urlrevisioned->out(), ];
        }
    }

    return $filesforcontext;
}

/**
 * Return the files from the customfonts file area as templatecontext structure.
 * It was designed to compose the files for the settings-customfonts-filelist.mustache template.
 * This function always loads the files from the filearea which is not really performant.
 * Thus, you have to take care where and how often you use it (or add some caching).
 *
 * @return array|null
 * @throws coding_exception
 * @throws dml_exception
 */
function theme_boost_union_get_customfonts_templatecontext() {
    global $OUTPUT;

    // Static variable to remember the files for subsequent calls of this function.
    static $filesforcontext = null;

    if ($filesforcontext == null) {
        // Get the system context.
        $systemcontext = \context_system::instance();

        // Get filearea.
        $fs = get_file_storage();

        // Get all files from filearea.
        $files = $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'customfonts', false, 'itemid', false);

        // Get the webfonts extensions list.
        $webfonts = theme_boost_union_get_webfonts_extensions();

        // Iterate over the files.
        $filesforcontext = [];
        foreach ($files as $af) {
            // Get the filename.
            $filename = $af->get_filename();

            // Check if the file is really a font file (as we can't really rely on the upload restriction in settings.php)
            // according to its file suffix (as the filetype might not have a known mimetype).
            // If it isn't a font file, skip it.
            $filenamesuffix = pathinfo($filename, PATHINFO_EXTENSION);
            if (!in_array('.'.$filenamesuffix, $webfonts)) {
                continue;
            }

            // Otherwise, fill the templatecontext of the file list.
            $urlpersistent = new core\url('/pluginfile.php/1/theme_boost_union/customfonts/0/'.$filename);
            $filesforcontext[] = ['filename' => $filename,
                    'fileurlpersistent' => $urlpersistent->out(), ];
        }
    }

    return $filesforcontext;
}

/**
 * Helper function which returns an array of accepted webfonts extensions (including the dots).
 *
 * @return array
 */
function theme_boost_union_get_webfonts_extensions() {
    return ['.eot', '.otf', '.svg', '.ttf', '.woff', '.woff2'];
}

/**
 * Helper function which makes sure that all webfont file types are registered in the system.
 * The webfont file types need to be registered in the system, otherwise the admin settings filepicker wouldn't allow restricting
 * the uploadable file types to webfonts only.
 *
 * Please note: If custom filetypes are defined in config.php, registering additional filetypes is not possible
 * due to a restriction in the set_custom_types() function in Moodle core. In this case, this function does not
 * register anything and will return false.
 *
 * @return boolean true if the filetypes were registered, false if not.
 * @throws coding_exception
 */
function theme_boost_union_register_webfonts_filetypes() {
    global $CFG;

    // If customfiletypes are set in config.php or PHP tests are running, we can't do anything.
    if (array_key_exists('customfiletypes', $CFG->config_php_settings) || PHPUNIT_TEST) {
        return false;
    }

    // Our array of webfont file types to register.
    // As we want to keep things simple, we do not set a particular icon for these file types.
    // Likewise, we do not set any type groups or use descriptions from the language pack.
    $webfonts = [
            'eot' => [
                    'extension' => 'eot',
                    'mimetype' => 'application/vnd.ms-fontobject',
                    'coreicon' => 'unknown',
            ],
            'otf' => [
                    'extension' => 'otf',
                    'mimetype' => 'font/otf',
                    'coreicon' => 'unknown',
            ],
            'svg' => [
                    'extension' => 'svg',
                    'mimetype' => 'image/svg+xml',
                    'coreicon' => 'unknown',
            ],
            'ttf' => [
                    'extension' => 'ttf',
                    'mimetype' => 'font/ttf',
                    'coreicon' => 'unknown',
            ],
            'woff' => [
                    'extension' => 'woff',
                    'mimetype' => 'font/woff',
                    'coreicon' => 'unknown',
            ],
            'woff2' => [
                    'extension' => 'woff2',
                    'mimetype' => 'font/woff2',
                    'coreicon' => 'unknown',
            ],
    ];

    // First, get the list of currently registered file types.
    $currenttypes = core_filetypes::get_types();

    // Iterate over the webfonts file types.
    foreach ($webfonts as $f) {
        // If the file type is already registered, skip it.
        if (array_key_exists($f['extension'], $currenttypes)) {
            continue;
        }

        // Otherwise, register the file type.
        core_filetypes::add_type($f['extension'], $f['mimetype'], $f['coreicon']);
    }

    return true;
}

/**
 * Helper function to render a preview of a HTML email to be shown on the theme settings page.
 *
 * If E-Mails have been branded, an E-Mail preview will be returned as string.
 * Otherwise, null will be returned.
 *
 * @return string|null
 */
function theme_boost_union_get_emailbrandinghtmlpreview() {
    global $OUTPUT;

    // Get branding snippets.
    $htmlprefix = get_string('templateemailhtmlprefix', 'theme_boost_union');
    $htmlsuffix = get_string('templateemailhtmlsuffix', 'theme_boost_union');

    // If no snippet was customized, return null.
    if (trim($htmlprefix) == '' && trim($htmlsuffix) == '') {
        return null;
    }

    // Otherwise, compose mail text.
    $mailtemplatecontext = ['body' => get_string('emailbrandinghtmldemobody', 'theme_boost_union')];
    $mail = $OUTPUT->render_from_template('core/email_html', $mailtemplatecontext);

    // And compose mail preview.
    $previewtemplatecontext = ['mail' => $mail, 'type' => 'Html', 'monospace' => false];
    $preview = $OUTPUT->render_from_template('theme_boost_union/emailpreview', $previewtemplatecontext);

    return $preview;
}

/**
 * Helper function to render a preview of a plaintext email to be shown on the theme settings page.
 *
 * If E-Mails have been branded, an E-Mail preview will be returned as string.
 * Otherwise, null will be returned.
 *
 * @return string|null
 */
function theme_boost_union_get_emailbrandingtextpreview() {
    global $OUTPUT;

    // Get branding snippets.
    $textprefix = get_string('templateemailtextprefix', 'theme_boost_union');
    $textsuffix = get_string('templateemailtextsuffix', 'theme_boost_union');

    // If no snippet was customized, return null.
    if (trim($textprefix) == '' && trim($textsuffix) == '') {
        return null;
    }

    // Otherwise, compose mail text.
    $mailtemplatecontext = ['body' => get_string('emailbrandingtextdemobody', 'theme_boost_union')];
    $mail = nl2br($OUTPUT->render_from_template('core/email_text', $mailtemplatecontext));

    // And compose mail preview.
    $previewtemplatecontext = ['mail' => $mail, 'type' => 'Text', 'monospace' => true];
    $preview = $OUTPUT->render_from_template('theme_boost_union/emailpreview', $previewtemplatecontext);

    return $preview;
}

/**
 * Helper function to compose the title of an external admin page.
 * This is adopted from /admin/settings.php and done to make sure that the external admin pages look as similar as possible
 * to the standard admin pages.
 *
 * @param string $pagename The page's name.
 *
 * @return string
 */
function theme_boost_union_get_externaladminpage_title($pagename) {
    global $SITE;

    $title = $SITE->shortname.': ';
    $title .= get_string('administration', 'core').': ';
    $title .= get_string('appearance', 'core').': ';
    $title .= get_string('themes', 'core').': ';
    $title .= get_string('pluginname', 'theme_boost_union').': ';
    $title .= $pagename;

    return $title;
}

/**
 * Helper function to compose the heading of an external admin page.
 * This is adopted from /admin/settings.php and done to make sure that the external admin pages look as similar as possible
 * to the standard admin pages.
 *
 * @return string
 */
function theme_boost_union_get_externaladminpage_heading() {
    global $SITE;

    return $SITE->fullname;
}

/**
 * Helper function which returns the course header image url, picking the current course from the course settings
 * or the fallback image from the theme.
 * If no course header image can should be shown for the current course, the function returns null.
 *
 * @return null | string
 */
function theme_boost_union_get_course_header_image_url() {
    global $PAGE;

    // If the current course is the frontpage course (which means that we are not within any real course),
    // directly return null.
    if (isset($PAGE->course->id) && $PAGE->course->id == SITEID) {
        return null;
    }

    // Get the course image.
    $courseimage = \core_course\external\course_summary_exporter::get_course_image($PAGE->course);

    // If the course has a course image.
    if ($courseimage) {
        // Then return it directly.
        return $courseimage;

        // Otherwise, if a fallback image is configured.
    } else if (get_config('theme_boost_union', 'courseheaderimagefallback')) {
        // Get the system context.
        $systemcontext = \context_system::instance();

        // Get filearea.
        $fs = get_file_storage();

        // Get all files from filearea.
        $files = $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'courseheaderimagefallback',
            false, 'itemid', false);

        // Just pick the first file - we are sure that there is just one file.
        $file = reset($files);

        // Build and return the image URL.
        return core\url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
            $file->get_itemid(), $file->get_filepath(), $file->get_filename());
    }

    // As no picture was found, return null.
    return null;
}

/**
 * Helper function which sets the URL to the CSS file as soon as the theme's mobilescss setting has any CSS code.
 * It's meant to be called as callback when changing the admin setting only.
 * *
 * @throws coding_exception
 * @throws dml_exception
 * @throws moodle_exception
 */
function theme_boost_union_set_mobilecss_url() {
    // Check if the admin has set any CSS code for the Mobile app.
    $csscode = get_config('theme_boost_union', 'mobilescss');
    if (!empty($csscode)) {
        // Build the Mobile app CSS file URL and especially add the current time as rev parameter.
        // This parameter isn't the theme revision as the theme cache is not cleared when this setting is stored.
        // It is just the time when the setting is saved.
        // This is the best we can do to make the Mobile app load the new styles when needed.
        $mobilescssurl = new core\url('/theme/boost_union/mobile/styles.php', ['rev' => time()]);

        // Set the $CFG->mobilecssurl setting.
        set_config('mobilecssurl', $mobilescssurl->out());

        // Otherwise.
    } else {
        // Clear the $CFG->mobilecssurl setting.
        set_config('mobilecssurl', '');
    }
}

/**
 * Returns an array of the defined additional block regions.
 *
 * @param array $pageregions List of page regions.
 * @return array $regions
 */
function theme_boost_union_get_additional_regions($pageregions=[]) {
    $regions = [
            'footerleft' => 'footer-left',
            'footerright' => 'footer-right',
            'footercenter' => 'footer-center',
            'offcanvasleft' => 'offcanvas-left',
            'offcanvasright' => 'offcanvas-right',
            'offcanvascenter' => 'offcanvas-center',
            'outsideleft' => 'outside-left',
            'outsideright' => 'outside-right',
            'outsidetop' => 'outside-top',
            'outsidebottom' => 'outside-bottom',
            'contentupper' => 'content-upper',
            'contentlower' => 'content-lower',
            'header' => 'header',
    ];

    return ($pageregions) ? array_intersect($regions, $pageregions) : $regions;
}

/**
 * Get the defined regions for the page layout.
 *
 * @param string $layout Pagelayout name.
 * @return array $regions
 */
function theme_boost_union_get_block_regions($layout) {

    // Get the admin setting for the layout.
    $regionsettings = get_config('theme_boost_union', 'blockregionsfor'.$layout);

    // Explode the admin setting to get the block regions.
    $settings = !empty($regionsettings) ? explode(',', $regionsettings) : [];

    // Add the configured regions to the side-pre region (which is always provided by Boost core).
    $regions = array_merge(['side-pre'], $settings);

    // Return.
    return $regions;
}

/**
 * Callback function which is called from settings.php if the enable custom activity icons setting has changed.
 *
 * It checks if the setting has just been disabled. If yes, it removes all custom icons from the
 * the pix_plugins/mod folder in the Moodledata directory as they are not needed anymore.
 */
function theme_boost_union_check_mod_icons_cleanup() {
    global $CFG;

    // Get the modiconsenable setting.
    $modiconsenable = get_config('theme_boost_union', 'modiconsenable');

    // If modiconsenable was just enabled, return directly as everything is fine.
    if ($modiconsenable != THEME_BOOST_UNION_SETTING_SELECT_NO) {
        return;
    }

    // Purge the content of the pix_plugins/mod folder in Moodledata.
    $pixpluginpath = $CFG->dataroot.DIRECTORY_SEPARATOR.'pix_plugins'.DIRECTORY_SEPARATOR.'mod';
    if (is_dir($pixpluginpath)) {
        remove_dir($pixpluginpath, false);
    }

    // Purge the theme cache to show the old icons in the GUI.
    theme_reset_all_caches();
}

/**
 * Callback function which is called from settings.php if the custom activity icons files setting has changed.
 *
 * First, it deletes all files that are placed within the pix_plugins/mod folder in the Moodledata directory
 * (see https://github.com/moodle/moodle/blob/15d4ea81e003439c528004a8d555a07cad0f02d3/lib/outputlib.php#L2151-L2169,
 * this location is used as third fallback for activity icons after looking for an icon in the theme and the parent theme).
 *
 * Then, it gets all icons from the files setting, picks all the valid icons which are in a folder of a valid activity
 * and stores them into the pix_plugins/mod folder in the Moodledata directory again.
 *
 * @throws coding_exception
 * @throws dml_exception
 * @throws moodle_exception
 */
function theme_boost_union_place_mod_icons() {
    global $CFG, $DB;

    // Get the modiconsenable setting.
    $modiconsenable = get_config('theme_boost_union', 'modiconsenable');

    // If modiconsenable is not enabled, return directly as we do not want to modify the placed icons.
    if ($modiconsenable != THEME_BOOST_UNION_SETTING_SELECT_YES) {
        return;
    }

    // Purge the content of the pix_plugins/mod folder in Moodledata.
    $pixpluginpath = $CFG->dataroot.DIRECTORY_SEPARATOR.'pix_plugins'.DIRECTORY_SEPARATOR.'mod';
    if (is_dir($pixpluginpath)) {
        remove_dir($pixpluginpath, true);
    }

    // Get the system context.
    $systemcontext = \context_system::instance();

    // Get filearea.
    $fs = get_file_storage();

    // Get all files from filearea.
    $files = $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'modicons', false, 'itemid', true);

    // Get installed activity plugins.
    $modules = $DB->get_records('modules', [], '', 'name');

    // Iterate over the files.
    foreach ($files as $file) {
        // Pick the filename and extension.
        $trimmedfilename = pathinfo($file->get_filename(), PATHINFO_FILENAME);
        $trimmedextension = pathinfo($file->get_filename(), PATHINFO_EXTENSION);

        // If the extension is _not_ svg or png.
        if (!($trimmedextension === 'png' || $trimmedextension === 'svg')) {
            // Skip the file.
            continue;
        }

        // If the filename is _not_ icon or monologo.
        if (!($trimmedfilename === 'icon' || $trimmedfilename === 'monologo')) {
            // Skip the file.
            continue;
        }

        // Get the number of the path size.
        // We expect the use files within one folder. Such paths have a path size of three.
        // (One before the leading slash, one for the folder, one for the file).
        $pathsize = count(explode(DIRECTORY_SEPARATOR, $file->get_filepath()));

        // If the file is not placed within a single folder.
        if (empty($file->get_filepath()) || $pathsize != 3) {
            // Skip the file.
            continue;
        }

        // Pick the folder name.
        $trimmedfolder = trim($file->get_filepath(), DIRECTORY_SEPARATOR);

        // If the folder does not have a valid activity name.
        if (!array_key_exists($trimmedfolder, $modules)) {
            // Skip the file.
            continue;
        }

        // Compose the path for the icon's folder in Moodledata.
        $path = $pixpluginpath.DIRECTORY_SEPARATOR.$trimmedfolder;

        // Create the folder.
        check_dir_exists($path, true, true);

        // Write the file to Moodledata.
        if (!empty($file)) {
            $file->copy_content_to($path.DIRECTORY_SEPARATOR.$file->get_filename());
        }
    }

    // Purge the theme cache to show the new icons in the GUI.
    theme_reset_all_caches();
}

/**
 * Return the custom icons from the modiconsfiles file area as templatecontext structure.
 * It was designed to compose the files for the settings-modicon-filelist.mustache template.
 * This function always loads the files from the filearea which is not really performant.
 * Thus, you have to take care where and how often you use it (or add some caching).
 *
 * @return array
 * @throws coding_exception
 * @throws dml_exception
 */
function theme_boost_union_get_modicon_templatecontext () {
    global $DB;

    // Get the system context.
    $systemcontext = \context_system::instance();

    // Get filearea.
    $fs = get_file_storage();

    // Get all files from filearea.
    $files = $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'modicons', false, 'filepath,filename', true);

    // Get installed activity plugins.
    $modules = $DB->get_records('modules', [], '', 'name');

    // Initialize template data.
    $templatedata = [];

    // Iterate over the files.
    foreach ($files as $file) {
        // Initialize template object.
        $templateobject = new stdClass();

        // Pick the filename and extension.
        $trimmedfilename = pathinfo($file->get_filename(), PATHINFO_FILENAME);
        $trimmedextension = pathinfo($file->get_filename(), PATHINFO_EXTENSION);

        // Check if we have a Moodle 4 icon, a Moodle 4 legacy icon or none of both.
        if (!($trimmedfilename === 'icon' || $trimmedfilename === 'monologo') ||
                !($trimmedextension === 'svg' || $trimmedextension === 'png')) {
            $templateobject->invalidname = true;
        } else if ($trimmedfilename === 'monologo') {
            $templateobject->moodle4 = true;
        } else if ($trimmedfilename === 'icon') {
            $templateobject->moodle3 = true;
        }

        // Get the number of the path size.
        // We expect the use files within one folder. Such paths have a path size of three.
        // (One before the leading slash, one for the folder, one for the file).
        $pathsize = count(explode(DIRECTORY_SEPARATOR, $file->get_filepath()));

        // Skip the root directory and all folder dot files.
        if ($pathsize < 2 || $file->get_filename() == '.') {
            continue;
        }

        // Compose and add the path to the template object.
        $templateobject->path = $file->get_filepath().$file->get_filename();

        // If we have a file within one single folder.
        if (!empty($file->get_filepath()) && $pathsize == 3) {
            // If the folder has a valid activity name.
            $foldername = trim($file->get_filepath(), DIRECTORY_SEPARATOR);
            if (array_key_exists($foldername, $modules)) {
                // Add the activity name to the template object.
                $templateobject->mod = get_string('modulename', $foldername);
            }
        }

        // Add the template object to the template data stack.
        array_push($templatedata, $templateobject);
    }

    return $templatedata;
}

/**
 * Returns the SCSS code to modify the activity icon purpose.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_scss_for_activity_icon_purpose($theme) {
    // Initialize SCSS snippet.
    $scss = '';

    // Get installed activity modules.
    $installedactivities = get_module_types_names();

    // Iterate over all existing activities.
    foreach ($installedactivities as $modname => $modinfo) {
        // Get default purpose of activity module.
        $defaultpurpose = plugin_supports('mod', $modname, FEATURE_MOD_PURPOSE, MOD_PURPOSE_OTHER);

        // If the plugin does not have any default purpose.
        if (!$defaultpurpose) {
            // Fallback to "other" purpose.
            $defaultpurpose = MOD_PURPOSE_OTHER;
        }

        // Compose selectors for blocks.
        $blocksscss = [];
        // If the admin wanted us to tint the timeline block as well.
        if (get_config('theme_boost_union', 'timelinetintenabled') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            $blocksscss[] = '.block_timeline .theme-boost-union-mod_'.$modname.'.activityiconcontainer img';
        }
        // If the admin wanted us to tint the upcoming events block as well.
        if (get_config('theme_boost_union', 'upcomingeventstintenabled') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            $blocksscss[] = '.block_calendar_upcoming .theme-boost-union-mod_'.$modname.'.activityiconcontainer img';
        }
        // If the admin wanted us to tint the recently accessed items block as well.
        if (get_config('theme_boost_union', 'recentlyaccesseditemstintenabled') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            $blocksscss[] = '.block_recentlyaccesseditems .theme-boost-union-'.$modname.'.activityiconcontainer img';
        }
        // If the admin wanted us to tint the activities block as well.
        if (get_config('theme_boost_union', 'activitiestintenabled') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            $blocksscss[] = '.block_activity_modules .content .icon[title="'.$modinfo.'"]';
        }
        $blocksscss = implode(', ', $blocksscss);

        // If the activity purpose setting is set and differs from the activity's default purpose.
        $activitypurpose = get_config('theme_boost_union', 'activitypurpose'.$modname);
        if ($activitypurpose && $activitypurpose != $defaultpurpose) {
            // Add CSS to modify the activity purpose color in the activity chooser and the activity icon.
            $scss .= '.activity.modtype_'.$modname.' .activityiconcontainer.courseicon img,';
            $scss .= '.modchoosercontainer .modicon_'.$modname.'.activityiconcontainer img,';
            $scss .= '#page-header .modicon_'.$modname.'.activityiconcontainer img';
            // Add CSS for the configured blocks.
            if (strlen($blocksscss) > 0) {
                $scss .= ', '.$blocksscss;
            }
            $scss .= ' {';
            // If the purpose is now different than 'other', change the filter to the new color.
            if ($activitypurpose != MOD_PURPOSE_OTHER) {
                $scss .= 'filter: var(--activity' . $activitypurpose . ') !important;';

                // Otherwise, the filter is removed (as there is no '--activityother' variable).
            } else {
                $scss .= 'filter: none !important;';
            }
            $scss .= '}';

            // Otherwise, if the purpose is unchanged.
        } else {
            // Add CSS for the configured blocks.
            if (strlen($blocksscss) > 0) {
                $scss .= $blocksscss.'{ ';

                // If the purpose is now different than 'other', set the filter to tint the icon.
                if ($activitypurpose != MOD_PURPOSE_OTHER) {
                    $scss .= 'filter: var(--activity' . $defaultpurpose . ') !important;';
                }

                $scss .= '}';
            }
        }
    }
    return $scss;
}

/**
 * Returns the SCSS code to add an external link icon after external links to mark them visually.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_scss_to_mark_external_links($theme) {
    global $CFG;

    // Initialize SCSS snippet.
    $scss = '';

    // If the corresponding setting is set to 'yes'.
    $markexternallinksconfig = get_config('theme_boost_union', 'markexternallinks');
    if (isset($markexternallinksconfig) && $markexternallinksconfig == THEME_BOOST_UNION_SETTING_SELECT_YES) {

        // Get the scope setting.
        $scope = get_config('theme_boost_union', 'markexternallinksscope');

        // Prepare the CSS selectors depending on the configured scope.
        switch ($scope) {
            case THEME_BOOST_UNION_SETTING_MARKLINKS_COURSEMAIN:
                $topltrselector = 'body.dir-ltr.path-course-view #region-main';
                $toprtlselector = 'body.dir-rtl.path-course-view #region-main';
                break;
            case THEME_BOOST_UNION_SETTING_MARKLINKS_WHOLEPAGE:
            default:
                $topltrselector = 'body.dir-ltr';
                $toprtlselector = 'body.dir-rtl';
                break;
        }

        // SCSS to add external link icon after the link and respect LTR and RTL while doing this.
        $scss = $topltrselector.' a:not([href^="' . $CFG->wwwroot . '"])[href^="http://"]::after,'.
                $topltrselector.' a:not([href^="' . $CFG->wwwroot . '"])[href^="https://"]::after {
            @include externallink(ltr);
        }';
        $scss .= $toprtlselector.' a:not([href^="' . $CFG->wwwroot . '"])[href^="http://"]::before,'.
                $toprtlselector.' a:not([href^="' . $CFG->wwwroot . '"])[href^="https://"]::before {
            @include externallink(rtl);
        }';

        // Revert some things depending on the configured scope.
        if ($scope == THEME_BOOST_UNION_SETTING_MARKLINKS_WHOLEPAGE) {
            // While adding the external link icon to text links is perfectly fine and intended, the SCSS code also
            // matches on image links. And this should be avoided for optical reasons.
            // Unfortunately, we can't select _all_ images which are surrounded by links with pure CSS.
            // But we can at least revert the external link icon in images and other assets which we know that should not
            // get it:
            // * Everything inside the frontpage slider.
            $scss .= '#themeboostunionslider a::before, #themeboostunionslider a::after {
                display: none;
            }';

            // Moodle adds a hardcoded external-link icon to several links:
            // * The "services and support" link in the questionmark menu (which should point to moodle.com/help, but may also point
            // to the URL in the $CFG->servicespage setting).
            // * The "contact site support" link in the questionmark menu (as soon as the URL in the $CFG->supportpage setting is
            // set).
            // * The links to the Moodle docs (which are created with the get_docs_url() helper function).
            // * The "Chat to course participants" link in the questionmark menu (as soon as the communication setting is set within
            // a course).
            // * The "Give feedback about this software" link in the questionmark menu (if the $CFG->enableuserfeedback setting
            // is enabled).
            // * The "EXIF remover" link on /admin/settings.php?section=exifremover.
            // * Anything else which is shown in the call-to-action notification banners on the Dashboard
            // (Currently just the "Give feedback about this software" link as well).
            // These icons become obsolete now. We remove them with the sledgehammer.
            $scss .= '.footer-support-link a[href^="https://moodle.com/help/"] .fa-arrow-up-right-from-square,
                    .footer-support-link a[target="_blank"] .fa-arrow-up-right-from-square';
            if (!empty($CFG->servicespage)) {
                $scss .= ', .footer-support-link a[href="'.$CFG->servicespage.'"] .fa-arrow-up-right-from-square';
            }
            if (!empty($CFG->supportpage)) {
                $scss .= ', a[href="'.$CFG->supportpage.'"] .fa-arrow-up-right-from-square';
            }
            if (!empty($CFG->enableuserfeedback)) {
                $scss .= ', a[href^="https://feedback.moodle.org"] .fa-arrow-up-right-from-square,
                a[href^="https://feedback.moodle.org"] .ms-1';
            }
            $scss .= ', a[href^="'.get_docs_url().'"] .fa-arrow-up-right-from-square,
                    a[href^="https://exiftool.sourceforge.net"] .fa-arrow-up-right-from-square,
                    div.cta a .fa-arrow-up-right-from-square {
                display: none;
            }';
        }
    }
    return $scss;
}

/**
 * Returns the SCSS to add a broken-chain symbol in front of broken links and make the font red to mark them visually.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_scss_to_mark_broken_links($theme) {
    // Initialize SCSS snippet.
    $scss = '';

    // If the corresponding setting is set to 'yes'.
    $markbrokenlinksconfig = get_config('theme_boost_union', 'markbrokenlinks');
    if (isset($markbrokenlinksconfig) && $markbrokenlinksconfig == THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Set font color to the 'danger' color.
        $scss .= 'a[href*="/brokenfile.php"] {
            color: $danger;
        }';

        // SCSS to add broken-chain icon in front of the link and respect LTR and RTL while doing this.
        $scss .= 'body.dir-ltr a[href*="/brokenfile.php"]::before {
            font-family: "#{$fa-style-family}";
            content: "\f127" !important;
            font-weight: 900;
            padding-right: 0.25rem;
        }';
        $scss .= 'body.dir-rtl a[href*="/brokenfile.php"]::after {
            font-family: "#{$fa-style-family}";
            content: "\f127" !important;
            font-weight: 900;
            padding-left: 0.25rem;
        }';
    }

    return $scss;
}

/**
 * Returns the SCSS to add an envelope symbol in front of mailto links to mark them visually.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_scss_to_mark_mailto_links($theme) {
    // Initialize SCSS snippet.
    $scss = '';

    // If the corresponding setting is set to 'yes'.
    $markmailtolinksconfig = get_config('theme_boost_union', 'markmailtolinks');
    if (isset($markmailtolinksconfig) && $markmailtolinksconfig == THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Get the scope setting.
        $scope = get_config('theme_boost_union', 'markmailtolinksscope');

        // Prepare the CSS selectors depending on the configured scope.
        switch ($scope) {
            case THEME_BOOST_UNION_SETTING_MARKLINKS_COURSEMAIN:
                $topltrselector = 'body.dir-ltr.path-course-view #region-main';
                $toprtlselector = 'body.dir-rtl.path-course-view #region-main';
                break;
            case THEME_BOOST_UNION_SETTING_MARKLINKS_WHOLEPAGE:
            default:
                $topltrselector = 'body.dir-ltr';
                $toprtlselector = 'body.dir-rtl';
                break;
        }

        // SCSS to add envelope icon in front of the link and respect LTR and RTL while doing this.
        $scss .= $topltrselector.' a[href^="mailto"]::before {
            @include mailtolink(ltr);
        }';
        $scss .= $toprtlselector.' a[href^="mailto"]::after {
            @include mailtolink(rtl);
        }';
    }

    return $scss;
}

/**
 * Returns the SCSS code to hide the course image and/or the course progress in the course overview block, depending
 * on the theme settings courseoverviewshowcourseimages and courseoverviewshowcourseprogress respectively.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_scss_courseoverview_block($theme) {
    // Initialize SCSS snippet.
    $scss = '';

    // Selector for the course overview block.
    $blockselector = '.block_myoverview.block div[data-region="courses-view"]';

    // Get the course image setting, defaults to true if the setting does not exist.
    $courseoverviewshowcourseimagesconfig = get_config('theme_boost_union', 'courseoverviewshowcourseimages');
    if (!isset($courseoverviewshowcourseimagesconfig)) {
        $showcourseimagescard = true;
        $showcourseimageslist = true;
        $showimagessummary = true;
    } else {
        $showcourseimages = explode(',', $courseoverviewshowcourseimagesconfig);
        $showcourseimagescard = in_array(THEME_BOOST_UNION_SETTING_COURSEOVERVIEW_SHOWCOURSEIMAGES_CARD, $showcourseimages);
        $showcourseimageslist = in_array(THEME_BOOST_UNION_SETTING_COURSEOVERVIEW_SHOWCOURSEIMAGES_LIST, $showcourseimages);
        $showimagessummary = in_array(THEME_BOOST_UNION_SETTING_COURSEOVERVIEW_SHOWCOURSEIMAGES_SUMMARY, $showcourseimages);
    }

    // If the corresponding settings are set to false.
    if (!$showimagessummary) {
        $listitemselector = $blockselector.' .course-summaryitem > .row ';
        $scss .= $listitemselector.'> .col-md-2 { display: none !important; }'.PHP_EOL;
        $scss .= $listitemselector.'> .col-md-9 { @extend .col-md-11; }'.PHP_EOL;
    }
    if (!$showcourseimageslist) {
        $listitemselector = $blockselector.' .course-listitem:not(.course-summaryitem) > .row ';
        $scss .= $listitemselector.'> .col-md-2 { display: none !important; }'.PHP_EOL;
        $scss .= $listitemselector.'> .col-md-9 { @extend .col-md-11; }'.PHP_EOL;
    }
    if (!$showcourseimagescard) {
        $scss .= $blockselector.' .card-img-top { display: none !important; }'.PHP_EOL;
    }

    // Get the course progress setting, defaults to true if the setting does not exist.
    $courseoverviewshowcourseprogressconfig = get_config('theme_boost_union', 'courseoverviewshowcourseprogress');
    if (!isset($courseoverviewshowcourseprogressconfig) ||
            $courseoverviewshowcourseprogressconfig == THEME_BOOST_UNION_SETTING_SELECT_YES) {
        $showcourseprogress = true;
    } else {
        $showcourseprogress = false;
    }

    // If the corresponding setting is set to false.
    if (!$showcourseprogress) {
        $scss .= $blockselector.' .progress-text { display: none !important; }'.PHP_EOL;
    }

    return $scss;
}

/**
 * Helper function which returns an array of login methods on the login page.
 *
 * @return array
 */
function theme_boost_union_get_loginpage_methods() {
    return [1 => 'local',
            2 => 'idp',
            3 => 'firsttimesignup',
            4 => 'guest',
    ];
}

/**
 * Returns the SCSS code to re-order the elements of the login form, depending on the theme settings loginorder*.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_scss_login_order($theme) {
    // Initialize SCSS snippet.
    $scss = '';

    // Get the login methods.
    $loginmethods = theme_boost_union_get_loginpage_methods();

    // If the default orders are unchanged.
    $unchanged = true;
    foreach ($loginmethods as $key => $lm) {
        $setting = get_config('theme_boost_union', 'loginorder'.$lm);
        if ($setting != $key) {
            $unchanged = false;
        }
    }
    if ($unchanged == true) {
        // Hide the first login-divider (as we have added login-dividers to all orderable login methods,
        // but do not want a divider between the page heading and the first login method).
        $scss .= '#theme_boost_union-loginorder .theme_boost_union-loginmethod:first-of-type .login-divider { display: none; }';

        // Return the SCSS code as we are done.
        return $scss;
    }

    // Make the loginform a flexbox.
    $scss .= '#theme_boost_union-loginorder { display: flex; flex-direction: column; }';

    // Initialize a variable to detect the very first method.
    $veryfirstmethodname = '';
    $veryfirstmethodorder = 99; // This assumes that we will never have more than 99 login methods which should be fair.

    // Iterate over all login methods.
    foreach ($loginmethods as $lm) {
        // Set the flexbox order for this login method.
        $setting = get_config('theme_boost_union', 'loginorder'.$lm);
        $scss .= '#theme_boost_union-loginorder-'.$lm.' { order: '.$setting.'; }';

        // If no other login method has a lower order than this one.
        if ($setting < $veryfirstmethodorder) {
            // Remember this login method as very first method.
            $veryfirstmethodorder = $setting;
            $veryfirstmethodname = $lm;
        }
    }

    // Hide the first login-divider - similar to the 'unchanged settings' case, but in this case based on the flexbox orders.
    $scss .= '#theme_boost_union-loginorder-'.$veryfirstmethodname.' .login-divider { display: none; }';

    return $scss;
}

/**
 * Helper function which returns the list of possible touch icons for iOS.
 *
 * @return array A multidimensional array
 */
function theme_boost_union_get_touchicons_for_ios() {
    $filenameprefix = 'apple-icon-';
    $filenamesuffixes = ['jpg', 'png'];

    $recommendedsizes = ['120x120', '152x152', '167x167', '180x180'];
    $optionalsizes = ['57x57', '60x60', '72x72', '76x76', '114x114', '144x144'];

    return [
        'filenameprefix' => $filenameprefix,
        'filenamesuffixes' => $filenamesuffixes,
        'sizes' => [
            'recommended' => $recommendedsizes,
            'optional' => $optionalsizes,
        ],
        'filenames' => [
            'recommended' => preg_filter('/^/', $filenameprefix, $recommendedsizes),
            'optional' => preg_filter('/^/', $filenameprefix, $optionalsizes),
        ],
    ];
}

/**
 * Callback function which is called from settings.php if the touch icon files for iOS setting has changed.
 *
 * It gets all files from the files setting, picks all the expected files (and ignores all others)
 * and stores them into an application cache for quicker access.
 *
 * @return void
 */
function theme_boost_union_touchicons_for_ios_checkin() {
    // Create cache for touch icon files.
    $cache = cache::make('theme_boost_union', 'touchiconsios');

    // Purge the existing cache values as we will refill the cache now.
    $cache->purge();

    // Get list of possible touch icons for iOS.
    $touchiconsios = theme_boost_union_get_touchicons_for_ios();

    // Initialize the file list with all possible files.
    $filelist = [];
    foreach ($touchiconsios['filenames']['recommended'] as $ti) {
        $candidatefile = new stdClass();
        $candidatefile->exists = false;
        $candidatefile->recommended = true;
        $candidatefile->filename = $ti;
        $candidatefile->size = str_replace($touchiconsios['filenameprefix'], '', $ti);
        $filelist[$ti] = $candidatefile;
    }
    foreach ($touchiconsios['filenames']['optional'] as $ti) {
        $candidatefile = new stdClass();
        $candidatefile->exists = false;
        $candidatefile->recommended = false;
        $candidatefile->filename = $ti;
        $candidatefile->size = str_replace($touchiconsios['filenameprefix'], '', $ti);
        $filelist[$ti] = $candidatefile;
    }

    // Get the system context.
    $systemcontext = \context_system::instance();

    // Get filearea.
    $fs = get_file_storage();

    // Get touch icon files.
    $files = $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'touchiconsios', false, 'itemid', false);

    // Iterate over the uploaded files and fill the file list.
    foreach ($files as $file) {
        // Get the filename including extension.
        $filename = $file->get_filename();

        // Get the filename without extension.
        $filenamewithoutext = pathinfo($filename,  PATHINFO_FILENAME);

        // If the filename is a recommended filename or if it is an optional filename.
        if (in_array($filenamewithoutext, $touchiconsios['filenames']['recommended']) ||
            in_array($filenamewithoutext, $touchiconsios['filenames']['optional'])) {
            // Get the file extension.
            $filenameextension = pathinfo($filename, PATHINFO_EXTENSION);

            // If the file extension is a valid extension.
            if (in_array($filenameextension, $touchiconsios['filenamesuffixes'])) {
                // Set the exists flag in the return array.
                $filelist[$filenamewithoutext]->exists = true;

                // And set the full filename including suffix.
                $filelist[$filenamewithoutext]->filename = $filename;
            }
        }
    }

    // Add the file list to the cache.
    $cache->set('filelist', $filelist);

    // Add a marker value to the cache which indicates that the files have been checked into the cache completely.
    // This will help to decide later if the cache is really empty (and should be refilled) or if there aren't just any
    // files uploaded.
    $cache->set('checkedin', true);
}

/**
 * Helper function which returns the templatecontext with the file list for the uploaded touch icons for iOS.
 *
 * @return array The array of files.
 */
function theme_boost_union_get_touchicons_for_ios_templatecontext() {
    // Create cache for touch icon files.
    $cache = cache::make('theme_boost_union', 'touchiconsios');

    // If the cache is completely empty, check the files in on-the-fly.
    if ($cache->get('checkedin') != true) {
        theme_boost_union_touchicons_for_ios_checkin();
    }

    // Get the cached file list.
    $filelist = $cache->get('filelist');

    // The filelist in the cache is already structured in a way that it can be directly used as templatecontext :).
    // Thus, return the templatecontext (and remove the array indices for proper rendering in Mustache).
    return array_values($filelist);
}

/**
 * Returns the HTML code to add the touch icons to the page.
 *
 * @return string
 */
function theme_boost_union_get_touchicons_html_for_page() {
    // Create cache for touch icon files for iOS.
    $cache = cache::make('theme_boost_union', 'touchiconsios');

    // If the cache is completely empty, check the files in on-the-fly.
    if ($cache->get('checkedin') != true) {
        theme_boost_union_touchicons_for_ios_checkin();
    }

    // Get the cached file list.
    $filelist = $cache->get('filelist');

    // Initialize string.
    $touchiconstring = '';

    // If there are files uploaded.
    if (is_array($filelist) && count($filelist) > 0) {
        // Iterate over the files and fill the string with the file list.
        foreach ($filelist as $file) {
            // If the file exists (i.e. it has been uploaded).
            if ($file->exists == true) {
                // Build the file URL.
                $fileurl = new core\url('/pluginfile.php/1/theme_boost_union/touchiconsios/' .
                    theme_get_revision().'/'.$file->filename);

                // Compose and append the HTML tag.
                $touchiconstring .= '<link rel="apple-touch-icon" sizes="';
                $touchiconstring .= $file->size;
                $touchiconstring .= '" href="'.$fileurl->out().'">';
            }
        }
    }

    // Return the string.
    return $touchiconstring;
}

/**
 * Helper function to map Boost Union settings ('yes'/'no') to corresponding string values ('true'/'false')
 * This is needed for Bootstrap which expects string boolean values.
 *
 * @param string $var Either 'yes' or 'no'
 */
function theme_boost_union_yesno_to_boolstring($var) {
    if ($var == THEME_BOOST_UNION_SETTING_SELECT_YES) {
        return 'true';
    } else {
        return 'false';
    }
}

/**
 * Returns the HTML code for the starred courses popover.
 * It fetches all favorite courses and renders them as a popover menu.
 *
 * This function is copied and modified from block_starredcourses_external::get_starred_courses()
 *
 * @return string HTML to display in the navbar.
 */
function theme_boost_union_get_navbar_starredcoursespopover() {
    global $USER, $OUTPUT;

    // If a theme other than Boost Union or a child theme of it is active, return directly.
    // This is necessary as the callback is called regardless of the active theme.
    if (theme_boost_union_is_active_theme() != true) {
        return '';
    }

    // The popover is relevant only for logged-in users. If the user is not logged in, return directly.
    if (!isloggedin()) {
        return '';
    }

    // If the popover is disabled, return directly.
    $setting = get_config('theme_boost_union', 'shownavbarstarredcourses');
    if (!isset($setting) || $setting != THEME_BOOST_UNION_SETTING_SELECT_YES) {
        return '';
    }

    // Get the user context.
    $usercontext = context_user::instance($USER->id);

    // Get the user favourites service, scoped to a single user (their favourites only).
    $userservice = \core_favourites\service_factory::get_service_for_user_context($usercontext);

    // Get the favourites, by type, for the user.
    $favourites = $userservice->find_favourites_by_type('core_course', 'courses');

    // If there aren't any favourite courses, return directly.
    if (!$favourites) {
        return '';
    }

    // Pick the course IDs from the course objects.
    $favouritecourseids = array_map(
        function($favourite) {
            return $favourite->itemid;
        }, $favourites);

    // Get all courses that the current user is enrolled in, restricted down to favourites.
    $filteredcourses = [];
    if ($favouritecourseids) {
        $courses = course_get_enrolled_courses_for_logged_in_user(0, 0, null, null,
            COURSE_DB_QUERY_LIMIT, $favouritecourseids);
        list($filteredcourses, $processedcount) = course_filter_courses_by_favourites(
            $courses,
            $favouritecourseids,
            0
        );
    }
    // Grab the course ids.
    $filteredcourseids = array_column($filteredcourses, 'id');

    // Filter out any favourites that are not in the list of enroled courses.
    $filteredfavourites = array_filter($favourites, function($favourite) use ($filteredcourseids) {
        return in_array($favourite->itemid, $filteredcourseids);
    });

    // Compose the template context.
    $coursesfortemplate = [];
    foreach ($filteredfavourites as $favourite) {
        $course = get_course($favourite->itemid);
        $context = \context_course::instance($favourite->itemid);
        $canviewhiddencourses = has_capability('moodle/course:viewhiddencourses', $context);

        if ($course->visible || $canviewhiddencourses) {
            $coursesfortemplate[] = [
                'url' => new \core\url('/course/view.php', ['id' => $course->id]),
                'fullname' => $course->fullname,
                'visible' => $course->visible == 1,
            ];
        }
    }

    // Sort the favourites by name (if there is anything to be sorted).
    if (count($coursesfortemplate) > 1) {
        usort($coursesfortemplate, function($a, $b) {
            if ($a['fullname'] == $b['fullname']) {
                return 0;
            }

            return strcasecmp(trim($a['fullname']), trim($b['fullname']));
        });
    }

    // Compose the popover menu.
    $html = $OUTPUT->render_from_template('theme_boost_union/popover-favourites', ['favourites' => $coursesfortemplate]);

    return $html;
}

/**
 * Callback to add head elements.
 * This function is implemented here and used from two locations:
 * -> function theme_boost_union_before_standard_html_head in lib.php (for releases up to Moodle 4.3)
 * -> class theme_boost_union\local\hook\output\before_standard_head_html_generation (for releases from Moodle 4.4 on).
 *
 * We use this callback
 * -> to inject the flavour's CSS code to the page
 * -> to add the touch icons to the page
 *
 * @param \core\hook\output\before_standard_head_html_generation $hook If the hook is passed, the hook implementation will
 *                                                                     be used. If not, the legacy implementation will
 *                                                                     be used.
 * @return string|void The legacy implementation will return a string, the hook implementation will return nothing.
 */
function theme_boost_union_callbackimpl_before_standard_html(&$hook = null) {
    global $CFG;

    // Require local library.
    require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

    // Initialize HTML.
    $html = '';

    // If a theme other than Boost Union or a child theme of it is active, return directly.
    // This is necessary as the callback is called regardless of the active theme.
    if (theme_boost_union_is_active_theme() != true) {
        if ($hook != null) {
            return;
        } else {
            return $html;
        }
    }

    // Add the touch icons to the page.
    $html .= theme_boost_union_get_touchicons_html_for_page();

    if ($hook != null) {
        // Add the HTML code to the hook.
        $hook->add_html($html);
    } else {
        // Return the HTML code.
        return $html;
    }
}

/**
 * Callback to add body elements on top.
 * This function is implemented here and used from two locations:
 * -> function theme_boost_union_before_standard_top_of_body_html in lib.php (for releases up to Moodle 4.3)
 * -> class theme_boost_union\local\hook\output\before_standard_top_of_body_html_generation (for releases from Moodle 4.4 on).
 *
 * We use this callback
 * -> to add the accessibility form link
 *
 * @param \core\hook\output\before_standard_top_of_body_html_generation $hook If the hook is passed, the hook implementation will
 *                                                                      be used. If not, the legacy implementation will
 *                                                                      be used.
 * @return string|void The legacy implementation will return a string, the hook implementation will return nothing.
 */
function theme_boost_union_callbackimpl_before_standard_top_of_body_html(&$hook = null) {
    global $CFG, $PAGE;

    // Require local library.
    require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

    // Initialize HTML.
    $html = '';

    // If a theme other than Boost Union or a child theme of it is active, return directly.
    // This is necessary as the callback is called regardless of the active theme.
    if (theme_boost_union_is_active_theme() != true) {
        if ($hook != null) {
            return;
        } else {
            return $html;
        }
    }

    // Require local library.
    require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

    // Add the accessibility support skip link to the page.
    $html .= theme_boost_union_get_accessibility_support_skip_link();

    if ($hook != null) {
        // Add the HTML code to the hook.
        $hook->add_html($html);
    } else {
        // Return the HTML code.
        return $html;
    }
}

/**
 * Gets and returns the external SCSS based on the theme configuration.
 *
 * @param string $type The type of SCSS which is requested (pre or post).
 * @return string
 */
function theme_boost_union_get_external_scss($type) {
    global $CFG;

    // Require file library.
    require_once($CFG->libdir . '/filelib.php');

    // If an invalid type was requested, return directly.
    if ($type != 'pre' && $type != 'post') {
        return '';
    }

    // Get the SCSS source.
    $scsssource = get_config('theme_boost_union', 'extscsssource');

    // If fetching external SCSS is disabled, return directly.
    if ($scsssource == THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_NONE) {
        return '';
    }

    // If the admin wanted to use download URLs.
    if ($scsssource == THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_DOWNLOAD) {
        // Get the URL config.
        switch ($type) {
            case 'post':
                $url = get_config('theme_boost_union', 'extscssurlpost');
                break;
            case 'pre':
                $url = get_config('theme_boost_union', 'extscssurlpre');
                break;
        }

        // If the URL is empty, return directly.
        if (empty($url)) {
            return '';
        }

        // If the URL is invalid, return directly.
        // This should not happen as the URL has already validated when the setting was stored,
        // but better be safe than sorry.
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return '';
        }

        // Otherwise, if the admin wanted to use a private Github repository.
    } else if ($scsssource == THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_GITHUB) {
        // Get the file path.
        switch ($type) {
            case 'post':
                $ghfilepath = get_config('theme_boost_union', 'extscssgithubpostfilepath');
                break;
            case 'pre':
                $ghfilepath = get_config('theme_boost_union', 'extscssgithubprefilepath');
                break;
        }

        // Compose the request URL for the Github API.
        $ghuser = get_config('theme_boost_union', 'extscssgithubuser');
        $ghrepo = get_config('theme_boost_union', 'extscssgithubrepo');
        $ghurl = 'https://api.github.com/repos/'.$ghuser.'/'.$ghrepo.'/contents/'.$ghfilepath;

        // Get the download URL from the Github API.
        $curl2 = new curl();
        $curl2header = [
            'Accept: application/vnd.github+json',
            'Authorization: Bearer '.get_config('theme_boost_union', 'extscssgithubtoken'),
            'X-GitHub-Api-Version: 2022-11-28',
        ];
        $curl2->setHeader($curl2header);
        $curl2ret = $curl2->get($ghurl);

        // If cURL had an error, return directly (as we cannot do anything about it).
        $curl2errno = $curl2->get_errno();
        if (!empty($curl2errno)) {
            return '';
        }

        // If cURL did get anything different than HTTP 200, return directly
        // (as we have to assume that something is broken).
        $curl2info = $curl2->get_info();
        if ($curl2info['http_code'] != 200) {
            return '';
        }

        // Decode the JSON from the Github API JSON data.
        $curl2data = json_decode($curl2ret);

        // If the JSON data does not contain a download URL, return directly.
        if (is_object($curl2data) !== true || property_exists($curl2data, 'download_url') !== true) {
            return '';
        }

        // Extract the download URL from the JSON data.
        $url = $curl2data->download_url;

        // If the URL is empty, return directly.
        if (empty($url)) {
            return '';
        }

        // If the URL is invalid, return directly.
        // This should not happen as the URL came directly from Github,
        // but better be safe than sorry.
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return '';
        }
    }

    // Initialize cURL.
    $curl = new curl();

    // If the URL is blocked, return directly.
    // Again, this should not happen as the URL has already checked when the setting was stored,
    // but the setting may have changed in the meantime.
    if ($curl->get_security()->url_is_blocked($url)) {
        return '';
    }
    // Get the external SCSS.
    $extscss = $curl->get($url);

    // If cURL had an error, return directly (as we cannot do anything about it).
    $curlerrno = $curl->get_errno();
    if (!empty($curlerrno)) {
        return '';
    }

    // If cURL did get anything different than HTTP 200, return directly
    // (as we have to assume that something is broken).
    $curlinfo = $curl->get_info();
    if ($curlinfo['http_code'] != 200) {
        return '';
    }

    // If external SCSS validation is enabled.
    if (get_config('theme_boost_union', 'extscssvalidation') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // If the fetched SCSS code cannot be compiled, return directly
        // (as we must not include broken SCSS code).
        $compiler = new core_scss();
        try {
            $compiler->compile($extscss);
        } catch (Exception $e) {
            return '';
        }
    }

    // Now return the (hopefully valid and working) SCSS code.
    return $extscss;
}

/**
 * Build the link to the accessibility support page visible for screen readers.
 *
 * @return string
 * @throws coding_exception
 * @throws dml_exception
 */
function theme_boost_union_get_accessibility_support_skip_link() {
    $output = '';

    // If the accessibility support is enabled.
    $enableaccessibilitysupportsetting = get_config('theme_boost_union', 'enableaccessibilitysupport');
    if (isset($enableaccessibilitysupportsetting) && $enableaccessibilitysupportsetting == THEME_BOOST_UNION_SETTING_SELECT_YES) {

        // If user login is either not required or if the user is logged in.
        $allowaccessibilitysupportwithoutloginsetting = get_config('theme_boost_union', 'allowaccessibilitysupportwithoutlogin');
        if (!(isset($allowaccessibilitysupportwithoutloginsetting) &&
                $allowaccessibilitysupportwithoutloginsetting != THEME_BOOST_UNION_SETTING_SELECT_YES) ||
                (isloggedin() && !isguestuser())) {

            // Add link for screen readers to accessibility support page.
            $supporturl = new \core\url('/theme/boost_union/accessibility/support.php');
            $supporttitle = theme_boost_union_get_accessibility_srlinktitle();
            $output .= \core\output\html_writer::link($supporturl, $supporttitle, [
                'id' => 'access-support-form-sr-link',
                'class' => 'sr-only sr-only-focusable',
            ]);
        }
    }

    return $output;
}

/**
 * Helper function which wxtracts and returns the pluginname for the given callback name.
 * This function simply differentiates between real plugins and core components.
 * The result is especially used in the footersuppressstandardfooter_* feature.
 *
 * @param stdClass $callback The callback.
 * @return string
 */
function theme_boost_union_get_pluginname_from_callbackname($callback) {
    // If the component is 'core', things are somehow different.
    if ($callback['component'] == 'core') {
        $hookexplode = explode('::', $callback['callback']);
        $pluginname = array_shift($hookexplode);
    } else {
        $pluginname = $callback['component'];
    }

    return $pluginname;
}

/**
 * Helper function which is called from the before_session_start() callback which manipulates Moodle core's hooks.
 */
function theme_boost_union_manipulate_hooks() {
    global $CFG;

    // If this is called by a CLI script.
    if (CLI_SCRIPT) {
        // Return directly.
        return;
    }

    // If $CFG->hooks_callback_overrides is not set yet.
    if (!isset($CFG->hooks_callback_overrides)) {
        // Initialize it as empty array.
        $CFG->hooks_callback_overrides = [];
    }

    // Note: You might think that this function does not need to be processed during AJAX requests as well.
    // But in this case, due to the way how Moodle's setup works, AJAX requests would "rollback" the hook manipulations
    // and Boost Union would have to compose the manipulated hooks again on the next "real" page load.
    // This would result in longer page load times for real end users.

    // Get Moodle core's hookcallbacks cache.
    $corecache = \cache::make('core', 'hookcallbacks');

    // Get Boost Union's hookoverrides cache.
    $bucache = \cache::make('theme_boost_union', 'hookoverrides');

    // Get the latest overrides from cache.
    $overridesfromcache = $bucache->get('overrides');

    // If a value for the latest overrides was found in the cache.
    if ($overridesfromcache !== false) {
        // Set it as the new $CFG->hooks_callback_overrides.
        $CFG->hooks_callback_overrides = $overridesfromcache;

        // Otherwise.
    } else {
        // Use a temporary marker in the hookoverrides cache as mutex (to avoid that this code is run in parallel and
        // race conditions appear).
        // This is a quite lightweight approach compared to a lock and especially helpful as the hookoverrides cache
        // is a local cache store which means that this code should be run on each node.
        $alreadystarted = $bucache->get('manipulationstarted');

        // If the manipulation has already been started, return directly.
        // In this case, the hooks will not be manipulated, but we can't do anything about it.
        if ($alreadystarted == true) {
            return;
        }

        // Set the mutex marker.
        $bucache->set('manipulationstarted', true);

        // Require the own library.
        require_once($CFG->dirroot.'/theme/boost_union/lib.php');

        // Get the array of plugins with the before_standard_footer_html_generation hook which can be suppressed by Boost Union.
        //
        // Ideally, this would be done with:
        // $pluginswithhook =
        // di::get(hook_manager::class)->get_callbacks_for_hook('core\\hook\\output\\before_standard_footer_html_generation');
        // like it's done in settings.php, but it's not that easy.
        // If we use get_callbacks_for_hook() to get the list of plugins, the hook manager will be instantiated,
        // will create the list of callbacks and will be kept as static object for the rest of the script lifetime.
        // We won't have a possibility to modify the list of callbacks with $CFG->hooks_callback_overrides after that point.
        //
        // Thus, we adopt the code from init_standard_callbacks(), load_callbacks() and add_component_callbacks()
        // to here to search for existing hooks ourselves.
        // In addition to that, it is important to know that this hook list is cached. We thus set a marker in
        // the hookoverrides cache to store the fact that we have manipulated the hooks and do not need to do that
        // again until the cache is cleared. On the other hand, if we already have manipulated the hooks, we have to
        // "convince" Moodle to use it (see later).

        // Get list of all files with callbacks, one per component.
        $components = ['core' => "{$CFG->dirroot}/lib/db/hooks.php"];
        $plugintypes = \core\component::get_plugin_types();
        foreach ($plugintypes as $plugintype => $plugintypedir) {
            $plugins = \core\component::get_plugin_list($plugintype);
            foreach ($plugins as $pluginname => $plugindir) {
                if (!$plugindir) {
                    continue;
                }
                $components["{$plugintype}_{$pluginname}"] = "{$plugindir}/db/hooks.php";
            }
        }

        // Iterate over the hooks files and collect all hooks.
        // Doing this, we do not do the same cleanup and check operations as the hook manager does.
        // If there would be a problem with a particular hook file, the hook manager itself would stumble upon it anyway.
        $callbacks = [];
        $parsecallbacks = function ($hookfile) {
            $callbacks = [];
            include($hookfile);
            return $callbacks;
        };
        foreach ($components as $component => $hookfile) {
            if (!file_exists($hookfile)) {
                continue;
            }
            $newcallbacks = $parsecallbacks($hookfile);
            if (!is_array($newcallbacks) || !$newcallbacks) {
                continue;
            }
            foreach ($newcallbacks as &$ncb) {
                $ncb['component'] = $component;
            }
            $callbacks = array_merge($callbacks, $newcallbacks);
        }

        // Pick the callbacks which implement the core\hook\output\before_standard_footer_html_generation hook.
        $bsfhgcallbacks = [];
        foreach ($callbacks as $callback) {
            if ($callback['hook'] == 'core\\hook\\output\\before_standard_footer_html_generation') {
                // If the callback is a string.
                if (is_string($callback['callback'])) {
                    // Use it directly.
                    $bsfhgcallbacks[] = ['callback' => $callback['callback'], 'component' => $callback['component']];

                    // Otherwise, if the callback is an array with two elements.
                } else if (is_array($callback['callback']) && count($callback['callback']) == 2) {
                    // Normalize and use it.
                    $bsfhgcallbacks[] = ['callback' => implode('::', $callback['callback']), 'component' => $callback['component']];
                }

                // In all other cases, ignore the callback as it does not match our expectations.
            }
        }

        // Iterate over all found callbacks.
        foreach ($bsfhgcallbacks as $callback) {
            // Extract the pluginname.
            $pluginname = theme_boost_union_get_pluginname_from_callbackname($callback);
            // If the given plugin's output is suppressed by Boost Union's settings.
            $suppresssetting = get_config('theme_boost_union', 'footersuppressstandardfooter_'.$pluginname);
            if (isset($suppresssetting) && $suppresssetting == THEME_BOOST_UNION_SETTING_SELECT_YES) {
                // Set the plugin's hook as disabled.
                // phpcs:disable moodle.Files.LineLength.TooLong
                $CFG->hooks_callback_overrides['core\\hook\\output\\before_standard_footer_html_generation'][$callback['callback']] =
                        ['disabled' => true];
                // phpcs:enable
            }
        }

        // Remember the hook overrides in the cache.
        $bucache->set('overrides', $CFG->hooks_callback_overrides);

        // Remove the mutex marker.
        $bucache->delete('manipulationstarted');
    }

    // Now, as this function is called via before_session_start(), we can (and have to) assume that the hook_manager
    // has not been instantiated yet on this page load.
    // But it will be instantiated soon at the end of /lib/setup.php and our modifications which we set in
    // $CFG->hooks_callback_overrides will be taken into account then.
}

/**
 * Helper function which is called from settings.php as callback.
 * It simply removes the cached hook overrides for the Boost Union hook manipulations so that they are
 * processed again on the next page load.
 */
function theme_boost_union_remove_hookmanipulation() {
    // Get the cache.
    $cache = \cache::make('theme_boost_union', 'hookoverrides');

    // Remove the hook overrides.
    $cache->delete('overrides');
}

/**
 * Helper function to check if Boost Union or a child theme of Boost Union is active.
 * This is needed at multiple locations to avoid that callbacks in Boost Union affect other active themes.
 *
 * @return bool
 */
function theme_boost_union_is_active_theme() {
    global $PAGE;

    if ($PAGE->theme->name == 'boost_union' || in_array('boost_union', $PAGE->theme->parents)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Helper function to check if a child theme of Boost Union (and _not_ Boost Union itself) is active.
 * This is needed at multiple locations to improve child theme support in Boost Union already.
 *
 * @return bool
 */
function theme_boost_union_is_active_childtheme() {
    global $PAGE;

    if ($PAGE->theme->name != 'boost_union') {
        return true;
    } else {
        return false;
    }
}
