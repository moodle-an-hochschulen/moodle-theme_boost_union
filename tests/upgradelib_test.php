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

namespace theme_boost_union;

/**
 * Theme Boost Union - Tests for the upgrade library.
 *
 * @package    theme_boost_union
 * @category   test
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class upgradelib_test extends \advanced_testcase {
    /**
     * Load the upgrade library before each test.
     *
     * @return void
     */
    protected function setUp(): void {
        global $CFG;

        parent::setUp();
        require_once($CFG->dirroot . '/theme/boost_union/db/upgradelib.php');
    }

    /**
     * Test the conversion of a smart menu from the former data model to the provider data model.
     *
     * @covers ::theme_boost_union_upgrade_smartmenu_to_providers
     * @dataProvider smartmenu_to_providers_provider
     * @param array $menu The menu record in the former data model.
     * @param string $expectedlocation The expected location field.
     * @param string $expectedpresentation The expected presentation field.
     * @param string|null $expectedpresentationdata The expected presentationdata field.
     * @return void
     */
    public function test_smartmenu_to_providers(
        array $menu,
        string $expectedlocation,
        string $expectedpresentation,
        ?string $expectedpresentationdata
    ): void {
        $converted = theme_boost_union_upgrade_smartmenu_to_providers((object) $menu);

        $this->assertSame($expectedlocation, $converted->location);
        $this->assertSame($expectedpresentation, $converted->presentation);
        $this->assertSame($expectedpresentationdata, $converted->presentationdata);
    }

    /**
     * Data provider for test_smartmenu_to_providers().
     *
     * @return array
     */
    public static function smartmenu_to_providers_provider(): array {
        return [
            'List menu in the main navigation, without card fields (they do not exist anymore)' => [
                ['location' => '["1"]', 'type' => '0'],
                '["main"]',
                'list',
                null,
            ],
            'List menu in all built-in locations, with location IDs stored as strings' => [
                ['location' => '["1","2","3"]', 'type' => '0'],
                '["main","menu","user"]',
                'list',
                null,
            ],
            'List menu in all built-in locations, with location IDs stored as integers' => [
                ['location' => '[1,2,3]', 'type' => 0],
                '["main","menu","user"]',
                'list',
                null,
            ],
            'Menu in the removed bottom bar location keeps the key of this location' => [
                ['location' => '["1","4"]', 'type' => '0'],
                '["main","bottom"]',
                'list',
                null,
            ],
            'Unknown and invalid location IDs are dropped' => [
                ['location' => '["1","9","foo","0"]', 'type' => '0'],
                '["main"]',
                'list',
                null,
            ],
            'Duplicate location IDs are merged' => [
                ['location' => '["2","2",2]', 'type' => '0'],
                '["menu"]',
                'list',
                null,
            ],
            'Menu without any location' => [
                ['location' => '[]', 'type' => '0'],
                '[]',
                'list',
                null,
            ],
            'Menu with an empty location field' => [
                ['location' => '', 'type' => '0'],
                '[]',
                'list',
                null,
            ],
            'Menu with a broken location field' => [
                ['location' => 'not json', 'type' => '0'],
                '[]',
                'list',
                null,
            ],
            'Card menu with card settings (as they are returned as strings by the database)' => [
                ['location' => '["1"]', 'type' => '1', 'cardsize' => '2', 'cardform' => '3', 'cardoverflowbehavior' => '1'],
                '["main"]',
                'card',
                '{"card":{"cardsize":2,"cardform":3,"cardoverflowbehavior":1}}',
            ],
            'Card menu with partly empty card settings' => [
                ['location' => '["1"]', 'type' => '1', 'cardsize' => '2', 'cardform' => null, 'cardoverflowbehavior' => null],
                '["main"]',
                'card',
                '{"card":{"cardsize":2}}',
            ],
            'Card menu without card settings' => [
                ['location' => '["3"]', 'type' => '1', 'cardsize' => null, 'cardform' => null, 'cardoverflowbehavior' => null],
                '["user"]',
                'card',
                null,
            ],
            'List menu keeps its card settings for the case that it is switched back to card' => [
                ['location' => '["1"]', 'type' => '0', 'cardsize' => '1', 'cardform' => '1', 'cardoverflowbehavior' => '2'],
                '["main"]',
                'list',
                '{"card":{"cardsize":1,"cardform":1,"cardoverflowbehavior":2}}',
            ],
            'Presentation data which exists already is kept' => [
                [
                    'location' => '["1"]',
                    'type' => '1',
                    'cardsize' => '2',
                    'cardform' => null,
                    'cardoverflowbehavior' => null,
                    'presentationdata' => '{"other":{"foo":"bar"}}',
                ],
                '["main"]',
                'card',
                '{"other":{"foo":"bar"},"card":{"cardsize":2}}',
            ],
            'Unknown type IDs fall back to the list presentation' => [
                ['location' => '["1"]', 'type' => '7'],
                '["main"]',
                'list',
                null,
            ],
        ];
    }

    /**
     * Test the conversion of the card settings of a smart menu item from the former data model to the provider
     * data model.
     *
     * @covers ::theme_boost_union_upgrade_smartmenu_item_to_providers
     * @dataProvider smartmenu_item_to_providers_provider
     * @param array $item The menu item record in the former data model.
     * @param string|null $expected The expected presentationdata field or null if it does not have to be changed.
     * @return void
     */
    public function test_smartmenu_item_to_providers(array $item, ?string $expected): void {
        $this->assertSame($expected, theme_boost_union_upgrade_smartmenu_item_to_providers((object) $item));
    }

    /**
     * Data provider for test_smartmenu_item_to_providers().
     *
     * @return array
     */
    public static function smartmenu_item_to_providers_provider(): array {
        return [
            'Item without any card settings is not changed' => [
                ['imagealt' => null, 'textposition' => null, 'textcolor' => null, 'backgroundcolor' => null],
                null,
            ],
            'Item with empty card settings is not changed' => [
                ['imagealt' => '', 'textposition' => '0', 'textcolor' => '', 'backgroundcolor' => ''],
                null,
            ],
            'Item with all card settings' => [
                ['imagealt' => 'My image', 'textposition' => '2', 'textcolor' => '#ffffff', 'backgroundcolor' => '#000000'],
                '{"card":{"imagealt":"My image","textposition":2,"textcolor":"#ffffff","backgroundcolor":"#000000"}}',
            ],
            'Item with some card settings' => [
                ['imagealt' => null, 'textposition' => '1', 'textcolor' => '', 'backgroundcolor' => '#ff0000'],
                '{"card":{"textposition":1,"backgroundcolor":"#ff0000"}}',
            ],
            'Presentation data which exists already is kept' => [
                [
                    'imagealt' => 'My image',
                    'textposition' => null,
                    'textcolor' => null,
                    'backgroundcolor' => null,
                    'presentationdata' => '{"other":{"foo":"bar"}}',
                ],
                '{"other":{"foo":"bar"},"card":{"imagealt":"My image"}}',
            ],
        ];
    }
}
