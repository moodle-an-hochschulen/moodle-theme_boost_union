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
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Course image" filemanager
    And I press "Save and display"
    And I am on site homepage
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "<role>"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg1.jpg')]" "xpath_element" should exist

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
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Course image" filemanager
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
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Course" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg2.jpg" file to "Fallback course header image" filemanager
    And I press "Save changes"
    And I am on site homepage
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "<role>"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '1/theme_boost_union/courseheaderimagefallback/0/login_bg2.jpg')]" "xpath_element" should exist

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
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Course" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg2.jpg" file to "Fallback course header image" filemanager
    And I press "Save changes"
    And I am on site homepage
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '1/theme_boost_union/courseheaderimagefallback/0/login_bg2.jpg')]" "xpath_element" should exist
    And I click on "Settings" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Course image" filemanager
    And I press "Save and display"
    And I am on site homepage
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "<role>"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '1/theme_boost_union/courseheaderimagefallback/0/login_bg2.jpg')]" "xpath_element" should not exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg1.jpg')]" "xpath_element" should exist

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
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Course image" filemanager
    And I press "Save and display"
    And I am on "Course 2" course homepage
    And I click on "Settings" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg2.jpg" file to "Course image" filemanager
    And I press "Save and display"
    And I am on site homepage
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "<role>"
    And I am on "Course 1" course homepage
    Then "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg1.jpg')]" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg2.jpg')]" "xpath_element" should not exist
    And I am on "Course 2" course homepage
    And "//div[@id='courseheaderimage']" "xpath_element" should exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg1.jpg')]" "xpath_element" should not exist
    And "//div[@id='courseheaderimage' and contains(@style, '/course/overviewfiles/login_bg2.jpg')]" "xpath_element" should exist

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
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Course image" filemanager
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
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Course image" filemanager
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
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Course image" filemanager
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
