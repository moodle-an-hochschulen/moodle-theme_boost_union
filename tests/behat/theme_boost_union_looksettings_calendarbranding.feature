@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_calendarbranding
Feature: Configuring the theme_boost_union plugin for the "Calendar branding" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "categories" exist:
      | name          | category | idnumber |
      | My category 1 | 0        | CAT1     |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | CAT1     |
    And the following "course enrolments" exist:
      | user  | course | role           |
      | admin | C1     | editingteacher |
    And the following "blocks" exist:
      | blockname      | contextlevel | reference | pagetypepattern | defaultregion |
      | calendar_month | System       | 1         | my-index        | content       |

  @javascript
  Scenario: Setting: Calendar event colors - Setting the color for event type "Category"
    Given the following config values are set as admin:
      | config                           | value   | plugin            |
      | calendareventcolormaincategory   | #ffc8ff | theme_boost_union |
      | calendareventcolorbordercategory | #ff00ff | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I create a calendar event with form data:
      | Type of event | Category         |
      | Category      | My category 1    |
      | Event title   | Category 1 Event |
    And I follow "Dashboard"
    Then DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_category" should have computed style "background-color" "rgb(255, 200, 255)"
    # Now we have to test each border property for one particular border side individually as the Behat step would handle border or even border-with as shorthand notation which is untestable currently.
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_category" should have computed style "border-top-width" "2px"
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_category" should have computed style "border-top-style" "solid"
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_category" should have computed style "border-top-color" "rgb(255, 0, 255)"

  @javascript
  Scenario: Setting: Calendar event colors - Setting the color for event type "Course"
    Given the following config values are set as admin:
      | config                         | value   | plugin            |
      | calendareventcolormaincourse   | #ffc8ff | theme_boost_union |
      | calendareventcolorbordercourse | #ff00ff | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I create a calendar event with form data:
      | Type of event | Course         |
      | Course        | Course 1       |
      | Event title   | Course 1 Event |
    And I follow "Dashboard"
    Then DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_course" should have computed style "background-color" "rgb(255, 200, 255)"
    # Now we have to test each border property for one particular border side individually as the Behat step would handle border or even border-with as shorthand notation which is untestable currently.
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_course" should have computed style "border-top-width" "2px"
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_course" should have computed style "border-top-style" "solid"
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_course" should have computed style "border-top-color" "rgb(255, 0, 255)"

  @javascript
  Scenario: Setting: Calendar event colors - Setting the color for event type "Group"
    Given the following config values are set as admin:
      | config                        | value   | plugin            |
      | calendareventcolormaingroup   | #ffc8ff | theme_boost_union |
      | calendareventcolorbordergroup | #ff00ff | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    And the following "groups" exist:
      | name    | course | idnumber |
      | Group 1 | C1     | G1       |
    And the following "group members" exist:
      | user  | group |
      | admin | G1    |
    When I log in as "admin"
    And I set the field "course" in the ".block_calendar_month" "css_element" to "Course 1"
    And I create a calendar event:
      | Type of event | Group         |
      | Group         | Group 1       |
      | Event title   | Group 1 Event |
    And I follow "Dashboard"
    Then DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_group" should have computed style "background-color" "rgb(255, 200, 255)"
    # Now we have to test each border property for one particular border side individually as the Behat step would handle border or even border-with as shorthand notation which is untestable currently.
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_group" should have computed style "border-top-width" "2px"
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_group" should have computed style "border-top-style" "solid"
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_group" should have computed style "border-top-color" "rgb(255, 0, 255)"

  @javascript
  Scenario: Setting: Calendar event colors - Setting the color for event type "User"
    Given the following config values are set as admin:
      | config                       | value   | plugin            |
      | calendareventcolormainuser   | #ffc8ff | theme_boost_union |
      | calendareventcolorborderuser | #ff00ff | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I create a calendar event with form data:
      | Type of event | User       |
      | Event title   | User Event |
    And I follow "Dashboard"
    Then DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_user" should have computed style "background-color" "rgb(255, 200, 255)"
    # Now we have to test each border property for one particular border side individually as the Behat step would handle border or even border-with as shorthand notation which is untestable currently.
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_user" should have computed style "border-top-width" "2px"
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_user" should have computed style "border-top-style" "solid"
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_user" should have computed style "border-top-color" "rgb(255, 0, 255)"

  @javascript
  Scenario: Setting: Calendar event colors - Setting the color for event type "Site"
    Given the following config values are set as admin:
      | config                       | value   | plugin            |
      | calendareventcolormainsite   | #ffc8ff | theme_boost_union |
      | calendareventcolorbordersite | #ff00ff | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I create a calendar event with form data:
      | Type of event | Site       |
      | Event title   | Site Event |
    And I follow "Dashboard"
    Then DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_site" should have computed style "background-color" "rgb(255, 200, 255)"
    # Now we have to test each border property for one particular border side individually as the Behat step would handle border or even border-with as shorthand notation which is untestable currently.
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_site" should have computed style "border-top-width" "2px"
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_site" should have computed style "border-top-style" "solid"
    And DOM element ".maincalendar .calendarmonth ul li .calendar-circle.calendar_event_site" should have computed style "border-top-color" "rgb(255, 0, 255)"

  # Unfortunately, this can't be tested with Behat as the event type is not displayed as an option when creating a new event
  # Scenario: Setting: Calendar event colors - Setting the color for event type "Other"

  @javascript
  Scenario: Setting: General calendar branding - Setting the color for calendar icons
    Given the following config values are set as admin:
      | config             | value   | plugin            |
      | calendariconscolor | #022973 | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I create a calendar event with form data:
      | Type of event | User       |
      | Event title   | User Event |
    And I follow "Dashboard"
    And I click on "Full calendar" "link"
    Then DOM element ".block .calendar_filters li span i" should have computed style "color" "rgb(2, 41, 115)"
