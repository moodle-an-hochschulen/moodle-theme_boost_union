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
 * Theme Boost Union - JS for topics course format addition to realize always visible section summary.
 *
 * @module     theme_boost_union/section-summary
 * @copyright  2023 Mario Wehr <m.wehr@fh-kaernten.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

let summarCtrlList;

/**
 * Responsible for moving summary information to inner <-> outer place.
 */
class SummaryMoveController {
    constructor(section, innerSummaryLoc) {
        this.innerSummaryLoc = innerSummaryLoc;
        const contentItem = section.querySelector('.course-content-item-content');
        this.outerSummaryLoc = contentItem.nextElementSibling; // No need for query selector.

        // If section is initial closed, move the summary content to the outer summary location.
        if (!contentItem.classList.contains("show")) {
            $(this.outerSummaryLoc).hide();
            this.moveContent(this.innerSummaryLoc, this.outerSummaryLoc);
            $(this.outerSummaryLoc).fadeIn(500);
        }

        // Register for bootstrap collapse events.
        $(contentItem).on('hide.bs.collapse', () => {
            this.moveContent(this.innerSummaryLoc, this.outerSummaryLoc);
        });
        $(contentItem).on('show.bs.collapse', () => {
            this.moveContent(this.outerSummaryLoc, this.innerSummaryLoc);
        });
    }

    /**
     * Moves all content from one to another.
     * @param {HTMLElement} source
     * @param {HTMLElement} target
     */
    moveContent(source, target) {
        while (source.firstChild) {
            // While there are child nodes in the source div, move them to the target div.
            target.appendChild(source.firstChild);
        }
    }
}

export const init = () => {
    const sectionList = document.querySelectorAll('ul.topics li.section.course-section');
    summarCtrlList = new Array(sectionList.length);
    for (let idx = 0; idx < sectionList.length; idx++) {
        const section = sectionList[idx];
        const innerSummaryLoc = section.querySelector('.course-content-item-content .course-description-item.summarytext');
        // Only create SummaryMoveController if inner summary content is present.
        if (innerSummaryLoc) {
            summarCtrlList[idx] = new SummaryMoveController(section, innerSummaryLoc);
        }
    }
};
