@theme @theme_boost_union @theme_boost_union_goodiesfordesigners @theme_boost_union_footnote
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
    Then element "#footnote a[href*='https://www.externallink.com']" pseudo-class "after" should match "content": ""

  @javascript
  Scenario: Feature: Mark mailto links
    And the following config values are set as admin:
      | config          | value                                                          | plugin            |
      | markmailtolinks | no                                                             | theme_boost_union |
      | footnote        | <a href="mailto:test@test.de" class="mailtolink">Mail-Link</a> | theme_boost_union |
    When I log in as "admin"
    And I follow "Dashboard"
    Then element "#footnote a[href^='mailto']" pseudo-class "before" should match "content": ""

  @javascript
  Scenario: Feature: Themerev as SCSS variable
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "SCSS" "link" in the "#adminsettings .nav-tabs" "css_element"
    # We add a small CSS snippet to the page which adds the themrev to the Dashboard header.
    # This is just to make it easy to detect that this SCSS variable is set.
    And I set the field "Raw SCSS" to multiline:
    """
    #page-my-index h1:after { content: 'Themerev: #{$themerev}'; }
    """
    And I press "Save changes"
    And Behat debugging is enabled
    And I follow "Dashboard"
    Then I should see "Dashboard" in the "#page-my-index h1" "css_element"
    And element "#page-my-index h1" pseudo-class "after" should contain "content": "Themerev"
    # This test will break on Jan 25 2027 as the unixtime timestamp will then start with 18. But we will accept that.
    And element "#page-my-index h1" pseudo-class "after" should contain "content": "17"
