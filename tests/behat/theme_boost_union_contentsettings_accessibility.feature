@theme @theme_boost_union @theme_boost_union_contentsettings @theme_boost_union_contentsettings_accessibility
Feature: Configuring the theme_boost_union plugin for the "Accessibility" tab on the "Content" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | user     |

  Scenario Outline: Setting: Enable accessibility support page - Do not enable the accessibility support page
    Given the following config values are set as admin:
      | config                     | value     | plugin            |
      | enableaccessibilitysupport | <setting> | theme_boost_union |
    When I log in as "user"
    And I am on site homepage
    Then "#access-support-form-sr-link" "css_element" <shouldornot> exist

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: Enable accessibility button in page navigation
    Given the following config values are set as admin:
      | config                     | value     | plugin            |
      | enableaccessibilitysupport | yes       | theme_boost_union |
      | enableaccessibilitybutton  | <setting> | theme_boost_union |
    When I log in as "user"
    And I am on site homepage
    Then ".icon.fa-universal-access" "css_element" <shouldornot> exist in the "#top-buttons" "css_element"
    # Link to accessibility feedback always visible for screen readers.
    And I should see "Send accessibility feedback" in the "#page-wrapper > .sr-only-focusable" "css_element"

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: Enable page to be shown without login
    Given the following config values are set as admin:
      | config                                 | value     | plugin            |
      | enableaccessibilitysupport             | yes       | theme_boost_union |
      | enableaccessibilitysupportwithoutlogin | <setting> | theme_boost_union |
    When I log in as "<user>"
    And I am on accessibilitysupport page
    Then I <shouldornot> see "Accessibility support" in the "title" "css_element"
    # Always show link to accessibility support form for screen readers when page is enabled.
    And "#access-support-form-sr-link" "css_element" should exist

    Examples:
      | user  | setting | shouldornot |
      | guest | yes     | should      |
      | guest | no      | should not  |
      | user  | yes     | should      |
      | user  | no      | should      |

  Scenario Outline: Setting: Enable anonymous checkbox in accessibility form
    Given the following config values are set as admin:
      | config                      | value     | plugin            |
      | enableaccessibilitysupport  | yes       | theme_boost_union |
      | enablesendanonymouscheckbox | <setting> | theme_boost_union |
    When I log in as "user"
    And I am on accessibilitysupport page
    Then "sendanonymous" "checkbox" <shouldornot> exist

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: Enable checkbox for sending technical information in accessibility form
    Given the following config values are set as admin:
      | config                     | value     | plugin            |
      | enableaccessibilitysupport | yes       | theme_boost_union |
      | enablesendtechinfocheckbox | <setting> | theme_boost_union |
    When I log in as "user"
    And I am on accessibilitysupport page
    Then "sendtechinfo" "checkbox" <shouldornot> exist

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  @javascript
  Scenario: Sending accessibility support form anonymously
    Given the following config values are set as admin:
      | config                      | value | plugin            |
      | enableaccessibilitysupport  | yes   | theme_boost_union |
      | enablesendanonymouscheckbox | yes   | theme_boost_union |
    When I log in as "user"
    And I am on accessibilitysupport page

    Then "Name" "field" should be visible
    And "Email address" "field" should be visible

    When I click on "sendanonymous" "checkbox"
    Then "Name" "field" should not be visible
    And "Email address" "field" should not be visible

  @javascript
  Scenario: Sending accessibility support form with technical information
    Given the following config values are set as admin:
      | config                     | value | plugin            |
      | enableaccessibilitysupport | yes   | theme_boost_union |
      | enablesendtechinfocheckbox | yes   | theme_boost_union |
    When I log in as "user"
    And I am on accessibilitysupport page

    Then "Technical information to send" "field" should be visible

    When I click on "sendtechinfo" "checkbox"
    Then "Technical information to send" "field" should not be visible

  @javascript
  Scenario: Sending the accessibility support form as guest
    Given the following config values are set as admin:
      | config                                 | value | plugin            |
      | enableaccessibilitysupport             | yes   | theme_boost_union |
      | enableaccessibilitysupportwithoutlogin | yes   | theme_boost_union |
    When I log in as "guest"
    And I am on accessibilitysupport page
    Then I set the field "Name" to "Guest"
    And I set the field "Email address" to "guest@example.com"
    And I set the field "Message" to "My message"
    When I click on "Submit" "button"
    Then I should see "Accessibility feedback message sent"

  @javascript
  Scenario: Sending the accessibility support form as user
    Given the following config values are set as admin:
      | config                     | value | plugin            |
      | enableaccessibilitysupport | yes   | theme_boost_union |
    When I log in as "user"
    And I am on accessibilitysupport page
    Then I set the field "Message" to "My message"
    When I click on "Submit" "button"
    Then I should see "Accessibility feedback message sent"
