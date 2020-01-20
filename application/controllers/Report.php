<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/16/17
 * Time: 5:21 AM
 */
class Report extends CI_Controller
{

    private $MODULE_ID = '';
    private $GROUP_ID = '';

    function __construct()
    {
        parent::__construct();


        $this->data['CURRENT_USER'] = current_user();

        $this->form_validation->set_error_delimiters('<div class="required">', '</div>');

        $this->data['title'] = 'Administrator';

        $tmp_group = get_user_group();
        $this->GROUP_ID = $this->data['GROUP_ID'] = $tmp_group->id;
        $this->MODULE_ID = $this->data['MODULE_ID'] = $tmp_group->module_id;
    }

    function print_application($id)
    {
        $id = decode_id($id);
        //echo $id;exit();
        $APPLICANT = $this->applicant_model->get_applicant($id);
        if ($APPLICANT) {
            $next_kin1 = $this->applicant_model->get_nextkin_info($APPLICANT->id)->result();
            if (count($next_kin1) > 0) {
                $next_kin = $next_kin1;
            }

            $referee = $this->applicant_model->get_applicant_referee($APPLICANT->id)->result();
            if (count($referee) > 0) {
                $academic_referee = $referee;
            }

            $sponsor = $this->applicant_model->get_applicant_sponsor($APPLICANT->id)->row();
            if ($sponsor) {
                $sponsor_info = $sponsor;
            }

            $employer = $this->applicant_model->get_applicant_employer($APPLICANT->id)->row();
            if ($employer) {
                $employer_info = $employer;
            }

            $education_bg = $this->applicant_model->get_education_bg(null, $APPLICANT->id);
            $attachment_list = $this->applicant_model->get_attachment($APPLICANT->id);
            $mychoice1 = $this->applicant_model->get_programme_choice($APPLICANT->id);
            if ($mychoice1) {
                $mycoice = $mychoice1;
            }

            include_once 'report/print_application.php';
        } else {
            $this->session->set_flashdata('message', show_alert('This request did not pass our security checks.', 'info'));
            $current_user_group = get_user_group();
            if ($current_user_group->id == 4) {
                redirect(site_url('applicant_dashboard'), 'refresh');
            } else {
                redirect(site_url('dashboard'), 'refresh');
            }
        }
    }


    function print_student_exam_letter($id)
    {
        $id = decode_id($id);
        //echo $id;exit();
        $APPLICANT = $this->applicant_model->get_member_transcript($id);
        if ($APPLICANT) {
            $this->data['APPLICANT'] = $APPLICANT;
            include_once 'report/print_student_exam_letter.php';
        }
    }

    function print_member_transcript($id)
    {
        $id = decode_id($id);
        //echo $id;exit();
        $APPLICANT = $this->applicant_model->get_member_transcript($id);
        //var_dump($APPLICANT);exit();
        if ($APPLICANT) {
            $this->data['APPLICANT'] = $APPLICANT;
            $user_id = $this->data['APPLICANT']->user_id;
            $programme = $this->common_model->get_student($user_id)->row()->programme_id;
            if ($programme != 0) {
                $reg_no = $this->common_model->get_student($user_id)->row()->registration_number;
                $results = $this->exam_model->get_results($reg_no)->result();
                $this->data['results'] = $results;

                include_once 'report/print_member_transcript.php';
            } else {
                $this->session->set_flashdata('message', show_alert('Set programme for this candidate to print transcript.', 'info'));

            }
        }


    }


    function print_student_remark($id)
    {
        $id = decode_id($id);
        //echo $id;exit();
        $APPLICANT = $this->applicant_model->get_member_transcript($id);
        //var_dump($APPLICANT);exit();
        if ($APPLICANT) {
            $this->data['APPLICANT'] = $APPLICANT;
            $user_id = $this->data['APPLICANT']->user_id;
            $programme = $this->common_model->get_student($user_id)->row()->programme_id;
            if ($programme != 0) {
                $reg_no = $this->common_model->get_student($user_id)->row()->registration_number;
                $results = $this->exam_model->get_results($reg_no)->result();
                $this->data['results'] = $results;

                include_once 'report/print_student_remark.php';
            } else {
                $this->session->set_flashdata('message', show_alert('Set programme for this candidate to print transcript.', 'info'));

            }
        }


    }


