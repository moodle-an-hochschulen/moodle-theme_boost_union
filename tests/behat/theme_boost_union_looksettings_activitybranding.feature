@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_activitybranding
Feature: Configuring the theme_boost_union plugin for the "Activity branding" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |

  @javascript
  Scenario Outline: Setting: Activity icon colors - Setting the color
    Given the following config values are set as admin:
      | config                         | value      | plugin            |
      | activityiconcolor<purposename> | <colorhex> | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I click on "Add an activity or resource" "button" in the "Topic 1" "section"
    Then DOM element ".chooser-container .activityiconcontainer.modicon_<modname>" should have computed style "background-color" "<colorrgb>"

    # Unfortunately, we can only test 4 out of 6 purpose types as Moodle does does not ship with any activity with the
    # administration and interface types. But this should be an acceptable test coverage anyway.
    Examples:
      | purposename   | modname | colorhex | colorrgb         |
      | assessment    | assign  | #FF0000  | rgb(255, 0, 0)   |
      | collaboration | data    | #00FF00  | rgb(0, 255, 0)   |
      | communication | choice  | #0000FF  | rgb(0, 0, 255)   |
      | content       | book    | #FFFF00  | rgb(255, 255, 0) |

  @javascript
  Scenario Outline: Setting: Activity icon purposes - Setting the purpose
    Given I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Activity branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I select "<purpose>" from the "<modname>" singleselect
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage
    And I turn editing mode on
    When I click on "Add an activity or resource" "button" in the "Topic 1" "section"
    # We just test if the color in the activity chooser was changed.
    # Testing all other locations where the activity icons are shown as well and where Boost Union had to modify
    # the color individually as well would be an overhead which does not really make sense here.
    Then DOM element ".chooser-container .activityiconcontainer.modicon_<mod>" should have computed style "background-color" "<colorrgb>"

    # We do not want to burn too much CPU time by testing all plugins. We just test two plugins which is fine as all plugins are handled with the same PHP code.
    # These examples will work until Moodle core changes the default colors of the module purpose types.
    Examples:
      | modname    | purpose       | mod    | colorrgb          |
      | Assignment | Collaboration | assign | rgb(247, 99, 77)  |
      | Book       | Communication | book   | rgb(17, 166, 118) |

  @javascript @_file_upload
  Scenario Outline: Setting: Custom icons files - Upload custom icons files
    Given the following config values are set as admin:
      | config         | value | plugin            |
      | modiconsenable | yes   | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
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
    And Behat debugging is enabled

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
    And Behat debugging is enabled
