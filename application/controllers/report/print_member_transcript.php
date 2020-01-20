<?php
ini_set("memory_limit", "-1");
set_time_limit(0);
$this->load->library('pdf');
$pdf = $this->pdf->startPDF('A4');
$pdf->SetHTMLHeader($this->pdf->PDFHeader());
$pdf->SetHTMLFooter($this->pdf->PDFFooter());
$html = '<style>' . file_get_contents('./media/css/pdf_css.css') . '</style>';
$html .= '<table style="width: 100%;">
<tr>
<td style="width: 90%; vertical-align: top;"><label>' . $APPLICANT->registration_number . ' </label></td>
<td><label> '.date('d-m-Y').'</label></td>
</tr>
</table>';

$html .= '<table style="width: 100%;">
<tr>
<td style="width: 40%; vertical-align: top;">
 <label>' . $APPLICANT->first_name . ' ' . $APPLICANT->other_names . ' ' . $APPLICANT->surname . '<br/>
 </td>
</tr>
</table>';


$html .= '<br/><div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            STATEMENT OF RESULTS (' . $APPLICANT->first_name . ' ' . $APPLICANT->other_names . ' ' . $APPLICANT->surname . ')
        </div>';
$html .= '<p>This is to certify that the above named is a member of the Tanzania Institute of Bankers with Membership Number ' . $APPLICANT->registration_number . '.</p>';
$html .= '<p>He sat for TIOB ' . receive_programme_code($APPLICANT->programme_id) . ' Examinations and successfully completed the programme.  The following is a summary of his performance: </p>';

$html .= '<table class="table" cellspacing="0" cellpadding="3">
            <thead>
            <tr nobr="true">
                <th style="width: 50px;">S/No</th>
                <th style="width: 250px;">Examination Date</th>
                <th>Subject</th>
                <th>Marks</th>
                <th>Result</th>

            </tr>
            </thead>
            <tbody>';

foreach ($results as $rowkey => $rowvalue) {
    $rq="SELECT * FROM courses WHERE code='".$rowvalue->course."' ";
                         $rw=$this->db->query($rq)->result();
                         foreach ($rw as $key => $value1) {
                             $name=$value1->name;
                         }
                    //get score marks
                    $score=0; $remark='';
                    //$value->score_marks
                    $score = $rowvalue->score_marks;
                    if ($score <>'') {
                        if ($score >= 81) {
                            $remark = 'Distiction';
                        } elseif ($score >= 65) {
                            $remark = 'Credit';
                        } elseif ($score >= 51) {
                            $remark = 'Pass';
                        } else {
                            $remark = 'Fail';
                        }
                    }
    $html .= '<tr nobr="true">
                    <td style="vertical-align: middle; text-align: center;">' . ($rowkey + 1) . '</td>
                    <td>' . $rowvalue->academic_year . ' &nbsp; ' . $rowvalue->exam_session . '</td>
                    <td>' . $name . '</td>
                    <td>' . $rowvalue->score_marks . '</td>
                    <td>' . $remark . '</td>

                </tr>';
}

$html .= '</tbody></table> <br><br> ';

$html .= '<table style="width: 100%;">
<tr>
<td style="width: 40%; vertical-align: top;">
 <label>Yours sincerely,</label>
 <label for=""><h4>THE TANZANIA INSTITUTE OF BANKERS</h4></label>
 </td>
</tr>
</table><br><br><br><br><br><br><br>';

$html .= '<h4>FOR : EXECUTIVE DIRECTOR</h4>';

$html .= '<table style="width: 100%;">
<tr>
<td style="width: 40%; vertical-align: top;">
 <label>Results range,</label><br>
 <label>  81 – 100% &nbsp;	Distinction </label><br>
 <label>  65 – 80% &nbsp;	Merit Pass </label><br>
 <label>  51 – 64% &nbsp;	Pass </label>
 </td>
</tr>
</table><br>';

$pdf->WriteHTML($html);
$fileArray = array();
foreach ($attachment_list as $rowkey => $rowvalue) {
    $extension = getExtension($rowvalue->attachment);
    if ($extension == 'pdf') {
        $fileArray[] = UPLOAD_FOLDER . 'attachment/' . $rowvalue->attachment;
    } else {
        $pdf->AddPage();
        $pdf->WriteHTML('<img src="' . UPLOAD_FOLDER . 'attachment/' . $rowvalue->attachment . '"/>');
    }
}


$outputName = UPLOAD_FOLDER . 'attachment/' . current_user()->id . '_MERGE.pdf';
if (file_exists($outputName)) {
    unlink($outputName);
}
$cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";

if (count($fileArray) > 0) {
    foreach ($fileArray as $file) {
        $cmd .= $file . " ";
    }

    $result = shell_exec($cmd);

    $pdf->AddPage();
    $pdf->SetImportUse();
    $pagecount = $pdf->SetSourceFile($outputName);
    for ($i = 1; $i <= $pagecount; $i++) {
        $tplId = $pdf->ImportPage($i);
        $pdf->UseTemplate($tplId, 5, 17, 200);
        if ($i < $pagecount)
            $pdf->AddPage();
    }


}

if (file_exists($outputName)) {
    unlink($outputName);
}

//Close and output PDF document
$pdf->Output('CANDIDATE TRANSCRIPT' . '.pdf', 'D');
exit;
?>