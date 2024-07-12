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
 * Theme Boost Union - Hex to CSS Filter.
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union\lib\hextocssfilter;

// This code is obtained from a third party source. Let's ignore the missing PHPDoc comments.
// phpcs:disable moodle.Commenting.VariableComment.Missing
// phpcs:disable moodle.Commenting.MissingDocblock.Missing
// phpcs:disable moodle.Commenting.MissingDocblock.Function

/**
 * Class which represents a color to be transformed to CSS filters.
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias <bias@alexanderbias.de>
 *             based on code on https://wiki.cgx.me/code/php/colorsolver
 *             based on code on https://stackoverflow.com/questions/42966641/
 *                     how-to-transform-black-into-any-given-color-using-only-css-filters/43960991#43960991
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class color {
    public float $r;
    public float $g;
    public float $b;

    public function __construct($r, $g, $b) {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    private function clamp($value) {
        if ($value > 255) {
            $value = 255;
        } else if ($value < 0) {
            $value = 0;
        }
        return $value;
    }

    private function multiply($matrix) {
        $newr = $this->clamp($this->r * $matrix[0] + $this->g * $matrix[1] + $this->b * $matrix[2]);
        $newg = $this->clamp($this->r * $matrix[3] + $this->g * $matrix[4] + $this->b * $matrix[5]);
        $newb = $this->clamp($this->r * $matrix[6] + $this->g * $matrix[7] + $this->b * $matrix[8]);

        $this->r = $newr;
        $this->g = $newg;
        $this->b = $newb;
    }

    public function huerotate($angle = 0) {
        $angle = $angle / 180 * M_PI;
        $angsin = sin($angle);
        $angcos = cos($angle);

        $this->multiply([0.213 + $angcos * 0.787 - $angsin * 0.213,
            0.715 - $angcos * 0.715 - $angsin * 0.715,
            0.072 - $angcos * 0.072 + $angsin * 0.928,
            0.213 - $angcos * 0.213 + $angsin * 0.143,
            0.715 + $angcos * 0.285 + $angsin * 0.140,
            0.072 - $angcos * 0.072 - $angsin * 0.283,
            0.213 - $angcos * 0.213 - $angsin * 0.787,
            0.715 - $angcos * 0.715 + $angsin * 0.715,
            0.072 + $angcos * 0.928 + $angsin * 0.072,
        ]);
    }

    private function grayscale($value = 1) {
        $this->multiply([0.2126 + 0.7874 * (1 - $value),
            0.7152 - 0.7152 * (1 - $value),
            0.0722 - 0.0722 * (1 - $value),
            0.2126 - 0.2126 * (1 - $value),
            0.7152 + 0.2848 * (1 - $value),
            0.0722 - 0.0722 * (1 - $value),
            0.2126 - 0.2126 * (1 - $value),
            0.7152 - 0.7152 * (1 - $value),
            0.0722 + 0.9278 * (1 - $value),
        ]);
    }

    public function sepia($value = 1) {
        $this->multiply([0.393 + 0.607 * (1 - $value),
            0.769 - 0.769 * (1 - $value),
            0.189 - 0.189 * (1 - $value),
            0.349 - 0.349 * (1 - $value),
            0.686 + 0.314 * (1 - $value),
            0.168 - 0.168 * (1 - $value),
            0.272 - 0.272 * (1 - $value),
            0.534 - 0.534 * (1 - $value),
            0.131 + 0.869 * (1 - $value),
        ]);
    }

    public function saturate($value = 1) {
        $this->multiply([0.213 + 0.787 * $value,
            0.715 - 0.715 * $value,
            0.072 - 0.072 * $value,
            0.213 - 0.213 * $value,
            0.715 + 0.285 * $value,
            0.072 - 0.072 * $value,
            0.213 - 0.213 * $value,
            0.715 - 0.715 * $value,
            0.072 + 0.928 * $value,
        ]);
    }

    public function brightness($value = 1) {
        $this->linear($value);
    }

    public function contrast($value = 1) {
        $this->linear($value, -(0.5 * $value) + 0.5);
    }

    private function linear($slope = 1, $intercept = 0) {
        $this->r = $this->clamp($this->r * $slope + $intercept * 255);
        $this->g = $this->clamp($this->g * $slope + $intercept * 255);
        $this->b = $this->clamp($this->b * $slope + $intercept * 255);
    }

    public function invert($value = 1) {
        $this->r = $this->clamp(($value + $this->r / 255 * (1 - 2 * $value)) * 255);
        $this->g = $this->clamp(($value + $this->g / 255 * (1 - 2 * $value)) * 255);
        $this->b = $this->clamp(($value + $this->b / 255 * (1 - 2 * $value)) * 255);
    }

    public static function hextorgb($hex) {
        $r = hexdec(substr($hex, 1, 2));
        $g = hexdec(substr($hex, 3, 2));
        $b = hexdec(substr($hex, 5, 2));

        return [$r, $g, $b];
    }

    public static function hsl($r, $g, $b) {
        $r = $r / 255;
        $g = $g / 255;
        $b = $b / 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $h = ($max + $min) / 2;
        $s = ($max + $min) / 2;
        $l = ($max + $min) / 2;

        if ($max == $min) {
            $h = 0;
            $s = 0;
        } else {
            $d = $max - $min;
            $s = ($l > 0.5) ? $d / (2 - $max - $min) : $d / ($max + $min);

            switch ($max) {
                case $r:
                    $h = ($g - $b) / $d + ($g < $b ? 6 : 0);
                    break;
                case $g:
                    $h = ($b - $r) / $d + 2;
                    break;
                case $b:
                    $h = ($r - $g) / $d + 4;
                    break;
            }

            $h /= 6;
        }

        return ["h" => $h * 100, "s" => $s * 100, "l" => $l * 100];
    }
}
