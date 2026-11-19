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

namespace theme_boost_union\smartmenu;

/**
 * Theme Boost Union - Base class of all smart menu providers.
 *
 * A smart menu provider is either a location provider (which defines where and how smart menus are placed on the page,
 * see \theme_boost_union\smartmenu\location\base) or a presentation provider (which defines how the items of a smart
 * menu are presented, see \theme_boost_union\smartmenu\presentation\base).
 *
 * This class holds everything which both provider kinds have in common: Their identity, their contributions to the
 * smart menu and smart menu item edit forms, their assets and their lifecycle callbacks.
 *
 * The settings of a provider are stored by Boost Union as JSON in the locationdata column (for locations) or in the
 * presentationdata column (for presentations) of the menu or the menu item, keyed by the key of the provider
 * (see get_data_field() and get_data()). A provider does not need database tables of its own.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class provider {
    /**
     * The version of the provider API.
     * This number is raised whenever the provider API is changed in a way which is not compatible with the existing
     * providers of other plugins. Each provider declares the version of the provider API which it has been built for
     * (see get_api_version()). Providers which have been built for another version of the provider API are ignored
     * by the provider registry until they have been adapted.
     *
     * @var int
     */
    public const API_VERSION = 1;

    /**
     * The regular expression which a provider key has to match.
     * The key must not contain an underscore as the underscore separates the provider prefix from the field name
     * in the form element names. It fits into the presentation column of the menus table.
     *
     * @var string
     */
    public const KEY_REGEX = '/^[a-z][a-z0-9]{1,49}$/';

    /**
     * Get the key of the provider.
     * The key identifies the provider within its kind, it is stored in the database and has to match KEY_REGEX.
     *
     * @return string
     */
    abstract public function get_key(): string;

    /**
     * Get the version of the provider API which this provider has been built for.
     * Providers of other plugins have to return a fixed number here (and must not return the API_VERSION constant),
     * as this number is compared with API_VERSION to find out if the provider is compatible with the installed
     * version of Boost Union. If it is not, the provider is ignored.
     *
     * @return int
     */
    abstract public function get_api_version(): int;

    /**
     * Get the localised name of the provider which is shown to the admin.
     *
     * @return string
     */
    abstract public function get_name(): string;

    /**
     * Get the kind prefix of the provider ('loc' for locations, 'pres' for presentations).
     *
     * @return string
     */
    abstract protected function get_kind_prefix(): string;

    /**
     * Get the name of the field of a menu or menu item in which the settings of the providers of this kind are stored
     * ('locationdata' for locations, 'presentationdata' for presentations).
     *
     * @return string
     */
    abstract public function get_data_field(): string;

    /**
     * Get the stored settings of this provider from a menu or menu item record.
     *
     * @param \stdClass|null $record The menu or menu item record (with the provider data being JSON encoded or decoded).
     * @return array The settings, keyed by the plain field names.
     */
    final public function get_data(?\stdClass $record): array {
        // Get the settings of all providers of this kind from the record.
        // The data field is locationdata for locations and presentationdata for presentations. Its content is either
        // still JSON encoded (as it is stored in the database) or already decoded, both is handled here.
        // If no record is given or if the data field is not set, there are no settings.
        $data = \theme_boost_union\smartmenu::decode_providerdata($record->{$this->get_data_field()} ?? null);

        // Pick the settings of this provider which are stored under its key.
        // If nothing (or something else than a list of settings) is stored for this provider, return no settings.
        return is_array($data[$this->get_key()] ?? null) ? $data[$this->get_key()] : [];
    }

    /**
     * Get the prefix of the form elements of this provider.
     * As a location and a presentation might share the same key, the kind prefix is part of the form prefix.
     *
     * @return string
     */
    final public function get_form_prefix(): string {
        return $this->get_kind_prefix() . $this->get_key();
    }

    /**
     * Get the name of a form element of this provider.
     * All form elements which a provider adds to the smart menu forms have to be named with this function to avoid
     * collisions with the form elements of Boost Union and of other providers.
     *
     * @param string $name The plain name of the field.
     * @return string The prefixed name of the form element.
     */
    final public function field_name(string $name): string {
        return $this->get_form_prefix() . '_' . $name;
    }

    /**
     * Get the AMD modules which have to be loaded on the page as soon as this provider renders at least one menu.
     * Each module has to export an init() function which is called without arguments.
     *
     * Please note: The main navigation and the menu bar are rendered by React which replaces the DOM nodes when the
     * navigation is re-measured. The modules should therefore use delegated event listeners.
     *
     * @param array $nodes The smart menu nodes which this provider renders on the current page.
     * @return string[] The AMD module names, e.g. ['local_example/menu'].
     */
    public function get_amd_modules(array $nodes): array {
        return [];
    }

    /**
     * Get the SCSS code which should be added to the pre SCSS of Boost Union (i.e. variables).
     *
     * @return string
     */
    public function get_pre_scss(): string {
        return '';
    }

    /**
     * Get the SCSS code which should be added after the post SCSS of Boost Union.
     * The code is compiled together with the theme and can use all Bootstrap and Boost Union variables and mixins.
     *
     * @return string
     */
    public function get_post_scss(): string {
        return '';
    }

    /**
     * Add the form elements of this provider to the smart menu edit form.
     * The elements have to be named with field_name().
     *
     * The provider does not have to care about hiding its elements as long as it is not selected in the form:
     * This function is called by add_menu_form_elements() which hides all elements which have been added here.
     *
     * This function is protected on purpose: It must only be called by add_menu_form_elements(), so that
     * the elements of a provider cannot get into the form without being hidden. Providers should overwrite it
     * as protected function as well.
     *
     * @param \MoodleQuickForm $mform The form.
     * @param array $customdata The custom data of the form.
     * @return void
     */
    protected function menu_form_definition(\MoodleQuickForm $mform, array $customdata): void {
    }

    /**
     * Let this provider add its form elements to the smart menu edit form and hide all of these elements
     * as long as this provider is not selected in the form.
     *
     * This function is called by the smart menu edit form. It is the reason why the providers do not have to hide
     * their elements themselves (and why a provider cannot forget to do it).
     *
     * @param \MoodleQuickForm $mform The form.
     * @param array $customdata The custom data of the form.
     * @return void
     */
    final public function add_menu_form_elements(\MoodleQuickForm $mform, array $customdata): void {
        // Remember the elements which exist in the form before the provider adds its elements.
        // Please note: The form does not offer a function to get the names of its elements, so the element index
        // of the form is used directly (as Moodle core does it in several places as well).
        $existingelements = array_keys($mform->_elementIndex);

        // Let the provider add its elements.
        $this->menu_form_definition($mform, $customdata);

        // Hide each element which has been added by the provider as long as the provider is not selected.
        // (Elements within a group are covered by the group, elements without a name cannot be hidden at all).
        foreach (array_diff(array_keys($mform->_elementIndex), $existingelements) as $elementname) {
            $this->hide_unless_selected($mform, (string) $elementname);
        }
    }

    /**
     * Hide a form element of the smart menu edit form as long as this provider is not selected.
     *
     * @param \MoodleQuickForm $mform The form.
     * @param string $elementname The (prefixed) name of the element to hide.
     * @return void
     */
    abstract protected function hide_unless_selected(\MoodleQuickForm $mform, string $elementname): void;

    /**
     * Validate the form elements of this provider in the smart menu edit form.
     * This function is only called if this provider is selected in the submitted form.
     *
     * @param array $data The submitted form data.
     * @param array $files The submitted files.
     * @return array The errors, keyed by the (prefixed) element names.
     */
    public function menu_form_validation(array $data, array $files): array {
        return [];
    }

    /**
     * Prepare the stored settings of this provider for the smart menu edit form.
     * By default, each stored setting is mapped to the form element with the same name.
     * Providers with file managers or editors have to prepare their draft areas here.
     *
     * @param \stdClass $menu The menu record (with an empty id if a new menu is created).
     * @param array $providerdata The stored settings of this provider for the menu.
     * @return array The form data, keyed by the (prefixed) element names.
     */
    public function menu_form_prepare_data(\stdClass $menu, array $providerdata): array {
        return $this->prefix_data($providerdata);
    }

    /**
     * Process the submitted smart menu edit form and return the settings of this provider which should be stored.
     * By default, the values of all form elements of this provider are stored.
     * Providers with file managers or editors have to save their draft areas here.
     * This function is only called if this provider is selected in the submitted form.
     *
     * @param \stdClass $formdata The submitted form data.
     * @param int $menuid The ID of the menu which has been saved.
     * @return array The settings to be stored, keyed by the plain field names.
     */
    public function menu_form_process_data(\stdClass $formdata, int $menuid): array {
        return $this->unprefix_data((array) $formdata);
    }

    /**
     * Add the form elements of this provider to the smart menu item edit form.
     * This function is only called if this provider is used by the menu of the item.
     *
     * @param \MoodleQuickForm $mform The form.
     * @param \stdClass $menu The menu record of the item.
     * @param array $customdata The custom data of the form.
     * @return void
     */
    public function item_form_definition(\MoodleQuickForm $mform, \stdClass $menu, array $customdata): void {
    }

    /**
     * Validate the form elements of this provider in the smart menu item edit form.
     *
     * @param array $data The submitted form data.
     * @param array $files The submitted files.
     * @param \stdClass $menu The menu record of the item.
     * @return array The errors, keyed by the (prefixed) element names.
     */
    public function item_form_validation(array $data, array $files, \stdClass $menu): array {
        return [];
    }

    /**
     * Prepare the stored settings of this provider for the smart menu item edit form.
     * By default, each stored setting is mapped to the form element with the same name.
     *
     * @param \stdClass $item The menu item record (with an empty id if a new item is created).
     * @param \stdClass $menu The menu record of the item.
     * @param array $providerdata The stored settings of this provider for the item.
     * @return array The form data, keyed by the (prefixed) element names.
     */
    public function item_form_prepare_data(\stdClass $item, \stdClass $menu, array $providerdata): array {
        return $this->prefix_data($providerdata);
    }

    /**
     * Process the submitted smart menu item edit form and return the settings of this provider which should be stored.
     * By default, the values of all form elements of this provider are stored.
     *
     * @param \stdClass $formdata The submitted form data.
     * @param int $itemid The ID of the menu item which has been saved.
     * @param \stdClass $menu The menu record of the item.
     * @return array The settings to be stored, keyed by the plain field names.
     */
    public function item_form_process_data(\stdClass $formdata, int $itemid, \stdClass $menu): array {
        return $this->unprefix_data((array) $formdata);
    }

    /**
     * Callback which is called after a menu has been deleted.
     *
     * @param int $menuid The ID of the deleted menu.
     * @return void
     */
    public function menu_deleted(int $menuid): void {
    }

    /**
     * Callback which is called after a menu item has been deleted.
     *
     * @param int $itemid The ID of the deleted menu item.
     * @return void
     */
    public function item_deleted(int $itemid): void {
    }

    /**
     * Callback which is called after a menu has been duplicated.
     * The stored settings of the provider have already been copied to the new menu at this point.
     *
     * @param int $oldmenuid The ID of the original menu.
     * @param int $newmenuid The ID of the new menu.
     * @return void
     */
    public function menu_duplicated(int $oldmenuid, int $newmenuid): void {
    }

    /**
     * Callback which is called after a menu item has been duplicated.
     * The stored settings of the provider have already been copied to the new item at this point.
     *
     * @param int $olditemid The ID of the original menu item.
     * @param int $newitemid The ID of the new menu item.
     * @return void
     */
    public function item_duplicated(int $olditemid, int $newitemid): void {
    }

    /**
     * Helper function to add the provider prefix to the keys of the given data.
     *
     * This is needed as the settings of a provider are stored with their plain field names (e.g. 'cardsize'),
     * whereas the form elements of a provider have to be named with the provider prefix (e.g. 'prescard_cardsize')
     * to avoid collisions with the form elements of Boost Union and of other providers (see field_name()).
     * So, the stored settings have to be mapped to the prefixed element names before they are loaded into the form.
     * This function is the counterpart of unprefix_data().
     *
     * @param array $data The data, keyed by the plain field names.
     * @return array The data, keyed by the prefixed element names.
     */
    final protected function prefix_data(array $data): array {
        $result = [];
        foreach ($data as $name => $value) {
            // Add the provider prefix to the plain field name.
            $result[$this->field_name($name)] = $value;
        }
        return $result;
    }

    /**
     * Helper function to pick the values of this provider from the given data and to remove the provider prefix from their keys.
     *
     * This is needed as the submitted form data contains the values of all form elements of the form
     * (the ones of Boost Union and the ones of all providers), whereas only the values of this provider should be
     * stored as its settings, and as they should be stored with their plain field names (e.g. 'cardsize'
     * instead of 'prescard_cardsize'), independent of the naming of the form elements.
     * This function is the counterpart of prefix_data().
     *
     * @param array $data The data, keyed by the prefixed element names.
     * @return array The data of this provider, keyed by the plain field names.
     */
    final protected function unprefix_data(array $data): array {
        // Get the prefix of the form elements of this provider including the separator (e.g. 'prescard_').
        $prefix = $this->field_name('');

        $result = [];
        foreach ($data as $name => $value) {
            // Pick the value only if it belongs to a form element of this provider
            // and remove the provider prefix from its name.
            if (str_starts_with((string) $name, $prefix)) {
                $result[substr($name, strlen($prefix))] = $value;
            }
        }
        return $result;
    }
}
