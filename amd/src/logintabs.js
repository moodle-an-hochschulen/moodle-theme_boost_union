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
 * Theme Boost Union - JS code for login tabs layout sync
 *
 * @module     theme_boost_union/logintabs
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Selectors for the relevant elements in the login page.
const SELECTOR_TABS = '#login-layout-tabs';
const SELECTOR_TARGETS = '.login-heading, .login-instructions';
const SELECTOR_WRAPPER = '.login-wrapper';
const SELECTOR_CONTAINER = '.login-container';
const DATA_SPACER_ATTR = 'data-bu-login-spacer';
const SPACER_TOP = 'top';
const SPACER_BOTTOM = 'bottom';

// State for the vertical lock, currently only storing the top offset of the container inside the wrapper.
const loginLockState = {
    topOffset: null,
};

/**
 * Get the media query for a Bootstrap breakpoint.
 * Tries to read from Bootstrap's CSS variable, falls back to provided value if not available.
 *
 * @param {string} cssVar - The CSS variable name to read.
 * @returns {string} Media query string
 */
const getMediaQueryByVar = (cssVar) => {
    // Fallback values for Bootstrap breakpoints if CSS variables are not available (e.g., in older Bootstrap versions).
    const fallbackMap = {
        '--bs-breakpoint-sm': '576px',
        '--bs-breakpoint-md': '768px',
    };
    const fallback = fallbackMap[cssVar];

    // Try to get breakpoint from Bootstrap 5.3+ CSS variable.
    const breakpoint = getComputedStyle(document.body)
        .getPropertyValue(cssVar).trim();

    // Use the CSS variable value if available, otherwise fall back to default.
    const breakpointValue = breakpoint || fallback;

    return `(min-width: ${breakpointValue})`;
};

/**
 * Entrypoint of the js.
 *
 * @method init
 */
export const init = () => {
    docReady(initLoginTabs);
};

/**
 * Ensures the passed function will be called after the DOM is ready/loaded:
 * In case DOM is fully loaded when JS is called, call within next tick.
 * Otherwise sets an eventlistener for DOMContentLoaded.
 *
 * @param {Function} callback
 */
const docReady = (callback) => {
    if (document.readyState === "complete" || document.readyState === "interactive") {
        setTimeout(callback, 1);
    } else {
        document.addEventListener('DOMContentLoaded', callback);
    }
};

/**
 * Initialising.
 */
const initLoginTabs = () => {
    // Even if this JS is only loaded on pages with login tabs, the tabs, wrapper and container might not be present
    // for strange reasons.

    // Get the tabs. If they are not present, we cannot do anything, so just return.
    const tabs = document.querySelector(SELECTOR_TABS);
    if (!tabs) {
        return;
    }

    // Get the wrapper and container elements. If they are not present, we cannot do anything, so just return.
    const wrapper = document.querySelector(SELECTOR_WRAPPER);
    const container = wrapper?.querySelector(SELECTOR_CONTAINER);
    if (!wrapper || !container) {
        return;
    }

    // Create media query matcher using Bootstrap's breakpoints.
    const mediaQueryWidth = window.matchMedia(getMediaQueryByVar('--bs-breakpoint-md'));
    const mediaQueryVertical = window.matchMedia(getMediaQueryByVar('--bs-breakpoint-sm'));

    // Apply layout adjustments initially.
    applyWidth(mediaQueryWidth);
    applyVerticalLock(mediaQueryVertical);

    // Re-apply on resize.
    window.addEventListener('resize', () => {
        applyWidth(mediaQueryWidth);
        applyVerticalLock(mediaQueryVertical, true);
    });

    // Re-apply when media query changes (e.g., rotating device or resizing browser).
    mediaQueryWidth.addEventListener('change', () => {
        applyWidth(mediaQueryWidth);
    });
    mediaQueryVertical.addEventListener('change', () => {
        applyVerticalLock(mediaQueryVertical, true);
    });

    // Re-apply if the tabs element itself changes size.
    if (typeof ResizeObserver === 'function') {
        const observer = new ResizeObserver(() => {
            applyWidth(mediaQueryWidth);
        });
        observer.observe(tabs);
    }

    // Re-apply vertical lock when the container height changes.
    if (typeof ResizeObserver === 'function') {
        const observer = new ResizeObserver(() => {
            applyVerticalLock(mediaQueryVertical);
        });
        observer.observe(container);
    }
};

/**
 * Apply the tabs width to login headings and instructions.
 *
 * @param {MediaQueryList} mediaQueryList - The media query to check.
 */
