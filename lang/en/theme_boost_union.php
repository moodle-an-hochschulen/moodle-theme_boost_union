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
 * @copyright  2022 Moodle an Hochschulen e.V. <kontakt@moodle-an-hochschulen.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// General.
$string['pluginname'] = 'Boost Union';
$string['choosereadme'] = 'Theme Boost Union is an enhanced child theme of Boost provided by Moodle an Hochschulen e.V.';
$string['configtitle'] = 'Boost Union';

// Settings: Look page.
$string['configtitlelook'] = 'Look';

// Settings: General settings tab.
// ... Section: Theme presets.
$string['presetheading'] = 'Theme presets';

// Settings: Advances settings tab.
// ... Section: Raw SCSS.
$string['scssheading'] = 'Raw SCSS';

// Settings: Page tab.
$string['pagetab'] = 'Page';
// ... Section: Layout.
$string['layoutheading'] = 'Layout';
// ... ... Setting: Course content max width.
$string['coursecontentmaxwidthsetting'] = 'Course content max width';
$string['coursecontentmaxwidthsetting_desc'] = 'With this setting, you can override Moodle\'s default content width without manual SCSS modifications. By default, Moodle uses a course content max width of 830px. You can enter other pixel-based values like 1200px, but you can also enter a percentage-based value like 100% or a viewport-width value like 90vw.';

// Settings: Branding tab.
$string['brandingtab'] = 'Branding';
// ... Section: Favicon.
$string['faviconheading'] = 'Favicon';
// ... ... Setting: Favicon
$string['faviconsetting'] = 'Favicon';
$string['faviconsetting_desc'] = 'Here, you can upload a custom image (.ico or .png format) that the browser will show as the favicon of your Moodle website. If no custom favicon is uploaded, a standard Moodle favicon will be used.';
// ... Section: Background images.
$string['backgroundimagesheading'] = 'General background images';
// ... Section: Login page background images.
$string['loginbackgroundimagesheading'] = 'Login page background images';
$string['loginbackgroundimage'] = 'Login page background images';
$string['loginbackgroundimage_desc'] = 'The images to display as a background of the login page. One of these images will be picked randomly and shown when the user visits the login page.';
$string['loginbackgroundimagetextsetting'] = 'Display text for login background images';
$string['loginbackgroundimagetextsetting_desc'] = 'With this optional setting you can add text, e.g. a copyright notice to your uploaded background images. This text will appear on top of the page footer on the login page. However, for screen real estate reasons, it is only shown on larger screen sizes.<br/>
Each line consists of the file identifier (the file name), the text that should be displayed and the text color, separated by a pipe character. Each declaration needs to be written in a new line. <br/>
For example:<br/>
background-image-1.jpg|Copyright: CC0|dark<br/>
As text color, you can use the values "dark" or "light".<br />
You can declare texts for a arbitrary amount of your uploaded login background images. The texts will be added only to those images that match their filename with the identifier declared in this setting.';
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
// ... ... Setting: Activity icon color for 'Interface'.
$string['activityiconcolorinterfacesetting'] = 'Activity icon color for "Interface"';
$string['activityiconcolorinterfacesetting_desc'] = 'The activity icon color for "Interface"';
// Settings: Resources.
$string['resourcestab'] = 'Resources';
$string['resourcescachecontrolnote'] = 'Please note that the files are shipped to the browser with the \'Cache-Control\' header set which tells the browser to cache the file. If you are sure that you won\'t change the file in the near future, you can use the persistent URL to link to the file. However, if you plan to modify a file but keep the same filename every now and then, you should rather use the revisioned URL and re-link the file where you have used it everytime you update the file to avoid that the browsers will show cached outdated versions of the file.';
// ... Section: Additional resources.
$string['additionalresourcesheading'] = 'Additional resources';
// ... ... Setting: Additional resources.
$string['additionalresourcessetting'] = 'Additional resources';
$string['additionalresourcessetting_desc'] = 'With this setting you can upload additional resources to the theme. The advantage of uploading files to this file area is that those files can be delivered without a check if the user is logged in. This is also why you should only add files that are uncritical and everyone should be allowed to access and don\'t need be protected with a valid login. As soon as you have uploaded at least one file to this filearea and have stored the settings, a list will appear underneath which will give you the URL which you can use to reference a particular file.';
// ... ... Information: Additional resources list.
$string['additionalresourceslistsetting'] = 'Additional resources list';
$string['additionalresourceslistsetting_desc'] = 'This is the list of files which you have uploaded to the additional resources filearea. The given URLs can be used to link to these files from within your custom CSS, from the footnote or whereever you need to use uploaded files but can\'t upload files in place.';
$string['additionalresourcesfileurlpersistent'] = 'URL (persistent)';
$string['additionalresourcesfileurlrevisioned'] = 'URL (revisioned)';

