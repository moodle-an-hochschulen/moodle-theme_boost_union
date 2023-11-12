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
 * Theme Boost Union - Footer question mark button layout include.
 *
 * @package    theme_boost_union
 * @copyright  2023 Luca Bösch <luca.boesch@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$footersetting = get_config('theme_boost_union', 'enablefooterbutton');

// If the footer button is enabled.
$footerquestionmark = isset($footersetting) ? $footersetting : THEME_BOOST_UNION_SETTING_ENABLEFOOTER_ALL;
if ($footerquestionmark != THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE) {
    // Add marker to show the footer button to templatecontext.
    $templatecontext['footerbutton'] = true;
}
