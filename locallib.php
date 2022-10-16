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
 * @copyright  2022 Moodle an Hochschulen e.V. <kontakt@moodle-an-hochschulen.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Build the course related hints HTML code.
 * This function evaluates and composes all course related hints which may appear on a course page below the course header.
 *
 * @copyright  2022 Moodle an Hochschulen e.V. <kontakt@moodle-an-hochschulen.de>
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

    // If the setting showhintcoursehidden is set and the visibility of the course is hidden and
    // a hint for the visibility will be shown.
    if (get_config('theme_boost_union', 'showhintcoursehidden') == THEME_BOOST_UNION_SETTING_SELECT_YES
            && has_capability('theme/boost_union:viewhintinhiddencourse', \context_course::instance($COURSE->id))
            && $PAGE->has_set_url()
            && $PAGE->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)
            && $COURSE->visible == false) {

        // Prepare template context.
        $templatecontext = array('courseid' => $COURSE->id);

        // If the user has the capability to change the course settings, an additional link to the course settings is shown.
        if (has_capability('moodle/course:update', context_course::instance($COURSE->id))) {
            $templatecontext['showcoursesettingslink'] = true;
        } else {
            $templatecontext['showcoursesettingslink'] = false;
        }

        // Render template and add it to HTML code.
        $html .= $OUTPUT->render_from_template('theme_boost_union/course-hint-hidden', $templatecontext);
    }

    // If the setting showhintcourseguestaccess is set and the user is accessing the course with guest access,
    // a hint for users is shown.
    // We also check that the user did not switch the role. This is a special case for roles that can fully access the course
    // without being enrolled. A role switch would show the guest access hint additionally in that case and this is not
    // intended.
    if (get_config('theme_boost_union', 'showhintcourseguestaccess') == THEME_BOOST_UNION_SETTING_SELECT_YES
            && is_guest(\context_course::instance($COURSE->id), $USER->id)
            && $PAGE->has_set_url()
            && $PAGE->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)
            && !is_role_switched($COURSE->id)) {

        // Require self enrolment library.
        require_once($CFG->dirroot . '/enrol/self/lib.php');

        // Prepare template context.
        $templatecontext = array('courseid' => $COURSE->id,
                'role' => role_get_name(get_guest_role()));

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

            // Prepare template context.
            $templatecontext = array();

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
                    $url = new moodle_url('/enrol/editinstance.php', array('courseid' => $COURSE->id,
                            'id' => $selfenrolinstanceid, 'type' => 'self'));
                    $selfenrolinstanceobject->name = html_writer::link($url, $selfenrolinstanceobject->name);
                }

                // Add the enrolment instance information to the template context depending on the instance configuration.
                if ($selfenrolinstanceobject->unrestrictedness == 'unlimited') {
                    $templatecontext['selfenrolinstances'][] = get_string('showhintcourseselfenrolunlimited', 'theme_boost_union',
                            array('name' => $selfenrolinstanceobject->name));
                } else if ($selfenrolinstanceobject->unrestrictedness == 'until') {
                    $templatecontext['selfenrolinstances'][] = get_string('showhintcourseselfenroluntil', 'theme_boost_union',
                            array('name' => $selfenrolinstanceobject->name,
                                    'until' => userdate($selfenrolinstanceobject->enddate)));
                } else if ($selfenrolinstanceobject->unrestrictedness == 'from') {
                    $templatecontext['selfenrolinstances'][] = get_string('showhintcourseselfenrolfrom', 'theme_boost_union',
                            array('name' => $selfenrolinstanceobject->name,
                                    'from' => userdate($selfenrolinstanceobject->startdate)));
                } else if ($selfenrolinstanceobject->unrestrictedness == 'since') {
                    $templatecontext['selfenrolinstances'][] = get_string('showhintcourseselfenrolsince', 'theme_boost_union',
                            array('name' => $selfenrolinstanceobject->name,
                                    'since' => userdate($selfenrolinstanceobject->startdate)));
                } else if ($selfenrolinstanceobject->unrestrictedness == 'fromuntil') {
                    $templatecontext['selfenrolinstances'][] = get_string('showhintcourseselfenrolfromuntil', 'theme_boost_union',
                            array('name' => $selfenrolinstanceobject->name,
                                    'until' => userdate($selfenrolinstanceobject->enddate),
                                    'from' => userdate($selfenrolinstanceobject->startdate)));
                } else if ($selfenrolinstanceobject->unrestrictedness == 'sinceuntil') {
                    $templatecontext['selfenrolinstances'][] = get_string('showhintcourseselfenrolsinceuntil', 'theme_boost_union',
                            array('name' => $selfenrolinstanceobject->name,
                                    'until' => userdate($selfenrolinstanceobject->enddate),
                                    'since' => userdate($selfenrolinstanceobject->startdate)));
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
        $url = new moodle_url('/course/switchrole.php',
                array('id' => $COURSE->id,
                        'sesskey' => sesskey(),
                        'switchrole' => 0,
                        'returnurl' => $PAGE->url->out_as_local_url(false)));

        // Prepare template context.
        $templatecontext = array('role' => $role,
                'url' => $url->out());

        // Render template and add it to HTML code.
        $html .= $OUTPUT->render_from_template('theme_boost_union/course-hint-switchedrole', $templatecontext);
    }

    // Return HTML code.
    return $html;
}

