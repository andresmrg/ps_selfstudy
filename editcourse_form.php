<?php

require_once("{$CFG->libdir}/formslib.php");

class editcourse_form extends moodleform {

    function definition() {

        if(isset($_GET['id'])) {
            $id = $_GET['id'];    
        }
        if(isset($_GET['code'])) {
            $code = $_GET['code'];
        }
        if(isset($_GET['name'])) {
            $name = $_GET['name'];
        }
        if(isset($_GET['desc'])) {
            $description = base64_decode($_GET['desc']);
        }
        if(isset($_GET['type'])) {
            $type = $_GET['type'];
        }
        if(isset($_GET['status'])) {
            $status = $_GET['status'];
        }

        $mform = & $this->_form;
        
        // add group for text areas
        //$mform->addElement('header', 'displayinfo', get_string('text_coursename', 'block_ps_selfstudy'));

		// load existing course code
        $mform->addElement('text', 'course_code', get_string('field_coursecode', 'block_ps_selfstudy'));
        $mform->setType('course_code', PARAM_NOTAGS);
        $mform->addRule('course_code', null, 'required', null, 'client');
        $mform->setDefault('course_code', $code); 

        // load existing course name
        $mform->addElement('text', 'course_name', get_string('field_coursename', 'block_ps_selfstudy'));
        $mform->setType('course_name', PARAM_NOTAGS);
        $mform->addRule('course_name', null, 'required', null, 'client');
        $mform->setDefault('course_name', $name);  

        //textarea
        $mform->addElement('textarea', 'course_description', get_string("field_description", "block_ps_selfstudy"), 'wrap="virtual" rows="15" cols="50"');
        $mform->setType('course_description', PARAM_NOTAGS);
        $mform->addRule('course_description', null, 'required', null, 'client');
        $mform->setDefault('course_description', $description);  

        //checkbox for link courses, if it is not checked, it is like if it didn't exist
        $mform->addElement('checkbox', 'course_type', get_string('field_checkbox', 'block_ps_selfstudy'));
        $mform->addElement('html', 'To create a self-study course with link, select the checkbox above.<br><br>'); //note about the type course
        $mform->setDefault('course_type', $type); 

        //checkbox for link courses, if it is not checked, it is like if it didn't exist
        $mform->addElement('checkbox', 'course_status', get_string('field_checkbox_hide', 'block_ps_selfstudy'));
        $mform->addElement('html', 'Select the checkbox above to have the course hidden by default.<br><br>'); //note about the type course
        $mform->setDefault('course_status', $status); 

        //add time
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_NOTAGS);
        $mform->setDefault('id', $id); 

        $this->add_action_buttons();
    }

}
