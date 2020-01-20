<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');

}
if (isset($_GET)) {

    $title1 = '';
    if (isset($_GET['key']) && $_GET['key'] <> '') {
        $title1 .= " Searching Key :<strong> " . $_GET['key'] . '</strong> &nbsp; &nbsp; &nbsp;';
    }

    if (isset($_GET['type']) && $_GET['type'] <> '') {
        $title1 .= " Programme Name :<strong> " . get_code($_GET['type']) . '</strong>';
    }

    if (isset($_GET['from']) && $_GET['from'] <> '') {
        $title1 .= " From :<strong> " . $_GET['from'] . '</strong>';
    }

    if (isset($_GET['to']) && $_GET['to'] <> '') {
        $title1 .= " To :<strong> " . $_GET['to'] . '</strong>';
    }

    if (isset($_GET['year']) && $_GET['year'] <> '') {
        $title1 .= " Exam  Reogistration List In :<strong> " . $_GET['year'] . '</strong>';
    }

    if (isset($_GET['name']) && $_GET['name'] <> '') {
        $title1 .= " Search key :<strong> " . $_GET['name'] . '</strong>';
    }

    if (isset($_GET['cooperate']) && $_GET['cooperate'] <> '') {
        $title1 .= " Cooperate ID :<strong> " . $_GET['cooperate'] . '</strong>';
    }

    if ($title1 == '') {
        $title1 .= " Exam Registration List In :<strong> " . date("Y") . '</strong>';
    }

    echo '<div class="alert alert-warning">' . $title1 . '</div>';
}

?>

