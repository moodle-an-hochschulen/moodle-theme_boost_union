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
 * Theme Boost Union - Weekly sections course format renderer
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\output;

use core_courseformat\output\local\content;
use theme_boost_union\util\section;

/**
 * Extending the renderer of the Weekly sections course format.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class format_weeks_renderer extends \format_weeks\output\renderer {
    /**
     * Render the course format content (i.e. the course main page).
     *
     * This method is detected and called by core_courseformat\output\section_renderer::render()
     * and applies Boost Union's section appearance settings to the exported template data
     * before the template is rendered.
     *
     * @param content $widget The course format content renderable.
     * @return string The rendered HTML.
     */
    public function render_content(content $widget): string {
        $data = $widget->export_for_template($this);
        section::apply_appearance($data, $this->page);
        return $this->render_from_template($widget->get_template_name($this), $data);
    }
}
