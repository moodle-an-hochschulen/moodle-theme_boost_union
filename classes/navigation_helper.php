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

/**
 * Helper class for managing navigation elements in a course.
 *
 * This class generates navigation links for moving between sections and activities
 * within a Moodle course, taking user visibility into account.
 *
 * @package    theme_boost_union
 * @copyright  2024 oncampus GmbH <support@oncampus.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class navigation_helper {
    /**
     * Retrieves the previous and next navigation elements for a given item within a course.
     *
     * This function constructs a navigation sequence of all visible sections and activities within a course.
     * It then finds the position of the specified section or activity within this sequence, which includes
     * both sections and activities as they appear to the user.
     *
     * The function returns the elements immediately before and after the specified item, effectively providing
     * either the previous or next section or activity. This allows for seamless navigation between sections
     * and activities in the course structure, skipping hidden or inaccessible items.
     *
     * @param int $courseid The ID of the course.
     * @param int $itemid The ID of the section or activity (could be section ID or cm ID).
     * @param string $type The type of the item ('section' or 'activity').
     * @param bool $sectionmodjumps Optional. Whether to include section jumps in the navigation sequence. Default is true.
     *
     * @return array Returns an associative array with:
     *               - 'prevElement': The previous item in the sequence (either a section or activity),
     *                 or null if there is no previous item.
     *               - 'nextElement': The next item in the sequence (either a section or activity),
     *                 or null if there is no next item.
     *               Each element in the array is an associative array containing details about the navigation item.
     */
    public static function get_navigation_links($courseid, $itemid, $type, $sectionmodjumps = true) {
        // Build navigation sequence for the course based on visibility and structure.
        $sequence = self::build_navigation_sequence($courseid, $sectionmodjumps);

        // Locate the position of the current item (section or activity) in the sequence.
        $position = self::get_navigation_position($sequence, $itemid, $type);

        // Initialize previous and next items as null.
        $prev = null;
        $next = null;

        // If the item exists in the sequence, find its previous and next elements.
        if ($position !== null) {
            if (isset($sequence[$position + 1])) {
                $next = $sequence[$position + 1];
            }
            if (isset($sequence[$position - 1])) {
                $prev = $sequence[$position - 1];
            }

            return [
                'prevElement' => $prev,
                'nextElement' => $next,
            ];
        }

        // Return null if no previous or next elements exist in the sequence.
        return [
            'prevElement' => null,
            'nextElement' => null,
        ];
    }

    /**
     * Builds a sequential array of all sections and activities within a course, optionally including section jumps.
     *
     *  Each entry in the sequence represents either a section or an activity.
     *  Only elements that are visible to the user are included in the sequence.
     *
     * @param int $courseid The ID of the course.
     * @param bool $sectionmodjumps Optional. If true, includes sections as individual navigation points.
     *
     * @return array Returns an array of navigation items (sections and activities) in the course.
     *               Each item is an associative array containing 'type' ('section' or 'activity'), 'id', and for activities, 'url'.
     */
    private static function build_navigation_sequence($courseid, $sectionmodjumps = true) {
        $modinfo = get_fast_modinfo($courseid);
        $sequence = [];

        // Loop through each section in the course.
        foreach ($modinfo->get_section_info_all() as $section) {
            // Only include sections that are visible and have a valid section number.
            if ($section->uservisible && $section->section > 0) {
                // If section jumps are enabled, add the section to the navigation sequence.
                if ($sectionmodjumps) {
                    $sequence[] = [
                        'type' => 'section',
                        'id' => $section->id,
                    ];
                }

                // Process each activity in the section sequence, including only those that are:
                // - visible to the user (`uservisible`)
                // - have a URL (some items like labels may not have a URL)
                // - visible on the course page (`visibleoncoursepage`), i.e., not in "stealth mode".
                if (!empty($section->sequence)) {
                    $sectionmods = explode(',', $section->sequence);
                    foreach ($sectionmods as $cmid) {
                        $cm = $modinfo->get_cm($cmid);

                        // Apply the visibility filters for each activity.
                        if ($cm->uservisible && !empty($cm->url) && $cm->visibleoncoursepage) {
                            $sequence[] = [
                                'type' => 'activity',
                                'id' => $cm->id,
                                'url' => $cm->url,
                            ];
                        }
                    }
                }
            }
        }
        return $sequence;
    }

    /**
     * Determines the position of a specific item (section or activity) within the navigation sequence.
     *
     * This function searches the sequence for the specified item and returns its index if found.
     *
     * @param array $sequence The navigation sequence array, containing sections and activities.
     * @param int $itemid The ID of the section or activity (could be section ID or cm ID).
     * @param string $type The type of the item ('section' or 'activity').
     *
     * @return int|null Returns the index of the item in the sequence array, or null if the item is not found.
     */
    private static function get_navigation_position($sequence, $itemid, $type) {
        // Loop through the sequence to locate the item's index.
        foreach ($sequence as $index => $item) {
            if ($item['type'] === $type && $item['id'] == $itemid) {
                return $index;
            }
        }
        // Return null if the item is not found in the sequence.
        return null;
    }
}
