<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme Boost Union - Language pack
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Let codechecker ignore some sniffs for this file as it is perfectly well ordered, just not alphabetically.
// phpcs:disable moodle.Files.LangFilesOrdering.UnexpectedComment
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder

// General.
$string['pluginname'] = 'Boost Union';
$string['choosereadme'] = 'Theme Boost Union is an enhanced child theme of Boost which is intended, on the one hand, to make Boost simply more configurable and, on the other hand, to provide helpful additional features for the daily Moodle operation of admins, teachers and students. Boost Union is maintained by Moodle an Hochschulen e.V., in cooperation with ssystems GmbH, together with bdecent GmbH and lern.link GmbH';
$string['configtitle'] = 'Boost Union';
$string['githubissueslink'] = '<a href="https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/issues">Github issues</a>';

// General select options.
$string['never'] = 'Never';
$string['always'] = 'Always';
$string['nochange'] = 'No change';
$string['forguestsonly'] = 'Only for guests and non-logged-in users';

// Settings: General strings.
$string['dontchange'] = 'Do not change anything';

// Settings: General warnings.
$string['warningslashargumentsdisabled'] = 'Warning: The <a href="{$a->url}">slasharguments setting</a> is disabled in your Moodle configuration currently. However, this setting is required for the correct operation of the following Boost Union setting. Please enable slasharguments, otherwise the following Boost Union setting will not have any effect.';

// Settings: Overview page.
$string['settingsoverview'] = 'Settings overview';
$string['settingsoverview_title'] = 'Boost Union settings overview';
$string['settingsoverview_look_desc'] = 'Settings for branding your Moodle site are located here: Colors, icons, images, sizing and, of course, custom SCSS.';
$string['settingsoverview_feel_desc'] = 'Settings for the overall behaviour of your Moodle site are located here: Navigation items, navigation helpers, blocks and links.';
$string['settingsoverview_content_desc'] = 'Settings for the global content of your Moodle site are located here: Footer, static pages, info banners, advertisement tiles and sliders.';
$string['settingsoverview_functionality_desc'] = 'Settings for additional useful global or course-related functionality on your Moodle site are located here.';
$string['settingsoverview_accessibility_desc'] = 'Settings for accessibility-related functionality on your Moodle site are located here.';
$string['settingsoverview_flavours_desc'] = 'With flavours, you can diversify the look of your Moodle site between cohorts and / or course categories.';
$string['settingsoverview_smartmenus_desc'] = 'With smart menus, you can extend the navigation items of your Moodle site in the main menu and the user menus well as introduce a bottom menu or a top menu.';
$string['settingsoverview_all'] = 'All settings on one page';
$string['settingsoverview_all_desc'] = 'Here, you can open the standard Moodle category settings page for Boost Union that shows all settings on one page. But beware, it is really packed.';

// Settings: Look page.
$string['configtitlelook'] = 'Look';

// Settings: General settings tab.
// ... Section: Theme presets.
$string['presetheading'] = 'Theme presets';
$string['presetheading_desc'] = 'Theme presets can be used to dramatically alter the appearance of the theme. Boost Union does not re-implement the theme preset setting. If you want to use theme presets, please set them directly in Boost. Boost Union will inherit and use the configured preset.';
$string['presetbutton'] = 'Set theme preset in Boost';

// Settings: SCSS tab.
$string['scsstab'] = 'SCSS';
// ... Section: Raw SCSS.
$string['scssheading'] = 'Raw SCSS';

// ... Section: External SCSS.
$string['extscssheading'] = 'External SCSS';
$string['extscssheading_desc'] = 'In addition to the raw SCSS settings above, Boost Union can load SCSS from an external source. It is included before the SCSS code which is defined above which means that you can manage a centralized external SCSS codebase and can still amend it with local SCSS additions.';
$string['extscssheading_instr'] = 'Instructions:';
$string['extscssheading_drop'] = 'If Boost Union cannot fetch the external SCSS file for any reason, it will simply ignore the external SCSS file to avoid hickups with SCSS compiling and broken frontends.';
$string['extscssheading_structure'] = 'The external SCSS must be provided as plaintext file, without any headers or footers, containing just the SCSS code.';
$string['extscssheading_prepost'] = 'Just like the raw SCSS settings above, the external SCSS is split into two pieces: Pre and Post SCSS. Pre SCSS can be used for initializing SCSS variables, Post SCSS is used for your actual SCSS code.';
$string['extscssheading_sources'] = 'You can configure Boost Union to fetch the external SCSS file either from a public download URL (which will be accessed and fetched with an unauthenticated cURL request) or from a private Github repository (which will be accessed and fetched with a Github API token).';
$string['extscssheading_task'] = 'There is a <a href="{$a}">scheduled task theme_boost_union\task\purge_cache</a> which is disabled by default but which you can enable if you want Boost Union to periodically fetch and compile the external SCSS code.';
$string['invalidurl'] = 'The given URL is invalid';
// ... ... Setting: External SCSS source.
$string['extscsssource'] = 'External SCSS source';
$string['extscsssource_desc'] = 'Pick the type of source from where you want to fetch the external SCSS.';
$string['extscsssourcenone'] = 'None';
$string['extscsssourcedownload'] = 'Public download URL';
$string['extscsssourcegithub'] = 'Private Github repository';
// ... ... Setting: External Pre SCSS download URL.
$string['extscssurlpre'] = 'External Pre SCSS download URL';
$string['extscssurlpre_desc'] = 'The public download URL from where the External Pre SCSS should be fetched.';
// ... ... Setting: External Post SCSS download URL.
$string['extscssurlpost'] = 'External Post SCSS download URL';
$string['extscssurlpost_desc'] = 'The public download URL from where the external Post SCSS should be fetched.';
// ... ... Setting: External SCSS Github API token.
$string['extscssgithubtoken'] = 'External SCSS Github API token';
$string['extscssgithubtoken_desc'] = 'The Github API token which will be used to fetch the SCSS code from the given private Github repository.';
$string['extscssgithubtoken_docs'] = 'Go to <a href="https://github.com/settings/tokens">your Github token settings</a> to generate an API token and to see the official documentation.';
// ... ... Setting: External SCSS Github API user.
$string['extscssgithubuser'] = 'External SCSS Github API user';
$string['extscssgithubuser_desc'] = 'The Github API user or organization which owns the private Github repository.';
$string['extscssgithubuser_example'] = 'Example: If you can see the file in your Github account on https://github.com/moodle-an-hochschulen/moodle-theme_boost_union-extscsstest/blob/main/extscss.scss, the user will be <em>moodle-an-hochschulen</em>.';
// ... ... Setting: External SCSS Github API repository.
$string['extscssgithubrepo'] = 'External SCSS Github API repository';
$string['extscssgithubrepo_desc'] = 'The private Github repository where the SCSS files are located.';
$string['extscssgithubrepo_example'] = 'Example: If you can see the file in your Github account on https://github.com/moodle-an-hochschulen/moodle-theme_boost_union-extscsstest/blob/main/extscss.scss, the repository will be <em>moodle-theme_boost_union-extscsstest</em>.';
// ... ... Setting: External Pre SCSS Github file path.
$string['extscssgithubprefilepath'] = 'External Pre SCSS Github file path';
$string['extscssgithubprefilepath_desc'] = 'The path within the private Github repository where the Pre SCSS file is located.';
$string['extscssgithubfilepath_example'] = 'Example: If you can see the file in your Github account on https://github.com/moodle-an-hochschulen/moodle-theme_boost_union-extscsstest/blob/main/extscss.scss, the file path will be <em>/extscss.scss</em>.';
// ... ... Setting: External Post SCSS Github file path.
$string['extscssgithubpostfilepath'] = 'External Post SCSS Github file path';
$string['extscssgithubpostfilepath_desc'] = 'The path within the private Github repository where the Post SCSS file is located.';
// ... ... Setting: External SCSS validation.
$string['extscssvalidationsetting'] = 'External SCSS validation';
$string['extscssvalidationsetting_desc'] = 'If this setting is enabled, the external SCSS is validated if it can be compiled before it is added to the SCSS stack. External SCSS code which can\'t be compiled is silently ignored and not used. However, this validation is only run on the external SCSS code only, it is not run on the combined SCSS stack which would be the result of the integration of the external SCSS. This means that, as soon as you use SCSS variables from Moodle core or Bootstrap in your external SCSS, you have to disable the validation and verify yourself that the SCSS code is valid to avoid broken frontends.';

// Settings: Page tab.
$string['pagetab'] = 'Page';
// ... Section: Page width.
$string['pagewidthheading'] = 'Page width';
// ... ... Setting: Course content max width.
$string['coursecontentmaxwidthsetting'] = 'Course content max width';
$string['coursecontentmaxwidthsetting_desc'] = 'With this setting, you can override Moodle\'s course content width without manual SCSS modifications. This width is used as page width of course pages and within several activities. By default, Moodle uses a course content max width of 830px. You can enter other pixel-based values like 1200px, but you can also enter a percentage-based value like 100% or a viewport-width value like 90vw.';
// ... ... Setting: Medium content max width.
$string['mediumcontentmaxwidthsetting'] = 'Medium content max width';
$string['mediumcontentmaxwidthsetting_desc'] = 'With this setting, you can override Moodle\'s medium content width without manual SCSS modifications. This page width is used in certain activities like the database activity. By default, Moodle uses a medium content max width of 1120px. You can enter other pixel-based values like 1200px, but you can also enter a percentage-based value like 100% or a viewport-width value like 90vw.';
// ... Section: Drawer width.
$string['drawerwidthheading'] = 'Drawer width';
// ... ... Setting: Course content max width.
$string['courseindexdrawerwidthsetting'] = 'Course index drawer width';
$string['courseindexdrawerwidthsetting_desc'] = 'With this setting, you can override Moodle\'s course index drawer width without manual SCSS modifications. By default, Moodle uses a course index drawer width of 285px. You can enter other pixel-based values like 320px, but values with other units like percentage-based values or a viewport-width value won\'t work.';
// ... ... Setting: Medium content max width.
$string['blockdrawerwidthsetting'] = 'Block drawer width';
$string['blockdrawerwidthsetting_desc'] = 'With this setting, you can override Moodle\'s block drawer width without manual SCSS modifications. By default, Moodle uses a medium content max width of 315px. You can enter other pixel-based values like 400px, but values with other units like percentage-based values or a viewport-width value won\'t work.';

// Settings: Site branding tab.
$string['sitebrandingtab'] = 'Site branding';
// ... Section: Logos.
$string['logosheading'] = 'Logos';
$string['logosheading_desc'] = 'Please note: Boost Union has its own logo upload and does not use the logo from <a href="{$a}">Moodle core\'s logo setting</a>.<br />Boost Union especially allows you to upload more image formats that Moodle core allows and allows you to override the uploaded logos within its flavours.';
// ... ... Setting: Logo.
$string['logosetting'] = 'Logo';
$string['logosetting_desc'] = 'Here, you can upload a full logo to be used as decoration. This image is especially used on the login page. This image can be quite high resolution because it will be scaled down for use.';
// ... ... Setting: Compact logo.
$string['logocompactsetting'] = 'Compact logo';
$string['logocompactsetting_desc'] = 'Here, you can upload a compact version of the same logo as above, such as an emblem, shield or icon. This image is especially used in the navigation bar at the top of each Moodle page. The image should be clear even at small sizes.';
// ... Section: Favicon.
$string['faviconheading'] = 'Favicon';
$string['faviconheading_desc'] = 'Please note: Boost Union has its own favicon upload and does not use the favicon from <a href="{$a}">Moodle core\'s favicon setting</a>.<br />Boost Union especially allows you to override the uploaded favicon within its flavours.';
// ... ... Setting: Favicon
$string['faviconsetting'] = 'Favicon';
$string['faviconsetting_desc'] = 'Here, you can upload a custom image (.ico or .png format) that the browser will show as the favicon of your Moodle website. If no custom favicon is uploaded, a standard Moodle favicon will be used.';
// ... Section: Background images.
$string['backgroundimagesheading'] = 'General background images';
// ... ... Setting: Background image
$string['backgroundimagesetting'] = 'Background image';
$string['backgroundimagesetting_desc'] = 'Here, you can upload a custom image to display as a background of the site. The background image you upload here will override the background image in your theme preset files.';
// ... ... Setting: Background image position
$string['backgroundimagepositionsetting'] = 'Background image position';
$string['backgroundimagepositionsetting_desc'] = 'With this setting, you control the positioning of the background image within the browser window. The first value is the horizontal position, the second value is the vertical position.';
// ... Section: Brand colors.
$string['brandcolorsheading'] = 'Brand colors';
// ... Section: Bootstrap colors.
$string['bootstrapcolorsheading'] = 'Bootstrap colors';
// ... ... Setting: Bootstrap color for 'Success'.
$string['bootstrapcolorsuccesssetting'] = 'Bootstrap color for "Success"';
$string['bootstrapcolorsuccesssetting_desc'] = 'The Bootstrap color for "Success"';
// ... ... Setting: Bootstrap color for 'Info'.
$string['bootstrapcolorinfosetting'] = 'Bootstrap color for "Info"';
$string['bootstrapcolorinfosetting_desc'] = 'The Bootstrap color for "Info"';
// ... ... Setting: Bootstrap color for 'Warning'.
$string['bootstrapcolorwarningsetting'] = 'Bootstrap color for "Warning"';
$string['bootstrapcolorwarningsetting_desc'] = 'The Bootstrap color for "Warning"';
// ... ... Setting: Bootstrap color for 'Danger'.
$string['bootstrapcolordangersetting'] = 'Bootstrap color for "Danger"';
$string['bootstrapcolordangersetting_desc'] = 'The Bootstrap color for "Danger"';
// ... Section: Navbar.
$string['navbarheading'] = 'Navbar';
// ... ... Setting: Navbar color.
$string['navbarcolorsetting'] = 'Navbar color';
$string['navbarcolorsetting_desc'] = 'With this setting, you can change the navbar color from the default light navbar to a dark one or a colored one.';
$string['navbarcolorsetting_light'] = 'Light navbar with dark font color (unchanged as presented by Moodle core)';
$string['navbarcolorsetting_dark'] = 'Dark navbar with light font color';
$string['navbarcolorsetting_primarydark'] = 'Primary color navbar with light font color';
$string['navbarcolorsetting_primarylight'] = 'Primary color navbar with dark font color';

// Settings: Activity branding tab.
$string['activitybrandingtab'] = 'Activity branding';
// ... Section: Activity icon colors.
$string['activityiconcolorsheading'] = 'Activity icon colors';
// ... ... Setting: Activity icon color for 'Administration'.
$string['activityiconcoloradministrationsetting'] = 'Activity icon color for "Administration"';
$string['activityiconcoloradministrationsetting_desc'] = 'The activity icon color for "Administration"';
// ... ... Setting: Activity icon color for 'Assessment'.
$string['activityiconcolorassessmentsetting'] = 'Activity icon color for "Assessment"';
$string['activityiconcolorassessmentsetting_desc'] = 'The activity icon color for "Assessment"';
// ... ... Setting: Activity icon color for 'Collaboration'.
$string['activityiconcolorcollaborationsetting'] = 'Activity icon color for "Collaboration"';
$string['activityiconcolorcollaborationsetting_desc'] = 'The activity icon color for "Collaboration"';
// ... ... Setting: Activity icon color for 'Communication'.
$string['activityiconcolorcommunicationsetting'] = 'Activity icon color for "Communication"';
$string['activityiconcolorcommunicationsetting_desc'] = 'The activity icon color for "Communication"';
// ... ... Setting: Activity icon color for 'Content'.
$string['activityiconcolorcontentsetting'] = 'Activity icon color for "Content"';
$string['activityiconcolorcontentsetting_desc'] = 'The activity icon color for "Content"';
// ... ... Setting: Activity icon color for 'Interactive content'.
$string['activityiconcolorinteractivecontentsetting'] = 'Activity icon color for "Interactive content"';
$string['activityiconcolorinteractivecontentsetting_desc'] = 'The activity icon color for "Interactive content"';
// ... ... Setting: Activity icon color for 'Interface'.
$string['activityiconcolorinterfacesetting'] = 'Activity icon color for "Interface"';
$string['activityiconcolorinterfacesetting_desc'] = 'The activity icon color for "Interface"';
// ... ... Setting: Activity icon color fidelity'.
$string['activityiconcolorfidelitysetting'] = 'Activity icon color fidelity';
$string['activityiconcolorfidelitysetting_desc'] = 'With the settings above, you set a hex color which will be used to tint the particular activity icon. However, technically, the activity icon is tinted with a CSS filter. Boost Union uses a sophisticated algorithm to determine a CSS filter which matches the given hex color visually, but this algorithm is based on a randomized search and might produce suboptimal results when it is run just once. With this setting, you can allow Boost Union to run the algorithm multiple times and pick the filter which deviates least from the hex color at the end. Please note that this setting has an impact on the cache purging times (the more iterations you allow, the longer Moodle will take to purge the theme cache), but it will not have an impact on page load times.';
$string['activityiconcolorfidelity_oneshot'] = 'One shot (1 iteration)';
$string['activityiconcolorfidelity_sometries'] = 'Some tries (up to 10 iterations)';
$string['activityiconcolorfidelity_detailled'] = 'Detailled research (up to 100 iterations)';
$string['activityiconcolorfidelity_insane'] = 'Insane quest (up to 500 iterations)';
// ... Section: Activity icon purposes.
$string['activitypurposeheading'] = 'Activity icon purposes';
$string['activitypurposeheading_desc'] = 'With these settings, you can override the activity icon background color which is defined by the activity\'s purpose (and which is a hardcoded plugin feature in each activity).';
$string['activitypurposeheadingtechnote'] = 'Technical note: Due to the way how Moodle core implements the activity purposes and their colors, the activity purposes are only overridden with CSS by Boost Union. Currently, all areas in Moodle core which show colored activity icons should be covered. If you spot any area or third party plugin which continues to show the unchanged activity purpose colors, please report it on {$a}.';
$string['activitypurposeadministration'] = 'Administration';
$string['activitypurposeassessment'] = 'Assessment';
$string['activitypurposecollaboration'] = 'Collaboration';
$string['activitypurposecommunication'] = 'Communication';
$string['activitypurposecontent'] = 'Content';
$string['activitypurposeinteractivecontent'] = 'Interactive content';
$string['activitypurposeinterface'] = 'Interface';
$string['activitypurposeother'] = 'Other';
// ... Section: Activity icons.
$string['modiconsheading'] = 'Activity icons';
// ... ... Setting: Enable custom icons for activities and resources.
$string['modiconsenablesetting'] = 'Enable custom icons for activities and resources';
$string['modiconsenablesetting_desc'] = 'With this setting, you can modify the icons for activities and resources which are used by Moodle on the course pages and in the activity chooser.';
// ... ... Setting: Custom icon files.
$string['modiconsfiles'] = 'Custom icons files';
$string['modiconsfiles_desc'] = 'Here, you can upload custom icons for all or only some activity modules installed in this Moodle instance.';
$string['modiconsfileshowto'] = 'To upload a particular custom activity icon, start by creating a folder with the internal name of the activity, e.g. <em>assign</em> for the assigment activity. In this folder, you upload the icon as SVG file called monologo.svg and, if possible, as fallback PNG file called monologo.png. If you want to customize the colored icons which have been in use up to Moodle 3 and which may still be used by older plugins, you can also upload them as icon.svg and icon.png files. However, please stick to monochromatic SVG icons if possible for best results. Then, please save the settings page. As soon as you have save the setting with at least one file, a file list will appear below which helps you to check if the custom icons have been uploaded correctly.';
$string['modiconsfilestech'] = 'Technical note: After saving the setting, the uploaded folder structure and icon files will be copied to the pix_plugins/mod folder in your Moodledata directory. This is where Moodle core searches for custom activity icons. All icon files which may already exist in this place will be overwritten when you save this setting.';
$string['modiconserrorcreatingpath'] = 'The pix_plugins/mod folder could not be created in your Moodledata directory.<br />The exception message was:{$a}.';
// ... ... Information: Custom icons files list.
$string['modiconlistsetting'] = 'Custom icons files list';
$string['modiconlistsetting_desc'] = 'This is the list of custom icon files which you have uploaded to the custom icon files filearea above. All valid icon files are listed here. In addition to that, other files you may have uploaded as well but which are not valid icon files are also shown as broken files.';
$string['modiconsuccess4x'] = 'This icon will be used for the <em>{$a}</em> activity as Moodle 4 icon.';
$string['modiconsuccess3x'] = 'This icon will be used for the <em>{$a}</em> activity as Moodle 3 legacy icon.';
$string['modiconnamefail'] = 'This file was uploaded into the correct folder for the <em>{$a}</em> activity, but the filename is not valid. Please change the filename to either <em>monologo.svg</em> / <em>monologo.png</em> (for Moodle 4 icons) or to <em>icon.svg</em> / <em>icon.png</em> (for Moodle 3 legacy icons).';
$string['modiconnotexist'] = 'This file was upload to an unsuitable location as it’s impossible to deduce a particular activity from the file path <em>{$a}</em>.';
$string['modiconactivity'] = 'Activity';
$string['modiconactivityunknown'] = 'Unknown';
$string['modiconversion'] = 'Icon version';
$string['modicongtmoodle4'] = 'Moodle 4 icon';
$string['modiconltmoodle311'] = 'Moodle 3 legacy icon';

