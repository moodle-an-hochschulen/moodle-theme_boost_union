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

namespace theme_boost_union\form;

use renderer_base;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/lib/form/editor.php');

/**
 * Form element for handling the colour picker.
 *
 * @package    theme_boost_union
 * @copyright  2023 Mario Wehr <m.wehr@fh-kaernten.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_boost_union_colourpicker_form_element extends \HTML_QuickForm_element implements \templatable {

    // String html for help button, if empty then no help.
    public $_helpbutton = '';

    /**
     * Class constructor
     *
     * @param    string     Name of the element
     * @param    mixed      Label(s) for the element
     * @param    mixed      Associative array of tag attributes or HTML attributes name="value" pairs
     * @since     1.0
     * @access    public
     * @return    void
     */
    public function __construct($elementname=null, $elemenlabel=null, $attributes=null) {
        parent::__construct($elementname, $elemenlabel, $attributes);
        $this->_type = 'static';
    }

    /**
     * Sets name of editor
     *
     * @param string $name name of element
     */
    // @codingStandardsIgnoreStart
    public function setName($name) {
        $this->updateAttributes(array('name' => $name));
    }
    // @codingStandardsIgnoreEnd
    /**
     * Returns name of element
     *
     * @return string
     */
    // @codingStandardsIgnoreStart
    function getName() {
        return $this->getAttribute('name');
    }
    // @codingStandardsIgnoreEnd
    /**
     * get html for help button
     *
     * @return string html for help button
     */
    // @codingStandardsIgnoreStart
    public function getHelpButton() {
        return $this->_helpbutton;
    }
    // @codingStandardsIgnoreEnd
    /**
     * Sets the value of the form element
     *
     * @param string $value
     */
    // @codingStandardsIgnoreStart
    public function setvalue($value) {
        $this->updateAttributes(array('value' => $value));
    }
    // @codingStandardsIgnoreEnd
    /**
     * Gets the value of the form element
     */
    public function getvalue() {
        return $this->getAttribute('value');
    }

    /**
     * Returns the html string to display this element.
     *
     * @return string
     */
    public function tohtml() {
        global $PAGE, $OUTPUT;

        $icon = new \pix_icon('i/loading', get_string('loading', 'admin'), 'moodle', ['class' => 'loadingicon']);
        $context = (object) [
            'icon' => $icon->export_for_template($OUTPUT),
            'name' => $this->getAttribute('name'),
            'id' => $this->getAttribute('id'),
            'value' => $this->getAttribute('value'),
            "readonly" => false,
            'haspreviewconfig' => false,
        ];
        $PAGE->requires->js_init_call('M.util.init_colour_picker', array($this->getAttribute('id'), null));
        return $OUTPUT->render_from_template('core_admin/setting_configcolourpicker', $context);
    }

    /**
     * Function to export the renderer data in a format that is suitable for a mustache template.
     *
     * @param \renderer_base $output Used to do a final render of any components that need to be rendered for export.
     * @return \stdClass|array
     */
    public function export_for_template(renderer_base $output) {
        $context['html'] = $this->toHtml();
        $context['id'] = $this->getAttribute('id');
        return $context;
    }
}

/**
 * Colour picker validation rule
 *
 * @package    theme_boost_union
 * @copyright  2023 Mario Wehr <m.wehr@fh-kaernten.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_boost_union_colourpicker_rule extends \HTML_QuickForm_Rule {

    /**
     * Validates the colour that was entered by the user
     *
     * @param string $value Value to check
     * @param int|string|array $options Not used yet
     * @return bool true if value is not empty
     */
    public function validate($value, $options = null) {

        // List of valid HTML colour names.
        $colornames = array(
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
            'whitesmoke', 'yellow', 'yellowgreen'
        );

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
