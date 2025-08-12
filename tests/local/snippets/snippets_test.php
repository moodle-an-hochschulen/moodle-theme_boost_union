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

namespace theme_boost_union\local\snippets;

use advanced_testcase;
use theme_boost_union\local\snippets\snippets;

/**
 * Theme Boost Union - Tests for (built-in) SCSS Snippets and snippets class.
 *
 * @package    theme_boost_union
 * @category   test
 * @coversDefaultClass \theme_boost_union\local\snippets\snippets
 * @copyright  André Menrath <andre.menrath@uni-graz.at>, 2024 University of Graz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class snippets_test extends advanced_testcase {
    /**
     * Test looking for visual preview image file of snippet.

     * @covers ::get_snippet_preview_url
     *
     * @return void
     */
    public function test_get_snippet_preview_url(): void {
        global $CFG;

        $file = snippets::get_snippet_preview_url('debugging_footer.scss', source::BUILT_IN);

        // Check that indeed the present webp preview for this snippet is returned.
        $this->assertInstanceOf(\core\url::class, $file);
        $this->assertEquals($CFG->wwwroot . '/theme/boost_union/snippets/builtin/debugging_footer.webp', $file);
    }

    /**
     * Test parsing a snippet which is present/readable.

     * @covers ::get_enabled_snippet_scss
     *
     * @return void
     */
    public function test_fetch_enabled_snippets_scss(): void {
        global $DB;

        $this->resetAfterTest();

        // Get the SCSS content of all enabled snippets.
        $scss = snippets::get_enabled_snippet_scss();

        // Builtin snippets are disabled by default as a whole, so the scss should be empty.
        $this->assertEquals('', $scss);

        // Enable builtin snippets directly via the DB.
        set_config('enablebuiltinsnippets', 'yes', 'theme_boost_union');

        // Automatically run updated callback.
        theme_boost_union_populate_builtin_snippets();

        // Get the SCSS content of all enabled snippets again.
        $scss = snippets::get_enabled_snippet_scss();

        // No particular builtin snippet is enabled by default, so the scss should still be empty.
        $this->assertEquals('', $scss);

        // Enable a builtin snippet directly via the DB.
        $snippet = $DB->get_record(
            'theme_boost_union_snippets',
            ['source' => 'theme_boost_union', 'name' => 'debugging_footer.scss']
        );
        $snippet->enabled = 1;
        $DB->update_record('theme_boost_union_snippets', $snippet);

        // Get the SCSS content of all enabled snippets again.
        $scss = snippets::get_enabled_snippet_scss();

        // Verify that the Snippets SCSS content is now queried.
        $this->assertNotEquals('', $scss);
        $this->assertStringContainsString('.footer-content-debugging', $scss);
    }
}
