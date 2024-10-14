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
 * Theme Boost Union - Menu overview page
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Require config.
require(__DIR__.'/../../../config.php');

// Require plugin libraries.
require_once($CFG->dirroot. '/theme/boost_union/smartmenus/menulib.php');

// Require admin library.
require_once($CFG->libdir.'/adminlib.php');

// Get parameters.
$action = optional_param('action', null, PARAM_TEXT);
$menuid = optional_param('id', null, PARAM_INT);

// Get system context.
$context = context_system::instance();

// Access checks.
admin_externalpage_setup('theme_boost_union_smartmenus');

// Prepare the page (to make sure that all necessary information is already set even if we just handle the actions as a start).
$PAGE->set_context($context);
$PAGE->set_url(new core\url('/theme/boost_union/smartmenus/menus.php'));
$PAGE->set_cacheable(false);

// Process actions.
if ($action !== null && confirm_sesskey()) {
    // Every action is based on a menu, thus the menu ID param has to exist.
    $menuid = required_param('id', PARAM_INT);

    // Create menu instance. Actions are performed in smartmenu instance.
    $menu = theme_boost_union\smartmenu::instance($menuid);

    // The actions might be done with more than one DB statements which should have a monolithic effect, so we use a transaction.
    $transaction = $DB->start_delegated_transaction();

    // Perform the requested action.
    switch ($action) {
        case 'delete':
            // Delete the menu.
            if ($menu->delete_menu()) {
                // Notification to user for menu deleted success.
                \core\notification::success(get_string('smartmenusmenudeletesuccess', 'theme_boost_union'));
            }
            break;
        case 'down':
            // Move the menu downwards.
            $menu->move_downward();
            break;
        case 'up':
            // Move the menu upwards.
            $menu->move_upward();
            break;
        case 'copy':
            // Duplicate the menu and its items.
            $menu->duplicate();
            break;
        case 'hide':
            // Disable the menu visibility.
            $menu->update_visible(false);
            break;
        case 'show':
            // Enable the menu visibility.
            $menu->update_visible(true);
            break;
    }

    // Allow to update the changes to database.
    $transaction->allow_commit();

    // Redirect to the same page.
    redirect($PAGE->url);
}

// Further prepare the page.
$PAGE->set_title(theme_boost_union_get_externaladminpage_title(get_string('smartmenus', 'theme_boost_union')));
$PAGE->set_heading(theme_boost_union_get_externaladminpage_heading());

// Build smart menus table.
$table = new theme_boost_union\table\smartmenus_menus($context->id);
$table->define_baseurl($PAGE->url);

// Start page output.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('smartmenus', 'theme_boost_union'));

// Show smart menus description.
echo get_string('smartmenus_desc', 'theme_boost_union');

// Add experimental warning.
$experimentalnotification = new \core\output\notification(get_string('smartmenusexperimental', 'theme_boost_union'),
        \core\output\notification::NOTIFY_WARNING);
$experimentalnotification->set_show_closebutton(false);
echo $OUTPUT->render($experimentalnotification);

// Prepare 'Create menu' button.
$createbutton = $OUTPUT->box_start();
$createbutton .= $OUTPUT->single_button(
        new \core\url('/theme/boost_union/smartmenus/edit.php', ['sesskey' => sesskey()]),
        get_string('smartmenusmenucreate', 'theme_boost_union'), 'get');
$createbutton .= $OUTPUT->box_end();

// If there aren't any smart menus yet.
$countmenus = $DB->count_records('theme_boost_union_menus');
if ($countmenus < 1) {
    // Show the table, which, since it is empty, falls back to the
    // "There aren't any smart menus created yet. Please create your first smart menu to get things going." notice.
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
