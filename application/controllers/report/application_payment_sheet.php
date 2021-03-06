<?php
$this->load->library('excel');

$row_title = array(
    'S/No',
    'First Name',
    'Other Names',
    'Surname',
    'Transaction Date',
    'Recept',
    'Amount',
    'charges'
);

$max_column = PHPExcel_Cell::stringFromColumnIndex(count($row_title));

$this->excel->startExcel(11,$max_column);
$sheet = $this->excel->get_sheet_instance();
$sheet->setTitle("APPLICATION FEE");
if(!is_null($year)){

$sheet->setCellValue('B3','APPLICATION PAYMENT FOR YEAR : '.$year);

}else if(!is_null($from) && !is_null($to)){
//$sheet->setCellValue('B3','MEMBER LIST '.$yea.' -  2 ');
$sheet->setCellValue('B3','APPLICATION PAYMENT FROM '.$from_date.' TO '.$to_date);
}else{
$sheet->setCellValue('B3',' APPLICATIONS  FEE');
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

        if (isset($key) && $key != '') {
            if (isset($_GET['key']) && $_GET['key'] != '') {
            $where .= " AND  (a.FirstName LIKE '%" . $_GET['key'] . "%' OR a.MiddleName LIKE '%" . $_GET['key'] . "%' OR a.LastName LIKE '%" . $_GET['key'] . "%')";
        }

        if (isset($year) && $year!= '') {
            $where .= " AND YEAR(p.createdon)='$year' ";
        }
//        else{
//            $yr=date('Y');
//            $where .= " AND YEAR(p.createdon) ='$yr' ";
//        }

        }
        if (isset($from_date) && $from_date != '') {
            $where .= " AND DATE(p.createdon)>='" . format_date($from_date) . "' ";
        }
        if (isset($to_date) && $to_date != '') {
            $where .= " AND DATE(p.createdon)<='" . format_date($to_date) . "' ";
        }

        //exit($where);
        $applicant = $this->db->query(" SELECT p.*,a.FirstName,a.MiddleName,a.LastName FROM application_payment as p LEFT JOIN application as a ON (p.applicant_id=a.id)  $where ORDER BY p.createdon DESC ")->result();
        $sn =1;
        foreach ($applicant as $key=>$value){
            // $amount=$amount+$value->amount;
            // $charge=$charge+$value->charges;
            $applicant_row = array(
                $sn,
                $value->FirstName,
                $value->MiddleName,
                $value->LastName,
                $value->createdon,
                $value->receipt,
                $value->amount,
                $value->charges

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

$application_type="application paid list";

$this->excel->Output($application_type);
exit;
?>
