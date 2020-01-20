<?php
include  'entryMode_FormIV.php';


$FORM6_POINT = 0;
$NTAL4_GPA = 0;
if($status == 1) {
    $status = 0;
    $pass = 0;
    $gpa_required = $setting_info->gpa_pass;


    //Get all Form IV Certificates
    $NTAL4_CERTIFICATE = $this->db->where(array('certificate' => 3, 'applicant_id' => $applicant_id))->order_by('division','DESC')->get('application_education_authority')->row();
  if($NTAL4_CERTIFICATE){
      if($NTAL4_CERTIFICATE->division >= $gpa_required){
          $NTAL4_GPA = $NTAL4_CERTIFICATE->division;
          $status = 1;
      }else{
          $remark = 'Less GPA  for Certificate';
          $status = 0;
      }
  }else{
      $remark = 'No Certificate GPA found';
      $status = 0;
  }



}