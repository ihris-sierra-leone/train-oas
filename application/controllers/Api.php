<?php

/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 9:33   AM
 */
class Api extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function receive_payment()
    {

        $data_json = file_get_contents("php://input");

        $payment_status= $this->common_model->get_payment_status()->row()->payment_status;

        if(!isJson($data_json))
        {

            $response=array(
                'code'=>0,
                'status'=>'Failure',
                'description'=>'Request should be in JSON formart only'
            );

             echo json_encode( $response);

        }else
        {
            $data = json_decode($data_json, TRUE);
            if (trim($data['ACTION']) == 'VALIDATE') {
                if(isset($data['REFERENCE']))
                {
                    $reference = $data['REFERENCE'];
                }elseif(isset($data['reference']))
                {
                    $reference = $data['reference'];
                }
                
                $applicant_id = substr($reference, 4);
                $flag = substr($reference, 3, 1);

                $payments_log = array(
                    'data' => print_r($data,true)
                );

                $this->db->insert('payments_log', $payments_log);
                $client = $this->applicant_model->get_applicant($applicant_id);
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

                if($payment_status!='ACTIVE')
                {

                    $array['STATUS'] = 'NOT_VALID';
                }
                echo json_encode($array);

            } else if (trim($data['ACTION']) == 'TRANS') {
                if(isset($data['REFERENCE']))
                {
                    $reference = $data['REFERENCE'];
                }elseif(isset($data['reference']))
                {
                    $reference = $data['reference'];
                }
                $applicant_id = substr($reference, 4);
                $flag = substr($reference, 3, 1);

                $payments_log = array(
                    'msisdn' => trim($data['msisdn']),
                    'reference' => trim($data['reference']),
                    'createdon' => $data['timestamp'],
                    'receipt' => trim($data['receipt']),
                    'amount' => trim($data['amount']),
                    'data' => print_r($data,true),
                );
                $this->db->insert('payments_log', $payments_log);
                $check_receipt = $this->db->where("receipt", $data['receipt'])->get("fee_statement")->row();
                $return['receipt'] = $data['receipt'];
                if (!$check_receipt) {
                    $ayear = $this->common_model->get_academic_year()->row()->AYear;
                    $semester = $this->common_model->get_academic_year()->row()->semester;
                    $client_info = $client_data = $this->applicant_model->get_applicant($applicant_id);
                    if($client_info)
                    {
                        $return['clientID'] = $client_data->FirstName . ' ' . $client_data->LastName;
                        $return['status'] = 'SUCCESS';
                        echo json_encode($return);
                        $trans_timestamp = date('Y-m-d H:i:s', strtotime($data['timestamp']));
                        $user_id = $client_info->user_id;
                        $member_type = $client_info->member_type;

                        #receive amount as it is.
                        $amount_received = $data['amount'];
                        $number = $data['msisdn'];

                        $application_type = $client_info->application_type;
                        if ($application_type == 1) {
                            $programme_id = 1001;
                        } else if ($application_type == 2) {
                            $programme_id = 1002;
                        } else if ($application_type == 3) {
                            $programme_id = 1003;
                        } else if ($application_type == 4) {
                                $programme_id = 1004;
                        } else if ($application_type == 5) {
                            $programme_id = 1005;
                        }
                        $annual_amount = $this->db->get_where('exam_fee', array('programmeID' =>$programme_id, 'member_category' => $member_type))->row()->annual_amount;
                        $application_amount = $amount_received-$annual_amount;

                        if (!is_null($user_id)) {
                            $statement_array = array(
                                'msisdn' => $number,
                                'reference' => $reference,
                                'user_id' => $user_id,
                                'applicant_id' => $applicant_id,
                                'timestamp' => $trans_timestamp,
                                'receipt' => trim($data['receipt']),
                                'amount' => $amount_received,
                                'flag' => $flag,
                                'academic_year' => $ayear,
                                'pay_method'=>ReturnMobileOperator(trim($data['msisdn'])),
                                'charges'=>trim($data['charges'])
                            );

                            if($flag==1){
                                $array['annual_amount'] = $annual_amount;
                                $array['application_amount'] = $application_amount;
                            }

                            $this->db->insert('fee_statement', $statement_array);
                        }

                        #then start money distribution

                        if($flag==1){ //this is for application fee

                            $annual_array = array(
                                'msisdn' => $number,
                                'reference' => trim($data['reference']),
                                'applicant_id' => $applicant_id,
                                'user_id' => $user_id,
                                'timestamp' => $trans_timestamp,
                                'receipt' => trim($data['receipt']),
                                'amount' => $annual_amount,
                                'academic_year' => $ayear,
                                'pay_method'=>ReturnMobileOperator(trim($data['msisdn'])),
                                'charges' => trim($data['charges'])
                            );

                            $add = $this->db->insert('annual_fees', $annual_array);
                            if ($add) {
                                $application_array = array(
                                    'msisdn' => $number,
                                    'reference' => trim($data['reference']),
                                    'applicant_id' => $applicant_id,
                                    'timestamp' => $trans_timestamp,
                                    'receipt' => trim($data['receipt']),
                                    'amount' => ($application_amount - $data['charges']),
                                    'charges' => trim($data['charges']),
                                    'academic_year' => $ayear,
                                    'pay_method'=>ReturnMobileOperator(trim($data['msisdn']))

                                );

                                $this->db->insert('application_payment', $application_array);

                            }


                        }
                        if($flag==2){ //this is for annual fee

                            $array = array(
                                'msisdn' => $number,
                                'reference' => trim($data['reference']),
                                'applicant_id' => $applicant_id,
                                'user_id' => $user_id,
                                'timestamp' => $trans_timestamp,
                                'receipt' => trim($data['receipt']),
                                'amount' => ($amount_received - $data['charges']),
                                'charges' => trim($data['charges']),
                                'academic_year' => $ayear,
                                'pay_method'=>ReturnMobileOperator(trim($data['msisdn']))

                            );
                            $this->db->insert('temp_annual_fees', $array);
                            /*$return['clientID'] = $client_data->FirstName . ' ' . $client_data->LastName;
                            $return['status'] = 'SUCCESS';
                            echo json_encode($return);*/
                        }

                        /* if this is for examinations */
                        if($flag==3){
//                        $return = array(
//                            'status' => '',
//                            'receipt' => $data['receipt'],
//                            'clientID' => $data['reference']
//                        );

                            $examinations_array = array(
                                'msisdn' => $number,
                                'reference' => trim($data['reference']),
                                'applicant_id' => $applicant_id,
                                'user_id' => $user_id,
                                'timestamp' => $trans_timestamp,
                                'receipt' => trim($data['receipt']),
                                'amount' => ($amount_received - $data['charges']),
                                'charges' => trim($data['charges']),
                                'academic_year' => $ayear,
                                'session' => $semester,
                                'pay_method'=>ReturnMobileOperator(trim($data['msisdn']))

                            );
                            $this->db->insert('examinations_payment', $examinations_array);
                            /* $return['clientID'] = $client_data->FirstName . ' ' . $client_data->LastName;
                             $return['status'] = 'SUCCESS';

                             echo json_encode($return);*/
                        }

                    }else{
                        //fake payment.
                        $return['clientID'] = "Fake Payment";
                        $return['status'] = 'SUCCESS';
                        echo json_encode($return);
                    }


                } else {
                    //DUPLICATE
                    $applicant_id = substr($check_receipt->reference, 4);
                    $client_data = $this->applicant_model->get_applicant($applicant_id);
                    $return['clientID'] = $client_data->FirstName . ' ' . $client_data->LastName;
                    $return['status'] = 'DUPLICATE';
                    echo json_encode($return);
                }


                //update payment log
                $this->db->set('status', 1);
                $this->db->where('reference', trim($reference));
                $this->db->update('payments_log');


            }



        }

    }

    function push_payment($reference){
        $data = $this->db->get_where('payments_log', array('reference' =>$reference,'status'=>0))->row();
        if($data)
        {
            $data=$data->data;
            $data= print_r_reverse($data);
            $json_data = json_encode($data);
            $responce=sendJsonlOverPost("http://localhost/tiob/index.php/api/receive_payment",$json_data);
            echo ($responce);

        }

    }

}
