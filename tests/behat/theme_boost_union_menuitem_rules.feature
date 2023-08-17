@theme @theme_boost_union @theme_boost_union_smartmenu @theme_boost_union_menuitemrules

Feature: Configuring the theme_boost_union plugin on the "Smart menu items" page, applying different configuration to the individual menu item
  In order to use th features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given I log in as "admin"
    And I navigate to "Language > Language packs" in site administration
    And I set the field "Available language packs" to "fr"
    When I press "Install selected language pack(s)"
    Then I should see "Language pack 'fr' was successfully installed"
    And I set the field "Available language packs" to "de"
    When I press "Install selected language pack(s)"
    Then I should see "Language pack 'de' was successfully installed"
    And I am on homepage
    Given the following "courses" exist:
      | fullname| shortname | category |
      | Test | C1 | 0 |
    And the following "users" exist:
      | username | firstname | lastname | email             | lang |
      | student1 | student   | User 1   | student1@test.com | en   |
      | student2 | student2  | User 2   | student2@test.com | fr   |
      | teacher  | Teacher   | User 1   | teacher2@test.com | de   |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher  | C1     | editingteacher |
      | student1 | C1     | student        |
      | admin    | C1     | manager        |
    And the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | CH1      |
      | Cohort 2 | CH2      |
    And the following "cohort members" exist:
      | user     | cohort |
      | student1 | CH1    |
      | teacher  | CH2    |

    Given I log in as "admin"
    And I create menu with the following fields to these values:
    | Title     | Quick Links              |
    | Locations | Main, Menu, User, Bottom |
    And I set "Quick Links" items with the following fields to these values:
    | Title     | Resources           |
    | Type      | Static              |
    | URL       | https://moodle.org  |
    And I set "Quick Links" items with the following fields to these values:
    | Title     | Courses                   |
    | Type      | Static                    |
    | URL       | https://moodle.org/course |

  @javascript
  Scenario: Smartmenuitem: Access Rules - Based on the user roles
    # Given I log in as "admin"
    Given I navigate to smartmenus
    And I should see "Quick Links" in the "smartmenus" "table"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    Then I click on ".action-list-items" "css_element" in the "Quick Links" "table_row"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By role" to "Manager"
    And I click on "Save changes" "button"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    Then I log out
    And I log in as "student1"
    And I should not see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    Then I log in as "admin"
    And I navigate to smartmenu "Quick Links" items
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By role" to "Manager, Student"
    And I click on "Save changes" "button"
    And I log out
    Then I log in as "student1"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    When I log in as "teacher"
    And I should not see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    Then I log in as "admin"
    And I navigate to smartmenu "Quick Links" items
    Then I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By role" to "Manager, Student, Teacher"
    And I click on "Save changes" "button"
    And I log out
    Then I log in as "teacher"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenuitem: Access Rules - Based on the user assignment in cohorts
    Given I navigate to smartmenus
    And I should see "Quick Links" in the "smartmenus" "table"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    Then I click on ".action-list-items" "css_element" in the "Quick Links" "table_row"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By cohort" to "Cohort 1"
    And I click on "Save changes" "button"
    Then I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And I should not see "Resources" in the ".primary-navigation" "css_element"
    Then I log out
    And I log in as "student1"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    When I log in as "teacher"
    And I should not see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    Then I log in as "admin"
    And I add "teacher" user to "CH1" cohort members
    And I log out
    Then I log in as "teacher"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenuitem: Access Rules - Based on the user assignments in multiple cohorts
    Given I navigate to smartmenu "Quick Links" items
    And I should see "Resources" in the "smartmenus_item" "table"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    Then I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By cohort" to "Cohort 1, Cohort 2"
    And I set the field "Operator" to "Any"
    And I click on "Save changes" "button"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And I should not see "Resources" in the ".primary-navigation" "css_element"
    Then I log out
    And I log in as "student1"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    When I log in as "teacher"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    Then I log in as "admin"
    And I navigate to smartmenu "Quick Links" items
    Then I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "Operator" to "All"
    And I click on "Save changes" "button"
    And I log out
    Then I log in as "teacher"
    And I should not see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student1"
    And I should not see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    Then I log in as "admin"
    And I add "student1" user to "CH2" cohort members
    And I log out
    And I log in as "student1"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenuitem: Access Rules - Display menus for based on user prefered language
    Given I log in as "teacher"
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Preferences" "link" in the "#usermenu-carousel" "css_element"
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "Deutsch ‎(de)‎"
    And I click on "Save changes" "button"
    Given I log in as "student2"
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Preferences" "link" in the "#usermenu-carousel" "css_element"
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "Français ‎(fr)‎"
    And I click on "Save changes" "button"
    Given I log in as "admin"
    And I navigate to smartmenu "Quick Links" items
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By language" to "English"
    And I click on "Save changes" "button"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And I should see "Resources" in the ".primary-navigation" "css_element"
    And I log out
    Then I log in as "student1"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    And I should not see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    Then I log in as "admin"
    And I navigate to smartmenu "Quick Links" items
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By language" to "English, Deutsch"
    And I click on "Save changes" "button"
    And I log out
    Then I log in as "teacher"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    Then I log out
    And I log in as "student1"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    And I should not see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario: Smartmenuitem: Access Rules - Display menus based on multiple conditions
    Given I log in as "teacher"
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Preferences" "link" in the "#usermenu-carousel" "css_element"
    And I click on "Preferred language" "link"
    And I set the field "Preferred language" to "Deutsch ‎(de)‎"
    And I click on "Save changes" "button"
    Given I log in as "admin"
    And I navigate to smartmenu "Quick Links" items
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By role" to "Manager, Student"
    And I set the field "By cohort" to "Cohort 1"
    And I set the field "By language" to "English"
    And I click on "Save changes" "button"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And I should not see "Resources" in the ".primary-navigation" "css_element"
    And I log out
    Then I log in as "student1"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    And I should not see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "admin"
    And I navigate to smartmenu "Quick Links" items
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By role" to "Manager, Student, Teacher"
    And I set the field "By cohort" to "Cohort 1, Cohort 2"
    And I set the field "By language" to "English, Deutsch"
    And I click on "Save changes" "button"
    And I log out
    Then I log in as "teacher"
    And I should see menu "Quick Links" item "Resources" in location "Main, Menu, User, Bottom"
