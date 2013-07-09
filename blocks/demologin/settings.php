<?php

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir. '/coursecatlib.php');

$settings->add(new admin_setting_heading('block_demologin_header', '', get_string('configure_description', 'block_demologin')));

$settings->add(new admin_setting_configtextarea('block_demologin_description', get_string('configure_democourse_description_title', 'block_demologin'),
                   get_string('configure_democourse_description', 'block_demologin'), ''));


$cats = coursecat::make_categories_list();
$choices = array(0 => '');
foreach ($cats as $key => $value) {
    $choices[$key] = str_repeat('&nbsp;', coursecat::get($key)->depth - 1). $value;
}

$settings->add(new admin_setting_configselect('block_demologin_democategory',
        get_string('configure_democourse_category_title', 'block_demologin'),
        get_string('configure_democourse_category', 'block_demologin'),
		0, $choices));

$settings->add(new admin_setting_configtext('block_demologin_max_demo_users', get_string('configure_democourse_max_demo_users_title', 'block_demologin'),
                   get_string('configure_democourse_max_demo_users', 'block_demologin'), 100, PARAM_INT));

$systemcontext = context_system::instance();
$roles = role_fix_names(get_all_roles(), $systemcontext, ROLENAME_ORIGINAL);

$params = array();
$params[0] = '';
foreach($roles as $role) {
	$params[$role->id] = $role->localname;
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
