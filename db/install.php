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
 * Theme Boost Union - Install script.
 *
 * @package    theme_boost_union
 * @copyright  2024 André Menrath, University of Graz <andre.menrath@uni-graz.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use theme_boost_union\snippets;

/**
 * Executed on installation of Boost Union
 *
 * @return bool
 */
function xmldb_theme_boost_union_install() {
    // Load the builtin SCSS snippets into the database.
    snippets::add_builtin_snippets();

    return true;
}
