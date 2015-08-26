<?php 

require_once('../../config.php');
require_once("{$CFG->libdir}/moodlelib.php");


global $USER;

/*PREPARGIN MESSAGE*/
//get course name

$subject = "Course Shipment Your order for $course->course_code $course->course_name has been shipped";
$message = "
<p>$USER->firstname $USER->lastname</p>

<p>You should receive this order within the next week. Be sure come back into the system and indicate the date you complete the course so that your training history can be update.</p>

<p>Thank you.</p>
";

$user = new stdClass;
$user->id = $USER->id;
$user->email = $USER->email;
$user->mailformat = 1;
$from = new stdClass;
$from->email="noreply@ibm.com";
$from->firstname="No ";
$from->lastname="Reply";

if(!email_to_user($user, $from, $subject, $message,$message, ",", false)) {
	print_error('emailfail', 'error', '');
}