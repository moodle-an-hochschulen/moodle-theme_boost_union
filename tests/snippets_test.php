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

namespace theme_boost_union;

use advanced_testcase;
use theme_boost_union\snippets;

/**
 * Theme Boost Union - Tests for (built-in) SCSS Snippets and snippets class.
 *
 * @package    theme_boost_union
 * @category   test
 * @coversDefaultClass \theme_boost_union\snippets
 * @copyright  André Menrath <andre.menrath@uni-graz.at>, 2024 University of Graz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class snippets_test extends advanced_testcase {
    /**
     * Test the parsing of the snippets meta information from the files top comment.
     *
     * @covers ::get_snippet_meta
     * @covers ::get_snippet_meta_from_file
     *
     * @return void
     */
    public function test_parse_snippet_header(): void {
        $meta = snippets::get_snippet_meta('debugging_footer.scss', 'theme_boost_union');
        $this->assertEquals('Debugging footer', $meta->title);
        $this->assertEquals('global', $meta->scope);
        $this->assertEquals('devsonly', $meta->goal);
        $this->assertEquals(
            "By default, the performance footer in Moodle aligns with the content width and integrates in the page layout in a " .
            "suboptimal way. This snippet changes the performance footer to be full-width and 'below the fold'. Additionally, it " .
            "slightly improves the content styling in the performance footer as well.",
            $meta->description
        );
    }

    /**
     * Register (new) local builtin snippets in the database.
     *
     * @covers ::add_builtin_snippets
     *
     * @return void
     */
    public function test_register_builtin_snippets(): void {
        global $DB;

        $this->resetAfterTest();

        $countinitial = $DB->count_records(
            'theme_boost_union_snippets',
            ['source' => 'theme_boost_union']
        );

        // Run function that parses builtin snippets.
        snippets::add_builtin_snippets();

        $count = $DB->count_records(
            'theme_boost_union_snippets',
            ['source' => 'theme_boost_union']
        );

        // Since we have added no snippets in between the count should still be the same.
        $this->assertEquals($count, $countinitial);

        // Delete one snippet from the database.
        $DB->delete_records(
            'theme_boost_union_snippets',
            ['source' => 'theme_boost_union', 'name' => 'debugging_footer.scss']
        );

        $count = $DB->count_records(
            'theme_boost_union_snippets',
            ['source' => 'theme_boost_union']
        );

        $this->assertEquals($countinitial - 1, $count);

        // Run function that parses and registers new builtin snippets.
        snippets::add_builtin_snippets();

        // The builtin snippet which was just deleted from the db should be registered again.
        $snippet = $DB->get_record(
            'theme_boost_union_snippets',
            ['source' => 'theme_boost_union', 'name' => 'debugging_footer.scss']
        );

        $this->assertNotEmpty($snippet);
    }

    /**
     * Test parsing a snippet which is not present/readable.
     *
     * @covers ::get_snippet_meta
     * @covers ::get_snippet_meta_from_file
     *
     * @return void
     */
    public function test_parse_snippet_header_of_non_existing_snippet(): void {
        $meta = snippets::get_snippet_meta('this_snippet_does_not_exist.scss', 'theme_boost_union');
        $this->assertEquals(null, $meta);
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
        $this->assertStringContainsString('Snippet Title: Debugging footer', $scss);
    }

    /**
     * Test looking for visual preview image file of snippet.

     * @covers ::get_snippet_preview_url
     *
     * @return void
     */
    public function test_lookup_visual_preview_file(): void {
        global $CFG;

        $file = snippets::get_snippet_preview_url('debugging_footer.scss', 'theme_boost_union');

        // Check that indeed the present webp preview for this snippet is returned.
        $this->assertEquals($CFG->wwwroot . '/theme/boost_union/snippets/builtin/debugging_footer.webp', $file);
    }
}
