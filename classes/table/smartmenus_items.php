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
 * Theme Boost Union - Smart menu items overview table.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\table;

use core\output\html_writer;

defined('MOODLE_INTERNAL') || die();

// Require table library.
require_once($CFG->libdir.'/tablelib.php');

/**
 * List of smart menu items.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class smartmenus_items extends \core_table\sql_table {

    /**
     * @var int $count Smart menu items count.
     */
    private $count;

    /**
     * @var int $totalmenuitems Total menu items count.
     */
    private $totalmenuitems;

    /**
     * @var int $menuid Menu ID.
     */
    private $menuid;

    /**
     * Setup table.
     *
     * @param int $menuid The menu ID.
     * @throws \coding_exception
     */
    public function __construct($menuid) {
        global $DB;

        // Call parent constructor.
        parent::__construct('smartmenu_items');

        // Define the headers and columns.
        $headers[] = get_string('smartmenusmenuitemtitle', 'theme_boost_union');
        $headers[] = get_string('smartmenusmenuitemtype', 'theme_boost_union');
        $headers[] = get_string('smartmenusmenuitemrestriction', 'theme_boost_union');
        $headers[] = get_string('up') . '/' . get_string('down');
        $headers[] = get_string('actions');
        $columns[] = 'title';
        $columns[] = 'type';
        $columns[] = 'restrictions';
        $columns[] = 'updown';
        $columns[] = 'actions';
        $this->sortable(false); // Having a sortable table would be nice, but this would interfere with the up/down feature.
        $this->collapsible(false);
        $this->pageable(false); // Having a pageable table would be nice, but we will keep it simple for now.
        $this->define_columns($columns);
        $this->define_headers($headers);
        $this->define_header_column('title');

        // Remember the menu id for further usage.
        $this->menuid = $menuid;

        // Set an ID to this table (just used for Behat testing).
        $this->set_attribute('id', 'smartmenus_items');

        // Initialize values for the updown feature.
        $this->count = 0;
        $this->totalmenuitems = $DB->count_records('theme_boost_union_menuitems');
    }

    /**
     * Title column.
     *
     * @param stdclass $row Data of the item.
     * @return string Language formatted title of item.
     */
    public function col_title($row) {
        // Return the title after filter.
        return format_string($row->title);
    }

    /**
     * Type column.
     *
     * @param \stdClass $data
     * @return mixed
     */
    public function col_type($data) {
        // Get the type.
        $type = \theme_boost_union\smartmenu_item::get_types($data->type);

        // Return the type as badge.
        return html_writer::tag('span', $type, ['class' => 'badge bg-primary text-light']);
    }

    /**
     * Restrictions column.
     *
     * @param \stdClass $data
     * @return mixed
     */
    public function col_restrictions($data) {
        global $DB;

        // Initialize rules.
        $rules = [];

        // If we have role restrictions.
        if ($data->roles != '' && !empty(json_decode($data->roles))) {
            // Compose the rule list.
            $roles = json_decode($data->roles);
            $rolelist = $DB->get_records_list('role', 'id', $roles);
            $rolenames = role_fix_names($rolelist);
            array_walk($rolenames, function(&$value) {
                $value = html_writer::tag('span', $value->localname, ['class' => 'badge bg-primary text-light']);
            });

            // Amend rule list.
            $rules[] = [
                'name' => get_string('smartmenusbyrole', 'theme_boost_union'),
                'value' => implode(' ', $rolenames),
            ];
        }

        // If we have cohort restrictions.
        if ($data->cohorts != '' && !empty(json_decode($data->cohorts))) {
            // Compose the rule list.
            $cohorts = json_decode($data->cohorts);
            $cohortlist = $DB->get_records_list('cohort', 'id', $cohorts);
            array_walk($cohortlist, function(&$value) {
                $value = html_writer::tag('span', $value->name, ['class' => 'badge bg-primary text-light']);
            });

            // Amend rule list.
            $rules[] = [
                'name' => get_string('smartmenusbycohort', 'theme_boost_union'),
                'value' => implode(' ', $cohortlist),
            ];
        }

        // If we have language restrictions.
        if ($data->languages != '' && !empty(json_decode($data->languages))) {
            // Compose the rule list.
            $languages = json_decode($data->languages);
            $options = get_string_manager()->get_list_of_translations();
            $languagelist = [];
            foreach ($languages as $lang) {
                if (isset($options[$lang])) {
                    $languagelist[] = html_writer::tag('span', $options[$lang], ['class' => 'badge bg-primary text-light']);
                }
            }

            // Amend rule list.
            $rules[] = [
                'name' => get_string('smartmenusbylanguage', 'theme_boost_union'),
                'value' => implode(' ', $languagelist),
            ];
        }

        // If we have date restrictions.
        if ($data->start_date || $data->end_date) {
            // If we have start date restrictions.
            if ($data->start_date) {
                $datelist[] = get_string('smartmenusbydatefrom', 'theme_boost_union').': '.
                        userdate($data->start_date, get_string('strftimedate', 'core_langconfig'));
            }

            // If we have end date restrictions.
            if ($data->end_date) {
                $datelist[] = get_string('smartmenusbydateuntil', 'theme_boost_union').': '.
                        userdate($data->end_date, get_string('strftimedate', 'core_langconfig'));
            }

            array_walk($datelist, function(&$value) {
                $value = html_writer::tag('span', $value, ['class' => 'badge bg-primary text-light']);
            });

            // Amend rule list.
            $rules[] = [
                    'name' => get_string('smartmenusbydate', 'theme_boost_union'),
                    'value' => implode(' ', $datelist),
            ];
        }

        // Compose the restriction list.
        $html = '';
        foreach ($rules as $rule) {
            $html .= $rule['name'].': ';
            $html .= html_writer::empty_tag('br');
            $html .= $rule['value'];
            $html .= html_writer::empty_tag('br');
        }

        // Return the restriction list or a 'Not restricted' notice.
        return $html ? $html : get_string('smartmenusnorestrict', 'theme_boost_union');
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
        $actionurl = new \core\url('/theme/boost_union/smartmenus/items.php');

        // Initialize column value.
        $updown = '';

        // Get spacer icon.
        $spacer = $OUTPUT->pix_icon('spacer', '', 'moodle', ['class' => 'iconsmall theme_boost_union-sortorderspacer']);

        // If there is more than one smart menu item and we do not handle the first (number 0) smart menu item.
        if ($this->count > 0) {
            // Add the up icon.
            $updown .= html_writer::link($actionurl->out(false,
                    ['action' => 'up', 'id' => $data->id, 'sesskey' => sesskey()]),
                    $OUTPUT->pix_icon('t/up', get_string('up'), 'moodle',
                            ['class' => 'iconsmall']), ['class' => 'sort-smartmenuitems-up-action']);

            // Otherwise, just add a spacer.
        } else {
            $updown .= $spacer;
        }

        // If there is more than one smart menu item and we do not handle the last smart menu item.
        if ($this->count < ($this->totalmenuitems - 1)) {
            // Add the down icon.
            $updown .= '&nbsp;';
            $updown .= html_writer::link($actionurl->out(false,
                    ['action' => 'down', 'id' => $data->id, 'sesskey' => sesskey()]),
                    $OUTPUT->pix_icon('t/down', get_string('down'), 'moodle',
                            ['class' => 'iconsmall']), ['class' => 'sort-smartmenuitems-down-action']);

            // Otherwise, just add a spacer.
        } else {
            $updown .= $spacer;
        }

        // Increase the smart menu items counter.
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
        $actionurl = new \core\url('/theme/boost_union/smartmenus/items.php');

        // Initialize actions.
        $actions = [];

        // Show/Hide.
        if ($data->visible) {
            $actions[] = [
                'url' => new \core\url($actionurl, ['action' => 'hide', 'id' => $data->id, 'sesskey' => sesskey()]),
                'icon' => new \core\output\pix_icon('t/hide', get_string('hide')),
                'attributes' => ['class' => 'action-hide'],
            ];
        } else {
            $actions[] = [
                'url' => new \core\url($actionurl, ['action' => 'show', 'id' => $data->id, 'sesskey' => sesskey()]),
                'icon' => new \core\output\pix_icon('t/show', get_string('show')),
                'attributes' => ['class' => 'action-show'],
            ];
        }

        // Edit.
        $actions[] = [
            'url' => new \core\url('/theme/boost_union/smartmenus/edit_items.php',
                    ['id' => $data->id, 'sesskey' => sesskey()]),
            'icon' => new \core\output\pix_icon('t/edit', get_string('edit')),
            'attributes' => ['class' => 'action-edit'],
        ];

        // Duplicate.
        $actions[] = [
            'url' => new \core\url($actionurl, ['action' => 'copy', 'id' => $data->id, 'sesskey' => sesskey()]),
            'icon' => new \core\output\pix_icon('t/copy', get_string('smartmenusmenuitemduplicate', 'theme_boost_union')),
            'attributes' => ['class' => 'action-copy'],
        ];

        // Delete.
        $actions[] = [
            'url' => new \core\url($actionurl, ['action' => 'delete', 'id' => $data->id, 'sesskey' => sesskey()]),
            'icon' => new \core\output\pix_icon('t/delete', get_string('delete')),
            'attributes' => ['class' => 'action-delete'],
            'confirm' => new \core\output\actions\confirm_action(
                get_string('smartmenusmenuitemdeleteconfirm', 'theme_boost_union')
            ),
        ];

        // Compose action icons for all actions.
        $actionshtml = [];
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
        return html_writer::span(join('', $actionshtml), 'smartmenu-items-actions');
    }

    /**
     * Get the smart menu items for the table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     * @throws \dml_exception
     */
    public function query_db($pagesize, $useinitialsbar = true) {
        global $DB;

        // Compose SQL base query.
        $sql = 'SELECT *
                FROM {theme_boost_union_menuitems} t
                WHERE menu=:menuid
                ORDER BY sortorder';
        $sqlparams = ['menuid' => $this->menuid];

        // Get records.
        $this->rawdata = $DB->get_recordset_sql($sql, $sqlparams);
    }

    /**
     * Override the message if the table contains no entries.
     */
    public function print_nothing_to_display() {
        global $OUTPUT;

        // Show notification as html element.
        $notification = new \core\output\notification(
                get_string('smartmenusmenuitemnothingtodisplay', 'theme_boost_union'),
                        \core\output\notification::NOTIFY_INFO);
        $notification->set_show_closebutton(false);
        echo $OUTPUT->render($notification);
    }
}
