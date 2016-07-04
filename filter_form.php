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
 * Generates the filter by code.
 *
 * @package block_ps_selfstudy
 * @copyright 2015 Andres Ramos
 */

require_once($CFG->libdir . '/formslib.php');

/**
 * Filter Form Class.
 **/
class filter_form extends moodleform {

    /**
     * Defines the form elements.
     *
     * @return void
     **/
    public function definition() {

        $mform = & $this->_form;

        // Input text.
        $mform->addElement('text', 'filter_code', get_string('field_filtercode', 'block_ps_selfstudy'));
        $mform->setType('filter_code', PARAM_NOTAGS);

        $buttonarray = array();
        $buttonarray[] =& $mform->createElement('submit', 'submit_button', get_string('submitbutton', 'block_ps_selfstudy'));
        $buttonarray[] =& $mform->createElement('cancel', 'cancel_button', get_string('resetbutton', 'block_ps_selfstudy'));
        $mform->addGroup($buttonarray, 'buttonarray', '', '', false);

    }
}