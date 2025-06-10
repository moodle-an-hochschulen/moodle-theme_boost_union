@theme @theme_boost_union @theme_boost_union_feelsettings @theme_boost_union_feelsettings_ai
Feature: Configuring the theme_boost_union plugin for the "AI" tab on the "Feel" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  # This file just replicates all test scenarios from ai/placement/courseassist/tests/behat/course_assist_features.feature,
  # but runs each of them once for each possible value of the theme_boost_union/aiplacementcourseassistlocation setting,
  # once with Boost as the active theme and once with Boost Union (this ensures that the default functionality does not break).
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
    And the following "core_ai > ai providers" exist:
      | provider          | name            | enabled | apikey | orgid |
      | aiprovider_openai | OpenAI API test | 1       | 123    | abc   |
    And the following config values are set as admin:
      | enabled | 1 | aiplacement_courseassist |

  Scenario Outline: AI features dropdown is visible when more than one feature is enabled
    Given the following config values are set as admin:
      | config                          | value     | plugin            |
      | theme                           | <theme>   |                   |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union |
    # Purge cached hook overrides.
    And all caches are purged
    When I am on the "PageName1" "page activity" page logged in as teacher1
    Then "AI features" "button" <defaultshouldorshouldnot> exist in the "#page-content [role='main']" "css_element"
    And "AI features" "button" <headeractionshouldorshouldnot> exist in the "#page-header .header-actions-container" "css_element"
    # Check nested buttons exist too.
    And "Summarise" "button" should exist
    And "Explain" "button" should exist

    Examples:
      | theme       | setting      | defaultshouldorshouldnot | headeractionshouldorshouldnot |
      | boost       | default      | should                   | should not                    |
      | boost       | headeraction | should                   | should not                    |
      | boost_union | default      | should                   | should not                    |
      | boost_union | headeraction | should not               | should                        |

  Scenario Outline: AI features dropdown is not visible when only one feature is enabled
    Given the following config values are set as admin:
      | config                          | value     | plugin            |
      | theme                           | <theme>   |                   |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union |
    # Purge cached hook overrides.
    And all caches are purged
    And I set the following action configuration for ai provider with name "OpenAI API test":
      | action         | enabled |
      | explain_text   | 0       |
      | summarise_text | 1       |
    When I am on the "PageName1" "page activity" page logged in as teacher1
    Then "AI features" "button" should not exist
    And "Explain" "button" should not exist
    # Only the summarise button should exist.
    And "Summarise" "button" <defaultshouldorshouldnot> exist in the "#page-content [role='main']" "css_element"
    And "Summarise" "button" <headeractionshouldorshouldnot> exist in the "#page-header .header-actions-container" "css_element"

    Examples:
      | theme       | setting      | defaultshouldorshouldnot | headeractionshouldorshouldnot |
      | boost       | default      | should                   | should not                    |
      | boost       | headeraction | should                   | should not                    |
      | boost_union | default      | should                   | should not                    |
      | boost_union | headeraction | should not               | should                        |

  Scenario Outline: AI features are not available if placement is not enabled
    Given the following config values are set as admin:
      | config                          | value     | plugin                   |
      | theme                           | <theme>   |                          |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union        |
      | enabled                         |           | aiplacement_courseassist |
    # Purge cached hook overrides.
    And all caches are purged
    When I am on the "PageName1" "page activity" page logged in as teacher1
    Then "AI features" "button" should not exist

    Examples:
      | theme       | setting      |
      | boost       | default      |
      | boost       | headeraction |
      | boost_union | default      |
      | boost_union | headeraction |

  Scenario Outline: AI features are not available if provider action is not enabled
    Given the following config values are set as admin:
      | config                          | value     | plugin            |
      | theme                           | <theme>   |                   |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union |
    # Purge cached hook overrides.
    And all caches are purged
    And I set the following action configuration for ai provider with name "OpenAI API test":
      | action         | enabled |
      | explain_text   | 0       |
      | summarise_text | 0       |
    When I am on the "PageName1" "page activity" page logged in as teacher1
    Then "AI features" "button" should not exist

    Examples:
      | theme       | setting      |
      | boost       | default      |
      | boost       | headeraction |
      | boost_union | default      |
      | boost_union | headeraction |

  Scenario Outline: AI features are not available if placement action is not enabled
    Given the following config values are set as admin:
      | config                          | value     | plugin            |
      | theme                           | <theme>   |                   |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union |
    # Purge cached hook overrides.
    And all caches are purged
    And I set the following action configuration for ai provider with name "OpenAI API test":
      | action         | enabled |
      | explain_text   | 0       |
      | summarise_text | 0       |
    When I am on the "PageName1" "page activity" page logged in as teacher1
    Then "AI features" "button" should not exist

    Examples:
      | theme       | setting      |
      | boost       | default      |
      | boost       | headeraction |
      | boost_union | default      |
      | boost_union | headeraction |

  Scenario Outline: AI features are not available if the user does not have permission
    Given the following config values are set as admin:
      | config                          | value     | plugin            |
      | theme                           | <theme>   |                   |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union |
    # Purge cached hook overrides.
    And all caches are purged
    When I am on the "PageName1" "page activity" page logged in as teacher2
    Then "AI features" "button" should not exist

    Examples:
      | theme       | setting      |
      | boost       | default      |
      | boost       | headeraction |
      | boost_union | default      |
      | boost_union | headeraction |

  @javascript
  Scenario Outline: I can view the AI drawer contents using the AI features dropdown
    Given the following config values are set as admin:
      | config                          | value     | plugin            |
      | theme                           | <theme>   |                   |
      | aiplacementcourseassistlocation | <setting> | theme_boost_union |
    # Purge cached hook overrides.
    And all caches are purged
    And I am on the "PageName1" "page activity" page logged in as teacher1
    And "AI features" "button" <defaultshouldorshouldnot> exist in the "#page-content [role='main']" "css_element"
    And "AI features" "button" <headeractionshouldorshouldnot> exist in the "#page-header .header-actions-container" "css_element"
    When I click on "AI features" "button"
    And I click on "Summarise" "button"
    Then I should see "Welcome to the new AI feature!" in the ".ai-drawer" "css_element"

    Examples:
      | theme       | setting      | defaultshouldorshouldnot | headeractionshouldorshouldnot |
      | boost       | default      | should                   | should not                    |
      | boost       | headeraction | should                   | should not                    |
      | boost_union | default      | should                   | should not                    |
      | boost_union | headeraction | should not               | should                        |
