<?php
$status = 0;
$pass = 0;
$sitting_no = 0;
$point = 0;
$min_point = $setting_info->min_point;
$is_eligible = 0;
$pass2 = 0;
$required_form4 = 0;

/********************************FORM IV CRITERIA *******************************************************/

//Get Form IV exclusive subjects
$exclusive_subject4 = explode(',', $setting_info->form4_exclusive);

//Get Inclusive Subject
$inclusive_subject4 = json_decode($setting_info->form4_inclusive, true);

//Get all Form IV Certificates
$OLEVEL_CERTIFICATE = $this->db->where(array('certificate' => 1, 'applicant_id' => $applicant_id))->get('application_education_authority')->result();

//Get all subjects for IV
$OLEVEL_SUBJECT_LIST = $this->db->where(array('certificate' => 1, 'applicant_id' => $applicant_id))->order_by('grade', 'ASC')->get('application_education_subject')->result();
$SUBJECT_HOLD = array();

foreach ($OLEVEL_SUBJECT_LIST as $SUB_KEY => $SUB_VALUE) {
    $grade = strtoupper(trim($SUB_VALUE->grade));
    $subject = trim($SUB_VALUE->subject);
    $SUBJECT_HOLD[$subject] = $grade;
    if (!in_array($subject, $exclusive_subject4)) {
        switch ($grade) {
            case "A":
                $pass2 += 1;
                break;
            case "B+":
                $pass2 += 1;
                break;
            case "B":
                $pass2 += 1;
                break;
            case "C":
                $pass2 += 1;
                break;
            case "D":
                $pass2 += 1;
                break;
            case "E":
                $pass += 1;
                break;
            default:
                break;
        }
    }

}


// check if required subject has valid grades == USING AND
$required_not_found = 0;
$required_check_fail = 0;
foreach ($inclusive_subject4 as $req_K => $req_V) {
    if (array_key_exists($req_K, $SUBJECT_HOLD)) {
        if (!is_grade_greater_equal($SUBJECT_HOLD[$req_K], $req_V)) {
            $required_check_fail++;
        }
    } else {
        $required_not_found++;
    }
}


if ($required_check_fail == 0 && $required_not_found == 0) {
    $is_eligible = 1;
} else {
    $is_eligible = 0;
    $remark = "Do not Pass required subjects of Form IV";
}

if ($is_eligible) {

// check if required subject has valid grades == USING OR
//Get  Subject by OR condition
    $inclusive_subject4_OR = json_decode($setting_info->form4_or_subject, true);
    $is_or_condition_passed = 0;
    $is_or_condition_passed_required=0;
    if (count($inclusive_subject4_OR) > 0) {
        $get_key_in_array = array_keys($inclusive_subject4_OR);
        $get_subject_in_array = $inclusive_subject4_OR[$get_key_in_array[0]];
        $tmp  = explode('|',$get_key_in_array[0]);
        $get_key_in_array_grade = $tmp[0];
        $is_or_condition_passed_required = $tmp[1];
        foreach ($get_subject_in_array as $p => $vp) {
            if (array_key_exists($vp, $SUBJECT_HOLD)) {
                if (is_grade_greater_equal($SUBJECT_HOLD[$vp], $get_key_in_array_grade)) {
                    $is_or_condition_passed++;
                }
            }
        }

    }

    if ($is_or_condition_passed >= $is_or_condition_passed_required) {
        $is_eligible = 1;
    } else {
        $is_eligible = 0;
        $remark = "Do not Pass  in one of the subject under OR CONDITION Form IV ";
    }

    if ($is_eligible) {
//Check if has pass total number of subject as required
        if ($setting_info->form4_pass == 0 && $is_eligible == 1) {
            $is_eligible = 0;
            $remark = "Setting of subjects No. to pass Form IV not found";
        } else if ($is_eligible) {
            if ($pass2 >= $setting_info->form4_pass) {
                $is_eligible = 1;
            } else {
                $is_eligible = 0;
                $remark = "Do not Pass '.$setting_info->form4_pass.' subjects of Form VI";
            }
        }

    }
}

/*********************************** FORM VI CRITERIA *********************************************/

