@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_divider
Feature: Configuring the theme_boost_union plugin on the "Smart menus" page, using a divider link item
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "theme_boost_union > smart menu" exists:
      | title    | Quick links                                      |
      | location | Main navigation, Menu bar, User menu, Bottom bar |
    And the following "theme_boost_union > smart menu item" exists:
      | menu     | Quick links |
      | itemtype | Divider     |

  Scenario: Smartmenus: Menu items: Divider - Use a divider in smart menu
    When I log in as "admin"
    # Divider in main navigation.
    Then ".dropdown-divider" "css_element" should exist in the ".primary-navigation" "css_element"
    # Divider in user menu.
    And ".dropdown-divider" "css_element" should exist in the "#usermenu-carousel" "css_element"
    # Divider in bottom menu.
    And ".dropdown-divider" "css_element" should exist in the ".bottom-navigation" "css_element"
    # Divider in menubar.
    And ".dropdown-divider" "css_element" should exist in the "nav.menubar" "css_element"
