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
      | activityiconcolorfidelity      | 500        | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I click on "Add an activity or resource" "button" in the "New section" "section"
    # First, we test that the default filter is _not_ set anymore.
    Then DOM element ".chooser-container .activityiconcontainer.modicon_<modname> img" should not have computed style "filter" "<originalfilter>"
    # And then, as the hex color to CSS filter conversion results are not reproducible, we test if the applied filter is close enough to the hex color.
    And DOM element ".chooser-container .activityiconcontainer.modicon_<modname> img" should have a CSS filter close enough to hex color "<colorhex>"

    # Unfortunately, we can only test 4 out of 6 purpose types as Moodle does does not ship with any activity with the
    # administration and interface types. But this should be an acceptable test coverage anyway.
    Examples:
      | purposename        | modname | colorhex | originalfilter                                                                              |
      | assessment         | assign  | #FF0000  | invert(0.36) sepia(0.98) saturate(69.69) hue-rotate(315deg) brightness(0.9) contrast(1.19)  |
      | collaboration      | data    | #00FF00  | invert(0.25) sepia(0.54) saturate(62.26) hue-rotate(245deg) brightness(1) contrast(1.02)    |
      | communication      | choice  | #0000FF  | invert(0.48) sepia(0.74) saturate(48.87) hue-rotate(11deg) brightness(1.02) contrast(1.01)  |
      | content            | book    | #FFFF00  | invert(0.49) sepia(0.52) saturate(46.75) hue-rotate(156deg) brightness(0.89) contrast(1.02) |
      | interactivecontent | lesson  | #00FFFF  | invert(0.25) sepia(0.63) saturate(11.52) hue-rotate(344deg) brightness(0.94) contrast(0.91) |

  @javascript
  Scenario Outline: Setting: Activity icon purposes - Setting the purpose
    Given I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Activity branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I select "<purpose>" from the "<modname>" singleselect
    And I press "Save changes"
    And Behat debugging is enabled
    When I am on "Course 1" course homepage
    And I turn editing mode on
    And I add a <mod> activity to course "Course 1" section "0" and I fill the form with:
      | <titlesetting> | Test name |
    Then DOM element ".activity.modtype_<mod> .activityiconcontainer.courseicon img" should have computed style "filter" "<filter>"
    And I click on "Add an activity or resource" "button" in the "New section" "section"
    Then DOM element ".chooser-container .activityiconcontainer.modicon_<mod> img" should have computed style "filter" "<filter>"
    And I am on the "Test name" "<mod> activity" page
    Then DOM element "#page-header .modicon_<mod>.activityiconcontainer img" should have computed style "filter" "<filter>"

    # We do not want to burn too much CPU time by testing all plugins. We just test two plugins which is fine as all plugins are handled with the same PHP code.
    # In addition to that, we test the 'other purpose' which is special.
    # These examples will work until Moodle core changes the default colors of the module purpose types.
    Examples:
      | modname    | titlesetting    | purpose       | mod    | filter                                                                                     |
      | Assignment | Assignment name | Collaboration | assign | invert(0.25) sepia(0.54) saturate(62.26) hue-rotate(245deg) brightness(1) contrast(1.02)   |
      | Book       | Name            | Communication | book   | invert(0.48) sepia(0.74) saturate(48.87) hue-rotate(11deg) brightness(1.02) contrast(1.01) |
      | Assignment | Assignment name | Other         | assign | none                                                                                       |

  @javascript @_file_upload
  Scenario Outline: Setting: Custom icons files - Upload custom icons files
    Given the following config values are set as admin:
      | config         | value | plugin            |
      | modiconsenable | yes   | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Activity branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I click on ".fa-folder-plus" "css_element" in the "#admin-modiconsfiles .fp-btn-mkdir" "css_element"
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
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Activity branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then I should not see "Custom icons files list"
    And ".settings-modicons-filelist" "css_element" should not exist
    And Behat debugging is enabled
