<?php

/**
 * Block that allows users to access courses in a demo-course
 * category. The Category must be defined in the Configuration
 * of this block.
 *
 *
 * get_content() checks if a category has been defined and lists
 * the courses in this category with a link to /blocks/demologin/logindemo.php
 * using "course=*course shortname*" as parameter.
 *
 * The shortname must be alphanumeric, with no whitespaces or other characters.
 *
 * logindemo2.php will then create a specific demo user with full
 * student rights for the courses and logs in the user in as the demo user.
 *
 * @author Ralf Wiederhold <ralf.wiederhold@email.de>
 */
class block_demologin extends block_base {

	var $title;
	var $version;
	var $content;


	function init() {
		$this->title = get_string('title', 'block_demologin');
	}

    function applicable_formats() {
        return array('site' => true);
    }

	function get_content() {

		global $CFG;

		if($this->content !== NULL) {
			return $this->content;
		}

		$this->content = new stdClass;
		$this->content->footer = '';

		$cat = $CFG->block_demologin_democategory;
		if($cat == 0) {
			$this->content->text = '<div class="content error">'.get_string('error_no_category_defined', 'block_demologin').'</div>';
			return $this->content;
		}

		if(empty($CFG->block_demologin_max_demo_users)) {
			$this->content->text = '<div class="content error">'.get_string('error_no_max_users_defined', 'block_demologin').'</div>';
			return $this->content;
		}

		if(empty($CFG->block_demologin_demouser_role_course)) {
			$this->content->text = '<div class="content error">'.get_string('error_no_demouser_role_course_defined', 'block_demologin').'</div>';
			return $this->content;
		}

        if(empty($CFG->block_demologin_demouser_role_system)) {
			$this->content->text = '<div class="content error">'.get_string('error_no_demouser_role_system_defined', 'block_demologin').'</div>';
			return $this->content;
		}


		$courses = get_courses($cat, 'c.sortorder ASC', 'c.id, c.shortname, c.fullname');
		if(count($courses) > 0) {

			$content = '';
			if(!empty($CFG->block_demologin_description)) {
				$content = '<div class="content">'.$CFG->block_demologin_description.'</div>';
			}

			$content .= '<ul>';
			foreach($courses as $course) {

				$content .= '<li>
								<a href="'.$CFG->wwwroot.'/blocks/demologin/logindemo.php?course='.$course->shortname.'">'.$course->fullname.'</a>
							 </li>';
			}
			$content .= '</ul>';
		}
		else {
			$content = '<div class="content error">'.get_string('error_no_courses_in_category', 'block_demologin').'</div>';
		}

		$this->content->text = $content;
		return $this->content;
	}

	function has_config() {
		return true;
	}

}
