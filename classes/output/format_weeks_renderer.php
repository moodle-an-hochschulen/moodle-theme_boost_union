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

use format_topics\output\renderer;
use theme_boost_union\courseformat\format_renderer_trait;

class format_weeks_renderer extends renderer {
    use format_renderer_trait;

    public function render_content($widget): bool|string {
        $templatedata = $widget->export_for_template($this);
        if (!$templatedata->initialsection->iscoursedisplaymultipage) {
            $this->setup_courseformat_additions('weekly', $templatedata);
        }

        return $this->render_from_template(
            $widget->get_template_name($this),
            $templatedata
        );
    }
}
