<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Web service local plugin.
 *
 * @package     local_custom_restapi
 * @copyright   2026 Renzo Medina <medinast30@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_custom_restapi_get_courses' => [
        'classname' => 'local_custom_restapi\external\get_courses',
        'methodname' => 'execute',
        'description' => 'Returns list of active courses.',
        'type' => 'read',
        'ajax' => true,
    ],
    'local_custom_restapi_create_course' => [
        'classname'   => 'local_custom_restapi\external\create_course',
        'methodname' => 'execute',
        'description' => 'Creates a new course.',
        'type' => 'write',
        'ajax' => true,
    ],
    'local_custom_restapi_enrol_user' => [
        'classname'   => 'local_custom_restapi\external\enrol_user',
        'methodname'  => 'execute',
        'description' => 'Enrols a user in a course',
        'type'        => 'write',
        'ajax'        => true,
    ],
    'local_custom_restapi_get_user_courses' => [
        'classname'   => 'local_custom_restapi\external\get_user_courses',
        'methodname'  => 'execute',
        'description' => 'Returns courses for a user',
        'type'        => 'read',
        'ajax'        => true,
    ],
    'local_custom_restapi_get_user' => [
        'classname'   => 'local_custom_restapi\external\get_user',
        'methodname'  => 'execute',
        'description' => 'Returns user details',
        'type'        => 'read',
        'ajax'        => true,
    ],
];

$services = [
    'Custom REST API' => [
        'functions' => [
            'local_custom_restapi_get_courses',
            'local_custom_restapi_create_course',
            'local_custom_restapi_enrol_user',
            'local_custom_restapi_get_user_courses',
            'local_custom_restapi_get_user',
        ],
        'restrictedusers' => 0,
        'enabled' => 1,
        'shortname' => 'local_custom_restapi',
    ],
];
