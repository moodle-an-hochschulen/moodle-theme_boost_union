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

// General.
$string['pluginname'] = 'Boost Union';
$string['choosereadme'] = '<p>Theme Boost Union is an enhanced child theme of Boost which is intended, on the one hand, to make Boost simply more configurable and, on the other hand, to provide helpful additional features for the daily Moodle operation of admins, teachers and students.</p><p>Boost Union is maintained by<br />Moodle an Hochschulen e.V.,</p><p>in cooperation with<br />lern.link GmbH</p><p>together with<br />bdecent GmbH</p>';
$string['configtitle'] = 'Boost Union';

// Settings: General strings.
$string['dontchange'] = 'Do not change anything';

// Settings: Look page.
$string['configtitlelook'] = 'Look';

// Settings: General settings tab.
// ... Section: Theme presets.
$string['presetheading'] = 'Theme presets';

// Settings: SCSS tab.
$string['scsstab'] = 'SCSS';
// ... Section: Raw SCSS.
$string['scssheading'] = 'Raw SCSS';

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

// Settings: Branding tab.
$string['brandingtab'] = 'Branding';
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
// ... Section: Navbar.
$string['navbarheading'] = 'Navbar';
// ... ... Setting: Navbar color.
$string['navbarcolorsetting'] = 'Navbar color';
$string['navbarcolorsetting_desc'] = 'With this setting, you can change the navbar color from the default light navbar to a dark one or a colored one.';
$string['navbarcolorsetting_light'] = 'Light navbar with dark font color (unchanged as presented by Moodle core)';
$string['navbarcolorsetting_dark'] = 'Dark navbar with light font color';
$string['navbarcolorsetting_primarydark'] = 'Primary color navbar with light font color';
$string['navbarcolorsetting_primarylight'] = 'Primary color navbar with dark font color';

