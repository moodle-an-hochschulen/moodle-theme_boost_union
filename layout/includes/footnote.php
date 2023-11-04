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
 * Theme Boost Union - Footnote layout include.
 *
 * @package   theme_boost_union
 * @copyright 2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @copyright based on code from theme_boost by Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$footnotesetting = get_config('theme_boost_union', 'footnote');

// Only proceed if text area does not only contains empty tags.
if (!html_is_blank($footnotesetting)) {
    // Use format_text function to enable multilanguage filtering.
    $footnotesetting = format_text($footnotesetting, FORMAT_HTML, ['noclean' => true]);

    // Add marker to show the footnote to templatecontext.
    $templatecontext['showfootnote'] = true;

    // Add footnote to templatecontext.
    $templatecontext['footnotesetting'] = $footnotesetting;
}
