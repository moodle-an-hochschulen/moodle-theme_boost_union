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
 * Theme Boost Union - Core renderer
 *
 * @package    theme_boost_union
 * @copyright  2022 Moodle an Hochschulen e.V. <kontakt@moodle-an-hochschulen.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\output;

/**
 * Extending the core_renderer interface.
 *
 * @package    theme_boost_union
 * @copyright  2022 Moodle an Hochschulen e.V. <kontakt@moodle-an-hochschulen.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Returns the moodle_url for the favicon.
     *
     * This renderer function is copied and modified from /lib/outputrenderers.php
     *
     * @since Moodle 2.5.1 2.6
     * @return moodle_url The moodle_url for the favicon
     */
    public function favicon() {
        if (!empty($this->page->theme->settings->favicon)) {
            return $this->page->theme->setting_file_url('favicon', 'favicon');
        } else {
            return $this->image_url('favicon', 'theme');
        }
    }

    /**
     * Returns HTML attributes to use within the body tag. This includes an ID and classes.
     *
     * This renderer function is copied and modified from /lib/outputrenderers.php
     *
     * @since Moodle 2.5.1 2.6
     * @param string|array $additionalclasses Any additional classes to give the body tag,
     * @return string
     */
    public function body_attributes($additionalclasses = array()) {
        global $CFG;

        // Require local library.
        require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

        if (!is_array($additionalclasses)) {
            $additionalclasses = explode(' ', $additionalclasses);
        }

        // If this isn't the login page and the page has a background image, add a class to the body attributes.
        if ($this->page->pagelayout != 'login') {
            if (!empty(get_config('theme_boost_union', 'backgroundimage'))) {
                $additionalclasses[] = 'backgroundimage';
            }
        }

        // If this is the login page and the page has a login background image, add a class to the body attributes.
        if ($this->page->pagelayout == 'login') {
            // Generate the background image class for displaying a random image for the login page.
            $loginimageclass = theme_boost_union_get_random_loginbackgroundimage_class();

            // If the background image class was returned, we can expect that a background image was set.
            // In this case, add both the general loginbackgroundimage class as well as the generated
            // class to the body tag.
            if ($loginimageclass != '') {
                $additionalclasses[] = 'loginbackgroundimage';
                $additionalclasses[] = $loginimageclass;
            }
        }

        return ' id="'. $this->body_id().'" class="'.$this->body_css_classes($additionalclasses).'"';
    }
}
