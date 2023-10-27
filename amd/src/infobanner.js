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
 * Theme Boost Union - JS code infobanner
 *
 * @module     theme_boost_union/infobanner
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @copyright  based on code from theme_boost_campus by Kathrin Osswald.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function($) {
    "use strict";

    /**
     * Initialising.
     */
    function initInfoBanner() {
        // Register click handler for all close buttons.
        $('.themeboostunioninfobanner .close').on('click', function() {
            // As soon as the button was clicked, get the number of the info banner.
            var infobannerno = $(this).attr('data-infobanner-no');

            // And store the dismissing of the info banner as a user preference to persist this decision.
            require(['core_user/repository'], function(UserRepository) {
                UserRepository.setUserPreference('theme_boost_union_infobanner' + infobannerno + '_dismissed', true);
            });
        });
    }

    return {
        init: function() {
            initInfoBanner();
        }
    };
});
