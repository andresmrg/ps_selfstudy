<?php

require_once('../../config.php');
require_once('requestcourse_form.php');
require_once("../../user/lib.php");

global $OUTPUT, $PAGE, $COURSE, $USER;

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

    /*
    1. update user profile
    2. save request data into request table
    3. take the user to the list of request and pass the message if the request was made
    successfully
    */
    $profile = new stdClass();
    $profile->id = $USER->id;
    $profile->firstname         = $fromform->firstname;
    $profile->lastname         = $fromform->lastname;
    $profile->email         = $fromform->email;
    $profile->country         = $fromform->country;
    $profile->department         = $fromform->department;
    $profile->city         = $fromform->city;
    $profile->zipcode         = $fromform->zipcode;
    $profile->address         = $fromform->address;
    $profile->phone1         = $fromform->phone1;

    //print_object($profile);
    user_update_user($profile, false,true);
    // Reload from db.
    $user = $DB->get_record('user', array('id' => $profile->id), '*', MUST_EXIST);
    // Override old $USER session variable if needed.
    if ($USER->id == $user->id) {
        // Override old $USER session variable if needed.
        foreach ((array)$user as $variable => $value) {
            if ($variable === 'description' or $variable === 'password') {
                // These are not set for security nad perf reasons.
                continue;
            }
            $USER->$variable = $value;
        }
    }

    $today = time();
    $request = new stdClass();
    $request->student_id = $profile->id;
    $request->course_id = $fromform->courseid;
    $request->request_date = $today;  
    //$request->request_status = $profile->id;

    //2. store the request data in the request table
    if (!$DB->insert_record('block_ps_selfstudy_request', $request)) {
      print_error('inserterror', 'block_ps_selfstudy');
    }
   	$courseurl = new moodle_url($CFG->wwwroot.'/blocks/ps_selfstudy/viewrequests.php');
   	redirect($courseurl);

} else {
    // form didn't validate or this is the first display
  $site = get_site();
  echo $OUTPUT->header();
  $form_page->display();
  echo $OUTPUT->footer();
}