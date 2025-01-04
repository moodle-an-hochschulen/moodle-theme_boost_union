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
 * Theme Boost Union - Upgrade script.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Function to upgrade theme_boost_union
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_theme_boost_union_upgrade($oldversion) {
    global $DB, $OUTPUT;

    $dbman = $DB->get_manager();

    if ($oldversion < 2022080916) {

        // Define table theme_boost_union_flavours to be created.
        $table = new xmldb_table('theme_boost_union_flavours');

        // Adding fields to table theme_boost_union_flavours.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('description_format', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('sort', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('applytocohorts', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('applytocohorts_ids', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('applytocategories', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('applytocategories_ids', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('applytocategories_subcats', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('look_logo', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('look_logocompact', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('look_favicon', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('look_backgroundimage', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('look_rawscss', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table theme_boost_union_flavours.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for theme_boost_union_flavours.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2022080916, 'theme', 'boost_union');
    }

    if ($oldversion < 2022080922) {

        // Start composing the notification to inform the admin.
        $message = \core\output\html_writer::tag('p', get_string('upgradenotice_2022080922', 'theme_boost_union'));

        // Handle the logo and compact logo (which have got now new settings in Boost Union).
        foreach (['logo', 'logocompact'] as $setting) {
            // Initialize the logo copying status.
            $logocopied = false;

            // Fetch the logo config from Moodle core.
            $logocore = get_config('core_admin', $setting);

            // If a logo exists in Moodle core.
            if (!empty($logocore)) {
                // Get the system context.
                $systemcontext = \context_system::instance();

                // Get file storage.
                $fs = get_file_storage();

                // Get the files.
                $files = $fs->get_area_files($systemcontext->id, 'core_admin', $setting, false, 'itemid', false);
                if ($files) {
                    // Just pick the first file - we are sure that there is just one file.
                    $file = reset($files);

                    // Create the filerecord with the modified information.
                    $filerecord = [
                            'component' => 'theme_boost_union',
                            'filearea' => $setting,
                    ];

                    // Copy the logo file to Boost Union.
                    $newfile = $fs->create_file_from_storedfile($filerecord, $file);

                    // Set the theme config to the file name.
                    set_config($setting, '/'.$newfile->get_filename(), 'theme_boost_union');

                    // Remember the logo copying status.
                    $logocopied = true;
                }
            }

            // If the logo has been copied.
            if ($logocopied == true) {
                // Add the corresponding note to the notification.
                $message .= \core\output\html_writer::tag('p', get_string('upgradenotice_2022080922_copied', 'theme_boost_union',
                        get_string('upgradenotice_2022080922_'.$setting, 'theme_boost_union')));

                // Otherwise, if no logo was copied.
            } else {
                // Add the corresponding note to the notification.
                $message .= \core\output\html_writer::tag('p', get_string('upgradenotice_2022080922_notcopied', 'theme_boost_union',
                        get_string('upgradenotice_2022080922_'.$setting, 'theme_boost_union')));
            }
        }

        // Show the notification.
        // (If this notification is shown during a CLI upgrade, the p and strong HTML tags are shown as well.
        // We accept this glitch as it's just a one-time glitch and the admin can still read the notification.
        $notification = new \core\output\notification($message, \core\output\notification::NOTIFY_SUCCESS);
        $notification->set_show_closebutton(false);
        echo $OUTPUT->render($notification);

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2022080922, 'theme', 'boost_union');
    }

    if ($oldversion < 2023010517) {

        // Define table theme_boost_union_menus to be created.
        $table = new xmldb_table('theme_boost_union_menus');

        // Adding fields to table theme_boost_union_menus.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '18', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null, 'title');
        $table->add_field('description_format', XMLDB_TYPE_INTEGER, 9, null, null, null, null, 'description');
        $table->add_field('showdesc', XMLDB_TYPE_INTEGER, '9', null, null, null, null, 'description_format');
        $table->add_field('sortorder', XMLDB_TYPE_INTEGER, '18', XMLDB_UNSIGNED, null, null, null, 'showdesc');
        $table->add_field('location', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null, 'sortorder');
        $table->add_field('type', XMLDB_TYPE_INTEGER, '9', null, XMLDB_NOTNULL, null, '1', 'location');
        $table->add_field('mode', XMLDB_TYPE_INTEGER, '9', null, null, null, '1', 'type');
        $table->add_field('cssclass', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'mode');
        $table->add_field('moremenubehavior', XMLDB_TYPE_INTEGER, '9', XMLDB_UNSIGNED, null, null, null, 'cssclass');
        $table->add_field('cardsize', XMLDB_TYPE_INTEGER, '9', XMLDB_UNSIGNED, null, null, null, 'moremenubehavior');
        $table->add_field('cardform', XMLDB_TYPE_INTEGER, '9', XMLDB_UNSIGNED, null, null, null, 'cardsize');
        $table->add_field('overflowbehavior', XMLDB_TYPE_INTEGER, '9', XMLDB_UNSIGNED, null, null, null, 'cardform');
        $table->add_field('roles', XMLDB_TYPE_TEXT, null, null, null, null, null, 'overflowbehavior');
        $table->add_field('rolecontext', XMLDB_TYPE_INTEGER, '9', null, null, null, null, 'roles');
        $table->add_field('cohorts', XMLDB_TYPE_TEXT, null, null, null, null, null, 'rolecontext');
        $table->add_field('operator', XMLDB_TYPE_INTEGER, '9', null, null, null, null, 'cohorts');
        $table->add_field('languages', XMLDB_TYPE_TEXT, null, null, null, null, null, 'operator');
        $table->add_field('start_date', XMLDB_TYPE_INTEGER, '18', null, null, null, null, 'languages');
        $table->add_field('end_date', XMLDB_TYPE_INTEGER, '18', null, null, null, null, 'start_date');
        $table->add_field('visible', XMLDB_TYPE_INTEGER, '9', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '1', 'end_date');

        // Adding keys to table theme_boost_union_menus.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for theme_boost_union_menus.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table theme_boost_union_menuitems to be created.
        $table = new xmldb_table('theme_boost_union_menuitems');

        // Adding fields to table theme_boost_union_menuitems.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '18', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE);
        $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, null);
        $table->add_field('menu', XMLDB_TYPE_INTEGER, '18', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, 'title');
        $table->add_field('type', XMLDB_TYPE_INTEGER, '9', XMLDB_UNSIGNED, null, null, '1', 'menu');
        $table->add_field('sortorder', XMLDB_TYPE_INTEGER, '18', XMLDB_UNSIGNED, null, null, null, 'type');
        $table->add_field('url', XMLDB_TYPE_TEXT, null, null, null, null, null, 'sortorder');
        $table->add_field('category', XMLDB_TYPE_TEXT, null, null, null, null, null, 'url');
        $table->add_field('enrolmentrole', XMLDB_TYPE_TEXT, null, null, null, null, null, 'category');
        $table->add_field('completionstatus', XMLDB_TYPE_CHAR, '20', null, null, null, null, 'enrolmentrole');
        $table->add_field('daterange', XMLDB_TYPE_CHAR, '20', null, null, null, null, 'completionstatus');
        $table->add_field('customfields', XMLDB_TYPE_TEXT, null, null, null, null, null, 'daterange');
        $table->add_field('displayfield', XMLDB_TYPE_INTEGER, '9', null, null, null, null, 'customfields');
        $table->add_field('textcount', XMLDB_TYPE_INTEGER, '9', null, null, null, null, 'displayfield');
        $table->add_field('mode', XMLDB_TYPE_INTEGER, '9', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, 'textcount');
        $table->add_field('menuicon', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'mode');
        $table->add_field('display', XMLDB_TYPE_INTEGER, '9', XMLDB_UNSIGNED, null, null, null, 'menuicon');
        $table->add_field('tooltip', XMLDB_TYPE_CHAR, '255', null, null);
        $table->add_field('target', XMLDB_TYPE_INTEGER, '9', null, null, null, null, 'tooltip');
        $table->add_field('cssclass', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'target');
        $table->add_field('textposition', XMLDB_TYPE_INTEGER, '9', null, null, null, '0', 'cssclass');
        $table->add_field('textcolor', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'textposition');
        $table->add_field('backgroundcolor', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'textcolor');
        $table->add_field('desktop', XMLDB_TYPE_INTEGER, '9', XMLDB_UNSIGNED, null, null, '1', 'backgroundcolor');
        $table->add_field('tablet', XMLDB_TYPE_INTEGER, '9', XMLDB_UNSIGNED, null, null, '1', 'desktop');
        $table->add_field('mobile', XMLDB_TYPE_INTEGER, '9', XMLDB_UNSIGNED, null, null, '1', 'tablet');
        $table->add_field('roles', XMLDB_TYPE_TEXT, null, null, null, null, null, 'mobile');
        $table->add_field('rolecontext', XMLDB_TYPE_INTEGER, '9', null, null, null, '1', 'roles');
        $table->add_field('cohorts', XMLDB_TYPE_TEXT, null, null, null, null, null, 'rolecontext');
        $table->add_field('operator', XMLDB_TYPE_INTEGER, '9', null, null, null, '1', 'cohorts');
        $table->add_field('languages', XMLDB_TYPE_TEXT, null, null, null, null, null, 'operator');
        $table->add_field('start_date', XMLDB_TYPE_INTEGER, '18', null, null, null, null, 'languages');
        $table->add_field('end_date', XMLDB_TYPE_INTEGER, '18', null, null, null, null, 'start_date');
        $table->add_field('visible', XMLDB_TYPE_INTEGER, '9', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '1', 'end_date');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '18', null, null, null, null, 'visible');

        // Adding keys to table theme_boost_union_menuitems.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for theme_boost_union_menuitems.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2023010517, 'theme', 'boost_union');
    }

    if ($oldversion < 2023010519) {

        // Define table theme_boost_union_menus to be altered.
        $table = new xmldb_table('theme_boost_union_menus');

        // Define field overflowbehavior to be altered.
        $field = new xmldb_field('overflowbehavior', XMLDB_TYPE_INTEGER, '9', null, null, null, null, 'cardform');

        // Launch rename field to cardoverflowbehavior.
        $dbman->rename_field($table, $field, 'cardoverflowbehavior');

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2023010519, 'theme', 'boost_union');
    }

    if ($oldversion < 2023010521) {

        // Remove the THEME_BOOST_UNION_SETTING_COURSEBREADCRUMBS_DONTCHANGE option from the categorybreadcrumbs setting
        // and replace it with THEME_BOOST_UNION_SETTING_SELECT_NO if necessary.
        $oldconfig = get_config('theme_boost_union', 'categorybreadcrumbs');
        if ($oldconfig == 'dontchange') {
            set_config('categorybreadcrumbs', THEME_BOOST_UNION_SETTING_SELECT_NO, 'theme_boost_union');
        }

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2023010521, 'theme', 'boost_union');
    }

    if ($oldversion < 2023090100) {
        // Remove the files from the FontAwesome filearea of Boost Union as this setting was removed.
        $systemcontext = context_system::instance();
        $fs = get_file_storage();
        $fs->delete_area_files($systemcontext->id, 'theme_boost_union', 'fontawesome');

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2023090100, 'theme', 'boost_union');
    }

    if ($oldversion < 2023102008) {
        // Define table theme_boost_union_menus to be altered.
        $table = new xmldb_table('theme_boost_union_menuitems');

        // Define field listsort to be added to theme_boost_union_menuitems.
        $field = new xmldb_field('listsort', XMLDB_TYPE_INTEGER, '9', null, null, null, null, 'customfields');

        // Conditionally launch add field listsort.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2023102008, 'theme', 'boost_union');
    }

    if ($oldversion < 2023102009) {

        // Changing precision of field description_format on table theme_boost_union_flavours to (2).
        $table = new xmldb_table('theme_boost_union_flavours');
        $field = new xmldb_field('description_format', XMLDB_TYPE_INTEGER, '2', null, null, null, null, 'description');

        // Launch change of precision for field description_format.
        $dbman->change_field_precision($table, $field);

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2023102009, 'theme', 'boost_union');
    }

    if ($oldversion < 2023102021) {

        // Remove the preset setting from Boost Union.
        unset_config('preset', 'theme_boost_union');

        // Remove the presetfiles setting from Boost Union.
        unset_config('presetfiles', 'theme_boost_union');

        // Remove the files from the preset filearea of Boost Union as this setting was removed.
        $systemcontext = context_system::instance();
        $fs = get_file_storage();
        $fs->delete_area_files($systemcontext->id, 'theme_boost_union', 'preset');

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2023102021, 'theme', 'boost_union');
    }

    if ($oldversion < 2023102027) {
        // Define table theme_boost_union_menuitems to be altered.
        $table = new xmldb_table('theme_boost_union_menuitems');

        // Define field listsort to be added to theme_boost_union_menuitems.
        $field = new xmldb_field('category_subcats', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'category');

        // Conditionally launch add field listsort.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2023102027, 'theme', 'boost_union');
    }

    if ($oldversion < 2024100702) {

        // Define field byadmin to be added to theme_boost_union_menus.
        $table = new xmldb_table('theme_boost_union_menus');
        $field = new xmldb_field('byadmin', XMLDB_TYPE_INTEGER, '9', null, XMLDB_NOTNULL, null, '0', 'visible');

        // Conditionally launch add field byadmin.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field byadmin to be added to theme_boost_union_menuitems.
        $table = new xmldb_table('theme_boost_union_menuitems');
        $field = new xmldb_field('byadmin', XMLDB_TYPE_INTEGER, '9', null, XMLDB_NOTNULL, null, '0', 'timemodified');

        // Conditionally launch add field byadmin.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2024100702, 'theme', 'boost_union');
    }

    if ($oldversion < 2024100706) {

        // Define table theme_boost_union_flavours to be altered.
        $table = new xmldb_table('theme_boost_union_flavours');

        // Define field look_rawscsspre to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_rawscsspre', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Conditionally launch add field look_rawscsspre.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_brandcolor to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_brandcolor', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_brandcolor.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_bootstrapcolorsuccess to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_bootstrapcolorsuccess', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_bootstrapcolorsuccess.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_bootstrapcolorinfo to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_bootstrapcolorinfo', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_bootstrapcolorinfo.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_bootstrapcolorwarning to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_bootstrapcolorwarning', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_bootstrapcolorwarning.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_bootstrapcolordanger to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_bootstrapcolordanger', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_bootstrapcolordanger.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2024100706, 'theme', 'boost_union');
    }

    if ($oldversion < 2024100707) {

        // Define table theme_boost_union_flavours to be altered.
        $table = new xmldb_table('theme_boost_union_flavours');

        // Define field look_backgroundimagepos to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_backgroundimagepos', XMLDB_TYPE_CHAR, '32', null, null, null, null,
                'look_backgroundimage');

        // Conditionally launch add field look_backgroundimagepos.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_aicoladministration to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_aicoladministration', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_aicoladministration.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_aicolassessment to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_aicolassessment', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_aicolassessment.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_aicolcollaboration to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_aicolcollaboration', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_aicolcollaboration.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_aicolcommunication to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_aicolcommunication', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_aicolcommunication.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_aicolcontent to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_aicolcontent', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_aicolcontent.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_aicolinteractivecontent to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_aicolinteractivecontent', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_aicolinteractivecontent.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_aicolinterface to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_aicolinterface', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_aicolinterface.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field look_navbarcolor to be added to theme_boost_union_flavours.
        $field = new xmldb_field('look_navbarcolor', XMLDB_TYPE_CHAR, '32', null, null, null, null);

        // Conditionally launch add field look_navbarcolor.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2024100707, 'theme', 'boost_union');
    }

    return true;
}
