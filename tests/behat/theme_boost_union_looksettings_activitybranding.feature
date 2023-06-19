@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_activitybranding
Feature: Configuring the theme_boost_union plugin for the "Activity branding" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | student1 |
      | teacher1 |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon color for "Administration" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon color for "Assessment" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon color for "Collaboration" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon color for "Communication" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon color for "Content" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon color for "Interface" - Setting the color

  # Unfortunately, this can't be tested with Behat yet
  # Scenario: Setting: Activity icon purposes - Setting the purpose

  @javascript @_file_upload
  Scenario Outline: Setting: Custom icons files - Upload custom icons files
    Given the following config values are set as admin:
      | config         | value | plugin            |
      | modiconsenable | yes   | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Activity branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I click on ".fa-folder-o" "css_element" in the "#admin-modiconsfiles .fp-btn-mkdir" "css_element"
    And I set the field "New folder name" to "assign"
    And I click on ".fp-dlg-butcreate" "css_element" in the ".moodle-dialogue .fp-mkdir-dlg" "css_element"
    And I click on ".aabtn" "css_element" in the "#admin-modiconsfiles .fp-folder" "css_element"
    And I upload "theme/boost_union/tests/fixtures/<iconfile>" file to "Custom icons files" filemanager
    And I press "Save changes"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Activity branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then I should see "Custom icons files list"
    And ".settings-modicons-filelist" "css_element" should exist
    And I should see "/<modtechname>/<iconfile>" in the ".settings-modicons-filelist h6" "css_element"
    And I should see "Activity: <modclearname>" in the ".settings-modicons-filelist" "css_element"
    And I should see "Icon version: <iconversion>" in the ".settings-modicons-filelist" "css_element"
    # Unfortunately we can only test the result in the custom icons files list. We cannot distinguish the icons in the activity chooser visually

    Examples:
      | iconfile     | iconversion          | modtechname | modclearname |
      | monologo.svg | Moodle 4 icon        | assign      | Assignment   |
      | monologo.png | Moodle 4 icon        | assign      | Assignment   |
      | icon.svg     | Moodle 3 legacy icon | assign      | Assignment   |
      | icon.png     | Moodle 3 legacy icon | assign      | Assignment   |

  @javascript @_file_upload
  Scenario: Setting: Custom icons files - Do not upload any file (countercheck)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Activity branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then I should not see "Custom icons files list"
    And ".settings-modicons-filelist" "css_element" should not exist
