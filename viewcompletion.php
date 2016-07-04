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
 * Display the table of the courses completed.
 *
 * @package block_ps_selfstudy
 * @copyright 2015 Andres Ramos
 */
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/tablelib.php');
require_once('completion_table.php');

global $OUTPUT, $PAGE;

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/viewcompletion.php');
$PAGE->set_pagelayout('standard');

$download = optional_param('download', '', PARAM_ALPHA);

$tablecompletion = new completion_table('uniqueid');
$tablecompletion->is_downloading($download, 'complete_history', 'History');

if (!$tablecompletion->is_downloading()) {
    // Define headers.
    $PAGE->set_title(get_string('title_viewcompletion', 'block_ps_selfstudy'));
    $PAGE->set_heading(get_string('title_viewcompletion', 'block_ps_selfstudy'));
    $PAGE->navbar->add(get_string('title_viewcompletion', 'block_ps_selfstudy'));
    $site = get_site();
    echo $OUTPUT->header(); // Output header.

}

if (has_capability('block/ps_selfstudy:viewrequests', $context, $USER->id)) {

    // SQL to get all requests.
    $fields = " r.id,c.course_code,
                c.course_name,
                u.firstname,
                u.lastname,
                u.email,
                r.student_id,
                r.course_id,
                x.completion_date,
                x.completion_status";
    $from = "   {block_ps_selfstudy_complete} as x
                JOIN {block_ps_selfstudy_request} r ON (r.id=x.request_id)
                JOIN {block_ps_selfstudy_course} c ON (c.id=r.course_id)
                JOIN {user} u ON u.id = r.student_id AND u.deleted = 0";
    $sqlconditions = "x.completion_status = 'completed'";
    $tablecompletion->define_baseurl($CFG->wwwroot . "/blocks/ps_selfstudy/viewcompletion.php");
    $tablecompletion->no_sorting('empctry');
    $tablecompletion->set_sql($fields, $from, $sqlconditions);
    $tablecompletion->out(30, true);

} else {
    print_error('nopermissiontoviewpage', 'error', '');
}

if (!$tablecompletion->is_downloading()) {
    echo $OUTPUT->footer();
}