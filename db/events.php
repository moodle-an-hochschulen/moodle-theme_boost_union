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

$observers = array(
        array(
                'eventname' => '\core\event\cohort_deleted',
                'callback' => '\theme_boost_union\eventobservers::cohort_deleted'
        ),
        array(
                'eventname' => '\core\event\cohort_member_added',
                'callback' => '\theme_boost_union\eventobservers::cohort_member_added'
        ),
        array(
                'eventname' => '\core\event\cohort_member_removed',
                'callback' => '\theme_boost_union\eventobservers::cohort_member_removed'
        ),
);
