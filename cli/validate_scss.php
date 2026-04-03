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
 * Theme Boost Union - CLI script to validate the SCSS as it would be compiled during a theme cache purge.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');

// Get cli options.
[$options, $unrecognized] = cli_get_params(
    ['help' => false],
    ['h' => 'help']
);
if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

// CLI help.
if ($options['help']) {
    $help = "Boost Union: Validate SCSS compilation

This script compiles the SCSS of the Boost Union theme exactly as it would happen during a theme
cache purge. The script just validates the compilation, no compiled CSS is written to disk or
stored in any cache.

This is useful for catching SCSS syntax errors in custom SCSS, raw SCSS settings,
external SCSS files or SCSS snippets during development and debugging.

Options:
-h, --help  Print out this help

Example:
\$ sudo -u www-data /usr/bin/php validate_scss.php
";
    cli_writeln($help);
    exit(0);
}

// Verify that Moodle is installed already.
if (empty($CFG->version)) {
    cli_error('Error: Database is not yet installed.');
}

// Verify that the script is not run during an upgrade.
if (moodle_needs_upgrading()) {
    cli_error('Error: Moodle upgrade pending, script execution suspended.');
}

// Get the admin user.
$admin = get_admin();
if (!$admin) {
    cli_error('Error: No admin account was found.');
}

// Execute the CLI script with admin permissions.
\core\session\manager::set_user($admin);

// Verify that the Boost Union theme is actually installed.
if (!array_key_exists('boost_union', core_component::get_plugin_list('theme'))) {
    cli_error('Error: Boost Union is not installed yet.');
}

// Print CLI script title.
cli_heading('Boost Union: Validate SCSS');

// Load the theme configuration. This also loads lib.php and renderers.php
// for the theme and its parents, making all callback functions available.
$themeconfig = \core\output\theme_config::load('boost_union');

// Resolve the SCSS property: import paths and the main SCSS file path (or closure).
$scssproperties = $themeconfig->get_scss_property();
if (!$scssproperties) {
    cli_error('Error: The theme did not define a SCSS file, or it is not readable.');
}
[$paths, $scss] = $scssproperties;

// Allow more memory and time, exactly as the theme cache build does.
raise_memory_limit(MEMORY_EXTRA);
core_php_time_limit::raise(300);

// We intentionally set up the SCSS compiler here ourselves rather than calling the core method
// \core\output\theme_config::get_css_content_from_scss() directly. There are two reasons for this:
// 1. The method is declared protected and therefore cannot be called from outside the class.
// 2. Even if it were accessible, it swallows SCSS exceptions and silently returns false on failure
// (see the catch block in that method: $compiled = false; debugging(..., DEBUG_DEVELOPER)).
// That means a compilation error would never surface as a visible error in the CLI output.
// By replicating the minimal compiler setup here using only the public API methods
// (get_scss_property(), get_pre_scss_code(), get_extra_scss_code()) and wrapping to_css() in our
// own try/catch, we can let the exception propagate and report the exact error message to the user.
$compiler = new core_scss();

$compiler->prepend_raw_scss($themeconfig->get_pre_scss_code());

if (is_string($scss)) {
    $compiler->set_file($scss);
} else {
    $compiler->append_raw_scss($scss($themeconfig));
    $compiler->setImportPaths($paths);
}

$compiler->append_raw_scss($themeconfig->get_extra_scss_code());

// Compile and report the result.
try {
    $compiled = $compiler->to_css();
    $compiler = null;
    unset($compiler);
    $bytes = strlen($compiled);
    cli_writeln("Success: SCSS compiled successfully ($bytes bytes of CSS generated).");
} catch (\Exception $e) {
    $compiler = null;
    unset($compiler);
    cli_error('Error: SCSS compilation failed: ' . $e->getMessage());
}

exit(0);
