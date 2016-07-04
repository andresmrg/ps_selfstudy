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
 * Delete a course and delete all records related to the course (requests and completion).
 * @package     block_ps_selfstudy
 * @copyright   2015 Andres Ramos
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

// Make sure guests can't access to this page.
require_login();
if (isguestuser()) {
    print_error('guestsarenotallowed');
}

global $DB;
$context = context_system::instance();

// Get URL params.
$id = optional_param('id',  0,  PARAM_INT);
if ($id) {

    // If the user has the right permissions.
    if (has_capability('block/ps_selfstudy:managecourses', $context, $USER->id)) {
        // Delete course record.
        if (!$DB->delete_records('block_ps_selfstudy_course', ['id' => $id])) {
            print_error('inserterror', 'block_ps_selfstudy');
        }

        // Delete all requests related to this course if any.
        $requestlist = $DB->get_records('block_ps_selfstudy_request',
                                        array('course_id' => $id), $sort = '', $fields = 'id', $limitfrom = 0, $limitnum = 0);
        if ($requestlist) {
            foreach ($requestlist as $field) {
                if (!$DB->delete_records('block_ps_selfstudy_request', array('id' => $field->id))) {
                    print_error('inserterror', 'block_ps_selfstudy');
                }
                // Delete all completions with this requests if any.
                $completionid = $DB->get_record('block_ps_selfstudy_complete', array('request_id' => $field->id), $fields = 'id');
                if ($completionid) {
                    if (!$DB->delete_records('block_ps_selfstudy_complete', array('id' => $completionid->id))) {
                        print_error('inserterror', 'block_ps_selfstudy');
                    }
                }
            }
        }
        // Redirect to manage course page.
        $url = new moodle_url('/blocks/ps_selfstudy/managecourses.php?success=del');
        redirect($url);
    } else {
        print_error('nopermissiontoviewpage', 'error', '');
    }

} else {
    $url = new moodle_url('/blocks/ps_selfstudy/managecourses.php');
    redirect($url);
}