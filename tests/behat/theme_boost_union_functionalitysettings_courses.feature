@theme @theme_boost_union @theme_boost_union_functionalitysettings @theme_boost_union_functionalitysettings_courses
Feature: Configuring the theme_boost_union plugin for the "Courses" tab on the "Functionality" page
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

  Scenario: Setting: Show hint for switched role - Enable the setting
    Given the following config values are set as admin:
      | config                   | value | plugin            |
      | showswitchedroleincourse | yes   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I follow "Switch role to..." in the user menu
    And I click on "Student" "button"
    Then I should see "You are viewing this course currently with the role:" in the ".course-hint-switchedrole" "css_element"
    When I click on "Return to my normal role" "link" in the ".course-hint-switchedrole" "css_element"
    Then I should not see "You are viewing this course currently with the role:"
    And ".course-hint-switchedrole" "css_element" should not exist

  Scenario: Setting: Show hint in hidden courses - Enable the setting
    Given the following config values are set as admin:
      | config               | value | plugin            |
      | showhintcoursehidden | yes   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Course visibility | Hide |
    And I click on "Save and display" "button"
    Then I should see "This course is currently hidden. Only enrolled teachers can access this course when hidden." in the ".course-hint-hidden" "css_element"
    When I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Course visibility | Show |
    And I click on "Save and display" "button"
    Then I should not see "This course is currently hidden. Only enrolled teachers can access this course when hidden."
    And ".course-hint-hidden" "css_element" should not exist

  Scenario: Setting: Show hint for forum notifications in hidden courses - Enable the setting
    Given the following "activity" exists:
      | course   | C1            |
      | activity | forum         |
      | idnumber | Announcements |
      | name     | Announcements |
    And the following config values are set as admin:
      | config                     | value | plugin            |
      | showhintcoursehidden       | yes   | theme_boost_union |
      | showhintforumnotifications | yes   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Course visibility | Hide |
    And I click on "Save and display" "button"
    And I am on the "Announcements" "forum activity" page
    Then I should see "This course is currently hidden. This means that students will not be notified online or by email of any messages you post in this forum." in the ".course-hint-hidden" "css_element"
    When I click on "Add discussion topic" "link"
    And I click on "#id_advancedadddiscussion" "css_element"
    Then I should see "This course is currently hidden. This means that students will not be notified online or by email of any messages you post in this forum." in the ".course-hint-hidden" "css_element"
    And I set the field "Subject" to "My post"
    And I set the field "Message" to "My message"
    And I click on "Post to forum" "button"
    When I click on "My post" "link"
    Then I should see "This course is currently hidden. This means that students will not be notified online or by email of any messages you post in this forum." in the ".course-hint-hidden" "css_element"
    When I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Course visibility | Show |
    And I click on "Save and display" "button"
    Then I should not see "This course is currently hidden. This means that students will not be notified online or by email of any messages you post in this forum."
    And ".course-hint-hidden" "css_element" should not exist

  Scenario: Setting: Show hint guest for access - Enable the setting
    Given the following config values are set as admin:
      | config                    | value | plugin            |
      | showhintcourseguestaccess | yes   | theme_boost_union |
    And the following "users" exist:
      | username |
      | student2 |
    When I log in as "teacher1"
    And I am on the "Course 1" "enrolment methods" page
    And I click on "Edit" "link" in the "Guest access" "table_row"
    And I set the following fields to these values:
      | Allow guest access | Yes |
    And I press "Save changes"
    And I log out
    When I log in as "student2"
    And I am on "Course 1" course homepage
    Then I should see "You are currently viewing this course as Guest." in the ".course-hint-guestaccess" "css_element"
    And I log out
    And I log in as "teacher1"
    And I am on the "Course 1" "enrolment methods" page
    And I click on "Enable" "link" in the "Self enrolment (Student)" "table_row"
    And I log out
    When I log in as "student2"
    And I am on "Course 1" course homepage
    Then I should see "To have full access to the course, you can self enrol into this course." in the ".course-hint-guestaccess" "css_element"
    And I click on "self enrol into this course" "link" in the ".course-hint-guestaccess" "css_element"
    And I click on "Enrol me" "button"
    Then I should not see "You are currently viewing this course as Guest."
    And ".course-hint-guestaccess" "css_element" should not exist
    And I log out
    When I log in as "teacher1"
    And I am on the "Course 1" "enrolment methods" page
    And I click on "Edit" "link" in the "Guest access" "table_row"
    And I set the following fields to these values:
      | Allow guest access | No |
    And I press "Save changes"
    And I log out
    When I log in as "student2"
    And I am on "Course 1" course homepage
    Then I should not see "You are currently viewing this course as Guest."
    And ".course-hint-guestaccess" "css_element" should not exist

  Scenario: Setting: Show hint for self enrolment without enrolment key - Enable the setting
    Given the following config values are set as admin:
      | config                  | value | plugin            |
      | showhintcourseselfenrol | yes   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should not see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should not exist
    And I am on the "Course 1" "enrolment methods" page
    When I click on "Enable" "link" in the "Self enrolment (Student)" "table_row"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should exist
    And I log out
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then I should not see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should not exist

  Scenario: Setting: Show hint for self enrolment without enrolment key - Enable the setting and check that the call for action is shown
    Given the following config values are set as admin:
      | config                  | value | plugin            |
      | showhintcourseselfenrol | yes   | theme_boost_union |
    And the following "users" exist:
      | username |
      | teacher2 |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | teacher2 | C1     | teacher |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should not see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And I should not see "If you don't want any Moodle user to have access to this course freely, please restrict the self enrolment settings."
    And ".course-hint-selfenrol" "css_element" should not exist
    And I am on the "Course 1" "enrolment methods" page
    When I click on "Enable" "link" in the "Self enrolment (Student)" "table_row"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And I should see "If you don't want any Moodle user to have access to this course freely, please restrict the self enrolment settings."
    And ".course-hint-selfenrol" "css_element" should exist
    And I log out
    When I log in as "teacher2"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And I should not see "If you don't want any Moodle user to have access to this course freely, please restrict the self enrolment settings."
    And ".course-hint-selfenrol" "css_element" should exist
    And I log out
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then I should not see "This course is currently visible to everyone and self enrolment without enrolment key is"
    And I should not see "If you don't want any Moodle user to have access to into this course freely, please restrict the self enrolment settings."
    And ".course-hint-selfenrol" "css_element" should not exist

  Scenario: Setting: Show hint for self enrolment without an enrolment key - Enable the setting and check that it is hidden when new enrolments are disabled
    Given the following config values are set as admin:
      | config                  | value | plugin            |
      | showhintcourseselfenrol | yes   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should not see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should not exist
    And I am on the "Course 1" "enrolment methods" page
    When I click on "Enable" "link" in the "Self enrolment (Student)" "table_row"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should exist
    When I click on "Self enrolment (Student)" "link" in the ".course-hint-selfenrol" "css_element"
    And I set the following fields to these values:
      | Allow new self enrolments | 0 |
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should not see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should not exist

  Scenario: Setting: Show hint for self enrolment without an enrolment key - Enable the setting and check that it is hidden when a password is set
    Given the following config values are set as admin:
      | config                  | value | plugin            |
      | showhintcourseselfenrol | yes   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should not see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should not exist
    And I am on the "Course 1" "enrolment methods" page
    When I click on "Enable" "link" in the "Self enrolment (Student)" "table_row"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should exist
    When I click on "Self enrolment (Student)" "link" in the ".course-hint-selfenrol" "css_element"
    And I set the following fields to these values:
      | Enrolment key | 1234 |
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should not see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should not exist

  Scenario: Setting: Show hint for self enrolment without an enrolment key - Enable the setting and check the hints depending on the configured start and / or end dates
    Given the following config values are set as admin:
      | config                  | value | plugin            |
      | showhintcourseselfenrol | yes   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should not see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should not exist
    And I am on the "Course 1" "enrolment methods" page
    When I click on "Enable" "link" in the "Self enrolment (Student)" "table_row"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is possible."
    And I should see "The Self enrolment (Student) enrolment instance allows unrestricted self enrolment indefinitely."
    And ".course-hint-selfenrol" "css_element" should exist
    When I click on "Self enrolment (Student)" "link" in the ".course-hint-selfenrol" "css_element"
    And I set the following fields to these values:
      | id_enrolstartdate_enabled | 0       |
      | id_enrolenddate_enabled   | 1       |
    # We can't use the ##yesterday## notation here.
      | id_enrolenddate_day       | 1       |
      | id_enrolenddate_month     | January |
      | id_enrolenddate_year      | 2019    |
      | id_enrolenddate_hour      | 00      |
      | id_enrolenddate_minute    | 00      |
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should not see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should not exist
    When I am on the "Course 1" "enrolment methods" page
    And I click on "Edit" "link" in the "Self enrolment (Student)" "table_row"
    And I set the following fields to these values:
      | id_enrolstartdate_enabled | 0       |
      | id_enrolenddate_enabled   | 1       |
    # We can't use the ##tomorrow## notation here. This test will break in the year 2050.
      | id_enrolenddate_day       | 1       |
      | id_enrolenddate_month     | January |
      | id_enrolenddate_year      | 2050    |
      | id_enrolenddate_hour      | 00      |
      | id_enrolenddate_minute    | 00      |
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is possible."
    And I should see "The Self enrolment (Student) enrolment instance allows unrestricted self enrolment until Saturday, 1 January 2050, 12:00 AM."
    And ".course-hint-selfenrol" "css_element" should exist
    When I am on the "Course 1" "enrolment methods" page
    And I click on "Edit" "link" in the "Self enrolment (Student)" "table_row"
    And I set the following fields to these values:
      | id_enrolstartdate_enabled | 1       |
    # We can't use the ##yesterday## notation here.
      | id_enrolstartdate_day     | 1       |
      | id_enrolstartdate_month   | January |
      | id_enrolstartdate_year    | 2019    |
      | id_enrolstartdate_hour    | 00      |
      | id_enrolstartdate_minute  | 00      |
      | id_enrolenddate_enabled   | 0       |
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is possible."
    And I should see "The Self enrolment (Student) enrolment instance allows unrestricted self enrolment currently."
    And ".course-hint-selfenrol" "css_element" should exist
    When I am on the "Course 1" "enrolment methods" page
    And I click on "Edit" "link" in the "Self enrolment (Student)" "table_row"
    And I set the following fields to these values:
      | id_enrolstartdate_enabled | 1       |
    # We can't use the ##tomorrow## notation here. This test will break in the year 2050.
      | id_enrolstartdate_day     | 1       |
      | id_enrolstartdate_month   | January |
      | id_enrolstartdate_year    | 2050    |
      | id_enrolstartdate_hour    | 00      |
      | id_enrolstartdate_minute  | 00      |
      | id_enrolenddate_enabled   | 0       |
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is planned to become possible."
    And I should see "The Self enrolment (Student) enrolment instance allows unrestricted self enrolment from Saturday, 1 January 2050, 12:00 AM on."
    And ".course-hint-selfenrol" "css_element" should exist
    When I am on the "Course 1" "enrolment methods" page
    And I click on "Edit" "link" in the "Self enrolment (Student)" "table_row"
    And I set the following fields to these values:
      | id_enrolstartdate_enabled | 1       |
    # We can't use the ##Monday next week## notation here. This test will break in the year 2050.
      | id_enrolstartdate_day     | 1       |
      | id_enrolstartdate_month   | January |
      | id_enrolstartdate_year    | 2050    |
      | id_enrolstartdate_hour    | 00      |
      | id_enrolstartdate_minute  | 00      |
      | id_enrolenddate_enabled   | 1       |
    # We can't use the ##Tuesday next week## notation here. This test will break in the year 2050.
      | id_enrolenddate_day       | 2       |
      | id_enrolenddate_month     | January |
      | id_enrolenddate_year      | 2050    |
      | id_enrolenddate_hour      | 00      |
      | id_enrolenddate_minute    | 00      |
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is planned to become possible."
    And I should see "The Self enrolment (Student) enrolment instance allows unrestricted self enrolment from Saturday, 1 January 2050, 12:00 AM until Sunday, 2 January 2050, 12:00 AM."
    And ".course-hint-selfenrol" "css_element" should exist
    When I am on the "Course 1" "enrolment methods" page
    And I click on "Edit" "link" in the "Self enrolment (Student)" "table_row"
    And I set the following fields to these values:
      | id_enrolstartdate_enabled | 1       |
    # We can't use the ##yesterday## notation here.
      | id_enrolstartdate_day     | 1       |
      | id_enrolstartdate_month   | January |
      | id_enrolstartdate_year    | 2019    |
      | id_enrolstartdate_hour    | 00      |
      | id_enrolstartdate_minute  | 00      |
      | id_enrolenddate_enabled   | 1       |
    # We can't use the ##tomorrow## notation here. This test will break in the year 2050.
      | id_enrolenddate_day       | 1       |
      | id_enrolenddate_month     | January |
      | id_enrolenddate_year      | 2050    |
      | id_enrolenddate_hour      | 00      |
      | id_enrolenddate_minute    | 00      |
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is possible."
    And I should see "The Self enrolment (Student) enrolment instance allows unrestricted self enrolment until Saturday, 1 January 2050, 12:00 AM."
    And ".course-hint-selfenrol" "css_element" should exist
    When I am on the "Course 1" "enrolment methods" page
    And I click on "Edit" "link" in the "Self enrolment (Student)" "table_row"
    And I set the following fields to these values:
      | id_enrolstartdate_enabled | 1       |
    # We can't use the ##3 days ago## notation here.
      | id_enrolstartdate_day     | 1       |
      | id_enrolstartdate_month   | January |
      | id_enrolstartdate_year    | 2018    |
      | id_enrolstartdate_hour    | 00      |
      | id_enrolstartdate_minute  | 00      |
      | id_enrolenddate_enabled   | 1       |
    # We can't use the ##2 days ago## notation here.
      | id_enrolenddate_day       | 1       |
      | id_enrolenddate_month     | January |
      | id_enrolenddate_year      | 2019    |
      | id_enrolenddate_hour      | 00      |
      | id_enrolenddate_minute    | 00      |
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should not see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should not exist

  Scenario: Setting: Show hint for self enrolment without an enrolment key - Enable the setting and add more than one self enrolment instance
    Given the following config values are set as admin:
      | config                  | value | plugin            |
      | showhintcourseselfenrol | yes   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should not see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And ".course-hint-selfenrol" "css_element" should not exist
    And I am on the "Course 1" "enrolment methods" page
    When I click on "Enable" "link" in the "Self enrolment (Student)" "table_row"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is"
    And I should see "The Self enrolment (Student) enrolment instance allows unrestricted self enrolment indefinitely."
    And ".course-hint-selfenrol" "css_element" should exist
    When I add "Self enrolment" enrolment method in "Course 1" with:
      | Custom instance name | Custom self enrolment |
    And I am on the "Course 1" "enrolment methods" page
    And I click on "Edit" "link" in the "Custom self enrolment" "table_row"
    And I set the following fields to these values:
      | id_enrolstartdate_enabled | 0       |
      | id_enrolenddate_enabled   | 1       |
    # We can't use the ##tomorrow## notation here. This test will break in the year 2050.
      | id_enrolenddate_day       | 1       |
      | id_enrolenddate_month     | January |
      | id_enrolenddate_year      | 2050    |
      | id_enrolenddate_hour      | 00      |
      | id_enrolenddate_minute    | 00      |
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is possible."
    And I should see "The Self enrolment (Student) enrolment instance allows unrestricted self enrolment indefinitely."
    And I should see "The Custom self enrolment enrolment instance allows unrestricted self enrolment until Saturday, 1 January 2050, 12:00 AM."
    And ".course-hint-selfenrol" "css_element" should exist
    When I am on the "Course 1" "enrolment methods" page
    And I click on "Edit" "link" in the "Self enrolment (Student)" "table_row"
    And I set the following fields to these values:
      | Enrolment key | 1234 |
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should see "This course is currently visible to everyone and self enrolment without an enrolment key is possible."
    And I should not see "The Self enrolment (Student) enrolment instance allows unrestricted self enrolment indefinitely."
    And I should see "The Custom self enrolment enrolment instance allows unrestricted self enrolment until Saturday, 1 January 2050, 12:00 AM."
    And ".course-hint-selfenrol" "css_element" should exist
