<?php  

defined('MOODLE_INTERNAL') || die;

$settings->add(new admin_setting_heading('block_demologin_header', '', get_string('configure_description', 'block_demologin')));

$settings->add(new admin_setting_configtextarea('block_demologin_description', get_string('configure_democourse_description_title', 'block_demologin'),
                   get_string('configure_democourse_description', 'block_demologin'), ''));

$settings->add(new admin_settings_coursecat_select('block_demologin_democategory', get_string('configure_democourse_category_title', 'block_demologin'),
                   get_string('configure_democourse_category', 'block_demologin'), 0));

$settings->add(new admin_setting_configtext('block_demologin_max_demo_users', get_string('configure_democourse_max_demo_users_title', 'block_demologin'),
                   get_string('configure_democourse_max_demo_users', 'block_demologin'), 100, PARAM_INT));
		

$roles = $DB->get_records('role');
$params = array();
foreach($roles as $role) {
	$params[$role->id] = $role->name;
}
		
$settings->add(new admin_setting_configselect('block_demologin_demouser_role_course',
        get_string('configure_democourse_demouser_role_course_title', 'block_demologin'),
        get_string('configure_democourse_demouser_role_course', 'block_demologin'),
		0,
		$params));
        
$settings->add(new admin_setting_configselect('block_demologin_demouser_role_system',
        get_string('configure_democourse_demouser_role_system_title', 'block_demologin'),
        get_string('configure_democourse_demouser_role_system', 'block_demologin'),
		0,
		$params));
				   
?>
