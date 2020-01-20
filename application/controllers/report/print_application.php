<?php
ini_set("memory_limit", "-1");
set_time_limit(0);
$student_regno = null;
$out_put = null;
//if($APPLICANT->member_type==1)
//echo $APPLICANT->user_id;exit;
$student_info = $this->db->get_where('students', array('user_id' => $APPLICANT->user_id))->row();
//echo($student_info->registration_number);exit;
if ($student_info) {
    $student_regno = $student_info->registration_number;
    $out_put = $student_info->first_name.' MEMBER FORM';
    $title = "MEMBERSHIP DATA FORM:";
}else{
    $student_regno = "";
    $out_put = $APPLICANT->FirstName.' APPLICATION FORM';
    $title = "APPLICATION DATA FORM";
}

//echo $student_regno; exit;


$this->load->library('pdf');
$pdf = $this->pdf->startPDF('A4');
$pdf->SetHTMLHeader($this->pdf->PDFHeader());
$pdf->SetHTMLFooter($this->pdf->PDFFooter());
$html = '<style>' . file_get_contents('./media/css/pdf_css.css') . '</style>';
$html .= '<h3 style="text-align: center; padding: 0px; margin: 0px;">'.$title.' '.$student_regno.'</h3>';

$html .= ' <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
         Person Particulars 
     </div>';
$html .= '<table style="width: 100%;">
<tr>
<td style="width: 40%; vertical-align: top;">
 <label>First Name : </label> ' . $APPLICANT->FirstName . '<br/>
 <label>Last Name : </label> ' . $APPLICANT->LastName . '<br/>
 <label>Other Name : </label> ' . $APPLICANT->MiddleName . '<br/>
 <label>Sex : </label> ' . $APPLICANT->Gender . '<br/>
 <label>Birth Date : </label> ' . format_date($APPLICANT->dob, false) . '<br/>
  <label>Place of Birth : </label> ' . $APPLICANT->birth_place . '<br/>
  <label>Marital Status : </label> ' . get_value('maritalstatus', $APPLICANT->marital_status, 'name') . '<br/>
 </td>
<td style="width: 40%; vertical-align: top;">
 <label>Country of Residence : </label> ' . get_value('nationality', $APPLICANT->residence_country, 'Country') . '<br/>
 <label>Disability : </label> ' . get_value('disability', $APPLICANT->Disability, 'name') . '<br/>
 <label>Nationality : </label> ' . get_value('nationality', $APPLICANT->Nationality, 'Name') . '<br/>
 <label>Application Year : </label> ' . $APPLICANT->AYear . '<br/>
 <label>Application Type : </label> ' . application_type($APPLICANT->application_type) . '<br/>
 <label>Entry Category : </label> ' . entry_type($APPLICANT->entry_category) . '<br/>';
$html .= '</td>
<td style="width: 20%; vertical-align: top;">
<img src="' . HTTP_PROFILE_IMG . $APPLICANT->photo . '" style="width: 100px;" />
</td>
</tr>
</table>';

$html .= '<br/><div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Contact Information
        </div>';

$html .= '<table style="width: 100%;">
<tr>
<td style="width: 50%; vertical-align: top;">
 <label>Mobile Number 1 : </label> ' . $APPLICANT->Mobile1 . '<br/>
 <label>Mobile Number 2 : </label> ' . $APPLICANT->Mobile2 . '<br/>
 <label>Email : </label> ' . $APPLICANT->Email . '<br/>
</td>
<td style="width: 50%; vertical-align: top;">
 <label>Postal Address : </label> ' . $APPLICANT->postal . '<br/>
 <label>Physcal Address : </label> ' . $APPLICANT->physical . '<br/>
</td>
</tr>
</table>';

$html .= '<br/> <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Next of Kin Information
        </div>';
