@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_docslink
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, using a dynamic documentationn link item
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given I log in as "admin"
    And I create smart menu with the following fields to these values:
      | Title            | Quick links              |
      | Menu location(s) | Main, Menu, User, Bottom |

  @javascript
  Scenario: Smartmenus: Menu items: Documentation Link - Add a smart menu documentation link item in smart menu
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Documentation          |
      | Menu item type | Moodle Documentation   |
    And I should see smart menu "Quick links" item "Documentation" in location "Main, Menu, User, Bottom"
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Documentation')]" "xpath_element" should contain "/en/theme/boost_union/smartmenus/items"
    And I go to the courses management page
    And I should see smart menu "Quick links" item "Documentation" in location "Main, Menu, User, Bottom"
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Documentation')]" "xpath_element" should contain "en/course/management"
