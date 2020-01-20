<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5><?php echo (isset($id) ? 'Edit Group Information':'Edit Study Level'); ?></h5>
    </div>
    
    <div class="ibox-content">
        <?php echo form_open(current_full_url('simsdata/edit_studylevel/'), ' class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group"><label class="col-lg-3 control-label">Name : <span
                    class="required">*</span></label>

            <div class="col-lg-8">
                <input type="text" value=" " class="form-control" name="name">
                <?php echo form_error('name'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Type : <span
                    class="required">*</span></label>

             <div class="col-lg-8">
                <select name="department"  class="form-control">
                    <option value=""> [ Status ]</option>
                    <option value="CENTRE">CENTRE</option>
                   
                </select>
               
              <?php echo form_error('description'); ?>
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