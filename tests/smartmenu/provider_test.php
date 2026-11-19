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

use advanced_testcase;
use theme_boost_union\hook\smartmenu\collect_providers;
use theme_boost_union\smartmenu;
use theme_boost_union\smartmenu_item;

/**
 * Theme Boost Union - Tests for the smart menu location and presentation providers.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class provider_test extends advanced_testcase {
    /**
     * Setup the test.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest();
        provider_registry::reset();
    }

    /**
     * Tear down the test.
     *
     * @return void
     */
    protected function tearDown(): void {
        provider_registry::reset();
        parent::tearDown();
    }

    /**
     * Prepare a test which uses the test providers from the test fixtures.
     *
     * This is not done in setUp() as some tests of this class need the built-in providers only
     * or register the providers on their own.
     *
     * @param bool $withexport Whether the test exports the primary navigation. If so, the test is run as admin
     *                         (to see all smart menus) and the page is prepared for the export.
     * @return \moodle_page|null The prepared page or null if the test does not export the primary navigation.
     */
    protected function prepare_provider_test(bool $withexport = true): ?\moodle_page {
        global $PAGE;

        // Enable the test providers and reset the registry to make sure that they are picked up.
        set_config('behat_smartmenu_testproviders', 1, 'theme_boost_union');
        provider_registry::reset();

        // If the test does not export the primary navigation, we are done.
        if (!$withexport) {
            return null;
        }

        // Become admin to see all smart menus.
        $this->setAdminUser();

        // Use Boost Union as site theme (the theme is resolved as soon as the page is initialised for output).
        set_config('theme', 'boost_union');

        // Set a URL and a context for the page, as it is done by every real page script.
        // The primary navigation and the renderer which the tests use to export the smart menus need both:
        // The URL is used to flag the active menu items and the context is used to check capabilities and to format
        // the menu titles. Without them, Moodle would throw coding exceptions or debugging messages during the export.
        // The site home URL and the system context are used as the tests do not depend on a particular page.
        $PAGE->set_url('/');
        $PAGE->set_context(\context_system::instance());

        // Return the prepared page.
        return $PAGE;
    }

    /**
     * Test that the built-in providers are registered.
     *
     * @covers \theme_boost_union\smartmenu\provider_registry::get_locations
     * @covers \theme_boost_union\smartmenu\provider_registry::get_presentations
     * @covers \theme_boost_union\smartmenu\provider_registry::get_fallback_presentation
     * @return void
     */
    public function test_builtin_providers(): void {
        // The built-in providers come first (other installed plugins may have added further providers after them).
        $this->assertSame(['main', 'menu', 'user'], array_slice(array_keys(provider_registry::get_locations()), 0, 3));
        $this->assertSame(['list'], array_slice(array_keys(provider_registry::get_presentations()), 0, 1));
        $this->assertSame('list', provider_registry::get_fallback_presentation()->get_key());

        // The options of the smart menu class are built from the providers.
        $this->assertSame(
            [
                smartmenu::LOCATION_MAIN => 'Main navigation',
                smartmenu::LOCATION_MENU => 'Menu bar',
                smartmenu::LOCATION_USER => 'User menu',
            ],
            array_slice(provider_registry::get_location_options(), 0, 3, true)
        );
        $this->assertSame('List', provider_registry::get_presentation_options()[smartmenu::PRESENTATION_LIST]);

        // Providers which do not exist are not returned.
        $this->assertNull(provider_registry::get_location('doesnotexist'));
        $this->assertNull(provider_registry::get_presentation('doesnotexist'));
    }

    /**
     * Test that other plugins can add providers with the hook and that broken providers are ignored.
     *
     * @covers \theme_boost_union\smartmenu\provider_registry::load
     * @covers \theme_boost_union\smartmenu\provider_registry::index_providers
     * @covers \theme_boost_union\smartmenu\provider_registry::get_fingerprint
     * @covers \theme_boost_union\hook\smartmenu\collect_providers
     * @return void
     */
    public function test_hook_providers(): void {
        global $CFG;
        require_once($CFG->dirroot . '/theme/boost_union/tests/fixtures/smartmenu/testlocation.php');
        require_once($CFG->dirroot . '/theme/boost_union/tests/fixtures/smartmenu/testpresentation.php');

        $fingerprintbefore = provider_registry::get_fingerprint();
        provider_registry::reset();

        // A presentation with an invalid key.
        $invalid = new class extends \theme_boost_union_fixtures\smartmenu\testpresentation {
            /**
             * Get the key of the provider.
             *
             * @return string
             */
            public function get_key(): string {
                return 'invalid_key';
            }
        };
        // A presentation which tries to replace the built-in list presentation.
        $duplicate = new class extends \theme_boost_union_fixtures\smartmenu\testpresentation {
            /**
             * Get the key of the provider.
             *
             * @return string
             */
            public function get_key(): string {
                return 'list';
            }
        };

        // A presentation which has been built for an older version of the provider API.
        $outdated = new class extends \theme_boost_union_fixtures\smartmenu\testpresentation {
            /**
             * Get the key of the provider.
             *
             * @return string
             */
            public function get_key(): string {
                return 'outdated';
            }

            /**
             * Get the version of the provider API which this provider has been built for.
             *
             * @return int
             */
            public function get_api_version(): int {
                return self::API_VERSION - 1;
            }
        };
        // A presentation which has been built for a newer version of the provider API.
        $toonew = new class extends \theme_boost_union_fixtures\smartmenu\testpresentation {
            /**
             * Get the key of the provider.
             *
             * @return string
             */
            public function get_key(): string {
                return 'toonew';
            }

            /**
             * Get the version of the provider API which this provider has been built for.
             *
             * @return int
             */
            public function get_api_version(): int {
                return self::API_VERSION + 1;
            }
        };

        $this->redirectHook(collect_providers::class, function (collect_providers $hook) use (
            $invalid,
            $duplicate,
            $outdated,
            $toonew
        ): void {
            $hook->add_location(new \theme_boost_union_fixtures\smartmenu\testlocation());
            $hook->add_presentation(new \theme_boost_union_fixtures\smartmenu\testpresentation());
            $hook->add_presentation($invalid);
            $hook->add_presentation($duplicate);
            $hook->add_presentation($outdated);
            $hook->add_presentation($toonew);
        });

        // The valid providers are added after the built-in ones, the broken ones are ignored.
        $this->assertSame(['main', 'menu', 'user', 'testlocation'], array_keys(provider_registry::get_locations()));
        $this->assertSame(['list', 'testpresentation'], array_keys(provider_registry::get_presentations()));
        $this->assertInstanceOf(presentation\plainlist::class, provider_registry::get_presentation('list'));
        $this->assertDebuggingCalledCount(4);

        // The fingerprint of the providers has changed.
        $this->assertNotSame($fingerprintbefore, provider_registry::get_fingerprint());
    }

    /**
     * Test the naming of the form elements and the data fields of the providers.
     *
     * @covers \theme_boost_union\smartmenu\provider::field_name
     * @covers \theme_boost_union\smartmenu\provider::get_form_prefix
     * @covers \theme_boost_union\smartmenu\provider::get_data_field
     * @covers \theme_boost_union\smartmenu\provider::get_data
     * @covers \theme_boost_union\smartmenu\provider::menu_form_prepare_data
     * @covers \theme_boost_union\smartmenu\provider::menu_form_process_data
     * @return void
     */
    public function test_provider_field_names(): void {
        $this->prepare_provider_test(false);

        $location = provider_registry::get_location('testlocation');
        $presentation = provider_registry::get_presentation('testpresentation');

        $this->assertSame('loctestlocation', $location->get_form_prefix());
        $this->assertSame('loctestlocation_label', $location->field_name('label'));
        $this->assertSame('prestestpresentation', $presentation->get_form_prefix());
        $this->assertSame('prestestpresentation_boxcolor', $presentation->field_name('boxcolor'));

        // The settings of the locations and of the presentations are stored in separate fields.
        $this->assertSame('locationdata', $location->get_data_field());
        $this->assertSame('presentationdata', $presentation->get_data_field());
        $record = (object) [
            'locationdata' => '{"testlocation": {"label": "My label"}}',
            'presentationdata' => ['testpresentation' => ['boxcolor' => 'green']],
        ];
        $this->assertSame(['label' => 'My label'], $location->get_data($record));
        $this->assertSame(['boxcolor' => 'green'], $presentation->get_data($record));
        $this->assertSame([], $presentation->get_data(null));

        // The stored settings are mapped to the form elements and back.
        $this->assertSame(
            ['prestestpresentation_boxcolor' => 'green'],
            $presentation->menu_form_prepare_data((object) ['id' => 0], ['boxcolor' => 'green'])
        );
        $formdata = (object) [
            'title' => 'Menu',
            'prestestpresentation_boxcolor' => 'green',
            'loctestlocation_label' => 'Label',
        ];
        $this->assertSame(['boxcolor' => 'green'], $presentation->menu_form_process_data($formdata, 1));
        $this->assertSame(['label' => 'Label'], $location->menu_form_process_data($formdata, 1));
    }

    /**
     * Build the form data of a menu.
     *
     * @param array $overrides The values to override.
     * @return \stdClass
     */
    protected function get_menu_formdata(array $overrides = []): \stdClass {
        return (object) array_merge([
            'title' => 'Provider menu',
            'description' => ['text' => 'Description', 'format' => FORMAT_HTML],
            'showdesc' => smartmenu::DESC_NEVER,
            'location' => [smartmenu::LOCATION_MAIN],
            'presentation' => smartmenu::PRESENTATION_LIST,
            'mode' => smartmenu::MODE_SUBMENU,
            'moremenubehavior' => smartmenu::MOREMENU_DONOTCHANGE,
            'roles' => [],
            'cohorts' => [],
            'languages' => [],
            'visible' => 1,
        ], $overrides);
    }

    /**
     * Test that the settings of the providers are stored when a menu is saved with the edit form.
     *
     * @covers \theme_boost_union\smartmenu::manage_instance
     * @covers \theme_boost_union\smartmenu::add_provider_form_data
     * @covers \theme_boost_union\smartmenu::duplicate
     * @return void
     */
    public function test_menu_providerdata(): void {
        global $DB;

        $this->prepare_provider_test(false);

        // Create a menu with the test presentation at the main navigation and at the test location.
        $menuid = smartmenu::manage_instance($this->get_menu_formdata([
            'location' => [smartmenu::LOCATION_MAIN, 'testlocation'],
            'presentation' => 'testpresentation',
            'prestestpresentation_boxcolor' => 'green',
            'loctestlocation_label' => 'My label',
        ]), true);

        $menu = smartmenu::get_menu($menuid);
        $this->assertSame(['main', 'testlocation'], $menu->location);
        $this->assertSame('testpresentation', $menu->presentation);
        $this->assertSame('green', $menu->presentationdata['testpresentation']['boxcolor']);
        $this->assertSame('My label', $menu->locationdata['testlocation']['label']);
        // The built-in providers do not have any settings.
        $this->assertSame([], $menu->locationdata['main']);

        // The stored settings are prepared for the edit form.
        $formdata = smartmenu::add_provider_form_data(smartmenu::get_menu($menuid));
        $this->assertSame('green', $formdata->prestestpresentation_boxcolor);
        $this->assertSame('My label', $formdata->loctestlocation_label);

        // Switch the menu to the list presentation and remove it from the test location.
        // The settings of the providers which are not used anymore are kept.
        smartmenu::manage_instance($this->get_menu_formdata([
            'id' => $menuid,
            'location' => [smartmenu::LOCATION_MAIN],
            'presentation' => smartmenu::PRESENTATION_LIST,
            'prestestpresentation_boxcolor' => 'red',
            'loctestlocation_label' => 'Changed label',
        ]), true);
        $menu = smartmenu::get_menu($menuid);
        $this->assertSame(['main'], $menu->location);
        $this->assertSame('list', $menu->presentation);
        $this->assertSame('green', $menu->presentationdata['testpresentation']['boxcolor']);
        $this->assertSame('My label', $menu->locationdata['testlocation']['label']);

        // Updating a menu without processing the providers does not touch the stored settings.
        smartmenu::manage_instance($this->get_menu_formdata(['id' => $menuid, 'title' => 'Renamed menu']));
        $menu = smartmenu::get_menu($menuid);
        $this->assertSame('Renamed menu', $menu->title);
        $this->assertSame('green', $menu->presentationdata['testpresentation']['boxcolor']);
        $this->assertSame('My label', $menu->locationdata['testlocation']['label']);

        // Duplicating the menu copies the stored settings.
        smartmenu::instance($menuid)->duplicate();
        $duplicate = $DB->get_record_select('theme_boost_union_menus', 'id <> :id', ['id' => $menuid], '*', MUST_EXIST);
        $this->assertSame(
            'green',
            smartmenu::decode_providerdata($duplicate->presentationdata)['testpresentation']['boxcolor']
        );
        $this->assertSame(
            'My label',
            smartmenu::decode_providerdata($duplicate->locationdata)['testlocation']['label']
        );
    }

    /**
     * Test that the settings of the providers are stored when a menu item is saved with the edit form.
     *
     * @covers \theme_boost_union\smartmenu_item::manage_instance
     * @covers \theme_boost_union\smartmenu_item::add_provider_form_data
     * @return void
     */
    public function test_item_providerdata(): void {
        $this->prepare_provider_test(false);

        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $menu = $generator->create_smartmenu([
            'title' => 'Provider menu',
            'location' => [smartmenu::LOCATION_MAIN],
            'presentation' => 'testpresentation',
        ]);

        $itemid = smartmenu_item::manage_instance((object) [
            'title' => 'Provider item',
            'menu' => $menu->id,
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.org',
            'mode' => smartmenu_item::MODE_INLINE,
            'sortorder' => 1,
            'category' => [],
            'enrolmentrole' => [],
            'completionstatus' => [],
            'daterange' => [],
            'roles' => [],
            'cohorts' => [],
            'languages' => [],
            'prestestpresentation_note' => 'My note',
        ], true);
        $this->assertGreaterThan(0, $itemid);

        $item = smartmenu_item::instance($itemid)->get_item();
        $this->assertSame('My note', $item->presentationdata['testpresentation']['note']);

        // The stored settings are prepared for the edit form.
        $formdata = smartmenu_item::add_provider_form_data(smartmenu::get_menu($menu->id), $item);
        $this->assertSame('My note', $formdata->prestestpresentation_note);

        // The presentation decorates the item node with the stored settings.
        $this->setAdminUser();
        $nodes = smartmenu_item::instance($itemid)->build();
        $this->assertSame('My note', $nodes[0]['testnote']);
    }

    /**
     * Test that the locations render their menus and that the presentations render the content of the menus.
     *
     * @covers \theme_boost_union\output\navigation\primary::export_for_template
     * @covers \theme_boost_union\output\navigation\primary::apply_presentation_content
     * @covers \theme_boost_union\output\navigation\primary::attach_to_user_menu
     * @return void
     */
    public function test_export_with_providers(): void {
        global $DB;

        $page = $this->prepare_provider_test();

        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $menu = $generator->create_smartmenu([
            'title' => 'Boxes menu',
            'location' => [smartmenu::LOCATION_MAIN, smartmenu::LOCATION_MENU, smartmenu::LOCATION_USER, 'testlocation'],
            'presentation' => 'testpresentation',
            'locationdata' => ['testlocation' => ['label' => 'My label']],
            'presentationdata' => ['testpresentation' => ['boxcolor' => 'green']],
        ]);
        $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'Boxed item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.org',
            'presentationdata' => ['testpresentation' => ['note' => 'My note']],
        ]);
        $listmenu = $generator->create_smartmenu([
            'title' => 'List menu',
            'location' => [smartmenu::LOCATION_MAIN],
        ]);
        // Additionally, assign the menu to a location which does not exist (anymore).
        $DB->set_field('theme_boost_union_menus', 'location', json_encode(['main', 'doesnotexist']), ['id' => $listmenu->id]);
        \cache::make('theme_boost_union', 'smartmenus')->purge();
        $generator->create_smartmenu_item([
            'menu' => $listmenu->id,
            'title' => 'List item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.com',
        ]);

        $primary = new \theme_boost_union\output\navigation\primary($page);
        $export = $primary->export_for_template($page->get_renderer('core'));

        // The body classes of the smart menus and of the test location are exported.
        $this->assertSame(['theme-boost-union-smartmenu', 'theme-boost-union-testlocation'], $export['bodyclasses']);
        $this->assertTrue($export['includesmartmenu']);

        // In the main navigation, the menu with the test presentation carries the content which has been rendered
        // by the presentation and the class which has been added by the presentation.
        $items = json_decode($export['moremenu']['reactprops'], true)['items'];
        $boxes = array_values(array_filter($items, fn($item) => strip_tags($item['text']) === 'Boxes menu'));
        $this->assertCount(1, $boxes);
        $this->assertStringContainsString('testpresentation-boxes', $boxes[0]['contenthtml']);
        $this->assertStringContainsString('data-location="main"', $boxes[0]['contenthtml']);
        $this->assertStringContainsString('My note', $boxes[0]['contenthtml']);
        $this->assertContains('testpresentation-green', $boxes[0]['wrapperclasses']);

        // The menu with the list presentation does not carry any content.
        $list = array_values(array_filter($items, fn($item) => strip_tags($item['text']) === 'List menu'));
        $this->assertCount(1, $list);
        $this->assertNull($list[0]['contenthtml']);
        $this->assertCount(1, $list[0]['children']);

        // In the mobile primary navigation (which is the main navigation in the drawer on small screens), the menus are
        // always shown as list. So, the menu with the test presentation does not carry the content there, but its items.
        $mobilenodes = array_map(fn($node) => (array) $node, $export['mobileprimarynav']);
        $mobileboxes = array_values(array_filter($mobilenodes, fn($node) => strip_tags($node['text']) === 'Boxes menu'));
        $this->assertCount(1, $mobileboxes);
        $this->assertTrue(empty($mobileboxes[0]['contenthtml']));
        $this->assertCount(1, $mobileboxes[0]['children']);
        $mobilelist = array_values(array_filter($mobilenodes, fn($node) => strip_tags($node['text']) === 'List menu'));
        $this->assertCount(1, $mobilelist);
        $this->assertTrue(empty($mobilelist[0]['contenthtml']));
        $this->assertCount(1, $mobilelist[0]['children']);

        // In the menu bar, the test presentation is not supported and the menu is shown as list.
        $this->assertStringContainsString('boost-union-menubar', $export['slots']['navbar_above']);
        $this->assertArrayNotHasKey('menubar', $export);
        preg_match('/data-react-props=(["\'])(.*?)\1/s', $export['slots']['navbar_above'], $matches);
        $menubaritems = json_decode(html_entity_decode($matches[2], ENT_QUOTES | ENT_HTML5, 'UTF-8'), true)['items'];
        $this->assertCount(1, $menubaritems);
        $this->assertNull($menubaritems[0]['contenthtml']);

        // In the user menu, the menus are always shown as list. So, the submenu of the menu with the test presentation
        // does not carry the content there, but its items.
        $submenus = array_values(array_filter($export['user']['submenus'], fn($submenu) => $submenu->title === 'Boxes menu'));
        $this->assertCount(1, $submenus);
        $this->assertFalse(isset($submenus[0]->contenthtml));
        $this->assertCount(1, $submenus[0]->items);

        // The test location rendered the menu into its slot.
        $this->assertStringContainsString('theme-boost-union-testlocation-menus', $export['slots']['body_end']);
        $this->assertStringContainsString('My label', $export['slots']['body_end']);
        $this->assertStringContainsString('data-location="testlocation"', $export['slots']['body_end']);
        $this->assertSame('', $export['slots']['footer_above']);

        // If the test providers are not available anymore, the menu is shown as list and is not shown at the
        // test location anymore.
        unset_config('behat_smartmenu_testproviders', 'theme_boost_union');
        provider_registry::reset();
        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));
        $items = json_decode($export['moremenu']['reactprops'], true)['items'];
        $boxes = array_values(array_filter($items, fn($item) => strip_tags($item['text']) === 'Boxes menu'));
        $this->assertCount(1, $boxes);
        $this->assertNull($boxes[0]['contenthtml']);
        $this->assertCount(1, $boxes[0]['children']);
        $this->assertNotContains('testpresentation-green', $boxes[0]['wrapperclasses']);
        $this->assertSame('', $export['slots']['body_end']);
        $this->assertSame(['theme-boost-union-smartmenu'], $export['bodyclasses']);
    }

    /**
     * Test that the active items are flagged before the presentations render the content of the menus
     * in the main navigation.
     *
     * @covers \theme_boost_union\output\navigation\primary::build_primary_navigation
     * @return void
     */
    public function test_export_flags_active_items_for_presentations(): void {
        global $CFG, $FULLME;

        $page = $this->prepare_provider_test();

        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $menu = $generator->create_smartmenu([
            'title' => 'Boxes menu',
            'location' => [smartmenu::LOCATION_MAIN],
            'presentation' => 'testpresentation',
        ]);
        $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'Current item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => $CFG->wwwroot . '/theme/boost_union/current.php',
        ]);
        $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'Other item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => $CFG->wwwroot . '/theme/boost_union/other.php',
        ]);

        // Pretend that the page to which the first item links is the current page.
        $oldfullme = $FULLME;
        $FULLME = $CFG->wwwroot . '/theme/boost_union/current.php';
        try {
            $primary = new \theme_boost_union\output\navigation\primary($page);
            $export = $primary->export_for_template($page->get_renderer('core'));
        } finally {
            $FULLME = $oldfullme;
        }

        // The content which has been rendered by the presentation highlights the first item only.
        $items = json_decode($export['moremenu']['reactprops'], true)['items'];
        $boxes = array_values(array_filter($items, fn($item) => strip_tags($item['text']) === 'Boxes menu'));
        $this->assertCount(1, $boxes);
        $this->assertSame(1, substr_count($boxes[0]['contenthtml'], 'testpresentation-box-active'));
        $this->assertMatchesRegularExpression(
            '/testpresentation-box-active"><a[^>]*>(?:(?!<\/a>).)*Current item/',
            $boxes[0]['contenthtml']
        );
    }

    /**
     * Test that a broken presentation and a broken location do not break the primary navigation.
     *
     * @covers \theme_boost_union\output\navigation\primary::export_for_template
     * @covers \theme_boost_union\output\navigation\primary::apply_presentation_content
     * @return void
     */
    public function test_export_with_broken_providers(): void {
        $page = $this->prepare_provider_test();

        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $menu = $generator->create_smartmenu([
            'title' => 'Boxes menu',
            'location' => [smartmenu::LOCATION_MAIN, 'testlocation'],
            'presentation' => 'testpresentation',
        ]);
        $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'Boxed item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.org',
        ]);

        // If the presentation fails to render the content of the menu, the menu is shown as list
        // (in the main navigation and at the test location which lets the presentation render the content as well).
        set_config('behat_smartmenu_testpresentation_broken', 1, 'theme_boost_union');
        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));
        $this->assertDebuggingCalledCount(2);
        $items = json_decode($export['moremenu']['reactprops'], true)['items'];
        $boxes = array_values(array_filter($items, fn($item) => strip_tags($item['text']) === 'Boxes menu'));
        $this->assertCount(1, $boxes);
        $this->assertNull($boxes[0]['contenthtml']);
        $this->assertCount(1, $boxes[0]['children']);
        $this->assertStringContainsString('Boxed item', $export['slots']['body_end']);
        $this->assertStringNotContainsString('testpresentation-boxes', $export['slots']['body_end']);
        unset_config('behat_smartmenu_testpresentation_broken', 'theme_boost_union');

        // If the location fails to render its menus, the location is skipped and the menu is still shown
        // in the main navigation.
        set_config('behat_smartmenu_testlocation_broken', 1, 'theme_boost_union');
        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));
        $this->assertDebuggingCalledCount(1);
        $this->assertSame('', $export['slots']['body_end']);
        $this->assertSame(['theme-boost-union-smartmenu'], $export['bodyclasses']);
        $items = json_decode($export['moremenu']['reactprops'], true)['items'];
        $boxes = array_values(array_filter($items, fn($item) => strip_tags($item['text']) === 'Boxes menu'));
        $this->assertCount(1, $boxes);
        $this->assertStringContainsString('testpresentation-boxes', $boxes[0]['contenthtml']);
    }

    /**
     * Test that the cached item nodes of a menu are rebuilt when the menu is saved.
     *
     * @covers \theme_boost_union\smartmenu::manage_instance
     * @return void
     */
    public function test_menu_update_purges_item_cache(): void {
        $this->prepare_provider_test(false);
        $this->setAdminUser();

        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $menu = $generator->create_smartmenu([
            'title' => 'Boxes menu',
            'location' => [smartmenu::LOCATION_MAIN],
            'presentation' => 'testpresentation',
        ]);
        $item = $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'Boxed item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.org',
            'presentationdata' => ['testpresentation' => ['note' => 'My note']],
        ]);

        // Build the item. The item node is decorated by the test presentation and is cached.
        $nodes = smartmenu_item::instance($item->id)->build();
        $this->assertSame('My note', $nodes[0]['testnote']);

        // Switch the menu to the list presentation.
        smartmenu::manage_instance($this->get_menu_formdata([
            'id' => $menu->id,
            'presentation' => smartmenu::PRESENTATION_LIST,
        ]), true);

        // The item node is rebuilt and is not decorated by the test presentation anymore.
        $nodes = smartmenu_item::instance($item->id)->build();
        $this->assertArrayNotHasKey('testnote', $nodes[0]);
    }

    /**
     * Test that the purges of the smart menu caches because of a changed set of providers are limited.
     *
     * @covers \theme_boost_union\smartmenu::build_smartmenu
     * @return void
     */
    public function test_provider_fingerprint_purge_limit(): void {
        $this->prepare_provider_test(false);
        $this->setAdminUser();

        $cache = \cache::make('theme_boost_union', 'smartmenus');
        $cache->purge();

        // The first build stores the fingerprint of the providers.
        smartmenu::build_smartmenu();
        $this->assertSame(provider_registry::get_fingerprint(), $cache->get(smartmenu::CACHE_PROVIDERFINGERPRINT));

        // Change the set of providers again and again, like web servers which do not agree on the set of providers.
        // The caches are purged and the fingerprint is updated until the limit is reached.
        $enabled = true;
        for ($purge = 2; $purge <= smartmenu::PROVIDERFINGERPRINT_MAXPURGES; $purge++) {
            $enabled = !$enabled;
            set_config('behat_smartmenu_testproviders', (int) $enabled, 'theme_boost_union');
            provider_registry::reset();
            smartmenu::build_smartmenu();
            $this->assertSame(provider_registry::get_fingerprint(), $cache->get(smartmenu::CACHE_PROVIDERFINGERPRINT));
        }

        // From then on, the caches are not purged anymore and the fingerprint is kept.
        $enabled = !$enabled;
        set_config('behat_smartmenu_testproviders', (int) $enabled, 'theme_boost_union');
        provider_registry::reset();
        $cache->set('purgemarker', 1);
        smartmenu::build_smartmenu();
        $this->assertNotSame(provider_registry::get_fingerprint(), $cache->get(smartmenu::CACHE_PROVIDERFINGERPRINT));
        $this->assertSame(1, $cache->get('purgemarker'));

        // As soon as the interval is over, the caches are purged again.
        $purges = $cache->get(smartmenu::CACHE_PROVIDERFINGERPRINTPURGES);
        $this->assertSame(smartmenu::PROVIDERFINGERPRINT_MAXPURGES, $purges['count']);
        $purges['start'] -= smartmenu::PROVIDERFINGERPRINT_PURGEINTERVAL;
        $cache->set(smartmenu::CACHE_PROVIDERFINGERPRINTPURGES, $purges);
        smartmenu::build_smartmenu();
        $this->assertSame(provider_registry::get_fingerprint(), $cache->get(smartmenu::CACHE_PROVIDERFINGERPRINT));
        $this->assertFalse($cache->get('purgemarker'));
    }

    /**
     * Test that the form elements of a location are hidden in the smart menu edit form as long as only other locations
     * are selected, including locations which do not exist (anymore).
     *
     * @covers \theme_boost_union\smartmenu\location\base::hide_unless_selected
     * @return void
     */
    public function test_location_form_elements_hidden_with_unknown_location(): void {
        global $DB;

        $this->prepare_provider_test(false);
        $this->setAdminUser();

        // Build the edit form for a menu which is assigned to the main navigation and to a location which does not exist.
        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $menu = $generator->create_smartmenu(['title' => 'Menu', 'location' => [smartmenu::LOCATION_MAIN]]);
        $DB->set_field('theme_boost_union_menus', 'location', json_encode(['main', 'doesnotexist']), ['id' => $menu->id]);
        $menu = $DB->get_record('theme_boost_union_menus', ['id' => $menu->id], '*', MUST_EXIST);
        $form = new \theme_boost_union\form\smartmenu_edit_form(null, ['id' => $menu->id, 'menu' => $menu]);
        $mform = (new \ReflectionProperty(\moodleform::class, '_form'))->getValue($form);

        // Get the locations for which the label of the test location is hidden.
        $hideifs = (new \ReflectionProperty(\MoodleQuickForm::class, '_hideifs'))->getValue($mform);
        $rules = array_filter(
            $hideifs['location[]']['in'],
            fn($elements) => in_array('loctestlocation_label', $elements)
        );
        $this->assertCount(1, $rules);
        $hiddenfor = explode('|', array_key_first($rules));

        // The label is hidden if other locations are selected, including the location which does not exist.
        $this->assertContains('main', $hiddenfor);
        $this->assertContains('doesnotexist', $hiddenfor);
        $this->assertContains('', $hiddenfor);
        $this->assertNotContains('testlocation', $hiddenfor);
    }

    /**
     * Test that a menu is shown as list at a location which does not support the presentation of the menu.
     *
     * @covers \theme_boost_union\smartmenu\provider_registry::is_supported_combination
     * @covers \theme_boost_union\output\navigation\primary::apply_presentation_content
     * @return void
     */
    public function test_export_with_location_restriction(): void {
        $page = $this->prepare_provider_test();

        $testpresentation = provider_registry::get_presentation('testpresentation');
        $listpresentation = provider_registry::get_presentation('list');
        $testlocation = provider_registry::get_location('testlocation');
        $mainlocation = provider_registry::get_location('main');
        $menubarlocation = provider_registry::get_location('menu');

        // By default, the test location supports all presentations.
        // The test presentation does not support the menu bar.
        $this->assertTrue(provider_registry::is_supported_combination($testpresentation, $testlocation));
        $this->assertTrue(provider_registry::is_supported_combination($testpresentation, $mainlocation));
        $this->assertFalse(provider_registry::is_supported_combination($testpresentation, $menubarlocation));
        $this->assertTrue(provider_registry::is_supported_combination($listpresentation, $menubarlocation));

        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $menu = $generator->create_smartmenu([
            'title' => 'Boxes menu',
            'location' => [smartmenu::LOCATION_MAIN, 'testlocation'],
            'presentation' => 'testpresentation',
        ]);
        $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'Boxed item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.org',
        ]);

        // The menu is rendered by the test presentation at the test location.
        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));
        $this->assertStringContainsString('data-location="testlocation"', $export['slots']['body_end']);

        // Restrict the test location to the list presentation.
        set_config('behat_smartmenu_testlocation_listonly', 1, 'theme_boost_union');
        $this->assertFalse(provider_registry::is_supported_combination($testpresentation, $testlocation));
        $this->assertTrue(provider_registry::is_supported_combination($listpresentation, $testlocation));
        $this->assertTrue(provider_registry::is_supported_combination($testpresentation, $mainlocation));

        // The menu is shown as list at the test location now, but it is still rendered by the test presentation
        // in the main navigation.
        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));
        $this->assertStringContainsString('Boxes menu', $export['slots']['body_end']);
        $this->assertStringContainsString('Boxed item', $export['slots']['body_end']);
        $this->assertStringNotContainsString('testpresentation-boxes', $export['slots']['body_end']);
        $items = json_decode($export['moremenu']['reactprops'], true)['items'];
        $boxes = array_values(array_filter($items, fn($item) => strip_tags($item['text']) === 'Boxes menu'));
        $this->assertCount(1, $boxes);
        $this->assertStringContainsString('data-location="main"', $boxes[0]['contenthtml']);
    }

    /**
     * Test that the locations and the presentations are asked for their AMD modules with the nodes which they render.
     *
     * @covers \theme_boost_union\output\navigation\primary::export_for_template
     * @covers \theme_boost_union\output\navigation\primary::group_nodes_by_presentation
     * @return void
     */
    public function test_export_amd_modules(): void {
        $page = $this->prepare_provider_test();

        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');

        // Create a menu with the test presentation and a menu with the list presentation,
        // both in the main navigation and at the test location.
        // Additionally, create two menus with the test presentation in the menu bar which does not support it.
        $menus = [
            'Boxes menu' => ['presentation' => 'testpresentation', 'location' => [smartmenu::LOCATION_MAIN, 'testlocation']],
            'List menu' => ['presentation' => 'list', 'location' => [smartmenu::LOCATION_MAIN, 'testlocation']],
            'Menu bar boxes menu 1' => ['presentation' => 'testpresentation', 'location' => [smartmenu::LOCATION_MENU]],
            'Menu bar boxes menu 2' => ['presentation' => 'testpresentation', 'location' => [smartmenu::LOCATION_MENU]],
        ];
        foreach ($menus as $title => $settings) {
            $menu = $generator->create_smartmenu(['title' => $title] + $settings);
            $generator->create_smartmenu_item([
                'menu' => $menu->id,
                'title' => $title . ' item',
                'type' => smartmenu_item::TYPESTATIC,
                'url' => 'https://moodle.org',
            ]);
        }

        // The test providers do not have any AMD modules by default.
        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));
        $this->assertSame([], $export['amdmodules']);

        // Let the test providers return AMD modules whose names contain the number of the nodes which they were given.
        set_config('behat_smartmenu_testpresentation_amd', 1, 'theme_boost_union');
        set_config('behat_smartmenu_testlocation_amd', 1, 'theme_boost_union');
        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));

        // The test presentation is asked with its own menu only (in the main navigation and at the test location,
        // which results in the same module), but not with the menu with the list presentation next to it. And it is
        // not asked at all for the two menus in the menu bar as they are shown as list there.
        // The test location is asked with both of its menus, regardless of their presentations.
        $this->assertSame(
            ['theme_boost_union/testpresentation_1nodes', 'theme_boost_union/testlocation_2nodes'],
            $export['amdmodules']
        );
    }

    /**
     * Test that a location only works within its own scope: The data which the primary navigation exports to the
     * layouts is not exposed by the location context, and the context functions which modify the navigation structures
     * of the built-in locations are reserved for these locations.
     *
     * @covers \theme_boost_union\smartmenu\location\context::add_slot_html
     * @covers \theme_boost_union\smartmenu\location\context::set_primary_navigation
     * @covers \theme_boost_union\smartmenu\location\context::attach_to_user_menu
     * @return void
     */
    public function test_location_context_scope(): void {
        $page = $this->prepare_provider_test();
        $output = $page->get_renderer('core');
        $primary = new \theme_boost_union\output\navigation\primary($page);

        // Helper to create the export data and a location context for a given location.
        $createcontext = function (string $locationkey) use ($primary, $output, $page): array {
            $export = (object) [
                'moremenu' => null,
                'mobileprimarynav' => null,
                'user' => ['items' => []],
                'slots' => array_fill_keys(location\base::SLOTS, ''),
            ];
            return [$export, new location\context([], $primary, $output, $page, $export, $locationkey)];
        };

        // The export data is not exposed by the context.
        [$export, $context] = $createcontext('testlocation');
        $this->assertFalse((new \ReflectionProperty($context, 'export'))->isPublic());

        // Each location can add HTML to the template slots, but only to the existing ones.
        $context->add_slot_html(location\base::SLOT_BODY_END, '<p>Test</p>');
        $this->assertSame('<p>Test</p>', $export->slots[location\base::SLOT_BODY_END]);
        try {
            $context->add_slot_html('doesnotexist', '<p>Test</p>');
            $this->fail('A coding exception was expected for an unknown slot.');
        } catch (\coding_exception $e) {
            $this->assertStringContainsString('Unknown smart menu template slot', $e->getMessage());
        }

        // The main navigation location can set the main navigation.
        [$export, $context] = $createcontext(smartmenu::LOCATION_MAIN);
        $context->set_primary_navigation(['nodearray' => []], [['text' => 'Mobile']]);
        $this->assertSame(['nodearray' => []], $export->moremenu);
        $this->assertSame([['text' => 'Mobile']], $export->mobileprimarynav);

        // The user menu location can attach nodes to the user menu.
        [$export, $context] = $createcontext(smartmenu::LOCATION_USER);
        $context->attach_to_user_menu([(object) ['text' => 'Node', 'title' => 'Node']]);
        $this->assertContains('Node', array_map(fn($item) => ((object) $item)->text ?? null, $export->user['items']));

        // Each location is refused when it tries to modify the navigation structures of another location.
        $refused = [
            'testlocation' => ['set_primary_navigation', 'attach_to_user_menu'],
            smartmenu::LOCATION_MENU => ['set_primary_navigation', 'attach_to_user_menu'],
            smartmenu::LOCATION_MAIN => ['attach_to_user_menu'],
            smartmenu::LOCATION_USER => ['set_primary_navigation'],
        ];
        foreach ($refused as $locationkey => $functions) {
            foreach ($functions as $function) {
                [$export, $context] = $createcontext($locationkey);
                try {
                    if ($function === 'set_primary_navigation') {
                        $context->set_primary_navigation(['nodearray' => []], []);
                    } else {
                        $context->attach_to_user_menu([(object) ['text' => 'Node', 'title' => 'Node']]);
                    }
                    $this->fail('A coding exception was expected for ' . $locationkey . ' calling ' . $function . '().');
                } catch (\coding_exception $e) {
                    $this->assertStringContainsString('is not allowed to modify', $e->getMessage());
                }

                // The export data was not modified.
                $this->assertNull($export->moremenu);
                $this->assertNull($export->mobileprimarynav);
                $this->assertSame(['items' => []], $export->user);
            }
        }
    }

    /**
     * Test which providers support the inline mode and that menus with a presentation which does not support
     * the inline mode are always shown as submenu.
     *
     * @covers \theme_boost_union\smartmenu\location\base::menu_supports_inline_mode
     * @covers \theme_boost_union\smartmenu\presentation\base::menu_supports_inline_mode
     * @covers \theme_boost_union\smartmenu::build
     * @return void
     */
    public function test_inline_mode_support(): void {
        $page = $this->prepare_provider_test();

        // The built-in providers confirm that they support the inline mode. The test providers do not overwrite
        // menu_supports_inline_mode(), so they prove that the inline mode is not supported by default.
        $this->assertTrue(provider_registry::get_location('main')->menu_supports_inline_mode());
        $this->assertTrue(provider_registry::get_location('menu')->menu_supports_inline_mode());
        $this->assertTrue(provider_registry::get_location('user')->menu_supports_inline_mode());
        $this->assertTrue(provider_registry::get_presentation('list')->menu_supports_inline_mode());
        $this->assertFalse(provider_registry::get_location('testlocation')->menu_supports_inline_mode());
        $this->assertFalse(provider_registry::get_presentation('testpresentation')->menu_supports_inline_mode());

        // Create a menu in inline mode with the list presentation and a menu in inline mode with the test presentation.
        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $listmenu = $generator->create_smartmenu([
            'title' => 'Inline list menu',
            'location' => [smartmenu::LOCATION_MAIN],
            'mode' => smartmenu::MODE_INLINE,
        ]);
        $generator->create_smartmenu_item([
            'menu' => $listmenu->id,
            'title' => 'Inline list item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.org',
        ]);
        $boxesmenu = $generator->create_smartmenu([
            'title' => 'Inline boxes menu',
            'location' => [smartmenu::LOCATION_MAIN],
            'presentation' => 'testpresentation',
            'mode' => smartmenu::MODE_INLINE,
        ]);
        $generator->create_smartmenu_item([
            'menu' => $boxesmenu->id,
            'title' => 'Inline boxes item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.com',
        ]);

        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));
        $items = json_decode($export['moremenu']['reactprops'], true)['items'];
        $texts = array_map(fn($item) => strip_tags($item['text']), $items);

        // The item of the menu with the list presentation is shown directly in the main navigation.
        $this->assertContains('Inline list item', $texts);
        $this->assertNotContains('Inline list menu', $texts);

        // The menu with the test presentation is shown as submenu although it is set to inline mode.
        $this->assertContains('Inline boxes menu', $texts);
        $this->assertNotContains('Inline boxes item', $texts);
        $boxes = array_values(array_filter($items, fn($item) => strip_tags($item['text']) === 'Inline boxes menu'));
        $this->assertStringContainsString('Inline boxes item', $boxes[0]['contenthtml']);
    }

    /**
     * Test that a menu in inline mode is not shown at a location which does not support the inline mode.
     *
     * @covers \theme_boost_union\smartmenu::group_nodes_by_location
     */
    public function test_inline_mode_unsupported_location(): void {
        $page = $this->prepare_provider_test();

        // Create a menu in inline mode and a menu in submenu mode, both in the main navigation and at the test location.
        // The menu form would refuse to save the inline menu, but the generator does not validate the combination.
        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $inlinemenu = $generator->create_smartmenu([
            'title' => 'Inline menu',
            'location' => [smartmenu::LOCATION_MAIN, 'testlocation'],
            'mode' => smartmenu::MODE_INLINE,
        ]);
        $generator->create_smartmenu_item([
            'menu' => $inlinemenu->id,
            'title' => 'Inline item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.org',
        ]);
        $submenu = $generator->create_smartmenu([
            'title' => 'Sub menu',
            'location' => [smartmenu::LOCATION_MAIN, 'testlocation'],
        ]);
        $generator->create_smartmenu_item([
            'menu' => $submenu->id,
            'title' => 'Sub item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.com',
        ]);

        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));

        // The developer is notified about the menu which is not shown at the test location.
        $this->assertDebuggingCalled('The smart menu with the ID ' . $inlinemenu->id . ' is in inline mode and is not shown ' .
                'at the location \'testlocation\' as this location does not support the inline mode.');

        // The inline menu is not shown at the test location, the menu in submenu mode is.
        $this->assertStringNotContainsString('Inline item', $export['slots']['body_end']);
        $this->assertStringContainsString('Sub menu', $export['slots']['body_end']);
        $this->assertStringContainsString('Sub item', $export['slots']['body_end']);

        // In the main navigation, both menus are still shown.
        $items = json_decode($export['moremenu']['reactprops'], true)['items'];
        $texts = array_map(fn($item) => strip_tags($item['text']), $items);
        $this->assertContains('Inline item', $texts);
        $this->assertContains('Sub menu', $texts);
    }

    /**
     * Test which locations support the more menu behavior setting.
     *
     * @covers \theme_boost_union\smartmenu\location\base::menu_supports_moremenu_behavior
     * @return void
     */
    public function test_moremenu_behavior_support(): void {
        $this->prepare_provider_test(false);

        // The main navigation and the menu bar collapse their menus into a "More" menu, the user menu does not.
        $this->assertTrue(provider_registry::get_location('main')->menu_supports_moremenu_behavior());
        $this->assertTrue(provider_registry::get_location('menu')->menu_supports_moremenu_behavior());
        $this->assertFalse(provider_registry::get_location('user')->menu_supports_moremenu_behavior());

        // The test location does not overwrite menu_supports_moremenu_behavior(), so it proves that the more menu
        // behavior is not supported by default.
        $this->assertFalse(provider_registry::get_location('testlocation')->menu_supports_moremenu_behavior());
    }

    /**
     * Test that providers of other plugins which confirm the inline mode support are served with menus in inline mode.
     *
     * @covers \theme_boost_union\smartmenu\location\base::menu_supports_inline_mode
     * @covers \theme_boost_union\smartmenu\presentation\base::menu_supports_inline_mode
     * @covers \theme_boost_union\smartmenu::group_nodes_by_location
     * @covers \theme_boost_union\smartmenu::build
     */
    public function test_inline_mode_supported_by_providers(): void {
        $page = $this->prepare_provider_test();

        // The inline capable test providers confirm that they support the inline mode.
        $this->assertTrue(provider_registry::get_location('testinlinelocation')->menu_supports_inline_mode());
        $this->assertTrue(provider_registry::get_presentation('testinlinepresentation')->menu_supports_inline_mode());

        // Create a menu in inline mode with the list presentation and a menu in inline mode with the inline capable
        // test presentation, both in the main navigation and at the inline capable test location.
        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $listmenu = $generator->create_smartmenu([
            'title' => 'Inline list menu',
            'location' => [smartmenu::LOCATION_MAIN, 'testinlinelocation'],
            'mode' => smartmenu::MODE_INLINE,
        ]);
        $generator->create_smartmenu_item([
            'menu' => $listmenu->id,
            'title' => 'Inline list item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.org',
        ]);
        $decoratedmenu = $generator->create_smartmenu([
            'title' => 'Inline decorated menu',
            'location' => [smartmenu::LOCATION_MAIN, 'testinlinelocation'],
            'presentation' => 'testinlinepresentation',
            'mode' => smartmenu::MODE_INLINE,
        ]);
        $generator->create_smartmenu_item([
            'menu' => $decoratedmenu->id,
            'title' => 'Inline decorated item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.com',
        ]);

        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));
        $this->assertDebuggingNotCalled();

        // The location gets the nodes of the menu items instead of the nodes of the menus and shows the items directly.
        $slot = $export['slots']['page_top'];
        $this->assertStringContainsString('Inline list item', $slot);
        $this->assertStringContainsString('Inline decorated item', $slot);
        $this->assertStringNotContainsString('Inline list menu', $slot);
        $this->assertStringNotContainsString('Inline decorated menu', $slot);
        $this->assertStringNotContainsString('testinlinelocation-menu"', $slot);
        $this->assertSame(2, substr_count($slot, 'testinlinelocation-inlineitem'));

        // Only the item of the menu with the inline capable test presentation has been decorated by this presentation.
        $this->assertSame(1, substr_count($slot, 'testinlinepresentation-decorated'));

        // The menu with the inline capable test presentation is shown inline in the main navigation as well
        // (and is not forced to be a submenu like the menus of presentations without inline mode support).
        $items = json_decode($export['moremenu']['reactprops'], true)['items'];
        $texts = array_map(fn($item) => strip_tags($item['text']), $items);
        $this->assertContains('Inline list item', $texts);
        $this->assertContains('Inline decorated item', $texts);
        $this->assertNotContains('Inline decorated menu', $texts);

        // The presentation does not have any AMD module by default.
        $this->assertSame([], $export['amdmodules']);

        // If the presentation has an AMD module, it is loaded for the menu in inline mode as well. The presentation
        // is asked with its own item node only and not with the item node of the menu with the list presentation.
        set_config('behat_smartmenu_testinlinepresentation_amd', 1, 'theme_boost_union');
        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));
        $this->assertSame(['theme_boost_union/testinlinepresentation_1nodes'], $export['amdmodules']);
    }

    /**
     * Test that menu items in submenu mode are only shown in menus with a presentation which supports this mode.
     *
     * @covers \theme_boost_union\smartmenu\presentation\base::item_supports_submenu_mode
     * @covers \theme_boost_union\smartmenu_item::generate_dynamic_item
     */
    public function test_item_submenu_mode_support(): void {
        global $DB;

        $page = $this->prepare_provider_test();

        // The list presentation confirms that it supports the submenu mode of menu items. The test presentations do not
        // overwrite item_supports_submenu_mode(), so they prove that this mode is not supported by default.
        $this->assertTrue(provider_registry::get_presentation('list')->item_supports_submenu_mode());
        $this->assertFalse(provider_registry::get_presentation('testpresentation')->item_supports_submenu_mode());
        $this->assertFalse(provider_registry::get_presentation('testinlinepresentation')->item_supports_submenu_mode());

        $this->getDataGenerator()->create_course(['fullname' => 'Submenu course']);

        // Create a menu with the list presentation and a menu with the test presentation.
        // Both get a dynamic courses item in submenu mode.
        // The menu item form does not offer the submenu mode for the menu with the test presentation,
        // but the generator does not care about that.
        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $menus = [];
        foreach (['list' => 'List menu', 'testpresentation' => 'Boxes menu'] as $presentation => $title) {
            $menus[$presentation] = $generator->create_smartmenu([
                'title' => $title,
                'location' => [smartmenu::LOCATION_MAIN],
                'presentation' => $presentation,
            ]);
            $generator->create_smartmenu_item([
                'menu' => $menus[$presentation]->id,
                'title' => 'Submenu courses',
                'type' => smartmenu_item::TYPEDYNAMIC,
                'mode' => smartmenu_item::MODE_SUBMENU,
            ]);
        }

        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));
        $items = json_decode($export['moremenu']['reactprops'], true)['items'];
        $listmenu = array_values(array_filter($items, fn($item) => strip_tags($item['text']) === 'List menu'));
        $boxesmenu = array_values(array_filter($items, fn($item) => strip_tags($item['text']) === 'Boxes menu'));

        // In the menu with the list presentation, the item is shown with the course in its submenu.
        $this->assertCount(1, $listmenu);
        $this->assertStringContainsString('Submenu courses', json_encode($listmenu[0]));
        $this->assertStringContainsString('Submenu course', json_encode($listmenu[0]['children']));

        // In the menu with the test presentation, the item is not shown at all.
        $this->assertStringNotContainsString('Submenu course', json_encode($boxesmenu));

        // As soon as the item is switched to inline mode, its course is shown in the menu with the test presentation.
        $boxesitem = $DB->get_record('theme_boost_union_menuitems', ['menu' => $menus['testpresentation']->id]);
        $DB->set_field('theme_boost_union_menuitems', 'mode', smartmenu_item::MODE_INLINE, ['id' => $boxesitem->id]);
        \cache::make('theme_boost_union', 'smartmenus')->purge();
        \cache::make('theme_boost_union', 'smartmenu_items')->purge();
        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));
        $items = json_decode($export['moremenu']['reactprops'], true)['items'];
        $boxesmenu = array_values(array_filter($items, fn($item) => strip_tags($item['text']) === 'Boxes menu'));
        $this->assertCount(1, $boxesmenu);
        $this->assertStringContainsString('Submenu course', $boxesmenu[0]['contenthtml']);
    }

    /**
     * Test that a presentation of another plugin which confirms the support for the submenu mode of menu items
     * gets the menu items in submenu mode together with their children.
     *
     * @covers \theme_boost_union\smartmenu\presentation\base::item_supports_submenu_mode
     * @covers \theme_boost_union\smartmenu_item::generate_dynamic_item
     */
    public function test_item_submenu_mode_supported_by_provider(): void {
        $page = $this->prepare_provider_test();

        // The submenu capable test presentation confirms that it supports the submenu mode of menu items.
        $this->assertTrue(provider_registry::get_presentation('testsubmenupresentation')->item_supports_submenu_mode());

        $this->getDataGenerator()->create_course(['fullname' => 'Nested course']);

        // Create a menu with the submenu capable test presentation, with a static item and with a dynamic courses item
        // in submenu mode.
        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $menu = $generator->create_smartmenu([
            'title' => 'Nested menu',
            'location' => [smartmenu::LOCATION_MAIN],
            'presentation' => 'testsubmenupresentation',
        ]);
        $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'Static item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.org',
        ]);
        $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'Nested courses',
            'type' => smartmenu_item::TYPEDYNAMIC,
            'mode' => smartmenu_item::MODE_SUBMENU,
        ]);

        $export = (new \theme_boost_union\output\navigation\primary($page))->export_for_template($page->get_renderer('core'));
        $items = json_decode($export['moremenu']['reactprops'], true)['items'];
        $nestedmenu = array_values(array_filter($items, fn($item) => strip_tags($item['text']) === 'Nested menu'));
        $this->assertCount(1, $nestedmenu);
        $content = $nestedmenu[0]['contenthtml'];

        // The presentation has got the item in submenu mode and has rendered its course as nested list.
        $this->assertStringContainsString('Static item', $content);
        $this->assertSame(1, substr_count($content, 'testsubmenupresentation-submenutitle'));
        $this->assertStringContainsString('Nested courses', $content);
        $submenu = substr($content, strpos($content, 'testsubmenupresentation-submenu"'));
        $this->assertStringContainsString('Nested course', $submenu);
        $this->assertStringNotContainsString('Static item', $submenu);
    }
}
