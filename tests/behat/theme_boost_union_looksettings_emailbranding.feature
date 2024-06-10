@theme @theme_boost_union @theme_boost_union_looksettings @theme_boost_union_looksettings_emailbranding
Feature: Configuring the theme_boost_union plugin for the "E-Mail branding" tab on the "Look" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

#  Unfortunately, this can't be tested with Behat yet
#  See https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/issues/140 for details
#  @javascript
#  Scenario: Setting: HTML E-Mail branding
#    Given the following "language customisations" exist:
#      | component         | stringid                | value          |
#      | theme_boost_union | templateemailhtmlprefix | My HTML prefix |
#      | theme_boost_union | templateemailhtmlsuffix | My HTML suffix |
#    When I log in as "admin"
#    And Behat debugging is disabled
#    And I navigate to "Appearance > Boost Union > Look" in site administration
#    And I click on "E-Mail branding" "link" in the "#adminsettings .nav-tabs" "css_element"
#    Then I should not see "Up to now, the HTML E-Mails haven't been customized within this feature"
#    And I should see "This is a preview of a HTML E-Mail"
#    And "My HTML prefix" "text" should appear after "HTML E-Mail preview" "text"
#    And "My HTML suffix" "text" should appear after "HTML E-Mail preview" "text"
#    And "Plaintext E-Mail preview" "text" should appear after "My HTML prefix" "text"
#    And "Plaintext E-Mail preview" "text" should appear after "My HTML suffix" "text"
#    And "My HTML prefix" "text" should appear before "Lorem ipsum dolor sit amet" "text"
#    And "My HTML suffix" "text" should appear after "Lorem ipsum dolor sit amet" "text"
#    And Behat debugging is enabled

  @javascript
  Scenario: Setting: HTML E-Mail branding (countercheck)
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "E-Mail branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then I should see "Up to now, the HTML E-Mails haven't been customized within this feature"
    And I should not see "This is a preview of a HTML E-Mail"
    And Behat debugging is enabled

#  Unfortunately, this can't be tested with Behat yet
#  See https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/issues/140 for details
#  @javascript
#  Scenario: Setting: Plaintext E-Mail branding
#    Given the following "language customisations" exist:
#      | component         | stringid                | value               |
#      | theme_boost_union | templateemailtextprefix | My plaintext prefix |
#      | theme_boost_union | templateemailtextsuffix | My plaintext suffix |
#    When I log in as "admin"
#    And Behat debugging is disabled
#    And I navigate to "Appearance > Boost Union > Look" in site administration
#    And I click on "E-Mail branding" "link" in the "#adminsettings .nav-tabs" "css_element"
#    Then I should not see "Up to now, the plaintext E-Mails haven't been customized within this feature"
#    And I should see "This is a preview of a plaintext E-Mail"
#    And "My plaintext prefix" "text" should appear after "Plaintext E-Mail preview" "text"
#    And "My plaintext suffix" "text" should appear after "Plaintext E-Mail preview" "text"
#    And "HTML E-Mail preview" "text" should appear before "My HTML prefix" "text"
#    And "HTML E-Mail preview" "text" should appear before "My HTML suffix" "text"
#    And "My plaintext prefix" "text" should appear before "Lorem ipsum dolor sit amet" "text"
#    And "My plaintext suffix" "text" should appear after "Lorem ipsum dolor sit amet" "text"
#    And Behat debugging is enabled

  @javascript
  Scenario: Setting: Plaintext E-Mail branding (countercheck)
    When I log in as "admin"
    And Behat debugging is disabled
    And I navigate to "Appearance > Boost Union > Look" in site administration
    And I click on "E-Mail branding" "link" in the "#adminsettings .nav-tabs" "css_element"
    Then I should see "Up to now, the plaintext E-Mails haven't been customized within this feature"
    And I should not see "This is a preview of a plaintext E-Mail"
    And Behat debugging is enabled
