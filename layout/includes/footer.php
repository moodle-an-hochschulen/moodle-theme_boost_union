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
 * Theme Boost Union - Footer question mark button layout include.
 *
 * @package    theme_boost_union
 * @copyright  2023 Luca Bösch <luca.boesch@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$footersetting = get_config('theme_boost_union', 'enablefooterbutton');

// If the footer button is enabled.
$footerquestionmark = isset($footersetting) ? $footersetting : THEME_BOOST_UNION_SETTING_ENABLEFOOTER_ALL;
if ($footerquestionmark != THEME_BOOST_UNION_SETTING_ENABLEFOOTER_NONE) {
    // Add marker to show the footer button to templatecontext.
    $templatecontext['footerbutton'] = true;

    // If the "Suppress 'Chat to course participants' link" setting is not enabled.
    $footersuppresschatsetting = get_config('theme_boost_union', 'footersuppresschat');
    if (!isset($footersuppresschatsetting) || $footersuppresschatsetting != THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Add marker to show this link.
        $templatecontext['footershowchat'] = true;

        // Otherwise.
    } else {
        // Add marker to hide this link.
        $templatecontext['footershowchat'] = false;
    }

    // If the "Suppress 'Documentation for this page' link" setting is not enabled.
    $footersuppresshelpsetting = get_config('theme_boost_union', 'footersuppresshelp');
    if (!isset($footersuppresshelpsetting) || $footersuppresshelpsetting != THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Add marker to show this link.
        $templatecontext['footershowhelp'] = true;

        // Otherwise.
    } else {
        // Add marker to hide this link.
        $templatecontext['footershowhelp'] = false;
    }

    // If the "Suppress 'Services and support' link" setting is not enabled.
    $footersuppressservicessetting = get_config('theme_boost_union', 'footersuppressservices');
    if (!isset($footersuppressservicessetting) || $footersuppressservicessetting != THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Add marker to show this link.
        $templatecontext['footershowservices'] = true;

        // Otherwise.
    } else {
        // Add marker to hide this link.
        $templatecontext['footershowservices'] = false;
    }

    // If the "Suppress 'Contact site support' link" setting is not enabled.
    $footersuppresscontactsetting = get_config('theme_boost_union', 'footersuppresscontact');
    if (!isset($footersuppresscontactsetting) || $footersuppresscontactsetting != THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Add marker to show this link.
        $templatecontext['footershowcontact'] = true;

        // Otherwise.
    } else {
        // Add marker to hide this link.
        $templatecontext['footershowcontact'] = false;
    }

    // If any of the 'Documentation for this page', 'Services and support' or 'Contact site support' links are enabled.
    if (isset($templatecontext['footershowhelp']) && $templatecontext['footershowhelp'] == true ||
            isset($templatecontext['footershowservices']) && $templatecontext['footershowservices'] == true ||
            isset($templatecontext['footershowcontact']) && $templatecontext['footershowcontact'] == true) {
        // Add marker to show popover links.
        $templatecontext['footershowpopoverlinks'] = true;

        // Otherwise.
    } else {
        // Add marker to hide popover links.
        $templatecontext['footershowpopoverlinks'] = false;
    }

    // If the "Suppress Login info" setting is not enabled.
    $footersuppresslogininfosetting = get_config('theme_boost_union', 'footersuppresslogininfo');
    if (!isset($footersuppresslogininfosetting) || $footersuppresslogininfosetting != THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Add marker to show this link.
        $templatecontext['footershowlogininfo'] = true;

        // Otherwise.
    } else {
        // Add marker to hide this link.
        $templatecontext['footershowlogininfo'] = false;
    }

    // If the "Suppress 'Reset user tour on this page' link" setting is not enabled.
    $footersuppressusertoursetting = get_config('theme_boost_union', 'footersuppressusertour');
    if (!isset($footersuppressusertoursetting) || $footersuppressusertoursetting != THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Add marker to show this link.
        $templatecontext['footershowusertour'] = true;

        // Otherwise.
    } else {
        // Add marker to hide this link.
        $templatecontext['footershowusertour'] = false;
    }

    // If the "Suppress 'Powered by Moodle' link" setting is not enabled.
    $footersuppresspoweredsetting = get_config('theme_boost_union', 'footersuppresspowered');
    if (!isset($footersuppresspoweredsetting) || $footersuppresspoweredsetting != THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Add marker to show this link.
        $templatecontext['footershowpowered'] = true;

        // Otherwise.
    } else {
        // Add marker to hide this link.
        $templatecontext['footershowpowered'] = false;
    }

    // If the "Suppress icons in front of the footer links" setting is not enabled.
    $footersuppressfooterlinkiconssetting = get_config('theme_boost_union', 'footersuppressicons');
    if (!isset($footersuppressfooterlinkiconssetting) ||
        $footersuppressfooterlinkiconssetting != THEME_BOOST_UNION_SETTING_SELECT_YES) {
        // Add marker to show the icons.
        $templatecontext['suppressfooterlinkicons'] = false;

        // Otherwise.
    } else {
        // Add marker to hide the icons.
        $templatecontext['suppressfooterlinkicons'] = true;
    }
}

// If the accessibility button is enabled.
$enableaccessibilitysupportsetting = get_config('theme_boost_union', 'enableaccessibilitysupport');
$enableaccessibilitysupportfooterbuttonsetting = get_config('theme_boost_union', 'enableaccessibilitysupportfooterbutton');
if (isset($enableaccessibilitysupportsetting) &&
        $enableaccessibilitysupportsetting == THEME_BOOST_UNION_SETTING_SELECT_YES &&
        isset($enableaccessibilitysupportfooterbuttonsetting) &&
        $enableaccessibilitysupportfooterbuttonsetting == THEME_BOOST_UNION_SETTING_SELECT_YES) {

    // If user login is either not required or if the user is logged in.
    $allowaccessibilitysupportwithoutloginsetting = get_config('theme_boost_union', 'allowaccessibilitysupportwithoutlogin');
    if (!(isset($allowaccessibilitysupportwithoutloginsetting) &&
            $allowaccessibilitysupportwithoutloginsetting != THEME_BOOST_UNION_SETTING_SELECT_YES) ||
            (isloggedin() && !isguestuser())) {

        // Add marker to show this link.
        $templatecontext['accessibilitybutton'] = true;
        $templatecontext['accessibilitybuttonlink'] = new \core\url('/theme/boost_union/accessibility/support.php');
        $templatecontext['accessibilitybuttonsrlinktitle'] = theme_boost_union_get_accessibility_srlinktitle();
    }

    // Otherwise.
} else {
    // Add marker to hide this link.
    $templatecontext['accessibilitybutton'] = false;
}
