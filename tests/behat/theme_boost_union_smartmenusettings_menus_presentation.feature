@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menus @theme_boost_union_smartmenusettings_menus_presentation
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, applying different presentation options to the individual smart menus
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | user1    |

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Display smart menu description in different places
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title            | Useful Resources                  |
      | Description      | List of useful external resources |
      | Show description | Below                             |
      | Menu location(s) | Main, Menu                        |
    And I click on "Save and return" "button"
    And I set "Useful Resources" smart menu items with the following fields to these values:
      | Title          | Info    |
      | Menu item type | Heading |
    And I click on "Useful Resources" "link" in the ".primary-navigation" "css_element"
    Then "List of useful external resources" "text" should appear after "Info" "link"
    And I navigate to smart menus
    And I click on ".action-edit" "css_element" in the "Useful Resources" "table_row"
    And I set the field "Show description" to "Above"
    And I click on "Save and return" "button"
    And I click on "Useful Resources" "link" in the ".primary-navigation" "css_element"
    And "List of useful external resources" "text" should appear before "Info" "link"
    And I click on ".action-edit" "css_element" in the "Useful Resources" "table_row"
    And I set the field "Show description" to "Help"
    And I click on "Save and return" "button"
    And "i.fa-question-circle" "css_element" should appear before "Info" "link"
    And I click on ".action-edit" "css_element" in the "Useful Resources" "table_row"
    And I set the field "Show description" to "Never"
    And I click on "Save and return" "button"
    And "List of useful external resources" "text" should not exist in the ".primary-navigation" "css_element"

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Include the custom css class to a smart menu
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I expand all fieldsets
    And I set the following fields to these values:
      | Title            | Quick links      |
      | Menu location(s) | Main navigation  |
      | CSS class        | quick-links-menu |
    And I click on "Save and return" "button"
    Then ".nav-item.quick-links-menu" "css_element" should exist in the ".primary-navigation" "css_element"
    And I should see "Quick links" in the ".nav-item.quick-links-menu" "css_element"
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And I set the field "CSS class" to "quick-links"
    And I click on "Save and return" "button"
    Then ".nav-item.quick-links-menu" "css_element" should not exist in the ".primary-navigation" "css_element"
    And ".nav-item.quick-links" "css_element" should exist in the ".primary-navigation" "css_element"
    And I should see "Quick links" in the ".nav-item.quick-links" "css_element"

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Use different styles for a smart menu
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title             | Quick links |
      | Menu location(s)  | Main        |
      | Presentation type | Card        |
    And I click on "Save and configure items" "button"
    And I should see "Quick links" in the "#region-main h4" "css_element"
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Smartmenu Resource |
      | Menu item type | Static             |
      | URL            | https://moodle.org |
    And I click on "Save changes" "button"
    Then ".dropdown.nav-item.card-dropdown" "css_element" should exist in the ".primary-navigation" "css_element"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And ".card-dropdown .dropdown-menu.show" "css_element" should exist in the ".primary-navigation" "css_element"
    And I should see "Smartmenu Resource" in the ".card-dropdown .dropdown-menu.show .card-block" "css_element"
    And I click on "Smart menu settings" "icon" in the "#region-main h4" "css_element"
    And I set the field "Presentation type" to "List"
    And I click on "Save and return" "button"
    Then ".dropdown.nav-item.card-dropdown" "css_element" should not exist in the ".primary-navigation" "css_element"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And ".dropdown-menu.show .card-block" "css_element" should not exist in the ".primary-navigation" "css_element"

  @javascript
  Scenario Outline: Smartmenu: Menus: Presentation - Display the smart menu and its menu items as cards with different sizes
    When I log in as "admin"
    And I create smart menu with the following fields to these values:
      | Title             | Quick links |
      | Menu location(s)  | Main        |
      | Presentation type | Card        |
      | Card size         | <cardsize>  |
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Smartmenu Resource |
      | Menu item type | Static             |
      | URL            | https://moodle.org |
    And I click on "Smart menus" "link" in the "#page-navbar .breadcrumb" "css_element"
    And ".dropdown.nav-item.card-dropdown" "css_element" should exist in the ".primary-navigation" "css_element"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    Then I should see ".card-dropdown .dropdown-menu.show img" style "height" value "<height>"

    Examples:
      | cardsize       | height |
      | Tiny (50px)    | 50     |
      | Small (100px)  | 100    |
      | Medium (150px) | 150    |
      | Large (200px)  | 200    |

  @javascript
  Scenario Outline: Smartmenu: Menus: Presentation - Display the smart menu and its menu items as card withs different aspect ratios
    When I log in as "admin"
    And I create smart menu with the following fields to these values:
      | Title             | Quick links |
      | Menu location(s)  | Main        |
      | Presentation type | Card        |
      | Card form         | <cardform>  |
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Smartmenu Resource |
      | Menu item type | Static             |
      | URL            | https://moodle.org |
    And I click on "Smart menus" "link" in the "#page-navbar .breadcrumb" "css_element"
    And ".dropdown.nav-item.card-dropdown" "css_element" should exist in the ".primary-navigation" "css_element"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    Then ".card-dropdown.card-form-<class> .dropdown-menu.show" "css_element" should exist in the ".primary-navigation" "css_element"

    Examples:
      | cardform        | class     |
      | Portrait (2/3)  | portrait  |
      | Square (1/1)    | square    |
      | Landscape (3/2) | landscape |
      | Full width      | fullwidth |
