<?php
$this->load->library('excel');
//get programmecode
$programmecode = stripslashes($_GET['Code']);
$programmeid = stripslashes($_GET['iD']);
$codes=array();
$row = $this->db->query("SELECT * FROM courses WHERE programme_id='$programmeid' ORDER BY code ")->result();
$row_title = array();
$course_code = array();

$row_title[0] = 'CENTERS';
$z=1;

foreach ($row as $key=>$value){
    $row_title[$z] = $value->shortname;
    //$course_code[$z] = $value->code;
    $codes[$z]=$value->code;
    $z++;

}
$row_title[$z] = 'Total candidates';
$z++;
$s=1;
$max_column = PHPExcel_Cell::stringFromColumnIndex(count($row_title)+$s);

$sql = "SELECT * FROM programme WHERE Code='$programmecode'";
$programmeName = $this->db->query($sql)->result();
foreach ($programmeName as $key => $value) {
    $name = $value->Name;
}
$this->excel->startExcel(11,$max_column);
$sheet = $this->excel->get_sheet_instance();
if($flag==2){
   $sheet->setTitle("PACKAGING LIST");
$sheet->setCellValue('B3','PACKAGING LIST '.$ayear.' - '.strtoupper($name).' '); 
}else{
$sheet->setTitle("ATTENDANCE LIST");
$sheet->setCellValue('B3','ATTENDANCE LIST '.$ayear.' - '.strtoupper($name).' ');
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

        $applicant = $this->db->query("SELECT * FROM examination_centers ORDER BY center_name")->result();

        $sn =1;$k=0;
        foreach ($applicant as $key=>$value){
         $datacenters=array();
            $applicant_row = array(
                $sn,
                $number=$value->center_name,
               // $sex=$value->gender
            );

        $regno=$value->id;
        $total = 0;
        foreach ($codes as $keycode) {
                
        $courseCode=$keycode;
    
        $registered_course = $this->db->query("SELECT COUNT(registration_number) as count FROM student_exam_registered WHERE center_id='$regno' AND coursecode='$courseCode' ")->result();
           foreach ($registered_course as $dataCode => $valuekey) {
                
                $Kecode = $valuekey->count;
                $datacenters[$k][$courseCode]=$Kecode;
                $total=$total+$Kecode;

              }

             $k++; 
            }
            if($total=='0'){
                $datacenters[$k][$total]='0';
            }else{
              if($flag==2){
               $total = $total+10;
             }
               $datacenters[$k][$total]=$total; 

            }
            foreach ($datacenters as $key) {
                foreach ($key as $key => $value1) {
                    $applicant_row[$z]=$value1;
                    $z++;
                }
            }

            $z=2; $n=2;
           
            $sheet->fromArray($applicant_row,null,'B'.$rows);
            $sheet->getRowDimension($rows)->setRowHeight(15);
            $rows++;
            $sn++;
         
         
        }
     //var_dump($applicant_row);exit();

        $sheet->getStyle('B'.$initial_row.':'.$max_column.($rows-1))->applyFromArray($set_borders);
        $rows++;
        $rows++;


$sheet->removeColumn('A');

$this->excel->Output("CENTRE ATTENDANCE-LIST");
exit;
?>