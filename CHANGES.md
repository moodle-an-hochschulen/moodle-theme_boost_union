moodle-theme_boost_union
========================

Changes
-------

### Unreleased

* 2023-01-22 - Feature: Allow the admin to change the H5P content bank width, solves #201.

### v4.0-r11

* 2023-01-21 - Improvement: Add note about grandchild themes to the README file, solves #122.
* 2023-01-16 - Improvement: Remove Boost Union's own fallback CSS file for now, relates to #89.

### v4.0-r10

* 2023-01-15 - Improvement: Trim 'dark' and 'light' for login bg images, solves #192.
* 2023-01-15 - Improvement: Note to assure matching login image/login image text.
* 2023-01-15 - Feature: Login page layouts, solves #37.
* 2023-01-14 - Tests: Add Behat test steps for multilanguage static page content.
* 2023-01-13 - Improvement: Static pages headings and the links to the static pages now support multilang, solves #188.
* 2023-01-09 - Feature: Add advertisement tiles which can be displayed on site home, solves #161.
* 2023-01-08 - Tests: Avoid to burn too much CPU time by testing all available course image options.
* 2023-01-08 - Bugfix: Infobanners were sometimes incorrectly ordered if the same order was given to multiple banners, solves #181.
* 2023-01-06 - Improvement: Small language tweaks in self enrolment course banners
* 2023-01-05 - Bugfix: Unparsable JSON in templates/core/full_header.mustache

### v4.0-r9

* 2022-12-31 - Feature: Add settings and layouts to enable/disable showing course images or a fallback image in the header of the course page, solves #77.
* 2022-12-31 - Feature: Allow admins to define 'flavours' (i.e. special designs) which are applied to cohorts and / or course categories, solves #25.
* 2022-12-19 - Feature: Allow admins to hide primary navigation items, solves #65.
* 2022-12-14 - Feature: Built-in contact, help and maintenance pages, solves #150.
* 2022-11-28 - Updated Moodle Plugin CI to latest upstream recommendations

### v4.0-r8

* 2022-11-21 - Improvement: Restrict accepted file types for background images, solves #147.
* 2022-11-15 - Feature: Possibility to upload FontAwesome 6 Free to the theme, solves #59.
* 2022-11-08 - Feature: Allow admins to override the email templates within the theme, solves #60.

### v4.0-r7

* 2022-11-09 - Bugfix: Site administration was broken if customfiletypes were set in config.php, solves #133.

### v4.0-r6

* 2022-11-03 - Bugfix: Let favicon() always return a moodle_url object, solves #130.
* 2022-10-26 - Improvement: Restrict uploadable file types in custom fonts filearea, solves #120.

### v4.0-r5

* 2022-10-18 - Feature: Filearea for custom fonts, helps to solve #38.
* 2022-10-15 - Feature: Filearea for additional resources, solves #113.

### v4.0-r4

* 2022-10-15 - Feature: Random login background image with text, solves #36.
* 2022-10-14 - Bugfix: Make login page background and footnote work together, solves #107.
* 2022-10-13 - Bugfix: Footnote did not have a white background, solves #106.
* 2022-10-13 - Bugfix: Custom SCSS and background image SCSS was included twice, solves #103 #104.
* 2022-10-12 - Settings: screenshot image for theme chooser solves #33.

### v4.0-r3

* 2022-10-12 - Feature: Configurable activity navigation, solves #100.
* 2022-09-30 - Improvement: Localize month names for time-controlled info banners, solves #75.

### v4.0-r2

* 2022-09-27 - Improvement: Align the fallback CSS file with theme_boost.
* 2022-09-27 - Improvement: Align the theme's config.php even more with theme_boost. 
* 2022-09-26 - Bugfix: Adopt config.php, solves #67 #82.
* 2022-09-07 - Feature: Scrollspy, solves #19

### v4.0-r1

* 2022-07-18 - Feature: Bootstrap colors, solves #35
* 2022-07-15 - Feature: Information banners (base functionality), helps to resolve #4.
* 2022-07-15 - Settings: Divide theme settings into multiple pages, solves #52
* 2022-07-13 - Release: Add contributors to README.md, solves #44
* 2022-07-13 - Release: Add UPGRADE.md, solves #29
* 2022-07-08 - Feature: Customize activity icon background colors, solves #49
* 2022-07-07 - Feature: Show a warning banner if JavaScript is disabled, solves #46
* 2022-07-07 - Feature: Make the course content column width configurable, helps to resolve #18.
* 2022-07-06 - Feature: Built-in imprint, solves #32
* 2022-07-05 - Feature: Configurable favicon, solves #34
* 2022-07-05 - Feature: Allow non-admins to edit theme settings, solves #28
* 2022-07-05 - Feature: Back to top button, solves #7
* 2022-07-05 - Adopt changes in Boost core for MDL-74634
* 2022-06-21 - Add course related hints feature, solves #5
* 2022-04-30 - Added footnote functionality, helps to resolve #6.
* 2022-06-20 - Allow full Behat runs with Boost Campus suite, fixes #14.
* 2022-06-20 - Prepare settings.php page, solves #2.
* 2022-06-20 - Fill README.md, helps to resolve #3.
* 2022-04-29 - Adopt all changes which have happened in Boost core for the Moodle 4.0 release
* 2022-03-17 - Create boilerplate as Boost child theme without any additional features.
