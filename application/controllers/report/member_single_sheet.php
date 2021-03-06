<?php
$this->load->library('excel');

$row_title = array(
    'S/No',
    'RegNo',
    'First Name',
    'Other Names',
    'Surname',
    'Sex',
    'Membership',
    'Programme',
    'Address',
    'Mobile',
    'Email',

);

$max_column = PHPExcel_Cell::stringFromColumnIndex(count($row_title));

$this->excel->startExcel(11,$max_column);
$sheet = $this->excel->get_sheet_instance();
//$sheet->setTitle("MEMBER LIST");
if(!is_null($application_type) && !is_null($coorat) && !is_null($yea)){
    $sql = "SELECT * FROM member WHERE id='$coorat' ";
    $rw = $this->db->query($sql)->result();
    foreach ($rw as $key => $value) {
        $name = $value->institution_name;
    }
    $sheet->setCellValue('B3','MEMBER LIST '.receive_programme_code($application_type).' - '.strtoupper($name).' - '.$yea.' ');

}elseif (!is_null($application_type) && !is_null($coorat)){
    //$application_type = '';
    $sql = "SELECT * FROM member WHERE id='$coorat' ";
    $rw = $this->db->query($sql)->result();
    foreach ($rw as $key => $value) {
        $name = $value->institution_name;
    }
    $sheet->setCellValue('B3','MEMBER LIST '.receive_programme_code($application_type).' - '.strtoupper($name).' ');

}elseif (!is_null($coorat) && !is_null($from)){
    $sql = "SELECT * FROM member WHERE id='$coorat' ";
    $rw = $this->db->query($sql)->result();
    foreach ($rw as $key => $value) {
        $name = $value->institution_name;
    }
    $sheet->setCellValue('B3','MEMBER LIST '.strtoupper($name).' - '.$from.' TO '.$to.' ');


}elseif (!is_null($application_type) && !is_null($from)){

    $sheet->setCellValue('B3','MEMBER LIST BWTWEEN '.$from.' TO '.$to.' - '.strtoupper(receive_programme_code($application_type)).' ');

}elseif (!is_null($application_type)){

    $sheet->setCellValue('B3','MEMBER LIST '.strtoupper(receive_programme_code($application_type)).' ');

}elseif (!is_null($coorat)){
    $application_type = '';
    $sql = "SELECT * FROM member WHERE id='$coorat' ";
    $rw = $this->db->query($sql)->result();
    foreach ($rw as $key => $value) {
        $name = $value->institution_name;
    }
    $sheet->setCellValue('B3','MEMBER LIST '.strtoupper($name).' ');

}else{

    $sheet->setCellValue('B3','LIST OF ALL MEMBER');
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

if (isset($application_type) && $application_type != '') {
    $where .= " AND programme_id='" . $application_type . "' ";
}
//if (isset($yea) && $yea != '') {
//    $where .= " AND entry_year='" . $yea . "' ";
//}
if (isset($from) && $from != '') {
    $where .= " AND DATE(updated_at)>='" . format_date($from) . "' ";
}

if (isset($to) && $to != '') {
    $where .= " AND DATE(updated_at)<='" . format_date($to) . "' ";
}
//$from = $from;
//$to = $to;
if (isset($coorat) && $coorat != '') {
    $where .= " AND cooperate='" . $coorat . "' ";
}
//exit($where);

$applicant = $this->db->query("SELECT * FROM students $where ORDER BY registration_number")->result();

$sn =1;
foreach ($applicant as $key=>$value){
    $programme = $value->programme_id;

    if($programme==0){
        $programme_id=0;
    }else{
        $programme_id=receive_programme_code($programme);
    }


    $corp_id=$value->cooperate;

    if($corp_id==0){
        $institution="";

    }else{

        $corporates=$this->common_model->get_cooperate($corp_id)->result();
        foreach ($corporates as $key1 => $value1) {
            $institution=$value1->institution_name;
        }
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

    $applicant_row = array(
        $sn,
        $value->registration_number,
        $value->first_name,
        $value->other_names,
        $value->surname,
        $value->gender,
        member_type($value->member_type),
        $programme_id,
        $institution,
        $value->address,
        $value->mobile,
        $value->email,

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

$application_type="Members list";

$this->excel->Output($application_type);
exit;
?>
