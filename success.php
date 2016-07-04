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
 * This file sends the message to the user when he has submit a course request.
 *
 * @package block_ps_selfstudy
 * @copyright 2015 Andres Ramos
 */

require_once(__DIR__ . '/../../config.php');

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

global $DB, $USER;

$id = optional_param('id',  0,  PARAM_INT);
$status = optional_param('status',  0,  PARAM_NOTAGS);
$courseid = optional_param('courseid',  0,  PARAM_INT);
$requestid = optional_param('rid',  0,  PARAM_INT);

// Success when the course has been shipped.
if ($id && $status && $courseid) {

    $course = $DB->get_record('block_ps_selfstudy_course', array('id' => $courseid), $fields = 'course_name,course_code');

    // Get user data to send email.
    $userid = $DB->get_record('block_ps_selfstudy_request', array('id' => $id), $fields = 'student_id');
    $user = $DB->get_record('user', array('id' => $userid->student_id), $fields = 'firstname,lastname,email');

    if (!$DB->update_record('block_ps_selfstudy_request', array('id' => $id, 'request_status' => $status))) {
        print_error('inserterror', 'block_ps_selfstudy');
    }
    include('sendmessage.php');
    $url = new moodle_url('/blocks/ps_selfstudy/viewrequests.php');
    redirect($url);

} else if ($requestid) {

    // Success when a user mark a course as completed.
    $today = time();

    $completion = new stdClass();
    $completion->request_id = $requestid;
    $completion->completion_status = "completed";
    $completion->completion_date = $today;

    if (!$DB->insert_record('block_ps_selfstudy_complete', $completion)) {
        print_error('cannotsavedata', 'error', '');
    }
    $url = new moodle_url('/blocks/ps_selfstudy/myrequests.php?success=completed');
    redirect($url);

} else if ($id) {

    // Success when a user request a link type course.
    $courseid = $id;
    $today = time();
    $request = new stdClass();
    $request->student_id = $USER->id;
    $request->course_id = $courseid;
    $request->request_date = $today;
    $request->request_status = 2;

    if (!$DB->insert_record('block_ps_selfstudy_request', $request)) {
        print_error('inserterror', 'block_ps_selfstudy');
    }

    $link = $DB->get_record('block_ps_selfstudy_course', array('id' => $courseid), $fields = 'course_link');
    $url = $link->course_link;
    if (!preg_match("~^(ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }

    $externalurl = new moodle_url($url);
    redirect($externalurl);

} else {
    $url = new moodle_url('/blocks/ps_selfstudy/myrequests.php');
    redirect($url);
}