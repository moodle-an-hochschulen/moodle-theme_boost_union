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
 * Theme Boost Union - JS for smart menu to realize the third level submenu support.
 *
 * @module     theme_boost_union/smartmenu
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(["jquery", "core/moremenu"], function($) {
    /**
     * Implement the second level of submenu support.
     * Find the submenus inside the dropdown, add an event listener for click event which - on the click - shows the submenu list.
     */
    const addSubmenu = () => {
        // Fetch the list of submenus from moremenu.
        var submenu = document.querySelectorAll('nav.moremenu .dropdown-submenu');
        if (submenu !== null) {
            submenu.forEach((item) => {
                // Add event listener to show the submenu on click.
                item.addEventListener('click', (e) => {
                    var target = e.currentTarget;
                    // Hide the shown menu.
                    hideSubmenus(target);
                    target.classList.toggle('show');
                    // Prevent hiding the parent menu.
                    e.stopPropagation();
                });
            });
        }

        // Hide the submenus when its parent dropdown is hidden.
        $(document).on('hidden.bs.dropdown', e => {
            var target = e.relatedTarget.parentNode;
            var submenus = target.querySelectorAll('.dropdown-submenu.show');
            if (submenus !== null) {
                submenus.forEach((e) => e.classList.remove('show'));
            }
        });

        // Provide the third level menu support inside the more menu.
        // StopPropagation used in the toggledropdown method on Moremenu.js, It prevents the opening of the third level menus.
        // Used the document delegation method to fetch the click on moremenu and submenu.
        document.addEventListener('click', (e) => {
            var dropdown = e.target.closest('.dropdownmoremenu');
            var subMenu = e.target.closest('.dropdown-submenu');
            if (dropdown && subMenu !== null) {
                // Hide the previously opend submenus. before open the new one.
                dropdown.querySelectorAll('.dropdown-submenu.show').forEach((menu) => {
                    menu.classList.remove('show');
                });
                subMenu.classList.toggle('show');
            }

            // Hide the opened menus before open the other menus.
            var dropdownMenu = e.target.parentNode.classList.contains('dropdown');
            if (dropdown && dropdownMenu) {
                dropdown.querySelectorAll('.dropdown-menu.show').forEach((menu) => {
                    // Hide the opened menus in more menu.
                    if (menu != e.target.closest('.dropdown-menu')) {
                        menu.classList.remove('show');
                    }
                });
            }

        }, true);

        // Prevent the closing of dropdown during the click on help icon.
        var helpIcon = document.querySelectorAll('.moremenu .dropdown .menu-helpicon');
        if (helpIcon !== null) {
            helpIcon.forEach((icon) => {
                icon.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            });
        }
    };

    /**
     * Hide visible submenus before display new submenu.
     *
     * @param {Selector} target
     */
    const hideSubmenus = (target) => {
        var visibleMenu = document.querySelectorAll('nav.moremenu .dropdown-submenu.show');
        if (visibleMenu !== null) {
            visibleMenu.forEach((el) => {
                if (el != target) {
                    el.classList.remove('show');
                }
            });
        }
    };

    /**
     * Make the no wrapped card menus scroll using swipe or drag.
     */
    const cardScroll = () => {
        var cards = document.querySelectorAll('.card-dropdown.card-overflow-no-wrap');
        if (cards !== null) {
            var scrollStart; // Verify the mouse is clicked and still in click not released.
            var scrollMoved; // Prevent the click on scrolling.
            let startPos, scrollPos;

            cards.forEach((card) => {
                var scrollElement = card.querySelector('.dropdown-menu');

                scrollElement.addEventListener('mousedown', (e) => {
                    scrollStart = true;
                    var target = e.currentTarget.querySelector('.card-block-wrapper');
                    startPos = e.pageX;
                    scrollPos = target.scrollLeft;
                });

                scrollElement.addEventListener('mousemove', (e) => {
                    e.preventDefault();
                    if (!scrollStart) {
                        return;
                    }
                    scrollMoved = true;
                    var target = e.currentTarget.querySelector('.card-block-wrapper');
                    const scroll = e.pageX - startPos;
                    target.scrollLeft = scrollPos - scroll;
                });

                scrollElement.addEventListener('click', (e) => {
                    if (scrollMoved) {
                        e.preventDefault();
                        scrollMoved = false;
                    }
                    e.stopPropagation();
                });
                scrollElement.addEventListener('mouseleave', () => {
                    scrollStart = false;
                    scrollMoved = false;
                });
                scrollElement.addEventListener('mouseup', () => {
                    scrollStart = false;
                });
            });
        }
    };

    /**
     * Move the menubar and primary navigation menu items from more menu.
     */
    const autoCollapse = () => {
        var primaryNav = document.querySelector('.primary-navigation ul.more-nav');
        moveOutMoreMenu(primaryNav);

        var menuBar = document.querySelector('nav.menubar ul.more-nav');
        moveOutMoreMenu(menuBar);
    };

    /**
     * Move the items from more menu, items which is set to force outside more menu.
     * Remove those items from more menu and insert the menu before the last normal item.
     * Find the length and children's length to insert the out menus in that positions.
     * Rerun the more menu it will more the other normal menus into more menu to fix the alignmenu issue.
     *
     * @param {HTMLElement} navMenu The navbar container.
     */
    const moveOutMoreMenu = (navMenu) => {

        if (navMenu === null) {
            return;
        }

        var outMenus = navMenu.querySelectorAll('.dropdownmoremenu .force-menu-out');
        var menuslist = [];

        if (outMenus === null) {
            return;
        }

        outMenus.forEach((menu) => {
            menu.querySelector('a').classList.remove('dropdown-item');
            menu.querySelector('a').classList.add('nav-link');

            menuslist.push(menu);
            menu.parentNode.removeChild(menu);
        });
        // Find the length and children's length to insert the out menus in that positions.
        var length = menuslist.length;
        var navLength = navMenu.children.length - 1; // Remove more menu.
        var newPosition = navLength - length || 0;
        // Insert the stored menus before the more menu.
        menuslist.forEach((menu) => navMenu.insertBefore(menu, navMenu.children[newPosition]));
        window.dispatchEvent(new Event('resize')); // Dispatch the resize event to create more menu.
    };

    return {
        init: () => {
            addSubmenu();
            cardScroll();
            autoCollapse();
        }
    };
});
