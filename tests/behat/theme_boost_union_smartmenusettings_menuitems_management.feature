@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_management
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, managing the individual smart menu items
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given I log in as "admin"
    And I create smart menu with the following fields to these values:
      | Title            | Quick links              |
      | Menu location(s) | Main, Menu, User, Bottom |

  @javascript
  Scenario: Smartmenus: Menu items: Management - When a smart menu is just created, no smart menu items exist
    When I log in as "admin"
    And I navigate to smart menus
    And I should see "Smart menus" in the "#region-main h2" "css_element"
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    Then I should see "There aren't any items added to this smart menu yet. Please add an item to this menu."
    And "table" "css_element" should not exist in the "#region-main" "css_element"
    And "Add menu item" "button" should exist in the "#region-main" "css_element"
    And ".smartmenu-items-actions" "css_element" should not exist in the "#region-main" "css_element"

  @javascript
  Scenario: Smart menus: Menu items: Management - Create a new smart menu item
    When I log in as "admin"
    And I navigate to smart menus
    And I should see "Smart menus" in the "#region-main h2" "css_element"
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Info    |
      | Menu item type | Heading |
    And I click on "Save changes" "button"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    Then I should not see "There aren't any items added to this smart menu yet. Please add an item to this menu."
    And "table" "css_element" should exist in the "#region-main" "css_element"
    And the following should exist in the "smartmenus_items" table:
      | Title |
      | Info  |
    And I should see "Info" in the "smartmenus_items" "table"
    And ".smartmenu-items-actions" "css_element" should exist in the "smartmenus_items" "table"
    And I should see smart menu "Quick links" item "Info" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenus: Menu items: Management - Edit an existing smart menu item
    When I log in as "admin"
    And I navigate to smart menus
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Info    |
      | Menu item type | Heading |
    And I click on "Save changes" "button"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And the following should exist in the "smartmenus_items" table:
      | Title |
      | Info  |
    And I click on ".action-edit" "css_element" in the "Info" "table_row"
    And I set the field "Title" to "Useful Resources"
    And I set the field "Menu item type" to "Static"
    And I set the field "URL" to "https://moodle.org"
    And I click on "Save changes" "button"
    Then I should not see "Info" in the "smartmenus_items" "table"
    And the following should exist in the "smartmenus_items" table:
      | Title            |
      | Useful Resources |

  @javascript
  Scenario: Smartmenus: Menu items: Management - Delete an existing smart menu item
    When I log in as "admin"
    And I navigate to smart menus
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Info    |
      | Menu item type | Heading |
    And I click on "Save changes" "button"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And the following should exist in the "smartmenus_items" table:
      | Title |
      | Info  |
    And ".action-delete" "css_element" should exist in the "smartmenus_items" "table"
    And I click on ".action-delete" "css_element" in the "Info" "table_row"
    And I should see "Are you sure you want to delete this menu item from the smart menu?" in the ".modal-dialog" "css_element"
    And I click on "Cancel" "button" in the ".modal-dialog" "css_element"
    And I should see "Info" in the "smartmenus_items" "table"
    And I click on ".action-delete" "css_element" in the "Info" "table_row"
    And I should see "Are you sure you want to delete this menu item from the smart menu?" in the ".modal-dialog" "css_element"
    And I click on "Yes" "button" in the ".modal-dialog" "css_element"
    Then "smartmenus_items" "table" should not exist
    And I should see "There aren't any items added to this smart menu yet. Please add an item to this menu."

  @javascript
  Scenario: Smartmenus: Menu items: Management - Duplicate an existing smart menu item
    When I log in as "admin"
    And I navigate to smart menus
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Info    |
      | Menu item type | Heading |
    And I click on "Save changes" "button"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And the following should exist in the "smartmenus_items" table:
      | Title |
      | Info  |
    And ".action-copy" "css_element" should exist in the "Info" "table_row"
    And I click on ".action-copy" "css_element" in the "Info" "table_row"
    Then "Info" "text" should exist in the "table#smartmenus_items #smartmenu_items_r0" "css_element"
    And "Info" "text" should exist in the "table#smartmenus_items #smartmenu_items_r1" "css_element"
    And I click on ".action-edit" "css_element" in the "table#smartmenus_items #smartmenu_items_r1" "css_element"
    And I set the field "Title" to "External Resources"
    And I click on "Save changes" "button"
    Then I should see smart menu "Quick links" item "Info" in location "Main, Menu, User, Bottom"
    And I should see smart menu "Quick links" item "External Resources" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenus: Menu items: Management - Modify the visibility of an existing smart menu item
    When I log in as "admin"
    And I navigate to smart menus
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Info    |
      | Menu item type | Heading |
    And I click on "Save changes" "button"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And the following should exist in the "smartmenus_items" table:
      | Title |
      | Info  |
    And ".action-hide" "css_element" should exist in the "Info" "table_row"
    And ".action-show" "css_element" should not exist in the "Info" "table_row"
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Demo item |
      | Menu item type | Heading   |
    And I click on "Save changes" "button"
    Then I should see smart menu "Quick links" item "Info" in location "Main, Menu, User, Bottom"
    And I click on ".action-hide" "css_element" in the "Info" "table_row"
    Then I should not see smart menu "Quick links" item "Info" in location "Main, Menu, User, Bottom"
    And ".action-hide" "css_element" should not exist in the "Info" "table_row"
    And ".action-show" "css_element" should exist in the "Info" "table_row"
    And I click on ".action-show" "css_element" in the "Info" "table_row"
    Then I should see smart menu "Quick links" item "Info" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenus: Menu items: Management - Move an existing smart menu item up and down
    When I log in as "admin"
    And I navigate to smart menus
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Info    |
      | Menu item type | Heading |
    And I click on "Save changes" "button"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And the following should exist in the "smartmenus_items" table:
      | Title |
      | Info  |
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Courses |
      | Menu item type | Heading |
    And I click on "Save changes" "button"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And the following should exist in the "smartmenus_items" table:
      | Title   |
      | Courses |
    And "Info" "table_row" should appear before "Courses" "table_row"
    And I click on ".sort-smartmenuitems-up-action" "css_element" in the "Courses" "table_row"
    Then "Info" "table_row" should appear after "Courses" "table_row"
    And I click on ".sort-smartmenuitems-up-action" "css_element" in the "Info" "table_row"
    And "Info" "table_row" should appear before "Courses" "table_row"
