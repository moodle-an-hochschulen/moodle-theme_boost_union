moodle-theme_boost_union
========================

[![Moodle Plugin CI](https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/workflows/Moodle%20Plugin%20CI/badge.svg?branch=MOODLE_405_STABLE)](https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/actions?query=workflow%3A%22Moodle+Plugin+CI%22+branch%3AMOODLE_405_STABLE)

Theme Boost Union is an enhanced child theme of Boost which is intended, on the one hand, to make Boost simply more configurable and, on the other hand, to provide helpful additional features for the daily Moodle operation of admins, teachers and students.


Requirements
------------

This theme requires Moodle 4.5+


Motivation for this theme
-------------------------

The Boost theme in Moodle core is not really configurable, many things are hardcoded and can only be changed with tricks or with core hacks. This theme intends to provider simple settings for admins to let them configure important settings easily without thinking about the inner workings of the theme.

On the other hand, many Moodle installations share the same basic functional needs like the possibility to add an imprint page or a footnote. This theme intends to provide these basic features without needing to fiddle with other plugins.

One highlight is the main design principle of Boost Union: As soon as it is activated on a Moodle site, it does not change anything yet and simply behaves as Boost from Moodle core does. The admin can enable and configure only the theme features he needs and does not need to care about side effects from other, disabled theme features.

As a side note, it is quite easy to create a grandchild theme of Boost Union. See the 'Grandchild theme support' section below for details.


Installation
------------

Install the theme like any other theme to folder
/theme/boost_union

See http://docs.moodle.org/en/Installing_plugins for details on installing Moodle plugins


Usage & Settings
----------------

After installing the theme, it does not do anything to Moodle yet.

To configure the theme and its behaviour, please visit:
Site administration -> Appearance -> Boost Union.

There, you find multiple setting pages:

### Settings page "Look"

#### Tab "General settings"

In this tab there are the following settings:

##### Theme presets

Theme presets can be used to dramatically alter the appearance of the theme. Boost Union does not re-implement the theme preset setting. If you want to use theme presets, please set them directly in Boost. Boost Union will inherit and use the configured preset.

#### Tab "SCSS"

In this tab there are the following settings:

##### Raw SCSS

###### Raw initial SCSS

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

###### Raw SCSS

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

##### External SCSS

In addition to the raw SCSS settings above, Boost Union can load SCSS from an external source. It is included before the SCSS code which is defined above which means that you can manage a centralized external SCSS codebase and can still amend it with local SCSS additions.

#### Tab "Page"

In this tab there are the following settings:

##### Page width

###### Course content max width

With this setting, you can override Moodle's course content width without manual SCSS modifications.

###### Medium content max width

With this setting, you can override Moodle's default medium width without manual SCSS modifications.

##### Drawer width

###### Course index drawer width

With this setting, you can override Moodle's course index drawer width without manual SCSS modifications.

###### Block drawer width

With this setting, you can override Moodle's block drawer width without manual SCSS modifications.

#### Tab "Site Branding"

In this tab there are the following settings:

##### Logos

###### Logo

Here, you can upload a full logo to be used as decoration. This image is especially used on the login page. This image can be quite high resolution because it will be scaled down for use.

###### Compact logo

Here, you can upload a compact version of the same logo as above, such as an emblem, shield or icon. This image is especially used in the navigation bar at the top of each Moodle page. The image should be clear even at small sizes.

##### Favicon

###### Favicon

Here, you can upload a custom image that the browser will show as the favicon of your Moodle website. If no custom favicon is uploaded, a standard Moodle favicon will be used.

##### General background images

###### Background image

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme
Please note: This will not interfere with the setting "theme_boost_union | loginbackgroundimage" which means that the pictures uploaded here will be shown on all pages except the login page.

###### Background image position

With this setting, you control the positioning of the background image within the browser window. The first value is the horizontal position, the second value is the vertical position.

##### Brand colors

###### Brand color

This setting is already available in the Moodle core theme Boost. For more information how to use it, please have a look at the official Moodle documentation: http://docs.moodle.org/en/Boost_theme

##### Bootstrap colors

With these settings, you can overwrite the Bootstrap colors which are used within the Moodle GUI.

##### Navbar

With this setting, you can change the navbar color from the default light navbar to a dark one or a colored one.

