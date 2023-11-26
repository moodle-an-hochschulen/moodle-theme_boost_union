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
 * Theme Boost Union - Format Topics renderer
 *
 * @package    theme_boost_union
 * @copyright  2023 Mario Wehr, FH Kärnten <m.wehr@fh-kaernten.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\courseformat;

use stdClass;

class customfield_manager {
    private static ?customfield_manager $instance = null;

    // ... The content of the category description field is used for identifying the category.
    public const TOPICS_CATEGORY_KEY = 'bu_topics_custom_field_category';
    public const CUSTOM_FIELD_TOPICS_ALWAYSSHOWINITIALSECTION = 'bu_topics_alwaysshowinitalsection';
    public const CUSTOM_FIELD_TOPICS_ALWAYSSHOWSUMMARY = 'bu_topics_alwaysshowsummary';
    public const CUSTOM_FIELD_TOPICS_HIDETITLE = 'bu_topics_hidetitle';

    public const WEEKLY_CATEGORY_KEY = 'bu_weekly_field_category';
    public const CUSTOM_FIELD_WEEKLY_ALWAYSSHOWINITIALSECTION = 'bu_weekly_alwaysshowinitalsection';
    public const CUSTOM_FIELD_WEEKLY_ALWAYSSHOWSUMMARY = 'bu_weekly_alwaysshowsummary';
    public const CUSTOM_FIELD_WEEKLY_HIDETITLE = 'bu_weekly_hidetitle';

    private function __construct() {
    }

    public static function getinstance(): customfield_manager {
        if (self::$instance == null) {
            self::$instance = new customfield_manager();
        }

        return self::$instance;
    }

    /**
     * Create our category.
     *
     * @param string $categorykey category key
     * @return int category id
     */
    private function create_category(string $categorykey): int {
        global $DB;

        $cat = new stdClass();
        switch ($categorykey) {
            case self::TOPICS_CATEGORY_KEY:
                $cat->name = get_string('cf:topicscategoryname', 'theme_boost_union');
                break;
            case self::WEEKLY_CATEGORY_KEY:
                $cat->name = get_string('cf:weeklycategoryname', 'theme_boost_union');
                break;
        }
        $cat->description = $categorykey; // Description field cannot be changed via UI -> identifier.
        $cat->component = 'core_course';
        $cat->area = 'course';
        $cat->timecreated = time();
        $cat->timemodified = time();
        $cat->contextid = 1;

        return $DB->insert_record('customfield_category', $cat);
    }

    /**
     * Create custom field.
     *
     * @param string $fieldkey custom field identifier
     * @param int $catid custom field owner categroy id
     */
    private function create_field_with_name(string $fieldkey, int $catid): void {
        global $DB;

        $field = new stdClass();
        $field->shortname = $fieldkey;
        $field->type = 'checkbox';
        $field->timecreated = time();
        $field->timemodified = time();
        $field->sortorder = 0;
        $field->configdata = json_encode([
            'required"' => 0,
            'uniquevalues' => 0,
            'checkbydefault' => 0,
            'locked' => '0',
            'visibility' => '1', // Visibility is used to control availability in course settings.
        ]);
        $field->categoryid = $catid;

        switch ($fieldkey) {
            case self::CUSTOM_FIELD_TOPICS_ALWAYSSHOWSUMMARY:
            case self::CUSTOM_FIELD_WEEKLY_ALWAYSSHOWSUMMARY:
                $field->name = get_string('cf:alwaysshowsectionsummary_fieldname', 'theme_boost_union');
                break;
            case self::CUSTOM_FIELD_TOPICS_ALWAYSSHOWINITIALSECTION:
            case self::CUSTOM_FIELD_WEEKLY_ALWAYSSHOWINITIALSECTION:
                $field->name = get_string('cf:alwaysshowinitialsection_fieldname', 'theme_boost_union');
                break;
            case self::CUSTOM_FIELD_TOPICS_HIDETITLE:
            case self::CUSTOM_FIELD_WEEKLY_HIDETITLE:
                $field->name = get_string('cf:hidetitle_fieldname', 'theme_boost_union');
                $field->description = get_string('cf:hidetitle_fieldname_desc', 'theme_boost_union');
                break;
        }

        $DB->insert_record('customfield_field', $field);
    }

    /**
     * Update custom field value.
     *
     * @param stdClass $field
     * @param bool $value
     */
    private function change_field_availability(stdClass $field, bool $value): void {
        global $DB;

        $data = json_decode($field->configdata);
        $data->visibility = ($value) ? '2' : '0';
        $data->locked = ($value) ? '0' : '1';
        $field->configdata = json_encode($data);
        $DB->update_record('customfield_field', $field);
    }

    /**
     * Updated custom field.
     * @param string $fieldkey
     * @param bool $value
     */
    public function update_field_with_name(string $fieldkey, bool $value): void {
        global $DB;

        switch ($fieldkey) {
            case self::CUSTOM_FIELD_TOPICS_ALWAYSSHOWINITIALSECTION:
            case self::CUSTOM_FIELD_TOPICS_ALWAYSSHOWSUMMARY:
            case self::CUSTOM_FIELD_TOPICS_HIDETITLE:
                $categorykey = self::TOPICS_CATEGORY_KEY;
                break;
            case self::CUSTOM_FIELD_WEEKLY_ALWAYSSHOWINITIALSECTION:
            case self::CUSTOM_FIELD_WEEKLY_ALWAYSSHOWSUMMARY:
            case self::CUSTOM_FIELD_WEEKLY_HIDETITLE:
                $categorykey = self::WEEKLY_CATEGORY_KEY;
                break;
        }

        // Check if category exists.
        $textcompare = $DB->sql_compare_text('description') . ' = ' . $DB->sql_compare_text(':description');
        $cat = $DB->get_record_sql(
            "select id from {customfield_category} WHERE $textcompare",
            ['description' => $categorykey]
        );

        if (!$cat) {
            // Create category for our custom fields.
            $catid = $this->create_category($categorykey);
        } else {
            $catid = $cat->id;
        }

        // Get Field.
        $field = $DB->get_record('customfield_field', ['categoryid' => $catid, 'shortname' => $fieldkey]);
        if (!$field) {
            $this->create_field_with_name($fieldkey, $catid);
        } else {
            $this->change_field_availability($field, $value);
        }
    }
}
