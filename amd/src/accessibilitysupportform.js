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
 * Theme Boost Union - JS code accessibility support form
 *
 * @module     theme_boost_union/accessibilitysupportform
 * @copyright  2024 Simon Schoenenberger <scgo@zhaw.ch>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Fill form fields with system information.
 *
 * @param {HTMLElement} form
 */
function fillFormFields(form) {
    const systemInfoField = form.querySelector('[name=techinfo]');

    if (!systemInfoField) {
        return;
    }

    const systemInfo = [
        `windowSize="${window.innerWidth}×${window.innerHeight}"`,
        `prefersReducedMotion=${window.matchMedia('(prefers-reduced-motion: reduce)').matches}`,
        `prefersMoreContrast=${window.matchMedia('(prefers-contrast: more)').matches}`,
        `invertedColors=${window.matchMedia('(inverted-colors: inverted)').matches}`,
        `vendor="${navigator.vendor}"`,
        `userAgent="${navigator.userAgent}"`,
    ];
    const replaceFields = {
        'systeminfo': systemInfo.join('\n'),
    };

    var infoMessage = systemInfoField.value;

    for (let field in replaceFields) {
        let value = replaceFields[field];
        infoMessage = infoMessage.replace(`{${field}}`, value);
    }

    systemInfoField.value = infoMessage;
    // Only set readable. Browser does not send field value if field is disabled.
    systemInfoField.readOnly = true;
}

export const init = (config) => {
    const form = document.getElementById(config.formId);
    fillFormFields(form);
};
