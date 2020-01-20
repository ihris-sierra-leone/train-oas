<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>Registration Deadline</h5>
    </div>
    <div class="ibox-content">

        <?php
        echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>


        <div class="form-group"><label class="col-lg-3 control-label">Application Deadline  : <span class="required">*</span></label>

            <div class="col-lg-8">
                <input type="text" placeholder="DD-MM-YYYY" name="deadline"
                       value="<?php echo format_date($deadline->deadline,false); ?>"
                       class="form-control mydate_input"/>
                <?php echo form_error('deadline'); ?>
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
