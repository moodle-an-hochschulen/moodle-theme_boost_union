@theme @theme_boost_union @theme_boost_union_smartmenusettings @theme_boost_union_smartmenusettings_menuitems @theme_boost_union_smartmenusettings_menuitems_placeholders
Feature: Placeholder support in Boost Union smart menu items
  In order to use dynamic, user-specific and context-specific menu items
  As admin
  I need to be able to use placeholders in smart menu item titles and URLs

  Background:
    Given I log in as "admin"
    And the following "courses" exist:
      | fullname     | shortname |
      | Test course1 | C1        |
    And the following "users" exist:
      | username | firstname | lastname |
      | user1    | Test      | User     |
      | user2    | Another   | Student  |
      | teacher1 | Teacher   | One      |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | user1    | C1     | student        |
      | user2    | C1     | student        |
      | teacher1 | C1     | editingteacher |
    And the following "theme_boost_union > smart menu" exists:
      | title    | Placeholder Menu |
      | location | Main navigation  |

  Scenario: Static item with {courseid} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu               |
      | title    | Go to course {courseid}        |
      | itemtype | Static (with placeholders)     |
      | url      | /course/view.php?id={courseid} |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Go to course 42" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "/course/view.php?id=42"

  Scenario: Heading item with {courseid} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu            |
      | title    | Go to course {courseid}     |
      | itemtype | Heading (with placeholders) |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Go to course 42" in location "Main"

  Scenario: Static item with {coursefullname} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu                       |
      | title    | Main course: {coursefullname}          |
      | itemtype | Static (with placeholders)             |
      | url      | /course/view.php?name={coursefullname} |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Main course: Test course1" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "name=Test%20course1"

  Scenario: Heading item with {coursefullname} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu              |
      | title    | Main course: {coursefullname} |
      | itemtype | Heading (with placeholders)   |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Main course: Test course1" in location "Main"

  Scenario: Static item with {courseshortname} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu                     |
      | title    | Go to {courseshortname}              |
      | itemtype | Static (with placeholders)           |
      | url      | /course/?shortname={courseshortname} |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Go to C1" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "shortname=C1"

  Scenario: Heading item with {courseshortname} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu            |
      | title    | Go to {courseshortname}     |
      | itemtype | Heading (with placeholders) |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Go to C1" in location "Main"

  Scenario: Static item with {userid} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu              |
      | title    | Your profile (ID: {userid})   |
      | itemtype | Static (with placeholders)    |
      | url      | /user/profile.php?id={userid} |
    When I log in as "user1"
    Then I should see smart menu "Placeholder Menu" item "Your profile (ID: 3)" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "/user/profile.php?id=3"

  Scenario: Heading item with {userid} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu              |
      | title    | Your profile (ID: {userid})   |
      | itemtype | Heading (with placeholders)   |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Your profile (ID: 3)" in location "Main"

  Scenario: Static item with {userusername} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu                      |
      | title    | User: {userusername}                  |
      | itemtype | Static (with placeholders)            |
      | url      | /user/profile.php?name={userusername} |
    When I log in as "user1"
    Then I should see smart menu "Placeholder Menu" item "User: user1" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "name=user1"

  Scenario: Heading item with {userusername} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu            |
      | title    | User: {userusername}        |
      | itemtype | Heading (with placeholders) |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "User: user1" in location "Main"

  Scenario: Static item with {userfullname} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu                      |
      | title    | Hello {userfullname}!                 |
      | itemtype | Static (with placeholders)            |
      | url      | /user/profile.php?name={userfullname} |
    When I log in as "user1"
    Then I should see smart menu "Placeholder Menu" item "Hello Test User!" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "name=Test%20User"

  Scenario: Heading item with {userfullname} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu            |
      | title    | Hello {userfullname}!       |
      | itemtype | Heading (with placeholders) |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Hello Test User!" in location "Main"

  Scenario: Static item with {editingtoggle} placeholder when editing is off
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu                      |
      | title    | Edit mode: {editingtoggle}            |
      | itemtype | Static (with placeholders)            |
      | url      | /course/view.php?edit={editingtoggle} |
    When I log in as "teacher1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Edit mode: on" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "edit=on"

  Scenario: Heading item with {editingtoggle} placeholder when editing is off
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu            |
      | title    | Edit mode: {editingtoggle}  |
      | itemtype | Heading (with placeholders) |
    When I log in as "teacher1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Edit mode: on" in location "Main"

  Scenario: Static item with {editingtoggle} placeholder when editing is on
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu                      |
      | title    | Edit mode: {editingtoggle}            |
      | itemtype | Static (with placeholders)            |
      | url      | /course/view.php?edit={editingtoggle} |
    When I log in as "teacher1"
    And I am on "Test course1" course homepage
    And I turn editing mode on
    Then I should see smart menu "Placeholder Menu" item "Edit mode: off" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "edit=off"

  Scenario: Heading item with {editingtoggle} placeholder when editing is on
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu            |
      | title    | Edit mode: {editingtoggle}  |
      | itemtype | Heading (with placeholders) |
    When I log in as "teacher1"
    And I am on "Test course1" course homepage
    And I turn editing mode on
    Then I should see smart menu "Placeholder Menu" item "Edit mode: off" in location "Main"

  Scenario: Static item with {pagecontextid} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu                 |
      | title    | Context ID: {pagecontextid}      |
      | itemtype | Static (with placeholders)       |
      | url      | /course/?context={pagecontextid} |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Context ID: 99" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "context=99"

  Scenario: Heading item with {pagecontextid} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu            |
      | title    | Context ID: {pagecontextid} |
      | itemtype | Heading (with placeholders) |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Context ID: 99" in location "Main"

  Scenario: Static item with {pagepath} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu              |
      | title    | Current page path: {pagepath} |
      | itemtype | Static (with placeholders)    |
      | url      | {pagepath}                    |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Current page path: /course/view.php" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "/course/view.php"

  Scenario: Heading item with {pagepath} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu              |
      | title    | Current page path: {pagepath} |
      | itemtype | Heading (with placeholders)   |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Current page path: /course/view.php" in location "Main"

  Scenario: Static item with {sesskey} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu                   |
      | title    | Security: {sesskey}                |
      | itemtype | Static (with placeholders)         |
      | url      | /course/view.php?sesskey={sesskey} |
    When I log in as "user1"
    Then I should see smart menu "Placeholder Menu" item "Security: behat0000000000000000000000000000" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "sesskey=behat0000000000000000000000000000"

  Scenario: Heading item with {sesskey} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu            |
      | title    | Security: {sesskey}         |
      | itemtype | Heading (with placeholders) |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Security: behat0000000000000000000000000000" in location "Main"

  Scenario: Static item with {courseurl} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu           |
      | title    | Go to current course       |
      | itemtype | Static (with placeholders) |
      | url      | {courseurl}                |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Go to current course" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "/course/view.php?id=42"

  Scenario: Static item with {pageurl} placeholder
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu           |
      | title    | Reload current page        |
      | itemtype | Static (with placeholders) |
      | url      | {pageurl}                  |
    When I log in as "user1"
    And I am on the "user1" "user > profile" page
    Then I should see smart menu "Placeholder Menu" item "Reload current page" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "/user/profile.php?id="

  Scenario: Multiple placeholders in item title
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu                                    |
      | title    | {userfullname} in {coursefullname} (ID: {courseid}) |
      | itemtype | Static (with placeholders)                          |
      | url      | /course/view.php?id={courseid}                      |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Test User in Test course1 (ID: 42)" in location "Main"

  Scenario: Multiple placeholders in URL
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu                                |
      | title    | User Profile                                    |
      | itemtype | Static (with placeholders)                      |
      | url      | /user/profile.php?id={userid}&course={courseid} |
    When I log out
    And I log in as "user1"
    And I am on "Test course1" course homepage
    Then the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "/user/profile.php?id=3&course=42"

  Scenario: Placeholder content is not cached and different per user
    Given the following "theme_boost_union > smart menu item" exists:
      | menu     | Placeholder Menu           |
      | title    | Welcome {userfullname}     |
      | itemtype | Static (with placeholders) |
      | url      | /dashboard                 |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Welcome Test User" in location "Main"
    And I log out
    And I log in as "user2"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Welcome Another Student" in location "Main"

  Scenario: Mailto item with placeholders in title, subject and body
    Given the following "theme_boost_union > smart menu item" exists:
      | menu          | Placeholder Menu                                 |
      | title         | Contact about {coursefullname}                   |
      | itemtype      | Mailto (with placeholders)                       |
      | email         | support@test.com                                 |
      | email_subject | Question about {courseshortname}                 |
      | email_body    | Hello, I am {userfullname}.\nCourse: {courseurl} |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Contact about Test course1" in location "Main"
    # Test each mailto parameter separately to make sure the placeholders are replaced and correctly URL-encoded in the href.
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "mailto:support@test.com"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "subject=Question%20about%20C1"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "body=Hello%2C%20I%20am%20Test%20User.%0ACourse%3A%20"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "%2Fcourse%2Fview.php%3Fid%3D42"

  Scenario: Mailto item with placeholders is not cached and different per user
    Given the following "theme_boost_union > smart menu item" exists:
      | menu          | Placeholder Menu            |
      | title         | Mail as {userfullname}      |
      | itemtype      | Mailto (with placeholders)  |
      | email         | support@test.com            |
      | email_subject | Message from {userusername} |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Mail as Test User" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "subject=Message%20from%20user1"
    And I log out
    And I log in as "user2"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Mail as Another Student" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "subject=Message%20from%20user2"

  Scenario: Plain mailto item does not replace placeholders
    Given the following "theme_boost_union > smart menu item" exists:
      | menu          | Placeholder Menu          |
      | title         | Contact {coursefullname}  |
      | itemtype      | Mailto                    |
      | email         | support@test.com          |
      | email_subject | Course {courseid}         |
    When I log in as "user1"
    And I am on "Test course1" course homepage
    Then I should see smart menu "Placeholder Menu" item "Contact {coursefullname}" in location "Main"
    And the "href" attribute of "a.boost-union-smartmenuitem" "css_element" should contain "subject=Course%20%7Bcourseid%7D"