/**
 * Build the link to the imprint page.
 *
 * @return string.
 */
function theme_boost_union_get_imprint_link() {
    // Compose the URL object.
    $url = new moodle_url('/theme/boost_union/pages/imprint.php');

    // Return the string representation of the URL.
    return $url->out();
}

/**
 * Build the page title of the imprint page.
 *
 * @return string.
 */
function theme_boost_union_get_imprint_pagetitle() {
    // Get the configured page title.
    $imprintpagetitleconfig = get_config('theme_boost_union', 'imprintpagetitle');

    // If there is a string configured.
    if ($imprintpagetitleconfig) {
        // Return this setting.
        return $imprintpagetitleconfig;

        // Otherwise.
    } else {
        // Return the default string.
        return get_string('imprintpagetitledefault', 'theme_boost_union');
    }
}

/**
 * Helper function to check if a given info banner should be shown on this page.
 * This function checks
 * a) if the banner is enabled at all
 * b) if the banner has any content (i.e. is not empty)
 * b) if the banner is configured to be shown on the given page
 * c) if the banner is configured to be shown now (in case it is a time-based banner)
 *
 * @copyright  2022 Moodle an Hochschulen e.V. <kontakt@moodle-an-hochschulen.de>
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
 * Helper function to compare two infobanner orders.
 *
 * @param int $a The first value
 * @param int $b The second value
 *
 * @return boolean.
 */
function theme_boost_union_infobanner_compare_order($a, $b) {
    if ($a->order == $b->order) {
        // Basically, we should return 0 in this case.
        // But due to the way how usort works internally, info banners with the same order would end up in the result array
        // in reversed order (compared to the numbering order on the theme settings page).
        // Thus, we do a little trick and tell the sorting algorithm that the first item is greater than the second one
        // by returning a positive number.
        return 1;
    }
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
    $codingexception[] = array();

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
        $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
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
            if (strcmp($filename, $settings[0]) == 0) {
                // If the color value is not acceptable, replace it with dark.
                if ($settings[2] != 'dark' && $settings[2] != 'light') {
                    $settings[2] = 'dark';
                }

                // Return the text + text color that belongs to the randomly selected image.
                return array(format_string($settings[1]), $settings[2]);
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
        $filesforcontext = array();
        foreach ($files as $af) {
            $urlpersistent = new moodle_url('/pluginfile.php/1/theme_boost_union/additionalresources/0/'.$af->get_filename());
            $urlrevisioned = new moodle_url('/pluginfile.php/1/theme_boost_union/additionalresources/'.theme_get_revision().
                    '/'.$af->get_filename());
            $filesforcontext[] = array('filename' => $af->get_filename(),
                                        'filetype' => $af->get_mimetype(),
                                        'filesize' => display_size($af->get_filesize()),
                                        'fileicon' => $OUTPUT->image_icon(file_file_icon($af, 64), get_mimetype_description($af)),
                                        'fileurlpersistent' => $urlpersistent->out(),
                                        'fileurlrevisioned' => $urlrevisioned->out());
        }
    }

    return $filesforcontext;
}
