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

namespace theme_boost_union;

use moodle_url;
use section_info;

/**
 * Class sectionmod
 *
 * Represents a course section with navigation properties.
 *
 * @package    theme_boost_union
 * @copyright  2024 oncampus GmbH <support@oncampus.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class sectionmod {
    /**
     * Section information object.
     *
     * @var section_info
     */
    public section_info $section;

    /**
     * Visibility of the section.
     *
     * @var bool
     */
    public bool $visible;

    /**
     * URL of the section.
     *
     * @var moodle_url
     */
    public moodle_url $url;

    /**
     * Determines if this is the next section in navigation.
     *
     * @var bool
     */
    private bool $nextsection;

    /**
     * Constructor for sectionmod.
     *
     * @param section_info $section Section information object.
     * @param bool $nextsection Indicates if this is the next section in navigation.
     */
    public function __construct(section_info $section, bool $nextsection) {
        $this->section = $section;
        $this->visible = $this->section->visible;
        $this->url = new moodle_url('/course/view.php', ['id' => $section->course, 'section' => $section->section]);
        $this->nextsection = $nextsection;
    }

    /**
     * Returns the formatted name for the section based on its position in navigation.
     *
     * @return string The name of the section for navigation (e.g., "Next section" or "Previous section").
     */
    public function get_formatted_name(): string {
        if ($this->nextsection) {
            return get_string('nextsection', 'theme_boost_union');
        } else {
            return get_string('prevsection', 'theme_boost_union');
        }
    }
}
