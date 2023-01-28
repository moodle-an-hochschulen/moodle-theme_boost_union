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
    global $DB;

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

        // Copy the global logo and compact logo to Boost Union's new logo fileareas.
        $logocopied = false;
        foreach (['logo', 'logocompact'] as $setting) {
            $logo = get_config('core_admin', $setting);
            if (!empty($logo)) {
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
                    $filerecord = array(
                            'component' => 'theme_boost_union',
                            'filearea' => $setting,
                    );

                    // Copy the logo file to Boost Union.
                    $newfile = $fs->create_file_from_storedfile($filerecord, $file);

                    // Set the theme config to the file name.
                    set_config($setting, '/'.$newfile->get_filename(), 'theme_boost_union');

                    // Remember this fact.
                    $logocopied = true;
                }
            }
        }

        // If at least one logo has been copied.
        if ($logocopied == true) {
            // Show a notification to inform the admin.
            $message = get_string('upgradenotice_2022080922', 'theme_boost_union');
            \core\notification::success($message);
        }

        // Boost_union savepoint reached.
        upgrade_plugin_savepoint(true, 2022080922, 'theme', 'boost_union');
    }

    return true;
}
