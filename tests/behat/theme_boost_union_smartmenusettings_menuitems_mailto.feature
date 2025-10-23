@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_mailto
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, using a mailto item
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Quick links                                      |
      | location | Main navigation, Menu bar, User menu, Bottom bar |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links          |
      | title    | Mail                 |
      | itemtype | Mailto               |
      | email    | test@test.com        |

  @javascript
  Scenario: Smartmenus: Menu items: Mailto - Add a smart menu documentation link item in a smart menu
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Smart menus" in site administration
    Then I should see smart menu "Quick links" item "Mail" in location "Main, Menu, User, Bottom"
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Mail')]" "xpath_element" should contain "mailto:test@test.com"
