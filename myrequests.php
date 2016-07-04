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
 * Display the list of all requests made by a user.
 *
 * @package     block_ps_selfstudy
 * @copyright   2015 Andres Ramos
 */

require_once(__DIR__ . '/../../config.php');
require($CFG->libdir . '/tablelib.php');

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

// Get URL params.
$success = optional_param('success',  0,  PARAM_NOTAGS);

global $OUTPUT, $PAGE, $DB, $USER;

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/myrequests.php');
$PAGE->set_pagelayout('standard');

/**** TABLE LIST OF PHYSICAL REQUESTS ****/
$table = new html_table();
$table->head = array(
    get_string('coursecode', 'block_ps_selfstudy'),
    get_string('coursetitle', 'block_ps_selfstudy'),
    get_string('requestdate', 'block_ps_selfstudy'),
    get_string('status', 'block_ps_selfstudy'),
    get_string('completion', 'block_ps_selfstudy')
);
$table->data = array();
/**** TABLE LINK TYPE REQUESTS ****/
$tablelinktype = new html_table();
$tablelinktype->head = array(
    get_string('coursecode', 'block_ps_selfstudy'),
    get_string('coursetitle', 'block_ps_selfstudy'),
    get_string('link', 'block_ps_selfstudy'),
    get_string('requestdate', 'block_ps_selfstudy'),
    get_string('completion', 'block_ps_selfstudy')
);
$tablelinktype->data = array();
/**** TABLE HISTORY ****/
$tablehistory = new html_table();
$tablehistory->head = array(
    get_string('coursecode', 'block_ps_selfstudy'),
    get_string('coursetitle', 'block_ps_selfstudy'),
    get_string('completiondate', 'block_ps_selfstudy'),
    get_string('status', 'block_ps_selfstudy')
);
$tablehistory->data = array();

$querysql = "SELECT request.id AS id,
                    request.request_date,
                    request.request_status,
                    course.course_code,
                    course.course_name,
                    course.course_type,
                    course.course_link
               FROM {block_ps_selfstudy_request} request
               JOIN {block_ps_selfstudy_course} course ON course.id = request.course_id
              WHERE student_id = ?";
$request = $DB->get_records_sql($querysql, array($USER->id));

// Loop the request records to form the requests list and the link list.
foreach ($request as $value) {

    // Format requested date from timestamp.
    $timestamp = $value->request_date;
    $date = date("m/d/Y", $timestamp);

    // Get status to be strings.
    // If status is pending, then doesn't show the link for completion.
    if ($value->request_status == 0) {
        $status = "Pending";
        $completion = '';
    } else {
        // ... Otherwise, display shipped and show the completion link.
        $completion = '<a href="action.php?action=completecourse&requestid= '.$value->id.'">
        ' . get_string('complete', 'block_ps_selfstudy') . '</a>';
        $status = "Shipped";
    }

    // If it is course link type, add to second table, if not, add it to the request table.
    if ($value->course_type == 1) {
        // If the course was completed => skip it.
        if ($DB->record_exists('block_ps_selfstudy_complete', array('request_id' => $value->id))) {
            continue;
        } else {

            // Display blank if doesn't have a link.
            $link = $value->course_link;
            if ($link == '0') {
                $link = "";
            }

            // Create completion button and table.
            $completion = '<a href="action.php?action=completecourse&requestid= '.$value->id.'">
            ' . get_string('complete', 'block_ps_selfstudy') . '</a>';
            $row1 = array($value->course_code, $value->course_name, $link, $date, $completion);
            $tablelinktype->data[] = $row1;
        }
    } else {

        // If the course was completed => skip it.
        if ($DB->record_exists('block_ps_selfstudy_complete', array('request_id' => $value->id))) {
            continue;
        } else {
            $row = array($value->course_code, $value->course_name, $date, $status, $completion);
            $table->data[] = $row;
        }
    }
}

foreach ($request as $value) {
    // Get all data from _complete table.
    $completionlist = $DB->get_records(
        'block_ps_selfstudy_complete',
        array('request_id' => $value->id), $sort = '', $fields = '*'
    );

    // Loop the request tablehistory.
    foreach ($completionlist as $values) {
        $courseid = $DB->get_record('block_ps_selfstudy_request', array('id' => $values->request_id), $fields = 'course_id');

        // Format requested date from timestamp.
        $timestamp = $values->completion_date;
        $date = date("m/d/Y", $timestamp);
        $status = ucfirst($values->completion_status);

        // Add the cells to the request table.
        $row = array($value->course_code, $value->course_name, $date, $status);
        $tablehistory->data[] = $row;
    }
}
// Define headers.
$PAGE->set_title(get_string('myrequests', 'block_ps_selfstudy'));
$PAGE->set_heading(get_string('myrequests', 'block_ps_selfstudy'));
$PAGE->navbar->add(get_string('myrequests', 'block_ps_selfstudy'));

echo $OUTPUT->header(); // Output header.
if (!empty($success)) {

    $attributes = array("class" => "alert alert-success");
    if ($success == 'yes') {
        echo html_writer::tag('div', get_string('ordersubmitted', 'block_ps_selfstudy'), $attributes);
    } else {
        echo html_writer::tag('div', get_string('completecourse', 'block_ps_selfstudy'), $attributes);
    }
}
if (has_capability('block/ps_selfstudy:myrequests', $context, $USER->id)) {
    if ($table->data) {
        echo get_string('tablerequest', 'block_ps_selfstudy');
        echo html_writer::table($table);
    }
    if ($tablelinktype->data) {
        echo get_string('tablelink', 'block_ps_selfstudy');
        echo html_writer::table($tablelinktype);
    }
    if ($tablehistory->data) {
        echo get_string('tablehistory', 'block_ps_selfstudy');
        echo html_writer::table($tablehistory);
    }
    if (!$table->data && !$tablelinktype->data && !$tablehistory->data) {
        echo get_string('nopendingrequests', 'block_ps_selfstudy');
    }
} else {
    print_error('nopermissiontoviewpage', 'error', '');
}
echo $OUTPUT->footer();