@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_page
Feature: Configuring the theme_boost_union plugin for the "Page" tab on the "Look" page
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

  # Unfortunately, this can't be tested with Behat yet
  # And as this feature file for this tab can't be empty, we just add a dummy step.
  Scenario: Setting: Course content max width - Overwrite the course content max width setting
    When I log in as "admin"

  # Unfortunately, this can't be tested with Behat yet
  # And as this feature file for this tab can't be empty, we just add a dummy step.
  Scenario: Setting: Medium content max width - Overwrite the medium content max width setting
    When I log in as "admin"
