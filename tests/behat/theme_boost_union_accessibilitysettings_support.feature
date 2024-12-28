@theme @theme_boost_union @theme_boost_union_accessibilitysettings @theme_boost_union_accessibilitysettings_support
Feature: Configuring the theme_boost_union plugin for the "Support page" tab on the "Accessibility" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email               |
      | student1 | Student   | One      | student@example.com |

  # This scenario is covered withhin @theme_boost_union_contentsettings_staticpages to avoid code duplication.
  # Scenario Outline: Setting: Enable accessibility support page - Do not enable the page

  # This scenario is covered withhin @theme_boost_union_contentsettings_staticpages to avoid code duplication.
  # Scenario Outline: Setting: Enable accessibility support page - Enable and fill the page with content

  # This scenario is covered withhin @theme_boost_union_contentsettings_staticpages to avoid code duplication.
  # Scenario Outline: Setting: accessibility support page link position - Do not automatically add the page link

  # This scenario is covered withhin @theme_boost_union_contentsettings_staticpages to avoid code duplication.
  # Scenario Outline: Setting: accessibility support page link position - Add the page link to the footnote automatically (even if the footnote is empty otherwise)

  # This scenario is covered withhin @theme_boost_union_contentsettings_staticpages to avoid code duplication.
  # Scenario Outline: Setting: accessibility support page link position - Add the page link to the footnote automatically (if the footnote contains some content already)

  # This scenario is covered withhin @theme_boost_union_contentsettings_staticpages to avoid code duplication.
  # Scenario Outline: Setting: accessibility support page link position - Add the page link to the footer automatically

  # This scenario is covered withhin @theme_boost_union_contentsettings_staticpages to avoid code duplication.
  # Scenario Outline: Setting: accessibility support page link position - Add the page link to the footnote and the footer automatically

  # This scenario is covered withhin @theme_boost_union_contentsettings_staticpages to avoid code duplication.
  # Scenario Outline: Setting: accessibility support page title - Set an empty page title (and trigger the fallback string)

  # This scenario is covered withhin @theme_boost_union_contentsettings_staticpages to avoid code duplication.
  # Scenario Outline: Setting: accessibility support page title - Set a custom page title

  Scenario Outline: Setting: Enable accessibility support page - Add the screen reader link to the page
    Given the following config values are set as admin:
      | config                     | value     | plugin            |
      | enableaccessibilitysupport | <setting> | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    Then "#access-support-form-sr-link" "css_element" <shouldornot> exist

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: Allow accessibility support page without login - Check real user and guest
    Given the following config values are set as admin:
      | config                                 | value     | plugin            |
      | enableaccessibilitysupport             | yes       | theme_boost_union |
      | enableaccessibilitysupportfooterbutton | yes       | theme_boost_union |
      | accessibilitysupportlinkposition       | both      | theme_boost_union |
      | allowaccessibilitysupportwithoutlogin  | <setting> | theme_boost_union |
    When I log in as "<user>"
    Then "#access-support-form-sr-link" "css_element" <shouldornot> exist
    And "#btn-accessibility-support" "css_element" <shouldornot> exist
    And ".theme_boost_union_footnote_accessibilitysupportlink" "css_element" <shouldornot> exist
    And ".theme_boost_union_footer_accessibilitysupportlink" "css_element" <shouldornot> exist
    And I am on accessibilitysupport page
    # Logged in users will see the page, but guests will be redirected to the login page.
    Then I <shouldornot> see "Accessibility support" in the "#region-main" "css_element"

    Examples:
      | user     | setting | shouldornot |
      | guest    | yes     | should      |
      | guest    | no      | should not  |
      | student1 | yes     | should      |
      | student1 | no      | should      |

  Scenario Outline: Setting: Allow accessibility support page without login - Check login page
    Given the following config values are set as admin:
      | config                                 | value     | plugin            |
      | enableaccessibilitysupport             | yes       | theme_boost_union |
      | enableaccessibilitysupportfooterbutton | yes       | theme_boost_union |
      | accessibilitysupportlinkposition       | both      | theme_boost_union |
      | allowaccessibilitysupportwithoutlogin  | <setting> | theme_boost_union |
    When I am on login page
    Then "#access-support-form-sr-link" "css_element" <shouldornot> exist
    And "#btn-accessibility-support" "css_element" <shouldornot> exist
    And ".theme_boost_union_footnote_accessibilitysupportlink" "css_element" <shouldornot> exist
    And ".theme_boost_union_footer_accessibilitysupportlink" "css_element" <shouldornot> exist
    And I am on accessibilitysupport page
    # Logged in users will see the page, but guests will be redirected to the login page.
    Then I <shouldornot> see "Accessibility support" in the "#region-main" "css_element"

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: Enable accessibility support footer button
    Given the following config values are set as admin:
      | config                                 | value     | plugin            |
      | enableaccessibilitysupport             | yes       | theme_boost_union |
      | enableaccessibilitysupportfooterbutton | <setting> | theme_boost_union |
    When I log in as "student1"
    Then "#btn-accessibility-support" "css_element" <shouldornot> exist in the "#boost-union-footer-buttons" "css_element"

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: Allow anonymous support page submissions - Enable the setting
    Given the following config values are set as admin:
      | config                     | value     | plugin            |
      | enableaccessibilitysupport | yes       | theme_boost_union |
      | allowanonymoussubmits      | <setting> | theme_boost_union |
    When I log in as "student1"
    And I am on accessibilitysupport page
    Then "sendanonymous" "checkbox" <shouldornot> exist

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  @javascript
  Scenario: Setting: Allow anonymous support page submissions - Submit the form
    Given the following config values are set as admin:
      | config                     | value | plugin            |
      | enableaccessibilitysupport | yes   | theme_boost_union |
      | allowanonymoussubmits      | yes   | theme_boost_union |
    When I log in as "student1"
    And I am on accessibilitysupport page
    Then "Name" "field" should be visible
    And "Email address" "field" should be visible
    When I click on "sendanonymous" "checkbox"
    Then "Name" "field" should not be visible
    And "Email address" "field" should not be visible

  Scenario Outline: Setting: Allow sending technical information along - Enable the setting
    Given the following config values are set as admin:
      | config                     | value     | plugin            |
      | enableaccessibilitysupport | yes       | theme_boost_union |
      | allowsendtechinfoalong     | <setting> | theme_boost_union |
    When I log in as "student1"
    And I am on accessibilitysupport page
    Then "sendtechinfo" "checkbox" <shouldornot> exist
    Then "Technical information to send" "field" <shouldornot> exist

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  @javascript
  Scenario: Setting: Allow sending technical information along - Submit the form
    Given the following config values are set as admin:
      | config                     | value | plugin            |
      | enableaccessibilitysupport | yes   | theme_boost_union |
      | allowsendtechinfoalong     | yes   | theme_boost_union |
    When I log in as "student1"
    And I am on accessibilitysupport page
    Then "Technical information to send" "field" should be visible
    And the "Technical information to send" "field" should be readonly
    # We do not fully check the content of the field, we just check for some keywords.
    And I should see "Referrer page:" in the "Technical information to send" "field"
    And I should see "System information:" in the "Technical information to send" "field"
    When I click on "sendtechinfo" "checkbox"
    Then "Technical information to send" "field" should not be visible

  # Unfortunately, this can't be tested with Behat yet as we do not have a way to test sent emails yet.
  # Scenario: Setting: Accessibility support user mail

  Scenario Outline: Setting: Accessibility support page screenreader link title
    Given the following config values are set as admin:
      | config                              | value     | plugin            |
      | enableaccessibilitysupport          | yes       | theme_boost_union |
      | accessibilitysupportpagesrlinktitle | <setting> | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    And "#access-support-form-sr-link" "css_element" should exist
    Then I should see "<title>" in the "#access-support-form-sr-link" "css_element"

    Examples:
      | setting         | title                     |
      | Customized link | Customized link           |
      |                 | Get accessibility support |

  @javascript
  Scenario: Sending the accessibility support form as guest
    Given the following config values are set as admin:
      | config                                | value | plugin            |
      | enableaccessibilitysupport            | yes   | theme_boost_union |
      | allowaccessibilitysupportwithoutlogin | yes   | theme_boost_union |
    When I log in as "guest"
    And I am on accessibilitysupport page
    And the "Name" "field" should not be readonly
    And the "Email address" "field" should not be readonly
    And the field "Subject" matches value "Accessibility feedback"
    And I click on "Submit" "button"
    And I should see "Required" in the "#fitem_id_message" "css_element"
    And I should see "Required" in the "#fitem_id_name" "css_element"
    And I should see "Missing email address" in the "#fitem_id_email" "css_element"
    And I set the field "Name" to "Guest"
    And I set the field "Email address" to "guest@example.com"
    And I set the field "Message" to "My message"
    And I click on "Submit" "button"
    Then I should see "Your accessibility support request was sent"
    # Unfortunately, the content of the email can't be tested with Behat yet as we do not have a way to test sent emails yet.

  @javascript
  Scenario: Sending the accessibility support form as user
    Given the following config values are set as admin:
      | config                     | value | plugin            |
      | enableaccessibilitysupport | yes   | theme_boost_union |
    When I log in as "student1"
    And I am on accessibilitysupport page
    And the "Name" "field" should be readonly
    And the field "Name" matches value "Student One"
    And the "Email address" "field" should be readonly
    And the field "Email address" matches value "student@example.com"
    And the field "Subject" matches value "Accessibility feedback"
    And I click on "Submit" "button"
    And I should see "Required" in the "#fitem_id_message" "css_element"
    And I set the field "Message" to "My message"
    And I click on "Submit" "button"
    Then I should see "Your accessibility support request was sent"
    # Unfortunately, the content of the email can't be tested with Behat yet as we do not have a way to test sent emails yet.

  # Unfortunately, this can't be tested with Behat yet as we do not have a way to test sent emails yet (or rather block the sending of the email).
  # Scenario: Sending the accessibility support form fails and the fallback message is shown

  @javascript
  Scenario Outline: Setting: Add re-captcha to accessibility support page
    Given the following config values are set as admin:
      | config              | value |
      | recaptchapublickey  | foo   |
      | recaptchaprivatekey | bar   |
    And the following config values are set as admin:
      | config                                | value     | plugin            |
      | enableaccessibilitysupport            | yes       | theme_boost_union |
      | allowaccessibilitysupportwithoutlogin | yes       | theme_boost_union |
      | accessibilitysupportrecaptcha         | <setting> | theme_boost_union |
    When I am on accessibilitysupport page
    Then "#fitem_id_recaptcha_element" "css_element" <nonshouldornot> exist
    And I log in as "guest"
    And I am on accessibilitysupport page
    And "#fitem_id_recaptcha_element" "css_element" <guestshouldornot> exist
    And I log out
    And I log in as "student1"
    And I am on accessibilitysupport page
    And "#fitem_id_recaptcha_element" "css_element" <usershouldornot> exist

    Examples:
      | setting             | nonshouldornot | guestshouldornot | usershouldornot |
      | never               | should not     | should not       | should not      |
      | always              | should         | should           | should          |
      | guestandnonloggedin | should         | should           | should not      |
