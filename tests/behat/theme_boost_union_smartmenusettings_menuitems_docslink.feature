@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_docslink
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, using a dynamic documentation link item
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Quick links                                      |
      | location | Main navigation, Menu bar, User menu, Bottom bar |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links          |
      | title    | Documentation        |
      | itemtype | Moodle documentation |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links                     |
      | title    | Dummy item to avoid menu hiding |
      | itemtype | Static                          |
      | url      | /dummy                          |

  @javascript
  Scenario: Smartmenus: Menu items: Moodle documenation - Add a smart menu documentation link item in a smart menu
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Smart menus" in site administration
    Then I should see smart menu "Quick links" item "Documentation" in location "Main, Menu, User, Bottom"
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Documentation')]" "xpath_element" should contain "/en/admin/theme/boost_union/smartmenus/menus"
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Documentation')]" "xpath_element" should contain "https://docs.moodle.org/"
    And I go to the courses management page
    And I should see smart menu "Quick links" item "Documentation" in location "Main, Menu, User, Bottom"
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Documentation')]" "xpath_element" should contain "/en/course/management"
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Documentation')]" "xpath_element" should contain "https://docs.moodle.org/"

  @javascript
  Scenario Outline: Smartmenus: Menu items: Moodle documenation - Show the smart menu documentation link item only to users who have the necessary capability
    Given the following "users" exist:
      | username |
      | teacher1 |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And the following "permission overrides" exist:
      | capability           | permission   | role           | contextlevel | reference |
      | moodle/site:doclinks | <permission> | editingteacher | System       |           |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I <shouldornot> see smart menu "Quick links" item "Documentation" in location "Main, Menu, User, Bottom"

    Examples:
      | permission | shouldornot |
      | Allow      | should      |
      | Prevent    | should not  |
