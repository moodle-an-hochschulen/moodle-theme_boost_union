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

/**
 * Theme Boost Union - Tests for the accessibility support form.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class accessibilitysupport_form_test extends advanced_testcase {
    /**
     * Data provider for test_get_referrer_page().
     *
     * The placeholder WWWROOT is replaced with $CFG->wwwroot in the test
     * (as $CFG is not available in data providers).
     *
     * @return array
     */
    public static function referrer_provider(): array {
        return [
            'No referrer' => [null, ''],
            'Local referrer' => ['WWWROOT/course/view.php?id=5', 'WWWROOT/course/view.php?id=5'],
            'Local site home as referrer' => ['WWWROOT/', 'WWWROOT/'],
            'The form itself as referrer' => ['WWWROOT/theme/boost_union/accessibility/support.php', ''],
            'External referrer' => ['https://attacker.example/phishing.html', ''],
            'External referrer on a lookalike host' => ['WWWROOT.attacker.example/phishing.html', ''],
            'External referrer with wwwroot in the path' => ['https://attacker.example/WWWROOT/', ''],
        ];
    }

    /**
     * Test that only referrers which are located on this Moodle site are accepted as referrer page
     * (and as redirect target) of the accessibility support form.
     *
     * @covers \theme_boost_union\form\accessibilitysupport_form::get_referrer_page
     * @dataProvider referrer_provider
     *
     * @param string|null $referrer The referrer which is sent by the browser (or null if no referrer is sent).
     * @param string $expected The expected referrer page.
     * @return void
     */
    public function test_get_referrer_page(?string $referrer, string $expected): void {
        global $CFG, $SESSION;

        require_once($CFG->dirroot . '/theme/boost_union/lib.php');

        $this->resetAfterTest();

        // Replace the wwwroot placeholders.
        $expected = str_replace('WWWROOT', $CFG->wwwroot, $expected);

        // Make sure that there isn't any referrer stored in the session yet.
        unset($SESSION->boost_union_accessibility_pagereferrer);

        // Set the referrer which is sent by the browser.
        if ($referrer !== null) {
            $_SERVER['HTTP_REFERER'] = str_replace('WWWROOT', $CFG->wwwroot, $referrer);
        } else {
            unset($_SERVER['HTTP_REFERER']);
        }

        // Get the referrer page from the form.
        $form = new \theme_boost_union\form\accessibilitysupport_form();
        $this->assertSame($expected, $form->get_referrer_page());

        // And check that nothing else than the expected referrer page was stored in the session.
        $this->assertSame($expected, $SESSION->boost_union_accessibility_pagereferrer ?? '');
    }

    /**
     * Test that a local referrer which was stored in the session is not overwritten by an external referrer.
     *
     * @covers \theme_boost_union\form\accessibilitysupport_form::get_referrer_page
     *
     * @return void
     */
    public function test_get_referrer_page_keeps_stored_referrer(): void {
        global $CFG, $SESSION;

        require_once($CFG->dirroot . '/theme/boost_union/lib.php');

        $this->resetAfterTest();

        // Open the form with a local referrer.
        $localreferrer = $CFG->wwwroot . '/course/view.php?id=5';
        $_SERVER['HTTP_REFERER'] = $localreferrer;
        $form = new \theme_boost_union\form\accessibilitysupport_form();
        $this->assertSame($localreferrer, $form->get_referrer_page());

        // Open the form again with an external referrer.
        $_SERVER['HTTP_REFERER'] = 'https://attacker.example/phishing.html';
        $form = new \theme_boost_union\form\accessibilitysupport_form();
        $this->assertSame($localreferrer, $form->get_referrer_page());
        $this->assertSame($localreferrer, $SESSION->boost_union_accessibility_pagereferrer);
    }
}
