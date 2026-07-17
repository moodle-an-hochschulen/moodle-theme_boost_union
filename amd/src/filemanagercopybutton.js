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
 * Theme Boost Union - JS for a button which copies a file from one file manager to another file manager.
 *
 * This module adds the behaviour to a button which copies the file from a source file manager over to a target file
 * manager. The button is only shown as long as the target file manager is empty but the source file manager is not empty.
 * When the user confirms the action, the source file is copied over to the target file manager, purely on the client
 * side (into the target file manager's draft area), just as if the user had uploaded the file manually. The file is only
 * stored permanently as soon as the user saves the form.
 *
 * The module handles any number of such buttons on the page. Each button is configured individually via its own data
 * attributes:
 * - data-action="theme_boost_union/filemanagercopybutton" (marks the element as a copy button)
 * - data-source-elementid (the DOM id of the source file manager's hidden input element)
 * - data-target-elementid (the DOM id of the target file manager's hidden input element)
 * - data-confirm-title (the title of the confirmation dialog)
 * - data-confirm-question (the question shown in the confirmation dialog)
 * The button's own text is used as the label of the confirmation dialog's confirm button.
 *
 * @module     theme_boost_union/filemanagercopybutton
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {saveCancelPromise} from 'core/notification';
import Log from 'core/log';
import Pending from 'core/pending';

// The CSS selector which marks a copy button (based on its data-action attribute).
const BUTTON_SELECTOR = '[data-action="theme_boost_union/filemanagercopybutton"]';

// The CSS class which marks an empty file manager.
const FM_NOFILES_CLASS = 'fm-nofiles';

/**
 * Get the file manager container node which belongs to a given file manager form element.
 *
 * @param {String} elementid The DOM id of the file manager's hidden input element.
 * @return {HTMLElement|null} The file manager container node or null if it could not be found.
 */
const getFilemanagerNode = (elementid) => {
    // Get the hidden input element which holds the draft item id.
    const hiddeninput = document.getElementById(elementid);
    if (!hiddeninput) {
        return null;
    }

    // Get the surrounding form item and search the file manager container within it.
    const fitem = hiddeninput.closest('.fitem');
    if (!fitem) {
        return null;
    }

    return fitem.querySelector('.filemanager');
};

/**
 * Check if a given file manager is empty (i.e. does not hold any file).
 *
 * @param {HTMLElement|null} filemanagernode The file manager container node.
 * @return {Boolean} True if the file manager is empty, false otherwise.
 */
const isFilemanagerEmpty = (filemanagernode) => {
    if (!filemanagernode) {
        return true;
    }

    return filemanagernode.classList.contains(FM_NOFILES_CLASS);
};

/**
 * Wait until a given file manager holds at least one file.
 *
 * The core file manager announces its fill state by toggling the fm-nofiles class on its container node, so we simply
 * watch that class attribute. We give up after a timeout so that a failed upload does not block the caller forever.
 *
 * @param {HTMLElement} filemanagernode The file manager container node.
 * @param {Number} timeout The maximum time to wait in milliseconds.
 * @return {Promise<void>} A promise which resolves as soon as the file manager is filled (or the timeout has passed).
 */
const waitUntilFilemanagerIsFilled = (filemanagernode, timeout = 10000) => {
    return new Promise((resolve) => {
        // If the file manager holds a file already, we are done.
        if (!isFilemanagerEmpty(filemanagernode)) {
            resolve();
            return;
        }

        let timer = null;

        // Otherwise, watch the file manager's class attribute until it does not report itself as empty anymore.
        const observer = new MutationObserver(() => {
            if (!isFilemanagerEmpty(filemanagernode)) {
                clearTimeout(timer);
                observer.disconnect();
                resolve();
            }
        });

        timer = setTimeout(() => {
            observer.disconnect();
            resolve();
        }, timeout);

        observer.observe(filemanagernode, {attributes: true, attributeFilter: ['class']});
    });
};

/**
 * Get the draft item id which is used by a given file manager form element.
 *
 * @param {String} elementid The DOM id of the file manager's hidden input element.
 * @return {Number|null} The draft item id or null if it could not be found.
 */
const getDraftItemid = (elementid) => {
    const hiddeninput = document.getElementById(elementid);
    if (!hiddeninput || !hiddeninput.value) {
        return null;
    }

    return parseInt(hiddeninput.value, 10);
};

/**
 * Fetch the list of files which currently live in a given draft area.
 *
 * @param {Number} draftitemid The draft item id.
 * @return {Promise<Array>} A promise which resolves to the array of file objects (each with a url and filename attribute).
 */
const getDraftAreaFiles = async(draftitemid) => {
    // Compose the request to the draft files ajax endpoint.
    const params = new URLSearchParams();
    params.append('sesskey', M.cfg.sesskey);
    params.append('action', 'list');
    params.append('itemid', draftitemid);
    params.append('filepath', '/');

    // Send the request.
    const response = await fetch(M.cfg.wwwroot + '/repository/draftfiles_ajax.php?action=list', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        },
        body: params.toString(),
    });
    const data = await response.json();

    // Return the files, but only the real files (i.e. no folders).
    if (data && Array.isArray(data.list)) {
        return data.list.filter((file) => file.type !== 'folder');
    }

    return [];
};

/**
 * Copy the file from the source file manager over to the target file manager by simulating a manual file upload.
 *
 * This is done by fetching the existing source file and then dispatching a synthetic 'drop' event on the target file
 * manager. This way, the file is uploaded to the target file manager's draft area and the file manager is refreshed,
 * just as if the user had dragged and dropped the file manually.
 *
 * @param {String} sourceelementid The DOM id of the source file manager's hidden input element.
 * @param {HTMLElement} targetnode The target file manager container node.
 * @return {Promise<void>}
 */
const copyFile = async(sourceelementid, targetnode) => {
    // Get the draft item id of the source file manager.
    const sourcedraftitemid = getDraftItemid(sourceelementid);
    if (sourcedraftitemid === null) {
        return;
    }

    // Get the files which currently live in the source draft area. We only copy if there is exactly one file, so that
    // it is always unambiguous which file is copied over (this also guards against a file being added between showing
    // the button and confirming the action).
    const files = await getDraftAreaFiles(sourcedraftitemid);
    if (files.length !== 1) {
        return;
    }

    // Use the single file as source file.
    const sourcefile = files[0];

    // Fetch the source file as a blob and build a real File object from it.
    const fileresponse = await fetch(sourcefile.url, {credentials: 'same-origin'});
    const blob = await fileresponse.blob();
    const file = new File([blob], sourcefile.filename, {type: blob.type});

    // Compose a data transfer object which holds the file.
    const datatransfer = new DataTransfer();
    datatransfer.items.add(file);

    // Determine the node which listens for drop events within the target file manager.
    const dropnode = targetnode.querySelector('.filemanager-container') || targetnode;

    // Dispatch a synthetic 'drop' event on the target file manager, which triggers the drag-and-drop upload handler
    // and thus uploads the file to the target file manager's draft area and refreshes the field.
    const dropevent = new DragEvent('drop', {
        bubbles: true,
        cancelable: true,
        dataTransfer: datatransfer,
    });
    dropnode.dispatchEvent(dropevent);

    // The drop event only kicks off the upload, the upload itself and the subsequent refresh of the target file manager
    // happen asynchronously afterwards. We wait until the target file manager has really picked up the file, so that the
    // copy operation is completely finished when this function returns.
    await waitUntilFilemanagerIsFilled(targetnode);
};

/**
 * Set up a single copy button.
 *
 * The button carries the DOM ids of the two involved file managers as well as the confirmation dialog texts as data
 * attributes, so that the module does not need to be told about them via the init parameters and can handle any number
 * of copy buttons on the page.
 *
 * @param {HTMLElement} button The copy button.
 */
const setupButton = (button) => {
    // Get the file manager element ids from the button's data attributes.
    const sourceelementid = button.dataset.sourceElementid;
    const targetelementid = button.dataset.targetElementid;

    // If one of the file managers are not defined, we do nothing.
    if (!sourceelementid || !targetelementid) {
        return;
    }

    // Get the two file manager container nodes.
    const sourcenode = getFilemanagerNode(sourceelementid);
    const targetnode = getFilemanagerNode(targetelementid);

    // If one of the file managers could not be found, we do nothing.
    if (!sourcenode || !targetnode) {
        return;
    }

    // Helper function to update the button visibility based on the current fill state of the two file managers.
    // The button is only shown as long as the target file manager is empty but the source file manager is not empty.
    // We rely on the file managers' own empty-state (the fm-nofiles class) rather than counting the rendered file nodes,
    // as the latter is not reliable across the file manager's three view modes (icons, tree and details): each view mode
    // renders the file list with a different markup, so a node count would be wrong in some of them. The copy step itself
    // makes sure server-side that the source holds exactly one file, so that it is always unambiguous which file is copied.
    const updateButtonVisibility = () => {
        if (isFilemanagerEmpty(targetnode) && !isFilemanagerEmpty(sourcenode)) {
            button.classList.remove('d-none');
        } else {
            button.classList.add('d-none');
        }
    };

    // The file managers set their fill state asynchronously after they have loaded their files from the server.
    // Additionally, the fill state changes when the user uploads or removes a file. In both cases, the core file manager
    // toggles the fm-nofiles class on its container node. We observe that class attribute on both file managers and update
    // the button visibility accordingly. The class attribute lives on the stable container node (unlike the .fp-content
    // markup which is re-rendered), so this observation keeps working across uploads, removals and view mode changes.
    const observer = new MutationObserver(updateButtonVisibility);
    observer.observe(targetnode, {attributes: true, attributeFilter: ['class']});
    observer.observe(sourcenode, {attributes: true, attributeFilter: ['class']});

    // Do an initial visibility update (in case the file managers have already loaded).
    updateButtonVisibility();

    // Add the click handler to the button.
    button.addEventListener('click', async(e) => {
        // Prevent the default action.
        e.preventDefault();

        // Ask the user to confirm the action, using the button's own text as the label of the confirm button.
        // We deliberately do NOT wrap the confirmation dialog in a 'pending' promise: the dialog waits for user
        // interaction and wrapping it would make automated tests (Behat) time out while waiting for it to complete.
        try {
            await saveCancelPromise(
                button.dataset.confirmTitle,
                button.dataset.confirmQuestion,
                button.textContent.trim(),
                {triggerElement: button}
            );
        } catch (error) {
            // The user has cancelled the confirmation dialog (in which case the promise is rejected without an error)
            // or the dialog failed to open. We only log real errors and do nothing else.
            if (error) {
                Log.debug('Boost Union file manager copy button failed.');
                Log.debug(error);
            }
            return;
        }

        // The user has confirmed the action, so we copy the source file over to the target file manager. This is the
        // real asynchronous work, so we wrap it in a 'pending' promise to let automated tests wait for its completion.
        const pendingPromise = new Pending('theme_boost_union/filemanagercopybutton');
        try {
            await copyFile(sourceelementid, targetnode);
        } catch (error) {
            Log.debug('Boost Union file manager copy button failed.');
            Log.debug(error);
        } finally {
            pendingPromise.resolve();
        }
    });
};

/**
 * Entrypoint of the JS.
 *
 * Sets up all copy buttons which are present on the page. Each button is configured individually via its own data
 * attributes, so the module can handle any number of copy buttons.
 *
 * @method init
 */
export const init = () => {
    document.querySelectorAll(BUTTON_SELECTOR).forEach(setupButton);
};
