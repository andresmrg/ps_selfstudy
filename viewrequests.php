<?php
/**
 * Simple file test_custom.php to drop into root of Moodle installation.
 * This is an example of using a sql_table class to format data.
 */
require "../../config.php";
require "$CFG->libdir/tablelib.php";
global $OUTPUT, $PAGE, $DB;

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/viewrequests.php');
$PAGE->set_pagelayout('standard');

//create table to hold the data
$table = new html_table();
$table->head = array('Student', 'Course Title','Request date','Status','Action');
$table->data = array();

//get all data from requesttable
$request = $DB->get_records('block_ps_selfstudy_request', array('request_status'=>'pending'), $sort='', $fields='*', $limitfrom=0, $limitnum=0);

//loop the request table
foreach($request as $value) {
	$user = $DB->get_record('user', array('id'=>$value->student_id), $fields='firstname,lastname');
	$course = $DB->get_record('block_ps_selfstudy_course', array('id'=>$value->course_id), $fields='course_name');

	//get firstname and lastname together
	$fullname = "$user->firstname $user->lastname";
	//format requested date from timestamp
	$timestamp = $value->request_date;
	$date = date("m/d/Y",$timestamp);
	$status = ucfirst($value->request_status);

	$links = '<a href="success.php?id='.$value->id.'&status=complete">Complete</a> - <a href="deleterequest.php?id='.$value->id.'">Delete</a>';
	//add the cells to the request table
	$row = array($fullname,$course->course_name,$date,$status,$links);
    $table->data[] = $row;		
}

// Define headers
$PAGE->set_title('View requests');
$PAGE->set_heading('View requests');
$PAGE->navbar->add('View requests', new moodle_url('/blocks/ps_selfstudy/viewrequests.php'));

$site = get_site();
echo $OUTPUT->header(); //output header
echo html_writer::table($table);
echo $OUTPUT->footer();