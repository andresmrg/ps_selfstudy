<?php 

require_once('../../lib/moodlelib.php');

$emaildata = new stdClass();
$emaildata->touser = 'andrewramos@paradisosolutions.com';
$emaildata->fromuser = 'andresmao2@gmail.com';
$emaildata->subject = 'Test email';
$emaildata->message = 'Here is my message';
$emaildata->messagehtml = '';

if(!email_to_user($toUser, $fromUser, $subject, $messageText, $messageHtml, ", ", true)) {
	echo "error";
}