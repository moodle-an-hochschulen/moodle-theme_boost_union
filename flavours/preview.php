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
 * Theme Boost Union - Flavours preview page
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Require config.
require(__DIR__.'/../../../config.php');

// Require plugin libraries.
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');
require_once($CFG->dirroot.'/theme/boost_union/flavours/flavourslib.php');

// Get parameters.
$flavourid = required_param('id', PARAM_INT);

// Get system context.
$context = context_system::instance();

// Access checks.
require_login();
require_capability('theme/boost_union:configure', $context);

// Prepare the page.
$PAGE->set_context($context);
$PAGE->set_url(new core\url('/theme/boost_union/flavours/preview.php', ['id' => $flavourid]));
$PAGE->set_cacheable(false);
$PAGE->navbar->add(get_string('pluginname', 'theme_boost_union'), new core\url('/admin/category.php',
        ['category' => 'theme_boost_union']));
$PAGE->navbar->add(get_string('flavoursflavours', 'theme_boost_union'), new core\url('/theme/boost_union/flavours/overview.php'));
$PAGE->set_title(theme_boost_union_get_externaladminpage_title(get_string('flavourspreviewflavour', 'theme_boost_union')));
$PAGE->set_heading(get_string('flavourspreviewflavour', 'theme_boost_union'));
$PAGE->navbar->add(get_string('flavourspreviewflavour', 'theme_boost_union'));

// Start page output.
echo $OUTPUT->header();

// Show example content.
echo get_string('flavourspreviewblindtext', 'theme_boost_union');

// Show back to overview button.
echo $OUTPUT->box_start('text-center');
echo $OUTPUT->single_button(
        new \core\url('/theme/boost_union/flavours/overview.php'),
        get_string('flavoursbacktooverview', 'theme_boost_union'), 'get');
echo $OUTPUT->box_end();

// Finish page output.
echo $OUTPUT->footer();
