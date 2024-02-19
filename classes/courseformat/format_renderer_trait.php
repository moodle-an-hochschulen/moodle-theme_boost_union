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

use core_course\customfield\course_handler;
use core_customfield\data_controller;
use stdClass;

trait format_renderer_trait {
    /** @var data_controller[]|null $tasks */
    private ?array $datactrllist = null;

    /**
     * Get custom field value.
     *
     * @param string $fieldkey
     * @return bool override allowed
     */
    private function is_override_allowed(string $fieldkey): bool {
        global $COURSE;

        // Only create data controller array once.
        if (is_null($this->datactrllist)) {
            $this->datactrllist = course_handler::create()->get_instance_data($COURSE->id);
        }
        // Loop through data controllers and search for our field keys.
        foreach ($this->datactrllist as $datactrl) {
            if ($datactrl->get_field()->get('shortname') == $fieldkey) {
                return $datactrl->get_value();
            }
        }
        return false;
    }

    /**
     * Get custom field value.
     *
     * @param string $formatname
     * @param stdClass $templatedata
     */
    private function setup_courseformat_additions(string $formatname, stdClass $templatedata): void {
        // Always show section summary.
        $formatnameuppercase = strtoupper($formatname);
        $alwaysshowsummary =
            (get_config('theme_boost_union', "cf_{$formatname}alwaysshowsectionsummary")
                == THEME_BOOST_UNION_SETTING_SELECT_YES
            );
        $alwaysshowsectionsummaryoverride =
            (get_config('theme_boost_union', "cf_{$formatname}alwaysshowsectionsummaryoverride")
                == THEME_BOOST_UNION_SETTING_SELECT_YES
            );

        // ... if per course override is allowed.
        if ($alwaysshowsectionsummaryoverride) {
            $alwaysshowsummary = $this->is_override_allowed(
                constant(customfield_manager::class . "::CUSTOM_FIELD_{$formatnameuppercase}_ALWAYSSHOWSUMMARY")
            );
        }

        if ($alwaysshowsummary) {
            $this->page->requires->js_call_amd('theme_boost_union/course_format/section-summary', 'init');
        }

        // Always show initial section.
        $alwaysshowinitalsection =
            (get_config('theme_boost_union', "cf_{$formatname}alwaysshowinitialsection")
                == THEME_BOOST_UNION_SETTING_SELECT_YES
            );

        $alwaysshowinitialsectionoverride =
            (get_config('theme_boost_union', "cf_{$formatname}alwaysshowinitialsectionoverride")
                == THEME_BOOST_UNION_SETTING_SELECT_YES
            );

        // ... if per course override is allowed.
        if ($alwaysshowinitialsectionoverride) {
            $alwaysshowinitalsection = $this->is_override_allowed(
                constant(customfield_manager::class . "::CUSTOM_FIELD_{$formatnameuppercase}_ALWAYSSHOWINITIALSECTION")
            );
        }

        if ($alwaysshowinitalsection) {
            $this->page->requires->js_call_amd('theme_boost_union/course_format/initial-section', 'init');

            $templatedata->initialsection->iscoursedisplaymultipage = true;
            $templatedata->initialsection->collapsemenu = false;
            $templatedata->initialsection->contentcollapsed = true;
            $templatedata->initialsection->header->headerdisplaymultipage = true;

            // Should title be hidden?
            $hidetile = (get_config('theme_boost_union', "cf_{$formatname}hidetitle") == THEME_BOOST_UNION_SETTING_SELECT_YES);

            if ($alwaysshowinitialsectionoverride) {
                $hidetile = $this->is_override_allowed(
                    constant(customfield_manager::class . "::CUSTOM_FIELD_{$formatnameuppercase}_HIDETITLE")
                );
            }

            if ($hidetile) {
                $templatedata->initialsection->header->title = '';
            }

            if (!empty($templatedata->sections)) {
                $templatedata->sections[0]->collapsemenu = true;
            }
        }
    }
}
