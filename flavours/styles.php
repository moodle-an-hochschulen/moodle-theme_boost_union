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
 * Theme Boost Union - Flavours styles serving
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
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

// Get parameters.
$flavourid = required_param('id', PARAM_INT);
$themerev = required_param('rev', PARAM_INT); // We do not really need the theme revision in this script, we just require it
                                              // to support proper cache control in the browser.

// Initialize SCSS code.
$scss = '';

// Get the raw SCSS and the background image from the database,
// throw an exception if it does not exist because then something is really wrong.
try {
    // Note: It would be worthwhile to pick this data from a MUC instance instead of fetching it from the DB
    // again and again. However, as the result is cached in the browser and the browser should not request
    // a flavour's CSS file again and again this should be ok for now.
    $flavour = $DB->get_record('theme_boost_union_flavours',
            ['id' => $flavourid], 'look_rawscss, look_backgroundimage', MUST_EXIST);

    // Catch the exception.
} catch (\Exception $e) {
    // Just die, there is no use to output any error message, it would even be counter-productive if the browser
    // tries to interpret it as CSS code.
    die;
}

// If the flavour has raw SCSS code.
// Note: In the current state of implementation, this setting only allows the usage of custom CSS, not SCSS.
// There is a follow-up issue on Github to add SCSS support.
// However, to ease this future improvement, the setting has already been called 'rawscss'.
if (!empty($flavour->look_rawscss)) {
    // Add it to the SCSS code.
    $scss .= $flavour->look_rawscss;
}

// If the flavour has a background image.
if ($flavour->look_backgroundimage != null) {
    // Compose the URL to the flavour's background image.
    $backgroundimageurl = moodle_url::make_pluginfile_url(
            context_system::instance()->id, 'theme_boost_union', 'flavours_look_backgroundimage', $flavourid,
            '/'.theme_get_revision(), '/'.$flavour->look_backgroundimage);

    // And add it to the SCSS code, adhering the fact that we must not overwrite the login page background image again.
    $scss .= 'body:not(.pagelayout-login) { ';
    $scss .= 'background-image: url("'.$backgroundimageurl.'");';
    $scss .= '}';
}

// Send out the resulting CSS code. The theme revision will be set as etag to support the browser caching.
css_send_cached_css_content($scss, theme_get_revision());
