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
 * Theme Boost Union - CRUD flavours page
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @copyright  based on code by bdecent gmbh <https://bdecent.de> in format_kickstart.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Require config.
require(__DIR__.'/../../../config.php');

// Require plugin libraries.
require_once($CFG->dirroot.'/theme/boost_union/lib.php');
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');
require_once($CFG->dirroot.'/theme/boost_union/flavours/flavourslib.php');

// Get parameters.
$action = required_param('action', PARAM_TEXT);

// Get system context.
$context = context_system::instance();

// Access checks.
require_login();
require_sesskey();
require_capability('theme/boost_union:configure', $context);

// Prepare the page.
$PAGE->set_context($context);
$PAGE->set_url(new core\url('/theme/boost_union/flavours/edit.php', ['action' => $action]));
$PAGE->set_cacheable(false);
$PAGE->navbar->add(get_string('pluginname', 'theme_boost_union'), new core\url('/admin/category.php',
        ['category' => 'theme_boost_union']));
$PAGE->navbar->add(get_string('flavoursflavours', 'theme_boost_union'), new core\url('/theme/boost_union/flavours/overview.php'));
switch ($action) {
    case 'create':
        $PAGE->set_title(theme_boost_union_get_externaladminpage_title(get_string('flavourscreateflavour', 'theme_boost_union')));
        $PAGE->set_heading(get_string('flavourscreateflavour', 'theme_boost_union'));
        $PAGE->navbar->add(get_string('flavourscreateflavour', 'theme_boost_union'));
        break;
    case 'edit':
        $PAGE->set_title(theme_boost_union_get_externaladminpage_title(get_string('flavourseditflavour', 'theme_boost_union')));
        $PAGE->set_heading(get_string('flavourseditflavour', 'theme_boost_union'));
        $PAGE->navbar->add(get_string('flavourseditflavour', 'theme_boost_union'));
        break;
    case 'delete':
        $PAGE->set_title(theme_boost_union_get_externaladminpage_title(get_string('flavoursdeleteflavour', 'theme_boost_union')));
        $PAGE->set_heading(get_string('flavoursdeleteflavour', 'theme_boost_union'));
        $PAGE->navbar->add(get_string('flavoursdeleteflavour', 'theme_boost_union'));
        break;
}

