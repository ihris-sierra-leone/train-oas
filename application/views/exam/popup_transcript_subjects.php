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
            ATTENDED EXAMINATION
        </div>

        <?php
        if($results) {
            $programme_id = $this->db->query("select * from students where registration_number='" . $APPLICANT->registration_number . "' ")->row()->programme_id;
            $programme = receive_programme_code($programme_id);
            $programmeid = $this->db->query("select * from programme where Name='$programme' ")->row()->id;
            $coursesno = $this->db->query("select count(id) as counter from courses where programme_id=$programmeid ")->row()->counter;

            foreach ($results as $key => $value) {
                ?>
                <i class="fa fa-angle-right"></i>&nbsp; <?php echo $this->common_model->get_course($value->course)->row()->name; ?>
            <?php }
            $examno = $this->db->query("select count(course) as counter from exam_results where registration_number='" . $APPLICANT->registration_number . "' ")->row()->counter;
            $remain = $coursesno - $examno;
            if ($remain <> 0) {
                ?>

                <h4>Remained</h4>
                <?php
                echo $remain;
                exit;
            }
        }

        ?>

    </div>


</div>

