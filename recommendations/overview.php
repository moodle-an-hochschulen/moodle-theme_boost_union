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
 * Theme Boost Union - Recommendations overview page.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Require config.
require(__DIR__ . '/../../../config.php');

// Require plugin libraries.
require_once($CFG->dirroot . '/theme/boost_union/lib.php');
require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

// Require admin library.
require_once($CFG->libdir . '/adminlib.php');

// Get parameters.
$action = optional_param('action', null, PARAM_TEXT);
$id = optional_param('id', null, PARAM_ALPHANUMEXT);

// Get system context.
$context = context_system::instance();

// Access checks.
admin_externalpage_setup('theme_boost_union_recommendations');

// Prepare the page (to make sure that all necessary information is already set even if we just handle the actions as a start).
$PAGE->set_context($context);
$PAGE->set_url(new core\url('/theme/boost_union/recommendations/overview.php'));
$PAGE->set_cacheable(false);

// Process actions.
if ($action !== null && confirm_sesskey()) {
    // Every action is based on a recommendation, thus the recommendation ID param has to exist.
    $id = required_param('id', PARAM_ALPHANUMEXT);

    // The actions might be done with more than one DB statement which should have a monolithic effect, so we use a transaction.
    $transaction = $DB->start_delegated_transaction();

    // Handle mute/unmute actions.
    switch ($action) {
        case 'mute':
                \theme_boost_union\recommendation\manager::set_recommendation_muted($id, true);
                \core\notification::success(get_string('recommendationmutesuccess', 'theme_boost_union'));
            break;
        case 'unmute':
                \theme_boost_union\recommendation\manager::set_recommendation_muted($id, false);
                \core\notification::success(get_string('recommendationunmutesuccess', 'theme_boost_union'));
            break;
        case 'autofix':
                $recommendation = \theme_boost_union\recommendation\manager::get_recommendation_by_id($id);
            if ($recommendation !== null && $recommendation->is_autofixable()) {
                $recommendation->autofix();
                \core\notification::success(get_string('recommendationautofixsuccess', 'theme_boost_union'));
            }
            break;
    }

    // Allow to update the changes to database.
    $transaction->allow_commit();

    // Redirect to the same page.
    redirect($PAGE->url);
}

// Further prepare the page.
$PAGE->set_title(theme_boost_union_get_externaladminpage_title(get_string('recommendations', 'theme_boost_union')));
$PAGE->set_heading(theme_boost_union_get_externaladminpage_heading());

// Start page output.
echo $OUTPUT->header();
echo \theme_boost_union\admin_settingspage_tabs_with_tertiary::get_tertiary_navigation_for_externalpage();

// Show alert if Boost Union is not the active theme.
echo theme_boost_union_is_not_active_alert();

// Show recommendations intro.
$intro = new \core\output\notification(
    get_string('recommendations_desc', 'theme_boost_union'),
    \core\output\notification::NOTIFY_INFO
);
$intro->set_show_closebutton(false);
$intro->set_extra_classes(['alert-light']);
echo $OUTPUT->render($intro);

// Show the tables grouped by category.
$categories = \theme_boost_union\recommendation\manager::get_supported_categories();
foreach ($categories as $categorykey => $categorylabel) {
    // Build the recommendations table for this category.
    $table = new \theme_boost_union\table\recommendations_overview($categorykey);
    $table->define_baseurl($PAGE->url);

    // Show recommendations table for this category.
    $table->out(0, true);
}

// Finish page output.
echo $OUTPUT->footer();
