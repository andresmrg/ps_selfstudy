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
 * Display All requests from students whether shipped or pending
 *
 * @package     block_ps_selfstudy
 * @copyright   2015 Andres Ramos
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/tablelib.php');
require_once('filter_form.php');
require_once('viewrequests_table.php');

global $OUTPUT, $PAGE;

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/viewallrequests.php');
$PAGE->set_pagelayout('standard');
$filterform = new filter_form();

$download = optional_param('download', '', PARAM_ALPHA);

$table = new viewrequests_table('uniqueid');
$table->is_downloading($download, 'view_all_requests', get_string('allrequests',  'block_ps_selfstudy'));

if ($filterform->is_cancelled()) {

    // Redirect to all requests page.
    $courseurl = new moodle_url('/blocks/ps_selfstudy/viewallrequests.php');
    redirect($courseurl);

} else if ($fromform = $filterform->get_data()) {

    $sqlconditions = "course_code = '".$fromform->filter_code."' AND ";

    if (!$table->is_downloading()) {

        // Define headers.
        $PAGE->set_title(get_string('title_viewallrequests', 'block_ps_selfstudy'));
        $PAGE->set_heading(get_string('title_viewallrequests', 'block_ps_selfstudy'));
        $PAGE->navbar->add(get_string('title_viewallrequests', 'block_ps_selfstudy'));
        $site = get_site();
        echo $OUTPUT->header(); // Output header.
        $filterform->display();
        echo "<hr>";
    }

    if (has_capability('block/ps_selfstudy:viewrequests', $context, $USER->id)) {

        // SQL to get all requests.
        $fields = " r.id,
                    c.course_code,
                    c.course_name,
                    u.firstname,
                    u.lastname,
                    u.email,
                    u.address,
                    u.department,
                    u.country,
                    u.city,
                    u.phone1,
                    r.student_id,
                    r.course_id,
                    r.request_date, r.request_status";
        $from = "   {block_ps_selfstudy_request} r
                JOIN {block_ps_selfstudy_course} c ON c.id = r.course_id
                JOIN {user} u ON u.id = r.student_id
                AND u.deleted = 0";
        $sqlconditions .= 'r.request_status != 2';
        $table->define_baseurl("$CFG->wwwroot/blocks/ps_selfstudy/viewallrequests.php");
        $link = '<br><a href="viewrequests.php">'.get_string('clickpendinglist', 'block_ps_selfstudy').'</a>';
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

    // Forms without submission.
    if (!$table->is_downloading()) {
        // Define headers.
        $PAGE->set_title(get_string('title_viewallrequests', 'block_ps_selfstudy'));
        $PAGE->set_heading(get_string('title_viewallrequests', 'block_ps_selfstudy'));
        $PAGE->navbar->add(get_string('title_viewallrequests', 'block_ps_selfstudy'));
        $site = get_site();
        echo $OUTPUT->header(); // Output header.
        $filterform->display();
        echo "<hr>";
    }

    if (has_capability('block/ps_selfstudy:viewrequests', $context, $USER->id)) {

        // SQL to get all requests.
        $fields = "r.id,
                    c.course_code,
                    c.course_name,
                    u.firstname,
                    u.lastname,
                    u.email,
                    u.address,
                    u.department,
                    u.country,
                    u.city,
                    u.phone1,
                    r.student_id,
                    r.course_id,
                    r.request_date,
                    r.request_status";
        $from = "   {block_ps_selfstudy_request} as r
               JOIN {block_ps_selfstudy_course} c ON c.id = r.course_id
               JOIN {user} u ON u.id = r.student_id AND u.deleted = 0";
        $sqlconditions = 'r.request_status != 2';
        $table->define_baseurl("$CFG->wwwroot/blocks/ps_selfstudy/viewallrequests.php");
        $link = '<br><a href="viewrequests.php">'.get_string('clickpendinglist', 'block_ps_selfstudy').'</a>';
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