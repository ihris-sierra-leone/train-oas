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
$html .= '<br>';
$html .= '<h3 style="text-align: center; padding: 0px; margin: 0px;">'.get_code($APPLICANT->programme_id).'</h3>';

$html .= ' <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
         Person Particulars
     </div>';
$html .= '<table style="width: 100%;">
<tr>
<td style="width: 90%; vertical-align: top;">
<label>' . $APPLICANT->first_name . ' </label>'.$nbsp.'<label>' . $APPLICANT->other_names . ' </label>'.$nbsp.'<label>' . $APPLICANT->surname . ' </label>

</td>

</tr>
</table>';

$html .= '<br>';

$html .= '<h3 style="text-align: center; padding: 0px; margin: 0px;">EXAMINATION ATTENDANCE NOTICE</h3> <br>';


$html .= '<h5 style="text-align: center">YOU HAVE BEEN ENTERED FOR THE FOLLOWING SUBJECTS , PLEASE CHECK THESE:</h5>';

$html .= '<table class="table" cellspacing="0" cellpadding="3">
            <thead>
            <tr nobr="true">
                <th style="width:60px;">SNo</th>
                <th style="width: 300px;">Name</th>
                <th style="width: 150px;">Date</th>
                <th style="width: 80px;">Time</th>
            </tr>
            </thead>
            <tbody>';
$academic_year = $this->common_model->get_academic_year()->row()->AYear;
$exam_session = $this->common_model->get_academic_year()->row()->semester;
$registered_list = $this->db->query("SELECT * FROM student_exam_registered WHERE registration_number='" . $APPLICANT->registration_number . "' AND exam_year='$academic_year' ")->result();


$i = 1;
foreach ($registered_list as $key => $value) {
    $cid = $value->center_id;
    $centre_code = $value->center_code;
    $code = $value->course_id;
    $time = $this->db->query("SELECT * FROM exam_register WHERE coursecode='$code'")->row()->time;
    $exam_date = $this->db->query("SELECT * FROM exam_register WHERE coursecode='$code'")->row()->exam_date;
    $venue = $this->db->query("SELECT * FROM venue WHERE centre_id='$cid'")->row()->name;
    $centercode = $this->db->query("select * from examination_centers where id='$cid'")->row()->center_code;

    $html .= '<tr nobr="true">
                    <td style="vertical-align: middle; text-align: center;">' . ($i) . '</td>
                    <td>' . get_courses($value->course_id) . '</td>
                    <td>' . $exam_date . '</td>
                    <td>' . $time . '</td>
                    

                </tr>';
    $i++;
}

$html .= '</tbody></table> <br>';

$html .= '<h5>YOUR EXAMINATION VENUE</h5>';
$html .= '<table style="width: 100%;">
<tr>
<td style="width: 80%; vertical-align: top;">
<label> '. $venue .'</label><br>
<label> '. get_centre($cid) .'</label><br>

</td>
<td>
<label for="center">CENTRE CODE: &nbsp; '.$centercode.'</label>
</td>

</tr>
</table>';



$pdf->WriteHTML($html);
$fileArray = array();


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
$pdf->Output('EXAMINATION LETTER' . '.pdf', 'D');
exit;
?>