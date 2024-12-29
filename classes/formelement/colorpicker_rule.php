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
 * Theme Boost Union - Form element for color picker
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\formelement;

use HTML_QuickForm_Rule;

/**
 * Validation rule for color picker
 *
 * This class is copied and modified from admin_setting_configcolourpicker in /lib/adminlib.php.
 *
 * @package   theme_boost_union
 * @copyright 2023 Mario Wehr <m.wehr@fh-kaernten.at>
 *            based on code 2010 by Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class colorpicker_rule extends HTML_QuickForm_Rule {
    /**
     * Validates the colour that was entered by the user.
     *
     * @param string $value Value to check
     * @param int|string|array $options Not used yet
     * @return bool true if value is not empty
     */
    public function validate($value, $options = null) {

        // List of valid HTML colour names.
        $colornames = [
            'aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure',
            'beige', 'bisque', 'black', 'blanchedalmond', 'blue',
            'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse',
            'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson',
            'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray',
            'darkgrey', 'darkgreen', 'darkkhaki', 'darkmagenta',
            'darkolivegreen', 'darkorange', 'darkorchid', 'darkred',
            'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray',
            'darkslategrey', 'darkturquoise', 'darkviolet', 'deeppink',
            'deepskyblue', 'dimgray', 'dimgrey', 'dodgerblue', 'firebrick',
            'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro',
            'ghostwhite', 'gold', 'goldenrod', 'gray', 'grey', 'green',
            'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo',
            'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen',
            'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan',
            'lightgoldenrodyellow', 'lightgray', 'lightgrey', 'lightgreen',
            'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue',
            'lightslategray', 'lightslategrey', 'lightsteelblue', 'lightyellow',
            'lime', 'limegreen', 'linen', 'magenta', 'maroon',
            'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple',
            'mediumseagreen', 'mediumslateblue', 'mediumspringgreen',
            'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream',
            'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive',
            'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod',
            'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip',
            'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red',
            'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown',
            'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue',
            'slategray', 'slategrey', 'snow', 'springgreen', 'steelblue', 'tan',
            'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white',
            'whitesmoke', 'yellow', 'yellowgreen',
        ];

        if (preg_match('/^#?([[:xdigit:]]{3}){1,2}$/', $value)) {
            if (strpos($value, '#') !== 0) {
                $value = '#'.$value;
            }
            return $value;
        } else if (in_array(strtolower($value), $colornames)) {
            return $value;
        } else if (preg_match('/rgb\(\d{0,3}%?, ?\d{0,3}%?, ?\d{0,3}%?\)/i', $value)) {
            return $value;
        } else if (preg_match('/rgba\(\d{0,3}%?, ?\d{0,3}%?, ?\d{0,3}%?, ?\d(\.\d)?\)/i', $value)) {
            return $value;
        } else if (preg_match('/hsl\(\d{0,3}, ?\d{0,3}%, ?\d{0,3}%\)/i', $value)) {
            return $value;
        } else if (preg_match('/hsla\(\d{0,3}, ?\d{0,3}%,\d{0,3}%, ?\d(\.\d)?\)/i', $value)) {
            return $value;
        } else if (($value == 'transparent') || ($value == 'currentColor') || ($value == 'inherit')) {
            return $value;
        } else {
            return false;
        }
    }
}
