
<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
if(is_array($student_info) || is_object($student_info)) {
    foreach ($student_info as $key => $value) {
        $name = $value->first_name . ' ' . $value->other_names . ' ' . $value->surname;
        $regno= $value->registration_number;
//        echo $value->programme_id;
        $centre = get_centre($value->centre_id);
        $programme = receive_programme_code($value->programme_id);
//        $programme =   (isset($value->programme_id) ? ($value->programme_id <> 0 ? get_code($value->programme_id) : 'Null') : 'Null' );
    }
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Enter Registration Number to Search for a Student</h5>
    <?php echo form_open(site_url('search'), ' method="GET" class="form-horizontal ng-pristine ng-valid"') ?>
            <div class="col-md-1 pull-right no-padding">
                <input type="submit" value="Search" class="btn btn-success btn-sm">
            </div>
            <div class="col-md-5 pull-right ">
                <input type="text" value="<?php echo(isset($_GET['key']) ? $_GET['key'] : '') ?>" name="key"
                       class="form-control" placeholder="Search....">
            </div>
        </div>
        <?php
        echo form_close();

if(isset($student_info) && !empty($student_info) <> ''){
   echo  "<div class='ibox-content'>
        <h5>
          Registration Number : $regno  <br>
          Name : $name <br>
          Programme : $programme <br>
          
        </h5>";
   ?>

<!--    Centre   :   $centre <br>-->

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

        foreach($results as $key => $value) {
            if($value->published == 0){
            ?>
            <tr><td style="vertical-align: middle; text-align: center; padding-right: 5px;">
                    <a href="<?php echo site_url('edit_score/?code='.$value->course.'&reg='.$regno) ?>"></i><?php echo($key + 1) ?></a>
                </td>
                <?php } else{ ?>
                <td><?php echo $key+1 ?></td> <?php } ?>
                <td><?php echo $value->	academic_year; ?></td>
                <td><?php echo $value->exam_session; ?></td>
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
   <?php } ?>
</div>
</div>
<script>
    $(function(){
        $(".select2_search").select2({
            theme: "bootstrap",
            placeholder: " [ Select Principal ] ",
            allowClear: true
        });

    })
</script>

