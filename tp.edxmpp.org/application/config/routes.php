<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//導向正確位置
$route['test'] = 'test/index';
$route['test2'] = 'test2/index';
$route['test/(:any)'] = 'test/index/$1';
$route['student'] = 'student/student/index';
$route['student/(:any)'] = 'student/student/$1';
$route['help_center'] = 'student/help_center/index';
$route['modify_member_information'] = 'student/modify_member_information/index';
$route['modify_member_information/(:any)'] = 'student/modify_member_information/$1';
$route['modify_member_information/emailPass/(:any)'] = 'student/modify_member_information/emailPass/$1';
$route['become_teacher'] = 'student/become_teacher';
$route['become_teacher/(:any)'] = 'student/become_teacher/$1';
$route['become_teacher/(:any)/(:any)'] = 'student/become_teacher/$1/$2';
$route['Course_introduction/(:any)/(:any)'] = 'student/Course_introduction/index/$1/$2';
$route['Course_introduction/(:any)'] = 'student/Course_introduction/$1';
$route['Teacher_sales/(:any)/(:any)'] = 'student/Teacher_sales/index/$1/$2';
$route['Teacher_sales/(:any)'] = 'student/Teacher_sales/$1';
$route['shopping_cart'] = 'student/shopping_cart';
$route['live_courses'] = 'teacher/live_courses';
$route['live_courses/(:any)'] = 'teacher/live_courses/$1';
$route['live_courses/edit_courses/(:any)'] = 'teacher/live_courses/edit_courses/$1';
$route['course_management'] = 'teacher/course_management';
$route['course_management/(:any)'] = 'teacher/course_management/$1';
$route['course_management/(:any)/(:any)'] = 'teacher/course_management/$1/$2';
$route['shopping_cart/addShopping/(:any)/(:any)/(:any)'] = 'student/shopping_cart/addShopping/$1/$2/$3';
$route['shopping_cart/addShopping/(:any)/(:any)'] = 'student/shopping_cart/addShopping/$1/$2';
$route['shopping_cart/deleteShopping/(:any)'] = 'student/shopping_cart/deleteShopping/$1';
$route['shopping_cart/shoppingBuyClass'] = 'student/shopping_cart/shoppingBuyClass';
$route['shopping_cart/ecpayCheck'] = 'student/shopping_cart/ecpayCheck';
$route['buy_record'] = 'student/buy_record/index';
$route['buy_record/(:any)'] = 'student/buy_record/$1';
$route['my_course/(:any)'] = 'student/my_course/index/$1';
$route['my_course_fun/(:any)'] = 'student/my_course/$1';
$route['add_film_course'] = 'teacher/film_course/index';
$route['edit_film_course/(:any)'] = 'teacher/film_course/index/$1';
$route['film_course'] = 'student/film_course/index';
$route['film_course/(:any)'] = 'teacher/film_course/$1';
$route['film_courses/(:any)/(:any)'] = 'student/film_course/$1/$2';
$route['film_courses/(:any)'] = 'student/film_course/index/$1';
$route['filmCourses/(:any)'] = 'student/film_course/$1';
$route['film_courses/previewFilm/(:any)'] = 'student/film_course/previewFilm/$1';
$route['classStudent_information/(:any)'] = 'teacher/classStudent_information/index/$1';
$route['classStudent_information_fun/(:any)'] = 'teacher/classStudent_information/$1';
$route['classStudent_information_fun/(:any)/(:any)'] = 'teacher/classStudent_information/$1/$2';
$route['my_learn_process/(:any)'] = 'student/my_learn_process/index/$1';
$route['my_learn_process-getComment'] = 'student/my_learn_process/getComment';
$route['addComment'] = 'student/my_learn_process/addComment';
$route['addCommentFilm'] = 'student/my_learn_process/addCommentFilm';
$route['MLP_deleteComment'] = 'student/my_learn_process/deleteComment';
$route['MLP_changeComment'] = 'student/my_learn_process/changeComment';
$route['my_course_evaluation/(:any)/(:any)'] = 'teacher/my_course_evaluation/index/$1/$2';
$route['dashboard/(:any)'] = 'student/dashboard/$1';
$route['dashboard/(:any)/(:any)'] = 'student/dashboard/index/$1/$2';
$route['teacher_page/(:any)/(:any)'] = 'teacher/teacher_page/index/$1/$2';
$route['teacher_page/index/(:any)'] = 'teacher/teacher_page/index/$1';
$route['live_room/(:any)'] = 'student/live_room/index/$1'; //l_id
$route['fundraising_course/(:any)'] = 'student/fundraising_course/index/$1';
$route['fundraising_course_fun/(:any)'] = 'student/fundraising_course/$1';
$route['fundraisingCourse_management/(:any)'] = 'teacher/FundraisingCourse_management/$1';
$route['fundraisingCourse_management/(:any)/(:any)'] = 'teacher/FundraisingCourse_management/$1/$2';
$route['edit_fundraisingCourse/(:any)'] = 'teacher/FundraisingCourse_management/index/$1';
$route['add_fundraising_course'] = 'teacher/FundraisingCourse_management/addFundraisingCourse_view';
$route['stored_value'] = 'student/stored_value/index';
$route['stored_value/(:any)'] = 'student/stored_value/$1';
$route['course_favorite'] = 'student/course_favorite/index';
$route['course_favorite/(:any)'] = 'student/course_favorite/$1';
$route['pay_page'] = 'teacher/pay_page/index';
$route['pay_page_fun/(:any)'] = 'teacher/pay_page/$1';
$route['daily_tasks'] = 'student/daily_tasks/index';
$route['daily_tasks_fun/(:any)'] = 'student/daily_tasks/$1';
$route['ad_page'] = 'student/ad_page/index';

