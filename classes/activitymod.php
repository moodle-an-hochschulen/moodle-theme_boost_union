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

use cm_info;
use moodle_url;

/**
 * Class activitymod
 *
 * Represents a module activity with specific properties
 * and provides methods to get formatted names for navigation.
 *
 * @package    theme_boost_union
 * @copyright  2024 oncampus GmbH <support@oncampus.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class activitymod {
    /**
     * Course module info object.
     *
     * @var cm_info
     */
    public cm_info $cm;

    /**
     * Visibility of the activity.
     *
     * @var bool
     */
    public bool $visible;

    /**
     * URL of the activity.
     *
     * @var moodle_url
     */
    public moodle_url $url;

    /**
     * Reference to the next activity, if any.
     *
     * @var mixed
     */
    private $next;

    /**
     * Constructor for activitymod class.
     *
     * @param cm_info $cm Course module info object.
     * @param mixed $next The next activity (can be null if no next activity).
     */
    public function __construct(cm_info $cm, $next) {
        $this->cm = $cm;
        $this->visible = $cm->visible;
        $this->url = $cm->url;
        $this->next = $next;
    }

    /**
     * Returns the formatted name for the activity.
     *
     * This function provides a string indicating either "Next activity" or "Previous activity"
     * depending on the context, to help with navigation.
     *
     * @return string Formatted name for the activity.
     */
    public function get_formatted_name() {
        if ($this->next) {
            return get_string('nextactivity', 'theme_boost_union');
        } else {
            return get_string('prevactivity', 'theme_boost_union');
        }
    }
}
