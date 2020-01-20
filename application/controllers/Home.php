<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 9:17 AM
 */
class Home extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('image_lib');
        $this->load->helper(array('captcha'));
        $this->data['title'] = 'Home';
        $this->form_validation->set_error_delimiters('<div class="required">', '</div>');

    }

    public function test()
    {
        echo "hello world";
    }

    function index()
    {

        $this->data['content'] = 'auth/login';
        $this->load->view('public_template', $this->data);
    }


    function registration_start()
    {
        $this->data['content'] = 'home/registration_start';
        $this->load->view('public_template', $this->data);
    }

    function member_start()
    {
        $this->data['content'] = 'home/member_start';
        $this->load->view('public_template', $this->data);
    }


    function application_start()
    {

        if (isset($_GET) && isset($_GET['type']) && isset($_GET['entry']) && isset($_GET['NT']) && isset($_GET['CSEE'])) {
            $this->data['type'] = $application_type = $_GET['type'];
            $this->data['entry'] = $entry_category = $_GET['entry'];
            $this->data['CSEE'] = $CSEE = $_GET['CSEE'];
            $this->data['NT'] = $NT = $_GET['NT'];

            // Validate email
            if (filter_var(trim($this->input->post('email')), FILTER_VALIDATE_EMAIL)) {
                $check_if_exist = $this->db->where('Email', trim($this->input->post('email')))->get('application')->row();
                if($check_if_exist){
                    $this->session->set_flashdata('message', show_alert('Your Email address exists, Please reset password to continue !!', 'warning'));
                    redirect('forgot_password', 'refresh');
                }

            } else {

                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[application.email]|is_unique[users.email]');

            }

            //  $this->form_validation->set_rules('username', 'Username', 'alpha_dash|trim|required|is_unique[users.username]');

            $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');

            $this->form_validation->set_rules('firstname', 'First Name', 'required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'required');
            $this->form_validation->set_rules('gender', 'Gender', 'required');
            $this->form_validation->set_rules('dob', 'Birth Date', 'required|valid_date');
            $this->form_validation->set_rules('nationality', 'Nationality', 'required');
            $this->form_validation->set_rules('disability', 'Disability', 'required');
            $this->form_validation->set_rules('birth_place', 'Place of Birth', 'required');
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'required');
            // $this->form_validation->set_rules('cooperate', 'Corperate', 'required');
            $this->form_validation->set_rules('residence_country', 'Country of Residence', 'required');
            $this->form_validation->set_rules('member_type', 'Member Type', 'required');
            $this->form_validation->set_rules('tiob_number_status', 'Status', 'required');

            if ($this->input->post('member_type') == 1) {
                $this->form_validation->set_rules('cooperate', 'Corperate', 'required');
            }
            if ($this->input->post('tiob_number_status') == 1) {
                $this->form_validation->set_rules('tiob_number', 'TiOB Number', 'required');
            }


            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|matches[conf_password]');
            $this->form_validation->set_rules('conf_password', 'Confirm Password', 'required');
            $this->form_validation->set_rules('capture', 'Capture', 'required');

            $capture = $this->session->userdata('capture_code');
            $capture2 = $this->input->post('capture');
            $required = false;
            if ($this->input->post('capture')) {
                if ($capture2 == $capture) {
                    $required = true;
                } else {
                    $required = false;
                    $this->data['message'] = show_alert('Invalid value in capture, Please enter correct value', 'warning');
                    //$this->form_validation->set_rules('capture','Capture','');
                }
            }


            if ($this->form_validation->run() == true && ($required == true)) {
                $row_year = $this->common_model->get_academic_year(null, 1, 1)->row();
                if ($row_year) {
                    $array_data = array(
                        'AYear' => $row_year->AYear,
                        'Semester' => $row_year->semester,
                        'application_type' => $application_type,
                        'entry_category' => $entry_category,
                        'CSEE' => $CSEE,
                        'NT' => $NT,
                        'duration' => programme_duration($application_type, $entry_category),
                        'FirstName' => ucfirst(strtolower(trim($this->input->post('firstname')))),
                        'MiddleName' => ucfirst(strtolower(trim($this->input->post('middlename')))),
                        'LastName' => ucfirst(strtolower(trim($this->input->post('lastname')))),
                        'Email' => strtolower(trim($this->input->post('email'))),
                        'cooperate' => trim($this->input->post('cooperate')),
                        'Gender' => trim($this->input->post('gender')),
                        'Disability' => trim($this->input->post('disability')),
                        'Nationality' => trim($this->input->post('nationality')),
                        'birth_place' => trim($this->input->post('birth_place')),
                        'marital_status' => trim($this->input->post('marital_status')),
                        'residence_country' => trim($this->input->post('residence_country')),
                        'dob' => format_date(trim($this->input->post('dob'))),
                        'member_type' => trim($this->input->post('member_type')),
                        'status' => 0,
                        'createdon' => date('Y-m-d H:i:s')
                    );
                    /* if ($entry_category == 2) {
                         $array_data['form6_index'] = trim($this->input->post('form6_index'));
                     } else if ($entry_category == 3 || $entry_category == 4) {
                         $array_data['diploma_number'] = trim($this->input->post('diploma_number'));
                     }*/
                    $username = trim($this->input->post('username'));
                    $array_data['username'] = $username;
                    $register = $this->applicant_model->new_applicant($array_data, trim($this->input->post('password')));
                    if ($register) {
                        $this->ion_auth_model->login($username, trim($this->input->post('password')), true);
                        $this->session->set_flashdata('message', show_alert('Information saved successfully, Please add Contact Information !!', 'success'));
                        redirect('applicant_contact', 'refresh');
                    } else {
                        $this->data['message'] = show_alert("Fail to save Information, Please try again !!", 'info');
                    }
                } else {
                    $this->data['message'] = show_alert("Configuration not set, Please use " . anchor(site_url('contact'), 'this link', 'style="color:red; text-decoration:underline;"') . "  to report", 'info');
                }
            } else {
                $captcha_num = '1234567890';
                $captcha_num = substr(str_shuffle($captcha_num), 0, 6);
                $this->session->set_userdata('capture_code', $captcha_num);
                $this->data['captcha_num'] = $captcha_num;
            }
            $this->data['marital_status_list'] = $this->common_model->get_marital_status()->result();
            $this->data['gender_list'] = $this->common_model->get_gender()->result();
            $this->data['disability_list'] = $this->common_model->get_disability()->result();
            $this->data['nationality_list'] = $this->common_model->get_nationality()->result();

            $this->data['content'] = 'home/application_start';
            $this->load->view('public_template', $this->data);
        } else {
            redirect('application_start', 'refresh');
        }
    }

    function member_form()
    {
        if (isset($_GET) && isset($_GET['type']) && isset($_GET['entry']) && isset($_GET['NT']) && isset($_GET['CSEE'])) {
            $this->data['type'] = $application_type = $_GET['type'];
            $this->data['entry'] = $entry_category = $_GET['entry'];
            $this->data['CSEE'] = $CSEE = $_GET['CSEE'];
            $this->data['NT'] = $NT = $_GET['NT'];

            // Validate email
            if (filter_var(trim($this->input->post('email')), FILTER_VALIDATE_EMAIL)) {
                $check_if_exist = $this->db->where('Email', trim($this->input->post('email')))->get('application')->row();
                if($check_if_exist){
                    $this->session->set_flashdata('message', show_alert('Your Email address exists, Please reset password to continue !!', 'warning'));
                    redirect('forgot_password', 'refresh');
                }

            } else {

                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[application.email]|is_unique[users.email]');

            }

            $this->form_validation->set_rules('username', 'Username', 'trim|callback_username_check_blank|required|is_unique[users.username]');
            $this->form_validation->set_rules('firstname', 'First Name', 'required');
            $this->form_validation->set_rules('lastname', 'Last Name', 'required');
            $this->form_validation->set_rules('gender', 'Gender', 'required');
            $this->form_validation->set_rules('dob', 'Birth Date', 'required|valid_date');
            $this->form_validation->set_rules('nationality', 'Nationality', 'required');
            $this->form_validation->set_rules('disability', 'Disability', 'required');
            $this->form_validation->set_rules('birth_place', 'Place of Birth', 'required');
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'required');
            $this->form_validation->set_rules('residence_country', 'Country of Residence', 'required');
            $this->form_validation->set_rules('member_type', 'Member Type', 'required');
            $this->form_validation->set_rules('programme', 'Programme', 'required');
            if ($this->input->post('member_type') == 1) {
                $this->form_validation->set_rules('cooperate', 'Corperate', 'required');
            }
            //'cooperate' => trim($this->input->post('cooperate')),

            /* if($application_type > 2){
                $this->form_validation->set_rules('form4_index', ($application_type == 3 ? 'Certificate ' : 'Form IV ').' Index No', 'required|is_unique[application.form4_index]|is_unique[users.username]');
            }else{
                $this->form_validation->set_rules('form4_index', ($application_type == 3 ? 'Certificate ' : 'Form IV ').' Index No', 'required|valid_indexNo|is_unique[application.form4_index]|is_unique[users.username]');
            }

            if ($entry_category == 2) {
                $this->form_validation->set_rules('form6_index', 'Form VI Index', 'required|is_unique[application.form6_index]');
            } else if ($entry_category == 3) {
                $this->form_validation->set_rules('diploma_number', 'Certificate Number', 'required');
            } else if ($entry_category == 4) {
                $this->form_validation->set_rules('diploma_number', 'Diploma Number', 'required');
            }
            */
//            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[application.email]|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|matches[conf_password]');
            $this->form_validation->set_rules('conf_password', 'Confirm Password', 'required');
            $this->form_validation->set_rules('capture', 'Capture', 'required');

            $capture = $this->session->userdata('capture_code');
            $capture2 = $this->input->post('capture');
            $required = false;
            if ($this->input->post('capture')) {
                if ($capture2 == $capture) {
                    $required = true;
                } else {
                    $required = false;
                    $this->data['message'] = show_alert('Invalid value in capture, Please enter correct value', 'warning');
                    //$this->form_validation->set_rules('capture','Capture','');
                }
            }


            if ($this->form_validation->run() == true && ($required == true)) {
                $row_year = $this->common_model->get_academic_year(null, 1, 1)->row();
                if ($row_year) {
                    /*=======================
                    end of old
                    =====================*/
                    // $student_data = array();
                    $data = array(
                        'AYear' => $row_year->AYear,
                        'Semester' => $row_year->semester,
                        'application_type' => $application_type,
                        'entry_category' => $entry_category,
                        'CSEE' => $CSEE,
                        'NT' => $NT,
                        'duration' => programme_duration($application_type, $entry_category),
                        'FirstName' => ucfirst(strtolower(trim($this->input->post('firstname')))),
                        'MiddleName' => ucfirst(strtolower(trim($this->input->post('middlename')))),
                        'LastName' => ucfirst(strtolower(trim($this->input->post('lastname')))),
                        'Email' => strtolower(trim($this->input->post('email'))),
                        'Gender' => trim($this->input->post('gender')),
                        'Disability' => trim($this->input->post('disability')),
                        'Nationality' => trim($this->input->post('nationality')),
                        'birth_place' => trim($this->input->post('birth_place')),
                        'marital_status' => trim($this->input->post('marital_status')),
                        'residence_country' => trim($this->input->post('residence_country')),
                        'dob' => format_date(trim($this->input->post('dob'))),
                        'member_type' => trim($this->input->post('member_type')),
                        'tiob_member' => trim($this->input->post('tiob_number_status')),
                        'Regno' => trim($this->input->post('tiob_number')),
                        'cooperate' => trim($this->input->post('cooperate')),
                        'createdon' => date('Y-m-d H:i:s')
                    );

                    $username = trim($this->input->post('username'));
                    $data['username'] = $username;
                    $register = $this->applicant_model->new_applicant($data, trim($this->input->post('password')));
                    if ($register) {
                        $student_data = array(
                            //'registration_number' => $_SESSION['regno'],
                            'first_name' => ucfirst(strtolower(trim($this->input->post('firstname')))),
                            'other_names' => ucfirst(strtolower(trim($this->input->post('middlename')))),
                            'surname' => ucfirst(strtolower(trim($this->input->post('lastname')))),
                            'entry_year' => $row_year->AYear,
                            'gender' => trim($this->input->post('gender')),
                            'dob' => format_date(trim($this->input->post('dob'))),
                            'marital_status' => trim($this->input->post('marital_status')),
                            'email' => strtolower(trim($this->input->post('email'))),
                            'nationality' => trim($this->input->post('residence_country')),
                            'member_type' => trim($this->input->post('member_type')),
                            'application_type' => $application_type,
                            'programme_id' => trim($this->input->post('programme')),
                            'cooperate' => trim($this->input->post('cooperate')),
                            'profile_avatar' => 'default.jpg',
                            'account_check' => 1
                        );
                        $student_feedback = $this->exam_model->update_student_data($student_data, $_SESSION['regno']);
                        if ($student_feedback) {
                            $this->ion_auth_model->login($username, trim($this->input->post('password')), true);
                            $this->session->set_flashdata('message', show_alert('Information saved successfully, Please add Contact Information !!', 'success'));
                            redirect('applicant_contact', 'refresh');
                        } else {
                            $this->data['message'] = show_alert("Fail to save Information, Please try again !!", 'info');
                        }
                    }
//                    $this->exam_model->import_application_data($data);
//

//
//
//
//                    $data['application_type'] = $application_type;
//
//                    $get_applicant_id = $this->db->query("select id from application order by id desc")->row()->id;
//                    $additional_data = array(
//                        'firstname' => $this->input->post('firstname'),
//                        'lastname' => $this->input->post('lastname'),
//                        'username' => $this->input->post('username'),
//                        'applicant_id' => $get_applicant_id,
//                        'password' => $this->ion_auth->hash_password($this->input->post('password')),
//                        'email' => $this->input->post('email'),
//                        'campus_id' => 1,
//                        'active' => 1
//
//                    );
//                    $this->db->insert('users',$additional_data);
//                    $last_id = $this->db->query("select id from users order by id desc")->row()->id;
//                    $user_grp_data = array(
//                      'user_id' => $last_id,
//                      'group_id' => 4
//                    );
//                    $this->exam_model->insert_user_data($user_grp_data,$last_id);
//                    $this->db->update('application', array('user_id'=>$last_id));

//                    $student_data['user_id'] = $last_id;
//                    $student_feedback = $this->exam_model->update_student_data($student_data,$_SESSION['regno']);
//                    $this->session->set_flashdata('message', show_alert('Information saved successfully, Now you can login !!', 'success'));
//                    redirect('login', 'refresh');

                } else {
                    $this->data['message'] = show_alert("Configuration not set, Please use " . anchor(site_url('contact'), 'this link', 'style="color:red; text-decoration:underline;"') . "  to report", 'info');
                }
            } else {
                $captcha_num = '1234567890';
                $captcha_num = substr(str_shuffle($captcha_num), 0, 6);
                $this->session->set_userdata('capture_code', $captcha_num);
                $this->data['captcha_num'] = $captcha_num;
            }
            $this->data['marital_status_list'] = $this->common_model->get_marital_status()->result();
            $this->data['gender_list'] = $this->common_model->get_gender()->result();
            $this->data['disability_list'] = $this->common_model->get_disability()->result();
            $this->data['nationality_list'] = $this->common_model->get_nationality()->result();

            $this->data['content'] = 'home/member_form';
            $this->load->view('public_template', $this->data);
        } else {
            redirect('application_start', 'refresh');
        }
    }

    function membership_start()
    {

        $key = $this->input->post('regno');
        if (!is_null($key)) {
            $regno = check_student($id = null, $key);
            if ($regno) {
                $account = $this->db->query("select account_check from students where registration_number
                 ='$key' ")->row()->account_check;
                if ($account == 0) {
                    $this->load->library('session');
                    //keep regno into session
                    $_SESSION['regno'] = $regno;

                    redirect('member_start', 'refresh');
                } else {
                    $this->session->set_flashdata('message', show_alert('Account for this Registration # already exist.Please contact System Administrator to remind your password', 'warning'));
                    redirect('membership_start', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', show_alert('Membership Number is not Recognised, Please review your number or create new account', 'warning'));
                redirect('membership_start', 'refresh');
            }
        }

        $this->data['applicant_dashboard'] = 'applicant_dashboard';
        $this->data['content'] = 'home/membership_start';
        //redirect('login', 'refresh');
        $this->load->view('public_template', $this->data);
    }

    function capture($capture_code)
    {
        $captcha_num = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        $captcha_num = substr(str_shuffle($captcha_num), 0, 6);
        $captcha_num = $capture_code;
        //$_SESSION['capture_code']=$captcha_num;
        $font_size = 20;
        $img_width = 300;
        $img_height = 50;
        header('Content-type: image/jpeg');
        $image = imagecreate($img_width, $img_height); // create background image with dimensions
        imagecolorallocate($image, 255, 255, 255); // set background color
        $text_color = imagecolorallocate($image, 0, 0, 0); // set captcha text color
        imagettftext($image, $font_size, 0, 15, 30, $text_color, './media/NIT.ttf', $captcha_num);
        imagejpeg($image);

    }


    function recommendation()
    {
        if (isset($_GET) && isset($_GET['key']) && isset($_GET['referee_id']) && isset($_GET['code'])) {
            $applicant_id = decode_id($_GET['key']);
            $referee_id = decode_id($_GET['referee_id']);
            $rec_code = $_GET['code'];
            $applicant_info = $this->data['APPLICANT'] = $this->db->where('id', $applicant_id)->get('application')->row();
            $referee_info = $this->data['REFEREE'] = $this->db->where('id', $referee_id)->get('application_referee')->row();
            if ($applicant_info && $referee_info && $applicant_info->id == $referee_info->applicant_id && $rec_code == $referee_info->rec_code) {
                $this->data['recommendation_area'] = $recommendation_area = $this->common_model->get_recommendation_area()->result();

                $this->form_validation->set_rules('recommend_overall', 'above', 'required');
                $this->form_validation->set_rules('applicant_known', 'above', 'required');
                $this->form_validation->set_rules('weakness', 'above', 'required');
                $this->form_validation->set_rules('description_for_qn3', 'above', 'required');
                $this->form_validation->set_rules('other_degree', 'above', 'required');
                $this->form_validation->set_rules('producing_org_work', 'above', 'required');
                $recommendation_area_value = array();
                foreach ($recommendation_area as $rec_key => $rec_value) {
                    $this->form_validation->set_rules('recommend_' . $rec_value->id, $rec_value->name, 'required');
                    $recommendation_area_value[$rec_value->id] = trim($this->input->post('recommend_' . $rec_value->id));
                }

                if ($this->form_validation->run() == true) {

                    $array = array(
                        'applicant_id' => $applicant_id,
                        'referee_id' => $referee_id,
                        'recommend_overall' => trim($this->input->post('recommend_overall')),
                        'applicant_known' => trim($this->input->post('applicant_known')),
                        'description_for_qn3' => trim($this->input->post('description_for_qn3')),
                        'weakness' => trim($this->input->post('weakness')),
                        'other_degree' => trim($this->input->post('other_degree')),
                        'producing_org_work' => trim($this->input->post('producing_org_work')),
                        'other_capability' => trim($this->input->post('other_capability')),
                        'anything' => trim($this->input->post('anything')),
                        'recommendation_arrea' => json_encode($recommendation_area_value)
                    );

                    $record_recommendation = $this->applicant_model->record_referee_recomendation($array);
                    if ($record_recommendation) {
                        $this->session->set_flashdata('message', show_alert('Recommendation saved successfully', 'success'));
                        redirect(current_full_url(), 'refresh');
                    } else {
                        $this->data['message'] = show_alert('Fail to save recomendation, Please try again later', 'warning');
                    }

                }


                $row_data = $this->applicant_model->get_referee_recomendation($applicant_id, $referee_id)->row();
                if ($row_data) {
                    $this->data['recommendation_info'] = $row_data;
                }


                $this->data['allowed'] = 1;
            } else {
                $this->data['allowed'] = 0;
            }
        } else {
            $this->data['allowed'] = 0;
        }

        $this->data['applicant_dashboard'] = 'applicant_dashboard';
        $this->data['content'] = 'home/recommendation';
        $this->load->view('public_template', $this->data);
    }

    function old_member_sign_up($id=null)
    {

        if($id!=null)
        {
            $applicant_id=$id=decode_id($id);
            $applicant_info= $this->db->query("select * from application where id='".$id."'")->row();
            if($applicant_info)
            {
                $check_account=$this->db->query("select * from users where applicant_id='".$id."'")->row();
                if($check_account)
                {
                    $student_info= $this->db->query("select * from students where user_id='".$check_account->id."'")->row();

                    if($check_account->active!=3)
                    {
                        $this->data['student_info']=$student_info;
                        $this->session->set_flashdata('message', show_alert('Account for this Registration # already exist.Please contact System Administrator to remind your password', 'warning'));
                        //redirect('old_member_sign_up', 'refresh');
                    }else{
                        if($student_info)
                        {
                            $this->data['student_info']=$student_info;
                        }

                    }

                }

            }else{
                $this->session->set_flashdata('message', show_alert("The action didn't pass our security check!!", 'warning'));
                //redirect('old_member_sign_up', 'refresh');
            }

        }
        $this->form_validation->set_rules('regno','Student Registration Number', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[application.email]|is_unique[users.email]');
        $this->form_validation->set_rules('password','Password ', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');

        $this->form_validation->set_rules('password_confirm', "Confirm Password", 'required');
        if ($this->form_validation->run() == true) {
            //check if regno exist
            $student_info= $this->db->query("select * from students where registration_number='".trim($this->input->post('regno'))."'")->row();

            if($student_info)
            {
                //check if active is 3
                $check_account=$this->db->query("select * from users where id='".$student_info->user_id."'")->row();
                if($check_account)
                {
                    if($check_account->active==3)
                    {
                        $user_additional_data = array(
                            'username' => trim($this->input->post('username')),
                            'password' => $this->ion_auth->hash_password(trim($this->input->post('password'))),
                            'active' => 1
                        );
                        $this->db->where('id', $check_account->id);
                        $this->db->update('users',$user_additional_data);
                        $this->session->set_flashdata('message', show_alert('Member account successfully created!!', 'success'));


                    }else
                    {
                        $this->data['student_info']=$student_info;
                        $this->session->set_flashdata('message', show_alert('Account for this Registration # already exist.Please contact System Administrator to remind your password', 'warning'));
                        redirect('old_member_sign_up', 'refresh');

                    }
                }else{
                    $this->session->set_flashdata('message', show_alert("The action didn't pass our security check!!", 'warning'));
                    redirect('old_member_sign_up', 'refresh');
                }



            }


        }


        $this->data['content'] = 'home/old_member_sign_up';
        $this->load->view('public_template', $this->data);



    }

    function CleanStudentData()
    {
        $select="select * from application where user_id=9473";
        $dirty_students=$this->db->query($select)->result();
        if($dirty_students)
        {
            foreach ($dirty_students as $key=>$value)
            {
                $applicant_id=$value->id;
                $current_email=$value->Email;
                $current_user=$this->db->query('select * from users where email="'.$current_email.'"')->row();
                $this->db->where('id', $applicant_id);
                $this->db->update('application', array('user_id'=>$current_user->id));
            }

        }
    }

}
