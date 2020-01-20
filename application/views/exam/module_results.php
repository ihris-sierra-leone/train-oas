<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Module Results</h5>

    </div>
    <div class="ibox-content">

        <?php if ($results) { ?>

            <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th style="width: 50px;">S/No</th>
                    <th style="width: 100px;">Year</th>
                    <th style="width: 100px;">Session</th>
                    <th>Subject</th>
                    <th style="width: 150px;">Score</th>
                    <th style="width: 150px;">Remark</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($results as $key => $value) {
                    ?>
                    <tr>
                        <td style="vertical-align: middle; text-align: center; padding-right: 5px;"><?php echo($key + 1) ?>
                        </td>
                        <td><?php echo $value->academic_year; ?></td>
                        <td><?php echo $value->exam_session; ?></td>
                        <td><?php echo $this->common_model->get_course($value->course)->row()->name; ?></td>
                        <td><?php echo $value->score_marks; ?></td>
                        <td><?php $score = 0;
                            $remark = '';
                            $score = $value->score_marks;
                            if ($score <> '') {
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

        <?php } else { ?>

            <div class="alert alert-warning">NO RESULT FOUND.</div>

        <?php } ?>


    </div>
</div>

<script>
    $(function () {
        $(".select2_search").select2({
            theme: "bootstrap",
            placeholder: " [ Select Principal ] ",
            allowClear: true
        });

    })
</script>