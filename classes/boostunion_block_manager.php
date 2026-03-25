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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Theme Boost Union - Custom block manager.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union;

/**
 * Custom block manager for Boost Union theme.
 *
 * This class extends the core block_manager to add region-specific permission checks.
 * It ensures that users cannot add or move blocks to regions they don't have permission to edit.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class boostunion_block_manager extends \block_manager {
    /**
     * Check if a user has capability to edit blocks in a specific region.
     *
     * This function checks if a region is an additional region provided by Boost Union
     * and whether the user has the required capability to edit blocks in that region.
     *
     * For non-Boost Union regions, it returns true.
     *
     * @param string $region The region name to check.
     * @param \context $context The context to check capabilities in.
     * @return bool
     */
    public static function can_user_edit_region($region, $context) {
        global $CFG;

        // Require own locallib.php.
        require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

        // Get additional regions available in Boost Union.
        $additionalregions = theme_boost_union_get_additional_regions();

        // Check if region is an additional region.
        $regioncapname = array_search($region, $additionalregions);
        if (empty($regioncapname)) {
            return true;
        }

        // Build the capability name for this region.
        $capability = 'theme/boost_union:editregion' . $regioncapname;

        return has_capability($capability, $context);
    }

    /**
     * Harden block regions in block edit context by filtering non-editable regions.
     *
     * @param array $regions The full region list.
     * @param array $settings Regions configured for the current layout (without side-pre).
     * @return array
     */
    public static function harden_block_regions(array $regions, array $settings): array {
        global $CFG, $DB, $PAGE;

        // Check if we're in a block editing context.
        // This can happen in two ways:
        // 1. Direct URL access:
        // -> Moodle standard for block edit via /course/view.php?id=<courseid>&bui_editid=<blockinstanceid> URL.
        // -> bui_editid Parameter can be found in URL.
        // 2. AJAX/Modal access:
        // -> Webservice core_form_dynamic_form is called.
        // -> block_html_edit_form form is rendered.
        // -> blockid is in the form data.
        // This check only needs to be done once per request as the editing context doesn't change,
        // thus we use static variables to store the results for subsequent calls of this function within the same request.
        static $iseditingblock = null;
        static $isajax = false;
        static $pagecontext = null;

        // Only perform the edit context detection once.
        if ($iseditingblock === null) {
            $iseditingblock = false;

            // Case 1: Direct URL access.
            $buieditid = optional_param('bui_editid', null, PARAM_INT);
            if ($buieditid !== null && isset($PAGE) && $PAGE->url->compare(new \core\url('/course/view.php'), URL_MATCH_BASE)) {
                $iseditingblock = true;
            }

            // Case 2: AJAX/Modal access.
            // We need to check this only if we haven't already identified that we're in a block editing context via case 1.
            if (!$iseditingblock) {
                // Get the backtrace to check if we're being called from block editing context.
                $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

                // Look for block_edit_form class in the call stack.
                foreach ($backtrace as $trace) {
                    $class = isset($trace['class']) ? $trace['class'] : '';
                    if ($class === 'block_edit_form') {
                        $iseditingblock = true;
                        $isajax = true;
                        break;
                    }
                }
            }

            // If this is a block editing call, determine the page context once.
            if ($iseditingblock) {
                // If this is not an AJAX call.
                if ($isajax == false) {
                    // For non-AJAX calls, we can get the context from the PAGE global.
                    $pagecontext = $PAGE->context;

                    // Otherwise, if this is an AJAX call.
                } else {
                    // Try to get the PAGE context from the global, too.
                    try {
                        $pagecontext = $PAGE->context;

                        // But most probably, this will fail.
                    } catch (\Exception $e) {
                        // If the page context is still null (i.e., not retrieved in a previous call of this function).
                        if ($pagecontext === null) {
                            // Try to get the context from the block being edited via blockid in the JSON POST body.
                            $blockid = null;

                            // Get the raw POST body.
                            // This approach is the best we can do as Moodle does not store the POST payload in a global variable.
                            // But it's the same as it is done in lib/ajax/service.php when handling AJAX requests,
                            // so we can be sure that it works as expected.
                            $input = file_get_contents('php://input');
                            if (!empty($input)) {
                                // Decode the JSON input to extract the formdata string, which contains the blockid.
                                $jsondata = json_decode($input, true);

                                // Check if the JSON data contains the formdata.
                                if (is_array($jsondata) && isset($jsondata[0]['args']['formdata'])) {
                                    // Parse the formdata URL-encoded string to extract blockid.
                                    parse_str($jsondata[0]['args']['formdata'], $parsed);

                                    // If we have a blockid in the parsed formdata, clean it and store it in $blockid.
                                    if (isset($parsed['blockid'])) {
                                        $blockid = clean_param($parsed['blockid'], PARAM_INT);
                                    }
                                }
                            }

                            // If we have a blockid now.
                            if ($blockid !== null) {
                                // Load the block instance from the database.
                                $blockinstance = $DB->get_record(
                                    'block_instances',
                                    ['id' => $blockid],
                                    'parentcontextid',
                                    IGNORE_MISSING
                                );

                                // If we have found an instance.
                                if ($blockinstance && $blockinstance->parentcontextid) {
                                    try {
                                        // Reconstruct the context from the block's parent context ID.
                                        $pagecontext = \context::instance_by_id($blockinstance->parentcontextid);

                                        // If the context ID is invalid.
                                    } catch (\dml_exception $e) {
                                        // Mark context as failed resolution.
                                        $pagecontext = false;
                                        // Simply return all regions now.
                                        return $regions;
                                    }

                                    // Otherwise, if the block instance could not be found or has no context.
                                } else {
                                    // Mark context as failed resolution.
                                    $pagecontext = false;
                                    // Simply return all regions now.
                                    return $regions;
                                }

                                // Otherwise, if no block ID was found in the payload.
                            } else {
                                // Mark context as failed resolution.
                                $pagecontext = false;
                                // Simply return all regions now.
                                return $regions;
                            }
                        }
                    }
                }
            }
        }

        // If this is a block editing call, filter regions based on capabilities.
        if ($iseditingblock) {
            // If context is null or false (failed to retrieve previously), we cannot check any capabilities.
            if ($pagecontext === null || $pagecontext === false) {
                // Simply return all regions now.
                return $regions;
            }

            // Remove regions the user has no permission to edit from the regions array.
            // Require the block manager class for access to the helper.
            require_once($CFG->dirroot . '/theme/boost_union/classes/boostunion_block_manager.php');

            // Iterate over the regions defined in the admin setting for this layout.
            foreach ($settings as $region) {
                // Use the helper method to check if user can edit this region.
                if (!self::can_user_edit_region($region, $pagecontext)) {
                    // Remove the region from the regions array.
                    $regions = array_diff($regions, [$region]);
                }
            }

            // Now return the filtered regions.
            return $regions;
        }

        // Fallback: If we're not in a block editing context, return all regions unfiltered.
        return $regions;
    }

    /**
     * Get all block regions.
     *
     * Normally this returns all known regions unchanged.
     * For the drag-and-drop JS initialisation call only, we filter out regions
     * the user is not allowed to edit so they are not registered as drop targets.
     *
     * @return array
     */
    public function get_regions() {
        // Get all regions from parent method.
        $regions = parent::get_regions();

        // If the user cannot edit blocks or is not in editing mode, return all regions unfiltered.
        if (!$this->page->user_is_editing() || !$this->page->user_can_edit_blocks()) {
            return $regions;
        }

        // Filter only for the drag/drop initialisation call, not globally.
        $isdragdropinit = false;

        // Get the backtrace to check if we're being called from the DnD context.
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        // Look for init_requirements_data function in the call stack.
        foreach ($backtrace as $trace) {
            $class = $trace['class'] ?? '';
            $function = $trace['function'] ?? '';
            if (
                $class === 'core\\output\\requirements\\page_requirements_manager' &&
                    $function === 'init_requirements_data'
            ) {
                $isdragdropinit = true;
                break;
            }
        }

        // If this is not the drag-and-drop initialisation call, return all regions unfiltered.
        if (!$isdragdropinit) {
            return $regions;
        }

        // Keep only regions the user may edit. Non-editable regions stay rendered,
        // but are no longer registered by JS as drag/drop destinations.
        $regions = array_values(array_filter($regions, function ($region) {
            return self::can_user_edit_region($region, $this->page->context);
        }));

        // Return the filtered regions for the DnD initialisation call.
        return $regions;
    }

    /**
     * Override add_block to check region-specific edit capabilities.
     *
     * This method adds a permission check before allowing a block to be added to a region.
     * If the user doesn't have the capability to edit the target region, an exception is thrown.
     *
     * @param string $blockname The name of the block to add
     * @param string $region The region to add the block to
     * @param int $weight The weight/position of the block
     * @param bool $showinsubcontexts Whether to show in subcontexts
     * @param string|null $pagetypepattern The page type pattern
     * @param string|null $subpagepattern The subpage pattern
     * @return \block_base|null The created block instance or null
     * @throws \moodle_exception If user doesn't have the permission to edit the target region
     */
    public function add_block($blockname, $region, $weight, $showinsubcontexts, $pagetypepattern = null, $subpagepattern = null) {

        // Check region-specific capability before allowing block addition.
        if (!self::can_user_edit_region($region, $this->page->context)) {
            throw new \moodle_exception(
                'nopermissions',
                '',
                $this->page->url->out(),
                get_string('blockactionnotallowed_add', 'theme_boost_union', $region)
            );
        }

        // Call parent method.
        return parent::add_block($blockname, $region, $weight, $showinsubcontexts, $pagetypepattern, $subpagepattern);
    }

    /**
     * Override reposition_block to check region-specific edit capabilities.
     *
     * This method adds a permission check before allowing a block to be moved to a new region.
     * This is called when blocks are drag-and-dropped or their region is changed via the UI.
     *
     * @param int $blockinstanceid The ID of the block instance to reposition
     * @param string $newregion The region to move the block to
     * @param int $newweight The new weight/position
     * @throws \moodle_exception If user doesn't have the permission to edit the target region
     */
    public function reposition_block($blockinstanceid, $newregion, $newweight) {
        // Check if user can edit the target region.
        if (!self::can_user_edit_region($newregion, $this->page->context)) {
            throw new \moodle_exception(
                'nopermissions',
                '',
                $this->page->url->out(),
                get_string('blockactionnotallowed_move', 'theme_boost_union', $newregion)
            );
        }

        // Call parent method.
        return parent::reposition_block($blockinstanceid, $newregion, $newweight);
    }

    /**
     * Override process_url_add to add region permission check.
     *
     * This method is called when a block is added via URL parameters.
     * It adds a check to ensure users can only add blocks to regions they have permission to edit.
     *
     * @throws \moodle_exception If user doesn't have the permission to edit the target region
     */
    public function process_url_add() {
        // Get the block region from URL parameters.
        $blockregion = optional_param('bui_blockregion', null, PARAM_TEXT);

        // If a specific region is being targeted, check permissions.
        if (
            $blockregion !== null && $blockregion !== '' &&
            !self::can_user_edit_region($blockregion, $this->page->context)
        ) {
                throw new \moodle_exception(
                    'nopermissions',
                    '',
                    $this->page->url->out(),
                    get_string('blockactionnotallowed_add', 'theme_boost_union', $blockregion)
                );
        }

        // Call parent method.
        return parent::process_url_add();
    }

    /**
     * Override save_block_data to validate region permissions when block settings are saved.
     *
     * This ensures that when a user saves block configuration (including changing the region
     * in the settings), they have permission to edit the target region.
     *
     * @param \block_base $block The block being configured
     * @param \stdClass $data The data from the block config form
     * @throws \moodle_exception If user doesn't have the permission to edit the target region
     */
    public function save_block_data(\block_base $block, \stdClass $data): void {
        // Check if the region is being changed via the config form.
        // If Moodle has accepted a submitted region value, ensure the user may edit it.
        if (
            isset($data->bui_region) &&
                !self::can_user_edit_region($data->bui_region, $this->page->context)
        ) {
            throw new \moodle_exception(
                'nopermissions',
                '',
                $this->page->url->out(),
                get_string('blockactionnotallowed_configure', 'theme_boost_union', $data->bui_region)
            );
        }

        // Check if the default region is being changed.
        // If Moodle has accepted a submitted region value, ensure the user may edit it.
        if (
            isset($data->bui_defaultregion) &&
                !self::can_user_edit_region($data->bui_defaultregion, $this->page->context)
        ) {
            throw new \moodle_exception(
                'nopermissions',
                '',
                $this->page->url->out(),
                get_string('blockactionnotallowed_configure', 'theme_boost_union', $data->bui_defaultregion)
            );
        }

        // Call parent method.
        // If Moodle normalised an invalid submitted region to null and the save then fails at DB level,
        // replace the raw DB exception with a cleaner one.
        try {
            parent::save_block_data($block, $data);
        } catch (\dml_write_exception $e) {
            // Get the submitted region values from the request to provide a more specific error message.
            $submittedregion = '';
            $submitteddefaultregion = '';
            $input = file_get_contents('php://input');
            if (!empty($input)) {
                $jsondata = json_decode($input, true);
                if (is_array($jsondata) && isset($jsondata[0]['args']['formdata'])) {
                    parse_str($jsondata[0]['args']['formdata'], $parsed);
                    if (isset($parsed['bui_region'])) {
                        $submittedregion = (string) $parsed['bui_region'];
                    }
                    if (isset($parsed['bui_defaultregion'])) {
                        $submitteddefaultregion = (string) $parsed['bui_defaultregion'];
                    }
                }
            }

            // Determine the region label for the error message.
            $regionlabel = trim($submittedregion !== '' ? $submittedregion : $submitteddefaultregion);
            if ($regionlabel === '') {
                $regionlabel = get_string('none');
            }

            // Throw a permission exception with a specific message about the region.
            throw new \moodle_exception(
                'nopermissions',
                '',
                $this->page->url->out(),
                get_string('blockactionnotallowed_configure', 'theme_boost_union', $regionlabel)
            );
        }
    }

    /**
     * Override process_url_edit to add region permission check.
     *
     * This method handles block editing and ensures users cannot move blocks
     * to regions they don't have permission to edit.
     *
     * @return bool True if a block edit was processed
     * @throws \moodle_exception If user doesn't have the permission to edit the target region
     */
    public function process_url_edit() {
        global $CFG;

        // Get the block instance ID from URL parameters.
        $blockid = optional_param('bui_editid', null, PARAM_INT);

        // If no specific block instance is being edited, call parent method (which might handle other edit actions).
        if (!$blockid) {
            return parent::process_url_edit();
        }

        // Get the block instance.
        $block = $this->find_instance($blockid);

        // Check current region permissions.
        if ($block && !self::can_user_edit_region($block->instance->region, $this->page->context)) {
            throw new \moodle_exception(
                'nopermissions',
                '',
                $this->page->url->out(),
                get_string('blockactionnotallowed_configure', 'theme_boost_union', $block->instance->region)
            );
        }

        // Call parent method.
        return parent::process_url_edit();
    }
}
