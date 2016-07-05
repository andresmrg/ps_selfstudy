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
 * Form that a users have to fill out to request a course.
 *
 * @package     block_ps_selfstudy
 * @copyright   2015 Andres Ramos
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . '/formslib.php');

/**
 * Form for requesting a selfstudy course
 * @package    block_ps_selfstudy
 * @copyright  2015 Andres Ramos
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class requestcourse_form extends moodleform {

    /**
     * Defines the form elements.
     *
     * @return void
     **/
    protected function definition() {

        global $USER, $CFG, $DB;

        $courseid = optional_param('id',  0,  PARAM_INT);

        $mform = & $this->_form;

        $course = $DB->get_record(
            'block_ps_selfstudy_course',
            array ('id' => $courseid), $fields = '*', $strictness = IGNORE_MISSING
        );
        $mform->addElement('html', get_string('requestingcopyofcourse', 'block_ps_selfstudy', $course->course_name));

        // Group user profile fields.
        $mform->addElement('header', 'displayinfo', get_string('group_userfields', 'block_ps_selfstudy'));

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_NOTAGS);
        $mform->setDefault('courseid', $courseid);

        // Load all users profile fields.
        $mform->addElement('text', 'firstname', get_string('firstname', 'block_ps_selfstudy'));
        $mform->setType('firstname', PARAM_NOTAGS);
        $mform->addRule('firstname', null, 'required', null, 'client');
        $mform->setDefault('firstname', $USER->firstname);

        $mform->addElement('text', 'lastname', get_string('lastname', 'block_ps_selfstudy'));
        $mform->setType('lastname', PARAM_NOTAGS);
        $mform->addRule('lastname', null, 'required', null, 'client');
        $mform->setDefault('lastname', $USER->lastname);

        $mform->addElement('text', 'email', get_string("email", "block_ps_selfstudy"));
        $mform->setType('email', PARAM_EMAIL);
        $mform->addRule('email', null, 'required', null, 'client');
        $mform->addRule('email', null, 'email', null, 'client');
        $mform->setDefault('email', $USER->email);

        // Group shipping information.
        $mform->addElement('header', 'shipping', get_string('group_shipping', 'block_ps_selfstudy'));

        $mform->addElement('text', 'address', get_string('address', 'block_ps_selfstudy'));
        $mform->setType('address', PARAM_NOTAGS);
        $mform->addRule('address', null, 'required', null, 'client');
        $mform->setDefault('address', $USER->address);

        // Get address2.
        $address2id = $DB->get_record(
            'user_info_field',
            array('shortname' => 'address2'), $fields = 'id', $strictness = IGNORE_MISSING
        );
        if (!empty($address2id)) {
            $address2 = $DB->get_record(
                'user_info_data',
                array ('userid' => $USER->id, 'fieldid' => $address2id->id),
                $fields = 'data', $strictness = IGNORE_MISSING
            );
            $mform->addElement('text', 'address2', get_string('address2', 'block_ps_selfstudy'));
            $mform->setType('address2', PARAM_NOTAGS);

            if (!empty($address2)) {
                $mform->setDefault('address2', $address2->data);
            }
        }

        $mform->addElement('text', 'city', get_string('city', 'block_ps_selfstudy'));
        $mform->setType('city', PARAM_NOTAGS);
        $mform->addRule('city', null, 'required', null, 'client');
        $mform->setDefault('city', $USER->city);

        // Get state.
        $stateid = $DB->get_record(
            'user_info_field',
            array ('shortname' => 'state'), $fields = 'id', $strictness = IGNORE_MISSING
        );
        if (!empty($stateid)) {
            $state = $DB->get_record(
                'user_info_data',
                array('userid' => $USER->id, 'fieldid' => $stateid->id),
                $fields = 'data', $strictness = IGNORE_MISSING
            );
            $mform->addElement('text', 'state', get_string('state', 'block_ps_selfstudy'));
            $mform->setType('state', PARAM_NOTAGS);
            $mform->addRule('state', null, 'required', null, 'client');
            if (!empty($state)) {
                $mform->setDefault('state', $state->data);
            }
        }

        // Get zipcode.
        $zipid = $DB->get_record(
            'user_info_field',
            array ('shortname' => 'zipcode'), $fields = 'id', $strictness = IGNORE_MISSING
        );
        if (!empty($zipid)) {
            $zipcode = $DB->get_record(
                'user_info_data',
                array ('userid' => $USER->id, 'fieldid' => $zipid->id),
                $fields = 'data', $strictness = IGNORE_MISSING
            );
            $mform->addElement('text', 'zipcode', get_string('zipcode', 'block_ps_selfstudy'), 'maxlength="7" minlength="5"');
            $mform->setType('zipcode', PARAM_NOTAGS);
            $mform->addRule('zipcode', null, 'required', null, 'client');

            if (!empty($zipcode)) {
                $mform->setDefault('zipcode', $zipcode->data);
            }
        }

        $choices = get_string_manager()->get_list_of_countries();
        $choices = array('' => get_string('selectacountry') . '...') + $choices;
        $mform->addElement('select', 'country', get_string('country', 'block_ps_selfstudy'), $choices);
        if (!empty($CFG->country)) {
            $mform->setDefault('country', $CFG->country);
        }
        if (!empty($USER->country)) {
            $mform->setDefault('country', $USER->country);
        }

        $mform->addElement('text', 'phone1', get_string('phone1', 'block_ps_selfstudy'), 'maxlength="20" minlength="5"');
        $mform->setType('phone1', PARAM_NOTAGS);
        $mform->addRule('phone1', null, 'required', null, 'client');
        $mform->setDefault('phone1', $USER->phone1);

        $this->add_action_buttons(true, get_string('submitbutton', 'block_ps_selfstudy'));
    }
}