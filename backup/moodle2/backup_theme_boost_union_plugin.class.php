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
 * Theme Boost Union - Backup course-specific settings.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Backup course-specific settings.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_theme_boost_union_plugin extends backup_theme_plugin {
    /**
     * Define the course plugin structure for backup.
     *
     * @return backup_plugin_element
     */
    protected function define_course_plugin_structure() {
        // Define the virtual plugin element without conditions.
        $plugin = $this->get_plugin_element();

        // Create a visible container for our data.
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        // Connect our visible container to the parent.
        $plugin->add_child($pluginwrapper);

        // Define our elements.
        $courseoverride = new backup_nested_element(
            'courseoverride',
            ['id'],
            ['courseid', 'name', 'value']
        );

        // Build elements hierarchy.
        $pluginwrapper->add_child($courseoverride);

        // Set sources to populate the data.
        $courseoverride->set_source_table('theme_boost_union_course', ['courseid' => backup::VAR_COURSEID]);

        // Define file annotations for course header images.
        $pluginwrapper->annotate_files('theme_boost_union', 'courseheaderimage', null);

        // Return everything.
        return $plugin;
    }
}
