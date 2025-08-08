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

namespace theme_boost_union\local\snippets;

// phpcs:disable
/**
 * Enum which handles snippet sources.
 *
 * @package    theme_boost_union
 * @copyright  2025 André Menrath <andre.menrath@uni-graz.at>, University of Graz
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
enum source: string {
    // phpcs:enable
    case BUILT_IN  = 'theme_boost_union';
    case UPLOADED  = 'uploaded';
    case COMMUNITY = 'community';

    /**
     * The filearea type where snippets are stored.
     *
     * Aka the filearea column in the files table.
     *
     * @see https://moodledev.io/docs/5.0/apis/subsystems/files#file-areas
     * @var string
     */
    public const FILEAREA_TYPE = 'snippet';

    /**
     * Get the moodle itemid for the filearea for each snippet source class.
     *
     * @throws \InvalidArgumentException
     * @return int
     */
    public function get_itemid(): ?int {
        return match($this) {
            self::BUILT_IN   => 0,
            self::UPLOADED   => 1,
            self::COMMUNITY  => 2,
        };
    }

    /**
     * Get the moodle filearea for each snippet source class.
     *
     * @return string
     */
    public function get_is_enabled_setting_name(): ?string {
        return match($this) {
            self::BUILT_IN  => 'enablebuiltinsnippets',
            self::UPLOADED  => 'enableuploadedsnippets',
            self::COMMUNITY => 'enablecommunitysnippets',
        };
    }

    /**
     * Get the filearea for each source.
     *
     * @return string
     */
    public function get_filearea(): string {
        return self::FILEAREA_TYPE;
    }
}
