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
 * External function to get user details.
 *
 * @package   local_custom_restapi
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_custom_restapi\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * Class get_user
 * @package local_custom_restapi\external
 * @copyright 2026, Renzo Medina <medinast30@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_user extends \external_api {

    /**
     * Defines the parameters for the get_user function.
     * @return \external_function_parameters
     */
    public static function execute_parameters() {
        return new \external_function_parameters([]);
    }

    /**
     * Executes the get_user function.
     * @param int $userid User ID
     * @return array User details
     */
    public static function execute() {
        global $DB;
        $users = $DB->get_records('user', null, 'id ASC', 'id, username, email, firstname, lastname');
        $result = [];
        foreach ($users as $user) {
            if ($user->id == 1) {
                continue;
            }
            $result[] = [
                'id' => (int)$user->id,
                'username' => $user->username,
                'email' => $user->email,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
            ];
        }
        return $result;
    }

    /**
     * Defines the return structure for the get_user function.
     * @return \external_single_structure
     */
    public static function execute_returns() {
        return new \external_multiple_structure(
            new \external_single_structure([
                'id' => new \external_value(PARAM_INT, 'User ID'),
                'username' => new \external_value(PARAM_TEXT, 'Username'),
                'email' => new \external_value(PARAM_TEXT, 'Email address'),
                'firstname' => new \external_value(PARAM_TEXT, 'First name'),
                'lastname' => new \external_value(PARAM_TEXT, 'Last name'),
            ])
        );
    }
}