// Settings: Login page tab.
$string['loginpagetab'] = 'Login page';
// ... Section: Login page background images.
$string['loginbackgroundimagesheading'] = 'Login page background images';
// ... ... Setting: Login page background image.
$string['loginbackgroundimage'] = 'Login page background images';
$string['loginbackgroundimage_desc'] = 'The images to display as a background of the login page. One of these images will be picked randomly and shown when the user visits the login page. Please make sure not to use non-ASCII-characters in the filename if you want to display text for login background images.';
// ... ... Setting: Login page background image position.
$string['loginbackgroundimagepositionsetting'] = 'Login page background image position';
$string['loginbackgroundimagepositionsetting_desc'] = 'With this setting, you control the positioning of the login page background image within the browser window. The first value is the horizontal position, the second value is the vertical position.';
// ... ... Setting: Login page background image text.
$string['loginbackgroundimagetextsetting'] = 'Display text for login background images';
$string['loginbackgroundimagetextsetting_desc'] = 'With this optional setting you can add text, e.g. a copyright notice to your uploaded background images. This text will appear on top of the page footer on the login page. However, for screen real estate reasons, it is only shown on larger screen sizes.<br/>
Each line consists of the file identifier (the file name), the text that should be displayed and the text color, separated by a pipe character. Each declaration needs to be written in a new line. <br/>
For example:<br/>
background-image-1.jpg|Copyright: CC0|dark<br/>
As text color, you can use the values "dark" or "light".<br />
You can declare texts for an arbitrary amount of your uploaded login background images. The texts will be added only to those images that match their filename with the identifier declared in this setting.';
// ... Section: Login form.
$string['loginformheading'] = 'Login form';
// ... ... Setting: login form position.
$string['loginformpositionsetting'] = 'Login form position';
$string['loginformpositionsetting_desc'] = 'With this setting, you can optimize the login form to fit to a greater variety of background images. By default, the login form is displayed centered on the login page. Alternatively, you can move it to the left or to the right of the login page to let other parts of the background image shine through. Of course, you can also change this setting if no background images are uploaded at all.';
$string['loginformpositionsetting_center'] = 'Centered';
$string['loginformpositionsetting_left'] = 'Left-aligned';
$string['loginformpositionsetting_right'] = 'Right-aligned';
// ... ... Setting: login form transparency.
$string['loginformtransparencysetting'] = 'Login form transparency';
$string['loginformtransparencysetting_desc'] = 'With this setting, you can make the login form slightly transparent to let the background image shine through even more.';
// ... Section: Login providers.
$string['loginprovidersheading'] = 'Login providers';
$string['loginprovidersheading_desc'] = 'Please note: Boost Union has its own login providers settings and does not use the \'{$a->settingname}\' setting from <a href="{$a->url}">Moodle core\'s authentication setting</a>.';
// ... ... Setting: Local login form.
$string['loginlocalloginenablesetting'] = 'Local login';
$string['loginlocalloginenablesetting_desc'] = 'With this setting, you control if the local login form is shown on the login page or not. By default, the local login form is shown and users an login into the site as normal. If you disable this setting, the local login form is hidden. This allows you to just provide login buttons for external identity providers like OAuth2 or OIDC.';
$string['loginlocalloginenablesetting_note'] = 'Please note: As soon as you hide the local login form, you risk that admins cannot log in anymore with a local account if there is a problem with the external identity provider. To allow local logins anyway in such cases, a <a href="{$a->url}">side entrance local login page</a> is provided. On this side entrance local login page, all of Moodle\'s login security measures apply as well.';
$string['loginlocalloginformhead'] = 'Local login';
$string['loginlocalloginlocalnotdisabled'] = 'The local login is enabled on the standard login form. There is no need to log in on this local login page here. Please use the <a href="{$a->url}">standard login page</a> for logging in.';
// ... ... Setting: Local login intro.
$string['loginlocalshowintrosetting'] = 'Local login intro';
$string['loginlocalshowintrosetting_desc'] = 'With this setting, you control if a <em>\'{$a}\'</em> intro is shown above the local login form or not. By default, the intro is not shown. But if you enable it, this intro may help users to understand which credentials to use in the local login form, especially if you provide more than one login method or if you have changed the order of the login methods.';
$string['loginlocalintro'] = 'Login with your Moodle account';
// ... ... Setting: IDP login intro.
$string['loginidpshowintrosetting'] = 'IDP login intro';
$string['loginidpshowintrosetting_desc'] = 'With this setting, you control if the <em>\'{$a}\'</em> intro is shown above the IDP login buttons or not. By default, the intro is shown and users will be quickly informed what the IDP buttons are about. If you disable this setting, the IDP intro is hidden. This allows you to provide a clean user login interface if you just use external identity providers like OAuth2 or OIDC.';
// ... Section: Login order.
$string['loginorderheading'] = 'Login order';
$string['loginorderheading_desc'] = 'With these settings, you control the order of the login methods in the login form. The presented order will be defined from lowest to highest ordinal number, skipping all login methods and login form elements which are disabled in Moodle.';
$string['loginorderheading_note'] = 'Technical note: The presented order will be realized with CSS flexbox orders, not by rearranging the login widgets in the HTML DOM. This should be fine on all modern browsers but might not work on really old browsers.';
// ... ... Settings: Login order.
$string['loginorderlocalsetting'] = 'Local login';
$string['loginorderidpsetting'] = 'IDP login';
$string['loginorderfirsttimesignupsetting'] = 'Information for first time visitors & Self registration';
$string['loginorderguestsetting'] = 'Guest login';

// Settings: Dashboard / My courses tab.
$string['dashboardtab'] = 'Dashboard / My courses';
// ... Section: Course overview block.
$string['courseoverviewheading'] = 'Course overview block';
// ... ... Setting: Show course images.
$string['courseoverviewshowcourseimagessetting'] = 'Show course images';
$string['courseoverviewshowcourseimagessetting_desc'] = 'With this setting, you can control whether the course image is visible inside the course overview block or not. It is possible to choose a different setting for Card view, Summary view, and List view.';
// ... ... Setting: Show course completion progress.
$string['courseoverviewshowprogresssetting'] = 'Show course completion progress';
$string['courseoverviewshowprogresssetting_desc'] = 'With this setting, you can control whether the course completion progress is visible inside the course overview block or not.';

// Settings: Blocks tab.
// The string for this tab is the same as on the 'Feel' page.
// ... Section: Timeline block.
$string['timelineheading'] = 'Timeline block';
// Setting: Tint activity icons in the timeline block.
$string['timelinetintenabled'] = 'Tint timeline activity icons';
$string['timelinetintenabled_desc'] = 'With this setting, you can tint the activity icons in the timeline block based on the activity purposes. By default, Moodle core displays them just as black icons.';
// ... Section: Upcoming events block.
$string['upcomingeventsheading'] = 'Upcoming events block';
// Setting: Tint activity icons in the upcoming events block.
$string['upcomingeventstintenabled'] = 'Tint upcoming events activity icons';
$string['upcomingeventstintenabled_desc'] = 'With this setting, you can tint the activity icons in the upcoming events block based on the activity purposes. By default, Moodle core displays them just as black icons.';
// ... Section: Recently accessed items block.
$string['recentlyaccesseditemsheading'] = 'Recently accessed items block';
// Setting: Tint activity icons in the recently accessed items block.
$string['recentlyaccesseditemstintenabled'] = 'Tint recently accessed items activity icons';
$string['recentlyaccesseditemstintenabled_desc'] = 'With this setting, you can tint the activity icons in the recently accessed items block based on the activity purposes. By default, Moodle core displays them just as black icons.';
// ... Section: Activities block.
$string['activitiesheading'] = 'Activities block';
// Setting: Tint activity icons in the activities block.
$string['activitiestintenabled'] = 'Tint activities activity icons';
$string['activitiestintenabled_desc'] = 'With this setting, you can tint the activity icons in the activities block based on the activity purposes. By default, Moodle core displays them just as black icons.';

// Settings: Course tab.
$string['coursetab'] = 'Course';
// ... Section: Course header.
$string['courseheaderheading'] = 'Course Header';
// ... ... Setting: Course header.
$string['courseheaderimageenabled'] = 'Display the course image in the course header';
$string['courseheaderimageenabled_desc'] = 'When enabled, the course image (which can be uploaded in a course\'s course settings) is displayed in the header of a course. The course images are shown there in addition to the \'My courses\' page where they are always shown.';
$string['courseheaderimagefallback'] = 'Fallback course header image';
$string['courseheaderimagefallback_desc'] = 'If you upload an image in this setting, it is used as fallback image and is displayed in the course header if no course image is uploaded in a particular course\'s course settings. If you do not upload an image here, a course header image is only shown in a particular course if a course image is uploaded in this particular course\'s course settings.';
$string['courseheaderimageheight'] = 'Course header image height';
$string['courseheaderimageheight_desc'] = 'With this setting, you control the height of the presented course header image.';
$string['courseheaderimagelayout'] = 'Course header image layout';
$string['courseheaderimagelayout_desc'] = 'With this setting, you control the layout of the course header image and the course title.';
$string['courseheaderimagelayoutstackeddark'] = 'Course title stacked on course image (white font color for dark background images)';
$string['courseheaderimagelayoutstackedlight'] = 'Course title stacked on course image (black font color for light background images)';
$string['courseheaderimagelayoutheadingabove'] = 'Course title above of course image';
$string['courseheaderimageposition'] = 'Course header image position';
$string['courseheaderimageposition_desc'] = 'With this setting, you control the positioning of the course header image within the course header image container. The first value is the horizontal position, the second value is the vertical position.';
// ... Section: Course index.
$string['courseindexheading'] = 'Course Index';
// ... ... Setting: Course index.
$string['courseindexmodiconenabled'] = 'Display activity type icons in course index';
$string['courseindexmodiconenabled_desc'] = 'When enabled, the corresponding activity type icon is displayed in front of the index row with the title of the activity. In doing so, the course activity type is either replacing the course completion indicator which is moved from the front to the end of the course index row line, or colored in by the completion state color.';
$string['courseindexcompletioninfoposition'] = 'Position of activity completion indication';
$string['courseindexcompletioninfoposition_desc'] = 'Choose the position where the completion indication is displayed. <em>End of line</em> displays the standard completion indicator at the end of the course index row line. <em>Start of line</em> displays the standard completion indicator at the start of the course index row line. <em>Icon color</em> does not show the standard completion indicator, but encodes the completion information as background of the course module icon.';
$string['courseindexcompletioninfopositionendofline'] = 'End of line';
$string['courseindexcompletioninfopositioniconcolor'] = 'Icon color';
$string['courseindexcompletioninfopositionstartofline'] = 'Start of line';

// Settings: E-Mail branding tab.
$string['emailbrandingtab'] = 'E-Mail branding';
$string['templateemailhtmlprefix'] = '';
$string['templateemailhtmlsuffix'] = '';
$string['templateemailtextprefix'] = '';
$string['templateemailtextsuffix'] = '';
// ... Section: E-Mails introduction.
$string['emailbrandingintroheading'] = 'Introduction';
$string['emailbrandingintronote'] = 'Please note: This is an advanced functionality which uses some workarounds to provide E-Mail branding options. Please follow the instructions closely.';
$string['emailbrandinginstruction'] = 'How-to';
$string['emailbrandinginstruction0'] = 'With this Boost Union feature, you can apply branding to all E-Mails which Moodle is sending out.';
$string['emailbrandinginstructionli1'] = 'Go to the <a href="{$a->url}" target="_blank">language customization settings page</a> to open the <em>{$a->lang}</em> language pack for editing.';
$string['emailbrandinginstructionli2'] = 'Search for and modify these strings in the <code>theme_boost_union</code> language pack:';
$string['emailbrandinginstructionli2li1'] = '<code>templateemailhtmlprefix</code>: This snippet will be added <em>at the beginning / before the body</em> of all <em>HTML E-Mails</em> which Moodle is sending out.';
$string['emailbrandinginstructionli2li2'] = '<code>templateemailhtmlsuffix</code>: This snippet will be added <em>at the end / after the body</em> of all <em>HTML E-Mails</em> which Moodle is sending out.';
$string['emailbrandinginstructionli2li3'] = '<code>templateemailtextprefix</code>: This snippet will be added <em>at the beginning / before the body</em> of all <em>plaintext E-Mails</em> which Moodle is sending out.';
$string['emailbrandinginstructionli2li4'] = '<code>templateemailtextsuffix</code>: This snippet will be added <em>at the end / after the body</em> of all <em>plaintext E-Mails</em> which Moodle is sending out.';
$string['emailbrandinginstructionli3'] = 'Save the changes to the language pack.';
$string['emailbrandinginstructionli4'] = 'Come back to this page and have a look at the E-Mail previews below.';
$string['emailbrandingpitfalls'] = 'Pitfalls';
$string['emailbrandingpitfalls0'] = 'Using this feature, there are some pitfalls which you should be aware of:';
$string['emailbrandingpitfallsli1'] = 'It is mandatory that you modify the language pack strings of the <em>current default language</em> of this site. Even if you have multiple language packs installed, only changes to the default language will have an effect.';
$string['emailbrandingpitfallsli2'] = 'Respecting the receipient\'s language is not possible. Thus, you should use language-agnostic terms within your E-Mail branding snippets.';
$string['emailbrandingpitfallsli3'] = 'If you ever change the site\'s default language in the future, you will have to migrate the modified language pack strings to the new default language pack.';
$string['emailbrandingpitfallsli4'] = 'In plaintext E-Mails, there is a line break and an empty line added automatically after the prefix and an empty line added automatically before the suffix snippet. This is to make sure that the suffix and prefix do not stick directly to the E-Mail body.';
$string['emailbrandingpitfallsli5'] = 'In HTML E-Mails, the prefix and the suffix are directly added before and above the E-Mail body. This is to make sure that you can work with HTML tags easily, however you will have to handle all spacing around the body yourself.';
$string['emailbrandingpitfallsli6'] = 'In HTML E-Mails, you can open a HTML tag in the prefix snippet and close the tag in the suffix snippet without problems. Just remember to create valid HTML in the resulting mail.';
// ... Section: HTML E-Mails.
$string['emailbrandinghtmlheading'] = 'HTML E-Mail preview';
$string['emailbrandinghtmlintro'] = 'This is a preview of a HTML E-Mail based on the branding prefixes and suffixes which are currently set in the language pack.';
$string['emailbrandinghtmlnopreview'] = 'Up to now, the HTML E-Mails haven\'t been customized within this feature. E-Mails will be composed and sent out normally.';
$string['emailbrandinghtmldemobody'] = '<p>E-Mail body starts here.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><p>Id donec ultrices tincidunt arcu non sodales. Id volutpat lacus laoreet non curabitur gravida arcu.</p><p>Cursus turpis massa tincidunt dui. Pellentesque nec nam aliquam sem et tortor consequat id. In ornare quam viverra orci sagittis eu volutpat. Sem nulla pharetra diam sit amet nisl suscipit. Justo donec enim diam vulputate ut pharetra.</p><p>E-Mail body ends here.</p>';
// ... Section: Plaintext E-Mails.
$string['emailbrandingtextheading'] = 'Plaintext E-Mail preview';
$string['emailbrandingtextintro'] = 'This is a preview of a plaintext E-Mail based on the branding prefixes and suffixes which are currently set in the language pack.';
$string['emailbrandingtextnopreview'] = 'Up to now, the plaintext E-Mails haven\'t been customized within this feature. E-Mails will be composed and sent out normally.';
$string['emailbrandingtextdemobody'] = 'E-Mail body starts here.

Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.

