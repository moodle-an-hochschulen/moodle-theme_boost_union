@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_presentation
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, applying different presentation options to the individual smart menu items
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
    And the following "custom field categories" exist:
      | name                 | component   | area   | itemid |
      | Course custom fields | core_course | course | 0      |
    And the following "custom fields" exist:
      | name              | category             | type | shortname |
      | Test custom field | Course custom fields | text | testcf    |
    And the following "courses" exist:
      | fullname               | shortname                     | category | customfield_testcf                   |
      | Test course1           | C1                            | 0        | Custom1                              |
      | Test course2           | C2                            | 0        | Custom2                              |
      | Test course word count | C3 has a very long short name | 0        | Custom3 has a very long custom field |
    And the following "users" exist:
      | username |
      | user1    |
    And the following "theme_boost_union > smart menu" exists:
      | title    | Quick links                                      |
      | location | Main navigation, Menu bar, User menu             |

  Scenario Outline: Smartmenus: Menu items: Presentation - Open the smart menu items in different targets
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links       |
      | title    | Available courses |
      | itemtype | Dynamic courses   |
      | category | 0                 |
      | itemmode | Inline            |
      | target   | <setting>         |
    When I log in as "admin"
    Then the "target" attribute of "Test course1" "theme_boost_union > Main menu smart menu item" <should>
    And the "target" attribute of "Test course1" "theme_boost_union > User menu smart menu item" <should>
    And the "target" attribute of "Test course1" "theme_boost_union > Menu bar smart menu item" <should>

    Examples:
      | setting     | should                  |
      | New tab     | should contain "_blank" |
      | Same window | should not be set       |

  Scenario: Smartmenus: Menu items: Presentation - Include the custom css class for a smart menu item
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links           |
      | title    | Resources             |
      | itemtype | Static                |
      | url      | http://moodle.org     |
      | cssclass | static-item-resources |
    When I log in as "admin"
    Then the "class" attribute of "Resources" "theme_boost_union > Main menu smart menu item" should contain "static-item-resources"
    And the "class" attribute of "Resources" "theme_boost_union > User menu smart menu item" should contain "static-item-resources"
    And the "class" attribute of "Resources" "theme_boost_union > Menu bar smart menu item" should contain "static-item-resources"

  Scenario Outline: Smartmenus: Menu items: Presentation - Display the different fields as smart menu item title
    Given the following "theme_boost_union > smart menu item" exists:
      | menu                    | Quick links       |
      | title                   | Available courses |
      | itemtype                | Dynamic courses   |
      | category                | 0                 |
      | displayfield            | <firstlinefield>  |
      | displayfieldcustomfield | <firstlinecustom> |
    When I log in as "admin"
    Then I should see smart menu "Quick links" item "<showntitle>" in location "Main, Menu, User"
    And I should not see smart menu "Quick links" item "<notshowntitle>" in location "Main, Menu, User"

    Examples:
      | firstlinefield                          | firstlinecustom | showntitle             | notshowntitle |
      | Course short name                       |                 | C1                     | Test course   |
      | Course full name                        |                 | Test course word count | C1            |
      | Custom course field                     | testcf          | Custom1                | Test course1  |
      | Course full name (Course short name)    |                 | Test course1 (C1)      | Custom1       |
      | Course short name (Course full name)    |                 | C1 (Test course1)      | Custom1       |
      | Course full name (Custom course field)  | testcf          | Test course1 (Custom1) | C1            |
      | Course short name (Custom course field) | testcf          | C1 (Custom1)           | Test course1  |

  Scenario Outline: Smartmenus: Menu items: Presentation - Display the different fields as smart menu item second line (Show second line cases)
    Given the following "theme_boost_union > smart menu item" exists:
      | menu                          | Quick links        |
      | title                         | Available courses  |
      | itemtype                      | Dynamic courses    |
      | category                      | 0                  |
      # We just set the first line to avoid false positives with the second line values.
      | displayfield                  | <firstlinefield>   |
      | displayfieldcustomfield       | <firstlinecustom>  |
      | displayfieldsecond            | <secondlinefield>  |
      | displayfieldsecondcustomfield | <secondlinecustom> |
    When I log in as "admin"
    Then I should see smart menu "Quick links" item "<showntitle>" in location "Main, Menu, User"

    Examples:
      | firstlinefield      | firstlinecustom | secondlinefield     | secondlinecustom | showntitle   |
      | Custom course field | testcf          | Course full name    |                  | Test course1 |
      | Course full name    |                 | Course short name   |                  | C1           |
      | Course full name    |                 | Custom course field | testcf           | Custom1      |

  Scenario: Smartmenus: Menu items: Presentation - Display the different fields as smart menu item second line (No second line case)
    Given the following "theme_boost_union > smart menu item" exists:
      | menu                    | Quick links          |
      | title                   | Available courses    |
      | itemtype                | Dynamic courses      |
      | category                | 0                    |
      # We just set the first line to avoid false positives with the second line values.
      | displayfield            | Custom course field  |
      | displayfieldcustomfield | testcf               |
      | displayfieldsecond      | -- No second line -- |
    When I log in as "admin"
    Then I should not see smart menu "Quick links" item "Test course1" in location "Main, Menu, User"
    And I should not see smart menu "Quick links" item "C1" in location "Main, Menu, User"
    And ".boost-union-smartmenu-secondline" "css_element" should not exist

  Scenario Outline: Smartmenus: Menu items: Presentation - Display the smart menu item course full name with a word count
    Given the following "theme_boost_union > smart menu item" exists:
      | menu                          | Quick links              |
      | title                         | Available courses        |
      | itemtype                      | Dynamic courses          |
      | category                      | 0                        |
      | displayfield                  | <firstlinefield>         |
      | displayfieldcustomfield       | <firstlinecustom>        |
      | textcount                     | <numberofwords>          |
      | displayfieldsecond            | <secondlinefield>        |
      | displayfieldsecondcustomfield | <secondlinecustom>       |
      | textcountsecond               | <secondlinewords>        |
    When I log in as "admin"
    Then I should see smart menu "Quick links" item "<showntitle>" in location "Main, Menu, User"
    And I should not see smart menu "Quick links" item "<notshowntitle>" in location "Main, Menu, User"

    Examples:
      | firstlinefield                         | firstlinecustom | numberofwords | secondlinefield      | secondlinecustom | secondlinewords | showntitle                                           | notshowntitle          |
      # First line - no word count: full course name is shown unchanged.
      | Course full name                       |                 |               | -- No second line -- |                  |                 | Test course word count                               | Test course..          |
      # First line - Course short name: short name is never truncated, even if textcount is set.
      | Course short name                      |                 | 2             | -- No second line -- |                  |                 | C3 has a very long short name                        | C3 has..               |
      # First line - Custom course field: custom field is never truncated, even if textcount is set.
      | Custom course field                    | testcf          | 2             | -- No second line -- |                  |                 | Custom3 has a very long custom field                 | Custom3 has..          |
      # First line - Course full name, word count = 2: full name is truncated.
      | Course full name                       |                 | 2             | -- No second line -- |                  |                 | Test course..                                        | Test course word count |
      # First line - combined fields: only the full name part is truncated, the other part is kept intact.
      | Course full name (Course short name)   |                 | 2             | -- No second line -- |                  |                 | Test course.. (C3 has a very long short name)        | Test course word count |
      | Course short name (Course full name)   |                 | 2             | -- No second line -- |                  |                 | C3 has a very long short name (Test course..)        | Test course word count |
      | Course full name (Custom course field) | testcf          | 2             | -- No second line -- |                  |                 | Test course.. (Custom3 has a very long custom field) | Test course word count |
      # Second line - Course full name, no word count: full course name is shown unchanged in second line.
      | Course short name                      |                 |               | Course full name     |                  |                 | Test course word count                               | Test course..          |
      # Second line - Course short name: short name is never truncated, even if textcountsecond is set.
      | Course full name                       |                 | 2             | Course short name    |                  | 2               | C3 has a very long short name                        | C3 has..               |
      # Second line - Custom course field: custom field is never truncated, even if textcountsecond is set.
      | Course full name                       |                 | 2             | Custom course field  | testcf           | 2               | Custom3 has a very long custom field                 | Custom3 has..          |
      # Second line - Course full name, word count = 2: full name in second line is truncated.
      | Course short name                      |                 |               | Course full name     |                  | 2               | Test course..                                        | Test course word count |

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the menu item title in different types
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links     |
      | title    | External links  |
      | itemtype | Heading         |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links        |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
    When I log in as "admin"
    And I should see smart menu "Quick links" item "External links" in location "Main, Menu, User"
    # Menu items in the main menu.
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    Then I should see "External links" in the ".primary-navigation .menu-item-heading" "css_element"
    And I should see "Resources" in the ".primary-navigation .menu-item-static" "css_element"
    And the "href" attribute of ".primary-navigation .menu-item-heading" "css_element" should contain "#"
    And the "href" attribute of ".primary-navigation .menu-item-static" "css_element" should contain "https://moodle.org"
    # Menu items in the menu bar.
    And I click on "Quick links" "link" in the ".boost-union-menubar" "css_element"
    Then I should see "External links" in the ".boost-union-menubar .menu-item-heading" "css_element"
    And I should see "Resources" in the ".boost-union-menubar .menu-item-static" "css_element"
    And the "href" attribute of ".boost-union-menubar .menu-item-heading" "css_element" should contain "#"
    And the "href" attribute of ".boost-union-menubar .menu-item-static" "css_element" should contain "https://moodle.org"
    # Menu items in user menu.
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Quick links" "link" in the "#usermenu-carousel" "css_element"
    Then I should see "External links" in the "#usermenu-carousel .menu-item-heading" "css_element"
    And I should see "Resources" in the "#usermenu-carousel .menu-item-static" "css_element"
    And the "href" attribute of "//div[contains(@id, 'usermenu-carousel')]//div[contains(@class, 'carousel-item')]//a[contains(@class, 'menu-item-heading')]" "xpath_element" should contain "#"
    And the "href" attribute of "//div[contains(@id, 'usermenu-carousel')]//div[contains(@class, 'carousel-item')]//a[contains(@class, 'menu-item-static')]" "xpath_element" should contain "https://moodle.org"

  Scenario: Smartmenus: Menu items: Presentation - Display the menu items in different order
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links         |
      | title    | Demo item 01        |
      | itemtype | Static              |
      | url      | https://example.com |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links         |
      | title    | Demo item 02        |
      | itemtype | Static              |
      | url      | https://example.com |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links         |
      | title    | Demo item 03        |
      | itemtype | Static              |
      | url      | https://example.com |
    When I log in as "admin"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And "Demo item 02" "text" should appear after "Demo item 01" "text"
    And "Demo item 03" "text" should appear after "Demo item 02" "text"
    And the following "theme_boost_union > smart menu item" exists:
      | menu      | Quick links         |
      | title     | Demo item 04        |
      | itemtype  | Static              |
      | url       | https://example.com |
      | sortorder | 6                   |
    And the following "theme_boost_union > smart menu item" exists:
      | menu      | Quick links         |
      | title     | Demo item 05        |
      | itemtype  | Static              |
      | url       | https://example.com |
      | sortorder | 4                   |
    And all Boost Union MUC caches are purged
    And I reload the page
    And "Demo item 04" "text" should appear after "Demo item 03" "text"
    And "Demo item 04" "text" should appear after "Demo item 05" "text"

  # The following "Smartmenus: Menu items: Presentation - Display the menu items in different viewports" scenarios look like they
  # could be combined into a single scenario outline, but they are not because with the scenario outline approach, the test
  # would attempt to open menus that were hidden due to having no items.

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the menu items in different viewports - hide the menu items on mobile devices
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links        |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
      | desktop  | 0                  |
      | tablet   | 0                  |
      | mobile   | 1                  |
    When I log in as "admin"
    Then I should see smart menu "Quick links" item "Resources" in location "Menu, Main, User"
    And I change viewport size to "tablet"
    Then I should see smart menu "Quick links" item "Resources" in location "User, Menu"
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    Then I should see smart menu "Quick links" item "Resources" in location "Main"
    And I change viewport size to "mobile"
    Then "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Menu bar smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > User menu smart menu" should not be visible

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the menu items in different viewports - hide the menu items on tablet and mobile devices
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links        |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
      | desktop  | 0                  |
      | tablet   | 1                  |
      | mobile   | 1                  |
    When I log in as "admin"
    Then I should see smart menu "Quick links" item "Resources" in location "Menu, Main, User"
    And I change viewport size to "tablet"
    Then "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Menu bar smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > User menu smart menu" should not be visible
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    Then "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Main menu smart menu" should not be visible
    And I change viewport size to "mobile"
    Then "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Menu bar smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > User menu smart menu" should not be visible

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the menu items in different viewports - hide the menu items on desktop devices
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links        |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
      | desktop  | 1                  |
      | tablet   | 0                  |
      | mobile   | 0                  |
    When I log in as "admin"
    Then "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Main menu smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Menu bar smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > User menu smart menu" should not be visible
    And I change viewport size to "tablet"
    Then I should see smart menu "Quick links" item "Resources" in location "User, Menu"
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    Then I should see smart menu "Quick links" item "Resources" in location "Main"
    And I change viewport size to "mobile"
    Then I should see smart menu "Quick links" item "Resources" in location "Menu, User"

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the menu items in different viewports - hide the menu items on desktop and mobile devices
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links        |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
      | desktop  | 1                  |
      | tablet   | 0                  |
      | mobile   | 1                  |
    When I log in as "admin"
    Then "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Main menu smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Menu bar smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > User menu smart menu" should not be visible
    And I change viewport size to "tablet"
    Then I should see smart menu "Quick links" item "Resources" in location "User, Menu"
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    Then I should see smart menu "Quick links" item "Resources" in location "Main"
    And I change viewport size to "mobile"
    Then "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Menu bar smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > User menu smart menu" should not be visible

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Select an existing icon from the icon autocomplete list
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links |
      | title    | Resources   |
      | itemtype | Heading     |
    When I log in as "admin"
    And I navigate to smart menu "Quick links" items
    And I should see "Resources" in the "smartmenus_items" "table"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_menuicon" "css_element"
    And I set the field "Icon" to "fa-folder"
    And I should see "path_folder" in the "#fitem_id_menuicon .form-autocomplete-selection" "css_element"

  # Unfortunately, this can't be tested with Behat as Behat would throw an
  # 'Unable to find 'nonexistingicon' in the list of options, and unable to create a new option (InvalidArgumentException)'
  # exception when trying to select an unexisting icon.
  # Scenario: Smartmenus: Menu items: Presentation - Select an unexisting icon from the icon autocomplete list

  @javascript
  Scenario Outline: Smartmenus: Menu items: Presentation - Display the menu items title with icon
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links         |
      | title    | Resources           |
      | itemtype | Heading             |
      | display  | <presentationtitle> |
    When I log in as "admin"
    And I navigate to smart menu "Quick links" items
    And I should see "Resources" in the "smartmenus_items" "table"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_menuicon" "css_element"
    And I should see "<iconname>" in the "#fitem_id_menuicon .form-autocomplete-suggestions [data-value='<iconvalue>'] small" "css_element"
    And I should see "<iconbadge>" in the "#fitem_id_menuicon .form-autocomplete-suggestions [data-value='<iconvalue>'] span.badge" "css_element"
    And the "class" attribute of "#fitem_id_menuicon .form-autocomplete-suggestions [data-value='<iconvalue>'] i.fa" "css_element" should contain "<faicon>"
    And I click on "<iconname>" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    # Open the menu item entry again to check the style of the auto-selected value.
    And I should see "<iconname>" in the "#fitem_id_menuicon .form-autocomplete-selection [data-value='<iconvalue>'] small" "css_element"
    And I should see "<iconbadge>" in the "#fitem_id_menuicon .form-autocomplete-selection [data-value='<iconvalue>'] span.badge" "css_element"
    And the "class" attribute of "#fitem_id_menuicon .form-autocomplete-selection [data-value='<iconvalue>'] i.fa" "css_element" should contain "<faicon>"
    And I click on "Save changes" "button"
    # We cannot use the "I should see smart menu item step" here because it checks only for the text, not for the icon.
    # Instead, we open the menu and check for the icon presence manually.
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    Then ".<faicon>" "css_element" should exist in the ".primary-navigation .dropdown-item.menu-item-heading" "css_element"
    And ".<faicon>" "css_element" should exist in the ".boost-union-menubar .dropdown-item" "css_element"
    And ".<faicon>" "css_element" should exist in the "#usermenu-carousel .carousel-item.submenu .dropdown-item" "css_element"
    And I <desktoptextcheck> see "Resources" in the ".primary-navigation .dropdown-item.menu-item-heading" "css_element"

    Examples:
      | presentationtitle                                      | iconname          | iconvalue                        | iconbadge         | faicon         | desktoptextcheck |
      | Show text and icon as title                            | fa-circle-info    | theme_boost_union:fa-circle-info | FontAwesome Solid | fa-circle-info | should           |
      | Show text and icon as title                            | core:i/circleinfo | core:i/circleinfo                | Moodle core       | fa-circle-info | should           |
      | Hide title text and show only icon (on all devices)    | fa-circle-info    | theme_boost_union:fa-circle-info | FontAwesome Solid | fa-circle-info | should not       |
      | Hide title text and show only icon (on all devices)    | core:i/circleinfo | core:i/circleinfo                | Moodle core       | fa-circle-info | should not       |
      | Hide title text and show only icon (on mobile devices) | fa-circle-info    | theme_boost_union:fa-circle-info | FontAwesome Solid | fa-circle-info | should           |
      | Hide title text and show only icon (on mobile devices) | core:i/circleinfo | core:i/circleinfo                | Moodle core       | fa-circle-info | should           |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Presentation - Display the tooltip on hover over the menu items of a menu in submenu mode
    # The "Quick links" menu is in submenu mode: The static item is shown as dropdown item, the dynamic courses item
    # (which is in submenu mode as well) as link to the third level of the menu.
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links        |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
      | tooltip  | <tooltip>          |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links      |
      | title    | Course list      |
      | itemtype | Dynamic courses  |
      | itemmode | Submenu          |
      | tooltip  | <coursestooltip> |
    When I log in as "admin"
    And I click on "Quick links" "link" in the "<container>" "css_element"
    And I hover over the "Resources" "text" in the "<container>" "css_element"
    Then "body > .tooltip" "css_element" <shouldornot> exist
    And I <shouldornot> see "External links"
    And I hover over the "Course list" "text" in the "<container>" "css_element"
    Then "body > .tooltip" "css_element" <shouldornot> exist
    And I <shouldornot> see "All courses"

    Examples:
      | container            | tooltip        | coursestooltip | shouldornot |
      | .primary-navigation  | External links | All courses    | should      |
      | .primary-navigation  |                |                | should not  |
      | .boost-union-menubar | External links | All courses    | should      |
      | .boost-union-menubar |                |                | should not  |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Presentation - Display the tooltip on hover over the menu items of a menu in inline mode
    # The "Links" menu is in inline mode: The static item is shown as navigation pill, the dynamic courses item
    # (which is in submenu mode) as dropdown toggle pill. The tooltip of the toggle is hidden as soon as its dropdown opens.
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Links                     |
      | location | Main navigation, Menu bar |
      | mode     | Inline                    |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Links              |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
      | tooltip  | <tooltip>          |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Links            |
      | title    | Course list      |
      | itemtype | Dynamic courses  |
      | itemmode | Submenu          |
      | tooltip  | <coursestooltip> |
    When I log in as "admin"
    # Make the screen really large so that the inline items are not collapsed into the "More" menu.
    And I change viewport size to "large"
    And I hover over the "Resources" "text" in the "<container>" "css_element"
    Then "body > .tooltip" "css_element" <shouldornot> exist
    And I <shouldornot> see "External links"
    And I hover over the "Course list" "text" in the "<container>" "css_element"
    Then "body > .tooltip" "css_element" <shouldornot> exist
    And I <shouldornot> see "All courses"
    # Opening the dropdown of the toggle hides its tooltip (which would overlap the dropdown otherwise).
    # The tooltip is faded out, so this has to wait until it is gone instead of just asserting that it is gone.
    And I click on "Course list" "link" in the "<container>" "css_element"
    And I wait until "body > .tooltip" "css_element" does not exist
    And I should see "Test course1" in the "<container>" "css_element"

    Examples:
      | container            | tooltip        | coursestooltip | shouldornot |
      | .primary-navigation  | External links | All courses    | should      |
      | .primary-navigation  |                |                | should not  |
      | .boost-union-menubar | External links | All courses    | should      |
      | .boost-union-menubar |                |                | should not  |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Presentation - Display the tooltip on hover over the menu items of a menu inside the more menu
    # The "Links" menu is in submenu mode and forced into the "More" menu where it is shown as nested menu.
    Given the following "theme_boost_union > smart menu" exists:
      | title            | Links                     |
      | location         | Main navigation, Menu bar |
      | mode             | Submenu                   |
      | moremenubehavior | Force into more menu      |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Links              |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
      | tooltip  | External links     |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Links           |
      | title    | Course list     |
      | itemtype | Dynamic courses |
      | itemmode | Submenu         |
      | tooltip  | All courses     |
    When I log in as "admin"
    And I click on "More" "link" in the "<container>" "css_element"
    And I click on "Links" "link" in the "<container> [data-region='moredropdown']" "css_element"
    And I hover over the "Resources" "text" in the "<container> [data-region='moredropdown']" "css_element"
    Then "body > .tooltip" "css_element" should exist
    And I should see "External links"
    And I hover over the "Course list" "text" in the "<container> [data-region='moredropdown']" "css_element"
    Then "body > .tooltip" "css_element" should exist
    And I should see "All courses"

    Examples:
      | container            |
      | .primary-navigation  |
      | .boost-union-menubar |

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the tooltip on hover over the menu items in the user menu
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links        |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
      | tooltip  | External links     |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links     |
      | title    | Course list     |
      | itemtype | Dynamic courses |
      | itemmode | Submenu         |
      | tooltip  | All courses     |
    When I log in as "admin"
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Quick links" "link" in the "#usermenu-carousel" "css_element"
    And I hover over the "Resources" "text" in the "#usermenu-carousel" "css_element"
    Then "body > .tooltip" "css_element" should exist
    And I should see "External links"
    And I hover over the "Course list" "text" in the "#usermenu-carousel" "css_element"
    Then "body > .tooltip" "css_element" should exist
    And I should see "All courses"

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the "Hidden from students" tooltip on hover over a hidden course in a dynamic courses item
    Given the following "courses" exist:
      | fullname      | shortname | category | visible |
      | Hidden course | CH        | 0        | 0       |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links     |
      | title    | Course list     |
      | itemtype | Dynamic courses |
      | itemmode | Inline          |
    When I log in as "admin"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And I hover over the "Hidden course" "text" in the ".primary-navigation" "css_element"
    Then "body > .tooltip" "css_element" should exist
    And I should see "Hidden from students"
    # A visible course does not get this tooltip.
    And I hover over the "Test course1" "text" in the ".primary-navigation" "css_element"
    Then I should not see "Hidden from students"

  Scenario: Smartmenu: Menu items: Presentation - Add a smart menu item with multilang tags
    Given the following "language packs" exist:
      | language |
      | de       |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links                                                                                                 |
      | title    | <span lang="en" class="multilang">Lorem ipsum</span><span lang="de" class="multilang">Dolor sit amet</span> |
      | itemtype | Static                                                                                                      |
      | url      | https://moodle.org/foo                                                                                      |
    When I log in as "admin"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "English ‎(en)‎"
    And I press "Save changes"
    And I am on homepage
    Then I should see smart menu "Quick links" item "Lorem ipsum" in location "Main, Menu, User"
    And I should not see smart menu "Quick links" item "Dolor sit amet" in location "Main, Menu, User"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "Deutsch ‎(de)‎"
    And I press "Save changes"
    And I am on homepage
    Then I should see smart menu "Quick links" item "Dolor sit amet" in location "Main, Menu, User"
    And I should not see smart menu "Quick links" item "Lorem ipsum" in location "Main, Menu, User"

  Scenario: Smartmenus: Menu items: Presentation - Hide empty menus (in submenu mode)
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Links                                            |
      | location | Main navigation, Menu bar, User menu             |
      | mode     | Submenu                                          |
    When I log in as "admin"
    And "Links" "theme_boost_union > Main menu smart menu" should not exist
    And "Links" "theme_boost_union > Menu bar smart menu" should not exist
    And "Links" "theme_boost_union > User menu smart menu" should not exist
    And "Smartmenu Resource" "theme_boost_union > Main menu smart menu item" should not exist
    And "Smartmenu Resource" "theme_boost_union > Menu bar smart menu item" should not exist
    And "Smartmenu Resource" "theme_boost_union > User menu smart menu item" should not exist
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Links              |
      | title    | Smartmenu Resource |
      | itemtype | Static             |
      | url      | http://moodle.org  |
    And all Boost Union MUC caches are purged
    And I am on homepage
    Then "Links" "theme_boost_union > Main menu smart menu" should exist
    And "Links" "theme_boost_union > Menu bar smart menu" should exist
    And "Links" "theme_boost_union > User menu smart menu" should exist

  Scenario: Smartmenus: Menu items: Presentation - Hide empty menus (in inline mode)
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Links                                            |
      | location | Main navigation, Menu bar, User menu             |
      | mode     | Inline                                           |
    When I log in as "admin"
    And "Links" "theme_boost_union > Main menu smart menu" should not exist
    And "Links" "theme_boost_union > Menu bar smart menu" should not exist
    And "Links" "theme_boost_union > User menu smart menu" should not exist
    And "Smartmenu Resource" "theme_boost_union > Main menu smart menu item" should not exist
    And "Smartmenu Resource" "theme_boost_union > Menu bar smart menu item" should not exist
    And "Smartmenu Resource" "theme_boost_union > User menu smart menu item" should not exist
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Links              |
      | title    | Smartmenu Resource |
      | itemtype | Static             |
      | url      | http://moodle.org  |
    And all Boost Union MUC caches are purged
    And I am on homepage
    Then "Smartmenu Resource" "theme_boost_union > Main menu smart menu item" should exist
    And "Smartmenu Resource" "theme_boost_union > Menu bar smart menu item" should exist
    And "Smartmenu Resource" "theme_boost_union > User menu smart menu item" should exist

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Make full submenu header clickable
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links       |
      | title    | Available courses |
      | itemtype | Dynamic courses   |
      | category | 0                 |
      | itemmode | Submenu           |
    When I log in as "admin"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And I click on "Available courses" "link" in the ".primary-navigation" "css_element"
    Then I should see "Test course1" in the ".primary-navigation" "css_element"
    And I should see "Test course2" in the ".primary-navigation" "css_element"
    And I should see "Available courses" in the ".primary-navigation .header .carousel-navigation-link" "css_element"
    # Primary navigation.
    And I click on ".carousel-item.active .header" "css_element" in the ".primary-navigation" "css_element"
    Then I should see "Available courses" in the ".primary-navigation .carousel-item" "css_element"
    And I should not see "Available courses" in the ".primary-navigation .header .carousel-navigation-link" "css_element"
    And I should not see "Test course1" in the ".primary-navigation" "css_element"
    # User menu (where the header becomes fully clickable as soon as a smart menu is added).
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Quick links" "link" in the "#usermenu-carousel" "css_element"
    And I click on "Available courses" "link" in the "#usermenu-carousel" "css_element"
    Then I should see "Test course1" in the "#usermenu-carousel" "css_element"
    And I should see "Test course2" in the "#usermenu-carousel" "css_element"
    And I should see "Available courses" in the "#usermenu-carousel .carousel-item.active .header .carousel-navigation-link" "css_element"
    And I click on ".carousel-item.active .header" "css_element" in the "#usermenu-carousel" "css_element"
    Then I should see "Available courses" in the "#usermenu-carousel .carousel-item.active" "css_element"
    And I should not see "Available courses" in the "#usermenu-carousel .carousel-item.active .header .carousel-navigation-link" "css_element"
    And I should not see "Test course1" in the "#usermenu-carousel" "css_element"

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Opening a smart menu submenu should not scroll to top of the page
    Given the following "theme_boost_union > smart menu" exists:
      | title    | All courses     |
      | location | Main navigation |
      | mode     | Submenu         |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | All courses     |
      | title    | Category 1      |
      | itemtype | Dynamic courses |
      | itemmode | Submenu         |
    When I log in as "admin"
    And I change the viewport size to "medium"
    And I am on "Test course1" course homepage
    And I make the navbar fixed
    Then I scroll page to DOM element with ID "page-footer"
    And I click on "All courses" "link" in the ".primary-navigation" "css_element"
    And I click on "Category 1" "link" in the ".primary-navigation" "css_element"
    Then page top is not at the top of the viewport
