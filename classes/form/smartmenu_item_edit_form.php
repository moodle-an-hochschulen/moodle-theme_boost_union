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
 * Theme Boost Union - Smart menu item edit form
 *
 * @package    theme_boost_union
 * @copyright  2023 bdecent GmbH <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\form;

defined('MOODLE_INTERNAL') || die();

// Require forms library.
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/cohort/lib.php');

use theme_boost_union\smartmenu_item;
use theme_boost_union\smartmenu;

/**
 * Smart menu items edit form.
 */
class smartmenu_item_edit_form extends \moodleform {

    /**
     * Define form elements.
     *
     * @throws \coding_exception
     */
    public function definition() {
        global $DB, $PAGE, $CFG;

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

        // Get an easier handler for the form.
        $mform = $this->_form;

        // Add the smart menu item ID as hidden element.
        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);

        // Add the smart menu ID as hidden element (and set it to 0 if it is not given).
        $mform->addElement('hidden', 'menu', 0);
        $mform->setType('menu', PARAM_INT);
        $menuid = (isset($this->_customdata['menu'])) ? $this->_customdata['menu'] : 0;
        $mform->setDefault('menu', $menuid);

        // Add general settings as header element.
        $mform->addElement('header', 'generalsettingsheader',
                get_string('smartmenusgeneralsectionheader', 'theme_boost_union'));
        $mform->setExpanded('generalsettingsheader');

