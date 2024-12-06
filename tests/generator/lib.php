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
 * Theme Boost Union - Test data generator.
 *
 * @package    theme_boost_union
 * @copyright  2024 onwards Catalyst IT EU {@link https://catalyst-eu.net}
 * @author     Mark Johnson <mark.johnson@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_boost_union_generator extends component_generator_base {
    /**
     * Generate a smart menu.
     *
     * An array of one or more locations are required, which must match the smartmenu:LOCATION_* constants.
     * All other properties are optional.
     *
     * @param array $data
     * @return stdClass
     */
    public function create_smartmenu(array $data): \stdClass {
        global $DB;
        $location = $data['location'] ?? [];
        if (empty($location)) {
            throw new Exception('A Smart menu location must be specified.');
        }
        $validlocations = array_keys(smartmenu::get_locations());
        if (!empty(array_diff($validlocations, $location))) {
            throw new Exception('Invalid Smart menu location.');
        }
        $validdescriptions = array_keys(smartmenu::get_showdescription_options());
        $showdescription = $data['showdescription'] ?? smartmenu::DESC_NEVER;
        if (!in_array($showdescription, $validdescriptions)) {
            throw new Exception('Invalid showdescription.');
        }
        $validtypes = array_keys(smartmenu::get_types());
        $type = $data['type'] ?? smartmenu::TYPE_LIST;
        if (!in_array($type, $validtypes)) {
            throw new Exception('Invalid showdescription.');
        }
        $validmodes = array_keys(smartmenu::get_mode_options());
        $mode = $data['mode'] ?? smartmenu::MODE_SUBMENU;
        if (!in_array($mode, $validmodes)) {
            throw new Exception('Invalid mode.');
        }
        $validbehaviours = array_keys(smartmenu::get_moremenu_options());
        $moremenubehaviour = $data['moremenubehaviour'] ?? smartmenu::MOREMENU_DONOTCHANGE;
        if (!in_array($moremenubehaviour, $validbehaviours)) {
            throw new Exception('Invalid moremenubehaviour.');
        }

        $cardsize = null;
        $cardform = null;
        $cardoverflowbehaviour = null;
        if ($type === smartmenu::TYPE_CARD) {
            $validcardsizes = array_keys(smartmenu::get_cardsize_options());
            $cardsize = $data['cardsize'] ?? smartmenu::CARDSIZE_SMALL;
            if (!in_array($cardsize, $validcardsizes)) {
                throw new Exception('Invalid cardsize.');
            }
            $validcardforms = array_keys(smartmenu::get_cardform_options());
            $cardform = $data['cardform'] ?? smartmenu::CARDFORM_SQUARE;
            if (!in_array($cardform, $validcardforms)) {
                throw new Exception('Invalid cardform.');
            }
            $validbehaviours = array_keys(smartmenu::get_cardoverflowbehaviour_options());
            $cardoverflowbehaviour = strtolower($data['cardoverflowbehaviour']) ?? smartmenu::CARDOVERFLOWBEHAVIOUR_NOWRAP;
            if (!in_array($cardoverflowbehaviour, $validbehaviours)) {
                throw new Exception('Invalid cardoverflowbehaviour.');
            }
        }
        [
            $roles,
            $rolecontext,
            $cohorts,
            $operator,
            $byadmin,
            $languages,
            $startdate,
            $enddate,
        ] = $this->parse_restrictions($data);

        $sortorder = $data['sortorder'] ?? $DB->count_records('theme_boost_union_menus') + 1;
        $record = (object)[
            'title' => $data['title'] ?? 'Smart menu ' . random_string(),
            'description' => $data['description'] ?? 'Smart menu description ' . random_string(),
            'description_format' => $data['description_format'] ?? FORMAT_HTML,
            'showdescription' => $showdescription,
            'sortorder' => $sortorder,
            'location' => json_encode($location),
            'type' => $type,
            'mode' => $mode,
            'cssclass' => $data['cssclass'] ?? null,
            'moremenubehaviour' => $moremenubehaviour,
            'cardsize' => $cardsize,
            'cardform' => $cardform,
            'cardoverflowbehaviour' => $cardoverflowbehaviour,
            'roles' => $roles,
            'rolecontext' => $rolecontext,
            'cohorts' => $cohorts,
            'operator' => $operator,
            'byadmin' => $byadmin,
            'languages' => $languages,
            'start_date' => $startdate,
            'end_date' => $enddate,
            'visible' => $data['visible'] ?? 1,
        ];
        $record->id = $DB->insert_record('theme_boost_union_menus', $record);
        return $record;
    }

    /**
     * Generate a Smart menu item.
     *
     * The ID of the parent menu must be specified. All other properties are optional.
     *
     * @param array $data
     * @return stdClass
     */
    public function create_smartmenu_item(array $data): \stdClass {
        global $DB;

        if (!$DB->record_exists('theme_boost_union_menus', ['id' => $data['menu']])) {
            throw new Exception('Menu not found with id ' . $data['menu']);
        }

        $sortorder = $data['sortorder'] ?? $DB->count_records('theme_boost_union_menus') + 1;

        $validtypes = array_keys(smartmenu_item::get_types());
        $type = $data['type'] ?? smartmenu_item::TYPESTATIC;
        if (!in_array($type, $validtypes)) {
            throw new Exception('Invalid type.');
        }

        $url = null;
        if ($type === smartmenu_item::TYPESTATIC) {
            if (empty($data['url'])) {
                throw new Exception('URL is required when type is static.');
            }
            $url = $data['url'];
        }

        $category = json_encode($data['category'] ?? []);
        $categorysubcats = $data['category_subcats'] ?? false;
        $enrolmentrole = json_encode($data['enrolmentrole'] ?? []);
        $completionstatus = json_encode($data['completionstatus'] ?? []);
        $daterange = json_encode($data['daterange'] ?? []);
        $customfields = json_encode($data['customfields'] ?? new stdClass());
        $listsort = null;
        $displayfield = null;
        $textcount = null;
        if ($type == smartmenu_item::TYPEDYNAMIC) {
            $validsorts = array_keys(smartmenu_item::get_listsort_options());
            $listsort = $data['listsort'] ?? smartmenu_item::LISTSORT_FULLNAME_ASC;
            if (!in_array($listsort, $validsorts)) {
                throw new Exception('Invalid listsort.');
            }
            $validdisplayfields = array_keys(smartmenu_item::get_displayfield_options());
            $displayfield = $data['displayfield'] ?? smartmenu_item::FIELD_FULLNAME;
            if (!in_array($displayfield, $validdisplayfields)) {
                throw new Exception('Invalid displayfield.');
            }
            $textcount = $data['textcount'] ?? null;
        }

        $validmodes = [
            smartmenu_item::MODE_INLINE,
            smartmenu_item::MODE_SUBMENU,
        ];
        $mode = $data['mode'] ?? smartmenu_item::MODE_INLINE;
        if (!in_array($mode, $validmodes)) {
            throw new Exception('Invalid mode.');
        }

        $validdisplays = array_keys(smartmenu_item::get_display_options());
        $display = $data['display'] ?? smartmenu_item::DISPLAY_SHOWTITLEICON;
        if (!in_array($display, $validdisplays)) {
            throw new Exception('Invalid display.');
        }

        $validtargets = array_keys(smartmenu_item::get_target_options());
        $target = $data['target'] ?? smartmenu_item::TARGET_SAME;
        if (!in_array($target, $validtargets)) {
            throw new Exception('Invalid target.');
        }

        $validtextpositions = array_keys(smartmenu_item::get_textposition_options());
        $textposition = $data['textposition'] ?? smartmenu_item::POSITION_BELOW;
        if (!in_array($textposition, $validtextpositions)) {
            throw new Exception('Invalid text position.');
        }

        [
            $roles,
            $rolecontext,
            $cohorts,
            $operator,
            $byadmin,
            $languages,
            $startdate,
            $enddate,
        ] = $this->parse_restrictions($data);

        $record = (object)[
            'title' => $data['title'] ?? 'Smart menu item ' . random_string(),
            'menu' => $data['menu'],
            'type' => $type,
            'sortorder' => $sortorder,
            'url' => $url,
            'category' => $category,
            'category_subcats' => $categorysubcats,
            'enrolmentrole' => $enrolmentrole,
            'completionstatus' => $completionstatus,
            'daterange' => $daterange,
            'customfields' => $customfields,
            'listsort' => $listsort,
            'displayfield' => $displayfield,
            'textcount' => $textcount,
            'mode' => $mode,
            'menuicon' => $data['menuicon'] ?? null,
            'display' => $display,
            'tooltip' => $data['tooltip'] ?? null,
            'target' => $target,
            'cssclass' => $data['cssclass'] ?? null,
            'textposition' => $textposition,
            'textcolor' => $data['textcolor'] ?? null,
            'backgroundcolor' => $data['backgroundcolor'] ?? null,
            'desktop' => $data['desktop'] ?? 0,
            'tablet' => $data['tablet'] ?? 0,
            'mobile' => $data['mobile'] ?? 0,
            'roles' => $roles,
            'rolecontext' => $rolecontext,
            'byadmin' => $byadmin,
            'cohorts' => $cohorts,
            'operator' => $operator,
            'languages' => $languages,
            'start_date' => $startdate,
            'end_date' => $enddate,
            'visible' => $data['visible'] ?? 1,
        ];
        $record->id = $DB->insert_record('theme_boost_union_menuitems', $record);
        return $record;
    }

    /**
     * Parse the provided restriction fields to ensure they are valid and appropriately encoded.
     *
     * @param array $data
     * @return array
     * @throws Exception
     */
    protected function parse_restrictions(array $data): array {
        $roles = $data['roles'] ?? [];
        $rolecontext = null;
        if (!empty($roles)) {
            $validcontexts = array_keys(smartmenu::get_rolecontext_options());
            $rolecontext = $data['rolecontext'] ?? smartmenu::ANYCONTEXT;
            if (!in_array($rolecontext, $validcontexts)) {
                throw new Exception('Invalid rolecontext.');
            }
        }
        $cohorts = $data['cohorts'] ?? [];
        $operator = null;
        if (!empty($cohorts)) {
            $validoperators = array_keys(smartmenu::get_operator_options());
            $operator = $data['operator'] ?? smartmenu::ANY;
            if (!in_array($operator, $validoperators)) {
                throw new Exception('Invalid operator.');
            }
        }
        $validbyadmins = array_keys(smartmenu::get_byadmin_options());
        $byadmin = $data['byadmin'] ?? smartmenu::BYADMIN_ALL;
        if (!in_array($byadmin, $validbyadmins)) {
            throw new Exception('Invalid byadmin.');
        }
        $languages = $data['languages'] ?? [];
        $startdate = $data['start_date'] ?? 0;
        $enddate = $data['end_date'] ?? 0;
        return [
            json_encode($roles),
            $rolecontext,
            json_encode($cohorts),
            $operator,
            $byadmin,
            json_encode($languages),
            $startdate,
            $enddate,
        ];
    }
}
