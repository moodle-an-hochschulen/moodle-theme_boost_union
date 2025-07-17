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
 * File defining the after_config hook logic.
 *
 * @package    theme_boost_union
 * @copyright  2023 Daniel Poggenpohl <daniel.poggenpohl@fernuni-hagen.de> and Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\local\hook;

/**
 * Class defining the after_config logic.
 */
class after_config {
    /**
     * Executes additional configuration after theme setup.
     */
    public static function callback(): void {
        global $PAGE, $CFG;

        $url = qualified_me();
        $url = str_replace($CFG->wwwroot, '', $url);
        $parts = explode('?', $url);
        $url = $parts[0];
        $params = $parts[1] ?? '';
        $parmarray = [];

        if (!empty($params)) {
            $params = explode('#', $params)[0];
            $params = explode('&', $params);
            foreach ($params as $param) {
                [$name, $value] = explode('=', $param);
                $parmarray[$name] = $value;
            }
        }

        if ($url == '/course/view.php') {
            if (array_key_exists('section', $parmarray)) {
                $jsparams = [
                    'courseid' => $parmarray['id'],
                    'section'  => $parmarray['section'],
                ];
                $PAGE->requires->js_call_amd('theme_boost_union/sectionnav', 'init', $jsparams);
            }
        }
    }
}
