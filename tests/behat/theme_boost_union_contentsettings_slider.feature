@theme @theme_boost_union @theme_boost_union_contentsettings @theme_boost_union_contentsettings_slider @javascript @_file_upload

Feature: Configuring the theme_boost_union plugin for the "Slider" tab on the "Content" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | teacher1 |
    And the following config values are set as admin:
      | config        | value                              | plugin            |
      | slide1enabled | yes                                | theme_boost_union |
      | slide1caption | Slide 1                            | theme_boost_union |
      | slide1content | This is a test content for slide 1 | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Content" in site administration
    And I click on "Slider" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Slide 1 background image" filemanager
    And I press "Save changes"
    And I am on site homepage
    And Behat debugging is enabled
    And I log out

  Scenario: Setting: Slider - Display the slider on the frontpage only and nowhere else
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionslide1" "css_element" should exist
    And I follow "Dashboard"
    Then "#themeboostunionslide1" "css_element" should not exist
    And I follow "My courses"
    Then "#themeboostunionslide1" "css_element" should not exist
    When I am on "Course 1" course homepage
    Then "#themeboostunionslide1" "css_element" should not exist
    When I log out
    And I click on "Log in" "link" in the ".logininfo" "css_element"
    Then "#themeboostunionslide1" "css_element" should not exist

  Scenario Outline: Setting: Slider - Display the slider wrapper and the individual slide only if it is enabled
    Given the following config values are set as admin:
      | config        | value     | plugin            |
      | slide1enabled | <enabled> | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionslider-wrapper" "css_element" <shouldexist>
    And "#themeboostunionslide1" "css_element" <shouldexist>

    Examples:
      | enabled | shouldexist      |
      | yes     | should exist     |
      | no      | should not exist |

  Scenario Outline: Setting: Slider - Display the slider before or after the main output of site home
    Given the following config values are set as admin:
      | config                  | value                             | plugin            |
      | sliderfrontpageposition | <sliderposition>                  | theme_boost_union |
      | tilefrontpageposition   | <advtileposition>                 | theme_boost_union |
      | tile1enabled            | yes                               | theme_boost_union |
      | tile1title              | Tile 1                            | theme_boost_union |
      | tile1content            | This is a test content for tile 1 | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionslider" "css_element" should appear <beforeafter1> "div[role='main']" "css_element"
    And "#themeboostunionslider" "css_element" should appear <beforeafter2> "#themeboostunionadvtiles" "css_element"

    Examples:
      | sliderposition | advtileposition | beforeafter1 | beforeafter2 |
      | 1              | 1               | before       | before       |
      | 2              | 1               | before       | after        |
      | 3              | 2               | after        | before       |
      | 4              | 2               | after        | after        |

  Scenario Outline: Setting: Slider - Enable arrow navigation
    Given the following config values are set as admin:
      | config         | value     | plugin            |
      | sliderarrownav | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionslider .carousel-control-next" "css_element" <shouldornot> exist
    And "#themeboostunionslider .carousel-control-prev" "css_element" <shouldornot> exist

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: Slider - Enable slider indicator navigation
    Given the following config values are set as admin:
      | config             | value     | plugin            |
      | sliderindicatornav | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionslider .carousel-indicators" "css_element" <shouldornot> exist

    Examples:
      | setting | shouldornot |
      | yes     | should      |
      | no      | should not  |

  Scenario Outline: Setting: Slider - Slider animation type
    Given the following config values are set as admin:
      | config          | value     | plugin            |
      | slideranimation | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionslider.slide" "css_element" <slideshouldornot> exist
    And "#themeboostunionslider.carousel-fade" "css_element" <carouselshouldornot> exist

    Examples:
      | setting | slideshouldornot | carouselshouldornot |
      | 0       | should not       | should not          |
      | 1       | should           | should not          |
      | 2       | should           | should              |

  Scenario Outline: Setting: Slider - Slider interval speed
    Given the following config values are set as admin:
      | config         | value     | plugin            |
      | sliderinterval | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then the "data-interval" attribute of "#themeboostunionslider" "css_element" should contain "<speed>"

    Examples:
      | setting | speed |
      | 500     | 1000  |
      | 4321    | 4321  |
      | 10001   | 10000 |

  Scenario Outline: Setting: Slider - Allow slider keyboard interaction
    Given the following config values are set as admin:
      | config         | value     | plugin            |
      | sliderkeyboard | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then the "data-keyboard" attribute of "#themeboostunionslider" "css_element" should contain "<keyboard>"

    Examples:
      | setting | keyboard |
      | yes     | true     |
      | no      | false    |

  Scenario Outline: Setting: Slider - Pause slider on mouseover
    Given the following config values are set as admin:
      | config      | value     | plugin            |
      | sliderpause | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then the "data-pause" attribute of "#themeboostunionslider" "css_element" should contain "<pause>"

    Examples:
      | setting | pause |
      | yes     | hover |
      | no      | false |

  Scenario Outline: Setting: Slider - Cycle through slides
    Given the following config values are set as admin:
      | config     | value     | plugin            |
      | sliderride | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then the "data-ride" attribute of "#themeboostunionslider" "css_element" should contain "<ride>"

    Examples:
      | setting | ride     |
      | 0       | carousel |
      | 1       | true     |
      | 2       | false    |

  Scenario Outline: Setting: Slider - Continuously cycle through slides
    Given the following config values are set as admin:
      | config     | value     | plugin            |
      | sliderwrap | <setting> | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then the "data-wrap" attribute of "#themeboostunionslider" "css_element" should contain "<wrap>"

    Examples:
      | setting | wrap  |
      | yes     | true  |
      | no      | false |

  Scenario: Setting: Slider - Display the uploaded background image within a slide
    Given the following config values are set as admin:
      | config                   | value                         | plugin            |
      | slide1backgroundimagealt | This is the image description | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionslide1 > img" "css_element" should exist
    And the "src" attribute of "#themeboostunionslide1 > img" "css_element" should contain "pluginfile.php/1/theme_boost_union/slidebackgroundimage1/0/login_bg1.png"
    And the "alt" attribute of "#themeboostunionslide1 > img" "css_element" should contain "This is the image description"

  Scenario: Setting: Slider - Display an individual slide only if an image is uploaded
    Given the following config values are set as admin:
      | config        | value                              | plugin            |
      | slide2enabled | yes                                | theme_boost_union |
      | slide2caption | Slide 2                            | theme_boost_union |
      | slide2content | This is a test content for slide 2 | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionslider-wrapper" "css_element" should exist
    And "#themeboostunionslide1" "css_element" should exist
    And "#themeboostunionslide2" "css_element" should not exist

  Scenario: Setting: Slider - Display the caption and the content in the corresponding HTML elements
    Given the following config values are set as admin:
      | config        | value                                                                                                                                               | plugin            |
      | slide1enabled | yes                                                                                                                                                 | theme_boost_union |
      | slide1caption | <span lang="en" class="multilang">Slide 1</span><span lang="de" class="multilang">Folie 1</span>                                                    | theme_boost_union |
      | slide1content | <span lang="en" class="multilang">This is a test content for slide 1</span><span lang="de" class="multilang">Dies ist Testinhalt für Folie 1</span> | theme_boost_union |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionslider #themeboostunionslide1" "css_element" should exist
    And I should see "This is a test content for slide 1" in the "#themeboostunionslide1 .carousel-caption" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">This is a test content for slide 1</span>" in the "#themeboostunionslide1 .carousel-caption" "css_element"
    And I should not see "This is a test content for slide 1Dies ist Testinhalt für Folie 1" in the "#themeboostunionslide1 .carousel-caption" "css_element"
    And I should see "Slide 1" in the "#themeboostunionslide1 .carousel-caption h5" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Slide 1</span>" in the "#themeboostunionslide1 .carousel-caption h5" "css_element"
    And I should not see "Slide 1Folie 1" in the "#themeboostunionslide1 .carousel-caption h5" "css_element"

  Scenario: Setting: Slider - Use the links in the slider
    Given the following config values are set as admin:
      | config           | value                              | plugin            |
      | slide1enabled    | yes                                | theme_boost_union |
      | slide1caption    | Slide 1                            | theme_boost_union |
      | slide1content    | This is a test content for slide 1 | theme_boost_union |
      | slide1link       |                                    | theme_boost_union |
      | slide1linktitle  |                                    | theme_boost_union |
      | slide1linksource | 0                                  | theme_boost_union |
      | slide1linktarget | same                               | theme_boost_union |
      | slide2enabled    | yes                                | theme_boost_union |
      | slide2caption    | Slide 2                            | theme_boost_union |
      | slide2content    | This is a test content for slide 2 | theme_boost_union |
      | slide2link       | www.behat.de                       | theme_boost_union |
      | slide2linktitle  |                                    | theme_boost_union |
      | slide2linksource | 0                                  | theme_boost_union |
      | slide2linktarget | same                               | theme_boost_union |
      | slide3enabled    | yes                                | theme_boost_union |
      | slide3caption    | Slide 3                            | theme_boost_union |
      | slide3content    | This is a test content for slide 3 | theme_boost_union |
      | slide3link       | www.behat.com                      | theme_boost_union |
      | slide3linktitle  | Link to Behat                      | theme_boost_union |
      | slide3linksource | 1                                  | theme_boost_union |
      | slide3linktarget | same                               | theme_boost_union |
      | slide4enabled    | yes                                | theme_boost_union |
      | slide4caption    | Slide 4                            | theme_boost_union |
      | slide4content    | This is a test content for slide 4 | theme_boost_union |
      | slide4link       | www.google.com                     | theme_boost_union |
      | slide4linktitle  | Link to Google                     | theme_boost_union |
      | slide4linksource | 2                                  | theme_boost_union |
      | slide4linktarget | new                                | theme_boost_union |
    And I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Content" in site administration
    And I click on "Slider" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Slide 2 background image" filemanager
    # For a strange reason, Behat fails if we upload all images at once. So we simply save the form after each upload.
    And I press "Save changes"
    And I navigate to "Appearance > Boost Union > Content" in site administration
    And I click on "Slider" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Slide 3 background image" filemanager
    And I press "Save changes"
    And I navigate to "Appearance > Boost Union > Content" in site administration
    And I click on "Slider" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Slide 4 background image" filemanager
    And I press "Save changes"
    And I am on site homepage
    And Behat debugging is enabled
    And I log out
    When I log in as "teacher1"
    And I am on site homepage
    Then "#themeboostunionslide1 > a" "css_element" should not exist
    And "#themeboostunionslide1 > .carousel-caption > a" "css_element" should not exist
    And "#themeboostunionslide2 > a" "css_element" should exist
    And "#themeboostunionslide2 > .carousel-caption > a" "css_element" should exist
    And the "href" attribute of "#themeboostunionslide2 > a" "css_element" should contain "www.behat.de"
    And the "href" attribute of "#themeboostunionslide2 > .carousel-caption > a" "css_element" should contain "www.behat.de"
    And the "title" attribute of "#themeboostunionslide2 > a" "css_element" should not be set
    And the "title" attribute of "#themeboostunionslide2 > .carousel-caption > a" "css_element" should not be set
    And the "target" attribute of "#themeboostunionslide2 > a" "css_element" should not be set
    And the "target" attribute of "#themeboostunionslide2 > .carousel-caption > a" "css_element" should not be set
    And the "rel" attribute of "#themeboostunionslide2 > a" "css_element" should not be set
    And the "rel" attribute of "#themeboostunionslide2 > .carousel-caption > a" "css_element" should not be set
    And "#themeboostunionslide3 > a" "css_element" should exist
    And "#themeboostunionslide3 > .carousel-caption > a" "css_element" should not exist
    And the "href" attribute of "#themeboostunionslide3 > a" "css_element" should contain "www.behat.com"
    And the "title" attribute of "#themeboostunionslide3 > a" "css_element" should contain "Link to Behat"
    And the "target" attribute of "#themeboostunionslide3 > a" "css_element" should not be set
    And the "rel" attribute of "#themeboostunionslide3 > a" "css_element" should not be set
    And "#themeboostunionslide4 > a" "css_element" should not exist
    And "#themeboostunionslide4 > .carousel-caption > a" "css_element" should exist
    And the "href" attribute of "#themeboostunionslide4 > .carousel-caption > a" "css_element" should contain "www.google.com"
    And the "title" attribute of "#themeboostunionslide4 > .carousel-caption > a" "css_element" should contain "Link to Google"
    And the "target" attribute of "#themeboostunionslide4 > .carousel-caption > a" "css_element" should contain "_blank"
    And the "rel" attribute of "#themeboostunionslide4 > .carousel-caption > a" "css_element" should contain "noreferrer"
    And the "rel" attribute of "#themeboostunionslide4 > .carousel-caption > a" "css_element" should contain "noreferrer"

  Scenario Outline: Setting: Slider - Display the slides according to the configured orders
    Given the following config values are set as admin:
      | config        | value                             | plugin            |
      | slide1enabled | yes                               | theme_boost_union |
      | slide1content | This is a test content for tile 1 | theme_boost_union |
      | slide1order   | <orders1>                         | theme_boost_union |
      | slide2enabled | yes                               | theme_boost_union |
      | slide2content | This is a test content for tile 2 | theme_boost_union |
      | slide2order   | <orders2>                         | theme_boost_union |
      | slide4enabled | yes                               | theme_boost_union |
      | slide4content | This is a test content for tile 4 | theme_boost_union |
      | slide4order   | <orders4>                         | theme_boost_union |
      | slide6enabled | yes                               | theme_boost_union |
      | slide6content | This is a test content for tile 6 | theme_boost_union |
      | slide6order   | <orders6>                         | theme_boost_union |
    And I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Content" in site administration
    And I click on "Slider" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Slide 2 background image" filemanager
    # For a strange reason, Behat fails if we upload all images at once. So we simply save the form after each upload.
    And I press "Save changes"
    And I navigate to "Appearance > Boost Union > Content" in site administration
    And I click on "Slider" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Slide 4 background image" filemanager
    And I press "Save changes"
    And I navigate to "Appearance > Boost Union > Content" in site administration
    And I click on "Slider" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I upload "theme/boost_union/tests/fixtures/login_bg1.png" file to "Slide 6 background image" filemanager
    And I press "Save changes"
    And I am on site homepage
    And Behat debugging is enabled
    And I log out
    When I log in as "teacher1"
    And I am on site homepage
    Then "//div[@id='themeboostunionslider']/div[contains(@class, 'carousel-inner')]/*[<positions1>][@id='themeboostunionslide1']" "xpath_element" should exist
    And "//div[@id='themeboostunionslider']/div[contains(@class, 'carousel-inner')]/*[<positions2>][@id='themeboostunionslide2']" "xpath_element" should exist
    And "//div[@id='themeboostunionslider']/div[contains(@class, 'carousel-inner')]/*[<positions4>][@id='themeboostunionslide4']" "xpath_element" should exist
    And "//div[@id='themeboostunionslider']/div[contains(@class, 'carousel-inner')]/*[<positions6>][@id='themeboostunionslide6']" "xpath_element" should exist

    Examples:
      | orders1 | positions1 | orders2 | positions2 | orders4 | positions4 | orders6 | positions6 |
      | 1       | 1          | 2       | 2          | 3       | 3          | 4       | 4          |
      | 2       | 2          | 4       | 4          | 3       | 3          | 1       | 1          |
      | 1       | 1          | 4       | 4          | 3       | 3          | 1       | 2          |
      | 1       | 1          | 1       | 2          | 2       | 3          | 3       | 4          |
      | 5       | 2          | 6       | 3          | 3       | 1          | 8       | 4          |

  Scenario: Setting: Slider - Show and hide the admin settings based on the main "Enable slide x" setting
    Given the following config values are set as admin:
      | config        | value | plugin            |
      | slide1enabled | yes   | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Content" in site administration
    And I click on "Slider" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then "#admin-slide1backgroundimage" "css_element" should be visible
    Then "#admin-slide3backgroundimage" "css_element" should not be visible
    Then "#admin-slide4backgroundimage" "css_element" should not be visible
    And I select "Yes" from the "Enable slide 4" singleselect
    Then "#admin-slide1backgroundimage" "css_element" should be visible
    Then "#admin-slide3backgroundimage" "css_element" should not be visible
    Then "#admin-slide4backgroundimage" "css_element" should be visible
    And I select "No" from the "Enable slide 1" singleselect
    Then "#admin-slide1backgroundimage" "css_element" should not be visible
    Then "#admin-slide3backgroundimage" "css_element" should not be visible
    Then "#admin-slide4backgroundimage" "css_element" should be visible

  Scenario Outline: Setting: Slider - Display the configured content style
    Given the following config values are set as admin:
      | config             | value                              | plugin            |
      | slide1contentstyle | <style>                            | theme_boost_union |
      | slide1content      | This is a test content for slide 1 | theme_boost_union |
    When I log in as "teacher1"
    And I am on site homepage
    Then "//div[@id='themeboostunionslide1']//div[contains(@class, 'carousel-caption') and contains(@class, '<shouldclass>')]" "xpath_element" should exist
    And "//div[@id='themeboostunionslide1']//div[contains(@class, 'carousel-caption') and contains(@class, '<shouldnotclass1>')]" "xpath_element" should not exist
    And "//div[@id='themeboostunionslide1']//div[contains(@class, 'carousel-caption') and contains(@class, '<shouldnotclass2>')]" "xpath_element" should not exist

    # We do not want to burn too much CPU time by testing all available options. We just test the default value and a non-default values.
    Examples:
      | style      | shouldclass      | shouldnotclass1   | shouldnotclass2   |
      | light      | slide-light      | slide-lightshadow | slide-dark        |
      | darkshadow | slide-darkshadow | slide-lightshadow | slide-light       |
