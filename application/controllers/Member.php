
<?php

class Member extends CI_Controller
{

    private $MODULE_ID = '';
    private $GROUP_ID = '';

    function __construct()
    {
        parent::__construct();


        $this->data['CURRENT_USER'] = current_user();

        $this->form_validation->set_error_delimiters('<div class="required">', '</div>');

        $this->data['title'] = 'Member';
        $this->APPLICANT = $this->applicant_model->get_applicant($this->data['CURRENT_USER']->applicant_id);
        if ($this->APPLICANT) {
            $this->data['APPLICANT'] = $this->APPLICANT;
            $this->data['APPLICANT_MENU'] = $this->APPLICANT_MENU = $this->applicant_model->get_examination_payments($this->APPLICANT->id);

        }

        $tmp_group = get_user_group();
        $this->GROUP_ID = $this->data['GROUP_ID'] = $tmp_group->id;
        $this->MODULE_ID = $this->data['MODULE_ID'] = $tmp_group->module_id;
    }

    function annual_billing()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_USERS', 'profile')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'annual_billing/', 'title' => 'Annual subscription fee');

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'billing';
        $this->data['content'] = 'member/annual_billing';
        $this->load->view('template', $this->data);
    }

    function exam_billing()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_USERS', 'profile')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'annual_billing/', 'title' => 'Examination fee');

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'member_process';
        $this->data['content'] = 'member/exam_billing';
        $this->load->view('template', $this->data);

    }


    function register()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_MEMBERS', 'register')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'register/', 'title' => 'Search member');

        $this->data['gender_list'] = $this->common_model->get_gender()->result();
        $this->data['marital_status_list'] = $this->common_model->get_marital_status()->result();
        $this->data['nationality_list'] = $this->common_model->get_nationality()->result();
        $this->data['disability_list'] = $this->common_model->get_disability()->result();
        $this->data['active_menu'] = 'member_process';
        $this->data['content'] = 'member/register';
        $this->load->view('template', $this->data);
    }


    function import()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        $excel_upload = TRUE;
        $upload_error = '';


        // if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'grade_book')) {
        //     $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
        //     redirect(site_url('dashboard'), 'refresh');
        // }

        // validate form input
        $this->form_validation->set_rules('post_data', 'post_data', 'required');
        // $this->form_validation->set_rules('course','Course', 'required');


        if ($this->form_validation->run() == true) {

            //$code_course = $this->input->post('course');
            if (isset($_FILES['userfile']['name']) && empty($_FILES['userfile']['name'])) {
                $upload_error = '<div class="required">You must upload excel file.</div>';
                $excel_upload = FALSE;
            } elseif (isset($_FILES['userfile']['name']) && (get_file_extension($_FILES['userfile']['name']) != 'xlsx' && get_file_extension($_FILES['userfile']['name']) != 'xls')) {
                $upload_error = '<div class="required">File uploaded must be in xls or xlxs format.</div>';
                $excel_upload = FALSE;
            }

            if ($excel_upload == TRUE) {

                $dest_name = time() . 'result_sheet.xlsx';
                move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/temp/' . $dest_name);
                // set file path
                $file = './uploads/temp/' . $dest_name;
                //load the excel library
                $this->load->library('excel');
                //read file from path
                $objPHPExcel = IOFactory::load($file);
                //get only the Cell Collection
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                //extract to a PHP readable array format
                $header = array();
                $arr_data = array();
                foreach ($cell_collection as $cell) {
                    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                    //header will/should be in row 1 only. of course this can be modified to suit your need.
                    if ($row == 1) {
                        $header[$row][$column] = trim($data_value);
                    } else {
                        $arr_data[$row][$column] = trim($data_value);
                    }
                }


                foreach ($arr_data as $row) {

                    $data = array();
                    $student_data = array();
                    $courseError = array();
                    if (trim($row['A']) <> '') {

                        $val = substr(trim($row['A']) ,0 , 4);
                        if ($val === "TIOB") {
                            $this->session->set_flashdata('message', show_alert('Invalid import template', 'warning'));
                            redirect(site_url('import'), 'refresh');
                            exit;
                        }

                        $data['AYear'] = $row['A'];
                        $data['Semester'] = $row['B'];
                        // $data['RegNo'] = $row['C'];
                        $data['LastName'] = $row['D'];
                        $data['FirstName'] = $row['E'];
                        $data['MiddleName'] = $row['F'];
                        $data['postal'] = $row['G'];
                        $data['Mobile1'] = (isset($row['H']) ? '255'.$row['H'] : '');
                        $data['Email'] = (isset($row['I']) ? $row['I'] : '');
                        $data['cooperate'] = (isset($row['J']) ? $row['J'] : '');
                        $data['level'] = $row['M'];
                        $current_username=$row['C'];
                        $current_password="123456";
                        if(empty($row['J'])){
                            $row['N'] = 0;
                        }else{
                            $row['N'] = 1;
                        }
                        $data['member_type'] = $row['N'];

                        //pack data for student table
                        $student_data['registration_number'] = $row['C'];
                        $student_data['entry_year'] = $row['A'];
                        $student_data['programme_id'] = $row['L'];
                        $student_data['level'] = $row['M'];
                            $student_data['first_name'] = $row['E'];
                            $student_data['surname'] = $row['D'];
                            $student_data['other_names'] = $row['F'];
                            $student_data['address'] = $row['G'];
                            $student_data['mobile'] =(isset($row['H']) ? '255'.$row['H'] : '');
                            $student_data['email'] = (isset($row['I']) ? $row['I'] : '');
                        if(empty($row['J'])){
                            $row['N'] = 0;
                        }else{
                            $row['N'] = 1;
                        }
                        $student_data['member_type'] = $row['N'];
                        $student_data['cooperate'] = (isset($row['J']) ? $row['J'] : '');
                        $student_data['created_at'] = date('Y-m-d H:i:s');
                        $student_data['updated_at'] = date('Y-m-d H:i:s');
                        $student_data['centre_id'] = $row['K'];

                        if($student_data['programme_id'] == 1002){
                            $application_type = 2;
                        }else if($student_data['programme_id'] == 1001){
                            $application_type = 1;
                        }else if($student_data['programme_id'] == 1003){
                            $application_type = 3;
                        }else{
                            $application_type = 3;
                        }

                        $data['application_type'] = $application_type;

                        // echo $data['application_type']; exit;


                        // $data['Recorder']=$current_user->username;


                        $regno = check_student($id = null, $student_data['registration_number']);

                        if ($regno == TRUE) {
                            // $check_user_id = $this->db->get_where('students',array('registration_number'=>$student_data['registration_number']))->row()->user_id;
                            // echo $check_user_id; exit;
                            // if(empty($check_user_id)){
//                            $this->exam_model->import_application_data($data);
//
//                            $get_applicant_id = $this->db->query("select id from application order by id desc")->row()->id;
//                            $additional_data = array(
//                                'firstname' => $student_data['first_name'],
//                                'lastname' => $student_data['surname'],
//                                'username' => $student_data['registration_number'],
//                                'applicant_id' => $get_applicant_id,
//                                'password' => $this->ion_auth->hash_password(strtoupper($student_data['surname'])),
//                                'phone' => (isset($row['H']) ? $row['H'] : ''),
//                                'email' => (isset($row['I']) ? $row['I'] : ''),
//                                'campus_id' => 1,
//                                'active' => 1
//
//                            );
//                            $this->db->insert('users',$additional_data);
//                            $last_id = $this->db->query("select id from users order by id desc")->row()->id;
//                            $user_grp_data = array(
//                                'user_id' => $last_id,
//                                'group_id' => 4
//                            );
//                            $this->exam_model->insert_user_data($user_grp_data,$last_id);
//                            $this->db->update('application', array('user_id'=>$last_id));
//
//                            $student_data['user_id'] = $last_id;
//                            $student_feedback = $this->exam_model->update_student_data($student_data,$student_data['registration_number']);
                            // }
                            // $student_feedback = $this->exam_model->update_student_data($student_data,$student_data['registration_number']);
                            // echo "hapa nafika 1";exit;
                            // $courseError[$regno] = $student_data['registration_number'] . ' STUDENT EXIST IN THE SYSTEM';
                        } else {

                            // echo "hapa nafika";exit;
                            $feedback=$this->exam_model->import_application_data($data);
                            $get_applicant_id = $this->db->query("select id from application order by id desc")->row()->id;
                            $user_additional_data = array(
                                'firstname' => $student_data['first_name'],
                                'lastname' => $student_data['surname'],
                                'username' => $current_username,
                                'applicant_id' => $get_applicant_id,
                                'password' => $this->ion_auth->hash_password($current_password),
                                'phone' => (isset($row['H']) ? $row['H'] : ''),
                                'email' => (isset($row['I']) ? $row['I'] : ''),
                                'campus_id' => 1,
                                'active' => 3

                            );
                            $this->db->insert('users',$user_additional_data);
                            $last_id = $this->db->query("select id from users order by id desc")->row()->id;
                            $user_grp_data = array(
                                'user_id' => $last_id,
                                'group_id' => 4
                            );
                            $this->exam_model->insert_user_data($user_grp_data);
                            $this->db->where('id', $get_applicant_id);
                            $this->db->update('application', array('user_id'=>$last_id));
                            $student_data['user_id'] = $last_id;

                            //$this->exam_model->import_application_data($data);
                            $student_feedback = $this->exam_model->import_student_data($student_data);
                            if (!$feedback) {
                                $courseError[$regno] = $student_data['registration_number'] . ' DUPLICATE RESULTS';
                            }
                        }

                        if ($courseError) {
                            $courseError['final'] = "PLEASE REVIEW YOUR INFORMATION";
                            $this->data['result_error'] = $courseError;
                            unlink('./uploads/temp/' . $dest_name);
                        } else {
                            execInBackground('response send_notification_after_import ' . $get_applicant_id.' ');
                            $this->session->set_flashdata('message', show_alert('Members Imported successfully!!', 'success'));
                            unlink('./uploads/temp/' . $dest_name);
                        }
                    }
                }
            }

        }


        $this->data['upload_error'] = $upload_error;
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member Import');
        $this->data['bscrum'][] = array('link' => 'import/', 'title' => 'Import');

        $this->data['active_menu'] = 'member_process';
        $this->data['content'] = 'member/import';
        $this->load->view('template', $this->data);
    }

    function import_new_member()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }

        $current_user = current_user();

        $this->load->model('member_model');

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        $excel_upload = TRUE;
        $upload_error = '';


        // validate form input
        $this->form_validation->set_rules('post_data','post_data', 'required');
        // $this->form_validation->set_rules('cooperate','Cooperate field', 'required');

        if ($this->form_validation->run() == true) {

            // $cooperate = $this->input->post('cooperate');

            if (isset($_FILES['userfile']['name']) && empty($_FILES['userfile']['name'])) {
                $upload_error = '<div class="required">You must upload excel file.</div>';
                $excel_upload = FALSE;
            } elseif (isset($_FILES['userfile']['name']) && (get_file_extension($_FILES['userfile']['name']) != 'xlsx' && get_file_extension($_FILES['userfile']['name']) != 'xls')) {
                $upload_error = '<div class="required">File uploaded must be in xls or xlsx format.</div>';
                $excel_upload = FALSE;
            }

            if ($excel_upload == TRUE) {
                $dest_name=time().'result_sheet.xlsx';
                move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/temp/'.$dest_name);
                // set file path
                $file = './uploads/temp/'.$dest_name;
                //load the excel library
                $this->load->library('excel');
                //read file from path
                $objPHPExcel = IOFactory::load($file);
                //get only the Cell Collection
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                //extract to a PHP readable array format
                $header=array();
                $arr_data=array();
                foreach ($cell_collection as $cell) {
                    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

                    if ($row == 1) {
                        $header[$row][$column] = trim($data_value);
                    } else {
                        $arr_data[$row][$column] = trim($data_value);
                    }
                }


                $error_when_import=array();
                foreach ($arr_data as $row) {
                    $data=array();

                    if (trim($row['A']) <> '' ) {

                        $data['registration_number']=$row['A'];

                        $regno = check_student($id=null,$data['registration_number']);
                        $value = $data['registration_number'];
                        //echo $regno;exit;
                        if(!$regno){
                            $this->member_model->import_members($data,$id=null);
                        }else{
                            $error_when_import[$value] = 'Duplicate Entry For ' .$data['registration_number'];
                        }

                    }

                }

                if($error_when_import[$value]){
                    $error_when_import['final'] = "PLEASE REVIEW YOUR INFORMATION";
                    $this->data['member_error'] = $error_when_import;
                    unlink('./uploads/temp/' . $dest_name);
                }else {
                    $this->session->set_flashdata('message', show_alert('Members imported successfully !!', 'success'));
                    unlink('./uploads/temp/'.$dest_name);
                }
            }
        }


        $this->data['upload_error']=$upload_error;
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'import/', 'title' => 'Import');

        $this->data['active_menu'] = 'member_process';
        $this->data['content'] = 'member/import_new_member';
        $this->load->view('template', $this->data);


    }

    function billing()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_MEMBERS', 'billing')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'billing/', 'title' => 'Billing');

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'member_process';
        $this->data['content'] = 'member/billing';
        $this->load->view('template', $this->data);
    }

    function suggestion_box()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_MEMBERS', 'suggestion_box')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'suggestion_box/', 'title' => 'Suggestion Box');

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'member_process';
        $this->data['content'] = 'member/suggestion';
        $this->load->view('template', $this->data);
    }
    function member_list()
    {

        $current_user = current_user();

        $this->load->library('pagination');

        $cout_where=0;
        $where = " WHERE 1=1 ";

//        $where = ' AND a.status != 0 ';

        if (isset($_GET['type']) && $_GET['type'] != '') {
            $where .= " AND programme_id='" . $_GET['type'] . "' ";
            $cout_where +=1;
        }

        if (isset($_GET['cooperate']) && $_GET['cooperate'] != '') {
            $where .= " AND cooperate='" . $_GET['cooperate'] . "' ";
            $cout_where +=1;
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $where .= " AND entry_year='" . $_GET['year'] . "' ";
            $cout_where +=1;
        }

        if (isset($_GET['name']) && $_GET['name'] != '') {

            $cout_where +=1;
            // $where .= " AND first_name LIKE '%" . $_GET['name'] . "%'  OR surname LIKE '%" . $_GET['name'] . "%' OR other_names LIKE '%" . $_GET['name'] . "%' ";
            $where .= " AND first_name LIKE '%" . $_GET['name'] . "%'  OR registration_number='".$_GET['name']."' OR surname LIKE '%" . $_GET['name'] . "%' OR other_names LIKE '%" . $_GET['name'] . "%' ";
        }
/*
        if (isset($_GET['from']) && $_GET['from'] != '') {
            $cout_where +=1;
            $where .= " AND DATE(updated_at)>='" . format_date($_GET['from']) . "' ";
        }

        if (isset($_GET['to']) && $_GET['to'] != '') {
            $where .= " AND DATE(updated_at)<='" . format_date($_GET['to']) . "' ";
            $cout_where +=1;
        }
*/
        if($cout_where==0)
            $where .= " AND  first_name<>''";

       // $sql = " SELECT students.*,application.id as appl_id FROM students left join application  on students.user_id=application.user_id   $where ";
        $sql = " SELECT * FROM students  $where ";

        $sql2 = " SELECT count(id) as counter FROM students $where ";

        $config["base_url"] = site_url('member_list/');
        
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");


        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
        $config["per_page"] = 50;
        $config["uri_segment"] = 2;

        $where_len = strlen($where);
        if ($where_len != 31){
            $this->data['member_list'] = $this->db->query($sql . "  ORDER BY id DESC ")->result();
        }else{
            $this->data['member_list'] = $this->db->query($sql . "  ORDER BY id DESC limit 100")->result();
        }
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'member_list/', 'title' => 'Member List');
        $this->data['active_menu'] = 'member_process';
        $this->data['content'] = 'member/member_list';
        $this->load->view('template', $this->data);

    }


    function exam_registration_list()
    {

        $current_user = current_user();

        $this->load->library('pagination');

        $cout_where=0;
        $where = ' WHERE 1=1 ';

//        $where = ' AND a.status != 0 ';

        if (isset($_GET['type']) && $_GET['type'] != '') {
            $where .= " AND programme_id='" . $_GET['type'] . "' ";
            $cout_where +=1;
        }

        if (isset($_GET['exam_session']) && $_GET['exam_session'] != '') {
          $where .= " AND exam_semester='" . $_GET['exam_session'] . "' ";
            $cout_where +=1;
        }
        if (isset($_GET['exam_center']) && $_GET['exam_center'] != '') {
            $where .= " AND center_id='" . $_GET['exam_center'] . "' ";
            $cout_where +=1;
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $where .= " AND exam_year='" . $_GET['year'] . "' ";
            $cout_where +=1;
        }


        if($cout_where==0)
        {

            $where=" AND  exam_year='" . date('Y'). "'";
        }

        $sql = "SELECT DISTINCT  student_exam_registered.registration_number as regno,  students.*,exam_year,exam_semester,center_id FROM students  inner join student_exam_registered on student_exam_registered.registration_number=students.registration_number  $where ";

        $config["base_url"] = site_url('exam_registration_list/');

        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
        if (count($_GET) > 0)
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config["uri_segment"] = 2;

        $this->data['member_list'] = $this->db->query($sql . "  ORDER BY id DESC ")->result();
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'exam_registration_list/', 'title' => 'Examination Register List');
        $this->data['active_menu'] = 'member_process';
        $this->data['content'] = 'member/exam_registration_list';
        $this->load->view('template', $this->data);

    }

    function cooperate_member()
    {

        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        $this->load->library('pagination');

        $where = ' WHERE 1=1 ';


        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  institution_name LIKE '%" . $_GET['key'] . "%'";
        }

        $sql = " SELECT * FROM member  $where ";
        $sql2 = " SELECT count(id) as counter FROM member  $where ";

        $config["base_url"] = site_url('cooperate_member/');

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
        $this->data['member_list'] = $this->db->query($sql . " ORDER BY institution_name ASC ")->result();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'cooperate_member/', 'title' => 'Cooperate Members');
        $this->data['active_menu'] = 'member_process';
        $this->data['content'] = 'member/cooperate_member';
        $this->load->view('template', $this->data);

    }

    function fellow_member()
    {

        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

//        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_MEMBERS', 'cooperate_member')) {
//            $this->session->set_flashdata("message", show_alert("MANAGE_MEMBERS :: Access denied !!", 'info'));
//            redirect(site_url('dashboard'), 'refresh');
//        }

        $this->load->library('pagination');

        $where = ' WHERE 1=1 ';


        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  (name LIKE '%" . $_GET['key'] . "%' OR  email LIKE '%" . $_GET['key'] . "%' OR title LIKE '%" . $_GET['key'] . "%' OR  postal LIKE '%" . $_GET['key'] . "%')";
        }

        $sql = " SELECT * FROM fellow_member  $where ";
        $sql2 = " SELECT count(id) as counter FROM fellow_member  $where ";

        $config["base_url"] = site_url('fellow_member/');

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
        $this->data['fellow_list'] = $this->db->query($sql . " ORDER BY name ASC ")->result();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'fellow_member/', 'title' => 'Fellow Members');
        $this->data['active_menu'] = 'member_process';
        $this->data['content'] = 'member/fellow_member';
        $this->load->view('template', $this->data);

    }

    /**
     * @param null $id
     */
    function add_member($id = null)
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Setting');
        $this->data['bscrum'][] = array('link' => 'add_member/' . $id, 'title' => 'Member');

        $this->form_validation->set_rules('name', 'name', 'required');
        //$this->form_validation->set_rules('mobileone', 'mobile', 'required');
        $this->form_validation->set_rules('postal', 'postal', 'required');

        $this->load->model('member_model');
        if ($this->form_validation->run() == true) {
            $array_data = array(
                'institution_name' => trim($this->input->post('name')),
                'mobile' => trim($this->input->post('mobile')),
                'telephone' => trim($this->input->post('telephone')),
                'fax' => trim($this->input->post('fax')),
                'email' => trim($this->input->post('email')),
                'postal' => trim($this->input->post('postal')),
                'website' => trim($this->input->post('website'))

                //'member_type_id' =>1
            );

            $add = $this->member_model->add_member($array_data, $id);
            if ($add) {
                $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
                redirect('cooperate_member', 'refresh');
            } else {
                $this->data['message'] = show_alert('Fail to save Information', 'info');
            }

        }
        if (!is_null($id)) {
            $check = $this->member_model->get_member($id)->row();
            if ($check) {
                $this->data['member_info'] = $check;
            }
        }
        $this->data['membershiptype_list'] = $this->common_model->get_membertype()->result();
        $this->data['active_menu'] = 'cooperate_member';
        $this->data['content'] = 'member/add_member';
        $this->load->view('template', $this->data);
    }

    function add_fellow_member($id = null)
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Setting');
        $this->data['bscrum'][] = array('link' => 'add_fellow_member/' . $id, 'title' => 'Member');

        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_rules('postal', 'postal', 'required');
        $this->form_validation->set_rules('email', 'email', 'required|valid_email');
        $this->load->model('member_model');
        if ($this->form_validation->run() == true) {
            $array_data = array(
                'name' => trim($this->input->post('name')),
                'mobile' => trim($this->input->post('mobile')),
                'title' => trim($this->input->post('title')),
                'email' => trim($this->input->post('email')),
                'postal' => trim($this->input->post('postal')),
                'member_type_id' => 2
            );

            $add = $this->member_model->add_fellow_member($array_data, $id);
            if ($add) {
                $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
                redirect('fellow_member', 'refresh');
            } else {
                $this->data['message'] = show_alert('Fail to save Information', 'info');
            }

        }
        if (!is_null($id)) {
            $check = $this->member_model->get_fellow($id)->row();
            if ($check) {
                $this->data['member_info'] = $check;
            }
        }
        $this->data['active_menu'] = 'member_process';
        $this->data['content'] = 'member/add_fellow_member';
        $this->load->view('template', $this->data);
    }

    function change_stata($id = null)
    {

        $current_user = current_user();
        $uid = stripcslashes($_GET['id']);
        //echo $uid;exit();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'register/' . $uid, 'title' => 'Member informations');

        $this->form_validation->set_rules('status', 'Status', 'required');
        $this->load->model('member_model');

        if ($this->form_validation->run() == true) {
            $appid = stripcslashes($_GET['appID']);
            $id = $uid;
            $status_receive = trim($this->input->post('status'));
            $level = trim($this->input->post('level'));


            if ($status_receive == 2) {

                $application_status = array(
                    'status' => $status_receive
                );
                $array_data = array(
                    'group_id' => 5
                );

                $this->member_model->change_stata($array_data, $application_status, $uid);
                $this->db->insert('notify_tmp',
                    array(
                    'status'=>$status_receive,
                    'type' => 'FEED',
                    'data' => json_encode(array('applicant_id' => $appid, 'user_id' => $uid, 'resend' => 1))
                    )
                );
                $last_row = $this->db->insert_id();
                execInBackground('response send_notification ' . $last_row);
                $this->session->set_flashdata('message', show_alert('Student has been rejected! Information updated successfull', 'success'));
                redirect('applicant_list/', 'refresh');


            } elseif ($status_receive == 6) {

                $array_data = array(
                    'group_id' => 5,
                );
                $application_status = array(
                    'status' => $status_receive
                );

                $this->member_model->change_stata($array_data, $application_status, $uid);
                $this->db->insert('notify_tmp', 
                    array(
                        'status'=>$status_receive,
                        'type' => 'FEED', 
                        'data' => json_encode(array('applicant_id' => $appid, 'user_id' => $uid, 'resend' => 1))
                    )
                );
                $last_row = $this->db->insert_id();
                execInBackground('response send_notification ' . $last_row);
                $this->session->set_flashdata('message', show_alert('Information saved successfully, Student selected and feedback has been sent', 'success'));
                redirect('applicant_list/', 'refresh');

            } elseif ($status_receive == 8) {

                $array_data = array(
                    'group_id' => 4,
                );
                $application_status = array(
                    'status' => $status_receive,
                    'member_type' => 1,
                    'level' => $level
                );
                $add = $this->member_model->change_stata($array_data, $application_status, $uid);

            } elseif ($status_receive == 9) {
                $array_data = array(
                    'group_id' => 4,
                );
                $application_status = array(
                    'status' => $status_receive,
                    'member_type' => $status_receive,
                    'level' => $level
                );
                $add = $this->member_model->change_stata($array_data, $application_status, $uid);

            } else {
                if ($status_receive == 4) {

                    $array_data = array(
                        'group_id' => 4,
                    );
                    $application_status = array(
                        'status' => $status_receive,
                        'member_type' => '0',
                        'level' => $level
                    );
                    $add = $this->member_model->change_stata($array_data, $application_status, $uid);
                }
            }

            if ($add) {
                $check = $this->member_model->get_info($uid)->row();
                $pcode1 = '';
                if ($check) {

                    $this->data['member_details'] = $check;
                    $code = "SELECT * FROM application_programme_choice WHERE applicant_id IN (SELECT id FROM application WHERE user_id='$uid' )";
                    $pcode = $this->db->query($code)->result();
                    foreach ($pcode as $key => $value) {
                        $pcode1 = $value->choice1;
                    }
                    #---------------------------------------------------------------------------
                    #                   generate registration number
                    #---------------------------------------------------------------------------

                    $member_type = $this->data['member_details']->member_type;

                    if ((!is_null($member_type) && $member_type == 1) || (!is_null($status_receive) && $status_receive == 8)) {
                        //if((!is_null($status_receive) && $status_receive == 8)){

                        $where = '/O/';
                    } elseif ((!is_null($status_receive) && $status_receive == 9)) {
                        $where = '/A/';
                    } else {
                        $where = '/S/';
                    }

                    $flag = false;
                    while ($flag == false) {
                        $check_regno = "SELECT MAX( CAST( (SUBSTRING( registration_number, 8 ) ) AS UNSIGNED )) AS max_number_student
											FROM students WHERE registration_number like '%$where%'";
                        $feedback = $this->db->query($check_regno)->row();
                        $max_number = $feedback->max_number_student;
                        $a = strlen($max_number);
                        if ($max_number == '') {
                            $number_before = '0000';
                        } elseif ($a == 1) {
                            $number_before = '000';
                        } elseif ($a == 2) {
                            $number_before = '00';
                        } elseif ($a == 3) {
                            $number_before = '0';
                        } else {
                            $number_before = '';
                        }
                        $max_number_student = $max_number + 1;
                        $student_membership_number = 'TIOB' . $where . $number_before . $max_number_student;

                        $user_identity_number = $uid;
                        #CHECK AGAIN IF NUMBER EXISTS IN DATABASE
                        $unique = check_student($uid = null, $student_membership_number);
                        if (is_null($unique)) {
                            $student_membership_number = $student_membership_number;
                            $flag = true;
                        } else {
                            $student_membership_number = '';
                            $flag = false;
                        }

                    }

                    #---------------------------------------------------------------------------
                    #       End to  generate registration number
                    #---------------------------------------------------------------------------

                    $update_student_records = array(
                        'registration_number' => $student_membership_number,
                        'member_type' => member_type($this->data['member_details']->member_type),
                    );

                    $copy_student_records = array(
                        'registration_number' => $student_membership_number,
                        'admission_number' => $this->data['member_details']->admission_number,
                        'first_name' => $this->data['member_details']->FirstName,
                        'other_names' => $this->data['member_details']->MiddleName,
                        'surname' => $this->data['member_details']->LastName,
                        'cooperate' => $this->data['member_details']->cooperate,
                        'entry_year' => $this->data['member_details']->AYear,
                        'gender' => $this->data['member_details']->Gender,
                        'dob' => $this->data['member_details']->dob,
                        'marital_status' => $this->data['member_details']->marital_status,
                        'email' => $this->data['member_details']->Email,
                        'profile_avatar' => $this->data['member_details']->photo,
                        'mobile' => $this->data['member_details']->Mobile1,
                        'nationality' => get_country($this->data['member_details']->Nationality),
                        'member_type' => $this->data['member_details']->member_type,
                        'application_type' => $this->data['member_details']->application_type,
                        'programme_id' => $pcode1,
                        'updated_at' => $this->data['member_details']->createdon,
                        'level' => $this->data['member_details']->level,
                        'user_id' => $user_identity_number,

                    );

                    $query = '';

                    if (($student_membership_number && $status_receive == 9) || ($student_membership_number && $status_receive == 8)) {

                        $query = $this->member_model->insert_student_records($copy_student_records, $update_student_records, $user_identity_number);
                    } elseif ($student_membership_number) {
                        $query = $this->member_model->insert_student_records($copy_student_records);
                    }
                    if ($query) {
                        //  echo $user_identity_number;echo $appid;echo $status_receive;
                        $this->db->insert('notify_tmp',
                            array(
                                'status'=>$status_receive,
                                'type' => 'FEED',
                                'data' => json_encode(array('applicant_id' => $appid, 'user_id' => $user_identity_number, 'resend' => 1))
                            )
                        );
                        $last_row = $this->db->insert_id();
                        execInBackground('response send_notification ' . $last_row);
                        $this->session->set_flashdata('message', show_alert('Information updated successfully', 'success'));
                        redirect('member_list/', 'refresh');
                    } else {
                        $this->session->set_flashdata('message', show_alert('This student is already a member', 'warning'));
                        redirect('applicant_list/', 'refresh');

                    }
                }
            }

            
        }
    }

    function member_registration_form()
    {

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'register/', 'title' => 'Member informations');

        $this->load->model('member_model');
        $key = stripslashes($_GET['Code']);
        $flag = stripslashes($_GET['q']);
        if (!is_null($key)) {
            $check = $this->member_model->search_member($key, $flag)->row();
            if ($check) {
                $this->data['member_info'] = $check;
            } else {
                $this->session->set_flashdata('message', show_alert('Sorry, your search key does not match any information in our database. Retry again!', 'info'));
                redirect('register', 'refresh');
                $this->data['active_menu'] = 'applicant_list';
                $this->load->view('template', $this->data);
            }
        }
        if ($flag == 1) {
            $this->data['active_menu'] = 'member_list';
        } else {
            $this->data['active_menu'] = 'applicant_list';
        }
        $this->data['content'] = 'member/registration_form';
        $this->load->view('template', $this->data);
    }

    function set_programme()
    {

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'register/', 'title' => 'Change programme');

        $this->load->model('member_model');
        $key = stripslashes($_GET['Code']);
        $flag = stripslashes($_GET['q']);
        if (!is_null($key)) {
            $check = $this->member_model->search_member($key, $flag)->row();
            if ($check) {
                $this->data['member_info'] = $check;
            }
        }
        $this->data['active_menu'] = 'applicant_list';
        $this->data['content'] = 'member/set_programme';
        $this->load->view('template', $this->data);
    }

    function candidate_programme()
    {

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'register/', 'title' => 'Change programme');

        $this->load->model('member_model');
        $key = stripslashes($_GET['Code']);
        $flag = stripslashes($_GET['q']);
        if (!is_null($key)) {
            $check = $this->member_model->get_member_programme($key, $flag)->row();
            if ($check) {
                $this->data['member_info'] = $check;
            }
        }
        $this->data['active_menu'] = 'member_list';
        $this->data['content'] = 'member/candidate_programme';
        $this->load->view('template', $this->data);
    }


    function change_programme($id)
    {

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'register/', 'title' => 'Change programme');

        $this->load->model('member_model');
        $pid = trim($this->input->post('programme'));
        if ($pid === '1') {
            $program_data = array(
                'choice1' => 1001
            );
            $application_data = array(
                'application_type' => $pid
            );
        } elseif ($pid === '2') {
            $program_data = array(
                'choice1' => 1002,
            );
            $application_data = array(
                'application_type' => $pid
            );
        } else {
            $program_data = array(
                'choice1' => 1003,
            );
            $application_data = array(
                'application_type' => $pid
            );
        }

        $this->member_model->change_programme($program_data, $id);
        $this->member_model->change_applicationtype($application_data, $id);
        $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
        redirect('applicant_list/', 'refresh');
    }


    function change_member_programme($id)
    {

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'register/', 'title' => 'Change programme');

        $this->load->model('member_model');
        $pid = trim($this->input->post('programme'));
        if ($pid === '1') {
            $program_data = array(
                'programme_id' => 1001
            );

        } elseif ($pid === '2') {
            $program_data = array(
                'programme_id' => 1002,
            );

        } else {
            $program_data = array(
                'programme_id' => 1003,
            );

        }

        $this->member_model->change_member_programme($program_data, $id);
        //$this->member_model->change_applicationtype($application_data, $id);
        $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
        redirect('member_list/', 'refresh');
    }

    function myprofile($id = null)
    {

        $current_user = current_user();
        $this->form_validation->set_rules('firstname', 'First Name', 'required');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'regex_match[/^[0-9]{12}$/]');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');


