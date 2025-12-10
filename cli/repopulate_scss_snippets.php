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
 * Theme Boost Union - CLI script to re-populate the built-in SCSS snippets in the database.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
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
    $help = "Boost Union: Re-populate built-in SCSS snippets

Normally, the built-in SCSS snippets are re-populated if the Boost Union version is raised. This is triggered in db/upgrade.php.
This is perfectly fine as long as admins to not want to fiddle with the list of buit-in snippets manually.

To ease such admin tasks as well as the crafting of SCSS Snippet PRs,
this CLI script can be run and will re-populate the list of built-in SCSS snippets based on the list of snippets
which exist on disk in the theme/boost_union/snippets/builtin directory.

Options:
-h, --help      Print out this help

Example:
\$ sudo -u www-data /usr/bin/php repopulate_scss_snippets.php
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
cli_heading('Boost Union: Re-populate built-in SCSS snippets');

// Execute the function to repopulate the SCSS snippets.
try {
    \theme_boost_union\snippets::add_builtin_snippets();
    cli_writeln('Success: SCSS snippets have been re-populated in the database.');
} catch (Exception $e) {
    cli_error('Error: ' . $e->getMessage());
}

exit(0);
