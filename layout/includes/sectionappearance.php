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
 * Theme Boost Union - Section appearance include
 *
 * @package   theme_boost_union
 * @copyright 2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Add the body classes which realize the section appearance settings (with course override support) via CSS.
// We have to do this here (and not within \theme_boost_union\util\section::apply_appearance()) as the body
// classes have to be set before the <body> tag is rendered by $OUTPUT->header(), while the course format content
// (and thus apply_appearance()) is rendered afterwards.
foreach (\theme_boost_union\util\section::get_body_classes($PAGE) as $sectionappearanceclass) {
    $extraclasses[] = $sectionappearanceclass;
}
