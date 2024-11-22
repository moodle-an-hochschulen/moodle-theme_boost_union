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
 * Theme Boost Union - Smart menu edit form
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

use theme_boost_union\smartmenu;

/**
 * Form for editing or adding a smart menu item.
 */
class smartmenu_edit_form extends \moodleform {

    /**
     * Define form elements.
     *
     * @throws \coding_exception
     */
    public function definition() {
        // Get an easier handler for the form.
        $mform = $this->_form;

        // Add the smart menu ID as hidden element.
        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);

        // Add general settings as header element.
        $mform->addElement('header', 'generalsettingsheader',
                get_string('smartmenusgeneralsectionheader', 'theme_boost_union'));
        $mform->setExpanded('generalsettingsheader');

        // Add the title as input element.
        $mform->addElement('text', 'title', get_string('smartmenusmenutitle', 'theme_boost_union'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('required'), 'required');
        $mform->addHelpButton('title', 'smartmenusmenutitle', 'theme_boost_union');

        // Add the description title as editor element.
        $mform->addElement('editor', 'description', get_string('smartmenusmenudescription', 'theme_boost_union'));
        $mform->setType('description', PARAM_CLEANHTML);
        $mform->addHelpButton('description', 'smartmenusmenudescription', 'theme_boost_union');

        // Add structure as header element.
        $mform->addElement('header', 'structureheader',
                get_string('smartmenusmenustructureheader', 'theme_boost_union'));
        $mform->setExpanded('structureheader');

        // Add locations as autocompletefield.
        $locationtypes = smartmenu::get_locations();
        $location = $mform->addElement('autocomplete', 'location', get_string('smartmenusmenulocation', 'theme_boost_union'),
                $locationtypes);
        $mform->addHelpButton('location', 'smartmenusmenulocation', 'theme_boost_union');
        $location->setMultiple(true);
        $mform->addRule('location', get_string('required'), 'required');

        // Add mode as select element.
        $modeoptions = smartmenu::get_mode_options();
        $mform->addElement('select', 'mode', get_string('smartmenusmenumode', 'theme_boost_union'), $modeoptions);
        $mform->setDefault('mode', smartmenu::MODE_SUBMENU);
        $mform->setType('mode', PARAM_INT);
        $mform->addHelpButton('mode', 'smartmenusmenumode', 'theme_boost_union');

        // Add presentation as header element.
        $mform->addElement('header', 'presentationheader',
                get_string('smartmenusmenupresentationheader', 'theme_boost_union'));
        $mform->setExpanded('presentationheader');

        // Add type as select element.
        $types = smartmenu::get_types();
        $mform->addElement('select', 'type', get_string('smartmenusmenutype', 'theme_boost_union'), $types);
        $mform->setDefault('type', smartmenu::TYPE_LIST);
        $mform->setType('type', PARAM_INT);
        $mform->addHelpButton('type', 'smartmenusmenutype', 'theme_boost_union');

        // Add show description as select element.
        $showdescriptionoptions = smartmenu::get_showdescription_options();
        $mform->addElement('select', 'showdesc', get_string('smartmenusmenushowdescription', 'theme_boost_union'),
                $showdescriptionoptions);
        $mform->setDefault('showdesc', smartmenu::DESC_NEVER);
        $mform->setType('showdesc', PARAM_INT);
        $mform->addHelpButton('showdesc', 'smartmenusmenushowdescription', 'theme_boost_union');

        // Add more menu behavior as select element.
        $moremenuoptions = smartmenu::get_moremenu_options();
        $mform->addElement('select', 'moremenubehavior', get_string('smartmenusmenumoremenubehavior', 'theme_boost_union'),
                $moremenuoptions);
        $mform->setDefault('moremenubehavior', smartmenu::MOREMENU_DONOTCHANGE);
        $mform->setType('moremenubehavior', PARAM_INT);
        $mform->addHelpButton('moremenubehavior', 'smartmenusmenumoremenubehavior', 'theme_boost_union');

        // Add CSS class as input element.
        $mform->addElement('text', 'cssclass', get_string('smartmenusmenucssclass', 'theme_boost_union'));
        $mform->addHelpButton('cssclass', 'smartmenusmenucssclass', 'theme_boost_union');
        $mform->setType('cssclass', PARAM_TEXT);

        // Add card size as select element.
        $cardsizeoptions = smartmenu::get_cardsize_options();
        $mform->addElement('select', 'cardsize', get_string('smartmenusmenucardsize', 'theme_boost_union'), $cardsizeoptions);
        $mform->setDefault('cardsize', smartmenu::CARDSIZE_TINY);
        $mform->setType('cardsize', PARAM_INT);
        $mform->hideIf('cardsize', 'type', 'neq', smartmenu::TYPE_CARD);
        $mform->addHelpButton('cardsize', 'smartmenusmenucardsize', 'theme_boost_union');

        // Add card form as select element.
        $cardformoptions = smartmenu::get_cardform_options();
        $mform->addElement('select', 'cardform',
                get_string('smartmenusmenucardform', 'theme_boost_union'), $cardformoptions);
        $mform->setDefault('cardform', smartmenu::CARDFORM_SQUARE);
        $mform->setType('cardform', PARAM_INT);
        $mform->hideIf('cardform', 'type', 'neq', smartmenu::TYPE_CARD);
        $mform->addHelpButton('cardform', 'smartmenusmenucardform', 'theme_boost_union');

        // Add card overflow behaviour as select element.
        $cardoverflowoptions = smartmenu::get_cardoverflowbehaviour_options();
        $mform->addElement('select', 'cardoverflowbehavior',
                get_string('smartmenusmenucardoverflowbehavior', 'theme_boost_union'), $cardoverflowoptions);
        $mform->setDefault('cardoverflowbehaviour', smartmenu::CARDOVERFLOWBEHAVIOUR_NOWRAP);
        $mform->setType('cardoverflowbehaviour', PARAM_INT);
        $mform->hideIf('cardoverflowbehavior', 'type', 'neq', smartmenu::TYPE_CARD);
        $mform->addHelpButton('cardoverflowbehavior', 'smartmenusmenucardoverflowbehavior', 'theme_boost_union');

        // Add restrict visibility by roles as header element.
        $mform->addElement('header', 'restrictbyrolesheader',
                get_string('smartmenusrestrictbyrolesheader', 'theme_boost_union'));
        // Set the header to expanded if the restriction is already set.
        if (isset($this->_customdata['menu']) &&
                count(json_decode($this->_customdata['menu']->roles)) > 0) {
            $mform->setExpanded('restrictbyrolesheader');
        }

        // Add by roles as autocomplete element.
        $rolelist = role_get_names(\context_system::instance());
        $roleoptions = [];
        foreach ($rolelist as $role) {
            if ($role->archetype !== 'frontpage') { // Frontpage roles are not supported in the menus restriction.
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
        if (isset($this->_customdata['menu']) && $this->_customdata['menu']->byadmin) {
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
        if (isset($this->_customdata['menu']) &&
                count(json_decode($this->_customdata['menu']->cohorts)) > 0) {
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
        if (isset($this->_customdata['menu']) &&
                count(json_decode($this->_customdata['menu']->languages)) > 0) {
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
        if (isset($this->_customdata['menu']) &&
                ($this->_customdata['menu']->start_date > 0 || $this->_customdata['menu']->end_date > 0)) {
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

        // Add the action buttons (as we have two buttons, we need a group).
        $actionbuttons = [];
        $actionclasses = ['class' => 'form-submit'];
        $actionbuttons[] = &$mform->createElement('submit', 'saveandreturn',
                get_string('savechangesandreturn'), $actionclasses);
        $actionbuttons[] = &$mform->createElement('submit', 'saveanddisplay',
                get_string('smartmenussavechangesandconfigure', 'theme_boost_union'), $actionclasses);
        $actionbuttons[] = &$mform->createElement('cancel');
        $mform->addGroup($actionbuttons, 'actionbuttons', '', [' '], false);
        $mform->closeHeaderBefore('actionbuttons');
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

        // If the menu type is card.
        if ($data['type'] == smartmenu::TYPE_CARD) {
            // Verify that the card size is not empty.
            // (This should be already the case as this wiget is just a select element without neutral option).
            if (empty($data['cardsize'])) {
                $errors['cardsize'] = get_string('required');
            }

            // Verify that the card form is not empty.
            // (This should be already the case as this wiget is just a select element without neutral option).
            if (empty($data['cardform'])) {
                $errors['cardform'] = get_string('required');
            }

            // Verify that the overflow behaviour is selected.
            // (This should be already the case as this wiget is just a select element without neutral option).
            if (empty($data['cardoverflowbehavior'])) {
                $errors['cardoverflowbehavior'] = get_string('required');
            }
        }

        // Validate the smart menu location is filled.
        if (empty($data['location'])) {
            $errors['location'] = get_string('required');
        }

        // Return errors.
        return $errors;
    }
}
