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
      | config   | value             | plugin            |
      | footnote | Whatever footnote | theme_boost_union |
    When I log in as "admin"
    And I follow "Dashboard"
    Then "#footnote" "css_element" should exist
    And I should see "Whatever footnote" in the "#footnote" "css_element"
    And I log out
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then "#footnote" "css_element" should exist
    And I should see "Whatever footnote" in the "#footnote" "css_element"
    And I log out
    And I follow "Log in"
    Then "#footnote" "css_element" should exist
    And I should see "Whatever footnote" in the "#footnote" "css_element"
