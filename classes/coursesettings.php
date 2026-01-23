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
 * Theme Boost Union - Course settings utility
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

/**
 * Utility class containing static functions for dealing with course-specific settings.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class coursesettings {

    /**
     * Get a particular theme setting (just as it's done with get_config()),
     * but check as well if the setting is overridden for the current course.
     *
     * @param string $name The name of the setting.
     * @return mixed The requested theme setting.
     */
    public static function get_config_with_course_override($name) {
        global $DB, $PAGE;

        // If we are not on a course page or if we are on the frontpage course.
        if ($PAGE->pagelayout != 'course' || $PAGE->course->id == SITEID) {
            // Return the theme setting as normal.
            return get_config('theme_boost_union', $name);
        }

        // If the given setting may not be overridden for courses at all (anymore).
        $settingoverride = get_config('theme_boost_union', $name.'_courseoverride');
        if ($settingoverride == false) {
            // Return the theme setting as normal.
            return get_config('theme_boost_union', $name);
        }

        // Get the course ID.
        $courseid = $PAGE->course->id;

        // Create cache key for this specific course and setting combination.
        $cachekey = $courseid.'_'.$name;

        // Try to get the result from cache first.
        $cache = \cache::make('theme_boost_union', 'courseoverrides');
        $cachedresult = $cache->get($cachekey);

        // If a result was found, return it.
        // But validate it first as some aspects have still to be considered everytime the result is returned from cache.
        if ($cachedresult !== false) {
            return self::validate_course_setting_value($name, $cachedresult);
        }

        // Cache miss - get the course-specific settings from database.
        $courseconfig = $DB->get_record('theme_boost_union_course',
            ['courseid' => $courseid, 'name' => $name], 'value', IGNORE_MISSING);

        // If the course-specific setting is explicitly set to "use global default".
        if ($courseconfig !== false && $courseconfig !== null &&
            $courseconfig->value == THEME_BOOST_UNION_SETTING_USEGLOBAL) {
            // Get the global theme setting.
            $result = get_config('theme_boost_union', $name);

            // Otherwise, if the setting is not configured to use the global default, but there is also
            // no course-specific setting for this setting (which will happen if the course settings were not saved yet).
        } else if ($courseconfig === false || $courseconfig === null) {
            // Get the global theme setting.
            $result = get_config('theme_boost_union', $name);

            // Otherwise.
        } else {
            // Use the course-specific setting.
            $result = $courseconfig->value;
        }

        // Validate the result before caching and returning it.
        $result = self::validate_course_setting_value($name, $result);

        // Store the result in cache for future requests.
        $cache->set($cachekey, $result);

        // Return the result.
        return $result;
    }

    /**
     * Get all course-specific settings for a course.
     * This function does not use the MUC by purpose and should only be used on the course settings page.
     *
     * @param int $courseid The course ID.
     * @return array Array of setting name => value pairs.
     */
    public static function get_all_course_overrides($courseid) {
        global $DB;

        $records = $DB->get_records('theme_boost_union_course', ['courseid' => $courseid], '', 'name, value');
        $settings = [];

        foreach ($records as $record) {
            $settings[$record->name] = $record->value;
        }

        return $settings;
    }

    /**
     * Set a course-specific override for a Boost Union setting.
     *
     * @param int $courseid The course ID.
     * @param string $name The name of the setting.
     * @param mixed $value The value to set. If null or empty, the setting will be deleted.
     * @return bool True on success, false on failure.
     */
    public static function set_course_setting($courseid, $name, $value) {
        global $DB;

        // Check if a record already exists for this setting.
        $existingrecord = $DB->get_record('theme_boost_union_course', ['courseid' => $courseid, 'name' => $name]);

        // If we have a value to save.
        if ($value !== null && $value !== '') {
            // If there is an existing record.
            if ($existingrecord) {
                // Update existing record.
                $existingrecord->value = $value;
                $result = $DB->update_record('theme_boost_union_course', $existingrecord);

                // Otherwise.
            } else {
                // Insert new record.
                $newrecord = new \stdClass();
                $newrecord->courseid = $courseid;
                $newrecord->name = $name;
                $newrecord->value = $value;
                $result = $DB->insert_record('theme_boost_union_course', $newrecord);
            }

            // Otherwise, no value or empty value - delete existing record if it exists.
        } else {
            if ($existingrecord) {
                $result = $DB->delete_records('theme_boost_union_course',
                    ['courseid' => $courseid, 'name' => $name]);
            } else {
                $result = true; // Nothing to delete, consider it successful.
            }
        }

        // Invalidate the cache for this specific setting.
        $cache = \cache::make('theme_boost_union', 'courseoverrides');
        $cachekey = $courseid . '_' . $name;
        $cache->delete($cachekey);

        return $result;
    }

    /**
     * Get options array with global default as first option.
     *
     * @param string $setting The setting name.
     * @param array $options The regular options array.
     * @return array Options array with global default prepended.
     */
    private static function get_options_with_global_default($setting, $options) {
        // Get the current global value.
        $globalvalue = get_config('theme_boost_union', $setting);

        // Find the label for the global value.
        $globallabel = isset($options[$globalvalue]) ? $options[$globalvalue] : $globalvalue;

        // Build the "Use global" option label.
        $usegloballabel = get_string('useglobaldefault', 'theme_boost_union', $globallabel);

        // Prepend the global default option.
        return [THEME_BOOST_UNION_SETTING_USEGLOBAL => $usegloballabel] + $options;
    }

    /**
     * Get the course settings configuration array for form handling.
     *
     * @return array The course settings configuration array.
     */
    public static function get_course_settings_config() {
        // Prepare options array for Yes / No select settings.
        // We will use binary select settings just as it's done in settings.php.
        $yesnooption = [
                THEME_BOOST_UNION_SETTING_SELECT_YES => get_string('yes'),
                THEME_BOOST_UNION_SETTING_SELECT_NO => get_string('no'),
            ];

        // Define the course override settings we handle with their configuration.
        return [
            'courseheaderenabled' => [
                'options' => self::get_options_with_global_default('courseheaderenabled', $yesnooption),
                'helpbutton' => true,
                'importtransfercontrolledby' => 'courseheaderimporttransfer',
                'importtransfercontrolcapa' => 'theme/boost_union:transfercourseheaderduringimport',
                'restorecontrolledby' => 'theme_boost_union_restore_course_header_settings',
            ],
            'courseheaderlayout' => [
                'options' => self::get_options_with_global_default('courseheaderlayout',
                    self::get_courseheaderlayout_options(true)),
                'helpbutton' => true,
                'hide_if' => [
                    'element' => 'theme_boost_union_courseheaderenabled',
                    'condition' => 'neq',
                    'value' => THEME_BOOST_UNION_SETTING_SELECT_YES,
                ],
                'importtransfercontrolledby' => 'courseheaderimporttransfer',
                'importtransfercontrolcapa' => 'theme/boost_union:transfercourseheaderduringimport',
                'restorecontrolledby' => 'theme_boost_union_restore_course_header_settings',
            ],
            'courseheaderheight' => [
                'options' => self::get_options_with_global_default('courseheaderheight',
                    self::get_courseheaderheight_options()),
                'helpbutton' => true,
                'hide_if' => [
                    'element' => 'theme_boost_union_courseheaderenabled',
                    'condition' => 'neq',
                    'value' => THEME_BOOST_UNION_SETTING_SELECT_YES,
                ],
                'importtransfercontrolledby' => 'courseheaderimporttransfer',
                'importtransfercontrolcapa' => 'theme/boost_union:transfercourseheaderduringimport',
                'restorecontrolledby' => 'theme_boost_union_restore_course_header_settings',
            ],
            'courseheadercanvasborder' => [
                'options' => self::get_options_with_global_default('courseheadercanvasborder',
                    self::get_courseheadercanvasborder_options()),
                'helpbutton' => true,
                'hide_if' => [
                    'element' => 'theme_boost_union_courseheaderenabled',
                    'condition' => 'neq',
                    'value' => THEME_BOOST_UNION_SETTING_SELECT_YES,
                ],
                'importtransfercontrolledby' => 'courseheaderimporttransfer',
                'importtransfercontrolcapa' => 'theme/boost_union:transfercourseheaderduringimport',
                'restorecontrolledby' => 'theme_boost_union_restore_course_header_settings',
            ],
            'courseheadercanvasbackground' => [
                'options' => self::get_options_with_global_default('courseheadercanvasbackground',
                    self::get_courseheadercanvasbackground_options()),
                'helpbutton' => true,
                'hide_if' => [
                    'element' => 'theme_boost_union_courseheaderenabled',
                    'condition' => 'neq',
                    'value' => THEME_BOOST_UNION_SETTING_SELECT_YES,
                ],
                'importtransfercontrolledby' => 'courseheaderimporttransfer',
                'importtransfercontrolcapa' => 'theme/boost_union:transfercourseheaderduringimport',
                'restorecontrolledby' => 'theme_boost_union_restore_course_header_settings',
            ],
            'courseheadertextonimagecolor' => [
                'options' => self::get_options_with_global_default('courseheadertextonimagecolor',
                    self::get_courseheadertextonimagecolor_options()),
                'helpbutton' => true,
                'hide_if' => [
                    'element' => 'theme_boost_union_courseheaderenabled',
                    'condition' => 'neq',
                    'value' => THEME_BOOST_UNION_SETTING_SELECT_YES,
                ],
                'importtransfercontrolledby' => 'courseheaderimporttransfer',
                'importtransfercontrolcapa' => 'theme/boost_union:transfercourseheaderduringimport',
                'restorecontrolledby' => 'theme_boost_union_restore_course_header_settings',
            ],
            'courseheaderimageposition' => [
                'options' => self::get_options_with_global_default('courseheaderimageposition',
                    self::get_courseheaderimageposition_options()),
                'helpbutton' => true,
                'hide_if' => [
                    'element' => 'theme_boost_union_courseheaderenabled',
                    'condition' => 'neq',
                    'value' => THEME_BOOST_UNION_SETTING_SELECT_YES,
                ],
                'importtransfercontrolledby' => 'courseheaderimporttransfer',
                'importtransfercontrolcapa' => 'theme/boost_union:transfercourseheaderduringimport',
                'restorecontrolledby' => 'theme_boost_union_restore_course_header_settings',
            ],
        ];
    }

    /**
     * Get a simple array of course setting names for iteration.
     *
     * @return array Array of course setting names.
     */
    public static function get_course_setting_names() {
        return array_keys(self::get_course_settings_config());
    }

    /**
     * Get the course filearea configuration array.
     *
     * @return array The course filearea configuration array.
     */
    public static function get_course_filearea_config() {
        return [
            'courseheaderimage' => [
                'importtransfercontrolledby' => 'courseheaderimporttransfer',
                'importtransfercontrolcapa' => 'theme/boost_union:transfercourseheaderduringimport',
                'restorecontrolledby' => 'theme_boost_union_restore_course_header_settings',
                'clearbeforewrite' => true,
            ],
        ];
    }

    /**
     * Get a simple array of course filearea names for iteration.
     *
     * @return array Array of course filearea names.
     */
    public static function get_course_filearea_names() {
        return array_keys(self::get_course_filearea_config());
    }

    /**
     * Get the options array for course header image position setting.
     *
     * @return array The options array for course header image position.
     */
    public static function get_courseheaderimageposition_options() {
        return [
            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER =>
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER,
            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP =>
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP,
            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM =>
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM,
            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP =>
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP,
            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER =>
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER,
            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM =>
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM,
            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP =>
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP,
            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER =>
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER,
            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM =>
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM,
        ];
    }

    /**
     * Get the options array for course header height setting.
     *
     * @return array The options array for course header height.
     */
    public static function get_courseheaderheight_options() {
        return [
            THEME_BOOST_UNION_SETTING_HEIGHT_100PX => THEME_BOOST_UNION_SETTING_HEIGHT_100PX,
            THEME_BOOST_UNION_SETTING_HEIGHT_150PX => THEME_BOOST_UNION_SETTING_HEIGHT_150PX,
            THEME_BOOST_UNION_SETTING_HEIGHT_200PX => THEME_BOOST_UNION_SETTING_HEIGHT_200PX,
            THEME_BOOST_UNION_SETTING_HEIGHT_250PX => THEME_BOOST_UNION_SETTING_HEIGHT_250PX,
        ];
    }

    /**
     * Get the options array for course header canvas border setting.
     *
     * @return array The options array for course header canvas border.
     */
    public static function get_courseheadercanvasborder_options() {
        return [
            THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBORDER_NONE =>
                    get_string('courseheadercanvasborder_none', 'theme_boost_union'),
            THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBORDER_GREY =>
                    get_string('courseheadercanvasborder_grey', 'theme_boost_union'),
            THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBORDER_BRANDCOLOR =>
                    get_string('courseheadercanvasborder_brandcolor', 'theme_boost_union'),
        ];
    }

    /**
     * Get the options array for course header canvas background setting.
     *
     * @return array The options array for course header canvas background.
     */
    public static function get_courseheadercanvasbackground_options() {
        return [
            THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBACKGROUND_TRANSPARENT =>
                    get_string('courseheadercanvasbackground_transparent', 'theme_boost_union'),
            THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBACKGROUND_WHITE =>
                    get_string('courseheadercanvasbackground_white', 'theme_boost_union'),
            THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBACKGROUND_LIGHTGREY =>
                    get_string('courseheadercanvasbackground_lightgrey', 'theme_boost_union'),
            THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBACKGROUND_LIGHTBRANDCOLOR =>
                    get_string('courseheadercanvasbackground_lightbrandcolor', 'theme_boost_union'),
            THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBACKGROUND_BRANDCOLORGRADIENTLIGHT =>
                    get_string('courseheadercanvasbackground_brandcolorgradientlight', 'theme_boost_union'),
            THEME_BOOST_UNION_SETTING_COURSEHEADERCANVASBACKGROUND_BRANDCOLORGRADIENTFULL =>
                    get_string('courseheadercanvasbackground_brandcolorgradientfull', 'theme_boost_union'),
        ];
    }

    /**
     * Get the options array for course header text on image color setting.
     *
     * @return array The options array for course header text on image color.
     */
    public static function get_courseheadertextonimagecolor_options() {
        return [
            THEME_BOOST_UNION_SETTING_COURSEHEADERTEXTCOLOR_WHITE =>
                    get_string('courseheadertextcolor_white', 'theme_boost_union'),
            THEME_BOOST_UNION_SETTING_COURSEHEADERTEXTCOLOR_BLACK =>
                    get_string('courseheadertextcolor_black', 'theme_boost_union'),
        ];
    }

    /**
     * Get the options array for course header layout setting.
     *
     * @param bool $forcourseform Whether to filter options for course form usage and do not return excluded layouts.
     * @return array The options array for course header layout.
     */
    public static function get_courseheaderlayout_options($forcourseform = false) {
        $options = [
            THEME_BOOST_UNION_SETTING_COURSEHEADERLAYOUT_STACKED =>
                    get_string('courseheaderlayoutstacked', 'theme_boost_union'),
            THEME_BOOST_UNION_SETTING_COURSEHEADERLAYOUT_HEADINGABOVE =>
                    get_string('courseheaderlayoutheadingabove', 'theme_boost_union'),
        ];

        // If this is for a course form, filter out layouts that are excluded for course-level selection.
        if ($forcourseform) {
            $excludedlayouts = get_config('theme_boost_union', 'courseheaderlayoutexclusionlist');
            if (!empty($excludedlayouts)) {
                $excludedlayouts = explode(',', $excludedlayouts);

                // Get the currently configured global layout to ensure it's always available.
                $currentgloballayout = get_config('theme_boost_union', 'courseheaderlayout');

                // Iterate over the excluded layouts.
                foreach ($excludedlayouts as $excludedlayout) {
                    // Exclude the layout, but only if it's not the currently configured global layout.
                    if ($excludedlayout != $currentgloballayout) {
                        unset($options[$excludedlayout]);
                    }
                }
            }
        }

        return $options;
    }

    /**
     * Check if course header image upload is enabled based on the courseheaderimagesource setting.
     *
     * @return bool True if course header image upload is enabled, false otherwise.
     */
    public static function courseheaderimage_is_enabled() {
        $courseheaderimagesource = get_config('theme_boost_union', 'courseheaderimagesource');
        return ($courseheaderimagesource == THEME_BOOST_UNION_SETTING_COURSEHEADERIMAGESOURCE_DEDICATEDPLUSGLOBAL ||
                $courseheaderimagesource == THEME_BOOST_UNION_SETTING_COURSEHEADERIMAGESOURCE_DEDICATEDNOGLOBAL ||
                $courseheaderimagesource == THEME_BOOST_UNION_SETTING_COURSEHEADERIMAGESOURCE_DEDICATEDPLUSCOURSEPLUSGLOBAL ||
                $courseheaderimagesource == THEME_BOOST_UNION_SETTING_COURSEHEADERIMAGESOURCE_DEDICATEDPLUSCOURSENOGLOBAL);
    }

    /**
     * Check if course header image setting is configured to use the global image.
     *
     * @return bool True if course header image has a global fallback, false otherwise.
     */
    public static function courseheaderimage_uses_global_image() {
        $courseheaderimagesource = get_config('theme_boost_union', 'courseheaderimagesource');
        return ($courseheaderimagesource == THEME_BOOST_UNION_SETTING_COURSEHEADERIMAGESOURCE_COURSEPLUSGLOBAL ||
                $courseheaderimagesource == THEME_BOOST_UNION_SETTING_COURSEHEADERIMAGESOURCE_DEDICATEDPLUSGLOBAL ||
                $courseheaderimagesource == THEME_BOOST_UNION_SETTING_COURSEHEADERIMAGESOURCE_DEDICATEDPLUSCOURSEPLUSGLOBAL ||
                $courseheaderimagesource == THEME_BOOST_UNION_SETTING_COURSEHEADERIMAGESOURCE_GLOBAL);
    }

    /**
     * Get the file manager options for course header image uploads.
     *
     * @return array The file manager options array.
     */
    public static function get_courseheaderimage_options() {
        global $CFG;

        // Use the same file types as core course overview files.
        $acceptedtypes = (new \core_form\filetypes_util)->normalize_file_types($CFG->courseoverviewfilesext);
        if (in_array('*', $acceptedtypes) || empty($acceptedtypes)) {
            $acceptedtypes = '*';
        }

        // Compose options.
        $options = [
            'maxfiles' => 1,
            'maxbytes' => $CFG->maxbytes,
            'subdirs' => 0,
            'accepted_types' => $acceptedtypes,
        ];

        return $options;
    }

    /**
     * Check if a course format is excluded from the course header feature.
     *
     * @param string $courseformat The course format.
     * @return bool True if the course should be excluded, false otherwise.
     */
    public static function is_courseformat_excluded_from_courseheaderfeature($courseformat) {
        // If no course format is given, we cannot determine if we have to exclude it.
        // So we don't exclude it, just to be sure.
        if (empty($courseformat)) {
            return false;
        }

        // Get the excluded course formats.
        $excludedformats = get_config('theme_boost_union', 'courseheaderformatexclusionlist');

        // If no exclusion list is configured, don't exclude any course.
        if (empty($excludedformats)) {
            return false;
        }

        // The setting is stored as a comma-separated list.
        $excludedformats = explode(',', $excludedformats);

        // Check if the course format is in the exclusion list.
        return in_array($courseformat, $excludedformats);
    }

    /**
     * Get the effective course format for exclusion checking.
     * This considers both submitted form data and current course format.
     *
     * @param object $course The course object.
     * @return string The effective course format to check.
     */
    public static function get_effective_course_format($course) {
        // If we have submitted form data (which happens if the teacher changes the course format on the course settings page),
        // and that data contains a format, use it.
        $submittedformat = optional_param('format', '', PARAM_ALPHANUMEXT);
        if (!empty($submittedformat)) {
            return $submittedformat;

            // Otherwise, if the submitted course has a format set, then use it.
        } else if (property_exists($course, 'format')) {
            return $course->format;

            // Othwerwise, if the submitted course does not haven ID (which happens when creating a new course),
            // use the default course format.
        } else if (!property_exists($course, 'id')) {
            return get_config('moodlecourse', 'format');
        }

        // As last resort, return an empty string. This should not happen.
        return '';
    }

    /**
     * Validate a course setting value and apply any necessary corrections.
     * This method handles special validation logic for specific settings.
     *
     * @param string $name The name of the setting.
     * @param mixed $value The value to validate.
     * @return mixed The validated (and potentially corrected) value.
     */
    private static function validate_course_setting_value($name, $value) {
        // Special handling for courseheaderlayout setting: Check if the layout was excluded in the meantime.
        if ($name === 'courseheaderlayout') {
            // Get the available layout options for course forms (which excludes layouts from the exclusion list).
            $availablelayouts = self::get_courseheaderlayout_options(true);

            // If the value is not in the available layouts, fall back to global setting.
            if (!array_key_exists($value, $availablelayouts)) {
                return get_config('theme_boost_union', $name);
            }
        }

        // For all other settings or if validation passes, return the original value.
        return $value;
    }
}
