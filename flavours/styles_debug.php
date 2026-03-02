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
 * This file is responsible for serving of individual style sheets in designer mode for Boost Union flavours.
 *
 * This file is copied and modified from /theme/styles_debug.php.
 * It is only called to serve the Boost Union CSS if a flavour is applied to the page in theme designer mode.
 * If no flavour is applied, the original /theme/styles_debug.php is called.
 * This is controlled by theme_boost_union_alter_css_urls().
 *
 * @package   theme_boost_union
 * @copyright 2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 *            based on code 2009 by Petr Skoda (skodak)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Disable moodle specific debug messages and any errors in output,
// comment out when debugging or better look into error log!
define('NO_DEBUG_DISPLAY', true);
define('NO_UPGRADE_CHECK', true);
define('NO_MOODLE_COOKIES', true);

require('../../../config.php');
require_once($CFG->dirroot . '/lib/csslib.php');

$themename = optional_param('theme', 'standard', PARAM_SAFEDIR);
$type      = optional_param('type', '', PARAM_SAFEDIR);
$subtype   = optional_param('subtype', '', PARAM_SAFEDIR);
$sheet     = optional_param('sheet', '', PARAM_SAFEDIR);
$usesvg    = optional_param('svg', 1, PARAM_BOOL);
$rtl       = optional_param('rtl', false, PARAM_BOOL);
$flavourid = optional_param('flavourid', 0, PARAM_INT);

// If no flavourid is provided, this file must have been called by mistake.
// In this case, we simply die.
if (empty($flavourid)) {
    css_send_css_not_found();
}

// phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedIf
if (file_exists("$CFG->dirroot/theme/$themename/config.php")) {
    // The theme exists in standard location - ok.
// phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedIf
} else if (!empty($CFG->themedir) && file_exists("$CFG->themedir/$themename/config.php")) {
    // Alternative theme location contains this theme - ok.
} else {
    css_send_css_not_found();
}

// Store the active flavour in the global scope.
// This global variable is only set here and read in two functions in lib.php.
// This approach feels a bit hacky but it is the most efficient way to get the flavour ID into that function.
global $themeboostunionappliedflavour;
$themeboostunionappliedflavour = $flavourid;

$theme = theme_config::load($themename);
$theme->force_svg_use($usesvg);
$theme->set_rtl_mode($rtl);

if ($type === 'editor') {
    $csscontent = $theme->get_css_content_editor();
    css_send_uncached_css($csscontent);
}

// We need some kind of caching here because otherwise the page navigation becomes
// way too slow in theme designer mode. Feel free to create full cache definition later...
$key = "$type $subtype $sheet $usesvg $rtl $flavourid";
$cache = cache::make_from_params(\core_cache\store::MODE_APPLICATION, 'core', 'themedesigner', ['theme' => $themename]);
if ($content = $cache->get($key)) {
    if ($content['created'] > time() - THEME_DESIGNER_CACHE_LIFETIME) {
        $csscontent = $content['data'];
        css_send_uncached_css($csscontent);
    }
}

$csscontent = $theme->get_css_content_debug($type, $subtype, $sheet);
$cache->set($key, ['data' => $csscontent, 'created' => time()]);

css_send_uncached_css($csscontent);
