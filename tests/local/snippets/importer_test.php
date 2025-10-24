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

use theme_boost_union\local\snippets\importer;

/**
 * Tests for Boost Union
 *
 * @package    theme_boost_union
 * @category   test
 * @coversDefaultClass \theme_boost_union\local\snippets\importer
 * @copyright  2025 André Menrath <andre.menrath@uni-graz.at>, University of Graz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class importer_test extends \advanced_testcase {
    /**
     * Register (new) local builtin snippets in the database.
     *
     * @covers ::add_builtin_snippets
     *
     * @return void
     */
    public function test_import_builtin_snippets(): void {
        global $DB;

        $this->resetAfterTest();

        // Run function that parses builtin snippets.
        importer::import_builtin_snippets();

        $count = $DB->count_records(
            'theme_boost_union_snippets',
            ['source' => 'theme_boost_union']
        );

        // Since we have added no snippets in between the count should still be the same.
        $this->assertEquals($count, 6);

        // Delete one snippet from the database.
        $DB->delete_records(
            'theme_boost_union_snippets',
            ['source' => 'theme_boost_union', 'name' => 'debugging_footer.scss']
        );

        $count = $DB->count_records(
            'theme_boost_union_snippets',
            ['source' => 'theme_boost_union']
        );

        $this->assertEquals(5, $count);

        // Run function that parses and registers new builtin snippets.
        importer::import_builtin_snippets();

        // The builtin snippet which was just deleted from the db should be registered again.
        $snippet = $DB->get_record(
            'theme_boost_union_snippets',
            ['source' => 'theme_boost_union', 'name' => 'debugging_footer.scss']
        );

        $this->assertNotEmpty($snippet);
    }
}
