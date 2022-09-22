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
    And I toggle editing mode
    Then The page will be reloaded
    And The page view will scroll back to x "0" y "250"

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
    And I toggle editing mode
    Then The page will be reloaded
    And The page view will remain at x "0" y "0"

  @javascript  
  Scenario: Setting: Dark navbar - Enable "Back to top button" (countercheck)
    Given the following config values are set as admin:
      | config          | value | plugin            |
      | darknavbar      | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    Then I should see "<nav class="navbar fixed-top bg-dark  navbar-expand" aria-label="Site navigation">"

