@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_scss_snippets
Feature: Configuring the theme_boost_union plugin on the "SCSS Snippets" page.
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  @javascript
  Scenario: SCSS snippets: Overview page when no snippet sources are enabled.
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then I should see "There aren't any SCSS snippets which can be used"

  @javascript
  Scenario: SCSS snippets: Overview page when builtin-snippets are enabled.
    Given the following config values are set as admin:
      | config                | value | plugin            |
      | enablebuiltinsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then I should not see "There aren't any SCSS snippets which can be used"
    And I should see "Rainbow navbar"

  @javascript @_file_upload
  Scenario: SCSS snippets: Overview page with uploaded snippets.
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | enableuploadedsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then I should see "There aren't any SCSS snippets which can be used"
    And I click on "Settings" "link" in the "#region-main .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/snippets.zip" file to "Upload snippets" filemanager
    And I press "Save changes"
    And I click on "Overview" "link" in the "#region-main .nav-tabs" "css_element"
    Then I should not see "There aren't any SCSS snippets which can be used"
    And I should see "Darken hover background color in secondary menu items"
