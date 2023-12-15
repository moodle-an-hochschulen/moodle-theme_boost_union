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
 * Theme Boost Union - Behat rules which have been backported from future Moodle versions.
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../../../lib/behat/behat_base.php');

/**
 * Class behat_theme_boost_union_base_backports
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union_base_backports extends behat_base {
    /**
     * Generic mouse over action. Mouse over a element of the specified type.
     *
     * This step is basically existent in Moodle core, but is not usable due to a bug as
     * https://github.com/moodle/moodle/commit/905ccf34fc672168c06192ba801386e6e88cc83b / MDL-78199 was only
     * integrated to Moodle 4.3.
     *
     * @When I hover over the :element :selectortype in the :containerelement :containerselectortype
     * @param string $element Element we look for
     * @param string $selectortype The type of what we look for
     * @param string $containerelement Element we look for
     * @param string $containerselectortype The type of what we look for
     */
    public function i_hover_in_the(string $element, $selectortype, string $containerelement, $containerselectortype): void {
        // Gets the node based on the requested selector type and locator.
        $node = $this->get_node_in_container($selectortype, $element, $containerselectortype, $containerelement);
        $this->execute_js_on_node($node, '{{ELEMENT}}.scrollIntoView();');
        $node->mouseOver();
    }
}
