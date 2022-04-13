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

### 3. Tab "Info Banner Settings"

#### Perpetual information banner

##### Enable perpetual info banner

With this checkbox you can decide if the perpetual information banner should be shown or hidden on the selected pages.

##### Perpetual information banner content (dependent on setting "Enable perpetual info banner")

Enter your information which should be shown within the banner here.

##### Page layouts to display the info banner on (dependent on setting "Enable perpetual info banner")

With this setting you can select the pages on which the perpetual information banner should be displayed.

##### Bootstrap css class for the perpetual info banner (dependent on setting "Enable perpetual info banner")

With this setting you can select the Bootstrap style with which the perpetual information banner should be displayed.

##### Perpetual info banner dismissible (dependent on setting "Enable perpetual info banner")

With this checkbox you can make the banner dismissible permanently. If the user clicks on the x-button a confirmation dialogue will appear and only after the user confirmed this dialogue the banner will be 
hidden for this user permanently.

Please note:

This setting has no effect for the banners shown on the login page. Because banners on the login page cannot be clicked away permanently, we do not offer the possibility to click the banner away at all on the 
login page.

##### Confirmation dialogue (dependent on setting "Perpetual info banner dismissible")

When you enable this setting you can show a confirmation dialogue to a user when he is dismissing the info banner.

The text is saved in the string with the name "closingperpetualinfobanner":
```
Are you sure you want to dismiss this information? Once done it will not occur again!
```
You can override this within your language customization if you need some other text in this dialogue.

##### Reset visibility for perpetual info banner (dependent on setting "Perpetual info banner dismissible")

By enabling this checkbox, the visibility of the individually dismissed perpetual info banners will be set to visible again. You can use this setting if you made important content changes and want to show the 
info to all users again.

Please note:
After saving this option, the database operations for resetting the visibility will be triggered and this checkbox will be unticked again. The next enabling and saving of this feature will trigger the 
database operations for resetting the visibility again.

#### Time controlled information banner

##### Enable time controlled info banner

With this checkbox you can decide if the time controlled information banner should be shown or hidden on the selected pages.

##### Time controlled information banner content (dependent on setting "Enable time controlled info banner")

Enter your information which should be shown within the time controlled banner here.

##### Page layouts to display the info banner on (dependent on setting "Enable time controlled info banner")

With this setting you can select the pages on which the time controlled information banner should be displayed.
If both info banners are active on a selected layout, the time controlled info banner will always appear above the perpetual info banner!

##### Bootstrap css class for the time controlled info banner (dependent on setting "Enable time controlled info banner")

With this setting you can select the Bootstrap style with which the time controlled information banner should be displayed.

##### Start time for the time controlled info banner (dependent on setting "Enable time controlled info banner")

With this setting you can define when the time controlled information banner should be displayed on the selected pages.
Please enter a valid in this format: YYYY-MM-DD HH:MM:SS. For example: "2020-01-01 08:00:00". The time zone will be the time zone you have defined in the setting "Default timezone".
If you leave this setting empty but entered a date in the for the end, it is the same as if you entered a date far in the past.

##### End time for the time controlled info banner (dependent on setting "Enable time controlled info banner")

With this setting you can define when the time controlled information banner should be hidden on the selected pages.
Please enter a valid date in this format: YYYY-MM-DD HH:MM:SS. For example: "2020-01-07 08:00:00. The time zone will be the time zone you have defined in the setting "Default timezone".
If you leave this setting empty but entered a date in the for the start, the banner won't hide after the starting time has been reached.


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
