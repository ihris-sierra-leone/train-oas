<?php
/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 9:04 AM
 */

/**
 * Get callback URL
 *
 * @return bool
 */
function get_callback()
{
    if (isset($_GET['callback'])) {
        return $_GET['callback'];
    }

    return FALSE;
}

/**
 * Check if callback URL is set
 *
 * @return bool|string
 */
function is_callback_set()
{
    if (isset($_GET['callback'])) {
        return '?callback=' . $_GET['callback'];
    }

    return FALSE;
}

/**
 * Encode table ID to large string of numbers
 *
 * @param $id
 * @return string
 */
function encode_id($id)
{
    $string = "ABCDEFGHIJKLMNOPQRSTUVXWZ";
    $rand = str_split($string);
    $left = array_rand($rand, 1);
    $right = array_rand($rand, 1);

    $build_query = $left . '_' . $id . '_' . $right;
    $strt_arry = str_split($build_query);
    $arry = array();
    foreach ($strt_arry as $kx => $vx) {
        $re = unpack('C*', $vx);
        if (strlen($re[1]) === 3) {
            $arry[] = $re[1];
        } else {
            $arry[] = '0' . $re[1];
        }
    }

    return $parameter = implode('', $arry);
}

/**
 * Decode string to normal table ID
 *
 * @param $string
 * @return null
 */

function username_check_blank($str)
{

    $pattern = ' ';
    $result = preg_match($pattern, $str);

    if ($result)
    {
        $this->form_validation->set_message(__FUNCTION__, 'The %s field can not have a " "');
        return FALSE;
    }
    else
    {
        return TRUE;
    }
}

function decode_id($string)
{
    $str = join(array_map('chr', str_split($string, 3)));
    $exp = explode('_', $str);
    if (count($exp) == 3) {
        return $exp[1];
    } else {
        return NULL;
    }
}

/**
 * @author  Kevin van Zonneveld <kevin@vanzonneveld.net>
 * @author  Simon Franz
 * @author  Deadfish
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
 * @link      http://kevin.vanzonneveld.net/
 *
 * @param mixed $in String or long input to translate
 * @param boolean $to_num Reverses translation when true
 * @param mixed $pad_up Number or boolean padds the result up to a specified length
 * @param string $passKey Supplying a password makes it harder to calculate the original ID
 *
 * @return mixed string or long
 */
function alphaID($in, $to_num = false, $pad_up = false, $passKey = null)
{

    $index = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    if ($passKey !== null) {

        for ($n = 0; $n < strlen($index); $n++) {
            $i[] = substr($index, $n, 1);
        }

        $passhash = hash('sha256', $passKey);
        $passhash = (strlen($passhash) < strlen($index)) ? hash('sha512', $passKey) : $passhash;

        for ($n = 0; $n < strlen($index); $n++) {
            $p[] = substr($passhash, $n, 1);
        }

        array_multisort($p, SORT_DESC, $i);
        $index = implode($i);
    }

    $base = strlen($index);

    if ($to_num) {
        // Digital number  <<--  alphabet letter code
        $in = strrev($in);
        $out = 0;
        $len = strlen($in) - 1;
        for ($t = 0; $t <= $len; $t++) {
            $bcpow = bcpow($base, $len - $t);
            $out = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
        }

        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $out -= pow($base, $pad_up);
            }
        }
        $out = sprintf('%F', $out);
        $out = substr($out, 0, strpos($out, '.'));
    } else {
        // Digital number  -->>  alphabet letter code
        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $in += pow($base, $pad_up);
            }
        }

        $out = "";
        for ($t = floor(log($in, $base)); $t >= 0; $t--) {
            $bcp = bcpow($base, $t);
            $a = floor($in / $bcp) % $base;
            $out = $out . substr($index, $a, 1);
            $in = $in - ($a * $bcp);
        }
        $out = strrev($out); // reverse
    }

    return $out;
}

function get_user_group($id = null)
{
    $CI = &get_instance();

    $id || $id = $CI->session->userdata('sims_online_user_id');

    $groupinfo = $CI->ion_auth_model->get_users_groups($id)->row();

    if ($groupinfo) {
        return $groupinfo;
    }

    return false;
}

