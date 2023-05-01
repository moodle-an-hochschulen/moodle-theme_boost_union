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
 * Theme Boost Union - Flavours delete form
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @copyright  based on code by bdecent gmbh <https://bdecent.de> in format_kickstart.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\form;

defined('MOODLE_INTERNAL') || die();

// Require forms library.
require_once($CFG->libdir.'/formslib.php');

/**
 * Flavours delete form.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @copyright  based on code by bdecent gmbh <https://bdecent.de> in format_kickstart.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class flavour_delete_form extends \moodleform {

    /**
     * Define form elements.
     *
     * @throws \coding_exception
     */
    public function definition() {
        global $OUTPUT;

        // Get an easier handler for the form.
        $mform = $this->_form;

        // Add the flavour ID as hidden element.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        // Add notification as html element.
        $notification = new \core\output\notification(
                get_string('flavoursdeleteconfirmation', 'theme_boost_union', $this->_customdata['flavour']->title),
                \core\output\notification::NOTIFY_WARNING);
        $notification->set_show_closebutton(false);
        $mform->addElement('html', $OUTPUT->render($notification));

        // Add the action buttons.
        $this->add_action_buttons(true, get_string('delete'));
    }
}
