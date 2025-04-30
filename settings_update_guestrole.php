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
 * Theme Boost Union - Update the guest role.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../config.php');

// Require login and sesskey.
require_login();
require_sesskey();

// Get system context.
$context = context_system::instance();

// Require the necessary capability to configure the theme (or an admin account which has this capability automatically).
require_capability('theme/boost_union:configure', $context);

// Get the URL parameters.
$fix = required_param('fix', PARAM_BOOL);

// Precheck: If the guestroleupgradedfrompre500 setting is not set to true.
if (get_config('theme_boost_union', 'guestroleupgradedfrompre500') != 1) {
    throw new moodle_exception('error:infobannerdismissnonotvalidnotset', 'theme_boost_union');
}

// If the admin wants to fix the guest role.
if ($fix == true) {

    // Fix the guest role.
    $guestarchetyperoles = get_archetype_roles('guest');
    if ($guestarchetyperoles) {
        // Define capabilities to add to guest roles.
        $capabilities = [
            'theme/boost_union:viewregionheader' => CAP_ALLOW,
            'theme/boost_union:viewregionoutsideleft' => CAP_ALLOW,
            'theme/boost_union:viewregionoutsideright' => CAP_ALLOW,
            'theme/boost_union:viewregionoutsidetop' => CAP_ALLOW,
            'theme/boost_union:viewregionoutsidebottom' => CAP_ALLOW,
            'theme/boost_union:viewregioncontentupper' => CAP_ALLOW,
            'theme/boost_union:viewregioncontentlower' => CAP_ALLOW,
            'theme/boost_union:viewregionfooterleft' => CAP_ALLOW,
            'theme/boost_union:viewregionfooterright' => CAP_ALLOW,
            'theme/boost_union:viewregionfootercenter' => CAP_ALLOW,
            'theme/boost_union:viewregionoffcanvasleft' => CAP_ALLOW,
            'theme/boost_union:viewregionoffcanvasright' => CAP_ALLOW,
            'theme/boost_union:viewregionoffcanvascenter' => CAP_ALLOW,
        ];

        // Loop through all guest roles.
        foreach ($guestarchetyperoles as $role) {
            // Add each capability to the role.
            foreach ($capabilities as $capability => $permission) {
                assign_capability($capability, $permission, $role->id, $context->id);
            }
        }
    }

    // Set the redirect message.
    $redirectmessage = get_string('blockregionsheading_guestrole_fixed', 'theme_boost_union');
} else {
    // Set the redirect message.
    $redirectmessage = get_string('blockregionsheading_guestrole_kept', 'theme_boost_union');
}

// Unset the guestroleupgradedfrompre500 marker.
unset_config('guestroleupgradedfrompre500', 'theme_boost_union');

// Redirect with a nice message.
$redirecturl = new core\url('/admin/settings.php',
        ['section' => 'theme_boost_union_feel'],
        'theme_boost_union_feel_blocks');
redirect($redirecturl, $redirectmessage, \core\output\notification::NOTIFY_SUCCESS);
