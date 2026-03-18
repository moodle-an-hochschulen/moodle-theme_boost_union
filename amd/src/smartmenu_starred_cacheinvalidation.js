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
 * Theme Boost Union - JS for client-side invalidation of smart menu starred-courses cache.
 *
 * @module     theme_boost_union/smartmenu_starred_cacheinvalidation
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Ajax from 'core/ajax';
import {subscribe} from 'core/pubsub';
import * as CourseEvents from 'core_course/events';
import Log from 'core/log';

// Flag to remember if the module has already been initialized.
let initialized = false;

// Flag to remember if a cache invalidation is currently running.
let invalidationrunning = false;

const invalidateSmartmenuCache = async() => {
    // Prevent multiple simultaneous invalidation calls,
    // which could happen if a user stars/unstars multiple courses in a short time.
    if (invalidationrunning) {
        return;
    }

    // Set the flag to indicate that an invalidation is currently running.
    invalidationrunning = true;

    // Call the server to invalidate the cache for the starred courses smart menu items for this user.
    try {
        await Ajax.call([{
            methodname: 'theme_boost_union_smartmenus_clear_starredcourses_cache',
            args: {},
        }])[0];
    } catch (error) {
        Log.debug('Boost Union smart menu cache invalidation failed.');
        Log.debug(error);
    } finally {
        invalidationrunning = false;
    }
};

/**
 * Entrypoint of the JS.
 *
 * @method init
 */
export const init = () => {
    // Prevent multiple initializations,
    // which could lead to multiple event handlers being registered and thus multiple cache invalidation calls
    // for a single starring/unstarring action.
    if (initialized) {
        return;
    }

    // Set the flag to prevent future multiple initializations.
    initialized = true;

    // Register event handlers for course starring and unstarring events
    // to invalidate the smart menu cache when these actions occur.
    subscribe(CourseEvents.favourited, invalidateSmartmenuCache);
    subscribe(CourseEvents.unfavorited, invalidateSmartmenuCache);
};
