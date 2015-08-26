<?php
/**
 * Simple file test_custom.php to drop into root of Moodle installation.
 * This is an example of using a sql_table class to format data.
 */
require "../../config.php";
require "$CFG->libdir/tablelib.php";

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

global $OUTPUT, $PAGE, $DB, $USER;

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/myrequests.php');
$PAGE->set_pagelayout('standard');

/**** TABLE LIST OF REQUESTS ****/
//create table to list the status of my requests
$table = new html_table();
$table->head = array('Course Code','Course Title','Request date','Status','Completion');
$table->data = array();

//get all data from requesttable
$request = $DB->get_records('block_ps_selfstudy_request', array('student_id'=>$USER->id), $sort='', $fields='*', $limitfrom=0, $limitnum=0);

//loop the request table
foreach($request as $value) {
	$course = $DB->get_record('block_ps_selfstudy_course', array('id'=>$value->course_id), $fields='id,course_name,course_code');

	//format requested date from timestamp
	$timestamp = $value->request_date;
	$date = date("m/d/Y",$timestamp);

	if($value->request_status == 0) {
		$status = "Pending";
		$completion = '';
	} else {
		$completion = '<a href="success.php?cid='.$course->id.'">Complete</a>';
		$status = "Shipped";
	}

	//add the cells to the request table
	$row = array($course->course_code,$course->course_name,$date,$status,$completion);
    $table->data[] = $row;		
}

/**** TABLE HISTORY ****/
//create table to list the status of my requests
$table_history = new html_table();
$table_history->head = array('Course Code','Course Title','Request date','Status','Completion');
$table_history->data = array();

//get all data from requesttable
$request = $DB->get_records('block_ps_selfstudy_request', array('student_id'=>$USER->id), $sort='', $fields='*', $limitfrom=0, $limitnum=0);

//loop the request table_history
foreach($request as $value) {
	$course = $DB->get_record('block_ps_selfstudy_course', array('id'=>$value->course_id), $fields='course_name,course_code');

	//format requested date from timestamp
	$timestamp = $value->request_date;
	$date = date("m/d/Y",$timestamp);

	if($value->request_status == 0) {
		$status = "Pending";
		$completion = '';
	} else {
		$completion = '<a href="#">Complete</a>';
		$status = "Shipped";
	}

	//add the cells to the request table
	$row = array($course->course_code,$course->course_name,$date,$status,$completion);
    $table_history->data[] = $row;		
}

// Define headers
$PAGE->set_title('My requests');
$PAGE->set_heading('My requests');
$PAGE->navbar->add('My requests', new moodle_url('/blocks/ps_selfstudy/myrequests.php'));

$site = get_site();
echo $OUTPUT->header(); //output header
if(isset($_GET['success'])) {
	echo "<div class='alert alert-success'>Order Submitted</div>";
}
echo html_writer::table($table);
echo "<h2>History</h2>";
echo html_writer::table($table_history);
echo $OUTPUT->footer();