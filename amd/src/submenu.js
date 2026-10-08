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
 * Theme Boost Union - JS code to initialize and handle events of the sub menus in smart menu.
 *
 * This AMD module is copied and modified from lib/amd/src/usermenu.js.
 *
 * @module     theme_boost_union/submenu
 * @copyright  2024 bdecent GmbH <https://bdecent.de>
 * @copyright  based on code from core/usermenu by Mihail Geshoski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {Carousel} from 'bootstrap';
import {space, enter} from 'core/key_codes';

/**
 * Smartmenu submenu constants.
 */
const Selectors = {
    smartMenuCarousel: '[data-bs-toggle="smartmenu-carousel"]',
    smartMenuCarouselClass: '.theme-boost-union-smartmenu-carousel',
    smartMenuCarouselItem: '[data-bs-toggle="smartmenu-carousel"] .carousel-item',
    smartMenuCarouselItemActive: '[data-bs-toggle="smartmenu-carousel"] .carousel-item.active',
    smartMenuCarouselNavigationLink: '[data-bs-toggle="smartmenu-carousel"] .carousel-navigation-link',
    attr: {
        smartMenuCarouselTargetAttr: 'data-carousel-target-id',
        smartMenuCarouselNavigationClass: 'carousel-navigation-link',
    },
};

/**
 * Register event listeners.
 *
 * @param {HTMLElement} smartMenu
 */
const registerEventListeners = (smartMenu) => {

    const smartMenuCarousel = smartMenu.querySelector(Selectors.smartMenuCarousel);

    // Handle click events in the smart menu.
    smartMenu.addEventListener('click', (e) => {

        // Handle click event on the carousel navigation (control) links in the smart menu.
        if (e.target.matches(Selectors.smartMenuCarouselNavigationLink)) {
            e.preventDefault();
            carouselManagement(e);
        }

    }, true);

    smartMenu.addEventListener('keydown', e => {
        // Handle keydown event on the carousel navigation (control) links in the smart menu.
        if ((e.keyCode === space ||
            e.keyCode === enter) &&
            e.target.matches(Selectors.smartMenuCarouselNavigationLink)) {
            e.preventDefault();
            carouselManagement(e);
        }
    }, true);

    /**
     * We do the same actions here even if the caller was a click or button press.
     *
     * @param {Event} e The triggering element and key presses etc.
     */
    const carouselManagement = e => {

        // By default the smart menu dropdown element closes on a click event. This behaviour is not desirable
        // as we need to be able to navigate through the carousel items (submenus of the smart menu) within the
        // smart menu. Therefore, we need to prevent the propagation of this event and then manually call the
        // carousel transition.
        e.stopPropagation();
        e.preventDefault();

        // The id of the targeted carousel item.
        const targetedCarouselItemId = e.target.dataset.carouselTargetId;
        const targetedCarouselItem = smartMenu.querySelector('#' + targetedCarouselItemId);
        // Get the position (index) of the targeted carousel item within the parent container element.
        const index = Array.from(targetedCarouselItem.parentNode.children).indexOf(targetedCarouselItem);
        // Navigate to the targeted carousel item.
        Carousel.getOrCreateInstance(smartMenuCarousel).to(index);

    };

    // Handle the 'hide.bs.dropdown' event (Fired when the dropdown menu is being closed).
    smartMenu.addEventListener('hide.bs.dropdown', () => {
        // Reset the state once the smart menu dropdown is closed and return back to the first (main) carousel item
        // if necessary.
        Carousel.getOrCreateInstance(smartMenuCarousel).to(0);

    });

    // Handle the 'slid.bs.carousel' event (Fired when the carousel has completed its slide transition).
    smartMenuCarousel?.addEventListener('slid.bs.carousel', () => {
        const activeCarouselItem = smartMenu.querySelector(Selectors.smartMenuCarouselItemActive);
        // Set the focus on the newly activated carousel item.
        if (activeCarouselItem !== null) {
            activeCarouselItem.focus();
        }
    });
};

/**
 * Initialize the sub menus.
 */
const init = () => {

    const smartMenus = document.querySelectorAll(Selectors.smartMenuCarouselClass);

    // Registers event listeners to enable the submenu items carousel.
    if (smartMenus !== undefined && smartMenus.length !== null) {
        smartMenus.forEach((e) => registerEventListeners(e));
    }
};

export default {
    init: init,
};
