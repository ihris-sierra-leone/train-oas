<?php
$GROUP_ID = 9;
if(has_section_role($MODULE_ID,$GROUP_ID,'ACCOUNTANT')){ ?>

<li ><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-briefcase"></i> Dashboard</a></li>
    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'feesetup' ? 'active':''):''); ?>">
    <a href="<?php echo site_url('dashboard'); ?>">
        <i class="fa fa-cog"></i> <span class="nav-label">Fee Setup</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
         <li ><a href="<?php echo site_url('fee_setup'); ?>"><i class="fa fa-angle-right"></i> Fee settings</a></li>
         <li ><a href="<?php echo site_url('fee_list'); ?>"><i class="fa fa-angle-right"></i> Fee List</a></li>

     </ul>
    </li>
<?php } if (has_section_role($MODULE_ID, $GROUP_ID, 'ACCOUNTANT')) { ?>
    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'collection' ? 'active':''):''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-cc-visa"></i> <span class="nav-label">Billing</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if(has_role($MODULE_ID,$GROUP_ID,'ACCOUNTANT','applicant_list')){ ?>
                <li ><a href="<?php echo site_url('collection'); ?>"><i class="fa fa-angle-right"></i> Application Fee</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'ACCOUNTANT','short_listed')){ ?>
                <li><a href="<?php echo site_url('annualSubFee') ?>"><i class="fa fa-angle-right"></i>Annual sub Fee</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'ACCOUNTANT','applicant_selection')){ ?>
                <li><a href="<?php echo site_url('examFee') ?>"><i class="fa fa-angle-right"></i>Examinations Fee</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'ACCOUNTANT','statement')){ ?>
                <li><a href="<?php echo site_url('statement') ?>"><i class="fa fa-angle-right"></i>Fee statement</a></li>
            <?php } ?>
        </ul>
    </li>
<?php } ?>
<li class="<?php echo (isset($active_menu) ? ($active_menu == 'feesetup' ? 'active':''):''); ?>">
    <a href="<?php echo site_url('dashboard'); ?>">
        <i class="fa fa-cog"></i> <span class="nav-label">Receive payments</span><span class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
        <!--            <li ><a href="--><?php //echo site_url('register_new_member'); ?><!--"><i class="fa fa-angle-right"></i> New Member</a></li>-->
        <li ><a href="<?php echo site_url('register_existing_member'); ?>"><i class="fa fa-angle-right"></i> Annual payments</a></li>
        <li ><a href="<?php echo site_url('import_examination_payments') ; ?>"><i class="fa fa-angle-right"></i> Exam payments</a></li>

    </ul>
</li>
<li class="<?php echo (isset($active_menu) ? ($active_menu == 'reports' ? 'active':''):''); ?>">
    <a href="<?php echo site_url('dashboard'); ?>">
        <i class="fa fa-file-o"></i> <span class="nav-label">Cash reports</span><span class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
        <!--            <li ><a href="--><?php //echo site_url('register_new_member'); ?><!--"><i class="fa fa-angle-right"></i> New Member</a></li>-->
        <li ><a href="<?php echo site_url('annual_report'); ?>"><i class="fa fa-angle-right"></i> Annual report</a></li>
        <li ><a href="<?php echo site_url('exam_report') ; ?>"><i class="fa fa-angle-right"></i> Exam report</a></li>

    </ul>
</li>
<li><a href="<?php echo site_url('member_list'); ?>"><i class="fa fa-users"></i>Member List</a></li>
<li class="<?php echo(isset($active_menu) ? ($active_menu == 'security' ? 'active' : '') : ''); ?>">
    <a href="<?php echo site_url(); ?>">
        <i class="fa fa-lock"></i> <span class="nav-label">Security</span> <span
            class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
        <li><a href="<?php echo site_url('login_history'); ?>"><i class="fa fa-angle-right"></i>Login History</a></li>
        <li><a href="<?php echo site_url('change_password'); ?>"><i class="fa fa-angle-right"></i>Change Password</a></li>