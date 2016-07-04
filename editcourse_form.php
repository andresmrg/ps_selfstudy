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
 * This file generates the from to allow users to edit a course.
 *
 * @package block_ps_selfstudy
 * @copyright 2015 Andres Ramos
 */

require_once($CFG->libdir . '/formslib.php');

/**
 * Class to generates the form.
 */
class editcourse_form extends moodleform {

    public function definition() {
        global $DB;

        $id = optional_param('id',  0,  PARAM_INT);
        $course = $DB->get_record('block_ps_selfstudy_course', array('id' => $id),  $fields = '*',  $strictness = IGNORE_MISSING);

        $mform = & $this->_form;

        // Pass the id.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_NOTAGS);
        if (!empty($id)) {
            $mform->setDefault('id', $id);
        }

        // Load existing course code.
        $mform->addElement('text', 'course_code', get_string('field_coursecode', 'block_ps_selfstudy'));
        $mform->setType('course_code', PARAM_NOTAGS);
        $mform->addRule('course_code', null, 'required', null, 'client');
        if (!empty($course->course_code)) {
            $mform->setDefault('course_code', $course->course_code);
        }

        $mform->addElement('text', 'description_link', get_string('field_description_link', 'block_ps_selfstudy'));
        $mform->setType('description_link', PARAM_NOTAGS);
        if (!empty($course->description_link)) {
            @$mform->setDefault('description_link', $course->description_link);
        }

        $mform->addElement('text', 'course_platform', get_string('field_platform', 'block_ps_selfstudy'));
        $mform->setType('course_platform', PARAM_NOTAGS);
        $mform->addRule('course_platform', null, 'required', null, 'client');
        if (!empty($course->course_platform)) {
            $mform->setDefault('course_platform', $course->course_platform);
        }

        // Load existing course name.
        $mform->addElement('text', 'course_name', get_string('field_coursename', 'block_ps_selfstudy'));
        $mform->setType('course_name', PARAM_NOTAGS);
        $mform->addRule('course_name', null, 'required', null, 'client');
        if (!empty($course->course_name)) {
            $mform->setDefault('course_name', $course->course_name);
        }

        $mform->addElement(
            'textarea', 'course_description',
            get_string("field_description", "block_ps_selfstudy"),
            'wrap = "virtual" rows = "15" cols = "50"'
        );
        $mform->setType('course_description', PARAM_RAW);
        $mform->addRule('course_description', null, 'required', null, 'client');
        if (!empty($course->course_description)) {
            $mform->setDefault('course_description', $course->course_description);
        }

        $mform->addElement('text', 'course_hours', get_string('field_hours', 'block_ps_selfstudy'));
        $mform->setType('course_hours', PARAM_NOTAGS);
        $mform->addRule('course_hours', null, 'required', null, 'client');
        if (!empty($course->course_hours)) {
            $mform->setDefault('course_hours', $course->course_hours);
        }

        // Checkbox for link courses, if it is not checked, it is like if it didn't exist.
        $mform->addElement(
            'advcheckbox', 'course_type',
            get_string('field_checkbox', 'block_ps_selfstudy'),
            'Select to create a self-study course with link.', array('group' => 1), array(0, 1)
        );
        if (!empty($course->course_type)) {
            $mform->setDefault('course_type', $course->course_type);
        }

        $mform->disabledif ('course_link', 'course_type');
        $mform->addElement('text', 'course_link', get_string('field_link', 'block_ps_selfstudy'));
        $mform->setType('course_link', PARAM_NOTAGS);
        if (!empty($course->course_link)) {
            $mform->setDefault('course_link', $course->course_link);
        }

        // Checkbox for link courses, if it is not checked, it is like if it didn't exist.
        $mform->addElement(
            'advcheckbox', 'course_status',
            get_string('field_checkbox_hide', 'block_ps_selfstudy'),
            'Select to hide the course by default.', array('group' => 2), array(0, 1)
        );
        @$mform->setDefault('course_status', $course->course_status);

        $this->add_action_buttons();
    }
}