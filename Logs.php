<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 8/3/17
 * Time: 10:42 AM
 */
class Logs extends CI_Controller
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


    function api_issues()
    {
        $current_user = current_user();



        $this->data['bscrum'][] = array('link' => '#', 'title' => 'NECTA & NACTE API');
        $this->data['bscrum'][] = array('link' => 'api_issues/', 'title' => 'API Issues');

        $this->load->library('pagination');

        $where = ' WHERE ed.api_status != 1 ';


        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  (ed.index_number LIKE '%" . $_GET['key'] . "%' OR ap.MiddleName LIKE '%" . $_GET['key'] . "%'  OR ap.FirstName LIKE '%" . $_GET['key'] . "%' OR  ap.LastName LIKE '%" . $_GET['key'] . "%')";
        }


        $sql = " SELECT ed.*,ap.FirstName,ap.MiddleName,ap.LastName FROM application_education_authority as ed INNER JOIN application as ap ON ed.applicant_id=ap.id  $where ";
        $sql2 = " SELECT count(ed.id) as counter FROM application_education_authority as ed INNER JOIN application as ap ON ed.applicant_id=ap.id  $where ";

        $config["base_url"] = site_url('logs/api_issues/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 3;

        include 'include/config_pagination.php';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);
        $this->data['pagination_links'] = $this->pagination->create_links();

        $this->data['applicant_list'] = $this->db->query($sql . " ORDER BY ap.FirstName ASC LIMIT $page," . $config["per_page"])->result();


        $this->data['active_menu'] = 'logs';
        $this->data['content'] = 'panel/api_issues_logs';
        $this->load->view('template', $this->data);
    }


    function retry($id){
        $id = decode_id($id);
        $this->db->delete('necta_tmp_result',array('authority_id'=>$id));
        $row = $this->db->where('id',$id)->get('application_education_authority')->row();
        if($row){
            $this->applicant_model->trigger_necta_results($row->certificate, $id,'NEW');
            $this->session->set_flashdata('message',show_alert('Background process initiated, Refresh page after 30 sec and see the row if exist...','success'));
            redirect('logs/api_issues','refresh');
        }else{
            $this->session->set_flashdata('message',show_alert('No changes take place, Row already affected ','info'));
            redirect('logs/api_issues','refresh');
        }
    }

    function change_name(){
        $firstname = ucfirst(strtolower(trim($this->input->post('firstname'))));
        $middlename = ucfirst(strtolower(trim($this->input->post('middlename'))));
        $lastname = ucfirst(strtolower(trim($this->input->post('lastname'))));
        $id = trim($this->input->post('applicant_id'));
        $this->db->update("application", array('FirstName'=>$firstname,'MiddleName'=>$middlename,'LastName'=>$lastname), array('id'=>$id));
        $this->db->update("users", array('firstname'=>$firstname,'lastname'=>$lastname), array('applicant_id'=>$id));

    }

}
