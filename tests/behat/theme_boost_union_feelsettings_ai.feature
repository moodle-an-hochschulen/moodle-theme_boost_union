@theme @theme_boost_union @theme_boost_union_feelsettings @theme_boost_union_feelsettings_ai
Feature: Configuring the theme_boost_union plugin for the "AI" tab on the "Feel" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  # This file just replicates all test scenarios from ai/placement/courseassist/tests/behat/course_assist_summarise.feature,
  # but runs each of them once for each possible value of the theme_boost_union/aiplacementcourseassistlocation setting.
  # Each scenario also checks whether the button appears at the right location.

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email          |
      | teacher1 | Teacher   | 1        | t1@example.com |
      | teacher2 | Teacher   | 2        | t2@example.com |
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1        | topics |
    And the following "roles" exist:
      | name                   | shortname | description      | archetype      |
      | Custom editing teacher | custom1   | My custom role 1 | editingteacher |
      | Custom teacher         | custom2   | My custom role 2 | editingteacher |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | teacher1 | C1     | custom1 |
      | teacher2 | C1     | custom2 |
    And the following "activities" exist:
      | activity | name      | intro     | introformat | course | content     | contentformat | idnumber |
      | page     | PageName1 | PageDesc1 | 1           | C1     | PageContent | 1             | 1        |
    And the following "permission overrides" exist:
      | capability                              | permission | role    | contextlevel | reference |
      | aiplacement/courseassist:summarise_text | Prohibit   | custom2 | Course       | C1        |
    And I log in as "admin"
    And I enable "openai" "aiprovider" plugin
    And the following config values are set as admin:
      | apikey | 123 | aiprovider_openai |
    And I enable "courseassist" "aiplacement" plugin

  @javascript
  Scenario Outline: Summarise text using AI is not available if placement is not enabled
    Given the following config values are set as admin:
      | config                          | value     | plugin            |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union |
    And I disable "courseassist" "aiplacement" plugin
    # Purge cached hook overrides.
    And all caches are purged
    When I am on the "PageName1" "page activity" page logged in as teacher1
    Then "Summarise" "button" should not exist
    And I enable "courseassist" "aiplacement" plugin
    And I am on the "PageName1" "page activity" page logged in as teacher1
    And "Summarise" "button" <defaultshouldorshouldnot> exist in the "#page-content [role='main']" "css_element"
    And "Summarise" "button" <headeractionshouldorshouldnot> exist in the "#page-header .header-actions-container" "css_element"

    Examples:
      | setting      | defaultshouldorshouldnot | headeractionshouldorshouldnot |
      | default      | should                   | should not                    |
      | headeraction | should not               | should                        |

  @javascript
  Scenario Outline: Summarise text using AI is not available if provider is not enabled
    Given the following config values are set as admin:
      | config                          | value     | plugin            |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union |
    And I disable "openai" "aiprovider" plugin
    # Purge cached hook overrides.
    And all caches are purged
    When I am on the "PageName1" "page activity" page logged in as teacher1
    Then "Summarise" "button" should not exist
    And I enable "openai" "aiprovider" plugin
    And I am on the "PageName1" "page activity" page logged in as teacher1
    And "Summarise" "button" <defaultshouldorshouldnot> exist in the "#page-content [role='main']" "css_element"
    And "Summarise" "button" <headeractionshouldorshouldnot> exist in the "#page-header .header-actions-container" "css_element"

    Examples:
      | setting      | defaultshouldorshouldnot | headeractionshouldorshouldnot |
      | default      | should                   | should not                    |
      | headeraction | should not               | should                        |

  @javascript
  Scenario Outline: Summarise text using AI is not available if provider action is not enabled
    Given the following config values are set as admin:
      | config                          | value     | plugin            |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union |
      | summarise_text                  |           | aiprovider_openai |
    # Purge cached hook overrides.
    And all caches are purged
    When I am on the "PageName1" "page activity" page logged in as teacher1
    Then "Summarise" "button" should not exist
    And the following config values are set as admin:
      | summarise_text | 1 | aiprovider_openai |
    And I am on the "PageName1" "page activity" page logged in as teacher1
    And "Summarise" "button" <defaultshouldorshouldnot> exist in the "#page-content [role='main']" "css_element"
    And "Summarise" "button" <headeractionshouldorshouldnot> exist in the "#page-header .header-actions-container" "css_element"

    Examples:
      | setting      | defaultshouldorshouldnot | headeractionshouldorshouldnot |
      | default      | should                   | should not                    |
      | headeraction | should not               | should                        |

  @javascript
  Scenario Outline: Summarise text using AI is not available if placement action is not enabled
    Given the following config values are set as admin:
      | config                          | value     | plugin                   |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union        |
      | summarise_text                  |           | aiplacement_courseassist |
    # Purge cached hook overrides.
    And all caches are purged
    When I am on the "PageName1" "page activity" page logged in as teacher1
    Then "Summarise" "button" should not exist
    And the following config values are set as admin:
      | summarise_text | 1 | aiplacement_courseassist |
    And I am on the "PageName1" "page activity" page logged in as teacher1
    And "Summarise" "button" <defaultshouldorshouldnot> exist in the "#page-content [role='main']" "css_element"
    And "Summarise" "button" <headeractionshouldorshouldnot> exist in the "#page-header .header-actions-container" "css_element"

    Examples:
      | setting      | defaultshouldorshouldnot | headeractionshouldorshouldnot |
      | default      | should                   | should not                    |
      | headeraction | should not               | should                        |

  @javascript
  Scenario Outline: Summarise text using AI is not available if provider action is not enabled and placement action is enabled
    Given the following config values are set as admin:
      | config                          | value     | plugin                   |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union        |
      | summarise_text                  |           | aiplacement_courseassist |
      | summarise_text                  |           | aiprovider_openai        |
    # Purge cached hook overrides.
    And all caches are purged
    When I am on the "PageName1" "page activity" page logged in as teacher1
    Then "Summarise" "button" should not exist
    And the following config values are set as admin:
      | summarise_text | 1 | aiplacement_courseassist |
    And I am on the "PageName1" "page activity" page logged in as teacher1
    And "Summarise" "button" should not exist
    And the following config values are set as admin:
      | summarise_text | 1 | aiprovider_openai |
    And I am on the "PageName1" "page activity" page logged in as teacher1
    And "Summarise" "button" <defaultshouldorshouldnot> exist in the "#page-content [role='main']" "css_element"
    And "Summarise" "button" <headeractionshouldorshouldnot> exist in the "#page-header .header-actions-container" "css_element"

    Examples:
      | setting      | defaultshouldorshouldnot | headeractionshouldorshouldnot |
      | default      | should                   | should not                    |
      | headeraction | should not               | should                        |

  @javascript
  Scenario Outline: Summarise text using AI is not available if the user does not have permission
    Given the following config values are set as admin:
      | config                          | value     | plugin            |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union |
    # Purge cached hook overrides.
    And all caches are purged
    When I am on the "PageName1" "page activity" page logged in as teacher2
    Then "Summarise" "button" should not exist
    When I am on the "PageName1" "page activity" page logged in as teacher1
    And "Summarise" "button" <defaultshouldorshouldnot> exist in the "#page-content [role='main']" "css_element"
    And "Summarise" "button" <headeractionshouldorshouldnot> exist in the "#page-header .header-actions-container" "css_element"
    And I click on "Summarise" "button"
    And I should see "Welcome to the new AI feature!" in the ".ai-drawer" "css_element"

    Examples:
      | setting      | defaultshouldorshouldnot | headeractionshouldorshouldnot |
      | default      | should                   | should not                    |
      | headeraction | should not               | should                        |
