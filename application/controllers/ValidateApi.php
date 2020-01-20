<?php

/**
 * Created by Atom.
 * User: festus
 * Date: 10/09/18
 * Time: 9:33 AM
 */
class ValidateApi extends  CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function validation()
    {

      // echo "hapa nafika";exit;

        $data_json = file_get_contents("php://input");
        // print_r($data_json);exit;
            $data = json_decode($data_json, TRUE);
            if($data['action']=='validate'){
            $validate_referense = $this->db->get_where('nmb_logs', array('reference'=>$data['reference']))->row();
            if(!empty($validate_referense)){
              $amount_required = $validate_referense->amount;
              $student_id = $validate_referense->student_id;
              $refence = $validate_referense->reference;
              $type = $validate_referense->type;
              $action = $validate_referense->action;
              $channel = $validate_referense->channel;
              $customer = $validate_referense->customer;
              $applicant_id = substr($data['reference'], 7);
              $applicant_values = $this->db->get_where('application', array('id'=>$applicant_id))->row();
              $URL=  "https://212.71.252.209/gateway/";
                $return = array(
                    "status" => 1,
                    "channel" => $channel,
                    "customer" => $customer,
                    "reference" => $refence,
                    "student_name" => $applicant_values->FirstName.' '.$applicant_values->LastName,
                    "student_id" => $student_id,
                    "amount"      => $amount_required,
                    "type"   => $type,
                    "action"   => $action

                );

            }else{
              $return = array(
                  'status'=>'0',
                  'description'=>'NOT VALID'
                  );
            }
            echo json_encode($return);
          }
        }

      }