// Settings: Feel page.
$string['configtitlefeel'] = 'Feel';

// Settings: Navigation tab.
$string['navigationtab'] = 'Navigation';
// ... Section: Navigation.
$string['navigationheading'] = 'Navigation';
// ... ... Setting: Back to top button.
$string['backtotop'] = 'Back to top';
$string['backtotopbuttonsetting'] = 'Back to top button';
$string['backtotopbuttonsetting_desc'] = 'With this setting a back to top button will appear in the bottom right corner of the page as soon as the user scrolls down the page. A button like this existed already on Boost in Moodle Core until Moodle 3.11, but was removed in 4.0. With Boost Union, you can bring it back.';
// ... ... Setting: Scroll-spy
$string['scrollspy'] = 'Scroll-spy';
$string['scrollspysetting'] = 'Scroll-spy';
$string['scrollspysetting_desc'] = 'With this setting, upon toggling edit mode on and off, the scroll position at where the user was when performing the toggle is preserved.';
// ... ... Setting: Activity navigation
$string['activitynavigation'] = 'Activity navigation';
$string['activitynavigationsetting'] = 'Activity navigation elements';
$string['activitynavigationsetting_desc'] = 'With this setting the elements to jump to the previous and next activity/resource as well as the pull down menu to jump to a distinct activity/resource become displayed. UI elements like this existed already on Boost in Moodle Core until Moodle 3.11, but were removed in 4.0. With Boost Union, you can bring them back.';

// Settings: Blocks tab.
$string['blockstab'] = 'Blocks';
// ... Section: General blocks.
$string['blocksgeneralheading'] = 'General blocks';

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

