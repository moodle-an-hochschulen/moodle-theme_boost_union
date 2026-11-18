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
 * Theme Boost Union - Upgrade library.
 *
 * This file holds the parts of the upgrade steps in db/upgrade.php which convert data and which are covered
 * by unit tests. The changes of the database structure remain in db/upgrade.php.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Convert a smart menu from the former data model to the provider data model (upgrade step 2026100503).
 *
 * In the former data model, the locations of a menu were stored as JSON list of integer IDs, the presentation was
 * stored as integer ID in the type field and the settings of the card presentation had fields of their own.
 * In the provider data model, the locations and the presentation are stored as provider keys and the settings of the
 * providers are stored as JSON, keyed by the keys of the providers.
 *
 * @param \stdClass $menu The menu record in the former data model (with the fields location and type and, if they
 *                        still exist, cardsize, cardform and cardoverflowbehavior).
 * @return \stdClass An object with the converted values: location (JSON list of location keys), presentation
 *                   (presentation key) and presentationdata (JSON or null if there are not any settings).
 */
function theme_boost_union_upgrade_smartmenu_to_providers(\stdClass $menu): \stdClass {
    // The former location IDs and their location keys.
    // The bottom bar location (4) has been removed from Boost Union, but its key is kept for the menus which
    // have been assigned to it so that a plugin which provides this location again can pick them up.
    $locationkeys = [1 => 'main', 2 => 'menu', 3 => 'user', 4 => 'bottom'];

    // The card settings of the menus. The card presentation (1) has been removed from Boost Union, but its key
    // and its settings are kept so that a plugin which provides this presentation again can pick them up.
    $cardfields = ['cardsize', 'cardform', 'cardoverflowbehavior'];

    // Convert the location IDs to location keys. Unknown location IDs are dropped.
    $keys = [];
    foreach ((array) (json_decode($menu->location ?? '') ?: []) as $location) {
        if (is_numeric($location) && isset($locationkeys[(int) $location])) {
            $keys[] = $locationkeys[(int) $location];
        }
    }

    // Convert the type ID to the presentation key.
    $presentation = (($menu->type ?? 0) == 1) ? 'card' : 'list';

    // Move the card settings to the presentation data.
    // The settings are moved regardless of the presentation of the menu (they could also be filled for a list menu,
    // e.g. if the menu had been switched from card to list) so that they are available again if the menu is
    // switched back to card. Presentation data which exists already is kept.
    $presentationdata = json_decode($menu->presentationdata ?? '', true) ?: [];
    $carddata = [];
    foreach ($cardfields as $cardfield) {
        if (isset($menu->{$cardfield})) {
            $carddata[$cardfield] = (int) $menu->{$cardfield};
        }
    }
    if (!empty($carddata)) {
        $presentationdata['card'] = $carddata;
    }

    return (object) [
        'location' => json_encode(array_values(array_unique($keys))),
        'presentation' => $presentation,
        'presentationdata' => !empty($presentationdata) ? json_encode($presentationdata) : null,
    ];
}

/**
 * Convert the card settings of a smart menu item from the former data model to the provider data model
 * (upgrade step 2026100503).
 *
 * In the former data model, the settings of the card presentation had fields of their own in the menu item.
 * In the provider data model, they are stored as JSON in the presentationdata field, keyed by the key of the
 * card presentation.
 *
 * @param \stdClass $item The menu item record in the former data model (with the fields imagealt, textposition,
 *                        textcolor and backgroundcolor).
 * @return string|null The presentation data of the item as JSON or null if the item does not have any card settings
 *                     (which means that the presentation data of the item does not have to be changed).
 */
function theme_boost_union_upgrade_smartmenu_item_to_providers(\stdClass $item): ?string {
    // Collect the card settings of the item which have been set.
    $carddata = [];
    if (isset($item->imagealt) && $item->imagealt !== '') {
        $carddata['imagealt'] = $item->imagealt;
    }
    if (!empty($item->textposition)) {
        $carddata['textposition'] = (int) $item->textposition;
    }
    if (isset($item->textcolor) && $item->textcolor !== '') {
        $carddata['textcolor'] = $item->textcolor;
    }
    if (isset($item->backgroundcolor) && $item->backgroundcolor !== '') {
        $carddata['backgroundcolor'] = $item->backgroundcolor;
    }

    // If the item does not have any card settings, there is nothing to convert.
    if (empty($carddata)) {
        return null;
    }

    // Move the card settings to the presentation data. Presentation data which exists already is kept.
    $presentationdata = json_decode($item->presentationdata ?? '', true) ?: [];
    $presentationdata['card'] = $carddata;

    return json_encode($presentationdata);
}
