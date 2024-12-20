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
 * This file is responsible for serving the one huge CSS of each theme.
 *
 * This file is copied and modified from /theme/styles.php.
 * It is only called to serve the Boost Union CSS if a flavour is applied to the page.
 * If no flavour is applied, the original /theme/styles.php is called.
 * This is controlled by theme_boost_union_alter_css_urls().
 *
 * @package   theme_boost_union
 * @copyright 2023 Mario Wehr
 *            based on code 2009 by Petr Skoda (skodak)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Disable moodle specific debug messages and any errors in output,
// comment out when debugging or better look into error log!
define('NO_DEBUG_DISPLAY', true);

define('ABORT_AFTER_CONFIG', true);
require('../../../config.php');
require_once($CFG->dirroot.'/lib/csslib.php');

if ($slashargument = min_get_slash_argument()) {
    $slashargument = ltrim($slashargument, '/');
    if (substr_count($slashargument, '/') < 2) {
        css_send_css_not_found();
    }

    if (strpos($slashargument, '_s/') === 0) {
        // Can't use SVG.
        $slashargument = substr($slashargument, 3);
        $usesvg = false;
    } else {
        $usesvg = true;
    }

    list($themename, $rev, $flavourid, $type) = explode('/', $slashargument, 4);
    $themename = min_clean_param($themename, 'SAFEDIR');
    $rev       = min_clean_param($rev, 'RAW');
    $flavourid = min_clean_param($flavourid, 'INT');
    $type      = min_clean_param($type, 'SAFEDIR');

} else {
    $themename = min_optional_param('theme', 'standard', 'SAFEDIR');
    $rev       = min_optional_param('rev', 0, 'RAW');
    $type      = min_optional_param('type', 'all', 'SAFEDIR');
    $usesvg    = (bool)min_optional_param('svg', '1', 'INT');
}

// If no flavourid is provided, this file must have been called by mistake.
// In this case, we simply die.
if (empty($flavourid)) {
    css_send_css_not_found();
}

// Store the active flavour in the global scope.
// This global variable is only set here and read in two functions in lib.php.
// This approach feels a bit hacky but it is the most efficient way to get the flavour ID into that function.
global $themeboostunionappliedflavour;
$themeboostunionappliedflavour = $flavourid;

// Check if we received a theme sub revision which allows us
// to handle local caching on a per theme basis.
$values = explode('_', $rev);
$rev = min_clean_param(array_shift($values), 'INT');
$themesubrev = array_shift($values);

if (!is_null($themesubrev)) {
    $themesubrev = min_clean_param($themesubrev, 'INT');
}

// Note: We only check validity of the revision number here, we do not check the theme sub-revision because this is
// not solely based on time.
if (!min_is_revision_valid_and_current($rev)) {
    // If the rev is invalid, normalise it to -1 to disable all caching.
    $rev = -1;
}

// Check that type fits into the expected values.
if (!in_array($type, ['all', 'all-rtl', 'editor', 'editor-rtl'])) {
    css_send_css_not_found();
}

// phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedIf
if (file_exists("$CFG->dirroot/theme/$themename/config.php")) {
    // The theme exists in standard location - ok.
// phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedIf
} else if (!empty($CFG->themedir) && file_exists("$CFG->themedir/$themename/config.php")) {
    // Alternative theme location contains this theme - ok.
} else {
    header('HTTP/1.0 404 not found');
    die('Theme was not found, sorry.');
}

$candidatedir = "$CFG->localcachedir/theme/$rev/$themename/css";
$candidatesheet = "{$candidatedir}/" . theme_boost_union_flavour_styles_get_filename($type, $themesubrev, $flavourid, $usesvg);
$etag = theme_styles_get_etag($themename, $rev, $type, $themesubrev, $usesvg);

if (file_exists($candidatesheet)) {
    if (!empty($_SERVER['HTTP_IF_NONE_MATCH']) || !empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        // We do not actually need to verify the etag value because our files
        // never change in cache because we increment the rev counter.
        css_send_unmodified(filemtime($candidatesheet), $etag);
    }
    css_send_cached_css($candidatesheet, $etag);
}

// Ok, now we need to start normal moodle script, we need to load all libs and $DB.
define('ABORT_AFTER_CONFIG_CANCEL', true);

define('NO_MOODLE_COOKIES', true); // Session not used here.
define('NO_UPGRADE_CHECK', true);  // Ignore upgrade check.

require("$CFG->dirroot/lib/setup.php");

$theme = theme_config::load($themename);
$theme->force_svg_use($usesvg);
$theme->set_rtl_mode(substr($type, -4) === '-rtl');

$themerev = theme_get_revision();
$currentthemesubrev = theme_get_sub_revision_for_theme($themename);

