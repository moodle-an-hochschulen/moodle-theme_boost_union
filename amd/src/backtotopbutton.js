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
 * Theme Boost Union - JS code back to top button
 *
 * @module     theme_boost_union/backtotopbutton
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @copyright  based on code from theme_boost_campus by Kathrin Osswald.
 * @copyright  2024 University of Graz based on code/ideas of Mark Sharp <mark.sharp@solent.ac.uk>
 *             written 2022 for Solent University {@link https://www.solent.ac.uk}
 * @author     André Menrath <andre.menrath@uni-graz.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {getString} from 'core/str';

/**
 * Create the back-to-top button element.
 *
 * @param {string} backToTopString Aria text for the back-to-top button.
 */
const createBackToTopButton = (backToTopString) => {
    let button = document.createElement('button');
    button.id = 'back-to-top';
    button.className = 'btn btn-icon bg-secondary icon-no-margin d-print-none';
    button.setAttribute('aria-label', backToTopString);
    button.innerHTML = '<i aria-hidden="true" class="fa fa-chevron-up fa-fw"></i>';
    return button;
};

/**
 * Scroll event handler.
 * @param {element} button The back-to-top button.
 * @param {integer} scrollDistance Scroll distance from the top when to show the back-to-top button.
 */
const handleScroll = (button, scrollDistance) => {
    button.style.display = document.documentElement.scrollTop > scrollDistance ? 'block' : 'none';
};

/**
 * Scroll to top behavior.
 *
 * @param {event} event
 */
const scrollToTop = (event) => {
    event.preventDefault();
    window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth'
    });
};

/**
 * Throttle function to limit calls.
 *
 * @param   {function} func  The function to throttle.
 * @param   {number}   limit The time interval in milliseconds to throttle the function.
 *
 * @returns {function}       The throttled function.
 */
const throttle = (func, limit) => {
    let inThrottle = false;

    return (...args) => {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => {
                inThrottle = false;
            }, limit);
        }
    };
};

/**
 * Initial setup for the back to top button.
 */
export const init = async() => {
    // Configuration value when to start showing the back-to-top button.
    const scrollDistance = 220;

    // Aria text used for the back-to-top button.
    const backToTopString = await getString('backtotop', 'theme_boost_union');

    // Create and add the back-to-top button to the DOM.
    const footer = document.querySelector('#page-footer');
    const button = createBackToTopButton(backToTopString);
    footer.after(button);

    // Add event listeners that toggle the visibility and the behavior of the back-to-top button.
    const throttledScrollHandler = throttle(() => handleScroll(button, scrollDistance), 200);

    document.addEventListener('scroll', throttledScrollHandler);
    button.addEventListener('click', scrollToTop);
};