Id donec ultrices tincidunt arcu non sodales. Id volutpat lacus laoreet non curabitur gravida arcu.

Cursus turpis massa tincidunt dui. Pellentesque nec nam aliquam sem et tortor consequat id. In ornare quam viverra orci sagittis eu volutpat. Sem nulla pharetra diam sit amet nisl suscipit. Justo donec enim diam vulputate ut pharetra.

E-Mail body ends here.';

// Settings: Resources tab.
$string['resourcestab'] = 'Resources';
$string['resourcescachecontrolnote'] = 'Please note that the files are shipped to the browser with the \'Cache-Control\' header set which tells the browser to cache the file. If you are sure that you won\'t change the file in the near future, you can use the persistent URL to link to the file. However, if you plan to modify a file but keep the same filename every now and then, you should rather use the revisioned URL and re-link the file where you have used it everytime you update the file to avoid that the browsers will show cached outdated versions of the file.';
// ... Section: Additional resources.
$string['additionalresourcesheading'] = 'Additional resources';
// ... ... Setting: Additional resources.
$string['additionalresourcessetting'] = 'Additional resources';
$string['additionalresourcessetting_desc'] = 'With this setting you can upload additional resources to the theme. The advantage of uploading files to this file area is that those files can be delivered without a check if the user is logged in. This is also why you should only add files that are uncritical and everyone should be allowed to access and don\'t need be protected with a valid login. As soon as you have uploaded at least one file to this filearea and have stored the settings, a list will appear underneath which will give you the URL which you can use to reference a particular file.';
// ... ... Information: Additional resources list.
$string['additionalresourceslistsetting'] = 'Additional resources list';
$string['additionalresourceslistsetting_desc'] = 'This is the list of files which you have uploaded to the additional resources filearea. The given URLs can be used to link to these files from within your custom CSS, from the footnote or wherever you need to use uploaded files but can\'t upload files in place.';
$string['additionalresourcesfileurlpersistent'] = 'URL (persistent)';
$string['additionalresourcesfileurlrevisioned'] = 'URL (revisioned)';
// ... Section: Custom fonts.
$string['customfontsheading'] = 'Custom fonts';
// ... ... Setting: Custom fonts.
$string['customfontssetting'] = 'Custom fonts';
$string['customfontssetting_desc'] = 'With this setting you can upload custom fonts to the theme. The advantage of uploading fonts to this file area is that those fonts can be delivered without a check if the user is logged in and can be used as locally installed fonts everywhere on the site. As soon as you have uploaded at least one font to this filearea and have stored the settings, a list will appear underneath which will give you CSS code snippets which you can use as a boilerplate to reference particular fonts in your custom SCSS.';
// ... ... Information: Custom fonts list.
$string['customfontslistsetting'] = 'Custom fonts list';
$string['customfontslistsetting_desc'] = 'This is the list of fonts which you have uploaded to the custom fonts filearea. The given CSS snippets can be used to add these fonts to your custom SCSS. Please note that you will have to take care of the font format value as well as the font-family, font-style and font-weight CSS properties yourself for now as Boost Union is not able yet to parse the font files.';
$string['customfontsfileurlpersistent'] = 'URL (persistent)';
$string['customfontsfileurlrevisioned'] = 'URL (revisioned)';

// Settings: H5P tab.
$string['h5ptab'] = 'H5P';
// ... Section: Raw CSS for H5P.
$string['cssh5pheading'] = 'Raw CSS for H5P';
// ... ... Setting: Raw CSS for H5P.
$string['cssh5psetting'] = 'Raw CSS for H5P';
$string['cssh5psetting_desc'] = 'Use this field to provide CSS code which will be applied to the presentation of H5P content by mod_h5p and mod_hvp. Please inspect the H5P content types to find the necessary CSS selectors.';
// ... Section: Content width.
$string['contentwidthheading'] = 'Content width';
// ... ... Setting: H5P content bank max width.
$string['h5pcontentmaxwidthsetting'] = 'H5P content bank max width';
$string['h5pcontentmaxwidthsetting_desc'] = 'With this setting, you can override Moodle\'s H5P content bank width without manual SCSS modifications. This width is used for the H5P editor within the content bank. It is <em>not</em> used for the width of the H5P activity. By default, Moodle uses a H5P content bank max width of 960px. You can enter other pixel-based values like 1200px, but you can also enter a percentage-based value like 100% or a viewport-width value like 90vw.';

// Settings: Mobile tab.
$string['mobiletab'] = 'Mobile';
// ... Section: Mobile app.
$string['mobileappheading'] = 'Mobile app';
// ... ... Setting: Additional CSS for Mobile app.
$string['mobilecss'] = 'Additional CSS for Mobile app';
$string['mobilecss_desc'] = 'With this setting, you can write custom CSS code to customise your mobile app interface. The CSS code will be only added to the Mobile app depiction of this Moodle instance and will not be shown in the webbrowser version. Read more about this feature in the <a href="https://moodledev.io/general/app/customisation/remote-themes#how-do-remote-themes-work">Moodle dev docs</a>.';
$string['mobilecss_set'] = 'As soon as you add any CSS code to this setting and save the setting, the <a href="{$a->url}">Moodle core setting <em>mobilecssurl</em></a> will be automatically set to a URL of the Boost Union theme.';
$string['mobilecss_overwrite'] = 'As soon as you add any CSS code to this setting and save the setting, the <a href="{$a->url}">Moodle core setting <em>mobilecssurl</em></a> will be automatically overwritten with a URL of the Boost Union theme. Currently this setting is set to <a href="{$a->value}">{$a->value}</a>.';
$string['mobilecss_donotchange'] = 'This step is necessary to ship the CSS code to the Mobile app. Do not change the URL there unless you really want to remove the CSS code from the Mobile app again.';
// ... Section: Mobile appearance.
$string['mobileappearanceheading'] = 'Mobile appearance';
// ... ... Setting: Touch icon files for iOS.
$string['touchiconfilesios'] = 'Touch icon files for iOS';
$string['touchiconfilesios_desc'] = 'Within this setting, you can upload files which are used as homescreen icon as soon as the Moodle site is added to the iOS homescreen as bookmark.';
$string['touchiconfilesios_recommended'] = 'Recommended files for iOS:';
$string['touchiconfilesios_optional'] = 'Optional files for iOS:';
$string['touchiconfilesios_example'] = 'Example filename: apple-icon-152x152.png';
$string['touchiconfilesios_note'] = 'Recommended files have a good size to be shown properly on current iOS devices and should be provided. Optional files are (or have been previously) supported by iOS devices as well but should be really considered as optional unless you have a particular legacy device to support.';
$string['touchiconfilesioslist'] = 'Touch icon files for iOS list';
$string['touchiconfilesioslist_desc'] = 'This is the list of files which you have uploaded to the touch icon files for iOS filearea.';
$string['touchiconlistiosrecommendeduploaded'] = 'This is a recommended file to be used as touch icon on iOS devices and it was uploaded.';
$string['touchiconlistiosrecommendedmissing'] = 'This is a recommended file to be used as touch icon on iOS devices, but it was not uploaded properly.';
$string['touchiconlistiosoptionaluploaded'] = 'This is an optional file to be used as touch icon on iOS devices and it was uploaded.';
$string['touchiconlistiosoptionalmissing'] = 'This is an optional file to be used as touch icon on iOS devices and it was not uploaded.';

// Settings: Feel page.
$string['configtitlefeel'] = 'Feel';

// Settings: Navigation tab.
$string['navigationtab'] = 'Navigation';
// ... Section: Primary navigation.
$string['primarynavigationheading'] = 'Primary navigation';
// ... ... Settings: Hide nodes in primary navigation.
$string['hidenodesprimarynavigationsetting'] = 'Hide nodes in primary navigation';
$string['hidenodesprimarynavigationsetting_desc'] = 'With this setting, you can hide one or multiple nodes from the primary navigation.<br /><br />
Please note: Here, you can just remove navigation nodes. But if you want to add custom navigation nodes, please consider using <a href="{$a->url}">Boost Union\'s smart menu functionality</a>.';
// ... ... Settings: Alternative logo link URL.
$string['alternativelogolinkurlsetting'] = 'Alternative logo link URL';
$string['alternativelogolinkurlsetting_desc'] = 'With this setting, you can set an alternative link URL which will be used as link on the logo in the navigation bar. You can use this setting to, for example, link to your organization\'s website instead of the Moodle frontpage to maintain a homogeneous navigation bar throughout all of your organization\'s systems.';

// ... Section: User menu.
$string['usermenuheading'] = 'User menu';
// ... ... Settings: Show full name in the user menu.
$string['showfullnameinusermenussetting'] = 'Show full name in the user menu';
$string['showfullnameinusermenussetting_desc'] = 'With this setting, you can show the logged-in user\'s full name at the top of the user menu. This can be especially helpful for exam situations where teachers have to confirm that the user is logged in with his own account, but it might also be helpful for the user himself. In contrast to the Classic theme which shows the user\'s full name in the navbar near the avatar, this approach here does not claim any additional rare space in the navbar.';
$string['showfullnameinusermenussetting_loggedinas'] = 'You are logged in as:';
// ... ... Settings: Add preferred language link to language menu.
$string['addpreferredlangsetting'] = 'Add preferred language link to language menu';
$string['addpreferredlangsetting_desc'] = 'With this setting, you can add a \'Set preferred language\' setting to the language menu within the user menu. Understandably, this setting is only processed if the setting <a href="{$a->url1}">Display language menu</a> is enabled, and if at least <a href="{$a->url2}">a second language pack is installed</a> and <a href="{$a->url3}">offered for selection</a>.';
$string['setpreferredlanglink'] = 'Set preferred language';
// ... Section: Navbar heading.
$string['navbarheading'] = 'Navbar';
// ... ... Setting: Show starred courses popover in the navbar.
$string['shownavbarstarredcoursessetting'] = 'Show starred courses popover in the navbar';
$string['shownavbarstarredcoursessetting_desc'] = 'With this setting, you can show a popover menu with links to starred courses next to the messages and notifications menus.';
$string['shownavbarstarredcourses_config'] = 'Set starred courses on the \'My courses\' page';
$string['shownavbarstarredcourses_label'] = 'Starred courses';
// ... Section: Breadcrumbs.
$string['breadcrumbsheading'] = 'Breadcrumbs';
// ... ... Setting: Course category breadcrumb.
$string['categorybreadcrumbs'] = 'Display the category breadcrumbs in the course header';
$string['categorybreadcrumbs_desc'] = 'By default, the course category breadcrumbs are not shown on course pages in the course header. With this setting, you can show the course category breadcrumbs in the course header above the course name.';
// ... Section: Navigation.
$string['navigationheading'] = 'Navigation';
// ... ... Setting: Back to top button.
$string['backtotop'] = 'Back to top';
$string['backtotopbuttonsetting'] = 'Back to top button';
$string['backtotopbuttonsetting_desc'] = 'With this setting a back to top button will appear in the bottom right corner of the page as soon as the user scrolls down the page. A button like this existed already on Boost in Moodle Core until Moodle 3.11, but was removed in 4.0. With Boost Union, you can bring it back.';
// ... ... Setting: Scroll-spy
$string['scrollspysetting'] = 'Scroll-spy';
$string['scrollspysetting_desc'] = 'With this setting, upon toggling edit mode on and off, the scroll position at where the user was when performing the toggle is preserved.';
// ... ... Setting: Activity & section navigation
$string['activitynavigationsetting'] = 'Activity & section navigation elements';
$string['activitynavigationsetting_desc'] = 'With this setting, the elements to jump to the previous and next activity/resource as well as the pull down menu to jump to a distinct activity/resource become displayed. Furthermore, within courses using the \'one section per page\' mode, similar elements for the previous and next section are displayed as well. UI elements like this existed already on Boost in Moodle Core until Moodle 3.11, but were removed in 4.0. With Boost Union, you can bring them back.';

// Settings: Blocks tab.
$string['blockstab'] = 'Blocks';
// ... Section: General blocks.
$string['blocksgeneralheading'] = 'General blocks';
// ... Section: Block regions.
$string['blockregionsheading'] = 'Additional block regions';
$string['blockregionsheading_desc'] = '<p>Boost Union provides a large number of additional block regions which can be used to add and show blocks over the whole Moodle page:</p>
<ul><li>The <em>Outside block regions</em> are placed on all four sides of the Moodle page. They can be used to show blocks which accompany the shown Moodle page but do not directly belong to the main content.</li>
<li>The <em>Header block region</em> is placed between the Outside (top) area and the main content area. It can be used to show a block as course header information.</li>
<li>The <em>Content block regions</em> are placed directly over and under the main content in the main content area. They can be used to add blocks to the course content flow.</li>
<li>The <em>Footer block regions</em> are placed at the bottom of the page between the Outside (bottom) area and the footnote. You have three footer regions available to build columns if necessary.</li>
<li>The <em>Off-canvas block region</em> is somehow special as it hovers over the whole Moodle page as a drawer. The drawer is opened by the 9-dots icon at the very right side of the navigation bar. You have three off-canvas regions available to build columns if necessary.</li></ul>
<p>Please note:</p>
<ul><li>By default, all additional block regions are disabled. Please enable the particular block regions on the particular page layouts according to your needs. Try to be as focused as possible – too many block regions could overwhelm end users.</li>
<li>As soon as an additional block region is enabled, it is visible for all authenticated users and editable by teachers and managers (depending on the fact if the particular user is allowed to edit the particular Moodle page, of course). But there are also theme/boost_union:viewregion* and theme/boost_union:editregion* capabilities which allow you to fine-tune the usage of each block region according to your needs.</li>
<li>The Outside (left), Outside (right), Content (upper), Content (lower) and Header block regions are not available for all page layouts.</li></ul>';
$string['blockregionsheading_experimental'] = 'Please note: The <em>Outside (left) and Outside (right) block regions</em> are fully working in the current state of implementation, but have to be <em>considered as experimental</em> as they do not wrap properly on medium width screens yet. Against this background, please use them with care. This issue will be fixed in an upcoming release.';
$string['region-none'] = 'None';
$string['region-outside-left'] = 'Outside (left)';
$string['region-outside-top'] = 'Outside (top)';
$string['region-outside-bottom'] = 'Outside (bottom)';
$string['region-outside-right'] = 'Outside (right)';
$string['region-content-upper'] = 'Content (upper)';
$string['region-content-lower'] = 'Content (lower)';
$string['region-footer-left'] = 'Footer (left)';
$string['region-footer-right'] = 'Footer (right)';
$string['region-footer-center'] = 'Footer (center)';
$string['region-header'] = 'Header';
$string['region-offcanvas-left'] = 'Off-canvas (left)';
$string['region-offcanvas-right'] = 'Off-canvas (right)';
$string['region-offcanvas-center'] = 'Off-canvas (center)';
$string['closeoffcanvas'] = 'Close Off-canvas drawer';
$string['openoffcanvas'] = 'Open Off-canvas drawer';
// ... ... Setting: Block regions for 'x' layout.
$string['blockregionsforlayout'] = 'Additional block regions for \'{$a}\' layout';
$string['blockregionsforlayout_desc'] = 'With this setting, you can enable additional block regions for the \'{$a}\' layout.';
// ... Section: Outside regions.
$string['outsideregionsheading'] = 'Outside regions';
$string['outsideregionsheading_desc'] = 'Outside regions can not only be enabled with the layout settings above, their appearance can also be customized.';
// ... ... Setting: Block region width for Outside (left) region.
$string['blockregionoutsideleftwidth'] = 'Block region width for \'Outside (left)\' region';
$string['blockregionoutsideleftwidth_desc'] = 'With this setting, you can set the width of the \'Outside (left)\' block region which is shown on the left hand side of the main content area. By default, Boost Union uses a width of 300px. You can enter other pixel-based values like 200px, but you can also enter a percentage-based value like 10% or a viewport-width value like 10vw.';
// ... ... Setting: Block region width for Outside (right) region.
$string['blockregionoutsiderightwidth'] = 'Block region width for \'Outside (right)\' region';
$string['blockregionoutsiderightwidth_desc'] = 'With this setting, you can set the width of the \'Outside (right)\' block region which is shown on the right hand side of the main content area. By default, Boost Union uses a width of 300px. You can enter other pixel-based values like 200px, but you can also enter a percentage-based value like 10% or a viewport-width value like 10vw.';
// ... ... Setting: Block region width for Outside (top) region.
$string['blockregionoutsidetopwidth'] = 'Block region width for \'Outside (top)\' region';
$string['blockregionoutsidetopwidth_desc'] = 'With this setting, you can set the width of the \'Outside (top)\' block region which is shown at the very top of the page. You can choose between full width, course content width and hero width.';
$string['outsideregionswidthfullwidth'] = 'Full width';
$string['outsideregionswidthcoursecontentwidth'] = 'Course content width';
$string['outsideregionswidthherowidth'] = 'Hero width';
// ... ... Setting: Block region width for Outside (bottom) region.
$string['blockregionoutsidebottomwidth'] = 'Block region width for \'Outside (bottom)\' region';
$string['blockregionoutsidebottomwidth_desc'] = 'With this setting, you can set the width of the \'Outside (bottom)\' block region which is shown below the main content. You can choose between full width, course content width and hero width.';
// ... ... Setting: Block region width for Footer region.
$string['blockregionfooterwidth'] = 'Block region width for \'Footer\' region';
$string['blockregionfooterwidth_desc'] = 'With this setting, you can set the width of the \'Footer\' block region. You can choose between full width, course content width and hero width.';
// ... ... Setting: Outside regions horizontal placement.
$string['outsideregionsplacement'] = 'Outside regions horizontal placement';
$string['outsideregionsplacement_desc'] = 'With this setting, you can control if, on larger screens, the \'Outside (left)\' and \'Outside (right)\' block regions should be placed near the main content area or rather near the window edges.';
$string['outsideregionsplacementnextmaincontent'] = 'Display \'Outside (left)\' and \'Outside (right)\' regions next to the main content area';
$string['outsideregionsplacementnearwindowedges'] = 'Display \'Outside (left)\' and \'Outside (right)\' regions near the window edges';
// ... Section: Site home right-hand block drawer behaviour.
$string['sitehomerighthandblockdrawerbehaviour'] = 'Site home right-hand block drawer';
// ... ... Setting: Show right-hand block drawer of site home on visit.
$string['showsitehomerighthandblockdraweronvisitsetting'] = 'Show right-hand block drawer of site home on visit';
$string['showsitehomerighthandblockdraweronvisitsetting_desc'] = 'With this setting, the right-hand block drawer of site home will be displayed in its expanded state by default. This only applies to users who are not logged in and does not overwrite the toggle state of each individual user.';
// ... ... Setting: Show right-hand block drawer of site home on first login.
$string['showsitehomerighthandblockdraweronfirstloginsetting'] = 'Show right-hand block drawer of site home on first login';
$string['showsitehomerighthandblockdraweronfirstloginsetting_desc'] = 'With this setting, the right-hand block drawer of site home will be displayed in its expanded state by default. This only applies to users who log in for the very first time and does not overwrite the toggle state of each individual user.';
// ... ... Setting: Show right-hand block drawer of site home on guest login.
$string['showsitehomerighthandblockdraweronguestloginsetting'] = 'Show right-hand block drawer of site home on guest login';
$string['showsitehomerighthandblockdraweronguestloginsetting_desc'] = 'With this setting, the right-hand block drawer of site home will be displayed in its expanded state by default. This only applies to users who log in as a guest.';

