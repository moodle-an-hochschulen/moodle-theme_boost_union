@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_breadcrumbs
Feature: Configuring the theme_boost_union plugin for the "Breadcrumbs" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | student1 |
      | teacher1 |
    And the following "categories" exist:
      | name                | category | idnumber | category |
      | Course category     | 0        | CC       |          |
      | Course sub category | 1        | CSC      | CC       |
    And the following "courses" exist:
      | fullname | shortname | format | category |
      | Course 1 | C1        | topics | CSC      |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  Scenario: Setting: Course breadcrumbs - Set the course categories breadcrumbs
    Given the following config values are set as admin:
      | config                     | value | plugin            |
      | categorybreadcrumbsenabled | yes   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then "Course category" "text" should exist in the ".breadcrumb" "css_element"
    And "Course sub category" "link" should exist in the ".breadcrumb" "css_element"

  Scenario: Setting: Course breadcrumbs - Unet the course categories breadcrumbs
    Given the following config values are set as admin:
      | config                     | value | plugin            |
      | categorybreadcrumbsenabled | no    | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then "Course category" "text" should not exist in the ".breadcrumb" "css_element"
    And "Course sub category" "link" should not exist in the ".breadcrumb" "css_element"
