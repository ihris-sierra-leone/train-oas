<?php
$Paid_amount = $this->applicant_model->get_paid_amount(current_user()->applicant_id);
$amount_required = 100;
if($Paid_amount<$amount_required)
{
    redirect(site_url('applicant_payment'), 'refresh');

}

?>

<div class="ibox">
    <div class="ibox-heading">
        <div class="ibox-title">
            <h5>Profile Picture</h5></div>
    </div>

    <div class="ibox-content">
        <div style="margin-bottom: 15px; color: green; font-weight: bold;">Uploaded Photo should be Passport size with blue background otherwise your application will be rejected</div>


        <?php echo form_open_multipart(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>


        <div class="form-group"><label class="col-lg-3 control-label">&nbsp;</label>

            <div class="col-lg-8">
                <img style="height: 130px; width: 130px;" src="<?php echo HTTP_PROFILE_IMG.$APPLICANT->photo;  ?>" >

            </div>
        </div>

<!--        --><?php //if($APPLICANT->status == 0){ ?>
        <?php if(1 == 1){ ?>

        <div class="form-group"><label class="col-lg-3 control-label">Change Photo <span class="required">*</span> : </label>

            <div class="col-lg-8">
                <input type="file" name="file1" class="form-control"/>
                <?php echo (form_error('file1') ? form_error('file1'):(isset($upload_error) ? '<div class="required">'.$upload_error.'</div>':''));

                ?>
            </div>
        </div>
<input type="hidden" name="test" value="1"/>

        <div class="form-group" style="margin-top: 10px;">
            <div class="col-lg-offset-4 col-lg-6">
                <input class="btn btn-sm btn-success" type="submit" value="<?php echo (!is_section_used('PHOTO',$APPLICANT_MENU) ? 'Upload ' :'Edit '); ?> Picture"/>
            </div>
        </div>
        <?php }else{ ?>
            <script>
                disable_edit();
            </script>
        <?php } ?>
        <?php echo form_close();

        ?>



    </div>
</div>