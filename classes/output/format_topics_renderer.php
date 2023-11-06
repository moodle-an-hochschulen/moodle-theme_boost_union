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
namespace theme_boost_union\output;

use core_course\customfield\course_handler;
use core_customfield\data_controller;
use format_topics\output\renderer;
use theme_boost_union\courseformat\customfield_manager;

class format_topics_renderer extends renderer {
    /** @var data_controller[]|null $tasks */
    private ?array $datactrllist = null;

    public function render_content($widget): bool|string {
        // Always show section summary.
        $alwaysshowsummary =
            (get_config('theme_boost_union', 'courseformattopicsalwaysshowsectionsummary')
                == THEME_BOOST_UNION_SETTING_SELECT_YES);
        $alwaysshowsectionsummaryoverride =
            (get_config('theme_boost_union', 'courseformattopicsalwaysshowsectionsummaryoverride')
                == THEME_BOOST_UNION_SETTING_SELECT_YES);

        // ... if per course override is allowed.
        if ($alwaysshowsectionsummaryoverride) {
            $alwaysshowsummary = $this->is_override_allowed(customfield_manager::CUSTOM_FIELD_ALWAYSSHOWSUMMARY);
        }

        // Always show initial section.
        $alwaysshowinitalsection =
            (get_config('theme_boost_union', 'courseformattopicsalwaysshowinitialsection')
                == THEME_BOOST_UNION_SETTING_SELECT_YES);

        $alwaysshowinitialsectionoverride =
            (get_config('theme_boost_union', 'courseformattopicsalwaysshowinitialsectionoverride')
                == THEME_BOOST_UNION_SETTING_SELECT_YES);

        // ... if per course override is allowed.
        if ($alwaysshowinitialsectionoverride) {
            $alwaysshowinitalsection = $this->is_override_allowed(
                customfield_manager::CUSTOM_FIELD_ALWAYSSHOWINITIALSECTION
            );
        }

        // Check if any course format settings are enabled.
        if ($alwaysshowsummary || $alwaysshowinitalsection) {
            // Adding additional information to template data.
            $templatedata = $widget->export_for_template($this);
            $templatedata->alwaysshowsummary = $alwaysshowsummary;
            if ($alwaysshowinitalsection) {
                $templatedata->initialsection->alwaysshowinitialsection = true;
                $templatedata->initialsection->contentcollapsed = false;
                $templatedata->initialsection->collapsemenu = false;
                if (!empty($templatedata->sections)) {
                    $templatedata->sections[0]->collapsemenu = true;
                }
            }
            // Render our own modified content template.
            return $this->render_from_template(
                'theme_boost_union/theme_boost/core_courseformat/local/content',
                $templatedata
            );
        } else {
            // Render core template.
            return $this->render_from_template(
                $widget->get_template_name($this),
                $widget->export_for_template($this)
            );
        }
    }

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
        // Loop through data controllers amd search for our field keys.
        foreach ($this->datactrllist as $datactrl) {
            if ($datactrl->get_field()->get('shortname') == $fieldkey) {
                return $datactrl->get_value();
            }
        }
        return false;
    }
}
