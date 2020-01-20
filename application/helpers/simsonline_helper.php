<?php
/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 9:05 AM
 */


function get_collage_info()
{
    $CI = &get_instance();
    return $CI->db->get('college')->row();
}


/**
 * Get current user who logged or pass user ID to get user row
 * Fail to get user, system redirect to login
 *
 * @param null $id
 * @return bool
 */
function current_user($id = null)
{
    $CI = &get_instance();

    $id || $id = $CI->session->userdata('sims_online_user_id');

    #send this id to student table if imported
//    if($id){
//        $CI->db->update('students',array('user_id'=> $id), array('id' => $id));
//    }

  //  $user = $CI->db->get_where('users',array('id' => $id))->row();

    $CI->db->select('users.id,users.*,users_groups.group_id');
    $CI->db->from('users');
    $CI->db->where('users.id',$id);
    $CI->db->join('users_groups', 'users_groups.user_id = users.id');
    $user = $CI->db->get()->row();

    if ($user) {
        return $user;
    }
    else {
        $CI->session->set_flashdata('message', 'Please Login to continue');
        $replaced = str_replace('index.php/', '', (str_replace(base_url(), '', current_full_url())));
        if ($replaced && $replaced != 'index.php') {
            $callback = redirect('login/?callback=' . $replaced, 'refresh');
        } else {
            $callback = redirect('login', 'refresh');

        }
        return FALSE;
    }
}

// function current_student($id = null)
// {
//     $CI = &get_instance();
//     $id || $id = $CI->session->userdata('sims_online_user_id');
//     $CI->db->select('*');
//     $CI->db->from('students');
//     $CI->db->where('students.user_id',$id);
//     $CI->db->join('users_groups', 'users_groups.user_id = students.user_id');
//     $CI->db->join('application', 'application.user_id = students.user_id');
//     $student = $CI->db->get()->row();
//
//     if ($student) {
//         return  $student;
//     }
// }



/**
 * Get campus Information
 * @param null $id
 * @return mixed
 */
function current_campus($id = null)
{
    $CI = &get_instance();

    $id || $id = $CI->session->userdata('sims_online_user_id');

    $row = $CI->common_model->get_campus($id)->row();

    if ($row) {
        return $row;
    } else {
        return $CI->common_model->get_campus(current_user()->campus_id)->row();
    }
}

/**
 * Run background activities
 *
 * @param $cmd
 */
function execInBackground($cmd)
{

    if (substr(php_uname(), 0, 7) == "Windows") {
        pclose(popen("start /B " . 'php ./index.php  ' . $cmd, "r"));
    } else {
        exec('php ./index.php  ' . $cmd . " > /dev/null 2>&1 &");
    }
}



/**
 * Format date based on mysql timestamp or easy for human reading
 * If $tomysql  is true : format will be  YYYY-MM-DD
 * If $tomysql  is false : format will be  DD-MM-YYYY
 *
 * @param $date
 * @param bool|true $tomysql
 * @return string
 */
function format_date($date, $tomysql = true)
{
    $CI = &get_instance();
    if ($date == '') {
        return '';
    }
    if ($tomysql) {
        if (preg_match("/^[0-9]{1,2}-[0-9]{1,2}-[0-9]{4}$/", $date)) {
            $expl = explode('-', $date);
            return $expl[2] . '-' . $expl[1] . '-' . $expl[0];
        } else {
            return $date;
        }
    } else {
        if (preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/", $date)) {
            $expl = explode('-', $date);
            $rt = $expl[2] . '-' . $expl[1] . '-' . $expl[0];
            if ($rt == '00-00-0000') {
                return '';
            }
            return $rt;
        } else {
            if ($date == '00-00-0000') {
                return '';
            }
            return $date;
        }
    }
}


/**
 * Get column value by passing table name, id of the table and  column name
 *
 * @param $table
 * @param $id
 * @param string $column
 * @return string
 */
function get_value($table, $where, $column = 'name')
{
    $CI = &get_instance();
    if (is_array($where)) {
        $CI->db->where($where);
    } else {
        $CI->db->where('id', $where);
    }
    $row = $CI->db->get($table)->row();
    if ($row) {
        if ($column <> '') {
            return $row->{$column};
        } else {
            return $row;
        }
    }

    return '';
}


/**
 * Function to format alert
 * alert_type: info,success,error,warning
 *
 * @param $text
 * @param $alert_type
 * @return string
 */