$html .= '<table style="width: 100%;" cellspacing="3">
<tr>
<td style="width: 49%; vertical-align: top; font-weight: bold; color: blue; border-bottom: 1px solid blue; font-size: 13px;">Next of Kin 1</td>
<td style="width: 2%;"></td>
<td style="width: 49%; vertical-align: top; font-weight: bold; color: blue; border-bottom: 1px solid blue; font-size: 13px;">Next of Kin 2</td>
</tr>
<tr>
<td style="vertical-align: top;">
 <label>Name : </label> ' . (isset($next_kin) ? $next_kin[0]->name : '') . '<br/>
 <label>Mobile 1 : </label> ' . (isset($next_kin) ? $next_kin[0]->mobile1 : '') . '<br/>
 <label>Mobile 2 : </label> ' . (isset($next_kin) ? $next_kin[0]->mobile2 : '') . '<br/>
 <label>Email : </label> ' . (isset($next_kin) ? $next_kin[0]->email : '') . '<br/>
 <label>Postal  : </label> ' . (isset($next_kin) ? $next_kin[0]->postal : '') . '<br/>
 <label>Relation  : </label> ' . (isset($next_kin) ? $next_kin[0]->relation : '') . '<br/>
</td>
<td>&nbsp;</td>
<td style="vertical-align: top;">
<label>Name : </label> ' . (isset($next_kin) ? $next_kin[1]->name : '') . '<br/>
 <label>Mobile 1 : </label> ' . (isset($next_kin) ? $next_kin[1]->mobile1 : '') . '<br/>
 <label>Mobile 2 : </label> ' . (isset($next_kin) ? $next_kin[1]->mobile2 : '') . '<br/>
 <label>Email : </label> ' . (isset($next_kin) ? $next_kin[1]->email : '') . '<br/>
 <label>Postal  : </label> ' . (isset($next_kin) ? $next_kin[1]->postal : '') . '<br/>
 <label>Relation  : </label> ' . (isset($next_kin) ? $next_kin[1]->relation : '') . '<br/>
</td>
</tr>
</table>';

$html .= '<br/><div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Application Fee Payment
        </div>';

$html .= '<h4>Your Reference Number for payment is : ' . REFERENCE_START . $APPLICANT->id . '</h4>

<table class="table" cellspacing="0" cellpadding="3">
    <thead>
    <tr>
        <th style="width: 50px;">S/No</th>
        <th style="text-align: center; width: 100px;">Receipt</th>
        <th style="text-align: center; width: 100px;">Amount</th>
        <th style="text-align: center; width: 150px;">Trans Time</th>
    </tr>
    </thead>
    <tbody>';

$payment_transaction = $this->db->where('user_id', $APPLICANT->user_id)->get("fee_statement")->result();
$total_amount = 0;
//$total_charge = 0;

foreach ($payment_transaction as $key => $value) {

    $html .= ' <tr>
            <td style="text-align: right;">' . ($key + 1) . '.</td>
            <td style="text-align: center;">' . $value->receipt . '</td>
            <td style="text-align: right;">' . number_format($value->amount, 2) . '</td>
            <td style="text-align: center;">' . $value->createdon . '</td>
        </tr>';
}
$html .= ' 
    </tbody>
</table>';


$html .= '<br/><div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Education Background
        </div>';
