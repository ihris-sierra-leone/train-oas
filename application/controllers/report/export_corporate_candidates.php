<?php
$this->load->library('excel');

$row_title = array(
    'S/No',
    'First Name',
    'Surname',
    'Registration Number',
    'Programme',

);

$max_column = PHPExcel_Cell::stringFromColumnIndex(count($row_title));

$this->excel->startExcel(11,$max_column);
$sheet = $this->excel->get_sheet_instance();
$sheet->setTitle("CORPORATE CANDIDATE LIST");
if(!is_null($corporate)){
$corporatename = $this->db->query("select * from member where id='$corporate' ")->row()->institution_name;
//echo $corporatename; exit;
    $sheet->setCellValue('B3','CANDIDATE LIST IN : '.$corporatename);

}
$department_color = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '006BFF'),
        'size'  => 15
    ));

$set_borders = array(
    'borders' => array(
        'allborders' =>array(
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
$sheet->mergeCells('B'.$rows.':'.$max_column.$rows);
// $sheet->setCellValue('B'.$rows,'Programme : '.$programme->Name);
$sheet->getStyle('B'.$rows)->getFont()->setSize(14);
$sheet->getRowDimension($rows)->setRowHeight(18);
$rows++;
$initial_row = $rows;
$sheet->fromArray($row_title,null,'B'.$rows);
$sheet->getRowDimension($rows)->setRowHeight(18);
$rows++;

$where = ' WHERE 1=1 ';

// }
if (isset($corporate) && $corporate != '') {
    $where .= " AND cooperate='$corporate' ";

}

//$applicant = $this->db->query("select f.id, f.user_id,f.timestamp,f.receipt, app.FirstName, app.MiddleName, app.LastName, f.createdon, f.amount from fee_statement as f inner join application as app on (f.user_id=app.user_id)
//        $where order by f.createdon desc ")->result();
$sql = "SELECT * FROM students as s INNER JOIN exam_results as er ON (s.registration_number=er.registration_number) $where ";
$applicant = $this->db->query($sql. " GROUP by s.registration_number ")->result();
//var_dump($applicant); exit;
$sn =1;
foreach ($applicant as $key=>$value){
    // echo $value->FirstName;
    // exit;

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

    $applicant_row = array(
        $sn,
        $value->first_name,
        $value->surname,
        $value->registration_number,
        get_code($value->programme_id),
//        $value->createdon,
//        $value->receipt,
//        $value->amount,
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


    $sheet->fromArray($applicant_row,null,'B'.$rows);
    $sheet->getRowDimension($rows)->setRowHeight(15);
    $rows++;
    $sn++;
}


// $wrapp_text = PHPExcel_Cell::stringFromColumnIndex(count($row_title)-2);
//$sheet->getStyle($wrapp_text.($initial_row+1).':'.$wrapp_text.($rows-1))->getAlignment()->setWrapText(true);

//$wrapp_text = PHPExcel_Cell::stringFromColumnIndex(count($row_title)-1);
//$sheet->getStyle($wrapp_text.($initial_row+1).':'.$wrapp_text.($rows-1))->getAlignment()->setWrapText(true);

$sheet->getStyle('B'.$initial_row.':'.$max_column.($rows-1))->applyFromArray($set_borders);
$rows++;
$rows++;


$sheet->removeColumn('A');

$application_type="CORPORATE CANDIDATE LIST";

$this->excel->Output($application_type);
exit;
?>