#### Tab "Activity Branding"

In this tab there are the following settings:

##### Activity icon colors

With these settings, you can overwrite the activity icon colors which are used within courses.

##### Activity icon purposes

With these settings, you can override the activity icon background color which is defined by the activity's purpose (and which is a hardcoded plugin feature in each activity).

##### Activity icons

###### Custom icons for activities and resources

With this setting, you can modify the icons for activities and resources which are used by Moodle on the course pages and in the activity chooser. You can upload custom icons for all or only some activity modules installed in this Moodle instance.

#### Tab "Login page"

##### Login page background images

###### Login page background images

This setting is already available in the Moodle core theme Boost.
However, in Boost Union you can not only add one but up to 25 files as a background image for the login page. One of these images will be picked randomly and shown when the user visits the login page.

###### Login page background image position

With this setting, you control the positioning of the login page background image within the browser window. The first value is the horizontal position, the second value is the vertical position.

###### Display text for login background images

With this optional setting you can add text, e.g. a copyright notice to your uploaded login background images.
Each line consists of the file identifier (the file name) and the text that should be displayed, separated by a pipe character. Each declaration needs to be written in a new line.

For example:
``background-image-1.jpg|Copyright: CC0|dark``

As text color, you can use the values "dark" or "light".

You can declare texts for an arbitrary amount of your uploaded login background images. The texts will be added only to those images that match their filename with the identifier declared in this setting.

##### Login form

###### Login form position

With this setting, you can optimize the login form to fit to a greater variety of background images. By default, the login form is displayed centered on the login page. Alternatively, you can move it to the left or to the right of the login page to let other parts of the background image shine through. Of course, you can also change this setting if no background images are uploaded at all.

###### Login form transparency

With this setting, you can make the login form slightly transparent to let the background image shine through even more.

##### Login providers

###### Local login

With this setting, you control if the local login form is shown on the login page or not. By default, the local login form is shown and users an login into the site as normal. If you disable this setting, the local login form is hidden. This allows you to just provide login buttons for external identity providers like OAuth2 or OIDC.

Please note: As soon as you hide the local login form, you risk that admins cannot log in anymore with a local account if there is a problem with the external identity provider. To allow local logins anyway in such cases, a side entrance local login page is provided on /theme/boost_union/locallogin.php. On this side entrance local login page, all of Moodle's login security measures apply as well.

###### Local login intro

With this setting, you control if a 'Login with your Moodle account' intro is shown above the local login form or not. By default, the intro is not shown. But if you enable it, this intro may help users to understand which credentials to use in the local login form, especially if you provide more than one login method or if you have changed the order of the login methods.

###### IDP login intro

With this setting, you control if the 'Log in using your account on' intro is shown above the IDP login buttons or not. By default, the intro is shown and users will be quickly informed what the IDP buttons are about. If you disable this setting, the IDP intro is hidden. This allows you to provide a clean user login interface if you just use external identity providers like OAuth2 or OIDC.

##### Login order

With these settings, you control the order of the login methods in the login form. The presented order will be defined from lowest to highest ordinal number, skipping all login methods and login form elements which are disabled in Moodle.

#### Tab "Dashboard / My courses"

##### Course overview block

###### Show course images

With this setting, you can control whether the course image is visible inside the course overview block or not. It is possible to choose a different setting for Card view, Summary view, and List view. 

###### Show course completion progress

With this setting, you can control whether the course completion progress is visible inside the course overview block or not.

#### Tab "Blocks"

##### Timeline block

###### Tint timeline activity icons

With this setting, you can tint the activity icons in the timeline block based on the activity purposes. By default, Moodle core displays them just as black icons.

##### Upcoming events block

###### Tint upcoming events activity icons

With this setting, you can tint the activity icons in the upcoming events block based on the activity purposes. By default, Moodle core displays them just as black icons.

##### Recently accessed items block

###### Tint recently accessed items activity icons

With this setting, you can tint the activity icons in the recently accessed items block based on the activity purposes. By default, Moodle core displays them just as black icons.

##### Activities block

###### Tint activities activity icons

With this setting, you can tint the activity icons in the activities block based on the activity purposes. By default, Moodle core displays them just as black icons.

#### Tab "Course"

##### Course Header

