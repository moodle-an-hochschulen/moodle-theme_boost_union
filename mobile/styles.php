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
 * Theme Boost Union - Mobile styles serving. Logic copied from flavours/style.php.
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
// @codingStandardsIgnoreStart
// Let codechecker ignore the next line because otherwise it would complain about a missing login check
// after requiring config.php which is really not needed.require('../config.php');
require(__DIR__.'/../../../config.php');
// @codingStandardsIgnoreEnd

// Require css sending libraries.
require_once($CFG->dirroot.'/lib/csslib.php');
require_once($CFG->dirroot.'/lib/configonlylib.php');

// Initialize CSS code.
$css = '';

// Get the css fro the setting.
try {
    $configmobilecss = get_config('theme_boost_union', 'mobilecss');
} catch (\Exception $e) {
    // Should not happen but in case...
    die;
}

// Always add the css-code in case it is empty - maybe it is supposed to be deleted.
$css .= $configmobilecss;
// Send out the resulting CSS code. The theme revision will be set as etag to support the browser caching.
css_send_cached_css_content($css, theme_get_revision());
