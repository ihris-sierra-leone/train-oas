<?php
$this->load->library('excel');

$row_title = array(
    'S/No',
    'First Name',
    'Other Names',
    'Surname',
    'Sex',
    'Paid Amount',
    'Membership',
    'Programme',
    'Mobile',
    'Email'
);
$max_column = PHPExcel_Cell::stringFromColumnIndex(count($row_title));

$this->excel->startExcel(11, $max_column);
$sheet = $this->excel->get_sheet_instance();
$sheet->setTitle("APPLICANT LIST");
if (isset($date)) {
    $application_type = '';
    $sheet->setCellValue('B3', 'APPLICANT LIST ON : ' . $date . '');
} elseif (isset($frm) && isset($to)) {
    $sheet->setCellValue('B3', 'APPLICANT LIST BETWEEN: ' . $frm . ' - ' . $to . ' ');
} elseif (isset($application_type)) {
    $sheet->setCellValue('B3', 'APPLICANT LIST ' . $ayear . ' - ' . strtoupper(application_type($application_type)) . ' ');
} elseif (isset($status)) {
//    echo "hapa";
//    exit;
    $sheet->setCellValue('B3', 'LIST OF : ' . search_status($status) . 'APPLICANT');
} else {
    $sheet->setCellValue('B3', 'APPLICANT LIST ' . $yearl . ' - ' . strtoupper(application_type($application_type)) . ' ');
}
$department_color = array(
    'font' => array(
        'bold' => true,
        'color' => array('rgb' => '006BFF'),
        'size' => 15
    ));

$set_borders = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_HAIR
        ))
);

//if($type<>''){
//    $where =  " WHERE `type`='$type' ";
//}else{
//    $where='';
//}
//
//$department_list = $this->db->query("SELECT DISTINCT Departmentid as department_id FROM programme $where ")->result();
//
//
//    $rows = 6;
//    $department_name = get_value('department',$department->department_id,'Name');
//    $sheet->mergeCells('B'.$rows.':'.$max_column.$rows);
//    $sheet->setCellValue('B'.$rows,'Department : '.$department_name);
//    $sheet->getStyle('B'.$rows)->applyFromArray($department_color);
//    $sheet->getRowDimension($rows)->setRowHeight(20);
//    if($type<>''){
//        $where =  " AND `type`='$type' ";
//    }else{
//        $where='';
//    }
//
//    $programme_list = $this->db->query("SELECT * FROM programme WHERE  Departmentid='$department->department_id' $where")->result();


$rows = 6;
$sheet->mergeCells('B' . $rows . ':' . $max_column . $rows);
// $sheet->setCellValue('B'.$rows,'Programme : '.$programme->Name);
$sheet->getStyle('B' . $rows)->getFont()->setSize(14);
$sheet->getRowDimension($rows)->setRowHeight(18);
$rows++;
$initial_row = $rows;
$sheet->fromArray($row_title, null, 'B' . $rows);
$sheet->getRowDimension($rows)->setRowHeight(18);
$rows++;

$where = ' WHERE 1=1 ';

if (isset($application_type) && $application_type != '') {
    $where .= " AND application_type='" . $application_type . "' ";
}
if (isset($yearl) && $yearl != '') {
    $where .= " AND AYear='" . $yearl . "' ";
}
if (isset($date) && $date != '') {
    $where .= " AND DATE(createdon)='" . $date . "' ";
}
if (isset($frm) && $frm != '') {
    $where .= " AND DATE(createdon)>='" . format_date($frm) . "' ";
}
if (isset($status) && $status != '') {
    $where .= " AND submitted=$status ";
}
if (isset($to) && $to != '') {
    $where .= " AND DATE(createdon)<='" . format_date($to) . "' ";
}

$where .= " AND  status=0 ";

$applicant = $this->db->query("SELECT * FROM application $where ORDER BY FirstName")->result();

$sn = 1;
foreach ($applicant as $key => $value) {

    $amount_paid = $this->db->select_sum('amount')->where(array('applicant_id' => $value->id))->get('application_payment')->row();
    if ($amount_paid) {
        $paid = $amount_paid->amount;
    } else {
        $paid = '';
    }

//            $choice = 0;
//            //Get Choice Number
//            if($value->choice1 == $programme->Code){
//                $choice = 1;
//            }else if($value->choice2 == $programme->Code){
//                $choice = 2;
//            }else if($value->choice3 == $programme->Code){
//                $choice = 3;
//            }else if($value->choice4 == $programme->Code){
//                $choice = 4;
//            }else if($value->choice5 == $programme->Code){
//                $choice = 5;
//            }

//            //Form IV subject Result
//            $form4_result = $this->db->query("SELECT aes.*,ss.shortname, ss.name as subject_name FROM application_education_subject as aes
//                       INNER JOIN secondary_subject as ss ON (aes.subject=ss.id) WHERE aes.applicant_id ='$value->applicant_id' AND aes.certificate=1")->result();
//            $form4_result_data = '';
//            foreach ($form4_result as $form4_key=>$form4_value){
//                if(($form4_key%4) == 0){
//                    $form4_result_data .= "\n";
//                }
//                $form4_result_data .= $form4_value->shortname.'-'.$form4_value->grade.',';
//            }initial_row


    //Form VI subject Result
//            $form6_result = $this->db->query("SELECT aes.*,ss.shortname, ss.name as subject_name FROM application_education_subject as aes
//                       INNER JOIN secondary_subject as ss ON (aes.subject=ss.id) WHERE aes.applicant_id ='$value->applicant_id' AND aes.certificate=2")->result();
//            $form6_result_data = '';
//            foreach ($form6_result as $form6_key=>$form6_value){
//                if(($form6_key%4) == 0){
//                    $form6_result_data .= "\n";
//                }
//                $form6_result_data .= $form6_value->shortname.'-'.$form6_value->grade.',';
//            }

    if ($value->member_type == 1) {
        $member_type = "Ordinary Member";
    } elseif ($value->member_type == 0) {
        $member_type = "Student Member";
    }

    $application_type = application_type($value->application_type);

    $applicant_row = array(
        $sn,
        $value->FirstName,
        $value->MiddleName,
        $value->LastName,
        $value->Gender,
        $paid,
        $member_type,
        $application_type,
        $value->Mobile1,
        $value->Email
        //  $value->institution_id,
        //  $value->nationality,
        // get_value('disability',$value->Disability,'name'),
        // get_value('nationality',$value->Nationality,'Name'),
        //  get_value('maritalstatus',$value->marital_status,'name'),
        //$value->contacts_information,
        // $value->Mobile2,
        // $value->Email,
        // entry_type($value->entry_category),
        // $choice,
        // get_index_number($value->applicant_id,1),
        // get_index_number($value->applicant_id,2),
        ////get_index_number($value->applicant_id,$value->entry_category),
        //  rtrim($form4_result_data,', '),
        ///  rtrim($form6_result_data,', '),


    );


    $sheet->fromArray($applicant_row, null, 'B' . $rows);
    $sheet->getRowDimension($rows)->setRowHeight(15);
    $rows++;
    $sn++;
}


// $wrapp_text = PHPExcel_Cell::stringFromColumnIndex(count($row_title)-2);
//$sheet->getStyle($wrapp_text.($initial_row+1).':'.$wrapp_text.($rows-1))->getAlignment()->setWrapText(true);

//$wrapp_text = PHPExcel_Cell::stringFromColumnIndex(count($row_title)-1);
//$sheet->getStyle($wrapp_text.($initial_row+1).':'.$wrapp_text.($rows-1))->getAlignment()->setWrapText(true);

$sheet->getStyle('B' . $initial_row . ':' . $max_column . ($rows - 1))->applyFromArray($set_borders);
$rows++;
$rows++;


$sheet->removeColumn('A');
$application_type = "Applicants-list";
$this->excel->Output($application_type);
exit;
?>
