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
 * Theme Boost Union - Restore course-specific settings.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Restore course-specific settings.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_theme_boost_union_plugin extends restore_theme_plugin {
    /**
     * Define the course plugin structure for restore.
     *
     * @return array
     */
    protected function define_course_plugin_structure() {
        // Create path array.
        $paths = [];

        // Create courseoverride path element.
        $elementname = 'courseoverride';
        $elementpath = $this->get_pathfor($elementname);
        $paths[] = new restore_path_element($elementname, $elementpath);

        // Return paths.
        return $paths;
    }

    /**
     * Process course override data during restore.
     *
     * @param array $data The data to process
     */
    public function process_courseoverride($data) {
        // Convert to object and set target course ID.
        $data = (object)$data;
        $data->courseid = $this->task->get_courseid();

        // Get the course override settings which we handle.
        $coursesettings = \theme_boost_union\coursesettings::get_course_settings_config();

        // If the given setting is controlled by a restore setting.
        if (
            array_key_exists($data->name, $coursesettings) &&
                array_key_exists('restorecontrolledby', $coursesettings[$data->name])
        ) {
            // If the user does not want to restore this setting, return.
            $restorecontrolledby = $coursesettings[$data->name]['restorecontrolledby'] ?? null;
            $restoreconfig = $this->task->get_setting_value($restorecontrolledby);
            if ($restoreconfig != true) {
                return;
            }
        }

        // Use the course transfer helper to transfer the setting.
        \theme_boost_union\util\course_settings_transfer::transfer_course_setting($data);
    }

    /**
     * Process course plugin files after restore.
     *
     * This method is called after the course has been restored to restore any files associated with the theme settings.
     */
    public function after_restore_course() {
        // Get file storage.
        $fs = get_file_storage();

        // Get new course context.
        $newcontext = \context_course::instance($this->task->get_courseid());

        // Get all file areas used by the theme for course specific file uploads.
        $fileareas = \theme_boost_union\coursesettings::get_course_filearea_config();

        // Iterate through each file area and transfer files.
        foreach ($fileareas as $filearea => $config) {
            // If the given filearea is controlled by a restore setting.
            if (array_key_exists('restorecontrolledby', $config)) {
                // If the user does not want to restore this filearea, return.
                $restorecontrolledby = $config['restorecontrolledby'] ?? null;
                $restoreconfig = $this->task->get_setting_value($restorecontrolledby);
                if ($restoreconfig != true) {
                    return;
                }
            }

            // Clear the file area before transferring files if configured to do so.
            $clearbeforewrite = $config['clearbeforewrite'] ?? false;
            if ($clearbeforewrite) {
                // Get all existing files in the target file area.
                $existingfiles = $fs->get_area_files(
                    $newcontext->id,
                    'theme_boost_union',
                    $filearea,
                    false,
                    'filepath, filename',
                    false
                );

                // Delete all existing files in the file area.
                foreach ($existingfiles as $existingfile) {
                    $existingfile->delete();
                }
            }

            // Restore course specific file uploads if they exist in the backup.
            // Use null as mapping item name for course-level files.
            $this->add_related_files('theme_boost_union', $filearea, null);
        }
    }
}
