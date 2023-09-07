@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_presentation
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, applying different presentation options to the individual smart menu items
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given I log in as "admin"
    And the following "courses" exist:
      | fullname               | shortname | category |
      | Test course1           | C1        | 0        |
      | Test course2           | C2        | 0        |
      | Test course word count | C3        | 0        |
    And the following "users" exist:
      | username |
      | user1    |
    And I create smart menu with the following fields to these values:
      | Title            | Quick links              |
      | Menu location(s) | Main, Menu, User, Bottom |

  @javascript
  Scenario Outline: Smartmenus: Menu items: Presentation - Open the smart menu items in different targets
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title                            | Available courses |
      | Menu item type                   | Dynamic courses   |
      | Dynamic courses: Course category | Category 1        |
      | Menu item mode                   | Inline            |
      | Link target                      | <setting>         |
    And I should see "Available courses" in the "smartmenus_items" "table"
    # Menu items in main navigation
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And I should see "Test course1" in the ".primary-navigation" "css_element"
    And the "target" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Test course1')]" "xpath_element" <should>
    # Menu items in user menu.
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Quick links" "link" in the "#usermenu-carousel" "css_element"
    And I should see "Test course1" in the "#usermenu-carousel" "css_element"
    And the "target" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Test course1')]" "xpath_element" <should>
    # Menu items in bottom menu.
    Then I change the viewport size to "740x900"
    And I click on "Quick links" "link" in the ".bottom-navigation" "css_element"
    And I should see "Test course1" in the ".bottom-navigation" "css_element"
    And the "target" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Test course1')]" "xpath_element" <should>
    Then I change the viewport size to "large"
    # Menu items in menubar.
    And I click on "Quick links" "link" in the "nav.menubar" "css_element"
    And I should see "Test course1" in the "nav.menubar" "css_element"
    And the "target" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Test course1')]" "xpath_element" <should>

    Examples:
      | setting     | should                  |
      | New tab     | should contain "_blank" |
      | Same window | should not be set       |

  @javascript
  Scenario: Smartmenus: Menu items: Presentation - Include the custom css class for a smart menu item
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title          | Resources         |
      | Menu item type | Static            |
      | Menu item URL  | http://moodle.org |
    And I navigate to smart menu "Quick links" items
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "CSS class" to "static-item-resources"
    And I click on "Save changes" "button"
    And I should see "Resources" in the "smartmenus_items" "table"
    And the "class" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "static-item-resources"
    And the "class" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "static-item-resources"
    And the "class" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "static-item-resources"
    And I change the viewport size to "740x900"
    And the "class" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "static-item-resources"
    And I change the viewport size to "large"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "CSS class" to "course-resource-links"
    And I click on "Save changes" "button"
    And the "class" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should not contain "static-item-resources"
    And the "class" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "course-resource-links"
    And the "class" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should not contain "static-item-resources"
    And the "class" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "course-resource-links"
    And the "class" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should not contain "static-item-resources"
    And the "class" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "course-resource-links"
    And I change the viewport size to "740x900"
    And the "class" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should not contain "static-item-resources"
    And the "class" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "course-resource-links"

  @javascript
  Scenario Outline: Smartmenus: Menu items: Presentation - Display the different fields as smart menu item title
    When I log in as "admin"
    And I set "Quick links" smart menu items with the following fields to these values:
      | Title                                     | Available courses |
      | Menu item type                            | Dynamic courses   |
      | Dynamic courses: Course category          | Category 1        |
      | Dynamic courses: Course name presentation | <selectnamefield> |
      | Dynamic courses: Number of words          | <numberofwords>   |
    And I should see "Available courses" in the "smartmenus_items" "table"
    And I click on "Quick links" "link" in the ".primary-navigation" "css_element"
    And I should see "<showntitle>" in the ".primary-navigation" "css_element"
    And I should not see "<notshowntitle>" in the ".primary-navigation" "css_element"

    Examples:
      | selectnamefield   | numberofwords | showntitle             | notshowntitle          |
      | Course short name |               | C1                     | Test course            |
      | Course full name  |               | Test course word count | C1                     |
      | Course full name  | 2             | Test course..          | Test course word count |
