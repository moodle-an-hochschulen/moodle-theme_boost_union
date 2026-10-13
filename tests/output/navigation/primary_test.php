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

namespace theme_boost_union\output\navigation;

use advanced_testcase;
use theme_boost_union\smartmenu;
use theme_boost_union\smartmenu_item;

/**
 * Theme Boost Union - Tests for the primary navigation renderable.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @coversDefaultClass \theme_boost_union\output\navigation\primary
 */
final class primary_test extends advanced_testcase {
    /**
     * Prepare the page for the tests.
     *
     * @return \moodle_page
     */
    protected function prepare_page(): \moodle_page {
        global $PAGE;

        // Use Boost Union as site theme (the theme is resolved as soon as the page is initialised for output).
        set_config('theme', 'boost_union');

        $PAGE->set_url('/');
        $PAGE->set_context(\context_system::instance());

        return $PAGE;
    }

    /**
     * Find the first item in the given React items which matches the given callback.
     *
     * @param array $items The React items.
     * @param callable $callback The callback.
     * @return array|null
     */
    protected function find_item(array $items, callable $callback): ?array {
        foreach ($items as $item) {
            if ($callback($item)) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Test that the React props are exported like in core if no smart menus are configured.
     *
     * @covers ::export_for_template
     */
    public function test_export_for_template_without_smartmenus(): void {
        $this->resetAfterTest();
        $this->setAdminUser();

        $page = $this->prepare_page();
        $primary = new primary($page);
        $data = $primary->export_for_template($page->get_renderer('core'));

        // The main navigation carries the React props.
        $this->assertArrayHasKey('reactprops', $data['moremenu']);
        $props = json_decode($data['moremenu']['reactprops'], true);
        $this->assertIsArray($props);
        $this->assertNotEmpty($props['items']);
        $this->assertSame('navbar-nav', $props['navbarstyle']);
        $this->assertSame('primarynav-measured', $props['measuredclass']);

        // Without smart menus, the core export is used which does not know about the menu bar.
        $this->assertArrayNotHasKey('menubar', $data);
        $this->assertArrayNotHasKey('includesmartmenu', $data);

        // None of the nodes is a smart menu node.
        foreach ($props['items'] as $item) {
            $this->assertArrayNotHasKey('smartmenu', $item);
            $this->assertArrayNotHasKey('smartmenuitem', $item);
        }
    }

    /**
     * Test that the smart menus are exported to the React props of the main navigation and the menu bar.
     *
     * @covers ::export_for_template
     * @covers ::export_react_props
     * @covers ::export_node_for_react
     */
    public function test_export_for_template_with_smartmenus(): void {
        $this->resetAfterTest();
        $this->setAdminUser();

        // Create a smart menu with some items.
        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $menu = $generator->create_smartmenu([
            'title' => 'Quick & links',
            'location' => [smartmenu::LOCATION_MAIN, smartmenu::LOCATION_MENU],
            'cssclass' => 'quick-links-menu',
            'moremenubehavior' => smartmenu::MOREMENU_OUTSIDE,
            'description' => 'List of useful links',
            'showdescription' => smartmenu::DESC_ABOVE,
        ]);
        $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'Moodle & more',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.org/?a=1&b=2',
            'target' => smartmenu_item::TARGET_NEW,
            'cssclass' => 'static-item',
            'tooltip' => 'Open moodle.org',
        ]);
        $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'Heading',
            'type' => smartmenu_item::TYPEHEADING,
        ]);
        $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'Divider',
            'type' => smartmenu_item::TYPEDIVIDER,
        ]);

        $page = $this->prepare_page();
        $primary = new primary($page);
        $data = $primary->export_for_template($page->get_renderer('core'));

        $this->assertTrue($data['includesmartmenu']);

        // Main navigation.
        $props = json_decode($data['moremenu']['reactprops'], true);
        $this->assertIsArray($props);
        $this->assertSame('navbar-nav', $props['navbarstyle']);
        $this->assertSame(get_string('usermenugoback', 'core'), $props['gobacklabel']);

        // The core nodes are exported unchanged.
        $corenode = $this->find_item(
            $props['items'],
            fn($item) => !array_key_exists('smartmenu', $item) && !array_key_exists('smartmenuitem', $item)
        );
        $this->assertNotNull($corenode);
        $this->assertStringStartsNotWith('smartmenu-', $corenode['key']);
        $this->assertArrayNotHasKey('wrapperclasses', $corenode);
        $this->assertArrayNotHasKey('forceout', $corenode);

        // The smart menu node.
        $menunode = $this->find_item($props['items'], fn($item) => !empty($item['smartmenu']));
        $this->assertNotNull($menunode);
        $this->assertSame('smartmenu-' . $menu->id, $menunode['key']);
        // The formatted label must not be entity-decoded.
        $this->assertSame(format_string('Quick & links'), $menunode['text']);
        $this->assertContains('boost-union-smartmenu', $menunode['wrapperclasses']);
        $this->assertContains('quick-links-menu', $menunode['wrapperclasses']);
        $this->assertSame([], $menunode['classes']);
        $this->assertTrue($menunode['forceout']);
        $this->assertFalse($menunode['forceintomoremenu']);
        $this->assertTrue($menunode['showchildreninsubmenu']);
        $this->assertStringContainsString('List of useful links', $menunode['helptext']);
        $this->assertSame('above', $menunode['helptextposition']);
        $this->assertNull($menunode['helpicon']);
        $this->assertNull($menunode['tooltip']);
        $this->assertCount(3, $menunode['children']);

        // The static item.
        $staticitem = $menunode['children'][0];
        $this->assertTrue($staticitem['smartmenuitem']);
        $this->assertStringStartsWith('smartmenu-', $staticitem['key']);
        $this->assertContains('boost-union-smartmenuitem', $staticitem['classes']);
        $this->assertContains('static-item', $staticitem['classes']);
        $this->assertContains('menu-item-static', $staticitem['classes']);
        $this->assertSame($staticitem['classes'], $staticitem['wrapperclasses']);
        $this->assertStringContainsString('Moodle &amp; more', $staticitem['text']);
        $this->assertStringContainsString('a=1&b=2', $staticitem['href']);
        $this->assertSame('Open moodle.org', $staticitem['tooltip']);
        $this->assertSame([['name' => 'target', 'value' => '__blank']], $staticitem['attributes']);
        $this->assertFalse($staticitem['forceout']);
        $this->assertFalse($staticitem['divider']);

        // The heading item.
        $headingitem = $menunode['children'][1];
        $this->assertTrue($headingitem['smartmenuitem']);
        $this->assertContains('menu-item-heading', $headingitem['classes']);
        $this->assertSame('#', $headingitem['href']);
        $this->assertSame([], $headingitem['attributes']);
        $this->assertNull($headingitem['tooltip']);

        // The divider item.
        $divideritem = $menunode['children'][2];
        $this->assertTrue($divideritem['divider']);

        // Menu bar.
        $this->assertIsArray($data['menubar']);
        $menubarprops = json_decode($data['menubar']['reactprops'], true);
        $this->assertSame('navbar-nav-menu-bar', $menubarprops['navbarstyle']);
        $this->assertCount(1, $menubarprops['items']);
        $this->assertTrue($menubarprops['items'][0]['smartmenu']);
        $this->assertSame('smartmenu-' . $menu->id, $menubarprops['items'][0]['key']);
        $this->assertStringContainsString('d-lg-flex', $data['menubar']['classes']);
    }

    /**
     * Test that the items of a smart menu in inline mode are exported as top-level smart menu items.
     *
     * @covers ::export_for_template
     * @covers ::export_node_for_react
     */
    public function test_export_for_template_with_inline_smartmenu(): void {
        $this->resetAfterTest();
        $this->setAdminUser();

        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $menu = $generator->create_smartmenu([
            'title' => 'Inline links',
            'location' => [smartmenu::LOCATION_MAIN],
            'mode' => smartmenu::MODE_INLINE,
            'moremenubehavior' => smartmenu::MOREMENU_INTO,
        ]);
        $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'Inline item',
            'type' => smartmenu_item::TYPESTATIC,
            'url' => 'https://moodle.org',
            'desktop' => 1,
        ]);

        $page = $this->prepare_page();
        $primary = new primary($page);
        $data = $primary->export_for_template($page->get_renderer('core'));

        $props = json_decode($data['moremenu']['reactprops'], true);
        $this->assertNull($this->find_item($props['items'], fn($item) => !empty($item['smartmenu'])));

        $inlineitem = $this->find_item($props['items'], fn($item) => !empty($item['smartmenuitem']));
        $this->assertNotNull($inlineitem);
        $this->assertStringContainsString('Inline item', $inlineitem['text']);
        $this->assertSame('https://moodle.org', $inlineitem['href']);
        $this->assertFalse($inlineitem['showchildreninsubmenu']);
        $this->assertSame([], $inlineitem['children']);
        $this->assertContains('boost-union-smartmenuitem', $inlineitem['classes']);
        $this->assertContains('d-lg-none', $inlineitem['classes']);
        $this->assertContains('d-lg-none', $inlineitem['wrapperclasses']);
        $this->assertFalse($inlineitem['forceout']);
    }

    /**
     * Test that a dynamic courses item in submenu mode is exported with its courses as children and with a harmless
     * '#' link (React blocks javascript: URLs, so no javascript:void(0) pseudo URL must be exported).
     *
     * @covers ::export_for_template
     * @covers ::export_node_for_react
     */
    public function test_export_for_template_with_dynamic_courses_submenu(): void {
        $this->resetAfterTest();
        $this->setAdminUser();

        $generator = $this->getDataGenerator()->get_plugin_generator('theme_boost_union');
        $menu = $generator->create_smartmenu([
            'title' => 'Courses',
            'location' => [smartmenu::LOCATION_MAIN],
            'mode' => smartmenu::MODE_SUBMENU,
        ]);
        // Restrict the dynamic courses item to a course category with a single course.
        $category = $this->getDataGenerator()->create_category();
        $course = $this->getDataGenerator()->create_course(['category' => $category->id, 'fullname' => 'Course 1']);
        $generator->create_smartmenu_item([
            'menu' => $menu->id,
            'title' => 'My courses',
            'type' => smartmenu_item::TYPEDYNAMIC,
            'mode' => smartmenu_item::MODE_SUBMENU,
            'category' => [$category->id],
            'desktop' => 1,
        ]);

        $page = $this->prepare_page();
        $primary = new primary($page);
        $data = $primary->export_for_template($page->get_renderer('core'));

        $props = json_decode($data['moremenu']['reactprops'], true);
        $menunode = $this->find_item($props['items'], fn($item) => !empty($item['smartmenu']));
        $this->assertNotNull($menunode);
        $this->assertCount(1, $menunode['children']);

        // The dynamic courses item itself has no real URL.
        $coursesitem = $menunode['children'][0];
        $this->assertTrue($coursesitem['smartmenuitem']);
        $this->assertStringContainsString('My courses', $coursesitem['text']);
        $this->assertTrue($coursesitem['showchildreninsubmenu']);
        $this->assertSame('#', $coursesitem['href']);

        // The course is its child.
        $this->assertCount(1, $coursesitem['children']);
        $courseitem = $coursesitem['children'][0];
        $this->assertStringContainsString('Course 1', $courseitem['text']);
        $this->assertStringContainsString('/course/view.php?id=' . $course->id, $courseitem['href']);
    }
}
