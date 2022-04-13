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

// Settings: General settings tab.
$string['presetheading'] = 'Theme presets';

// Settings: Advances settings tab.
$string['scssheading'] = 'Raw SCSS';

// Settings: Branding tab.
$string['brandingtab'] = 'Branding';
$string['backgroundimagesheading'] = 'Background images';
$string['brandcolorsheading'] = 'Brand colors';

// Settings: Blocks tab.
$string['blockstab'] = 'Blocks';
$string['blocksgeneralheading'] = 'General blocks';

// Privacy API.
$string['privacy:metadata'] = 'The Boost Union theme does not store any personal data about any user.';

// Info banner settings.
$string['infobannersettings'] = 'Info Banner Settings';

// ...Perpetual information banner.
$string['perpetualinfobannerheadingsetting'] = 'Perpetual information banner';
$string['perpetualinfobannerheadingsetting_desc'] = 'The following settings allow to show some important information within a prominent perpetual banner.';
$string['perpibenablesetting'] = 'Enable perpetual info banner';
$string['perpibenablesetting_desc'] = 'With this checkbox you can decide if the perpetual information banner should be shown or hidden on the selected pages.';
$string['perpibcontent'] = 'Perpetual information banner content';
$string['perpibcontent_desc'] = 'Enter your information which should be shown within the banner here.';
$string['perpibshowonpagessetting'] = 'Page layouts to display the info banner on';
$string['perpibshowonpagessetting_desc'] = 'With this setting you can select the pages on which the perpetual information banner should be displayed.';
$string['perpibcsssetting'] = 'Bootstrap css class for the perpetual info banner';
$string['perpibcsssetting_desc'] = 'With this setting you can select the Bootstrap style with which the perpetual information banner should be displayed.';
$string['perpibdismisssetting'] = 'Perpetual info banner dismissible';
$string['perpibdismisssetting_desc'] = 'With this checkbox you can make the banner dismissible permanently. If the user clicks on the x-button a confirmation dialogue will appear and only after the user confirmed this dialogue the banner will be hidden for this user permanently.
<br/><br/>Please note: <br/> This setting has no effect for the banners shown on the login page. Because banners on the login page cannot be clicked away permanently, we do not offer the possibility to click the banner away at all on the login page.';
$string['perpibconfirmsetting'] = 'Confirmation dialogue';
$string['perpibconfirmsetting_desc'] = 'When you enable this setting you can show a confirmation dialogue to a user when he is dismissing the info banner.
<br/>The text is saved in the string with the name "closingperpetualinfobanner":<br/><br/>
Are you sure you want to dismiss this information? Once done it will not occur again!<br/><br/>
You can override this within your language customization if you need some other text in this dialogue.';
$string['perpetualinfobannerresetvisiblitysetting'] = 'Reset visibility for perpetual info banner';
$string['perpetualinfobannerresetvisiblitysetting_desc'] = 'By enabling this checkbox, the visibility of the individually dismissed perpetual info banners will be set to visible again. You can use this setting if you made important content changes and want to show the info to all users again.<br/><br/>
Please note: <br/>
After saving this option, the database operations for resetting the visibility will be triggered and this checkbox will be unticked again. The next enabling and saving of this feature will trigger the database operations for resetting the visibility again.';

// ...Time controlled information banner.
$string['timedinfobannerheadingsetting'] = 'Time controlled information banner';
$string['timedinfobannerheadingsetting_desc'] = 'The following settings allow to show some important information within a prominent time controlled banner.';
$string['timedibenablesetting'] = 'Enable time controlled info banner';
$string['timedibenablesetting_desc'] = 'With this checkbox you can decide if the time controlled information banner should be shown or hidden on the selected pages.';
$string['timedibcontent'] = 'Time controlled information banner content';
$string['timedibcontent_desc'] = 'Enter your information which should be shown within the time controlled banner here.';
$string['timedibshowonpagessetting'] = 'Page layouts to display the info banner on';
$string['timedibshowonpagessetting_desc'] = 'With this setting you can select the pages on which the time controlled information banner should be displayed.
<br/> If both info banners are active on a selected layout, the time controlled info banner will always appear above the perpetual info banner!';
$string['timedibcsssetting'] = 'Bootstrap css class for the time controlled info banner';
$string['timedibcsssetting_desc'] = 'With this setting you can select the Bootstrap style with which the time controlled information banner should be displayed.';
$string['timedibstartsetting'] = 'Start time for the time controlled info banner';
$string['timedibstartsetting_desc'] = 'With this setting you can define when the time controlled information banner should be displayed on the selected pages.
<br/>Please enter a valid in this format: YYYY-MM-DD HH:MM:SS. For example: "2020-01-01 08:00:00". The time zone will be the time zone you have defined in the setting "Default timezone".
<br/>If you leave this setting empty but entered a date in the for the end, it is the same as if you entered a date far in the past.';
$string['timedibendsetting'] = 'End time for the time controlled info banner';
$string['timedibendsetting_desc'] = 'With this setting you can define when the time controlled information banner should be hidden on the selected pages.
<br/>Please enter a valid date in this format: YYYY-MM-DD HH:MM:SS. For example: "2020-01-07 08:00:00. The time zone will be the time zone you have defined in the setting "Default timezone".
<br/>If you leave this setting empty but entered a date in the for the start, the banner won\'t hide after the starting time has been reached.';

// ...General information banner.
$string['ibcsssetting_nobootstrap'] = 'If you choose the \'{$a->bootstrapnone}\' option, the information banner will be output without any particular Bootstrap color.';

// ADDITIONAL STRINGS (IN ALPHABETICAL ORDER).
$string['bootstrapprimarycolor'] = 'Primary color';
$string['bootstrapsecondarycolor'] = 'Secondary color';
$string['bootstrapsuccesscolor'] = 'Success color';
$string['bootstrapdangercolor'] = 'Danger color';
$string['bootstrapwarningcolor'] = 'Warning color';
$string['bootstrapinfocolor'] = 'Info color';
$string['bootstraplightcolor'] = 'Light color';
$string['bootstrapdarkcolor'] = 'Dark color';
$string['bootstrapnone'] = 'No Bootstrap color';
$string['close'] = 'Close';
$string['confirmation'] = 'Confirmation';
$string['closingperpetualinfobanner'] = 'Are you sure you want to dismiss this information? Once done it will not occur again!';
$string['login_page'] = "Login page";
$string['resetperpetualinfobannersuccess'] = 'Success! All perpetual info banner instances are visible again.
<br/>The setting "Reset visibility for perpetual info banner" has been reset.';
$string['yes_close'] = "Yes, close!";
