<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h3>Welcome to SARIS - OAS </h3>
            <hr/>
            <ul>
                <li>SARIS ONLINE APPLICATION SYSTEM is an efficiency system for applicants from different perspective academic areas to apply for different Universities by following simple and easiest steps to fulfil needs of application and admission to a particular University.</li><br>
                <li>It is a reliable online application system without any intervening time and immediately  notification and status upon your application.</li><br>
                <li>Wise and recommended to read and understand admission requirements before starting application process. <a href="javascript:void(0);" data-toggle="popover" role="button" title="" data-original-title="Admission Requirement <a style='float:right;' href='javascript:void(0);' class='close_popover'>X</a>" data-html="true" data-placement="bottom" data-content="1. <a target='_blank' href='<?php //echo ADMISSION_REQUIREMENT_UNDERGRADUATE ?>'>Undergraduate Programmes</a><br/><br/>2. <a target='_blank' href='<?php //echo ADMISSION_REQUIREMENT_POSTGRADUATE; ?>'>Postgraduate Programmes</a>" style="font-size: 12px; font-weight: bold; text-decoration: underline;">Admission Requirements</a>. </li><br/>
                <li>Click link to start application: <a href="<?php //echo site_url('registration_start'); ?>" style="font-weight: bold; text-decoration: underline;">Start Application</a>.  </li>
            </ul>
        </div>
        <div class="col-md-offset-1 col-md-6">
            <h3> Existing Member Create Account </h3>
            <hr/>
            <div class="clear-form">
        <?php echo form_open(current_full_url(), ' class="form-horizontal ng-pristine ng-valid"') ?>
                <div class="form-heading">
                    <h3 style="border-bottom: 1px solid #555; padding-bottom: 4px;">Create Account</h3>
                </div>
                <div class="form-body">
                    <br/>
                    <div style="color: red; font-size: 12px;">
                        <?php if (isset($message)) {
                            echo $message;
                        } else if ($this->session->flashdata('message') != '') {
                            echo $this->session->flashdata('message');
                        }
                        ?>
                    </div>
                    <p>
                        <label for="password" style="font-size: 14px;">Registration</label>
                        <input type="text" name="regno"  id="reg_no" class="col-md-12"  <?php if(isset($student_info))  echo 'readonly'; ?>  value=" <?php if(isset($student_info))  echo $student_info->registration_number; ?>" style="margin-top: 0px;" id="identity" placeholder="Reg Number">
                    <?php echo form_error('regno'); ?>

                    </p>



                    <p>
                        <label for="Email" style="font-size: 14px;">User Name</label>
                        <input type="text" name="username" class="col-md-12" value="" style="margin-top: 0px;" id="username" placeholder="Username">
                    <?php echo form_error('username'); ?>

                    </p>





                    <p>
                        <label for="Email" style="font-size: 14px;">Password</label>     
                         <input type="password" name="password" class="col-md-12" value="" style="margin-top: 0px;" id="password"  placeholder="Password">
                             <?php echo form_error('password'); ?>

                        </p>




                    <p>
                          <label for="Email" style="font-size: 14px;">Comfirm Password</label>

                          <input type="password" name="password_confirm" class="col-md-12" value="" style="margin-top: 0px;" id="password" placeholder="Confirm Password" >
                                    <?php echo form_error('password_confirm'); ?>
                    </p>


                     <div class="body-split clearfix" style="margin-left: 30px;">
                    
                    <div class="pull-right">
                                 <button class="btn btn-success pull-right" type="submit">Create Account </button>
                        </div>
                    </div>
                </div>
                <div class="form-footer">
                    <hr/>
                    <p class="center">
                        <a href="<?php echo site_url('login'); ?>">Back to Login</a>
                    </p>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

</div>







