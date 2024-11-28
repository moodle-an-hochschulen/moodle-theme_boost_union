@theme @theme_boost_union @theme_boost_union_feelsettings @theme_boost_union_feelsettings_pagelayouts @theme_boost_union_footer
Feature: Configuring the theme_boost_union plugin for the "Page layouts" tab on the "Feel" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  @javascript
  Scenario Outline: Setting: Show navigation on policy overview page
    Given the following config values are set as admin:
      | config                   | value     | plugin            |
      | policyoverviewnavigation | <setting> | theme_boost_union |
    And I visit '/admin/tool/policy/viewall.php'
    Then ".navbar" "css_element" <shouldornot> exist
    And "#page-footer" "css_element" <shouldornot> exist

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |
