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
use backup;
use backup_controller;
use restore_controller;
use core\di;
use core\hook\manager;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/theme/boost_union/lib.php');
require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');

/**
 * Tests for backup and restore functionality.
 *
 * @package             theme_boost_union
 * @category            test
 * @coversDefaultClass  \restore_theme_boost_union_plugin
 * @author              Mikhail Golenkov <mikhailgolenkov@catalyst-au.net>
 * @license             http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class backup_restore_test extends advanced_testcase {

    /**
     * Test that course restore succeeds when theme_boost_union restore settings are not present in the plan.
     *
     * @covers ::process_courseoverride
     * @covers ::after_restore_course
     *
     * @return void
     */
    public function test_restore_without_theme_restore_settings(): void {
        global $USER;

        $this->resetAfterTest();
        $this->setAdminUser();

        di::set(
            manager::class,
            manager::phpunit_get_instance([])
        );

        $generator = $this->getDataGenerator();
        $sourcecourse = $generator->create_course();
        coursesettings::set_course_setting($sourcecourse->id, 'courseheaderenabled', THEME_BOOST_UNION_SETTING_SELECT_YES);

        $bc = new backup_controller(
            backup::TYPE_1COURSE,
            $sourcecourse->id,
            backup::FORMAT_MOODLE,
            backup::INTERACTIVE_NO,
            backup::MODE_IMPORT,
            $USER->id
        );
        $backupid = $bc->get_backupid();
        $bc->execute_plan();
        $bc->destroy();

        $targetcourse = $generator->create_course();
        $rc = new restore_controller(
            $backupid,
            $targetcourse->id,
            backup::INTERACTIVE_NO,
            backup::MODE_GENERAL,
            $USER->id,
            backup::TARGET_NEW_COURSE
        );

        $this->assertTrue($rc->execute_precheck());
        $this->assertFalse($rc->get_plan()->setting_exists('theme_boost_union_restore_course_header_settings'));

        $rc->execute_plan();
        $rc->destroy();

        $this->assertNotEmpty($targetcourse->id);
    }
}
