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
    Then "Category E" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And I am on "Course C2" course homepage
    And "Category E" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And "Category ED" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And I am on "Course C3" course homepage
    And "Category E" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And "Category ED" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And "Category EDC" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And I am on "Course C4" course homepage
    And "Category E" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And "Category ED" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And "Category EDC" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And "Category EDCB" "link" <shouldornot> exist in the "#page-navbar" "css_element"

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
    And "CC1" "link" should exist in the ".breadcrumb" "css_element"
    And "New section" "text" should exist in the ".breadcrumb" "css_element"
    And "Category ED" "link" should appear after "Category E" "link" in the ".breadcrumb" "css_element"
    And "CC1" "link" should appear after "Category ED" "link" in the ".breadcrumb" "css_element"
    And "New section" "text" should appear after "CC1" "link" in the ".breadcrumb" "css_element"

  Scenario Outline: Setting: Course category breadcrumbs (verify that the last breadcrumb item is only linked if it is a category)
    Given the following "categories" exist:
      | name           | category | idnumber | category |
      | Category E     | 0        | CE       | 0        |
    And the following "courses" exist:
      | fullname  | shortname | category |
      | Course C1 | CC1       | CE       |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | CC1    | editingteacher |
    And the following config values are set as admin:
      | config              | value     | plugin            |
      | categorybreadcrumbs | <setting> | theme_boost_union |
    When I log in as "teacher1"
    # On the course main page, the category breadcrumbs are the only breadcrumb items and remain clickable.
    And I am on "Course C1" course homepage
    Then "Category E" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    # On a section page, the section is the last breadcrumb item and must not be clickable (just like in Boost core).
    And I am on the "Course C1 > New section" "course > section" page
    And "Category E" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And "CC1" "link" should exist in the "#page-navbar" "css_element"
    And "New section" "text" should exist in the "#page-navbar" "css_element"
    And "New section" "link" should not exist in the "#page-navbar" "css_element"
    # On the course backup page, the breadcrumb only consists of items without link in Boost core and is removed completely.
    # With category breadcrumbs, the categories are shown and the current page is the last breadcrumb item without link.
    And I am on the "Course C1" "backup" page
    And "Category E" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And "Backup" "link" should not exist in the "#page-navbar" "css_element"
    And "Backup" "text" <shouldornot> exist in the "#page-navbar" "css_element"
    And "Course administration" "text" should not exist in the "#page-navbar" "css_element"

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: Course category breadcrumbs (verify that the section page of an empty general section ends with the general section)
    Given the following "categories" exist:
      | name           | category | idnumber | category |
      | Category E     | 0        | CE       | 0        |
    And the following "courses" exist:
      | fullname  | shortname | category | numsections |
      | Course C1 | CC1       | CE       | 2           |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | CC1    | editingteacher |
    And the following config values are set as admin:
      | config              | value     | plugin            |
      | categorybreadcrumbs | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on the "Course C1 > Section 0" "course > section" page
    Then "CC1" "link" should exist in the "#page-navbar" "css_element"
    And "Category E" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    # Moodle core has a glitch here (MDL-90146): The topics format removes the navigation node of the general section if it is empty.
    # On the section page of such an empty general section, Moodle core does not find a navigation node for the current page
    # and falls back to the first section with a matching URL. As a result, the breadcrumb ends with "New section" instead
    # of "General" in Boost core. Boost Union works around this glitch regardless of the categorybreadcrumbs setting,
    # so both examples end with "General" here. As soon as MDL-90146 is fixed in Moodle core, the workaround can be removed
    # and this scenario still has to pass.
    And "General" "text" should exist in the "#page-navbar" "css_element"
    And "New section" "text" should not exist in the "#page-navbar" "css_element"

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: Course category breadcrumbs (verify that the section page of an empty general section ends with the general section even if there is no other section)
    Given the following "categories" exist:
      | name           | category | idnumber | category |
      | Category E     | 0        | CE       | 0        |
    And the following "courses" exist:
      | fullname  | shortname | category | numsections |
      | Course C1 | CC1       | CE       | 0           |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | CC1    | editingteacher |
    And the following config values are set as admin:
      | config              | value     | plugin            |
      | categorybreadcrumbs | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on the "Course C1 > Section 0" "course > section" page
    # This is another flavour of the Moodle core glitch (MDL-90146): As there is no other section which Moodle core could fall
    # back to, the breadcrumb ends with the course and is removed completely in Boost core. Boost Union works around this as well.
    Then "Category E" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And "CC1" "link" should exist in the "#page-navbar" "css_element"
    And "General" "text" should exist in the "#page-navbar" "css_element"
    And "General" "link" should not exist in the "#page-navbar" "css_element"
    And "General" "text" should appear after "CC1" "link" in the "#page-navbar" "css_element"

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: Course category breadcrumbs (verify that the section page of a subsection ends with the parent section and the subsection)
    Given the following "categories" exist:
      | name           | category | idnumber | category |
      | Category E     | 0        | CE       | 0        |
    And the following "courses" exist:
      | fullname  | shortname | category | numsections |
      | Course C1 | CC1       | CE       | 2           |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | CC1    | editingteacher |
    And the following "activities" exist:
      | activity   | name        | course | idnumber | section |
      | subsection | Subsection1 | CC1    | sub1     | 2       |
      | page       | Page1       | CC1    | page1    | 3       |
    And the following config values are set as admin:
      | config              | value     | plugin            |
      | categorybreadcrumbs | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on the "Course C1 > Subsection1" "course > section" page
    # Moodle core does not find the navigation node of the subsection either and the breadcrumb does not end with the subsection
    # in Boost core. Boost Union works around this as well and shows the parent section and the subsection.
    Then "Category E" "link" <shouldornot> exist in the "#page-navbar" "css_element"
    And "CC1" "link" should exist in the "#page-navbar" "css_element"
    And "New section" "link" should exist in the "#page-navbar" "css_element"
    And "Subsection1" "text" should exist in the "#page-navbar" "css_element"
    And "Subsection1" "link" should not exist in the "#page-navbar" "css_element"
    And "New section" "link" should appear after "CC1" "link" in the "#page-navbar" "css_element"
    And "Subsection1" "text" should appear after "New section" "link" in the "#page-navbar" "css_element"

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |
