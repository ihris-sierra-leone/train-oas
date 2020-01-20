<?php

class Exam extends CI_Controller
{

    private $MODULE_ID = '';
    private $GROUP_ID = '';

    private $APPLICANT = false;
    private $APPLICANT_MENU = array();

    function __construct()
    {
        parent::__construct();


        $this->data['CURRENT_USER'] = current_user();


        $this->form_validation->set_error_delimiters('<div class="required">', '</div>');

        $this->data['title'] = 'Examination Officer';
        $this->APPLICANT = $this->applicant_model->get_applicant($this->data['CURRENT_USER']->applicant_id);
        if ($this->APPLICANT) {
            $this->data['APPLICANT'] = $this->APPLICANT;
            $this->data['APPLICANT_MENU'] = $this->APPLICANT_MENU = $this->applicant_model->get_examination_payments($this->APPLICANT->id);

        }
        $current_user_group = get_user_group();

        $tmp_group = get_user_group();
        $this->GROUP_ID = $this->data['GROUP_ID'] = $tmp_group->id;
        $this->MODULE_ID = $this->data['MODULE_ID'] = $tmp_group->module_id;
    }

    function is_examination_pay()
    {
        $current_user = current_user();
        $applicant_id = $this->APPLICANT->id;
        $ayear = $this->common_model->get_academic_year()->row()->AYear;
        $semester = $this->common_model->get_academic_year()->row()->semester;
        // $amount = $this->applicant_model->get_amount_paid($applicant_id,$ayear,$semester);
        $get_data = $this->db->query("SELECT SUM(amount) as amount,SUM(charges) as charges FROM examinations_payment WHERE applicant_id='$applicant_id' AND academic_year='$ayear' AND session='$semester' ")->row();
        $amount = $get_data->amount + $get_data->charges;
        $userid = $current_user->id;

        $regno = $this->db->query("SELECT * FROM students WHERE user_id='$userid' ")->row()->registration_number;

        $member = $this->db->query("SELECT * FROM students WHERE user_id='$userid' ")->row()->member_type;

        $programmeID = $this->db->query("SELECT * FROM students WHERE user_id='$userid' ")->row()->programme_id;

        $exam_fee = $this->db->query("SELECT * FROM exam_fee WHERE programmeID='$programmeID' AND member_category=$member ")->row()->amount;
        // exam_year,exam_semester
        $examNo = $this->db->query("SELECT COUNT(course_id) AS count FROM temp_exam_registered WHERE registration_number='$regno' AND exam_year='$ayear' AND exam_semester='$semester'")->row()->count;

        $total_amount = $exam_fee * $examNo;

        $selected = $this->db->query("SELECT COUNT(course_id) AS count2 FROM student_exam_registered WHERE registration_number='$regno' AND exam_year='$ayear' AND exam_semester='$semester'")->row()->count2;

        if ($selected <> 0) {

            $total2 = $selected * $exam_fee;

            $total = $total_amount + $total2;
        } else {

            $total = $total_amount;
        }

        //compare amount
        if (!empty($amount) && ($amount >= $total)) {
            $all = $this->db->query("SELECT * FROM temp_exam_registered WHERE registration_number='$regno' AND exam_year='$ayear' AND exam_semester='$semester'")->result();
            if ($all) {
                foreach ($all as $key => $valueData) {
                    // $eyear = $valueData->exam_year;
                    $data = array(
                        'exam_semester' => $valueData->exam_semester,
                        'exam_year' => $valueData->exam_year,
                        'coursecode' => $valueData->coursecode,
                        'course_id' => $valueData->course_id,
                        'center_id' => $valueData->center_id,
                        'registration_number' => $regno
                    );

                    $add = $this->exam_model->add_exam_registration($data);
                    if ($add) {

                        //delete Data from temp collection
                        $this->db->where('registration_number', $regno);
                        $this->db->where('exam_year', $valueData->exam_year);
                        $this->db->delete('temp_exam_registered');
                    }
                }

                $this->session->set_flashdata('message', show_alert('Payment recognised, Please use menu at the left side to continue', 'success'));
                echo 1;
            } else {
                echo 0;
            }

        }
    }

    function publish()
    {
        $current_user = current_user();
        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

//        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'membership_fee')) {
//            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
//            redirect(site_url('dashboard'), 'refresh');
//        }

        #get code and action
        if (isset($_GET['code']) && isset($_GET['action'])) {
            $code = $_GET['code'];
            $action = $_GET['action'];
            $data = array('published' => $action);


            if (!is_null($code) && !is_null($action)) {
                $academic_year = $this->common_model->get_academic_year()->row()->AYear;
                $exam_session = $this->common_model->get_academic_year()->row()->semester;

                $sql = "SELECT registration_number FROM students WHERE programme_id='$code'";
                $all_students = $this->db->query($sql)->result();
                foreach ($all_students as $key => $value) {
                    $regno = $value->registration_number;
                    $this->exam_model->publish($data, $regno, $academic_year, $exam_session);

                }
                if ($action == 1) {
                    $this->session->set_flashdata('message', show_alert('Results Published successfully !!', 'success'));
                } else {
                    $this->session->set_flashdata('message', show_alert('Results Unpublished successfully !!', 'success'));

                }
            }
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examinations');
        $this->data['bscrum'][] = array('link' => 'publish/', 'title' => 'Publish Exams');
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/publish';
        $this->load->view('template', $this->data);
    }

    function grade_book()
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


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'grade_book')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        // validate form input
        $this->form_validation->set_rules('post_data', 'post_data', 'required');
        $this->form_validation->set_rules('course', 'Course', 'required');


