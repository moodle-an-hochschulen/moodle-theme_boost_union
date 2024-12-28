@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_scss
Feature: Configuring the theme_boost_union plugin for the "SCSS" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |

  @javascript
  Scenario: Setting: Raw (initial) SCSS - Add custom SCSS to the theme
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "SCSS" "link" in the "#adminsettings .nav-tabs" "css_element"
    # We add a SCSS variable and a small SCSS snippet to the page which hides the heading in the page header.
    # This is just to make it easy to detect the effect of this custom SCSS code and to verify that SCSS is compiled correctly.
    And I set the field "Raw initial SCSS" to multiline:
    """
    $myvariable: none;
    """
    And I set the field "Raw SCSS" to multiline:
    """
    #page-header h1 { display: $myvariable; }
    """
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario Outline: Setting: External SCSS - Add external SCSS download URL to the theme
    Given the following config values are set as admin:
      | config        | value | plugin            |
      | extscsssource | 1     | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "SCSS" "link" in the "#adminsettings .nav-tabs" "css_element"
    # We add a small CSS snippet to the page which hides the heading in the page header.
    # This is just to make it easy to detect the effect of this custom SCSS code.
    And I set the following fields to these values:
      | <urlfield> | <url> |
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"

    Examples:
      | urlfield                        | url                                                                                                                 |
      | External Pre SCSS download URL  | https://raw.githubusercontent.com/moodle-an-hochschulen/moodle-theme_boost_union/main/tests/fixtures/extscss.scss |
      | External Post SCSS download URL | https://raw.githubusercontent.com/moodle-an-hochschulen/moodle-theme_boost_union/main/tests/fixtures/extscss.scss |

  @javascript
  Scenario Outline: Setting: External SCSS - Add external SCSS from Github API to the theme
    Given the following config values are set as admin:
      | config        | value | plugin            |
      | extscsssource | 2     | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "SCSS" "link" in the "#adminsettings .nav-tabs" "css_element"
    # We add a small CSS snippet to the page which hides the heading in the page header.
    # This is just to make it easy to detect the effect of this custom SCSS code.
    And I set the following fields to these values:
      | External SCSS Github API token      | github_pat_<githubkey1><githubkey2><githubkey3> |
      | External SCSS Github API user       | moodle-an-hochschulen                           |
      | External SCSS Github API repository | moodle-theme_boost_union-extscsstest            |
      | <pathfield>                         | <filepath>                                      |
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"

    Examples:
      # The Github key which is placed here is is a fine-grained access token which was especially created for this Behat scenario and which will expire on 2025-05-06 due to Github's token lifetime policy.
      # It is sliced into three pieces to avoid that Github's code scanning engine will find and invalidate it.
      | pathfield                           | filepath      | githubkey1                   | githubkey3                   | githubkey2                 |
      | External Pre SCSS Github file path  | /extscss.scss | 11AAIKUFQ0r5mGRLvI53V1_spQkU | 7kSEWBd25CNtUJE7UBA6dPdya3zM | C5O4Xo453LqQgKoXVAsmuKuC1q |
      | External Post SCSS Github file path | /extscss.scss | 11AAIKUFQ0r5mGRLvI53V1_spQkU | 7kSEWBd25CNtUJE7UBA6dPdya3zM | C5O4Xo453LqQgKoXVAsmuKuC1q |

  @javascript
  Scenario Outline: Setting: External SCSS - Add a broken SCSS download URL to the theme
    Given the following config values are set as admin:
      | config        | value | plugin            |
      | extscsssource | 1     | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "SCSS" "link" in the "#adminsettings .nav-tabs" "css_element"
    # We first add a valid CSS snippet to the page which is just there to detect later that SCSS has been compiled correctly.
    And I set the field "Raw SCSS" to multiline:
    """
    #page-header h1 { display: none; }
    """
    And I press "Save changes"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "SCSS" "link" in the "#adminsettings .nav-tabs" "css_element"
    # And then we add a broken SCSS URL to the theme.
    And I set the field "External Post SCSS download URL" to "<url>"
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage
    # Regardless of the fact that the broken URL was configured as external source, the SCSS
    # should be compiled correctly.
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"

    Examples:
      | url                                                                                                                 |
      | https://raw.githubusercontent.com/moodle-an-hochschulen/moodle-theme_boost_union/broken/tests/fixtures/extscss.scss |

  @javascript
  Scenario Outline: Setting: External SCSS - Add an invalid external SCSS code to the theme and validate it
    Given the following config values are set as admin:
      | config            | value | plugin            |
      | extscsssource     | 1     | theme_boost_union |
      | extscssvalidation | yes   | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "SCSS" "link" in the "#adminsettings .nav-tabs" "css_element"
    # We first add a valid CSS snippet to the page which is just there to detect later that SCSS has been compiled correctly.
    And I set the field "Raw SCSS" to multiline:
    """
    #page-header h1 { display: none; }
    """
    And I press "Save changes"
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "SCSS" "link" in the "#adminsettings .nav-tabs" "css_element"
    And I set the field "External Post SCSS download URL" to "<url>"
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage
    # Regardless of the fact that invalid SCSS code has been fetched from the external source, the SCSS
    # should be compiled correctly.
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"

    Examples:
      | url                                                                                                                         |
      | https://raw.githubusercontent.com/moodle-an-hochschulen/moodle-theme_boost_union/broken/tests/fixtures/extscss.scss         |
      | https://raw.githubusercontent.com/moodle-an-hochschulen/moodle-theme_boost_union/main/tests/fixtures/extscss-invalid.scss |

  @javascript
  Scenario Outline: Setting: External SCSS - Add an external SCSS code with Bootstrap variables to the theme and validate it
    Given the following config values are set as admin:
      | config            | value      | plugin            |
      | extscsssource     | 1          | theme_boost_union |
      | extscssvalidation | <validate> | theme_boost_union |
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "SCSS" "link" in the "#adminsettings .nav-tabs" "css_element"
    # And then we add external SCSS code with Bootstrap variables to the theme.
    And I set the field "External Post SCSS download URL" to "<url>"
    And I press "Save changes"
    And Behat debugging is enabled
    And I am on "Course 1" course homepage
    # Regardless of the fact that broken / invalid SCSS code has been fetched from the external source, the SCSS
    # should be compiled correctly.
    Then I <shouldornot> see "Course 1" in the "#page-header .page-header-headings" "css_element"

    Examples:
      | validate | shouldornot | url                                                                                                                                |
      | yes      | should      | https://raw.githubusercontent.com/moodle-an-hochschulen/moodle-theme_boost_union/main/tests/fixtures/extscss-with-variables.scss |
      | no       | should not  | https://raw.githubusercontent.com/moodle-an-hochschulen/moodle-theme_boost_union/main/tests/fixtures/extscss-with-variables.scss |
