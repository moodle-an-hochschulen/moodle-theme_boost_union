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
      | Title            | Quick links              |
      | Menu location(s) | Main, Menu, User, Bottom |
      | CSS class        | quick-links-menu         |
    And I click on "Save and return" "button"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Resources         |
      | Menu item type | Static            |
      | Menu item URL  | http://moodle.org |
    And the "class" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Quick links')]//.." "xpath_element" should contain "quick-links-menu"
    And the "class" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Quick links')]" "xpath_element" should contain "quick-links-menu"
    And the "class" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Quick links')]//.." "xpath_element" should contain "quick-links-menu"
    And I change the viewport size to "740x900"
    And the "class" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Quick links')]//.." "xpath_element" should contain "quick-links-menu"
    And I change the viewport size to "large"
    And I navigate to smart menus
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And I set the field "CSS class" to "quick-links"
    And I click on "Save and return" "button"
    And the "class" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Quick links')]//.." "xpath_element" should not contain "quick-links-menu"
    And the "class" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Quick links')]//.." "xpath_element" should contain "quick-links"
    And the "class" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Quick links')]" "xpath_element" should not contain "quick-links-menu"
    And the "class" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Quick links')]" "xpath_element" should contain "quick-links"
    And the "class" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Quick links')]//.." "xpath_element" should not contain "quick-links-menu"
    And the "class" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Quick links')]//.." "xpath_element" should contain "quick-links"
    And I change the viewport size to "740x900"
    And the "class" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Quick links')]//.." "xpath_element" should not contain "quick-links-menu"
    And the "class" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Quick links')]//.." "xpath_element" should contain "quick-links"

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
    Then DOM element ".card-dropdown .dropdown-menu.show img" should have computed style "height" "<height>"

    Examples:
      | cardsize       | height |
      | Tiny (50px)    | 50px   |
      | Small (100px)  | 100px  |
      | Medium (150px) | 150px  |
      | Large (200px)  | 200px  |

  @javascript
  Scenario Outline: Smartmenu: Menus: Presentation - Displays the card menu container in various overflow behaviors
    When I log in as "admin"
    And I create smart menu with the following fields to these values:
      | Title                  | Quick Links     |
      | Menu location(s)       | Main navigation |
      | Presentation type      | Card            |
      | Card overflow behavior | <overflow>      |
    And I set "Quick Links" smart menu items with the following fields to these values:
      | Title          | Smartmenu Resource |
      | Menu item type | Static             |
      | Menu item URL  | https://moodle.org |
    And I click on "Smart menus" "link" in the "#page-navbar .breadcrumb" "css_element"
    And ".dropdown.nav-item.card-dropdown" "css_element" should exist in the ".primary-navigation" "css_element"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    Then ".card-dropdown.card-overflow-no-wrap .dropdown-menu.show" "css_element" <nowrapshouldornot> exist in the ".primary-navigation" "css_element"
    And ".card-dropdown.card-overflow-wrap .dropdown-menu.show" "css_element" <wrapshouldornot> exist in the ".primary-navigation" "css_element"

    Examples:
      | overflow | nowrapshouldornot | wrapshouldornot | flexshouldornot |
      | No wrap  | should            | should not      | should          |
      | Wrap     | should not        | should          | should not      |

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

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Add a smart menu with multilang tags
    Given the following "language packs" exist:
      | language |
      | de       |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title            | <span lang="en" class="multilang">Lorem ipsum</span><span lang="de" class="multilang">Dolor sit amet</span> |
      | Menu location(s) | Main, Menu, User, Bottom                                                                                    |
    And I click on "Save and return" "button"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "English ‎(en)‎"
    And I press "Save changes"
    And I am on site homepage
    Then I should see smart menu "Lorem ipsum" in location "Main, Menu, User, Bottom"
    And I should not see smart menu "Dolor sit amet" in location "Main, Menu, User, Bottom"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "Deutsch ‎(de)‎"
    And I press "Save changes"
    And I am on site homepage
    Then I should see smart menu "Dolor sit amet" in location "Main, Menu, User, Bottom"
    And I should not see smart menu "Lorem ipsum" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Display the menus inside and outside more menu
    When I log in as "admin"
    And I create smart menu with the following fields to these values:
      | Title              | Quick links          |
      | Menu location(s)   | Main, Menu           |
      | Menu mode          | Submenu              |
      | More menu behavior | Force into more menu |
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title           | Smartmenu Resource |
      | Menu item type  | Heading            |
    And I click on "Smart menus" "link" in the "#page-navbar .breadcrumb" "css_element"
    And I should not see smart menu "Quick links" in location "Main, Menu"
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    Then I should see smart menu "Quick links" in location "Main"
    And I click on "More" "link" in the ".boost-union-menubar" "css_element"
    Then I should see smart menu "Quick links" in location "Menu"
    And I create smart menu with the following fields to these values:
      | Title            | Test quick demo links 01 |
      | Menu location(s) | Main, Menu               |
    And I create smart menu with the following fields to these values:
      | Title            | Test quick demo links 02 |
      | Menu location(s) | Main, Menu               |
    And I create smart menu with the following fields to these values:
      | Title            | Test quick demo links 03 |
      | Menu location(s) | Main, Menu               |
    And I create smart menu with the following fields to these values:
      | Title            | Test quick demo links 04 |
      | Menu location(s) | Main, Menu               |
    And I create smart menu with the following fields to these values:
      | Title            | Test quick demo links 05 |
      | Menu location(s) | Main, Menu               |
    And I create smart menu with the following fields to these values:
      | Title            | Test quick demo links long title 01  |
      | Menu location(s) | Menu                                 |
    And I create smart menu with the following fields to these values:
      | Title            | Test quick demo links long title 02  |
      | Menu location(s) | Menu                                 |
    And I create smart menu with the following fields to these values:
      | Title            | Test quick demo links 06 |
      | Menu location(s) | Main, Menu               |
    Then I change the viewport size to "1600x495"
    And I should not see smart menu "Test quick demo links 06" in location "Main, Menu"
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    Then I should see smart menu "Test quick demo links 06" in location "Main"
    And I click on "More" "link" in the ".boost-union-menubar" "css_element"
    Then I should see smart menu "Test quick demo links 06" in location "Menu"
    And I click on ".action-edit" "css_element" in the "Test quick demo links 06" "table_row"
    Then I click on "Expand all" "link"
    And I set the field "More menu behavior" to "Keep outside of more menu"
    And I click on "Save and return" "button"
    And I should see smart menu "Test quick demo links 06" in location "Main, Menu"
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    Then I should not see "Test quick demo links 06" in the ".primary-navigation .dropdownmoremenu" "css_element"
    And I click on "More" "link" in the ".boost-union-menubar" "css_element"
    Then I should not see "Test quick demo links 06" in the ".boost-union-menubar .dropdownmoremenu" "css_element"
