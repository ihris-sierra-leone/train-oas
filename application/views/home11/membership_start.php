<?php
include VIEWPATH.'include/pbscrum.php';
?>

<div class="col-lg-12 text-center">
    <h1>Registration Process</h1>
    <?php
    if (isset($message)) {
        echo $message;
    } else if ($this->session->flashdata('message') != '') {
        echo $this->session->flashdata('message');
    }
    ?>
</div>

<div class="row gray-bg">
    <div class="container">
        <div class="col-lg-4">
            <div class="ibox">
                <div class="ibox-heading">
                    <div class="ibox-title"><h5 style="color: brown;"> IMPORTANT NOTE</h5></div>
                </div>
                <div class="ibox-content">

                          <h3><b>Fill your membership number</b></h3>
                            After completion then continue to fill all the required information to complete you account.



                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-heading">
                    <div class="ibox-title"><h5>Member registration number</h5></div>
                </div>
                <div class="ibox-content">

                    <?php  echo form_open(site_url('membership_start'), ' class="form-horizontal ng-pristine ng-valid"') ?>
                    <div class="form-group"><label class="col-lg-3 control-label">Regno : </label>

                        <div class="col-lg-7">
                            <input type="text"
                                   value="<?php echo set_value('regno'); ?>"
                                   class="form-control " name="regno">
                            <?php echo form_error('regno'); ?>
                        </div>
                    </div>
<!--                    <hr>-->

                    <div class="form-group" style="margin-top: 10px;">
                        <div class="col-lg-offset-3 col-lg-6">
                            <input class="btn btn-sm btn-success" type="submit" value="Verify Number"/>
                        </div>
                    </div>
                    <?php echo form_close(); ?>

</div></div></div></div>

