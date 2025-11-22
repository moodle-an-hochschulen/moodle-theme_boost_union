@theme @theme_boost_union @theme_boost_union_flavourssettings @theme_boost_union_flavourssettings_look
Feature: Configuring the theme_boost_union plugin on the "Flavours" page, applying the look features
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "categories" exist:
      | name   | category | idnumber |
      | Cat 1  | 0        | CAT1     |
    And the following "courses" exist:
      | fullname  | shortname | category |
      | Course 1  | C1        | CAT1     |

  # Unfortunately, this can't be tested with Behat yet as the full logo is not displayed anywhere outside the login page
  # Scenario: Flavours: Logo - Upload a logo (with a global logo not having been uploaded before)

  # Unfortunately, this can't be tested with Behat yet as the full logo is not displayed anywhere outside the login page
  # Scenario: Flavours: Logo - Upload a logo (with a global logo being overridden)

  # Unfortunately, this can't be tested with Behat yet as the full logo is not displayed anywhere outside the login page
  # Scenario: Flavours: Logo - Do not upload a logo (with a global logo being served properly)

  Scenario: Flavours: Compact logo - Upload a compact logo (with a global compact logo not having been uploaded before)
    Given the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    And the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                  | filepath                                         |
      | My shiny new flavour | flavours_look_logocompact | theme/boost_union/tests/fixtures/flavourlogo.png |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    # We can't check the uploaded image file visually, but we can verify that the compact logo is shipped from the theme_boost_union flavour filearea.
    Then "//nav[contains(@class, 'navbar')]//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/flavours_look_logocompact')][contains(@src, 'flavourlogo.png')]" "xpath_element" should exist

  Scenario: Flavours: Compact logo - Upload a compact logo (with a global compact logo being overridden)
    Given the following "theme_boost_union > setting files" exist:
      | filearea    | filepath                                        |
      | logocompact | theme/boost_union/tests/fixtures/moodlelogo.png |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    And the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                  | filepath                                         |
      | My shiny new flavour | flavours_look_logocompact | theme/boost_union/tests/fixtures/flavourlogo.png |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    # We can't check the uploaded image file visually, but we can verify that the compact logo is shipped from the theme_boost_union flavour filearea.
    Then "//nav[contains(@class, 'navbar')]//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/flavours_look_logocompact')][contains(@src, 'flavourlogo.png')]" "xpath_element" should exist

  Scenario: Flavours: Compact logo - Do not upload a compact logo (with a global compact logo being served properly)
    Given the following "theme_boost_union > setting files" exist:
      | filearea    | filepath                                        |
      | logocompact | theme/boost_union/tests/fixtures/moodlelogo.png |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    # We can't check the uploaded image file visually, but we can verify that the compact logo is shipped from the theme_boost_union global logo filearea.
    Then "//nav[contains(@class, 'navbar')]//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/logocompact')][contains(@src, 'moodlelogo.png')]" "xpath_element" should exist

  Scenario: Setting: Compact logo - Upload a PNG compact logo and check that it is resized on the server-side
    Given the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    And the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                  | filepath                                         |
      | My shiny new flavour | flavours_look_logocompact | theme/boost_union/tests/fixtures/flavourlogo.png |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then "//nav[contains(@class, 'navbar')]//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/flavours_look_logocompact/')][contains(@src, '/300x300/')][contains(@src, 'flavourlogo.png')]" "xpath_element" should exist

  Scenario: Setting: Compact logo - Upload a SVG compact logo and check that it is not resized on the server-side
    Given the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    And the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                  | filepath                                         |
      | My shiny new flavour | flavours_look_logocompact | theme/boost_union/tests/fixtures/flavourlogo.svg |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then "//nav[contains(@class, 'navbar')]//img[contains(@class, 'logo')][contains(@src, 'pluginfile.php/1/theme_boost_union/flavours_look_logocompact/')][contains(@src, '/1/')][contains(@src, 'flavourlogo.svg')]" "xpath_element" should exist

  Scenario: Flavours: Favicon - Upload a favicon (with a global favicon not having been uploaded before)
    Given the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    And the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea              | filepath                                            |
      | My shiny new flavour | flavours_look_favicon | theme/boost_union/tests/fixtures/flavourfavicon.ico |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    # We can't check the uploaded image file visually, but we can verify that the favicon is shipped from the theme_boost_union flavour filearea.
    Then "//head//link[contains(@rel, 'shortcut')][contains(@href, 'pluginfile.php/1/theme_boost_union/flavours_look_favicon')][contains(@href, 'flavourfavicon.ico')]" "xpath_element" should exist

  Scenario: Flavours: Favicon - Upload a favicon (with a global favicon being overridden)
    Given the following "theme_boost_union > setting files" exist:
      | filearea | filepath                                      |
      | favicon  | theme/boost_union/tests/fixtures/favicon.ico |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    And the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea              | filepath                                            |
      | My shiny new flavour | flavours_look_favicon | theme/boost_union/tests/fixtures/flavourfavicon.ico |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    # We can't check the uploaded image file visually, but we can verify that the favicon is shipped from the theme_boost_union flavour filearea.
    Then "//head//link[contains(@rel, 'shortcut')][contains(@href, 'pluginfile.php/1/theme_boost_union/flavours_look_favicon')][contains(@href, 'flavourfavicon.ico')]" "xpath_element" should exist

  Scenario: Flavours: Favicon - Do not upload a favicon (with a global favicon being served properly)
    Given the following "theme_boost_union > setting files" exist:
      | filearea | filepath                                     |
      | favicon  | theme/boost_union/tests/fixtures/favicon.ico |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    # We can't check the uploaded image file visually, but we can verify that the favicon is shipped from the theme_boost_union global favicon filearea.
    Then "//head//link[contains(@rel, 'shortcut')][contains(@href, 'pluginfile.php/1/theme_boost_union/favicon')][contains(@href, 'favicon.ico')]" "xpath_element" should exist

  @javascript
  Scenario: Flavours: Background image - Upload a background image (with a global background image not having been uploaded before)
    Given the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    And the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                      | filepath                                       |
      | My shiny new flavour | flavours_look_backgroundimage | theme/boost_union/tests/fixtures/login_bg2.png |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element "body" should have computed style "background-size" "cover"
    And DOM element "body" should have background image with file name "login_bg2.png"

  @javascript
  Scenario: Flavours: Background image - Upload a background image (with a global background image being overridden)
    Given the following "theme_boost_union > setting files" exist:
      | filearea        | filepath                                       |
      | backgroundimage | theme/boost_union/tests/fixtures/login_bg1.png |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    And the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                      | filepath                                       |
      | My shiny new flavour | flavours_look_backgroundimage | theme/boost_union/tests/fixtures/login_bg2.png |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element "body" should have computed style "background-size" "cover"
    And DOM element "body" should have background image with file name "login_bg2.png"

  @javascript
  Scenario: Flavours: Background image - Do not upload a background image (with a global background image being served properly)
    Given the following "theme_boost_union > setting files" exist:
      | filearea        | filepath                                       |
      | backgroundimage | theme/boost_union/tests/fixtures/login_bg1.png |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element "body" should have computed style "background-size" "cover"
    And DOM element "body" should have background image with file name "login_bg1.png"

  @javascript
  Scenario Outline: Flavours: Background image - Define the background image position (with a global setting not having been set before)
    Given the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids | look_backgroundimagepos |
      | My shiny new flavour | CAT1                  | <position>              |
    And the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                      | filepath                                       |
      | My shiny new flavour | flavours_look_backgroundimage | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element "body" should have computed style "background-position" "<cssvalue>"

    # We do not want to burn too much CPU time by testing all available options. We just test one non-default value.
    Examples:
      | position      | cssvalue |
      | center center | 50% 50%  |

  @javascript
  Scenario Outline: Flavours: Background image - Define the background image position (with the global setting being overridden)
    Given the following config values are set as admin:
      | config                  | value            | plugin            |
      | backgroundimageposition | <globalposition> | theme_boost_union |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids | look_backgroundimagepos |
      | My shiny new flavour | CAT1                  | <position>              |
    And the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                      | filepath                                       |
      | My shiny new flavour | flavours_look_backgroundimage | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element "body" should have computed style "background-position" "<cssvalue>"
    And DOM element "body" should not have computed style "background-position" "<shouldnotcssvalue>"

    # We do not want to burn too much CPU time by testing all available options. We just test one non-default value.
    Examples:
      | globalposition | position      | cssvalue | shouldnotcssvalue |
      | bottom right   | center center | 50% 50%  | 100% 100%         |

  @javascript
  Scenario Outline: Flavours: Background image - Do not define the background image position (with a global setting being served properly)
    Given the following config values are set as admin:
      | config                  | value      | plugin            |
      | backgroundimageposition | <position> | theme_boost_union |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids | look_backgroundimagepos |
      | My shiny new flavour | CAT1                  | nochange                |
    And the following "theme_boost_union > flavour files" exist:
      | flavour              | filearea                      | filepath                                       |
      | My shiny new flavour | flavours_look_backgroundimage | theme/boost_union/tests/fixtures/login_bg1.png |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then DOM element "body" should have computed style "background-position" "<cssvalue>"

    # We do not want to burn too much CPU time by testing all available options. We just test one non-default value.
    Examples:
      | position      | cssvalue |
      | center center | 50% 50%  |

  @javascript
  Scenario: Flavours: Brand color - Set the brand color (with a global color not having been set before)
    Given the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids | look_brandcolor |
      | My shiny new flavour | CAT1                  | #FF0000         |
    And the following "activities" exist:
      | activity | name      | intro                                                     | course |
      | label    | Label one | <span class="mytesttext text-primary">My test text</span> | C1     |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "rgb(255, 0, 0)"

  @javascript
  Scenario: Flavours: Brand color - Set the brand color (with the global setting being overridden)
    Given the following config values are set as admin:
      | config     | value   | plugin            |
      | brandcolor | #FFFFFF | theme_boost_union |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids | look_brandcolor |
      | My shiny new flavour | CAT1                  | #FF0000         |
    And the following "activities" exist:
      | activity | name      | intro                                                     | course |
      | label    | Label one | <span class="mytesttext text-primary">My test text</span> | C1     |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "rgb(255, 0, 0)"

  @javascript
  Scenario: Flavours: Brand color - Do not set the brand color (with a global setting being served properly)
    Given the following config values are set as admin:
      | config     | value   | plugin            |
      | brandcolor | #FF0000 | theme_boost_union |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    And the following "activities" exist:
      | activity | name      | intro                                                     | course |
      | label    | Label one | <span class="mytesttext text-primary">My test text</span> | C1     |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "rgb(255, 0, 0)"

  @javascript
  Scenario Outline: Flavours: Bootstrap colors - Set the Bootstrap colors (with a global color not having been set before)
    Given the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids | look_bootstrapcolor<type> |
      | My shiny new flavour | CAT1                  | <colorhex>                |
    And the following "activities" exist:
      | activity | name      | intro                                                    | course |
      | label    | Label one | <span class="mytesttext text-<type>">My test text</span> | C1     |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "<colorrgb>"

    Examples:
      | type    | colorhex | colorrgb         |
      | success | #FF0000  | rgb(255, 0, 0)   |
      | info    | #00FF00  | rgb(0, 255, 0)   |
      | warning | #0000FF  | rgb(0, 0, 255)   |
      | danger  | #FFFF00  | rgb(255, 255, 0) |

  @javascript
  Scenario Outline: Flavours: Bootstrap colors - Set the Bootstrap colors (with the global setting being overridden)
    Given the following config values are set as admin:
      | config               | value   | plugin            |
      | bootstrapcolor<type> | #FFFFFF | theme_boost_union |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids | look_bootstrapcolor<type> |
      | My shiny new flavour | CAT1                  | <colorhex>                |
    And the following "activities" exist:
      | activity | name      | intro                                                    | course |
      | label    | Label one | <span class="mytesttext text-<type>">My test text</span> | C1     |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "<colorrgb>"

    # We only test 1 out of 4 color types as we have tested the rest already in the previous scenario.
    Examples:
      | type    | colorhex | colorrgb       |
      | success | #FF0000  | rgb(255, 0, 0) |

  @javascript
  Scenario Outline: Flavours: Bootstrap colors - Do not set the Bootstrap colors (with a global setting being served properly)
    Given the following config values are set as admin:
      | config               | value      | plugin            |
      | bootstrapcolor<type> | <colorhex> | theme_boost_union |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    And the following "activities" exist:
      | activity | name      | intro                                                    | course |
      | label    | Label one | <span class="mytesttext text-<type>">My test text</span> | C1     |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I should see "My test text"
    Then DOM element ".mytesttext" should have computed style "color" "<colorrgb>"

    # We only test 1 out of 4 color types as we have tested the rest already in the previous scenario.
    Examples:
      | type    | colorhex | colorrgb       |
      | success | #FF0000  | rgb(255, 0, 0) |

  @javascript
  Scenario Outline: Flavours: Activity icon colors - Setting the color (with a global color not having been set before)
    Given the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids | look_aicol<purposename> |
      | My shiny new flavour | CAT1                  | <colorhex>              |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I click on "Add content" "button" in the "New section" "section"
    And I click on "Activity or resource" "button" in the "New section" "section"
    # First, we test that the default filter is _not_ set anymore.
    Then DOM element "[data-region=chooser-container] [data-internal=<modname>] .activityiconcontainer img" should not have a CSS filter close to hex color "<originalhex>"
    # And then we test if the applied filter is close enough to the hex color.
    And DOM element "[data-region=chooser-container] [data-internal=<modname>] .activityiconcontainer img" should have a CSS filter close enough to hex color "<colorhex>"

    # Unfortunately, we can only test 5 out of 7 purpose types as Moodle does does not ship with any activity with the
    # administration and interface types. But this should be an acceptable test coverage anyway.
    Examples:
      | purposename        | modname | colorhex | originalhex |
      | assessment         | assign  | #FFFF00  | #f90086     |
      | collaboration      | data    | #00FF00  | #5b40ff     |
      | communication      | choice  | #0000FF  | #eb6200     |
      | content            | book    | #FFFF00  | #0099ad     |
      | interactivecontent | lesson  | #00FFFF  | #8d3d1b     |

  @javascript
  Scenario Outline: Flavours: Activity icon colors - Setting the color (with the global setting being overridden)
    Given the following config values are set as admin:
      | config                         | value   | plugin            |
      | activityiconcolor<purposename> | #00FFFF | theme_boost_union |
    And the theme cache is purged and the theme is reloaded
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids | look_aicol<purposename> |
      | My shiny new flavour | CAT1                  | <colorhex>              |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I click on "Add content" "button" in the "New section" "section"
    And I click on "Activity or resource" "button" in the "New section" "section"
    # First, we test that the default filter is _not_ set anymore.
    Then DOM element "[data-region=chooser-container] [data-internal=<modname>] .activityiconcontainer img" should not have a CSS filter close to hex color "<originalhex>"
    # And then we test if the applied filter is close enough to the hex color.
    And DOM element "[data-region=chooser-container] [data-internal=<modname>] .activityiconcontainer img" should have a CSS filter close enough to hex color "<colorhex>"

    # We only test 1 out of 6 purpose types as we have tested the rest already in the previous scenario.
    Examples:
      | purposename        | modname | colorhex | originalhex |
      | assessment         | assign  | #FFFF00  | #f90086     |

  @javascript
  Scenario Outline: Flavours: Activity icon colors - Do not set the color (with a global setting being served properly)
    Given the following config values are set as admin:
      | config                         | value      | plugin            |
      | activityiconcolor<purposename> | <colorhex> | theme_boost_union |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids |
      | My shiny new flavour | CAT1                  |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I turn editing mode on
    And I click on "Add content" "button" in the "New section" "section"
    And I click on "Activity or resource" "button" in the "New section" "section"
    # First, we test that the default filter is _not_ set anymore.
    Then DOM element "[data-region=chooser-container] [data-internal=<modname>] .activityiconcontainer img" should not have a CSS filter close to hex color "<originalhex>"
    # And then we test if the applied filter is close enough to the hex color.
    And DOM element "[data-region=chooser-container] [data-internal=<modname>] .activityiconcontainer img" should have a CSS filter close enough to hex color "<colorhex>"

    # We only test 1 out of 6 purpose types as we have tested the rest already in the previous scenario.
    Examples:
      | purposename        | modname | colorhex | originalhex |
      | assessment         | assign  | #FFFF00  | #f90086     |

  Scenario Outline: Setting: Navbar color - Set the navbar color (with a global color not having been set before)
    Given the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids | look_navbarcolor |
      | My shiny new flavour | CAT1                  | <setting>        |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then the "class" attribute of ".navbar" "css_element" should contain "<classes>"
    And the "data-bs-theme" attribute of ".navbar" "css_element" should <databstheme>

    Examples:
      | setting      | classes    | databstheme     |
      | light        | bg-body    | not be set      |
      | dark         | bg-dark    | contain "dark"  |
      | primarylight | bg-primary | contain "light" |
      | primarydark  | bg-primary | contain "dark"  |

  Scenario Outline: Setting: Navbar color - Set the navbar color (with the global setting being overridden)
    Given the following config values are set as admin:
      | config      | value           | plugin            |
      | navbarcolor | <globalsetting> | theme_boost_union |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids | look_navbarcolor |
      | My shiny new flavour | CAT1                  | <setting>        |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then the "class" attribute of ".navbar" "css_element" should contain "<classes>"
    And the "class" attribute of ".navbar" "css_element" should not contain "<shouldnotclasses>"
    And the "data-bs-theme" attribute of ".navbar" "css_element" should <databstheme>

    # We only test 1 out of 4 color types as we have tested the rest already in the previous scenario.
    Examples:
      | globalsetting | setting | classes | databstheme | shouldnotclasses |
      | dark          | light   | bg-body | not be set  | bg-dark          |

  Scenario Outline: Setting: Navbar color - Do not set the navbar color (with a global setting being served properly)
    Given the following config values are set as admin:
      | config      | value     | plugin            |
      | navbarcolor | <setting> | theme_boost_union |
    And the following "theme_boost_union > flavours" exist:
      | title                | applytocategories_ids | look_navbarcolor |
      | My shiny new flavour | CAT1                  | nochange         |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    Then the "class" attribute of ".navbar" "css_element" should contain "<classes>"
    And the "data-bs-theme" attribute of ".navbar" "css_element" should <databstheme>

    # We only test 1 out of 4 color types as we have tested the rest already in the previous scenario.
    Examples:
      | setting | classes | databstheme    |
      | dark    | bg-dark | contain "dark" |

  @javascript
  Scenario: Flavours: Raw (initial) SCSS - Add custom SCSS to the page
    Given the following "theme_boost_union > flavours" exist:
      | title                | look_rawscsspre    | look_rawscss                              |
      | My shiny new flavour | $myvariable: none; | #page-header h1 { display: $myvariable; } |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on ".action-preview" "css_element" in the "My shiny new flavour" "table_row"
    Then I should not see "Preview flavour" in the "#page-header .page-header-headings" "css_element"
