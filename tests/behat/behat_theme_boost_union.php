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
 * Theme Boost Union - Standard behat step data providers.
 *
 * @package    theme_boost_union
 * @copyright  2024 onwards Catalyst IT EU {@link https://catalyst-eu.net}
 * @author     Mark Johnson <mark.johnson@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_theme_boost_union extends behat_base {
    /**
     * Convert page names to URLs for steps like 'When I am on the "[page name]" page'.
     *
     * Tabbed settings pages support an optional "> Tabname" suffix, otherwise they will load the default tab.
     *
     * Recognised page names are:
     * | Settings      | Settings overview             |
     * | Look          | "Look" settings page          |
     * | Feel          | "Feel" settings page          |
     * | Content       | "Content" settings page       |
     * | Functionality | "Functionality" settings page |
     * | Accessibility | "Accessibility" settings page |
     * | Flavours      | Flavours listing page         |
     * | Smart menus   | Smart menus listing page      |
     *
     * @param string $page name of the page, with the component name removed e.g. 'Admin notification'.
     * @return moodle_url the corresponding URL.
     * @throws Exception with a meaningful error message if the specified page cannot be found.
     */
    protected function resolve_page_url(string $page): moodle_url {
        $parts = explode('>', strtolower($page));
        $section = trim($parts[0]);
        if (count($parts) < 2) {
            return match ($section) {
                'settings' => new moodle_url('/theme/boost_union/settings_overview.php'),
                'look' => new moodle_url('/admin/settings.php?section=theme_boost_union_look'),
                'feel' => new moodle_url('/admin/settings.php?section=theme_boost_union_feel'),
                'content' => new moodle_url('/admin/settings.php?section=theme_boost_union_content'),
                'functionality' => new moodle_url('/admin/settings.php?section=theme_boost_union_functionality'),
                'accessibility' => new moodle_url('/admin/settings.php?section=theme_boost_union_accessibility'),
                'flavours' => new moodle_url('/theme/boost_union/flavours/overview.php'),
                'smart menus' => new moodle_url('/theme/boost_union/smartmenus/menus.php'),
                default => throw new Exception('Unrecognised theme_boost_union page "' . $page . '."')
            };
        }
        $suffix = trim($parts[1]);
        $tabs = [];
        switch ($section) {
            case 'look':
                $tabs = [
                    'general',
                    'scss',
                    'page',
                    'sitebranding',
                    'activitybranding',
                    'loginpage',
                    'dashboard',
                    'blocks',
                    'course',
                    'emailbranding',
                    'resources',
                    'h5p',
                    'mobile',
                ];
                $suffix = match ($suffix) {
                    'general', 'general settings' => 'general',
                    'my courses', 'dashboard / my courses' => 'dashboard',
                    default => str_replace([' ', '-'], ['', ''], $suffix)
                };
                break;

            case 'feel':
                $tabs = [
                    'navigation',
                    'blocks',
                    'pagelayouts',
                    'links',
                    'misc',
                ];
                $suffix = match ($suffix) {
                    'miscellaneous' => 'misc',
                    default => str_replace([' ', '-'], ['', ''], $suffix)
                };
                break;

            case 'content':
                $tabs = [
                    'footer',
                    'staticpages',
                    'infobanner',
                    'tiles',
                    'slider',
                ];
                $suffix = match ($suffix) {
                    'advertisement tiles' => 'tiles',
                    default => str_replace([' ', '-'], ['', ''], $suffix)
                };
                $section = match ($suffix) {
                    'infobanner' => 'infobanners',
                    'tiles', 'slider' => '',
                    default => $section,
                };
                break;

            case 'functionality':
                $tabs = [
                    'courses',
                    'administration',
                ];
                break;

            case 'accessibility':
                $tabs = [
                    'declaration',
                    'support',
                ];
                break;

            default:
                throw new Exception('Unrecognised theme_boost_union page "' . $page . '."');
        }

        if (!in_array($suffix, $tabs)) {
            throw new Exception('Unrecognised theme_boost_union page "' . $page . '."');
        }
        return new moodle_url('/admin/settings.php?section=theme_boost_union_' . $section . '_' . $suffix);
    }

    /**
     * Convert page names to URLs for steps like 'When I am on the "[identifier]" "[page type]" page'.
     *
     * Recognised page names are:
     * | pagetype            | name meaning                | description                                               |
     * | Flavour > Preview   | Flavour title               | The flavour preview page (flavours/preview.php)           |
     * | Flavour > Edit      | Flavour title               | The flavour edit page (flavours/edit.php)                 |
     * | Smart menu > Edit   | Menu title                  | The smart menu edit page (smartmenus/edit.php)            |
     * | Smart menu > Items  | Menu title                  | The smart menu items page (smartmenus/items.php)          |
     * | Smart menu item     | Menu title > Item title     | The smart menu item edit page (smartmenus/edit_items.php) |
     * | Course completion   | Course identifier           | The course completion form                                |
     *
     * @param string $type identifies which type of page this is, e.g. 'Smart menu item'.
     * @param string $identifier identifies the particular page, e.g. 'Menu 1 > Item 1'.
     * @return moodle_url the corresponding URL.
     * @throws Exception with a meaningful error message if the specified page cannot be found.
     */
    protected function resolve_page_instance_url(string $type, string $identifier): moodle_url {
        $parts = explode('>', strtolower($type));
        $pagetype = trim($parts[0]);

        switch ($pagetype) {
            case 'flavour':
                $page = trim($parts[1]);
                if (!in_array($page, ['preview', 'edit'])) {
                    throw new Exception('Unrecognised theme_boost_union page type "' . $type . '."');
                }
                return new moodle_url(
                    '/theme/boost_union/flavours/' . $page . '.php',
                    [
                        'id' => $this->get_flavour_id_by_title($identifier),
                        'sesskey' => $this->get_sesskey(),
                    ]
                );

            case 'smart menu':
                $page = trim($parts[1]);
                if (!in_array($page, ['edit', 'items'])) {
                    throw new Exception('Unrecognised theme_boost_union page type "' . $type . '."');
                }
                $idparam = $page == 'edit' ? 'id' : 'menu';
                return new moodle_url(
                    '/theme/boost_union/smartmenus/' . $page . '.php',
                    [
                        $idparam => $this->get_smartmenu_id_by_title($identifier),
                        'sesskey' => $this->get_sesskey(),
                    ]
                );

            case 'smart menu item':
                $idparts = explode('>', $identifier);
                $menutitle = trim($idparts[0]);
                $itemtitle = trim($idparts[1]);
                $menuid = $this->get_smartmenu_id_by_title($menutitle);
                return new moodle_url(
                    '/theme/boost_union/smartmenus/edit_items.php',
                    [
                        'id' => $this->get_smartmenu_item_id_by_title($menuid, $itemtitle),
                        'sesskey' => $this->get_sesskey(),
                    ]
                );

            case 'course completion':
                return new moodle_url(
                    '/course/completion.php',
                    [
                        'id' => $this->get_course_id($identifier),
                    ]
                );

            default:
                throw new Exception('Unrecognised theme_boost_union page type "' . $type . '."');
        }
    }

    /**
     * Return named selectors for use with steps like `the following "locator" "identifier" should exist`.
     *
     * Supported selectors:
     * - "Smart menu" - The menu button for a smart menu. Locator = Smart menu title.
     * - "Smart menu item" - The menu button for a smart menu item. Locator = Item title.
     * - "Main menu smart menu" - The submenu for a smart menu within the main menu. Locator = Smart menu title.
     * - "Main menu smart menu item" - A smart menu item within the main menu. Locator = Item title.
     * - "Menu bar smart menu" - The submenu for a smart menu within top menu bar. Locator = Smart menu title.
     * - "Menu bar smart menu item" - A smart menu item within top menu bar. Locator = Item title.
     * - "User menu smart menu" - The submenu for a smart menu within top user menu. Locator = Smart menu title.
     * - "User menu smart menu item" - A smart menu item within a user menu submenu. Locator = Item title.
     * - "Bottom bar smart menu" - The submenu for a smart menu within bottom menu bar. Locator = Smart menu title.
     * - "Bottom bar smart menu item" - A smart menu item within bottom menu bar. Locator = Item title.
     *
     * @return array|behat_component_named_selector[]
     */
    public static function get_exact_named_selectors(): array {
        return [
            new behat_component_named_selector(
                'Smart menu',
                [".//a[@role = 'menuitem'][contains(text(), %locator%)]"],
            ),
            new behat_component_named_selector(
                'Smart menu item',
                [".//a[contains(@class, 'boost-union-smartmenuitem')][contains(text(), %locator%)]"],
            ),
            new behat_component_named_selector(
                'Main menu smart menu',
                [
                    ".//div[contains(@class, 'primary-navigation')]//li[contains(@class, 'boost-union-smartmenu')]" .
                        "/a[contains(text(), %locator%)]/../div[@role = 'menu']",
                ],
            ),
            new behat_component_named_selector(
                'Main menu smart menu item',
                [
                    ".//div[contains(@class, 'primary-navigation')]" .
                        "//a[contains(@class, 'boost-union-smartmenuitem')][contains(text(), %locator%)]",
                ],
            ),
            new behat_component_named_selector(
                'Menu bar smart menu',
                [
                    ".//nav[contains(@class, 'boost-union-menubar')]//li[contains(@class, 'boost-union-smartmenu')]" .
                        "/a[contains(text(), %locator%)]/../div[@role = 'menu']",
                ],
            ),
            new behat_component_named_selector(
                'Menu bar smart menu item',
                [
                    ".//nav[contains(@class, 'boost-union-menubar')]" .
                        "//a[contains(@class, 'boost-union-smartmenuitem')][contains(text(), %locator%)]",
                ],
            ),
            new behat_component_named_selector(
                'User menu smart menu',
                [
                    ".//div[@id = 'usermenu-carousel']//div[contains(@class, 'carousel-item')][@aria-label = %locator%]",
                ],
            ),
            new behat_component_named_selector(
                'User menu smart menu item',
                [
                    ".//div[@id = 'usermenu-carousel']//div[contains(@class, 'carousel-item')]" .
                        "//a[contains(@class, 'boost-union-smartmenuitem')][contains(text(), %locator%)]",
                ],
            ),
            new behat_component_named_selector(
                'Bottom bar smart menu',
                [
                    ".//nav[contains(@class, 'boost-union-bottom-menu')]//li[contains(@class, 'boost-union-smartmenu')]" .
                        "/a[contains(text(), %locator%)]/../div[@role = 'menu']"],
            ),
            new behat_component_named_selector(
                'Bottom bar smart menu item',
                [
                    ".//nav[contains(@class, 'boost-union-bottom-menu')]" .
                        "//a[contains(@class, 'boost-union-smartmenuitem')][contains(text(), %locator%)]",
                ],
            ),
        ];
    }

    /**
     * Given the title of a Flavour, return the ID.
     *
     * @param string $title
     * @return int
     * @throws dml_exception
     */
    protected function get_flavour_id_by_title(string $title): int {
        global $DB;
        $id = $DB->get_field('theme_boost_union_flavours', 'id', ['title' => $title]);
        if (!$id) {
            throw new Exception('Cannot find Boost Union flavour with title "' . $title . '"');
        }
        return $id;
    }

    /**
     * Given the title of a Smart menu, return the ID.
     *
     * @param string $title
     * @return int
     * @throws dml_exception
     */
    protected function get_smartmenu_id_by_title(string $title): int {
        global $DB;
        $id = $DB->get_field('theme_boost_union_menus', 'id', ['title' => $title]);
        if (!$id) {
            throw new Exception('Cannot find Boost Union smart menu with title "' . $title . '"');
        }
        return $id;
    }

    /**
     * Given the menu ID and title of a Smart menu item, return the item ID.
     *
     * @param int $menuid
     * @param string $title
     * @return int
     * @throws dml_exception
     */
    protected function get_smartmenu_item_id_by_title(int $menuid, string $title): int {
        global $DB;
        $id = $DB->get_field('theme_boost_union_menuitems', 'id', ['menu' => $menuid, 'title' => $title]);
        if (!$id) {
            throw new Exception('Cannot find Boost Union smart menu item with title "' . $title . '"');
        }
        return $id;
    }
}
