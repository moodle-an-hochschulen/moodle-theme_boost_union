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
 * Theme Boost Union - Custom Behat rules for MWP
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

/**
 * Class behat_theme_boost_union_base_mwp
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union_base_mwp extends behat_base {
    /**
     * Simulates MWP core being present by setting a Behat simulation config flag.
     *
     * This step sets a config flag that is picked up by \theme_boost_union\local\mwp::core_present()
     * when BEHAT_SITE_RUNNING is defined, causing it to return true as if tool_tenant were installed.
     *
     * @Given MWP core is simulated to be present
     */
    public function mwp_core_is_simulated_to_be_present() {
        set_config('behat_mwp_core_present', 1, 'theme_boost_union');
    }

    /**
     * Simulates MWP core not being present by clearing the Behat simulation config flag.
     *
     * This step unsets the config flag so that \theme_boost_union\local\mwp::core_present()
     * falls through to the normal file-based check (which will return false as tool_tenant is not installed).
     *
     * @Given MWP core is simulated to be not present
     */
    public function mwp_core_is_simulated_to_be_not_present() {
        unset_config('behat_mwp_core_present', 'theme_boost_union');
    }
}
