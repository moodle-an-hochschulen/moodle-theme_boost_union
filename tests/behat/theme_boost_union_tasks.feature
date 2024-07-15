@theme @theme_boost_union @theme_boost_union_tasks
Feature: Running tasks in the theme_boost_union plugin
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |

  @javascript
  Scenario: Running the \theme_boost_union\task\purge_cache task
    Given the following config values are set as admin:
      | config         | value                                                                                                               | plugin            |
      | extscsssource  | 1                                                                                                                   | theme_boost_union |
      | extscssurlpost | https://raw.githubusercontent.com/moodle-an-hochschulen/moodle-theme_boost_union/main/tests/fixtures/extscss.scss | theme_boost_union |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I run the scheduled task "\theme_boost_union\task\purge_cache"
    And I reload the page
    # The page must be reloaded a second time as the updated theme revision might not be shipped on the direct subsequent page load.
    And I reload the page
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"
