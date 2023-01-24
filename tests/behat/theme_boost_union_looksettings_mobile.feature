@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_mobile
Feature: Configuring the theme_boost_union plugin for the "Mobile app" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following config values are set as admin:
      | config                 | value |
      | enablemobilewebservice | yes   |

  Scenario: Setting: Additional CSS for Mobile app - Insert CSS code and test that the mobilecssurl URL is set correctly.
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile app" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "Additional CSS for Mobile app" to multiline:
    """
    a.test { font-size: 16px; }
    """
    And I press "Save changes"
    And I navigate to "General > Mobile app > Mobile appearance" in site administration
    Then "//div[@id='admin-mobilecssurl']//input[contains(@value, 'theme/boost_union/mobile/styles.php')]" "xpath_element" should exist

  Scenario: Setting: Additional CSS for Mobile app - Insert CSS code and test that the mobilecssurl URL is overwritten correctly.
    Given the following config values are set as admin:
      | config       | value                      |
      | mobilecssurl | https://mymoodle/mycss.css |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile app" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "Additional CSS for Mobile app" to multiline:
    """
    a.test { font-size: 16px; }
    """
    And I press "Save changes"
    And I navigate to "General > Mobile app > Mobile appearance" in site administration
    Then "//div[@id='admin-mobilecssurl']//input[contains(@value, 'theme/boost_union/mobile/styles.php')]" "xpath_element" should exist
    And I should not see "mycss.css" in the "#id_s__mobilecssurl" "css_element"

  Scenario: Setting: Additional CSS for Mobile app - Remove CSS code and test that the mobilecssurl URL is cleared correctly.
    Given the following config values are set as admin:
      | config       | value                      |
      | mobilecssurl | https://mymoodle/mycss.css |
    And the following config values are set as admin:
      | config     | value                       | plugin            |
      | mobilescss | a.test { font-size: 16px; } | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile app" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "Additional CSS for Mobile app" to multiline:
    """
    """
    And I press "Save changes"
    And I navigate to "General > Mobile app > Mobile appearance" in site administration
    Then "//div[@id='admin-mobilecssurl']//input[contains(@value, 'theme/boost_union/mobile/styles.php')]" "xpath_element" should not exist

  # Unfortunately, this can't be tested with Behat yet as Mobile App testing is not added to this plugin yet.
  # Scenario: Setting: Additional CSS for Mobile app - Verify that the CSS code has an effect in the Mobile app.

  @javascript @_file_upload
  Scenario: Setting: Additional mobile images
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/apple-icon-152x152.jpeg" file to "Touch Icon Files" filemanager
    And I press "Save changes"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then I should see "Icon Files for Devices"
    And ".settings-touchicon-filelist" "css_element" should exist
    And I should see "apple-icon-152x152" in the ".apple-icon-152x152" "css_element"
    And I should see "It is an optional file to display the icon on iOS devices and was uploaded." in the ".apple-icon-152x152" "css_element"
    And I should see "It is an optional file to display the icon on iOS devices, but it was not uploaded properly." in the ".apple-icon-57x57" "css_element"
    And I should see "It is a recommended file to display the icon on iOS devices, but it was not uploaded properly." in the ".apple-icon-180x180" "css_element"
    # Icons should also be tested - however, mobile testing is a tobe done when there is more time https://docs.moodle.org/dev/Acceptance_testing_for_the_Moodle_App.

  @javascript @_file_upload
  Scenario: Setting: Additional mobile images countercheck no list
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then I should not see "Icon Files for Devices"
