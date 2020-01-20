<?php
$this->load->library('excel');
$academic_year = $this->common_model->get_academic_year()->row()->AYear;

$codes=array();
$row = $this->db->query("SELECT code, name, shortname FROM courses WHERE programme_id='$programmecode' ORDER BY shortname ASC  ")->result();
$row_title = array();
$row_title[0] = 'REGISTRATION NUMBER';
//$row_title[1] = 'NO OF PASS';
$z=1;

foreach ($row as $key=>$value){
    $row_title[$z] = $value->shortname;
    $z++;
}
$row_title[$z] = 'NO OF PASS';
$row_title[$z+1] = 'NO OF FAIL';

$max_column = PHPExcel_Cell::stringFromColumnIndex(count($row_title)+1);

$this->excel->startExcel(11,$max_column);
$sheet = $this->excel->get_sheet_instance();
$sheet->setTitle("BOARD REPORT");
$sheet->setCellValue('B3','ACADEMIC YEAR '.$ayear.' - '.strtoupper($application_type).' ');

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
//$programmeid = stripslashes($_GET['iD']);





$rows = 6;
$sheet->mergeCells('B'.$rows.':'.$max_column.$rows);

        $sheet->getStyle('B'.$rows)->getFont()->setSize(14);
        $sheet->getRowDimension($rows)->setRowHeight(18);
        $rows++;
        $initial_row = $rows;
        $sheet->fromArray($row_title,null,'C'.$rows);
        $sheet->getRowDimension($rows)->setRowHeight(18);
        $rows++;
        $titles = array();
        unset($row_title[0]);
        unset($row_title[$z]);
        unset($row_title[$z+1]);

        $titles = $row_title;

        $check = $this->exam_model->get_board_report($programmecode)->result();

        $sn =1;
        $single_student_course=array();
        $reg = array();
foreach ($check as $key=>$value){
$reg[$value->registration_number]=$value->registration_number;
}
        foreach ($reg as $key=>$value){

          $regno=$value;
          $count_pass=0;
          $count_fail=0;
          $mdcounter=0;
          $applicant_row = array(
              $sn,
              $regno,
              //$sex=$value->gender
          );
          $p = 2;
        $dbcoursecode = $this->exam_model->get_course_code($programmecode)->result();
        $score = array();

            if ($dbcoursecode){

                foreach($dbcoursecode as $value){
                  $code = $value->code;
                  $sname = $value->shortname;

                  $this->data['infos'] = $this->exam_model->get_course_marks($regno,$academic_year,$code)->result();

                  $marks = $this->data['infos'];

                    if ($this->data['infos']) {
                      foreach ($marks as $key => $value) {
                      $key_marks = $value->score_marks;
                      $score[$sname] = $value->score_marks;

                      }



                    if($key_marks >= '0'){
                        if($key_marks >= '51'){
                          $count_pass = $count_pass+1;
                        //  $applicant_row[$p] = $key_marks;
                              //echo "<td><b>[$key_marks]</b></td>";
                        }else {
                          $count_fail = $count_fail+1;
                            //echo "<td>$key_marks</td>";
                            //$applicant_row[$p] = $key_marks;
                          }

                        }

                     }
                $p++;

               }

             }

            $z=2; $n=2;

            foreach ($titles as $key) {
             $data = $key;
             if(array_key_exists($data, $score)){
               $applicant_row[$p] = $score[$data];
               $p++;
             }else{
              $applicant_row[$p] = " - " ;
              $p++;
             }

            }


           $applicant_row[$p] = $count_pass ;
           $applicant_row[$p+1] = $count_fail ;
          // var_dump($applicant_row); exit;
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
