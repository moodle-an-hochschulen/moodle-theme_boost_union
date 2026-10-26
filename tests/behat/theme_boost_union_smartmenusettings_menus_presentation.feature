@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menus @theme_boost_union_smartmenusettings_menus_presentation
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, applying different presentation options to the individual smart menus
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    # For this feature, we would not really need the home and my courses nodes. However, we still enable them to keep the feature
    # and the steps with the 'More' menu in particular consistent with previous Boost Union releases.
    Given the following config values are set as admin:
      | config          | value |
      | enablemyhome    | 1     |
      | enablemycourses | 1     |
    And the following "users" exist:
      | username |
      | user1    |

  Scenario Outline: Smartmenu: Menus: Presentation - Display smart menu description in different places
    Given the following "theme_boost_union > smart menu" exists:
      | title           | Useful Resources                  |
      | location        | Main navigation, Menu bar         |
      | description     | List of useful external resources |
      | showdescription | <showdescription>                 |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Useful Resources |
      | title    | Info             |
      | itemtype | Heading          |
    When I log in as "admin"
    And I click on "Useful Resources" "link" in the ".primary-navigation" "css_element"
    Then "i.fa-circle-question" "css_element" <iconshould> exist in the ".primary-navigation" "css_element"
    And "List of useful external resources" "text" <descshould> exist in the ".primary-navigation" "css_element"
    And <specificcheck>

    Examples:
      | showdescription | iconshould | descshould | specificcheck                                                                                    |
      | Below           | should not | should     | "List of useful external resources" "text" should appear after "Info" "link"                     |
      | Above           | should not | should     | "List of useful external resources" "text" should appear before "Info" "link"                    |
      | Help            | should     | should not | "i.fa-circle-question" "css_element" should appear before "Info" "link"                          |
      # For this example, we duplicate a step into the specificcheck as we need to fill it but there isn't anything to check anymore
      | Never           | should not | should not | "i.fa-circle-question" "css_element" should not exist in the ".primary-navigation" "css_element" |

  @javascript
  Scenario Outline: Smartmenu: Menus: Presentation - Show the smart menu description as help icon and keep the menu open when the help icon is clicked (with JavaScript)
    Given the following "theme_boost_union > smart menu" exists:
      | title           | Useful Resources                  |
      | location        | Main navigation, Menu bar         |
      | description     | List of useful external resources |
      | showdescription | Help                              |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Useful Resources |
      | title    | Info             |
      | itemtype | Heading          |
    When I log in as "admin"
    And I click on "Useful Resources" "link" in the "<container>" "css_element"
    And I click on ".menu-helpicon .help-icon" "css_element" in the "<container>" "css_element"
    Then I should see "List of useful external resources" in the "body > .popover" "css_element"
    And I should see "Info" in the "<container>" "css_element"

    Examples:
      | container            |
      | .primary-navigation  |
      | .boost-union-menubar |

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Show the smart menu description as help icon and keep the user menu open when the help icon is clicked (with JavaScript)
    Given the following "theme_boost_union > smart menu" exists:
      | title           | Useful Resources                  |
      | location        | User menu                         |
      | description     | List of useful external resources |
      | showdescription | Help                              |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Useful Resources |
      | title    | Info             |
      | itemtype | Heading          |
    When I log in as "admin"
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Useful Resources" "link" in the "#usermenu-carousel" "css_element"
    And I click on ".menu-helpicon .help-icon" "css_element" in the "#usermenu-carousel .carousel-item.active" "css_element"
    Then I should see "List of useful external resources" in the "body > .popover" "css_element"
    And I should see "Info" in the "#usermenu-carousel .carousel-item.active" "css_element"

  Scenario: Smartmenu: Menus: Presentation - Include the custom css class to a smart menu
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Quick links                                      |
      | location | Main navigation, Menu bar, User menu             |
      | cssclass | quick-links-menu                                 |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links       |
      | title    | Resources         |
      | itemtype | Static            |
      | url      | http://moodle.org |
    When I log in as "admin"
    # Note: We use XPath selectors here instead of the named selectors because:
    # - The "Main menu smart menu" named selector points to the dropdown menu (div[@role='menu']), not the parent <li> element
    # - The CSS class is applied to the <li> element for Main/Menu bar locations
    # - The CSS class is applied to the <a> element for User menu location
    # - XPath allows us to precisely target the correct element in each location using parent::li or the link itself
    Then the "class" attribute of "//div[contains(@class, 'primary-navigation')]//a[contains(normalize-space(.), 'Quick links')]/parent::li" "xpath_element" should contain "quick-links-menu"
    And the "class" attribute of "//div[@id='usermenu-carousel']//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Quick links')]" "xpath_element" should contain "quick-links-menu"
    And the "class" attribute of "//nav[contains(@class, 'boost-union-menubar')]//a[contains(normalize-space(.), 'Quick links')]/parent::li" "xpath_element" should contain "quick-links-menu"

  Scenario: Smartmenu: Menus: Presentation - Add a smart menu with multilang tags
    Given the following "language packs" exist:
      | language |
      | de       |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
    And the following "theme_boost_union > smart menu" exists:
      | title    | <span lang="en" class="multilang">Lorem ipsum</span><span lang="de" class="multilang">Dolor sit amet</span> |
      | location | Main navigation, Menu bar, User menu                                                                        |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | <span lang="en" class="multilang">Lorem ipsum</span><span lang="de" class="multilang">Dolor sit amet</span> |
      | title    | Multilang          |
      | itemtype | Static             |
      | url      | https://moodle.org |
    When I log in as "admin"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "English ‎(en)‎"
    And I press "Save changes"
    And I am on homepage
    Then I should see smart menu "Lorem ipsum" in location "Main, Menu, User"
    And I should not see smart menu "Dolor sit amet" in location "Main, Menu, User"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "Deutsch ‎(de)‎"
    And I press "Save changes"
    And I am on homepage
    Then I should see smart menu "Dolor sit amet" in location "Main, Menu, User"
    And I should not see smart menu "Lorem ipsum" in location "Main, Menu, User"

  @javascript
  Scenario Outline: Smartmenu: Menus: Presentation - Display the menus inside and outside more menu (Looking at the visible menus in the main menu)
    Given the following "theme_boost_union > smart menu" exists:
      | title            | Quick links reeeeeally overlong title 01  |
      | location         | Main navigation |
      | mode             | Submenu         |
      | moremenubehavior | <menu1beh>      |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links reeeeeally overlong title 01 |
      | title    | Info           |
      | itemtype | Heading        |
    And the following "theme_boost_union > smart menu" exists:
      | title            | Quick links reeeeeally overlong title 02  |
      | location         | Main navigation |
      | mode             | Submenu         |
      | moremenubehavior | <menu2beh>      |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links reeeeeally overlong title 02 |
      | title    | Info           |
      | itemtype | Heading        |
    And the following "theme_boost_union > smart menu" exists:
      | title            | Quick links reeeeeally overlong title 03  |
      | location         | Main navigation |
      | mode             | Submenu         |
      | moremenubehavior | <menu3beh>      |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links reeeeeally overlong title 03 |
      | title    | Info           |
      | itemtype | Heading        |
    When I log in as "admin"
    # Make the screen really large so that the site administration node is not collapsed into the "More" menu by the overlong smart menus.
    And I change viewport size to "large"
    # Set the frontpage title to better control the available space in the navbar
    And I navigate to "Site home > Site home settings" in site administration
    And I set the field "id_s__shortname" to "Boost Union Test"
    And I press "Save changes"
    # Hide the standard navigation items to better reproduce the "More" behaviour in the navbar
    And the following config values are set as admin:
      | config                     | value                             | plugin            |
      | hidenodesprimarynavigation | home,myhome,courses,siteadminnode | theme_boost_union |
    And I follow "Dashboard"
    # Make the screen really large to test the "More" behaviour without any screen real estate constraints
    And I change viewport size to "large"
    Then I <menu1outsideshouldornotlarge> see smart menu "Quick links reeeeeally overlong title 01" outside more menu in location "Main"
    And I <menu1insideshouldornotlarge> see smart menu "Quick links reeeeeally overlong title 01" inside more menu in location "Main"
    And I <menu2outsideshouldornotlarge> see smart menu "Quick links reeeeeally overlong title 02" outside more menu in location "Main"
    And I <menu2insideshouldornotlarge> see smart menu "Quick links reeeeeally overlong title 02" inside more menu in location "Main"
    And I <menu3outsideshouldornotlarge> see smart menu "Quick links reeeeeally overlong title 03" outside more menu in location "Main"
    And I <menu3insideshouldornotlarge> see smart menu "Quick links reeeeeally overlong title 03" inside more menu in location "Main"
    And ".primary-navigation .dropdownmoremenu" "css_element" <moreshouldornotlarge> be visible
    # Make the screen smaller and test the "More" behaviour in the navbar
    # The screen size is chosen such that two (overlong) menus and the "More" menu fit into the navbar, but three menus don't.
    And I change viewport size to "1440x1024"
    Then I <menu1outsideshouldornottablet> see smart menu "Quick links reeeeeally overlong title 01" outside more menu in location "Main"
    And I <menu1insideshouldornottablet> see smart menu "Quick links reeeeeally overlong title 01" inside more menu in location "Main"
    And I <menu2outsideshouldornottablet> see smart menu "Quick links reeeeeally overlong title 02" outside more menu in location "Main"
    And I <menu2insideshouldornottablet> see smart menu "Quick links reeeeeally overlong title 02" inside more menu in location "Main"
    And I <menu3outsideshouldornottablet> see smart menu "Quick links reeeeeally overlong title 03" outside more menu in location "Main"
    And I <menu3insideshouldornottablet> see smart menu "Quick links reeeeeally overlong title 03" inside more menu in location "Main"
    And ".primary-navigation .dropdownmoremenu" "css_element" <moreshouldornottablet> be visible

    Examples:
      | menu1beh               | menu1outsideshouldornotlarge | menu1insideshouldornotlarge | menu1outsideshouldornottablet | menu1insideshouldornottablet | menu2beh               | menu2outsideshouldornotlarge | menu2insideshouldornotlarge | menu2outsideshouldornottablet | menu2insideshouldornottablet | menu3beh                  | menu3outsideshouldornotlarge | menu3insideshouldornotlarge | menu3outsideshouldornottablet | menu3insideshouldornottablet | moreshouldornotlarge | moreshouldornottablet |
      # Example 1: On larger screens, all menus are shown. On smaller screens, the 01 and 02 menus are shown as they have enough space and the 03 menu is moved to the more menu due to lack of space.
      | Do not change anything | should                       | should not                  | should                        | should not                   | Do not change anything | should                       | should not                  | should                        | should not                   | Do not change anything    | should                       | should not                  | should not                    | should                       | should not           | should                |
      # Example 2: On larger screens, the 01 menu is moved to the more menu due to its configuration and the 02 and 03 menus are shown. On smaller screens, the same is the case.
      | Force into more menu   | should not                   | should                      | should not                    | should                       | Do not change anything | should                       | should not                  | should                        | should not                   | Do not change anything    | should                       | should not                  | should                        | should not                   | should               | should                |
      # Example 3: On larger screens, all menus are shown. On smaller screens, the 01 menu is shown as it has enough space, the 03 menu is shown as it should be kept out of the more menu due to its configuration and the 02 menu is moved to the more menu due to lack of space.
      | Do not change anything | should                       | should not                  | should                        | should not                   | Do not change anything | should                       | should not                  | should not                    | should                       | Keep outside of more menu | should                       | should not                  | should                        | should not                   | should not           | should                |
      # Example 4: On larger screens, the 01 and 03 menus are shown and the 02 menu is moved to the more menu due to its configuration. On smaller screens, the 02 menu is moved to the more menu due to its configuration, the 03 menu is shown as it should be kept out of the more menu due to its configuration and the 01 menu is moved to the more menu due to lack of space.
      | Do not change anything | should                       | should not                  | should                        | should not                   | Force into more menu   | should not                   | should                      | should not                    | should                       | Keep outside of more menu | should                       | should not                  | should                        | should not                   | should               | should                |

  @javascript
  Scenario Outline: Smartmenu: Menus: Presentation - Display the menus inside and outside more menu (Looking at the more menu content in the main menu)
    # This scenario is split from the previous scenario outline as we can only go into a more menu if it exists.
    # It basically tests the same examples than the previous scenario and just verifies that the smart menus are there as soon as you click the "More" link.
    Given the following "theme_boost_union > smart menu" exists:
      | title            | Quick links reeeeeally overlong title 01  |
      | location         | Main navigation |
      | mode             | Submenu         |
      | moremenubehavior | <menu1beh>      |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links reeeeeally overlong title 01 |
      | title    | Info           |
      | itemtype | Heading        |
    And the following "theme_boost_union > smart menu" exists:
      | title            | Quick links reeeeeally overlong title 02  |
      | location         | Main navigation |
      | mode             | Submenu         |
      | moremenubehavior | <menu2beh>      |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links reeeeeally overlong title 02 |
      | title    | Info           |
      | itemtype | Heading        |
    And the following "theme_boost_union > smart menu" exists:
      | title            | Quick links reeeeeally overlong title 03  |
      | location         | Main navigation |
      | mode             | Submenu         |
      | moremenubehavior | <menu3beh>      |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links reeeeeally overlong title 03 |
      | title    | Info           |
      | itemtype | Heading        |
    When I log in as "admin"
    # Make the screen really large so that the site administration node is not collapsed into the "More" menu by the overlong smart menus.
    And I change viewport size to "large"
    # Set the frontpage title to better control the available space in the navbar
    And I navigate to "Site home > Site home settings" in site administration
    And I set the field "id_s__shortname" to "Boost Union Test"
    And I press "Save changes"
    # Hide the standard navigation items to better reproduce the "More" behaviour in the navbar
    And the following config values are set as admin:
      | config                     | value                             | plugin            |
      | hidenodesprimarynavigation | home,myhome,courses,siteadminnode | theme_boost_union |
    And I follow "Dashboard"
    # Make the screen smaller to test the "More" behaviour in the navbar
    # The screen size is chosen such that two (overlong) menus and the "More" menu fit into the navbar, but three menus don't.
    And I change viewport size to "1440x1024"
    # Verify the order of the menu items (as they may have been re-ordered due to their settings).
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    And "Quick links reeeeeally overlong title 01" "text" should appear <orderrelation1and2> "Quick links reeeeeally overlong title 02" "text"
    And "Quick links reeeeeally overlong title 02" "text" should appear <orderrelation2and3> "Quick links reeeeeally overlong title 03" "text"
    And "Quick links reeeeeally overlong title 01" "text" should appear <orderrelation1and3> "Quick links reeeeeally overlong title 03" "text"

    Examples:
      | menu1beh             | menu2beh               | menu3beh                  | orderrelation1and2 | orderrelation2and3 | orderrelation1and3 |
      | Force into more menu | Do not change anything | Do not change anything    | after              | before             | after              |
      | Do not change anything | Do not change anything | Keep outside of more menu | before             | after              | before             |
      | Do not change anything | Force into more menu   | Keep outside of more menu | before             | after              | before             |

  @javascript
  Scenario Outline: Smartmenu: Menus: Presentation - Display the menus inside and outside more menu (Looking at the visible menus in the menu bar)
    Given the following "theme_boost_union > smart menu" exists:
      | title            | Quick links reeeeeally overlong title 01 |
      | location         | Menu bar                                 |
      | mode             | Submenu                                  |
      | moremenubehavior | <menu1beh>                               |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links reeeeeally overlong title 01 |
      | title    | Info                                     |
      | itemtype | Heading                                  |
    And the following "theme_boost_union > smart menu" exists:
      | title            | Quick links reeeeeally overlong title 02 |
      | location         | Menu bar                                 |
      | mode             | Submenu                                  |
      | moremenubehavior | <menu2beh>                               |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links reeeeeally overlong title 02 |
      | title    | Info                                     |
      | itemtype | Heading                                  |
    And the following "theme_boost_union > smart menu" exists:
      | title            | Quick links reeeeeally overlong title 03 |
      | location         | Menu bar                                 |
      | mode             | Submenu                                  |
      | moremenubehavior | <menu3beh>                               |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links reeeeeally overlong title 03 |
      | title    | Info                                     |
      | itemtype | Heading                                  |
    When I log in as "admin"
    # Set the frontpage title to better control the available space in the navbar
    And I navigate to "Site home > Site home settings" in site administration
    And I set the field "id_s__shortname" to "Boost Union Test"
    And I press "Save changes"
    # Hide the standard navigation items to better reproduce the "More" behaviour in the navbar
    And the following config values are set as admin:
      | config                     | value                             | plugin            |
      | hidenodesprimarynavigation | home,myhome,courses,siteadminnode | theme_boost_union |
    And I follow "Dashboard"
    # Make the screen really large to test the "More" behaviour without any screen real estate constraints
    And I change viewport size to "large"
    Then I <menu1outsideshouldornotlarge> see smart menu "Quick links reeeeeally overlong title 01" outside more menu in location "Menu"
    And I <menu1insideshouldornotlarge> see smart menu "Quick links reeeeeally overlong title 01" inside more menu in location "Menu"
    And I <menu2outsideshouldornotlarge> see smart menu "Quick links reeeeeally overlong title 02" outside more menu in location "Menu"
    And I <menu2insideshouldornotlarge> see smart menu "Quick links reeeeeally overlong title 02" inside more menu in location "Menu"
    And I <menu3outsideshouldornotlarge> see smart menu "Quick links reeeeeally overlong title 03" outside more menu in location "Menu"
    And I <menu3insideshouldornotlarge> see smart menu "Quick links reeeeeally overlong title 03" inside more menu in location "Menu"
    And ".boost-union-menubar .dropdownmoremenu" "css_element" <moreshouldornotlarge> be visible
    # Make the screen smaller and test the "More" behaviour in the menu bar
    And I change viewport size to "tablet"
    Then I <menu1outsideshouldornottablet> see smart menu "Quick links reeeeeally overlong title 01" outside more menu in location "Menu"
    And I <menu1insideshouldornottablet> see smart menu "Quick links reeeeeally overlong title 01" inside more menu in location "Menu"
    And I <menu2outsideshouldornottablet> see smart menu "Quick links reeeeeally overlong title 02" outside more menu in location "Menu"
    And I <menu2insideshouldornottablet> see smart menu "Quick links reeeeeally overlong title 02" inside more menu in location "Menu"
    And I <menu3outsideshouldornottablet> see smart menu "Quick links reeeeeally overlong title 03" outside more menu in location "Menu"
    And I <menu3insideshouldornottablet> see smart menu "Quick links reeeeeally overlong title 03" inside more menu in location "Menu"
    And ".boost-union-menubar .dropdownmoremenu" "css_element" <moreshouldornottablet> be visible

    Examples:
      | menu1beh               | menu1outsideshouldornotlarge | menu1insideshouldornotlarge | menu1outsideshouldornottablet | menu1insideshouldornottablet | menu2beh               | menu2outsideshouldornotlarge | menu2insideshouldornotlarge | menu2outsideshouldornottablet | menu2insideshouldornottablet | menu3beh                  | menu3outsideshouldornotlarge | menu3insideshouldornotlarge | menu3outsideshouldornottablet | menu3insideshouldornottablet | moreshouldornotlarge | moreshouldornottablet |
      # Example 1: On larger screens, all menus are shown. On smaller screens, the 01 and 02 menus are shown as they have enough space and the 03 menu is moved to the more menu due to lack of space.
      | Do not change anything | should                       | should not                  | should                        | should not                   | Do not change anything | should                       | should not                  | should                        | should not                   | Do not change anything    | should                       | should not                  | should not                    | should                       | should not           | should                |
      # Example 2: On larger screens, the 01 menu is moved to the more menu due to its configuration and the 02 and 03 menus are shown. On smaller screens, the same is the case.
      | Force into more menu   | should not                   | should                      | should not                    | should                       | Do not change anything | should                       | should not                  | should                        | should not                   | Do not change anything    | should                       | should not                  | should                        | should not                   | should               | should                |
      # Example 3: On larger screens, all menus are shown. On smaller screens, the 01 menu is shown as it has enough space, the 03 menu is shown as it should be kept out of the more menu due to its configuration and the 02 menu is moved to the more menu due to lack of space.
      | Do not change anything | should                       | should not                  | should                        | should not                   | Do not change anything | should                       | should not                  | should not                    | should                       | Keep outside of more menu | should                       | should not                  | should                        | should not                   | should not           | should                |
      # Example 4: On larger screens, the 01 and 03 menus are shown and the 02 menu is moved to the more menu due to its configuration. On smaller screens, the 02 menu is moved to the more menu due to its configuration, the 03 menu is shown as it should be kept out of the more menu due to its configuration and the 01 menu is moved to the more menu due to lack of space.
      | Do not change anything | should                       | should not                  | should                        | should not                   | Force into more menu   | should not                   | should                      | should not                    | should                       | Keep outside of more menu | should                       | should not                  | should                        | should not                   | should               | should                |

  @javascript
  Scenario Outline: Smartmenu: Menus: Presentation - Display the menus inside and outside more menu (Looking at the more menu content in the menu bar)
    # This scenario is split from the previous scenario outline as we can only go into a more menu if it exists.
    # It basically tests the same examples than the previous scenario and just verifies that the smart menus are there as soon as you click the "More" link.
    Given the following "theme_boost_union > smart menu" exists:
      | title            | Quick links reeeeeally overlong title 01 |
      | location         | Menu bar                                 |
      | mode             | Submenu                                  |
      | moremenubehavior | <menu1beh>                               |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links reeeeeally overlong title 01 |
      | title    | Info                                     |
      | itemtype | Heading                                  |
    And the following "theme_boost_union > smart menu" exists:
      | title            | Quick links reeeeeally overlong title 02 |
      | location         | Menu bar                                 |
      | mode             | Submenu                                  |
      | moremenubehavior | <menu2beh>                               |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links reeeeeally overlong title 02 |
      | title    | Info                                     |
      | itemtype | Heading                                  |
    And the following "theme_boost_union > smart menu" exists:
      | title            | Quick links reeeeeally overlong title 03 |
      | location         | Menu bar                                 |
      | mode             | Submenu                                  |
      | moremenubehavior | <menu3beh>                               |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links reeeeeally overlong title 03 |
      | title    | Info                                     |
      | itemtype | Heading                                  |
    When I log in as "admin"
    # Set the frontpage title to better control the available space in the navbar
    And I navigate to "Site home > Site home settings" in site administration
    And I set the field "id_s__shortname" to "Boost Union Test"
    And I press "Save changes"
    # Hide the standard navigation items to better reproduce the "More" behaviour in the navbar
    And the following config values are set as admin:
      | config                     | value                             | plugin            |
      | hidenodesprimarynavigation | home,myhome,courses,siteadminnode | theme_boost_union |
    And I follow "Dashboard"
    # Make the screen smaller to test the "More" behaviour in the menu bar
    And I change viewport size to "tablet"
    # Verify the order of the menu items (as they may have been re-ordered due to their settings).
    And I click on "More" "link" in the ".boost-union-menubar" "css_element"
    And "Quick links reeeeeally overlong title 01" "text" should appear <orderrelation1and2> "Quick links reeeeeally overlong title 02" "text"
    And "Quick links reeeeeally overlong title 02" "text" should appear <orderrelation2and3> "Quick links reeeeeally overlong title 03" "text"
    And "Quick links reeeeeally overlong title 01" "text" should appear <orderrelation1and3> "Quick links reeeeeally overlong title 03" "text"

    Examples:
      | menu1beh             | menu2beh               | menu3beh                  | orderrelation1and2 | orderrelation2and3 | orderrelation1and3 |
      | Force into more menu | Do not change anything | Do not change anything    | after              | before             | after              |
      | Do not change anything | Do not change anything | Keep outside of more menu | before             | after              | before             |
      | Do not change anything | Force into more menu   | Keep outside of more menu | before             | after              | before             |

  Scenario: Smartmenu: Menus: Presentation - Verify that the correct menu item is displayed as active when viewing the main menu item's page.
    Given the following config values are set as admin:
      | config       | value |
      | enablemyhome | 1     |
    And the following "theme_boost_union > smart menu" exists:
      | title    | Quick links     |
      | location | Main navigation |
      | mode     | Inline          |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links                         |
      | title    | Test node                           |
      | itemtype | Static                              |
      | url      | /admin/tool/dataprivacy/summary.php |
      | cssclass | testnode01                          |
    When I log in as "admin"
    And I am on site homepage
    Then the "class" attribute of ".primary-navigation [data-key='home'] a" "css_element" should contain "active"
    And the "class" attribute of ".primary-navigation .testnode01 a" "css_element" should not contain "active"
    And "//a[@aria-current = 'true']" "xpath" should exist in the ".primary-navigation [data-key='home']" "css_element"
    And "//a[@aria-current = 'true']" "xpath" should not exist in the ".primary-navigation .testnode01" "css_element"
    And I click on "Test node" "link" in the ".primary-navigation" "css_element"
    Then the "class" attribute of ".primary-navigation [data-key='home'] a" "css_element" should not contain "active"
    And the "class" attribute of ".primary-navigation .testnode01 a" "css_element" should contain "active"
    And "//a[@aria-current = 'true']" "xpath" should not exist in the ".primary-navigation [data-key='home']" "css_element"
    And "//a[@aria-current = 'true']" "xpath" should exist in the ".primary-navigation .testnode01" "css_element"

  Scenario: Smartmenu: Menus: Presentation - Verify that the correct menu item is displayed as active when viewing the submenu item's page.
    Given the following config values are set as admin:
      | config       | value |
      | enablemyhome | 1     |
    And the following "theme_boost_union > smart menu" exists:
      | title    | Quick links     |
      | location | Main navigation |
      | mode     | Submenu         |
      | cssclass | testnode01      |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links                         |
      | title    | Test node                           |
      | itemtype | Static                              |
      | url      | /admin/tool/dataprivacy/summary.php |
    When I log in as "admin"
    And I am on site homepage
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

  Scenario: Smartmenu: Menus: Presentation - Verify that the correct _custom_ menu item is displayed as active when viewing the custom menu item's page (Moodle core behaviour which must not be broken by the smart menus)
    Given the following config values are set as admin:
      | config          | value |
      | enablemyhome    | 1     |
      | enablemycourses | 1     |
    And I log in as "admin"
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

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Verify that the correct menu item is displayed as active when viewing the main menu item's page (with JavaScript)
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Quick links               |
      | location | Main navigation, Menu bar |
      | mode     | Inline                    |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links                         |
      | title    | Test node                           |
      | itemtype | Static                              |
      | url      | /admin/tool/dataprivacy/summary.php |
    When I log in as "admin"
    And I am on site homepage
    Then "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Home']" "xpath_element" should exist in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Test node']" "xpath_element" should not exist in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Test node']" "xpath_element" should not exist in the ".boost-union-menubar" "css_element"
    And I click on "Test node" "link" in the ".primary-navigation" "css_element"
    Then "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Home']" "xpath_element" should not exist in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'mds-nav-pill--selected') and @aria-current = 'page' and normalize-space() = 'Test node']" "xpath_element" should exist in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'mds-nav-pill--selected') and @aria-current = 'page' and normalize-space() = 'Test node']" "xpath_element" should exist in the ".boost-union-menubar" "css_element"

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Verify that the correct menu item is displayed as active when viewing the submenu item's page (with JavaScript)
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Quick links                          |
      | location | Main navigation, Menu bar, User menu |
      | mode     | Submenu                              |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links                         |
      | title    | Test node                           |
      | itemtype | Static                              |
      | url      | /admin/tool/dataprivacy/summary.php |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links       |
      | title    | Other node        |
      | itemtype | Static            |
      | url      | /user/profile.php |
    When I log in as "admin"
    And I am on site homepage
    # On the site homepage, the "Home" node is active and the smart menu is not.
    Then "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Home']" "xpath_element" should exist in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Quick links']" "xpath_element" should not exist in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Quick links']" "xpath_element" should not exist in the ".boost-union-menubar" "css_element"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'dropdown-item') and @aria-current = 'page' and normalize-space() = 'Test node']" "xpath_element" should not exist in the ".primary-navigation" "css_element"
    # Open the smart menu item's page.
    And I click on "Test node" "link" in the ".primary-navigation" "css_element"
    # In the main navigation, the smart menu and the smart menu item are active now, but the "Home" node is not anymore.
    Then "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Home']" "xpath_element" should not exist in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Quick links']" "xpath_element" should exist in the ".primary-navigation" "css_element"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'dropdown-item') and contains(@class, 'active') and @aria-current = 'page' and normalize-space() = 'Test node']" "xpath_element" should exist in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'dropdown-item') and contains(@class, 'active') and @aria-current = 'page' and normalize-space() = 'Other node']" "xpath_element" should not exist in the ".primary-navigation" "css_element"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    # In the menu bar, the smart menu and the smart menu item are active as well.
    And "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Quick links']" "xpath_element" should exist in the ".boost-union-menubar" "css_element"
    And I click on "Quick links" "link" in the ".boost-union-menubar" "css_element"
    And "//a[contains(@class, 'dropdown-item') and contains(@class, 'active') and @aria-current = 'page' and normalize-space() = 'Test node']" "xpath_element" should exist in the ".boost-union-menubar" "css_element"
    And "//a[contains(@class, 'dropdown-item') and contains(@class, 'active') and @aria-current = 'page' and normalize-space() = 'Other node']" "xpath_element" should not exist in the ".boost-union-menubar" "css_element"
    And I click on "Quick links" "link" in the ".boost-union-menubar" "css_element"
    # In the user menu, the smart menu item is active as well.
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Quick links" "link" in the "#usermenu-carousel" "css_element"
    And "//a[contains(@class, 'dropdown-item') and @aria-current = 'true' and normalize-space() = 'Test node']" "xpath_element" should exist in the "#usermenu-carousel" "css_element"
    And "//a[contains(@class, 'dropdown-item') and @aria-current = 'true' and normalize-space() = 'Other node']" "xpath_element" should not exist in the "#usermenu-carousel" "css_element"

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Verify that the correct menu item is displayed as active when viewing the page of a submenu item on the third level of a menu inside the more menu (with JavaScript)
    # The "Quick links" menu is in submenu mode and forced into the "More" menu where it is shown as nested menu.
    # Forcing it into the "More" menu is not a requirement of the tested behaviour, it just makes sure that the menu is
    # shown as nested menu in the "More" menu regardless of the browser window width.
    # The courses of the "Course list" item are shown on the third level of the menu.
    Given the following "courses" exist:
      | fullname      | shortname |
      | Test course 1 | C1        |
      | Test course 2 | C2        |
    And the following "theme_boost_union > smart menu" exists:
      | title            | Quick links                          |
      | location         | Main navigation, Menu bar, User menu |
      | mode             | Submenu                              |
      | moremenubehavior | Force into more menu                 |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links     |
      | title    | Course list     |
      | itemtype | Dynamic courses |
      | itemmode | Submenu         |
    When I log in as "admin"
    And I am on "Test course 1" course homepage
    # In the main navigation, the "More" menu is active, but the toggle of the nested menu is not marked as active.
    Then "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'More']" "xpath_element" should exist in the ".primary-navigation" "css_element"
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'dropdown-toggle') and normalize-space() = 'Quick links']" "xpath_element" should exist in the ".primary-navigation [data-region='moredropdown']" "css_element"
    And "//a[contains(@class, 'dropdown-toggle') and (contains(@class, 'active') or @aria-current) and normalize-space() = 'Quick links']" "xpath_element" should not exist in the ".primary-navigation [data-region='moredropdown']" "css_element"
    # The course on the third level is marked as active instead.
    And I click on "Quick links" "link" in the ".primary-navigation [data-region='moredropdown']" "css_element"
    And I click on "Course list" "link" in the ".primary-navigation [data-region='moredropdown']" "css_element"
    And "//a[contains(@class, 'dropdown-item') and contains(@class, 'active') and @aria-current = 'page' and normalize-space() = 'Test course 1']" "xpath_element" should exist in the ".primary-navigation [data-region='moredropdown']" "css_element"
    And "//a[contains(@class, 'dropdown-item') and contains(@class, 'active') and @aria-current = 'page' and normalize-space() = 'Test course 2']" "xpath_element" should not exist in the ".primary-navigation [data-region='moredropdown']" "css_element"
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    # In the menu bar, it is the same.
    And "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'More']" "xpath_element" should exist in the ".boost-union-menubar" "css_element"
    And I click on "More" "link" in the ".boost-union-menubar" "css_element"
    And "//a[contains(@class, 'dropdown-toggle') and normalize-space() = 'Quick links']" "xpath_element" should exist in the ".boost-union-menubar [data-region='moredropdown']" "css_element"
    And "//a[contains(@class, 'dropdown-toggle') and (contains(@class, 'active') or @aria-current) and normalize-space() = 'Quick links']" "xpath_element" should not exist in the ".boost-union-menubar [data-region='moredropdown']" "css_element"
    And I click on "Quick links" "link" in the ".boost-union-menubar [data-region='moredropdown']" "css_element"
    And I click on "Course list" "link" in the ".boost-union-menubar [data-region='moredropdown']" "css_element"
    And "//a[contains(@class, 'dropdown-item') and contains(@class, 'active') and @aria-current = 'page' and normalize-space() = 'Test course 1']" "xpath_element" should exist in the ".boost-union-menubar [data-region='moredropdown']" "css_element"
    And "//a[contains(@class, 'dropdown-item') and contains(@class, 'active') and @aria-current = 'page' and normalize-space() = 'Test course 2']" "xpath_element" should not exist in the ".boost-union-menubar [data-region='moredropdown']" "css_element"
    And I click on "More" "link" in the ".boost-union-menubar" "css_element"
    # In the user menu, the course on the third level is marked as active as well.
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Quick links" "link" in the "#usermenu-carousel" "css_element"
    And I click on "Course list" "link" in the "#usermenu-carousel .carousel-item.active" "css_element"
    And "//a[contains(@class, 'dropdown-item') and @aria-current = 'true' and normalize-space() = 'Test course 1']" "xpath_element" should exist in the "#usermenu-carousel .carousel-item.active" "css_element"
    And "//a[contains(@class, 'dropdown-item') and @aria-current = 'true' and normalize-space() = 'Test course 2']" "xpath_element" should not exist in the "#usermenu-carousel .carousel-item.active" "css_element"

  @javascript
  Scenario: Smartmenu: Menus: Presentation - Verify that the correct _custom_ menu item is displayed as active when viewing the custom menu item's page (Moodle core behaviour which must not be broken by the smart menus) (with JavaScript)
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Quick links               |
      | location | Main navigation, Menu bar |
      | mode     | Submenu                   |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links       |
      | title    | Other node        |
      | itemtype | Static            |
      | url      | /user/profile.php |
    And I log in as "admin"
    And I navigate to "Appearance > Advanced theme settings" in site administration
    And I set the field "Custom menu items" to multiline:
    """
    Test node|/admin/tool/dataprivacy/summary.php
    """
    And I click on "Save changes" "button"
    When I am on site homepage
    Then "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Home']" "xpath_element" should exist in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Test node']" "xpath_element" should not exist in the ".primary-navigation" "css_element"
    And I click on "Test node" "link" in the ".primary-navigation" "css_element"
    Then "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Home']" "xpath_element" should not exist in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'mds-nav-pill--selected') and @aria-current = 'page' and normalize-space() = 'Test node']" "xpath_element" should exist in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Quick links']" "xpath_element" should not exist in the ".primary-navigation" "css_element"
    And "//a[contains(@class, 'mds-nav-pill--selected') and normalize-space() = 'Quick links']" "xpath_element" should not exist in the ".boost-union-menubar" "css_element"

  @javascript
  Scenario Outline: Smartmenu: Menus: Presentation - Ensure the menu bar is not displayed when no menus are present
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Menu bar links |
      | location | Menu bar       |
      | mode     | Inline         |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Menu bar links    |
      | title    | Moodle org        |
      | itemtype | Static            |
      | url      | http://moodle.org |
      | itemmode | Inline            |
      | desktop  | <item1desk>       |
      | tablet   | <item1tab>        |
      | mobile   | <item1mob>        |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Menu bar links              |
      | title    | Moodle Plugins              |
      | itemtype | Static                      |
      | url      | https://moodle.org/plugins/ |
      | itemmode | Inline                      |
      | desktop  | <item2desk>                 |
      | tablet   | <item2tab>                  |
      | mobile   | <item2mob>                  |
    When I log in as "admin"
    And I change the viewport size to "large"
    And ".boost-union-menubar" "css_element" <menubarshouldornot> be visible
    And I change the viewport size to "tablet"
    And ".boost-union-menubar" "css_element" <menubartabshouldornot> be visible
    And I change the viewport size to "mobile"
    And ".boost-union-menubar" "css_element" <menubarmobshouldornot> be visible

    Examples:
      | item1desk | item1tab | item1mob | item2desk | item2tab | item2mob | menubarshouldornot | menubartabshouldornot | menubarmobshouldornot |
      | 1         | 1        | 1        | 1         | 1        | 1        | should not         | should not            | should not            |
      | 0         | 1        | 1        | 1         | 1        | 1        | should             | should not            | should not            |
      | 1         | 0        | 1        | 1         | 1        | 1        | should not         | should                | should not            |
      | 1         | 1        | 0        | 1         | 1        | 0        | should not         | should not            | should                |
      | 0         | 0        | 1        | 0         | 0        | 1        | should             | should                | should not            |
