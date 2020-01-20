<?php

/**
 * Created by Atom.
 * User: festus
 * Date: 10/09/18
 * Time: 9:33 AM
 */
class PaymentApi extends  CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function receive_payment()
    {
            $data_json = file_get_contents("php://input");
            $data = json_decode($data_json, TRUE);
            $get_number = substr($data['reference'],6);
            $applicant_id = substr($data['reference'], 7);
            $user_id = $this->db->get_where('application', array('id'=>$applicant_id))->row()->user_id;
            $flag = substr($data['reference'], 6, 1);
            $amount_received=$data['amount'];
            $number=$data['receipt'];
            $trans_timestamp = $data['timestamp'];
            $ayear = $this->common_model->get_academic_year()->row()->AYear;
            $session = $this->common_model->get_academic_year()->row()->semester;
            $member_type=$this->db->get_where('application',array('id'=>$applicant_id))->row()->member_type;
            // $annual_amount = $this->db->get_where('exam_fee', array('member_category'=>$member_type))->row()->annual_amount;
            $annual_amount = 50;
            if($flag==1){
              $check_receipt = $this->db->where("receipt",$data['receipt'])->get("application_payment")->row();
              if(!$check_receipt){
            $registration_fee = $amount_received - $annual_amount;

            $return = array(
                'status'=>'',
                'description'=>'SUCCESS'
                );
            $fee_statement=array(
                'user_id'=>$user_id,
                'timestamp'=>$trans_timestamp,
                'receipt'=>$data['receipt'],
                'amount'=>$amount_received,
                'academic_year'=>$ayear
                );
             $this->db->insert('fee_statement',$fee_statement);

             $annual_fee_array=array(
             'msisdn'=>$number,
             'reference'=>$data['reference'],
             'applicant_id'=>$applicant_id,
             'user_id'=>$user_id,
             'timestamp'=>$trans_timestamp,
             'receipt'=>$data['receipt'],
             'amount'=>$annual_amount,
             'academic_year'=>$ayear
             );

             $add=$this->db->insert('annual_fees',$annual_fee_array);

             $application_fee_array=array(
              'reference'=>$data['reference'],
              'applicant_id'=>$applicant_id,
              'timestamp'=>$trans_timestamp,
              'receipt'=>$data['receipt'],
              'amount'=>$registration_fee,
              'academic_year'=>$ayear,
              'account_number'=>$data['account_number'],
              'token'=>$data['token']
              );

             $this->db->insert('application_payment',$application_fee_array);
             $return['status'] = 1;

           }else{
             $return = array(
               'status'=>0,
               'description'=>'DUPLICATE RECEIPT'
                );
            }
           echo json_encode($return);

           }elseif($flag==2){
             $check_receipt = $this->db->where("receipt",$data['receipt'])->get("examinations_payment")->row();
             if(!$check_receipt){
             $return = array(
               'status'=>'',
               'description'=>'SUCCESS'
             );

             $fee_statement=array(
                 'user_id'=>$user_id,
                 'timestamp'=>$trans_timestamp,
                 'receipt'=>$data['receipt'],
                 'amount'=>$amount_received,
                 'academic_year'=>$ayear
                 );
              $this->db->insert('fee_statement',$fee_statement);

             $exam_array=array(
             'reference'=>$data['reference'],
             'applicant_id'=>$applicant_id,
             'user_id'=>$user_id,
             'timestamp'=>$trans_timestamp,
             'receipt'=>$data['receipt'],
             'amount'=>$amount_received,
             'academic_year'=>$ayear,
             'session' => $session
             );
           $this->db->insert('examinations_payment',$exam_array);
           $return['status'] = 1;

         }else{
           $return = array(
             'status'=>0,
             'description'=>'DUPLICATE RECEIPT'
              );
         }
         echo json_encode($return);

         }elseif($flag==3){
           $check_receipt = $this->db->where("receipt",$data['receipt'])->get("temp_annual_fees")->row();
           if(!$check_receipt){
           $return = array(
             'status'=>'',
             'description'=>'SUCCESS'
              );

              $fee_statement=array(
                  'user_id'=>$user_id,
                  'timestamp'=>$trans_timestamp,
                  'receipt'=>$data['receipt'],
                  'amount'=>$amount_received,
                  'academic_year'=>$ayear
                  );
               $this->db->insert('fee_statement',$fee_statement);

               $annual_fee_array=array(
               'reference'=>$data['reference'],
               'applicant_id'=>$applicant_id,
               'user_id'=>$user_id,
               'timestamp'=>$trans_timestamp,
               'receipt'=>$data['receipt'],
               'amount'=>$amount_received,
               'academic_year'=>$ayear
               );
               $this->db->insert('temp_annual_fees',$annual_fee_array);
               $return['status'] = 1;

             }else{
               $return = array(
                 'status'=>0,
                 'description'=>'DUPLICATE RECEIPT'
                  );
             }
             echo json_encode($return);
          }


    }

}
