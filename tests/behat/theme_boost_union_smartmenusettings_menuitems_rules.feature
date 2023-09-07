@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_rules
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, applying different rules to the individual smart menu items
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given I log in as "admin"
    And I navigate to "Language > Language packs" in site administration
    And I set the field "Available language packs" to "fr"
    And I press "Install selected language pack(s)"
    And I should see "Language pack 'fr' was successfully installed"
    And the "Installed language packs" select box should contain "fr"
    And I set the field "Available language packs" to "de"
    And I press "Install selected language pack(s)"
    And I should see "Language pack 'de' was successfully installed"
    And the "Installed language packs" select box should contain "de"
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

  @javascript
  Scenario Outline: Smartmenu: Menu items: Rules - Show smart menu item based on the user roles
    When I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I should see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By role" to "<byrole>"
    And I click on "Save changes" "button"
    And I should not see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"

    Examples:
      | byrole                    | student1shouldorshouldnot | teachershouldorshouldnot |
      | Manager                   | should not                | should not               |
      | Manager, Student          | should                    | should not               |
      | Manager, Student, Teacher | should                    | should                   |

  @javascript
  Scenario Outline: Smartmenu: Menu items: Rules - Show smart menu item based on the user assignment in single cohorts
    When I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I should see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By cohort" to "<bycohort>"
    And I click on "Save changes" "button"
    And I should not see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    Then I <student2shouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"

    Examples:
      | bycohort | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | Cohort 1 | should                    | should                    | should not               |
      | Cohort 2 | should not                | should                    | should                   |

  @javascript
  Scenario Outline: Smartmenu: Menu items: Rules - Show smart menu item based on the user assignment in multiple cohorts
    When I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I should see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By cohort" to "<bycohorts>"
    And I set the field "Operator" to "<operator>"
    And I click on "Save changes" "button"
    And I should not see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    Then I <student2shouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"

    Examples:
      | bycohorts          | operator | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | Cohort 1, Cohort 2 | Any      | should                    | should                    | should                   |
      | Cohort 1, Cohort 2 | All      | should not                | should                    | should not               |

  @javascript
  Scenario Outline: Smartmenu: Menu items: Rules - Show smart menu item based on the user's prefered language
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
    When I navigate to smart menus
    And I should see "Quick links" in the "smartmenus" "table"
    And I should see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By language" to "<bylanguage>"
    And I click on "Save changes" "button"
    And I log out
    And I log in as "student1"
    And I <student1shouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    And I <student2shouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    And I <teachershouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"

    Examples:
      | bylanguage       | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | English          | should                    | should not                | should not               |
      | English, Deutsch | should                    | should not                | should                   |

  @javascript
  Scenario Outline: Smartmenu: Menu items: Rules - Show smart menu item based on multiple conditions
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
    And I should see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I click on ".action-list-items" "css_element" in the "Quick links" "table_row"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "By role" to "<byrole>"
    And I set the field "By cohort" to "<bycohort>"
    And I set the field "By language" to "<bylanguage>"
    And I click on "Save changes" "button"
    And I log out
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    Then I <student2shouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Quick links" item "Resources" in location "Main, Menu, User, Bottom"

    Examples:
      | byrole                    | bycohort           | bylanguage       | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | Manager, Student          | Cohort 1           | English          | should                    | should not                | should not               |
      | Manager, Student, Teacher | Cohort 1, Cohort 2 | English, Deutsch | should                    | should not                | should                   |
