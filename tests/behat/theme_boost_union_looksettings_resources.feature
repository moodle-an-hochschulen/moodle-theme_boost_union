@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_resources
Feature: Configuring the theme_boost_union plugin for the "Resources" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  @javascript @_file_upload
  Scenario: Setting: Additional resources - Upload additional resources files
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Resources" "link"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.jpg" file to "Additional resources" filemanager
    And I press "Save changes"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Resources" "link"
    Then I should see "Additional resources list"
    And ".settings-additionalresources-filelist" "css_element" should exist
    And ".settings-additionalresources-filelist .list-group-item .icon[title='Image (JPEG)']" "css_element" should exist
    And ".settings-additionalresources-filelist .list-group-item .icon[src$='/f/jpeg-64']" "css_element" should exist
    And I should see "MIME type: image/jpeg" in the ".settings-additionalresources-filelist" "css_element"
    And I should see "Size: 38.5" in the ".settings-additionalresources-filelist" "css_element"
    And I should see "URL (persistent):" in the ".settings-additionalresources-filelist" "css_element"
    And I should see "/pluginfile.php/1/theme_boost_union/additionalresources/0/login_bg1.jpg" in the ".settings-additionalresources-filelist" "css_element"
    And I should see "URL (revisioned):" in the ".settings-additionalresources-filelist" "css_element"
    # Checking the revisioned URL is not possible currently with Behat without writing a custom step. We accept this for now.

  @javascript @_file_upload
  Scenario: Setting: Additional resources - Do not upload any file (countercheck)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Resources" "link"
    Then I should not see "Additional resources list"
    And ".settings-additionalresources-filelist" "css_element" should not exist
