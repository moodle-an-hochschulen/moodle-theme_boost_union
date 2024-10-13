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

/**
 * Class used for picking a date and time.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @copyright  based on code by Valery Fremaux (valery.fremaux@gmail.com) in block_use_stats.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Valery's original class comment:
 * This is a liiitle bit messy. We're using two selects, but we're returning
 * them as an array named after $name (so we only use $name2 internally for the setting)
 */
class admin_setting_configdatetime extends \admin_setting {

    /**
     * Get the selected time.
     *
     * @return mixed An array containing 'h'=>xx, 'm'=>xx, or null if not set
     */
    public function get_setting() {
        $result = $this->config_read($this->name);

        $datearr = getdate($result);

        $data = ['h' => $datearr['hours'],
                'm' => $datearr['minutes'],
                'y' => $datearr['year'],
                'M' => $datearr['mon'],
                'd' => $datearr['mday'], ];
        return $data;
    }

    /**
     * Store the time as unix timestamp.
     *
     * @param array $data Must be form 'y' => xxxx, 'M' => xx, 'd' => xx, 'h'=>xx, 'm'=>xx
     * @return bool true if success, false if not
     */
    public function write_setting($data) {
        if (!is_array($data)) {
            return '';
        }

        $datetime = mktime($data['h'], $data['m'], 0, $data['M'], $data['d'], $data['y']);
        $result = $this->config_write($this->name, $datetime);

        return ($result ? '' : get_string('errorsetting', 'admin'));
    }

    /**
     * Returns XHTML time select fields.
     *
     * @param array $data Must be form 'h'=>xx, 'm'=>xx
     * @param string $query
     * @return string XHTML time select fields and wrapping div(s)
     */
    public function output_html($data, $query = '') {
        $default = $this->get_defaultsetting();

        if (is_array($default)) {
            $defaultinfo = $default['y'].'-'.$default['M'].'-'.$default['d'].' '.$default['h'].':'.$default['m'];
        } else {
            $defaultinfo = null;
        }

        $return = '<div class="form-datetime defaultsnext">';

        $return .= '<select id="'.$this->get_id().'y" name="'.$this->get_full_name().'[y]" class="custom-select me-2">';
        for ($i = 2020; $i <= date("Y") + 10; $i++) {
            $return .= '<option value="'.$i.'"'.($i == $data['y'] ? ' selected="selected"' : '').'>'.$i.'</option>';
        }
        $return .= '</select>';

        $return .= '<select id="'.$this->get_id().'M" name="'.$this->get_full_name().'[M]" class="custom-select me-2">';
        for ($i = 1; $i <= 12; $i++) {
            $sel = ($i == $data['M'] ? ' selected="selected"' : '');
            $dateobj = \DateTime::createFromFormat('!m', $i);
            $return .= '<option value="'.$i.'"'.$sel.'>'.userdate($dateobj->getTimestamp(), '%B').'</option>';
        }
        $return .= '</select>';

        $return .= '<select id="'.$this->get_id().'d" name="'.$this->get_full_name().'[d]" class="custom-select me-2">';
        for ($i = 1; $i <= 31; $i++) {
            $sel = ($i == $data['d'] ? ' selected="selected"' : '');
            $return .= '<option value="'.$i.'"'.$sel.'>'.sprintf('%02d', $i).'</option>';
        }
        $return .= '</select>';

        $return .= '<select id="'.$this->get_id().'h" name="'.$this->get_full_name().'[h]" class="custom-select me-1">';
        for ($i = 0; $i < 24; $i++) {
            $sel = ($i == $data['h'] ? ' selected="selected"' : '');
            $return .= '<option value="'.$i.'"'.$sel.'>'.sprintf('%02d', $i).'</option>';
        }
        $return .= '</select>';

        $return .= '<span class="me-1">:</span>';

        $return .= '<select id="'.$this->get_id().'m" name="'.$this->get_full_name().'[m]" class="custom-select me-2">';
        for ($i = 0; $i < 60; $i += 5) {
            $sel = ($i == $data['m'] ? ' selected="selected"' : '');
            $return .= '<option value="'.$i.'"'.$sel.'>'.sprintf('%02d', $i).'</option>';
        }
        $return .= '</select>';

        $return .= '</div>';

        return format_admin_setting($this, $this->visiblename, $return, $this->description, false, '', $defaultinfo, $query);
    }
}
