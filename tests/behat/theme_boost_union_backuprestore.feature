@theme @theme_boost_union @theme_boost_union_backup_restore @core_backup
Feature: Backup and restore course-specific Boost Union settings
  In order to preserve course customizations
  As a teacher or admin
  I need course-specific theme settings to be included in course backups

  Background:
    Given the following "users" exist:
      | username |
      | teacher1 |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
      | Course 2 | C2        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | teacher1 | C2     | editingteacher |
    Given the following config values are set as admin:
      | config                                   | value               | plugin            |
      | courseheaderenabled                      | yes                 | theme_boost_union |
      | courseheaderenabled_courseoverride       | 1                   | theme_boost_union |
      | courseheaderlayout                       | headingabove        | theme_boost_union |
      | courseheaderlayout_courseoverride        | 1                   | theme_boost_union |
      | courseheaderheight                       | 150px               | theme_boost_union |
      | courseheaderheight_courseoverride        | 1                   | theme_boost_union |
      | courseheaderimageposition                | left top            | theme_boost_union |
      | courseheaderimageposition_courseoverride | 1                   | theme_boost_union |
      | courseheaderimagesource                  | dedicatedplusglobal | theme_boost_union |
    And the following config values are set as admin:
      | enableasyncbackup | 0 |
    And I log in as "teacher1"
    And I am on the "Course 1" "course" page
    And I navigate to "Settings" in current page administration
    And I expand all fieldsets
    And I set the following fields to these values:
      | Enable enhanced course header | yes          |
      | Course header layout          | stackedlight |
      | Course header height          | 250px        |
      | Course header image position  | right center |
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Course header image" filemanager
    And I press "Save and display"
    And I log out

  @javascript @_file_upload
  Scenario Outline: Course-specific settings are preserved in backup and restore (when restoring into a new course, performed by the admin)
    When I log in as "admin"
    And I backup "Course 1" course using this options:
      | Confirmation | Filename | test_backup.mbz |
    And I restore "test_backup.mbz" backup into a new course using this options:
      | Settings | Include course header settings | <include> |
    And I am on the "Course 1 copy 1" "course" page
    And I navigate to "Settings" in current page administration
    And I expand all fieldsets
    # We cannot cover the 'no' case here due to the hide_if rules in the form.
    Then the field "Enable enhanced course header" matches value "yes"
    And the field "Course header layout" <matchesornot> value "stackedlight"
    And the field "Course header height" <matchesornot> value "250px"
    And the field "Course header image position" <matchesornot> value "right center"
    And I <shouldornot> see "login_bg1.png" in the "#id_theme_boost_union_courseheaderimage_filemanager_fieldset" "css_element"

    Examples:
      | include | matchesornot   | shouldornot |
      | 1       | matches        | should      |
      | 0       | does not match | should not  |

  @javascript @_file_upload
  Scenario Outline: Course-specific settings are preserved in backup and restore (when restoring into the same course, performed by the teacher)
    When I log in as "teacher1"
    And I backup "Course 1" course using this options:
      | Confirmation | Filename | test_backup.mbz |
    And I am on the "Course 2" "course" page
    And I navigate to "Settings" in current page administration
    And I expand all fieldsets
    Then the field "Enable enhanced course header" matches value "yes"
    And the field "Course header layout" matches value "headingabove"
    And the field "Course header height" matches value "150px"
    And the field "Course header image position" matches value "left top"
    And I should not see "login_bg1.png" in the "#id_theme_boost_union_courseheaderimage_filemanager_fieldset" "css_element"
    And I am on the "Course 2" "restore" page
    And I merge "test_backup.mbz" backup into the current course <mergestrategy>:
      | Settings | Include course header settings | <include>   |
      | Schema   | Overwrite course configuration | <overwrite> |
    And I navigate to "Settings" in current page administration
    And I expand all fieldsets
    # We cannot cover the 'no' case here due to the hide_if rules in the form.
    Then the field "Enable enhanced course header" matches value "yes"
    And the field "Course header layout" <matchesornot> value "stackedlight"
    And the field "Course header height" <matchesornot> value "250px"
    And the field "Course header image position" <matchesornot> value "right center"
    And I <shouldornot> see "login_bg1.png" in the "#id_theme_boost_union_courseheaderimage_filemanager_fieldset" "css_element"

    Examples:
      | mergestrategy                                   | include | overwrite | matchesornot   | shouldornot |
      | after deleting it's contents using this options | 1       | Yes       | matches        | should      |
      | after deleting it's contents using this options | 0       | Yes       | does not match | should not  |
      | using this options                              | 1       | Yes       | matches        | should      |
      | using this options                              | 0       | Yes       | does not match | should not  |
      | after deleting it's contents using this options | 1       | No        | does not match | should not  |
      | after deleting it's contents using this options | 0       | No        | does not match | should not  |
      | using this options                              | 1       | No        | does not match | should not  |
      | using this options                              | 0       | No        | does not match | should not  |

  @javascript @_file_upload
  Scenario Outline: Course-specific settings are preserved in backup and restore (when restoring into the same course) and an existing coure header image is cleared
    Given I log in as "teacher1"
    And I am on the "Course 2" "course" page
    And I navigate to "Settings" in current page administration
    And I expand all fieldsets
    And I upload "theme/boost_union/tests/fixtures/login_bg2.png" file to "Course header image" filemanager
    And I press "Save and display"
    When I backup "Course 1" course using this options:
      | Confirmation | Filename | test_backup.mbz |
    And I am on the "Course 2" "restore" page
    And I merge "test_backup.mbz" backup into the current course <mergestrategy>:
      | Settings | Include course header settings | 1           |
      | Schema   | Overwrite course configuration | <overwrite> |
    And I navigate to "Settings" in current page administration
    And I expand all fieldsets
    Then I <shouldornot> see "login_bg1.png" in the "#id_theme_boost_union_courseheaderimage_filemanager_fieldset" "css_element"
    And I <shouldornotinverse> see "login_bg2.png" in the "#id_theme_boost_union_courseheaderimage_filemanager_fieldset" "css_element"

    Examples:
      | mergestrategy                                   | overwrite | shouldornot | shouldornotinverse |
      | after deleting it's contents using this options | Yes       | should      | should not         |
      | using this options                              | Yes       | should      | should not         |
      | after deleting it's contents using this options | No        | should not  | should not         |
      | using this options                              | No        | should not  | should             |

  @javascript @_file_upload
  Scenario: Course-specific settings are preserved when copying a course (performed by the admin)
    # Workaround for https://moodle.atlassian.net/browse/MDL-86764
    Given the following config values are set as admin:
      | config         | value      |
      | backup_version | 2010072300 |
    When I log in as "admin"
    And I am on the "Course 1" "course" page
    And I navigate to "Course reuse" in current page administration
    And I click on "Copy course" "link"
    And I set the following fields to these values:
      | Course full name  | Course 1 Copy |
      | Course short name | C1COPY        |
    And I press "Copy and view"
    And I run all adhoc tasks
    And I am on the "Course 1 Copy" "course" page
    And I navigate to "Settings" in current page administration
    And I expand all fieldsets
    Then the field "Enable enhanced course header" matches value "yes"
    And the field "Course header layout" matches value "stackedlight"
    And the field "Course header height" matches value "250px"
    And the field "Course header image position" matches value "right center"
    And I should see "login_bg1.png" in the "#id_theme_boost_union_courseheaderimage_filemanager_fieldset" "css_element"

  @javascript @_file_upload
  Scenario Outline: Course-specific settings are transferred during course import (performed by the teacher)
    Given the following config values are set as admin:
      | config                     | value     | plugin            |
      | courseheaderimporttransfer | <setting> | theme_boost_union |
    And the following "permission overrides" exist:
      | capability                                         | permission   | role           | contextlevel | reference |
      | theme/boost_union:transfercourseheaderduringimport | <permission> | editingteacher | Course       | C2        |
    When I log in as "teacher1"
    And I am on the "Course 2" "course" page
    And I navigate to "Settings" in current page administration
    And I expand all fieldsets
    Then the field "Enable enhanced course header" matches value "yes"
    And the field "Course header layout" matches value "headingabove"
    And the field "Course header height" matches value "150px"
    And the field "Course header image position" matches value "left top"
    And I should not see "login_bg1.png" in the "#id_theme_boost_union_courseheaderimage_filemanager_fieldset" "css_element"
    And I am on the "Course 2" "course" page
    And I navigate to "Course reuse" in current page administration
    And I click on "Import" "link"
    And I click on "importid" "radio" in the "Course 1" "table_row"
    And I press "Continue"
    And I press "Jump to final step"
    And I am on the "Course 2" "course" page
    And I navigate to "Settings" in current page administration
    And I expand all fieldsets
    # We cannot cover the 'no' case here due to the hide_if rules in the form.
    Then the field "Enable enhanced course header" matches value "yes"
    And the field "Course header layout" <matchesornot> value "stackedlight"
    And the field "Course header height" <matchesornot> value "250px"
    And the field "Course header image position" <matchesornot> value "right center"
    And I <shouldornot> see "login_bg1.png" in the "#id_theme_boost_union_courseheaderimage_filemanager_fieldset" "css_element"

    Examples:
      | setting      | permission | matchesornot   | shouldornot |
      | never        | Allow      | does not match | should not  |
      | bycapability | Allow      | matches        | should      |
      | bycapability | Prevent    | does not match | should not  |
      | always       | Allow      | matches        | should      |

  @javascript @_file_upload
  Scenario Outline: Course-specific settings are transferred during course import and an existing coure header image is cleared
    Given the following config values are set as admin:
      | config                     | value     | plugin            |
      | courseheaderimporttransfer | <setting> | theme_boost_union |
    And I log in as "teacher1"
    And I am on the "Course 2" "course" page
    And I navigate to "Settings" in current page administration
    And I expand all fieldsets
    And I upload "theme/boost_union/tests/fixtures/login_bg2.png" file to "Course header image" filemanager
    And I press "Save and display"
    When I am on the "Course 2" "course" page
    And I navigate to "Course reuse" in current page administration
    And I click on "Import" "link"
    And I click on "importid" "radio" in the "Course 1" "table_row"
    And I press "Continue"
    And I press "Jump to final step"
    And I am on the "Course 2" "course" page
    And I navigate to "Settings" in current page administration
    And I expand all fieldsets
    Then I <shouldornot> see "login_bg1.png" in the "#id_theme_boost_union_courseheaderimage_filemanager_fieldset" "css_element"
    And I <shouldornotinverse> see "login_bg2.png" in the "#id_theme_boost_union_courseheaderimage_filemanager_fieldset" "css_element"

    Examples:
      | setting | shouldornot | shouldornotinverse |
      | never   | should not  | should             |
      | always  | should      | should not         |
