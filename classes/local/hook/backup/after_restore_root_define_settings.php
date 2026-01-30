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
 * Theme Boost Union - Course restore settings hook callback.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\local\hook\backup;

/**
 * Course restore settings hook callback class.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class after_restore_root_define_settings {

    /**
     * Hook callback to add theme-specific settings to the restore process.
     *
     * @param \core_backup\hook\after_restore_root_define_settings $hook The hook instance.
     */
    public static function callback(\core_backup\hook\after_restore_root_define_settings $hook): void {
        // Get the task.
        $task = $hook->task;

        // Create a setting to control whether course header settings should be restored.
        $defaultvalue = true;
        $courseheadersetting = new \restore_generic_setting(
            'theme_boost_union_restore_course_header_settings',
            \base_setting::IS_BOOLEAN,
            $defaultvalue
        );
        $courseheadersetting->set_ui(new \backup_setting_ui_checkbox(
            $courseheadersetting,
            get_string('courseheaderrestoreoption', 'theme_boost_union')
        ));

        // Add the setting to the task.
        $task->add_setting($courseheadersetting);
    }
}
