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

  @javascript
  Scenario: Setting: Login page background images - Upload one custom login background image
    Given the following "theme_boost_union > setting files" exist:
      | filearea             | filepath                                       |
      | loginbackgroundimage | theme/boost_union/tests/fixtures/login_bg1.png |
    And the theme cache is purged and the theme is reloaded
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of "body" "css_element" should contain "path-login"
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage"
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage1"
    And DOM element "body" should have computed style "background-size" "cover"
    And DOM element "body" should have background image with file name "login_bg1.png"

  @javascript
  Scenario: Setting: Login page background images - Upload multiple custom login background image (and have one picked randomly)
    Given the following "theme_boost_union > setting files" exist:
      | filearea             | filepath                                       |
      | loginbackgroundimage | theme/boost_union/tests/fixtures/login_bg1.png |
      | loginbackgroundimage | theme/boost_union/tests/fixtures/login_bg2.png |
      | loginbackgroundimage | theme/boost_union/tests/fixtures/login_bg3.png |
    And the theme cache is purged and the theme is reloaded
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then the "class" attribute of "body" "css_element" should contain "path-login"
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage"
    # There isn't a real possibility to test the randomness of the login background picking.
    # However, the random image picking function is designed to detect Behat runs and will then always ship the
    # image matching the number of uploaded images (i.e. if you upload 3 images, you will get the third).
    And the "class" attribute of "body" "css_element" should contain "loginbackgroundimage3"
    And DOM element "body" should have computed style "background-size" "cover"
    And DOM element "body" should have background image with file name "login_bg3.png"

  @javascript
  Scenario Outline: Setting: Login page background images - Define the background image position.
    Given the following config values are set as admin:
      | config                       | value      | plugin            |
      | loginbackgroundimageposition | <position> | theme_boost_union |
    And the following "theme_boost_union > setting files" exist:
      | filearea             | filepath                                       |
      | loginbackgroundimage | theme/boost_union/tests/fixtures/login_bg1.png |
    And the theme cache is purged and the theme is reloaded
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then DOM element "body.pagelayout-login" should have computed style "background-position" "<cssvalue>"

    # We do not want to burn too much CPU time by testing all available options. We just test the default value and one non-default value.
    Examples:
      | position      | cssvalue |
      | center center | 50% 50%  |
      | left top      | 0% 0%    |

  Scenario: Setting: Display text for login background images - Add a text to the login background image
    Given the following config values are set as admin:
      | config                   | value                                                      | plugin            |
      | loginbackgroundimagetext | login_bg1.png\|Copyright by SplitShire on pexels.com\|dark | theme_boost_union |
    And the following "theme_boost_union > setting files" exist:
      | filearea             | filepath                                       |
      | loginbackgroundimage | theme/boost_union/tests/fixtures/login_bg1.png |
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then I should see "Copyright by SplitShire on pexels.com" in the "#loginbackgroundimagetext" "css_element"

  Scenario Outline: Setting: Display text for login background images - Match the text to the filename
    Given the following config values are set as admin:
      | config                   | value                                                       | plugin            |
      | loginbackgroundimagetext | <filename>.png\|Copyright by SplitShire on pexels.com\|dark | theme_boost_union |
    And the following "theme_boost_union > setting files" exist:
      | filearea             | filepath                                       |
      | loginbackgroundimage | theme/boost_union/tests/fixtures/login_bg1.png |
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then "#loginbackgroundimagetext" "css_element" <shouldexistornot>

    Examples:
      | filename   | shouldexistornot |
      | login_bg1  | should exist     |
      | login_bg2  | should not exist |

  Scenario Outline: Setting: Display text for login background images - Set the color for the text of the login background image
    Given the following config values are set as admin:
      | config                   | value                                                         | plugin            |
      | loginbackgroundimagetext | login_bg1.png\|Copyright by SplitShire on pexels.com\|<color> | theme_boost_union |
    And the following "theme_boost_union > setting files" exist:
      | filearea             | filepath                                       |
      | loginbackgroundimage | theme/boost_union/tests/fixtures/login_bg1.png |
    When I am on site homepage
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
    # Verify that login methods exist and appear in the correct DOM order.
    Then "#theme_boost_union-loginorder-local" "css_element" should exist
    And "#theme_boost_union-loginorder-idp" "css_element" should exist
    And "#theme_boost_union-loginorder-firsttimesignup" "css_element" should exist
    And "#theme_boost_union-loginorder-guest" "css_element" should exist
    # Check DOM order: verify that elements appear in the expected sequence.
    # We check the order by verifying that each element appears before the next one in the DOM.
    And "#<firstelementid>" "css_element" should appear before "#<secondelementid>" "css_element" in the "#theme_boost_union-loginorder" "css_element"
    And "#<secondelementid>" "css_element" should appear before "#<thirdelementid>" "css_element" in the "#theme_boost_union-loginorder" "css_element"
    And "#<thirdelementid>" "css_element" should appear before "#<fourthelementid>" "css_element" in the "#theme_boost_union-loginorder" "css_element"

    Examples:
      | localordersetting | idpordersetting | firsttimesignupordersetting | guestordersetting | firstelementid                          | secondelementid                      | thirdelementid                                | fourthelementid                       |
      | 1                 | 2               | 3                           | 4                 | theme_boost_union-loginorder-local      | theme_boost_union-loginorder-idp     | theme_boost_union-loginorder-firsttimesignup  | theme_boost_union-loginorder-guest    |
      | 2                 | 1               | 4                           | 3                 | theme_boost_union-loginorder-idp        | theme_boost_union-loginorder-local    | theme_boost_union-loginorder-guest            | theme_boost_union-loginorder-firsttimesignup |

  @javascript
  Scenario Outline: Setting: Login layout tabs - Switch between tabs
    Given the following config values are set as admin:
      | config            | value            | plugin            |
      | loginlayout       | tabs             | theme_boost_union |
      | primarylogin      | <primarylogin>   | theme_boost_union |
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
    # Verify that tabs navigation exists.
    Then "#login-tabs" "css_element" should exist
    And the "role" attribute of "#login-tabs" "css_element" should contain "tablist"
    # Verify that tab links exist.
    And "#login-tab-local-tab" "css_element" should exist
    And "#login-tab-idp-tab" "css_element" should exist
    And "#login-tab-signup-tab" "css_element" should exist
    And "#login-tab-guest-tab" "css_element" should exist
    # Verify that tab panes exist.
    And "#login-tab-local" "css_element" should exist
    And "#login-tab-idp" "css_element" should exist
    And "#login-tab-signup" "css_element" should exist
    And "#login-tab-guest" "css_element" should exist
    # Verify initial state: the primary login tab is active.
    Then the "class" attribute of "#login-tab-<activetab>-tab" "css_element" should contain "active"
    And the "class" attribute of "#login-tab-<activetab>" "css_element" should contain "show"
    And the "class" attribute of "#login-tab-<activetab>" "css_element" should contain "active"
    # Click on a different tab to test switching.
    When I click on "#login-tab-<switchtotab>-tab" "css_element"
    # Verify that the clicked tab is now active and the previous tab is inactive.
    Then the "class" attribute of "#login-tab-<switchtotab>-tab" "css_element" should contain "active"
    And the "class" attribute of "#login-tab-<switchtotab>" "css_element" should contain "show"
    And the "class" attribute of "#login-tab-<switchtotab>" "css_element" should contain "active"
    And the "class" attribute of "#login-tab-<activetab>-tab" "css_element" should not contain "active"
    And the "class" attribute of "#login-tab-<activetab>" "css_element" should not contain "show"
    And the "class" attribute of "#login-tab-<activetab>" "css_element" should not contain "active"

    Examples:
      | primarylogin | activetab | switchtotab |
      | none         | local     | idp         |
      | idp          | idp       | local       |
      | firsttimesignup | signup | guest       |

  @javascript
  Scenario: Setting: Login layout accordion - Verify accordion structure and functionality
    Given the following config values are set as admin:
      | config            | value     | plugin            |
      | loginlayout       | accordion | theme_boost_union |
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
    # Verify that accordion container exists.
    Then "#theme_boost_union-loginorder" "css_element" should exist
    And the "class" attribute of "#theme_boost_union-loginorder" "css_element" should contain "accordion"
    And the "class" attribute of "#theme_boost_union-loginorder" "css_element" should contain "login-layout-accordion"
    # Verify that accordion items exist.
    And "#accordion-local-header" "css_element" should exist
    And "#accordion-idp-header" "css_element" should exist
    And "#accordion-local-content" "css_element" should exist
    And "#accordion-idp-content" "css_element" should exist
    # Verify initial state: accordion items are collapsed by default.
    Then the "class" attribute of "#accordion-local-content" "css_element" should contain "collapse"
    And the "class" attribute of "#accordion-idp-content" "css_element" should contain "collapse"
    # Click on local accordion button to open it.
    When I click on "#accordion-local-header button" "css_element"
    # Verify that local accordion is now open.
    Then the "class" attribute of "#accordion-local-content" "css_element" should contain "show"
    And the "class" attribute of "#accordion-local-header button" "css_element" should not contain "collapsed"
    # Click on IDP accordion button to open it (should close local).
    When I click on "#accordion-idp-header button" "css_element"
    # Verify that IDP accordion is now open and local is closed.
    Then the "class" attribute of "#accordion-idp-content" "css_element" should contain "show"
    And the "class" attribute of "#accordion-idp-header button" "css_element" should not contain "collapsed"
    And the "class" attribute of "#accordion-local-content" "css_element" should not contain "show"
    And the "class" attribute of "#accordion-local-header button" "css_element" should contain "collapsed"

  Scenario Outline: Setting: Enable side entrance login - View the side entrance login page
    Given the following config values are set as admin:
      | config                  | value             | plugin            |
      | loginlocalloginenable   | <loginsetting>    | theme_boost_union |
      | sideentranceloginenable | <entrancesetting> | theme_boost_union |
    When I am on local login page
    Then I <shouldornot1> see "Local login"
    And ".login-heading" "css_element" <shouldornot1> exist
    And "#username" "css_element" <shouldornot1> exist
    And "#password" "css_element" <shouldornot1> exist
    And "#loginbtn" "css_element" <shouldornot1> exist
    And I <shouldornot2> see "There is no need to log in on this side entrance login page here"

    Examples:
      | loginsetting | entrancesetting | shouldornot1 | shouldornot2 |
      | yes          | auto            | should not   | should       |
      | yes          | always          | should       | should not   |
      | no           | auto            | should       | should not   |
      | no           | always          | should       | should not   |

  Scenario: Setting: Enable side entrance login - Use the side entrance login page - Simply login
    Given the following config values are set as admin:
      | config                  | value  | plugin            |
      | sideentranceloginenable | always | theme_boost_union |
    When I am on local login page
    And I set the following fields to these values:
    # With behat, the password is always the same as the username.
      | Username | admin |
      | Password | admin |
    And I press "Log in"
    Then I should see "Welcome, Admin" in the "page-header" "region"

  Scenario Outline: Setting: Enable side entrance login - Use the side entrance login page - Visit the side entrace login page again as a already logged in user
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user  | course | role           |
      | admin | C1     | editingteacher |
    And the following config values are set as admin:
      | config            | value               |
      | alternateloginurl | <alternateloginurl> |
    And the following config values are set as admin:
      | config                  | value  | plugin            |
      | sideentranceloginenable | always | theme_boost_union |
    When I am on local login page
    And I set the following fields to these values:
    # With behat, the password is always the same as the username.
      | Username | admin |
      | Password | admin |
    And I press "Log in"
    And I am on "Course 1" course homepage
    And I should see "Course 1" in the "#page-header" "css_element"
    And I am on local login page
    And I should see "You are already logged in"

    Examples:
      | alternateloginurl |
      |                   |
      | /foo              |

  Scenario: Setting: Enable side entrance login - Use the side entrance login page - Visit the side entrace login page as a guest user
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user  | course | role           |
      | admin | C1     | editingteacher |
    And the following config values are set as admin:
      | config           | value |
      | guestloginbutton | 1     |
      | autologinguests  | 1     |
    And the following config values are set as admin:
      | config                  | value  | plugin            |
      | sideentranceloginenable | always | theme_boost_union |
    When I log in as "admin"
    And I am on the "Course 1" "enrolment methods" page
    And I click on "Edit" "link" in the "Guest access" "table_row"
    And I set the following fields to these values:
      | Allow guest access | Yes |
    And I press "Save changes"
    And I log out
    And I am on "Course 1" course homepage
    And I should see "You are currently using guest access"
    And I am on local login page
    Then "form#login" "css_element" should exist
    And I set the following fields to these values:
    # With behat, the password is always the same as the username.
      | Username | admin |
      | Password | admin |
    And I press "Log in"
    And I should see "Hi, Admin" in the "page-header" "region"
    And I should not see "You are currently using guest access"
