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
 * Theme boost union - Edit menu.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Require config.
require(__DIR__.'/../../../config.php');

// Require admin library.
require_once($CFG->libdir.'/adminlib.php');

require_sesskey();

// Smart menu ID to edit.
$id = optional_param('id', null, PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHAEXT);

// Extend the features of admin settings.
admin_externalpage_setup('theme_boost_union_smartmenus');

// Page values.
$url = new moodle_url('/theme/boost_union/smartmenus/edit.php', ['id' => $id, 'sesskey' => sesskey()]);
$context = context_system::instance();

// Setup page values.
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_heading(get_string('smartmenus', 'theme_boost_union'));

$PAGE->navbar->add(get_string('themes', 'core'), new moodle_url('/admin/category.php', array('category' => 'themes')));
$PAGE->navbar->add(get_string('pluginname', 'theme_boost_union'), new moodle_url('/admin/category.php',
        array('category' => 'theme_boost_union')));
$PAGE->navbar->add(get_string('smartmenus', 'theme_boost_union'), new moodle_url('/theme/boost_union/smartmenus/edit.php'));

// Edit smart menus form.
$menuform = new \theme_boost_union\form\smartmenu_form(null, ['id' => $id]);
$overviewurl = new moodle_url('/theme/boost_union/smartmenus/menus.php');

if ($formdata = $menuform->get_data()) {
    $result = theme_boost_union\smartmenu::manage_instance($formdata);
    // After saved the menu data, lets redirect to configure items for this menu.
    if (isset($formdata->saveanddisplay) && $formdata->saveanddisplay) {
        $itemsurl = new \moodle_url('/theme/boost_union/smartmenus/items.php', ['menu' => $result]);
        redirect($itemsurl);
    } else {
        // Redirect to menus list.
        redirect($overviewurl);
    }
} else if ($menuform->is_cancelled()) {
    redirect($overviewurl);
}

// Setup the menu to the form, if the form id param available.
if ($id !== null && $id > 0) {

    if ($record = theme_boost_union\smartmenu::get_menu($id)) {
        // Set the menu data to the menu edit form.
        $menuform->set_data($record);
    } else {
        // Direct the user to list page with error message, when the requested menu is not available.
        \core\notification::error(get_string('smartmenusrecordmissing', 'theme_boost_union'));
        redirect($overviewurl);
    }
}

// Page content display started.
echo $OUTPUT->header();

// Add the create item and create menu buttons.
if ($id !== null && $id > 0) {
    echo smartmenu_helper::theme_boost_union_menuitems_button($id);
}

// Smart menu heading.
echo $OUTPUT->heading(get_string('smartmenussettins', 'theme_boost_union'));

// Display the smart menu form for create or edit.
echo $menuform->display();

// Footer.
echo $OUTPUT->footer();
