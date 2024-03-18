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
 * Theme Boost Union - JS code scroll-spy
 *
 * @module     theme_boost_union/scrollspy
 * @copyright  2022 Josha Bartsch <bartsch@itc.rwth-aachen.de>
 * @copyright  based on code from theme_fordson by Chris Kenniburg.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Runs once at initial load, and once at editmode-switch toggle.
 * Incase of initial load, checks sessionStorage whether a position was set and jumps to the appropriate position.
 *
 * Incase of a click on the switch, iterates over central elements (selector .section.main), determines element
 * with minimal distance between pixel-toprow of view and pixel-toprow of the element.
 * Writes element ID + distance of view from element into session storage.
 *
 * Saving a reference point + relative distance grants leeway for varying page elements.
 * (See original implementation: https://raw.githubusercontent.com/dbnschools/moodle-theme_fordson/master/javascript/scrollspy.js)
 */
const initScrollSpy = () => {
    // Check if .section.main exist.
    if (document.querySelector('.section.main')) {
        // Unfortunately the editmode-switch carries no unique ID
        let editToggle = document.querySelector('form.editmode-switch-form');
        if (!editToggle) {
            // Do not continue when there is no edit toggle.
            return;
        }
        editToggle.addEventListener('click', () => {

            window.sessionStorage.setItem('theme_boost_union_scrollspy_edittoggled', true);

            let viewporttop = window.scrollY;
            let closest = null;
            let closestoffset = null;

            document.querySelectorAll('.section.main').forEach((node) => {
                let thisoffset = node.offsetTop;

                if (closest && closest.offsetTop) {
                    closestoffset = closest.offsetTop;
                }
                if (closest === null || Math.abs(thisoffset - viewporttop) < Math.abs(closestoffset - viewporttop)) {
                    closest = node;
                }
            });

            window.sessionStorage.setItem('theme_boost_union_scrollspy_closestid', closest.id);
            window.sessionStorage.setItem('theme_boost_union_scrollspy_closestdelta', viewporttop - closest.offsetTop);
        });
        let edittoggled = window.sessionStorage.getItem('theme_boost_union_scrollspy_edittoggled');
        if (edittoggled) {

            let closestid = window.sessionStorage.getItem('theme_boost_union_scrollspy_closestid');
            let closestdelta = window.sessionStorage.getItem('theme_boost_union_scrollspy_closestdelta');

            if (closestid && closestdelta) {
                let closest = document.getElementById(closestid);
                let y = closest.offsetTop + parseInt(closestdelta);

                window.scrollTo(0, y);
            }

            window.sessionStorage.removeItem('theme_boost_union_scrollspy_edittoggled');
            window.sessionStorage.removeItem('theme_boost_union_scrollspy_closestid');
            window.sessionStorage.removeItem('theme_boost_union_scrollspy_closestdelta');
        }
    }
};

/**
 * Ensures the passed function will be called after the DOM is ready/loaded:
 * Incase DOM is fully loaded when JS is called, call within next tick.
 * Otherwise sets an eventlistener for DOMEventLoaded
 *
 * @param {*} callback
 */
const docReady = (callback) => {
    if (document.readyState === "complete" || document.readyState === "interactive") {
        setTimeout(callback, 1);
    } else {
        document.addEventListener('DOMContentLoaded', callback);
    }
};

export const init = () => {
    docReady(initScrollSpy);
};