        if ($this->form_validation->run() == true) {
            $code_course = $this->input->post('course');
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
                $data = array();
                $courseError = array();
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
                    if (trim($row['A']) <> '' && trim($row['B']) <> '') {
                        $data['registration_number'] = $row['A'];
                        $data['course'] = $code_course;
                        $data['score_marks'] = $row['B'];
                        $data['academic_year'] = $this->common_model->get_academic_year()->row()->AYear;
                        $data['exam_session'] = $this->common_model->get_academic_year()->row()->semester;
                        $data['Recorder'] = $current_user->username;
                        //1. test if regno exist

                        $regno = check_student($id = null, $data['registration_number']);
//                        echo $regno;exit;
                        if (!$regno) {
                            $courseError[$regno] = $data['registration_number'] . ' STUDENT DOES NOT EXIST IN THE SYSTEM';
                        }
                        //2. then test if coursecode exist

                        // kozi nakuja nayo kutoka kwenye input

                        // if 1 & 2 is true, proceed to import
                        if ($regno) {
                            $where = " WHERE registration_number='" . $data['registration_number'] . "' AND academic_year='" . $data['academic_year'] . "' AND course='$code_course' AND exam_session='" . $data['exam_session'] . "'  ";
                            $feedback = $this->exam_model->import_results($data, $where);
//                            echo $feedback;exit;
                            if (!$feedback) {
                                $courseError[$regno] = $data['registration_number'] . ' DUPLICATE RESULTS';
                            }
                        }
                    }
                }

