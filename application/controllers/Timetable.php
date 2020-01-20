<?php

class Timetable extends CI_Controller
{
    private $MODULE_ID = '';
    private $GROUP_ID = '';

    function __construct()
    {
        parent::__construct();


        $this->data['CURRENT_USER'] = current_user();

        $this->form_validation->set_error_delimiters('<div class="required">', '</div>');

        $this->data['title'] = 'Timetable';

        $tmp_group = get_user_group();
        $this->GROUP_ID = $this->data['GROUP_ID'] = $tmp_group->id;
        $this->MODULE_ID = $this->data['MODULE_ID'] = $tmp_group->module_id;
    }


    function register_event($id=null)
    {
        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_TIMETABLE', 'register_event')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_TIMETABLE :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Timetable');
        $this->data['bscrum'][] = array('link' => 'register_event/', 'title' => 'Register Event');

        $this->form_validation->set_rules('course', 'Course', 'required');
        $this->form_validation->set_rules('date', 'date', 'required');
        $this->form_validation->set_rules('time', 'time', 'required');

        $this->load->model('timetable_model');
        if ($this->form_validation->run() == true) {
            $array_data = array(
                'coursecode' => trim($this->input->post('course')),
                'exam_date' => format_date(trim($this->input->post('date'))),
                'time' => trim($this->input->post('time')),
                'year' => $this->common_model->get_academic_year($name=null,$status=1,$limit=null)->row()->AYear,
                'semester' => $this->common_model->get_academic_year($name=null,$status=1,$limit=null)->row()->semester
            );

            $add = $this->timetable_model->exam_register($array_data,$id);
            if($add){
                $this->session->set_flashdata('message',show_alert('Information saved successfully','success'));
                redirect('exam_list','refresh');
            }else{
                $this->data['message'] = show_alert('Fail to save Information','info');
            }
        }

        if(!is_null($id)){
            $check = $this->timetable_model->get_exam_register($id)->row();
            if($check){
                $this->data['exam_info'] = $check;
            }
        }

        $this->data['course_list'] = $this->common_model->get_course()->result();
        $this->data['centre_list'] = $this->common_model->get_exam_center()->result();
        $this->data['active_menu'] = 'timetable';
        $this->data['content'] = 'timetable/register_event';
        $this->load->view('template', $this->data);
    }


    function  exam_list(){

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        $this->load->library('pagination');
        $where = " WHERE 1=1";

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  name LIKE '%" . $_GET['key'] . "%'";
        }

        $sql = " SELECT * FROM exam_register  $where ";
        $sql2 = " SELECT count(id) as counter FROM exam_register  $where ";

        $config["base_url"] = site_url('exam_list/');
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 20;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();
        $this->data['exam_list'] = $this->db->query($sql . " ORDER BY exam_date ASC LIMIT $page," . $config["per_page"])->result();


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Exams');
        $this->data['bscrum'][] = array('link' => 'exam_list/', 'title' => 'Examination Schedule');
        $this->load->model('timetable_model');
        $this->data['exam_list'] = $this->timetable_model->get_exam_register()->result();
        $this->data['active_menu'] = 'timetable';
        $this->data['content'] = 'timetable/exam_list';
        $this->load->view('template', $this->data);

    }

    function register_time()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_TIMETABLE', 'register_time')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_TIMETABLE :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Timetable');
        $this->data['bscrum'][] = array('link' => 'register_time/', 'title' => 'Register Time');

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'timetable';
        $this->data['content'] = 'timetable/register_time';
        $this->load->view('template', $this->data);
    }


    function register_calender()
     {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_TIMETABLE', 'register_calender')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_TIMETABLE :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Timetable');
        $this->data['bscrum'][] = array('link' => 'register_calender/', 'title' => 'Register Calender');

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'timetable';
        $this->data['content'] = 'timetable/register_calender';
        $this->load->view('template', $this->data);
    }


    function attendance_list()
    {
        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Timetable');
        $this->data['bscrum'][] = array('link' => 'attendance_list/', 'title' => 'Attendance List');
        $this->data['programme_list'] =$this->common_model->get_programme()->result();
        $this->data['active_menu'] = 'timetable';
        $this->data['content'] = 'timetable/attendance_list';
        $this->load->view('template', $this->data);
    }


    function packaging_list()
    {
        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Timetable');
        $this->data['bscrum'][] = array('link' => 'packaging_list/', 'title' => 'Packaging List');
        $this->data['programme_list'] =$this->common_model->get_programme()->result();
        $this->data['active_menu'] = 'timetable';
        $this->data['content'] = 'timetable/packaging_list';
        $this->load->view('template', $this->data);
    }



}