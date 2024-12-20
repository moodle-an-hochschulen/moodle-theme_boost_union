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
 * Theme Boost Union - Accessibility support form.
 *
 * @package    theme_boost_union
 * @copyright  2024 Katalin Lukacs Toth, ZHAW Zurich University of Applied Sciences <lukc@zhaw.ch>
 * @copyright  2024 Simon Schoenenberger, ZHAW Zurich University of Applied Sciences <scgo@zhaw.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\form;

defined('MOODLE_INTERNAL') || die;

// Require forms library.
require_once($CFG->libdir.'/formslib.php');

/**
 * Accessibility support form.
 *
 * This form is copied and modified from /user/classes/form/contactsitesupport_form.php.
 *
 * @package    theme_boost_union
 * @copyright  2024 Katalin Lukacs Toth, ZHAW Zurich University of Applied Sciences <lukc@zhaw.ch>
 * @copyright  2024 Simon Schoenenberger, ZHAW Zurich University of Applied Sciences <scgo@zhaw.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class accessibilitysupport_form extends \moodleform {

    /**
     * Define form elements.
     *
     * @throws \coding_exception
     */
    public function definition(): void {
        global $CFG, $USER;

        // Get an easier handler for the form.
        $mform = $this->_form;

        // Get required string.
        $strrequired = get_string('required');

        // Form field: Send anonymously.
        $sendanonymoussetting = get_config('theme_boost_union', 'allowanonymoussubmits');
        // If the user should be allowed to send the request anonymously.
        if (isset($sendanonymoussetting) && $sendanonymoussetting == THEME_BOOST_UNION_SETTING_SELECT_YES && isloggedin()
                && !isguestuser()) {
            // Checkbox to submit anonymously.
            $accessibilitysupportanonymous = get_string('accessibilitysupportanonymouscheckbox', 'theme_boost_union');
            $mform->addElement('advcheckbox', 'sendanonymous', $accessibilitysupportanonymous);
            $mform->setDefault('sendanonymous', 0);

            // Otherwise.
        } else {
            $mform->addElement('hidden', 'sendanonymous', 0);
            $mform->setType('sendanonymous', PARAM_BOOL);
        }

        // Form field: Name.
        $mform->addElement('text', 'name', get_string('name'));
        $mform->addRule('name', $strrequired, 'required', null, 'client');
        $mform->setType('name', PARAM_TEXT);
        $mform->hideIf('name', 'sendanonymous', 'checked');

        // Form field: Email.
        $mform->addElement('text', 'email', get_string('email'));
        $mform->addRule('email', get_string('missingemail'), 'required', null, 'client');
        $mform->setType('email', PARAM_EMAIL);
        $mform->hideIf('email', 'sendanonymous', 'checked');

        // Form field: Subject.
        $mform->addElement('text', 'subject', get_string('subject'));
        $mform->setType('subject', PARAM_TEXT);
        $mform->setDefault('subject', get_string('accessibilitysupportdefaultsubject', 'theme_boost_union'));

        // Form field: Message.
        $textareaoptions = ['rows' => 10];
        $mform->addElement('textarea', 'message', get_string('message'), $textareaoptions);
        $mform->addRule('message', $strrequired, 'required', null, 'client');
        $mform->setType('message', PARAM_TEXT);

        // Form field: Send technical information.
        $enablesendtechinfo = get_config('theme_boost_union', 'allowsendtechinfoalong');
        // If the user should be allowed to send technical information.
        if (isset($enablesendtechinfo) && $enablesendtechinfo == THEME_BOOST_UNION_SETTING_SELECT_YES) {
            // Checkbox to agree to the sending technical information, checked by default.
            $accessibilitysupporttechinfo = get_string('accessibilitysupporttechinfocheckbox', 'theme_boost_union');
            $mform->addElement('advcheckbox', 'sendtechinfo', $accessibilitysupporttechinfo);
            $mform->setDefault('sendtechinfo', 1);

            // Form field: Technical information.
            $textareaoptions = ['rows' => 10];
            $mform->addElement('textarea', 'techinfo', get_string('accessibilitysupporttechinfolabel', 'theme_boost_union'),
                    $textareaoptions);
            $mform->setType('techinfo', PARAM_TEXT);
            $mform->setDefault('techinfo', $this->get_technical_information());
            $mform->hideIf('techinfo', 'sendtechinfo', 'notchecked');

            // Otherwise.
        } else {
            $mform->addElement('hidden', 'sendtechinfo', 0);
            $mform->setType('sendtechinfo', PARAM_BOOL);
        }

        // If the user is logged in set name and email fields to the current user info.
        if (isloggedin() && !isguestuser()) {
            $mform->setDefault('name', fullname($USER));
            $mform->hardFreeze('name');

            $mform->setDefault('email', $USER->email);
            $mform->hardFreeze('email');
        }

        // If the admin enabled re-captcha on this page.
        $accessibilitysupportrecaptcha = get_config('theme_boost_union', 'accessibilitysupportrecaptcha');
        if (isset($accessibilitysupportrecaptcha) &&
                ($accessibilitysupportrecaptcha == THEME_BOOST_UNION_SETTING_SELECT_ALWAYS) ||
                ($accessibilitysupportrecaptcha == THEME_BOOST_UNION_SETTING_SELECT_ONLYGUESTSANDNONLOGGEDIN &&
                        (!isloggedin() || isguestuser()))) {
            if (!empty($CFG->recaptchapublickey) && !empty($CFG->recaptchaprivatekey)) {
                $mform->addElement('recaptcha', 'recaptcha_element', get_string('security_question', 'auth'));
                $mform->addHelpButton('recaptcha_element', 'recaptcha', 'auth');
                $mform->closeHeaderBefore('recaptcha_element');
            }
        }

        $this->add_action_buttons(true, get_string('submit'));
    }

    /**
     * Validate user supplied data on the accessibility support form.
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK (true allowed for backwards compatibility too).
     */
    public function validation($data, $files): array {

        // Call parent validation.
        $errors = parent::validation($data, $files);

        // Validate email.
        if (!validate_email($data['email'])) {
            $errors['email'] = get_string('invalidemail');
        }

        // Validate recaptcha.
        if ($this->_form->elementExists('recaptcha_element')) {
            $recaptchaelement = $this->_form->getElement('recaptcha_element');

            if (!empty($this->_form->_submitValues['g-recaptcha-response'])) {
                $response = $this->_form->_submitValues['g-recaptcha-response'];
                if (!$recaptchaelement->verify($response)) {
                    $errors['recaptcha_element'] = get_string('incorrectpleasetryagain', 'auth');
                }
            } else {
                $errors['recaptcha_element'] = get_string('missingrecaptchachallengefield');
            }
        }

        return $errors;
    }

    /**
     * Get the "id" attribute for this form.
     *
     * @return string
     */
    public function get_id(): string {
        return $this->_form->getAttribute('id');
    }

    /**
     * Get the referrer page for this form.
     *
     * @return string
     */
    public function get_referrer_page(): string {
        global $SESSION;

        // Get referrer page (if it exists).
        $referrer = $_SERVER['HTTP_REFERER'] ?? '';

        // If we have a referrer page and if it is not the form itself, store it in the session.
        // This is necessary to carry the referrer over to the form submission.
        if ($referrer && strpos($referrer, 'accessibility/support.php') === false) {
            $SESSION->boost_union_accessibility_pagereferrer = $referrer;
        }

        // Return the referrer.
        return $SESSION->boost_union_accessibility_pagereferrer ?? '';
    }

    /**
     * Render the technical information.
     *
     * @return string
     */
    public function get_technical_information(): string {
        global $PAGE;

        // Get renderer.
        $renderer = $PAGE->get_renderer('core');

        // Compose the technical information.
        $data = [
            'referrerpage' => $this->get_referrer_page(),
        ];
        $techinfo = $renderer->render_from_template('theme_boost_union/accessibility-support-email-techinfo', $data);

        // Return the technical information.
        return $techinfo;
    }

}
