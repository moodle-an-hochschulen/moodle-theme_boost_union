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
 * Theme Boost Union - SCSS snippets
 *
 * @package    theme_boost_union
 * @copyright  2024 André Menrath, University of Graz <andre.menrath@uni-graz.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

use stdClass;

/**
 * Library class containing solely static functions for dealing with SCSS snippets.
 *
 * @package    theme_boost_union
 * @copyright  2024 André Menrath, University of Graz <andre.menrath@uni-graz.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class snippets {
    /**
     * List of all available Snippet Meta File headers.
     *
     * @var array
     */
    const SNIPPET_HEADERS = [
        'Snippet Title',
        'Goal',
        'Description',
        'Scope',
        'Creator',
        'Usage note',
        'Tracker issue',
        'Tested on',
    ];

    /**
     * Base path SCSS snippets that are shipped with boost_union.
     *
     * @var string
     */
    const BUILTIN_SNIPPETS_BASE_PATH = '/theme/boost_union/snippets/builtin/';

    /**
     * Allowed file extensions for the visual preview of the SCSS snippet in the more detail modal.
     *
     * The order in this array also reflects their priority if multiple matches should exist.
     *
     * @var array
     */
    const ALLOWED_PREVIEW_FILE_EXTENSIONS = [
        'webp',
        'png',
        'jpg',
        'jpeg',
        'gif',
    ];

    /**
     * Constant for identifying builtin snippets.
     *
     * @var string
     */
    const SOURCE_BUILTIN = 'theme_boost_union';

    /**
     * Constant for identifying uploaded snippets.
     *
     * @var string
     */
    const SOURCE_UPLOADED = 'uploaded';

    /**
     * Gets the snippet file based on the meta information.
     *
     * @param string $name The snippet's path.
     * @return string|null
     */
    public static function get_builtin_snippet_file($name): string|null {
        global $CFG;

        $file = $CFG->dirroot . self::BUILTIN_SNIPPETS_BASE_PATH . $name;

        return is_readable($file) ? $file : null;
    }

    /**
     * Get the preview images url via looking for a file with the same name.
     *
     * The preview file has currently the same name but a different file extension.
     *
     * @param string $name The snippet's name.
     * @param string $source The snippets source (uploaded, builtin etc.)
     * @return string|null The preview image's file url.
     */
    public static function get_snippet_preview_url($name, $source) {
        global $CFG;

        if ($source === self::SOURCE_BUILTIN) {
            $basepath = $CFG->dirroot . self::BUILTIN_SNIPPETS_BASE_PATH;
            $file = self::search_preview_file($basepath, $name);
            if ($file) {
                // Compose the file's URL.
                $url = new \moodle_url(substr($file, strlen($CFG->dirroot)));
                // And check if the file is readable.
                return is_readable($file) ? $url : null;
            }
        } else if ($source === self::SOURCE_UPLOADED) {
            // Get snippets file storage.
            $systemcontext = \core\context\system::instance();
            $fs = get_file_storage();
            $files = $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'uploadedsnippets', false, 'itemid', false);

            // Iterate over the files.
            foreach ($files as $file) {
                // Check if the file is a preview file by checking the filename.
                $pathinfo = pathinfo($file->get_filename());
                $filename = $pathinfo['filename'];
                $extension = $pathinfo['extension'];
                if (str_starts_with(basename($name), $filename) && in_array($extension, self::ALLOWED_PREVIEW_FILE_EXTENSIONS)) {
                    // Compose the file's URL.
                    $filename = basename($name);
                    $url = \moodle_url::make_pluginfile_url(
                        $file->get_contextid(),
                        $file->get_component(),
                        $file->get_filearea(),
                        $file->get_itemid(),
                        $file->get_filepath(),
                        $file->get_filename(),
                    );
                    return $url;
                }
            }
        }

        // If anything went wrong, return null just as if no snippet preview is present.
        return null;
    }

    /**
     * Get the SCSS of a snippet based on its path and source.
     *
     * Returns an empty string as a fallback if the snippet is not found.
     *
     * @param string $name The snippet's name.
     * @param string $source The snippet's source.
     *
     * @return string
     */
    public static function get_snippet_scss($name, $source): string {
        if ($source === self::SOURCE_BUILTIN) {
            // Get the snippets file, based on the source.
            $file = self::get_builtin_snippet_file($name);

            // If the file does not exist or is not readable, we simply return an empty string.
            if (!$file) {
                return '';
            }

            // Get and return the whole file content (which will contain the SCSS comments as well).
            $scss = file_get_contents($file);
        } else if ($source === self::SOURCE_UPLOADED) {
            // Get the file from the file storage.
            $fs = \get_file_storage();
            $systemcontext = \core\context\system::instance();
            $file = $fs->get_file($systemcontext->id, 'theme_boost_union', 'uploadedsnippets', 0, '/', $name);

            // If we have found a file.
            if ($file) {
                // Get its content.
                $scss = $file->get_content();
            }
        } else {
            $scss = '';
        }

        // Return the SCSS or an empty string if reading the file has failed.
        return $scss ?: '';
    }

    /**
     * Get the meta data of a snippet defined in the snippet code based on its name and source.
     *
     * @param string $name The snippet's path.
     * @param string $source The snippet's source.
     *
     * @return stdClass|null The snippet metadata object, or null if the snippet was not found.
     */
    public static function get_snippet_meta($name, $source): stdClass|null {
        if ($source === self::SOURCE_BUILTIN) {
            // Get the snippets file, based on the source.
            $file = self::get_builtin_snippet_file($name);

            // If the file does not exist or is not readable, we can not proceed.
            if (is_null($file)) {
                return null;
            }

            // Extract the meta from the SCSS file's top level multiline comment in WordPress style.
            $headers = self::get_snippet_meta_from_file($file);

            if (!$headers) {
                return null;
            }

            // Get the preview image as well.
            $image = self::get_snippet_preview_url($name, $source);
        } else if ($source === self::SOURCE_UPLOADED) {
            // Get the file from the file storage.
            $fs = \get_file_storage();
            $systemcontext = \core\context\system::instance();
            $file = $fs->get_file($systemcontext->id, 'theme_boost_union', 'uploadedsnippets', 0, '/', basename($name));

            // If we have not found a file.
            if (!$file) {
                // We are done already.
                return null;
            }

            // Extract the meta from the SCSS file content.
            $headers = self::get_snippet_meta_from_file_content($file->get_content());

            // Get the preview image as well.
            $image = self::get_snippet_preview_url($file->get_filename(), $source);
        }

        // Create an object containing the metadata.
        $snippet = new stdClass();
        $snippet->title = $headers['Snippet Title'];
        $snippet->description = $headers['Description'];
        $snippet->scope = $headers['Scope'];
        $snippet->goal = $headers['Goal'];
        $snippet->creator = $headers['Creator'];
        $snippet->usagenote = $headers['Usage note'];
        $snippet->testedon = $headers['Tested on'];
        $snippet->trackerissue = $headers['Tracker issue'];
        $snippet->source = $source;
        $snippet->image = $image;

        return $snippet;
    }

    /**
     * Combine snippets meta data from the snippets file with the database record.
     *
     * This is currently used for create the view for the settings table.
     *
     * @param \moodle_recordset $snippetrecordset The recordset of snippets which are present in the database.
     *
     * @return array An array of snippet objects.
     */
    public static function compose_snippets_data($snippetrecordset): array {
        // Initialize snippets array for returning later.
        $snippets = [];

        // Iterate over the snippets which are present in the database.
        foreach ($snippetrecordset as $snippetrecord) {
            // Get the meta information from the SCSS files' top multiline comment.
            $snippet = self::get_snippet_meta($snippetrecord->name, $snippetrecord->source);

            // Only if snippet metadata is found, it will be added to the returned snippets array.
            if ($snippet) {
                // Merge the two objects.
                $snippets[] = (object) array_merge((array) $snippetrecord, (array) $snippet);
            }
        }

        return $snippets;
    }

    /**
     * Checks which snippets are active and returns their scss.
     *
     * @return string
     */
    public static function get_enabled_snippet_scss(): string {
        global $DB;

        // Prepare WHERE clause based on the enabled sources.
        $whereparts = self::get_enabled_sources();
        if (count($whereparts) == 0) {
            // If no sources are enabled, we do not want to use any snippets.
            // We do this by restricting the query to an impossible source.
            $whereparts[] = 'impossible';
        }
        [$insql, $inparams] = $DB->get_in_or_equal($whereparts, SQL_PARAMS_NAMED);

        // Compose SQL base query.
        $sql = "SELECT *
                FROM {theme_boost_union_snippets} s
                WHERE enabled = '1' AND source " . $insql .
                " ORDER BY sortorder";
        $sqlparams = $inparams;

        // Get records.
        $data = $DB->get_recordset_sql($sql, $sqlparams);

        // Initialize SCSS code.
        $scss = '';

        // Iterate over all records.
        foreach ($data as $snippet) {
            // And add the SCSS code to the stack.
            $scss .= self::get_snippet_scss($snippet->name, $snippet->source);
        }

        // Close the recordset.
        $data->close();

        // Return the SCSS code.
        return $scss;
    }

    /**
     * Strips close comment and close php tags from file headers.
     *
     * @copyright WordPress https://developer.wordpress.org/reference/functions/_cleanup_header_comment/
     *
     * @param string $str Header comment to clean up.
     *
     * @return string
     */
    private static function cleanup_header_comment($str) {
        return trim(preg_replace('/\s*(?:\*\/|\?>).*/', '', $str));
    }

    /**
     * Retrieves Snippet metadata from a files content.
     *
     * Searches for metadata in the first 8 KB of a file, such as a plugin or theme.
     * Each piece of metadata must be on its own line. Fields can not span multiple
     * lines, the value will get cut at the end of the first line.
     *
     * If the file data is not within that first 8 KB, then the author should correct
     * the snippet.
     *
     * @copyright forked from https://developer.wordpress.org/reference/functions/get_file_data/
     *
     * @param string $filedata   The filedata
     *
     * @return string[]|false Array of file header values keyed by header name. False if file not valid.
     */
    public static function get_snippet_meta_from_file_content($filedata): array|false {
        // Make sure we catch CR-only line endings.
        $filedata = str_replace("\r", "\n", $filedata);

        // If the file is empty, we can not proceed.
        if (empty($filedata)) {
            return false;
        }

        // Initialize an array to hold the headers.
        $headers = [];

        // Scan for each snippet header meta information in the files top scss comment.
        foreach (self::SNIPPET_HEADERS as $regex) {
            if (
                preg_match('/^(?:[ \t]*)?[ \t\/*#@]*' . preg_quote($regex, '/') . ':(.*)$/mi', $filedata, $match)
                && $match[1]
            ) {
                $headers[$regex] = self::cleanup_header_comment($match[1]);
            } else {
                $headers[$regex] = '';
            }
        }

        // The title is the only required meta-key that actually must be set.
        // If it is not present, we can not proceed.
        if (array_key_exists('Snippet Title', $headers) && !empty($headers['Snippet Title'])) {
            return $headers;
        }

        return false;
    }

    /**
     * Retrieves Snippet metadata from a file.
     *
     * @param mixed $filename
     * @return bool|string[]
     */
    public static function get_snippet_meta_from_file($filename) {
        $filedata = file_get_contents($filename, false, null, 0, 8192);

        if (false === $filedata) {
            $filedata = '';
        }
        return self::get_snippet_meta_from_file_content($filedata);
    }

    /**
     * Retrieve all builtin SCSS snippets from the actual scss files on disk.
     *
     * @return string[]
     */
    private static function get_builtin_snippet_names(): array {
        global $CFG;
        // Get an array of all .scss files in the directory.
        $files = glob($CFG->dirroot . self::BUILTIN_SNIPPETS_BASE_PATH . '*.scss');

        // If there are files.
        if (is_array($files)) {
            // Return an array of the basenames of the files.
            $basenames = array_map(function ($file) {
                return basename($file);
            }, $files);
            return $basenames;

            // Otherwise.
        } else {
            return [];
        }
    }

    /**
     * Get the enabled snippet sources.
     *
     * @return array
     */
    public static function get_enabled_sources(): array {
        global $CFG;

        // Require lib.php (to ensure the THEME_BOOST_UNION_SETTING_SELECT_YES constant is available).
        // Normally, lib.php is autoloaded by Moodle core, but in PHPUnit tests it may not be the case.
        require_once($CFG->dirroot . '/theme/boost_union/lib.php');

        // Initialize sources array.
        $sources = [];

        // If builtin snippets are enabled.
        if (get_config('theme_boost_union', 'enablebuiltinsnippets') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            $sources[] = self::SOURCE_BUILTIN;
        }
        // If uploaded snippets are enabled.
        if (get_config('theme_boost_union', 'enableuploadedsnippets') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            $sources[] = self::SOURCE_UPLOADED;
        }

        // Return.
        return $sources;
    }

    /**
     * Process an uploaded SCSS snippet file – check if it is a valid snippet and add it to the filearea.
     *
     * @param mixed $filename
     * @param mixed $zipdir
     * @return void
     */
    private static function process_uploaded_scss_file($filename, $zipdir): void {
        $filecontent = \file_get_contents($zipdir . '/' . $filename, false);

        if (!$filecontent) {
            return;
        }

        // If the filename contains "boilerplate/boilerplate.scss", it is most likely the downloaded zip
        // from our SCSS snippets repository. In this case, we want to skip this file.
        if (str_contains($filename, 'boilerplate/boilerplate.scss')) {
            return;
        }

        // Kind of validate the snippet.
        if (!self::get_snippet_meta_from_file_content($filecontent)) {
            return;
        }

        // Get file storage.
        $fs = get_file_storage();
        $systemcontext = \context_system::instance();

        // Build file record, dropping the folder structure from the filename.
        $filerecord = [
            'contextid' => $systemcontext->id,
            'component' => 'theme_boost_union',
            'filearea' => 'uploadedsnippets',
            'itemid' => 0,
            'filepath' => '/',
            'filename' => basename($filename),
            'timecreated' => time(),
            'timemodified' => time(),
        ];

        // First check if the file already exists and if so, delete it.
        if (
            $fs->file_exists(
                $filerecord['contextid'],
                $filerecord['component'],
                $filerecord['filearea'],
                $filerecord['itemid'],
                $filerecord['filepath'],
                $filerecord['filename']
            )
        ) {
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

        // Save snippet to filearea for uploaded snippets.
        $fs->create_file_from_string($filerecord, $filecontent);

        // Save preview image for that snippet if it exists.
        $preview = self::search_preview_file($zipdir, $filename);
        if ($preview) {
            // Get the filename from the full path.
            $previewfilename = basename($preview);

            // Update the file record for the preview file.
            $filerecord['filename'] = $previewfilename;

            // Again, check first if the file already exists and if so, delete it.
            if (
                $fs->file_exists(
                    $filerecord['contextid'],
                    $filerecord['component'],
                    $filerecord['filearea'],
                    $filerecord['itemid'],
                    $filerecord['filepath'],
                    $filerecord['filename']
                )
            ) {
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

            // Save the preview file.
            $fs->create_file_from_pathname($filerecord, $preview);
        }
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
                    '.{' . implode(',', self::ALLOWED_PREVIEW_FILE_EXTENSIONS) . '}',
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

    /**
     * Process the SCSS snippets within an uploaded ZIP file and add valid ones to the library.
     *
     * @param mixed $file
     * @return void
     */
    private static function process_zip_file($file) {
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
                self::process_uploaded_scss_file($filename, $zipdir);
            }
        }

        // Delete the file from the temporary directory.
        $file->delete();
    }

    /**
     * Parse all uploaded snippets from the file area and add them to the database.
     *
     * @return void
     */
    public static function parse_uploaded_snippets() {
        global $DB;

        // Get the files from the file storage.
        $systemcontext = \context_system::instance();
        $fs = get_file_storage();
        $rawfiles = $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'uploadedsnippets', false, 'itemid', false);

        // Parse all zip files. All valid .scss snippet files will be extracted to the root filearea path along with their previews.
        // All other files will be deleted.
        foreach ($rawfiles as $file) {
            if ($file->get_mimetype() === 'application/zip') {
                self::process_zip_file($file);
            }
        }

        // Get the files from the file storage again, now that we have processed the zip files.
        $files = $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'uploadedsnippets', false, 'itemid', false);

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
        $presentnames = array_map(function ($snippet) {
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

            // Validate the files again.
            $filecontent = $file->get_content();
            if (!self::get_snippet_meta_from_file_content($filecontent)) {
                continue;
            }

            // Get the filename.
            $name = $file->get_filename();

            // If the snippet is not in the database yet.
            if (!in_array([$name, self::SOURCE_UPLOADED], $presentnames)) {
                // Add it to the database (raising the sort order).
                $DB->insert_record(
                    'theme_boost_union_snippets',
                    [
                        'name' => $name,
                        'source' => self::SOURCE_UPLOADED,
                        'sortorder' => ++$sortorder,
                    ]
                );
            }
        }

        // Commit transaction.
        $transaction->allow_commit();
    }

    /**
     * Return the area files of the uploaded snippets.
     *
     * @return \stored_file[]
     */
    private static function get_uploaded_snippet_files() {
        $systemcontext = \context_system::instance();
        $fs = get_file_storage();
        return $fs->get_area_files($systemcontext->id, 'theme_boost_union', 'uploadedsnippets', false, 'itemid', false);
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
        }, self::get_uploaded_snippet_files());

        // Merge all currently available snippets from all sources.
        $existing = array_merge($uploaded, self::get_builtin_snippet_names());

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
     * Make sure that the builtin snippets are in the database.
     *
     * @return void
     */
    public static function add_builtin_snippets(): void {
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
        $presentnames = array_map(function ($snippet) {
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
                        'source' => self::SOURCE_BUILTIN,
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
}
