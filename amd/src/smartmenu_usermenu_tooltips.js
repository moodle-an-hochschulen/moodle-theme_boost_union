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
 * Theme Boost Union - JS to keep the tooltips of the smart menu items in the user menu alive.
 *
 * Moodle core's user menu (core/usermenu) initializes tooltips for truncated dropdown items whenever the user menu
 * is opened. While doing so, it disposes the tooltip of every dropdown item which is not truncated, assuming that
 * all tooltips on dropdown items are its own truncation tooltips. This would dispose the tooltips of the smart menu
 * items (which are initialized by theme_boost/loader on page load) as well. This module re-initializes them after
 * core/usermenu has handled the opening of the user menu.
 *
 * @module     theme_boost_union/smartmenu_usermenu_tooltips
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {Tooltip} from 'bootstrap';

const SELECTORS = {
    usermenu: '.usermenu',
    tooltiptriggers: '.dropdown-menu [data-bs-toggle="tooltip"]',
};

/**
 * Initialize the module.
 *
 * This must be called after core/usermenu has been initialized so that its 'shown.bs.dropdown' listener (which
 * disposes the tooltips) runs before the listener of this module (which re-creates them).
 */
export const init = () => {
    const usermenu = document.querySelector(SELECTORS.usermenu);
    if (!usermenu) {
        return;
    }

    usermenu.addEventListener('shown.bs.dropdown', () => {
        usermenu.querySelectorAll(SELECTORS.tooltiptriggers).forEach((trigger) => {
            Tooltip.getOrCreateInstance(trigger);
        });
    });
};
