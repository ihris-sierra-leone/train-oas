<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');

}
if (isset($_GET)) {
    $title1 = '';
    if (isset($_GET['key']) && $_GET['key'] <> '') {
        $title1 = " Searching Key :<strong> " . $_GET['key'] . '</strong>';
    }

    if ($title1 <> '') {
        echo '<div class="alert alert-warning">' . $title1 . '</div>';
    }

}

$has_access = has_role($MODULE_ID, 'create_programme', $GROUP_ID, 'SETTINGS');
?>

<div class="row">
    <div class="col-md-12">
        <di class="ibox">
            <div class="ibox-title clearfix">
                <h5> Course registered for Exams</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <?php
                    $uid = $CURRENT_USER->id;
                    $academic_year = $this->common_model->get_academic_year()->row()->AYear;
                    $regno = $this->db->query("SELECT * FROM students WHERE user_id='$uid'")->row()->registration_number;
                    $ql = $this->db->query("SELECT * FROM student_exam_registered WHERE registration_number='$regno' AND exam_year='$academic_year' ")->result();

                    ?>

                    <?php if ($ql) {
                        echo "<div class='alert alert-info'>If course name won't display remove the course and register again</div>";
                        ?>
                        <table cellspacing="0" cellpadding="0" class="table table-bordered table-responsive"
                               style="" id="selected_exam_list">
                            <thead>
                            <tr>
                                <th style="width:60px;">SNo</th>
                                <th style="width: 300px;">Name</th>
                                <th style="width: 100px;">Centre</th>
                                <th style="width: 150px;">Date</th>
                                <th style="width: 80px;">Time</th>
                                <th style="width: 80px;">Venue</th>
                                <th style="width: 80px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = $this->uri->segment(2) + 1;
                            foreach ($registered_list as $key1 => $value1) {
                                $cid = $value1->center_id;
                                $code = $value1->course_id;
                                ?>
                                <tr>
                                    <td style="vertical-align: middle; padding-left: 4px;"><?php echo $i++; ?></td>
                                    <td style="vertical-align: middle; padding-left: 4px;"><?php echo get_courses($value1->course_id); ?></td>
                                    <td style="vertical-align: middle; padding-left: 4px;"><?php echo get_centre($value1->center_id); ?></td>
                                    <?php
                                    $qe = "SELECT * FROM exam_register WHERE coursecode='$code'";
                                    $cd = $this->db->query($qe)->result();
                                    if ($cd) {
                                        foreach ($cd as $key) {

                                            ?>

                                            <td style="vertical-align: middle; padding-left: 4px;">
                                                <?php echo $key->exam_date; ?>
                                            </td>
                                            <td style="vertical-align: middle; padding-left: 4px;">
                                                <?php echo $key->time;
                                                ?>
                                            </td>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <td style="vertical-align: middle; padding-left: 4px;"></td>
                                        <td style="vertical-align: middle; padding-left: 4px;"></td>
                                        <?php
                                    }
                                    $sqe = "SELECT * FROM venue WHERE centre_id='$cid' ";
                                    $venueID = $this->db->query($sqe)->result();
                                    if ($venueID) {
                                        foreach ($venueID as $key => $venue) {
                                            ?>
                                            <td style="vertical-align: middle; padding-left: 4px;">
                                                <?php echo $venue->name; ?>
                                            </td>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <td style="vertical-align: middle; padding-left: 4px;"></td>
                                    <?php } ?>
                                    <td style="vertical-align: middle; padding-left: 4px;">
                                        <a href="<?php echo site_url('delete_course/?Code=' . $value1->course_id . '&iD=' . $value1->registration_number); ?>"
                                           class=""><i class="fa fa-times"></i>&nbsp;Remove</a>
                                    </td>
                                    <!--                                    <td> <a href=""> <i class="fa fa-pencil"></i>&nbsp;edit information</td>-->
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>

                        <?php
                        $student_informations = $this->db->query("select * from students where registration_number='$regno' ")->result();
                        foreach ($student_informations as $key => $applicant_value) {
                            echo '<a href="javascript:void(0);" style="display: block;" class="popup_student_exam_letter" ID="' . encode_id($applicant_value->id) . '"
                           title="' . $applicant_value->first_name . ' ' . $applicant_value->other_names . ' ' . $applicant_value->surname . '"><i class="fa fa-file"></i> Get examination letter</a>';
                        }


                    } else {
                        echo "<div class='alert alert-warning'>You have not registered for any of your courses</div>";
                    }

                    ?>
                    <div><?php //echo $pagination_links; ?>
                        <div style="clear: both;"></div>
                    </div>
                </div>
            </div>
    </div>
    </di>
</div>

<script>
    $(document).ready(function () {
        $("body").on("click", ".popup_student_exam_letter", function () {
            var ID = $(this).attr("ID");
            var title = $(this).attr("title");
            $.confirm({
                title: title,
                content: "URL:<?php echo site_url('popup_student_exam_letter') ?>/" + ID + '/?status=1',
                confirmButton: 'Print',
                columnClass: 'col-md-10 col-md-offset-2',
                cancelButton: 'Close',
                extraButton: 'ExtraB',
                cancelButtonClass: 'btn-success',
                confirmButtonClass: 'btn-success',
                confirm: function () {
                    window.location.href = '<?php echo site_url('print_student_exam_letter') ?>/' + ID;
                    return false;
                },
                cancel: function () {
                    return true;
                }

            });
        })
    });
</script>
