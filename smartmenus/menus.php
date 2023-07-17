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
 * Theme Boost Union - List the available menus and manage the menu Create, Update, Delete actions, sort the order of menus.
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
$action = optional_param('action', null, PARAM_ALPHAEXT);
$menuid = optional_param('id', null, PARAM_INT);

// Page values.
$context = context_system::instance();

// Prepare the page.
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/theme/boost_union/smartmenus/menus.php'));

// Process actions.
if ($action !== null && confirm_sesskey()) {
    // Every action is based on a menu, thus the menu ID param has to exist.
    $menuid = required_param('id', PARAM_INT);

    // Create menu instance. Actions are performed in smartmenu instance.
    $menu = theme_boost_union\smartmenu::instance($menuid);

    $transaction = $DB->start_delegated_transaction();

    // Perform the requested action.
    switch ($action) {
        // Triggered action is delete, then init the deletion of menu.
        case 'delete':
            // Delete the menu.
            if ($menu->delete_menu()) {
                // Notification to user for menu deleted success.
                \core\notification::success(get_string('smartmenusmenudeleted', 'theme_boost_union'));
            }
            break;
        // Move the menu order to down.
        case "movedown":
            // Move the menu downwards.
            $menu->move_downward();
            break;
        case "moveup":
            // Move the menu upwards.
            $menu->move_upward();
            break;
        case "copy":
            // Duplicate the menu and it items.
            $menu->duplicate();
            break;
        case "hidemenu":
            // Disable the menu visibility.
            $menu->update_visible(false);
            break;
        case "showmenu":
            // Enable the menu.
            $menu->update_visible(true);
            break;
    }

    // Allow to update the changes to database.
    $transaction->allow_commit();

    // Redirect to the same page.
    redirect($PAGE->url);
}

// Access checks.
admin_externalpage_setup('theme_boost_union_smartmenus');

// Prepare the breadcrumbs. // TODO Review.
$PAGE->navbar->add(get_string('themes', 'core'), new moodle_url('/admin/category.php', array('category' => 'themes')));
$PAGE->navbar->add(get_string('pluginname', 'theme_boost_union'), new moodle_url('/admin/category.php',
                array('category' => 'theme_boost_union'))
);
$PAGE->navbar->add(get_string('smartmenus', 'theme_boost_union'), new moodle_url('/theme/boost_union/smartmenus/menus.php'));

// Further prepare the page.
$PAGE->set_heading(theme_boost_union_get_externaladminpage_heading());

// Build smart menus table.
$table = new theme_boost_union\table\smartmenu($context->id);
$table->define_baseurl($PAGE->url);

// Start page output.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('smartmenus', 'theme_boost_union'));

// Show smart menus description.
echo get_string('smartmenus_desc', 'theme_boost_union');

// Prepare 'Create smart menu' button. // TODO Review.
$createbutton = $OUTPUT->box_start();
$createbutton .= smartmenu_helper::theme_boost_union_smartmenu_buttons();
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
    $table->out(10, true);
}

// Finish page output.
echo $OUTPUT->footer();
