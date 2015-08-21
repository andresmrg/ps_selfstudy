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

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/managecourses.php');
$PAGE->set_pagelayout('standard');
$page = new managelinks_form();

//$download = optional_param('download', '', PARAM_ALPHA);

$table = new test_table('uniqueid');

// Define headers
$PAGE->set_title('Manage self-study courses');
$PAGE->set_heading('Manage self-study courses');
$PAGE->navbar->add('Manage self-study courses', new moodle_url('/blocks/ps_selfstudy/managecourses.php'));

$site = get_site();
echo $OUTPUT->header(); //output header
echo "<h2>Manage self-study courses<br><br></h2>";
$page->display(); //output button to create new courses

// Work out the sql for the table.
$table->set_sql('*', "{block_ps_selfstudy_course}", '1');
$table->define_baseurl("$CFG->wwwroot/blocks/ps_selfstudy/managecourses.php");
//$table->finish_output();
//$table->print_html();

$table->out(10, true); //print table
echo $OUTPUT->footer();

