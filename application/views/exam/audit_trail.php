<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-content">
      <table cellpadding="0" cellspacing="0" class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <th style="width: 50px;">S/No</th>
                    <th style="width: 75px;">Year</th>
                    <th style="width: 100px;">Session</th>
                    <th style="width: 100px;">Member</th>
                    <th style="width: 430px;">Course</th>
                    <th style="width: 75px;">Before</th>
                    <th style="width: 75px;">After</th>
                    <th style="width: 225px;">Time</th>
                    <th style="width: 100px;">Actor</th>
                    <th style="width: 100px;">Action</th>
                </tr>
                </thead
                <tbody>
                <?php
                foreach ($exam_changes as $key => $value) {
                    $id=$this->db->query("SELECT id FROM courses WHERE code='$value->course'")->row()->id;
                    if(strtolower($value->exam_session) =="november session"){
                      $session = "November";
                    }else{
                        $session = "May";
                    }
                    ?>
                    <tr>
                        <td style="vertical-align: middle; text-align: right; padding-right: 5px;"><?php echo($key + 1) ?>.</td>
                        <td><?php echo $value->academic_year; ?></td>
                        <td><?php echo $session; ?></td>
                        <td><?php echo $value->registration_number; ?></td>
                        <td><?php echo get_courses($id) ?></td>
                        <td><?php echo $value->score_marks; ?></td>
                        <td><?php echo $value->score_marks_after; ?></td>
                        <td><?php echo $value->action_time; ?></td>
                        <td><?php echo $value->action_user; ?></td>
                        <td><?php echo $value->action_value; ?></td>
                    </tr>
                <?php
                 }
                ?>
                </tbody>

            </table>
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
