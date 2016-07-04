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
 * View all requests displayed on an HTML table.
 *
 * @package block_ps_selfstudy
 * @copyright 2015 Andres Ramos
 */
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/tablelib.php');
require_once('filter_form.php');
require('viewrequests_table.php');
global $OUTPUT, $PAGE;

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/viewrequests.php');
$PAGE->set_pagelayout('standard');
$filterform = new filter_form();

$download = optional_param('download', '', PARAM_ALPHA);

$table = new viewrequests_table('uniqueid');
$table->is_downloading($download, 'view_requests', 'Requests');

if ($filterform->is_cancelled()) {

    $courseurl = new moodle_url('/blocks/ps_selfstudy/viewrequests.php');
    redirect($courseurl);

} else if ($fromform = $filterform->get_data()) {

    $sqlconditions = "course_code = '".$fromform->filter_code."' AND ";

    if (!$table->is_downloading()) {
        // Define headers.
        $PAGE->set_title(get_string('title_viewrequests', 'block_ps_selfstudy'));
        $PAGE->set_heading(get_string('title_viewrequests', 'block_ps_selfstudy'));
        $PAGE->navbar->add(get_string('title_viewrequests', 'block_ps_selfstudy'));
        $site = get_site();
        echo $OUTPUT->header(); // Output header.
        $filterform->display();
        echo "<hr>";
    }

    if (has_capability('block/ps_selfstudy:viewrequests', $context, $USER->id)) {

        // SQL to get all requests.
        $fields = 'r.id,c.course_code,c.course_name,u.firstname,u.lastname,u.email,
                u.address, u.country, u.city, u.phone1, r.student_id, r.course_id, r.request_date, r.request_status';
        $from = "{block_ps_selfstudy_request} as r
                JOIN {block_ps_selfstudy_course} c ON (c.id=r.course_id)
                JOIN {user} u ON(u.id=r.student_id)";
        $sqlconditions .= 'r.request_status = 0';
        $table->define_baseurl("$CFG->wwwroot/blocks/ps_selfstudy/viewrequests.php");
        $link = '<br><a href="viewallrequests.php">'.get_string('clickfulllist', 'block_ps_selfstudy').'</a>';
        $table->set_sql($fields, $from, $sqlconditions);
        $table->out(30, true); // Print table.
        if (!$table->is_downloading()) {
            echo $link;
        }
    } else {
        print_error('nopermissiontoviewpage', 'error', '');
    }
    if (!$table->is_downloading()) {
        echo $OUTPUT->footer();
    }

} else {

    if (!$table->is_downloading()) {
        // Define headers.
        $PAGE->set_title(get_string('title_viewrequests', 'block_ps_selfstudy'));
        $PAGE->set_heading(get_string('title_viewrequests', 'block_ps_selfstudy'));
        $PAGE->navbar->add(get_string('title_viewrequests', 'block_ps_selfstudy'));
        $site = get_site();
        echo $OUTPUT->header(); // Output header.
        $filterform->display();
        echo "<hr>";
    }

    if (has_capability('block/ps_selfstudy:viewrequests', $context, $USER->id)) {

        // SQL to get all requests.
        $fields = 'r.id,c.course_code,c.course_name,u.firstname,u.lastname,
            u.email, u.address, u.country, u.city, u.phone1, r.student_id, r.course_id, r.request_date, r.request_status';
        $from = "{block_ps_selfstudy_request} as r
                JOIN {block_ps_selfstudy_course} c ON (c.id=r.course_id)
                JOIN {user} u ON(u.id=r.student_id)";
        $sqlconditions = 'r.request_status = 0';
        $table->define_baseurl("$CFG->wwwroot/blocks/ps_selfstudy/viewrequests.php");
        $link = '<br><a href="viewallrequests.php">'.get_string('clickfulllist', 'block_ps_selfstudy').'</a>';
        $table->set_sql($fields, $from, $sqlconditions);
        $table->out(30, true); // Print table.

        if (!$table->is_downloading()) {
            echo $link;
        }
    } else {
        print_error('nopermissiontoviewpage', 'error', '');
    }
    if (!$table->is_downloading()) {
        echo $OUTPUT->footer();
    }
}