        // Add the title as input element.
        $mform->addElement('text', 'title', get_string('smartmenusmenuitemtitle', 'theme_boost_union'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('required'), 'required');
        $mform->addHelpButton('title', 'smartmenusmenuitemtitle', 'theme_boost_union');

        // Add structure as header element.
        $mform->addElement('header', 'structureheader',
                get_string('smartmenusmenuitemstructureheader', 'theme_boost_union'));
        $mform->setExpanded('structureheader');

        // Add the menu item type as select element.
        $typesoptions = smartmenu_item::get_types();
        $mform->addElement('select', 'type', get_string('smartmenusmenuitemtype', 'theme_boost_union'), $typesoptions);
        $mform->setDefault('type', smartmenu_item::TYPESTATIC);
        $mform->setType('type', PARAM_INT);
        $mform->addRule('type', get_string('required'), 'required');
        $mform->addHelpButton('type', 'smartmenusmenuitemtype', 'theme_boost_union');

        // Add menu item URL (for the static menu item type) as input element.
        $mform->addElement('text', 'url', get_string('smartmenusmenuitemurl', 'theme_boost_union'));
        $mform->setType('url', PARAM_URL);
        $mform->hideIf('url', 'type', 'neq', smartmenu_item::TYPESTATIC);
        $mform->addHelpButton('url', 'smartmenusmenuitemurl', 'theme_boost_union');

        // Add mode as select element.
        $modeoptions = smartmenu_item::get_mode_options();
        $mform->addElement('select', 'mode', get_string('smartmenusmenuitemmode', 'theme_boost_union'), $modeoptions);
        $mform->setDefault('mode', smartmenu_item::MODE_INLINE);
        $mform->setType('mode', PARAM_INT);
        $mform->addHelpButton('mode', 'smartmenusmenuitemmode', 'theme_boost_union');

        // Add category (for the dynamic courses menu item type) as autocomplete element.
        $categoriesoptions = \core_course_category::make_categories_list();
        $catwidget = $mform->addElement('autocomplete', 'category',
                get_string('smartmenusmenuitemtypedynamiccourses', 'theme_boost_union').': '.
                get_string('smartmenusdynamiccoursescoursecategory', 'theme_boost_union'), $categoriesoptions);
        $mform->setType('category', PARAM_INT);
        $mform->hideIf('category', 'type', 'neq', smartmenu_item::TYPEDYNAMIC);
        $catwidget->setMultiple(true);
        $mform->addHelpButton('category', 'smartmenusdynamiccoursescoursecategory', 'theme_boost_union');

        // Add include-subcategories as checkbox.
        $mform->addElement('advcheckbox', 'category_subcats',
                get_string('smartmenusdynamiccoursescoursecategorysubcats', 'theme_boost_union'));
        $mform->setType('category_subcats', PARAM_BOOL);
        $mform->addHelpButton('category_subcats', 'smartmenusdynamiccoursescoursecategorysubcats', 'theme_boost_union');
        $mform->hideIf('category_subcats', 'type', 'neq', smartmenu_item::TYPEDYNAMIC);

        // Add roles (for the dynamic courses menu item type) as autocomplete element.
        $courseroles = get_roles_for_contextlevels(CONTEXT_COURSE);
        list($insql, $inparams) = $DB->get_in_or_equal(array_values($courseroles));
        $roles = $DB->get_records_sql("SELECT * FROM {role} WHERE id $insql", $inparams);
        $rolesoptions = role_fix_names($roles, null, ROLENAME_ALIAS, true);
        $roleswidget = $mform->addElement('autocomplete', 'enrolmentrole',
                get_string('smartmenusmenuitemtypedynamiccourses', 'theme_boost_union').': '.
                get_string('smartmenusdynamiccoursesenrolmentrole', 'theme_boost_union'), $rolesoptions);
        $mform->setType('enrolmentrole', PARAM_INT);
        $mform->hideIf('enrolmentrole', 'type', 'neq', smartmenu_item::TYPEDYNAMIC);
        $roleswidget->setMultiple(true);
        $mform->addHelpButton('enrolmentrole', 'smartmenusdynamiccoursesenrolmentrole', 'theme_boost_union');

        // Add completion status (for the dynamic courses menu item type) as autocomplete element.
        $completionstatusoptions = smartmenu_item::get_completionstatus_options();
        $completionstatuswidget = $mform->addElement('autocomplete', 'completionstatus',
                get_string('smartmenusmenuitemtypedynamiccourses', 'theme_boost_union').': '.
                get_string('smartmenusdynamiccoursescompletionstatus', 'theme_boost_union'), $completionstatusoptions);
        $mform->setType('completionstatus', PARAM_INT);
        $mform->hideIf('completionstatus', 'type', 'neq', smartmenu_item::TYPEDYNAMIC);
        $completionstatuswidget->setMultiple(true);
        $mform->addHelpButton('completionstatus', 'smartmenusdynamiccoursescompletionstatus', 'theme_boost_union');

        // Add date range (for the dynamic courses menu item type) as autocomplete element.
        $daterangeoptions = smartmenu_item::get_daterange_options();
        $daterangewidget = $mform->addElement('autocomplete', 'daterange',
                get_string('smartmenusmenuitemtypedynamiccourses', 'theme_boost_union').': '.
                get_string('smartmenusdynamiccoursesdaterange', 'theme_boost_union'), $daterangeoptions);
        $mform->setType('daterange', PARAM_INT);
        $mform->hideIf('daterange', 'type', 'neq', smartmenu_item::TYPEDYNAMIC);
        $daterangewidget->setMultiple(true);
        $mform->addHelpButton('daterange', 'smartmenusdynamiccoursesdaterange', 'theme_boost_union');

        // Add additional form elements for custom course fields.
        smartmenu_item::load_custom_field_config($mform);

        // Add presentation as header element.
        $mform->addElement('header', 'presentationheader',
                get_string('smartmenusmenuitempresentationheader', 'theme_boost_union'));
        $mform->setExpanded('presentationheader');

        // Add icon as input element.
        // Build icon list.
        $theme = \core\output\theme_config::load($PAGE->theme->name);
        $faiconsystem = \core\output\icon_system_fontawesome::instance($theme->get_icon_system());
        $iconlist = $faiconsystem->get_core_icon_map();
        array_unshift($iconlist, '');
        // Create element.
        $iconwidget = $mform->addElement('select', 'menuicon',
                get_string('smartmenusmenuitemicon', 'theme_boost_union'), $iconlist);
        $mform->setType('menuicon', PARAM_TEXT);
        $iconwidget->setMultiple(false);
        $mform->addHelpButton('menuicon', 'smartmenusmenuitemicon', 'theme_boost_union');
        // Include the fontawesome icon picker to the element.
        $systemcontextid = \context_system::instance()->id;
        $PAGE->requires->js_call_amd('theme_boost_union/fontawesome-popover', 'init', ['#id_menuicon', $systemcontextid]);

        // Add title presentation and select element.
        $displayoptions = smartmenu_item::get_display_options();
        $mform->addElement('select', 'display', get_string('smartmenusmenuitemdisplayoptions', 'theme_boost_union'),
                $displayoptions);
        $mform->setDefault('display', smartmenu_item::DISPLAY_SHOWTITLEICON);
        $mform->setType('display', PARAM_INT);
        $mform->addHelpButton('display', 'smartmenusmenuitemdisplayoptions', 'theme_boost_union');

        // Add tooltip as input element.
        $mform->addElement('text', 'tooltip', get_string('smartmenusmenuitemtooltip', 'theme_boost_union'));
        $mform->setType('tooltip', PARAM_TEXT);
        $mform->addHelpButton('tooltip', 'smartmenusmenuitemtooltip', 'theme_boost_union');

        // Add link target as select element.
        $targetoptions = smartmenu_item::get_target_options();
        $mform->addElement('select', 'target', get_string('smartmenusmenuitemlinktarget', 'theme_boost_union'),
                $targetoptions);
        $mform->setDefault('target', smartmenu_item::TARGET_SAME);
        $mform->setType('target', PARAM_INT);
        $mform->addHelpButton('target', 'smartmenusmenuitemlinktarget', 'theme_boost_union');

        // Add responsive hiding as checkbox group.
        $responsivegroup = [];
        // Hide on desktop.
        $responsivegroup[] = $mform->createElement('advcheckbox', 'desktop',
                get_string('smartmenusmenuitemresponsivedesktop', 'theme_boost_union'), null, ['group' => 1]);
        // Hide on tablet.
        $responsivegroup[] = $mform->createElement('advcheckbox', 'tablet',
                get_string('smartmenusmenuitemresponsivetablet', 'theme_boost_union'), null, ['group' => 1]);
        // Hide on mobile.
        $responsivegroup[] = $mform->createElement('advcheckbox', 'mobile',
                get_string('smartmenusmenuitemresponsivemobile', 'theme_boost_union'), null, ['group' => 1]);
        $mform->addGroup($responsivegroup, 'responsive',
                get_string('smartmenusmenuitemresponsive', 'theme_boost_union'), '', false);
        $mform->addHelpButton('responsive', 'smartmenusmenuitemresponsive', 'theme_boost_union');

        // Add order as input element.
        $mform->addElement('text', 'sortorder', get_string('smartmenusmenuitemorder', 'theme_boost_union'));
        $mform->setType('sortorder', PARAM_INT);
        $mform->addRule('sortorder', get_string('required'), 'required');
        $mform->addRule('sortorder', get_string('err_numeric', 'form'), 'numeric', null, 'client');
        $mform->addHelpButton('sortorder', 'smartmenusmenuitemorder', 'theme_boost_union');
        if (isset($this->_customdata['nextorder'])) {
            $mform->setDefault('sortorder', $this->_customdata['nextorder']);
        }

        // Add CSS class as input element.
        $mform->addElement('text', 'cssclass', get_string('smartmenusmenuitemcssclass', 'theme_boost_union'));
        $mform->setType('cssclass', PARAM_TEXT);
        $mform->addHelpButton('cssclass', 'smartmenusmenuitemcssclass', 'theme_boost_union');

        // Add course list ordering (for the dynamic courses menu item type) as select element.
        $listsortoptions = smartmenu_item::get_listsort_options();
        $mform->addElement('select', 'listsort',
                get_string('smartmenusmenuitemtypedynamiccourses', 'theme_boost_union').': '.
                get_string('smartmenusmenuitemlistsort', 'theme_boost_union'), $listsortoptions);
        $mform->setDefault('listsort', smartmenu_item::LISTSORT_FULLNAME_ASC);
        $mform->setType('listsort', PARAM_INT);
        $mform->hideIf('listsort', 'type', 'neq', smartmenu_item::TYPEDYNAMIC);
        $mform->addHelpButton('listsort', 'smartmenusmenuitemlistsort', 'theme_boost_union');

        // Add course name presentation (for the dynamic courses menu item type) as select element.
        $displayfieldoptions = smartmenu_item::get_displayfield_options();
        $mform->addElement('select', 'displayfield',
                get_string('smartmenusmenuitemtypedynamiccourses', 'theme_boost_union').': '.
                get_string('smartmenusmenuitemdisplayfield', 'theme_boost_union'), $displayfieldoptions);
        $mform->setDefault('displayfield', smartmenu_item::FIELD_FULLNAME);
        $mform->setType('displayfield', PARAM_INT);
        $mform->hideIf('displayfield', 'type', 'neq', smartmenu_item::TYPEDYNAMIC);
        $mform->addHelpButton('displayfield', 'smartmenusmenuitemdisplayfield', 'theme_boost_union');

        // Add number of words (for the dynamic courses menu item type) as input element.
        $mform->addElement('text', 'textcount',
                get_string('smartmenusmenuitemtypedynamiccourses', 'theme_boost_union').': '.
                get_string('smartmenusmenuitemtextcount', 'theme_boost_union'));
        $mform->setType('textcount', PARAM_INT);
        $mform->addRule('textcount', get_string('err_numeric', 'form'), 'numeric', null, 'client');
        $mform->hideIf('textcount', 'type', 'neq', smartmenu_item::TYPEDYNAMIC);
        $mform->addHelpButton('textcount', 'smartmenusmenuitemtextcount', 'theme_boost_union');

        // If the menu is configured to be presented as cards.
        if (isset($this->_customdata['menutype']) && $this->_customdata['menutype'] == smartmenu::TYPE_CARD) {
            // Add card appearance as header element.
            $mform->addElement('header', 'cardpresentationheader',
                    get_string('smartmenusmenuitemcardappearanceheader', 'theme_boost_union'));
            $mform->setExpanded('cardpresentationheader');

            // Add card image as filepicker element.
            $filepickeroptions = smartmenu_item::image_filepickeroptions();
            $mform->addElement('filemanager', 'image', get_string('smartmenusmenuitemcardimage', 'theme_boost_union'), null,
                    $filepickeroptions);
            $mform->addHelpButton('image', 'smartmenusmenuitemcardimage', 'theme_boost_union');

            // Add card text position as select element.
            $textpositionoptions = smartmenu_item::get_textposition_options();
            $mform->addElement('select', 'textposition',
                    get_string('smartmenusmenuitemtextposition', 'theme_boost_union'), $textpositionoptions);
            $mform->setDefault('textposition', smartmenu_item::POSITION_BELOW);
            $mform->setType('textposition', PARAM_INT);
            $mform->addHelpButton('textposition', 'smartmenusmenuitemtextposition', 'theme_boost_union');

            // Add card text color as color picker element.
            $mform->addElement('theme_boost_union_colorpicker', 'textcolor',
                    get_string('smartmenusmenuitemcardtextcolor', 'theme_boost_union'));
            $mform->setType('textcolor', PARAM_TEXT);
            $mform->addRule('textcolor', get_string('validateerror', 'admin'), 'theme_boost_union_colorpicker_rule');
            $mform->addHelpButton('textcolor', 'smartmenusmenuitemcardtextcolor', 'theme_boost_union');

            // Add card background color as color picker element.
            $mform->addElement('theme_boost_union_colorpicker', 'backgroundcolor',
                    get_string('smartmenusmenuitemcardbackgroundcolor', 'theme_boost_union'));
            $mform->setType('backgroundcolor', PARAM_TEXT);
            $mform->addRule('backgroundcolor', get_string('validateerror', 'admin'), 'theme_boost_union_colorpicker_rule');
            $mform->addHelpButton('backgroundcolor', 'smartmenusmenuitemcardbackgroundcolor', 'theme_boost_union');
        }

        // Add restrict visibility by roles as header element.
        $mform->addElement('header', 'restrictbyrolesheader',
                get_string('smartmenusrestrictbyrolesheader', 'theme_boost_union'));
        // Set the header to expanded if the restriction is already set.
        if (isset($this->_customdata['menuitem']) &&
                count(json_decode($this->_customdata['menuitem']->roles)) > 0) {
            $mform->setExpanded('restrictbyrolesheader');
        }

        // Add by roles as autocomplete element.
        $rolelist = role_get_names(\context_system::instance());
        $roleoptions = [];
        foreach ($rolelist as $role) {
            if ($role->archetype !== 'frontpage') { // Frontpage roles are not supported in the items restriction.
                $roleoptions[$role->id] = $role->localname;
            }
        }
        $byroleswidget = $mform->addElement('autocomplete', 'roles', get_string('smartmenusbyrole', 'theme_boost_union'),
                $roleoptions);
        $byroleswidget->setMultiple(true);
        $mform->addHelpButton('roles', 'smartmenusbyrole', 'theme_boost_union');

        // Add context as select element.
        $rolecontext = smartmenu::get_rolecontext_options();
        $mform->addElement('select', 'rolecontext', get_string('smartmenusrolecontext', 'theme_boost_union'), $rolecontext);
        $mform->setDefault('rolecontext', smartmenu::ANYCONTEXT);
        $mform->setType('rolecontext', PARAM_INT);
        $mform->addHelpButton('rolecontext', 'smartmenusrolecontext', 'theme_boost_union');

        // Add restrict visibility by admin as header element.
        $mform->addElement('header', 'restrictbyadminheader',
                get_string('smartmenusrestrictbyadminheader', 'theme_boost_union'));
        if (isset($this->_customdata['menuitem']) && $this->_customdata['menuitem']->byadmin) {
            $mform->setExpanded('restrictbyadminheader');
        }

        // Add restriction as select element.
        $byadminoptions = smartmenu::get_byadmin_options();
        $mform->addElement('select', 'byadmin', get_string('smartmenusbyadmin', 'theme_boost_union'), $byadminoptions);
        $mform->setDefault('byadmin', smartmenu::BYADMIN_ALL);
        $mform->setType('byadmin', PARAM_INT);
        $mform->addHelpButton('byadmin', 'smartmenusbyadmin', 'theme_boost_union');

        // Add restrict visibility by cohorts as header element.
        $mform->addElement('header', 'restrictbycohortsheader',
                get_string('smartmenusrestrictbycohortsheader', 'theme_boost_union'));
        // Set the header to expanded if the restriction is already set.
        if (isset($this->_customdata['menuitem']) &&
                count(json_decode($this->_customdata['menuitem']->cohorts)) > 0) {
            $mform->setExpanded('restrictbycohortsheader');
        }

        // Add by cohorts as autocomplete element.
        $cohortslist = \cohort_get_all_cohorts(0, 0);
        $cohortoptions = $cohortslist['cohorts'];
        if ($cohortoptions) {
            array_walk($cohortoptions, function(&$value) {
                $value = $value->name;
            });
        }
        $bycohortswidget = $mform->addElement('autocomplete', 'cohorts', get_string('smartmenusbycohort', 'theme_boost_union'),
                $cohortoptions);
        $bycohortswidget->setMultiple(true);
        $mform->addHelpButton('cohorts', 'smartmenusbycohort', 'theme_boost_union');

        // Add operator as select element.
        $operatoroptions = smartmenu::get_operator_options();
        $mform->addElement('select', 'operator', get_string('smartmenusoperator', 'theme_boost_union'), $operatoroptions);
        $mform->setDefault('operator', smartmenu::ANY);
        $mform->setType('operator', PARAM_INT);
        $mform->addHelpButton('operator', 'smartmenusoperator', 'theme_boost_union');

        // Add restrict visibility by language as header element.
        $mform->addElement('header', 'restrictbylanguageheader',
                get_string('smartmenusrestrictbylanguageheader', 'theme_boost_union'));
        // Set the header to expanded if the restriction is already set.
        if (isset($this->_customdata['menuitem']) &&
                count(json_decode($this->_customdata['menuitem']->languages)) > 0) {
            $mform->setExpanded('restrictbylanguageheader');
        }

        // Add by language as autocomplete element.
        $languagelist = get_string_manager()->get_list_of_translations();
        $langoptions = [];
        foreach ($languagelist as $key => $lang) {
            $langoptions[$key] = $lang;
        }
        $bylanguagewidget = $mform->addElement('autocomplete', 'languages',
                get_string('smartmenusbylanguage', 'theme_boost_union'), $langoptions);
        $bylanguagewidget->setMultiple(true);
        $mform->addHelpButton('languages', 'smartmenusbylanguage', 'theme_boost_union');

        // Add restrict visibility by date as header element.
        $mform->addElement('header', 'restrictbydateheader',
                get_string('smartmenusrestrictbydateheader', 'theme_boost_union'));
        // Set the header to expanded if the restriction is already set.
        if (isset($this->_customdata['menuitem']) &&
                ($this->_customdata['menuitem']->start_date > 0 || $this->_customdata['menuitem']->end_date > 0)) {
            $mform->setExpanded('restrictbydateheader');
        }

        // Add from as datepicker element.
        $mform->addElement('date_time_selector', 'start_date',
                get_string('smartmenusbydatefrom', 'theme_boost_union'), ['optional' => true]);
        $mform->addHelpButton('start_date', 'smartmenusbydatefrom', 'theme_boost_union');

        // Add until as datepicker element.
        $mform->addElement('date_time_selector', 'end_date',
                get_string('smartmenusbydateuntil', 'theme_boost_union'), ['optional' => true]);
        $mform->addHelpButton('end_date', 'smartmenusbydateuntil', 'theme_boost_union');

        // Add the action buttons.
        $this->add_action_buttons();
    }

    /**
     * Validates form data.
     *
     * @param array $data Array containing form data.
     * @param array $files Array containing uploaded files.
     * @return array Array of errors, if any.
     */
    public function validation($data, $files) {
        // Call parent form validation first.
        $errors = parent::validation($data, $files);

        // If the menu item type is static.
        if ($data['type'] == smartmenu_item::TYPESTATIC) {
            // Verify that the URL field is not empty.
            if (empty($data['url'])) {
                $errors['url'] = get_string('required');
            }
        }

        // Return errors.
        return $errors;
    }
}
