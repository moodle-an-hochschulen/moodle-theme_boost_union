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
 * Behat blocks-related step definition overrides for the Boost Union theme.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.
// For that reason, we can't even rely on $CFG->admin being available here.

require_once(__DIR__ . '/../../../../blocks/tests/behat/behat_blocks.php');

use Behat\Mink\Exception\ElementNotFoundException;

/**
 * Blocks-related step definition overrides for the Boost Union theme.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union_behat_blocks extends behat_blocks {

    /**
     * Checks the order of elements before or after.
     *
     * @Then :arg1 :arg2 should display :arg3 :arg4 :arg5
     *
     * @param string $selector1 The first element selector.
     * @param string $type1 The type of the first element selector.
     * @param string $order The expected order (before or after).
     * @param string $selector2 The second element selector.
     * @param string $type2 The type of the second element selector.
     */
    public function assert_element_order($selector1, $type1, $order, $selector2, $type2) {
        $element1 = $this->find($type1, $selector1);
        $element2 = $this->find($type2, $selector2);

        if (!$element1) {
            throw new ElementNotFoundException($this->getSession(), 'element', $type1, $selector1);
        }

        if (!$element2) {
            throw new ElementNotFoundException($this->getSession(), 'element', $type2, $selector2);
        }

        $script = "
            return (function() {
                const el1 = document.querySelector('$selector1');
                const el2 = document.querySelector('$selector2');
                const order1 = parseInt(window.getComputedStyle(el1).order);
                const order2 = parseInt(window.getComputedStyle(el2).order);
                return order1 < order2 ? true : false;
            })();";

        $result = $this->evaluate_script($script);

        if (($order === 'before' && $result == false) || ($order === 'after' && $result == true)) {
            throw new Exception(sprintf(
                'The element "%s" (%s) is not displayed %s the element "%s" (%s).',
                $selector1, $type1, $order, $selector2, $type2
            ));
        }
    }
}