/**
 * Update Users Access Level
 *
 * @param $user_id
 * @param $module_id
 * @param $link
 * @param $action
 * @return bool
 */
function update_access($group_id, $module_id, $section, $role, $action)
{

    $CI = &get_instance();
    if ($action == 'ADD') {
        if (!has_role($module_id, $group_id, $section, $role)) {

            $CI->db->where("group_id", $group_id);
            $CI->db->where("module_id", $module_id);
            $CI->db->where("section", $section);
            $row = $CI->db->get("module_group_role")->row();

            if ($row) {
                $json = json_decode($row->role, true);

                if (is_array($json) && !in_array($role, $json)) {

                    array_push($json, $role);
                    $array = array('role' => json_encode(array_values($json)));
                    return $CI->db->update("module_group_role", $array, array('group_id' => $group_id, 'module_id' => $module_id, 'section' => $section));
                }
                return true;
            } else {
                $array = array(
                    'group_id' => $group_id,
                    'module_id' => $module_id,
                    'section' => $section,
                    'role' => json_encode(array($role))
                );

                return $CI->db->insert("module_group_role", $array);
            }
        }
    } else if ($action == 'DELETE') {
        if (has_role($module_id, $group_id, $section, $role)) {
            $CI->db->where("group_id", $group_id);
            $CI->db->where("module_id", $module_id);
            $CI->db->where("section", $section);
            $row = $CI->db->get("module_group_role")->row();
            if ($row) {
                $json = json_decode($row->role, true);
                if (is_array($json) && in_array($role, $json)) {
                    if (($key = array_search($role, $json)) !== false) {
                        unset($json[$key]);
                    }

                    $array = array('role' => json_encode(array_values($json)));
                    if (count($json) == 0) {
                        return $CI->db->update("module_group_role", $array, array('group_id' => $group_id, 'module_id' => $module_id, 'section' => $section));
                    } else {
                        return $CI->db->update("module_group_role", $array, array('group_id' => $group_id, 'module_id' => $module_id, 'section' => $section));
                    }

                }
                return false;
            }
        }
    }


}

/**
 * Check if user has role in a certain link
 * @param $module_id
 * @param $link
 * @return bool
 */

function has_role($module_id, $group_id, $section, $role)
{

    $CI = &get_instance();
    $CI->db->where("group_id", $group_id);
    $CI->db->where("module_id", $module_id);
    $CI->db->where("section", $section);
    $row = $CI->db->get("module_group_role")->row();

    if ($row) {

        $json = json_decode($row->role, true);
        if (is_array($json) && in_array($role, $json)) {
            return true;
        }
        return false;

    }
    return FALSE;

}

/**
 * User has role in section
 * @param $module_id
 * @param $group_id
 * @param $section_id
 * @return bool
 */
function has_section_role($module_id, $group_id, $section)
{

    $CI = &get_instance();
    $CI->db->where("group_id", $group_id);
    $CI->db->where("module_id", $module_id);
    $CI->db->where("section", $section);
    $row = $CI->db->get("module_group_role")->row();
    if ($row) {
        $json = json_decode($row->role, true);
        if (count($json) > 0) {
            return true;
        }
        return false;

    }
    return FALSE;

}

function sims_syncronise($datatype)
{
    $CI = &get_instance();

    $send_data = array(
        'datatype' => $datatype,
        'key' => SIMS_API_KEY
    );

    $send_json_string = json_encode($send_data);

    $CI->curl->create(SIMS_API_URL);
    $CI->curl->options(
        array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/xml'),
            CURLOPT_POSTFIELDS => $send_json_string,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1
        )
    );
    $response = $CI->curl->execute();
    $response = json_decode($response);

    if (json_last_error() === JSON_ERROR_NONE) {
        return $response;
    } else {
        return false;
    }


}

function get_nta_levelname($level, $years_4 = null)
{
    if ($years_4 == '4YPR') {
        return 'UQF : 6';
    } else {
        if ($level == 4) {
            return 'NTA LEVEL : 4';
        } else if ($level == 5) {
            return 'NTA LEVEL : 5';
        } else if ($level == 6) {
            return 'NTA LEVEL  : 6';
        } else if ($level == 7) {
            return 'NTA LEVEL : 7';
        } else if ($level == 8) {
            return 'NTA LEVEL  : 8';
        } else {
            return 'NTA LEVEL : ' . $level;
        }
    }
}

