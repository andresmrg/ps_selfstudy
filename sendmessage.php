<?php 

require_once('../../config.php');
require_once("{$CFG->libdir}/moodlelib.php");


global $USER;

/*PREPARGIN MESSAGE*/
//get course name
if(isset($_GET['id'])) {
   @$courseid = $_GET['id']; 
   $course = $DB->get_record('block_ps_selfstudy_course', array ('id'=>@$courseid), $fields='*', $strictness=IGNORE_MISSING);
}


$subject = 'Course Shipment Your order for Course Code, Course Title has been shipped';
$message = "
<p><strong>$USER->firstname $USER->lastname</strong></p>

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
$from->firstname="Reply";

if(!email_to_user($user, $from, $subject, $message,$message, ",", false)) {
	print_error('nopermissiontoviewpage', 'error', '');
}