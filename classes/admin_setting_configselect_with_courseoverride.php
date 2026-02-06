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
 * Theme Boost Union - Settings class file.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

use admin_setting_flag;

/**
 * Admin setting class with course override capability.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_setting_configselect_with_courseoverride extends \admin_setting_configselect {
    /**
     * Constructor.
     *
     * @param string $name Unique ascii name, either 'mysetting' for settings that in config, or 'myplugin/mysetting'
     *                     for ones in config_plugins.
     * @param string $visiblename Localised name.
     * @param string $description Localised long description.
     * @param mixed $defaultsetting Default value for the setting.
     * @param array $choices Array of $value=>$label for each selection item.
     * @param bool $courseoverride Default value for the course override capability.
     */
    public function __construct($name, $visiblename, $description, $defaultsetting, array $choices, $courseoverride = false) {
        // Create a parent instance.
        parent::__construct($name, $visiblename, $description, $defaultsetting, $choices);

        // Allow course override with the corresponding default.
        $this->set_courseoverride_flag_options(admin_setting_flag::ENABLED, $courseoverride);
    }

    /**
     * Set the course override flag options on this admin setting.
     *
     * @param bool $enabled - One of admin_setting_flag::ENABLED or admin_setting_flag::DISABLED.
     * @param bool $default - The default for the flag.
     */
    private function set_courseoverride_flag_options($enabled, $default) {
        $this->set_flag_options($enabled, $default, 'courseoverride', get_string('courseoverride', 'theme_boost_union'));
    }

    // Note regarding function write_setting($data):
    //
    // If the flag courseoverride was enabled before and is now disabled,
    // we do NOT delete existing course overrides here. This is done to avoid data loss if the admin re-enables
    // the course override capability later again. Instead, existing (but in the meantime not allowed anymore)
    // course overrides are actively ignored by each code piece which processes the course-specifc settings.
}
