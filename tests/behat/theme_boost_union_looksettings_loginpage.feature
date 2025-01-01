@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_loginpage
Feature: Configuring the theme_boost_union plugin for the "Login page" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  @javascript
  Scenario: Setting: Login page background images - Do not upload any login background image
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of "body" "css_element" should contain "path-login"
    And the "class" attribute of "body" "css_element" should not contain "loginbackgroundimage"
    And the "class" attribute of "body" "css_element" should not contain "loginbackgroundimage1"
    And DOM element "body" should have computed style "background-image" "none"

  @javascript @_file_upload
  Scenario: Setting: Login page background images - Upload one custom login background image
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Login page" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Login page background images" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of "body" "css_element" should contain "path-login"
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage"
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage1"
    And DOM element "body" should have computed style "background-size" "cover"
    And DOM element "body" should have background image with file name "login_bg1.png"

  @javascript @_file_upload
  Scenario: Setting: Login page background images - Upload multiple custom login background image (and have one picked randomly)
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Login page" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Login page background images" filemanager
    And I upload "theme/boost_union/tests/fixtures/login_bg2.png" file to "Login page background images" filemanager
    And I upload "theme/boost_union/tests/fixtures/login_bg3.png" file to "Login page background images" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of "body" "css_element" should contain "path-login"
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage"
    # There isn't a real possibility to test the randomness of the login background picking.
    # However, the random image picking function is designed to detect Behat runs and will then always ship the
    # image matching the number of uploaded images (i.e. if you upload 3 images, you will get the third).
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage3"
    And DOM element "body" should have computed style "background-size" "cover"
    And DOM element "body" should have background image with file name "login_bg3.png"

  @javascript @_file_upload
  Scenario Outline: Setting: Login page background images - Define the background image position.
    Given the following config values are set as admin:
      | config                       | value      | plugin            |
      | loginbackgroundimageposition | <position> | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Login page" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Login page background images" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then DOM element "body.pagelayout-login" should have computed style "background-position" "<cssvalue>"

    # We do not want to burn too much CPU time by testing all available options. We just test the default value and one non-default value.
    Examples:
      | position      | cssvalue |
      | center center | 50% 50%  |
      | left top      | 0% 0%    |

  @javascript @_file_upload
  Scenario: Setting: Display text for login background images - Add a text to the login background image
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Login page" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Login page background images" filemanager
    And I set the field "Display text for login background images" to "login_bg1.png|Copyright by SplitShire on pexels.com|dark"
    And I press "Save changes"
    And Behat debugging is enabled
    And I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then I should see "Copyright by SplitShire on pexels.com" in the "#loginbackgroundimagetext" "css_element"

  @javascript @_file_upload
  Scenario Outline: Setting: Display text for login background images - Match the text to the filename
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Login page" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Login page background images" filemanager
    And I set the field "Display text for login background images" to "<filename>.png|Copyright by SplitShire on pexels.com|dark"
    And I press "Save changes"
    And Behat debugging is enabled
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
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Login page" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Login page background images" filemanager
    And I set the field "Display text for login background images" to "login_bg1.png|Copyright by SplitShire on pexels.com|<color>"
    And I press "Save changes"
    And Behat debugging is enabled
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

  Scenario Outline: Setting: Local login
    Given the following config values are set as admin:
      | config                | value     | plugin            |
      | loginlocalloginenable | <setting> | theme_boost_union |
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then "form#login" "css_element" <shouldornot> exist

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: Local login - View the side entrance login page
    Given the following config values are set as admin:
      | config                | value     | plugin            |
      | loginlocalloginenable | <setting> | theme_boost_union |
    When I am on local login page
    Then I <shouldornot1> see "Local login"
    And ".login-heading" "css_element" <shouldornot1> exist
    And "#username" "css_element" <shouldornot1> exist
    And "#password" "css_element" <shouldornot1> exist
    And "#loginbtn" "css_element" <shouldornot1> exist
    And I <shouldornot2> see "The local login is enabled on the standard login form"

    Examples:
      | setting | shouldornot1 | shouldornot2 |
      | yes     | should not   | should       |
      | no      | should       | should not   |

  Scenario: Setting: Local login - Use the side entrance login page
    Given the following config values are set as admin:
      | config                | value | plugin            |
      | loginlocalloginenable | no    | theme_boost_union |
    When I am on local login page
    And I set the following fields to these values:
    # With behat, the password is always the same as the username.
      | Username | admin |
      | Password | admin |
    And I press "Log in"
    Then I should see "Welcome, Admin" in the "page-header" "region"

  Scenario Outline: Setting: Local login intro
    Given the following config values are set as admin:
      | config              | value     | plugin            |
      | loginlocalshowintro | <setting> | theme_boost_union |
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then "#theme_boost_union-loginorder-local .login-heading" "css_element" <shouldornot> exist

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: IDP login intro
    Given the following config values are set as admin:
      | config            | value     | plugin            |
      | loginidpshowintro | <setting> | theme_boost_union |
    And the following config values are set as admin:
        | config | value         |
        | auth   | manual,oauth2 |
    And I log in as "admin"
    And I navigate to "Server > OAuth 2 services" in site administration
    And I press "Google"
    And I should see "Create new service: Google"
    And I set the following fields to these values:
      | Name          | Testing service   |
      | Client ID     | thisistheclientid |
      | Client secret | supersecret       |
    And I press "Save changes"
    And I log out
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then ".login-identityproviders .login-heading" "css_element" <shouldornot> exist

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  @javascript
  # JavaScript is necessary here to be able to evaluate the result of the flexbox orders.
  Scenario Outline: Setting: Login order
    Given the following config values are set as admin:
      | config                    | value                         | plugin            |
      | loginorderlocal           | <localordersetting>           | theme_boost_union |
      | loginorderidp             | <idpordersetting>             | theme_boost_union |
      | loginorderfirsttimesignup | <firsttimesignupordersetting> | theme_boost_union |
      | loginorderguest           | <guestordersetting>           | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    And the following config values are set as admin:
      | config           | value               |
      | auth             | manual,email,oauth2 |
      | registerauth     | email               |
      | guestloginbutton | 1                   |
    And I log in as "admin"
    And I navigate to "Server > OAuth 2 services" in site administration
    And I press "Google"
    And I should see "Create new service: Google"
    And I set the following fields to these values:
      | Name          | Testing service   |
      | Client ID     | thisistheclientid |
      | Client secret | supersecret       |
    And I press "Save changes"
    And I log out
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    # We would have loved to test the visual order with the 'x should appear after / before y' step, but this step
    # does really only check the orders in the DOM and not on the screen.
    # So we just check if the 'order' properties are set correctly
    Then DOM element "#theme_boost_union-loginorder" should have computed style "display" "<display>"
    And DOM element "#theme_boost_union-loginorder" should have computed style "flex-direction" "<flexdirection>"
    And DOM element "#theme_boost_union-loginorder-local" should have computed style "order" "<localorderbrowser>"
    And DOM element "#theme_boost_union-loginorder-idp" should have computed style "order" "<idporderbrowser>"
    And DOM element "#theme_boost_union-loginorder-firsttimesignup" should have computed style "order" "<firsttimesignuporderbrowser>"
    And DOM element "#theme_boost_union-loginorder-guest" should have computed style "order" "<guestorderbrowser>"

    Examples:
      | localordersetting | localorderbrowser | idpordersetting | idporderbrowser | firsttimesignupordersetting | firsttimesignuporderbrowser | guestordersetting | guestorderbrowser | display | flexdirection |
      | 1                 | 0                 | 2               | 0               | 3                           | 0                           | 4                 | 0                 | block   | row           |
      | 2                 | 2                 | 1               | 1               | 4                           | 4                           | 3                 | 3                 | flex    | column        |
