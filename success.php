<?php 

require "../../config.php";
global $DB;

if(isset($_GET['id']) & isset($_GET['status'])) {

	$id = $_GET['id'];
	$status = $_GET['status'];

	if (!$DB->update_record('block_ps_selfstudy_request', array('id'=>$id,'request_status'=>$status))) {
   		print_error('inserterror', 'block_ps_selfstudy');
   	}
   	$url = new moodle_url('/blocks/ps_selfstudy/viewrequests.php');
   	redirect($url);
}