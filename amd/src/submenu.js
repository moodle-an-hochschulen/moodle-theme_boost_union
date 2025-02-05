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

import $ from 'jquery';
import {space, enter} from 'core/key_codes';

/**
 * Smartmenu submenu constants.
 */
const Selectors = {
    smartMenuCarousel: '[data-toggle="smartmenu-carousel"]',
    smartMenuCarouselClass: '.theme-boost-union-smartmenu-carousel',
    smartMenuCarouselItem: '[data-toggle="smartmenu-carousel"] .carousel-item',
    smartMenuCarouselItemActive: '[data-toggle="smartmenu-carousel"] .carousel-item.active',
    smartMenuCarouselNavigationLink: '[data-toggle="smartmenu-carousel"] .carousel-navigation-link',
    smartMenuDropDownItems: 'ul.dropdown-menu li.nav-item',
    dropDownMenu: '.dropdown-menu',
    roleMenu: '[role="menu"]',
    attr: {
        smartMenuCarouselTargetAttr: 'data-carousel-target-id',
        smartMenuCarouselNavigationClass: 'carousel-navigation-link',
    },
    region: {
        dropDown: '[data-region="moredropdown"]'
    }
};

/**
 * Register event listeners.
 *
 * @param {HTMLElement} smartMenu
 */
