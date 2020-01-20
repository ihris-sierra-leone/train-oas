<?php
$academic_year = $this->common_model->get_academic_year()->row()->AYear;
$semester = $this->common_model->get_academic_year()->row()->semester;
// nikitaka taarifa nyingine kuhusu programme nachukulia hapa kwenye for each
foreach($programme_info as $key=>$value){
    $programmename=$value->Name;
    $pcode = $value->id;
}
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != ''){
    echo $this->session->flashdata('message');
}
?>

<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5><?php echo $programmename.': '.$semester.' '.$academic_year.' Examinations Results'; ?> </h5>

    </div>
    <div class="ibox-content">
        <?php
        $mysqli = new mysqli("localhost", "root", "Zalongwa06", "zalongwa_saris_tiob");
        /* check connection */
        if ($mysqli->connect_errno) {
            printf("Connect failed: %s\n", $mysqli->connect_error);
            exit();
        }
        $db = new mysqli("localhost", "root", "Zalongwa06", "zalongwa_saris_tiob");
        ?>



      <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th style="width: 10px;">S/No</th>
                    <th style='width: 30px;'>Reg #</th>
                    <?php
                    /* If we have to retrieve large amount of data we use MYSQLI_USE_RESULT */
                    if ($result = $mysqli->query("SELECT code, name, shortname FROM courses WHERE programme_id ='$pcode'", MYSQLI_USE_RESULT)) {
                        while($obj = $result->fetch_object()){
                            $course_arr[] = $obj;
                            $mcode=$obj->shortname;
                            $coursecode=$obj->code;
                            echo "<th style='width: 30px;'>$mcode</th>";
                        }

                        $result->close();
                    }

                    ?>

                    <th style="width: 80px;">Total No. of Passes</th>
                    <th style="width: 80px;">Total No. of Fail</th>
                    <th style="width: 80px;">Total No. of Abscent</th>

                </tr>
                </thead>
                <tbody>
                <?php
                $i = $this->uri->segment(2)+1;
               // var_dump($report_info); exit();
                 foreach ($report_info as $key => $value) {

                     $regno = $value;
                 //}
                    ?>
                    <tr>
                        <td style="vertical-align: middle; text-align: right; padding-right: 5px;"><?php echo $i++; ?>.</td>
                        <td><?php echo (isset($value) ? $value:set_value('registration_number')); ?></td>
                        <?php
                        $count_pass=0;
                        $count_fail=0;
                        $count_abs=0;
                        $mdcounter=0;

                        if ($dbcoursecode = $mysqli->query("SELECT code FROM courses
                                                                   WHERE programme_id ='$pcode'", MYSQLI_USE_RESULT)) {
                            while($course = $dbcoursecode->fetch_object()) {
                                $code = $course->code;
/*
                                $dbreg = $this->db->query("SELECT registration_number FROM student_exam_registered
                                                              WHERE registration_number ='$regno' AND
                                                              coursecode = '$code' AND
                                                              exam_year = '$academic_year' ")->row()->registration_number;
                                   // $regobj = $dbreg->fetch_array(MYSQLI_ASSOC);
                                    //$registered=$regobj['registration_number'];
*/
                                if ($dbscores = $db->query("SELECT score_marks FROM exam_results
                                                              WHERE registration_number ='$regno' AND
                                                              course = '$code' AND
                                                              academic_year = '$academic_year' ", MYSQLI_ASSOC)) {
                                        $scoreobj = $dbscores->fetch_array(MYSQLI_ASSOC);
                                        $key_marks=$scoreobj['score_marks'];
                                        if($key_marks > '0'){
                                            if($key_marks >= '51'){
                                                $count_pass = $count_pass+1;
                                                echo "<td><b>[$key_marks]</b></td>";
                                            }else {
                                                $count_fail = $count_fail+1;
                                                echo "<td>$key_marks</td>";
                                            }
                                        }elseif ($key_marks == '0'){
                                            #Check if is in Registration
                                                    $count_abs = $count_abs+1;
                                                    echo "<td>ABS</td>";
                                        }else{
                                                 echo "<td></td>";
                                         }
                                    $dbscores->close();
                            }else{
                                    echo "<td><b> $mysqli->error</b></td>";
                                }
                        }
                   }
                    ?>
                    <td><?php echo $count_pass;?></td>
                    <td><?php echo $count_fail;?></td>
                        <td><?php echo $count_abs;?></td>
                    </tr>
                <?php
                 }
                ?>
                </tbody>

            </table>

            <div>
                <div style="clear: both;"></div>
                <a href="<?php echo site_url('report/export_board_report/?'.((isset($_GET) && !empty($_GET)) ? http_build_query($_GET):'') ); ?>"
                   class="btn btn-sm btn-success">Export Excel</a>

            </div>
    </div>
</div>

<script>
    $(function(){
        $(".select2_search").select2({
            theme: "bootstrap",
            placeholder: " [ Select Principal ] ",
            allowClear: true
        });

    })
</script>
