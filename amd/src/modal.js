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
 * Theme Boost Union - Generic modal.
 *
 * @module     theme_boost_union/modal
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalCancel from 'core/modal_cancel';
import Notification from 'core/notification';
import {getString} from 'core/str';

const SELECTORS = {
    MODAL: '[data-action="bumodal"]',
};

/**
 * Entrypoint of the js.
 *
 * @method init
 */
export const init = () => {
    registerListenerEvents();
};

/**
 * Register generic modal trigger listeners.
 *
 * Expected attributes on trigger element:
 * - data-action="bumodal"
 * - data-title="..."
 * - data-body="..."
 *
 * @method registerListenerEvents
 */
const registerListenerEvents = () => {
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest(SELECTORS.MODAL);
        if (trigger) {
            e.preventDefault();
            buildModal(trigger).catch(Notification.exception);
        }
    });
};

/**
 * Build the modal with the provided element data.
 *
 * @method buildModal
 * @param {object} element
 */
const buildModal = async(element) => {
    // Prepare data for modal.
    const data = {
        title: element.getAttribute('data-title') || '',
        body: element.getAttribute('data-body') || '',
    };

    await ModalCancel.create({
        title: data.title,
        body: data.body,
        buttons: {
            'cancel': getString('closebuttontitle', 'moodle'),
        },
        show: true,
    });
};
