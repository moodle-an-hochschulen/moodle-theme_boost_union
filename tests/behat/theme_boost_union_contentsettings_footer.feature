@theme @theme_boost_union @theme_boost_union_contentsettings @theme_boost_union_contentsettings_footer
Feature: Configuring the theme_boost_union plugin for the "Footer" tab on the "Content" page
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

  Scenario: Setting: Footnote - Use the footnote setting to show a string in the page footer on the Dashboard, on the course pages and on the login page
    Given the following config values are set as admin:
      | config   | value                                                                                              | plugin            |
      | footnote | <span lang="en" class="multilang">Footnote</span><span lang="de" class="multilang">Fussnote</span> | theme_boost_union |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
    When I log in as "admin"
    And I follow "Dashboard"
    Then "#footnote" "css_element" should exist
    And ".text_to_html" "css_element" should not exist in the "#footnote" "css_element"
    And I should see "Footnote" in the "#footnote" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Footnote</span>" in the "#footnote" "css_element"
    And I should not see "FootnoteFussnote" in the "#footnote" "css_element"
    And I log out
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then "#footnote" "css_element" should exist
    And ".text_to_html" "css_element" should not exist in the "#footnote" "css_element"
    And I should see "Footnote" in the "#footnote" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Footnote</span>" in the "#footnote" "css_element"
    And I should not see "FootnoteFussnote" in the "#footnote" "css_element"
    And I log out
    And I follow "Log in"
    Then "#footnote" "css_element" should exist
    And ".text_to_html" "css_element" should not exist in the "#footnote" "css_element"
    And I should see "Footnote" in the "#footnote" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Footnote</span>" in the "#footnote" "css_element"
    And I should not see "FootnoteFussnote" in the "#footnote" "css_element"

  @javascript
  Scenario Outline: Setting: Footer - Enable and disable the footer button
    Given the following config values are set as admin:
      | config             | value   | plugin            |
      | enablefooterbutton | <value> | theme_boost_union |
    When I log in as "teacher1"
    And I follow "Dashboard"
    And I change viewport size to "large"
    Then ".btn-footer-popover" "css_element" <desktopshouldornot> <visibleorexist>
    And I change viewport size to "480x800"
    Then ".btn-footer-popover" "css_element" <mobileshouldornot> <visibleorexist>
    And I am on "Course 1" course homepage
    And I change viewport size to "large"
    Then ".btn-footer-popover" "css_element" <desktopshouldornot> <visibleorexist>
    And I change viewport size to "480x800"
    Then ".btn-footer-popover" "css_element" <mobileshouldornot> <visibleorexist>
    And I log out
    And I follow "Log in"
    And I change viewport size to "large"
    Then ".btn-footer-popover" "css_element" <desktopshouldornot> <visibleorexist>
    And I change viewport size to "480x800"
    Then ".btn-footer-popover" "css_element" <mobileshouldornot> <visibleorexist>

    Examples:
      | value                     | desktopshouldornot | mobileshouldornot | visibleorexist |
      | enablefooterbuttonall     | should             | should            | be visible     |
      | enablefooterbuttondesktop | should             | should not        | be visible     |
      | enablefooterbuttonmobile  | should not         | should            | be visible     |
      | enablefooterbuttonnone    | should not         | should not        | exist          |
