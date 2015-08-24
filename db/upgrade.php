<?php
 
function xmldb_block_ps_selfstudy_upgrade($oldversion) {
    global $CFG, $DB;
 
    $result = TRUE;
 
 if ($oldversion < 2015081902) {

        // Define field course_platform to be added to block_ps_selfstudy_course.
        $table = new xmldb_table('block_ps_selfstudy_course');
        $field1 = new xmldb_field('course_platform', XMLDB_TYPE_CHAR, '40', null, XMLDB_NOTNULL, null, null, 'course_code');
        $field2 = new xmldb_field('course_hours', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'course_status');
        
        $dbman = $DB->get_manager();
        // Conditionally launch add field course_platform.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }

        // Ps_selfstudy savepoint reached.
        upgrade_block_savepoint(true, 2015081902, 'ps_selfstudy');
    }
 
    return $result;
}
?>