<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Examination Registration</h5>

    </div>
    <div class="ibox-content">

        <?php echo form_open_multipart(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>

        <div class="form-group"><label class="col-lg-3 control-label">Preffered Center : <span class="required">*</span></label>

            <div class="col-lg-8">
                <select name="center" class="form-control">
                    <option value="">Select Center</option>
                    <?php
                    foreach($center_list as $center){
                        ?>
                        <option value="<?php echo $center->id; ?>"><?php echo $center->center_name; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php echo form_error('center'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Course : <span class="required">*</span></label>

            <div class="col-lg-8">
                <select name="courses[]" class="form-control"  multiple>
                    <option value="">Select Courses</option>
                    <?php

                    foreach($course_list as $course){
                        ?>
                        <option value="<?php echo $course->id; ?>"><?php echo $course->name; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php echo form_error('course'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Receipt : <span
                        class="required">*</span></label>

            <div class="col-lg-8">
                <input type="file" value="" class="form-control" name="userfile" title="upload doc,docx, pdf, jpeg or png">
                <?php echo isset($upload_error) ?  $upload_error : ''; ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-8">
                <input class="btn btn-sm btn-success" type="submit" value="Submit"/>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>