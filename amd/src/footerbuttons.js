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
 * Theme Boost Union - JS code for footer buttons
 *
 * @module     theme_boost_union/footerbuttons
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function($) {
    "use strict";

    /**
     * Initializing.
     */
    function initFooterButtons() {
        // This will check if there is a communication button shown on the page already.
        // If yes, it will add a class to the body tag which will be later used to align the Boost Union footer buttons
        // with the communications button.
        // This is necessary as the communications button would otherwise be overlaid by the Boost Union footer buttons.
        if ($('#page-footer .btn-footer-communication').length) {
            $('body').addClass('theme-boost-union-commincourse');
        }
    }

    return {
        init: function() {
            initFooterButtons();
        }
    };
});
