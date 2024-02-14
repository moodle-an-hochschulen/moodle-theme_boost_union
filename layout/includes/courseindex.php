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
 * Theme Boost Union - course index modification include
 *
 * @package   theme_boost_union
 * @copyright 2024 Christian Wolters <info@christianwolters.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// If activity type icons should be displayed in the course index.
$showcmicon = get_config('theme_boost_union', 'courseindexmodiconenabled');
if ($showcmicon == THEME_BOOST_UNION_SETTING_SELECT_YES) {
    // Add a class to the body tag.
    $extraclasses[] = 'hascourseindexcmicons';

    // Switch between the possible activity completion presentation modes.
    $completionposition = get_config('theme_boost_union', 'courseindexcompletioninfoposition');
    switch ($completionposition) {
        // Completion encoded in icon color.
        case THEME_BOOST_UNION_SETTING_COMPLETIONINFOPOSITION_ICONCOLOR:
            $extraclasses[] = 'hascourseindexcplicon';
            break;
        // Completion at start of line.
        case THEME_BOOST_UNION_SETTING_COMPLETIONINFOPOSITION_STARTOFLINE:
            $extraclasses[] = 'hascourseindexcplsol';
            break;
        // Completion at end of line.
        case THEME_BOOST_UNION_SETTING_COMPLETIONINFOPOSITION_ENDOFLINE:
            $extraclasses[] = 'hascourseindexcpleol';
            break;
    }

    // Otherwise.
} else {
    // Add a class to the body tag.
    $extraclasses[] = 'nocourseindexcmicons';
}
