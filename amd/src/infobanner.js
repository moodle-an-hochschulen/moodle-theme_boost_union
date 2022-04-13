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
 * Theme Boost Union - JS code for feature information banner
 *
 * @module     theme_boost_union/infobanner
 * @copyright  2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/str', 'core/modal_factory', 'core/modal_events', 'core/notification'],
    function($, str, ModalFactory, ModalEvents, Notification) {
        "use strict";

        /**
         * Initialising.
         *
         * @param {string} showconfirmationdialogue
         */
        function initInfoBanner(showconfirmationdialogue) {

            var stringsforInfoBannerJS = [
                {
                    key: 'confirmation',
                    component: 'theme_boost_union'
                }, {
                    key: 'closingperpetualinfobanner',
                    component: 'theme_boost_union'
                }, {
                    key: 'yes_close',
                    component: 'theme_boost_union'
                }
            ];

            // Load the strings and modal before clicking on the button so that the confirmation dialogue can pop up faster.
            var stringsPromise = str.get_strings(stringsforInfoBannerJS);
            var modalPromise = ModalFactory.create({type: ModalFactory.types.SAVE_CANCEL});

            // With this we store the dismissing of the info banner as a user preference to persist this decision.
            $('#themeboostunionperpinfobanner .close').click(function(event) {
                // Stop propagation to keep the info banner there until the decision in the confirmation dialogue has been made.
                event.stopPropagation();

                if (showconfirmationdialogue == '1') {
                    $.when(stringsPromise, modalPromise).then(function(strings, modal) {
                        modal.setTitle(strings[0]);
                        modal.setBody(strings[1]);
                        modal.setSaveButtonText(strings[2]);
                        // Saved clicked - the dismissing of the info banner is confirmed.
                        modal.getRoot().on(ModalEvents.save, function() {
                            M.util.set_user_preference('theme_boost_union_infobanner_dismissed', true);
                            // Now close the alert.
                            $('#themeboostunionperpinfobanner').alert('close');
                        });
                        modal.show();
                        return modal;
                    }).fail(Notification.exception);
                } else {
                    M.util.set_user_preference('theme_boost_union_infobanner_dismissed', true);
                    // Now close the alert.
                    $('#themeboostunionperpinfobanner').alert('close');
                }
            });
        }

        return {
            init: function(showconfirmationdialogue) {
                initInfoBanner(showconfirmationdialogue);
            }
        };
    });
