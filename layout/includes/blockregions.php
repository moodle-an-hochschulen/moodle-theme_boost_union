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
 * @copyright 2022 bdecent gmbh <https://bdecent.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Require course library.
require_once($CFG->dirroot . '/course/lib.php');

// Require own locallib.php.
require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

/**
 * Class for composing the additional block regions for the mustach templates.
 *
 * @package    theme_boost_union
 * @copyright  2022 bdecent gmbh <https://bdecent.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class additionalregions {

    /**
     * @var array $regions List of page regions.
     */
    private $regions;

    /**
     * Constructor.
     * Get the all regions in theme_boost_union_get_additional_regions function.
     */
    public function __construct() {
        global $PAGE;

        $pageregions = $PAGE->blocks->get_regions();
        $this->regions = theme_boost_union_get_additional_regions($pageregions);
    }

    /**
     * Generate regions data.
     * Get the block region HTML for additional block regions.
     * Checking the view and edit capability for additional block regions.
     * Added addblockbuttons for each block regions.
     *
     * @return array $regionsdata
     */
    public function regionsdata() {
        global $OUTPUT, $PAGE;

        $regionsdata = [];
        foreach ($this->regions as $name => $region) {

            if (!has_capability('theme/boost_union:viewregion'.$name, $PAGE->context)) {
                $regionsdata[$name] = ['hasblocks' => false];
                continue;
            }

            $regionhtml = $OUTPUT->blocks($region);
            $blockbutton = (has_capability('theme/boost_union:editregion'.$name, $PAGE->context)) ?
                             $OUTPUT->addblockbutton($region) : '';
            $regionsdata[$name] = [
                'hasblocks' => (strpos($regionhtml, 'data-block=') !== false || !empty($blockbutton)),
                'regionhtml' => $regionhtml,
                'addblockbutton' => $blockbutton,
            ];
        }
        return $regionsdata;
    }

    /**
     * Return region class for main inner wrapper element based on the enabled additional block regions.
     *
     * @param array $regionsdata
     * @return string $maininnerwrapperclass
     */
    public function maininnerwrapperclass($regionsdata) {

        // If both outside-left and outside-right region is enabled.
        if ((!empty($regionsdata['outsideleft']['hasblocks'])) &&
                (!empty($regionsdata['outsideright']['hasblocks']))) {
            $maininnerwrapperclass = 'main-inner-outside-left-right';

            // If only outside-left region is enabled.
        } else if (!empty($regionsdata['outsideleft']['hasblocks'])) {
            $maininnerwrapperclass = 'main-inner-outside-left';

            // If only outside-right region is enabled.
        } else if (!empty($regionsdata['outsideright']['hasblocks'])) {
            $maininnerwrapperclass = 'main-inner-outside-right';

            // If neither outside-left nor outside-right regions are enabled.
        } else {
            $maininnerwrapperclass = 'main-inner-outside-none';
        }

        return $maininnerwrapperclass;
    }

    /**
     * Calculate region hasblocks count and add column classes for additional block regions.
     *
     * @param array $regions List of regions to generate class. i.e Footer and Canvas regions.
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
            'class' => 'col-xl-'.(($regioncount > 0 ) ? round(12 / $regioncount) : '12' ),
        ];
    }

    /**
     * Add the offcanvas block region data to regionsdata.
     *
     * @param array $regionsdata
     */
    public function addoffcanvasdata(&$regionsdata) {
        $list = $this->countcolclass([
                'offcanvasleft',
                'offcanvasright',
                'offcanvascenter', ], $regionsdata);

        $regionsdata['offcanvas'] = [
                'hasblocks' => ($list['count'] > 0) ? true : false,
                'class' => $list['class'],
        ];
    }

    /**
     * Add the footer block region data to regionsdata.
     *
     * @param array $regionsdata
     */
    public function addfooterdata(&$regionsdata) {
        $list = $this->countcolclass([
                'footerleft',
                'footerright',
                'footercenter', ], $regionsdata);

        $regionsdata['footer'] = [
                'hasblocks' => ($list['count'] > 0) ? true : false,
                'class' => $list['class'],
        ];
    }

    /**
     * Generate data to export for layouts.
     *
     * @return array region data
     */
    public function export_for_template() {
        global $PAGE;

        $regionsdata = $this->regionsdata();
        $this->addoffcanvasdata($regionsdata);
        $this->addfooterdata($regionsdata);

        $maininnerwrapperclass = $this->maininnerwrapperclass($regionsdata);

        return [
            'regions' => $regionsdata,
            'userisediting' => $PAGE->user_is_editing(),
            'maininnerwrapperclass' => $maininnerwrapperclass,
            'outsideregionsplacement' => 'main-inner-outside-'.get_config('theme_boost_union', 'outsideregionsplacement'),
            'outsidebottomwidth' => 'theme-block-region-outside-'.get_config('theme_boost_union', 'blockregionoutsidebottomwidth'),
            'outsidetopwidth' => 'theme-block-region-outside-'.get_config('theme_boost_union', 'blockregionoutsidetopwidth'),
            'footerwidth' => 'theme-block-region-footer-'.get_config('theme_boost_union', 'blockregionfooterwidth'),
        ];
    }
}

// Compose additional block regions.
$customregions = new additionalregions();

// Add additional block regions to the template context.
$templatecontext += $customregions->export_for_template();