    function print_transcript_subjects($id)
    {
        $id = decode_id($id);
        //echo $id;exit();
        $APPLICANT = $this->applicant_model->get_member_transcript($id);
        //var_dump($APPLICANT);exit();
        if ($APPLICANT) {
            $this->data['APPLICANT'] = $APPLICANT;
            $user_id = $this->data['APPLICANT']->user_id;
            $programme = $this->common_model->get_student($user_id)->row()->programme_id;
            if ($programme != 0) {
                $reg_no = $this->common_model->get_student($user_id)->row()->registration_number;
                $results = $this->exam_model->get_results($reg_no)->result();
                $this->data['results'] = $results;

                include_once 'report/print_transcript_subjects.php';
            } else {
                $this->session->set_flashdata('message', show_alert('Set programme for this candidate to print transcript.', 'info'));

            }
        }
    }

    function applicant_byProgramme()
    {
        $programme = (isset($_GET) && isset($_GET['programme'])) ? $_GET['programme'] : null;
        $application_type = (isset($_GET) && isset($_GET['type'])) ? $_GET['type'] : null;
        $applicant_list = $this->db->query("SELECT ap.*,pc.choice,pc.status as eligible,pc.comment,pc.point FROM application as ap  INNER JOIN application_elegibility as pc ON (ap.id=pc.applicant_id) WHERE ap.application_type='$application_type'
                      AND pc.ProgrammeCode='$programme' ORDER BY pc.status DESC,pc.point DESC  ")->result();
        $row_year = $this->common_model->get_academic_year(null, 1, 1)->row();
        $ayear = $row_year->AYear;
        include 'report/applicant_byProgramme.php';


    }

    function print_transcript($id)
    {
        $id = decode_id($id);
        $APPLICANT = $this->applicant_model->get_applicant($id);
        if ($APPLICANT) {
            $next_kin1 = $this->applicant_model->get_nextkin_info($APPLICANT->id)->result();
            if (count($next_kin1) > 0) {
                $next_kin = $next_kin1;
            }

            $referee = $this->applicant_model->get_applicant_referee($APPLICANT->id)->result();
            if (count($referee) > 0) {
                $academic_referee = $referee;
            }

            $sponsor = $this->applicant_model->get_applicant_sponsor($APPLICANT->id)->row();
            if ($sponsor) {
                $sponsor_info = $sponsor;
            }

            $employer = $this->applicant_model->get_applicant_employer($APPLICANT->id)->row();
            if ($employer) {
                $employer_info = $employer;
            }

            $education_bg = $this->applicant_model->get_education_bg(null, $APPLICANT->id);
            $attachment_list = $this->applicant_model->get_attachment($APPLICANT->id);
            $mychoice1 = $this->applicant_model->get_programme_choice($APPLICANT->id);
            if ($mychoice1) {
                $mycoice = $mychoice1;
            }


            include_once 'report/print_transcript.php';
        } else {
            $this->session->set_flashdata('message', show_alert('This request did not pass our security checks.', 'info'));
            $current_user_group = get_user_group();
            if ($current_user_group->id == 4) {
                redirect(site_url('applicant_dashboard'), 'refresh');
            } else {
                redirect(site_url('dashboard'), 'refresh');
            }
        }
    }

    function export_applicant()
    {
        $ayear = $this->common_model->get_academic_year(null, 1, 1)->row()->AYear;
        $key = $date = $from = $to = $year = $yearl = $application_type = $stats = $frm = null;

        if (isset($_GET['type']) && $_GET['type'] != '') {
            $key = $_GET['type'];
        }
        if (isset($_GET['date']) && $_GET['date'] != '') {
            $d = $_GET['date'];
            $date = format_date($d, true);
        }
        if (isset($_GET['from']) && $_GET['from'] != '') {
            $f = $_GET['from'];
            $from = format_date($f, true);
        }

        if (isset($_GET['status']) && $_GET['status'] != '') {
            $status = $_GET['status'];
            //$from = format_date($f, true);
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $t = $_GET['to'];
            $to = format_date($t, true);
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $year = $_GET['year'];
        }

        if (!is_null($key) && !is_null($year)) {
            $application_type = $key;
            $yearl = $year;
//            $frm = '';
//            $to = '';
//            $date = '';
            include_once 'report/applicant_single_sheet.php';
            exit;

        } elseif (!is_null($from) && !is_null($to)) {
            $frm = $from;
            $to = $to;
//            $application_type = '';
//            $yearl = '';
//            $date = '';
            include_once 'report/applicant_single_sheet.php';
            exit;

        } elseif (!is_null($status)) {

//            echo "nipo humu"; exit;
//            $frm = '';
            $status = $status;
//            $to = '';
//            $application_type = '';
//            $yearl = '';
//            $date = '';
            include_once 'report/applicant_single_sheet.php';
            exit;

        } elseif (!is_null($from) && !is_null($to) && !is_null($key)) {
            $frm = $from;
            $to = $to;
            $application_type = $key;
//            $yearl = '';
//            $date = '';
            include_once 'report/applicant_single_sheet.php';
            exit;


        } elseif (!is_null($key)) {
            $application_type = $key;
//            $frm = '';
//            $to = '';
//            $yearl = '';
//            $date = '';
            include_once 'report/applicant_single_sheet.php';
            exit;

        } elseif (!is_null($date)) {
            $date = $date;
//            $application_type = '';
//            $frm = '';
//            $to = '';
//            $yearl = '';
            include_once 'report/applicant_single_sheet.php';
            exit;
        } else {
            //all application Type - 3 worksheet in excel
            //For time being force to select Application Type First
            $this->session->set_flashdata('message', show_alert('Please select type of Application before click export button', 'info'));
            redirect('applicant_list', 'refresh');
        }


    }

    function export_member()
    {
        $ayear = $this->common_model->get_academic_year(null, 1, 1)->row()->AYear;

        $key = $from = $to = $year = $cooperate_member = $coorat = $yea = $type = null;

        if (isset($_GET['type']) && $_GET['type'] != '') {
            $key = $_GET['type'];
        }
        if (isset($_GET['year']) && $_GET['year'] != '') {
            $year = $_GET['year'];
        }
        if (isset($_GET['cooperate']) && $_GET['cooperate'] != '') {
            $cooperate_member = $_GET['cooperate'];
        }

        if (isset($_GET['from']) && $_GET['from'] != '') {
            $from = $_GET['from'];
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $to = $_GET['to'];
        }
        //echo $cooperate_member ;exit();
        if ((!is_null($key) && $key <> '') || (!is_null($from) && $from <> '') || (!is_null($cooperate_member) && $cooperate_member <> '')
        ) {
            //single type of application - one Worksheet
            if (!is_null($key) && !is_null($year) && !is_null($cooperate_member)) {
                $application_type = $key;
                //$yea = $year;
                $from = $from;
                $to = $to;
                $coorat = $cooperate_member;
            } elseif (!is_null($key) && !is_null($from)) {
                $application_type = $key;
                //$yea = $year;
                $from = $from;
                $to = $to;
            } elseif (!is_null($key) && !is_null($cooperate_member)) {
                $application_type = $key;
                $coorat = $cooperate_member;
                $yea = '';
            } elseif (!is_null($from) && !is_null($cooperate_member)) {
                $coorat = $cooperate_member;
                //$yea = $year;
                $application_type = '';
                $from = $from;
                $to = $to;
            } elseif (!is_null($key)) {

                $application_type = $key;

            } elseif (!is_null($from)) {
                $application_type = '';
                $from = $from;
                $to = $to;
                include_once 'report/member_by_year_sheet.php';
            } elseif (!is_null($cooperate_member)) {
                $application_type = '';
                $coorat = '';
                $coorat = $cooperate_member;
            }
//            exit;
            include_once 'report/member_single_sheet.php';
            exit();
        } elseif (is_null($type) && $type == '') {
            // no selection
            $application_type = "Member";
            include_once 'report/member_single_sheet.php';
            exit;
        } else {
            //all application Type - 3 worksheet in excel
            //For time being force to select Application Type First
//            $this->session->set_flashdata('message',show_alert('No related information to your search','info'));
//            redirect('member_list','refresh');
            $application_type = "Member";
            include_once 'report/member_single_sheet.php';
        }


    }

    function attendance()
    {
        $ayear = $this->common_model->get_academic_year(null, 1, 1)->row()->AYear;
        $key = $type = $entry = null;
        if (isset($_GET) && isset($_GET['key']) && isset($_GET['type']) && isset($_GET['entry'])) {
            $key = $_GET['key'];
            $type = $_GET['type'];
            $entry = $_GET['entry'];
        }

        $this->form_validation->set_rules('programmecode', 'Programme', 'required');

        if ($this->form_validation->run() == true) {
            $programmecode = stripslashes($_GET['Code']);

            echo $programmecode;
            exit();
        }

        if (!is_null($type) && $type <> '') {
            //single type of application - one Worksheet
            $application_type = application_type($type);
            include_once 'report/member_single_sheet.php';
            exit;
        } elseif (is_null($type) && $type == '') {
            $flag = $_GET['flag'];
            if ($flag == 0) {
                $application_type = "ATTENDANCE_LIST";
                include_once 'report/attendance_students.php';
                exit;
            } else {
                $application_type = "ATTENDANCE_LIST";
                include_once 'report/attendance.php';
                exit;
            }
        } else {
            //all application Type - 3 worksheet in excel
            //For time being force to select Application Type First
            $this->session->set_flashdata('message', show_alert('No related information to your search', 'info'));
            redirect('member_list', 'refresh');
        }


    }

    function export_board_report()
    {
        $ayear = $this->common_model->get_academic_year(null, 1, 1)->row()->AYear;
        $key = $type = $entry = null;
        if (isset($_GET) && isset($_GET['key']) && isset($_GET['type']) && isset($_GET['entry'])) {
            $key = $_GET['key'];
            $type = $_GET['type'];
            $entry = $_GET['entry'];
        }

        $programmecode = stripslashes($_GET['Code']);

        if (!is_null($type) && $type <> '') {
            //single type of application - one Worksheet
            $application_type = application_type($type);
            include_once 'report/member_single_sheet.php';
            exit;
        } elseif (is_null($type) && $type == '' && $programmecode != '') {
            // no selection
            $application_type = "BOARD REPORT";
            include_once 'report/board_report.php';
            exit;
        } else {
            //all application Type - 3 worksheet in excel
            //For time being force to select Application Type First
            $this->session->set_flashdata('message', show_alert('No related information to your search', 'info'));
            redirect('member_list', 'refresh');
        }


    }


    function package()
    {
        $ayear = $this->common_model->get_academic_year(null, 1, 1)->row()->AYear;
        $key = $type = $entry = null;
        if (isset($_GET) && isset($_GET['key']) && isset($_GET['type']) && isset($_GET['entry'])) {
            $key = $_GET['key'];
            $type = $_GET['type'];
            $entry = $_GET['entry'];
        }


        if (!is_null($type) && $type <> '') {
            //single type of application - one Worksheet
            $application_type = application_type($type);
            include_once 'report/member_single_sheet.php';
            exit;
        } elseif (is_null($type) && $type == '') {
            // no selection
            $application_type = "PACKAGING LIST";
            include_once 'report/package.php';
            exit;
        } else {
            //all application Type - 3 worksheet in excel
            //For time being force to select Application Type First
            $this->session->set_flashdata('message', show_alert('No related information to your search', 'info'));
            redirect('member_list', 'refresh');
        }


    }


    function export_application_payment()
    {
        $ayear = $this->common_model->get_academic_year(null, 1, 1)->row()->AYear;

        $key = $from = $to = $year = null;

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $key = $_GET['key'];
        }
        if (isset($_GET['from']) && $_GET['from'] != '') {
            $from = $_GET['from'];
        }
        if (isset($_GET['to']) && $_GET['to'] != '') {
            $to = $_GET['to'];
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $year = $_GET['year'];
        }

        if ((!is_null($from) && $from <> '') && (!is_null($to) && $to <> '')) {
            $from_date = $from;
            $to_date = $to;
            //$key=$key;
            include_once 'report/application_payment_sheet.php';
            exit();
        } elseif (!is_null($year)) {
            $year = $year;
            include_once 'report/application_payment_sheet.php';
            exit();
        } else {
            include_once 'report/application_payment_sheet.php';
        }


    }