// Settings: Static pages tab.
$string['staticpagestab'] = 'Static pages';
// ... Section: Imprint.
$string['imprintheading'] = 'Imprint';
// ... ... Setting: Enable imprint.
$string['enableimprintsetting'] = 'Enable imprint';
$string['imprintdisabled'] = 'The imprint is disabled for this site. There is nothing to see here.';
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
// Settings: Info banners tab.
$string['infobannertab'] = 'Info banner';
// ... Section: Info banners.
$string['infobannerheading'] = 'Info banner no. {$a->no}';
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
$string['infobannerdismissresetbutton'] = 'Reset visibility of info banner no. {$a->no}';
$string['infobannerdismissconfirm'] = 'Do you really want to reset the visibility of info banner no. {$a->no} and want to re-show it for all users who have dismissed it?';
$string['infobannerdismisssuccess'] = 'The visibility of info banner no. {$a->no} has been reset';
$string['infobannerdismissfail'] = 'The visibility reset of info banner no. {$a->no} has failed for at least one user';
$string['error:infobannerdismissnonotvalid'] = 'The given info banner number is not valid';
$string['error:infobannerdismissnonotdismissible'] = 'The given info banner is not dismissible';
$string['infobannerenabledsetting'] = 'Enable info banner no. {$a->no}';
$string['infobannerenabledsetting_desc'] = 'With this setting, you can enable info banner no. {$a->no}.';
$string['infobannercontentsetting'] = 'Info banner no. {$a->no} content';
$string['infobannercontentsetting_desc'] = 'Here, you enter the information which should be shown within info banner no. {$a->no}.';
$string['infobannerpagessetting'] = 'Page layouts to display info banner no. {$a->no} on';
$string['infobannerpagessetting_desc'] = 'With this setting, you can select the page layouts on which info banner no. {$a->no} should be displayed.';
$string['infobannerbsclasssetting'] = 'Info banner no. {$a->no} Bootstrap class';
$string['infobannerbsclasssetting_desc'] = 'With this setting, you can select the Bootstrap style with which info banner no. {$a->no} should be displayed. If you choose the \'No Bootstrap color\' option, the info banner will be output without any particular Bootstrap color which gives you the freedom to style the banner yourself within the rich-text editor.';
$string['infobannerordersetting'] = 'Info banner no. {$a->no} order position';
$string['infobannerordersetting_desc'] = 'With this setting, you define the order position of info banner no. {$a->no}. By default, the info banners are ordered from top to bottom like you see them on this settings page here. However, you can decide to assign another order position with this setting. If you assign the same order position to two or more information banners, they will be ordered again according to the order on this settings page.';
$string['infobannermodesetting'] = 'Info banner no. {$a->no} display mode';
$string['infobannermodesetting_desc'] = 'With this setting, you can define if info banner no. {$a->no} should be a perpetual banner (which is always shown) or a time controlled banner (which is only shown within the configured time interval)';
$string['infobannerdismissiblesetting'] = 'Info banner no. {$a->no} dismissible';
$string['infobannerdismissiblesetting_desc'] = 'With this setting, you can make info banner no. {$a->no} dismissible. If the user clicks on the x-button in the info banner, the banner will be hidden for this user permanently. The visibility is not reset anyhow automatically, even if you change the content of the info banner. If you want to reset the visibility of the info banner, click the \'Reset visibility\' button below.';
$string['infobannerstartsetting'] = 'Info banner no. {$a->no} start time';
$string['infobannerstartsetting_desc'] = 'With this setting, you can define from when on info banner no. {$a->no} should be displayed. The configured time is interpreted as server time, not as user time.';
$string['infobannerendsetting'] = 'Info banner no. {$a->no} end time';
$string['infobannerendsetting_desc'] = 'With this setting, you can define until when info banner no. {$a->no} should be displayed. The configured time is interpreted as server time, not as user time.';

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
// ... ... Setting: Show hint for guest access.
$string['showhintcoursguestaccesssetting'] = 'Show hint for guest access';
$string['showhintcourseguestaccesssetting_desc'] = 'With this setting a hint will appear in the course header when a user is accessing it with the guest access feature. If the course provides an active self enrolment, a link to that page is also presented to the user.';
$string['showhintcourseguestaccessgeneral'] = 'You are currently viewing this course as <strong>{$a->role}</strong>.';
$string['showhintcourseguestaccesslink'] = 'To have full access to the course, you can <a href="{$a->url}">self enrol into this course</a>.';
// ... ... Setting: Show hint for unrestricted self enrolment.
$string['showhintcourseselfenrolsetting'] = 'Show hint for self enrolment without enrolment key';
$string['showhintcourseselfenrolsetting_desc'] = 'With this setting a hint will appear in the course header if the course is visible and an enrolment without enrolment key is currently possible.';
$string['showhintcourseselfenrolstartcurrently'] = 'This course is currently visible and <strong>self enrolment without enrolment key</strong> is currently possible.';
$string['showhintcourseselfenrolstartfuture'] = 'This course is currently visible and <strong>self enrolment without enrolment key</strong> is planned to become possible.';
$string['showhintcourseselfenrolunlimited'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment infinitely.';
$string['showhintcourseselfenroluntil'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment until {$a->until}.';
$string['showhintcourseselfenrolfrom'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment from {$a->from} on.';
$string['showhintcourseselfenrolsince'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment currently.';
$string['showhintcourseselfenrolfromuntil'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment from {$a->from} until {$a->until}.';
$string['showhintcourseselfenrolsinceuntil'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment until {$a->until}.';
$string['showhintcourseselfenrolinstancecallforaction'] = 'If you don\'t want that any Moodle user can enrol into this course freely, please restrict the self enrolment settings.';

// Privacy API.
$string['privacy:metadata'] = 'The Boost Union theme does not store any personal data about any user.';

// Capabilities.
$string['boost_union:configure'] = 'To be able to configure the theme as non-admin';
$string['boost_union:viewhintcourseselfenrol'] = 'To be able to see a hint for unrestricted self enrolment in a visible course.';
$string['boost_union:viewhintinhiddencourse'] = 'To be able to see a hint in a hidden course.';
