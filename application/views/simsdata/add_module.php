<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5><?php echo (isset($id) ? 'Edit Group Information':'Add Course'); ?></h5>
    </div>

    <div class="ibox-content">
        <?php echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>
        <div class="form-group"><label class="col-lg-3 control-label">Name : <span
                    class="required"></span></label>

            <div class="col-lg-6">
                <input type="text" value="<?php echo (isset($module_info) ? $module_info->name:set_value('name')); ?> " class="form-control" name="name">
                <?php echo form_error('name'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Short Name : <span
                    class="required"></span></label>

            <div class="col-lg-6">
                <input type="text" value="<?php echo (isset($module_info) ? $module_info->shortname:set_value('shortname')); ?>" class="form-control" name="shortname">
                <?php echo form_error('shortname'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Code : <span
                        class="required"></span></label>

            <div class="col-lg-6">
                <?php if(isset($module_info)){?>
                <input type="text" value="<?php echo (isset($module_info) ? $module_info->code:set_value('code')); ?>"
                       class="form-control" name="code" disabled="disabled"> <?php } else { ?>
                    <input type="text" value=""
                           class="form-control" name="code">
                <?php } echo form_error('code'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Programme : <span
                        class="required"></span></label>

            <div class="col-lg-6">
                <select name="programme"  class="form-control">
                    <option value=""> [ Select Programme ]</option>
                    <?php
                    $sel = (isset($module_info) ? $module_info->programme_id: set_value('programme_id'));
                    foreach($programme_list as $key){
                        ?>
                        <option <?php echo ($sel == $key->id ? 'selected="selected"':''); ?> value="<?php echo $key->id; ?>"><?php echo $key->Name; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
                echo form_error('programme');
                ?>

            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Level : <span
                        class="required"></span></label>
            <div class="col-lg-6">
                <select name="level"  class="form-control">
                    <option value=""> [ Select Level ]</option>
                    <?php
                    $sel = (isset($module_info) ? $module_info->level: set_value('level'));
                    foreach(levels() as $key=>$value){
                        ?>
                        <option <?php echo ($sel == $key ? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <?php
                echo form_error('cat');
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
