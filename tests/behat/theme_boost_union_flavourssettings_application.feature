@theme @theme_boost_union @theme_boost_union_flavourssettings @theme_boost_union_flavourssettings_application
Feature: Configuring the theme_boost_union plugin on the "Flavours" page, applying to particular contexts
  In order to use the features
  As admin
  I need to be able to configure the theme Boost Union plugin

  Background:
    Given the following "users" exist:
      | username |
      | teacher1 |
      | teacher2 |
    And the following "categories" exist:
      | name   | category | idnumber |
      | Cat 1  | 0        | CAT1     |
      | Cat 1S | CAT1     | CAT1S    |
      | Cat 2  | 0        | CAT2     |
    And the following "courses" exist:
      | fullname  | shortname | category |
      | Course 1  | C1        | CAT1     |
      | Course 1S | C1S       | CAT1S    |
      | Course 2  | C2        | CAT2     |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | teacher2 | C1     | editingteacher |
      | teacher1 | C1S    | editingteacher |
      | teacher2 | C1S    | editingteacher |
      | teacher1 | C2     | editingteacher |
      | teacher2 | C2     | editingteacher |
    And the following "cohorts" exist:
      | name     | idnumber |
      | Cohort 1 | CH1      |
    And the following "cohort members" exist:
      | user     | cohort |
      | teacher1 | CH1    |

  Scenario: Flavours: Application - Flavour ID is added as body class attribute
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I set the field "Title" to "My shiny new flavour"
    And I click on "Save changes" "button"
    And I click on ".action-preview" "css_element" in the "My shiny new flavour" "table_row"
    # Unfortunately, we can't test for the particular flavour ID, we can just check that the class is there.
    Then the "class" attribute of "body" "css_element" should contain "flavour-"

  @javascript
  Scenario: Flavours: Application - Apply a flavour to a particular category without subcategories (and show this fact in the overview table and make sure that other categories are not affected)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Cat 1 flavour"
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
    And I should see "Course categories" in the "Cat 1 flavour" "table_row"
    And I log out
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I am on "Course 1S" course homepage
    And I should see "Course 1S" in the "#page-header .page-header-headings" "css_element"
    And I am on "Course 2" course homepage
    And I should see "Course 2" in the "#page-header .page-header-headings" "css_element"
    And I am on course index
    And I follow "Cat 1"
    And I should not see "Cat 1" in the "#page-header .page-header-headings" "css_element"
    And I select "Cat 1S" from the "Course categories" singleselect
    And I should see "Cat 1S" in the "#page-header .page-header-headings" "css_element"
    And I am on course index
    And I follow "Cat 2"
    And I should see "Cat 2" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario: Flavours: Application - Apply a flavour to a particular category with subcategories (and show this fact in the overview table and make sure that other categories are not affected)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Cat 1 flavour"
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
    And I set the field "Include subcategories" to "1"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Course categories" in the "Cat 1 flavour" "table_row"
    And I log out
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should not see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I am on "Course 1S" course homepage
    And I should not see "Course 1S" in the "#page-header .page-header-headings" "css_element"
    And I am on "Course 2" course homepage
    And I should see "Course 2" in the "#page-header .page-header-headings" "css_element"
    And I am on course index
    And I follow "Cat 1"
    And I should not see "Cat 1" in the "#page-header .page-header-headings" "css_element"
    And I select "Cat 1S" from the "Course categories" singleselect
    And I should not see "Cat 1S" in the "#page-header .page-header-headings" "css_element"
    And I am on course index
    And I follow "Cat 2"
    And I should see "Cat 2" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario: Flavours: Application - Do not apply a flavour to categories anymore
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Cat 1 flavour"
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
    And I should see "Course categories" in the "Cat 1 flavour" "table_row"
    And I click on ".action-edit" "css_element" in the "Cat 1 flavour" "table_row"
    And I expand all fieldsets
    And I select "No" from the "Apply to course categories" singleselect
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should not see "Course categories" in the "Cat 1 flavour" "table_row"
    And I log out
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I am on course index
    And I follow "Cat 1"
    And I should see "Cat 1" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario: Flavours: Application - Apply a flavour to a particular cohort (and show this fact in the overview table and make sure that other cohorts are not affected)
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Cohort 1 flavour"
    # We add a small CSS snippet to the flavour which hides the heading in the page header on the dashboard.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-my #page-header h1 { display: none; }
    """
    And I select "Yes" from the "Apply to cohorts" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocohorts_ids" "css_element"
    And I click on "Cohort 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Cohorts" in the "Cohort 1 flavour" "table_row"
    And I log out
    And I log in as "teacher1"
    And I follow "Dashboard"
    Then I should not see "Dashboard" in the "#page-header .page-header-headings" "css_element"
    And I log out
    And I log in as "teacher2"
    And I follow "Dashboard"
    Then I should see "Dashboard" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario: Flavours: Application - Do not apply a flavour to cohorts anymore
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Cohort 1 flavour"
    # We add a small CSS snippet to the flavour which hides the heading in the page header on the dashboard.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-my #page-header h1 { display: none; }
    """
    And I select "Yes" from the "Apply to cohorts" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocohorts_ids" "css_element"
    And I click on "Cohort 1" item in the autocomplete list
    And I press the escape key
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Cohorts" in the "Cohort 1 flavour" "table_row"
    And I click on ".action-edit" "css_element" in the "Cohort 1 flavour" "table_row"
    And I expand all fieldsets
    And I select "No" from the "Apply to cohorts" singleselect
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should not see "Cohorts" in the "Cohort 1 flavour" "table_row"
    And I log out
    And I log in as "teacher1"
    And I follow "Dashboard"
    Then I should see "Dashboard" in the "#page-header .page-header-headings" "css_element"

  @javascript
  Scenario: Flavours: Application - Stop after the first matching flavour
    When I log in as "admin"
    And I navigate to "Appearance > Boost Union > Flavours" in site administration
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Cat 1a flavour"
    # We add a small CSS snippet to the flavour which colorizes the heading in the page header on the dashboard.
    # This is just to make it easy to detect if this flavour is applied or not.
    And I set the field "Raw SCSS" to multiline:
    """
    .path-course-view #page-header h1, .path-course-index #page-header h1 { color: red; }
    """
    And I select "Yes" from the "Apply to course categories" singleselect
    And I click on ".form-autocomplete-downarrow" "css_element" in the "#fitem_id_applytocategories_ids" "css_element"
    And I click on "Cat 1" item in the autocomplete list
    And I press the escape key
    And I set the field "Include subcategories" to "0"
    And I click on "Save changes" "button"
    And I should see "Flavours" in the "#region-main h2" "css_element"
    And I should see "Course categories" in the "Cat 1a flavour" "table_row"
    And I click on "Create flavour" "button"
    And I should see "Create flavour" in the "#page-header h1" "css_element"
    And I expand all fieldsets
    And I set the field "Title" to "Cat 1b flavour"
    # We add a small CSS snippet to the flavour which hides the heading in the page header on the dashboard.
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
    And I should see "Course categories" in the "Cat 1b flavour" "table_row"
    And I log out
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should see "Course 1" in the "#page-header .page-header-headings" "css_element"
    And I am on course index
    And I follow "Cat 1"
    And I should see "Cat 1" in the "#page-header .page-header-headings" "css_element"
