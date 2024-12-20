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
 * Theme Boost Union - Flavours library
 *
 * @package    theme_boost_union
 * @copyright  2022 Alexander Bias, lern.link GmbH <alexander.bias@lernlink.de>
 * @copyright  on behalf of Zurich University of Applied Sciences (ZHAW)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Helper function to check if any and which flavour should be applied to this page output.
 *
 * It iterates over the existing flavours from top to bottom, checks each flavour if it applies to the given page output
 * and stops as soon as a particular flavour applies.
 *
 * @return stdClass|null The flavour object if any flavour applies, otherwise null.
 */
function theme_boost_union_get_flavour_which_applies() {
    global $CFG, $DB, $PAGE, $USER;

    // Security net:
    // This function is called from every Moodle page.
    // BUT: If the plugin is not properly installed or updated yet, we must not access any database table
    // as this would trigger a "Read from database" error.
    if (get_config('theme_boost_union', 'version') < 2022080916) {
        return null;
    }

    // Initialize static variables for the flavour which applies as this function might be called multiple
    // times during a page output.
    static $flavourchecked, $appliedflavour;

    // If the flavour which applies has already been checked on this page.
    if ($flavourchecked == true) {
        // Directly return the flavour which applies.
        return $appliedflavour;

        // Otherwise.
    } else {
        // If we are on the preview page.
        $previewurl = new core\url('/theme/boost_union/flavours/preview.php');
        if ($previewurl->compare($PAGE->url, URL_MATCH_BASE) == true) {
            // Get the flavour from the URL.
            $previewflavourid = required_param('id', PARAM_INT);

            // Get and remember the given flavour.
            $appliedflavour = $DB->get_record('theme_boost_union_flavours', ['id' => $previewflavourid]);

            // Remember the fact that the flavour has been checked for subsequent runs of this function.
            $flavourchecked = true;

            // And return the flavour.
            return $appliedflavour;
        }

        // If the flag to purge the flavours cache is set for this user.
        if (get_user_preferences('theme_boost_union_flavours_purgesessioncache', false) == true) {
            // Purge the flavours cache for this user.
            \core_cache\helper::purge_by_definition('theme_boost_union', 'flavours');
        }

        // Create cache for flavours.
        $cache = cache::make('theme_boost_union', 'flavours');

        // If the page has a category ID.
        if ($PAGE->category != null && $PAGE->category->id != null) {
            // Remember the page category ID for easier usage.
            $pagecategoryid = $PAGE->category->id;

            // Otherwise.
        } else {
            // Remember the page category ID as empty.
            $pagecategoryid = 0;
        }

        // Get the cached flavour for the current user and the page category ID.
        $cachedflavour = $cache->get($pagecategoryid);

        // If we got a cached flavour.
        if ($cachedflavour !== false) {
            // Remember the flavour object as we have found a match.
            $appliedflavour = $cachedflavour;

            // Remember the fact that the flavour has been checked for subsequent runs of this function.
            $flavourchecked = true;

            // And return the flavour.
            return $appliedflavour;
        }

        // Otherwise, if we do not have a cached flavour, we have to go down the rabbithole and calculate the flavour
        // which applies.

        // If the page has a category ID.
        if ($pagecategoryid != 0) {
            // Get the page category from the category manager and accept that it may not be found.
            $pagecategory = \core_course_category::get($pagecategoryid, IGNORE_MISSING);

            // If we got a valid category.
            if ($pagecategory != null) {
                // Pick the category path without the leading slash.
                $pagecategorypath = substr($pagecategory->path, 1);
                // Fill the parent ID array.
                $parentcategoryids = explode('/', $pagecategorypath);

                // Otherwise.
            } else {
                // Just remember an empty array to avoid breaking the following code.
                $parentcategoryids = [];
            }
        }

        // Initialize variable to hold the user's cohorts.
        // They won't be fetched before the first flavour is configured to use cohorts, but they must not be fetched for each
        // such flavour again.
        $usercohorts = null;

        // Get all flavours from the DB.
        // The more flavours you have, the more this query will become heavier.
        // However, it is just called once per page category ID within a session, so we accept this for now.
        $flavours = $DB->get_records('theme_boost_union_flavours', [], 'sort ASC');

        // Iterate over the flavours.
        foreach ($flavours as $f) {
            // If the flavour is configured to apply to categories and this page has a category id.
            if ($f->applytocategories == true && $pagecategoryid != 0) {
                // Decode the configured categories.
                $categoryids = json_decode($f->applytocategories_ids);

                // If at least one category is configured.
                if (!empty($categoryids)) {
                    // Iterate over the configured categories.
                    foreach ($categoryids as $c) {
                        // If the flavour is configured to apply to the page's category.
                        if ($c == $pagecategoryid) {
                            // Remember the flavour object as we have found a match.
                            $appliedflavour = $f;

                            // Store the flavour into the cache.
                            $cache->set($pagecategoryid, $f);

                            // Remember the fact that the flavour has been checked for subsequent runs of this function.
                            $flavourchecked = true;

                            // And return the flavour.
                            return $appliedflavour;
                        }

                        // If the flavour is configured to include all subcategories and the category at hand is in the list of
                        // the page's parent categories.
                        if ($f->applytocategories_subcats == true && in_array($c, $parentcategoryids)) {
                            // Remember the flavour object as we have found a match.
                            $appliedflavour = $f;

                            // Store the flavour into the cache.
                            $cache->set($pagecategoryid, $f);

                            // Remember the fact that the flavour has been checked for subsequent runs of this function.
                            $flavourchecked = true;

                            // And return the flavour.
                            return $appliedflavour;
                        }
                    }
                }
            }

            // If the flavour is configured to apply to cohorts.
            if ($f->applytocohorts == true) {

                // If the user cohorts have not be fetched up to now.
                if ($usercohorts == null) {
                    // Require cohort library.
                    require_once($CFG->dirroot.'/cohort/lib.php');

                    // Get and remember the user's cohorts.
                    $usercohorts = cohort_get_user_cohorts($USER->id);
                }

                // If this user has cohorts.
                if (!empty($usercohorts)) {
                    // Decode the configured cohorts.
                    $cohortids = json_decode($f->applytocohorts_ids);

                    // If at least one cohort is configured.
                    if (!empty($cohortids)) {
                        // If the user is a member of one of these cohorts.
                        if (theme_boost_union_flavours_cohorts_is_member($USER->id, $cohortids) == true) {
                            // Remember the flavour object as we have found a match.
                            $appliedflavour = $f;

                            // Store the flavour into the cache.
                            $cache->set($pagecategoryid, $f);

                            // Remember the fact that the flavour has been checked for subsequent runs of this function.
                            $flavourchecked = true;

                            // And return the flavour.
                            return $appliedflavour;
                        }
                    }
                }
            }
        }

        // If we haven't found any flavour which applies to the given page output, remember null as flavour object.
        $appliedflavour = null;

        // Store the flavour into the cache.
        $cache->set($pagecategoryid, null);

        // Remember the fact that the flavour has been checked for subsequent runs of this function.
        $flavourchecked = true;

        // And return the flavour.
        return $appliedflavour;
    }
}

