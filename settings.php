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
}
