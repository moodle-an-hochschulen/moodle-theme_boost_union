moodle-theme_boost_union
========================

Changes
-------

### Unreleased

* 2025-07-31 - Bugfix: Uninitialized $overflow might have caused the smart menu item icon picker to fail, resolves #1035
* 2025-07-28 - Bugfix: Reposition Boost Union footer buttons correctly if sticky footer is shown, resolves #1033
* 2025-07-28 - Improvement: Query SCSS snippets table during theme refresh only if the table exists, resolves #1024
* 2025-07-25 - Improvement: Introduce SCSS variable for smart menu menubar and bottom bar height, resolves #1023

### v5.0-r7

* 2025-07-22 - SCSS Snippet: Tertiary navigation button, resolves #1017
* 2025-07-22 - Improvement: Add CLI script to re-populate the list of built-in SCSS snippets, resolves #1019
* 2025-07-22 - Tests: Fix failing Behat test with the tertiary navigation, resolves #1009
* 2025-07-22 - Tests: Fix broken PHPUnit tests for SCSS snippets, resolves #1015

### v5.0-r6

* 2025-07-18 - Improvement: Allow all (solid and brands) FontAwesome icons for smart menu items, resolves #493
               Please note: Smart menu item icons are now distinguished if they come from Moodle core or from FontAwesome. Please have a look at the help icon for smart menu item icons if you are interested in the details.
* 2025-07-18 - Improvement: Improve the smart menu items icon list by replacing the custom icon picker with a Moodle autocomplete widget, by adding the icon names to the list and by sorting the list, helps to resolve #493
* 2025-07-18 - Tests: Fix failing Behat test with the tertiary navigation, resolves #1009
* 2025-07-16 - Tests: Fix / Improve Behat tests which deal with viewport sizes, resolves #1004 and #952.
* 2025-07-08 - Feature: Add a dedicated divider menu item type for smart menus, resolves #453.
               Please note: Existing dividers created using heading type with hash signs will be automatically converted to the new divider type during this version update.
* 2025-07-07 - Bugfix: SCSS Snippets overview page did not show previews of uploaded snippets, resolves #993
* 2025-07-06 - Feature: Add first version of CSS snippets feature, credits go to all members of the MoodleMootDACH 2024 dev camp team no. 22, resolves #988

### v5.0-r5

* 2025-07-05 - Chore: Rename the setting 'courselistinghowfields' to 'courselistingshowfields' to fix a typo
* 2025-07-05 - Improvement: Allow the admin to select the custom course fields on the course cards, resolves #846
* 2025-07-04 - Feature: Show course progress as progress bar on the course cards, resolves #844
* 2025-07-03 - Improvement: Show the login link in the navbar as button, resolves #979.
* 2025-06-06 - Feature: Add color picker settings for calendar branding, resolves #434.
* 2025-06-30 - Regression: Flavour favicon images were not working, resolves #942
* 2025-06-30 - Regression: Flavour background images were not working, resolves #942
* 2025-06-30 - Improvement: Allow the "guest access" hint for teachers to be shown as well if a guest password is set, resolves #984

### v5.0-r4

* 2025-06-09 - Upstream change: Remove preemptive regression fix after MDL-85326 was integrated, resolves #928.
               Please note: This change raises the required Moodle core version to 5.0.1
* 2025-06-09 - Tests: Fix a failing Behat test on the 'Allow admins to use the tertiary navigation' scenario, resolves #973
* 2025-06-06 - Upstream change: Adopt changes from MDL-85323 to locallogin.php
* 2025-06-06 - Upstream change: Adopt changes from MDL-85450 to upcoming-mini.mustache
* 2025-06-06 - Upstream change: Adopt changes from MDL-85220 to view-chards.mustache
* 2025-06-04 - Bugfix: Enabling the footersuppresslogininfo setting prevented the reset of the failed login attempts counter, resolves #658
* 2025-06-04 - Bugfix: Prevent debug message regarding newly introduced "displayhiddencourses" and "hiddencoursesort" option in dynamic courses items, resolves #970

### v5.0-r3

* 2025-05-30 - Improvement: Smart menus now display hidden courses to users with the appropriate capability, resolves #407.
               Please note: With this change, existing dynamic courses smart menu items will start to show hidden courses as this is what most users expect. If you do not want to show hidden courses, please change the newly introduced 'Show hidden courses' setting in your existing menu items after the theme update.
* 2025-05-30 - Improvement: Transform the plain paragraphs on some settings pages into Bootstrap alerts for a nicer look.
* 2025-05-27 - Bugfix: The page scrolled to the top when a dynamic courses submenu got clicked, resolves #962.

### v5.0-r2

* 2025-05-26 - Bugfix: Language selector menu for visitors did not respect navbar color, resolves #966.
* 2025-05-26 - Bugfix: Main navigation more menu did not respect navbar color, resolves #967
* 2025-05-26 - Improvement: Enhance course related hint for guest access for teachers by a possibility to build own guest access link, resolves #960.
* 2025-05-25 - Bugfix: Fix a faulty HTML structure in the slider feature, resolves #965.
* 2025-05-23 - Feature: Add course related hint for guest access for teachers as well, resolves #960.
* 2025-05-23 - Improvement: Re-sort the settings for the course related hints.

