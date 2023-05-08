moodle-theme_boost_union
========================

[![Moodle Plugin CI](https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/workflows/Moodle%20Plugin%20CI/badge.svg?branch=master)](https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/actions?query=workflow%3A%22Moodle+Plugin+CI%22+branch%3Amaster)

Theme Boost Union is an enhanced child theme of Boost which is intended, on the one hand, to make Boost simply more configurable and, on the other hand, to provide helpful additional features for the daily Moodle operation of admins, teachers and students.


Requirements
------------

This theme requires Moodle 4.0+


Motivation for this theme
-------------------------

The Boost theme in Moodle core is not really configurable, many things are hardcoded and can only be changed with tricks or with core hacks. This theme intends to provider simple settings for admins to let them configure important settings easily without thinking about the inner workings of the theme.

On the other hand, many Moodle installations share the same basic functional needs like the possibility to add an imprint page or a footnote. This theme intends to provide these basic features without needing to fiddle with other plugins.

One highlight is the main design principle of Boost Union: As soon as it is activated on a Moodle site, it does not change anything yet and simply behaves as Boost from Moodle core does. The admin can enable and configure only the theme features he needs and does not need to care about side effects from other, disabled theme features.

As a side note, it is quite easy to create a grandchild theme of Boost Union. That way, you can benefit from all the / only the Boost Union features you need, but you can also add additional local features or settings (that are not interesting as a pull request or feature request for the whole Boost Union community) to your local grandchild theme at the same time.


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

There, you find multiple setting pages:

### Settings page "Look"

#### Tab "General settings"

In this tab there are the following settings:

##### Theme presets

###### Theme preset

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

###### Additional theme preset files

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

#### Tab "SCSS"

In this tab there are the following settings:

##### Raw SCSS

###### Raw initial SCSS

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

###### Raw SCSS

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

#### Tab "Page"

In this tab there are the following settings:

##### Page width

###### Course content max width

With this setting, you can override Moodle's course content width without manual SCSS modifications.

###### Medium content max width

With this setting, you can override Moodle's default medium width without manual SCSS modifications.

#### Tab "Branding"

In this tab there are the following settings:

##### Logos

###### Logo

Here, you can upload a full logo to be used as decoration. This image is especially used on the login page. This image can be quite high resolution because it will be scaled down for use.

###### Compact logo

Here, you can upload a compact version of the same logo as above, such as an emblem, shield or icon. This image is especially used in the navigation bar at the top of each Moodle page. The image should be clear even at small sizes.

##### Favicon

###### Favicon

Here, you can upload a custom image that the browser will show as the favicon of your Moodle website. If no custom favicon is uploaded, a standard Moodle favicon will be used.

##### Background images

###### General background image

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme
Please note: This will not interfere with the setting "theme_boost_union | loginbackgroundimage" which means that the pictures uploaded here will be shown on all pages except the login page.

##### Brand colors

###### Brand color

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

##### Bootstrap colors

With these settings, you can overwrite the Bootstrap colors which are used within the Moodle GUI.

##### Activity icon colors

With these settings, you can overwrite the activity icon colors which are used within courses.

#### Tab "Login page"

##### Login page background images

###### Login page background images

This setting is already available in the Moodle core theme Boost.
However, in Boost Union you can not only add one but up to 25 files as a background image for the login page. One of these images will be picked randomly and shown when the user visits the login page.

###### Display text for login background images

With this optional setting you can add text, e.g. a copyright notice to your uploaded login background images.
Each line consists of the file identifier (the file name) and the text that should be displayed, separated by a pipe character. Each declaration needs to be written in a new line.

For example:
``background-image-1.jpg|Copyright: CC0|dark``

As text color, you can use the values "dark" or "light".

You can declare texts for an arbitrary amount of your uploaded login background images. The texts will be added only to those images that match their filename with the identifier declared in this setting.

##### Login form position

With this setting, you can optimize the login form to fit to a greater variety of background images. By default, the login form is displayed centered on the login page. Alternatively, you can move it to the left or to the right of the login page to let other parts of the background image shine through. Of course, you can also change this setting if no background images are uploaded at all.

