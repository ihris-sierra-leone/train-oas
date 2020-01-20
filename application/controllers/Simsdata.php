<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 3:22 PM
 */
class Simsdata extends CI_Controller
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

    function school_list()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS', 'school_list')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data from SARIS');
        $this->data['bscrum'][] = array('link' => 'school_list/', 'title' => 'Colleges or Schools List');
        if (isset($_GET) && isset($_GET['action'])) {
            $remote_data = sims_syncronise('school');
            if ($remote_data && is_object($remote_data)) {
                if($remote_data->status == 1) {
                    $this->common_model->save_remote_school($remote_data->data);
                $this->session->set_flashdata('message', show_alert('Process completed successfully ', 'success'));
                }else{
                    $this->session->set_flashdata('message', show_alert('Remote message : '.$remote_data->description, 'success'));

                }
            } else {
                $this->session->set_flashdata('message', show_alert('Error occur, no data updated !!', 'warning'));

            }
            redirect('school_list', 'refresh');
           }

            $this->data['school_list'] = $this->common_model->get_college_school()->result();
            $this->data['active_menu'] = 'sims_data';
            $this->data['content'] = 'simsdata/school_list';
            $this->load->view('template', $this->data);
    }

    function department_list()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS', 'department_list')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_DEPARTMENT :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data from SIMS');
        $this->data['bscrum'][] = array('link' => 'department_list/', 'title' => 'Department List');
        if (isset($_GET) && isset($_GET['action'])) {
            $remote_data = sims_syncronise('department');
            if ($remote_data && is_object($remote_data)) {
                if($remote_data->status == 1) {
                    $this->common_model->save_remote_department($remote_data->data);
                $this->session->set_flashdata('message', show_alert('Process completed successfully ', 'success'));
                }else{
                    $this->session->set_flashdata('message', show_alert('Remote message : '.$remote_data->description, 'success'));

                }
            } else {
                $this->session->set_flashdata('message', show_alert('Error occur, no data updated !!', 'warning'));

            }
            redirect('department_list', 'refresh');
        }

        $this->data['department_list'] = $this->common_model->get_department()->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/department_list';
        $this->load->view('template', $this->data);
    }

    function programme_list(){
        $current_user = current_user();

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS','programme_list')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_PROGRAMME :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        if (isset($_GET) && isset($_GET['action'])) {
            $remote_data = sims_syncronise('programme');
            if ($remote_data && is_object($remote_data)) {
                if($remote_data->status == 1) {
                    $this->common_model->save_remote_programme($remote_data->data);
                    $this->session->set_flashdata('message', show_alert('Process completed successfully ', 'success'));
                }else{
                    $this->session->set_flashdata('message', show_alert('Remote message : '.$remote_data->description, 'success'));

                }
            } else {
                $this->session->set_flashdata('message', show_alert('Error occur, no data updated !!', 'warning'));

            }
            redirect('programme_list', 'refresh');
        }


        $this->load->library('pagination');

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Setting');
        $this->data['bscrum'][] = array('link' => 'programme_list/', 'title' => 'Programme List');

        $department_list = get_user_department($current_user);

        $where = " WHERE 1=1";

        if (!is_null($department_list)) {
            if (!is_array($department_list)) {
                $department_list = array($department_list);
            }

            $where .= " AND Departmentid IN ( " . implode(',', $department_list) . " ) ";
        }


        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  Name LIKE '%" . $_GET['key'] . "%'";
        }

        $sql = " SELECT * FROM programme  $where ";

        $sql2 = " SELECT count(id) as counter FROM programme  $where ";

        $config["base_url"] = site_url('programme_list/');

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

        $this->data['programme_list'] = $this->db->query($sql . " ORDER BY Name ASC LIMIT 0," . $config["per_page"])->result();


        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/programme_list';
        $this->load->view('template', $this->data);
    }

    function add_programme($id=null){
        $current_user = current_user();
        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS','programme_list')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_PROGRAMME :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Setting');
        $this->data['bscrum'][] = array('link' => 'add_programme/'.$id, 'title' => 'Programme');


        if(is_null($id)) {
           $this->form_validation->set_rules('code', 'Code', 'required|is_unique[programme.Code]');
       }

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('department', 'Department', 'required');
        $this->form_validation->set_rules('cat', 'Category', 'required');

        if ($this->form_validation->run() == true) {
        $array_data = array(
            'Name' => trim($this->input->post('name')),
            'Departmentid' => trim($this->input->post('department')),
            'type' =>trim($this->input->post('cat'))
        );
        if(is_null($id)){
            $array_data['Code'] = trim($this->input->post('code'));
        }

        $add = $this->common_model->add_programme($array_data,$id);
        if($add){
            $this->session->set_flashdata('message',show_alert('Information saved successfully','success'));
            redirect('programme_list','refresh');
        }else{
            $this->data['message'] = show_alert('Fail to save Information','info');
        }


        }
         if(!is_null($id)){
            $check = $this->common_model->get_programme($id)->row();
            if($check){
                $this->data['programme_info'] = $check;
            }
    }
        $this->data['department_list'] =$this->common_model->get_department()->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/add_programme';
        $this->load->view('template', $this->data);
    }

    function add_center($id=null){
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Setting');
        $this->data['bscrum'][] = array('link' => 'add_center/'.$id, 'title' => 'Examination Centers');


        if(is_null($id)) {
           $this->form_validation->set_rules('code', 'Code', 'required|is_unique[examination_centers.center_code]');
       }

        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() == true) {
        $array_data = array(
            'center_name' => trim($this->input->post('name')),
            'center_venue' =>trim($this->input->post('venue'))
        );

       if(is_null($id)){
                $array_data['center_code'] = trim($this->input->post('code'));
       }

        $add = $this->common_model->add_center($array_data,$id);
        if($add){
            $this->session->set_flashdata('message',show_alert('Information saved successfully','success'));
            redirect('centre_list','refresh');
        }else{
            $this->data['message'] = show_alert('Fail to save Information','info');
        }


        }
         if(!is_null($id)){
            $check = $this->common_model->get_exam_center($id)->row();
            if($check){
                $this->data['center_info'] = $check;
            }
    }
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/add_center';
        $this->load->view('template', $this->data);
    }

    function register_venue($id=null)
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


