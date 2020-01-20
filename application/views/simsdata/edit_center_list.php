<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}

?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5><?php echo (isset($id) ? 'Edit Group Information':'Edit Centre Information'); ?></h5>
    </div>
    
    <div class="ibox-content">
        <?php echo form_open('simsdata/edit_center_list', 'class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group"><label class="col-lg-3 control-label">Center Name : <span
                    class="required"></span></label>

            <div class="col-lg-8">
                <input type="text" value="<?php ?>" class="form-control" name="name">
                <?php echo form_error('name'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Center Code : <span
                    class="required"></span></label>

            <div class="col-lg-8">
                <input type="text" value="<?php echo "value2"; ?>" class="form-control" name="description">
                <?php echo form_error('description'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Center Venue: <span
                    class="required"></span></label>

            <div class="col-lg-8">
                <input type="text" value="<?php echo "value3"; ?>" class="form-control" name="description">
                <?php echo form_error('venue'); ?>
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