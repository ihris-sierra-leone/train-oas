<?php
$this->load->library('excel');

$row_title = array(
    'S/No',
    'RegNo',
    'FirstDHBHName',
    'Other Names',
    'Surname',
    'Sex',
    'Membership',
    'Programme',
    'Designation',
    'Address',
    'Mobile',
    'Email',
    'Employer',
    'Nationality'
);

$sql="SELECT ";
$max_column = PHPExcel_Cell::stringFromColumnIndex(count($row_title));

$this->excel->startExcel(11,$max_column);
$sheet = $this->excel->get_sheet_instance();
$sheet->setTitle("ATTENDANCE LIST");
$sheet->setCellValue('B3','PACKAGE '.$ayear.' - '.strtoupper($application_type).' ');

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

//get programmecode
$programmecode = stripslashes($_GET['Code']);
$programmeid = stripslashes($_GET['iD']);



$codes=array();
$row = $this->db->query("SELECT * FROM courses WHERE programme_id='$programmeid' ORDER BY id ")->result();
$row_title = array();
$row_title[0] = 'MEMBERSHIP NUMBER';
$row_title[1] = 'SEX';
$z=2;

foreach ($row as $key=>$value){
    $row_title[$z] = $value->shortname;
    $codes[$z]=$value->id;
    $z++;
}

$rows = 6;
$sheet->mergeCells('B'.$rows.':'.$max_column.$rows);
       // $sheet->setCellValue('B'.$rows,'Programme : '.$programme->Name);
        $sheet->getStyle('B'.$rows)->getFont()->setSize(14);
        $sheet->getRowDimension($rows)->setRowHeight(18);
        $rows++;
        $initial_row = $rows;
        $sheet->fromArray($row_title,null,'C'.$rows);
        $sheet->getRowDimension($rows)->setRowHeight(18);
        $rows++;

        $applicant = $this->db->query("SELECT * FROM students where programme_id='$programmecode' ORDER BY registration_number")->result();

        $sn =1;
        $single_student_course=array();
        foreach ($applicant as $key=>$value){

            $regno=$value->registration_number;
            $registered_course = $this->db->query("SELECT coursecode FROM student_exam_registered where registration_number='$regno'")->result();
            //$registered_course = $this->db->query("SELECT * FROM student_exam_registered")->result();

           // var_dump($registered_course); exit();
          //  $registered_course = array('41','48');

            $applicant_row = array(
                $sn,
                $number=$value->registration_number,
                $sex=$value->gender
            );
//var_dump($codes); exit();
                $z=3; $n=2;
//            foreach ($registered_course as $key=>$value){
//                $single_student_course=$value->coursecode;
//            }

            foreach ($codes as $key=>$value) {
                    $thisvalue=$value;
                    foreach ($registered_course as $key=>$value){
                        $single_student_course=$value->coursecode;
                        if ($thisvalue == $registered_course) {
                            $applicant_row[$z] = ' R ';
                        } else {
                            $applicant_row[$z] = ' - ';
                        }
                    }
                    $z++;
                }


   //  var_dump($applicant); exit();
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

$this->excel->Output($application_type);
exit;
?>