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
 * Theme Boost Union - Tests for SCSS Snippets.
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
        $meta = snippets::get_snippet_meta('visual-depth.scss', 'theme_boost_union');
        $this->assertEquals('Visual depth', $meta->title);
        $this->assertEquals('global', $meta->scope);
        $this->assertEquals('eyecandy', $meta->goal);
        $this->assertEquals(
            'A less flat design than is intended in the Boost theme. Realised by ' .
            'box-shadows on a number of page elements and a colour gradient on the page background.',
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
            ['source' => 'theme_boost_union', 'path' => 'visual-depth.scss']
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
            ['source' => 'theme_boost_union', 'path' => 'visual-depth.scss']
        );

        $this->assertNotEmpty($snippet);
    }

    /**
     * Test the parsing snippet which is not present/readable.
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
     * Test the parsing snippet which is not present/readable.

     * @covers ::get_enabled_snippet_scss
     *
     * @return void
     */
    public function test_fetch_enabled_snippets_scss(): void {
        global $DB;

        $this->resetAfterTest();

        $scss = snippets::get_enabled_snippet_scss();

        // No snippets are enabled by default, so the scss should be empty.
        $this->assertEquals('', $scss);

        // Enable a snippet directly via the DB.
        $snippet = $DB->get_record(
            'theme_boost_union_snippets',
            ['source' => 'theme_boost_union', 'path' => 'visual-depth.scss']
        );
        $snippet->enabled = 1;
        $DB->update_record('theme_boost_union_snippets', $snippet);

        $scss = snippets::get_enabled_snippet_scss();

        // Verify that the Snippets SCSS content is now queried.
        $this->assertNotEquals('', $scss);
        $this->assertStringContainsString('Snippet Title: Visual depth', $scss);
    }
}
