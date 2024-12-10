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
 * Theme Boost Union - Settings overview file (which is just used as broker page).
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../config.php');

// Require login.
require_login();

// Get system context.
$context = context_system::instance();

// Require the necessary capability to configure the theme (or an admin account which has this capability automatically).
require_capability('theme/boost_union:configure', $context);

// Set page URL.
$PAGE->set_url('/theme/boost_union/settings_overview.php');

// Set page layout.
$PAGE->set_pagelayout('admin');

// Set page context.
$PAGE->set_context($context);

// Set page title.
$PAGE->set_title(get_string('settingsoverview_title', 'theme_boost_union'));

// Start page output.
echo $OUTPUT->header();

// Show page heading.
echo $OUTPUT->heading(get_string('settingsoverview_title', 'theme_boost_union'));

// First, compose template context for Boost Union setting pages.
$templatecontext['cards'][] = [
    'label' => get_string('configtitlelook', 'theme_boost_union'),
    'desc' => get_string('settingsoverview_look_desc', 'theme_boost_union'),
    'btn' => 'primary',
    'url' => new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_look']),
];
$templatecontext['cards'][] = [
    'label' => get_string('configtitlefeel', 'theme_boost_union'),
    'desc' => get_string('settingsoverview_feel_desc', 'theme_boost_union'),
    'btn' => 'primary',
    'url' => new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_feel']),
];
$templatecontext['cards'][] = [
    'label' => get_string('configtitlecontent', 'theme_boost_union'),
    'desc' => get_string('settingsoverview_content_desc', 'theme_boost_union'),
    'btn' => 'primary',
    'url' => new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_content']),
];
$templatecontext['cards'][] = [
    'label' => get_string('configtitlefunctionality', 'theme_boost_union'),
    'desc' => get_string('settingsoverview_functionality_desc', 'theme_boost_union'),
    'btn' => 'primary',
    'url' => new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_functionality']),
];
$templatecontext['cards'][] = [
    'label' => get_string('configtitleaccessibility', 'theme_boost_union'),
    'desc' => get_string('settingsoverview_accessibility_desc', 'theme_boost_union'),
    'btn' => 'primary',
    'url' => new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_accessibility']),
];
$templatecontext['cards'][] = [
    'label' => get_string('configtitleflavours', 'theme_boost_union'),
    'desc' => get_string('settingsoverview_flavours_desc', 'theme_boost_union'),
    'btn' => 'primary',
    'url' => new \core\url('/theme/boost_union/flavours/overview.php'),
];
$templatecontext['cards'][] = [
    'label' => get_string('smartmenus', 'theme_boost_union'),
    'desc' => get_string('settingsoverview_smartmenus_desc', 'theme_boost_union'),
    'btn' => 'primary',
    'url' => new \core\url('/theme/boost_union/smartmenus/menus.php'),
];

// Then, add additional cards from Boost Union Child themes.
$pluginsfunction = get_plugins_with_function('extend_busettingsoverview', 'lib.php');
foreach ($pluginsfunction as $plugintype => $plugins) {
    foreach ($plugins as $function) {
        try {
            $buccards = $function();
            $templatecontext['cards'] = array_merge($templatecontext['cards'], $buccards);
        } catch (Throwable $e) {
            debugging("Exception calling '$function'", DEBUG_DEVELOPER, $e->getTrace());
        }
    }
}

// Finally, add the category overview card.
$templatecontext['cards'][] = [
    'label' => get_string('settingsoverview_all', 'theme_boost_union'),
    'desc' => get_string('settingsoverview_all_desc', 'theme_boost_union'),
    'btn' => 'secondary',
    'url' => new \core\url('/admin/category.php', ['category' => 'theme_boost_union']),
];

// Render template.
echo $OUTPUT->render_from_template('theme_boost_union/settings-overview', $templatecontext);

// Finish page.
echo $OUTPUT->footer();
