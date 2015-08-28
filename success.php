<?php 

require "../../config.php";

require_login();
if (isguestuser()) {
  print_error('guestsarenotallowed');
}

global $DB, $USER;

//Success when the course has been shipped
if(isset($_GET['id']) && isset($_GET['status']) && isset($_GET['courseid'])) {

	$id = $_GET['id'];
	$status = $_GET['status'];
  $courseid = $_GET['courseid'];

  $course = $DB->get_record('block_ps_selfstudy_course', array('id'=>$courseid), $fields='course_name,course_code');

  if (!$DB->update_record('block_ps_selfstudy_request', array('id'=>$id,'request_status'=>$status))) {
   print_error('inserterror', 'block_ps_selfstudy');
 }
 include('sendmessage.php');
 $url = new moodle_url('/blocks/ps_selfstudy/viewrequests.php');
 redirect($url);
} else if (isset($_GET['rid'])) {
  //Success when a user mark a course as completed
  $requestid = $_GET['rid'];
  $today = time();

  $completion = new stdClass();
  $completion->request_id = $requestid;
  $completion->completion_status = "completed";
  $completion->completion_date = $today;

  if (!$DB->insert_record('block_ps_selfstudy_complete', $completion)) {
    print_error('cannotsavedata', 'error', '');
  }
  $url = new moodle_url('/blocks/ps_selfstudy/myrequests.php?success=completed');
  redirect($url);
} else if (isset($_GET['id'])) {
  //Success when a user request a link type course
  $courseid = $_GET['id'];
  $today = time();
    $request = new stdClass();
    $request->student_id = $USER->id;
    $request->course_id = $courseid;
    $request->request_date = $today;
    $request->request_status = 2;  

  if (!$DB->insert_record('block_ps_selfstudy_request', $request)) {
      print_error('inserterror', 'block_ps_selfstudy');
  }
 $url = new moodle_url('/blocks/ps_selfstudy/myrequests.php?success=yes');
 redirect($url);

} else {
 $url = new moodle_url('/blocks/ps_selfstudy/myrequests.php');
 redirect($url);
}