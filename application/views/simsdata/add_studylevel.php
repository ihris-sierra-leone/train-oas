<?php
if (isset($message)) {

    echo $message;

} else if ($this->session->flashdata('message') != '') {

    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5><?php echo (isset($id) ? 'Edit Group Information':'Add Study Level'); ?></h5>
    </div>


    <div class="ibox-content">
        <?php echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group"><label class="col-lg-3 control-label">Studylevel Name : <span
                    class="required">*</span></label>

            <div class="col-lg-8">
                <input type="text" value="<?php echo (isset($studylevel_info) ? $studylevel_info->name:set_value('name')); ?>" class="form-control" name="name">
                <?php echo form_error('name'); ?>
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
