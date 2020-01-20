<?php
if(isset($_GET) && isset($_GET['cat']) && isset($_GET['action']) && $_GET['cat'] == 2) {
    echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>
    <input type="hidden" value="2" name="catv">
    <div class="form-group">
        <label class="col-lg-4 control-label">Name of Institution : <span class="required">*</span></label>

        <div class="col-lg-6">
            <input type="text" placeholder="Name of Institution" value="<?php echo set_value('name',(isset($experience_info) ? $experience_info->name:'')); ?>" class="form-control" name="name">
            <?php echo form_error('name'); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-4 control-label">Award Given : <span class="required">*</span></label>

        <div class="col-lg-6">
            <input type="text" placeholder="Award"  value="<?php echo set_value('column1',(isset($experience_info) ? $experience_info->column1:'')); ?>" class="form-control" name="column1">
            <?php echo form_error('column1'); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-4 control-label">Year of Completion: <span class="required">*</span></label>

        <div class="col-lg-6">
            <input type="text" placeholder="Eg: 2015"  value="<?php echo set_value('column2',(isset($experience_info) ? $experience_info->column2:'')); ?>" class="form-control" name="column2">
            <?php echo form_error('column2'); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-4 control-label"></label>

        <div class="col-lg-6">
            <input class="btn btn-sm btn-success" type="submit" value="Save Information"/>
        </div>
    </div>

    <?php echo form_close();
}
?>

<table class="table" style="font-size: 13px;">
    <thead>
    <tr>
        <th style="width: 5%;">S/No</th>
        <th style="width: 40%;">Name of Institution</th>
        <th  style="width:20%;">Award Given</th>
        <th  style="width:20%;">Year of Completion</th>
        <?php  if($APPLICANT->status == 0) { ?>
        <th style="width:15%;">Action</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php
    $data_list = $this->applicant_model->get_experience($APPLICANT->id,null,2)->result();
    if(count($data_list) > 0){
        foreach ($data_list as $dk=>$dv){ ?>
            <tr>
                <td style="text-align: right;"><?php echo $dk+1; ?>.</td>
                <td><?php echo $dv->name; ?></td>
                <td><?php echo $dv->column1; ?></td>
                <td><?php echo $dv->column2; ?></td>
            <?php  if($APPLICANT->status == 0) { ?>
                <td style="color: green;"><a href="<?php echo site_url('applicant_experience/?cat=2&action=edit&id='.$dv->id); ?>" style="color: green;"><i class="fa fa-pencil"></i> Edit</a> |
                    <a class="remove_delete2" href="<?php echo site_url('applicant_experience/?rmid='.$dv->id); ?>" style="color: green;"><i class="fa fa-remove"></i> Delete</a></td>
                <?php } ?>
            </tr>
        <?php } }else{ ?>
        <tr>
            <td colspan="3">No data found !!</td>
        </tr>
    <?php } ?>
    </tbody>
</table>
