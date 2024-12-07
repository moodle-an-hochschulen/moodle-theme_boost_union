@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_rules
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, applying different rules to the individual smart menu items
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
    And the following "theme_boost_union > smart menu" exists:
      | title    | Quick links                                      |
      | location | Main navigation, Menu bar, User menu, Bottom bar |
    # Empty menus are hidden from view. To prevent that the whole menu is missing and the test fails,
    # a sample item is created.
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links |
      | title    | Info        |
      | itemtype | Heading     |

  @javascript
  Scenario Outline: Smartmenu: Menu items: Rules - Show smart menu item based on the user roles
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
    And the following "theme_boost_union > smart menu item" exists:
      | menu        | Quick links        |
      | title       | Resources          |
      | itemtype    | Static             |
      | url         | https://moodle.org |
      | roles       | <byrole>           |
      | rolecontext | <context>          |
    Given I am logged in as "admin"
    And I navigate to "Users > Permissions > User policies" in site administration
    And I set the field "Role for visitors" to "Visitor (visitor)"
    And I press "Save changes"
    And "Resources" "theme_boost_union > Smart menu item" <adminshouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <adminshouldorshouldnot> exist in the "Quick links" "theme_boost_union > Menu bar smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <adminshouldorshouldnot> exist in the "Quick links" "theme_boost_union > User menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <adminshouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "coursemanager"
    Then "Resources" "theme_boost_union > Smart menu item" <managershouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    When I am logged in as "student1"
    Then "Resources" "theme_boost_union > Smart menu item" <student1shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    When I am logged in as "teacher"
    Then "Resources" "theme_boost_union > Smart menu item" <teachershouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    When I am logged in as "systemmanager"
    Then "Resources" "theme_boost_union > Smart menu item" <systemshouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    When I am logged in as "guest"
    Then "Resources" "theme_boost_union > Smart menu item" <guestshouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    When I log out
    Then "Resources" "theme_boost_union > Smart menu item" <visitorshouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"

    Examples:
      | byrole                           | context | student1shouldorshouldnot | teachershouldorshouldnot | managershouldorshouldnot | guestshouldorshouldnot | adminshouldorshouldnot | systemshouldorshouldnot | visitorshouldorshouldnot |
      | Manager                          | Any     | should not                | should not               | should                   | should not             | should not             | should                  | should not               |
      | Manager, Student                 | Any     | should                    | should not               | should                   | should not             | should not             | should                  | should not               |
      | Manager, Student, editingteacher | Any     | should                    | should                   | should                   | should not             | should not             | should                  | should not               |
      | Manager, Student, editingteacher | System  | should not                | should not               | should not               | should not             | should not             | should                  | should not               |
      | user                             | Any     | should                    | should                   | should                   | should not             | should                 | should                  | should not               |
      | Guest                            | Any     | should not                | should not               | should not               | should                 | should not             | should not              | should not               |
      | Visitor                          | Any     | should not                | should not               | should not               | should not             | should not             | should not              | should                   |

  @javascript
  Scenario Outline: Smartmenu: Menu items: Rules - Show smart menu item based on being site admin
    And the following "theme_boost_union > smart menu item" exists:
      | menu        | Quick links        |
      | title       | Resources          |
      | itemtype    | Static             |
      | url         | https://moodle.org |
      | byadmin     | <byadmin>          |
    When I am logged in as "admin"
    And "Resources" "theme_boost_union > Smart menu item" <adminshouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <adminshouldorshouldnot> exist in the "Quick links" "theme_boost_union > Menu bar smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <adminshouldorshouldnot> exist in the "Quick links" "theme_boost_union > User menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <adminshouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "student1"
    And "Resources" "theme_boost_union > Smart menu item" <student1shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"

    Examples:
      | byadmin          | adminshouldorshouldnot | student1shouldorshouldnot |
      | All users        | should                 | should                    |
      | Site admins only | should                 | should not                |
      | Non-admins only  | should not             | should                    |

  @javascript
  Scenario Outline: Smartmenu: Menu items: Rules - Show smart menu item based on the user assignment in single cohorts
    Given the following "theme_boost_union > smart menu item" exists:
      | menu        | Quick links        |
      | title       | Resources          |
      | itemtype    | Static             |
      | url         | https://moodle.org |
      | cohorts     | <bycohort>         |
    When I am logged in as "admin"
    And "Resources" "theme_boost_union > Smart menu item" should not exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" should not exist in the "Quick links" "theme_boost_union > Menu bar smart menu"
    And "Resources" "theme_boost_union > Smart menu item" should not exist in the "Quick links" "theme_boost_union > User menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" should not exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "student1"
    Then "Resources" "theme_boost_union > Smart menu item" <student1shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "student2"
    Then "Resources" "theme_boost_union > Smart menu item" <student2shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "teacher"
    Then "Resources" "theme_boost_union > Smart menu item" <teachershouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"

    Examples:
      | bycohort | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | CH1      | should                    | should                    | should not               |
      | CH2      | should not                | should                    | should                   |

  @javascript
  Scenario Outline: Smartmenu: Menu items: Rules - Show smart menu item based on the user assignment in multiple cohorts
    Given the following "theme_boost_union > smart menu item" exists:
      | menu        | Quick links        |
      | title       | Resources          |
      | itemtype    | Static             |
      | url         | https://moodle.org |
      | cohorts     | <bycohorts>        |
      | operator    | <operator>         |
    When I am logged in as "admin"
    And "Resources" "theme_boost_union > Smart menu item" should not exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" should not exist in the "Quick links" "theme_boost_union > Menu bar smart menu"
    And "Resources" "theme_boost_union > Smart menu item" should not exist in the "Quick links" "theme_boost_union > User menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" should not exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "student1"
    Then "Resources" "theme_boost_union > Smart menu item" <student1shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "student2"
    Then "Resources" "theme_boost_union > Smart menu item" <student2shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "teacher"
    Then "Resources" "theme_boost_union > Smart menu item" <teachershouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"

    Examples:
      | bycohorts | operator | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | CH1, CH2  | Any      | should                    | should                    | should                   |
      | CH1, CH2  | All      | should not                | should                    | should not               |

  @javascript
  Scenario Outline: Smartmenu: Menu items: Rules - Show smart menu item based on the user's prefered language
    Given the following "language packs" exist:
      | language |
      | de       |
      | fr       |
    And the following "theme_boost_union > smart menu item" exists:
      | menu        | Quick links        |
      | title       | Resources          |
      | itemtype    | Static             |
      | url         | https://moodle.org |
      | languages   | <bylanguage>       |
    When I am logged in as "student1"
    Then "Resources" "theme_boost_union > Smart menu item" <student1shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <student1shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Menu bar smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <student1shouldorshouldnot> exist in the "Quick links" "theme_boost_union > User menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <student1shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "student2"
    And I follow "Language" in the user menu
    And I click on "Français" "link"
    Then "Resources" "theme_boost_union > Smart menu item" <student2shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "teacher"
    And I follow "Language" in the user menu
    And I click on "Deutsch" "link"
    Then "Resources" "theme_boost_union > Smart menu item" <teachershouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"

    Examples:
      | bylanguage | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | en         | should                    | should not                | should not               |
      | en, de     | should                    | should not                | should                   |

  @javascript
  Scenario Outline: Smartmenu: Menu items: Rules - Show smart menu item based on the custom date range
    Given the following "theme_boost_union > smart menu item" exists:
      | menu        | Quick links        |
      | title       | Resources          |
      | itemtype    | Static             |
      | url         | https://moodle.org |
      | start_date  | <start_date>       |
      | end_date    | <end_date>         |
    When I am logged in as "admin"
    Then "Resources" "theme_boost_union > Smart menu item" <menushouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <menushouldorshouldnot> exist in the "Quick links" "theme_boost_union > Menu bar smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <menushouldorshouldnot> exist in the "Quick links" "theme_boost_union > User menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <menushouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "student1"
    Then "Resources" "theme_boost_union > Smart menu item" <menushouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    When I am logged in as "teacher"
    Then "Resources" "theme_boost_union > Smart menu item" <menushouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"

    Examples:
      | start_date           | end_date              | menushouldorshouldnot |
      | ## 1 January 2023 ## | ## 1 January 2050 ##  | should                |
      | ## 1 January 2049 ## | ## 1 January 2050 ##  | should not            |
      | ## 1 January 2023 ## | ## 1 December 2023 ## | should not            |
      | ## 1 January 2023 ## |                       | should                |
      | ## 1 January 2049 ## |                       | should not            |
      |                      | ## 1 December 2050 ## | should                |
      |                      | ## 1 December 2023 ## | should not            |

  @javascript
  Scenario Outline: Smartmenu: Menu items: Rules - Show smart menu item based on multiple conditions
    Given the following "language packs" exist:
      | language |
      | de       |
      | fr       |
    And the following "theme_boost_union > smart menu item" exists:
      | menu        | Quick links        |
      | title       | Resources          |
      | itemtype    | Static             |
      | url         | https://moodle.org |
      | roles       | <byrole>           |
      | cohorts     | <bycohort>         |
      | languages   | <bylanguage>       |
    When I am logged in as "student1"
    Then "Resources" "theme_boost_union > Smart menu item" <student1shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Main menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <student1shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Menu bar smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <student1shouldorshouldnot> exist in the "Quick links" "theme_boost_union > User menu smart menu"
    And "Resources" "theme_boost_union > Smart menu item" <student1shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "student2"
    And I follow "Language" in the user menu
    And I click on "Français" "link"
    Then "Resources" "theme_boost_union > Smart menu item" <student2shouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"
    When I am logged in as "teacher"
    And I follow "Language" in the user menu
    And I click on "Deutsch" "link"
    Then "Resources" "theme_boost_union > Smart menu item" <teachershouldorshouldnot> exist in the "Quick links" "theme_boost_union > Bottom bar smart menu"

    Examples:
      | byrole                           | bycohort | bylanguage | student1shouldorshouldnot | student2shouldorshouldnot | teachershouldorshouldnot |
      | Manager, Student                 | CH1      | en         | should                    | should not                | should not               |
      | Manager, Student, editingteacher | CH1, CH2 | en, de     | should                    | should not                | should                   |

  @javascript
  Scenario: Smartmenu: Menu items: Rules - Deleting a cohort used for a rule removes it from the rule
    Given the following "theme_boost_union > smart menu item" exists:
      | menu        | Quick links        |
      | title       | Resources          |
      | itemtype    | Static             |
      | url         | https://moodle.org |
      | cohorts     | CH1, CH2           |
    And I am on the "Quick links" "theme_boost_union > Smart menu > Items" page logged in as "admin"
    And I should see "Cohort 1" in the "Resources" "table_row"
    And I should see "Cohort 2" in the "Resources" "table_row"
    When I navigate to "Users > Cohorts" in site administration
    And I open the action menu in "Cohort 1" "table_row"
    And I choose "Delete" in the open action menu
    And I click on "Delete" "button" in the "Delete selected" "dialogue"
    And I am on the "Quick links" "theme_boost_union > Smart menu > Items" page
    Then I should not see "Cohort 1" in the "Resources" "table_row"
    And I should see "Cohort 2" in the "Resources" "table_row"

  @javascript
  Scenario: Smartmenu: Menu items: Rules - Deleting a role used for a rule removes it from the rule
    Given the following "roles" exist:
      | shortname | name        |
      | test1     | Test role 1 |
      | test2     | Test role 2 |
    And the following "theme_boost_union > smart menu item" exists:
      | menu        | Quick links        |
      | title       | Resources          |
      | itemtype    | Static             |
      | url         | https://moodle.org |
      | roles       | test1, test2       |
    And I am on the "Quick links" "theme_boost_union > Smart menu > Items" page logged in as "admin"
    And I should see "Test role 1" in the "Resources" "table_row"
    And I should see "Test role 2" in the "Resources" "table_row"
    When I navigate to "Users > Define roles" in site administration
    And I click on "Delete" "link" in the "Test role 1" "table_row"
    And I press "Yes"
    And I am on the "Quick links" "theme_boost_union > Smart menu > Items" page
    Then I should not see "Test role 1" in the "Resources" "table_row"
    And I should see "Test role 2" in the "Resources" "table_row"