// Settings: Page layouts tab.
$string['pagelayoutstab'] = 'Page layouts';
// ... Section: tool_policy heading.
$string['policyheading'] = 'Policies';
// ... ... Setting: Navigation on policy overview page.
$string['policyoverviewnavigationsetting'] = 'Show navigation on policy overview page';
$string['policyoverviewnavigationsetting_desc'] = 'By default, the <a href="{$a->url}">policy overview page (provided by tool_policy)</a> does not show a navigation menu or footer. With this setting, you can show the primary navigation and footer on that page.';

// Settings: Links tab.
$string['linkstab'] = 'Links';
// ... Section: Special links markup.
$string['speciallinksmarkupheading'] = 'Special links markup';
// ... ... Setting: Mark external links.
$string['markexternallinkssetting'] = 'Mark external links';
$string['markexternallinkssetting_desc'] = 'Adds an "external link" icon after external links (which lead the user to a target outside Moodle).';
// ... ... Setting: Mark external links scope.
$string['markexternallinksscopesetting'] = 'Mark external links scope';
$string['markexternallinksscopesetting_desc'] = 'With this setting, you control the scope where Boost Union should mark external links. By default, Boost Union marks external links on the whole Moodle page and does its best to cover some edge-cases where adding the external link icon does not make much sense. However, you can also limit the scope to better avoid edge-cases.';
$string['marklinksscopesetting_wholepage'] = 'On the whole page';
$string['marklinksscopesetting_coursemain'] = 'Within the main content area of course main pages only';
// ... ... Setting: Mark mailto links.
$string['markmailtolinkssetting'] = 'Mark mailto links';
$string['markmailtolinkssetting_desc'] = 'Adds an "envelope" icon in front of mailto links.';
// ... ... Setting: Mark mailto links scope.
$string['markmailtolinksscopesetting'] = 'Mark mailto links scope';
$string['markmailtolinksscopesetting_desc'] = 'With this setting, you control the scope where Boost Union should mark mailto links. By default, Boost Union marks mailto links on the whole Moodle page. However, you can also limit the scope to avoid edge-cases.';
// ... ... Setting: Mark broken links.
$string['markbrokenlinkssetting'] = 'Mark broken links';
$string['markbrokenlinkssetting_desc'] = 'Adds a "broken chain" icon in front of broken links (which lead to uploaded draft files which have not been properly processed) and marks the link in the bootstrap color for "danger". In contrast to the "Mark external links" and "Mark mailto links" settings, there is no possibility to limit the scope of this setting as marking broken links is an indicator that something is broken and has to be fixed manually.';

// Settings: Misc tab.
$string['misctab'] = 'Miscellaneous';
// ... Section: JavaScript.
$string['javascriptheading'] = 'JavaScript';
// ... ... Setting: JavaScript disabled hint.
$string['javascriptdisabledhint'] = 'JavaScript disabled hint';
$string['javascriptdisabledhint_desc'] = 'With this setting, a hint will appear at the top of the Moodle page if JavaScript is not enabled. This is particularly helpful as several Moodle features do not work without JavaScript.';
$string['javascriptdisabledhinttext'] = 'JavaScript is disabled in your browser.<br />Many features of Moodle will be not usable or will appear to be broken.<br />Please enable JavaScript for the full Moodle experience.';

// Settings: Content page.
$string['configtitlecontent'] = 'Content';

// Settings: Footer tab.
$string['footertab'] = 'Footer';
// ... Section: Footnote.
$string['footnoteheading'] = 'Footnote';
// ... ... Setting: Footnote.
$string['footnotesetting'] = 'Footnote';
$string['footnotesetting_desc'] = 'Whatever you add to this textarea will be displayed at the end of a page, in the footer (not the floating footer) on every page which uses the layouts "drawers", "columns2" or "login". Content in this area could be for example the copyright, the terms of use or the name of your organisation. <br/> If you want to remove the footnote again, just empty the text area.';
// ... Section: Footer.
$string['footerheading'] = 'Footer';
// ... ... Setting: Enable footer.
$string['enablefooterbutton'] = 'Enable footer';
$string['enablefooterbutton_desc'] = 'With "footer", the circle containing the question mark at the bottom of the page is meant.<br />Upon click, the user is presented with an overlay. Depending on the site configuration Moodle shows several links (like "Documentation for this page" or "Data retention summary") are shown in this overlay.<br />With this setting, you can control whether to show or to suppress the footer button at the bottom of the page.';
$string['enablefooterbuttonboth'] = 'Enable on desktop, tablet and mobile';
$string['enablefooterbuttondesktop'] = 'Enable on desktop and tablet only, hide on mobile (unchanged as presented by Moodle core)';
$string['enablefooterbuttonmobile'] = 'Enable on mobile only, hide on desktop and tablet';
$string['enablefooterbuttonhidden'] = 'Hide on all devices';
// ... ... Setting: Suppress icons in front of the footer links.
$string['footersuppressiconssetting'] = 'Suppress icons in front of the footer links';
$string['footersuppressiconssetting_desc'] = 'With this setting, you can entirely suppress the icons in front of the footer links. \'Documentation for this page\' has a book icon, \'Services and support\' a life ring etc.';
// ... ... Setting: Suppress 'Chat to course participants' link.
$string['footersuppresschatsetting'] = 'Suppress \'Chat to course participants\' link';
$string['footersuppresschatsetting_desc'] = 'With this setting, you can entirely suppress the \'Chat to course participants\' link in the footer. This link would otherwise appear within courses as soon as a communication room is added in a course\'s settings.';
// ... ... Setting: Suppress 'Documentation for this page' link.
$string['footersuppresshelpsetting'] = 'Suppress \'Documentation for this page\' link';
$string['footersuppresshelpsetting_desc'] = 'With this setting, you can entirely suppress the \'Documentation for this page\' link in the footer. This link would otherwise appear if a <a href="{$a->url}">Moodle Docs document root</a> is set.';
// ... ... Setting: Suppress 'Services and support' link.
$string['footersuppressservicessetting'] = 'Suppress \'Services and support\' link';
$string['footersuppressservicessetting_desc'] = 'With this setting, you can entirely suppress the \'Services and support\' link in the footer. This link would otherwise show the <a href="{$a->url}">Services and support link</a> to administrators.';
// ... ... Setting: Suppress 'Contact site support' link.
$string['footersuppresscontactsetting'] = 'Suppress \'Contact site support\' link';
$string['footersuppresscontactsetting_desc'] = 'With this setting, you can entirely suppress the \'Contact site support\' link in the footer. This link would otherwise appear if the <a href="{$a->url}">Contact site support link</a> is set.';
// ... ... Setting: Suppress Login info.
$string['footersuppresslogininfosetting'] = 'Suppress Login info';
$string['footersuppresslogininfosetting_desc'] = 'With this setting, you can entirely suppress the login info in the footer. This info would otherwise show links to a user\'s profile and to the logout page.';
// ... ... Setting: Suppress 'Reset user tour on this page' link.
$string['footersuppressusertoursetting'] = 'Suppress \'Reset user tour on this page\' link';
$string['footersuppressusertoursetting_desc'] = 'With this setting, you can entirely suppress the \'Reset user tour on this page\' link in the footer. This link would otherwise provide the possibility to reset a user tour on a particular page.';
// ... ... Setting: Suppress theme switcher links.
$string['footersuppressthemeswitchsetting'] = 'Suppress theme switcher links';
$string['footersuppressthemeswitchsetting_desc'] = 'With this setting, you can entirely suppress the theme switcher links in the footer. The underlying system for device-specific themes was removed in Moodle 4.3, but the output routines are still there, so better be save than sorry.';
// ... ... Setting: Suppress 'Powered by Moodle' link.
$string['footersuppresspoweredsetting'] = 'Suppress \'Powered by Moodle\' link';
$string['footersuppresspoweredsetting_desc'] = 'With this setting, you can entirely suppress the \'Powered by Moodle\' link in the footer. This link would otherwise show an information that this site is running Moodle and provide a link to Moodle HQ.';
// ... ... Setting: Suppress footer output by core components.
$string['footersuppressstandardfootercore'] = 'Suppress footer output by core component \'{$a}\'';
$string['footersuppressstandardfootercore_desc'] = 'With this setting, you can entirely suppress the footer output by the core component \'{$a}\'. Core components can add additional content to the footer by implementing a particular hook or function. This core component has implemented this hook / function and might add content to the footer in certain circumstances.';
// ... ... Setting: Suppress footer output by plugins.
$string['footersuppressstandardfooter'] = 'Suppress footer output by plugin \'{$a}\'';
$string['footersuppressstandardfooter_desc'] = 'With this setting, you can entirely suppress the footer output by plugin \'{$a}\'. Plugins (even if they are shipped with Moodle core, but are still technically plugins) can add additional content to the footer by implementing a particular hook or function. This plugin has implemented this hook / function and might add content to the footer in certain circumstances.<br />Please note: Due to the way how the suppressing feature is implemented, the setting might not take effect before the second page load after saving the setting.';

