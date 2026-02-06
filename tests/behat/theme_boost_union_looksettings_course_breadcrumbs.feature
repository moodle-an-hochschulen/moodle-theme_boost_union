@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_course @theme_boost_union_looksettings_course_breadcrumbs
Feature: Configuring the theme_boost_union plugin for the "Breadcrumbs" section on the "Course" tab on the "Look" page
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

  Scenario Outline: Setting: Course category breadcrumbs
    Given the following "categories" exist:
      | name           | category | idnumber | category |
      | Category E     | 0        | CE       | 0        |
      | Category ED    | 1        | CED      | CE       |
      | Category EDC   | 2        | CEDC     | CED      |
      | Category EDCB  | 3        | CEDCB    | CEDC     |
    And the following "courses" exist:
      | fullname  | shortname | category |
      | Course C1 | CC1       | CE       |
      | Course C2 | CC2       | CED      |
      | Course C3 | CC3       | CEDC     |
      | Course C4 | CC4       | CEDCB    |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | CC1    | editingteacher |
      | teacher1 | CC2    | editingteacher |
      | teacher1 | CC3    | editingteacher |
      | teacher1 | CC4    | editingteacher |
    And the following config values are set as admin:
      | config              | value     | plugin            |
      | categorybreadcrumbs | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course C1" course homepage
    Then "Category E" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And I am on "Course C2" course homepage
    And "Category E" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category ED" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And I am on "Course C3" course homepage
    And "Category E" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category ED" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category EDC" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And I am on "Course C4" course homepage
    And "Category E" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category ED" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category EDC" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category EDCB" "link" <shouldornot> exist in the ".breadcrumb" "css_element"

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario: Setting: Course category breadcrumbs (verify that course sections are properly displayed _after_ the categories)
    Given the following "categories" exist:
      | name           | category | idnumber | category |
      | Category E     | 0        | CE       | 0        |
      | Category ED    | 1        | CED      | CE       |
    And the following "courses" exist:
      | fullname  | shortname | category |
      | Course C1 | CC1       | CED      |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | CC1    | editingteacher |
    And the following config values are set as admin:
      | config              | value     | plugin            |
      | categorybreadcrumbs | yes       | theme_boost_union |
    When I log in as "teacher1"
    And I am on the "Course C1 > New section" "course > section" page
    Then "Category E" "link" should exist in the ".breadcrumb" "css_element"
    And "Category ED" "link" should exist in the ".breadcrumb" "css_element"
    And "New section" "link" should exist in the ".breadcrumb" "css_element"
    And "Category ED" "link" should appear after "Category E" "link" in the ".breadcrumb" "css_element"
    And "New section" "link" should appear after "Category ED" "link" in the ".breadcrumb" "css_element"
