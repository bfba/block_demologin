<?php

///////////////////////////////////////
// DEFINE STATIC VALUES
///////////////////////////////////////

	define('DEMO_REPLAY_PAUSE', 5); //seconds
	define('DEMO_SESSION_TIMEOUT', 300); //seconds
    define('DEMO_USERNAME', 'demo');
    define('DEMO_PASSWORD', 'demo');
    define('DEMO_FIRSTNAME', 'demo');
    define('DEMO_LASTNAME', 'user');
    define('DEMO_EMAIL', 'demo');
    define('DEMO_CITY', 'Berlin');
    define('DEMO_COUNTRY', 'DE');
    define('DEMO_DESCRIPTION', 'Demo-Benutzer');

///////////////////////////////////////
// DEFINE VARIABLE VALUES
///////////////////////////////////////

	//get demo category from CFG
	if(empty($CFG->block_demologin_democategory)) {
		print_error(get_string('error_no_category_defined', 'block_demologin'));
	}
	define('DEMO_CATEGORY', $CFG->block_demologin_democategory);

	//get number of demousers from CFG
	if(empty($CFG->block_demologin_max_demo_users)) {
		print_error(get_string('error_no_max_users_defined', 'block_demologin'));
	}
    $maxusers = (int)$CFG->block_demologin_max_demo_users;
    define('DEMO_MAX_USERCOUNT', $maxusers);

	//get roleid for demousers in course from CFG
	if(empty($CFG->block_demologin_demouser_role_course)) {
		print_error(get_string('error_no_demouser_role_course_defined', 'block_demologin'));
	}
    define('DEMO_USERROLE_COURSE', $CFG->block_demologin_demouser_role_course);

    //get roleid for demousers in system from CFG
	if(empty($CFG->block_demologin_demouser_role_system)) {
		print_error(get_string('error_no_demouser_role_system_defined', 'block_demologin'));
	}
    define('DEMO_USERROLE_SYSTEM', $CFG->block_demologin_demouser_role_system);


/**
 * Utility class for the demologin Block
 * (static access)
 *
 *
 * @author Ralf Wiederhold <ralf.wiederhold@email.de>
 *
 */
class block_demologin_util {

	/**
	 * Returns the current value of the
	 * status counter (index of current
	 * demoaccount)
	 *
	 * Sets initial value if it doesn't
	 * exist yet
	 *
	 * @return {Integer} status counter
	 */
	static function get_status() {
            global $CFG, $DB;

            return (int) get_config('block_demologin', 'statusvar');
	}

	/**
	 * Sets the current value of the
	 * status counter (index of current
	 * demoaccount)
	 *
	 * @param {Integer} status counter
	 * @return {Void}
	 */
	static function set_status($status) {
            set_config('statusvar', $status, 'block_demologin');
	}

	/**
	 * Tests whether a user exists
	 *
	 * @param {String} the username
	 * @return {Boolean}
	 */
	static function user_exists($username) {
            global $CFG, $DB;

            return $DB->record_exists_sql("SELECT id FROM {user} WHERE username = ?", array($username));
	}

	/**
	 * Creates a new user and enrols him into
	 * the demo courses
	 *
	 * @param {String} username
	 * @param {String} password
	 * @param {String} Optional authentication method, default = manual
	 * @return {stdClass} User-Object
	 */
	static function create_user($username, $password, $auth = 'manual') {
            global $CFG, $DB;


            $newuser = create_user_record($username, $password, $auth); //->moodle/lib/moodlelib.php

            $newuser->firstname = DEMO_FIRSTNAME;
            $newuser->lastname = DEMO_LASTNAME;
            $newuser->email = DEMO_EMAIL;
            $newuser->emailstop = 1;
            $newuser->city = DEMO_CITY;
            $newuser->country = DEMO_COUNTRY;
            $newuser->description = DEMO_DESCRIPTION;
            $newuser->maildisplay = 0;
            $newuser->policyagreed = 1;

            $DB->update_record('user', $newuser);

            $systemcontext = context_system::instance();
            role_assign(DEMO_USERROLE_SYSTEM, $newuser->id, $systemcontext->id);

            //enrol into democourses
            if($courses = $DB->get_records('course', array('category' => DEMO_CATEGORY))){
                foreach ($courses as $course) {
                    enrol_try_internal_enrol($course->id, $newuser->id, DEMO_USERROLE_COURSE); //new 2.0 API function, -> moodle/lib/enrollib.php
                                                                                                                                             //uses the default enrolment method,
                                                                                                                                             //"manual"-method is hardcoded at 21.12.2010, Moodle 2.0 (Build: 20101214)
                }
            }
            return $newuser;
	}

