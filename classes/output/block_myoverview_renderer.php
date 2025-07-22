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
 * Overrides myoverview block renderer.
 *
 * @package   theme_boost_union
 * @copyright Copyright (c) 2024 Open LMS (https://www.openlms.net)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\output;

use block_myoverview\output\main as main;
use html_writer;
use stdClass;

defined('MOODLE_INTERNAL') || die();

class block_myoverview_renderer extends \block_myoverview\output\renderer {

    /**
     * Return the main content for the block overview.
     *
     * @param main $main The main renderable
     * @return string HTML string
     */
    public function render_main(main $main) {
        global $USER;

        if (!count(enrol_get_all_users_courses($USER->id, true))) {
            return $this->render_from_template(
                'block_myoverview/zero-state',
                $main->export_for_zero_state_template($this)
            );
        }

        $data = $main->export_for_template($this);

        $courses = enrol_get_my_courses('enddate', 'fullname ASC, id DESC');
        $coursesyears = [];
        foreach ($courses as $course) {
            if (!empty($course->enddate)) {
                $endyear = userdate($course->enddate, '%Y');
                $yearlink = html_writer::tag('a', $endyear,[
                    'class' => 'dropdown-item',
                    'href' => '#',
                    'data-filter' => 'year',
                    'data-value' => $endyear
                ]);
                $yearitem = new stdClass();
                $yearitem->$endyear = html_writer::tag('li', $yearlink);
                $coursesyears[$endyear] = $yearitem;
            }
            ksort($coursesyears);
        }
        if (!empty($coursesyears)) {
            $allyearslink = html_writer::tag('a', get_string('courseoverviewallyears', 'theme_boost_union'),[
                'class' => 'dropdown-item',
                'href' => '#',
                'data-filter' => 'year',
                'data-value' => 'all'
            ]);
            $yearslist = $allyearslink;
            foreach ($coursesyears as $year => $yearlistitem) {
                $yearslist .= $yearlistitem->$year;
            }
            $data['years'] = $yearslist;
        }

        return $this->render_from_template('block_myoverview/main', $data);
    }
}
