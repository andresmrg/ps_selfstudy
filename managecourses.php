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
 * Contains a table with the list of courses created, and provides the ability to edit, and remove them.
 *
 * @package  block_ps_selfstudy
 * @copyright 2015 Andres Ramos
 */
require_once(__DIR__ . '/../../config.php');
require($CFG->libdir . '/tablelib.php');
require('managecourses_table.php');
require_once('filter_form.php');
global $OUTPUT, $PAGE;

require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

// Get URL params.
$success = optional_param('success',  0,  PARAM_NOTAGS);

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/ps_selfstudy/managecourses.php');
$PAGE->set_pagelayout('standard');
$table = new managecourses_table('uniqueid');
$filterform = new filter_form();

// Define headers.
$PAGE->set_title(get_string('title_managecourses', 'block_ps_selfstudy'));
$PAGE->set_heading(get_string('title_managecourses', 'block_ps_selfstudy'));
// Nav breadcump.
$PAGE->navbar->add(get_string('title_managecourses', 'block_ps_selfstudy'));

if ($filterform->is_cancelled()) {

    $courseurl = new moodle_url('/blocks/ps_selfstudy/managecourses.php');
    redirect($courseurl);

} else if ($fromform = $filterform->get_data()) {

    $sqlconditions = "course_code = '".$fromform->filter_code."' AND ";

    echo $OUTPUT->header(); // Output header.

    if (has_capability('block/ps_selfstudy:managecourses', $context, $USER->id)) {
        // Display message if any.
        if ($success) {

            $attributes = array("class" => "alert alert-success");
            if ($success == 'del') {
                echo html_writer::tag('div', get_string('course_deleted', 'block_ps_selfstudy'), $attributes);
            } else {
                echo html_writer::tag('div', get_string('course_edited', 'block_ps_selfstudy'), $attributes);
            }
        }

        // Display filter form.
        $filterform->display();
        echo "<hr>";
        echo html_writer::link(
            new moodle_url('/blocks/ps_selfstudy/createcourse.php'),
            "Add a New Course", array("class" => "btn btn-default")
        );
        echo "<br><br>";

        // Get the course table.
        $table->set_sql('*', "{block_ps_selfstudy_course}", '1');
        $table->define_baseurl("$CFG->wwwroot/blocks/ps_selfstudy/managecourses.php");
        $table->out(30, true); // Print table.

    } else {
        print_error('nopermissiontoviewpage', 'error', '');
    }
    echo $OUTPUT->footer();

} else {

    $site = get_site();
    echo $OUTPUT->header(); // Output header.

    if (has_capability('block/ps_selfstudy:managecourses', $context, $USER->id)) {

        // Display message if any.
        if ($success) {

            $attributes = array("class" => "alert alert-success");
            if ($success == 'del') {
                echo html_writer::tag('div', get_string('course_deleted', 'block_ps_selfstudy'), $attributes);
            } else {
                echo html_writer::tag('div', get_string('course_edited', 'block_ps_selfstudy'), $attributes);
            }
        }

        // Display filter form.
        $filterform->display();
        echo "<hr>";
        echo html_writer::link(
            new moodle_url('/blocks/ps_selfstudy/createcourse.php'),
            get_string('addnewcourse',  'block_ps_selfstudy'), array("class" => "btn btn-default")
        );
        echo "<br><br>";

        // Get the course table.
        $table->set_sql('*', "{block_ps_selfstudy_course}", '1');
        $table->define_baseurl($CFG->wwwroot . "/blocks/ps_selfstudy/managecourses.php");
        $table->out(30, true); // Print table.

    } else {
        print_error('nopermissiontoviewpage', 'error', '');
    }
    echo $OUTPUT->footer();
}
echo "
<script>
    function checkConfirm()
    {
        var c = confirm('IMPORTANT: If you delete this course, all requests
            and completion records related to this course will be deleted. Do you want to proceed?');
        if (c) {
            return true;
        }
        else {
            return false;
        }
    }
    ('#linkContainer').on('click', 'a', function() {
    });
</script>
";