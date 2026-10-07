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
 * Behat navigation-related step definition overrides for the Boost Union theme.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.
// For that reason, we can't even rely on $CFG->admin being available here.

require_once(__DIR__ . '/../../../../lib/tests/behat/behat_navigation.php');
require_once(__DIR__ . '/../../../boost/tests/behat/behat_theme_boost_behat_navigation.php');

use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;

/**
 * Navigation-related step definition overrides for the Boost Union theme.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union_behat_navigation extends behat_theme_boost_behat_navigation {
    /**
     * Clicks link with specified id|title|alt|text in the primary navigation.
     *
     * This step overrides the core step as Boost Union renders the primary navigation with the React-based
     * core/primarymoremenu template (introduced in Moodle 5.3 with MDL-89294) only as long as no smart menu is configured.
     * As soon as a smart menu is configured, Boost Union renders the primary navigation with the core/moremenu template
     * whose container does not carry the primarynav-navbar class which the core step looks for.
     *
     * @throws ElementNotFoundException Thrown by behat_base::find
     * @param string $link
     */
    public function i_select_from_primary_navigation(string $link) {
        // If the React-based primary navigation is rendered, use the core step.
        $reactnav = $this->getSession()->getPage()->find('css', '.primary-navigation .navigation.primarynav-navbar');
        if ($reactnav !== null) {
            parent::i_select_from_primary_navigation($link);
            return;
        }

        // Otherwise, click the link in the core/moremenu-based primary navigation.
        $this->execute(
            'behat_general::i_click_on_in_the',
            [$link, 'link', '.primary-navigation .moremenu.navigation', 'css_element']
        );
    }
}