	/**
	 * Completely deletes an user
	 *
	 * @param {String} username
	 * @return {Void}
	 */
	static function delete_user($username) {
		global $CFG, $DB;

		$user = $DB->get_record_sql("SELECT * FROM {user} WHERE username = ?", array($username));

		delete_user($user); //->moodle/lib/moodlelib.php

		//and delete disabled user record
       // $DB->delete_records('user', array('id' => $user->id));


        //delete anything else
		self::delete_some_things($user->id);

	}

	/**
	 * Checks whether the given user has
	 * an active session (i.e. accessed
	 * the page in the last 5 minutes)
	 *
	 * @param {String} username
	 * @return {Boolean}
	 */
	static function user_in_use($username) {
		global $CFG, $DB;

		$user = $DB->get_record_sql("SELECT lastaccess FROM {user} WHERE username = ?", array($username));

		return ($user->lastaccess + DEMO_SESSION_TIMEOUT) > time();
	}

	/**
	 * Resets the whole plugin
	 *
	 * Every demouser will be deleted and
	 * the statcounter will be resetted
	 *
	 * @param {Boolean} Delete active Users too?
	 * @return {Void}
	 */
	static function reset_plugin($force = false) {
		global $CFG, $DB;

		if($demousers = $DB->get_records_sql("SELECT username FROM {user} WHERE email LIKE ?", array('demo%'))) {

			foreach($demousers as $user) {
				if($force) {
					self::delete_user($user->username);
				}
				else {
					if(!self::user_in_use($user->username)) {
						self::delete_user($user->username);
					}
				}
			}
		}

		self::set_status(0);
	}

/////////////////////////////////////////////////////////////
// PRIVATE METHODS
/////////////////////////////////////////////////////////////

	/**
	 * Deletes all Posts of a user, including complete discussions
	 *
	 * @param {Integer} user_id
	 * @return {Void}
	 */
    static function delete_forum_posts($userid) {
		global $DB;

        if((!$posts = $DB->get_records('forum_posts', array('userid' => $userid))) || (count($posts) < 1) ) {
            return;
        }
        foreach($posts as $post) {
            self::delete_forum_full_posts($post->id);
        }

        $DB->delete_records('forum_discussions', array('userid' => $userid));
        $DB->delete_records('forum_read', array('userid' => $userid));
        $DB->delete_records('forum_subscriptions', array('userid' => $userid));
    }


	/**
	 * Deletes all subsequent answers to a post
	 *
	 * @param {Integer} post_id
	 * @return {Void}
	 */
    static function delete_forum_full_posts($parent) {
		global $DB;

        //Antworten finden
        if($posts = $DB->get_records('forum_posts', array('parent' => $parent))) {
            foreach($posts as $post) {
                self::delete_forum_full_posts($post->id);
            }
        }
        $DB->delete_records('forum_ratings', array('id' => $parent));
        $DB->delete_records('forum_posts', array('id' => $parent));
    }


	/**
	 * Deletes anything related to a user, doesn't really care for integrity
	 *
	 * @param {Integer} user_id
	 * @return {Void}
	 */
    static function delete_some_things($userid){
		global $DB;

        if ($todelete = $DB->get_records('quiz_attempts', array('userid' => $userid))) {
            foreach($todelete as $del_attempt) {
                $DB->delete_records('quiz_attempts', array('id' => $del_attempt->id));
            }
        }
		$DB->delete_records('quiz_grades', array('userid' => $userid));

		$DB->delete_records('assignment_submissions', array('userid' => $userid));
        $DB->delete_records('chat_messages', array('userid' => $userid));
        $DB->delete_records('chat_users', array('userid' => $userid));
        $DB->delete_records('choice_answers', array('userid' => $userid));
//        $DB->delete_records('course_display', array('userid' => $userid));
        $DB->delete_records('glossary_entries', array('userid' => $userid));
        $DB->delete_records('lesson_attempts', array('userid' => $userid));
        $DB->delete_records('lesson_grades', array('userid' => $userid));
        $DB->delete_records('lesson_high_scores', array('userid' => $userid));
        $DB->delete_records('lesson_timer', array('userid' => $userid));
        $DB->delete_records('log', array('userid' => $userid));
        $DB->delete_records('message', array('useridfrom' => $userid));
        $DB->delete_records('message_contacts', array('userid' => $userid));
        $DB->delete_records('message_read', array('useridfrom' => $userid));
        $DB->delete_records('scorm_scoes_track', array('userid' => $userid));
        $DB->delete_records('stats_user_daily', array('userid' => $userid));
        $DB->delete_records('stats_user_monthly', array('userid' => $userid));
        $DB->delete_records('stats_user_weekly', array('userid' => $userid));
        $DB->delete_records('user_preferences', array('userid' => $userid));
        $DB->delete_records('grade_grades', array('userid' => $userid));
        $DB->delete_records('grade_grades_history', array('userid' => $userid));

        //forenbeitraege loesschen
        self::delete_forum_posts($userid);

		//nun den user selbst loeschen
        $DB->delete_records('user', array('id' => $userid));
    }

}