function get_application_deadline()
{
    $CI = &get_instance();
    $row = $CI->common_model->get_application_deadline()->row();
    if ($row) {
        return $row->deadline;
    } else {
        return date('Y-m-d', strtotime('2015-01-01'));
    }
}

function get_year()
    {
    $CI = &get_instance();
    return $CI->common_model->get_year()->row();
    }

    // function get_cooperate()
    // {
    // $CI = &get_instance();
    // return $CI->common_model->get_cooperate_member()->row();
    // }

function get_cooperate($id=null){
    $CI = &get_instance();
    $row = $CI->get_value('member',$id);
    if($row){
        return $row->result();
    }
    return '';
}

function entry_type($val = null)
    {
    $array = array(
        '1' => 'Form IV',
        '1.5'=>'VETA Level III',
        '2' => 'Form VI',
        '3' => 'NTA Level 4 / Technician',
        '4' => 'Diploma/Degree',
        '7' => 'TIOB Diploma',
        '8' => 'Certified Professional/Equivalent',
        '9' => 'NBAA',
        '10' => 'NBMM',
        '11' => 'Banking Certificate(TIOB/CIB/Equivalent)',
        '12' => 'CPA(T) - NBAA',
        '13' => 'CSPS - NBMM'
    );
    if (!is_null($val)) {
        return $array[$val];
    }
    return $array;
}

function entry_member_type($val = null)
{
    $array = array(
        '1' => 'Ordinary Member',
        '4' => 'Student Member',
    );
    if (!is_null($val)) {
        return $array[$val];
    }
    return $array;
}

function member_type($val = null)
{
    $array = array(
        '9' => 'Associate Member',
        '8' => 'Ordinary Member',
        '2' => 'Associate Member',
        '1' => 'Ordinary Member',
        '0' => 'Student Member'
    );
    if (!is_null($val)) {
        return $array[$val];
    }
    return $array;
}

function entry_category($val = null)
{
    $array = array(
        '1' => 'CSEE',
        '2' => 'ACSEE',
        '3' => 'NTA Level 4',
        '4' => 'NBAA',
        '5' => 'NBMM',
    );
    if (!is_null($val)) {
        return $array[$val];
    }
    return $array;
}

function addition_certificate($val = null,$application_type=null)
{
    $array = array(
        100 => 'Employer/Guaranter Certificate',
        101 => 'Others',
        102 => 'Internship Certificate',
        103 => 'Professional Registration',
        104 => 'Curriculum  Vitae (CV)',
        105 => 'Birth Certificate',
    );
    if($application_type < 3){
        unset($array[102]);
        unset($array[103]);
        unset($array[104]);
        unset($array[105]);
    }

    if (!is_null($val)) {
        if (!array_key_exists($val, $array)) {
            return $array[$val];
        }
    }

    return $array;


}

function entry_type_certificate($val = null)
{
    $array = array(
        '1' => 'O-Level Certificate',
        '1.5'=>'VETA Level III',
        '2' => 'A-Level Certificate',
        '3' => 'NTA Level 4 Certificate',
        '4' => 'Diploma/Degree Certificate / Transcript',
        '7' => 'TIOB Certificate / Transcript',
        '8' => 'Certified Professional/Equivalent',
        '9' => 'NBAA Certificate',
        '10' => 'NBMM Certificate',
        '11' => 'Banking Certificate(TIOB/CIB/Equivalent)',
        '12' => 'CPA(T) - NBAA Certificate',
        '13' => 'CSPS - NBMM Certificate'
    );

    if (!is_null($val)) {
        if($val > 101) {
            $array = $array + addition_certificate(null,3);
        }else{
            $array = $array + addition_certificate();

        }

        /*if(!array_key_exists($val,$array)){
           switch ($val){
               case 100:
                   return '';
                   break;
               case 101:
                   return 'Others';
                   break;
               case 102:
                   return 'Birth Certificate';
                   break;
           }
        }else {*/
        return $array[$val];
        // }
    }
    return $array;
}

