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
 * Theme Boost Union - JS code for snippets details modal.
 *
 * @module     theme_boost_union/snippetsdetailsmodal
 * @copyright  2024 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 *             based on core_admin/themeselector/preview_modal by David Woloszyn <david.woloszyn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalCancel from 'core/modal_cancel';
import Notification from 'core/notification';
import Templates from 'core/templates';
import {getString} from 'core/str';

const SELECTORS = {
    SNIPPETS_CONTAINER: 'theme_boost_union_snippets',
    DETAILS: '[data-action="details"]',
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
 * Register snippet related event listeners.
 *
 * @method registerListenerEvents
 */
const registerListenerEvents = () => {
    document.addEventListener('click', (e) => {
        const details = e.target.closest(SELECTORS.DETAILS);
        if (details) {
            buildModal(details).catch(Notification.exception);
        }
    });
};

/**
 * Build the modal with the provided data.
 *
 * @method buildModal
 * @param {object} element
 */
const buildModal = async(element) => {

    // Prepare data for modal.
    const data = {
        title: element.getAttribute('data-title'),
        description: element.getAttribute('data-description'),
        image: element.getAttribute('data-image'),
        sourcebadge: element.getAttribute('data-source-badge'),
        goalbadge: element.getAttribute('data-goal-badge'),
        scopebadge: element.getAttribute('data-scope-badge'),
        creator: element.getAttribute('data-creator'),
        testedon: element.getAttribute('data-testedon'),
        trackerissue: element.getAttribute('data-trackerissue'),
        usagenote: element.getAttribute('data-usagenote'),
        id: element.getAttribute('data-id'),
        code: element.getAttribute('data-code'),
    };

    await ModalCancel.create({
        title: data.title,
        body: Templates.render('theme_boost_union/snippetsdetailsmodal', data),
        large: true,
        buttons: {
            'cancel': getString('closebuttontitle', 'moodle'),
        },
        show: true,
    });
};