foreach ($education_bg as $rowkey => $rowvalue) {


    $html .= '<div style="font-size: 16px; margin-bottom: 10px; border-bottom: 1px solid blue; width: 70%; color: blue; font-weight: bold;">' . entry_type_certificate($rowvalue->certificate) . '</div>

        <table style="width: 100%;" cellspacing="3">

            <tr>
                <th style="width: 30%; text-align: left;">Examination Authority :</th>
                <td>' . $rowvalue->exam_authority . '</td>
            </tr>';
    if ($rowvalue->technician_type > 0) {
        $html .= '<tr>
                                    <th> Category :</th>
                                    <td>' . get_value('technician_type', $rowvalue->technician_type, 'name') . '</td>
                                </tr>';
    }
    if ($rowvalue->programme_title <> '') {
        $html .= '<tr>
                                    <th  style="text-align: left;"> Programme Title :</th>
                                    <td>' . $rowvalue->programme_title . '</td>
                                </tr>';
    }
    $html .= '<tr>
                <th style="text-align: left;"> ' . ($rowvalue->certificate < 3 ? 'Division' : ($rowvalue->certificate > 6 ? 'G.P.A/Degree Class':'G.P.A')) . ' :</th>
                <td>' . $rowvalue->division . '</td>
            </tr>
            <tr>
                <th  style="text-align: left;"> ' . ($rowvalue->certificate < 3 ? 'Centre/School' : 'College/Institution/University') . ' :</th>
                <td>' . $rowvalue->school . '</td>
            </tr>
            <tr>
                <th  style="text-align: left;">Country :</th>
                <td>' . get_country($rowvalue->country) . '</td>
            </tr>';
    $html .= '<tr>
                                        <th  style="text-align: left;">Index Number :</th>
                                        <td>' . $rowvalue->index_number . '</td>
                                    </tr>';


        $html .= '<tr>
        <th  style="text-align: left;">Completed Year:</th>
        <td>' . $rowvalue->completed_year . '</td>
    </tr>';


    $html .= '</table>

        <br/>';
    if ($rowvalue->certificate < 3) {
        $html .= '<h4 style="padding: 0px; margin: 0px;">SUBJECT LIST</h4>
            <table class="table" cellpadding="3" cellspacing="0"   style="width: 100%">
                <thead>
                <tr>
                    <th style="width: 70px;">S/No.</th>
                    <th>SUBJECT</th>
                    <th style="width: 150px; text-align: center;">GRADE</th>
                    <th style="width: 150px; text-align: center;">YEAR</th>
                </tr>
                </thead>
                <tbody>';
        $sno = 1;
        $subject_saved = $this->applicant_model->get_education_subject($rowvalue->applicant_id, $rowvalue->id);
        foreach ($subject_saved as $k => $v) {
            $html .= '<tr nobr="true">
                        <td style="vertical-align: middle; text-align: center">' . ($k + 1) . '</td>
                        <td>' . get_value('secondary_subject', $v->subject, 'name') . '</td>
                        <td style="text-align: center">' . $v->grade . '</td>
                        <td style="text-align: center">' . $v->year . '</td>
                    </tr>';
            $sno++;
        }
        $html .= '</tbody>
            </table>';
    }


}

$student_info = $this->db->get_where('students', array('user_id' => $APPLICANT->user_id))->row();
if ($student_info) {
    $results = $this->db->get_where('exam_results', array('registration_number' => $student_info->registration_number))->result();
}

