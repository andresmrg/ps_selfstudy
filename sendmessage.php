<?php 

require_once('../../lib/moodlelib.php');
require_once('../../config.php');

global $USER;

$subject = "subject";
$message = 'Here is my message';

$user = new stdClass;
$user->id = $USER->id;
$user->email = $USER->email;
$user->mailformat = 1;
$from->email="andrewramos@paradisosolutions.com";
$from->firstname="Andrew";
$from->lastname="Ramos";

if(email_to_user($user, $from, $subject, $message,$message))
{
	echo 'Message sended to all recipients';
}
else
{
	echo 'Message Not send ..please Try again later..';
}