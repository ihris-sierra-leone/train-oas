<?php
if (isset($message)) {
    echo $message;
} else if ($this->session->flashdata('message') != '') {
    echo $this->session->flashdata('message');
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title clearfix">
        <h5>Billing</h5>

    </div>
    <div class="ibox-content">

        Hii Page inapatika ktk path hii views/member/billing.php
        <br/>
        Controller inayohusika ni Member na method ni billing()
        <br/>
        Model inayohusika Member_model

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