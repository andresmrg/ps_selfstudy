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
require_once($CFG->libdir . '/moodlelib.php');

global $CFG;

$subject = "Course Shipment Your order for $course->course_code $course->course_name has been shipped";

$userinfo = new stdClass;
$userinfo->id = $userid->student_id;
$userinfo->email = $user->email;
$userinfo->firstname = $user->firstname;
$userinfo->lastname = $user->lastname;
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
the EPS system <a href='{$CFG->wwwroot}/blocks/ps_selfstudy/myrequests.php'>$CFG->wwwroot</a>
and indicate the date you complete the course so that your training history can be updated.</p>

<p>Thank you.</p>
";

if (!email_to_user($userinfo, $from, $subject, $message, $message, ",", false)) {
    print_error('emailfail', 'error', '');
}