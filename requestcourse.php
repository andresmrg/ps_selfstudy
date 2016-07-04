<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file displays a form for the user to fill up in order to request a physical course.
 *
 * @package   block_ps_selfstudy
 * @copyright Andres Ramos
 */
require_once(__DIR__ . '/../../config.php');
require_once('requestcourse_form.php');
require_once('../../user/lib.php');

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

global $OUTPUT, $PAGE, $COURSE, $USER;

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/requestcourse.php');
$PAGE->set_pagelayout('standard');
$formpage = new requestcourse_form();

// Define headers.
$PAGE->set_title(get_string('title_requestcourses', 'block_ps_selfstudy'));
$PAGE->set_heading(get_string('title_requestcourses', 'block_ps_selfstudy'));
// Nav breadcump.
$PAGE->navbar->ignore_active();
$PAGE->navbar->add('Available courses', new moodle_url('/blocks/ps_availablecourses/allcourses.php'));
$PAGE->navbar->add(get_string('title_requestcourses', 'block_ps_selfstudy'));

if ($formpage->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
    $courseurl = new moodle_url('/blocks/ps_selfstudy/managecourses.php');
    redirect($courseurl);

} else if ($fromform = $formpage->get_data()) {

    /*
     * 1. Update user profile.
     * 2. Save request data into request table.
     * 3. Take the user to the list of request and pass the message if the request was made
     *    successfully.
     */
    $profile = new stdClass();
    $profile->id = $USER->id;
    $profile->firstname   = $fromform->firstname;
    $profile->lastname    = $fromform->lastname;
    $profile->email       = $fromform->email;
    $profile->country     = $fromform->country;
    $profile->city        = $fromform->city;
    $profile->address     = $fromform->address;
    $profile->phone1      = $fromform->phone1;

    // Update user.
    user_update_user($profile, false, true);
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

    // 2. store the request data in the request table.
    if (!$DB->insert_record('block_ps_selfstudy_request', $request)) {
        print_error('inserterror', 'block_ps_selfstudy');
    }


    // Get id of the zipcode in the fields table.
    $zipfield = $DB->get_record(
        'user_info_field', array('shortname' => 'zipcode'),
        $fields = 'id', $strictness = IGNORE_MISSING
    );
    $zipcodedata = new stdClass();
    $zipcodedata->userid  = $USER->id;
    $zipcodedata->fieldid = $zipfield->id;
    $zipcodedata->data    = $fromform->zipcode;

    // Get id of the address2 in the fields table.
    $address2 = $DB->get_record(
        'user_info_field', array('shortname' => 'address2'),
        $fields = 'id', $strictness = IGNORE_MISSING
    );
    $address2data = new stdClass();
    $address2data->userid   = $USER->id;
    $address2data->fieldid  = $address2->id;
    $address2data->data     = $fromform->address2;

    // Get id of the state in the fields table.
    $stateid = $DB->get_record(
        'user_info_field', array('shortname' => 'state'),
        $fields = 'id', $strictness = IGNORE_MISSING
    );
    $statedata = new stdClass();
    $statedata->userid = $USER->id;
    $statedata->fieldid = $stateid->id;
    $statedata->data = $fromform->state;

    // If there is already a zipcode defined, update it.
    if ($DB->record_exists('user_info_data', array('fieldid' => $zipfield->id, 'userid' => $USER->id))) {
        // Get the record id.
        $dataid = $DB->get_record(
            'user_info_data', array('fieldid' => $zipfield->id, 'userid' => $USER->id),
            $fields = 'id', $strictness = IGNORE_MISSING
        );
        if (!$DB->update_record('user_info_data', array('id' => $dataid->id, 'data' => $fromform->zipcode))) {
            print_error('inserterror', 'block_ps_selfstudy');
        }
    } else {
            // 3. Insert a record with the zipcode.
        if (!$DB->insert_record('user_info_data', $zipcodedata)) {
            print_error('inserterror', 'block_ps_selfstudy');
        }
    }

    // If there is already a zipcode defined, update it.
    if ($DB->record_exists('user_info_data', array('fieldid' => $address2->id, 'userid' => $USER->id))) {
        // Get the record id.
        $addressdataid = $DB->get_record(
            'user_info_data',
            array(
                'fieldid'   => $address2->id,
                'userid'    => $USER->id
            ),
            $fields = 'id',
            $strictness = IGNORE_MISSING
        );
        if (!$DB->update_record('user_info_data', array('id' => $addressdataid->id, 'data' => $fromform->address2))) {
            print_error('inserterror', 'block_ps_selfstudy');
        }
    } else {
        // 3. insert a record with the zipcode.
        if (!$DB->insert_record('user_info_data', $address2data)) {
            print_error('inserterror', 'block_ps_selfstudy');
        }
    }

    // If there is already a state defined, update it.
    if ($DB->record_exists('user_info_data', array('fieldid' => $stateid->id, 'userid' => $USER->id))) {
        // Get the record id.
        $statedataid = $DB->get_record('user_info_data',
            array('fieldid' => $stateid->id, 'userid' => $USER->id),
            $fields = 'id', $strictness = IGNORE_MISSING
        );
        if (!$DB->update_record('user_info_data', array('id' => $statedataid->id, 'data' => $fromform->state))) {
            print_error('inserterror', 'block_ps_selfstudy');
        }
    } else {
        // 3. insert a record with the state.
        if (!$DB->insert_record('user_info_data', $statedata)) {
            print_error('inserterror', 'block_ps_selfstudy');
        }
    }

    // Redirect to my request page.
    $url = new moodle_url($CFG->wwwroot.'/blocks/ps_selfstudy/myrequests.php?success=yes');
    redirect($url);

} else {

    // Form didn't validate or this is the first display.
    $site = get_site();
    echo $OUTPUT->header();
    $formpage->display();
    echo $OUTPUT->footer();
}