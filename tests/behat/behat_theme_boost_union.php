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
 * Theme Boost Union - Custom Behat rules
 *
 * @package    theme_boost_union
 * @copyright  2022 Moodle an Hochschulen e.V. <kontakt@moodle-an-hochschulen.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../../../lib/behat/behat_base.php');

use Behat\Behat\Tester\Exception\PendingException as PendingException;

/**
 * Class behat_theme_boost_union
 *
 * @package    theme_boost_union
 * @copyright  2022 Moodle an Hochschulen e.V. <kontakt@moodle-an-hochschulen.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union extends behat_base {
    /**
     * Scroll the page.
     *
     * @copyright 2016 Shweta Sharma on https://stackoverflow.com/a/39613869.
     * @Then /^I scroll page to x "(?P<posx_number>\d+)" y "(?P<posy_number>\d+)"$/
     *
     * @param string $posx The x coordinate to scroll to.
     * @param string $posy The y coordinate to scroll to.
     *
     * @return void
     * @throws Exception
     */
    public function i_scroll_page_to_x_y_coordinates_of_page($posx, $posy) {
        try {
            $this->getSession()->executeScript("(function(){document.getElementById('page').scrollTo($posx, $posy);})();");
        } catch (Exception $e) {
            throw new \Exception("Scrolling the page to given coordinates failed");
        }
    }

    /**
     * Open the imprint page.
     *
     * @Given /^I am on imprint page$/
     */
    public function i_am_on_imprint_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/pages/imprint.php']);
    }

    /**
     * Toggle the edit mode
     *
     * @When /^I toggle editing mode$/
     */
    public function i_toggle_edit_mode() {
        throw new PendingException();
    }

    /**
     * Page reload
     *
     * @Then /^The page will be reloaded$/
     */
    public function the_page_will_be_reloaded() {
        throw new PendingException();
    }

    /**
     * Page view scrolling
     *
     * @Then /^The page view will scroll back to$/
     *
     * @throws PendingException
     * @param int $arg1 An x pixel dimensinovalue
     * @param int $arg2 An y pixel dimension value
     */
    public function the_page_view_will_scroll_back_to($arg1, $arg2) {
        throw new PendingException();
    }

    /**
     * Page view stops
     *
     * @Then /^The page view will remain at$/
     *
     * @throws PendingException
     * @param int $arg1 An x pixel dimensinovalue
     * @param int $arg2 An y pixel dimension value
     */
    public function the_page_view_will_remain_at($arg1, $arg2) {
        throw new PendingException();
    }
}
