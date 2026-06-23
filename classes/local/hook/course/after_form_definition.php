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

        // Require libraries.
        require_once($CFG->dirroot . '/theme/boost_union/lib.php');
        require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

        // If a theme other than Boost Union or a child theme of it is active, return directly.
        // This is necessary as the callback is called regardless of the active theme.
        if (theme_boost_union_is_active_theme() != true) {
            return;
        }

        // Get the context and the (effective) course format.
        $context = $hook->formwrapper->get_context();
        $course = $hook->formwrapper->get_course();
        $courseformat = coursesettings::get_effective_course_format($course);

        // Check which course settings form sections the user is allowed to use:
        // The course header settings require a dedicated capability and
        // must not be excluded for the given course format.
        $showcourseheadersettings = has_capability('theme/boost_union:overridecourseheaderincourse', $context) &&
                !coursesettings::is_courseformat_excluded_from_courseheaderfeature($courseformat);
        // The section settings require a dedicated capability and
        // are only supported by particular course formats.
        $showsectionssettings = has_capability('theme/boost_union:overridesectionincourse', $context) &&
                coursesettings::is_courseformat_supported_by_sectionfeature($courseformat);

        // If the user is not allowed to use any of the sections, we do nothing.
        if (!$showcourseheadersettings && !$showsectionssettings) {
            return;
        }

        // Get the form object.
        $mform = $hook->mform;

        // Constants for the places where to insert our elements before.
        define('THEME_BOOST_UNION_COURSESETTINGS_COURSEHEADER_INSERTBEFORE', 'courseformathdr');
        define('THEME_BOOST_UNION_COURSESETTINGS_COURSEIMAGES_INSERTBEFORE1', 'theme_boost_union_course_courseheaderhdr');
        define('THEME_BOOST_UNION_COURSESETTINGS_COURSEIMAGES_INSERTBEFORE2', 'courseformathdr');
        define('THEME_BOOST_UNION_COURSESETTINGS_SECTIONS_INSERTBEFORE', 'filehdr');

        // Get the course override settings which we handle, split by the form section they belong to.
        $courseheadersettings = coursesettings::get_course_settings_config_by_formsection('courseheader');
        $sectionssettings = coursesettings::get_course_settings_config_by_formsection('sections');

        // Part 1: 'Course header' section.

        // Check if we should show this section at all.
        $showcourseheader = false;
        if ($showcourseheadersettings) {
            foreach ($courseheadersettings as $setting => $config) {
                $overridesetting = get_config('theme_boost_union', $setting . '_courseoverride');
                if ($overridesetting) {
                    $showcourseheader = true;
                    break; // We only need to know if at least one is enabled.
                }
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

            // Iterate over the settings and add form elements.
            foreach ($courseheadersettings as $setting => $config) {
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
        }

        // Part 2: 'Course images' section.

        // Check if we should show this section at all.
        $showcourseimages = false;
        if ($showcourseheadersettings && coursesettings::courseheaderimage_is_enabled()) {
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

        // Part 3: 'Sections' section.

        // Check if we should show this section at all.
        $showsections = false;
        if ($showsectionssettings) {
            foreach ($sectionssettings as $setting => $config) {
                $overridesetting = get_config('theme_boost_union', $setting . '_courseoverride');
                if ($overridesetting) {
                    $showsections = true;
                    break; // We only need to know if at least one is enabled.
                }
            }
        }

        // Add sections section, if any setting of it can be overridden.
        if ($showsections) {
            // If insert-before header exists for the sections section, insert our elements before it.
            $sectionsinsertbefore = null;
            if ($mform->elementExists(THEME_BOOST_UNION_COURSESETTINGS_SECTIONS_INSERTBEFORE)) {
                $sectionsinsertbefore = THEME_BOOST_UNION_COURSESETTINGS_SECTIONS_INSERTBEFORE;
            }

            // Header: Sections.
            $sectionsheader = $mform->createElement(
                'header',
                'theme_boost_union_course_sectionshdr',
                get_string('sectionsheading', 'theme_boost_union')
            );
            if ($sectionsinsertbefore) {
                $mform->insertElementBefore($sectionsheader, $sectionsinsertbefore);
            } else {
                $mform->addElement($sectionsheader);
            }

            // Iterate over the settings and add form elements.
            foreach ($sectionssettings as $setting => $config) {
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
                    if ($sectionsinsertbefore) {
                        $mform->insertElementBefore(${'element' . $setting}, $sectionsinsertbefore);
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

                    // Hide the setting when the course is configured to show one section per page, as the section
                    // appearance settings only affect courses which show all sections on one page.
                    $mform->hideIf($formfieldname, 'coursedisplay', 'eq', COURSE_DISPLAY_MULTIPAGE);
                }
            }

            // Add a notice which is shown instead of the settings above when the course is configured to show one
            // section per page. In this case, all settings above are hidden and the section would be empty otherwise.
            // We use a 'static' element here (and not a 'warning' element which would be shown across the full width of
            // the form without an empty label column on the left) as only 'static' elements can be shown / hidden by the
            // hideIf rule below. The empty label column of the 'static' element is removed via the CSS utility class
            // 'theme-boost-union-fullwidth-formelement' instead (see the 'General styles' section in
            // scss/boost_union/post.scss), which is added to the element with the 'parentclass' attribute below.
            $sectionsnotice = $mform->createElement(
                'static',
                'theme_boost_union_course_sectionsnosettings',
                '',
                \html_writer::div(get_string('sectionsnosettingsnotice', 'theme_boost_union'), 'alert alert-info')
            );
            $sectionsnotice->updateAttributes(['parentclass' => 'theme-boost-union-fullwidth-formelement']);
            if ($sectionsinsertbefore) {
                $mform->insertElementBefore($sectionsnotice, $sectionsinsertbefore);
            } else {
                $mform->addElement($sectionsnotice);
            }

            // Show this notice only when the course is configured to show one section per page (i.e. hide it when the
            // course shows all sections on one page, in which case the settings above are available instead).
            $mform->hideIf('theme_boost_union_course_sectionsnosettings', 'coursedisplay', 'eq', COURSE_DISPLAY_SINGLEPAGE);
        }
    }
}
