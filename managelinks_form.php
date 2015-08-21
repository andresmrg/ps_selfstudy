<?php

require_once("{$CFG->libdir}/formslib.php");
global $CGG;

class managelinks_form extends moodleform {

    function definition() {
        $mform = & $this->_form;
        $options = array();
        //$mform->addElement('html', '<br><div><a href="displayemailtype.php">Manage emails </a></br>');
        //$mform->addElement('html', '<a href="addnewcron.php">Add new reminder</a></br></br>');
        $mform->addElement('button', 'intro', get_string('addcourse','block_ps_selfstudy'));
    }

}