##### Login form transparency

With this setting, you can make the login form slightly transparent to let the background image shine through even more.

#### Tab "Course"

##### Course Header

###### Display the course image in the course header

When enabled, the course image (which can be uploaded in a course's course settings) is displayed in the header of a course. The course images are shown there in addition to the 'My courses' page where they are always shown.

###### Fallback course header image

If you upload an image in this setting, it is used as fallback image and is displayed in the course header if no course image is uploaded in a particular course's course settings. If you do not upload an image here, a course header image is only shown in a particular course if a course image is uploaded in this particular course's course settings.

###### Course header image height

With this setting, you control the height of the presented course header image.

###### Course header image layout

With this setting, you control the layout of the course header image and the course title.

###### Course header image position

With this setting, you control the positioning of the course header image within the course header container. The first value is the horizontal position, the second value is the vertical position.

#### Tab "E-Mail branding"

In this tab, you find a feature which you can use to apply branding to all E-Mails which Moodle is sending out.

Please note: This is an advanced functionality which uses some workarounds to provide E-Mail branding options. Please follow the instructions closely.

#### Tab "Resources"

##### Additional resources

With this setting you can upload additional resources to the theme. The advantage of uploading files to this file area is that those files can be delivered without a check if the user is logged in. This is also why you should only add files that are uncritical and everyone should be allowed to access and don't need be protected with a valid login. As soon as you have uploaded at least one file to this filearea and have stored the settings, a list will appear underneath which will give you the URL which you can use to reference a particular file.

##### Custom fonts

With this setting you can upload custom fonts to the theme. The advantage of uploading fonts to this file area is that those fonts can be delivered without a check if the user is logged in and can be used as locally installed fonts everywhere on the site. As soon as you have uploaded at least one font to this filearea and have stored the settings, a list will appear underneath which will give you CSS code snippets which you can use as a boilerplate to reference particular fonts in your custom SCSS.

##### FontAwesome

Moodle core ships with FontAwesome 4 icons which are fine, but FontAwesome has evolved since then. If you want to use more recent FontAwesome icons, you can do this with this setting. As soon as you choose another version than FontAwesome 4, additional settings will appear where you can upload more recent FontAwesome versions.

#### Tab "H5P"

##### Raw CSS for H5P

###### Raw CSS for H5P

Use this field to provide CSS code which will be applied to the presentation of H5P content by mod_h5p and mod_hvp. Please inspect the H5P content types to find the necessary CSS selectors.

##### Content width

###### H5P content bank max width

With this setting, you can override Moodle's H5P content bank width without manual SCSS modifications.

#### Tab "Mobile app"

##### Mobile appearance

###### Additional CSS for Mobile app

With this setting, you can write custom CSS code to customise your mobile app interface. The CSS code will be only added to the Mobile app depiction of this Moodle instance and will not be shown in the webbrowser version.

### Settings page "Feel"

#### Tab "Navigation"

In this tab there are the following settings:

##### Primary navigation

###### Hide nodes in primary navigation

With this setting, you can hide one or multiple nodes from the primary navigation.

##### Breadcrumbs

###### Display the category breadcrumbs in the course header

By default, the course category breadcrumbs are not shown on course pages in the course header. With this setting, you can show the course category breadcrumbs in the course header above the course name.

##### Navigation

###### Back to top button

With this setting a back to top button will appear in the bottom right corner of the page as soon as the user scrolls down the page. A button like this existed already on Boost in Moodle Core until Moodle 3.11, but was removed in 4.0. With Boost Union, you can bring it back.

###### Scrollspy

With this setting, upon toggling edit mode on and off, the scroll position at where the user was when performing the toggle is preserved.

###### Activity navigation

With this setting the elements to jump to the previous and next activity/resource as well as the pull down menu to jump to a distinct activity/resource become displayed. UI elements like this existed already on Boost in Moodle Core until Moodle 3.11, but were removed in 4.0. With Boost Union, you can bring them back.

#### Tab "Blocks"

In this tab there are the following settings:

##### General blocks

###### Unneeded blocks

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

##### Additional block regions

Boost Union provides a large number of additional block regions which can be used to add and show blocks over the whole Moodle page:

* The Outside block regions are placed on all four sides of the Moodle page. They can be used to show blocks which accompany the shown Moodle page but do not directly belong to the main content.
* The Header block region is placed between the Outside (top) area and the main content area. It can be used to show a block as course header information.
* The Content block regions are placed directly over and under the main content in the main content area. They can be used to add blocks to the course content flow.
* The Footer block regions are placed at the bottom of the page between the Outside (bottom) area and the footnote. You have three footer regions available to build columns if necessary.
* The Off-canvas block region is somehow special as it hovers over the whole Moodle page as a drawer. The drawer is opened by the 9-dots icon at the very right side of the navigation bar. You have three off-canvas regions available to build columns if necessary.

Please note:

* By default, all additional block regions are disabled. Please enable the particular block regions on the particular page layouts according to your needs. Try to be as focused as possible – too many block regions could overwhelm end users.
* As soon as an additional block region is enabled, it is visible for all authenticated users and editable by teachers and managers (depending on the fact if the particular user is allowed to edit the particular Moodle page, of course). But there are also theme/boost_union:viewregion* and theme/boost_union:editregion* capabilities which allow you to fine-tune the usage of each block region according to your needs.
* The Outside (left), Outside (right), Content (upper), Content (lower) and Header block regions are not available for all page layouts.

##### Outside regions

Outside regions can not only be enabled with the layout settings above, their appearance can also be customized.

###### Block region width for 'Outside (left)' region

With this setting, you can set the width of the 'Outside (left)' block region which is shown on the left hand side of the main content area.

###### Block region width for 'Outside (right)' region

With this setting, you can set the width of the 'Outside (right)' block region which is shown on the right hand side of the main content area.

###### Block region width for 'Outside (top)' region

With this setting, you can set the width of the 'Outside (top)' block region which is shown at the very top of the page.

###### Block region width for 'Outside (bottom)' region

With this setting, you can set the width of the 'Outside (bottom)' block region which is shown below the main content.

###### Outside regions horizontal placement

With this setting, you can control if, on larger screens, the 'Outside (left)' and 'Outside (right)' block regions should be placed near the main content area or rather near the window edges.

##### Site home right-hand block drawer

###### Show right-hand block drawer of site home on visit

With this setting, the right-hand block drawer of site home will be displayed in its expanded state by default. This only applies to users who are not logged in and does not overwrite the toggle state of each individual user.

###### Show right-hand block drawer of site home on first login

With this setting, the right-hand block drawer of site home will be displayed in its expanded state by default. This only applies to users who log in for the very first time and does not overwrite the toggle state of each individual user.

###### Show right-hand block drawer of site home on guest login

With this setting, the right-hand block drawer of site home will be displayed in its expanded state by default. This only applies to users who log in as a guest.

#### Tab "Miscellaneous"

In this tab there are the following settings:

##### JavaScript

###### JavaScript disabled hint

With this setting, a hint will appear at the top of the Moodle page if JavaScript is not enabled. This is particularly helpful as several Moodle features do not work without JavaScript.

### Settings page "Content"

#### Tab "Footer"

In this tab there are the following settings:

##### Footnote

###### Footnote

Whatever you add to this textarea will be displayed at the end of a page, in the footer. Refer to the setting description on the settings page for further instructions.

#### Tab "Static pages"

In this tab there are the following settings:

##### Imprint

With these settings, you can add rich text content which will be shown on the imprint page.

##### Contact

With these settings, you can add rich text content which will be shown on a contact page (which is not the same as the built-in Moodle 'Contact site support' page).

##### Help

With these settings, you can add rich text content which will be shown on a help page.

##### Maintenance

With these settings, you can add rich text content which will be shown on a maintenance information page (which is not the same as the built-in Moodle maintenance page).

#### Tab "Information banners"

In this tab, you can enable and configure multiple information banners to be shown on selected pages.

#### Tab "Advertisement tiles"

In this tab, you can enable and configure multiple advertisement tiles to be shown on site home.

### Settings page "Functionality"

#### Tab "Courses"

In this tab there are the following settings:

##### Course related hints

###### Show hint for switched role

With this setting a hint will appear in the course header if the user has switched the role in the course.

###### Show hint in hidden courses

With this setting a hint will appear in the course header as long as the visibility of the course is hidden.

###### Show hint for guest access

With this setting a hint will appear in the course header when a user is accessing it with the guest access feature.

###### Show hint for self enrolment without enrolment key

With this setting a hint will appear in the course header if the course is visible and an enrolment without enrolment key is currently possible.

### Settings page "Flavours"

Boost Union's flavours offer a possibility to override particular Moodle look & feel settings in particular contexts. On this page, you can create and manage flavours.


Capabilities
------------

This plugin also introduces these additional capabilities:

### theme/boost_union:configure

This capability is used to control who is able to configure the theme as non-admin. By default, it is assigned to no role at all.

### theme/boost_union:viewhintcourseselfenrol

This capability is used to control who is able to see a hint for unrestricted self enrolment in a visible course (if this feature was enabled in the theme settings). By default, it is assigned to teachers, non-editing teachers and managers.

### theme/boost_union:viewhintinhiddencourse

This capability is used to control who is able to see a hint in a hidden course (if this feature was enabled in the theme settings). By default, it is assigned to teachers, non-editing teachers and managers.

### theme/boost_union:viewregion*

These capabilities are used to control who is allowed to see a particular block region. By default, they are assigned to all authenticated users, teachers, non-editing teachers and managers

### theme/boost_union:editregion*

These capabilities are used to control who is allowed to edit a particular block region. By default, they are assigned to teachers, non-editing teachers and managers.


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

in cooperation with\
lern.link GmbH

together with\
bdecent GmbH


Copyright
---------

The copyright of this plugin is held by\
Moodle an Hochschulen e.V.

Individual copyrights of individual developers are tracked in PHPDoc comments and Git commits.


Credits
-------

This theme is a successor of and heavily inspired by the former theme theme_boost_campus by Kathrin Osswald and Alexander Bias from Ulm University which was maintained until Moodle 3.11 on https://github.com/moodle-an-hochschulen/moodle-theme_boost_campus.


Contributors
------------

This theme is a collaboration result of multiple organisations.

Moodle an Hochschulen e.V. would like to thank these main contributors (in alphabetical order of the institutions) for their work:

* bdecent GmbH, Stefan Scholz: Code, Ideating, Funding
* Bern University of Applied Sciences (BFH), Luca Bösch: Code, Peer Review, Ideating
* FernUniversität in Hagen, Daniel Poggenpohl: Code, Ideating
* Hochschule Hannover - University of Applied Sciences and Arts: Funding, Ideating
* Käferfreie Software, Nina Herrmann: Code
* lern.link GmbH, Alexander Bias: Code, Peer Review, Ideating, Funding
* lern.link GmbH, Beata Waloszczyk: Code
* Moodle.NRW / Ruhr University Bochum, Tim Trappen: Code, Ideating
* Moodle.NRW / Ruhr University Bochum, Matthias Buttgereit: Code, Ideating
* moodleSCHULE e.V., Ralf Krause: German translation and curation, Ideating
* Ruhr University Bochum, Melanie Treitinger: Code, Ideating
* RWTH Aachen, Amrita Deb Dutta: Code
* RWTH Aachen, Josha Bartsch: Code
* Solent University, Mark Sharp: Code
* University of Graz, André Menrath: Code
* University of Lübeck, Christian Wolters: Peer Review, Ideating
* Zurich University of Applied Sciences (ZHAW): Funding, Ideating

Additionally, we thank all other contributors who contributed ideas, feedback and code snippets within the Github issues and pull requests as well as all contributors who contributed additional translations in AMOS, the Moodle translation tool.
