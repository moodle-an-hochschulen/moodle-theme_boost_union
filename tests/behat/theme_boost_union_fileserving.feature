@theme @theme_boost_union @theme_boost_union_fileserving
Feature: Serving the files of the theme_boost_union plugin
  In order to see the theme files
  As a user
  I need to get the theme files served properly

  Background:
    Given the following config values are set as admin:
      | enablemyhome      | 1           |
      | forcelogin        | 1           |
      | sitepolicyhandler | tool_policy |
    And the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | COHORT1  |
    And the following "users" exist:
      | username |
      | user1    |
    And the following "cohort members" exist:
      | cohort  | user  |
      | COHORT1 | user1 |
    # Create a flavour which applies to the cohort.
    And I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I select "Yes" from the "Apply to cohorts" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocohorts_ids" "css_element"
    And I click on "Cohort 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I should see "Flavours" in the ".admin_settingspage_tabs_with_tertiary .dropdown-toggle" "css_element"

  @javascript @_file_upload
  Scenario: File serving: Flavour compact logo is served on the site policy page (with the site policy not being accepted yet)
    Given I click on ".action-edit" "css_element" in the "My shiny new flavour" "table_row"
    And I should see "Edit flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I upload "theme/boost_union/tests/fixtures/flavourlogo.png" file to "Compact logo" filemanager
    And I click on "Save changes" "button"
    And I should see "Flavours" in the ".admin_settingspage_tabs_with_tertiary .dropdown-toggle" "css_element"
    And I log out
    And the following policies exist:
      | Name             | Revision | Content    | Summary     | Status |
      | This site policy |          | full text2 | short text2 | active |
    When I log in as "user1"
    Then I should see "This site policy"
    # The flavour compact logo must not only be referenced in the HTML source, it must be served properly as well.
    And "//nav//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/flavours_look_logocompact')][contains(@src, 'flavourlogo.png')]" "xpath_element" should exist
    And DOM element "nav img.logo" should be a successfully loaded image

  @javascript @_file_upload
  Scenario: File serving: User is redirected to the dashboard after accepting the site policy (and not to a flavour file)
    Given I click on ".action-edit" "css_element" in the "My shiny new flavour" "table_row"
    And I should see "Edit flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I upload "theme/boost_union/tests/fixtures/flavourlogo.png" file to "Compact logo" filemanager
    And I click on "Save changes" "button"
    And I should see "Flavours" in the ".admin_settingspage_tabs_with_tertiary .dropdown-toggle" "css_element"
    And I log out
    And the following policies exist:
      | Name             | Revision | Content    | Summary     | Status |
      | This site policy |          | full text2 | short text2 | active |
    When I log in as "user1"
    And I should see "This site policy"
    And I press "Next"
    And I set the field "I agree to the This site policy." to "1"
    And I press "Next"
    # Serving the flavour files must not have overwritten the URL which the user wanted to visit initially.
    Then the url should match "/my/"
    And I should see "Dashboard"

  @javascript @_file_upload
  Scenario: File serving: Flavour background image is served on the site policy page (with the site policy not being accepted yet)
    Given I click on ".action-edit" "css_element" in the "My shiny new flavour" "table_row"
    And I should see "Edit flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I upload "theme/boost_union/tests/fixtures/login_bg2.png" file to "Background image" filemanager
    And I click on "Save changes" "button"
    And I should see "Flavours" in the ".admin_settingspage_tabs_with_tertiary .dropdown-toggle" "css_element"
    And I log out
    And the following policies exist:
      | Name             | Revision | Content    | Summary     | Status |
      | This site policy |          | full text2 | short text2 | active |
    When I log in as "user1"
    Then I should see "This site policy"
    # The flavour background image must not only be referenced in the CSS, it must be served properly as well.
    And DOM element "body" should have background image with file name "login_bg2.png"
    And DOM element "body" should have a successfully loaded background image

  @javascript @_file_upload
  Scenario: File serving: Flavour compact logo is served on a regular page (with the forcelogin setting being enabled)
    Given I click on ".action-edit" "css_element" in the "My shiny new flavour" "table_row"
    And I should see "Edit flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I upload "theme/boost_union/tests/fixtures/flavourlogo.png" file to "Compact logo" filemanager
    And I click on "Save changes" "button"
    And I should see "Flavours" in the ".admin_settingspage_tabs_with_tertiary .dropdown-toggle" "css_element"
    And I log out
    When I log in as "user1"
    Then I should see "Dashboard"
    And "//nav//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/flavours_look_logocompact')][contains(@src, 'flavourlogo.png')]" "xpath_element" should exist
    And DOM element "nav img.logo" should be a successfully loaded image
