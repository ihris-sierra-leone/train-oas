<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 6:01 PM
 */
class Setting extends CI_Controller
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

    function current_semester($id = null)
    {
        $current_user = current_user();

        $this->data['id'] = $id;
        if (!is_null($id)) {
            $id = decode_id($id);
        }

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'SETTINGS','current_semester')) {
            $this->session->set_flashdata("message", show_alert("CURRENT_SEMESTER :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Settings');
        $this->data['bscrum'][] = array('link' => 'current_semester/', 'title' => 'Current Semester');

        $this->form_validation->set_rules('semester', 'Semester', 'required');
        $this->form_validation->set_rules('ayear', 'Academic Year', 'required');

        if ($this->form_validation->run() == true) {
            $array = array(
                'AYear' => trim($this->input->post('ayear')),
                'semester' => trim($this->input->post('semester')),
                'Status' => ($this->input->post('status') ? $this->input->post('status') : 0),
            );

            $this->setting_model->add_ayear($array);
            $this->session->set_flashdata('message', show_alert('Information saved ', 'success'));
            redirect('current_semester/', 'refresh');
        }

        if (!is_null($id)) {
            $yearinfo = $this->db->get_where('ayear', array('id' => $id))->row();
            if ($yearinfo) {
                $this->data['yearinfo'] = $yearinfo;
            }
        }

        $this->data['academic_year'] = $this->common_model->get_academic_year(null, null)->result();

        $this->data['semester_list'] = $this->common_model->get_semester(2);

        $this->data['active_menu'] = 'settings';
        $this->data['content'] = 'setting/current_semester';
        $this->load->view('template', $this->data);
    }

    function application_deadline()
    {
        $current_user = current_user();

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'SETTINGS','application_deadline')) {
            $this->session->set_flashdata("message", show_alert("APPLICATION_DEADLINE :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Settings');
        $this->data['bscrum'][] = array('link' => 'application_deadline/', 'title' => 'Application Deadline');

        $this->form_validation->set_rules('deadline', 'Deadline', 'required|valid_date');

        if ($this->form_validation->run() == true) {
            $array = array(
                'deadline' => format_date($this->input->post('deadline'))
            );


            $this->db->update('application_deadline', $array);
            $this->session->set_flashdata('message', show_alert('Information saved !!', 'success'));
            redirect('application_deadline', 'refresh');
        }

        $this->data['deadline'] = $this->common_model->get_application_deadline()->row();
        $this->data['active_menu'] = 'settings';
        $this->data['content'] = 'setting/application_deadline';
        $this->load->view('template', $this->data);
    }

    function payment_status()
    {

        $current_user = current_user();
        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'SETTINGS','application_deadline')) {
            $this->session->set_flashdata("message", show_alert("PAYMENT STATUS :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Settings');
        $this->data['bscrum'][] = array('link' => 'payment_status/', 'title' => 'Payment Status');

        $this->form_validation->set_rules('payment_status', 'Payment Status', 'required');

        if ($this->form_validation->run() == true) {
            $array = array(
                'payment_status' => format_date($this->input->post('payment_status'))
            );


            $this->db->update('payment_status', $array);
            $this->session->set_flashdata('message', show_alert('Information saved !!', 'success'));
            redirect('payment_status', 'refresh');
        }

        $this->data['pay_status'] = $this->common_model->get_payment_status()->row();
        $this->data['active_menu'] = 'settings';
        $this->data['content'] = 'setting/payment_status';
        $this->load->view('template', $this->data);
    }

}