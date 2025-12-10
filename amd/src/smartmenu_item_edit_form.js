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
 * Theme Boost Union - JavaScript for Smart menu item edit form enhancements
 *
 * @module     theme_boost_union/smartmenu_item_edit_form
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([], function() {
    /**
     * Initialize the smart menu form enhancements
     *
     * @param {Object} config - Configuration object.
     * @return {Object} - Public methods for the module.
     */
    var init = function(config) {
        // Get the type select element.
        var typeSelect = document.getElementById('id_type');

        // Function to toggle header visibility.
        var toggleHeaderVisibility = function() {
            var selectedType = parseInt(typeSelect.value);

            // Loop through each header configuration.
            config.headerVisibility.forEach(function(headerConfig) {
                var headerContainer = document.getElementById('id_' + headerConfig.headerId);
                if (!headerContainer) {
                    return; // Skip if header element not found.
                }

                // Initialize the visibility state.
                var shouldHide = false;

                // Check if the current selected type should hide this header.
                headerConfig.hideForTypes.forEach(function(typeValue) {
                    if (selectedType === parseInt(typeValue)) {
                        // Hide this header for the current type.
                        shouldHide = true;
                    }
                });

                // Toggle the header visibility.
                if (shouldHide) {
                    headerContainer.style.display = 'none';
                } else {
                    headerContainer.style.display = '';
                }
            });
        };

        // Run once on page load.
        toggleHeaderVisibility();

        // Add change event listener to the type select.
        if (typeSelect) {
            typeSelect.addEventListener('change', toggleHeaderVisibility);
        }

        return {
            toggleHeaderVisibility: toggleHeaderVisibility
        };
    };

    return {
        init: init
    };
});
