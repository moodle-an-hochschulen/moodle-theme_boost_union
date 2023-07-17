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

use theme_boost_union\smartmenu_item as menuitem;
use theme_boost_union\smartmenu;

/**
 * Smart menu items edit form.
 */
class smartmenu_item_form extends \moodleform {

    /**
     * Menu item create form elements defined.
     *
     * @return void
     */
    public function definition() {
        global $DB, $PAGE, $CFG;

        $mform = $this->_form;

        // Current edit item id, null for new items.
        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);

        // Menu id.
        $mform->addElement('hidden', 'menu', 0);
        $mform->setType('menu', PARAM_INT);
        $menuid = (isset($this->_customdata['menu'])) ? $this->_customdata['menu'] : 0;
        $mform->setDefault('menu', $menuid);

        require_once($CFG->dirroot.'/theme/boost_union/form/element-colorpicker.php');
        \MoodleQuickForm::registerElementType(
            'theme_boost_union_colorpicker',
            $CFG->dirroot.'/theme/boost_union/form/element-colorpicker.php',
            'moodlequickform_themeboostunion_colorpicker'
        );

        // General section.
        $mform->addElement('header', 'general', get_string('general', 'core'));

        // Menu item Title.
        $mform->addElement('text', 'title', get_string('smartmenustitle', 'theme_boost_union'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required');
        $mform->addHelpButton('title', 'smartmenustitle', 'theme_boost_union');

        // Type of the menu item. Display as heading or static content or dynamic courses.
        $types = menuitem::get_types();
        $mform->addElement('select', 'type', get_string('smartmenustype', 'theme_boost_union'), $types);
        $mform->setType('type', PARAM_INT);
        $mform->addRule('type', null, 'required');
        $mform->addHelpButton('type', 'smartmenustype', 'theme_boost_union');

        $mform->addElement('text', 'url', get_string('smartmenusurl', 'theme_boost_union'));
        $mform->setType('url', PARAM_URL);
        $mform->hideIf('url', 'type', 'neq', menuitem::TYPESTATIC);
        $mform->addHelpButton('url', 'smartmenusurl', 'theme_boost_union');

        // List of categories selector.
        $categories = \core_course_category::make_categories_list();
        $cate = $mform->addElement('autocomplete', 'category', get_string('smartmenuscategory', 'theme_boost_union'), $categories);
        $mform->setType('category', PARAM_INT);
        $mform->hideIf('category', 'type', 'neq',  menuitem::TYPEDYNAMIC);
        $cate->setMultiple(true);
        $mform->addHelpButton('category', 'smartmenuscategory', 'theme_boost_union');

        // Get list of roles used in course context.
        $roles = get_roles_for_contextlevels(CONTEXT_COURSE);
        list($insql, $inparams) = $DB->get_in_or_equal(array_values($roles));
        $roles = $DB->get_records_sql("SELECT * FROM {role} WHERE id $insql", $inparams);
        $enrolmentroles = role_fix_names($roles, null, ROLENAME_ALIAS, true);

        $role = $mform->addElement('autocomplete', 'enrolmentrole',
            get_string('smartmenusenrolmentrole', 'theme_boost_union'), $enrolmentroles);
        $mform->setType('enrolmentrole', PARAM_INT);
        $mform->hideIf('enrolmentrole', 'type', 'neq', menuitem::TYPEDYNAMIC);
        $role->setMultiple(true);
        $mform->addHelpButton('enrolmentrole', 'smartmenusenrolmentrole', 'theme_boost_union');

        $completionstatuses = array(
            menuitem::COMPLETION_ENROLLED => get_string('smartmenusenrolled', 'theme_boost_union'),
            menuitem::COMPLETION_INPROGRESS => get_string('inprogress', 'completion'),
            menuitem::COMPLETION_COMPLETED => get_string('completed', 'completion'),
        );
        $completion = $mform->addElement('autocomplete', 'completionstatus',
            get_string('smartmenuscompletionstatus', 'theme_boost_union'), $completionstatuses);
        $mform->setType('completionstatus', PARAM_INT);
        $mform->hideIf('completionstatus', 'type', 'neq', menuitem::TYPEDYNAMIC);
        $completion->setMultiple(true);
        $mform->addHelpButton('completionstatus', 'smartmenuscompletionstatus', 'theme_boost_union');

        $ranges = array(
            menuitem::RANGE_PAST => get_string('smartmenuspast', 'theme_boost_union'),
            menuitem::RANGE_PRESENT => get_string('smartmenuspresent', 'theme_boost_union'),
            menuitem::RANGE_FUTURE => get_string('smartmenusfuture', 'theme_boost_union'),
        );
        $range = $mform->addElement('autocomplete', 'daterange', get_string('smartmenusdaterange', 'theme_boost_union'), $ranges);
        $mform->setType('daterange', PARAM_INT);
        $mform->hideIf('daterange', 'type', 'neq', menuitem::TYPEDYNAMIC);
        $range->setMultiple(true);
        $mform->addHelpButton('daterange', 'smartmenusdaterange', 'theme_boost_union');

        // Load the custom course fields form elements.
        // Using custom fields to setup the conditions.
        menuitem::load_custom_field_config($mform);

        // Appearance section.
        $mform->addElement('header', 'appearance_header', get_string('smartmenusappearanceheader', 'theme_boost_union'));
        $mform->addElement('static', 'appearanceheader_desc', get_string('smartmenusappearanceheader_desc', 'theme_boost_union'));

        // Display field value option.
        $displayfields = [
            menuitem::FIELD_FULLNAME => get_string('smartmenusfullname', 'theme_boost_union'),
            menuitem::FIELD_SHORTNAME => get_string('smartmenusshortname', 'theme_boost_union'),
        ];
        $mform->addElement('select', 'displayfield', get_string('smartmenusdisplayfield', 'theme_boost_union'), $displayfields);
        $mform->hideIf('displayfield', 'type', 'neq', menuitem::TYPEDYNAMIC);
        $mform->addHelpButton('displayfield', 'smartmenusdisplayfield', 'theme_boost_union');

        // Number of charaters to display in menu item title.
        $mform->addElement('text', 'textcount', get_string('smartmenustextcount', 'theme_boost_union'));
        $mform->setType('textcount', PARAM_INT);
        $mform->hideIf('textcount', 'type', 'neq', menuitem::TYPEDYNAMIC);
        $mform->addHelpButton('textcount', 'smartmenustextcount', 'theme_boost_union');

        // Display options field.
        $displayoptions = [
            menuitem::MODE_INLINE => get_string('smartmenusinline', 'theme_boost_union'),
            menuitem::MODE_SUBMENU => get_string('smartmenussubmenu', 'theme_boost_union'),
        ];
        $mform->addElement('select', 'mode', get_string('smartmenusmode', 'theme_boost_union'), $displayoptions);
        $mform->addHelpButton('mode', 'smartmenusmode', 'theme_boost_union');

        // Menu Icon.
        $options = [];
        $theme = \theme_config::load($PAGE->theme->name);
        $faiconsystem = \core\output\icon_system_fontawesome::instance($theme->get_icon_system());
        $iconlist = $faiconsystem->get_core_icon_map();
        array_unshift($iconlist, '');
        // Icon element.
        $icons = $mform->addElement('select', 'menuicon', get_string('icon', 'core'), $iconlist);
        $icons->setMultiple(false);
        $mform->setType('menuicon', PARAM_TEXT);
        $mform->addHelpButton('menuicon', 'smartmenusmenuicon', 'theme_boost_union');

        // Include the fontawesome icon picker for menu icon select.
        $contextid = \context_system::instance()->id;
        $PAGE->requires->js_call_amd('theme_boost_union/fontawesome-popover', 'init', ['#id_menuicon', $contextid]);

        // Display options field.
        $displayoptions = [
            menuitem::DISPLAY_SHOWTITLEICON => get_string('smartmenusshowtitleicon', 'theme_boost_union'),
            menuitem::DISPLAY_HIDETITLE => get_string('smartmenushidetitle', 'theme_boost_union'),
            menuitem::DISPLAY_HIDETITLEMOBILE => get_string('smartmenushidetitlemobile', 'theme_boost_union')
        ];
        $mform->addElement('select', 'display', get_string('smartmenusdisplayoptions', 'theme_boost_union'), $displayoptions);
        $mform->addHelpButton('display', 'smartmenusdisplayoptions', 'theme_boost_union');

        // Tooltip field.
        $mform->addElement('text', 'tooltip', get_string('smartmenustooltip', 'theme_boost_union'));
        $mform->setType('tooltip', PARAM_TEXT);
        $mform->addHelpButton('tooltip', 'smartmenustooltip', 'theme_boost_union');

        // Order field.
        $mform->addElement('text', 'sortorder', get_string('smartmenusorder', 'theme_boost_union'));
        $mform->setType('sortorder', PARAM_INT);
        $mform->addRule('sortorder', get_string('required'), 'required');
        $mform->addRule('sortorder', get_string('err_numeric', 'form'), 'numeric');
        $mform->addHelpButton('sortorder', 'smartmenusorder', 'theme_boost_union');

        if (isset($this->_customdata['nextorder'])) {
            $mform->setDefault('sortorder', $this->_customdata['nextorder']);
        }

        // Target field.
        $targetoptions = [
            menuitem::TARGET_SAME => get_string('tilelinktargetsetting_samewindow', 'theme_boost_union'),
            menuitem::TARGET_NEW => get_string('tilelinktargetsetting_newtab', 'theme_boost_union')
        ];
        $mform->addElement('select', 'target', get_string('smartmenustarget', 'theme_boost_union'), $targetoptions);
        $mform->addHelpButton('target', 'smartmenustarget', 'theme_boost_union');

        // CSS class field.
        $mform->addElement('text', 'cssclass', get_string('smartmenuscssclass', 'theme_boost_union'));
        $mform->setType('cssclass', PARAM_TEXT);
        $mform->addHelpButton('cssclass', 'smartmenuscssclass', 'theme_boost_union');

        // Responsive fields.
        $group = [];
        // Hide in Desktop.
        $group[] = $mform->createElement('advcheckbox', 'desktop',
            get_string('smartmenusresponsivedesktop', 'theme_boost_union'), null, ['group' => 1]);
        // Hide in Tablet.
        $group[] = $mform->createElement('advcheckbox', 'tablet',
            get_string('smartmenusresponsivetablet', 'theme_boost_union'), null, ['group' => 1]);
        // Hide in mobile.
        $group[] = $mform->createElement('advcheckbox', 'mobile',
            get_string('smartmenusresponsivemobile', 'theme_boost_union'), null, ['group' => 1]);
        $mform->addGroup($group, 'responsive', get_string('smartmenusresponsive', 'theme_boost_union'), '', false);
        // Select all controller.
        $this->add_checkbox_controller(1);

        // Appearance section.
        $mform->addElement('header', 'appearance_card', get_string('smartmenusappearancecard', 'theme_boost_union'));
        $mform->addElement('static', 'appearancecard_desc', get_string('smartmenusappearancecard_desc', 'theme_boost_union'));

        // Appearance options for cards.
        $options = menuitem::image_fileoptions();
        $mform->addElement('filemanager', 'image', get_string('smartmenusimage', 'theme_boost_union'), null, $options);
        $mform->addHelpButton('image', 'smartmenusimage', 'theme_boost_union');

        $textpositionoptions = array(
            menuitem::POSITION_BELOW => get_string('smartmenusbelowimage', 'theme_boost_union'),
            menuitem::POSITION_OVERLAYTOP => get_string('smartmenusoverlaytop', 'theme_boost_union'),
            menuitem::POSITION_OVERLAYBOTTOM => get_string('smartmenusoverlaybottom', 'theme_boost_union')
        );
        $mform->addElement('select', 'textposition',
            get_string('smartmenustextposition', 'theme_boost_union'), $textpositionoptions);
        $mform->setDefault('textposition', 'theme_boost_union');
        $mform->addHelpButton('textposition', 'smartmenustextposition', 'theme_boost_union');

        // Text color.
        $mform->addElement('theme_boost_union_colorpicker', 'textcolor', get_string('smartmenustextcolor', 'theme_boost_union'));
        $mform->addHelpButton('textcolor', 'smartmenustextcolor', 'theme_boost_union');

        // Background color.
        $mform->addElement('theme_boost_union_colorpicker', 'backgroundcolor',
            get_string('smartmenusbackgroundcolor', 'theme_boost_union'));
        $mform->addHelpButton('backgroundcolor', 'smartmenusbackgroundcolor', 'theme_boost_union');

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
        $cohorts = \cohort_get_all_cohorts();
        $cohortoptions = $cohorts['cohorts'];
        if ($cohortoptions) {
            array_walk($cohortoptions, function(&$value) {
                $value = $value->name;
            });
        }
        $cohort = $mform->addElement('autocomplete', 'cohorts',
            get_string('smartmenusbycohort', 'theme_boost_union'), $cohortoptions);

        $mform->addHelpButton('cohorts', 'smartmenusbycohort', 'theme_boost_union');
        $cohort->setMultiple(true);

        $operator = [
            smartmenu::ANY => get_string('any'),
            smartmenu::ALL => get_string('all'),
        ];
        $mform->addElement('select', 'operator', get_string('smartmenusoperator', 'theme_boost_union'), $operator);
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

        // Access rule by dates.
        $mform->addElement('header', 'accessbydateselector', get_string('smartmenusaccessbydateselector', 'theme_boost_union'));
        // Prevent the menu display until the start date is reached.
        $mform->addElement('date_time_selector', 'start_date',
            get_string('smartmenusfrom', 'theme_boost_union'), array('optional' => true));
        $mform->addHelpButton('start_date', 'smartmenusfrom', 'theme_boost_union');
        // Hide the item, if the end date is reached.
        $mform->addElement('date_time_selector', 'end_date',
            get_string('smartmenusdurationuntil', 'theme_boost_union'), array('optional' => true));
        $mform->addHelpButton('end_date', 'smartmenusdurationuntil', 'theme_boost_union');

        $this->add_action_buttons();
    }

    /**
     * Validate the user input data. Verified the URL input filled if the item type is static.
     *
     * @param array $data
     * @param array $files
     * @return void
     */
    public function validation($data, $files) {
        $errors = [];
        // Verify the URL field is not empty if the item type is static.
        if ($data['type'] == menuitem::TYPESTATIC && empty($data['url'])) {
            $errors['url'] = get_string('required');
        }
        return $errors;
    }
}
