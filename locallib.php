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
 * Contains the functions to be executed on actions.php
 *
 * @package    block_ps_selfstudy
 * @copyright  2015 Andres Ramos
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Delete a request
 * @param integer $requestid
 * @return true if was deleted successfully, false otherwise
 */
function delete_request($requestid) {
    global $DB;

    // Delete course record.
    if (!$DB->delete_records('block_ps_selfstudy_request', array('id' => $requestid))) {
        return false;
    }

    // Delete all completions with this requests if any.
    $completionid = $DB->get_record('block_ps_selfstudy_complete', array('request_id' => $requestid), $fields = 'id');
    if ($completionid) {
        if (!$DB->delete_records('block_ps_selfstudy_complete', array('id' => $completionid->id))) {
            return false;
        }
        
    }
    return true;

}

/**
 * Deliver a request
 * @param integer $requestid
 * @return true if the request status was updated successfully, false otherwise
 */
function deliver_request($requestid) {
    global $DB;
    
    // Update the request status to 1, meaning it has been delivered.
    if (!$DB->update_record('block_ps_selfstudy_request', array('id' => $requestid, 'request_status' => '1'))) {
        return false;
    }
    
    // Notify the user that his/her course has been delivered.
    $sql = "SELECT  course_name AS coursename,
                    course_code AS coursecode,
                    request.student_id AS student_id,
                    firstname,
                    lastname,
                    email
              FROM  {block_ps_selfstudy_request} request
              JOIN  {block_ps_selfstudy_course} course ON course.id = request.course_id
              JOIN  {user} u ON u.id = student_id
             WHERE  request.id = ?";
    $requestinfo = $DB->get_record_sql($sql, array($requestid));
    notify_user($requestinfo);
    return true;
}

/**
 * Delete a course request and requests and completion associated.
 * @param   int $courseid with coursename,coursecode
 *          firstname,lastname and email of the user.
 * @return  true if was deleted successfully, false otherwise
 */
function delete_course_request($courseid) {
    global $DB;
    
    // Delete the course record.
    if (!$DB->delete_records('block_ps_selfstudy_course', array('id' => $courseid))) {
        return false;
    }

    // Delete all requests related to this course if any.
    $requestlist = $DB->get_records(
            'block_ps_selfstudy_request',
            array('course_id' => $courseid), $sort = '', $fields = 'id'
    );
    if ($requestlist) {
        foreach ($requestlist as $request) {
            delete_request($request->id);
        }
    }
    return true;
}

/**
 * Complete a course request.
 * @param   int $requestid
 * @return  true if was deleted successfully, false otherwise
 */
function complete_course_request($requestid) {
    global $DB;

    // Success when a user mark a course as completed.
    $today = time();
    $completion = new stdClass();
    $completion->request_id = $requestid;
    $completion->completion_status = "completed";
    $completion->completion_date = $today;

    if (!$DB->insert_record('block_ps_selfstudy_complete', $completion)) {
        return false;
    }
    return true;
}

/**
 * Add a new course request.
 * @param   object $request with all fields
 * @return  true if was added successfully, false otherwise
 */
function add_course_request($request) {
    global $DB;

    if (!$DB->insert_record('block_ps_selfstudy_request', $request)) {
        return false;
    }
    return true;
}

/**
 * Notify the user that a request was updated.
 * @param   object $requestinfo with coursename,coursecode
 *          firstname,lastname and email of the user.
 * @return  true if was deleted successfully, false otherwise
 */
function notify_user($requestinfo) {
    global $CFG;

    $subject = "Course Shipment Your order for $requestinfo->coursecode $requestinfo->coursename has been shipped";

    $userinfo = new stdClass;
    $userinfo->id = $requestinfo->student_id;
    $userinfo->email = $requestinfo->email;
    $userinfo->firstname = $requestinfo->firstname;
    $userinfo->lastname = $requestinfo->lastname;
    $userinfo->mailformat = 1;
    $userinfo->maildisplay = true;
    $userinfo->firstnamephonetic = '';
    $userinfo->lastnamephonetic = '';
    $userinfo->middlename = '';
    $userinfo->alternatename = '';
    $from = new stdClass;
    $from->email = "noreply@ibm.com";
    $from->firstname = "No ";
    $from->lastname = "Reply";

    $message = "
        <p>Hello $userinfo->firstname $userinfo->lastname,</p>

        <p>You should receive this order within the next week. Be sure to come back into
        the EPS system <a href='{$CFG->wwwroot}/blocks/ps_selfstudy/myrequests.php'>{$CFG->wwwroot}</a>
        and indicate the date you complete the course so that your training history can be updated.</p>

        <p>Thank you.</p>
    ";
    email_to_user($userinfo, $from, $subject, $message, $message, ",", false);
}