@theme @theme_boost_union @theme_boost_union_flavourssettings @theme_boost_union_flavourssettings_look
Feature: Configuring the theme_boost_union plugin on the "Flavours" page, applying the look features
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "categories" exist:
      | name   | category | idnumber |
      | Cat 1  | 0        | CAT1     |
    And the following "courses" exist:
      | fullname  | shortname | category |
      | Course 1  | C1        | CAT1     |

  # Unfortunately, this can't be tested with Behat yet as the full logo is not displayed anywhere outside the login page
  # Scenario: Flavours: Logo - Upload a logo (with a global logo not having been uploaded before)

  # Unfortunately, this can't be tested with Behat yet as the full logo is not displayed anywhere outside the login page
  # Scenario: Flavours: Logo - Upload a logo (with a global logo being overridden)

  # Unfortunately, this can't be tested with Behat yet as the full logo is not displayed anywhere outside the login page
  # Scenario: Flavours: Logo - Do not upload a logo (with a global logo being served properly)

  @javascript @_file_upload
  Scenario: Flavours: Compact logo - Upload a compact logo (with a global compact logo not having been uploaded before)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I set the field "Title" to "My shiny new flavour"
    And I upload "theme/boost_union/tests/fixtures/flavourlogo.png" file to "Compact logo" filemanager
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I click on ".action-preview" "css_element" in the "My shiny new flavour" "table_row"
    # We can't check the uploaded image file visually, but we can verify that the compact logo is shipped from the theme_boost_union flavour filearea.
    Then "//nav[contains(@class, 'navbar')]//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/flavours_look_logocompact')][contains(@src, 'flavourlogo.png')]" "xpath_element" should exist

  @javascript @_file_upload
  Scenario: Flavours: Compact logo - Upload a compact logo (with a global compact logo being overridden)
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/moodlelogo.png" file to "Compact logo" filemanager
    And I click on "Save changes" "button"
    And Behat debugging is enabled
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I set the field "Title" to "My shiny new flavour"
    And I upload "theme/boost_union/tests/fixtures/flavourlogo.png" file to "Compact logo" filemanager
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I click on ".action-preview" "css_element" in the "My shiny new flavour" "table_row"
    # We can't check the uploaded image file visually, but we can verify that the compact logo is shipped from the theme_boost_union flavour filearea.
    Then "//nav[contains(@class, 'navbar')]//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/flavours_look_logocompact')][contains(@src, 'flavourlogo.png')]" "xpath_element" should exist

  @javascript @_file_upload
  Scenario: Flavours: Compact logo - Do not upload a compact logo (with a global compact logo being served properly)
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/moodlelogo.png" file to "Compact logo" filemanager
    And I click on "Save changes" "button"
    And Behat debugging is enabled
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I set the field "Title" to "My shiny new flavour"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I click on ".action-preview" "css_element" in the "My shiny new flavour" "table_row"
    # We can't check the uploaded image file visually, but we can verify that the compact logo is shipped from the theme_boost_union global logo filearea.
    Then "//nav[contains(@class, 'navbar')]//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/logocompact')][contains(@src, 'moodlelogo.png')]" "xpath_element" should exist

  @javascript @_file_upload
  Scenario: Flavours: Favicon - Upload a favicon (with a global favicon not having been uploaded before)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I set the field "Title" to "My shiny new flavour"
    And I upload "theme/boost_union/tests/fixtures/flavourfavicon.ico" file to "Favicon" filemanager
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I click on ".action-preview" "css_element" in the "My shiny new flavour" "table_row"
    # We can't check the uploaded image file visually, but we can verify that the favicon is shipped from the theme_boost_union flavour filearea.
    Then "//head//link[contains(@rel, 'shortcut')][contains(@href, 'pluginfile.php/1/theme_boost_union/flavours_look_favicon')][contains(@href, 'flavourfavicon.ico')]" "xpath_element" should exist

  @javascript @_file_upload
  Scenario: Flavours: Favicon - Upload a favicon (with a global favicon being overridden)
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/favicon.ico" file to "Favicon" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I set the field "Title" to "My shiny new flavour"
    And I upload "theme/boost_union/tests/fixtures/flavourfavicon.ico" file to "Favicon" filemanager
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I click on ".action-preview" "css_element" in the "My shiny new flavour" "table_row"
    # We can't check the uploaded image file visually, but we can verify that the favicon is shipped from the theme_boost_union flavour filearea.
    Then "//head//link[contains(@rel, 'shortcut')][contains(@href, 'pluginfile.php/1/theme_boost_union/flavours_look_favicon')][contains(@href, 'flavourfavicon.ico')]" "xpath_element" should exist

  @javascript @_file_upload
  Scenario: Flavours: Favicon - Do not upload a favicon (with a global favicon being served properly)
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/favicon.ico" file to "Favicon" filemanager
    And I click on "Save changes" "button"
    And Behat debugging is enabled
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I set the field "Title" to "My shiny new flavour"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I click on ".action-preview" "css_element" in the "My shiny new flavour" "table_row"
    # We can't check the uploaded image file visually, but we can verify that the favicon is shipped from the theme_boost_union global favicon filearea.
    Then "//head//link[contains(@rel, 'shortcut')][contains(@href, 'pluginfile.php/1/theme_boost_union/favicon')][contains(@href, 'favicon.ico')]" "xpath_element" should exist

  @javascript @_file_upload
  Scenario: Flavours: Background image - Upload a background image (with a global background image not having been uploaded before)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I upload "theme/boost_union/tests/fixtures/login_bg2.png" file to "Background image" filemanager
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element "body" should have computed style "background-size" "cover"
    And DOM element "body" should have background image with file name "login_bg2.png"

  @javascript @_file_upload
  Scenario: Flavours: Background image - Upload a background image (with a global background image being overridden)
    Given I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Background image" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    When I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I upload "theme/boost_union/tests/fixtures/login_bg2.png" file to "Background image" filemanager
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element "body" should have computed style "background-size" "cover"
    And DOM element "body" should have background image with file name "login_bg2.png"

  @javascript @_file_upload
  Scenario: Flavours: Background image - Do not upload a background image (with a global background image being served properly)
    Given I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Background image" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    When I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element "body" should have computed style "background-size" "cover"
    And DOM element "body" should have background image with file name "login_bg1.png"

  @javascript @_file_upload
  Scenario Outline: Flavours: Background image - Define the background image position (with a global color not having been set before)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Background image" filemanager
    And I set the field "look_backgroundimagepos" to "<position>"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element "body" should have computed style "background-position" "<cssvalue>"

    # We do not want to burn too much CPU time by testing all available options. We just test one non-default value.
    Examples:
      | position      | cssvalue |
      | center center | 50% 50%  |

  @javascript @_file_upload
  Scenario Outline: Flavours: Background image - Define the background image position(with the global setting being overridden)
    Given the following config values are set as admin:
      | config                  | value            | plugin            |
      | backgroundimageposition | <globalposition> | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Background image" filemanager
    And I set the field "look_backgroundimagepos" to "<position>"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element "body" should have computed style "background-position" "<cssvalue>"
    And DOM element "body" should not have computed style "background-position" "<shouldnotcssvalue>"

    # We do not want to burn too much CPU time by testing all available options. We just test one non-default value.
    Examples:
      | globalposition | position      | cssvalue | shouldnotcssvalue |
      | bottom right   | center center | 50% 50%  | 100% 100%         |

  @javascript @_file_upload
  Scenario Outline: Flavours: Background image - Do not define the background image position (with a global setting being served properly)
    Given the following config values are set as admin:
      | config                  | value      | plugin            |
      | backgroundimageposition | <position> | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Background image" filemanager
    And I set the field "look_backgroundimagepos" to "No change"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element "body" should have computed style "background-position" "<cssvalue>"

    # We do not want to burn too much CPU time by testing all available options. We just test one non-default value.
    Examples:
      | position      | cssvalue |
      | center center | 50% 50%  |

  @javascript
  Scenario: Flavours: Brand color - Set the brand color (with a global color not having been set before)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I set the field "look_brandcolor" to "#FF0000"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And the following "activities" exist:
      | activity | name      | intro                                                     | course |
      | label    | Label one | <span class="mytesttext text-primary">My test text</span> | C1     |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "rgb(255, 0, 0)"

  @javascript
  Scenario: Flavours: Brand color - Set the brand color (with the global setting being overridden)
    Given the following config values are set as admin:
      | config     | value   | plugin            |
      | brandcolor | #FFFFFF | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I set the field "look_brandcolor" to "#FF0000"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And the following "activities" exist:
      | activity | name      | intro                                                     | course |
      | label    | Label one | <span class="mytesttext text-primary">My test text</span> | C1     |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "rgb(255, 0, 0)"

  @javascript
  Scenario: Flavours: Brand color - Do not set the brand color (with a global setting being served properly)
    Given the following config values are set as admin:
      | config     | value   | plugin            |
      | brandcolor | #FF0000 | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And the following "activities" exist:
      | activity | name      | intro                                                     | course |
      | label    | Label one | <span class="mytesttext text-primary">My test text</span> | C1     |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "rgb(255, 0, 0)"

  @javascript
  Scenario Outline: Flavours: Bootstrap colors - Set the Bootstrap colors (with a global color not having been set before)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I set the field "look_bootstrapcolor<type>" to "<colorhex>"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And the following "activities" exist:
      | activity | name      | intro                                                    | course |
      | label    | Label one | <span class="mytesttext text-<type>">My test text</span> | C1     |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "<colorrgb>"

    Examples:
      | type    | colorhex | colorrgb         |
      | success | #FF0000  | rgb(255, 0, 0)   |
      | info    | #00FF00  | rgb(0, 255, 0)   |
      | warning | #0000FF  | rgb(0, 0, 255)   |
      | danger  | #FFFF00  | rgb(255, 255, 0) |

  @javascript
  Scenario Outline: Flavours: Bootstrap colors - Set the Bootstrap colors (with the global setting being overridden)
    Given the following config values are set as admin:
      | config               | value   | plugin            |
      | bootstrapcolor<type> | #FFFFFF | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I set the field "look_bootstrapcolor<type>" to "<colorhex>"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And the following "activities" exist:
      | activity | name      | intro                                                    | course |
      | label    | Label one | <span class="mytesttext text-<type>">My test text</span> | C1     |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "<colorrgb>"

    # We only test 1 out of 4 color types as we have tested the rest already in the previous scenario.
    Examples:
      | type    | colorhex | colorrgb       |
      | success | #FF0000  | rgb(255, 0, 0) |

  @javascript
  Scenario Outline: Flavours: Bootstrap colors - Do not set the Bootstrap colors (with a global setting being served properly)
    Given the following config values are set as admin:
      | config               | value      | plugin            |
      | bootstrapcolor<type> | <colorhex> | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And the following "activities" exist:
      | activity | name      | intro                                                    | course |
      | label    | Label one | <span class="mytesttext text-<type>">My test text</span> | C1     |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "<colorrgb>"

    # We only test 1 out of 4 color types as we have tested the rest already in the previous scenario.
    Examples:
      | type    | colorhex | colorrgb       |
      | success | #FF0000  | rgb(255, 0, 0) |

  @javascript
  Scenario Outline: Flavours: Activity icon colors - Setting the color (with a global color not having been set before)
    Given the following config values are set as admin:
      | config                    | value | plugin            |
      | activityiconcolorfidelity | 500   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I set the field "look_aicol<purposename>" to "<colorhex>"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I click on "Add an activity or resource" "button" in the "New section" "section"
    # First, we test that the default filter is _not_ set anymore.
    Then DOM element ".chooser-container .activityiconcontainer.modicon_<modname> img" should not have computed style "filter" "<originalfilter>"
    # And then, as the hex color to CSS filter conversion results are not reproducible, we test if the applied filter is close enough to the hex color.
    And DOM element ".chooser-container .activityiconcontainer.modicon_<modname> img" should have a CSS filter close enough to hex color "<colorhex>"

    # Unfortunately, we can only test 4 out of 6 purpose types as Moodle does does not ship with any activity with the
    # administration and interface types. But this should be an acceptable test coverage anyway.
    Examples:
      | purposename        | modname | colorhex | originalfilter                                                                              |
      | assessment         | assign  | #FF0000  | invert(0.36) sepia(0.98) saturate(69.69) hue-rotate(315deg) brightness(0.9) contrast(1.19)  |
      | collaboration      | data    | #00FF00  | invert(0.25) sepia(0.54) saturate(62.26) hue-rotate(245deg) brightness(1) contrast(1.02)    |
      | communication      | choice  | #0000FF  | invert(0.48) sepia(0.74) saturate(48.87) hue-rotate(11deg) brightness(1.02) contrast(1.01)  |
      | content            | book    | #FFFF00  | invert(0.49) sepia(0.52) saturate(46.75) hue-rotate(156deg) brightness(0.89) contrast(1.02) |
      | interactivecontent | lesson  | #00FFFF  | invert(0.25) sepia(0.63) saturate(11.52) hue-rotate(344deg) brightness(0.94) contrast(0.91) |

  @javascript
  Scenario Outline: Flavours: Activity icon colors - Setting the color (with the global setting being overridden)
    Given the following config values are set as admin:
      | config                         | value   | plugin            |
      | activityiconcolor<purposename> | #00FFFF | theme_boost_union |
    And the following config values are set as admin:
      | config                    | value | plugin            |
      | activityiconcolorfidelity | 500   | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I set the field "look_aicol<purposename>" to "<colorhex>"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I click on "Add an activity or resource" "button" in the "New section" "section"
    # First, we test that the default filter is _not_ set anymore.
    Then DOM element ".chooser-container .activityiconcontainer.modicon_<modname> img" should not have computed style "filter" "<originalfilter>"
    # And then, as the hex color to CSS filter conversion results are not reproducible, we test if the applied filter is close enough to the hex color.
    And DOM element ".chooser-container .activityiconcontainer.modicon_<modname> img" should have a CSS filter close enough to hex color "<colorhex>"

    # We only test 1 out of 6 purpose types as we have tested the rest already in the previous scenario.
    Examples:
      | purposename | modname | colorhex | originalfilter                                                                             |
      | assessment  | assign  | #FF0000  | invert(0.36) sepia(0.98) saturate(69.69) hue-rotate(315deg) brightness(0.9) contrast(1.19) |

  @javascript
  Scenario Outline: Flavours: Activity icon colors - Do not set the color (with a global setting being served properly)
    Given the following config values are set as admin:
      | config                         | value      | plugin            |
      | activityiconcolor<purposename> | <colorhex> | theme_boost_union |
    And the following config values are set as admin:
      | config                    | value | plugin            |
      | activityiconcolorfidelity | 500   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I click on "Add an activity or resource" "button" in the "New section" "section"
    # First, we test that the default filter is _not_ set anymore.
    Then DOM element ".chooser-container .activityiconcontainer.modicon_<modname> img" should not have computed style "filter" "<originalfilter>"
    # And then, as the hex color to CSS filter conversion results are not reproducible, we test if the applied filter is close enough to the hex color.
    And DOM element ".chooser-container .activityiconcontainer.modicon_<modname> img" should have a CSS filter close enough to hex color "<colorhex>"

    # We only test 1 out of 6 purpose types as we have tested the rest already in the previous scenario.
    Examples:
      | purposename | modname | colorhex | originalfilter                                                                             |
      | assessment  | assign  | #FF0000  | invert(0.36) sepia(0.98) saturate(69.69) hue-rotate(315deg) brightness(0.9) contrast(1.19) |

  @javascript
  Scenario Outline: Setting: Navbar color - Set the navbar color (with a global color not having been set before)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I set the field "Navbar color" to "<setting>"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then the "class" attribute of ".navbar" "css_element" should contain "<classes>"

    Examples:
      | setting      | classes                 |
      | light        | navbar-light bg-white   |
      | dark         | navbar-dark bg-dark     |
      | primarylight | navbar-light bg-primary |
      | primarydark  | navbar-dark bg-primary  |

  @javascript
  Scenario Outline: Setting: Navbar color - Set the navbar color (with the global setting being overridden)
    Given the following config values are set as admin:
      | config      | value           | plugin            |
      | navbarcolor | <globalsetting> | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I set the field "Navbar color" to "<setting>"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then the "class" attribute of ".navbar" "css_element" should contain "<classes>"
    And the "class" attribute of ".navbar" "css_element" should not contain "<shouldnotclasses>"

    # We only test 1 out of 4 color types as we have tested the rest already in the previous scenario.
    Examples:
      | globalsetting | setting | classes               | shouldnotclasses    |
      | dark          | light   | navbar-light bg-white | navbar-dark bg-dark |

  @javascript
  Scenario Outline: Setting: Navbar color - Do not set the navbar color (with a global setting being served properly)
    Given the following config values are set as admin:
      | config      | value     | plugin            |
      | navbarcolor | <setting> | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    And I set the field "Navbar color" to "No change"
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then the "class" attribute of ".navbar" "css_element" should contain "<classes>"

    # We only test 1 out of 4 color types as we have tested the rest already in the previous scenario.
    Examples:
      | setting | classes             |
      | dark    | navbar-dark bg-dark |

  @javascript
  Scenario: Flavours: Raw (initial) SCSS - Add custom SCSS to the page
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "My shiny new flavour"
    # We add a SCSS variable and a small SCSS snippet to the flavour which hides the heading in the page header.
    # This is just to make it easy to detect the effect of this flavour and to verify that SCSS is compiled correctly.
    And I set the field "Raw initial SCSS" to multiline:
    """
    $myvariable: none;
    """
    And I set the field "Raw SCSS" to multiline:
    """
    #page-header h1 { display: $myvariable; }
    """
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I click on ".action-preview" "css_element" in the "My shiny new flavour" "table_row"
    Then I should not see "Preview flavour" in the "#page-header .page-header-headings" "css_element"
