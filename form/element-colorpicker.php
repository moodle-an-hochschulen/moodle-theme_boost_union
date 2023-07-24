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
 * Theme Boost Union Login - Form element for color picker
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once('HTML/QuickForm/input.php');
require_once($CFG->dirroot.'/lib/form/templatable_form_element.php');
require_once($CFG->dirroot.'/lib/form/text.php');

/**
 * Form element for color picker
 *
 * @package   theme_boost_union
 * @copyright bdecent GmbH 2021
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class moodlequickform_themeboostunion_colorpicker extends MoodleQuickForm_text implements templatable {

    use templatable_form_element {
        export_for_template as export_for_template_base;
    }

    /**
     * constructor
     *
     * @param string $elementname (optional) name of the text field
     * @param string $elementlabel (optional) text field label
     * @param string $attributes (optional) Either a typical HTML attribute string or an associative array
     */
    public function __construct($elementname=null, $elementlabel=null, $attributes=null) {
        parent::__construct($elementname, $elementlabel, $attributes);
        $this->setType('colorpicker');

        // Add the class admin_colourpicker.
        $class = $this->getAttribute('class');
        if (empty($class)) {
            $class = '';
        }
        $this->updateAttributes(array('class' => $class . ' union-form-colour-picker '));
    }

    /**
     * Export for template
     *
     * @param renderer_base $output
     * @return array|stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE;
        // Compose template context for Mform element.
        $context = $this->export_for_template_base($output);
        // Build loading icon.
        $icon = new pix_icon('i/loading', get_string('loading', 'admin'), 'moodle', ['class' => 'loadingicon']);
        $context['icon'] = $icon->export_for_template($output);
        // Add JS init call to page.
        $PAGE->requires->js_init_call('M.util.init_colour_picker', array($this->getAttribute('id'), ''));

        return $context;
    }
}
