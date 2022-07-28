@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_branding
Feature: Configuring the theme_boost_union plugin for the "Branding" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | student1 |
      | teacher1 |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  @javascript @_file_upload
  Scenario: Setting: Favicon - Upload a custom favicon
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Branding" "link"
    And I upload "theme/boost_union/tests/fixtures/favicon.ico" file to "Favicon" filemanager
    And I press "Save changes"
    # We can't check the uploaded favicon visually, but we can verify that the site's favicon is not shipped by pluginfile.php (for uploaded files) and not by theme/image.php (for image files from disk) anymore.
    Then "//head//link[contains(@rel, 'shortcut')][contains(@href, 'pluginfile.php/1/theme_boost_union/favicon')][contains(@href, 'favicon.ico')]" "xpath_element" should exist
    And "//head//link[contains(@rel, 'shortcut')][contains(@href, 'theme/image.php/boost_union/theme')][contains(@href, 'favicon')]" "xpath_element" should not exist

  @javascript @_file_upload
  Scenario: Setting: Favicon - Do not upload a custom favicon (countercheck)
    When I log in as "admin"
    Then "//head//link[contains(@rel, 'shortcut')][contains(@href, 'theme/image.php/boost_union/theme')][contains(@href, 'favicon')]" "xpath_element" should exist
    And "//head//link[contains(@rel, 'shortcut')][contains(@href, 'pluginfile.php/1/theme_boost_union/favicon')][contains(@href, 'favicon.ico')]" "xpath_element" should not exist

  @javascript @_file_upload
  # The setting is designed to display multiple login background images in a random order.
  # The randomness is the reason why we cannot test it here with more than one image.
  Scenario: Use Login page background images
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Branding" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg.jpg" file to "Login page background images" filemanager
    And I press "Save changes"
    And I log out
    And I click on "Log in" "link"
    And I am on homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of "body" "css_element" should contain "path-login"
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage1"

  # Dependent on setting "Use Login page background images"
  @javascript @_file_upload
  Scenario: Display text for login background images
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Branding" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg.jpg" file to "Login page background images" filemanager
    And I set the field "id_s_theme_boost_union_loginbackgroundimagetext" to "login_bg.jpg|Copyright by SplitShire on pexels.com"
    And I press "Save changes"
    And I log out
    And I click on "Log in" "link"
    And I am on homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of "body" "css_element" should contain "path-login"
    And I should see "Copyright by SplitShire on pexels.com" in the "#loginbackgroundimagetext" "css_element"

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Bootstrap color for "Success" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Bootstrap color for "Info" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Bootstrap color for "Warning" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Bootstrap color for "Danger" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon color for "Administration" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon color for "Assessment" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon color for "Collaboration" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon color for "Communication" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon color for "Content" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon color for "Interface" - Setting the color
