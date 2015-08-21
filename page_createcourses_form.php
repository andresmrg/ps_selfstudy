<?php

require_once("{$CFG->libdir}/formslib.php");

class page_managecourses_form extends moodleform {

    function definition() {

        $mform = & $this->_form;
        
        // add group for text areas
        //$mform->addElement('header', 'displayinfo', get_string('text_coursename', 'block_ps_selfstudy'));

		// add course name
		$mform->addElement('text', 'course_code', get_string('field_coursecode', 'block_ps_selfstudy'));
        $mform->addElement('text', 'course_name', get_string('field_coursename', 'block_ps_selfstudy'));
        $mform->addRule('course_code', null, 'required', null, 'client');
        $mform->addRule('course_name', null, 'required', null, 'client');

        //textarea
        $mform->addElement('textarea', 'course_description', get_string("field_description", "block_ps_selfstudy"), 'wrap="virtual" rows="20" cols="50"');
        $mform->addRule('course_description', null, 'required', null, 'client');

        //checkbox for link courses, if it is not checked, it is like if it didn't exist
        $mform->addElement('checkbox', 'course_type', get_string('field_checkbox', 'block_ps_selfstudy'));
        $mform->addElement('html', 'To create a self-study course with link, select the checkbox above.<br><br>'); //note about the type course

        //checkbox for link courses, if it is not checked, it is like if it didn't exist
        $mform->addElement('checkbox', 'course_status', get_string('field_checkbox_hide', 'block_ps_selfstudy'));
        $mform->addElement('html', 'Select the checkbox above to have the course hidden by default.<br><br>'); //note about the type course

        //add time
        $mform->addElement('hidden', 'date_created');

        $this->add_action_buttons();
    }

}