                if ($courseError) {
                    $courseError['final'] = "PLEASE REVIEW YOUR INFORMATION";
                    $this->data['result_error'] = $courseError;
                    unlink('./uploads/temp/' . $dest_name);
                } elseif ($feedback) {
                    $this->session->set_flashdata('message', show_alert('Results successfully uploaded !!', 'success'));
                    unlink('./uploads/temp/' . $dest_name);
                }
            }
        }


        $this->data['upload_error'] = $upload_error;
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'grade_book/', 'title' => 'Grade Book');
        $this->data['course_list'] = $this->common_model->get_course()->result();
        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/grade_book';
        $this->load->view('template', $this->data);
    }

    function exam_registration()
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


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'exam_registration')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        // validate form input
        $this->form_validation->set_rules('center', 'Center', 'required');
        $this->form_validation->set_rules('courses', 'Course', 'valid_multiselect');
        if ($this->form_validation->run() == true) {
            if (isset($_FILES['userfile']['name']) && empty($_FILES['userfile']['name'])) {
                $upload_error = '<div class="required">You must upload receipt attachment.</div>';
                $excel_upload = FALSE;
            } elseif (isset($_FILES['userfile']['name']) && (get_file_extension($_FILES['userfile']['name']) != 'doc'
                    && get_file_extension($_FILES['userfile']['name']) != 'docx' && get_file_extension($_FILES['userfile']['name']) != 'jpg'
                    && get_file_extension($_FILES['userfile']['name']) != 'png' && get_file_extension($_FILES['userfile']['name']) != 'pdf')
            ) {
                $upload_error = '<div class="required">File uploaded must be in doc,docx,pdf,jpeg or png format.</div>';
                $excel_upload = FALSE;
            }

            if ($excel_upload == TRUE) {
                $dest_name = time() . 'receipt.' . get_file_extension($_FILES['userfile']['name']);
                $data['registration_number'] = $this->common_model->get_student($current_user->id)->row()->registration_number;
                $data['examination_center'] = $this->input->post('center');;
                $data['exam_session'] = $this->common_model->get_exam_session()->id;
                $data['academic_year'] = $this->common_model->get_academic_year()->row()->AYear;
                $data['courses'] = json_encode($this->input->post('courses'));
                $data['user_id'] = $current_user->id;
                if (move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/attachment/' . $dest_name)) {
                    // set file path
                    $file = '../uploads/attachment/' . $dest_name;
                    $data['receipt'] = $file;
                }
                $this->exam_model->exam_registration($data);
                $this->session->set_flashdata('message', show_alert('Examination Registration done successfully !!', 'success'));
                redirect('exam_registration', 'refresh');
            }

        }


        $this->data['upload_error'] = $upload_error;
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'exam_registration/', 'title' => 'Examination Registration');
        $this->data['center_list'] = $this->common_model->get_exam_center()->result();
        $this->data['course_list'] = $this->common_model->get_courses()->result();
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/exam_registration';
        $this->load->view('template', $this->data);
    }

    function module_results()
    {
        if (!$this->ion_auth->logged_in()) {
            redirect('auth', 'refresh');
        }


        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'module_results')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'module_results/', 'title' => 'Module Results');
        // $regno = $this->common_model->get_student($current_user->id)->row()->registration_number;

        $regno = $this->db->get_where('students', array('user_id' => $current_user->id))->row()->registration_number;
        // echo $regno;exit;
        $this->data['results'] = $this->exam_model->get_student_results_by_regno($regno)->result();
        // var_dump($this->data['results']);exit;
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/module_results';
        $this->load->view('template', $this->data);
    }

    function exam_results()
    {
        $current_user = current_user();
        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'exam_results')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'exam_results/', 'title' => 'Exam Results');

        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/exam_results';
        $this->load->view('template', $this->data);
    }

    function search()
    {
        $current_user = current_user();
        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'search')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'search/', 'title' => 'Exam Results');

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
            $student_results = $this->exam_model->get_student_results($regno)->result();
            if ($student_results <> '' && $student_results != '') {
                $this->data['results'] = $student_results;
            } else {
                $this->data['message'] = show_alert('This student has no exam result this year', 'info');
            }
        } elseif ($regno <> '') {
            $this->data['message'] = show_alert('No result for your serach key', 'info');
        } else {

        }


        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/search';
        $this->load->view('template', $this->data);
    }

    function exam_register($id = null)
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'exam_register')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'exam_register/', 'title' => 'Exam Register');

        $this->load->model('exam_model');
        if (!is_null($id)) {
            {
                $check = $this->exam_model->registered_exam_list($id)->row();
                if ($check) {
                    $this->data['exam_info'] = $check;
                }

                $array_data = array(
                    'exam_semester' => $this->data['exam_info']->semester,
                    'coursecode' => $this->data['exam_info']->coursecode,
                    'course_id' => $this->data['exam_info']->coursecode,
                    'registration_number' => $this->common_model->get_student($current_user->id)->row()->registration_number
                );

                $add = $this->exam_model->add_exam_registration($array_data);
                if ($add) {
                    $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
                    redirect('registered_exam_list', 'refresh');
                } else {
                    $this->data['message'] = show_alert('Fail to save Information', 'info');
                }
            }

            $this->data['active_menu'] = 'exam';
            $this->data['content'] = 'exam/registered_exam_list';
            $this->load->view('template', $this->data);
        }
    }

    function exam_fee($id = null)
    {
        $current_user = current_user();

        $this->load->library('pagination');

        $where = ' WHERE 1=1 ';

        if (isset($_GET['name']) && $_GET['name'] != '') {
            $where .= " AND first_name LIKE '%" . $_GET['name'] . "%' ";
        }

        $sql = " SELECT * FROM students $where ";
        $sql2 = " SELECT COUNT(id) as counter FROM students $where ";

        $config["base_url"] = site_url('exam_fee/');

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

        $this->data['member_list'] = $this->db->query($sql . " ORDER BY registration_number ASC LIMIT $page," . $config["per_page"])->result();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Member');
        $this->data['bscrum'][] = array('link' => 'exam_fee/', 'title' => 'Exam Fee');
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/exam_fee';
        $this->load->view('template', $this->data);
    }

    function select_exam()
    {

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'select_exam')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->load->library('pagination');
        $where = " WHERE 1=1";

        if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  name LIKE '%" . $_GET['key'] . "%'";
        }

        $sql = " SELECT * FROM exam_register  $where ";
        $sql2 = " SELECT count(id) as counter FROM exam_register  $where ";

        $config["base_url"] = site_url('select_exam/');
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


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Exams');
        $this->data['bscrum'][] = array('link' => 'select_exam/', 'title' => 'Exam Selection');
        $this->load->model('timetable_model');
        $this->data['exam_list'] = $this->timetable_model->get_exam_register()->result();
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/select_exam';
        $this->load->view('template', $this->data);

    }



    function register_exam($id = null)
    {





        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Exams');
        $this->data['bscrum'][] = array('link' => 'register_exam/', 'title' => 'Exam Register');

        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/register_exam';
        $this->load->view('template', $this->data);
    }

    function registered_exam_list($id = null)
    {
        $ayear = $this->common_model->get_academic_year()->row()->AYear;
        $semester = $this->common_model->get_academic_year()->row()->semester;
        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'registered_exam_list')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }
        // $this->form_validation->set_rules('course', 'Course', 'required');
        //$this->form_validation->set_rules('center', 'Center', 'required');
        $id = $this->input->post('course');
        $cid = $this->input->post('center');
//        if ($id == '' || $cid == '') {
//
//            $this->session->set_flashdata('message', show_alert('Course or Center field can not be empty', 'warning'));
//            redirect('select_exam', 'refresh');
//        }else{


        $this->load->model('exam_model');
        if (!is_null($id)) {
            $check = $this->exam_model->get_courses($id)->row();
            if ($check) {
                $this->data['exam'] = $check;
            }
            $academic_year = $this->common_model->get_academic_year()->row();;
            $array_data = array(
                'exam_semester' => $academic_year->semester,
                'exam_year' => $academic_year->AYear,
                'coursecode' => $this->data['exam']->code,
                'course_id' => $id,
                'center_id' => $cid,
                'registration_number' => $this->common_model->get_student($current_user->id)->row()->registration_number
            );

            $add = $this->exam_model->temp_exam_registration($array_data);
            if ($add) {
                $this->session->set_flashdata('message', show_alert('Information saved successfully', 'success'));
                redirect('select_exam', 'refresh');

            } else {
                $this->data['message'] = show_alert('Fail to save Information, You have already register
                    for this course', 'info');
            }
        }
        //}


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'registered_exam_list/', 'title' => 'Exam Register');
        $reg_no = $this->common_model->get_student($current_user->id)->row()->registration_number;
        $this->data['registered_list'] = $this->exam_model->get_exam_registered($reg_no, $ayear, $semester)->result();
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/registered_exam_list';
        $this->load->view('template', $this->data);
    }

    function remove_course()
    {
        $courseID = stripcslashes($_GET['Code']);
        $regno = stripcslashes($_GET['iD']);
        $data = $this->common_model->remove_course($courseID, $regno);
        if ($data) {
            $this->session->set_flashdata('message', show_alert('Course removed successfully from list', 'warning'));
            redirect('select_exam', 'refresh');

        }
    }

    function delete_course()
    {
        $courseID = stripcslashes($_GET['Code']);
        $regno = stripcslashes($_GET['iD']);
        $data = $this->common_model->delete_course($courseID, $regno);
        if ($data) {
            $this->session->set_flashdata('message', show_alert('Course removed successfully from list', 'warning'));
            redirect('registered_exam_list', 'refresh');

        }
    }

    function delete_selection($id)
    {
        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'delete_selection/', 'title' => 'User List');

        $this->exam_model->delete_selection($id);
        $this->data['active_menu'] = 'exam';
        $this->session->set_flashdata('message', show_alert('One record deleted', 'success'));
        redirect(site_url() . '/registered_exam_list');
    }

    function timetable()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'timetable')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'timetable/', 'title' => 'Timetable');

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/timetable';
        $this->load->view('template', $this->data);
    }

    function transcript_report()
    {
        $current_user = current_user();
        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

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
                $sql = " SELECT * FROM students  $where ";
                $this->data['applicant_list'] = $this->db->query($sql)->result();

                //get results;
                $student_results = $this->exam_model->get_results($regno)->result();
                if ($student_results <> '' && $student_results != '') {
                    $this->data['results'] = $student_results;
                }
            } else {
                $this->data['message'] = show_alert('This student does not exist in our records', 'info');
            }
        } elseif ($regno <> '') {
            $this->data['message'] = show_alert('No result for your serach key', 'info');
        } else {

        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'transcript_report/', 'title' => 'Transcript Report');

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'transcript';
        $this->data['content'] = 'exam/transcript_report';
        $this->load->view('template', $this->data);
    }

    function popup_student_transcript($id)
    {
        $id = decode_id($id);
        //echo $id;exit();
        $APPLICANT = $this->applicant_model->get_member_transcript($id);
        // var_dump($APPLICANT);exit();
        if ($APPLICANT) {
            $this->data['APPLICANT'] = $APPLICANT;
            $user_id = $this->data['APPLICANT']->user_id;
            //var_dump($this->data['APPLICANT']);
            $reg_no = $this->common_model->get_student($user_id)->row()->registration_number;
            $this->data['results'] = $this->exam_model->get_results($reg_no)->result();
            $this->load->view('exam/popup_student_transcript', $this->data);
        } else {
            echo show_alert('This request did not pass our security checks.', 'info');
        }

    }


    function popup_student_remark($id)
    {
        $id = decode_id($id);
        //echo $id;exit();
        $APPLICANT = $this->applicant_model->get_member_transcript($id);
        // var_dump($APPLICANT);exit();
        if ($APPLICANT) {
            $this->data['APPLICANT'] = $APPLICANT;
            $user_id = $this->data['APPLICANT']->user_id;
            //var_dump($this->data['APPLICANT']);
            $reg_no = $this->common_model->get_student($user_id)->row()->registration_number;
            $this->data['results'] = $this->exam_model->get_results($reg_no)->result();
            $this->load->view('exam/popup_student_remark', $this->data);
        } else {
            echo show_alert('This request did not pass our security checks.', 'info');
        }

    }


    function popup_transcript_subjects($id)
    {
        $id = decode_id($id);
        //echo $id;exit();
        $APPLICANT = $this->applicant_model->get_member_transcript($id);
        // var_dump($APPLICANT);exit();
        if ($APPLICANT) {
            $this->data['APPLICANT'] = $APPLICANT;
            $user_id = $this->data['APPLICANT']->user_id;
            //var_dump($this->data['APPLICANT']);
            $reg_no = $this->common_model->get_student($user_id)->row()->registration_number;
            $this->data['results'] = $this->exam_model->get_results($reg_no)->result();
            $this->load->view('exam/popup_transcript_subjects', $this->data);
        } else {
            echo show_alert('This request did not pass our security checks.', 'info');
        }

    }

    function board_report()
    {
        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'board_report')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'board_report/', 'title' => 'Board Report');

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/board_report';
        $this->load->view('template', $this->data);
    }

    function get_board_report()
    {

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'display_board_report/', 'title' => 'Board Report');

        $this->form_validation->set_rules('board_report', 'Search value', 'required');

        $this->load->model('exam_model');
        $key = $this->input->post('board_report');
        $cours[] = array();
        $regno = array();
        $i = 0;
        $score[] = array();
        $s = 0;
        $pcode = stripslashes($_GET['Code']);
        if (!is_null($pcode)) {
            $key = $pcode;
        }

        $this->data['programme_info'] = $this->common_model->get_programme($key)->result();

        if ($this->form_validation->run() == false) {
            if (!is_null($key)) {
                $check = $this->exam_model->get_board_report($key)->result();

                if ($check) {
                    $this->data['report_info'] = $check;
                    foreach ($this->data['report_info'] as $key => $value) {
                        $cours[$i] = $value->course;
                        $regno [$value->registration_number] = $value->registration_number;
                        $i++;
                    }
                    $this->data['course_names'] = $cours;

                    $this->data['report_info'] = $check;
                    foreach ($this->data['report_info'] as $key => $value) {
                        $score[$s] = $value->score_marks;
                        $s++;
                    }
                    $this->data['score_marks'] = $score;
                    $this->data['report_info'] = $regno;
                    $sql = "SELECT shortname FROM courses WHERE programme_id =$key";
                    $this->data['course_header'] = $this->db->query($sql)->result();

                } else {
                    $this->session->set_flashdata('message', show_alert('Sorry, your search key does not match any information in our database. Retry again!', 'info'));
                    redirect('board_report', 'refresh');
                    $this->data['active_menu'] = 'exam';
                    $this->load->view('template', $this->data);
                }

            }
        } else {
            $this->session->set_flashdata('message', show_alert('Search by Reg # or First Name Or Last Name. Retry again!', 'warning'));
            redirect('register', 'refresh');
        }

        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/display_board_report';
        $this->load->view('template', $this->data);

    }

    function cumulative_report()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'cumulative_report')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'cumulative_report/', 'title' => 'Cumulative Report');

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/cumulative_report';
        $this->load->view('template', $this->data);
    }

    function graduate_report()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'graduate_report')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'graduate_report/', 'title' => 'Graduate Report');

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/graduate_report';
        $this->load->view('template', $this->data);
    }

    function membership_fee()
    {
        $current_user = current_user();


        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }


        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'membership_fee')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }


        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'membership_fee/', 'title' => 'Billing');
        $this->data['name'] = 'Juma Lungo';

        $this->data['school_list'] = $this->common_model->get_college_school()->result();
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/membership_fee';
        $this->load->view('template', $this->data);
    }

    function audit_trail()
    {
        $current_user = current_user();

        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

//        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_MEMBERS', 'graduate_report')) {
//            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
//            redirect(site_url('dashboard'), 'refresh');
//        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'register/', 'title' => 'Audit Trail');

        $this->data['exam_changes'] = $this->exam_model->audit_trail()->result();
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/audit_trail';
        $this->load->view('template', $this->data);
    }

    function fakeExamPay()
    {
        $amount_required = 120000;
        $ayear = $this->APPLICANT->AYear;
        $member_type = $this->APPLICANT->member_type;

        if ($this->APPLICANT->application_type == 3) {
            $amount_required = APPLICATION_FEE_POSTGRADUATE;
        }
        $array = array(
            'msisdn' => '255712765538',
            'reference' => REFERENCE_START . $this->APPLICANT->user_id,
            'applicant_id' => $this->APPLICANT->id,
            'user_id' => $this->APPLICANT->user_id,
            'timestamp' => date('Y-m-d H:i:s'),
            'receipt' => generatePIN(8) . $this->APPLICANT->id,
            'amount' => $amount_required,
            'fee_category' => 2,
            'createdon' => date('Y-m-d H:i:s'),
            'academic_year' => $ayear
        );

        $this->db->insert('examinations_payment', $array);

    }

    function fakeAnnualPay()
    {
        $amount_required = 30000;
        $ayear = $this->APPLICANT->AYear;
        $member_type = $this->APPLICANT->member_type;

        if ($this->APPLICANT->application_type == 3) {
            $amount_required = APPLICATION_FEE_POSTGRADUATE;
        }
        $array = array(
            'msisdn' => '255712765538',
            'reference' => REFERENCE_START . "" . generatePIN(1) . "" . $this->APPLICANT->id,
            'applicant_id' => $this->APPLICANT->id,
            'user_id' => 125,
            'timestamp' => date('Y-m-d H:i:s'),
            'receipt' => generatePIN(8) . $this->APPLICANT->id,
            'amount' => $amount_required,
            'academic_year' => 2018
        );
        $this->db->insert('temp_annual_fees', $array);

    }

    function is_annual_paid()
    {
        $current_user = current_user();
        $userid = $current_user->id;
        $acyear = $this->common_model->get_academic_year()->row()->AYear;
        $amount_required = 30000;
        $amount = $this->applicant_model->get_annual_paid($this->APPLICANT->id);
        if ($amount >= $amount_required) {
            $Data = $this->db->query("SELECT * FROM temp_annual_fees WHERE user_id='$userid' AND academic_year='$acyear' ")->result();
            if ($Data) {
                foreach ($Data as $key => $value) {
                    $data = array(
                        'msisdn' => $value->msisdn,
                        'reference' => $value->reference,
                        'applicant_id' => $value->applicant_id,
                        'user_id' => $value->user_id,
                        'timestamp' => $value->timestamp,
                        'pay_method' => $value->pay_method,
                        'receipt' => $value->receipt,
                        'amount' => $value->amount,
                        'charges' => $value->charges,
                        'academic_year' => $value->academic_year
                    );
                    $aa = $this->exam_model->transer_data($data);
                    if ($aa) {
                        $this->db->where('user_id', $userid);
                        $this->db->where('academic_year', $acyear);
                        $this->db->delete('temp_annual_fees');
                    }
                }
                $this->session->set_flashdata('message', show_alert('Payment recognised, Please use menu at the left side to continue', 'success'));
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    function edit_score()
    {
        $current_user = current_user();
        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'search')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'search/', 'title' => 'Exam Results');

        $this->form_validation->set_rules('score_marks', 'Score', 'required|numeric|less_than[100]');
        $flag = trim($this->input->post('update'));

        #TO UPDATE
        $feedback = false;
        $student_results = '';
        if ($flag == 1 && $this->form_validation->run() == true) {
            $coz = trim($this->input->post('course'));
            $score_marks = trim($this->input->post('score_marks'));
            $regn = trim($this->input->post('regno'));
            $exam_session = trim($this->input->post('esession'));
            $academic_year = trim($this->input->post('ayear'));
            $data = array('score_marks' => $score_marks);
            $where = array('registration_number' => $regn,
                'academic_year' => $academic_year,
                'exam_session' => $exam_session,
                'course' => $coz
            );
            //var_dump($where); var_dump($data); exit;
            $feedback = $this->exam_model->import_results($data, $where);

        } else {
            #get regno and course code
            $regno = stripslashes($_GET['reg']);
            $coz_code = stripslashes($_GET['code']);
            $this->data['student_info'] = $this->common_model->get_student($id = null, $regno)->result();
            $student_results = $this->exam_model->get_results($regno, $coz_code)->row();
        }


        if ($student_results <> '' && $student_results != '' && $feedback == false) {
            $this->data['results'] = $student_results;
        } elseif ($feedback == true) {
            $this->session->set_flashdata("message", show_alert("Examination Score Updated Successful !!", 'success'));
            redirect(site_url('search'), 'refresh');
        } else {
            $this->data['message'] = show_alert('This student has no exam result this year', 'info');
        }

        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/edit_score';
        $this->load->view('template', $this->data);
    }


    function popup_student_exam_letter($id)
    {
        $id = decode_id($id);
        $APPLICANT = $this->applicant_model->get_member_transcript($id);
        if ($APPLICANT) {
            $this->data['APPLICANT'] = $APPLICANT;
            $user_id = $this->data['APPLICANT']->user_id;
            $reg_no = $this->common_model->get_student($user_id)->row()->registration_number;
            // $this->data['$registered_list'] = $this->exam_model->get_exam_registered($reg_no)->result();
            $this->load->view('exam/popup_student_exam_letter', $this->data);
        } else {
            echo show_alert('This request did not pass our security checks.', 'info');
        }

    }


    function transcript_remark()
    {
        $current_user = current_user();
        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

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
                $sql = " SELECT * FROM students  $where ";
                $this->data['applicant_list'] = $this->db->query($sql)->result();

                //get results;
                $student_results = $this->exam_model->get_results($regno)->result();
                if ($student_results <> '' && $student_results != '') {
                    $this->data['results'] = $student_results;
                }
            } else {
                $this->data['message'] = show_alert('This student does not exist in our records', 'info');
            }
        } elseif ($regno <> '') {
            $this->data['message'] = show_alert('No result for your serach key', 'info');
        } else {

        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'transcript_remark/', 'title' => 'Transcript Remark');
        $this->data['active_menu'] = 'transcript';
        $this->data['content'] = 'exam/transcript_remark';
        $this->load->view('template', $this->data);
    }


    function trancript_subjects()
    {
        $current_user = current_user();
        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

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
                $sql = " SELECT * FROM students  $where ";
                $this->data['applicant_list'] = $this->db->query($sql)->result();

                //get results;
                $student_results = $this->exam_model->get_results($regno)->result();
                if ($student_results <> '' && $student_results != '') {
                    $this->data['results'] = $student_results;
                }
            } else {
                $this->data['message'] = show_alert('This student does not exist in our records', 'info');
            }
        } elseif ($regno <> '') {
            $this->data['message'] = show_alert('No result for your serach key', 'info');
        } else {

        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'trancript_subjects/', 'title' => 'Transcript by subjects');
        $this->data['active_menu'] = 'transcript';
        $this->data['content'] = 'exam/trancript_subjects';
        $this->load->view('template', $this->data);
    }


    public function corporate_candidates()
    {
        $current_user = current_user();

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Fee');
        $this->data['bscrum'][] = array('link' => 'corporate_candidates/', 'title' => 'Candidates in a particular corporate');

        $this->load->library('pagination');
        $academic_year = $this->common_model->get_academic_year()->row()->AYear;

        $where = " WHERE 1=1 ";

        if (isset($_GET['corporate']) && $_GET['corporate'] != '') {
            $where .= " AND cooperate='" . $_GET['corporate'] . "' ";

            $sql = "SELECT * FROM students as s INNER JOIN exam_results as er ON (s.registration_number=er.registration_number) $where ";
            $sql2 = "SELECT COUNT(er.id) as counter FROM students as s INNER JOIN exam_results as er ON (s.registration_number=er.registration_number) $where ";

            $config["base_url"] = site_url('corporate_candidates/');

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

            $this->data['students_list'] = $this->db->query($sql . " GROUP by er.registration_number ORDER BY er.id DESC  LIMIT $page," . $config["per_page"])->result();

        }


        $this->data['active_menu'] = 'reports';
        $this->data['content'] = 'exam/corporate_candidates';
        $this->data['member_list'] = $this->db->get('member')->result();
        $this->load->view('template', $this->data);
    }


    function course_results($course = null, $ayear = null, $session = null)
    {
        $current_user = current_user();
        if (isset($_GET['action']) && $_GET['action'] <> '') {
            $this->data['action'] = $_GET['action'];
        }

        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'MANAGE_EXAMINATION', 'search')) {
            $this->session->set_flashdata("message", show_alert("MANAGE_COLLEGE_SCHOOL :: Access denied !!", 'info'));
            redirect(site_url('dashboard'), 'refresh');
        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Examination');
        $this->data['bscrum'][] = array('link' => 'search/', 'title' => 'Exam Results');

        #get posted search key
        if (isset($_GET['course']) && $_GET['course'] != '') {
            $course = $_GET['course'];

        }
        if (isset($_GET['ayear']) && $_GET['ayear'] != '') {
            $ayear = $_GET['ayear'];
        }

        if (isset($_GET['session']) && $_GET['session'] != '') {
            $session = $_GET['session'];
        }


        #get student results
        if (!is_null($course) && !is_null($ayear) && !is_null($session)) {
            $student_results = $this->exam_model->get_course_results($course, $ayear, $session)->result();
            if ($student_results <> '' && $student_results != '') {
                $this->data['results'] = $student_results;
            } else {
                $this->data['message'] = show_alert('No result found', 'info');
            }
        }

        $this->data['course_list'] = $this->exam_model->get_courses()->result();
        $this->data['year_list'] = $this->db->get('ayear')->result();
        $this->data['semester_list'] = $this->exam_model->get_exam_sessions()->result();

        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/course_results';
        $this->load->view('template', $this->data);
    }

    function update_student_score()
    {
        $current_user = current_user();
        $score = $this->input->post('score');
        $id = $this->input->post('did');

        if (!is_null($score) && !is_null($id)) {
            $data = array(
                'score_marks' => $score
            );
            $update = $this->db->update('exam_results', $data, array('id' => $id));
            if ($update) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    /*
     * $trail_array = array(
                'academic_year' => $exam_student_results->academic_year,
                'course' => $exam_student_results->course,
                'registration_number' => $exam_student_results->registration_number,
                'score_marks' => $exam_student_results->score_marks,
                'score_marks_after' => $score,
                'exam_session' => $exam_student_results->exam_session,
                'action_time' => date('Y-m-d H:i:s'),
                'Recorder' => $exam_student_results->Recorder,
                'action_value' => 'Edit',
                'action_user' => $current_user->username
            );*/

    function delete_information()
    {
        $id = $this->input->post('id');
        if (!is_null($id)) {
            $delete = $this->common_model->delete_record($id, $table = 'exam_results');
            if ($delete) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    function student_exam_registered($ayear=null, $session=null)
    {
        $current_user = current_user();

//        if (!has_role($this->MODULE_ID, $this->GROUP_ID, 'APPLICANT', 'applicant_list')) {
//            $this->session->set_flashdata("message", show_alert("APPLICANT_LIST :: Access denied !!", 'info'));
//            redirect(site_url('dashboard'), 'refresh');
//        }

        $this->data['bscrum'][] = array('link' => '#', 'title' => 'Applicant');
        $this->data['bscrum'][] = array('link' => 'applicant_list/', 'title' => 'Applicant List');

//        $this->load->library('pagination');

        $where = " WHERE 1=1 ";
//
//        if (isset($_GET['type']) && $_GET['type'] != '') {
//
//            $where .= " AND application_type='" . $_GET['type'] . "' ";
//        }
//
//        if (isset($_GET['status']) && $_GET['status'] != '') {
//            $where .= " AND submitted='" . $_GET['status'] . "' ";
//        }
//
//        if (isset($_GET['from']) && $_GET['from'] != '') {
//            $frm = $_GET['from'];
//            $from = format_date($frm, true);
//            $where .= " AND DATE(createdon) >='" . $from . "' ";
//            //echo $where; exit();
//        }

//        if (isset($_GET['to']) && $_GET['to'] != '') {
//            $t = $_GET['to'];
//            $to = format_date($t, true);
//            $where .= " AND DATE(createdon) <='" . $to . "' ";
//        }
//
//        if (isset($_GET['year']) && $_GET['year'] != '') {
//            $where .= " AND AYear='" . $_GET['year'] . "' ";
//        }
//
//
//        if (isset($_GET['name']) && $_GET['name'] != '') {
//            $where .= " AND FirstName LIKE '%" . $_GET['name'] . "%' ";
//        }
//
//        $where .= " AND  status = 0  ";


//        $sql = " SELECT * FROM application  $where ";
//        $sql2 = " SELECT count(id) as counter FROM application  $where ";  //echo $sql; exit();
//
//        $config["base_url"] = site_url('applicant_list/');
//
//        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
//        if (count($_GET) > 0)
//            $config['suffix'] = '?' . http_build_query($_GET, '', "&");
//
//
//        $config["total_rows"] = $this->db->query($sql2)->row()->counter;
//        $config["per_page"] = 50;
//        $config["uri_segment"] = 2;
//
//        include 'include/config_pagination.php';
//
//        $this->pagination->initialize($config);
//        $page = ($this->uri->segment(2) ? $this->uri->segment(2) : 0);
//        $this->data['pagination_links'] = $this->pagination->create_links();

//        $this->data['student_exam_registered_list'] = $this->db->query($sql . " ORDER BY submitted DESC, FirstName ASC LIMIT $page," . $config["per_page"])->result();

        #get posted search key
        if (isset($_GET['ayear']) && $_GET['ayear'] != '') {
            $ayear = $_GET['ayear'];
            $where .= " AND exam_year='$ayear' ";
        }

        if (isset($_GET['session']) && $_GET['session'] != '') {
            $session = $_GET['session'];
            $where .= " AND exam_semester='$session' ";
        }

//        echo $where;exit;

//        SELECT
//    employeeNumber,
//    firstName,
//    lastName,
//    GROUP_CONCAT(DISTINCT customername
//        ORDER BY customerName)
//FROM
//    employees
//        INNER JOIN
//    customers ON customers . salesRepEmployeeNumber = employeeNumber
//GROUP BY employeeNumber
//ORDER BY firstName , lastname;


        #get student results
        if (!is_null($ayear) && !is_null($session)) {
//            $student_results = $this->exam_model->get_course_results($course,$ayear, $session )->result();
            $this->data['get_student'] = $this->db->query("select DISTINCT registration_number from student_exam_registered $where ")->result();
//            foreach($get_student as $r => $var){
////                echo $var->registration_number; exit;
//                $where .= "AND registration_number='$var->registration_number' ";
//            }
//            $student_list = $this->db->query("
//            SELECT registration_number, GROUP_CONCAT(DISTINCT coursecode) as courses FROM student_exam_registered $where
//            ")->result();
//
////            var_dump($student_list);exit;
//
//            if ($student_list <> '' && $student_list != '') {
//                $this->data['results'] = $student_list;
//            } else {
//                $this->data['message'] = show_alert('No result found', 'info');
//            }
        }

        $this->data['year_list'] = $this->db->get('ayear')->result();
        $this->data['sessions'] = $this->exam_model->get_exam_sessions()->result();
        $this->data['active_menu'] = 'exam';
        $this->data['content'] = 'exam/student_exam_registered';
        $this->load->view('template', $this->data);
    }


}