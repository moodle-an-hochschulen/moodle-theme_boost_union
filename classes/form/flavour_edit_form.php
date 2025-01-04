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
 * Theme Boost Union - Flavours edit form
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @copyright  based on code by bdecent gmbh <https://bdecent.de> in format_kickstart.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\form;

defined('MOODLE_INTERNAL') || die();

// Require forms library.
require_once($CFG->libdir.'/formslib.php');

// Require cohort library.
require_once($CFG->dirroot.'/cohort/lib.php');

/**
 * Flavours edit form.
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @copyright  based on code by bdecent gmbh <https://bdecent.de> in format_kickstart.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class flavour_edit_form extends \moodleform {

    /**
     * Define form elements.
     *
     * @throws \coding_exception
     */
    public function definition() {
        global $CFG, $OUTPUT;

        // Get an easier handler for the form.
        $mform = $this->_form;

        // Prepare yes-no option for multiple usage.
        $yesnooption = [false => get_string('no'), true => get_string('yes')];

        // Require and register the QuickForm colorpicker element.
        require_once($CFG->dirroot.'/theme/boost_union/classes/formelement/colorpicker.php');
        \MoodleQuickForm::registerElementType(
                'theme_boost_union_colorpicker',
                $CFG->dirroot.'/theme/boost_union/classes/formelement/colorpicker.php',
                '\theme_boost_union\formelement\colorpicker'
        );
        // Register validation rule for the QuickForm colorpicker element.
        \MoodleQuickForm::registerRule(
                'theme_boost_union_colorpicker_rule',
                null,
                '\theme_boost_union\formelement\colorpicker_rule',
                $CFG->dirroot.'/theme/boost_union/classes/formelement/colorpicker_rule.php'
        );

        // Add the flavour ID as hidden element.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        // Add general settings as header element.
        $mform->addElement('header', 'generalsettingsheader', get_string('flavoursgeneralsettings', 'theme_boost_union'));
        $mform->setExpanded('generalsettingsheader');

        // Add the title as input element.
        $mform->addElement('text', 'title', get_string('flavourstitle', 'theme_boost_union'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('required'), 'required');
        $mform->addHelpButton('title', 'flavourstitle', 'theme_boost_union');

        // Add the description title as editor element.
        $mform->addElement('editor', 'description', get_string('flavoursdescription', 'theme_boost_union'));
        $mform->setType('description', PARAM_CLEANHTML);
        $mform->addHelpButton('description', 'flavoursdescription', 'theme_boost_union');

        // Add look as header element.
        $mform->addElement('header', 'looksettingsheader', get_string('configtitlelook', 'theme_boost_union'));
        $mform->setExpanded('looksettingsheader');

        // Add logos heading.
        $context = new \stdClass();
        $context->title = get_string('logosheading', 'theme_boost_union', null, true);
        $mform->addElement(
                'html',
                // Wrapping the setting headings with a div with the ID "adminsettings" is not really correct as we will have
                // duplicate IDs on the page. But it is the only way to re-use the correct styling for the setting heading.
                // (And no, applying the ID to the form does not work either as we would trigger other unwanted stylings).
                '<div id="adminsettings">'.$OUTPUT->render_from_template('core_admin/setting_heading', $context).'</div>'
        );

        // Add logo as filemanager element.
        $mform->addElement('filemanager', 'flavours_look_logo',
                get_string('logo', 'admin'), null, [
                        'subdirs' => 0,
                        'maxfiles' => 1,
                        'accepted_types' => 'web_image',
                        'return_types' => FILE_INTERNAL,
                ]);
        $mform->addHelpButton('flavours_look_logo', 'flavourslogo', 'theme_boost_union');

        // Add logocompact as filemanager element.
        $mform->addElement('filemanager', 'flavours_look_logocompact',
                get_string('logocompact', 'admin'), null, [
                        'subdirs' => 0,
                        'maxfiles' => 1,
                        'accepted_types' => 'web_image',
                        'return_types' => FILE_INTERNAL,
                ]);
        $mform->addHelpButton('flavours_look_logocompact', 'flavourslogocompact', 'theme_boost_union');

        // Add favicon heading.
        $context = new \stdClass();
        $context->title = get_string('faviconheading', 'theme_boost_union', null, true);
        $mform->addElement(
                'html',
                '<div id="adminsettings">'.$OUTPUT->render_from_template('core_admin/setting_heading', $context).'</div>'
        );

        // Add favicon as filemanager element.
        $mform->addElement('filemanager', 'flavours_look_favicon',
                get_string('faviconsetting', 'theme_boost_union'), null, [
                        'subdirs' => 0,
                        'maxfiles' => 1,
                        'accepted_types' => 'image',
                        'return_types' => FILE_INTERNAL,
                ]);
        $mform->addHelpButton('flavours_look_favicon', 'flavoursfavicon', 'theme_boost_union');

        // Add backgroundimages heading.
        $context = new \stdClass();
        $context->title = get_string('backgroundimagesheading', 'theme_boost_union', null, true);
        $mform->addElement(
                'html',
                '<div id="adminsettings">'.$OUTPUT->render_from_template('core_admin/setting_heading', $context).'</div>'
        );

        // Add backgroundimage as filemanager element.
        $mform->addElement('filemanager', 'flavours_look_backgroundimage',
                get_string('backgroundimagesetting', 'theme_boost_union'), null, [
                        'subdirs' => 0,
                        'maxfiles' => 1,
                        'accepted_types' => 'web_image',
                        'return_types' => FILE_INTERNAL,
                ]);
        $mform->addHelpButton('flavours_look_backgroundimage', 'flavoursbackgroundimage', 'theme_boost_union');

        // Add background image position select element.
        $this->check_slasharguments_warning($mform);
        $backgroundimagepositionoptions = [
                THEME_BOOST_UNION_SETTING_SELECT_NOCHANGE =>
                        get_string('nochange', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_CENTER,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_TOP,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_CENTER_BOTTOM,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_TOP,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_CENTER,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_LEFT_BOTTOM,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_TOP,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_CENTER,
                THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM =>
                        THEME_BOOST_UNION_SETTING_IMAGEPOSITION_RIGHT_BOTTOM, ];
        $backgroundimagepositionselect = $mform->addElement(
                'select',
                'look_backgroundimagepos',
                get_string('flavoursbackgroundimageposition', 'theme_boost_union'),
                $backgroundimagepositionoptions,
                );
        $mform->setType('look_backgroundimagepos', PARAM_TEXT);
        $backgroundimagepositionselect->setSelected([THEME_BOOST_UNION_SETTING_SELECT_NOCHANGE]);
        $mform->addHelpButton('look_backgroundimagepos', 'flavoursbackgroundimageposition', 'theme_boost_union');

        // Add brand colors heading.
        $context = new \stdClass();
        $context->title = get_string('brandcolorsheading', 'theme_boost_union', null, true);
        $mform->addElement(
                'html',
                '<div id="adminsettings">'.$OUTPUT->render_from_template('core_admin/setting_heading', $context).'</div>'
        );

        // Add brandcolor as colorpicker element.
        $this->check_slasharguments_warning($mform);
        $mform->addElement(
                'theme_boost_union_colorpicker',
                'look_brandcolor',
                get_string('flavoursbrandcolor', 'theme_boost_union'),
                ['id' => 'colourpicker_brandcolour']);
        $mform->setType('look_brandcolor', PARAM_TEXT);
        $mform->addRule('look_brandcolor', get_string('validateerror', 'admin'), 'theme_boost_union_colorpicker_rule');
        $mform->addHelpButton('look_brandcolor', 'flavoursbrandcolor', 'theme_boost_union');

        // Add Bootstrap colors heading.
        $context = new \stdClass();
        $context->title = get_string('bootstrapcolorsheading', 'theme_boost_union', null, true);
        $mform->addElement(
                'html',
                '<div id="adminsettings">'.$OUTPUT->render_from_template('core_admin/setting_heading', $context).'</div>'
        );

        // Add Bootstrap color for 'success' as colorpicker element.
        $this->check_slasharguments_warning($mform);
        $mform->addElement(
                'theme_boost_union_colorpicker',
                'look_bootstrapcolorsuccess',
                get_string('flavoursbootstrapcolorsuccess', 'theme_boost_union'),
                ['id' => 'colourpicker_bootstrapcolorsuccess']);
        $mform->setType('look_bootstrapcolorsuccess', PARAM_TEXT);
        $mform->addRule('look_bootstrapcolorsuccess', get_string('validateerror', 'admin'), 'theme_boost_union_colorpicker_rule');
        $mform->addHelpButton('look_bootstrapcolorsuccess', 'flavoursbootstrapcolorsuccess', 'theme_boost_union');

        // Add Bootstrap color for 'info' as colorpicker element.
        $this->check_slasharguments_warning($mform);
        $mform->addElement(
                'theme_boost_union_colorpicker',
                'look_bootstrapcolorinfo',
                get_string('flavoursbootstrapcolorinfo', 'theme_boost_union'),
                ['id' => 'colourpicker_bootstrapcolorinfo']);
        $mform->setType('look_bootstrapcolorinfo', PARAM_TEXT);
        $mform->addRule('look_bootstrapcolorinfo', get_string('validateerror', 'admin'), 'theme_boost_union_colorpicker_rule');
        $mform->addHelpButton('look_bootstrapcolorinfo', 'flavoursbootstrapcolorinfo', 'theme_boost_union');

        // Add Bootstrap color for 'warning' as colorpicker element.
        $this->check_slasharguments_warning($mform);
        $mform->addElement(
                'theme_boost_union_colorpicker',
                'look_bootstrapcolorwarning',
                get_string('flavoursbootstrapcolorwarning', 'theme_boost_union'),
                ['id' => 'colourpicker-bootstrapcolorwarning']);
        $mform->setType('look_bootstrapcolorwarning', PARAM_TEXT);
        $mform->addRule('look_bootstrapcolorwarning', get_string('validateerror', 'admin'), 'theme_boost_union_colorpicker_rule');
        $mform->addHelpButton('look_bootstrapcolorwarning', 'flavoursbootstrapcolorwarning', 'theme_boost_union');

        // Add Bootstrap color for 'danger' as colorpicker element.
        $this->check_slasharguments_warning($mform);
        $mform->addElement(
                'theme_boost_union_colorpicker',
                'look_bootstrapcolordanger',
                get_string('flavoursbootstrapcolordanger', 'theme_boost_union'),
                ['id' => 'colourpicker-bbootstrapcolordanger']);
        $mform->setType('look_bootstrapcolordanger', PARAM_TEXT);
        $mform->addRule('look_bootstrapcolordanger', get_string('validateerror', 'admin'), 'theme_boost_union_colorpicker_rule');
        $mform->addHelpButton('look_bootstrapcolordanger', 'flavoursbootstrapcolordanger', 'theme_boost_union');

        // Add activity icon colors heading.
        $context = new \stdClass();
        $context->title = get_string('activityiconcolorsheading', 'theme_boost_union', null, true);
        $mform->addElement(
                'html',
                '<div id="adminsettings">'.$OUTPUT->render_from_template('core_admin/setting_heading', $context).'</div>'
        );

        // Define all activity icon purposes (without the 'other' purpose as this is not branded).
        $purposes = [MOD_PURPOSE_ADMINISTRATION,
                MOD_PURPOSE_ASSESSMENT,
                MOD_PURPOSE_COLLABORATION,
                MOD_PURPOSE_COMMUNICATION,
                MOD_PURPOSE_CONTENT,
                MOD_PURPOSE_INTERACTIVECONTENT,
                MOD_PURPOSE_INTERFACE];
        // Iterate over all purposes.
        foreach ($purposes as $purpose) {
            // Setting: Activity icon color.
            $this->check_slasharguments_warning($mform);
            $mform->addElement(
                    'theme_boost_union_colorpicker',
                    'look_aicol'.$purpose,
                    get_string('flavoursactivityiconcolor'.$purpose, 'theme_boost_union'),
                            ['id' => 'colourpicker-activityiconcolor'.$purpose]);
            $mform->setType('look_aicol'.$purpose, PARAM_TEXT);
            $mform->addRule('look_aicol'.$purpose, get_string('validateerror', 'admin'),
                    'theme_boost_union_colorpicker_rule');
            $mform->addHelpButton('look_aicol'.$purpose, 'flavoursactivityiconcolor'.$purpose, 'theme_boost_union');
        }

        // Add navbar heading.
        $context = new \stdClass();
        $context->title = get_string('navbarheading', 'theme_boost_union', null, true);
        $mform->addElement(
                'html',
                '<div id="adminsettings">'.$OUTPUT->render_from_template('core_admin/setting_heading', $context).'</div>'
        );

        // Add navbar color select element.
        $navbarcoloroptions = [
                THEME_BOOST_UNION_SETTING_SELECT_NOCHANGE =>
                        get_string('nochange', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_NAVBARCOLOR_LIGHT =>
                        get_string('navbarcolorsetting_light', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_NAVBARCOLOR_DARK =>
                        get_string('navbarcolorsetting_dark', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_NAVBARCOLOR_PRIMARYLIGHT =>
                        get_string('navbarcolorsetting_primarylight', 'theme_boost_union'),
                THEME_BOOST_UNION_SETTING_NAVBARCOLOR_PRIMARYDARK =>
                        get_string('navbarcolorsetting_primarydark', 'theme_boost_union'), ];
        $navbarcolorselect = $mform->addElement(
                'select',
                'look_navbarcolor',
                get_string('flavoursnavbarcolor', 'theme_boost_union'),
                $navbarcoloroptions,
                );
        $mform->setType('look_navbarcolor', PARAM_TEXT);
        $navbarcolorselect->setSelected([THEME_BOOST_UNION_SETTING_SELECT_NOCHANGE]);
        $mform->addHelpButton('look_navbarcolor', 'flavoursnavbarcolor', 'theme_boost_union');

        // Add SCSS heading.
        $context = new \stdClass();
        $context->title = get_string('scssheading', 'theme_boost_union', null, true);
        $mform->addElement(
                'html',
                '<div id="adminsettings">'.$OUTPUT->render_from_template('core_admin/setting_heading', $context).'</div>'
        );

        // Add custom initial SCSS as textarea element.
        $mform->addElement('textarea', 'look_rawscsspre', get_string('flavourscustomscsspre', 'theme_boost_union'), ['rows' => 8]);
        $mform->setType('title', PARAM_TEXT);
        $mform->addHelpButton('look_rawscsspre', 'flavourscustomscsspre', 'theme_boost_union');

        // Add custom SCSS as textarea element.
        $mform->addElement('textarea', 'look_rawscss', get_string('flavourscustomscss', 'theme_boost_union'), ['rows' => 8]);
        $mform->setType('title', PARAM_TEXT);
        $mform->addHelpButton('look_rawscss', 'flavourscustomscss', 'theme_boost_union');

        // Add apply-to-cohort as header element.
        $mform->addElement('header', 'applytocohortheader', get_string('flavoursapplytocohorts', 'theme_boost_union'));
        // Set the header to expanded if apply-to-cohort is already enabled.
        if (isset($this->_customdata['flavour']) && $this->_customdata['flavour']->applytocohorts == true) {
            $mform->setExpanded('applytocohortheader');
        }

        // Add apply-to-cohort as select element.
        $mform->addElement('select', 'applytocohorts', get_string('flavoursapplytocohorts', 'theme_boost_union'), $yesnooption);
        $mform->setDefault('applytocohorts', false);
        $mform->setType('applytocohorts', PARAM_BOOL);
        $mform->addHelpButton('applytocohorts', 'flavoursapplytocohorts', 'theme_boost_union');

        // Add cohort list as autocomplete field.
        $cohortdata = cohort_get_all_cohorts(0, 0);
        $cohortoptions = [];
        foreach ($cohortdata['cohorts'] as $cohort) {
            $cohortoptions[$cohort->id] = $cohort->name;
        }
        $mform->addElement('autocomplete', 'applytocohorts_ids', get_string('cohorts', 'cohort'), $cohortoptions,
                ['multiple' => true]);
        $mform->hideIf('applytocohorts_ids', 'applytocohorts', 'neq', 1);
        $mform->addHelpButton('applytocohorts_ids', 'flavoursapplytocohorts_ids', 'theme_boost_union');

        // Add apply-to-category as header element.
        $mform->addElement('header', 'applytocategoryheader', get_string('flavoursapplytocategories', 'theme_boost_union'));
        // Set the header to expanded if apply-to-category is already enabled.
        if (isset($this->_customdata['flavour']) && $this->_customdata['flavour']->applytocategories == true) {
            $mform->setExpanded('applytocategoryheader');
        }

        // Add apply-to-category as select element.
        $mform->addElement('select', 'applytocategories', get_string('flavoursapplytocategories', 'theme_boost_union'),
                $yesnooption);
        $mform->setDefault('applytocategories', false);
        $mform->setType('applytocategories', PARAM_BOOL);
        $mform->addHelpButton('applytocategories', 'flavoursapplytocategories', 'theme_boost_union');

        // Add category list as autocomplete field.
        $categoryoptions = \core_course_category::make_categories_list();
        $mform->addElement('autocomplete', 'applytocategories_ids', get_string('categories'), $categoryoptions,
                ['multiple' => true]);
        $mform->hideIf('applytocategories_ids', 'applytocategories', 'neq', 1);
        $mform->addHelpButton('applytocategories_ids', 'flavoursapplytocategories_ids', 'theme_boost_union');

        // Add include-subcategories as checkbox.
        $mform->addElement('advcheckbox', 'applytocategories_subcats',
                get_string('flavoursincludesubcategories', 'theme_boost_union'));
        $mform->setType('applytocategories_subcats', PARAM_BOOL);
        $mform->addHelpButton('applytocategories_subcats', 'flavoursincludesubcategories', 'theme_boost_union');
        $mform->hideIf('applytocategories_subcats', 'applytocategories', 'neq', 1);

        // Add the action buttons.
        $this->add_action_buttons();
    }

    /**
     * Theme Boost Union - Flavours edit form validation
     *
     * The routine to check the SCSS code is copied and modified from admin_setting_scsscode in /lib/adminlib.php.
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK (true allowed for backwards compatibility too).
     */
    public function validation($data, $files) {
        global $PAGE;

        $errors = [];

        // If we have any data.
        if (!empty($data)) {
            // Iterate over the SCSS fields.
            foreach (['look_rawscss', 'look_rawscsspre'] as $field) {
                // Check if the SCSS code can be compiled.
                if (!empty($data[$field])) {
                    $compiler = new \core_scss();
                    try {
                        if ($scssproperties = $PAGE->theme->get_scss_property()) {
                            $compiler->setImportPaths($scssproperties[0]);
                        }
                        $compiler->compile($data[$field]);
                    } catch (\ScssPhp\ScssPhp\Exception\ParserException $e) {
                        $errors[$field] = get_string('scssinvalid', 'admin', $e->getMessage());
                    // phpcs:disable Generic.CodeAnalysis.EmptyStatement.DetectedCatch
                    } catch (\ScssPhp\ScssPhp\Exception\CompilerException $e) {
                        // Silently ignore this - it could be a SCSS variable defined from somewhere
                        // else which we are not examining here.
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Helper function which adds a warning notification to the form if slasharguments is disabled.
     *
     * @param \MoodleQuickForm $mform The form object.
     * @return void
     */
    private function check_slasharguments_warning($mform) {
        global $CFG, $OUTPUT;

        // If slasharguments is disabled.
        if (empty($CFG->slasharguments)) {
            // Add a warning notification to the form.
            $slashargumentsurl = new \core\url('/admin/search.php', ['query' => 'slasharguments']);
            $notification = new \core\output\notification(
                    get_string('warningslashargumentsdisabled', 'theme_boost_union', ['url' => $slashargumentsurl]),
                    \core\output\notification::NOTIFY_WARNING);
            $notification->set_show_closebutton(false);
            $mform->addElement('html', $OUTPUT->render($notification));
        }
    }
}
