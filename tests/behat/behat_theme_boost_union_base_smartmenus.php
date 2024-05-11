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

require_once(__DIR__.'/../../../../lib/behat/behat_base.php');

use Behat\Gherkin\Node\{TableNode, PyStringNode};
use Behat\Mink\Exception\ElementNotFoundException as ElementNotFoundException;

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
        $this->execute('behat_navigation::i_navigate_to_in_site_administration',
            ['Appearance > Boost Union > Smart menus']);
    }

    /**
     * Open the smart menu items listing page.
     *
     * @Given /^I navigate to smart menu "(?P<menuname>(?:[^"]|\\")*)" items$/
     * @param string $menu Menu title.
     */
    public function i_navigate_to_smartmenu_item($menu) {
        $this->execute('behat_navigation::i_navigate_to_in_site_administration',
            ['Appearance > Boost Union > Smart menus']);
        $this->execute('behat_general::i_click_on_in_the', ['.action-list-items', 'css_element', $menu, 'table_row']);
    }

    /**
     * Fills a smart menu create form with field/value data.
     *
     * @Given /^I create smart menu with the following fields to these values:$/
     * @throws ElementNotFoundException Thrown by behat_base::find
     * @param TableNode $data
     */
    public function i_create_smartmenu_with_the_following_fields_to_these_values(TableNode $data) {
        $this->execute('behat_navigation::i_navigate_to_in_site_administration',
            ['Appearance > Boost Union > Smart menus']);
        $this->execute('behat_general::i_click_on', ['Create menu', 'button']);
        $this->execute('behat_forms::i_set_the_following_fields_to_these_values', [$data]);
        $this->execute('behat_general::i_click_on', ['Save and return', 'button']);
    }

    /**
     * Populate a smart menu using the provided form field/value data and add a default item to the menu
     * (to make sure that it is not hidden as empty menu).
     *
     * @Given /^I create smart menu with a default item with the following fields to these values:$/
     * @throws ElementNotFoundException Thrown by behat_base::find
     * @param TableNode $data
     */
    public function i_create_smartmenu_with_default_item_with_the_following_fields_to_these_values(TableNode $data) {
        $this->execute('behat_navigation::i_navigate_to_in_site_administration',
            ['Appearance > Boost Union > Smart menus']);
        $this->execute('behat_general::i_click_on', ['Create menu', 'button']);
        $this->execute('behat_forms::i_set_the_following_fields_to_these_values', [$data]);
        $this->execute('behat_general::i_click_on', ['Save and configure items', 'button']);

        // Default item for the menu.
        $items = new TableNode([
            ['Title', 'Info'],
            ['Menu item type', 'Heading'],
        ]);

        $this->execute('behat_general::i_click_on', ['Add menu item', 'button']);
        $this->execute('behat_forms::i_set_the_following_fields_to_these_values', [$items]);
        $this->execute('behat_general::i_click_on', ['Save changes', 'button']);
        $this->execute('behat_general::i_click_on_in_the', ['Smart menus', 'link', '.breadcrumb', "css_element"]);
    }

    /**
     * Adds a static menu item to the currently opened menu.
     *
     * @Given /^I add a smart menu static item item "(?P<itemname>(?:[^"]|\\")*)" "(?P<url>(?:[^"]|\\")*)"$/
     * @throws ElementNotFoundException Thrown by behat_base::find
     * @param string $item Item title.
     * @param string $url URL of the static item.
     */
    public function i_add_menu_static_item($item, $url) {

        $items = new TableNode([
            ['Title', $item],
            ['Menu item type', 'Static'],
            ['URL', $url],
        ]);

        $this->execute('behat_general::i_click_on', ['Save and configure items', 'button']);
        $this->execute('behat_general::i_click_on', ['Add menu item', 'button']);
        $this->execute('behat_forms::i_set_the_following_fields_to_these_values', [$items]);
        $this->execute('behat_general::i_click_on', ['Save changes', 'button']);
        $this->execute('behat_general::i_click_on_in_the', ['Smart menus', 'link', '.breadcrumb', "css_element"]);
    }

    /**
     * Fills a smart menu item form with field/value data.
     *
     * @Given /^I set "(?P<menuname>(?:[^"]|\\")*)" smart menu items with the following fields to these values:$/
     * @throws ElementNotFoundException Thrown by behat_base::find
     * @param string $menu Menu title.
     * @param TableNode $data
     */
    public function i_create_smartmenus_with_the_following_fields_to_these_values($menu, TableNode $data) {
        $this->execute('behat_navigation::i_navigate_to_in_site_administration',
            ['Appearance > Boost Union > Smart menus']);
        $this->execute('behat_general::i_click_on_in_the', ['.action-list-items', 'css_element', $menu, 'table_row']);
        $this->execute('behat_general::i_click_on', ['Add menu item', 'button']);
        $this->execute('behat_forms::i_set_the_following_fields_to_these_values', [$data]);
        $this->execute('behat_general::i_click_on', ['Save changes', 'button']);
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

        if (in_array('Main', $locations)) {
            $this->execute('behat_general::assert_element_contains_text', [$menu, '.primary-navigation', 'css_element']);
        }
        if (in_array('User', $locations)) {
            $this->execute('behat_general::i_click_on', ['#user-menu-toggle', 'css_element']);
            $this->execute('behat_general::assert_element_contains_text', [$menu, '#user-action-menu', 'css_element']);
        }
        if (in_array('Bottom', $locations)) {
            $this->execute('behat_general::i_change_window_size_to', ['viewport', '740x900']);
            $this->execute('behat_general::assert_element_contains_text', [$menu, '.boost-union-bottom-menu', 'css_element']);
            $this->execute('behat_general::i_change_window_size_to', ['viewport', 'large']);
        }
        if (in_array('Menu', $locations)) {
            $this->execute('behat_general::assert_element_contains_text', [$menu, '.boost-union-menubar', 'css_element']);
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

        if (in_array('Main', $locations)) {
            $this->execute('behat_general::i_click_on_in_the', [$menu, 'link', '.primary-navigation', 'css_element']);
            $this->execute('behat_general::assert_element_contains_text', [$item, '.primary-navigation', 'css_element']);
        }
        if (in_array('User', $locations)) {
            $this->execute('behat_general::i_click_on', ['#user-menu-toggle', 'css_element']);
            $this->execute('behat_general::i_click_on_in_the', [$menu, 'link', '#usermenu-carousel', 'css_element']);
            $this->execute('behat_general::assert_element_contains_text', [$item, '.carousel-item.active', 'css_element']);
            $this->execute('behat_general::i_click_on', ['#user-menu-toggle', 'css_element']);
        }
        if (in_array('Bottom', $locations)) {
            $this->execute('behat_general::i_change_window_size_to', ['viewport', '740x900']);
            $this->execute('behat_general::i_click_on_in_the', [$menu, 'link', '.bottom-navigation', 'css_element']);
            $this->execute('behat_general::assert_element_contains_text', [$item, '.bottom-navigation', 'css_element']);
            $this->execute('behat_general::i_change_window_size_to', ['viewport', 'large']);
        }
        if (in_array('Menu', $locations)) {
            $this->execute('behat_general::i_click_on_in_the', [$menu, 'link', 'nav.menubar', 'css_element']);
            $this->execute('behat_general::assert_element_contains_text', [$item, '.boost-union-menubar', 'css_element']);
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

        if (in_array('Main', $locations)) {
            $this->execute('behat_general::assert_element_not_contains_text', [$menu, '.primary-navigation', 'css_element']);
        }
        if (in_array('User', $locations)) {
            $this->execute('behat_general::i_click_on', ['#user-menu-toggle', 'css_element']);
            $this->execute('behat_general::assert_element_not_contains_text', [$menu, '#user-action-menu', 'css_element']);
        }
        if (in_array('Bottom', $locations)) {
            $this->execute('behat_general::i_change_window_size_to', ['viewport', '740x900']);
            // Check if the bottom menu is shown at all.
            try {
                $this->find('css_element', '.boost-union-bottom-menu');
                $bottommenufound = true;
            } catch (ElementNotFoundException $e) {
                // The bottom menu was not found at all.
                // This happens if the bottom menu does not contain any menus at all.
                // But this is fine and should not fail the test step.
                $bottommenufound = false;
            }
            // Only if we have a bottom menu.
            if ($bottommenufound == true) {
                $this->execute('behat_general::assert_element_not_contains_text',
                        [$menu, '.boost-union-bottom-menu', 'css_element']);
            }
            $this->execute('behat_general::i_change_window_size_to', ['viewport', 'large']);
        }
        if (in_array('Menu', $locations)) {
            // Check if the menu bar is shown at all.
            try {
                $this->find('css_element', '.boost-union-menubar');
                $menubarfound = true;
            } catch (ElementNotFoundException $e) {
                // The menu bar was not found at all.
                // This happens if the menu bar does not contain any menus at all.
                // But this is fine and should not fail the test step.
                $menubarfound = false;
            }
            // Only if we have a bottom menu.
            if ($menubarfound == true) {
                $this->execute('behat_general::assert_element_not_contains_text', [$menu, '.boost-union-menubar', 'css_element']);
            }
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

        if (in_array('Main', $locations)) {
            $this->execute('behat_general::i_click_on_in_the', [$menu, 'link', '.primary-navigation', 'css_element']);
            $this->execute('behat_general::assert_element_not_contains_text', [$item, '.primary-navigation', 'css_element']);
        }
        if (in_array('User', $locations)) {
            $this->execute('behat_general::i_click_on', ['#user-menu-toggle', 'css_element']);
            $this->execute('behat_general::i_click_on_in_the', [$menu, 'link', '#usermenu-carousel', 'css_element']);
            $this->execute('behat_general::assert_element_not_contains_text', [$item, '.carousel-item.active', 'css_element']);
            $this->execute('behat_general::i_click_on', ['#user-menu-toggle', 'css_element']);
        }
        if (in_array('Bottom', $locations)) {
            $this->execute('behat_general::i_change_window_size_to', ['viewport', '740x900']);
            $this->execute('behat_general::i_click_on_in_the', [$menu, 'link', '.bottom-navigation', 'css_element']);
            $this->execute('behat_general::assert_element_not_contains_text', [$item, '.boost-union-bottom-menu', 'css_element']);
            $this->execute('behat_general::i_change_window_size_to', ['viewport', 'large']);
        }
        if (in_array('Menu', $locations)) {
            $this->execute('behat_general::i_click_on_in_the', [$menu, 'link', 'nav.menubar', 'css_element']);
            $this->execute('behat_general::assert_element_not_contains_text', [$item, '.boost-union-menubar', 'css_element']);
        }
    }
}
