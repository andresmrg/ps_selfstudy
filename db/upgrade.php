<?php
 
function xmldb_block_ps_selfstudy_upgrade($oldversion) {
    global $CFG, $DB;
 
    $result = TRUE;

 $dbman = $DB->get_manager();
 if ($oldversion < 2015081905) {

        // Define field course_platform to be added to block_ps_selfstudy_course.
        $table = new xmldb_table('block_ps_selfstudy_course');
        $table_request = new xmldb_table('block_ps_selfstudy_request');
        $field1 = new xmldb_field('course_platform', XMLDB_TYPE_CHAR, '40', null, XMLDB_NOTNULL, null, null, 'course_code');
        $field2 = new xmldb_field('course_hours', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'course_status');
        $field3 = new xmldb_field('course_link', XMLDB_TYPE_CHAR, '1024', null, XMLDB_NOTNULL, null, '0', 'course_hours');
        $field4 = new xmldb_field('request_status', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'request_date');
        
        
        // Conditionally launch add field course_platform.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        if (!$dbman->field_exists($table, $field3)) {
            $dbman->add_field($table, $field3);
        }
        $dbman->change_field_type($table_request, $field4);
        $dbman->change_field_precision($table_request, $field4);
        $dbman->change_field_default($table_request, $field4);

        // Ps_selfstudy savepoint reached.
        upgrade_block_savepoint(true, 2015081905, 'ps_selfstudy');
    }

    if ($oldversion < 2015082507) {

        // Define field id to be added to block_ps_selfstudy_complete.
        $table = new xmldb_table('block_ps_selfstudy_complete');
        $field = new xmldb_field('completion_date', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'completion_status');
        $dbman = $DB->get_manager();
        // Conditionally launch add field id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Ps_selfstudy savepoint reached.
        upgrade_block_savepoint(true, 2015082507, 'ps_selfstudy');
    }

     if ($oldversion < 2015082508) {

        // Define field request_id to be added to block_ps_selfstudy_complete.
        $table = new xmldb_table('block_ps_selfstudy_complete');
        $field = new xmldb_field('request_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'course_id');

        // Conditionally launch add field request_id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Ps_selfstudy savepoint reached.
        upgrade_block_savepoint(true, 2015082508, 'ps_selfstudy');
    }

    if ($oldversion < 2015082512) {

        // Define key course_id (foreign) to be added to block_ps_selfstudy_request.
        $table = new xmldb_table('block_ps_selfstudy_request');
        $key = new xmldb_key('course_id', XMLDB_KEY_FOREIGN, array('course_id'), 'block_ps_selfstudy_course', array('id'));

        // Launch add key course_id.
        $dbman->add_key($table, $key);

        // Ps_selfstudy savepoint reached.
        upgrade_block_savepoint(true, 2015082512, 'ps_selfstudy');
    }
    if ($oldversion < 2015082513) {

        // Define key student_id (foreign) to be added to block_ps_selfstudy_request.
        $table = new xmldb_table('block_ps_selfstudy_request');
        $key = new xmldb_key('student_id', XMLDB_KEY_FOREIGN, array('student_id'), 'user', array('id'));

        // Launch add key student_id.
        $dbman->add_key($table, $key);

        // Ps_selfstudy savepoint reached.
        upgrade_block_savepoint(true, 2015082513, 'ps_selfstudy');
    }
    if ($oldversion < 2015082514) {

        // Define field request_id to be dropped from block_ps_selfstudy_complete.
        $table = new xmldb_table('block_ps_selfstudy_complete');
        $field = new xmldb_field('course_id');

        // Conditionally launch drop field request_id.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Ps_selfstudy savepoint reached.
        upgrade_block_savepoint(true, 2015082514, 'ps_selfstudy');
    }
    if ($oldversion < 2015082515) {

        // Define field request_id to be dropped from block_ps_selfstudy_complete.
        $table = new xmldb_table('block_ps_selfstudy_complete');
        $field = new xmldb_field('student_id');

        // Conditionally launch drop field request_id.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Ps_selfstudy savepoint reached.
        upgrade_block_savepoint(true, 2015082515, 'ps_selfstudy');
    }
    if ($oldversion < 2015082516) {

        // Define key request_id (foreign) to be added to block_ps_selfstudy_complete.
        $table = new xmldb_table('block_ps_selfstudy_complete');
        $key = new xmldb_key('request_id', XMLDB_KEY_FOREIGN, array('request_id'), 'block_ps_selfstudy_request', array('id'));

        // Launch add key request_id.
        $dbman->add_key($table, $key);

        // Ps_selfstudy savepoint reached.
        upgrade_block_savepoint(true, 2015082516, 'ps_selfstudy');
    }

    if ($oldversion < 2015092500) {

        // Define field description_link to be added to block_ps_selfstudy_course.
        $table = new xmldb_table('block_ps_selfstudy_course');
        $field = new xmldb_field('description_link', XMLDB_TYPE_CHAR, '1024', null, null, null, null, 'course_code');

        // Conditionally launch add field description_link.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Ps_selfstudy savepoint reached.
        upgrade_block_savepoint(true, 2015082517, 'ps_selfstudy');
    }

 
    return $result;
}
?>