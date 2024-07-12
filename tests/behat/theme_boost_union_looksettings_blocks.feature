@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_blocks
Feature: Configuring the theme_boost_union plugin for the "Blocks" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  @javascript
  Scenario Outline: Setting: Tint timeline / upcoming events / recently accessed items / activities activity icons - Enable the settings
    Given the following config values are set as admin:
      | config                           | value    | plugin            |
      | timelinetintenabled              | <tinttl> | theme_boost_union |
      | upcomingeventstintenabled        | <tintue> | theme_boost_union |
      | recentlyaccesseditemstintenabled | <tintrc> | theme_boost_union |
      | activitiestintenabled            | <tintac> | theme_boost_union |
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
      | recentlyaccesseditems | System       | 1         | my-index        | content       |
      | activity_modules      | Course       | C1        | course-view-*   | side-pre      |
    And I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Activity branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I select "Collaboration" from the "Assignment" singleselect
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage
    # Visiting the activity is necessar to fill the recently accessed items block.
    And I am on the "Test assign" "assign activity" page
    And I follow "Dashboard"
    Then DOM element ".block_timeline .theme-boost-union-mod_assign.activityiconcontainer img" <shouldornottl> have computed style "filter" "invert(0.25) sepia(0.54) saturate(62.26) hue-rotate(245deg) brightness(1) contrast(1.02)"
    And DOM element ".block_calendar_upcoming .theme-boost-union-mod_assign.activityiconcontainer img" <shouldornotue> have computed style "filter" "invert(0.25) sepia(0.54) saturate(62.26) hue-rotate(245deg) brightness(1) contrast(1.02)"
    And DOM element ".block_recentlyaccesseditems .theme-boost-union-assign.activityiconcontainer img" <shouldornotrc> have computed style "filter" "invert(0.25) sepia(0.54) saturate(62.26) hue-rotate(245deg) brightness(1) contrast(1.02)"
    And I am on "Course 1" course homepage
    Then DOM element ".block_activity_modules .content .icon[title=\'Assignment\']" <shouldornotac> have computed style "filter" "invert(0.25) sepia(0.54) saturate(62.26) hue-rotate(245deg) brightness(1) contrast(1.02)"

    Examples:
      | tinttl | tintrc | tintue | tintac | shouldornottl | shouldornotrc | shouldornotue | shouldornotac |
      | no     | no     | no     | no     | should not    | should not    | should not    | should not    |
      | yes    | no     | no     | no     | should        | should not    | should not    | should not    |
      | no     | yes    | no     | no     | should not    | should        | should not    | should not    |
      | no     | no     | yes    | no     | should not    | should not    | should        | should not    |
      | no     | no     | no     | yes    | should not    | should not    | should not    | should        |
