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
 * Theme boost union - Edit menu items.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Require config.
require(__DIR__.'/../../../config.php');

require_once($CFG->dirroot.'/lib/filelib.php');
// Require admin library.
require_once($CFG->libdir.'/adminlib.php');

require_sesskey();

// Menu ID.
$menuid = optional_param('menu', null, PARAM_INT);
// Smart menu ID to edit.
$id = optional_param('id', null, PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHAEXT);

if ($menuid == null && $id !== null) {
    // Verify the menu is exists.
    $item = $DB->get_record('theme_boost_union_menuitems', ['id' => $id]);
    if (!$menu = $DB->get_record('theme_boost_union_menus', ['id' => $item->menu])) {
        throw new moodle_exception('menunotfound', 'theme_boost_union_smartmenus');
    }
} else {
    // Verify the menu is exists.
    $menu = $DB->get_record('theme_boost_union_menus', ['id' => $menuid]);
    if (!$menu) {
        throw new moodle_exception('menunotfound', 'theme_boost_union_smartmenus');
    }
}
// Extend the features of admin settings.
admin_externalpage_setup('theme_boost_union_smartmenus');

// Page values.
$url = new moodle_url('/theme/boost_union/smartmenus/edit_items.php', ['menu' => $menu->id, 'sesskey' => sesskey()]);
$context = \context_system::instance();


// Setup page values.
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_heading(get_string('smartmenus', 'theme_boost_union'));

$PAGE->navbar->add(get_string('themes', 'core'), new moodle_url('/admin/category.php', array('category' => 'themes')));
$PAGE->navbar->add(get_string('pluginname', 'theme_boost_union'), new moodle_url('/admin/category.php',
        array('category' => 'theme_boost_union')));
$PAGE->navbar->add(get_string('smartmenus', 'theme_boost_union'), new moodle_url('/theme/boost_union/smartmenus/edit.php'));

// Edit smart menus form.
$draftitemid = file_get_submitted_draft_itemid('image');
file_prepare_draft_area(
    $draftitemid,
    $context->id,
    'theme_boost_union',
    'smartmenus_itemimage',
    $id,
    theme_boost_union\smartmenu_item::image_fileoptions()
);

// List of items overview page.
$overviewurl = new moodle_url('/theme/boost_union/smartmenus/items.php',  ['menu' => $menu->id]);

$lastmenu = theme_boost_union\smartmenu_item::get_lastitem($menu->id);
// Empty items then lastorder is 0.
$nextorder = isset($lastmenu->sortorder) ? $lastmenu->sortorder + 1 : 1;
// Create/edit menu items form instance. included the menu id.
$menuform = new \theme_boost_union\form\smartmenu_item_form($url->out(false), [
    'id' => $id,
    'menu' => $menu->id,
    'nextorder' => $nextorder,
]);

// Process the submitted items form data.
if ($formdata = $menuform->get_data()) {

    $result = theme_boost_union\smartmenu_item::manage_instance($formdata);
    if ($result) {
        redirect($overviewurl);
    }
} else if ($menuform->is_cancelled()) {
    redirect($overviewurl);
}

// Setup the menu to the form, if the form id param available.
if ($id !== null && $id > 0) {

    if ($record = theme_boost_union\smartmenu_item::instance($id)->get_item()) {
        // Get an unused draft itemid which will be used for this form.
        $record->image = $draftitemid;
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

// Display the smart menu form for create or edit.
echo $menuform->display();

// Footer.
echo $OUTPUT->footer();
