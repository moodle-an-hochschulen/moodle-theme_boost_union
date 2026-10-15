Upgrading this plugin
=====================

This is an internal documentation for plugin developers with some notes what has to be considered when updating this plugin to a new Moodle major version.

General
-------

* Generally, this is a theme which adds a larger set of additional functionality and configurability to Boost from Moodle core.
* Due to the nature of themes and due to the amount of settings, the upgrading effort is high.


Upstream changes
----------------

* This theme is built on top of theme_boost from Moodle core. It inherits the codebase from theme_boost and overwrites and extends several behaviours and functions. Doing this, code duplication couldn't be avoided. If there are any upstream changes in theme_boost, you should check if they should be adopted to this theme as well.
* In addition to that, you should check any upstream changes which happen in /lib/templates, /lib/classes/output/core_renderer.php and /lib/classes/output/renderer_base.php as some of these templates and renderers are overwritten in this theme as well.
* In addition to that, you should check any occurences of 'copied and modified' in Boost Union as these comments might indicate additional places where you have to check upstream changes.
* In addition to that, you should check if any additions to theme_boost_union_get_scss_to_mark_external_links() should be made. This might be particularly necessary if any new links have been added to the footer questionmark icon in Moodle core (have a look at footer.mustache) or if any links have been added which have a hardcoded external-link icon (check for new occurrences of "i/externallink" in the Moodle codebase).
* In addition to that, you should check any upstream changes in /lib/js/esm/src/nav/Nav.tsx. The smart menus in the primary navigation and the menu bar are rendered by the theme_boost_union/nav/Nav React component in js/esm/src/nav/Nav.tsx which is a 'copied and modified' version of this core component. The unmodified core version is kept in js/esm/src/nav/Nav.tsx.upstream next to it to ease the comparison. After changing the component, rebuild it with 'npx grunt esm' from within the plugin directory and commit the files in js/esm/build as well. The React data export in classes/output/navigation/primary.php extends the export of /lib/classes/navigation/output/primary.php and should be checked against upstream changes there, too.
* In addition to that, you should check any upstream changes in the '.primary-navigation .navigation.primarynav-navbar' block in /theme/boost/scss/moodle/primarynavigation.scss. The block which styles the React mount point of the smart menu menu bar in scss/boost_union/post.scss (search for 'exact copy') is an exact copy of this core block with just the selector adapted and has to be kept in sync.
* In addition to that, you should check any upstream changes in the styling of the user menu submenus in /theme/boost/scss/moodle/navbar.scss ('.carousel-navigation-link', '.dropdown-item.carousel-navigation-link::after' and '.submenu .header'). The third level of the smart menus in the primary navigation and the menu bar is styled with a copy of these rules in scss/boost_union/post.scss (search for 'copied from Moodle core's styling of the submenus in the user menu') to make it look like the submenus in the user menu, and this copy has to be kept in sync.
* In addition to that, you should check any upstream changes in the positioning of the drawers and the drawer toggle buttons in /theme/boost/scss/moodle/drawer.scss and /theme/boost/scss/moodle/layout.scss. The 'Page layout with menu bar shown' section in scss/boost_union/post.scss mirrors these rules (including the $drawer-anchored-* variables) and adds the smart menu menu bar height to them. It has to be kept in sync.
* In addition to that, you should check if Bootstrap still ships the .navbar-dark class and the --bs-navbar-* custom properties. The navbar color setting relies on them (see the 'Setting: Navbar color' section in scss/boost_union/post.scss and layout/includes/navbar.php). Bootstrap has announced to remove the .navbar-dark class in Bootstrap 6. If this happens, Boost Union has to set the --bs-navbar-* custom properties for the light font color scheme itself.


Automated tests
---------------

* The theme has a good coverage with Behat tests which test most of the theme's user stories.


Manual tests
------------

* There aren't any manual tests needed to upgrade this plugin.
* However, if you look at the Behat feature file, you will see that there are some scenarios still commented out. If you have time, you should test them manually or write a Behat test for it.


Visual checks
-------------

* As this is a theme, you should have a close look at all functionalities of the theme and all major Moodle GUI pages to make sure that everything is displayed nicely and correctly.
* Within the SCSS snippets feature, several snippets are shipped as built-in snippets and are provided as well in the Boost Union SCSS repository. These snippets should be checked and updated if necessary and, in any case, the tested version should be raised in each snippet.
