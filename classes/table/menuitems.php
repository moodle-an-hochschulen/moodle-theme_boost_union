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
 * Table to list the items for menu. Display the items access rules and it type.
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
use smartmenu_helper;

/**
 * List of items available in the menu.
 */
class menuitems extends \table_sql {

    /**
     * Setup and Render the menus table.
     *
     * @param int $pagesize Size of page for paginated displayed table.
     * @param bool $useinitialsbar Whether to use the initials bar which will only be used if there is a fullname column defined.
     * @param string $downloadhelpbutton
     */
    public function out($pagesize, $useinitialsbar, $downloadhelpbutton = '') {
        $columns = ['title', 'type', 'restrictions', 'sortorder'];

        $headers = [
            get_string('smartmenu:title', 'theme_boost_union'),
            get_string('smartmenu:types', 'theme_boost_union'),
            get_string('smartmenu:restriction', 'theme_boost_union'),
            get_string('action'),
        ];

        $this->define_columns($columns);
        $this->define_headers($headers);

        // Remove sorting for some fields.
        $this->sortable(false, 'sortorder', SORT_ASC);

        $this->guess_base_url();
        // Table name for TESTING.
        $this->set_attribute('id', 'smartmenus_item');

        parent::out($pagesize, $useinitialsbar, $downloadhelpbutton);
    }

    /**
     * Guess the base url for the participants table.
     */
    public function guess_base_url(): void {
        $menu = required_param('menu', PARAM_INT);
        $this->baseurl = new moodle_url('/theme/boost_union/smartmenus/items.php', ['menu' => $menu]);
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
        $this->set_sql('*', '{theme_boost_union_menuitems}', 'menu=:menuid', ['menuid' => $this->uniqueid]);
        parent::query_db($pagesize, $useinitialsbar);
    }

    /**
     * Display the item type, whether is static, dynamic, or heading.
     *
     * @param stdclass $row Data of the item.
     * @return string HTML content to show the item type.
     */
    public function col_type($row) {
        $type = \theme_boost_union\smartmenu_item::get_types($row->type);
        return html_writer::tag('span', $type, ['class' => 'badge badge-primary']);
    }

    /**
     * Display the access rules configured in menu. Fetch the user readable names for roles, cohorts, lanauges and dates.
     *
     * @param  object $row
     * @return string $html
     */
    public function col_restrictions($row) {
        global $DB;

        $rules = [];

        if ($row->roles != '' && !empty(json_decode($row->roles))) {
            $roles = json_decode($row->roles);
            $rolelist = $DB->get_records_list('role', 'id', $roles);
            $rolenames = role_fix_names($rolelist);
            array_walk($rolenames, function(&$value) {
                $value = html_writer::tag('span', $value->localname, ['class' => 'badge badge-primary']);
            });

            $rules[] = [
                'name' => get_string('smartmenu:byrole', 'theme_boost_union'),
                'value' => implode(' ', $rolenames)
            ];
        }

        if ($row->cohorts != '' && !empty(json_decode($row->cohorts))) {
            $cohorts = json_decode($row->cohorts);
            $cohortlist = $DB->get_records_list('cohort', 'id', $cohorts);

            array_walk($cohortlist, function(&$value) {
                $value = html_writer::tag('span', $value->name, ['class' => 'badge badge-primary']);
            });
            $rules[] = [
                'name' => get_string('smartmenu:bycohort', 'theme_boost_union'),
                'value' => implode(' ', $cohortlist)
            ];
        }

        if ($row->languages != '' && !empty(json_decode($row->languages))) {
            // Get user readable name for selected lanauges.
            $languages = json_decode($row->languages);
            $options = get_string_manager()->get_list_of_translations();
            $list = [];
            foreach ($languages as $lang) {
                if (isset($options[$lang])) {
                    $list[] = html_writer::tag('span', $options[$lang], ['class' => 'badge badge-primary']);
                }
            }
            $rules[] = [
                'name' => get_string('smartmenu:bylanguage', 'theme_boost_union'),
                'value' => implode(' ', $list)
            ];
        }

        if ($row->start_date) {
            $rules[] = [
                'name' => get_string('smartmenu:from', 'theme_boost_union'),
                'value' => userdate($row->start_date, get_string('strftimedate', 'core_langconfig') )
            ];

        }
        if ($row->end_date) {
            $rules[] = [
                'name' => get_string('smartmenu:durationuntil', 'theme_boost_union'),
                'value' => userdate($row->end_date, get_string('strftimedate', 'core_langconfig') )
            ];

        }

        $html = '';
        foreach ($rules as $rule) {
            $html .= html_writer::tag('li', html_writer::tag('label', $rule['name']) . $rule['value']);
        }
        return $html ? html_writer::tag('ul', $html) : get_string('smartmenu:norestrict', 'theme_boost_union');
    }

    /**
     * Actions Column, which contains the options to update the menuitem visibility, Update the menu, delete, duplicate, sort.
     * Used sortorder column as actions column, if not mention the sortorder column in columns order doesn't works based sortorder.
     * @param  \stdclass $row
     * @return string
     */
    public function col_sortorder($row) {
        global $OUTPUT;

        $baseurl = new \moodle_url('/theme/boost_union/smartmenus/items.php', [
            'id' => $row->id,
            'sesskey' => \sesskey()
        ]);
        $actions = array();

        // Show/Hide.
        if ($row->visible) {
            $actions[] = array(
                'url' => new \moodle_url($baseurl, array('action' => 'hide')),
                'icon' => new \pix_icon('t/hide', \get_string('hide')),
                'attributes' => array('data-action' => 'hide', 'class' => 'action-hide')
            );
        } else {
            $actions[] = array(
                'url' => new \moodle_url($baseurl, array('action' => 'show')),
                'icon' => new \pix_icon('t/show', \get_string('show')),
                'attributes' => array('data-action' => 'show', 'class' => 'action-show')
            );
        }
        // Edit.
        $actions[] = array(
            'url' => new moodle_url('/theme/boost_union/smartmenus/edit_items.php', [
                'id' => $row->id,
                'sesskey' => sesskey()
            ]),
            'icon' => new \pix_icon('t/edit', \get_string('edit')),
            'attributes' => array('class' => 'action-edit')
        );

        // Make the menu item duplicate.
        $actions[] = array(
            'url' => new \moodle_url($baseurl, ['action' => 'copy']),
            'icon' => new \pix_icon('t/copy', \get_string('smartmenu:copyitem', 'theme_boost_union')),
            'attributes' => array('class' => 'action-copy')
        );

        // Delete.
        $actions[] = array(
            'url' => new \moodle_url($baseurl, array('action' => 'delete')),
            'icon' => new \pix_icon('t/delete', \get_string('delete')),
            'attributes' => array('class' => 'action-delete'),
            'action' => new \confirm_action(get_string('smartmenu:deleteconfirmitem', 'theme_boost_union'))
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
        return html_writer::span(join('', $actionshtml), 'menu-item-actions item-actions mr-0');
    }

    /**
     * Override the default "Nothing to display" message when no menus available.
     *
     * @return void
     */
    public function print_nothing_to_display() {
        global $OUTPUT;

        // Show notification as html element.
        echo $OUTPUT->heading(get_string('itemsnothingtodisplay', 'theme_boost_union'));
    }
}
