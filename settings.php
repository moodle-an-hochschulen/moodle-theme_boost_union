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

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig || has_capability('theme/boost_union:configure', context_system::instance())) {

    // How this file works:
    // This theme's settings are divided into multiple settings pages.
    // This is quite unusual as Boost themes would have a nice tabbed settings interface.
    // However, as we are using many hide_if constraints for our settings, we would run into the
    // stupid "Too much data passed as arguments to js_call_amd..." debugging message if we would
    // pack all settings onto just one settings page.
    // To achieve this goal, we create a custom admin settings category and fill it with several settings pages.
    // However, there is still the $settings variable which is expected by Moodle coreto be filled with the theme
    // settings and which is automatically added to the admin settings tree in one settings page.
    // To avoid that there appears an empty "Boost Union" settings page near our own custom settings category,
    // we set $settings to null.

    // Avoid that the theme settings page is auto-created.
    $settings = null;

    // Create custom admin settings category.
    $ADMIN->add('themes', new admin_category('theme_boost_union',
            get_string('pluginname', 'theme_boost_union', null, true)));

    // Create empty settings page structure to make the site administration work on non-admin pages.
    if (!$ADMIN->fulltree) {
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

        // Create Flavours settings page as external page
        // (and allow users with the theme/boost_union:configure capability to access it).
        $flavourspage = new admin_externalpage('theme_boost_union_flavours',
                get_string('configtitleflavours', 'theme_boost_union', null, true),
                new moodle_url('/theme/boost_union/flavours/overview.php'),
                'theme/boost_union:configure');
        $ADMIN->add('theme_boost_union', $flavourspage);

        // Create Smart Menus settings page as external page.
        // (and allow users with the theme/boost_union:configure capability to access it).
        $smartmenuspage = new admin_externalpage('theme_boost_union_smartmenus',
                get_string('smartmenus', 'theme_boost_union', null, true),
                new moodle_url('/theme/boost_union/smartmenus/menus.php'),
                'theme/boost_union:configure');
        $ADMIN->add('theme_boost_union', $smartmenuspage);
    }

    // Create full settings page structure.
    // @codingStandardsIgnoreLine
    else if ($ADMIN->fulltree) {

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
        $title = get_string('presetheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

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
        $tab->add($setting);

        // Replicate the preset files setting from theme_boost.
        $name = 'theme_boost_union/presetfiles';
        $title = get_string('presetfiles', 'theme_boost', null, true);
        $description = get_string('presetfiles_desc', 'theme_boost', null, true);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
                ['maxfiles' => 20, 'accepted_types' => ['.scss']]);
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

        // Add tab to settings page.
        $page->add($tab);


        // Create site branding tab.
        $tab = new admin_settingpage('theme_boost_union_look_sitebranding',
                get_string('sitebrandingtab', 'theme_boost_union', null, true));

        // Create logos heading.
        $name = 'theme_boost_union/logosheading';
        $title = get_string('logosheading', 'theme_boost_union', null, true);
        $notificationurl = new moodle_url('/admin/settings.php', ['section' => 'logos']);
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
        $notificationurl = new moodle_url('/admin/settings.php', ['section' => 'logos']);
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

        // Setting: Activity icon color for 'administration'.
        $name = 'theme_boost_union/activityiconcoloradministration';
        $title = get_string('activityiconcoloradministrationsetting', 'theme_boost_union', null, true);
        $description = get_string('activityiconcoloradministrationsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Activity icon color for 'assessment'.
        $name = 'theme_boost_union/activityiconcolorassessment';
        $title = get_string('activityiconcolorassessmentsetting', 'theme_boost_union', null, true);
        $description = get_string('activityiconcolorassessmentsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Activity icon color for 'collaboration'.
        $name = 'theme_boost_union/activityiconcolorcollaboration';
        $title = get_string('activityiconcolorcollaborationsetting', 'theme_boost_union', null, true);
        $description = get_string('activityiconcolorcollaborationsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Activity icon color for 'communication'.
        $name = 'theme_boost_union/activityiconcolorcommunication';
        $title = get_string('activityiconcolorcommunicationsetting', 'theme_boost_union', null, true);
        $description = get_string('activityiconcolorcommunicationsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Activity icon color for 'content'.
        $name = 'theme_boost_union/activityiconcolorcontent';
        $title = get_string('activityiconcolorcontentsetting', 'theme_boost_union', null, true);
        $description = get_string('activityiconcolorcontentsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Activity icon color for 'interface'.
        $name = 'theme_boost_union/activityiconcolorinterface';
        $title = get_string('activityiconcolorinterfacesetting', 'theme_boost_union', null, true);
        $description = get_string('activityiconcolorinterfacesetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
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
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Local login.
        $name = 'theme_boost_union/loginlocalloginenable';
        $title = get_string('loginlocalloginenablesetting', 'theme_boost_union', null, true);
        $description = get_string('loginlocalloginenablesetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_YES, $yesnooption);
        $tab->add($setting);

        // Setting: IDP login intro.
        $name = 'theme_boost_union/loginidpshowintro';
        $title = get_string('loginidpshowintrosetting', 'theme_boost_union', null, true);
        $description = get_string('loginidpshowintrosetting_desc', 'theme_boost_union', get_string('potentialidps', 'auth'), true);
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_YES, $yesnooption);
        $tab->add($setting);

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
        $emailbrandinginstructionli1url = new moodle_url('/admin/tool/customlang/index.php', ['lng' => $CFG->lang]);
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


        // Create mobile app tab.
        $tab = new admin_settingpage('theme_boost_union_look_mobile',
                get_string('mobiletab', 'theme_boost_union', null, true));

        // Create Mobile appearance heading.
        $name = 'theme_boost_union/mobileappearanceheading';
        $title = get_string('mobileappearanceheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Additional CSS for Mobile app.
        $name = 'theme_boost_union/mobilescss';
        $title = get_string('mobilecss', 'theme_boost_union', null, true);
        $description = get_string('mobilecss_desc', 'theme_boost_union', null, true);
        $mobilecssurl = new moodle_url('/admin/settings.php', ['section' => 'mobileappearance']);
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
        $smartmenuurl = new moodle_url('/theme/boost_union/smartmenus/menus.php');
        $description = get_string('hidenodesprimarynavigationsetting_desc', 'theme_boost_union',
                ['url' => $smartmenuurl], true);
        $setting = new admin_setting_configmulticheckbox($name, $title, $description, [], $hidenodesoptions);
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

        // Setting: Activity navigation.
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
            'coursecategory' => $partialregions,
            'incourse' => $partialregions,
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
                $reseturl = new moodle_url('/theme/boost_union/settings_infobanner_resetdismissed.php',
                        ['sesskey' => sesskey(), 'no' => $i]);
                $description .= html_writer::empty_tag('br');
                $description .= html_writer::link($reseturl,
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
        $url = new moodle_url('/admin/settings.php', ['section' => 'frontpagesettings']);
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

            // Setting: Course header image position.
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
        $coursemgnturl = new moodle_url('/course/management.php');

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


        // Create Flavours settings page as external page
        // (and allow users with the theme/boost_union:configure capability to access it).
        $flavourspage = new admin_externalpage('theme_boost_union_flavours',
                get_string('configtitleflavours', 'theme_boost_union', null, true),
                new moodle_url('/theme/boost_union/flavours/overview.php'),
                'theme/boost_union:configure');
        $ADMIN->add('theme_boost_union', $flavourspage);
    }
}
