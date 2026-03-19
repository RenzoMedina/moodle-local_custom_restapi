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
 * External function to create a new course.
 *
 * @package   local_custom_restapi
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_custom_restapi\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * Class create_course
 * @package local_custom_restapi\external
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class create_course extends \external_api {

    /**
     * Defines the parameters for the create_course function.
     * @return \external_function_parameters
     */
    public static function execute_parameters() {
        return new \external_function_parameters([
            'fullname' => new \external_value(PARAM_TEXT, 'Full name of the course'),
            'shortname' => new \external_value(PARAM_TEXT, 'Short name of the course'),
            'startdate' => new \external_value(PARAM_INT, 'Start date of the course', VALUE_OPTIONAL, 0),
            'enddate' => new \external_value(PARAM_INT, 'End date of the course', VALUE_OPTIONAL, 0),
        ]);
    }
    /**
     * Executes the create_course function.
     * @param mixed $fullname
     * @param mixed $shortname
     * @param mixed $startdate
     * @param mixed $enddate
     * @return array []
     */
    public static function execute($fullname, $shortname, $startdate = 0, $enddate = 0) {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/course/lib.php');

        // Validate parameters.
        $params = self::validate_parameters(self::execute_parameters(), [
            'fullname' => $fullname,
            'shortname' => $shortname,
            'startdate' => $startdate,
            'enddate' => $enddate,
        ]);
        // Check permissions.
        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('moodle/course:create', $context);

        // Create course record.
        $course = new \stdClass();
        $course->fullname = $params['fullname'];
        $course->shortname = $params['shortname'];
        $course->startdate = $params['startdate'];
        $course->enddate = $params['enddate'];
        // Set default category to 1 (Miscellaneous) - adjust as needed.
        $course->category = 1;

        $course->id = $DB->insert_record('course', $course);

        return [
            'id'        => (int)$course->id,
            'fullname'  => $course->fullname,
            'shortname' => $course->shortname,
        ];
    }

    /**
     * Defines the return structure for the create_course function.
     * @return \external_single_structure
     */
    public static function execute_returns() {
        return new \external_single_structure([
            'id' => new \external_value(PARAM_INT, 'Course ID'),
            'fullname' => new \external_value(PARAM_TEXT, 'Course full name'),
            'shortname' => new \external_value(PARAM_TEXT, 'Course short name'),
        ]);
    }
}
