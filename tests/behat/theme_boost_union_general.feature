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
