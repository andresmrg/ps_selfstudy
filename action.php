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
 * This file will handle all requests and execute them
 * based on the action peformed.
 *
 * @package    block_ps_selfstudy
 * @copyright  2015 Andres Ramos
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/blocks/ps_selfstudy/locallib.php');

$action     = optional_param('action',  0,  PARAM_NOTAGS);
$requestid  = optional_param('requestid', 0, PARAM_NOTAGS);
$page       = optional_param('page', 0, PARAM_NOTAGS);

switch ($action) {
    case 'deleterequest':
        $result = delete_request($requestid);
        
        if($result) {
            // Redirect the user to the page where the deletion was made.
            if ($page) {
                $url = new moodle_url('/blocks/ps_selfstudy/viewallrequests.php');
                redirect($url);
            } else {
                $url = new moodle_url('/blocks/ps_selfstudy/viewrequests.php');
                redirect($url);
            }
        }
        break;
    
    default:
        # code...
        break;
}