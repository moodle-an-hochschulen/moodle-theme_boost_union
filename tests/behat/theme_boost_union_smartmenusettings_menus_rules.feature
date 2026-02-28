@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menus @theme_boost_union_smartmenusettings_menus_rules
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, applying different rules to the individual smart menus
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "courses" exist:
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
    And I log in as "admin"
    And I navigate to "Users > Permissions > User policies" in site administration
    And I set the field "Role for visitors" to "Visitor (visitor)"
    And I press "Save changes"
    And the following "theme_boost_union > smart menu" exists:
      | title       | Role links                                       |
      | location    | Main navigation, Menu bar, User menu, Bottom bar |
      | roles       | <byrole>                                         |
      | rolecontext | <context>                                        |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Role links         |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
    When I am on homepage
    Then I <adminshouldorshouldnot> see smart menu "Role links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "coursemanager"
    Then I <managershouldorshouldnot> see smart menu "Role links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Role links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Role links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "systemmanager"
    Then I <systemshouldorshouldnot> see smart menu "Role links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "guest"
    Then I <guestshouldorshouldnot> see smart menu "Role links" in location "Main, Menu, Bottom"
    And I log out
    And I <visitorshouldorshouldnot> see smart menu "Role links" in location "Main, Menu, Bottom"

    Examples:
      | byrole                           | context | student1shouldorshouldnot | teachershouldorshouldnot | managershouldorshouldnot | guestshouldorshouldnot | adminshouldorshouldnot | systemshouldorshouldnot | visitorshouldorshouldnot |
      | manager                          | Any     | should not                | should not               | should                   | should not             | should not             | should                  | should not               |
      | manager, student                 | Any     | should                    | should not               | should                   | should not             | should not             | should                  | should not               |
      | manager, student, editingteacher | Any     | should                    | should                   | should                   | should not             | should not             | should                  | should not               |
      | manager, student, editingteacher | System  | should not                | should not               | should not               | should not             | should not             | should                  | should not               |
      | user                             | Any     | should                    | should                   | should                   | should not             | should                 | should                  | should not               |
      | guest                            | Any     | should not                | should not               | should not               | should                 | should not             | should not              | should not               |
      | visitor                          | Any     | should not                | should not               | should not               | should not             | should not             | should not              | should                   |

  Scenario Outline: Smartmenu: Menus: Rules - Show smart menu based on being site admin
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Admin links                                      |
      | location | Main navigation, Menu bar, User menu, Bottom bar |
      | byadmin  | <byadmin>                                        |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Admin links        |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
    When I log in as "admin"
    Then I <adminshouldorshouldnot> see smart menu "Admin links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Admin links" in location "Main, Menu, User, Bottom"

    Examples:
      | byadmin          | adminshouldorshouldnot | student1shouldorshouldnot |
      | All users        | should                 | should                    |
      | Site admins only | should                 | should not                |
      | Non-admins only  | should not             | should                    |

  Scenario Outline: Smartmenu: Menus: Rules - Show smart menu based on the user assignment in single cohorts
    Given the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | CH1      |
      | Cohort 2 | CH2      |
    And the following "cohort members" exist:
      | user     | cohort |
      | student1 | CH1    |
      | student2 | CH1    |
      | student2 | CH2    |
      | teacher  | CH2    |
    And the following "theme_boost_union > smart menu" exists:
      | title    | Cohort links                                     |
      | location | Main navigation, Menu bar, User menu, Bottom bar |
      | cohorts  | <bycohort>                                       |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Cohort links       |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
    When I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Cohort links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    Then I <student2shouldorshouldnot> see smart menu "Cohort links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Cohort links" in location "Main, Menu, User, Bottom"

    Examples:
      | bycohort | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | CH1      | should                    | should                    | should not               |
      | CH2      | should not                | should                    | should                   |

  Scenario Outline: Smartmenu: Menus: Rules - Show smart menu based on the user assignment in multiple cohorts
    Given the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | CH1      |
      | Cohort 2 | CH2      |
    And the following "cohort members" exist:
      | user     | cohort |
      | student1 | CH1    |
      | student2 | CH1    |
      | student2 | CH2    |
      | teacher  | CH2    |
    And the following "theme_boost_union > smart menu" exists:
      | title    | Multi cohort links                               |
      | location | Main navigation, Menu bar, User menu, Bottom bar |
      | cohorts  | <bycohorts>                                      |
      | operator | <operator>                                       |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Multi cohort links |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
    When I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Multi cohort links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    Then I <student2shouldorshouldnot> see smart menu "Multi cohort links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Multi cohort links" in location "Main, Menu, User, Bottom"

    Examples:
      | bycohorts | operator | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | CH1, CH2  | Any      | should                    | should                    | should                   |
      | CH1, CH2  | All      | should not                | should                    | should not               |

  Scenario Outline: Smartmenu: Menus: Rules - Show smart menu based on the user's prefered language
    Given the following "language packs" exist:
      | language |
      | de       |
      | fr       |
    And the following "theme_boost_union > smart menu" exists:
      | title     | Language links                                   |
      | location  | Main navigation, Menu bar, User menu, Bottom bar |
      | languages | <bylanguage>                                     |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Language links     |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
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
    And I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Language links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    Then I <student2shouldorshouldnot> see smart menu "Language links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Language links" in location "Main, Menu, User, Bottom"

    Examples:
      | bylanguage | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | en         | should                    | should not                | should not               |
      | en, de     | should                    | should not                | should                   |

  Scenario: Smartmenu: Menus: Rules - Show smart menu based on the user's prefered language - Handle the case of forced language courses
    Given the following "language packs" exist:
      | language |
      | de       |
      | fr       |
    And the following "courses" exist:
      | fullname           | shortname | category | lang |
      | Forced Language de | FL1       | 0        | de   |
      | Forced Language fr | FL2       | 0        | fr   |
    And the following "theme_boost_union > smart menu" exists:
      | title     | Language de     |
      | location  | Main navigation |
      | languages | de              |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Language de          |
      | title    | Language menu node 1 |
      | itemtype | Static               |
      | url      | /bar                 |
    And the following "theme_boost_union > smart menu" exists:
      | title     | Language fr     |
      | location  | Main navigation |
      | languages | fr              |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Language fr          |
      | title    | Language menu node 2 |
      | itemtype | Static               |
      | url      | /bar                 |
    And I log in as "student1"
    And I am on "Forced Language de" course homepage
    Then I should see smart menu "Language de" in location "Main"
    And I should not see smart menu "Language fr" in location "Main"
    And I am on "Forced Language fr" course homepage
    Then I should not see smart menu "Language de" in location "Main"
    And I should see smart menu "Language fr" in location "Main"
    And I am on "Test" course homepage
    Then I should not see smart menu "Language de" in location "Main"
    And I should not see smart menu "Language fr" in location "Main"

  @javascript
  Scenario: Smartmenu: Menus: Rules - Show smart menu based on the user's prefered language - Handle the case of guests changing their language
    Given the following "language packs" exist:
      | language |
      | de       |
      | fr       |
    And the following config values are set as admin:
      | name             | value |
      | guestloginbutton | 1     |
      | autologinguests  | 1     |
      | forcelogin       | 1     |
    And the following "courses" exist:
      | fullname           | shortname | category | lang |
      | Forced Language de | FL1       | 0        | de   |
      | Forced Language fr | FL2       | 0        | fr   |
    And the following "theme_boost_union > smart menu" exists:
      | title     | Language de     |
      | location  | Main navigation |
      | languages | de              |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Language de          |
      | title    | Language menu node 1 |
      | itemtype | Static               |
      | url      | /bar                 |
    And the following "theme_boost_union > smart menu" exists:
      | title     | Language fr     |
      | location  | Main navigation |
      | languages | fr              |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Language fr          |
      | title    | Language menu node 2 |
      | itemtype | Static               |
      | url      | /bar                 |
    When I am on site homepage
    And I should see "You are currently using guest access"
    Then I should not see smart menu "Language de" in location "Main"
    And I should not see smart menu "Language fr" in location "Main"
    And I click on "#lang-menu-toggle" "css_element"
    And I click on "Deutsch ‎(de)‎" "link" in the "#lang-action-menu" "css_element"
    Then I should see smart menu "Language de" in location "Main"
    And I should not see smart menu "Language fr" in location "Main"
    And I click on "#lang-menu-toggle" "css_element"
    And I click on "Français ‎(fr)‎" "link" in the "#lang-action-menu" "css_element"
    Then I should not see smart menu "Language de" in location "Main"
    And I should see smart menu "Language fr" in location "Main"

  Scenario Outline: Smartmenu: Menus: Rules - Show the menus based on the custom date range
    Given the following "theme_boost_union > smart menu" exists:
      | title      | Date range links                                 |
      | location   | Main navigation, Menu bar, User menu, Bottom bar |
      | start_date | <start_date>                                     |
      | end_date   | <end_date>                                       |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Date range links   |
      | title    | Resources          |
      | itemtype | Static             |
      | url      | https://moodle.org |
    When I log in as "admin"
    Then I <menushouldorshouldnot> see smart menu "Date range links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student1"
    Then I <menushouldorshouldnot> see smart menu "Date range links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <menushouldorshouldnot> see smart menu "Date range links" in location "Main, Menu, User, Bottom"

    Examples:
      | start_date | end_date   | menushouldorshouldnot |
      | 1672531200 | 2524608000 | should                |
      | 2493072000 | 2524608000 | should not            |
      | 1672531200 | 1701388800 | should not            |
      | 1672531200 | 0          | should                |
      | 2493072000 | 0          | should not            |
      | 0          | 2524608000 | should                |
      | 0          | 1701388800 | should not            |

  Scenario Outline: Smartmenu: Menus: Rules - Show smart menu based on multiple conditions
    Given the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | CH1      |
      | Cohort 2 | CH2      |
    And the following "cohort members" exist:
      | user     | cohort |
      | student1 | CH1    |
      | student2 | CH1    |
      | student2 | CH2    |
      | teacher  | CH2    |
    And the following "language packs" exist:
      | language |
      | de       |
      | fr       |
    And I log in as "teacher"
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
    And the following "theme_boost_union > smart menu" exists:
      | title     | Quick links                                      |
      | location  | Main navigation, Menu bar, User menu, Bottom bar |
      | roles     | <roles>                                          |
      | cohorts   | <cohorts>                                        |
      | languages | <languages>                                      |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links  |
      | title    | Quick link 1 |
      | itemtype | Static       |
      | url      | /foo         |
    When I log in as "student1"
    Then I <student1shouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "student2"
    Then I <student2shouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"
    And I log out
    And I log in as "teacher"
    Then I <teachershouldorshouldnot> see smart menu "Quick links" in location "Main, Menu, User, Bottom"

    Examples:
      | roles                            | cohorts  | languages | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | manager, student                 | CH1      | en        | should                    | should not                | should not               |
      | manager, student, editingteacher | CH1, CH2 | en, de    | should                    | should not                | should                   |

  @javascript
  Scenario: Smartmenu: Menus: Rules - Deleting a cohort used for a rule removes it from the rule
    Given the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | CH1      |
      | Cohort 2 | CH2      |
    And the following "theme_boost_union > smart menu" exists:
      | title    | Quick links     |
      | location | Main navigation |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links  |
      | title    | Quick link 1 |
      | itemtype | Static       |
      | url      | /foo         |
    When I log in as "admin"
    And I navigate to smart menus
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
    And the following "theme_boost_union > smart menu" exists:
      | title    | Quick links     |
      | location | Main navigation |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links  |
      | title    | Quick link 1 |
      | itemtype | Static       |
      | url      | /foo         |
    When I log in as "admin"
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

  @javascript
  Scenario: Smartmenu: Menus: Rules - Smart menu is not repeated when user cache is reset on language change
  # The steps of this scenario are based on the issue report in
  # https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/issues/800
  # The scenario mainly serves to check that the issue is fixed and doesn't regress.
    Given the following "language packs" exist:
      | language |
      | de       |
      | fr       |
    And the following "theme_boost_union > smart menu" exists:
      | title    | Classroom       |
      | location | Main navigation |
      | mode     | Inline          |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Classroom           |
      | title    | Classroom menu node |
      | itemtype | Static              |
      | url      | /foo                |
    And the following "theme_boost_union > smart menu" exists:
      | title    | My courses       |
      | location | Main navigation  |
      | type     | Card             |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | My courses      |
      | title    | My courses      |
      | itemtype | Dynamic courses |
    And the following "theme_boost_union > smart menu" exists:
      | title    | Resources       |
      | location | Main navigation |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Resources           |
      | title    | Resources menu node |
      | itemtype | Static              |
      | url      | /foo                |
    When I log in as "admin"
    And I change the viewport size to "large"
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    And "Resources menu node" "theme_boost_union > Smart menu item" should exist in the "Resources" "theme_boost_union > Main menu smart menu"
    And "(//a[contains(@class, 'dropdown-toggle') and normalize-space()='Resources'])[2]" "xpath_element" should not exist in the ".primary-navigation" "css_element"
    Then I navigate to "Appearance > Boost Union > Smart menus" in site administration
    And I should see "My courses" in the "smartmenus" "table"
    And I click on ".action-copy" "css_element" in the "My courses" "table_row"
    And "(//th[contains(@id, 'smartmenus') and normalize-space()='My courses'])[2]" "xpath_element" should exist in the "smartmenus" "table"
    And I click on ".action-edit" "css_element" in the "My courses" "table_row"
    And I expand all fieldsets
    And I set the field "By language" to "English ‎(en)‎"
    And I set the field "Title" to "My courses - en"
    And I click on "Save and return" "button"
    Then I click on ".action-edit" "css_element" in the "//th[contains(@id, 'smartmenus') and normalize-space()='My courses']/parent::tr" "xpath_element"
    And I expand all fieldsets
    And I set the field "By language" to "Deutsch ‎(de)‎"
    And I set the field "Title" to "Meine Kurse"
    And I click on "Save and return" "button"
    Then I click on ".sort-smartmenus-up-action" "css_element" in the "Meine Kurse" "table_row"
    Then I should not see "Meine Kurse" in the ".primary-navigation" "css_element"
    And I should see "My courses - en" in the ".primary-navigation" "css_element"
    And I log out
    Then I log in as "teacher"
    And I should see "My courses - en" in the ".primary-navigation" "css_element"
    And I should not see "Meine Kurse" in the ".primary-navigation" "css_element"
    When I follow "Language" in the user menu
    And I click on "Deutsch" "link"
    Then I should see "Meine Kurse" in the ".primary-navigation" "css_element"
    And I should not see "My courses - en" in the ".primary-navigation" "css_element"
    And "(//a[contains(@class, 'dropdown-toggle') and normalize-space()='Resources'])[2]" "xpath_element" should not exist in the ".primary-navigation" "css_element"
