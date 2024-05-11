@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menus @theme_boost_union_smartmenusettings_menus_application
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, applying different configurations to the individual smart menus
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | user1    |

  @javascript
  Scenario: Smartmenu: Menus: Application - Add a smart menu to the main navigation
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title            | Links |
      | Menu location(s) | Main  |
    And I add a smart menu static item item "Info" "https://moodle.org"
    Then I should see smart menu "Links" in location "Main"
    And I log out
    And I log in as "user1"
    Then I should see smart menu "Links" in location "Main"

  @javascript
  Scenario: Smartmenu: Menus: Application - Show a single smart menu in multiple locations
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title            | Links                    |
      | Menu location(s) | Main, Menu, Bottom, User |
    And I click on "Save and configure items" "button"
    And I should see "Links" in the "#region-main h4" "css_element"
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Smartmenu Resource |
      | Menu item type | Static             |
      | URL            | https://moodle.org |
    And I click on "Save changes" "button"
    And I click on "Smart menus" "link" in the "#page-navbar .breadcrumb" "css_element"
    Then I should see "Main" in the "Links" "table_row"
    And I should see "Menu" in the "Links" "table_row"
    And I should see "User" in the "Links" "table_row"
    And I should see "Bottom" in the "Links" "table_row"
    Then I should see smart menu "Links" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenu: Menus: Application - Show a smart menu in different locations
    When I log in as "admin"
    And I create smart menu with a default item with the following fields to these values:
      | Title            | Links |
      | Menu location(s) | Main  |
    Then I should see "Main" in the "Links" "table_row"
    And I should see smart menu "Links" in location "Main"
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title            | External resources |
      | Menu location(s) | Menu               |
    And I click on "Save and configure items" "button"
    Then I should see "External resources" in the "#region-main h4" "css_element"
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Smartmenu Resource |
      | Menu item type | Static             |
      | URL            | https://moodle.org |
    And I click on "Save changes" "button"
    And I click on "Smart menus" "link" in the "#page-navbar .breadcrumb" "css_element"
    Then I should see "Menu" in the "External resources" "table_row"
    And I should see smart menu "External resources" item "Smartmenu Resource" in location "Menu"
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title            | Certificates |
      | Menu location(s) | User         |
    And I add a smart menu static item item "Course completions" "https://moodle.org"
    Then I should see "User" in the "Certificates" "table_row"
    And I should not see "Main" in the "Certificates" "table_row"
    And I should see smart menu "Certificates" in location "User"
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title            | SmartMenu Policy |
      | Menu location(s) | Bottom           |
    And I add a smart menu static item item "Privacy" "https://moodle.org/privacy"
    Then I should see "Bottom" in the "SmartMenu Policy" "table_row"
    And I should see smart menu "SmartMenu Policy" in location "Bottom"
