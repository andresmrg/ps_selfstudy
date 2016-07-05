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
 * Form for creating a selfstudy course
 * @package    block_ps_selfstudy
 * @copyright  2015 Andres Ramos
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir . '/formslib.php');

/**
 * Class form for creating a selfstudy course.
 *
 * @package     block_ps_selfstudy
 * @author      Andres Ramos
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/

class createcourse_form extends moodleform {

    /**
     * Main function to define the fields of the form.
     **/
    public function definition() {

        $mform = & $this->_form;

        // Add course name.
        $mform->addElement('text', 'course_code', get_string('field_coursecode', 'block_ps_selfstudy'));
        $mform->setType('course_code', PARAM_NOTAGS);
        $mform->addRule('course_code', null, 'required', null, 'client');

        // Add description link.
        $mform->addElement('text', 'description_link', get_string('field_description_link', 'block_ps_selfstudy'));
        $mform->setType('description_link', PARAM_NOTAGS);

        // Add course name.
        $mform->addElement('text', 'course_platform', get_string('field_platform', 'block_ps_selfstudy'));
        $mform->setType('course_platform', PARAM_NOTAGS);
        $mform->addRule('course_platform', null, 'required', null, 'client');

        $mform->addElement('text', 'course_name', get_string('field_coursename', 'block_ps_selfstudy'));
        $mform->setType('course_name', PARAM_NOTAGS);
        $mform->addRule('course_name', null, 'required', null, 'client');

        $mform->addElement(
            'textarea', 'course_description',
            get_string("field_description", "block_ps_selfstudy"), 'wrap="virtual" rows="15" cols="50"'
        );
        $mform->setType('course_description', PARAM_RAW);
        $mform->addRule('course_description', null, 'required', null, 'client');

        $mform->addElement('text', 'course_hours', get_string('field_hours', 'block_ps_selfstudy'));
        $mform->setType('course_hours', PARAM_NOTAGS);
        $mform->addRule('course_hours', null, 'required', null, 'client');

        // Checkbox for link courses, 0 for physical courses, 1 for link type courses.
        $mform->addElement(
            'advcheckbox', 'course_type',
            get_string('field_checkbox', 'block_ps_selfstudy'),
            get_string('createcoursewithlink',  'block_ps_selfstudy'), array('group' => 1), array(0, 1)
        );

        // Link.
        $mform->addElement('text', 'course_link', get_string('field_link', 'block_ps_selfstudy'));
        $mform->setType('course_link', PARAM_NOTAGS);
        $mform->disabledIf('course_link', 'course_type');

        // Checkbox for course status, 0 for active, 1 for hidden.
        $mform->addElement(
            'advcheckbox', 'course_status',
            get_string('field_checkbox_hide', 'block_ps_selfstudy'),
            get_string('hidecoursebydefault',  'block_ps_selfstudy'), array('group' => 2), array(0, 1)
        );

        // Add time.
        $mform->addElement('hidden', 'date_created');
        $mform->setType('date_created', PARAM_NOTAGS);

        $this->add_action_buttons();
    }

    /**
     * This function is to validate the course code is not saved twice.
     * @return array
     * @author Andres Ramos
     * @param array $data Contains the form values.
     **/
    public function validation($data, $files) {
        global $DB;

        $errors = array();
        if ($DB->record_exists('block_ps_selfstudy_course', array('course_code' => $data['course_code']))) {
            $errors['course_code'] = get_string('course_duplicated", "block_ps_selfstudy');
        }

        return $errors;
    }
}