//        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_TIMETABLE', 'register_venue')) {
//            $this->session->set_flashdata("message", show_alert("MANAGE_TIMETABLE :: Access denied !!", 'info'));
//            redirect(site_url('dashboard'), 'refresh');
//
//        }

        $this->form_validation->set_rules('name', 'Venue', 'required');
        $this->form_validation->set_rules('centre', 'Centre', 'required');


        if ($this->form_validation->run() == true) {
            $array_data = array(
                'name' => trim($this->input->post('name')),
                'centre_id' => trim($this->input->post('centre'))
            );

            $add = $this->common_model->register_venue($array_data,$id);
            if($add){
                $this->session->set_flashdata('message',show_alert('Information saved successfully','success'));
                redirect('venue_list','refresh');
            }else{
                $this->data['message'] = show_alert(' Duplicate Information, Fail to save','info');
            }

        }

        if(!is_null($id)){
            $check = $this->common_model->get_venue($id)->row();
            if($check){
                $this->data['venue_info'] = $check;
            }
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Venues');
        $this->data['bscrum'][] = array('link' => 'register_venue/', 'title' => 'Register Venue');
        $this->data['centre_list'] = $this->common_model->get_exam_center()->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/register_venue';
        $this->load->view('template', $this->data);
    }

    function  venue_list($id=null){

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

//
//        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'SETTINGS', 'member_list')) {
//            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
//            redirect(site_url('dashboard'), 'refresh');
//        }

        if(!is_null($id)){
            $this->common_model->delete_venue($id);
            $this->session->set_flashdata('message',show_alert('One record deleted','success'));
        }

        $this->load->library('pagination');
        $where = " WHERE 1=1";

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  name LIKE '%" . $_GET['key'] . "%'";
        }

        $sql = " SELECT * FROM venue  $where ";
        $sql2 = " SELECT count(id) as counter FROM venue  $where ";

        $config["base_url"] = site_url('venue_list/');
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");

        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 15;
        $config["uri_segment"] = 2;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);

        if(!is_null($id)){
          $page = 0;
        }

        $this->data['pagination_links'] = $this->pagination->create_links();
        $this->data['venue_list'] = $this->db->query($sql . " ORDER BY name ASC LIMIT $page," . $config["per_page"])->result();
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Venues');
        $this->data['bscrum'][] = array('link' => 'venue_list/', 'title' => 'Venue List');
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/venue_list';
        $this->load->view('template', $this->data);



    }

    //add MODULE
    function add_module($id=null){
        $current_user = current_user();
        // if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS','programme_list')) {
        //     $this->session->set_flashdata("message", show_alert("MANAGE_PROGRAMME :: Access denied !!", 'info'));
        //     redirect(site_url('dashboard'), 'refresh');
        // }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Setting');
        $this->data['bscrum'][] = array('link' => 'add_module/'.$id, 'title' => 'Add module');


         if(is_null($id)) {
            $this->form_validation->set_rules('code', 'Code', 'required|is_unique[courses.code]');
        }

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('shortname', 'Short Name', 'required');
        $this->form_validation->set_rules('programme', 'Programme', 'required');
        $this->form_validation->set_rules('level', 'Level', 'required');

        if ($this->form_validation->run() == true) {
        $array_data = array(
            'name' => trim($this->input->post('name')),
            'shortname' => trim($this->input->post('shortname')),
            'programme_id' => trim($this->input->post('programme')),
            'level' => trim($this->input->post('level'))
        );
        if(is_null($id)){
            $array_data['code'] = trim($this->input->post('code'));
        }

        $add = $this->common_model->add_module($array_data,$id);
        if($add){
            $this->session->set_flashdata('message',show_alert('Information saved successfully','success'));
            redirect('module_list','refresh');
        }else{
            $this->data['message'] = show_alert('Fail to save Information','info');
        }


        }
         if(!is_null($id)){
            $check = $this->common_model->get_module($id)->row();
            if($check){
                $this->data['module_info'] = $check;
            }
    }
        $this->data['programme_list'] =$this->common_model->get_programme()->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/add_module';
        $this->load->view('template', $this->data);
    }

    function add_department($id=null){
        $current_user = current_user();

        // if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS','programme_list')) {
        //     $this->session->set_flashdata("message", show_alert("MANAGE_PROGRAMME :: Access denied !!", 'info'));
        //     redirect(site_url('dashboard'), 'refresh');
        // }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Setting');
        $this->data['bscrum'][] = array('link' => 'add_department/'.$id, 'title' => 'Add Department');



        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('address', 'Short Name', 'required');
        $this->form_validation->set_rules('email', 'Email','required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required|valid_phone');


        if ($this->form_validation->run() == true) {
            $array_data = array(
                'name' => trim($this->input->post('name')),
                'Email' => trim($this->input->post('email')),
                'Address' => trim($this->input->post('address')),
                'LandLine' => trim($this->input->post('phone')),
                'Facultyid' => 1
            );


            $add = $this->common_model->add_department($array_data,$id);
            if($add){
                $this->session->set_flashdata('message',show_alert('Information saved successfully','success'));
                redirect('department_list','refresh');
            }else{
                $this->data['message'] = show_alert('Fail to save Information','info');
            }

        }
        if(!is_null($id)){
            $check = $this->common_model->get_department($id)->row();
            if($check){
                $this->data['dept_info'] = $check;
            }
        }
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/add_department';
        $this->load->view('template', $this->data);
    }

    //add session
    function add_session($id=null){
        $current_user = current_user();
        // if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS','programme_list')) {
        //     $this->session->set_flashdata("message", show_alert("MANAGE_PROGRAMME :: Access denied !!", 'info'));
        //     redirect(site_url('dashboard'), 'refresh');
        // }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Setting');
        $this->data['bscrum'][] = array('link' => 'add_module/'.$id, 'title' => 'Add module');

       //
      //   if(is_null($id)) {
      //      $this->form_validation->set_rules('code', 'Code', 'required|is_unique[programme.Code]');
      //  }

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run() == true) {
        $array_data = array(
            'title' => trim($this->input->post('title')),
            'status' => trim($this->input->post('status'))
        );
        // if(is_null($id)){
        //     $array_data['Code'] = trim($this->input->post('code'));
        // }

        $add = $this->common_model->add_session($array_data,$id);
        if($add){
            $this->session->set_flashdata('message',show_alert('Information saved successfully','success'));
            redirect('session_list','refresh');
        }else{
            $this->data['message'] = show_alert('Fail to save Information','info');
        }


        }
         if(!is_null($id)){
            $check = $this->common_model->get_exam_session($id)->row();
            if($check){
                $this->data['session_info'] = $check;
            }
    }
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/add_session';
        $this->load->view('template', $this->data);
    }

    //add studylevel
    function add_studylevel($id=null){
        $current_user = current_user();
        // if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS','programme_list')) {
        //     $this->session->set_flashdata("message", show_alert("MANAGE_PROGRAMME :: Access denied !!", 'info'));
        //     redirect(site_url('dashboard'), 'refresh');
        // }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Setting');
        $this->data['bscrum'][] = array('link' => 'add_studylevel/'.$id, 'title' => 'Add Studylevel');


        $this->form_validation->set_rules('name', 'Studylevel name', 'required');

        if ($this->form_validation->run() == true) {
         $array_data = array(
            'name' => trim($this->input->post('name')),
        );

        $add = $this->common_model->add_studylevel($array_data,$id);
        if($add){
            $this->session->set_flashdata('message',show_alert('Information saved successfully','success'));
            redirect('studylevel_list','refresh');
        }else{
            $this->data['message'] = show_alert('Fail to save Information','info');
        }


        }
         if(!is_null($id)){
            $check = $this->common_model->get_studylevel($id)->row();
            if($check){
                $this->data['studylevel_info'] = $check;
            }
    }
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/add_studylevel';
        $this->load->view('template', $this->data);
    }

    function centre_list(){

        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        // if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS', 'centre_list')) {
        //     $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
        //     redirect(site_url('dashboard'), 'refresh');
        // }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data from SARIS');
        $this->data['bscrum'][] = array('link' => 'centre_list/', 'title' => 'Centre List');
        $this->load->model('common_model');
        $this->data['centres'] = $this->common_model->get_exam_center()->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/centre_list';
        $this->load->view('template', $this->data);

    }

    //add certificate
    function add_certificate($id=null){
        $current_user = current_user();
        // if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS','programme_list')) {
        //     $this->session->set_flashdata("message", show_alert("MANAGE_PROGRAMME :: Access denied !!", 'info'));
        //     redirect(site_url('dashboard'), 'refresh');
        // }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Setting');
        $this->data['bscrum'][] = array('link' => 'add_certificate/'.$id, 'title' => 'Add Certificate');

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('value', 'Value', 'required');

        if ($this->form_validation->run() == true) {
         $array_data = array(
            'name' => trim($this->input->post('name')),
            'value' => trim($this->input->post('value'))
        );

        $add = $this->common_model->add_certificate($array_data,$id);
        if($add){
            $this->session->set_flashdata('message',show_alert('Information saved successfully','success'));
            redirect('certification_list','refresh');
        }else{
            $this->data['message'] = show_alert('Fail to save Information','info');
        }


        }
         if(!is_null($id)){
            $check = $this->common_model->get_certificates($id)->row();
            if($check){
                $this->data['certificate_info'] = $check;
            }
    }
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/add_certificate';
        $this->load->view('template', $this->data);
    }

    function module_list(){
        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data from SARIS');
        $this->data['bscrum'][] = array('link' => 'module_list/', 'title' => 'Module List');

        $this->load->library('pagination');

        $where = " WHERE 1=1";

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  name LIKE '%" . $_GET['key'] . "%' OR code LIKE '%" . $_GET['key'] . "%'";
        }

        $sql = " SELECT * FROM courses  $where ";
        $sql2 = " SELECT count(id) as counter FROM courses  $where ";

        $config["base_url"] = site_url('module_list/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");

            $config["total_rows"] = $this->db->query($sql2)->row()->counter;
            $config["per_page"] = 10;
            $config["uri_segment"] = 2;

            include 'include/config_pagination.php';

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
            $this->data['pagination_links'] = $this->pagination->create_links();
            $this->data['module_list'] = $this->db->query($sql . " ORDER BY name ASC ")->result();

        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/module_list';
        $this->load->view('template', $this->data);
    }

    function session_list(){

        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        // if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS', 'school_list')) {
        //     $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
        //     redirect(site_url('dashboard'), 'refresh');
        // }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data from SARIS');
        $this->data['bscrum'][] = array('link' => 'session_list/', 'title' => 'Session List');
        $this->data['sessions'] = $this->common_model->display_sessions()->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/session_list';
        $this->load->view('template', $this->data);
    }

    function certification_list(){
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'DATA_FROM_SIMS', 'school_list')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data from SARIS');
        $this->data['bscrum'][] = array('link' => 'certification_list/', 'title' => 'Certificate lists');

        $this->data['certification'] = $this->common_model->get_certificates()->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/certification_list';
        $this->load->view('template', $this->data);
    }

    public function delete_center($id){

        $this->common_model->delete_center($id);
        $this->data['active_menu'] = 'sims_data';
        $this->session->set_flashdata('message',show_alert('One record deleted','success'));
        redirect(site_url().'/simsdata/centre_list');

        }

    public function delete_module($id){

        $this->common_model->delete_module($id);
        $this->data['active_menu'] = 'sims_data';
        $this->session->set_flashdata('message',show_alert('One record deleted','success'));
        redirect(site_url().'/simsdata/module_list');

        }

    public function delete_department($id){

        $this->common_model->delete_department($id);
        $this->data['active_menu'] = 'sims_data';
        $this->session->set_flashdata('message',show_alert('One record deleted','success'));
        redirect(site_url().'/simsdata/department_list');

    }

    public function delete_programme($id){
        //check if this programme has already assigned to some courses
        if(!is_null($id)) {
            $programmeArray=array('programme_id'=>$id);
            $programmecode= get_course($programmeArray,$column='programme_id');
        }


        if($programmecode){
            $this->session->set_flashdata('message',show_alert('Can not delete programme, There are records associate to this programme.','warning'));
            redirect(site_url().'/simsdata/programme_list');
        }else{
            $this->common_model->delete_programme($id);
            $this->data['active_menu'] = 'sims_data';
            $this->session->set_flashdata('message',show_alert('One record deleted','success'));
        }

        redirect(site_url().'/simsdata/programme_list');

    }

    public function delete_certificate($id){

        $this->common_model->delete_cert($id);
        $this->data['active_menu'] = 'sims_data';
        $this->session->set_flashdata('message',show_alert('One record deleted','success'));
        redirect(site_url().'/simsdata/certification_list');

        }

    public function delete_exam_sassion($id){

        $this->common_model->delete_session($id);
        $this->data['active_menu'] = 'sims_data';
        $this->session->set_flashdata('message',show_alert('One record deleted','success'));
        redirect(site_url().'/simsdata/session_list');

        }

    public function delete_studylevel($id){

        $this->common_model->delete_studylevel($id);
        $this->data['active_menu'] = 'sims_data';
        $this->session->set_flashdata('message',show_alert('One record deleted','success'));
        redirect(site_url().'/simsdata/studylevel_list');

        }

