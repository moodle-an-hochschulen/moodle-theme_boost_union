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

use ScssPhp\ScssPhp\Exception\CompilerException;
use ScssPhp\ScssPhp\Exception\ParserException;

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

        // Add favicon as filemanager element.
        $mform->addElement('filemanager', 'flavours_look_favicon',
                get_string('faviconsetting', 'theme_boost_union'), null, [
                        'subdirs' => 0,
                        'maxfiles' => 1,
                        'accepted_types' => 'image',
                        'return_types' => FILE_INTERNAL,
                ]);
        $mform->addHelpButton('flavours_look_favicon', 'flavoursfavicon', 'theme_boost_union');

        // Add backgroundimage as filemanager element.
        $mform->addElement('filemanager', 'flavours_look_backgroundimage',
                get_string('backgroundimagesetting', 'theme_boost_union'), null, [
                        'subdirs' => 0,
                        'maxfiles' => 1,
                        'accepted_types' => 'web_image',
                        'return_types' => FILE_INTERNAL,
                ]);
        $mform->addHelpButton('flavours_look_backgroundimage', 'flavoursbackgroundimage', 'theme_boost_union');

        // Create brand colors heading.
        $context = new \stdClass();
        $context->title = get_string('brandcolorsheading', 'theme_boost_union', null, true);
        $mform->addElement(
                'html',
                '<div id="adminsettings">'. $OUTPUT->render_from_template('core_admin/setting_heading', $context), '</div>'
        );

        // Register custom colourpicker.
        \MoodleQuickForm::registerElementType('boost_union_colourpicker',
            $CFG->dirroot . '/theme/boost_union/classes/form/colourpicker_form_element.php',
            'theme_boost_union\form\theme_boost_union_colourpicker_form_element');
        // Register validation rule for colourpicker.
        \MoodleQuickForm::registerRule('theme_boost_union_colourpicker_rule',
            null,
            'theme_boost_union\form\theme_boost_union_colourpicker_rule',
            $CFG->dirroot . '/theme/boost_union/classes/form/colourpicker_form_element.php');

        // Add brandcolour as colourpicker element.
        $mform->addElement(
                'boost_union_colourpicker',
                'brandcolor',
                get_string('brandcolor', 'theme_boost'),
                [
                        'id' => 'colourpicker_brandcolour',
                ]);
        $mform->setDefault('brandcolor', '');
        $mform->addHelpButton('brandcolor', 'flavoursbrandcolour', 'theme_boost_union');
        // Add validation rule.
        $mform->addRule('brandcolor', get_string('validateerror', 'admin'), 'theme_boost_union_colourpicker_rule');

        // Create Bootstrap colors heading.
        $context = new \stdClass();
        $context->title = get_string('bootstrapcolorsheading', 'theme_boost_union', null, true);
        $mform->addElement(
            'html',
            '<div id="adminsettings">'. $OUTPUT->render_from_template('core_admin/setting_heading', $context), '</div>'
        );

        // Add Bootstrap color for 'success' as colourpicker element.
        $mform->addElement(
            'boost_union_colourpicker',
            'bootstrapcolorsuccess',
            get_string('bootstrapcolorsuccesssetting', 'theme_boost_union'),
            [
                'id' => 'colourpicker-bootstrapcolorsuccess',
            ]);
        $mform->setDefault('bootstrapcolorsuccess', '');
        $mform->addHelpButton('bootstrapcolorsuccess', 'flavoursbootstrapcolorsuccess', 'theme_boost_union');
        // Add validation rule.
        $mform->addRule('bootstrapcolorsuccess', get_string('validateerror', 'admin'), 'theme_boost_union_colourpicker_rule');

        // Add Bootstrap color for 'info' as colourpicker element.
        $mform->addElement(
            'boost_union_colourpicker',
            'bootstrapcolorinfo',
            get_string('bootstrapcolorinfosetting', 'theme_boost_union'),
            [
                'id' => 'colourpicker-bootstrapcolorinfo',
            ]);
        $mform->setDefault('bootstrapcolorinfo', '');
        $mform->addHelpButton('bootstrapcolorinfo', 'flavoursbootstrapcolorinfo', 'theme_boost_union');
        // Add validation rule.
        $mform->addRule('bootstrapcolorinfo', get_string('validateerror', 'admin'), 'theme_boost_union_colourpicker_rule');

        // Add Bootstrap color for 'warning' as colourpicker element.
        $mform->addElement(
            'boost_union_colourpicker',
            'bootstrapcolorwarning',
            get_string('bootstrapcolorwarningsetting', 'theme_boost_union'),
            [
                'id' => 'colourpicker-bootstrapcolorwarning',
            ]);
        $mform->addHelpButton('bootstrapcolorwarning', 'flavoursbootstrapcolorwarning', 'theme_boost_union');
        // Add validation rule.
        $mform->addRule('bootstrapcolorwarning', get_string('validateerror', 'admin'), 'theme_boost_union_colourpicker_rule');

        // Add Bootstrap color for 'danger' as colourpicker element.
        $mform->addElement(
            'boost_union_colourpicker',
            'bootstrapcolordanger',
            get_string('bootstrapcolordangersetting', 'theme_boost_union'),
            [
                'id' => 'colourpicker-bbootstrapcolordanger',
            ]);
        $mform->setDefault('bootstrapcolordanger', '');
        $mform->addHelpButton('bootstrapcolordanger', 'flavoursbootstrapcolordanger', 'theme_boost_union');
        // Add validation rule.
        $mform->addRule('bootstrapcolordanger', get_string('validateerror', 'admin'), 'theme_boost_union_colourpicker_rule');

        // Add custom css as textarea element.
        $mform->addElement('textarea', 'look_rawscss', get_string('rawscss', 'theme_boost'), array('rows' => 15));
        $mform->setType('title', PARAM_TEXT);
        $mform->addHelpButton('look_rawscss', 'flavourscustomcss', 'theme_boost_union');

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
     * @package theme_boost_union
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK (true allowed for backwards compatibility too).
     */
    public function validation($data, $files) {
        global $PAGE;

        $errors = [];

        if (!empty($data['look_rawscss']) && !empty($data)) {

            $scss = new \core_scss();
            try {
                if ($scssproperties = $PAGE->theme->get_scss_property()) {
                    $scss->setImportPaths($scssproperties[0]);
                }
                $scss->compile($data['look_rawscss']);
            } catch (ParserException $e) {
                $errors['look_rawscss'] = get_string('scssinvalid', 'admin', $e->getMessage());
            } catch (CompilerException $e) {
                $errors['look_rawscss'] = get_string('scssinvalid', 'admin', $e->getMessage());
            }
            $scss = null;
            unset($scss);
        }
        return $errors;
    }
}
