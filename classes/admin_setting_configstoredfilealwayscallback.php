<?php
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
 * Theme Boost Union - Settings class file
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

/**
 * Admin setting class which circumvents MDL-59082.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_setting_configstoredfilealwayscallback extends \admin_setting_configstoredfile {

    // This class is basically a admin_setting_configstoredfile class but which circumvents MDL-59082
    // and triggers the updatecallback everytime the setting is saved regardless if any values have changed.
    // As soon as MDL-59082 is fixed in Moodle core, this class could be removed again.
    // (Note to myself: This comment should be the class description,
    // but local_moodlecheck only allows one-line class descriptions).

    /**
     * Execute postupdatecallback.
     * @param mixed $original original value before write_setting()
     * @return bool true if changed, false if not.
     */
    public function post_write_settings($original) {
        // To circument MDL-59082, we have to make sure that in this function, the updatedcallback is
        // called in any case.
        //
        // This could be done by duplicating the code from the parent function and changing the
        // conditions under which the updatedcallback is called. However, we would have to keep the
        // duplicated code up to date.
        //
        // Alternatively, we could just call the updatedcallback here directly and then call the parent
        // function. This would come with the downside that the updatedcallback is called twice if the first
        // file in the filearea has changed (see MDL-59082 for details why this is happening like this).
        //
        // As this is a function just to be used in theme_boost_union for now, we accept this downside
        // and avoid managing duplicated code.

        // Call updatedcallback.
        $callbackfunction = $this->updatedcallback;
        if (!empty($callbackfunction) && function_exists($callbackfunction)) {
            $callbackfunction($this->get_full_name());
        }

        // Call parent function.
        parent::post_write_settings($original);

        // Return.
        return true;
    }
}
