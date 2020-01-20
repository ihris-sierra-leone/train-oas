<div class="ibox">


    <div class="ibox-content">

        <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            Person Particulars
        </div>
        <div class="row">
            <div class="col-md-5">
                <table class="table">
                    <tr>
                        <th class="no-borders" style="width: 40%;">Reg No :</th>
                        <td class="no-borders"><?php echo $APPLICANT->registration_number; ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders" style="width: 40%;">First Name :</th>
                        <td class="no-borders"><?php echo $APPLICANT->first_name; ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Last Name :</th>
                        <td class="no-borders"><?php echo $APPLICANT->surname; ?></td>
                    </tr>
                    <tr>
                        <th class="no-borders">Other Name :</th>
                        <td class="no-borders"><?php echo $APPLICANT->other_names; ?></td>
                    </tr>


                </table>
            </div>

        </div>


        <div style="font-weight: bold; font-size: 15px; margin-bottom: 15px; color: brown; border-bottom: 1px solid brown">
            EXAMINATION REGISTERED
        </div>
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
            </tr>
            </thead>
            <tbody>
            <?php
            $academic_year = $this->common_model->get_academic_year()->row()->AYear;
            $exam_session = $this->common_model->get_academic_year()->row()->semester;
            $registered_list = $this->db->query("SELECT * FROM student_exam_registered WHERE registration_number='" . $APPLICANT->registration_number . "' AND exam_year='$academic_year' ")->result();

            $i = 1;
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
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

    </div>


</div>