### v5.0-r1

* 2025-04-10 - Upgrade: Replace Bootstrap class custom-select with form-select
* 2025-05-17 - Upgrade: Failing smart menu Behat tests on Moodle 5.0, resolves #929.
* 2025-05-17 - Upgrade: Adopt changes from /lib/amd/src/usermenu.js to /theme/boost_union/amd/src/submenu.js, resolves #913.
* 2025-04-10 - Upgrade: Fix the inline-block presentation of the date settings in the infobanner settings.
* 2025-05-08 - Preemptive regression fix: Moodle 5.0 showed the footer (questionmark) icon on mobiles as well, resolves #928.
* 2025-04-10 - Upgrade: Remove the 'none' option from the slider animation type as this seems not to anymore out of the box in Bootstrap 5.
* 2025-04-10 - Upgrade: Adapt some more deprecated Bootstrap classes.
* 2025-04-10 - Improvement: Change the defaults of the theme/boost_union:viewregion* capabilities for the guest role on new Boost Union installatins, resolves #893.
               Please note: Boost Union instances which are upgraded from a release before v5.0 will see a notification on /admin/settings.php?section=theme_boost_union_feel#theme_boost_union_feel_blocks and will have the possibility there to fix the defaults of the role automatically.
* 2025-04-10 - Upgrade: Adapt width of advertisement tiles on site home, resolves #917.
* 2025-04-10 - Upgrade: Support the new activity overview page when changing activity icon purposes in Boost Union.
* 2025-04-10 - Upgrade: Remove recentlyaccesseditemstintenabled setting as the icons in this block are tinted by default now in Moodle 5.0.
* 2025-04-10 - Upgrade: Adapt activity coloring for Moodle 5.0 and remove the activityiconcolorfidelity setting, resolves #824.
* 2025-04-10 - Upgrade: Remove activitiestintenabled setting as the activities block is gone in Moodle 5.0.
* 2025-04-10 - Upgrade: Adapt Behat tests as the subsections are now enabled by default
* 2025-04-10 - Upgrade: Adopt changes regarding dismisssing alerts for the infobanner feature in Boost Union
* 2025-04-10 - Upgrade: Adopt Bootstrap changes to the media-breakpoint-down mixin
* 2025-04-10 - Upgrade: Adopt Bootstrap changes to the slider feature in Boost Union
* 2025-04-10 - Upgrade: Fix the appearance of the off-canvas block region
* 2025-04-10 - Upgrade: Adopt changes for course index view feature in Boost Union
* 2025-04-10 - Upgrade: Adopt changes to other sr-only classes used in Boost Union
* 2025-04-10 - Upgrade: Adopt changes to submenu.js
* 2025-04-10 - Upgrade: Adopt changes to other data-bs-* attributes used in Boost Union
* 2025-04-10 - Upgrade: Adopt changes to courselistingcard.mustache
* 2025-04-10 - Upgrade: Adopt changes to courselistinglist.mustache
* 2025-04-10 - Upgrade: Adopt changes to smartmenus-*menu-children.mustache
* 2025-04-10 - Upgrade: Adopt changes to cm.mustache
* 2025-04-10 - Upgrade: Adopt changes to user_menu.mustache
* 2025-04-10 - Upgrade: Adopt changes to loginform.mustache
* 2025-04-10 - Upgrade: Adopt changes to moremenu.mustache
* 2025-04-10 - Upgrade: Adopt changes to primary-drawer-mobile.mustache
* 2025-04-10 - Preemptive regression fix: Communication button in course did not use the same Bootstrap 5 styling as the footer (questionmark) button, resolves #912.
* 2025-04-10 - Upgrade: Adopt changes to footer.mustache and all Boost Union footer buttons.
* 2025-04-10 - Upgrade: Adopt changes to drawers.mustache
* 2025-04-10 - Upgrade: Adopt changes to classes/boostnavbar.php
* 2025-04-10 - Upgrade: Adopt changes to drawer.mustache
* 2025-04-10 - Upgrade: Adopt Bootstrap changes to navbar.mustache and the colored navbar feature.
               Please note: The bg-dark color has changed in Bootstrap 5 which results in Boost Union in a even darker navbar.
* 2025-04-10 - Upgrade: Remove mediumwidth setting, resolves #780
* 2025-04-10 - Upgrade: Replace the $nav-divider-color SCSS variable in post.scss which does not exist anymore and prevented the Boost Union SCSS from being compiled.
* 2025-04-10 - Prepare compatibility for Moodle 5.0.

### v4.5-r17

* 2025-05-21 - Bugfix: Smart menu "Visibility by language" restriction was not applied correctly after changes of the current language, resolves #697.
* 2025-05-20 - Release: Set the Boost Union logo and tagline as screenshot for the theme overview page, resolves #925

### v4.5-r16

* 2025-05-08 - Feature: Allow the admin to configure the link target of the cog icon in the starred courses popover, resolves #939
* 2025-05-08 - Bugfix: Smart menu item icon was not black when hovered on a black navbar, resolves #936

### v4.5-r15

