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
 * Theme Boost Union - JS code for manage block regions alignments.
 *
 * @module     theme_boost_union/blockregions
 * @copyright  2025 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([], function() {

    const SELECTORS = {
        outsideLeft: '#theme-block-region-outside-left',
        outsideRight: '#theme-block-region-outside-right',
        mainContent: '#page-content',
        courseContent: '#page-content .course-content',
        upperRegion: '#theme-block-region-content-upper',
        placeholderRegion: '.outside-region-placeholder',
        mainInner: '.main-inner-wrapper',
        mainPage: '#page',
    };

    return {

        OutsideRegionsVerticalAlignment: function() {

            /**
             * If the outside regions vertical alignment is enabled, Invisible class added on the outside regions.
             * Need to remove the class to show the outside regions.
             */
            const showOutsideRegions = function() {
                const outsideLeft = document.querySelector(SELECTORS.outsideLeft);
                const outsideRight = document.querySelector(SELECTORS.outsideRight);

                if (outsideLeft !== null) {
                    outsideLeft.classList.remove('invisible');
                }

                if (outsideRight !== null) {
                    outsideRight.classList.remove('invisible');
                }
            };

            const updateOutsideRegionsAlignment = function() {

                const outsideLeft = document.querySelector(SELECTORS.outsideLeft);
                const outsideRight = document.querySelector(SELECTORS.outsideRight);
                const mainPage = document.querySelector(SELECTORS.mainInner) || document.querySelector(SELECTORS.mainPage);

                // Use the upper region add block div as main content if turn editing on.
                // Use the course content div as main content if it exists, otherwise use the page content div.
                const mainContent = document.querySelector(SELECTORS.upperRegion) ||
                    document.querySelector(SELECTORS.courseContent) || document.querySelector(SELECTORS.mainContent);

                if (mainContent === null) {
                    return;
                }

                // Find the sizes of the outside regions and the main page.
                const outsideLeftWidth = outsideLeft ? outsideLeft.offsetWidth : 0; // Left region width.
                const outsideRightWidth = outsideRight ? outsideRight.offsetWidth : 0; // Right region width.
                const mainContentWidth = mainContent.offsetWidth; // Main content width.
                const mainPageWidth = mainPage ? mainPage.offsetWidth : 0; // Main inner wrapper width.

                var mainContentTop = mainContent.getBoundingClientRect().top;

                if (outsideLeft !== null && mainPageWidth >= outsideLeftWidth + mainContentWidth) {
                    // Find the difference between left region offset top and main content offset top.
                    // Assign the difference to the height of the placeholder region.
                    const leftRegionTop = outsideLeft.getBoundingClientRect().top;
                    const offsetTop = mainContentTop - leftRegionTop;
                    const leftPlaceholder = outsideLeft.querySelector(SELECTORS.placeholderRegion);
                    if (leftPlaceholder !== null) {
                        // Confirm the left region is inline with the main content.
                        const isLeftRegionInline = (mainPageWidth >= outsideLeftWidth + mainContentWidth);
                        leftPlaceholder.style.height = isLeftRegionInline ? offsetTop + 'px' : 0;
                    }
                }

                if (outsideRight !== null) {

                    // Find the difference between right region offset top and main content offset top.
                    // Assign the difference to the height of the placeholder region.
                    const rightRegionTop = outsideRight.getBoundingClientRect().top;
                    const offsetTop = mainContentTop - rightRegionTop;
                    const rightPlaceholder = outsideRight.querySelector(SELECTORS.placeholderRegion);
                    if (rightPlaceholder !== null) {
                        // Confirm the Right region is inline with the main content.
                        const isRightRegionInline = mainPageWidth >= (outsideRightWidth + mainContentWidth + outsideLeftWidth);
                        rightPlaceholder.style.height = isRightRegionInline ? offsetTop + 'px' : 0;
                    }
                }
            };

            window.addEventListener('resize', updateOutsideRegionsAlignment);
            updateOutsideRegionsAlignment();
            showOutsideRegions();
        }
    };

});
