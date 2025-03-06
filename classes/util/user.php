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

namespace theme_boost_union\util;

/**
 * Theme Boost Union - User utility class
 *
 * @package    theme_boost_union
 * @copyright  2024 Daniel Neis Araujo {@link https://www.adapta.online}
 *             based on code 2022 Willian Mano {@link https://conecti.me}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user {
    /**
     * @var \stdClass $user The user object.
     */
    protected $user;

    /**
     * Class constructor.
     *
     * @param \stdClass|int $user The user object or user ID.
     */
    public function __construct($user = null) {
        global $USER;

        // If a user ID is given instead of a full user object (which is not really encouraged but also not forbidden).
        if (!is_object($user)) {
            // Get the full user object.
            $user = \core_user::get_user($user, '*', MUST_EXIST);
        }

        // If we still don't have a user object, use the current user.
        if (!$user) {
            $user = $USER;
        }

        // Remember the user.
        $this->user = $user;
    }

    /**
     * Returns the user picture URL.
     *
     * @param int $imgsize The image size (in pixels)
     * @return string The user picture URL
     * @throws \coding_exception
     */
    public function get_user_picture($imgsize = 100) {
        global $PAGE;

        // Create a new user picture object.
        $userimg = new \user_picture($this->user);

        // Set the image size.
        $userimg->size = $imgsize;

        // Return the user picture URL.
        return $userimg->get_url($PAGE)->out();
    }
}