function entry_type_human($val = null)
{
    $array = array(
        '1' => 'CSEE',
        '1.5'=>'VETA Level III',
        '2' => 'ACSEE',
        '3' => 'NTA Level 4',
        '4' => 'Diploma/Degree',
        '7' => 'TIOB Diploma',
        '8' => 'CPB/Equivalent',
        '9' => 'NBAA',
        '10' => 'NBMM',
        '11' => 'Banking Certificate',
        '12' => 'CPA(T) - NBAA',
        '13' => 'CSPS - NBMM'
    );
    if (!is_null($val)) {
        if (!array_key_exists($val, $array)) {
            return '';
        } else {
            return $array[$val];
        }
    }
    return $array;
}

function certificate_by_entry_type($type)
{
    $return = entry_type_certificate();
    switch ($type) {
        case 1:
            //unset($return[2]);
            unset($return['1.5']);
            unset($return[3]);
            unset($return[4]);
            unset($return[7]);
            unset($return[8]);
            //unset($return[9]);
            unset($return[13]);
            unset($return[11]);
            unset($return[12]);
            break;
        case 1.5:
            unset($return[2]);
            unset($return[1]);
            unset($return[3]);
            unset($return[4]);
            unset($return[7]);
            unset($return[8]);
            break;
        case 2:
            unset($return[3]);
            unset($return['1.5']);
            unset($return[4]);
            unset($return[7]);
            unset($return[8]);
            break;
        case 3:
            unset($return[2]);
            unset($return['1.5']);
            unset($return[4]);
            unset($return[7]);
            unset($return[8]);
            break;
        case 4:
            unset($return[3]);
            unset($return[7]);
            unset($return[8]);
            break;
        case 7:
            unset($return[8]);
            break;
    }
    return $return;
}

function application_type($val = null)
{
    $array = array(
        '1' => 'Banking Certificate',
        '2' => 'Certified Professional Banking',
        '3' => 'Specialist Programme'
    );
    if (!is_null($val)) {
        return $array[$val];
    }
    return $array;
}

function application_start($val = null)
{
    $array = array(
        '1' => 'Banking Certificate',
        '2' => 'Certified Professional Banking',
//        '3' => 'Specialist Programme'
    );
    if (!is_null($val)) {
        return $array[$val];
    }
    return $array;
}

function receive_member_type($val = null)
{
    $array = array(
        '0' => 'Student Members',
        '1' => 'Ordinary Member'
    );
    if (!is_null($val)) {
        return $array[$val];
    }
    return $array;
}

function get_code($val = null)
{
    $array = array(
        '1001' => 'Banking Certificate',
        '1002' => 'Certified Professional Banking',
        '1003' => 'Specialist Programme'
    );
    if (!is_null($val)) {
        return $array[$val];
    }
    return $array;
}

function receive_programme_code($val = null)
{
    $array = array(
        '1001' => 'Banking Certificate',
        '1002' => 'Certified Professional Banking',
        '1003' => 'Specialist Programme',
        '1005' => 'IT certificate'
    );
    if (!is_null($val)) {
        return $array[$val];
    }
    return $array;
}


function attachment_required($inputname, $label = null)
{
    $CI = &get_instance();

    if (isset($_FILES[$inputname]['name']) && !empty($_FILES[$inputname]['name'])) {
        return true;
    }
    $CI->form_validation->set_rules($inputname, (!is_null($label) ? $label : $inputname), 'required');
    return false;

}

function grade_list()
{
    return array('A', 'B+', 'B', 'C', 'D', 'E', 'F', 'S');
}

function is_grade_greater_equal($grade1, $grade2)
{
    $grades = grade_list();
    $index_grade1 = array_search($grade1, $grades);
    $index_grade2 = array_search($grade2, $grades);
    if ($index_grade1 <= $index_grade2) {
        return true;
    }

    return false;
}

function grade_point($grade)
{
    array('A' => 10, 'B+' => 9, 'B' => 8, 'C' => 7, 'D' => 6, 'E' => 5, 'S' => 4, 'F' => 3);
}

