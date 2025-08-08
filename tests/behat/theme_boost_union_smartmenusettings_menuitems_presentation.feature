@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_presentation
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, applying different presentation options to the individual smart menu items
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "courses" exist:
      | fullname               | shortname | category |
      | Test course1           | C1        | 0        |
      | Test course2           | C2        | 0        |
      | Test course word count | C3        | 0        |
    And the following "users" exist:
      | username |
      | user1    |
    And the following "theme_boost_union > smart menu" exists:
      | title    | Quick links                                      |
      | location | Main navigation, Menu bar, User menu, Bottom bar |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Presentation - Open the smart menu items in different targets
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links       |
      | title    | Available courses |
      | itemtype | Dynamic courses   |
      | category | 0                 |
      | itemmode | Inline            |
      | target   | <setting>         |
    When I log in as "admin"
    # Menu items in main navigation
    Then "Test course1" "theme_boost_union > Smart menu item" should exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    Then I should see smart menu "Quick links" item "Test course1" in location "Main"
    And the "target" attribute of "//div[contains(@class, 'primary-navigation')]//a[contains(normalize-space(.), 'Test course1')]" "xpath_element" <should>
    # Menu items in user menu.
    Then "Test course1" "theme_boost_union > Smart menu item" should exist in the "Quick links" "theme_boost_union > User menu smart menu"
    And the "target" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Test course1')]" "xpath_element" <should>
    # Menu items in bottom menu.
    Then "Test course1" "theme_boost_union > Smart menu item" should exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    And the "target" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Test course1')]" "xpath_element" <should>
    Then I change viewport size to "large"
    # Menu items in menubar.
    Then "Test course1" "theme_boost_union > Smart menu item" should exist in the "Quick links" "theme_boost_union > Menu bar smart menu"
    And the "target" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Test course1')]" "xpath_element" <should>

    Examples:
      | setting     | should                  |
      | New tab     | should contain "_blank" |
      | Same window | should not be set       |

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Include the custom css class for a smart menu item
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links           |
      | title    | Resources             |
      | itemtype | Static                |
      | url      | http://moodle.org     |
      | cssclass | static-item-resources |
    When I am on the "Quick links" "theme_boost_union > Smart menu > Items" page logged in as "admin"
    Then the "class" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "static-item-resources"
    And the "class" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "static-item-resources"
    And the "class" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "static-item-resources"
    And I change viewport size to "mobile"
    And the "class" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "static-item-resources"
    And I change viewport size to "large"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "CSS class" to "course-resource-links"
    And I click on "Save changes" "button"
    And the "class" attribute of "//div[contains(@class, 'primary-navigation')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should not contain "static-item-resources"
    And the "class" attribute of "//div[contains(@class, 'primary-navigation')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "course-resource-links"
    And the "class" attribute of "//div[contains(@id, 'usermenu-carousel')]//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should not contain "static-item-resources"
    And the "class" attribute of "//div[contains(@id, 'usermenu-carousel')]//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "course-resource-links"
    And the "class" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should not contain "static-item-resources"
    And the "class" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "course-resource-links"
    And I change viewport size to "mobile"
    And the "class" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should not contain "static-item-resources"
    And the "class" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "course-resource-links"

  @javascript
  Scenario Outline: Smartmenus: Menu items: Presentation - Display the different fields as smart menu item title
    Given the following "theme_boost_union > smart menu item" exists:
      | menu         | Quick links       |
      | title        | Available courses |
      | itemtype     | Dynamic courses   |
      | category     | 0                 |
      | displayfield | <selectnamefield> |
      | textcount    | <numberofwords>   |
    When I log in as "admin"
    Then "<showntitle>" "theme_boost_union > Smart menu item" should exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    And "<showntitle>" "theme_boost_union > Smart menu item" should exist in the "Quick links" "theme_boost_union > Menu bar smart menu"
    And "<showntitle>" "theme_boost_union > Smart menu item" should exist in the "Quick links" "theme_boost_union > User menu smart menu"
    And "<showntitle>" "theme_boost_union > Smart menu item" should exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    And "<notshowntitle>" "theme_boost_union > Smart menu item" should not exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    And "<notshowntitle>" "theme_boost_union > Smart menu item" should not exist in the "Quick links" "theme_boost_union > Menu bar smart menu"
    And "<notshowntitle>" "theme_boost_union > Smart menu item" should not exist in the "Quick links" "theme_boost_union > User menu smart menu"
    And "<notshowntitle>" "theme_boost_union > Smart menu item" should not exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"

    Examples:
      | selectnamefield   | numberofwords | showntitle             | notshowntitle          |
      | Course short name |               | C1                     | Test course            |
      | Course full name  |               | Test course word count | C1                     |
      | Course full name  | 2             | Test course..          | Test course word count |

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the menu item title in different types
    When I log in as "admin"
    And I navigate to smart menu "Quick links" items
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title             | External links   |
      | Menu item type    | Heading          |
    And I should see "External links" in the "smartmenus_items" "table"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title             | Resources          |
      | Menu item type    | Static             |
      | Menu item URL     | https://moodle.org |
    And I should see "Resources" in the "smartmenus_items" "table"
    And I should see smart menu "Quick links" item "External links" in location "Main, Menu, User, Bottom"
    # Main menu.
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    Then I should see "External links" in the ".primary-navigation .menu-item-heading" "css_element"
    And I should see "Resources" in the ".primary-navigation .menu-item-static" "css_element"
    And the "href" attribute of ".primary-navigation .menu-item-heading" "css_element" should contain "#"
    And the "href" attribute of ".primary-navigation .menu-item-static" "css_element" should contain "https://moodle.org"
    # Menu bar.
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
    # Menu items in bottom menu.
    And I change viewport size to "740x900"
    And I click on "Quick links" "link" in the ".bottom-navigation" "css_element"
    Then I should see "External links" in the ".bottom-navigation .menu-item-heading" "css_element"
    And I should see "Resources" in the ".bottom-navigation .menu-item-static" "css_element"
    And the "href" attribute of "//div[@class='bottom-navigation']//a[contains(@class, 'menu-item-heading')]" "xpath_element" should contain "#"
    And the "href" attribute of "//div[@class='bottom-navigation']//a[contains(@class, 'menu-item-static')]" "xpath_element" should contain "https://moodle.org"

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the menu items in different order
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Demo item 01        |
      | Menu item type | Static              |
      | Menu item URL  | https://example.com |
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Demo item 02        |
      | Menu item type | Static              |
      | Menu item URL  | https://example.com |
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Demo item 03        |
      | Menu item type | Static              |
      | Menu item URL  | https://example.com |
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And "Demo item 02" "text" should appear after "Demo item 01" "text"
    And "Demo item 03" "text" should appear after "Demo item 02" "text"
    And I click on ".action-edit" "css_element" in the "Demo item 01" "table_row"
    And I set the field "Order" to "3"
    And I click on "Save changes" "button"
    And I click on ".action-edit" "css_element" in the "Demo item 03" "table_row"
    And I set the field "Order" to "1"
    And I click on "Save changes" "button"
    And "Demo item 02" "text" should appear after "Demo item 03" "text"
    And "Demo item 01" "text" should appear after "Demo item 02" "text"

  # The following "Smartmenus: Menu items: Presentation - Display the menu items in different viewports" scenarios look like they
  # could be combined into a single scenario outline, but they are not because with the scenario outline approach, the test
  # would attempt to open menus that were hidden due to having no items.

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the menu items in different viewports - hide the menu items on mobile devices
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Resources          |
      | Menu item type | Static             |
      | Menu item URL  | https://moodle.org |
      | desktop        | 0                  |
      | tablet         | 0                  |
      | mobile         | 1                  |
    Then I should see smart menu "Quick links" item "Resources" in location "Menu, Main, User"
    And I change viewport size to "tablet"
    Then I should see smart menu "Quick links" item "Resources" in location "User, Menu"
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    Then I should see smart menu "Quick links" item "Resources" in location "Main"
    And I change viewport size to "mobile"
    Then "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Menu bar smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > User menu smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Bottom bar smart menu" should not be visible

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the menu items in different viewports - hide the menu items on tablet and mobile devices
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Resources          |
      | Menu item type | Static             |
      | Menu item URL  | https://moodle.org |
      | desktop        | 0                  |
      | tablet         | 1                  |
      | mobile         | 1                  |
    Then I should see smart menu "Quick links" item "Resources" in location "Menu, Main, User"
    And I change viewport size to "tablet"
    Then "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Menu bar smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > User menu smart menu" should not be visible
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    Then "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Main menu smart menu" should not be visible
    And I change viewport size to "mobile"
    Then "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Menu bar smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > User menu smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Bottom bar smart menu" should not be visible

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the menu items in different viewports - hide the menu items on desktop devices
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Resources          |
      | Menu item type | Static             |
      | Menu item URL  | https://moodle.org |
      | desktop        | 1                  |
      | tablet         | 0                  |
      | mobile         | 0                  |
    Then "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Main menu smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Menu bar smart menu" should not be visible
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > User menu smart menu" should not be visible
    And I change viewport size to "tablet"
    Then I should see smart menu "Quick links" item "Resources" in location "User, Menu"
    And I click on "More" "link" in the ".primary-navigation" "css_element"
    Then I should see smart menu "Quick links" item "Resources" in location "Main"
    And I change viewport size to "mobile"
    Then I should see smart menu "Quick links" item "Resources" in location "Menu, User"
    And I should see smart menu "Quick links" item "Resources" in location "Bottom"

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the menu items in different viewports - hide the menu items on desktop and mobile devices
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Resources          |
      | Menu item type | Static             |
      | Menu item URL  | https://moodle.org |
      | desktop        | 1                  |
      | tablet         | 0                  |
      | mobile         | 1                  |
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
    And "Resources" "theme_boost_union > Smart menu item" in the "Quick links" "theme_boost_union > Bottom bar smart menu" should not be visible

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Select an existing icon from the icon autocomplete list
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title              | Resources           |
      | Menu item type     | Heading             |
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
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title              | Resources           |
      | Menu item type     | Heading             |
      | Title presentation | <presentationtitle> |
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
    And I <desktopshouldornot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User"
    Then ".<faicon>" "css_element" should exist in the ".primary-navigation .dropdown-item.menu-item-heading" "css_element"
    And ".<faicon>" "css_element" should exist in the ".boost-union-menubar .dropdown-item" "css_element"
    And ".<faicon>" "css_element" should exist in the "#usermenu-carousel .carousel-item.submenu .dropdown-item" "css_element"
    And I change viewport size to "mobile"
    And I <mobiletitleshould> see smart menu "Quick links" item "Resources" in location "Menu, User"
    And I click on "More" "button" in the ".bottom-navigation" "css_element"
    And I click on "Quick links" "link" in the "#theme_boost-drawers-primary" "css_element"
    And I <mobiletitleshould> see "Resources" in the "#theme_boost-drawers-primary" "css_element"
    Then ".<faicon>" "css_element" should exist in the ".primary-navigation .dropdown-item.menu-item-heading" "css_element"
    And ".<faicon>" "css_element" should exist in the ".boost-union-menubar .dropdown-item" "css_element"
    And ".<faicon>" "css_element" should exist in the "#usermenu-carousel .carousel-item.submenu .dropdown-item" "css_element"

    Examples:
      | presentationtitle                                      | iconname          | iconvalue                        | iconbadge         | faicon         | desktopshouldornot | mobiletitleshould |
      | Show text and icon as title                            | fa-circle-info    | theme_boost_union:fa-circle-info | FontAwesome Solid | fa-circle-info | should             | should            |
      | Show text and icon as title                            | core:i/circleinfo | core:i/circleinfo                | Moodle core       | fa-circle-info | should             | should            |
      | Hide title text and show only icon (on all devices)    | fa-circle-info    | theme_boost_union:fa-circle-info | FontAwesome Solid | fa-circle-info | should not         | should not        |
      | Hide title text and show only icon (on all devices)    | core:i/circleinfo | core:i/circleinfo                | Moodle core       | fa-circle-info | should not         | should not        |
      | Hide title text and show only icon (on mobile devices) | fa-circle-info    | theme_boost_union:fa-circle-info | FontAwesome Solid | fa-circle-info | should             | should not        |
      | Hide title text and show only icon (on mobile devices) | core:i/circleinfo | core:i/circleinfo                | Moodle core       | fa-circle-info | should             | should not        |

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the tooltip on hover over the menu items
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Resources          |
      | Menu item type | Static             |
      | Menu item URL  | https://moodle.org |
      | Tooltip        | External links     |
    And I should see "Resources" in the "smartmenus_items" "table"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And I hover over the "Resources" "text" in the ".primary-navigation" "css_element"
    Then I should see "External links" in the "body > .tooltip" "css_element"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I set the field "Tooltip" to ""
    And I click on "Save changes" "button"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And I hover over the "Resources" "link" in the ".primary-navigation" "css_element"
    And "body > .tooltip" "css_element" should not exist

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Display the card type menu items title with different position and color
    When I log in as "admin"
    And I navigate to smart menus
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I set the field "Presentation type" to "Card"
    And I click on "Save and return" "button"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title              | Resources          |
      | Menu item type     | Static             |
      | Menu item URL      | https://moodle.org |
      | Card text position | Below image        |
      | Card text color    | #FFFEFF            |
    And I should see smart menu "Quick links" item "Resources" in location "Main"
    Then DOM element ".dropdown-menu.show .card-block .content-block a.dropdown-item" should have computed style "color" "rgb(255, 254, 255)"
    And ".dropdown-menu.show .card-block.card-text-below" "css_element" should exist in the ".primary-navigation" "css_element"
    And ".dropdown-menu.show .card-block .content-block" "css_element" should appear after ".dropdown-menu.show .card-block .img-block" "css_element"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "Card text position" to "Top overlay"
    And I set the field "Card text color" to "#230017"
    And I click on "Save changes" "button"
    And I should see smart menu "Quick links" item "Resources" in location "Main"
    Then DOM element ".dropdown-menu.show .card-block .content-block a.dropdown-item" should have computed style "color" "rgb(35, 0, 23)"
    And ".dropdown-menu.show .card-block.card-text-overlay-top" "css_element" should exist in the ".primary-navigation" "css_element"
    And DOM element ".dropdown-menu.show .card-block .content-block" should have computed style "position" "absolute"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "Card text position" to "Bottom overlay"
    And I click on "Save changes" "button"
    And I should see smart menu "Quick links" item "Resources" in location "Main"
    Then ".dropdown-menu.show .card-block.card-text-overlay-bottom" "css_element" should exist in the ".primary-navigation" "css_element"
    And DOM element ".dropdown-menu.show .card-block .content-block" should have computed style "position" "absolute"
    And DOM element ".dropdown-menu.show .card-block .content-block" should have computed style "align-items" "flex-end"

  @javascript @_file_upload
  Scenario: Smartmenus: Menu items: Presentation - Display the card type menu item with background image and colors
    When I log in as "admin"
    And I navigate to smart menus
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I set the field "Presentation type" to "Card"
    And I click on "Save and return" "button"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title                 | Resources           |
      | Menu item type        | Static              |
      | Menu item URL         | https://example.com |
      | Card background color | #031FC3             |
    And I should see smart menu "Quick links" item "Resources" in location "Main"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    Then DOM element ".dropdown-menu .menu-item-static .content-block" should have computed style "background-color" "rgb(3, 31, 195)"
    And I click on "Quick links" "link" in the ".boost-union-menubar" "css_element"
    Then DOM element ".boost-union-menubar .dropdown-menu.show .content-block" should have computed style "background-color" "rgb(3, 31, 195)"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I upload "theme/boost_union/tests/fixtures/backimg.png" file to "Card image" filemanager
    And I click on "Save changes" "button"
    Then I should see smart menu "Quick links" item "Resources" in location "Main, Menu"
    And the image at "//div[contains(@class, 'primary-navigation')]//img[contains(@src, 'pluginfile.php') and contains(@src, '/theme_boost_union/smartmenus_itemimage/')]" "xpath_element" should be identical to "theme/boost_union/tests/fixtures/backimg.png"

  @javascript
  Scenario: Smartmenu: Menu items: Presentation - Add a smart menu item with multilang tags
    Given the following "language packs" exist:
      | language |
      | de       |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
    When I log in as "admin"
    And I navigate to smart menu "Quick links" items
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | <span lang="en" class="multilang">Lorem ipsum</span><span lang="de" class="multilang">Dolor sit amet</span> |
      | Menu item type | Static                                                                                                      |
      | URL            | https://moodle.org/foo                                                                                      |
    And I click on "Save changes" "button"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "English ‎(en)‎"
    And I press "Save changes"
    And I am on site homepage
    Then I should see smart menu "Quick links" item "Lorem ipsum" in location "Main, Menu, User, Bottom"
    And I should not see smart menu "Quick links" item "Dolor sit amet" in location "Main, Menu, User, Bottom"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "Deutsch ‎(de)‎"
    And I press "Save changes"
    And I am on site homepage
    Then I should see smart menu "Quick links" item "Dolor sit amet" in location "Main, Menu, User, Bottom"
    And I should not see smart menu "Quick links" item "Lorem ipsum" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario Outline: Smartmenus: Menu items: Presentation - Hide empty menus
    When I log in as "admin"
    And I create smart menu with the following fields to these values:
      | Title            | Links                    |
      | Menu location(s) | Main, Menu, User, Bottom |
      | Menu mode        | <menumode>               |
    And I should see "Links" in the "smartmenus" "table"
    And I should not see smart menu "<menutitle>" in location "Main, Menu, User, Bottom"
    And I set "Links" smart menu items with the following fields to these values:
      | Title          | Smartmenu Resource |
      | Menu item type | Static             |
      | URL            | http://moodle.org  |
    Then I should see smart menu "<menutitle>" in location "Main, Menu, User, Bottom"

    Examples:
      | menumode | menutitle          |
      | Submenu  | Links              |
      | Inline   | Smartmenu Resource |

  Scenario Outline: Smartmenus: Menu items: Presentation - Image alt text for the dynamic menu items
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Courses                                          |
      | location | Main navigation, Menu bar, User menu, Bottom bar |
      | type     | Card                                             |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Courses           |
      | title    | Available courses |
      | itemtype | Dynamic courses   |
      | category | 0                 |
      | itemmode | Inline            |
      | imagealt | <setting>         |
    When I log in as "admin"
    Then the "alt" attribute of "//div[contains(@class, 'primary-navigation')]//li[contains(@class, 'boost-union-smartmenu')]//a[contains(normalize-space(.), 'Test course1')]//ancestor-or-self::div[@class='content-block']/parent::div//div[@class='img-block']//img" "xpath_element" should contain "<testcourse1result>"
    And the "alt" attribute of "//div[contains(@class, 'primary-navigation')]//li[contains(@class, 'boost-union-smartmenu')]//a[contains(normalize-space(.), 'Test course2')]//ancestor-or-self::div[@class='content-block']/parent::div//div[@class='img-block']//img" "xpath_element" should contain "<testcourse2result>"
    And the "alt" attribute of "//div[contains(@class, 'primary-navigation')]//li[contains(@class, 'boost-union-smartmenu')]//a[contains(normalize-space(.), 'Test course word count')]//ancestor-or-self::div[@class='content-block']/parent::div//div[@class='img-block']//img" "xpath_element" should contain "<testcourse3result>"

    Examples:
      | setting                     | testcourse1result            | testcourse2result            | testcourse3result                      |
      | Image of course             | Image of course              | Image of course              | Image of course                        |
      | Image of course {menutitle} | Image of course Test course1 | Image of course Test course2 | Image of course Test course word count |
      |                             | Test course1                 | Test course2                 | Test course word count                 |

  Scenario Outline: Smartmenus: Menu items: Presentation - Image alt text for the static menu items
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Links                                            |
      | location | Main navigation, Menu bar, User menu, Bottom bar |
      | type     | Card                                             |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Links             |
      | title    | Moodle org        |
      | itemtype | Static            |
      | url      | http://moodle.org |
      | imagealt | <link1setting>    |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Links                       |
      | title    | Moodle Plugins              |
      | itemtype | Static                      |
      | url      | https://moodle.org/plugins/ |
      | imagealt | <link2setting>              |
    When I log in as "admin"
    Then the "alt" attribute of "//div[contains(@class, 'primary-navigation')]//li[contains(@class, 'boost-union-smartmenu')]//a[contains(normalize-space(.), 'Moodle org')]//ancestor-or-self::div[@class='content-block']/parent::div//div[@class='img-block']//img" "xpath_element" should contain "<link1result>"
    And the "alt" attribute of "//div[contains(@class, 'primary-navigation')]//li[contains(@class, 'boost-union-smartmenu')]//a[contains(normalize-space(.), 'Moodle Plugins')]//ancestor-or-self::div[@class='content-block']/parent::div//div[@class='img-block']//img" "xpath_element" should contain "<link2result>"

    Examples:
      | link1setting                  | link2setting                      | link1result                   | link2result                       |
      | Image of moodle official site | Image of moodle plugins directory | Image of moodle official site | Image of moodle plugins directory |
      | Image of {menutitle}          | Image of {menutitle}              | Image of Moodle org           | Image of Moodle Plugins           |
      |                               |                                   | Moodle org                    | Moodle Plugins                    |

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Make full submenu header clickable
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title                            | Available courses |
      | Menu item type                   | Dynamic courses   |
      | Dynamic courses: Course category | Category 1        |
      | Menu item mode                   | Submenu           |
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
      | title     | All courses     |
      | location  | Main navigation |
      | mode      | Submenu         |
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
