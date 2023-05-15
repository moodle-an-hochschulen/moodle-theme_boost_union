@theme @theme_boost_union @theme_boost_union_smartmenu @theme_boost_union_menuitemsettings @theme_boost_union_menuitemsettings_management

Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, managing the individual smart menu items

  In order to use the features
  As admin
  I need to be able to configure the theme boost union plugin

  Background:
    Given I log in as "admin"
    And I create menu with the following fields to these values:
    | Title     | Quick Links              |
    | Locations | Main, Menu, User, Bottom |

  @javascript
  Scenario: Smartmenus: Management - When the theme is installed no smart menus item exist
    When I log in as "admin"
    And I navigate to "Appearance > Themes > Boost Union > Smart menus" in site administration
    Then I should see "Smart menus" in the "#region-main h2" "css_element"
    And I click on ".action-list-items" "css_element" in the "Quick Links" "table_row"
    And I should see "There aren't any items are created for this menu. Please add first item to this menu."
    And "table" "css_element" should not exist in the "#region-main" "css_element"
    And "Add new item" "link" should exist in the "#region-main" "css_element"
    And ".menu-item-actions" "css_element" should not exist in the "#region-main" "css_element"

  @javascript
  Scenario: Smart menus: Management - Create new menu item
    Given I log in as "admin"
    And I navigate to smartmenus
    Then I should see "Smart menus" in the "#region-main h2" "css_element"
    And I click on ".action-list-items" "css_element" in the "Quick Links" "table_row"
    And I should see "\"Quick Links\" - items" in the "#region-main" "css_element"
    And I click on "Add new item" "link"
    And I set the following fields to these values:
    | Title | Info    |
    | Type  | Heading |
    And I click on "Save changes" "button"
    Then I should not see "There aren't any items are created for this menu. Please add first item to this menu."
    And "table" "css_element" should exist in the "#region-main" "css_element"
    And I should see "Info" in the "smartmenus_item" "table"
    And ".menu-item-actions" "css_element" should exist in the "smartmenus_item" "table"
    And I click on "Quick Links" "text" in the ".menubar" "css_element"
    And I should see "Info" in the "nav.moremenu" "css_element"

  @javascript
  Scenario: Smartmenus: Management - Edit an existing menu item
    Given I log in as "admin"
    And I navigate to smartmenus
    And I click on ".action-list-items" "css_element" in the "Quick Links" "table_row"
    And I click on "Add new item" "link"
    And I set the following fields to these values:
    | Title | Info    |
    | Type  | Heading |
    And I click on "Save changes" "button"
    And I should see "Info" in the "smartmenus_item" "table"
    And I click on ".action-edit" "css_element" in the "Info" "table_row"
    And I set the field "Title" to "Usefull Resources"
    And I set the field "Type" to "Static"
    And I set the field "URL" to "https://moodle.org"
    And I click on "Save changes" "button"
    Then I should not see "Info" in the "smartmenus_item" "table"
    And I should see "Usefull Resources" in the "smartmenus_item" "table"

  @javascript
  Scenario: Smartmenus: Management - Delete an existing menu item
    Given I log in as "admin"
    And I set "Quick Links" items with the following fields to these values:
    | Title| Info    |
    | Type | Heading |
    And ".action-delete" "css_element" should exist in the "smartmenus_item" "table"
    And I click on ".action-delete" "css_element" in the "Info" "table_row"
    Then I should see "Are you sure you want to delete this menu item from the smart menus?" in the ".moodle-dialogue-confirm" "css_element"
    And I click on "Cancel" "button" in the ".moodle-dialogue-confirm" "css_element"
    And I should see "Info" in the "smartmenus_item" "table"
    And I click on ".action-delete" "css_element" in the "Info" "table_row"
    Then I should see "Are you sure you want to delete this menu item from the smart menus?" in the ".moodle-dialogue-confirm" "css_element"
    And I click on "Yes" "button" in the ".moodle-dialogue-confirm" "css_element"
    Then "smartmenus_item" "table" should not exist
    And I should see "There aren't any items are created for this menu. Please add first item to this menu."

  @javascript
  Scenario: Smartmenus: Management - Duplicate an existing menu items
    Given I set "Quick Links" items with the following fields to these values:
    | Title| Info    |
    | Type | Heading |
    And I should see "Info" in the "smartmenus_item" "table"
    And ".action-copy" "css_element" should exist in the "Info" "table_row"
    And I click on ".action-copy" "css_element" in the "Info" "table_row"
    Then "Info" "text" should exist in the "table#smartmenus_item tbody tr:nth-child(1)" "css_element"
    And "Info" "text" should exist in the "table#smartmenus_item tbody tr:nth-child(2)" "css_element"
    And I click on ".action-edit" "css_element" in the "table#smartmenus_item tbody tr:nth-child(2)" "css_element"
    And I set the field "Title" to "External Resources"
    And I click on "Save changes" "button"
    And I click on "Quick Links" "link" in the "nav.moremenu" "css_element"
    And I should see "Info" in the "nav.moremenu" "css_element"
    And I should see "External Resources" in the "nav.moremenu" "css_element"

  @javascript
  Scenario: Smartmenus: Management - Modify the visibility of an existing menu items
    Given I log in as "admin"
    And I set "Quick Links" items with the following fields to these values:
    | Title| Info    |
    | Type | Heading |
    And I should see "Info" in the "smartmenus_item" "table"
    And "[data-action=hide]" "css_element" should exist in the "Info" "table_row"
    And ".action-hide" "css_element" should exist in the "Info" "table_row"
    And I click on "Quick Links" "link" in the "nav.moremenu" "css_element"
    And I should see "Info" in the "nav.moremenu" "css_element"
    And I click on ".action-hide" "css_element" in the "Info" "table_row"
    And I click on "Quick Links" "link" in the "nav.moremenu" "css_element"
    Then I should not see "Info" in the "nav.moremenu" "css_element"
    And ".action-hide" "css_element" should not exist in the "Info" "table_row"
    And "[data-action=show]" "css_element" should exist in the "Info" "table_row"
    And I click on ".action-show" "css_element" in the "Info" "table_row"
    Then I click on "Quick Links" "link" in the "nav.moremenu" "css_element"
    And I should see "Info" in the "nav.moremenu" "css_element"

  @javascript
  Scenario: Smartmenus: Management - Move existing menu items up and down
    Given I log in as "admin"
    And I set "Quick Links" items with the following fields to these values:
    | Title| Info    |
    | Type | Heading |
    And I should see "Info" in the "smartmenus_item" "table"
    And I click on "Add new item" "link"
    And I set the field "Title" to "Courses"
    And I set the field "Type" to "Static"
    And I set the field "URL" to "https://moodle.org/course"
    And I click on "Save changes" "button"
    And I should see "Courses" in the "smartmenus_item" "table"
    And "Info" "table_row" should appear before "Courses" "table_row"
    And I click on ".action-moveup" "css_element" in the "Courses" "table_row"
    Then "Info" "table_row" should appear after "Courses" "table_row"
    And I click on ".action-moveup" "css_element" in the "Info" "table_row"
    And "Info" "table_row" should appear before "Courses" "table_row"
    And I click on "Add new item" "link"
    And I set the field "Title" to "Badges"
    And I set the field "Type" to "Static"
    And I set the field "URL" to "https://moodle.org/badges"
    And I click on "Save changes" "button"
    And I should see "Badges" in the "smartmenus_item" "table"
    And "Info" "table_row" should appear before "Courses" "table_row"
    And "Badges" "table_row" should appear after "Courses" "table_row"
    And I click on ".action-moveup" "css_element" in the "Badges" "table_row"
    Then "Courses" "table_row" should appear after "Badges" "table_row"
    And I click on ".action-moveup" "css_element" in the "Badges" "table_row"
    And "Info" "table_row" should appear after "Badges" "table_row"
