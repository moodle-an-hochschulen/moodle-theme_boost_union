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
        $templatecontext = ['courseid' => $COURSE->id];

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
                    $url = new moodle_url('/enrol/editinstance.php', ['courseid' => $COURSE->id,
                            'id' => $selfenrolinstanceid, 'type' => 'self', ]);
                    $selfenrolinstanceobject->name = html_writer::link($url, $selfenrolinstanceobject->name);
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
        $url = new moodle_url('/course/switchrole.php',
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
            $urlpersistent = new moodle_url('/pluginfile.php/1/theme_boost_union/additionalresources/0/'.$af->get_filename());
            $urlrevisioned = new moodle_url('/pluginfile.php/1/theme_boost_union/additionalresources/'.theme_get_revision().
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
            $urlpersistent = new moodle_url('/pluginfile.php/1/theme_boost_union/customfonts/0/'.$filename);
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
    $previewtemplatecontext = ['mail' => $mail, 'type' => 'html', 'monospace' => false];
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
    $previewtemplatecontext = ['mail' => $mail, 'type' => 'text', 'monospace' => true];
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
                ['id' => $flavour->id, 'rev' => theme_get_revision()]);

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
        $mobilescssurl = new moodle_url('/theme/boost_union/mobile/styles.php', ['rev' => time()]);

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
 * @param theme_config $theme The theme config object.
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
        // If the activity purpose setting is set and differs from the activity's default purpose.
        $configname = 'activitypurpose'.$modname;
        if (isset($theme->settings->{$configname}) && $theme->settings->{$configname} != $defaultpurpose) {
            // Add CSS to modify the activity purpose color in the activity chooser and the activity icon.
            $scss .= '.activity.modtype_'.$modname.' .activityiconcontainer.courseicon,';
            $scss .= '.modchoosercontainer .modicon_'.$modname.'.activityiconcontainer,';
            $scss .= '#page-header .modicon_'.$modname.'.activityiconcontainer,';
            $scss .= '.block_recentlyaccesseditems .theme-boost-union-'.$modname.'.activityiconcontainer,';
            $scss .= '.block_timeline .theme-boost-union-mod_'.$modname.'.activityiconcontainer {';
            // If the purpose is now different than 'other', change the background color to the new color.
            if ($theme->settings->{$configname} != MOD_PURPOSE_OTHER) {
                $scss .= 'background-color: var(--activity' . $theme->settings->{$configname} . ') !important;';

                // Otherwise, the background color is set to light grey (as there is no '--activityother' variable).
            } else {
                $scss .= 'background-color: $light !important;';
            }
            // If the default purpose originally was 'other' and now is overridden, make the icon white.
            if ($defaultpurpose == MOD_PURPOSE_OTHER) {
                $scss .= '.activityicon, .icon { filter: brightness(0) invert(1); }';
            }
            // If the default purpose was not 'other' and now it is, make the icon black.
            if ($theme->settings->{$configname} == MOD_PURPOSE_OTHER) {
                $scss .= '.activityicon, .icon { filter: none; }';
            }
            $scss .= '}';
        }
    }
    return $scss;
}

/**
 * Returns the SCSS code to add an external link icon after external links to mark them visually.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_scss_to_mark_external_links($theme) {
    global $CFG;

    // Initialize SCSS snippet.
    $scss = '';

    // If the corresponding setting is set to 'yes'.
    if ($theme->settings->markexternallinks == THEME_BOOST_UNION_SETTING_SELECT_YES) {

        // SCSS to add external link icon after the link and respect LTR and RTL while doing this.
        $scss = 'body.dir-ltr a:not([href^="' . $CFG->wwwroot . '"])[href^="http://"]::after,
            body.dir-ltr a:not([href^="' . $CFG->wwwroot . '"])[href^="https://"]::after {
            font-family: "#{$fa-style-family}";
            content: "#{$fa-var-external-link}" !important;
            font-weight: 900;
            padding-left: 0.25rem;
        }';
        $scss .= 'body.dir-rtl a:not([href^="' . $CFG->wwwroot . '"])[href^="http://"]::before,
            body.dir-rtl a:not([href^="' . $CFG->wwwroot . '"])[href^="https://"]::before {
            font-family: "#{$fa-style-family}";
            content: "#{$fa-var-external-link}" !important;
            font-weight: 900;
            padding-right: 0.25rem;
        }';

        // Moodle adds a hardcoded external-link icon to several links:
        // * The "services and support" link in the questionmark menu (which should point to moodle.com/help, but may also point to
        // the URL in the $CFG->servicespage setting).
        // * The "contact site support" link in the questionmark menu (as soon as the URL in the $CFG->supportpage setting is set).
        // * The links to the Moodle docs (which are created with the get_docs_url() helper function).
        // These icons become obsolete now. We remove them with the sledgehammer.
        $scss .= '.footer-support-link a[href^="https://moodle.com/help/"] .fa-external-link';
        if (!empty($CFG->servicespage)) {
            $scss .= ', .footer-support-link a[href="'.$CFG->servicespage.'"] .fa-external-link';
        }
        if (!empty($CFG->supportpage)) {
            $scss .= ', a[href="'.$CFG->supportpage.'"] .fa-external-link';
        }
        $scss .= ', a[href^="'.get_docs_url().'"] .fa-external-link {
            display: none;
        }';
    }
    return $scss;
}

/**
 * Adds a broken-chain symbol in front of mailto links to mark them visually.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_scss_to_mark_broken_links($theme) {
     // Initialize SCSS snippet.
    $content = '';

    // If the corresponding setting is set to 'yes'.
    if ($theme->settings->markbrokenlinks == THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Set font color to the 'danger' color.
        $content .= 'a[href*="/brokenfile.php"] {color: var(--danger);}';

        // Add broken-chain symbol in front of link.
        $content .= 'a[href*="/brokenfile.php"]::before {
            font-family: FontAwesome;
            content: "\f127" !important;
            padding-right: 5px;
        }';
    }
    return $content;
}

/**
 * Adds an envelope symbol in front of broken links and make the font red to mark them visually
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_get_scss_to_mark_mailto_links($theme) {
     // Initialize SCSS snippet.
    $scss = '';

    // If the corresponding setting is set to 'yes'.
    if ($theme->settings->markmailtolinks == THEME_BOOST_UNION_SETTING_SELECT_YES) {

        // Add envelope symbol in front of link.
        $scss .= 'a[href^="mailto"]::before {
            font-family: FontAwesome;
            content: "\f003" !important;
            padding-right: 5px;
        }';
    }

    return $scss;
}
