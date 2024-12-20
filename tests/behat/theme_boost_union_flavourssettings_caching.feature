@theme @theme_boost_union @theme_boost_union_flavourssettings @theme_boost_union_flavourssettings_caching
Feature: Configuring the theme_boost_union plugin on the "Flavours" page, caching the flavours which apply
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "categories" exist:
      | name   | category | idnumber |
      | Cat 1  | 0        | CAT1     |
      | Cat 2  | 0        | CAT2     |
    And the following "courses" exist:
      | fullname  | shortname | category |
      | Course 1  | C1        | CAT1     |

  @javascript
  Scenario: Flavours: Caching - After creating an additional flavour (and thereby changing the flavour which applies to a particular user), the flavour which applies now should take direct effect (i.e. the flavours cache is properly purged)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Non-effective flavour"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should not see "Course categories" in the "Non-effective flavour" "table_row"
    And I am on "Course 1" course homepage
    And I should see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Effective flavour"
    # We add a small CSS snippet to the flavour which hides the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: none; }
    """
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I set the field "Include subcategories" to "0"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Course categories" in the "Effective flavour" "table_row"
    And I am on "Course 1" course homepage
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario: Flavours: Caching - After editing a flavour (and thereby changing the flavour which applies to a particular user), the flavour which applies now should take direct effect (i.e. the flavours cache is properly purged)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Effective flavour"
    # We add a small CSS snippet to the flavour which hides the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: none; }
    """
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I set the field "Include subcategories" to "0"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Course categories" in the "Effective flavour" "table_row"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Non-effective flavour"
    # We add a small CSS snippet to the flavour which shows the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: block; }
    """
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I set the field "Include subcategories" to "0"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Course categories" in the "Non-effective flavour" "table_row"
    And I am on "Course 1" course homepage
    And I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on ".action-edit" "css_element" in the "Effective flavour" "table_row"
    And I click on "span.badge" "css_element" in the "#fitem_id_applytocategories_ids .form-autocomplete-selection" "css_element"
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 2" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Course categories" in the "Non-effective flavour" "table_row"
    And I am on "Course 1" course homepage
    Then I should see "Course 1" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario: Flavours: Caching - After deleting a flavour (and thereby changing the flavour which applies to a particular user), the flavour which applies now should take direct effect (i.e. the flavours cache is properly purged)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Effective flavour"
    # We add a small CSS snippet to the flavour which hides the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: none; }
    """
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I set the field "Include subcategories" to "0"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Course categories" in the "Effective flavour" "table_row"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Non-effective flavour"
    # We add a small CSS snippet to the flavour which shows the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: block; }
    """
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I set the field "Include subcategories" to "0"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Course categories" in the "Non-effective flavour" "table_row"
    And I am on "Course 1" course homepage
    And I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on ".action-delete" "css_element" in the "Effective flavour" "table_row"
    And I click on "Delete" "button"
    And I am on "Course 1" course homepage
    Then I should see "Course 1" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario Outline: Flavours: Caching - After sorting a flavour (and thereby changing the flavour which applies to a particular user), the flavour which applies now should take direct effect (i.e. the flavours cache is properly purged)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Effective flavour"
    # We add a small CSS snippet to the flavour which hides the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: none; }
    """
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I set the field "Include subcategories" to "0"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Non-effective flavour"
    # We add a small CSS snippet to the flavour which shows the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: block; }
    """
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I set the field "Include subcategories" to "0"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I am on "Course 1" course homepage
    And I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on ".sort-flavour-<action>-action" "css_element" in the "<flavourtosort> flavour" "table_row"
    And I am on "Course 1" course homepage
    Then I should see "Course 1" in the "#page-header .page-header-headings" "css_element"

    Examples:
      | action | flavourtosort |
      | up     | Non-effective |
      | down   | Effective     |

  @javascript
  Scenario: Flavours: Caching - After deleting a cohort (and thereby changing the flavour which applies to a particular user), the flavour which applies now should take direct effect (i.e. the flavours cache is properly purged)
    Given the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | CH1      |
      | Cohort 2 | CH2      |
    And the following "cohort members" exist:
      | user  | cohort |
      | admin | CH1    |
      | admin | CH2    |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Effective flavour"
    # We add a small CSS snippet to the flavour which hides the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: none; }
    """
    And I select "Yes" from the "Apply to cohorts" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocohorts_ids" "css_element"
    And I click on "Cohort 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Cohorts" in the "Effective flavour" "table_row"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Non-effective flavour"
    # We add a small CSS snippet to the flavour which shows the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: block; }
    """
    And I select "Yes" from the "Apply to cohorts" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocohorts_ids" "css_element"
    And I click on "Cohort 2" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Cohorts" in the "Non-effective flavour" "table_row"
    And I am on "Course 1" course homepage
    And I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I navigate to "Users > Accounts > Cohorts" in site administration
    And I open the action menu in "Cohort 1" "table_row"
    And I choose "Delete" in the open action menu
    And I click on "Delete" "button" in the ".modal-dialog" "css_element"
    And I am on "Course 1" course homepage
    Then I should see "Course 1" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario: Flavours: Caching - After adding a user to a cohort (and thereby changing the flavour which applies to a particular user), the flavour which applies now should take direct effect (i.e. the flavours cache is properly purged)
    Given the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | CH1      |
      | Cohort 2 | CH2      |
    And the following "cohort members" exist:
      | user  | cohort |
      | admin | CH2    |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Effective flavour"
    # We add a small CSS snippet to the flavour which hides the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: none; }
    """
    And I select "Yes" from the "Apply to cohorts" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocohorts_ids" "css_element"
    And I click on "Cohort 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Cohorts" in the "Effective flavour" "table_row"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Non-effective flavour"
    # We add a small CSS snippet to the flavour which shows the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: block; }
    """
    And I select "Yes" from the "Apply to cohorts" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocohorts_ids" "css_element"
    And I click on "Cohort 2" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Cohorts" in the "Non-effective flavour" "table_row"
    And I am on "Course 1" course homepage
    And I should see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I navigate to "Users > Accounts > Cohorts" in site administration
    And I open the action menu in "Cohort 1" "table_row"
    And I choose "Assign" in the open action menu
    And I set the field "Potential users" to "Admin User (moodle@example.com)"
    And I press "Add"
    And I press "Back to cohorts"
    And I am on "Course 1" course homepage
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario: Flavours: Caching - After removing a user from a cohort (and thereby changing the flavour which applies to a particular user), the flavour which applies now should take direct effect (i.e. the flavours cache is properly purged)
    Given the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | CH1      |
      | Cohort 2 | CH2      |
    And the following "cohort members" exist:
      | user  | cohort |
      | admin | CH1    |
      | admin | CH2    |
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Effective flavour"
    # We add a small CSS snippet to the flavour which hides the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: none; }
    """
    And I select "Yes" from the "Apply to cohorts" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocohorts_ids" "css_element"
    And I click on "Cohort 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Cohorts" in the "Effective flavour" "table_row"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Non-effective flavour"
    # We add a small CSS snippet to the flavour which shows the heading in the page header on course pages and category overview pages.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { display: block; }
    """
    And I select "Yes" from the "Apply to cohorts" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocohorts_ids" "css_element"
    And I click on "Cohort 2" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Cohorts" in the "Non-effective flavour" "table_row"
    And I am on "Course 1" course homepage
    And I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I navigate to "Users > Accounts > Cohorts" in site administration
    And I open the action menu in "Cohort 1" "table_row"
    And I choose "Assign" in the open action menu
    And I set the field "Current users" to "Admin User (moodle@example.com)"
    And I press "Remove"
    And I press "Back to cohorts"
    And I am on "Course 1" course homepage
    Then I should see "Course 1" in the "#page-header .page-header-headings" "css_element"
