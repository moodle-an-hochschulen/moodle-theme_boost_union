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

namespace theme_boost_union\local\snippets;

use stdClass;
use core\context\system;
use core\url;

/**
 * Library class containing solely static functions for dealing with SCSS snippets.
 *
 * @package    theme_boost_union
 * @copyright  2024 André Menrath, University of Graz <andre.menrath@uni-graz.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class snippets {
    /**
     * GitHub Repository of the Boost Union Community Snippets.
     *
     * @var string
     */
    public const COMMUNITY_REPOSITORY = 'https://github.com/moodle-an-hochschulen/moodle-theme_boost_union_snippets';

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
     * Base path SCSS snippets that are shipped with boost_union.
     *
     * @var string
     */
    public const BUILTIN_SNIPPETS_BASE_PATH = '/theme/boost_union/snippets/builtin/';

    /**
     * Gets the snippet file based on the meta information.
     *
     * @param string $name        The snippet's path.
     * @param source $source      The snippet's source.
     * @param bool   $stripheader Whether to strip the snippets file content header.
     * @return string
     */
    private static function get_snippet_file_content(string $name, source $source, bool $stripheader = false): string {
        global $CFG;

        if ($source === source::BUILT_IN) {
            $file = $CFG->dirroot . self::BUILTIN_SNIPPETS_BASE_PATH . $name;
            $content = is_readable($file) ? file_get_contents($file) : '';
        } else if (in_array($source, source::cases(), true)) {
            $fs = get_file_storage();
            $context = system::instance();
            $file = $fs->get_file($context->id, 'theme_boost_union', $source->get_filearea(), $source->get_itemid(), '/', $name);
            $content = $file ? $file->get_content() : '';
        } else {
            throw new \InvalidArgumentException('Invalid snippet source provided.');
        }

        if ($stripheader) {
            $content = parser::strip_file_comment($content);
        }

        return $content;
    }

    /**
     * Fetch snippets from the community repository and add them to the database.
     *
     * @return void
     */
    public static function refresh_community_repository() {
        global $DB;

        $url = self::COMMUNITY_REPOSITORY .'/archive/refs/heads/main.zip';

        $content = @file_get_contents($url);

        if ($content === false) {
            throw new \moodle_exception('Could not fetch the community repository archive as zip.');
        }

        $context = \context_system::instance();
        $source = source::COMMUNITY;

        $filerecord = [
            'contextid' => $context->id,
            'component' => 'theme_boost_union',
            'filearea' => $source->get_filearea(),
            'itemid' => $source->get_itemid(),
            'filepath' => '/',
            'filename' => 'main.zip',
            'timecreated' => time(),
            'timemodified' => time(),
        ];

        $fs = get_file_storage();
        $fs->create_file_from_string($filerecord, $content);

        if (defined('CLI_SCRIPT')) {
            mtrace("Downloaded latest snippet repository from $url");
        }

        importer::import_snippets_from_filearea($source);
        importer::cleanup_snippets();
    }

    /**
     * Populate and cleanup uploaded snippets.
     *
     * @return void
     */
    public static function populate_uploaded_snippets(): void {
        importer::import_snippets_from_filearea(source::UPLOADED);
        importer::cleanup_snippets();
    }

    /**
     * Get the preview images url via looking for a file with the same name.
     *
     * The preview file has currently the same name but a different file extension.
     *
     * @param string $name The snippet's name.
     * @param source $source The snippets source (uploaded, builtin etc.)
     * @return string|null The preview image's file url.
     */
    public static function get_snippet_preview_url($name, $source): url|null {
        global $DB;

        $fs = get_file_storage();
        $context = system::instance();

        if ($source === source::BUILT_IN) {
            return new url( '/theme/boost_union/snippets/builtin/' . str_replace('.scss', '.webp', $name));
        }

        foreach (self::ALLOWED_PREVIEW_FILE_EXTENSIONS as $extension) {
            $file = $fs->get_file(
                $context->id,
                'theme_boost_union',
                $source->get_filearea(),
                $source->get_itemid(),
                '/',
                str_replace( '.scss', '.' . $extension, $name)
            );

            if ($file) {
                break;
            }
        }

        if (!$file) {
            return null;
        }

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

    /**
     * Get the meta data of a snippet defined in the snippet code based on its name and source.
     *
     * @param string $name The snippet's path.
     * @param source $source The snippet's source.
     *
     * @return stdClass|null The snippet metadata object, or null if the snippet was not found.
     */
    public static function get_snippet_data($name, $source): stdClass|null {
        // Get the snippets file, based on the source.
        $filecontent = self::get_snippet_file_content($name, $source);

        // If the file does not exist or is not readable, we can not proceed.
        if (!$filecontent) {
            return null;
        }

        // Extract the meta from the SCSS file's top level multiline comment in WordPress style.
        $headers = parser::get_snippet_meta($filecontent);

        if (!$headers) {
            return null;
        }

        // Get the preview image as well.
        $image = self::get_snippet_preview_url($name, $source);

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
        $snippet->source = $source->value;
        $snippet->image = $image;
        $snippet->scss = self::get_snippet_file_content($name, $source, true);

        return $snippet;
    }

    /**
     * Combine snippets meta data from the snippets file with the database record.
     *
     * This is currently used for create the view for the settings table.
     *
     * @param array $snippetrecordset The recordset of snippets which are present in the database.
     *
     * @return array An array of snippet objects.
     */
    public static function compose_snippets_data($snippetrecordset): array {
        // Initialize snippets array for returning later.
        $snippets = [];

        // Iterate over the snippets which are present in the database.
        foreach ($snippetrecordset as $snippetrecord) {
            $source = source::tryFrom($snippetrecord->source);

            if (!$source) {
                continue;
            }

            // Get the meta information from the SCSS files' top multiline comment.
            $snippet = self::get_snippet_data($snippetrecord->name, $source);

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
        list($insql, $inparams) = $DB->get_in_or_equal($whereparts, SQL_PARAMS_NAMED);

        // Compose SQL base query.
        $sql = "SELECT *
                FROM {theme_boost_union_snippets} s
                WHERE enabled = '1' AND source ".$insql.
                " ORDER BY sortorder";
        $sqlparams = $inparams;

        // Get records.
        $data = $DB->get_recordset_sql($sql, $sqlparams);

        // Initialize SCSS code.
        $scss = '';

        // Iterate over all records.
        foreach ($data as $snippet) {
            // And add the SCSS code to the stack.
            $source = source::tryFrom($snippet->source);
            $scss .= parser::strip_file_comment(self::get_snippet_file_content($snippet->name, $source));
        }

        // Close the recordset.
        $data->close();

        // Return the SCSS code.
        return $scss;
    }

    /**
     * Get the enabled snippet sources.
     *
     * @param bool $returnvalues Whether to return the values, or the enum object.
     * @return array
     */
    public static function get_enabled_sources($returnvalues=true): array {
        $sources = [];

        // Check if each snippet source is enabled and add to the sources array.
        foreach (source::cases() as $source) {
            if (get_config('theme_boost_union', $source->get_is_enabled_setting_name()) === THEME_BOOST_UNION_SETTING_SELECT_YES) {
                if ($returnvalues) {
                    $sources[] = $source->value;
                } else {
                    $sources[] = $source;
                }

            }
        }

        // Return the enabled sources.
        return $sources;
    }

    /**
     * Get all fileareas where snippets are stored.
     *
     * @return string[]
     */
    public static function get_fileareas(): array {
        $fileareas = [];

        // Check if each snippet source is enabled and add to the sources array.
        foreach (source::cases() as $source) {
            $filearea = $source->get_filearea();
            if ($filearea) {
                $fileareas[] = $filearea;
            }
        }

        // Return the enabled sources.
        return $fileareas;
    }

    /**
     * Populate builtin snippets in the database.
     *
     * @return void
     */
    public static function populate_builtin_snippets(): void {
        importer::import_builtin_snippets();
    }
}
