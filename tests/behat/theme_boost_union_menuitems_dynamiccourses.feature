@theme @theme_boost_union @theme_boost_union_smartmenu @theme_boost_union_menuitems_dynamiccourses
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, applying different items settings.
  In order to use th features
  As admin
  I need to be able to configure smart menu dynamic course item type to the theme Boost Union plugin

  Background:
    Given the following "categories" exist:
      | name         | category | idnumber |
      | Category 01  | 0        | CAT1     |
      | Category 02  | 0        | CAT2     |
      | Category 03  | 0        | CAT3     |
    And the following "courses" exist:
      | fullname  | shortname | category    |  enablecompletion |  startdate       | enddate         |
      | Course 01 | C1        | CAT1        |   1               |  ## now ##       | ## +5 days ## |
      | Course 02 | C2        | CAT1        |   1               |  ##1 year ago##  | ##1 month ago## |
      | Course 03 | C3        | CAT1        |   1               |  ## +5 days ##   | 0 |
      | Course 04 | C4        | CAT2        |   1               | 0 | 0 |
      | Course 05 | C5        | CAT2        |   1               | 0 | 0 |
      | Course 06 | C6        | CAT3        |   1               | 0 | 0 |
      | Course 07 | C7        | CAT3        |   1               | 0 | 0 |
    And the following "activities" exist:
      | activity   | name                   | intro                         | course | idnumber    | section | completion |
      | assign     | Test assignment name   | Test assignment description   | C1     | assign1     | 0       |   1        |
      | assign     | Test assignment name1  | Test assignment description   | C1     | assign2     | 0       |   1        |
      | assign     | Test assignment name   | Test assignment description   | C2     | assign1     | 0       |   1        |
      | assign     | Test assignment name1  | Test assignment description   | C2     | assign2     | 0       |   1        |
    And the following "users" exist:
      | username | firstname | lastname | email             |
      | student1 | student   | User 1   | student1@test.com |
      | student2 | student2  | User 2   | student2@test.com |
      | teacher  | Teacher   | User 1   | teacher2@test.com |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher  | C1     | editingteacher |
      | student1 | C1     | student        |
      | student2 | C1     | student        |
      | student1 | C2     | student        |
      | student2 | C2     | student        |
      | student1 | C3     | student        |
      | student2 | C3     | student        |
      | student1 | C4     | student        |
      | student2 | C4     | student        |
      | admin    | C1     | editingteacher |
      | admin    | C2     | editingteacher |
      | admin    | C3     | teacher        |
      | admin    | C4     | manager        |
      | admin    | C5     | student        |
      | admin    | C6     | student        |
    Given I log in as "admin"
    And I create menu with the following fields to these values:
    | Title     | List menu                |
    | Locations | Main, Menu, User, Bottom |
    And I set "List menu" items with the following fields to these values:
    | Title     | Dynamic courses |
    | Type      | Dynamic courses |

  @javascript
  Scenario: Smartmenu Dynamic courses Item: Check condition
    Given I navigate to smartmenus
    And I should see "List menu" in the "smartmenus" "table"
    And I click on ".action-list-items" "css_element" in the "List menu" "table_row"
    Then I should see "Dynamic courses"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I should see "Category"
    Then I should see "Enrolment role"
    Then I should see "Completion status"
    Then I should see "Date range"
    Then I should see "Enrolment role"
    Then I press "Save changes"

  @javascript
  Scenario: Smartmenu Dynamic courses Item: list courses
    And I should see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 05" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenu Dynamic courses Item: Category condition.
    Given I navigate to smartmenus
    And I click on ".action-list-items" "css_element" in the "List menu" "table_row"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I set the field "Category" to "Category 01"
    Then I press "Save changes"
    And I should see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 05" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenu Dynamic courses Item: Enrolment condition
    Given I navigate to smartmenus
    And I click on ".action-list-items" "css_element" in the "List menu" "table_row"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I set the field "Enrolment role" to "Non-editing teacher, Teacher"
    Then I press "Save changes"
    And I should see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 05" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 06" in location "Main, Menu, User, Bottom"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I set the field "Enrolment role" to "Teacher"
    Then I press "Save changes"
    And I should see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenu Dynamic courses Item: Completion condition
    Given I navigate to smartmenus
    And I click on ".action-list-items" "css_element" in the "List menu" "table_row"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I set the field "Completion status" to "Enrolled, In progress, Completed"
    Then I press "Save changes"
    # need to set course completion to activity.
    And I am on "Course 01" course homepage with editing mode on
    And I navigate to "Course completion" in current page administration
    And I click on "Condition: Activity completion" "link"
    And I set the following fields to these values:
      | Assignment - Test assignment name| 1  |
      | Assignment - Test assignment name1| 1  |
    And I press "Save changes"
    And I am on "Course 02" course homepage with editing mode on
    And I navigate to "Course completion" in current page administration
    And I click on "Condition: Activity completion" "link"
    And I set the following fields to these values:
      | Assignment - Test assignment name| 1  |
      | Assignment - Test assignment name1| 1  |
    And I press "Save changes"
    Then I log out
    Then I log in as "student1"
    Then I am on "Course 01" course homepage
    And the manual completion button of "Test assignment name" is displayed as "Mark as done"
    And I toggle the manual completion state of "Test assignment name"
    And the manual completion button of "Test assignment name1" is displayed as "Mark as done"
    And I toggle the manual completion state of "Test assignment name1"
    Then I am on "Course 02" course homepage
    And the manual completion button of "Test assignment name" is displayed as "Mark as done"
    And I toggle the manual completion state of "Test assignment name"
    Then I follow "Dashboard"
    And I should see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 05" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 06" in location "Main, Menu, User, Bottom"
    Then I log out
    Then I log in as "admin"
    And I navigate to smartmenus
    And I click on ".action-list-items" "css_element" in the "List menu" "table_row"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I set the field "Completion status" to "In progress, Completed"
    Then I press "Save changes"
    Then I log out
    Then I log in as "student1"
    And I should see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
    Then I log out
    Then I log in as "admin"
    And I navigate to smartmenus
    And I click on ".action-list-items" "css_element" in the "List menu" "table_row"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I set the field "Completion status" to "Completed"
    Then I press "Save changes"
    Then I log out
    Then I log in as "student1"
    And I should see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
    Then I log out
    And I log in as "admin"

  @javascript
  Scenario: Smartmenu Dynamic courses Item: Date condition
    Given I navigate to smartmenus
    And I click on ".action-list-items" "css_element" in the "List menu" "table_row"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I set the field "Date range" to "Past, Present, Future"
    Then I press "Save changes"
    Then I log out
    Then I log in as "student1"
    And I should see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
    Then I log out

    Then I log in as "admin"
    And I navigate to smartmenus
    And I click on ".action-list-items" "css_element" in the "List menu" "table_row"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I set the field "Date range" to "Future"
    Then I press "Save changes"
    Then I log out
    Then I log in as "student1"
    And I should not see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    Then I log out

    Then I log in as "admin"
    And I navigate to smartmenus
    And I click on ".action-list-items" "css_element" in the "List menu" "table_row"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I set the field "Date range" to "Present"
    Then I press "Save changes"
    Then I log out
    Then I log in as "student1"
    And I should see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    Then I log out

    Then I log in as "admin"
    And I navigate to smartmenus
    And I click on ".action-list-items" "css_element" in the "List menu" "table_row"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I set the field "Date range" to "Past"
    Then I press "Save changes"
    Then I log out
    Then I log in as "student1"
    And I should not see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    Then I log out

  @javascript
  Scenario: Smartmenu Dynamic courses Item: profile field condition
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
    Then I follow "Dashboard"
    Then I am on "Course 01" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Test field | value1 |
    And I click on "Save and display" "button"
    Then I am on "Course 02" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Test field | value1 |
    And I click on "Save and display" "button"
    Then I am on "Course 03" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
    | Test field | value2 |
    And I click on "Save and display" "button"
    Then I navigate to smartmenus
    And I click on ".action-list-items" "css_element" in the "List menu" "table_row"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I should see "Test field"
    Then I set the field "Test field" to "value1"
    Then I press "Save changes"
    And I should see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
    And I click on ".action-edit" "css_element" in the "Dynamic courses" "table_row"
    Then I should see "Test field"
    Then I set the field "Test field" to "value2"
    Then I press "Save changes"
    And I should not see menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 02" in location "Main, Menu, User, Bottom"
    And I should see menu "List menu" item "Course 03" in location "Main, Menu, User, Bottom"
    And I should not see menu "List menu" item "Course 04" in location "Main, Menu, User, Bottom"
