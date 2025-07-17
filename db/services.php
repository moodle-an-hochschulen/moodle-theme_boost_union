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
 * External services configuration for theme_boost_union.
 *
 * This file defines the external functions available for AJAX calls in the theme.
 *
 * @package    theme_boost_union
 * @copyright  2024 oncampus GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'theme_boost_union_section_nav' => [
        'classname'   => 'theme_boost_union\external\section_nav',
        'description' => 'Ajax to get navigation for a given section in given course.',
        'type'        => 'read',
        'ajax'        => true,
        'services'    => [],
    ],
];
