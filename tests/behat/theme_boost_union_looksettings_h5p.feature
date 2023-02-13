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

  @javascript
  Scenario: Setting: Raw CSS for H5P - Add custom SCSS to a mod_h5p content type
    When I log in as "admin"
    And I navigate to "Appearance > Themes > Boost Union > Look" in site administration
    And I click on "H5P" "link" in the "#adminsettings .nav-tabs" "css_element"
    # Ajust the border radius in a h5p accordeon to fit the look and feel of modern moodle.
    And I set the field "Raw CSS for H5P" to multiline:
    """
    .h5p-accordion {
        border-radius: .5rem !important;
    }
    """
    And I press "Save changes"
    And I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    Given the following "activity" exists:
      | activity        | h5pactivity                   |
      | course          | C1                            |
      | name            | H5P package           |
      | packagefilepath | h5p/tests/fixtures/ipsums.h5p |
    When I am on the "H5P package" "h5pactivity activity" page
    And I wait until the page is ready
    And I switch to "h5p-player" class iframe
    And I switch to "h5p-iframe" class iframe
    Then the element ".h5p-accordion" should have a "border-top-left-radius" of ".5rem"

  # Unfortunately, this can't be tested with Behat yet, cause the mod_hvp plugin only works when
  # php warnings don't trigger the shutdown handler. See https://github.com/h5p/moodle-mod_hvp/issues/487.
  # And as this feature file for this tab can't be empty, we just add a dummy step.
  @javascript
  Scenario: Setting: Raw CSS for H5P - Add custom SCSS to a mod_hvp content type
    When I log in as "admin"

  # Unfortunately, this can't be tested with Behat yet
  # And as this feature file for this tab can't be empty, we just add a dummy step.
  Scenario: Setting: H5P content bank max width - Overwrite the H5P content bank max width setting
    When I log in as "admin"
