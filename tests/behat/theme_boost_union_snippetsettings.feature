@theme @theme_boost_union @theme_boost_union_snippetssettings
Feature: Configuring the theme_boost_union plugin on the "SCSS Snippets" page.
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  @javascript
  Scenario: SCSS snippets: Overview page - No snippet sources are enabled.
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then I should see "There aren't any SCSS snippets which can be used"

  @javascript
  Scenario: SCSS snippets: Settings - Builtin-snippets are enabled.
    Given the following config values are set as admin:
      | config                | value | plugin            |
      | enablebuiltinsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then I should not see "There aren't any SCSS snippets which can be used"
    And I should see "Rainbow navbar"

  @javascript @_file_upload
  Scenario: SCSS snippets: Settings - Uploaded snippets are enabled and a ZIP file is uploaded.
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | enableuploadedsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then I should see "There aren't any SCSS snippets which can be used"
    And I click on "Settings" "link" in the "#region-main .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/snippets/snippets_sort.zip" file to "Upload snippets" filemanager
    And I press "Save changes"
    And I click on "Overview" "link" in the "#region-main .nav-tabs" "css_element"
    Then I should not see "There aren't any SCSS snippets which can be used"
    And I should see "First snippet"
    And I should see "Second snippet"

  @javascript @_file_upload
  Scenario: SCSS snippets: Settings - Uploaded snippets are enabled and individual snippet files are uploaded.
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | enableuploadedsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then I should see "There aren't any SCSS snippets which can be used"
    And I click on "Settings" "link" in the "#region-main .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/snippets/snippet_third.scss" file to "Upload snippets" filemanager
    And I upload "theme/boost_union/tests/fixtures/snippets/snippet_fourth.scss" file to "Upload snippets" filemanager
    And I press "Save changes"
    And I click on "Overview" "link" in the "#region-main .nav-tabs" "css_element"
    Then I should not see "There aren't any SCSS snippets which can be used"
    And I should see "Third snippet"
    And I should see "Fourth snippet"

  @javascript @_file_upload
  Scenario: SCSS snippets: Settings - Uploaded snippets are enabled and a ZIP file plus individual snippet files are uploaded.
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | enableuploadedsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then I should see "There aren't any SCSS snippets which can be used"
    And I click on "Settings" "link" in the "#region-main .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/snippets/snippets_sort.zip" file to "Upload snippets" filemanager
    And I upload "theme/boost_union/tests/fixtures/snippets/snippet_third.scss" file to "Upload snippets" filemanager
    And I upload "theme/boost_union/tests/fixtures/snippets/snippet_fourth.scss" file to "Upload snippets" filemanager
    And I press "Save changes"
    And I click on "Overview" "link" in the "#region-main .nav-tabs" "css_element"
    Then I should not see "There aren't any SCSS snippets which can be used"
    And I should see "First snippet"
    And I should see "Second snippet"
    And I should see "Third snippet"
    And I should see "Fourth snippet"

  @javascript @_file_upload
  Scenario: SCSS snippets: Settings - Uploaded snippets are enabled and duplicate new snippets replace existing snippets.
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | enableuploadedsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    And I click on "Settings" "link" in the "#region-main .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/snippets/snippets_sort.zip" file to "Upload snippets" filemanager
    And I upload "theme/boost_union/tests/fixtures/snippets/first.scss" file to "Upload snippets" filemanager
    And I press "Save changes"
    And I click on "Overview" "link" in the "#region-main .nav-tabs" "css_element"
    Then I should not see "There aren't any SCSS snippets which can be used"
    And I should see "First snippet"
    And I should see "Second snippet"
    And I should not see "First original snippet"

  @javascript @_file_upload
  Scenario: SCSS snippets: Settings - Uploaded snippets are enabled and duplicate names in uploaded + builtin snippets are possible.
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | enablebuiltinsnippets  | yes   | theme_boost_union |
      | enableuploadedsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    And I click on "Settings" "link" in the "#region-main .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/snippets/rainbow_navbar.scss" file to "Upload snippets" filemanager
    And I press "Save changes"
    And I click on "Overview" "link" in the "#region-main .nav-tabs" "css_element"
    Then I should not see "There aren't any SCSS snippets which can be used"
    And I should see "Boost Union built-in" in the "Rainbow navbar" "table_row"
    And I should see "Upload" in the "Rainbow duplicate" "table_row"

  @javascript @_file_upload
  Scenario: SCSS snippets: Settings - Uploaded snippets are enabled and the ZIP from the SCSS snippets repository is uploaded, using the snippets from the subdirectories and dropping the boilerplate.
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | enableuploadedsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then I should see "There aren't any SCSS snippets which can be used"
    And I click on "Settings" "link" in the "#region-main .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/snippets/moodle-theme_boost_union_snippets-main.zip" file to "Upload snippets" filemanager
    And I press "Save changes"
    And I click on "Overview" "link" in the "#region-main .nav-tabs" "css_element"
    Then I should not see "There aren't any SCSS snippets which can be used"
    And I should see "Darken hover background color in secondary menu items"
    And I should not see "<snippet title>"
    And I should not see "oilerplate"

  @javascript @_file_upload
  Scenario: SCSS snippets: Overview page - Snippets are enabled / disabled.
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | enableuploadedsnippets | yes   | theme_boost_union |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    And I click on "Settings" "link" in the "#region-main .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/snippets/snippets_sort.zip" file to "Upload snippets" filemanager
    And I press "Save changes"
    And I am on "Course 1" course homepage
    And I should see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    And I click on the "Enable" link in the table row containing "First snippet"
    And I am on "Course 1" course homepage
    And I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"

  @javascript @_file_upload
  Scenario: SCSS snippets: Overview page - Snippets are sorted.
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | enableuploadedsnippets | yes   | theme_boost_union |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    And I click on "Settings" "link" in the "#region-main .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/snippets/snippets_sort.zip" file to "Upload snippets" filemanager
    And I press "Save changes"
    And I click on "Overview" "link" in the "#region-main .nav-tabs" "css_element"
    And I click on the "Enable" link in the table row containing "First snippet"
    And I click on the "Enable" link in the table row containing "Second snippet"
    And "First snippet" "text" should appear before "Second snippet" "text"
    And I am on "Course 1" course homepage
    And I should see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    And I click on the "Up" link in the table row containing "Second snippet"
    Then "First snippet" "text" should appear after "Second snippet" "text"
    And I am on "Course 1" course homepage
    And I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario: SCSS snippets: Settings - The snippet metadata is shown on the overview page and in a modal.
    Given the following config values are set as admin:
      | config                | value | plugin            |
      | enablebuiltinsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then I should see "Rainbow navbar"
    And I should see "Boost Union built-in" in the "Rainbow navbar" "table_row"
    And I should see "Eye candy" in the "Rainbow navbar" "table_row"
    And I should see "Global" in the "Rainbow navbar" "table_row"
    And I click on the "Show details" link in the table row containing "Rainbow navbar"
    And ".modal-dialog" "css_element" should be visible
    And I should see "Rainbow navbar" in the ".modal-title" "css_element"
    And I should see "17 May is a special day for the queer community" in the ".modal-body" "css_element"
    And I should see "Nils Promer and Alexander Brehm" in the "Creator" "table_row"
    # Unfortunately, the following does not work:
    # And I should see "Boost Union built-in" in the ".modal-body Source" "table_row"
    # And I should see "Eye candy" in the "Goal" "table_row"
    # And I should see "Global" in the "Scope" "table_row"
    # Sadly, Behat detects the string already in the main page table. But we accept that as we have verified the data in the main page table before and do not need to verify it again in the modal.
    And I should see "Firefox for Mac" in the "Tested on" "table_row"

  @javascript @_file_upload
  Scenario: SCSS snippets: Settings - The snippet usage note is shown in a modal.
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | enableuploadedsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    And I click on "Settings" "link" in the "#region-main .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/snippets/snippet_third.scss" file to "Upload snippets" filemanager
    And I upload "theme/boost_union/tests/fixtures/snippets/snippet_usagenote.scss" file to "Upload snippets" filemanager
    And I press "Save changes"
    And I click on "Overview" "link" in the "#region-main .nav-tabs" "css_element"
    And I click on the "Show details" link in the table row containing "Third snippet"
    And ".modal-dialog" "css_element" should be visible
    And I should not see "Usage note" in the ".modal-body" "css_element"
    And I reload the page
    And I click on the "Show details" link in the table row containing "Snippet with usage note"
    And ".modal-dialog" "css_element" should be visible
    And I should see "Usage note" in the ".modal-body" "css_element"
    And I should see "Lorem ipsum" in the ".modal-body" "css_element"

  @javascript
  Scenario: SCSS snippets: Settings - The snippet preview image is shown in a modal.
    Given the following config values are set as admin:
      | config                | value | plugin            |
      | enablebuiltinsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then I should see "Rainbow navbar"
    And I click on the "Show details" link in the table row containing "Rainbow navbar"
    And ".modal-dialog" "css_element" should be visible
    # For an unknown reason, this does not work:
    # And I should see "Preview" in the ".modal-body h6" "css_element"
    And I should see "Preview" in the ".modal-body" "css_element"
    And ".modal-body img[alt=\"Preview\"]" "css_element" should exist

  @javascript
  Scenario: SCSS snippets: Settings - The snippet SCSS code is shown in a modal.
    Given the following config values are set as admin:
      | config                | value | plugin            |
      | enablebuiltinsnippets | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > SCSS snippets" in site administration
    Then I should see "Rainbow navbar"
    And I click on the "Show details" link in the table row containing "Rainbow navbar"
    And ".modal-dialog" "css_element" should be visible
    And I should not see ".primary-navigation .nav-item .nav-link"
    And I should not see "@copyright"
    And I click on "Show the SCSS code" "link"
    And I should see ".primary-navigation .nav-item .nav-link"
    And I should see "@copyright"
