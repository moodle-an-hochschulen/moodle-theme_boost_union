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

use \theme_boost_union\admin_setting_configdatetime;

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

        // Create Block Regions settings page
        // (and allow users with the theme/boost_union:configure capability to access it).
        $tab = new admin_settingpage('theme_boost_union_blockregion',
                get_string('configtitleblockregion', 'theme_boost_union', null, true),
                'theme/boost_union:configure');
        $ADMIN->add('theme_boost_union', $tab);
    }

    // Create full settings page structure.
    // @codingStandardsIgnoreLine
    else if ($ADMIN->fulltree) {

        // Require the necessary libraries.
        require_once($CFG->dirroot . '/theme/boost_union/lib.php');
        require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

        // Prepare options array for select settings.
        // Due to MDL-58376, we will use binary select settings instead of checkbox settings throughout this theme.
        $yesnooption = array(THEME_BOOST_UNION_SETTING_SELECT_YES => get_string('yes'),
                THEME_BOOST_UNION_SETTING_SELECT_NO => get_string('no'));


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
                array('maxfiles' => 20, 'accepted_types' => array('.scss')));
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create advanced settings tab.
        $tab = new admin_settingpage('theme_boost_union_look_advanced', get_string('advancedsettings', 'theme_boost', null, true));

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

        // Create layout heading.
        $name = 'theme_boost_union/layoutheading';
        $title = get_string('layoutheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Course content max width.
        $name = 'theme_boost_union/coursecontentmaxwidth';
        $title = get_string('coursecontentmaxwidthsetting', 'theme_boost_union', null, true);
        $description = get_string('coursecontentmaxwidthsetting_desc', 'theme_boost_union', null, true);
        $default = '830px';
        // Regular expression for checking if the value is a percent number (from 0% to 100%) or a pixel number (with 3 or 4 digits)
        // or a viewport width number (from 0 to 100).
        $regex = '/^((\d{1,2}|100)%)|((\d{1,2}|100)vw)|(\d{3,4}px)$/';
        $setting = new admin_setting_configtext($name, $title, $description, $default, $regex, 6);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);


        // Create branding tab.
        $tab = new admin_settingpage('theme_boost_union_look_branding', get_string('brandingtab', 'theme_boost_union', null, true));

        // Create favicon heading.
        $name = 'theme_boost_union/faviconheading';
        $title = get_string('faviconheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Favicon.
        $name = 'theme_boost_union/favicon';
        $title = get_string('faviconsetting', 'theme_boost_union', null, true);
        $description = get_string('faviconsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon', 0,
                array('maxfiles' => 1, 'accepted_types' => array('.ico', '.png')));
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create background images heading.
        $name = 'theme_boost_union/backgroundimagesheading';
        $title = get_string('backgroundimagesheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Replicate the Background image setting from theme_boost.
        $name = 'theme_boost_union/backgroundimage';
        $title = get_string('backgroundimage', 'theme_boost', null, true);
        $description = get_string('backgroundimage_desc', 'theme_boost', null, true);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'backgroundimage');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Replicate the Login Background image setting from theme_boost.
        $name = 'theme_boost_union/loginbackgroundimage';
        $title = get_string('loginbackgroundimage', 'theme_boost', null, true);
        $description = get_string('loginbackgroundimage_desc', 'theme_boost', null, true);
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginbackgroundimage');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create brand colors heading.
        $name = 'theme_boost_union/brandcolorsheading';
        $title = get_string('brandcolorsheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Replicate the Variable $body-color setting from theme_boost.
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

        // Add tab to settings page.
        $page->add($tab);


        // Create static pages tab.
        $tab = new admin_settingpage('theme_boost_union_content_staticpages',
                get_string('staticpagestab', 'theme_boost_union', null, true));

        // Create imprint heading.
        $name = 'theme_boost_union/imprintheading';
        $title = get_string('imprintheading', 'theme_boost_union', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Enable imprint.
        $name = 'theme_boost_union/enableimprint';
        $title = get_string('enableimprintsetting', 'theme_boost_union', null, true);
        $description = '';
        $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
        $tab->add($setting);

        // Setting: Imprint content.
        $name = 'theme_boost_union/imprintcontent';
        $title = get_string('imprintcontentsetting', 'theme_boost_union', null, true);
        $description = get_string('imprintcontentsetting_desc', 'theme_boost_union', null, true);
        $setting = new admin_setting_confightmleditor($name, $title, $description, '');
        $tab->add($setting);
        $page->hide_if('theme_boost_union/imprintcontent', 'theme_boost_union/enableimprint', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Imprint page title.
        $name = 'theme_boost_union/imprintpagetitle';
        $title = get_string('imprintpagetitlesetting', 'theme_boost_union', null, true);
        $description = get_string('imprintpagetitlesetting_desc', 'theme_boost_union', null, true);
        $default = get_string('imprintpagetitledefault', 'theme_boost_union', null, true);
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/imprintpagetitle', 'theme_boost_union/enableimprint', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Setting: Imprint link position.
        $name = 'theme_boost_union/imprintlinkposition';
        $title = get_string('imprintlinkpositionsetting', 'theme_boost_union', null, true);
        $imprinturl = theme_boost_union_get_imprint_link();
        $description = get_string('imprintlinkpositionsetting_desc', 'theme_boost_union', array('url' => $imprinturl), true);
        $imprintlinkpositionoption =
                // Don't use string lazy loading (= false) because the string will be directly used and would produce a
                // PHP warning otherwise.
                array(THEME_BOOST_UNION_SETTING_IMPRINTLINKPOSITION_NONE =>
                        get_string('imprintlinkpositionnone', 'theme_boost_union', null, false),
                        THEME_BOOST_UNION_SETTING_IMPRINTLINKPOSITION_FOOTNOTE =>
                                get_string('imprintlinkpositionfootnote', 'theme_boost_union', null, false),
                        THEME_BOOST_UNION_SETTING_IMPRINTLINKPOSITION_FOOTER =>
                                get_string('imprintlinkpositionfooter', 'theme_boost_union', null, false),
                        THEME_BOOST_UNION_SETTING_IMPRINTLINKPOSITION_BOTH =>
                                get_string('imprintlinkpositionboth', 'theme_boost_union', null, false));
        $default = 'none';
        $setting = new admin_setting_configselect($name, $title, $description, $default, $imprintlinkpositionoption);
        $tab->add($setting);
        $page->hide_if('theme_boost_union/imprintlinkposition', 'theme_boost_union/enableimprint', 'neq',
                THEME_BOOST_UNION_SETTING_SELECT_YES);

        // Add tab to settings page.
        $page->add($tab);


        // Create info banner tab.
        $tab = new admin_settingpage('theme_boost_union_infobanners_infobanner',
                get_string('infobannertab', 'theme_boost_union', null, true));

        // Prepare options for the pages settings.
        $infobannerpages = array(
            // Don't use string lazy loading (= false) because the string will be directly used and would produce a
            // PHP warning otherwise.
                THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_MY => get_string('myhome', 'core', null, false),
                THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_MYCOURSES => get_string('mycourses', 'core', null, false),
                THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_SITEHOME => get_string('sitehome', 'core', null, false),
                THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_COURSE => get_string('course', 'core', null, false),
                THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_LOGIN =>
                        get_string('infobannerpageloginpage', 'theme_boost_union', null, false)
        );

        // Prepare options for the bootstrap class settings.
        $infobannerbsclasses = array(
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
        );

        // Prepare options for the order settings.
        $infobannerorders = array();
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT; $i++) {
            $infobannerorders[$i] = $i;
        }

        // Prepare options for the mode settings.
        $infobannermodes = array(
            // Don't use string lazy loading (= false) because the string will be directly used and would produce a
            // PHP warning otherwise.
                THEME_BOOST_UNION_SETTING_INFOBANNERMODE_PERPETUAL =>
                        get_string('infobannermodeperpetual', 'theme_boost_union', null, false),
                THEME_BOOST_UNION_SETTING_INFOBANNERMODE_TIMEBASED =>
                        get_string('infobannermodetimebased', 'theme_boost_union', null, false)
        );

        // Create the hardcoded amount of information banners without code duplication.
        for ($i = 1; $i <= THEME_BOOST_UNION_SETTING_INFOBANNER_COUNT; $i++) {

            // Create Infobanner heading.
            $name = 'theme_boost_union/infobanner'.$i.'heading';
            $title = get_string('infobannerheading', 'theme_boost_union', array('no' => $i), true);
            $setting = new admin_setting_heading($name, $title, null);
            $tab->add($setting);

            // Setting: Infobanner enabled.
            $name = 'theme_boost_union/infobanner'.$i.'enabled';
            $title = get_string('infobannerenabledsetting', 'theme_boost_union', array('no' => $i), true);
            $description = get_string('infobannerenabledsetting_desc', 'theme_boost_union', array('no' => $i), true);
            $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO,
                    $yesnooption);
            $tab->add($setting);

            // Setting: Infobanner content.
            $name = 'theme_boost_union/infobanner'.$i.'content';
            $title = get_string('infobannercontentsetting', 'theme_boost_union', array('no' => $i), true);
            $description = get_string('infobannercontentsetting_desc', 'theme_boost_union', array('no' => $i), true);
            $setting = new admin_setting_confightmleditor($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'content', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Infobanner pages.
            $name = 'theme_boost_union/infobanner'.$i.'pages';
            $title = get_string('infobannerpagessetting', 'theme_boost_union', array('no' => $i), true);
            $description = get_string('infobannerpagessetting_desc', 'theme_boost_union', array('no' => $i), true);
            $setting = new admin_setting_configmultiselect($name, $title, $description,
                    array($infobannerpages[THEME_BOOST_UNION_SETTING_INFOBANNERPAGES_MY]), $infobannerpages);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'pages', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Infobanner bootstrap class.
            $name = 'theme_boost_union/infobanner'.$i.'bsclass';
            $title = get_string('infobannerbsclasssetting', 'theme_boost_union', array('no' => $i), true);
            $description = get_string('infobannerbsclasssetting_desc',
                    'theme_boost_union',
                    array('no' => $i, 'bootstrapnone' => get_string('bootstrapnone', 'theme_boost_union')),
                    true);
            $setting = new admin_setting_configselect($name, $title, $description,
                    'primary', $infobannerbsclasses);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'bsclass', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Infobanner order.
            $name = 'theme_boost_union/infobanner'.$i.'order';
            $title = get_string('infobannerordersetting', 'theme_boost_union', array('no' => $i), true);
            $description = get_string('infobannerordersetting_desc', 'theme_boost_union', array('no' => $i), true);
            $setting = new admin_setting_configselect($name, $title, $description,
                    $i, $infobannerorders);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'order', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Infobanner mode.
            $name = 'theme_boost_union/infobanner'.$i.'mode';
            $title = get_string('infobannermodesetting', 'theme_boost_union', array('no' => $i), true);
            $description = get_string('infobannermodesetting_desc', 'theme_boost_union', array('no' => $i), true);
            $setting = new admin_setting_configselect($name, $title, $description,
                    THEME_BOOST_UNION_SETTING_INFOBANNERMODE_PERPETUAL, $infobannermodes);
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'mode', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);

            // Setting: Infobanner start time.
            $name = 'theme_boost_union/infobanner'.$i.'start';
            $title = get_string('infobannerstartsetting', 'theme_boost_union', array('no' => $i), true);
            $description = get_string('infobannerstartsetting_desc', 'theme_boost_union', array('no' => $i), true);
            $setting = new admin_setting_configdatetime($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'start', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);
            $page->hide_if('theme_boost_union/infobanner'.$i.'start', 'theme_boost_union/infobanner'.$i.'mode', 'neq',
                    THEME_BOOST_UNION_SETTING_INFOBANNERMODE_TIMEBASED);

            // Setting: Infobanner end time.
            $name = 'theme_boost_union/infobanner'.$i.'end';
            $title = get_string('infobannerendsetting', 'theme_boost_union', array('no' => $i), true);
            $description = get_string('infobannerendsetting_desc', 'theme_boost_union', array('no' => $i), true);
            $setting = new admin_setting_configdatetime($name, $title, $description, '');
            $tab->add($setting);
            $page->hide_if('theme_boost_union/infobanner'.$i.'end', 'theme_boost_union/infobanner'.$i.'enabled', 'neq',
                    THEME_BOOST_UNION_SETTING_SELECT_YES);
            $page->hide_if('theme_boost_union/infobanner'.$i.'end', 'theme_boost_union/infobanner'.$i.'mode', 'neq',
                    THEME_BOOST_UNION_SETTING_INFOBANNERMODE_TIMEBASED);

            // Setting: Infobanner dismissible.
            $name = 'theme_boost_union/infobanner'.$i.'dismissible';
            $title = get_string('infobannerdismissiblesetting', 'theme_boost_union', array('no' => $i), true);
            $description = get_string('infobannerdismissiblesetting_desc', 'theme_boost_union', array('no' => $i), true);
            // Add Reset button if the info banner is already configured to be dismissible.
            if (get_config('theme_boost_union', 'infobanner'.$i.'dismissible') == true) {
                $reseturl = new moodle_url('/theme/boost_union/settings_infobanner_resetdismissed.php',
                        array('sesskey' => sesskey(), 'no' => $i));
                $description .= html_writer::empty_tag('br');
                $description .= html_writer::link($reseturl,
                        get_string('infobannerdismissresetbutton', 'theme_boost_union', array('no' => $i), true),
                        array('class' => 'btn btn-secondary mt-3', 'role' => 'button'));
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

        // Create Block region settings page with tabs.
        // (and allow users with the theme/boost_union:configure capability to access it).
        $page = new theme_boost_admin_settingspage_tabs('theme_boost_union_blockregion',
                get_string('configtitleblockregion', 'theme_boost_union', null, true),
                'theme/boost_union:configure');

        // Create Block region tab.
        $tab = new admin_settingpage('theme_boost_union_blockregion',
               get_string('blockregiontab', 'theme_boost_union', null, true));

        // Left block Region width.
        $name = 'theme_boost_union/leftregionwidth';
        $title = get_string('leftregionwidth', 'theme_boost_union');
        $description = get_string('leftregionwidthdesc', 'theme_boost_union');
        $default = '300px';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Right block Region width.
        $name = 'theme_boost_union/rightregionwidth';
        $title = get_string('rightregionwidth', 'theme_boost_union');
        $description = get_string('rightregionwidthdesc', 'theme_boost_union');
        $default = '300px';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Right block Region width.
        $name = 'theme_boost_union/regionplacement';
        $title = get_string('regionplacement', 'theme_boost_union');
        $description = get_string('regionplacementdesc', 'theme_boost_union');
        $options = [
            0 => get_string('nextmaincontent', 'theme_boost_union'),
            1 => get_string('nearwindow', 'theme_boost_union')
        ];
        $setting = new admin_setting_configselect($name, $title, $description, 0, $options);
        $tab->add($setting);

         // Add tab to settings page.
        $page->add($tab);

        // Add settings page to the admin settings category.
        $ADMIN->add('theme_boost_union', $page);
    }
}
