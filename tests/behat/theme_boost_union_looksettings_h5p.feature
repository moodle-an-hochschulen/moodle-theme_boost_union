@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_h5p
Feature: Configuring the theme_boost_union plugin for the "H5P" tab on the "Look" page
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
  Scenario: Setting: Raw CSS for H5P - Add custom SCSS to a mod_h5p content type
    When I log in as "admin"

  # Unfortunately, this can't be tested with Behat yet
  # And as this feature file for this tab can't be empty, we just add a dummy step.
  Scenario: Setting: Raw CSS for H5P - Add custom SCSS to a mod_hvp content type
    When I log in as "admin"

  # Unfortunately, this can't be tested with Behat yet
  # And as this feature file for this tab can't be empty, we just add a dummy step.
  Scenario: Setting: H5P content bank max width - Overwrite the H5P content bank max width setting
    When I log in as "admin"
