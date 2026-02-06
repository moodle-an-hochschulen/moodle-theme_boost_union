@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_course @theme_boost_union_looksettings_course_courseindex
Feature: Configuring the theme_boost_union plugin for the "Course index" section on the "Course" tab on the "Look" page
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
      | Course 2 | C2        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | teacher1 | C2     | editingteacher |
      | student1 | C1     | student        |
      | student1 | C2     | student        |

  @javascript
  Scenario Outline: Setting: Course index - Display activity type icons in course index.
    Given the following config values are set as admin:
      | config                            | value      | plugin            |
      | courseindexmodiconenabled         | <enabled>  | theme_boost_union |
      | courseindexcompletioninfoposition | <position> | theme_boost_union |
    And the following "courses" exist:
      | fullname    | shortname | enablecompletion |
      | Courseindex | CI        | 1                |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | CI     | editingteacher |
      | student1 | CI     | student        |
    And the following "activities" exist:
      | activity | name                               | intro                       | course | idnumber | section | completion |
      | assign   | Test assignment without completion | Test assignment description | CI     | assign1  | 0       | 0          |
      | assign   | Test assignment incomplete         | Test assignment description | CI     | assign2  | 0       | 1          |
      | assign   | Test assignment complete           | Test assignment description | CI     | assign3  | 0       | 1          |
    When I log in as "student1"
    And I am on "Courseindex" course homepage with editing mode off
    And the manual completion button of "Test assignment incomplete" is displayed as "Mark as done"
    And the manual completion button of "Test assignment complete" is displayed as "Mark as done"
    And I toggle the manual completion state of "Test assignment complete"
    And the manual completion button of "Test assignment complete" is displayed as "Done"
    # Check the body tags
    Then the "class" attribute of "body" "css_element" <hascourseindexcmicons> contain "hascourseindexcmicons"
    And the "class" attribute of "body" "css_element" <hascourseindexcplicon> contain "hascourseindexcplicon"
    And the "class" attribute of "body" "css_element" <hascourseindexcplsol> contain "hascourseindexcplsol"
    And the "class" attribute of "body" "css_element" <hascourseindexcpleol> contain "hascourseindexcpleol"
    # Check the visibility of the activity icon
    And "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) .courseindex-cmicon-container" "css_element" <iconvisible> be visible
    # Check the completion data of the completion indicator as icon color
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) .courseindex-cmicon-container .completioninfo" "css_element" should contain "NaN"
    And the "data-for" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) .courseindex-cmicon-container .completioninfo" "css_element" <iconshouldornot> contain "cm_completion"
    And the "class" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) .courseindex-cmicon-container" "css_element" should not contain "courseindex-cmicon-cpl-complete"
    And the "class" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) .courseindex-cmicon-container" "css_element" should not contain "courseindex-cmicon-cpl-incomplete"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(2) .courseindex-cmicon-container .completioninfo" "css_element" <iconshouldornot> contain "0"
    And the "data-for" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(2) .courseindex-cmicon-container .completioninfo" "css_element" <iconshouldornot> contain "cm_completion"
    And the "class" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(2) .courseindex-cmicon-container" "css_element" <iconshouldornot> contain "courseindex-cmicon-cpl-incomplete"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(3) .courseindex-cmicon-container .completioninfo" "css_element" <iconshouldornot> contain "1"
    And the "data-for" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(3) .courseindex-cmicon-container .completioninfo" "css_element" <iconshouldornot> contain "cm_completion"
    And the "class" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(3) .courseindex-cmicon-container" "css_element" <iconshouldornot> contain "courseindex-cmicon-cpl-complete"
    # Check the completion data of the standard completion indicator at the start of the line
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) > .completioninfo" "css_element" should contain "NaN"
    And the "data-for" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) > .completioninfo" "css_element" <solshouldornot> contain "cm_completion"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(2) > .completioninfo" "css_element" <solshouldornot> contain "0"
    And the "data-for" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(2) > .completioninfo" "css_element" <solshouldornot> contain "cm_completion"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(3) > .completioninfo" "css_element" <solshouldornot> contain "1"
    And the "data-for" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(3) > .completioninfo" "css_element" <solshouldornot> contain "cm_completion"
    # Check the completion data of the completion indicator at the end of the line
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) > .ms-auto > .completioninfo" "css_element" should contain "NaN"
    And the "data-for" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) > .ms-auto > .completioninfo" "css_element" <eolshouldornot> contain "cm_completion"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(2) > .ms-auto > .completioninfo" "css_element" <eolshouldornot> contain "0"
    And the "data-for" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(2) > .ms-auto > .completioninfo" "css_element" <eolshouldornot> contain "cm_completion"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(3) > .ms-auto > .completioninfo" "css_element" <eolshouldornot> contain "1"
    And the "data-for" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(3) > .ms-auto > .completioninfo" "css_element" <eolshouldornot> contain "cm_completion"
    And I log out
    And I log in as "teacher1"
    And I am on "Courseindex" course homepage with editing mode on
    Then the "class" attribute of "body" "css_element" <hascourseindexcmicons> contain "hascourseindexcmicons"
    And the "class" attribute of "body" "css_element" <hascourseindexcplicon> contain "hascourseindexcplicon"
    And the "class" attribute of "body" "css_element" <hascourseindexcplsol> contain "hascourseindexcplsol"
    And the "class" attribute of "body" "css_element" <hascourseindexcpleol> contain "hascourseindexcpleol"
    # Check the visibility of the activity icon
    And "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) .courseindex-cmicon-container" "css_element" <iconvisible> be visible
    # Verify that all completion indicators, regardless of the position, are disabled
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) .courseindex-cmicon-container .completioninfo" "css_element" should contain "NaN"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(2) .courseindex-cmicon-container .completioninfo" "css_element" should contain "NaN"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(3) .courseindex-cmicon-container .completioninfo" "css_element" should contain "NaN"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) > .completioninfo" "css_element" should contain "NaN"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(2) > .completioninfo" "css_element" should contain "NaN"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(3) > .completioninfo" "css_element" should contain "NaN"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) > .ms-auto > .completioninfo" "css_element" should contain "NaN"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(2) > .ms-auto > .completioninfo" "css_element" should contain "NaN"
    And the "data-value" attribute of "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(3) > .ms-auto > .completioninfo" "css_element" should contain "NaN"

    Examples:
      | enabled | position    | hascourseindexcmicons | hascourseindexcplicon | hascourseindexcplsol | hascourseindexcpleol | iconvisible | iconshouldornot | solshouldornot | eolshouldornot |
      | no      | endofline   | should not            | should not            | should not           | should not           | should not  | should not      | should         | should not     |
      | yes     | endofline   | should                | should not            | should not           | should               | should      | should not      | should not     | should         |
      | yes     | startofline | should                | should not            | should               | should not           | should      | should not      | should         | should not     |
      | yes     | iconcolor   | should                | should                | should not           | should not           | should      | should          | should not     | should not     |

  @javascript
  Scenario Outline: Setting: Course index - Display activity type icons in subsections in the course index as well.
    Given the following config values are set as admin:
      | config                            | value      | plugin            |
      | courseindexmodiconenabled         | <enabled>  | theme_boost_union |
      | courseindexcompletioninfoposition | <position> | theme_boost_union |
    And I enable "subsection" "mod" plugin
    And the following "courses" exist:
      | fullname    | shortname | enablecompletion | numsections   | initsections  |
      | Courseindex | CI        | 1                | 3             | 1             |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | student1 | CI     | student        |
    And the following "activities" exist:
      | activity   | name           		| course    | idnumber | section | completion |
      | subsection | Subsection1     		| CI        | sub1     | 1       |            |
      | page       | Page in Subsection | CI        | page11   | 4       | 1          |
    When I log in as "student1"
    And I am on "Courseindex" course homepage with editing mode off
    And the manual completion button of "Page in Subsection" is displayed as "Mark as done"
    And I toggle the manual completion state of "Page in Subsection"
    And the manual completion button of "Page in Subsection" is displayed as "Done"
    # Check the visibility of the activity icon
    And "#courseindex .courseindex-item-content .courseindex-item:nth-of-type(1) .courseindex-cmicon-container" "css_element" <iconvisible> be visible
    # Check the completion data of the completion indicator at the end of the line
    # We just check this option to make sure that a completion indicator is there.
    # We do not test all available options for subsections again as this has been tested in the previous scenario and the PHP / Mustache code is the same.
    And the "data-value" attribute of "#courseindex .delegated-section .courseindex-item-content .courseindex-item:nth-of-type(1) > .ms-auto > .completioninfo" "css_element" <eolshouldornot> contain "1"
    And the "data-for" attribute of "#courseindex .delegated-section .courseindex-item-content .courseindex-item:nth-of-type(1) > .ms-auto > .completioninfo" "css_element" <eolshouldornot> contain "cm_completion"

    Examples:
      | enabled | position    | iconvisible | eolshouldornot |
      | no      | endofline   | should not  | should not     |
      | yes     | endofline   | should      | should         |
