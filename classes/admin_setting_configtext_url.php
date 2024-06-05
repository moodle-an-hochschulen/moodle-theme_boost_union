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
 * @copyright  2023 Lukas MuLu Müller, lern.link GmbH <mulu@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

/**
 * Special setting for configtextarea with URL validation.
 *
 * @package    theme_boost_union
 * @copyright  2023 Lukas MuLu Müller, lern.link GmbH <mulu@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_setting_configtext_url extends \admin_setting_configtext {

    /**
     * Validate the contents of the configtext to ensure it's a valid URL.
     *
     * @param string $data The data from text field.
     * @return mixed bool true for success or string:error on failure.
     */
    public function validate($data) {
        global $CFG;

        // If no entry is made validate true, otherwise the setting would always return an error and
        // could not be saved.
        if (empty($data)) {
            return true;
        }

        // Require file library.
        require_once($CFG->libdir.'/filelib.php');

        // If the URL is invalid, respond with an error message.
        if (filter_var($data, FILTER_VALIDATE_URL) === false) {
            return get_string('invalidurl', 'theme_boost_union');
        } else {
            // Check if the URL is blocked.
            $curl = new \curl();
            $security = $curl->get_security();

            // If the URL is blocked, return the reason as error message.
            if ($security->url_is_blocked($data)) {
                return $security->get_blocked_url_string();
            }
        }

        // Return the result of the parent class' check of the data.
        return parent::validate($data);
    }
}