//admin start
$route['TPManager'] = 'admin/signIn/index';
$route['signIn/signIn'] = 'admin/signIn/signIn';
$route['admin_management'] = 'admin/admin_management/index';
$route['admin_management/(:any)'] = 'admin/admin_management/$1';
$route['course_options'] = 'admin/Course_options/index';
$route['course_options/(:any)'] = 'admin/Course_options/$1';
$route['notice_record'] = 'admin/notice_record/index';
$route['notice_record/(:any)'] = 'admin/notice_record/$1';
$route['notice_record/(:any)/(:any)'] = 'admin/notice_record/$1/$2';
$route['currency_conversion'] = 'admin/currency_conversion/index';
$route['currency_conversion/(:any)'] = 'admin/currency_conversion/$1';
$route['currency_conversion/(:any)/(:any)'] = 'admin/currency_conversion/$1/$2';
$route['member_management'] = 'admin/member_management/index';
$route['member_management/(:any)'] = 'admin/member_management/$1';
$route['member_management/(:any)/(:any)'] = 'admin/member_management/$1/$2';
$route['member_management_detail/(:any)'] = 'admin/member_management_detail/index/$1';
$route['member_management_detail_fun/(:any)/(:any)'] = 'admin/member_management_detail/$1/$2';
$route['teacherCheck_detail/(:any)'] = 'admin/teacherCheck_detail/index/$1';
$route['teacherCheck/(:any)'] = 'admin/teacherCheck/$1';
$route['teams_liveManagement'] = 'admin/teams_liveManagement';
$route['teams_liveManagement/(:any)'] = 'admin/teams_liveManagement/$1';
$route['teams_liveManagement/(:any)/(:any)'] = 'admin/teams_liveManagement/$1/$2';
$route['teams_account_issues'] = 'admin/teams_account_issues/index';
$route['teams_account_issues/(:any)'] = 'admin/teams_account_issues/$1';
$route['teams_account_issues/(:any)/(:any)'] = 'admin/teams_account_issues/$1/$2';
$route['course_label'] = 'admin/course_label/index';
$route['course_label/(:any)'] = 'admin/course_label/$1';
$route['course_label/(:any)/(:any)'] = 'admin/course_label/$1/$2';
$route['report_management'] = 'admin/report_management/index';
$route['report_management/(:any)'] = 'admin/report_management/$1';
$route['message_management'] = 'admin/message_management/index';
$route['message_management/(:any)'] = 'admin/message_management/$1';
$route['hand_outDiamonds'] = 'admin/hand_outDiamonds/index';
$route['hand_outDiamonds/(:any)'] = 'admin/hand_outDiamonds/$1';
$route['hand_outGolds'] = 'admin/hand_outGolds/index';
$route['hand_outGolds/(:any)'] = 'admin/hand_outGolds/$1';
$route['payment_history'] = 'admin/payment_history/index';
$route['payment_history/(:any)'] = 'admin/payment_history/$1';
$route['teacher_salary_management'] = 'admin/teacher_salary_management/index';
$route['teacher_salary_management/(:any)'] = 'admin/teacher_salary_management/$1';
//admin end


