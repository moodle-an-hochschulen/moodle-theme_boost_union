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
 * Behat navigation-related step definition overrides for the Boost Union theme.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.
// For that reason, we can't even rely on $CFG->admin being available here.

require_once(__DIR__ . '/../../../../lib/tests/behat/behat_navigation.php');
require_once(__DIR__ . '/../../../boost/tests/behat/behat_theme_boost_behat_navigation.php');

use Behat\Mink\Exception\ElementNotFoundException as ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException as ExpectationException;

/**
 * Navigation-related step definition overrides for the Boost Union theme.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union_behat_navigation extends behat_theme_boost_behat_navigation {

    /**
     * Set the course format options to one page per section.
     *
     * @Given /^"(?P<coursefullname_string>(?:[^"]|\\")*)" has been set to one page per section$/
     * @param string $coursefullname The full name of the course.
     * @return void
     */
    public function has_been_set_to_one_page_per_section(string $coursefullname) {
        $courseid = $this->get_course_id($coursefullname);
        $format = course_get_format($courseid);
        $sectionsperpage = new stdClass();
        $sectionsperpage->coursedisplay = COURSE_DISPLAY_MULTIPAGE;
        $format->update_course_format_options($sectionsperpage);
    }

    /**
     * Opens the nth section of the course.
     *
     * @When /^I am on section "(?P<section_int>(?:[^"]|\\")*)" page of "(?P<coursefullname_string>(?:[^"]|\\")*)" course$/
     * @param int $section The ID of the section.
     * @param string $coursefullname The full name of the course.
     * @return void
     */
    public function i_am_on_the_nth_section_page_of_course(int $section, string $coursefullname) {
        $courseid = $this->get_course_id($coursefullname);
        $urlparams = [
            'id' => $courseid,
            'section' => $section,
        ];
        $url = new moodle_url('/course/view.php', $urlparams);
        $this->execute('behat_general::i_visit', [$url]);
    }
}
