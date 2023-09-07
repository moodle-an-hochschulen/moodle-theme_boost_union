@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_dynamiccourses
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, using dynamic menus to automatically compose smart menu items
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "categories" exist:
      | name        | category | idnumber |
      | Category 01 | 0        | CAT1     |
      | Category 02 | 0        | CAT2     |
      | Category 03 | 0        | CAT3     |
    And the following "courses" exist:
      | fullname  | shortname | category | enablecompletion | startdate      | enddate         |
      | Course 01 | C1        | CAT1     | 1                | ## now ##      | ## +5 days ##   |
      | Course 02 | C2        | CAT1     | 1                | ##1 year ago## | ##1 month ago## |
      | Course 03 | C3        | CAT1     | 1                | ## +5 days ##  | 0               |
      | Course 04 | C4        | CAT2     | 1                | 0              | 0               |
      | Course 05 | C5        | CAT2     | 1                | 0              | 0               |
      | Course 06 | C6        | CAT3     | 1                | 0              | 0               |
    And the following "activities" exist:
      | activity | name                  | intro                       | course | idnumber | section | completion |
      | assign   | Test assignment name1 | Test assignment description | C1     | assign1  | 0       | 1          |
      | assign   | Test assignment name2 | Test assignment description | C1     | assign2  | 0       | 1          |
      | assign   | Test assignment name3 | Test assignment description | C2     | assign1  | 0       | 1          |
      | assign   | Test assignment name4 | Test assignment description | C2     | assign2  | 0       | 1          |
    And the following "users" exist:
      | username |
      | student1 |
      | teacher  |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher  | C1     | editingteacher |
      | teacher  | C5     | editingteacher |
      | teacher  | C6     | editingteacher |
      | student1 | C1     | student        |
      | student1 | C2     | student        |
      | student1 | C3     | student        |
      | student1 | C4     | student        |
    And I log in as "admin"
    And I create smart menu with the following fields to these values:
      | Title            | List menu                |
      | Menu location(s) | Main, Menu, User, Bottom |
    And I set "List menu" smart menu items with the following fields to these values:
      | Title          | Dynamic courses |
      | Menu item type | Dynamic courses |

  @javascript
  Scenario: Smartmenus: Menu items: Dynamic courses - Check the smart menu item settings fields which are shown conditionally for dynamic courses
    When I log in as "admin"
    And I navigate to smart menus
    And I should see "List menu" in the "smartmenus" "table"
    And I click on ".action-list-items" "css_element" in the "List menu" "table_row"
    Then I should see "Dynamic courses"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    And I should see "Dynamic courses: Course category"
    And I should see "Dynamic courses: Enrolment role"
    And I should see "Dynamic courses: Completion status"
    And I should see "Dynamic courses: Date range"

  @javascript
  Scenario: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on all existing courses (without any condition)
    When I log in as "student1"
    Then I should see smart menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should see smart menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should see smart menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I should see smart menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
    And I should see smart menu "List menu" item "Course 05" in location "Main, Menu, User, Bottom"
    And I should see smart menu "List menu" item "Course 06" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on a category condition
    When I log in as "admin"
    And I navigate to smart menu "List menu" items
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    And I set the field "Dynamic courses: Course category" to "<category>"
    And I press "Save changes"
    And I log out
    And I log in as "<user>"
    Then I <course1> see smart menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I <course2> see smart menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I <course3> see smart menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I <course4> see smart menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
    And I <course5> see smart menu "List menu" item "Course 05" in location "Main, Menu, User, Bottom"
    And I <course6> see smart menu "List menu" item "Course 06" in location "Main, Menu, User, Bottom"

    Examples:
      | category    | user     | course1    | course2    | course3    | course4    | course5    | course6    |
      | Category 01 | student1 | should     | should     | should     | should not | should not | should not |
      | Category 02 | student1 | should not | should not | should not | should     | should     | should not |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on a enrolment role condition
    When I log in as "admin"
    And I navigate to smart menu "List menu" items
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    And I set the field "Dynamic courses: Enrolment role" to "<role>"
    And I press "Save changes"
    And I log out
    And I log in as "<user>"
    Then I <course1> see smart menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I <course2> see smart menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I <course3> see smart menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I <course4> see smart menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
    And I <course5> see smart menu "List menu" item "Course 05" in location "Main, Menu, User, Bottom"
    And I <course6> see smart menu "List menu" item "Course 06" in location "Main, Menu, User, Bottom"

    Examples:
      | role                         | user     | course1    | course2    | course3    | course4    | course5    | course6    |
      | Non-editing teacher, Teacher | student1 | should not | should not | should not | should not | should not | should not |
      | Non-editing teacher, Teacher | teacher  | should     | should not | should not | should not | should     | should     |
      | Student                      | student1 | should     | should     | should     | should     | should not | should not |
      | Student                      | teacher  | should not | should not | should not | should not | should not | should not |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on a completion status condition
    When I log in as "admin"
    And I navigate to smart menu "List menu" items
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    And I set the field "Dynamic courses: Completion status" to "<completionstatus>"
    And I press "Save changes"
    And I am on "Course 01" course homepage with editing mode on
    And I navigate to "Course completion" in current page administration
    And I click on "Condition: Activity completion" "link"
    And I set the following fields to these values:
      | Assignment - Test assignment name1 | 1 |
      | Assignment - Test assignment name2 | 1 |
    And I press "Save changes"
    And I am on "Course 02" course homepage with editing mode on
    And I navigate to "Course completion" in current page administration
    And I click on "Condition: Activity completion" "link"
    And I set the following fields to these values:
      | Assignment - Test assignment name3 | 1 |
      | Assignment - Test assignment name4 | 1 |
    And I press "Save changes"
    And I log out
    And I log in as "<user>"
    And I am on "Course 01" course homepage
    And the manual completion button of "Test assignment name1" is displayed as "Mark as done"
    And I toggle the manual completion state of "Test assignment name1"
    And the manual completion button of "Test assignment name2" is displayed as "Mark as done"
    And I toggle the manual completion state of "Test assignment name2"
    And I am on "Course 02" course homepage
    And the manual completion button of "Test assignment name3" is displayed as "Mark as done"
    And I toggle the manual completion state of "Test assignment name3"
    And I follow "Dashboard"
    Then I <course1> see smart menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I <course2> see smart menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I <course3> see smart menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I <course4> see smart menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
    And I <course5> see smart menu "List menu" item "Course 05" in location "Main, Menu, User, Bottom"

    Examples:
      | completionstatus                 | user     | course1    | course2    | course3    | course4    | course5    |
      | Enrolled, In progress, Completed | student1 | should     | should     | should     | should     | should not |
      | In progress, Completed           | student1 | should     | should     | should not | should not | should not |
      | Enrolled                         | student1 | should not | should not | should     | should     | should not |
      | Completed                        | student1 | should     | should not | should not | should not | should not |
      | In progress                      | student1 | should not | should     | should not | should not | should not |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on a date range condition
    When I log in as "admin"
    And I navigate to smart menu "List menu" items
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    And I set the field "Dynamic courses: Date range" to "<daterange>"
    And I press "Save changes"
    And I log out
    And I log in as "<user>"
    Then I <course1> see smart menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I <course2> see smart menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I <course3> see smart menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I <course4> see smart menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"

    Examples:
      | daterange             | user     | course1    | course2    | course3    | course4    | course5    |
      | Past, Present, Future | student1 | should     | should     | should     | should     | should     |
      | Future                | student1 | should not | should not | should     | should not | should not |
      | Present               | student1 | should     | should not | should not | should     | should     |
      | Past                  | student1 | should not | should     | should not | should not | should not |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on a course field condition
    Given the following "custom field categories" exist:
      | name   | component   | area   | itemid |
      | Others | core_course | course | 0      |
    And I log in as "admin"
    And I navigate to "Courses > Course custom fields" in site administration
    And I click on "Add a new custom field" "link"
    And I click on "Short text" "link"
    And I set the following fields to these values:
      | Name       | Test field |
      | Short name | testfield  |
    And I click on "Save changes" "button" in the "Adding a new Short text" "dialogue"
    And I follow "Dashboard"
    And I am on "Course 01" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Test field | value1 |
    And I click on "Save and display" "button"
    And I am on "Course 02" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Test field | value1 |
    And I click on "Save and display" "button"
    And I am on "Course 03" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Test field | value2 |
    And I click on "Save and display" "button"
    When I log in as "admin"
    And I navigate to smart menu "List menu" items
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    And I should see "Test field"
    And I set the field "Test field" to "<value>"
    And I press "Save changes"
    And I log out
    And I log in as "<user>"
    Then I <course1> see smart menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I <course2> see smart menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I <course3> see smart menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I <course4> see smart menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"

    Examples:
      | value  | user     | course1    | course2    | course3    | course4    |
      | value1 | student1 | should     | should     | should not | should not |
      | value2 | student1 | should not | should not | should     | should not |