$cache = true;
// If the client is requesting a revision that doesn't match both
// the global theme revision and the theme specific revision then
// tell the browser not to cache this style sheet because it's
// likely being regenerated.
if ($themerev <= 0 || $themerev != $rev || $themesubrev != $currentthemesubrev) {
    $rev = $themerev;
    $themesubrev = $currentthemesubrev;
    $cache = false;

    $candidatedir = "$CFG->localcachedir/theme/$rev/$themename/css";
    $candidatesheet = "{$candidatedir}/" . theme_boost_union_flavour_styles_get_filename($type, $themesubrev, $flavourid, $usesvg);
    $etag = theme_styles_get_etag($themename, $rev, $type, $themesubrev, $usesvg);
}

make_localcache_directory('theme', false);

if ($type === 'editor' || $type === 'editor-rtl') {
    $csscontent = $theme->get_css_content_editor();

    if ($cache) {
        css_store_css($theme, $candidatesheet, $csscontent);
        css_send_cached_css($candidatesheet, $etag);
    } else {
        css_send_uncached_css($csscontent);
    }

}

if (($fallbacksheet = theme_styles_fallback_content($theme)) && !$theme->has_css_cached_content()) {
    // The theme is not yet available and a fallback is available.
    // Return the fallback immediately, specifying the Content-Length, then generate in the background.
    $css = file_get_contents($fallbacksheet);
    css_send_temporary_css($css);

    // The fallback content has now been sent.
    // There will be an attempt to generate the content, but it should not be served.
    // The Content-Length above means that the client will disregard it anyway.
    $sendaftergeneration = false;

    // There may be another client currently holding a lock and generating the stylesheet.
    // Use a very low lock timeout as the connection will be ended immediately afterwards.
    $locktimeout = 1;
} else {
    // There is no fallback content to be issued here, therefore the generated content must be output.
    $sendaftergeneration = true;

    // Use a realistic lock timeout as the intention is to avoid lock contention.
    $locktimeout = rand(90, 120);
}

// Attempt to fetch the lock.
$lockfactory = \core\lock\lock_config::get_lock_factory('core_theme_get_css_content');
$lock = $lockfactory->get_lock($themename, $locktimeout);

if ($sendaftergeneration || $lock) {
    // Either the lock was successful, or the lock was unsuccessful but the content *must* be sent.

    // The content does not exist locally.
    // Generate and save it.
    $candidatesheet = theme_boost_union_flavour_styles_generate_and_store($theme, $rev, $themesubrev, $candidatedir, $flavourid);

    if ($lock) {
        $lock->release();
    }

    if ($sendaftergeneration) {
        if (!$cache) {
            // Do not pollute browser caches if invalid revision requested,
            // let's ignore legacy IE breakage here too.
            css_send_uncached_css(file_get_contents($candidatesheet));

        } else {
            // Real browsers - this is the expected result!
            css_send_cached_css($candidatesheet, $etag);
        }
    }
}

/**
 * Generate the theme CSS and store it.
 *
 * @param   theme_config    $theme The theme to be generated
 * @param   int             $rev The theme revision
 * @param   int             $themesubrev The theme sub-revision
 * @param   string          $candidatedir The directory that it should be stored in
 * @param   int             $flavourid The flavour ID
 * @return  string          The path that the primary CSS was written to
 */
function theme_boost_union_flavour_styles_generate_and_store($theme, $rev, $themesubrev, $candidatedir, $flavourid) {
    global $CFG;
    require_once("{$CFG->libdir}/filelib.php");

    // Generate the content first.
    if (!$csscontent = theme_boost_union_flavour_get_css_cached_content($theme, $flavourid)) {
        $csscontent = $theme->get_css_content();
        theme_boost_union_flavour_set_css_content_cache($theme, $flavourid, $csscontent);
    }

    if ($theme->get_rtl_mode()) {
        $type = "all-rtl";
    } else {
        $type = "all";
    }

    // Determine the candidatesheet path.
    $candidatesheet = "{$candidatedir}/" . theme_boost_union_flavour_styles_get_filename($type, $themesubrev, $flavourid,
            $theme->use_svg_icons());

    // Store the CSS.
    css_store_css($theme, $candidatesheet, $csscontent);

    // Store the fallback CSS in the temp directory.
    // This file is used as a fallback when waiting for a theme to compile and is not versioned in any way.
    $fallbacksheet = make_temp_directory("theme/{$theme->name}")
        . "/"
        . theme_boost_union_flavour_styles_get_filename($type, $themesubrev, $flavourid, $theme->use_svg_icons());
    css_store_css($theme, $fallbacksheet, $csscontent);

    // Delete older revisions from localcache.
    $themecachedirs = glob("{$CFG->localcachedir}/theme/*", GLOB_ONLYDIR);
    foreach ($themecachedirs as $localcachedir) {
        $cachedrev = [];
        preg_match("/\/theme\/([0-9]+)$/", $localcachedir, $cachedrev);
        $cachedrev = isset($cachedrev[1]) ? intval($cachedrev[1]) : 0;
        if ($cachedrev > 0 && $cachedrev < $rev) {
            fulldelete($localcachedir);
        }
    }

    // Delete older theme subrevision CSS from localcache.
    $subrevfiles = glob("{$CFG->localcachedir}/theme/{$rev}/{$theme->name}/css/*.css");
    foreach ($subrevfiles as $subrevfile) {
        $cachedsubrev = [];
        preg_match("/_([0-9]+)_([0-9]+)\.([0-9]+\.)?css$/", $subrevfile, $cachedsubrev);
        $cachedsubrev = isset($cachedsubrev[1]) ? intval($cachedsubrev[1]) : 0;
        if ($cachedsubrev > 0 && $cachedsubrev < $themesubrev) {
            fulldelete($subrevfile);
        }
    }

    return $candidatesheet;
}

