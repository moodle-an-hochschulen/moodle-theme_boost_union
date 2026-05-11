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
 * Boost Union recommendations check.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\check;

use core\check\result;

/**
 * Check if Boost Union recommendations need attention.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class recommendations extends \core\check\check {
    /**
     * A link to a place to action this.
     *
     * @return \action_link|null
     */
    public function get_action_link(): ?\action_link {
        // Compose and return a link to the recommendations overview page.
        return new \action_link(
            new \moodle_url('/theme/boost_union/recommendations/overview.php'),
            get_string('recommendations', 'theme_boost_union')
        );
    }

    /**
     * Return result.
     *
     * @return result
     */
    public function get_result(): result {
        // If there are any recommendations that need attention.
        if (\theme_boost_union\recommendation\manager::has_recommendations_needing_attention()) {
            // We show a warning.
            $status = result::WARNING;
            $summary = get_string('checkrecommendationswarning', 'theme_boost_union');

            // Otherwise.
        } else {
            // We are good.
            $status = result::OK;
            $summary = get_string('checkrecommendationsok', 'theme_boost_union');
        }

        // Compose details with a link to the recommendations overview page.
        $details = get_string('checkrecommendationsdetails', 'theme_boost_union', [
            'url' => (new \moodle_url('/theme/boost_union/recommendations/overview.php'))->out(),
        ]);

        // Return the result.
        return new result($status, $summary, $details);
    }
}