//    public function centerForm(){
//
//            $this->form_validation->set_rules('name', 'Name', 'required');
//            $this->form_validation->set_rules('code', 'Code', 'required');
//            $this->form_validation->set_rules('venue', 'Venue', 'required');
//
//            if($this->form_validation->run() == FALSE){
//            $this->data['active_menu'] = 'sims_data';
//            $this->session->set_flashdata('message',show_alert('Sorry all fields are required...!!!','warning'));
//            redirect(site_url().'/simsdata/add_center');
//
//            }else{
//
//            $save = array(
//            'center_name' => $this->input->post('name'),
//            'center_code' => $this->input->post('code'),
//            'center_venue' => $this->input->post('venue')
//                );
//
//            $this->common_model->save_center($save);
//            $this->data['active_menu'] = 'sims_data';
//            $this->session->set_flashdata('message',show_alert('Record saved successfully','success'));
//            redirect(site_url().'/simsdata/centre_list');
//        }
//      }

    public function moduleForm(){

            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            if($this->form_validation->run() == FALSE){
            $this->data['active_menu'] = 'sims_data';
            $this->session->set_flashdata('message',show_alert('Sorry all fields are required...!!!','warning'));
            redirect(site_url().'/simsdata/add_module');

            }else{

            $dataArray = array(
               'name' => $this->input->post('name'),
               'description' => $this->input->post('description')
                );
            //$this->load->model('common_model');
            $this->common_model->save_module($dataArray);
            $this->data['active_menu'] = 'sims_data';
            $this->session->set_flashdata('message',show_alert('A record saved successfully','success'));
            redirect(site_url().'/simsdata/module_list');
        }
      }

    public function certificateForm(){

            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('value', 'Value', 'required');
            if($this->form_validation->run() == FALSE){
            $this->data['active_menu'] = 'sims_data';
            $this->session->set_flashdata('message',show_alert('Sorry all fields are required...!!!','warning'));
            redirect(site_url().'/simsdata/add_certificate');

            }else{

            $ArrayData = array(
               'name' => $this->input->post('name'),
               'value' => $this->input->post('value')
                );
            //$this->load->model('common_model');
            $this->common_model->save_certificates($ArrayData);
            $this->data['active_menu'] = 'sims_data';
            $this->session->set_flashdata('message',show_alert('New certificate added successfully','success'));
            redirect(site_url().'/simsdata/certification_list');
        }
      }

    public function sessionForm(){

            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required');

            if($this->form_validation->run() == FALSE){

            $this->data['active_menu'] = 'sims_data';
            $this->session->set_flashdata('message',show_alert('Sorry all fields are required...!!!','warning'));
            redirect(site_url().'/simsdata/add_session');

            }else{

            $sessionArray = array(
               'title' => $this->input->post('title'),
               'status' => $this->input->post('status')
                );
            //$this->load->model('common_model');
            $this->common_model->save_sessions($sessionArray);
            $this->data['active_menu'] = 'sims_data';
            $this->session->set_flashdata('message',show_alert('A record saved successfully','success'));
            redirect(site_url().'/simsdata/session_list');
          }
        }

    public function studylevelForm(){

            $this->form_validation->set_rules('college', 'College', 'required');
            $this->form_validation->set_rules('type', 'Type', 'required');

            if($this->form_validation->run() == FALSE){
            $this->data['active_menu'] = 'sims_data';
            $this->session->set_flashdata('message',show_alert('Sorry all fields are required...!!!','warning'));
            redirect(site_url().'/simsdata/add_studylevel');

            }else{

            $DataArray = array(
               'type1' => $this->input->post('type'),
               'name' => $this->input->post('college')
                );
            //$this->load->model('common_model');

            $this->common_model->save_studylevel($DataArray);
            $this->data['active_menu'] = 'sims_data';
            $this->session->set_flashdata('message',show_alert('A record saved successfully','success'));
            redirect(site_url().'/simsdata/studylevel_list');
        }
      }

    function studylevel_list(){

        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data from SARIS');
        $this->data['bscrum'][] = array('link' => 'studylevel_list/', 'title' => 'StudyLevel List');
        $this->data['studylevel_list'] = $this->common_model->get_studylevel()->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/studylevel_list';
        $this->load->view('template', $this->data);
    }

    function manage_subject(){
        $current_user = current_user();
        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'SETTINGS', 'manage_subject')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_SUBJECT :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data from SARIS');
        $this->data['bscrum'][] = array('link' => 'manage_subject/', 'title' => 'Secondary Subject');


        $this->data['sec_subject'] = $this->setting_model->get_sec_subject()->result();
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/manage_subject';
        $this->load->view('template', $this->data);
    }

    function add_sec_subject($id=null){
        $current_user = current_user();
        $this->data['id'] = $id;
        if(!is_null($id)){
            $id = decode_id($id);
        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'SETTINGS', 'manage_subject')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_SUBJECT :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Data from SARIS');
        $this->data['bscrum'][] = array('link' => 'add_sec_subject/'.$this->data['id'], 'title' => (is_null($id) ? 'Add New Subject':'Edit Subject'));

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('shortname', 'Short Name', 'required');

        if ($this->form_validation->run() == true) {
            $array_data = array(
                'name'=> trim($this->input->post('name')),
                'shortname'=> trim($this->input->post('shortname')),
                'status'=> trim($this->input->post('status')),
            );

            $add = $this->setting_model->add_sec_subject($array_data,$id);
            if($add){
                $this->session->set_flashdata('message',show_alert('Information saved successfully','success'));
                redirect('manage_subject','refresh');
            }else{
                $this->data['message'] = show_alert('Fail to save Information','warning');
            }

        }


        if(!is_null($id)){
            $this->data['subject_info'] = $this->setting_model->get_sec_subject($id)->row();
        }
        $this->data['active_menu'] = 'sims_data';
        $this->data['content'] = 'simsdata/add_sec_subject';
        $this->load->view('template', $this->data);
    }


}
