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
    // Get the system information field.
    const systemInfoField = form.querySelector('[name=techinfo]');

    // If the system information field does not exist, return.
    if (!systemInfoField) {
        return;
    }

    // Compose the system information.
    const systemInfo = [
        `windowSize="${window.innerWidth}×${window.innerHeight}"`,
        `prefersReducedMotion=${window.matchMedia('(prefers-reduced-motion: reduce)').matches}`,
        `prefersMoreContrast=${window.matchMedia('(prefers-contrast: more)').matches}`,
        `invertedColors=${window.matchMedia('(inverted-colors: inverted)').matches}`,
        `vendor="${navigator.vendor}"`,
        `userAgent="${navigator.userAgent}"`,
    ];

    // Prepare fields to replace placeholders later.
    const replaceFields = {
        'systeminfo': systemInfo.join('\n'),
    };

    // Get the current value of the system information field.
    var infoMessage = systemInfoField.value;

    // Replace the placeholders with the actual values.
    // Currently, only the system information "{systeminfo}" is replaced.
    for (let field in replaceFields) {
        let value = replaceFields[field];
        infoMessage = infoMessage.replace(`##${field}##`, value);
    }
    systemInfoField.value = infoMessage;

    // Set the system information to readonly. Browsers do not send field values if a field is disabled.
    systemInfoField.readOnly = true;
}

export const init = (config) => {
    // Get the provided form ID from the config.
    const form = document.getElementById(config.formId);

    // Fill the form fields with system information.
    fillFormFields(form);
};
