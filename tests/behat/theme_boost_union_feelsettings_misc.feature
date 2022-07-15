@theme @theme_boost_union @theme_boost_union_feelsetttings @theme_boost_union_feelsetttings_misc
Feature: Configuring the theme_boost_union plugin for the "Miscellaneous" tab on the "Feel" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Scenario: Setting: JavaScript disabled hint - Enable the setting and make sure the hint is shown when JavaScript is disabled
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | javascriptdisabledhint | yes   | theme_boost_union |
    When I log in as "admin"
    Then "#javascriptdisabledhint" "css_element" should exist
    And I should see "JavaScript is disabled in your browser" in the "#javascriptdisabledhint" "css_element"

  @javascript
  Scenario: Setting: JavaScript disabled hint - Enable the setting and make sure the hint is not shown when JavaScript is enabled
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | javascriptdisabledhint | yes   | theme_boost_union |
    When I log in as "admin"
    Then "#javascriptdisabledhint" "css_element" should not exist

  Scenario: Setting: JavaScript disabled hint - Disable the setting and make sure the hint is not shown when JavaScript is disabled
    Given the following config values are set as admin:
      | config                 | value | plugin            |
      | javascriptdisabledhint | no    | theme_boost_union |
    When I log in as "admin"
    Then "#javascriptdisabledhint" "css_element" should not exist
