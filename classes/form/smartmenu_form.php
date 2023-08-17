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
class smartmenu_form extends \moodleform {

    /**
     * Define smartmenu form elements.
     *
     * @return void
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);

        // General section.
        $mform->addElement('header', 'generalsection', get_string('smartmenusgeneralsection', 'theme_boost_union'));

        // Add the Title field (required).
        $mform->addElement('text', 'title', get_string('smartmenusmenutitle', 'theme_boost_union'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('error'), 'required');
        $mform->addHelpButton('title', 'smartmenusmenutitle', 'theme_boost_union');

        // Add the Description field (optional).
        $mform->addElement('editor', 'description', get_string('description'));
        $mform->addHelpButton('description', 'smartmenusdescription', 'theme_boost_union');

        // Add the Show Description field (required).
        $options = array(
            smartmenu::DESC_NEVER => get_string('never', 'core'),
            smartmenu::DESC_ABOVE => get_string('smartmenusabove', 'theme_boost_union'),
            smartmenu::DESC_BELOW => get_string('smartmenusbelow', 'theme_boost_union'),
            smartmenu::DESC_HELP => get_string('help', 'core')
        );
        $mform->addElement('select', 'showdesc', get_string('smartmenusshowdescription', 'theme_boost_union'), $options);

        // Add the Location field, Locations the menu will be shown.
        $types = \theme_boost_union\smartmenu::get_locations();
        $location = $mform->addElement('autocomplete', 'location', get_string('smartmenuslocation', 'theme_boost_union'), $types);
        $mform->addHelpButton('location', 'smartmenuslocation', 'theme_boost_union');
        $location->setMultiple(true);

        // Add the Type field (required), card, list.
        $types = \theme_boost_union\smartmenu::get_types();
        $mform->addElement('select', 'type', get_string('smartmenustypes', 'theme_boost_union'), $types);
        $mform->addHelpButton('type', 'smartmenustypes', 'theme_boost_union');

        // Advanced settings options. Settings for advanced users / special use cases.
        $mform->addElement('header', 'advanced_settings', get_string('smartmenusadvancedsettings', 'theme_boost_union'));

        // Display options mode.
        $displayoptions = [
            smartmenu::MODE_SUBMENU => get_string('smartmenussubmenu', 'theme_boost_union'),
            smartmenu::MODE_INLINE => get_string('smartmenusinline', 'theme_boost_union'),
        ];
        $mform->addElement('select', 'mode', get_string('smartmenusmode', 'theme_boost_union'), $displayoptions);
        $mform->addHelpButton('mode', 'smartmenusmenumode', 'theme_boost_union');

        // CSS Class.
        $mform->addElement('text', 'cssclass', get_string('smartmenuscssclass', 'theme_boost_union'));
        $mform->addHelpButton('cssclass', 'smartmenuscssclass', 'theme_boost_union');
        $mform->setType('cssclass', PARAM_TEXT);

        // More menu behavior.
        $moremenu = array(
            smartmenu::MOREMENU_DEFAULT => get_string('default', 'core'),
            smartmenu::MOREMENU_INTO => get_string('smartmenusforcedintomoremenu', 'theme_boost_union'),
            smartmenu::MOREMENU_OUTSIDE => get_string('smartmenusforcedoutsideofmoremenu', 'theme_boost_union')
        );
        $mform->addElement('select', 'moremenubehavior', get_string('smartmenusmoremenubehavior', 'theme_boost_union'), $moremenu);
        $mform->addHelpButton('moremenubehavior', 'smartmenusmoremenubehavior', 'theme_boost_union');

        // Card appearance options.
        $mform->addElement('header', 'card_appearance', get_string('smartmenuscardappearance', 'theme_boost_union'));

        // Size.
        $sizeoptions = array(
            smartmenu::TINY => get_string('smartmenustiny', 'theme_boost_union').' (50px)',
            smartmenu::SMALL => get_string('smartmenussmall', 'theme_boost_union').' (100px)',
            smartmenu::MEDIUM => get_string('smartmenusmedium', 'theme_boost_union').' (150px)',
            smartmenu::LARGE => get_string('smartmenuslarge', 'theme_boost_union').' (200px)'
        );
        $mform->addElement('select', 'cardsize', get_string('smartmenuscardsize', 'theme_boost_union'), $sizeoptions);
        $mform->disabledIf('cardsize', 'type', 0);
        $mform->addHelpButton('cardsize', 'smartmenuscardsize', 'theme_boost_union');

        // Form.
        $formoptions = array(
            smartmenu::SQUARE => get_string('smartmenussquare', 'theme_boost_union') . ' (1/1)',
            smartmenu::PORTRAIT => get_string('smartmenusportrait', 'theme_boost_union') . ' (2/3)',
            smartmenu::LANDSCAPE => get_string('smartmenuslandscape', 'theme_boost_union') . ' (3/2)',
            smartmenu::FULLWIDTH => get_string('smartmenusfullwidth', 'theme_boost_union')
        );
        $mform->addElement('select', 'cardform', get_string('smartmenuscardform', 'theme_boost_union'), $formoptions);
        $mform->disabledIf('cardform', 'type', 'neq', smartmenu::TYPE_CARD);
        $mform->addHelpButton('cardform', 'smartmenuscardform', 'theme_boost_union');

        // Overflow behavior.
        $overflow = array(
            smartmenu::NOWRAP => get_string('smartmenusnowrap', 'theme_boost_union'),
            smartmenu::WRAP => get_string('smartmenuswrap', 'theme_boost_union')
        );
        $mform->addElement('select', 'overflowbehavior', get_string('smartmenusoverflowbehavior', 'theme_boost_union'), $overflow);
        $mform->disabledIf('overflowbehavior', 'type', 'neq', smartmenu::TYPE_CARD);
        $mform->addHelpButton('overflowbehavior', 'smartmenusoverflowbehavior', 'theme_boost_union');

        // Access rule by roles.
        $mform->addElement('header', 'accessbyroles', get_string('smartmenusaccessbyroles', 'theme_boost_union'));

        // Access based on the user roles.
        $roleoptions = role_get_names(\context_system::instance());
        $roles = [];
        foreach ($roleoptions as $role) {
            $roles[$role->id] = $role->localname;
        }
        $roles = $mform->addElement('autocomplete', 'roles', get_string('smartmenusbyrole', 'theme_boost_union'), $roles);
        $mform->addHelpButton('roles', 'smartmenusbyrole', 'theme_boost_union');
        $roles->setMultiple(true);

        $rolecontext = [
            smartmenu::ANYCONTEXT => get_string('any'),
            smartmenu::SYSTEMCONTEXT => get_string('coresystem'),
        ];
        $mform->addElement('select', 'rolecontext', get_string('smartmenusrolecontext', 'theme_boost_union'), $rolecontext);
        $mform->addHelpButton('rolecontext', 'smartmenusrolecontext', 'theme_boost_union');

        // Access rule by cohorts.
        $mform->addElement('header', 'accessbycohorts', get_string('smartmenusaccessbycohorts', 'theme_boost_union'));

        // Cohorts based access.
        $cohortslist = \cohort_get_all_cohorts();
        $cohorts = $cohortslist['cohorts'];
        if ($cohorts) {
            array_walk($cohorts, function(&$value) {
                $value = $value->name;
            });
        }

        $cohort = $mform->addElement('autocomplete', 'cohorts', get_string('smartmenusbycohort', 'theme_boost_union'), $cohorts);
        $cohort->setMultiple(true);
        $mform->addHelpButton('cohorts', 'smartmenusbycohort', 'theme_boost_union');

        $rolecontext = [
            smartmenu::ANY => get_string('any'),
            smartmenu::ALL => get_string('all'),
        ];
        $mform->addElement('select', 'operator', get_string('smartmenusoperator', 'theme_boost_union'), $rolecontext);
        $mform->addHelpButton('operator', 'smartmenusoperator', 'theme_boost_union');

        // Access rule by languages.
        $mform->addElement('header', 'accessbylanguage', get_string('smartmenusaccessbylanguage', 'theme_boost_union'));

        // Languages based access.
        $languages = get_string_manager()->get_list_of_translations();
        $langoptions = array();
        foreach ($languages as $key => $lang) {
            $langoptions[$key] = $lang;
        }
        $language = $mform->addElement('autocomplete', 'languages',
            get_string('smartmenusbylanguage', 'theme_boost_union'), $langoptions);
        $language->setMultiple(true);
        $mform->addHelpButton('languages', 'smartmenusbylanguage', 'theme_boost_union');

        // Access rule by languages.
        $mform->addElement('header', 'accessbydateselector', get_string('smartmenusaccessbydateselector', 'theme_boost_union'));

        $mform->addElement('date_time_selector', 'start_date',
            get_string('smartmenusfrom', 'theme_boost_union'), array('optional' => true));
        $mform->addHelpButton('start_date', 'smartmenusfrom', 'theme_boost_union');

        $mform->addElement('date_time_selector', 'end_date',
            get_string('smartmenusdurationuntil', 'theme_boost_union'), array('optional' => true));
        $mform->addHelpButton('end_date', 'smartmenusdurationuntil', 'theme_boost_union');

        // Add the Submit button.
        // When two elements we need a group.
        $buttonar = array();
        $classar = array('class' => 'form-submit');
        $buttonar[] = &$mform->createElement('submit', 'saveandreturn', get_string('savechangesandreturn'), $classar);
        $buttonar[] = &$mform->createElement('submit', 'saveanddisplay',
            get_string('smartmenussavechangesandconfigure', 'theme_boost_union'), $classar);
        $buttonar[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonar, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
    }

    /**
     * Validates form data. Verify the card form size and overflow behaviour is selected if menu type is card.
     *
     * @param array $data Array containing form data.
     * @param array $files Array containing uploaded files.
     * @return array Array of errors, if any.
     */
    public function validation($data, $files) {
        $errors = [];
        // Verify the URL field is not empty if the item type is static.
        if ($data['type'] == smartmenu::TYPE_CARD && empty($data['cardform'])) {
            $errors['cardform'] = get_string('required');
        }
        if ($data['type'] == smartmenu::TYPE_CARD && empty($data['overflowbehavior'])) {
            $errors['overflowbehavior'] = get_string('required');
        }
        return $errors;
    }
}
