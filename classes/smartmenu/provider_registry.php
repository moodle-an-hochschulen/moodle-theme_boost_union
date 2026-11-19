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

use theme_boost_union\hook\smartmenu\collect_providers;
use theme_boost_union\smartmenu\location\base as location_base;
use theme_boost_union\smartmenu\presentation\base as presentation_base;

/**
 * Theme Boost Union - Registry of the smart menu location and presentation providers.
 *
 * The registry knows the built-in providers of Boost Union and collects the providers of other plugins
 * with the \theme_boost_union\hook\smartmenu\collect_providers hook.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class provider_registry {
    /** @var string The key of the presentation which is used as fallback if the presentation of a menu is not available. */
    public const FALLBACK_PRESENTATION = 'list';

    /** @var location_base[]|null The location providers, keyed by their keys (request cache). */
    protected static ?array $locations = null;

    /** @var presentation_base[]|null The presentation providers, keyed by their keys (request cache). */
    protected static ?array $presentations = null;

    /**
     * Get all location providers.
     *
     * @return location_base[] The location providers, keyed by their keys. The built-in locations come first.
     */
    public static function get_locations(): array {
        self::load();
        return self::$locations;
    }

    /**
     * Get all presentation providers.
     *
     * @return presentation_base[] The presentation providers, keyed by their keys. The built-in presentations come first.
     */
    public static function get_presentations(): array {
        self::load();
        return self::$presentations;
    }

    /**
     * Get a location provider.
     *
     * @param string $key The key of the location.
     * @return location_base|null The location provider or null if there is no location with this key.
     */
    public static function get_location(string $key): ?location_base {
        return self::get_locations()[$key] ?? null;
    }

    /**
     * Get a presentation provider.
     *
     * @param string $key The key of the presentation.
     * @return presentation_base|null The presentation provider or null if there is no presentation with this key.
     */
    public static function get_presentation(string $key): ?presentation_base {
        return self::get_presentations()[$key] ?? null;
    }

    /**
     * Get the presentation provider of a menu.
     * If the presentation of the menu is not available (anymore), the fallback presentation is returned.
     *
     * @param \stdClass $menu The menu record.
     * @return presentation_base
     */
    public static function get_menu_presentation(\stdClass $menu): presentation_base {
        return self::get_presentation((string) ($menu->presentation ?? '')) ?? self::get_fallback_presentation();
    }

    /**
     * Get the location providers of a menu.
     * Locations of the menu which are not available (anymore) are skipped.
     *
     * @param \stdClass $menu The menu record.
     * @return location_base[] The location providers, keyed by their keys.
     */
    public static function get_menu_locations(\stdClass $menu): array {
        // Get the location keys of the menu.
        // Depending on where the menu record comes from, the locations are either already decoded as array
        // (if the record comes from smartmenu::get_menu(), which is the case for the menu item edit form and
        // for saving a menu item) or they are JSON encoded as they are stored in the database
        // (in smartmenu::manage_instance() where the record has just been prepared for being stored).
        $menulocation = $menu->location ?? null;
        if (is_array($menulocation)) {
            $keys = $menulocation;
        } else {
            $keys = json_decode($menulocation ?? '[]');
            // If the JSON could not be decoded or if it did not contain a list, the menu does not have any location.
            if (!is_array($keys)) {
                $keys = [];
            }
        }

        // Pick the location providers of the menu from all location providers which are available.
        // The providers are returned in the order of the registry (and not in the order in which they are stored
        // in the menu). Locations of the menu which are not available (anymore) are skipped this way.
        $menulocations = [];
        foreach (self::get_locations() as $key => $provider) {
            if (in_array((string) $key, array_map('strval', $keys), true)) {
                $menulocations[$key] = $provider;
            }
        }

        return $menulocations;
    }

    /**
     * Get all providers (presentation and locations) which are used by a menu.
     *
     * @param \stdClass $menu The menu record.
     * @return provider[] The providers, keyed by their form prefixes.
     */
    public static function get_menu_providers(\stdClass $menu): array {
        $providers = [];

        // Add the presentation of the menu first.
        // A menu always has exactly one presentation. If the presentation of the menu is not available (anymore),
        // this is the fallback presentation.
        $presentation = self::get_menu_presentation($menu);
        $providers[$presentation->get_form_prefix()] = $presentation;

        // Add the locations of the menu afterwards.
        // A menu can have several locations. Locations which are not available (anymore) are skipped.
        foreach (self::get_menu_locations($menu) as $location) {
            $providers[$location->get_form_prefix()] = $location;
        }

        // The providers are keyed by their form prefixes (and not by their plain keys)
        // as a location and a presentation might share the same key.
        return $providers;
    }

    /**
     * Get all providers (presentations and locations).
     *
     * @return provider[] The providers, keyed by their form prefixes.
     */
    public static function get_all_providers(): array {
        $providers = [];

        // Build one list of all presentations and all locations which are available, presentations first.
        // The lists are merged without their keys as a location and a presentation might share the same key.
        $allproviders = array_merge(array_values(self::get_presentations()), array_values(self::get_locations()));

        // Key the providers by their form prefixes which are unique for all providers.
        foreach ($allproviders as $provider) {
            $providers[$provider->get_form_prefix()] = $provider;
        }

        return $providers;
    }

    /**
     * Get the fallback presentation provider.
     *
     * @return presentation_base
     */
    public static function get_fallback_presentation(): presentation_base {
        return self::get_presentations()[self::FALLBACK_PRESENTATION];
    }

    /**
     * Check if a presentation can be used at a location.
     * This is the case if the presentation supports the location and if the location supports the presentation.
     *
     * @param presentation_base $presentation The presentation provider.
     * @param location_base $location The location provider.
     * @return bool
     */
    public static function is_supported_combination(presentation_base $presentation, location_base $location): bool {
        // Check if the presentation supports the location (null means that it supports all locations).
        $supportedlocations = $presentation->get_supported_locations();
        if ($supportedlocations !== null && !in_array($location->get_key(), $supportedlocations)) {
            return false;
        }

        // Check if the location supports the presentation (null means that it supports all presentations).
        $supportedpresentations = $location->get_supported_presentations();
        if ($supportedpresentations !== null && !in_array($presentation->get_key(), $supportedpresentations)) {
            return false;
        }

        return true;
    }

    /**
     * Get the locations as options for select elements.
     *
     * @return string[] The localised location names, keyed by the location keys.
     */
    public static function get_location_options(): array {
        $options = [];
        foreach (self::get_locations() as $key => $provider) {
            $options[$key] = $provider->get_name();
        }

        return $options;
    }

    /**
     * Get the presentations as options for select elements.
     *
     * @return string[] The localised presentation names, keyed by the presentation keys.
     */
    public static function get_presentation_options(): array {
        $options = [];
        foreach (self::get_presentations() as $key => $provider) {
            $options[$key] = $provider->get_name();
        }

        return $options;
    }

    /**
     * Get a fingerprint of the currently available providers.
     * The fingerprint changes as soon as a provider is added or removed (e.g. when a plugin is installed or uninstalled)
     * and is used to invalidate the smart menu caches in this case.
     *
     * @return string
     */
    public static function get_fingerprint(): string {
        $classes = [];
        foreach (self::get_all_providers() as $formprefix => $provider) {
            $classes[$formprefix] = get_class($provider);
        }
        ksort($classes);
        return sha1(json_encode($classes));
    }

    /**
     * Reset the request cache of the registry.
     * This is needed in tests and after plugins have been installed or uninstalled within the same request.
     *
     * @return void
     */
    public static function reset(): void {
        self::$locations = null;
        self::$presentations = null;
    }

    /**
     * Load the built-in providers and collect the providers of other plugins.
     *
     * @return void
     */
    protected static function load(): void {
        if (self::$locations !== null && self::$presentations !== null) {
            return;
        }

        // The built-in providers come first.
        $locations = [new location\main(), new location\menubar(), new location\usermenu()];
        $presentations = [new presentation\plainlist()];

        // Collect the providers of other plugins.
        // During the initial installation of Moodle, the hook manager must not be used yet.
        if (!during_initial_install()) {
            // Create the hook instance which collects the providers.
            $hook = new collect_providers();

            // Add the test providers from the test fixtures to the hook.
            // This is only done in Behat and PHPUnit runs and only if the test providers have been enabled explicitly.
            self::add_test_providers($hook);

            // Dispatch the hook.
            // Each plugin which has registered a callback for the hook in its db/hooks.php file gets the hook instance
            // now and adds its locations and presentations to it.
            \core\di::get(\core\hook\manager::class)->dispatch($hook);

            // Append the collected providers to the built-in providers.
            // The built-in providers remain first, followed by the test providers (if any) and by the providers
            // of the other plugins in the order in which their callbacks have been called.
            $locations = array_merge($locations, $hook->get_locations());
            $presentations = array_merge($presentations, $hook->get_presentations());
        }

        // Index the providers by their keys and remember them for the rest of the request.
        // Locations and presentations are indexed separately, so a location and a presentation may share the same key.
        self::$locations = self::index_providers($locations);
        self::$presentations = self::index_providers($presentations);
    }

    /**
     * Index the given providers by their keys and validate the API versions and the keys.
     * Providers of other plugins which have been built for another version of the provider API or which have invalid
     * or duplicate keys are skipped with a debugging message,
     * as a broken add-on plugin must not break the navigation of the whole site.
     *
     * @param provider[] $providers The providers, built-in providers first.
     * @return provider[] The providers, keyed by their keys.
     */
    protected static function index_providers(array $providers): array {
        $indexed = [];

        // Iterate over all providers.
        foreach ($providers as $provider) {
            // Skip the provider if it has been built for another version of the provider API.
            // A provider which has been built for an older version has not been adapted to the incompatible changes
            // of the provider API yet. A provider which has been built for a newer version expects a provider API
            // which the installed version of Boost Union does not provide yet.
            $apiversion = $provider->get_api_version();
            if ($apiversion !== provider::API_VERSION) {
                debugging('Smart menu provider ' . get_class($provider) . ' has been built for version ' . $apiversion .
                        ' of the provider API, but Boost Union provides version ' . provider::API_VERSION .
                        ', and is ignored.', DEBUG_DEVELOPER);
                continue;
            }

            // Get the key of the provider.
            $key = $provider->get_key();

            // Skip the provider if its key is invalid.
            // The key is stored in the database and is used as part of the form element names,
            // so it has to match the given format.
            if (!preg_match(provider::KEY_REGEX, $key)) {
                debugging('Smart menu provider ' . get_class($provider) . ' has the invalid key "' . $key .
                        '" and is ignored.', DEBUG_DEVELOPER);
                continue;
            }

            // Skip the provider if its key is already used by a provider which has been indexed before.
            // The first provider with a key wins. Built-in providers are added first, so this can only happen
            // for the providers of other plugins and a plugin cannot replace a built-in provider.
            if (isset($indexed[$key])) {
                debugging('Smart menu provider ' . get_class($provider) . ' uses the key "' . $key .
                        '" which is already used by ' . get_class($indexed[$key]) . ' and is ignored.', DEBUG_DEVELOPER);
                continue;
            }

            // Otherwise, remember the provider by its key.
            $indexed[$key] = $provider;
        }

        return $indexed;
    }

    /**
     * Add the test providers from the test fixtures.
     * These providers are only available in Behat and PHPUnit runs and only if they have been enabled explicitly.
     *
     * @param collect_providers $hook The hook to which the providers are added.
     * @return void
     */
    protected static function add_test_providers(collect_providers $hook): void {
        global $CFG;

        // Do nothing if we are neither in a Behat run nor in a PHPUnit run.
        // This makes sure that the test providers can never show up on a production site.
        if (!defined('BEHAT_SITE_RUNNING') && !(defined('PHPUNIT_TEST') && PHPUNIT_TEST)) {
            return;
        }

        // Do nothing if the test providers have not been enabled explicitly by the test.
        // This way, all tests which do not deal with providers run with the built-in providers only.
        if (!get_config('theme_boost_union', 'behat_smartmenu_testproviders')) {
            return;
        }

        // Load the test fixtures manually.
        // They live in the tests directory and are not found by the Moodle autoloader.
        require_once($CFG->dirroot . '/theme/boost_union/tests/fixtures/smartmenu/testlocation.php');
        require_once($CFG->dirroot . '/theme/boost_union/tests/fixtures/smartmenu/testpresentation.php');
        require_once($CFG->dirroot . '/theme/boost_union/tests/fixtures/smartmenu/testinlinelocation.php');
        require_once($CFG->dirroot . '/theme/boost_union/tests/fixtures/smartmenu/testinlinepresentation.php');
        require_once($CFG->dirroot . '/theme/boost_union/tests/fixtures/smartmenu/testsubmenupresentation.php');

        // Add the test location and the test presentation to the hook,
        // in the same way as a plugin would do it in its hook callback.
        $hook->add_location(new \theme_boost_union_fixtures\smartmenu\testlocation());
        $hook->add_presentation(new \theme_boost_union_fixtures\smartmenu\testpresentation());

        // Add the test location and the test presentation which confirm the inline mode support as well.
        // The two providers above rely on the default and do not support the inline mode.
        $hook->add_location(new \theme_boost_union_fixtures\smartmenu\testinlinelocation());
        $hook->add_presentation(new \theme_boost_union_fixtures\smartmenu\testinlinepresentation());

        // Add the test presentation which confirms the support for the submenu mode of menu items as well.
        // The test presentations above rely on the default and do not support the submenu mode of menu items.
        $hook->add_presentation(new \theme_boost_union_fixtures\smartmenu\testsubmenupresentation());
    }
}
