<?php

$string['title'] = 'Demo Courses';
$string['pluginname'] = 'DemoLogin';
$string['configure_description'] = 'This Block allows you to configure a Coursecategory whose Courses will be accessible for everyone visiting the page. A bunch of demoaccounts will be created, whith each giving full student access to a course. Users will be temporarily assigned one of these accounts, which will last until its session expires. For this to work the courses shortnames must contain only alphanumeric characters and no whitespaces.';
$string['error_no_courses_in_category'] = 'No courses in the defined category.';
$string['error_enable_cookies'] = 'You have to enable Cookies in your Browsersettings.';
$string['error_user_still_active'] = 'Sorry, but there are currently no demousers available. Please try again later.';
$string['error_cannot_login_demouser'] = 'Sorry, but there seems to be a problem logging in the demouser. Please contact an administrator, if this problem persists.';

$string['configure_democourse_category_title'] = 'Course Category:';
$string['configure_democourse_category'] = 'Category containing the demo courses.';
$string['error_no_category_defined'] = 'No Category defined.';

$string['configure_democourse_description_title'] = 'Description:';
$string['configure_democourse_description'] = 'Description that will be displayed in the block, prior to the list of courses. May be empty.';

$string['configure_democourse_max_demo_users_title'] = 'max Demousers.';
$string['configure_democourse_max_demo_users'] = 'Number of demousers to create at most. If the number is hit, demousers will be recycled. Depends on your traffic. Keep it high enough that no active demouser will be recycled.';
$string['error_no_max_users_defined'] = 'Max number of demousers undefined.';

$string['configure_democourse_demouser_role_course_title'] = 'Course role for Demousers.';
$string['configure_democourse_demouser_role_course'] = 'The role Demousers shall be assigned in course contexts. <span style="font-weight:bold;color:red;">Caution: Only assign the Student role or another role with similar or less rights here</span>';
$string['error_no_demouser_role_course_defined'] = 'Demouser course role undefined.';

$string['configure_democourse_demouser_role_system_title'] = 'System role for Demousers.';
$string['configure_democourse_demouser_role_system'] = 'The role Demousers shall be assigned in the system context. <span style="font-weight:bold;color:red;">Caution: Only assign the Guest role or another role with similar or less rights here</span>';
$string['error_no_demouser_role_system_defined'] = 'Demouser system role undefined.';

$string['flooding_error'] = 'you click a tad often';
$string['argument_error'] = 'missing argument or invalid argument format';
$string['course_error'] = 'block_demologin: course not defined or not in allowed category';

