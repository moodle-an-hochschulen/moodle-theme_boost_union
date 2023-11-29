@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_dashboard_mycourses
Feature: Configuring the theme_boost_union plugin for the "Dashboard/My courses" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | student1 |
    And the following "courses" exist:
      | fullname | shortname | enablecompletion | showcompletionconditions |
      | Course 1 | C1        | 1                | 1                        |
      | Course 2 | C2        | 1                | 1                        |
    And the following "activities" exist:
      | activity | name              | intro                       | course | idnumber | section | completion |
      | assign   | Activity sample 1 | Test assignment description | C1     | sample1  | 1       | 1          |
      | assign   | Activity sample 2 | Test assignment description | C2     | sample2  | 1       | 1          |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | student1 | C1     | student        |
      | student1 | C2     | student        |

  @javascript
  Scenario: Setting: Check if the settings are present in the correct place
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Dashboard / My courses" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then I should see "Show course images"
    And I should see "Card" in the "#admin-courseoverviewshowcourseimages" "css_element"
    And I should see "List" in the "#admin-courseoverviewshowcourseimages" "css_element"
    And I should see "Summary" in the "#admin-courseoverviewshowcourseimages" "css_element"
    And I should see "Show course completion progress"

  @javascript
  Scenario Outline: Setting: Check if courseoverviewshowcourseprogress setting is working properly
    Given the following config values are set as admin:
      | config                           | value          | plugin            |
      | courseoverviewshowcourseprogress | <settingvalue> | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    Then I should see "All caches were purged"
    Then I log in as "student1"
    And I follow "Dashboard"
    And I turn editing mode on
    And I click on "Add a block" "link" in the "#region-main" "css_element"
    And I should see "Course overview" in the ".modal-body" "css_element"
    And I click on "a[data-blockname=myoverview]" "css_element" in the ".modal-body" "css_element"
    Then I should see "Course overview" in the "#region-main" "css_element"
    And I <shouldornot> see "0% complete" in the ".block_myoverview.block div[data-region=courses-view]" "css_element"
    Examples:
      | settingvalue | shouldornot |
      | yes          | should      |
      | no           | should not  |

  @javascript
  Scenario Outline: Setting: Check if courseoverviewshowcourseimages setting is working properly
    Given the following config values are set as admin:
      | config                         | value          | plugin            |
      | courseoverviewshowcourseimages | <settingvalue> | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    Then I should see "All caches were purged"
    Then I log in as "student1"
    And I follow "Dashboard"
    And I turn editing mode on
    And I click on "Add a block" "link" in the "#region-main" "css_element"
    And I should see "Course overview" in the ".modal-body" "css_element"
    And I click on "a[data-blockname=myoverview]" "css_element" in the ".modal-body" "css_element"
    Then I should see "Course overview" in the "#region-main" "css_element"
    And I click on "#displaydropdown" "css_element" in the "section.block_myoverview" "css_element"
    And I click on "[data-value=card]" "css_element" in the ".dropdown-menu.show" "css_element"
    And ".block_myoverview.block div[data-region=courses-view] .dashboard-card-img" "css_element" <shouldornotcard> be visible
    And I click on "#displaydropdown" "css_element" in the "section.block_myoverview" "css_element"
    And I click on "[data-value=list]" "css_element" in the ".dropdown-menu.show" "css_element"
    And ".block_myoverview.block div[data-region=courses-view] .course-listitem:not(.course-summaryitem) > .row > .col-md-2" "css_element" <shouldornotlist> be visible
    And I click on "#displaydropdown" "css_element" in the "section.block_myoverview" "css_element"
    And I click on "[data-value=summary]" "css_element" in the ".dropdown-menu.show" "css_element"
    And ".block_myoverview.block div[data-region=courses-view] .course-summaryitem > .row > .col-md-2" "css_element" <shouldornotsummary> be visible

    Examples:
      | settingvalue      | shouldornotcard | shouldornotlist | shouldornotsummary |
      | card,list,summary | should          | should          | should             |
      | card,list         | should          | should          | should not         |
      | card,summary      | should          | should not      | should             |
      | list,summary      | should not      | should          | should             |
      | card              | should          | should not      | should not         |
      | list              | should not      | should          | should not         |
      | summary           | should not      | should not      | should             |
      |                   | should not      | should not      | should not         |
