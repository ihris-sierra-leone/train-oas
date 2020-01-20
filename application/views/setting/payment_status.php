<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>Payment Status</h5>
    </div>
    <div class="ibox-content">

        <?php
        echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>


        <div class="form-group"><label class="col-lg-3 control-label">Payment Status  :  <span class="required">*</span></label>

            <div class="col-lg-8">
                <select name="payment_status">
                    <option value="<?php echo isset($pay_status->payment_status)? $pay_status->payment_status :''; ?>" selected>
                        <?php echo isset($pay_status->payment_status)? $pay_status->payment_status :''; ?>
                    </option>
                    <option value="ACTIVE">ACTIVE</option>
                    <option value="INACTIVE">INACTIVE</option>
                </select>
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
