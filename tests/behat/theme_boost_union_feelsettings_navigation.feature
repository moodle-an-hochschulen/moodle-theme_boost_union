@theme @theme_boost_union @theme_boost_union_feelsettings @theme_boost_union_feelsettings_navigation @theme_boost_union_footer
Feature: Configuring the theme_boost_union plugin for the "Navigation" tab on the "Feel" page
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

  Scenario Outline: Setting: Hide a single node in primary navigation.
    Given the following config values are set as admin:
      | config                     | value      | plugin            |
      | hidenodesprimarynavigation | <nodename> | theme_boost_union |
    When I log in as "admin"
    And I am on homepage
    Then I should not see "<nodetitle>" in the ".primary-navigation" "css_element"

    Examples:
      | nodename      | nodetitle           |
      | home          | Home                |
      | myhome        | Dashboard           |
      | courses       | My courses          |
      | siteadminnode | Site administration |

  Scenario Outline: Setting: Hide multiple nodes in primary navigation.
    Given the following config values are set as admin:
      | config                     | value       | plugin            |
      | hidenodesprimarynavigation | <nodenames> | theme_boost_union |
    When I log in as "admin"
    And I am on homepage
    Then I should not see "<firstnodetitle>" in the ".primary-navigation" "css_element"
    And I should not see "<secondnodetitle>" in the ".primary-navigation" "css_element"

    Examples:
      | nodenames             | firstnodetitle | secondnodetitle     |
      | home,myhome           | Home           | Dashboard           |
      | courses,siteadminnode | My courses     | Site administration |

  Scenario Outline: Setting: Alternative logo link URL.
    Given the following config values are set as admin:
      | config                 | value     | plugin            |
      | alternativelogolinkurl | <setting> | theme_boost_union |
    And the following config values are set as admin:
      # We set the start page to the Dashboard to be able to distinguish the used link URL by the '/my/' path later.
      | config          | value |
      | defaulthomepage | 1     |
    When I log in as "admin"
    And I am on homepage
    Then the "href" attribute of ".navbar-brand" "css_element" should contain "<href>"

    Examples:
      | setting         | href            |
      |                 | /my/            |
      | https://foo.bar | https://foo.bar |

  @javascript
  Scenario Outline: Setting:  Show full name in the user menu.
    Given the following config values are set as admin:
      | config                 | value     | plugin            |
      | showfullnameinusermenu | <setting> | theme_boost_union |
    And the following "users" exist:
      | username     | firstname | lastname |
      | menutestuser | Menutest  | User     |
    When I log in as "menutestuser"
    And I click on "User menu" "button" in the ".usermenu" "css_element"
    Then ".usermenu .loggedinas" "css_element" <shouldornot> exist
    And I <shouldornot> see "You are logged in as:" in the ".usermenu .carousel-inner" "css_element"
    And I <shouldornot> see "Menutest User" in the ".usermenu .carousel-inner" "css_element"

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  @javascript
  Scenario Outline: Setting: Add preferred language link to language menu.
    Given the following "language packs" exist:
      | language |
      | de       |
    And the following config values are set as admin:
      | langmenu | 1 |
    And the following config values are set as admin:
      | config           | value     | plugin            |
      | addpreferredlang | <setting> | theme_boost_union |
    When I log in as "admin"
    And I click on "User menu" "button" in the ".usermenu" "css_element"
    And I click on "Language" "link" in the ".usermenu" "css_element"
    Then I <shouldornot> see "Set preferred language" in the ".usermenu .carousel-item.submenu" "css_element"
    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  @javascript
  Scenario Outline: Setting: Show starred courses popover in the navbar.
    Given the following config values are set as admin:
      | config                   | value     | plugin            |
      | shownavbarstarredcourses | <setting> | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "student1"
    And I follow "My courses"
    And I click on ".coursemenubtn" "css_element" in the "//div[contains(@class, 'card course-card') and contains(.,'Course 1')]" "xpath_element"
    And I click on "Star this course" "link" in the "//div[contains(@class, 'card course-card') and contains(.,'Course 1')]" "xpath_element"
    And I reload the page
    Then "nav.navbar #usernavigation .popover-region-favourites" "css_element" <shouldornot> be visible

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  @javascript
  Scenario: Setting: Show starred courses popover in the navbar (and make sure that I see the right courses there).
    Given the following config values are set as admin:
      | config                   | value | plugin            |
      | shownavbarstarredcourses | yes   | theme_boost_union |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 2 | C2        |
      | Course 3 | C3        |
      | Course 4 | C4        |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | student1 | C2     | student |
      | student1 | C3     | student |
      | student1 | C4     | student |
    And the theme cache is purged and the theme is reloaded
    When I log in as "student1"
    And I follow "My courses"
    And I click on ".coursemenubtn" "css_element" in the "//div[contains(@class, 'card course-card') and contains(.,'Course 2')]" "xpath_element"
    And I click on "Star this course" "link" in the "//div[contains(@class, 'card course-card') and contains(.,'Course 2')]" "xpath_element"
    And I click on ".coursemenubtn" "css_element" in the "//div[contains(@class, 'card course-card') and contains(.,'Course 3')]" "xpath_element"
    And I click on "Star this course" "link" in the "//div[contains(@class, 'card course-card') and contains(.,'Course 3')]" "xpath_element"
    And I log out
    And I log in as "admin"
    And I am on "Course 3" course homepage
    And I navigate to course participants
    And I click on "Unenrol" "icon" in the "student1" "table_row"
    And I click on "Unenrol" "button" in the "Unenrol" "dialogue"
    And I log out
    And I log in as "student1"
    And I click on "nav.navbar #usernavigation .popover-region-favourites .nav-link" "css_element"
    Then I should not see "Course 1" in the ".popover-region-favourites .popover-region-content-container" "css_element"
    And I should see "Course 2" in the ".popover-region-favourites .popover-region-content-container" "css_element"
    And I should not see "Course 3" in the ".popover-region-favourites .popover-region-content-container" "css_element"
    And I should not see "Course 4" in the ".popover-region-favourites .popover-region-content-container" "css_element"

  Scenario Outline: Setting: Course category breadcrumbs
    Given the following "categories" exist:
      | name           | category | idnumber | category |
      | Category E     | 0        | CE       | 0        |
      | Category ED    | 1        | CED      | CE       |
      | Category EDC   | 2        | CEDC     | CED      |
      | Category EDCB  | 3        | CEDCB    | CEDC     |
    And the following "courses" exist:
      | fullname  | shortname | category |
      | Course C1 | CC1       | CE       |
      | Course C2 | CC2       | CED      |
      | Course C3 | CC3       | CEDC     |
      | Course C4 | CC4       | CEDCB    |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | CC1    | editingteacher |
      | teacher1 | CC2    | editingteacher |
      | teacher1 | CC3    | editingteacher |
      | teacher1 | CC4    | editingteacher |
    And the following config values are set as admin:
      | config              | value     | plugin            |
      | categorybreadcrumbs | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course C1" course homepage
    Then "Category E" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And I am on "Course C2" course homepage
    And "Category E" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category ED" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And I am on "Course C3" course homepage
    And "Category E" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category ED" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category EDC" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And I am on "Course C4" course homepage
    And "Category E" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category ED" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category EDC" "link" <shouldornot> exist in the ".breadcrumb" "css_element"
    And "Category EDCB" "link" <shouldornot> exist in the ".breadcrumb" "css_element"

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario: Setting: Course category breadcrumbs (verify that course sections are properly displayed _after_ the categories)
    Given the following "categories" exist:
      | name           | category | idnumber | category |
      | Category E     | 0        | CE       | 0        |
      | Category ED    | 1        | CED      | CE       |
    And the following "courses" exist:
      | fullname  | shortname | category |
      | Course C1 | CC1       | CED      |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | CC1    | editingteacher |
    And the following config values are set as admin:
      | config              | value     | plugin            |
      | categorybreadcrumbs | yes       | theme_boost_union |
    When I log in as "teacher1"
    And I am on the "Course C1 > New section" "course > section" page
    Then "Category E" "link" should exist in the ".breadcrumb" "css_element"
    And "Category ED" "link" should exist in the ".breadcrumb" "css_element"
    And "New section" "link" should exist in the ".breadcrumb" "css_element"
    And "Category ED" "link" should appear after "Category E" "link" in the ".breadcrumb" "css_element"
    And "New section" "link" should appear after "Category ED" "link" in the ".breadcrumb" "css_element"

  @javascript
  Scenario: Setting: back to top button - Enable "Back to top button"
    Given the following config values are set as admin:
      | config          | value | plugin            |
      | backtotopbutton | yes   | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then "#back-to-top" "css_element" should exist
    And "#page-footer" "css_element" should appear before "#back-to-top" "css_element"
    And "#back-to-top" "css_element" should not be visible
    And I scroll page to x "0" y "250"
    And "#back-to-top" "css_element" should be visible
    And I click on "#back-to-top" "css_element"
    # Then I wait 1 second as the scroll up process is animated
    And I wait "1" seconds
    And "#back-to-top" "css_element" should not be visible

  @javascript
  Scenario: Setting: back to top button - Disable "Back to top button" (countercheck)
    Given the following config values are set as admin:
      | config          | value | plugin            |
      | backtotopbutton | no    | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then "#back-to-top" "css_element" should not exist

  @javascript
  Scenario: Setting: Scrollspy - Enable "Scrollspy"
    Given the following config values are set as admin:
      | config          | value | plugin             |
      | scrollspy       | yes   | theme_boost_union  |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I scroll page to DOM element with ID "section-4"
    And I make the navbar fixed
    And I turn editing mode on
    And I wait "2" seconds
    Then DOM element "section-4" is at the top of the viewport
    And page top is not at the top of the viewport
    And I make the navbar fixed
    And I turn editing mode off
    And I wait "2" seconds
    Then DOM element "section-4" is at the top of the viewport
    And page top is not at the top of the viewport

  @javascript
  Scenario: Setting: Scrollspy - Disable "Scrollspy" (countercheck)
    Given the following config values are set as admin:
      | config          | value | plugin             |
      | scrollspy       | no    | theme_boost_union  |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I scroll page to x "0" y "250"
    And I turn editing mode on
    Then page top is at the top of the viewport
    And I scroll page to x "0" y "250"
    And I turn editing mode off
    Then page top is at the top of the viewport

  @javascript
  Scenario: Settings: back to top button in combination with the scrollspy - Make sure that the back to top button is always shown
    Given the following config values are set as admin:
      | config          | value | plugin            |
      | backtotopbutton | yes   | theme_boost_union |
      | scrollspy       | yes   | theme_boost_union  |
    When I log in as "admin"
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    And I am on "Course 1" course homepage
    And "#back-to-top" "css_element" should exist
    And "#back-to-top" "css_element" should not be visible
    And I scroll page to DOM element with ID "section-4"
    And I make the navbar fixed
    And I turn editing mode on
    And I wait "2" seconds
    And page top is not at the top of the viewport
    Then "#back-to-top" "css_element" should be visible

  @javascript
  Scenario: Setting: Activity navigation - Enable "Activity navigation"
    Given the following config values are set as admin:
      | config             | value | plugin            |
      | activitynavigation | yes   | theme_boost_union |
    And the following "activities" exist:
      | activity | name    | course | idnumber |
      | forum    | Forum 1 | C1     | forum1   |
      | forum    | Forum 2 | C1     | forum2   |
      | forum    | Forum 3 | C1     | forum3   |
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    When I follow "Forum 2"
    Then I should see "Forum 1" in the "#prev-activity-link" "css_element"
    And I should see "Forum 3" in the "#next-activity-link" "css_element"

  @javascript
  Scenario: Setting: Activity navigation - Disable "Activity navigation" (countercheck)
    Given the following config values are set as admin:
      | config             | value | plugin            |
      | activitynavigation | no    | theme_boost_union |
    And the following "activities" exist:
      | activity | name    | course | idnumber |
      | forum    | Forum 1 | C1     | forum1   |
      | forum    | Forum 2 | C1     | forum2   |
      | forum    | Forum 3 | C1     | forum3   |
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    When I follow "Forum 2"
    Then "#prev-activity-link" "css_element" should not exist
    And "#next-activity-link" "css_element" should not exist
