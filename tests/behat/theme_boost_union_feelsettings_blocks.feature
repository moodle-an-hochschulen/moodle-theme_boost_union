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
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1        | topics |
    And the following "activities" exist:
      | activity   | name                   | intro                         | course | idnumber    | section |
      | assign     | Test assignment name   | Test assignment description   | C1     | assign1     | 0       |
      | book       | Test book name         | Test book description         | C1     | book1       | 0       |
      | chat       | Test chat name         | Test chat description         | C1     | chat1       | 1       |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  @javascript
  Scenario Outline: Additional Boost Union block regions: left, right, top, bottom, footerleft, footerright, footercenter.
    When I log in as "admin"
    And I turn editing mode on
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    And I should see "Add a block" in the "<regionselectorwithanchor>" "css_element"
    Then I click on "Add a block" "link" in the "<regionselectorwithanchor>" "css_element"
    Then I should see "Online users"
    Then I click on "Online users" "link"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    Then I press "Continue"
    Then I should see "Online users" in the "<regionselectorwithanchor>" "css_element"
    And I am on site homepage
    And I should see "Add a block" in the "<regionselectorwithanchor>" "css_element"
    Then I click on "Add a block" "link" in the "<regionselectorwithanchor>" "css_element"
    And I should see "Calendar" in the ".modal-body" "css_element"
    Then I click on "Calendar" "link" in the ".modal-body" "css_element"
    Then I should see "Calendar" in the "<regionselectorwithanchor>" "css_element"
    And I am on "Course 1" course homepage
    And I should see "Add a block" in the "<regionselectorwithanchor>" "css_element"
    Then I click on "Add a block" "link" in the "<regionselectorwithanchor>" "css_element"
    Then I should see "Activities"
    Then I click on "Activities" "link"
    Then I should see "Activities" in the "<regionselectorwithanchor>" "css_element"
    And the following "permission overrides" exist:
      | capability                         | permission | role    | contextlevel | reference |
      | theme/boost_union:<editcapability> | Allow      | user    | System       |           |
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should see "Online users" in the "<regionselectorwithanchor>" "css_element"
    And I turn editing mode on
    And I should see "Add a block" in the "<regionselectorwithanchor>" "css_element"
    Then I click on "Add a block" "link" in the "<regionselectorwithanchor>" "css_element"
    Then I should see "Logged in user"
    And I click on "Logged in user" "link"
    Then I should see "Logged in user" in the "<regionselectorwithanchor>" "css_element"
    And I am on site homepage
    Then I should see "Calendar" in the "<regionselectorwithanchor>" "css_element"
    And I am on "Course 1" course homepage
    Then I should see "Activities" in the "<regionselectorwithanchor>" "css_element"
    And the following "permission overrides" exist:
      | capability                         | permission | role    | contextlevel | reference |
      | theme/boost_union:<viewcapability> | Prevent    | user    | System       |           |
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should not see "Online users"
    Then "<regionselectorwithanchor>" "css_element" should not exist
    And I am on site homepage
    Then I should not see "Calendar"
    Then "<regionselectorwithanchor>" "css_element" should not exist
    And I am on "Course 1" course homepage
    Then I should not see "Activities"
    Then "<regionselectorwithanchor>" "css_element" should not exist

    Examples:
      | regionselectorwithanchor                 | viewcapability         | editcapability         |
      | .block-region-left#leftblock             | viewregionleft         | editregionleft         |
      | .block-region-right#rightblock           | viewregionright        | editregionright        |
      | .block-region-top#topregion              | viewregiontop          | editregiontop          |
      | .block-region-bottom#bottomregion        | viewregionbottom       | editregionbottom       |
      | .block-region-footer-left#footerleft     | viewregionfooterleft   | editregionfooterleft   |
      | .block-region-footer-right#footerright   | viewregionfooterright  | editregionfooterright  |
      | .block-region-footer-center#footercenter | viewregionfootercenter | editregionfootercenter |

  @javascript
  Scenario: Additional Boost Union block region: headertop (Compared to the outside and footer regions, this test is more compact and thus not included in the Scenario outline above).
    When I log in as "admin"
    And I turn editing mode on
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    Then ".block-region-headertop#headertopregion" "css_element" should not exist
    And I am on site homepage
    And I should see "Add a block" in the ".block-region-headertop#headertopregion" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-headertop#headertopregion" "css_element"
    And I should see "Calendar" in the ".modal-body" "css_element"
    Then I click on "Calendar" "link" in the ".modal-body" "css_element"
    Then I should see "Calendar" in the ".block-region-headertop#headertopregion" "css_element"
    And I am on "Course 1" course homepage
    And I should see "Add a block" in the ".block-region-headertop#headertopregion" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-headertop#headertopregion" "css_element"
    Then I should see "Activities"
    Then I click on "Activities" "link"
    Then I should see "Activities" in the ".block-region-headertop#headertopregion" "css_element"
    And the following "permission overrides" exist:
      | capability                            | permission | role    | contextlevel | reference |
      | theme/boost_union:editregionheadertop | Allow      | user    | System       |           |
    And I log in as "student1"
    Then I follow "Dashboard"
    Then ".block-region-headertop#headertopregion" "css_element" should not exist
    And I am on site homepage
    Then I should see "Calendar" in the ".block-region-headertop#headertopregion" "css_element"
    And I am on "Course 1" course homepage
    Then I should see "Activities" in the ".block-region-headertop#headertopregion" "css_element"
    And the following "permission overrides" exist:
      | capability                            | permission | role    | contextlevel | reference |
      | theme/boost_union:viewregionheadertop | Prevent    | user    | System       |           |
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should not see "Online users"
    Then ".block-region-headertop#headertopregion" "css_element" should not exist
    And I am on site homepage
    Then I should not see "Calendar"
    Then ".block-region-headertop#headertopregion" "css_element" should not exist
    And I am on "Course 1" course homepage
    Then I should not see "Activities"
    Then ".block-region-headertop#headertopregion" "css_element" should not exist

  @javascript
  Scenario: Additional Boost Union block region: offcanvas (Compared to the outside and footer regions, this test is slightly different and thus not included in the Scenario outline above).
    When I log in as "admin"
    And I turn editing mode on
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    Then I click on "#theme_boost_union-offcanvas-btn" "css_element"
    And I should see "Add a block" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I should see "Online users"
    Then I click on "Online users" "link"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    Then I press "Continue"
    And I turn editing mode off
    Then I click on "#theme_boost_union-offcanvas-btn" "css_element"
    Then I should see "Online users" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I am on site homepage
    And I turn editing mode on
    Then I click on "#theme_boost_union-offcanvas-btn" "css_element"
    And I should see "Add a block" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I should see "Calendar" in the ".modal-body" "css_element"
    Then I click on "Calendar" "link" in the ".modal-body" "css_element"
    And I turn editing mode off
    And I click on "#theme_boost_union-offcanvas-btn" "css_element"
    Then I should see "Calendar" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    Then I click on "#theme_boost_union-offcanvas-btn" "css_element"
    And I should see "Add a block" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I should see "Activities"
    Then I click on "Activities" "link"
    And I turn editing mode off
    And I click on "#theme_boost_union-offcanvas-btn" "css_element"
    Then I should see "Activities" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And the following "permission overrides" exist:
      | capability                                | permission | role    | contextlevel | reference |
      | theme/boost_union:editregionoffcanvasleft | Allow      | user    | System       |           |
    And I log in as "student1"
    Then I follow "Dashboard"
    And I click on "#theme_boost_union-offcanvas-btn" "css_element"
    Then I should see "Online users" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I am on site homepage
    Then I follow "Dashboard"
    And I turn editing mode on
    Then I click on "#theme_boost_union-offcanvas-btn" "css_element"
    And I should see "Add a block" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I should see "Logged in user"
    And I click on "Logged in user" "link"
    And I turn editing mode off
    And I click on "#theme_boost_union-offcanvas-btn" "css_element"
    Then I should see "Logged in user" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I am on site homepage
    And I click on "#theme_boost_union-offcanvas-btn" "css_element"
    Then I should see "Calendar" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I am on "Course 1" course homepage
    And I click on "#theme_boost_union-offcanvas-btn" "css_element"
    Then I should see "Activities" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And the following "permission overrides" exist:
      | capability                                | permission | role    | contextlevel | reference |
      | theme/boost_union:viewregionoffcanvasleft | Prevent    | user    | System       |           |
    And I log in as "student1"
    Then I follow "Dashboard"
    And "#theme_boost_union-offcanvas-btn" "css_element" should not exist
    And I am on site homepage
    And "#theme_boost_union-offcanvas-btn" "css_element" should not exist
    And I am on "Course 1" course homepage
    And "#theme_boost_union-offcanvas-btn" "css_element" should not exist

  Scenario: Setting: Outside regions placement on larger screens
    Given the following config values are set as admin:
      | config                  | value | plugin            |
      | outsideregionsplacement | 0     | theme_boost_union |
    When I log in as "admin"
    And I am on site homepage
    Then the "class" attribute of "#page" "css_element" should contain "blocks-next-maincontent"
    And the "class" attribute of "#page" "css_element" should not contain "blocks-near-windowedges"
    And the following config values are set as admin:
      | config                  | value | plugin            |
      | outsideregionsplacement | 1     | theme_boost_union |
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    And I am on site homepage
    Then the "class" attribute of "#page" "css_element" should contain "blocks-near-windowedges"
    And the "class" attribute of "#page" "css_element" should not contain "blocks-next-maincontent"

  @javascript
  Scenario: Setting: Outside (left) block region width - Setting the width
    Given I log in as "admin"
    And I turn editing mode on
    And I navigate to "Appearance > Themes > Boost Union > Feel" in site administration
    Then I click on "Blocks" "link"
    And I should see "Block regions for dashboard layout"
    And I set the following fields to these values:
      | Block regions for dashboard layout| outside-left, outside-right|
      | Outside (left) block region width | 400px                      |
    Then I press "Save changes"
    And I follow "Dashboard"
    Then I click on "Add a block" "link" in the ".block-region-left#leftblock" "css_element"
    Then I should see "Online users"
    Then I click on "Online users" "link"
    Then I should see "Online users" in the ".block-region-left#leftblock" "css_element"
    Then Boostunion ".block-region-left.pre-side-block" should contain style "width" "400px"
    And I navigate to "Appearance > Themes > Boost Union > Feel" in site administration
    Then I click on "Blocks" "link"
    And I set the following fields to these values:
      | Outside (left) block region width | 500px |
    Then I press "Save changes"
    And I follow "Dashboard"
    Then I should see "Online users" in the ".block-region-left#leftblock" "css_element"
    Then Boostunion ".block-region-left.pre-side-block" should contain style "width" "500px"

  @javascript
  Scenario: Setting: Outside (right) block region width - Setting the width
    Given I log in as "admin"
    And I turn editing mode on
    And I navigate to "Appearance > Themes > Boost Union > Feel" in site administration
    Then I click on "Blocks" "link"
    And I should see "Block regions for dashboard layout"
    And I set the following fields to these values:
      | Block regions for dashboard layout| outside-left, outside-right|
      | Outside (right) block region width | 400px                     |
    Then I press "Save changes"
    And I follow "Dashboard"
    Then I click on "Add a block" "link" in the ".block-region-right#rightblock" "css_element"
    Then I should see "Online users"
    Then I click on "Online users" "link"
    Then I should see "Online users" in the ".block-region-right#rightblock" "css_element"
    Then Boostunion ".block-region-right.post-side-block" should contain style "width" "400px"
    And I navigate to "Appearance > Themes > Boost Union > Feel" in site administration
    Then I click on "Blocks" "link"
    And I set the following fields to these values:
      | Outside (right) block region width | 500px |
    Then I press "Save changes"
    And I follow "Dashboard"
    Then I should see "Online users" in the ".block-region-right#rightblock" "css_element"
    Then Boostunion ".block-region-right.post-side-block" should contain style "width" "500px"
