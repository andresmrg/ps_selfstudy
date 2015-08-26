<?php 

defined('MOODLE_INTERNAL') || die();
$messageproviders = array (
    // Notify teacher that a student has submitted a quiz attempt
    'submission' => array (
        'capability'  => 'block/ps_selfstudy:emailnotifysubmission'
    ),
    // Confirm a student's quiz attempt
    'confirmation' => array (
        'capability'  => 'block/ps_selfstudy:emailconfirmsubmission'
    )
);