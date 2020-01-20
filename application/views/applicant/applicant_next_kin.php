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
            <h5>Next of Kin / Parents / Guardians Particulars</h5></div>
    </div>

    <div class="ibox-content">


        <?php echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>

        <div style=" color: brown; font-weight: bold; border-bottom: 1px solid brown; margin-bottom: 10px;">
            NEXT OF KIN 1 (Primary)
        </div>
        <div class="form-group"><label class="col-lg-3 control-label">Name: <span
                    class="required disabledata">*</span> </label>

            <div class="col-lg-8">
                <input type="text" value="<?php echo (isset($next_kin) ? $next_kin[0]->name:set_value('name')); ?>" class="form-control" name="name">
                <?php echo form_error('name'); ?>
            </div>
        </div>
        <div class="form-group"><label class="col-lg-3 control-label">Mobile Number1 : <span
                    class="required disabledata">*</span> </label>

            <div class="col-lg-8">
                <input type="text" placeholder="Eg. 255xxxxxxxxx" value="<?php echo (isset($next_kin) ? $next_kin[0]->mobile1:set_value('mobile1')); ?>" class="form-control" name="mobile1" onKeyPress="return numbersonly(event,this.value)" maxlength="12">
                <?php echo form_error('mobile1'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Mobile Number2 :  </label>

            <div class="col-lg-8">
                <input type="text" placeholder="Eg. 255xxxxxxxxx" value="<?php echo (isset($next_kin) ? $next_kin[0]->mobile2:set_value('mobile2')); ?>" class="form-control" name="mobile2" onKeyPress="return numbersonly(event,this.value)" maxlength="12">
                <?php echo form_error('mobile2'); ?>
            </div>
        </div>


        <div class="form-group"><label class="col-lg-3 control-label">Email :  </label>

            <div class="col-lg-8">
                <input type="text" value="<?php echo (isset($next_kin) ? $next_kin[0]->email:set_value('email')); ?>" class="form-control" name="email">
                <?php echo form_error('email'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Address : <span
                        class="required disabledata">*</span></label>

            <div class="col-lg-8">
                <textarea class="form-control" name="postal"><?php echo (isset($next_kin) ? $next_kin[0]->postal:set_value('postal')); ?></textarea>
                <?php echo form_error('postal'); ?>
            </div>
        </div>
        <div class="form-group"><label class="col-lg-3 control-label">Relation :  </label>

            <div class="col-lg-8">
                <input type="text" value="<?php echo (isset($next_kin) ? $next_kin[0]->relation:set_value('relation')); ?>" class="form-control" name="relation">
                <?php echo form_error('relation'); ?>
            </div>
        </div>


        <div style=" color: brown; font-weight: bold; border-bottom: 1px solid brown; margin-bottom: 10px;">
            NEXT OF KIN 2
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Name:  </label>

            <div class="col-lg-8">
                <input type="text" value="<?php echo (isset($next_kin) ? $next_kin[1]->name:set_value('name2')); ?>" class="form-control" name="name2">
                <?php echo form_error('name2'); ?>
            </div>
        </div>
        <div class="form-group"><label class="col-lg-3 control-label">Mobile Number1 :  </label>

            <div class="col-lg-8">
                <input type="text" placeholder="Eg. 255xxxxxxxxx" value="<?php echo (isset($next_kin) ? $next_kin[1]->mobile1:set_value('mobile21')); ?>" class="form-control" name="mobile21" onKeyPress="return numbersonly(event,this.value)" maxlength="12">
                <?php echo form_error('mobile21'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label">Mobile Number2 :  </label>

            <div class="col-lg-8">
                <input type="text" placeholder="Eg. 255xxxxxxxxx" value="<?php echo (isset($next_kin) ? $next_kin[1]->mobile2:set_value('mobile22')); ?>" class="form-control" name="mobile22" onKeyPress="return numbersonly(event,this.value)" maxlength="12">
                <?php echo form_error('mobile22'); ?>
            </div>
        </div>


        <div class="form-group"><label class="col-lg-3 control-label">Email :  </label>

            <div class="col-lg-8">
                <input type="text" value="<?php echo (isset($next_kin) ? $next_kin[1]->email:set_value('email2')); ?>" class="form-control" name="email2">
                <?php echo form_error('email2'); ?>
            </div>
        </div>

        <div class="form-group"><label class="col-lg-3 control-label"> Address : </label>

            <div class="col-lg-8">
                <textarea class="form-control" name="postal2"><?php echo (isset($next_kin) ? $next_kin[1]->postal:set_value('postal2')); ?></textarea>
                <?php echo form_error('postal2'); ?>
            </div>
        </div>
        <div class="form-group"><label class="col-lg-3 control-label">Relation :  </label>

            <div class="col-lg-8">
                <input type="text" value="<?php echo (isset($next_kin) ? $next_kin[1]->relation:set_value('relation1')); ?>" class="form-control" name="relation1">
                <?php echo form_error('relation1'); ?>
            </div>
        </div>

<!--        --><?php //if($APPLICANT->status == 0){ ?>
        <?php if(1==1){ ?>

        <div class="form-group" style="margin-top: 10px;">
            <div class="col-lg-offset-4 col-lg-6">
                <input class="btn btn-sm btn-success" type="submit" value="<?php echo (!is_section_used('NEXT_KIN',$APPLICANT_MENU) ? 'Save ' :'Edit '); ?>Information"/>
            </div>
        </div>

        <?php }else{ ?>
            <script>
                disable_edit();
            </script>
        <?php } ?>



        <?php echo form_close(); ?>




    </div>

</div>