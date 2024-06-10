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
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "H5P" "link" in the "#adminsettings .nav-tabs" "css_element"
    # We add a small CSS snippet to the page which makes the H5P content red.
    # This is just to make it easy to detect the effect of this custom CSS code.
    And I set the field "Raw CSS for H5P" to multiline:
    """
    .h5p-accordion {
        color: #FF0000 !important;
    }
    """
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage with editing mode on
    Given the following "activity" exists:
      | activity        | h5pactivity                   |
      | course          | C1                            |
      | name            | H5P package                   |
      | packagefilepath | h5p/tests/fixtures/ipsums.h5p |
    When I am on the "H5P package" "h5pactivity activity" page
    And I wait until the page is ready
    And I switch to "h5p-player" class iframe
    And I switch to "h5p-iframe" class iframe
    Then DOM element "#h5p-panel-content-0-0" should have computed style "color" "rgb(255, 0, 0)"

  # Unfortunately, this can't be tested with Behat yet, cause the mod_hvp plugin only works when
  # php warnings don't trigger the shutdown handler. See https://github.com/h5p/moodle-mod_hvp/issues/487.
  # Scenario: Setting: Raw CSS for H5P - Add custom SCSS to a mod_hvp content type

  @javascript
  Scenario: Setting: H5P content bank max width - Overwrite the H5P content bank max width setting
    Given the following config values are set as admin:
      | config             | value | plugin            |
      | h5pcontentmaxwidth | 600px | theme_boost_union |
    And the following "contentbank content" exist:
      | contextlevel | reference | contenttype     | user     | contentname | filepath                       |
      | Course       | C1        | contenttype_h5p | teacher1 | ipsums.h5p  | /h5p/tests/fixtures/ipsums.h5p |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I navigate to "Content bank" in current page administration
    And I click on "ipsums.h5p" "link" in the ".content-bank" "css_element"
    Then DOM element ".core_contentbank_viewcontent" should have computed style "max-width" "600px"
