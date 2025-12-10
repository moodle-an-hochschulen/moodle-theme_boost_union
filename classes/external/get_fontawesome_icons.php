<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Theme Boost Union - External function to get FontAwesome icons
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\external;

use core_external\external_api;
use core_external\external_description;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;

/**
 * Provides the theme_boost_union_get_fontawesome_icons external function.
 */
class get_fontawesome_icons extends external_api {
    /**
     * @var int This constant defines the maximum number of FontAwesome icons.
     *
     * For now, the limit is set higher than the whole list of icons to allow the whole list to be presented as one.
     */
    private const MAX_RESULTS = 3000;

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'query' => new external_value(PARAM_RAW, 'The search query', VALUE_REQUIRED),
        ]);
    }

    /**
     * Get FontAwesome icons matching the given query.
     *
     * @param string $query The search request.
     * @return array
     */
    public static function execute(string $query): array {
        global $CFG, $DB, $PAGE;

        // Require lib.php (to ensure the theme_boost_union_build_fa_icon_map function is available).
        // Normally, lib.php is autoloaded by Moodle core, but in PHPUnit tests it may not be the case.
        require_once($CFG->dirroot . '/theme/boost_union/lib.php');
        require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

        $params = external_api::validate_parameters(self::execute_parameters(), [
            'query' => $query,
        ]);
        $query = clean_param($params['query'], PARAM_TEXT);

        // Set the page context.
        // Otherwise, the theme_boost_union_build_fa_icon_map() function will complain.
        $systemcontext = \context_system::instance();
        $PAGE->set_context($systemcontext);

        // Get the icon map using the existing function.
        $iconmap = theme_boost_union_build_fa_icon_map();

        // Filter icons based on the search query if provided.
        $results = [];
        $count = 0;
        $overflow = false;
        if (!empty($query)) {
            foreach ($iconmap as $key => $icon) {
                if (empty($key)) {
                    continue;
                }
                // Search in key and icon class.
                if (
                    stripos($key, $query) !== false ||
                    (isset($icon['class']) && stripos($icon['class'], $query) !== false)
                ) {
                    // If we haven't reached the maximum results yet.
                    if ($count <= self::MAX_RESULTS) {
                        // Add the icon to the results.
                        $results[$key] = $icon;
                        $count++;

                        // Otherwise.
                    } else {
                        // Stop as we have too many results.
                        $overflow = true;
                        break;
                    }
                }
            }
        } else {
            // Return all icons if no query is provided (limit to MAX_RESULTS).
            $results = array_slice($iconmap, 0, self::MAX_RESULTS, true);
            if (count($iconmap) > self::MAX_RESULTS) {
                // If we have more results than MAX_RESULTS, set overflow to true.
                $overflow = true;
            } else {
                $overflow = false;
            }
        }

        // Format the results for return.
        $formattedresults = [];
        foreach ($results as $name => $icon) {
            $formattedresults[] = [
                'name' => $name,
                'class' => $icon['class'],
                'source' => $icon['source'],
            ];
        }

        return ['icons' => $formattedresults,
                'maxicons' => self::MAX_RESULTS,
                'overflow' => $overflow,
        ];
    }

    /**
     * Describes the external function result value.
     *
     * @return external_description
     */
    public static function execute_returns(): external_description {
        return new external_single_structure([
            'icons' => new external_multiple_structure(
                new external_single_structure([
                    'name' => new external_value(PARAM_TEXT, 'The icon name'),
                    'class' => new external_value(PARAM_TEXT, 'The icon\'s FontAwesome class'),
                    'source' => new external_value(PARAM_ALPHA, 'The icon source'),
                ])
            ),
            'maxicons' => new external_value(PARAM_INT, 'Configured maximum icons in the list.'),
            'overflow' => new external_value(PARAM_BOOL, 'Were there more icons than maxicons found?'),
        ]);
    }
}
