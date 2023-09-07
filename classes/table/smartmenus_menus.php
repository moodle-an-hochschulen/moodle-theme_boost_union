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
 * Theme Boost Union - Smart menus overview table.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\table;

defined('MOODLE_INTERNAL') || die();

// Require table library.
require_once($CFG->libdir.'/tablelib.php');

/**
 * List of smart menus.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class smartmenus_menus extends \table_sql {

    /**
     * Setup table.
     *
     * @throws \coding_exception
     */
    public function __construct() {
        global $DB;

        // Call parent constructor.
        parent::__construct('smartmenus');

        // Define the headers and columns.
        $headers[] = get_string('smartmenusmenutitle', 'theme_boost_union');
        $headers[] = get_string('smartmenusmenudescription', 'theme_boost_union');
        $headers[] = get_string('smartmenusmenulocation', 'theme_boost_union');
        $headers[] = get_string('smartmenusmenutype', 'theme_boost_union');
        $headers[] = get_string('up') . '/' . get_string('down');
        $headers[] = get_string('actions');
        $columns[] = 'title';
        $columns[] = 'description';
        $columns[] = 'location';
        $columns[] = 'type';
        $columns[] = 'updown';
        $columns[] = 'actions';
        $this->sortable(false); // Having a sortable table would be nice, but this would interfere with the up/down feature.
        $this->collapsible(false);
        $this->pageable(false); // Having a pageable table would be nice, but we will keep it simple for now.
        $this->define_columns($columns);
        $this->define_headers($headers);
        $this->define_header_column('title');

        // Set an ID to this table (just used for Behat testing).
        $this->set_attribute('id', 'smartmenus');

        // Initialize values for the updown feature.
        $this->count = 0;
        $this->totalmenus = $DB->count_records('theme_boost_union_menus');
    }

    /**
     * Location column.
     *
     * @param \stdClass $data
     * @return mixed
     */
    public function col_location($data) {
        // Decode all locations.
        $locations = json_decode($data->location);

        // Implode all given locations and show a badge for each of them.
        return (!empty($locations)) ? implode(' ', array_map(function($value) {
            $location = \theme_boost_union\smartmenu::get_location($value);
            return \html_writer::tag('span', $location, ['class' => 'badge badge-primary']);
        }, $locations)) : "";
    }

    /**
     * Type column.
     *
     * @param \stdClass $data
     * @return mixed
     */
    public function col_type($data) {
        // Get the type.
        $type = \theme_boost_union\smartmenu::get_type($data->type);

        // Return the type as badge.
        return \html_writer::tag('span', $type, ['class' => 'badge badge-primary']);
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
        $actionurl = new \moodle_url('/theme/boost_union/smartmenus/menus.php');

        // Initialize column value.
        $updown = '';

        // Get spacer icon.
        $spacer = $OUTPUT->pix_icon('spacer', '', 'moodle', array('class' => 'iconsmall'));

        // If there is more than one smart menu and we do not handle the first (number 0) smart menu.
        if ($this->count > 0) {
            // Add the up icon.
            $updown .= \html_writer::link($actionurl->out(false,
                    array('action' => 'up', 'id' => $data->id, 'sesskey' => sesskey())),
                    $OUTPUT->pix_icon('t/up', get_string('up'), 'moodle',
                            array('class' => 'iconsmall')), array('class' => 'sort-smartmenus-up-action'));

            // Otherwise, just add a spacer.
        } else {
            $updown .= $spacer;
        }

        // If there is more than one smart menu and we do not handle the last smart menu.
        if ($this->count < ($this->totalmenus - 1)) {
            // Add the down icon.
            $updown .= '&nbsp;';
            $updown .= \html_writer::link($actionurl->out(false,
                    array('action' => 'down', 'id' => $data->id, 'sesskey' => sesskey())),
                    $OUTPUT->pix_icon('t/down', get_string('down'), 'moodle',
                            array('class' => 'iconsmall')), array('class' => 'sort-smartmenus-down-action'));

            // Otherwise, just add a spacer.
        } else {
            $updown .= $spacer;
        }

        // Increase the smart menus counter.
        $this->count++;

        // Return the column value.
        return $updown;
    }

    /**
     * Actions column.
     *
     * @param \stdClass $data
     * @return mixed
     */
    public function col_actions($data) {
        global $OUTPUT;

        // Prepare action URL.
        $actionurl = new \moodle_url('/theme/boost_union/smartmenus/menus.php');

        // Initialize actions.
        $actions = array();

        // Show/Hide.
        if ($data->visible) {
            $actions[] = array(
                'url' => new \moodle_url($actionurl, array('action' => 'hide', 'id' => $data->id, 'sesskey' => sesskey())),
                'icon' => new \pix_icon('t/hide', get_string('hide')),
                'attributes' => array('class' => 'action-hide')
            );
        } else {
            $actions[] = array(
                'url' => new \moodle_url($actionurl, array('action' => 'show', 'id' => $data->id, 'sesskey' => sesskey())),
                'icon' => new \pix_icon('t/show', get_string('show')),
                'attributes' => array('class' => 'action-show')
            );
        }

        // Edit.
        $actions[] = array(
            'url' => new \moodle_url('/theme/boost_union/smartmenus/edit.php', array('id' => $data->id, 'sesskey' => sesskey())),
            'icon' => new \pix_icon('t/edit', get_string('edit')),
            'attributes' => array('class' => 'action-edit')
        );

        // Duplicate.
        $actions[] = array(
            'url' => new \moodle_url($actionurl, array('action' => 'copy', 'id' => $data->id, 'sesskey' => sesskey())),
            'icon' => new \pix_icon('t/copy', get_string('smartmenusmenuduplicate', 'theme_boost_union')),
            'attributes' => array('class' => 'action-copy')
        );

        // List items.
        $actions[] = array(
            'url' => new \moodle_url('/theme/boost_union/smartmenus/items.php', array('menu' => $data->id)),
            'icon' => new \pix_icon('e/bullet_list', get_string('list')),
            'attributes' => array('class' => 'action-list-items')
        );

        // Delete.
        $actions[] = array(
            'url' => new \moodle_url($actionurl, array('action' => 'delete', 'id' => $data->id, 'sesskey' => sesskey())),
            'icon' => new \pix_icon('t/delete', get_string('delete')),
            'attributes' => array('class' => 'action-delete'),
            'confirm' => new \confirm_action(get_string('smartmenusmenudeleteconfirm', 'theme_boost_union'))
        );

        // Compose action icons for all actions.
        $actionshtml = array();
        foreach ($actions as $action) {
            $action['attributes']['role'] = 'button';
            $actionshtml[] = $OUTPUT->action_icon(
                $action['url'],
                $action['icon'],
                ($action['confirm'] ?? null),
                $action['attributes']
            );
        }

        // Return all actions.
        return \html_writer::span(join('', $actionshtml), 'smartmenu-actions');
    }

    /**
     * Get the smart menus for the table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     * @throws \dml_exception
     */
    public function query_db($pagesize, $useinitialsbar = true) {
        global $DB;

        // Compose SQL base query.
        $sql = 'SELECT *
                FROM {theme_boost_union_menus} t
                ORDER BY sortorder';

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
                get_string('smartmenusmenunothingtodisplay', 'theme_boost_union'),
                    \core\output\notification::NOTIFY_INFO);
        $notification->set_show_closebutton(false);
        echo $OUTPUT->render($notification);
    }
}
