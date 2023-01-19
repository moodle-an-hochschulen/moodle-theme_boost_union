@theme @theme_boost_union @theme_boost_union_feelsettings @theme_boost_union_feelsettings_links
Feature: Configuring the theme_boost_union plugin for the "Links" tab on the "Feel" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "activities" exist:
      | activity | name        | intro                                                    | course   |
      | label    | Label one   | <a href="mailto:test@test.de">Mail-Link</a>              | C1 	     |
      | label    | Label two   | <a href="/brokenfile.php">Broken Link</a>                | C1       |
      | label    | Label three | <a href="https://www.externallink.com">Extrnal Link</a>  | C1       |
      | label    | Label three | <a href="/my/">Internal Link</a>                         | C1       |

  @javascript
  Scenario Outline: Setting: Mark external links
    Given the following config values are set as admin:
      | config            | value   | plugin            |
      | markexternallinks | <value> | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    And I am on "Course 1" course homepage
    Then element "a[href*='https://www.externallink.com']" pseudo-class "after" should contain "content": <content>
    And element "a[href='/my/']" pseudo-class "after" should contain "content": none

    Examples:
      | value | content |
      | yes   | ""    |
      | no    | none   |

  @javascript
  Scenario Outline: Setting: Mark mailto links
    Given the following config values are set as admin:
      | config          | value   | plugin            |
      | markmailtolinks | <value> | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    And I am on "Course 1" course homepage
    Then element "a[href^='mailto']" pseudo-class "before" should contain "content": <content>
    And element "a[href='/my/']" pseudo-class "after" should contain "content": none

    Examples:
      | value | content |
      | yes   | ""    |
      | no    | none   |

  @javascript
  Scenario Outline: Setting: Mark broken links
    Given the following config values are set as admin:
      | config          | value   | plugin            |
      | markbrokenlinks | <value> | theme_boost_union |
    When I log in as "admin"
    And I navigate to "Development > Purge caches" in site administration
    And I press "Purge all caches"
    And I am on "Course 1" course homepage
    Then element "a[href*='/brokenfile.php']" pseudo-class "before" should contain "content": <content>
    And element "a[href='/my/']" pseudo-class "after" should contain "content": none

    Examples:
      | value | content |
      | yes   | ""    |
      | no    | none   |
