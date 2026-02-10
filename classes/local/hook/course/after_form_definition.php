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
 * Theme Boost Union - Course form definition hook callback.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\local\hook\course;

use theme_boost_union\coursesettings;

/**
 * Course form definition hook callback class.
 *
 * @package    theme_boost_union
 * @copyright  2025 Alexander Bias, ssystems GmbH <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class after_form_definition {
    /**
     * Add custom fields to the course edit form.
     *
     * @param \core_course\hook\after_form_definition $hook The hook data
     */
    public static function callback(\core_course\hook\after_form_definition $hook): void {
        global $CFG;

        // Require local library.
        require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

        // If a theme other than Boost Union or a child theme of it is active, return directly.
        // This is necessary as the callback is called regardless of the active theme.
        if (theme_boost_union_is_active_theme() != true) {
            return;
        }

        // If the user does not have the capability to override the course header settings in this course, we do nothing.
        $context = $hook->formwrapper->get_context();
        if (!has_capability('theme/boost_union:overridecourseheaderincourse', $context)) {
            return;
        }

        // If this course format is excluded from the course header feature, we do nothing.
        $course = $hook->formwrapper->get_course();
        $courseformat = coursesettings::get_effective_course_format($course);
        if (coursesettings::is_courseformat_excluded_from_courseheaderfeature($courseformat)) {
            return;
        }

        // Get the form object.
        $mform = $hook->mform;

        // Constants for the places where to insert our elements before.
        define('THEME_BOOST_UNION_COURSESETTINGS_COURSEHEADER_INSERTBEFORE', 'courseformathdr');
        define('THEME_BOOST_UNION_COURSESETTINGS_COURSEIMAGES_INSERTBEFORE1', 'theme_boost_union_course_courseheaderhdr');
        define('THEME_BOOST_UNION_COURSESETTINGS_COURSEIMAGES_INSERTBEFORE2', 'courseformathdr');

        // Get the course override settings which we handle.
        $coursesettings = coursesettings::get_course_settings_config();

        // Part 1: 'Course header' section.

        // Check if we should show this section at all.
        $showcourseheader = false;
        foreach ($coursesettings as $setting => $config) {
            $overridesetting = get_config('theme_boost_union', $setting . '_courseoverride');
            if ($overridesetting) {
                $showcourseheader = true;
                break; // We only need to know if at least one is enabled.
            }
        }

        // Add course header section, if any setting of it can be overridden.
        if ($showcourseheader) {
            // If insert-before header exists for course header, insert our elements before it.
            $courseheaderinsertbefore = null;
            if ($mform->elementExists(THEME_BOOST_UNION_COURSESETTINGS_COURSEHEADER_INSERTBEFORE)) {
                $courseheaderinsertbefore = THEME_BOOST_UNION_COURSESETTINGS_COURSEHEADER_INSERTBEFORE;
            }

            // Header: Course header.
            $courseheaderheader = $mform->createElement(
                'header',
                'theme_boost_union_course_courseheaderhdr',
                get_string('courseheaderheading', 'theme_boost_union')
            );
            if ($courseheaderinsertbefore) {
                $mform->insertElementBefore($courseheaderheader, $courseheaderinsertbefore);
            } else {
                $mform->addElement($courseheaderheader);
            }
        }

        // Iterate over the settings and add form elements.
        foreach ($coursesettings as $setting => $config) {
            $overridesetting = get_config('theme_boost_union', $setting . '_courseoverride');
            if ($overridesetting && isset($config['options']) && is_array($config['options'])) {
                $formfieldname = 'theme_boost_union_' . $setting;
                // Create a new element variable for each setting to avoid issues in the loop as it is passed by reference
                // to insertElementBefore().
                ${'element' . $setting} = $mform->createElement(
                    'select',
                    $formfieldname,
                    get_string($setting, 'theme_boost_union'),
                    $config['options']
                );
                if ($courseheaderinsertbefore) {
                    $mform->insertElementBefore(${'element' . $setting}, $courseheaderinsertbefore);
                } else {
                    $mform->addElement(${'element' . $setting});
                }

                // Add help button if specified.
                if (isset($config['helpbutton']) && $config['helpbutton']) {
                    $mform->addHelpButton($formfieldname, $setting, 'theme_boost_union');
                }

                // Add hide_if rule if specified.
                if (isset($config['hide_if']) && is_array($config['hide_if'])) {
                    $mform->hideIf(
                        $formfieldname,
                        $config['hide_if']['element'],
                        $config['hide_if']['condition'],
                        $config['hide_if']['value']
                    );
                }
            }
        }

        // Part 2: 'Course images' section.

        // Check if we should show this section at all.
        $showcourseimages = false;
        if (coursesettings::courseheaderimage_is_enabled()) {
            $showcourseimages = true;
        }

        // Add course images section, if needed.
        if ($showcourseimages) {
            // If insert-before header exists for course images, insert our elements before it.
            $courseheaderinsertbefore = null;
            if ($mform->elementExists(THEME_BOOST_UNION_COURSESETTINGS_COURSEIMAGES_INSERTBEFORE1)) {
                $courseheaderinsertbefore = THEME_BOOST_UNION_COURSESETTINGS_COURSEIMAGES_INSERTBEFORE1;
            } else if ($mform->elementExists(THEME_BOOST_UNION_COURSESETTINGS_COURSEIMAGES_INSERTBEFORE2)) {
                $courseheaderinsertbefore = THEME_BOOST_UNION_COURSESETTINGS_COURSEIMAGES_INSERTBEFORE2;
            }

            // Header: Course images.
            $courseimagesheader = $mform->createElement(
                'header',
                'theme_boost_union_course_courseimageshdr',
                get_string('courseimagesheading', 'theme_boost_union')
            );
            if ($courseheaderinsertbefore) {
                $mform->insertElementBefore($courseimagesheader, $courseheaderinsertbefore);
            } else {
                $mform->addElement($courseimagesheader);
            }

            // Fist of all, move the core course overview files filemanager to this section if it exists and the insert-before
            // header was found as well.
            if ($mform->elementExists('overviewfiles_filemanager') && $courseheaderinsertbefore) {
                // Get the existing element.
                $overviewfileselement = $mform->getElement('overviewfiles_filemanager');

                // Remove it from its current position.
                $mform->removeElement('overviewfiles_filemanager');

                // Re-add it in the course images section.
                $mform->insertElementBefore($overviewfileselement, $courseheaderinsertbefore);
            }

            // Get course header image file manager options.
            $courseheaderimageoptions = coursesettings::get_courseheaderimage_options();

            // Add course header image file manager.
            $courseheaderimagefilemanager = $mform->createElement(
                'filemanager',
                'theme_boost_union_courseheaderimage_filemanager',
                get_string('courseheaderimage', 'theme_boost_union'),
                null,
                $courseheaderimageoptions
            );
            if ($courseheaderinsertbefore) {
                $mform->insertElementBefore($courseheaderimagefilemanager, $courseheaderinsertbefore);
            } else {
                $mform->addElement($courseheaderimagefilemanager);
            }

            // Add help button for course header image, based on the setting.
            if (coursesettings::courseheaderimage_uses_global_image()) {
                $mform->addHelpButton(
                    'theme_boost_union_courseheaderimage_filemanager',
                    'courseheaderimageplusfallback',
                    'theme_boost_union'
                );
            } else if (
                get_config('theme_boost_union', 'courseheaderimagerequirement') ==
                    THEME_BOOST_UNION_SETTING_COURSEHEADERIMAGEREQUIREMENT_ENHANCEDWITHOUTIMAGE
            ) {
                $mform->addHelpButton(
                    'theme_boost_union_courseheaderimage_filemanager',
                    'courseheaderimagenoimage',
                    'theme_boost_union'
                );
            } else {
                $mform->addHelpButton(
                    'theme_boost_union_courseheaderimage_filemanager',
                    'courseheaderimagenofallback',
                    'theme_boost_union'
                );
            }

            // Add hide_if rule.
            $hideifconfig = coursesettings::get_hide_if_with_global_default('courseheaderenabled');
            $mform->hideIf(
                'theme_boost_union_courseheaderimage_filemanager',
                $hideifconfig['element'],
                $hideifconfig['condition'],
                $hideifconfig['value']
            );
        }
    }
}