if (count($results) > 0) {
    $html .= '  <div style="font-size: 16px; margin-bottom: 10px; border-bottom: 1px solid blue; width: 70%; color: blue; font-weight: bold;">
        TIOB EXAMINATIONS RESULTS
    </div>';
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
        $rq = "SELECT * FROM courses WHERE code='" . $rowvalue->course . "' ";
        $rw = $this->db->query($rq)->result();
        foreach ($rw as $key => $value1) {
            $name = $value1->name;
        }
        //get score marks
        $score = 0;
        $remark = '';
        //$value->score_marks
        $score = $rowvalue->score_marks;
        if ($score <> '') {
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
}

 if ($APPLICANT->application_type == 3) {
     $html .= '<br/><div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">

        Professional Experience
    </div>';
            foreach (experience() as $key1 => $value1) {
                $html .= '<div style="font-size: 13px; margin-bottom: 10px; border-bottom: 1px solid blue; width: 70%; color: blue; font-weight: bold;">'.$value1.'</div>';

                $tmp = '';
                switch ($key1) {
                    case 1:
                        $tmp = '<table class="table3" cellspacing="0" cellpadding="3" >
    <thead>
    <tr>
        <th style="width: 5%;">S/No</th>
        <th style="width: 40%;">Institute</th>
        <th>Address</th>
                          </tr>
                          </thead>
                          <tbody>';
                        $data_list = $this->applicant_model->get_experience($APPLICANT->id,null, 1)->result();
                        if (count($data_list) > 0) {
                            foreach ($data_list as $dk => $dv) {
                                $tmp .= '<tr>
                                      <td style="text-align: right;">' . ($dk + 1) . '.</td>
                                      <td>' . $dv->name . '</td>
                                      <td>' . $dv->column1 . '</td>
                                      
                                  </tr>';
                            }
                        } else {
                            $tmp .= '<tr>
                                  <td colspan="3">No data found !!</td>
                              </tr>';
                        }
                        $tmp .= '</tbody>
                          </table>';
                        break;

                    case 2:
                        $tmp .= '<table  class="table3" cellspacing="0" cellpadding="3">
    <thead>
    <tr>
        <th style="width: 5%;">S/No</th>
        <th style="width: 40%;">Name of Institution</th>
        <th  style="width:20%;">Award Given</th>
        <th  style="width:20%;">Year of Completion</th>
                                </tr>
                                </thead>
                                <tbody>';

                        $data_list = $this->applicant_model->get_experience($APPLICANT->id,null, 2)->result();
                        if (count($data_list) > 0) {
                            foreach ($data_list as $dk => $dv) {
                                $tmp .= '<tr>
                                            <td style="text-align: right;">' . ($dk + 1) . '.</td>
                                            <td>' . $dv->name . '</td>
                                            <td>' . $dv->column1 . '</td>
                                            <td>' . $dv->column2 . '</td>
                                        </tr>';
                            }
                        } else {
                            $tmp .= '<tr>
                                        <td colspan="3">No data found !!</td>
                                    </tr>';
                        }
                        $tmp .= '</tbody>
                                </table>';

                        break;

                    case 3:
                        $tmp = '<table  class="table3" cellspacing="0" cellpadding="3">
    <thead>
    <tr>
        <th style="width: 5%;">S/No</th>
        <th style="width: 30%;">Post Held</th>
        <th  style="width:30%;">Employer</th>
        <th  style="width:20%;">When (Month/Year)</th>
                                </tr>
                                </thead>
                                <tbody>';

                        $data_list = $this->applicant_model->get_experience($APPLICANT->id,null, 3)->result();
                        if (count($data_list) > 0) {
                            foreach ($data_list as $dk => $dv) {
                                $tmp .= '<tr>
                                            <td style="text-align: right;">' . ($dk + 1) . '.</td>
                                            <td>' . $dv->name . '</td>
                                            <td>' . $dv->column1 . '</td>
                                            <td>' . $dv->column2 . '</td>
                                        </tr>';
                            }
                        } else {
                            $tmp .= '<tr>
                                        <td colspan="5">No data found !!</td>
                                    </tr>';
                        }
                        $tmp .= '</tbody>
                                </table>';

                        break;

                    default:
                        break;
                }

                $html.=$tmp.'<br/>';

                /***************************END OF SWITCH **************************/



             }



     $html .= '<br/> <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Academic Referees
        </div>';
     $html .= '<table style="width: 100%;" cellspacing="3">
<tr>
<td style="width: 49%; vertical-align: top; font-weight: bold; color: blue; border-bottom: 1px solid blue; font-size: 13px;">Referee 1</td>
<td style="width: 2%;"></td>
<td style="width: 49%; vertical-align: top; font-weight: bold; color: blue; border-bottom: 1px solid blue; font-size: 13px;">Referee 2</td>
</tr>
<tr>
<td style="vertical-align: top;">
 <label>Name : </label> ' . (isset($academic_referee) ? $academic_referee[0]->name : '') . '<br/>
 <label>Mobile 1 : </label> ' . (isset($academic_referee) ? $academic_referee[0]->mobile1 : '') . '<br/>
 <label>Mobile 2 : </label> ' . (isset($academic_referee) ? $academic_referee[0]->mobile2 : '') . '<br/>
 <label>Email : </label> ' . (isset($academic_referee) ? $academic_referee[0]->email : '') . '<br/>
 <label>Address  : </label> ' . (isset($academic_referee) ? $academic_referee[0]->address : '') . '<br/>
</td>
<td>&nbsp;</td>
<td style="vertical-align: top;">
<label>Name : </label> ' . (isset($academic_referee) ? $academic_referee[1]->name : '') . '<br/>
 <label>Mobile 1 : </label> ' . (isset($academic_referee) ? $academic_referee[1]->mobile1 : '') . '<br/>
 <label>Mobile 2 : </label> ' . (isset($academic_referee) ? $academic_referee[1]->mobile2 : '') . '<br/>
 <label>Email : </label> ' . (isset($academic_referee) ? $academic_referee[1]->email : '') . '<br/>
 <label>Address  : </label> ' . (isset($academic_referee) ? $academic_referee[1]->address : '') . '<br/>
</td>
</tr>
</table>';



     $html .= '<br/> <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Sponsor & Current Employer Information
        </div>';
     $html .= '<table style="width: 100%;" cellspacing="3">
<tr>
<td style="width: 49%; vertical-align: top; font-weight: bold; color: blue; border-bottom: 1px solid blue; font-size: 13px;">Sponsor Information</td>
<td style="width: 2%;"></td>
<td style="width: 49%; vertical-align: top; font-weight: bold; color: blue; border-bottom: 1px solid blue; font-size: 13px;">Current Employer Information </td>
</tr>
<tr>
<td style="vertical-align: top;">
 <label>Name : </label> ' . (isset($sponsor_info) ? $sponsor_info->name : '') . '<br/>
 <label>Mobile 1 : </label> ' . (isset($sponsor_info) ? $sponsor_info->mobile1 : '') . '<br/>
 <label>Mobile 2 : </label> ' . (isset($sponsor_info) ? $sponsor_info->mobile2 : '') . '<br/>
 <label>Email : </label> ' . (isset($sponsor_info) ? $sponsor_info->email : '') . '<br/>
 <label>Address  : </label> ' . (isset($sponsor_info) ? $sponsor_info->address : '') . '<br/>
</td>
<td>&nbsp;</td>
<td style="vertical-align: top;">
<label>Name : </label> ' . (isset($employer_info) ? $employer_info->name : '') . '<br/>
 <label>Mobile 1 : </label> ' . (isset($employer_info) ? $employer_info->mobile1 : '') . '<br/>
 <label>Mobile 2 : </label> ' . (isset($employer_info) ? $employer_info->mobile2 : '') . '<br/>
 <label>Email : </label> ' . (isset($employer_info) ? $employer_info->email : '') . '<br/>
 <label>Address  : </label> ' . (isset($employer_info) ? $employer_info->address : '') . '<br/>
</td>
</tr>
</table>';



 }


if(count($attachment_list) > 0){

$html .= '<br/><div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Attachment
        </div>';
$html .= '<table class="table" cellspacing="0" cellpadding="3">
            <thead>
            <tr nobr="true">
                <th style="width: 50px;">S/No</th>
                <th style="width: 250px;">Certificate</th>
                <th>Comment</th>

            </tr>
            </thead>
            <tbody>';

foreach ($attachment_list as $rowkey => $rowvalue) {
    $html .= '<tr nobr="true">
                    <td style="vertical-align: middle; text-align: center;">' . ($rowkey + 1) . '</td>
                    <td>' . entry_type_certificate($rowvalue->certificate) . '</td>
                    <td>' . $rowvalue->comment . '</td>

                </tr>';

}

$html .= '</tbody></table>';
}

if (isset($mycoice)){

    $html .= '<br/><div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
    Programme Choices
</div>';

if (isset($mycoice)) {
    $html .= '       <table  cellpadding="5" cellspacing="0">
                        <tr nobr="true">
                            <th  style="width: 20%; text-align: left;">Programme :</th>
                            <td >' . get_value('programme', array("Code" => $mycoice->choice1), 'Name') . '</td>
                        </tr>

                    </table>';
}

}

$html .= '<br/><div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
    Declaration
</div>';
$html .= 'I, ' . $APPLICANT->FirstName . ' ' . $APPLICANT->LastName . ' hereby declare that the information given in this form  is  true
        to the best of my knowledge.';


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
$pdf->Output($out_put . '.pdf', 'D');
exit;
?>
