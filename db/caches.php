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
 * Theme Boost Union - Cache definitions.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$definitions = [
        // This cache stores the flavours which apply to a particular user in his user session.
        // It is there to avoid that the flavour which applies has to be calculated on every page load.
        // The cache key is the page's course category ID
        // (and 0 for all pages which are not placed within a course category).
        // The cache value is the full flavour object.
        //
        // This is a session cache by purpose. It isn't an application cache as it contains user-specific data.
        // And it isn't a user preference as we have to store multiple values per user (one value per page category ID).
        // A benefit of the session cache is that it is invalidated on each login which means that, if the cache misbehaves for any
        // reason, everything should be fine again after logging out and in again.
        //
        // Beyond that, the cache has to invalidated based on several events:
        // 1. When a flavour is created / edited / deleted
        // -- (this is realized in flavours/edit.php where \core_cache\helper::purge_by_event
        // -- is called with an invalidationevent which purges the cache for all users).
        // 2. When the flavours are re-sorted
        // -- (this is realized in flavours/overview.php where \core_cache\helper::purge_by_event
        // -- is called with an invalidationevent which purges the cache for all users).
        // 3. When a cohort is deleted
        // -- (this is realized with an event observer where \core_cache\helper::purge_by_event
        // -- is called with an invalidationevent which purges the cache for all users).
        // 4. When a user is added to / removed from a category
        // -- (this is realized with an event observer which sets a user preference flag, followed by a check in
        // -- theme_boost_union_get_flavour_which_applies() which purges the cache for the affected user).
        'flavours' => [
                'mode' => \core_cache\store::MODE_SESSION,
                'simplekeys' => true,
                'simpledata' => false,
                'invalidationevents' => [
                    'theme_boost_union_flavours_resorted',
                    'theme_boost_union_flavours_created',
                    'theme_boost_union_flavours_edited',
                    'theme_boost_union_flavours_deleted',
                    'theme_boost_union_cohort_deleted',
                ],
        ],
        // This cache stores the touch icon files for iOS (which are uploaded in the Boost Union settings)
        // to avoid that the files have to be read from the filearea on every page load.
        'touchiconsios' => [
            'mode' => \core_cache\store::MODE_APPLICATION,
            'simplekeys' => true,
            'simpledata' => true,
            'staticacceleration' => true,
        ],
        // This cache stores the smart menus.
        'smartmenus' => [
                'mode' => \core_cache\store::MODE_APPLICATION,
                'simplekeys' => true,
                'simpledata' => false,
                'overrideclass' => '\theme_boost_union\cache\loader',
        ],
        // This cache stores the smart menus' menu items.
        'smartmenu_items' => [
                'mode' => \core_cache\store::MODE_APPLICATION,
                'simplekeys' => true,
                'simpledata' => false,
                'overrideclass' => '\theme_boost_union\cache\loader',
        ],
        // This cache stores the hook overrides.
        'hookoverrides' => [
                'mode' => \core_cache\store::MODE_APPLICATION,
                'simplekeys' => true,
                'simpledata' => false,
                'canuselocalstore' => true,
                'staticacceleration' => false,
        ],
];
