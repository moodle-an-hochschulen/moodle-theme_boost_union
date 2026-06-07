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
 * Theme Boost Union Login - Login page layout.
 *
 * This layoutfile is based on theme/boost/layout/login.php
 *
 * Modifications compared to this layout file:
 * * Include footnote
 * * Include static pages
 * * Include accessibility pages
 * * Include info banners
 *
 * @package   theme_boost_union
 * @copyright 2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @copyright based on code from theme_boost by Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Get the body attributes.
$bodyattributes = $OUTPUT->body_attributes();

// Get the login page arrangement.
$loginarrangement = get_config('theme_boost_union', 'loginarrangement');

// If the login page arrangement is legacy.
if ($loginarrangement == THEME_BOOST_UNION_SETTING_LOGINARRANGEMENT_LEGACY) {
    // Set the flag for mustache.
    $usesidebysideloginarrangement = false;

    // We cannot use $PAGE->add_body_class here anymore as the output is already being generated,
    // so we need to add the class directly to the body attributes.
    $bodyattributes = preg_replace('/\bclass="([^"]*)"/', 'class="$1 theme_boost_union-loginarrangement-legacy"', $bodyattributes);

    // Compose the login wrapper class.
    $loginformposition = get_config('theme_boost_union', 'loginformposition');
    $loginwrapperclass = 'login-wrapper-' . $loginformposition;

    // Otherwise, it should be side by side.
} else {
    // Set the flag.
    $usesidebysideloginarrangement = true;

    // We cannot use $PAGE->add_body_class here anymore as the output is already being generated,
    // so we need to add the class directly to the body attributes.
    $bodyattributes =
            preg_replace('/\bclass="([^"]*)"/', 'class="$1 theme_boost_union-loginarrangement-sidebyside"', $bodyattributes);
}

// Get the login background image text and color.
[$loginbackgroundimagetext, $loginbackgroundimagetextcolor] = theme_boost_union_get_loginbackgroundimage_text();

// Left-panel instructions. Only set when the admin has defined custom instructions;
// the template falls back to the default welcome content when this is empty/null.
$leftinstructions = !empty($CFG->auth_instructions)
    ? format_text($CFG->auth_instructions, FORMAT_MOODLE, ['context' => context_system::instance()])
    : null;

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'leftinstructions' => $leftinstructions,
    'loginbackgroundimagetext' => $loginbackgroundimagetext,
    'loginbackgroundimagetextcolor' => $loginbackgroundimagetextcolor,
    'usesidebysideloginarrangement' => $usesidebysideloginarrangement,
    'loginwrapperclass' => (!empty($loginwrapperclass)) ? $loginwrapperclass : '',
    'logincontainerclass' =>
            (get_config('theme_boost_union', 'loginformtransparency') == THEME_BOOST_UNION_SETTING_SELECT_YES) ?
                    'login-container-80t' : '',
];

// Include the template content for the footnote.
require_once(__DIR__ . '/includes/footnote.php');

// Include the template content for the static pages.
require_once(__DIR__ . '/includes/staticpages.php');

// Include the template content for the accessibility pages.
require_once(__DIR__ . '/includes/accessibilitypages.php');

// Include the template content for the footer button.
require_once(__DIR__ . '/includes/footer.php');

// Include the template content for the info banners.
require_once(__DIR__ . '/includes/infobanners.php');

// Render login.mustache from theme_boost (which is overridden in theme_boost_union).
echo $OUTPUT->render_from_template('theme_boost/login', $templatecontext);
