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
 * External function to get user courses.
 *
 * @package   local_custom_restapi
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_custom_restapi\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * Class get_user_courses
 * @package local_custom_restapi\external
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_user_courses extends \external_api {

    /**
     * Defines the parameters for the get_user_courses function.
     * @return \external_function_parameters
     */
    public static function execute_parameters() {
        return new \external_function_parameters([
            'userid' => new \external_value(PARAM_INT, 'User ID'),
        ]);
    }

    /**
     * Executes the get_user_courses function.
     * @param int $userid User ID
     * @return array User courses
     */
    public static function execute($userid) {
        global $DB;
        // Validate parameters.
        $params = self::validate_parameters(self::execute_parameters(), [
            'userid' => $userid,
        ]);
        $DB->get_record('user', ['id' => $params['userid']], '*', MUST_EXIST);
        // Get courses the user is enrolled in.
        $courses = enrol_get_users_courses($params['userid'], true, 'id, fullname, shortname, startdate, enddate');
        // Prepare the result array.
        $result = [];
        foreach ($courses as $course) {
            $result[] = [
                'id' => (int)$course->id,
                'fullname' => $course->fullname,
                'shortname' => $course->shortname,
                'startdate' => userdate($course->startdate, get_string('strftimedate', 'langconfig')),
                'enddate' => userdate($course->enddate, get_string('strftimedate', 'langconfig')),
            ];
        }
        return $result;
    }

    /**
     * Defines the return structure for the get_user_courses function.
     * @return \external_multiple_structure
     */
    public static function execute_returns() {
        return new \external_multiple_structure(
            new \external_single_structure([
                'id' => new \external_value(PARAM_INT, 'Course ID'),
                'fullname' => new \external_value(PARAM_TEXT, 'Course full name'),
                'shortname' => new \external_value(PARAM_TEXT, 'Course short name'),
                'startdate' => new \external_value(PARAM_TEXT, 'Course start date'),
                'enddate' => new \external_value(PARAM_TEXT, 'Course end date'),
            ])
        );
    }
}
