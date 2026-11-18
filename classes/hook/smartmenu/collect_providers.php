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

namespace theme_boost_union\hook\smartmenu;

use theme_boost_union\smartmenu\location\base as location_base;
use theme_boost_union\smartmenu\presentation\base as presentation_base;

/**
 * Theme Boost Union - Hook to collect the smart menu location and presentation providers of other plugins.
 *
 * Boost Union ships its built-in smart menu locations (main navigation, menu bar, user menu) and its built-in
 * smart menu presentation (list) itself. As Moodle themes cannot have subplugins, other plugins can use this hook
 * to add further locations and presentations to the smart menus.
 *
 * @package    theme_boost_union
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
#[\core\attribute\label('Allows plugins to add location providers and presentation providers to the Boost Union smart menus.')]
#[\core\attribute\tags('theme_boost_union', 'navigation')]
final class collect_providers {
    /** @var location_base[] The location providers which have been added by the hook callbacks. */
    protected array $locations = [];

    /** @var presentation_base[] The presentation providers which have been added by the hook callbacks. */
    protected array $presentations = [];

    /**
     * Add a smart menu location provider.
     *
     * @param location_base $provider The location provider.
     * @return void
     */
    public function add_location(location_base $provider): void {
        $this->locations[] = $provider;
    }

    /**
     * Add a smart menu presentation provider.
     *
     * @param presentation_base $provider The presentation provider.
     * @return void
     */
    public function add_presentation(presentation_base $provider): void {
        $this->presentations[] = $provider;
    }

    /**
     * Get the location providers which have been added by the hook callbacks.
     *
     * @return location_base[]
     */
    public function get_locations(): array {
        return $this->locations;
    }

    /**
     * Get the presentation providers which have been added by the hook callbacks.
     *
     * @return presentation_base[]
     */
    public function get_presentations(): array {
        return $this->presentations;
    }
}
