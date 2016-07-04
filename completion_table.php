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
 * This table displays the user's completions.
 * @package    block_ps_selfstudy
 * @copyright  2015 Andres Ramos
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class completion_table extends table_sql {

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('course_code', 'course_name', 'empctry', 'email',
                'firstname', 'completion_date', 'completion_status');
        // Define the titles of columns to show in header.
        $headers = array('Course Code', 'Title', 'EmpSerial/CC', 'Email Address', 'Name',
                    'Completion Date', 'Completion Status');

        $this->sortable(true, 'course_code', SORT_ASC);
        $this->collapsible(false);
        $this->define_columns($columns);
        $this->define_headers($headers);
    }

    /**
     * Generate the display of the user's full name column.
     * @param object $values the table row being output.
     * @return string HTML content to go inside the td.
     */
    public function col_firstname($values) {
        // If the data is being downloaded than we don't want to show HTML.
        if ($this->is_downloading()) {
            $fullname = "$values->firstname $values->lastname";
            return $fullname;
        } else {
            return '<a href="$CFG->wwwroot/../../../user/profile.php?id='.$values->student_id.'">
                '.$values->firstname." ".$values->lastname.'</a>';
        }
    }

    /**
     * Generate the display of the user's custom profile field EmpSerialCC.
     * @param object $values the table row being output.
     * @return string HTML content to go inside the td.
     */
    public function col_empctry($values) {
        global $DB;
        // If the data is being downloaded than we don't want to show HTML.
        $empctryid = $DB->get_record('user_info_field', array('shortname' => 'empctry'),
                $fields = 'id', $strictness = IGNORE_MISSING);
        if (!empty($empctryid)) {
            $empctry = $DB->get_record('user_info_data', array('userid' => $values->student_id,
                    'fieldid' => $empctryid->id), $fields = 'data', $strictness = IGNORE_MISSING);
            if (!empty($empctry)) {
                return $empctry->data;
            } else {
                return "";
            }
        }
    }

    /**
     * Generate the display of the user's completion status.
     * @param object $values the table row being output.
     * @return string HTML content to go inside the td.
     */
    public function col_completion_status($values) {
        // If the value is 0, show Pending status.
        if ($values->completion_status == "completed") {
            return "Completed";
        }
    }

    /**
     * Generate the display of the user's completion date.
     * @param object $values the table row being output.
     * @return string HTML content to go inside the td.
     */
    public function col_completion_date($values) {
        // Show readable date from timestamp.
        $date = $values->completion_date;
        return date("m/d/Y", $date);
    }
}