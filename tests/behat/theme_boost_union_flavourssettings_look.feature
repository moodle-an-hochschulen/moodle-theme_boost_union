@theme @theme_boost_union @theme_boost_union_flavourssettings @theme_boost_union_flavourssettings_look
Feature: Configuring the theme_boost_union plugin on the "Flavours" page, applying the look features
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  # Unfortunately, this can't be tested with Behat yet as the full logo is not displayed anywhere outside the login page
  # Scenario: Flavours: Logo - Upload a logo (with a global logo not having been uploaded before)

  # Unfortunately, this can't be tested with Behat yet as the full logo is not displayed anywhere outside the login page
  # Scenario: Flavours: Logo - Upload a logo (with a global logo being overridden)

  # Unfortunately, this can't be tested with Behat yet as the full logo is not displayed anywhere outside the login page
  # Scenario: Flavours: Logo - Do not upload a logo (with a global logo being served properly)

  @javascript @_file_upload
  Scenario: Flavours: Compact logo - Upload a compact logo (with a global compact logo not having been uploaded before)
    When I log in as "admin"
    And I navigate to "Appearance > Themes > Boost Union > Flavours" in site administration
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
    And I navigate to "Appearance > Themes > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/moodlelogo.png" file to "Compact logo" filemanager
    And I click on "Save changes" "button"
    And I navigate to "Appearance > Themes > Boost Union > Flavours" in site administration
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
    And I navigate to "Appearance > Themes > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/moodlelogo.png" file to "Compact logo" filemanager
    And I click on "Save changes" "button"
    And I navigate to "Appearance > Themes > Boost Union > Flavours" in site administration
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
    And I navigate to "Appearance > Themes > Boost Union > Flavours" in site administration
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
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/favicon.ico" file to "Favicon" filemanager
    And I press "Save changes"
    And I navigate to "Appearance > Themes > Boost Union > Flavours" in site administration
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
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/favicon.ico" file to "Favicon" filemanager
    And I click on "Save changes" "button"
    And I navigate to "Appearance > Themes > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I set the field "Title" to "My shiny new flavour"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I click on ".action-preview" "css_element" in the "My shiny new flavour" "table_row"
    # We can't check the uploaded image file visually, but we can verify that the favicon is shipped from the theme_boost_union global favicon filearea.
    Then "//head//link[contains(@rel, 'shortcut')][contains(@href, 'pluginfile.php/1/theme_boost_union/favicon')][contains(@href, 'favicon.ico')]" "xpath_element" should exist

  # Unfortunately, this can't be tested with Behat yet as the background image is added via external CSS which can't be referenced with XPath
  # Scenario: Flavours: Background image - Upload a background image (with a global background image not having been uploaded before)

  # Unfortunately, this can't be tested with Behat yet as the background image is added via external CSS which can't be referenced with XPath
  # Scenario: Flavours: Background image - Upload a background image (with a global background image being overridden)

  # Unfortunately, this can't be tested with Behat yet as the background image is added via external CSS which can't be referenced with XPath
  # Scenario: Flavours: Background image - Do not upload a background image (with a global background image being served properly)

  @javascript
  Scenario: Flavours: Custom SCSS - Add custom SCSS to the page
    When I log in as "admin"
    And I navigate to "Appearance > Themes > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I set the field "Title" to "My shiny new flavour"
    # We add a small CSS snippet to the flavour which hides the heading in the page header.
    # This is just to make it easy to detect the effect of this flavour.
    And I set the field "Custom CSS" to multiline:
    """
    #page-header h1 { display: none; }
    """
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I click on ".action-preview" "css_element" in the "My shiny new flavour" "table_row"
    Then I should not see "Preview flavour" in the "#page-header .page-header-headings" "css_element"
