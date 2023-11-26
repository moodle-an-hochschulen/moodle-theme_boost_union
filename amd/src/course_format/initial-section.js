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
 * Theme Boost Union - JS for topics/weekly course format addition to fix error in boost collapse toggler because of missing [data-toggle=collapse]
 * when initial section is in always view state.
 *
 * @module     theme_boost_union/section-summary
 * @copyright  2023 Mario Wehr <m.wehr@fh-kaernten.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

export const init = () => {
    const initalSection = document.querySelector('ul.topics li.section.course-section, ul.weeks li.section.course-section');
    if (initalSection) {
        // Create dummy item with [data-toggle="collapse"].
        initalSection.appendChild(document.createElement('div')).setAttribute("data-toggle", "collapse");
    }
};