* 2025-05-06 - Tests: The fine-grained personal access token (theme-boost_union-extscsstest.behat) had expired, resolves #932
* 2025-05-06 - Regression: Course search page did not use the improved course listings anymore, resolves #930
* 2025-05-02 - Bugfix: On mobile devices, the course listing combo box produced horizontal scroll bars, resolves #926.
* 2025-04-30 - Bugfix: Modified smart menu transition time had an impact on the slider, resolves #922.
* 2025-04-30 - Bugfix: Fix a HTML nesting glitch for the course lists on site home, resolves #919.
* 2025-04-28 - Improvement: Remove a surplus CSS statement regarding the back-to-top button.

### v4.5-r14

* 2025-04-25 - Improvement: Fix a small glitch in the slider's language strings
* 2025-04-24 - Bugfix: Make sure that the navbar highlight is not shown in the dark navbar, resolves #908.
* 2025-04-22 - Bugfix: Support multilang course category names in stickyheaders, resolves #905.

### v4.5-r13

* 2025-04-06 - Bugfix: Enrol page used modified course listing renderer from category index / site home as well, resolves #895.
* 2025-04-01 - Feature: Add moodle documentation smartmenu item type, resolves #657.

### v4.5-r12

* 2025-03-29 - Bugfix: Remove assumption that syscontext->id = 1, resolves #627
* 2025-03-28 - Improvement: Add resizing to flavour logo and compact logo, resolves #212.
* 2025-03-27 - Improvement: Add admin main navigation to smart menu items page as well, resolves #882.
* 2025-03-27 - Improvement: Add tertiary navigation to Boost Union admin settings pages to allow jumping from one settings page to another, resolves #876.
               Child theme support: If you are running a Boost Union Child theme, please make sure to adapt your child theme to our latest boilerplate.

### v4.5-r11

* 2025-03-26 - Bugfix: Footer button got pushed upwards even though no bottom menu bar was present, resolves #784.
* 2025-03-25 - Bugfix: If a course category is hidden, the "go to course" button (in the course cards view) got a wrong color, resolves #877.
* 2025-03-25 - Bugfix: Make sure that the sticky category headers (in the course cards view) does not cover the more menu, resolves #866.
* 2025-03-25 - Bugfix: Do not show the sticky category headers (in the course cards view) for categories with just a few courses, resolves #866.
* 2025-03-24 - Bugfix: SmartMenu caused JavaScript error 'Uncaught TypeError: moreMenu is null', resolves #850.
* 2025-03-10 - Bugfix: Guests could not use the side entrance login page, resolves #653.
* 2025-03-10 - Bugfix: Fix unwanted redirect to IdP from locallogin.php in case alternateloginurl is set, resolves #775.
* 2025-03-10 - Improvement: Show a clearer warning to the admin about the risks of disabling the local login form, resolves #777.
* 2025-03-10 - Improvement: Allow the side entrace login page to be enabled independent from the localloginenable setting, resolves #782

### v4.5-r10

* 2025-03-10 - Bugfix: The course details modal did not work on all site home widgets, resolves #851.
* 2025-03-09 - Bugfix: The theme's SCSS could not be built if setting activityiconcolorfidelity was not set yet, resolves #838
* 2025-03-09 - Upgrade: Remove theme_boost_union_before_standard_html_head and theme_boost_union_before_standard_top_of_body_html callbacks from Moodle 4.5 and 4.4 releases finally, resolves #604.
* 2025-03-09 - Improvement: Introduce a Boost Union specific string for "submit" to be used in the accessbility support form which allows better translatons, resolves #830.
* 2025-03-08 - Upgrade: Replace .media Bootstrap class according to MDL-80396, resolves #835.
* 2025-03-08 - Improvement: Add setting to limit the maximum width of the navbar logo if it is too broad or has a special aspect ratio, resolves #544.

### v4.5-r9

* 2025-02-22 - Feature: Add some styling to the category listings on site home and on the category index pages, resolves #840.
* 2025-02-16 - Feature: Show the course listing on site home and on the category index pages as (proper) list, resolves #573.
* 2025-02-07 - Feature: Show the course listing on site home and on the category index pages as cards, resolves #558.

### v4.5-r8

* 2025-02-17 - Bugfix: Remove the possibility to set the activity purpose for subsections to avoid that activities within subsections get tinted with the wrong color, resolves #823.
* 2025-02-12 - Bugfix: Accessibility page link in description differed from real location, resolves #818.

### v4.5-r7

* 2025-02-11 - Bugfix: Using smart menus together with custom menus broke Moodle, resolves #814, regression of #602.
* 2025-02-10 - Bugfix: Adopt accessibility changes from MDL-67683 which led to Boost Union Behat failures on Moodle core 4.5.2 and 4.4.6, resolves #813.
               Please note: This change raises Boost Union's required Moodle core version to 4.5.2.

### v4.5-r6

