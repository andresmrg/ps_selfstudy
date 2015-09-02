<?php

require_once('../../config.php');
require_once('requestcourse_form.php');
require_once("../../user/lib.php");

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

global $OUTPUT, $PAGE, $COURSE, $USER;

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/requestcourse.php');
$PAGE->set_pagelayout('standard');
$form_page = new requestcourse_form();

// Define headers
$PAGE->set_title(get_string('title_requestcourse','block_ps_selfstudy'));
$PAGE->set_heading('Request self-study courses');
//$PAGE->navbar->add('Request self-study course', new moodle_url('/blocks/ps_selfstudy/requestcourse.php'));

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
    $profile->address         = $fromform->address;
    $profile->phone1         = $fromform->phone1;

    //update user
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

    //2. store the request data in the request table
    if (!$DB->insert_record('block_ps_selfstudy_request', $request)) {
      print_error('inserterror', 'block_ps_selfstudy');
  }


    //get id of the zipcode in the fields table
  $zip_id = $DB->get_record('user_info_field', array('shortname'=>'zipcode'), $fields='id', $strictness=IGNORE_MISSING);
  $zipcodedata = new stdClass();
  $zipcodedata->userid = $USER->id;
  $zipcodedata->fieldid = $zip_id->id;
  $zipcodedata->data = $fromform->zipcode;

  //if there is already a zipcode defined, update it.
  if($DB->record_exists('user_info_data', array('fieldid'=>$zip_id->id,'userid'=>$USER->id))) {
    //get the record id
    $dataid = $DB->get_record('user_info_data', array('fieldid'=>$zip_id->id,'userid'=>$USER->id), $fields='id', $strictness=IGNORE_MISSING);
    if (!$DB->update_record('user_info_data', array('id'=>$dataid->id,'data'=>$fromform->zipcode))) {
      print_error('inserterror', 'block_ps_selfstudy');
  }
} else {
        //3. insert a record with the zipcode
  if (!$DB->insert_record('user_info_data', $zipcodedata)) {
      print_error('inserterror', 'block_ps_selfstudy');
  }
}
//echo "<script>alert('Order Submitted');</script>";
    //redirect to my request page
$courseurl = new moodle_url($CFG->wwwroot.'/blocks/ps_selfstudy/myrequests.php?success=yes');
redirect($courseurl);

} else {
    // form didn't validate or this is the first display
  $site = get_site();
  echo $OUTPUT->header();
  $form_page->display();
  echo $OUTPUT->footer();
}