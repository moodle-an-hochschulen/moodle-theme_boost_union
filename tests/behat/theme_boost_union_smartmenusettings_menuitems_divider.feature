@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_divider
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, using a divider link item
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "courses" exist:
      | fullname               | shortname | category |
      | Test course1           | C1        | 0        |
      | Test course2           | C2        | 0        |
      | Test course word count | C3        | 0        |
    And the following "users" exist:
      | username |
      | user1    |
    And the following "theme_boost_union > smart menu" exists:
      | title    | Quick links                                      |
      | location | Main navigation, Menu bar, User menu, Bottom bar |

  @javascript
  Scenario: Smartmenus: Menu items: Divider - Use a divider in smart menu
    When I log in as "admin"
    And I navigate to smart menu "Quick links" items
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Menu item type | Divider |
    And I click on "Save changes" "button"
    # Divider in main navigation.
    And ".dropdown-divider" "css_element" should exist in the ".primary-navigation" "css_element"
    # Divider in user menu.
    And ".dropdown-divider" "css_element" should exist in the "#usermenu-carousel" "css_element"
    # Divider in bottom menu.
    And ".dropdown-divider" "css_element" should exist in the ".bottom-navigation" "css_element"
    # Divider in menubar.
    And ".dropdown-divider" "css_element" should exist in the "nav.menubar" "css_element"
