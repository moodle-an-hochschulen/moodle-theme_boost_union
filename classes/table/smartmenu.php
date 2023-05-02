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
 * Table to list the menus.
 *
 * @package    theme_boost_union
 * @copyright  bdecent GmbH 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\table;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/lib/tablelib.php');

use moodle_url;
use html_writer;
use theme_boost_union\smartmenu as menuinstance;

/**
 * List of Smart menus table.
 */
class smartmenu extends \table_sql {

    /**
     * Setup and Render the menus table.
     *
     * @param int $pagesize Size of page for paginated displayed table.
     * @param bool $useinitialsbar Whether to use the initials bar which will only be used if there is a fullname column defined.
     * @param string $downloadhelpbutton
     */
    public function out($pagesize, $useinitialsbar, $downloadhelpbutton = '') {

        // Define table headers and columns.
        $columns = ['title', 'location', 'type', 'action'];
        $headers = [
            get_string('smartmenu:title', 'theme_boost_union'),
            get_string('smartmenu:location', 'theme_boost_union'),
            get_string('smartmenu:types', 'theme_boost_union'),

            get_string('action'),
        ];

        $this->define_columns($columns);
        $this->define_headers($headers);

        $this->guess_base_url();

        parent::out($pagesize, $useinitialsbar, $downloadhelpbutton);
    }

    /**
     * Guess the base url for the menu items table.
     */
    public function guess_base_url(): void {
        $this->baseurl = new moodle_url('/theme/boost_union/smartmenu/overview.php');
    }

    /**
     * Set the sql query to fetch smart menus list.
     *
     * @param int $pagesize Size of page for paginated displayed table.
     * @param boolean $useinitialsbar Whether to use the initials bar which will only be used if there is a fullname column defined.
     * @return void
     */
    public function query_db($pagesize, $useinitialsbar = true) {
        // Fetch all avialable records from smart menu table.
        $this->set_sql('*', '{theme_boost_union_menus}', '1=1 ORDER BY sortorder ASC');

        parent::query_db($pagesize, $useinitialsbar);
    }

    /**
     * Show the menu title in the list, render the description based on the show description value "Above, below and help".
     * Default help_icon method only works with string identifiers, rendered the help icon from template directly.
     *
     * @param object $row
     * @return void
     */
    public function col_title($row) {
        global $OUTPUT;
        $description = format_text($row->description, FORMAT_HTML);
        $title = html_writer::tag('h6', $row->title, ['class' => 'menu-title']);

        // Use title only when the description is empty.
        if (empty(html_to_text($description))) {
            return $title;
        }

        switch ($row->showdesc) {
            case menuinstance::DESC_ABOVE:
                $title = $OUTPUT->box($description) . $title;
                break;
            case menuinstance::DESC_BELOW;
                $title = $title . $OUTPUT->box($description);
                break;
            case menuinstance::DESC_HELP;
                $alt  = get_string('description');
                $data = [
                    'text' => $description,
                    'alt' => $alt,
                    'icon' => (new \pix_icon('help', $alt, 'core', ['class' => 'iconhelp']))->export_for_template($OUTPUT),
                    'ltr' => !right_to_left()
                ];
                $helpicon = $OUTPUT->render_from_template('core/help_icon', $data);
                $title = $title . $helpicon;
                break;
        }
        return $title;
    }

    /**
     * Display the locations of menu. Convert the languge shortname to user readable name.
     *
     * @param stdClass $row The row object containing the location information.
     * @return string The HTML code to display the location information as a list of badges.
     */
    public function col_location($row) {
        $locations = json_decode($row->location);
        return (!empty($locations)) ? implode(' ', array_map(function($value) {
            $location = \theme_boost_union\smartmenu::get_location($value);
            return html_writer::tag('span', $location, ['class' => 'badge badge-primary']);
        }, $locations)) : "";
    }

