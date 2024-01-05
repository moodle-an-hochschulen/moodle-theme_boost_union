@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_page
Feature: Configuring the theme_boost_union plugin for the "Page" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |

  @javascript
  Scenario: Setting: Course content max width - Overwrite the course content max width setting
    Given the following config values are set as admin:
      | config                | value | plugin            |
      | coursecontentmaxwidth | 600px | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element ".main-inner" should have computed style "max-width" "600px"

  @javascript
  Scenario: Setting: Medium content max width - Overwrite the medium content max width setting
    Given the following config values are set as admin:
      | config                | value | plugin            |
      | mediumcontentmaxwidth | 600px | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    And the following "activities" exist:
      | activity | name               | course |
      | data     | Test database name | C1     |
    When I log in as "admin"
    And I am on the "Test database name" "data activity" page
    Then DOM element ".main-inner" should have computed style "max-width" "600px"

  @javascript
  Scenario: Setting: Course index drawer width - Overwrite the course index drawer width setting
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | courseindexdrawerwidth | 400px | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    Then DOM element "#theme_boost-drawers-courseindex" should have computed style "width" "400px"

  @javascript
  Scenario: Setting: Block drawer width - Overwrite the block drawer width setting
    Given the following config values are set as admin:
      | config           | value | plugin            |
      | blockdrawerwidth | 400px | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    Then DOM element "#theme_boost-drawers-blocks" should have computed style "width" "400px"