const applyWidth = (mediaQueryList) => {
    // Only apply width on md+ breakpoint (matching CSS media-breakpoint-up(md)).
    if (!mediaQueryList.matches) {
        // On smaller screens, remove any inline styles that were applied.
        document.querySelectorAll(SELECTOR_TARGETS).forEach((node) => {
            node.style.width = '';
            node.style.maxWidth = '';
        });
        return;
    }

    // Get the tabs. If they are not present, we cannot do anything, so just return.
    const tabs = document.querySelector(SELECTOR_TABS);
    if (!tabs) {
        return;
    }

    // Get the tabs width. If it is 0, we cannot do anything, so just return.
    const tabsWidth = Math.ceil(tabs.getBoundingClientRect().width);
    if (!tabsWidth) {
        return;
    }

    // Apply the width to the target elements.
    document.querySelectorAll(SELECTOR_TARGETS).forEach((node) => {
        node.style.width = `${tabsWidth}px`;
        node.style.maxWidth = `${tabsWidth}px`;
    });
};

/**
 * Prevent vertical jumping of the login container when tabs switch content.
 * Locks the container's top position on load and lets it grow downward only.
 *
 * @param {MediaQueryList} mediaQueryList - The media query to check.
 * @param {boolean} recalc - Whether to re-calc the top offset.
 */
const applyVerticalLock = (mediaQueryList, recalc = false) => {
    // Only apply vertical lock on sm+ breakpoint (matching CSS media-breakpoint-up(sm)).
    if (!mediaQueryList.matches) {
        resetVerticalLock();
        return;
    }

    // Get the wrapper, container and tabs elements. If they are not present, we cannot do anything, so just return.
    const wrapper = document.querySelector(SELECTOR_WRAPPER);
    const container = wrapper?.querySelector(SELECTOR_CONTAINER);
    const tabs = document.querySelector(SELECTOR_TABS);
    if (!wrapper || !container || !tabs) {
        resetVerticalLock();
        return;
    }

    // On first run, calculate and store the top offset of the container inside the wrapper.
    // On subsequent runs (e.g., on resize), we can skip this if the container height didn't
    // change in a way that would cause overflow, to prevent unnecessary layout thrashing.
    // However, if recalc is explicitly requested (e.g., on breakpoint change), we need to
    // re-calculate the top offset, as the layout might have changed significantly.
    if (loginLockState.topOffset === null || recalc) {
        loginLockState.topOffset = getTopOffset(wrapper, container);
    }

    // Ensure the spacer elements exist and get references to them.
    const {topSpacer, bottomSpacer} = ensureSpacers(wrapper, container);

    // Apply necessary layout styles to wrapper and container for the vertical lock to work.
    applyWrapperLayout(wrapper);
    applyContainerLayout(container);

    // Set the top spacer to the calculated top offset, and the bottom spacer to take up remaining space.
    topSpacer.style.height = `${loginLockState.topOffset}px`;
    bottomSpacer.style.minHeight = '0';

    // If the container's bottom is currently overflowing the viewport, try to adjust the top offset to prevent overflow.
    if (adjustTopOffsetForOverflow(wrapper, container)) {
        topSpacer.style.height = `${loginLockState.topOffset}px`;
    }
};

/**
 * Adjust the top offset when the container would overflow the viewport.
 *
 * @param {HTMLElement} wrapper
 * @param {HTMLElement} container
 * @returns {boolean}
 */
const adjustTopOffsetForOverflow = (wrapper, container) => {
    // Get the current positions and viewport height.
    const wrapperRect = wrapper.getBoundingClientRect();
    const containerRect = container.getBoundingClientRect();
    const viewportHeight = window.innerHeight;

    // If the container's bottom is not overflowing the viewport, no adjustment is needed.
    if (containerRect.bottom <= viewportHeight) {
        return false;
    }

    // Calculate a new top offset to try to fit the container within the viewport.
    let newTopOffset = loginLockState.topOffset;

    // If the container is taller than the viewport, align the top of the container with the top of the viewport.
    if (containerRect.height >= viewportHeight) {
        newTopOffset = Math.max(0, Math.round(0 - wrapperRect.top));

        // Otherwise, if the container is shorter than the viewport, try to center it vertically within the viewport.
    } else {
        const centeredTop = Math.round((viewportHeight - containerRect.height) / 2);
        newTopOffset = Math.max(0, Math.round(centeredTop - wrapperRect.top));
    }

    // If the new top offset is the same as the current one, no adjustment is needed.
    if (newTopOffset === loginLockState.topOffset) {
        return false;
    }

    // Update the top offset in the state.
    loginLockState.topOffset = newTopOffset;

    // Indicate that an adjustment was made.
    return true;
};

