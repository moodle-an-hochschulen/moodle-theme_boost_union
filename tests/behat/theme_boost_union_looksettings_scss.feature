@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_scss
Feature: Configuring the theme_boost_union plugin for the "SCSS" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |

  @javascript
  Scenario: Setting: Raw initial SCSS - Add custom SCSS to the theme
    When I log in as "admin"
    And I navigate to "Appearance > Themes > Boost Union > Look" in site administration
    And I click on "SCSS" "link" in the "#adminsettings .nav-tabs" "css_element"
    # We add a small CSS snippet to the page which hides the heading in the page header.
    # This is just to make it easy to detect the effect of this custom SCSS code.
    And I set the field "Raw initial SCSS" to multiline:
    """
    #page-header h1 { display: none; }
    """
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario: Setting: Raw SCSS - Add custom SCSS to the theme
    When I log in as "admin"
    And I navigate to "Appearance > Themes > Boost Union > Look" in site administration
    And I click on "SCSS" "link" in the "#adminsettings .nav-tabs" "css_element"
    # We add a small CSS snippet to the page which hides the heading in the page header.
    # This is just to make it easy to detect the effect of this custom SCSS code.
    And I set the field "Raw SCSS" to multiline:
    """
    #page-header h1 { display: none; }
    """
    And I press "Save changes"
    And I am on "Course 1" course homepage
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"
