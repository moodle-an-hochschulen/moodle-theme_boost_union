@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menus @theme_boost_union_smartmenusettings_menus_management
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, managing the individual smart menus
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Scenario: Smart menus: Menus: Management - When the theme is installed, no smart menus exist
    When I log in as "admin"
    And I navigate to smart menus
    And  I should see "Smart menus" in the "#region-main h2" "css_element"
    Then I should see "There aren't any smart menus created yet. Please create your first smart menu to get things going."
    And "table" "css_element" should not exist in the "#region-main" "css_element"
    And "Create menu" "button" should exist in the "#region-main" "css_element"
    And ".smartmenu-actions" "css_element" should not exist in the "#region-main" "css_element"

  @javascript
  Scenario: Smart menus: Menus: Management - Create a new smart menu
    When I log in as "admin"
    And I navigate to smart menus
    And I should see "Smart menus" in the "#region-main h2" "css_element"
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title            | Links |
      | Menu location(s) | Main  |
    And I click on "Save and return" "button"
    And I should see "Smart menus" in the "#region-main h2" "css_element"
    Then I should not see "There aren't any smart menus created yet. Please create your first smart menu to get things going."
    And "table" "css_element" should exist in the "#region-main" "css_element"
    And the following should exist in the "smartmenus" table:
      | Title | Menu location(s) |
      | Links | Main             |
    And I should see "Links" in the "smartmenus" "table"
    And ".smartmenu-actions" "css_element" should exist in the "smartmenus" "table"
    And I should see "Links" in the "nav.moremenu" "css_element"

  @javascript
  Scenario: Smart menus: Menus: Management - Edit an existing smart menu
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title            | Links |
      | Menu location(s) | Main  |
    And I click on "Save and return" "button"
    Then I should see "Links" in the "smartmenus" "table"
    And I click on ".action-edit" "css_element" in the "Links" "table_row"
    And I set the field "Title" to "Useful Resources"
    And I set the field "Description" to "List of useful external resources"
    And I set the field "Show description" to "Below"
    And I click on "Save and return" "button"
    Then I should not see "Links" in the "smartmenus" "table"
    And I should see "Useful Resources" in the "smartmenus" "table"
    And I should see "List of useful external resources" in the "smartmenus" "table"

  @javascript
  Scenario: Smartmenus: Menus: Management - Delete an existing smart menu
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title | Links |
    And I click on "Save and return" "button"
    And I should see "Links" in the "smartmenus" "table"
    And ".action-delete" "css_element" should exist in the "smartmenus" "table"
    And I click on ".action-delete" "css_element" in the "Links" "table_row"
    And I should see "Are you sure you want to delete this menu from the smart menus?" in the ".moodle-dialogue-confirm" "css_element"
    And I click on "Cancel" "button" in the ".moodle-dialogue-confirm" "css_element"
    And I should see "Links" in the "smartmenus" "table"
    And I click on ".action-delete" "css_element" in the "Links" "table_row"
    And I should see "Are you sure you want to delete this menu from the smart menus?" in the ".moodle-dialogue-confirm" "css_element"
    And I click on "Yes" "button" in the ".moodle-dialogue-confirm" "css_element"
    Then I should see "There aren't any smart menus created yet. Please create your first smart menu to get things going."

  @javascript
  Scenario: Smartmenus: Menus: Management - Duplicate an existing smart menu
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title            | Links |
      | Menu location(s) | Main  |
    And I click on "Save and return" "button"
    And I should see "Links" in the "smartmenus" "table"
    And ".action-copy" "css_element" should exist in the "Links" "table_row"
    And I click on ".action-copy" "css_element" in the "Links" "table_row"
    And "Links" "text" should exist in the "table#smartmenus #smartmenus_r0" "css_element"
    And "Links" "text" should exist in the "table#smartmenus #smartmenus_r1" "css_element"
    And I click on ".action-edit" "css_element" in the "table#smartmenus #smartmenus_r1" "css_element"
    And I set the field "Title" to "Useful Links"
    And I click on "Save and return" "button"
    And I should see "Links" in the "nav.moremenu" "css_element"
    Then I should see "Useful Links" in the "nav.moremenu" "css_element"

  @javascript
  Scenario: Smartmenus: Menus: Management - Modify the visibility of an existing smart menu
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title            | Links |
      | Menu location(s) | Main  |
    And I click on "Save and return" "button"
    And I should see "Links" in the "smartmenus" "table"
    And ".action-hide" "css_element" should exist in the "Links" "table_row"
    And ".action-show" "css_element" should not exist in the "Links" "table_row"
    And I should see "Links" in the "nav.moremenu" "css_element"
    And ".action-hide" "css_element" should exist in the "Links" "table_row"
    And I click on ".action-hide" "css_element" in the "Links" "table_row"
    Then I should not see "Links" in the "nav.moremenu" "css_element"
    And ".action-show" "css_element" should exist in the "Links" "table_row"
    And ".action-hide" "css_element" should not exist in the "Links" "table_row"
    And I click on ".action-show" "css_element" in the "Links" "table_row"
    Then I should see "Links" in the "nav.moremenu" "css_element"

  Scenario: Smartmenus: Menus: Management - Move an existing smart menu up and down
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title | Enrolled courses |
    And I click on "Save and return" "button"
    And I should see "Enrolled courses" in the "smartmenus" "table"
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title | Completed courses |
    And I click on "Save and return" "button"
    And I should see "Completed courses" in the "smartmenus" "table"
    And "Enrolled courses" "table_row" should appear before "Completed courses" "table_row"
    And I click on ".sort-smartmenus-up-action" "css_element" in the "Completed courses" "table_row"
    Then "Enrolled courses" "table_row" should appear after "Completed courses" "table_row"
    And I click on ".sort-smartmenus-up-action" "css_element" in the "Enrolled courses" "table_row"
    And "Enrolled courses" "table_row" should appear before "Completed courses" "table_row"
