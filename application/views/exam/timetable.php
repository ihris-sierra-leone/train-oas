<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Timetable</h5>

    </div>
    <div class="ibox-content">

        Hii Page inapatika ktk path hii views/exam/timetable.php
        <br/>
        Controller inayohusika ni Exam na method ni timetable()
        <br/>
        Model inayohusika Exam_model

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