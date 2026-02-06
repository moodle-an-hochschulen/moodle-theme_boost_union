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
 * Theme Boost Union - Course form submission hook callback.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\local\hook\course;

use theme_boost_union\coursesettings;

/**
 * Course form submission hook callback class.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class after_form_submission {
    /**
     * Save custom field values after course form submission.
     *
     * @param \core_course\hook\after_form_submission $hook The hook data
     */
    public static function callback(\core_course\hook\after_form_submission $hook): void {
        global $DB;

        // Get the submitted form data.
        $data = $hook->get_data();

        // Only proceed if we have a course ID.
        if (!empty($data->id)) {
            // Get the context from the course ID in the submitted data.
            $context = \context_course::instance($data->id);

            // If the user does not have the capability to override the course header settings in this course, we do nothing.
            if (!has_capability('theme/boost_union:overridecourseheaderincourse', $context)) {
                return;
            }

            // If this course format is excluded from the course header feature, we do nothing.
            if (!empty($data->format) && coursesettings::is_courseformat_excluded_from_courseheaderfeature($data->format)) {
                return;
            }

            // Remember the course ID in a more useable variable.
            $courseid = $data->id;

            // Get the course override record from the database.
            $record = $DB->get_record('theme_boost_union_course', ['courseid' => $courseid]);

            // If no record exists, create a new one.
            if (!$record) {
                $record = new \stdClass();
                $record->courseid = $courseid;
            }

            // Get the course override settings which we handle.
            $coursesettings = coursesettings::get_course_setting_names();

            // Process each setting individually.
            foreach ($coursesettings as $setting) {
                $overridesetting = get_config('theme_boost_union', $setting . '_courseoverride');
                if ($overridesetting) {
                    $formfieldname = 'theme_boost_union_' . $setting;
                    $value = $data->$formfieldname ?? null;

                    // If "Use global default" is selected, delete the override record.
                    if ($value == THEME_BOOST_UNION_SETTING_USEGLOBAL) {
                        coursesettings::set_course_setting($courseid, $setting, null);
                    } else {
                        // Save the specific override value.
                        coursesettings::set_course_setting($courseid, $setting, $value);
                    }
                }
            }

            // Handle course header image file manager if the feature is enabled.
            if (coursesettings::courseheaderimage_is_enabled()) {
                // Handle the file manager for course header image.
                if (isset($data->theme_boost_union_courseheaderimage_filemanager)) {
                    // Save the files from the draft area to the real file area.
                    $courseheaderimageoptions = coursesettings::get_courseheaderimage_options();
                    $context = \context_course::instance($courseid);
                    file_save_draft_area_files(
                        $data->theme_boost_union_courseheaderimage_filemanager,
                        $context->id,
                        'theme_boost_union',
                        'courseheaderimage',
                        0,
                        $courseheaderimageoptions
                    );
                }
            }
        }
    }
}
