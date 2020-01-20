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
            EXAMINATION RESULTS
        </div>
        <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th style="width: 50px;">S/No</th>
                <th style="width: 150px;">Examination date</th>
                <th>Subject</th>
                <th style="width: 150px;">Marks</th>
                <th style="width: 150px;">Result</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($results as $key => $value) {
                if($value->published == 0){
                    ?>
                    <tr><td style="vertical-align: middle; text-align: center; padding-right: 5px;">
                        <a href="<?php echo site_url('edit_score/?code='.$value->course.'&reg='.$regno) ?>"></i><?php echo($key + 1) ?></a>
                    </td>
                <?php } else{ ?>
                    <td><?php echo $key+1 ?></td> <?php } ?>
                <td><?php echo $value->	academic_year.' '.$value->exam_session; ?></td>
                <td><?php echo $this->common_model->get_course($value->course)->row()->name; ?></td>
                <td><?php echo $value->score_marks; ?></td>
                <td><?php $score=0; $remark='';
                    $score = $value->score_marks;
                    if ($score <>'') {
                        if ($score >= 81) {
                            $remark = 'Distiction';
                        } elseif ($score >= 65) {
                            $remark = 'Credit';
                        } elseif ($score >= 51) {
                            $remark = 'Pass';
                        } else {
                            $remark = 'Fail';
                        }
                    }
                    echo $remark; ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

    </div>


</div>

