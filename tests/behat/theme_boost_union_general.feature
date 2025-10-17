@theme @theme_boost_union @theme_boost_union_general @theme_boost_union_general_admin
Feature: Configuring the theme_boost_union plugin as admin
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  @javascript
  Scenario: Redirect the user from the theme selector page to the Boost Union settings overview page
    When I log in as "admin"
    And I follow "Site administration"
    And I navigate to "Appearance > Themes" in site administration
    And I click on "#theme-settings-boost_union" "css_element" in the "#theme-card-boost_union" "css_element"
    And I should see "Look" in the ".card-body" "css_element"
    And I should see "Settings for branding your Moodle site"
    And I should see "Settings overview" in the ".breadcrumb" "css_element"

  @javascript
  Scenario: Allow admins to use the tertiary navigation to navigate between the individual Boost Union admin pages
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Quick links     |
      | location | Main navigation |
    And the following "theme_boost_union > smart menu item" exists:
      | menu        | Quick links        |
      | title       | Resources          |
      | itemtype    | Static             |
      | url         | https://moodle.org |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    Then "body#page-admin-setting-theme_boost_union_look" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should be visible
    And I should see "Look" in the ".admin_settingspage_tabs_with_tertiary .dropdown-toggle" "css_element"
    And "h2:has(+ .admin_settingspage_tabs_with_tertiary)" "css_element" should not be visible
    # We just need to test the navigation to the 'feel' page as a representative of all theme admin pages.
    And I set the field "List of Boost Union settings pages" to "Feel"
    Then "body#page-admin-setting-theme_boost_union_feel" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should be visible
    And I should see "Feel" in the ".admin_settingspage_tabs_with_tertiary .dropdown-toggle" "css_element"
    And "h2:has(+ .admin_settingspage_tabs_with_tertiary)" "css_element" should not be visible
    And Behat debugging is enabled
    # However, we have to test the 'flavours' page as well as this is an external admin page.
    And I set the field "List of Boost Union settings pages" to "Flavours"
    Then "body#page-admin-theme-boost_union-flavours-overview" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should be visible
    And I should see "Flavours" in the ".admin_settingspage_tabs_with_tertiary .dropdown-toggle" "css_element"
    And "h2:has(+ .admin_settingspage_tabs_with_tertiary)" "css_element" should not be visible
    # And we have to test the 'snippets' page as well as this is an external admin page.
    And I set the field "List of Boost Union settings pages" to "SCSS snippets"
    Then "body#page-admin-theme-boost_union-snippets-overview" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should be visible
    And I should see "SCSS snippets" in the ".admin_settingspage_tabs_with_tertiary .dropdown-toggle" "css_element"
    And "h2:has(+ .admin_settingspage_tabs_with_tertiary)" "css_element" should not be visible
    # And we have to test the 'smart menus' page as well as this is an external admin page.
    And I set the field "List of Boost Union settings pages" to "Smart menus"
    Then "body#page-admin-theme-boost_union-smartmenus-menus" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should be visible
    And I should see "Smart menus" in the ".admin_settingspage_tabs_with_tertiary .dropdown-toggle" "css_element"
    And "h2:has(+ .admin_settingspage_tabs_with_tertiary)" "css_element" should not be visible
    # And we have to test the 'smart menus items' page as well as this is an external admin page.
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    Then "body#page-admin-theme-boost_union-smartmenus-items" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should be visible
    And I should see "Smart menus" in the ".admin_settingspage_tabs_with_tertiary .dropdown-toggle" "css_element"
    And "h2:has(+ .admin_settingspage_tabs_with_tertiary)" "css_element" should not be visible
    # And we have to test the 'all settings on one page' page as well as this is an individual page.
    And Behat debugging is disabled
    # This step has thrown a timeout error due to the massive amount of Tiny editors which load on this page.
    # So we increased the timeout with $CFG->behat_increasetimeout via 'moodle-plugin-ci add-config'.
    And I set the field "List of Boost Union settings pages" to "All settings on one page"
    Then "body#page-admin-setting-theme_boost_union" "css_element" should exist
    And ".admin_settingspage_tabs_with_tertiary" "css_element" should not exist
    And I should see "Category: Boost Union" in the "#region-main h2" "css_element"

  @javascript
  Scenario: Show inactive-theme warning on settings overview and all Boost Union sub pages
    Given I log in as "admin"
    # First, check that the warning does NOT show when Boost Union is active.
    When I navigate to "Appearance > Boost Union > Settings overview" in site administration
    Then I should not see "Boost Union (or a child theme of Boost Union) is currently not the active theme"
    # Then, ensure that Boost Union is NOT active anymore (switch to Boost).
    And I navigate to "Appearance > Themes" in site administration
    And I click on "Select theme" "button" in the "#theme-select-form-boost" "css_element"
    # The Settings overview page should now show the warning.
    When I navigate to "Appearance > Boost Union > Settings overview" in site administration
    Then "Boost Union (or a child theme of Boost Union) is currently not the active theme" "text" should appear after "Boost Union settings overview" "text"
    # Look
    When Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And Behat debugging is enabled
    Then "Boost Union (or a child theme of Boost Union) is currently not the active theme" "text" should appear after ".admin_settingspage_tabs_with_tertiary" "css_element"
    # Feel
    When I navigate to "Appearance > Boost Union > Feel" in site administration
    Then "Boost Union (or a child theme of Boost Union) is currently not the active theme" "text" should appear after ".admin_settingspage_tabs_with_tertiary" "css_element"
    # Content
    When Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Content" in site administration
    And Behat debugging is enabled
    Then "Boost Union (or a child theme of Boost Union) is currently not the active theme" "text" should appear after ".admin_settingspage_tabs_with_tertiary" "css_element"
    # Functionality
    When I navigate to "Appearance > Boost Union > Functionality" in site administration
    Then "Boost Union (or a child theme of Boost Union) is currently not the active theme" "text" should appear after ".admin_settingspage_tabs_with_tertiary" "css_element"
    # Accessibility
    When I navigate to "Appearance > Boost Union > Accessibility" in site administration
    Then "Boost Union (or a child theme of Boost Union) is currently not the active theme" "text" should appear after ".admin_settingspage_tabs_with_tertiary" "css_element"
    # Flavours
    When I navigate to "Appearance > Boost Union > Flavours" in site administration
    Then "Boost Union (or a child theme of Boost Union) is currently not the active theme" "text" should appear after ".admin_settingspage_tabs_with_tertiary" "css_element"
    # SCSS snippets
    When I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then "Boost Union (or a child theme of Boost Union) is currently not the active theme" "text" should appear after ".admin_settingspage_tabs_with_tertiary" "css_element"
    # SCSS snippets > Settings
    And I click on "Settings" "link" in the "#region-main .nav-tabs" "css_element"
    Then "Boost Union (or a child theme of Boost Union) is currently not the active theme" "text" should appear after ".admin_settingspage_tabs_with_tertiary" "css_element"
    # Smart menus
    When I navigate to "Appearance > Boost Union > Smart menus" in site administration
    Then "Boost Union (or a child theme of Boost Union) is currently not the active theme" "text" should appear after ".admin_settingspage_tabs_with_tertiary" "css_element"

  @javascript
  Scenario: Switch to the active Boost Union admin sub-tab after saving a setting and the following page reload
    When I log in as "admin"
    And I follow "Site administration"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Page" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "Course content max width" to "600px"
    And I click on "Save changes" "button"
    And Behat debugging is enabled
    Then I should see "Course content max width" in the ".tab-content" "css_element"
    And "#theme_boost_union_look_page.tab-pane.active" "css_element" should exist
    And "#theme_boost_union_look_page.tab-pane:not(.active)" "css_element" should not exist
    And "#theme_boost_union_look_general.tab-pane.active" "css_element" should not exist
    And "#theme_boost_union_look_general.tab-pane:not(.active)" "css_element" should exist
