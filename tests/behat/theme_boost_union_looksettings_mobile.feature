@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_mobile
Feature: Configuring the theme_boost_union plugin for the "Mobile" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following config values are set as admin:
      | config                 | value |
      | enablemobilewebservice | yes   |

  Scenario: Setting: Additional CSS for Mobile app - Insert CSS code and test that the mobilecssurl URL is set correctly.
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "Additional CSS for Mobile app" to multiline:
    """
    a.test { font-size: 16px; }
    """
    And I press "Save changes"
    And Behat debugging is enabled
    And I navigate to "General > Mobile app > Mobile appearance" in site administration
    Then "//div[@id='admin-mobilecssurl']//input[contains(@value, 'theme/boost_union/mobile/styles.php')]" "xpath_element" should exist

  Scenario: Setting: Additional CSS for Mobile app - Insert CSS code and test that the mobilecssurl URL is overwritten correctly.
    Given the following config values are set as admin:
      | config       | value                      |
      | mobilecssurl | https://mymoodle/mycss.css |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "Additional CSS for Mobile app" to multiline:
    """
    a.test { font-size: 16px; }
    """
    And I press "Save changes"
    And Behat debugging is enabled
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
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "Additional CSS for Mobile app" to multiline:
    """
    """
    And I press "Save changes"
    And Behat debugging is enabled
    And I navigate to "General > Mobile app > Mobile appearance" in site administration
    Then "//div[@id='admin-mobilecssurl']//input[contains(@value, 'theme/boost_union/mobile/styles.php')]" "xpath_element" should not exist

  # Unfortunately, this can't be tested with Behat yet as Mobile App testing is not added to this plugin yet.
  # Scenario: Setting: Additional CSS for Mobile app - Verify that the CSS code has an effect in the Mobile app.

  @javascript @_file_upload
  Scenario: Setting: Touch icon files for iOS - Upload touch icon files
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/apple-icon-180x180.png" file to "Touch icon files for iOS" filemanager
    And I upload "theme/boost_union/tests/fixtures/apple-icon-60x60.png" file to "Touch icon files for iOS" filemanager
    And I press "Save changes"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then I should see "Touch icon files for iOS list"
    And ".settings-touchiconsios-filelist" "css_element" should exist
    And I should see "apple-icon-180x180.png" in the ".apple-icon-180x180" "css_element"
    And I should not see "apple-icon-152x152.png" in the ".apple-icon-152x152" "css_element"
    And I should see "apple-icon-152x152" in the ".apple-icon-152x152" "css_element"
    And I should see "apple-icon-60x60.png" in the ".apple-icon-60x60" "css_element"
    And I should see "This is a recommended file to be used as touch icon on iOS devices and it was uploaded." in the ".apple-icon-180x180" "css_element"
    And I should see "This is a recommended file to be used as touch icon on iOS devices, but it was not uploaded properly." in the ".apple-icon-152x152" "css_element"
    And I should see "This is an optional file to be used as touch icon on iOS devices and it was uploaded." in the ".apple-icon-60x60" "css_element"
    And I should see "This is an optional file to be used as touch icon on iOS devices and it was not uploaded." in the ".apple-icon-57x57" "css_element"
    # We can't check that the uploaded file is properly used when the site is added to the mobile homescreen, but we can at least verify that the file is added to the head of the page.
    And "//head//link[contains(@rel, 'apple-touch-icon')][contains(@sizes, '180x180')][contains(@href, 'pluginfile.php/1/theme_boost_union/touchiconsios')][contains(@href, 'apple-icon-180x180.png')]" "xpath_element" should exist
    And "//head//link[contains(@rel, 'apple-touch-icon')][contains(@sizes, '152x152')][contains(@href, 'pluginfile.php/1/theme_boost_union/touchiconsios')][contains(@href, 'apple-icon-152x152.png')]" "xpath_element" should not exist
    And "//head//link[contains(@rel, 'apple-touch-icon')][contains(@sizes, '60x60')][contains(@href, 'pluginfile.php/1/theme_boost_union/touchiconsios')][contains(@href, 'apple-icon-60x60.png')]" "xpath_element" should exist
    And Behat debugging is enabled

  @javascript @_file_upload
  Scenario: Setting: Touch icon files for iOS - Do not upload any file (countercheck)
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Mobile" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then I should not see "Touch icon files for iOS list"
    And ".settings-touchiconsios-filelist" "css_element" should not exist
    And Behat debugging is enabled
