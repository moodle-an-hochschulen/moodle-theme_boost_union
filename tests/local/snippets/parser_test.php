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
 * @coversDefaultClass \theme_boost_union\local\snippets\parser
 * @copyright  André Menrath <andre.menrath@uni-graz.at>, 2024 University of Graz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class parser_test extends advanced_testcase {
    /**
     * Test the parsing of the snippets meta information from the files top comment.
     *
     * @covers ::get_snippet_meta
     * @covers ::get_snippet_meta_from_file
     *
     * @return void
     */
    public function test_parse_snippet_header(): void {
        $filecontent = file_get_contents(__DIR__ . './../../fixtures/snippets/snippet_usagenote.scss');
        $meta = parser::get_snippet_meta($filecontent);
        $this->assertEquals('Snippet with usage note', $meta['Snippet Title']);
        $this->assertEquals('global', $meta['Scope']);
        $this->assertEquals('eyecandy', $meta['Goal']);
        $this->assertEquals(
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut",
            $meta['Description']
        );
    }

    /**
     * Test the stripping the snippets meta information (top file comment) of the scss file.
     *
     * @covers ::strip_file_comment
     *
     * @return void
     */
    public function test_strip_file_comment(): void {
        $filecontent = file_get_contents(__DIR__ . './../../fixtures/snippets/snippet_usagenote.scss');
        $scss = parser::strip_file_comment($filecontent);
        $this->assertStringNotContainsString('Snippet Title', $scss);
        $this->assertStringContainsString('#page-header', $scss);
    }
}
