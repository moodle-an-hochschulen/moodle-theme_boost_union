moodle-theme_boost_union
========================

[![Moodle Plugin CI](https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/workflows/Moodle%20Plugin%20CI/badge.svg?branch=master)](https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/actions?query=workflow%3A%22Moodle+Plugin+CI%22+branch%3Amaster)

Theme Boost Union is an enhanced child theme of Boost provided by Moodle an Hochschulen e.V.


Work in progress
----------------

This theme is still work in progress and requirements are still discussed.
Please join the discussion on https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/issues.


Requirements
------------

This theme requires Moodle 4.0+


Motivation for this theme
-------------------------

The Boost theme in Moodle core is rather limited in terms of functionality and configurability. We implemented this Boost child theme to accommodate several enhancement needs while keeping the functionality from Boost from Moodle core as much as possible as well.


Installation
------------

Install the theme like any other theme to folder
/theme/boost_union

See http://docs.moodle.org/en/Installing_plugins for details on installing Moodle plugins


Usage & Settings
----------------

After installing the theme, it does not do anything to Moodle yet.

To configure the theme and its behaviour, please visit:
Site administration -> Appearance -> Themes -> Boost Union.

There, you find multiple settings tabs:

### Tab "General settings"

In this tab there are the following settings:

#### Theme presets

##### Theme preset

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

##### Additional theme preset files

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

### Tab "Advanced settings"

In this tab there are the following settings:

#### Raw SCSS

##### Raw initial SCSS

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

##### Raw SCSS

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

### Tab "Page"

In this tab there are the following settings:

#### Navigation

##### Back to top button

With this setting a back to top button will appear in the bottom right corner of the page as soon as the user scrolls down the page. A button like this existed already on Boost in Moodle Core until Moodle 3.11, but was removed in 4.0. With Boost Union, you can bring it back.

### Tab "Branding"

In this tab there are the following settings:

#### Background images

##### Background image

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

##### Login background image

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

#### Brand colors

##### Brand color

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

### Tab "Blocks"

In this tab there are the following settings:

#### General blocks

##### Unneeded blocks

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

### Tab "Courses"

In this tab there are the following settings:

#### Course related hints

##### Show hint for switched role

With this setting a hint will appear in the course header if the user has switched the role in the course.

##### Show hint in hidden courses

With this setting a hint will appear in the course header as long as the visibility of the course is hidden.

##### Show hint for guest access

With this setting a hint will appear in the course header when a user is accessing it with the guest access feature.

##### Show hint for self enrolment without enrolment key

With this setting a hint will appear in the course header if the course is visible and an enrolment without enrolment key is currently possible.

### Tab "Footer"

In this tab there are the following settings:

#### Footnote

##### Footnote

Whatever you add to this textarea will be displayed at the end of a page, in the footer. Refer to the setting description on the settings page for further instructions.


Capabilities
------------

This plugin also introduces these additional capabilities:

### theme/boost_union:viewhintcourseselfenrol

This capability is used to control who is able to see a hint for unrestricted self enrolment in a visible course (if this feature was enabled in the theme settings). By default, it is assigned to teachers, non-editing teachers and managers.

### theme/boost_union:viewhintinhiddencourse

This capability is used to control who is able to see a hint in a hidden course (if this feature was enabled in the theme settings). By default, it is assigned to teachers, non-editing teachers and managers.


How this theme works
--------------------

This Boost child theme is implemented with minimal code duplication in mind. It inherits / requires as much code as possible from theme_boost and only implements the extended or modified functionalities.


Plugin repositories
-------------------

This plugin is published and regularly updated in the Moodle plugins repository:
http://moodle.org/plugins/view/theme_boost_union

The latest development version can be found on Github:
https://github.com/moodle-an-hochschulen/moodle-theme_boost_union


Bug and problem reports / Support requests
------------------------------------------

This plugin is carefully developed and thoroughly tested, but bugs and problems can always appear.

Please report bugs and problems on Github:
https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/issues

We will do our best to solve your problems, but please note that due to limited resources we can't always provide per-case support.


Feature proposals
-----------------

Due to limited resources, the functionality of this plugin is primarily implemented for our own local needs and published as-is to the community. We are aware that members of the community will have other needs and would love to see them solved by this plugin.

Please issue feature proposals on Github:
https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/issues

Please create pull requests on Github:
https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/pulls

We are always interested to read about your feature proposals or even get a pull request from you, but please accept that we can handle your issues only as feature _proposals_ and not as feature _requests_.


Moodle release support
----------------------

Due to limited resources, this plugin is only maintained for the most recent major release of Moodle as well as the most recent LTS release of Moodle. Bugfixes are backported to the LTS release. However, new features and improvements are not necessarily backported to the LTS release.

Apart from these maintained releases, previous versions of this plugin which work in legacy major releases of Moodle are still available as-is without any further updates in the Moodle Plugins repository.

There may be several weeks after a new major release of Moodle has been published until we can do a compatibility check and fix problems if necessary. If you encounter problems with a new major release of Moodle - or can confirm that this plugin still works with a new major release - please let us know on Github.

If you are running a legacy version of Moodle, but want or need to run the latest version of this plugin, you can get the latest version of the plugin, remove the line starting with $plugin->requires from version.php and use this latest plugin version then on your legacy Moodle. However, please note that you will run this setup completely at your own risk. We can't support this approach in any way and there is an undeniable risk for erratic behavior.


Translating this theme
----------------------

This Moodle plugin is shipped with an english language pack only. All translations into other languages must be managed through AMOS (https://lang.moodle.org) by what they will become part of Moodle's official language pack.

As the plugin creator, we manage the translation into german for our own local needs on AMOS. Please contribute your translation into all other languages in AMOS where they will be reviewed by the official language pack maintainers for Moodle.


Right-to-left support
---------------------

This plugin has not been tested with Moodle's support for right-to-left (RTL) languages.
If you want to use this plugin with a RTL language and it doesn't work as-is, you are free to send us a pull request on Github with modifications.


Maintainers
-----------

The plugin is maintained by\
Moodle an Hochschulen e.V.


Copyright
---------

The copyright of this plugin is held by\
Moodle an Hochschulen e.V.

Individual copyrights of individual developers are tracked in PHPDoc comments and Git commits.


Credits
-------

This theme is a successor of and heavily inspired by the former theme theme_boost_campus by Kathrin Osswald and Alexander Bias from Ulm University which was maintained until Moodle 3.11 on https://github.com/moodle-an-hochschulen/moodle-theme_boost_campus.
