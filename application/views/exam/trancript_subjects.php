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
        <?php echo form_open(site_url('trancript_subjects'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
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
        foreach ($student_info as $key => $value){
            $programme = receive_programme_code($value->programme_id);
            $programmeid=$this->db->query("select * from programme where Name='$programme' ")->row()->id;
            $coursesno = $this->db->query("select count(id) as counter from courses where programme_id=$programmeid ")->row()->counter;

        }
    ?>
    <div class="ibox-title clearfix">
        <table cellspacing="0" cellpadding="0" class="table table-bordered"
               style="" id="applicantlist">
            <thead>
            <tr>
                <th style="width: 200px;">Candidate Name</th>
                <th style="width: 100px; text-align: center;">Registration Number</th>
                <th style="width: 100px; text-align: center;">Programme</th>
                <th style="width: 60px;">Action</th>

            </tr>
            </thead>
            <tbody>
            <?php
            $page = 1;
            foreach ($applicant_list as $applicant_key => $applicant_value) {

                ?>
                <tr>
                    <td style="text-align: left;"><?php echo $applicant_value->first_name . ' ' . $applicant_value->other_names . ' ' . $applicant_value->surname; ?></td>
                    <td style="text-align: center;"><?php echo $applicant_value->registration_number; ?></td>
                    <td style="text-align: center;"><?php echo receive_programme_code($applicant_value->programme_id); ?></td>
                    <td style="text-align: left;"><?php echo '<a href="javascript:void(0);" style="display: block;" class="popup_transcript_subjects" ID="' . encode_id($applicant_value->id) . '"
                      title="' . $applicant_value->first_name . ' ' . $applicant_value->other_names . ' ' . $applicant_value->surname . '"><i class="fa fa-eye"></i> priview statement</a>'; ?></td>

                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

        <br>
        <h4>Attended subjects</h4>
        <?php
        foreach($results as $key => $value) {
        ?>
        <i class="fa fa-angle-right"></i>&nbsp; <?php echo $this->common_model->get_course($value->course)->row()->name; ?>
        <?php }
        $examno = $this->db->query("select count(course) as counter from exam_results where registration_number='".$applicant_value->registration_number."' ")->row()->counter;
        $remain = $coursesno-$examno;
        if($remain != 0) {
            ?>

            <h4>Remained</h4>
            <?php
            echo $remain; exit;
        }

        ?>

    </div>
</div>
<?php } ?>
</div>
</div>

<script>
    $(document).ready(function () {
        $("body").on("click",".popup_transcript_subjects",function () {
            var ID = $(this).attr("ID");
            var title = $(this).attr("title");
            $.confirm({
                title:title,
                content:"URL:<?php echo site_url('popup_transcript_subjects') ?>/"+ID+'/?status=1',
                confirmButton:'Print',
                columnClass:'col-md-10 col-md-offset-2',
                cancelButton:'Close',
                extraButton:'ExtraB',
                cancelButtonClass: 'btn-success',
                confirmButtonClass: 'btn-success',
                confirm:function () {
                    window.location.href = '<?php echo site_url('print_transcript_subjects') ?>/'+ID;
                    return false;
                },
                cancel:function () {
                    return true;
                }

            });
        })
    });
</script>

