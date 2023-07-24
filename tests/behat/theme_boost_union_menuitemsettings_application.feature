@theme @theme_boost_union @theme_boost_union_smartmenu @theme_boost_union_menusettings @theme_boost_union_menuitemsettings_application
Feature: Configuring the theme_boost_union plugin on the "Smart menus" items page, applying different configuration to the individual smart menu items
  In order to use th features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "courses" exist:
      | fullname                | shortname | category |
      | Test course              | C1 | 0 |
      | Test course2             | C2 | 0 |
      | Test course word count   | C3 | 0 |
    And the following "users" exist:
      | username | firstname | lastname | email             | lang |
      | student1 | student   | User 1   | student1@test.com | en   |
      | student2 | student2  | User 2   | student2@test.com | fr   |
      | teacher  | Teacher   | User 1   | teacher2@test.com | de   |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher  | C1     | editingteacher |
      | student1 | C1     | student        |
      | admin    | C1     | manager        |
    And the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | CH1      |
      | Cohort 2 | CH1      |
    And the following "cohort members" exist:
      | user     | cohort |
      | student1 | CH1    |

    Given I log in as "admin"
    And I create menu with the following fields to these values:
      | Title     | Quick Links              |
      | Locations | Main, Menu, User, Bottom |

  @javascript
  Scenario: Smartmenuitem: Application - Add a item in smartmenu to the main navigation
    Given I log in as "admin"
    And I navigate to smartmenu "Quick Links" items
    And I click on "Add new item" "link"
    And I set the following fields to these values:
    | Title | Badges                    |
    | Type  | Static                    |
    | URL   | https://moodle.org/badges |
    And I click on "Save changes" "button"
    And I click on "Quick Links" "link" in the "nav.moremenu" "css_element"
    And I should see "Badges" in the "nav.moremenu" "css_element"
    And I log out
    And I log in as "student1"
    And I click on "Quick Links" "link" in the "nav.moremenu" "css_element"
    And I should see "Badges" in the "nav.moremenu" "css_element"
    And I log out
    And I log in as "admin"
    And I navigate to smartmenu "Quick Links" items
    And I click on ".action-edit" "css_element" in the "Badges" "table_row"
    And I expand all fieldsets
    And I set the field "Title" to "Your Badges"
    And I click on "Save changes" "button"
    And I click on "Quick Links" "link" in the "nav.moremenu" "css_element"
    And I should see "Your Badges" in the "nav.moremenu" "css_element"

  # Inline mode for items.
  @javascript
  Scenario: Smartmenuitem: Application - Display menu items in different mode
    Given I log in as "admin"
    And I navigate to smartmenu "Quick Links" items
    And I set "Quick Links" items with the following fields to these values:
      | Title     | Available courses |
      | Type      | Dynamic courses   |
      | Category  | Category 1        |
      | Mode      | Inline            |
    And I should see "Available courses" in the "smartmenus_item" "table"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And I should not see "Available courses" in the ".primary-navigation" "css_element"
    And I should see "Test course" in the ".primary-navigation" "css_element"
    And I should see "Test course2" in the ".primary-navigation" "css_element"
    # Inline menu items in usermenu.
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Quick Links" "link" in the "#usermenu-carousel" "css_element"
    And I should not see "Available courses" in the "#usermenu-carousel" "css_element"
    And I should see "Test course" in the "#usermenu-carousel" "css_element"
    And I should see "Test course2" in the "#usermenu-carousel" "css_element"
    # Inline items in bottom menu.
    Then I change the viewport size to "750x900"
    And I click on "Quick Links" "link" in the ".bottom-navigation" "css_element"
    And I should not see "Available courses" in the ".bottom-navigation" "css_element"
    And I should see "Test course" in the ".bottom-navigation" "css_element"
    And I should see "Test course2" in the ".bottom-navigation" "css_element"
    Then I change the viewport size to "large"
    # Inline Items in menubar.
    And I click on "Quick Links" "link" in the "nav.menubar" "css_element"
    And I should not see "Available courses" in the "nav.menubar" "css_element"
    And I should see "Test course" in the "nav.menubar" "css_element"
    And I should see "Test course2" in the "nav.menubar" "css_element"
    # Update the mode to submenu
    And I click on ".action-edit" "css_element" in the "Available courses" "table_row"
    And I set the field "Mode" to "Submenu"
    And I click on "Save changes" "button"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And I should see "Available courses" in the ".primary-navigation" "css_element"
    And I should not see "Test course" in the ".primary-navigation" "css_element"
    And I click on "Available courses" "link" in the ".primary-navigation" "css_element"
    And I should see "Test course" in the ".primary-navigation" "css_element"
    And I should see "Test course2" in the ".primary-navigation" "css_element"
    # Usermenu items in Submenu mode
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Quick Links" "link" in the "#usermenu-carousel" "css_element"
    And I should see "Available courses" in the "#usermenu-carousel" "css_element"
    And I should not see "Test course" in the "#usermenu-carousel" "css_element"
    And I click on "Available courses" "link" in the ".carousel-item.active" "css_element"
    And I should see "Test course" in the ".carousel-item.active" "css_element"
    And I should see "Test course2" in the ".carousel-item.active" "css_element"
    # Submenu items in bottom menu.
    Then I change the viewport size to "750x900"
    And I click on "Quick Links" "link" in the ".bottom-navigation" "css_element"
    And I should see "Available courses" in the ".bottom-navigation" "css_element"
    And I should not see "Test course" in the ".bottom-navigation" "css_element"
    And I click on "Available courses" "link" in the ".bottom-navigation" "css_element"
    And I should see "Test course" in the ".bottom-navigation" "css_element"
    And I should see "Test course2" in the ".bottom-navigation" "css_element"
    Then I change the viewport size to "large"
    # Submenu Items in menubar.
    And I click on "Quick Links" "link" in the "nav.menubar" "css_element"
    And I should see "Available courses" in the "nav.menubar" "css_element"
    And I should not see "Test course" in the "nav.menubar" "css_element"
    And I click on "Available courses" "link" in the "nav.menubar" "css_element"
    And I should see "Test course" in the "nav.menubar" "css_element"
    And I should see "Test course2" in the "nav.menubar" "css_element"

  @javascript
  Scenario: Smartmenuitem: Application - Open the item in different target
    Given I log in as "admin"
    And I navigate to smartmenu "Quick Links" items
    And I set "Quick Links" items with the following fields to these values:
      | Title     | Available courses |
      | Type      | Dynamic courses   |
      | Category  | Category 1        |
      | Mode      | Inline            |
      | Target    | New tab           |
    And I should see "Available courses" in the "smartmenus_item" "table"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And the "target" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Test course')]" "xpath_element" should contain "_blank"
    # Same window menu items in usermenu.
    And I click on "#user-menu-toggle" "css_element"
    And I click on "Quick Links" "link" in the "#usermenu-carousel" "css_element"
    And I should see "Test course" in the "#usermenu-carousel" "css_element"
    And the "target" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Test course')]" "xpath_element" should contain "_blank"
    # Submenu items in bottom menu.
    Then I change the viewport size to "750x900"
    And I click on "Quick Links" "link" in the ".bottom-navigation" "css_element"
    And I should see "Test course" in the ".bottom-navigation" "css_element"
    And the "target" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Test course')]" "xpath_element" should contain "_blank"
    Then I change the viewport size to "large"
    # Menubar with same window items.
    And I click on "Quick Links" "link" in the "nav.menubar" "css_element"
    And I should see "Test course" in the "nav.menubar" "css_element"
    And the "target" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Test course')]" "xpath_element" should contain "_blank"
    # SET new window mode.
    And I navigate to smartmenu "Quick Links" items
    And I click on ".action-edit" "css_element" in the "Available courses" "table_row"
    And I set the field "Target" to "Same window"
    And I click on "Save changes" "button"
    # Same window in primary menu.
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And the "target" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Test course')]" "xpath_element" should not be set

  @javascript
  Scenario: Smartmenuitem: Application - Include the custom css class for menu items
    Given I set "Quick Links" items with the following fields to these values:
      | Title         | Resources         |
      | Type          | Static            |
      | Menu item URL | http://moodle.org |
    # And I navigate to smartmenu "Quick Links" items
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "id_cssclass" to "static-item-resources"
    And I click on "Save changes" "button"
    And I should see "Resources" in the "smartmenus_item" "table"
    And the "class" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "static-item-resources"
    And the "class" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "static-item-resources"
    And the "class" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "static-item-resources"
    Then I change the viewport size to "750x900"
    And the "class" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "static-item-resources"
    Then I change the viewport size to "large"
    And I click on ".action-edit" "css_element" in the "Resources" "table_row"
    And I expand all fieldsets
    And I set the field "id_cssclass" to "course-resource-links"
    And I click on "Save changes" "button"
    And the "class" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should not contain "static-item-resources"
    And the "class" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "course-resource-links"
    And the "class" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should not contain "static-item-resources"
    And the "class" attribute of "//div[contains(@class, 'carousel-item')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "course-resource-links"
    And the "class" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should not contain "static-item-resources"
    And the "class" attribute of "//nav[contains(@class, 'menubar')]//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "course-resource-links"
    Then I change the viewport size to "750x900"
    And the "class" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should not contain "static-item-resources"
    And the "class" attribute of "//div[@class='bottom-navigation']//a[contains(normalize-space(.), 'Resources')]" "xpath_element" should contain "course-resource-links"

  @javascript
  Scenario: Smartmenuitem: Application - Display the different field for menu item title
    Given I log in as "admin"
    And I set "Quick Links" items with the following fields to these values:
      | Title             | Available courses |
      | Type              | Dynamic courses   |
      | Category          | Category 1        |
      | Select name field | Short name        |
    And I should see "Available courses" in the "smartmenus_item" "table"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And I should see "C1" in the ".primary-navigation" "css_element"
    And I should not see "Test course word count" in the ".primary-navigation" "css_element"
    And I navigate to smartmenu "Quick Links" items
    And I click on ".action-edit" "css_element" in the "Available courses" "table_row"
    And I set the field "Select name field" to "Full name"
    And I click on "Save changes" "button"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And I should not see "C1" in the ".primary-navigation" "css_element"
    And I should see "Test course word count" in the ".primary-navigation" "css_element"
    # Set the words count.
    And I navigate to smartmenu "Quick Links" items
    And I click on ".action-edit" "css_element" in the "Available courses" "table_row"
    And I set the field "Number of words" to "2"
    And I click on "Save changes" "button"
    And I should see "Available courses" in the "smartmenus_item" "table"
    And I click on "Quick Links" "link" in the ".primary-navigation" "css_element"
    And I should see "Test course.." in the ".primary-navigation" "css_element"
