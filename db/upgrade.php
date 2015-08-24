<?php
 
function xmldb_block_ps_selfstudy_upgrade($oldversion) {
    global $CFG, $DB;
 
    $result = TRUE;
 
 if ($oldversion < 2015081905) {

        // Define field course_platform to be added to block_ps_selfstudy_course.
        $table = new xmldb_table('block_ps_selfstudy_course');
        $table_request = new xmldb_table('block_ps_selfstudy_request');
        $field1 = new xmldb_field('course_platform', XMLDB_TYPE_CHAR, '40', null, XMLDB_NOTNULL, null, null, 'course_code');
        $field2 = new xmldb_field('course_hours', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'course_status');
        $field3 = new xmldb_field('course_link', XMLDB_TYPE_CHAR, '1024', null, XMLDB_NOTNULL, null, '0', 'course_hours');
        $field4 = new xmldb_field('request_status', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'request_date');
        
        $dbman = $DB->get_manager();
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
 
    return $result;
}
?>