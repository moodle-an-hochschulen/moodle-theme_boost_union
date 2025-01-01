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
use Behat\Mink\Exception\DriverException;

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
            throw new ExpectationException('The \''.$selector.'\' DOM element does not have the computed style \''.
                    $style.'\'=\''.$value.'\', it has the computed style \''.$computedstyle.'\' instead.', $this->getSession());
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
            throw new ExpectationException('The \''.$selector.'\' DOM element does have the computed style \''.
                $style.'\'=\''.$computedstyle.'\', but it should not have it.', $this->getSession());
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
            throw new ExpectationException('The \''.$selector.'\' DOM element does not have a background image with the file '.
                    'name \''.$filename.'\', it has the file name \''.$basenamefromjs.'\' instead.',
                            $this->getSession());
        }
    }

    /**
     * Checks if the given DOM element has a CSS filter which is close enough to the given hex color.
     *
     * @copyright 2024 Alexander Bias <bias@alexanderbias.de>
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

        // Check if the computed filter is close enough to the given color.
        $solver = new \theme_boost_union\lib\hextocssfilter\solver($color);
        $closeenough = $solver->filter_is_close_enough($computedfilter, '2');

        if ($closeenough != true) {
            throw new ExpectationException('The \''.$selector.'\' DOM element with the CSS filter \''.
                $computedfilter.'\', is not close enough to the color \''.$color.'\'.', $this->getSession());
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
            window.getComputedStyle(document.querySelector(\"". $s ."\"), ':".$ps."').getPropertyValue(\"".$pr."\")
        )";

        $result = Normalizer::normalize($this->evaluate_script($getvalueofpseudoelementjs), Normalizer::FORM_C);
        $eq = Normalizer::normalize('"'.$v.'"', Normalizer::FORM_C);

        if (!($result == $eq)) {
            throw new ExpectationException("Didn't find a match for '".$v."' with ".$s.":".$ps.".", $this->getSession());
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
            window.getComputedStyle(document.querySelector(\"". $s ."\"), ':".$ps."').getPropertyValue(\"".$pr."\")
        )";

        $result = Normalizer::normalize($this->evaluate_script($getvalueofpseudoelementjs), Normalizer::FORM_C);
        $needle = Normalizer::normalize($v, Normalizer::FORM_C);
        if (strpos($result, $needle) === false) {
            throw new ExpectationException("Didn't find '".$v."' in ".$s.":".$ps.".", $this->getSession());
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
            window.getComputedStyle(document.querySelector(\"". $s ."\"), ':".$ps."').getPropertyValue(\"".$pr."\")
        )";

        $result = $this->evaluate_script($pseudoelementcontent);

        if ($result != "none") {
            throw new ExpectationException($s.":".$ps.".content is: ".$result, $this->getSession());
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
}
