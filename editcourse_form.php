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

        //pass id
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_NOTAGS);
        $mform->setDefault('id', $id);

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
        $mform->addElement('advcheckbox', 'course_type', get_string('field_checkbox', 'block_ps_selfstudy'), 'Select to create a self-study course with link.', array('group' => 1), array(0, 1));
        $mform->setDefault('course_type', $type); 

        //checkbox for link courses, if it is not checked, it is like if it didn't exist
        $mform->addElement('advcheckbox', 'course_status', get_string('field_checkbox_hide', 'block_ps_selfstudy'), 'Select to hide the course by default.', array('group' => 2), array(0, 1));
        $mform->setDefault('course_status', $status);
        
        $this->add_action_buttons();
    }

}
