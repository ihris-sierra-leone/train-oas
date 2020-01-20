<div class="container">
<div class="row">
    <div class="col-md-5">
        <h3>Welcome to SARIS </h3>
        <hr/>
        <ul>
            <li>The <b>Student Academic Register Information System (SARIS)</b> is an efficiency system for applicants from different perspective academic areas to apply for different Universities by following simple and easiest steps to fulfil needs of application and admission to a particular Institution.</li><br>
            <li>It is a reliable online application system without any intervening time and immediately  notification and status upon your application.</li><br>
            <li>Wise and recomended to read and understand admission requirements before starting application process.  <a href="<?php echo site_url(); ?>" style="font-weight: bold; text-decoration: underline;">Admission Requirements</a>.</li><br/>
            <!-- <li>Click link to start application: <a href="<?php echo site_url('registration_start'); ?>" style="font-weight: bold; text-decoration: underline;">Start Application</a>.  </li> -->
        </ul>
    </div>
    <div class="col-md-offset-1 col-md-6">

        <div class="clear-form">
            <?php echo form_open('login'); ?>
            <div class="form-heading">
                <h3 style="border-bottom: 1px solid #555; padding-bottom: 4px;">Sign In</h3>
            </div>
            <div class="form-body">
                <div style="color: red; font-size: 12px;">
                    <?php if (isset($message)) {
                        echo $message;
                    } else if ($this->session->flashdata('message') != '') {
                        echo $this->session->flashdata('message');
                    }
                    ?>
                </div>

                <input type="text" name="identity" class="col-md-12" placeholder="Username">
                <?php echo form_error('identity'); ?>
                <input type="password" name="password" class="col-md-12" placeholder="Password">
                <?php echo form_error('password'); ?>
                <div class="body-split clearfix" style="margin-left: 30px;">
<!--                    <div class="pull-left">-->
<!--                        <label class="checkbox" style="font-size: 13px;">-->
<!--                            <input type="checkbox" value="remember-me"> Remember me-->
<!--                        </label>-->
<!--                    </div>-->
                    <div class="pull-right">
                        <button class="btn btn-success pull-right" type="submit">Login</button>
                    </div>
                </div>
            </div>
            <div class="form-footer">
<!--                <hr/>-->
                <p class="center">
                    <label> Don't you have account ?  : <a href="<?php echo site_url('registration_start'); ?>">Create Account</a> </label>
                </p>
                <!--                <p class="center">-->
                <!--                  <label> For applicant only : <a href="--><?php //echo site_url('registration_start'); ?><!--">Create Account</a> </label>-->
                <!--                </p>-->

                <p class="center">
                    <label> For Existing Member : <a href="<?php echo site_url('old_member_sign_up'); ?>">Click here</a> </label>
                </p>
                <p class="center">
                  <label style="font-size: 12px;">Forgot your Password ?  <a href="<?php echo site_url('forgot_password'); ?>">Click here</a> </label>
                </p>
            </div>



            </form>
        </div>
    </div>
</div>

</div>
