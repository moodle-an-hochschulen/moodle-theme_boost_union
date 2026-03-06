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

  @javascript
  Scenario Outline: Setting: Login container width
    Given the following config values are set as admin:
      | config              | value     | plugin            |
      | logincontainerwidth | <setting> | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then DOM element ".login-container" should have computed style "width" "<cssvalue>"

    Examples:
      | setting | cssvalue |
      | 600px   | 600px    |
      |         | 500px    |

  Scenario Outline: Setting: Login layout
    Given the following config values are set as admin:
      | config      | value     | plugin            |
      | loginlayout | <layout>  | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I am on site homepage
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then "#login-layout-tabs" "css_element" <tabsshouldornot> exist
    And "#login-layout-accordion" "css_element" <accordionshouldornot> exist

    Examples:
      | layout    | tabsshouldornot | accordionshouldornot |
      | vertical  | should not      | should not           |
      | tabs      | should          | should not           |
      | accordion | should not      | should               |

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
    Then "#login-method-local" "css_element" should exist
    And "#login-method-idp" "css_element" should exist
    And "#login-method-firsttimesignup" "css_element" should exist
    And "#login-method-guest" "css_element" should exist
    # Check DOM order: verify that elements appear in the expected sequence.
    # We check the order by verifying that each element appears before the next one in the DOM.
    And "#<firstelementid>" "css_element" should appear before "#<secondelementid>" "css_element" in the "#theme_boost_union-loginform" "css_element"
    And "#<secondelementid>" "css_element" should appear before "#<thirdelementid>" "css_element" in the "#theme_boost_union-loginform" "css_element"
    And "#<thirdelementid>" "css_element" should appear before "#<fourthelementid>" "css_element" in the "#theme_boost_union-loginform" "css_element"

    Examples:
      | localordersetting | idpordersetting | firsttimesignupordersetting | guestordersetting | firstelementid                    | secondelementid                   | thirdelementid                              | fourthelementid                             |
      | 1                 | 2               | 3                           | 4                 | login-method-local | login-method-idp   | login-method-firsttimesignup | login-method-guest           |
      | 2                 | 1               | 4                           | 3                 | login-method-idp   | login-method-local | login-method-guest           | login-method-firsttimesignup |

  Scenario Outline: Setting: Login methods enabled
    Given the following config values are set as admin:
      | config                      | value     | plugin            |
      | loginlocalloginenable       | <local>   | theme_boost_union |
      | loginidploginenable         | <idp>     | theme_boost_union |
      | loginselfregistrationenable | <selfreg> | theme_boost_union |
      | loginguestloginenable       | <guest>   | theme_boost_union |
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
    Then "#login-method-local" "css_element" <localshouldornot> exist
    And "#login-method-idp" "css_element" <idpshouldornot> exist
    And "#login-method-firsttimesignup" "css_element" <selfregshouldornot> exist
    And "#login-method-guest" "css_element" <guestshouldornot> exist

    Examples:
      | local | idp | selfreg | guest | localshouldornot | idpshouldornot | selfregshouldornot | guestshouldornot |
      | yes   | yes | yes     | yes   | should           | should         | should             | should           |
      | no    | no  | no      | no    | should not       | should not     | should not         | should not       |
      | yes   | no  | yes     | no    | should           | should not     | should             | should not       |

  Scenario Outline: Setting: Login intro visibility
    Given the following config values are set as admin:
      | config                         | value     | plugin            |
      | loginlocalloginenable          | yes       | theme_boost_union |
      | loginidploginenable            | yes       | theme_boost_union |
      | loginselfregistrationenable    | yes       | theme_boost_union |
      | loginguestloginenable          | yes       | theme_boost_union |
      | loginlocalshowintro            | <local>   | theme_boost_union |
      | loginidpshowintro              | <idp>     | theme_boost_union |
      | loginselfregistrationshowintro | <selfreg> | theme_boost_union |
      | loginguestshowintro            | <guest>   | theme_boost_union |
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
    Then "#login-method-local h2.login-heading" "css_element" <localshould> exist
    And "#login-method-idp h2.login-heading" "css_element" <idpshould> exist
    And "#login-method-firsttimesignup h2.login-heading" "css_element" <selfregshould> exist
    And "#login-method-guest h2.login-heading" "css_element" <guestshould> exist

    Examples:
      | local | idp | selfreg | guest | localshould | idpshould | selfregshould | guestshould |
      |       |     |         |       | should not  | should    | should        | should      |
      | yes   | yes | yes     | yes   | should      | should    | should        | should      |
      | no    | no  | no      | no    | should not  | should not| should not    | should not  |

  Scenario Outline: Setting: Login intro text
    Given the following config values are set as admin:
      | config                         | value         | plugin            |
      | loginlocalloginenable          | yes           | theme_boost_union |
      | loginidploginenable            | yes           | theme_boost_union |
      | loginselfregistrationenable    | yes           | theme_boost_union |
      | loginguestloginenable          | yes           | theme_boost_union |
      | loginlocalshowintro            | yes           | theme_boost_union |
      | loginidpshowintro              | yes           | theme_boost_union |
      | loginselfregistrationshowintro | yes           | theme_boost_union |
      | loginguestshowintro            | yes           | theme_boost_union |
      | loginlocalintrotext            | <localtext>   | theme_boost_union |
      | loginidpintrotext              | <idptext>     | theme_boost_union |
      | loginselfregistrationintrotext | <selfregtext> | theme_boost_union |
      | loginguestintrotext            | <guesttext>   | theme_boost_union |
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
    Then I should see "<localexpected>" in the "#login-method-local h2.login-heading" "css_element"
    And I should see "<idpexpected>" in the "#login-method-idp h2.login-heading" "css_element"
    And I should see "<selfregexpected>" in the "#login-method-firsttimesignup h2.login-heading" "css_element"
    And I should see "<guestexpected>" in the "#login-method-guest h2.login-heading" "css_element"

    Examples:
      | localtext | idptext | selfregtext | guesttext | localexpected                  | idpexpected                   | selfregexpected               | guestexpected                       |
      |           |         |             |           | Login with your Moodle account | Log in using your account on: | Is this your first time here? | Some courses may allow guest access |
      | Local A   | IDP A   | Selfreg A   | Guest A   | Local A                        | IDP A                         | Selfreg A                     | Guest A                             |

  @javascript
  Scenario Outline: Setting: Login layout tabs - Verify tabs structure and primarylogin functionality
    Given the following config values are set as admin:
      | config       | value          | plugin            |
      | loginlayout  | tabs           | theme_boost_union |
      | primarylogin | <primarylogin> | theme_boost_union |
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
    Then "#login-layout-tabs" "css_element" should exist
    And the "role" attribute of "#login-layout-tabs" "css_element" should contain "tablist"
    # Verify that tab links exist.
    And "#login-method-local-tab" "css_element" should exist
    And I should not see "multilang"
    And "#login-method-idp-tab" "css_element" should exist
    And "#login-method-firsttimesignup-tab" "css_element" should exist
    And "#login-method-guest-tab" "css_element" should exist
    # Verify that tab panes exist.
    And "#login-method-local" "css_element" should exist
    And "#login-method-idp" "css_element" should exist
    And "#login-method-firsttimesignup" "css_element" should exist
    And "#login-method-guest" "css_element" should exist
    # Verify initial state: the primary login tab is active.
    Then the "class" attribute of "#login-method-<activetab>-tab" "css_element" should contain "active"
    And the "class" attribute of "#login-method-<activetab>" "css_element" should contain "show"
    And the "class" attribute of "#login-method-<activetab>" "css_element" should contain "active"
    # Click on a different tab to test switching.
    When I click on "#login-method-<switchtotab>-tab" "css_element"
    # Verify that the clicked tab is now active and the previous tab is inactive.
    Then the "class" attribute of "#login-method-<switchtotab>-tab" "css_element" should contain "active"
    And the "class" attribute of "#login-method-<switchtotab>" "css_element" should contain "show"
    And the "class" attribute of "#login-method-<switchtotab>" "css_element" should contain "active"
    And the "class" attribute of "#login-method-<activetab>-tab" "css_element" should not contain "active"
    And the "class" attribute of "#login-method-<activetab>" "css_element" should not contain "show"
    And the "class" attribute of "#login-method-<activetab>" "css_element" should not contain "active"

    Examples:
      | primarylogin    | activetab       | switchtotab     |
      | none            | local           | idp             |
      | local           | local           | idp             |
      | idp             | idp             | local           |
      | firsttimesignup | firsttimesignup | guest           |
      | guest           | guest           | firsttimesignup |

  @javascript
  Scenario Outline: Setting: Login layout accordion - Verify accordion structure and primarylogin functionality
    Given the following config values are set as admin:
      | config       | value          | plugin            |
      | loginlayout  | accordion      | theme_boost_union |
      | primarylogin | <primarylogin> | theme_boost_union |
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
    Then "#login-layout-accordion" "css_element" should exist
    And the "class" attribute of "#login-layout-accordion" "css_element" should contain "accordion"
    # Verify that accordion links exist.
    And "#login-method-local-accordion-header" "css_element" should exist
    And "#login-method-idp-accordion-header" "css_element" should exist
    And "#login-method-firsttimesignup-accordion-header" "css_element" should exist
    And "#login-method-guest-accordion-header" "css_element" should exist
    # Verify that accordion panes exist.
    And "#login-method-local-accordion-content" "css_element" should exist
    And "#login-method-idp-accordion-content" "css_element" should exist
    And "#login-method-firsttimesignup-accordion-content" "css_element" should exist
    And "#login-method-guest-accordion-content" "css_element" should exist
    # Verify initial state: the primary login accordion is open (or all collapsed when none).
    Then the "class" attribute of "#login-method-<activemethod>-accordion-content" "css_element" <activeshow> "show"
    And the "class" attribute of "#login-method-<activemethod>-accordion-header button" "css_element" <activecollapsed> "collapsed"
    # Click on a different accordion button to test switching.
    When I click on "#login-method-<switchto>-accordion-header button" "css_element"
    # Verify that the clicked accordion is now open and the previous one is closed.
    Then the "class" attribute of "#login-method-<switchto>-accordion-content" "css_element" should contain "show"
    And the "class" attribute of "#login-method-<switchto>-accordion-header button" "css_element" should not contain "collapsed"
    And the "class" attribute of "#login-method-<activemethod>-accordion-content" "css_element" should not contain "show"
    And the "class" attribute of "#login-method-<activemethod>-accordion-header button" "css_element" should contain "collapsed"

    Examples:
      | primarylogin    | activemethod    | switchto        | activeshow         | activecollapsed    |
      | none            | local           | idp             | should not contain | should contain     |
      | local           | local           | idp             | should contain     | should not contain |
      | idp             | idp             | local           | should contain     | should not contain |
      | firsttimesignup | firsttimesignup | guest           | should contain     | should not contain |
      | guest           | guest           | firsttimesignup | should contain     | should not contain |

  Scenario Outline: Setting: Login layout labels
    Given the following config values are set as admin:
      | config                          | value     | plugin            |
      | loginlayout                     | <layout>  | theme_boost_union |
      | loginlocalloginenable           | yes       | theme_boost_union |
      | loginidploginenable             | yes       | theme_boost_union |
      | loginselfregistrationenable     | yes       | theme_boost_union |
      | loginguestloginenable           | yes       | theme_boost_union |
      | loginlocalloginlabel            | <local>   | theme_boost_union |
      | loginidploginlabel              | <idp>     | theme_boost_union |
      | loginselfregistrationloginlabel | <selfreg> | theme_boost_union |
      | loginguestloginlabel            | <guest>   | theme_boost_union |
    And the following config values are set as admin:
      | config           | value               |
      | auth             | manual,email,oauth2 |
      | registerauth     | email               |
      | guestloginbutton | 1                   |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
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
    Then I should see "<localexpected>" in the "<localselector>" "css_element"
    And I should see "<idpexpected>" in the "<idpselector>" "css_element"
    And I should see "<selfregexpected>" in the "<selfregselector>" "css_element"
    And I should see "<guestexpected>" in the "<guestselector>" "css_element"

    Examples:
      | layout    | local                                                                                                 | idp   | selfreg   | guest   | localexpected  | idpexpected | selfregexpected   | guestexpected | localselector                                       | idpselector                                       | selfregselector                                               | guestselector                                       |
      | tabs      | Moodle <span class="multilang" lang="en">account</span><span class="multilang" lang="de">Konto</span> |       |           |         | Moodle account | IDP login   | Self registration | Guest login   | #login-method-local-tab                             | #login-method-idp-tab                             | #login-method-firsttimesignup-tab                             | #login-method-guest-tab                             |
      | tabs      | Local A                                                                                               | IDP A | Selfreg A | Guest A | Local A        | IDP A       | Selfreg A         | Guest A       | #login-method-local-tab                             | #login-method-idp-tab                             | #login-method-firsttimesignup-tab                             | #login-method-guest-tab                             |
      | accordion | Moodle <span class="multilang" lang="en">account</span><span class="multilang" lang="de">Konto</span> |       |           |         | Moodle account | IDP login   | Self registration | Guest login   | #login-method-local-accordion-header .login-heading | #login-method-idp-accordion-header .login-heading | #login-method-firsttimesignup-accordion-header .login-heading | #login-method-guest-accordion-header .login-heading |
      | accordion | Local A                                                                                               | IDP A | Selfreg A | Guest A | Local A        | IDP A       | Selfreg A         | Guest A       | #login-method-local-accordion-header .login-heading | #login-method-idp-accordion-header .login-heading | #login-method-firsttimesignup-accordion-header .login-heading | #login-method-guest-accordion-header .login-heading |

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
