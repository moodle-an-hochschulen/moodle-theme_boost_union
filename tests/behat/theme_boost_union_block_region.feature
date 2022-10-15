@theme @theme_boost_union  @theme_boost_union_block_config @javascript
Feature: Theme boost union block config
  In order to add more functionality to pages
  I need to add blocks to pages

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | student1 | Student | 1 | student1@example.com |
      | student2 | Student | 2 | student2@example.com |
      | teacher  | teacher | name | teacher@example.com |
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1 | topics |
    And the following "activities" exist:
      | activity   | name                   | intro                         | course | idnumber    | section |
      | assign     | Test assignment name   | Test assignment description   | C1     | assign1     | 0       |
      | book       | Test book name         | Test book description         | C1     | book1       | 0       |
      | chat       | Test chat name         | Test chat description         | C1     | chat1       | 1       |
    And the following "course enrolments" exist:
      | user | course | role |
      | student1 | C1 | student |
      | student2 | C1 | student |
      | teacher  | C1  | teacher |
    And I log in as "admin"
    When I turn editing mode on

  Scenario: Add a block to a left region.
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-left#leftblock" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-left#leftblock" "css_element"
    Then I should see "Online users"
    Then I click on "Online users" "link"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    Then I press "Continue"
    Then I should see "Online users" in the ".block-region-left#leftblock" "css_element"
    And I am on site homepage
    And I should see "Add a block" in the ".block-region-left#leftblock" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-left#leftblock" "css_element"
    And I should see "Calendar" in the ".modal-body" "css_element"
    Then I click on "Calendar" "link" in the ".modal-body" "css_element"
    Then I should see "Calendar" in the ".block-region-left#leftblock" "css_element"
    And I am on "Course 1" course homepage
    And I should see "Add a block" in the ".block-region-left#leftblock" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-left#leftblock" "css_element"
    Then I should see "Activities"
    Then I click on "Activities" "link"
    Then I should see "Activities" in the ".block-region-left#leftblock" "css_element"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:editregionleft" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should see "Online users" in the ".block-region-left#leftblock" "css_element"
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-left#leftblock" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-left#leftblock" "css_element"
    Then I should see "Logged in user"
    And I click on "Logged in user" "link"
    Then I should see "Logged in user" in the ".block-region-left#leftblock" "css_element"
    And I am on site homepage
    Then I should see "Calendar" in the ".block-region-left#leftblock" "css_element"
    And I am on "Course 1" course homepage
    Then I should see "Activities" in the ".block-region-left#leftblock" "css_element"
    And I log in as "admin"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:viewregionleft" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should not see "Online users"
    Then ".block-region-left#leftblock" "css_element" should not exist
    And I am on site homepage
    Then I should not see "Calendar"
    Then ".block-region-left#leftblock" "css_element" should not exist
    And I am on "Course 1" course homepage
    Then I should not see "Activities"
    Then ".block-region-left#leftblock" "css_element" should not exist

  Scenario: Add a block to a right region.
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-right#rightblock" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-right#rightblock" "css_element"
    Then I should see "Online users"
    Then I click on "Online users" "link"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    Then I press "Continue"
    Then I should see "Online users" in the ".block-region-right#rightblock" "css_element"
    And I am on site homepage
    And I should see "Add a block" in the ".block-region-right#rightblock" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-right#rightblock" "css_element"
    And I should see "Calendar" in the ".modal-body" "css_element"
    Then I click on "Calendar" "link" in the ".modal-body" "css_element"
    Then I should see "Calendar" in the ".block-region-right#rightblock" "css_element"
    And I am on "Course 1" course homepage
    And I should see "Add a block" in the ".block-region-right#rightblock" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-right#rightblock" "css_element"
    Then I should see "Activities"
    Then I click on "Activities" "link"
    Then I should see "Activities" in the ".block-region-right#rightblock" "css_element"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:editregionright" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should see "Online users" in the ".block-region-right#rightblock" "css_element"
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-right#rightblock" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-right#rightblock" "css_element"
    Then I should see "Logged in user"
    And I click on "Logged in user" "link"
    Then I should see "Logged in user" in the ".block-region-right#rightblock" "css_element"
    And I am on site homepage
    Then I should see "Calendar" in the ".block-region-right#rightblock" "css_element"
    And I am on "Course 1" course homepage
    Then I should see "Activities" in the ".block-region-right#rightblock" "css_element"
    And I log in as "admin"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:viewregionright" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should not see "Online users"
    Then ".block-region-right#rightblock" "css_element" should not exist
    And I am on site homepage
    Then I should not see "Calendar"
    Then ".block-region-right#rightblock" "css_element" should not exist
    And I am on "Course 1" course homepage
    Then I should not see "Activities"
    Then ".block-region-right#rightblock" "css_element" should not exist

  Scenario: Add a block to a top region.
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-top#topregion" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-top#topregion" "css_element"
    Then I should see "Online users"
    Then I click on "Online users" "link"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    Then I press "Continue"
    Then I should see "Online users" in the ".block-region-top#topregion" "css_element"
    And I am on site homepage
    And I should see "Add a block" in the ".block-region-top#topregion" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-top#topregion" "css_element"
    And I should see "Calendar" in the ".modal-body" "css_element"
    Then I click on "Calendar" "link" in the ".modal-body" "css_element"
    Then I should see "Calendar" in the ".block-region-top#topregion" "css_element"
    And I am on "Course 1" course homepage
    And I should see "Add a block" in the ".block-region-top#topregion" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-top#topregion" "css_element"
    Then I should see "Activities"
    Then I click on "Activities" "link"
    Then I should see "Activities" in the ".block-region-top#topregion" "css_element"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:editregiontop" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should see "Online users" in the ".block-region-top#topregion" "css_element"
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-top#topregion" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-top#topregion" "css_element"
    Then I should see "Logged in user"
    And I click on "Logged in user" "link"
    Then I should see "Logged in user" in the ".block-region-top#topregion" "css_element"
    And I am on site homepage
    Then I should see "Calendar" in the ".block-region-top#topregion" "css_element"
    And I am on "Course 1" course homepage
    Then I should see "Activities" in the ".block-region-top#topregion" "css_element"
    And I log in as "admin"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:viewregiontop" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should not see "Online users"
    Then ".block-region-top#topregion" "css_element" should not exist
    And I am on site homepage
    Then I should not see "Calendar"
    Then ".block-region-top#topregion" "css_element" should not exist
    And I am on "Course 1" course homepage
    Then I should not see "Activities"
    Then ".block-region-top#topregion" "css_element" should not exist

  Scenario: Add a block to a bottom region.
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-bottom#bottomregion" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-bottom#bottomregion" "css_element"
    Then I should see "Online users"
    Then I click on "Online users" "link"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    Then I press "Continue"
    Then I should see "Online users" in the ".block-region-bottom#bottomregion" "css_element"
    And I am on site homepage
    And I should see "Add a block" in the ".block-region-bottom#bottomregion" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-bottom#bottomregion" "css_element"
    And I should see "Calendar" in the ".modal-body" "css_element"
    Then I click on "Calendar" "link" in the ".modal-body" "css_element"
    Then I should see "Calendar" in the ".block-region-bottom#bottomregion" "css_element"
    And I am on "Course 1" course homepage
    And I should see "Add a block" in the ".block-region-bottom#bottomregion" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-bottom#bottomregion" "css_element"
    Then I should see "Activities"
    Then I click on "Activities" "link"
    Then I should see "Activities" in the ".block-region-bottom#bottomregion" "css_element"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:editregionbottom" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should see "Online users" in the ".block-region-bottom#bottomregion" "css_element"
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-bottom#bottomregion" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-bottom#bottomregion" "css_element"
    Then I should see "Logged in user"
    And I click on "Logged in user" "link"
    Then I should see "Logged in user" in the ".block-region-bottom#bottomregion" "css_element"
    And I am on site homepage
    Then I should see "Calendar" in the ".block-region-bottom#bottomregion" "css_element"
    And I am on "Course 1" course homepage
    Then I should see "Activities" in the ".block-region-bottom#bottomregion" "css_element"
    And I log in as "admin"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:viewregionbottom" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should not see "Online users"
    Then ".block-region-bottom#bottomregion" "css_element" should not exist
    And I am on site homepage
    Then I should not see "Calendar"
    Then ".block-region-bottom#bottomregion" "css_element" should not exist
    And I am on "Course 1" course homepage
    Then I should not see "Activities"
    Then ".block-region-bottom#bottomregion" "css_element" should not exist

  Scenario: Add a block to a footerleft region.
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-footer-left#footerleft" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-footer-left#footerleft" "css_element"
    Then I should see "Online users"
    Then I click on "Online users" "link"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    Then I press "Continue"
    Then I should see "Online users" in the ".block-region-footer-left#footerleft" "css_element"
    And I am on site homepage
    And I should see "Add a block" in the ".block-region-footer-left#footerleft" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-footer-left#footerleft" "css_element"
    And I should see "Calendar" in the ".modal-body" "css_element"
    Then I click on "Calendar" "link" in the ".modal-body" "css_element"
    Then I should see "Calendar" in the ".block-region-footer-left#footerleft" "css_element"
    And I am on "Course 1" course homepage
    And I should see "Add a block" in the ".block-region-footer-left#footerleft" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-footer-left#footerleft" "css_element"
    Then I should see "Activities"
    Then I click on "Activities" "link"
    Then I should see "Activities" in the ".block-region-footer-left#footerleft" "css_element"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:editregionfooterleft" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should see "Online users" in the ".block-region-footer-left#footerleft" "css_element"
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-footer-left#footerleft" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-footer-left#footerleft" "css_element"
    Then I should see "Logged in user"
    And I click on "Logged in user" "link"
    Then I should see "Logged in user" in the ".block-region-footer-left#footerleft" "css_element"
    And I am on site homepage
    Then I should see "Calendar" in the ".block-region-footer-left#footerleft" "css_element"
    And I am on "Course 1" course homepage
    Then I should see "Activities" in the ".block-region-footer-left#footerleft" "css_element"
    And I log in as "admin"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:viewregionfooterleft" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should not see "Online users"
    Then ".block-region-footer-left#footerleft" "css_element" should not exist
    And I am on site homepage
    Then I should not see "Calendar"
    Then ".block-region-footer-left#footerleft" "css_element" should not exist
    And I am on "Course 1" course homepage
    Then I should not see "Activities"
    Then ".block-region-footer-left#footerleft" "css_element" should not exist

  Scenario: Add a block to a footerright region.
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-footer-right#footerright" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-footer-right#footerright" "css_element"
    Then I should see "Online users"
    Then I click on "Online users" "link"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    Then I press "Continue"
    Then I should see "Online users" in the ".block-region-footer-right#footerright" "css_element"
    And I am on site homepage
    And I should see "Add a block" in the ".block-region-footer-right#footerright" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-footer-right#footerright" "css_element"
    And I should see "Calendar" in the ".modal-body" "css_element"
    Then I click on "Calendar" "link" in the ".modal-body" "css_element"
    Then I should see "Calendar" in the ".block-region-footer-right#footerright" "css_element"
    And I am on "Course 1" course homepage
    And I should see "Add a block" in the ".block-region-footer-right#footerright" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-footer-right#footerright" "css_element"
    Then I should see "Activities"
    Then I click on "Activities" "link"
    Then I should see "Activities" in the ".block-region-footer-right#footerright" "css_element"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:editregionfooterright" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should see "Online users" in the ".block-region-footer-right#footerright" "css_element"
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-footer-right#footerright" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-footer-right#footerright" "css_element"
    Then I should see "Logged in user"
    And I click on "Logged in user" "link"
    Then I should see "Logged in user" in the ".block-region-footer-right#footerright" "css_element"
    And I am on site homepage
    Then I should see "Calendar" in the ".block-region-footer-right#footerright" "css_element"
    And I am on "Course 1" course homepage
    Then I should see "Activities" in the ".block-region-footer-right#footerright" "css_element"
    And I log in as "admin"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:viewregionfooterright" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should not see "Online users"
    Then ".block-region-footer-right#footerright" "css_element" should not exist
    And I am on site homepage
    Then I should not see "Calendar"
    Then ".block-region-footer-right#footerright" "css_element" should not exist
    And I am on "Course 1" course homepage
    Then I should not see "Activities"
    Then ".block-region-footer-right#footerright" "css_element" should not exist

  Scenario: Add a block to a footercenter region.
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-footer-center#footercenter" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-footer-center#footercenter" "css_element"
    Then I should see "Online users"
    Then I click on "Online users" "link"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    Then I press "Continue"
    Then I should see "Online users" in the ".block-region-footer-center#footercenter" "css_element"
    And I am on site homepage
    And I should see "Add a block" in the ".block-region-footer-center#footercenter" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-footer-center#footercenter" "css_element"
    And I should see "Calendar" in the ".modal-body" "css_element"
    Then I click on "Calendar" "link" in the ".modal-body" "css_element"
    Then I should see "Calendar" in the ".block-region-footer-center#footercenter" "css_element"
    And I am on "Course 1" course homepage
    And I should see "Add a block" in the ".block-region-footer-center#footercenter" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-footer-center#footercenter" "css_element"
    Then I should see "Activities"
    Then I click on "Activities" "link"
    Then I should see "Activities" in the ".block-region-footer-center#footercenter" "css_element"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:editregionfootercenter" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should see "Online users" in the ".block-region-footer-center#footercenter" "css_element"
    And I turn editing mode on
    And I should see "Add a block" in the ".block-region-footer-center#footercenter" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-footer-center#footercenter" "css_element"
    Then I should see "Logged in user"
    And I click on "Logged in user" "link"
    Then I should see "Logged in user" in the ".block-region-footer-center#footercenter" "css_element"
    And I am on site homepage
    Then I should see "Calendar" in the ".block-region-footer-center#footercenter" "css_element"
    And I am on "Course 1" course homepage
    Then I should see "Activities" in the ".block-region-footer-center#footercenter" "css_element"
    And I log in as "admin"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:viewregionfootercenter" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then I should not see "Online users"
    Then ".block-region-footer-center#footercenter" "css_element" should not exist
    And I am on site homepage
    Then I should not see "Calendar"
    Then ".block-region-footer-center#footercenter" "css_element" should not exist
    And I am on "Course 1" course homepage
    Then I should not see "Activities"
    Then ".block-region-footer-center#footercenter" "css_element" should not exist

  Scenario: Add a block to a headertop region.
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
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:editregionheadertop" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    Then ".block-region-headertop#headertopregion" "css_element" should not exist
    And I am on site homepage
    Then I should see "Calendar" in the ".block-region-headertop#headertopregion" "css_element"
    And I am on "Course 1" course homepage
    Then I should see "Activities" in the ".block-region-headertop#headertopregion" "css_element"
    And I log in as "admin"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:viewregionheadertop" "checkbox"
    And I press "Save changes"
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

  Scenario: Add a block to a offcanvas region.
    And I navigate to "Appearance > Default Dashboard page" in site administration
    And I turn editing mode on
    #And I click on "#usernavigation .drawer-offcanvas-toggle" "css_element"
    And I should see "Add a block" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I should see "Online users"
    Then I click on "Online users" "link"
    And I press "Reset Dashboard for all users"
    And I should see "All Dashboard pages have been reset to default."
    Then I press "Continue"
    And I turn editing mode off
    And I click on "#usernavigation .drawer-offcanvas-toggle" "css_element"
    Then I should see "Online users" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I am on site homepage
    And I turn editing mode on
    #And I click on "#usernavigation .drawer-offcanvas-toggle" "css_element"
    And I should see "Add a block" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I should see "Calendar" in the ".modal-body" "css_element"
    Then I click on "Calendar" "link" in the ".modal-body" "css_element"
    And I turn editing mode off
    And I click on "#usernavigation .drawer-offcanvas-toggle" "css_element"
    Then I should see "Calendar" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    #And I click on "#usernavigation .drawer-offcanvas-toggle" "css_element"
    And I should see "Add a block" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I should see "Activities"
    Then I click on "Activities" "link"
    And I turn editing mode off
    And I click on "#usernavigation .drawer-offcanvas-toggle" "css_element"
    Then I should see "Activities" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I am on site homepage
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:editregionoffcanvasleft" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    And I click on "#usernavigation .drawer-offcanvas-toggle" "css_element"
    Then I should see "Online users" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I am on site homepage
    Then I follow "Dashboard"
    And I turn editing mode on
    #And I click on "#usernavigation .drawer-offcanvas-toggle" "css_element"
    And I should see "Add a block" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I click on "Add a block" "link" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    Then I should see "Logged in user"
    And I click on "Logged in user" "link"
    And I turn editing mode off
    And I click on "#usernavigation .drawer-offcanvas-toggle" "css_element"
    Then I should see "Logged in user" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I am on site homepage
    And I click on "#usernavigation .drawer-offcanvas-toggle" "css_element"
    Then I should see "Calendar" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I am on "Course 1" course homepage
    And I click on "#usernavigation .drawer-offcanvas-toggle" "css_element"
    Then I should see "Activities" in the ".block-region-offcanvasleft#offcanvasregionleft" "css_element"
    And I am on site homepage
    And I log in as "admin"
    And I navigate to "Users > Permissions > Define roles" in site administration
    And I follow "Authenticated user"
    And I press "Edit"
    And I click on "theme/boost_union:viewregionoffcanvasleft" "checkbox"
    And I press "Save changes"
    And I log in as "student1"
    Then I follow "Dashboard"
    And "#usernavigation .drawer-offcanvas-toggle" "css_element" should not exist
    And I am on site homepage
    And "#usernavigation .drawer-offcanvas-toggle" "css_element" should not exist
    And I am on "Course 1" course homepage
    And "#usernavigation .drawer-offcanvas-toggle" "css_element" should not exist
