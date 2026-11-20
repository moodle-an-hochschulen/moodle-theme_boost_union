@theme @theme_boost_union @theme_boost_union_fileserving
Feature: Serving the files of the theme_boost_union plugin
  In order to see the theme files
  As a user
  I need to get the theme files served properly

  Background:
    Given the following config values are set as admin:
      | enablemyhome      | 1           |
      | forcelogin        | 1           |
      | sitepolicyhandler | tool_policy |
    And the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | COHORT1  |
    And the following "users" exist:
      | username |
      | user1    |
    And the following "cohort members" exist:
      | cohort  | user  |
      | COHORT1 | user1 |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocohorts_ids |
      | My shiny new flavour | COHORT1            |

  @javascript
  Scenario: File serving: Flavour compact logo is served on the site policy page (with the site policy not being accepted yet)
    Given the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                  | filepath                                         |
      | My shiny new flavour | flavours_look_logocompact | theme/boost_union/tests/fixtures/flavourlogo.png |
    And the following policies exist:
      | Name             | Revision | Content    | Summary     | Status |
      | This site policy |          | full text2 | short text2 | active |
    When I log in as "user1"
    Then I should see "This site policy"
    # The flavour compact logo must not only be referenced in the HTML source, it must be served properly as well.
    And "//nav//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/flavours_look_logocompact')][contains(@src, 'flavourlogo.png')]" "xpath_element" should exist
    And DOM element "nav img.logo" should be a successfully loaded image

  @javascript
  Scenario: File serving: User is redirected to the dashboard after accepting the site policy (and not to a flavour file)
    Given the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                  | filepath                                         |
      | My shiny new flavour | flavours_look_logocompact | theme/boost_union/tests/fixtures/flavourlogo.png |
    And the following policies exist:
      | Name             | Revision | Content    | Summary     | Status |
      | This site policy |          | full text2 | short text2 | active |
    When I log in as "user1"
    And I should see "This site policy"
    And I press "Next"
    And I set the field "I agree to the This site policy." to "1"
    And I press "Next"
    # Serving the flavour files must not have overwritten the URL which the user wanted to visit initially.
    Then the url should match "/my/"
    And I should see "Dashboard"

  @javascript
  Scenario: File serving: Flavour background image is served on the site policy page (with the site policy not being accepted yet)
    Given the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                      | filepath                                       |
      | My shiny new flavour | flavours_look_backgroundimage | theme/boost_union/tests/fixtures/login_bg2.png |
    And the following policies exist:
      | Name             | Revision | Content    | Summary     | Status |
      | This site policy |          | full text2 | short text2 | active |
    When I log in as "user1"
    Then I should see "This site policy"
    # The flavour background image must not only be referenced in the CSS, it must be served properly as well.
    And DOM element "body" should have background image with file name "login_bg2.png"
    And DOM element "body" should have a successfully loaded background image

  @javascript
  Scenario: File serving: Flavour compact logo is served on a regular page (with the forcelogin setting being enabled)
    Given the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                  | filepath                                         |
      | My shiny new flavour | flavours_look_logocompact | theme/boost_union/tests/fixtures/flavourlogo.png |
    When I log in as "user1"
    Then I should see "Dashboard"
    And "//nav//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/flavours_look_logocompact')][contains(@src, 'flavourlogo.png')]" "xpath_element" should exist
    And DOM element "nav img.logo" should be a successfully loaded image

  @javascript
  Scenario: File serving: Course header image is served to a logged-in user (with the forcelogin setting being enabled)
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user  | course | role    |
      | user1 | C1     | student |
    And the following config values are set as admin:
      | config                  | value             | plugin            |
      | courseheaderenabled     | yes               | theme_boost_union |
      | courseheaderimagesource | dedicatednoglobal | theme_boost_union |
    And the following "theme_boost_union > course header image" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg3.png |
    When I log in as "user1"
    And I am on "Course 1" course homepage
    # The course header image must not only be referenced in the HTML source, it must be served properly as well.
    Then DOM element "#courseheaderimage" should have background image with file name "login_bg3.png"
    And DOM element "#courseheaderimage" should have a successfully loaded background image

  Scenario: File serving: Course header image is not served to a non-logged-in user (with the forcelogin setting being enabled)
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "theme_boost_union > course header image" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg3.png |
    When I request the course header image "login_bg3.png" of course "C1" directly
    Then the url should match "/login/index\.php"

  Scenario: File serving: Course header image is not served to a non-logged-in user (with the forcelogin setting being disabled)
    Given the following config values are set as admin:
      | forcelogin | 0 |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "theme_boost_union > course header image" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg3.png |
    When I request the course header image "login_bg3.png" of course "C1" directly
    Then the url should match "/login/index\.php"

  Scenario: File serving: Course header image is served to a logged-in user who is enrolled in the course (when requested directly)
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user  | course | role    |
      | user1 | C1     | student |
    And the following "theme_boost_union > course header image" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg3.png |
    When I log in as "user1"
    And I request the course header image "login_bg3.png" of course "C1" directly
    Then the url should match "/theme_boost_union/courseheaderimage/0/login_bg3\.png"

  Scenario: File serving: Course header image is not served to a logged-in user who is not enrolled in the course
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "theme_boost_union > course header image" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg3.png |
    When I log in as "user1"
    And I request the course header image "login_bg3.png" of course "C1" directly
    Then the url should match "/enrol/index\.php"

  Scenario: File serving: Course header image is not served to a logged-in user who is enrolled in the course if the course is hidden
    Given the following "courses" exist:
      | fullname | shortname | visible |
      | Course 1 | C1        | 0       |
    And the following "course enrolments" exist:
      | user  | course | role    |
      | user1 | C1     | student |
    And the following "theme_boost_union > course header image" exists:
      | course   | C1                                             |
      | filepath | theme/boost_union/tests/fixtures/login_bg3.png |
    When I log in as "user1"
    And I request the course header image "login_bg3.png" of course "C1" directly
    Then I should see "This course is currently unavailable to students"
