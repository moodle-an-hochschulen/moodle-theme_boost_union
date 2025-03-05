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

namespace theme_boost_union\util;

/**
 * Theme Boost Union - Course utility class
 *
 * @package    theme_boost_union
 * @copyright  2024 Daniel Neis Araujo {@link https://www.adapta.online}
 *             based on code 2022 Willian Mano {@link https://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course {
    /**
     * @var \stdClass $course The course object.
     */
    protected $course;

    /**
     * Class constructor.
     *
     * @param \core_course_list_element $course The course as core_course_list_element.
     */
    public function __construct($course) {
        // Remember the course.
        $this->course = $course;
    }

    /**
     * Returns the (first) course image URL, falling back to a generated image if the course does not have a course image.
     *
     * @return string The course image URL.
     */
    public function get_courseimage() {
        global $CFG, $OUTPUT;

        // Iterate over all course image files.
        foreach ($this->course->get_course_overviewfiles() as $file) {
            // If this is a valid image.
            if ($file->is_valid_image()) {
                // Compose the URL.
                $url = \moodle_url::make_file_url('/pluginfile.php',
                    '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                    $file->get_filearea() . $file->get_filepath() . $file->get_filename(), !$file->is_valid_image());

                // And return it.
                return $url->out();
            }
        }

        // Return a generated image URL as fallback.
        return $OUTPUT->get_generated_image_for_id($this->course->id);
    }

    /**
     * Returns HTML to display course contacts.
     *
     * @return array The array of course contacts.
     */
    public function get_course_contacts() {

        // Initialize course contacts array.
        $contacts = [];

        // If there are course contacts.
        if ($this->course->has_course_contacts()) {
            // Get the course contacts.
            $instructors = $this->course->get_course_contacts();

            // Iterate over all course contacts.
            foreach ($instructors as $instructor) {
                // Get the user util for this user.
                $user = $instructor['user'];
                $userutil = new user($user->id);

                // Compose the contact and add it to the array.
                $contacts[] = [
                    'id' => $user->id,
                    'contactname' => fullname($user),
                    'userpicture' => $userutil->get_user_picture(100),
                    'role' => $instructor['role']->displayname,
                ];
            }
        }

        // Return the course contacts.
        return $contacts;
    }

    /**
     * Returns the (formatted) course category name.
     *
     * @return string The course category name.
     * @throws \moodle_exception
     */
    public function get_category(): string {
        // Get the course category.
        $cat = \core_course_category::get($this->course->category, IGNORE_MISSING);

        // If the category was not found for an unknown reason.
        if (!$cat) {
            // Return an empty string and avoid failing.
            return '';
        }

        // Return the formatted name.
        return $cat->get_formatted_name();
    }

    /**
     * Returns the course summary.
     *
     * @param \coursecat_helper $chelper The coursecat_helper helper object.
     * @return string|bool The course summary or false if there is no summary.
     */
    public function get_summary(\coursecat_helper $chelper): string {

        // If there is a summary.
        if ($this->course->has_summary()) {
            // Get and return the summary.
            return $chelper->get_course_formatted_summary($this->course,
                ['overflowdiv' => true, 'noclean' => true, 'para' => false]
            );
        }

        // Fallback.
        return false;
    }

    /**
     * Returns course custom fields.
     *
     * @return string The string of custom fields.
     */
    public function get_custom_fields(): string {

        // If there are custom fields.
        if ($this->course->has_custom_fields()) {
            // Get the course handler.
            $handler = \core_course\customfield\course_handler::create();

            // Get and return the custom fields.
            return $handler->display_custom_fields_data($this->course->get_custom_fields());
        }

        // Fallback.
        return '';
    }

    /**
     * Returns the course enrolment icons.
     *
     * @return array Array of enrolment icons
     */
    public function get_enrolment_icons(): array {
        // If there are enrolment icons.
        if ($icons = enrol_get_course_info_icons($this->course)) {
            // Return them.
            return $icons;
        }

        // Fallback.
        return [];
    }

    /**
     * Return the course progress for the given user.
     *
     * @param int $userid The user ID or 0 for the current user.
     * @return int|null The user progress as percentage or null if there is no progress information.
     */
    public function get_progress($userid = 0) {
        // Get and return the user progress.
        return \core_completion\progress::get_course_progress_percentage(get_course($this->course->id), $userid);
    }
}
