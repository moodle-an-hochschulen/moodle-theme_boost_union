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
 * Theme Boost Union - footer buttons include.
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$backtotopbutton = get_config('theme_boost_union', 'backtotopbutton');
$accessibilitysupportfooterbutton = get_config('theme_boost_union', 'enableaccessibilitysupportfooterbutton');

// Add footer buttons AMC module if a Boost Union footer button is enabled.
if ($backtotopbutton == THEME_BOOST_UNION_SETTING_SELECT_YES ||
        $accessibilitysupportfooterbutton == THEME_BOOST_UNION_SETTING_SELECT_YES) {
    $PAGE->requires->js_call_amd('theme_boost_union/footerbuttons', 'init');
}
