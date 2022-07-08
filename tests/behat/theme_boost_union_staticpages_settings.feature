@theme @theme_boost_union @theme_boost_union_staticpages_settings
Feature: Configuring the theme_boost_union plugin for the "Static pages" tab
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  @javascript
  Scenario: Setting: Enable imprint - Do not enable the imprint page
    Given the following config values are set as admin:
      | config        | value                     | plugin            |
      | enableimprint | no                        | theme_boost_union |
    # The footnote is just filled to make sure it is displayed at all and we can check for the .imprintlink within it later.
      | footnote      | <p>My little footnote</p> | theme_boost_union |
    When I log in as "admin"
    Then ".imprintlink" "css_element" should not exist
    And I am on imprint page
    Then I should see "The imprint is disabled for this site. There is nothing to see here."
    And ".imprintlink" "css_element" should not exist in the "#footnote" "css_element"
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    And ".imprintlink" "css_element" should not exist in the ".footer .popover-body" "css_element"

  Scenario: Setting: Enable imprint - Enable and fill the imprint page with content
    Given the following config values are set as admin:
      | config         | value              | plugin            |
      | enableimprint  | yes                | theme_boost_union |
      | imprintcontent | <p>Lorem ipsum</p> | theme_boost_union |
    When I log in as "admin"
    And I am on imprint page
    Then I should see "Lorem ipsum" in the "div[role='main']" "css_element"
    And I should see "Imprint" in the "title" "css_element"
    And I should see "Imprint" in the "div[role='main'] h2" "css_element"

  @javascript
  Scenario: Setting: Imprint link position - Do not automatically add the imprint link
    Given the following config values are set as admin:
      | config              | value                     | plugin            |
      | enableimprint       | yes                       | theme_boost_union |
      | imprintcontent      | <p>Lorem ipsum</p>        | theme_boost_union |
      | imprintlinkposition | none                      | theme_boost_union |
    # The footnote is just filled to make sure it is displayed at all and we can check for the .imprintlink within it later.
      | footnote            | <p>My little footnote</p> | theme_boost_union |
    When I log in as "admin"
    And I am on imprint page
    Then I should see "Lorem ipsum" in the "div[role='main']" "css_element"
    And ".imprintlink" "css_element" should not exist in the "#footnote" "css_element"
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    And ".imprintlink" "css_element" should not exist in the ".footer .popover-body" "css_element"

  @javascript
  Scenario: Setting: Imprint link position - Add the imprint link to the footnote automatically (even if the footnote is empty otherwise)
    Given the following config values are set as admin:
      | config              | value              | plugin            |
      | enableimprint       | yes                | theme_boost_union |
      | imprintcontent      | <p>Lorem ipsum</p> | theme_boost_union |
      | imprintlinkposition | footnote           | theme_boost_union |
      | footnote            |                    | theme_boost_union |
    When I log in as "admin"
    Then "#footnote" "css_element" should exist
    And ".imprintlink" "css_element" should exist in the "#footnote" "css_element"
    And I should see "Imprint" in the ".imprintlink" "css_element"
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    And ".imprintlink" "css_element" should not exist in the ".footer .popover-body" "css_element"

  @javascript
  Scenario: Setting: Imprint link position - Add the imprint link to the footnote automatically (if the footnote contains some content already)
    Given the following config values are set as admin:
      | config              | value                     | plugin            |
      | enableimprint       | yes                       | theme_boost_union |
      | imprintcontent      | <p>Lorem ipsum</p>        | theme_boost_union |
      | imprintlinkposition | footnote                  | theme_boost_union |
      | footnote            | <p>My little footnote</p> | theme_boost_union |
    When I log in as "admin"
    Then "#footnote" "css_element" should exist
    And ".imprintlink" "css_element" should exist in the "#footnote" "css_element"
    And I should see "Imprint" in the ".imprintlink" "css_element"
    And ".imprintlink" "css_element" should appear after "My little footnote" "text"
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    And ".imprintlink" "css_element" should not exist in the ".footer .popover-body" "css_element"

  @javascript
  Scenario: Setting: Imprint link position - Add the imprint link to the footer automatically
    Given the following config values are set as admin:
      | config              | value                     | plugin            |
      | enableimprint       | yes                       | theme_boost_union |
      | imprintcontent      | <p>Lorem ipsum</p>        | theme_boost_union |
      | imprintlinkposition | footer                    | theme_boost_union |
    # The footnote is just filled to make sure it is displayed at all and we can check for the .imprintlink within it later.
      | footnote            | <p>My little footnote</p> | theme_boost_union |
    When I log in as "admin"
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then ".imprintlink" "css_element" should exist in the ".footer .popover-body" "css_element"
    And I should see "Imprint" in the ".imprintlink" "css_element"
    And ".imprintlink" "css_element" should not exist in the "#footnote" "css_element"

  @javascript
  Scenario: Setting: Imprint link position - Add the imprint link to the footnote and the footer automatically
    Given the following config values are set as admin:
      | config              | value                     | plugin            |
      | enableimprint       | yes                       | theme_boost_union |
      | imprintcontent      | <p>Lorem ipsum</p>        | theme_boost_union |
      | imprintlinkposition | both                      | theme_boost_union |
    # The footnote is just filled to make sure it is displayed at all and we can check for the .imprintlink within it later.
      | footnote            | <p>My little footnote</p> | theme_boost_union |
    When I log in as "admin"
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then ".imprintlink" "css_element" should exist in the ".footer .popover-body" "css_element"
    And I should see "Imprint" in the ".imprintlink" "css_element"
    And ".imprintlink" "css_element" should exist in the "#footnote" "css_element"

  @javascript
  Scenario: Setting: Imprint page title - Set an empty imprint page title (and trigger the fallback string)
    Given the following config values are set as admin:
      | config              | value              | plugin            |
      | enableimprint       | yes                | theme_boost_union |
      | imprintcontent      | <p>Lorem ipsum</p> | theme_boost_union |
      | imprintpagetitle    |                    | theme_boost_union |
    When I log in as "admin"
    And I am on imprint page
    Then I should see "Imprint" in the "div[role='main'] h2" "css_element"
    And "//title[contains(text(),'Imprint')]" "xpath_element" should exist
    And the following config values are set as admin:
      | config              | value              | plugin            |
      | imprintlinkposition | footnote           | theme_boost_union |
    And I reload the page
    Then I should see "Imprint" in the "#footnote .imprintlink" "css_element"
    And the following config values are set as admin:
      | config              | value              | plugin            |
      | imprintlinkposition | footer             | theme_boost_union |
    And I reload the page
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I should see "Imprint" in the ".footer .popover-body .imprintlink" "css_element"

  @javascript
  Scenario: Setting: Imprint page title - Set a custom imprint page title
    Given the following config values are set as admin:
      | config              | value              | plugin            |
      | enableimprint       | yes                | theme_boost_union |
      | imprintcontent      | <p>Lorem ipsum</p> | theme_boost_union |
      | imprintpagetitle    | Custom             | theme_boost_union |
    When I log in as "admin"
    And I am on imprint page
    Then I should see "Custom" in the "div[role='main'] h2" "css_element"
    And "//title[contains(text(),'Custom')]" "xpath_element" should exist
    And the following config values are set as admin:
      | config              | value              | plugin            |
      | imprintlinkposition | footnote           | theme_boost_union |
    And I reload the page
    Then I should see "Custom" in the "#footnote .imprintlink" "css_element"
    And the following config values are set as admin:
      | config              | value              | plugin            |
      | imprintlinkposition | footer             | theme_boost_union |
    And I reload the page
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I should see "Custom" in the ".footer .popover-body .imprintlink" "css_element"
