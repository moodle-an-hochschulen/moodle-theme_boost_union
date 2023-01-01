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

// Settings: Feel page.
$string['configtitlefeel'] = 'Feel';

// Settings: Navigation tab.
$string['navigationtab'] = 'Navigation';
// ... Section: Primary navigation.
$string['primarynavigationheading'] = 'Primary navigation';
// ... ... Settings: Hide nodes in primary navigation.
$string['hidenodesprimarynavigationsetting'] = 'Hide nodes in primary navigation';
$string['hidenodesprimarynavigationsetting_desc'] = 'With this setting, you can hide one or multiple nodes from the primary navigation.';
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

// Caches.
$string['cachedef_fontawesome'] = 'FontAwesome files (which are uploaded in the Boost Union settings)';
