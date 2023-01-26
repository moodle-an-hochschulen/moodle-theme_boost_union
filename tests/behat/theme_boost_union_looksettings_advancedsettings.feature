@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_advancedsettings
Feature: Configuring the theme_boost_union plugin for the "Branding" tab on the "Look" page
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
    And the following "activity" exists:
      | activity | h5pactivity          |
      | name     | H5P package          |
      | intro    | Test H5P description |
      | course   | C1                   |
      | idnumber | h5ppackage           |

  @javascript @_file_upload
  Scenario: Setting: Upload raw css to be shown in h5p and possibly hvp plugins.
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Advanced settings" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "Raw CSS for H5P" to ".h5p-joubelui-button {background: green !important;}"
    And I press "Save changes"
    And I click on "Advanced settings" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then the field "Raw CSS for H5P" matches value ".h5p-joubelui-button {background: green !important;}"

    # And I log in as "teacher1"
    # And I am on "Course 1" course homepage
    # And I click on "H5P package" "link"
    # Then "//html[@class='h5p-iframe]//head//link[contains(@rel, 'theme/boost_union/style/custom.css')]" "xpath_element" should exist
    # as we do not have h5p content this does not exist.
