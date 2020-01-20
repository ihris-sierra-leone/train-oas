
<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
if(is_array($student_info) || is_object($student_info)) {
    foreach ($student_info as $key => $value) {
        $name = $value->first_name . ' ' . $value->other_names . ' ' . $value->surname;
        $regno= $value->registration_number;
        $centre = get_centre($value->centre_id);
        $programme = get_code($value->programme_id);
    }
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Edit Examination Score</h5>
    <?php echo form_open(site_url('edit_score'), ' method="POST" class="form-horizontal ng-pristine ng-valid"') ?>
        </div>
        <?php

if(isset($student_info) && !empty($student_info) <> ''){
   echo  "<div class='ibox-content'>
        <h5>
          Registration Number : $regno  <br>
          Name : $name <br>
          Programme : $programme <br>
          Centre   :   $centre <br>
        </h5>";
   ?>

    <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive">
        <thead>
        <tr>
            <th style="width: 50px;">S/No</th>
            <th style="width: 100px;">Year</th>
            <th style="width: 100px;">Session</th>
            <th>Subject</th>
            <th style="width: 150px;">Score</th>
            <th style="width: 150px;">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($results) && !empty($results) <> ''){
            ?>
            <tr><td><?php echo($key + 1) ?></td>
                <td><?php echo $results->academic_year; ?></td>
                <td><?php echo $results->exam_session; ?></td>
                <td><?php echo $this->common_model->get_course($results->course)->row()->name; ?></td>
                <td><?php ?>
                    <input type="hidden" value="<?php echo $regno; ?>" name="regno">
                    <input type="hidden" value="<?php echo $results->course; ?>" name="course">
                    <input type="hidden" value="<?php echo $results->exam_session; ?>" name="esession">
                    <input type="hidden" value="<?php echo $results->academic_year; ?>" name="ayear">
                    <input type="number" value="<?php
                    echo (isset($results) ? $results->score_marks:set_value('score_marks')); ?>" name="score_marks">
                    </td>
                <td>
                    <button value='1' name="update"> Update </button>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
   <?php }
   echo form_close();
   ?>
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

