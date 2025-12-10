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
    And I click on "Add content" "button" in the "New section" "section"
    And I click on "Activity or resource" "button" in the "New section" "section"
    # First, we test that the default filter is _not_ set anymore.
    Then DOM element ".chooser-container .activityiconcontainer.modicon_<modname> img" should not have a CSS filter close to hex color "<originalhex>"
    # And then we test if the applied filter is close enough to the hex color.
    And DOM element ".chooser-container .activityiconcontainer.modicon_<modname> img" should have a CSS filter close enough to hex color "<colorhex>"

    # Unfortunately, we can only test 5 out of 7 purpose types as Moodle does does not ship with any activity with the
    # administration and interface types. But this should be an acceptable test coverage anyway.
    Examples:
      | purposename        | modname | colorhex | originalhex |
      | assessment         | assign  | #FFFF00  | #f90086     |
      | collaboration      | data    | #00FF00  | #5b40ff     |
      | communication      | choice  | #0000FF  | #eb6200     |
      | content            | book    | #FFFF00  | #0099ad     |
      | interactivecontent | lesson  | #00FFFF  | #8d3d1b     |

  @javascript
  Scenario Outline: Setting: Activity icon colors - Setting the color (for activities in subsections)
    Given I enable "subsection" "mod" plugin
    And the following "courses" exist:
      | fullname           | shortname | category | numsections | initsections |
      | Subsectioncourse 1 | SC1       | 0        | 3           | 1            |
    And the following "activities" exist:
      | activity   | name             		   | course | idnumber | section |
      | subsection | Subsection1      	     | SC1    | sub      | 1       |
      | <modname>  | Activity in Subsection1 | SC1    | subact   | 4       |
    And the following config values are set as admin:
      | config                         | value      | plugin            |
      | activityiconcolor<purposename> | <colorhex> | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Subsectioncourse 1" course homepage
    # First, we test that the default filter is _not_ set anymore.
    Then DOM element ".modtype_subsection .modtype_<modname> .activityicon" should not have a CSS filter close to hex color "<originalhex>"
    # And then we test if the applied filter is close enough to the hex color.
    And DOM element ".modtype_subsection .modtype_<modname> .activityicon" should have a CSS filter close enough to hex color "<colorhex>"

    # We only test one example. That's enough to verify that subsections work.
    Examples:
      | purposename | modname | colorhex | originalhex |
      | content     | book    | #FFFF00  | #0099ad     |

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
    Then DOM element ".activity.modtype_<mod> .activityiconcontainer.courseicon img" should have a CSS filter close enough to hex color "<colorhex>"
    And I click on "Add content" "button" in the "New section" "section"
    And I click on "Activity or resource" "button" in the "New section" "section"
    Then DOM element ".chooser-container .activityiconcontainer.modicon_<mod> img" should have a CSS filter close enough to hex color "<colorhex>"
    And I am on the "Test name" "<mod> activity" page
    Then DOM element "#page-header .modicon_<mod>.activityiconcontainer img" should have a CSS filter close enough to hex color "<colorhex>"
    And I am on the "Course 1" "course > activities" page
    Then DOM element "#page-course-overview #<mod>_overview_title .activityiconcontainer img" should have a CSS filter close enough to hex color "<colorhex>"

    # We do not want to burn too much CPU time by testing all plugins. We just test two plugins which is fine as all plugins are handled with the same PHP code.
    # These examples will work until Moodle core changes the default colors of the module purpose types.
    # Note: Testing individual resource activities like "book" is not possible here as they are all combined under the "Resources" section on the actitivities overview page.
    Examples:
      | modname    | titlesetting    | purpose       | mod    | colorhex |
      | Assignment | Assignment name | Collaboration | assign | #5b40ff  |
      | Forum      | Forum name      | Communication | forum  | #eb6200  |

  @javascript
  Scenario: Setting: Activity icon purposes - Removing the purpose
    Given I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Activity branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I select "Other" from the "Assignment" singleselect
    And I press "Save changes"
    And Behat debugging is enabled
    When I am on "Course 1" course homepage
    And I turn editing mode on
    And I add a assign activity to course "Course 1" section "0" and I fill the form with:
      | Assignment name | Test name |
    Then DOM element ".activity.modtype_assign .activityiconcontainer.courseicon img" should have computed style "filter" "none"
    And I click on "Add content" "button" in the "New section" "section"
    And I click on "Activity or resource" "button" in the "New section" "section"
    Then DOM element ".chooser-container .activityiconcontainer.modicon_assign img" should have computed style "filter" "none"
    And I am on the "Test name" "assign activity" page
    Then DOM element "#page-header .modicon_assign.activityiconcontainer img" should have computed style "filter" "none"
    And I am on the "Course 1" "course > activities" page
    Then DOM element "#page-course-overview #assign_overview_title .activityiconcontainer img" should have computed style "filter" "none"

  @javascript
  Scenario Outline: Setting: Activity icon purposes - Setting the purpose (for activities in subsections)
    Given I enable "subsection" "mod" plugin
    And the following "courses" exist:
      | fullname           | shortname | category | numsections | initsections |
      | Subsectioncourse 1 | SC1       | 0        | 3           | 1            |
    And the following "activities" exist:
      | activity   | name             		   | course | idnumber | section |
      | subsection | Subsection1      	     | SC1    | sub      | 1       |
      | <mod>      | Activity in Subsection1 | SC1    | subact   | 4       |
    And I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "Activity branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I select "<purpose>" from the "<modname>" singleselect
    And I press "Save changes"
    And Behat debugging is enabled
    When I am on "Subsectioncourse 1" course homepage
    Then DOM element ".modtype_subsection .modtype_<mod> .activityicon" should have a CSS filter close enough to hex color "<colorhex>"

    # We only test one example. That's enough to verify that subsections work.
    Examples:
      | modname | titlesetting | purpose       | mod  | colorhex |
      | Book    | Name         | Communication | book | #eb6200  |

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
