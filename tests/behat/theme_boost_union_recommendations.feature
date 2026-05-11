@theme @theme_boost_union @theme_boost_union_recommendations
Feature: Recommendations in theme_boost_union
  In order to configure Boost Union robustly
  As admin
  I need to be able to access and review Recommendations

  Background:
    Given I log in as "admin"

  Scenario: Admin can navigate to recommendations and sees table structure
    When I navigate to "Appearance > Boost Union > Recommendations" in site administration
    Then "body#page-admin-theme-boost_union-recommendations-overview" "css_element" should exist
    And "table#recommendations-moodlecore" "css_element" should exist
    And I should see "Status" in the "table#recommendations-moodlecore" "css_element"
    And I should see "Recommendation" in the "table#recommendations-moodlecore" "css_element"
    And I should see "Summary" in the "table#recommendations-moodlecore" "css_element"
    And I should see "Actions" in the "table#recommendations-moodlecore" "css_element"
    And "table#recommendations-moodlecore .recommendations-actions .action-edit" "css_element" should exist
    And "table#recommendations-moodlecore .recommendations-actions .action-details" "css_element" should exist

  @javascript
  Scenario: Admin can open and close recommendation info modal
    When I navigate to "Appearance > Boost Union > Recommendations" in site administration
    And I should see "Slash arguments support" in the "table#recommendations-moodlecore" "css_element"
    When I click on ".action-details" "css_element" in the "Slash arguments support" "table_row"
    Then ".modal-dialog" "css_element" should be visible
    And I should see "Slash arguments support" in the ".modal-title" "css_element"
    And I should see "Slash arguments should be enabled" in the ".modal-body" "css_element"
    And I should see "Some Boost Union features rely on the Moodle core function slasharguments" in the ".modal-body" "css_element"
    When I click on ".modal-dialog .btn-close" "css_element"
    Then ".modal-dialog" "css_element" should not be visible

  Scenario: Admin can mute and unmute a recommendation
    Given the following config values are set as admin:
      | config         | value |
      | slasharguments | 0     |
    And the following config values are set as admin:
      | config | value        | plugin      |
      | preset | default.scss | theme_boost |
    And I navigate to "Appearance > Boost Union > Recommendations" in site administration
    And I should see "Slash arguments support" in the "table#recommendations-moodlecore" "css_element"
    And I should see "Warning" in the "Slash arguments support" "table_row"
    And "Slash arguments support" "text" should appear before "Theme Boost preset" "text"
    And ".action-unmute" "css_element" should not exist in the "Slash arguments support" "table_row"
    And ".action-mute" "css_element" should exist in the "Slash arguments support" "table_row"
    When I click on ".action-mute" "css_element" in the "Slash arguments support" "table_row"
    Then I should see "Muted" in the "Slash arguments support" "table_row"
    And I should see "The recommendation has been muted."
    And "Slash arguments support" "text" should appear after "Theme Boost preset" "text"
    And ".action-unmute" "css_element" should exist in the "Slash arguments support" "table_row"
    And ".action-mute" "css_element" should not exist in the "Slash arguments support" "table_row"
    When I click on ".action-unmute" "css_element" in the "Slash arguments support" "table_row"
    Then I should see "Warning" in the "Slash arguments support" "table_row"
    And I should see "The recommendation has been unmuted."
    And "Slash arguments support" "text" should appear before "Theme Boost preset" "text"
    And ".action-unmute" "css_element" should not exist in the "Slash arguments support" "table_row"
    And ".action-mute" "css_element" should exist in the "Slash arguments support" "table_row"

  Scenario Outline: Verify Recommendation slasharguments (Status check)
    Given the following config values are set as admin:
      | config         | value                 |
      | slasharguments | <slashargumentsvalue> |
    When I navigate to "Appearance > Boost Union > Recommendations" in site administration
    Then I should see "Slash arguments support" in the "table#recommendations-moodlecore" "css_element"
    And I should see "<statustext>" in the "Slash arguments support" "table_row"

    Examples:
      | slashargumentsvalue | statustext |
      | 1                   | OK         |
      | 0                   | Warning    |

  Scenario: Verify Recommendation slasharguments (Autofix check)
    Given the following config values are set as admin:
      | config         | value |
      | slasharguments | 0     |
    And I navigate to "Appearance > Boost Union > Recommendations" in site administration
    And I should see "Warning" in the "Slash arguments support" "table_row"
    And ".action-autofix" "css_element" should exist in the "Slash arguments support" "table_row"
    When I click on ".action-autofix" "css_element" in the "Slash arguments support" "table_row"
    Then I should see "OK" in the "Slash arguments support" "table_row"
    And I should see "The recommendation has been fixed automatically."
    And ".action-autofix" "css_element" should not exist in the "Slash arguments support" "table_row"

  Scenario Outline: Verify Recommendation themeboostpreset (Status check)
    Given the following config values are set as admin:
      | config | value         | plugin      |
      | preset | <presetvalue> | theme_boost |
    When I navigate to "Appearance > Boost Union > Recommendations" in site administration
    Then I should see "Theme Boost preset" in the "table#recommendations-moodlecore" "css_element"
    And I should see "<statustext>" in the "Theme Boost preset" "table_row"

    Examples:
      | presetvalue  | statustext |
      | default.scss | OK         |
      | classic.scss | Warning    |

  Scenario: Verify Recommendation themeboostpreset (Autofix check)
    Given the following config values are set as admin:
      | config | value        | plugin      |
      | preset | classic.scss | theme_boost |
    And I navigate to "Appearance > Boost Union > Recommendations" in site administration
    And I should see "Warning" in the "Theme Boost preset" "table_row"
    And ".action-autofix" "css_element" should exist in the "Theme Boost preset" "table_row"
    When I click on ".action-autofix" "css_element" in the "Theme Boost preset" "table_row"
    Then I should see "OK" in the "Theme Boost preset" "table_row"
    And I should see "The recommendation has been fixed automatically."
    And ".action-autofix" "css_element" should not exist in the "Theme Boost preset" "table_row"

  Scenario Outline: Verify Recommendation corelogo / corecompactlogo / corefavicon (Status check for negative case)
    Given the following "theme_boost_union > core files" exist:
      | filearea   | filepath     |
      | <filearea> | <uploadfile> |
    When I navigate to "Appearance > Boost Union > Recommendations" in site administration
    Then I should see "<recommendationtitle>" in the "table#recommendations-moodlecore" "css_element"
    And I should see "Notice" in the "<recommendationtitle>" "table_row"

    Examples:
      | filearea    | uploadfile                                     | recommendationtitle   |
      | logo        | theme/boost_union/tests/fixtures/login_bg1.png | Logo upload         |
      | logocompact | theme/boost_union/tests/fixtures/login_bg2.png | Compact logo upload |
      | favicon     | theme/boost_union/tests/fixtures/favicon.ico   | Favicon upload      |

  Scenario Outline: Verify Recommendation corelogo / corecompactlogo / corefavicon (Status check for positive case)
    When I navigate to "Appearance > Boost Union > Recommendations" in site administration
    Then I should see "<recommendationtitle>" in the "table#recommendations-moodlecore" "css_element"
    And I should see "OK" in the "<recommendationtitle>" "table_row"

    Examples:
      | recommendationtitle   |
      | Logo upload         |
      | Compact logo upload |
      | Favicon upload      |

  Scenario Outline: Verify Recommendation corelogo / corecompactlogo / corefavicon (Autofix check)
    Given the following "theme_boost_union > core files" exist:
      | filearea   | filepath     |
      | <filearea> | <uploadfile> |
    When I navigate to "Appearance > Boost Union > Recommendations" in site administration
    Then I should see "<recommendationtitle>" in the "table#recommendations-moodlecore" "css_element"
    And I should see "Notice" in the "<recommendationtitle>" "table_row"
    And ".action-autofix" "css_element" should exist in the "<recommendationtitle>" "table_row"
    When I click on ".action-autofix" "css_element" in the "<recommendationtitle>" "table_row"
    Then I should see "OK" in the "<recommendationtitle>" "table_row"
    And I should see "The recommendation has been fixed automatically."
    And ".action-autofix" "css_element" should not exist in the "<recommendationtitle>" "table_row"

    Examples:
      | filearea    | uploadfile                                     | recommendationtitle   |
      | logo        | theme/boost_union/tests/fixtures/login_bg1.png | Logo upload         |
      | logocompact | theme/boost_union/tests/fixtures/login_bg2.png | Compact logo upload |
      | favicon     | theme/boost_union/tests/fixtures/favicon.ico   | Favicon upload      |

  Scenario Outline: Verify Recommendation coreauthinstructions (Status check)
    Given the following config values are set as admin:
      | config            | value                   |
      | auth_instructions | <authinstructionsvalue> |
    When I navigate to "Appearance > Boost Union > Recommendations" in site administration
    Then I should see "Auth instructions" in the "table#recommendations-moodlecore" "css_element"
    And I should see "<statustext>" in the "Auth instructions" "table_row"

    Examples:
      | authinstructionsvalue                | statustext |
      | <p>Legacy core auth instructions</p> | Notice     |
      |                                      | OK         |

  Scenario: Verify Recommendation coreauthinstructions (Autofix check)
    Given the following config values are set as admin:
      | config            | value                                |
      | auth_instructions | <p>Legacy core auth instructions</p> |
    And I navigate to "Appearance > Boost Union > Recommendations" in site administration
    And I should see "Auth instructions" in the "table#recommendations-moodlecore" "css_element"
    And I should see "Notice" in the "Auth instructions" "table_row"
    And ".action-autofix" "css_element" should exist in the "Auth instructions" "table_row"
    When I click on ".action-autofix" "css_element" in the "Auth instructions" "table_row"
    Then I should see "OK" in the "Auth instructions" "table_row"
    And I should see "The recommendation has been fixed automatically."
    And ".action-autofix" "css_element" should not exist in the "Auth instructions" "table_row"

  Scenario: Verify recommendation notification and view-all action on settings page (with the core logo recommendation as an example)
    Given the following "theme_boost_union > core files" exist:
      | filearea | filepath                                       |
      | logo     | theme/boost_union/tests/fixtures/login_bg1.png |
    When I navigate to "Appearance > Boost Union > Look" in site administration
    Then "div.theme-boost-union-recommendationnotification" "css_element" should exist
    And I should see "Logo upload" in the "div.theme-boost-union-recommendationnotification" "css_element"
    And "div.theme-boost-union-recommendationnotification .recommendations-actions .action-viewall" "css_element" should exist
    And I click on "View all recommendations" "link"
    Then "body#page-admin-theme-boost_union-recommendations-overview" "css_element" should exist