/**
 * Fetch the preferred fallback content location if available.
 *
 * @param   theme_config    $theme The theme to be generated
 * @return  string          The path to the fallback sheet on disk
 */
function theme_styles_fallback_content($theme) {
    global $CFG;

    if (!$theme->usefallback) {
        // This theme does not support fallbacks.
        return false;
    }

    $type = $theme->get_rtl_mode() ? 'all-rtl' : 'all';
    $filename = theme_boost_union_flavour_styles_get_filename($type);

    $fallbacksheet = "{$CFG->tempdir}/theme/{$theme->name}/{$filename}";
    if (file_exists($fallbacksheet)) {
        return $fallbacksheet;
    }

    return false;
}

/**
 * Get the filename for the specified configuration.
 *
 * @param   string  $type The requested sheet type
 * @param   int     $themesubrev The theme sub-revision
 * @param   int     $flavourid The flavour ID
 * @param   bool    $usesvg Whether SVGs are allowed
 * @return  string  The filename for this sheet
 */
function theme_boost_union_flavour_styles_get_filename($type, $themesubrev = 0, $flavourid = 0, $usesvg = true) {
    $filename = $type;
    $filename .= ($themesubrev > 0) ? "_{$themesubrev}" : '';
    $filename .= ($flavourid > 0) ? "_{$flavourid}" : '';
    $filename .= $usesvg ? '' : '-nosvg';

    return "{$filename}.css";
}

/**
 * Determine the correct etag for the specified configuration.
 *
 * @param   string  $themename The name of the theme
 * @param   int     $rev The revision number
 * @param   string  $type The requested sheet type
 * @param   int     $themesubrev The theme sub-revision
 * @param   bool    $usesvg Whether SVGs are allowed
 * @return  string  The etag to use for this request
 */
function theme_styles_get_etag($themename, $rev, $type, $themesubrev, $usesvg) {
    $etag = [$rev, $themename, $type, $themesubrev];

    if (!$usesvg) {
        $etag[] = 'nosvg';
    }

    return sha1(implode('/', $etag));
}

/**
 * Return cached post processed CSS content.
 *
 * This function is copied and modified from /lib/classes/output/theme_config.php
 *
 * @param theme_config $theme The theme to be generated
 * @param int $flavourid The flavour ID
 * @return bool|string The cached css content or false if not found.
 */
function theme_boost_union_flavour_get_css_cached_content($theme, $flavourid) {
    $key = theme_boost_union_flavour_get_css_cache_key($theme, $flavourid);
    $cache = cache::make('core', 'postprocessedcss');

    return $cache->get($key);
}

/**
 * Generate the css content cache key.
 *
 * This function is copied and modified from /lib/classes/output/theme_config.php
 *
 * @param theme_config $theme The theme to be generated
 * @param int $flavourid The flavour ID
 * @return string The post processed css cache key.
 */
function theme_boost_union_flavour_get_css_cache_key($theme, $flavourid) {
    $nosvg = (!$theme->use_svg_icons()) ? 'nosvg_' : '';
    $rtlmode = ($theme->get_rtl_mode() == true) ? 'rtl' : 'ltr';

    return $nosvg . $theme->name . '_' . $flavourid . '_' . $rtlmode;
}

/**
 * Set post processed CSS content cache.
 *
 * This function is copied and modified from /lib/classes/output/theme_config.php
 *
 * @param theme_config $theme The theme to be generated
 * @param int $flavourid The flavour ID
 * @param string $csscontent The post processed CSS content.
 * @return bool True if the content was successfully cached.
 */
function theme_boost_union_flavour_set_css_content_cache($theme, $flavourid, $csscontent) {
    $cache = cache::make('core', 'postprocessedcss');
    $key = theme_boost_union_flavour_get_css_cache_key($theme, $flavourid);

    return $cache->set($key, $csscontent);
}
