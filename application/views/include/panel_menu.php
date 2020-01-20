<li class="<?php echo (isset($active_menu) ? ($active_menu == 'dashboard' ? 'active':''):''); ?>">
    <a href="<?php echo site_url('dashboard'); ?>">
        <i class="fa fa-dashboard"></i> <span class="nav-label">Dashboard</span></a>
</li>

<?php
if (has_section_role($MODULE_ID, $GROUP_ID, 'APPLICANT')) { ?>
    <li class="<?php echo(isset($active_menu) ? ($active_menu == 'applicant_list' ? 'active' : '') : 'active'); ?> ">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-desktop"></i> <span class="nav-label">Applicant</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if(has_role($MODULE_ID,$GROUP_ID,'APPLICANT','applicant_list')){ ?>
                <li ><a href="<?php echo site_url('applicant_list'); ?>"><i class="fa fa-angle-right"></i> Applicant List</a></li>
                <li ><a href="<?php echo site_url('sent_emails_list'); ?>"><i class="fa fa-angle-right"></i> Sent Emails List</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'APPLICANT','short_listed')){ ?>
                <li><a href="<?php echo site_url('short_listed') ?>"><i class="fa fa-angle-right"></i>Applicant Short Listed</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'APPLICANT','applicant_selection')){ ?>
                <li><a href="<?php echo site_url('applicant_selection') ?>"><i class="fa fa-angle-right"></i>Applicant Select</a></li>
            <?php } ?>
        </ul>
    </li>

<?php }if(has_section_role($MODULE_ID,$GROUP_ID,'MANAGE_MEMBERS')){ ?>

    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'member_process' ? 'active':''):''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-user-plus"></i> <span class="nav-label">Member Process</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php// if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_MEMBERS','profile')){?>
            <!-- <li ><a href="<?php //echo site_url('profile'); ?>"><i class="fa fa-angle-right"></i> Profile</a></li> -->

            <?php if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_MEMBERS','manage_member')){ ?>
                <li ><a href="<?php echo site_url('cooperate_member'); ?>"><i class="fa fa-angle-right"></i> Corporate Members</a></li>
            <?php }?>
            <?php if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_MEMBERS','manage_member')){ ?>
                <li><a href="<?php echo site_url('fellow_member') ?>"><i class="fa fa-angle-right"></i> Fellow Member</a></li>
            <?php } ?>
            <?php
            // if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_MEMBERS','manage_member')){ ?>
            <!--  <li><a href="<?php //echo site_url('register') ?>"><i class="fa fa-angle-right"></i> Registration Form</a></li> -->

            <?php
            // }
            if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_MEMBERS','import')){ ?>
<!--                <li ><a href="--><?php //echo site_url('import_new_member'); ?><!--"><i class="fa fa-angle-right"></i> Import Member Info</a></li>-->
                <li ><a href="<?php echo site_url('import'); ?>"><i class="fa fa-angle-right"></i> Import Members</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_MEMBERS','member_list')){ ?>
                <li><a href="<?php echo site_url('member_list'); ?>"><i class="fa fa-angle-right"></i>Member List</a></li>
                <li><a href="<?php echo site_url('exam_registration_list'); ?>"><i class="fa fa-angle-right"></i>Exam Registration list</a></li>
            <?php }?>
        </ul>
    </li>
<?php } ?>
<li class="<?php echo (isset($active_menu) ? ($active_menu=='search_member' ? 'active' : '') : ''); ?>"><a href="<?php echo site_url('search_member_form'); ?>"><i class="fa fa-search"></i>Search member form</a></li>

<li class="<?php echo(isset($active_menu) ? ($active_menu == 'security' ? 'active' : '') : ''); ?>">
    <a href="<?php echo site_url(); ?>">
        <i class="fa fa-lock"></i> <span class="nav-label">Security</span> <span
                class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
        <li><a href="<?php echo site_url('login_history'); ?>"><i class="fa fa-angle-right"></i>Login History</a></li>
        <li><a href="<?php echo site_url('change_password'); ?>"><i class="fa fa-angle-right"></i>Change Password</a></li>