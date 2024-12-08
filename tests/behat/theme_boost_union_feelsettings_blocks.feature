@theme @theme_boost_union @theme_boost_union_feelsettings @theme_boost_union_feelsettings_blocks
Feature: Configuring the theme_boost_union plugin for the "Blocks" tab on the "Feel" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | student1 |
      | teacher1 |
    And the following "categories" exist:
      | name        | category | idnumber |
      | Category A  | 0        | CATA     |
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1        | topics |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  Scenario Outline: Setting: Enable additional block regions (on a course page and the frontpage where all regions are offered)
    When I am on the "Acceptance test site" "Course" page logged in as "admin"
    And I turn editing mode on
    Then "#theme-block-region-<region>" "css_element" should not exist
    And the following config values are set as admin:
      | config                   | value          | plugin            |
      | blockregionsforfrontpage | <settingvalue> | theme_boost_union |
    And I reload the page
    Then "#theme-block-region-<region>" "css_element" <should> exist
    And I am on "Course 1" course homepage with editing mode on
    Then "#theme-block-region-<region>" "css_element" should not exist
    And the following config values are set as admin:
      | config                | value          | plugin            |
      | blockregionsforcourse | <settingvalue> | theme_boost_union |
    And I reload the page
    Then "#theme-block-region-<region>" "css_element" <should> exist

    Examples:
      | region           | settingvalue                                                                                                                                                                    | should     |
      | outside-top      | outside-top                                                                                                                                                                     | should     |
      | outside-left     | outside-left                                                                                                                                                                    | should     |
      | outside-right    | outside-right                                                                                                                                                                   | should     |
      | outside-bottom   | outside-bottom                                                                                                                                                                  | should     |
      | footer-left      | footer-left                                                                                                                                                                     | should     |
      | footer-right     | footer-right                                                                                                                                                                    | should     |
      | footer-center    | footer-center                                                                                                                                                                   | should     |
      | content-upper    | content-upper                                                                                                                                                                   | should     |
      | content-lower    | content-lower                                                                                                                                                                   | should     |
      | header           | header                                                                                                                                                                          | should     |
      | outside-top      | outside-top,outside-bottom                                                                                                                                                      | should     |
      | outside-top      | outside-top,outside-left,outside-right,outside-bottom,footer-left,footer-right,footer-center,offcanvas-left,offcanvas-right,offcanvas-center,content-upper,content-lower,header | should     |
      | outside-top      | outside-bottom                                                                                                                                                                  | should not |
      | outside-top      | footer-left,footer-right                                                                                                                                                        | should not |

  Scenario Outline: Setting: Enable additional block regions (on the Dashboard page where all but the content-* regions are offered)
    When I am on the "Homepage" page logged in as "admin"
    And I turn editing mode on
    Then "#theme-block-region-<region>" "css_element" should not exist
    And the following config values are set as admin:
      | config                     | value          | plugin            |
      | blockregionsformydashboard | <settingvalue> | theme_boost_union |
    And I reload the page
    Then "#theme-block-region-<region>" "css_element" <should> exist

    Examples:
      | region           | settingvalue                                                                                                                                        | should     |
      | outside-top      | outside-top                                                                                                                                         | should     |
      | outside-left     | outside-left                                                                                                                                        | should     |
      | outside-right    | outside-right                                                                                                                                       | should     |
      | outside-bottom   | outside-bottom                                                                                                                                      | should     |
      | footer-left      | footer-left                                                                                                                                         | should     |
      | footer-right     | footer-right                                                                                                                                        | should     |
      | footer-center    | footer-center                                                                                                                                       | should     |
      | content-upper    | outside-top                                                                                                                                         | should not |
      | content-lower    | outside-top                                                                                                                                         | should not |
      | header           | header                                                                                                                                              | should     |
      | outside-top      | outside-top,outside-bottom                                                                                                                          | should     |
      | outside-top      | outside-top,outside-left,outside-right,outside-bottom,footer-left,footer-right,footer-center,offcanvas-left,offcanvas-right,offcanvas-center,header | should     |
      | outside-top      | outside-bottom                                                                                                                                      | should not |
      | outside-top      | footer-left,footer-right                                                                                                                            | should not |

  Scenario Outline: Setting: Enable additional block regions (on the admin overview page where not all regions are offered)
    When I log in as "admin"
    And I follow "Site administration"
    And I turn editing mode on
    Then "#theme-block-region-<region>" "css_element" should not exist
    And the following config values are set as admin:
      | config               | value          | plugin            |
      | blockregionsforadmin | <settingvalue> | theme_boost_union |
    And I reload the page
    Then "#theme-block-region-<region>" "css_element" <should> exist

    Examples:
      | region           | settingvalue                                                                                                      | should     |
      | outside-top      | outside-top                                                                                                       | should     |
      | outside-bottom   | outside-bottom                                                                                                    | should     |
      | footer-left      | footer-left                                                                                                       | should     |
      | footer-right     | footer-right                                                                                                      | should     |
      | footer-center    | footer-center                                                                                                     | should     |
      | outside-left     | outside-top                                                                                                       | should not |
      | outside-right    | outside-top                                                                                                       | should not |
      | content-upper    | outside-top                                                                                                       | should not |
      | content-lower    | outside-top                                                                                                       | should not |
      | header           | outside-top                                                                                                       | should not |
      | outside-top      | outside-top,outside-bottom                                                                                        | should     |
      | outside-top      | outside-top,outside-bottom,footer-left,footer-right,footer-center,offcanvas-left,offcanvas-right,offcanvas-center | should     |
      | outside-top      | outside-bottom                                                                                                    | should not |
      | outside-top      | footer-left,footer-right                                                                                          | should not |

  Scenario Outline: Setting: Use additional block regions (on a course page and the frontpage where all regions are offered)
    Given the following config values are set as admin:
      | config                   | value          | plugin            |
      | blockregionsforfrontpage | <settingvalue> | theme_boost_union |
      | blockregionsforcourse    | <settingvalue> | theme_boost_union |
    And the following "blocks" exist:
      | blockname      | contextlevel | reference            | pagetypepattern | defaultregion |
      | online_users   | Course       | Acceptance test site | site-index      | <region>      |
      | calendar_month | Course       | C1                   | course-view-*   | <region>      |
    When I am on the "Acceptance test site" "Course" page logged in as "admin"
    Then I should see "Online users" in the "#theme-block-region-<region>" "css_element"
    When I am on "Course 1" course homepage
    Then I should see "Calendar" in the "#theme-block-region-<region>" "css_element"

    Examples:
      | region         | settingvalue   |
      | outside-top    | outside-top    |
      | outside-left   | outside-left   |
      | outside-right  | outside-right  |
      | outside-bottom | outside-bottom |
      | footer-left    | footer-left    |
      | footer-right   | footer-right   |
      | footer-center  | footer-center  |
      | content-upper  | content-upper  |
      | content-lower  | content-lower  |
      | header         | header         |

  Scenario Outline: Setting: Use additional block regions (on the Dashboard page where all but the content-* regions are offered)
    Given the following config values are set as admin:
      | config                     | value          | plugin            |
      | blockregionsformydashboard | <settingvalue> | theme_boost_union |
    And the following "blocks" exist:
      | blockname      | contextlevel | reference | pagetypepattern | defaultregion |
      | online_users   | System       | 1         | my-index        | <region>      |
    When I am on the "Homepage" page logged in as "admin"
    Then I should see "Online users" in the "#theme-block-region-<region>" "css_element"

    Examples:
      | region         | settingvalue   |
      | outside-top    | outside-top    |
      | outside-left   | outside-left   |
      | outside-right  | outside-right  |
      | outside-bottom | outside-bottom |
      | footer-left    | footer-left    |
      | footer-right   | footer-right   |
      | footer-center  | footer-center  |
      | header         | header         |

  Scenario Outline: Setting: Use additional block regions (on the admin overview page where not all regions are offered)
    Given the following config values are set as admin:
      | config               | value          | plugin            |
      | blockregionsforadmin | <settingvalue> | theme_boost_union |
    And the following "blocks" exist:
      | blockname      | contextlevel | reference | pagetypepattern | defaultregion |
      | online_users   | System       | 1         | admin-search    | <region>      |
    When I log in as "admin"
    And I follow "Site administration"
    Then I should see "Online users" in the "#theme-block-region-<region>" "css_element"

    Examples:
      | region         | settingvalue   |
      | outside-top    | outside-top    |
      | outside-bottom | outside-bottom |
      | footer-left    | footer-left    |
      | footer-right   | footer-right   |
      | footer-center  | footer-center  |

  @javascript
  Scenario Outline: Setting: Enable and use the off-canvas block regions (Compared to the other regions, these regions are slightly different and thus they are not covered in the Scenario outlines above).
    When I am on the "Acceptance test site" "Course" page logged in as "admin"
    Then "#theme_boost_union-offcanvas-btn" "css_element" should not exist
    And "#theme_boost_union-drawers-offcanvas" "css_element" should not exist
    And "#theme-block-region-offcanvas-editing" "css_element" should not be visible
    And "#theme-block-region-<region>" "css_element" should not exist
    And I turn editing mode on
    Then "#theme_boost_union-offcanvas-btn" "css_element" should not exist
    And "#theme_boost_union-drawers-offcanvas" "css_element" should not exist
    And "#theme-block-region-offcanvas-editing" "css_element" should not be visible
    And "#theme-block-region-<region>" "css_element" should not exist
    And I turn editing mode off
    And the following config values are set as admin:
      | config                   | value          | plugin            |
      | blockregionsforfrontpage | <settingvalue> | theme_boost_union |
    And I reload the page
    Then "#theme_boost_union-offcanvas-btn" "css_element" should not exist
    And "#theme_boost_union-drawers-offcanvas" "css_element" should not exist
    And "#theme-block-region-offcanvas-editing" "css_element" should not be visible
    And "#theme-block-region-<region>" "css_element" should not exist
    And I turn editing mode on
    Then "#theme_boost_union-offcanvas-btn" "css_element" should exist
    And "#theme_boost_union-drawers-offcanvas" "css_element" should not exist
    And "#theme-block-region-offcanvas-editing" "css_element" should not be visible
    And "#theme-block-region-<region>" "css_element" should exist
    And "#theme-block-region-<region>" "css_element" should not be visible
    And I click on "#theme_boost_union-offcanvas-btn" "css_element"
    And "#theme_boost_union-drawers-offcanvas" "css_element" should not exist
    And "#theme-block-region-offcanvas-editing" "css_element" should be visible
    And "#theme-block-region-<region>" "css_element" should exist
    And "#theme-block-region-<region>" "css_element" should be visible
    And I should see "Add a block" in the "#theme-block-region-<region>" "css_element"
    And the following "blocks" exist:
      | blockname      | contextlevel | reference            | pagetypepattern | defaultregion |
      | online_users   | Course       | Acceptance test site | site-index      | <region>      |
    And I am on site homepage
    And I turn editing mode off
    Then "#theme_boost_union-offcanvas-btn" "css_element" should exist
    And "#theme_boost_union-drawers-offcanvas" "css_element" should exist
    And "#theme-block-region-offcanvas-editing" "css_element" should not be visible
    And "#theme-block-region-<region>" "css_element" should exist
    And "#theme-block-region-<region>" "css_element" should not be visible
    And I click on "#theme_boost_union-offcanvas-btn" "css_element"
    Then "#theme_boost_union-drawers-offcanvas" "css_element" should exist
    And "#theme-block-region-offcanvas-editing" "css_element" should not be visible
    And "#theme-block-region-<region>" "css_element" should exist
    And "#theme-block-region-<region>" "css_element" should be visible
    And I should see "Online users" in the "#theme-block-region-<region>" "css_element"

    Examples:
      | region           | settingvalue                                                                                                                                 |
      | offcanvas-left   | offcanvas-left                                                                                                                               |
      | offcanvas-right  | offcanvas-right                                                                                                                              |
      | offcanvas-center | offcanvas-center                                                                                                                             |
      | offcanvas-left   | outside-top,outside-left,outside-right,outside-bottom,footer-left,footer-right,footer-center,offcanvas-left,offcanvas-center,offcanvas-right |

  Scenario Outline: Setting: Set capabilities to control the editability of additional block regions (for all regions except offcanvas regions)
    Given the following config values are set as admin:
      | config                | value    | plugin            |
      | blockregionsforcourse | <region> | theme_boost_union |
    And the following "blocks" exist:
      | blockname      | contextlevel | reference | pagetypepattern | defaultregion |
      | online_users   | Course       | C1        | course-view-*   | <region>      |
    When I am on the "Course 1" "Course" page logged in as "teacher1"
    And I turn editing mode on
    Then "#theme-block-region-<region>" "css_element" should exist
    And I should see "Add a block" in the "#theme-block-region-<region>" "css_element"
    And the following "permission overrides" exist:
      | capability                         | permission | role           | contextlevel | reference |
      | theme/boost_union:<editcapability> | Prevent    | editingteacher | System       |           |
    And I reload the page
    Then "#theme-block-region-<region>" "css_element" should exist
    And I should not see "Add a block" in the "#theme-block-region-<region>" "css_element"

    Examples:
      | region         | editcapability          |
      | outside-left   | editregionoutsideleft   |
      | outside-right  | editregionoutsideright  |
      | outside-top    | editregionoutsidetop    |
      | outside-bottom | editregionoutsidebottom |
      | footer-left    | editregionfooterleft    |
      | footer-right   | editregionfooterright   |
      | footer-center  | editregionfootercenter  |
      | content-upper  | editregioncontentupper  |
      | content-lower  | editregioncontentlower  |
      | header         | editregionheader        |

  @javascript
  Scenario Outline: Setting: Set capabilities to control the editability of additional block regions (for offcanvas regions)
    Given the following config values are set as admin:
      | config                | value    | plugin            |
      | blockregionsforcourse | <region> | theme_boost_union |
    And the following "blocks" exist:
      | blockname      | contextlevel | reference | pagetypepattern | defaultregion |
      | online_users   | Course       | C1        | course-view-*   | <region>      |
    When I am on the "Course 1" "Course" page logged in as "teacher1"
    And I turn editing mode on
    And I click on "#theme_boost_union-offcanvas-btn" "css_element"
    Then "#theme-block-region-<region>" "css_element" should exist
    And I should see "Add a block" in the "#theme-block-region-<region>" "css_element"
    And the following "permission overrides" exist:
      | capability                         | permission | role           | contextlevel | reference |
      | theme/boost_union:<editcapability> | Prevent    | editingteacher | System       |           |
    And I reload the page
    And I click on "#theme_boost_union-offcanvas-btn" "css_element"
    Then "#theme-block-region-<region>" "css_element" should exist
    And I should not see "Add a block" in the "#theme-block-region-<region>" "css_element"

    Examples:
      | region           | editcapability            |
      | offcanvas-left   | editregionoffcanvasleft   |
      | offcanvas-center | editregionoffcanvascenter |
      | offcanvas-right  | editregionoffcanvasright  |

  Scenario Outline: Setting: Set capabilities to control the visibility of additional block regions (for all regions except offcanvas regions)
    Given the following config values are set as admin:
      | config                | value    | plugin            |
      | blockregionsforcourse | <region> | theme_boost_union |
    And the following "blocks" exist:
      | blockname      | contextlevel | reference | pagetypepattern | defaultregion |
      | online_users   | Course       | C1        | course-view-*   | <region>      |
    When I am on the "Course 1" "Course" page logged in as "teacher1"
    Then "#theme-block-region-<region>" "css_element" should exist
    And I should see "Online users" in the "#theme-block-region-<region>" "css_element"
    And the following "permission overrides" exist:
      | capability                         | permission | role           | contextlevel | reference |
      | theme/boost_union:<viewcapability> | Prevent    | user           | System       |           |
      | theme/boost_union:<viewcapability> | Prevent    | editingteacher | System       |           |
    And I reload the page
    Then "#theme-block-region-<region>" "css_element" should not exist

    Examples:
      | region         | viewcapability          |
      | outside-left   | viewregionoutsideleft   |
      | outside-right  | viewregionoutsideright  |
      | outside-top    | viewregionoutsidetop    |
      | outside-bottom | viewregionoutsidebottom |
      | footer-left    | viewregionfooterleft    |
      | footer-right   | viewregionfooterright   |
      | footer-center  | viewregionfootercenter  |
      | content-upper  | viewregioncontentupper  |
      | content-lower  | viewregioncontentlower  |
      | header         | viewregionheader        |

  @javascript
  Scenario Outline: Setting: Set capabilities to control the visibility of additional block regions (for offcanvas regions)
    Given the following config values are set as admin:
      | config                | value    | plugin            |
      | blockregionsforcourse | <region> | theme_boost_union |
    And the following "blocks" exist:
      | blockname      | contextlevel | reference | pagetypepattern | defaultregion |
      | online_users   | Course       | C1        | course-view-*   | <region>      |
    When I am on the "Course 1" "Course" page logged in as "teacher1"
    And I click on "#theme_boost_union-offcanvas-btn" "css_element"
    Then "#theme-block-region-<region>" "css_element" should exist
    And I should see "Online users" in the "#theme-block-region-<region>" "css_element"
    And the following "permission overrides" exist:
      | capability                         | permission | role           | contextlevel | reference |
      | theme/boost_union:<viewcapability> | Prevent    | user           | System       |           |
      | theme/boost_union:<viewcapability> | Prevent    | editingteacher | System       |           |
    And I reload the page
    Then "#theme_boost_union-offcanvas-btn" "css_element" should not exist
    And "#theme-block-region-<region>" "css_element" should not exist

    Examples:
      | region           | viewcapability            |
      | offcanvas-left   | viewregionoffcanvasleft   |
      | offcanvas-center | viewregionoffcanvascenter |
      | offcanvas-right  | viewregionoffcanvasright  |

  @javascript
  Scenario Outline: Setting: Block region width for 'Outside (left/right)' regions
    Given the following config values are set as admin:
      | config                | value          | plugin            |
      | blockregionsforcourse | <region>       | theme_boost_union |
      | <config>              | <settingvalue> | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I am on the "Course 1" "Course" page logged in as "admin"
    And I turn editing mode on
    And I should see "Add a block" in the "#theme-block-region-<region>" "css_element"
    Then DOM element "#theme-block-region-<region>" should have computed style "width" "<settingvalue>"

    Examples:
      | region        | config                       | settingvalue |
      | outside-left  | blockregionoutsideleftwidth  | 300px        |
      | outside-left  | blockregionoutsideleftwidth  | 400px        |
      | outside-right | blockregionoutsiderightwidth | 300px        |
      | outside-right | blockregionoutsiderightwidth | 400px        |

  Scenario Outline: Setting: Block region width for 'Outside (top/bottom)' regions
    Given the following config values are set as admin:
      | config                | value          | plugin            |
      | blockregionsforcourse | <region>       | theme_boost_union |
      | <config>              | <settingvalue> | theme_boost_union |
    When I am on the "Course 1" "Course" page logged in as "admin"
    And I turn editing mode on
    And I should see "Add a block" in the "#theme-block-region-<region>" "css_element"
    Then the "class" attribute of "#theme-block-region-<region>" "css_element" should contain "theme-block-region-outside-<settingvalue>"

    Examples:
      | region         | config                        | settingvalue       |
      | outside-top    | blockregionoutsidetopwidth    | fullwidth          |
      | outside-top    | blockregionoutsidetopwidth    | coursecontentwidth |
      | outside-top    | blockregionoutsidetopwidth    | herowidth          |
      | outside-bottom | blockregionoutsidebottomwidth | fullwidth          |
      | outside-bottom | blockregionoutsidebottomwidth | coursecontentwidth |
      | outside-bottom | blockregionoutsidebottomwidth | herowidth          |

  Scenario Outline: Setting: Block region width for 'Footer' regions
    Given the following config values are set as admin:
      | config                | value          | plugin            |
      | blockregionsforcourse | footer-left    | theme_boost_union |
      | <config>              | <settingvalue> | theme_boost_union |
    When I am on the "Course 1" "Course" page logged in as "admin"
    And I turn editing mode on
    And I should see "Add a block" in the "#theme-block-region-footer-left" "css_element"
    Then the "class" attribute of "#theme-block-region-footer" "css_element" should contain "theme-block-region-footer-<settingvalue>"

    Examples:
      | config                        | settingvalue       |
      | blockregionfooterwidth        | fullwidth          |
      | blockregionfooterwidth        | coursecontentwidth |
      | blockregionfooterwidth        | herowidth          |

  Scenario Outline: Setting: Outside regions horizontal placement
    Given the following config values are set as admin:
      | config                  | value          | plugin            |
      | outsideregionsplacement | <settingvalue> | theme_boost_union |
    When I am on the "Acceptance test site" "Course" page logged in as "admin"
    And I turn editing mode on
    Then the "class" attribute of ".main-inner-wrapper" "css_element" should contain "<classcontain>"
    And the "class" attribute of ".main-inner-wrapper" "css_element" should not contain "<classnotcontain>"

    Examples:
      | settingvalue    | classcontain                       | classnotcontain                    |
      | nextmaincontent | main-inner-outside-nextmaincontent | main-inner-outside-nearwindowedges |
      | nearwindowedges | main-inner-outside-nearwindowedges | main-inner-outside-nextmaincontent |

  Scenario: Verify orders of all block regions
    Given the following config values are set as admin:
      | config                   | value                                                                                                                                                                           | plugin            |
      | blockregionsforfrontpage | outside-top,outside-left,outside-right,outside-bottom,footer-left,footer-right,footer-center,offcanvas-left,offcanvas-right,offcanvas-center,content-upper,content-lower,header | theme_boost_union |
    When I am on the "Acceptance test site" "Course" page logged in as "admin"
    And I turn editing mode on
    Then "#theme-block-region-offcanvas-editing" "css_element" should appear before "#theme-block-region-outside-top" "css_element"
    And "#theme-block-region-outside-top" "css_element" should appear before "#theme-block-region-header" "css_element"
    And "#theme-block-region-header" "css_element" should appear before ".main-inner-wrapper" "css_element"
    And ".main-inner-wrapper" "css_element" should appear before "#theme-block-region-outside-bottom" "css_element"
    And "#theme-block-region-outside-bottom" "css_element" should appear before "#theme-block-region-footer" "css_element"
    And "#theme-block-region-offcanvas-editing #theme-block-region-offcanvas-left" "css_element" should exist
    And "#theme-block-region-offcanvas-editing #theme-block-region-offcanvas-center" "css_element" should exist
    And "#theme-block-region-offcanvas-editing #theme-block-region-offcanvas-right" "css_element" should exist
    And ".main-inner-wrapper #theme-block-region-content-upper" "css_element" should exist
    And ".main-inner-wrapper #theme-block-region-content-lower" "css_element" should exist
    And "#theme-block-region-footer #theme-block-region-footer-left" "css_element" should exist
    And "#theme-block-region-footer #theme-block-region-footer-center" "css_element" should exist
    And "#theme-block-region-footer #theme-block-region-footer-right" "css_element" should exist
    And "#theme-block-region-offcanvas-left" "css_element" should appear before "#theme-block-region-offcanvas-center" "css_element"
    And "#theme-block-region-offcanvas-center" "css_element" should appear before "#theme-block-region-offcanvas-right" "css_element"
    And "#theme-block-region-content-upper" "css_element" should appear before "#page-content" "css_element"
    And "#page-content" "css_element" should appear before "#theme-block-region-content-lower" "css_element"
    And "#theme-block-region-footer-left" "css_element" should appear before "#theme-block-region-footer-center" "css_element"
    And "#theme-block-region-footer-center" "css_element" should appear before "#theme-block-region-footer-right" "css_element"

  Scenario Outline: Setting: Show right-hand block drawer of site home on first login
    Given the following config values are set as admin:
      | config                                       | value     | plugin            |
      | showsitehomerighthandblockdraweronfirstlogin | <setting> | theme_boost_union |
    And the following "blocks" exist:
      | blockname    | contextlevel | reference            | pagetypepattern | defaultregion |
      | online_users | Course       | Acceptance test site | site-index      | side-pre      |
    When I am on the "Acceptance test site" "Course" page logged in as "student1"
    Then the "class" attribute of ".drawer-right" "css_element" <shouldcontain> "show"

    Examples:
      | setting | shouldcontain      |
      | yes     | should contain     |
      | no      | should not contain |

  Scenario Outline: Setting: Show right-hand block drawer of site home on visit
    Given the following config values are set as admin:
      | config                                  | value     | plugin            |
      | showsitehomerighthandblockdraweronvisit | <setting> | theme_boost_union |
    And the following "blocks" exist:
      | blockname    | contextlevel | reference            | pagetypepattern | defaultregion |
      | online_users | Course       | Acceptance test site | site-index      | side-pre      |
    When I am on site homepage
    Then the "class" attribute of ".drawer-right" "css_element" <shouldcontain> "show"

    Examples:
      | setting | shouldcontain      |
      | yes     | should contain     |
      | no      | should not contain |

  Scenario Outline: Setting: Show right-hand block drawer of site home on guest login
    Given the following config values are set as admin:
      | config                                       | value     | plugin            |
      | showsitehomerighthandblockdraweronguestlogin | <setting> | theme_boost_union |
    And the following "blocks" exist:
      | blockname    | contextlevel | reference            | pagetypepattern | defaultregion |
      | online_users | Course       | Acceptance test site | site-index      | side-pre      |
    When I log in as "guest"
    And I am on site homepage
    Then the "class" attribute of ".drawer-right" "css_element" <shouldcontain> "show"

    Examples:
      | setting | shouldcontain      |
      | yes     | should contain     |
      | no      | should not contain |
