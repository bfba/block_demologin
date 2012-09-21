<?php 
/**
 * Manages the demoaccounts for the block "demologin"
 *
 * If someone requests to view a democourse, this script 
 * will cycled through a bunch of demoaccounts and assign
 * an account to the user, which will then be logged in
 * with that account
 *
 * Does no real cleanup of the database. Demoaccounts have to
 * be deleted manually after the block has been deinstalled.
 *
 * @author ?
 * @revised 19.12.2010
 * @author Ralf Wiederhold <ralf.wiederhold@email.de>
 */

 
    require_once '../../config.php';
    require_once 'demologin_util.class.php'; //Constants defined here
	
	//for fast reset in testing
	//block_demologin_util::reset_plugin(true);exit;
	
	//Check if cookies are available
    if(!$SESSION->on) {
        print_error('error_enable_cookies', 'block_demologin');
    }

///////////////////////////////////////
// CHECK FOR MISUSE
///////////////////////////////////////   
	
    //The values used in 1.9 Version are not available in Moodle 2.0 anymore

    //so only check for flooding
    if(isset($_SESSION['demologin']->starttime) && intval($_SESSION['demologin']->starttime) >= time()) {
//        error('you click a tad often', $CFG->wwwroot);
        print_error('flooding_error', 'block_demologin');
	exit;
    }
    if(empty($_SESSION['demologin'])){
        $_SESSION['demologin'] = new stdClass();
    }
    $_SESSION['demologin']->starttime = intval(time() + DEMO_REPLAY_PAUSE);

	
	
///////////////////////////////////////
// CHECK PARAM
///////////////////////////////////////
	
    //Course Shortname must be a String containing only Letters and/or Numbers
    $moodlecourse = required_param('course', PARAM_ALPHANUM);
    if( $moodlecourse == '') {
//        error("missing argument or invalid argument format");
        print_error('argument_error', 'block_demologin');
        exit;
    }
    
    //get course_id
    if((!$this_course = $DB->get_record('course', array('shortname' => $moodlecourse, 'category' => DEMO_CATEGORY))) || (count($this_course) < 1)) {
//        error('block_demologin: course not defined or not in allowed category', $CFG->wwwroot);
        print_error('course_error', 'block_demologin');
        exit;
    }
    $course_id = $this_course->id;
    
    
///////////////////////////////////////
// CHECK USER
///////////////////////////////////////
	
	//check if the user is already logged in and has rights to access the requested democourse
    if(isset($USER->username)){ 

        $enrolments = enrol_get_users_courses($USER->id); //Ok, since Moodle2 we can't use "moodle/course:view" to check
                                                                                                          //enrolments easily, so we get the users enrolments and check
                                                                                                          //if our course is in there
        if(isset($enrolments[$course_id])) {
            if($_SESSION['demologin']->starttime < time()) {
                unset($_SESSION['demologin']->starttime);
            }

            redirect($CFG->wwwroot.'/course/view.php?id='.$course_id);
            exit;
        }
    }
	//elsewise he will be logged in as a demouser, regardless of current logins


///////////////////////////////////////////
// COLLECT STATUS
///////////////////////////////////////////
	
    $status = block_demologin_util::get_status();
    $status++;

//sanity check
    if($status < 0 || $status > DEMO_MAX_USERCOUNT) {
            $status = 0;
    }

///////////////////////////////////////////
// USER/ACCOUNT MANAGEMENT
///////////////////////////////////////////	
    
	$username = DEMO_USERNAME.$status;
	$password = DEMO_PASSWORD.rand(1,10000);
	
    //if current username already exists delete the user
    if(block_demologin_util::user_exists($username)) {
		
        //but only, if it is not currently in use
		if(block_demologin_util::user_in_use($username)) {
			print_error('error_user_still_active', 'block_demologin');
			exit;
		}
		block_demologin_util::delete_user($username);
    }
    
    //Create new user
    if(!$DB->record_exists('user', array('username' => $username))) { //sicherstellen, dass der alte benutzer nicht mehr existiert
		
        $newuser = block_demologin_util::create_user($username, $password);
		block_demologin_util::set_status($status);
    }
    
    
///////////////////////////////////////////
// LOGGING IN
///////////////////////////////////////////
	
    $user = authenticate_user_login($username, $password);
    if ($user) {

        // Let's get them all set up.
        $USER = $user;
        add_to_log(SITEID, 'user', 'login', "view.php?id=$USER->id&course=".SITEID, $USER->id, 0, $USER->id);
        
        
        update_user_login_times();
        set_moodle_cookie($USER->username);
        set_login_session_preferences();
        redirect($CFG->wwwroot.'/course/view.php?id='.$course_id);
    } 
	else {
		print_error('error_cannot_login_demouser', 'block_demologin');
		exit;
	}


//should never get here	
redirect($CFG->wwwroot);
exit;
	
?>