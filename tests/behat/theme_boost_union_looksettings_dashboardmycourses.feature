@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_dashboardmycourses
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
      | activity | name              | course | completion |
      | assign   | Activity sample 1 | C1     | 1          |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | student1 | C1     | student |
      | student1 | C2     | student |

  @javascript
  Scenario Outline: Setting: Show course images - Display the course completion progress in the myoverview block or not
    Given the following config values are set as admin:
      | config                           | value          | plugin            |
      | courseoverviewshowcourseprogress | <settingvalue> | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "student1"
    And I follow "My courses"
    Then I <shouldornot> see "0% complete" in the ".block_myoverview.block div[data-region=courses-view]" "css_element"
    Examples:
      | settingvalue | shouldornot |
      | yes          | should      |
      | no           | should not  |

  @javascript
  Scenario Outline: Setting: Show course images - Display the course image in the myoverview block or not
    Given the following config values are set as admin:
      | config                         | value          | plugin            |
      | courseoverviewshowcourseimages | <settingvalue> | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "student1"
    And I follow "My courses"
    Then I should see "Course overview" in the "#region-main" "css_element"
    And I click on "#displaydropdown" "css_element" in the "section.block_myoverview" "css_element"
    And I click on "[data-value=card]" "css_element" in the ".dropdown-menu.show" "css_element"
    And ".block_myoverview.block div[data-region=courses-view] .card-img-top" "css_element" <shouldornotcard> be visible
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

  @javascript
  Scenario: Setting: Course overview image source - Display the course image with a (non-effective) fallback to a geometric pattern (default Moodle behavior)
    Given the following config values are set as admin:
      | config                    | value             | plugin            |
      | courseoverviewimagesource | coursepluspattern | theme_boost_union |
    And the following "users" exist:
      | username |
      | student2 |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | student2 | C1     | student |
    And the following "theme_boost_union > course overview files" exist:
      | course | filepath                                       |
      | C1     | theme/boost_union/tests/fixtures/login_bg1.png |
    And the theme cache is purged and the theme is reloaded
    When I log in as "student2"
    And I follow "My courses"
    Then "//div[contains(@class, 'course-card')]//div[contains(@style, 'course/overviewfiles/login_bg1.png')]" "xpath_element" should exist
    And "//div[contains(@class, 'course-card')]//div[contains(@style, 'data:image/svg+xml')]" "xpath_element" should not exist

  @javascript
  Scenario: Setting: Course overview image source - Display the course image with a (non-effective) fallback to the uploaded fallback image
    Given the following config values are set as admin:
      | config                    | value              | plugin            |
      | courseoverviewimagesource | courseplusfallback | theme_boost_union |
    And the following "users" exist:
      | username |
      | student2 |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | student2 | C1     | student |
    And the following "theme_boost_union > setting files" exist:
      | filearea                    | filepath                                       |
      | courseoverviewimagefallback | theme/boost_union/tests/fixtures/login_bg2.png |
    And the following "theme_boost_union > course overview files" exist:
      | course | filepath                                       |
      | C1     | theme/boost_union/tests/fixtures/login_bg1.png |
    And the theme cache is purged and the theme is reloaded
    When I log in as "student2"
    And I follow "My courses"
    Then "//div[contains(@class, 'course-card')]//div[contains(@style, 'course/overviewfiles/login_bg1.png')]" "xpath_element" should exist
    And "//div[contains(@class, 'course-card')]//div[contains(@style, 'theme_boost_union/courseoverviewimagefallback/0/login_bg2.png')]" "xpath_element" should not exist
    And "//div[contains(@class, 'course-card')]//div[contains(@style, 'data:image/svg+xml')]" "xpath_element" should not exist

  @javascript
  Scenario: Setting: Course overview image source - Display the fallback to a geometric pattern if no course image is uploaded
    Given the following config values are set as admin:
      | config                    | value             | plugin            |
      | courseoverviewimagesource | coursepluspattern | theme_boost_union |
    And the following "users" exist:
      | username |
      | student2 |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | student2 | C1     | student |
    When I log in as "student2"
    And I follow "My courses"
    Then "//div[contains(@class, 'course-card')]//div[contains(@style, 'course/overviewfiles/login_bg1.png')]" "xpath_element" should not exist
    And "//div[contains(@class, 'course-card')]//div[contains(@style, 'data:image/svg+xml')]" "xpath_element" should exist

  @javascript
  Scenario: Setting: Course overview image source - Display the fallback to the fallback image if no course image is uploaded
    Given the following config values are set as admin:
      | config                    | value              | plugin            |
      | courseoverviewimagesource | courseplusfallback | theme_boost_union |
    And the following "users" exist:
      | username |
      | student2 |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | student2 | C1     | student |
    And the following "theme_boost_union > setting files" exist:
      | filearea                    | filepath                                       |
      | courseoverviewimagefallback | theme/boost_union/tests/fixtures/login_bg2.png |
    And the theme cache is purged and the theme is reloaded
    When I log in as "student2"
    And I follow "My courses"
    Then "//div[contains(@class, 'course-card')]//div[contains(@style, 'course/overviewfiles/login_bg1.png')]" "xpath_element" should not exist
    And "//div[contains(@class, 'course-card')]//div[contains(@style, 'theme_boost_union/courseoverviewimagefallback/0/login_bg2.png')]" "xpath_element" should exist
    And "//div[contains(@class, 'course-card')]//div[contains(@style, 'data:image/svg+xml')]" "xpath_element" should not exist