// Handle actions.
switch ($action) {
    // Create flavour.
    case 'create':
        // Init form.
        $form = new \theme_boost_union\form\flavour_edit_form($PAGE->url);

        // If the form was submitted.
        if ($data = $form->get_data()) {
            // Gather the submitted data which needs processing before storing it.
            // Note: title and look_rawscss are automagically saved by mform as they do not need any processing.
            $data->description_format = $data->description['format'];
            $data->description = $data->description['text'];
            if (isset($data->applytocohorts_ids)) {
                $data->applytocohorts_ids = json_encode($data->applytocohorts_ids);
            }
            if (isset($data->applytocategories_ids)) {
                $data->applytocategories_ids = json_encode($data->applytocategories_ids);
            }

            // Calculate and remember the sort order.
            $countflavours = $DB->count_records('theme_boost_union_flavours');
            $data->sort = (!empty($countflavours)) ? $countflavours + 1 : 1;

            // Add placeholders for filepaths (even if the DB would do this herself).
            $data->look_logo = null;
            $data->look_logocompact = null;
            $data->look_favicon = null;
            $data->look_backgroundimage = null;

            // Insert the submitted data into the database table.
            $id = $DB->insert_record('theme_boost_union_flavours', $data);

            // Store the files.
            file_save_draft_area_files($data->flavours_look_logo, $context->id, 'theme_boost_union',
                    'flavours_look_logo', $id, ['subdirs' => 0, 'maxfiles' => 1]);
            file_save_draft_area_files($data->flavours_look_logocompact, $context->id, 'theme_boost_union',
                    'flavours_look_logocompact', $id, ['subdirs' => 0, 'maxfiles' => 1]);
            file_save_draft_area_files($data->flavours_look_favicon, $context->id, 'theme_boost_union',
                    'flavours_look_favicon', $id, ['subdirs' => 0, 'maxfiles' => 1]);
            file_save_draft_area_files($data->flavours_look_backgroundimage, $context->id, 'theme_boost_union',
                    'flavours_look_backgroundimage', $id, ['subdirs' => 0, 'maxfiles' => 1]);

            // Get the files again to remember the filenames (and ignore the dot folder in the filearea).
            $looklogofilename = theme_boost_union_flavours_get_filename('look_logo', $id);
            $looklogocompactfilename = theme_boost_union_flavours_get_filename('look_logocompact', $id);
            $faviconfilename = theme_boost_union_flavours_get_filename('look_favicon', $id);
            $backgroundimagefilename = theme_boost_union_flavours_get_filename('look_backgroundimage', $id);

            // Update the database record to include the file names.
            $updaterecord = $DB->get_record('theme_boost_union_flavours', ['id' => $id]);
            $updaterecord->look_logo = $looklogofilename;
            $updaterecord->look_logocompact = $looklogocompactfilename;
            $updaterecord->look_favicon = $faviconfilename;
            $updaterecord->look_backgroundimage = $backgroundimagefilename;
            $DB->update_record('theme_boost_union_flavours', $updaterecord);

            // Reset theme cache.
            // This is necessary as the flavour asset URLs contain the themerev.
            theme_reset_all_caches();

            // Purge the flavours cache as well as the users might get other flavours which apply after the creation.
            // We would have preferred using \core_cache\helper::purge_by_definition, but this just purges the session cache
            // of the current user and not for all users.
            \core_cache\helper::purge_by_event('theme_boost_union_flavours_created');

            // Show success notification.
            \core\notification::success(get_string('flavoursnotificationcreated', 'theme_boost_union'));

            // Redirect to overview page.
            redirect(new core\url('/theme/boost_union/flavours/overview.php'));

            // Otherwise if the form was cancelled.
        } else if ($form->is_cancelled()) {
            // Redirect to overview page.
            redirect(new core\url('/theme/boost_union/flavours/overview.php'));
        }

        break;

    // Edit flavour.
    case 'edit':
        // Get parameters.
        $id = required_param('id', PARAM_INT);

        // Get flavour from DB.
        $flavour = $DB->get_record('theme_boost_union_flavours', ['id' => $id], '*', MUST_EXIST);

        // Init form and pass the $flavour object to it.
        $form = new \theme_boost_union\form\flavour_edit_form($PAGE->url, ['flavour' => $flavour]);

        // If the form was submitted.
        if ($data = $form->get_data()) {
            // Gather the submitted data which needs processing before storing it.
            // Note: title and look_rawscss are automagically saved by mform as they do not need any processing.
            $data->description_format = $data->description['format'];
            $data->description = $data->description['text'];
            if (isset($data->applytocohorts_ids)) {
                $data->applytocohorts_ids = json_encode($data->applytocohorts_ids);
            }
            if (isset($data->applytocategories_ids)) {
                $data->applytocategories_ids = json_encode($data->applytocategories_ids);
            }

            // Update the submitted data in the database table.
            $DB->update_record('theme_boost_union_flavours', $data);

            // Store the files.
            file_save_draft_area_files($data->flavours_look_logo, $context->id, 'theme_boost_union',
                    'flavours_look_logo', $data->id, ['subdirs' => 0, 'maxfiles' => 1]);
            file_save_draft_area_files($data->flavours_look_logocompact, $context->id, 'theme_boost_union',
                    'flavours_look_logocompact', $data->id, ['subdirs' => 0, 'maxfiles' => 1]);
            file_save_draft_area_files($data->flavours_look_favicon, $context->id, 'theme_boost_union',
                    'flavours_look_favicon', $data->id, ['subdirs' => 0, 'maxfiles' => 1]);
            file_save_draft_area_files($data->flavours_look_backgroundimage, $context->id, 'theme_boost_union',
                    'flavours_look_backgroundimage', $data->id, ['subdirs' => 0, 'maxfiles' => 1]);

            // Get the files again to remember the filenames (and ignore the dot folder in the filearea).
            $looklogofilename = theme_boost_union_flavours_get_filename('look_logo', $id);
            $looklogocompactfilename = theme_boost_union_flavours_get_filename('look_logocompact', $id);
            $faviconfilename = theme_boost_union_flavours_get_filename('look_favicon', $id);
            $backgroundimagefilename = theme_boost_union_flavours_get_filename('look_backgroundimage', $id);

            // Update the database record to include the file names.
            $updaterecord = $DB->get_record('theme_boost_union_flavours', ['id' => $id]);
            $updaterecord->look_logo = $looklogofilename;
            $updaterecord->look_logocompact = $looklogocompactfilename;
            $updaterecord->look_favicon = $faviconfilename;
            $updaterecord->look_backgroundimage = $backgroundimagefilename;
            $DB->update_record('theme_boost_union_flavours', $updaterecord);

            // Reset theme cache.
            // This is necessary as the flavour asset URLs contain the themerev.
            theme_reset_all_caches();

            // Purge the flavours cache as well as the users might get other flavours which apply after the editing.
            // We would have preferred using \core_cache\helper::purge_by_definition, but this just purges the session cache
            // of the current user and not for all users.
            \core_cache\helper::purge_by_event('theme_boost_union_flavours_edited');

            // Show success notification.
            \core\notification::success(get_string('flavoursnotificationedited', 'theme_boost_union'));

            // Redirect to overview page.
            redirect(new core\url('/theme/boost_union/flavours/overview.php'));

            // Otherwise if the form was cancelled.
        } else if ($form->is_cancelled()) {
            // Redirect to overview page.
            redirect(new core\url('/theme/boost_union/flavours/overview.php'));

            // Otherwise if the form was not yet submitted.
        } else {
            // Prepare the file areas.
            $looklogodraftitemid = file_get_submitted_draft_itemid('flavours_look_logo');
            $looklogocompactdraftitemid = file_get_submitted_draft_itemid('flavours_look_logocompact');
            $favicondraftitemid = file_get_submitted_draft_itemid('flavours_look_favicon');
            $backgroundimagedraftitemid = file_get_submitted_draft_itemid('flavours_look_backgroundimage');
            file_prepare_draft_area($looklogodraftitemid, $context->id, 'theme_boost_union', 'flavours_look_logo',
                    $id, ['subdirs' => 0, 'maxfiles' => 1]);
            file_prepare_draft_area($looklogocompactdraftitemid, $context->id, 'theme_boost_union', 'flavours_look_logocompact',
                    $id, ['subdirs' => 0, 'maxfiles' => 1]);
            file_prepare_draft_area($favicondraftitemid, $context->id, 'theme_boost_union', 'flavours_look_favicon',
                    $id, ['subdirs' => 0, 'maxfiles' => 1]);
            file_prepare_draft_area($backgroundimagedraftitemid, $context->id, 'theme_boost_union', 'flavours_look_backgroundimage',
                    $id, ['subdirs' => 0, 'maxfiles' => 1]);
            $flavour->flavours_look_logo = $looklogodraftitemid;
            $flavour->flavours_look_logocompact = $looklogocompactdraftitemid;
            $flavour->flavours_look_favicon = $favicondraftitemid;
            $flavour->flavours_look_backgroundimage = $backgroundimagedraftitemid;

            // Gather the data for the form.
            $flavour->description = [
                    'text' => $flavour->description,
                    'format' => $flavour->description_format,
            ];
            if (isset($flavour->applytocohorts_ids)) {
                $flavour->applytocohorts_ids = json_decode($flavour->applytocohorts_ids, true);
            }
            if (isset($flavour->applytocategories_ids)) {
                $flavour->applytocategories_ids = json_decode($flavour->applytocategories_ids, true);
            }

            // Fill the data into the form.
            $form->set_data($flavour);
        }

        break;

    // Delete flavour.
    case 'delete':
        // Get parameters.
        $id = required_param('id', PARAM_INT);

        // Get flavour from DB.
        $flavour = $DB->get_record('theme_boost_union_flavours', ['id' => $id], '*', MUST_EXIST);

        // Init form and pass the $flavour object to it.
        $form = new \theme_boost_union\form\flavour_delete_form($PAGE->url, ['flavour' => $flavour]);

        // If the form was submitted.
        if ($data = $form->get_data()) {
            // Delete the flavour from the database table.
            $DB->delete_records('theme_boost_union_flavours', ['id' => $data->id]);

            // Get file storage.
            $fs = get_file_storage();

            // Delete the files from the filearea (including the dot folder).
            $fs->delete_area_files($context->id, 'theme_boost_union', 'flavours_look_logo', $data->id);
            $fs->delete_area_files($context->id, 'theme_boost_union', 'flavours_look_logocompact', $data->id);
            $fs->delete_area_files($context->id, 'theme_boost_union', 'flavours_look_favicon', $data->id);
            $fs->delete_area_files($context->id, 'theme_boost_union', 'flavours_look_backgroundimage', $data->id);

            // Delete fallback sheet. And delete them all because they get generated on building the all.css.
            fulldelete($CFG->tempdir . '/theme/boost_union/');

            // Reset theme cache.
            // This is necessary as the flavour asset URLs contain the themerev.
            theme_reset_all_caches();

            // Purge the flavours cache as well as the users might get other flavours which apply after the deletion.
            // We would have preferred using \core_cache\helper::purge_by_definition, but this just purges the session cache
            // of the current user and not for all users.
            \core_cache\helper::purge_by_event('theme_boost_union_flavours_deleted');

            // Show success notification.
            \core\notification::success(get_string('flavoursnotificationdeleted', 'theme_boost_union'));

            // Redirect to overview page.
            redirect(new core\url('/theme/boost_union/flavours/overview.php'));

            // Otherwise if the form was cancelled.
        } else if ($form->is_cancelled()) {
            // Redirect to overview page.
            redirect(new core\url('/theme/boost_union/flavours/overview.php'));

            // Otherwise if the form was not yet submitted.
        } else {
            // Fill the data into the form.
            $form->set_data($flavour);
        }

        break;
}

// Start page output.
echo $OUTPUT->header();

// Show form.
$form->display();

// Finish page output.
echo $OUTPUT->footer();
