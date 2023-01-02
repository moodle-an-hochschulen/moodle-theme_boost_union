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
    And I click on "Branding" "link" in the "#adminsettings .nav-tabs" "css_element"
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

  Scenario: Setting: Login page background images - Do not upload any login background image
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of "body" "css_element" should contain "path-login"
    And the "class" attribute of "body" "css_element" should not contain "loginbackgroundimage"
    And the "class" attribute of "body" "css_element" should not contain "loginbackgroundimage1"

  @javascript @_file_upload
  Scenario: Setting: Login page background images - Upload one custom login background image
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Login page background images" filemanager
    And I press "Save changes"
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of "body" "css_element" should contain "path-login"
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage"
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage1"

  @javascript @_file_upload
  Scenario: Setting: Login page background images - Upload multiple custom login background image (and have one picked randomly)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Login page background images" filemanager
    And I upload "theme/boost_union/tests/fixtures/login_bg2.jpg" file to "Login page background images" filemanager
    And I upload "theme/boost_union/tests/fixtures/login_bg3.jpg" file to "Login page background images" filemanager
    And I press "Save changes"
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of "body" "css_element" should contain "path-login"
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage"
    # There isn't a real possibility to test the randomness of the login background picking.
    # However, the random image picking function is designed to detect Behat runs and will then always ship the
    # image matching the number of uploaded images (i.e. if you upload 3 images, you will get the third).
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage3"

  @javascript @_file_upload
  Scenario: Setting: Display text for login background images - Add a text to the login background image
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Login page background images" filemanager
    And I set the field "Display text for login background images" to "login_bg1.jpg|Copyright by SplitShire on pexels.com|dark"
    And I press "Save changes"
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then I should see "Copyright by SplitShire on pexels.com" in the "#loginbackgroundimagetext" "css_element"

  @javascript @_file_upload
  Scenario Outline: Setting: Display text for login background images - Match the text to the filename
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Login page background images" filemanager
    And I set the field "Display text for login background images" to "<filename>.jpg|Copyright by SplitShire on pexels.com|dark"
    And I press "Save changes"
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then "#loginbackgroundimagetext" "css_element" <shouldexistornot>

    Examples:
      | filename   | shouldexistornot |
      | login_bg1  | should exist     |
      | login_bg2  | should not exist |

  @javascript @_file_upload
  Scenario Outline: Setting: Display text for login background images - Set the color for the text of the login background image
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Login page background images" filemanager
    And I set the field "Display text for login background images" to "login_bg1.jpg|Copyright by SplitShire on pexels.com|<color>"
    And I press "Save changes"
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of "#loginbackgroundimagetext span" "css_element" should contain "text-<csscolor>"

    Examples:
      | color      | csscolor |
      | dark       | dark     |
      | light      | light    |
      | wrongcolor | dark     |

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
