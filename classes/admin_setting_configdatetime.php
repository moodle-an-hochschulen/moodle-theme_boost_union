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

use core_date;
use html_writer;
use MoodleQuickForm;

/**
 * Class used for picking a date and time.
 *
 * This admin setting renders Moodle core's mform date_time_selector widget and stores the picked date and time
 * as unix timestamp.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_setting_configdatetime extends \admin_setting {
    /** @var string|null The name of another setting of the same plugin whose date and time this date and time must not precede. */
    private $notbeforesetting = null;

    /** @var string|\lang_string|null The error message to show if this date and time precedes the other setting's one. */
    private $notbeforeerror = null;

    /** @var array The conditions (setting name => value) which must all be met for the not-before check to apply. */
    private $notbeforeconditions = [];

    /**
     * Require that this setting's date and time is not before the date and time of another setting of the same plugin.
     *
     * The check is done when the setting is written. It compares this setting's new date and time with the other setting's
     * currently stored date and time. If both settings are saved within the same admin settings form submission, the other
     * setting has to be placed before this setting on the settings page so that it is written first.
     * The check is skipped if the other setting is not set (yet).
     *
     * Optionally, the check can be restricted to the case that other settings of the same plugin have particular values
     * (e.g. that a feature is enabled at all), similar to the hide_if() dependencies of the settings page. These settings
     * have to be placed before this setting on the settings page as well. Without this restriction, a stored date and time
     * which violates the check would raise an error on each submission of the settings page, even if the setting is hidden.
     *
     * @param string $settingname The name of the other setting (without the plugin prefix).
     * @param string|\lang_string $errormessage The error message to show if the check fails.
     * @param array $conditions The conditions (setting name without the plugin prefix => value) which must all be met
     *                          for the check to apply.
     * @return void
     */
    public function set_not_before(string $settingname, $errormessage, array $conditions = []): void {
        $this->notbeforesetting = $settingname;
        $this->notbeforeerror = $errormessage;
        $this->notbeforeconditions = $conditions;
    }

    /**
     * Get the stored date and time.
     *
     * @return int|null The stored unix timestamp or null if the setting is not set yet.
     */
    public function get_setting() {
        $result = $this->config_read($this->name);

        // If the setting is not set yet.
        if ($result === null) {
            return null;
        }

        // The setting is stored as a unix timestamp, with 0 meaning that no date and time is set.
        return (int) $result;
    }

    /**
     * Store the date and time as unix timestamp.
     *
     * @param array|int|string $data The submitted date_time_selector data, i.e. an array with the keys
     *                               'year', 'month', 'day', 'hour' and 'minute', or the default setting,
     *                               i.e. a unix timestamp or 0 if no date and time is set.
     * @return string Empty string if everything is ok, string error message otherwise.
     */
    public function write_setting($data) {
        // If the given data is not an array, it was not submitted with a date_time_selector.
        // In this case, it is most probably the default setting which is applied by Moodle core during installations
        // and upgrades. The default setting has to be stored as well. Otherwise, the setting would remain unset, Moodle core would
        // consider it as new setting on each and every upgrade and would try to apply the default setting again and again.
        if (!is_array($data)) {
            // A numeric default setting is stored as unix timestamp.
            // Any other default setting is stored as 0 which means that no date and time is set.
            $timestamp = is_numeric($data) ? (int) $data : 0;

            // Otherwise, the data was submitted with a date_time_selector.
        } else {
            // Make sure that all date and time parts have been submitted.
            foreach (['year', 'month', 'day', 'hour', 'minute'] as $key) {
                if (!isset($data[$key]) || !is_numeric($data[$key])) {
                    return get_string('errorsetting', 'admin');
                }
            }

            // Let the date_time_selector element convert the submitted data to a unix timestamp.
            $element = $this->create_element();
            $submitvalues = [$this->get_full_name() => $data];
            $timestamp = $element->exportValue($submitvalues);
            if (!is_numeric($timestamp)) {
                return get_string('errorsetting', 'admin');
            }
            $timestamp = (int) $timestamp;
        }

        // If this date and time must not be before the date and time of another setting, check this.
        // This check is done regardless of the way the data was given, as a non-array value is not necessarily the default
        // setting but might also have been submitted with a hand-crafted request to the admin settings form.
        // It is only skipped if no date and time is set at all.
        if ($timestamp !== 0 && $this->notbeforesetting !== null && $this->not_before_conditions_met()) {
            $other = $this->config_read($this->notbeforesetting);
            if (is_numeric($other) && (int) $other > 0 && $timestamp < (int) $other) {
                return (string) $this->notbeforeerror;
            }
        }

        // Store the unix timestamp.
        $result = $this->config_write($this->name, $timestamp);

        return ($result ? '' : get_string('errorsetting', 'admin'));
    }

    /**
     * Checks if all conditions for the not-before check are met.
     *
     * @return bool True if all conditions are met (or if there are no conditions), false otherwise.
     */
    private function not_before_conditions_met(): bool {
        foreach ($this->notbeforeconditions as $settingname => $value) {
            if ((string) $this->config_read($settingname) !== (string) $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the HTML of the date_time_selector widget.
     *
     * @param int|string|array $data The stored unix timestamp or, if the form was submitted but could not be saved,
     *                               the submitted date_time_selector data array.
     * @param string $query
     * @return string The date_time_selector widget wrapped in the admin setting markup.
     */
    public function output_html($data, $query = '') {
        global $OUTPUT;

        // Compose the default setting info.
        $default = $this->get_defaultsetting();
        if (is_numeric($default) && (int) $default > 0) {
            $defaultinfo = userdate(
                (int) $default,
                get_string('strftimedatetime', 'langconfig'),
                core_date::get_server_timezone()
            );
        } else {
            $defaultinfo = null;
        }

        // Build the date_time_selector element with the given data.
        $element = $this->create_element($data);

        // Render the sub-elements of the element (the date and time selects and the calendar button)
        // with their Moodle core templates, just like Moodle core does it in its date_time_selector form element template.
        // This also initializes the JavaScript for the calendar popup.
        // Just like the mform renderer does it, generate the id attribute of the element first.
        $element->_generateId();
        $context = $element->export_for_template($OUTPUT);
        $elementshtml = '';
        foreach ($context['elements'] as $subelement) {
            $elementshtml .= $subelement['separator'] . $subelement['html'];
        }

        // Wrap the sub-elements within the same markup which Moodle core uses in its date_time_selector form element template.
        // This markup is needed for the calendar popup JavaScript as well as for the Behat date_time field type detection.
        $return = html_writer::start_tag(
            'fieldset',
            ['id' => $this->get_id(), 'class' => 'm-0 p-0 border-0', 'data-fieldtype' => 'date_time']
        );
        $return .= html_writer::tag('legend', $this->visiblename, ['class' => 'sr-only']);
        $return .= html_writer::div($elementshtml, 'fdate_time_selector d-flex flex-wrap align-items-center');
        $return .= html_writer::end_tag('fieldset');

        return format_admin_setting($this, $this->visiblename, $return, $this->description, false, '', $defaultinfo, $query);
    }

    /**
     * Creates the date_time_selector element.
     *
     * The element is created within a throwaway form instance. The form itself will never be rendered, but the element
     * has to be added to a form so that it can create its sub-elements and run its standard lifecycle, i.e. converting
     * the given unix timestamp (or submitted data) to its sub-element values.
     *
     * @param int|string|array|null $data The unix timestamp or the submitted date_time_selector data array to be set as
     *                                    element value, or null if no value should be set.
     * @return \MoodleQuickForm_date_time_selector The element.
     */
    protected function create_element($data = null): \MoodleQuickForm_date_time_selector {
        global $CFG;

        // Require formslib for the date_time_selector element.
        require_once($CFG->libdir . '/formslib.php');

        $elementname = $this->get_full_name();
        $mform = new MoodleQuickForm('theme_boost_union_configdatetime', 'post', '');
        if ($data !== null) {
            $mform->setDefault($elementname, $data);
        }

        return $mform->addElement('date_time_selector', $elementname, $this->visiblename, [
            // The date and time is interpreted as server time, not as user time, just as it has always been
            // (and as the setting descriptions promise).
            'timezone' => core_date::get_server_timezone(),
        ]);
    }
}
