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
 * Contains the functions to be executed on actions.php
 *
 * @package    block_ps_selfstudy
 * @copyright  2015 Andres Ramos
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Delete a request
 * @param integer $requestid
 * @return true if was deleted successfully, false otherwise
 */
function delete_request($requestid) {
    global $DB;

    // Delete course record.
    if (!$DB->delete_records('block_ps_selfstudy_request', array('id' => $requestid))) {
        return false;
    }

    // Delete all completions with this requests if any.
    $completionid = $DB->get_record('block_ps_selfstudy_complete', array('request_id' => $requestid), $fields = 'id');
    if ($completionid) {
        if (!$DB->delete_records('block_ps_selfstudy_complete', array('id' => $completionid->id))) {
            return false;
        }
        
    }
    return true;

}