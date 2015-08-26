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
$PAGE->set_url('/blocks/ps_selfstudy/viewrequests.php');
$PAGE->set_pagelayout('standard');

//create table to hold the data
$table = new html_table();
$table->head = array('Course Code','Course Title','Request date','Status');
$table->data = array();

//get all data from requesttable
$request = $DB->get_records('block_ps_selfstudy_request', array('student_id'=>$USER->id), $sort='', $fields='*', $limitfrom=0, $limitnum=0);

//loop the request table
foreach($request as $value) {
	$course = $DB->get_record('block_ps_selfstudy_course', array('id'=>$value->course_id), $fields='course_name,course_code');

	//format requested date from timestamp
	$timestamp = $value->request_date;
	$date = date("m/d/Y",$timestamp);

	if($value->request_status == 0) {
		$status = "Pending";
	} else {
		$status = "Shipped";
	}

	//add the cells to the request table
	$row = array($course->course_code,$course->course_name,$date,$status);
    $table->data[] = $row;		
}

// Define headers
$PAGE->set_title('My requests');
$PAGE->set_heading('My requests');
$PAGE->navbar->add('My requests', new moodle_url('/blocks/ps_selfstudy/viewrequests.php'));

$site = get_site();
echo $OUTPUT->header(); //output header
echo html_writer::table($table);
echo $OUTPUT->footer();