<?php 

require "../../config.php";

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

global $DB;

if(isset($_GET['id']) & isset($_GET['status'])) {

	$id = $_GET['id'];
	$status = $_GET['status'];

	if (!$DB->update_record('block_ps_selfstudy_request', array('id'=>$id,'request_status'=>$status))) {
   		print_error('inserterror', 'block_ps_selfstudy');
   	}
   	$url = new moodle_url('/blocks/ps_selfstudy/viewrequests.php');
   	redirect($url);
} else {
	$url = new moodle_url('/blocks/ps_selfstudy/viewrequests.php');
   	redirect($url);
}