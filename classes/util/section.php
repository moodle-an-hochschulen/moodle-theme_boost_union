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

namespace theme_boost_union\util;

use theme_boost_union\coursesettings;

/**
 * Theme Boost Union - Section utility class.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class section {
    /**
     * Apply the section appearance settings (with course override support) to the exported
     * template data of the course format's content output class.
     *
     * This is called from the theme's course format renderer overrides just before the
     * course main page is rendered from the core_courseformat/local/content template.
     *
     * It applies two settings: the appearance of section 0 (the general section) and the
     * appearance of all following sections (sections 1+).
     *
     * @param \stdClass $data The exported template data of the content output class.
     * @param \moodle_page $page The current page.
     */
    public static function apply_appearance(\stdClass $data, \moodle_page $page): void {
        // If a single section page (course/section.php) is shown, do not touch the data.
        // The section appearance settings only control the course main page.
        if (!empty($data->singlesection)) {
            return;
        }

        // If there are no sections in the data for whatever reason, do not touch the data.
        if (empty($data->sections) || !is_array($data->sections)) {
            return;
        }

        // Get the configured appearance of section 0 and of sections 1+ (each with course override support).
        $sectionzeroappearance = coursesettings::get_config_with_course_override('sectionzeroappearance');
        $sectiononeplusappearance = coursesettings::get_config_with_course_override('sectiononeplusappearance');

        // Get Moodle core's 'expandsection' URL parameter which is meant to force-expand the given section
        // (see core_courseformat\output\local\content\section::is_section_collapsed()).
        $expandsection = $page->url->get_param('expandsection');

        // Apply the configured collapse state to each section, picking the setting which is responsible for it
        // (section 0 has its own setting, all other sections share the sections 1+ setting).
        foreach ($data->sections as $section) {
            $appearance = ((int) $section->num === 0) ? $sectionzeroappearance : $sectiononeplusappearance;
            self::apply_collapse_state($section, $appearance, $expandsection);
        }

        // Handle the section-0-only 'Hide section 0 entirely' appearance, but only if the user is not editing
        // the course. Otherwise the section's activities would be unreachable for teachers.
        if (
            $sectionzeroappearance == THEME_BOOST_UNION_SETTING_SECTIONAPPEARANCE_HIDDEN &&
            !$page->user_is_editing()
        ) {
            self::hide_sectionzero($data);
        }
    }

    /**
     * Apply the configured collapse state of the given appearance to a single section's exported template data.
     *
     * The 'collapsed by default' and 'without collapsing' appearances only set the collapse state here; visually
     * hiding the collapse toggle and keeping the content expanded is realized via a body class
     * (see self::get_body_classes()) and CSS (see the 'Settings: Feel -> Course' section in scss/boost_union/post.scss).
     *
     * @param \stdClass $section The exported template data of a single section.
     * @param string|null $appearance The configured appearance which applies to the section.
     * @param string|null $expandsection The value of Moodle core's 'expandsection' URL parameter.
     */
    private static function apply_collapse_state(\stdClass $section, $appearance, $expandsection): void {
        switch ($appearance) {
            // Show the section with collapsing, collapsed by default:
            // Force the collapsed state on every page load, regardless of the user's section preference.
            // The user can still expand the section with the chevron, but this choice will not persist.
            case THEME_BOOST_UNION_SETTING_SECTIONAPPEARANCE_COLLAPSIBLECOLLAPSED:
                // Respect the 'expandsection' URL parameter for the particular section it targets.
                if ($expandsection === null || (int) $expandsection !== (int) $section->num) {
                    $section->contentcollapsed = true;
                }
                break;

            // Show the section without collapsing:
            // Make sure that the section is rendered in its expanded state.
            case THEME_BOOST_UNION_SETTING_SECTIONAPPEARANCE_NOTCOLLAPSIBLE:
                $section->contentcollapsed = false;
                break;
        }
    }

    /**
     * Remove section 0 from the exported sections (realizing the 'Hide section 0 entirely' appearance).
     *
     * @param \stdClass $data The exported template data of the content output class.
     */
    private static function hide_sectionzero(\stdClass $data): void {
        // Search section 0 within the exported sections.
        foreach ($data->sections as $key => $section) {
            if ((int) $section->num !== 0) {
                continue;
            }

            // Remember whether section 0 carried the 'Collapse all / Expand all' control and remove the section.
            $hadcollapsemenu = !empty($section->collapsemenu);
            unset($data->sections[$key]);
            $data->sections = array_values($data->sections);

            // If section 0 carried the 'Collapse all / Expand all' control, move this control to the (new) first
            // section to keep it available on the course page.
            if ($hadcollapsemenu && count($data->sections) > 0) {
                $data->sections[0]->collapsemenu = true;
            }

            break;
        }
    }

    /**
     * Get the body classes which realize the section appearance settings (with course override support)
     * via CSS (see the 'Settings: Feel -> Course' section in scss/boost_union/post.scss).
     *
     * This is called from the theme's layout file. It has to be called there (and not from
     * self::apply_appearance()) as the body classes have to be set before the <body> tag is rendered
     * by $OUTPUT->header(), while the course format content (and thus apply_appearance()) is rendered
     * afterwards.
     *
     * @param \moodle_page $page The current page.
     * @return array The body classes to add.
     */
    public static function get_body_classes(\moodle_page $page): array {
        // Initialize the return array.
        $classes = [];

        // If we are not on a course page or if we are on the frontpage course, do nothing.
        if ($page->pagelayout != 'course' || $page->course->id == SITEID) {
            return $classes;
        }

        // If the course does not use one of the supported course formats, do nothing.
        if (!coursesettings::is_courseformat_supported_by_sectionfeature($page->course->format)) {
            return $classes;
        }

        // Get the configured appearance of section 0 (with course override support).
        $sectionzeroappearance = coursesettings::get_config_with_course_override('sectionzeroappearance');

        switch ($sectionzeroappearance) {
            // Show section 0 without collapsing:
            // Add a body class which hides section 0's collapse toggle and keeps its content expanded.
            case THEME_BOOST_UNION_SETTING_SECTIONAPPEARANCE_NOTCOLLAPSIBLE:
                $classes[] = 'theme-boost-union-sectionzeronotcollapsible';
                break;

            // Hide section 0 entirely:
            // Add a body class which hides section 0 in the course index (on the course main page itself,
            // section 0 is already removed server-side in self::apply_appearance()). But only if the user is
            // not editing the course, as otherwise its content would be unreachable for editing.
            case THEME_BOOST_UNION_SETTING_SECTIONAPPEARANCE_HIDDEN:
                if (!$page->user_is_editing()) {
                    $classes[] = 'theme-boost-union-hidesectionzero';
                }
                break;
        }

        // Get the configured appearance of sections 1+ (with course override support).
        $sectiononeplusappearance = coursesettings::get_config_with_course_override('sectiononeplusappearance');

        // Show sections 1+ without collapsing:
        // Add a body class which hides the collapse toggles of sections 1+ and keeps their content expanded.
        if ($sectiononeplusappearance == THEME_BOOST_UNION_SETTING_SECTIONAPPEARANCE_NOTCOLLAPSIBLE) {
            $classes[] = 'theme-boost-union-sectiononeplusnotcollapsible';
        }

        // Return.
        return $classes;
    }
}
