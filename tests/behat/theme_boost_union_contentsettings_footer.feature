@theme @theme_boost_union @theme_boost_union_contentsettings @theme_boost_union_contentsettings_footer @theme_boost_union_footer @theme_boost_union_footnote
Feature: Configuring the theme_boost_union plugin for the "Footer" tab on the "Content" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | student1 |
      | teacher1 |
    And the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  Scenario: Setting: Footnote - Use the footnote setting to show a string in the page footer on the Dashboard, on the course pages and on the login page
    Given the following config values are set as admin:
      | config   | value                                                                                              | plugin            |
      | footnote | <span lang="en" class="multilang">Footnote</span><span lang="de" class="multilang">Fussnote</span> | theme_boost_union |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
    When I log in as "admin"
    And I follow "Dashboard"
    Then "#footnote" "css_element" should exist
    And ".text_to_html" "css_element" should not exist in the "#footnote" "css_element"
    And I should see "Footnote" in the "#footnote" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Footnote</span>" in the "#footnote" "css_element"
    And I should not see "FootnoteFussnote" in the "#footnote" "css_element"
    And I log out
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then "#footnote" "css_element" should exist
    And ".text_to_html" "css_element" should not exist in the "#footnote" "css_element"
    And I should see "Footnote" in the "#footnote" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Footnote</span>" in the "#footnote" "css_element"
    And I should not see "FootnoteFussnote" in the "#footnote" "css_element"
    And I log out
    And I follow "Log in"
    Then "#footnote" "css_element" should exist
    And ".text_to_html" "css_element" should not exist in the "#footnote" "css_element"
    And I should see "Footnote" in the "#footnote" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Footnote</span>" in the "#footnote" "css_element"
    And I should not see "FootnoteFussnote" in the "#footnote" "css_element"

  @javascript
  Scenario Outline: Setting: Footer - Enable and disable the footer button
    Given the following config values are set as admin:
      | config             | value   | plugin            |
      | enablefooterbutton | <value> | theme_boost_union |
    When I log in as "teacher1"
    And I follow "Dashboard"
    And I change viewport size to "large"
    Then ".btn-footer-popover" "css_element" <desktopshouldornot> <visibleorexist>
    And I change viewport size to "480x800"
    Then ".btn-footer-popover" "css_element" <mobileshouldornot> <visibleorexist>
    And I am on "Course 1" course homepage
    And I change viewport size to "large"
    Then ".btn-footer-popover" "css_element" <desktopshouldornot> <visibleorexist>
    And I change viewport size to "480x800"
    Then ".btn-footer-popover" "css_element" <mobileshouldornot> <visibleorexist>
    And I log out
    And I follow "Log in"
    And I change viewport size to "large"
    Then ".btn-footer-popover" "css_element" <desktopshouldornot> <visibleorexist>
    And I change viewport size to "480x800"
    Then ".btn-footer-popover" "css_element" <mobileshouldornot> <visibleorexist>

    Examples:
      | value                     | desktopshouldornot | mobileshouldornot | visibleorexist |
      | enablefooterbuttonall     | should             | should            | be visible     |
      | enablefooterbuttondesktop | should             | should not        | be visible     |
      | enablefooterbuttonmobile  | should not         | should            | be visible     |
      | enablefooterbuttonnone    | should not         | should not        | exist          |

  @javascript
  Scenario Outline: Setting: Footer - Suppress 'Chat to course participants' link
    Given the following config values are set as admin:
      | enablecommunicationsubsystem | 1 |
    And the following config values are set as admin:
      | config             | value   | plugin            |
      | footersuppresschat | <value> | theme_boost_union |
    And all caches are purged
    And I log in as "admin"
    And I am on "Course 1" course homepage
    And I navigate to "Communication" in current page administration
    And I select "Custom link" from the "Provider" singleselect
    And I set the following fields to these values:
      | Room name       | Test URL                                                                                   |
      | Custom link URL | #wwwroot#/communication/provider/customlink/tests/behat/fixtures/custom_link_test_page.php |
    And I press "Save changes"
    When I am on "Course 1" course homepage
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I <shouldornot> see "Chat to course participants" in the ".popover-body" "css_element"

    Examples:
      | value | shouldornot |
      | no    | should      |
      | yes   | should not  |

  @javascript
  Scenario Outline: Setting: Footer - Suppress 'Documentation for this page' link
    Given the following config values are set as admin:
      | docroot | https://docs.moodle.org |
    And the following config values are set as admin:
      | config             | value   | plugin            |
      | footersuppresshelp | <value> | theme_boost_union |
    And all caches are purged
    And I log in as "admin"
    And I am on "Course 1" course homepage
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I <shouldornot> see "Documentation for this page" in the ".popover-body" "css_element"

    Examples:
      | value | shouldornot |
      | no    | should      |
      | yes   | should not  |

  @javascript
  Scenario Outline: Setting: Footer - Suppress 'Services and support' link
    Given the following config values are set as admin:
      | servicespage | https://mymoodlesupport.com |
    And the following config values are set as admin:
      | config                 | value   | plugin            |
      | footersuppressservices | <value> | theme_boost_union |
    And all caches are purged
    And I log in as "admin"
    When I am on site homepage
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I <shouldornot> see "Services and support" in the ".popover-body" "css_element"

    Examples:
      | value | shouldornot |
      | no    | should      |
      | yes   | should not  |

  @javascript
  Scenario Outline: Setting: Footer - Suppress 'Contact site support' link
    Given the following config values are set as admin:
      | supportemail        | admin@mymoodlesupport.com |
      | supportavailability | 2                         |
    And the following config values are set as admin:
      | config                | value   | plugin            |
      | footersuppresscontact | <value> | theme_boost_union |
    And all caches are purged
    And I log in as "admin"
    When I am on site homepage
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I <shouldornot> see "Contact site support" in the ".popover-body" "css_element"

    Examples:
      | value | shouldornot |
      | no    | should      |
      | yes   | should not  |

  @javascript
  Scenario Outline: Setting: Footer - Suppress Login info
    Given the following config values are set as admin:
      | config                  | value   | plugin            |
      | footersuppresslogininfo | <value> | theme_boost_union |
    And all caches are purged
    And I log in as "admin"
    When I am on site homepage
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I <shouldornot> see "You are logged in as" in the ".popover-body" "css_element"

    Examples:
      | value | shouldornot |
      | no    | should      |
      | yes   | should not  |

  @javascript
  Scenario Outline: Setting: Footer - Suppress 'Reset user tour on this page' link
    # Note: The steps to create and use the tour were copied from the @tool_usertours Behat feature.
    Given the following config values are set as admin:
      | config                 | value   | plugin            |
      | footersuppressusertour | <value> | theme_boost_union |
    And all caches are purged
    And I log in as "admin"
    And I add a new user tour with:
      | Name                | First tour |
      | Description         | My first tour |
      | Apply to URL match  | /my/% |
      | Tour is enabled     | 1 |
    And I add steps to the "First tour" tour:
      | targettype                | Title   | id_content                                                                                                                     | Content type   |
      | Display in middle of page | Welcome | Welcome to your personal learning space. We'd like to give you a quick tour to show you some of the areas you may find helpful | Manual         |
    And I add steps to the "First tour" tour:
      | targettype | targetvalue_block | Title    | id_content                                                                    | Content type   |
      | Block      | Timeline          | Timeline | This is the Timeline. All of your upcoming activities can be found here       | Manual         |
      | Block      | Calendar          | Calendar | This is the Calendar. All of your assignments and due dates can be found here | Manual         |
    And I add steps to the "First tour" tour:
      | targettype | targetvalue_selector | Title     | id_content                                                                                         | Content type   |
      | Selector   | .usermenu            | User menu | This is your personal user menu. You'll find your personal preferences and your user profile here. | Manual         |
    And I am on homepage
    And I should see "Welcome to your personal learning space. We'd like to give you a quick tour to show you some of the areas you may find helpful"
    And I click on "Next" "button" in the "[data-role='flexitour-step']" "css_element"
    And I should see "This is the Timeline. All of your upcoming activities can be found here"
    And I should not see "This is the Calendar. All of your assignments and due dates can be found here"
    And I click on "Next" "button" in the "[data-role='flexitour-step']" "css_element"
    And I should see "This is the Calendar. All of your assignments and due dates can be found here"
    And I should not see "This area shows you what's happening in some of your courses"
    And I click on "Skip tour" "button" in the "[data-role='flexitour-step']" "css_element"
    And I should not see "This area shows you what's happening in some of your courses"
    And I am on homepage
    And I should not see "Welcome to your personal learning space. We'd like to give you a quick tour to show you some of the areas you may find helpful"
    When I am on homepage
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I <shouldornot> see "Reset user tour on this page" in the ".popover-body" "css_element"

    Examples:
      | value | shouldornot |
      | no    | should      |
      | yes   | should not  |

  # Unfortunately, this can't be tested with Behat on Moodle 4.3 anymore
  # Scenario Outline: Setting: Footer - Suppress theme switcher links

  @javascript
  Scenario Outline: Setting: Footer - Suppress 'Powered by Moodle' link
    Given the following config values are set as admin:
      | config                | value   | plugin            |
      | footersuppresspowered | <value> | theme_boost_union |
    And all caches are purged
    And I log in as "admin"
    When I am on site homepage
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I <shouldornot> see "Powered by Moodle" in the ".popover-body" "css_element"

    Examples:
      | value | shouldornot |
      | no    | should      |
      | yes   | should not  |

  @javascript
  Scenario Outline: Setting: Footer - Suppress footer output by plugin 'tool_dataprivacy'
    Given the following config values are set as admin:
      | config                   | value | plugin           |
      | showdataretentionsummary | 1     | tool_dataprivacy |
    And the following config values are set as admin:
      | config                                        | value   | plugin            |
      | footersuppressstandardfooter_tool_dataprivacy | <value> | theme_boost_union |
    And all caches are purged
    And I log in as "admin"
    When I am on site homepage
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I <shouldornot> see "Data retention summary" in the ".popover-body" "css_element"

    Examples:
      | value | shouldornot |
      | no    | should      |
      | yes   | should not  |

  @javascript
  Scenario Outline: Setting: Footer - Suppress footer output by core component 'core_userfeedback'
    Given the following config values are set as admin:
      | enableuserfeedback | 1 |
    And the following config values are set as admin:
      | config                                         | value   | plugin            |
      | footersuppressstandardfooter_core_userfeedback | <value> | theme_boost_union |
    And all caches are purged
    And I log in as "admin"
    When I am on site homepage
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I <shouldornot> see "Give feedback about this software" in the ".popover-body" "css_element"

    Examples:
      | value | shouldornot |
      | no    | should      |
      | yes   | should not  |

  @javascript
  Scenario Outline: Setting: Footer - Suppress footer output by plugin 'tool_mobile'
    Given the following config values are set as admin:
      | config                 | value |
      | enablemobilewebservice | 1     |
    And the following config values are set as admin:
      | config                                   | value   | plugin            |
      | footersuppressstandardfooter_tool_mobile | <value> | theme_boost_union |
    And all caches are purged
    And I log in as "admin"
    When I am on site homepage
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I <shouldornot> see "Get the mobile app" in the ".popover-body" "css_element"

    Examples:
      | value | shouldornot |
      | no    | should      |
      | yes   | should not  |

  @javascript
  Scenario Outline: Setting: Footer - Suppress footer output by plugin 'tool_policy'
    Given the following config values are set as admin:
      | sitepolicyhandler | tool_policy |
    And the following policies exist:
      | Policy | Name             | Revision | Content   | Summary    | Status   |
      | P1     | This site policy |          | full text | short text | active   |
    And the following config values are set as admin:
      | config                                   | value   | plugin            |
      | footersuppressstandardfooter_tool_policy | <value> | theme_boost_union |
    And all caches are purged
    And I log in as "admin"
    When I am on site homepage
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I <shouldornot> see "Policies" in the ".popover-body" "css_element"

    Examples:
      | value | shouldornot |
      | no    | should      |
      | yes   | should not  |

  @javascript
  Scenario Outline: Setting: Footer - Suppress icons in front of the footer links
    Given the following config values are set as admin:
      | config                   | value              | plugin            |
      | footersuppressicons      | <value>            | theme_boost_union |
      | enableaboutus            | yes                | theme_boost_union |
      | aboutuscontent           | <p>Lorem ipsum</p> | theme_boost_union |
      | aboutuslinkposition      | footer             | theme_boost_union |
      | enableoffers             | yes                | theme_boost_union |
      | offerscontent            | <p>Lorem ipsum</p> | theme_boost_union |
      | offerslinkposition       | footer             | theme_boost_union |
      | enableimprint            | yes                | theme_boost_union |
      | imprintcontent           | <p>Lorem ipsum</p> | theme_boost_union |
      | imprintlinkposition      | footer             | theme_boost_union |
      | enablecontact            | yes                | theme_boost_union |
      | contactcontent           | <p>Lorem ipsum</p> | theme_boost_union |
      | contactlinkposition      | footer             | theme_boost_union |
      | enablehelp               | yes                | theme_boost_union |
      | helpcontent              | <p>Lorem ipsum</p> | theme_boost_union |
      | helplinkposition         | footer             | theme_boost_union |
      | enablemaintainance       | yes                | theme_boost_union |
      | maintainancecontent      | <p>Lorem ipsum</p> | theme_boost_union |
      | maintainancelinkposition | footer             | theme_boost_union |
      | enablepage1              | yes                | theme_boost_union |
      | page1content             | <p>Lorem ipsum</p> | theme_boost_union |
      | page1linkposition        | footer             | theme_boost_union |
      | enablepage2              | yes                | theme_boost_union |
      | page2content             | <p>Lorem ipsum</p> | theme_boost_union |
      | page2linkposition        | footer             | theme_boost_union |
      | enablepage3              | yes                | theme_boost_union |
      | page3content             | <p>Lorem ipsum</p> | theme_boost_union |
      | page3linkposition        | footer             | theme_boost_union |
    When I log in as "admin"
    And I am on "Course 1" course homepage
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then ".footer-support-link a i.icon.fa-book" "css_element" <shouldornot> exist in the ".footer .popover-body" "css_element"
    And ".footer-support-link a i.icon.fa-life-ring" "css_element" <shouldornot> exist in the ".footer .popover-body" "css_element"
    And ".footer-support-link a i.icon.fa-envelope" "css_element" <shouldornot> exist in the ".footer .popover-body" "css_element"
    And ".footer-support-link a i.icon.fa-circle-info" "css_element" <shouldornot> exist in the ".footer .popover-body" "css_element"
    And ".footer-support-link a i.icon.fa-briefcase" "css_element" <shouldornot> exist in the ".footer .popover-body" "css_element"
    And ".footer-support-link a i.icon.fa-building-o" "css_element" <shouldornot> exist in the ".footer .popover-body" "css_element"
    And ".footer-support-link a i.icon.fa-address-card" "css_element" <shouldornot> exist in the ".footer .popover-body" "css_element"
    And ".footer-support-link a i.icon.fa-question-circle-o" "css_element" <shouldornot> exist in the ".footer .popover-body" "css_element"
    And ".footer-support-link a i.icon.fa-arrow-circle-o-right" "css_element" <shouldornot> exist in the ".footer .popover-body" "css_element"

    Examples:
      | value | shouldornot |
      | no    | should      |
      | yes   | should not  |
