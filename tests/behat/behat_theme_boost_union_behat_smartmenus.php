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
 * Theme Boost Union - Custom Behat rules for smartmenus
 *
 * @package    theme_boost_union
 * @copyright  bdecent GmbH 2023
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../../../lib/behat/behat_base.php');

use Behat\Gherkin\Node\{TableNode, PyStringNode};

/**
 * Class behat_theme_boost_union_behat_smartmenus
 *
 * @package    theme_boost_union
 * @copyright  bdecent GmbH 2023e>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union_behat_smartmenus extends behat_base {


    /**
     * Open the smart menu listing page.
     *
     * @Given /^I navigate to smartmenus$/
     *
     */
    public function i_navigate_to_smartmenus() {
        $this->execute('behat_navigation::i_navigate_to_in_site_administration',
            ["Appearance > Themes > Boost Union > Smart menus"]);
    }

    /**
     * Open the smart menu items listing page.
     *
     * @Given /^I navigate to smartmenu "(?P<menuname>(?:[^"]|\\")*)" items$/
     *
     * @param string $menu Menu title.
     */
    public function i_navigate_to_smartmenu_item($menu) {
        $this->execute('behat_navigation::i_navigate_to_in_site_administration',
            ["Appearance > Themes > Boost Union > Smart menus"]);
        $this->execute("behat_general::i_click_on_in_the", ['.action-list-items', "css_element", $menu, 'table_row']);
    }

    /**
     * Fills a smart menu create form with field/value data.
     *
     * @Given /^I create menu with the following fields to these values:$/
     * @throws ElementNotFoundException Thrown by behat_base::find
     * @param TableNode $data
     */
    public function i_create_menu_with_the_following_fields_to_these_values(TableNode $data) {

        $this->execute('behat_navigation::i_navigate_to_in_site_administration',
            ["Appearance > Themes > Boost Union > Smart menus"]);
        $this->execute("behat_general::i_click_on", ["Create new smart menu", "button"]);
        $this->execute('behat_forms::i_set_the_following_fields_to_these_values', [$data]);
        $this->execute("behat_general::i_click_on", ["Save and return", "button"]);
    }

    /**
     * Fills a smart menu item form with field/value data.
     *
     * @Given /^I set "(?P<menuname>(?:[^"]|\\")*)" items with the following fields to these values:$/
     * @throws ElementNotFoundException Thrown by behat_base::find
     * @param string $menu Menu title.
     * @param TableNode $data
     */
    public function i_create_menus_with_the_following_fields_to_these_values($menu, TableNode $data) {

        $this->execute('behat_navigation::i_navigate_to_in_site_administration',
            ["Appearance > Themes > Boost Union > Smart menus"]);
        $this->execute("behat_general::i_click_on_in_the", [".action-edit", "css_element", $menu, 'table_row']);
        $this->execute("behat_general::i_click_on", ["Add new item", "link"]);
        $this->execute('behat_forms::i_set_the_following_fields_to_these_values', [$data]);
        $this->execute("behat_general::i_click_on", ["Save changes", "button"]);
    }

    /**
     * Verify the card size height.
     *
     * @Given /^I should see "(?P<element>(?:[^"]|\\")*)" style "(?P<rule>(?:[^"]|\\")*)" value "(?P<value>(?:[^"]|\\")*)"$/
     * @param string $selector
     * @param string $rule
     * @param string $value
     */
    public function i_should_see_style_value($selector, $rule, $value) {

        // The DOM and the JS should be all ready and loaded. Running without spinning
        // as this is a widely used step and we can not spend time here trying to see
        // a DOM node that is not always there (at the moment clean is not even the
        // default theme...).
        $navbuttonjs = "return (
            Y.one('$selector') &&
            Y.one('$selector').getComputedStyle('$rule') !== $value
        )";

        // Adding an extra click we need to show the 'Log in' link.
        if (!$this->evaluate_script($navbuttonjs)) {
            return false;
        }
        return true;
    }

    /**
     * Test the menus are avaialble and visible in the given locations.
     *
     * @Given /^I should see menu "(?P<menu>(?:[^"]|\\")*)" in location "(?P<locations>(?:[^"]|\\")*)"$/
     *
     * @param string $menu Menu title.
     * @param string $locations Menu locations to verify (Main, Menu, User, Bottom)
     */
    public function i_should_see_menu_in_location($menu, $locations) {

        $locations = array_map('trim', explode(',', $locations));

        if (in_array('Main', $locations)) {
            $this->execute('behat_general::assert_element_contains_text', [$menu, ".primary-navigation", "css_element"]);
        }

        if (in_array('User', $locations)) {
            $this->execute('behat_general::i_click_on', ["#user-menu-toggle", "css_element"]);
            $this->execute('behat_general::assert_element_contains_text', [$menu, "#user-action-menu", "css_element"]);
        }
        if (in_array('Bottom', $locations)) {
            $this->execute('behat_general::i_change_window_size_to', ['viewport', '750x1000']);
            $this->execute('behat_general::assert_element_contains_text', [$menu, ".boost-union-bottom-menu", "css_element"]);
            $this->execute('behat_general::i_change_window_size_to', ['viewport', 'large']);
        }
        if (in_array('Menu', $locations)) {
            $this->execute('behat_general::assert_element_contains_text', [$menu, ".boost-union-menubar", "css_element"]);
        }
    }

    /**
     * Test the menu items are avaialble and visible in the given locations.
     *
     * @Given I should see menu :menu item :item in location :locations
     *
     * @param string $menu Menu title
     * @param string $item Menu item title
     * @param string $locations Locations to verify (Main, Menu, Bottom, User)
     */
    public function i_should_see_menu_item_in_location($menu, $item, $locations) {

        $locations = array_map('trim', explode(',', $locations));

        if (in_array('Main', $locations)) {
            $this->execute('behat_general::i_click_on_in_the', [$menu, "link", ".primary-navigation", "css_element"]);
            $this->execute('behat_general::assert_element_contains_text', [$item, ".primary-navigation", "css_element"]);
        }

        if (in_array('User', $locations)) {
            $this->execute('behat_general::i_click_on', ["#user-menu-toggle", "css_element"]);
            $this->execute('behat_general::i_click_on_in_the', [$menu, "link", "#usermenu-carousel", "css_element"]);
            $this->execute('behat_general::assert_element_contains_text', [$item, ".carousel-item.active", "css_element"]);
            $this->execute('behat_general::i_click_on', ["#user-menu-toggle", "css_element"]);

        }
        if (in_array('Bottom', $locations)) {
            $this->execute('behat_general::i_change_window_size_to', ['viewport', '750x900']);
            $this->execute('behat_general::i_click_on_in_the', [$menu, "link", ".bottom-navigation", "css_element"]);
            $this->execute('behat_general::assert_element_contains_text', [$item, ".bottom-navigation", "css_element"]);
            $this->execute('behat_general::i_change_window_size_to', ['viewport', 'large']);
        }
        if (in_array('Menu', $locations)) {
            $this->execute('behat_general::i_click_on_in_the', [$menu, "link", "nav.menubar", "css_element"]);
            $this->execute('behat_general::assert_element_contains_text', [$item, ".boost-union-menubar", "css_element"]);
        }
    }

    /**
     * Test the menus are not avaialble or visible in the given locations.
     *
     * @Given I should not see menu :menu in location :location
     *
     * @param string $menu Menu title
     * @param string $location Locations to verify (Main, Menu, Bottom, User)
     */
    public function i_should_not_see_menu_in_location($menu, $location) {

        $locations = array_map('trim', explode(',', $location));

        if (in_array('Main', $locations)) {
            $this->execute('behat_general::assert_element_not_contains_text', [$menu, ".primary-navigation", "css_element"]);
        }

        if (in_array('User', $locations)) {
            $this->execute('behat_general::i_click_on', ["#user-menu-toggle", "css_element"]);
            $this->execute('behat_general::assert_element_not_contains_text', [$menu, "#user-action-menu", "css_element"]);
        }
        if (in_array('Bottom', $locations)) {
            $this->execute('behat_general::i_change_window_size_to', ['viewport', '750x900']);
            $this->execute('behat_general::assert_element_not_contains_text', [$menu, ".boost-union-bottom-menu", "css_element"]);
            $this->execute('behat_general::i_change_window_size_to', ['viewport', 'large']);
        }
        if (in_array('Menu', $locations)) {
            $this->execute('behat_general::assert_element_not_contains_text', [$menu, ".boost-union-menubar", "css_element"]);
        }
    }

    /**
     * Test the menu items are not avaialble and visible in the given locations.
     *
     * @Given I should not see menu :menu item :item in location :location
     *
     * @param string $menu Menu title
     * @param string $item Menu item title
     * @param string $location Locations to verify (Main, Menu, Bottom, User)
     */
    public function i_should_not_see_menu_item_in_location($menu, $item, $location) {

        $locations = array_map('trim', explode(',', $location));

        if (in_array('Main', $locations)) {
            $this->execute('behat_general::i_click_on_in_the', [$menu, "link", ".primary-navigation", "css_element"]);
            $this->execute('behat_general::assert_element_not_contains_text', [$item, ".primary-navigation", "css_element"]);
        }

        if (in_array('User', $locations)) {
            $this->execute('behat_general::i_click_on', ["#user-menu-toggle", "css_element"]);
            $this->execute('behat_general::i_click_on_in_the', [$menu, "link", "#usermenu-carousel", "css_element"]);
            $this->execute('behat_general::assert_element_not_contains_text', [$item, ".carousel-item.active", "css_element"]);
            $this->execute('behat_general::i_click_on', ["#user-menu-toggle", "css_element"]);
        }

        if (in_array('Bottom', $locations)) {
            $this->execute('behat_general::i_change_window_size_to', ['viewport', '750x900']);
            $this->execute('behat_general::i_click_on_in_the', [$menu, "link", ".bottom-navigation", "css_element"]);
            $this->execute('behat_general::assert_element_not_contains_text', [$item, ".boost-union-bottom-menu", "css_element"]);
            $this->execute('behat_general::i_change_window_size_to', ['viewport', 'large']);
        }

        if (in_array('Menu', $locations)) {
            $this->execute('behat_general::i_click_on_in_the', [$menu, "link", "nav.menubar", "css_element"]);
            $this->execute('behat_general::assert_element_not_contains_text', [$item, ".boost-union-menubar", "css_element"]);
        }
    }
}
