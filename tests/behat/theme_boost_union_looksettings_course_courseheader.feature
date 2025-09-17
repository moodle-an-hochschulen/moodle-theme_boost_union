@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_course @theme_boost_union_looksettings_course_courseheader
Feature: Configuring the theme_boost_union plugin for the "Course header" section on the "Course" tab on the "Look" page
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

  Scenario: Setting: Course header - Do not display the course header if course headers are disabled regardless if a course image is uploaded in the course.
    Given the following config values are set as admin:
      | config              | value | plugin            |
      | courseheaderenabled | no    | theme_boost_union |
    And the following "theme_boost_union > course overview file" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should not exist

  Scenario: Setting: Course header - Display the course header for the courseplusglobal image source variant
    Given the following config values are set as admin:
      | config                  | value            | plugin            |
      | courseheaderenabled     | yes              | theme_boost_union |
      | courseheaderimagesource | courseplusglobal | theme_boost_union |
    # First, do not upload any image at all
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should not exist
    # Next, upload a course image to course 1 and check that it is used
    And the following "theme_boost_union > course overview file" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg2.png')]" "xpath_element" should exist
    # Next, upload a global image and check that it is used in course 2 (where no course image is uploaded up to now)
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And I am on "Course 2" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/theme_boost_union/courseheaderimageglobal/0/login_bg1.png')]" "xpath_element" should exist
    # Finally upload a course image to course 2 as well and check that it is used instead of the global image
    And the following "theme_boost_union > course overview file" exists:
      | course   | C2                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    And I am on "Course 2" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg2.png')]" "xpath_element" should exist

  Scenario: Setting: Course header - Display the course header for the coursenoglobal image source variant
    Given the following config values are set as admin:
      | config                  | value          | plugin            |
      | courseheaderenabled     | yes            | theme_boost_union |
      | courseheaderimagesource | coursenoglobal | theme_boost_union |
    # First, do not upload any image at all
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should not exist
    # Next, upload a course image to course 1 and check that it is used
    And the following "theme_boost_union > course overview file" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg2.png')]" "xpath_element" should exist

  Scenario: Setting: Course header - Display the course header for the dedicatedplusglobal image source variant
    Given the following config values are set as admin:
      | config                  | value               | plugin            |
      | courseheaderenabled     | yes                 | theme_boost_union |
      | courseheaderimagesource | dedicatedplusglobal | theme_boost_union |
    # First, do not upload any image at all
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should not exist
    # Next, upload a course image to course 1 and check that it is not used
    And the following "theme_boost_union > course overview file" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should not exist
    # Next, upload a dedicated course header image to course 1 and check that it is used
    And the following "theme_boost_union > course header image" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg3.png |
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/theme_boost_union/courseheaderimage/0/login_bg3.png')]" "xpath_element" should exist
    # Next, upload a global image and check that it is used in course 2 (where no dedicated course header image is uploaded up to now)
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And I am on "Course 2" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/theme_boost_union/courseheaderimageglobal/0/login_bg1.png')]" "xpath_element" should exist
    # Finally upload a dedicated course header image to course 2 as well and check that it is used instead of the global image
    And the following "theme_boost_union > course header image" exists:
      | course   | C2                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg3.png |
    And I am on "Course 2" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/theme_boost_union/courseheaderimage/0/login_bg3.png')]" "xpath_element" should exist

  Scenario: Setting: Course header - Display the course header for the dedicatednoglobal image source variant
    Given the following config values are set as admin:
      | config                  | value             | plugin            |
      | courseheaderenabled     | yes               | theme_boost_union |
      | courseheaderimagesource | dedicatednoglobal | theme_boost_union |
    # First, do not upload any image at all
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should not exist
    # Next, upload a course image to course 1 and check that it is not used
    And the following "theme_boost_union > course overview file" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should not exist
    # Next, upload a dedicated course header image to course 1 and check that it is used
    And the following "theme_boost_union > course header image" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg3.png |
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/theme_boost_union/courseheaderimage/0/login_bg3.png')]" "xpath_element" should exist

  Scenario: Setting: Course header - Display the course header for the global image source variant
    Given the following config values are set as admin:
      | config                  | value  | plugin            |
      | courseheaderenabled     | yes    | theme_boost_union |
      | courseheaderimagesource | global | theme_boost_union |
    # First, do not upload any image at all
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should not exist
    # Next, upload a course image to course 1 and check that it is not used
    And the following "theme_boost_union > course overview file" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should not exist
    # Next, upload a global image and check that it is used
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/theme_boost_union/courseheaderimageglobal/0/login_bg1.png')]" "xpath_element" should exist

  Scenario Outline: Setting: Course header - Display the course header with the image of the current course (and not another course by mistake)
    Given the following config values are set as admin:
      | config                  | value    | plugin            |
      | courseheaderenabled     | yes      | theme_boost_union |
      | courseheaderimagesource | <source> | theme_boost_union |
    And the following "<generator>" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And the following "<generator>" exists:
      | course   | C2                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/<fileaareatech>/login_bg1.png')]" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/<fileaareatech>/login_bg2.png')]" "xpath_element" should not exist
    And I am on "Course 2" course homepage
    And "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/<fileaareatech>/login_bg1.png')]" "xpath_element" should not exist
    And "//div[@id='courseheaderimage' and contains(@style, '/<fileaareatech>/login_bg2.png')]" "xpath_element" should exist

    Examples:
      | source              | generator                                | fileaareatech                         |
      | courseplusglobal    | theme_boost_union > course overview file | course/overviewfiles                  |
      | coursenoglobal      | theme_boost_union > course overview file | course/overviewfiles                  |
      | dedicatedplusglobal | theme_boost_union > course header image  | theme_boost_union/courseheaderimage/0 |
      | dedicatednoglobal   | theme_boost_union > course header image  | theme_boost_union/courseheaderimage/0 |

  @javascript @_file_upload
  Scenario: Setting: Course header - Upload a dedicated course header image during course creation (which has to be tested separately as it has its own code path)
    Given the following config values are set as admin:
      | config                  | value             | plugin            |
      | courseheaderenabled     | yes               | theme_boost_union |
      | courseheaderimagesource | dedicatednoglobal | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Courses > Add a new course" in site administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Course full name  | Course 3 |
      | Course short name | C3       |
    And I upload "theme/boost_union/tests/fixtures/login_bg3.png" file to "Course header image" filemanager
    And I press "Save and display"
    And I am on "Course 3" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/theme_boost_union/courseheaderimage/0/login_bg3.png')]" "xpath_element" should exist

  Scenario Outline: Setting: Course header - Define the course header layout (globally).
    Given the following config values are set as admin:
      | config                  | value    | plugin            |
      | courseheaderenabled     | yes      | theme_boost_union |
      | courseheaderimagesource | global   | theme_boost_union |
      | courseheaderlayout      | <layout> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "<elementshouldexist>" "css_element" should exist in the "#page-header" "css_element"
    And "<elementshouldnotexist1>" "css_element" should not exist in the "#page-header" "css_element"
    And "<elementshouldnotexist2>" "css_element" should not exist in the "#page-header" "css_element"

    Examples:
      | layout       | elementshouldexist           | elementshouldnotexist1       | elementshouldnotexist2       |
      | stackeddark  | #bucourseheader.stackeddark  | #bucourseheader.headingabove | #bucourseheader.stackedlight |
      | stackedlight | #bucourseheader.stackedlight | #bucourseheader.headingabove | #bucourseheader.stackeddark  |
      | headingabove | #bucourseheader.headingabove | #bucourseheader.stackeddark  | #bucourseheader.stackedlight |

  Scenario: Setting: Course header - Define the course header layout (per course).
    Given the following config values are set as admin:
      | config                            | value        | plugin            |
      | courseheaderenabled               | yes          | theme_boost_union |
      | courseheaderimagesource           | global       | theme_boost_union |
      | courseheaderlayout                | headingabove | theme_boost_union |
      | courseheaderlayout_courseoverride | 0            | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I should not see "Course header layout"
    And the following config values are set as admin:
      | config                            | value | plugin            |
      | courseheaderlayout_courseoverride | 1     | theme_boost_union |
    And I reload the page
    And I should see "Course header layout" in the "#id_theme_boost_union_course_courseheaderhdr" "css_element"
    And I set the field "Course header layout" to "stackedlight"
    And I press "Save and display"
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "#bucourseheader.stackedlight" "css_element" should exist in the "#page-header" "css_element"
    And "#bucourseheader.headingabove" "css_element" should not exist in the "#page-header" "css_element"

  Scenario Outline: Setting: Course header - Define the course header (min-)height (globally).
    Given the following config values are set as admin:
      | config                  | value    | plugin            |
      | courseheaderenabled     | yes      | theme_boost_union |
      | courseheaderimagesource | global   | theme_boost_union |
      | courseheaderheight      | <height> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'min-height: <height>')]" "xpath_element" should exist

    # We do not want to burn too much CPU time by testing all available options. We just test the default value and one non-default value.
    Examples:
      | height |
      | 150px  |
      | 250px  |

  Scenario: Setting: Course header - Define the course header (min-)height (per course).
    Given the following config values are set as admin:
      | config                            | value  | plugin            |
      | courseheaderenabled               | yes    | theme_boost_union |
      | courseheaderimagesource           | global | theme_boost_union |
      | courseheaderheight                | 150px  | theme_boost_union |
      | courseheaderheight_courseoverride | 0      | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I should not see "Course header height"
    And the following config values are set as admin:
      | config                            | value | plugin            |
      | courseheaderheight_courseoverride | 1     | theme_boost_union |
    And I reload the page
    And I should see "Course header height" in the "#id_theme_boost_union_course_courseheaderhdr" "css_element"
    And I set the field "Course header height" to "250px"
    And I press "Save and display"
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'min-height: 250px')]" "xpath_element" should exist

  Scenario Outline: Setting: Course header - Define the course header image position (globally).
    Given the following config values are set as admin:
      | config                    | value      | plugin            |
      | courseheaderenabled       | yes        | theme_boost_union |
      | courseheaderimagesource   | global     | theme_boost_union |
      | courseheaderimageposition | <position> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'background-position: <position>')]" "xpath_element" should exist

    # We do not want to burn too much CPU time by testing all available options. We just test the default value and one non-default value.
    Examples:
      | position      |
      | center center |
      | left top      |

  Scenario: Setting: Course header - Define the course header image position (per course).
    Given the following config values are set as admin:
      | config                                   | value         | plugin            |
      | courseheaderenabled                      | yes           | theme_boost_union |
      | courseheaderimagesource                  | global        | theme_boost_union |
      | courseheaderimageposition                | center center | theme_boost_union |
      | courseheaderimageposition_courseoverride | 0             | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I should not see "Course header image position"
    And the following config values are set as admin:
      | config                                   | value | plugin            |
      | courseheaderimageposition_courseoverride | 1     | theme_boost_union |
    And I reload the page
    And I should see "Course header image position" in the "#id_theme_boost_union_course_courseheaderhdr" "css_element"
    And I set the field "Course header image position" to "left top"
    And I press "Save and display"
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'background-position: left top')]" "xpath_element" should exist

  Scenario: Setting: Course header - Enable, disable and re-enable course overrides
    Given the following config values are set as admin:
      | config                            | value  | plugin            |
      | courseheaderenabled               | yes    | theme_boost_union |
      | courseheaderimagesource           | global | theme_boost_union |
      | courseheaderheight                | 150px  | theme_boost_union |
      | courseheaderheight_courseoverride | 0      | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'min-height: 150px')]" "xpath_element" should exist
    And the following config values are set as admin:
      | config                            | value | plugin            |
      | courseheaderheight_courseoverride | 1     | theme_boost_union |
    And I click on "Settings" "link"
    And I set the field "Course header height" to "250px"
    And I press "Save and display"
    And I am on "Course 1" course homepage
    And "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'min-height: 250px')]" "xpath_element" should exist
    And the following config values are set as admin:
      | config                            | value | plugin            |
      | courseheaderheight_courseoverride | 0     | theme_boost_union |
    And I click on "Settings" "link"
    And I should not see "Course header height"
    And I am on "Course 1" course homepage
    And "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'min-height: 150px')]" "xpath_element" should exist

  Scenario Outline: Setting: Course header - Show the "course header image" filemanager and move the "course image" filemanager as needed
    Given the following config values are set as admin:
      | config                  | value    | plugin            |
      | courseheaderenabled     | yes      | theme_boost_union |
      | courseheaderimagesource | <source> | theme_boost_union |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    Then "#id_theme_boost_union_course_courseimageshdr" "css_element" <coursehheaderheader> exist
    And "#id_theme_boost_union_course_courseimageshdr #fitem_id_theme_boost_union_courseheaderimage_filemanager" "css_element" <courseheaderfilemanager> exist
    And "<courseimagefilemanagerplacement> #id_overviewfiles_filemanager_label" "css_element" should exist

    Examples:
      | source              | coursehheaderheader | courseheaderfilemanager | courseimagefilemanagerplacement              |
      | courseplusglobal    | should not          | should not              | #id_descriptionhdr                           |
      | coursenoglobal      | should not          | should not              | #id_descriptionhdr                           |
      | dedicatedplusglobal | should              | should                  | #id_theme_boost_union_course_courseimageshdr |
      | dedicatednoglobal   | should              | should                  | #id_theme_boost_union_course_courseimageshdr |
      | global              | should not          | should not              | #id_descriptionhdr                           |

  Scenario Outline: Setting: Course header - Control the course override settings via capability when editing courses
    Given the following config values are set as admin:
      | config                                   | value | plugin            |
      | courseheaderenabled_courseoverride       | 1     | theme_boost_union |
      | courseheaderlayout_courseoverride        | 1     | theme_boost_union |
      | courseheaderheight_courseoverride        | 1     | theme_boost_union |
      | courseheaderimageposition_courseoverride | 1     | theme_boost_union |
    And the following "permission overrides" exist:
      | capability                                     | permission   | role           | contextlevel | reference |
      | theme/boost_union:overridecourseheaderincourse | <permission> | editingteacher | Course       | C1        |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    Then "#fitem_id_theme_boost_union_courseheaderenabled" "css_element" <shouldornot> exist
    And "#fitem_id_theme_boost_union_courseheaderlayout" "css_element" <shouldornot> exist
    And "#fitem_id_theme_boost_union_courseheaderheight" "css_element" <shouldornot> exist
    And "#fitem_id_theme_boost_union_courseheaderimageposition" "css_element" <shouldornot> exist

    Examples:
      | permission | shouldornot |
      | Prevent    | should not  |
      | Allow      | should      |

  Scenario Outline: Setting: Course header - Control the course override settings via capability when creating courses
    Given the following config values are set as admin:
      | config                                   | value    | plugin            |
      | courseheaderenabled_courseoverride       | 1        | theme_boost_union |
      | courseheaderlayout_courseoverride        | 1        | theme_boost_union |
      | courseheaderheight_courseoverride        | 1        | theme_boost_union |
      | courseheaderimageposition_courseoverride | 1        | theme_boost_union |
    And the following "users" exist:
      | username |
      | manager  |
    And the following "system role assigns" exist:
      | user    | role    | contextlevel |
      | manager | manager | System       |
    And the following "permission overrides" exist:
      | capability                                     | permission   | role    | contextlevel | reference |
      | theme/boost_union:overridecourseheaderincourse | <permission> | manager | System       |           |
    When I log in as "manager"
    And I navigate to "Courses > Add a new course" in site administration
    Then "#fitem_id_theme_boost_union_courseheaderenabled" "css_element" <shouldornot> exist
    And "#fitem_id_theme_boost_union_courseheaderlayout" "css_element" <shouldornot> exist
    And "#fitem_id_theme_boost_union_courseheaderheight" "css_element" <shouldornot> exist
    And "#fitem_id_theme_boost_union_courseheaderimageposition" "css_element" <shouldornot> exist

    Examples:
      | permission | shouldornot |
      | Prevent    | should not  |
      | Allow      | should      |

  Scenario: Setting: Course header - Course format exclusion list excludes selected formats from being processed on course page rendering
    Given the following config values are set as admin:
      | config                          | value        | plugin            |
      | courseheaderenabled             | yes          | theme_boost_union |
      | courseheaderimagesource         | global       | theme_boost_union |
      | courseheaderformatexclusionlist | social,weeks | theme_boost_union |
    And the following "courses" exist:
      | fullname      | shortname | format |
      | Topics Course | TC        | topics |
      | Social Course | SC        | social |
    And the following "course enrolments" exist:
      | user     | course | role    |
      | student1 | TC     | student |
      | student1 | SC     | student |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "student1"
    And I am on "Topics Course" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And I am on "Social Course" course homepage
    And "//div[@id='courseheaderimage']" "xpath_element" should not exist

  @javascript
  Scenario Outline: Setting: Course header - Course format exclusion list excludes selected formats from being processed when editing courses
    Given the following config values are set as admin:
      | config                             | value        | plugin            |
      | courseheaderenabled                | yes          | theme_boost_union |
      | courseheaderformatexclusionlist    | social,weeks | theme_boost_union |
      | courseheaderenabled_courseoverride | 1            | theme_boost_union |
    And the following "courses" exist:
      | fullname | shortname | format   |
      | Course 3 | C3        | <format> |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C3     | editingteacher |
    # First try an existing course
    When I log in as "teacher1"
    And I am on "Course 3" course homepage
    And I click on "Settings" "link"
    And I expand all fieldsets
    Then "#fitem_id_theme_boost_union_courseheaderenabled" "css_element" <shouldornot> exist
    # Then change the course format to a format which is excluded
    And I set the field "Format" to "weeks"
    Then "#fitem_id_theme_boost_union_courseheaderenabled" "css_element" should not exist
    # Then change the course format to a format which is not excluded
    And I expand all fieldsets
    And I set the field "Format" to "topics"
    Then "#fitem_id_theme_boost_union_courseheaderenabled" "css_element" should exist

    Examples:
      | format | shouldornot |
      | topics | should      |
      | weeks  | should not  |

  Scenario Outline: Setting: Course header - Course format exclusion list excludes selected formats from being processed when creating courses
    Given the following config values are set as admin:
      | config                             | value        | plugin            |
      | courseheaderenabled                | yes          | theme_boost_union |
      | courseheaderformatexclusionlist    | social,weeks | theme_boost_union |
      | courseheaderenabled_courseoverride | 1            | theme_boost_union |
      | format                             | <format>     | moodlecourse      |
    When I log in as "admin"
    And I navigate to "Courses > Add a new course" in site administration
    Then "#fitem_id_theme_boost_union_courseheaderenabled" "css_element" <shouldornot> exist

    Examples:
      | format | shouldornot |
      | topics | should      |
      | weeks  | should not  |

  Scenario Outline: Setting: Course header - Course header layouts exclusion list excludes selected layouts from being offered
    Given the following config values are set as admin:
      | config                            | value        | plugin            |
      | courseheaderenabled               | yes          | theme_boost_union |
      | courseheaderlayout                | headingabove | theme_boost_union |
      | courseheaderlayoutexclusionlist   | <layout>     | theme_boost_union |
      | courseheaderlayout_courseoverride | 1            | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 2" course homepage
    And I click on "Settings" "link"
    Then the "Course header layout" select box <stackeddarkshouldornot> contain "Course title stacked on course header image (white font color for dark background images)"
    And the "Course header layout" select box <stackedlightshouldornot> contain "Course title stacked on course header image (black font color for light background images)"
    And the "Course header layout" select box <headingaboveshouldornot> contain "Course title above of course header image"

    Examples:
      | layout                   | stackeddarkshouldornot | stackedlightshouldornot | headingaboveshouldornot |
      | stackeddark,stackedlight | should not             | should not              | should                  |
      | stackeddark              | should not             | should                  | should                  |
      # The last case is a special one: if only the currently set layout is excluded, it should still be shown.
      | headingabove             | should                 | should                  | should                  |

  Scenario: Setting: Course header - Course header layouts exclusion list excludes selected layouts from being used
    Given the following config values are set as admin:
      | config                            | value        | plugin            |
      | courseheaderenabled               | yes          | theme_boost_union |
      | courseheaderlayout                | headingabove | theme_boost_union |
      | courseheaderlayout_courseoverride | 1            | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I expand all fieldsets
    And I set the field "Course header layout" to "stackedlight"
    And I press "Save and display"
    And "#bucourseheader.stackedlight" "css_element" should exist in the "#page-header" "css_element"
    And "#bucourseheader.headingabove" "css_element" should not exist in the "#page-header" "css_element"
    And the following config values are set as admin:
      | config                          | value        | plugin            |
      | courseheaderlayoutexclusionlist | stackedlight | theme_boost_union |
    And I reload the page
    And "#bucourseheader.stackedlight" "css_element" should not exist in the "#page-header" "css_element"
    And "#bucourseheader.headingabove" "css_element" should exist in the "#page-header" "css_element"
