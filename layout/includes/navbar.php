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
 * Theme Boost Union - Navbar layout include.
 *
 * @package    theme_boost_union
 * @copyright  2023 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$navbarcolorsetting = get_config('theme_boost_union', 'navbarcolor');

// Compose the navbar color classes based on the navbarcolor setting.
switch($navbarcolorsetting) {
    case THEME_BOOST_UNION_SETTING_NAVBARCOLOR_DARK:
        $templatecontext['navbarcolorclasses'] = 'navbar-dark bg-dark';
        break;
    case THEME_BOOST_UNION_SETTING_NAVBARCOLOR_PRIMARYLIGHT:
        $templatecontext['navbarcolorclasses'] = 'navbar-light bg-primary';
        break;
    case THEME_BOOST_UNION_SETTING_NAVBARCOLOR_PRIMARYDARK:
        $templatecontext['navbarcolorclasses'] = 'navbar-dark bg-primary';
        break;
    case THEME_BOOST_UNION_SETTING_NAVBARCOLOR_LIGHT:
    default:
        $templatecontext['navbarcolorclasses'] = 'navbar-light bg-white';
        break;
}
