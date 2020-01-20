<?php if (has_section_role($MODULE_ID, $GROUP_ID, 'MANAGE_EXAMINATION')) { ?>

    <li class="<?php echo(isset($active_menu) ? ($active_menu == 'exam' ? 'active' : '') : ''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-file-excel-o"></i> <span class="nav-label">Examination</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_EXAMINATION', 'search')) { ?>
                <li><a href="<?php echo site_url('search'); ?>"><i class="fa fa-angle-right"></i>Search</a></li>
            <?php }
            if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_EXAMINATION', 'exam_fee')) { ?>
                <!-- <li ><a href="<?php //echo site_url('exam_fee'); ?>"><i class="fa fa-angle-right"></i> Exam fee</a></li> -->
            <?php }
            if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_EXAMINATION', 'grade_book')) { ?>
                <li><a href="<?php echo site_url('grade_book'); ?>"><i class="fa fa-angle-right"></i> Gradebook</a></li>
            <?php }
            if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_EXAMINATION', 'exam_registration')) { ?>
                <li><a href="<?php echo site_url('exam_registration') ?>"><i class="fa fa-angle-right"></i> Examination
                        Registration</a></li>
            <?php }
            if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_EXAMINATION', 'module_results')) { ?>
                <li><a href="<?php echo site_url('module_results'); ?>"><i class="fa fa-angle-right"></i> Module Results</a>
                </li>
            <?php } //if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_EXAMINATION','timetable')){ ?>
            <!-- <li><a href="<?php //echo site_url('timetable'); ?>"><i class="fa fa-angle-right"></i> Timetable</a></li> -->
            <?php //} 
//            if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_EXAMINATION', 'transcript_report')) { ?>
<!--                <li><a href="--><?php //echo site_url('transcript_report'); ?><!--"><i class="fa fa-angle-right"></i> Transcript-->
<!--                        report</a></li>-->
<!--            --><?php //}
            if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_EXAMINATION', 'board_report')) { ?>
                <li><a href="<?php echo site_url('board_report'); ?>"><i class="fa fa-angle-right"></i> Board Report</a>
                </li>
            <?php }
            if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_EXAMINATION', 'cumulative_report')) { ?>
                <!-- <li><a href="<?php // echo site_url('cumulative_report'); ?>"><i class="fa fa-angle-right"></i> Cumulative Results</a></li> -->
            <?php }
            if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_EXAMINATION', 'graduate_report')) { ?>
                <!-- <li><a href="<?php // echo site_url('graduate_report'); ?>"><i class="fa fa-angle-right"></i> Graduate Report</a></li> -->
            <?php } ?>
            <?php if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_EXAMINATION', 'graduate_report')) { ?>
                <li><a href="<?php echo site_url('publish'); ?>"><i class="fa fa-angle-right"></i> Publish Results</a>
                </li>
            <?php } ?>
            <?php if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_EXAMINATION', 'graduate_report')) { ?>
                <li><a href="<?php echo site_url('audit_trail'); ?>"><i class="fa fa-angle-right"></i> Audit Trail</a>
                </li>
            <?php } ?>
<!--            <li><a href="--><?php //echo site_url('student_exam_registered'); ?><!--"><i class="fa fa-angle-right"></i> Exam Registration</a>-->
<!--            </li>-->
            <li><a href="<?php echo site_url('exam_registration_list'); ?>"><i class="fa fa-angle-right"></i>Exam Registration list</a></li>


            <li><a href="<?php echo site_url('course_results'); ?>"><i class="fa fa-angle-right"></i> Course results</a>
            </li>

        </ul>
    </li>
<?php }
if (has_section_role($MODULE_ID, $GROUP_ID, 'MANAGE_TIMETABLE')) { ?>

    <li class="<?php echo(isset($active_menu) ? ($active_menu == 'timetable' ? 'active' : '') : ''); ?>">
        <a href="<?php echo site_url('dashboard'); ?>">
            <i class="fa fa-calendar"></i> <span class="nav-label">Timetable</span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <!-- <?php /*if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_TIMETABLE','venue_list')){ */ ?>
                <li ><a href="<?php /*echo site_url('venue_list'); */ ?>"><i class="fa fa-angle-right"></i> Register Venue</a></li>-->
            <?php if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_TIMETABLE', 'exam_list')) { ?>
                <li><a href="<?php echo site_url('exam_list') ?>"><i class="fa fa-angle-right"></i> Set Timetable</a>
                </li>
            <?php }
            if (has_role($MODULE_ID, $GROUP_ID, 'MANAGE_TIMETABLE', 'exam_list')) { ?>
                <li><a href="<?php echo site_url('attendance_list'); ?>"><i class="fa fa-angle-right"></i>Attendance
                        List</a></li>
            <?php } //if(has_role($MODULE_ID,$GROUP_ID,'MANAGE_TIMETABLE','exam_list')){ ?>
            <!-- <li><a href="<?php //echo site_url('packaging_list'); ?>"><i class="fa fa-angle-right"></i> Packaging List</a></li> -->
            <?php //}
            ?>

        </ul>
    </li>
<?php } ?>

<li class="<?php echo(isset($active_menu) ? ($active_menu == 'transcript' ? 'active' : '') : ''); ?>">
    <a href="<?php echo site_url('dashboard'); ?>">
        <i class="fa fa-file-archive-o"></i> <span class="nav-label">Transcript report</span> <span
                class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
        <li><a href="<?php echo site_url('transcript_report') ?>"><i class="fa fa-angle-right"></i>Transcript (Mks+Rmk) </a></li>
        <li><a href="<?php echo site_url('transcript_remark'); ?>"><i class="fa fa-angle-right"></i>Transcript (Rmrk)</a>
        </li>
<!--        <li><a href="--><?php //echo site_url('trancript_subjects'); ?><!--"><i class="fa fa-angle-right"></i>Transcript (Subjects)</a>-->
        </li>
    </ul>
</li>

<li class="<?php echo(isset($active_menu) ? ($active_menu == 'transcript' ? 'active' : '') : ''); ?>">
    <a href="<?php echo site_url('dashboard'); ?>">
        <i class="fa fa-file-archive-o"></i> <span class="nav-label">Candidate Report</span> <span
                class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
        <li><a href="<?php echo site_url('corporate_candidates') ?>"><i class="fa fa-angle-right"></i>Corporate candidates </a></li>
<!--        <li><a href="--><?php //echo site_url('transcript_remark'); ?><!--"><i class="fa fa-angle-right"></i>Transcript (Rmrk)</a>-->
        </li>
        <!--        <li><a href="--><?php //echo site_url('trancript_subjects'); ?><!--"><i class="fa fa-angle-right"></i>Transcript (Subjects)</a>-->
        </li>
    </ul>
</li>

<li class="<?php echo(isset($active_menu) ? ($active_menu == 'security' ? 'active' : '') : ''); ?>">
    <a href="<?php echo site_url(); ?>">
        <i class="fa fa-lock"></i> <span class="nav-label">Security</span> <span
                class="fa arrow"></span></a>
    <ul class="nav nav-second-level collapse">
        <li><a href="<?php echo site_url('login_history'); ?>"><i class="fa fa-angle-right"></i>Login History</a></li>
        <li><a href="<?php echo site_url('change_password'); ?>"><i class="fa fa-angle-right"></i>Change Password</a>
        </li>
