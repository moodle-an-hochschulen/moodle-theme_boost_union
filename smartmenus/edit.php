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
 * Theme Boost Union - Edit menu.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Require config.
require(__DIR__.'/../../../config.php');

// Require plugin libraries.
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

// Require admin library.
require_once($CFG->libdir.'/adminlib.php');

// Get parameters.
$id = optional_param('id', null, PARAM_INT);

// Get system context.
$context = context_system::instance();

// Access checks.
require_login();
require_sesskey();
require_capability('theme/boost_union:configure', $context);

// Prepare the page.
$PAGE->set_context($context);
$PAGE->set_url(new core\url('/theme/boost_union/smartmenus/edit.php', ['id' => $id, 'sesskey' => sesskey()]));
$PAGE->set_cacheable(false);
$PAGE->navbar->add(get_string('pluginname', 'theme_boost_union'), new core\url('/admin/category.php',
        ['category' => 'theme_boost_union']));
$PAGE->navbar->add(get_string('smartmenus', 'theme_boost_union'), new core\url('/theme/boost_union/smartmenus/menus.php'));
$PAGE->set_title(theme_boost_union_get_externaladminpage_title(get_string('smartmenus', 'theme_boost_union')));
if ($id !== null && $id > 0) {
    $PAGE->set_heading(get_string('smartmenusmenuedit', 'theme_boost_union'));
    $PAGE->navbar->add(get_string('smartmenusmenuedit', 'theme_boost_union'));
} else {
    $PAGE->set_heading(get_string('smartmenusmenucreate', 'theme_boost_union'));
    $PAGE->navbar->add(get_string('smartmenusmenucreate', 'theme_boost_union'));
}

// If we are editing an existing menu.
if ($id != null) {
    // Get menu from DB.
    $menu = $DB->get_record('theme_boost_union_menus', ['id' => $id], '*', MUST_EXIST);

    // Init form and pass the id and menu object to it.
    $form = new \theme_boost_union\form\smartmenu_edit_form(null, ['id' => $id, 'menu' => $menu]);

    // Otherwise, if we are creating a new menu.
} else {
    // Init form and pass the id to it.
    $form = new \theme_boost_union\form\smartmenu_edit_form(null, ['id' => $id]);
}

// If the form was submitted.
if ($data = $form->get_data()) {
    // Handle form results.
    $menuid = theme_boost_union\smartmenu::manage_instance($data);

    // After the menu data was saved, let's redirect to configure items for this menu.
    if (isset($data->saveanddisplay) && $data->saveanddisplay) {
        redirect(new core\url('/theme/boost_union/smartmenus/items.php', ['menu' => $menuid]));

        // Otherwise.
    } else {
        // Redirect to menu list.
        redirect(new core\url('/theme/boost_union/smartmenus/menus.php'));
    }

    // Otherwise if the form was cancelled.
} else if ($form->is_cancelled()) {
    // Redirect to menu list.
    redirect(new core\url('/theme/boost_union/smartmenus/menus.php'));
}

// If a menu ID is given.
if ($id !== null && $id > 0) {
    // Fetch the data for the menu.
    if ($record = theme_boost_union\smartmenu::get_menu($id)) {
        // Set the menu data to the menu edit form.
        $form->set_data($record);

        // If the menu is not available.
    } else {
        // Add a notification to the page.
        \core\notification::error(get_string('error:smartmenusmenunotfound', 'theme_boost_union'));

        // Redirect to menu list (where the notification is shown).
        redirect(new core\url('/theme/boost_union/smartmenus/menus.php'));
    }
}

// Start page output.
echo $OUTPUT->header();

// Show form.
echo $form->display();

// Finish page output.
echo $OUTPUT->footer();
