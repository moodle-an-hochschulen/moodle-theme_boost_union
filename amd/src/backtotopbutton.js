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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/str', 'core/notification'], function($, str, Notification) {
    "use strict";

    // Remember if the back to top button is shown currently.
    let buttonShown = false;

    /**
     * Initializing.
     */
    function initBackToTop() {
        // Define the scroll distance after which the button will be shown.
        const scrolldistance = 220;

        // Get the string backtotop from language file.
        let stringsPromise = str.get_string('backtotop', 'theme_boost_union');

        // If the string has arrived, add backtotop button to DOM and add scroll and click handlers.
        $.when(stringsPromise).then(function(string) {
            // Add a fontawesome icon to the footer as the back to top button.
            $('#boost-union-footer-buttons').prepend('<button id="back-to-top" ' +
                    'class="btn btn-icon bg-secondary icon-no-margin d-print-none"' +
                    'aria-label="' + string + '">' +
                    '<i aria-hidden="true" class="fa fa-chevron-up fa-fw "></i></button>');

            // Check directly if the button should be shown.
            // This is helpful for all cases when this code here runs _after_ the page has been scrolled,
            // especially by the scrollspy feature or by a simple browser page reload.
            if ($(window).scrollTop() > scrolldistance) {
                checkAndShow();
            } else {
                checkAndHide();
            }

            // This function fades the button in when the page is scrolled down or fades it out
            // if the user is at the top of the page again.
            $(window).on('scroll', function() {
                if ($(window).scrollTop() > scrolldistance) {
                    checkAndShow();
                } else {
                    checkAndHide();
                }
            });

            // This function scrolls the page to top with a duration of 500ms.
            $('#back-to-top').on('click', function(event) {
                event.preventDefault();
                $('html, body').animate({scrollTop: 0}, 500);
                $('#back-to-top').blur();
            });

            return true;
        }).fail(Notification.exception);
    }

    /**
     * Helper function to handle the button visibility when the page is scrolling up.
     */
    function checkAndHide() {
        // Check if the button is still shown.
        if (buttonShown === true) {
            // Fade it out and remember the status in the end.
            // To be precise, the faceOut() function will be called multiple times as buttonShown is not set until the button is
            // really faded out. However, as soon as it is faded out, it won't be called until the button is shown again.
            $('#back-to-top').fadeOut(100, function() {
                buttonShown = false;
            });
        }
    }

    /**
     * Helper function to handle the button visibility when the page is scrolling down.
     */
    function checkAndShow() {
        // Check if the button is not yet shown.
        if (buttonShown === false) {
            // Fade it in and remember the status in the end.
            $('#back-to-top').fadeIn(300, function() {
                buttonShown = true;
            });
        }
    }

    return {
        init: function() {
            initBackToTop();
        }
    };
});