<div class="ibox">
    <div class="ibox-title">
        <h5>Exam Registration List</h5>
    </div>
    <div class="ibox-content">
        <?php echo form_open(site_url('exam_registration_list'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group no-padding">

            <div class="col-md-2">
                <select name="type" class="form-control">
                    <option value="">&nbsp;&nbsp;&nbsp;&nbsp;[ Search by programme ]</option>
                    <?php
                    foreach (receive_programme_code() as $tkey => $tvalue) {
                        ?>
                        <option <?php echo(isset($_GET['type']) ? ($_GET['type'] == $tkey ? 'selected="selected"' : '') : '') ?>
                                value="<?php echo $tkey; ?>"><?php echo $tvalue ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>



            <div class="col-md-2">
                                <select name="year" class="form-control">
                                    <option value="">[ By year ]</option>
                                    <?php
                                    $years = $this->db->query("SELECT distinct exam_year FROM student_exam_registered")->result();
                                    foreach ($years as $key => $year) {
                                        ?>
                                        <option <?php echo(isset($_GET['year']) ? ($_GET['year'] == $year->exam_year ? 'selected="selected"' : '') : '') ?>
                                                value="<?php echo $year->exam_year; ?>"><?php echo $year->exam_year; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
            </div>



            <div class="col-md-3">
                <?php
                $query = "SELECT * FROM exam_sessions";
                $exam_sessions = $this->db->query($query)->result();
                ?>
                <select name="exam_session" class="form-control">
                    <option value=""> Search by Session</option>
                    <?php
                    foreach ($exam_sessions as $key => $value) {
                        ?>
                        <option <?php echo(isset($_GET['exam_session']) ? $_GET['exam_session'] : '') ?>
                                value="<?php echo $value->title; ?>"><?php echo $value->title; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
                echo form_error('exam_session');
                ?>
            </div>

            <div class="col-md-3">
                <?php
                $query = "SELECT * FROM examination_centers";
                $exam_sessions = $this->db->query($query)->result();
                ?>
                <select name="exam_center" class="form-control">
                    <option value=""> Search by Center</option>
                    <?php
                    foreach ($exam_sessions as $key => $value) {
                        ?>
                        <option <?php echo(isset($_GET['exam_session']) ? $_GET['exam_session'] : '') ?>
                                value="<?php echo $value->id; ?>"><?php echo $value->center_name; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
                echo form_error('exam_center');
                ?>
            </div>

            <div class="col-md-1">
                <input type="submit" value="Search" class="btn btn-success btn-sm">
            </div>
        </div>
        <?php echo form_close(); ?>
        <hr>


            <div style="clear: both;"></div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered dt-responsive  text-align"  id="datatable" width="100%">

                <thead>
                    <tr>
                        <th style="width: 2%; text-align: center">S/No</th>
                        <th>RegNo</th>
                        <th>Name</th>
                        <th>Membership</th>
                        <th>Programme</th>
                        <th>Courses</th>
                        <th>Center</th>
<!--                        <th>Mobile</th>-->
                        <th>Year</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i=1;
                    foreach ($member_list as $applicant_key => $applicant_value) {
                        $applicant_info = $this->db->get_where('application',array('user_id'=>$applicant_value->user_id))->row();

                        $Student_exam_registered = $this->db->query("select Distinct coursecode from  student_exam_registered where registration_number='".$applicant_value->regno."'")->result();
                        $registered_courses="";

                        if($Student_exam_registered)
                        { $j=0;
                            foreach ($Student_exam_registered as $exam_key => $exam_value) {
                                if($j==0)
                                {
                                    $registered_courses=$exam_value->coursecode;
                                }else{
                                    $registered_courses=$registered_courses.','.$exam_value->coursecode;

                                }
                                $j++;
                            }
                        }


                        if($applicant_info){
                            $applicant_id = $applicant_info->id;
                        }
//                        $corp_id=$applicant_value->cooperate;
//                        if($corp_id==0){
//                            $institution="NULL";
//                        }else{
//                            $corporates=$this->common_model->get_cooperate($corp_id)->result();
//                            foreach ($corporates as $key => $value) {
//                                $institution=$value->institution_name;
//                            }
//                        }
                        $center=get_value('examination_centers',$applicant_value->center_id,'center_name')
                        ?>
                        <tr>
                            <td style="text-align: right;"><?php echo ($i); ?></td>
                            <td style="text-align: left;"><?php echo $applicant_value->regno; ?></td>
                            <td style="text-align: left;"><?php echo $applicant_value->first_name.' '.$applicant_value->other_names.' '.$applicant_value->surname; ?></td>
                            <td style="text-align: left;"><?php echo (isset($applicant_value->member_type) ? ($applicant_value->member_type<> '' ? member_type($applicant_value->member_type) : '') : ''); ?></td>
                            <td style="text-align: left;"><?php if ($applicant_value->programme_id == 0) {
                                    echo 0;
                                } else {
                                    echo receive_programme_code($applicant_value->programme_id);
                                                } ?></td>

                            <td style="text-align: left;"><?php  echo $registered_courses; ?></td>
                                <td style="text-align: left;"><?php  echo $center; ?></td>

                            <!--                            <td style="text-align: left;">--><?php //echo $applicant_value->email; ?><!--</td>-->
<!--                            <td style="text-align: left;">--><?php //echo $applicant_value->mobile; ?><!--</td>-->
<!--                            <td style="text-align: left;">--><?php // echo $institution; ?>
                            <td style="text-align: left;"><?php  echo $applicant_value->exam_year; ?></td>


                        </tr>
                        <?php
                        $i=$i+1;
                    }
                    ?>
                    </tbody>
                </table>
<!--                <div>-->
<!--                    <div style="clear: both;"></div>-->
<!--                    <a href="--><?php //echo site_url('report/export_member/?' . ((isset($_GET) && !empty($_GET)) ? http_build_query($_GET) : '')); ?><!--"-->
<!--                       class="btn btn-sm btn-success">Export Excel</a>-->
<!---->
<!--                </div>-->
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("body").on("click", ".popup_applicant_info", function () {
            var ID = $(this).attr("ID");
            var title = $(this).attr("title");
            $.confirm({
                title: title,
                content: "URL:<?php echo site_url('popup_applicant_info') ?>/" + ID + '/?status=1',
                confirmButton: 'Print',
                columnClass: 'col-md-10 col-md-offset-2',
                cancelButton: 'Close',
                extraButton: 'ExtraB',
                cancelButtonClass: 'btn-success',
                confirmButtonClass: 'btn-success',
                confirm: function () {
                    window.location.href = '<?php echo site_url('print_application') ?>/' + ID;
                    return false;
                },
                cancel: function () {
                    return true;
                }

            });
        })
    });
</script>



