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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Add smart menu elements to template context.
$templatecontext['menubar'] = $primarymenu['menubar'] ?? [];
$templatecontext['bottombar'] = $primarymenu['bottombar'] ?? [];

// Add smart menu flag if the smart menu contains any menus to show.
$includesmartmenu = (isset($primarymenu['includesmartmenu']) && !empty($primarymenu['includesmartmenu']));
$templatecontext['includesmartmenu'] = $includesmartmenu ? true : false;
