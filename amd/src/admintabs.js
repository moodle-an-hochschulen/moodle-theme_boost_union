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
 * Theme Boost Union - JS code current admin tab selector
 *
 * @module     theme_boost_union/admintab
 * @copyright  2023 Mario Wehr <m.wehr@fh-kaernten.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function($) {
    "use strict";

    /**
     * Initialising.
     */
    function initAdminTabs() {
        // Wait for Bootstrap to be finally loaded.
        // As soon it's loaded, the anonymous function's code is executed.
        whenBootstrapIsAvailable(() => {
            // The sessionStorage key.
            const sessionKey = 'theme_boost_union_active_admin_tab';

            // Register an event listener on all boost union tabs.
            $('a[href^="#theme_boost_union_"]').parent().on("shown.bs.tab", function() {
                // Store the active tab in the session.
                window.sessionStorage.setItem(sessionKey, $(this).children('a').eq(0).attr('href'));
            });

            // Get the active tab from the session.
            const activeTab = window.sessionStorage.getItem(sessionKey);

            // If an active tab was stored in the session.
            if (activeTab) {
                // Switch to the active tab from the session.
                $('a[href="' + activeTab + '"]').tab('show');
            }
        });
    }

    /**
     * Wait for Bootstrap to be finally loaded.
     *
     * @param {function} callback
     */
    function whenBootstrapIsAvailable(callback) {
        window.setTimeout(() => {
            if (typeof $().tab == 'function') {
                callback();
            } else {
                whenBootstrapIsAvailable(callback);
            }
        }, 100);
    }

    return {
        init: function() {
            initAdminTabs();
        }
    };
});
