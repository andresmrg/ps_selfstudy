<?php

require_once('../../config.php');
require_once('editcourse_form.php');

global $OUTPUT, $PAGE;

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/editcourse.php');
$PAGE->set_pagelayout('standard');
$form_page = new editcourse_form();

// Define headers
$PAGE->set_title('Edit self-study course');
$PAGE->navbar->add('Edit self-study course', new moodle_url('/blocks/ps_selfstudy/editcourse.php'));

require_login();

if($form_page->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
	$courseurl = new moodle_url('/blocks/ps_selfstudy/managecourses.php');

} else if ($fromform = $form_page->get_data()) {
    // We need to add code to appropriately act on and store the submitted data
   	/*
   	1. get the data from the form
   	2. save into the db
   	3. redirect to the course list page
   	*/
    
    //print_object($fromform);
   	if (!$DB->update_record('block_ps_selfstudy_course', $fromform)) {
   		print_error('inserterror', 'block_ps_selfstudy');
   	}
   	$courseurl = new moodle_url('/blocks/ps_selfstudy/managecourses.php');
   	redirect($courseurl);

   } else {
    // form didn't validate or this is the first display
   	$site = get_site();
   	echo $OUTPUT->header();
   	echo "<h2>Edit course<br><br></h2>";
   	$form_page->display();
   	echo $OUTPUT->footer();
   }

// form didn't validate or this is the first display