// Settings: Login page tab.
$string['loginpagetab'] = 'Login page';
// ... Section: Login page background images.
$string['loginbackgroundimagesheading'] = 'Login page background images';
// ... ... Setting: Login page background image.
$string['loginbackgroundimage'] = 'Login page background images';
$string['loginbackgroundimage_desc'] = 'The images to display as a background of the login page. One of these images will be picked randomly and shown when the user visits the login page. Please make sure not to use non-ASCII-characters in the filename if you want to display text for login background images.';
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
$string['emailbrandinginstructionli2'] = 'Search for and modify these strings in the <code>theme_boost_union language</code> pack:';
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
$string['additionalresourceslistsetting_desc'] = 'This is the list of files which you have uploaded to the additional resources filearea. The given URLs can be used to link to these files from within your custom CSS, from the footnote or whereever you need to use uploaded files but can\'t upload files in place.';
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
// ... Section: FontAwesome.
$string['fontawesomeheading'] = 'FontAwesome';
// ... ... Setting: FontAwesome version.
$string['fontawesomeversionsetting'] = 'FontAwesome version';
$string['fontawesomeversionsetting_desc'] = 'Moodle core ships with FontAwesome 4 icons which are fine, but FontAwesome has evolved since then. If you want to use more recent FontAwesome icons, you can do this with this setting. As soon as you choose another version than FontAwesome 4, additional settings will appear where you can upload more recent FontAwesome versions.';
$string['fontawesomeversionnone'] = 'Keep FontAwesome 4 (as shipped with Moodle core)';
$string['fontawesomeversionfa6free'] = 'Update to FontAwesome 6 Free';
// ... ... Setting: FontAwesome files.
$string['fontawesomefilessetting'] = 'FontAwesome files';
$string['fontawesomefilessetting_desc'] = 'With this setting you can upload more recent FontAwesome files to Moodle. You have to upload the FontAwesome files to Moodle yourself due to licensing constraints. Just head over to <a href="https://fontawesome.com">fontawesome.com</a>, download the FontAwesome package and upload the files here.';
$string['fontawesomefilesstructurenote'] = 'Please note that the files must be uploaded with the correct folder structure and with the correct file names. Please start by creating a <em>css</em> and a <em>webfonts</em> folder in the filepicker, upload the <em>fa-solid-900.woff2</em> file into the <em>webfonts</em> folder and save the settings page. As soon as you have done this, a file list will appear below which helps you to identify and upload the right files into these folders.';
// ... ... Information: FontAwesome files list.
$string['fontawesomelistsetting'] = 'FontAwesome files list';
$string['fontawesomelistsetting_desc'] = 'This is the list of FontAwesome files which you have uploaded to the FontAwesome files filearea above. All FontAwesome files which are valid for the configured FontAwesome version are listed here, other files which you may have uploaded as well but which are not valid or needed FontAwesome files are ignored. The FontAwesome files are automatically added to the Moodle pages and have a direct effect as soon as you save this setting.';
$string['fontawesomelistnote'] = 'Please note that, if you upload only a fraction of the mandatory files, the FontAwesome icons can appear as broken on the Moodle page. This cannot be fixed until you upload all mandatory files or remove all files again.';
$string['fontawesomelistfileinfo-fa6free-css-fontawesome.min.css'] = 'This is the main CSS file which adds all available FontAwesome glyphs to the Moodle page.';
$string['fontawesomelistfileinfo-fa6free-css-brands.min.css'] = 'This is an additional CSS file which adds the font for FontAwesome brand icons to the Moodle page.';
$string['fontawesomelistfileinfo-fa6free-css-regular.min.css'] = 'This is an additional CSS file which adds the font for FontAwesome regular icons to the Moodle page.';
$string['fontawesomelistfileinfo-fa6free-css-solid.min.css'] = 'This is an additional CSS file which adds the font for FontAwesome solid icons to the Moodle page.';
$string['fontawesomelistfileinfo-fa6free-css-v4-font-face.min.css'] = 'This is the CSS file which makes sure that the FontAwesome 4 icons in Moodle are still displayed correctly.';
$string['fontawesomelistfileinfo-fa6free-webfonts-fa-brands-400.woff2'] = 'This is the font file for FontAwesome brand icons (in the WOFF2 format).';
$string['fontawesomelistfileinfo-fa6free-webfonts-fa-brands-400.ttf'] = 'This is the font file for FontAwesome brand icons (in the TTF format).';
$string['fontawesomelistfileinfo-fa6free-webfonts-fa-regular-400.woff2'] = 'This is the font file for FontAwesome regular icons (in the WOFF2 format).';
$string['fontawesomelistfileinfo-fa6free-webfonts-fa-regular-400.ttf'] = 'This is the font file for FontAwesome regular icons (in the TTF format).';
$string['fontawesomelistfileinfo-fa6free-webfonts-fa-solid-900.woff2'] = 'This is the font file for FontAwesome solid icons (in the WOFF2 format).';
$string['fontawesomelistfileinfo-fa6free-webfonts-fa-solid-900.ttf'] = 'This is the font file for FontAwesome solid icons (in the TTF format).';
$string['fontawesomelistfileinfo-fa6free-webfonts-fa-v4compatibility.woff2'] = 'This is the font file for the FontAwesome v4 compatibility (in the WOFF2 format).';
$string['fontawesomelistfileinfo-fa6free-webfonts-fa-v4compatibility.ttf'] = 'This is the font file for the FontAwesome v4 compatibility (in the TTF format).';
$string['fontawesomelistmandatoryuploaded'] = 'It is a mandatory file for FontAwesome to work and it was uploaded properly.';
$string['fontawesomelistoptionaluploaded'] = 'It is an optional file to enhance the FontAwesome iconset and it was uploaded properly.';
$string['fontawesomelistmandatorymissing'] = 'It is a mandatory file for FontAwesome to work, but it was not uploaded properly. Please try to upload it properly.';
$string['fontawesomelistoptionalmissing'] = 'It is an optional file to enhance the FontAwesome iconset, but it was not uploaded. This fine as long as you don\'t need it.';
// ... ... Information: FontAwesome checks.
$string['fontawesomecheckssetting'] = 'FontAwesome checks';
$string['fontawesomecheckssetting_desc'] = 'Here, you can verify visually if the FontAwesome files have been uploaded and added to the Moodle page properly. If one of the checks fail, please double-check if you have uploaded all mandatory files correctly.';
$string['fontawesomecheck-fa6free-general-title'] = 'General functionality';
$string['fontawesomecheck-fa6free-general-description'] = 'If you see a checkmark icon on the left hand side, FontAwesome is generally working in your site.';
$string['fontawesomecheck-fa6free-fallback-title'] = 'FontAwesome 4 fallback';
$string['fontawesomecheck-fa6free-fallback-description'] = 'Newer FontAwesome versions use to remap older icon identifiers to newer ones or even get rid of some icons. If you see a solid map icon on the left hand side, your FontAwesome 6 version is properly showing remapped icons from FontAwesome 4.';
$string['fontawesomecheck-fa6free-newstuff-title'] = 'FontAwesome 6 icons';
$string['fontawesomecheck-fa6free-newstuff-description'] = 'Newer FontAwesome versions ship with additional icons compared to the FontAwesome 4 iconset. If you see a virus icon on the left hand side, your FontAwesome 6 version is properly showing new icons which are new in FontAwesome 6.';
$string['fontawesomecheck-fa6free-filter-title'] = 'FontAwesome filter';
$string['fontawesomecheck-fa6free-filter-description'] = 'As you have the FontAwesome filter plugin installed, you should be sure that the filter handles the new FontAwesome 6 icons correctly as well. If you see a users icon on the left hand side, the filter is working properly with the FontAwesome 6 version icons.';

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