// Settings: Static pages tab.
$string['staticpagestab'] = 'Static pages';
// ... Section: About us.
$string['aboutusheading'] = 'About us';
// ... ... Setting: Enable about us page.
$string['enableaboutussetting'] = 'Enable about us page';
$string['aboutusdisabled'] = 'The about us page is disabled for this site. There is nothing to see here.';
// ... ... Setting: About us content.
$string['aboutuscontentsetting'] = 'About us content';
$string['aboutuscontentsetting_desc'] = 'In this setting, you can add rich text content which will be shown on the about us page.';
// ... ... Setting: About us page title.
$string['aboutuspagetitledefault'] = 'About us';
$string['aboutuspagetitlesetting'] = 'About us page title';
$string['aboutuspagetitlesetting_desc'] = 'In this setting, you can define the title of the about us page. This text will be used as link text to the about us page as well if you configure \'About us link position\' accordingly.';
// ... ... Setting: About us link position.
$string['aboutuslinkpositionnone'] = 'Do not automatically show a link to the about us page';
$string['aboutuslinkpositionfootnote'] = 'Add a link to the about us page to the footnote';
$string['aboutuslinkpositionfooter'] = 'Add a link to the about us page to the footer (questionmark) icon';
$string['aboutuslinkpositionboth'] = 'Add a link to the about us page to the footnote and to the footer (questionmark) icon';
$string['aboutuslinkpositionsetting'] = 'About us link position';
$string['aboutuslinkpositionsetting_desc'] = 'In this setting, you can configure if a link to the about us page should be added automatically to the Moodle page. If you do not want to show a link automatically, you can add a link to {$a->url} from anywhere in Moodle manually.';
// ... Section: Offers.
$string['offersheading'] = 'Offers';
// ... ... Setting: Enable offers page.
$string['enableofferssetting'] = 'Enable offers page';
$string['offersdisabled'] = 'The offers page is disabled for this site. There is nothing to see here.';
// ... ... Setting: Offers content.
$string['offerscontentsetting'] = 'Offers content';
$string['offerscontentsetting_desc'] = 'In this setting, you can add rich text content which will be shown on the offers page.';
// ... ... Setting: Offers page title.
$string['offerspagetitledefault'] = 'Offers';
$string['offerspagetitlesetting'] = 'Offers page title';
$string['offerspagetitlesetting_desc'] = 'In this setting, you can define the title of the offers page. This text will be used as link text to the offers page as well if you configure \'Offers link position\' accordingly.';
// ... ... Setting: Offers link position.
$string['offerslinkpositionnone'] = 'Do not automatically show a link to the offers page';
$string['offerslinkpositionfootnote'] = 'Add a link to the offers page to the footnote';
$string['offerslinkpositionfooter'] = 'Add a link to the offers page to the footer (questionmark) icon';
$string['offerslinkpositionboth'] = 'Add a link to the offers page to the footnote and to the footer (questionmark) icon';
$string['offerslinkpositionsetting'] = 'Offers link position';
$string['offerslinkpositionsetting_desc'] = 'In this setting, you can configure if a link to the offers page should be added automatically to the Moodle page. If you do not want to show a link automatically, you can add a link to {$a->url} from anywhere in Moodle manually.';
// ... Section: Imprint.
$string['imprintheading'] = 'Imprint';
// ... ... Setting: Enable imprint.
$string['enableimprintsetting'] = 'Enable imprint';
$string['imprintdisabled'] = 'The imprint page is disabled for this site. There is nothing to see here.';
// ... ... Setting: Imprint content.
$string['imprintcontentsetting'] = 'Imprint content';
$string['imprintcontentsetting_desc'] = 'In this setting, you can add rich text content which will be shown on the imprint page.';
// ... ... Setting: Imprint page title.
$string['imprintpagetitledefault'] = 'Imprint';
$string['imprintpagetitlesetting'] = 'Imprint page title';
$string['imprintpagetitlesetting_desc'] = 'In this setting, you can define the title of the imprint page. This text will be used as link text to the imprint page as well if you configure \'Imprint link position\' accordingly.';
// ... ... Setting: Imprint link position.
$string['imprintlinkpositionnone'] = 'Do not automatically show a link to the imprint page';
$string['imprintlinkpositionfootnote'] = 'Add a link to the imprint page to the footnote';
$string['imprintlinkpositionfooter'] = 'Add a link to the imprint page to the footer (questionmark) icon';
$string['imprintlinkpositionboth'] = 'Add a link to the imprint page to the footnote and to the footer (questionmark) icon';
$string['imprintlinkpositionsetting'] = 'Imprint link position';
$string['imprintlinkpositionsetting_desc'] = 'In this setting, you can configure if a link to the imprint page should be added automatically to the Moodle page. If you do not want to show a link automatically, you can add a link to {$a->url} from anywhere in Moodle manually.';
// ... Section: Contact page.
$string['contactheading'] = 'Contact';
// ... ... Setting: Enable contact page.
$string['enablecontactsetting'] = 'Enable contact page';
$string['contactdisabled'] = 'The contact page is disabled for this site. There is nothing to see here.';
// ... ... Setting: Contact page content.
$string['contactcontentsetting'] = 'Contact page content';
$string['contactcontentsetting_desc'] = 'In this setting, you can add rich text content which will be shown on a contact page (which is not the same as the built-in Moodle \'Contact site support\' page).';
// ... ... Setting: Contact page title.
$string['contactpagetitledefault'] = 'Contact';
$string['contactpagetitlesetting'] = 'Contact page title';
$string['contactpagetitlesetting_desc'] = 'In this setting, you can define the title of the contact page. This text will be used as link text to the contact page as well if you configure \'Contact page link position\' accordingly.';
// ... ... Setting: Contact page link position.
$string['contactlinkpositionnone'] = 'Do not automatically show a link to the contact page';
$string['contactlinkpositionfootnote'] = 'Add a link to the contact page to the footnote';
$string['contactlinkpositionfooter'] = 'Add a link to the contact page to the footer (questionmark) icon';
$string['contactlinkpositionboth'] = 'Add a link to the contact page to the footnote and to the footer (questionmark) icon';
$string['contactlinkpositionsetting'] = 'Contact page link position';
$string['contactlinkpositionsetting_desc'] = 'In this setting, you can configure if a link to the contact page should be added automatically to the Moodle page. If you do not want to show a link automatically, you can add a link to {$a->url} from anywhere in Moodle manually.';
// ... Section: Help page.
$string['helpheading'] = 'Help';
// ... ... Setting: Enable help page.
$string['enablehelpsetting'] = 'Enable help page';
$string['helpdisabled'] = 'The help page is disabled for this site. There is nothing to see here.';
// ... ... Setting: Help page content.
$string['helpcontentsetting'] = 'Help page content';
$string['helpcontentsetting_desc'] = 'In this setting, you can add rich text content which will be shown on a help page.';
// ... ... Setting: Help page title.
$string['helppagetitledefault'] = 'Help';
$string['helppagetitlesetting'] = 'Help page title';
$string['helppagetitlesetting_desc'] = 'In this setting, you can define the title of the help page. This text will be used as link text to the help page as well if you configure \'Help page link position\' accordingly.';
// ... ... Setting: Help page link position.
$string['helplinkpositionnone'] = 'Do not automatically show a link to the help page';
$string['helplinkpositionfootnote'] = 'Add a link to the help page to the footnote';
$string['helplinkpositionfooter'] = 'Add a link to the help page to the footer (questionmark) icon';
$string['helplinkpositionboth'] = 'Add a link to the help page to the footnote and to the footer (questionmark) icon';
$string['helplinkpositionsetting'] = 'Help page link position';
$string['helplinkpositionsetting_desc'] = 'In this setting, you can configure if a link to the help page should be added automatically to the Moodle page. If you do not want to show a link automatically, you can add a link to {$a->url} from anywhere in Moodle manually.';
// ... Section: Maintenance page.
$string['maintenanceheading'] = 'Maintenance';
// ... ... Setting: Enable maintenance page.
$string['enablemaintenancesetting'] = 'Enable maintenance information page';
$string['maintenancedisabled'] = 'The maintenance information page is disabled for this site. There is nothing to see here.';
// ... ... Setting: Maintenance page content.
$string['maintenancecontentsetting'] = 'Maintenance information page content';
$string['maintenancecontentsetting_desc'] = 'In this setting, you can add rich text content which will be shown on a maintenance information page (which is not the same as the built-in Moodle maintenance mode page).';
// ... ... Setting: Maintenance page title.
$string['maintenancepagetitledefault'] = 'Maintenance';
$string['maintenancepagetitlesetting'] = 'Maintenance information page title';
$string['maintenancepagetitlesetting_desc'] = 'In this setting, you can define the title of the maintenance information page. This text will be used as link text to the maintenance information page as well if you configure \'Maintenance information page link position\' accordingly.';
// ... ... Setting: Maintenance page link position.
$string['maintenancelinkpositionnone'] = 'Do not automatically show a link to the maintenance information page';
$string['maintenancelinkpositionfootnote'] = 'Add a link to the maintenance information page to the footnote';
$string['maintenancelinkpositionfooter'] = 'Add a link to the maintenance information page to the footer (questionmark) icon';
$string['maintenancelinkpositionboth'] = 'Add a link to the maintenance information page to the footnote and to the footer (questionmark) icon';
$string['maintenancelinkpositionsetting'] = 'Maintenance information page link position';
$string['maintenancelinkpositionsetting_desc'] = 'In this setting, you can configure if a link to the maintenance information page should be added automatically to the Moodle page. If you do not want to show a link automatically, you can add a link to {$a->url} from anywhere in Moodle manually.';
// ... Section: Generic page 1.
$string['page1heading'] = 'Generic page 1';
// ... ... Setting: Enable generic page 1.
$string['enablepage1setting'] = 'Enable generic page 1';
$string['page1disabled'] = 'The generic page 1 is disabled for this site. There is nothing to see here.';
// ... ... Setting: Generic page 1 content.
$string['page1contentsetting'] = 'Generic page 1 content';
$string['page1contentsetting_desc'] = 'In this setting, you can add rich text content which will be shown on the generic page 1.';
// ... ... Setting: Generic page 1 title.
$string['page1pagetitledefault'] = 'Generic page 1';
$string['page1pagetitlesetting'] = 'Generic page 1 title';
$string['page1pagetitlesetting_desc'] = 'In this setting, you can define the title of the generic page 1. This text will be used as link text to the generic page 1 as well if you configure \'Generic page 1 link position\' accordingly.';
// ... ... Setting: Generic page 1 link position.
$string['page1linkpositionnone'] = 'Do not automatically show a link to the generic page 1';
$string['page1linkpositionfootnote'] = 'Add a link to the generic page 1 to the footnote';
$string['page1linkpositionfooter'] = 'Add a link to the generic page 1 to the footer (questionmark) icon';
$string['page1linkpositionboth'] = 'Add a link to the generic page 1 to the footnote and to the footer (questionmark) icon';
$string['page1linkpositionsetting'] = 'Generic page 1 link position';
$string['page1linkpositionsetting_desc'] = 'In this setting, you can configure if a link to the generic page 1 should be added automatically to the Moodle page. If you do not want to show a link automatically, you can add a link to {$a->url} from anywhere in Moodle manually.';
// ... Section: Generic page 2.
$string['page2heading'] = 'Generic page 2';
// ... ... Setting: Enable generic page 2.
$string['enablepage2setting'] = 'Enable generic page 2';
$string['page2disabled'] = 'The generic page 2 is disabled for this site. There is nothing to see here.';
// ... ... Setting: Generic page 2 content.
$string['page2contentsetting'] = 'Generic page 2 content';
$string['page2contentsetting_desc'] = 'In this setting, you can add rich text content which will be shown on the generic page 2.';
// ... ... Setting: Generic page 2 title.
$string['page2pagetitledefault'] = 'Generic page 2';
$string['page2pagetitlesetting'] = 'Generic page 2 title';
$string['page2pagetitlesetting_desc'] = 'In this setting, you can define the title of the generic page 2. This text will be used as link text to the generic page 2 as well if you configure \'Generic page 2 link position\' accordingly.';
// ... ... Setting: Generic page 2 link position.
$string['page2linkpositionnone'] = 'Do not automatically show a link to the generic page 2';
$string['page2linkpositionfootnote'] = 'Add a link to the generic page 2 to the footnote';
$string['page2linkpositionfooter'] = 'Add a link to the generic page 2 to the footer (questionmark) icon';
$string['page2linkpositionboth'] = 'Add a link to the generic page 2 to the footnote and to the footer (questionmark) icon';
$string['page2linkpositionsetting'] = 'Generic page 2 link position';
$string['page2linkpositionsetting_desc'] = 'In this setting, you can configure if a link to the generic page 2 should be added automatically to the Moodle page. If you do not want to show a link automatically, you can add a link to {$a->url} from anywhere in Moodle manually.';
// ... Section: Generic page 3.
$string['page3heading'] = 'Generic page 3';
// ... ... Setting: Enable generic page 3.
$string['enablepage3setting'] = 'Enable generic page 3';
$string['page3disabled'] = 'The generic page 3 is disabled for this site. There is nothing to see here.';
// ... ... Setting: Generic page 3 content.
$string['page3contentsetting'] = 'Generic page 3 content';
$string['page3contentsetting_desc'] = 'In this setting, you can add rich text content which will be shown on the generic page 3.';
// ... ... Setting: Generic page 3 title.
$string['page3pagetitledefault'] = 'Generic page 3';
$string['page3pagetitlesetting'] = 'Generic page 3 title';
$string['page3pagetitlesetting_desc'] = 'In this setting, you can define the title of the generic page 3. This text will be used as link text to the generic page 3 as well if you configure \'Generic page 3 link position\' accordingly.';
// ... ... Setting: Generic page 3 link position.
$string['page3linkpositionnone'] = 'Do not automatically show a link to the generic page 3';
$string['page3linkpositionfootnote'] = 'Add a link to the generic page 3 to the footnote';
$string['page3linkpositionfooter'] = 'Add a link to the generic page 3 to the footer (questionmark) icon';
$string['page3linkpositionboth'] = 'Add a link to the generic page 3 to the footnote and to the footer (questionmark) icon';
$string['page3linkpositionsetting'] = 'Generic page 3 link position';
$string['page3linkpositionsetting_desc'] = 'In this setting, you can configure if a link to the generic page 3 should be added automatically to the Moodle page. If you do not want to show a link automatically, you can add a link to {$a->url} from anywhere in Moodle manually.';

// Settings: Info banners tab.
$string['infobannertab'] = 'Info banner';
// ... Section: Info banners.
$string['infobannerheading'] = 'Info banner {$a->no}';
$string['infobannerpageloginpage'] = 'Login page';
$string['infobannermodeperpetual'] = 'Perpetual';
$string['infobannermodetimebased'] = 'Time controlled';
$string['bootstrapprimarycolor'] = 'Primary color';
$string['bootstrapsecondarycolor'] = 'Secondary color';
$string['bootstrapsuccesscolor'] = 'Success color';
$string['bootstrapdangercolor'] = 'Danger color';
$string['bootstrapwarningcolor'] = 'Warning color';
$string['bootstrapinfocolor'] = 'Info color';
$string['bootstraplightcolor'] = 'Light color';
$string['bootstrapdarkcolor'] = 'Dark color';
$string['bootstrapnone'] = 'No Bootstrap color';
$string['infobannerclose'] = 'Close';
$string['infobannerdismissreset'] = 'Reset visibility of dismissed info banner';
$string['infobannerdismissresetbutton'] = 'Reset visibility of info banner {$a->no}';
$string['infobannerdismissconfirm'] = 'Do you really want to reset the visibility of info banner {$a->no} and want to re-show it for all users who have dismissed it?';
$string['infobannerdismisssuccess'] = 'The visibility of info banner {$a->no} has been reset';
$string['infobannerdismissfail'] = 'The visibility reset of info banner {$a->no} has failed for at least one user';
$string['error:infobannerdismissnonotvalid'] = 'The given info banner number is not valid';
$string['error:infobannerdismissnonotdismissible'] = 'The given info banner is not dismissible';
$string['infobannerenabledsetting'] = 'Enable info banner {$a->no}';
$string['infobannerenabledsetting_desc'] = 'With this setting, you can enable info banner {$a->no}.';
$string['infobannercontentsetting'] = 'Info banner {$a->no} content';
$string['infobannercontentsetting_desc'] = 'Here, you enter the information which should be shown within info banner {$a->no}.';
$string['infobannerpagessetting'] = 'Page layouts to display info banner {$a->no} on';
$string['infobannerpagessetting_desc'] = 'With this setting, you can select the page layouts on which info banner {$a->no} should be displayed.';
$string['infobannerbsclasssetting'] = 'Info banner {$a->no} Bootstrap class';
$string['infobannerbsclasssetting_desc'] = 'With this setting, you can select the Bootstrap style with which info banner {$a->no} should be displayed. If you choose the \'No Bootstrap color\' option, the info banner will be output without any particular Bootstrap color which gives you the freedom to style the banner yourself within the rich-text editor.';
$string['infobannerordersetting'] = 'Info banner {$a->no} order position';
$string['infobannerordersetting_desc'] = 'With this setting, you define the order position of info banner {$a->no}. By default, the info banners are ordered from top to bottom like you see them on this settings page here. However, you can decide to assign another order position with this setting. If you assign the same order position to two or more information banners, they will be ordered again according to the order on this settings page.';
$string['infobannermodesetting'] = 'Info banner {$a->no} display mode';
$string['infobannermodesetting_desc'] = 'With this setting, you can define if info banner {$a->no} should be a perpetual banner (which is always shown) or a time controlled banner (which is only shown within the configured time interval)';
$string['infobannerdismissiblesetting'] = 'Info banner {$a->no} dismissible';
$string['infobannerdismissiblesetting_desc'] = 'With this setting, you can make info banner {$a->no} dismissible. If the user clicks on the x-button in the info banner, the banner will be hidden for this user permanently. The visibility is not reset anyhow automatically, even if you change the content of the info banner. If you want to reset the visibility of the info banner, click the \'Reset visibility\' button below.';
$string['infobannerstartsetting'] = 'Info banner {$a->no} start time';
$string['infobannerstartsetting_desc'] = 'With this setting, you can define from when on info banner {$a->no} should be displayed. The configured time is interpreted as server time, not as user time.';
$string['infobannerendsetting'] = 'Info banner {$a->no} end time';
$string['infobannerendsetting_desc'] = 'With this setting, you can define until when info banner {$a->no} should be displayed. The configured time is interpreted as server time, not as user time.';
// Settings: Advertisement tiles tab.
$string['tilestab'] = 'Advertisement tiles';
// ... Section: Advertisement tiles general.
$string['tilesgeneralheading'] = 'Advertisement tiles general';
$string['tilecolumnssetting'] = 'Number of advertisement tile columns per row';
$string['tilecolumnssetting_desc'] = 'Here, you define the number of columns per row in the presented grid of advertisement tiles. Please note that this number of columns applies to desktop / larger screens. On smaller screens and mobile screens, the advertisement tile columns are automatically wrapped.';
$string['tilefrontpagepositionsetting'] = 'Position of the advertisement tiles on site home';
$string['tilefrontpagepositionsetting_desc'] = 'Advertisement tiles are shown on site home only. With this setting, you control if the advertisement tiles are displayed before the site home content or after the site home content. If you want to show only the advertisement tiles on site home and nothing else, all other site home content can be removed by changing the <a href="{$a->url}">site home settings</a>.';
$string['tilefrontpagepositionsetting_before'] = 'Before the site home content';
$string['tilefrontpagepositionsetting_after'] = 'After the site home content';
$string['tileheightsetting'] = 'Advertisement tiles height';
$string['tileheightsetting_desc'] = 'With this setting, you control the height of the advertisement tiles. The configured height is the minimum height of each tile. If a tile\'s content is higher than this configured height, the whole row of tiles will be automatically made higher as needed.';
// ... Section: Advertisement tiles.
$string['tileheading'] = 'Advertisement tile {$a->no}';
$string['tilebackgroundimagepositionsetting'] = 'Advertisement tile {$a->no} background image position';
$string['tilebackgroundimagepositionsetting_desc'] = 'With this setting, you control the positioning of the background image within the advertisement tile {$a->no} container. The first value is the horizontal position, the second value is the vertical position.';
$string['tilebackgroundimagesetting'] = 'Advertisement tile {$a->no} background image';
$string['tilebackgroundimagesetting_desc'] = 'Here, you can upload an image file which will be shown as background image behind the content of the advertisement tile {$a->no}. Please make sure or check that the content is still readable on the background image. This is an optional setting, the advertisement tile will work even if you do not upload any background image.';
$string['tilecontentsetting'] = 'Advertisement tile {$a->no} content';
$string['tilecontentsetting_desc'] = 'Here, you enter the content which should be displayed in the advertisement tile {$a->no}. The content is displayed in the middle of the tile. This is an optional setting, the advertisement tile will be shown even if you do not set any content.';
$string['tilecontentstylesetting'] = 'Advertisement tile {$a->no} content style';
$string['tilecontentstylesetting_dark'] = 'Dark (black font color for light background images)';
$string['tilecontentstylesetting_darkshadow'] = 'Dark & Shadow (black font color with a light shadow for light background images)';
$string['tilecontentstylesetting_desc'] = 'Here, you can modify the style of the content of advertisement tile {$a->no}. By default, the content style is controlled by the style which you set in the rich-text editor above. However, to allow consistent and easy styling especially when using text on background images, you can override the style here.';
$string['tilecontentstylesetting_nochange'] = 'No change (control all styling in the rich-text editor)';
$string['tilecontentstylesetting_light'] = 'Light (white font color for dark background images)';
$string['tilecontentstylesetting_lightshadow'] = 'Light & Shadow (white font color with a dark shadow for dark background images)';
$string['tileenabledsetting'] = 'Enable advertisement tile {$a->no}';
$string['tileenabledsetting_desc'] = 'With this setting, you can enable advertisement tile {$a->no}.';
$string['tilelinksetting'] = 'Advertisement tile {$a->no} link URL';
$string['tilelinksetting_desc'] = 'Here, you can set a (Moodle-internal or external) URL which will be offered as link button at the end of the advertisement tile {$a->no}. This is an optional setting, the advertisement tile will work even if you do not set any link URL.';
$string['tilelinktitlefallback'] = 'Link';
$string['tilelinktitlesetting'] = 'Advertisement tile {$a->no} link title';
$string['tilelinktitlesetting_desc'] = 'Here, you can set a link title which is used as label of the link button as soon as you set a link URL in the advertisement tile {$a->no}. Please note that if you set a link URL but do not set a link title, the link button will just be labeled with \'Link\'.';
$string['tilelinktargetsetting'] = 'Advertisement tile {$a->no} link target';
$string['tilelinktargetsetting_desc'] = 'Here, you can set the link target which is set for the link button as soon as you set a link URL in the advertisement tile {$a->no}.';
$string['tilelinktargetsetting_samewindow'] = 'Same window';
$string['tilelinktargetsetting_newtab'] = 'New tab';
$string['tileordersetting'] = 'Advertisement tile {$a->no} order position';
$string['tileordersetting_desc'] = 'With this setting, you define the order position of the advertisement tile {$a->no}. By default, the advertisement tiles are ordered from top to bottom and left to right like you see them on this settings page here. However, you can decide to assign another order position with this setting. If you assign the same order position to two or more advertisement tiles, they will be ordered again according to the order on this settings page.';
$string['tiletitlesetting'] = 'Advertisement tile {$a->no} title';
$string['tiletitlesetting_desc'] = 'Here, you enter the title which should be displayed in the advertisement tile {$a->no}. This is an optional setting, the advertisement tile will be shown even if you do not set a title.';
// Settings: Slider tab.
$string['slidertab'] = 'Slider';
// ... Section: Slider general.
$string['slidergeneralheading'] = 'Slider general';
$string['slideranimationsetting'] = 'Slider animation type';
$string['slideranimationsetting_desc'] = 'With this setting, you control the slider animation. \'Slide\' applies a sliding animation, \'Fade\' applies a fading animation and \'None\' removes all animations.';
$string['slideranimationsetting_fade'] = 'Fade';
$string['slideranimationsetting_none'] = 'None';
$string['slideranimationsetting_slide'] = 'Slide';
$string['sliderarrownavsetting'] = 'Enable arrow navigation';
$string['sliderarrownavsetting_desc'] = 'With this setting, you can add navigation arrows on both sides of the slider.';
$string['sliderfrontpagepositionsetting'] = 'Position of the slider on site home';
$string['sliderfrontpagepositionsetting_desc'] = 'The slider is shown on site home only. With this setting, you control if the slider is displayed before the site home content or after the site home content. If you want to show only the slider on site home and nothing else, all other site home content can be removed by changing the <a href="{$a->url}">site home settings</a>.';
$string['sliderfrontpagepositionsetting_afterafter'] = 'After the site home content (and after the advertisement tiles)';
$string['sliderfrontpagepositionsetting_afterbefore'] = 'After the site home content (but before the advertisement tiles)';
$string['sliderfrontpagepositionsetting_beforeafter'] = 'Before the site home content (and after the advertisement tiles)';
$string['sliderfrontpagepositionsetting_beforebefore'] = 'Before the site home content (but before the advertisement tiles)';
$string['sliderindicatornavsetting'] = 'Enable slider indicator navigation';
$string['sliderindicatornavsetting_desc'] = 'With this setting, you can add navigation indicators on the bottom of the slider.';
$string['sliderintervalsetting'] = 'Slider interval speed';
$string['sliderintervalsetting_desc'] = 'With this setting, you control how long a slide is displayed in milliseconds. The minimum value is 1000 (one second) and the maximum value is 10000 (10 seconds).';
$string['sliderkeyboardsetting'] = 'Allow slider keyboard interaction';
$string['sliderkeyboardsetting_desc'] = 'With this setting, you enable keyboard inputs (arrow keys) to control the slider. Please note that disabling this lowers accessibility.';
$string['sliderpausesetting'] = 'Pause slider on mouseover';
$string['sliderpausesetting_desc'] = 'With this setting, you prevent the slider from cycling through the slides when a user hovers over a slide. Please note that disabling this lowers accessibility.';
$string['sliderridesetting'] = 'Cycle through slides';
$string['sliderridesetting_desc'] = 'With this setting, you control the cycling behaviour of the slider. \'On page load\' begins cycling through slides after the page has finished loading, \'After interaction\' will start cycling after a user has interacted with the slider. \'Never\' disables the automatic cycling of slides altogether, requiring user input to cycle through slides.';
$string['sliderridesetting_afterinteraction'] = 'After interaction';
$string['sliderridesetting_never'] = 'Never';
$string['sliderridesetting_onpageload'] = 'On page load';
$string['sliderwrapsetting'] = 'Continuously cycle through slides';
$string['sliderwrapsetting_desc'] = 'With this setting, you make the slider cycling through all slides. If you disable this, the slider will stop cycling at the last slide.';
// ... Section: Slides.
$string['slideheading'] = 'Slide {$a->no}';
$string['slidebackgroundimagealtsetting'] = 'Slide {$a->no} background image alt attribute';
$string['slidebackgroundimagealtsetting_desc'] = 'Here, you can set an alt attribute for the image of slide {$a->no}. This is an optional setting, the slide will be shown even if you do not set an alt attribute. Please note that not providing an alt attribute lowers accessibility.';
$string['slidebackgroundimagesetting'] = 'Slide {$a->no} background image';
$string['slidebackgroundimagesetting_desc'] = 'Here, you can upload an image file which will be shown as background image behind the content of slide {$a->no}. Please make sure or check that the content is still readable on the background image. Please also try to make sure that the aspect ratio of all slides\' background images is equal (as the background image aspect ratio controls the height of the slide and you might want to avoid flickering when the slides are changed). This is a mandatory setting, the slide will not be shown if you do not upload any background image.';
$string['slidecaptionsetting'] = 'Slide {$a->no} caption';
$string['slidecaptionsetting_desc'] = 'Here, you enter the caption which should be displayed in slide {$a->no}. The caption is displayed at the bottom center of the slide. This is an optional setting, the slide will be shown even if you do not set a caption.';
$string['slidecontentsetting'] = 'Slide {$a->no} content';
$string['slidecontentsetting_desc'] = 'Here, you enter the content which should be displayed in slide {$a->no}. The content is displayed at the bottom center of the slide. If a caption is set, the content is displayed below the caption. Please note that the rich-text editor produces left-aligned text by default, but you might want to change that to centered text for a nicer look. Please also refrain from adding too much content to the slide and please test your content on small devices as content which overflows the slide will simply be hidden. This is an optional setting, the slide will be shown even if you do not set any content.';
$string['slidecontentstylesetting'] = 'Slide {$a->no} content style';
$string['slidecontentstylesetting_dark'] = 'Dark (black font color for light background images)';
$string['slidecontentstylesetting_darkshadow'] = 'Dark & Shadow (black font color with a light shadow for light background images)';
$string['slidecontentstylesetting_desc'] = 'Here, you can modify the style of the content of slide {$a->no}. By default, the content style is a white font color for dark background images. However, to allow consistent and easy styling on all kinds of background images, you can override the style here. Please note that this setting will overrrule the font color which you set in the rich-text editor above in any case.';
$string['slidecontentstylesetting_light'] = 'Light (white font color for dark background images)';
$string['slidecontentstylesetting_lightshadow'] = 'Light & Shadow (white font color with a dark shadow for dark background images)';
$string['slideenabledsetting'] = 'Enable slide {$a->no}';
$string['slideenabledsetting_desc'] = 'With this setting, you can enable slide {$a->no}.';
$string['slidelinksetting'] = 'Slide {$a->no} link URL';
$string['slidelinksetting_desc'] = 'Here, you can set a (Moodle-internal or external) URL which the slide content of slide {$a->no} will link to. This is an optional setting, the slide will be shown even if you do not set a link URL.';
$string['slidelinktitlesetting'] = 'Slide {$a->no} link title';
$string['slidelinktitlesetting_desc'] = 'Here, you can set a link title which is presented as tooltip as soon as the user hovers over slide {$a->no}. This is an optional setting, the slide will be linked even if you do not set a link title. Please note that not providing a link title lowers accessibility.';
$string['slidelinksourcesetting'] = 'Slide {$a->no} link source';
$string['slidelinksourcesetting_desc'] = 'Here, you can control which elements of the slider link to the given link URL. You can choose between linking the background image only, linking the slide\'s text elements (caption and content) only or linking both of these.';
$string['slidelinksourcesetting_both'] = 'Background image and text elements';
$string['slidelinksourcesetting_image'] = 'Background image only';
$string['slidelinksourcesetting_text'] = 'Text elements only';
$string['slidelinktargetsetting'] = 'Slide {$a->no} link target';
$string['slidelinktargetsetting_desc'] = 'Here, you can set the link target which is set for the slide link as soon as you set a link URL in the slide {$a->no}.';
$string['slidelinktargetsetting_samewindow'] = 'Same window';
$string['slidelinktargetsetting_newtab'] = 'New tab';
$string['slideordersetting'] = 'Slide {$a->no} order position';
$string['slideordersetting_desc'] = 'With this setting, you define the order position of the slide {$a->no}. By default, the slides are ordered as you see them on this settings page here. However, you can decide to assign another order position with this setting. If you assign the same order position to two or more slides, they will be ordered again according to the order on this settings page.';

