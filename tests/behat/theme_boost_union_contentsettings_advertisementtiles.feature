@theme @theme_boost_union @theme_boost_union_contentsettings @theme_boost_union_contentsettings_advertisementtiles
Feature: Configuring the theme_boost_union plugin for the "Advertisement tiles" tab on the "Content" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | teacher1 |

  Scenario: Setting: Advertisement tiles - Display the advertisement tiles on the frontpage only and nowhere else
    Given the following config values are set as admin:
      | config       | value                             | plugin            |
      | tile1enabled | yes                               | theme_boost_union |
      | tile1title   | Tile 1                            | theme_boost_union |
      | tile1content | This is a test content for tile 1 | theme_boost_union |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionadvtile1" "css_element" should exist
    And I follow "Dashboard"
    Then "#themeboostunionadvtile1" "css_element" should not exist
    And I follow "My courses"
    Then "#themeboostunionadvtile1" "css_element" should not exist
    When I am on "Course 1" course homepage
    Then "#themeboostunionadvtile1" "css_element" should not exist
    When I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then "#themeboostunionadvtile1" "css_element" should not exist

  Scenario Outline: Setting: Advertisement tiles - Display the advertisement tile wrapper and the individual advertisement tile only if it is enabled
    Given the following config values are set as admin:
      | config       | value     | plugin            |
      | tile1enabled | <enabled> | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionadvtiles" "css_element" <shouldexist>
    And "#themeboostunionadvtile1" "css_element" <shouldexist>

    Examples:
      | enabled | shouldexist      |
      | yes     | should exist     |
      | no      | should not exist |

  Scenario Outline: Setting: Advertisement tiles - Display the advertisement tiles before or after the main output of site home
    Given the following config values are set as admin:
      | config                | value                             | plugin            |
      | tilefrontpageposition | <position>                        | theme_boost_union |
      | tile1enabled          | yes                               | theme_boost_union |
      | tile1title            | Tile 1                            | theme_boost_union |
      | tile1content          | This is a test content for tile 1 | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionadvtiles" "css_element" should appear <beforeafter> "div[role='main']" "css_element"

    Examples:
      | position | beforeafter |
      | 1        | before      |
      | 2        | after       |

  Scenario Outline: Setting: Advertisement tiles - When changing number of columns content is aligned accordingly.
    Given the following config values are set as admin:
      | config                | value                             | plugin            |
      | tilecolumns           | <setting>                         | theme_boost_union |
      | tile1enabled          | yes                               | theme_boost_union |
      | tile1title            | Tile 1                            | theme_boost_union |
      | tile1content          | This is a test content for tile 1 | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then I should see "This is a test content for tile 1" in the "#themeboostunionadvtile1<designclass>" "css_element"

    Examples:
      | setting | designclass               |
      | 1       | .col-12                   |
      | 2       | .col-12.col-sm-6          |
      | 3       | .col-12.col-sm-6.col-md-4 |
      | 4       | .col-12.col-sm-6.col-md-3 |

  Scenario: Setting: Advertisement tiles - Display the title, the content and the link in the corresponding HTML elements
    Given the following config values are set as admin:
      | config         | value                                                                                                                                               | plugin            |
      | tile1enabled   | yes                                                                                                                                                 | theme_boost_union |
      | tile1title     | <span lang="en" class="multilang">Tile 1</span><span lang="de" class="multilang">Kachel 1</span>                                                    | theme_boost_union |
      | tile1content   | <span lang="en" class="multilang">This is a test content for tile 1</span><span lang="de" class="multilang">Dies ist Testinhalt für Kachel 1</span> | theme_boost_union |
      | tile1link      | www.behat.com                                                                                                                                       | theme_boost_union |
      | tile1linktitle | <span lang="en" class="multilang">Link to Behat</span><span lang="de" class="multilang">Link zu Behat</span>                                        | theme_boost_union |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionadvtiles #themeboostunionadvtile1" "css_element" should exist
    And I should see "This is a test content for tile 1" in the "#themeboostunionadvtile1 .card .card-body .card-text" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">This is a test content for tile 1</span>" in the "#themeboostunionadvtile1 .card .card-body .card-text" "css_element"
    And I should not see "This is a test content for tile 1Dies ist Testinhalt für Kachel 1" in the "#themeboostunionadvtile1 .card .card-body .card-text" "css_element"
    And I should see "Tile 1" in the "#themeboostunionadvtile1 .card h5.card-header" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Tile 1</span>" in the "#themeboostunionadvtile1 .card h5.card-header" "css_element"
    And I should not see "Tile 1Kachel 1" in the "#themeboostunionadvtile1 .card h5.card-header" "css_element"
    And I should see "Link to Behat" in the "#themeboostunionadvtile1 .card .card-footer #themeboostunionadvtile1link" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Link to Behat</span>" in the "#themeboostunionadvtile1 .card .card-footer #themeboostunionadvtile1link" "css_element"
    And I should not see "Link to BehatLink zu Behat" in the "#themeboostunionadvtile1 .card .card-footer #themeboostunionadvtile1link" "css_element"

  Scenario: Setting: Advertisement tiles - Display the links in the advertisement tiles
    Given the following config values are set as admin:
      | config          | value          | plugin            |
      | tile1enabled    | yes            | theme_boost_union |
      | tile1link       |                | theme_boost_union |
      | tile1linktitle  |                | theme_boost_union |
      | tile1linktarget | same           | theme_boost_union |
      | tile2enabled    | yes            | theme_boost_union |
      | tile2link       | www.behat.de   | theme_boost_union |
      | tile2linktitle  |                | theme_boost_union |
      | tile2linktarget | same           | theme_boost_union |
      | tile3enabled    | yes            | theme_boost_union |
      | tile3link       | www.behat.com  | theme_boost_union |
      | tile3linktitle  | Link to Behat  | theme_boost_union |
      | tile3linktarget | same           | theme_boost_union |
      | tile4enabled    | yes            | theme_boost_union |
      | tile4link       | www.google.com | theme_boost_union |
      | tile4linktitle  | Link to Google | theme_boost_union |
      | tile4linktarget | new            | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionadvtile1link" "css_element" should not exist
    And "#themeboostunionadvtile2link" "css_element" should exist
    And I should see "Link" in the "#themeboostunionadvtile2link" "css_element"
    And I should not see "www.behat.de" in the "#themeboostunionadvtile2link" "css_element"
    And the "href" attribute of "#themeboostunionadvtile2link" "css_element" should contain "www.behat.de"
    And "#themeboostunionadvtile3link" "css_element" should exist
    And I should see "Link to Behat" in the "#themeboostunionadvtile3link" "css_element"
    And I should not see "www.behat.com" in the "#themeboostunionadvtile3link" "css_element"
    And the "href" attribute of "#themeboostunionadvtile3link" "css_element" should contain "www.behat.com"
    And the "target" attribute of "#themeboostunionadvtile3link" "css_element" should not be set
    And the "rel" attribute of "#themeboostunionadvtile3link" "css_element" should not be set
    And "#themeboostunionadvtile4link" "css_element" should exist
    And I should see "Link to Google" in the "#themeboostunionadvtile4link" "css_element"
    And I should not see "www.google.com" in the "#themeboostunionadvtile4link" "css_element"
    And the "href" attribute of "#themeboostunionadvtile4link" "css_element" should contain "www.google.com"
    And the "target" attribute of "#themeboostunionadvtile4link" "css_element" should contain "_blank"
    And the "rel" attribute of "#themeboostunionadvtile4link" "css_element" should contain "noopener"
    And the "rel" attribute of "#themeboostunionadvtile4link" "css_element" should contain "noreferrer"

  Scenario Outline: Setting: Advertisement tiles - Display the tiles according to the configured orders
    Given the following config values are set as admin:
      | config       | value                             | plugin            |
      | tilecolumns  | 2                                 | theme_boost_union |
      | tile1enabled | yes                               | theme_boost_union |
      | tile1content | This is a test content for tile 1 | theme_boost_union |
      | tile1order   | <ordert1>                         | theme_boost_union |
      | tile2enabled | yes                               | theme_boost_union |
      | tile2content | This is a test content for tile 2 | theme_boost_union |
      | tile2order   | <ordert2>                         | theme_boost_union |
      | tile4enabled | yes                               | theme_boost_union |
      | tile4content | This is a test content for tile 4 | theme_boost_union |
      | tile4order   | <ordert4>                         | theme_boost_union |
      | tile6enabled | yes                               | theme_boost_union |
      | tile6content | This is a test content for tile 6 | theme_boost_union |
      | tile6order   | <ordert6>                         | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "//div[@id='themeboostunionadvtiles']/div[contains(@class, 'row')]/*[<positiont1>][@id='themeboostunionadvtile1']" "xpath_element" should exist
    And "//div[@id='themeboostunionadvtiles']/div[contains(@class, 'row')]/*[<positiont2>][@id='themeboostunionadvtile2']" "xpath_element" should exist
    And "//div[@id='themeboostunionadvtiles']/div[contains(@class, 'row')]/*[<positiont4>][@id='themeboostunionadvtile4']" "xpath_element" should exist
    And "//div[@id='themeboostunionadvtiles']/div[contains(@class, 'row')]/*[<positiont6>][@id='themeboostunionadvtile6']" "xpath_element" should exist

    Examples:
      | ordert1  | positiont1 | ordert2  | positiont2 | ordert4  | positiont4 | ordert6  | positiont6 |
      | 1        | 1          | 2        | 2          | 3        | 3          | 4        | 4          |
      | 2        | 2          | 4        | 4          | 3        | 3          | 1        | 1          |
      | 1        | 1          | 4        | 4          | 3        | 3          | 1        | 2          |
      | 1        | 1          | 1        | 2          | 2        | 3          | 3        | 4          |
      | 5        | 2          | 6        | 3          | 3        | 1          | 8        | 4          |

  @javascript @_file_upload
  Scenario: Setting: Advertisement tiles - Display the uploaded background image
    Given the following config values are set as admin:
      | config       | value                             | plugin            |
      | tile1enabled | yes                               | theme_boost_union |
      | tile1content | This is a test content for tile 1 | theme_boost_union |
      | tile2enabled | no                                | theme_boost_union |
      | tile2content | This is a test content for tile 2 | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Content" in site administration
    And I click on "Advertisement tiles" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Advertisement tile 1 background image" filemanager
    And I press "Save changes"
    And I am on site homepage
    And Behat debugging is enabled
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "teacher1"
    And I am on site homepage
    Then "//div[@id='themeboostunionadvtile1']/*[1][contains(@style, 'pluginfile.php/1/theme_boost_union/tilebackgroundimage1/0/login_bg1.png')]" "xpath_element" should exist
    And "//div[@id='themeboostunionadvtile2']/*[1][contains(@style, 'pluginfile.php')]" "xpath_element" should not exist

  Scenario Outline: Setting: Advertisement tiles - Define the tile (min-)height.
    Given the following config values are set as admin:
      | config       | value                             | plugin            |
      | tileheight   | <height>                          | theme_boost_union |
      | tile1enabled | yes                               | theme_boost_union |
      | tile1content | This is a test content for tile 1 | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "//div[@id='themeboostunionadvtile1']/*[1][contains(@class, 'card') and contains(@style, 'min-height: <height>')]" "xpath_element" should exist

    # We do not want to burn too much CPU time by testing all available options. We just test the default value and one non-default value.
    Examples:
      | height |
      | 150px  |
      | 250px  |

  @javascript @_file_upload
  Scenario Outline: Setting: Advertisement tiles - Define the background image position.
    Given the following config values are set as admin:
      | config                       | value                             | plugin            |
      | tile1enabled                 | yes                               | theme_boost_union |
      | tile1content                 | This is a test content for tile 1 | theme_boost_union |
      | tile1backgroundimageposition | <position>                        | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Content" in site administration
    And I click on "Advertisement tiles" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Advertisement tile 1 background image" filemanager
    And I press "Save changes"
    And I am on site homepage
    And Behat debugging is enabled
    And I log out
    And I am on site homepage
    And I follow "Log in"
    And I log in as "teacher1"
    And I am on site homepage
    Then "//div[@id='themeboostunionadvtile1']/*[1][contains(@class, 'card') and contains(@style, 'background-position: <position>')]" "xpath_element" should exist

    # We do not want to burn too much CPU time by testing all available options. We just test the default value and one non-default value.
    Examples:
      | position      |
      | center center |
      | left top      |

  @javascript
  Scenario: Setting: Advertisement tiles - Show and hide the admin settings based on the main "Enable advertisement tile x" setting
    Given the following config values are set as admin:
      | config       | value | plugin            |
      | tile1enabled | yes   | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Content" in site administration
    And I click on "Advertisement tiles" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then "#admin-tile1title" "css_element" should be visible
    Then "#admin-tile3title" "css_element" should not be visible
    Then "#admin-tile4title" "css_element" should not be visible
    And I select "Yes" from the "Enable advertisement tile 4" singleselect
    Then "#admin-tile1title" "css_element" should be visible
    Then "#admin-tile3title" "css_element" should not be visible
    Then "#admin-tile4title" "css_element" should be visible
    And I select "No" from the "Enable advertisement tile 1" singleselect
    Then "#admin-tile1title" "css_element" should not be visible
    Then "#admin-tile3title" "css_element" should not be visible
    Then "#admin-tile4title" "css_element" should be visible

  @javascript
  Scenario Outline: Setting: Advertisement tiles - Display the configured content style
    Given the following config values are set as admin:
      | config            | value                             | plugin            |
      | tile1contentstyle | <style>                           | theme_boost_union |
      | tile1enabled      | yes                               | theme_boost_union |
      | tile1content      | This is a test content for tile 1 | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "//div[@id='themeboostunionadvtile1']//div[contains(@class, 'card-text') and contains(@class, '<cssclass>')]" "xpath_element" <shouldornot> exist

    # We do not want to burn too much CPU time by testing all available options. We just test the default value and two non-default values.
    Examples:
      | style      | cssclass        | shouldornot |
      | nochange   | tile-light      | should not  |
      | light      | tile-light      | should      |
      | darkshadow | tile-darkshadow | should      |