// Settings: Mobile app tab.
$string['mobiletab'] = 'Mobile app';
// ... Section: Mobile appearance.
$string['mobileappearanceheading'] = 'Mobile appearance';
// ... ... Setting: Additional CSS for Mobile app.
$string['mobilecss'] = 'Additional CSS for Mobile app';
$string['mobilecss_desc'] = 'With this setting, you can write custom CSS code to customise your mobile app interface. The CSS code will be only added to the Mobile app depiction of this Moodle instance and will not be shown in the webbrowser version. Read more about this feature in the <a href="https://moodledev.io/general/app/customisation/remote-themes#how-do-remote-themes-work">Moodle dev docs</a>.';
$string['mobilecss_set'] = 'As soon as you add any CSS code to this setting and save the setting, the <a href="{$a->url}">Moodle core setting <em>mobilecssurl</em></a> will be automatically set to a URL of the Boost Union theme.';
$string['mobilecss_overwrite'] = 'As soon as you add any CSS code to this setting and save the setting, the <a href="{$a->url}">Moodle core setting <em>mobilecssurl</em></a> will be automatically overwritten with a URL of the Boost Union theme. Currently this setting is set to <a href="{$a->value}">{$a->value}</a>.';
$string['mobilecss_donotchange'] = 'This step is necessary to ship the CSS code to the Mobile app. Do not change the URL there unless you really want to remove the CSS code from the Mobile app again.';

// Settings: Feel page.
$string['configtitlefeel'] = 'Feel';

