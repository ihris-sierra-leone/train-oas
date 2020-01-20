<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/16/17
 * Time: 10:45 AM
 */
class Panel extends CI_Controller
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


    function sent_emails_list()
    {
        if(isset($_GET['resend_selected']) ) {

            if (isset($_GET['txtSelect'])) {
                $selected_mails = $_GET['txtSelect'];

                foreach ($selected_mails as $key => $sent_id) {
                    execInBackground('response send_notification ' . $sent_id);
                }

                $this->session->set_flashdata("message", show_alert("Selected sent mails Successfully resent", 'info'));
                redirect(site_url('sent_emails_list/'),'refresh');
            }else{
                $this->session->set_flashdata("message", show_alert("Please select at least one sent mail", 'danger'));
                redirect(site_url('sent_emails_list/'),'refresh');

            }
        }

        $current_user = current_user();
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Resend Email');
        $this->data['bscrum'][] = array('link' => 'send_emails_list/', 'title' => 'Sent Emails');
        $where = " WHERE 1=1 ";

        if (isset($_GET['from']) && $_GET['from'] != '') {
            $frm = $_GET['from'];
            $from = format_date($frm, true);
            $where .= " AND DATE(time_stamp) >='" . $from . "' ";

        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $t = $_GET['to'];
            $to = format_date($t, true);
            $where .= " AND DATE(time_stamp) <='" . $to . "' ";
        }
        //echo $where; exit();
        $config["base_url"] = site_url('send_emails_list/');
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $this->data['sent_emails_list'] = $this->db->query("select * from notify_tmp  ".$where. " order by id DESC")->result();
        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'panel/sent_emails_list';
        $this->load->view('template', $this->data);
    }
    function applicant_list()
    {
         //echo "hapa nafika";exit;

        $current_user = current_user();

        if(isset($_GET['delete_selected']) )
        {
            if(isset($_GET['txtSelect']))
            {
                $selected_aplicants=$_GET['txtSelect'];

                foreach ($selected_aplicants as $key=>$aplicant_id)
                {
                    $this->db->where('id', $aplicant_id);
                    $this->db->delete('application');

                    $this->db->where('applicant_id', $aplicant_id);
                    $this->db->delete('users');
                }

                $this->session->set_flashdata("message", show_alert("Selected Applicants Successfully Deleted", 'info'));
                redirect(site_url('applicant_list/'),'refresh');
            }else
            {
                $this->session->set_flashdata("message", show_alert("Please select at least one applicant", 'danger'));
                redirect(site_url('applicant_list/'),'refresh');
            }

        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'APPLICANT', 'applicant_list')) {
            $this->session->set_flashdata("message", show_alert("APPLICANT_LIST :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Applicant');
        $this->data['bscrum'][] = array('link' => 'applicant_list/', 'title' => 'Applicant List');

        $this->load->library('pagination');

        $where = ' WHERE 1=1 ';
        $count_where=0;

        if (isset($_GET['type']) && $_GET['type'] != '') {

            $where .= " AND application_type='" . $_GET['type'] . "' ";
            $count_where +=1;
        }

        if (isset($_GET['status']) && $_GET['status'] != '') {
            $where .= " AND submitted='" . $_GET['status'] . "' ";
            $count_where +=1;
        }

        if (isset($_GET['from']) && $_GET['from'] != '') {
            $frm = $_GET['from'];
            $from = format_date($frm, true);
            $where .= " AND DATE(createdon) >='" . $from . "' ";
            //echo $where; exit();
            $count_where +=1;
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $t = $_GET['to'];
            $to = format_date($t, true);
            $where .= " AND DATE(createdon) <='" . $to . "' ";
            $count_where +=1;
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $where .= " AND AYear='" . $_GET['year'] . "' ";
            $count_where +=1;
        }


        if (isset($_GET['name']) && $_GET['name'] != '') {
            $where .= " AND FirstName LIKE '%" . $_GET['name'] . "%' OR MiddleName LIKE '%" . $_GET['name'] . "%' OR LastName LIKE '%" . $_GET['name'] . "%' ";
            $count_where +=1;
        }

        if($count_where==0)
        {
            $where .= " AND AYear='" . date('Y') . "' ";
        }
        $where .= " AND  status = 0  ";


        $sql = " SELECT * FROM application  $where ";
        $sql2 = " SELECT count(id) as counter FROM application  $where ";  //echo $sql; exit();

        $config["base_url"] = site_url('applicant_list/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

       // $this->data['applicant_list'] = $this->db->query($sql . " ORDER BY submitted DESC, FirstName ASC LIMIT $page," . $config["per_page"])->result();

        $this->data['applicant_list'] = $this->db->query($sql . " ORDER BY submitted DESC, FirstName ASC ")->result();


        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'panel/applicant_list';
        $this->load->view('template', $this->data);
    }

    function change_status()
    {
        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->form_validation->set_rules('applicant_id', 'Applicant ID', 'required');

        if ($this->form_validation->run() == true) {
            $array_data = array(
                'status' => $this->input->post('status'),
                'submitted' => $this->input->post('status')
            );

            $applicant_id = $this->input->post('applicant_id');

            $register = $this->applicant_model->update_applicant($array_data, array('id' => $applicant_id));

            echo '<div style="color: #0000cc">Status updated..</div>';
        } else {
            echo $this->input->post('status') . '|' . $this->input->post('applicant_id') . ' The Status field is required';
        }


    }

    function popup_applicant_info($id)
    {
        $id = decode_id($id);
        $APPLICANT = $this->applicant_model->get_applicant($id);
        if ($APPLICANT) {
            $this->data['APPLICANT'] = $APPLICANT;
            $next_kin = $this->applicant_model->get_nextkin_info($APPLICANT->id)->result();
            if (count($next_kin) > 0) {
                $this->data['next_kin'] = $next_kin;
            }

            $referee = $this->applicant_model->get_applicant_referee($APPLICANT->id)->result();
            if (count($referee) > 0) {
                $this->data['academic_referee'] = $referee;
            }

            $sponsor = $this->applicant_model->get_applicant_sponsor($APPLICANT->id)->row();
            if ($sponsor) {
                $this->data['sponsor_info'] = $sponsor;
            }

            $employer = $this->applicant_model->get_applicant_employer($APPLICANT->id)->row();
            if ($employer) {
                $this->data['employer_info'] = $employer;
            }

            $this->data['education_bg'] = $this->applicant_model->get_education_bg(null, $APPLICANT->id);
            $this->data['attachment_list'] = $this->applicant_model->get_attachment($APPLICANT->id);
            $mychoice = $this->applicant_model->get_programme_choice($APPLICANT->id);
            if ($mychoice) {
                $this->data['mycoice'] = $mychoice;
            }
            if (isset($_GET) && isset($_GET['status'])) {
                $this->data['change_status'] = 1;
            }
            $this->load->view('panel/popup_applicant_info', $this->data);
        } else {
            echo show_alert('This request did not pass our security checks.', 'info');
        }

    }


    function manage_criteria()
    {
        $current_user = current_user();

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'CRITERIA', 'manage_criteria')) {
            $this->session->set_flashdata("message", show_alert("SELECTION_CRITERIA :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Criteria');
        $this->data['bscrum'][] = array('link' => 'manage_criteria/', 'title' => 'Selection Criteria');

        $this->data['programme_list'] = $this->common_model->get_programme()->result();
        $this->data['active_menu'] = 'manage_criteria';
        $this->data['content'] = 'panel/manage_criteria';
        $this->load->view('template', $this->data);
    }

    function programme_setting_panel($code = null)
    {
        $current_user = current_user();
        $this->data['CODE'] = $code;
        $ENTRY = (isset($_GET) && isset($_GET['entry']) ? $_GET['entry'] : null);
        if (!is_null($code)) {
            $row_year = $this->common_model->get_academic_year(null, 1, 1)->row();
            if ($row_year) {

                if (isset($_GET['sub_id']) && isset($_GET['cat']) && isset($_GET['row_id'])) {
                    //remove one row in the setting configurations
                    $get_row = $this->db->where('id', $_GET['row_id'])->get('application_criteria_setting')->row();
                    if ($get_row) {
                        $column = 'form6_inclusive';
                        if ($_GET['cat'] == 'IV') {
                            $column = 'form4_inclusive';
                        }

                        $column_data = json_decode($get_row->{$column}, true);
                        unset($column_data[$_GET['sub_id']]);

                        $this->db->update('application_criteria_setting', array($column => json_encode($column_data)), array('id' => $_GET['row_id']));
                        $this->session->set_flashdata('message', show_alert('Setting Data updated successfully', 'info'));
                        redirect(remove_query_string(array('sub_id', 'cat', 'row_id')), 'refresh');

                    }

                }

                //if(isset($_GET) && isset($_GET['type'])) {
                $this->form_validation->set_rules('save_data', 'Save Data', 'required');
                $this->form_validation->set_rules('form4_pass', '', 'integer');

                if ($this->form_validation->run() == true) {

                    $form4_data = array();
                    $form6_data = array();
                    $subject_form4 = $this->input->post('subjectIV');
                    $grade_form4 = $this->input->post('gradeIV');

                    $subject_form6 = $this->input->post('subjectVI');
                    $grade_form6 = $this->input->post('gradeVI');

                    $subjectIVOR = $this->input->post('subjectIVOR');
                    $gradeIVOR = $this->input->post('gradeIVOR');
                    $gradeIVORNO = $this->input->post('gradeIVORNO');

                    $subjectVIOR = $this->input->post('subjectVIOR');
                    $gradeVIOR = $this->input->post('gradeVIOR');
                    $gradeVIORNO = $this->input->post('gradeVIORNO');


                    if ($subject_form4) {
                        foreach ($subject_form4 as $k => $v) {
                            if ($grade_form4[$k] <> '' && $v <> '') {
                                $form4_data[$v] = $grade_form4[$k];
                            }
                        }
                    }

                    if ($subject_form6) {
                        foreach ($subject_form6 as $k => $v) {
                            if ($grade_form6[$k] <> '' && $v <> '') {
                                $form6_data[$v] = $grade_form6[$k];
                            }
                        }
                    }


                    $array_data = array(
                        'AYear' => $row_year->AYear,
                        'entry' => $this->input->post('entry'),
                        'form4_inclusive' => json_encode($form4_data),
                        'form4_exclusive' => ($this->input->post('subject4_exclusive') ? implode(',', $this->input->post('subject4_exclusive')) : ''),
                        'form4_pass' => trim($this->input->post('form4_pass')),
                        'form6_inclusive' => json_encode($form6_data),
                        'form6_exclusive' => ($this->input->post('subject6_exclusive') ? implode(',', $this->input->post('subject6_exclusive')) : ''),
                        'min_point' => ($this->input->post('min_point') ? $this->input->post('min_point') : ''),
                        'form6_pass' => trim($this->input->post('form6_pass')),
                        'gpa_pass' => trim($this->input->post('gpa_pass')),
                        'ProgrammeCode' => $code,
                        'createdby' => $current_user->id,
                        'createdon' => date('Y-m-d H:i:s'),
                        'form4_or_subject' => ($gradeIVOR ? json_encode(array($gradeIVOR . '|' . $gradeIVORNO => $subjectIVOR)) : ''),
                        'form6_or_subject' => ($gradeVIOR ? json_encode(array($gradeVIOR . '|' . $gradeVIORNO => $subjectVIOR)) : ''),
                    );


                    $conf = $this->setting_model->programme_setting_criteria($array_data);
                    if ($conf) {
                        $this->session->set_flashdata('message', show_alert('Selection Criteria Saved successfully', 'success'));
                        redirect(current_full_url(), 'refresh');
                    } else {
                        $this->data['message'] = show_alert('Fail to save Criteria Information', 'info');
                    }
                }

                $setting_info = $this->setting_model->get_selection_criteria($code, $row_year->AYear, $ENTRY);

                if ($setting_info) {
                    $this->data['setting_info'] = $setting_info;
                }
                $this->data['content_view'] = "panel/set_criteria_rules";
                //}else{
                //  $this->data['content_view'] = "panel/set_criteria_category";
                //}
            } else {
                $this->data['message'] = show_alert('No active Year created, No Configuration allowed', 'info');
            }
            $this->data['programme_info'] = $this->db->where('Code', $code)->get('programme')->row();
            $this->data['subject_list'] = $this->setting_model->get_sec_subject(null, 1)->result();

            $this->load->view("panel/programme_setting_panel", $this->data);

        } else {
            echo "Please use link in the left side to start setting";
        }
    }

    function short_listed()
    {
        $current_user = current_user();
        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'APPLICANT', 'short_listed')) {
            $this->session->set_flashdata("message", show_alert("APPLICANT_SHORT_LISTED :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Applicant');
        $this->data['bscrum'][] = array('link' => 'short_listed/', 'title' => 'Applicant Short Listed');


        $this->data['programme_list'] = $this->common_model->get_programme()->result();
        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'panel/short_listed';
        $this->load->view('template', $this->data);

    }

    function run_eligibility()
    {
        $programme_list = $this->common_model->get_programme()->result();
        foreach ($programme_list as $key => $value) {
            $new = $this->db->insert('run_eligibility', array('ProgrammeCode' => $value->Code));
            $last_id = $this->db->insert_id();
            if ($last_id) {
                execInBackground('response run_eligibility ' . $last_id);
            }
        }

        $this->session->set_flashdata('message', show_alert('This process will take some time to finish. Please Wait ...', 'info'));
        redirect('short_listed', 'refresh');


    }

    function run_eligibility_active()
    {
        $check = $this->db->get("run_eligibility")->row();
        if (!$check) {
            $this->session->set_flashdata('message', show_alert('Run Eligibility completed, Please Continue with other activities ', 'success'));

        } else {
            $this->session->set_flashdata('message', show_alert('This process will take some time to finish. Please still wait ...', 'info'));
        }
        echo '1';
    }

    function collection()
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Fee');
        $this->data['bscrum'][] = array('link' => 'collection/', 'title' => 'Application Fee');

        $this->load->library('pagination');

        $where = ' WHERE 1=1 ';

        $count_where=0;
        if (isset($_GET['key']) && $_GET['key'] != '') {
//            $where .= " AND a.FirstName ='" . $_GET['key'] . "' ";
            $where .= " AND  (a.FirstName LIKE '%" . $_GET['key'] . "%' OR a.MiddleName LIKE '%" . $_GET['key'] . "%' OR a.LastName LIKE '%" . $_GET['key'] . "%')";
            $count_where+=1;
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $where .= " AND YEAR(p.createdon) ='" . $_GET['year'] . "' ";
            $count_where+=1;

        }

        if (isset($_GET['from']) && $_GET['from'] != '') {
            $where .= " AND DATE(p.createdon) >='" . format_date($_GET['from']) . "' ";
            $count_where+=1;
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $where .= " AND DATE(p.createdon) <='" . format_date($_GET['to']) . "' ";
            $count_where+=1;
        }

        if($count_where==0)
        {
            $where .= " AND YEAR(p.createdon) ='" . date('Y') . "' ";
        }
        $sql = " SELECT p.*,a.FirstName,a.MiddleName,a.LastName FROM application_payment as p LEFT JOIN application as a ON (p.applicant_id=a.id)  $where ";
        $sql2 = " SELECT count(p.id) as counter FROM application_payment as p LEFT JOIN application as a ON (p.applicant_id=a.id)  $where ";

       // $this->data['total_amount'] = $this->db->query(" select SUM(amount) as amount FROM application_payment as p LEFT JOIN application as a ON (p.applicant_id=a.id) $where ")->row()->amount;
        //$this->data['total_charges'] = $this->db->query(" select SUM(charges) as charge FROM application_payment as p LEFT JOIN application as a ON (p.applicant_id=a.id) $where ")->row()->charge;

        $config["base_url"] = site_url('collection/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['collection_list'] = $this->db->query($sql . " ORDER BY p.createdon DESC ")->result();


        $this->data['active_menu'] = 'collection';
        $this->data['content'] = 'panel/collection';
        $this->load->view('template', $this->data);
    }


    function annualSubFee()
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Fee');
        $this->data['bscrum'][] = array('link' => 'annualSubFee/', 'title' => 'Annual Subscription Fee');

        $this->load->library('pagination');
        $academic_year = $this->common_model->get_academic_year()->row()->AYear;

        $where = " WHERE 1=1 AND pay_method is null ";
        $where_count=0;

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $where .= " AND YEAR(k.createdon) ='" . $_GET['year'] . "' ";
            $where_count +=1;
        }

        if (isset($_GET['key']) && $_GET['key'] != '') {
//            $where .= " AND app.FirstName ='" . $_GET['key'] . "' ";
            $where .= " AND  (app.FirstName LIKE '%" . $_GET['key'] . "%' OR app.MiddleName LIKE '%" . $_GET['key'] . "%' OR app.LastName LIKE '%" . $_GET['key'] . "%')";
            $where_count +=1;
        }

        if (isset($_GET['from']) && $_GET['from'] != '') {
            $where .= " AND DATE(k.createdon) >='" . format_date($_GET['from']) . "' ";
            $where_count +=1;
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $where .= " AND DATE(k.createdon) <='" . format_date($_GET['to']) . "' ";
            $where_count +=1;
        }

        if($where_count==0)
        {
            $where .= " AND YEAR(k.createdon) ='" . date('Y') . "' ";
        }

        $sql = "SELECT k.*,app.FirstName, app.MiddleName,app.LastName,app.user_id FROM annual_fees as k LEFT JOIN application as app ON (k.user_id=app.user_id) $where ";
            $sql2 = "SELECT COUNT(k.user_id) as counter FROM annual_fees as k LEFT JOIN application as app ON (k.user_id=app.user_id) $where ";

       // $this->data['total_amount'] = $this->db->query(" select SUM(amount) as amount FROM annual_fees as k LEFT JOIN application as app ON (k.user_id=app.user_id) $where ")->row()->amount;
        //$this->data['total_charges'] = $this->db->query(" select SUM(charges) as charge FROM annual_fees as k LEFT JOIN application as app ON (k.user_id=app.user_id) $where ")->row()->charge;

        $config["base_url"] = site_url('annualSubFee/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['annual_fee_list'] = $this->db->query($sql . " ORDER BY k.createdon DESC ")->result();


        $this->data['active_menu'] = 'collection';
        $this->data['content'] = 'panel/annual_fee';
        $this->load->view('template', $this->data);
    }

    function examFee()
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Fee');
        $this->data['bscrum'][] = array('link' => 'examFee/', 'title' => 'Examination(s) Fee');

        $this->load->library('pagination');

        $where = " WHERE 1=1 AND pay_method<>'Cash'";
        $count_where=0;

        if (isset($_GET['key']) && $_GET['key'] != '') {
//            $where .= " AND p.first_name LIKE '%" . $_GET['key'] . "%' ";
//            $where .= " AND  (p.first_name LIKE '%" . $_GET['key'] . "%' OR p.other_names LIKE '%" . $_GET['key'] . "%' OR p.surname LIKE '%" . $_GET['key'] . "%')";
            $where .= " AND  (app.FirstName LIKE '%" . $_GET['key'] . "%' OR app.MiddleName LIKE '%" . $_GET['key'] . "%' OR app.LastName LIKE '%" . $_GET['key'] . "%')";
            $count_where+=1;
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $where .= " AND YEAR(a.createdon) ='" . $_GET['year'] . "' ";
            $count_where+=1;
        }


        if (isset($_GET['from']) && $_GET['from'] != '') {
            $where .= " AND DATE(a.createdon) >='" . format_date($_GET['from']) . "' ";
            $count_where+=1;
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $where .= " AND DATE(a.createdon) <='" . format_date($_GET['to']) . "' ";
            $count_where+=1;
        }

        if($count_where==0)
        {
            $where .= " AND YEAR(a.createdon) ='" . date('Y') . "' ";
        }

        $sql = "SELECT a.*, app.FirstName, app.MiddleName, app.LastName, app.user_id FROM examinations_payment as a LEFT JOIN application as app ON (a.user_id=app.user_id) $where ";


//        $sql = " SELECT a.*,p.first_name,p.other_names,p.surname FROM students as p LEFT JOIN examinations_payment as a ON (p.user_id=a.user_id)  $where ";

        $sql2 = " SELECT count(a.id) as counter FROM examinations_payment as a INNER JOIN application as app ON (app.user_id=a.user_id)  $where ";
       // $this->data['total_amount'] = $this->db->query(" select SUM(amount) as amount FROM examinations_payment as a LEFT JOIN application as app ON (app.user_id=a.user_id) $where ")->row()->amount;
       // $this->data['total_charges'] = $this->db->query(" select SUM(charges) as charge FROM examinations_payment as a LEFT JOIN application as app ON (app.user_id=a.user_id) $where ")->row()->charge;
         $config["base_url"] = site_url('examFee/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['exam_fee_list'] = $this->db->query($sql . " ORDER BY a.createdon DESC LIMIT $page," . $config["per_page"])->result();


        $this->data['active_menu'] = 'collection';
        $this->data['content'] = 'panel/examFee';
        $this->load->view('template', $this->data);
    }

    function applicant_selection()
    {
        $current_user = current_user();
        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'APPLICANT', 'applicant_selection')) {
            $this->session->set_flashdata("message", show_alert("APPLICANT_SELECTION :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Applicant');
        $this->data['bscrum'][] = array('link' => 'applicant_selection/', 'title' => 'Applicant Selection');


        $this->data['programme_list'] = $this->common_model->get_programme()->result();
        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'panel/applicant_selection';
        $this->load->view('template', $this->data);

    }

    function run_selection()
    {
        $programme_list = $this->common_model->get_programme()->result();
        foreach ($programme_list as $key => $value) {
            $new = $this->db->insert('run_selection', array('ProgrammeCode' => $value->Code));
            $last_id = $this->db->insert_id();
            if ($last_id) {
                execInBackground('response run_selection ');
            }
        }

        $this->session->set_flashdata('message', show_alert('This process will take some time to finish. Please Wait ...', 'info'));
        redirect('applicant_selection', 'refresh');


    }

    function run_selection_active()
    {
        $check = $this->db->get("run_selection")->row();
        if (!$check) {
            $this->session->set_flashdata('message', show_alert('Run Eligibility completed, Please Continue with other activities ', 'success'));

        } else {
            $this->session->set_flashdata('message', show_alert('This process will take some time to finish. Please still wait ...', 'info'));
        }
        echo '1';
    }


    function fee_setup($id = null)
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Venues');
        $this->data['bscrum'][] = array('link' => 'fee_setup/', 'title' => 'Fee Setting');

        $this->form_validation->set_rules('member_type', 'Member Type', 'required');
        $this->form_validation->set_rules('programme', 'Programme', 'required');
        $this->form_validation->set_rules('fee_type', 'Fee Type', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required');


        if ($this->form_validation->run() == true) {
            $member_type = trim($this->input->post('member_type'));
            $programmeid = trim($this->input->post('programme'));
            $fee_type = trim($this->input->post('fee_type'));

            if ($fee_type == 1) {
                $chek = $this->db->query("select annual_amount from exam_fee where member_category='$member_type' and programmeID='$programmeid' ")->result();
                if (!$chek) {
                    $array_data = array(
                        'member_category' => trim($this->input->post('member_type')),
                        'programmeID' => trim($this->input->post('programme')),
                        'amount' => trim($this->input->post('amount'))

                    );
                    $this->db->insert('exam_fee', $array_data);
                    $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
                    redirect('fee_list', 'refresh');

                } else {

                    $array_data = array(
                        'member_category' => trim($this->input->post('member_type')),
                        'programmeID' => trim($this->input->post('programme')),
                        'amount' => trim($this->input->post('amount'))

                    );

                    $this->db->where('member_category', $member_type);
                    $this->db->where('programmeID', $programmeid);
                    $update = $this->db->update('exam_fee', $array_data);
                    if ($update) {
                        $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
                        redirect('fee_list', 'refresh');
                    }
                }

            } else {
                $chek = $this->db->query("select amount from exam_fee where member_category='$member_type' and programmeID='$programmeid' ")->row()->amount;

                if (!$chek) {
                    $array_data = array(
                        'member_category' => trim($this->input->post('member_type')),
                        'programmeID' => trim($this->input->post('programme')),
                        'annual_amount' => trim($this->input->post('amount'))

                    );
                    $this->db->insert('exam_fee', $array_data);
                    $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
                    redirect('fee_list', 'refresh');

                } else {

                    $array_data = array(
                        'member_category' => trim($this->input->post('member_type')),
                        'programmeID' => trim($this->input->post('programme')),
                        'annual_amount' => trim($this->input->post('amount'))

                    );

                    $this->db->where('member_category', $member_type);
                    $this->db->where('programmeID', $programmeid);
                    $update = $this->db->update('exam_fee', $array_data);
                    if ($update) {
                        $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
                        redirect('fee_list', 'refresh');
                    }
                }
            }

        }
        $this->data['active_menu'] = 'feesetup';
        $this->data['content'] = 'panel/fee_setup';
        $this->load->view('template', $this->data);
    }


    function fee_list($id = null)
    {

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!is_null($id)) {
            $this->common_model->delete_venue($id);
            $this->session->set_flashdata('message', show_alert('One record deleted', 'success'));
        }

        $this->load->library('pagination');
        $where = " WHERE 1=1";

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  name LIKE '%" . $_GET['key'] . "%'";
        }


        $sql = " SELECT * FROM exam_fee  $where ";
        $sql2 = " SELECT count(id) as counter FROM exam_fee  $where ";

        $config["base_url"] = site_url('fee_list/');
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 15;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);

        if (!is_null($id)) {
            $page = 0;
        }

        $this->data['pagination_links'] = $this->pagination->create_links();
        $this->data['venue_list'] = $this->db->query($sql . " ORDER BY programmeID ASC LIMIT $page," . $config["per_page"])->result();
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Fees');
        $this->data['bscrum'][] = array('link' => 'Fee_list/', 'title' => 'Fee List');
        $this->data['active_menu'] = 'feesetup';
        $this->data['content'] = 'panel/fee_list';
        $this->load->view('template', $this->data);


    }


    function statement()
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Fee');
        $this->data['bscrum'][] = array('link' => 'fee_statement/', 'title' => 'Fee Statement');

        $this->load->library('pagination');
        $academic_year = $this->common_model->get_academic_year()->row()->AYear;

        $where = " WHERE 1=1 AND LENGTH(f.receipt) > 6";

        $where_count=0;

        if (isset($_GET['key']) && $_GET['key'] != '') {
//            $where .= " AND app.FirstName ='" .$_GET['key'] . "' ";
            $where .= " AND  (app.FirstName LIKE '%" . $_GET['key'] . "%' OR app.MiddleName LIKE '%" . $_GET['key'] . "%' OR app.LastName LIKE '%" . $_GET['key'] . "%')";
            $where_count+=1;
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $where .= " AND YEAR(f.createdon) ='" . $_GET['year'] . "' ";
            $where_count+=1;
        }

        if (isset($_GET['from']) && $_GET['from'] != '') {
            $where .= " AND DATE(f.createdon) >='" . format_date($_GET['from']) . "' ";
            $where_count+=1;
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $where .= " AND DATE(f.createdon) <='" . format_date($_GET['to']) . "' ";
            $where_count+=1;
        }


        if( $where_count==0)
        {
            $where .= " AND YEAR(f.createdon) ='" . date('Y') . "' ";

        }
//        $sql = "SELECT app.FirstName, app.MiddleName, app.LastName, amount, f.* FROM fee_statement as f LEFT JOIN application as app
//  on (f.user_id=app.user_id) $where  ";

        $sql = "SELECT f.*, app.FirstName, app.MiddleName, app.LastName FROM fee_statement as f LEFT JOIN application as app on (f.user_id=app.user_id) $where  ";


//        echo $sql;


        $sql2 = "select count(f.user_id) as counter from fee_statement as f left join application as app on (f.user_id=app.user_id) $where ";

        //$this->data['total_amount'] = $this->db->query(" select SUM(amount) as amount FROM fee_statement as f left join application as app on (f.user_id=app.user_id) $where ")->row()->amount;

        $config["base_url"] = site_url('statement/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['statement_fee_list'] = $this->db->query($sql . " ORDER BY f.createdon DESC ")->result();


        $this->data['active_menu'] = 'collection';
        $this->data['content'] = 'panel/fee_statement';
        $this->load->view('template', $this->data);
    }

    public function annual_report()
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Fee');
        $this->data['bscrum'][] = array('link' => 'annual_report/', 'title' => 'Annual Subscription Fee');

        $this->load->library('pagination');
        $academic_year = $this->common_model->get_academic_year()->row()->AYear;

        $where = " WHERE 1=1 AND pay_method='Cash' ";

        $where_count=0;

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $where .= " AND YEAR(k.createdon) ='" . $_GET['year'] . "' ";
            $where_count+=1;
        }

        if (isset($_GET['key']) && $_GET['key'] != '') {
//            $where .= " AND app.FirstName ='" . $_GET['key'] . "' ";
            $where .= " AND  (app.FirstName LIKE '%" . $_GET['key'] . "%' OR app.MiddleName LIKE '%" . $_GET['key'] . "%' OR app.LastName LIKE '%" . $_GET['key'] . "%')";
            $where_count+=1;
        }

        if (isset($_GET['from']) && $_GET['from'] != '') {
            $where .= " AND DATE(k.createdon) >='" . format_date($_GET['from']) . "' ";
            $where_count+=1;
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $where .= " AND DATE(k.createdon) <='" . format_date($_GET['to']) . "' ";
            $where_count+=1;
        }

        if($where_count==0)
        {
            $where .= " AND YEAR(k.createdon) ='" . date('Y') . "' ";

        }

        $sql = "SELECT k.*, app.FirstName, app.MiddleName, app.LastName FROM annual_fees as k LEFT JOIN application as app ON (k.user_id=app.user_id) $where ";
        $sql2 = "SELECT COUNT(k.user_id) as counter FROM annual_fees as k LEFT JOIN application as app ON (k.user_id=app.user_id) $where ";

        //$this->data['total_amount'] = $this->db->query(" select SUM(amount) as amount FROM annual_fees as k left join application as app on (k.user_id=app.user_id) $where ")->row()->amount;
       // $this->data['total_charges'] = $this->db->query(" select SUM(charges) as charges FROM annual_fees as k left join application as app on (k.user_id=app.user_id) $where ")->row()->charges;

        $config["base_url"] = site_url('annual_report/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['annual_fee_list'] = $this->db->query($sql . " ORDER BY k.createdon DESC ")->result();


        $this->data['active_menu'] = 'reports';
        $this->data['content'] = 'panel/annual_report';
        $this->load->view('template', $this->data);
    }

    public function exam_report()
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Fee');
        $this->data['bscrum'][] = array('link' => 'exam_report/', 'title' => 'Examination(s) Fee');

        $this->load->library('pagination');

        $where = " WHERE 1=1 AND pay_method='Cash' ";
        $where_count=0;
        if (isset($_GET['key']) && $_GET['key'] != '') {
//            $where .= " AND p.first_name LIKE '%" . $_GET['key'] . "%' ";
            $where .= " AND  (app.FirstName LIKE '%" . $_GET['key'] . "%' OR app.MiddleName LIKE '%" . $_GET['key'] . "%' OR app.LastName LIKE '%" . $_GET['key'] . "%')";
            $where_count +=1;
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $where .= " AND YEAR(a.createdon) ='" . $_GET['year'] . "' ";
            $where_count +=1;
        }


        if (isset($_GET['from']) && $_GET['from'] != '') {
            $where .= " AND DATE(a.createdon) >='" . format_date($_GET['from']) . "' ";
            $where_count +=1;
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $where .= " AND DATE(a.createdon) <='" . format_date($_GET['to']) . "' ";
            $where_count +=1;
        }
        if( $where_count ==0)
        {
            $where .= " AND YEAR(a.createdon) ='" . date("Y") . "' ";
        }


//        $sql = " SELECT a.*,p.first_name,p.other_names,p.surname,p.registration_number FROM examinations_payment as a LEFT JOIN students as p  ON (p.user_id=a.user_id)  $where ";

        $sql = " SELECT a.*, app.FirstName, app.MiddleName, app.LastName, app.user_id FROM examinations_payment as a LEFT JOIN application as app ON (a.user_id=app.user_id) $where  ";

        $sql2 = " SELECT count(a.id) as counter FROM examinations_payment as a  LEFT JOIN application as app  ON (app.user_id=a.user_id)  $where ";


        //$this->data['total_amount'] = $this->db->query(" select SUM(amount) as amount FROM examinations_payment as a left join application as app on (a.user_id=app.user_id) $where ")->row()->amount;
        //$this->data['total_charges'] = $this->db->query(" select SUM(charges) as charges FROM examinations_payment as a left join application as app on (a.user_id=app.user_id) $where ")->row()->charges;

//        $sql2 = " SELECT count(a.id) as counter FROM examinations_payment as a  LEFT JOIN students as p  ON (p.user_id=a.user_id)  $where ";

        $config["base_url"] = site_url('exam_report/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['exam_fee_list'] = $this->db->query($sql . " ORDER BY a.createdon DESC ")->result();


        $this->data['active_menu'] = 'reports';
        $this->data['content'] = 'panel/exam_report.php';
        $this->load->view('template', $this->data);

    }


    public function view_recorded_data()
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Fee');
        $this->data['bscrum'][] = array('link' => 'view_recorded_data/', 'title' => 'View record');

        $this->load->library('pagination');
        $academic_year = $this->common_model->get_academic_year()->row()->AYear;

        $where = " WHERE 1=1 AND pay_method='Cash' ";

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND app.FirstName LIKE '%" . $_GET['key'] . "%' OR app.MiddleName LIKE '%" . $_GET['key'] . "%' OR app.LastName LIKE '%" . $_GET['key'] . "%' ";
        }

        $sql = "SELECT k.*, app.FirstName, app.MiddleName, app.LastName, app.id FROM temp_annual_fees as k INNER JOIN application as app ON (k.user_id=app.user_id) $where ";
        $sql2 = "SELECT COUNT(app.id) as counter FROM temp_annual_fees as k INNER JOIN application as app ON (k.user_id=app.user_id) $where ";

        $config["base_url"] = site_url('view_recorded_data/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['annual_fee_list'] = $this->db->query($sql . " ORDER BY k.createdon DESC LIMIT $page," . $config["per_page"])->result();


        $this->data['active_menu'] = 'reports';
        $this->data['content'] = 'member/view_recorded_data';
        $this->load->view('template', $this->data);
    }
    



}
