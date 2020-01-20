<?php
/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 2:25 PM
 */
if(has_section_role($MODULE_ID,$GROUP_ID,'MANAGE_USERS')){ ?>

    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'profile' ? 'active':''):''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-user"></i> <span class="nav-label">Profile</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">

          <?php
            if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_USERS','profile')){ ?>
                <li ><a href="<?php echo site_url('myprofile'); ?>"><i class="fa fa-angle-right"></i> My profile</a></li>
            <?php } ?>
        </ul>
    </li>

    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'billing' ? 'active':''):''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-dollar"></i> <span class="nav-label">Billing</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">

          <?php
            if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_USERS','billing')){ ?>
                <li ><a href="<?php echo site_url('annual_billing'); ?>"><i class="fa fa-angle-right"></i> Annual billing</a></li>
            <?php } ?>

            <li ><a href="<?php echo site_url('member_fee_statement'); ?>"><i class="fa fa-angle-right"></i> My Fee Statement</a></li>


        </ul>
    </li>


<?php }

if(has_section_role($MODULE_ID,$GROUP_ID,'MANAGE_EXAMINATION')){ ?>

    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'exam' ? 'active':''):''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-file"></i> <span class="nav-label">Examination</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">

            <?php if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_EXAMINATION','grade_book')){ ?>
                <li ><a href="<?php echo site_url('grade_book'); ?>"><i class="fa fa-angle-right"></i> Gradebook</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_EXAMINATION','select_exam')){ ?>
                <li><a href="<?php echo site_url('select_exam') ?>"><i class="fa fa-angle-right"></i> Examination Registration</a></li>
            <?php }
            if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_EXAMINATION','registered_exam_list')){ ?>
                <li><a href="<?php echo site_url('registered_exam_list') ?>"><i class="fa fa-angle-right"></i> Registered Exams</a></li>
            <?php }

            if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_EXAMINATION','module_results')){ ?>
                <li ><a href="<?php echo site_url('module_results'); ?>"><i class="fa fa-angle-right"></i> Module Results</a></li>
            <?php }

            if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_EXAMINATION','timetable')){ ?>
                <li><a href="<?php echo site_url('timetable'); ?>"><i class="fa fa-angle-right"></i> Timetable</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_EXAMINATION','membership_fee')){  ?>
            	<li><a href="<?php echo site_url('membership_fee'); ?>"><i class="fa fa-angle-right"></i> Billing</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_EXAMINATION','transcript_report')){  ?>
                <li><a href="<?php echo site_url('transcript_report'); ?>"><i class="fa fa-angle-right"></i> Transcript report</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_EXAMINATION','board_report')){?>
                <li><a href="<?php echo site_url('board_report'); ?>"><i class="fa fa-angle-right"></i> Board Report</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_EXAMINATION','cumulative_report')){?>
                <li><a href="<?php echo site_url('cumulative_report'); ?>"><i class="fa fa-angle-right"></i> Cumulative Results</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_EXAMINATION','graduate_report')){?>
                <li><a href="<?php echo site_url('graduate_report'); ?>"><i class="fa fa-angle-right"></i> Graduate Report</a></li>
            <?php }?>
        </ul>
    </li>

<?php } ?>




<li class="<?php echo(isset($active_menu) ? ($active_menu == 'security' ? 'active' : '') : ''); ?>">
    <a href="<?php echo site_url(); ?>">
        <i class="fa fa-lock"></i> <span class="nav-label">Security</span> <span
            class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
        <li><a href="<?php echo site_url('login_history'); ?>"><i class="fa fa-angle-right"></i>Login History</a></li>
        <li><a href="<?php echo site_url('change_password'); ?>"><i class="fa fa-angle-right"></i>Change Password</a></li>
