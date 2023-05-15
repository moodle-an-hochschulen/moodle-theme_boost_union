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
 * @copyright  bdecent GmbH 2023
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

        require_once($CFG->dirroot.'/theme/boost_union/smartmenus/colorpicker.php');
        \MoodleQuickForm::registerElementType(
            'theme_boost_unioncolorpicker',
            $CFG->dirroot.'/theme/boost_union/smartmenus/colorpicker.php',
            'moodlequickform_boostunioncolorpicker'
        );

        // General section.
        $mform->addElement('header', 'general', get_string('general', 'core'));

        // Menu item Title.
        $mform->addElement('text', 'title', get_string('smartmenu:title', 'theme_boost_union'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required');
        $mform->addHelpButton('title', 'smartmenu:title', 'theme_boost_union');

        // Type of the menu item. Display as heading or static content or dynamic courses.
        $types = menuitem::get_types();
        $mform->addElement('select', 'type', get_string('smartmenu:type', 'theme_boost_union'), $types);
        $mform->setType('type', PARAM_INT);
        $mform->addRule('type', null, 'required');
        $mform->addHelpButton('type', 'smartmenu:type', 'theme_boost_union');

        $mform->addElement('text', 'url', get_string('smartmenu:url', 'theme_boost_union'));
        $mform->setType('url', PARAM_URL);
        $mform->disabledIf('url', 'type', 'neq', menuitem::TYPESTATIC);
        $mform->addHelpButton('url', 'smartmenu:url', 'theme_boost_union');

        // List of categories selector.
        $categories = \core_course_category::make_categories_list();
        $cate = $mform->addElement('autocomplete', 'category', get_string('smartmenu:category', 'theme_boost_union'), $categories);
        $mform->setType('category', PARAM_INT);
        $mform->hideIf('category', 'type', 'neq',  menuitem::TYPEDYNAMIC);
        $cate->setMultiple(true);
        $mform->addHelpButton('category', 'smartmenu:category', 'theme_boost_union');

        // Get list of roles used in course context.
        $roles = get_roles_for_contextlevels(CONTEXT_COURSE);
        list($insql, $inparams) = $DB->get_in_or_equal(array_values($roles));
        $roles = $DB->get_records_sql("SELECT * FROM {role} WHERE id $insql", $inparams);
        $enrolmentroles = role_fix_names($roles, null, ROLENAME_ALIAS, true);

        $role = $mform->addElement('autocomplete', 'enrolmentrole',
            get_string('smartmenu:enrolmentrole', 'theme_boost_union'), $enrolmentroles);
        $mform->setType('enrolmentrole', PARAM_INT);
        $mform->hideIf('enrolmentrole', 'type', 'neq', menuitem::TYPEDYNAMIC);
        $role->setMultiple(true);
        $mform->addHelpButton('enrolmentrole', 'smartmenu:enrolmentrole', 'theme_boost_union');

        $completionstatuses = array(
            menuitem::COMPLETION_ENROLLED => get_string('smartmenu:enrolled', 'theme_boost_union'),
            menuitem::COMPLETION_INPROGRESS => get_string('inprogress', 'completion'),
            menuitem::COMPLETION_COMPLETED => get_string('completed', 'completion'),
        );
        $completion = $mform->addElement('autocomplete', 'completionstatus',
            get_string('smartmenu:completionstatus', 'theme_boost_union'), $completionstatuses);
        $mform->setType('completionstatus', PARAM_INT);
        $mform->hideIf('completionstatus', 'type', 'neq', menuitem::TYPEDYNAMIC);
        $completion->setMultiple(true);
        $mform->addHelpButton('completionstatus', 'smartmenu:completionstatus', 'theme_boost_union');

        $ranges = array(
            menuitem::RANGE_PAST => get_string('smartmenu:past', 'theme_boost_union'),
            menuitem::RANGE_PRESENT => get_string('smartmenu:present', 'theme_boost_union'),
            menuitem::RANGE_FUTURE => get_string('smartmenu:future', 'theme_boost_union'),
        );
        $range = $mform->addElement('autocomplete', 'daterange', get_string('smartmenu:daterange', 'theme_boost_union'), $ranges);
        $mform->setType('daterange', PARAM_INT);
        $mform->hideIf('daterange', 'type', 'neq', menuitem::TYPEDYNAMIC);
        $range->setMultiple(true);
        $mform->addHelpButton('daterange', 'smartmenu:daterange', 'theme_boost_union');

        // Load the custom course fields form elements.
        // Using custom fields to setup the conditions.
        menuitem::load_custom_field_config($mform);

        // Appearance section.
        $mform->addElement('header', 'appearance_header', get_string('appearance', 'core'));
        $mform->addElement('static', 'appearanceheader_desc', get_string('smartmenu:appearanceheader_desc', 'theme_boost_union'));

        // Display field value option.
        $displayfields = [
            menuitem::FIELD_FULLNAME => get_string('smartmenu:fullname', 'theme_boost_union'),
            menuitem::FIELD_SHORTNAME => get_string('smartmenu:shortname', 'theme_boost_union'),
        ];
        $mform->addElement('select', 'displayfield', get_string('smartmenu:displayfield', 'theme_boost_union'), $displayfields);
        $mform->hideIf('displayfield', 'type', 'neq', menuitem::TYPEDYNAMIC);
        $mform->addHelpButton('displayfield', 'smartmenu:displayfield', 'theme_boost_union');

        // Number of charaters to display in menu item title.
        $mform->addElement('text', 'textcount', get_string('smartmenu:textcount', 'theme_boost_union'));
        $mform->setType('textcount', PARAM_INT);
        $mform->hideIf('textcount', 'type', 'neq', menuitem::TYPEDYNAMIC);
        $mform->addHelpButton('textcount', 'smartmenu:textcount', 'theme_boost_union');

        // Display options field.
        $displayoptions = [
            menuitem::MODE_INLINE => get_string('smartmenu:inline', 'theme_boost_union'),
            menuitem::MODE_SUBMENU => get_string('smartmenu:submenu', 'theme_boost_union'),
        ];
        $mform->addElement('select', 'mode', get_string('smartmenu:mode', 'theme_boost_union'), $displayoptions);
        $mform->addHelpButton('mode', 'smartmenu:mode', 'theme_boost_union');

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
        $mform->addHelpButton('menuicon', 'smartmenu:menuicon', 'theme_boost_union');

        // Include the fontaswesome icon picker for menu icon select.
        $contextid = \context_system::instance()->id;
        $PAGE->requires->js_call_amd("theme_boost_union/fontawesome-popover", 'init', ["#id_menuicon", $contextid]);

        // Display options field.
        $displayoptions = [
            menuitem::DISPLAY_SHOWTITLEICON => get_string('smartmenu:showtitleicon', 'theme_boost_union'),
            menuitem::DISPLAY_HIDETITLE => get_string('smartmenu:hidetitle', 'theme_boost_union'),
            menuitem::DISPLAY_HIDETITLEMOBILE => get_string('smartmenu:hidetitlemobile', 'theme_boost_union')
        ];
        $mform->addElement('select', 'display', get_string('smartmenu:displayoptions', 'theme_boost_union'), $displayoptions);
        $mform->addHelpButton('display', 'smartmenu:displayoptions', 'theme_boost_union');

        // Tooltip field.
        $mform->addElement('text', 'tooltip', get_string('smartmenu:tooltip', 'theme_boost_union'));
        $mform->setType('tooltip', PARAM_TEXT);
        $mform->addHelpButton('tooltip', 'smartmenu:tooltip', 'theme_boost_union');

        // Order field.
        $mform->addElement('text', 'sortorder', get_string('smartmenu:order', 'theme_boost_union'));
        $mform->setType('sortorder', PARAM_INT);
        $mform->addRule('sortorder', get_string('required'), 'required');
        $mform->addRule('sortorder', get_string('err_numeric', 'form'), 'numeric');
        $mform->addHelpButton('sortorder', 'smartmenu:order', 'theme_boost_union');

        if (isset($this->_customdata['nextorder'])) {
            $mform->setDefault('sortorder', $this->_customdata['nextorder']);
        }

        // Target field.
        $targetoptions = [
            menuitem::TARGET_SAME => get_string('tilelinktargetsetting_samewindow', 'theme_boost_union'),
            menuitem::TARGET_NEW => get_string('tilelinktargetsetting_newtab', 'theme_boost_union')
        ];
        $mform->addElement('select', 'target', get_string('smartmenu:target', 'theme_boost_union'), $targetoptions);
        $mform->addHelpButton('target', 'smartmenu:target', 'theme_boost_union');

        // CSS class field.
        $mform->addElement('text', 'cssclass', get_string('smartmenu:cssclass', 'theme_boost_union'));
        $mform->setType('cssclass', PARAM_TEXT);
        $mform->addHelpButton('cssclass', 'smartmenu:cssclass', 'theme_boost_union');

        // Responsive fields.
        $group = [];
        // Hide in Desktop.
        $group[] = $mform->createElement('advcheckbox', 'desktop',
            get_string('smartmenu:responsivedesktop', 'theme_boost_union'), null, ['group' => 1]);
        // Hide in Tablet.
        $group[] = $mform->createElement('advcheckbox', 'tablet',
            get_string('smartmenu:responsivetablet', 'theme_boost_union'), null, ['group' => 1]);
        // Hide in mobile.
        $group[] = $mform->createElement('advcheckbox', 'mobile',
            get_string('smartmenu:responsivemobile', 'theme_boost_union'), null, ['group' => 1]);
        $mform->addGroup($group, 'responsive', get_string('smartmenu:responsive', 'theme_boost_union'), '', false);
        // Select all controller.
        $this->add_checkbox_controller(1);

        // Appearance section.
        $mform->addElement('header', 'appearance_card', get_string('smartmenu:appearancecard', 'theme_boost_union'));
        $mform->addElement('static', 'appearancecard_desc', get_string('smartmenu:appearancecard_desc', 'theme_boost_union'));

        // Appearance options for cards.
        $options = menuitem::image_fileoptions();
        $mform->addElement('filemanager', 'image', get_string('smartmenu:image', 'theme_boost_union'), null, $options);
        $mform->addHelpButton('image', 'smartmenu:image', 'theme_boost_union');

        $textpositionoptions = array(
            menuitem::POSITION_BELOW => get_string('smartmenu:belowimage', 'theme_boost_union'),
            menuitem::POSITION_OVERLAYTOP => get_string('smartmenu:overlaytop', 'theme_boost_union'),
            menuitem::POSITION_OVERLAYBOTTOM => get_string('smartmenu:overlaybottom', 'theme_boost_union')
        );
        $mform->addElement('select', 'textposition',
            get_string('smartmenu:textposition', 'theme_boost_union'), $textpositionoptions);
        $mform->setDefault('textposition', 'theme_boost_union');
        $mform->addHelpButton('textposition', 'smartmenu:textposition', 'theme_boost_union');

        // Text color.
        $mform->addElement('theme_boost_unioncolorpicker', 'textcolor', get_string('smartmenu:textcolor', 'theme_boost_union'));
        $mform->addHelpButton('textcolor', 'smartmenu:textcolor', 'theme_boost_union');

        // Background color.
        $mform->addElement('theme_boost_unioncolorpicker', 'backgroundcolor',
            get_string('smartmenu:backgroundcolor', 'theme_boost_union'));
        $mform->addHelpButton('backgroundcolor', 'smartmenu:backgroundcolor', 'theme_boost_union');

        // Access rule by roles.
        $mform->addElement('header', 'accessbyroles', get_string('smartmenu:accessbyroles', 'theme_boost_union'));

        // Access based on the user roles.
        $roleoptions = role_get_names(\context_system::instance());
        $roles = [];
        foreach ($roleoptions as $role) {
            $roles[$role->id] = $role->localname;
        }
        $roles = $mform->addElement('autocomplete', 'roles', get_string('smartmenu:byrole', 'theme_boost_union'), $roles);
        $mform->addHelpButton('roles', 'smartmenu:byrole', 'theme_boost_union');
        $roles->setMultiple(true);

        $rolecontext = [
            smartmenu::ANYCONTEXT => get_string('any'),
            smartmenu::SYSTEMCONTEXT => get_string('coresystem'),
        ];
        $mform->addElement('select', 'rolecontext', get_string('smartmenu:rolecontext', 'theme_boost_union'), $rolecontext);
        $mform->addHelpButton('rolecontext', 'smartmenu:rolecontext', 'theme_boost_union');

        // Access rule by cohorts.
        $mform->addElement('header', 'accessbycohorts', get_string('smartmenu:accessbycohorts', 'theme_boost_union'));

        // Cohorts based access.
        $cohorts = \cohort_get_all_cohorts();
        $cohortoptions = $cohorts['cohorts'];
        if ($cohortoptions) {
            array_walk($cohortoptions, function(&$value) {
                $value = $value->name;
            });
        }
        $cohort = $mform->addElement('autocomplete', 'cohorts',
            get_string('smartmenu:bycohort', 'theme_boost_union'), $cohortoptions);

        $mform->addHelpButton('cohorts', 'smartmenu:bycohort', 'theme_boost_union');
        $cohort->setMultiple(true);

        $operator = [
            smartmenu::ANY => get_string('any'),
            smartmenu::ALL => get_string('all'),
        ];
        $mform->addElement('select', 'operator', get_string('smartmenu:operator', 'theme_boost_union'), $operator);
        $mform->addHelpButton('operator', 'smartmenu:operator', 'theme_boost_union');

        // Access rule by languages.
        $mform->addElement('header', 'accessbylanguage', get_string('smartmenu:accessbylanguage', 'theme_boost_union'));

        // Languages based access.
        $languages = get_string_manager()->get_list_of_translations();
        $langoptions = array();
        foreach ($languages as $key => $lang) {
            $langoptions[$key] = $lang;
        }
        $language = $mform->addElement('autocomplete', 'languages',
            get_string('smartmenu:bylanguage', 'theme_boost_union'), $langoptions);
        $language->setMultiple(true);
        $mform->addHelpButton('languages', 'smartmenu:bylanguage', 'theme_boost_union');

        // Access rule by dates.
        $mform->addElement('header', 'accessbydateselector', get_string('smartmenu:accessbydateselector', 'theme_boost_union'));
        // Prevent the menu display until the start date is reached.
        $mform->addElement('date_time_selector', 'start_date',
            get_string('smartmenu:from', 'theme_boost_union'), array('optional' => true));
        $mform->addHelpButton('start_date', 'smartmenu:from', 'theme_boost_union');
        // Hide the item, if the end date is reached.
        $mform->addElement('date_time_selector', 'end_date',
            get_string('smartmenu:durationuntil', 'theme_boost_union'), array('optional' => true));
        $mform->addHelpButton('end_date', 'smartmenu:durationuntil', 'theme_boost_union');

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
