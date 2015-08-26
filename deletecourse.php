<?php

require_once('../../config.php');

require_login();
if (isguestuser()) {
	print_error('guestsarenotallowed');
}
global $DB;

if(isset($_GET['id'])) {
	$id = $_GET['id'];

	if (has_capability('block/ps_selfstudy:managecourses', $context, $USER->id)) {
      //Delete course record
		if (!$DB->delete_records('block_ps_selfstudy_course', ['id' => $id])) {
			print_error('inserterror', 'block_ps_selfstudy');
		}
		$url = new moodle_url('/blocks/ps_selfstudy/managecourses.php');
		redirect($url);
	} else {
		print_error('nopermissiontoviewpage', 'error', '');
	}

} else {
	$url = new moodle_url('/blocks/ps_selfstudy/managecourses.php');
	redirect($url);
	}