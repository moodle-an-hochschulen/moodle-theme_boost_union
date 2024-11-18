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
    And "i.fa-circle-question" "css_element" should appear before "Info" "link"
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
    And I add a smart menu static item item "Multilang" "https://moodle.org"
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
  Scenario Outline: Smartmenu: Menus: Presentation - Display the menus inside and outside more menu (Looking at the visible menus in the main menu)
    When I log in as "admin"
    Given I create smart menu with a default item with the following fields to these values:
      | Title              | Quick links 01 |
      | Menu location(s)   | Main           |
      | Menu mode          | Submenu        |
      | More menu behavior | <menu1beh>     |
    And I create smart menu with a default item with the following fields to these values:
      | Title              | Quick links 02 |
      | Menu location(s)   | Main           |
      | Menu mode          | Submenu        |
      | More menu behavior | <menu2beh>     |
    And I create smart menu with a default item with the following fields to these values:
      | Title              | Quick links 03 |
      | Menu location(s)   | Main           |
      | Menu mode          | Submenu        |
      | More menu behavior | <menu3beh>     |
    # Set the frontpage title to better control the available space in the navbar
    And I am on site homepage
    And I click on "Settings" "link" in the ".secondary-navigation" "css_element"
    And I set the field "id_s__shortname" to "Boost Union Test"
    And I press "Save changes"
    # Hide the standard navigation items to better reproduce the "More" behaviour in the navbar
    And the following config values are set as admin:
      | config                     | value                             | plugin            |
      | hidenodesprimarynavigation | home,myhome,courses,siteadminnode | theme_boost_union |
    And I follow "Dashboard"
    # Make the screen really large to test the "More" behaviour without any screen real estate constraints
    And I change the viewport size to "large"
    Then I <menu1shouldornotlarge> see smart menu "Quick links 01" in location "Main"
    And I <menu2shouldornotlarge> see smart menu "Quick links 02" in location "Main"
    And I <menu3shouldornotlarge> see smart menu "Quick links 03" in location "Main"
    And ".primary-navigation .dropdownmoremenu" "css_element" <moreshouldornotlarge> be visible
    # Make the screen smaller and test the "More" behaviour in the navbar
    And I change the viewport size to "tablet"
    Then I <menu1shouldornottablet> see smart menu "Quick links 01" in location "Main"
    And I <menu2shouldornottablet> see smart menu "Quick links 02" in location "Main"
    And I <menu3shouldornottablet> see smart menu "Quick links 03" in location "Main"
    And ".primary-navigation .dropdownmoremenu" "css_element" <moreshouldornottablet> be visible

    Examples:
      # Behaviour option IDs: 0 = Do not change anything, 1 = Force into more menu, 2 = Keep outside of more menu
      | menu1beh | menu1shouldornotlarge | menu1shouldornottablet | menu2beh | menu2shouldornotlarge | menu2shouldornottablet | menu3beh | menu3shouldornotlarge | menu3shouldornottablet | moreshouldornotlarge | moreshouldornottablet |
      # Example 1: On larger screens, all menus are shown. On smaller screens, the 01 and 02 menus are shown as they have enough space and the 03 menu is moved to the more menu due to lack of space.
      # Against this background, this example should look like this:
      # | 0        | should                | should                 | 0        | should                | should                 | 0        | should                | should not             | should not           | should                |
      # Unfortunately, due to MDL-81892, it looks like this and can't be corrected before MDL-81892 is fixed:
      | 0        | should                | should                 | 0        | should                | should not             | 0        | should                | should not             | should not           | should                |
      # Example 2: On larger screens, the 01 menu is moved to the more menu due to its configuration and the 02 and 03 menus are shown. On smaller screens, the same is the case.
      | 1        | should not            | should not             | 0        | should                | should                 | 0        | should                | should                 | should               | should                |
      # Example 3: On larger screens, all menus are shown. On smaller screens, the 01 menu is shown as it has enough space, the 03 menu is shown as it should be kept out of the more menu due to its configuration and the 02 menu is moved to the more menu due to lack of space.
      # Against this background, this example should look like this:
      # | 0        | should                | should                 | 0        | should                | should not             | 2        | should                | should                 | should not           | should                |
      | 0        | should                | should not             | 0        | should                | should not             | 2        | should                | should                 | should not           | should                |
      # Example 4: On larger screens, the 01 and 03 menus are shown and the 02 menu is moved to the more menu due to its configuration. On smaller screens, the 02 menu is moved to the more menu due to its configuration, the 03 menu is shown as it should be kept out of the more menu due to its configuration and the 01 menu is moved to the more menu due to lack of space.
      | 0        | should                | should                 | 1        | should not            | should not             | 2        | should                | should                 | should               | should                |

  @javascript
  Scenario Outline: Smartmenu: Menus: Presentation - Display the menus inside and outside more menu (Looking at the more menu content in the main menu)
    # This scenario is split from the previous scenario outline as we can only go into a more menu if it exists.
    # It basically tests the same examples than the previous scenario and just verifies that the smart menus are there as soon as you click the "More" link
    When I log in as "admin"
    Given I create smart menu with a default item with the following fields to these values:
      | Title              | Quick links 01 |
      | Menu location(s)   | Main           |
      | Menu mode          | Submenu        |
      | More menu behavior | <menu1beh>     |
    And I create smart menu with a default item with the following fields to these values:
      | Title              | Quick links 02 |
      | Menu location(s)   | Main           |
      | Menu mode          | Submenu        |
      | More menu behavior | <menu2beh>     |
    And I create smart menu with a default item with the following fields to these values:
      | Title              | Quick links 03 |
      | Menu location(s)   | Main           |
      | Menu mode          | Submenu        |
      | More menu behavior | <menu3beh>     |
    # Set the frontpage title to better control the available space in the navbar
    And I am on site homepage
    And I click on "Settings" "link" in the ".secondary-navigation" "css_element"
    And I set the field "id_s__shortname" to "Boost Union Test"
    And I press "Save changes"
    # Hide the standard navigation items to better reproduce the "More" behaviour in the navbar
    And the following config values are set as admin:
      | config                     | value                             | plugin            |
      | hidenodesprimarynavigation | home,myhome,courses,siteadminnode | theme_boost_union |
    And I follow "Dashboard"
    # Make the screen smaller and test the "More" behaviour in the navbar
    # This pixel screen size is essentially the same than the 'tablet' size, but for some strange reason Behat on moodle-docker made the screen too small in this scenario
    And I change the viewport size to "768x1024"
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    Then I should see smart menu "Quick links 01" in location "Main"
    And I should see smart menu "Quick links 02" in location "Main"
    And I should see smart menu "Quick links 03" in location "Main"
    # Finally verify the order of the menu items (as they are may have been re-ordered due to their settings)
    And "Quick links 01" "text" should appear <menu1and2> "Quick links 02" "text"
    And "Quick links 02" "text" should appear <menu2and3> "Quick links 03" "text"
    And "Quick links 01" "text" should appear <menu1and3> "Quick links 03" "text"

    Examples:
      # Behaviour option IDs: 0 = Do not change anything, 1 = Force into more menu, 2 = Keep outside of more menu
      | menu1beh | menu2beh | menu3beh | menu1and2 | menu2and3 | menu1and3 |
      | 1        | 0        | 0        | after     | before    | after     |
      # This example should look like this:
      # | 0        | 0        | 2        | before    | after     | before    |
      # Unfortunately, due to MDL-81892, it looks like this and can't be corrected before MDL-81892 is fixed:
      | 0        | 0        | 2        | before    | after     | after    |
      # This example should look like this:
      # | 0        | 1        | 2        | before    | after     | before    |
      # Unfortunately, due to MDL-81892, it looks like this and can't be corrected before MDL-81892 is fixed:
      | 0        | 1        | 2        | before    | after     | before    |

  @javascript
  Scenario Outline: Smartmenu: Menus: Presentation - Display the menus inside and outside more menu (Looking at the visible menus in the menu bar)
    When I log in as "admin"
    Given I create smart menu with a default item with the following fields to these values:
      | Title              | Quick links reeeeeally overlong title 01 |
      | Menu location(s)   | Menu                                 |
      | Menu mode          | Submenu                              |
      | More menu behavior | <menu1beh>                           |
    And I create smart menu with a default item with the following fields to these values:
      | Title              | Quick links reeeeeally overlong title 02 |
      | Menu location(s)   | Menu                                 |
      | Menu mode          | Submenu                              |
      | More menu behavior | <menu2beh>                           |
    And I create smart menu with a default item with the following fields to these values:
      | Title              | Quick links reeeeeally overlong title 03 |
      | Menu location(s)   | Menu                                 |
      | Menu mode          | Submenu                              |
      | More menu behavior | <menu3beh>                           |
    # Set the frontpage title to better control the available space in the navbar
    And I am on site homepage
    And I click on "Settings" "link" in the ".secondary-navigation" "css_element"
    And I set the field "id_s__shortname" to "Boost Union Test"
    And I press "Save changes"
    # Hide the standard navigation items to better reproduce the "More" behaviour in the navbar
    And the following config values are set as admin:
      | config                     | value                             | plugin            |
      | hidenodesprimarynavigation | home,myhome,courses,siteadminnode | theme_boost_union |
    And I follow "Dashboard"
    # Make the screen really large to test the "More" behaviour without any screen real estate constraints
    And I change the viewport size to "large"
    Then I <menu1shouldornotlarge> see smart menu "Quick links reeeeeally overlong title 01" in location "Menu"
    And I <menu2shouldornotlarge> see smart menu "Quick links reeeeeally overlong title 02" in location "Menu"
    And I <menu3shouldornotlarge> see smart menu "Quick links reeeeeally overlong title 03" in location "Menu"
    And ".boost-union-menubar .dropdownmoremenu" "css_element" <moreshouldornotlarge> be visible
    # Make the screen smaller and test the "More" behaviour in the menu bar
    And I change the viewport size to "tablet"
    Then I <menu1shouldornottablet> see smart menu "Quick links reeeeeally overlong title 01" in location "Menu"
    And I <menu2shouldornottablet> see smart menu "Quick links reeeeeally overlong title 02" in location "Menu"
    And I <menu3shouldornottablet> see smart menu "Quick links reeeeeally overlong title 03" in location "Menu"
    And ".boost-union-menubar .dropdownmoremenu" "css_element" <moreshouldornottablet> be visible

    Examples:
      # Behaviour option IDs: 0 = Do not change anything, 1 = Force into more menu, 2 = Keep outside of more menu
      | menu1beh | menu1shouldornotlarge | menu1shouldornottablet | menu2beh | menu2shouldornotlarge | menu2shouldornottablet | menu3beh | menu3shouldornotlarge | menu3shouldornottablet | moreshouldornotlarge | moreshouldornottablet |
      # Example 1: On larger screens, all menus are shown. On smaller screens, the 01 and 02 menus are shown as they have enough space and the 03 menu is moved to the more menu due to lack of space.
      # Against this background, this example should look like this:
      # | 0        | should                | should                 | 0        | should                | should                 | 0        | should                | should not             | should not           | should                |
      # Unfortunately, due to MDL-81892, it looks like this and can't be corrected before MDL-81892 is fixed:
      | 0        | should                | should                 | 0        | should                | should not             | 0        | should                | should not             | should not           | should                |
      # Example 2: On larger screens, the 01 menu is moved to the more menu due to its configuration and the 02 and 03 menus are shown. On smaller screens, the same is the case.
      | 1        | should not            | should not             | 0        | should                | should                 | 0        | should                | should                 | should               | should                |
      # Example 3: On larger screens, all menus are shown. On smaller screens, the 01 menu is shown as it has enough space, the 03 menu is shown as it should be kept out of the more menu due to its configuration and the 02 menu is moved to the more menu due to lack of space.
      # Against this background, this example should look like this:
      # | 0        | should                | should                 | 0        | should                | should not             | 2        | should                | should                 | should not           | should                |
      # Unfortunately, due to MDL-81892, it looks like this and can't be corrected before MDL-81892 is fixed:
      | 0        | should                | should not             | 0        | should                | should not             | 2        | should                | should                 | should not           | should                |
      # Example 4: On larger screens, the 01 and 03 menus are shown and the 02 menu is moved to the more menu due to its configuration. On smaller screens, the 02 menu is moved to the more menu due to its configuration, the 03 menu is shown as it should be kept out of the more menu due to its configuration and the 01 menu is moved to the more menu due to lack of space.
      | 0        | should                | should                 | 1        | should not            | should not             | 2        | should                | should                 | should               | should                |

  @javascript
  Scenario Outline: Smartmenu: Menus: Presentation - Display the menus inside and outside more menu (Looking at the more menu content in the menu bar)
    # This scenario is split from the previous scenario outline as we can only go into a more menu if it exists.
    # It basically tests the same examples than the previous scenario and just verifies that the smart menus are there as soon as you click the "More" link
    When I log in as "admin"
    Given I create smart menu with a default item with the following fields to these values:
      | Title              | Quick links reeeeeally overlong title 01 |
      | Menu location(s)   | Menu                                 |
      | Menu mode          | Submenu                              |
      | More menu behavior | <menu1beh>                           |
    And I create smart menu with a default item with the following fields to these values:
      | Title              | Quick links reeeeeally overlong title 02 |
      | Menu location(s)   | Menu                                 |
      | Menu mode          | Submenu                              |
      | More menu behavior | <menu2beh>                           |
    And I create smart menu with a default item with the following fields to these values:
      | Title              | Quick links reeeeeally overlong title 03 |
      | Menu location(s)   | Menu                                 |
      | Menu mode          | Submenu                              |
      | More menu behavior | <menu3beh>                           |
    # Set the frontpage title to better control the available space in the navbar
    And I am on site homepage
    And I click on "Settings" "link" in the ".secondary-navigation" "css_element"
    And I set the field "id_s__shortname" to "Boost Union Test"
    And I press "Save changes"
    # Hide the standard navigation items to better reproduce the "More" behaviour in the navbar
    And the following config values are set as admin:
      | config                     | value                             | plugin            |
      | hidenodesprimarynavigation | home,myhome,courses,siteadminnode | theme_boost_union |
    And I follow "Dashboard"
    # Make the screen smaller and test the "More" behaviour in the navbar
    # This pixel screen size is essentially the same than the 'tablet' size, but for some strange reason Behat on moodle-docker made the screen too small in this scenario
    And I change the viewport size to "768x1024"
    And I click on "More" "link" in the ".boost-union-menubar" "css_element"
    Then I should see smart menu "Quick links reeeeeally overlong title 01" in location "Menu"
    And I should see smart menu "Quick links reeeeeally overlong title 02" in location "Menu"
    And I should see smart menu "Quick links reeeeeally overlong title 03" in location "Menu"
    # Finally verify the order of the menu items (as they are may have been re-ordered due to their settings)
    And "Quick links reeeeeally overlong title 01" "text" should appear <menu1and2> "Quick links reeeeeally overlong title 02" "text"
    And "Quick links reeeeeally overlong title 02" "text" should appear <menu2and3> "Quick links reeeeeally overlong title 03" "text"
    And "Quick links reeeeeally overlong title 01" "text" should appear <menu1and3> "Quick links reeeeeally overlong title 03" "text"

    Examples:
      # Behaviour option IDs: 0 = Do not change anything, 1 = Force into more menu, 2 = Keep outside of more menu
      | menu1beh | menu2beh | menu3beh | menu1and2 | menu2and3 | menu1and3 |
      | 1        | 0        | 0        | after     | before    | after     |
      | 0        | 0        | 2        | before    | after     | after     |
      | 0        | 1        | 2        | before    | after     | before    |

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Verify that the correct menu item is displayed as active when viewing the main menu item's page.
    Given I log in as "admin"
    And I create smart menu with the following fields to these values:
      | Title             | Quick links |
      | Menu location(s)  | Main        |
      | Menu mode         | Inline      |
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Test node                           |
      | Menu item type | Static                              |
      | URL            | /admin/tool/dataprivacy/summary.php |
      | CSS class      | testnode01                          |
    When I am on site homepage
    Then the "class" attribute of ".primary-navigation [data-key='home'] a" "css_element" should contain "active"
    And the "class" attribute of ".primary-navigation .testnode01 a" "css_element" should not contain "active"
    And "//a[@aria-current = 'true']" "xpath" should exist in the ".primary-navigation [data-key='home']" "css_element"
    And "//a[@aria-current = 'true']" "xpath" should not exist in the ".primary-navigation .testnode01" "css_element"
    And I click on "Test node" "link" in the ".primary-navigation" "css_element"
    Then the "class" attribute of ".primary-navigation [data-key='home'] a" "css_element" should not contain "active"
    And the "class" attribute of ".primary-navigation .testnode01 a" "css_element" should contain "active"
    And "//a[@aria-current = 'true']" "xpath" should not exist in the ".primary-navigation [data-key='home']" "css_element"
    And "//a[@aria-current = 'true']" "xpath" should exist in the ".primary-navigation .testnode01" "css_element"

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Verify that the correct menu item is displayed as active when viewing the submenu item's page.
    Given I log in as "admin"
    And I create smart menu with the following fields to these values:
      | Title             | Quick links |
      | Menu location(s)  | Main        |
      | Menu mode         | Submenu     |
      | CSS class         | testnode01  |
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Test node                           |
      | Menu item type | Static                              |
      | URL            | /admin/tool/dataprivacy/summary.php |
    When I am on site homepage
    Then the "class" attribute of ".primary-navigation [data-key='home'] a" "css_element" should contain "active"
    And the "class" attribute of ".primary-navigation .testnode01 a" "css_element" should not contain "active"
    And "//a[@aria-current = 'true']" "xpath" should exist in the ".primary-navigation [data-key='home']" "css_element"
    And "//a[@aria-current = 'true']" "xpath" should not exist in the ".primary-navigation .testnode01" "css_element"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And I click on "Test node" "link" in the ".primary-navigation" "css_element"
    Then the "class" attribute of ".primary-navigation [data-key='home'] a" "css_element" should not contain "active"
    And the "class" attribute of ".primary-navigation .testnode01 a" "css_element" should contain "active"
    And "//a[@aria-current = 'true']" "xpath" should not exist in the ".primary-navigation [data-key='home']" "css_element"
    And "//a[@aria-current = 'true']" "xpath" should exist in the ".primary-navigation .testnode01" "css_element"

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Verify that the correct _custom_ menu item is displayed as active when viewing the custom menu item's page (Moodle core behaviour which must not be broken by the smart menus)
    Given I log in as "admin"
    And I navigate to "Appearance > Advanced theme settings" in site administration
    And I set the field "Custom menu items" to multiline:
    """
    Test node|/admin/tool/dataprivacy/summary.php
    """
    And I click on "Save changes" "button"
    When I am on site homepage
    Then the "class" attribute of ".primary-navigation [data-key='home'] a" "css_element" should contain "active"
    And the "class" attribute of ".primary-navigation .nav-item:nth-child(5) a" "css_element" should not contain "active"
    And "//a[@aria-current = 'true']" "xpath" should exist in the ".primary-navigation [data-key='home']" "css_element"
    And "//a[@aria-current = 'true']" "xpath" should not exist in the ".primary-navigation .nav-item:nth-child(5)" "css_element"
    And I click on "Test node" "link" in the ".primary-navigation" "css_element"
    Then the "class" attribute of ".primary-navigation [data-key='home'] a" "css_element" should not contain "active"
    And the "class" attribute of ".primary-navigation .nav-item:nth-child(5) a" "css_element" should contain "active"
    And "//a[@aria-current = 'true']" "xpath" should not exist in the ".primary-navigation [data-key='home']" "css_element"
    And "//a[@aria-current = 'true']" "xpath" should exist in the ".primary-navigation .nav-item:nth-child(5)" "css_element"
