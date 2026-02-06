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
      | fullname | shortname | enablecompletion | showcompletionconditions |
      | Course 1 | C1        | 1                | 1                        |
      | Course 2 | C2        | 1                | 1                        |
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
    Then "#courseheaderimage" "css_element" should not exist

  Scenario: Setting: Course header - Display the course header for the courseplusglobal image source variant
    Given the following config values are set as admin:
      | config                  | value            | plugin            |
      | courseheaderenabled     | yes              | theme_boost_union |
      | courseheaderimagesource | courseplusglobal | theme_boost_union |
    # First, do not upload any image at all
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should not exist
    # Next, upload a course image to course 1 and check that it is used
    And the following "theme_boost_union > course overview file" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg2.png')]" "xpath_element" should exist
    # Next, upload a global image and check that it is used in course 2 (where no course image is uploaded up to now)
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And I am on "Course 2" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/theme_boost_union/courseheaderimageglobal/0/login_bg1.png')]" "xpath_element" should exist
    # Finally upload a course image to course 2 as well and check that it is used instead of the global image
    And the following "theme_boost_union > course overview file" exists:
      | course   | C2                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    And I am on "Course 2" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg2.png')]" "xpath_element" should exist

  Scenario: Setting: Course header - Display the course header for the coursenoglobal image source variant
    Given the following config values are set as admin:
      | config                  | value          | plugin            |
      | courseheaderenabled     | yes            | theme_boost_union |
      | courseheaderimagesource | coursenoglobal | theme_boost_union |
    # First, do not upload any image at all
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should not exist
    # Next, upload a course image to course 1 and check that it is used
    And the following "theme_boost_union > course overview file" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg2.png')]" "xpath_element" should exist

  Scenario: Setting: Course header - Display the course header for the dedicatedplusglobal image source variant
    Given the following config values are set as admin:
      | config                  | value               | plugin            |
      | courseheaderenabled     | yes                 | theme_boost_union |
      | courseheaderimagesource | dedicatedplusglobal | theme_boost_union |
    # First, do not upload any image at all
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should not exist
    # Next, upload a course image to course 1 and check that it is not used
    And the following "theme_boost_union > course overview file" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should not exist
    # Next, upload a dedicated course header image to course 1 and check that it is used
    And the following "theme_boost_union > course header image" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg3.png |
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/theme_boost_union/courseheaderimage/0/login_bg3.png')]" "xpath_element" should exist
    # Next, upload a global image and check that it is used in course 2 (where no dedicated course header image is uploaded up to now)
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And I am on "Course 2" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/theme_boost_union/courseheaderimageglobal/0/login_bg1.png')]" "xpath_element" should exist
    # Finally upload a dedicated course header image to course 2 as well and check that it is used instead of the global image
    And the following "theme_boost_union > course header image" exists:
      | course   | C2                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg3.png |
    And I am on "Course 2" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/theme_boost_union/courseheaderimage/0/login_bg3.png')]" "xpath_element" should exist

  Scenario: Setting: Course header - Display the course header for the dedicatednoglobal image source variant
    Given the following config values are set as admin:
      | config                  | value             | plugin            |
      | courseheaderenabled     | yes               | theme_boost_union |
      | courseheaderimagesource | dedicatednoglobal | theme_boost_union |
    # First, do not upload any image at all
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should not exist
    # Next, upload a course image to course 1 and check that it is not used
    And the following "theme_boost_union > course overview file" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should not exist
    # Next, upload a dedicated course header image to course 1 and check that it is used
    And the following "theme_boost_union > course header image" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg3.png |
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/theme_boost_union/courseheaderimage/0/login_bg3.png')]" "xpath_element" should exist

  Scenario: Setting: Course header - Display the course header for the global image source variant
    Given the following config values are set as admin:
      | config                  | value  | plugin            |
      | courseheaderenabled     | yes    | theme_boost_union |
      | courseheaderimagesource | global | theme_boost_union |
    # First, do not upload any image at all
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should not exist
    # Next, upload a course image to course 1 and check that it is not used
    And the following "theme_boost_union > course overview file" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should not exist
    # Next, upload a global image and check that it is used
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should exist
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
    Then "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/<fileaareatech>/login_bg1.png')]" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/<fileaareatech>/login_bg2.png')]" "xpath_element" should not exist
    And I am on "Course 2" course homepage
    And "#courseheaderimage" "css_element" should exist
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
    Then "#courseheaderimage" "css_element" should exist
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
    Then "#courseheaderimage" "css_element" should exist
    And "<elementshouldexist>" "css_element" should exist in the "#page-header" "css_element"
    And "<elementshouldnotexist>" "css_element" should not exist in the "#page-header" "css_element"

    Examples:
      | layout       | elementshouldexist           | elementshouldnotexist        |
      | stacked      | #bucourseheader.stacked      | #bucourseheader.headingabove |
      | headingabove | #bucourseheader.headingabove | #bucourseheader.stacked      |

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
    And I set the field "Course header layout" to "stacked"
    And I press "Save and display"
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And "#bucourseheader.stacked" "css_element" should exist in the "#page-header" "css_element"
    And "#bucourseheader.headingabove" "css_element" should not exist in the "#page-header" "css_element"

  Scenario Outline: Setting: Course header - Define the course header image requirement.
    Given the following config values are set as admin:
      | config                        | value          | plugin            |
      | courseheaderenabled           | yes            | theme_boost_union |
      | courseheaderimagesource       | <source>       | theme_boost_union |
      | courseheaderimagerequirement  | <requirement>  | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And the following "theme_boost_union > course overview file" exists:
      | course   | <course>                                       |
      | filepath | theme/boost_union/tests/fixtures/login_bg2.png |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "#bucourseheader" "css_element" <shouldornotelement> exist
    And "#bucourseheader #courseheaderimage<imageclass>" "css_element" <shouldornotclass> exist

    # We do not want to burn too much CPU time by testing all possible image sources. We just test one with a fallback and one without.
    Examples:
      | source           | requirement          | course | shouldornotelement | shouldornotclass | imageclass    |
      | courseplusglobal | standardonly         | C1     | should             | should           | .withimage    |
      | courseplusglobal | enhancedwithoutimage | C1     | should             | should           | .withimage    |
      | coursenoglobal   | standardonly         | C1     | should             | should           | .withimage    |
      | coursenoglobal   | enhancedwithoutimage | C1     | should             | should           | .withimage    |
      # To simulate not uploading an image in course 1 in the same scenario outline, we simply upload the image to course 2 to that course 1 has no image.
      | courseplusglobal | standardonly         | C2     | should             | should           | .withimage    |
      | courseplusglobal | enhancedwithoutimage | C2     | should             | should           | .withimage    |
      | coursenoglobal   | standardonly         | C2     | should not         | should not       | .withimage    |
      | coursenoglobal   | enhancedwithoutimage | C2     | should             | should           | .withoutimage |

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
    Then "#courseheaderimage" "css_element" should exist
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
    Then "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'min-height: 250px')]" "xpath_element" should exist

  Scenario Outline: Setting: Course header - Define the course header canvas border (globally).
    Given the following config values are set as admin:
      | config                    | value    | plugin            |
      | courseheaderenabled       | yes      | theme_boost_union |
      | courseheaderimagesource   | global   | theme_boost_union |
      | courseheadercanvasborder  | <border> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And the "class" attribute of "#courseheaderimage" "css_element" <shouldornot> contain "<borderclass>"

    # We do not want to burn too much CPU time by testing all available options. We just test the default value and the other available values.
    Examples:
      | border     | borderclass             | shouldornot |
      | none       | border                  | should not  |
      | grey       | border-secondary border | should      |
      | brandcolor | border-primary border   | should      |

  Scenario: Setting: Course header - Define the course header canvas border (per course).
    Given the following config values are set as admin:
      | config                                  | value | plugin            |
      | courseheaderenabled                     | yes   | theme_boost_union |
      | courseheaderimagesource                 | global| theme_boost_union |
      | courseheadercanvasborder                | none  | theme_boost_union |
      | courseheadercanvasborder_courseoverride | 0     | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I should not see "Course header canvas border"
    And the following config values are set as admin:
      | config                                  | value | plugin            |
      | courseheadercanvasborder_courseoverride | 1     | theme_boost_union |
    And I reload the page
    And I should see "Course header canvas border" in the "#id_theme_boost_union_course_courseheaderhdr" "css_element"
    And I set the field "Course header canvas border" to "Grey border"
    And I press "Save and display"
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And "#courseheaderimage.border-secondary" "css_element" should exist

  Scenario Outline: Setting: Course header - Define the course header canvas background (globally).
    Given the following config values are set as admin:
      | config                        | value        | plugin            |
      | courseheaderenabled           | yes          | theme_boost_union |
      | courseheaderimagesource       | global       | theme_boost_union |
      | courseheadercanvasbackground  | <background> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And the "class" attribute of "#courseheaderimage" "css_element" <shouldornot> contain "<backgroundclass>"

    Examples:
      | background              | backgroundclass            | shouldornot |
      | transparent             | bg-                        | should not  |
      | white                   | bg-white                   | should      |
      | lightgrey               | bg-light                   | should      |
      | lightbrandcolor         | bg-primary-light           | should      |
      | brandcolorgradientlight | bg-primary-gradient-light  | should      |
      | brandcolorgradientfull  | bg-primary-gradient-full   | should      |

  Scenario: Setting: Course header - Define the course header canvas background (per course).
    Given the following config values are set as admin:
      | config                                      | value       | plugin            |
      | courseheaderenabled                         | yes         | theme_boost_union |
      | courseheaderimagesource                     | global      | theme_boost_union |
      | courseheadercanvasbackground                | transparent | theme_boost_union |
      | courseheadercanvasbackground_courseoverride | 0           | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I should not see "Course header canvas background"
    And the following config values are set as admin:
      | config                                      | value | plugin            |
      | courseheadercanvasbackground_courseoverride | 1     | theme_boost_union |
    And I reload the page
    And I should see "Course header canvas background" in the "#id_theme_boost_union_course_courseheaderhdr" "css_element"
    And I set the field "Course header canvas background" to "White"
    And I press "Save and display"
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And the "class" attribute of "#courseheaderimage" "css_element" should contain "bg-white"

  Scenario Outline: Setting: Course header - Define the course header text on image style (globally).
    Given the following config values are set as admin:
      | config                        | value       | plugin            |
      | courseheaderenabled           | yes         | theme_boost_union |
      | courseheaderimagesource       | global      | theme_boost_union |
      | courseheaderlayout            | <layout>    | theme_boost_union |
      | courseheadertextonimagestyle  | <textstyle> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And the "class" attribute of ".bucoursetitle" "css_element" <shouldornot> contain "<textclass>"

    # We do not want to burn too much CPU time by testing all possible options. We just test the available values on a values where the style is set plus one layout where the text style should not be set.
    Examples:
      | layout       | textstyle   | textclass               | shouldornot |
      | stacked      | light       | textonimage-light       | should      |
      | stacked      | lightshadow | textonimage-lightshadow | should      |
      | stacked      | lightbg     | textonimage-lightbg     | should      |
      | stacked      | dark        | textonimage-dark        | should      |
      | stacked      | darkshadow  | textonimage-darkshadow  | should      |
      | stacked      | darkbg      | textonimage-darkbg      | should      |
      | headingabove | light       | textonimage-light       | should not  |
      | headingabove | dark        | textonimage-dark        | should not  |

  Scenario: Setting: Course header - Define the course header text on image style (per course).
    Given the following config values are set as admin:
      | config                                       | value   | plugin            |
      | courseheaderenabled                          | yes     | theme_boost_union |
      | courseheaderimagesource                      | global  | theme_boost_union |
      | courseheaderlayout                           | stacked | theme_boost_union |
      | courseheadertextonimagestyle                 | white   | theme_boost_union |
      | courseheadertextonimagestyle_courseoverride  | 0       | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I should not see "Course header text on image style"
    And the following config values are set as admin:
      | config                                      | value | plugin            |
      | courseheadertextonimagestyle_courseoverride | 1     | theme_boost_union |
    And I reload the page
    And I should see "Course header text on image style" in the "#id_theme_boost_union_course_courseheaderhdr" "css_element"
    And I set the field "Course header text on image style" to "Dark (dark font color for light background images)"
    And I press "Save and display"
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    Then "#courseheaderimage" "css_element" should exist
    And the "class" attribute of ".bucoursetitle" "css_element" should contain "textonimage-dark"

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
    Then "#courseheaderimage" "css_element" should exist
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
    Then "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'background-position: left top')]" "xpath_element" should exist

  Scenario Outline: Setting: Course header - Show course contacts in the course header
    Given the following config values are set as admin:
      | config                   | value           | plugin            |
      | courseheaderenabled      | yes             | theme_boost_union |
      | courseheaderimagesource  | global          | theme_boost_union |
      | courseheadershowcontacts | <contactsvalue> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And the following "users" exist:
      | username | firstname | lastname |
      | teacher2 | John      | Doe      |
      | teacher3 | Jane      | Doe      |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher2 | C1     | editingteacher |
      | teacher3 | C1     | editingteacher |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then ".courseheadericonsbar .coursecontacts" "css_element" <shouldornot> exist in the "#bucourseheader" "css_element"

    Examples:
      | contactsvalue | shouldornot |
      | no            | should not  |
      | yes           | should      |

  # We do not check the content of the course contacts section here as there, the same data as on the course category overview
  # page is used and that is already tested in theme_boost_union_looksettings_categoryindexsitehome.feature
  # Scenario Outline: Setting: Course header - Show course contacts in the course header: Check the content

  Scenario Outline: Setting: Course header - Show course shortname in the course header
    Given the following config values are set as admin:
      | config                    | value            | plugin            |
      | courseheaderenabled       | yes              | theme_boost_union |
      | courseheaderimagesource   | global           | theme_boost_union |
      | courseheadershowshortname | <shortnamevalue> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then ".bucoursetitle .shortname" "css_element" <shouldornot> exist in the "#bucourseheader" "css_element"

    Examples:
      | shortnamevalue | shouldornot |
      | no             | should not  |
      | yes            | should      |

  Scenario Outline: Setting: Course header - Show course category in the course header
    Given the following config values are set as admin:
      | config                   | value           | plugin            |
      | courseheaderenabled      | yes             | theme_boost_union |
      | courseheaderimagesource  | global          | theme_boost_union |
      | courseheadershowcategory | <categoryvalue> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then ".bucoursetitle .categoryname" "css_element" <shouldornot> exist in the "#bucourseheader" "css_element"

    Examples:
      | categoryvalue | shouldornot |
      | no            | should not  |
      | yes           | should      |

  Scenario Outline: Setting: Course header - Show course completion progress in the course header: Set the setting
    Given the following config values are set as admin:
      | config                   | value          | plugin            |
      | courseheaderenabled      | yes            | theme_boost_union |
      | courseheaderimagesource  | global         | theme_boost_union |
      | courseheadershowprogress | <settingvalue> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And the following "activities" exist:
      | activity | name              | course | completion |
      | assign   | Activity sample 1 | C1     | 1          |
      | assign   | Activity sample 2 | C2     | 1          |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then ".courseprogress" "css_element" <shouldornot> exist in the "#bucourseheader" "css_element"

    Examples:
      | settingvalue | shouldornot |
      | no           | should not  |
      | yes          | should      |

  # We do not check the content of the course contacts section here as there, the same data as on the course category overview
  # page is used and that is already tested in theme_boost_union_looksettings_categoryindexsitehome.feature
  # Scenario: Setting: Course header - Show course completion progress in the course header: Check the content

  Scenario Outline: Setting: Course header - Show course completion progress in the course header: Check the style
    Given the following config values are set as admin:
      | config                      | value    | plugin            |
      | courseheaderenabled         | yes      | theme_boost_union |
      | courseheaderimagesource     | global   | theme_boost_union |
      | courseheadershowprogress    | yes      | theme_boost_union |
      | courseheaderprogressstyle   | <style>  | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And the following "activities" exist:
      | activity | name              | course | completion |
      | assign   | Activity sample 1 | C1     | 1          |
      | assign   | Activity sample 2 | C2     | 1          |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then ".courseprogress" "css_element" should exist in the "#bucourseheader" "css_element"
    And "#courseheaderimage .courseprogress" "css_element" <progressonimageshouldornot> exist
    And "#courseheaderimage + .courseprogress" "css_element" <progressbelowimageshouldornot> exist

    Examples:
      | style      | progressonimageshouldornot | progressbelowimageshouldornot |
      | percentage | should                     | should not                    |
      | bar        | should not                 | should                        |

  Scenario: Setting: Course header - Show course fields in the course header: Set the setting (to disabled)
    Given the following config values are set as admin:
      | config                  | value  | plugin            |
      | courseheaderenabled     | yes    | theme_boost_union |
      | courseheaderimagesource | global | theme_boost_union |
      | courseheadershowfields  | no     | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
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
    And I am on "Course 1" course homepage
    Then ".customfields" "css_element" should not exist in the "#bucourseheader" "css_element"

  Scenario Outline: Setting: Course header - Show course fields in the course header: Set the setting (to enabled, with various fields selected)
    Given the following config values are set as admin:
      | config                  | value  | plugin            |
      | courseheaderenabled     | yes    | theme_boost_union |
      | courseheaderimagesource | global | theme_boost_union |
      | courseheadershowfields  | yes    | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And the following "custom field categories" exist:
      | name          | component   | area   | itemid |
      | Fieldcategory | core_course | course | 0      |
    And the following "custom fields" exist:
      | name    | category      | type     | shortname | description | configdata            |
      | Field 1 | Fieldcategory | text     | f1        | d1          |                       |
      | Field 2 | Fieldcategory | select   | f2        | d2          | {"options":"a\nb\nc"} |
    And I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Course" "link" in the "#adminsettings .nav-tabs" "css_element"
    # We must specify the container where to look for the fields as Behat would stumble otherwise as the same fields exist
    # on the "Category index / site home" tab as well.
    And I set the field "Field 1" in the "#admin-courseheaderselectfields" "css_element" to "<field1value>"
    And I set the field "Field 2" in the "#admin-courseheaderselectfields" "css_element" to "<field2value>"
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Field 1 | test |
      | Field 2 | a    |
    And I press "Save and display"
    And I log out
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then ".customfields" "css_element" <shouldornot> exist in the "#bucourseheader" "css_element"
    And ".customfields .customfield_f1" "css_element" <field1shouldornot> exist in the "#bucourseheader" "css_element"
    And ".customfields .customfield_f2" "css_element" <field2shouldornot> exist in the "#bucourseheader" "css_element"

    Examples:
      | field1value | field2value | shouldornot | field1shouldornot | field2shouldornot |
      | 0           | 0           | should not  | should not        | should not        |
      | 1           | 0           | should      | should            | should not        |
      | 1           | 1           | should      | should            | should            |

  Scenario Outline: Setting: Course header - Show course fields in the course header: Check the content and style
    Given the following config values are set as admin:
      | config                   | value        | plugin            |
      | courseheaderenabled      | yes          | theme_boost_union |
      | courseheaderimagesource  | global       | theme_boost_union |
      | courseheadershowfields   | yes          | theme_boost_union |
      | courseheaderstylefields  | <stylevalue> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And the following "custom field categories" exist:
      | name          | component   | area   | itemid |
      | Fieldcategory | core_course | course | 0      |
    And the following "custom fields" exist:
      | name    | category      | type     | shortname | description | configdata            |
      | Field 1 | Fieldcategory | text     | f1        | d1          |                       |
      | Field 2 | Fieldcategory | select   | f2        | d2          | {"options":"a\nb\nc"} |
    And I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Course" "link" in the "#adminsettings .nav-tabs" "css_element"
    # We must specify the container where to look for the fields as Behat would stumble otherwise as the same fields exist
    # on the "Category index / site home" tab as well.
    And I set the field "Field 1" in the "#admin-courseheaderselectfields" "css_element" to "1"
    And I set the field "Field 2" in the "#admin-courseheaderselectfields" "css_element" to "1"
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Field 1 | test |
      | Field 2 | a    |
    And I press "Save and display"
    And I log out
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then ".customfields" "css_element" should exist in the "#bucourseheader" "css_element"
    And I should see "Field 1" in the ".customfields .<fieldselector>.customfield_text .customfieldname" "css_element"
    And I should see "test" in the ".customfields .<fieldselector>.customfield_text .customfieldvalue" "css_element"
    And I should see "Field 2" in the ".customfields .<fieldselector>.customfield_select .customfieldname" "css_element"
    And I should see "a" in the ".customfields .<fieldselector>.customfield_select .customfieldvalue" "css_element"

    Examples:
      | stylevalue | fieldselector    |
      | text       | customfield      |
      | badge      | customfieldbadge |

  Scenario Outline: Setting: Course header - Show course fields in the course header: No fields existing
    Given the following config values are set as admin:
      | config                   | value          | plugin            |
      | courseheaderenabled      | yes            | theme_boost_union |
      | courseheaderimagesource  | global         | theme_boost_union |
      | courseheadershowfields   | <settingvalue> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    And I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Course" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I should see "There isn't any usable custom course field yet."
    And Behat debugging is enabled
    And I log out
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then ".customfields" "css_element" <shouldornot> exist in the "#bucourseheader" "css_element"

    Examples:
      | settingvalue | shouldornot |
      | no           | should not  |
      | yes          | should not  |

  Scenario Outline: Setting: Course header - Show course popup button in the course header: Set the setting
    Given the following config values are set as admin:
      | config                  | value  | plugin            |
      | courseheaderenabled     | yes    | theme_boost_union |
      | courseheaderimagesource | global | theme_boost_union |
      | courseheadershowpopup   | <show> | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then ".courseheadericonsbar .popupbutton" "css_element" <shouldornot> exist in the "#bucourseheader" "css_element"

    Examples:
      | show | shouldornot |
      | yes  | should      |
      | no   | should not  |

  @javascript
  Scenario: Setting: Course header - Show course popup button in the course header: Click the button
    Given the following config values are set as admin:
      | config                  | value  | plugin            |
      | courseheaderenabled     | yes    | theme_boost_union |
      | courseheaderimagesource | global | theme_boost_union |
      | courseheadershowpopup   | yes    | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on ".courseheadericonsbar .popupbutton" "css_element"
    Then ".modal-dialog" "css_element" should be visible
    And I should see "Course 1" in the ".modal-dialog .modal-title" "css_element"
    And I click on ".modal-dialog .btn-close" "css_element"
    And ".modal-dialog" "css_element" should not be visible

  # We do not check the content of the popup here as there, the same data as on the course category overview
  # page is used and that is already tested in theme_boost_union_looksettings_categoryindexsitehome.feature
  # Scenario: Setting: Course header - Show course popup button in the course header: Check the content

  Scenario: Setting: Course header - Show edit icon in course header when edit mode is on
    Given the following config values are set as admin:
      | config                            | value | plugin            |
      | courseheaderenabled               | yes   | theme_boost_union |
      | courseheaderheight_courseoverride | 1     | theme_boost_union |
      | courseheadershowediticon          | yes   | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then ".courseheadericonsbar .courseheaderediticon" "css_element" should not exist in the "#bucourseheader" "css_element"
    And I turn editing mode on
    And ".courseheadericonsbar .courseheaderediticon a i.fa-pencil" "css_element" should exist in the "#bucourseheader" "css_element"
    And I click on ".courseheadericonsbar .courseheaderediticon a" "css_element"
    And I should see "Edit course settings"
    And the field "Course full name" matches value "Course 1"

  Scenario: Setting: Course header - Do not show edit icon in course header when setting is disable (countercheck)
    Given the following config values are set as admin:
      | config                            | value | plugin            |
      | courseheaderenabled               | yes   | theme_boost_union |
      | courseheaderheight_courseoverride | 1     | theme_boost_union |
      | courseheadershowediticon          | no    | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    Then ".courseheadericonsbar .courseheaderediticon" "css_element" should not exist in the "#bucourseheader" "css_element"

  Scenario: Setting: Course header - Do not show edit icon to students (who cannot edit the course anyway)
    Given the following config values are set as admin:
      | config                            | value | plugin            |
      | courseheaderenabled               | yes   | theme_boost_union |
      | courseheaderheight_courseoverride | 1     | theme_boost_union |
      | courseheadershowediticon          | yes   | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then ".courseheadericonsbar .courseheaderediticon" "css_element" should not exist in the "#bucourseheader" "css_element"

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
    Then "#courseheaderimage" "css_element" should exist
    And I am on "Social Course" course homepage
    And "#courseheaderimage" "css_element" should not exist

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
    Then the "Course header layout" select box <stackedshouldornot> contain "Course title stacked on full surface course header image"
    And the "Course header layout" select box <headingaboveshouldornot> contain "Course title above of full surface course header image"

    Examples:
      | layout               | stackedshouldornot | headingaboveshouldornot |
      # The first case is straightforward: if stacked is excluded, it should not be offered.
      | stacked              | should not         | should                  |
      # Then, we have a special one: if only the currently set layout is excluded, it should still be shown.
      | headingabove         | should             | should                  |
      # Last, we should check the exclusion of multiple commas-separated layouts.
      # However, as long as we only have two layouts, this test case falls back to the second one.
      # This should be extended when more layouts are added.
      | stacked,headingabove | should not         | should                  |

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
    And I set the field "Course header layout" to "stacked"
    And I press "Save and display"
    And "#bucourseheader.stacked" "css_element" should exist in the "#page-header" "css_element"
    And "#bucourseheader.headingabove" "css_element" should not exist in the "#page-header" "css_element"
    And the following config values are set as admin:
      | config                          | value   | plugin            |
      | courseheaderlayoutexclusionlist | stacked | theme_boost_union |
    And I reload the page
    And "#bucourseheader.stacked" "css_element" should not exist in the "#page-header" "css_element"
    And "#bucourseheader.headingabove" "css_element" should exist in the "#page-header" "css_element"

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
    And "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'min-height: 150px')]" "xpath_element" should exist
    And the following config values are set as admin:
      | config                            | value | plugin            |
      | courseheaderheight_courseoverride | 1     | theme_boost_union |
    And I click on "Settings" "link"
    And I set the field "Course header height" to "250px"
    And I press "Save and display"
    And I am on "Course 1" course homepage
    And "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'min-height: 250px')]" "xpath_element" should exist
    And the following config values are set as admin:
      | config                            | value | plugin            |
      | courseheaderheight_courseoverride | 0     | theme_boost_union |
    And I click on "Settings" "link"
    And I should not see "Course header height"
    And I am on "Course 1" course homepage
    And "#courseheaderimage" "css_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, 'min-height: 150px')]" "xpath_element" should exist

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

  Scenario Outline: Setting: Course header - 'Use global default' option is available and selected by default and shows the actual global setting value
    Given the following config values are set as admin:
      | config                            | value                | plugin            |
      | courseheaderenabled               | yes                  | theme_boost_union |
      | courseheaderlayout                | <courseheaderlayout> | theme_boost_union |
      | courseheaderlayout_courseoverride | 1                    | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And "#bucourseheader.<courseheaderlayout>" "css_element" should exist
    And I navigate to "Settings" in current page administration
    Then the field "Course header layout" matches value "Use global default (<courseheaderlabel>)"

    Examples:
      | courseheaderlayout | courseheaderlabel                                        |
      | headingabove       | Course title above of full surface course header image   |
      | stacked            | Course title stacked on full surface course header image |

  Scenario: Setting: Course header - 'Use global default' option can be changed to course override value and this value persists
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
    And "#bucourseheader.headingabove" "css_element" should exist
    And I navigate to "Settings" in current page administration
    And I set the field "Course header layout" to "Course title stacked on full surface course header image"
    And I press "Save and display"
    And I am on "Course 1" course homepage
    Then "#bucourseheader.stacked" "css_element" should exist
    And I navigate to "Settings" in current page administration
    Then the field "Course header layout" matches value "Course title stacked on full surface course header image"
    And I am on "Course 1" course homepage
    Then "#bucourseheader.stacked" "css_element" should exist

  Scenario: Setting: Course header - 'Use global default' option can be set back in the course again
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
    And "#bucourseheader.headingabove" "css_element" should exist
    And I navigate to "Settings" in current page administration
    And I set the field "Course header layout" to "Course title stacked on full surface course header image"
    And I press "Save and display"
    And I am on "Course 1" course homepage
    Then "#bucourseheader.stacked" "css_element" should exist
    And I navigate to "Settings" in current page administration
    And I set the field "Course header layout" to "Use global default (Course title above of full surface course header image)"
    And I press "Save and display"
    And I navigate to "Settings" in current page administration
    Then the field "Course header layout" matches value "Use global default (Course title above of full surface course header image)"
    And I am on "Course 1" course homepage
    Then "#bucourseheader.headingabove" "css_element" should exist

  Scenario: Setting: Course header - Global default change affects courses using global default
    Given the following config values are set as admin:
      | config                            | value        | plugin            |
      | courseheaderenabled               | yes          | theme_boost_union |
      | courseheaderimagesource           | global       | theme_boost_union |
      | courseheaderlayout                | headingabove | theme_boost_union |
      | courseheaderlayout_courseoverride | 1            | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And "#bucourseheader.headingabove" "css_element" should exist
    # Now change the global default (and change it within the GUI to trigger the updatecallback method).
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Course" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "Course header layout" to "Course title stacked on full surface course header image"
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage
    Then "#bucourseheader.stacked" "css_element" should exist
    And "#bucourseheader.headingabove" "css_element" should not exist

  Scenario: Setting: Course header - Global default change does not affect courses with specific override
    Given the following config values are set as admin:
      | config                            | value        | plugin            |
      | courseheaderenabled               | yes          | theme_boost_union |
      | courseheaderimagesource           | global       | theme_boost_union |
      | courseheaderlayout                | headingabove | theme_boost_union |
      | courseheaderlayout_courseoverride | 1            | theme_boost_union |
    And the following "theme_boost_union > setting file" exists:
      | filearea | courseheaderimageglobal                        |
      | filepath | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    And I set the field "Course header layout" to "Course title above of full surface course header image"
    And I press "Save and display"
    And I am on "Course 1" course homepage
    And "#bucourseheader.headingabove" "css_element" should exist
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Course" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "Course header layout" to "Course title stacked on full surface course header image"
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage
    And I navigate to "Settings" in current page administration
    Then the field "Course header layout" matches value "Course title above of full surface course header image"
    And I am on "Course 1" course homepage
    And "#bucourseheader.headingabove" "css_element" should exist
