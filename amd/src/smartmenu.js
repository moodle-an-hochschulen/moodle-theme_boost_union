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

    const Selectors = {
        dropDownMenu: "dropdownmoremenu",
        forceOut: "force-menu-out",
        navLink: "nav-link",
        dropDownItem: "dropdown-item",
        classes: {
            dropDownMenuList: ".dropdownmoremenu ul.dropdown-menu",
            forceOut: ".dropdownmoremenu .force-menu-out"
        }
    };

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
        if (primaryNav != undefined) {
            setOutMenuPositions(primaryNav); // Create a data flag to maintain the original position of the menus.
            moveOutMoreMenu(primaryNav);
        }


        var menuBar = document.querySelector('nav.menubar ul.more-nav');
        if (menuBar != undefined) {
            setOutMenuPositions(menuBar);
            moveOutMoreMenu(menuBar);
        }

        window.onresize = (e) => {
            // Verify the event is original by browser resize.
            if (e.isTrusted) {
                moveOutMoreMenu(primaryNav);
                moveOutMoreMenu(menuBar);
            }
        };
    };

    /**
     * Finds and sets the positions of all menus before moving them,
     * helping to maintain the positions of the menus after being moved out from the moremenu.
     *
     * @param {HTMLElement} navMenu The navbar container.
     */
    const setOutMenuPositions = (navMenu) => {

        if (navMenu === undefined || navMenu === null) {
            return;
        }

        // Find all menu items excluding the dropdownmoremenu class.
        var li = Array.from(navMenu.children).filter((e) => !e.classList.contains(Selectors.dropDownMenu));

        // Initialize the position variable.
        var position = 0;

        // Loop through each menu item and set its original position.
        li.forEach((menu) => {
            position = li.indexOf(menu);
            menu.dataset.orgposition = position; // Store the original position in the menu's dataset.
        });

        // Maintain the positions of the menus inside the moremenu from the last position of the outside menus.
        var moreMenu = navMenu.querySelector(Selectors.classes.dropDownMenuList);
        Array.from(moreMenu.children).forEach((menu) => {
            menu.dataset.orgposition = position++;
        });
    };

    /**
     * Rearranges the menus placed outside the more menu based on their original positions.
     *
     * @param {HTMLElement} navMenu The navbar container.
     */
    const reArrangeMenuOrgPositions = (navMenu) => {
        // Retrieve all menu items and sort them based on their original positions.
        var li = Array.from(navMenu.children).sort((a, b) => a.dataset.orgposition - b.dataset.orgposition);
        // Append the sorted menu items back to the navbar container.
        li.forEach((menu) => navMenu.appendChild(menu));
    };

    /**
     * Move the items from more menu, items which is set to force outside more menu.
     * Remove those items from more menu and insert the menu before the last normal item.
     * Find the length and children's length to insert the out menus in that positions.
     * Move the non forced more menu to moremenu to make the menu alignment.
     * Rerun the more menu it will more the other normal menus into more menu to fix the alignmenu issue.
     * After the menus are move out, rearrange menus to its original positions.
     *
     * @param {HTMLElement} navMenu The navbar container.
     */
    const moveOutMoreMenu = (navMenu) => {

        if (navMenu === null) {
            return;
        }

        // Filter the available menus to move inside of more menu.
        var li = Array.from(navMenu.children).reverse().filter(
            (e) => !e.classList.contains(Selectors.forceOut) && !e.classList.contains(Selectors.dropDownMenu));

        // Alternate menus are not available for move to moremenu, stop make the menus move to outside.
        if (li.length < 1) {
            return;
        }

        var outMenus = navMenu.querySelectorAll(Selectors.classes.forceOut);
        var menuslist = [];

        if (outMenus === null) {
            return;
        }

        outMenus.forEach((menu) => {
            menu.querySelector('a').classList.remove(Selectors.dropDownItem);
            menu.querySelector('a').classList.add(Selectors.navLink);

            menuslist.push(menu);
            menu.parentNode.removeChild(menu);
        });

        // Insert the stored menus before the more menu.
        var moveMenus = [];
        menuslist.forEach((menu) => {
            if (navMenu.insertBefore(menu, navMenu.lastElementChild) && li.length > 0) {
                // Instead of move into moremenu, place the menus before the moremenu will moved to moremenu by moremenu.js.
                moveMenus.push(li.shift());
            }
        });

        // Move the non forced more menu before the moremenu to make the menu alignment.
        moveMenus.forEach((menu) => {
            navMenu.insertBefore(menu, navMenu.lastElementChild);
        });

        window.dispatchEvent(new Event('resize')); // Dispatch the resize event to create more menu.

        // After the menus are move out, rearrange menus to its original positions.
        reArrangeMenuOrgPositions(navMenu);
    };

    return {
        init: () => {
            addSubmenu();
            cardScroll();
            autoCollapse();
        }
    };
});
