<?php
/**
 * Simple file test_custom.php to drop into root of Moodle installation.
 * This is an example of using a sql_table class to format data.
 */
require "../../config.php";
require "$CFG->libdir/tablelib.php";
require_once('managelinks_form.php');
require "courselist_table.php";
global $OUTPUT, $PAGE;

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/managecourses.php');
$PAGE->set_pagelayout('standard');
$link_form = new managelinks_form();
$table = new courselist_table('uniqueid');

// Define headers
$PAGE->set_title('Manage self-study courses');
$PAGE->set_heading('Manage self-study courses');
$PAGE->navbar->add('Manage self-study courses', new moodle_url('/blocks/ps_selfstudy/managecourses.php'));


$site = get_site();
echo $OUTPUT->header(); //output header
if (has_capability('block/ps_selfstudy:managecourses', $context, $USER->id)) {
	$link_form->display(); //output button to create new courses
// Get the course table.
$table->set_sql('*', "{block_ps_selfstudy_course}", '1');
$table->define_baseurl("$CFG->wwwroot/blocks/ps_selfstudy/managecourses.php");
$table->out(10, true); //print table
} else {
	print_error('nopermissiontoviewpage', 'error', '');
}
echo $OUTPUT->footer();




