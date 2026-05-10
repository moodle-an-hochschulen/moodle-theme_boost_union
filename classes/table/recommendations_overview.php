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
 * Theme Boost Union - Recommendations overview table.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\table;

use core\output\html_writer;
use theme_boost_union\recommendation\manager;

defined('MOODLE_INTERNAL') || die();

// Require table library.
require_once($CFG->libdir . '/tablelib.php');

/**
 * List of recommendations.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class recommendations_overview extends \core_table\sql_table {
    /**
     * Category to filter recommendations by.
     *
     * @var string
     */
    protected $category;

    /**
     * Setup table.
     *
     * @param string $category Category constant to filter recommendations by.
     * @throws \coding_exception
     */
    public function __construct(string $category) {

        // Store optional category filter.
        $this->category = $category;

        // Call parent constructor.
        parent::__construct('recommendations');

        // Define the headers and columns.
        $headers[] = get_string('recommendationstatusheader', 'theme_boost_union');
        $headers[] = get_string('recommendationrecommendationheader', 'theme_boost_union');
        $headers[] = get_string('recommendationsummaryheader', 'theme_boost_union');
        $headers[] = get_string('recommendationactionsheader', 'theme_boost_union');
        $columns[] = 'status';
        $columns[] = 'title';
        $columns[] = 'summary';
        $columns[] = 'actions';
        $this->sortable(false); // Having a sortable table here does not make much sense.
        $this->collapsible(false);
        $this->pageable(false); // Having a pageable table here does not make much sense.
        $this->define_columns($columns);
        $this->define_headers($headers);
        $this->define_header_column('title');
        $this->column_class('actions', 'text-nowrap');
        $this->set_attribute('id', 'recommendations-' . $category);

        // Render category title as table caption.
        // We fetch the string from the supported categories to ensure that only valid categories are used
        // and to avoid that we have to compose the string key here.
        $supportedcategories = manager::get_supported_categories();
        if (isset($supportedcategories[$this->category])) {
            $this->set_caption($supportedcategories[$this->category], ['class' => 'h4']);
        }
    }

    /**
     * Check if the table has recommendations to display.
     *
     * @return bool
     */
    public function is_empty(): bool {
        return empty($this->rawdata);
    }

    /**
     * Status column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_status($data) {
        // The status label is already prepared in query_db(),
        // we just need to wrap it in a badge with the appropriate class.
        return html_writer::span(format_string($data->statuslabel), 'badge ' . $data->statusbadgeclass);
    }

    /**
     * Actions column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_actions($data) {
        // Render the action icons for this recommendation.
        return self::render_actions_for_recommendation_data($data);
    }

    /**
     * Render actions for recommendation-like data.
     *
     * Required properties on $data: id, title, summary, description, actionurl.
     *
     * This function has been made static to be reusable from the settings page notification as well.
     *
     * @param \stdClass $data
     * @param bool $includeviewall If true, include an additional action linking to the recommendations overview page.
     * @return string
     */
    public static function render_actions_for_recommendation_data(\stdClass $data, bool $includeviewall = false): string {
        global $OUTPUT;

        // Initialize actions.
        $actions = [];

        // Info.
        $actions[] = [
            'url' => '#',
            'icon' => new \core\output\pix_icon(
                'info',
                get_string('recommendationmoreinfo', 'theme_boost_union'),
                'theme_boost_union'
            ),
            'attributes' => [
                'class' => 'action-details py-0 ps-0 ms-0 me-0',
                'data-action' => 'recommendation-details',
                'data-title' => $data->title,
                'data-summary' => $data->summary,
                'data-description' => $data->description,
                'data-id' => $data->id,
            ],
        ];

        // Auto-fix (if the recommendation supports it).
        if (!empty($data->autofixable)) {
            $actions[] = [
                'url' => new \core\url('/theme/boost_union/recommendations/overview.php', [
                    'action' => 'autofix',
                    'id' => $data->id,
                    'sesskey' => sesskey(),
                ]),
                'icon' => new \core\output\pix_icon(
                    'autofix',
                    get_string('recommendationautofix', 'theme_boost_union'),
                    'theme_boost_union'
                ),
                'attributes' => [
                    'class' => 'action-autofix py-0 ms-0 me-0',
                    'title' => get_string('recommendationautofix', 'theme_boost_union'),
                    'aria-label' => get_string('recommendationautofix', 'theme_boost_union'),
                ],
            ];
        }

        // Edit (if an action URL is set).
        if ($data->actionurl !== null) {
            $actions[] = [
                'url' => $data->actionurl,
                'icon' => new \core\output\pix_icon('i/settings', get_string('recommendationopensetting', 'theme_boost_union')),
                'attributes' => ['class' => 'action-edit py-0 ms-0 me-0'],
            ];
        }

        // Mute / Unmute.
        $muted = manager::is_recommendation_muted($data->id);
        $actions[] = [
            'url' => new \core\url('/theme/boost_union/recommendations/overview.php', [
                'action' => $muted ? 'unmute' : 'mute',
                'id' => $data->id,
                'sesskey' => sesskey(),
            ]),
            'icon' => new \core\output\pix_icon(
                $muted ? 'muted' : 'unmuted',
                $muted ? get_string('recommendationunmute', 'theme_boost_union') :
                    get_string('recommendationmute', 'theme_boost_union'),
                'theme_boost_union'
            ),
            'attributes' => [
                'class' => $muted ? 'action-unmute py-0 pe-0 ms-0 me-0' : 'action-mute py-0 pe-0 ms-0 me-0',
                'title' => $muted ? get_string('recommendationunmute', 'theme_boost_union') :
                    get_string('recommendationmute', 'theme_boost_union'),
                'aria-label' => $muted ? get_string('recommendationunmute', 'theme_boost_union') :
                    get_string('recommendationmute', 'theme_boost_union'),
            ],
        ];

        // View all recommendations.
        if ($includeviewall) {
            $actions[] = [
                'url' => new \core\url('/theme/boost_union/recommendations/overview.php'),
                'icon' => new \core\output\pix_icon(
                    'viewall',
                    get_string('recommendationviewall', 'theme_boost_union'),
                    'theme_boost_union'
                ),
                'attributes' => [
                    'class' => 'action-viewall py-0 pe-0 ps-2 ms-0 me-0',
                    'title' => get_string('recommendationviewall', 'theme_boost_union'),
                    'aria-label' => get_string('recommendationviewall', 'theme_boost_union'),
                ],
            ];
        }

        // Compose action icons for all actions.
        $actionshtml = [];
        foreach ($actions as $action) {
            $action['attributes']['role'] = 'button';
            $actionshtml[] = $OUTPUT->action_icon(
                $action['url'],
                $action['icon'],
                ($action['confirm'] ?? null),
                $action['attributes']
            );
        }

        // Ensure the recommendation details modal JS is included.
        self::ensure_details_modal_js();

        // Return all actions.
        return html_writer::span(join('', $actionshtml), 'recommendations-actions');
    }

    /**
     * Ensure the recommendation details modal JS is added to the page, but only once per request.
     */
    public static function ensure_details_modal_js(): void {
        global $PAGE;

        // Initialize static variable to track if the JS has already been included.
        static $initialized = false;

        // If the JS is already included or if $PAGE is not available, do nothing.
        if ($initialized || empty($PAGE)) {
            return;
        }

        // Include the JS module for the recommendation details modal.
        $PAGE->requires->js_call_amd('theme_boost_union/recommendationdetailsmodal', 'init');

        // And remember that fact.
        $initialized = true;
    }

    /**
     * Get the recommendations for the table.
     *
     * @param int $pagesize
     * @param bool $useinitialsbar
     * @throws \dml_exception
     */
    public function query_db($pagesize, $useinitialsbar = true) {

        // Initialize raw data array.
        $this->rawdata = [];

        // Get recommendations for the configured category.
        $recommendations = manager::get_recommendations($this->category);

        // Iterate over recommendations and prepare data for the table.
        foreach ($recommendations as $recommendation) {
            // Initialize a row of data for the table.
            $row = new \stdClass();

            // Get the row data.
            $row->id = $recommendation->get_id();
            $row->statuslabel = manager::get_status_label($recommendation);
            $row->statusbadgeclass = manager::get_status_badge_class(manager::get_effective_status($recommendation));
            $row->title = $recommendation->get_title();
            $row->summary = $recommendation->get_summary();
            $row->description = $recommendation->get_description();
            $row->actionurl = $recommendation->get_action_url();
            $row->autofixable = $recommendation->is_autofixable() && manager::recommendation_needs_attention($recommendation);

            // Add the row to the table data.
            $this->rawdata[] = $row;
        }
    }

    /**
     * Override the message if the table contains no entries.
     */
    public function print_nothing_to_display() {
        // Do not render a default empty-table message for categories without recommendations.
    }
}
