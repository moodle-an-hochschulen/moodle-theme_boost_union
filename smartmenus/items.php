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
 * Theme Boost Union - Menu items page.
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
$menuid = optional_param('menu', null, PARAM_INT);
$id = optional_param('id', null, PARAM_INT);

// Verify the existence of the menu and menu item.
if ($menuid == null && $id !== null) {
    // Verify the menu item exists. Get the menu from item id.
    $item = $DB->get_record('theme_boost_union_menuitems', ['id' => $id]);
    if (!$menu = $DB->get_record('theme_boost_union_menus', ['id' => $item->menu])) {
        throw new moodle_exception('error:smartmenusmenuitemnotfound', 'theme_boost_union');
    }
} else {
    // Verify the menu exists.
    $menu = $DB->get_record('theme_boost_union_menus', ['id' => $menuid]);
    if (!$menu) {
        throw new moodle_exception('error:smartmenusmenunotfound', 'theme_boost_union');
    }
}

// Compose the page URL.
$pageurl = new core\url('/theme/boost_union/smartmenus/items.php', ['menu' => $menu->id]);

// Get system context.
$context = context_system::instance();

// Access checks.
require_login();
require_capability('theme/boost_union:configure', $context);

// Prepare the page (to make sure that all necessary information is already set even if we just handle the actions as a start).
$PAGE->set_context($context);
$PAGE->set_url($pageurl);
$PAGE->set_cacheable(false);
$PAGE->navbar->add(get_string('pluginname', 'theme_boost_union'), new core\url('/admin/category.php',
        ['category' => 'theme_boost_union']));
$PAGE->navbar->add(get_string('smartmenus', 'theme_boost_union'), new core\url('/theme/boost_union/smartmenus/menus.php'));
$PAGE->navbar->add(get_string('smartmenusmenuitems', 'theme_boost_union'), new core\url('/theme/boost_union/smartmenus/items.php',
        ['menu' => $menu->id]));

// Process actions.
if ($action !== null && confirm_sesskey()) {
    // Every action is based on a menu, thus the menu ID param has to exist.
    $id = required_param('id', PARAM_INT);

    // Create menu instance. Actions are performed in smartmenu instance.
    $item = new theme_boost_union\smartmenu_item($id);

    // The actions might be done with more than one DB statements which should have a monolithic effect, so we use a transaction.
    $transaction = $DB->start_delegated_transaction();

    // Perform the requested action.
    switch ($action) {
        case 'delete':
            // Delete the menu.
            if ($item->delete_menuitem()) {
                // Notification to user for menu deleted success.
                \core\notification::success(get_string('smartmenusmenuitemdeletesuccess', 'theme_boost_union'));
            }
            break;
        case 'down':
            // Move the item downwards.
            $item->move_downward();
            break;
        case 'up':
            // Move the item upwards.
            $item->move_upward();
            break;
        case 'copy':
            // Duplicate the item.
            $item->duplicate();
            break;
        case 'hide':
            // Hide the item from menu.
            $item->update_field('visible', false);
            break;
        case 'show':
            // Show the item in menu.
            $item->update_field('visible', true);
            break;
    }

    // Allow to update the changes to database.
    $transaction->allow_commit();

    // Redirect to the items page to remove the params from the URL.
    redirect($pageurl);
}

// Further prepare the page.
$PAGE->set_title(theme_boost_union_get_externaladminpage_title(get_string('smartmenus', 'theme_boost_union')));
$PAGE->set_heading(theme_boost_union_get_externaladminpage_heading());

// Build smart menu items table.
$table = new theme_boost_union\table\smartmenus_items($menu->id);
$table->define_baseurl($PAGE->url, ['menu' => $menu->id]);

// Start page output.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('smartmenus', 'theme_boost_union'));
if (isset($menu->title)) {
    $menuheading = format_string($menu->title);
    $settingstitle = get_string('smartmenussettings', 'theme_boost_union');
    $settingsurl = new core\url('/theme/boost_union/smartmenus/edit.php', ['id' => $menuid, 'sesskey' => sesskey()]);
    $menuheading .= \core\output\html_writer::link($settingsurl,
            $OUTPUT->pix_icon('t/edit', $settingstitle, 'moodle', ['class' => 'ms-2']));
    echo $OUTPUT->heading($menuheading, 4);
}

// Prepare 'Create menu item' buttons.
$createbutton = $OUTPUT->box_start();
$createbutton .= $OUTPUT->single_button(
        new \core\url('/theme/boost_union/smartmenus/edit_items.php', ['menu' => $menuid, 'sesskey' => sesskey()]),
        get_string('smartmenusmenuaddnewitem', 'theme_boost_union'), 'get');
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
    $table->out(0, true);
}

// Finish page output.
echo $OUTPUT->footer();
