<?php

if (isset($result_error)) {
    foreach($result_error as $key=>$value){
        echo $value;
        echo "<br/>";
    }
}

if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5><?php echo 'Grade Book'; ?></h5>
    </div>
    <div class="ibox-content">
        <?php echo form_open_multipart(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>
        <?php echo form_hidden('post_data', '1'); ?>
        <div class="form-group">
            <div class="col-lg-8 col-lg-offset-3">
               <a href="../uploads/template/result_template.xlsx">Download Template</a>
            </div>
        </div>

        <div class="form-group" id="course"><label class="col-lg-3 control-label">Course : <span class="required">*</span></label>
            <div class="col-lg-6">
                <select name="course"  class="form-control">
                    <option value=""> [ Select Course ]</option>
                    <?php
                    foreach($course_list as $key=>$value){
                        ?>
                        <option value="<?php echo$value->code; ?>"><?php echo $value->name; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
                echo form_error('course');
                ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Results Sheet : <span
                        class="required">*</span></label>

            <div class="col-lg-6">
                <input type="file" value="" class="form-control" name="userfile">
                <?php echo isset($upload_error) ?  $upload_error : ''; ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-8">
                <input class="btn btn-sm btn-success" type="submit" value="Upload Results"/>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>