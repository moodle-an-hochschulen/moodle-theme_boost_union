@theme @theme_boost_union @theme_boost_union_mwp
Feature: Configuring the theme_boost_union plugin on Moodle Workplace™
  In order to use Boost Union on Moodle Workplace™
  As admin
  I need some additional aspects

  Scenario: Verify Recommendation mwpextension (Status check - Negative case)
    Given MWP core is simulated to be present
    And I log in as "admin"
    When I navigate to "Appearance > Boost Union > Recommendations" in site administration
    Then "table#recommendations-mwp" "css_element" should exist
    And I should see "Boost Union MWP extension" in the "table#recommendations-mwp" "css_element"
    And I should see "Warning" in the "Boost Union MWP extension" "table_row"

  Scenario: Verify Recommendation mwpextension (Status check - Positive case)
    Given MWP core is simulated to be not present
    And I log in as "admin"
    When I navigate to "Appearance > Boost Union > Recommendations" in site administration
    Then I should not see "Boost Union MWP extension"
