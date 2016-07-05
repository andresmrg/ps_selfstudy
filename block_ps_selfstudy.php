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
 * Selfstudy block
 *
 * @package    block_ps_selfstudy
 * @copyright  Andres Ramos
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_ps_selfstudy extends block_list {

    public function init() {
        $this->title = get_string('selfstudy', 'block_ps_selfstudy');
    }

    public function get_content() {

        global $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons = array();

        $managecourses = new moodle_url('/blocks/ps_selfstudy/managecourses.php');
        $viewrequests = new moodle_url('/blocks/ps_selfstudy/viewrequests.php');
        $myrequests = new moodle_url('/blocks/ps_selfstudy/myrequests.php');
        $viewcompletion = new moodle_url('/blocks/ps_selfstudy/viewcompletion.php');

        $context = context_system::instance();
        if (has_capability('block/ps_selfstudy:managecourses', $context, $USER->id)) {
            $this->content->items[] = html_writer::link($managecourses, get_string('link_managecourses', 'block_ps_selfstudy'));
        }
        if (has_capability('block/ps_selfstudy:viewrequests', $context, $USER->id)) {
            $this->content->items[] = html_writer::link($viewrequests, get_string('link_requests', 'block_ps_selfstudy'));
        }
        if (has_capability('block/ps_selfstudy:viewrequests', $context, $USER->id)) {
            $this->content->items[] = html_writer::link($viewcompletion, get_string('link_completion', 'block_ps_selfstudy'));
        }
        if (has_capability('block/ps_selfstudy:myrequests', $context, $USER->id)) {
            $this->content->items[] = html_writer::link($myrequests, get_string('link_myrequests', 'block_ps_selfstudy'));
        }
        return $this->content;
    }

    /**
     * Defines where this block should be available.
     * @return array
     **/
    public function applicable_formats() {
        return array(
               'site-index'     => true,
              'course-view'     => true,
        'course-view-social'    => true,
                      'mod'     => true,
                 'mod-quiz'     => true
        );
    }

    /**
     * Defines whether you can allow multiple instances of the same block.
     * @return false to prevent multiple instances
     **/
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Defines where this block has configuration options.
     * @return false, so it doesn't have config.
     **/
    public function has_config() {
        return false;
    }

}