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

/**
 * Class parser
 *
 * @package    theme_boost_union
 * @copyright  2025 André Menrath <andre.menrath@uni-graz.at>, University of Graz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class parser {
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
     * @param string $filecontent  The filedata
     *
     * @return array|false Array of file header values keyed by header name. False if file not valid.
     */
    public static function get_snippet_meta($filecontent): array|false {
        // Make sure we catch CR-only line endings.
        $filedata = str_replace( "\r", "\n", $filecontent );

        // If the file is empty, we can not proceed.
        if (empty($filedata)) {
            return false;
        }

        // Initialize an array to hold the headers.
        $headers = [];

        // Scan for each snippet header meta information in the files top scss comment.
        foreach (self::SNIPPET_HEADERS as $regex) {
            if (preg_match('/^(?:[ \t]*)?[ \t\/*#@]*' . preg_quote($regex, '/') . ':(.*)$/mi', $filecontent, $match)
                && $match[1]) {
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
     * Validate a snippet (only the file header, not the css)
     *
     * @param mixed $filecontent
     * @return bool
     */
    public static function validate_snippet($filecontent): bool {
        return is_array(self::get_snippet_meta($filecontent));
    }

    /**
     * Strip the top level multiline filecomment containing the snippet.
     *
     * @param string $filecontent
     * @return string
     */
    public static function strip_file_comment($filecontent): string {
        return preg_replace('/\/\*\*[\s\S]*?\*\/\s*/', '', $filecontent);
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
}
