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

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\DriverException;
use Behat\Mink\Exception\ElementNotFoundException;

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
                window.getComputedStyle(document.querySelector('$selector')).getPropertyValue('$style')
            )
        ";
        $computedstyle = $this->evaluate_script($stylejs);
        if ($computedstyle != $value) {
            throw new ExpectationException(
                'The \'' . $selector . '\' DOM element does not have the computed style \'' .
                    $style . '\'=\'' . $value . '\', it has the computed style \'' . $computedstyle . '\' instead.',
                $this->getSession()
            );
        }
    }

    /**
     * Checks if the given DOM element does not have the given computed style.
     *
     * @copyright 2024 Alexander Bias <bias@alexanderbias.de>
     * @Then DOM element :arg1 should not have computed style :arg2 :arg3
     * @param string $selector
     * @param string $style
     * @param string $value
     * @throws ExpectationException
     */
    public function dom_element_should_not_have_computed_style($selector, $style, $value) {
        $stylejs = "
            return (
                window.getComputedStyle(document.querySelector('$selector')).getPropertyValue('$style')
            )
        ";
        $computedstyle = $this->evaluate_script($stylejs);
        if ($computedstyle == $value) {
            throw new ExpectationException('The \'' . $selector . '\' DOM element does have the computed style \'' .
                $style . '\'=\'' . $computedstyle . '\', but it should not have it.', $this->getSession());
        }
    }

    /**
     * Checks if the given DOM element has a background image with the given file name.
     *
     * @copyright 2024 Alexander Bias <bias@alexanderbias.de>
     * @Then DOM element :arg1 should have background image with file name :arg2
     * @param string $selector
     * @param string $filename
     * @throws ExpectationException
     */
    public function dom_element_should_have_background_image($selector, $filename) {
        $stylejs = "
            return (
                window.getComputedStyle(document.querySelector('$selector')).getPropertyValue('background-image')
            )
        ";
        $computedstyle = $this->evaluate_script($stylejs);
        $urlmatches = [];
        preg_match('/url\(["\']?(.*?)["\']?\)/', $computedstyle, $urlmatches);
        $urlfromjs = $urlmatches[1];
        $basenamefromjs = basename($urlfromjs);
        if ($basenamefromjs != $filename) {
            throw new ExpectationException(
                'The \'' . $selector . '\' DOM element does not have a background image with the file ' .
                    'name \'' . $filename . '\', it has the file name \'' . $basenamefromjs . '\' instead.',
                $this->getSession()
            );
        }
    }

    /**
     * Checks if the given DOM element has a CSS filter which is close enough to the given hex color.
     *
     * @copyright 2025 Alexander Bias <abias@ssystems.de>
     * @Then DOM element :arg1 should have a CSS filter close enough to hex color :arg2
     * @param string $selector
     * @param string $color
     * @throws ExpectationException
     */
    public function dom_element_should_have_css_filter_close_to_hex($selector, $color) {
        $stylejs = "
            return (
                window.getComputedStyle(document.querySelector('$selector')).getPropertyValue('filter')
            )
        ";
        $computedfilter = $this->evaluate_script($stylejs);

        // Assess the filter.
        $closeenough = $this->assess_icon_tinting_filter_against_hex($computedfilter, $color);

        if ($closeenough != true) {
            throw new ExpectationException('The \'' . $selector . '\' DOM element with the CSS filter \'' .
                $computedfilter . '\', is not close enough to the color \'' . $color . '\'.', $this->getSession());
        }
    }

    /**
     * Checks if the given DOM element does not have a CSS filter which is close to the given hex color.
     *
     * @copyright 2025 Alexander Bias <abias@ssystems.de>
     * @Then DOM element :arg1 should not have a CSS filter close to hex color :arg2
     * @param string $selector
     * @param string $color
     * @throws ExpectationException
     */
    public function dom_element_should_not_have_css_filter_close_to_hex($selector, $color) {
        $stylejs = "
            return (
                window.getComputedStyle(document.querySelector('$selector')).getPropertyValue('filter')
            )
        ";
        $computedfilter = $this->evaluate_script($stylejs);

        // Assess the filter.
        $closeenough = $this->assess_icon_tinting_filter_against_hex($computedfilter, $color);

        if ($closeenough == true) {
            throw new ExpectationException('The \'' . $selector . '\' DOM element with the CSS filter \'' .
                $computedfilter . '\', is too close to the color \'' . $color . '\'.', $this->getSession());
        }
    }

    /**
     * Assesses if the given CSS filter is close to the given hex color or not.
     *
     * @copyright 2025 Alexander Bias <abias@ssystems.de>
     * @param string $filter
     * @param string $color
     * @return bool
     * @throws ExpectationException
     */
    private function assess_icon_tinting_filter_against_hex($filter, $color) {
        // Extract the matrix values from the filter (and unescape the quotes in the filter before doing that).
        $valuesmatches = [];
        if (!preg_match('/values="([\d\.\s]+)"/', stripslashes($filter), $valuesmatches)) {
            throw new ExpectationException('The given CSS filter does not have feColorMatrix values.', $this->getSession());
        }
        $matrixvaluesstring = $valuesmatches[1];
        $matrixvalues = array_map('floatval', preg_split('/\s+/', trim($matrixvaluesstring)));

        // The color components are at positions 4, 9, and 14 (zero-indexed).
        $ractual = isset($matrixvalues[4]) ? $matrixvalues[4] : 0;
        $gactual = isset($matrixvalues[9]) ? $matrixvalues[9] : 0;
        $bactual = isset($matrixvalues[14]) ? $matrixvalues[14] : 0;

        // Convert the hex color to RGB values.
        $hex = ltrim($color, '#');
        $rexpected = hexdec(substr($hex, 0, 2)) / 255;
        $gexpected = hexdec(substr($hex, 2, 2)) / 255;
        $bexpected = hexdec(substr($hex, 4, 2)) / 255;

        // Compare with some tolerance (1%).
        $tolerance = 0.01;
        $closeenough =
            abs($ractual - $rexpected) <= $tolerance &&
            abs($gactual - $gexpected) <= $tolerance &&
            abs($bactual - $bexpected) <= $tolerance;

        // Return.
        return $closeenough;
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
            throw new \Exception('Scrolling the page to the \'' . $selector . '\' DOM element failed');
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
        if (
            $positionelement > $positionviewport + 50 ||
                $positionelement < $positionviewport - 50
        ) { // Allow some deviation of 50px of the scrolling position.
            throw new ExpectationException(
                'The DOM element \'' . $selector . '\' is not a the top of the page',
                $this->getSession()
            );
        }
    }

    /**
     * Checks if a property of a pseudo-class of an element matches a certain value.
     *
     * @Then /^element "(?P<s>.*?)" pseudo-class "(?P<ps>.*?)" should match "(?P<pr>.*?)": "(?P<v>.*?)"$/
     * @param string $s selector
     * @param string $ps pseudo
     * @param string $pr property
     * @param string $v value
     * @throws ExpectationException
     * @throws DriverException
     */
    public function pseudoclass_content_matches($s, $ps, $pr, $v) {
        if (!$this->running_javascript()) {
            throw new DriverException("Pseudo-classes can only be evaluated with Javascript enabled.");
        }

        $getvalueofpseudoelementjs = "return (
            window.getComputedStyle(document.querySelector(\"" . $s . "\"), ':" . $ps . "').getPropertyValue(\"" . $pr . "\")
        )";

        $result = Normalizer::normalize($this->evaluate_script($getvalueofpseudoelementjs), Normalizer::FORM_C);
        $eq = Normalizer::normalize('"' . $v . '"', Normalizer::FORM_C);

        if (!($result == $eq)) {
            throw new ExpectationException(
                "Didn't find a match for '" . $v . "' with " . $s . ":" . $ps . ".",
                $this->getSession()
            );
        }
    }

    /**
     * Checks if a property of a pseudo-class of an element contains a certain value.
     *
     * @Then /^element "(?P<s>.*?)" pseudo-class "(?P<ps>.*?)" should contain "(?P<pr>.*?)": "(?P<v>.*?)"$/
     * @param string $s selector
     * @param string $ps pseudo
     * @param string $pr property
     * @param string $v value
     * @throws ExpectationException
     * @throws DriverException
     */
    public function pseudoclass_content_contains($s, $ps, $pr, $v) {
        if (!$this->running_javascript()) {
            throw new DriverException("Pseudo-classes can only be evaluated with Javascript enabled.");
        }

        $getvalueofpseudoelementjs = "return (
            window.getComputedStyle(document.querySelector(\"" . $s . "\"), ':" . $ps . "').getPropertyValue(\"" . $pr . "\")
        )";

        $result = Normalizer::normalize($this->evaluate_script($getvalueofpseudoelementjs), Normalizer::FORM_C);
        $needle = Normalizer::normalize($v, Normalizer::FORM_C);
        if (strpos($result, $needle) === false) {
            throw new ExpectationException("Didn't find '" . $v . "' in " . $s . ":" . $ps . ".", $this->getSession());
        }
    }

    /**
     * Checks if a property of a pseudo-class of an element matches 'none'.
     *
     * @Then /^element "(?P<s>(?:[^"]|\\")*)" pseudo-class "(?P<ps>(?:[^"]|\\")*)" should match "(?P<pr>(?:[^"]|\\")*)": none$/
     * @param string $s selector
     * @param string $ps pseudo
     * @param string $pr property
     * @throws ExpectationException
     * @throws DriverException
     */
    public function pseudoclass_content_none($s, $ps, $pr) {
        if (!$this->running_javascript()) {
            throw new DriverException("Pseudo-classes can only be evaluated with Javascript enabled.");
        }

        $pseudoelementcontent = "return (
            window.getComputedStyle(document.querySelector(\"" . $s . "\"), ':" . $ps . "').getPropertyValue(\"" . $pr . "\")
        )";

        $result = $this->evaluate_script($pseudoelementcontent);

        if ($result != "none") {
            throw new ExpectationException($s . ":" . $ps . ".content is: " . $result, $this->getSession());
        }
    }

    /**
     * Purges theme cache and reloads the theme
     *
     * @Given /^the theme cache is purged and the theme is reloaded$/
     */
    public function purge_theme_cache_and_reload_theme() {
        theme_reset_all_caches();
    }

    /**
     * Purges all Boost Union MUC caches
     *
     * @Given /^all Boost Union MUC caches are purged$/
     */
    public function purge_all_boost_union_muc_caches() {
        // Purge the flavours cache.
        \core_cache\helper::purge_by_definition('theme_boost_union', 'flavours');

        // Purge the touch icons iOS cache.
        \core_cache\helper::purge_by_definition('theme_boost_union', 'touchiconsios');

        // Purge the smart menus cache.
        \core_cache\helper::purge_by_definition('theme_boost_union', 'smartmenus');

        // Purge the smart menu items cache.
        \core_cache\helper::purge_by_definition('theme_boost_union', 'smartmenu_items');

        // Purge the hook suppressions cache.
        \core_cache\helper::purge_by_definition('theme_boost_union', 'hooksuppress');

        // Purge the FontAwesome icons cache.
        \core_cache\helper::purge_by_definition('theme_boost_union', 'fontawesomeicons');
    }

    /**
     * Purges all caches
     *
     * @Given /^all caches are purged$/
     */
    public function purge_all_caches() {
        purge_caches();
    }

    /**
     * Disables debugging in Behat.
     *
     * We sometimes need to deactivate debugging for a while as Behat steps would otherwise fail due to the
     * stupid 'Too much data passed as arguments to js_call_amd' debugging message which can't be avoided
     * on Boost Union settings pages as we simply use too much hide_if() there.
     *
     * @Given /^Behat debugging is disabled$/
     */
    public function disable_behat_debugging() {
        set_config('debug', 0);
        set_config('debugdisplay', 0);
    }

    /**
     * Re-enables debugging in Behat.
     *
     * @Given /^Behat debugging is enabled$/
     */
    public function enable_behat_debugging() {
        set_config('debug', 32767);
        set_config('debugdisplay', 1);
    }

    /**
     * Open the login page.
     *
     * @Given /^I am on login page$/
     */
    public function i_am_on_login_page() {
        $this->execute('behat_general::i_visit', ['/login/index.php']);
    }

    /**
     * Check if the given elements are vertically aligned.
     *
     * This function verifies that multiple DOM elements have the same vertical position
     * (same top coordinate) in the browser viewport. It's particularly useful for testing
     * that block regions or other layout elements are properly aligned after JavaScript
     * calculations have been applied.
     *
     * @Then /^DOM elements "(?P<s>(?:[^"]|\\")*)" should be vertically aligned$/
     *
     * @param string $elements List of CSS selectors separated by commas (e.g., "#element1,#element2,.class3")
     * @throws ExpectationException If elements don't exist or are not vertically aligned
     */
    public function dom_elements_are_vertically_aligned($elements) {
        // Split the comma-separated list of selectors into an array.
        $elements = explode(',', $elements);

        // Store the reference top position from the first element.
        $top = null;

        // Iterate through each selector to check alignment.
        foreach ($elements as $selector) {
            // First, verify that the element actually exists in the DOM.
            $elementexists = "return document.querySelector('$selector')";
            $elementexists = $this->evaluate_script($elementexists);
            if ($elementexists === null) {
                throw new ExpectationException('The element \'' . $selector . '\' does not exist', $this->getSession());
            }

            // Get the top position of the current element relative to the viewport.
            $js = "return document.querySelector('$selector').getBoundingClientRect().top";
            $newtop = $this->evaluate_script($js);

            // Set the reference position from the first element.
            if ($top === null) {
                $top = $newtop;
            } else {
                // Compare subsequent elements' top positions with the reference.
                // All elements must have exactly the same top position to be considered aligned.
                if ($newtop !== $top) {
                    throw new ExpectationException('The elements are not vertically aligned', $this->getSession());
                }

                // Update reference for next comparison (though it should be the same).
                $top = $newtop;
            }
        }
    }

    /**
     * Checks the visual order of elements before or after.
     *
     * @Then the visual order of :arg1 :arg2 should be :arg3 :arg4 :arg5
     *
     * @param string $selector1 The first element selector.
     * @param string $type1 The type of the first element selector.
     * @param string $order The expected order (before or after).
     * @param string $selector2 The second element selector.
     * @param string $type2 The type of the second element selector.
     */
    public function element_should_display_order($selector1, $type1, $order, $selector2, $type2) {
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
                $selector1,
                $type1,
                $order,
                $selector2,
                $type2
            ));
        }
    }
}
