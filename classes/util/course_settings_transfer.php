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
 * Theme Boost Union - Course settings transfer helper for backup/restore operations.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\util;

/**
 * Course settings helper for backup/restore operations.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_settings_transfer {

    /**
     * Transfer a single course setting from one course to another.
     *
     * This method contains the shared logic for validating and saving course-specific theme settings,
     * used by both the restore class and the course restored event observer which is called during course import.
     *
     * @param array|object $data The setting data containing name, value, and courseid.
     */
    public static function transfer_course_setting($data): void {
        global $DB;

        // Convert to object if needed.
        $data = (object)$data;

        // If not all required fields are present, return.
        if (!isset($data->courseid) || !isset($data->name) || !isset($data->value)) {
            return;
        }

        // If the given setting is not a known course-specific setting, return.
        $validsettings = \theme_boost_union\coursesettings::get_course_setting_names();
        if (!in_array($data->name, $validsettings)) {
            return;
        }

        // Check if this setting already exists for the course.
        $existingrecord = $DB->get_record('theme_boost_union_course', ['courseid' => $data->courseid, 'name' => $data->name]);

        // Insert or update the record.
        if (!$existingrecord) {
            $DB->insert_record('theme_boost_union_course', $data);
        } else {
            $existingrecord->value = $data->value;
            $DB->update_record('theme_boost_union_course', $existingrecord);
        }

        // Clear cache for this course setting.
        $cache = \cache::make('theme_boost_union', 'courseoverrides');
        $cachekey = $data->courseid . '_' . $data->name;
        $cache->delete($cachekey);
    }

    /**
     * Transfer multiple course settings from one course to another.
     *
     * This function is meant to be called during course import only.
     *
     * @param array $originalsettings Array of original course setting records.
     * @param int $newcourseid The ID of the new course.
     */
    public static function transfer_course_settings(array $originalsettings, int $newcourseid): void {
        // Get the course override settings which we handle.
        $coursesettings = \theme_boost_union\coursesettings::get_course_settings_config();

        // Get new course context.
        $newcontext = \context_course::instance($newcourseid);

        // Iterate over each original setting and transfer it to the new course.
        foreach ($originalsettings as $setting) {

            // If the setting is not controlled for course import transfer, skip it.
            $importtransfercontrolledby = $coursesettings[$setting->name]['importtransfercontrolledby'] ?? null;
            if ($importtransfercontrolledby == null) {
                continue;
            }
            $importtransferconfig = get_config('theme_boost_union', $importtransfercontrolledby);
            if ($importtransferconfig === false) {
                continue;
            }
            $importtransfercontrolcapa = $coursesettings[$setting->name]['importtransfercontrolcapa'] ?? null;
            if ($importtransfercontrolledby == THEME_BOOST_UNION_SETTING_SELECT_BYCAPABILITY &&
                    $importtransfercontrolcapa == null) {
                continue;
            }

            // Check if transfer is allowed based on the setting value.
            $transferallowed = false;
            switch ($importtransferconfig) {
                case THEME_BOOST_UNION_SETTING_SELECT_ALWAYS:
                    // Allow transfer.
                    $transferallowed = true;
                    break;
                case THEME_BOOST_UNION_SETTING_SELECT_BYCAPABILITY:
                    // Check capability in the destination course context.
                    $transferallowed = has_capability($importtransfercontrolcapa, $newcontext);
                    break;
                case THEME_BOOST_UNION_SETTING_SELECT_NEVER:
                default:
                    // Do not allow transfer.
                    $transferallowed = false;
                    break;
            }

            // If transfer is not allowed, skip this setting.
            if (!$transferallowed) {
                continue;
            }

            // Compose data.
            $data = [
                'courseid' => $newcourseid,
                'name' => $setting->name,
                'value' => $setting->value,
            ];

            // Transfer the setting to the new course.
            self::transfer_course_setting($data);
        }
    }

    /**
     * Transfer course files from original course to new course.
     *
     * This function is meant to be called during course import only.
     *
     * @param int $originalcourseid The ID of the original course.
     * @param int $newcourseid The ID of the new course.
     */
    public static function transfer_course_files(int $originalcourseid, int $newcourseid): void {
        // Get file storage.
        $fs = get_file_storage();

        // Get original course context.
        $originalcontext = \context_course::instance($originalcourseid);

        // Get new course context.
        $newcontext = \context_course::instance($newcourseid);

        // Get all file areas used by the theme for course specific file uploads.
        $fileareas = \theme_boost_union\coursesettings::get_course_filearea_config();

        // Iterate through each file area and transfer files.
        foreach ($fileareas as $filearea => $config) {

            // If the file area is not controlled for course import transfer, skip it.
            $importtransfercontrolledby = $config['importtransfercontrolledby'] ?? null;
            if ($importtransfercontrolledby == null) {
                continue;
            }
            $importtransferconfig = get_config('theme_boost_union', $importtransfercontrolledby);
            if ($importtransferconfig === false) {
                continue;
            }
            $importtransfercontrolcapa = $config['importtransfercontrolcapa'] ?? null;
            if ($importtransfercontrolledby == THEME_BOOST_UNION_SETTING_SELECT_BYCAPABILITY &&
                    $importtransfercontrolcapa == null) {
                continue;
            }

            // Check if transfer is allowed based on the setting value.
            $transferallowed = false;
            switch ($importtransferconfig) {
                case THEME_BOOST_UNION_SETTING_SELECT_ALWAYS:
                    $transferallowed = true;
                    break;
                case THEME_BOOST_UNION_SETTING_SELECT_BYCAPABILITY:
                    // Check capability in the destination course context.
                    $transferallowed = has_capability($importtransfercontrolcapa, $newcontext);
                    break;
                case THEME_BOOST_UNION_SETTING_SELECT_NEVER:
                default:
                    $transferallowed = false;
                    break;
            }

            // If transfer is not allowed, skip this setting.
            if (!$transferallowed) {
                continue;
            }

            // Clear the file area before transferring files if configured to do so.
            $clearbeforewrite = $config['clearbeforewrite'] ?? false;
            if ($clearbeforewrite) {
                // Get all existing files in the target file area.
                $existingfiles = $fs->get_area_files($newcontext->id, 'theme_boost_union', $filearea, false, 'filepath, filename',
                        false);

                // Delete all existing files in the file area.
                foreach ($existingfiles as $existingfile) {
                    $existingfile->delete();
                }
            }

            // Get all files from this file area in the original course.
            $files = $fs->get_area_files($originalcontext->id, 'theme_boost_union', $filearea, false, 'filepath, filename', false);

            // Transfer each file to the new course.
            foreach ($files as $file) {
                // Check if the file already exists in new course to avoid duplicates.
                $existingfile = $fs->get_file(
                    $newcontext->id,
                    'theme_boost_union',
                    $filearea,
                    $file->get_itemid(),
                    $file->get_filepath(),
                    $file->get_filename()
                );

                // Only create the file if it doesn't already exist.
                if (!$existingfile) {
                    $fs->create_file_from_storedfile([
                        'contextid' => $newcontext->id,
                        'component' => 'theme_boost_union',
                        'filearea' => $filearea,
                        'itemid' => $file->get_itemid(),
                        'filepath' => $file->get_filepath(),
                        'filename' => $file->get_filename(),
                    ], $file);
                }
            }
        }
    }
}
