@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_loginpage
Feature: Configuring the theme_boost_union plugin for the "Login page" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

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
    And I click on "Login page" "link" in the "#adminsettings .nav-tabs" "css_element"
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
    And I click on "Login page" "link" in the "#adminsettings .nav-tabs" "css_element"
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
    And I click on "Login page" "link" in the "#adminsettings .nav-tabs" "css_element"
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
    And I click on "Login page" "link" in the "#adminsettings .nav-tabs" "css_element"
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
    And I click on "Login page" "link" in the "#adminsettings .nav-tabs" "css_element"
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

  Scenario Outline: Setting: Login form position
    Given the following config values are set as admin:
      | config            | value     | plugin            |
      | loginformposition | <setting> | theme_boost_union |
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of ".login-wrapper" "css_element" should contain "<class>"
    And the "class" attribute of ".login-wrapper" "css_element" should not contain "<notclass1>"
    And the "class" attribute of ".login-wrapper" "css_element" should not contain "<notclass2>"

    Examples:
      | setting | class                | notclass1            | notclass2           |
      | center  | login-wrapper-center | login-wrapper-left   | login-wrapper-right |
      | left    | login-wrapper-left   | login-wrapper-center | login-wrapper-right |
      | right   | login-wrapper-right  | login-wrapper-center | login-wrapper-left  |

  Scenario Outline: Setting: Login form transparency
    Given the following config values are set as admin:
      | config                | value     | plugin            |
      | loginformtransparency | <setting> | theme_boost_union |
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of ".login-container" "css_element" <shouldcontain> "login-container-80t"

    Examples:
      | setting | shouldcontain      |
      | yes     | should contain     |
      | no      | should not contain |