    function export_examination_payment()
    {
        $ayear = $this->common_model->get_academic_year(null, 1, 1)->row()->AYear;

        $key = $from = $to = $year = null;

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $key = $_GET['key'];
        }
        if (isset($_GET['from']) && $_GET['from'] != '') {
            $from = $_GET['from'];
        }
        if (isset($_GET['to']) && $_GET['to'] != '') {
            $to = $_GET['to'];
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $year = $_GET['year'];
        }

        //echo $cooperate_member ;exit();
        if ((!is_null($from) && $from <> '') && (!is_null($to) && $to <> '')) {
            $from_date = $from;
            $to_date = $to;
            //$key=$key;
            include_once 'report/examinations_payment_sheet.php';
            exit();
        } elseif (!is_null($year)) {
            $year = $year;
            include_once 'report/examinations_payment_sheet.php';
            exit();
        } else {
            include_once 'report/examinations_payment_sheet.php';
        }
    }


    function export_exam_report()
    {
        $ayear = $this->common_model->get_academic_year(null, 1, 1)->row()->AYear;

        $key = $from = $to = $year = null;

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $key = $_GET['key'];
        }
        if (isset($_GET['from']) && $_GET['from'] != '') {
            $from = $_GET['from'];
        }
        if (isset($_GET['to']) && $_GET['to'] != '') {
            $to = $_GET['to'];
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $year = $_GET['year'];
        }

        //echo $cooperate_member ;exit();
        if ((!is_null($from) && $from <> '') && (!is_null($to) && $to <> '')) {
            $from_date = $from;
            $to_date = $to;
            //$key=$key;
            include_once 'report/examinations_report_sheet.php';
            exit();
        } elseif (!is_null($year)) {
            $year = $year;
            include_once 'report/examinations_report_sheet.php';
            exit();
        } else {
            include_once 'report/examinations_report_sheet.php';
        }
    }

    function export_annual_payment()
    {
        $ayear = $this->common_model->get_academic_year(null, 1, 1)->row()->AYear;

        $key = $from = $to = $year = null;

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $key = $_GET['key'];
        }
        if (isset($_GET['from']) && $_GET['from'] != '') {
            $from = $_GET['from'];
        }
        if (isset($_GET['to']) && $_GET['to'] != '') {
            $to = $_GET['to'];
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $year = $_GET['year'];
        }

        if ((!is_null($from) && $from <> '') && (!is_null($to) && $to <> '')) {
            $from_date = $from;
            $to_date = $to;
            include_once 'report/annual_payment_sheet.php';
            exit();
        } elseif (!is_null($year)) {
            $year = $year;
            include_once 'report/annual_payment_sheet.php';
            exit();
        } else {
            include_once 'report/annual_payment_sheet.php';
        }
    }


    function export_annual_report()
    {
        $ayear = $this->common_model->get_academic_year(null, 1, 1)->row()->AYear;

        $key = $from = $to = $year = null;

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $key = $_GET['key'];
        }
        if (isset($_GET['from']) && $_GET['from'] != '') {
            $from = $_GET['from'];
        }
        if (isset($_GET['to']) && $_GET['to'] != '') {
            $to = $_GET['to'];
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $year = $_GET['year'];
        }

        if ((!is_null($from) && $from <> '') && (!is_null($to) && $to <> '')) {
            $from_date = $from;
            $to_date = $to;
            include_once 'report/annual_report_sheet.php';
            exit();
        } elseif (!is_null($year)) {
            $year = $year;
            include_once 'report/annual_report_sheet.php';
            exit();
        } else {
            include_once 'report/annual_report_sheet.php';
        }
    }


    function export_fee_statement()
    {
        $ayear = $this->common_model->get_academic_year(null, 1, 1)->row()->AYear;

        $key = $from = $to = $year = null;

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $key = $_GET['key'];
        }
        if (isset($_GET['from']) && $_GET['from'] != '') {
            $from = $_GET['from'];
        }
        if (isset($_GET['to']) && $_GET['to'] != '') {
            $to = $_GET['to'];
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $year = $_GET['year'];
        }

        //echo $cooperate_member ;exit();
        if ((!is_null($from) && $from <> '') && (!is_null($to) && $to <> '')) {
            $from_date = $from;
            $to_date = $to;
            //$key=$key;
            include_once 'report/fee_statement_sheet.php';
            exit();
        } elseif (!is_null($year)) {
            $year = $year;
            include_once 'report/fee_statement_sheet.php';
            exit();
        } else {
            include_once 'report/fee_statement_sheet.php';
        }
    }

    public function export_corporate_candidates()
    {
        $ayear = $this->common_model->get_academic_year(null, 1, 1)->row()->AYear;
        $corporate = null;
        if (isset($_GET['corporate']) && $_GET['corporate'] != '') {
            $key = $_GET['corporate'];
            if ((!is_null($key) && $key <> '')) {
                $corporate = $key;
                include_once 'report/export_corporate_candidates.php';
                exit();
            }
        }


    }


}