/**
 * Helper function which gets the filename of the uploaded if to a given flavour filearea with the given itemid.
 * @param string $filearea The filearea (without the 'flavours_' prefix).
 * @param int $itemid The item id within the filearea.
 *
 * @return string|null The filename, if a file was uploaded, or null, if no file was uploaded to the filearea.
 */
function theme_boost_union_flavours_get_filename($filearea, $itemid) {
    // Get system context.
    $context = context_system::instance();

    // Get file storage.
    $fs = get_file_storage();

    // Get all files from the given filearea.
    $files = $fs->get_area_files($context->id, 'theme_boost_union', 'flavours_'.$filearea, $itemid,
            'sortorder,filepath,filename', false);
    if ($files) {
        // Just pick the first file - we are sure that there is just one file.
        $file = reset($files);
        // Get the file name.
        $filename = $file->get_filename();
    } else {
        $filename = null;
    }

    // Return the file name.
    return $filename;
}


/**
 * Helper function which checks if a user is a member of the given cohorts.
 * @param int $userid
 * @param array $cohorts
 *
 * @return bool
 */
function theme_boost_union_flavours_cohorts_is_member($userid, $cohorts) {
    global $DB;

    if (!empty($cohorts)) {
        // Create IN statement for cohorts.
        list($in, $params) = $DB->get_in_or_equal($cohorts);
        // Add param for userid.
        $params[] = $userid;
        // Return true if "userid = " . $userid . " AND cohortid IN " . $cohorts.
        return $DB->record_exists_select('cohort_members', "cohortid $in AND userid = ?", $params);
    } else {
        return false;
    }
}

/**
 * Helper function which checks if a flavour exists which is configured to apply to the given cohort.
 *
 * @param int $cohortid
 *
 * @return bool
 */
function theme_boost_union_flavour_exists_for_cohort($cohortid) {
    global $DB;

    // Get the flavours which are configured to apply to (any) cohort.
    $flavoursforcohorts = $DB->get_records('theme_boost_union_flavours', ['applytocohorts' => 1], '', 'applytocohorts_ids');

    // Iterate over the flavours.
    foreach ($flavoursforcohorts as $f) {
        // Decode JSON.
        $cohorts = json_decode($f->applytocohorts_ids);

        // If the given cohort is configured in this flavour.
        if (in_array($cohortid, $cohorts)) {
            return true;
        }
    }

    // We didn't find any matching cohort, return false.
    return false;
}

/**
 * Helper function to get a config item from the given flavour ID.
 *
 * This function should only be used during the SCSS generation process (where the generated SCSS will be cached afterwards).
 * It should not be used during the page output directly as it will fetch the flavour config item directly from the database.
 *
 * @param string $flavourid The flavour id.
 * @param string $configkey The config key.
 * @return string|null The config item if it exists, otherwise null.
 */
function theme_boost_union_get_flavour_config_item_for_flavourid(string $flavourid, string $configkey) {
    global $DB;

    // Initialize static variable for the flavour record as this function might be called multiple times during a page output.
    static $flavourrecord;

    // If the flavour has not been been fetched yet.
    if ($flavourrecord == null) {
        // Get the given flavour record with the given flavour ID from the database.
        $flavourrecord = $DB->get_record('theme_boost_union_flavours', ['id' => $flavourid]);
    }

    // If the flavour record has a config item with the given key.
    if (isset($flavourrecord->{$configkey})) {
        // Return it.
        return $flavourrecord->{$configkey};
    }

    // Fallback: Return null.
    return null;
}
