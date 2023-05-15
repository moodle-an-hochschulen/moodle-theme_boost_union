@theme @theme_boost_union @theme_boost_union_smartmenu @theme_boost_union_menusettings @theme_boost_union_menusettings_management

Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, managing the individual smart menus

  In order to use the features
  As admin
  I need to be able to configure the theme boost union plugin

  Scenario: Smartmenus: Management - When the theme is installed no smart menus exist
    When I log in as "admin"
    And I navigate to "Appearance > Themes > Boost Union > Smart menus" in site administration
    Then I should see "Smart menus" in the "#region-main h2" "css_element"
    And I should see "There aren't any smart menus are created. Please create your first smart menu."
    And "table" "css_element" should not exist in the "#region-main" "css_element"
    And "Create new smart menu" "button" should exist in the "#region-main" "css_element"
    And ".menu-item-actions" "css_element" should not exist in the "#region-main" "css_element"

  @javascript
  Scenario: Smart menus: Management - Create new smart menu and menu item
    Given I log in as "admin"
    And I navigate to "Appearance > Themes > Boost Union > Smart menus" in site administration
    Then I should see "Smart menus" in the "#region-main h2" "css_element"
    And I click on "Create new smart menu" "button"
    And I should see "Smart menu - settings" in the "#region-main" "css_element"
    And I set the following fields to these values:
    | Title | Links |
    | Locations | Main |
    And I click on "Save and return" "button"
    Then I should see "Smart menus" in the "#region-main h2" "css_element"
    And I should not see "There aren't any smart menus are created. Please create your first smart menu."
    And "table" "css_element" should exist in the "#region-main" "css_element"
    And the following should exist in the "smartmenus" table:
    | Title | Locations |
    | Links | Main      |
    And I should see "Links" in the "smartmenus" "table"
    And ".menu-item-actions" "css_element" should exist in the "smartmenus" "table"
    And I should see "Links" in the "nav.moremenu" "css_element"

  @javascript
  Scenario: Smartmenus: Management - Edit an existing menus
    Given I log in as "admin"
    And I navigate to smartmenus
    And I click on "Create new smart menu" "button"
    And I set the following fields to these values:
    | Title | Links |
    | Locations | Main |
    And I click on "Save and return" "button"
    And I should see "Links" in the "smartmenus" "table"
    And I click on ".action-edit" "css_element" in the "Links" "table_row"
    And I set the field "Title" to "Usefull Resources"
    And I set the field "Description" to "List of usefull external resources"
    And I set the field "Show description" to "Below"
    And I click on "Save and return" "button"
    Then I should not see "Links" in the "smartmenus" "table"
    And I should see "Usefull Resources" in the "smartmenus" "table"
    And I should see "List of usefull external resources" in the "smartmenus" "table"

  @javascript
  Scenario: Smartmenus: Management - Delete an existing menus
    Given I log in as "admin"
    And I navigate to smartmenus
    And I click on "Create new smart menu" "button"
    And I set the following fields to these values:
    | Title | Links |
    And I click on "Save and return" "button"
    And I should see "Links" in the "smartmenus" "table"
    And ".action-delete" "css_element" should exist in the "smartmenus" "table"
    And I click on ".action-delete" "css_element" in the "Links" "table_row"
    Then I should see "Are you sure you want to delete this menu from the smart menus?" in the ".moodle-dialogue-confirm" "css_element"
    And I click on "Cancel" "button" in the ".moodle-dialogue-confirm" "css_element"
    And I should see "Links" in the "smartmenus" "table"
    And I click on ".action-delete" "css_element" in the "Links" "table_row"
    Then I should see "Are you sure you want to delete this menu from the smart menus?" in the ".moodle-dialogue-confirm" "css_element"
    And I click on "Yes" "button" in the ".moodle-dialogue-confirm" "css_element"
    And I should see "There aren't any smart menus are created. Please create your first smart menu."

  @javascript
  Scenario: Smartmenus: Management - Duplicate an existing menus
    Given I log in as "admin"
    And I navigate to smartmenus
    And I click on "Create new smart menu" "button"
    And I set the following fields to these values:
    | Title | Links |
    | Location | Main |
    And I click on "Save and return" "button"
    And I should see "Links" in the "smartmenus" "table"
    And ".action-copy" "css_element" should exist in the "Links" "table_row"
    And I click on ".action-copy" "css_element" in the "Links" "table_row"
    Then "Links" "text" should exist in the "table#smartmenus tr#1_r0" "css_element"
    And "Links" "text" should exist in the "table#smartmenus tr#1_r1" "css_element"
    And I click on ".action-edit" "css_element" in the "table#smartmenus tr#1_r1" "css_element"
    And I set the field "Title" to "Useful Links"
    And I click on "Save and return" "button"
    And I should see "Links" in the "nav.moremenu" "css_element"
    And I should see "Useful Links" in the "nav.moremenu" "css_element"

  @javascript
  Scenario: Smartmenus: Management - Modify the visibility of an existing menus
    Given I log in as "admin"
    And I navigate to smartmenus
    And I click on "Create new smart menu" "button"
    And I set the following fields to these values:
    | Title | Links |
    | Location | Main |
    And I click on "Save and return" "button"
    And I should see "Links" in the "smartmenus" "table"
    And "[data-action=hide]" "css_element" should exist in the "Links" "table_row"
    And I should see "Links" in the "nav.moremenu" "css_element"
    And ".action-hide" "css_element" should exist in the "Links" "table_row"
    And I click on ".action-hide" "css_element" in the "Links" "table_row"
    Then I should not see "Links" in the "nav.moremenu" "css_element"
    And "[data-action=show]" "css_element" should exist in the "Links" "table_row"
    And I click on ".action-show" "css_element" in the "Links" "table_row"
    Then I should see "Links" in the "nav.moremenu" "css_element"

  Scenario: Smartmenus: Management - Move existing menus up and down
    Given I log in as "admin"
    And I navigate to smartmenus
    And I click on "Create new smart menu" "button"
    And I set the field "Title" to "Enroled courses"
    And I click on "Save and return" "button"
    And I should see "Enroled courses" in the "smartmenus" "table"
    And I click on "Create new smart menu" "button"
    And I set the field "Title" to "Completed courses"
    And I click on "Save and return" "button"
    And I should see "Completed courses" in the "smartmenus" "table"
    And "Enroled courses" "table_row" should appear before "Completed courses" "table_row"
    And I click on ".action-moveup" "css_element" in the "Completed courses" "table_row"
    Then "Enroled courses" "table_row" should appear after "Completed courses" "table_row"
    And I click on ".action-moveup" "css_element" in the "Enroled courses" "table_row"
    And "Enroled courses" "table_row" should appear before "Completed courses" "table_row"