* 2025-02-04 - Improvement: Hide the 'Menu item mode' settings for smart menu items which are not of the 'dynamic courses' item type, resolves #804.
* 2025-02-04 - Bugfix: Smart menu 3rd level submenus were being cut-off in responsive / mobile view, resolves #356.
               Please note: This is a comparably large visual change which effectively replaces the presentation of a 3rd level smart menu (which can only be realized with dynamic courses menu items up to now). Flyout menus in the main navigation area and the menu bar area have been replaced with the 'sliding door' submenu behaviour which has been used in the user menu only up to now. If you are using dynamic courses menu items, please test your particular smart menu setup before updating to this Boost Union release.
* 2025-02-04 - Bugfix: Smart menu 3rd level submenus had a font color which differed from the 2nd level and might have been even invisible, resolves #459.
* 2025-02-04 - Bugfix: Long smart menus were not scrollable vertically, resolves #406.
* 2025-02-04 - Bugfix: Fix smart menu dynamic course items not updating properly based on course role assignments, resolves #749.
* 2025-02-04 - Bugfix: Smart menu item pointing to external site gets highlighted as active by mistake, resolves #758.
* 2025-02-04 - Improvement: Allow changing of home URL on small devices as well, resolves #802.
* 2025-01-31 - Improvement: Add option to include alt text for item image in smart menu cards, resolves #752.

### v4.5-r5

* 2024-12-31 - Child theme support: Fully replicate Boost Union's extra SCSS if a Boost Union Child theme is the current theme, resolves #718, resolves theme_boost_union_child/#5.
* 2024-12-31 - Bugfix / Child theme support: The theme_boost_union_get_pre_scss() and theme_boost_union_get_extra_scss() function used $theme->settings although they should not do that anymore, resolves #791
* 2024-12-31 - Tests: Increase the test coverage for the background image setting, helps to resolve theme_boost_union_child/#5
* 2024-12-31 - Bugfix: In flavours, not setting the brand color / bootstrap colors in a flavour did not result in the global brand color / bootstrap colors being served properly, resolves #790.
* 2024-12-30 - Improvement: Add more flavour settings (Activity icon colors, navbar color, background image position), resolves #789.
* 2024-12-25 - Documentation: Explain the SCSS stack order in the README.
* 2024-12-25 - Bugfix: Fix the order in which all the pre SCSS assets are added to the SCSS stack, resolves #788.
* 2024-12-22 - Feature: Allow overwriting of brand colors and the usage of SCSS (instead of pure CSS) in flavours, resolves #155.
               Child theme support: If you are running a Boost Union Child theme, please make sure to adapt your child theme to our latest boilerplate.
* 2024-12-15 - Feature: Add declaration of accessibility page and accessibility support page, resolves #567.

### v4.5-r4

* 2024-12-06 - Tests: Add several Behat optimisations to bring down the test suite run time, resolves #765.
* 2024-12-06 - Upstream change: Adopt changes from MDL-83759 ('System notification navbar popover is misplaced in Moodle 4.4 and 4.5')
* 2024-12-06 - Upstream change: Adopt changes from MDL-75610 ('Quiz activity name no longer being displayed in quiz landing page when using Safe Exam Browser'), resolves #766.

### v4.5-r3

* 2024-11-19 - Bugfix: The starred courses popover showed a JavaScript error in the browser JS console, resolves #759.
* 2024-11-19 - Bugfix: The starred courses popover in the navbar must only be shown if Boost Union or Boost Union child is active, resolves #759.
* 2024-11-18 - Improvement: Add the possibility to restrict smart menus and smart menu items to site admins and non-site admins only, resolves #421.
* 2024-11-18 - Bugfix: Footer displacement on pages with minimal content, resolves #655.
* 2024-11-18 - Upstream change: Adopt changes from MDL-77732 ('Custom menu items do not receive active behaviour'), resolves #436 #620 #384 #715.
* 2024-11-13 - Upstream change: Adopt changes from MDL-78999 ('Site logo does not appear in mobile view'), resolves #753.
* 2024-11-11 - Release: Add ssystems GmbH to the list of maintainers in README.md.

### v4.5-r2

* 2024-10-31 - Bugfix: Fix possible site failure when a cohort or role used as restriction of a smart menu item is deleted, resolves #737.
* 2024-10-24 - Release: Change support thread URL in README to a tiny URL.
* 2024-10-24 - Tests: Try to fix Behat error 'Warning: Undefined array key 1' on Moodle 4.5, resolves #734.

### v4.5-r1

