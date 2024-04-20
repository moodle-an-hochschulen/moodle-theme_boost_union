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
 * Theme Boost Union - JS code off-canvas
 *
 * @module     theme_boost_union/offcanvas
 * @copyright  2022 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'theme_boost/drawers', 'core/modal', 'core/notification'], function($, Drawers, Modal, Notification) {

    let modalBackdrop = null;

    /**
     * Helper function to get the OffCanvas backdrop and add an EventListener which closes
     * the drawer as soon as the user clicks on the backdrop.
     *
     * @returns {object}
     */
    const getDrawerBackdrop = function() {

        if (!modalBackdrop) {
            modalBackdrop = Modal.prototype.getBackdrop().then(backdrop => {
                backdrop.getAttachmentPoint().get(0).addEventListener('click', e => {
                    e.preventDefault();
                    var currentDrawer = Drawers.getDrawerInstanceForNode(
                        document.getElementById('theme_boost_union-drawers-offcanvas')
                    );
                    currentDrawer.closeDrawer(false);
                    backdrop.hide();
                });
                return backdrop;
            })
            .catch(Notification.exception);
        }
        return modalBackdrop;
    };

    /**
     * Used this listener to hide the off canvas drawer from the page.
     */
    function initOffcanvasBackdrop() {
        // Add EventListener for showing a drawer.
        document.addEventListener(Drawers.eventTypes.drawerShown, function(e) {
            // If the drawer which is shown is _not_ the offcanvas drawer, return.
            if (e.target.id != 'theme_boost_union-drawers-offcanvas') {
                return null;
            }

            // Get the drawer's backdrop and show it.
            getDrawerBackdrop().then(backdrop => {
                backdrop.show();
                return backdrop;
            })
            .catch(Notification.exception);

            return true;
        });

        // Add EventListener for hiding a drawer.
        document.addEventListener(Drawers.eventTypes.drawerHide, function(e) {
            // If the drawer which is hidden is _not_ the offcanvas drawer, return.
            if (e.target.id != 'theme_boost_union-drawers-offcanvas') {
                return null;
            }

            getDrawerBackdrop().then(backdrop => {
                backdrop.hide();
                return backdrop;
            })
            .catch(Notification.exception);

            return true;
        });
    }

    return {
        init: function() {
            initOffcanvasBackdrop();
        }
    };
});
