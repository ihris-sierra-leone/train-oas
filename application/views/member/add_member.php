<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5><?php echo (isset($id) ? 'Edit User Information':'Add New Member'); ?></h5>
    </div>
    <div class="ibox-content">

        <?php echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>

        <div class="form-group"><label class="col-lg-3 control-label">Institution Name : <span
                        class="required">*</span></label>

            <div class="col-lg-6">
                <input type="text" value="<?php echo (isset($member_info) ? $member_info->institution_name:set_value('name')); ?>" class="form-control" name="name">
                <?php echo form_error('name'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Mobile : </label>

            <div class="col-lg-6">
                <input type="text" value="<?php echo (isset($member_info) ? $member_info->mobile:set_value('mobile')); ?>" class="form-control" name="mobile">
                <?php echo form_error('mobile'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Telephone : </label>

            <div class="col-lg-6">
                <input type="text" value="<?php echo (isset($member_info) ? $member_info->telephone:set_value('telephone')); ?>" class="form-control" name="telephone">
                <?php echo form_error('telephone'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Fax : </label>
            <div class="col-lg-6">
                <input type="text" value="<?php echo (isset($member_info) ? $member_info->fax:set_value('fax')); ?>" class="form-control" name="fax">
                <?php echo form_error('fax'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label"> Email:</label>

            <div class="col-lg-6">
                <input type="text" value="<?php echo (isset($member_info) ? $member_info->email:set_value('email')); ?>" class="form-control" name="email">
                <?php echo form_error('email'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">website : </label>

            <div class="col-lg-6">
                <input type="text" value="<?php echo (isset($member_info) ? $member_info->website:set_value('website')); ?>" class="form-control" name="website">
                <?php echo form_error('website'); ?>
            </div>
        </div>


        <div class="form-group"><label class="col-lg-3 control-label">Postal Address : <span
                        class="required">*</span></label>

            <div class="col-lg-6">
                <input type="text" value="<?php echo (isset($member_info) ? $member_info->postal:set_value('postal')); ?>" class="form-control" name="postal">
                <?php echo form_error('postal'); ?>
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
