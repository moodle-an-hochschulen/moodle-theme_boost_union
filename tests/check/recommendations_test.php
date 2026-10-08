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

namespace theme_boost_union\check;

use advanced_testcase;
use core\check\result;

/**
 * Theme Boost Union - Tests for the recommendations check.
 *
 * @package    theme_boost_union
 * @category   test
 * @coversDefaultClass \theme_boost_union\check\recommendations
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class recommendations_test extends advanced_testcase {
    /**
     * Set up a recommendation which needs attention.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();

        $this->resetAfterTest();

        // Disable slash arguments to make sure that the slasharguments recommendation needs attention.
        set_config('slasharguments', 0);

        // Reset the cached status of the slasharguments recommendation so that it is re-evaluated.
        \theme_boost_union\recommendation\check\slasharguments::reset_cache();
    }

    /**
     * Test that the check reports a warning if a recommendation needs attention and Boost Union is the active theme.
     *
     * @covers ::get_result
     *
     * @return void
     */
    public function test_get_result_boostunion_active(): void {
        set_config('theme', 'boost_union');

        $result = (new recommendations())->get_result();

        $this->assertEquals(result::WARNING, $result->get_status());
    }

    /**
     * Test that the check reports just an info if a recommendation needs attention, but Boost Union is not the active theme.
     *
     * @covers ::get_result
     *
     * @return void
     */
    public function test_get_result_boostunion_not_active(): void {
        set_config('theme', 'boost');

        $result = (new recommendations())->get_result();

        $this->assertEquals(result::INFO, $result->get_status());
    }

    /**
     * Test that the check reports OK if no recommendation needs attention.
     *
     * @covers ::get_result
     *
     * @return void
     */
    public function test_get_result_no_attention_needed(): void {
        set_config('theme', 'boost_union');

        // Mute all recommendations to make sure that none of them needs attention, regardless of the test environment.
        foreach (\theme_boost_union\recommendation\manager::get_recommendations() as $recommendation) {
            \theme_boost_union\recommendation\manager::set_recommendation_muted($recommendation->get_id(), true);
        }

        $result = (new recommendations())->get_result();

        $this->assertEquals(result::OK, $result->get_status());
    }
}
