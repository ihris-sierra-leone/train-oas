<?php
$this->load->library('excel');
//get programmecode
$programmecode = stripslashes($_GET['Code']);
$programmeid = stripslashes($_GET['iD']);
$codes=array();
$row = $this->db->query("SELECT * FROM courses WHERE programme_id='$programmeid' ORDER BY code ")->result();
$row_title = array();
$course_code = array();

$row_title[0] = 'MEMBERSHIP NUMBER';
$row_title[1] = 'SEX';
$z=2;

$total_registered=array(
'Total' => '',
'Total1' => '',
'Total2' => 'TOTAL'
);
 $increment_registered=0;
 $p=0;
foreach ($row as $key=>$value){
    $row_title[$z] = $value->shortname;
    //$course_code[$z] = $value->code;
    $codes[$z]=$value->code;
    $z++;

}
$s=1;
$max_column = PHPExcel_Cell::stringFromColumnIndex(count($row_title)+$s);

$sql = "SELECT * FROM programme WHERE Code='$programmecode'";
$programmeName = $this->db->query($sql)->result();
foreach ($programmeName as $key => $value) {
    $name = $value->Name;
}
$this->excel->startExcel(11,$max_column);
$sheet = $this->excel->get_sheet_instance();
$sheet->setTitle("ATTENDANCE LIST");
$sheet->setCellValue('B3','ATTENDANCE LIST '.$ayear.' - '.strtoupper($name).' ');

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
        foreach ($applicant as $key=>$value){

            $applicant_row = array(
                $sn,
                $number=$value->registration_number,
                $sex=$value->gender
            );

            $regno=$value->registration_number;

            $data = array();
            $registered_course = $this->db->query("SELECT coursecode FROM student_exam_registered where registration_number='$regno' ORDER BY coursecode ")->result();
            foreach ($registered_course as $dataCode) {
                $Kecode = $dataCode->coursecode;
                $data[$Kecode] = $Kecode;
            }
            if($registered_course){
                foreach ($codes as $key=>$value) {
                    $thisvalue=$value;
                        if (array_key_exists($thisvalue, $data)) {
                            $applicant_row[$z] = 'R';
                            $z++;
                           if(array_key_exists($thisvalue, $total_registered)){
                            $previousValue = $total_registered[$thisvalue];
                            $total_registered[$thisvalue]=$previousValue+1;
                            }else{
                                $total_registered[$thisvalue]=$increment_registered+1;
                            }
                           $p++;
                        } else {
                            $applicant_row[$z] = ' - ';
                            $z++;

                       if(array_key_exists($thisvalue, $total_registered)){
                            $previousValue = $total_registered[$thisvalue];
                            $total_registered[$thisvalue]=$previousValue+0;
                            }else{
                                $total_registered[$thisvalue]=$increment_registered+0;
                            }
                           
                        }
                    }

            }
            $z=3; $n=2;
            
            $sheet->fromArray($applicant_row,null,'B'.$rows);
            $sheet->getRowDimension($rows)->setRowHeight(15);
            $rows++;
            $sn++;
        }

        $sheet->fromArray($total_registered,null,'B'.$rows);
        $sheet->getRowDimension($rows)->setRowHeight(15); 
        $rows++;
        
        
        
        $sheet->getStyle('B'.$initial_row.':'.$max_column.($rows-1))->applyFromArray($set_borders);
        $rows++;
        $rows++;


$sheet->removeColumn('A');

$this->excel->Output("STUDENTS ATTENDANCE-LIST ");
exit;
?>