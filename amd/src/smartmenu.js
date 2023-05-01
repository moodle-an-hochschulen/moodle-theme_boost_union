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
 * Theme Boost Union - JS code smartmenu
 *
 * @module     theme_boost_union/smartmenu
 * @copyright  bdecent GmbH 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Implement the second level of submenu support.
 * Find the submenus inside the dropdown add event listener for click event, on the click show the submenu list.
 */
const addSubmenu = () => {
    // Fetch the list of submenus from moremenu.
    var submenu = document.querySelectorAll('nav.moremenu .dropdown-submenu');
    if (submenu !== null) {
        submenu.forEach((item) => {
            // Add event listener to show the submenu on click.
            item.addEventListener('click', (e) => {
                e.preventDefault();
                var target = e.currentTarget;
                target.classList.toggle('show');
                // Prevent the hide of parent menu.
                e.stopPropagation();
            });
        });
    }
};

export const init = () => {
    addSubmenu();
};
