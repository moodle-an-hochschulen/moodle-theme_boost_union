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

  @javascript
  Scenario: Smartmenu: Menus: Application - Show a smart menu in the main navigation together with a custom menu
    Given I log in as "admin"
    And I navigate to "Appearance > Advanced theme settings" in site administration
    And I set the field "Custom menu items" to multiline:
    """
    Custom menu
    -Custom node 1|/foo/
    -Custom node 2|/bar/foobar.php
    """
    And I press "Save changes"
    And the following "theme_boost_union > smart menu" exists:
      | title    | Smart menu      |
      | location | Main navigation |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Smart menu        |
      | title    | Smart menu node 1 |
      | itemtype | Static            |
      | url      | /foooo            |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Smart menu        |
      | title    | Smart menu node 2 |
      | itemtype | Static            |
      | url      | /baaar            |
    And I log out
    And I log in as "user1"
    # Resize the window to avoid that menus fall into the "More" menu and influence this test.
    And I change viewport size to "large"
    Then I should see smart menu "Smart menu" in location "Main"
    And "Smart menu node 1" "theme_boost_union > Smart menu item" should exist in the "Smart menu" "theme_boost_union > Main menu smart menu"
    And "Smart menu node 2" "theme_boost_union > Smart menu item" should exist in the "Smart menu" "theme_boost_union > Main menu smart menu"
    And I should see "Custom menu" in the "nav" "css_element"
    And I click on "Custom menu" "link" in the "nav" "css_element"
    And I should see "Custom node 1" in the "nav" "css_element"
    And I should see "Custom node 2" in the "nav" "css_element"
    And "Custom menu" "link" should appear before "Smart menu" "link" in the "nav" "css_element"
    And "My courses" "link" should appear before "Custom menu" "link" in the "nav" "css_element"

  @javascript
  Scenario Outline: Smartmenu: Menus: Application - Verify the bottom bar existence
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Smart menu                                       |
      | location | <smartmenulocations> |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Smart menu        |
      | title    | Smart menu node 1 |
      | itemtype | Static            |
      | url      | /foo              |
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Help menu           |
      | location | <helpmenulocations> |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Help menu         |
      | title    | Moodle ORG        |
      | itemtype | Static            |
      | url      | /bar              |
    And I log out
    And I log in as "user1"
    And I change viewport size to "mobile"
    Then I <smartmenushouldornot> see smart menu "Smart menu" in location "Bottom"
    And I <helpmenushouldornot> see smart menu "Help menu" in location "Bottom"
    And the "class" attribute of "body" "css_element" <bodyclassshouldornot> contain "theme-boost-union-bottombar"
    And I log out
    And I change viewport size to "large"
    And I log in as "admin"
    And I navigate to smart menus
    And I click on ".action-edit" "css_element" in the "Smart menu" "table_row"
    And I set the following fields to these values:
      | Menu location(s) | <smartmenueditlocations> |
    And I click on "Save and return" "button"
    And I click on ".action-edit" "css_element" in the "Help menu" "table_row"
    And I set the following fields to these values:
      | Menu location(s) | <helpmenueditlocations> |
    And I click on "Save and return" "button"
    And I log out
    And I log in as "user1"
    And I change viewport size to "mobile"
    Then I <smartmenueditshouldornot> see smart menu "Smart menu" in location "Bottom"
    And I <helpmenueditshouldornot> see smart menu "Help menu" in location "Bottom"
    And the "class" attribute of "body" "css_element" <bodyclasseditshouldornot> contain "theme-boost-union-bottombar"

    Examples:
      | smartmenulocations          | helpmenulocations           | smartmenushouldornot | helpmenushouldornot | bodyclassshouldornot | smartmenueditlocations      | helpmenueditlocations       | smartmenueditshouldornot | helpmenueditshouldornot | bodyclasseditshouldornot |
      | Main navigation, Bottom bar | Main navigation, Bottom bar | should               | should              | should               | Main navigation, Bottom bar | Main navigation             | should                   | should not              | should                   |
      | Main navigation, Bottom bar | Main navigation, Bottom bar | should               | should              | should               | Main navigation             | Main navigation             | should not               | should not              | should not               |
      | Main navigation             | Main navigation             | should not           | should not          | should not           | Main navigation, Bottom bar | Main navigation, Bottom bar | should                   | should                  | should                   |