###### Display the course image in the course header

When enabled, the course image (which can be uploaded in a course's course settings) is displayed in the header of a course. The course images are shown there in addition to the 'My courses' page where they are always shown.

###### Fallback course header image

If you upload an image in this setting, it is used as fallback image and is displayed in the course header if no course image is uploaded in a particular course's course settings. If you do not upload an image here, a course header image is only shown in a particular course if a course image is uploaded in this particular course's course settings.

###### Course header image layout

With this setting, you control the layout of the course header image and the course title.

###### Course header image height

With this setting, you control the height of the presented course header image.

###### Course header image position

With this setting, you control the positioning of the course header image within the course header container. The first value is the horizontal position, the second value is the vertical position.

##### Course index

###### Display activity icon in course index

When enabled, the corresponding activity type icon is displayed in front of the index row with the title of the activity. As soon as the icon is displayed at the start of the line, you will get a second setting to decide where to show the activity completion indication instead.

###### Position of activity completion indication.

Choose the position where the completion indication is displayed.

#### Tab "E-Mail branding"

In this tab, you find a feature which you can use to apply branding to all E-Mails which Moodle is sending out.

Please note: This is an advanced functionality which uses some workarounds to provide E-Mail branding options. Please follow the instructions closely.

#### Tab "Resources"

##### Additional resources

With this setting you can upload additional resources to the theme. The advantage of uploading files to this file area is that those files can be delivered without a check if the user is logged in. This is also why you should only add files that are uncritical and everyone should be allowed to access and don't need be protected with a valid login. As soon as you have uploaded at least one file to this filearea and have stored the settings, a list will appear underneath which will give you the URL which you can use to reference a particular file.

##### Custom fonts

With this setting you can upload custom fonts to the theme. The advantage of uploading fonts to this file area is that those fonts can be delivered without a check if the user is logged in and can be used as locally installed fonts everywhere on the site. As soon as you have uploaded at least one font to this filearea and have stored the settings, a list will appear underneath which will give you CSS code snippets which you can use as a boilerplate to reference particular fonts in your custom SCSS.

#### Tab "H5P"

##### Raw CSS for H5P

###### Raw CSS for H5P

Use this field to provide CSS code which will be applied to the presentation of H5P content by mod_h5p and mod_hvp. Please inspect the H5P content types to find the necessary CSS selectors.

##### Content width

###### H5P content bank max width

With this setting, you can override Moodle's H5P content bank width without manual SCSS modifications.

#### Tab "Mobile"

##### Mobile app

###### Additional CSS for Mobile app

With this setting, you can write custom CSS code to customise your mobile app interface. The CSS code will be only added to the Mobile app depiction of this Moodle instance and will not be shown in the webbrowser version.

##### Mobile appearance

###### Touch Icon Files for iOS

Within this setting, you can upload files which are used as homescreen icon as soon as the Moodle site is added to the iOS homescreen as bookmark.

### Settings page "Feel"

#### Tab "Navigation"

In this tab there are the following settings:

##### Primary navigation

###### Hide nodes in primary navigation

With this setting, you can hide one or multiple nodes from the primary navigation.

###### Alternative logo link URL

With this setting, you can set an alternative link URL which will be used as link on the logo in the navigation bar. You can use this setting to, for example, link to your organization's website instead of the Moodle frontpage to maintain a homogeneous navigation bar throughout all of your organization's systems.

##### User menu

###### Show full name in the user menu

With this setting, you can show the logged-in user's full name at the top of the user menu. This can be especially helpful for exam situations where teachers have to confirm that the user is logged in with his own account, but it might also be helpful for the user himself. In contrast to the Classic theme which shows the user's full name in the navbar near the avatar, this approach here does not claim any additional rare space in the navbar.

###### Add preferred language link to language menu

With this setting, you can add a 'Set preferred language' setting to the language menu within the user menu. Understandably, this setting is only processed if the language menu is enabled at all.

##### Navbar

###### Show starred courses popover in the navbar

With this setting, you can show a popover menu with links to starred courses next to the messages and notifications menus.

##### Breadcrumbs

###### Display the category breadcrumbs in the course header

By default, the course category breadcrumbs are not shown on course pages in the course header. With this setting, you can show the course category breadcrumbs in the course header above the course name.

##### Navigation

###### Back to top button

With this setting a back to top button will appear in the bottom right corner of the page as soon as the user scrolls down the page. A button like this existed already on Boost in Moodle Core until Moodle 3.11, but was removed in 4.0. With Boost Union, you can bring it back.

###### Scroll-spy

With this setting, upon toggling edit mode on and off, the scroll position at where the user was when performing the toggle is preserved.

###### Activity navigation elements

With this setting the elements to jump to the previous and next activity/resource as well as the pull down menu to jump to a distinct activity/resource become displayed. UI elements like this existed already on Boost in Moodle Core until Moodle 3.11, but were removed in 4.0. With Boost Union, you can bring them back.

###### Show navigation on policy overview page

By default, the policy overview page (provided by tool_policy) does not show a navigation menu or footer. With this setting, you can show the primary navigation and footer on that page.

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

###### Block region width for 'Footer' region

With this setting, you can set the width of the 'Footer' block region.

###### Outside regions horizontal placement

With this setting, you can control if, on larger screens, the 'Outside (left)' and 'Outside (right)' block regions should be placed near the main content area or rather near the window edges.

##### Site home right-hand block drawer

###### Show right-hand block drawer of site home on visit

With this setting, the right-hand block drawer of site home will be displayed in its expanded state by default. This only applies to users who are not logged in and does not overwrite the toggle state of each individual user.

###### Show right-hand block drawer of site home on first login

With this setting, the right-hand block drawer of site home will be displayed in its expanded state by default. This only applies to users who log in for the very first time and does not overwrite the toggle state of each individual user.

###### Show right-hand block drawer of site home on guest login

With this setting, the right-hand block drawer of site home will be displayed in its expanded state by default. This only applies to users who log in as a guest.

#### Tab "Links"

In this tab there are the following settings:

##### Special links markup

###### Mark external links

Adds an "external link" icon after external links (which lead the user to a target outside Moodle).

###### Mark external links scope

With this setting, you control the scope where Boost Union should mark external links. By default, Boost Union marks external links on the whole Moodle page and does its best to cover some edge-cases where adding the external link icon does not make much sense. However, you can also limit the scope to better avoid edge-cases.

###### Mark mailto links

Adds an "envelope" icon in front of mailto links.

###### Mark mailto links scope

With this setting, you control the scope where Boost Union should mark mailto links. By default, Boost Union marks mailto links on the whole Moodle page. However, you can also limit the scope to avoid edge-cases.

###### Mark broken links

Adds a "broken chain" icon in front of broken links (which lead to uploaded draft files which have not been properly processed) and marks the link in the bootstrap color for "danger".

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

##### Footer

###### Enable footer

With this setting, you can control whether to show or to suppress the footer button at the bottom of the page.

###### Suppress icons in front of the footer links

With this setting, you can entirely suppress the icons in front of the footer links.

###### Suppress ... link

With these settings, you can entirely suppress particular links in the footer.

###### Suppress footer output by plugin ...

With this setting, you can entirely suppress the footer output by particular plugins.

#### Tab "Static pages"

In this tab there are the following settings:

##### About us

With these settings, you can add rich text content which will be shown on an about us page.

##### Offers

With these settings, you can add rich text content which will be shown on an offers page.

##### Imprint

With these settings, you can add rich text content which will be shown on the imprint page.

##### Contact

With these settings, you can add rich text content which will be shown on a contact page (which is not the same as the built-in Moodle 'Contact site support' page).

##### Help

With these settings, you can add rich text content which will be shown on a help page.

##### Maintenance

With these settings, you can add rich text content which will be shown on a maintenance information page (which is not the same as the built-in Moodle maintenance page).

##### Generic page 1

With these settings, you can add rich text content which will be shown on a generic page 1.

##### Generic page 2

With these settings, you can add rich text content which will be shown on a generic page 2.

##### Generic page 3

With these settings, you can add rich text content which will be shown on a generic page 3.

#### Tab "Accessibility"

In this tab there are the following settings:

#### Declaration of accessibility

With these settings, you can add rich text content which will be shown on a declaration of accessibility page.

#### Accessibility support page

With these settings, you can enable the accessibility support page which provides a contact form for accessibility issues.

#### Tab "Information banners"

In this tab, you can enable and configure multiple information banners to be shown on selected pages.

#### Tab "Advertisement tiles"

In this tab, you can enable and configure multiple advertisement tiles to be shown on site home.

#### Tab "Slider"

In this tab, you can enable and configure multiple slides to be shown on site home.

### Settings page "Functionality"

#### Tab "Courses"

In this tab there are the following settings:

##### Course related hints

###### Show hint for switched role

With this setting a hint will appear in the course header if the user has switched the role in the course.

###### Show hint for forum notifications in hidden courses

With this setting a hint will not only appear in the course header but also in forums as long as the visibility of the course is hidden.

###### Show hint in hidden courses

With this setting a hint will appear in the course header as long as the visibility of the course is hidden.

###### Show hint for guest access

With this setting a hint will appear in the course header when a user is accessing it with the guest access feature.

###### Show hint for self enrolment without enrolment key

With this setting a hint will appear in the course header if the course is visible and an enrolment without enrolment key is currently possible.

#### Tab "Administration"

In this tab there are the following settings:

##### Course management

###### Show view course icon

By default, on the course management page, Moodle requires you to either open the course details or to pass through the course settings before you can click an additional UI element to view the course. By enabling this setting, you can add a 'View course' icon directly to the category listing on the course management page.

### Settings page "Flavours"

Boost Union's flavours offer a possibility to override particular Moodle look & feel settings in particular contexts. On this page, you can create and manage flavours.

### Settings page "Smart menus"

Smart menus allow site administrators to create customizable menus that can be placed in different locations on the site, such as the site main menu, bottom mobile menu, and user menu. The menus can be configured to display different types of content, including links to other pages or resources, category links, or user profile links. On this page, you can create and manage smart menus.


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


Scheduled Tasks
---------------

This plugin also introduces these additional scheduled tasks:

### \theme_boost_union\task\purge_cache

This scheduled task can be used to purge the theme cache periodically, for example every night. It is especially there to fetch external SCSS code which might have been updated since the last purging of the theme cache.\
By default, the task is disabled.


How this theme works
--------------------

This Boost child theme is implemented with minimal code duplication in mind. It inherits / requires as much code as possible from theme_boost and only implements the extended or modified functionalities.


Goodies for designers
---------------------

In addition to our mission to provide admin settings for each and every feature of this theme, designers may want to use features of this theme within (S)CSS code directly. As designer, you should know these possibilities:

* Mark external and mailto links (manually):
  As an alternative to the markexternallinks and markmailtolinks settings which automatically mark these kind of links, you can also add the .externallink and .mailtolink class to a ```<a>``` HTML tag to manually mark any link as an external / as a mailto link.
* Themerev as SCSS variable:
  During a custom SCSS design project, you might come into the situation that you have to link to an uploaded image or other asset which is served by Moodle's pluginfile.php script. Unfortunately, these URLs contain a theme revision parameter. To be able to use these URLs properly in custom SCSS and to avoid breaking Moodle's caching features, Boost Union provides the $themerev SCSS variable to be used in your custom SCSS.


Exceptions to our main design principle
---------------------------------------

As you have read in the introduction, the main design principle of Boost Union is not to change anything in the GUI until Boost Union is set as active theme and a particular feature is enabled in the theme settings. However, due to the way how Moodle core and Boost in Moodle core is built, this main design principle sometimes could not be fully satisfied:

* Footer popover:
  As soon as you click the footer button (questionmark icon) in the bottom right corner of the screen, a popover with several links appears. However, the content of this link list is far from being well-structured and looks more like a garage sale. When implementing the settings to individually suppress each of these popover links, we had to make some code re-arrangements which result in the fact that the popover links are slightly more well-structured even if you do not enable any setting in Boost Union.
* Suppress footer outputs by plugin / core component:
  Due to the way how the settings `theme_boost_union | footersuppressstandardfooter_*` had to be built, it was not possible to quickly and reliably detect if Boost Union (or a Boost Union child theme) is the active theme. Thus, these settings are also applied if another theme than Boost Union is active. Please make sure to disable these settings if Boost Union is installed but should not be used.


Companion plugin local_navbarplus
---------------------------------

With the footersuppressusertour setting, you can disable the possibility to reset a user tour in the footer popover. If you have enabled this setting, you might want to have a look at our plugin local_navbarplus as a companion plugin which allows you, among other things, to add a "Reset user tour" link to the navigation bar instead. local_navbarplus is published on https://moodle.org/plugins/local_navbarplus and on https://github.com/moodle-an-hochschulen/moodle-local_navbarplus.


Interference with forced settings in config.php
-----------------------------------------------

Due to the way how some Boost Union features had to be built, you have to be aware of the following interferences if you force settings in config.php:

* $CFG->hooks_callback_overrides:
  With this setting, you can override hook definitions in config.php - see https://moodledev.io/docs/4.4/apis/core/hooks#hooks-overview-page.
  However, if you use the `theme_boost_union | footersuppressstandardfooter_*` settings, this forced setting will be set as well during each page load.
  Using the Boost Union settings and overriding hooks manually in config.php at the same time should work, but is not officially supported and tested by Boost Union.


Support for other companion plugins
-----------------------------------

This theme ships with some additions for companion plugins:

* block_dash / local_dash:
  * Style improvements for Dash dashboards in combination with Boost Union block regions
* local_learningtools:
  * Style improvements for the learning tools button in combination with the Boost Union bottom navigation
* local_och5p / local_och5pcore:
  * Renderer additions to add necessary additional CSS and JS files.


Grandchild theme support
------------------------

It is quite easy to create a grandchild theme of Boost Union. That way, you can benefit from all the / only the Boost Union features you need, but you can also add additional local features or settings to your local grandchild theme at the same time.

If you plan to build a grandchild theme of Boost Union, we have prepared a 'Boost Union Child' boilerplate for you which can help you to do the first steps.

Boost Union Child can be found on Github:
https://github.com/moodle-an-hochschulen/moodle-theme_boost_union_child

While Boost Union Child will surely help you to realize all your local Boost Union dreams, please do yourself and the whole community a favour and verify that your planned features are indeed not interesting as a pull request or feature request for the whole Boost Union community and could be contributed to Boost Union directly instead.


SCSS stack order
----------------

Within Boost Union, you have multiple possibilities to add your own SCSS code to the Moodle page. And many of the Boost Union settings add SCSS code as well to realize the particular setting's goal. However, as you know, in SCSS the order of the instructions is key.

The following list should give you an insight in which order all the SCSS code is added to the CSS stack which is shipped to the browser in the end.
To fully understand this list, you have to be aware of two terms in Moodle theming:

* _Pre SCSS_ or _Raw Initial SCSS_:\
  This SCSS code is used only to initialize SCSS variables and not to write real SCSS code directly.
* _Post SCSS_ or _Raw SCSS_:\
  This SCSS code is the real SCSS code which is compiled to CSS for the browser and which might consume the SCSS variables which have been set in the Pre SCSS.

Having said that, here's the order how all the SCSS code is added to the SCSS stack:

1. All plugins' `styles.css` files:\
   Each Moodle plugin can ship with a `styles.css` file which contains CSS code (not SCSS code!) for the plugin. These files are added at the very beginning in the order of the plugin names and types.

2. `theme_boost` > `get_pre_scss()`:
   * Adds the Boost Union Pre SCSS from the theme settings\
     (which is set on `/admin/settings.php?section=theme_boost_union_look#theme_boost_union_look_scss`).\
     Note: In fact, this function adds the _active theme's_ Pre SCSS which becomes important if you use a Boost Union Child theme.

3. `theme_boost_union` > `get_pre_scss()`:
   * Adds the Boost Union Pre SCSS from disk\
     (which is located on `/theme/boost_union/scss/boost_union/pre.scss` and which is empty currently)
   * Sets several SCSS variables based on Boost Union or Boost Union flavour settings
   * Adds the Boost Union external Pre SCSS\
     (which is set on `/admin/settings.php?section=theme_boost_union_look#theme_boost_union_look_scss`)
   * Adds the Boost Union flavour Pre SCSS\
     (which is set within the active flavour on `/theme/boost_union/flavours/overview.php`)

4. `theme_boost_union` > `get_main_scss()`:
   * Calls the `theme_boost` > `get_main_scss()` function
     * Adds the Boost Core Preset\
       (which is set on `/admin/settings.php?section=themesettingboost` and defaults to the `/theme/boost/scss/preset/default.scss` file).
       With this preset, the FontAwesome library, the Bootstrap library and all the Moodle core stylings are added which means that this preset is the place where all the Moodle core style is added.
   * Adds the Boost Union Post SCSS from disk\
     (which is located on `/theme/boost_union/scss/boost_union/post.scss`)
     This file holds all the Boost Union specific SCSS code which can be added to the stack without being dependent on specific configurations like configured colors or sizes.
   * Add the Boost Union external SCSS\
     (which is set on `/admin/settings.php?section=theme_boost_union_look#theme_boost_union_look_scss`)

5. `theme_boost` > `get_extra_scss()`:
   * Adds the Boost Union Post SCSS from the theme settings\
     (which is set on `/admin/settings.php?section=theme_boost_union_look#theme_boost_union_look_scss`).\
     Note: In fact, this function adds the _active theme's_ Post SCSS which becomes important if you use a Boost Union Child theme.
   * Adds the page background image and login page background image

6. `theme_boost_union` > `get_extra_scss()`:
   * Overrides / enhances the background images which have been set before
   * Adds the Boost Union flavour Post SCSS\
     (which is set within the active flavour on `/theme/boost_union/flavours/overview.php`)
   * Adds the Boost Union features' SCSS.
     This is the Boost Union specific SCSS code which has to be added to the stack based on specific configurations, for example for changing the activity icon purposes or for changing the login form order.


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

Support thread:
https://s.gwdg.de/bxqZti

We kindly invite you to use this support thread in case of any questions you might have. We are a team of many (sometimes power) users of Boost Union and will try to answer or collectively according to our measures. If any other users know answers or are quicker, don't hesitate to answer. We will do our best to solve your problems, but please note that due to limited resources we can't always provide per-case support.


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
ssystems GmbH

together with\
bdecent GmbH

and\
lern.link GmbH


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

* Academic Moodle Cooperation (AMC): Ideating, Code
* Baden-Württemberg Cooperative State University (DHBW), Katja Neubehler: Code
* bdecent GmbH, Stefan Scholz: Code, Ideating, Funding
* Bern University of Applied Sciences (BFH), Luca Bösch: Code, Peer Review, Ideating
* Carinthia University of Applied Sciences, Mario Wehr: Code
* Catalyst IT Europe, Mark Johnson: Code
* Catalyst IT Europe, Simon Thornett: Code
* ELAN e.V., Farbod Zamani: Code
* FernUniversität in Hagen, Daniel Poggenpohl: Code, Ideating
* Hochschule Hannover - University of Applied Sciences and Arts: Code, Funding, Ideating
* Käferfreie Software, Nina Herrmann: Code
* lern.link GmbH, Alexander Bias: Code, Peer Review, Ideating, Funding
* lern.link GmbH, Lukas MuLu Müller: Code
* lern.link GmbH, Beata Waloszczyk: Code
* Lutheran University of Applied Sciences Nuremberg: Funding
* Moodle.NRW / Ruhr University Bochum, Annika Lambert: Code
* Moodle.NRW / Ruhr University Bochum, Matthias Buttgereit: Code, Ideating
* Moodle.NRW / Ruhr University Bochum, Tim Trappen: Code, Ideating
* moodleSCHULE e.V., Ralf Krause: German translation and curation, Ideating
* Plakos GmbH, Waldemar Erdmann: Funding, Ideating
* Ruhr University Bochum, Melanie Treitinger: Code, Ideating
* RWTH Aachen, Amrita Deb Dutta: Code
* RWTH Aachen, Josha Bartsch: Code
* Solent University, Mark Sharp: Code
* ssystems GmbH, Alexander Bias: Code, Peer Review, Ideating, Funding
* Technische Universität Berlin, Lars Bonczek: Code
* University of Bayreuth, Nikolai Jahreis: Code
* University of Graz, André Menrath: Code
* University of Lübeck, Christian Wolters: Code, Peer Review, Ideating
* Zurich University of Applied Sciences (ZHAW): Code, Funding, Ideating

Additionally, we thank all other contributors who contributed ideas, feedback and code snippets within the Github issues and pull requests as well as all contributors who contributed additional translations in AMOS, the Moodle translation tool.
