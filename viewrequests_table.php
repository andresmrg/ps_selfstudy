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
 * Table class to display selfstudy courses and allow users to request courses.
 *
 * @package     block_ps_selfstudy
 * @copyright   2015 Andres Ramos
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class viewrequests_table extends table_sql {

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    public function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array(
            'course_code',
            'course_name',
            'email',
            'firstname',
            'address',
            'address2',
            'city',
            'state',
            'zipcode',
            'country',
            'phone1',
            'request_date',
            'request_status'
        );
        // Define the titles of columns to show in header.
        $headers = array(
            get_string('coursecode', 'block_ps_selfstudy'),
            get_string('title', 'block_ps_selfstudy'),
            get_string('email', 'block_ps_selfstudy'),
            get_string('firstname', 'block_ps_selfstudy'),
            get_string('address', 'block_ps_selfstudy'),
            get_string('address2', 'block_ps_selfstudy'),
            get_string('city', 'block_ps_selfstudy'),
            get_string('state', 'block_ps_selfstudy'),
            get_string('zip', 'block_ps_selfstudy'),
            get_string('country', 'block_ps_selfstudy'),
            get_string('phone1', 'block_ps_selfstudy'),
            get_string('requestdate', 'block_ps_selfstudy'),
            get_string('status', 'block_ps_selfstudy'),
        );

        if (!$this->is_downloading()) {
            $columns[] = 'actions';
            $headers[] = 'Action';
        }

        global $DB;

        $this->sortable(true, 'course_code', SORT_ASC);
        $this->collapsible(false);
        $this->no_sorting('actions');

        $this->define_columns($columns);
        $this->define_headers($headers);

    }

    /**
     * This function is called for each data row to allow processing of the
     * username value.
     *
     * @param object $values Contains object with all the values of record.
     * @return $string Return username with link to profile or username only
     *     when downloading.
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
     * Generate the display of the address 2.
     * @param object $values the table row being output.
     * @return string HTML content to go inside the td.
     */
    public function col_address2($values) {
        global $DB;

        $address2id = $DB->get_record(
            'user_info_field', array('shortname' => 'address2'), $fields = 'id', $strictness = IGNORE_MISSING
        );
        $address2 = $DB->get_record(
            'user_info_data', array('userid' => $values->student_id, 'fieldid' => $address2id->id),
            $fields = 'data', $strictness = IGNORE_MISSING
        );
        if ($address2) {
            return $address2->data;
        }
        return "-";
    }

    /**
     * Generate the display of the zipcode.
     * @param object $values the table row being output.
     * @return string HTML content to go inside the td.
     */
    public function col_zipcode($values) {
        global $DB;

        $zipid = $DB->get_record(
            'user_info_field', array ('shortname' => 'zipcode'), $fields = 'id', $strictness = IGNORE_MISSING
        );
        $zipcode = $DB->get_record(
            'user_info_data',
            array('userid' => $values->student_id, 'fieldid' => $zipid->id),
            $fields = 'data', $strictness = IGNORE_MISSING
        );

        if ($zipcode) {
            return $zipcode->data;
        }
        return "-";
    }

    /**
     * Generate the display of the state.
     * @param object $values the table row being output.
     * @return string HTML content to go inside the td.
     */
    public function col_state($values) {
        global $DB;
        // Display full address.
        $stateid = $DB->get_record(
            'user_info_field', array('shortname' => 'state'), $fields = 'id', $strictness = IGNORE_MISSING
        );
        $state = $DB->get_record(
            'user_info_data', array('userid' => $values->student_id, 'fieldid' => $stateid->id),
            $fields = 'data', $strictness = IGNORE_MISSING
        );
        if ($state) {
            return $state->data;
        }
        return "-";
    }

    /**
     * Generate the display of the request status.
     * @param object $values the table row being output.
     * @return string HTML content to go inside the td.
     */
    public function col_request_status($values) {
        // If the value is 0, show Pending status.
        if ($values->request_status == 0) {
            return "Pending";
        } else {
            return "Shipped";
        }
    }

    /**
     * Generate the display of the request date.
     * @param object $values the table row being output.
     * @return string HTML content to go inside the td.
     */
    public function col_request_date($values) {
        // Show readable date from timestamp.
        $date = $values->request_date;
        return date("m/d/Y", $date);
    }

    /**
     * Generate the display of the action links.
     * @param object $values the table row being output.
     * @return string HTML content to go inside the td.
     */
    public function col_actions($values) {
        if (!$this->is_downloading()) {
            if ($values->request_status == 0) {
                return '<a href="action.php?action=deliver&requestid='.$values->id.'">
                        Delivered</a> - <a href="action.php?action=deleterequest&requestid='.$values->id.'">Delete</a>';
            } else {
                return '<a href="action.php?action=deleterequest&requestid='.$values->id.'">Delete</a>';
            }
        }
    }
}