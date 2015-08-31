<?php
/**
 * Simple file test_custom.php to drop into root of Moodle installation.
 * This is an example of using a sql_table class to format data.
 */
require "../../config.php";
require "$CFG->libdir/tablelib.php";
require_once('managelinks_form.php');
require "testviewrequests_table.php";
global $OUTPUT, $PAGE;

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/testviewrequests.php');
$PAGE->set_pagelayout('standard');
$link_form = new managelinks_form();
$table = new testviewrequests_table('uniqueid');

// Define headers
$PAGE->set_title('View Pending Requests');
$PAGE->set_heading('View Pending Requests');

$site = get_site();
echo $OUTPUT->header(); //output header
if (has_capability('block/ps_selfstudy:viewrequests', $context, $USER->id)) {
	$link_form->display(); //output button to create new courses
// Get the course table.
	$fields = 'r.id,c.course_code,c.course_name,u.firstname,u.lastname,u.email,u.address,u.country,u.phone1,r.request_date,r.request_status';
	$from = "{block_ps_selfstudy_request} as r JOIN {block_ps_selfstudy_course} c ON (c.id=r.course_id) JOIN {user} u ON(u.id=r.student_id)";
	$sqlconditions = 'r.request_status = 0';
$table->set_sql($fields, $from, $sqlconditions);
//$table->set_count_sql("SELECT COUNT(*) FROM $from WHERE $sqlconditions");
$table->define_baseurl("$CFG->wwwroot/blocks/ps_selfstudy/testviewrequests.php");
$table->out(10, true); //print table
} else {
	print_error('nopermissiontoviewpage', 'error', '');
}
echo $OUTPUT->footer();




