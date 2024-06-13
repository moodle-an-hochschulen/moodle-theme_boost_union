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
 * Theme Boost Union - H5P styles serving.
 *
 * @package    theme_boost_union
 * @copyright  2023 Nina Herrmann <nina.herrmann@gmx.de>
 *             on behalf of Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Do not show any debug messages and any errors which might break the shipped CSS.
define('NO_DEBUG_DISPLAY', true);

// Do not do any upgrade checks here.
define('NO_UPGRADE_CHECK', true);

// Require config.
// Let codechecker ignore the next line because otherwise it would complain about a missing login check
// after requiring config.php which is really not needed.
require(__DIR__.'/../../../config.php'); // phpcs:disable moodle.Files.RequireLogin.Missing

// Require css sending libraries.
require_once($CFG->dirroot.'/lib/csslib.php');
require_once($CFG->dirroot.'/lib/configonlylib.php');

// Initialize SCSS code.
$scss = '';

// Get the raw SCSS from the admin setting,
// throw an exception if get_config throws an exception which happens only if something is really wrong.
try {
    // Note: In the current state of implementation, this setting only allows the usage of custom CSS, not SCSS.
    // There is a follow-up issue on Github to add SCSS support.
    // However, to ease this future improvement, the setting has already been called 'mobilescss'.
    $configh5pcss = get_config('theme_boost_union', 'cssh5p');

    // Catch the exception.
} catch (\Exception $e) {
    // Just die, there is no use to output any error message, it would even be counter-productive if the browser
    // tries to interpret it as CSS code.
    die;
}

// Always add the CSS code even if it is empty.
$scss .= $configh5pcss;

// Send out the resulting CSS code. The theme revision will be set as etag to support the browser caching.
css_send_cached_css_content($scss, theme_get_revision());
