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

namespace theme_boost_union\task;

/**
 * Class refresh_snippets_from_community_repository
 *
 * @package    theme_boost_union
 * @copyright  2025 André Menrath <andre.menrath@uni-graz.at>, University of Graz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class refresh_snippets_from_community_repository extends \core\task\scheduled_task {
    /**
     * Return localised task name.
     *
     * @return string
     */
    public function get_name() {
        return get_string('task_refreshcommunitysnippets', 'theme_boost_union');
    }

    /**
     * Execute scheduled task.
     *
     * @return boolean
     */
    public function execute() {
        // Simply reset the theme cache.
        theme_boost_union_refresh_community_sippets();

        // Return true, just to keep the Moodle scheduler happy.
        return true;
    }
}
