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
 * Theme Boost Union - Custom Behat rules for the 'Look' settings
 *
 * @package    theme_boost_union
 * @copyright  2023 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

/**
 * Class behat_theme_boost_union_base_look
 *
 * @package    theme_boost_union
 * @copyright  2023 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union_base_look extends behat_base {
    /**
     * Open the local login page.
     *
     * @Given /^I am on local login page$/
     */
    public function i_am_on_local_login_page() {
        $this->execute('behat_general::i_visit', ['/theme/boost_union/locallogin.php']);
    }

    /**
     * Configure the organisations which are offered by the Moodle WAYF service of auth_shibboleth.
     *
     * The organisations are given as table with the columns idp and name. This step is needed as the organisations
     * are stored with one organisation per line and as a value with line breaks can't be given in a Behat table cell.
     *
     * @Given the following Shibboleth organisations are configured:
     * @param \Behat\Gherkin\Node\TableNode $table The organisations.
     */
    public function the_following_shibboleth_organisations_are_configured(\Behat\Gherkin\Node\TableNode $table) {
        $lines = [];
        foreach ($table->getHash() as $row) {
            $lines[] = $row['idp'] . ', ' . $row['name'];
        }
        set_config('organization_selection', implode("\n", $lines), 'auth_shibboleth');
    }

    /**
     * Set the SAML domain cookie which remembers the Shibboleth IdPs which the user has used before.
     *
     * The IdPs are given as comma-separated list of IdP identifiers, the most recently used IdP comes last.
     * The cookie value is composed in the same way as auth_shibboleth does it in generate_cookie_value().
     * The browser must have visited a page of the site before this step is used.
     *
     * @Given the Shibboleth IdP cookie remembers the IdPs :idps
     * @param string $idps The comma-separated list of IdP identifiers.
     */
    public function the_shibboleth_idp_cookie_remembers_the_idps($idps) {
        $idparray = array_map('trim', explode(',', $idps));
        $value = implode(' ', array_map('base64_encode', $idparray));
        $this->getSession()->setCookie('_saml_idp', $value);
    }

    /**
     * Set a browser cookie to a raw value.
     *
     * The browser must have visited a page of the site before this step is used.
     *
     * @Given the browser cookie :name is set to :value
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie.
     */
    public function the_browser_cookie_is_set_to($name, $value) {
        $this->getSession()->setCookie($name, $value);
    }
}
