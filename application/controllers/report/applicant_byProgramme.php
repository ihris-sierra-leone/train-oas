<?php
$this->load->library('excel');
$max_column = 'L';
$this->excel->startExcel(12,$max_column);
$sheet = $this->excel->get_sheet_instance();
$sheet->setTitle("APPLICANT LIST");
$sheet->setCellValue('B3','APPLICANT LIST REPORT - '.$ayear);
$programmeinfo = get_value('programme',array('Code'=>$programme),null);



//Heading Main
$sheet->mergeCells('B4:'.$max_column.'4');
$sheet->setCellValue('B4','Programme : '.$programmeinfo->Name);
$sheet->getStyle('B4')->getFont()->setSize(13);

$set_borders = array(
    'borders' => array(
        'allborders' =>array(
            'style' => PHPExcel_Style_Border::BORDER_HAIR
        ))
);


$rows = 6;
$column = 'A';
$sheet->setCellValue($column.$rows,'S/No');
$column++;
$sheet->setCellValue($column.$rows,'Form4 Index');
$column++;
$sheet->setCellValue($column.$rows,'Applicant Name');
$column++;
$sheet->setCellValue($column.$rows,'Sex');
$column++;
$sheet->setCellValue($column.$rows,'Mobile');
$column++;
$sheet->setCellValue($column.$rows,'Entry Type');
$column++;
$sheet->setCellValue($column.$rows,'Duration');
$column++;
$sheet->setCellValue($column.$rows,'Choice#');
$column++;
$sheet->setCellValue($column.$rows,'Point');
$column++;
$sheet->setCellValue($column.$rows,'Eligibility');
$column++;
$sheet->setCellValue($column.$rows,'Remark');

$rows++;
foreach ($applicant_list as $key=>$value){
$column = 'A';
$sheet->setCellValue($column.$rows,($key+1));
$column++;
$sheet->setCellValue($column.$rows,$value->form4_index);
$column++;
$sheet->setCellValue($column.$rows,$value->FirstName.' '.$value->MiddleName.' '.$value->LastName);
$column++;
$sheet->setCellValue($column.$rows,$value->Gender);
$column++;
$sheet->setCellValue($column.$rows, str_replace(' ','',$value->Mobile1));
$column++;
$sheet->setCellValue($column.$rows,entry_type_human($value->entry_category));
$column++;
$sheet->setCellValue($column.$rows,$value->duration);
$column++;
$sheet->setCellValue($column.$rows,$value->choice);
$column++;
$sheet->setCellValue($column.$rows,$value->point);
$column++;
$sheet->setCellValue($column.$rows,($value->eligible == 1 ? 'Yes':'No'));
$column++;
$sheet->setCellValue($column.$rows,$value->comment);
$rows++;

}
$max_column = $column;
$sheet->getStyle('A6:'.$max_column.($rows-1))->applyFromArray($set_borders);
$this->excel->Output("APPLICANT LIST");
exit;
?>