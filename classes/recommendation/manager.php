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
 * Recommendation manager.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\recommendation;

use theme_boost_union\table\recommendations_overview;

/**
 * Manager to collect and evaluate recommendations.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class manager {
    /**
     * Return recommendation instances.
     *
     * @param string|null $category Optional category constant to filter recommendations by.
     * @return recommendation[] Array of recommendation instances keyed by recommendation id.
     */
    public static function get_recommendations(?string $category = null): array {
        // Initialize empty result array.
        $recommendations = [];

        // Iterate over all discovered recommendation classes.
        foreach (self::get_recommendation_classes() as $classname) {
            // Instantiate the recommendation and check it.
            $instance = self::instantiate_recommendation($classname);
            if ($instance === null) {
                continue;
            }

            // Skip recommendations from other categories if a filter is set.
            if ($category !== null && $instance->get_category() !== $category) {
                continue;
            }

            // Check for duplicate recommendation ids.
            $id = $instance->get_id();
            if (isset($recommendations[$id])) {
                debugging('Duplicate recommendation id "' . $id . '" for class "' . $classname . '".', DEBUG_DEVELOPER);
                continue;
            }

            // Add to result keyed by recommendation id.
            $recommendations[$id] = $instance;
        }

        // Sort recommendations by status priority (defined by get_supported_statuses()) and title.
        $statusorder = array_flip(array_keys(self::get_supported_statuses()));
        usort($recommendations, function (recommendation $a, recommendation $b) use ($statusorder): int {
            $statusa = self::get_effective_status($a);
            $statusb = self::get_effective_status($b);
            $statuscmp = ($statusorder[$statusa] ?? PHP_INT_MAX) <=>
                ($statusorder[$statusb] ?? PHP_INT_MAX);
            if ($statuscmp !== 0) {
                return $statuscmp;
            }

            return strcmp($a->get_title(), $b->get_title());
        });

        // Return the result.
        return $recommendations;
    }

    /**
     * Return a recommendation instance by id.
     *
     * The $recommendationid may contain slash-separated arguments (e.g. "myrecommendation/3").
     * In that case only the part before the first slash is used to look up the recommendation
     * and the arguments are stored in the recommendation instance.
     *
     * @param string $recommendationid Recommendation id, optionally with slash-separated args.
     * @return recommendation|null
     */
    public static function get_recommendation_by_id(string $recommendationid): ?recommendation {
        // Parse the recommendation id and optional args.
        ['id' => $baseid, 'args' => $args] = self::parse_recommendation_id($recommendationid);

        // Iterate over all recommendations and return the one with the matching id.
        foreach (self::get_recommendations() as $recommendation) {
            if ($recommendation->get_id() === $baseid) {
                // If arguments are provider.
                if (!empty($args)) {
                    // If the recommendation supports arguments, pass them via set_args().
                    if ($recommendation->supports_args()) {
                        $recommendation->set_args($args);

                        // Otherwise log a debug message.
                    } else {
                        debugging(
                            'Recommendation instance "' . $baseid . '" received arguments but does not support them.',
                            DEBUG_DEVELOPER
                        );
                    }
                }
                return $recommendation;
            }
        }

        // Fallback to null if no recommendation with the given id exists.
        return null;
    }

    /**
     * Return supported status values with their labels and descriptions in order.
     *
     * Each entry is an array with keys 'label' and 'description'.
     *
     * @return array[] Array of status data keyed by status value.
     */
    public static function get_supported_statuses(): array {
        return [
            recommendation::WARNING => [
                'label' => get_string('recommendationstatus_warning', 'theme_boost_union'),
                'description' => get_string('recommendationstatus_warning_description', 'theme_boost_union'),
            ],
            recommendation::NOTICE => [
                'label' => get_string('recommendationstatus_notice', 'theme_boost_union'),
                'description' => get_string('recommendationstatus_notice_description', 'theme_boost_union'),
            ],
            recommendation::CHECK => [
                'label' => get_string('recommendationstatus_check', 'theme_boost_union'),
                'description' => get_string('recommendationstatus_check_description', 'theme_boost_union'),
            ],
            recommendation::OK => [
                'label' => get_string('recommendationstatus_ok', 'theme_boost_union'),
                'description' => get_string('recommendationstatus_ok_description', 'theme_boost_union'),
            ],
            recommendation::NA => [
                'label' => get_string('recommendationstatus_na', 'theme_boost_union'),
                'description' => get_string('recommendationstatus_na_description', 'theme_boost_union'),
            ],
            recommendation::MUTED => [
                'label' => get_string('recommendationstatus_muted', 'theme_boost_union'),
                'description' => get_string('recommendationstatus_muted_description', 'theme_boost_union'),
            ],
        ];
    }

    /**
     * Return supported category values and labels in order.
     *
     * @return string[] Array of category labels keyed by category value.
     */
    public static function get_supported_categories(): array {
        return [
            recommendation::CATEGORY_MOODLECORE => get_string('recommendationcategory_moodlecore', 'theme_boost_union'),
            recommendation::CATEGORY_BOOSTUNION => get_string('recommendationcategory_boostunion', 'theme_boost_union'),
            recommendation::CATEGORY_THIRDPARTY => get_string('recommendationcategory_thirdparty', 'theme_boost_union'),
            recommendation::CATEGORY_USABILITY => get_string('recommendationcategory_usability', 'theme_boost_union'),
            recommendation::CATEGORY_ACCESSIBILITY => get_string('recommendationcategory_accessibility', 'theme_boost_union'),
        ];
    }

    /**
     * Check if at least one recommendation needs attention.
     *
     * A recommendation needs attention if its effective status is neither OK, N/A nor MUTED.
     *
     * @return bool
     */
    public static function has_recommendations_needing_attention(): bool {
        // Iterate over all recommendations.
        foreach (self::get_recommendations() as $recommendation) {
            // Check if there is at least one recommendation needing attention.
            if (self::recommendation_needs_attention($recommendation)) {
                return true;
            }
        }

        // Fall back to false if no recommendation needs attention.
        return false;
    }

    /**
     * Render a notification for a single recommendation.
     *
     * The notification is rendered only if the recommendation exists and its effective status needs attention.
     *
     * @param string $recommendationid Recommendation id, optionally with slash-separated args.
     * @return string Rendered HTML or empty string.
     */
    public static function render_recommendation_notification(string $recommendationid): string {
        global $OUTPUT;

        // Get recommendation by id (args are parsed and passed inside get_recommendation_by_id).
        $recommendation = self::get_recommendation_by_id($recommendationid);

        // Stop if it does not exist.
        if ($recommendation === null) {
            return '';
        }

        // Only show notification for recommendations which need attention.
        if (!self::recommendation_needs_attention($recommendation)) {
            return '';
        }

        // Get the effective status of the recommendation to determine the badge class and for potential use in the template.
        $status = self::get_effective_status($recommendation);

        // Prepare recommendation data for shared actions renderer.
        $data = new \stdClass();
        $data->id = $recommendation->get_id();
        $data->title = $recommendation->get_title();
        $data->summary = $recommendation->get_summary();
        $data->description = $recommendation->get_description();
        $data->actionurl = $recommendation->get_action_url();
        $data->autofixable = $recommendation->supports_autofix();
        $data->statuslabel = self::get_status_label($recommendation);
        $data->statusbadgeclass = self::get_status_badge_class($status);
        $data->statusdescription = self::get_status_description($recommendation);
        $data->possiblesolution = self::get_possible_solution($recommendation);

        // Render notification body with mustache template.
        $content = $OUTPUT->render_from_template('theme_boost_union/recommendationsnotification', [
            'statuslabel' => format_string(self::get_status_label($recommendation)),
            'statusbadgeclass' => self::get_status_badge_class($status),
            'recommendationtitle' => format_string($recommendation->get_title()),
            'recommendationsummary' => format_string($recommendation->get_summary()),
            'actionshtml' => recommendations_overview::render_actions_for_recommendation_data($data, true),
        ]);

        // Render as info notification while preserving action link data-* attributes.
        // If we would use $OUTPUT->notification(), the action link data-* attributes would be stripped.
        return $OUTPUT->render_from_template('core/notification_info', [
            'message' => $content,
            'closebutton' => false,
        ]);
    }

    /**
     * Return the badge class for a recommendation status.
     *
     * @param string $status The recommendation status to get the badge class for.
     * @return string
     */
    public static function get_status_badge_class(string $status): string {
        switch ($status) {
            case recommendation::OK:
                return 'badge-success';

            case recommendation::NOTICE:
                return 'badge-info';

            case recommendation::CHECK:
                return 'text-bg-info';

            case recommendation::WARNING:
                return 'badge-warning';

            case recommendation::NA:
                return 'badge-secondary';

            case recommendation::MUTED:
                return 'badge-secondary';

            default:
                return 'badge-secondary';
        }
    }

    /**
     * Return recommendation status with muted state applied.
     *
     * @param recommendation $recommendation The recommendation to get the effective status for.
     * @return string
     */
    public static function get_effective_status(recommendation $recommendation): string {
        // If the recommendation is muted, return muted status regardless of the actual recommendation status.
        if (self::is_recommendation_muted($recommendation->get_id())) {
            return recommendation::MUTED;
        }

        // Otherwise, return the actual recommendation status.
        return $recommendation->get_status();
    }

    /**
     * Check if a recommendation should be shown in the recommendations list.
     *
     * A recommendation is hidden from the list when hide_if_ok() returns true and
     * its effective status is OK or N/A.
     *
     * @param recommendation $recommendation The recommendation to check.
     * @return bool
     */
    public static function recommendation_should_be_shown(recommendation $recommendation): bool {
        if (
            $recommendation->hide_if_ok() &&
                (self::get_effective_status($recommendation) === recommendation::OK ||
                 self::get_effective_status($recommendation) === recommendation::NA)
        ) {
            return false;
        }
        return true;
    }

    /**
     * Check if a recommendation needs attention.
     *
     * A recommendation needs attention if its effective status is neither OK, N/A nor MUTED.
     *
     * @param recommendation $recommendation The recommendation to check.
     * @return bool
     */
    public static function recommendation_needs_attention(recommendation $recommendation): bool {
        $status = self::get_effective_status($recommendation);
        return $status !== recommendation::OK &&
            $status !== recommendation::NA &&
            $status !== recommendation::MUTED;
    }

    /**
     * Return the validated status label for a recommendation.
     *
     * @param recommendation $recommendation The recommendation to get the status label for.
     * @return string
     */
    public static function get_status_label(recommendation $recommendation): string {
        // Get supported statuses and the recommendation status.
        $supported = self::get_supported_statuses();
        $result = self::get_effective_status($recommendation);

        // If the status is supported, return the corresponding label.
        if (array_key_exists($result, $supported)) {
            return $supported[$result]['label'];
        }

        // If status is not supported, log debug message.
        debugging(
            'Recommendation "' . $recommendation->get_id() .
                '" returned unsupported status "' . $result . '".',
            DEBUG_DEVELOPER
        );

        // Return fallback label for unsupported status.
        return $supported[recommendation::NA]['label'];
    }

    /**
     * Return the validated status description for a recommendation.
     *
     * @param recommendation $recommendation The recommendation to get the status description for.
     * @return string
     */
    public static function get_status_description(recommendation $recommendation): string {
        // Get supported statuses and the recommendation status.
        $supported = self::get_supported_statuses();
        $result = self::get_effective_status($recommendation);

        // If the status is supported, return the corresponding description.
        if (array_key_exists($result, $supported)) {
            return $supported[$result]['description'];
        }

        // If status is not supported, log debug message.
        debugging(
            'Recommendation "' . $recommendation->get_id() .
                '" returned unsupported status "' . $result . '".',
            DEBUG_DEVELOPER
        );

        // Return fallback description for unsupported status.
        return $supported[recommendation::NA]['description'];
    }

    /**
     * Return a "Possible solutions" hint text for a recommendation that needs attention.
     *
     * Returns an empty string when the recommendation does not need attention or when
     * neither an action URL nor autofix support is available.
     *
     * @param recommendation $recommendation The recommendation to get the solution text for.
     * @return string
     */
    public static function get_possible_solution(recommendation $recommendation): string {
        // Only show a solution hint when the recommendation needs attention.
        if (!self::recommendation_needs_attention($recommendation)) {
            return '';
        }

        // The CHECK status requires manual review regardless of available actions.
        if (self::get_effective_status($recommendation) === recommendation::CHECK) {
            return get_string('recommendationsolution_check', 'theme_boost_union');
        }

        // Determine which solution options are available for this recommendation.
        $autofixable = $recommendation->supports_autofix();
        $hasactionurl = $recommendation->get_action_url() !== null;

        // Return appropriate hint text based on available solution options.
        if ($autofixable && $hasactionurl) {
            return get_string('recommendationsolution_both', 'theme_boost_union');
        } else if ($autofixable) {
            return get_string('recommendationsolution_autofixonly', 'theme_boost_union');
        } else if ($hasactionurl) {
            return get_string('recommendationsolution_actionurlonly', 'theme_boost_union');
        }

        // No solution hint available.
        return '';
    }

    /**
     * Check if a recommendation is muted.
     *
     * @param string $recommendationid The id of the recommendation to check the muted state for.
     * @return bool
     */
    public static function is_recommendation_muted(string $recommendationid): bool {
        // Determine the config key to persist the muted state.
        $key = self::get_recommendation_muted_config_key($recommendationid);

        // Return the muted status.
        // This will automatically return false if the config is not set, which is the default state for all recommendations.
        return get_config('theme_boost_union', $key);
    }

    /**
     * Persist muted state for a recommendation.
     *
     * @param string $recommendationid The id of the recommendation to set the muted state for.
     * @param bool $muted Whether the recommendation should be muted or unmuted.
     * @return bool
     */
    public static function set_recommendation_muted(string $recommendationid, bool $muted): bool {
        // Determine the config key to persist the muted state.
        $key = self::get_recommendation_muted_config_key($recommendationid);

        // If muted is true, set the config to 'true'.
        if ($muted == true) {
            return set_config($key, true, 'theme_boost_union');

            // If muted is false, remove the config.
        } else {
            return unset_config($key, 'theme_boost_union');
        }
    }

    /**
     * Return the config key used to persist muted recommendations.
     *
     * @param string $recommendationid The id of the recommendation to get the config key for.
     * @return string
     */
    protected static function get_recommendation_muted_config_key(string $recommendationid): string {
        return 'recommendation-muted-' . $recommendationid;
    }

    /**
     * Parse a (possibly parameterised) recommendation id into its base id and optional args.
     *
     * Examples:
     *   "myrecommendation"         → ['id' => 'myrecommendation', 'args' => []]
     *   "myrecommendation/3"       → ['id' => 'myrecommendation', 'args' => ['3']]
     *   "myrecommendation/foo/bar" → ['id' => 'myrecommendation', 'args' => ['foo', 'bar']]
     *
     * @param string $recommendationid Full recommendation id, optionally with slash-separated args.
     * @return array{id: string, args: string[]}
     */
    protected static function parse_recommendation_id(string $recommendationid): array {
        // Split the full id by slashes. The first part is the base id, the rest are args.
        $parts = explode('/', $recommendationid);

        // Return the base id and args.
        return [
            'id'   => array_shift($parts),
            'args' => $parts,
        ];
    }

    /**
     * Return all recommendation class names.
     *
     * @return string[]
     */
    protected static function get_recommendation_classes(): array {
        // Initialize empty result array.
        $classes = [];

        // Discover built-in recommendation classes from the recommendation\check namespace.
        $builtinclasses = \core_component::get_component_classes_in_namespace('theme_boost_union', 'recommendation\\check');
        $classes = array_keys($builtinclasses);

        // Discover additional recommendation classes from plugins.
        $plugincallbacks = get_plugins_with_function('extend_burecommendations', 'lib.php');
        foreach ($plugincallbacks as $plugintype => $plugins) {
            foreach ($plugins as $plugin => $callback) {
                try {
                    $plugclasses = $callback();
                } catch (\Throwable $e) {
                    debugging("Exception calling '$callback'", DEBUG_DEVELOPER, $e->getTrace());
                    continue;
                }

                if (!is_array($plugclasses)) {
                    debugging('Callback "' . $callback . '" did not return an array of class names.', DEBUG_DEVELOPER);
                    continue;
                }

                foreach ($plugclasses as $plugclass) {
                    if (!is_string($plugclass) || $plugclass === '') {
                        debugging('Invalid recommendation class from callback "' . $callback . '".', DEBUG_DEVELOPER);
                        continue;
                    }
                    $classes[] = $plugclass;
                }
            }
        }

        // Return all discovered classes.
        return $classes;
    }

    /**
     * Instantiate a recommendation by class name.
     *
     * @param string $classname
     * @return recommendation|null
     */
    protected static function instantiate_recommendation(string $classname): ?recommendation {
        // Check if the class exists.
        if (!class_exists($classname)) {
            debugging('Recommendation class "' . $classname . '" could not be loaded.', DEBUG_DEVELOPER);
            return null;
        }

        // Instantiate the class and check if it implements the recommendation interface.
        $instance = new $classname();
        if (!($instance instanceof recommendation)) {
            debugging('Recommendation class "' . $classname . '" must implement recommendation.', DEBUG_DEVELOPER);
            return null;
        }

        // Return the instance.
        return $instance;
    }
}
