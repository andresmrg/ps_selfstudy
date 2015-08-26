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


$subject = get_string('subject', 'block_ps_selfstudy';
$message = "
<b>Dear friend,

You have requested the a physical copy of the course {@$course->course_name} and you will receive it within the next 2 weeks.

If you have any problem please contact our Shipping Director at email@domain.com

Thank you.
";

$user = new stdClass;
$user->id = $USER->id;
$user->email = $USER->email;
$user->mailformat = 1;
$from = new stdClass;
$from->email="noreply@ibm.com";
$from->firstname="No-Reply";

if(!email_to_user($user, $from, $subject, $message,$message, ",", false)) {
	print_error('nopermissiontoviewpage', 'error', '');
}