@theme @theme_boost_union @theme_boost_union_info_banner_settings
Feature: Configuring the theme_boost_union plugin for the "Info banner Settings" tab
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | teacher1 |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |

  Scenario: Display perpetual info banner on all available pages
    Given the following config values are set as admin:
      | config            | value                              | plugin             |
      | perpibenable      | 1                                  | theme_boost_union |
      | perpibcontent     | "This is a test content"           | theme_boost_union |
      | perpibshowonpages | mydashboard,frontpage,course,login | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    And I follow "Dashboard"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    When I am on "Course 1" course homepage
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    When I log out
    And I click on "Log in" "link"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"

  Scenario: Display perpetual info banner only on one available page
    Given the following config values are set as admin:
      | config            | value                    | plugin             |
      | perpibenable      | 1                        | theme_boost_union |
      | perpibcontent     | "This is a test content" | theme_boost_union |
      | perpibshowonpages | mydashboard              | theme_boost_union |
    When I log in as "teacher1"
    And I follow "Dashboard"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    When I am on "Course 1" course homepage
    Then I should not see "This is a test content"
    When I log out
    And I click on "Log in" "link"
    Then I should not see "This is a test content"

  Scenario: Display perpetual info with the different bootstrap color classes
    Given the following config values are set as admin:
      | config            | value                    | plugin             |
      | perpibenable      | 1                        | theme_boost_union |
      | perpibcontent     | "This is a test content" | theme_boost_union |
      | perpibshowonpages | mydashboard              | theme_boost_union |
      | perpibcss         | primary                  | theme_boost_union |
    When I log in as "teacher1"
    And I follow "Dashboard"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    And the "class" attribute of "#themeboostunionperpinfobanner" "css_element" should contain "primary"
    And I log out
    Given the following config values are set as admin:
      | config    | value     | plugin             |
      | perpibcss | secondary | theme_boost_union |
    When I log in as "teacher1"
    And I follow "Dashboard"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    And the "class" attribute of "#themeboostunionperpinfobanner" "css_element" should contain "secondary"
    And I log out
    Given the following config values are set as admin:
      | config    | value   | plugin             |
      | perpibcss | success | theme_boost_union |
    When I log in as "teacher1"
    And I follow "Dashboard"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    And the "class" attribute of "#themeboostunionperpinfobanner" "css_element" should contain "success"
    And I log out
    Given the following config values are set as admin:
      | config    | value  | plugin             |
      | perpibcss | danger | theme_boost_union |
    When I log in as "teacher1"
    And I follow "Dashboard"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    And the "class" attribute of "#themeboostunionperpinfobanner" "css_element" should contain "danger"
    And I log out
    Given the following config values are set as admin:
      | config    | value   | plugin             |
      | perpibcss | warning | theme_boost_union |
    When I log in as "teacher1"
    And I follow "Dashboard"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    And the "class" attribute of "#themeboostunionperpinfobanner" "css_element" should contain "warning"
    And I log out
    Given the following config values are set as admin:
      | config    | value | plugin             |
      | perpibcss | info  | theme_boost_union |
    When I log in as "teacher1"
    And I follow "Dashboard"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    And the "class" attribute of "#themeboostunionperpinfobanner" "css_element" should contain "info"
    And I log out
    Given the following config values are set as admin:
      | config    | value | plugin             |
      | perpibcss | light | theme_boost_union |
    When I log in as "teacher1"
    And I follow "Dashboard"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    And the "class" attribute of "#themeboostunionperpinfobanner" "css_element" should contain "light"
    And I log out
    Given the following config values are set as admin:
      | config    | value | plugin             |
      | perpibcss | dark  | theme_boost_union |
    When I log in as "teacher1"
    And I follow "Dashboard"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    And the "class" attribute of "#themeboostunionperpinfobanner" "css_element" should contain "dark"
    And I log out

  Scenario: Save perpetual content but do not enable the info banner setting at all.
    Given the following config values are set as admin:
      | config            | value                    | plugin             |
      | perpibenable      | 0                        | theme_boost_union |
      | perpibcontent     | "This is a test content" | theme_boost_union |
      | perpibshowonpages | mydashboard              | theme_boost_union |
    When I log in as "teacher1"
    And I follow "Dashboard"
    Then I should not see "This is a test content"

  @javascript
  Scenario: Enable setting "Perpetual info banner dismissible"
    Given the following config values are set as admin:
      | config            | value                    | plugin             |
      | perpibenable      | 1                        | theme_boost_union |
      | perpibcontent     | "This is a test content" | theme_boost_union |
      | perpibshowonpages | mydashboard              | theme_boost_union |
      | perpibdismiss     | 1                        | theme_boost_union |
    When I log in as "teacher1"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    When I click on "#themeboostunionperpinfobannerclosebutton" "css_element"
    Then I should not see "This is a test content"

  # This setting depends on the setting "Info banner dismissible"
  @javascript
  Scenario: Enable setting "Confirmation dialogue"
    Given the following config values are set as admin:
      | config            | value                    | plugin             |
      | perpibenable      | 1                        | theme_boost_union |
      | perpibcontent     | "This is a test content" | theme_boost_union |
      | perpibshowonpages | mydashboard              | theme_boost_union |
      | perpibdismiss     | 1                        | theme_boost_union |
      | perpibconfirm     | 1                        | theme_boost_union |
    When I log in as "teacher1"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    When I click on "#themeboostunionperpinfobannerclosebutton" "css_element"
    Then I should see "Confirmation" in the ".modal-title" "css_element"
    When I click on "Cancel" "button" in the ".modal-footer" "css_element"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    When I click on "#themeboostunionperpinfobannerclosebutton" "css_element"
    Then I should see "Confirmation" in the ".modal-title" "css_element"
    When I click on "Yes, close!" "button" in the ".modal-footer" "css_element"
    Then I should not see "This is a test content"

  # This setting depends on the setting "Info banner dismissible"
  @javascript
  Scenario: Enable setting "Reset visibility for perpetual info banner"
    Given the following config values are set as admin:
      | config            | value                    | plugin             |
      | perpibenable      | 1                        | theme_boost_union |
      | perpibcontent     | "This is a test content" | theme_boost_union |
      | perpibshowonpages | mydashboard              | theme_boost_union |
      | perpibdismiss     | 1                        | theme_boost_union |
    When I log in as "teacher1"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
    When I click on "#themeboostunionperpinfobannerclosebutton" "css_element"
    Then I should not see "This is a test content"
    And I log out
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union" in site administration
    And I click on "Info Banner Settings" "link"
    And I click on "Reset visibility" "checkbox"
    And I press "Save changes"
    Then I should see "Success! All perpetual info banner instances are visible again."
    And I log out
    When I log in as "teacher1"
    Then I should see "This is a test content" in the "#themeboostunionperpinfobanner" "css_element"
