@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_blocks
Feature: Configuring the theme_boost_union plugin for the "Blocks" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  @javascript
  Scenario Outline: Setting: Tint timeline / upcoming events icons - Enable the settings
    Given the following config values are set as admin:
      | config                           | value    | plugin            |
      | timelinetintenabled              | <tinttl> | theme_boost_union |
      | upcomingeventstintenabled        | <tintue> | theme_boost_union |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "activities" exist:
      | activity | course | idnumber | name        | intro                   | timeopen        | duedate      |
      | assign   | C1     | assign1  | Test assign | Test assign description | ##1 month ago## | ##tomorrow## |
    And the following "course enrolments" exist:
      | user  | course | role    |
      | admin | C1     | student |
    And the following "blocks" exist:
      | blockname             | contextlevel | reference | pagetypepattern | defaultregion |
      | timeline              | System       | 1         | my-index        | content       |
      | calendar_upcoming     | System       | 1         | my-index        | content       |
    And I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Activity branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I select "Collaboration" from the "Assignment" singleselect
    And I press "Save changes"
    And Behat debugging is enabled
    And I follow "Dashboard"
    Then DOM element ".block_timeline .theme-boost-union-mod_assign.activityiconcontainer img" <shouldornottl>
    And DOM element ".block_calendar_upcoming .theme-boost-union-mod_assign.activityiconcontainer img" <shouldornotue>

    Examples:
      | tinttl | tintue | shouldornottl                                                | shouldornotue                                                |
      | no     | no     | should have computed style "filter" "none"                   | should have computed style "filter" "none"                   |
      | yes    | no     | should have a CSS filter close enough to hex color "#5b40ff" | should have computed style "filter" "none"                   |
      | no     | yes    | should have computed style "filter" "none"                   | should have a CSS filter close enough to hex color "#5b40ff" |
      | yes    | yes    | should have a CSS filter close enough to hex color "#5b40ff" | should have a CSS filter close enough to hex color "#5b40ff" |
