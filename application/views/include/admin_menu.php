<?php
/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 2:25 PM
 */
if(has_section_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS')){ ?>

    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'sims_data' ? 'active':''):''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-cog"></i> <span class="nav-label">Data Source</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','school_list')){ ?>
                <li ><a href="<?php echo site_url('centre_list'); ?>"><i class="fa fa-angle-right"></i> Centres</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','school_list')){ ?>
                <li ><a href="<?php echo site_url('venue_list'); ?>"><i class="fa fa-angle-right"></i>Venues</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','department_list')){ ?>
                <li><a href="<?php echo site_url('department_list') ?>"><i class="fa fa-angle-right"></i> Departments </a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){ ?>
                <li ><a href="<?php echo site_url('programme_list'); ?>"><i class="fa fa-angle-right"></i> Programmes </a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){ ?>
                <li><a href="<?php echo site_url('module_list'); ?>"><i class="fa fa-angle-right"></i> Courses </a></li>
            <?php }

//            if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){  ?>
<!--                <li><a href="--><?php //echo site_url('session_list'); ?><!--"><i class="fa fa-angle-right"></i> Session List</a></li>-->
<!--            --><?php //}

            if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){?>
            <li><a href="<?php echo site_url('manage_subject'); ?>"><i class="fa fa-angle-right"></i> Secondary Subject List</a></li>
            <?php }

//            if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){?>
<!--                <li><a href="--><?php //echo site_url('certification_list'); ?><!--"><i class="fa fa-angle-right"></i> Certification List</a></li>-->
<!--            --><?php //}if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){?>
<!--                <li><a href="--><?php //echo site_url('studylevel_list'); ?><!--"><i class="fa fa-angle-right"></i> StudyLevel List</a></li>-->
<!--            --><?php //}?>

        </ul>
    </li>
<?php } if(has_section_role($MODULE_ID,$GROUP_ID,'SETTINGS')){ ?>

    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'settings' ? 'active':''):''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-tasks"></i> <span class="nav-label">Settings</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if(has_role($MODULE_ID,$GROUP_ID,'SETTINGS','manage_subject')){ ?>
                <li ><a href="<?php echo site_url('current_semester'); ?>"><i class="fa fa-angle-right"></i> Academic Year</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'SETTINGS','current_semester')){ ?>
                <li ><a href="<?php echo site_url('application_deadline'); ?>"><i class="fa fa-angle-right"></i> Application Deadline</a></li>
            <?php }

           if(has_role($MODULE_ID,$GROUP_ID,'SETTINGS','application_deadline')){ ?>
                <li ><a href="<?php echo site_url('payment_status'); ?>"><i class="fa fa-angle-right"></i> Payment Status</a></li>
            <?php }  ?>


        </ul>
    </li>
<?php }if (has_section_role($MODULE_ID, $GROUP_ID, 'CRITERIA')) { ?>
    <li class="<?php echo(isset($active_menu) ? ($active_menu == 'manage_criteria' ? 'active' : '') : 'active'); ?> ">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-cogs"></i> <span class="nav-label">Criteria</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if(has_role($MODULE_ID,$GROUP_ID,'CRITERIA','manage_criteria')){ ?>
                <li > <a href="<?php echo site_url('manage_criteria'); ?>"> <i class="fa fa-angle-right"></i> <span class="nav-label">Eligibility</span></a></li>
                <?php }if(has_role($MODULE_ID,$GROUP_ID,'CRITERIA','selection_criteria')){ ?>
                <li > <a href="<?php echo 'javascript:void(0);';//site_url('selection_criteria'); ?>"> <i class="fa fa-angle-right"></i> <span class="nav-label">Selection</span></a></li>
            <?php } ?>
        </ul>
    </li>
<?php } if(has_section_role($MODULE_ID,$GROUP_ID,'MANAGE_USERS')){ ?>

  <li class="<?php echo(isset($active_menu) ? ($active_menu == 'logs' ? 'active' : '') : 'active'); ?> ">
    <a href="<?php echo site_url('dashboard'); ?>">
        <i class="fa fa-institution"></i> <span class="nav-label">NACTE & NECTA API</span><span class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
        <li ><a href="<?php echo site_url('logs/api_issues'); ?>"><i class="fa fa-angle-right"></i> API Issues</a></li>
        <li ><a href="<?php echo site_url('logs/payments_log'); ?>"><i class="fa fa-angle-right"></i> Payments Logs List</a></li>

    </ul>
</li>


    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'manage_users' ? 'active':''):''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-users"></i> <span class="nav-label">Manage Users</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_USERS','create_group')){ ?>
                <li ><a href="<?php echo site_url('add_group'); ?>"><i class="fa fa-angle-right"></i> Add Users Group</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_USERS','group_list')){ ?>
                <li><a href="<?php echo site_url('group_list') ?>"><i class="fa fa-angle-right"></i> Users Group List</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,"MANAGE_USERS",'user_list')){ ?>
                <li><a href="<?php echo site_url('create_user') ?>"><i class="fa fa-angle-right"></i> New User Account</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_USERS','user_list')){ ?>
                <li><a href="<?php echo site_url('user_list') ?>"><i class="fa fa-angle-right"></i> Users List</a></li>
            <?php } ?>
        </ul>

    </li>
<?php }?>

<li class="<?php echo(isset($active_menu) ? ($active_menu == 'security' ? 'active' : '') : ''); ?>">
    <a href="<?php echo site_url(); ?>">
        <i class="fa fa-lock"></i> <span class="nav-label">Security</span> <span
                class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
        <li><a href="<?php echo site_url('login_history'); ?>"><i class="fa fa-angle-right"></i>Login History</a></li>
        <li><a href="<?php echo site_url('change_password'); ?>"><i class="fa fa-angle-right"></i>Change Password</a></li>
