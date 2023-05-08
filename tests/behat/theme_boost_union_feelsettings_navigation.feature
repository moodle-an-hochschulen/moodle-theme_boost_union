@theme @theme_boost_union @theme_boost_union_feelsettings @theme_boost_union_feelsettings_navigation
Feature: Configuring the theme_boost_union plugin for the "Navigation" tab on the "Feel" page
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

  Scenario Outline: Setting: Hide a single node in primary navigation.
    Given the following config values are set as admin:
      | config                     | value      | plugin            |
      | hidenodesprimarynavigation | <nodename> | theme_boost_union |
    When I log in as "admin"
    And I am on homepage
    Then I should not see "<nodetitle>" in the ".primary-navigation" "css_element"

    Examples:
      | nodename      | nodetitle           |
      | home          | Home                |
      | myhome        | Dashboard           |
      | courses       | My courses          |
      | siteadminnode | Site administration |

  Scenario Outline: Setting: Hide multiple nodes in primary navigation.
    Given the following config values are set as admin:
      | config                     | value       | plugin            |
      | hidenodesprimarynavigation | <nodenames> | theme_boost_union |
    When I log in as "admin"
    And I am on homepage
    Then I should not see "<firstnodetitle>" in the ".primary-navigation" "css_element"
    And I should not see "<secondnodetitle>" in the ".primary-navigation" "css_element"

    Examples:
      | nodenames             | firstnodetitle | secondnodetitle     |
      | home,myhome           | Home           | Dashboard           |
      | courses,siteadminnode | My courses     | Site administration |

  Scenario Outline: Setting: Course category breadcrumbs
    Given the following "categories" exist:
      | name           | category | idnumber | category |
      | Category E     | 0        | CE       | 0        |
      | Category ED    | 1        | CED      | CE       |
      | Category EDC   | 2        | CEDC     | CED      |
      | Category EDCB  | 3        | CEDCB    | CEDC     |
      | Category EDCBA | 4        | CEDCBA   | CEDCB    |
    And the following "courses" exist:
      | fullname  | shortname | category |
      | Course C1 | CC1       | CE       |
      | Course C2 | CC2       | CED      |
      | Course C3 | CC3       | CEDC     |
      | Course C4 | CC4       | CEDCB    |
      | Course C5 | CC5       | CEDCBA   |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | CC2    | editingteacher |
      | teacher1 | CC3    | editingteacher |
      | teacher1 | CC4    | editingteacher |
      | teacher1 | CC5    | editingteacher |
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
    And I am on "Course C5" course homepage
    And "Category E" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category ED" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category EDC" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category EDCB" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category EDCBA" "link" <shouldornot> exist in the ".breadcrumb" "css_element"

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |
      # We do not check the 'dontchange' setting as this is Boost core behaviour which Boost Union does not control.

  @javascript
  Scenario: Setting: back to top button - Enable "Back to top button"
    Given the following config values are set as admin:
      | config          | value | plugin            |
      | backtotopbutton | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    And I am on "Course 1" course homepage
    Then "#back-to-top" "css_element" should exist
    And "#page-footer" "css_element" should appear before "#back-to-top" "css_element"
    And "#back-to-top" "css_element" should not be visible
    And I scroll page to x "0" y "250"
    And "#back-to-top" "css_element" should be visible
    And I click on "#back-to-top" "css_element"
    # Then I wait 1 second as the scroll up process is animated
    And I wait "1" seconds
    And "#back-to-top" "css_element" should not be visible

  @javascript
  Scenario: Setting: back to top button - Disable "Back to top button" (countercheck)
    Given the following config values are set as admin:
      | config          | value | plugin            |
      | backtotopbutton | no    | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    And I am on "Course 1" course homepage
    Then "#back-to-top" "css_element" should not exist

  @javascript
  Scenario: Setting: Scrollspy - Enable "Scrollspy"
    Given the following config values are set as admin:
      | config          | value | plugin             |
      | scrollspy       | yes   | theme_boost_union  |
    When I log in as "admin"
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    And I am on "Course 1" course homepage
    And I scroll page to x "0" y "250"
    And I turn editing mode on
    # The rest of this scenario isn't tested yet as the necessary steps still have to be implemented:
    # Then The page will be reloaded
    # And The page view will scroll back to x "0" y "250"
    # And I turn editing mode off
    # Then The page will be reloaded
    # And The page view will scroll back to x "0" y "250"

  @javascript
  Scenario: Setting: Scrollspy - Disable "Scrollspy" (countercheck)
    Given the following config values are set as admin:
      | config          | value | plugin             |
      | scrollspy       | no    | theme_boost_union  |
    When I log in as "admin"
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    And I am on "Course 1" course homepage
    And I scroll page to x "0" y "250"
    And I turn editing mode on
    # The rest of this scenario isn't tested yet as the necessary steps still have to be implemented:
    # Then The page will be reloaded
    # And The page view will remain at x "0" y "0"
    # And I turn editing mode off
    # Then The page will be reloaded
    # And The page view will scroll back to x "0" y "250"
    # And The page view will remain at x "0" y "0"

  @javascript
  Scenario: Setting: Activity navigation - Enable "Activity navigation"
    Given the following config values are set as admin:
      | config             | value | plugin            |
      | activitynavigation | yes   | theme_boost_union |
    And the following "activities" exist:
      | activity | name    | course | idnumber |
      | forum    | Forum 1 | C1     | forum1   |
      | forum    | Forum 2 | C1     | forum2   |
      | forum    | Forum 3 | C1     | forum3   |
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    When I follow "Forum 2"
    Then I should see "Forum 1" in the "#prev-activity-link" "css_element"
    And I should see "Forum 3" in the "#next-activity-link" "css_element"

  @javascript
  Scenario: Setting: Activity navigation - Disable "Activity navigation" (countercheck)
    Given the following config values are set as admin:
      | config             | value | plugin            |
      | activitynavigation | no    | theme_boost_union |
    And the following "activities" exist:
      | activity | name    | course | idnumber |
      | forum    | Forum 1 | C1     | forum1   |
      | forum    | Forum 2 | C1     | forum2   |
      | forum    | Forum 3 | C1     | forum3   |
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    When I follow "Forum 2"
    Then "#prev-activity-link" "css_element" should not exist
    And "#next-activity-link" "css_element" should not exist
