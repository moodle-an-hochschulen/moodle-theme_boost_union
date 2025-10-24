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

namespace theme_boost_union\local\snippets;

use core\context\system;
use stored_file;

/**
 * Class import
 *
 * @package    theme_boost_union
 * @copyright  2025 André Menrath <andre.menrath@uni-graz.at>, University of Graz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class importer {
    /**
     * Parse all uploaded snippets from the file area and add them to the database.
     *
     * @param source $source The snippets source.
     * @return void
     */
    public static function import_snippets_from_filearea($source) {
        global $DB;

        $rawfiles = self::get_area_files($source);

        // Parse all zip files. All valid .scss snippet files will be extracted to the root filearea path along with their previews.
        // All other files will be deleted.
        foreach ($rawfiles as $file) {
            if ($file->get_mimetype() === 'application/zip') {
                self::process_zip_file($file, $source);
            }
        }

        // Get the files from the file storage again, now that we have processed the zip files.
        $files = self::get_area_files($source);

        // Get snippets which are known in the database.
        $snippets = $DB->get_records(
            'theme_boost_union_snippets',
            [],
            'sortorder DESC',
            'id,name,sortorder,source'
        );

        // Get the highest sortorder present.
        $sortorder = empty($snippets) ? 0 : intval(reset($snippets)->sortorder);

        // Prepare an array with all the present snippet names.
        $presentnames = array_map(function($snippet) {
            return [$snippet->name, $snippet->source];
        }, $snippets);

        // Start a transaction to ensure that the database is not left in an inconsistent state.
        $transaction = $DB->start_delegated_transaction();

        // Iterate over the files.
        foreach ($files as $file) {
            // Only use scss files.
            if ($file->get_mimetype() !== 'text/x-scss') {
                continue;
            }

            $filecontent = $file->get_content();
            if (!parser::validate_snippet($filecontent)) {
                continue;
            }

            // Get the filename.
            $name = $file->get_filename();

            // If the snippet is not in the database yet.
            if (!in_array([$name, $source->value], $presentnames)) {
                // Add it to the database (raising the sort order).
                $DB->insert_record(
                    'theme_boost_union_snippets',
                    [
                        'name' => $name,
                        'source' => $source->value,
                        'sortorder' => ++$sortorder,
                    ]
                );
            }
        }

        // Commit transaction.
        $transaction->allow_commit();
    }

    /**
     * Make sure that the builtin snippets are in the database.
     *
     * @return void
     */
    public static function import_builtin_snippets(): void {
        global $DB;

        // Get builtin snippets that are present on disk.
        $names = self::get_builtin_snippet_names();

        // Get builtin snippets which are known in the database.
        $snippets = $DB->get_records(
                'theme_boost_union_snippets',
                [],
                'sortorder DESC',
                'id,name,sortorder'
        );

        // Get the highest sortorder present.
        $sortorder = empty($snippets) ? 0 : intval(reset($snippets)->sortorder);

        // Prepare an array with all the present builtin snippet names.
        $presentnames = array_map(function($snippet) {
            return $snippet->name;
        }, $snippets);

        // Start a transaction to ensure that the database is not left in an inconsistent state.
        $transaction = $DB->start_delegated_transaction();

        // Iterate over the builtin snippets that are present on disk.
        foreach ($names as $name) {
            // If the snippet is not in the database yet.
            if (!in_array($name, $presentnames)) {
                // Add it to the database (raising the sort order).
                $DB->insert_record(
                    'theme_boost_union_snippets',
                    [
                        'name' => $name,
                        'source' => source::BUILT_IN->value,
                        'sortorder' => ++$sortorder,
                    ]
                );
            }
        }

        // Cleanup snippets.
        self::cleanup_snippets();

        // Commit transaction.
        $transaction->allow_commit();
    }

    /**
     * Retrieve all builtin SCSS snippets from the actual scss files on disk.
     *
     * @return string[]
     */
    private static function get_builtin_snippet_names(): array {
        global $CFG;
        // Get an array of all .scss files in the directory.
        $files = glob($CFG->dirroot . snippets::BUILTIN_SNIPPETS_BASE_PATH . '*.scss');

        // If there are files.
        if (is_array($files)) {
            // Return an array of the basenames of the files.
            $basenames = array_map(function($file) {
                return basename($file);
            }, $files);
            return $basenames;

            // Otherwise.
        } else {
            return [];
        }
    }

    /**
     * Process an uploaded SCSS snippet file – check if it is a valid snippet and add it to the filearea.
     *
     * @param string $filename
     * @param string $zipdir
     * @param source $source
     * @return void
     */
    public static function import_snippet($filename, $zipdir, $source): void {
        $filecontent = \file_get_contents($zipdir.'/'.$filename, false);

        if (!$filecontent) {
            return;
        }

        // If the filename contains "boilerplate/boilerplate.scss", it is most likely the downloaded zip
        // from our SCSS snippets repository. In this case, we want to skip this file.
        if (str_contains($filename, 'boilerplate/boilerplate.scss')) {
            return;
        }

        if (!parser::validate_snippet($filecontent)) {
            return;
        }

        // Get file storage.
        $fs = get_file_storage();
        $systemcontext = \context_system::instance();

        // Build file record, dropping the folder structure from the filename.
        $filerecord = [
            'contextid' => $systemcontext->id,
            'component' => 'theme_boost_union',
            'filearea' => $source->get_filearea(),
            'itemid' => $source->get_itemid(),
            'filepath' => '/',
            'filename' => basename($filename),
            'timecreated' => time(),
            'timemodified' => time(),
        ];

        // First check if the file already exists and if so, delete it.
        self::delete_file_if_exists($filerecord);

        // Save snippet to filearea for uploaded snippets.
        $fs->create_file_from_string($filerecord, $filecontent);

        self::cli_log('Refreshed snippet: ' . $source->value . ' – ' . $filename . '\n');

        // Save preview image for that snippet if it exists.
        $preview = self::search_preview_file($zipdir, $filename);
        if ($preview) {
            // Get the filename from the full path.
            $previewfilename = basename($preview);

            // Update the file record for the preview file.
            $filerecord['filename'] = $previewfilename;
            $filerecord['itemid'] = $source->get_itemid();

            // Again, check first if the file already exists and if so, delete it.
            self::delete_file_if_exists($filerecord);

            // Save the preview file.
            $fs->create_file_from_pathname($filerecord, $preview);

            self::cli_log("Refreshed preview file for: $filename");
        }
    }

    /**
     * Remove non existing snippets from the database and remove possible gaps in the sortorder.
     *
     * @return void
     */
    public static function cleanup_snippets(): void {
        global $DB;

        // Get snippets which are in the database.
        $snippets = $DB->get_records(
            'theme_boost_union_snippets',
            [],
            'sortorder DESC',
            'id,name,sortorder,source'
        );

        // Get existing existing uploaded snippets.
        $uploaded = array_map(function ($file) {
            return $file->get_filename();
        }, self::get_area_files(source::UPLOADED));

        // Get existing existing uploaded snippets.
        $community = array_map(function ($file) {
            return $file->get_filename();
        }, self::get_area_files(source::COMMUNITY));

        $builtin = get_config('theme_boost_union', 'enablebuiltinsnippets') ? self::get_builtin_snippet_names() : [];

        // Merge all currently available snippets from all sources.
        $existing = array_merge($uploaded, $community, $builtin);

        // Get snippets that are in the DB but not available.
        $delete = [];
        foreach ($snippets as $key => $snippet) {
            if (!in_array($snippet->name, $existing)) {
                $delete[] = $snippet->id;
            }
        }

        // If there are snippets to delete.
        if (!empty($delete)) {
            // Delete snippets that are not available anymore.
            $DB->delete_records_list('theme_boost_union_snippets', 'id', $delete);
            // Fetch remaining snippets, ordered by sortorder.
            $remaining = $DB->get_records('theme_boost_union_snippets', null, 'sortorder ASC');
            // Update each remaining snippet with a new sort order to remove gaps.
            $newsortorder = 1;
            foreach ($remaining as $snippet) {
                if ($snippet->sortorder != $newsortorder) {
                    $DB->set_field('theme_boost_union_snippets', 'sortorder', $newsortorder, ['id' => $snippet->id]);
                }
                $newsortorder++;
            }
        }
    }

    /**
     * Write logs when runned in cli mode.
     *
     * @param string $message
     * @return void
     */
    private static function cli_log($message): void {
        if (defined('CLI_SCRIPT')) {
            mtrace($message);
        }
    }

    /**
     * Get area files by snippet source.
     *
     * @param \theme_boost_union\local\snippets\source $source
     * @return stored_file[]
     */
    private static function get_area_files(source $source) {
        $context = system::instance();
        $fs = get_file_storage();
        return $fs->get_area_files($context->id, 'theme_boost_union', $source->get_filearea(), false, 'itemid', false);
    }

    /**
     * Delete a file if it exists.
     *
     * @param array $filerecord
     * @return void
     */
    private static function delete_file_if_exists($filerecord): void {
        $fs = get_file_storage();

        if ($fs->file_exists(
            $filerecord['contextid'],
            $filerecord['component'],
            $filerecord['filearea'],
            $filerecord['itemid'],
            $filerecord['filepath'],
            $filerecord['filename']
        )) {
            $existingfile = $fs->get_file(
                $filerecord['contextid'],
                $filerecord['component'],
                $filerecord['filearea'],
                $filerecord['itemid'],
                $filerecord['filepath'],
                $filerecord['filename']
            );
            if ($existingfile) {
                $existingfile->delete();
            }
        }
    }

    /**
     * Process the SCSS snippets within an uploaded ZIP file and add valid ones to the library.
     *
     * @param stored_file $file
     * @param source $source
     * @return void
     */
    private static function process_zip_file($file, $source) {
        // Extract the zip file to a temporary directory.
        $fp = get_file_packer('application/zip');
        $zipdir = \make_request_directory(false);
        $zipcontents = $fp->extract_to_pathname($file, $zipdir);

        // If this was not a zip file or the extraction failed, we do not want to proceed.
        if (!$zipcontents) {
            $file->delete();
            return;
        }

        // Iterate over the extracted files and process them.
        foreach ($zipcontents as $filename => $unzipresult) {
            if ($unzipresult && $filename && strtolower(substr($filename, -5)) === '.scss') {
                self::import_snippet($filename, $zipdir, $source);
            }
        }

        // Delete the file from the temporary directory.
        $file->delete();
    }

    /**
     * Search for a snippet preview image in the same path.
     *
     * @param string $basepath
     * @param string $name
     * @return string|null
     */
    private static function search_preview_file($basepath, $name): string|null {
        // Ensure there is a directory seperator at the end of the base path.
        $basepath = rtrim($basepath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        // Search for the .scss suffix in the path.
        $search = '.scss';
        $pos = strrpos($name, $search);
        if ($pos !== false) {
            // Compose the file pattern that searched for files with the same basename and the supported extensions.
            $pattern = $basepath .
                substr_replace(
                    $name,
                    '.{' . implode(',', snippets::ALLOWED_PREVIEW_FILE_EXTENSIONS) . '}',
                    $pos,
                    strlen($search)
            );
            // Search for the preview file.
            $files = glob($pattern, GLOB_BRACE);
            // Select the first match of the found preview file(s).
            if (!empty($files)) {
                return $files[0];
            }
        }
        return null;
    }
}