const registerEventListeners = (smartMenu) => {

    // Handle click events in the smart menu.
    smartMenu.addEventListener('click', (e) => {

        // Handle click event on the carousel navigation (control) links in the smart menu.
        if (e.target.matches(Selectors.smartMenuCarouselNavigationLink)) {
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
        // The id of the targeted carousel item.
        const targetedCarouselItemId = e.target.dataset.carouselTargetId;
        const targetedCarouselItem = smartMenu.querySelector('#' + targetedCarouselItemId);
        // Get the position (index) of the targeted carousel item within the parent container element.
        const index = Array.from(targetedCarouselItem.parentNode.children).indexOf(targetedCarouselItem);
        // Navigate to the targeted carousel item.
        $(smartMenu.querySelector(Selectors.smartMenuCarousel)).carousel(index);
    };

    // Handle the 'hide.bs.dropdown' event (Fired when the dropdown menu is being closed).
    $(Selectors.smartMenu).on('hide.bs.dropdown', () => {
        // Reset the state once the smart menu dropdown is closed and return back to the first (main) carousel item
        // if necessary.
        $(smartMenu.querySelector(Selectors.smartMenuCarousel)).carousel(0);
    });

    // Handle the 'slid.bs.carousel' event (Fired when the carousel has completed its slide transition).
    $(Selectors.smartMenuCarousel).on('slid.bs.carousel', () => {
        const activeCarouselItem = smartMenu.querySelector(Selectors.smartMenuCarouselItemActive);
        // Set the focus on the newly activated carousel item.
        if (activeCarouselItem !== null) {
            activeCarouselItem.focus();
        }
    });
};

/**
 * Sets up the visibility and positions of card menus inside the moremenu.
 */
const moreMenuCardItem = () => {

    window.onresize = () => initMoreMenuCardItem();

    /**
     * Fetches the primary and menubar navigations moremenu and registers the card menu update.
     */
    const initMoreMenuCardItem = () => {
        // Get the primary navigation more menu and initialize card menu update.
        var primaryNav = document.querySelector('.primary-navigation ul.more-nav .dropdownmoremenu');
        registerMoreMenuCardItem(primaryNav);

        // Get the menubar more menu and initialize card menu update.
        var menuBar = document.querySelector('nav.menubar ul.more-nav .dropdownmoremenu');
        registerMoreMenuCardItem(menuBar);
    };

    /**
     * Registers the click event on the moremenu to update the menus width and position
     * and the visibility of card menus inside the more menu.
     *
     * @param {HTMLElement} moreMenu The more menu element.
     * @returns {void}
     */
    const registerMoreMenuCardItem = (moreMenu) => {

        // Fetch the list of moved menu items from ul.dropdown-menu li.nav-items.
        var items = moreMenu.querySelectorAll(Selectors.smartMenuDropDownItems);

        if (items.length <= 0) {
            return;
        }

        // Close the dropdown menu of the more menu to prevent faulty clicks on elements moved outside
        // of the more menu during resizing.
        var subMenuDropDownMenu = moreMenu.querySelectorAll(Selectors.dropDownMenu + ' ' + Selectors.dropDownMenu);
        if (subMenuDropDownMenu !== null) {
            Array.from(subMenuDropDownMenu).forEach((e) => e.classList.remove('show'));
        }

        // Hide the opened card menus when the carousel item is clicked.
        moreMenu.removeEventListener('click', hideCardMenus, true);
        moreMenu.addEventListener('click', hideCardMenus, true);

        // Remove the width of card menus moved to outside from more menu.
        if (moreMenu.parentNode.querySelectorAll('.dropdown.card-dropdown')) {
            moreMenu.parentNode.querySelectorAll('.dropdown.card-dropdown').forEach((e) => {
                var cardDropDown = e.querySelector(Selectors.dropDownMenu);
                if (cardDropDown !== null) {
                    cardDropDown.style.removeProperty('width');
                    cardDropDown.style.removeProperty('left');
                    cardDropDown.style.removeProperty('right');
                }
            });
        }
        // Parent moremenu.
        var parentMenu = moreMenu.parentNode;
        // Hide all opened card menus on dropdown shown.
        $(parentMenu).on('shown.bs.dropdown', hideOpenMenus);
        $(parentMenu).on('hidden.bs.dropdown', hideOpenMenus);
    };

    /**
     * Hide all the opened card menus on dropdown shown.
     * @param { Event } e The click event.
     */
    const hideOpenMenus = (e) => {
        if (e.target.matches('.dropdownmoremenu')) {
            var subMenuDropDownMenu = e.currentTarget.querySelectorAll(Selectors.dropDownMenu + ' ' + Selectors.dropDownMenu);
            if (subMenuDropDownMenu !== null) {
                Array.from(subMenuDropDownMenu).forEach((e) => e.classList.remove('show'));
            }
        }
    };

    /**
     * Hides the opened card menus when a carousel item is clicked. Update the current active card menus position and width.
     *
     * @param { Event } e The click event.
     * @returns { void}
     */
    const hideCardMenus = e => {
        var moreMenu = e.target.closest(Selectors.region.dropDown);
        if (moreMenu && moreMenu.querySelectorAll(Selectors.dropDownMenu)) {
            moreMenu.querySelectorAll(Selectors.dropDownMenu).forEach((dropdownmenu) => {

                // Hide the nav items other than this target and the carousel navigation links.
                if (!e.target.isEqualNode(dropdownmenu.previousElementSibling)
                    && !e.target.matches(Selectors.smartMenuCarouselNavigationLink)
                    && e.target.matches('.dropdown-toggle.dropdown-item')) {
                    dropdownmenu.classList.remove('show');
                }
                // If the target is a card dropdown link, then update the width of this card dropdown.
                if (e.target.isEqualNode(dropdownmenu.previousElementSibling)
                    && e.target.parentNode.matches('.dropdown.card-dropdown')) {
                    updatePosition(e);
                }
            });
        }
    };

    /**
     * Update the position and width of the card menus inside the moremenu.
     *
     * @param {event} e
     */
    const updatePosition = e => {

        const innerMenu = e.target.parentNode.querySelector(Selectors.roleMenu);

        if (innerMenu) {
            // Calculate and adjust the position of the card menu based on the dropdown menu's position.
            var dropDown = innerMenu.parentNode.closest(Selectors.dropDownMenu);
            var rect = dropDown.getBoundingClientRect();
            var right = document.scrollingElement.clientWidth - rect.right;

            // Use the left section of the moremenu dropdown.
            if (rect.left >= right) {
                innerMenu.style.width = rect.left + 'px';
                innerMenu.style.left = 0;
            } else {
                // Use the right side of the moremenu dropdown.
                innerMenu.style.width = right + 'px';
                innerMenu.style.left = 'inherit';
                innerMenu.style.right = 0;
            }
        }
    };
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

    // Calculate and setup the card menus width and positions inside the more menu.
    moreMenuCardItem();
};

export default {
    init: init,
};
