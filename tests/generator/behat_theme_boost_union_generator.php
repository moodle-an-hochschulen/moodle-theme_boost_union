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

use theme_boost_union\smartmenu;
use theme_boost_union\smartmenu_item;

/**
 * Theme Boost Union - Behat generator.
 *
 * @package    theme_boost_union
 * @copyright  2024 onwards Catalyst IT EU {@link https://catalyst-eu.net}
 * @author     Mark Johnson <mark.johnson@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union_generator extends behat_generator_base {
    /**
     * Define entities that can be generated with the 'the following "x" exist(s):' steps.
     *
     * Currently supported entities:
     * - "smart menus" - Requires a location, supports all options.
     *   - Most properties can be specified as they appear on the editing form.
     *   - "location", "roles", "cohorts" and "languages" support multiple values as a comma-separated list.
     *   - "roles" are specified by shortname, "cohorts" are specified by idnumber and "languages" are specified by
     *     short code (for example, en, de).
     * - "smart menu items" - Requires a menu title, supports all options except file uploads.
     *   - Most properties can be specified as they appear on the editing form.
     *   - "menu" is the title of the Smart menu this item belongs to.
     *   - "categories", "completionstatus", "daterange", "customfields", "roles", "cohorts" and "languages"
     *     support multiple values as a comma-separated list.
     *   - "roles" are specified by shortname, "cohorts" are specified by idnumber and "languages" are specified by
     *     short code (for example, en, de).
     *   - "customfields" are specified in `name: value` format, where name is the name or shortname of the custom field.
     *
     * @return array[]
     */
    protected function get_creatable_entities(): array {
        return [
            'smart menus' => [
                'singular' => 'smart menu',
                'datagenerator' => 'smartmenu',
                'required' => ['location'],
                'switchids' => [
                    'location' => 'location',
                    'showdescription' => 'showdescription',
                    'type' => 'type',
                    'mode' => 'mode',
                    'moremenubehaviour' => 'moremenubehaviour',
                    'cardform' => 'cardform',
                    'cardsize' => 'cardsize',
                    'cardoverflowbehaviour' => 'cardoverflowbehaviour',
                    'roles' => 'roles',
                    'rolecontext' => 'rolecontext',
                    'cohorts' => 'cohorts',
                    'operator' => 'operator',
                    'languages' => 'languages',
                    'byadmin' => 'byadmin',
                ],
            ],

            'smart menu items' => [
                'singular' => 'smart menu item',
                'datagenerator' => 'smartmenu_item',
                'required' => ['menu'],
                'switchids' => [
                    'menu' => 'menu',
                    'itemtype' => 'type',
                    'categories' => 'category',
                    'enrolmentrole' => 'enrolmentrole',
                    'completionstatus' => 'completionstatus',
                    'daterange' => 'daterange',
                    'customfields' => 'customfields',
                    'listsort' => 'listsort',
                    'displayfield' => 'displayfield',
                    'itemmode' => 'mode',
                    'display' => 'display',
                    'target' => 'target',
                    'textposition' => 'textposition',
                    'roles' => 'roles',
                    'rolecontext' => 'rolecontext',
                    'cohorts' => 'cohorts',
                    'operator' => 'operator',
                    'languages' => 'languages',
                    'byadmin' => 'byadmin',
                ],
            ],
        ];
    }

    /**
     * Return the ID of an identifier from an array of ids => identifiers.
     *
     * If the identifier does not appear in the array, throw an exception with a list of allowed values.
     *
     * @param string $name
     * @param string $identifier
     * @param array $options A list of allowed options, keyed by ID.
     * @return int
     */
    protected function option_id(string $name, string $identifier, array $options): int {
        $id = array_search(trim($identifier), $options);
        if ($id === false) {
            throw new Exception("Invalid {$name}: '{$identifier}'. Allowed values are '" .
                implode("', '", $options) . "'");
        }
        return $id;
    }

    /**
     * Given a comma-separated list of location strings, return an array of location IDs.
     *
     * @param string $locations A comma-separated list of locations, must be strings from the smartmenu::get_locations() array.
     * @return array
     */
    protected function get_location_id(string $locations): array {
        $locationoptions = smartmenu::get_locations();
        $locationids = [];
        foreach (explode(',', $locations) as $location) {
            $location = trim($location);
            if (empty($location)) {
                continue;
            }
            $locationid = array_search($location, $locationoptions);
            if ($locationid === false) {
                throw new Exception("Invalid location '{$location}'. Allowed locations are: " . implode(',', $locationoptions));
            }
            $locationids[] = $locationid;
        }
        return $locationids;
    }

    /**
     * Return the ID for the given showdescription setting.
     *
     * @param string $showdescription
     * @return int
     * @throws Exception
     */
    protected function get_showdescription_id(string $showdescription): int {
        return $this->option_id('showdescription', $showdescription, smartmenu::get_showdescription_options());
    }

    /**
     * Return the ID for the given menu type.
     *
     * @param string $type
     * @return int
     * @throws Exception
     */
    protected function get_type_id(string $type): int {
        return $this->option_id('type', $type, smartmenu::get_types());
    }

    /**
     * Return the ID for the given menu mode.
     *
     * @param string $mode
     * @return int
     * @throws Exception
     */
    protected function get_mode_id(string $mode): int {
        return $this->option_id('mode', $mode, smartmenu::get_mode_options());
    }

    /**
     * Return the ID for the given moremenubehavriour setting.
     *
     * @param string $moremenu
     * @return int
     * @throws Exception
     */
    protected function get_moremenubehaviour_id(string $moremenu): int {
        return $this->option_id('moremenu', $moremenu, smartmenu::get_moremenu_options());
    }

    /**
     * Return the ID for the given cardsize setting.
     *
     * @param string $cardsize
     * @return int
     * @throws Exception
     */
    protected function get_cardsize_id(string $cardsize): int {
        return $this->option_id('cardsize', $cardsize, smartmenu::get_cardsize_options());
    }

    /**
     * Return the ID for the given cardform setting.
     *
     * @param string $cardform
     * @return int
     * @throws Exception
     */
    protected function get_cardform_id(string $cardform): int {
        return $this->option_id('cardform', $cardform, smartmenu::get_cardform_options());
    }

    /**
     * Return the ID for the given cardoverflowbehaviour setting.
     *
     * @param string $cardoverflowbehaviour
     * @return int
     * @throws Exception
     */
    protected function get_cardoverflowbehaviour_id(string $cardoverflowbehaviour): int {
        return $this->option_id('cardoverflowbehaviour', $cardoverflowbehaviour, smartmenu::get_cardoverflowbehaviour_options());
    }

    /**
     * Given a comma-separated list of role shortnames, return an array of role IDs.
     *
     * @param string $roles
     * @return array
     * @throws Exception
     */
    protected function get_roles_id(string $roles): array {
        $roleids = [];
        foreach (explode(',', $roles) as $shortname) {
            $shortname = trim($shortname);
            if (empty($shortname)) {
                continue;
            }
            $roleids[] = $this->get_role_id(strtolower($shortname));
        }
        return $roleids;
    }

    /**
     * Return the ID for the given rolecontext setting.
     *
     * @param string $rolecontext
     * @return int
     * @throws Exception
     */
    protected function get_rolecontext_id(string $rolecontext): int {
        return $this->option_id('rolecontext', $rolecontext, smartmenu::get_rolecontext_options());
    }

    /**
     * Given a comma-separated list of cohort idnumbers, return an array of the cohort IDs.
     *
     * @param string $cohorts
     * @return array
     * @throws dml_exception
     */
    protected function get_cohorts_id(string $cohorts): array {
        global $DB;
        $cohortids = [];
        foreach (explode(',', $cohorts) as $idnumber) {
            $idnumber = trim($idnumber);
            if (empty($idnumber)) {
                continue;
            }
            $cohortids[] = $DB->get_field('cohort', 'id', ['idnumber' => $idnumber]);
        }
        return $cohortids;
    }

    /**
     * Return the ID for the given operator setting.
     *
     * @param string $operator
     * @return int
     * @throws Exception
     */
    protected function get_operator_id(string $operator): int {
        return $this->option_id('operator', $operator, smartmenu::get_operator_options());
    }

    /**
     * Return the ID for the given byadmin setting.
     *
     * @param string $byadmin
     * @return int
     * @throws Exception
     */
    protected function get_byadmin_id(string $byadmin): int {
        return $this->option_id('byadmin', $byadmin, smartmenu::get_byadmin_options());
    }

    /**
     * Given a comma-separated list of language short codes, return an array of each trimmed value.
     *
     * @param string $languages
     * @return array
     */
    protected function get_languages_id(string $languages): array {
        return array_map('trim', explode(',', $languages));
    }

    /**
     * Given the title of an existing Smart menu, return the menu ID.
     *
     * @param string $title
     * @return int
     * @throws dml_exception
     */
    protected function get_menu_id(string $title): int {
        global $DB;
        $id = $DB->get_field('theme_boost_union_menus', 'id', ['title' => $title]);
        if (!$id) {
            throw new Exception('Menu not found with title ' . $title);
        }
        return $id;
    }

    /**
     * Return the ID for a given smart menu item type.
     *
     * @param string $type
     * @return int
     * @throws Exception
     */
    protected function get_itemtype_id(string $type): int {
        return $this->option_id('itemtype', $type, smartmenu_item::get_types());
    }

    /**
     * Given a comma-separated list of course category ID numbers, return an array of the category IDs.
     *
     * @param string $categories
     * @return array
     * @throws dml_exception
     */
    protected function get_categories_id(string $categories): array {
        global $DB;
        $categories = explode(',', $categories);
        $categoryids = [];
        foreach ($categories as $idnumber) {
            $idnumber = trim($idnumber);
            if (empty($idnumber)) {
                continue;
            }
            $categoryids[] = $DB->get_field('course_categories', 'id', ['idnumber' => $idnumber]);
        }
        return $categoryids;
    }

    /**
     * Return role IDs for the enrolmentrole setting. {@see get_roles_id}
     *
     * @param string $roles
     * @return array
     * @throws Exception
     */
    protected function get_enrolmentrole_id(string $roles): array {
        return $this->get_roles_id($roles);
    }

    /**
     * Given a comma-separated list of completion status settings, return an array of completion status IDs.
     *
     * @param string $completionstatuses
     * @return array
     * @throws Exception
     */
    protected function get_completionstatus_id(string $completionstatuses): array {
        $completionstatusids = [];
        $statuses = smartmenu_item::get_completionstatus_options();
        foreach (explode(',', $completionstatuses) as $completionstatus) {
            $completionstatusids[] = $this->option_id('completionstatus', $completionstatus, $statuses);
        }
        return $completionstatusids;
    }

    /**
     * Given a comma-separated list of date range settings, return an array of date range IDs.
     *
     * @param string $dateranges
     * @return array
     * @throws Exception
     */
    protected function get_daterange_id(string $dateranges): array {
        $daterangeids = [];
        $ranges = smartmenu_item::get_daterange_options();
        foreach (explode(',', $dateranges) as $daterange) {
            $daterangeids[] = $this->option_id('daterange', $daterange, $ranges);
        }
        return $daterangeids;
    }

    /**
     * Return an array of custom field settings.
     *
     * Given a comma-separated list of `name: value` pairs for custom fields, return an array of [name => value].
     *
     * @param string $customfields
     * @return array
     * @throws dml_exception
     */
    protected function get_customfields_id(string $customfields): array {
        global $DB;
        $customfields = explode(',', $customfields);
        $customfieldids = [];
        foreach ($customfields as $customfield) {
            if (empty(trim($customfield))) {
                continue;
            }
            [$identifier, $value] = explode(':', $customfield);
            $identifier = trim($identifier);
            $shortname = $DB->get_field_select(
                'customfield_field',
                'shortname',
                'name = ? OR shortname = ?',
                [$identifier, $identifier]
            );
            if (!$shortname) {
                throw new Exception('No custom field found with name or shortname ' . $identifier);
            }
            $customfieldids[$shortname] = trim($value);
        }
        return $customfieldids;
    }

    /**
     * Return the ID of the given listsort option.
     *
     * @param string $listsort
     * @return int
     * @throws Exception
     */
    protected function get_listsort_id(string $listsort): int {
        return $this->option_id('listsort', $listsort, smartmenu_item::get_listsort_options());
    }

    /**
     * Return the ID of the given displayfield option.
     *
     * @param string $displayfield
     * @return int
     * @throws Exception
     */
    protected function get_displayfield_id(string $displayfield): int {
        return $this->option_id('displayfield', $displayfield, smartmenu_item::get_displayfield_options());
    }

    /**
     * Return the ID of the given Smart menu item mode setting.
     *
     * @param string $mode
     * @return int
     * @throws Exception
     */
    protected function get_itemmode_id(string $mode): int {
        return $this->option_id('itemmode', $mode, smartmenu_item::get_mode_options());
    }

    /**
     * Return the ID of the given display setting.
     *
     * @param string $display
     * @return int
     * @throws Exception
     */
    protected function get_display_id(string $display): int {
        return $this->option_id('display', $display, smartmenu_item::get_display_options());
    }

    /**
     * Return the ID of the given target setting.
     *
     * @param string $target
     * @return int
     * @throws Exception
     */
    protected function get_target_id(string $target): int {
        return $this->option_id('target', $target, smartmenu_item::get_target_options());
    }

    /**
     * Return the ID of the given textposition setting.
     *
     * @param string $textposition
     * @return int
     * @throws Exception
     */
    protected function get_textposition_id(string $textposition): int {
        return $this->option_id('textposition', $textposition, smartmenu_item::get_textposition_options());
    }
}
