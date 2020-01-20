<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 9:33 AM
 */
class Api extends  CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function receive_payment()
    {
        $data_json = file_get_contents("php://input");

        $data = json_decode($data_json, TRUE);

        if ($data['ACTION'] == 'VALIDATE') {
            $reference = $data['REFERENCE'];
            $myreference = substr($reference,4);
            $client = $this->applicant_model->get_applicant($myreference);

            $array = array(
                'MKEY' => $data['MKEY'],
                'REFERENCE' => $data['REFERENCE'],
                'ACTION' => 'VALIDATE',
                'STATUS' => 'SUCCESS'
            );
            if (!$client) {
                $array['STATUS'] = 'NOT_VALID';
            } else {
                $array['STATUS'] = 'SUCCESS';

            }
            echo json_encode($array);

            } else if ($data['ACTION'] == 'TRANS') {
            $check_receipt = $this->db->where("receipt",$data['receipt'])->get("application_payment")->row();

            if(!$check_receipt) {
            $applicant_id = substr($data['reference'],4);
            $client_info=  $client_data = $this->applicant_model->get_applicant($applicant_id);
            $trans_date = date('Y-m-d',strtotime($data['timestamp']));
            $trans_timestamp = date('Y-m-d H:i:s',strtotime($data['timestamp']));

            $uid=$this->db->query("SELECT * FROM application WHERE id='$applicant_id' ")->row()->user_id;
            $member_type=$this->db->query("SELECT * FROM application WHERE id='$applicant_id' ")->row()->member_type;
            $ayear = $this->common_model->get_academic_year()->row()->AYear;
            $semester = $this->common_model->get_academic_year()->row()->semester;
            $regno=$this->db->query("select * from students where user_id='$uid' ")->row()->registration_number;
            $programmeID=$this->db->query("select * from students where user_id='$uid' ")->row()->programme_id;

            #receive amount as it is.
            $amount_received=$data['amount'];
            $number=$data['msisdn'];

            if(!is_null($uid)){
            $Array=array(
                'user_id'=>$uid,
                'timestamp'=>$trans_timestamp,
                'receipt'=>$data['receipt'],
                'amount'=>$amount_received,
                'academic_year'=>$ayear
                );
                $this->db->insert('fee_statement',$Array);
            }

            #then start money distribution
            #here check if received money is for examination.
            $check=$this->db->query("select * from temp_exam_registered where registration_number='$regno' and exam_year='$ayear' ")->result();
            if($check){
                $return = array(
                'status'=>'',
                'receipt'=>$data['receipt'],
                'clientID'=>$data['reference']
                );

                $Array=array(
                'msisdn'=>$number,
                'reference'=>$data['reference'],
                'applicant_id'=>$applicant_id,
                'user_id'=>$uid,
                'timestamp'=>$trans_timestamp,
                'receipt'=>$data['receipt'],
                'amount'=>$amount_received-1000,
                'charges'=>1000,
                'academic_year'=>$ayear,
                'session' => $semester
                );
              $this->db->insert('examinations_payment',$Array);
              $return['clientID'] = $client_data->FirstName.' '.$client_data->LastName;
              $return['status'] = 'SUCCESS';

              echo json_encode($return);

            }else{

            #incase it is not for examinations
            #receive amount.
            //$amount_required = $data['amount'];
            $checkAnnual=$this->db->query("select * from annual_fees where user_id='$uid' and academic_year!='$ayear' ")->result();
            if($checkAnnual){

                $return = array(
                'status'=>'',
                'receipt'=>$data['receipt'],
                'clientID'=>$data['reference']
                );

                $array=array(
                'msisdn'=>$number,
                'reference'=>$data['reference'],
                'applicant_id'=>$applicant_id,
                'user_id'=>$uid,
                'timestamp'=>$trans_timestamp,
                'receipt'=>$data['receipt'],
                'amount'=>$amount_received-1000,
                'charges'=>1000,
                'academic_year'=>$ayear
                );
                $this->db->insert('temp_annual_fees',$array);
                $return['clientID'] = $client_data->FirstName.' '.$client_data->LastName;
                $return['status'] = 'SUCCESS';

                echo json_encode($return);
              }
            else{
            #take it as new applicant.
            if($member_type==1)
            {
            $annual=30000;
            }
            elseif($member_type==0)
            {
            $annual=40000;
            }
            else
            {
            $annual=30000;
            }

            if($amount_received==30000 || $amount_received==40000)
            {
                $return = array(
                    'status'=>'',
                    'receipt'=>$data['receipt'],
                    'clientID'=>$data['reference']
                    );

                    $array=array(
                    'msisdn'=>$number,
                    'reference'=>$data['reference'],
                    'applicant_id'=>$applicant_id,
                    'user_id'=>$uid,
                    'timestamp'=>$trans_timestamp,
                    'receipt'=>$data['receipt'],
                    'amount'=>$amount_received-1000,
                    'charges'=>1000,
                    'academic_year'=>$ayear
                    );
                    $this->db->insert('temp_annual_fees',$array);
                    $return['clientID'] = $client_data->FirstName.' '.$client_data->LastName;
                    $return['status'] = 'SUCCESS';

                    echo json_encode($return);
            }else{
            if($amount_received==70000 || $amount_received==90000){
            $amount_needed=$amount_received-$annual;
            //$amount_needed=$data['amount'];
            $return = array(
            'status'=>'',
            'receipt'=>$data['receipt'],
            'clientID'=>$data['reference']
            );
            $array=array(
            'msisdn'=>$number,
            'reference'=>$data['reference'],
            'applicant_id'=>$applicant_id,
            'user_id'=>$uid,
            'timestamp'=>$trans_timestamp,
            'receipt'=>$data['receipt'],
            'amount'=>$annual,
            'academic_year'=>$ayear
            );

            $add=$this->db->insert('annual_fees',$array);
            if($add){
            $array=array(
            'msisdn'=>$number,
            'reference'=>$data['reference'],
            'applicant_id'=>$applicant_id,
            'timestamp'=>$trans_timestamp,
            'receipt'=>$data['receipt'],
            'amount'=>$amount_needed-1000,
            'charges'=>1000,
            'academic_year'=>$ayear
            );

           $this->db->insert('application_payment',$array);
           $return['clientID'] = $client_data->FirstName.' '.$client_data->LastName;
           $return['status'] = 'SUCCESS';

           echo json_encode($return);

            }
           }
          }

          }
         }
        // echo json_encode($return);
        }
        else{
                //DUPLICATE
                $applicant_id = substr($check_receipt->reference,4);
                $client_data = $this->applicant_model->get_applicant($applicant_id);
                $return['clientID'] = $client_data->FirstName.' '.$client_data->LastName;
                $return['status'] = 'DUPLICATE';

                echo json_encode($return);
            }

        }
    }

}
