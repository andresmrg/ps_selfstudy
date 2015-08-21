<?php
/**
 * Test table class to be put in test_table.php of root of Moodle installation.
 *  for defining some custom column names and proccessing
 * Username and Password feilds using custom and other column methods.
 */
class test_table extends table_sql {

    /**
     * Constructor
     * @param int $uniqueid all tables have to have a unique id, this is used
     *      as a key when storing table properties like sort order in the session.
     */
    function __construct($uniqueid) {
        parent::__construct($uniqueid);
        // Define the list of columns to show.
        $columns = array('course_name', 'course_description', 'course_type','course_status','date_created');
        $this->define_columns($columns);

        // Define the titles of columns to show in header.
        $headers = array('Course Name', 'Description', 'Course Type','Status','Date Created');
        $this->define_headers($headers);
    }

    /**
     * This function is called for each data row to allow processing of the
     * username value.
     *
     * @param object $values Contains object with all the values of record.
     * @return $string Return username with link to profile or username only
     *     when downloading.
     */
    function course_username($values) {
        // If the data is being downloaded than we don't want to show HTML.
        if ($this->is_downloading()) {
            return $values->username;
        } else {
            return '<a href="$CFG->wwwroot/../../../user/profile.php?id='.$values->id.'">'.$values->username.'</a>';
        }
    }

    /**
     * This function is called for each data row to allow processing of
     * columns which do not have a *_cols function.
     * @return string return processed value. Return NULL if no change has
     *     been made.
     */
    function other_cols($colname, $value) {
        // For security reasons we don't want to show the password hash.
        if ($colname == 'course_type') {
            //print_object($value->course_type);
            if($value->course_type == 0) {
                return "Phisical Copy";    
            } else {
                return "Link Course";
            }
        }
        if ($colname == 'course_status') {
            if($value->course_status == 0) {
                return "Active";    
            } else {
                return "Disable";
            }
        }
        if ($colname == 'date_created') {
            $date = $value->date_created;
            return date("m/d/Y",$date);
        }
    }
}