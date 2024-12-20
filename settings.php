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
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use theme_boost_union\admin_setting_configdatetime;
use theme_boost_union\admin_setting_configstoredfilealwayscallback;
use theme_boost_union\admin_setting_configtext_url;
use core\di;
use core\hook\manager as hook_manager;

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig || has_capability('theme/boost_union:configure', context_system::instance())) {
    global $PAGE;

    // How this file works:
    // This theme's settings are divided into multiple settings pages.
    // This is quite unusual as Boost themes would have a nice tabbed settings interface.
    // However, as we are using many hide_if constraints for our settings, we would run into the
    // stupid "Too much data passed as arguments to js_call_amd..." debugging message if we would
    // pack all settings onto just one settings page.
    // To achieve this goal, we create a custom admin settings category and fill it with several settings pages.

    // However, there is still the $settings variable which is expected by Moodle core to be filled with the theme
    // settings and which is automatically linked from the theme selector page.
    // To avoid that there appears a broken "Boost Union" settings page, we redirect the user to a settings
    // overview page if he opens this page.
    $mainsettingspageurl = new core\url('/admin/settings.php', ['section' => 'themesettingboost_union']);
    if ($ADMIN->fulltree && $PAGE->has_set_url() && $PAGE->url->compare($mainsettingspageurl)) {
        redirect(new core\url('/theme/boost_union/settings_overview.php'));
    }

    // Create custom admin settings category.
    $ADMIN->add('appearance', new admin_category('theme_boost_union',
            get_string('pluginname', 'theme_boost_union', null, true)));

    // Create empty settings page structure to make the site administration work on non-admin pages.
    if (!$ADMIN->fulltree) {
        // Create Overview page
        // (and allow users with the theme/boost_union:configure capability to access it).
        $overviewpage = new admin_externalpage('theme_boost_union_overview',
                get_string('settingsoverview', 'theme_boost_union', null, true),
                new core\url('/theme/boost_union/settings_overview.php'),
                'theme/boost_union:configure');
        $ADMIN->add('theme_boost_union', $overviewpage);

        // Create Look settings page
        // (and allow users with the theme/boost_union:configure capability to access it).
        $tab = new admin_settingpage('theme_boost_union_look',
                get_string('configtitlelook', 'theme_boost_union', null, true),
                'theme/boost_union:configure');
        $ADMIN->add('theme_boost_union', $tab);

        // Create Feel settings page
        // (and allow users with the theme/boost_union:configure capability to access it).
        $tab = new admin_settingpage('theme_boost_union_feel',
                get_string('configtitlefeel', 'theme_boost_union', null, true),
                'theme/boost_union:configure');
        $ADMIN->add('theme_boost_union', $tab);

        // Create Content settings page
        // (and allow users with the theme/boost_union:configure capability to access it).
        $tab = new admin_settingpage('theme_boost_union_content',
                get_string('configtitlecontent', 'theme_boost_union', null, true),
                'theme/boost_union:configure');
        $ADMIN->add('theme_boost_union', $tab);

        // Create Functionality settings page
        // (and allow users with the theme/boost_union:configure capability to access it).
        $tab = new admin_settingpage('theme_boost_union_functionality',
                get_string('configtitlefunctionality', 'theme_boost_union', null, true),
                'theme/boost_union:configure');
        $ADMIN->add('theme_boost_union', $tab);

        // Create Accessibility settings page
        // (and allow users with the theme/boost_union:configure capability to access it).
        $tab = new admin_settingpage('theme_boost_union_accessibility',
                get_string('configtitleaccessibility', 'theme_boost_union', null, true),
                'theme/boost_union:configure');
        $ADMIN->add('theme_boost_union', $tab);

        // Create Flavours settings page as external page
        // (and allow users with the theme/boost_union:configure capability to access it).
        $flavourspage = new admin_externalpage('theme_boost_union_flavours',
                get_string('configtitleflavours', 'theme_boost_union', null, true),
                new core\url('/theme/boost_union/flavours/overview.php'),
                'theme/boost_union:configure');
        $ADMIN->add('theme_boost_union', $flavourspage);

        // Create Smart Menus settings page as external page.
        // (and allow users with the theme/boost_union:configure capability to access it).
        $smartmenuspage = new admin_externalpage('theme_boost_union_smartmenus',
                get_string('smartmenus', 'theme_boost_union', null, true),
                new core\url('/theme/boost_union/smartmenus/menus.php'),
                'theme/boost_union:configure');
        $ADMIN->add('theme_boost_union', $smartmenuspage);
    }

    // Create full settings page structure.
    else if ($ADMIN->fulltree) { // phpcs:disable moodle.ControlStructures.ControlSignature.Found

        // Require the necessary libraries.
        require_once($CFG->dirroot . '/theme/boost_union/lib.php');
        require_once($CFG->dirroot . '/theme/boost_union/locallib.php');
        require_once($CFG->dirroot . '/course/lib.php');

        // Prepare options array for select settings.
        // Due to MDL-58376, we will use binary select settings instead of checkbox settings throughout this theme.
        $yesnooption = [THEME_BOOST_UNION_SETTING_SELECT_YES => get_string('yes'),
                THEME_BOOST_UNION_SETTING_SELECT_NO => get_string('no'), ];

        // Prepare regular expression for checking if the value is a percent number (from 0% to 100%) or a pixel number
        // (with 3 or 4 digits) or a viewport width number (from 0 to 100).
        $widthregex = '/^((\d{1,2}|100)%)|((\d{1,2}|100)vw)|(\d{3,4}px)$/';

        // Create Look settings page with tabs
        // (and allow users with the theme/boost_union:configure capability to access it).
        $page = new theme_boost_admin_settingspage_tabs('theme_boost_union_look',
                get_string('configtitlelook', 'theme_boost_union', null, true),
                'theme/boost_union:configure');


        // Create general settings tab.
        $tab = new admin_settingpage('theme_boost_union_look_general', get_string('generalsettings', 'theme_boost', null, true));

        // Create theme presets heading.
        $name = 'theme_boost_union/presetheading';
        $preseturl = new core\url('/admin/settings.php', ['section' => 'themesettingboost'], 'theme_boost_general');
        $title = get_string('presetheading', 'theme_boost_union', null, true);
        $description = get_string('presetheading_desc', 'theme_boost_union', null, true).'<br />'.
            // We would love to use $OUTPUT->single_button($preseturl, ...) here, but this results in the fact
            // that the settings page redirects to the Boost Core settings after saving for an unknown reason.
            \core\output\html_writer::link($preseturl,
                    get_string('presetbutton', 'theme_boost_union', null, true),
                    ['class' => 'btn btn-secondary my-3']);
        $setting = new admin_setting_heading($name, $title, $description);
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create SCSS tab.
        $tab = new admin_settingpage('theme_boost_union_look_scss', get_string('scsstab', 'theme_boost_union', null, true));

        // Create Raw SCSS heading.
        $name = 'theme_boost_union/scssheading';
        $title = get_string('scssheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Replicate the Raw initial SCSS setting from theme_boost.
        $name = 'theme_boost_union/scsspre';
        $title = get_string('rawscsspre', 'theme_boost', null, true);
        $description = get_string('rawscsspre_desc', 'theme_boost', null, true);
        $default = '';
        $setting = new admin_setting_scsscode($name, $title, $description, $default, PARAM_RAW);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Replicate the Raw SCSS setting from theme_boost.
        $name = 'theme_boost_union/scss';
        $title = get_string('rawscss', 'theme_boost', null, true);
        $description = get_string('rawscss_desc', 'theme_boost', null, true);
        $default = '';
        $setting = new admin_setting_scsscode($name, $title, $description, $default, PARAM_RAW);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create external SCSS heading.
        $name = 'theme_boost_union/extscssheading';
        $title = get_string('extscssheading', 'theme_boost_union', null, true);
        $taskurl = new core\url('/admin/tool/task/scheduledtasks.php',
                ['action' => 'edit', 'task' => 'theme_boost_union\task\purge_cache']);
        $description = get_string('extscssheading_desc', 'theme_boost_union', null, true).'<br /><br />'.
                get_string('extscssheading_instr', 'theme_boost_union', null, true).
                '<ul><li>'.get_string('extscssheading_sources', 'theme_boost_union', null, true).'</li>'.
                '<li>'.get_string('extscssheading_prepost', 'theme_boost_union', null, true).'</li>'.
                '<li>'.get_string('extscssheading_structure', 'theme_boost_union', null, true).'</li>'.
                '<li>'.get_string('extscssheading_drop', 'theme_boost_union', null, true).'</li>'.
                '<li>'.get_string('extscssheading_task', 'theme_boost_union', $taskurl->out(), true).'</li></ul>';
        $setting = new admin_setting_heading($name, $title, $description);
        $tab->add($setting);

        // Setting: External SCSS source.
        $extscsssourceoptions = [
                THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_NONE =>
                        get_string('extscsssourcenone', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_DOWNLOAD =>
                        get_string('extscsssourcedownload', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_GITHUB =>
                        get_string('extscsssourcegithub', 'theme_boost_union'),
        ];
        $name = 'theme_boost_union/extscsssource';
        $title = get_string('extscsssource', 'theme_boost_union', null, true);
        $description = get_string('extscsssource_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_NONE, $extscsssourceoptions);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: External Pre SCSS download URL.
        $name = 'theme_boost_union/extscssurlpre';
        $title = get_string('extscssurlpre', 'theme_boost_union', null, true);
        $description = get_string('extscssurlpre_desc', 'theme_boost_union', null, true);
        $default = '';
        $setting = new admin_setting_configtext_url($name, $title, $description, $default, PARAM_URL);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/extscssurlpre', 'theme_boost_union/extscsssource', 'neq',
                THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_DOWNLOAD);

        // Setting: External Post SCSS download URL.
        $name = 'theme_boost_union/extscssurlpost';
        $title = get_string('extscssurlpost', 'theme_boost_union', null, true);
        $description = get_string('extscssurlpost_desc', 'theme_boost_union', null, true);
        $default = '';
        $setting = new admin_setting_configtext_url($name, $title, $description, $default, PARAM_URL);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/extscssurlpost', 'theme_boost_union/extscsssource', 'neq',
                THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_DOWNLOAD);

        // Setting: External SCSS Github API token.
        $name = 'theme_boost_union/extscssgithubtoken';
        $title = get_string('extscssgithubtoken', 'theme_boost_union', null, true);
        $description = get_string('extscssgithubtoken_desc', 'theme_boost_union', null, true).'<br />'.
                get_string('extscssgithubtoken_docs', 'theme_boost_union', null, true);
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_ALPHANUMEXT);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/extscssgithubtoken', 'theme_boost_union/extscsssource', 'neq',
                THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_GITHUB);

        // Setting: External SCSS Github API user.
        $name = 'theme_boost_union/extscssgithubuser';
        $title = get_string('extscssgithubuser', 'theme_boost_union', null, true);
        $description = get_string('extscssgithubuser_desc', 'theme_boost_union', null, true).'<br />'.
                get_string('extscssgithubuser_example', 'theme_boost_union', null, true);
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_ALPHANUMEXT);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/extscssgithubuser', 'theme_boost_union/extscsssource', 'neq',
                THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_GITHUB);

        // Setting: External SCSS Github API repository.
        $name = 'theme_boost_union/extscssgithubrepo';
        $title = get_string('extscssgithubrepo', 'theme_boost_union', null, true);
        $description = get_string('extscssgithubrepo_desc', 'theme_boost_union', null, true).'<br />'.
                get_string('extscssgithubrepo_example', 'theme_boost_union', null, true);
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_ALPHANUMEXT);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/extscssgithubrepo', 'theme_boost_union/extscsssource', 'neq',
                THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_GITHUB);

        // Setting: External Pre SCSS Github file path.
        $name = 'theme_boost_union/extscssgithubprefilepath';
        $title = get_string('extscssgithubprefilepath', 'theme_boost_union', null, true);
        $description = get_string('extscssgithubprefilepath_desc', 'theme_boost_union', null, true).'<br />'.
                get_string('extscssgithubfilepath_example', 'theme_boost_union', null, true);
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_PATH);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/extscssgithubprefilepath', 'theme_boost_union/extscsssource', 'neq',
                THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_GITHUB);

        // Setting: External Post SCSS Github file path.
        $name = 'theme_boost_union/extscssgithubpostfilepath';
        $title = get_string('extscssgithubpostfilepath', 'theme_boost_union', null, true);
        $description = get_string('extscssgithubpostfilepath_desc', 'theme_boost_union', null, true).'<br />'.
                get_string('extscssgithubfilepath_example', 'theme_boost_union', null, true);
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_PATH);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/extscssgithubpostfilepath', 'theme_boost_union/extscsssource', 'neq',
                THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_GITHUB);

        // Setting: External SCSS validation.
        $name = 'theme_boost_union/extscssvalidation';
        $title = get_string('extscssvalidationsetting', 'theme_boost_union', null, true);
        $description = get_string('extscssvalidationsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_YES, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/extscssvalidation', 'theme_boost_union/extscsssource', 'eq',
                THEME_BOOST_UNION_SETTING_EXTSCSSSOURCE_NONE);

        // Add tab to settings page.
        $page->add($tab);


        // Create page tab.
        $tab = new admin_settingpage('theme_boost_union_look_page', get_string('pagetab', 'theme_boost_union', null, true));

        // Create page width heading.
        $name = 'theme_boost_union/pagewidthheading';
        $title = get_string('pagewidthheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Course content max width.
        $name = 'theme_boost_union/coursecontentmaxwidth';
        $title = get_string('coursecontentmaxwidthsetting', 'theme_boost_union', null, true);
        $description = get_string('coursecontentmaxwidthsetting_desc', 'theme_boost_union', null, true);
        $default = '830px';
        $setting = new admin_setting_configtext($name, $title, $description, $default, $widthregex, 6);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Medium content max width.
        $name = 'theme_boost_union/mediumcontentmaxwidth';
        $title = get_string('mediumcontentmaxwidthsetting', 'theme_boost_union', null, true);
        $description = get_string('mediumcontentmaxwidthsetting_desc', 'theme_boost_union', null, true);
        $default = '1120px';
        $setting = new admin_setting_configtext($name, $title, $description, $default, $widthregex, 6);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create drawer width heading.
        $name = 'theme_boost_union/drawerwidthheading';
        $title = get_string('drawerwidthheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Course index drawer width.
        $name = 'theme_boost_union/courseindexdrawerwidth';
        $title = get_string('courseindexdrawerwidthsetting', 'theme_boost_union', null, true);
        $description = get_string('courseindexdrawerwidthsetting_desc', 'theme_boost_union', null, true);
        $default = '285px';
        $setting = new admin_setting_configtext($name, $title, $description, $default, $widthregex, 6);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Block drawer width.
        $name = 'theme_boost_union/blockdrawerwidth';
        $title = get_string('blockdrawerwidthsetting', 'theme_boost_union', null, true);
        $description = get_string('blockdrawerwidthsetting_desc', 'theme_boost_union', null, true);
        $default = '315px';
        $setting = new admin_setting_configtext($name, $title, $description, $default, $widthregex, 6);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create site branding tab.
        $tab = new admin_settingpage('theme_boost_union_look_sitebranding',
                get_string('sitebrandingtab', 'theme_boost_union', null, true));

        // Create logos heading.
        $name = 'theme_boost_union/logosheading';
        $title = get_string('logosheading', 'theme_boost_union', null, true);
        $notificationurl = new core\url('/admin/settings.php', ['section' => 'logos']);
        $notification = new \core\output\notification(get_string('logosheading_desc', 'theme_boost_union', $notificationurl->out()),
                \core\output\notification::NOTIFY_INFO);
        $notification->set_show_closebutton(false);
        $description = $OUTPUT->render($notification);
        $setting = new admin_setting_heading($name, $title, $description);
        $tab->add($setting);

        // Replicate the logo setting from core_admin.
        $name = 'theme_boost_union/logo';
        $title = get_string('logosetting', 'theme_boost_union', null, true);
        $description = get_string('logosetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo', 0,
                ['maxfiles' => 1, 'accepted_types' => 'web_image']);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Replicate the compact logo setting from core_admin.
        $name = 'theme_boost_union/logocompact';
        $title = get_string('logocompactsetting', 'theme_boost_union', null, true);
        $description = get_string('logocompactsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'logocompact', 0,
                ['maxfiles' => 1, 'accepted_types' => 'web_image']);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create favicon heading.
        $name = 'theme_boost_union/faviconheading';
        $title = get_string('faviconheading', 'theme_boost_union', null, true);
        $notificationurl = new core\url('/admin/settings.php', ['section' => 'logos']);
        $notification = new \core\output\notification(get_string('faviconheading_desc', 'theme_boost_union',
                $notificationurl->out()), \core\output\notification::NOTIFY_INFO);
        $notification->set_show_closebutton(false);
        $description = $OUTPUT->render($notification);
        $setting = new admin_setting_heading($name, $title, $description);
        $tab->add($setting);

        // Replicate the favicon setting from core_admin.
        $name = 'theme_boost_union/favicon';
        $title = get_string('faviconsetting', 'theme_boost_union', null, true);
        $description = get_string('faviconsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0,
                ['maxfiles' => 1, 'accepted_types' => 'image']);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create background images heading.
        $name = 'theme_boost_union/backgroundimagesheading';
        $title = get_string('backgroundimagesheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Replicate the Background image setting from theme_boost.
        $name = 'theme_boost_union/backgroundimage';
        $title = get_string('backgroundimagesetting', 'theme_boost_union', null, true);
        $description = get_string('backgroundimagesetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'backgroundimage', 0,
                ['maxfiles' => 1, 'accepted_types' => 'web_image']);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Background image position.
        $name = 'theme_boost_union/backgroundimageposition';
        $title = get_string('backgroundimagepositionsetting', 'theme_boost_union', null, true);
        $description = get_string('backgroundimagepositionsetting_desc', 'theme_boost_union', null, true);
        $backgroundimagepositionoptions = [
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM, ];
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP, $backgroundimagepositionoptions);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create brand colors heading.
        $name = 'theme_boost_union/brandcolorsheading';
        $title = get_string('brandcolorsheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Replicate the brand color setting from theme_boost.
        $name = 'theme_boost_union/brandcolor';
        $title = get_string('brandcolor', 'theme_boost', null, true);
        $description = get_string('brandcolor_desc', 'theme_boost', null, true);
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create Bootstrap colors heading.
        $name = 'theme_boost_union/bootstrapcolorsheading';
        $title = get_string('bootstrapcolorsheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Bootstrap color for 'success'.
        $name = 'theme_boost_union/bootstrapcolorsuccess';
        $title = get_string('bootstrapcolorsuccesssetting', 'theme_boost_union', null, true);
        $description = get_string('bootstrapcolorsuccesssetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Bootstrap color for 'info'.
        $name = 'theme_boost_union/bootstrapcolorinfo';
        $title = get_string('bootstrapcolorinfosetting', 'theme_boost_union', null, true);
        $description = get_string('bootstrapcolorinfosetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Bootstrap color for 'warning'.
        $name = 'theme_boost_union/bootstrapcolorwarning';
        $title = get_string('bootstrapcolorwarningsetting', 'theme_boost_union', null, true);
        $description = get_string('bootstrapcolorwarningsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Bootstrap color for 'danger'.
        $name = 'theme_boost_union/bootstrapcolordanger';
        $title = get_string('bootstrapcolordangersetting', 'theme_boost_union', null, true);
        $description = get_string('bootstrapcolordangersetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create navbar heading.
        $name = 'theme_boost_union/navbarheading';
        $title = get_string('navbarheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Navbar color.
        $name = 'theme_boost_union/navbarcolor';
        $title = get_string('navbarcolorsetting', 'theme_boost_union', null, true);
        $description = get_string('navbarcolorsetting_desc', 'theme_boost_union', null, true);
        $navbarcoloroptions = [
                THEME_BOOST_UNION_SETTING_NAVBARCOLOR_LIGHT =>
                        get_string('navbarcolorsetting_light', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_NAVBARCOLOR_DARK =>
                        get_string('navbarcolorsetting_dark', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_NAVBARCOLOR_PRIMARYLIGHT =>
                        get_string('navbarcolorsetting_primarylight', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_NAVBARCOLOR_PRIMARYDARK =>
                        get_string('navbarcolorsetting_primarydark', 'theme_boost_union'), ];
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_NAVBARCOLOR_LIGHT,
                $navbarcoloroptions);
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create activity branding tab.
        $tab = new admin_settingpage('theme_boost_union_look_activitybranding',
                get_string('activitybrandingtab', 'theme_boost_union', null, true));

        // Create activity icon colors heading.
        $name = 'theme_boost_union/activityiconcolorsheading';
        $title = get_string('activityiconcolorsheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Define all activity icon purposes (without the 'other' purpose as this is not branded).
        $purposes = [MOD_PURPOSE_ADMINISTRATION,
                MOD_PURPOSE_ASSESSMENT,
                MOD_PURPOSE_COLLABORATION,
                MOD_PURPOSE_COMMUNICATION,
                MOD_PURPOSE_CONTENT,
                MOD_PURPOSE_INTERACTIVECONTENT,
                MOD_PURPOSE_INTERFACE];
        // Iterate over all purposes.
        foreach ($purposes as $purpose) {
            // Setting: Activity icon color.
            $name = 'theme_boost_union/activityiconcolor'.$purpose;
            $title = get_string('activityiconcolor'.$purpose.'setting', 'theme_boost_union', null, true);
            $description = get_string('activityiconcolor'.$purpose.'setting_desc', 'theme_boost_union', null, true);
            $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
            $setting->set_updatedcallback('theme_reset_all_caches');
            $tab->add($setting);
        }

        // Setting: Activity icon color fidelity.
        $name = 'theme_boost_union/activityiconcolorfidelity';
        $title = get_string('activityiconcolorfidelitysetting', 'theme_boost_union', null, true);
        $description = get_string('activityiconcolorfidelitysetting_desc', 'theme_boost_union', null, true);
        $activityiconcolorfidelityoptions = [
                1 => get_string('activityiconcolorfidelity_oneshot', 'theme_boost_union'),
                10 => get_string('activityiconcolorfidelity_sometries', 'theme_boost_union'),
                100 => get_string('activityiconcolorfidelity_detailled', 'theme_boost_union'),
                500 => get_string('activityiconcolorfidelity_insane', 'theme_boost_union'),
            ];
        $setting = new admin_setting_configselect($name, $title, $description, 1, $activityiconcolorfidelityoptions);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create activity icons purpose heading.
        $name = 'theme_boost_union/activitypurposeheading';
        $title = get_string('activitypurposeheading', 'theme_boost_union', null, true);
        $description = get_string('activitypurposeheading_desc', 'theme_boost_union', null, true).'<br /><br />'.
                get_string('activitypurposeheadingtechnote', 'theme_boost_union',
                        get_string('githubissueslink', 'theme_boost_union', null, true),
                true);
        $setting = new admin_setting_heading($name, $title, $description);
        $tab->add($setting);

        // Prepare activity purposes.
        $purposesoptions = [
                MOD_PURPOSE_ADMINISTRATION => get_string('activitypurposeadministration', 'theme_boost_union'),
                MOD_PURPOSE_ASSESSMENT => get_string('activitypurposeassessment', 'theme_boost_union'),
                MOD_PURPOSE_COLLABORATION => get_string('activitypurposecollaboration', 'theme_boost_union'),
                MOD_PURPOSE_COMMUNICATION => get_string('activitypurposecommunication', 'theme_boost_union'),
                MOD_PURPOSE_CONTENT => get_string('activitypurposecontent', 'theme_boost_union'),
                MOD_PURPOSE_INTERACTIVECONTENT => get_string('activitypurposeinteractivecontent', 'theme_boost_union'),
                MOD_PURPOSE_INTERFACE => get_string('activitypurposeinterface', 'theme_boost_union'),
                MOD_PURPOSE_OTHER => get_string('activitypurposeother', 'theme_boost_union'),
        ];
        // Get installed activity modules.
        $installedactivities = get_module_types_names();
        // Iterate over all existing activities.
        foreach ($installedactivities as $modname => $modinfo) {
            // Get default purpose of activity module.
            $defaultpurpose = plugin_supports('mod', $modname, FEATURE_MOD_PURPOSE, MOD_PURPOSE_OTHER);
            // If the plugin does not have any default purpose.
            if (!$defaultpurpose) {
                // Fallback to "other" purpose.
                $defaultpurpose = MOD_PURPOSE_OTHER;
            }

            // Create the setting.
            $name = 'theme_boost_union/activitypurpose'.$modname;
            $title = get_string('modulename', $modname, null, true);
            $description = '';
            $setting = new admin_setting_configselect($name, $title, $description, $defaultpurpose, $purposesoptions);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $tab->add($setting);
        }

        // Create activity icons heading.
        $name = 'theme_boost_union/modicons';
        $title = get_string('modiconsheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Enable custom icons for activities and resources.
        $name = 'theme_boost_union/modiconsenable';
        $title = get_string('modiconsenablesetting', 'theme_boost_union', null, true);
        $description = get_string('modiconsenablesetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_boost_union_check_mod_icons_cleanup');
        $tab->add($setting);

        // Setting: Custom icon files.
        $name = 'theme_boost_union/modiconsfiles';
        $title = get_string('modiconsfiles', 'theme_boost_union', null, true);
        $description = get_string('modiconsfiles_desc', 'theme_boost_union', null, true).'<br /><br />'.
                get_string('modiconsfileshowto', 'theme_boost_union', null, true).'<br /><br />'.
                get_string('modiconsfilestech', 'theme_boost_union', null, true);
        // Use our enhanced implementation of admin_setting_configstoredfile to circumvent MDL-59082.
        // This can be changed back to admin_setting_configstoredfile as soon as MDL-59082 is fixed.
        $setting = new admin_setting_configstoredfilealwayscallback($name, $title, $description, 'modicons', 0,
                ['maxfiles' => -1, 'subdirs' => 1, 'accepted_types' => ['.png', '.svg']]);
        $setting->set_updatedcallback('theme_boost_union_place_mod_icons');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/modiconsfiles', 'theme_boost_union/modiconsenable', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Information: Custom icons files list.
        // If there is at least one file uploaded and if custom icons are enabled (unfortunately, hide_if does not
        // work for admin_setting_description up to now, that's why we have to use this workaround).
        $modiconsenableconfig = get_config('theme_boost_union', 'modiconsenable');
        if ($modiconsenableconfig == THEME_BOOST_UNION_SETTING_SELECT_YES &&
                !empty(get_config('theme_boost_union', 'modiconsfiles'))) {
            // Prepare the widget.
            $name = 'theme_boost_union/modiconlist';
            $title = get_string('modiconlistsetting', 'theme_boost_union', null, true);
            $description = get_string('modiconlistsetting_desc', 'theme_boost_union', null, true);

            // Append the file list to the description.
            $templatecontext = ['files' => theme_boost_union_get_modicon_templatecontext()];
            $description .= $OUTPUT->render_from_template('theme_boost_union/settings-modicon-filelist', $templatecontext);

            // Finish the widget.
            $setting = new admin_setting_description($name, $title, $description);
            $tab->add($setting);
        }

        // Add tab to settings page.
        $page->add($tab);


        // Create login page tab.
        $tab = new admin_settingpage('theme_boost_union_look_loginpage',
                get_string('loginpagetab', 'theme_boost_union', null, true));

        // Create login page background images heading.
        $name = 'theme_boost_union/loginbackgroundimagesheading';
        $title = get_string('loginbackgroundimagesheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Login page background image.
        $name = 'theme_boost_union/loginbackgroundimage';
        $title = get_string('loginbackgroundimage', 'theme_boost_union', null, true);
        $description = get_string('loginbackgroundimage_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbackgroundimage', 0,
                ['maxfiles' => 25, 'accepted_types' => 'web_image']);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Login page background image position.
        $name = 'theme_boost_union/loginbackgroundimageposition';
        $title = get_string('loginbackgroundimagepositionsetting', 'theme_boost_union', null, true);
        $description = get_string('loginbackgroundimagepositionsetting_desc', 'theme_boost_union', null, true);
        $loginbackgroundimagepositionoptions = [
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM, ];
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP, $loginbackgroundimagepositionoptions);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Login page background image text.
        $name = 'theme_boost_union/loginbackgroundimagetext';
        $title = get_string('loginbackgroundimagetextsetting', 'theme_boost_union', null, true);
        $description = get_string('loginbackgroundimagetextsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configtextarea($name, $title, $description, null, PARAM_TEXT);
        $tab->add($setting);

        // Create login form heading.
        $name = 'theme_boost_union/loginformheading';
        $title = get_string('loginformheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Login form position.
        $name = 'theme_boost_union/loginformposition';
        $title = get_string('loginformpositionsetting', 'theme_boost_union', null, true);
        $description = get_string('loginformpositionsetting_desc', 'theme_boost_union', null, true);
        $loginformoptions = [
                THEME_BOOST_UNION_SETTING_LOGINFORMPOS_CENTER => get_string('loginformpositionsetting_center', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_LOGINFORMPOS_LEFT => get_string('loginformpositionsetting_left', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_LOGINFORMPOS_RIGHT =>
                        get_string('loginformpositionsetting_right', 'theme_boost_union'), ];
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_LOGINFORMPOS_CENTER,
                $loginformoptions);
        $tab->add($setting);

        // Setting: Login form transparency.
        $name = 'theme_boost_union/loginformtransparency';
        $title = get_string('loginformtransparencysetting', 'theme_boost_union', null, true);
        $description = get_string('loginformtransparencysetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Create login providers heading.
        $name = 'theme_boost_union/loginprovidersheading';
        $title = get_string('loginprovidersheading', 'theme_boost_union', null, true);
        $notificationurl = new core\url('/admin/settings.php', ['section' => 'manageauths']);
        $notificationsettingname = get_string('showloginform', 'core_auth');
        $notification = new \core\output\notification(
            get_string('loginprovidersheading_desc', 'theme_boost_union',
            ['settingname' => $notificationsettingname, 'url' => $notificationurl->out()]),
            \core\output\notification::NOTIFY_INFO
        );
        $notification->set_show_closebutton(false);
        $description = $OUTPUT->render($notification);
        $setting = new admin_setting_heading($name, $title, $description);
        $tab->add($setting);

        // Setting: Local login.
        $name = 'theme_boost_union/loginlocalloginenable';
        $title = get_string('loginlocalloginenablesetting', 'theme_boost_union', null, true);
        $localloginurl = new core\url('/theme/boost_union/locallogin.php');
        $description = get_string('loginlocalloginenablesetting_desc', 'theme_boost_union', null, true).'<br /><br />'.
                get_string('loginlocalloginenablesetting_note', 'theme_boost_union', ['url' => $localloginurl], true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_YES, $yesnooption);
        $tab->add($setting);

        // Setting: Local login intro.
        $name = 'theme_boost_union/loginlocalshowintro';
        $title = get_string('loginlocalshowintrosetting', 'theme_boost_union', null, true);
        $description = get_string('loginlocalshowintrosetting_desc', 'theme_boost_union',
                get_string('loginlocalintro', 'theme_boost_union'), true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/loginlocalshowintro', 'theme_boost_union/loginlocalloginenable', 'neq',
            THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: IDP login intro.
        $name = 'theme_boost_union/loginidpshowintro';
        $title = get_string('loginidpshowintrosetting', 'theme_boost_union', null, true);
        $description = get_string('loginidpshowintrosetting_desc', 'theme_boost_union', get_string('potentialidps', 'auth'), true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_YES, $yesnooption);
        $tab->add($setting);

        // Create login order heading.
        $name = 'theme_boost_union/loginorderheading';
        $title = get_string('loginorderheading', 'theme_boost_union', null, true);
        $description = get_string('loginorderheading_desc', 'theme_boost_union', null, true).'<br /><br />'.
                get_string('loginorderheading_note', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, $description);
        $tab->add($setting);

        // Create the login order settings without code duplication.
        $loginmethods = theme_boost_union_get_loginpage_methods();
        $loginmethodsoptions = [];
        foreach ($loginmethods as $key => $lm) {
            $loginmethodsoptions[$key] = $key;
        }
        foreach ($loginmethods as $key => $lm) {
            $name = 'theme_boost_union/loginorder'.$lm;
            $title = get_string('loginorder'.$lm.'setting', 'theme_boost_union', null, true);
            $setting = new admin_setting_configselect($name, $title, null, $key, $loginmethodsoptions);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $tab->add($setting);
        }

        // Add tab to settings page.
        $page->add($tab);


        // Create Dashboard / My courses tab.
        $tab = new admin_settingpage('theme_boost_union_look_dashboard',
                get_string('dashboardtab', 'theme_boost_union', null, true));

        // Create Course overview block heading.
        $name = 'theme_boost_union/courseoverviewheading';
        $title = get_string('courseoverviewheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Prepare show course images options.
        $showcourseimagesoptions = [
                THEME_BOOST_UNION_SETTING_COURSEOVERVIEW_SHOWCOURSEIMAGES_CARD => get_string('card', 'block_myoverview'),
                THEME_BOOST_UNION_SETTING_COURSEOVERVIEW_SHOWCOURSEIMAGES_LIST => get_string('list', 'block_myoverview'),
                THEME_BOOST_UNION_SETTING_COURSEOVERVIEW_SHOWCOURSEIMAGES_SUMMARY => get_string('summary', 'block_myoverview'),
        ];
        // Setting: Show course images.
        $name = 'theme_boost_union/courseoverviewshowcourseimages';
        $title = get_string('courseoverviewshowcourseimagessetting', 'theme_boost_union', null, true);
        $description = get_string('courseoverviewshowcourseimagessetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configmulticheckbox($name, $title, $description, $showcourseimagesoptions,
                $showcourseimagesoptions);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Show course progress.
        $name = 'theme_boost_union/courseoverviewshowcourseprogress';
        $title = get_string('courseoverviewshowprogresssetting', 'theme_boost_union', null, true);
        $description = get_string('courseoverviewshowprogresssetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_YES, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create Blocks tab.
        $tab = new admin_settingpage('theme_boost_union_look_blocks',
            get_string('blockstab', 'theme_boost_union', null, true));

        // Create Timeline block heading.
        $name = 'theme_boost_union/timelineheading';
        $title = get_string('timelineheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Tint activity icons in the timeline block.
        $name = 'theme_boost_union/timelinetintenabled';
        $title = get_string('timelinetintenabled', 'theme_boost_union', null, true);
        $description = get_string('timelinetintenabled_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create Upcoming events block heading.
        $name = 'theme_boost_union/upcomingeventsheading';
        $title = get_string('upcomingeventsheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Tint activity icons in the upcoming events block.
        $name = 'theme_boost_union/upcomingeventstintenabled';
        $title = get_string('upcomingeventstintenabled', 'theme_boost_union', null, true);
        $description = get_string('upcomingeventstintenabled_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create Recently accessed items block heading.
        $name = 'theme_boost_union/recentlyaccesseditemsheading';
        $title = get_string('recentlyaccesseditemsheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Tint activity icons in the recently accessed items block.
        $name = 'theme_boost_union/recentlyaccesseditemstintenabled';
        $title = get_string('recentlyaccesseditemstintenabled', 'theme_boost_union', null, true);
        $description = get_string('recentlyaccesseditemstintenabled_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create Activities block heading.
        $name = 'theme_boost_union/activitiesheading';
        $title = get_string('activitiesheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Tint activity icons in the activities block.
        $name = 'theme_boost_union/activitiestintenabled';
        $title = get_string('activitiestintenabled', 'theme_boost_union', null, true);
        $description = get_string('activitiestintenabled_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create course tab.
        $tab = new admin_settingpage('theme_boost_union_look_course',
                get_string('coursetab', 'theme_boost_union', null, true));

        // Create course header heading.
        $name = 'theme_boost_union/courseheaderheading';
        $title = get_string('courseheaderheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Display the course image in the course header.
        $name = 'theme_boost_union/courseheaderimageenabled';
        $title = get_string('courseheaderimageenabled', 'theme_boost_union', null, true);
        $description = get_string('courseheaderimageenabled_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Setting: Fallback course header image.
        $name = 'theme_boost_union/courseheaderimagefallback';
        $title = get_string('courseheaderimagefallback', 'theme_boost_union', null, true);
        $description = get_string('courseheaderimagefallback_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'courseheaderimagefallback', 0,
                ['maxfiles' => 1, 'accepted_types' => 'web_image']);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/courseheaderimagefallback', 'theme_boost_union/courseheaderimageenabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Course header image layout.
        $name = 'theme_boost_union/courseheaderimagelayout';
        $title = get_string('courseheaderimagelayout', 'theme_boost_union', null, true);
        $description = get_string('courseheaderimagelayout_desc', 'theme_boost_union', null, true);
        $courseheaderimagelayoutoptions = [
                THEME_BOOST_UNION_SETTING_COURSEIMAGELAYOUT_STACKEDDARK =>
                        get_string('courseheaderimagelayoutstackeddark', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_COURSEIMAGELAYOUT_STACKEDLIGHT =>
                        get_string('courseheaderimagelayoutstackedlight', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_COURSEIMAGELAYOUT_HEADINGABOVE =>
                        get_string('courseheaderimagelayoutheadingabove', 'theme_boost_union'), ];
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_COURSEIMAGELAYOUT_HEADINGABOVE, $courseheaderimagelayoutoptions);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/courseheaderimagelayout', 'theme_boost_union/courseheaderimageenabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Course header image height.
        $name = 'theme_boost_union/courseheaderimageheight';
        $title = get_string('courseheaderimageheight', 'theme_boost_union', null, true);
        $description = get_string('courseheaderimageheight_desc', 'theme_boost_union', null, true);
        $courseheaderimageheightoptions = [
                THEME_BOOST_UNION_SETTING_HEIGHT_100PX => THEME_BOOST_UNION_SETTING_HEIGHT_100PX,
                THEME_BOOST_UNION_SETTING_HEIGHT_150PX => THEME_BOOST_UNION_SETTING_HEIGHT_150PX,
                THEME_BOOST_UNION_SETTING_HEIGHT_200PX => THEME_BOOST_UNION_SETTING_HEIGHT_200PX,
                THEME_BOOST_UNION_SETTING_HEIGHT_250PX => THEME_BOOST_UNION_SETTING_HEIGHT_250PX, ];
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_HEIGHT_150PX,
                $courseheaderimageheightoptions);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/courseheaderimageheight', 'theme_boost_union/courseheaderimageenabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Course header image position.
        $name = 'theme_boost_union/courseheaderimageposition';
        $title = get_string('courseheaderimageposition', 'theme_boost_union', null, true);
        $description = get_string('courseheaderimageposition_desc', 'theme_boost_union', null, true);
        $courseheaderimagepositionoptions = [
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM, ];
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER, $courseheaderimagepositionoptions);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/courseheaderimageposition', 'theme_boost_union/courseheaderimageenabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Create course index heading.
        $name = 'theme_boost_union/courseindexheading';
        $title = get_string('courseindexheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Display activity type icons in course index.
        $name = 'theme_boost_union/courseindexmodiconenabled';
        $title = get_string('courseindexmodiconenabled', 'theme_boost_union', null, true);
        $description = get_string('courseindexmodiconenabled_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Setting:  Position of activity completion indication.
        $name = 'theme_boost_union/courseindexcompletioninfoposition';
        $title = get_string('courseindexcompletioninfoposition', 'theme_boost_union', null, true);
        $description = get_string('courseindexcompletioninfoposition_desc', 'theme_boost_union', null, true);
        $courseindexcompletioninfopositionoptions = [
                THEME_BOOST_UNION_SETTING_COMPLETIONINFOPOSITION_ENDOFLINE =>
                        get_string('courseindexcompletioninfopositionendofline', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_COMPLETIONINFOPOSITION_STARTOFLINE =>
                        get_string('courseindexcompletioninfopositionstartofline', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_COMPLETIONINFOPOSITION_ICONCOLOR =>
                        get_string('courseindexcompletioninfopositioniconcolor', 'theme_boost_union'), ];
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_COMPLETIONINFOPOSITION_ENDOFLINE, $courseindexcompletioninfopositionoptions);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/courseindexcompletioninfoposition', 'theme_boost_union/courseindexmodiconenabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Add tab to settings page.
        $page->add($tab);


        // Create E_Mail branding tab.
        $tab = new admin_settingpage('theme_boost_union_look_emailbranding',
                get_string('emailbrandingtab', 'theme_boost_union', null, true));

        // Create E_Mail branding introduction heading.
        $name = 'theme_boost_union/emailbrandingintroheading';
        $title = get_string('emailbrandingintroheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Create E-Mail branding introduction note.
        $name = 'theme_boost_union/emailbrandingintronote';
        $title = '';
        $description = '<div class="alert alert-info" role="alert">'.
                get_string('emailbrandingintronote', 'theme_boost_union', null, true).'</div>';
        $setting = new admin_setting_description($name, $title, $description);
        $tab->add($setting);

        // Create E-Mail branding instruction.
        $name = 'theme_boost_union/emailbrandinginstruction';
        $title = '';
        $description = '<h4>'.get_string('emailbrandinginstruction', 'theme_boost_union', null, true).'</h4>';
        $description .= '<p>'.get_string('emailbrandinginstruction0', 'theme_boost_union', null, true).'</p>';
        $emailbrandinginstructionli1url = new core\url('/admin/tool/customlang/index.php', ['lng' => $CFG->lang]);
        $description .= '<ul><li>'.get_string('emailbrandinginstructionli1', 'theme_boost_union',
                ['url' => $emailbrandinginstructionli1url->out(), 'lang' => $CFG->lang], true).'</li>';
        $description .= '<li>'.get_string('emailbrandinginstructionli2', 'theme_boost_union', null, true).'</li>';
        $description .= '<ul><li>'.get_string('emailbrandinginstructionli2li1', 'theme_boost_union', null, true).'</li>';
        $description .= '<li>'.get_string('emailbrandinginstructionli2li2', 'theme_boost_union', null, true).'</li>';
        $description .= '<li>'.get_string('emailbrandinginstructionli2li3', 'theme_boost_union', null, true).'</li>';
        $description .= '<li>'.get_string('emailbrandinginstructionli2li4', 'theme_boost_union', null, true).'</li></ul>';
        $description .= '<li>'.get_string('emailbrandinginstructionli3', 'theme_boost_union', null, true).'</li>';
        $description .= '<li>'.get_string('emailbrandinginstructionli4', 'theme_boost_union', null, true).'</li></ul>';
        $description .= '<h4>'.get_string('emailbrandingpitfalls', 'theme_boost_union', null, true).'</h4>';
        $description .= '<p>'.get_string('emailbrandingpitfalls0', 'theme_boost_union', null, true).'</p>';
        $description .= '<ul><li>'.get_string('emailbrandingpitfallsli1', 'theme_boost_union', null, true).'</li>';
        $description .= '<li>'.get_string('emailbrandingpitfallsli2', 'theme_boost_union', null, true).'</li>';
        $description .= '<li>'.get_string('emailbrandingpitfallsli3', 'theme_boost_union', null, true).'</li>';
        $description .= '<li>'.get_string('emailbrandingpitfallsli4', 'theme_boost_union', null, true).'</li>';
        $description .= '<li>'.get_string('emailbrandingpitfallsli5', 'theme_boost_union', null, true).'</li>';
        $description .= '<li>'.get_string('emailbrandingpitfallsli6', 'theme_boost_union', null, true).'</li></ul>';
        $setting = new admin_setting_description($name, $title, $description);
        $tab->add($setting);

        // Create HTML E-Mails heading.
        $name = 'theme_boost_union/emailbrandinghtmlheading';
        $title = get_string('emailbrandinghtmlheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Get HTML E-Mail preview.
        $htmlpreview = theme_boost_union_get_emailbrandinghtmlpreview();

        // If the HTML E-Mails are customized.
        if ($htmlpreview != null) {
            // Create HTML E-Mail intro.
            $name = 'theme_boost_union/emailbrandinghtmlintro';
            $title = '';
            $description = '<div class="alert alert-info" role="alert">'.
                    get_string('emailbrandinghtmlintro', 'theme_boost_union', null, true).'</div>';
            $setting = new admin_setting_description($name, $title, $description);
            $tab->add($setting);

            // Create HTML E-Mail preview.
            $name = 'theme_boost_union/emailbrandinghtmlpreview';
            $title = '';
            $description = $htmlpreview;
            $setting = new admin_setting_description($name, $title, $description);
            $tab->add($setting);

            // Otherwise.
        } else {
            // Create HTML E-Mail intro.
            $name = 'theme_boost_union/emailbrandinghtmlnopreview';
            $title = '';
            $description = '<div class="alert alert-info" role="alert">'.
                    get_string('emailbrandinghtmlnopreview', 'theme_boost_union', null, true).'</div>';
            $setting = new admin_setting_description($name, $title, $description);
            $tab->add($setting);
        }

        // Create Plaintext E-Mails heading.
        $name = 'theme_boost_union/emailbrandingtextheading';
        $title = get_string('emailbrandingtextheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Get Plaintext E-Mail preview.
        $textpreview = theme_boost_union_get_emailbrandingtextpreview();

        // If the Plaintext E-Mails are customized.
        if ($textpreview != null) {
            // Create Plaintext E-Mail intro.
            $name = 'theme_boost_union/emailbrandingtextintro';
            $title = '';
            $description = '<div class="alert alert-info" role="alert">'.
                    get_string('emailbrandingtextintro', 'theme_boost_union', null, true).'</div>';
            $setting = new admin_setting_description($name, $title, $description);
            $tab->add($setting);

            // Create Plaintext E-Mail preview.
            $name = 'theme_boost_union/emailbrandingtextpreview';
            $title = '';
            $description = $textpreview;
            $setting = new admin_setting_description($name, $title, $description);
            $tab->add($setting);

            // Otherwise.
        } else {
            // Create Plaintext E-Mail intro.
            $name = 'theme_boost_union/emailbrandingtextnopreview';
            $title = '';
            $description = '<div class="alert alert-info" role="alert">'.
                    get_string('emailbrandingtextnopreview', 'theme_boost_union', null, true).'</div>';
            $setting = new admin_setting_description($name, $title, $description);
            $tab->add($setting);
        }

        // Add tab to settings page.
        $page->add($tab);


        // Create resources tab.
        $tab = new admin_settingpage('theme_boost_union_look_resources',
                get_string('resourcestab', 'theme_boost_union', null, true));

        // Create additional resources heading.
        $name = 'theme_boost_union/additionalresourcesheading';
        $title = get_string('additionalresourcesheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Additional resources.
        $name = 'theme_boost_union/additionalresources';
        $title = get_string('additionalresourcessetting', 'theme_boost_union', null, true);
        $description = get_string('additionalresourcessetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'additionalresources', 0,
                ['maxfiles' => -1]);
        $tab->add($setting);

        // Information: Additional resources list.
        // If there is at least one file uploaded.
        if (!empty(get_config('theme_boost_union', 'additionalresources'))) {
            // Prepare the widget.
            $name = 'theme_boost_union/additionalresourceslist';
            $title = get_string('additionalresourceslistsetting', 'theme_boost_union', null, true);
            $description = get_string('additionalresourceslistsetting_desc', 'theme_boost_union', null, true).'<br /><br />'.
                    get_string('resourcescachecontrolnote', 'theme_boost_union', null, true);

            // Append the file list to the description.
            $templatecontext = ['files' => theme_boost_union_get_additionalresources_templatecontext()];
            $description .= $OUTPUT->render_from_template('theme_boost_union/settings-additionalresources-filelist',
                    $templatecontext);

            // Finish the widget.
            $setting = new admin_setting_description($name, $title, $description);
            $tab->add($setting);

        }

        // Create custom fonts heading.
        $name = 'theme_boost_union/customfontsheading';
        $title = get_string('customfontsheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Register the webfonts file types for filtering the uploads in the subsequent admin settings.
        // This function call may return false. In this case, the filetypes were not registered and we
        // can't restrict the filetypes in the subsequent admin settings unfortunately.
        $registerfontsresult = theme_boost_union_register_webfonts_filetypes();

        // Setting: Custom fonts.
        $name = 'theme_boost_union/customfonts';
        $title = get_string('customfontssetting', 'theme_boost_union', null, true);
        $description = get_string('customfontssetting_desc', 'theme_boost_union', null, true);
        if ($registerfontsresult == true) {
            $setting = new admin_setting_configstoredfile($name, $title, $description, 'customfonts', 0,
                    ['maxfiles' => -1, 'accepted_types' => theme_boost_union_get_webfonts_extensions()]);
        } else {
            $setting = new admin_setting_configstoredfile($name, $title, $description, 'customfonts', 0,
                    ['maxfiles' => -1]);
        }
        $tab->add($setting);

        // Information: Custom fonts list.
        // If there is at least one file uploaded.
        if (!empty(get_config('theme_boost_union', 'customfonts'))) {
            // Prepare the widget.
            $name = 'theme_boost_union/customfontslist';
            $title = get_string('customfontslistsetting', 'theme_boost_union', null, true);
            $description = get_string('customfontslistsetting_desc', 'theme_boost_union', null, true);

            // Append the file list to the description.
            $templatecontext = ['files' => theme_boost_union_get_customfonts_templatecontext()];
            $description .= $OUTPUT->render_from_template('theme_boost_union/settings-customfonts-filelist', $templatecontext);

            // Finish the widget.
            $setting = new admin_setting_description($name, $title, $description);
            $tab->add($setting);

        }

        // Add tab to settings page.
        $page->add($tab);


        // Create H5P tab.
        $tab = new admin_settingpage('theme_boost_union_look_h5p',
                get_string('h5ptab', 'theme_boost_union', null, true));

        // Create Raw CSS for H5P heading.
        $name = 'theme_boost_union/cssh5pheading';
        $title = get_string('cssh5pheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Raw CSS for H5P.
        $name = 'theme_boost_union/cssh5p';
        $title = get_string('cssh5psetting', 'theme_boost_union', null, true);
        $description = get_string('cssh5psetting_desc', 'theme_boost_union', null, true);
        $default = '';
        $setting = new admin_setting_scsscode($name, $title, $description, $default, PARAM_RAW);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create content bank width heading.
        $name = 'theme_boost_union/contentwidthheading';
        $title = get_string('contentwidthheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: H5P content bank max width.
        $name = 'theme_boost_union/h5pcontentmaxwidth';
        $title = get_string('h5pcontentmaxwidthsetting', 'theme_boost_union', null, true);
        $description = get_string('h5pcontentmaxwidthsetting_desc', 'theme_boost_union', null, true);
        $default = '960px';
        $setting = new admin_setting_configtext($name, $title, $description, $default, $widthregex, 6);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create mobile tab.
        $tab = new admin_settingpage('theme_boost_union_look_mobile',
                get_string('mobiletab', 'theme_boost_union', null, true));

        // Create Mobile app heading.
        $name = 'theme_boost_union/mobileappheading';
        $title = get_string('mobileappheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Additional CSS for Mobile app.
        $name = 'theme_boost_union/mobilescss';
        $title = get_string('mobilecss', 'theme_boost_union', null, true);
        $description = get_string('mobilecss_desc', 'theme_boost_union', null, true);
        $mobilecssurl = new core\url('/admin/settings.php', ['section' => 'mobileappearance']);
        // If another Mobile App CSS URL is set already (in the $CFG->mobilecssurl setting), we add a warning to the description.
        if (isset($CFG->mobilecssurl) && !empty($CFG->mobilecssurl) &&
                strpos($CFG->mobilecssurl, '/boost_union/mobile/styles.php') == false) {
            $mobilescssnotification = new \core\output\notification(
                    get_string('mobilecss_overwrite', 'theme_boost_union',
                            ['url' => $mobilecssurl->out(), 'value' => $CFG->mobilecssurl]).' '.
                    get_string('mobilecss_donotchange', 'theme_boost_union'),
                    \core\output\notification::NOTIFY_WARNING);
            $mobilescssnotification->set_show_closebutton(false);
            $description .= $OUTPUT->render($mobilescssnotification);

            // Otherwise, we just add a note to the description.
        } else {
            $mobilescssnotification = new \core\output\notification(
                    get_string('mobilecss_set', 'theme_boost_union',
                            ['url' => $mobilecssurl->out()]).' '.
                    get_string('mobilecss_donotchange', 'theme_boost_union'),
                    \core\output\notification::NOTIFY_INFO);
            $mobilescssnotification->set_show_closebutton(false);
            $description .= $OUTPUT->render($mobilescssnotification);
        }
        // Using admin_setting_scsscode is not 100% right here as this setting does not support SCSS.
        // However, is shouldn't harm if the CSS code is parsed by the setting.
        $setting = new admin_setting_scsscode($name, $title, $description, '', PARAM_RAW);
        $setting->set_updatedcallback('theme_boost_union_set_mobilecss_url');
        $tab->add($setting);

        // Create Mobile appearance heading.
        $name = 'theme_boost_union/mobileappearanceheading';
        $title = get_string('mobileappearanceheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Touch icon files for iOS.
        $name = 'theme_boost_union/touchiconfilesios';
        $title = get_string('touchiconfilesios', 'theme_boost_union', null, true);
        $touchiconsios = theme_boost_union_get_touchicons_for_ios();
        $description = get_string('touchiconfilesios_desc', 'theme_boost_union', null, true).'<br />';
        $description .= get_string('touchiconfilesios_recommended', 'theme_boost_union', null, true).' ';
        $description .= $touchiconsios['filenameprefix'].
            '['.
            implode(' | ', $touchiconsios['sizes']['recommended']).
            ']-.['.
            implode(' | ', $touchiconsios['filenamesuffixes']).
            ']';
        $description .= '<br />';
        $description .= get_string('touchiconfilesios_optional', 'theme_boost_union', null, true).' ';
        $description .= $touchiconsios['filenameprefix'].
            '['.
            implode(' | ', $touchiconsios['sizes']['optional']).
            ']-.['.
            implode(' | ', $touchiconsios['filenamesuffixes']).
            ']';
        $description .= '<br />';
        $description .= get_string('touchiconfilesios_example', 'theme_boost_union', null, true);
        $description .= '<br />';
        $description .= get_string('touchiconfilesios_note', 'theme_boost_union', null, true);
        // Use our enhanced implementation of admin_setting_configstoredfile to circumvent MDL-59082.
        // This can be changed back to admin_setting_configstoredfile as soon as MDL-59082 is fixed.
        $setting = new admin_setting_configstoredfilealwayscallback($name, $title, $description, 'touchiconsios', 0,
                ['maxfiles' => -1, 'subdirs' => 0, 'accepted_types' => ['.jpg', '.png']]);
        $setting->set_updatedcallback('theme_boost_union_touchicons_for_ios_checkin');
        $tab->add($setting);

        // Information: Touch icon files for iOS list.
        // If there is at least one file uploaded.
        if (!empty(get_config('theme_boost_union', 'touchiconfilesios'))) {
            // Prepare the widget.
            $name = 'theme_boost_union/touchiconfilesioslist';
            $title = get_string('touchiconfilesioslist', 'theme_boost_union', null, true);
            $description = get_string('touchiconfilesioslist_desc', 'theme_boost_union', null, true);

            // Append the icon list to the description.
            $templatecontext = ['files' => theme_boost_union_get_touchicons_for_ios_templatecontext()];
            $description .= $OUTPUT->render_from_template('theme_boost_union/settings-touchiconsios-filelist', $templatecontext);

            // Finish the widget.
            $setting = new admin_setting_description($name, $title, $description);
            $tab->add($setting);
        }

        // Add tab to settings page.
        $page->add($tab);

        // Add settings page to the admin settings category.
        $ADMIN->add('theme_boost_union', $page);


        // Create Feel settings page with tabs
        // (and allow users with the theme/boost_union:configure capability to access it).
        $page = new theme_boost_admin_settingspage_tabs('theme_boost_union_feel',
                get_string('configtitlefeel', 'theme_boost_union', null, true),
                'theme/boost_union:configure');


        // Create navigation tab.
        $tab = new admin_settingpage('theme_boost_union_feel_navigation',
                get_string('navigationtab', 'theme_boost_union', null, true));

        // Create primary navigation heading.
        $name = 'theme_boost_union/primarynavigationheading';
        $title = get_string('primarynavigationheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Prepare hide nodes options.
        $hidenodesoptions = [
                THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_HOME => get_string('home'),
                THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_MYHOME => get_string('myhome'),
                THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_MYCOURSES => get_string('mycourses'),
                THEME_BOOST_UNION_SETTING_HIDENODESPRIMARYNAVIGATION_SITEADMIN => get_string('administrationsite'),
        ];

        // Setting: Hide nodes in primary navigation.
        $name = 'theme_boost_union/hidenodesprimarynavigation';
        $title = get_string('hidenodesprimarynavigationsetting', 'theme_boost_union', null, true);
        $smartmenuurl = new core\url('/theme/boost_union/smartmenus/menus.php');
        $description = get_string('hidenodesprimarynavigationsetting_desc', 'theme_boost_union',
                ['url' => $smartmenuurl], true);
        $setting = new admin_setting_configmulticheckbox($name, $title, $description, [], $hidenodesoptions);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Alternative logo link URL.
        $name = 'theme_boost_union/alternativelogolinkurl';
        $title = get_string('alternativelogolinkurlsetting', 'theme_boost_union', null, true);
        $description = get_string('alternativelogolinkurlsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
        $tab->add($setting);

        // Create user menu heading.
        $name = 'theme_boost_union/usermenuheading';
        $title = get_string('usermenuheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Show full name in the user menu.
        $name = 'theme_boost_union/showfullnameinusermenu';
        $title = get_string('showfullnameinusermenussetting', 'theme_boost_union', null, true);
        $description = get_string('showfullnameinusermenussetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Setting: Add preferred language link to language menu.
        $name = 'theme_boost_union/addpreferredlang';
        $title = get_string('addpreferredlangsetting', 'theme_boost_union', null, true);
        $langmenuurl = new core\url('/admin/search.php', ['query' => 'langmenu']);
        $langtoolurl = new core\url('/admin/tool/langimport/index.php');
        $langlisturl = new core\url('/admin/search.php', ['query' => 'langlist']);
        $description = get_string('addpreferredlangsetting_desc',
                'theme_boost_union',
                ['url1' => $langmenuurl, 'url2' => $langtoolurl, 'url3' => $langlisturl],
                true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Create navbar heading.
        $name = 'theme_boost_union/navbarheading';
        $title = get_string('navbarheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Show starred courses popover in the navbar.
        $name = 'theme_boost_union/shownavbarstarredcourses';
        $title = get_string('shownavbarstarredcoursessetting', 'theme_boost_union', null, true);
        $description = get_string('shownavbarstarredcoursessetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create breadcrumbs heading.
        $name = 'theme_boost_union/breadcrumbsheading';
        $title = get_string('breadcrumbsheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Display the category breadcrumb in the course header.
        $categorybreadcrumbsoptions = [
            // Don't use string lazy loading (= false) because the string will be directly used and would produce a
            // PHP warning otherwise.
            THEME_BOOST_UNION_SETTING_SELECT_YES => get_string('yes'),
            THEME_BOOST_UNION_SETTING_SELECT_NO => get_string('no'),
        ];
        $name = 'theme_boost_union/categorybreadcrumbs';
        $title = get_string('categorybreadcrumbs', 'theme_boost_union', null, true);
        $description = get_string('categorybreadcrumbs_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_SELECT_NO, $categorybreadcrumbsoptions);
        $tab->add($setting);

        // Create navigation heading.
        $name = 'theme_boost_union/navigationheading';
        $title = get_string('navigationheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: back to top button.
        $name = 'theme_boost_union/backtotopbutton';
        $title = get_string('backtotopbuttonsetting', 'theme_boost_union', null, true);
        $description = get_string('backtotopbuttonsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: scroll-spy.
        $name = 'theme_boost_union/scrollspy';
        $title = get_string('scrollspysetting', 'theme_boost_union', null, true);
        $description = get_string('scrollspysetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Activity & section navigation.
        $name = 'theme_boost_union/activitynavigation';
        $title = get_string('activitynavigationsetting', 'theme_boost_union', null, true);
        $description = get_string('activitynavigationsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create blocks tab.
        $tab = new admin_settingpage('theme_boost_union_feel_blocks', get_string('blockstab', 'theme_boost_union', null, true));

        // Create blocks general heading.
        $name = 'theme_boost_union/blocksgeneralheading';
        $title = get_string('blocksgeneralheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Replicate the Unaddable blocks setting from theme_boost.
        $name = 'theme_boost_union/unaddableblocks';
        $title = get_string('unaddableblocks', 'theme_boost', null, true);
        $description = get_string('unaddableblocks_desc', 'theme_boost', null, true);
        $default = 'navigation,settings,course_list,section_links';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $tab->add($setting);

        // Create block regions heading.
        $name = 'theme_boost_union/blockregionsheading';
        $title = get_string('blockregionsheading', 'theme_boost_union', null, true);
        $description = get_string('blockregionsheading_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, $description);
        $tab->add($setting);

        // Add experimental warning.
        $name = 'theme_boost_union/blockregionsheadingexperimental';
        $notification = new \core\output\notification(get_string('blockregionsheading_experimental', 'theme_boost_union'),
                \core\output\notification::NOTIFY_WARNING);
        $notification->set_show_closebutton(false);
        $description = $OUTPUT->render($notification);
        $setting = new admin_setting_heading($name, '', $description);
        $tab->add($setting);

        // Settings: Additional block regions for 'x' layout.
        // List of region strings.
        $regionstr = (array) get_strings([
            'region-outside-top',
            'region-outside-left',
            'region-outside-right',
            'region-outside-bottom',
            'region-content-upper',
            'region-content-lower',
            'region-header',
            'region-footer-left',
            'region-footer-right',
            'region-footer-center',
            'region-offcanvas-left',
            'region-offcanvas-right',
            'region-offcanvas-center',
        ], 'theme_boost_union');
        // List of all available regions.
        $allavailableregions = [
            'outside-top' => $regionstr['region-outside-top'],
            'outside-left' => $regionstr['region-outside-left'],
            'outside-right' => $regionstr['region-outside-right'],
            'outside-bottom' => $regionstr['region-outside-bottom'],
            'footer-left' => $regionstr['region-footer-left'],
            'footer-right' => $regionstr['region-footer-right'],
            'footer-center' => $regionstr['region-footer-center'],
            'offcanvas-left' => $regionstr['region-offcanvas-left'],
            'offcanvas-right' => $regionstr['region-offcanvas-right'],
            'offcanvas-center' => $regionstr['region-offcanvas-center'],
            'content-upper' => $regionstr['region-content-upper'],
            'content-lower' => $regionstr['region-content-lower'],
            'header' => $regionstr['region-header'],
        ];
        // Partial list of regions (used on some layouts).
        $partialregions = [
            'outside-top' => $regionstr['region-outside-top'],
            'outside-bottom' => $regionstr['region-outside-bottom'],
            'footer-left' => $regionstr['region-footer-left'],
            'footer-right' => $regionstr['region-footer-right'],
            'footer-center' => $regionstr['region-footer-center'],
            'offcanvas-left' => $regionstr['region-offcanvas-left'],
            'offcanvas-right' => $regionstr['region-offcanvas-right'],
            'offcanvas-center' => $regionstr['region-offcanvas-center'],
        ];
        // Build list of page layouts and map the regions to each page layout.
        $pagelayouts = [
            'standard' => $partialregions,
            'admin' => $partialregions,
            'coursecategory' => $allavailableregions,
            'incourse' => $allavailableregions,
            'mypublic' => $partialregions,
            'report' => $partialregions,
            'course' => $allavailableregions,
            'frontpage' => $allavailableregions,
        ];
        // For the mydashboard layout, remove the content-* layouts as there are already block regions.
        $pagelayouts['mydashboard'] = array_filter($allavailableregions, function($key) {
            return ($key != 'content-upper' && $key != 'content-lower') ? true : false;
        }, ARRAY_FILTER_USE_KEY);
        // Create admin setting for each page layout.
        foreach ($pagelayouts as $layout => $regions) {
            $name = 'theme_boost_union/blockregionsfor'.$layout;
            $title = get_string('blockregionsforlayout', 'theme_boost_union', $layout, true);
            $description = get_string('blockregionsforlayout_desc', 'theme_boost_union', $layout, true);
            $setting = new admin_setting_configmulticheckbox($name, $title, $description, [], $regions);
            $tab->add($setting);
        }

        // Create outside regions heading.
        $name = 'theme_boost_union/outsideregionsheading';
        $title = get_string('outsideregionsheading', 'theme_boost_union', null, true);
        $description = get_string('outsideregionsheading_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, $description);
        $tab->add($setting);

        // Setting: Block region width for Outside (left) region.
        $name = 'theme_boost_union/blockregionoutsideleftwidth';
        $title = get_string('blockregionoutsideleftwidth', 'theme_boost_union', null, true);
        $description = get_string('blockregionoutsideleftwidth_desc', 'theme_boost_union', null, true);
        $default = '300px';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Block region width for Outside (right) region.
        $name = 'theme_boost_union/blockregionoutsiderightwidth';
        $title = get_string('blockregionoutsiderightwidth', 'theme_boost_union', null, true);
        $description = get_string('blockregionoutsiderightwidth_desc', 'theme_boost_union', null, true);
        $default = '300px';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Block region width for Outside (top) region.
        $outsideregionswidthoptions = [
            // Don't use string lazy loading (= false) because the string will be directly used and would produce a
            // PHP warning otherwise.
                THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSWITH_FULLWIDTH =>
                        get_string('outsideregionswidthfullwidth', 'theme_boost_union', null, false),
                THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSWITH_COURSECONTENTWIDTH =>
                        get_string('outsideregionswidthcoursecontentwidth', 'theme_boost_union', null, false),
                THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSWITH_HEROWIDTH =>
                        get_string('outsideregionswidthherowidth', 'theme_boost_union', null, false), ];
        $name = 'theme_boost_union/blockregionoutsidetopwidth';
        $title = get_string('blockregionoutsidetopwidth', 'theme_boost_union', null, true);
        $description = get_string('blockregionoutsidetopwidth_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSWITH_FULLWIDTH, $outsideregionswidthoptions);
        $tab->add($setting);

        // Setting: Block region width for Outside (bottom) region.
        $name = 'theme_boost_union/blockregionoutsidebottomwidth';
        $title = get_string('blockregionoutsidebottomwidth', 'theme_boost_union', null, true);
        $description = get_string('blockregionoutsidebottomwidth_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSWITH_FULLWIDTH, $outsideregionswidthoptions);
        $tab->add($setting);

        // Setting: Block region width for Footer region.
        $name = 'theme_boost_union/blockregionfooterwidth';
        $title = get_string('blockregionfooterwidth', 'theme_boost_union', null, true);
        $description = get_string('blockregionfooterwidth_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSWITH_FULLWIDTH, $outsideregionswidthoptions);
        $tab->add($setting);

        // Setting: Outside regions horizontal placement.
        $outsideregionsplacementoptions = [
            // Don't use string lazy loading (= false) because the string will be directly used and would produce a
            // PHP warning otherwise.
                THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSPLACEMENT_NEXTMAINCONTENT =>
                        get_string('outsideregionsplacementnextmaincontent', 'theme_boost_union', null, false),
                THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSPLACEMENT_NEARWINDOW =>
                        get_string('outsideregionsplacementnearwindowedges', 'theme_boost_union', null, false), ];
        $name = 'theme_boost_union/outsideregionsplacement';
        $title = get_string('outsideregionsplacement', 'theme_boost_union', null, true);
        $description = get_string('outsideregionsplacement_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_OUTSIDEREGIONSPLACEMENT_NEXTMAINCONTENT, $outsideregionsplacementoptions);
        $tab->add($setting);

        // Create site home right-hand blocks drawer behaviour heading.
        $name = 'theme_boost_union/sitehomerighthandblockdrawerbehaviour';
        $title = get_string('sitehomerighthandblockdrawerbehaviour', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Show right-hand block drawer of site home on visit.
        $name = 'theme_boost_union/showsitehomerighthandblockdraweronvisit';
        $title = get_string('showsitehomerighthandblockdraweronvisitsetting', 'theme_boost_union', null, true);
        $description = get_string('showsitehomerighthandblockdraweronvisitsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Setting: Show right-hand block drawer of site home on first login.
        $name = 'theme_boost_union/showsitehomerighthandblockdraweronfirstlogin';
        $title = get_string('showsitehomerighthandblockdraweronfirstloginsetting', 'theme_boost_union', null, true);
        $description = get_string('showsitehomerighthandblockdraweronfirstloginsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Setting: Show right-hand block drawer of site home on guest login.
        $name = 'theme_boost_union/showsitehomerighthandblockdraweronguestlogin';
        $title = get_string('showsitehomerighthandblockdraweronguestloginsetting', 'theme_boost_union', null, true);
        $description = get_string('showsitehomerighthandblockdraweronguestloginsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create page layouts tab.
        $tab = new admin_settingpage('theme_boost_union_feel_pagelayouts',
                get_string('pagelayoutstab', 'theme_boost_union', null, true));

        // Create tool_policy heading.
        $name = 'theme_boost_union/policyheading';
        $title = get_string('policyheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Navigation on policy overview page.
        $name = 'theme_boost_union/policyoverviewnavigation';
        $title = get_string('policyoverviewnavigationsetting', 'theme_boost_union', null, true);
        $policyoverviewurl = new core\url('/admin/tool/policy/viewall.php');
        $description = get_string('policyoverviewnavigationsetting_desc', 'theme_boost_union', ['url' => $policyoverviewurl], true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create links tab.
        $tab = new admin_settingpage('theme_boost_union_feel_links', get_string('linkstab', 'theme_boost_union', null, true));

        // Create Special Links Markup heading.
        $name = 'theme_boost_union/speciallinksmarkupheading';
        $title = get_string('speciallinksmarkupheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Mark external links.
        $name = 'theme_boost_union/markexternallinks';
        $title = get_string('markexternallinkssetting', 'theme_boost_union', null, true);
        $description = get_string('markexternallinkssetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Mark external links scope.
        $markexternallinksscopeoptions = [
                // Don't use string lazy loading (= false) because the string will be directly used and would produce a
                // PHP warning otherwise.
                THEME_BOOST_UNION_SETTING_MARKLINKS_WHOLEPAGE =>
                        get_string('marklinksscopesetting_wholepage', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_MARKLINKS_COURSEMAIN =>
                        get_string('marklinksscopesetting_coursemain', 'theme_boost_union'),
        ];
        $name = 'theme_boost_union/markexternallinksscope';
        $title = get_string('markexternallinksscopesetting', 'theme_boost_union', null, true);
        $description = get_string('markexternallinksscopesetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_MARKLINKS_WHOLEPAGE, $markexternallinksscopeoptions);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/markexternallinksscope', 'theme_boost_union/markexternallinks', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Mark mailto links.
        $name = 'theme_boost_union/markmailtolinks';
        $title = get_string('markmailtolinkssetting', 'theme_boost_union', null, true);
        $description = get_string('markmailtolinkssetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Mark mailto links scope.
        $markmailtolinksscopeoptions = [
                // Don't use string lazy loading (= false) because the string will be directly used and would produce a
                // PHP warning otherwise.
                THEME_BOOST_UNION_SETTING_MARKLINKS_WHOLEPAGE =>
                        get_string('marklinksscopesetting_wholepage', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_MARKLINKS_COURSEMAIN =>
                        get_string('marklinksscopesetting_coursemain', 'theme_boost_union'),
        ];
        $name = 'theme_boost_union/markmailtolinksscope';
        $title = get_string('markmailtolinksscopesetting', 'theme_boost_union', null, true);
        $description = get_string('markmailtolinksscopesetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_MARKLINKS_WHOLEPAGE, $markmailtolinksscopeoptions);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/markmailtolinksscope', 'theme_boost_union/markmailtolinks', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Mark broken links.
        $name = 'theme_boost_union/markbrokenlinks';
        $title = get_string('markbrokenlinkssetting', 'theme_boost_union', null, true);
        $description = get_string('markbrokenlinkssetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create misc tab.
        $tab = new admin_settingpage('theme_boost_union_feel_misc', get_string('misctab', 'theme_boost_union', null, true));

        // Create JavaScript heading.
        $name = 'theme_boost_union/javascriptheading';
        $title = get_string('javascriptheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: JavaScript disabled hint.
        $name = 'theme_boost_union/javascriptdisabledhint';
        $title = get_string('javascriptdisabledhint', 'theme_boost_union', null, true);
        $description = get_string('javascriptdisabledhint_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);

        // Add settings page to the admin settings category.
        $ADMIN->add('theme_boost_union', $page);


        // Create Content settings page with tabs
        // (and allow users with the theme/boost_union:configure capability to access it).
        $page = new theme_boost_admin_settingspage_tabs('theme_boost_union_content',
                get_string('configtitlecontent', 'theme_boost_union', null, true),
                'theme/boost_union:configure');

        // Create footer tab.
        $tab = new admin_settingpage('theme_boost_union_content_footer', get_string('footertab', 'theme_boost_union', null, true));

        // Create footnote heading.
        $name = 'theme_boost_union/footnoteheading';
        $title = get_string('footnoteheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Footnote.
        $name = 'theme_boost_union/footnote';
        $title = get_string('footnotesetting', 'theme_boost_union', null, true);
        $description = get_string('footnotesetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_confightmleditor($name, $title, $description, '');
        $tab->add($setting);

        // Create footer heading.
        $name = 'theme_boost_union/footerheading';
        $title = get_string('footerheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Enable footer.
        $enablefooterbuttonoptions = [
            THEME_BOOST_UNION_SETTING_ENABLEFOOTER_ALL =>
                    get_string('enablefooterbuttonboth', 'theme_boost_union', null, true),
            THEME_BOOST_UNION_SETTING_ENABLEFOOTER_DESKTOP =>
                    get_string('enablefooterbuttondesktop', 'theme_boost_union', null, true),
            THEME_BOOST_UNION_SETTING_ENABLEFOOTER_MOBILE =>
                    get_string('enablefooterbuttonmobile', 'theme_boost_union', null, true),
            THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE =>
                    get_string('enablefooterbuttonhidden', 'theme_boost_union', null, true),
        ];
        $name = 'theme_boost_union/enablefooterbutton';
        $title = get_string('enablefooterbutton', 'theme_boost_union', null, true);
        $description = get_string('enablefooterbutton_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_ENABLEFOOTER_DESKTOP, $enablefooterbuttonoptions);
        $tab->add($setting);

        // Setting: Suppress icons in front of the footer links.
        $name = 'theme_boost_union/footersuppressicons';
        $title = get_string('footersuppressiconssetting', 'theme_boost_union', null, true);
        $description = get_string('footersuppressiconssetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Setting: Suppress 'Chat to course participants' link.
        $name = 'theme_boost_union/footersuppresschat';
        $title = get_string('footersuppresschatsetting', 'theme_boost_union', null, true);
        $description = get_string('footersuppresschatsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/footersuppresschat', 'theme_boost_union/enablefooterbutton', 'eq',
                THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE);

        // Setting: Suppress 'Documentation for this page' link.
        $name = 'theme_boost_union/footersuppresshelp';
        $title = get_string('footersuppresshelpsetting', 'theme_boost_union', null, true);
        $url = new core\url('/admin/settings.php', ['section' => 'documentation']);
        $description = get_string('footersuppresshelpsetting_desc', 'theme_boost_union', ['url' => $url], true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/footersuppresshelp', 'theme_boost_union/enablefooterbutton', 'eq',
                THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE);

        // Setting: Suppress 'Services and support' link.
        $name = 'theme_boost_union/footersuppressservices';
        $title = get_string('footersuppressservicessetting', 'theme_boost_union', null, true);
        $url = new core\url('/admin/settings.php', ['section' => 'supportcontact']);
        $description = get_string('footersuppressservicessetting_desc', 'theme_boost_union', ['url' => $url], true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/footersuppressservices', 'theme_boost_union/enablefooterbutton', 'eq',
                THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE);

        // Setting: Suppress 'Contact site support' link.
        $name = 'theme_boost_union/footersuppresscontact';
        $title = get_string('footersuppresscontactsetting', 'theme_boost_union', null, true);
        $url = new core\url('/admin/settings.php', ['section' => 'supportcontact']);
        $description = get_string('footersuppresscontactsetting_desc', 'theme_boost_union', ['url' => $url], true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/footersuppresscontact', 'theme_boost_union/enablefooterbutton', 'eq',
                THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE);

        // Setting: Suppress Login info.
        $name = 'theme_boost_union/footersuppresslogininfo';
        $title = get_string('footersuppresslogininfosetting', 'theme_boost_union', null, true);
        $description = get_string('footersuppresslogininfosetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/footersuppresslogininfo', 'theme_boost_union/enablefooterbutton', 'eq',
                THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE);

        // Setting: Suppress 'Reset user tour on this page' link.
        $name = 'theme_boost_union/footersuppressusertour';
        $title = get_string('footersuppressusertoursetting', 'theme_boost_union', null, true);
        $description = get_string('footersuppressusertoursetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/footersuppressusertour', 'theme_boost_union/enablefooterbutton', 'eq',
                THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE);

        // Setting: Suppress theme switcher links.
        $name = 'theme_boost_union/footersuppressthemeswitch';
        $title = get_string('footersuppressthemeswitchsetting', 'theme_boost_union', null, true);
        $description = get_string('footersuppressthemeswitchsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/footersuppressthemeswitch', 'theme_boost_union/enablefooterbutton', 'eq',
                THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE);

        // Setting: Suppress 'Powered by Moodle' link.
        $name = 'theme_boost_union/footersuppresspowered';
        $title = get_string('footersuppresspoweredsetting', 'theme_boost_union', null, true);
        $description = get_string('footersuppresspoweredsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/footersuppresspowered', 'theme_boost_union/enablefooterbutton', 'eq',
                THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE);

        // Settings: Suppress footer output by plugins (for updated plugins with the hook).
        // Get the array of plugins with the before_standard_footer_html_generation hook which can be suppressed by Boost Union.
        $pluginswithcallback =
                di::get(hook_manager::class)->get_callbacks_for_hook('core\\hook\\output\\before_standard_footer_html_generation');
        // Iterate over all plugins.
        foreach ($pluginswithcallback as $callback) {
            // Extract the pluginname.
            $pluginname = theme_boost_union_get_pluginname_from_callbackname($callback);
            // Compose the label.
            if ($callback['component'] == 'core') {
                $hooklabeltitle = get_string('footersuppressstandardfootercore', 'theme_boost_union', $pluginname, true);
                $hooklabeldesc = get_string('footersuppressstandardfootercore_desc', 'theme_boost_union', $pluginname, true);
            } else {
                $hooklabeltitle = get_string('footersuppressstandardfooter', 'theme_boost_union', $pluginname, true);
                $hooklabeldesc = get_string('footersuppressstandardfooter_desc', 'theme_boost_union', $pluginname, true);
            }
            // Get the plugin name from the language pack.
            // Create the setting.
            $name = 'theme_boost_union/footersuppressstandardfooter_'.$pluginname;
            $setting = new admin_setting_configselect($name, $hooklabeltitle, $hooklabeldesc,
                    THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
            $setting->set_updatedcallback('theme_boost_union_remove_hookmanipulation');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/footersuppressstandardfooter_'.$pluginname,
                    'theme_boost_union/enablefooterbutton', 'eq', THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE);
        }

        // Settings: Suppress footer output by plugins (for legacy plugins).
        // Get the array of plugins with the standard_footer_html() function which can be suppressed by Boost Union.
        $pluginswithfunction = get_plugins_with_function('standard_footer_html', 'lib.php');
        // Iterate over all plugins.
        foreach ($pluginswithfunction as $plugintype => $plugins) {
            foreach ($plugins as $pluginname => $function) {
                // Create the setting.
                $name = 'theme_boost_union/footersuppressstandardfooter_'.$plugintype.'_'.$pluginname;
                $title = get_string('footersuppressstandardfooter',
                        'theme_boost_union',
                        get_string('pluginname', $plugintype.'_'.$pluginname, null, true),
                        true);
                $description = get_string('footersuppressstandardfooter_desc',
                        'theme_boost_union',
                        get_string('pluginname', $plugintype.'_'.$pluginname, null, true),
                        true);
                $setting = new admin_setting_configselect($name, $title, $description,
                        THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
                $tab->add($setting);
                $page->hide_if('theme_boost_union/footersuppressstandardfooter_'.$plugintype.'_'.$pluginname,
                       'theme_boost_union/enablefooterbutton', 'eq', THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE);
            }
        }

        // Add tab to settings page.
        $page->add($tab);


        // Create static pages tab.
        $tab = new admin_settingpage('theme_boost_union_content_staticpages',
                get_string('staticpagestab', 'theme_boost_union', null, true));

        // The static pages to be supported.
        $staticpages = ['aboutus', 'offers', 'imprint', 'contact', 'help', 'maintenance', 'page1', 'page2', 'page3'];

        // Iterate over the pages.
        foreach ($staticpages as $staticpage) {

            // Create page heading.
            $name = 'theme_boost_union/'.$staticpage.'heading';
            $title = get_string($staticpage.'heading', 'theme_boost_union', null, true);
            $setting = new admin_setting_heading($name, $title, null);
            $tab->add($setting);

            // Setting: Enable page.
            $name = 'theme_boost_union/enable'.$staticpage;
            $title = get_string('enable'.$staticpage.'setting', 'theme_boost_union', null, true);
            $description = '';
            $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                    $yesnooption);
            $tab->add($setting);

            // Setting: Page content.
            $name = 'theme_boost_union/'.$staticpage.'content';
            $title = get_string($staticpage.'contentsetting', 'theme_boost_union', null, true);
            $description = get_string($staticpage.'contentsetting_desc', 'theme_boost_union', null, true);
            $setting = new admin_setting_confightmleditor($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/'.$staticpage.'content', 'theme_boost_union/enable'.$staticpage, 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Page title.
            $name = 'theme_boost_union/'.$staticpage.'pagetitle';
            $title = get_string($staticpage.'pagetitlesetting', 'theme_boost_union', null, true);
            $description = get_string($staticpage.'pagetitlesetting_desc', 'theme_boost_union', null, true);
            $default = get_string($staticpage.'pagetitledefault', 'theme_boost_union', null, true);
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/'.$staticpage.'pagetitle', 'theme_boost_union/enable'.$staticpage, 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Page link position.
            $name = 'theme_boost_union/'.$staticpage.'linkposition';
            $title = get_string($staticpage.'linkpositionsetting', 'theme_boost_union', null, true);
            $staticpageurl = theme_boost_union_get_staticpage_link($staticpage);
            $description = get_string($staticpage.'linkpositionsetting_desc', 'theme_boost_union', ['url' => $staticpageurl],
                    true);
            $linkpositionoption =
                    // Don't use string lazy loading (= false) because the string will be directly used and would produce a
                    // PHP warning otherwise.
                    [THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_NONE =>
                            get_string($staticpage.'linkpositionnone', 'theme_boost_union', null, false),
                            THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTNOTE =>
                                    get_string($staticpage.'linkpositionfootnote', 'theme_boost_union', null, false),
                            THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTER =>
                                    get_string($staticpage.'linkpositionfooter', 'theme_boost_union', null, false),
                            THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_BOTH =>
                                    get_string($staticpage.'linkpositionboth', 'theme_boost_union', null, false), ];
            $default = 'none';
            $setting = new admin_setting_configselect($name, $title, $description, $default, $linkpositionoption);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/'.$staticpage.'linkposition', 'theme_boost_union/enable'.$staticpage, 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);
        }

        // Add tab to settings page.
        $page->add($tab);


        // Create info banner tab.
        $tab = new admin_settingpage('theme_boost_union_infobanners_infobanner',
                get_string('infobannertab', 'theme_boost_union', null, true));

        // Prepare options for the pages settings.
        $infobannerpages = [
            // Don't use string lazy loading (= false) because the string will be directly used and would produce a
            // PHP warning otherwise.
                THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_MY => get_string('myhome', 'core', null, false),
                THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_MYCOURSES => get_string('mycourses', 'core', null, false),
                THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_SITEHOME => get_string('sitehome', 'core', null, false),
                THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_COURSE => get_string('course', 'core', null, false),
                THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_LOGIN =>
                        get_string('infobannerpageloginpage', 'theme_boost_union', null, false),
        ];

        // Prepare options for the bootstrap class settings.
        $infobannerbsclasses = [
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
                'none' => get_string('bootstrapnone', 'theme_boost_union', null, false),
        ];

        // Prepare options for the order settings.
        $infobannerorders = [];
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT; $i++) {
            $infobannerorders[$i] = $i;
        }

        // Prepare options for the mode settings.
        $infobannermodes = [
            // Don't use string lazy loading (= false) because the string will be directly used and would produce a
            // PHP warning otherwise.
                THEME_BOOST_UNION_SETTING_INFOBANNERMODE_PERPETUAL =>
                        get_string('infobannermodeperpetual', 'theme_boost_union', null, false),
                THEME_BOOST_UNION_SETTING_INFOBANNERMODE_TIMEBASED =>
                        get_string('infobannermodetimebased', 'theme_boost_union', null, false),
        ];

        // Create the hardcoded amount of information banners without code duplication.
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT; $i++) {

            // Create Infobanner heading.
            $name = 'theme_boost_union/infobanner'.$i.'heading';
            $title = get_string('infobannerheading', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_heading($name, $title, null);
            $tab->add($setting);

            // Setting: Infobanner enabled.
            $name = 'theme_boost_union/infobanner'.$i.'enabled';
            $title = get_string('infobannerenabledsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('infobannerenabledsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                    $yesnooption);
            $tab->add($setting);

            // Setting: Infobanner content.
            $name = 'theme_boost_union/infobanner'.$i.'content';
            $title = get_string('infobannercontentsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('infobannercontentsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_confightmleditor($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'content', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Infobanner pages.
            $name = 'theme_boost_union/infobanner'.$i.'pages';
            $title = get_string('infobannerpagessetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('infobannerpagessetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configmultiselect($name, $title, $description,
                    [$infobannerpages[THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_MY]], $infobannerpages);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'pages', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Infobanner bootstrap class.
            $name = 'theme_boost_union/infobanner'.$i.'bsclass';
            $title = get_string('infobannerbsclasssetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('infobannerbsclasssetting_desc',
                    'theme_boost_union',
                    ['no' => $i, 'bootstrapnone' => get_string('bootstrapnone', 'theme_boost_union')],
                    true);
            $setting = new admin_setting_configselect($name, $title, $description,
                    'primary', $infobannerbsclasses);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'bsclass', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Infobanner order.
            $name = 'theme_boost_union/infobanner'.$i.'order';
            $title = get_string('infobannerordersetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('infobannerordersetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configselect($name, $title, $description,
                    $i, $infobannerorders);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'order', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Infobanner mode.
            $name = 'theme_boost_union/infobanner'.$i.'mode';
            $title = get_string('infobannermodesetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('infobannermodesetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configselect($name, $title, $description,
                    THEME_BOOST_UNION_SETTING_INFOBANNERMODE_PERPETUAL, $infobannermodes);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'mode', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Infobanner start time.
            $name = 'theme_boost_union/infobanner'.$i.'start';
            $title = get_string('infobannerstartsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('infobannerstartsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configdatetime($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'start', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);
            $page->hide_if('theme_boost_union/infobanner'.$i.'start', 'theme_boost_union/infobanner'.$i.'mode', 'neq',
                    THEME_BOOST_UNION_SETTING_INFOBANNERMODE_TIMEBASED);

            // Setting: Infobanner end time.
            $name = 'theme_boost_union/infobanner'.$i.'end';
            $title = get_string('infobannerendsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('infobannerendsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configdatetime($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'end', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);
            $page->hide_if('theme_boost_union/infobanner'.$i.'end', 'theme_boost_union/infobanner'.$i.'mode', 'neq',
                    THEME_BOOST_UNION_SETTING_INFOBANNERMODE_TIMEBASED);

            // Setting: Infobanner dismissible.
            $name = 'theme_boost_union/infobanner'.$i.'dismissible';
            $title = get_string('infobannerdismissiblesetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('infobannerdismissiblesetting_desc', 'theme_boost_union', ['no' => $i], true);
            // Add Reset button if the info banner is already configured to be dismissible.
            if (get_config('theme_boost_union', 'infobanner'.$i.'dismissible') == true) {
                $reseturl = new core\url('/theme/boost_union/settings_infobanner_resetdismissed.php',
                        ['sesskey' => sesskey(), 'no' => $i]);
                $description .= \core\output\html_writer::empty_tag('br');
                $description .= \core\output\html_writer::link($reseturl,
                        get_string('infobannerdismissresetbutton', 'theme_boost_union', ['no' => $i], true),
                        ['class' => 'btn btn-secondary mt-3', 'role' => 'button']);
            }
            $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                    $yesnooption);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'dismissible', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);
            $page->hide_if('theme_boost_union/infobanner'.$i.'dismissible', 'theme_boost_union/infobanner'.$i.'mode', 'neq',
                    THEME_BOOST_UNION_SETTING_INFOBANNERMODE_PERPETUAL);
        }

        // Add tab to settings page.
        $page->add($tab);


        // Create advertisement tiles tab.
        $tab = new admin_settingpage('theme_boost_union_tiles',
            get_string('tilestab', 'theme_boost_union', null, true));

        // Create advertisement tiles general heading.
        $name = 'theme_boost_union/tilesgeneralheading';
        $title = get_string('tilesgeneralheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Position of the advertisement tiles on the frontpage.
        $tilefrontpagepositionoptions = [
                THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_FRONTPAGEPOSITION_BEFORE =>
                        get_string('tilefrontpagepositionsetting_before', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_FRONTPAGEPOSITION_AFTER =>
                        get_string('tilefrontpagepositionsetting_after', 'theme_boost_union'), ];
        $name = 'theme_boost_union/tilefrontpageposition';
        $title = get_string('tilefrontpagepositionsetting', 'theme_boost_union', null, true);
        $url = new core\url('/admin/settings.php', ['section' => 'frontpagesettings']);
        $description = get_string('tilefrontpagepositionsetting_desc', 'theme_boost_union', ['url' => $url], true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_FRONTPAGEPOSITION_BEFORE, $tilefrontpagepositionoptions);
        $tab->add($setting);

        // Setting: Number of advertisement tile columns per row.
        $tilecolumnsoptions = [];
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_COLUMN_COUNT; $i++) {
            $tilecolumnsoptions[$i] = $i;
        }
        $name = 'theme_boost_union/tilecolumns';
        $title = get_string('tilecolumnssetting', 'theme_boost_union', null, true);
        $description = get_string('tilecolumnssetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, 2, $tilecolumnsoptions);
        $tab->add($setting);

        // Setting: Advertisement tiles height.
        $name = 'theme_boost_union/tileheight';
        $title = get_string('tileheightsetting', 'theme_boost_union', null, true);
        $description = get_string('tileheightsetting_desc', 'theme_boost_union', null, true);
        $tileheightoptions = [
                THEME_BOOST_UNION_SETTING_HEIGHT_100PX => THEME_BOOST_UNION_SETTING_HEIGHT_100PX,
                THEME_BOOST_UNION_SETTING_HEIGHT_150PX => THEME_BOOST_UNION_SETTING_HEIGHT_150PX,
                THEME_BOOST_UNION_SETTING_HEIGHT_200PX => THEME_BOOST_UNION_SETTING_HEIGHT_200PX,
                THEME_BOOST_UNION_SETTING_HEIGHT_250PX => THEME_BOOST_UNION_SETTING_HEIGHT_250PX, ];
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_HEIGHT_150PX, $tileheightoptions);
        $tab->add($setting);

        // Prepare options for the order settings.
        $tilesorders = [];
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_COUNT; $i++) {
            $tilesorders[$i] = $i;
        }

        // Create the hardcoded amount of advertisement tiles without code duplication.
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_ADVERTISEMENTTILES_COUNT; $i++) {

            // Create advertisement tile heading.
            $name = 'theme_boost_union/tile'.$i.'heading';
            $title = get_string('tileheading', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_heading($name, $title, null);
            $tab->add($setting);

            // Setting: Advertisement tile enabled.
            $name = 'theme_boost_union/tile'.$i.'enabled';
            $title = get_string('tileenabledsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('tileenabledsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                    $yesnooption);
            $tab->add($setting);

            // Setting: Advertisement tile title.
            $name = 'theme_boost_union/tile'.$i.'title';
            $title = get_string('tiletitlesetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('tiletitlesetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configtext($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/tile'.$i.'title', 'theme_boost_union/tile'.$i.'enabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Advertisement tile content.
            $name = 'theme_boost_union/tile'.$i.'content';
            $title = get_string('tilecontentsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('tilecontentsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_confightmleditor($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/tile'.$i.'content', 'theme_boost_union/tile'.$i.'enabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Advertisement tile background image.
            $name = 'theme_boost_union/tile'.$i.'backgroundimage';
            $title = get_string('tilebackgroundimagesetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('tilebackgroundimagesetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configstoredfile($name, $title, $description, 'tilebackgroundimage'.$i, 0,
                ['maxfiles' => 1, 'accepted_types' => 'web_image']);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/tile'.$i.'backgroundimage', 'theme_boost_union/tile'.$i.'enabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Advertisement tile background image position.
            $name = 'theme_boost_union/tile'.$i.'backgroundimageposition';
            $title = get_string('tilebackgroundimagepositionsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('tilebackgroundimagepositionsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $tilebackgroundimagepositionoptions = [
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER =>
                            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER,
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP =>
                            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP,
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM =>
                            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM,
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP =>
                            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP,
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER =>
                            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER,
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM =>
                            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM,
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP =>
                            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP,
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER =>
                            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER,
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM =>
                            THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM, ];
            $setting = new admin_setting_configselect($name, $title, $description,
                    THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER, $tilebackgroundimagepositionoptions);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/tile'.$i.'backgroundimageposition', 'theme_boost_union/tile'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Advertisement tile content style.
            $name = 'theme_boost_union/tile'.$i.'contentstyle';
            $title = get_string('tilecontentstylesetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('tilecontentstylesetting_desc', 'theme_boost_union', ['no' => $i], true);
            $tilecontentstyleoptions = [
                    THEME_BOOST_UNION_SETTING_CONTENTSTYLE_NOCHANGE =>
                            get_string('tilecontentstylesetting_nochange', 'theme_boost_union'),
                    THEME_BOOST_UNION_SETTING_CONTENTSTYLE_LIGHT =>
                            get_string('tilecontentstylesetting_light', 'theme_boost_union'),
                    THEME_BOOST_UNION_SETTING_CONTENTSTYLE_LIGHTSHADOW =>
                            get_string('tilecontentstylesetting_lightshadow', 'theme_boost_union'),
                    THEME_BOOST_UNION_SETTING_CONTENTSTYLE_DARK =>
                            get_string('tilecontentstylesetting_dark', 'theme_boost_union'),
                    THEME_BOOST_UNION_SETTING_CONTENTSTYLE_DARKSHADOW =>
                            get_string('tilecontentstylesetting_darkshadow', 'theme_boost_union'),
            ];
            $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_CONTENTSTYLE_NOCHANGE, $tilecontentstyleoptions);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/tile'.$i.'contentstyle', 'theme_boost_union/tile'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Advertisement tile link URL.
            $name = 'theme_boost_union/tile'.$i.'link';
            $title = get_string('tilelinksetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('tilelinksetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/tile'.$i.'link', 'theme_boost_union/tile'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Advertisement tile link title.
            $name = 'theme_boost_union/tile'.$i.'linktitle';
            $title = get_string('tilelinktitlesetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('tilelinktitlesetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configtext($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/tile'.$i.'linktitle', 'theme_boost_union/tile'.$i.'enabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Advertisement tile link target.
            $name = 'theme_boost_union/tile'.$i.'linktarget';
            $title = get_string('tilelinktargetsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('tilelinktargetsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $tilelinktargetnoptions = [
                    THEME_BOOST_UNION_SETTING_LINKTARGET_SAMEWINDOW =>
                            get_string('tilelinktargetsetting_samewindow', 'theme_boost_union'),
                    THEME_BOOST_UNION_SETTING_LINKTARGET_NEWTAB =>
                            get_string('tilelinktargetsetting_newtab', 'theme_boost_union'), ];
            $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_LINKTARGET_SAMEWINDOW,
                    $tilelinktargetnoptions);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/tile'.$i.'linktarget', 'theme_boost_union/tile'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Advertisement tile order position.
            $name = 'theme_boost_union/tile'.$i.'order';
            $title = get_string('tileordersetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('tileordersetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configselect($name, $title, $description, $i, $tilesorders);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/tile'.$i.'order', 'theme_boost_union/tile'.$i.'enabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);
        }

        // Add tab to settings page.
        $page->add($tab);


        // Create slider tab.
        $tab = new admin_settingpage('theme_boost_union_slider',
                get_string('slidertab', 'theme_boost_union', null, true));

        // Create slider general heading.
        $name = 'theme_boost_union/slidergeneralheading';
        $title = get_string('slidergeneralheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Position of the slider on the frontpage.
        $sliderfrontpagepositionoptions = [
                THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_BEFOREBEFORE =>
                        get_string('sliderfrontpagepositionsetting_beforebefore', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_BEFOREAFTER =>
                        get_string('sliderfrontpagepositionsetting_beforeafter', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_AFTERBEFORE =>
                        get_string('sliderfrontpagepositionsetting_afterbefore', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_AFTERAFTER =>
                        get_string('sliderfrontpagepositionsetting_afterafter', 'theme_boost_union'),
        ];
        $name = 'theme_boost_union/sliderfrontpageposition';
        $title = get_string('sliderfrontpagepositionsetting', 'theme_boost_union', null, true);
        $url = new core\url('/admin/settings.php', ['section' => 'frontpagesettings']);
        $description = get_string('sliderfrontpagepositionsetting_desc', 'theme_boost_union', ['url' => $url], true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_SLIDER_FRONTPAGEPOSITION_BEFOREBEFORE, $sliderfrontpagepositionoptions);
        $tab->add($setting);

        // Setting: Enable arrow navigation.
        $name = 'theme_boost_union/sliderarrownav';
        $title = get_string('sliderarrownavsetting', 'theme_boost_union', null, true);
        $description = get_string('sliderarrownavsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                $yesnooption);
        $tab->add($setting);

        // Setting: Enable slider indicator navigation.
        $name = 'theme_boost_union/sliderindicatornav';
        $title = get_string('sliderindicatornavsetting', 'theme_boost_union', null, true);
        $description = get_string('sliderindicatornavsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                $yesnooption);
        $tab->add($setting);

        // Setting: Slider animation type.
        $slideranimationoptions = [
                THEME_BOOST_UNION_SETTING_SLIDER_ANIMATIONTYPE_NONE =>
                        get_string('slideranimationsetting_none', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_SLIDER_ANIMATIONTYPE_FADE =>
                        get_string('slideranimationsetting_fade', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_SLIDER_ANIMATIONTYPE_SLIDE =>
                        get_string('slideranimationsetting_slide', 'theme_boost_union'),
        ];
        $name = 'theme_boost_union/slideranimation';
        $title = get_string('slideranimationsetting', 'theme_boost_union', null, true);
        $description = get_string('slideranimationsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_SLIDER_ANIMATIONTYPE_SLIDE, $slideranimationoptions);
        $tab->add($setting);

        // Setting: Slider interval speed.
        $name = 'theme_boost_union/sliderinterval';
        $title = get_string('sliderintervalsetting', 'theme_boost_union', null, true);
        $description = get_string('sliderintervalsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configtext($name, $title, $description, 5000, PARAM_INT, 6);
        $tab->add($setting);

        // Setting: Allow slider keyboard interaction.
        $name = 'theme_boost_union/sliderkeyboard';
        $title = get_string('sliderkeyboardsetting', 'theme_boost_union', null, true);
        $description = get_string('sliderkeyboardsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_YES,
                $yesnooption);
        $tab->add($setting);

        // Setting: Pause slider on mouseover.
        $name = 'theme_boost_union/sliderpause';
        $title = get_string('sliderpausesetting', 'theme_boost_union', null, true);
        $description = get_string('sliderpausesetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_YES,
                $yesnooption);
        $tab->add($setting);

        // Setting: Cycle through slides.
        $sliderrideoptions = [
                THEME_BOOST_UNION_SETTING_SLIDER_RIDE_ONPAGELOAD =>
                        get_string('sliderridesetting_onpageload', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_SLIDER_RIDE_AFTERINTERACTION =>
                        get_string('sliderridesetting_afterinteraction', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_SLIDER_RIDE_NEVER =>
                        get_string('sliderridesetting_never', 'theme_boost_union'),
        ];
        $name = 'theme_boost_union/sliderride';
        $title = get_string('sliderridesetting', 'theme_boost_union', null, true);
        $description = get_string('sliderridesetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_SLIDER_RIDE_ONPAGELOAD, $sliderrideoptions);
        $tab->add($setting);

        // Setting: Continuously cycle through slides.
        $name = 'theme_boost_union/sliderwrap';
        $title = get_string('sliderwrapsetting', 'theme_boost_union', null, true);
        $description = get_string('sliderwrapsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_YES,
                $yesnooption);
        $tab->add($setting);

        // Prepare options for the order settings.
        $slidesorders = [];
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_SLIDES_COUNT; $i++) {
            $slidesorders[$i] = $i;
        }

        // Create a hardcoded amount of slides without code duplication.
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_SLIDES_COUNT; $i++) {

            // Create slide heading.
            $name = 'theme_boost_union/slide'.$i.'heading';
            $title = get_string('slideheading', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_heading($name, $title, null);
            $tab->add($setting);

            // Setting: Slide enabled.
            $name = 'theme_boost_union/slide'.$i.'enabled';
            $title = get_string('slideenabledsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('slideenabledsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                    $yesnooption);
            $tab->add($setting);

            // Setting: Slide background image.
            $name = 'theme_boost_union/slide'.$i.'backgroundimage';
            $title = get_string('slidebackgroundimagesetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('slidebackgroundimagesetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configstoredfile($name, $title, $description, 'slidebackgroundimage'.$i, 0,
                ['maxfiles' => 1, 'accepted_types' => 'web_image']);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/slide'.$i.'backgroundimage', 'theme_boost_union/slide'.$i.'enabled',
                'neq', THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Slide background image alt attribute.
            $name = 'theme_boost_union/slide'.$i.'backgroundimagealt';
            $title = get_string('slidebackgroundimagealtsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('slidebackgroundimagealtsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configtext($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/slide'.$i.'backgroundimagealt', 'theme_boost_union/slide'.$i.'enabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Slide caption.
            $name = 'theme_boost_union/slide'.$i.'caption';
            $title = get_string('slidecaptionsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('slidecaptionsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configtext($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/slide'.$i.'caption', 'theme_boost_union/slide'.$i.'enabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Slide content.
            $name = 'theme_boost_union/slide'.$i.'content';
            $title = get_string('slidecontentsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('slidecontentsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_confightmleditor($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/slide'.$i.'content', 'theme_boost_union/slide'.$i.'enabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Slide content style.
            $name = 'theme_boost_union/slide'.$i.'contentstyle';
            $title = get_string('slidecontentstylesetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('slidecontentstylesetting_desc', 'theme_boost_union', ['no' => $i], true);
            $slidecontentstyleoptions = [
                    THEME_BOOST_UNION_SETTING_CONTENTSTYLE_LIGHT =>
                            get_string('slidecontentstylesetting_light', 'theme_boost_union'),
                    THEME_BOOST_UNION_SETTING_CONTENTSTYLE_LIGHTSHADOW =>
                            get_string('slidecontentstylesetting_lightshadow', 'theme_boost_union'),
                    THEME_BOOST_UNION_SETTING_CONTENTSTYLE_DARK =>
                            get_string('slidecontentstylesetting_dark', 'theme_boost_union'),
                    THEME_BOOST_UNION_SETTING_CONTENTSTYLE_DARKSHADOW =>
                            get_string('slidecontentstylesetting_darkshadow', 'theme_boost_union'),
            ];
            $setting = new admin_setting_configselect($name, $title, $description,
                THEME_BOOST_UNION_SETTING_CONTENTSTYLE_LIGHT, $slidecontentstyleoptions);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/slide'.$i.'contentstyle', 'theme_boost_union/slide'.$i.'enabled', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Slide link URL.
            $name = 'theme_boost_union/slide'.$i.'link';
            $title = get_string('slidelinksetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('slidelinksetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/slide'.$i.'link', 'theme_boost_union/slide'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Slide link title.
            $name = 'theme_boost_union/slide'.$i.'linktitle';
            $title = get_string('slidelinktitlesetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('slidelinktitlesetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configtext($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/slide'.$i.'linktitle', 'theme_boost_union/slide'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Slide link source.
            $name = 'theme_boost_union/slide'.$i.'linksource';
            $title = get_string('slidelinksourcesetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('slidelinksourcesetting_desc', 'theme_boost_union', ['no' => $i], true);
            $slidelinksourceoptions = [
                    THEME_BOOST_UNION_SETTING_SLIDER_LINKSOURCE_BOTH =>
                            get_string('slidelinksourcesetting_both', 'theme_boost_union'),
                    THEME_BOOST_UNION_SETTING_SLIDER_LINKSOURCE_IMAGE =>
                            get_string('slidelinksourcesetting_image', 'theme_boost_union'),
                    THEME_BOOST_UNION_SETTING_SLIDER_LINKSOURCE_TEXT =>
                            get_string('slidelinksourcesetting_text', 'theme_boost_union'),
            ];
            $setting = new admin_setting_configselect($name, $title, $description,
                    THEME_BOOST_UNION_SETTING_SLIDER_LINKSOURCE_BOTH, $slidelinksourceoptions);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/slide'.$i.'linksource', 'theme_boost_union/slide'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Slide link target.
            $name = 'theme_boost_union/slide'.$i.'linktarget';
            $title = get_string('slidelinktargetsetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('slidelinktargetsetting_desc', 'theme_boost_union', ['no' => $i], true);
            $slidelinktargetnoptions = [
                    THEME_BOOST_UNION_SETTING_LINKTARGET_SAMEWINDOW =>
                            get_string('slidelinktargetsetting_samewindow', 'theme_boost_union'),
                    THEME_BOOST_UNION_SETTING_LINKTARGET_NEWTAB =>
                            get_string('slidelinktargetsetting_newtab', 'theme_boost_union'), ];
            $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_LINKTARGET_SAMEWINDOW,
                    $slidelinktargetnoptions);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/slide'.$i.'linktarget', 'theme_boost_union/slide'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Slide order position.
            $name = 'theme_boost_union/slide'.$i.'order';
            $title = get_string('slideordersetting', 'theme_boost_union', ['no' => $i], true);
            $description = get_string('slideordersetting_desc', 'theme_boost_union', ['no' => $i], true);
            $setting = new admin_setting_configselect($name, $title, $description, $i, $slidesorders);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/slide'.$i.'order', 'theme_boost_union/slide'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);
        }

        // Add tab to settings page.
        $page->add($tab);

        // Add settings page to the admin settings category.
        $ADMIN->add('theme_boost_union', $page);


        // Create Functionality settings page with tabs
        // (and allow users with the theme/boost_union:configure capability to access it).
        $page = new theme_boost_admin_settingspage_tabs('theme_boost_union_functionality',
                get_string('configtitlefunctionality', 'theme_boost_union', null, true),
                'theme/boost_union:configure');

        // Create courses tab.
        $tab = new admin_settingpage('theme_boost_union_functionality_courses',
                get_string('coursestab', 'theme_boost_union', null, true));

        // Create course related hints heading.
        $name = 'theme_boost_union/courserelatedhintsheading';
        $title = get_string('courserelatedhintsheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Show hint for switched role.
        $name = 'theme_boost_union/showswitchedroleincourse';
        $title = get_string('showswitchedroleincoursesetting', 'theme_boost_union', null, true);
        $description = get_string('showswitchedroleincoursesetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Show hint in hidden courses.
        $name = 'theme_boost_union/showhintcoursehidden';
        $title = get_string('showhintcoursehiddensetting', 'theme_boost_union', null, true);
        $description = get_string('showhintcoursehiddensetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Setting: Show hint for forum notifications in hidden courses.
        $name = 'theme_boost_union/showhintforumnotifications';
        $title = get_string('showhintforumnotificationssetting', 'theme_boost_union', null, true);
        $description = get_string('showhintforumnotificationssetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/showhintforumnotifications', 'theme_boost_union/showhintcoursehidden', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Show hint guest for access.
        $name = 'theme_boost_union/showhintcourseguestaccess';
        $title = get_string('showhintcoursguestaccesssetting', 'theme_boost_union', null, true);
        $description = get_string('showhintcourseguestaccesssetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Setting: Show hint for self enrolment without enrolment key.
        $name = 'theme_boost_union/showhintcourseselfenrol';
        $title = get_string('showhintcourseselfenrolsetting', 'theme_boost_union', null, true);
        $description = get_string('showhintcourseselfenrolsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create administration tab.
        $tab = new admin_settingpage('theme_boost_union_functionality_administration',
            get_string('administrationtab', 'theme_boost_union', null, true));

        // Create course management heading.
        $name = 'theme_boost_union/coursemanagementheading';
        $title = get_string('coursemanagementheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Prepare course management page URL.
        $coursemgnturl = new core\url('/course/management.php');

        // Setting: Show view course icon in course management.
        $name = 'theme_boost_union/showviewcourseiconincoursemgnt';
        $title = get_string('showviewcourseiconincoursemgntsetting', 'theme_boost_union', null, true);
        $description = get_string('showviewcourseiconincoursemgntsesetting_desc', 'theme_boost_union',
                $coursemgnturl->out(), true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);

        // Add settings page to the admin settings category.
        $ADMIN->add('theme_boost_union', $page);


        // Create Accessibility settings page with tabs
        // (and allow users with the theme/boost_union:configure capability to access it).
        $page = new theme_boost_admin_settingspage_tabs('theme_boost_union_accessibility',
                get_string('configtitleaccessibility', 'theme_boost_union', null, true),
                'theme/boost_union:configure');

        // Create Declaration tab.
        $tab = new admin_settingpage('theme_boost_union_content_accessibilitydeclaration',
                get_string('accessibilitydeclarationtab', 'theme_boost_union', null, true));

        // Create Declaration of accessibility page heading.
        $name = 'theme_boost_union/accessibilityheading';
        $title = get_string('accessibilitydeclarationheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Enable Declaration of accessibility page.
        $name = 'theme_boost_union/enableaccessibilitydeclaration';
        $title = get_string('enableaccessibilitydeclarationsetting', 'theme_boost_union', null, true);
        $staticpagesurl = new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_content'],
                'theme_boost_union_content_staticpages');
        $description = get_string('enableaccessibilitydeclarationsetting_desc', 'theme_boost_union', ['url' => $staticpagesurl],
                true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                $yesnooption);
        $tab->add($setting);

        // Setting: Declaration of accessibility page content.
        $name = 'theme_boost_union/accessibilitydeclarationcontent';
        $title = get_string('accessibilitydeclarationcontentsetting', 'theme_boost_union', null, true);
        $description = get_string('accessibilitydeclarationcontentsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_confightmleditor($name, $title, $description, '');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/accessibilitydeclarationcontent', 'theme_boost_union/enableaccessibilitydeclaration',
                'neq', THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Declaration of accessibility page title.
        $name = 'theme_boost_union/accessibilitydeclarationpagetitle';
        $title = get_string('accessibilitydeclarationpagetitlesetting', 'theme_boost_union', null, true);
        $description = get_string('accessibilitydeclarationpagetitlesetting_desc', 'theme_boost_union', null, true);
        $default = get_string('accessibilitydeclarationpagetitledefault', 'theme_boost_union', null, true);
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/accessibilitydeclarationpagetitle', 'theme_boost_union/enableaccessibilitydeclaration',
                'neq', THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Declaration of accessibility page link position.
        $name = 'theme_boost_union/accessibilitydeclarationlinkposition';
        $title = get_string('accessibilitydeclarationlinkpositionsetting', 'theme_boost_union', null, true);
        $pageurl = theme_boost_union_get_staticpage_link('accessibility');
        $description = get_string('accessibilitydeclarationlinkpositionsetting_desc', 'theme_boost_union', ['url' => $pageurl],
                true);
        $linkpositionoption =
                // Don't use string lazy loading (= false) because the string will be directly used and would produce a
                // PHP warning otherwise.
                [THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_NONE =>
                        get_string('accessibilitydeclarationlinkpositionnone', 'theme_boost_union', null, false),
                        THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTNOTE =>
                                get_string('accessibilitydeclarationlinkpositionfootnote', 'theme_boost_union', null, false),
                        THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTER =>
                                get_string('accessibilitydeclarationlinkpositionfooter', 'theme_boost_union', null, false),
                        THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_BOTH =>
                                get_string('accessibilitydeclarationlinkpositionboth', 'theme_boost_union', null, false), ];
        $default = 'none';
        $setting = new admin_setting_configselect($name, $title, $description, $default, $linkpositionoption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/accessibilitydeclarationlinkposition', 'theme_boost_union/enableaccessibilitydeclaration',
                'neq', THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Add tab to settings page.
        $page->add($tab);

        // Create Support tab.
        $tab = new admin_settingpage('theme_boost_union_content_accessibilitysupport',
                get_string('accessibilitysupporttab', 'theme_boost_union', null, true));

        // Create Accessibility support page heading.
        $name = 'theme_boost_union/accessibilitysupportheading';
        $title = get_string('accessibilitysupportheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Enable accessibility support page.
        $name = 'theme_boost_union/enableaccessibilitysupport';
        $title = get_string('enableaccessibilitysupportsetting', 'theme_boost_union', null, true);
        $sitesupporturl = new \core\url('/user/contactsitesupport.php');
        $description = get_string('enableaccessibilitysupportsetting_desc', 'theme_boost_union', ['url' => $sitesupporturl],
                true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                $yesnooption);
        $tab->add($setting);

        // Setting: Accessibility support page content.
        $name = 'theme_boost_union/accessibilitysupportcontent';
        $title = get_string('accessibilitysupportcontentsetting', 'theme_boost_union', null, true);
        $description = get_string('accessibilitysupportcontentsetting_desc', 'theme_boost_union', null, true);
        $default = get_string('accessibilitysupportcontentdefault', 'theme_boost_union', null, true);
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/accessibilitysupportcontent', 'theme_boost_union/enableaccessibilitysupport', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Accessibility support page title.
        $name = 'theme_boost_union/accessibilitysupportpagetitle';
        $title = get_string('accessibilitysupportpagetitlesetting', 'theme_boost_union', null, true);
        $description = get_string('accessibilitysupportpagetitlesetting_desc', 'theme_boost_union', null, true);
        $default = get_string('accessibilitysupportpagetitledefault', 'theme_boost_union', null, true);
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/accessibilitysupportpagetitle', 'theme_boost_union/enableaccessibilitysupport', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Accessibility support page link position.
        $name = 'theme_boost_union/accessibilitysupportlinkposition';
        $title = get_string('accessibilitysupportlinkpositionsetting', 'theme_boost_union', null, true);
        $pageurl = theme_boost_union_get_accessibility_link('support');
        $description = get_string('accessibilitysupportlinkpositionsetting_desc', 'theme_boost_union', ['url' => $pageurl],
                true);
        $linkpositionoption =
                // Don't use string lazy loading (= false) because the string will be directly used and would produce a
                // PHP warning otherwise.
                [THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_NONE =>
                        get_string('accessibilitysupportlinkpositionnone', 'theme_boost_union', null, false),
                        THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTNOTE =>
                                get_string('accessibilitysupportlinkpositionfootnote', 'theme_boost_union', null, false),
                        THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_FOOTER =>
                                get_string('accessibilitysupportlinkpositionfooter', 'theme_boost_union', null, false),
                        THEME_BOOST_UNION_SETTING_STATICPAGELINKPOSITION_BOTH =>
                                get_string('accessibilitysupportlinkpositionboth', 'theme_boost_union', null, false), ];
        $default = 'none';
        $setting = new admin_setting_configselect($name, $title, $description, $default, $linkpositionoption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/accessibilitysupportlinkposition', 'theme_boost_union/enableaccessibilitysupport', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Allow accessibility support page without login.
        $name = 'theme_boost_union/allowaccessibilitysupportwithoutlogin';
        $title = get_string('allowaccessibilitysupportwithoutlogin', 'theme_boost_union', null, true);
        $description = get_string('allowaccessibilitysupportwithoutlogin_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/allowaccessibilitysupportwithoutlogin', 'theme_boost_union/enableaccessibilitysupport',
                'neq', THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Enable accessibility button.
        $name = 'theme_boost_union/enableaccessibilitysupportfooterbutton';
        $title = get_string('enableaccessibilitysupportfooterbuttonsetting', 'theme_boost_union', null, true);
        $description = get_string('enableaccessibilitysupportfooterbuttonsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/enableaccessibilitysupportfooterbutton', 'theme_boost_union/enableaccessibilitysupport',
                'neq', THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Allow anonymous support page submissions.
        $name = 'theme_boost_union/allowanonymoussubmits';
        $title = get_string('allowanonymoussubmitssetting', 'theme_boost_union', null, true);
        $description = get_string('allowanonymoussubmitssetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/allowanonymoussubmits', 'theme_boost_union/enableaccessibilitysupport', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Allow sending technical information along.
        $name = 'theme_boost_union/allowsendtechinfoalong';
        $title = get_string('allowsendtechinfoalongsetting', 'theme_boost_union', null, true);
        $description = get_string('allowsendtechinfoalongsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_YES,
                $yesnooption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/allowsendtechinfoalong', 'theme_boost_union/enableaccessibilitysupport', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Accessibility support user email.
        $name = 'theme_boost_union/accessibilitysupportusermail';
        $title = get_string('accessibilitysupportusermail', 'theme_boost_union', null, true);
        $sitesupportsettingsurl = new \core\url('/admin/settings.php', ['section' => 'supportcontact']);
        $description = get_string('accessibilitysupportusermail_desc', 'theme_boost_union', ['url' => $sitesupportsettingsurl],
                true);
        $setting = new admin_setting_configtext($name, $title, $description, '', PARAM_EMAIL);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/accessibilitysupportusermail', 'theme_boost_union/enableaccessibilitysupport', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Accessibility support page screenreader title.
        $name = 'theme_boost_union/accessibilitysupportpagesrlinktitle';
        $title = get_string('accessibilitysupportpagesrlinktitlesetting', 'theme_boost_union', null, true);
        $description = get_string('accessibilitysupportpagesrlinktitlesetting_desc', 'theme_boost_union', null, true);
        $default = get_string('accessibilitysupportpagesrlinktitledefault', 'theme_boost_union', null, true);
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/accessibilitysupportpagesrlinktitle', 'theme_boost_union/enableaccessibilitysupport',
                'neq', THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Add re-captcha to accessibility support page.
        $name = 'theme_boost_union/accessibilitysupportrecaptcha';
        $title = get_string('accessibilitysupportrecaptcha', 'theme_boost_union', null, true);
        $authsettingsurl = new \core\url('/admin/settings.php', ['section' => 'manageauths']);
        $supportformsurl = new \core\url('/user/contactsitesupport.php');
        $description = get_string('accessibilitysupportrecaptcha_desc', 'theme_boost_union',
                ['settings' => $authsettingsurl, 'support' => $supportformsurl], true);
        $accessibilitysupportrecaptchaoptions = [
                THEME_BOOST_UNION_SETTING_SELECT_NEVER =>
                        get_string('never', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_SELECT_ALWAYS =>
                        get_string('always', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_SELECT_ONLYGUESTSANDNONLOGGEDIN =>
                        get_string('forguestsonly', 'theme_boost_union'),
        ];
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NEVER,
                $accessibilitysupportrecaptchaoptions);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/accessibilitysupportrecaptcha', 'theme_boost_union/enableaccessibilitysupport',
                'neq', THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Add tab to settings page.
        $page->add($tab);

        // Add settings page to the admin settings category.
        $ADMIN->add('theme_boost_union', $page);

    }

    // Add JS to remember the active admin tab to the page.
    $PAGE->requires->js_call_amd('theme_boost_union/admintabs', 'init');
}
