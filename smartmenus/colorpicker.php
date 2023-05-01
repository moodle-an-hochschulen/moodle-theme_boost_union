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
 * File contains definition of class MoodleQuickForm_boostunioncolorpicker
 *
 * @package    theme_boost_union
 * @copyright  bdecent GmbH 2021
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("HTML/QuickForm/input.php");

/**
 * Form element for handling colorpicker
 *
 * @package   theme_boost_union
 * @copyright bdecent GmbH 2021
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class moodlequickform_boostunioncolorpicker extends HTML_QuickForm_input {

    /** @var bool if true label will be hidden */
    public $_helpbutton = '';

    /** @var bool if true label will be hidden */
    public $_hiddenlabel = false;

    /** @var bool Whether to force the display of this element to flow LTR. */
    protected $forceltr = false;

    /**
     * Sets label to be hidden
     *
     * @param bool $hiddenlabel sets if label should be hidden
     */
    public function sethiddenlabel($hiddenlabel) {
        $this->_hiddenlabel = $hiddenlabel;
    }

    /**
     * Get force LTR option.
     *
     * @return bool
     */
    public function get_force_ltr() {
        return $this->forceltr;
    }

    /**
     * get html for help button
     *
     * @return string html for help button
     */
    public function gethelpbutton() {
        return $this->_helpbutton;
    }

    /**
     * Force the field to flow left-to-right.
     *
     * This is useful for fields such as URLs, passwords, settings, etc...
     *
     * @param bool $value The value to set the option to.
     */
    public function set_force_ltr($value) {
        $this->forceltr = (bool) $value;
    }

     /**
      * Returns HTML for this form element.
      *
      * @return string
      */
    public function toHtml() {
        global $PAGE, $OUTPUT;
        $icon = new pix_icon('i/loading', get_string('loading', 'admin'), 'moodle', ['class' => 'loadingicon']);
        $template = (object) [
            'id' => $this->getAttribute('id'),
            'name' => $this->getAttribute('name'),
            'value' => $this->getAttribute('value'),
            'icon' => $icon->export_for_template($OUTPUT),
            'haspreviewconfig' => '',
            'forceltr' => $this->get_force_ltr(),
            'readonly' => '',
        ];
        $colorpicker = $OUTPUT->render_from_template('core_admin/setting_configcolourpicker', $template);
        $context = $template;
        $context->colorpicker = $colorpicker;
        $context->lable = $this->getLabel();
        $context->type = 'colorpicker';
        $PAGE->requires->js_init_call('M.util.init_colour_picker', array($this->getAttribute('id'), ''));
        return $OUTPUT->render_from_template('theme_boost_union/element_colorpicker', $context);
    }
}
