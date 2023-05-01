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
 * Theme Boost Union - Flavours overview table
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @copyright  based on code by bdecent gmbh <https://bdecent.de> in format_kickstart.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

defined('MOODLE_INTERNAL') || die();

// Require table library.
require_once($CFG->libdir.'/tablelib.php');

/**
 * List of flavours.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @copyright  based on code by bdecent gmbh <https://bdecent.de> in format_kickstart.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class flavours_overview_table extends \table_sql {

    /**
     * Setup table.
     *
     * @throws \coding_exception
     */
    public function __construct() {
        global $DB;

        // Call parent constructor.
        parent::__construct('flavours');

        // Define the headers and columns.
        $headers[] = get_string('flavourstitle', 'theme_boost_union');
        $headers[] = get_string('flavoursdescription', 'theme_boost_union');
        $headers[] = get_string('flavoursappliesto', 'theme_boost_union');
        $headers[] = get_string('up') .'/'. get_string('down');
        $headers[] = get_string('actions');
        $columns[] = 'title';
        $columns[] = 'description';
        $columns[] = 'appliesto';
        $columns[] = 'updown';
        $columns[] = 'actions';
        $this->sortable(false); // Having a sortable table would be nice, but this would interfere with the up/down feature.
        $this->collapsible(false);
        $this->pageable(false); // Having a pageable table would be nice, but we will keep it simple for now.
        $this->define_columns($columns);
        $this->define_headers($headers);

        // Initialize values for the updown feature.
        $this->count = 0;
        $this->totalflavours = $DB->count_records('theme_boost_union_flavours');
    }

    /**
     * Updown column.
     *
     * @param \stdClass $data
     * @return mixed
     */
    public function col_updown($data) {
        global $OUTPUT;

        // Prepare action URL.
        $actionurl = new \moodle_url('/theme/boost_union/flavours/overview.php');

        // Initialize column value.
        $updown = '';

        // Get spacer icon.
        $spacer = $OUTPUT->pix_icon('spacer', '', 'moodle', array('class' => 'iconsmall'));

        // If there is more than one flavour and we do not handle the first (number 0) flavour.
        if ($this->count > 0) {
            // Add the up icon.
            $updown .= \html_writer::link($actionurl->out(false,
                            array('action' => 'up', 'id' => $data->id)),
                            $OUTPUT->pix_icon('t/up', get_string('up'), 'moodle',
                                    array('class' => 'iconsmall')), array('class' => 'sort-flavour-up-action'));

            // Otherwise, just add a spacer.
        } else {
            $updown .= $spacer;
        }

        // If there is more than one flavour and we do not handle the last flavour.
        if ($this->count < ($this->totalflavours - 1)) {
            // Add the down icon.
            $updown .= '&nbsp;';
            $updown .= \html_writer::link($actionurl->out(false,
                            array('action' => 'down', 'id' => $data->id)),
                            $OUTPUT->pix_icon('t/down', get_string('down'), 'moodle',
                                    array('class' => 'iconsmall')), array('class' => 'sort-flavour-down-action'));

            // Otherwise, just add a spacer.
        } else {
            $updown .= $spacer;
        }

        // Increase the flavour counter.
        $this->count++;

        // Return the column value.
        return $updown;
    }

    /**
     * Applies to column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_appliesto($data) {
        // Initialize the badges.
        $badges = array();

        // If apply-to-categories is enabled, add a badge.
        if ($data->applytocategories == true) {
            $badges[] = \html_writer::tag('span',
                    get_string('categories'),
                    array('class' => 'badge badge-primary'));
        }

        // If apply-to-cohorts is enabled, add a badge.
        if ($data->applytocohorts == true) {
            $badges[] = \html_writer::tag('span',
                    get_string('cohorts', 'cohort'),
                    array('class' => 'badge badge-primary'));
        }

        // Implode and return the badges.
        return implode(' ', $badges);
    }

    /**
     * Actions column.
     *
     * @param \stdClass $data
     * @return string
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function col_actions($data) {
        global $OUTPUT;

        // Compose and return the action buttons.
        return
            $OUTPUT->single_button(
                    new \moodle_url('/theme/boost_union/flavours/preview.php', ['id' => $data->id]),
                    get_string('flavourspreview', 'theme_boost_union'), 'get').
            $OUTPUT->single_button(
                    new \moodle_url('/theme/boost_union/flavours/edit.php', ['action' => 'edit', 'id' => $data->id]),
                    get_string('flavoursedit', 'theme_boost_union'), 'get').
            $OUTPUT->single_button(
                    new \moodle_url('/theme/boost_union/flavours/edit.php', ['action' => 'delete', 'id' => $data->id]),
                    get_string('flavoursdelete', 'theme_boost_union'), 'get');
    }

    /**
     * Get the flavours for the table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     * @throws \dml_exception
     */
    public function query_db($pagesize, $useinitialsbar = true) {
        global $DB;

        // Compose SQL base query.
        $sql = 'SELECT *
                FROM {theme_boost_union_flavours} t
                ORDER BY sort';

        // Get records.
        $this->rawdata = $DB->get_recordset_sql($sql);
    }

    /**
     * Override the message if the table contains no entries.
     */
    public function print_nothing_to_display() {
        global $OUTPUT;

        // Show notification as html element.
        $notification = new \core\output\notification(
                get_string('flavoursnothingtodisplay', 'theme_boost_union'), \core\output\notification::NOTIFY_INFO);
        $notification->set_show_closebutton(false);
        echo $OUTPUT->render($notification);
    }

}
