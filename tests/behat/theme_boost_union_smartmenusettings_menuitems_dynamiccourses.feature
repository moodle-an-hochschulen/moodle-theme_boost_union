@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_dynamiccourses
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, using dynamic menus to automatically compose smart menu items
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "categories" exist:
      | name        | category | idnumber |
      | Category 01 | 0        | CAT1     |
      | Category 02 | 0        | CAT2     |
      | Category 03 | 0        | CAT3     |
    And the following "courses" exist:
      | fullname  | shortname | category | enablecompletion | startdate      | enddate         |
      | Course 01 | C1        | CAT1     | 1                | ## now ##      | ## +5 days ##   |
      | Course 02 | C2        | CAT1     | 1                | ##1 year ago## | ##1 month ago## |
      | Course 03 | C3        | CAT1     | 1                | ## +5 days ##  | 0               |
      | Course 04 | C4        | CAT2     | 1                | 0              | 0               |
      | Course 05 | C5        | CAT2     | 1                | 0              | 0               |
      | Course 06 | C6        | CAT3     | 1                | 0              | 0               |
    And the following "activities" exist:
      | activity | name                  | intro                       | course | idnumber | section | completion |
      | assign   | Test assignment name1 | Test assignment description | C1     | assign1  | 0       | 1          |
      | assign   | Test assignment name2 | Test assignment description | C1     | assign2  | 0       | 1          |
      | assign   | Test assignment name3 | Test assignment description | C2     | assign1  | 0       | 1          |
      | assign   | Test assignment name4 | Test assignment description | C2     | assign2  | 0       | 1          |
    And the following "users" exist:
      | username |
      | student1 |
      | teacher  |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher  | C1     | editingteacher |
      | teacher  | C5     | editingteacher |
      | teacher  | C6     | editingteacher |
      | student1 | C1     | student        |
      | student1 | C2     | student        |
      | student1 | C3     | student        |
      | student1 | C4     | student        |
    And the following "theme_boost_union > smart menu" exists:
      | title    | List menu                                        |
      | location | Main navigation, Menu bar, User menu, Bottom bar |
      | cssclass | dynamiccoursetest                                |

  @javascript
  Scenario: Smartmenus: Menu items: Dynamic courses - Check the smart menu item settings fields which are shown conditionally for dynamic courses
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | List menu       |
      | title    | Dynamic courses |
      | itemtype | Dynamic courses |
    And I am logged in as "admin"
    When I am on the "List menu > Dynamic courses" "theme_boost_union > smart menu item" page
    And I should see "Dynamic courses: Course category"
    And I should see "Dynamic courses: Enrolment role"
    And I should see "Dynamic courses: Completion status"
    And I should see "Dynamic courses: Date range"

  @javascript
  Scenario: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on all existing courses (without any condition)
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | List menu       |
      | title    | Dynamic courses |
      | itemtype | Dynamic courses |
    When I am logged in as "student1"
    Then "Course 01" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > Menu bar smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > User menu smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > Bottom bar smart menu"
    And "Course 02" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 03" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 04" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 05" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 06" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And I should see smart menu "List menu" item "Course 01" in location "Main, Menu, User, Bottom"

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on a category condition
    Given the following "theme_boost_union > smart menu item" exists:
      | menu       | List menu       |
      | title      | Dynamic courses |
      | itemtype   | Dynamic courses |
      | categories | <category>      |
    When I am logged in as "student1"
    Then "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Menu bar smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > User menu smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Bottom bar smart menu"
    And "Course 02" "theme_boost_union > Smart menu item" <course2> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 03" "theme_boost_union > Smart menu item" <course3> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 04" "theme_boost_union > Smart menu item" <course4> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 05" "theme_boost_union > Smart menu item" <course5> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 06" "theme_boost_union > Smart menu item" <course6> exist in the "List menu" "theme_boost_union > Main menu smart menu"

    Examples:
      | category | course1    | course2    | course3    | course4    | course5    | course6    |
      | CAT1     | should     | should     | should     | should not | should not | should not |
      | CAT2     | should not | should not | should not | should     | should     | should not |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on a category condition (with or without subcategories)
    Given the following "categories" exist:
      | name          | category | idnumber |
      | Category 01a  | CAT1     | CAT1a    |
      | Category 01b  | CAT1     | CAT1b    |
      | Category 01aa | CAT1a    | CAT1aa   |
    And the following "courses" exist:
      | fullname    | shortname | category |
      | Course 01a  | C1a       | CAT1a    |
      | Course 01b  | C1b       | CAT1b    |
      | Course 01aa | C1aa      | CAT1aa   |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | student1 | C1a    | student |
      | student1 | C1b    | student |
      | student1 | C1aa   | student |
    And the following "theme_boost_union > smart menu item" exists:
      | menu             | List menu       |
      | title            | Dynamic courses |
      | itemtype         | Dynamic courses |
      | categories       | CAT1            |
      | category_subcats | <subcat>        |
    When I log in as "student1"
    Then "Course 01" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > Menu bar smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > User menu smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" should exist in the "List menu" "theme_boost_union > Bottom bar smart menu"
    And "Course 01a" "theme_boost_union > Smart menu item" <shouldornot> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 01b" "theme_boost_union > Smart menu item" <shouldornot> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 01aa" "theme_boost_union > Smart menu item" <shouldornot> exist in the "List menu" "theme_boost_union > Main menu smart menu"

    Examples:
      | subcat | shouldornot |
      | 0      | should not  |
      | 1      | should      |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on a enrolment role condition
    # Empty menus are hidden from view. To prevent that the whole menu is missing and the test fails,
    # a sample item is created.
    Given the following "theme_boost_union > smart menu items" exist:
      | menu      | title           | itemtype        | enrolmentrole |
      | List menu | Info            | Heading         |               |
      | List menu | Dynamic courses | Dynamic courses | <role>        |
    When I log in as "<user>"
    Then "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Menu bar smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > User menu smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Bottom bar smart menu"
    And "Course 02" "theme_boost_union > Smart menu item" <course2> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 03" "theme_boost_union > Smart menu item" <course3> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 04" "theme_boost_union > Smart menu item" <course4> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 05" "theme_boost_union > Smart menu item" <course5> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 06" "theme_boost_union > Smart menu item" <course6> exist in the "List menu" "theme_boost_union > Main menu smart menu"

    Examples:
      | role                    | user     | course1    | course2    | course3    | course4    | course5    | course6    |
      | teacher, editingteacher | student1 | should not | should not | should not | should not | should not | should not |
      | teacher, editingteacher | teacher  | should     | should not | should not | should not | should     | should     |
      | student                 | student1 | should     | should     | should     | should     | should not | should not |
      | student                 | teacher  | should not | should not | should not | should not | should not | should not |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on a completion status condition
    Given the following "theme_boost_union > smart menu item" exists:
      | menu             | List menu          |
      | title            | Dynamic courses    |
      | itemtype         | Dynamic courses    |
      | completionstatus | <completionstatus> |
    And I am on the "Course 01" "theme_boost_union > Course completion" page logged in as "admin"
    And I expand all fieldsets
    And I set the following fields to these values:
      | Assignment - Test assignment name1 | 1 |
      | Assignment - Test assignment name2 | 1 |
    And I press "Save changes"
    And I am on the "Course 02" "theme_boost_union > Course completion" page
    And I expand all fieldsets
    And I set the following fields to these values:
      | Assignment - Test assignment name3 | 1 |
      | Assignment - Test assignment name4 | 1 |
    And I press "Save changes"
    And I am on the "Course 01" "course" page logged in as "student1"
    And the manual completion button of "Test assignment name1" is displayed as "Mark as done"
    And I toggle the manual completion state of "Test assignment name1"
    And the manual completion button of "Test assignment name2" is displayed as "Mark as done"
    And I toggle the manual completion state of "Test assignment name2"
    And I am on "Course 02" course homepage
    And the manual completion button of "Test assignment name3" is displayed as "Mark as done"
    And I toggle the manual completion state of "Test assignment name3"
    And I follow "Dashboard"
    Then "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Menu bar smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > User menu smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Bottom bar smart menu"
    And "Course 02" "theme_boost_union > Smart menu item" <course2> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 03" "theme_boost_union > Smart menu item" <course3> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 04" "theme_boost_union > Smart menu item" <course4> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 05" "theme_boost_union > Smart menu item" <course5> exist in the "List menu" "theme_boost_union > Main menu smart menu"

    Examples:
      | completionstatus                 | course1    | course2    | course3    | course4    | course5    |
      | Enrolled, In progress, Completed | should     | should     | should     | should     | should not |
      | In progress, Completed           | should     | should     | should not | should not | should not |
      | Enrolled                         | should not | should not | should     | should     | should not |
      | Completed                        | should     | should not | should not | should not | should not |
      | In progress                      | should not | should     | should not | should not | should not |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on a date range condition
    Given the following "theme_boost_union > smart menu item" exists:
      | menu             | List menu       |
      | title            | Dynamic courses |
      | itemtype         | Dynamic courses |
      | daterange        | <daterange>     |
    When I log in as "student1"
    Then "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Menu bar smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > User menu smart menu"
    And "Course 01" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Bottom bar smart menu"
    And "Course 02" "theme_boost_union > Smart menu item" <course2> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 03" "theme_boost_union > Smart menu item" <course3> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 04" "theme_boost_union > Smart menu item" <course4> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 05" "theme_boost_union > Smart menu item" <course5> exist in the "List menu" "theme_boost_union > Main menu smart menu"

    Examples:
      | daterange             | course1    | course2    | course3    | course4    | course5    |
      | Past, Present, Future | should     | should     | should     | should     | should     |
      | Future                | should not | should not | should     | should not | should not |
      | Present               | should     | should not | should not | should     | should     |
      | Past                  | should not | should     | should not | should not | should not |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Compose the dynamic course list based on a course field condition
    Given the following "custom field categories" exist:
      | name   | component   | area   | itemid |
      | Others | core_course | course | 0      |
    And the following "custom fields" exist:
      | name       | category  | type | shortname |
      | Test field | Others    | text | testfield |
    And the following "courses" exist:
      | fullname  | shortname | customfield_testfield |
      | Course 07 | C7        | value1                |
      | Course 08 | C8        | value1                |
      | Course 09 | C9        | value2                |
    And the following "theme_boost_union > smart menu item" exists:
      | menu             | List menu           |
      | title            | Dynamic courses     |
      | itemtype         | Dynamic courses     |
      | customfields     | Test field: <value> |
    When I log in as "student1"
    Then "Course 07" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 07" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Menu bar smart menu"
    And "Course 07" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > User menu smart menu"
    And "Course 07" "theme_boost_union > Smart menu item" <course1> exist in the "List menu" "theme_boost_union > Bottom bar smart menu"
    And "Course 08" "theme_boost_union > Smart menu item" <course2> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 09" "theme_boost_union > Smart menu item" <course3> exist in the "List menu" "theme_boost_union > Main menu smart menu"
    And "Course 04" "theme_boost_union > Smart menu item" <course4> exist in the "List menu" "theme_boost_union > Main menu smart menu"

    Examples:
      | value  | course1    | course2    | course3    | course4    |
      | value1 | should     | should     | should not | should not |
      | value2 | should not | should not | should     | should not |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Sort the course list based on the given setting
    Given the following "courses" exist:
      | fullname   | shortname | category | idnumber |
      | AAA Course | BBB       | CAT1     | CCC      |
      | BBB Course | AAA       | CAT1     | BBB      |
      | CCC Course | CCC       | CAT1     | AAA      |
    And the following "theme_boost_union > smart menu item" exists:
      | menu             | List menu       |
      | title            | Dynamic courses |
      | itemtype         | Dynamic courses |
      | listsort         | <sorting>       |
    When I log in as "student1"
    And I click on "List menu" "theme_boost_union > Smart menu"
    Then "<thisbeforethat1>" "text" should appear before "<thisbeforethat2>" "text" in the ".dynamiccoursetest .dropdown-menu" "css_element"
    And "<thisbeforethat2>" "text" should appear before "<thisbeforethat3>" "text" in the ".dynamiccoursetest .dropdown-menu" "css_element"

    Examples:
      | sorting                     | thisbeforethat1 | thisbeforethat2 | thisbeforethat3 |
      | Course fullname ascending   | AAA Course      | BBB Course      | CCC Course      |
      | Course fullname descending  | CCC Course      | BBB Course      | AAA Course      |
      | Course shortname ascending  | BBB Course      | AAA Course      | CCC Course      |
      | Course shortname descending | CCC Course      | AAA Course      | BBB Course      |
      | Course ID ascending         | AAA Course      | BBB Course      | CCC Course      |
      | Course ID descending        | CCC Course      | BBB Course      | AAA Course      |
      | Course ID number ascending  | CCC Course      | BBB Course      | AAA Course      |
      | Course ID number descending | AAA Course      | BBB Course      | CCC Course      |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Dynamic courses - Hide empty menus
    And the following "theme_boost_union > smart menu" exists:
      | title    | Mode menu                                        |
      | location | Main navigation, Menu bar, User menu, Bottom bar |
      | mode     | <menumode>                                       |
    And the following "theme_boost_union > smart menu item" exists:
      | menu          | Mode menu       |
      | title         | Dynamic courses |
      | itemtype      | Dynamic courses |
      | itemmode      | <menumode>      |
      | enrolmentrole | <role>          |
    When I log in as "<user>"
    Then "<menutitle>" "theme_boost_union > Main menu <locator>" <shouldornot> exist
    And "<menutitle>" "theme_boost_union > Menu bar <locator>" <shouldornot> exist
    And "<menutitle>" "theme_boost_union > User menu <locator>" <shouldornot> exist
    And "<menutitle>" "theme_boost_union > Bottom bar <locator>" <shouldornot> exist

    Examples:
      | role                    | user     | shouldornot | menutitle | menumode | locator         |
      | teacher, editingteacher | student1 | should not  | Mode menu | Submenu  | smart menu      |
      | teacher, editingteacher | teacher  | should      | Mode menu | Submenu  | smart menu      |
      | student                 | student1 | should      | Mode menu | Submenu  | smart menu      |
      | student                 | teacher  | should not  | Mode menu | Submenu  | smart menu      |
      | teacher, editingteacher | student1 | should not  | Course 01 | Inline   | smart menu item |
      | teacher, editingteacher | teacher  | should      | Course 01 | Inline   | smart menu item |
      | student                 | student1 | should      | Course 01 | Inline   | smart menu item |
      | student                 | teacher  | should not  | Course 01 | Inline   | smart menu item |
