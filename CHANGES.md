moodle-theme_boost_union
========================

Changes
-------

### Unreleased

* 2023-09-09 - Release: Adopt theme_boost changes from MDL-78656 to moremenu_children.mustache template, align menu item icon and tooltip handling at the same time, solves #385.
* 2023-08-30 - Improvement: Align actions column on flavours and smart menus settings pages, solves #381.
* 2023-08-19 - Improvement: Fix more mustache linter warnings, solves #360.
* 2023-08-02 - Improvement: Add 'aboutus', 'offers', 'page1', 'page2' and 'page3' static pages, solves #351.
* 2023-08-19 - Bugfix: Fix unparsable example JSON in Mustache template, solves #348.
* 2023-08-18 - Improvement: Add CSS and Scripts for local_och5p local_och5pcore to the theme renderer.
* 2023-08-13 - Improvement: Make the fonts in the dark navbar variants always fully white (not only when hovered) to improve the contrast.
* 2023-08-13 - Feature: Smart menus, solves #137

### v4.1-r9

* 2023-07-24 - Improvement: Only add load OffCanvas module when offcanvas region is enabled, solves #343.
* 2023-07-11 - Improvement: Place mustache templates which are overridden from theme_boost into subfolder, solves #337.
* 2023-07-10 - Bugfix: Omit warning when no CSS cached folder present, solves #330.
* 2023-07-10 - Bugfix: Overwriting module purposes did not affect all places where the activity icon is displayed, solves #318.
* 2023-07-07 - Bugfix: Changing module purpose to "other" made icon background white instead of grey, solves #333.
* 2023-07-07 - Bugfix: Changing module purpose to "other" made icon invisible, solves #319.
* 2023-06-26 - Bugfix: If the settings blockregionoutsiderightwidth or blockregionoutsiderightwidth were not set for any reason, the SCSS was not compiled, solves #325.

### v4.1-r8

* 2023-06-18 - Improvement: Split "Branding" tab into "Site branding" and "Activity branding", solves #315.
* 2023-06-17 - Bugfix: Hide nodes in primary navigation had no effect on site administration menu item, solves #312.
* 2023-06-14 - Feature: Add admin option to configure block size in footer, solves first part of #253.
* 2023-06-03 - Bugfix: Help fixing PHPUnit runs with Boost Union, solves #305.
* 2023-06-14 - Bugfix: Fix footer and footnote placement, solves #269.
* 2023-06-13 - Feature: Add admin option to mark external links, solves #307.
* 2023-06-13 - Feature: Allow the admin to overwrite the modules purpose, solves #288.
* 2023-05-17 - Improvement: Improve SCSS for dark navbar and primary color navbar, solves #273.
* 2023-05-11 - Feature: Allow the admin to upload custom icons for activities and resources, solves #175.

### v4.1-r7

* 2023-04-20 - Feature: Course category breadcrumbs in course header, solves #284.
* 2023-04-02 - Improvement: Add SCSS code to improve the block regions presentation in our companion plugin Dash Pro, solves #291.
* 2023-04-17 - Bugfix: Setting activityiconcolorcommunication was not processed anymore, solves #279.
* 2023-04-13 - Bugfix: When there is no edit switch, as on the assignment grading page, scrollfix should not kick in, solves #276.
* 2023-04-12 - Feature: Allow right-side blocks drawer of site home to be extended by default #169.

### v4.1-r6

* 2023-03-22 - Feature: Allow admin to provide several additional block regions, solves #30.
               Please note: This is a comparably large addition. If you encounter any issues with this feature, please report it on <https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/issues>.
* 2023-03-20 - Improvement: Don't force child themes to reimplement various color settings (e.g. 'brandcolor'), solves #260.
* 2023-03-17 - Improvement: Reduce code duplication when child theming by checking theme ancestry in theme_boost_union_before_standard_html_head, solves #245.

### v4.1-r5

* 2023-03-19 - Bugfix: Fully support multilang strings in advertisement tiles, solves #258.
* 2023-03-17 - Improvement: Don't force child themes to reimplement settings 'loginformposition' and 'loginformtransparency', solves #247.
* 2023-03-15 - Bugfix: $THEME->editor_scss referenced a non-existing sheet. Setting it also ignored Boost's sheets. This solves #242.
* 2023-03-18 - Bugfix: Fix wrong rgba color definition for advertisement tile backgrounds, solves #244.

### v4.1-r4

* 2023-03-06 - Bugfix: Align the search bar with the dark navbar look, solves #234.
* 2023-03-06 - Bugfix: Fix hover background color on dark navbars, solves #236.
* 2023-03-06 - Bugfix: Fix edit switch color on dark navbars, solves #235.

### v4.1-r3

* 2023-03-05 - Release: Add lern.link GmbH and bdecent GmbH to the list of maintainers in README.md.
* 2023-03-05 - Bugfix: Improve card header and footer of advertisement tiles with background images, solves #232.
* 2023-03-01 - Tests: Updated Moodle Plugin CI to use PHP 8.1 and Postgres 13 from Moodle 4.1 on.
* 2023-02-12 - Feature: Allow admin to change the navbar color, solves #39, helps to resolve #110.

### v4.1-r2

* 2023-02-12 - Feature: Enable admins to upload css code for mod_h5p and mod_hvp, solves #166 #207.
* 2023-02-12 - Cleanup: Change the "Advanced settings" tab to "SCSS", solves #226.
* 2023-02-12 - Cleanup: Create a dedicated "H5P" tab, solves #227.
* 2023-02-11 - Cleanup: Course related hints feature was handled twice in drawers.php, solves #223.
* 2023-02-11 - Improvement: Remove the 'no.' suffix from the info banners and tiles, solves #203.
* 2023-02-10 - Bugfix: Align horizontal margins for the info banners, solves #218.
* 2023-02-09 - Bugfix: Fix svg logo display problem in Firefox, solves #160.

### v4.1-r1

* 2023-02-04 - Upgrade: Persist the dedicated favicon setting in Boost Union even though Moodle core has a favicon setting in 4.1 as well, solves #78.
* 2023-02-04 - Upgrade: The back-top-top button must respect the presence of the new sticky footer, solves #186.
* 2023-02-04 - Upgrade: Add .footer-support-link class to Boost Union's footer links to align them with Boost Core in 4.1 again
* 2023-02-04 - Upgrade: Allow the admin to change medium width pages which were introduced for the database activity in 4.1 as well.
* 2023-02-04 - Upgrade: Adopt upstream changes in footer.mustache
* 2023-02-04 - Upgrade: Fix Behat tests which broke with Moodle 4.1.
* 2023-02-04 - Prepare compatibility for Moodle 4.1.

### v4.0-r12

* 2023-01-30 - Feature: Allow the admin to set CSS rules for the Moodle Mobile App, solves #195.
* 2023-01-28 - Improvement: Do not resize SVG logo files during serving, helps to solve #160.
* 2023-01-26 - Feature: Add dedicated logo settings to Boost Union, solves #211.
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
* 2022-06-20 - Allow full Behat runs with Boost Union suite, fixes #14.
* 2022-06-20 - Prepare settings.php page, solves #2.
* 2022-06-20 - Fill README.md, helps to resolve #3.
* 2022-04-29 - Adopt all changes which have happened in Boost core for the Moodle 4.0 release
* 2022-03-17 - Create boilerplate as Boost child theme without any additional features.
