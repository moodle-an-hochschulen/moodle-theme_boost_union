@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_mobile
Feature: Configuring the theme_boost_union plugin for the "mobile" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  @javascript
  Scenario: mobilecss:  - Insert content in editor for additional css which is only displayed in the App.
    Given the following config values are set as admin:
      | config                 | value  |
      | enablemobilewebservice | yes    |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "Additional CSS" to ".atest {font-size: 16px;}"
    And I press "Save changes"
    And I navigate to "General > Mobile app > Mobile appearance" in site administration
    Then "//div[@id='admin-mobilecssurl']//input[contains(@value, 'theme/boost_union/mobile/styles.php')]" "xpath_element" should exist

    # Manual Testing, is css really used in App.
