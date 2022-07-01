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

// Require the necessary libraries.
require_once($CFG->dirroot.'/theme/boost_union/lib.php');

if ($ADMIN->fulltree) {

    // Prepare options array for select settings.
    // Due to MDL-58376, we will use binary select settings instead of checkbox settings throughout this theme.
    $yesnooption = array(THEME_BOOST_UNION_SETTING_SELECT_YES => get_string('yes'),
            THEME_BOOST_UNION_SETTING_SELECT_NO => get_string('no'));

    // Create settings page with tabs (and allow users with the theme/boost_union:configure capability to access it).
    $settings = new theme_boost_admin_settingspage_tabs('themesettingboost_union',
            get_string('configtitle', 'theme_boost_union', null, true),
            'theme/boost_union:configure');


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


    // Create page tab.
    $page = new admin_settingpage('theme_boost_union_page', get_string('pagetab', 'theme_boost_union', null, true));

    // Create navigation heading.
    $name = 'theme_boost_union/navigationheading';
    $title = get_string('navigationheading', 'theme_boost_union', null, true);
    $setting = new admin_setting_heading($name, $title, null);
    $page->add($setting);

    // Setting: back to top button.
    $name = 'theme_boost_union/backtotopbutton';
    $title = get_string('backtotopbuttonsetting', 'theme_boost_union', null, true);
    $description = get_string('backtotopbuttonsetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
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


    // Create courses tab.
    $page = new admin_settingpage('theme_boost_union_courses', get_string('coursestab', 'theme_boost_union', null, true));

    // Create course related hints heading.
    $name = 'theme_boost_union/courserelatedhintsheading';
    $title = get_string('courserelatedhintsheading', 'theme_boost_union', null, true);
    $setting = new admin_setting_heading($name, $title, null);
    $page->add($setting);

    // Setting: Show hint for switched role.
    $name = 'theme_boost_union/showswitchedroleincourse';
    $title = get_string('showswitchedroleincoursesetting', 'theme_boost_union', null, true);
    $description = get_string('showswitchedroleincoursesetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Setting: Show hint in hidden courses.
    $name = 'theme_boost_union/showhintcoursehidden';
    $title = get_string('showhintcoursehiddensetting', 'theme_boost_union', null, true);
    $description = get_string('showhintcoursehiddensetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
    $page->add($setting);

    // Setting: Show hint guest for access.
    $name = 'theme_boost_union/showhintcourseguestaccess';
    $title = get_string('showhintcoursguestaccesssetting', 'theme_boost_union', null, true);
    $description = get_string('showhintcourseguestaccesssetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
    $page->add($setting);

    // Setting: Show hint for self enrolment without enrolment key.
    $name = 'theme_boost_union/showhintcourseselfenrol';
    $title = get_string('showhintcourseselfenrolsetting', 'theme_boost_union', null, true);
    $description = get_string('showhintcourseselfenrolsetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_configselect($name, $title, $description, THEME_BOOST_UNION_SETTING_SELECT_NO, $yesnooption);
    $page->add($setting);

    // Add tab to settings page.
    $settings->add($page);


    // Create footer tab.
    $page = new admin_settingpage('theme_boost_union_footer', get_string('footertab', 'theme_boost_union', null, true));

    // Create footnote heading.
    $name = 'theme_boost_union/footnoteheading';
    $title = get_string('footnoteheading', 'theme_boost_union', null, true);
    $setting = new admin_setting_heading($name, $title, null);
    $page->add($setting);

    // Setting: Footnote.
    $name = 'theme_boost_union/footnote';
    $title = get_string('footnotesetting', 'theme_boost_union', null, true);
    $description = get_string('footnotesetting_desc', 'theme_boost_union', null, true);
    $setting = new admin_setting_confightmleditor($name, $title, $description, '');
    $page->add($setting);

    // Add tab to settings page.
    $settings->add($page);
}

// Above, we made the theme setting not only available to admins but also
// to non-admins who have the theme/boost_union:configure as well.
// This was done when the theme_boost_admin_settingspage_tabs object is instantiated.
// However, for unknown reasons, Moodle allows users with this capability to access the theme settings on
// /admin/settings.php?section=themesettingboost_union without any problems,
// but it does not add the settings page to the site administration tree
// (even though and especially if the user has the moodle/site:configview capability as well).
// This means that these users won't find the theme settings unless they have the direct URL.
//
// To overcome this strange issue, we add an external admin page link to the site navigation
// for all non-admin users with this capability. This is only necessary if the Admin fulltree
// is not expanded yet.
$systemcontext = context_system::instance();
if ($ADMIN->fulltree == false &&
        has_capability('moodle/site:config', $systemcontext) == false&&
        has_capability('theme/boost_union:configure', $systemcontext) == true) {
    // Create new external settings page.
    $externalpage = new admin_externalpage('themesettingboost_union_formanagers',
            get_string('configtitle', 'theme_boost_union', null, true),
            new moodle_url('/admin/settings.php', array('section' => 'themesettingboost_union')),
            'theme/boost_union:configure');

    // Add external settings page to themes category.
    $ADMIN->add('themes', $externalpage);
}
