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
 * Class theme_boost_union_core_h5p_renderer for core(h5p) and additional activity(hvp).
 *
 * Extends the H5P renderer/ HVP renderer so that we are able to override the relevant
 * functions declared there.
 * @package theme_boost_union
 * @copyright 2022 Nina Herrmann <nina.herrmann@gmx.de>
 * @copyright on behalf of Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

// If hvp is installed, we will create the second renderer class.
if (file_exists($CFG->dirroot . '/mod/hvp/version.php')) {
    require_once($CFG->dirroot . '/mod/hvp/renderer.php');
}

/**
 * Renderer for core h5p activity.
 * @package theme_boost_union
 * @copyright 2022 Nina Herrmann <nina.herrmann@gmx.de>
 * @copyright on behalf of Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_boost_union_core_h5p_renderer extends \core_h5p\output\renderer {

    /**
     * Add styles when an H5P is displayed.
     *
     * @param array $styles Styles that will be applied.
     * @param array $libraries Libraries that wil be shown.
     * @param string $embedtype How the H5P is displayed.
     */
    public function h5p_alter_styles(&$styles, $libraries, $embedtype) {
        // Build the h5p CSS file URL.
        $h5pcssurl = new moodle_url('/theme/boost_union/h5p/styles.php');

        $styles[] = (object) array(
            'path'    => $h5pcssurl->out(),
            'version' => '?ver=' . theme_get_revision(),
        );
    }
}
if (class_exists('mod_hvp_renderer')) {
    /**
     * Class theme_h5pmod_mod_hvp_renderer
     *
     * Extends the HVP renderer so that we are able to override the relevant
     * functions declared there
     * @package theme_boost_union
     * @copyright 2022 Nina Herrmann <nina.herrmann@gmx.de>
     * @copyright on behalf of Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
     * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */
    class theme_boost_union_mod_hvp_renderer extends mod_hvp_renderer {

        /**
         * Add styles when an H5(V)P is displayed.
         *
         * @param array $styles Styles that will be applied.
         * @param array $libraries Libraries that wil be shown.
         * @param string $embedtype How the H5P is displayed.
         */
        public function hvp_alter_styles(&$styles, $libraries, $embedtype) {
            // Build the hvp CSS file URL.
            $h5pcssurl = new moodle_url('/theme/boost_union/h5p/styles.php');

            $styles[] = (object)array(
                'path' => $h5pcssurl->out(),
                'version' => '?ver=' . theme_get_revision(),
            );
        }
    }
}
