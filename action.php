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
 * This file will handle all requests and execute them
 * based on the action peformed.
 *
 * @package    block_ps_selfstudy
 * @copyright  2015 Andres Ramos
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/blocks/ps_selfstudy/locallib.php');

global $CFG;

$action     = optional_param('action',  0,  PARAM_NOTAGS);
$requestid  = optional_param('requestid', 0, PARAM_NOTAGS);
$page       = optional_param('page', 0, PARAM_NOTAGS);
$courseid   = optional_param('courseid', 0, PARAM_NOTAGS);

switch ($action) {
    case 'deleterequest':

        $result = delete_request($requestid);
        if ($result) {
            // Redirect the user to the page where the deletion was made.
            $url = new moodle_url($CFG->wwwroot . '/blocks/ps_selfstudy/viewrequests.php');
            redirect($url);
        }
        break;

    case 'deliver':

        $result = deliver_request($requestid);
        if ($result) {
            $url = new moodle_url($CFG->wwwroot . '/blocks/ps_selfstudy/viewrequests.php');
            redirect($url);
        }
        break;

    case 'deletecourse':

        $result = delete_course_request($courseid);
        if ($result) {
            $url = new moodle_url($CFG->wwwroot . '/blocks/ps_selfstudy/managecourses.php?success=del');
            redirect($url);
        }
        break;

    case 'completecourse':

        $result = complete_course_request($requestid);
        if ($result) {
            $url = new moodle_url($CFG->wwwroot . '/blocks/ps_selfstudy/myrequests.php?success=completed');
            redirect($url);
        }
        break;

    case 'go':

        // Success when a user request a link type course.
        $today = time();
        $request = new stdClass();
        $request->student_id = $USER->id;
        $request->course_id = $courseid;
        $request->request_date = $today;
        $request->request_status = 2;

        $result = add_course_request($request);

        // Get the course link and redirect the user.
        if ($result) {
            $link = $DB->get_record('block_ps_selfstudy_course', array('id' => $courseid), $fields = 'course_link');
            $url = $link->course_link;
            if (!preg_match("~^(ht)tps?://~i", $url)) {
                $url = "http://" . $url;
            }
            $externalurl = new moodle_url($url);
            redirect($externalurl);
        }
        break;

    default:

        // Redirect to myrequest page.
        $url = new moodle_url($CFG->wwwroot . '/blocks/ps_selfstudy/myrequests.php');
        redirect($url);
        break;
}