// Settings: Functionality page.
$string['configtitlefunctionality'] = 'Functionality';

// Settings: Courses tab.
$string['coursestab'] = 'Courses';
// ... Section: Course related hints.
$string['courserelatedhintsheading'] = 'Course related hints';
// ... ... Setting: Show hint for switched role setting.
$string['showswitchedroleincoursesetting'] = 'Show hint for switched role';
$string['showswitchedroleincoursesetting_desc'] = 'With this setting a hint will appear in the course header if the user has switched the role in the course. By default, this information is only displayed right near the user\'s avatar in the user menu. By enabling this option, you can show this information - together with a link to switch back - within the course page as well.';
$string['switchedroleto'] = 'You are viewing this course currently with the role: <strong>{$a->role}</strong>';
// ... ... Setting: Show hint for hidden course.
$string['showhintcoursehiddensetting'] = 'Show hint in hidden courses';
$string['showhintcoursehiddensetting_desc'] = 'With this setting a hint will appear in the course header as long as the visibility of the course is hidden. This helps to identify the visibility state of a course at a glance without the need for looking at the course settings.';
$string['showhintcoursehiddengeneral'] = 'This course is currently <strong>hidden</strong>. Only enrolled teachers can access this course when hidden.';
$string['showhintcoursehiddensettingslink'] = 'You can change the visibility in the <a href="{$a->url}">course settings</a>.';
// ... ... Setting: Show hint for forum notifications in hidden courses.
$string['showhintforumnotificationssetting'] = 'Show hint for forum notifications in hidden courses';
$string['showhintforumnotificationssetting_desc'] = 'With this setting a hint will not only appear in the course header but also in forums as long as the visibility of the course is hidden. This is to clarify that notifications within a forum are not send to students and to help the teachers understand this circumstance.';
$string['showhintforumnotifications'] = 'This course is currently <strong>hidden</strong>. This means that <strong>students will not be notified</strong> online or by email of any messages you post in this forum.';
// ... ... Setting: Show hint for guest access.
$string['showhintcoursguestaccesssetting'] = 'Show hint for guest access';
$string['showhintcourseguestaccesssetting_desc'] = 'With this setting a hint will appear in the course header when a user is accessing it with the guest access feature. If the course provides an active self enrolment, a link to that page is also presented to the user.';
$string['showhintcourseguestaccessgeneral'] = 'You are currently viewing this course as <strong>{$a->role}</strong>.';
$string['showhintcourseguestaccesslink'] = 'To have full access to the course, you can <a href="{$a->url}">self enrol into this course</a>.';
// ... ... Setting: Show hint for unrestricted self enrolment.
$string['showhintcourseselfenrolsetting'] = 'Show hint for self enrolment without enrolment key';
$string['showhintcourseselfenrolsetting_desc'] = 'With this setting a hint will appear in the course header if the course is visible and an enrolment without enrolment key is currently possible.';
$string['showhintcourseselfenrolstartcurrently'] = 'This course is currently visible to everyone and <strong>self enrolment without an enrolment key</strong> is possible.';
$string['showhintcourseselfenrolstartfuture'] = 'This course is currently visible to everyone and <strong>self enrolment without an enrolment key</strong> is planned to become possible.';
$string['showhintcourseselfenrolunlimited'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment indefinitely.';
$string['showhintcourseselfenroluntil'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment until {$a->until}.';
$string['showhintcourseselfenrolfrom'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment from {$a->from} on.';
$string['showhintcourseselfenrolsince'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment currently.';
$string['showhintcourseselfenrolfromuntil'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment from {$a->from} until {$a->until}.';
$string['showhintcourseselfenrolsinceuntil'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment until {$a->until}.';
$string['showhintcourseselfenrolinstancecallforaction'] = 'If you don\'t want any Moodle user to have access to this course freely, please restrict the self enrolment settings.';

// Settings: Accessibility page.
$string['configtitleaccessibility'] = 'Accessibility';

// Settings: Administration tab.
$string['administrationtab'] = 'Administration';
// ... Section: Course management.
$string['coursemanagementheading'] = 'Course management';
// ... ... Setting: Show view course icon in course management.
$string['showviewcourseiconincoursemgntsetting'] = 'Show view course icon';
$string['showviewcourseiconincoursemgntsesetting_desc'] = 'By default, on the <a href="{$a}">course management page</a>, Moodle requires you to either open the course details or to pass through the course settings before you can click an additional UI element to view the course. By enabling this setting, you can add a \'View course\' icon directly to the category listing on the course management page.';

// Settings: Declaration tab.
$string['accessibilitydeclarationtab'] = 'Declaration';
// ... Section: Declaration of accessibility page.
$string['accessibilitydeclarationheading'] = 'Declaration of accessibility';
// ... ... Setting: Enable declaration of accessibility page.
$string['enableaccessibilitydeclarationsetting'] = 'Enable declaration of accessibility page';
$string['enableaccessibilitydeclarationsetting_desc'] = 'With this setting, you can enable a declaration of accessibility page. It will behave just like the <a href="{$a->url}">other static pages</a> in Boost Union.';
$string['accessibilitydeclarationdisabled'] = 'The declaration of accessibility information page is disabled for this site. There is nothing to see here.';
// ... ... Setting: Declaration of accessibility page content.
$string['accessibilitydeclarationcontentsetting'] = 'Declaration of accessibility page content';
$string['accessibilitydeclarationcontentsetting_desc'] = 'In this setting, you can add rich text content which will be shown on a declaration of accessibility page.';
// ... ... Setting: Declaration of accessibility page title.
$string['accessibilitydeclarationpagetitledefault'] = 'Declaration of accessibility';
$string['accessibilitydeclarationpagetitlesetting'] = 'Declaration of accessibility page title';
$string['accessibilitydeclarationpagetitlesetting_desc'] = 'In this setting, you can define the title of the declaration of accessibility page. This text will be used as link text to the declaration of accessibility page as well if you configure \'Declaration of accessibility page link position\' accordingly.';
// ... ... Setting: Declaration of accessibility page link position.
$string['accessibilitydeclarationlinkpositionnone'] = 'Do not automatically show a link to the declaration of accessibility page';
$string['accessibilitydeclarationlinkpositionfootnote'] = 'Add a link to the declaration of accessibility page to the footnote';
$string['accessibilitydeclarationlinkpositionfooter'] = 'Add a link to the declaration of accessibility page to the footer (questionmark) icon';
$string['accessibilitydeclarationlinkpositionboth'] = 'Add a link to the declaration of accessibility page to the footnote and to the footer (questionmark) icon';
$string['accessibilitydeclarationlinkpositionsetting'] = 'Declaration of accessibility page link position';
$string['accessibilitydeclarationlinkpositionsetting_desc'] = 'In this setting, you can configure if a link to the declaration of accessibility page should be added automatically to the Moodle page. If you do not want to show a link automatically, you can add a link to {$a->url} from anywhere in Moodle manually.';
// Settings: Support page tab.
$string['accessibilitysupporttab'] = 'Support page';
// ... Section: Accessibility support page.
$string['accessibilitysupportheading'] = 'Accessibility support page';
// ... ... Setting: Enable accessibility support page.
$string['enableaccessibilitysupportsetting'] = 'Enable accessibility support page';
$string['enableaccessibilitysupportsetting_desc'] = 'With this setting, you can enable a accessibility support page. It will behave similar to <a href="{$a->url}">Moodle core\'s site support page</a>.';
$string['accessibilitysupportdisabled'] = 'The accessibility support page is disabled for this site. There is nothing to see here.';
$string['accessibilitysupportdefaultsubject'] = 'Accessibility feedback';
$string['accessibilitysupportusermailsubject'] = 'Accessibility support request';
$string['accessibilitysupportmessagesent'] = 'Your accessibility support request was sent.';
$string['accessibilitysupportmessagenotsent'] = 'Unfortunately your accessibility support request could not be sent.';
$string['accessibilitysupportmessagetryagain'] = 'Please try again later.';
$string['accessibilitysupportmessagetryalternative'] = 'Please try again later or sent an email directly to <a href="mailto:{$a}">{$a}</a>.';
// ... ... Setting: Accessibility support page content.
$string['accessibilitysupportcontentsetting'] = 'Accessibility support page content';
$string['accessibilitysupportcontentsetting_desc'] = 'In this setting, you can add rich text content which will be shown on the accessibility support page, together with a form to send accessibility feedback or to report an accessibility barrier.';
$string['accessibilitysupportcontentdefault'] = '<p>If you have any accessibility feedback or want to report a barrier, please use the form below.</p><p>Do you work with assistive technology such as screen readers, magnifiers, voice control or speech recognition software? If yes, please specify which ones. To help us to process your request, you can allow the form to automatically send the following information along with your message: The URL on which you were when you opened this support form (this is called \'referrer\') and some information about your browser.</p>';
// ... ... Setting: Accessibility support page title.
$string['accessibilitysupportpagetitledefault'] = 'Accessibility support';
$string['accessibilitysupportpagetitlesetting'] = 'Accessibility support page title';
$string['accessibilitysupportpagetitlesetting_desc'] = 'In this setting, you can define the title of the accessibility support page. This text will be used as link text to the accessibility support information page as well if you configure \'Accessibility support page link position\' accordingly.';
// ... ... Setting: Accessibility support page link position.
$string['accessibilitysupportlinkpositionnone'] = 'Do not automatically show a link to the accessibility support page';
$string['accessibilitysupportlinkpositionfootnote'] = 'Add a link to the accessibility support page to the footnote';
$string['accessibilitysupportlinkpositionfooter'] = 'Add a link to the accessibility support page to the footer (questionmark) icon';
$string['accessibilitysupportlinkpositionboth'] = 'Add a link to the accessibility support page to the footnote and to the footer (questionmark) icon';
$string['accessibilitysupportlinkpositionsetting'] = 'Accessibility support page link position';
$string['accessibilitysupportlinkpositionsetting_desc'] = 'In this setting, you can configure if a link to the accessibility support page should be added automatically to the Moodle page. If you do not want to show a link automatically, you can add a link to {$a->url} from anywhere in Moodle manually.';
// ... ... Setting: Allow accessibility support page without login.
$string['allowaccessibilitysupportwithoutlogin'] = 'Allow accessibility support page without login';
$string['allowaccessibilitysupportwithoutlogin_desc'] = 'If this setting is enabled, the accessibility support page will be shown to users who are not logged in. If this setting is disabled, only logged in users will be allowed to access the accessibility support page.';
// ... ... Setting: Enable accessibility button.
$string['enableaccessibilitysupportfooterbuttonsetting'] = 'Enable accessibility support footer button';
$string['enableaccessibilitysupportfooterbuttonsetting_desc'] = 'With this setting, you can add a link to the accessibility support page as a floating accessibility icon above the footer (questionmark) icon.';
// ... ... Setting: Allow anonymous support page submissions
$string['allowanonymoussubmitssetting'] = 'Allow anonymous support page submissions';
$string['allowanonymoussubmitssetting_desc'] = 'With this setting, you can allow the user to send the accessibility feedback anonymously through the accessibility support page. Users can then decide if they want to send feedback anonymously (without sending their username and email address) or not.';
$string['accessibilitysupportanonymouscheckbox'] = 'I prefer to send my accessibility support request anonymously';
$string['accessibilitysupportanonymoususer'] = 'Anonymous user';
$string['accessibilitysupportanonymousemail'] = 'anonymous@email.invalid';
$string['accessibilitysupportsentforanonymoususer'] = 'The user requested to send this accessibility feedback anonymously.';
// ... ... Setting: Allow sending technical information along.
$string['allowsendtechinfoalongsetting'] = 'Allow sending technical information along';
$string['allowsendtechinfoalongsetting_desc'] = 'With this setting, you can allow the user to send technical information along on the accessibility support page. Users can then decide if they want to send technical information or not.';
$string['accessibilitysupporttechinfocheckbox'] = 'I agree to send the following technical information along with my message';
$string['accessibilitysupporttechinfo'] = 'Technical information';
$string['accessibilitysupporttechinfolabel'] = 'Technical information to send along';
$string['accessibilitysupporttechinforeferrer'] = "Referrer page";
$string['accessibilitysupporttechinfosysinfo'] = 'System information';
// ... ... Setting: Accessibility support user mail.
$string['accessibilitysupportusermail'] = 'Accessibility support user mail';
$string['accessibilitysupportusermail_desc'] = 'Here you define the email address to which the accessibility support requests should be sent. If you leave this field empty, the requests will be sent to the <a href="{$a->url}">configured site support contact</a>.';
$string['accessibilitysupportuserfirstname'] = 'Accessibility';
$string['accessibilitysupportuserlastname'] = 'support';
// ... ... Setting: Accessibility support page screenreader title
$string['accessibilitysupportpagesrlinktitledefault'] = 'Get accessibility support';
$string['accessibilitysupportpagesrlinktitlesetting'] = 'Accessibility support page screenreader link title';
$string['accessibilitysupportpagesrlinktitlesetting_desc'] = 'In this setting, you can define the screenreader link title for the accessibility support page. This text will be used as link text which is only shown to screenreaders.';
// ... ... Setting: Add re-captcha to accessibility support page
$string['accessibilitysupportrecaptcha'] = 'Add re-captcha to accessibility support page';
$string['accessibilitysupportrecaptcha_desc'] = 'With this setting, you control if a re-captcha is added to the accessibility support page. This is to prevent spam and abuse of the accessibility support form, just like it is done within <a href="{$a->support}">Moodle core\'s support form</a>. However, adding re-captchas add an additional accessibility barrier for users who use screenreaders or other assistive technologies which might be counter-productive in this case. Thus, please choose wisely if you want to enable this setting. Please also note that, even if enabled, the re-captcha is not shown until you set the necessary <a href="{$a->settings}">API keys in the authentication settings</a>.';

// Settings: Flavours page.
$string['configtitleflavours'] = 'Flavours';
$string['flavoursactivityiconcoloradministration'] = 'Activity icon color for "Administration"';
$string['flavoursactivityiconcoloradministration_help'] = 'With this setting, the flavour will override the activity icon "Administration" color which is configured in Boost Union\'s look settings.';
$string['flavoursactivityiconcolorassessment'] = 'Activity icon color for "Assessment"';
$string['flavoursactivityiconcolorassessment_help'] = 'With this setting, the flavour will override the activity icon "Assessment" color which is configured in Boost Union\'s look settings.';
$string['flavoursactivityiconcolorcollaboration'] = 'Activity icon color for "Collaboration"';
$string['flavoursactivityiconcolorcollaboration_help'] = 'With this setting, the flavour will override the activity icon "Collaboration" color which is configured in Boost Union\'s look settings.';
$string['flavoursactivityiconcolorcommunication'] = 'Activity icon color for "Communication"';
$string['flavoursactivityiconcolorcommunication_help'] = 'With this setting, the flavour will override the activity icon "Communication" color which is configured in Boost Union\'s look settings.';
$string['flavoursactivityiconcolorcontent'] = 'Activity icon color for "Content"';
$string['flavoursactivityiconcolorcontent_help'] = 'With this setting, the flavour will override the activity icon "Content" color which is configured in Boost Union\'s look settings.';
$string['flavoursactivityiconcolorinteractivecontent'] = 'Activity icon color for "Interactive content"';
$string['flavoursactivityiconcolorinteractivecontent_help'] = 'With this setting, the flavour will override the activity icon "Interactive content" color which is configured in Boost Union\'s look settings.';
$string['flavoursactivityiconcolorinterface'] = 'Activity icon color for "Interface"';
$string['flavoursactivityiconcolorinterface_help'] = 'With this setting, the flavour will override the activity icon "Interface" color which is configured in Boost Union\'s look settings.';
$string['flavoursappliesto'] = 'Applies to';
$string['flavoursapplytocategories'] = 'Apply to course categories';
$string['flavoursapplytocategories_help'] = 'Here, you define if this flavour should be applied to course categories.';
$string['flavoursapplytocategories_ids'] = 'Course categories';
$string['flavoursapplytocategories_ids_help'] = 'Here, you define one or more particular course categories which this flavour should be applied to. As soon as the rendered Moodle page is located within one of the configured course categories, the flavour is applied.';
$string['flavoursapplytocohorts'] = 'Apply to cohorts';
$string['flavoursapplytocohorts_help'] = 'Here, you define if this flavour should be applied to cohorts.';
$string['flavoursapplytocohorts_ids'] = 'Cohorts';
$string['flavoursapplytocohorts_ids_help'] = 'Here, you define one or more particular cohorts which this flavour should be applied to. As soon as the user is a member of one of the configured cohorts, the flavour is applied.<br /><br />Please note that, if you define more than one cohorts, there is no need for the user to be a member of all of them at the same time.<br /><br />Please also note that at the current state of implementation category cohorts are treated just as if they were system cohorts.';
$string['flavoursbackgroundimage'] = 'Background image';
$string['flavoursbackgroundimage_help'] = 'With this setting, the flavour will override the background image which is configured in Boost Union\'s look settings.';
$string['flavoursbackgroundimageposition'] = 'Background image position';
$string['flavoursbackgroundimageposition_help'] = 'With this setting, the flavour will override the background image position which is configured in Boost Union\'s look settings.';
$string['flavoursbacktooverview'] = 'Back to flavour overview';
$string['flavoursbootstrapcolordanger'] = 'Bootstrap color for "Danger"';
$string['flavoursbootstrapcolordanger_help'] = 'With this setting, the flavour will override the Bootstrap "danger" color which is configured in Boost Union\'s look settings.';
$string['flavoursbootstrapcolorinfo'] = 'Bootstrap color for "Info"';
$string['flavoursbootstrapcolorinfo_help'] = 'With this setting, the flavour will override the Bootstrap info "color" which is configured in Boost Union\'s look settings.';
$string['flavoursbootstrapcolorsuccess'] = 'Bootstrap color for "Success"';
$string['flavoursbootstrapcolorsuccess_help'] = 'With this setting, the flavour will override the Bootstrap "success" color which is configured in Boost Union\'s look settings.';
$string['flavoursbootstrapcolorwarning'] = 'Bootstrap color for "Warning"';
$string['flavoursbootstrapcolorwarning_help'] = 'With this setting, the flavour will override the Bootstrap "warning" color which is configured in Boost Union\'s look settings.';
$string['flavoursbrandcolor'] = 'Brand color';
$string['flavoursbrandcolor_help'] = 'With this setting, the flavour will override the brand color which is configured in Boost Union\'s look settings.';
$string['flavourscreateflavour'] = 'Create flavour';
$string['flavourscustomscss'] = 'Raw SCSS';
$string['flavourscustomscss_help'] = 'With this setting, you can write custom SCSS for the flavour. It will be appended to the stack of CSS code which is shipped to the browser as soon as the flavour applies.';
$string['flavourscustomscsspre'] = 'Raw initial SCSS';
$string['flavourscustomscsspre_help'] = 'With this setting, you can write custom initial SCSS for the flavour. It will be used when building the CSS code which is shipped to the browser as soon as the flavour applies.';
$string['flavoursdelete'] = 'Delete';
$string['flavoursdeleteflavour'] = 'Delete flavour';
$string['flavoursdeleteconfirmation'] = 'Do you really want to delete the flavour <em>{$a}</em>?';
$string['flavoursdescription'] = 'Description';
$string['flavoursdescription_help'] = 'The flavour\'s description is just used internally to allow you to identify a particular flavour in the list of flavours.';
$string['flavoursedit'] = 'Edit';
$string['flavourseditflavour'] = 'Edit flavour';
$string['flavoursfavicon'] = 'Favicon';
$string['flavoursfavicon_help'] = 'With this setting, the flavour will override the favicon which is configured in Boost Union\'s look settings.';
$string['flavoursflavours'] = 'Flavours';
$string['flavoursgeneralsettings'] = 'General settings';
$string['flavoursincludesubcategories'] = 'Include subcategories';
$string['flavoursincludesubcategories_help'] = 'If checked, the flavour will also be applied to the subcategories of the chosen categories.';
$string['flavourslogo'] = 'Logo';
$string['flavourslogo_help'] = 'With this setting, the flavour will override the logo which is configured in Boost Union\'s look settings.';
$string['flavourslogocompact'] = 'Compact logo';
$string['flavourslogocompact_help'] = 'With this setting, the flavour will override the logo which is configured in Boost Union\'s look settings.';
$string['flavoursnavbarcolor'] = 'Navbar color';
$string['flavoursnavbarcolor_help'] = 'With this setting, the flavour will override the navbar color which is configured in Boost Union\'s look settings.';
$string['flavoursnotificationcreated'] = 'The flavour was created successfully';
$string['flavoursnotificationdeleted'] = 'The flavour was deleted successfully';
$string['flavoursnotificationedited'] = 'The flavour was edited successfully';
$string['flavoursnothingtodisplay'] = 'There aren\'t any flavours created yet. Please create your first flavour to get things going.';
$string['flavoursoverview_desc'] = '<p>Boost Union\'s flavours offer a possibility to override particular Moodle look & feel settings in particular contexts. On this page, you can create and manage flavours.</p><p>Within each flavour, you define if it should be applied to particular course categories or particular cohorts. Afterwards, during each Moodle page rendering, Boost Union checks if any flavour applies. Please note that, for each Moodle page rendering, only the first matching flavour in the list is applied and the remaining flavours are ignored. Thus, the order of the flavours on this page is key.</p><p>Please note as well that after each change which you make to the set of flavours, the theme cache is purged. This is necessary to make sure that all assets are shipped properly and up-to-date to the browser.</p>';
$string['flavourspreview'] = 'Preview';
$string['flavourspreviewflavour'] = 'Preview flavour';
$string['flavourspreviewblindtext'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Nunc id cursus metus aliquam eleifend mi in nulla. Felis imperdiet proin fermentum leo vel orci porta. Sed nisi lacus sed viverra tellus in hac habitasse. Vivamus arcu felis bibendum ut. Nisi porta lorem mollis aliquam ut porttitor. Odio euismod lacinia at quis risus sed vulputate odio. Sed felis eget velit aliquet sagittis id consectetur purus. Nec ullamcorper sit amet risus nullam eget. Pellentesque sit amet porttitor eget dolor. Cursus mattis molestie a iaculis at erat pellentesque.';
$string['flavourstitle'] = 'Title';
$string['flavourstitle_help'] = 'The flavour\'s title is just used internally to allow you to document a particular flavour in the list of flavours.';

// Settings: Smart menus page.
$string['smartmenus'] = 'Smart menus';
$string['error:smartmenusmenuitemnotfound'] = 'Smart menu item not found';
$string['error:smartmenusmenunotfound'] = 'Smart menu not found';
$string['smartmenus_desc'] = '<p>Smart menus allow site administrators to create customizable menus that can be placed in different locations on the site, such as the site main menu, bottom mobile menu, and user menu. The menus can be configured to display different types of content, including links to other pages or resources, category links, or user profile links.</p><p>Site administrators can create a new menu and specify the menu items, and display settings. The administrator can also choose where the menu will be displayed on the site and whether it should be visible to all users or only to certain user roles.</p>';
$string['smartmenusbycohort'] = 'By cohort';
$string['smartmenusbycohort_help'] = 'Restrict the visibility based on the user\'s cohorts.';
$string['smartmenusbydate'] = 'By date';
$string['smartmenusbydate_help'] = 'Restrict the visibility based on the date';
$string['smartmenusbydatefrom'] = 'From';
$string['smartmenusbydatefrom_help'] = 'Restrict the visibility before the given date is reached';
$string['smartmenusbydateuntil'] = 'Until';
$string['smartmenusbydateuntil_help'] = 'Restrict the visibility after the given date is reached';
$string['smartmenusbylanguage'] = 'By language';
$string['smartmenusbylanguage_help'] = 'Restrict the visibility based on the user\'s language';
$string['smartmenusbyrole'] = 'By role';
$string['smartmenusbyrole_help'] = 'Restrict the visibility based on the user\'s roles.';
$string['smartmenusbyadmin'] = 'Show to';
$string['smartmenusbyadmin_help'] = 'Restrict the visibility based on the fact if the user is a site admin or not.';
$string['smartmenusbyadmin_all'] = 'All users';
$string['smartmenusbyadmin_admins'] = 'Site admins only';
$string['smartmenusbyadmin_nonadmins'] = 'Non-admins only';
$string['smartmenusdynamiccoursescompletionstatus'] = 'Completion status';
$string['smartmenusdynamiccoursescompletionstatus_help'] = 'The dynamic courses menu item list will contain all courses of the user which match the selected completion status. For example, if you select \'In progress\' as the completion status, the dynamic courses menu item list will only contain courses that the current user is currently working on.';
$string['smartmenusdynamiccoursescompletionstatuscompleted'] = 'Completed';
$string['smartmenusdynamiccoursescompletionstatusenrolled'] = 'Enrolled';
$string['smartmenusdynamiccoursescompletionstatusinprogress'] = 'In progress';
$string['smartmenusdynamiccoursescoursecategory'] = 'Course category';
$string['smartmenusdynamiccoursescoursecategory_help'] = 'The dynamic courses menu item list will contain all courses from the selected course categories.';
$string['smartmenusdynamiccoursescoursecategorysubcats'] = 'Include subcategories';
$string['smartmenusdynamiccoursescoursecategorysubcats_help'] = 'If checked, the dynamic courses menu will also contain all courses from the subcategories of the selected courses categories.';
$string['smartmenusdynamiccoursesdaterange'] = 'Date range';
$string['smartmenusdynamiccoursesdaterange_help'] = 'The dynamic courses menu item list will contain all courses which fall into the selected date range.';
$string['smartmenusdynamiccoursesdaterangefuture'] = 'Future';
$string['smartmenusdynamiccoursesdaterangepast'] = 'Past';
$string['smartmenusdynamiccoursesdaterangepresent'] = 'Present';
$string['smartmenusdynamiccoursesenrolmentrole'] = 'Enrolment role';
$string['smartmenusdynamiccoursesenrolmentrole_help'] = 'The dynamic courses menu item list will contain all courses where the user is enrolled with the selected role.';
$string['smartmenusexperimental'] = 'Please note: The smart menus functionality is fully usable in the current state of implementation, but has to be <em>considered as experimental</em> due to the large amount of setting combinations which still might trigger unexpected issues. Against this background, please test your smart menus with your individual menu settings thoroughly. If you encounter any issues with smart menus, please report them on <a href="https://github.com/moodle-an-hochschulen/moodle-theme_boost_union/issues">Github</a> with clear steps to reproduce.';
$string['smartmenusgeneralsectionheader'] = 'General settings';
$string['smartmenusmenuaddnewitem'] = 'Add menu item';
$string['smartmenusmenucardform'] = 'Card form';
$string['smartmenusmenucardform_help'] = 'Select the form of the card for card-style menus, choosing between square, portrait, landscape or fullwidth.';
$string['smartmenusmenucardformfullwidth'] = 'Full width';
$string['smartmenusmenucardformlandscape'] = 'Landscape';
$string['smartmenusmenucardformportrait'] = 'Portrait';
$string['smartmenusmenucardformsquare'] = 'Square';
$string['smartmenusmenucardoverflowbehavior'] = 'Card overflow behavior';
$string['smartmenusmenucardoverflowbehavior_help'] = 'Select how the menu should behave when it overflows its container, choosing between showing a scrollbar or wrapping the overflowing items.';
$string['smartmenusmenucardoverflowbehaviornowrap'] = 'No wrap';
$string['smartmenusmenucardoverflowbehaviorwrap'] = 'Wrap';
$string['smartmenusmenucardsize'] = 'Card size';
$string['smartmenusmenucardsize_help'] = 'Select the size of the card for card-style menus, choosing between tiny, small, medium, or large.';
$string['smartmenusmenucardsizelarge'] = 'Large';
$string['smartmenusmenucardsizemedium'] = 'Medium';
$string['smartmenusmenucardsizesmall'] = 'Small';
$string['smartmenusmenucardsizetiny'] = 'Tiny';
$string['smartmenusmenucreate'] = 'Create menu';
$string['smartmenusmenucreatesuccess'] = 'Smart menu created successfully';
$string['smartmenusmenucssclass'] = 'CSS class';
$string['smartmenusmenucssclass_help'] = 'Enter a CSS class for the menu. This can be used to apply custom styling to the menu.';
$string['smartmenusmenudeleteconfirm'] = 'Are you sure you want to delete this menu from the smart menus?';
$string['smartmenusmenudeletesuccess'] = 'Smart menu deleted successfully';
$string['smartmenusmenudescription'] = 'Description';
$string['smartmenusmenudescription_help'] = 'The description of the menu. This will be primarily used as internal documentation, but you can also show it within the menu by using the \'Show description\' option.';
$string['smartmenusmenuduplicate'] = 'Duplicate menu and its items';
$string['smartmenusmenuduplicatesuccess'] = 'Menu and its menu items duplicated successfully';
$string['smartmenusmenuedit'] = 'Edit menu';
$string['smartmenusmenueditsuccess'] = 'Smart menu updated successfully';
$string['smartmenusmenuitemcardappearanceheader'] = 'Card appearance';
$string['smartmenusmenuitemcardbackgroundcolor'] = 'Card background color';
$string['smartmenusmenuitemcardbackgroundcolor_help'] = 'Select the background color for the card of the menu item';
$string['smartmenusmenuitemcardimage'] = 'Card image';
$string['smartmenusmenuitemcardimage_help'] = 'Select an image to display next to the menu item title in the card.';
$string['smartmenusmenuitemcardtextcolor'] = 'Card text color';
$string['smartmenusmenuitemcardtextcolor_help'] = 'Select the color for the card of the menu item.';
$string['smartmenusmenuitemcreate'] = 'Create menu item';
$string['smartmenusmenuitemcreatesuccess'] = 'Smart menu item created successfully';
$string['smartmenusmenuitemcssclass'] = 'CSS class';
$string['smartmenusmenuitemcssclass_help'] = 'Enter a CSS class for the menu item. This can be used to apply custom styling to the menu item.';
$string['smartmenusmenuitemdeleteconfirm'] = 'Are you sure you want to delete this menu item from the smart menu?';
$string['smartmenusmenuitemdeletesuccess'] = 'Smart menu item deleted successfully';
$string['smartmenusmenuitemlistsort'] = 'Course list sorting';
$string['smartmenusmenuitemlistsort_help'] = 'The course list will be sorted by the selected criteria and sort order. Choose between fullname, shortname, course ID and course ID number as criteria in combination with ascending and descending sort order.';
$string['smartmenusmenuitemlistsortfullnameasc'] = 'Course fullname ascending';
$string['smartmenusmenuitemlistsortfullnamedesc'] = 'Course fullname descending';
$string['smartmenusmenuitemlistsortshortnameasc'] = 'Course shortname ascending';
$string['smartmenusmenuitemlistsortshortnamedesc'] = 'Course shortname descending';
$string['smartmenusmenuitemlistsortcourseidasc'] = 'Course ID ascending';
$string['smartmenusmenuitemlistsortcourseiddesc'] = 'Course ID descending';
$string['smartmenusmenuitemlistsortcourseidnumberasc'] = 'Course ID number ascending';
$string['smartmenusmenuitemlistsortcourseidnumberdesc'] = 'Course ID number descending';
$string['smartmenusmenuitemdisplayfield'] = 'Course name presentation';
$string['smartmenusmenuitemdisplayfield_help'] = 'The course name which will be used as the title of the dynamic courses menu items. Choose between course full name and course short name';
$string['smartmenusmenuitemdisplayfieldcoursefullname'] = 'Course full name';
$string['smartmenusmenuitemdisplayfieldcourseshortname'] = 'Course short name';
$string['smartmenusmenuitemdisplayoptions'] = 'Title presentation';
$string['smartmenusmenuitemdisplayoptions_help'] = 'Choose how you want the menu item title to be displayed.';
$string['smartmenusmenuitemdisplayoptionshidetitle'] = 'Hide title text and show only icon (on all devices)';
$string['smartmenusmenuitemdisplayoptionshidetitlemobile'] = 'Hide title text and show only icon (on mobile devices)';
$string['smartmenusmenuitemdisplayoptionsshowtitleicon'] = 'Show text and icon as title';
$string['smartmenusmenuitemduplicate'] = 'Duplicate menu item';
$string['smartmenusmenuitemduplicatesuccess'] = 'Menu item duplicated successfully';
$string['smartmenusmenuitemedit'] = 'Edit menu item';
$string['smartmenusmenuitemeditsuccess'] = 'Smart menu item updated successfully';
$string['smartmenusmenuitemicon'] = 'Icon';
$string['smartmenusmenuitemicon_help'] = 'The icon to display next to the menu item title.';
$string['smartmenusmenuitemlinktarget'] = 'Link target';
$string['smartmenusmenuitemlinktarget_help'] = 'The target for the link of the menu item. The menu item link will open in this target when clicked (i.e. in the same window or in a new tab).';
$string['smartmenusmenuitemlinktargetnewtab'] = 'New tab';
$string['smartmenusmenuitemlinktargetsamewindow'] = 'Same window';
$string['smartmenusmenuitemmode'] = 'Menu item mode';
$string['smartmenusmenuitemmode_help'] = '<p>Select the mode how the menu item should be displayed within the menu.</p><ul><li>Inline: The menu item is displayed as a regular menu item within the menu. This is the default option.</li><li>Submenu: The menu item is displayed as a submenu item, which can be expanded or collapsed by clicking on the parent item. This mode is useful for building a third navigation level as well as for dynamic courses menu items, where course lists can be displayed as submenu items of this menu item. The title of this menu item is used as the text for the submenu item.</li></ul>';
$string['smartmenusmenuitemnothingtodisplay'] = 'There aren\'t any items added to this smart menu yet. Please add an item to this menu.';
$string['smartmenusmenuitemorder'] = 'Order';
$string['smartmenusmenuitemorder_help'] = 'Rearrange the position of item if needed. All menu items in the menu will be ordered by this order value.';
$string['smartmenusmenuitempresentationheader'] = 'Menu item presentation';
$string['smartmenusmenuitemresponsive'] = 'Responsive hiding';
$string['smartmenusmenuitemresponsive_help'] = 'By enabling any of these checkboxes, the menu item will be hidden on devices with the given display size.';
$string['smartmenusmenuitemresponsivedesktop'] = 'Desktop';
$string['smartmenusmenuitemresponsivemobile'] = 'Mobile';
$string['smartmenusmenuitemresponsivetablet'] = 'Tablet';
$string['smartmenusmenuitemrestriction'] = 'Access rules';
$string['smartmenusmenuitems'] = 'Menu items';
$string['smartmenusmenuitemstructureheader'] = 'Menu item structure';
$string['smartmenusmenuitemtextcount'] = 'Number of words';
$string['smartmenusmenuitemtextcount_help'] = 'Specify the maximum number of words to be displayed as title in the dynamic courses menu items. If you leave this field empty, the title will be displayed in full length.';
$string['smartmenusmenuitemtextposition'] = 'Card text position';
$string['smartmenusmenuitemtextposition_help'] = '<p>Select the position of the menu item text in relation to the card image, choosing from below image, top overlay and bottom overlay.</p><ul><li>Top overlay: Displays the menu item title over the overlay and at the top of the card.</li><li>Bottom overlay: Displays the menu item title over the overlay and at the bottom of the card.</li><li>Below image: Displays the menu item title below the card image.</li></ul>';
$string['smartmenusmenuitemtextpositionbelowimage'] = 'Below image';
$string['smartmenusmenuitemtextpositionoverlaybottom'] = 'Bottom overlay';
$string['smartmenusmenuitemtextpositionoverlaytop'] = 'Top overlay';
$string['smartmenusmenuitemtitle'] = 'Title';
$string['smartmenusmenuitemtitle_help'] = 'The title of the menu. This will be used as the label of this menu item. If you want to display a separator in the menu, choose Heading as type and use hash signs (###) as title.';
$string['smartmenusmenuitemtooltip'] = 'Tooltip';
$string['smartmenusmenuitemtooltip_help'] = 'The tooltip which will be displayed when the user hovers over the menu item.';
$string['smartmenusmenuitemtype'] = 'Menu item type';
$string['smartmenusmenuitemtype_help'] = '<p>Select the type of menu item you want to create, choosing between static, heading and dynamic courses.</p><ul><li>Static: A static menu item is simply a link to a fixed URL that does not change.</li><li>Heading: A heading menu item is used to group related menu items together under a common heading. It does not have a link and is not clickable.</li><li>Dynamic courses: A dynamic courses menu item is used to display a list of courses based on certain criteria, such as course category, course enrolment role, course completion status or date range. The content displayed in a dynamic courses menu item will update automatically as the criteria changes.</li></ul>';
$string['smartmenusmenuitemtypedynamiccourses'] = 'Dynamic courses';
$string['smartmenusmenuitemtypeheading'] = 'Heading';
$string['smartmenusmenuitemtypestatic'] = 'Static';
$string['smartmenusmenuitemurl'] = 'Menu item URL';
$string['smartmenusmenuitemurl_help'] = 'The static URL for the menu item. This is the link that will be followed when the menu item is clicked.';
$string['smartmenusmenulocation'] = 'Menu location(s)';
$string['smartmenusmenulocation_help'] = '<p>Select the location(s) where you want the menu to appear on the page:</p><ul><li>The main navigation is at the top of the page where Moodle core shows the Home, Dashboard, My courses and Site administration navigation items already.</li><li>The menu bar is located above the main navigation, at the top of the page.</li><li>The user menu can be accessed by clicking on the user avatar in the navigation bar.</li><li>The bottom bar is placed at the bottom of the screen and can be used to implement a thumb navigation for easy access to important areas, such as the dashboard, the my courses page or the home page.</li></ul><p>Please note that upon enabling the bottom bar, the hamburger icon will be replaced by your site\'s logo, because users can reach the main navigation then using the bottom bar.</p>';
$string['smartmenusmenulocationbottom'] = 'Bottom bar';
$string['smartmenusmenulocationmain'] = 'Main navigation';
$string['smartmenusmenulocationmenu'] = 'Menu bar';
$string['smartmenusmenulocationuser'] = 'User menu';
$string['smartmenusmenumode'] = 'Menu mode';
$string['smartmenusmenumode_help'] = '<p>Select the mode how the menu\'s items should be displayed.</p><ul><li>Submenu: The menu items is displayed as a submenu with the menu\'s title as parent node. This is the default option.</li><li>Inline: The menu\'s items are displayed directly in the navigation, one after another. Please note that this option is not supported for card type menus.</li></ul>';
$string['smartmenusmenumoremenubehavior'] = 'More menu behavior';
$string['smartmenusmenumoremenubehavior_help'] = '<p>Select what should happen if there are too many menus to fit in the menu location.</p><ul><li>Do not change anything: No particular behaviour will be enforced, excess menus will be moved into the \'More\' menu automatically.</li><li>Force into more menu: This mode moves the menu directly into the \'More\' menu even if there would still be space.</li><li>Keep outside of more menu: This mode keeps the menu outside of the \'More\' menu as long as possible – moving other subsequent menus to the more menu instead if needed.</li></ul><p>Please note that this setting only affects menus which are located in the main navigation or in the menu bar area.</p>';
$string['smartmenusmenumoremenubehaviorforceinto'] = 'Force into more menu';
$string['smartmenusmenumoremenubehaviorkeepoutside'] = 'Keep outside of more menu';
$string['smartmenusmenunothingtodisplay'] = 'There aren\'t any smart menus created yet. Please create your first smart menu to get things going.';
$string['smartmenusmenupresentationheader'] = 'Menu presentation';
$string['smartmenusmenushowdescription'] = 'Show description';
$string['smartmenusmenushowdescription_help'] = '<p>Select if / how the description should be shown in the menu, choosing between Never, Above, Below and Help.</p><ul><li>Never: Do not show the description in the menu and use it only for internal purposes. This is the default option.</li><li>Above: Show the description at the top of the menu\'s list of menu items.</li><li>Below: Show the description at the end of the menu\'s list of menu items.</li><li>Help: Show the description as help icon near the menu\'s list of menu items.</li></ul>';
$string['smartmenusmenushowdescriptionabove'] = 'Above';
$string['smartmenusmenushowdescriptionbelow'] = 'Below';
$string['smartmenusmenushowdescriptionhelp'] = 'Help';
$string['smartmenusmenushowdescriptionnever'] = 'Never';
$string['smartmenusmenustructureheader'] = 'Menu structure';
$string['smartmenusmenutitle'] = 'Title';
$string['smartmenusmenutitle_help'] = 'The title of the menu. This will be used as the label of the parent node of this menu.';
$string['smartmenusmenutype'] = 'Presentation type';
$string['smartmenusmenutype_help'] = '<p>Select the type of presentation for the menu, choosing between list and card.</p><ul><li>List: A list menu is composed of simple text links. This is the default option.</li><li>Card: A card menu is composed of cards.</li></ul>';
$string['smartmenusmenutypecard'] = 'Card';
$string['smartmenusmenutypelist'] = 'List';
$string['smartmenusmodeinline'] = 'Inline';
$string['smartmenusmodesubmenu'] = 'Submenu';
$string['smartmenusnorestrict'] = 'Not restricted';
$string['smartmenusoperator'] = 'Operator';
$string['smartmenusoperator_help'] = 'Select the operator for the cohort condition (Any or All)';
$string['smartmenusrestrictbycohortsheader'] = 'Restrict visibility by cohorts';
$string['smartmenusrestrictbydateheader'] = 'Restrict visibility by date';
$string['smartmenusrestrictbylanguageheader'] = 'Restrict visibility by language';
$string['smartmenusrestrictbyrolesheader'] = 'Restrict visibility by roles';
$string['smartmenusrestrictbyadminheader'] = 'Restrict visibility by site admin status';
$string['smartmenusrolecontext'] = 'Context';
$string['smartmenusrolecontext_help'] = 'Select the context for which the user\'s role should be checked (Any context or system context only)';
$string['smartmenussavechangesandconfigure'] = 'Save and configure items';
$string['smartmenussettings'] = 'Smart menu settings';

// Privacy API.
$string['privacy:metadata'] = 'The Boost Union theme does not store any personal data about any user.';

// Capabilities.
$string['boost_union:configure'] = 'To be able to configure the theme as non-admin';
$string['boost_union:viewhintcourseselfenrol'] = 'To be able to see a hint for unrestricted self enrolment in a visible course.';
$string['boost_union:viewhintinhiddencourse'] = 'To be able to see a hint in a hidden course.';
$string['boost_union:viewregionheader'] = 'To be able to see the Header block region';
$string['boost_union:editregionheader'] = 'To be able to edit the Header block region';
$string['boost_union:viewregionoutsideleft'] = 'To be able to see the Outside (left) block region';
$string['boost_union:editregionoutsideleft'] = 'To be able to edit the Outside (left) block region';
$string['boost_union:viewregionoutsideright'] = 'To be able to see the Outside (right) block region';
$string['boost_union:editregionoutsideright'] = 'To be able to edit the Outside (right) block region';
$string['boost_union:viewregionoutsidetop'] = 'To be able to see the Outside (top) block region';
$string['boost_union:editregionoutsidetop'] = 'To be able to edit the Outside (top) block region';
$string['boost_union:viewregionoutsidebottom'] = 'To be able to see the Outside (bottom) block region';
$string['boost_union:editregionoutsidebottom'] = 'To be able to edit the Outside (bottom) block region';
$string['boost_union:viewregioncontentupper'] = 'To be able to see the Content (upper) block region';
$string['boost_union:editregioncontentupper'] = 'To be able to edit the Content (upper) block region';
$string['boost_union:viewregioncontentlower'] = 'To be able to see the Content (lower) block region';
$string['boost_union:editregioncontentlower'] = 'To be able to edit the Content (lower) block region';
$string['boost_union:viewregionfooterleft'] = 'To be able to see the Footer (left) block region';
$string['boost_union:editregionfooterleft'] = 'To be able to edit the Footer (left) block region';
$string['boost_union:viewregionfooterright'] = 'To be able to see the Footer (right) block region';
$string['boost_union:editregionfooterright'] = 'To be able to edit the Footer (right) block region';
$string['boost_union:viewregionfootercenter'] = 'To be able to see the Footer (center) block region';
$string['boost_union:editregionfootercenter'] = 'To be able to edit the Footer (center) block region';
$string['boost_union:viewregionoffcanvasleft'] = 'To be able to see the Off-canvas (left) block region';
$string['boost_union:editregionoffcanvasleft'] = 'To be able to edit the Off-canvas (left) block region';
$string['boost_union:viewregionoffcanvasright'] = 'To be able to see the Off-canvas (right) block region';
$string['boost_union:editregionoffcanvasright'] = 'To be able to edit the Off-canvas (right) block region';
$string['boost_union:viewregionoffcanvascenter'] = 'To be able to see the Off-canvas (center) block region';
$string['boost_union:editregionoffcanvascenter'] = 'To be able to edit the Off-canvas (center) block region';

// Caches.
$string['cachedef_flavours'] = 'Flavours which apply to a given page\'s category ID for the current user';
$string['cachedef_smartmenus'] = 'Smart menus';
$string['cachedef_smartmenu_items'] = 'Smart menu items';
$string['cachedef_touchiconsios'] = 'Touch icon files for iOS';
$string['cachedef_hookoverrides'] = 'Hook overrides';

// Scheduled tasks.
$string['task_purgecache'] = 'Purge theme cache';

// Upgrade notices.
$string['upgradenotice_2022080922'] = 'From this release on, Boost Union has its own logo and compact logo settings and does not use these files from the Moodle core settings anymore.';
$string['upgradenotice_2022080922_logo'] = 'logo';
$string['upgradenotice_2022080922_logocompact'] = 'compact logo';
$string['upgradenotice_2022080922_copied'] = 'The existing <strong>{$a}</strong> from the Moodle core settings has been copied to the Boost Union {$a} setting during this upgrade. Please double-check the result.';
$string['upgradenotice_2022080922_notcopied'] = 'The <strong>{$a}</strong> setting within Boost Union is empty now. If you want to use a {$a} within Boost Union from now on, just upload it into the Boost Union {$a} setting later.';
