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
 * Theme Boost Union - Additional regions.
 *
 * @package   theme_boost_union
 * @copyright 2022 Luca Bösch, BFH Bern University of Applied Sciences luca.boesch@bfh.ch
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

// Require own locallib.php.
require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

/**
 * Add to the additional block regions.
 *
 * @package    theme_boost_union
 * @copyright  2022 Moodle an Hochschulen e.V. <kontakt@moodle-an-hochschulen.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class additionalregions {

    /**
     * Constructor.
     * Get the all regions in theme_boost_union_additional_regions function.
     */
    public function __construct() {
        global $PAGE;
        $this->page = $PAGE;
        $pageregions = $this->page->blocks->get_regions();
        $this->regions = theme_boost_union_additional_regions($pageregions);
    }

    /**
     * Generate regions data.
     * Get the block region HTML for additional block regions.
     * Checking the view and edit capability for additional block regions.
     * Added addblockbuttons for each block regions.
     * @return array $regionsdata
     */
    public function regionsdata() {
        global $OUTPUT;
        $regionsdata = [];
        foreach ($this->regions as $name => $region) {

            if (!has_capability('theme/boost_union:viewregion'.$name, $this->page->context)) {
                $regionsdata[$name] = ['hasblocks' => false];
                continue;
            }

            $regionhtml = $OUTPUT->blocks($region);
            $blockbutton = (has_capability('theme/boost_union:editregion'.$name, $this->page->context)) ?
                             $OUTPUT->addblockbutton($region) : '';
            $regionsdata[$name] = [
                'hasblocks' => (strpos($regionhtml, 'data-block=') !== false || !empty($blockbutton)),
                'regionhtml' => $regionhtml,
                'addblockbutton' => $blockbutton
            ];
        }
        return $regionsdata;
    }

    /**
     * Return some region classes for additional block regions.
     * @param array $regionsdata
     * @return array $regionsdata
     */
    public function regionclass($regionsdata) {

        if ((!empty($regionsdata['left']['hasblocks'])) && (!empty($regionsdata['right']['hasblocks']))) {
            $regionclass = 'main-content-region-block';
        } else if (!empty($regionsdata['left']['hasblocks'])) {
            $regionclass = 'main-content-left-region';
        } else if (!empty($regionsdata['right']['hasblocks'])) {
            $regionclass = 'main-content-right-region';
        }

        return isset($regionclass) ? $regionclass : '';
    }

    /**
     * Calculate region hasblocks count and add column classes for additional block regions.
     * @param array $regions List of regions to gnereate class. i.e Footer and Canvas regions
     * @param array $regionsdata
     * @return array set of region contents
     */
    public function countcolclass($regions, $regionsdata) {
        $regioncount = 0;
        foreach ($regions as $region) {
            if (isset($regionsdata[$region])) {
                $regioncount += ($regionsdata[$region]['hasblocks']) ? true : false;
            }
        }
        return [
            'count' => $regioncount,
            'class' => 'col-xl-'.(($regioncount > 0 ) ? round(12 / $regioncount) : '12' )
        ];
    }

    /**
     * Add the offcanvas block region data to regionsdata.
     * @param array $regionsdata
     */
    public function addcanvasdata(&$regionsdata) {
        $list = $this->countcolclass([
            'offcanvasleft',
            'offcanvasright',
            'offcanvascenter'], $regionsdata);

        $regionsdata['offcanvas'] = [
            'hasblocks' => ($list['count'] > 0) ? true : false,
            'class' => $list['class'],
        ];
    }

    /**
     * Generate data to export for layouts.
     * @return array region data
     */
    public function export_for_template() {
        $regionsdata = $this->regionsdata();
        $this->addcanvasdata($regionsdata);

        $regionclass = $this->regionclass($regionsdata);
        $footerclass = $this->countcolclass([
            'footerleft',
            'footerright',
            'footercenter'
        ], $regionsdata);
        return [
            'regions' => $regionsdata,
            'regionclass' => $regionclass,
            'footerclass' => $footerclass['class'],
            'mainregionclass' => 'main-region-block'
        ];
    }
}

$customregions = new additionalregions();
$templatecontext += $customregions->export_for_template();
