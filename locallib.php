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
 * Build the link to a static page.
 *
 * @param string $page The static page's identifier.
 * @return string.
 */
function theme_boost_union_get_staticpage_link($page) {
    // Compose the URL object.
    $url = new moodle_url('/theme/boost_union/pages/'.$page.'.php');

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
 * Helper function to compare either infobanner or tiles orders.
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
        return moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
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
            if (strcmp($filename, trim($settings[0])) == 0) {
                // Trim the second parameter as we need it more than once.
                $settings[2] = trim($settings[2]);

                // If the color value is not acceptable, replace it with dark.
                if ($settings[2] != 'dark' && $settings[2] != 'light') {
                    $settings[2] = 'dark';
                }

                // Return the text + text color that belongs to the randomly selected image.
                return array(format_string(trim($settings[1])), $settings[2]);
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
        $filesforcontext = array();
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
            $urlpersistent = new moodle_url('/pluginfile.php/1/theme_boost_union/customfonts/0/'.$filename);
            $filesforcontext[] = array('filename' => $filename,
                    'fileurlpersistent' => $urlpersistent->out());
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
    return array('.eot', '.otf', '.svg', '.ttf', '.woff', '.woff2');
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

    // If customfiletypes are set in config.php, we can't do anything.
    if (array_key_exists('customfiletypes', $CFG->config_php_settings)) {
        return false;
    }

    // Our array of webfont file types to register.
    // As we want to keep things simple, we do not set a particular icon for these file types.
    // Likewise, we do not set any type groups or use descriptions from the language pack.
    $webfonts = array(
            'eot' => array(
                    'extension' => 'eot',
                    'mimetype' => 'application/vnd.ms-fontobject',
                    'coreicon' => 'unknown'
            ),
            'otf' => array(
                    'extension' => 'otf',
                    'mimetype' => 'font/otf',
                    'coreicon' => 'unknown'
            ),
            'svg' => array(
                    'extension' => 'svg',
                    'mimetype' => 'image/svg+xml',
                    'coreicon' => 'unknown'
            ),
            'ttf' => array(
                    'extension' => 'ttf',
                    'mimetype' => 'font/ttf',
                    'coreicon' => 'unknown'
            ),
            'woff' => array(
                    'extension' => 'woff',
                    'mimetype' => 'font/woff',
                    'coreicon' => 'unknown'
            ),
            'woff2' => array(
                    'extension' => 'woff2',
                    'mimetype' => 'font/woff2',
                    'coreicon' => 'unknown'
            ),
    );

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
    $mailtemplatecontext = array('body' => get_string('emailbrandinghtmldemobody', 'theme_boost_union'));
    $mail = $OUTPUT->render_from_template('core/email_html', $mailtemplatecontext);

    // And compose mail preview.
    $previewtemplatecontext = array('mail' => $mail);
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
    $mailtemplatecontext = array('body' => get_string('emailbrandingtextdemobody', 'theme_boost_union'));
    $mail = nl2br($OUTPUT->render_from_template('core/email_text', $mailtemplatecontext));
    $mail = '<div class="text-monospace">'.$mail.'</div>';

    // And compose mail preview.
    $previewtemplatecontext = array('mail' => $mail);
    $preview = $OUTPUT->render_from_template('theme_boost_union/emailpreview', $previewtemplatecontext);

    return $preview;
}

/**
 * Callback function which is called from settings.php if the FontAwesome files setting has changed.
 *
 * It gets all files from the files setting, picks all the expected files (and ignores all others)
 * and stores them into an application cache for quicker access.
 *
 * @return void
 */
function theme_boost_union_fontawesome_checkin() {
    // Create cache for FontAwesome files.
    $cache = cache::make('theme_boost_union', 'fontawesome');

    // Purge the existing cache values as we will refill the cache now.
    $cache->purge();

    // Get FontAwesome version config.
    $faconfig = get_config('theme_boost_union', 'fontawesomeversion');

    // If a FontAwesome version is enabled.
    if ($faconfig != THEME_BOOST_UNION_SETTING_FAVERSION_NONE && $faconfig != null) {

        // Get the system context.
        $systemcontext = \context_system::instance();

        // Get filearea.
        $fs = get_file_storage();

        // Get FontAwesome file structure.
        $filestructure = theme_boost_union_get_fontawesome_filestructure($faconfig);

        // If a valid file structure could be retrieved.
        if ($filestructure != null) {

            // Iterate over the folder structure.
            foreach ($filestructure as $folder => $files) {

                // Initialize a folder list.
                $folderlist = array();

                // Iterate over the files in the folder.
                foreach ($files as $file => $expected) {

                    // Try to get the file from the filearea.
                    $fsfile = $fs->get_file($systemcontext->id, 'theme_boost_union', 'fontawesome', 0, '/'.$folder.'/', $file);

                    // If the file exists.
                    if ($fsfile != false) {
                        // Add the file to the folder list.
                        $folderlist[] = $file;
                    }
                }

                // Add the folder to the cache.
                $cache->set($folder, $folderlist);
            }
        }
    }

    // Add a marker value to the cache which indicates that the files have been checked into the cache completely.
    // This will help to decide later if the cache is really empty (and should be refilled) or if there aren't just any
    // files uploaded.
    $cache->set('checkedin', true);
}

/**
 * Helper function which returns an array of accepted fontawesome file extensions (including the dots).
 *
 * @return array
 */
function theme_boost_union_get_fontawesome_extensions() {
    return array('.css', '.eot', '.svg', '.ttf', '.woff', '.woff2');
}

/**
 * Helper function which returns the files which are expected to be provided for a given FontAwesome version.
 *
 * @param string $version The FontAwesome version, given as THEME_BOOST_UNION_SETTING_FAVERSION_* constant.
 *
 * @return array|null The array of files or null if an invalid FontAwesome version was provided.
 */
function theme_boost_union_get_fontawesome_filestructure($version) {
    // Pick the files for the selected FA version.
    switch ($version) {
        case THEME_BOOST_UNION_SETTING_FAVERSION_FA6FREE:
            $files = array('css' => array('fontawesome.min.css' => THEME_BOOST_UNION_SETTING_FAFILES_MANDATORY,
                            'solid.min.css' => THEME_BOOST_UNION_SETTING_FAFILES_MANDATORY,
                            'regular.min.css' => THEME_BOOST_UNION_SETTING_FAFILES_OPTIONAL,
                            'brands.min.css' => THEME_BOOST_UNION_SETTING_FAFILES_OPTIONAL,
                            'v4-font-face.min.css' => THEME_BOOST_UNION_SETTING_FAFILES_MANDATORY),
                    'webfonts' => array('fa-solid-900.woff2' => THEME_BOOST_UNION_SETTING_FAFILES_MANDATORY,
                            'fa-solid-900.ttf' => THEME_BOOST_UNION_SETTING_FAFILES_MANDATORY,
                            'fa-regular-400.woff2' => THEME_BOOST_UNION_SETTING_FAFILES_OPTIONAL,
                            'fa-regular-400.ttf' => THEME_BOOST_UNION_SETTING_FAFILES_OPTIONAL,
                            'fa-brands-400.woff2' => THEME_BOOST_UNION_SETTING_FAFILES_OPTIONAL,
                            'fa-brands-400.ttf' => THEME_BOOST_UNION_SETTING_FAFILES_OPTIONAL,
                            'fa-v4compatibility.woff2' => THEME_BOOST_UNION_SETTING_FAFILES_MANDATORY,
                            'fa-v4compatibility.ttf' => THEME_BOOST_UNION_SETTING_FAFILES_MANDATORY));
            break;
        default:
            // This only happens if an invalid version was provided.
            $files = null;
    }

    // Return the file structure.
    return $files;
}

/**
 * Helper function which return the files from the fontawesome file area as templatecontext structure.
 * It was designed to compose the files for the settings-fontawesome-filelist.mustache template.
 * This function uses the fontawesome cache definition, i.e. it does not load the files from the filearea directly.
 * This means it uses the same data source as the theme_boost_union_add_fontawesome_to_page() function which adds
 * the fontawesome files to the page.
 *
 * @return array|null
 * @throws coding_exception
 * @throws dml_exception
 */
function theme_boost_union_get_fontawesome_templatecontext() {
    // Create cache for FontAwesome files.
    $cache = cache::make('theme_boost_union', 'fontawesome');

    // If the cache is completely empty, check the files in on-the-fly.
    if ($cache->get('checkedin') != true) {
        theme_boost_union_fontawesome_checkin();
    }

    // Get FontAwesome version config.
    $faconfig = get_config('theme_boost_union', 'fontawesomeversion');

    // If a FontAwesome version is enabled.
    if ($faconfig != THEME_BOOST_UNION_SETTING_FAVERSION_NONE && $faconfig != null) {

        // Initialize context variable.
        $filesforcontext = array();

        // Get FontAwesome file structure.
        $filestructure = theme_boost_union_get_fontawesome_filestructure($faconfig);

        // If a valid file structure could be retrieved.
        if ($filestructure != null) {

            // Iterate over the folder structure.
            foreach ($filestructure as $folder => $files) {

                // Get the cached data for this folder.
                $cachedfolder = $cache->get($folder);

                // Iterate over the files in the folder structure.
                foreach ($files as $file => $expected) {

                    // Deduce the mandatory value.
                    if ($expected == THEME_BOOST_UNION_SETTING_FAFILES_MANDATORY) {
                        $mandatory = true;
                    } else {
                        $mandatory = false;
                    }

                    // Compose the file path.
                    $filepath = $folder . '/' . $file;

                    // Get the description of the file.
                    $fileidentifier = str_replace('/', '-', $filepath);
                    $description = get_string('fontawesomelistfileinfo-' . $faconfig . '-' . $fileidentifier, 'theme_boost_union');

                    // If the folder was not uploaded at all or if the folder is empty, we do not need to check if the file exists.
                    // We can add the file as non-existent right away.
                    if ($cachedfolder == null || ($cachedfolder == array()) && count($cachedfolder) < 1) {
                        $exists = false;

                        // Otherwise, we have to check the file it was uploaded.
                    } else {
                        $exists = in_array($file, $cachedfolder);
                    }

                    // Add the file to the template structure.
                    $filesforcontext[] = array('filepath' => $filepath, 'exists' => $exists, 'mandatory' => $mandatory,
                            'description' => $description);
                }
            }
        }
    }

    return $filesforcontext;
}

/**
 * Helper function which returns the visual checks for the configured FontAwesome version.
 *
 * @return array|null The array of checks or null if an invalid FontAwesome version is configured.
 */
function theme_boost_union_get_fontawesome_checks_templatecontext() {
    global $CFG;

    // Get FontAwesome version config.
    $version = get_config('theme_boost_union', 'fontawesomeversion');

    // Pick the checks for the selected FA version.
    switch ($version) {
        case THEME_BOOST_UNION_SETTING_FAVERSION_FA6FREE:
            $checks = array(
                    array('icon' => '<i class="fa fa-check-circle-o fa-3x fa-fw"></i>',
                            'title' => get_string('fontawesomecheck-fa6free-general-title', 'theme_boost_union'),
                            'description' => get_string('fontawesomecheck-fa6free-general-description', 'theme_boost_union')),
                    array('icon' => '<i class="fa fa-map-o fa-3x fa-fw"></i>',
                            'title' => get_string('fontawesomecheck-fa6free-fallback-title', 'theme_boost_union'),
                            'description' => get_string('fontawesomecheck-fa6free-fallback-description', 'theme_boost_union')),
                    array('icon' => '<i class="fa-solid fa-virus-covid fa-3x fa-fw"></i>',
                            'title' => get_string('fontawesomecheck-fa6free-newstuff-title', 'theme_boost_union'),
                            'description' => get_string('fontawesomecheck-fa6free-newstuff-description', 'theme_boost_union')),
            );
            break;
        default:
            // This only happens if an invalid version was provided.
            $checks = null;
    }

    // If the filter_fontawesome plugin is installed, add a check for filtering the icons.
    if (file_exists($CFG->dirroot.'/filter/fontawesome/version.php')) {
        $checks[] = array('icon' => format_text('[fa-solid fa-users-line fa-3x fa-fw]'),
                'title' => get_string('fontawesomecheck-fa6free-filter-title', 'theme_boost_union'),
                'description' => get_string('fontawesomecheck-fa6free-filter-description', 'theme_boost_union'));
    }

    // Return the checks structure.
    return $checks;
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
 * Helper function which adds the CSS files from the fontawesome file area to the Moodle page.
 * This function uses the fontawesome cache definition, i.e. it does not load the files from the filearea directly.
 * It's meant to be called by theme_boost_union_before_standard_html_head() only.
 * *
 * @throws coding_exception
 * @throws dml_exception
 * @throws moodle_exception
 */
function theme_boost_union_add_fontawesome_to_page() {
    global $PAGE;

    // Create cache for FontAwesome files.
    $cache = cache::make('theme_boost_union', 'fontawesome');

    // If the cache is completely empty, check the files in on-the-fly.
    if ($cache->get('checkedin') != true) {
        theme_boost_union_fontawesome_checkin();
    }

    // Get FontAwesome version config.
    $faconfig = get_config('theme_boost_union', 'fontawesomeversion');

    // If a FontAwesome version is enabled.
    if ($faconfig != THEME_BOOST_UNION_SETTING_FAVERSION_NONE && $faconfig != null) {

        // Get the cached data for the CSS folder (we do not need to add files from any other folders in the cache).
        $cachedfolder = $cache->get('css');

        // Iterate over the files in the cached folder structure.
        foreach ($cachedfolder as $cachedfile) {

            // Build the FontAwesome CSS file URL.
            $facssurl = new moodle_url('/pluginfile.php/1/theme_boost_union/fontawesome/' .
                    theme_get_revision().'/css/'.$cachedfile);

            // Add the CSS file to the page.
            $PAGE->requires->css($facssurl);
        }
    }
}

/**
 * Helper function which adds the CSS file from the flavour to the Moodle page.
 * It's meant to be called by theme_boost_union_before_standard_html_head() only.
 * *
 * @throws coding_exception
 * @throws dml_exception
 * @throws moodle_exception
 */
function theme_boost_union_add_flavourcss_to_page() {
    global $CFG, $PAGE;

    // Require flavours library.
    require_once($CFG->dirroot . '/theme/boost_union/flavours/flavourslib.php');

    // If any flavour applies to this page.
    $flavour = theme_boost_union_get_flavour_which_applies();
    if ($flavour != null) {
        // Build the flavour CSS file URL.
        $flavourcssurl = new moodle_url('/theme/boost_union/flavours/styles.php',
                array('id' => $flavour->id, 'rev' => theme_get_revision()));

        // Add the CSS file to the page.
        $PAGE->requires->css($flavourcssurl);
    }
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
        return moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
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
        $mobilescssurl = new moodle_url('/theme/boost_union/mobile/styles.php', array('rev' => time()));

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
            'header' => 'header'
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