// alert_type: info,success,error,warning
function show_alert($text, $alert_type)
{
    $alert = '<div class="alert alert-' . $alert_type . ' alert-dismissable" style="text-align: left;">
    <button aria-hidden="true" data-dismiss="alert" class="close alert_message" type="button">X</button>' .
        $text . '
</div>';
    return $alert;
}

/**
 *  Get current full URL
 *
 * @return string
 */
function current_full_url($query = null)

{
    parse_str($_SERVER['QUERY_STRING'], $query_string);
    if (!is_null($query)) {
        parse_str($query, $new_query);
        foreach ($new_query as $k => $v) {
            $query_string[$k] = $v;
        }
    }

    return current_url() . (count($query_string) > 0 ? '/?' . http_build_query($query_string) : '');
}

/**
 *  Remove querystring
 *
 * @return string
 */
function remove_query_string($query = null)

{

    parse_str($_SERVER['QUERY_STRING'], $query_string);

    if (!is_null($query)) {
        if(!is_array($query)){
            $query = array($query);
        }
        foreach ($query_string as $k => $v) {
            if(in_array($k,$query)) {
                unset($query_string[$k]);
            }
        }
    }

    return current_url() . (count($query_string) > 0 ? '/?' . http_build_query($query_string) : '');
}

/**
 * Record user Login time and browser used
 */
function user_login_history()
{
    $CI = &get_instance();
    $CI->load->library('user_agent');
    $browser = $CI->agent->browser();
    $vesrion = $CI->agent->version();
    $platform = $CI->agent->platform();
    $history_data = array(
        'user_id' => $CI->session->userdata('sims_online_user_id'),
        'login_time' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'],
        'browser' => "Platform : " . $platform . ' -- Browser : ' . $browser . ' -- Version : ' . $vesrion

    );
    $CI->db->insert('user_login_history', $history_data);

}


/**
 * Get List of the Image extension allowed to be uploaded
 * @return array
 */
function img_allowed_list()
{
    $CI = &get_instance();
    return array('jpg', 'jepg', 'png', 'gif', 'bmp');
}

/**
 * Check if image is in the list
 * @param $img_name
 * @return bool
 */

function is_img_allowed($img_name)
{
    $CI = &get_instance();
    $ext = getExtension($img_name);
    $allowed_img = img_allowed_list();
    if (!is_null($ext)) {
        if (in_array($ext, $allowed_img)) {
            return TRUE;
        }
        return FALSE;
    }
}

/**
 * Get Extension of the file based on filename
 * @param $str
 * @return null|string
 */
function getExtension($str)
{
    $i = strrpos($str, ".");
    if (!$i) {
        return null;
    }
    $l = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);
    return strtolower($ext);
}

/**
 * Function to upload file to server
 * @param $file_array
 * @param $input
 * @param null $folder
 * @return bool|string
 */
function uploadFile($file_array, $input, $folder = null)
{
    $filename = time() . generatePIN(4) . '.' . getExtension($file_array[$input]['name']);
    $path = UPLOAD_FOLDER . $folder;
    $path = $path . basename($filename);
    if (move_uploaded_file($file_array[$input]['tmp_name'], $path)) {
        chmod($path, FILE_READ_MODE);
        return $filename;
    } else {
        return FALSE;
    }
}

/**
 * Generate random digit
 * @param int $digits
 * @return string
 */
function generatePIN($digits = 4)
{
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while ($i < $digits) {
        //generate a random number between 0 and 9.
        $pin .= mt_rand(0, 9);
        $i++;
    }
    return $pin;
}


function log_notification($message, $priority = 1,$user_id=null)
{
    $CI = &get_instance();
    $logs = array(
        'message' => $message,
        'date_time' => date('Y-m-d H:i:s'),
        'priority' => $priority,
        'createdby' => (!is_null($user_id) ? $user_id: current_user()->id)
    );
    $CI->db->insert('log_notification', $logs);
}


function get_user_department($user_object = null, $user_id = null)
{
    $CI = &get_instance();
    if (is_null($user_object) && is_null($user_id)) {
        return null;
    }
    if (!is_null($user_id)) {
        $user_object = current_user($user_id);
    }

    if (is_null($user_object)) {
        return null;
    }

    $department_list = null;
    //GET DEPARTMENT FIRST
    if ($user_object->access_area_id > 0) {
        if ($user_object->access_area == 1) {
            //Access Only Programme Under college/School
            $get_department = $CI->common_model->get_department(null, $user_object->access_area_id)->result();
            if (count($get_department) > 0) {
                $department_list = convert_to_single_array($get_department, 'id');
            }
        } else if ($user_object->access_area == 2) {
            $department_list = array($user_object->access_area_id);
        }
    }

    return $department_list;
}