* 2024-10-15 - Upgrade: Update FontAwesome icon mappings for handling external links.
* 2024-10-15 - Upgrade: Add note to the 'Login providers' settings that Boost Union will continue to use its own settings, even after MDL-80967 was integrated in Moodle 4.5
* 2024-10-15 - Upgrade: Enhance the 'activity type icons in course index' feature to support subsections in 4.5 + align the icon stylings to 4.5
* 2024-10-14 - Upgrade: Fix broken Behat scenario 'Smartmenu: Menus: Presentation - Display smart menu description in different places'
* 2024-10-14 - Upgrade: Fix broken Behat scenario 'Smartmenus: Menu items: Presentation - Display the menu items title with icon'
* 2024-10-14 - Upgrade: Fix broken Behat scenario 'Setting: Custom icons files - Upload custom icons files'
* 2024-10-14 - Upgrade: Fix broken Behat scenario 'Flavours: Caching - After deleting a cohort, the flavour which applies now should take direct effect'
* 2024-10-14 - Upgrade: Fix broken Behat scenario 'Setting: Footer - Suppress icons in front of the footer links'
* 2024-10-14 - Upgrade: Adopt changes from MDL-82183 and use several new class names, at least in our own / non-adopted code.
* 2024-10-14 - Upgrade: Adopt changes from MDL-81960 and use new \core\url class, at least in our own / non-adopted code.
* 2024-10-14 - Upgrade: Adopt changes from MDL-81920 and use new \core\lang_string class.
* 2024-10-14 - Upgrade: Adopt changes from MDL-81031 and use new \core\user class.
* 2024-10-14 - Upgrade: Adopt changes from MDL-66903 and use new \core\component class.
* 2024-10-14 - Upgrade: Adopt changes from MDL-82158 and use new cache classes.
* 2024-10-13 - Upgrade: Adopt changes from MDL-75671 into custom Boost Union code.
* 2024-10-13 - Upgrade: Remove legacy implementation of before_standard_html_head, resolves #606.
* 2024-10-13 - Upgrade: Adopt changes from MDL-82183 where lib/outputrenderers.php was split up.
* 2024-10-13 - Upgrade: Adopt changes from MDL-75671 in navbar.mustache
* 2024-10-13 - Upgrade: Adopt changes from MDL-81725 in cm.mustache
* 2024-10-13 - Upgrade: Adopt changes from MDL-75671 in cm.mustache
* 2024-10-13 - Upgrade: Adopt changes from MDL-75671 in event-list-item.mustache
* 2024-10-13 - Upgrade: Adopt changes from MDL-75671 in view-cards.mustache
* 2024-10-10 - Upgrade: Adopt changes from MDL-81818 to remove old bootstrap classes
* 2024-10-10 - Upgrade: Adopt changes from MDL-74251 to remove old icon classes
* 2024-10-10 - Upgrade: Adopt changes from MDL-75671 in user_menu.mustache
* 2024-10-10 - Upgrade: Adopt changes from MDL-75671 in user_action_menu_submenu_items.mustache
* 2024-10-10 - Upgrade: Adopt changes from MDL-75671 in primary-drawer-mobile.mustache
* 2024-10-10 - Upgrade: Adopt changes from MDL-75671 in drawers.mustache
* 2024-10-10 - Upgrade: Adopt changes from MDL-75671 in upcoming_mini.mustache
* 2024-10-10 - Upgrade: Adopt changes from MDL-74251 and MDL-75671 in loginform.mustache
* 2024-10-10 - Upgrade: Adopt change from MDL-75671 in full_header.mustache
* 2024-10-10 - Upgrade: Use the before_session_start() callback instead of the after_config() callback on Moodle 4.5, resolves #721.
* 2024-10-10 - Upgrade: Adopt change from MDL-75671 in course_listitem_actions().
* 2024-10-10 - Upgrade: Fix removed /cache/classes/loaders.php which prevented the theme from being used on Moodle 4.5, resolves #708.
* 2024-10-07 - Prepare compatibility for Moodle 4.5.

### v4.4-r3

