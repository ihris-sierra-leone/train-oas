<div class="row wrapper border-bottom white-bg page-heading navy-bg" style="margin-top: -20px;">
    <?php if(!isset($CURRENT_USER)){ ?>
    <div class="col-lg-6">
        <ul class="nav navbar-top-links navbar-right" >
            <li class="border-right">
                <a href="<?php echo site_url(); ?>" style="color: #fff;">
                    <i class="fa fa-home"></i> HOME</a>
            </li>
            <li class="border-right">
                <a href="<?php echo site_url('registration_start'); ?>" style="color: #fff;">
                    <i class="fa fa-file-text-o"></i> APPLICATION</a>
            </li>
        </ul>
    </div>
    <?php }else {?>
    <div class="col-lg-6">

        <ul class="nav navbar-top-links navbar-right" >
            <li class="border-right">
                <a href="<?php echo site_url('applicant_dashboard'); ?>" style="color: #fff;">
                    <i class="fa fa-home"></i> HOME</a>
            </li>
            <li class="border-right">
                <a href="<?php echo site_url('logout'); ?>" style="color: #fff;">
                    <i class="fa fa-sign-out"></i> LOGOUT</a>
            </li>
        </ul>

    </div>
    <?php } ?>

</div>