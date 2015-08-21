<?php

require_once('../../config.php');
//require_once('deletecourse_form.php')

global $DB, $OUTPUT, $PAGE;

//Get course ID
$id = $_GET['id'];

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/deletecourse.php');
$PAGE->set_pagelayout('standard');

//Delete course record
if (!$DB->delete_records('block_ps_selfstudy_course', ['id' => $id])) {
    print_error('inserterror', 'block_simplehtml');
}
$courseurl = new moodle_url('/blocks/ps_selfstudy/managecourses.php');
redirect($courseurl);

// form didn't validate or this is the first display
$site = get_site();
echo $OUTPUT->header();
echo "<h2>Add a new course<br><br></h2>";
echo $OUTPUT->footer();
