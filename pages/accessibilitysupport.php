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
 * Theme Boost Union - Accessibility support page.
 *
 * @package    theme_boost_union
 * @copyright  2024 Katalin Lukacs Toth, ZHAW Zurich University of Applied Sciences <lukc@zhaw.ch>
 * @copyright  2024 Simon Schoenenberger, ZHAW Zurich University of Applied Sciences <scgo@zhaw.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Include config.php.
// @codingStandardsIgnoreStart
// Let codechecker ignore the next line because otherwise it would complain about a missing login check
// after requiring config.php which is really not needed.
require(__DIR__ . '/../../../config.php');
// @codingStandardsIgnoreEnd

// Require the necessary libraries.
require_once($CFG->dirroot.'/theme/boost_union/lib.php');
require_once($CFG->dirroot.'/theme/boost_union/locallib.php');

// Set page URL.
$PAGE->set_url('/theme/boost_union/pages/accessibilitysupport.php');

// Set page layout.
$PAGE->set_pagelayout('standard');

// Set page context.
$PAGE->set_context(context_system::instance());

// Add page name as body class.
$PAGE->add_body_class('theme_boost_union-accessibilitysupport');

// Get theme config.
$config = get_config('theme_boost_union');

// If the Accessibility supportpage is disabled, we just show a short friendly warning page and are done.
if ($config->enableaccessibilitysupport != THEME_BOOST_UNION_SETTING_SELECT_YES) {
    echo $OUTPUT->header();
    $notification = new \core\output\notification(get_string('accessibilitysupportdisabled', 'theme_boost_union'),
            \core\output\notification::NOTIFY_INFO);
    $notification->set_show_closebutton(false);
    echo $OUTPUT->render($notification);
    echo $OUTPUT->footer();
    die;
}

// If user login is required, we redirect to the login page.
if ($config->enableaccessibilitysupportwithoutlogin != THEME_BOOST_UNION_SETTING_SELECT_YES) {
    if (!isloggedin() || isguestuser()) {
        redirect(get_login_url());
    }
}

// Set page title.
$PAGE->set_title(theme_boost_union_get_staticpage_pagetitle('accessibilitysupport'));

$form = new \theme_boost_union\form\accessibilitysupport_form();

// Get the page where we came from.
$referrerpage = $form->get_referrer_page();
if (!$referrerpage) {
    $referrerpage = $CFG->wwwroot;
}

if ($form->is_cancelled()) {
    // Redirect to the previous page when the form was canceled.
    redirect($referrerpage);
} else if ($form->is_submitted() && $form->is_validated() && confirm_sesskey()) {
    $data = $form->get_data();

    // Remove the automatic system information field when the sendtechinfo field was unchecked by the user.
    if (!$data->sendtechinfo) {
        unset($data->techinfo);
    }

    $validuser = isloggedin() && !isguestuser();
    if ($validuser) {
        // Use the current user as sender when logged in.
        $from = $USER;
        $data->notloggedinuser = false;
    } else {
        // Use the noreply user as default sender when not logged in.
        $from = core_user::get_noreply_user();
        $data->notloggedinuser = true;
    }

    // Use a generic sender name and email when the user wants to be anonymous.
    if ($data->sendanonymous) {
        $from = core_user::get_noreply_user();
        $data->name = get_string('accessibilitysupportanonymoususer', 'theme_boost_union');
        $data->email = get_string('accessibilitysupportanonymousemail', 'theme_boost_union');
    }

    $subject = get_string('accessibilitysupportemailsubject', 'theme_boost_union', format_string($SITE->fullname));
    $renderer = $PAGE->get_renderer('core');
    // Render email body with form data.
    $message = $renderer->render_from_template('theme_boost_union/accessibility_support_email_body', $data);

    $accessibilityemail = get_config('theme_boost_union', 'accessibilitysupportemail');
    $accessibilityname = get_config('theme_boost_union', 'accessibilitysupportname');

    // Configure the noreply user as receiver when an accessibility support email was configured.
    if ($accessibilityemail) {
        // We need to create a dummy user record to send the mail.
        // The user record is only used to send the mail.
        $supportuser = core_user::get_noreply_user();
        $supportuser->email = $accessibilityemail;
        if ($accessibilityname) {
            $supportuser->firstname = $accessibilityname;
        }
    } else {
        // Use the default support user as receiver when no accessibility support email was configured.
        $supportuser = core_user::get_support_user();
    }

    // Show fallback page with contact information when the email could not be sent.
    if (!email_to_user($supportuser, $from, $subject, $message)) {
        $supportemail = $CFG->supportemail;
        $form->set_data($data);
        $templatectx = [
            'supportemail' => $validuser ? html_writer::link("mailto:{$supportemail}", $supportemail) : false,
            'supportform' => $form->render(),
        ];

        // Render the support fallback page.
        $formoutput = $renderer->render_from_template('theme_boost_union/accessibility_support_not_available', $templatectx);
    } else {
        // Redirect to the previous page and show message when the email was successfully sent.
        $level = \core\output\notification::NOTIFY_SUCCESS;
        redirect($referrerpage, get_string('accessibilitysupportmessagesent', 'theme_boost_union'), 3, $level);
    }
} else {
    // Render the form when it was not submitted yet.
    $formoutput = $form->render();
}

// Include form specific Javascript.
$PAGE->requires->js_call_amd('theme_boost_union/accessibilitysupportform', 'init', [
    [
        'formId' => $form->get_id(),
    ],
]);

// Start page output.
echo $OUTPUT->header();

// Show page heading.
echo $OUTPUT->heading(theme_boost_union_get_staticpage_pagetitle('accessibilitysupport'));

// Output Accessibility support page content.
echo format_text($config->accessibilitysupportcontent, FORMAT_MOODLE, ['trusted' => true, 'noclean' => true]);

// Output the form.
echo $formoutput;

// Finish page.
echo $OUTPUT->footer();
