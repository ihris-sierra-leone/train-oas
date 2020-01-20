<?php
$applicant = $this->db->query("SELECT ap.*,pc.choice1,pc.choice2, pc.choice3 FROM application as ap  
INNER JOIN application_programme_choice as pc ON (ap.id=pc.applicant_id) WHERE ap.AYear='$ayear' AND
 (pc.choice1='$programme' OR pc.choice2='$programme' OR pc.choice2='$programme') ")->result();


foreach ($applicant as $key=>$value){
    $applicant_id = $value->id;

    $choice = 0;
    //Get Choice Number
    if($value->choice1 == $programme){
        $choice = 1;
    }else if($value->choice2 == $programme){
        $choice = 2;
    }if($value->choice3 == $programme){
        $choice = 3;
    }

    //If choice found
    if($choice > 0){
        //Create default array with Not eligible status
        $applicant_data=array(
            'applicant_id'=>$value->id,
            'ProgrammeCode'=>$programme,
            'entry_category'=>$value->entry_category,
            'choice'=>$choice,
            'AYear'=>$ayear,
            'status'=>0,
            'comment'=>'',
            'point'=>0,
        );
        $point = 0;
        $pass = 0;
        $status = 0;
        $remark = '';
        //Switch to Entry Type
       switch ($value->entry_category){
           case 1:
               $setting_info = $this->setting_model->get_selection_criteria($programme,$ayear,1); // get selection criteria
               //Form IV Entry Qualification
                 include 'entryMode_FormIV.php';
               $applicant_data['status'] = $status;
               $applicant_data['comment'] = $remark;
               $applicant_data['point'] = $point;
               $applicant_data['sitting_no'] = $sitting_no;
               break;
           case 1.5:
               //VETA NVL III Entry Qualification
               include 'eligibility_VETA.php';

               break;

           case 2:
               $setting_info = $this->setting_model->get_selection_criteria($programme,$ayear,2);
               //Form VI Entry Qualification
               include 'entryMode_FormVI.php';
               $applicant_data['status'] = $status;
               $applicant_data['comment'] = $remark;
               $applicant_data['point'] = $point;
               $applicant_data['sitting_no'] = $sitting_no;
               break;

           case 3:
               //NTA Level 4 Entry Qualification
               $setting_info = $this->setting_model->get_selection_criteria($programme,$ayear,3);
               //Form VI Entry Qualification
               include 'entryMode_NTAL4.php';
               $applicant_data['status'] = $status;
               $applicant_data['comment'] = $remark;
               $applicant_data['point'] = $point;
               $applicant_data['form4_point'] = $FORM4_POINT;
               $applicant_data['gpa'] = $NTAL4_GPA;
               break;
           case 4:
               //Diploma Entry Qualification
               $setting_info = $this->setting_model->get_selection_criteria($programme,$ayear,4);
               //Form VI Entry Qualification
               include 'entryMode_Diploma.php';
               $applicant_data['status'] = $status;
               $applicant_data['comment'] = $remark;
               $applicant_data['point'] = $point;
               $applicant_data['form4_point'] = $FORM4_POINT;
               $applicant_data['form6_point'] = $FORM6_POINT;
               $applicant_data['gpa'] = $NTAL4_GPA;
               break;
           default:


               break;
       }



       $where1 = array(
           'applicant_id'=>$value->id,
           'ProgrammeCode'=>$programme,
           'entry_category'=>$value->entry_category,
       );

       $already_exist = $this->db->where($where1)->get('application_elegibility')->row();
        if($already_exist){
            $this->db->update('application_elegibility', $applicant_data,array('id'=>$already_exist->id));
        }else {
        $this->db->insert('application_elegibility', $applicant_data);
       }


    }






 }

 ?>