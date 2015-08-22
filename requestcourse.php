<?php

require_once('../../config.php');
require_once('requestcourse_form.php');

global $OUTPUT, $PAGE, $COURSE;

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/requestcourse.php');
$PAGE->set_pagelayout('standard');
$form_page = new requestcourse_form();

// Define headers
$PAGE->set_title('Request self-study course');
$PAGE->navbar->add('Request self-study course', new moodle_url('/blocks/ps_selfstudy/requestcourse.php'));

if($form_page->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
	$courseurl = new moodle_url('/blocks/ps_selfstudy/managecourses.php');

} else if ($fromform = $form_page->get_data()) {
    // We need to add code to appropriately act on and store the submitted data

    //print_object($fromform);
   	//if (!$DB->insert_record('block_ps_selfstudy_course', $fromform)) {
   		//print_error('inserterror', 'block_ps_selfstudy');
   	//}
   	//$courseurl = new moodle_url('/blocks/ps_selfstudy/managecourses.php');
   	//redirect($courseurl);

   } else {
    // form didn't validate or this is the first display
   	$site = get_site();
   	echo $OUTPUT->header();
   	$form_page->display();
   	echo $OUTPUT->footer();
   }

// form didn't validate or this is the first display
