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
 * Theme Boost Union - Course form definition after data hook callback.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\local\hook\course;

use theme_boost_union\coursesettings;

/**
 * Course form definition after data hook callback class.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class after_form_definition_after_data {
    /**
     * Load custom field values into the form.
     *
     * @param \core_course\hook\after_form_definition_after_data $hook The hook data
     */
    public static function callback(\core_course\hook\after_form_definition_after_data $hook): void {
        global $DB;

        // If the user does not have the capability to override the course header settings in this course, we do nothing.
        $context = $hook->formwrapper->get_context();
        if (!has_capability('theme/boost_union:overridecourseheaderincourse', $context)) {
            return;
        }

        // If this course format is excluded from the course header feature, we do nothing.
        $course = $hook->formwrapper->get_course();
        $courseformat = coursesettings::get_effective_course_format($course);
        if (coursesettings::is_courseformat_excluded_from_courseheaderfeature($courseformat)) {
            return;
        }

        // Get the form.
        $mform = $hook->mform;

        // Get the course override settings which we handle.
        $coursesettings = coursesettings::get_course_setting_names();

        // Get the course override settings and set "Use global default" as the default for all settings.
        // To simplify the code logic, we always set the global default first and overwrite it later if there are course values.
        foreach ($coursesettings as $setting) {
            $overridesetting = get_config('theme_boost_union', $setting . '_courseoverride');
            if ($overridesetting) {
                // Set "Use global default" as the default value.
                $mform->setDefault('theme_boost_union_' . $setting, THEME_BOOST_UNION_SETTING_USEGLOBAL);
            }
        }

        // If this is an existing course.
        if (!empty($course->id) && $course->id > 0) {
            // Load the existing course override settings.
            $settings = coursesettings::get_all_course_overrides($course->id);

            // If we have found settings, use each of them as new form default, but only if their override is (still) allowed.
            if ($settings) {
                foreach ($coursesettings as $setting) {
                    $overridesetting = get_config('theme_boost_union', $setting . '_courseoverride');
                    if (isset($settings[$setting]) && $overridesetting) {
                        // Use the saved override value (which might be THEME_BOOST_UNION_SETTING_USEGLOBAL or a specific value).
                        $mform->setDefault('theme_boost_union_' . $setting, $settings[$setting]);
                    }
                }
            }

            // Handle course header image file manager if the feature is enabled.
            if (coursesettings::courseheaderimage_is_enabled()) {
                // Create a draft area and copy existing files to it.
                $context = \context_course::instance($course->id);
                $courseheaderimageoptions = coursesettings::get_courseheaderimage_options();
                $draftitemid = file_get_submitted_draft_itemid('theme_boost_union_courseheaderimage_filemanager');
                file_prepare_draft_area(
                    $draftitemid,
                    $context->id,
                    'theme_boost_union',
                    'courseheaderimage',
                    0,
                    $courseheaderimageoptions
                );

                // Set the draft area ID as the default value for the file manager.
                $mform->setDefault('theme_boost_union_courseheaderimage_filemanager', $draftitemid);
            }

            // If not.
        } else {
            // Handle course header image file manager if the feature is enabled.
            if (coursesettings::courseheaderimage_is_enabled()) {
                // For new courses, just prepare an empty draft area.
                $draftitemid = file_get_submitted_draft_itemid('theme_boost_union_courseheaderimage_filemanager');
                $mform->setDefault('theme_boost_union_courseheaderimage_filemanager', $draftitemid);
            }
        }
    }
}
