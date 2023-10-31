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
 * Theme Boost Union - General custom Behat rules
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../../../lib/behat/behat_base.php');

use Behat\Mink\Exception\ExpectationException;

/**
 * Class behat_theme_boost_union_base_general
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union_base_general extends behat_base {
    /**
     * Checks if the given DOM element has the given computed style.
     *
     * @copyright 2023 Alexander Bias <bias@alexanderbias.de>
     * @Then DOM element :arg1 should have computed style :arg2 :arg3
     * @param string $selector
     * @param string $style
     * @param string $value
     * @throws ExpectationException
     */
    public function dom_element_should_have_computed_style($selector, $style, $value) {
        $stylejs = "
            return (
                $('$selector').css('$style')
            )
        ";
        $computedstyle = $this->evaluate_script($stylejs);
        if ($computedstyle != $value) {
            throw new ExpectationException('The \''.$selector.'\' DOM element does not have the computed style \''.
                    $style.'\'=\''.$value.'\', it has the computed style \''.$computedstyle.'\' instead.', $this->getSession());
        }
    }

    /**
     * Scroll the page to a given coordinate.
     *
     * @copyright 2016 Shweta Sharma on https://stackoverflow.com/a/39613869.
     * @Then /^I scroll page to x "(?P<posx_number>\d+)" y "(?P<posy_number>\d+)"$/
     * @param string $posx The x coordinate to scroll to.
     * @param string $posy The y coordinate to scroll to.
     * @return void
     * @throws Exception
     */
    public function i_scroll_page_to_x_y_coordinates_of_page($posx, $posy) {
        try {
            $this->getSession()->executeScript("(function(){window.scrollTo($posx, $posy);})();");
        } catch (Exception $e) {
            throw new \Exception("Scrolling the page to given coordinates failed");
        }
    }

    /**
     * Scroll the page to the DOM element with the given ID.
     *
     * @copyright 2023 Alexander Bias <bias@alexanderbias.de>
     * @Then I scroll page to DOM element with ID :arg1
     * @param string $selector
     * @return void
     * @throws Exception
     */
    public function i_scroll_page_to_dom_element_with_id($selector) {
        $scrolljs = "(function(){
                let element = document.getElementById('$selector');
                let y = element.offsetTop;
                window.scrollTo(0, y);
        })();";
        try {
            $this->getSession()->executeScript($scrolljs);
        } catch (Exception $e) {
            throw new \Exception('Scrolling the page to the \''.$selector.'\' DOM element failed');
        }
    }

    /**
     * Make the navbar fixed.
     *
     * This is not really a real Behat step, but it is necessary for the test of the scrollspy feature in the
     * 'Setting: Scrollspy - Enable "Scrollspy"' scenario.
     *
     * The problem is there:
     * 1. The 'I scroll page to DOM element with ID' step scrolls the page down as it is needed for the scenario.
     * 2. The 'I turn editing mode on' scrolls the page up again to click the edit button as the navbar is not fixed
     *    in Behat runs.
     *
     * Because of this, the scrollspy would save the wrong viewport-y value and the scenario would fail.
     * Against this background, we have to make the navbar fixed just before turning editing on.
     * And that's what this step is here for.
     *
     * @copyright 2023 Alexander Bias <bias@alexanderbias.de>
     * @Then I make the navbar fixed
     * @return void
     * @throws Exception
     */
    public function i_make_the_navbar_fixed() {
        $fixedjs = "(function(){
                document.querySelector('nav.navbar').style.position = 'fixed';
        })();";
        try {
            $this->getSession()->executeScript($fixedjs);
        } catch (Exception $e) {
            throw new \Exception('Making the navbar fixed failed');
        }
    }

    /**
     * Checks if the top of the page is at the top of the viewport.
     *
     * @copyright 2023 Alexander Bias <bias@alexanderbias.de>
     * @Then page top is at the top of the viewport
     * @throws ExpectationException
     */
    public function page_top_is_at_top_of_viewport() {
        $posviewportjs = "
            return (
                window.scrollY
            )
        ";
        $positionviewport = $this->evaluate_script($posviewportjs);
        if ($positionviewport != 0) {
            throw new ExpectationException('The page top is not at the top of the viewport', $this->getSession());
        }
    }

    /**
     * Checks if the top of the page is not at the top of the viewport.
     *
     * @copyright 2023 Alexander Bias <bias@alexanderbias.de>
     * @Then page top is not at the top of the viewport
     * @throws ExpectationException
     */
    public function page_top_is_not_at_top_of_viewport() {
        $posviewportjs = "
            return (
                window.scrollY
            )
        ";
        $positionviewport = $this->evaluate_script($posviewportjs);
        if ($positionviewport == 0) {
            throw new ExpectationException('The page top is at the top of the viewport', $this->getSession());
        }
    }

    /**
     * Checks if the given DOM element is at the top of the viewport.
     *
     * @copyright 2023 Alexander Bias <bias@alexanderbias.de>
     * @Then DOM element :arg1 is at the top of the viewport
     * @param string $selector
     * @throws ExpectationException
     */
    public function dom_element_is_at_top_of_viewport($selector) {
        $poselementjs = "
            return (
                document.getElementById('$selector').offsetTop
            )
        ";
        $positionelement = $this->evaluate_script($poselementjs);
        $posviewportjs = "
            return (
                window.scrollY
            )
        ";
        $positionviewport = $this->evaluate_script($posviewportjs);
        if ($positionelement > $positionviewport + 50 ||
                $positionelement < $positionviewport - 50) { // Allow some deviation of 50px of the scrolling position.
            throw new ExpectationException('The DOM element \''.$selector.'\' is not a the top of the page', $this->getSession());
        }
    }
}
