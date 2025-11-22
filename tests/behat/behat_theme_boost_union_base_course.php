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
 * Theme Boost Union - Behat step definitions for course-related actions.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

/**
 * Theme Boost Union - Behat step definitions for course-related actions.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union_base_course extends behat_base {
    /**
     * Mark a course as complete for a given user.
     *
     * @Given /^the course "(?P<coursename>(?:[^"]|\\")*)" is marked complete for user "(?P<username>(?:[^"]|\\")*)"$/
     * @param string $coursename
     * @param string $username
     */
    public function course_is_marked_complete_for_user($coursename, $username) {
        global $DB;

        // Get user ID.
        $user = $DB->get_record('user', ['username' => $username], 'id', MUST_EXIST);

        // Get course ID.
        $course = $DB->get_record('course', ['shortname' => $coursename], 'id', MUST_EXIST);

        // Update or create course completion record to mark course as "completed".
        $coursecompletion = $DB->get_record('course_completions', [
            'course' => $course->id,
            'userid' => $user->id,
        ]);

        if ($coursecompletion) {
            // Mark as completed.
            if (empty($coursecompletion->timestarted)) {
                $coursecompletion->timestarted = time() - 86400; // Started yesterday.
            }
            $coursecompletion->timecompleted = time();
            $DB->update_record('course_completions', $coursecompletion);
        } else {
            // Create new course completion record as completed.
            $coursecompletion = new stdClass();
            $coursecompletion->course = $course->id;
            $coursecompletion->userid = $user->id;
            $coursecompletion->timeenrolled = time() - 172800; // Enrolled 2 days ago.
            $coursecompletion->timestarted = time() - 86400; // Started yesterday.
            $coursecompletion->timecompleted = time(); // Completed now.
            $DB->insert_record('course_completions', $coursecompletion);
        }

        // Clear completion cache.
        $completioncache = cache::make('core', 'completion');
        $cachekey = "{$user->id}_{$course->id}";
        $completioncache->delete($cachekey);
    }

    /**
     * Mark a course as in progress for a given user.
     *
     * @Given /^the course "(?P<coursename>(?:[^"]|\\")*)" is marked in progress for user "(?P<username>(?:[^"]|\\")*)"$/
     * @param string $coursename
     * @param string $username
     */
    public function course_is_marked_in_progress_for_user($coursename, $username) {
        global $DB;

        // Get user ID.
        $user = $DB->get_record('user', ['username' => $username], 'id', MUST_EXIST);

        // Get course ID.
        $course = $DB->get_record('course', ['shortname' => $coursename], 'id', MUST_EXIST);

        // Update or create course completion record to mark course as "in progress".
        $coursecompletion = $DB->get_record('course_completions', [
            'course' => $course->id,
            'userid' => $user->id,
        ]);

        if ($coursecompletion) {
            // Mark as in progress (timestarted set, no timecompleted).
            if (empty($coursecompletion->timestarted)) {
                $coursecompletion->timestarted = time();
            }
            $coursecompletion->timecompleted = null;
            $DB->update_record('course_completions', $coursecompletion);
        } else {
            // Create new course completion record as in progress.
            $coursecompletion = new stdClass();
            $coursecompletion->course = $course->id;
            $coursecompletion->userid = $user->id;
            $coursecompletion->timeenrolled = time() - 86400; // Enrolled yesterday.
            $coursecompletion->timestarted = time(); // Started now.
            $coursecompletion->timecompleted = null; // Not completed.
            $DB->insert_record('course_completions', $coursecompletion);
        }

        // Clear completion cache.
        $completioncache = cache::make('core', 'completion');
        $cachekey = "{$user->id}_{$course->id}";
        $completioncache->delete($cachekey);
    }

    /**
     * Unenrol a user from a course.
     *
     * @Given /^the user "(?P<username>(?:[^"]|\\")*)" is unenrolled from course "(?P<coursename>(?:[^"]|\\")*)"$/
     * @param string $username
     * @param string $coursename
     */
    public function user_is_unenrolled_from_course($username, $coursename) {
        global $DB;

        // Get user ID.
        $user = $DB->get_record('user', ['username' => $username], 'id', MUST_EXIST);

        // Get course ID.
        $course = $DB->get_record('course', ['shortname' => $coursename], 'id', MUST_EXIST);

        // Get course context.
        $context = context_course::instance($course->id);

        // Get all enrolment instances for this course.
        $instances = $DB->get_records('enrol', ['courseid' => $course->id]);

        // Try to find and delete user enrolments.
        foreach ($instances as $instance) {
            $ue = $DB->get_record('user_enrolments', [
                'enrolid' => $instance->id,
                'userid' => $user->id,
            ]);

            if ($ue) {
                // Get the enrolment plugin.
                $plugin = enrol_get_plugin($instance->enrol);
                if ($plugin) {
                    // Use the plugin's unenrol method for proper cleanup.
                    $plugin->unenrol_user($instance, $user->id);
                } else {
                    // Fallback: direct database deletion.
                    $DB->delete_records('user_enrolments', ['id' => $ue->id]);
                    $DB->delete_records('role_assignments', [
                        'userid' => $user->id,
                        'contextid' => $context->id,
                    ]);
                }
            }
        }
    }
}
