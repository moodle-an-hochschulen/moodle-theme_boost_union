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
 * Theme Boost Union - Settings file
 *
 * @package    theme_boost_union
 * @copyright  2022 Moodle an Hochschulen e.V. <kontakt@moodle-an-hochschulen.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    // Create settings page with tabs.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingboost_union',
            get_string('configtitle', 'theme_boost_union', null, true));


    // Create general settings tab.
    $page = new admin_settingpage('theme_boost_union_general', get_string('generalsettings', 'theme_boost', null, true));

    // Create theme presets heading.
    $name = 'theme_boost_union/presetheading';
    $title = get_string('presetheading', 'theme_boost_union', null, true);
    $setting = new admin_setting_heading($name, $title, null);
    $page->add($setting);

    // Replicate the preset setting from theme_boost, but use our own file area.
    $name = 'theme_boost_union/preset';
    $title = get_string('preset', 'theme_boost', null, true);
    $description = get_string('preset_desc', 'theme_boost', null, true);
    $default = 'default.scss';

    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_boost_union', 'preset', 0, 'itemid, filepath, filename', false);

    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    $choices['default.scss'] = 'default.scss';
    $choices['plain.scss'] = 'plain.scss';

    $setting = new admin_setting_configthemepreset($name, $title, $description, $default, $choices, 'boost_union');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Replicate the preset files setting from theme_boost.
    $name = 'theme_boost_union/presetfiles';
    $title = get_string('presetfiles', 'theme_boost', null, true);
    $description = get_string('presetfiles_desc', 'theme_boost', null, true);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
            array('maxfiles' => 20, 'accepted_types' => array('.scss')));
    $page->add($setting);

    // Add tab to settings page.
    $settings->add($page);


    // Create advanced settings tab.
    $page = new admin_settingpage('theme_boost_union_advanced', get_string('advancedsettings', 'theme_boost', null, true));

    // Create Raw SCSS heading.
    $name = 'theme_boost_union/scssheading';
    $title = get_string('scssheading', 'theme_boost_union', null, true);
    $setting = new admin_setting_heading($name, $title, null);
    $page->add($setting);

    // Replicate the Raw initial SCSS setting from theme_boost.
    $name = 'theme_boost_union/scsspre';
    $title = get_string('rawscsspre', 'theme_boost', null, true);
    $description = get_string('rawscsspre_desc', 'theme_boost', null, true);
    $default = '';
    $setting = new admin_setting_scsscode($name, $title, $description, $default, PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Replicate the Raw SCSS setting from theme_boost.
    $name = 'theme_boost_union/scss';
    $title = get_string('rawscss', 'theme_boost', null, true);
    $description = get_string('rawscss_desc', 'theme_boost', null, true);
    $default = '';
    $setting = new admin_setting_scsscode($name, $title, $description, $default, PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Add tab to settings page.
    $settings->add($page);


    // Create branding tab.
    $page = new admin_settingpage('theme_boost_union_branding', get_string('brandingtab', 'theme_boost_union', null, true));

    // Create background images heading.
    $name = 'theme_boost_union/backgroundimagesheading';
    $title = get_string('backgroundimagesheading', 'theme_boost_union', null, true);
    $setting = new admin_setting_heading($name, $title, null);
    $page->add($setting);

    // Replicate the Background image setting from theme_boost.
    $name = 'theme_boost_union/backgroundimage';
    $title = get_string('backgroundimage', 'theme_boost', null, true);
    $description = get_string('backgroundimage_desc', 'theme_boost', null, true);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'backgroundimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Replicate the Login Background image setting from theme_boost.
    $name = 'theme_boost_union/loginbackgroundimage';
    $title = get_string('loginbackgroundimage', 'theme_boost', null, true);
    $description = get_string('loginbackgroundimage_desc', 'theme_boost', null, true);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbackgroundimage');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Create brand colors heading.
    $name = 'theme_boost_union/brandcolorsheading';
    $title = get_string('brandcolorsheading', 'theme_boost_union', null, true);
    $setting = new admin_setting_heading($name, $title, null);
    $page->add($setting);

    // Replicate the Variable $body-color setting from theme_boost.
    $name = 'theme_boost_union/brandcolor';
    $title = get_string('brandcolor', 'theme_boost', null, true);
    $description = get_string('brandcolor_desc', 'theme_boost', null, true);
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Add tab to settings page.
    $settings->add($page);


    // Create blocks tab.
    $page = new admin_settingpage('theme_boost_union_blocks', get_string('blockstab', 'theme_boost_union', null, true));

    // Create blocks general heading.
    $name = 'theme_boost_union/blocksgeneralheading';
    $title = get_string('blocksgeneralheading', 'theme_boost_union', null, true);
    $setting = new admin_setting_heading($name, $title, null);
    $page->add($setting);

    // Replicate the Unaddable blocks setting from theme_boost.
    $name = 'theme_boost_union/unaddableblocks';
    $title = get_string('unaddableblocks', 'theme_boost', null, true);
    $description = get_string('unaddableblocks_desc', 'theme_boost', null, true);
    $default = 'navigation,settings,course_list,section_links';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
    $page->add($setting);

    // Add tab to settings page.
    $settings->add($page);

    // Create info banner settings tab.
    $page = new admin_settingpage('theme_boost_union_infobanner', get_string('infobannersettings',
        'theme_boost_union', null, true));

    // Settings title to group perpetual information banner settings together with a common heading and description.
    $name = 'theme_boost_union/perpetualinfobannerheading';
    $title = get_string('perpetualinfobannerheadingsetting', 'theme_boost_union', null, true);
    $description = get_string('perpetualinfobannerheadingsetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_heading($name, $title, $description);
    $page->add($setting);

    // Activate perpetual information banner.
    $name = 'theme_boost_union/perpibenable';
    $title = get_string('perpibenablesetting', 'theme_boost_union', null, true);
    $description = get_string('perpibenablesetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $page->add($setting);

    // Perpetual information banner content.
    $name = 'theme_boost_union/perpibcontent';
    $title = get_string('perpibcontent', 'theme_boost_union', null, true);
    $description = get_string('perpibcontent_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_confightmleditor($name, $title, $description, '');
    $page->add($setting);
    $settings->hide_if('theme_boost_union/perpibcontent',
        'theme_boost_union/perpibenable', 'notchecked');

    // Select pages on which the perpetual information banner should be shown.
    $name = 'theme_boost_union/perpibshowonpages';
    $title = get_string('perpibshowonpagessetting', 'theme_boost_union', null, true);
    $description = get_string('perpibshowonpagessetting_desc', 'theme_boost_union', null, true);
    $perpibshowonpageoptions = [
        // Don't use string lazy loading (= false) because the string will be directly used and would produce a
        // PHP warning otherwise.
        'mydashboard' => get_string('myhome', 'core', null, false),
        'frontpage' => get_string('sitehome', 'core', null, false),
        'course' => get_string('course', 'core', null, false),
        'login' => get_string('login_page', 'theme_boost_union', null, false)
    ];
    $setting = new admin_setting_configmultiselect($name, $title, $description,
        array($perpibshowonpageoptions['mydashboard']), $perpibshowonpageoptions);
    $page->add($setting);
    $settings->hide_if('theme_boost_union/perpibshowonpages',
        'theme_boost_union/perpibenable', 'notchecked');

    // Select the bootstrap class that should be used for the perpetual info banner.
    $name = 'theme_boost_union/perpibcss';
    $title = get_string('perpibcsssetting', 'theme_boost_union', null, true);
    $description = get_string('perpibcsssetting_desc', 'theme_boost_union', null, true).'<br />'.
        get_string('ibcsssetting_nobootstrap', 'theme_boost_union',
            array('bootstrapnone' => get_string('bootstrapnone', 'theme_boost_union')));
    $perpibcssoptions = [
        // Don't use string lazy loading (= false) because the string will be directly used and would produce a
        // PHP warning otherwise.
        'primary' => get_string('bootstrapprimarycolor', 'theme_boost_union', null, false),
        'secondary' => get_string('bootstrapsecondarycolor', 'theme_boost_union', null, false),
        'success' => get_string('bootstrapsuccesscolor', 'theme_boost_union', null, false),
        'danger' => get_string('bootstrapdangercolor', 'theme_boost_union', null, false),
        'warning' => get_string('bootstrapwarningcolor', 'theme_boost_union', null, false),
        'info' => get_string('bootstrapinfocolor', 'theme_boost_union', null, false),
        'light' => get_string('bootstraplightcolor', 'theme_boost_union', null, false),
        'dark' => get_string('bootstrapdarkcolor', 'theme_boost_union', null, false),
        'none' => get_string('bootstrapnone', 'theme_boost_union', null, false)
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $perpibcssoptions['primary'],
        $perpibcssoptions);
    $page->add($setting);
    $settings->hide_if('theme_boost_union/perpibcss',
        'theme_boost_union/perpibenable', 'notchecked');

    // Perpetual information banner dismissible.
    $name = 'theme_boost_union/perpibdismiss';
    $title = get_string('perpibdismisssetting', 'theme_boost_union', null, true);
    $description = get_string('perpibdismisssetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $page->add($setting);
    $settings->hide_if('theme_boost_union/perpibdismiss',
        'theme_boost_union/perpibenable', 'notchecked');

    // Perpetual information banner show confirmation dialogue when dismissing.
    $name = 'theme_boost_union/perpibconfirm';
    $title = get_string('perpibconfirmsetting', 'theme_boost_union', null, true);
    $description = get_string('perpibconfirmsetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $page->add($setting);
    $settings->hide_if('theme_boost_union/perpibconfirm',
        'theme_boost_union/perpibenable', 'notchecked');
    $settings->hide_if('theme_boost_union/perpibconfirm',
        'theme_boost_union/perpibdismiss', 'notchecked');

    // Reset the user preference for all users.
    $name = 'theme_boost_union/perpibresetvisibility';
    $title = get_string('perpetualinfobannerresetvisiblitysetting', 'theme_boost_union', null, true);
    $description = get_string('perpetualinfobannerresetvisiblitysetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $setting->set_updatedcallback('theme_boost_union_infobanner_reset_visibility');
    $page->add($setting);
    $settings->hide_if('theme_boost_union/perpibresetvisibility',
        'theme_boost_union/perpibenable', 'notchecked');
    $settings->hide_if('theme_boost_union/perpibresetvisibility',
        'theme_boost_union/perpibdismiss', 'notchecked');

    // Settings title to group time controlled information banner settings together with a common heading and description.
    $name = 'theme_boost_union/timedinfobannerheading';
    $title = get_string('timedinfobannerheadingsetting', 'theme_boost_union', null, true);
    $description = get_string('timedinfobannerheadingsetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_heading($name, $title, $description);
    $page->add($setting);

    // Activate time controlled information banner.
    $name = 'theme_boost_union/timedibenable';
    $title = get_string('timedibenablesetting', 'theme_boost_union', null, true);
    $description = get_string('timedibenablesetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $page->add($setting);

    // Time controlled information banner content.
    $name = 'theme_boost_union/timedibcontent';
    $title = get_string('timedibcontent', 'theme_boost_union', null, true);
    $description = get_string('timedibcontent_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_confightmleditor($name, $title, $description, '');
    $page->add($setting);
    $settings->hide_if('theme_boost_union/timedibcontent',
        'theme_boost_union/timedibenable', 'notchecked');

    // Select pages on which the time controlled information banner should be shown.
    $name = 'theme_boost_union/timedibshowonpages';
    $title = get_string('timedibshowonpagessetting', 'theme_boost_union', null, true);
    $description = get_string('timedibshowonpagessetting_desc', 'theme_boost_union', null, true);
    $timedibpageoptions = [
        // Don't use string lazy loading (= false) because the string will be directly used and would produce a
        // PHP warning otherwise.
        'mydashboard' => get_string('myhome', 'core', null, false),
        'frontpage' => get_string('sitehome', 'core', null, false),
        'course' => get_string('course', 'core', null, false),
        'login' => get_string('login_page', 'theme_boost_union', null, false)
    ];
    $setting = new admin_setting_configmultiselect($name, $title, $description,
        array($timedibpageoptions['mydashboard']), $timedibpageoptions);
    $page->add($setting);
    $settings->hide_if('theme_boost_union/timedibshowonpages',
        'theme_boost_union/timedibenable', 'notchecked');

    // Select the bootstrap class that should be used for the perpetual info banner.
    $name = 'theme_boost_union/timedibcss';
    $title = get_string('timedibcsssetting', 'theme_boost_union', null, true);
    $description = get_string('timedibcsssetting_desc', 'theme_boost_union', null, true).'<br />'.
        get_string('ibcsssetting_nobootstrap', 'theme_boost_union',
            array('bootstrapnone' => get_string('bootstrapnone', 'theme_boost_union')));
    $timedibcssoptions = [
        // Don't use string lazy loading (= false) because the string will be directly used and would produce a
        // PHP warning otherwise.
        'primary' => get_string('bootstrapprimarycolor', 'theme_boost_union', null, false),
        'secondary' => get_string('bootstrapsecondarycolor', 'theme_boost_union', null, false),
        'success' => get_string('bootstrapsuccesscolor', 'theme_boost_union', null, false),
        'danger' => get_string('bootstrapdangercolor', 'theme_boost_union', null, false),
        'warning' => get_string('bootstrapwarningcolor', 'theme_boost_union', null, false),
        'info' => get_string('bootstrapinfocolor', 'theme_boost_union', null, false),
        'light' => get_string('bootstraplightcolor', 'theme_boost_union', null, false),
        'dark' => get_string('bootstrapdarkcolor', 'theme_boost_union', null, false),
        'none' => get_string('bootstrapnone', 'theme_boost_union', null, false)
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $timedibcssoptions['primary'],
        $timedibcssoptions);
    $page->add($setting);
    $settings->hide_if('theme_boost_union/timedibcss',
        'theme_boost_union/timedibenable', 'notchecked');

    // This will check for the desired date time format YYYY-MM-DD HH:MM:SS.
    $timeregex = '/(20[0-9]{2}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\s([0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9])|^$/';

    // Start time for controlled information banner.
    $name = 'theme_boost_union/timedibstart';
    $title = get_string('timedibstartsetting', 'theme_boost_union', null, true);
    $description = get_string('timedibstartsetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_configtext($name, $title, $description, '', $timeregex);
    $page->add($setting);
    $settings->hide_if('theme_boost_union/timedibstart',
        'theme_boost_union/timedibenable', 'notchecked');

    // End time for controlled information banner.
    $name = 'theme_boost_union/timedibend';
    $title = get_string('timedibendsetting', 'theme_boost_union', null, true);
    $description = get_string('timedibendsetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_configtext($name, $title, $description, '', $timeregex);
    $page->add($setting);
    $settings->hide_if('theme_boost_union/timedibend',
        'theme_boost_union/timedibenable', 'notchecked');

    // Add tab to settings page.
    $settings->add($page);
}
