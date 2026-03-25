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
 * Theme Boost Union - Hook: Manipulate things directly after config.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\local\hook;

/**
 * Hook listener to manipulate things as early as possible.
 *
 * This hook fires right after setup.php is loaded, i.e. before ANY block manager
 * is instantiated. This ensures our custom block manager is used in ALL contexts,
 * including AJAX requests.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class after_config {
    /**
     * Register the custom block manager class if the harden block regions feature is enabled.
     *
     * @param \core\hook\after_config $hook
     */
    public static function callback(\core\hook\after_config $hook): void {
        global $CFG;

        // Do not run during initial installation.
        if (during_initial_install()) {
            return;
        }

        // Check if the harden block regions feature is enabled.
        // We intentionally use the literal 'yes' value here instead of the
        // THEME_BOOST_UNION_SETTING_SELECT_YES constant, as the theme's lib.php
        // is not loaded at this early stage of the bootstrap.
        $hardenblockregions = get_config('theme_boost_union', 'hardenblockregions');
        if (!empty($hardenblockregions) && $hardenblockregions === 'yes') {
            $CFG->blockmanagerclass = 'theme_boost_union\boostunion_block_manager';
            $CFG->blockmanagerclassfile = $CFG->dirroot . '/theme/boost_union/classes/boostunion_block_manager.php';
        }
    }
}
