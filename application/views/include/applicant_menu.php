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
                <li ><a href="<?php echo site_url('centre_list'); ?>"><i class="fa fa-angle-right"></i> Centres List</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','department_list')){ ?>
                <li><a href="<?php echo site_url('department_list') ?>"><i class="fa fa-angle-right"></i> Department List</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){ ?>
                <li ><a href="<?php echo site_url('programme_list'); ?>"><i class="fa fa-angle-right"></i> Programme List</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){ ?>
                <li><a href="<?php echo site_url('module_list'); ?>"><i class="fa fa-angle-right"></i> Module List</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){  ?>
                <li><a href="<?php echo site_url('session_list'); ?>"><i class="fa fa-angle-right"></i> Session List</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){?>
                <li><a href="<?php echo site_url('manage_subject'); ?>"><i class="fa fa-angle-right"></i> Secondary Subject List</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){?>
                <li><a href="<?php echo site_url('certification_list'); ?>"><i class="fa fa-angle-right"></i> Certification List</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){?>
                <li><a href="<?php echo site_url('studylevel_list'); ?>"><i class="fa fa-angle-right"></i> StudyLevel List</a></li>
            <?php }?>
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
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'SETTINGS','application_deadline')){ ?>
                <li ><a href="<?php echo site_url('current_semester'); ?>"><i class="fa fa-angle-right"></i> Semester Session</a></li>
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
<?php }if (has_section_role($MODULE_ID, $GROUP_ID, 'APPLICANT')) { ?>
    <li class="<?php echo(isset($active_menu) ? ($active_menu == 'applicant_list' ? 'active' : '') : 'active'); ?> ">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-desktop"></i> <span class="nav-label">Applicant</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if(has_role($MODULE_ID,$GROUP_ID,'APPLICANT','applicant_list')){ ?>
                <li ><a href="<?php echo site_url('applicant_list'); ?>"><i class="fa fa-angle-right"></i> Applicant List</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'APPLICANT','short_listed')){ ?>
                <li><a href="<?php echo site_url('short_listed') ?>"><i class="fa fa-angle-right"></i>Applicant Short Listed</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'APPLICANT','applicant_selection')){ ?>
                <li><a href="<?php echo site_url('applicant_selection') ?>"><i class="fa fa-angle-right"></i>Applicant Select</a></li>
            <?php } ?>
        </ul>
    </li>
<?php } if (has_section_role($MODULE_ID, $GROUP_ID, 'APPLICANT')) { ?>
    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'collection' ? 'active':''):''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-cc-visa"></i> <span class="nav-label">Billing</span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if(has_role($MODULE_ID,$GROUP_ID,'APPLICANT','applicant_list')){ ?>
                <li ><a href="<?php echo site_url('collection'); ?>"><i class="fa fa-angle-right"></i> Fee Category</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'APPLICANT','short_listed')){ ?>
                <li><a href="<?php echo site_url('collection') ?>"><i class="fa fa-angle-right"></i>Fee Rate</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'APPLICANT','applicant_selection')){ ?>
                <li><a href="<?php echo site_url('collection') ?>"><i class="fa fa-angle-right"></i>Payment Statement</a></li>
            <?php } ?>
        </ul>
    </li>
<?php } ?>

<?php if(has_section_role($MODULE_ID,$GROUP_ID,'MANAGE_USERS')){ ?>

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
<?php } if(has_section_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS')){ ?>

    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'exam' ? 'active':''):''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-file-excel-o"></i> <span class="nav-label">Examination</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','school_list')){ ?>
                <li ><a href="<?php echo site_url('grade_book'); ?>"><i class="fa fa-angle-right"></i> Gradebook</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','department_list')){ ?>
                <li><a href="<?php echo site_url('exam_registration') ?>"><i class="fa fa-angle-right"></i> Examination Registration</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){ ?>
                <li ><a href="<?php echo site_url('module_results'); ?>"><i class="fa fa-angle-right"></i> Module Results</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){ ?>
                <li><a href="<?php echo site_url('timetable'); ?>"><i class="fa fa-angle-right"></i> Timetable</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){  ?>
                <li><a href="<?php echo site_url('transcript_report'); ?>"><i class="fa fa-angle-right"></i> Transcript report</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){?>
                <li><a href="<?php echo site_url('board_report'); ?>"><i class="fa fa-angle-right"></i> Board Report</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){?>
                <li><a href="<?php echo site_url('cumulative_report'); ?>"><i class="fa fa-angle-right"></i> Cumulative Results</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){?>
                <li><a href="<?php echo site_url('graduate_report'); ?>"><i class="fa fa-angle-right"></i> Graduate Report</a></li>
            <?php }?>
        </ul>
    </li>
<?php }if(has_section_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS')){ ?>

    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'member_process' ? 'active':''):''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-user-plus"></i> <span class="nav-label">Member Process</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','school_list')){ ?>
                <li ><a href="<?php echo site_url('profile'); ?>"><i class="fa fa-angle-right"></i> Profile</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','department_list')){ ?>
                <li><a href="<?php echo site_url('register') ?>"><i class="fa fa-angle-right"></i> Registration Form</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){ ?>
                <li ><a href="<?php echo site_url('import'); ?>"><i class="fa fa-angle-right"></i> Import Members</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){ ?>
                <li><a href="<?php echo site_url('exam_register'); ?>"><i class="fa fa-angle-right"></i> Exam Register</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){  ?>
                <li><a href="<?php echo site_url('exam_results'); ?>"><i class="fa fa-angle-right"></i> Exam Results</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){?>
                <li><a href="<?php echo site_url('billing'); ?>"><i class="fa fa-angle-right"></i> Billing</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){?>
                <li><a href="<?php echo site_url('suggestion_box'); ?>"><i class="fa fa-angle-right"></i> Suggestion Box</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){?>
                <li><a href="<?php echo site_url('security'); ?>"><i class="fa fa-angle-right"></i> Security</a></li>
            <?php }?>
        </ul>
    </li>
<?php }if(has_section_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS')){ ?>

    <li class="<?php echo (isset($active_menu) ? ($active_menu == 'timetable' ? 'active':''):''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-calendar"></i> <span class="nav-label">Timetable</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','school_list')){ ?>
                <li ><a href="<?php echo site_url('register_venue'); ?>"><i class="fa fa-angle-right"></i> Register Venue</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','department_list')){ ?>
                <li><a href="<?php echo site_url('register_event') ?>"><i class="fa fa-angle-right"></i> Register Event</a></li>
            <?php }if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){ ?>
                <li ><a href="<?php echo site_url('register_time'); ?>"><i class="fa fa-angle-right"></i> Register Time</a></li>
            <?php } if(has_role($MODULE_ID,$GROUP_ID,'DATA_FROM_SIMS','programme_list')){ ?>
                <li><a href="<?php echo site_url('register_calender'); ?>"><i class="fa fa-angle-right"></i> Register Calender</a></li>
            <?php }?>
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