/**
 * Get the top offset of the container inside the wrapper.
 *
 * @param {HTMLElement} wrapper
 * @param {HTMLElement} container
 * @returns {number}
 */
const getTopOffset = (wrapper, container) => {
    // Calculate the offset of the container's top relative to the wrapper's top.
    const wrapperRect = wrapper.getBoundingClientRect();
    const containerRect = container.getBoundingClientRect();
    const offset = containerRect.top - wrapperRect.top;

    // Ensure the offset is not negative and round it to an integer.
    return Math.max(0, Math.round(offset));
};

/**
 * Ensure the top and bottom spacer elements exist.
 *
 * @param {HTMLElement} wrapper
 * @param {HTMLElement} container
 * @returns {{topSpacer: HTMLElement, bottomSpacer: HTMLElement}}
 */
const ensureSpacers = (wrapper, container) => {
    // Check if the top spacer exists, and create it if not.
    let topSpacer = wrapper.querySelector(`[${DATA_SPACER_ATTR}="${SPACER_TOP}"]`);
    if (!topSpacer) {
        topSpacer = createSpacer(SPACER_TOP);
        wrapper.insertBefore(topSpacer, container);
    }

    // Check if the bottom spacer exists, and create it if not.
    let bottomSpacer = wrapper.querySelector(`[${DATA_SPACER_ATTR}="${SPACER_BOTTOM}"]`);
    if (!bottomSpacer) {
        bottomSpacer = createSpacer(SPACER_BOTTOM);
        // Insert the bottom spacer after the container. If the container is the last child, append it to the end.
        if (container.nextSibling) {
            wrapper.insertBefore(bottomSpacer, container.nextSibling);
        } else {
            wrapper.appendChild(bottomSpacer);
        }
    }

    // Return references to the spacer elements.
    return {topSpacer, bottomSpacer};
};

/**
 * Create a spacer element.
 *
 * @param {string} position
 * @returns {HTMLElement}
 */
const createSpacer = (position) => {
    // Create a div element to serve as a spacer, with appropriate attributes and styles based on its position (top or bottom).
    const spacer = document.createElement('div');
    spacer.setAttribute(DATA_SPACER_ATTR, position);
    spacer.style.flex = position === SPACER_TOP ? '0 0 auto' : '1 1 auto';
    spacer.style.minHeight = '0';

    return spacer;
};

/**
 * Apply wrapper layout styles for vertical lock.
 *
 * @param {HTMLElement} wrapper
 */
const applyWrapperLayout = (wrapper) => {
    // Set the wrapper to use flexbox layout, with column direction and items aligned based on its classes.
    wrapper.style.display = 'flex';
    wrapper.style.flexDirection = 'column';
    wrapper.style.justifyContent = 'flex-start';

    // Determine horizontal alignment based on wrapper classes.
    let alignment = 'center';
    if (wrapper.classList.contains('login-wrapper-left')) {
        alignment = 'flex-start';
    } else if (wrapper.classList.contains('login-wrapper-right')) {
        alignment = 'flex-end';
    }
    wrapper.style.alignItems = alignment;

    // Ensure the wrapper does not exceed the viewport width.
    wrapper.style.maxWidth = '100%';
};

/**
 * Apply container layout styles for vertical lock.
 *
 * @param {HTMLElement} container
 */
const applyContainerLayout = (container) => {
    // Set the container to not grow or shrink, maintaining its natural size.
    container.style.flex = '0 0 auto';
};

/**
 * Reset vertical lock styles and spacers.
 */
const resetVerticalLock = () => {
    // Get the wrapper and container elements.
    const wrapper = document.querySelector(SELECTOR_WRAPPER);
    const container = document.querySelector(SELECTOR_CONTAINER);

    // Remove spacer elements and reset any inline styles applied for the vertical lock.
    if (wrapper) {
        wrapper.querySelectorAll(`[${DATA_SPACER_ATTR}]`).forEach((spacer) => {
            spacer.remove();
        });
        wrapper.style.display = '';
        wrapper.style.flexDirection = '';
        wrapper.style.justifyContent = '';
        wrapper.style.alignItems = '';
        wrapper.style.maxWidth = '';
    }

    // Reset container styles.
    if (container) {
        container.style.flex = '';
    }

    // Reset the login lock state.
    loginLockState.topOffset = null;
};
