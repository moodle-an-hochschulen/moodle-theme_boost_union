@theme @theme_boost_union @theme_boost_union_functionalitysettings @theme_boost_union_functionalitysettings_administration
Feature: Configuring the theme_boost_union plugin for the "Administration" tab on the "Functionality" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "categories" exist:
      | name           | category | idnumber |
      | Category C     | 0        | C        |
    And the following "courses" exist:
      | fullname  | shortname | category |
      | Course C1 | C1        | C        |

  @javascript
  Scenario Outline: Setting: Show view course icon - Disable and enable the setting
    Given the following config values are set as admin:
      | config                         | value   | plugin            |
      | showviewcourseiconincoursemgnt | <value> | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Courses > Manage courses and categories" in site administration
    And I click on "Category C" "link" in the "#category-listing" "css_element"
    Then I should see "Course C1" in the "#course-listing" "css_element"
    And "#course-listing .course-item-actions a.action-view" "css_element" <shouldexist>
    And I set the field "Search courses" to "Course C1"
    And I click on ".simplesearchform .search-icon" "css_element"
    And I should see "Search results"
    Then I should see "Course C1" in the "#course-listing" "css_element"
    And "#course-listing .course-item-actions a.action-view" "css_element" <shouldexist>

    Examples:
      | value | shouldexist      |
      | yes   | should exist     |
      | no    | should not exist |
