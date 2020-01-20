<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}

?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Enter Registration Number to Search for a Student</h5>
        <?php echo form_open(site_url('transcript_remark'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="col-md-1 pull-right no-padding">
            <input type="submit" value="Search" class="btn btn-success btn-sm">
        </div>
        <div class="col-md-5 pull-right ">
            <input type="text" value="<?php echo(isset($_GET['key']) ? $_GET['key'] : '') ?>" name="key"
                   class="form-control" placeholder="Search....">
        </div>
    </div>
    <?php
    echo form_close();

    if (isset($student_info) && !empty($student_info) <> ''){
    ?>
    <div class="ibox-title clearfix">
        <table cellspacing="0" cellpadding="0" class="table table-bordered"
               style="" id="applicantlist">
            <thead>
            <tr>
                <th style="width: 200px;">Candidate Name</th>
                <th style="width: 100px; text-align: center;">Registration Number</th>
                <th style="width: 60px;">Action</th>

            </tr>
            </thead>
            <tbody>
            <?php
            $page = 1;
            foreach ($applicant_list as $applicant_key => $applicant_value) {
                //  $mobile1=$this->db->query("select * from students where user_id='".$applicant_value->user_id."'  ")->row()->mobile;

                ?>
                <tr>
                    <td style="text-align: left;"><?php echo $applicant_value->first_name . ' ' . $applicant_value->other_names . ' ' . $applicant_value->surname; ?></td>
                    <td style="text-align: center;"><?php echo $applicant_value->registration_number; ?></td>
                    <td style="text-align: left;"><?php echo '<a href="javascript:void(0);" style="display: block;" class="popup_student_remark" ID="' . encode_id($applicant_value->id) . '"
                      title="' . $applicant_value->first_name . ' ' . $applicant_value->other_names . ' ' . $applicant_value->surname . '"><i class="fa fa-eye"></i> priview statement</a>'; ?></td>

                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

        <br>
        <h4>Examinations results</h4>
        <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th style="width: 50px;">S/No</th>
                <th style="width: 100px;">Year</th>
                <th style="width: 100px;">Session</th>
                <th>Subject</th>
                <th style="width: 150px;">Remark</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($results as $key => $value) {
                if($value->published == 0){
                    ?>
                    <tr><td style="vertical-align: middle; text-align: center; padding-right: 5px;">
                        <a href="<?php echo site_url('edit_score/?code='.$value->course.'&reg='.$regno) ?>"></i><?php echo($key + 1) ?></a>
                    </td>
                <?php } else{ ?>
                    <td><?php echo $key+1 ?></td> <?php } ?>
                <td><?php echo $value->	academic_year; ?></td>
                <td><?php echo $value->exam_session; ?></td>
                <td><?php echo $this->common_model->get_course($value->course)->row()->name; ?></td>
                <td><?php $score=0; $remark='';
                    $score = $value->score_marks;
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
                    echo $remark; ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>


    </div>
</div>
<?php } ?>
</div>
</div>

<script>
    $(document).ready(function () {
        $("body").on("click",".popup_student_remark",function () {
            var ID = $(this).attr("ID");
            var title = $(this).attr("title");
            $.confirm({
                title:title,
                content:"URL:<?php echo site_url('popup_student_remark') ?>/"+ID+'/?status=1',
                confirmButton:'Print',
                columnClass:'col-md-10 col-md-offset-2',
                cancelButton:'Close',
                extraButton:'ExtraB',
                cancelButtonClass: 'btn-success',
                confirmButtonClass: 'btn-success',
                confirm:function () {
                    window.location.href = '<?php echo site_url('print_student_remark') ?>/'+ID;
                    return false;
                },
                cancel:function () {
                    return true;
                }

            });
        })
    });
</script>

