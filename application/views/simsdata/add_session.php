<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5><?php echo (isset($id) ? 'Edit Group Information':'Add Exam Session'); ?></h5>
    </div>

    <div class="ibox-content">
        <?php echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group"><label class="col-lg-3 control-label">Title : <span
                    class="required"></span></label>

            <div class="col-lg-8">
                <input type="text" value="<?php echo (isset($session_info) ? $session_info->title:set_value('title')); ?> " class="form-control" name="title">
                <?php echo form_error('title'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Status : <span
                    class="required"></span></label>

           <div class="col-lg-8">
                <select name="status"  class="form-control">
                    <option value=""><?php echo (isset($session_info) ? $session_info->status:set_value('status')); ?></option>
                    <option value="1">1</option>
                    <option value="0">0</option>

                </select>
              <?php echo form_error('status'); ?>
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
