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
 * Theme Boost Union - JS code to handle block regions alignments.
 *
 * @module     theme_boost_union/blockregions
 * @copyright  2025 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([], function() {

    // CSS selectors for the relevant page elements and block regions.
    const SELECTORS = {
        outsideLeft: '#theme-block-region-outside-left',
        outsideRight: '#theme-block-region-outside-right',
        mainContent: '#page-content',
        courseContent: '#page-content .course-content',
        upperRegion: '#theme-block-region-content-upper',
        alignmentspacerRegion: '.outside-region-alignmentspacer',
        mainInner: '.main-inner-wrapper',
        mainPage: '#page',
    };

    /**
     * Get the Bootstrap large breakpoint value from CSS custom properties.
     * Falls back to 992px if the CSS variable is not available.
     * @returns {number} The large viewport breakpoint in pixels.
     */
    const getLargeViewportBreakpoint = function() {
        // Try to get the Bootstrap --bs-breakpoint-lg CSS custom property.
        const rootStyles = getComputedStyle(document.documentElement);
        const bsLgBreakpoint = rootStyles.getPropertyValue('--bs-breakpoint-lg');

        if (bsLgBreakpoint) {
            // Parse the value (e.g., "992px" -> 992).
            const numericValue = parseInt(bsLgBreakpoint.replace('px', ''), 10);
            return isNaN(numericValue) ? 992 : numericValue;
        }

        // Fallback to Bootstrap's default lg breakpoint.
        return 992;
    };

    // Calculate the large viewport breakpoint once at module initialization to avoid repeated calculations during resize events.
    const LARGE_VIEWPORT_BREAKPOINT = getLargeViewportBreakpoint();

    return {

        /**
         * Initialize the vertical alignment system for outside block regions.
         * This function manages the visibility and positioning of left and right sidebar regions
         * to ensure they align properly with the main content area on large viewports.
         * On smaller viewports, it resets the alignment to let Bootstrap handle the layout.
         */
        OutsideRegionsVerticalAlignment: function() {

            /**
             * If the outside regions vertical alignment is enabled, the 'invisible' class is added on the outside regions
             * in Mustache. We then need to remove the class to show the outside regions again after they have been vertically
             * aligned.
             */
            const showOutsideRegions = function() {
                const outsideLeft = document.querySelector(SELECTORS.outsideLeft);
                const outsideRight = document.querySelector(SELECTORS.outsideRight);

                // Remove Bootstrap's invisible class to make left region visible.
                if (outsideLeft !== null) {
                    outsideLeft.classList.remove('invisible');
                }

                // Remove Bootstrap's invisible class to make right region visible.
                if (outsideRight !== null) {
                    outsideRight.classList.remove('invisible');
                }
            };

            /**
             * Calculate and update the vertical alignment of outside block regions.
             * This function ensures that left and right block regions align properly with the main content
             * by adjusting alignmentspacer heights based on the difference in vertical positioning.
             * Vertical alignment is only applied on large viewports.
             */
            const updateOutsideRegionsAlignment = function() {

                // Get references to the main page elements.
                const outsideLeft = document.querySelector(SELECTORS.outsideLeft);
                const outsideRight = document.querySelector(SELECTORS.outsideRight);
                const mainPage = document.querySelector(SELECTORS.mainInner) || document.querySelector(SELECTORS.mainPage);

                // Check if we're on a large viewport where vertical alignment should be applied.
                const isLargeViewport = window.innerWidth >= LARGE_VIEWPORT_BREAKPOINT;

                // If we're on a small viewport, reset alignmentspacer heights and exit.
                if (!isLargeViewport) {
                    if (outsideLeft !== null) {
                        const leftAlignmentspacer = outsideLeft.querySelector(SELECTORS.alignmentspacerRegion);
                        if (leftAlignmentspacer !== null) {
                            leftAlignmentspacer.style.height = '0px';
                        }
                    }
                    if (outsideRight !== null) {
                        const rightAlignmentspacer = outsideRight.querySelector(SELECTORS.alignmentspacerRegion);
                        if (rightAlignmentspacer !== null) {
                            rightAlignmentspacer.style.height = '0px';
                        }
                    }
                    return;
                }

                // Use the upper region add block div as main content if turn editing on.
                // Use the course content div as main content if it exists, otherwise use the page content div.
                const mainContent = document.querySelector(SELECTORS.upperRegion) ||
                    document.querySelector(SELECTORS.courseContent) || document.querySelector(SELECTORS.mainContent);

                // Exit early if no main content is found.
                if (mainContent === null) {
                    return;
                }

                // Calculate dimensions for alignment calculations.
                const outsideLeftWidth = outsideLeft ? outsideLeft.offsetWidth : 0;
                const outsideRightWidth = outsideRight ? outsideRight.offsetWidth : 0;
                const mainContentWidth = mainContent.offsetWidth;
                const mainPageWidth = mainPage ? mainPage.offsetWidth : 0;

                // Get the top position of the main content for alignment reference.
                var mainContentTop = mainContent.getBoundingClientRect().top;

                // Handle left region alignment if it exists and has enough space.
                if (outsideLeft !== null && mainPageWidth >= outsideLeftWidth + mainContentWidth) {
                    // Find the difference between left region offset top and main content offset top.
                    // Assign the difference to the height of the alignmentspacer region.
                    const leftRegionTop = outsideLeft.getBoundingClientRect().top;
                    const offsetTop = mainContentTop - leftRegionTop;
                    const leftAlignmentspacer = outsideLeft.querySelector(SELECTORS.alignmentspacerRegion);
                    if (leftAlignmentspacer !== null) {
                        // Confirm the left region is inline with the main content.
                        const isLeftRegionInline = (mainPageWidth >= outsideLeftWidth + mainContentWidth);
                        // Set alignmentspacer height to create proper vertical alignment.
                        leftAlignmentspacer.style.height = isLeftRegionInline ? offsetTop + 'px' : 0;
                    }
                }

                // Handle right region alignment if it exists.
                if (outsideRight !== null) {

                    // Find the difference between right region offset top and main content offset top.
                    // Assign the difference to the height of the alignmentspacer region.
                    const rightRegionTop = outsideRight.getBoundingClientRect().top;
                    const offsetTop = mainContentTop - rightRegionTop;
                    const rightAlignmentspacer = outsideRight.querySelector(SELECTORS.alignmentspacerRegion);
                    if (rightAlignmentspacer !== null) {
                        // Confirm the Right region is inline with the main content.
                        const isRightRegionInline = mainPageWidth >= (outsideRightWidth + mainContentWidth + outsideLeftWidth);
                        // Set alignmentspacer height to create proper vertical alignment
                        rightAlignmentspacer.style.height = isRightRegionInline ? offsetTop + 'px' : 0;
                    }
                }
            };

            // Set up event listener for window resize to maintain proper alignment behavior.
            // This ensures that alignment is applied/removed when switching between viewport sizes.
            window.addEventListener('resize', updateOutsideRegionsAlignment);

            // Perform initial alignment calculation when the module is loaded.
            updateOutsideRegionsAlignment();

            // Make the outside regions visible by removing the invisible class.
            showOutsideRegions();
        }
    };
});
