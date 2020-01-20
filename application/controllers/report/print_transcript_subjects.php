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
$html .= '<p>This serves as proof that named above is a member of the Tanzania Institute of Bankers with Registration Number ' . $APPLICANT->registration_number . '.</p>';
$html .= '<p>He/She is attempting the ' . receive_programme_code($APPLICANT->programme_id) . ' examinations and has passed the following subjects </p>';

if($results) {
    $programme_id = $this->db->query("select * from students where registration_number='" . $APPLICANT->registration_number . "' ")->row()->programme_id;
    $programme = receive_programme_code($programme_id);
    $programmeid = $this->db->query("select * from programme where Name='$programme' ")->row()->id;
    $coursesno = $this->db->query("select count(id) as counter from courses where programme_id=$programmeid ")->row()->counter;
    foreach ($results as $key => $value) {

        $html .= '<li> '. $this->common_model->get_course($value->course)->row()->name .' </li><br><br>';

    }
    $examno = $this->db->query("select count(course) as counter from exam_results where registration_number='" . $APPLICANT->registration_number . "' ")->row()->counter;
    $remain = $coursesno - $examno;

}

$html .= '<p>He/She remains with '. $remain .' subjects to complete the ' . receive_programme_code($APPLICANT->programme_id) . ' programme. </p><br><br><br><br>';


$html .= '<table style="width: 100%;">
<tr>
<td style="width: 40%; vertical-align: top;">
 <label>Yours sincerely,</label><br>
 <label for=""><h4>THE TANZANIA INSTITUTE OF BANKERS</h4></label>
 </td>
</tr>
</table><br><br><br><br><br>';

$html .= '<h4>FOR : EXECUTIVE DIRECTOR</h4>';


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