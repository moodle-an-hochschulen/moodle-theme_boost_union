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
 * Theme Boost Union - Flavours overview page
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @copyright  based on code by bdecent gmbh <https://bdecent.de> in format_kickstart.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Require config.
require(__DIR__.'/../../../config.php');

// Require plugin libraries.
require_once($CFG->dirroot.'/theme/boost_union/lib.php');
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');
require_once($CFG->dirroot.'/theme/boost_union/flavours/flavourslib.php');

// Require admin library.
require_once($CFG->libdir.'/adminlib.php');

// Get parameters.
$action = optional_param('action', '', PARAM_TEXT);
$flavourid = optional_param('id', '', PARAM_INT);

// Get system context.
$context = context_system::instance();

// Prepare the page (to make sure that all necessary information is already set even if we just handle the actions as a start).
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/theme/boost_union/flavours/overview.php'));
$PAGE->set_cacheable(false);

// Process sort action.
if ($action && $flavourid) {
    // Sort 'up' action.
    if ($action == 'up') {
        $currentflavour = $DB->get_record('theme_boost_union_flavours', array('id' => $flavourid));
        $prevflavour = $DB->get_record('theme_boost_union_flavours', array('sort' => $currentflavour->sort - 1));
        if ($prevflavour) {
            // The sorting is done with two DB statements which should have a monolithic effect,
            // so we use a transaction.
            $transaction = $DB->start_delegated_transaction();
            $DB->set_field('theme_boost_union_flavours', 'sort', $prevflavour->sort,
                    array('id' => $currentflavour->id));
            $DB->set_field('theme_boost_union_flavours', 'sort', $currentflavour->sort,
                    array('id' => $prevflavour->id));
            $transaction->allow_commit();

            // Purge the flavours cache as the users might get other flavours which apply after the sorting.
            // We would have preferred using cache_helper::purge_by_definition, but this just purges the session cache
            // of the current user and not for all users.
            cache_helper::purge_by_event('theme_boost_union_flavours_resorted');
        }

        // Sort 'down' action.
    } else if ($action = "down") {
        $currentflavour = $DB->get_record('theme_boost_union_flavours', array('id' => $flavourid));
        $nextflavour = $DB->get_record('theme_boost_union_flavours', array('sort' => $currentflavour->sort + 1));
        if ($nextflavour) {
            // The sorting is done with two DB statements which should have a monolithic effect,
            // so we use a transaction.
            $transaction = $DB->start_delegated_transaction();
            $DB->set_field('theme_boost_union_flavours', 'sort', $nextflavour->sort,
                    array('id' => $currentflavour->id));
            $DB->set_field('theme_boost_union_flavours', 'sort', $currentflavour->sort,
                    array('id' => $nextflavour->id));
            $transaction->allow_commit();

            // Purge the flavours cache as the users might get other flavours which apply after the sorting.
            // We would have preferred using cache_helper::purge_by_definition, but this just purges the session cache
            // of the current user and not for all users.
            cache_helper::purge_by_event('theme_boost_union_flavours_resorted');
        }
    }

    // Redirect to the same page.
    redirect($PAGE->url);
}

// Access checks.
admin_externalpage_setup('theme_boost_union_flavours');

// Further prepare the page.
$PAGE->set_title(theme_boost_union_get_externaladminpage_title(get_string('flavoursflavours', 'theme_boost_union')));
$PAGE->set_heading(theme_boost_union_get_externaladminpage_heading());

// Build flavours table.
$table = new \theme_boost_union\flavours_overview_table();
$table->define_baseurl($PAGE->url);

// Start page output.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('configtitleflavours', 'theme_boost_union'));

// Show flavours description.
echo get_string('flavoursoverview_desc', 'theme_boost_union');

// Prepare 'Create flavours' button.
$createbutton = $OUTPUT->box_start();
$createbutton .= $OUTPUT->single_button(
        new \moodle_url('/theme/boost_union/flavours/edit.php', ['action' => 'create']),
        get_string('flavourscreateflavour', 'theme_boost_union'), 'get');
$createbutton .= $OUTPUT->box_end();

// If there aren't any flavours yet.
$countflavours = $DB->count_records('theme_boost_union_flavours');
if ($countflavours < 1) {
    // Show the table, which, since it is empty, falls back to the
    // "There aren't any flavours created yet. Please create your first flavour to get things going." notice.
    $table->out(0, true);

    // And then show the button.
    echo $createbutton;

    // Otherwise.
} else {
    // Show the button.
    echo $createbutton;

    // And then show the table.
    $table->out(0, true);
}

// Finish page output.
echo $OUTPUT->footer();
