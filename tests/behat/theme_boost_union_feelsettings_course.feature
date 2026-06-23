@theme @theme_boost_union @theme_boost_union_feelsettings @theme_boost_union_feelsettings_course
Feature: Configuring the theme_boost_union plugin for the "Course" tab on the "Feel" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | student1 |
      | teacher1 |
    And the following "courses" exist:
      | fullname | shortname | format | numsections |
      | Course 1 | C1        | topics | 3           |
      | Course 2 | C2        | weeks  | 3           |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
      | teacher1 | C2     | editingteacher |
      | student1 | C2     | student        |
    And the following "activities" exist:
      | activity | course | section | name                    |
      | assign   | C1     | 0       | Assignment in section 0 |
      | assign   | C1     | 1       | Assignment in section 1 |
      | assign   | C2     | 0       | Assignment in section 0 |
      | assign   | C2     | 1       | Assignment in section 1 |

  @javascript
  Scenario Outline: Setting: Appearance of sections - Show a section with collapsing, expanded by default (default setting, regression check)
    Given the following config values are set as admin:
      | config    | value               | plugin            |
      | <setting> | collapsibleexpanded | theme_boost_union |
    When I log in as "student1"
    And I am on "<coursefullname>" course homepage
    # The sections 0 and 1 exist.
    Then "li#section-0" "css_element" should exist
    And "li#section-0" "css_element" should be visible
    And "li#section-1" "css_element" should exist
    And "li#section-1" "css_element" should be visible
    # The collapse toggle of section 0 is displayed and the section is expanded by default.
    And "//li[@id='section-0']//a[@data-for='sectiontoggler']" "xpath_element" should be visible
    And "//li[@id='section-0']//a[@data-for='sectiontoggler' and @aria-expanded='true']" "xpath_element" should exist
    And I should see "Assignment in section 0" in the "li#section-0" "css_element"
    # The collapse toggle of section 1 is displayed and the section is expanded by default as well.
    And "//li[@id='section-1']//a[@data-for='sectiontoggler']" "xpath_element" should be visible
    And "//li[@id='section-1']//a[@data-for='sectiontoggler' and @aria-expanded='true']" "xpath_element" should exist
    And I should see "Assignment in section 1" in the "li#section-1" "css_element"

    Examples:
      | coursefullname | setting                  |
      | Course 1       | sectionzeroappearance    |
      | Course 2       | sectionzeroappearance    |
      | Course 1       | sectiononeplusappearance |
      | Course 2       | sectiononeplusappearance |

  @javascript
  Scenario Outline: Setting: Appearance of sections - Show a section with collapsing, collapsed by default
    Given the following config values are set as admin:
      | config    | value                | plugin            |
      | <setting> | collapsiblecollapsed | theme_boost_union |
    When I log in as "student1"
    And I am on "<coursefullname>" course homepage
    # The sections 0 and 1 exist.
    Then "li#section-0" "css_element" should exist
    And "li#section-0" "css_element" should be visible
    And "li#section-1" "css_element" should exist
    And "li#section-1" "css_element" should be visible
    # The collapse toggle of the configured section is displayed and the section is collapsed by default.
    And "//li[@id='section-<targetnum>']//a[@data-for='sectiontoggler']" "xpath_element" should be visible
    And "//li[@id='section-<targetnum>']//a[@data-for='sectiontoggler' and @aria-expanded='false']" "xpath_element" should exist
    And I should not see "Assignment in section <targetnum>" in the "li#section-<targetnum>" "css_element"
    # The other section should remain untouched and behave as usual (expanded by default).
    And "//li[@id='section-<othernum>']//a[@data-for='sectiontoggler']" "xpath_element" should be visible
    And "//li[@id='section-<othernum>']//a[@data-for='sectiontoggler' and @aria-expanded='true']" "xpath_element" should exist
    And I should see "Assignment in section <othernum>" in the "li#section-<othernum>" "css_element"
    # The user can still expand the configured section with the chevron.
    When I click on "//li[@id='section-<targetnum>']//a[@data-for='sectiontoggler']" "xpath_element"
    Then I should see "Assignment in section <targetnum>" in the "li#section-<targetnum>" "css_element"
    # But after a reload, the configured section is presented collapsed again.
    When I reload the page
    Then I should not see "Assignment in section <targetnum>" in the "li#section-<targetnum>" "css_element"
    And I should see "Assignment in section <othernum>" in the "li#section-<othernum>" "css_element"

    Examples:
      | coursefullname | setting                  | targetnum | othernum |
      | Course 1       | sectionzeroappearance    | 0         | 1        |
      | Course 2       | sectionzeroappearance    | 0         | 1        |
      | Course 1       | sectiononeplusappearance | 1         | 0        |
      | Course 2       | sectiononeplusappearance | 1         | 0        |

  @javascript
  Scenario Outline: Setting: Appearance of sections - Show a section without collapsing
    Given the following config values are set as admin:
      | config    | value          | plugin            |
      | <setting> | notcollapsible | theme_boost_union |
    When I log in as "student1"
    And I am on "<coursefullname>" course homepage
    # The sections 0 and 1 exist.
    Then "li#section-0" "css_element" should exist
    And "li#section-0" "css_element" should be visible
    And "li#section-1" "css_element" should exist
    And "li#section-1" "css_element" should be visible
    # The collapse toggle of the configured section is not displayed anymore (but kept in the DOM).
    And "//li[@id='section-<targetnum>']//a[@data-for='sectiontoggler']" "xpath_element" should exist
    And "//li[@id='section-<targetnum>']//a[@data-for='sectiontoggler']" "xpath_element" should not be visible
    And I should see "Assignment in section <targetnum>" in the "li#section-<targetnum>" "css_element"
    # The other section should remain untouched and behave as usual.
    And "//li[@id='section-<othernum>']//a[@data-for='sectiontoggler']" "xpath_element" should be visible
    And "//li[@id='section-<othernum>']//a[@data-for='sectiontoggler' and @aria-expanded='true']" "xpath_element" should exist
    And I should see "Assignment in section <othernum>" in the "li#section-<othernum>" "css_element"
    # Even the 'Collapse all' control does not collapse the configured section (but it collapses the other section).
    When I click on "#collapsesections" "css_element"
    Then I should see "Assignment in section <targetnum>" in the "li#section-<targetnum>" "css_element"
    And I should not see "Assignment in section <othernum>" in the "li#section-<othernum>" "css_element"

    Examples:
      | coursefullname | setting                  | targetnum | othernum |
      | Course 1       | sectionzeroappearance    | 0         | 1        |
      | Course 2       | sectionzeroappearance    | 0         | 1        |
      | Course 1       | sectiononeplusappearance | 1         | 0        |
      | Course 2       | sectiononeplusappearance | 1         | 0        |

  @javascript
  Scenario Outline: Setting: Appearance of section 0 - Hide section 0 entirely
    Given the following config values are set as admin:
      | config                | value  | plugin            |
      | sectionzeroappearance | hidden | theme_boost_union |
    When I log in as "student1"
    And I am on "<coursefullname>" course homepage
    # The section 1 exists, but the section 0 does not.
    Then "li#section-0" "css_element" should not exist
    And I should not see "Assignment in section 0"
    And "li#section-1" "css_element" should exist
    And "li#section-1" "css_element" should be visible
    # Section 0 is hidden in the course index as well.
    And ".courseindex-section[data-number='0']" "css_element" should not be visible
    # The 'Collapse all' control has been moved to section 1 (the new first section) and is still available there.
    And "Collapse all" "link" should exist in the "li#section-1" "css_element"
    # The section 1 should remain untouched and behave as usual.
    And "//li[@id='section-1']//a[@data-for='sectiontoggler']" "xpath_element" should be visible
    And "//li[@id='section-1']//a[@data-for='sectiontoggler' and @aria-expanded='true']" "xpath_element" should exist
    And I should see "Assignment in section 1" in the "li#section-1" "css_element"

    Examples:
      | coursefullname |
      | Course 1       |
      | Course 2       |

  @javascript
  Scenario Outline: Setting: Appearance of section 0 - Hide section 0 entirely, but show it again as soon as the teacher is editing the course
    Given the following config values are set as admin:
      | config                | value  | plugin            |
      | sectionzeroappearance | hidden | theme_boost_union |
    When I log in as "teacher1"
    And I am on "<coursefullname>" course homepage
    # The section 1 exists, but the section 0 does not.
    Then "li#section-0" "css_element" should not exist
    And I should not see "Assignment in section 0"
    And "li#section-1" "css_element" should exist
    And "li#section-1" "css_element" should be visible
    # Section 0 should re-appear when editing is enabled.
    When I turn editing mode on
    Then "li#section-0" "css_element" should exist
    And "li#section-0" "css_element" should be visible
    And I should see "Assignment in section 0" in the "li#section-0" "css_element"
    And ".courseindex-section[data-number='0']" "css_element" should be visible

    Examples:
      | coursefullname |
      | Course 1       |
      | Course 2       |

  @javascript
  Scenario Outline: Setting: Appearance of sections - Override the setting in an individual course
    Given the following config values are set as admin:
      | config                   | value               | plugin            |
      | <setting>                | collapsibleexpanded | theme_boost_union |
      | <setting>_courseoverride | 0                   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "<coursefullname>" course homepage
    And I click on "Settings" "link"
    And I expand all fieldsets
    And "#id_theme_boost_union_course_sectionshdr" "css_element" should not exist
    And the following config values are set as admin:
      | config                   | value | plugin            |
      | <setting>_courseoverride | 1     | theme_boost_union |
    And I reload the page
    And I expand all fieldsets
    And "#id_theme_boost_union_course_sectionshdr" "css_element" should exist
    And I should see "<settinglabel>" in the "#id_theme_boost_union_course_sectionshdr" "css_element"
    And I set the field "<settinglabel>" to "<settingvalue>"
    And I press "Save and display"
    And I log out
    And I log in as "student1"
    And I am on "<coursefullname>" course homepage
    # The course override takes effect: the configured section is collapsed, while the other section stays expanded.
    Then I should not see "Assignment in section <targetnum>" in the "li#section-<targetnum>" "css_element"
    And I should see "Assignment in section <othernum>" in the "li#section-<othernum>" "css_element"

    Examples:
      | coursefullname | setting                  | settinglabel              | settingvalue                                           | targetnum | othernum |
      | Course 1       | sectionzeroappearance    | Appearance of section 0   | Show section 0 with collapsing, collapsed by default   | 0         | 1        |
      | Course 2       | sectionzeroappearance    | Appearance of section 0   | Show section 0 with collapsing, collapsed by default   | 0         | 1        |
      | Course 1       | sectiononeplusappearance | Appearance of sections 1+ | Show sections 1+ with collapsing, collapsed by default | 1         | 0        |
      | Course 2       | sectiononeplusappearance | Appearance of sections 1+ | Show sections 1+ with collapsing, collapsed by default | 1         | 0        |

  @javascript
  Scenario: Setting: Appearance of section 0 - Do not offer the course override for unsupported course formats
    Given the following config values are set as admin:
      | config                               | value | plugin            |
      | sectionzeroappearance_courseoverride | 1     | theme_boost_union |
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 3 | C3        | social |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C3     | editingteacher |
    When I log in as "teacher1"
    And I am on "Course 3" course homepage
    And I click on "Settings" "link"
    And I expand all fieldsets
    Then "#id_theme_boost_union_course_sectionshdr" "css_element" should not exist
    And I should not see "Appearance of section 0"
    And I should not see "Appearance of sections 1+"

  @javascript
  Scenario Outline: Setting: Appearance of section 0 - The appearance exclusion list excludes selected appearances from being offered in the course settings
    Given the following config values are set as admin:
      | config                               | value               | plugin            |
      | sectionzeroappearance                | collapsibleexpanded | theme_boost_union |
      | sectionzeroappearanceexclusionlist   | <excluded>          | theme_boost_union |
      | sectionzeroappearance_courseoverride | 1                   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I expand all fieldsets
    Then the "Appearance of section 0" select box <hiddenshouldornot> contain "Hide section 0 entirely"
    And the "Appearance of section 0" select box <notcollapsibleshouldornot> contain "Show section 0 without collapsing"
    # The currently configured global appearance is never excluded, even if it is ticked in the exclusion list.
    And the "Appearance of section 0" select box should contain "Show section 0 with collapsing, expanded by default"

    Examples:
      | excluded                                  | hiddenshouldornot | notcollapsibleshouldornot |
      # Excluding a single appearance (which is not the global one) removes it from the course settings.
      | hidden                                    | should not        | should                    |
      # Excluding the currently set global appearance has no effect, it stays available.
      | collapsibleexpanded                       | should            | should                    |
      # Excluding multiple appearances (one of them being the global one) removes only the non-global ones.
      | hidden,notcollapsible,collapsibleexpanded | should not        | should not                |

  @javascript
  Scenario: Setting: Appearance of section 0 - An already-set excluded appearance falls back to the global appearance
    Given the following config values are set as admin:
      | config                               | value               | plugin            |
      | sectionzeroappearance                | collapsibleexpanded | theme_boost_union |
      | sectionzeroappearance_courseoverride | 1                   | theme_boost_union |
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I click on "Settings" "link"
    And I expand all fieldsets
    And I set the field "Appearance of section 0" to "Hide section 0 entirely"
    And I press "Save and display"
    # The course override takes effect: section 0 is hidden.
    Then "li#section-0" "css_element" should not exist
    # Now the admin excludes the 'hidden' appearance from being available for course-specific overrides.
    And the following config values are set as admin:
      | config                             | value  | plugin            |
      | sectionzeroappearanceexclusionlist | hidden | theme_boost_union |
    And I reload the page
    # The course now falls back to the global appearance (expanded by default), so section 0 is shown again.
    Then "li#section-0" "css_element" should exist
    And I should see "Assignment in section 0" in the "li#section-0" "css_element"
