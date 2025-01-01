@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_sitebranding
Feature: Configuring the theme_boost_union plugin for the "Site branding" tab on the "Look" page
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
  Scenario: Setting: Logo - Upload a custom logo to the theme
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/moodlelogo.png" file to "Logo" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    # We can't check the uploaded image file visually, but we can verify that the compact logo is shipped from the theme_boost_union global logo filearea.
    Then "//div[@id='loginlogo']//img[@id='logoimage'][contains(@src, 'pluginfile.php/1/theme_boost_union/logo')][contains(@src, 'moodlelogo.png')]" "xpath_element" should exist

  @javascript @_file_upload
  Scenario: Setting: Logo - Do not upload a custom logo to the theme (countercheck)
    When I log in as "admin"
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then "#loginlogo #logoimage" "css_element" should not exist

  @javascript @_file_upload
  Scenario: Setting: Logo - Upload a custom logo to Moodle core (countercheck)
    When I log in as "admin"
    And I navigate to "Appearance > Logos" in site administration
    And I upload "theme/boost_union/tests/fixtures/moodlelogo.png" file to "Logo" filemanager
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then "#loginlogo #logoimage" "css_element" should not exist

  @javascript @_file_upload
  Scenario: Setting: Compact logo - Upload a PNG logo to the theme and check that it is resized
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/moodlelogo.png" file to "Logo" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then "//div[@id='loginlogo']//img[@id='logoimage'][contains(@src, 'pluginfile.php/1/theme_boost_union/logo/0x200/')]" "xpath_element" should exist

  @javascript @_file_upload
  Scenario: Setting: Compact logo - Upload a SVG logo to the theme and check that it is not resized
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/moodlelogo.svg" file to "Logo" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then "//div[@id='loginlogo']//img[@id='logoimage'][contains(@src, 'pluginfile.php/1/theme_boost_union/logo/1/')]" "xpath_element" should exist

  @javascript @_file_upload
  Scenario: Setting: Compact logo - Upload a custom compact logo to the theme
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/moodlelogo.png" file to "Compact logo" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on site homepage
    # We can't check the uploaded image file visually, but we can verify that the compact logo is shipped from the theme_boost_union global logo filearea.
    Then "//nav[contains(@class, 'navbar')]//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/logocompact')][contains(@src, 'moodlelogo.png')]" "xpath_element" should exist

  @javascript @_file_upload
  Scenario: Setting: Compact logo - Do not upload a custom compact logo to the theme (countercheck)
    When I log in as "admin"
    And I am on site homepage
    Then ".navbar .logo" "css_element" should not exist

  @javascript @_file_upload
  Scenario: Setting: Compact logo - Upload a custom compact logo to Moodle core (countercheck)
    When I log in as "admin"
    And I navigate to "Appearance > Logos" in site administration
    And I upload "theme/boost_union/tests/fixtures/moodlelogo.png" file to "Compact logo" filemanager
    And I am on site homepage
    Then ".navbar .logo" "css_element" should not exist

  @javascript @_file_upload
  Scenario: Setting: Compact logo - Upload a PNG compact logo to the theme and check that it is resized
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/moodlelogo.png" file to "Compact logo" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on site homepage
    Then "//nav[contains(@class, 'navbar')]//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/logocompact/300x300/')]" "xpath_element" should exist

  @javascript @_file_upload
  Scenario: Setting: Compact logo - Upload a SVG compact logo to the theme and check that it is not resized
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/moodlelogo.svg" file to "Compact logo" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on site homepage
    Then "//nav[contains(@class, 'navbar')]//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/logocompact/1/')]" "xpath_element" should exist

  @javascript @_file_upload
  Scenario: Setting: Favicon - Upload a custom favicon to the theme
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/favicon.ico" file to "Favicon" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    # We can't check the uploaded favicon visually, but we can verify that the site's favicon is not shipped by pluginfile.php (for uploaded files) and not by theme/image.php (for image files from disk) anymore.
    Then "//head//link[contains(@rel, 'shortcut')][contains(@href, 'pluginfile.php/1/theme_boost_union/favicon')][contains(@href, 'favicon.ico')]" "xpath_element" should exist
    And "//head//link[contains(@rel, 'shortcut')][contains(@href, 'theme/image.php/boost_union')][contains(@href, 'favicon')]" "xpath_element" should not exist

  @javascript @_file_upload
  Scenario: Setting: Favicon - Do not upload a custom favicon to the theme (countercheck)
    When I log in as "admin"
    Then "//head//link[contains(@rel, 'shortcut')][contains(@href, 'theme/image.php/boost_union')][contains(@href, 'favicon')]" "xpath_element" should exist
    And "//head//link[contains(@rel, 'shortcut')][contains(@href, 'pluginfile.php/1/theme_boost_union/favicon')][contains(@href, 'favicon.ico')]" "xpath_element" should not exist

  @javascript @_file_upload
  Scenario: Setting: Favicon - Upload a custom favicon to Moodle core (countercheck)
    When I log in as "admin"
    And I navigate to "Appearance > Logos" in site administration
    And I upload "theme/boost_union/tests/fixtures/favicon.ico" file to "Favicon" filemanager
    And I am on site homepage
    Then "//head//link[contains(@rel, 'shortcut')][contains(@href, 'theme/image.php/boost_union')][contains(@href, 'favicon')]" "xpath_element" should exist
    And "//head//link[contains(@rel, 'shortcut')][contains(@href, 'pluginfile.php/1/theme_boost_union/favicon')][contains(@href, 'favicon.ico')]" "xpath_element" should not exist

  @javascript @_file_upload
  Scenario: Setting: Background image - Upload a background image.
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Background image" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on site homepage
    Then DOM element "body" should have computed style "background-size" "cover"
    And DOM element "body" should have background image with file name "login_bg1.png"

  @javascript
  Scenario: Setting: Background image - Do not upload a background image (countercheck).
    When I log in as "admin"
    And I am on site homepage
    Then DOM element "body" should have computed style "background-image" "none"

  @javascript @_file_upload
  Scenario Outline: Setting: Background image - Define the background image position.
    Given the following config values are set as admin:
      | config                  | value      | plugin            |
      | backgroundimageposition | <position> | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Site branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Background image" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on site homepage
    Then DOM element "body" should have computed style "background-position" "<cssvalue>"

    # We do not want to burn too much CPU time by testing all available options. We just test the default value and one non-default value.
    Examples:
      | position      | cssvalue |
      | center center | 50% 50%  |
      | left top      | 0% 0%    |

  @javascript
  Scenario: Setting: Brand color - Set the brand color
    Given the following config values are set as admin:
      | config     | value   | plugin            |
      | brandcolor | #FF0000 | theme_boost_union |
    And the following "activities" exist:
      | activity | name      | intro                                                     | course |
      | label    | Label one | <span class="mytesttext text-primary">My test text</span> | C1     |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "rgb(255, 0, 0)"

  @javascript
  Scenario Outline: Setting: Bootstrap colors - Set the Bootstrap colors
    Given the following config values are set as admin:
      | config               | value      | plugin            |
      | bootstrapcolor<type> | <colorhex> | theme_boost_union |
    And the following "activities" exist:
      | activity | name      | intro                                                    | course |
      | label    | Label one | <span class="mytesttext text-<type>">My test text</span> | C1     |
    And the theme cache is purged and the theme is reloaded
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

  Scenario Outline: Setting: Navbar color - Set the navbar color
    Given the following config values are set as admin:
      | config      | value     | plugin            |
      | navbarcolor | <setting> | theme_boost_union |
    When I log in as "admin"
    Then the "class" attribute of ".navbar" "css_element" should contain "<classes>"

    Examples:
      | setting      | classes                 |
      | light        | navbar-light bg-white   |
      | dark         | navbar-dark bg-dark     |
      | primarylight | navbar-light bg-primary |
      | primarydark  | navbar-dark bg-primary  |
