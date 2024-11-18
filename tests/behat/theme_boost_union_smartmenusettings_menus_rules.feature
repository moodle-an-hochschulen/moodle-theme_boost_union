@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menus @theme_boost_union_smartmenusettings_menus_rules
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, applying different rules to the individual smart menus
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given I log in as "admin"
    And I am on homepage
    And the following "courses" exist:
      | fullname | shortname | category |
      | Test     | C1        | 0        |
    And the following "users" exist:
      | username |
      | student1 |
      | student2 |
      | teacher  |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher  | C1     | editingteacher |
      | student1 | C1     | student        |
    And the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | CH1      |
      | Cohort 2 | CH2      |
    And the following "cohort members" exist:
      | user     | cohort |
      | student1 | CH1    |
      | student2 | CH1    |
      | student2 | CH2    |
      | teacher  | CH2    |
    And I create smart menu with the following fields to these values:
      | Title            | Quick links              |
      | Menu location(s) | Main, Menu, User, Bottom |
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Resources          |
      | Menu item type | Static             |
      | URL            | https://moodle.org |
    And the following "language packs" exist:
      | language |
      | de       |
      | fr       |

  @javascript
  Scenario Outline: Smartmenu: Menus: Rules - Show smart menu based on the user roles
    Given the following "users" exist:
      | username      |
      | coursemanager |
      | systemmanager |
    And the following "course enrolments" exist:
      | user          | course | role    |
      | coursemanager | C1     | manager |
    And the following "system role assigns" exist:
      | user          | course               | role    |
      | systemmanager | Acceptance test site | manager |
    And the following "roles" exist:
      | name    | shortname | description     |
      | Visitor | visitor   | My visitor role |
    And I navigate to "Users > Permissions > User policies" in site administration
    And I set the field "Role for visitors" to "Visitor (visitor)"
    And I press "Save changes"
    When I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I should see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And I set the field "By role" to "<byrole>"
    And I set the field "Context" to "<context>"
    And I click on "Save and return" "button"
    And I <adminshouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "coursemanager"
    Then I <managershouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "systemmanager"
    Then I <systemshouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "guest"
    Then I <guestshouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, Bottom"
    And I log out
    And I <visitorshouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, Bottom"

    Examples:
      | byrole                    | context | student1shouldorshouldnot | teachershouldorshouldnot | managershouldorshouldnot | guestshouldorshouldnot | adminshouldorshouldnot | systemshouldorshouldnot | visitorshouldorshouldnot |
      | Manager                   | Any     | should not                | should not               | should                   | should not             | should not             | should                  | should not               |
      | Manager, Student          | Any     | should                    | should not               | should                   | should not             | should not             | should                  | should not               |
      | Manager, Student, Teacher | Any     | should                    | should                   | should                   | should not             | should not             | should                  | should not               |
      | Manager, Student, Teacher | System  | should not                | should not               | should not               | should not             | should not             | should                  | should not               |
      | Authenticated user        | Any     | should                    | should                   | should                   | should not             | should                 | should                  | should not               |
      | Guest                     | Any     | should not                | should not               | should not               | should                 | should not             | should not              | should not               |
      | Visitor                   | Any     | should not                | should not               | should not               | should not             | should not             | should not              | should                   |

  @javascript
  Scenario Outline: Smartmenu: Menus: Rules - Show smart menu based on being site admin
    When I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I should see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And I set the field "Show to" to "<byadmin>"
    And I click on "Save and return" "button"
    And I <adminshouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"

    Examples:
      | byadmin | adminshouldorshouldnot | student1shouldorshouldnot |
      | 0       | should                 | should                    |
      | 1       | should                 | should not                |
      | 2       | should not             | should                    |

  @javascript
  Scenario Outline: Smartmenu: Menus: Rules - Show smart menu based on the user assignment in single cohorts
    When I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I should see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And I set the field "By cohort" to "<bycohort>"
    And I click on "Save and return" "button"
    And I should not see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    Then I <student2shouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"

    Examples:
      | bycohort | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | Cohort 1 | should                    | should                    | should not               |
      | Cohort 2 | should not                | should                    | should                   |

  @javascript
  Scenario Outline: Smartmenu: Menus: Rules - Show smart menu based on the user assignment in multiple cohorts
    When I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I should see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And I set the field "By cohort" to "<bycohorts>"
    And I set the field "Operator" to "<operator>"
    And I click on "Save and return" "button"
    And I should not see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    Then I <student2shouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"

    Examples:
      | bycohorts          | operator | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | Cohort 1, Cohort 2 | Any      | should                    | should                    | should                   |
      | Cohort 1, Cohort 2 | All      | should not                | should                    | should not               |

  @javascript
  Scenario Outline: Smartmenu: Menus: Rules - Show smart menu based on the user's prefered language
    When I log in as "teacher"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "Deutsch ‎(de)‎"
    And I press "Save changes"
    And I log out
    And I log in as "student1"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "English ‎(en)‎"
    And I press "Save changes"
    And I log out
    And I log in as "student2"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "Français ‎(fr)‎"
    And I press "Save changes"
    And I log out
    When I log in as "admin"
    And I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I should see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And I set the field "By language" to "<bylanguage>"
    And I click on "Save and return" "button"
    And I log out
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    Then I <student2shouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"

    Examples:
      | bylanguage       | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | English          | should                    | should not                | should not               |
      | English, Deutsch | should                    | should not                | should                   |

  @javascript
  Scenario Outline: Smartmenu: Menus: Rules - Show the menus based on the custom date range
    When I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I should see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And I set the following fields to these values:
      | id_start_date_enabled    | <startenabled> |
      | id_end_date_enabled      | <endenabled>   |
      | id_start_date_day        | <start_day>    |
      | id_start_date_month      | <start_month>  |
      | id_start_date_year       | <start_year>   |
      | id_end_date_day          | <end_day>      |
      | id_end_date_month        | <end_month>    |
      | id_end_date_year         | <end_year>     |
    And I click on "Save and return" "button"
    And I <menushouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    Then I log out
    And I log in as "student1"
    And I <menushouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    When I log in as "teacher"
    And I <menushouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"

    Examples:
      | startenabled |endenabled | start_day | start_month | start_year | end_day | end_month | end_year | menushouldorshouldnot |
      | 1            | 1         | 1         | January     | 2023       | 1       | January   | 2050     | should                |
      | 1            | 1         | 1         | January     | 2049       | 1       | January   | 2050     | should not            |
      | 1            | 1         | 1         | January     | 2023       | 1       | December  | 2023     | should not            |
      | 1            | 0         | 1         | January     | 2023       | 1       | 1         | 2023     | should                |
      | 1            | 0         | 1         | January     | 2049       | 1       | 1         | 2023     | should not            |
      | 0            | 1         | 1         | January     | 2023       | 1       | December  | 2050     | should                |
      | 0            | 1         | 1         | January     | 2023       | 1       | December  | 2023     | should not            |

  @javascript
  Scenario Outline: Smartmenu: Menus: Rules - Show smart menu based on multiple conditions
    Given I log in as "teacher"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "Deutsch ‎(de)‎"
    And I press "Save changes"
    And I log out
    And I log in as "student1"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "English ‎(en)‎"
    And I press "Save changes"
    And I log out
    And I log in as "student2"
    And I follow "Preferences" in the user menu
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "Français ‎(fr)‎"
    And I press "Save changes"
    And I log out
    When I log in as "admin"
    And I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I should see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And I set the field "By role" to "<byrole>"
    And I set the field "By cohort" to "<bycohort>"
    And I set the field "By language" to "<bylanguage>"
    And I click on "Save and return" "button"
    And I log out
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    Then I <student2shouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"

    Examples:
      | byrole                    | bycohort           | bylanguage       | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | Manager, Student          | Cohort 1           | English          | should                    | should not                | should not               |
      | Manager, Student, Teacher | Cohort 1, Cohort 2 | English, Deutsch | should                    | should not                | should                   |

  @javascript
  Scenario: Smartmenu: Menus: Rules - Deleting a cohort used for a rule removes it from the rule
    Given I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And I set the field "By cohort" to "Cohort 1, Cohort 2"
    And I set the field "Operator" to "Any"
    And I click on "Save and return" "button"
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And "Cohort 1" "autocomplete_selection" should exist
    And "Cohort 2" "autocomplete_selection" should exist
    When I navigate to "Users > Cohorts" in site administration
    And I open the action menu in "Cohort 1" "table_row"
    And I choose "Delete" in the open action menu
    And I click on "Delete" "button" in the "Delete selected" "dialogue"
    And I am on site homepage
    And I navigate to smart menus
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And "Cohort 1" "autocomplete_selection" should not exist
    And "Cohort 2" "autocomplete_selection" should exist

  @javascript
  Scenario: Smartmenu: Menus: Rules - Deleting a role used for a rule removes it from the rule
    Given the following "roles" exist:
      | shortname | name        |
      | test1     | Test role 1 |
      | test2     | Test role 2 |
    And I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And I set the field "By role" to "Test role 1, Test role 2"
    And I set the field "Operator" to "Any"
    And I click on "Save and return" "button"
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And "Test role 1" "autocomplete_selection" should exist
    And "Test role 2" "autocomplete_selection" should exist
    When I navigate to "Users > Define roles" in site administration
    And I click on "Delete" "link" in the "Test role 1" "table_row"
    And I press "Yes"
    And I navigate to smart menus
    And I click on ".action-edit" "css_element" in the "Quick links" "table_row"
    And I expand all fieldsets
    And "Test role 1" "autocomplete_selection" should not exist
    And "Test role 2" "autocomplete_selection" should exist
