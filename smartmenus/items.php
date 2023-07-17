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
 * Theme Boost Union - List the items table for the menu.
 *
 * Manage the item Create, Update, Delete actions, sort the order of menus, Duplicate the item.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Require config.
require(__DIR__.'/../../../config.php');

// Require admin library.
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot. '/theme/boost_union/smartmenus/menulib.php');

// Get parameters.
$menuid = optional_param('menu', null, PARAM_INT);
$id = optional_param('id', null, PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHAEXT);

// Verify the existence of the menu and menu item.
if ($menuid == null && $id !== null) {
    // Verify the menu item exists. Get the menu from item id.
    $item = $DB->get_record('theme_boost_union_menuitems', ['id' => $id]);
    if (!$menu = $DB->get_record('theme_boost_union_menus', ['id' => $item->menu])) {
        throw new moodle_exception('menunotfound', 'theme_boost_union_smartmenus');
    }
} else {
    // Verify the menu exists.
    $menu = $DB->get_record('theme_boost_union_menus', ['id' => $menuid]);
    if (!$menu) {
        throw new moodle_exception('menunotfound', 'theme_boost_union_smartmenus');
    }
}

// Page values.
$url = new moodle_url('/theme/boost_union/smartmenus/items.php', ['menu' => $menu->id]);
$context = \context_system::instance();

// Access checks.
admin_externalpage_setup('theme_boost_union_smartmenus');

// Prepare the page.
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_heading(get_string('smartmenus', 'theme_boost_union')); // TODO Review.

// Prepare the breadcrumbs. // TODO Review.
$PAGE->navbar->add(get_string('themes', 'core'), new moodle_url('/admin/category.php', array('category' => 'themes')));
$PAGE->navbar->add(get_string('pluginname', 'theme_boost_union'), new moodle_url('/admin/category.php',
                array('category' => 'theme_boost_union'))
);
$PAGE->navbar->add(get_string('smartmenus', 'theme_boost_union'), new moodle_url('/theme/boost_union/smartmenus/items.php'));

// Process actions.
if ($action !== null && confirm_sesskey() && $action != 'preview') {
    // Every action is based on a menu, thus the menu ID param has to exist.
    $id = required_param('id', PARAM_INT);

    // Create menu instance. Actions are performed in smartmenu instance.
    $item = new theme_boost_union\smartmenu_item($id);

    $transaction = $DB->start_delegated_transaction();
    // Perform the requested action.
    switch ($action) {
        // Triggered action is delete, then init the deletion of menu.
        case 'delete':
            // Delete the menu.
            if ($item->delete_menuitem()) {
                // Notification to user for menu deleted success.
                \core\notification::success(get_string('smartmenusmenudeleted', 'theme_boost_union'));
            }
            break;
        // Move the menu order to down.
        case "movedown":
            // Move the item to down order. Fetch the next item and use its order and change the next item position to upwards.
            $item->move_downward();
            break;
        case "moveup":
            // Move the item to upwards.Fetch the previous item and use its order and change the previous item position to upwards.
            $item->move_upward();
            break;
        case "copy":
            // Duplicate the item. Clone the item instance, removed the id and send to manage_instance.
            // It will create the item as newone.
            $item->duplicate();
            break;
        case "hide":
            // Hide the item from menu.
            $item->update_field('visible', false);
            break;
        case "show":
            // Show the item in menu.
            $item->update_field('visible', true);
            break;
    }

    // Allow to update the changes to database.
    $transaction->allow_commit();

    // Redirect to items page to remove the params from the URL.
    redirect($url);
}

// Build smart menu items table.
$table = new theme_boost_union\table\menuitems($menu->id);
$table->define_baseurl($PAGE->url);

// Start page output.
echo $OUTPUT->header();
if (isset($menu->title)) { // TODO Review.
    echo $OUTPUT->heading(get_string('smartmenusmenuheading', 'theme_boost_union', $menu->title));
}

// Show smart menus description.
echo get_string('smartmenus_desc', 'theme_boost_union');

// Prepare 'Create smart menu item' buttons. // TODO Review.
$createbutton = $OUTPUT->box_start();
$createbutton .= smartmenu_helper::theme_boost_union_menuitems_button($menu->id);
$createbutton .= $OUTPUT->box_end();

// If there aren't any smart menu items yet.
$countitems = $DB->count_records('theme_boost_union_menuitems');
if ($countitems < 1) {
    // Show the table, which, since it is empty, falls back to the
    // "There aren't any items added to this smart menu yet. Please add an item to this menu." notice.
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
