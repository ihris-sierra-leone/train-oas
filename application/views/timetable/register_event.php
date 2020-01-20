<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5><?php echo (isset($id) ? 'Edit User Information':'Add Exam Timetable '); ?></h5>
    </div>
    <div class="ibox-content">

        <?php echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>

        <div class="form-group"><label class="col-lg-3 control-label">Date : <span
                        class="required">*</span></label>

            <div class="col-lg-4">
                <input type="text" value="<?php echo (isset($exam_info) ? format_date($exam_info->exam_date,false):set_value('exam_date')); ?>" class="form-control mydate_input" name="date">
                <?php echo form_error('date'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Time (12 Hrs ): <span
                        class="required">*</span></label>

            <div class="col-lg-4">
                <input type="text" placeholder="Time format: H:m " value="<?php echo (isset($exam_info) ? $exam_info->time:set_value('time')); ?>" class="form-control" name="time">
                <?php echo form_error('time'); ?>
            </div>
        </div>

        <div class="form-group" id="course"><label class="col-lg-3 control-label">Course : <span class="required">*</span></label>
            <div class="col-lg-4">
                <select name="course"  class="form-control">
                    <option value=""> [ Select Course ]</option>
                    <?php
                    $sel = (isset($exam_info) ? $exam_info->coursecode: set_value('coursecode'));
                    foreach($course_list as $key=>$value){
                        ?>
                        <option <?php echo ($sel == $value->id ? 'selected="selected"':''); ?> value="<?php echo$value->id; ?>"><?php echo $value->name; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
                echo form_error('course');
                ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-8">
                <input class="btn btn-sm btn-success" type="submit" value="Save Information"/>
            </div>
        </div>

        <?php echo form_close(); ?>

    </div>
</div>

<script>
    $(document).ready(function () {

        $('.mydate_input').datepicker({
            autoclose: true,
            format: "dd-mm-yyyy",
            endDate:"31-12-2025"
        });

    })
</script>