//Start Verification for form VI
if ($is_eligible) {
    //Get Form VI exclusive subjects
    $exclusive_subject6 = explode(',', $setting_info->form6_exclusive);

    //Get Inclusive Subject
    $inclusive_subject6 = json_decode($setting_info->form6_inclusive, true);

    //Get all Form VI Certificates
    $ALEVEL_CERTIFICATE = $this->db->where(array('certificate' => 2, 'applicant_id' => $applicant_id))->get('application_education_authority')->result();
    $sitting_no = count($ALEVEL_CERTIFICATE);

    //Get all subjects for VI
    $ALEVEL_SUBJECT_LIST = $this->db->where(array('certificate' => 2, 'applicant_id' => $applicant_id))->order_by('grade', 'ASC')->get('application_education_subject')->result();

    $SUBJECT_HOLD = array();
    foreach ($ALEVEL_SUBJECT_LIST as $SUB_KEY => $SUB_VALUE) {
        $grade = strtoupper(trim($SUB_VALUE->grade));
        $subject = trim($SUB_VALUE->subject);
        $SUBJECT_HOLD[$subject] = $grade;
        if (!in_array($subject, $exclusive_subject6)) {
            switch ($grade) {
                case "A":
                    if (array_key_exists($subject, $inclusive_subject6)) {
                        $point += 5;
                    }
                    $pass += 1;

                    break;
                case "B":
                    if (array_key_exists($subject, $inclusive_subject6)) {
                        $point += 4;
                    }
                    $pass += 1;
                    break;
                case "C":
                    if (array_key_exists($subject, $inclusive_subject6)) {
                        $point += 3;
                    }
                    $pass += 1;
                    break;
                case "D":
                    if (array_key_exists($subject, $inclusive_subject6)) {
                        $point += 2;
                    }
                    $pass += 1;
                    break;
                case "E":
                    if (array_key_exists($subject, $inclusive_subject6)) {
                        $point += 1;
                    }
                    $pass += 1;
                    break;
                default:
                    break;
            }
        }

    }


    // check if required subject has valid grades
    $required_not_found = 0;
    $required_check_fail = 0;
    foreach ($inclusive_subject6 as $req_K => $req_V) {
        if (array_key_exists($req_K, $SUBJECT_HOLD)) {
            if (!is_grade_greater_equal($SUBJECT_HOLD[$req_K], $req_V)) {
                $required_check_fail++;
            }
        } else {
            $required_not_found++;
        }
    }


    if ($required_check_fail == 0 && $required_not_found == 0) {
        $is_eligible = 1;
    } else {
        $is_eligible = 0;
        $remark = "Do not Pass required subjects of Form VI ";
    }


    if ($is_eligible) {

        // check if required subject has valid grades == USING OR
        //Get  Subject by OR condition
        $inclusive_subject6_OR = json_decode($setting_info->form6_or_subject, true);
        $is_or_condition_passed = 0;
        $is_or_condition_passed_required=0;
        if (count($inclusive_subject6_OR) > 0) {
            $get_key_in_array = array_keys($inclusive_subject6_OR);
            $get_subject_in_array = $inclusive_subject6_OR[$get_key_in_array[0]];
            $tmp  = explode('|',$get_key_in_array[0]);
            $get_key_in_array_grade = $tmp[0];
            $is_or_condition_passed_required = $tmp[1];
            foreach ($get_subject_in_array as $p => $vp) {
                if (array_key_exists($vp, $SUBJECT_HOLD)) {
                    if (is_grade_greater_equal($SUBJECT_HOLD[$vp], $get_key_in_array_grade)) {
                        $is_or_condition_passed++;
                    }
                }
            }

        }

        if ($is_or_condition_passed >= $is_or_condition_passed_required) {
            $is_eligible = 1;
        } else {
            $is_eligible = 0;
            $remark = "Do not Pass  in one of the subject under OR CONDITION Form VI ";
        }


        if ($is_eligible) {

            if ($setting_info->form6_pass == 0 && $is_eligible == 1) {
                $is_eligible = 0;
                $remark = "Setting of subjects number to pass Form VI not found";
            } else if ($is_eligible) {
                if ($pass >= $setting_info->form6_pass) {
                    $is_eligible = 1;
                } else {
                    $is_eligible = 0;
                    $remark = "Do not Pass '.$setting_info->form6_pass.' subjects of Form VI";
                }
                if ($is_eligible) {
                    if ($point >= $min_point && $is_eligible == 1) {
                        $status = 1;
                        $remark = 'Eligible';
                    }
                }
            }
        }
    }
}