<?php 

require_once('../../config.php');
require_once("{$CFG->libdir}/moodlelib.php");


global $USER;

$subject = "subject";
$message = 'Here is my message';

$user = new stdClass;
$user->id = $USER->id;
$user->email = $USER->email;
$user->mailformat = 1;
$from = new stdClass;
$from->email="andrewramos@paradisosolutions.com";
$from->firstname="Andrew";
$from->lastname="Ramos";

if(!email_to_user($user, $from, $subject, $message,$message, ",", false)) {
	echo 'Message failed';
} else {
	echo 'Sent';
}