    /**
     * Display the "type" of column for a row in the item table.
     *
     * @param object $row The database row representing the Smart Menu item.
     * @return string The HTML representation of the "type" column for the given row.
     */
    public function col_type($row) {
        $type = \theme_boost_union\smartmenu::get_type($row->type);
        return html_writer::tag('span', $type, ['class' => 'badge badge-primary']);
    }

    /**
     * Actions Column, which contains the options to update the menuitem visibility, Update the menu, delete, duplicate, sort.
     *
     * @param  \stdclass $row
     * @return string
     */
    public function col_action($row) {
        global $OUTPUT;

        // Current visible status.
        $status = $row->visible ? get_string('enable') : get_string('disable');
        $badge = $row->visible ? 'badge-success' : 'badge-danger';
        $statusbadge = html_writer::tag('span', $status, ['class' => 'mr-1 badge '.$badge]);

        $baseurl = new \moodle_url('/theme/boost_union/smartmenus/menus.php', [
            'id' => $row->id,
            'sesskey' => \sesskey()
        ]);
        $actions = array();

        // Show/Hide.
        if ($row->visible) {
            $actions[] = array(
                'url' => new \moodle_url($baseurl, array('action' => 'hidemenu')),
                'icon' => new \pix_icon('t/hide', \get_string('hide')),
                'attributes' => array('data-action' => 'hide', 'class' => 'action-hide')
            );
        } else {
            $actions[] = array(
                'url' => new \moodle_url($baseurl, array('action' => 'showmenu')),
                'icon' => new \pix_icon('t/show', \get_string('show')),
                'attributes' => array('data-action' => 'show', 'class' => 'action-show')
            );
        }

        // Edit.
        $actions[] = array(
            'url' => new moodle_url('/theme/boost_union/smartmenus/edit.php', [
                'id' => $row->id,
                'sesskey' => sesskey()
            ]),
            'icon' => new \pix_icon('t/edit', \get_string('edit')),
            'attributes' => array('class' => 'action-edit')
        );

        // Make the menu duplicate.
        $actions[] = array(
            'url' => new \moodle_url($baseurl, ['action' => 'copy']),
            'icon' => new \pix_icon('t/copy', \get_string('smartmenu:copymenu', 'theme_boost_union')),
            'attributes' => array('class' => 'action-copy')
        );

        // List of items.
        $itemsurl = new \moodle_url('/theme/boost_union/smartmenus/items.php', ['menu' => $row->id]);
        $actions[] = array(
            'url' => $itemsurl,
            'icon' => new \pix_icon('e/bullet_list', \get_string('list')),
            'attributes' => array('class' => 'action-list-items')
        );

        // Delete.
        $actions[] = array(
            'url' => new \moodle_url($baseurl, array('action' => 'delete')),
            'icon' => new \pix_icon('t/delete', \get_string('delete')),
            'attributes' => array('class' => 'action-delete'),
            'action' => new \confirm_action(get_string('smartmenu:deleteconfirmmenu', 'theme_boost_union'))
        );

        // Move up/down.
        $actions[] = array(
            'url' => new \moodle_url($baseurl, array('action' => 'moveup')),
            'icon' => new \pix_icon('t/up', \get_string('moveup')),
            'attributes' => array('data-action' => 'moveup', 'class' => 'action-moveup')
        );
        $actions[] = array(
            'url' => new \moodle_url($baseurl, array('action' => 'movedown')),
            'icon' => new \pix_icon('t/down', \get_string('movedown')),
            'attributes' => array('data-action' => 'movedown', 'class' => 'action-movedown')
        );

        $actionshtml = array();
        foreach ($actions as $action) {
            $action['attributes']['role'] = 'button';
            $actionshtml[] = $OUTPUT->action_icon(
                $action['url'],
                $action['icon'],
                ($action['action'] ?? null),
                $action['attributes']
            );
        }
        return $statusbadge . html_writer::span(join('', $actionshtml), 'menu-item-actions item-actions mr-0');
    }
}
