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
      | menu     | Quick links   |
      | title    | Mail          |
      | itemtype | Mailto        |
      | email    | test@test.com |

  Scenario: Smartmenus: Menu items: Mailto - Add a smart menu documentation link item in a smart menu
    When I log in as "admin"
    Then I should see smart menu "Quick links" item "Mail" in location "Main, Menu, User, Bottom"
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Mail')]" "xpath_element" should contain "mailto:test@test.com"

  Scenario: Smartmenus: Menu items: Mailto - Cc, Bcc, subject and body are URL-encoded in the href
    Given the following "theme_boost_union > smart menu item" exists:
      | menu          | Quick links                |
      | title         | Rich mail                  |
      | itemtype      | Mailto                     |
      | email         | a@test.com, b@test.com     |
      | email_cc      | cc1@test.com, cc2@test.com |
      | email_bcc     | bcc@test.com               |
      | email_subject | Hi there                   |
      | email_body    | First line\nSecond line    |
    When I log in as "admin"
    Then I should see smart menu "Quick links" item "Rich mail" in location "Main, Menu, User, Bottom"
    # Test each mailto parameter separately to make sure they are all included and correctly URL-encoded in the href.
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Rich mail')]" "xpath_element" should contain "mailto:a@test.com,b@test.com"
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Rich mail')]" "xpath_element" should contain "cc=cc1%40test.com%2Ccc2%40test.com"
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Rich mail')]" "xpath_element" should contain "bcc=bcc%40test.com"
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Rich mail')]" "xpath_element" should contain "subject=Hi%20there"
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Rich mail')]" "xpath_element" should contain "body=First%20line%0ASecond%20line"
    # Test the whole href value to make sure all parameters are correctly combined in the mailto link.
    And the "href" attribute of "//div[@class='primary-navigation']//a[contains(normalize-space(.), 'Rich mail')]" "xpath_element" should contain "mailto:a@test.com,b@test.com?cc=cc1%40test.com%2Ccc2%40test.com&bcc=bcc%40test.com&subject=Hi%20there&body=First%20line%0ASecond%20line"
