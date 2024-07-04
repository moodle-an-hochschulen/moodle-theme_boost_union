@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_generalsettings
Feature: Configuring the theme_boost_union plugin for the "General settings" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |

  @javascript @_file_upload
  Scenario: Setting: Inherit theme preset from Boost Core to Boost Union
    When I log in as "admin"
    And I navigate to "Appearance > Themes" in site administration
    And I click on "#theme-settings-boost" "css_element" in the "#theme-card-boost" "css_element"
    And I click on "General settings" "link" in the "#adminsettings .nav-tabs" "css_element"
    # We upload a preset which hides the heading in the page header.
    # This is just to make it easy to detect the effect of the preset.
    And I upload "theme/boost_union/tests/fixtures/preset.scss" file to "Additional theme preset files" filemanager
    And I click on "Save changes" "button"
    And I navigate to "Appearance > Themes" in site administration
    And I click on "#theme-settings-boost" "css_element" in the "#theme-card-boost" "css_element"
    And I click on "General settings" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "Theme preset" to "preset.scss"
    And I click on "Save changes" "button"
    And I am on "Course 1" course homepage
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"
