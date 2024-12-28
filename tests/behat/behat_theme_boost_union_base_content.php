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
 * Theme Boost Union - Custom Behat rules for the 'Content' settings
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../../../lib/behat/behat_base.php');

/**
 * Class behat_theme_boost_union_base_content
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union_base_content extends behat_base {
    /**
     * Open the aboutus page.
     *
     * @Given /^I am on aboutus page$/
     */
    public function i_am_on_aboutus_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/pages/aboutus.php']);
    }

    /**
     * Open the offers page.
     *
     * @Given /^I am on offers page$/
     */
    public function i_am_on_offers_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/pages/offers.php']);
    }

    /**
     * Open the imprint page.
     *
     * @Given /^I am on imprint page$/
     */
    public function i_am_on_imprint_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/pages/imprint.php']);
    }

    /**
     * Open the contact page.
     *
     * @Given /^I am on contact page$/
     */
    public function i_am_on_contact_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/pages/contact.php']);
    }

    /**
     * Open the help page.
     *
     * @Given /^I am on help page$/
     */
    public function i_am_on_help_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/pages/help.php']);
    }

    /**
     * Open the maintenance page.
     *
     * @Given /^I am on maintenance page$/
     */
    public function i_am_on_maintenance_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/pages/maintenance.php']);
    }

    /**
     * Open the accessibility declaration page.
     *
     * @Given /^I am on accessibilitydeclaration page$/
     */
    public function i_am_on_accessibilitydeclaration_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/accessibility/declaration.php']);
    }

    /**
     * Open the accessibility support page.
     *
     * @Given /^I am on accessibilitysupport page$/
     */
    public function i_am_on_accessibilitysupport_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/accessibility/support.php']);
    }

    /**
     * Open the page1 page.
     *
     * @Given /^I am on page1 page$/
     */
    public function i_am_on_page1_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/pages/page1.php']);
    }

    /**
     * Open the page2 page.
     *
     * @Given /^I am on page2 page$/
     */
    public function i_am_on_page2_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/pages/page2.php']);
    }

    /**
     * Open the page3 page.
     *
     * @Given /^I am on page3 page$/
     */
    public function i_am_on_page3_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/pages/page3.php']);
    }
}
