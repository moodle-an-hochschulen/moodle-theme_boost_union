@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_providers
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, using smart menu location and presentation providers
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    # The test providers are a location and a presentation which are shipped as test fixtures of Boost Union
    # to be able to test the provider API without an additional plugin.
    Given the following config values are set as admin:
      | config                        | value | plugin            |
      | behat_smartmenu_testproviders | 1     | theme_boost_union |

  @javascript
  Scenario: Smartmenu: Providers - Show the settings of a presentation provider only if it is selected
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    Then "Test presentation box color" "select" should not be visible
    And I set the field "Presentation type" to "Test presentation"
    Then "Test presentation box color" "select" should be visible
    # The settings of the presentation provider do not depend on the selected locations.
    And I set the field "Menu location(s)" to "Main navigation, Test location"
    Then "Test presentation box color" "select" should be visible
    # The settings of the presentation provider are hidden again as soon as another presentation is selected.
    And I set the field "Presentation type" to "List"
    Then "Test presentation box color" "select" should not be visible

  @javascript
  Scenario: Smartmenu: Providers - Show the settings of a location provider only if it is selected
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    Then "Test location label" "field" should not be visible
    # The settings of the location provider are not shown as long as only other locations are selected.
    And I set the field "Menu location(s)" to "Main navigation"
    Then "Test location label" "field" should not be visible
    And I set the field "Menu location(s)" to "Main navigation, Menu bar, User menu"
    Then "Test location label" "field" should not be visible
    And I set the field "Menu location(s)" to "Main navigation, Test location"
    Then "Test location label" "field" should be visible
    # The settings of the location provider do not depend on the selected presentation.
    And I set the field "Presentation type" to "Test presentation"
    Then "Test location label" "field" should be visible
    # The settings of the location provider are hidden again as soon as the location is deselected.
    And I set the field "Menu location(s)" to "Main navigation"
    Then "Test location label" "field" should not be visible

  @javascript
  Scenario: Smartmenu: Providers - Show the more menu behavior setting only if a location is selected which supports it
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    # The setting is not shown as long as no location is selected.
    Then "More menu behavior" "select" should not be visible
    # The main navigation and the menu bar collapse their menus into a "More" menu, so they support the setting.
    And I set the field "Menu location(s)" to "Main navigation"
    Then "More menu behavior" "select" should be visible
    And I set the field "Menu location(s)" to "Menu bar"
    Then "More menu behavior" "select" should be visible
    # The user menu does not have a "More" menu and the test location does not confirm that it supports the setting.
    And I set the field "Menu location(s)" to "User menu"
    Then "More menu behavior" "select" should not be visible
    And I set the field "Menu location(s)" to "Test location"
    Then "More menu behavior" "select" should not be visible
    And I set the field "Menu location(s)" to "User menu, Test location"
    Then "More menu behavior" "select" should not be visible
    # The setting is shown as soon as at least one of the selected locations supports it.
    And I set the field "Menu location(s)" to "User menu, Test location, Menu bar"
    Then "More menu behavior" "select" should be visible

  @javascript
  Scenario: Smartmenu: Providers - Store the default more menu behavior if no location is selected which supports the setting
    Given the following "theme_boost_union > smart menu" exists:
      | title            | Forced menu          |
      | location         | Main navigation      |
      | moremenubehavior | Force into more menu |
    When I log in as "admin"
    And I navigate to smart menus
    And I click on ".action-edit" "css_element" in the "Forced menu" "table_row"
    Then the field "More menu behavior" matches value "Force into more menu"
    # As the more menu behavior is hidden for the user menu, it is not submitted and the menu is stored
    # with the default more menu behavior.
    And I set the field "Menu location(s)" to "User menu"
    Then "More menu behavior" "select" should not be visible
    And I click on "Save and return" "button"
    And I click on ".action-edit" "css_element" in the "Forced menu" "table_row"
    And I set the field "Menu location(s)" to "Main navigation"
    Then the field "More menu behavior" matches value "Do not change anything"

  @javascript
  Scenario: Smartmenu: Providers - Store the settings of the providers of a menu and of a menu item
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title             | Boxes                          |
      | Menu location(s)  | Main navigation, Test location |
      | Presentation type | Test presentation              |
    And I set the following fields to these values:
      | Test presentation box color | Green    |
      | Test location label         | My label |
    And I click on "Save and configure items" "button"
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title                  | Boxed item         |
      | Menu item type         | Static             |
      | Menu item URL          | https://moodle.org |
      | Test presentation note | My note            |
    And I click on "Save changes" "button"
    Then I should see "Boxed item" in the "smartmenus_items" "table"
    And I click on ".action-edit" "css_element" in the "Boxed item" "table_row"
    Then the field "Test presentation note" matches value "My note"
    And I navigate to smart menus
    Then I should see "Test location" in the "Boxes" "table_row"
    And I should see "Test presentation" in the "Boxes" "table_row"
    And I click on ".action-edit" "css_element" in the "Boxes" "table_row"
    Then the field "Test presentation box color" matches value "Green"
    And the field "Test location label" matches value "My label"

  Scenario: Smartmenu: Providers - Add the menu item settings of a presentation provider only to the menu items of menus with this presentation
    Given the following "theme_boost_union > smart menus" exist:
      | title     | location        | presentation      |
      | Boxes     | Main navigation | Test presentation |
      | List menu | Main navigation | List              |
    When I log in as "admin"
    # The presentation of a menu item is determined by its menu and cannot be changed in the menu item form.
    # So, the settings of the other presentation providers are not hidden there, they are not added to the form at all.
    And I navigate to smart menu "Boxes" items
    And I click on "Add menu item" "button"
    Then "Test presentation note" "field" should exist
    And I navigate to smart menu "List menu" items
    And I click on "Add menu item" "button"
    Then "Test presentation note" "field" should not exist

  @javascript
  Scenario: Smartmenu: Providers - Keep the settings of the providers of a menu as long as the providers are not selected
    Given the following "theme_boost_union > smart menu" exists:
      | title            | Boxes                                       |
      | location         | Main navigation, Test location              |
      | locationdata     | {"testlocation": {"label": "My label"}}     |
      | presentation     | Test presentation                           |
      | presentationdata | {"testpresentation": {"boxcolor": "green"}} |
    When I log in as "admin"
    And I navigate to smart menus
    # Deselect the test location and the test presentation and save the menu.
    And I click on ".action-edit" "css_element" in the "Boxes" "table_row"
    And I set the following fields to these values:
      | Menu location(s)  | Main navigation |
      | Presentation type | List            |
    And I click on "Save and return" "button"
    Then I should not see "Test location" in the "Boxes" "table_row"
    And I should not see "Test presentation" in the "Boxes" "table_row"
    # The settings of the providers have not been dropped. As soon as the providers are selected again,
    # their settings are shown with the former values.
    And I click on ".action-edit" "css_element" in the "Boxes" "table_row"
    And I set the following fields to these values:
      | Menu location(s)  | Main navigation, Test location |
      | Presentation type | Test presentation              |
    Then the field "Test presentation box color" matches value "Green"
    And the field "Test location label" matches value "My label"

  @javascript
  Scenario: Smartmenu: Providers - Validate that the presentation provider supports the selected location providers
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    # The test presentation does not support the menu bar.
    And I set the following fields to these values:
      | Title             | Boxes                     |
      | Menu location(s)  | Main navigation, Menu bar |
      | Presentation type | Test presentation         |
    And I click on "Save and return" "button"
    Then I should see "The presentation type 'Test presentation' cannot be used at the location 'Menu bar'."
    # The menu can be saved as soon as the unsupported location is deselected.
    And I set the field "Menu location(s)" to "Main navigation"
    And I click on "Save and return" "button"
    Then I should see "Boxes" in the "smartmenus" "table"

  @javascript
  Scenario: Smartmenu: Providers - Validate that the selected location providers support the inline mode
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    # The menu mode is hidden for the test presentation, so the list presentation is used to test the inline mode.
    # The inline mode is refused as soon as one of the selected locations does not support it.
    And I set the following fields to these values:
      | Title             | Inline menu                    |
      | Menu location(s)  | Main navigation, Test location |
      | Presentation type | List                           |
      | Menu mode         | Inline                         |
    And I click on "Save and return" "button"
    Then I should see "The location 'Test location' does not support the inline mode."
    # The menu can be saved as soon as only locations are selected which support the inline mode.
    And I set the field "Menu location(s)" to "Main navigation"
    And I click on "Save and return" "button"
    Then I should see "Inline menu" in the "smartmenus" "table"

  @javascript
  Scenario: Smartmenu: Providers - Validate the settings of a location provider of a menu only if the provider is selected
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    # The test location refuses the label 'invalid'.
    And I set the following fields to these values:
      | Title            | Labelled menu                  |
      | Menu location(s) | Main navigation, Test location |
    And I set the field "Test location label" to "invalid"
    And I click on "Save and return" "button"
    Then I should see "The test location label is invalid."
    # The settings of a provider which is not selected are not validated.
    And I set the field "Menu location(s)" to "Main navigation"
    And I click on "Save and return" "button"
    Then I should not see "The test location label is invalid."
    And I should see "Labelled menu" in the "smartmenus" "table"
    # As soon as the provider is selected again, a valid label can be saved.
    And I click on ".action-edit" "css_element" in the "Labelled menu" "table_row"
    And I set the field "Menu location(s)" to "Main navigation, Test location"
    And I set the field "Test location label" to "valid"
    And I click on "Save and return" "button"
    Then I should see "Test location" in the "Labelled menu" "table_row"

  @javascript
  Scenario: Smartmenu: Providers - Validate the settings of a presentation provider of a menu item
    Given the following "theme_boost_union > smart menu" exists:
      | title        | Boxes             |
      | location     | Main navigation   |
      | presentation | Test presentation |
    When I log in as "admin"
    And I navigate to smart menu "Boxes" items
    And I click on "Add menu item" "button"
    # The test presentation refuses the note 'invalid'.
    And I set the following fields to these values:
      | Title                  | Boxed item         |
      | Menu item type         | Static             |
      | Menu item URL          | https://moodle.org |
      | Test presentation note | invalid            |
    And I click on "Save changes" "button"
    Then I should see "The test presentation note is invalid."
    And I set the field "Test presentation note" to "valid"
    And I click on "Save changes" "button"
    Then I should not see "The test presentation note is invalid."
    And I should see "Boxed item" in the "smartmenus_items" "table"

  @javascript
  Scenario: Smartmenu: Providers - Render a menu with a presentation provider in the built-in locations and in the location of a location provider
    # The menu is assigned to all built-in locations and to the test location and uses the test presentation.
    # The test presentation renders the menu items as boxes (instead of as list) and marks the boxes with the location
    # for which they have been rendered. The menu and the menu item carry settings of the providers:
    # The label of the test location, the box color and the note of the test presentation.
    # (The test presentation does not support the menu bar. The menu form would refuse this combination,
    # but the generator does not care about that).
    Given the following "theme_boost_union > smart menu" exists:
      | title            | Boxes                                               |
      | location         | Main navigation, Menu bar, User menu, Test location |
      | locationdata     | {"testlocation": {"label": "My label"}}             |
      | presentation     | Test presentation                                   |
      | presentationdata | {"testpresentation": {"boxcolor": "green"}}         |
    And the following "theme_boost_union > smart menu item" exists:
      | menu             | Boxes                                     |
      | title            | Boxed item                                |
      | itemtype         | Static                                    |
      | url              | https://moodle.org                        |
      | presentationdata | {"testpresentation": {"note": "My note"}} |
    # A second menu with the list presentation serves as countercheck.
    And the following "theme_boost_union > smart menu" exists:
      | title    | List menu                                 |
      | location | Main navigation, User menu, Test location |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | List menu          |
      | title    | List item          |
      | itemtype | Static             |
      | url      | https://moodle.com |
    When I log in as "admin"
    # The test location:
    # It adds its body class and renders its menus into a template slot at the end of the page. The menu is shown with
    # the label from the settings of the location and its content has been rendered by the test presentation
    # (with the note from the settings of the presentation for the menu item).
    Then the "class" attribute of "body" "css_element" should contain "theme-boost-union-testlocation"
    And ".theme-boost-union-testlocation-menus .testpresentation-boxes[data-location='testlocation']" "css_element" should exist
    And I should see "Boxes (My label)" in the ".theme-boost-union-testlocation-menus" "css_element"
    And I should see "My note" in the ".theme-boost-union-testlocation-menus" "css_element"
    # The main navigation (a built-in location):
    # The dropdown of the menu shows the boxes of the test presentation instead of the list of menu items. The menu
    # carries the class for the box color from the settings of the presentation for the menu.
    And I click on "Boxes" "link" in the ".primary-navigation" "css_element"
    Then ".primary-navigation .testpresentation-green .testpresentation-boxes[data-location='main']" "css_element" should be visible
    And I should see "Boxed item" in the ".primary-navigation .testpresentation-boxes" "css_element"
    And I should see "My note" in the ".primary-navigation .testpresentation-boxes" "css_element"
    # The user menu (a built-in location):
    # In the user menu, smart menus are always shown as list, regardless of their presentation.
    # So, the submenu of the menu shows the menu item as list item there, without the boxes and without the note.
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Boxes" "link" in the "#usermenu-carousel" "css_element"
    Then "Boxed item" "link" in the "#usermenu-carousel .carousel-item.active" "css_element" should be visible
    And "#usermenu-carousel .testpresentation-boxes" "css_element" should not exist
    And I should not see "My note" in the "#usermenu-carousel" "css_element"
    And I press the escape key
    # The menu bar (a built-in location which is not supported by the test presentation):
    # The menu is shown there nevertheless, but as list.
    And I click on "Boxes" "link" in the ".boost-union-menubar" "css_element"
    Then "Boxed item" "link" in the ".boost-union-menubar" "css_element" should be visible
    And ".boost-union-menubar .testpresentation-boxes" "css_element" should not exist
    And I press the escape key
    # Countercheck: The menu with the list presentation is shown as list in all locations,
    # its menu item is not rendered within the boxes of the test presentation anywhere on the page.
    And I should see "List item" in the ".theme-boost-union-testlocation-menus" "css_element"
    And I click on "List menu" "link" in the ".primary-navigation" "css_element"
    Then "List item" "link" in the ".primary-navigation" "css_element" should be visible
    And "//div[contains(@class, 'testpresentation-boxes')]//*[contains(text(), 'List item')]" "xpath_element" should not exist

  Scenario: Smartmenu: Providers - Render a menu with a presentation provider without JavaScript
    # The main navigation has two rendering paths: With JavaScript, it is rendered by the React component (which is
    # tested in the scenario above). Without JavaScript, the fallback markup from the Mustache templates is shown,
    # which has to output the content of the presentation on its own. As the React component replaces this fallback
    # markup as soon as it runs, the fallback can only be tested in a scenario without JavaScript.
    Given the following "theme_boost_union > smart menu" exists:
      | title        | Boxes             |
      | location     | Main navigation   |
      | presentation | Test presentation |
    And the following "theme_boost_union > smart menu item" exists:
      | menu             | Boxes                                     |
      | title            | Boxed item                                |
      | itemtype         | Static                                    |
      | url              | https://moodle.org                        |
      | presentationdata | {"testpresentation": {"note": "My note"}} |
    # A second menu with the list presentation serves as countercheck.
    And the following "theme_boost_union > smart menu" exists:
      | title    | List menu       |
      | location | Main navigation |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | List menu          |
      | title    | List item          |
      | itemtype | Static             |
      | url      | https://moodle.com |
    When I log in as "admin"
    Then ".primary-navigation .testpresentation-boxes[data-location='main']" "css_element" should exist
    And I should see "Boxed item" in the ".primary-navigation .testpresentation-boxes" "css_element"
    And I should see "My note" in the ".primary-navigation .testpresentation-boxes" "css_element"
    # Countercheck: The menu with the list presentation is shown as list,
    # its menu item is not rendered within the boxes of the test presentation.
    And I should see "List item" in the ".primary-navigation" "css_element"
    And "//div[contains(@class, 'testpresentation-boxes')]//*[contains(text(), 'List item')]" "xpath_element" should not exist

  @javascript
  Scenario: Smartmenu: Providers - Render a menu with a presentation provider as list in the mobile navigation drawer
    # The main navigation is shown in a drawer on small screens. This drawer is built as a collapsible list,
    # so smart menus are always shown as list there, regardless of their presentation.
    Given the following "theme_boost_union > smart menu" exists:
      | title        | Boxes             |
      | location     | Main navigation   |
      | presentation | Test presentation |
    And the following "theme_boost_union > smart menu item" exists:
      | menu             | Boxes                                     |
      | title            | Boxed item                                |
      | itemtype         | Static                                    |
      | url              | https://moodle.org                        |
      | presentationdata | {"testpresentation": {"note": "My note"}} |
    When I log in as "admin"
    And I change window size to "mobile"
    And I click on "Side panel" "button"
    And I click on "Boxes" "link" in the ".drawer-primary" "css_element"
    Then "Boxed item" "link" in the ".drawer-primary" "css_element" should be visible
    And ".drawer-primary .testpresentation-boxes" "css_element" should not exist
    And I should not see "My note" in the ".drawer-primary" "css_element"

  @javascript
  Scenario: Smartmenu: Providers - Flag the providers of a menu which are not available anymore and keep them when the menu is saved
    # This scenario covers the case that a plugin which provides a location or a presentation is uninstalled (or disabled)
    # while there are still menus which use its providers. Such a menu must not break the page, the admin must be able
    # to see what is wrong, and the stored providers must not get lost silently as soon as the menu is edited.
    # Otherwise, the menu would not come back in its former shape when the plugin is installed again.
    Given the following "theme_boost_union > smart menu" exists:
      | title        | Boxes                          |
      | location     | Main navigation, Test location |
      | presentation | Test presentation              |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Boxes              |
      | title    | Boxed item         |
      | itemtype | Static             |
      | url      | https://moodle.org |
    # Simulate that the plugin which provides the test location and the test presentation has been uninstalled.
    And the following config values are set as admin:
      | config                        | value | plugin            |
      | behat_smartmenu_testproviders | 0     | theme_boost_union |
    When I log in as "admin"
    # The menu is not shown at the missing location anymore and is shown as list in the main navigation.
    Then ".theme-boost-union-testlocation-menus" "css_element" should not exist
    And the "class" attribute of "body" "css_element" should not contain "theme-boost-union-testlocation"
    And I click on "Boxes" "link" in the ".primary-navigation" "css_element"
    Then "Boxed item" "link" should be visible
    And ".testpresentation-boxes" "css_element" should not exist
    # In the list of smart menus, the available location is shown as usual and the missing location is flagged
    # with a warning badge. For the presentation, the list presentation which is used as fallback is shown together
    # with a warning badge for the missing presentation.
    And I navigate to smart menus
    Then "//span[contains(@class, 'bg-primary')][text()='Main navigation']" "xpath_element" should exist in the "Boxes" "table_row"
    And "//span[contains(@class, 'bg-warning')][text()='testlocation (not available)']" "xpath_element" should exist in the "Boxes" "table_row"
    And "//span[contains(@class, 'bg-primary')][text()='List']" "xpath_element" should exist in the "Boxes" "table_row"
    And "//span[contains(@class, 'bg-warning')][text()='testpresentation (not available)']" "xpath_element" should exist in the "Boxes" "table_row"
    # In the menu form, the missing providers are kept as (flagged) options and are still selected.
    And I click on ".action-edit" "css_element" in the "Boxes" "table_row"
    Then I should see "Main navigation" in the "#fitem_id_location .form-autocomplete-selection" "css_element"
    And I should see "testlocation (not available)" in the "#fitem_id_location .form-autocomplete-selection" "css_element"
    And the field "Presentation type" matches value "testpresentation (not available)"
    # If the menu is saved without touching the location and the presentation, the missing providers remain stored.
    And I set the field "Title" to "Renamed boxes"
    And I click on "Save and return" "button"
    Then I should see "Renamed boxes" in the "smartmenus" "table"
    And "//span[contains(@class, 'bg-warning')][text()='testlocation (not available)']" "xpath_element" should exist in the "Renamed boxes" "table_row"
    And "//span[contains(@class, 'bg-warning')][text()='testpresentation (not available)']" "xpath_element" should exist in the "Renamed boxes" "table_row"
    # Simulate that the plugin which provides the test location and the test presentation has been installed again.
    # The menu comes back in its former shape: It is shown at the test location and with the test presentation again
    # and the providers are not flagged anymore.
    And the following config values are set as admin:
      | config                        | value | plugin            |
      | behat_smartmenu_testproviders | 1     | theme_boost_union |
    And I reload the page
    Then "//span[contains(@class, 'bg-primary')][text()='Test location']" "xpath_element" should exist in the "Renamed boxes" "table_row"
    And "//span[contains(@class, 'bg-primary')][text()='Test presentation']" "xpath_element" should exist in the "Renamed boxes" "table_row"
    And I should not see "(not available)" in the "Renamed boxes" "table_row"
    And ".theme-boost-union-testlocation-menus .testpresentation-boxes[data-location='testlocation']" "css_element" should exist
    And I click on "Renamed boxes" "link" in the ".primary-navigation" "css_element"
    Then ".primary-navigation .testpresentation-boxes[data-location='main']" "css_element" should be visible

  @javascript
  Scenario: Smartmenu: Providers - Hide the menu mode if the selected presentation provider does not support the inline mode
    Given the following "theme_boost_union > smart menu" exists:
      | title        | Boxes             |
      | location     | Main navigation   |
      | presentation | Test presentation |
      | mode         | Inline            |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Boxes              |
      | title    | Boxed item         |
      | itemtype | Static             |
      | url      | https://moodle.org |
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    Then "Menu mode" "select" should be visible
    And I set the field "Presentation type" to "Test presentation"
    Then "Menu mode" "select" should not be visible
    And I set the field "Presentation type" to "List"
    Then "Menu mode" "select" should be visible
    # An existing menu with the inline mode can still be saved and is shown as submenu.
    And I navigate to smart menus
    And I click on ".action-edit" "css_element" in the "Boxes" "table_row"
    Then "Menu mode" "select" should not be visible
    And I set the field "Title" to "Renamed boxes"
    And I click on "Save and return" "button"
    Then I should see "Renamed boxes" in the "smartmenus" "table"
    And I click on "Renamed boxes" "link" in the ".primary-navigation" "css_element"
    Then ".primary-navigation .testpresentation-boxes[data-location='main']" "css_element" should be visible
    And I should see "Boxed item" in the ".primary-navigation .testpresentation-boxes" "css_element"
    # The menu has been stored with the submenu mode as the menu mode was hidden.
    And I navigate to smart menus
    And I click on ".action-edit" "css_element" in the "Renamed boxes" "table_row"
    And I set the field "Presentation type" to "List"
    Then the field "Menu mode" matches value "Submenu"

  @javascript
  Scenario: Smartmenu: Providers - Validate that the location provider supports the selected presentation provider
    # Restrict the test location to the list presentation.
    Given the following config values are set as admin:
      | config                                | value | plugin            |
      | behat_smartmenu_testlocation_listonly | 1     | theme_boost_union |
    When I log in as "admin"
    And I navigate to smart menus
    And I click on "Create menu" "button"
    And I set the following fields to these values:
      | Title             | Boxes             |
      | Menu location(s)  | Test location     |
      | Presentation type | Test presentation |
    And I click on "Save and return" "button"
    Then I should see "The presentation type 'Test presentation' cannot be used at the location 'Test location'."
    And I set the following fields to these values:
      | Presentation type | List |
    And I click on "Save and return" "button"
    Then I should see "Boxes" in the "smartmenus" "table"

  @javascript
  Scenario: Smartmenu: Providers - Do not offer the menu item mode if the presentation provider of the menu does not support the submenu mode of menu items
    Given the following "courses" exist:
      | fullname      | shortname |
      | Nested course | NC1       |
    And the following "theme_boost_union > smart menus" exist:
      | title     | location        | presentation      |
      | Boxes     | Main navigation | Test presentation |
      | List menu | Main navigation | List              |
    # The test presentation does not support the submenu mode of menu items.
    # The menu item form would not offer the submenu mode for this menu, but the generator does not care about that.
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Boxes           |
      | title    | Old courses     |
      | itemtype | Dynamic courses |
      | itemmode | Submenu         |
    When I log in as "admin"
    # The menu item mode is not offered for a new menu item.
    And I navigate to smart menu "Boxes" items
    And I click on "Add menu item" "button"
    And I set the field "Menu item type" to "Dynamic courses"
    Then "Dynamic courses: Course category" "text" should be visible
    And "Menu item mode" "select" should not exist
    # The menu item mode is not offered for the existing menu item either.
    # As soon as the menu item is saved, it is set to the inline mode and its courses are shown in the menu.
    And I navigate to smart menu "Boxes" items
    And I click on ".action-edit" "css_element" in the "Old courses" "table_row"
    Then "Menu item mode" "select" should not exist
    And I click on "Save changes" "button"
    Then I should see "Old courses" in the "smartmenus_items" "table"
    And I click on "Boxes" "link" in the ".primary-navigation" "css_element"
    Then I should see "Nested course" in the ".primary-navigation .testpresentation-boxes" "css_element"
    # The list presentation supports the submenu mode of menu items, so the menu item mode is offered.
    And I navigate to smart menu "List menu" items
    And I click on "Add menu item" "button"
    And I set the field "Menu item type" to "Dynamic courses"
    Then "Menu item mode" "select" should be visible

  @javascript
  Scenario: Smartmenu: Providers - Present a menu item in submenu mode with a presentation provider which supports the submenu mode of menu items
    Given the following "courses" exist:
      | fullname      | shortname |
      | Nested course | NC1       |
    And the following "theme_boost_union > smart menu" exists:
      | title        | Nested menu                       |
      | location     | Main navigation                   |
      | presentation | Submenu capable test presentation |
    When I log in as "admin"
    And I navigate to smart menu "Nested menu" items
    And I click on "Add menu item" "button"
    And I set the following fields to these values:
      | Title          | Nested courses  |
      | Menu item type | Dynamic courses |
      | Menu item mode | Submenu         |
    And I click on "Save changes" "button"
    Then I should see "Nested courses" in the "smartmenus_items" "table"
    And I click on "Nested menu" "link" in the ".primary-navigation" "css_element"
    Then I should see "Nested courses" in the ".primary-navigation .testsubmenupresentation-list .testsubmenupresentation-submenutitle" "css_element"
    And I should see "Nested course" in the ".primary-navigation .testsubmenupresentation-list .testsubmenupresentation-submenu" "css_element"
