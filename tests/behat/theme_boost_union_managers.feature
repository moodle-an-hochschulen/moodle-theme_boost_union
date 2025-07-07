@theme @theme_boost_union @theme_boost_union_managers
Feature: Configuring the theme_boost_union plugin as manager
  In order to use the features
  As manager
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | manager  |
    Given the following "system role assigns" exist:
      | user    | role    | contextlevel |
      | manager | manager | System       |

  Scenario: Capabilities - Allow managers to configure Boost Union
    Given the following "permission overrides" exist:
      | capability                  | permission | role    | contextlevel | reference |
      | theme/boost_union:configure | Allow      | manager | System       |           |
    And I log in as "manager"
    And I follow "Site administration"
    Then ".secondary-navigation li[data-key='appearance']" "css_element" should exist
    # We just need to test the 'look' page as a representative of all theme admin pages.
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And "body#page-admin-setting-theme_boost_union_look" "css_element" should exist
    And I should see "Look" in the "#region-main" "css_element"
    And I should see "General settings" in the "#region-main" "css_element"
    And Behat debugging is enabled
    # However, we have to test the 'flavours' page as well as this is an external admin page.
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And "body#page-admin-theme-boost_union-flavours-overview" "css_element" should exist
    And I should see "Flavours" in the "#region-main" "css_element"
    And I should see "Create flavour" in the "#region-main" "css_element"
    # However, we have to test the 'snippets' page as well as this is an external admin page.
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    And "body#page-admin-theme-boost_union-snippets-overview" "css_element" should exist
    And I should see "SCSS snippets" in the "#region-main" "css_element"
    And I should see "There aren't any SCSS snippets which can be used" in the "#region-main" "css_element"
    # And we have to test the 'smart menus' page as well as this is an external admin page.
    And I navigate to "Appearance > Boost Union > Smart menus" in site administration
    And "body#page-admin-theme-boost_union-smartmenus-menus" "css_element" should exist
    And I should see "Smart menus" in the "#region-main" "css_element"
    And I should see "Create menu" in the "#region-main" "css_element"
    # And we have to test the 'settings overview' page as well as this is an individual page.
    And I navigate to "Appearance > Boost Union > Settings overview" in site administration
    And "body#page-theme-boost_union-settings_overview" "css_element" should exist
    And I should see "Boost Union settings overview" in the "#region-main" "css_element"

  Scenario: Capabilities - Do not allow managers to configure Boost Union (countercheck)
    Given the following "permission overrides" exist:
      | capability                  | permission | role    | contextlevel | reference |
      | theme/boost_union:configure | Prevent    | manager | System       |           |
    And I log in as "manager"
    And I follow "Site administration"
    Then ".secondary-navigation li[data-key='appearance']" "css_element" should not exist
