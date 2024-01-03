@theme @theme_boost_union @theme_boost_union_goodiesfordesigners
Feature: Using the goodies for designers in the theme_boost_union plugin
  In order to use the features
  As designer
  I need to be able to apply styles and visual additions

  @javascript
  Scenario: Feature: Mark external links
    And the following config values are set as admin:
      | config            | value                                                                         | plugin            |
      | markexternallinks | no                                                                            | theme_boost_union |
      | footnote          | <a href="https://www.externallink.com" class="externallink">External Link</a> | theme_boost_union |
    When I log in as "admin"
    And I follow "Dashboard"
    Then element "#footnote a[href*='https://www.externallink.com']" pseudo-class "after" should contain "content": ""

  @javascript
  Scenario: Feature: Mark mailto links
    And the following config values are set as admin:
      | config          | value                                                          | plugin            |
      | markmailtolinks | no                                                             | theme_boost_union |
      | footnote        | <a href="mailto:test@test.de" class="mailtolink">Mail-Link</a> | theme_boost_union |
    When I log in as "admin"
    And I follow "Dashboard"
    Then element "#footnote a[href^='mailto']" pseudo-class "before" should contain "content": ""
