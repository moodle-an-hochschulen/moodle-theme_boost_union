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
 * Theme Boost Union - JS code which shows all fontawesome icons in a popover.
 *
 * @module     theme_boost_union/fontawesome-popover
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @copyright  based on code from theme_boost\footer-popover by Bas Brands.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'theme_boost/popover', 'core/fragment', 'core/notification'], function($, popover, Fragment, Notification) {

    const SELECTORS = {
        PICKERCONTAINER: '.fontawesome-iconpicker-popover',
        PICKERCONTENT: '[data-region="icons-list"]',
    };

    var contextID;

    let pickerIsShown = false;

    var SELECTBOX;

    /**
     * Get the icon list for popover.
     *
     * @returns {String} HTML string
     * @private
     */
    const getIconList = () => {
        return Fragment.loadFragment('theme_boost_union', 'icons_list', contextID, {});
    };

    /**
     * Filter the icons in the list with values which the user entered in the search input.
     * Given input will contain the text in both data-value and data-label.
     * Ex. "core:t\document" is data-value and "fa-document" is data-label.
     *
     * @param {Element} target
     */
    const filterIcons = (target) => {
        var filter = target.value.toLowerCase();
        SELECTBOX.value = filter || 0;
        var ul = document.querySelector('.fontawesome-iconpicker-popover ul.fontawesome-icon-suggestions');
        if (ul === undefined || ul === null) {
            return;
        }
        var li = ul.querySelectorAll('li');

        for (var i = 0; i < li.length; i++) {
            var value = li[i].getAttribute('data-value');
            var label = li[i].getAttribute('data-label');
            if (!value.toLowerCase().includes(filter) && !label.toLowerCase().includes(filter)) {
                li[i].style.display = "none";
            } else {
                li[i].style.display = "inline-block";
            }
        }
    };

    /**
     * Creates input element and append the element into the target element's parent node.
     * User is able to search icons using this input field.
     *
     * @param {String} target Element Selector.
     */
    const createElements = (target) => {

        var input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.classList.add('fontawesome-autocomplete');
        input.classList.add('form-control');
        input.setAttribute('name', 'iconsearch');

        if (SELECTBOX.value != '') {
            input.value = SELECTBOX.querySelector('option[selected]') !== null
                ? SELECTBOX.querySelector('option[selected]').text : '';
        }

        var wrapper = document.createElement('div');
        wrapper.classList.add("fontawesome-picker-container");
        wrapper.append(input);

        document.querySelector(target).style.display = 'none';
        document.querySelector(target).parentNode.append(wrapper);
    };

    /**
     * Update the target with fontawesome iconpicker.
     *
     * Create picker input field for search icons insert to DOM, fetch the icons list and setup the popover with icons content.
     * Display the popover when the icon search input field is focused or clicked. This way user can view the list of icons and
     * search icons. When the icon is selected, same icon in the select element will be selected.
     *
     * @param {String} target Element Selector.
     */
    const iconPicker = (target) => {

        SELECTBOX = document.querySelector(target);

        if (SELECTBOX === undefined || SELECTBOX === null) {
            return;
        }

        // Create input element and insert for search icons and hide the current target select box.
        createElements(target);

        // Parent of the target element.
        var selectBoxParent = document.querySelector(target).parentNode;

        // Input element for search icons, appended in createElements method.
        const pickerInput = selectBoxParent.querySelector("input.fontawesome-autocomplete");

        // Check the search input created and inserted in DOM.
        if (pickerInput === undefined || pickerInput === null) {
            setTimeout(() => iconPicker(target), 1000);
            return;
        }

        // Fetch the icons list and setup popover with icons list.
        getIconList().then(function(html) {

            $(pickerInput).popover({
                content: html,
                html: true,
                placement: 'bottom',
                customClass: 'fontawesome-picker',
                trigger: 'click',
                sanitize: false
            });

            // Event observer when the popover is inserted in DOM, create event listner for each icon in icons list.
            // Icon is clicked, set the icon data-value as value for select box.
            // Set the icon label to value of autocomplete picker.
            $(pickerInput).on('inserted.bs.popover', function() {
                var ul = document.querySelector('.fontawesome-iconpicker-popover ul.fontawesome-icon-suggestions');
                ul.querySelectorAll('li').forEach((li) => {
                    li.addEventListener('click', (e) => {
                        var target = e.target.closest('li');
                        var value = target.getAttribute('data-value');
                        var label = target.getAttribute('data-label');
                        pickerInput.value = label;
                        SELECTBOX.value = value || 0;
                        $(pickerInput).popover('hide');
                    });
                });
            });
            return;
        }).catch(Notification.exception);

        document.addEventListener('click', e => {
            if (pickerIsShown && !e.target.closest(SELECTORS.PICKERCONTAINER)) {
                $(pickerInput).popover('hide');
            }
        },
        true);

        document.addEventListener('keydown', e => {
            if (pickerIsShown && e.key === 'Escape') {
                $(pickerInput).popover('hide');
                pickerInput.focus();
            }
        });

        document.addEventListener('focus', e => {
            if (pickerIsShown && !e.target.closest(SELECTORS.PICKERCONTAINER)) {
                $(pickerInput).popover('hide');
            }
        },
        true);

        $(pickerInput).on('shown.bs.popover', () => {
            pickerIsShown = true;
            // Add class to selected icon, helps to differentiate.
            if (pickerInput.value != '') {
                var iconSuggestion = document.querySelector('.fontawesome-iconpicker-popover ul.fontawesome-icon-suggestions');
                if (iconSuggestion.querySelector('li[data-label="' + pickerInput.value + '"]') !== null) {
                    // Remove selected class.
                    iconSuggestion.querySelectorAll('li').forEach((li) =>
                            li.classList.remove('selected'));
                    // Assign selected class for new.
                    iconSuggestion.querySelector('li[data-label="' + pickerInput.value + '"]').classList.add('selected');
                }
            }
        });

        $(pickerInput).on('hide.bs.popover', () => {
            pickerIsShown = false;
        });

        pickerInput.addEventListener('keyup', function(e) {
            filterIcons(e.target);
        });

    };

    return {
        init: (target, contextid) => {
            contextID = contextid;
            iconPicker(target);
        }

    };
});
