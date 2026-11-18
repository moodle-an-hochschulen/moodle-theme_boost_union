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
 * Theme Boost Union - smart menus include.
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Please note: $primarymenu is the export of \theme_boost_union\output\navigation\primary::export_for_template().
// The smart menu specific keys (slots, amdmodules, includesmartmenu, ...) are only set there if at least one smart menu
// has been built for the current user. Otherwise, the export of the core primary navigation is returned as it is
// and these keys are missing. This is the case if
// - no smart menu exists at all or all smart menus are hidden from the current user (by visibility or by restrictions), or
// - the smart menu table does not exist (yet), i.e. during an installation or upgrade.
// Thus, each key has to be read with a fallback value here.

// Add the template slots into which the smart menu location providers rendered their smart menus.
// The slots are fixed insertion points in the layout templates (e.g. smartmenuslots.navbar_above).
// If the slots key is set, it holds all slots, with an empty string for each slot which has not been filled by a
// location provider. If the slots key is missing, the same structure with empty strings for all slots is built
// here, so that the templates always get all slot variables.
$templatecontext['smartmenuslots'] = $primarymenu['slots'] ??
        array_fill_keys(\theme_boost_union\smartmenu\location\base::SLOTS, '');

// Load the AMD modules which the smart menu location and presentation providers need on the page.
// The amdmodules key holds the module names which have been collected from all providers which render at least one
// smart menu on the page. The init() function of each module is called without arguments.
// If the amdmodules key is missing, no module has to be loaded.
foreach (($primarymenu['amdmodules'] ?? []) as $smartmenuamdmodule) {
    $PAGE->requires->js_call_amd($smartmenuamdmodule, 'init');
}

// Add smart menu flag if the smart menu contains any menus to show.
$includesmartmenu = (isset($primarymenu['includesmartmenu']) && !empty($primarymenu['includesmartmenu']));
$templatecontext['includesmartmenu'] = $includesmartmenu ? true : false;

// Add smart menu flag if a rendered smart menu item requires client-side starred-courses cache invalidation.
$includestarredcacheinvalidation = (
    isset($primarymenu['includestarredcacheinvalidation']) &&
    !empty($primarymenu['includestarredcacheinvalidation'])
);
$templatecontext['includestarredcacheinvalidation'] = $includestarredcacheinvalidation ? true : false;
