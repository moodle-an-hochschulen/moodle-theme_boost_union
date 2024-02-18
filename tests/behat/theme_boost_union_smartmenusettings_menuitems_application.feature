@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_application
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, applying different configurations to the individual smart menu items
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given I log in as "admin"
    And the following "courses" exist:
      | fullname               | shortname | category |
      | Test course1           | C1        | 0        |
      | Test course2           | C2        | 0        |
      | Test course word count | C3        | 0        |
    And the following "users" exist:
      | username |
      | user1    |
    And I create smart menu with the following fields to these values:
      | Title            | Quick links              |
      | Menu location(s) | Main, Menu, User, Bottom |

  @javascript
  Scenario: Smartmenus: Menu items: Application - Add a smart menu item in smart menu to the main navigation
    When I log in as "admin"
    And I navigate to smart menu "Quick links" items
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Badges                    |
      | Menu item type | Static                    |
      | URL            | https://moodle.org/badges |
    And I click on "Save changes" "button"
    And I should see smart menu "Quick links" item "Badges" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "user1"
    And I should see smart menu "Quick links" item "Badges" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenus: Menu items: Application - Display the smart menu items in inline mode
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title                            | Available courses |
      | Menu item type                   | Dynamic courses   |
      | Dynamic courses: Course category | Category 1        |
      | Menu item mode                   | Inline            |
    And I should see "Available courses" in the "smartmenus_items" "table"
    And I should not see smart menu "Quick links" item "Available courses" in location "Main, Menu, User, Bottom"
    And I should see smart menu "Quick links" item "Test course1" in location "Main, Menu, User, Bottom"
    And I should see smart menu "Quick links" item "Test course2" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenus: Menu items: Application - Display the smart menu items in submenu modes
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title                            | Available courses |
      | Menu item type                   | Dynamic courses   |
      | Dynamic courses: Course category | Category 1        |
      | Menu item mode                   | Submenu           |
    And I should see "Available courses" in the "smartmenus_items" "table"
    # Submenu items in main navigation.
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And I should see "Available courses" in the ".primary-navigation" "css_element"
    And I should not see "Test course1" in the ".primary-navigation" "css_element"
    And I click on "Available courses" "link" in the ".primary-navigation" "css_element"
    And I should see "Test course1" in the ".primary-navigation" "css_element"
    And I should see "Test course2" in the ".primary-navigation" "css_element"
    # Submenu items in user menu.
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Quick links" "link" in the "#usermenu-carousel" "css_element"
    And I should see "Available courses" in the "#usermenu-carousel" "css_element"
    And I should not see "Test course1" in the "#usermenu-carousel" "css_element"
    And I click on "Available courses" "link" in the ".carousel-item.active" "css_element"
    And I should see "Test course1" in the ".carousel-item.active" "css_element"
    And I should see "Test course2" in the ".carousel-item.active" "css_element"
    # Submenu items in bottom menu.
    And I change the viewport size to "740x900"
    And I click on "Quick links" "link" in the ".bottom-navigation" "css_element"
    And I should see "Available courses" in the ".bottom-navigation" "css_element"
    And I should not see "Test course1" in the ".bottom-navigation" "css_element"
    And I click on "Available courses" "link" in the ".bottom-navigation" "css_element"
    And I should see "Test course1" in the ".bottom-navigation" "css_element"
    And I should see "Test course2" in the ".bottom-navigation" "css_element"
    And I change the viewport size to "large"
    # Submenu items in menubar.
    And I click on "Quick links" "link" in the "nav.menubar" "css_element"
    And I should see "Available courses" in the "nav.menubar" "css_element"
    And I should not see "Test course1" in the "nav.menubar" "css_element"
    And I click on "Available courses" "link" in the "nav.menubar" "css_element"
    And I should see "Test course1" in the "nav.menubar" "css_element"
    And I should see "Test course2" in the "nav.menubar" "css_element"