//        $this->form_validation->set_rules('campus', 'Campus', 'required');
//        $this->form_validation->set_rules('dob', 'Birth Date', 'required|valid_date');
//        $this->form_validation->set_rules('nationality', 'Nationality', 'required');
//        $this->form_validation->set_rules('disability', 'Disability', 'required');
//        $this->form_validation->set_rules('disability', 'Disability', 'required');
//        $this->form_validation->set_rules('birth_place', 'Place of Birth', 'required');
//        $this->form_validation->set_rules('marital_status', 'Marital Status', 'required');
//        $this->form_validation->set_rules('residence_country', 'Country of Residence', 'required');

        /*if ($this->APPLICANT->entry_category == 2) {
                $this->form_validation->set_rules('form6_index', 'Form VI Index', 'required|is_unique_edit[application.form6_index.' . $this->APPLICANT->id . ']');
            } else if ($this->APPLICANT->entry_category == 3) {
                $this->form_validation->set_rules('diploma_number', 'Certificate Number', 'required');
            } else if ($this->APPLICANT->entry_category == 4) {
                $this->form_validation->set_rules('diploma_number', 'Diploma Number', 'required');
            }*/


        if ($this->form_validation->run() == true) {
            $array_data = array(
                'FirstName' => ucfirst(trim($this->input->post('firstname'))),
                'MiddleName' => ucfirst(trim($this->input->post('middlename'))),
                'LastName' => ucfirst(trim($this->input->post('lastname'))),
                'Gender' => trim($this->input->post('gender')),
                'entry_category' => trim($this->input->post('entry_type')),
                'Disability' => trim($this->input->post('disability')),
                'Nationality' => trim($this->input->post('nationality')),
                'birth_place' => trim($this->input->post('birth_place')),
                'marital_status' => trim($this->input->post('marital_status')),
                'Mobile1' => trim($this->input->post('mobile')),
                'Email' => trim($this->input->post('email')),
                'residence_country' => trim($this->input->post('residence_country')),
                'dob' => format_date(trim($this->input->post('dob'))),
                'modifiedon' => date('Y-m-d H:i:s'),
                'modifiedby' => $current_user->id
            );
            /* if($this->APPLICANT->entry_category == 2){
                 $array_data['form6_index'] = trim($this->input->post('form6_index'));
             }else if($this->APPLICANT->entry_category == 3 || $this->APPLICANT->entry_category == 4){
                 $array_data['diploma_number'] = trim($this->input->post('diploma_number'));
             }*/
            $register = $this->applicant_model->update_applicant($array_data, array('id' => $this->APPLICANT->id));
            if ($register) {

                $student_data=array(
                    'first_name'=>ucfirst(trim($this->input->post('firstname'))),
                    'other_names'=>ucfirst(trim($this->input->post('middlename'))),
                    'surname'=>ucfirst(trim($this->input->post('lastname'))),
                    'mobile'=>trim($this->input->post('mobile')),
                    'email'=>trim($this->input->post('email')),
                );

                $this->db->where('user_id', $current_user->id);
                $this->db->update('students', $student_data);


                $additional_data = array(
                    'firstname' => $array_data['FirstName'],
                    'lastname' => $array_data['LastName'],
                    'email'=>$array_data['Email'],
                    'phone'=>$array_data['Mobile1']
                );

                $this->ion_auth_model->update($current_user->id, $additional_data);


                $this->session->set_flashdata('message', show_alert('Information saved successfully!!', 'success'));
                redirect('myprofile/' . $id, 'refresh');
            }

        }

        $this->data['gender_list'] = $this->common_model->get_gender()->result();
        $this->data['campus_list'] = $this->common_model->get_campus()->result();
        $this->data['disability_list'] = $this->common_model->get_disability()->result();
        $this->data['nationality_list'] = $this->common_model->get_nationality()->result();
        $this->data['marital_status_list'] = $this->common_model->get_marital_status()->result();
        $this->data['active_menu'] = 'profile';
        $this->data['content'] = 'member/myprofile';
        $this->load->view('template', $this->data);
    }

    public function delete_member($id)
    {
        $this->member_model->delete_member($id);
        $this->data['active_menu'] = 'member_process';
        $this->session->set_flashdata('message', show_alert('One record deleted', 'success'));
        redirect(site_url() . '/member/cooperate_member');
    }

    public function delete_fellow($id)
    {
        $this->member_model->delete_fellow_member($id);
        $this->data['active_menu'] = 'member_process';
        $this->session->set_flashdata('message', show_alert('One record deleted', 'success'));
        redirect(site_url() . '/member/fellow_member');
    }

    function renotify($id = null)
    {
        $current_user = current_user();

        if (isset($_GET['resend'])) {
            $getvalue = $this->db->query("select id,user_id,Email from application where user_id in (select user_id from students)")->result();
            foreach ($getvalue as $key => $value) {
                $appid = $value->id;
                $uid = $value->user_id;

                $this->db->insert('notify_tmp',
                    array(
                        'type' => 'FEED',
                        'data' => json_encode(array('applicant_id' => $appid, 'user_id' => $uid, 'resend' => 1))
                    )
                );
                $last_row = $this->db->insert_id();
                execInBackground('response send_notification ' . $last_row);
                //  $this->session->set_flashdata('message', show_alert('Feedback sent to the email', 'success'));
                //  redirect('member_list/', 'refresh');
                // }

            }
            exit;

            $this->data['middle_content'] = 'applicant/applicant_activate';
            $this->data['content'] = 'applicant/home';
            $this->load->view('public_template', $this->data);
        }

    }


    public function register_existing_member()
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Receive payments');
        $this->data['bscrum'][] = array('link' => 'Existing Member/', 'title' => 'Annual payments');

        $this->form_validation->set_rules('regno', 'Registration Number', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required');


        if ($this->form_validation->run() == true) {

            $regno = $this->input->post('regno');
            $unique = check_student($uid = null, $regno);
            if (is_null($unique)) {
                $this->session->set_flashdata('message', show_alert('Registration Number does not exist in a member list, check it carefully and try again!', 'warning'));
                redirect('register_existing_member', 'refresh');
            } else {
                $uid = $this->db->query("select * from students where registration_number='$regno'")->row()->user_id;

                $applicant = $this->db->query("select * from users where id='$uid'")->row()->applicant_id;
                $array_data = array(
                    'user_id' => $uid,
                    'applicant_id' => $applicant,
                    'amount' => trim($this->input->post('amount')),
                    'receipt' => generatePIN(6),
                    'pay_method' => trim($this->input->post('payment_type')),
                    'academic_year' => $this->common_model->get_academic_year()->row()->AYear

                );
                $save = $this->db->insert('temp_annual_fees', $array_data);
                if ($save) {
                    $statement_data = array(
                        'user_id' => $uid,
                        'amount' => trim($this->input->post('amount')),
                        'receipt' => generatePIN(6),
                        'timestamp' => date('Y-m-d H:i:s'),
                        'createdon' => date('Y-m-d H:i:s'),
                        'pay_method' => trim($this->input->post('payment_type')),
                        'academic_year' => $this->common_model->get_academic_year()->row()->AYear


                    );
                    $this->db->insert('fee_statement', $statement_data);

                    $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
                    redirect('view_recorded_data', 'refresh');
                } else {
                    $this->session->set_flashdata('message', show_alert('Failed to save information, no recored found for this candidate contact the candidate to create account in the system', 'warning'));
                    redirect('register_existing_member', 'refresh');
                }
            }
        }

        $this->data['active_menu'] = 'feesetup';
        $this->data['content'] = 'member/register_existing_member';
        $this->load->view('template', $this->data);
    }


    public function import_examination_payments()
    {

        $current_user = current_user();
        $active_year = $this->common_model->get_academic_year()->row()->AYear;
        $semester = $this->common_model->get_academic_year()->row()->semester;

        $this->form_validation->set_rules('regno', 'Registration Number', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required');


        if ($this->form_validation->run() == true) {

            $regno = $this->input->post('regno');

            $unique = check_student($uid = null, $regno);
            if (is_null($unique)) {
                $this->session->set_flashdata('message', show_alert('Registration Number does not exist in a member list, check it carefully and try again!', 'warning'));
                redirect('register_existing_member', 'refresh');
            } else {
                $uid = $this->db->query("select * from students where registration_number='$regno'")->row()->user_id;

                $applicant = $this->db->query("select * from users where id='$uid'")->row()->applicant_id;

//                $check_student_registered = $this->db->query("select * from temp_exam_registered where registration_number='$regno' ")->result();
//                if(is_null($check_student_registered)) {

                $array_data = array(
                    'user_id' => $uid,
                    'applicant_id' => $applicant,
                    'amount' => trim($this->input->post('amount')),
                    'receipt' => generatePIN(6),
                    'pay_method' => trim($this->input->post('payment_type')),
                    'academic_year' => $this->common_model->get_academic_year()->row()->AYear,
                    'session' => $this->common_model->get_academic_year()->row()->semester

                );
                $save = $this->db->insert('examinations_payment', $array_data);
                if ($save) {
                    $statement_data = array(
                        'user_id' => $uid,
                        'amount' => trim($this->input->post('amount')),
                        'receipt' => generatePIN(6),
                        'timestamp' => date('Y-m-d H:i:s'),
                        'createdon' => date('Y-m-d H:i:s'),
                        'academic_year' => $this->common_model->get_academic_year()->row()->AYear,
                        // 'session' => $this->common_model->get_academic_year()->row()->semester

                    );
                    $this->db->insert('fee_statement', $statement_data);

                    $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
                    redirect('register_existing_member', 'refresh');
                } else {
                    $this->session->set_flashdata('message', show_alert('Failed to save information', 'warning'));
                    redirect('register_existing_member', 'refresh');
                }
//                }else{
//                    $this->session->set_flashdata('message', show_alert('Failed to save information, candidate has not registered for examinations yet..!', 'warning'));
//                    redirect('register_existing_member', 'refresh');
//                }
            }
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Receive payments');
        $this->data['bscrum'][] = array('link' => 'import/', 'title' => 'Exam Payments');

        $this->data['active_menu'] = 'feesetup';
        $this->data['content'] = 'member/import_examination_payments';
        $this->load->view('template', $this->data);
    }

    function member_change_status()
    {

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'register/', 'title' => 'Member informations');

        $this->load->model('member_model');
        $key = stripslashes($_GET['Code']);
        $flag = stripslashes($_GET['q']);
        if (!is_null($key)) {
            $check = $this->member_model->search_member_forstatus($key, $flag)->row();
            if ($check) {
                $this->data['member_info'] = $check;
            } else {
                $this->session->set_flashdata('message', show_alert('Sorry, your search key does not match any information in our database. Retry again!', 'info'));
                redirect('register', 'refresh');
                $this->data['active_menu'] = 'applicant_list';
                $this->load->view('template', $this->data);
            }
        }
        if ($flag == 1) {
            $this->data['active_menu'] = 'member_list';
        } else {
            $this->data['active_menu'] = 'applicant_list';
        }
        $this->data['active_menu'] = 'member_process';
        $this->data['content'] = 'member/member_change_status';
        $this->load->view('template', $this->data);
    }


    public function change_member_status_action()
    {

        $this->form_validation->set_rules('status', 'Status', 'required');
        $status_receive = trim($this->input->post('status'));
        if ($status_receive == 8) {
            $this->form_validation->set_rules('cooperate', 'Corporate', 'required');
        }

        if ($this->form_validation->run() == true) {

            $appid = stripcslashes($_GET['appID']);
            $uid = stripcslashes($_GET['id']);
            $this->load->model('member_model');
            $status_receive = trim($this->input->post('status'));
            $corporate = trim($this->input->post('cooperate'));

            if ($status_receive == 8) {

                $application_status = array(
                    'member_type' => 1,
                    'cooperate' => $corporate
                );
                $add = $this->member_model->change_member_status($application_status, $uid);

            } else {
                if ($status_receive == 4) {

                    $application_status = array(
                        'member_type' => '0',
                        'cooperate' => '0'
                    );
                    $add = $this->member_model->change_member_status($application_status, $uid);
                }
            }
            if($add){
                $this->session->set_flashdata('message', show_alert('Information updated successfully', 'success'));
                redirect('member_list/', 'refresh');
            }else{
                $this->session->set_flashdata('message', show_alert('Failed to save information', 'warning'));
                redirect('member_list/', 'refresh');
            }
        }
    }

    function search_member_form()
    {
        $current_user = current_user();
        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

//        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'search')) {
//            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
//            redirect(site_url('dashboard'), 'refresh');
//        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Search');
        $this->data['bscrum'][] = array('link' => 'search/', 'title' => 'Search member form');

        #get posted search key
        if (isset($_GET['key']) && $_GET['key'] != '') {
            $regno = $_GET['key'];
        } else {
            $regno = '';
        }

        #get student information
        $check = $this->data['student_info'] = $this->common_model->get_student($id = null, $regno)->result();
        if (!$check) {
            if ($regno == '') {
            } else {
                $this->data['message'] = show_alert('Sorry!!  Your search key does not match any information', 'warning');
            }
        }
        #get student results
        if ($regno) {
            $student_id = $this->db->query("select * from students where registration_number='$regno'")->row()->user_id;
            if ($student_id <> '' && $student_id != '') {
                $where = "WHERE 1 = 1 AND user_id='$student_id'";
                $sql = " SELECT * FROM application  $where ";
                $this->data['applicant_list'] = $this->db->query($sql)->result();
                //$this->data['serach_results'] = $student_results;
            } else {
                $this->data['message'] = show_alert('This student does not exist in our records', 'info');
            }
        } elseif ($regno <> '') {
            $this->data['message'] = show_alert('No result for your serach key', 'info');
        } else {

        }


        $this->data['active_menu'] = 'search_member';
        $this->data['content'] = 'member/search_member_form';
        $this->load->view('template', $this->data);
    }


    function member_fee_statement()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_USERS', 'profile')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'member_fee_statement/', 'title' => 'My Fee Statement');

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'billing';
        $this->data['content'] = 'member/member_fee_statement';
        $this->load->view('template', $this->data);

    }



}
