<?php
/**
 * Simple file test_custom.php to drop into root of Moodle installation.
 * This is an example of using a sql_table class to format data.
 */
require "../../config.php";
require "$CFG->libdir/tablelib.php";
require "completion_table.php";
global $OUTPUT, $PAGE;

require_login();
if (isguestuser()) {
	print_error('guestsarenotallowed');
}

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/viewcompletion.php');
$PAGE->set_pagelayout('standard');


$download = optional_param('download', '', PARAM_ALPHA);

$table_completion = new completion_table('uniqueid');
$table_completion->is_downloading($download, 'complete_history', 'History');


	//FIRST TIME
	if (!$table_completion->is_downloading()) {
	//Define headers
		$PAGE->set_title(get_string('title_viewcompletion','block_ps_selfstudy'));
		$PAGE->set_heading(get_string('title_viewcompletion','block_ps_selfstudy'));
		$site = get_site();
		echo $OUTPUT->header(); //output header
		
		echo "<hr>";
	}

	if (has_capability('block/ps_selfstudy:viewrequests', $context, $USER->id)) {

		//sql to get all requests
		$fields = 'r.id,c.course_code,c.course_name,u.firstname,u.lastname,u.email,r.student_id,r.course_id,x.completion_date,x.completion_status';
		$from = "{block_ps_selfstudy_complete} as x JOIN {block_ps_selfstudy_request} r ON (r.id=x.request_id) JOIN {block_ps_selfstudy_course} c ON (c.id=r.course_id) JOIN {user} u ON(u.id=r.student_id)";
		$sqlconditions = "x.completion_status = 'completed'";
		$table_completion->define_baseurl("$CFG->wwwroot/blocks/ps_selfstudy/viewcompletion.php");
		//$link = '<br><a href="viewrequests.php">'.get_string('clickpendinglist','block_ps_selfstudy').'</a>';
		$table_completion->set_sql($fields, $from, $sqlconditions);
		$table_completion->out(30, true); //print table

	} else {
		print_error('nopermissiontoviewpage', 'error', '');
	}
	if (!$table_completion->is_downloading()) {
		echo $OUTPUT->footer();
	}

