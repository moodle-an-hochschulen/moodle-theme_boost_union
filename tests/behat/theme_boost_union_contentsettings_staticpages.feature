@theme @theme_boost_union @theme_boost_union_contentsettings @theme_boost_union_contentsettings_staticpages @theme_boost_union_footer @theme_boost_union_footnote
Feature: Configuring the theme_boost_union plugin for the "Static pages" tab on the "Content" page
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  # Note:
  # This feature file also covers scenarios which are common with
  # @theme_boost_union_accessibilitysettings_declaration and @theme_boost_union_accessibilitysettings_support
  # to avoid duplication of the same scenarios in multiple files.

  @javascript
  Scenario Outline: Setting: Enable static page - Do not enable the static page page
    Given the following config values are set as admin:
      | config       | value                     | plugin            |
      | enable<page> | no                        | theme_boost_union |
    # The footnote is just filled to make sure it is displayed at all and we can check for the .<page>link within it later.
      | footnote      | <p>My little footnote</p> | theme_boost_union |
    When I log in as "admin"
    Then ".theme_boost_union_footer_<page>link" "css_element" should not exist
    And ".theme_boost_union_footnote_<page>link" "css_element" should not exist
    And I am on <page> page
    Then I should see "The <pagedisabled> is disabled for this site. There is nothing to see here."
    And ".theme_boost_union_footnote_<page>link" "css_element" should not exist in the "#footnote" "css_element"
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    And ".theme_boost_union_footer_<page>link" "css_element" should not exist in the ".footer .popover-body" "css_element"

    Examples:
      | page                     | pagedisabled                                  |
      | aboutus                  | about us page                                 |
      | offers                   | offers page                                   |
      | imprint                  | imprint page                                  |
      | contact                  | contact page                                  |
      | help                     | help page                                     |
      | maintenance              | maintenance information page                  |
      | accessibilitydeclaration | declaration of accessibility information page |
      | accessibilitysupport     | accessibility support page                    |
      | page1                    | generic page 1                                |
      | page2                    | generic page 2                                |
      | page3                    | generic page 3                                |

  Scenario Outline: Setting: Enable static page - Enable and fill the static page with content
    Given the following config values are set as admin:
      | config        | value                                                                                                              | plugin            |
      | enable<page>  | yes                                                                                                                | theme_boost_union |
      | <page>content | <p><span lang="en" class="multilang">Lorem ipsum</span><span lang="de" class="multilang">Dolor sit amet</span></p> | theme_boost_union |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
    When I log in as "admin"
    And I am on <page> page
    Then I should see "Lorem ipsum" in the "div[role='main']" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Lorem ipsum</span>" in the "div[role='main']" "css_element"
    And I should not see "Lorem ipsumDolor sit amet" in the "div[role='main']" "css_element"
    And I should see "<pagetitle>" in the "title" "css_element"
    And I should see "<pagetitle>" in the "div[role='main'] h2" "css_element"

    Examples:
      | page                     | pagetitle                    |
      | aboutus                  | About us                     |
      | offers                   | Offers                       |
      | imprint                  | Imprint                      |
      | contact                  | Contact                      |
      | help                     | Help                         |
      | maintenance              | Maintenance                  |
      | accessibilitydeclaration | Declaration of accessibility |
      | accessibilitysupport     | Accessibility support        |
      | page1                    | Generic page 1               |
      | page2                    | Generic page 2               |
      | page3                    | Generic page 3               |

  @javascript
  Scenario Outline: Setting: Static page link position - Do not automatically add the static page link
    Given the following config values are set as admin:
      | config             | value                     | plugin            |
      | enable<page>       | yes                       | theme_boost_union |
      | <page>content      | <p>Lorem ipsum</p>        | theme_boost_union |
      | <page>linkposition | none                      | theme_boost_union |
    # The footnote is just filled to make sure it is displayed at all and we can check for the .<page>link within it later.
      | footnote            | <p>My little footnote</p> | theme_boost_union |
    When I log in as "admin"
    And I am on <page> page
    Then I should see "Lorem ipsum" in the "div[role='main']" "css_element"
    And ".theme_boost_union_footnote_<page>link" "css_element" should not exist in the "#footnote" "css_element"
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    And ".theme_boost_union_footer_<page>link" "css_element" should not exist in the ".footer .popover-body" "css_element"

    Examples:
      | page                     |
      | aboutus                  |
      | offers                   |
      | imprint                  |
      | contact                  |
      | help                     |
      | maintenance              |
      | accessibilitydeclaration |
      | accessibilitysupport     |
      | page1                    |
      | page2                    |
      | page3                    |

  @javascript
  Scenario Outline: Setting: Static page link position - Add the static page link to the footnote automatically (even if the footnote is empty otherwise)
    Given the following config values are set as admin:
      | config             | value              | plugin            |
      | enable<page>       | yes                | theme_boost_union |
      | <page>content      | <p>Lorem ipsum</p> | theme_boost_union |
      | <page>linkposition | footnote           | theme_boost_union |
      | footnote           |                    | theme_boost_union |
    When I log in as "admin"
    Then "#footnote" "css_element" should exist
    And ".theme_boost_union_footnote_<page>link" "css_element" should exist in the "#footnote" "css_element"
    And I should see "<pagetitle>" in the ".theme_boost_union_footnote_<page>link" "css_element"
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    And ".theme_boost_union_footer_<page>link" "css_element" should not exist in the ".footer .popover-body" "css_element"

    Examples:
      | page                     | pagetitle                    |
      | aboutus                  | About us                     |
      | offers                   | Offers                       |
      | imprint                  | Imprint                      |
      | contact                  | Contact                      |
      | help                     | Help                         |
      | maintenance              | Maintenance                  |
      | accessibilitydeclaration | Declaration of accessibility |
      | accessibilitysupport     | Accessibility support        |
      | page1                    | Generic page 1               |
      | page2                    | Generic page 2               |
      | page3                    | Generic page 3               |

  @javascript
  Scenario Outline: Setting: Static page link position - Add the static page link to the footnote automatically (if the footnote contains some content already)
    Given the following config values are set as admin:
      | config             | value                     | plugin            |
      | enable<page>       | yes                       | theme_boost_union |
      | <page>content      | <p>Lorem ipsum</p>        | theme_boost_union |
      | <page>linkposition | footnote                  | theme_boost_union |
      | footnote           | <p>My little footnote</p> | theme_boost_union |
    When I log in as "admin"
    Then "#footnote" "css_element" should exist
    And ".theme_boost_union_footnote_<page>link" "css_element" should exist in the "#footnote" "css_element"
    And I should see "<pagetitle>" in the ".theme_boost_union_footnote_<page>link" "css_element"
    And ".theme_boost_union_footnote_<page>link" "css_element" should appear after "My little footnote" "text"
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    And ".theme_boost_union_footer_<page>link" "css_element" should not exist in the ".footer .popover-body" "css_element"

    Examples:
      | page                     | pagetitle                    |
      | aboutus                  | About us                     |
      | offers                   | Offers                       |
      | imprint                  | Imprint                      |
      | contact                  | Contact                      |
      | help                     | Help                         |
      | maintenance              | Maintenance                  |
      | accessibilitydeclaration | Declaration of accessibility |
      | accessibilitysupport     | Accessibility support        |
      | page1                    | Generic page 1               |
      | page2                    | Generic page 2               |
      | page3                    | Generic page 3               |

  @javascript
  Scenario Outline: Setting: Static page link position - Add the static page link to the footer automatically
    Given the following config values are set as admin:
      | config             | value                     | plugin            |
      | enable<page>       | yes                       | theme_boost_union |
      | <page>content      | <p>Lorem ipsum</p>        | theme_boost_union |
      | <page>linkposition | footer                    | theme_boost_union |
    # The footnote is just filled to make sure it is displayed at all and we can check for the .<page>link within it later.
      | footnote            | <p>My little footnote</p> | theme_boost_union |
    When I log in as "admin"
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then ".theme_boost_union_footer_<page>link" "css_element" should exist in the ".footer .popover-body" "css_element"
    And I should see "<pagetitle>" in the ".theme_boost_union_footer_<page>link" "css_element"
    And ".theme_boost_union_footnote_<page>link" "css_element" should not exist in the "#footnote" "css_element"

    Examples:
      | page                     | pagetitle                    |
      | aboutus                  | About us                     |
      | offers                   | Offers                       |
      | imprint                  | Imprint                      |
      | contact                  | Contact                      |
      | help                     | Help                         |
      | maintenance              | Maintenance                  |
      | accessibilitydeclaration | Declaration of accessibility |
      | accessibilitysupport     | Accessibility support        |
      | page1                    | Generic page 1               |
      | page2                    | Generic page 2               |
      | page3                    | Generic page 3               |

  @javascript
  Scenario Outline: Setting: Static page link position - Add the static page link to the footnote and the footer automatically
    Given the following config values are set as admin:
      | config             | value                     | plugin            |
      | enable<page>       | yes                       | theme_boost_union |
      | <page>content      | <p>Lorem ipsum</p>        | theme_boost_union |
      | <page>linkposition | both                      | theme_boost_union |
    # The footnote is just filled to make sure it is displayed at all and we can check for the .<page>link within it later.
      | footnote            | <p>My little footnote</p> | theme_boost_union |
    When I log in as "admin"
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then ".theme_boost_union_footer_<page>link" "css_element" should exist in the ".footer .popover-body" "css_element"
    And I should see "<pagetitle>" in the ".theme_boost_union_footer_<page>link" "css_element"
    And ".theme_boost_union_footnote_<page>link" "css_element" should exist in the "#footnote" "css_element"

    Examples:
      | page                     | pagetitle                    |
      | aboutus                  | About us                     |
      | offers                   | Offers                       |
      | imprint                  | Imprint                      |
      | contact                  | Contact                      |
      | help                     | Help                         |
      | maintenance              | Maintenance                  |
      | accessibilitydeclaration | Declaration of accessibility |
      | accessibilitysupport     | Accessibility support        |
      | page1                    | Generic page 1               |
      | page2                    | Generic page 2               |
      | page3                    | Generic page 3               |

  @javascript
  Scenario Outline: Setting: Static page page title - Set an empty static page page title (and trigger the fallback string)
    Given the following config values are set as admin:
      | config             | value              | plugin            |
      | enable<page>       | yes                | theme_boost_union |
      | <page>content      | <p>Lorem ipsum</p> | theme_boost_union |
      | <page>pagetitle    |                    | theme_boost_union |
    When I log in as "admin"
    And I am on <page> page
    Then I should see "<pagetitle>" in the "div[role='main'] h2" "css_element"
    And "//title[contains(text(),'<pagetitle>')]" "xpath_element" should exist
    And the following config values are set as admin:
      | config             | value              | plugin            |
      | <page>linkposition | footnote           | theme_boost_union |
    And I reload the page
    Then I should see "<pagetitle>" in the "#footnote .theme_boost_union_footnote_<page>link" "css_element"
    And the following config values are set as admin:
      | config             | value              | plugin            |
      | <page>linkposition | footer             | theme_boost_union |
    And I reload the page
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I should see "<pagetitle>" in the ".footer .popover-body .theme_boost_union_footer_<page>link" "css_element"

    Examples:
      | page                     | pagetitle                    |
      | aboutus                  | About us                     |
      | offers                   | Offers                       |
      | imprint                  | Imprint                      |
      | contact                  | Contact                      |
      | help                     | Help                         |
      | maintenance              | Maintenance                  |
      | accessibilitydeclaration | Declaration of accessibility |
      | accessibilitysupport     | Accessibility support        |
      | page1                    | Generic page 1               |
      | page2                    | Generic page 2               |
      | page3                    | Generic page 3               |

  @javascript
  Scenario Outline: Setting: Static page page title - Set a custom static page page title
    Given the following config values are set as admin:
      | config          | value                                                                                             | plugin            |
      | enable<page>    | yes                                                                                               | theme_boost_union |
      | <page>content   | <p>Lorem ipsum</p>                                                                                | theme_boost_union |
      | <page>pagetitle | <span lang="en" class="multilang">Custom</span><span lang="de" class="multilang">Angepasst</span> | theme_boost_union |
    And the "multilang" filter is "on"
    And the "multilang" filter applies to "content and headings"
    When I log in as "admin"
    And I am on <page> page
    Then I should see "Custom" in the "div[role='main'] h2" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Custom</span>" in the "div[role='main'] h2" "css_element"
    And I should not see "CustomAngepasst" in the "div[role='main'] h2" "css_element"
    And "//title[contains(text(),'Custom')]" "xpath_element" should exist
    And the following config values are set as admin:
      | config             | value              | plugin            |
      | <page>linkposition | footnote           | theme_boost_union |
    And I reload the page
    Then I should see "Custom" in the "#footnote .theme_boost_union_footnote_<page>link" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Custom</span>" in the "#footnote .theme_boost_union_footnote_<page>link" "css_element"
    And I should not see "CustomAngepasst" in the "#footnote .theme_boost_union_footnote_<page>link" "css_element"
    And the following config values are set as admin:
      | config             | value              | plugin            |
      | <page>linkposition | footer             | theme_boost_union |
    And I reload the page
    And I click on ".btn-footer-popover" "css_element" in the "#page-footer" "css_element"
    Then I should see "Custom" in the ".footer .popover-body .theme_boost_union_footer_<page>link" "css_element"
    And I should not see "<span lang=\"en\" class=\"multilang\">Custom</span>" in the ".footer .popover-body .theme_boost_union_footer_<page>link" "css_element"
    And I should not see "CustomAngepasst" in the ".footer .popover-body .theme_boost_union_footer_<page>link" "css_element"

    Examples:
      | page                     |
      | aboutus                  |
      | offers                   |
      | imprint                  |
      | contact                  |
      | help                     |
      | maintenance              |
      | accessibilitydeclaration |
      | accessibilitysupport     |
      | page1                    |
      | page2                    |
      | page3                    |
