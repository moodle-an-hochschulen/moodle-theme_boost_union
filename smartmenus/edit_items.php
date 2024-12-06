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
 * Theme Boost Union - Edit menu items.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Require config.
require(__DIR__.'/../../../config.php');

// Require plugin libraries.
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

// Require file library.
require_once($CFG->dirroot.'/lib/filelib.php');

// Require admin library.
require_once($CFG->libdir.'/adminlib.php');

// Get parameters.
$id = optional_param('id', null, PARAM_INT);
$menuid = optional_param('menu', null, PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHAEXT);

// Get system context.
$context = context_system::instance();

// Access checks.
require_login();
require_sesskey();
require_capability('theme/boost_union:configure', $context);

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

// Prepare the page.
$PAGE->set_context($context);
$PAGE->set_url(new core\url('/theme/boost_union/smartmenus/edit_items.php', ['menu' => $menu->id, 'sesskey' => sesskey()]));
$PAGE->navbar->add(get_string('pluginname', 'theme_boost_union'), new core\url('/admin/category.php',
        ['category' => 'theme_boost_union']));
$PAGE->navbar->add(get_string('smartmenus', 'theme_boost_union'), new core\url('/theme/boost_union/smartmenus/menus.php'));
$PAGE->navbar->add(get_string('smartmenusmenuitems', 'theme_boost_union'), new core\url('/theme/boost_union/smartmenus/items.php',
        ['menu' => $menu->id]));
$PAGE->set_title(theme_boost_union_get_externaladminpage_title(get_string('smartmenus', 'theme_boost_union')));
if ($menuid == null && $id !== null) {
    $PAGE->set_heading(get_string('smartmenusmenuitemedit', 'theme_boost_union'));
    $PAGE->navbar->add(get_string('smartmenusmenuitemedit', 'theme_boost_union'));
} else {
    $PAGE->set_heading(get_string('smartmenusmenuitemcreate', 'theme_boost_union'));
    $PAGE->navbar->add(get_string('smartmenusmenuitemcreate', 'theme_boost_union'));
}

// Prepare draft item id for the form.
$draftitemid = file_get_submitted_draft_itemid('image');
file_prepare_draft_area($draftitemid, $context->id, 'theme_boost_union', 'smartmenus_itemimage', $id,
        theme_boost_union\smartmenu_item::image_filepickeroptions()
);

// Prepare the next menu item id based on the last item ID (if no menu items exist yet, the last item is 0).
$lastmenu = theme_boost_union\smartmenu_item::get_lastitem($menu->id);
$nextorder = isset($lastmenu->sortorder) ? $lastmenu->sortorder + 1 : 1;

// Prepare form URL.
$formurl = new core\url('/theme/boost_union/smartmenus/edit_items.php', ['menu' => $menu->id, 'sesskey' => sesskey()]);

// If we are editing an existing menu item.
if ($id != null) {
    // Get menu item from DB.
    $menuitem = $DB->get_record('theme_boost_union_menuitems', ['id' => $id], '*', MUST_EXIST);

    // Init form and pass the id and menu item object to it.
    $form = new \theme_boost_union\form\smartmenu_item_edit_form($formurl->out(false), [
            'id' => $id,
            'menu' => $menu->id,
            'menutype' => $menu->type,
            'nextorder' => $nextorder,
            'menuitem' => $menuitem,
    ]);

    // Otherwise, if we are creating a new menu item.
} else {
    // Init form and pass the id to it.
    $form = new \theme_boost_union\form\smartmenu_item_edit_form($formurl->out(false), [
            'id' => $id,
            'menu' => $menu->id,
            'menutype' => $menu->type,
            'nextorder' => $nextorder,
    ]);
}

// If the form was submitted.
if ($data = $form->get_data()) {
    // Handle form results.
    $result = theme_boost_union\smartmenu_item::manage_instance($data);
    if ($result) {
        // Redirect to menu item list.
        redirect(new core\url('/theme/boost_union/smartmenus/items.php', ['menu' => $menu->id]));
    }

    // Otherwise if the form was cancelled.
} else if ($form->is_cancelled()) {
    // Redirect to menu item list.
    redirect(new core\url('/theme/boost_union/smartmenus/items.php', ['menu' => $menu->id]));
}

// If a menu item ID is given.
if ($id !== null && $id > 0) {
    // Fetch the data for the menu item.
    if ($record = theme_boost_union\smartmenu_item::instance($id)->get_item()) {
        // Get an unused draft item id which will be used for this form.
        $record->image = $draftitemid;

        // Set the menu data to the menu edit form.
        $form->set_data($record);

        // If the menu item is not available.
    } else {
        // Add a notification to the page.
        \core\notification::error(get_string('error:smartmenusmenuitemnotfound', 'theme_boost_union'));

        // Redirect to menu item list (where the notification is shown).
        redirect(new core\url('/theme/boost_union/smartmenus/items.php', ['menu' => $menu->id]));
    }
}

// Start page output.
echo $OUTPUT->header();

// Show form.
echo $form->display();

// Finish page output.
echo $OUTPUT->footer();
