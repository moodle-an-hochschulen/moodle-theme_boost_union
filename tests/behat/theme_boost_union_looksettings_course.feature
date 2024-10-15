@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_course
Feature: Configuring the theme_boost_union plugin for the "Course" tab on the "Look" page
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

  @javascript @_file_upload
  Scenario Outline: Setting: Course header image - Display the course header image with the course image if course header images are enabled and an image is uploaded in the course.
    Given the following config values are set as admin:
      | config                   | value | plugin            |
      | courseheaderimageenabled | yes   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Course image" filemanager
    And I press "Save and display"
    And I am on site homepage
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "<role>"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg1.png')]" "xpath_element" should exist

    Examples:
      | role      |
      | student1  |
      | teacher1  |

  @javascript @_file_upload
  Scenario Outline: Setting: Course header image - Do not display the course header image if course header images are enabled but an image is not uploaded in the course.
    Given the following config values are set as admin:
      | config                   | value | plugin            |
      | courseheaderimageenabled | yes   | theme_boost_union |
    When I log in as "<role>"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should not exist

    Examples:
      | role      |
      | student1  |
      | teacher1  |

  @javascript @_file_upload
  Scenario Outline: Setting: Course header image - Do not display the course header image if course header images are disabled regardless if an image is uploaded in the course.
    Given the following config values are set as admin:
      | config                   | value | plugin            |
      | courseheaderimageenabled | no    | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Course image" filemanager
    And I press "Save and display"
    And I am on site homepage
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "<role>"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should not exist

    Examples:
      | role      |
      | student1  |
      | teacher1  |

  @javascript @_file_upload
  Scenario Outline: Setting: Course header image - Display the course header image with the fallback image if course header images are enabled but an image is not uploaded in the course.
    Given the following config values are set as admin:
      | config                   | value | plugin            |
      | courseheaderimageenabled | yes   | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Course" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg2.png" file to "Fallback course header image" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on site homepage
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "<role>"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '1/theme_boost_union/courseheaderimagefallback/0/login_bg2.png')]" "xpath_element" should exist

    Examples:
      | role      |
      | student1  |
      | teacher1  |

  @javascript @_file_upload
  Scenario Outline: Setting: Course header image - Display the course header image with the fallback image until a course image is uploaded.
    Given the following config values are set as admin:
      | config                   | value | plugin            |
      | courseheaderimageenabled | yes   | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Course" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg2.png" file to "Fallback course header image" filemanager
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on site homepage
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '1/theme_boost_union/courseheaderimagefallback/0/login_bg2.png')]" "xpath_element" should exist
    And I click on "Settings" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Course image" filemanager
    And I press "Save and display"
    And I am on site homepage
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "<role>"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '1/theme_boost_union/courseheaderimagefallback/0/login_bg2.png')]" "xpath_element" should not exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg1.png')]" "xpath_element" should exist

    Examples:
      | role      |
      | student1  |
      | teacher1  |

  @javascript @_file_upload
  Scenario Outline: Setting: Course header image - Display the course header image with the image of the current course.
    Given the following config values are set as admin:
      | config                   | value | plugin            |
      | courseheaderimageenabled | yes   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Course image" filemanager
    And I press "Save and display"
    And I am on "Course 2" course homepage
    And I click on "Settings" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg2.png" file to "Course image" filemanager
    And I press "Save and display"
    And I am on site homepage
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "<role>"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg1.png')]" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg2.png')]" "xpath_element" should not exist
    And I am on "Course 2" course homepage
    And "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg1.png')]" "xpath_element" should not exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg2.png')]" "xpath_element" should exist

    Examples:
      | role      |
      | student1  |
      | teacher1  |

  @javascript @_file_upload
  Scenario Outline: Setting: Course header image - Define the course header image (min-)height.
    Given the following config values are set as admin:
      | config                   | value    | plugin            |
      | courseheaderimageenabled | yes      | theme_boost_union |
      | courseheaderimageheight  | <height> | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Course image" filemanager
    And I press "Save and display"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'min-height: <height>')]" "xpath_element" should exist

    # We do not want to burn too much CPU time by testing all available options. We just test the default value and one non-default value.
    Examples:
      | height |
      | 150px  |
      | 250px  |

  @javascript @_file_upload
  Scenario Outline: Setting: Course header image - Define the course header image position.
    Given the following config values are set as admin:
      | config                    | value      | plugin            |
      | courseheaderimageenabled  | yes        | theme_boost_union |
      | courseheaderimageposition | <position> | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Course image" filemanager
    And I press "Save and display"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'background-position: <position>')]" "xpath_element" should exist

    # We do not want to burn too much CPU time by testing all available options. We just test the default value and one non-default value.
    Examples:
      | position      |
      | center center |
      | left top      |

  @javascript @_file_upload
  Scenario Outline: Setting: Course header image - Define the course header image layout.
    Given the following config values are set as admin:
      | config                   | value    | plugin            |
      | courseheaderimageenabled | yes      | theme_boost_union |
      | courseheaderimagelayout  | <layout> | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Course image" filemanager
    And I press "Save and display"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "<elementshouldexist>" "css_element" should exist in the "#page-header" "css_element"
    And "<elementshouldnotexist1>" "css_element" should not exist in the "#page-header" "css_element"
    And "<elementshouldnotexist2>" "css_element" should not exist in the "#page-header" "css_element"

    Examples:
      | layout       | elementshouldexist                                 | elementshouldnotexist1                             | elementshouldnotexist2                     |
      | stackeddark  | #courseheaderimage.courseheaderimage-dark          | div.d-flex.align-items-center + #courseheaderimage | #courseheaderimage.courseheaderimage-light |
      | stackedlight | #courseheaderimage.courseheaderimage-light         | div.d-flex.align-items-center + #courseheaderimage | #courseheaderimage.courseheaderimage-dark  |
      | headingabove | div.d-flex.align-items-center + #courseheaderimage | #courseheaderimage.courseheaderimage-dark          | #courseheaderimage.courseheaderimage-light |

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
