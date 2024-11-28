@theme @theme_boost_union @theme_boost_union_feelsettings @theme_boost_union_feelsettings_links @theme_boost_union_footnote
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
      | label    | Label three | <a href="https://www.externallink.com">External Link</a> | C1       |
      | label    | Label three | <a href="/my/">Internal Link</a>                         | C1       |
    And the following config values are set as admin:
      | config   | value                                                                                                                                     | plugin            |
      | footnote | <a href="mailto:test@test.de">Mail-Link</a><br /><a href="https://www.externallink.com">External Link</a><a href="/my/">Internal Link</a> | theme_boost_union |

  @javascript
  Scenario Outline: Setting: Mark external links
    Given the following config values are set as admin:
      | config                 | value   | plugin            |
      | markexternallinks      | <value> | theme_boost_union |
      | markexternallinksscope | <scope> | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I follow "Dashboard"
    Then element "#footnote a[href*='https://www.externallink.com']" pseudo-class "after" should match "content": <contentfootnote>
    And element "#footnote a[href='/my/']" pseudo-class "after" should match "content": none
    And I am on "Course 1" course homepage
    Then element "#region-main a[href*='https://www.externallink.com']" pseudo-class "after" should match "content": <contentcourse>
    And element "#region-main a[href='/my/']" pseudo-class "after" should match "content": none

    Examples:
      | value | scope      | contentfootnote | contentcourse |
      | yes   | wholepage  | ""             | ""           |
      | yes   | coursemain | none            | ""           |
      | no    | wholepage  | none            | none          |

  @javascript
  Scenario Outline: Setting: Mark mailto links
    Given the following config values are set as admin:
      | config               | value   | plugin            |
      | markmailtolinks      | <value> | theme_boost_union |
      | markmailtolinksscope | <scope> | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I follow "Dashboard"
    Then element "#footnote a[href^='mailto']" pseudo-class "before" should match "content": <contentfootnote>
    And element "#footnote a[href='/my/']" pseudo-class "after" should match "content": none
    And I am on "Course 1" course homepage
    Then element "#region-main a[href^='mailto']" pseudo-class "before" should match "content": <contentcourse>
    And element "#region-main a[href='/my/']" pseudo-class "after" should match "content": none

    Examples:
      | value | scope      | contentfootnote | contentcourse |
      | yes   | wholepage  | ""             | ""           |
      | yes   | coursemain | none            | ""           |
      | no    | wholepage  | none            | none          |

  @javascript
  Scenario Outline: Setting: Mark broken links
    Given the following config values are set as admin:
      | config          | value   | plugin            |
      | markbrokenlinks | <value> | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then element "a[href*='/brokenfile.php']" pseudo-class "before" should match "content": <content>
    And element "a[href='/my/']" pseudo-class "after" should match "content": none

    Examples:
      | value | content |
      | yes   | ""    |
      | no    | none   |