function get_file_mimetype($filename)
{
    $CI = &get_instance();
    $CI->load->library("FileMimeType");
    return $CI->filemimetype->get_mime_type($filename);
}

function application_status($id = null)
{
    $array = array(
        '0' => 'Incomplete',
        '1' => 'Submitted',
        '2' => 'Rejected',
        '3' => 'Shortlisted',
        '4' => 'Selected'

    );

    if (!is_null($id)) {
        if (!array_key_exists($id, $array)) {
            return '';
        } else {
            return $array[$id];
        }
    }
    return $array;
}

function current_status($id = null)
{
    $array = array(
        '0' => 'Applicant',
        //'6' => 'Selected',
        //'2' => 'Rejected',
        '4' => 'Member',
        '8' => 'Ordinary Member',
        '9' => 'Associate Member'
    );

    if (!is_null($id)) {

        if (!array_key_exists($id, $array)) {
            return '';
        } else {
            return $array[$id];
        }
    }
    return $array;
}

function search_status($id = null)
{
    $array = array(
        '0' => 'Incomplete',
        '1' => 'Submitted',

    );

    if (!is_null($id)) {

        if (!array_key_exists($id, $array)) {
            return '';
        } else {
            return $array[$id];
        }
    }
    return $array;
}

function exam_fee($id = null)
{
    $array = array(
        '1' => 'Annual fee',
        '2' => 'Exam fee'
    );

    if (!is_null($id)) {
        if (!array_key_exists($id, $array)) {
            return '';
        } else {
            return $array[$id];
        }
    }
    return $array;
}


function programme_duration($application_type, $entry)
{
    if ($application_type == 2) {
        return 3;
    } else {
        $d = 3;
        switch ($entry) {
            CASE 1 :
                $d = 3;
                break;
            case 2:
                $d = 2;
                break;
            case 3:
                $d = 2;
                break;
            default:
                $d = 3;
                break;
        }

        return $d;
    }
}

function experience($id = null)
{
    $array = array(
        '1' => 'Internship',
        '2' => 'Professional Training',
        '3' => 'Work Experience'
    );

    if (!is_null($id)) {
        return $array[$id];
    }
    return $array;
}

function yes_no($id=null){
    $array=array(
        '1'=>'Yes',
        '0'=>'No'
    );
    if(!is_null($id)){
        return $array[$id];
    }

    return $array;
}

function CSEE_type($id=null){
    $array=array(
        '1'=>'Tanzania (From NECTA)',
        '0'=>'Outside Tanzania (Foreign)'
    );
    if(!is_null($id)){
        return $array[$id];
    }

    return $array;
}

function change_status($id=null){
        $array=array(
            '0' => 'Applicant',
            // '6' => 'Selected',
            // '2' => 'Rejected',
            '4' => 'Member',
            '8' => 'Ordinary Member',
            '9' => 'Associate Member'
        );


    if(!is_null($id)){
        return $array[$id];
    }

    return $array;
}


function check_student($id=null,$regno=null){
    $CI = &get_instance();
    $row = $CI->common_model->get_student($id, $regno)->row();
    if($row){
        return $row->registration_number;
    }

}

function get_country($id,$column='Country'){
    $CI = &get_instance();
    $row = get_value('nationality',$id,null);
    if($row){
        return $row->{$column};
    }
    return '';
}


function get_centre($id,$column='center_name'){
    $CI = &get_instance();
    $row = get_value('examination_centers',$id,null);
    if($row){
        return $row->{$column};
    }
    return '';
}

function get_courses($id,$column='name'){
    $CI = &get_instance();
    $row = get_value('courses',$id,null);
    if($row){
        return $row->{$column};
    }
    return '';
}

function  programme($id=null,$column='name'){
    $CI = &get_instance();

    $row = get_value('programme',$column);
    if($row){
        return $row->{$column};
    }

    if(!is_null($id)){
        $CI->db->where('id',$id);
    }

    return $CI->db->get('programme');
}

function levels($id=null){

    $array = array(
        '1' => 'Level I',
        '2' => 'Level II',
        '3' => 'NONE'

    );

    if(!is_null($id)){
        return $array[$id];
    }

    return $array;
}


