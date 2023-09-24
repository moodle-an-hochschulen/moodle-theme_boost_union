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
 * Theme Boost Union - Event handlers.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$observers = [
        [
                'eventname' => '\core\event\cohort_deleted',
                'callback' => '\theme_boost_union\eventobservers::cohort_deleted',
        ],
        [
                'eventname' => '\core\event\cohort_member_added',
                'callback' => '\theme_boost_union\eventobservers::cohort_member_added',
        ],
        [
                'eventname' => '\core\event\cohort_member_removed',
                'callback' => '\theme_boost_union\eventobservers::cohort_member_removed',
        ],
        [
                'eventname' => 'core\event\role_assigned',
                'callback' => '\theme_boost_union\eventobservers::role_assigned',
        ],
        [
                'eventname' => 'core\event\role_deleted',
                'callback' => '\theme_boost_union\eventobservers::role_deleted',
        ],
        [
                'eventname' => 'core\event\role_unassigned',
                'callback' => '\theme_boost_union\eventobservers::role_unassigned',
        ],
        [
                'eventname' => 'core\event\user_updated',
                'callback' => '\theme_boost_union\eventobservers::user_updated',
        ],
        [
                'eventname' => 'core\event\course_created',
                'callback' => '\theme_boost_union\eventobservers::course_updated',
        ],
        [
                'eventname' => 'core\event\course_completion_updated',
                'callback' => '\theme_boost_union\eventobservers::completion_updated',
        ],
        [
                'eventname' => 'core\event\course_module_completion_updated',
                'callback' => '\theme_boost_union\eventobservers::completion_updated',
        ],
        [
                'eventname' => 'core\event\course_updated',
                'callback' => '\theme_boost_union\eventobservers::course_updated',
        ],
        [
                'eventname' => 'core\event\course_deleted',
                'callback' => '\theme_boost_union\eventobservers::course_updated',
        ],
        [
                'eventname' => 'core\event\course_category_deleted',
                'callback' => '\theme_boost_union\eventobservers::category_updated',
        ],
        [
                'eventname' => 'core\event\course_category_updated',
                'callback' => '\theme_boost_union\eventobservers::category_updated',
        ],

];