* 2024-10-21 - Improvement: Add link to policyoverviewnavigation setting, resolves #732.
* 2024-10-14 - Test: Change tests/fixtures/*.jpg to tests/fixtures/*.png to prevent resizing issues with JPG images

### v4.4-r2

* 2024-10-09 - Bugfix: Course category breadcrumbs were broken on the course enrolment page due to MDL-80974 and were removed, resolves #727.
* 2024-10-08 - Upstream change: Adopt change from MDL-82298 into smartmenus-[card|more]menu-children.mustache
* 2024-08-24 - Upgrade: Update Bootstrap classes for Moodle 4.4.
* 2024-08-11 - Updated Moodle Plugin CI to latest upstream recommendations
* 2024-07-24 - Test: Fix broken Behat scenario 'Suppress 'Chat to course participants' link', resolves #696
* 2024-07-23 - Bugfix: Fix unparsable example JSON in Mustache template

### v4.4-r1

* 2024-07-15 - Development: Rename master branch to main, please update your clones.
* 2024-07-13 - Upgrade: Make the \theme_boost_union\task\purge_cache task non-blocking as this has been deprecated in Moodle core.
* 2024-07-13 - Bugfix: Adopt fix for MDL-82397 before its integration into Moodle core, relates to #691.
* 2024-07-12 - Upgrade: Adapt the course index icon feature visually to the new icon sizes.
* 2024-07-12 - Upgrade: Adopt changes for coloring the activity icons when modifying the activity purpose.
* 2024-07-12 - Upgrade: Adopt new activity purpose "Interactive content" when coloring activity icons, resolves #611.
* 2024-07-07 - Upgrade: Adopt changes for coloring the activity icons, moving from background-colors to CSS filters, resolves #631.
* 2024-07-04 - Upgrade: Fix Behat tests which broke due to the introduction of section pages in Moodle core.
* 2024-07-04 - Upgrade: Adopt changes in boostnavbar.php from Boost core.
* 2024-07-04 - Upgrade: Fix Behat tests which broke due to changes in the section naming in Moodle core.
* 2024-07-04 - Upgrade: Adapt a Behat test as planned regarding the new theme selector in Moodle core.
* 2024-07-04 - Upgrade: Fix Behat tests which broke due to changes on the MyCourses page in Moodle core.
* 2024-06-25 - Upgrade: Adopt and handle core changes for the footersuppressstandardfooter_* settings, moving from callback functions to hooks.
* 2024-06-19 - Upgrade: Adopt changes in event-list-item.mustache from block_timeline in core.
* 2024-06-19 - Upgrade: Adopt changes in view-cards.mustache from block_recentlyaccesseditems in core.
* 2024-06-19 - Upgrade: Adopt changes in loginform.mustache from Boost core.
* 2024-06-19 - Upgrade: Adopt changes in navbar.mustache from Boost core.
* 2024-06-01 - Prepare compatibility for Moodle 4.4.

### v4.3-r15

* 2024-07-11 - Bugfix: Allow external SCSS to use SCSS variables by disabling the SCSS validation, resolves #683.
* 2024-06-23 - Upstream change: Adopt change in view-chards.mustache from MDL-70829.
* 2024-06-18 - Release: Let codechecker ignore some sniffs in the language pack.
* 2024-06-13 - Cleanup: Change @codingStandardsIgnore tags to phpcs:disable, resolves #676.
* 2024-06-12 - Cleanup: Fix CSS warnings in external SCSS tests, resolves #674.

### v4.3-r14

* 2024-06-10 - Cleanup: Introduce a dedicated Behat step to deactivate and activate debugging, resolves #670.
* 2024-05-05 - Cleanup: Fix 'Implicitly marking a parameter as nullable is deprecated since PHP 8.4' codechecker warning, resolves #667.
* 2024-04-28 - Feature: Allow admins to configure URLs from where Boost Union will fetch additional raw SCSS code, resolves #41.
* 2024-05-13 - Improvement: Suppress icons in footer, resolves #649
* 2024-05-13 - Bugfix: Make the "More menu behavior" setting in smart menus more stable, resolves #461.

### v4.3-r13

* 2024-05-11 - Improvement: Enhance smart menu restrictions for authenticated user role, guest roles and visitor role, resolves #571
* 2024-05-11 - Improvement: Smart menu "locations" must be filled with a value, resolves #404
* 2024-05-10 - Bugfix: Do not show empty smart menus to users, resolves #405
* 2024-05-09 - Bugfix: Smart menu menubar overlaid course index, resolves #607
* 2024-04-27 - Improvement: Add navigation to policy overview page, resolves #633

### v4.3-r12

* 2024-04-20 - Bugfix: Footnote ignored paragraph breaks, resolves #623.
* 2024-04-20 - Improvement: Add hint that notifications don't work within forums for hidden courses, resolves #98.
* 2024-04-20 - Bugfix: Correct order for in-course breadcrumb when sections exist in it (First categories then sections), solves #317.
* 2024-04-20 - Cleanup: Add proper JS promise error handling, resolves #435.

### v4.3-r11

* 2024-04-01 - Bugfix: Site support form success message is now shown above advert tiles / the slider on frontpage, partly resolves #488.
* 2024-04-01 - Bugfix: In smart menus, the search for cohorts in restrict visibility by cohorts didn't work for more than 25 cohorts, resolves #462.
* 2024-04-01 - Improvement: Enhance the activitynavigation setting description to cover section navigation as well, resolves #536.
* 2024-03-30 - Bugfix: Smart menu divider did not work for user menu submenus, resolves #537.
* 2024-03-25 - Upgrade: Boost Union settings were moved to an admin settings category of its own to support the new theme chooser on Moodle 4.4, resolves #482.
               Please note: This change is backported to Moodle 4.3 to 4.1 as well.
               Child theme support: If you are running a Boost Union Child theme, please make sure to adapt your child theme to our latest boilerplate.
* 2024-03-22 - Upgrade: Migrate the before_standard_html_head() function to the new hook callback on Moodle 4.4, resolves #604.

### v4.3-r10

* 2024-03-18 - Improvement: Add prefixes to the sessionStorage keys in the scrollspy implementation, resolves #598.
* 2024-03-18 - Improvement: Switch to the active Boost Union admin sub-tab after saving a setting and the following page reload, resolves #468.
* 2024-03-16 - Feature: Show the logged-in user's full name in the user menu, resolves #439.
* 2024-03-16 - Bugfix: Leave the last item's link in the breadcrumb only if it's really needed, resolves #595

### v4.3-r9

* 2024-03-13 - Improvement: In smart menus, dynamic courses can now pick up the courses from all subcategories, resolves #395.
* 2024-03-13 - Bugfix: Custom course fields of type "Textarea" were not conditionally hidden in the smart menu configuration, resolves #576.
* 2024-03-01 - Feature: Show starred courses popover in the navbar, resolves #289.

### v4.3-r8

* 2024-02-22 - Feature: Allow the admin to change the link behind the logo in the navbar, resolves #565.
* 2024-02-22 - Feature: Allow administrators to change the order of login items on the login page without using CSS or touching the mustache template, resolves #504.

### v4.3-r7

* 2024-02-21 - Bugfix: Single activity format contained unnecessary second level of navigation items, resolves #415.

### v4.3-r6

* 2024-02-18 - Make codechecker happier
* 2024-02-17 - Test: Use custom step to check the menus and menu items existence, resolves #365.
* 2024-02-12 - Feature: Allow the admin to display activity icons in course content navigation, resolves #16.
* 2024-02-11 - Child theme support: Improve namespace of class smartmenu_helper, resolves #494.
* 2024-02-11 - Child theme support: Update note about grandchild themes in README.md
* 2024-02-09 - Improvement: Inherit preset setting and preset files from Boost Core instead of duplicating them into Boost Union, resolves #267.
               Please note: The preset setting in Boost Union was practically broken up to now. Removing the duplicated setting should not break anything.
* 2024-02-06 - Child theme support: Adapt favicon behat scenario to support Boost Union Child
* 2024-01-30 - Child theme support: Don't force child themes to reimplement the settings to mark links and the settings to modify the course overview block, resolves #345.
* 2024-01-30 - Child theme support: Don't force child theme to reimplement activitypurpose_MODNAME settings, resolves #370.
* 2024-02-11 - Upstream change: Update 'Documentation for this page' string after upstream change in MDL-80725, resolves #559.
* 2024-02-11 - Peer review management: Add a github action checking pull requests for use of non get_config() theme settings references, resolves #257.

### v4.3-r5

* 2024-01-20 - Improvement: Add a side entrance login page for local logins if the local login form is disabled on the standard login page, resolves #539.
* 2024-01-20 - Improvement: Make all block regions available for the incourse and coursecategory page layouts, resolves #543.
* 2024-01-19 - Bugfix: Get rid of 'Undefined stdClass property' notices for static page settings, resolves #431.

### v4.3-r4

* 2024-01-14 - Bugfix: Add missing theme_reset_all_caches updatecallback to markmailtolinks and markbrokenlinks settings.
* 2024-01-10 - Bugfix: Avoid debug messages during initial installation of Boost Union due to uninitialized settings.
* 2024-01-07 - Test: Install language packs programmatically in Behat tests, solves #540.
* 2024-01-07 - Test: Add behat tests for the customisation of the appearance of H5P activities, solves #228.
* 2024-01-07 - Bugfix: The scrollspy Javascript shortly showed an error when using the edit toggle button not on course page, solves #286.
* 2024-01-06 - Feature: Add admin setting to add a direct link for selecting default language to the user's language menu, solves #128.
* 2024-01-06 - Test: Enhance the test coverage of the Look -> Page settings.
* 2024-01-06 - Feature: Allow the admin to set the course index and block drawer width, solves #74.
* 2024-01-05 - Test: Enhance the test coverage of the Look -> Site branding settings.
* 2024-01-05 - Test: Enhance the test coverage of the Look -> Activity branding settings.
* 2024-01-04 - Feature: Add admin setting to suppress all links in the footer popup individually, solves #6.
* 2024-01-04 - Improvement: Improve the logic in the overridden footer.mustache, solves #530.
* 2024-01-04 - Feature: Provide $CFG->themerev as SCSS variable, solves #58.
* 2023-12-31 - Improvement: Allow designers to mark external and mailto links manually, solves #525 & #526 & #528.
* 2023-12-31 - Improvement: Add an admin setting to limit the scope of the "Mark external links" and "Mark mailto links" features, solves #525 & #526 & #528.
* 2023-12-31 - Improvement: Fix several edge-cases where the "Mark external links" should not add its icons, solves #525 & #526 & #528.
* 2023-12-28 - Feature: Add slider which can be displayed on site home, solves #162.
* 2023-12-27 - Improvement: Add content style setting to the advertisement tiles, solves #519.
* 2023-12-27 - Test: Always reactivate debugging during Behat tests, solves #521.
* 2023-12-26 - Improvement: Do not add the advertisement tiles div to the frontpage if no tile is activated, solves #516.
* 2023-12-24 - Bugfix: Back to top button was missing directly after the scroll-spy scrolled the page, solves #386.
* 2023-12-24 - Tests: Use a dedicated and simple step to purge the theme cache, solves #513.
* 2023-12-23 - Feature: Add admin option to mark broken links and mailto links, solves #163 #164.
* 2023-12-21 - Feature: Add setting to upload touch-images for iOS devices, solves #151.
* 2023-12-18 - Improvement: Shrink description_format column size in theme_boost_union_flavours table, solves #321.
* 2023-12-11 - Improvement: Configurable sort order in menu items of smart menu, solves #403.
* 2023-12-20 - Test: Enhance the test overage of the smart menus, solves #363 #364 #367 #374 #375.
* 2023-12-10 - Feature: Allow the admin to hide the manual login form and the IDP login intro, solves #490.
* 2023-12-10 - Improvement: Allow the admin to change the look of the course overview block, solves #204

### v4.3-r3

* 2023-12-05 - Improvement: Option to suppress footer (circle containing the question mark) button, solves #444.
* 2023-12-01 - Bugfix: Static pages unnecessarily cleaned configured content, solves #486.
* 2023-11-23 - Bugfix: Add background color to OAuth2 login button, solves #473.

### v4.3-r2

* 2023-11-11 - Bugfix: Bulk actions widget overlaid course header image, solves #469.
* 2023-11-09 - Bugfix: Hide back to top button on small screens as soon as the right hand drawer is opened, solves #379.
* 2023-11-09 - Bugfix: Styles of styled e-mail previews leaked into the rest of the admin UI, solves #413.
* 2023-11-04 - Bugfix: Pass footnote content without text_to_html div generation, solves #442.
* 2023-10-09 - Improvement: Add a direct 'view course' icon on the course management pages, solves #129.
* 2023-10-05 - Improvement: Allow the admin to set the background-position of the background and login background images, solves #111.
* 2023-11-03 - Bugfix: Add missing cachedef strings to the language pack, solves #441.

### v4.3-r1

* 2023-10-31 - Upgrade: Align the new communications button with the back-to-top button and the bottom menu.
* 2023-10-28 - Upgrade: Fix the scrollspy which broke on Moodle 4.3, solves #420.
* 2023-10-28 - Upgrade: Fix a broken Behat test with the additional resources setting.
* 2023-10-28 - Upgrade: Fix the back-to-top button which broke on Moodle 4.3, solves #419.
* 2023-10-27 - Upgrade: Fix a broken Behat test with modal confirm dialogues.
* 2023-10-27 - Upgrade: Get rid of deprecation warning in the additional resources setting, solves #425.
* 2023-10-27 - Upgrade: Use the new $activity-icon-* SCSS variables for the activity icon color settings.
* 2023-10-27 - Upgrade: Replace deprecated user_preference_allow_ajax_update() function.
* 2023-10-25 - Upgrade: Adopt changes in layout/drawers.php from Boost core.
* 2023-10-25 - Upgrade: Adopt changes in primary-drawer-mobile.mustache from Boost core.
* 2023-10-25 - Upgrade: Adopt changes in drawers.mustache and footer.mustache from Boost core.
* 2023-10-20 - Prepare compatibility for Moodle 4.3.

### v4.2-r4

* 2023-10-31 - Bugfix: Align the back-to-top button better with the sticky footer, solves #437.
* 2023-10-29 - Test: Add missing Behat tests for Scroll-spy implementation, solves #86.
* 2023-10-14 - Add automated release to moodle.org/plugins

### v4.2-r3

* 2023-10-01 - Bugfix: Omit PHP deprecation warnings on PHP 8.2, solves #411.
* 2023-10-01 - Test: Run Moodle Plugin CI with PHP 8.2 as well.
* 2023-10-01 - Bugfix: Smart menus did not support multilanguage filters, solves #376.
* 2023-09-28 - Release: Make sure that Smart Menu SCSS does not affect installations which do not use smart menus, solves #380.
* 2023-09-22 - Bugfix: Transition in second level in user menu was wrong, solves #397
* 2023-09-22 - Improvement: Smartmenus.js is only be added to the page if smart menus are really used, solves #357
* 2023-09-22 - Bugfix: Smart menu language restriction did not respect switching language with the language switcher, solves #358.
* 2023-09-26 - Bugfix: The smart menu third level arrow was broken, solves #402.
* 2023-09-22 - Make codechecker happier
* 2023-09-24 - Test: Behat scenario 'Show hint for self enrolment without an enrolment key' was broken, solves #398.
* 2023-09-22 - Improvement: Reuse Moodle core function remove_dir(), solves #369.

### v4.2-r2

* 2023-09-19 - Bugfix: Fix fatal mustache rendering errors, solves #390.
               This issue was a regression of #385 which adopted Moodle core changes from MDL-78656 into Boost Union.
               During this adoption into Boost Union, it was overseen to raise the minimum required Moodle core version for Boost Union at the same time.
               If you intend to use this release of Boost Union, please update Moodle core to the latest Moodle core 4.2.2+ or wait some more days for 4.2.3. Thank you for your understanding.

### v4.2-r1

* 2023-09-17 - Bugfix: Fix double-labeling and FontAwesome issues with the 'Mark external links' feature, solves #323 and #327.
* 2023-09-17 - Upgrade: Use better icon for offcanvas button with FontAwesome 6, solves #265.
* 2023-09-17 - Upgrade: Remove 'FontAwesome version' setting as FontAwesome 6 Free has been integrated into Moodle core. Boost Union will use FontAwesome 6 by default from 4.2 on, solves #389.
* 2023-09-17 - Upgrade: Adopt PHPDoc change from MDL-77164.
* 2023-09-01 - Prepare compatibility for Moodle 4.2.
* 2023-09-17 - Improvement: Adopt Moodle core bugfix from MDL-78138 which fixed the category breadcrumbs in Boost core, removing the 'Do not change anything' option from the 'Display the category breadcrumbs in the course header' setting, solves #388
* 2023-09-17 - Bugfix: Adopt Moodle core bugfix from MDL-78644 to allow .ico files as favicons again, solves #387.
* 2023-09-17 - Updated Moodle Plugin CI to latest upstream recommendations

### v4.1-r10

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
