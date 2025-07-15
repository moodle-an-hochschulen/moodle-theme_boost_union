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

namespace theme_boost_union\external;

use core\exception\moodle_exception;
use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_value;
use html_writer;
use moodle_url;
use theme_boost_union\navigation_helper;

/**
 * Class section_nav
 *
 * Provides navigation links for previous and next sections/activities in a course.
 *
 * @package    theme_boost_union
 * @copyright  2024 oncampus GmbH <support@oncampus.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class section_nav extends external_api {
    /**
     * Defines the parameters for the execute function.
     *
     * @return external_function_parameters Parameters required by the execute function.
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'The course ID'),
            'section' => new external_value(PARAM_INT, 'The section number within the course'),
        ]);
    }

    /**
     * Retrieves navigation links for the specified course section.
     *
     * @param int $courseid The ID of the course.
     * @param int $section The section number within the course.
     * @return array Navigation links for the previous and next sections/activities.
     */
    public static function execute(int $courseid, int $section) {
        self::validate_parameters(self::execute_parameters(), ['courseid' => $courseid, 'section' => $section]);

        require_login($courseid);

        $modinfo = get_fast_modinfo($courseid);
        $sectioninfo = $modinfo->get_section_info($section);

        $navigationlinks = navigation_helper::get_navigation_links($courseid, $sectioninfo->id, 'section');

        $prevlink = null;
        $prevname = null;
        $nextlink = null;
        $nextname = null;

        if (!empty($navigationlinks['prevElement'])) {
            [$prevlink, $prevname] = self::generate_navigation_link($courseid, $navigationlinks['prevElement'], false);
        }

        if (!empty($navigationlinks['nextElement'])) {
            [$nextlink, $nextname] = self::generate_navigation_link($courseid, $navigationlinks['nextElement'], true);
        }

        return [
            'prevlink' => $prevlink ? $prevlink->out(false) : null,
            'prevname' => $prevname,
            'nextlink' => $nextlink ? $nextlink->out(false) : null,
            'nextname' => $nextname,
        ];
    }

    /**
     * Defines the return type of the execute function.
     *
     * @return external_function_parameters The return type for the execute function.
     */
    public static function execute_returns() {
        return new external_function_parameters([
            'prevlink' => new external_value(PARAM_URL, '', VALUE_OPTIONAL),
            'prevname' => new external_value(PARAM_RAW, '', VALUE_OPTIONAL),
            'nextlink' => new external_value(PARAM_URL, '', VALUE_OPTIONAL),
            'nextname' => new external_value(PARAM_RAW, '', VALUE_OPTIONAL),
        ]);
    }

    /**
     * Generates a navigation link for a course section or activity.
     *
     * This function creates a navigation link URL and label for either the previous or next
     * section/activity within a course. It includes navigation arrows and indicates if the
     * section or activity is hidden.
     *
     * @param int $courseid The ID of the course containing the navigation element.
     * @param array $element The navigation element, containing type, ID, and visibility status.
     *                       - 'type' should be 'section' or 'activity'.
     *                       - 'id' is the section/activity ID.
     *                       - 'url' (for activity type) is the moodle_url object.
     *                       - 'visible' indicates if the element is visible.
     * @param bool $isnext Determines if this is the next item in navigation (true) or the previous item (false).
     *
     * @return array An array containing:
     *               - moodle_url|null $link: The URL to the section or activity.
     *               - string|null $name: The text label with directional arrow and visibility status.
     * @throws moodle_exception
     */
    private static function generate_navigation_link($courseid, $element, $isnext) {
        global $OUTPUT;

        // Retrieve course module information for the given course ID.
        $modinfo = get_fast_modinfo($courseid);

        // Initialize link and name as null; these will hold the navigation target's URL and display name.
        $link = null;
        $name = null;

        // Check if the element type is a section.
        if ($element['type'] === 'section') {
            // Retrieve section info and build a URL for navigation to this specific section.
            $sectioninfo = $modinfo->get_section_info_by_id($element['id']);
            $sectionnum = $sectioninfo->section;
            $link = new moodle_url("/course/view.php", ['id' => $courseid, 'section' => $sectionnum]);

            // Set the arrow direction based on whether this is a 'next' or 'previous' link.
            $arrow = $isnext ? '<i class="fas fa-caret-right"></i>' : '<i class="fas fa-caret-left"></i>';

            // Configure the display name for the section link.
            if ($isnext) {
                // Set name for the "next section" link and add a hidden label if the section is not visible.
                $name = get_string('nextsection', 'theme_boost_union');
                if (!$sectioninfo->visible) {
                    $name .= ' ' . get_string('hiddenwithbrackets');
                }
                // Append the directional arrow icon.
                $name .= html_writer::tag('span', $arrow, ['class' => 'rarrow ml-2']);
            } else {
                // Set name for the "previous section" link and add a hidden label if not visible.
                $name = html_writer::tag('span', $arrow, ['class' => 'larrow mr-2']) .
                    get_string('prevsection', 'theme_boost_union');
                if (!$sectioninfo->visible) {
                    $name .= ' ' . get_string('hiddenwithbrackets');
                }
            }
        } else if ($element['type'] === 'activity') {
            // Retrieve the course module (cm) information using the element's ID.
            $cm = $modinfo->get_cm($element['id']);
            // For activities, use the provided URL in the element array.
            $link = $element['url'];
            $arrow = $isnext ? '<i class="fas fa-caret-right"></i>' : '<i class="fas fa-caret-left"></i>';

            // Configure the display name for the activity link.
            if ($isnext) {
                // Set name for "next activity" and add hidden label if activity is not visible.
                $name = get_string('nextactivity', 'theme_boost_union');
                if (!$cm->visible) {
                    $name .= ' ' . get_string('hiddenwithbrackets');
                }
                // Append directional arrow.
                $name .= html_writer::tag('span', $arrow, ['class' => 'rarrow ml-2']);
            } else {
                // Set name for "previous activity" and add hidden label if not visible.
                $name = html_writer::tag('span', $arrow, ['class' => 'larrow mr-2']) .
                    get_string('prevactivity', 'theme_boost_union');
                if (!$cm->visible) {
                    $name .= ' ' . get_string('hiddenwithbrackets');
                }
            }
        }

        // Return an array containing the URL link and formatted name of the navigation element.
        return [$link, $name];
    }
}
