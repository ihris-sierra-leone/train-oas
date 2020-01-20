<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 8:02 AM
 */
class Dashboard  extends CI_Controller
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



    function dashboard(){
        $current_user = current_user();

        /*if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_USERS', 'create_group')) {
            $this->session->set_flashdata("message", show_alert("ADD_GROUP :: Access denied !!", 'info'));
            redirect(site_url(), 'refresh');
        }*/
        
        $get_group = $current_user->id;
        $ql = "SELECT * FROM users_groups WHERE user_id='$get_group'";
        $fr = $this->db->query($ql)->result();
        foreach ($fr as $key => $value) {
            $usr_group=$value->group_id;
        }
        //echo $usr_group; exit();
        if($usr_group === '4'){

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Dashboard');
        $this->data['active_menu'] = 'dashboard';

        $this->data['content'] = 'dashboard/student_dashboard';
        $this->load->view('template', $this->data);
        //exit();
       }elseif($usr_group === '8'){
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Dashboard');
        $this->data['active_menu'] = 'dashboard';

        $this->data['content'] = 'dashboard/account_dashboard';
        $this->load->view('template', $this->data);
       }else{

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Dashboard');
        $this->data['active_menu'] = 'dashboard';

        $this->data['content'] = 'dashboard/dashboard';
        $this->load->view('template', $this->data);
       }

    }

}