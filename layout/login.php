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

$bodyattributes = $OUTPUT->body_attributes();
list($loginbackgroundimagetext, $loginbackgroundimagetextcolor) = theme_boost_union_get_loginbackgroundimage_text();

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'loginbackgroundimagetext' => $loginbackgroundimagetext,
    'loginbackgroundimagetextcolor' => $loginbackgroundimagetextcolor,
    'loginwrapperclass' => 'login-wrapper-'.get_config('theme_boost_union', 'loginformposition'),
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
