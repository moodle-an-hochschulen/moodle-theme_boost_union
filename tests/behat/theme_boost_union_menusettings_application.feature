@theme @theme_boost_union @theme_boost_union_smartmenu @theme_boost_union_menusettings @theme_boost_union_menusettings_application
Feature: Configuring the theme_boost_union plugin on the "Smart menu items" page, applying different configuration to the individual menu items
  In order to use th features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | user1 |
      | user2 |
    And the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | CH1      |
      | Cohort 2 | CH1      |
    And the following "cohort members" exist:
      | user     | cohort |
      | user1 | CH1    |

  @javascript
  Scenario: Smartmenu: Application - Add a smartmenu to the main navigation
    Given I log in as "admin"
    And I navigate to smartmenus
    And I click on "Create new smart menu" "button"
    And I set the following fields to these values:
    | Title | Links |
    | Locations | Main |
    And I click on "Save and return" "button"
    And I should see "Links" in the "nav.moremenu" "css_element"
    And I log out
    And I log in as "user1"
    And I should see "Links" in the "nav.moremenu" "css_element"
    And I log out
    And I log in as "user2"
    And I should see "Links" in the "nav.moremenu" "css_element"

  @javascript
  Scenario: Smartmenu: Application - Display menu description in difference places on smart menus table
    Given I log in as "admin"
    And I navigate to smartmenus
    And I click on "Create new smart menu" "button"
    And I set the field "Title" to "Usefull Resources"
    And I set the field "Description" to "List of usefull external resources"
    And I set the field "Show description" to "Below"
    And I set the field "Locations" to "Main navigation, Menu bar"
    And I click on "Save and return" "button"
    And I set "Usefull Resources" items with the following fields to these values:
    | Title| Info    |
    | Type | Heading |
    And I click on "Usefull Resources" "link" in the ".primary-navigation" "css_element"
    # And I should see "List of usefull external resources"
    And "List of usefull external resources" "text" should appear after "Info" "link"
    And I navigate to smartmenus
    And I click on ".action-edit" "css_element" in the "Usefull Resources" "table_row"
    And I set the field "Show description" to "Above"
    And I click on "Save and return" "button"
    And I click on "Usefull Resources" "link" in the ".primary-navigation" "css_element"
    And "List of usefull external resources" "text" should appear before "Info" "link"
    And I click on ".action-edit" "css_element" in the "Usefull Resources" "table_row"
    And I set the field "Show description" to "Help"
    And I click on "Save and return" "button"
    And "i.fa-question-circle" "css_element" should appear before "Info" "link"
    And I click on ".action-edit" "css_element" in the "Usefull Resources" "table_row"
    And I set the field "Show description" to "Never"
    And I click on "Save and return" "button"
    And "List of usefull external resources" "text" should not exist in the ".primary-navigation" "css_element"

  @javascript
  Scenario: Smartmenu: Application - Shown a menu in multiple locations
    Given I log in as "admin"
    And I navigate to smartmenus
    And I click on "Create new smart menu" "button"
    And I set the following fields to these values:
    | Title     | Links                    |
    | Locations | Main, Menu, Bottom, User |
    And I click on "Save and configure items" "button"
    And I should see "\"Links\" - items" in the "#region-main" "css_element"
    And I click on "Add new item" "link"
    And I set the following fields to these values:
    | Title | Smartmenu Resourse |
    | Type  | Static             |
    | URL   | https://moodle.org |
    And I click on "Save changes" "button"
    And I should see "Back to all smart menus"
    And I click on "Back to all smart menus" "button"
    And I should see "Main" in the "Links" "table_row"
    And I should see "Menu" in the "Links" "table_row"
    And I should see "User" in the "Links" "table_row"
    And I should see "Bottom" in the "Links" "table_row"
    And I should see "Links" in the ".primary-navigation" "css_element"
    And I click on "#user-menu-toggle" "css_element"
    And "#user-action-menu" "css_element" should be visible
    And I should see "Links" in the "#user-action-menu" "css_element"
    And ".boost-union-menubar" "css_element" should be visible
    And I click on "Links" "link" in the ".boost-union-menubar" "css_element"
    And I should see "Smartmenu Resourse" in the ".boost-union-menubar" "css_element"
    And I change viewport size to "740x1000"
    And ".boost-union-bottom-menu" "css_element" should be visible
    And I should see "Links" in the ".boost-union-bottom-menu" "css_element"

  @javascript
  Scenario: Smartmenu: Application - Shown multiple menus in different locations
    Given I log in as "admin"
    And I navigate to smartmenus
    And I click on "Create new smart menu" "button"
    And I set the following fields to these values:
    | Title     | Links |
    | Locations | Main |
    And I click on "Save and return" "button"
    And I should see "Main" in the "Links" "table_row"
    And I should see "Links" in the ".primary-navigation" "css_element"
    And I click on "Create new smart menu" "button"
    And I set the following fields to these values:
    | Title     | External resources |
    | Locations | Menu               |
    And I click on "Save and configure items" "button"
    And I should see "\"External resources\" - items" in the "#region-main" "css_element"
    And I click on "Add new item" "link"
    And I set the following fields to these values:
    | Title | Smartmenu Resourse |
    | Type  | Static             |
    | URL   | https://moodle.org |
    And I click on "Save changes" "button"
    And I should see "Back to all smart menus"
    And I click on "Back to all smart menus" "button"
    And I should see "Menu" in the "External resources" "table_row"
    And ".boost-union-menubar" "css_element" should be visible
    And I click on "External resources" "link" in the ".boost-union-menubar" "css_element"
    And I should see "Smartmenu Resourse" in the ".boost-union-menubar" "css_element"
    Then I click on "Create new smart menu" "button"
    And I set the following fields to these values:
    | Title     | Certificates  |
    | Locations | User          |
    And I click on "Save and return" "button"
    And I should see "User" in the "Certificates" "table_row"
    And I should not see "Main" in the "Certificates" "table_row"
    And I click on "#user-menu-toggle" "css_element"
    And "#user-action-menu" "css_element" should be visible
    And I should see "Certificates" in the "#user-action-menu" "css_element"
    And I should not see "Links" in the "#user-action-menu" "css_element"
    Then I click on "Create new smart menu" "button"
    And I set the following fields to these values:
    | Title     | SmartMenu Policy  |
    | Locations | Bottom            |
    And I click on "Save and return" "button"
    And I should see "Bottom" in the "SmartMenu Policy" "table_row"
    And ".boost-union-bottom-menu" "css_element" should not be visible
    And I change viewport size to "740x1000"
    And ".boost-union-bottom-menu" "css_element" should be visible
    And I should see "SmartMenu Policy" in the ".boost-union-bottom-menu" "css_element"

  @javascript
  Scenario: Smartmenu: Application - Include the custom css class for menu
    Given I log in as "admin"
    And I navigate to smartmenus
    And I click on "Create new smart menu" "button"
    And I expand all fieldsets
    And I set the following fields to these values:
    | Title     | Quick Links      |
    | Locations | Main navigation  |
    | CSS class | quick-links-menu |
    And I click on "Save and return" "button"
    And ".nav-item.quick-links-menu" "css_element" should exist in the ".primary-navigation" "css_element"
    And I should see "Quick Links" in the ".nav-item.quick-links-menu" "css_element"
    And I click on ".action-edit" "css_element" in the "Quick Links" "table_row"
    And I expand all fieldsets
    And I set the field "CSS class" to "quick-links"
    And I click on "Save and return" "button"
    Then ".nav-item.quick-links-menu" "css_element" should not exist in the ".primary-navigation" "css_element"
    And ".nav-item.quick-links" "css_element" should exist in the ".primary-navigation" "css_element"
    And I should see "Quick Links" in the ".nav-item.quick-links" "css_element"

  @javascript
  Scenario: Smartmenu: Application - Use different style for menus
    Given I log in as "admin"
    And I navigate to smartmenus
    And I click on "Create new smart menu" "button"
    And I set the following fields to these values:
    | Title     | Quick Links |
    | Locations | Main        |
    | Type      | Card        |
    And I click on "Save and configure items" "button"
    And I should see "\"Quick Links\" - items" in the "#region-main" "css_element"
    And I click on "Add new item" "link"
    And I set the following fields to these values:
    | Title | Smartmenu Resource |
    | Type  | Static             |
    | URL   | https://moodle.org |
    And I click on "Save changes" "button"
    And ".dropdown.nav-item.card-dropdown" "css_element" should exist in the ".primary-navigation" "css_element"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And ".card-dropdown .dropdown-menu.show" "css_element" should exist in the ".primary-navigation" "css_element"
    And I should see "Smartmenu Resource" in the ".card-dropdown .dropdown-menu.show .card-block" "css_element"
    And I click on "Smart menu settings" "button"
    And I set the field "Type" to "List"
    And I click on "Save and return" "button"
    Then ".dropdown.nav-item.card-dropdown" "css_element" should not exist in the ".primary-navigation" "css_element"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And ".dropdown-menu.show .card-block" "css_element" should not exist in the ".primary-navigation" "css_element"

  @javascript
  Scenario: Smartmenu: Application - Display the menu and it items as card with different size
    Given I log in as "admin"
    And I create menu with the following fields to these values:
    | Title     | Quick Links |
    | Locations | Main        |
    | Type      | Card        |
    And I set "Quick Links" items with the following fields to these values:
    | Title | Smartmenu Resource |
    | Type  | Static             |
    | URL   | https://moodle.org |
    And I click on "Back to all smart menus" "button"
    And ".dropdown.nav-item.card-dropdown" "css_element" should exist in the ".primary-navigation" "css_element"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And I should see ".card-dropdown .dropdown-menu.show img" style "height" value "100"
    And I click on ".action-edit" "css_element" in the "Quick Links" "table_row"
    And I expand all fieldsets
    And I set the field "Card size" to "Medium (150px)"
    And I click on "Save and return" "button"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    Then I should see ".card-dropdown .dropdown-menu.show img" style "height" value "150"
    And I click on ".action-edit" "css_element" in the "Quick Links" "table_row"
    And I expand all fieldsets
    And I set the field "Card size" to "Large (200px)"
    And I click on "Save and return" "button"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    Then I should see ".card-dropdown .dropdown-menu.show img" style "height" value "200"

  @javascript
  Scenario: Smartmenu: Application - Displays the card menus container in different sizes
    Given I log in as "admin"
    And I create menu with the following fields to these values:
    | Title     | Quick Links    |
    | Locations | Main           |
    | Type      | Card           |
    | Card form | Portrait (2/3) |
    And I set "Quick Links" items with the following fields to these values:
    | Title     | Smartmenu Resource |
    | Type      | Static             |
    | URL       | https://moodle.org |
    And I click on "Back to all smart menus" "button"

    And ".dropdown.nav-item.card-dropdown" "css_element" should exist in the ".primary-navigation" "css_element"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    Then ".card-dropdown.card-form-portrait .dropdown-menu.show" "css_element" should exist in the ".primary-navigation" "css_element"
    And I click on ".action-edit" "css_element" in the "Quick Links" "table_row"
    And I expand all fieldsets
    And I set the field "Card form" to "Square (1/1)"
    And I click on "Save and return" "button"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    Then ".card-dropdown.card-form-square  .dropdown-menu.show" "css_element" should exist in the ".primary-navigation" "css_element"
    And I click on ".action-edit" "css_element" in the "Quick Links" "table_row"
    And I expand all fieldsets
    And I set the field "Card form" to "Landscape (3/2)"
    And I click on "Save and return" "button"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    Then ".card-dropdown.card-form-landscape .dropdown-menu.show" "css_element" should exist in the ".primary-navigation" "css_element"
    And I click on ".action-edit" "css_element" in the "Quick Links" "table_row"
    And I expand all fieldsets
    And I set the field "Card form" to "Full width"
    And I click on "Save and return" "button"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    Then ".card-dropdown.card-form-fullwidth .dropdown-menu.show" "css_element" should exist in the ".primary-navigation" "css_element"
