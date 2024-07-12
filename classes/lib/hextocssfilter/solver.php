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
 * Class which is the solver to transform a color to CSS filters.
 *
 * @package    theme_boost_union
 * @copyright  2024 Alexander Bias <bias@alexanderbias.de>
 *             based on code on https://wiki.cgx.me/code/php/colorsolver
 *             based on code on https://stackoverflow.com/questions/42966641/
 *                     how-to-transform-black-into-any-given-color-using-only-css-filters/43960991#43960991
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class solver {
    private array $targetrgb;
    private array $targethsl;
    private color $targetcolor;

    public function __construct($rgb) {
        $this->targetrgb = color::hexToRgb($rgb);
        $this->targethsl = color::hsl($this->targetrgb[0], $this->targetrgb[1], $this->targetrgb[2]);
        $this->targetcolor = new color($this->targetrgb[0], $this->targetrgb[1], $this->targetrgb[2]);
    }

    public function loss($filters) {
        $color = new color(0, 0, 0);

        $color->invert($filters[0] / 100);
        $color->sepia($filters[1] / 100);
        $color->saturate($filters[2] / 100);
        $color->hueRotate($filters[3] * 3.6);
        $color->brightness($filters[4] / 100);
        $color->contrast($filters[5] / 100);

        $colorhsl = color::hsl($color->r, $color->g, $color->b);

        return (
            abs($color->r - $this->targetcolor->r) +
            abs($color->g - $this->targetcolor->g) +
            abs($color->b - $this->targetcolor->b) +
            abs($colorhsl["h"] - $this->targethsl["h"]) +
            abs($colorhsl["s"] - $this->targethsl["s"]) +
            abs($colorhsl["l"] - $this->targethsl["l"])
        );
    }

    private function spsa($a, $a2, $c, $values, $iters) {
        $alpha = 1;
        $gamma = 0.16666666666666666;

        $best = null;
        $bestloss = INF;

        $deltas = [];
        $highargs = [];
        $lowargs = [];

        for ($k = 0; $k < $iters; $k++) {
            $ck = $c / pow($k + 1, $gamma);

            for ($i = 0; $i < 6; $i++) {
                $deltas[$i] = (rand(0, 1) > 0.5) ? 1 : -1;
                $highargs[$i] = $values[$i] + $ck * $deltas[$i];
                $lowargs[$i] = $values[$i] - $ck * $deltas[$i];
            }

            $lossdiff = $this->loss($highargs) - $this->loss($lowargs);

            for ($i = 0; $i < 6; $i++) {
                $g = $lossdiff / (2 * $ck) * $deltas[$i];
                $ak = $a2[$i] / pow($a + $k + 1, $alpha);
                $values[$i] = $this->fix($values[$i] - $ak * $g, $i);
            }

            $loss = $this->loss($values);

            if ($loss < $bestloss) {
                $best = $values;
                $bestloss = $loss;
            }
        }

        return ["values" => $best, "loss" => $bestloss];
    }

    private function fix($value, $idx) {
        $max = 100;

        if ($idx == 2 /* saturate */) {
            $max = 7500;
        } else if ($idx == 4 /* brightness */ || $idx == 5 /* contrast */) {
            $max = 200;
        }

        if ($idx == 3 /* hue-rotate */) {
            if ($value > $max) {
                // Original code which led to the 'Deprecated: Implicit conversion from float to int loses precision' debug message:
                // $value %= $max.
                $value = (int)$value % $max;
            } else if ($value < 0) {
                // Original code which led to the 'Deprecated: Implicit conversion from float to int loses precision' debug message:
                // $value = $max + $value % $max.
                $value = $max + (int)$value % $max;
            }
        } else if ($value < 0) {
            $value = 0;
        } else if ($value > $max) {
            $value = $max;
        }

        return $value;
    }

    private function solvewide() {
        $a = 5;
        $c = 15;
        $a2 = [60, 180, 18000, 600, 1.2, 1.2];

        $best = ["loss" => INF];
        for ($i = 0; $best["loss"] > 25 && $i < 3; $i++) {
            $initial = [50, 20, 3750, 50, 100, 100];
            $result = $this->spsa($a, $a2, $c, $initial, 1000);
            if ($result["loss"] < $best["loss"]) {
                $best = $result;
            }
        }

        return $best;
    }

    private function solvenarrow($wide) {
        $a = $wide["loss"];
        $c = 2;
        $a1 = $a + 1;
        $a2 = [0.25 * $a1, 0.25 * $a1, $a1, 0.25 * $a1, 0.2 * $a1, 0.2 * $a1];
        return $this->spsa($a, $a2, $c, $wide["values"], 500);
    }

    private function css($filters) {
        return "invert(" . round($filters[0]) . "%) sepia(" . round($filters[1]) . "%) saturate(" .
                round($filters[2]) . "%) hue-rotate(" . round($filters[3] * 3.6) . "deg) brightness(" .
                round($filters[4]) . "%) contrast(" . round($filters[5]) . "%)";
    }

    public function solve() {
        // The original code tries to find _one_ filter set and returns it.
        // Unfortunately, as the calculation is based on a random component, this filter set does not necessarily
        // need to be perfect.
        // In theory, the admin who sees that a color is off, could easily purge the theme cache to trigger a
        // recalculation. But the more colors have been changed by the admin, the probability that at least one
        // color is off with every cache purge gets higher and higher.
        // Thus, we kill this with iron (as the filters are cached afterwards and we do not really have to care about time),
        // calculate multiple colors and pick the one with least loss.

        // Initialize the best filter up to now.
        $bestfilteruptonow = '';
        $leastlossuptonow = 9999;

        // Get the number of iterations which the admin wanted us to do.
        $imax = get_config('theme_boost_union', 'activityiconcolorfidelity');

        // Try the configured number of iterations to find a filter which fits really well (i.e. loss < 0.005).
        $i = 0;
        while ($leastlossuptonow > 0.005 && $i < $imax) {
            // Test another color.
            $i++;
            $nexttrycolor = $this->solveNarrow($this->solveWide());

            // If the loss is smaller than the one which is the best up to now.
            if ($nexttrycolor["loss"] < $leastlossuptonow) {
                // Remember this color as new best color.
                $bestfilteruptonow = $nexttrycolor;
                $leastlossuptonow = $nexttrycolor["loss"];
            }
        }

        // Return the best color which we have found.
        return [
            "values" => $bestfilteruptonow["values"],
            "loss" => $bestfilteruptonow["loss"],
            "filter" => $this->css($bestfilteruptonow["values"]),
        ];
    }

    /**
     * Helper function to check if the given CSS filter is close enough to the color.
     * This function is not contained in the original library and was implemented just for Behat testing.
     *
     * @param string $cssfilters The CSS filter string.
     * @param float $loss The loss which is acceptable.
     * @return bool
     */
    public function filter_is_close_enough($cssfilters, $loss) {
        // Verify input data.
        if (strlen($cssfilters) < 1 ||
                strpos($cssfilters, 'invert') === false ||
                strpos($cssfilters, 'sepia') === false ||
                strpos($cssfilters, 'saturate') === false ||
                strpos($cssfilters, 'hue-rotate') === false ||
                strpos($cssfilters, 'brightness') === false ||
                strpos($cssfilters, 'contrast') === false ||
                is_numeric($loss) == false) {
            return false;
        }

        // Split the provided filter by spaces.
        $filters = explode(' ', $cssfilters);

        // Extract only the values of the filters.
        foreach ($filters as &$f) {
            preg_match('#\((.*?)\)#', $f, $match);
            $f = $match[1];
        }

        // Remove the 'deg' suffix from the fourth filter (hue-rotate).
        $filters[3] = substr($filters[3], 0, -3);

        // Divide the fourth filter (hue-rotate) by 3.6 to revert the calculation from the css() function.
        $filters[3] = $filters[3] / 3.6;

        // Multiply all other filters by 100 to revert the percent calculation from the css() function.
        $filters[0] *= 100;
        $filters[1] *= 100;
        $filters[2] *= 100;
        $filters[4] *= 100;
        $filters[5] *= 100;

        // Compute the loss of the given color.
        $calculatedloss = $this->loss($filters);

        // Compare and return the result.
        if ($calculatedloss <= $loss) {
            return true;
        } else {
            return false;
        }
    }
}