// Settings: Navigation tab.
$string['navigationtab'] = 'Navigation';
// ... Section: Primary navigation.
$string['primarynavigationheading'] = 'Primary navigation';
// ... ... Settings: Hide nodes in primary navigation.
$string['hidenodesprimarynavigationsetting'] = 'Hide nodes in primary navigation';
$string['hidenodesprimarynavigationsetting_desc'] = 'With this setting, you can hide one or multiple nodes from the primary navigation.';
// ... Section: Breadcrumbs.
$string['breadcrumbsheading'] = 'Breadcrumbs';
// ... ... Setting: Course category breadcrumb.
$string['categorybreadcrumbs'] = 'Display the category breadcrumbs in the course header';
$string['categorybreadcrumbs_desc'] = 'By default, the course category breadcrumbs are not shown on course pages in the course header. With this setting, you can show the course category breadcrumbs in the course header above the course name.<br/><br/>
Please note: The "Do not change anything" option does not change anything about the course category breadcrumbs like they are presented by Moodle core. Whereas the "No" option takes care that course category breadcrumbs are never shown at all. This might be a difference as Boost in Moodle core might indeed show course category breadcrumbs beginning on deeper nested course category levels (which the Boost Union developers assess to be a glitch).';
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
// ... ... Setting: Outside regions horizontal placement.
$string['outsideregionsplacement'] = 'Outside regions horizontal placement';
$string['outsideregionsplacement_desc'] = 'With this setting, you can control if, on larger screens, the \'Outside (left)\' and \'Outside (right)\' block regions should be placed near the main content area or rather near the window edges.';
$string['outsideregionsplacementnextmaincontent'] = 'Display \'Outside (left)\' and \'Outside (right)\' regions next to the main content area';
$string['outsideregionsplacementnearwindowedges'] = 'Display \'Outside (left)\' and \'Outside (right)\' regions near the window edges';

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
$string['infobannerstartsetting_desc'] = 'With this setting, you can define from when on info banner {$a->no} should be displayed. The configured time is interpreted as server time, not as user time.';$string['infobannerendsetting'] = 'Info banner {$a->no} end time';
$string['infobannerendsetting_desc'] = 'With this setting, you can define until when info banner {$a->no} should be displayed. The configured time is interpreted as server time, not as user time.';

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
$string['showhintcourseselfenrolstartcurrently'] = 'This course is currently visible to everyone and <strong>self enrolment without an enrolment key</strong> is possible.';
$string['showhintcourseselfenrolstartfuture'] = 'This course is currently visible to everyone and <strong>self enrolment without an enrolment key</strong> is planned to become possible.';
$string['showhintcourseselfenrolunlimited'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment indefinitely.';
$string['showhintcourseselfenroluntil'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment until {$a->until}.';
$string['showhintcourseselfenrolfrom'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment from {$a->from} on.';
$string['showhintcourseselfenrolsince'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment currently.';
$string['showhintcourseselfenrolfromuntil'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment from {$a->from} until {$a->until}.';
$string['showhintcourseselfenrolsinceuntil'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment until {$a->until}.';
$string['showhintcourseselfenrolinstancecallforaction'] = 'If you don\'t want any Moodle user to have access to this course freely, please restrict the self enrolment settings.';

// Settings: Flavours page.
$string['configtitleflavours'] = 'Flavours';
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
$string['flavoursbacktooverview'] = 'Back to flavour overview';
$string['flavourscreateflavour'] = 'Create flavour';
$string['flavourscustomcss'] = 'Custom CSS';
$string['flavourscustomcss_help'] = 'With this setting, you can write custom CSS for the flavour. It will be appended to the stack of CSS code which is shipped to the browser as soon as the flavour applies. Please note that in the current state of implementation, this setting only allows the usage of custom CSS, not SCSS.';
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

// Settings: Advertisement tiles.
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
$string['cachedef_fontawesome'] = 'FontAwesome files (which are uploaded in the Boost Union settings)';
$string['cachedef_flavours'] = 'Flavours which apply to a given page category ID for the current user';

// Upgrade notices.
$string['upgradenotice_2022080922'] = 'From this release on, Boost Union has its own logo and compact logo settings and does not use these files from the Moodle core settings anymore.';
$string['upgradenotice_2022080922_logo'] = 'logo';
$string['upgradenotice_2022080922_logocompact'] = 'compact logo';
$string['upgradenotice_2022080922_copied'] = 'The existing <strong>{$a}</strong> from the Moodle core settings has been copied to the Boost Union {$a} setting during this upgrade. Please double-check the result.';
$string['upgradenotice_2022080922_notcopied'] = 'The <strong>{$a}</strong> setting within Boost Union is empty now. If you want to use a {$a} within Boost Union from now on, just upload it into the Boost Union {$a} setting later.';
