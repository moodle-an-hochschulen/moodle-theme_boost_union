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
 * Theme Boost Union - Custom Behat rules for the 'Smart menus' settings
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

use Behat\Gherkin\Node\{TableNode, PyStringNode};
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * Class behat_theme_boost_union_base_smartmenus
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union_base_smartmenus extends behat_base {
    /**
     * Open the smart menu listing page.
     *
     * @Given /^I navigate to smart menus$/
     */
    public function i_navigate_to_smartmenus() {
        $this->execute(
            'behat_navigation::i_navigate_to_in_site_administration',
            ['Appearance > Boost Union > Smart menus']
        );
    }

    /**
     * Open the smart menu items listing page.
     *
     * @Given /^I navigate to smart menu "(?P<menuname>(?:[^"]|\\")*)" items$/
     * @param string $menu Menu title.
     */
    public function i_navigate_to_smartmenu_item($menu) {
        $this->execute(
            'behat_navigation::i_navigate_to_in_site_administration',
            ['Appearance > Boost Union > Smart menus']
        );
        $this->execute('behat_general::i_click_on_in_the', ['.action-list-items', 'css_element', $menu, 'table_row']);
    }

    /**
     * Test if the smart menu is available and visible in the given locations.
     *
     * @Given /^I should see smart menu "(?P<menu>(?:[^"]|\\")*)" in location "(?P<locations>(?:[^"]|\\")*)"$/
     * @param string $menu Menu title.
     * @param string $locations Menu locations to verify (Main, Menu, User, Bottom)
     */
    public function i_should_see_smartmenu_in_location($menu, $locations) {
        $locations = array_map('trim', explode(',', $locations));

        // Use named selectors to check if the smart menu exists in the specified locations.
        if (in_array('Main', $locations)) {
            $this->execute(
                'behat_general::should_exist',
                [$menu, 'theme_boost_union > Main menu smart menu']
            );
        }
        if (in_array('User', $locations)) {
            $this->execute(
                'behat_general::should_exist',
                [$menu, 'theme_boost_union > User menu smart menu']
            );
        }
        if (in_array('Bottom', $locations)) {
            $this->execute(
                'behat_general::should_exist',
                [$menu, 'theme_boost_union > Bottom bar smart menu']
            );
        }
        if (in_array('Menu', $locations)) {
            $this->execute(
                'behat_general::should_exist',
                [$menu, 'theme_boost_union > Menu bar smart menu']
            );
        }
    }

    /**
     * Test if the smart menus are not available or visible in the given locations.
     *
     * @Given I should not see smart menu :menu in location :location
     * @param string $menu Menu title
     * @param string $location Locations to verify (Main, Menu, Bottom, User)
     */
    public function i_should_not_see_smartmenu_in_location($menu, $location) {
        $locations = array_map('trim', explode(',', $location));

        // Use named selectors to check if the smart menu does not exist in the specified locations.
        if (in_array('Main', $locations)) {
            $this->execute(
                'behat_general::should_not_exist',
                [$menu, 'theme_boost_union > Main menu smart menu']
            );
        }
        if (in_array('User', $locations)) {
            $this->execute(
                'behat_general::should_not_exist',
                [$menu, 'theme_boost_union > User menu smart menu']
            );
        }
        if (in_array('Bottom', $locations)) {
            $this->execute(
                'behat_general::should_not_exist',
                [$menu, 'theme_boost_union > Bottom bar smart menu']
            );
        }
        if (in_array('Menu', $locations)) {
            $this->execute(
                'behat_general::should_not_exist',
                [$menu, 'theme_boost_union > Menu bar smart menu']
            );
        }
    }

    /**
     * Test if the smart menu items are avaialble and visible in the given locations.
     *
     * @Given I should see smart menu :menu item :item in location :locations
     * @param string $menu Menu title
     * @param string $item Menu item title
     * @param string $locations Locations to verify (Main, Menu, Bottom, User)
     */
    public function i_should_see_smartmenu_item_in_location($menu, $item, $locations) {
        $locations = array_map('trim', explode(',', $locations));

        // Use named selectors instead of clicking through menus.
        if (in_array('Main', $locations)) {
            $this->execute(
                'behat_general::should_exist_in_the',
                [$item, 'theme_boost_union > Smart menu item', $menu, 'theme_boost_union > Main menu smart menu']
            );
        }
        if (in_array('User', $locations)) {
            $this->execute(
                'behat_general::should_exist_in_the',
                [$item, 'theme_boost_union > Smart menu item', $menu, 'theme_boost_union > User menu smart menu']
            );
        }
        if (in_array('Bottom', $locations)) {
            $this->execute(
                'behat_general::should_exist_in_the',
                [$item, 'theme_boost_union > Smart menu item', $menu, 'theme_boost_union > Bottom bar smart menu']
            );
        }
        if (in_array('Menu', $locations)) {
            $this->execute(
                'behat_general::should_exist_in_the',
                [$item, 'theme_boost_union > Smart menu item', $menu, 'theme_boost_union > Menu bar smart menu']
            );
        }
    }

    /**
     * Test if the smart menu items are not available and visible in the given locations.
     *
     * @Given I should not see smart menu :menu item :item in location :location
     * @param string $menu Menu title
     * @param string $item Menu item title
     * @param string $location Locations to verify (Main, Menu, Bottom, User)
     */
    public function i_should_not_see_smartmenu_item_in_location($menu, $item, $location) {
        $locations = array_map('trim', explode(',', $location));

        // Use named selectors instead of clicking through menus.
        if (in_array('Main', $locations)) {
            $this->execute(
                'behat_general::should_not_exist_in_the',
                [$item, 'theme_boost_union > Smart menu item', $menu, 'theme_boost_union > Main menu smart menu']
            );
        }
        if (in_array('User', $locations)) {
            $this->execute(
                'behat_general::should_not_exist_in_the',
                [$item, 'theme_boost_union > Smart menu item', $menu, 'theme_boost_union > User menu smart menu']
            );
        }
        if (in_array('Bottom', $locations)) {
            $this->execute(
                'behat_general::should_not_exist_in_the',
                [$item, 'theme_boost_union > Smart menu item', $menu, 'theme_boost_union > Bottom bar smart menu']
            );
        }
        if (in_array('Menu', $locations)) {
            $this->execute(
                'behat_general::should_not_exist_in_the',
                [$item, 'theme_boost_union > Smart menu item', $menu, 'theme_boost_union > Menu bar smart menu']
            );
        }
    }

    /**
     * Check if a smart menu is visible outside the More menu in a given location.
     *
     * @Given I should see smart menu :menu outside more menu in location :location
     * @param string $menu Menu title
     * @param string $location Location name (Main, Menu, Bottom)
     */
    public function i_should_see_smartmenu_outside_more_menu_in_location($menu, $location) {
        $locations = array_map('trim', explode(',', $location));

        // Use named selectors to check if the smart menu exists outside the More menu in the specified locations.
        if (in_array('Main', $locations)) {
            $this->execute(
                'behat_general::should_exist',
                [$menu, 'theme_boost_union > Main menu smart menu outside more menu']
            );
        }
        if (in_array('Menu', $locations)) {
            $this->execute(
                'behat_general::should_exist',
                [$menu, 'theme_boost_union > Menu bar smart menu outside more menu']
            );
        }
        if (in_array('Bottom', $locations)) {
            $this->execute(
                'behat_general::should_exist',
                [$menu, 'theme_boost_union > Bottom bar smart menu outside more menu']
            );
        }
    }

    /**
     * Check if a smart menu is NOT visible outside the More menu in a given location.
     *
     * @Given I should not see smart menu :menu outside more menu in location :location
     * @param string $menu Menu title
     * @param string $location Location name (Main, Menu, Bottom)
     */
    public function i_should_not_see_smartmenu_outside_more_menu_in_location($menu, $location) {
        $locations = array_map('trim', explode(',', $location));

        // Use named selectors to check if the smart menu does not exist outside the More menu in the specified locations.
        if (in_array('Main', $locations)) {
            $this->execute(
                'behat_general::should_not_exist',
                [$menu, 'theme_boost_union > Main menu smart menu outside more menu']
            );
        }
        if (in_array('Menu', $locations)) {
            $this->execute(
                'behat_general::should_not_exist',
                [$menu, 'theme_boost_union > Menu bar smart menu outside more menu']
            );
        }
        if (in_array('Bottom', $locations)) {
            $this->execute(
                'behat_general::should_not_exist',
                [$menu, 'theme_boost_union > Bottom bar smart menu outside more menu']
            );
        }
    }

    /**
     * Check if a smart menu is inside the More menu in a given location.
     *
     * @Given I should see smart menu :menu inside more menu in location :location
     * @param string $menu Menu title
     * @param string $location Location name (Main, Menu, Bottom)
     */
    public function i_should_see_smartmenu_inside_more_menu_in_location($menu, $location) {
        $locations = array_map('trim', explode(',', $location));

        // Use named selectors to check if the smart menu exists inside the More menu in the specified locations.
        if (in_array('Main', $locations)) {
            $this->execute(
                'behat_general::should_exist',
                [$menu, 'theme_boost_union > Main menu smart menu inside more menu']
            );
        }
        if (in_array('Menu', $locations)) {
            $this->execute(
                'behat_general::should_exist',
                [$menu, 'theme_boost_union > Menu bar smart menu inside more menu']
            );
        }
        if (in_array('Bottom', $locations)) {
            $this->execute(
                'behat_general::should_exist',
                [$menu, 'theme_boost_union > Bottom bar smart menu inside more menu']
            );
        }
    }

    /**
     * Check if a smart menu is NOT inside the More menu in a given location.
     *
     * @Given I should not see smart menu :menu inside more menu in location :location
     * @param string $menu Menu title
     * @param string $location Location name (Main, Menu, Bottom)
     */
    public function i_should_not_see_smartmenu_inside_more_menu_in_location($menu, $location) {
        $locations = array_map('trim', explode(',', $location));

        // Use named selectors to check if the smart menu does not exist inside the More menu in the specified locations.
        if (in_array('Main', $locations)) {
            $this->execute(
                'behat_general::should_not_exist',
                [$menu, 'theme_boost_union > Main menu smart menu inside more menu']
            );
        }
        if (in_array('Menu', $locations)) {
            $this->execute(
                'behat_general::should_not_exist',
                [$menu, 'theme_boost_union > Menu bar smart menu inside more menu']
            );
        }
        if (in_array('Bottom', $locations)) {
            $this->execute(
                'behat_general::should_not_exist',
                [$menu, 'theme_boost_union > Bottom bar smart menu inside more menu']
            );
        }
    }
}