function get_venue($id,$column='name'){
    $CI = &get_instance();
    $row = get_value('venue',$id,null);
    if($row){
        return $row->{$column};
    }
    return '';
}

function recommendation_rate($id=null){
    $array= array(
        '5'=>'Excellent',
        '4'=>'Very Good',
        '3'=>'Good',
        '2'=>'Average',
        '1'=>'Low',
    );

    if(!is_null($id)){
        return $array[$id];
    }

    return $array;
}

function recommendation_overall($id=null){
    $array= array(
        '4'=>'Highly Recommended',
        '3'=>'Recommended',
        '2'=>'Recommended with some reservations',
        '1'=>'Not Recommended',
    );

    if(!is_null($id)){
        return $array[$id];
    }

    return $array;
}

function get_exam_date($code){
    $CI = &get_instance();
    $row = $CI->timetable_model->get_exam_register($code)->row();
    if($row){
        return $row->exam_date;
        }

}

function get_exam_time($code){
    $CI = &get_instance();
    $row = $CI->timetable_model->get_exam_register($code)->row();
    if($row){
        return $row->time;
    }

}

function get_exam_center($code){
    $CI = &get_instance();
    $row = $CI->timetable_model->get_exam_register($code)->row();
    $row = get_value('examination_centers',$row->centre_id,'');
    if($row){
        return $row -> center_name;
    }

}

function get_exam_venue($code){
    $CI = &get_instance();
    $row = $CI->timetable_model->get_exam_register($code)->row();
    if($row){
        return $CI->timetable_model->get_venue($row->venue)->row()->name;
    }

}

if (!function_exists('get_file_extension')) {

    function get_file_extension($filename) {
        $filename = strtolower($filename);
        $ext = explode(".", $filename);
        $n = count($ext) - 1;
        $ext = $ext[$n];
        return $ext;
    }

function get_marital_status($id,$column='name'){
 $CI = &get_instance();
 $row = get_value('maritalstatus',$id,null);
 if($row){
     return $row->{$column};
  }
   return '';
}

function get_disability($id,$column='name'){
   $CI = &get_instance();
   $row = get_value('disability',$id,null);
   if($row){
      return $row->{$column};
   }
   return '';
}


function payment_status($id = null)
{
    $array = array(
        '1' => 'paid',
        '2' => 'Unpaid'

    );

    if (!is_null($id)) {

        if (!array_key_exists($id, $array)) {
            return '';
        } else {
            return $array[$id];
        }
    }
    return $array;
}

function payments_type($id = null)
{
    $array = array(
        '1' => 'Annual subscription fee',
        '2' => 'Exam fee',
        '3' => 'All'

    );

    if (!is_null($id)) {

        if (!array_key_exists($id, $array)) {
            return '';
        } else {
            return $array[$id];
        }
    }
    return $array;
}

}

/*** start here********/

function GetNumberOfDaysBetweenDates($from,$to='')
{
    $from=date('Y-m-d',strtotime($from));
  if($to=='')
  {
      $to=date('Y-m-d');
      
  }
    $earlier = new DateTime($from);
    $later = new DateTime($to);

    $diff = $later->diff($earlier)->format("%a");
    return $diff;
}




function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function sendJsonlOverPost($url, $postdata)
{


    $headers = array(
        "Content-type: application/json"
    ,"Content-length: ".strlen($postdata)
    ,"Connection: close"
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;

}

function print_r_reverse($input)
{
    $output = str_replace(['[', ']'], ["'", "'"], $input);
    $output = preg_replace('/=> (?!Array)(.*)$/m', "=> '$1',", $output);
    $output = preg_replace('/^\s+\)$/m', "),\n", $output);
    $output = rtrim($output, "\n,");
    return eval("return $output;");
}

function ReturnMobileOperator($mobileno){

    $result = substr($mobileno, 0, 5);
    if($result=='25571' or $result=='25565'   or $result=='25567'){
        return "TIGO PESA";
    }

    elseif($result=='25578' or $result=='25568'){
        return "AIRTEL MONEY";
    }
    elseif($result=='25577'){
        return "EASY PESA";
    }
    elseif($result=='25575' or  $result=='25576' ){
        return "M PESA";
    }else{
        return 'Mobile Money';
    }


}