//正確錯誤位置
$route['test/index'] = 'error';
$route['student/student'] = 'error';
$route['student/student/(:any)'] = 'error';
$route['student/help_center'] = 'error';
$route['student/modify_member_information'] = 'error';
$route['student/modify_member_information/(:any)'] = 'error';
$route['student/modify_member_information/emailPass/(:any)'] = 'error';
$route['student/become_teacher'] = 'error';
$route['student/become_teacher/(:any)'] = 'error';
$route['student/become_teacher/edit_education_background_image/(:any)'] = 'error';
$route['student/Course_introduction/index/(:any)/(:any)'] = 'error';
$route['student/Course_introduction/(:any)'] = 'error';
$route['student/Teacher_sales/index/(:any)/(:any)'] = 'error';
$route['student/shopping_cart'] = 'error';
$route['teacher/live_courses'] = 'error';
$route['teacher/live_courses/(:any)'] = 'error';
$route['teacher/course_management'] = 'error';
$route['teacher/course_management/(:any)'] = 'error';
$route['teacher/course_management/(:any)/(:any)'] = 'error';
$route['student/shopping_cart/addShopping/(:any)/(:any)/(:any)'] = 'error';
$route['student/shopping_cart/deleteShopping/(:any)'] = 'error';
$route['student/shopping_cart/shoppingBuyClass'] = 'error';
$route['student/buy_record/index'] = 'error';
$route['student/buy_record/(:any)'] = 'error';
$route['student/my_course/index'] = 'error';
$route['student/my_course/(:any)/(:any)'] = 'error';
$route['teacher/add_film_course'] = 'error';
$route['teacher/add_film_course/(:any)'] = 'error';
$route['student/film_course'] = 'error';
$route['student/film_course/(:any)'] = 'error';
$route['student/my_learn_process/index/(:any)'] = 'error';
$route['student/my_learn_process/getComment'] = 'error';
$route['student/my_learn_process/addComment'] = 'error';
$route['student/my_learn_process/deleteComment'] = 'error';
$route['student/my_learn_process/changeComment'] = 'error';
$route['teacher/my_course_evaluation/index/(:any)'] = 'error';
$route['student/dashboard/(:any)'] = 'error';
$route['student/dashboard/index/(:any)/(:any)'] = 'error';
$route['teacher/teacher_page/(:any)'] = 'error';
$route['student/live_room/(:any)'] = 'error';
$route['student/fundraising_course/(:any)'] = 'error';
$route['teacher/FundraisingCourse_management/(:any)'] = 'error';
$route['teacher/FundraisingCourse_management/(:any)/(:any)'] = 'error';
$route['student/course_favorite'] = 'error';
$route['student/course_favorite/(:any)'] = 'error';
$route['student/stored_value/index'] = 'error';
$route['teacher/pay_page/index'] = 'error';
$route['teacher/pay_page/(:any)'] = 'error';
$route['student/stored_value/(:any)'] = 'error';
$route['student/daily_tasks/index'] = 'error';
$route['student/daily_tasks/(:any)'] = 'error';
$route['student/ad_page/(:any)'] = 'error';
$route['teacher/classStudent_information_fun/(:any)'] = 'error';
$route['teacher/classStudent_information_fun/(:any)/(:any)'] = 'error';

//admin start
$route['admin/signIn/index'] = 'error';
$route['admin/signIn/signIn'] = 'error';
$route['admin/admin_management'] = 'error';
$route['admin/admin_management/index'] = 'error';
$route['admin/admin_management/(:any)'] = 'error';
$route['admin/course_options/index'] = 'error';
$route['admin/course_options/(:any)'] = 'error';
$route['admin/notice_record/index'] = 'error';
$route['admin/notice_record/(:any)'] = 'error';
$route['admin/notice_record/(:any)/(:any)'] = 'error';
$route['admin/currency_conversion/index'] = 'error';
$route['admin/currency_conversion/(:any)'] = 'error';
$route['admin/currency_conversion/(:any)/(:any)'] = 'error';
$route['admin/TeacherCheck_detail/index/(:any)'] = 'error';
$route['admin/teacherCheck/(:any)'] = 'error';
$route['admin/member_management'] = 'error';
$route['admin/member_management/(:any)'] = 'error';
$route['admin/member_management/(:any)/(:any)'] = 'error';
$route['admin/member_management_detail/index/(:any)'] = 'error';
$route['admin/teams_liveManagement'] = 'error';
$route['admin/teams_liveManagement/(:any)'] = 'error';
$route['admin/teams_liveManagement/(:any)/(:any)'] = 'error';
$route['admin/teams_account_issues'] = 'error';
$route['admin/teams_account_issues/(:any)'] = 'error';
$route['admin/teams_account_issues/(:any)/(:any)'] = 'error';
$route['admin/course_label'] = 'error';
$route['admin/course_label/(:any)'] = 'error';
$route['admin/course_label/(:any)/(:any)'] = 'error';
$route['admin/report_management'] = 'error';
$route['admin/report_management/(:any)'] = 'error';
$route['admin/message_management'] = 'error';
$route['admin/message_management/(:any)'] = 'error';
$route['https://ajcode.tk/teaching_platform_dev/resource/image/share/admin.jpg'] = 'error';
$route['admin/hand_outDiamonds'] = 'error';
$route['admin/hand_outDiamonds/(:any)'] = 'error';
$route['admin/payment_history'] = 'error';
$route['admin/payment_history/(:any)'] = 'error';
$route['admin/teacher_salary_management/(:any)'] = 'error';
//admin end


$route['en'] = 'home/index/en';
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
