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
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/cohort/lib.php');

use theme_boost_union\smartmenu;
use theme_boost_union\smartmenu\provider_registry;

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
        $mform->addElement(
            'header',
            'generalsettingsheader',
            get_string('smartmenusgeneralsectionheader', 'theme_boost_union')
        );
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
        $mform->addElement(
            'header',
            'structureheader',
            get_string('smartmenusmenustructureheader', 'theme_boost_union')
        );
        $mform->setExpanded('structureheader');

        // Add locations as autocompletefield.
        $locationtypes = provider_registry::get_location_options();
        // If the menu is assigned to locations which do not exist (anymore), e.g. as the plugin which provides the location
        // has been uninstalled, keep them as options. Otherwise, they would get lost silently as soon as the menu is saved.
        if (isset($this->_customdata['menu'])) {
            foreach ((array) (json_decode($this->_customdata['menu']->location ?? '') ?: []) as $locationkey) {
                if (!isset($locationtypes[$locationkey])) {
                    $locationtypes[$locationkey] = get_string('smartmenusproviderunknown', 'theme_boost_union', $locationkey);
                }
            }
        }
        $location = $mform->addElement(
            'autocomplete',
            'location',
            get_string('smartmenusmenulocation', 'theme_boost_union'),
            $locationtypes
        );
        $mform->addHelpButton('location', 'smartmenusmenulocation', 'theme_boost_union');
        $location->setMultiple(true);
        $mform->addRule('location', get_string('required'), 'required');

        // Add the form elements of the location providers.
        // The elements of a provider are hidden automatically as long as its location is not selected.
        foreach (provider_registry::get_locations() as $provider) {
            $provider->add_menu_form_elements($mform, $this->_customdata ?? []);
        }

        // Add mode as select element.
        $modeoptions = smartmenu::get_mode_options();
        $mform->addElement('select', 'mode', get_string('smartmenusmenumode', 'theme_boost_union'), $modeoptions);
        $mform->setDefault('mode', smartmenu::MODE_SUBMENU);
        $mform->setType('mode', PARAM_INT);
        $mform->addHelpButton('mode', 'smartmenusmenumode', 'theme_boost_union');

        // Add presentation as header element.
        $mform->addElement(
            'header',
            'presentationheader',
            get_string('smartmenusmenupresentationheader', 'theme_boost_union')
        );
        $mform->setExpanded('presentationheader');

        // Add presentation as select element.
        $presentations = provider_registry::get_presentation_options();
        // If the menu uses a presentation which does not exist (anymore), e.g. as the plugin which provides the presentation
        // has been uninstalled, keep it as option. Otherwise, it would get lost silently as soon as the menu is saved.
        if (
            isset($this->_customdata['menu']) &&
                !empty($this->_customdata['menu']->presentation) &&
                !isset($presentations[$this->_customdata['menu']->presentation])
        ) {
            $presentations[$this->_customdata['menu']->presentation] = get_string(
                'smartmenusproviderunknown',
                'theme_boost_union',
                $this->_customdata['menu']->presentation
            );
        }
        $mform->addElement(
            'select',
            'presentation',
            get_string('smartmenusmenutype', 'theme_boost_union'),
            $presentations
        );
        $mform->setDefault('presentation', smartmenu::PRESENTATION_LIST);
        $mform->setType('presentation', PARAM_ALPHANUM);
        $mform->addHelpButton('presentation', 'smartmenusmenutype', 'theme_boost_union');

        // Hide the menu mode as soon as a presentation is selected which does not support the inline mode.
        // Menus with such a presentation are always shown as submenu, so the menu mode would not have any effect.
        // (As the menu mode is not submitted when it is hidden, the form falls back to the default menu mode then.
        // So, a menu with such a presentation is stored with the submenu mode as soon as it is saved).
        foreach (provider_registry::get_presentations() as $key => $provider) {
            if (!$provider->menu_supports_inline_mode()) {
                $mform->hideIf('mode', 'presentation', 'eq', $key);
            }
        }

        // Add the form elements of the presentation providers.
        // The elements of a provider are hidden automatically as long as its presentation is not selected.
        foreach (provider_registry::get_presentations() as $provider) {
            $provider->add_menu_form_elements($mform, $this->_customdata ?? []);
        }

        // Add show description as select element.
        $showdescriptionoptions = smartmenu::get_showdescription_options();
        $mform->addElement(
            'select',
            'showdesc',
            get_string('smartmenusmenushowdescription', 'theme_boost_union'),
            $showdescriptionoptions
        );
        $mform->setDefault('showdesc', smartmenu::DESC_NEVER);
        $mform->setType('showdesc', PARAM_INT);
        $mform->addHelpButton('showdesc', 'smartmenusmenushowdescription', 'theme_boost_union');

        // Add more menu behavior as select element.
        $moremenuoptions = smartmenu::get_moremenu_options();
        $mform->addElement(
            'select',
            'moremenubehavior',
            get_string('smartmenusmenumoremenubehavior', 'theme_boost_union'),
            $moremenuoptions
        );
        $mform->setDefault('moremenubehavior', smartmenu::MOREMENU_DONOTCHANGE);
        $mform->setType('moremenubehavior', PARAM_INT);
        $mform->addHelpButton('moremenubehavior', 'smartmenusmenumoremenubehavior', 'theme_boost_union');

        // Hide the more menu behavior as long as no location is selected which supports it.
        // The setting only has an effect at locations which collapse their menus into a "More" menu.
        // The location element is a multi-select. For multi-selects, the 'in' condition is fulfilled if the selected
        // values are a subset of the given values. So, the element is hidden as soon as only locations which do not
        // support the setting (or no location at all, which is represented by an empty value) are selected.
        // (As the more menu behavior is not submitted when it is hidden, the form falls back to the default more menu
        // behavior then).
        $nomoremenukeys = [''];
        foreach (array_keys($locationtypes) as $locationkey) {
            $provider = provider_registry::get_location($locationkey);
            if ($provider === null || !$provider->menu_supports_moremenu_behavior()) {
                $nomoremenukeys[] = $locationkey;
            }
        }
        // Please note: The dependency has to be defined on the name of the HTML element, which is 'location[]' for the
        // multi-select, as the form JavaScript matches the dependencies by the name attributes of the elements.
        $mform->hideIf('moremenubehavior', 'location[]', 'in', $nomoremenukeys);

        // Add CSS class as input element.
        $mform->addElement('text', 'cssclass', get_string('smartmenusmenucssclass', 'theme_boost_union'));
        $mform->addHelpButton('cssclass', 'smartmenusmenucssclass', 'theme_boost_union');
        $mform->setType('cssclass', PARAM_TEXT);

        // Add restrict visibility by roles as header element.
        $mform->addElement(
            'header',
            'restrictbyrolesheader',
            get_string('smartmenusrestrictbyrolesheader', 'theme_boost_union')
        );
        // Set the header to expanded if the restriction is already set.
        if (
            isset($this->_customdata['menu']) &&
                count(json_decode($this->_customdata['menu']->roles)) > 0
        ) {
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
        $byroleswidget = $mform->addElement(
            'autocomplete',
            'roles',
            get_string('smartmenusbyrole', 'theme_boost_union'),
            $roleoptions
        );
        $byroleswidget->setMultiple(true);
        $mform->addHelpButton('roles', 'smartmenusbyrole', 'theme_boost_union');

        // Add context as select element.
        $rolecontext = smartmenu::get_rolecontext_options();
        $mform->addElement('select', 'rolecontext', get_string('smartmenusrolecontext', 'theme_boost_union'), $rolecontext);
        $mform->setDefault('rolecontext', smartmenu::ANYCONTEXT);
        $mform->setType('rolecontext', PARAM_INT);
        $mform->addHelpButton('rolecontext', 'smartmenusrolecontext', 'theme_boost_union');

        // Add restrict visibility by admin as header element.
        $mform->addElement(
            'header',
            'restrictbyadminheader',
            get_string('smartmenusrestrictbyadminheader', 'theme_boost_union')
        );
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
        $mform->addElement(
            'header',
            'restrictbycohortsheader',
            get_string('smartmenusrestrictbycohortsheader', 'theme_boost_union')
        );
        // Set the header to expanded if the restriction is already set.
        if (
            isset($this->_customdata['menu']) &&
                count(json_decode($this->_customdata['menu']->cohorts)) > 0
        ) {
            $mform->setExpanded('restrictbycohortsheader');
        }

        // Add by cohorts as autocomplete element.
        $cohortslist = \cohort_get_all_cohorts(0, 0);
        $cohortoptions = $cohortslist['cohorts'];
        if ($cohortoptions) {
            array_walk($cohortoptions, function (&$value) {
                $value = $value->name;
            });
        }
        $bycohortswidget = $mform->addElement(
            'autocomplete',
            'cohorts',
            get_string('smartmenusbycohort', 'theme_boost_union'),
            $cohortoptions
        );
        $bycohortswidget->setMultiple(true);
        $mform->addHelpButton('cohorts', 'smartmenusbycohort', 'theme_boost_union');

        // Add operator as select element.
        $operatoroptions = smartmenu::get_operator_options();
        $mform->addElement('select', 'operator', get_string('smartmenusoperator', 'theme_boost_union'), $operatoroptions);
        $mform->setDefault('operator', smartmenu::ANY);
        $mform->setType('operator', PARAM_INT);
        $mform->addHelpButton('operator', 'smartmenusoperator', 'theme_boost_union');

        // Add restrict visibility by language as header element.
        $mform->addElement(
            'header',
            'restrictbylanguageheader',
            get_string('smartmenusrestrictbylanguageheader', 'theme_boost_union')
        );
        // Set the header to expanded if the restriction is already set.
        if (
            isset($this->_customdata['menu']) &&
                count(json_decode($this->_customdata['menu']->languages)) > 0
        ) {
            $mform->setExpanded('restrictbylanguageheader');
        }

        // Add by language as autocomplete element.
        $languagelist = get_string_manager()->get_list_of_translations();
        $langoptions = [];
        foreach ($languagelist as $key => $lang) {
            $langoptions[$key] = $lang;
        }
        $bylanguagewidget = $mform->addElement(
            'autocomplete',
            'languages',
            get_string('smartmenusbylanguage', 'theme_boost_union'),
            $langoptions
        );
        $bylanguagewidget->setMultiple(true);
        $mform->addHelpButton('languages', 'smartmenusbylanguage', 'theme_boost_union');

        // Add restrict visibility by date as header element.
        $mform->addElement(
            'header',
            'restrictbydateheader',
            get_string('smartmenusrestrictbydateheader', 'theme_boost_union')
        );
        // Set the header to expanded if the restriction is already set.
        if (
            isset($this->_customdata['menu']) &&
                ($this->_customdata['menu']->start_date > 0 || $this->_customdata['menu']->end_date > 0)
        ) {
            $mform->setExpanded('restrictbydateheader');
        }

        // Add from as datepicker element.
        $mform->addElement(
            'date_time_selector',
            'start_date',
            get_string('smartmenusbydatefrom', 'theme_boost_union'),
            ['optional' => true]
        );
        $mform->addHelpButton('start_date', 'smartmenusbydatefrom', 'theme_boost_union');

        // Add until as datepicker element.
        $mform->addElement(
            'date_time_selector',
            'end_date',
            get_string('smartmenusbydateuntil', 'theme_boost_union'),
            ['optional' => true]
        );
        $mform->addHelpButton('end_date', 'smartmenusbydateuntil', 'theme_boost_union');

        // Add the action buttons (as we have two buttons, we need a group).
        $actionbuttons = [];
        $actionclasses = ['class' => 'form-submit'];
        $actionbuttons[] = &$mform->createElement(
            'submit',
            'saveandreturn',
            get_string('savechangesandreturn'),
            $actionclasses
        );
        $actionbuttons[] = &$mform->createElement(
            'submit',
            'saveanddisplay',
            get_string('smartmenussavechangesandconfigure', 'theme_boost_union'),
            $actionclasses
        );
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

        // Validate the smart menu location is filled.
        if (empty($data['location'])) {
            $errors['location'] = get_string('required');
        }

        // Get the presentation provider which is selected in the form.
        // A presentation which does not exist (anymore) cannot be validated, the variable is null then.
        $presentation = provider_registry::get_presentation((string) ($data['presentation'] ?? ''));

        // Get the keys of the locations which are selected in the form as strings.
        $selectedlocationkeys = [];
        foreach ((array) ($data['location'] ?? []) as $selectedlocationkey) {
            $selectedlocationkeys[] = (string) $selectedlocationkey;
        }

        // Get the location providers which are selected in the form, keyed by their keys.
        // Locations which do not exist (anymore) cannot be validated and are skipped this way,
        // as only the locations which are known to the registry are picked.
        $locations = [];
        foreach (provider_registry::get_locations() as $locationkey => $location) {
            if (in_array((string) $locationkey, $selectedlocationkeys, true)) {
                $locations[$locationkey] = $location;
            }
        }

        // Validate that the selected presentation and the selected locations support each other.
        if ($presentation !== null && !isset($errors['location'])) {
            foreach ($locations as $location) {
                if (!provider_registry::is_supported_combination($presentation, $location)) {
                    $errors['location'] = get_string(
                        'smartmenusprovidernotsupportedforlocation',
                        'theme_boost_union',
                        ['presentation' => $presentation->get_name(), 'location' => $location->get_name()]
                    );
                    break;
                }
            }
        }

        // Validate that the inline mode is supported by the selected locations.
        // (Presentations which do not support the inline mode are not validated here. They are shown as submenu
        // anyway and the menu mode is hidden in the form as soon as such a presentation is selected. The hidden
        // menu mode is not submitted by the browser then, but the presentation is checked here nevertheless to
        // be safe if the menu mode is submitted anyway. Otherwise, the error would be set on a hidden element).
        if (
            ($data['mode'] ?? null) == smartmenu::MODE_INLINE &&
            ($presentation === null || $presentation->menu_supports_inline_mode())
        ) {
            foreach ($locations as $location) {
                if (!$location->menu_supports_inline_mode()) {
                    $errors['mode'] = get_string(
                        'smartmenuslocationnoinline',
                        'theme_boost_union',
                        $location->get_name()
                    );
                    break;
                }
            }
        }

        // Let the selected providers validate their form elements.
        foreach (array_merge($presentation !== null ? [$presentation] : [], array_values($locations)) as $provider) {
            $errors = array_merge($errors, $provider->menu_form_validation($data, $files));
        }

        // Return errors.
        return $errors;
    }
}
