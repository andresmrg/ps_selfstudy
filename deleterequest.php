<?php

require_once('../../config.php');
//require_once('deletecourse_form.php')

global $DB;

//Get course ID
$id = $_GET['id'];

//Delete course record
if (!$DB->delete_records('block_ps_selfstudy_request', ['id' => $id])) {
    print_error('inserterror', 'block_ps_selfstudy');
}
$url = new moodle_url('/blocks/ps_selfstudy/viewrequests.php');
redirect($url);

// form didn't validate or this is the first display