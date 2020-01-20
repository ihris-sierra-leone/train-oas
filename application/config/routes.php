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
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route_structure = array(

    'home'=>array(
        'index','requirement','contact','faq','registration_start','application_start','registration_start1',
        'registration_bachelor','recommendation','membership_start','member_form','member_start','old_member_sign_up'
    ),

    'dashboard' => array(
        'dashboard'
    ),

    'auth' => array(
        'login', 'logout', 'change_password', 'login_history', 'activate', 'deactivate',
        'forgot_password', 'reset_password'
    ),

    'admin' => array(
        'add_group','group_list','grant_access','create_user','user_list','manage_database','reset_pass',
        'del_user'
    ),

     'simsdata' => array(
       'department_list','programme_list','add_programme','add_center', 'register_venue','delete_venue',
       'centre_list','venue_list','edit_center_list','add_module', 'module_list','edit_module','delete_module','add_session', 'edit_session', 'session_list','delete_center','certification_list',
       'add_studylevel','edit_studylevel','studylevel_list','manage_subject','add_sec_subject',
        'delete_exam_sassion','delete_studylevel','add_certificate','delete_certificate',
        'add_department','delete_department','delete_programme'
    ),

    'setting' => array(
        'current_semester','application_deadline','payment_status'
    ),

    'exam' => array(
        'grade_book','exam_registration','module_results','timetable','membership_fee','publish',
        'transcript_report','board_report','get_board_report','cumulative_report','graduate_report','exam_results',
        'exam_register','registered_exam_list','select_exam','delete_selection','transcript_list',
        'popup_student_transcript','search','exam_fee','pay_code','edit_score','remove_course',
        'is_examination_pay','is_annual_paid','audit_trail', 'popup_student_exam_letter','transcript_remark',
        'popup_student_remark','trancript_subjects','popup_transcript_subjects','delete_course',
        'corporate_candidates', 'course_results', 'update_student_score', 'delete_information',
        'student_exam_registered'

    ),

    'member' => array(
        'register','import','billing','suggestion_box','security', 'add_cooperate','member_list',
        'add_fellow_member','add_fellow_member','add_member','member_registration_form',
        'member_import','myprofile','profile','change_stata','cooperate_member','fellow_member',
        'delete_member','delete_fellow','annual_billing','exam_billing','set_programme','change_programme',
        'renotify','register_existing_member','exam_existing_member','import_examination_payments','candidate_programme',
        'change_member_programme', 'member_change_status','change_member_status_action','search_member_form',
        'import_new_member','member_fee_statement','exam_registration_list'

    ),

    'timetable' => array(
        'register_event','register_time','register_calender','exam_list','attendance_list','packaging_list'
    ),

    'applicant'=> array(
        'applicant_dashboard','applicant_basic','applicant_contact','applicant_profile',
        'applicant_next_kin','applicant_payment','is_applicant_pay','applicant_education','applicant_attachment',
        'applicant_choose_programme','applicant_submission','applicant_experience','applicant_referee','applicant_sponsor',
        'applicant_activate'
    ),

    'panel' => array(
        'applicant_list','popup_applicant_info','manage_criteria','short_listed','programme_list_panel','programme_setting_panel',
        'change_status','short_listed','run_eligibility','run_eligibility_active','collection','applicant_selection',
        'run_selection','run_selection_active','selection_criteria','programme_setting_selection','annualSubFee','examFee',
        'fee_setup','fee_list', 'statement','payment_retrieve','add_payments','annual_report','exam_report','view_recorded_data','sent_emails_list'
    ),


    'report'=> array(
        'print_application','print_student_exam_letter','print_member_transcript','print_student_remark',
        'print_transcript_subjects'
    )
);




foreach ($route_structure as $controller => $functions) {
    foreach ($functions as $key => $func) {
        $route[$func] = $controller . "/" . $func;
        $route[$func . '/(:any)'] = $controller . "/" . $func . '/$1';
        $route[$func . '/(:any)/(:any)'] = $controller . "/" . $func . '/$1/$2';
        $route[$func . '/(:any)/(:any)/(:any)'] = $controller . "/" . $func . '/$1/$2/$3';
    }
}
