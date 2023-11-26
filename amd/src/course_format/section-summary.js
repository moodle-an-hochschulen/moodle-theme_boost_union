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

const MOVE_DIRECTION = {
    MOVE_IN: 'move-in',
    MOVE_OUT: 'move-out'
};

// 1 Rem [px] - https://stackoverflow.com/questions/36532307/rem-px-in-javascript
const OneRem = parseFloat(getComputedStyle(document.documentElement).fontSize); // Used for mimic parent mb-3, .my-3 .

/**
 * Calculate height of hidden summary item
 * @param {HTMLElement} collapse
 * @param {HTMLElement} courseDescItem
 * @returns {number} height
 */
function calculateHeight(collapse, courseDescItem) {
    collapse.classList.add('calc-height');
    let height = courseDescItem.scrollHeight + OneRem;
    collapse.classList.remove('calc-height');
    return height;
}

/**
 * @param {HTMLElement} contentCollapse
 * @param {null|number} height
 * @param {null|number} minHeight
 */
function setCollapseStyles(contentCollapse, height, minHeight = null) {
    contentCollapse.style.overflow = 'hidden';
    if (height) {
        contentCollapse.classList.remove("collapse");
        contentCollapse.style.height = height + 'px';
    }
    if (minHeight) {
        contentCollapse.style.minHeight = height + 'px';
    }
}

/**
 * @param {HTMLElement} courseDescItem
 * @param {string} from
 * @param {string} to
 */
function indentSummary(courseDescItem, from, to) {
    courseDescItem.classList.remove(from);
    courseDescItem.classList.add(to);
}

/**
 * Responsible for moving summary information to inner <-> outer place and limit collapse to summary content height.
 */
class SummaryItemController {
    constructor(sectionCollapse, sectionSummary, sectionContent) {
        this.contentCollapse = sectionCollapse;
        this.sectionContent = sectionContent;
        this.sectionSummary = sectionSummary;
        this.height = 0;

        // Check if we start collapsed.
        if (!this.contentCollapse.classList.contains("show")) {
            this.height = calculateHeight(this.contentCollapse, this.sectionSummary);
            this.sectionContent.setAttribute('style', 'display:none !important');
            setCollapseStyles(this.contentCollapse, this.height);
            indentSummary(this.sectionSummary, MOVE_DIRECTION.MOVE_OUT, MOVE_DIRECTION.MOVE_IN);
        }

        $(this.contentCollapse).on('hide.bs.collapse', () => {
            this.height = this.sectionSummary.offsetHeight + OneRem;
            indentSummary(this.sectionSummary, MOVE_DIRECTION.MOVE_OUT, MOVE_DIRECTION.MOVE_IN);
            this.contentCollapse.style.minHeight = this.height + 'px';
        });

        $(this.contentCollapse).on('hidden.bs.collapse', () => {
            this.sectionContent.setAttribute('style', 'display:none !important');
            setCollapseStyles(this.contentCollapse, this.height);
        });

        $(this.contentCollapse).on('shown.bs.collapse', () => {
            this.height = this.sectionSummary.offsetHeight + OneRem;
            setCollapseStyles(this.contentCollapse, null, this.height);
        });

        $(this.contentCollapse).on('show.bs.collapse', () => {
            this.sectionContent.style.removeProperty('display');
            indentSummary(this.sectionSummary, MOVE_DIRECTION.MOVE_IN, MOVE_DIRECTION.MOVE_OUT);
        });

    }
}

export const init = () => {
    const sectionList = document.querySelectorAll('ul.topics li.section.course-section, ul.weeks li.section.course-section');
    const summaryCtrlList = new Array(sectionList.length);
    for (let idx = 0; idx < sectionList.length; idx++) {
        const section = sectionList[idx];
        const sectionCollapse = section.querySelector('.course-content-item-content');
        if (sectionCollapse) {
            const sectionSummary = sectionCollapse.querySelector('.course-description-item.summarytext');
            // Only create SummaryItemController if summary content is present.
            if (sectionSummary) {
                summaryCtrlList[idx] = new SummaryItemController(
                    sectionCollapse,
                    sectionSummary,
                    sectionCollapse.querySelector('ul.section')
                );
            }
        }
    }
};