function convert_to_single_array($array, $column = null)
{
    if (!is_array($array)) {
        return FALSE;
    }
    $result = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, convert_to_single_array($value));
        } else {
            if (!is_null($column)) {
                $result[] = $value->{$column};
            } else {
                $result[] = $value;
            }
        }
    }
    return $result;
}


function is_section_used($section,$array){
    if(array_key_exists($section,$array)){
        return $array[$section];
    }else{
        return false;
    }
}

/**
 * Notify By SMS
 *
 * Send Message to SMS Mtandao for notification
 *
 * @param $recepient - array of recipient or a string( more than one recipient separate by comma)
 * @param $sms - message
 */

function notify_by_sms($recepient, $sms,$send_by,$priority=1){
    $CI = &get_instance();

    if (!is_array($recepient)) {
        $recepient = array_unique(explode(',', $recepient));
    } else {
        $recepient = array_unique($recepient);
    }

    $recepient_mobile = array();
    $datalist = array();
    $tracker = $send_by;
    $data4 = array(
        'sender' => 'INFO',
        'message' => $sms,
        'priority' => $priority,
        'createdby' => $send_by,
        'sent_date'=>date('Y-m-d H:i:s'),
        'is_sent'=>1,
        'status'=>'Sent',
        'sent_status'=>$tracker
    );

    foreach ($recepient as $key => $value) {

        if ($CI->input->is_cli_request()) {
            $message_id = substr($value, -9) . generatePIN(3) . $key . date('ymHis');
        } else {
            $message_id = substr($value, -9) . $key . alphaID(time() . substr($value, -9));
        }


        ####Remove any space within phone number###########
        $value=preg_replace('/\s+/', '',trim($value));
        ###Check if first three character is not 255######
        $first_3_characters = substr($value, 0, 3);
        if((int)$first_3_characters!=255)
        {
            $value='255'.substr($value, 3);
        }
        $data4['mobile'] = $value;
        $data4['message_id'] = $message_id;
        $data5 = $data4;
        $data5['sms_count'] = ceil(strlen($sms) / 160);
        $datalist[] = $data5;
        $recepient_mobile[$message_id] = $value;
    }

    $array_to_json = array(
        'token' => '7659888729183abfda414b7525464cf3',
        'sender' => 'INFO',
        'message' => $sms,
        'push' => site_url('response/sms_delivery'),
        'recipient' => $recepient_mobile
    );


    $CI->db->insert_batch('message_sent',$datalist);

    $json_string = json_encode($array_to_json);

    $CI->curl->create('http://login.smsmtandao.com/smsmtandaoapi/send');
    $CI->curl->options(
        array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_POSTFIELDS => $json_string,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1
        )
    );

    $send = $CI->curl->execute();
    $send =  json_decode($send);

    if($send){
        $CI->db->update("message_sent",array('sent_status'=>$send->description),array('sent_status'=>$tracker));
    }


    function get_subject_object($code=null,$id=null){
        $CI = &get_instance();
        if(!is_null($code)){
            $CI->db->where('code',$code);
        }
        if(!is_null($id)){
            $CI->db->where('id',$id);
        }
        return  $CI->db->get('secondary_subject')->row();

    }

    function get_index_number($applicant_id,$category)
    {
        $CI = &get_instance();
        $index_number = '';
        switch ($category) {
            case 1:
                $row = $CI->db->where(array('applicant_id' => $applicant_id, 'certificate' => $category))->order_by('id', 'ASC')->get('application_education_authority')->row();
                if ($row) {
                    $index_number = $row->index_number;
                }
                break;

            case 2:
                $row = $CI->db->where(array('applicant_id' => $applicant_id, 'certificate' => $category))->order_by('id', 'ASC')->get('application_education_authority')->row();
                if ($row) {
                    $index_number = $row->index_number;
                }
                break;
            default:
                $row = $CI->db->where(array('applicant_id' => $applicant_id, 'certificate' => $category))->order_by('id', 'ASC')->get('application_education_authority')->row();
                if ($row) {
                    $index_number = $row->index_number;
                }
                break;
        }

        return $index_number;
    }
}
