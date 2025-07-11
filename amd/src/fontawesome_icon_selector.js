// This file is part of Moodle - https://moodle.org/
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
 * Provides the required functionality for an autocomplete element to select a FontAwesome icon.
 *
 * @module     theme_boost_union/fontawesome_icon_selector
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Ajax from 'core/ajax';
import {render as renderTemplate} from 'core/templates';
import {getString, getStrings} from 'core/str';

/**
 * Load the list of FontAwesome icons matching the query and render the selector labels for them.
 *
 * @param {String} selector The selector of the auto complete element.
 * @param {String} query The query string.
 * @param {Function} callback A callback function receiving an array of results.
 * @param {Function} failure A function to call in case of failure, receiving the error message.
 */
export async function transport(selector, query, callback, failure) {

    const request = {
        methodname: 'theme_boost_union_get_fontawesome_icons',
        args: {
            query: query
        }
    };

    try {
        const response = await Ajax.call([request])[0];

        if (response.overflow) {
            const msg = await getString('smartmenusmenuitemicon_ajaxtoomanyicons', 'theme_boost_union', '>' + response.maxicons);
            callback(msg);

        } else {
            // First, get all the strings we need using getStrings.
            const sourceStrings = await getStrings([
                {key: 'smartmenusmenuitemicon_sourcecore', component: 'theme_boost_union'},
                {key: 'smartmenusmenuitemicon_sourcefasolid', component: 'theme_boost_union'},
                {key: 'smartmenusmenuitemicon_sourcefabrand', component: 'theme_boost_union'},
                {key: 'smartmenusmenuitemicon_sourcefablank', component: 'theme_boost_union'}
            ]);

            // Then, format the icons based on their source.
            const formattedIcons = response.icons.map(icon => {
                // Format icon data based on source.
                let formattedIcon = {
                    value: icon.name
                };

                // If this is a Moodle core icon.
                if (icon.source === 'core') {
                    formattedIcon.name = icon.name;
                    formattedIcon.class = icon.class;
                    formattedIcon.source = sourceStrings[0];
                    formattedIcon.sourcecolor = 'bg-warning text-dark';

                    // Otherwise, if this is a FontAwesome solid icon.
                } else if (icon.source === 'fasolid') {
                    formattedIcon.name = icon.class;
                    formattedIcon.class = 'fas ' + icon.class;
                    formattedIcon.source = sourceStrings[1];
                    formattedIcon.sourcecolor = 'bg-success';

                    // Otherwise, if this is a FontAwesome brands icon.
                } else if (icon.source === 'fabrand') {
                    formattedIcon.name = icon.class;
                    formattedIcon.class = 'fab ' + icon.class;
                    formattedIcon.source = sourceStrings[2];
                    formattedIcon.sourcecolor = 'bg-success';

                    // Otherwise, if this is the FontAwesome blank icon.
                } else if (icon.source === 'fablank') {
                    formattedIcon.name = icon.class;
                    formattedIcon.class = 'fa ' + icon.class;
                    formattedIcon.source = sourceStrings[3];
                    formattedIcon.sourcecolor = 'bg-success';
                }
                // All other icon sources (which should not appear) will be ignored for now.

                return formattedIcon;
            });

            // Render all icons with the Mustache template.
            let labels = await Promise.all(
                formattedIcons.map(formattedIcon =>
                    renderTemplate('theme_boost_union/form_autocomplete_fontawesome_icon', formattedIcon)
                )
            );

            // Add the rendered HTML labels to the icons.
            formattedIcons.forEach((icon, index) => {
                icon.label = labels[index];
            });

            callback(formattedIcons);
        }

    } catch (e) {
        failure(e);
    }
}

/**
 * Process the results for auto complete elements.
 *
 * @param {String} selector The selector of the auto complete element.
 * @param {Array} results An array or results returned by {@see transport()}.
 * @return {Array} New array of the selector options.
 */
export function processResults(selector, results) {

    if (!Array.isArray(results)) {
        return results;

    } else {
        return results.map(result => ({value: result.value, label: result.label}));
    }
}
