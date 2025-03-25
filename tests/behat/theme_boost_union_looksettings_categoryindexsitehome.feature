@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_categoryindexsitehome
Feature: Configuring the theme_boost_union plugin for the "Category index / site home" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following config values are set as admin:
      | config                    | value   |
      | frontpageloggedin         | 2,4,5,6 |
      # We set courseswithsummarieslimit to a really small value as we have just some test courses and want to especially test
      # what happens when there are not enough courses to exceed this setting.
      | courseswithsummarieslimit | 1 |
    And the following "users" exist:
      | username |
      | student1 |
    And the following "categories" exist:
      | name        | category | idnumber |
      | Category A  | 0        | CATA     |
      | Category B  | 0        | CATB     |
      | Category BB | CATB     | CATBB    |
    And the following "courses" exist:
      | fullname | shortname | category | enablecompletion | showcompletionconditions |
      | Course 1 | C1        | CATA     | 1                | 1                        |
      | Course 2 | C2        | CATB     | 1                | 1                        |
      | Course 3 | C3        | CATBB    | 1                | 1                        |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | student1 | C1     | student |
      | student1 | C2     | student |
      | student1 | C3     | student |

  @javascript
  Scenario Outline: Setting: Course listing presentation: Set the setting
    Given the following config values are set as admin:
      | config                    | value          | plugin            |
      | courselistingpresentation | <settingvalue> | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole
    Then ".theme_boost_union-courselisting-<cssclass1>" "css_element" <shouldornot1> exist in the "#frontpage-category-combo" "css_element"
    And ".theme_boost_union-courselisting-<cssclass2>" "css_element" <shouldornot2> exist in the "#frontpage-category-combo" "css_element"
    # Check a subcategory in the 'Combo list' view on site home
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And ".theme_boost_union-courselisting-<cssclass1>" "css_element" <shouldornot1> exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And ".theme_boost_union-courselisting-<cssclass2>" "css_element" <shouldornot2> exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    # Check the 'Enrolled courses' view on site home
    And ".theme_boost_union-courselisting-<cssclass1>" "css_element" <shouldornot1> exist in the "#frontpage-course-list" "css_element"
    And ".theme_boost_union-courselisting-<cssclass2>" "css_element" <shouldornot2> exist in the "#frontpage-course-list" "css_element"
    # Check the 'List of courses' view on site home
    And ".theme_boost_union-courselisting-<cssclass1>" "css_element" <shouldornot1> exist in the "#frontpage-available-course-list" "css_element"
    And ".theme_boost_union-courselisting-<cssclass2>" "css_element" <shouldornot2> exist in the "#frontpage-available-course-list" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then ".theme_boost_union-courselisting-<cssclass1>" "css_element" <shouldornot1> exist in the ".course_category_tree" "css_element"
    And ".theme_boost_union-courselisting-<cssclass2>" "css_element" <shouldornot2> exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then ".theme_boost_union-courselisting-<cssclass1>" "css_element" <shouldornot1> exist in the ".course_category_tree" "css_element"
    And ".theme_boost_union-courselisting-<cssclass2>" "css_element" <shouldornot2> exist in the ".course_category_tree" "css_element"
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And ".theme_boost_union-courselisting-<cssclass1>" "css_element" <shouldornot1> exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And ".theme_boost_union-courselisting-<cssclass2>" "css_element" <shouldornot2> exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"

    Examples:
      | settingvalue | cssclass1 | shouldornot1 | cssclass2 | shouldornot2 |
      | nochange     | card      | should not   | list      | should not   |
      | cards        | card      | should       | list      | should not   |
      | list         | list      | should       | card      | should not   |

  @javascript
  Scenario Outline: Setting: Category listing presentation: Set the setting
    Given the following config values are set as admin:
      | config                      | value          | plugin            |
      | categorylistingpresentation | <settingvalue> | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'List of categories' view on site home
    Then ".theme_boost_union-catlisting" "css_element" <shouldornot> exist in the "#frontpage-category-names" "css_element"
    # Check the 'Combo list' view on site home
    And ".theme_boost_union-catlisting" "css_element" <shouldornot> exist in the "#frontpage-category-combo" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then ".theme_boost_union-catlisting" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    And ".theme_boost_union-coursecategoryinfo" "css_element" <shouldornot> exist
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then ".theme_boost_union-catlisting" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    And ".theme_boost_union-coursecategoryinfo" "css_element" <shouldornot> exist

    Examples:
      | settingvalue | shouldornot |
      | nochange     | should not  |
      | boxlist      | should      |

  @javascript
  Scenario Outline: Setting: Course listing presentation / Category listing presentation: Set both settings
    Given the following config values are set as admin:
      | config                      | value           | plugin            |
      | courselistingpresentation   | <coursevalue>   | theme_boost_union |
      | categorylistingpresentation | <categoryvalue> | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home
    And ".theme_boost_union-catlisting-cl" "css_element" <shouldornot> exist in the "#frontpage-category-combo" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then ".theme_boost_union-catlisting-cl" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then ".theme_boost_union-catlisting-cl" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"

    Examples:
      | coursevalue  | categoryvalue | shouldornot |
      | nochange     | nochange      | should not  |
      | nochange     | boxlist       | should not  |
      | cards        | nochange      | should not  |
      | list         | nochange      | should not  |
      | cards        | boxlist       | should      |
      | list         | boxlist       | should      |

  @javascript
  Scenario Outline: Setting: Course card column count: Set the setting
    Given the following config values are set as admin:
      | config                    | value          | plugin            |
      | courselistingpresentation | cards          | theme_boost_union |
      | coursecardscolumncount    | <settingvalue> | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole
    Then ".card-grid.<cssclass>" "css_element" should exist in the "#frontpage-category-combo" "css_element"
    # Check a subcategory in the 'Combo list' view on site home
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And ".card-grid.<cssclass>" "css_element" should exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    # Check the 'Enrolled courses' view on site home
    And ".card-grid.<cssclass>" "css_element" should exist in the "#frontpage-course-list" "css_element"
    # Check the 'List of courses' view on site home
    And ".card-grid.<cssclass>" "css_element" should exist in the "#frontpage-available-course-list" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then ".card-grid.<cssclass>" "css_element" should exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then ".card-grid.<cssclass>" "css_element" should exist in the ".course_category_tree" "css_element"
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And ".card-grid.<cssclass>" "css_element" should exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"

    Examples:
      | settingvalue | cssclass                               |
      | 1            | row-cols-1.row-cols-sm-1.row-cols-lg-1 |
      | 2            | row-cols-1.row-cols-sm-2.row-cols-lg-2 |
      | 3            | row-cols-1.row-cols-sm-3.row-cols-lg-3 |

  @javascript
  Scenario Outline: Setting: Show course image in the course listing: Set the setting
    Given the following config values are set as admin:
      | config                    | value          | plugin            |
      | courselistingpresentation | <coursevalue>  | theme_boost_union |
      | courselistinghowimage     | <settingvalue> | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole
    Then "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo" "css_element"
    # Check a subcategory in the 'Combo list' view on site home
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    # Check the 'Enrolled courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-course-list" "css_element"
    # Check the 'List of courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-available-course-list" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"

    Examples:
      | coursevalue | settingvalue | selector                     | shouldornot |
      | cards       | no           | .course-card .card-img-top   | should not  |
      | cards       | yes          | .course-card .card-img-top   | should      |
      | list        | no           | .course-listitem .list-image | should not  |
      | list        | yes          | .course-listitem .list-image | should      |

  @javascript
  Scenario Outline: Setting: Show course contacts in the course listing: Set the setting
    Given the following config values are set as admin:
      | config                    | value           | plugin            |
      | courselistingpresentation | <coursevalue>   | theme_boost_union |
      | courselistinghowimage     | <imagevalue>    | theme_boost_union |
      | courselistingshowcontacts | <contactsvalue> | theme_boost_union |
    And the following "users" exist:
      | username | firstname | lastname |
      | teacher1 | John      | Doe      |
      | teacher2 | Jane      | Doe      |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | teacher2 | C1     | editingteacher |
      | teacher1 | C2     | editingteacher |
      | teacher2 | C2     | editingteacher |
      | teacher1 | C3     | editingteacher |
      | teacher2 | C3     | editingteacher |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole
    Then "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo" "css_element"
    # Check a subcategory in the 'Combo list' view on site home
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    # Check the 'Enrolled courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-course-list" "css_element"
    # Check the 'List of courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-available-course-list" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"

    Examples:
      | coursevalue | imagevalue | contactsvalue | selector                         | shouldornot |
      | cards       | no         | no            | .course-card .coursecontacts     | should not  |
      | cards       | yes        | no            | .course-card .coursecontacts     | should not  |
      | cards       | yes        | yes           | .course-card .coursecontacts     | should      |
      | list        | no         | no            | .course-listitem .coursecontacts | should not  |
      | list        | yes        | no            | .course-listitem .coursecontacts | should not  |
      | list        | yes        | yes           | .course-listitem .coursecontacts | should      |

  @javascript
  Scenario Outline: Setting: Show course contacts in the course listing: Check the content
    Given the following config values are set as admin:
      | config                    | value | plugin            |
      | courselistingpresentation | cards | theme_boost_union |
      | courselistinghowimage     | yes   | theme_boost_union |
      | courselistingshowcontacts | yes   | theme_boost_union |
    And the following "users" exist:
      | username | firstname | lastname |
      | teacher1 | John      | Doe      |
      | teacher2 | Jane      | Doe      |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | teacher2 | C1     | editingteacher |
    When I log in as "<loginas>"
    And I am on the "CATA" category page
    Then ".course-card .coursecontacts" "css_element" should exist in the ".course_category_tree" "css_element"
    Then the "alt" attribute of ".course-card .coursecontacts <selectorforcontact1>:nth-of-type(1) <selectorforcontact2> img" "css_element" should contain "Jane Doe"
    And the "alt" attribute of ".course-card .coursecontacts <selectorforcontact1>:nth-of-type(2) <selectorforcontact2> img" "css_element" should contain "John Doe"

    Examples:
      | loginas  | selectorforcontact1 | selectorforcontact2 |
      | student1 | .contact            |                     |
      | admin    | a                   | .contact            |

  @javascript
  Scenario Outline: Setting: Show course shortname in the course listing: Set the setting
    Given the following config values are set as admin:
      | config                    | value          | plugin            |
      | courselistingpresentation | <coursevalue>  | theme_boost_union |
      | courselistinghowshortname | <settingvalue> | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole
    Then "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo" "css_element"
    # Check a subcategory in the 'Combo list' view on site home
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    # Check the 'Enrolled courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-course-list" "css_element"
    # Check the 'List of courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-available-course-list" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"

    Examples:
      | coursevalue | settingvalue | selector                    | shouldornot |
      | cards       | no           | .course-card .shortname     | should not  |
      | cards       | yes          | .course-card .shortname     | should      |
      | list        | no           | .course-listitem .shortname | should not  |
      | list        | yes          | .course-listitem .shortname | should      |

  @javascript
  Scenario Outline: Setting: Show course shortname in the course listing: Check the content
    Given the following config values are set as admin:
      | config                    | value         | plugin            |
      | courselistingpresentation | <coursevalue> | theme_boost_union |
      | courselistinghowshortname | yes           | theme_boost_union |
    When I log in as "student1"
    And I am on the "CATA" category page
    Then "<selector>" "css_element" should exist in the ".course_category_tree" "css_element"
    And I should see "C1" in the "<selector>" "css_element"

    Examples:
      | coursevalue | selector                    |
      | cards       | .course-card .shortname     |
      | list        | .course-listitem .shortname |

  @javascript
  Scenario Outline: Setting: Show course category in the course listing: Set the setting
    Given the following config values are set as admin:
      | config                    | value          | plugin            |
      | courselistingpresentation | <coursevalue>  | theme_boost_union |
      | courselistinghowcategory  | <settingvalue> | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole
    Then "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo" "css_element"
    # Check a subcategory in the 'Combo list' view on site home
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    # Check the 'Enrolled courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-course-list" "css_element"
    # Check the 'List of courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-available-course-list" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"

    Examples:
      | coursevalue | settingvalue | selector                       | shouldornot |
      | cards       | no           | .course-card .categoryname     | should not  |
      | cards       | yes          | .course-card .categoryname     | should      |
      | list        | no           | .course-listitem .categoryname | should not  |
      | list        | yes          | .course-listitem .categoryname | should      |

  @javascript
  Scenario Outline: Setting: Show course category in the course listing: Check the content
    Given the following config values are set as admin:
      | config                    | value         | plugin            |
      | courselistingpresentation | <coursevalue> | theme_boost_union |
      | courselistinghowcategory  | yes           | theme_boost_union |
    When I log in as "student1"
    And I am on the "CATA" category page
    Then "<selector>" "css_element" should exist in the ".course_category_tree" "css_element"
    And I should see "Category A" in the "<selector>" "css_element"

    Examples:
      | coursevalue | selector                       |
      | cards       | .course-card .categoryname     |
      | list        | .course-listitem .categoryname |

  @javascript
  Scenario Outline: Setting: Show course completion progress in the course listing: Set the setting
    Given the following config values are set as admin:
      | config                    | value          | plugin            |
      | courselistingpresentation | <coursevalue>  | theme_boost_union |
      | courselistinghowprogress  | <settingvalue> | theme_boost_union |
    And the following "activities" exist:
      | activity | name              | course | completion |
      | assign   | Activity sample 1 | C1     | 1          |
      | assign   | Activity sample 1 | C2     | 1          |
      | assign   | Activity sample 1 | C3     | 1          |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole
    Then "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo" "css_element"
    # Check a subcategory in the 'Combo list' view on site home
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    # Check the 'Enrolled courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-course-list" "css_element"
    # Check the 'List of courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-available-course-list" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"

    Examples:
      | coursevalue | settingvalue | selector                        | shouldornot |
      | cards       | no           | .course-card .progress-text     | should not  |
      | cards       | yes          | .course-card .progress-text     | should      |
      | list        | no           | .course-listitem .progress-text | should not  |
      | list        | yes          | .course-listitem .progress-text | should      |

  @javascript
  Scenario Outline: Setting: Show course completion progress in the course listing: Check the content
    Given the following config values are set as admin:
      | config                    | value         | plugin            |
      | courselistingpresentation | <coursevalue> | theme_boost_union |
      | courselistinghowprogress  | yes           | theme_boost_union |
    And the following "activities" exist:
      | activity | name              | course | completion |
      | assign   | Activity sample 1 | C1     | 1          |
      | assign   | Activity sample 1 | C2     | 1          |
      | assign   | Activity sample 1 | C3     | 1          |
    When I log in as "student1"
    And I am on the "CATA" category page
    Then "<selector>" "css_element" should exist in the ".course_category_tree" "css_element"
    And I should see "0% complete" in the "<selector>" "css_element"

    Examples:
      | coursevalue | selector                        |
      | cards       | .course-card .progress-text     |
      | list        | .course-listitem .progress-text |

  @javascript
  Scenario Outline: Setting: Show course enrolment icons in the course listing: Set the setting
    Given the following config values are set as admin:
      | config                     | value          | plugin            |
      | courselistingpresentation  | <coursevalue>  | theme_boost_union |
      | courselistinghowenrolicons | <settingvalue> | theme_boost_union |
    And the following config values are set as admin:
      | config           | value |
      | guestloginbutton | 1     |
    And I log in as "admin"
    And I am on the "Course 1" "enrolment methods" page
    And I click on "Edit" "link" in the "Guest access" "table_row"
    And I set the following fields to these values:
      | Allow guest access | Yes |
    And I press "Save changes"
    And I am on the "Course 2" "enrolment methods" page
    And I click on "Edit" "link" in the "Guest access" "table_row"
    And I set the following fields to these values:
      | Allow guest access | Yes |
    And I press "Save changes"
    And I am on the "Course 3" "enrolment methods" page
    And I click on "Edit" "link" in the "Guest access" "table_row"
    And I set the following fields to these values:
      | Allow guest access | Yes |
    And I press "Save changes"
    And I log out
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole
    Then "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo" "css_element"
    # Check a subcategory in the 'Combo list' view on site home
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    # Check the 'Enrolled courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-course-list" "css_element"
    # Check the 'List of courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-available-course-list" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"

    Examples:
      | coursevalue | settingvalue | selector                         | shouldornot |
      | cards       | no           | .course-card .enrolmenticons     | should not  |
      | cards       | yes          | .course-card .enrolmenticons     | should      |
      | list        | no           | .course-listitem .enrolmenticons | should not  |
      | list        | yes          | .course-listitem .enrolmenticons | should      |

  @javascript
  Scenario Outline: Setting: Show course enrolment icons in the course listing: Check the content
    Given the following config values are set as admin:
      | config                     | value         | plugin            |
      | courselistingpresentation  | <coursevalue> | theme_boost_union |
      | courselistinghowenrolicons | yes           | theme_boost_union |
    And the following config values are set as admin:
      | config           | value |
      | guestloginbutton | 1     |
    And I log in as "admin"
    And I am on the "Course 1" "enrolment methods" page
    And I click on "Enable" "link" in the "Self enrolment (Student)" "table_row"
    And I click on "Edit" "link" in the "Guest access" "table_row"
    And I set the following fields to these values:
      | Allow guest access | Yes |
    And I press "Save changes"
    When I log in as "student1"
    And I am on the "CATA" category page
    Then "<selector>" "css_element" should exist in the ".course_category_tree" "css_element"
    And ".fa-unlock-alt" "css_element" should exist in the "<selector> .enrolmenticon:nth-of-type(1)" "css_element"
    And ".fa-sign-in" "css_element" should exist in the "<selector> .enrolmenticon:nth-of-type(2)" "css_element"

    Examples:
      | coursevalue | selector                         |
      | cards       | .course-card .enrolmenticons     |
      | list        | .course-listitem .enrolmenticons |

  @javascript
  Scenario Outline: Setting: Show course fields in the course listing: Set the setting
    Given the following config values are set as admin:
      | config                    | value          | plugin            |
      | courselistingpresentation | <coursevalue>  | theme_boost_union |
      | courselistinghowfields    | <settingvalue> | theme_boost_union |
    And the following "custom field categories" exist:
      | name          | component   | area   | itemid |
      | Fieldcategory | core_course | course | 0      |
    And the following "custom fields" exist:
      | name    | category      | type     | shortname | description | configdata            |
      | Field 1 | Fieldcategory | text     | f1        | d1          |                       |
      | Field 2 | Fieldcategory | select   | f2        | d2          | {"options":"a\nb\nc"} |
    And I log in as "admin"
    And I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Field 1 | test |
      | Field 2 | a    |
    And I press "Save and display"
    And I am on "Course 2" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Field 1 | test |
      | Field 2 | a    |
    And I press "Save and display"
    And I am on "Course 3" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Field 1 | test |
      | Field 2 | a    |
    And I press "Save and display"
    And I log out
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole
    Then "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo" "css_element"
    # Check a subcategory in the 'Combo list' view on site home
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    # Check the 'Enrolled courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-course-list" "css_element"
    # Check the 'List of courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-available-course-list" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"

    Examples:
      | coursevalue | settingvalue | selector                       | shouldornot |
      | cards       | no           | .course-card .customfields     | should not  |
      | cards       | yes          | .course-card .customfields     | should      |
      | list        | no           | .course-listitem .customfields | should not  |
      | list        | yes          | .course-listitem .customfields | should      |

  @javascript
  Scenario Outline: Setting: Show course fields in the course listing: Check the content
    Given the following config values are set as admin:
      | config                    | value         | plugin            |
      | courselistingpresentation | <coursevalue> | theme_boost_union |
      | courselistinghowfields    | yes           | theme_boost_union |
    And the following "custom field categories" exist:
      | name          | component   | area   | itemid |
      | Fieldcategory | core_course | course | 0      |
    And the following "custom fields" exist:
      | name    | category      | type     | shortname | description | configdata            |
      | Field 1 | Fieldcategory | text     | f1        | d1          |                       |
      | Field 2 | Fieldcategory | select   | f2        | d2          | {"options":"a\nb\nc"} |
    And I log in as "admin"
    And I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Field 1 | test |
      | Field 2 | a    |
    And I press "Save and display"
    And I am on "Course 2" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Field 1 | test |
      | Field 2 | a    |
    And I press "Save and display"
    And I am on "Course 3" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Field 1 | test |
      | Field 2 | a    |
    And I press "Save and display"
    And I log out
    When I log in as "student1"
    And I am on the "CATA" category page
    Then "<selector>" "css_element" should exist in the ".course_category_tree" "css_element"
    And I should see "Field 1" in the "<selector> .customfield.customfield_text .customfieldname" "css_element"
    And I should see "test" in the "<selector> .customfield.customfield_text .customfieldvalue" "css_element"
    And I should see "Field 2" in the "<selector> .customfield.customfield_select .customfieldname" "css_element"
    And I should see "a" in the "<selector> .customfield.customfield_select .customfieldvalue" "css_element"

    Examples:
      | coursevalue | selector                       |
      | cards       | .course-card .customfields     |
      | list        | .course-listitem .customfields |

  @javascript
  Scenario Outline: Setting: Show goto button in the course listing: Set the setting
    Given the following config values are set as admin:
      | config                    | value          | plugin            |
      | courselistingpresentation | <coursevalue>  | theme_boost_union |
      | courselistinghowgoto      | <settingvalue> | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole
    Then "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo" "css_element"
    # Check a subcategory in the 'Combo list' view on site home
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    # Check the 'Enrolled courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-course-list" "css_element"
    # Check the 'List of courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-available-course-list" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"

    Examples:
      | coursevalue | settingvalue | selector                              | shouldornot |
      | cards       | no           | .course-card .card-footer .gotobutton | should not  |
      | cards       | yes          | .course-card .card-footer .gotobutton | should      |
      | list        | no           | .course-listitem .gotobutton          | should not  |
      | list        | yes          | .course-listitem .gotobutton          | should      |

  @javascript
  Scenario Outline: Setting: Show goto button in the course listing: Check the content
    Given the following config values are set as admin:
      | config                    | value         | plugin            |
      | courselistingpresentation | <coursevalue> | theme_boost_union |
      | courselistinghowgoto      | yes           | theme_boost_union |
    When I log in as "student1"
    And I am on the "CATA" category page
    Then "<selector>" "css_element" should exist in the ".course_category_tree" "css_element"
    And I click on "<selector>" "css_element"
    And I should see "Course 1" in the "#page-header" "css_element"

    Examples:
      | coursevalue | selector                              |
      | cards       | .course-card .card-footer .gotobutton |
      | list        | .course-listitem .gotobutton          |

  @javascript
  Scenario Outline: Setting: Show details popup in the course listing: Set the setting
    Given the following config values are set as admin:
      | config                    | value          | plugin            |
      | courselistingpresentation | <coursevalue>  | theme_boost_union |
      | courselistinghowpopup     | <settingvalue> | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole
    Then "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo" "css_element"
    # Check a subcategory in the 'Combo list' view on site home
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    # Check the 'Enrolled courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-course-list" "css_element"
    # Check the 'List of courses' view on site home
    And "<selector>" "css_element" <shouldornot> exist in the "#frontpage-available-course-list" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree" "css_element"
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" <shouldornot> exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"

    Examples:
      | coursevalue | settingvalue | selector                               | shouldornot |
      | cards       | no           | .course-card .card-footer .popupbutton | should not  |
      | cards       | yes          | .course-card .card-footer .popupbutton | should      |
      | list        | no           | .course-listitem .popupbutton          | should not  |
      | list        | yes          | .course-listitem .popupbutton          | should      |

  @javascript
  Scenario Outline: Setting: Show details popup in the course listing: Click the button
    Given the following config values are set as admin:
      | config                    | value          | plugin            |
      | courselistingpresentation | <coursevalue>  | theme_boost_union |
      | courselistinghowpopup     | yes            | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole
    Then "<selector>" "css_element" should exist in the "#frontpage-category-combo" "css_element"
    And I click on "<selector>" "css_element" in the "#frontpage-category-combo" "css_element"
    And ".modal-dialog" "css_element" should be visible
    And I click on ".modal-dialog button.close" "css_element"
    # Check a subcategory in the 'Combo list' view on site home
    And I reload the page
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" should exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And I click on "<selector>" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    And ".modal-dialog" "css_element" should be visible
    And I click on ".modal-dialog button.close" "css_element"
    # Check the 'Enrolled courses' view on site home
    And I reload the page
    And "<selector>" "css_element" should exist in the "#frontpage-course-list" "css_element"
    And I click on "<selector>" "css_element" in the "#frontpage-course-list" "css_element"
    And ".modal-dialog" "css_element" should be visible
    And I click on ".modal-dialog button.close" "css_element"
    # Check the 'List of courses' view on site home
    And I reload the page
    And "<selector>" "css_element" should exist in the "#frontpage-available-course-list" "css_element"
    And I click on "<selector>" "css_element" in the "#frontpage-available-course-list" "css_element"
    And ".modal-dialog" "css_element" should be visible
    And I click on ".modal-dialog button.close" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then "<selector>" "css_element" should exist in the ".course_category_tree" "css_element"
    And I click on "<selector>" "css_element" in the ".course_category_tree" "css_element"
    And ".modal-dialog" "css_element" should be visible
    And I click on ".modal-dialog button.close" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then "<selector>" "css_element" should exist in the ".course_category_tree" "css_element"
    And I click on "<selector>" "css_element" in the ".course_category_tree" "css_element"
    And ".modal-dialog" "css_element" should be visible
    And I click on ".modal-dialog button.close" "css_element"
    And I reload the page
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And "<selector>" "css_element" should exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And I click on "<selector>" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And ".modal-dialog" "css_element" should be visible
    And I click on ".modal-dialog button.close" "css_element"

    Examples:
      | coursevalue | selector                               |
      | cards       | .course-card .card-footer .popupbutton |
      | list        | .course-listitem .popupbutton          |

  @javascript
  Scenario Outline: Setting: Show details popup in the course listing: Check the content: Course summary
    Given the following config values are set as admin:
      | config                    | value | plugin            |
      | courselistingpresentation | cards | theme_boost_union |
      | courselistinghowpopup     | yes   | theme_boost_union |
    And I log in as "admin"
    And I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    And I set the field "Course summary" to "<summarycontent>"
    And I press "Save and display"
    And I log out
    When I log in as "student1"
    And I am on the "CATA" category page
    Then ".course-card .card-footer .popupbutton" "css_element" should exist in the ".course_category_tree" "css_element"
    And I click on ".course-card .card-footer .popupbutton" "css_element"
    And I should see "Course 1" in the ".modal-dialog .modal-header" "css_element"
    And ".theme_boost_union-courselisting-modal .coursesummary" "css_element" should exist
    And I should see "Course summary" in the ".theme_boost_union-courselisting-modal .coursesummary" "css_element"
    And I should see "<summarydisplayed>" in the ".theme_boost_union-courselisting-modal .coursesummary" "css_element"

    Examples:
      | summarycontent      | summarydisplayed                    |
      | This is our summary | This is our summary                 |
      |                     | This course does not have a summary |

  @javascript
  Scenario Outline: Setting: Show details popup in the course listing: Check the content: Course contacts
    Given the following config values are set as admin:
      | config                    | value | plugin            |
      | courselistingpresentation | cards | theme_boost_union |
      | courselistinghowpopup     | yes   | theme_boost_union |
    And the following "users" exist:
      | username | firstname | lastname |
      | teacher1 | John      | Doe      |
      | teacher2 | Jane      | Doe      |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | teacher2 | C1     | editingteacher |
    When I log in as "<loginas>"
    And I am on the "CATA" category page
    Then ".course-card .card-footer .popupbutton" "css_element" should exist in the ".course_category_tree" "css_element"
    And I click on ".course-card .card-footer .popupbutton" "css_element"
    And I should see "Course 1" in the ".modal-dialog .modal-header" "css_element"
    And ".theme_boost_union-courselisting-modal .coursecontacts" "css_element" should exist
    And I should see "Course contact" in the ".theme_boost_union-courselisting-modal .coursecontacts" "css_element"
    And I should see "Jane Doe" in the ".theme_boost_union-courselisting-modal .coursecontacts .contact:nth-of-type(1)" "css_element"
    And I should see "John Doe" in the ".theme_boost_union-courselisting-modal .coursecontacts .contact:nth-of-type(2)" "css_element"
    And ".theme_boost_union-courselisting-modal .coursecontacts .contact .card-footer .btn" "css_element" <shouldornot> exist

    Examples:
      | loginas  | shouldornot |
      | student1 | should not  |
      | admin    | should      |

  @javascript
  Scenario: Setting: Show details popup in the course listing: Check the content: Course classification
    Given the following config values are set as admin:
      | config                    | value | plugin            |
      | courselistingpresentation | cards | theme_boost_union |
      | courselistinghowpopup     | yes   | theme_boost_union |
    And the following "custom field categories" exist:
      | name          | component   | area   | itemid |
      | Fieldcategory | core_course | course | 0      |
    And the following "custom fields" exist:
      | name    | category      | type     | shortname | description | configdata            |
      | Field 1 | Fieldcategory | text     | f1        | d1          |                       |
      | Field 2 | Fieldcategory | select   | f2        | d2          | {"options":"a\nb\nc"} |
    And I log in as "admin"
    And I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Field 1 | test |
      | Field 2 | a    |
    And I press "Save and display"
    And I log out
    When I log in as "student1"
    And I am on the "CATA" category page
    Then ".course-card .card-footer .popupbutton" "css_element" should exist in the ".course_category_tree" "css_element"
    And I click on ".course-card .card-footer .popupbutton" "css_element"
    And I should see "Course 1" in the ".modal-dialog .modal-header" "css_element"
    And ".theme_boost_union-courselisting-modal .customfields" "css_element" should exist
    And I should see "Field 1" in the ".theme_boost_union-courselisting-modal .customfields .customfield.customfield_text .customfieldname" "css_element"
    And I should see "test" in the ".theme_boost_union-courselisting-modal .customfields .customfield.customfield_text .customfieldvalue" "css_element"
    And I should see "Field 2" in the ".theme_boost_union-courselisting-modal .customfields .customfield.customfield_select .customfieldname" "css_element"
    And I should see "a" in the ".theme_boost_union-courselisting-modal .customfields .customfield.customfield_select .customfieldvalue" "css_element"

  @javascript
  Scenario Outline: Setting: Course listing presentation / Category listing presentation: Verify the appearance of the sticky category headers
    Given the following config values are set as admin:
      | config                      | value           | plugin            |
      | courselistingpresentation   | <coursevalue>   | theme_boost_union |
      | categorylistingpresentation | <categoryvalue> | theme_boost_union |
    When I log in as "student1"
    And I am on site homepage
    # Check the 'Combo list' view on site home as a whole (and focus directly on the subcategories)
    And I click on ".info" "css_element" in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    Then ".theme_boost_union-stickycategory" "css_element" <cattreeshouldornot> exist in the "#frontpage-category-combo > .course_category_tree > .content > .subcategories > .category.with_children:nth-child(3) > .content > .subcategories > .category.with_children" "css_element"
    # Check the 'Enrolled courses' view on site home
    And ".theme_boost_union-stickycategory" "css_element" <singlecatshouldornot> exist in the "#frontpage-course-list" "css_element"
    # Check the 'List of courses' view on site home
    And ".theme_boost_union-stickycategory" "css_element" <singlecatshouldornot> exist in the "#frontpage-available-course-list" "css_element"
    # Check the categoriy overview page of a category without subcategories
    And I am on the "CATA" category page
    Then ".theme_boost_union-stickycategory" "css_element" <singlecatshouldornot> exist in the ".course_category_tree" "css_element"
    # Check the categoriy overview page of a category with subcategories
    And I am on the "CATB" category page
    Then ".theme_boost_union-stickycategory" "css_element" <singlecatshouldornot> exist in the ".course_category_tree" "css_element"
    And I click on ".info" "css_element" in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"
    And ".theme_boost_union-stickycategory" "css_element" <cattreeshouldornot> exist in the ".course_category_tree > .content > .subcategories > .category.with_children" "css_element"

    Examples:
      | coursevalue  | categoryvalue | singlecatshouldornot | cattreeshouldornot |
      | nochange     | nochange      | should not           | should not         |
      | nochange     | boxlist       | should not           | should not         |
      | cards        | nochange      | should not           | should not         |
      | list         | nochange      | should not           | should not         |
      | cards        | boxlist       | should not           | should             |
      | list         | boxlist       | should not           | should             |
