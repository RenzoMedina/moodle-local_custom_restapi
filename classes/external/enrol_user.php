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
 * External function to enrol a user in a course.
 *
 * @package   local_custom_restapi
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_custom_restapi\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * External function to enrol a user in a course.
 *
 * @package   local_custom_restapi
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class enrol_user extends \external_api {

    /**
     * Defines the parameters for the enrol_user function.
     * @return \external_function_parameters
     */
    public static function execute_parameters() {
        return new \external_function_parameters([
            'userid'   => new \external_value(PARAM_INT, 'User ID'),
            'courseid' => new \external_value(PARAM_INT, 'Course ID'),
            'roleid'   => new \external_value(PARAM_INT, 'Role ID', VALUE_OPTIONAL, 5),
        ]);
    }

    /**
     * Enrols a user in a course.
     * @param int $userid User ID
     * @param int $courseid Course ID
     * @param int $roleid Role ID
     * @return array{courseid: int, roleid: int, userid: int}
     */
    public static function execute($userid, $courseid, $roleid = 5) {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/enrol/locallib.php');

        $studentrole = $DB->get_record('role', ['shortname' => 'student'], '*', MUST_EXIST);
        $roleid = $params['roleid'] ?? $studentrole->id;
        // Validate parameters.
        $params = self::validate_parameters(self::execute_parameters(), [
            'userid' => $userid,
            'courseid' => $courseid,
            'roleid' => $roleid,
        ]);
        $course = $DB->get_record('course', ['id' => $params['courseid']], '*', MUST_EXIST);
        $context = \context_course::instance($course->id);
        self::validate_context($context);
        require_capability('enrol/manual:enrol', $context);

        $DB->get_record('user', ['id' => $params['userid']], '*', MUST_EXIST);
        $enrol = enrol_get_plugin('manual');
        $instance = $DB->get_record('enrol',
                    ['courseid' => $params['courseid'],
                    'enrol' => 'manual'],
                    '*',
                    MUST_EXIST);
        $enrol->enrol_user($instance, $params['userid'], $params['roleid']);
        return [
            'userid' => $params['userid'],
            'courseid' => $params['courseid'],
            'roleid' => $params['roleid'],
        ];
    }

    /**
     * Defines the return structure for the enrol_user function.
     * @return \external_single_structure
     */
    public static function execute_returns() {
        return new \external_single_structure([
            'userid' => new \external_value(PARAM_INT, 'User ID'),
            'courseid' => new \external_value(PARAM_INT, 'Course ID'),
            'roleid' => new \external_value(PARAM_INT, 'Role ID'),
        ]);
    }
}
