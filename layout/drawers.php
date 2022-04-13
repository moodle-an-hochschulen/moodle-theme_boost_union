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
 * A drawer based layout for the Boost Union theme.
 *
 * @package   theme_boost_union
 * @copyright 2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @copyright based on code from theme_boost by Bas Brands
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');
// MODIFICATION Start: Require own locallib.php.
require_once($CFG->dirroot . '/theme/boost_union/locallib.php');
// MODIFICATION END.

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

// MODIFICATION START: Allow own user preference to be set via Javascript.
user_preference_allow_ajax_update('theme_boost_union_infobanner_dismissed', PARAM_BOOL);
// MODIFICATION END.
user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
user_preference_allow_ajax_update('drawer-open-index', PARAM_BOOL);
user_preference_allow_ajax_update('drawer-open-block', PARAM_BOOL);

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING')) {
    $blockdraweropen = true;
}

$extraclasses = ['uses-drawers'];
if ($courseindexopen) {
    $extraclasses[] = 'drawer-open-index';
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
if (!$hasblocks) {
    $blockdraweropen = false;
}
$courseindex = core_course_drawer();
if (!$courseindex) {
    $courseindexopen = false;
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

// MODIFICATION START: Set these variables in any case as it's needed in the columns2.mustache file.
$perpinfobannershowonselectedpage = false;
$timedinfobannershowonselectedpage = false;
// MODIFICATION END.

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindexopen' => $courseindexopen,
    'blockdraweropen' => $blockdraweropen,
    'courseindex' => $courseindex,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'perpinfobannershowonselectedpage' => $perpinfobannershowonselectedpage,
    'timedinfobannershowonselectedpage' => $timedinfobannershowonselectedpage,
];


// MODIFICATION START: Settings for perpetual information banner.
$perpibenable = get_config('theme_boost_union', 'perpibenable');

if ($perpibenable) {
    $formatoptions = array('noclean' => true, 'newlines' => false);
    $perpibcontent = format_text(get_config('theme_boost_union', 'perpibcontent'), FORMAT_HTML, $formatoptions);
    // Result of multiselect is a string divided by a comma, so exploding into an array.
    $perpibshowonpages = explode(",", get_config('theme_boost_union', 'perpibshowonpages'));
    $perpibcss = get_config('theme_boost_union', 'perpibcss');
    $perpibdismiss = get_config('theme_boost_union', 'perpibdismiss');
    $perbibconfirmdialogue = get_config('theme_boost_union', 'perpibconfirm');
    $perbibuserprefdialdismissed = get_user_preferences('theme_boost_union_infobanner_dismissed');

    $perpinfobannershowonselectedpage = theme_boost_union_show_banner_on_selected_page($perpibshowonpages,
        $perpibcontent, $PAGE->pagelayout, $perbibuserprefdialdismissed);

    // Add the variables to the templatecontext array.
    $templatecontext['perpibcontent'] = $perpibcontent;
    if ($perpibcss != 'none') {
        $templatecontext['perpibcss'] = $perpibcss;
    }
    $templatecontext['perpibdismiss'] = $perpibdismiss;
    $templatecontext['perpinfobannershowonselectedpage'] = $perpinfobannershowonselectedpage;
    $templatecontext['perbibconfirmdialogue'] = $perbibconfirmdialogue;
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

echo $OUTPUT->render_from_template('theme_boost_union/drawers', $templatecontext);
