<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/14/17
 * Time: 12:37 PM
 */
class Applicant_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function new_applicant($applicant_data, $password)
    {
        $this->db->trans_start();
        $user_data = array(
            'firstname' => $applicant_data['FirstName'],
            'lastname' => $applicant_data['LastName'],
            'phone' => '',
            'title' => null,
            'campus_id' => 1,
            'activation_code'=>generatePIN(6),
            'access_area' => 0,
            'access_area_id' => 0
        );
        $user_id = $this->ion_auth_model->register($applicant_data['username'], $password, $applicant_data['Email'], $user_data, array(5));
        if ($user_id) {
            $year = $this->common_model->get_academic_year()->row()->AYear;
            $total_lenth_userid = strlen($user_id);
            if($total_lenth_userid == 1){
                $unique_number = $year.'-0000'.$user_id.'-TIOB';
            }elseif($total_lenth_userid == 2){
                $unique_number = $year.'-0OO'.$user_id.'-TIOB';
            }elseif($total_lenth_userid == 3){
                $unique_number = $year.'-00'.$user_id.'-TIOB';
            }elseif($total_lenth_userid == 4){
                $unique_number = $year.'-0'.$user_id.'-TIOB';
            }
            unset($applicant_data['username']);
            $applicant_data['admission_number'] = $unique_number;
            $applicant_data['createdby'] = $user_id;
            $applicant_data['user_id'] = $user_id;
            $this->db->insert('application', $applicant_data);
            $applicant_id = $this->db->insert_id();
            $this->add_applicant_step($applicant_id, 'BASIC', $applicant_id);
            $this->db->update('users',array('applicant_id'=>$applicant_id),array('id'=>$user_id));
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    function add_applicant_step($applicant_id, $section, $value)
    {
        $row = $this->db->where('applicant_id', $applicant_id)->get('application_steps')->row();
        if ($row) {
            $back_array = json_decode($row->data, true);
            $back_array[$section] = $value;
            $json = json_encode($back_array);
            $this->db->update('application_steps', array('data' => $json), array('id' => $row->id));
        } else {
            $json = json_encode(array($section => $value));
            $this->db->insert('application_steps', array('applicant_id' => $applicant_id, 'data' => $json));
        }
    }

    function get_applicant($id = null, $form4_index = null)
    {
        if (is_null($id) && is_null($form4_index)) {
            return false;
        }

        if (!is_null($id)) {
            $this->db->where('id', $id);
        }

        if (!is_null($form4_index)) {
            $this->db->where('form4_index', $form4_index);
        }
        return $this->db->get("application")->row();

    }

     function get_member($id = null)
    {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }

        return $this->db->get("students")->row();

    }

    function get_member_transcript($id = null)
      {
        if (!is_null($id)) {
            $this->db->where('id', $id);
        }
        return $this->db->get("students")->row();

    }


    function get_applicant_section($applicant_id)
    {
        $data = $this->db->where('applicant_id', $applicant_id)->get('application_steps')->row();
        if ($data) {
            $array = json_decode($data->data, true);
            return $array;
        } else {
            return array();
        }

    }
    
    function get_examination_payments($applicant_id)
    {
        $data = $this->db->where('applicant_id', $applicant_id)->get('examinations_payment')->row();
        if ($data) {
            $array = $data;
           // $array = json_decode($data->data, true);
            return $array;
        } else {
            return array();
        }

    }

    function update_applicant($array_data, $where)
    {
        return $this->db->update("application", $array_data, $where);
    }

    function get_nextkin_info($applicant_id, $primary = null)
    {

        $this->db->where('applicant_id', $applicant_id);
        if (!is_null($primary)) {
            $this->db->where('is_primary', $primary);
        }
        $this->db->order_by('is_primary', 'DESC');
        return $this->db->get("application_nextkin_info");
    }

    function add_nextkin_info($data)
    {
        $where = array(
            'applicant_id' => $data['applicant_id'],
            'is_primary' => $data['is_primary']
        );
        $this->db->where($where);
        $check = $this->db->get("application_nextkin_info")->row();
        if ($check) {
            return $this->db->update("application_nextkin_info", $data, $where);
        } else {
            return $this->db->insert("application_nextkin_info", $data);
        }
    }

    function get_paid_amount($applicant_id)
    {
        $year=$this->common_model->get_academic_year()->row();

        $row = $this->db->query("SELECT amount FROM fee_statement WHERE applicant_id='$applicant_id' and flag=1 and academic_year='$year->AYear' ")->row();
        if ($row) {
            return $row->amount;
        }
        return 0;
    }

     function get_amount_paid($applicant_id)
     {
        $year=$this->db->query("SELECT * FROM ayear WHERE Status=1 ")->result();
        foreach ($year as $key => $Ayear) {
        $ayear=$Ayear->AYear;
        }
        $row = $this->db->query("SELECT SUM(amount) as amount,SUM(charges) as charges FROM examinations_payment WHERE applicant_id='$applicant_id' AND academic_year='$ayear'")->row();
        if ($row) {
            return $row->amount + $row->charges;
        }
        return 0;
    }

   function get_last_year($uid)
     {
        if(!is_null($uid)){
            $this->db->where('user_id',$uid);
        }
        $this->db->order_by('academic_year','DESC','LIMIT 1');

        return $this->db->get('annual_fees')->row();
    }

     function get_entry_year($applicant_id)
     {
        if(!is_null($applicant_id)){
            $this->db->where('id',$applicant_id);
        }
        $this->db->order_by('AYear','ASC','LIMIT 1');

        return $this->db->get('application')->row();
    }


    function get_annual_paid($applicant_id)
    {
        $acyear = $this->common_model->get_academic_year()->row()->AYear;
        $row = $this->db->query("SELECT amount+charges as amount FROM temp_annual_fees WHERE applicant_id='$applicant_id' AND academic_year='$acyear'")->row();
        if ($row) {
            return $row->amount;
        }
        return 0;
    }

    /**
     * Run this function only if Category  is 1 = O-Level or 2 = A-Level
     * under Tanzania Country
     * @param $category
     * @param $id
     * @param $action
     */
    function trigger_necta_subjects($category, $year)
    {
        if ($category == 1 || $category == 2) {
            $row = $this->db->where(array('category' => $category, 'year' => $year))->get("necta_check_subject")->row();
            if ($row) {
                if($row->status == 0){
                    execInBackground('response get_necta_subject '.$row->id);
                }
            } else {
                $insert = $this->db->insert('necta_check_subject',array('category' => $category, 'year' => $year));
                $last_id = $this->db->insert_id();
                execInBackground('response get_necta_subject '.$last_id);
            }

        }
    }

    /**
     * Run this function only if Category  is 1 = O-Level or 2 = A-Level
     * under Tanzania Country
     * @param $category
     * @param $id
     * @param $action
     */
    function trigger_necta_results($category, $id,$action){

        if ($category == 1 || $category == 2) {
            $this->db->update('application_education_authority',array('hide'=>1),array('id'=>$id));
            $row = $this->db->where('id',$id)->get('application_education_authority')->row();
            $array = array(
                'category' => $category,
                'authority_id' => $id,
                'applicant_id'=> $row->applicant_id,
                'action'=> $action,
                'route' => 'NECTA',
                'action_time'=>date('Y-m-d H:i:s')
            );
            $insert = $this->db->insert('necta_tmp_result',$array);
            $last_id = $this->db->insert_id();
            execInBackground('response get_necta_results '.$last_id);

        }else if($category == 4){
            $this->db->update('application_education_authority',array('hide'=>1),array('id'=>$id));
            $row = $this->db->where('id',$id)->get('application_education_authority')->row();
            $array = array(
                'category' => $category,
                'authority_id' => $id,
                'route' => 'NACTE',
                'applicant_id'=> $row->applicant_id,
                'action'=> $action,
                'action_time'=>date('Y-m-d H:i:s')
            );
            $insert = $this->db->insert('necta_tmp_result',$array);
            $last_id = $this->db->insert_id();
            execInBackground('response get_necta_results '.$last_id);
        }
    }


    function add_education($data, $subject = null, $grade = null, $year = null, $edit_id = null)
    {
        $this->db->trans_start();

        // Only for Tanzania Country
        if($data['country'] == 220) {
            $this->trigger_necta_subjects($data['certificate'], $data['completed_year']);
        }

        if (is_null($edit_id)) {
            $this->db->insert("application_education_authority", $data);
            $last_id = $this->db->insert_id();

            // Only for Tanzania Country
            if($data['country'] == 220) {
                $this->trigger_necta_results($data['certificate'], $last_id,'new');
            }

        } else {
            $previous = $this->db->where('id',$edit_id)->get('application_education_authority')->row();
            $this->db->update("application_education_authority", $data, array('id' => $edit_id));
            $last_id = $edit_id;

            if($data['country'] == 220 && $previous) {
                if($previous->api_status == 0){
                    $this->trigger_necta_results($data['certificate'], $last_id,'edit');
                }else if($data['index_number'] != $previous->index_number){
                    $this->db->delete('application_education_subject',array('authority_id'=>$previous->id));
                    $this->trigger_necta_results($data['certificate'], $last_id,'edit');
                }
            }

        }

        if (is_array($subject) && is_array($grade) && is_array($year)) {
            foreach ($subject as $key => $value) {
                $row_grade = $grade[$key];
                $row_year = $year[$key];
                $row_subject = $value;
                if ($row_grade <> '' && $row_subject <> '' && $row_year <> '') {
                    $array_tmp = array(
                        'certificate' => $data['certificate'],
                        'authority_id' => $last_id,
                        'subject' => strtoupper($row_subject),
                        'grade' => strtoupper($row_grade),
                        'year' => $row_year,
                        'applicant_id' => $data['applicant_id'],
                    );

                    $row_check = $this->db->where(array('authority_id' => $array_tmp['authority_id'], 'applicant_id' => $array_tmp['applicant_id'], 'subject' => $array_tmp['subject']))->get("application_education_subject")->row();
                    if ($row_check) {
                        $this->db->update('application_education_subject', $array_tmp, array('id' => $row_check->id));
                    } else {
                        $this->db->insert('application_education_subject', $array_tmp);
                    }
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }

    }

    function get_education_bg($id = null, $applicant_id = null)
    {
        if (is_null($id) && is_null($applicant_id)) {
            return array();
        }
        if (!is_null($applicant_id)) {
            $this->db->where("applicant_id", $applicant_id);
        }
        if (!is_null($id)) {
            $this->db->where("id", $id);
        }

        $this->db->order_by("certificate", 'ASC');
        return $this->db->get("application_education_authority")->result();
    }

    function get_education_subject($applicant_id = null, $authority_id = null)
    {
        if (is_null($authority_id) && is_null($applicant_id)) {
            return array();
        }
        if (!is_null($applicant_id)) {
            $this->db->where("applicant_id", $applicant_id);
        }
        if (!is_null($authority_id)) {
            $this->db->where("authority_id", $authority_id);
        }

        return  $this->db->get("application_education_subject")->result();
    }

    function add_attachment($data)
    {
        return $this->db->insert('application_attachment', $data);
    }

    function get_attachment($applicant_id, $certificate = null)
    {
        if (!is_null($certificate)) {
            $this->db->where("certificate", $certificate);
        }
        $this->db->where("applicant_id", $applicant_id);

        return $this->db->get("application_attachment")->result();
    }

    function get_programme_for_choice($application_type)
    {
        $where = " WHERE type= $application_type";
        return $this->db->query("SELECT * FROM programme $where ORDER BY Name")->result();
    }

    function add_programme_choice($data)
    {
        $row = $this->db->where('applicant_id', $data['applicant_id'])->get("application_programme_choice")->row();
        if ($row) {
            return $this->db->update('application_programme_choice', $data, array('id' => $row->id));

        } else {
            return $this->db->insert('application_programme_choice', $data);
        }
    }

    function get_programme_choice($applicant_id)
    {
        return $this->db->where('applicant_id', $applicant_id)->get("application_programme_choice")->row();
    }


    function allow_submission($applicant_id)
    {
        $applicant_info = $this->get_applicant($applicant_id);
        $certificate_used = array();
        $return = array();
        $completed_form6=array();
        $certificate_list = $this->get_education_bg(null, $applicant_id);
        foreach ($certificate_list as $key => $value) {
            $certificate_used[$value->certificate]=$value->certificate;
            switch ($value->certificate) {
                case 1:
                    //O - level
//                    $row = $this->get_attachment($applicant_id, $value->certificate);
//                   if (count($row) == 0) {
//                        $return[] = 'O-Level Certificate missing !!';
//                    }
                    $row = $this->db->where(array('applicant_id'=>$applicant_id,'certificate'=>$value->certificate))->get('application_education_subject')->result();
                    if (count($row) < 7) {
                        $return[] = 'O-Level Subject missing, At least seven subjects required !!';
                    }

                    break;
                case 1.5:
                    //VETA NVL III check attachment
//                    $row = $this->get_attachment($applicant_id, $value->certificate);
//                    if (count($row) == 0) {
//                        $return[] = 'VETA Certificate missing !!';
//                    }
                    break;
                case 2:
                    $completed_form6[$value->completed_year] = $value->completed_year;
                    if($value->completed_year < 2007) {
//                        $row = $this->get_attachment($applicant_id, $value->certificate);
//                        if (count($row) == 0) {
//                            $return[] = 'A-Level Certificate missing !!';
//                        }
                        $row = $this->db->where(array('applicant_id' => $applicant_id, 'certificate' => $value->certificate))->get('application_education_subject')->result();
                        if (count($row) < 4) {
                            $return[] = 'A-Level Subject missing, At least four subjects required !!';
                        }
                    }
                    break;
                   case 3:
//                    $row = $this->get_attachment($applicant_id, $value->certificate);
//                    if (count($row) == 0) {
//                        $return[] = 'NTA Level 4  Certificate missing !!';
//                    }
                    break;
                     case 4:
//                    $row = $this->get_attachment($applicant_id, $value->certificate);
//                    if (count($row) == 0) {
//                        $return[] = 'Diploma  Certificate missing !!';
//                    }
                   break;
            }
        }

        //Check Entry Type
        if(!in_array($applicant_info->entry_category,$certificate_used)){
            if($applicant_info->entry_category == 2 || $applicant_info->entry_category == 4 && in_array(2007,$completed_form6)){

            }else {
                $return[] = entry_type($applicant_info->entry_category) . '  Certificate missing !!';
            }
        }

        $attachment_list = $this->get_attachment($applicant_id);
        foreach ($attachment_list as $ky => $vy) {
            if ($vy->certificate < 10) {
                $check_result = $this->db->where(array('certificate' => $vy->certificate, 'applicant_id' => $applicant_id))->get("application_education_authority")->row();
                if (!$check_result) {
                    $return[] = entry_type($vy->certificate) . '  Result missing under Education background !!';
                }
            }
        }

//        $row = $this->get_attachment($applicant_id, 100);
//        if (count($row) == 0) {
//            $return[] = 'Birth Certificate missing !!';
//        }
        if (count($return) == 0) {
            return 1;
        }

        return $return;

    }

    function add_experience($data,$id=null){
      if(!is_null($id)){
          return $this->db->update('application_experience',$data,array('id'=>$id));
      }  else{
          return $this->db->insert('application_experience',$data);
      }
    }

    function get_experience($applicant_id=null,$id=null,$type=null){
       if(!is_null($applicant_id)){
           $this->db->where('applicant_id',$applicant_id);
       }

       if(!is_null($id)){
           $this->db->where('id',$id);
       }
        if(!is_null($type)){
            $this->db->where('type',$type);
        }

        return $this->db->get('application_experience');
    }

    function add_applicant_referee($data)
    {
        $where = array(
            'applicant_id' => $data['applicant_id'],
            'is_primary' => $data['is_primary']
        );
        $this->db->where($where);
        $check = $this->db->get("application_referee")->row();
        if ($check) {
            return $this->db->update("application_referee", $data, $where);
        } else {
            return $this->db->insert("application_referee", $data);
        }
    }

    function get_applicant_referee($applicant_id, $primary = null)
    {

        $this->db->where('applicant_id', $applicant_id);
        if (!is_null($primary)) {
            $this->db->where('is_primary', $primary);
        }
        $this->db->order_by('is_primary', 'DESC');
        return $this->db->get("application_referee");
    }

    function add_applicant_sponsor($data)
    {
        $where = array(
            'applicant_id' => $data['applicant_id']
        );
        $this->db->where($where);
        $check = $this->db->get("application_sponsor")->row();
        if ($check) {
            return $this->db->update("application_sponsor", $data, $where);
        } else {
            return $this->db->insert("application_sponsor", $data);
        }
    }

    function get_applicant_sponsor($applicant_id=null,$id=null)
    {
       if(!is_null($applicant_id)) {
           $this->db->where('applicant_id', $applicant_id);
       }
       if(!is_null($id)){
           $this->db->where('id', $id);
       }
        return $this->db->get("application_referee");
    }

    function add_applicant_employer($data)
    {
        $where = array(
            'applicant_id' => $data['applicant_id']
        );
        $this->db->where($where);
        $check = $this->db->get("application_employer")->row();
        if ($check) {
            return $this->db->update("application_employer", $data, $where);
        } else {
            return $this->db->insert("application_employer", $data);
        }
    }

    function get_applicant_employer($applicant_id=null,$id=null)
    {
        if(!is_null($applicant_id)) {
            $this->db->where('applicant_id', $applicant_id);
        }
        if(!is_null($id)){
            $this->db->where('id', $id);
        }
        return $this->db->get("application_employer");
    }

    function record_referee_recomendation($data){
     $row = $this->db->where(array('applicant_id'=>$data['applicant_id'],'referee_id'=>$data['referee_id']))->get('application_referee_recommendation')->row();

     if($row){
         $data['modifiedon'] = date('Y-m-d H:i:s');
         return $this->db->update('application_referee_recommendation',$data,array('id'=>$row->id));
     }else{
         $data['createdon'] = date('Y-m-d H:i:s');
         return $this->db->insert('application_referee_recommendation',$data);
     }

    }

    function get_referee_recomendation($applicant_id=null,$referee_id=null,$id=null){
        if(is_null($applicant_id) && is_null($referee_id) && is_null($id)){
            return false;
        }

        if(!is_null($applicant_id)){
            $this->db->where('applicant_id',$applicant_id);
        }

        if(!is_null($referee_id)){
            $this->db->where('referee_id',$referee_id);
        }

        if(!is_null($id)){
            $this->db->where('id',$id);
        }

        return $this->db->get('application_referee_recommendation');
    }

    function necta_applicant_add_result($array_subject){
        $where = array(
            'applicant_id' => $array_subject['applicant_id'],
            'authority_id' => $array_subject['authority_id'],
            'subject' => $array_subject['subject'],
        );

        $check = $this->db->where($where)->get("application_education_subject")->row();
        if($check){
            //update
            return $this->db->update('application_education_subject',$array_subject,array('id'=> $check->id));
        }else{
            //insert
            return $this->db->insert('application_education_subject',$array_subject);
        }
    }

    

}
