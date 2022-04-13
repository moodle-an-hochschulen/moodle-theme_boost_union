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
 * A login page layout for the Boost Union theme.
 *
 * @package   theme_boost_union
 * @copyright 2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @copyright based on code from theme_boost by Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// MODIFICATION Start: Require own locallib.php.
require_once($CFG->dirroot . '/theme/boost_union/locallib.php');
// MODIFICATION END.

$bodyattributes = $OUTPUT->body_attributes();

// MODIFICATION START: Set these variables in any case as it's needed in the columns2.mustache file.
$perpinfobannershowonselectedpage = false;
$timedinfobannershowonselectedpage = false;
// MODIFICATION END.

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'perpinfobannershowonselectedpage' => $perpinfobannershowonselectedpage,
    'timedinfobannershowonselectedpage' => $timedinfobannershowonselectedpage
];

// MODIFICATION START: Settings for information banner.
$perpibenable = get_config('theme_boost_union', 'perpibenable');

if ($perpibenable) {
    $formatoptions = array('noclean' => true, 'newlines' => false);
    $perpibcontent = format_text(get_config('theme_boost_union', 'perpibcontent'), FORMAT_HTML, $formatoptions);
    // Result of multiselect is a string divided by a comma, so exploding into an array.
    $perpibshowonpages = explode(",", get_config('theme_boost_union', 'perpibshowonpages'));
    $perpibcss = get_config('theme_boost_union', 'perpibcss');

    $perpinfobannershowonselectedpage = theme_boost_union_show_banner_on_selected_page($perpibshowonpages,
        $perpibcontent, $PAGE->pagelayout, false);

    // Add the variables to the templatecontext array.
    $templatecontext['perpibcontent'] = $perpibcontent;
    if ($perpibcss != 'none') {
        $templatecontext['perpibcss'] = $perpibcss;
    }
    $templatecontext['perpinfobannershowonselectedpage'] = $perpinfobannershowonselectedpage;
}
// MODIFICATION END.

// MODIFICATION START: Settings for time controlled information banner.
$timedibenable = get_config('theme_boost_union', 'timedibenable');

if ($timedibenable) {
    $formatoptions = array('noclean' => true, 'newlines' => false);
    $timedibcontent = format_text(get_config('theme_boost_union', 'timedibcontent'), FORMAT_HTML, $formatoptions);
    // Result of multiselect is a string divided by a comma, so exploding into an array.
    $timedibshowonpages = explode(",", get_config('theme_boost_union', 'timedibshowonpages'));
    $timedibcss = get_config('theme_boost_union', 'timedibcss');
    $timedibstartsetting = get_config('theme_boost_union', 'timedibstart');
    $timedibendsetting = get_config('theme_boost_union', 'timedibend');
    // Get the current server time.
    $now = (new DateTime("now", core_date::get_server_timezone_object()))->getTimestamp();

    $timedinfobannershowonselectedpage = theme_boost_union_show_timed_banner_on_selected_page($now, $timedibshowonpages,
        $timedibcontent, $timedibstartsetting, $timedibendsetting, $PAGE->pagelayout);

    // Add the variables to the templatecontext array.
    $templatecontext['timedibcontent'] = $timedibcontent;
    if ($timedibcss != 'none') {
        $templatecontext['timedibcss'] = $timedibcss;
    }
    $templatecontext['timedinfobannershowonselectedpage'] = $timedinfobannershowonselectedpage;
}
// MODIFICATION END.
echo $OUTPUT->render_from_template('theme_boost_union/login', $templatecontext);
