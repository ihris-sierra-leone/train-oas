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
        <div class="form-group"><label class="col-lg-3 control-label">Member Type : <span class="required">*</span></label>
        <div class="col-lg-4">
            <select class="form-control" name="member_type" id="member_type">
                <option value=""> [ Select Centre ]</option>
                    <option value="0">Student Member</option>
                    <option value="1">Ordinary Member</option>
                    <option value="2">Associate Member</option>
                 
            </select>
            <?php echo form_error('member_type'); ?>
        </div>
    </div>

    <div class="form-group"><label class="col-lg-3 control-label">Programme : <span class="required">*</span></label>
        <div class="col-lg-4">
        <select class="form-control" id="programme" name="programme">
        <option value="">[ Select Type ]</option>
        <?php
        $sel = (isset($_GET['type']) ? $_GET['type'] : '');
        foreach (receive_programme_code() as $key=>$value){
            echo '<option '.($sel==$key ? 'selected="selected"':'').' value="'.$key.'">'.$value.'</option>';
        }
        ?>
    </select>
            <?php echo form_error('programme'); ?>
        </div>
    </div>

    <div class="form-group"><label class="col-lg-3 control-label">Fee Type : <span class="required">*</span></label>
        <div class="col-lg-4">
            <select class="form-control" name="fee_type" id="fee_type" value="<?php echo (isset($venue_info) ? $venue_info->name:set_value('name')); ?>">
                <option value=""> [ Select Centre ]</option>
            
                    <option value="0">Annual Subscription</option>
                    <option value="1">Exam Fee</option>
                   
            </select>
            <?php echo form_error('fee_type'); ?>
        </div>
    </div>
    

    <div class="form-group"><label class="col-lg-3 control-label">Amount : <span
                    class="required">*</span></label>

        <div class="col-lg-4">
            <input type="text" value="<?php echo (isset($venue_info) ? $venue_info->amount:set_value('name')); ?>" class="form-control" name="amount">
            <?php echo form_error('amount'); ?>
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
