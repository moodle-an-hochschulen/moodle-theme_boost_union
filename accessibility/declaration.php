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
 * Theme Boost Union - Declaration of accessibility page.
 *
 * @package    theme_boost_union
 * @copyright  2024 Katalin Lukacs Toth, ZHAW Zurich University of Applied Sciences <lukc@zhaw.ch>
 * @copyright  2024 Simon Schoenenberger, ZHAW Zurich University of Applied Sciences <scgo@zhaw.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Include config.php.
// Let codechecker ignore the next line because otherwise it would complain about a missing login check
// after requiring config.php which is really not needed.
require(__DIR__ . '/../../../config.php'); // phpcs:disable moodle.Files.RequireLogin.Missing

// Require the necessary libraries.
require_once($CFG->dirroot.'/theme/boost_union/lib.php');
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

// Set page URL.
$PAGE->set_url('/theme/boost_union/accessibility/declaration.php');

// Set page layout.
$PAGE->set_pagelayout('standard');

// Set page context.
$PAGE->set_context(context_system::instance());

// Add page name as body class.
$PAGE->add_body_class('theme_boost_union-accessibilitydeclaration');

// Get theme config.
$config = get_config('theme_boost_union');

// If the declaration of accessibility page is disabled, we just show a short friendly warning page and are done.
if ($config->enableaccessibilitydeclaration != THEME_BOOST_UNION_SETTING_SELECT_YES) {
    echo $OUTPUT->header();
    $notification = new \core\output\notification(get_string('accessibilitydeclarationdisabled', 'theme_boost_union'),
        \core\output\notification::NOTIFY_INFO);
    $notification->set_show_closebutton(false);
    echo $OUTPUT->render($notification);
    echo $OUTPUT->footer();
    die;
}

// Set page title.
$PAGE->set_title(theme_boost_union_get_accessibility_pagetitle('declaration'));

// Start page output.
echo $OUTPUT->header();

// Show page heading.
echo $OUTPUT->heading(theme_boost_union_get_accessibility_pagetitle('declaration'));

// Output declaration of accessibility page content.
echo format_text($config->accessibilitydeclarationcontent, FORMAT_MOODLE, ['trusted' => true, 'noclean' => true]);

// Finish page.
echo $OUTPUT->footer();
